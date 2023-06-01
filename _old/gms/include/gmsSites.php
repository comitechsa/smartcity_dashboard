<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

$this_File = __FILE__;
$this_File = str_replace("\\","/",$this_File);
$config["physicalPath"] = str_replace("gms/include/gmsSites.php","",$this_File);

$HTTP_HOST = $_SERVER['HTTP_HOST'];
$PHP_SELF = isset($_SERVER['PHP_SELF']) && !empty($_SERVER['PHP_SELF']) ? str_replace("index.php","",$_SERVER['PHP_SELF']) : "";

//$url_stuff = parse_url($HTTP_HOST); 
if ( strpos($HTTP_HOST,':') !== false ) 
{
	$HTTP_HOST_AR = split(":",$HTTP_HOST);
	$HTTP_HOST = $HTTP_HOST_AR[0];
}

//$Standard_Path = $HTTP_HOST . ($_SERVER['SERVER_PORT'] != "80" ? ":" . $_SERVER['SERVER_PORT'] : "") . $PHP_SELF;
$Standard_Path = $HTTP_HOST . $PHP_SELF;
$SiteFound = false;

foreach($sites as $key=>$value)
{
	if(preg_match("/". str_replace("/","_",$key) . "/", str_replace("/","_",$Standard_Path)))
	{
		//$config["realurl"] = 'https://' . $Standard_Path;
		//$config["siteurl"] = 'https://' . $key;
		$config["realurl"] = 'https://' . $Standard_Path;
		$config["siteurl"] = 'https://' . $key;
		$config["site"] = $value;
		$SiteFound = true;
		break;
	}
}

if(!$SiteFound)
{
	GoOffline("Please try again later.");
}

define('core_components_path',$config["physicalPath"] . "gms/components/");
define('custom_components_path',$config["physicalPath"] . "sites/" . $config["site"]. "/components/");
define('custom_components_path_relative',"sites/" . $config["site"]. "/components/");

?>