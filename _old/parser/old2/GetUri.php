<?php
/**************************************************************
*
* Package of classes GetUri can be used as an interface to communicate with 
* remote web-servers.
* There are folowing methods defined:
* GetUri(string) - class constructor, should get string with URI for initialization;
*
* string set_connection_type() - set connection type to keep-alive or close,
*       if no parameter specified, no changes is made
*       function returns previous setting or false on wrong parameter
*
* bool connect(string method) - establishes connection to the host, but if
*       there is already an open connection, function checks
*       if connection method (fopen or fsockopen) matches, if data was not 
*       transmitted, and may leave current connection
*       so you can call connect() many times, but it will perform connection
*       only when it needs to (keep-alive is supported but not implemented)
*
* bool reconnect(string method) - establishes connection to the host, but if
*       there is already an open connection, function closes it and opens a new
*       one
*
* bool disconnect(void) - closes connection to the remote host, false on error
*
* string get_link_opener(void) - returns the name of the function used to open
*       connection with the remote server
*
* void uri_lookup(void) - connects to the remote host, sends HTTP "HEAD"
*       request, receives Response headers, follows redirects and gets the final
*       URI's address and headers
*
* bool send_request([string type [,string connection]]) - sends HTTP request
*       type of the request can be "GET" (default), "POST", "HEAD";
*       connection can be "close" (default) or "keep-alive"
*
* bool get_headers(void) - read and parse Response headers
*       connect and send request prior to calling this method
*
* bool get_content(void) - read HTTP response
*       one should call connect() and send_request() before calling this method
*
* bool link_write(string data) - sends the data to the remote server
*
* string|false link_read([int limit]) - reads data sent by remote server
*       reading stops if limit (bytes) is reached
*       if reading method is "BYTES", default limit parameter is 512 bytes
*       if reading method is "STRINGS", reading stops with the end of line
*
* string link_read_method(string method) - define what function will be used to
*       read data from remote server
*       method can be "STRINGS" (default) or "BYTES"
*       note: in PHP versions prior 4.3 function fgets() ("STRINGS" method)
*       is not binary safe, so one should use "BYTES" to read binary data
*
* bool verbose([bool status]) - get and/or set the level of dubugging information
*       when true, all transmitted data is recorded in history field,
*       when false - only sent and received HTTP headers are.
*
* bool Request->register_get_var(string|array var, [string value]) - register 
*       variable to be sent by GET method to the remote host
*       can take array of strings or "name", "value" string pair as parameters
*
* bool Request->register_post_var(string|array var, [string value]) - register 
*       variable to be sent by POST method to the remote host
*       can take array of strings or "name", "value" string pair as parameters
*
* bool Request->add_custom_header(string header, [bool replace]) - register
*       custom header to be sent in HTTP request,
*       if replace is true, custom header will overwrite standart class header
*       with the same name, otherwise it will be appended
*
* string Request->get_request_string(string method[, string option]) - returns
*       HTTP request with custom headers and post data included
*
* array|false Response->find_header_by_name(string header_name) - use this 
*       method if you want to get value of a particular header(s). Reply is a 
*       numeric array of header values (without header name and colon ":").
*
* bool Response->eof_reached(void) - returns status of connection with remote host
*
**** Protected methods, don't call directly ****
* bool _host_socket_connect(void) - just opens a socket connection to the server
*       with address in $this->URI->host and writes the file pointer to
*       $this->link
*
* bool _host_file_connect(void) - opens a connection to the URI from
*        $this->URI->full using fopen() function
*/

include_once ("URI.class.php");
if (!defined("EOL"))define ("EOL", "\r\n");

// ******Settings*****
//limit the number of redirects the script will go through
if (!defined("REDIRECTS_LIMIT"))define ("REDIRECTS_LIMIT", 10);
//limit the number of HTTP headers the script will process before signalling 
//an error
if (!defined("HEADERS_LIMIT"))define ("HEADERS_LIMIT", 30);

