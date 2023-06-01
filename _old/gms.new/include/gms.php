<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

//error_reporting(0);

//Configuration Hash
$config = array();

//Load All Available Classes Libraries
require_once(dirname(__FILE__). '/gmsLibrary.php');

//Start Timer
$tim = new Timer();
$tim->start();

//Global Gms Functions
require_once(dirname(__FILE__). '/gmsFunctions.php');

//Validate The Site Url
require_once(dirname(__FILE__). '/gmsSites.php');
	
//Load Core Component
require_once(dirname(__FILE__). '/gmsComponents.php');

//Load Site Configuration
require_once($config["physicalPath"] . 'sites/' . $config["site"] . '/config.php');

//Validate Secure Pages
if(!defined('_ADMIN_PROCCESS')) ValidateSecurePages();

//Open The Database Connection
$db = new sql_db($config["dbhost"], $config["dbuser"], $config["dbpasswd"], $config["dbname"], false);
if(!$db->connection) GoOffline("Could not connect to the database.");
/*
if(!$db->db_connect_id){
	GoOffline("Could not connect to the database.");
}
*/
if(!defined( '_VALID_FLASH_PROCCESS' )) $events->trigger( 'onInit');

//Load Authentication Object
@session_start();

$AutoLoginName = "";
$AutoLoginPassword = "";
$SesPrefix = (isset($config["ses_const"]) ? $config["ses_const"] : "");
if(!isset($_SESSION[$SesPrefix."frAuthenticate"]) && isset($_COOKIE["autologin"]) && $_COOKIE["autologin"] != "")
{
	$dr = $db->RowSelectorQuery("SELECT * FROM users WHERE user_name='" . $_COOKIE["autologin"] . "'");
	if(isset($dr["user_password"]))
	{
		$AutoLoginName = $dr["user_name"];
		$AutoLoginPassword = $dr["user_password"];
	}
}

$is_admin = defined('_ADMIN_PROCCESS') ? "adm" : "fr";
$is_admin = $SesPrefix.$is_admin;

$auth = null;	
if(isset($_SESSION[$is_admin . 'Authenticate']))
{
	$auth = $_SESSION[$is_admin . 'Authenticate'];
	$auth->Init($db);
}
else
{
	$auth = new GmsAuthenticate();
	$auth->Init($db);
	if($AutoLoginName != "" && $AutoLoginPassword != "") Login($AutoLoginName,$AutoLoginPassword);
}
	
if(!defined( '_VALID_FLASH_PROCCESS' ))
{
	$events->trigger( 'onAfterAuthenticationLoad');

	//Load Messages
	if(isset($_SESSION[$is_admin . 'msgs']) && !empty($_SESSION[$is_admin . 'msgs']))
	{
		$messages = $_SESSION[$is_admin . 'msgs'];
	}
	else
	{
		$messages = new Messages();
	}
}

//Load Core Languages
$mainLang = $config["physicalPath"] . 'gms/languages/' . $auth->LanguageCode . '.php';
if(file_exists($mainLang))  require_once($mainLang);

//Load Site Languages
$siteLangaugesPath = $config["physicalPath"] . 'sites/' . $config["site"] . '/languages/' . $auth->LanguageCode . '.php';
if(file_exists($siteLangaugesPath)) require_once($siteLangaugesPath);

if(!defined( '_VALID_FLASH_PROCCESS' ))
{
	//Load Site Theme	
	$siteThemePath = $config["physicalPath"] . 'sites/' . $config["site"] . '/theme.php';
	if(file_exists($siteThemePath)) include($siteThemePath);
		
	//Here Must Load The common theme if not custom theme loaded
	
	$events->trigger( 'onLoad' );

	if(defined('_ADMIN_PROCCESS'))
	{
		if(isAdminRole())
		{
			$components->LoadRequestComponent();
		}
	}
	else
	{
		//Load Content Page
		//$pages->LoadRequestPage();
		$components->LoadRequestComponent();
	}
	
	$events->trigger( 'onAfterRequestComponentLoad' );

	//Load Public Template
	initGzip();
	if(defined('_ADMIN_PROCCESS'))
	{
		if(isAdminRole())
		{
			include($config["physicalPath"] . (isset($config["adm_access_path"])?$config["adm_access_path"]:"admin") . '/admin.logon.php');
		}
		else
		{
			include($config["physicalPath"] . (isset($config["adm_access_path"])?$config["adm_access_path"]:"admin") . '/admin.login.php');
		}
	}
	else
	{
		//Load Site Template
		$siteTemplatePath = $config["physicalPath"] . 'sites/' . $config["site"] . '/template.php';
		if(file_exists($siteTemplatePath)) include($siteTemplatePath);
	}
	doGzip();
	
	$events->trigger( 'onUnload' );
	
	//Close The Database Connection
	$db->sql_close();
	
	//Write Authenticate To Session
	WriteAuthenticateToSession();
	$_SESSION[$is_admin . 'msgs'] = $messages;
	
	$tim->stop();
	echo "<!--" . $tim->getTime() . "-->";
}

function isAdminRole()
{
	global $config, $auth;
	return ($auth->UserType == "Administrator" || (isset($config["adm_access_roles"]) && in_array($auth->UserType, $config["adm_access_roles"])));
}

?>