<?

if (!get_magic_quotes_gpc())
{   
	foreach ($_REQUEST as $key => $value){ $_REQUEST[$key] = addslashes_array($_REQUEST[$key]); }
    foreach ($_GET as $key => $value) { $_GET[$key] = addslashes_array($_GET[$key]); }
	foreach ($_POST as $key => $value) { $_POST[$key] = addslashes_array($_POST[$key]); }
	foreach ($_COOKIE as $key => $value) { $_COOKIE[$key] = addslashes_array($_COOKIE[$key]); }
}

function addslashes_array($a)
{
	if(is_array($a))
	{
		foreach($a as $n=>$v) {
			$b[$n]=addslashes_array($v);
		}
		return $b;
	}
	else
	{
		return addslashes($a);
	}
}

$GET_ARRAY = array(
	"start"			=>	" - paging - "
	,"item"			=>	" - pages - "
	,"page_id"		=>	" - pages - "
	,"page"			=>	" - pages - "
	,"c"			=>	" - news categories, gallery categories, product categories - "
	,"id"			=>	" - news detail, products detail - "
	,"man"			=>	" - product manufacturer - "
	,"sp"			=>	" - product specials - "
	,"adr"			=>	" - eco address - "
	,"pr_add"		=>	" - eco add product - "
	,"qnt"			=>	" - eco add product quantity - "
	,"spec"			=>	" - eco prod spec - "
	,"pr_del"		=>	" - prod del favorite - "
	,"pr_add"		=>	" - prod add favorite - "
	,"show_poll"	=>	" - polls detail - "
	,"pr"			=>	" - suggestions detail - "
	,"linkRedir"	=>	" - banners - "
	
	,"cu"	=>	" - cultivations - "
	,"edit_id"	=>	" - farmer id - "
	,"f_id"	=>	" - field id - "
	
	,"geo" => "geo location"
	,"km" => "kilometer"
	,"width" => "resize width"
	,"height" => "resize height"
);


$POST_ARRAY = array(
	"__poll_"		=>	" - polls vote - "
);

$injvars = "";
foreach ($_GET as $key => $value)
{ 
	if(isset($GET_ARRAY[$key]) && $value != "")
	{
		if($value != strval(intval(trim($value))))
		{
			$injvars .= $key . "=>" . stripslashes  ($value);			
			unset($_GET[$key]); $_GET[$key] = intval($value);
		}
	}
}

if($injvars != "")
{
	$HTTP_HOST = $_SERVER['HTTP_HOST'];
	$PHP_SELF = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : " ??? ";
	$IP = (getenv("HTTP_X_FORWARDED_FOR")) ? getenv("HTTP_X_FORWARDED_FOR") : getenv("REMOTE_ADDR");
	$PHP_SELF_IP = $HTTP_HOST . " (" . $PHP_SELF . (isset($_SERVER['QUERY_STRING']) ? "?" . stripslashes(urldecode($_SERVER['QUERY_STRING'])) : "") . ")";				
	$DateTime=date("Y-m-d H:i:s", time());				
	$LogMsg = "DateTime: " . $DateTime . "<br>";
	$LogMsg .= "Host: " . $PHP_SELF_IP . "<br>";
	$LogMsg .= "Ip: " . $IP . "<br>";
	$LogMsg .= "Var: " . $injvars . "<br>";
	$LogMsg .= "Header: ";
	foreach (getHeaders() as $name => $value) {
		$LogMsg .=  "$name: $value, ";
	}
	//echo $LogMsg;
	$Headers  = "MIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\nFrom: Inj - " . $HTTP_HOST . " <inj@wan.gr>\r\n";
	//@mail("-","Injection: " . $HTTP_HOST, $LogMsg, $Headers);
	
	if(!isset($_SERVER["HTTP_X_FORWARDED_FOR"])) $_SERVER["HTTP_X_FORWARDED_FOR"] = "....";
	$allow = array("119.251.51.131");
	if(in_array($_SERVER['REMOTE_ADDR'], $allow) || in_array($_SERVER["HTTP_X_FORWARDED_FOR"], $allow)) {
		header("HTTP/1.1 503 Service Unavailable");
		echo "<h1>SQL injection</h1>";
		exit();
	} 
}

function getHeaders()
{
    $headers = array();
    foreach ($_SERVER as $k => $v)
    {
        if (substr($k, 0, 5) == "HTTP_")
        {
            $k = str_replace('_', ' ', substr($k, 5));
            $k = str_replace(' ', '-', ucwords(strtolower($k)));
            $headers[$k] = $v;
        }
    }
    return $headers;
}  

?>