//class definition
class GetUri{
var $link                   = NULL;    // file pointer to the remote host opened
                                       // by fsockopen() or fopen()
var $link_method            = "none";  // name of function that opened connection,
                                       // can be "fsockopen", "fopen" or other,
                                       // "none" if no connection
var $connection             = "close"; // connection type (for socket connection)
var $connections_count      = 0;       // counter of connections
var $proxy                  = false;   // address of the proxy to use or false
                                       // if use direct connection
var $proxy_port             = 3128;    // port of the proxy
var $uri_stream_read_method = "STRINGS"; // method to read data from URLI
                                       // "STRINGS" means using fgets(),
                                       // "BYTES" - fread()
var $Request;                          // object for generating request to remote server 
var $Response;                         // object that contains fields with server Response
var $errors;                           // description of errors
var $history                = array(); // history of transmitted data
var $verbose                = false;   // log in history only HTTP headers, 
                                       // not all transmitted data

//class constructor
function GetUri ($target_uri, $connection = "close"){
    //init service objects
    $this->URI =& new URI($target_uri);
    $this->Request =& new Request($this);
    $this->set_connection_type($connection);
//    $this->Response =& new Response($this);
    $this->history['request_headers'] = array();
    $this->history['response_headers'] = array();
    $this->history['sent_data'] = "";
    $this->history['received_data'] = "";
}

// connect to the remote host, send HTTP-request and receive Response headers,
// detect redirects, follow them  (reconnects to the new URI)
// and find the final URI with content or error
function uri_lookup (){
    $done = false;
    //starting loop for processing redirects
    $redirects_count = 0;
    while (!$done){
        //count number of redirects
        if ($redirects_count > REDIRECTS_LIMIT){
            $this->errors['common'] = "Server redirects limit reached";
            break;
        }
        //re-process the URI if we are folowing a redirect
        if ($redirects_count){
            $this->URI->process($uri);
        }
        //connect if not connected
        if (!$this->connect("fsockopen")){
            return false;
        }
        //send request
        if (!$this->Request->sent_status){
            $this->send_request("HEAD");
        }
        //get headers of the URI from the web-server
        if (!$this->get_headers()){
            return false;
        }
        //detect "Location" header
        if (list ($location) = $this->Response->find_header_by_name ("location")){
            $uri = $location;// redirect detected
            ++$redirects_count;
            continue;
        }
        switch ($this->Response->status_class){
          default:
              $this->errors['Response'] = $this->Response->status_code.": ".$this->Response->reason_phrase;
          case 2:
            if ($this->Response->status_code == 204){
                $this->errors['Response'] = "204: No content";
            }elseif (list ($content_type) = $this->Response->find_header_by_name ("Content-type")){
                $this->Response->content_type = $content_type;
            }
            $done = true;
            break;
          case 3:
            if ($this->Response->status_code == 300){
                $this->errors['Response'] = $this->Response->status_code.": ".$this->Response->reason_phrase;
            }else{
                $this->errors['headers'] = $this->Response->status_code." (".$this->Response->reason_phrase.") returned, but no location specified";
            }
                $done = true;
            break;
        }
    }
    if (!isset($this->errors)){
        return true;
    }else {
        return false;
    }
}

// set the type of connection - keep-alive or close (default)
// when no parameter specified current value is returned
// any parameter value other then "keep-alive" will be treated as "close"
function set_connection_type($connection = false){
    $old_connection = $this->connection;
    if ($connection){
        $connection = strtolower($connection);
        $this->connection = ($connection == "keep-alive")?"keep-alive":"close";
        $this->Request->connection = $this->connection;
    }
    return $old_connection;
}

// check current connection (URI "unchanged" status and connection method)
// connect to the host according to specified connection method
// or keep current connection
function connect($link_method){
    $new_method = strtolower($link_method);
    switch ($new_method){
        case "fopen":
            $method = "_host_file_connect";
            break;
        case "fsockopen":
            $method = "_host_socket_connect";
            break;
        default:
            $this->errors['connect'] = "Unrecognised connection method";
            return false;
    }
    //connect if we are not connected yet
    if (!$this->is_connected()){
        $is_connected = $this->$method();
    }//if we are connected using different method or request have changed - reconnect
    elseif ($this->get_connection_method() != $new_method || $this->URI->changed){
        $is_connected = $this->reconnect($new_method);
    }
    elseif($new_method == "fsockopen"){
        //was there any communication using current connection?
        if ($this->Request->sent_status){
            $is_connected = $this->reconnect("fsockopen");
        }else{
            //leave it connected
            $is_connected = true;
        }
    }else{//fopen connection, check if data was read
        if ($this->Response->body_reception_started){
            $is_connected = $this->reconnect("fopen");
        }else{
            $is_connected = true;
        }
    }
    return $is_connected;
}

// break the connection if it is already established
// and connect to the host accoreding to connection method
function reconnect($link_method){
    $link_method = strtolower ($link_method);
    switch ($link_method){
        case "fopen":
            $method = "_host_file_connect";
            break;
        case "fsockopen":
            $method = "_host_socket_connect";
            break;
        default:
            $this->errors['reconnect'] = "Unrecognised connection method";
            return false;
    }

    if ($this->is_connected()){
        $this->disconnect();
    }
    return $this->$method();
}

//function opens socket connection to the remote host
function _host_socket_connect (){
    if ($this->link){
        $this->errors['socket_connect'] = "Error opening connection: connection already exists";
        return false;
    }
    //for proxy implementation (in the future)
    if($this->proxy){
        $port = $this->proxy_port;
    }
    //connecting
    $host = $this->URI->host;
    $port = $this->URI->port;
    $urlp = fsockopen ($host, $port, $errnom, $errstring);
    //check errors
    if (!$urlp){
        $this->errors['connect'] = "Error connecting to ".$this->URI->get_full_uri()
        ."\tError is: $errnom $errstring";
        return false;
    }
    $this->link = $urlp;
    $this->link_method = "fsockopen";
    ++$this->connections_count;

    //init objects and set flags
    $this->Request =& new Request($this);
    $this->Response =& new Response($this);
    $this->URI->changed = false;
    $this->Request->sent = false;
    $this->Response->headers_received = false;
    $this->Response->body_received = false;

    return true;
}

//connect to the URI using URL fopen wrapper
function _host_file_connect (){
    if ($this->link){
        $this->errors['file_connect'] = "Error opening connection: connection already exists";
        return false;
    }

    $uri = $this->URI->get_full_uri();

    $urlp = @fopen ($uri, "r"); //open the connection
    if (!$urlp) {
        $this->errors['connect'] = "Could not connect to $url";
        return false;
    }
    $this->link = $urlp;
    $this->link_method = "fopen";
    ++$this->connections_count;
    $this->Request =& new Request($this);
    $this->Response =& new Response($this);
    $this->URI->changed = false;
    $this->Request->sent = true;
    $this->Response->headers_received = true;
    $this->Response->body_received = false;
    return true;
}

//close connection to the host
function disconnect(){
    if (is_resource($this->link)){
        $to_return = @fclose($this->link);
    }else{
        $to_return = false;
        $this->errors['connect'] = "Trying to close non-existing connection.";
    }
    $this->link = NULL;
    $this->link_method = "none";
    return $to_return;
}

//returns the name of the function that opened file pointer
function get_connection_method(){
    return $this->link_method;
}

//
function is_connected(){
    if($this->link_method != "none"){
        return true;
    }else{
        return false;
    }
}

//write to the connection stream
function link_write($data){
    if (!$this->link){//are we already connected to the remote host?
        $this->errors['stream'] = "Error writing to server: no connection established.";
        return false; // otherwise return from the function with error
    }elseif($this->get_connection_method() == "fopen"){
    //if the link is opened by fopen(), it is read-only and no writing is possible
        $this->errors['stream'] = "Read-only connection to remote host, can't send data.";
        return false;
    }
    //get length of data
    $length = strlen($data);
    //send the data
    $written = fwrite ($this->link, $data, $length);

    if ($written !== false){
        if ($this->verbose){
            $this->history['sent_data'] .= $data;
        }
        return true;
    }else{
        $this->errors['stream'] = "Could not send data to remote server.";
        return false;
    }
}

// This function sets the method of reading data from server.
// it returns the old state of $this->uri_stream_read_method
// Parameter to this function can be "STRINGS", so that fgets() will be used
// "BYTES", so fread() will be used
// if no parameter given, the current state is returned without change
// This is done because fgets() is not binary safe in PHP earlier than 4.3
// Function parameter is case-insensitive.
function link_read_method($method = "CURRENT"){
    $old_method = $this->uri_stream_read_method;
    $method = strtoupper($method);
    switch ($method) {
      case "STRINGS":
        $this->uri_stream_read_method = "STRINGS";
        break;
      case "BYTES":
        $this->uri_stream_read_method = "BYTES";
        break;
      case "CURRENT":
        break;
      default:
        $this->errors['stream'] = "Error in link_read_method(): Wrong parameter passed ($method)";
        return false;
    }
    return $old_method;
}

//this function is the interface for processing Response as a stream
function link_read($length = false){
    if (!$this->link){//are we already connected to the remote host?
        $this->errors['stream'] = "Error reading from server: no connection.";
        return false; // otherwise return from the function with error
    }
    //read data with appropriate function according to reading method
    switch ($this->uri_stream_read_method) {
      //read data line by line, but not binary safe in PHP before 4.3
      case "STRINGS":
        if ($length){
            $data = fgets($this->link, $length);
        }else{
            $data = fgets($this->link);
        }
        break;
      //read data by portions of fixed size (binary safe)
      case "BYTES":
        if (!$length){
            $length = 512;
        }
        $data = fread($this->link, $length);
        break;
    }
    //log data in history log
    if ($this->verbose){
        $this->history['received_data'] .= $data;
    }
    //detect end of file
    if (feof($this->link)){
        $this->Response->uri_stream_eof_reached = true;
    }elseif ($data === false){
        $this->errors['stream'] = "Error reading server Response";
        $this->Response->uri_stream_eof_reached = true;
    }
    //set Response flags
    if (!$this->Response->headers_received && !$this->Response->headers_reception_started){
        $this->Response->headers_reception_started = true;
    }elseif(!$this->Response->body_reception_started){
        $this->Response->body_reception_started = true;
    }
    return $data;
}

//send the request to the remote host
function send_request($request_method = "GET", $custom_request = false){
    $this->request_sent = false;
    //are we connected to the remote host and able to send data?
    switch ($this->get_connection_method()){
      case "none": //no connection
        $this->errors['request'] = "Error sending HTTP request - no connection to remote host.";
        return false;
      case "fsockopen": //OK, go on
        break;
      case "fopen"://error, connection is read-only
        $this->errors['request'] = "Error sending HTTP request - connection is read-only.";
        return false;
    }
    $request_method = strtoupper($request_method);
    //if one need custom request - send it
    if ($request_method == "CUSTOM"){
        $request = $custom_request;
    }else{
        //get request string
        $request = $this->Request->get_request_string($request_method, $this->connection);
    }
    //sending request
    if ($this->link_write($request)){
        $this->Request->sent_status = true;
        $this->history['request_headers'][] = $request;
        return true;
    }else{
        return false;
    }
}

/*this function gets the headers of the current URI
* Status-Line is recorded to $this->Response->headers['status-line']
* Status Code (3 digits) - to $this->Response->status_code
* class of the Status Code (1st digit if status code) - to $this->Response->status_class
* reason_phrase in $this->Response->reason_phrase
* rest of the headers are stored in 2-dimension array
* $this->Response->headers[int][name|value]
* where $this->Response->headers[1][name] contains the name of the 1st
* (after the status line) header
* $this->Response->headers[1][value] contains the value of the 1st header
*/
function get_headers(){
    //check connection
    if ($this->get_connection_method() == "fopen"){
        $this->errors['headers'] = "Error retrieving headers: connection is wrapped. Use \"fsockopen\" connection method to get HTTP headers.";
        return false;
    }
    //check Request and Response status
    if($this->Response->headers_received){
        $this->errors['headers'] = "Error retrieving headers: already received";
        return false;
    }elseif(!$this->Request->sent_status){
        $this->errors['headers'] = "Error retrieving headers: request to remote server was not sent";
        return false;
    }
    //read headers
    $header_start = true;
    $header_end = false;
    $header_eol = false;
    $i = $j =0; // just counters
    //set reading method to "STRINGS" - use fgets()
    $this->link_read_method("STRINGS");
    //init variable to store headers
    $headers = array ('raw'=> "");

    //process Response header in loop by lines
    while (!$header_end && !$this->Response->eof_reached()){
        //read line from a Response
        $data = $this->link_read(1024);

        //record raw headers data
        $headers['raw'] .= $data;

        // limit the number of Response lines the script will process
        // in the case of buggy server Response
        if (++$i > HEADERS_LIMIT){
            $this->errors['headers'] = "Too many headers from the server";
            return false;
        }

        //parse Response headers - find status line and parse all other headers

        if ($data == EOL){
            //empty line defines the end of HTTP header
            //process buggy server replies with EOLs in the start of the Response
            if ($header_start){
                continue;// skip EOLs in the beginning of the header
            }else{
                //not the beginning of the header, it's the end of the header
                $header_end = true;
            }
        }else{
        //the Response is not an empty line, let's process the header
            if ($header_start){
                //this must be a Response status-line
                //check if the Response was in HTTP header format and get Response status code
                $headers['status-line'] = $data;
                $regexp = "~^HTTP/\d.\d (\d{3}) (.*\S)~";
                if (!preg_match ($regexp, $data, $found)){
                    $this->errors["headers"] = "Bad remote host Response - not an HTTP header";
                    return false;
                }
                $this->Response->status_code = $found[1];
                $this->Response->status_class = substr($this->Response->status_code, 0, 1);
                $this->Response->reason_phrase = $found[2];
                if ($this->Response->status_class != 1){
                    $header_start = false; //next lines will be a response headers
                }
            }else{
            //split the header into array
                    $regexp = "/^(\S*):\s*(.*\S)/";
                if (preg_match ($regexp, $data, $found)){
                        $headers[++$j]['name'] = $found[1];
                        $headers[$j]['value'] = $found[2];
                }else{
                    //process multiple line headers
                    $regexp = "/^( |\t)*(.*\S)/";
                    if (preg_match($regexp, $data, $found)) {
                        $headers[$j]['value'] .= " ". $found[2];
                    }else{
                        // ignore bad header
                        continue;
                    }
                }
            }
        }
    }
    $this->Response->headers = $headers;
    $this->history['response_headers'][] = $headers['raw'];
    //set flag of received headers and return
    if(isset($this->Response->status_code)){
        $this->Response->headers_received = true;
        return true;
    }else{
        return false;
    }
}

//get content of the URI
function get_content(){
    //check connection
    if(!$this->is_connected()){
        $this->errors['get_uri_content'] = "Error getting reply body: no connection";
        return false;
    }
    //check if response body is not received yet
    if ($this->Response->body_received){
        $this->errors['get_uri_content'] = "Error getting reply body: already received";
        return false;
    }
    //process headers
    if (!$this->Response->headers_received){
        if (!$this->get_headers()){
            return false;
        }
    }
    //read response body using binary-safe method
    $this->link_read_method("bytes");
    while (!$this->Response->eof_reached()){
        $data = $this->link_read(1024);
        if ($data === false){
            return false;
        }else{
            $this->Response->body .= $data;
        }
    }
    return true;
}

// set log datalisation level or get current status
function verbose($status = "get current"){
    $old_status = $this->verbose;
    if ($status !== "get current"){
        $this->verbose = (bool) $status;
    }
    return $old_status;
}

//returns number of errors, occured while performing operations with the class
function count_errors(){
    return count ($this->errors);
}

//end of the class
}



