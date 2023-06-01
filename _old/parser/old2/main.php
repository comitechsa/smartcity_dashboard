<?php
//check if target location specified
if (!isset($HTTP_GET_VARS['php_browser_uri']) || !$HTTP_GET_VARS['php_browser_uri']){
    header("Location: about:blank");
    die;
}

if (get_magic_quotes_gpc())
        $new_uri = stripslashes( urldecode( $HTTP_GET_VARS['php_browser_uri'] ));
else
        $new_uri = urldecode( $HTTP_GET_VARS['php_browser_uri'] );

//link up class definitions
require_once "URI.class.php";
require_once "GetUri.php";
require_once "ParseHtml.php";

//initialize object to work with HTTP protocol
$GetURI =& new GetUri($new_uri);

//follow redirects, get content type and other headers
$GetURI->uri_lookup();
check_errors();

//get URI after server redirects
$final_uri = $GetURI->URI->get_full_uri();

//get content type
$full_content_type = $GetURI->Response->content_type;
if ($semicolon_pos = strpos($full_content_type, ";")){
    $content_type = rtrim( substr( $full_content_type, 0, $semicolon_pos ) );
}else{
    $content_type = $full_content_type;
}

//set content type
header ("Content-type: $full_content_type");
//echo $content_type;

//check content type
switch (0){
  case strcasecmp("text/html", $content_type):
//  case strcasecmp("application/xhtml+xml", $content_type):
    $parse = true;
    $store_in_memory = true;
    break;
  default:
    $store_in_memory = false;
    $parse = false;
    break;
}

//get content of the URI
$content = "";
$GetURI->connect("fopen");
check_errors();
//binary-safe reading
$GetURI->link_read_method ("bytes");

if ($store_in_memory){
    while (!$GetURI->Response->eof_reached()){
        $content .= $GetURI->link_read();
    }
}else{
    while (!$GetURI->Response->eof_reached()){
        echo $GetURI->link_read();
    }
    die;
}

if ($parse){
    $HtmlParser =& new ParseHtml($content, $GetURI->URI);
    $content = $HtmlParser->html;
}
echo $content;


//check errors in GetUri
function check_errors(){
    global $GetURI, $new_uri;
    if ($GetURI->count_errors()){
        //errors found
        $output  = "Error occured while looking up $new_uri. \r\n";
        $output .= "<table border='0' width='100%'><tr><td><table border='1'>\r\n";
        foreach ($GetURI->errors as $error_name=>$error_description){
            $output .= "<tr><td>$error_name:</td><td>$error_description</td></tr>";
        }
        $output .= "</table></td></tr></table>\r\n";
        //dispaly errors and halt
        echo $output; 
        die;
    }
}

?>