<?php
/***********
* This script takes html retrieved from the remote server and changes all links
* so that it will lead to the script itself with original URL as parameter
*
*/
class ParseHtml{
var $tag_modifications = array ();//tags modifications
var $source_html;                 //page recieved from the web server
var $html = "";                   //html to display to user agent
var $URI;                         //object with parsed url of html page
//array of correspondence
var $tag_attributes = array("A" => "HREF",
                            "LINK" => "HREF",
                            "IMG" => "SRC",
                            "FORM" => "ACTION"
                            );

function ParseHtml($html, $URI_obj){
    global $HTTP_SERVER_VARS;
    $this->source_html = $html;
    $this->URI =& $URI_obj;
    $this->script_url = "http://".$HTTP_SERVER_VARS['HTTP_HOST'].$HTTP_SERVER_VARS["SCRIPT_NAME"];
    //offset - position of the parser
    $offset = 0;
    $done = false;
    while (!$done){
        //finding tag opening
        $tag_open_pos = strpos ($html, "<", $offset);
        if ($tag_open_pos === false){
            //no more tags - just adding the rest of the contents
            $this->html .= substr ($this->source_html, $offset);
            break;
        }
        //there is a tag opening smbol, add preceding page contents
        //(after prev. tag)
        $this->html .= substr ($this->source_html, $offset, $tag_open_pos-$offset);

        //moving the parse pointer to the beginning of the tag
        $offset = $tag_open_pos+1;

        //finding the position of ">" symbol
        $tag_close_pos = strpos ($html, ">", $offset);

        //if there is no closing ">", considering it's the last tag, process it 
        //and break the cycle
        //else move parse pointer behind the end of the tag and continue
        if ($tag_close_pos){
            //get the content of the tag to analyse
            $tag_content = trim (substr($html, $tag_open_pos+1, $tag_close_pos-$tag_open_pos-1));
            //change the tag, if needed
            if ($this->is_opening_tag("<$tag_content")){
                $new_tag = $this->change_tag ($tag_content);
            }else{
                $new_tag = $tag_content;
            }
            //add tag (changed or not) to the page
            $this->html .= "<". $new_tag . ">";
            //move page pointer behind the end of the tag
            $offset = $tag_close_pos+1;
        }else{
            $tag_content = trim (substr($html, $tag_open_pos+1));
            if ($this->is_opening_tag("<$tag_content")){
                $new_tag = $this->change_tag ($tag_content);
            }else{
                $new_tag = $tag_content;
            }
            $this->html .= "<". $new_tag . ">";
            break;
        }
        unset ($tag_open_pos, $tag_close_pos);
    }
}

function is_opening_tag($tag){
    return !preg_match("~^< */~", $tag);
}

//change tag attribute's value
//making the changes in the tags
function change_tag ($tag_content){
    //get tag name
    $space_position = strpos($tag_content, " ");
    if ($space_position){//tag has attributes
        $tag_name = substr($tag_content, 0, $space_position);
    }else return ($tag_content);

    //should we change this tag?
    if (!array_key_exists(strtoupper($tag_name), $this->tag_attributes)){
        return ($tag_content);
    }
    $attr_name = $this->tag_attributes[strtoupper($tag_name)];

    //get the value of the attribute
    //find symbol around the value (can be ', " or nothing) and split the tag
    $regexp = "/$attr_name\s*=\s*('|\")?.*/im";
    preg_match ($regexp, $tag_content, $found);
    if (isset($found[1])){
        $quote = $found[1];
        $regexp = "/(.*)$attr_name\s*=?\s*$quote([^$quote]*)$quote?\s?(.*)/is";
    }else{
        $regexp = "/(.*)$attr_name\s*=\s*(\S+)\s?(.*)/is";
    } 
    if (!preg_match ($regexp, $tag_content, $found))
        return $tag_content;
    $tag_part_before_attribute = $found[1];
    $attr_value = $found[2];
    $tag_part_after_attribute = $found[3];

    //convert relative address to absolute (if needed)
    $new_attr_value = $this->convert_relative_link($attr_value);
    //change the tag attribute value so the tag will point to the script,
    //and the original URL becomes the variable value passed to the script
    $new_attr_value = $this->link_url_to_script ($new_attr_value);
    
    //compiling new tag contentents
    //adding the beginning of the tag
    $new_tag_content = $tag_part_before_attribute;
    //adding new attribute
    $new_tag_content .= $attr_name. "=". '"'.$new_attr_value.'"';
    //adding the rest of the tag intact
    $new_tag_content .= $tag_part_after_attribute;
    
    return $new_tag_content;
}

// function recieves the URI from the tag attribute and checks if it is relative
// if so, function converts the URI to the absolute form 
// using data on current URI from the class variable (array) $url
function convert_relative_link ($relative_url){
    $regexp = "~^http://~i";
    if (preg_match($regexp, $relative_url)){
        return ($relative_url); // this is an absolute URL, no change needed
    }

    //attach relative link to the current URI's directory
    $new_path = dirname($this->URI->path)."/". $relative_url;
    //replace back and repetitive slashes with a single forward one
    $new_path = preg_replace ("~((\\\\+)|/){2,}~", "/", $new_path);
    //parse links to the upper directories
    if (strpos($new_path, "/../") !== false){
        $path_array = explode ("/", $new_path);
        foreach ($path_array as $key=>$value){
            if ($value == ".."){
                if ($key > 2){
                    unset ($path_array[$key-1]);
                }
                unset ($path_array[$key]);
            }
        }
        $new_path = implode ("/", $path_array);
    }
    //writing absolute url based on relative and base page addresses
    $absolute_url = $this->URI->scheme.$this->URI->user.$this->URI->pass.
        $this->URI->host.$this->URI->port_string.$new_path;
    return $absolute_url;
}

//url from the web page becomes the attribute passed to the script
function link_url_to_script ($url){
    global $HTTP_SERVER_VARS;   
    $script_url = "http://".$HTTP_SERVER_VARS['HTTP_HOST'].$HTTP_SERVER_VARS ['PHP_SELF'];
    
    //detect fragment in the target URI
    if ($fragment_pos = strpos($url, "#")){
        $fragment = substr($url, $fragment_pos);
    }else{
        $fragment ="";
    }
    //encode target URI to be passed as a parameter
    $point_to = urlencode($url);
    $full_new_url = $script_url."?php_browser_uri=".$point_to.$fragment;
    return $full_new_url;
}
//end of the class
}
?>