//**************************************************************//
// class represents the request sent to the remote server
class Request{
var $URI;
var $method                 = "GET";   // request type (GET, POST, HEAD, OPTIONS)
var $Request_Line           = "";
var $headers;                          // array or headers to send
var $custom_headers         = array(); // array or custom headers
var $body                   = "";      // request message body
var $sent_status            = false;   // status of the request
var $post_data              = "";      // data to send with POST request
var $raw_sent_data          = "";      // all data sent to the remote server
                                       // useng this "package" of classes

function Request(&$GetUri){
    $this->GetUri =& $GetUri;
    $this->URI =& $GetUri->URI;
    return true;
}

//use this function to prepare data to send with POST request
function register_get_var($var, $var_value = ""){
    //check parameters
    if (!$var){
        $this->GetUri->errors['register_GET_var'] = "Error registering query for GET request: no variable name specified.";
        return false;
    }
    if (is_array($var)){
        foreach ($var as $key=>$value) {
            if (!is_string($key) || !is_string($value)){
                $this->GetUri->errors['register_GET_var'] = "Error registering query for GET request: invalid parameter type.";
            }else{
                if ($this->URI->query){
                    $this->URI->query .= "&";
                }
                $this->post_data .= rawurlencode($key). "=" .rawurlencode($value);
            }
        }
    }elseif (is_string($var) && is_string($var_value)){
        if ($this->URI->query){
            $this->URI->query .= "&";
        }
        $this->URI->query .= urlencode($var)."=".urlencode($var_value);
    }else{
        $this->GetUri->errors['register_GET_var'] = "Error registering query for GET request: invalid parameter type.";
        return false;//wrong parameter type
    }
    return true;
}
//use this function to prepare data to send with POST request
function register_post_var($var, $var_value = ""){
    //check parameters
    if (!$var){
        $this->GetUri->errors['register_POST_var'] = "Error registering data for POST request - no variable name specified.";
        return false;
    }
    if (is_array($var)){
        foreach ($var as $k=>$v) {
            if($this->post_data) {
                $this->post_data .= "&";
            }
            $this->post_data .= rawurlencode($k). "=" .rawurlencode($v);
        }
    }elseif (is_string ($var)){
        if ($this->post_data){
            $this->post_data .= "&";
        }
        $this->post_data .= rawurlencode($var)."=".rawurlencode($var_value);
    }else{
        $this->GetUri->errors['register_POST_var'] = "Error registering query for POST request: invalid parameter type.";
        return false;
    }
    return true;
}

//add custom request header
function add_custom_header($header, $replace = false){
    static $index;
    if (!isset ($index)){
        $index = 0;
    }
    $this->custom_headers[$index]['value'] = $header;
    $this->custom_headers[$index]['replace'] = $replace;
}

//returns the request string to send to server
function get_request_string($request_method, $optional_parameter = null){

    //method is case-insensitive
    $request_method = strtoupper($request_method);

    //creating HTTP request
    $host = $this->URI->host;
    $path = $this->URI->path.$this->URI->query;
    switch ($request_method) {
      case "GET":
      case "HEAD":
      case "TRACE":
        $this->Request_Line = "$request_method $path HTTP/1.1";
        $this->headers["Host"] = "$host";
        $this->headers["User-Agent"] = "PHP script for HTTP protocol";
        $this->headers["Connection"] = $this->GetUri->connection;
        break;
      case "OPTIONS":
        if ($optional_parameter == "*"){
            $this->Request_Line = "$request_method * HTTP/1.1";
        }else{
            $this->Request_Line = "$request_method $path HTTP/1.1";
        }
        $this->headers["Host"] = "$host";
        $this->headers["User-Agent"] = "PHP script for HTTP protocol";
        $this->headers["Connection"] = $this->GetUri->connection;
        break;
      case "POST":
        $this->post_data = trim($this->post_data);
        $this->Request_Line = "$request_method $path HTTP/1.1";
        $this->headers["Host"] = "$host";
        $this->headers["User-Agent"] = "PHP script for HTTP protocol";
        $this->headers["Connection"] = $this->GetUri->connection;
        $this->headers["Accept"] = "*/*";
        $this->headers["Content-type"] = "application/x-www-form-urlencoded";
        $this->headers["Content-length"] = strlen($this->post_data);
        $this->body = $this->post_data."\r\n\r\n";
        break;
      default:
        $this->GetUri->errors['erquest'] = "Error sending request - wrong request method.";
    }
    //include custom headers
    $custom_headers_append = "";
    foreach ($this->custom_headers as $custom_header){
        //replace headers if custom headers are registered for replace
        if ($custom_header['replace']){
            $colon_pos = strpos($custom_header['value'], ":");
            if (!$colon_pos) {
                $this->URI->errors['headers'] = "Wrong custom header, skipping";
                continue;// skip incorrect header
            }
            //split custom headers name and value
            $custom_header_name = substr($custom_header['value'], 0, $colon_pos);
            $custom_header_value = substr($custom_header['value'], $colon_pos+1);
            //search for matched headers
            $match = false;
            foreach ($this->headers as $header_name->$header_value){
                if (!strcasecmp($header_name, $custom_header_name)){
                    //found
                    $this->headers[$header_name] = trim($custom_header_value);
                    $match = true;
                    break;
                }
            }
            if(!$match){
                $this->headers[$custom_header_name] = trim($custom_header_value);
            }
        }else{
            //don't replace, just add this header
            $custom_headers_append .= trim($custom_header['value'])."\r\n";
        }
    }
    //compile request string
    $http_request = $this->Request_Line."\r\n";
    foreach ($this->headers as $name=>$value){
        $http_request .= "$name: $value\r\n";
    }
    //add custom headers
    $http_request .= $custom_headers_append;
    //finish headers
    $http_request .= "\r\n";
    //add request body
    $http_request .= $this->body;

    return $http_request;
}
//end of the class
}

//**************************************************************//
// class represents the reply resieved from the remote server
class Response{
var $headers                    = array();
var $body                       = "";

var $headers_reception_started  = false;   // Response headers receiving flag
var $body_reception_started     = false;   // Response body recieving flag [true|false]

var $headers_received           = false;   // Response headers received flag [true|false]
var $body_received              = false;   // Response body recieving flag [true|false]
var $status_code;
var $status_class;
var $reason_phrase;
var $content_type;
var $uri_stream_eof_reached     = false;

function Response(&$GetUri){
    $this->GetUri =& $GetUri;
    return true;
}

//get header values by header name
function find_header_by_name($header_name){
    if (!$this->headers_received){
        $this->GetUri->errors[] = "Error looking up header: headers have not been received yet";
        return false;
    }
    $Response = false;

    foreach ($this->headers as $header){
            if (!strcasecmp($header['name'], $header_name))
                $Response [] = $header['value'];
    }
    return $Response;
}
//get eof status of stream
function eof_reached(){
    if ($this->uri_stream_eof_reached || !is_resource($this->GetUri->link))
        return true;
    else
        return false;
}


//end of the class
}
?>