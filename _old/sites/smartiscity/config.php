<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

$config["ajaxUrl"] = $config["siteurl"] . "/gms/remote/ajaxAgent.php";

//$config["SecurePages"] = array("checkout");

$config["dbhost"] = 'localhost';
$config["dbname"] = 'scities';
$config["dbuser"] = 'scities';
$config["dbpasswd"] = 'qwe#123!@#';
	
$config["errorLogFileName"] = 'errorLog.php';
$config["gzip_enabled"] = false;
$config["mailServer"] = "localhost";
$config["contactMail"] = "d.iordanidis@dotsoft.gr";
//$config["mailuser"] = '';
//$config["mailpassword"] = ''

$components->AddComponent("contact","contact","",custom_components_path_relative);

if(!function_exists("addslashes_array"))
{
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
}
//Automatic ExtJs Panel
$PHP_SELF = isset($_SERVER['PHP_SELF']) && !empty($_SERVER['PHP_SELF']) ? str_replace("index.php","",$_SERVER['PHP_SELF']) : "";

if(preg_match("/vpanel/", $PHP_SELF))
{
	if (!get_magic_quotes_gpc())
	{   
		foreach ($_REQUEST as $key => $value){ $_REQUEST[$key] = addslashes_array($_REQUEST[$key]); }
		foreach ($_GET as $key => $value) { $_GET[$key] = addslashes_array($_GET[$key]); }
		foreach ($_POST as $key => $value) { $_POST[$key] = addslashes_array($_POST[$key]); }
		foreach ($_COOKIE as $key => $value) { $_COOKIE[$key] = addslashes_array($_COOKIE[$key]); }
	}
	$config["enabled_ext"] = true;
	
	$tmp_comp = $components->Component;
	foreach($tmp_comp as $key=>$val)
	{
		$val[4] = true;
		$components->Component[$key] = $val;
	}
	
	$config["adm_access_path"] = "vpanel";
}
else 
{
	$config["enabled_jquery_msg"] = false;
}


$events->registerFunction("onAfterAuthenticationLoad","GlobalCalls");

function GlobalCalls()
{
	global $db,$auth,$config,$components;
	//echo 'usertype: '.$auth->UserType;
	//exit;
	if($auth->UserType != "")
	{
		$components->AddComponent("search","smartiscity","search",custom_components_path_relative);

		$components->AddComponent("regions","smartiscity","regions",custom_components_path_relative);
		$components->AddComponent("sensortypes","smartiscity","sensortypes",custom_components_path_relative);
		$components->AddComponent("sensorvars","smartiscity","sensorvars",custom_components_path_relative);
		$components->AddComponent("mysensors","smartiscity","mysensors",custom_components_path_relative);
		$components->AddComponent("notifications","smartiscity","notifications",custom_components_path_relative);
		$components->AddComponent("notificationslist","smartiscity","notificationslist",custom_components_path_relative);
		$components->AddComponent("messageslist","smartiscity","messageslist",custom_components_path_relative);
		$components->AddComponent("messages","smartiscity","messages",custom_components_path_relative);
		$components->AddComponent("preview","smartiscity","preview",custom_components_path_relative);
		$components->AddComponent("preview2","smartiscity","preview2",custom_components_path_relative);
		
		
		$components->AddComponent("users","user","users",custom_components_path_relative);
		$components->AddComponent("roles","user","roles",custom_components_path_relative);
		$components->AddComponent("users2","user","users2",custom_components_path_relative);
		$components->AddComponent("fpass","user","fpass",custom_components_path_relative);
		//$components->AddComponent("register","user","e_register",custom_components_path_relative);
		$components->AddComponent("profile","user","profile",custom_components_path_relative);

		
		//Support Section
		//$components->AddComponent("stickets","support","tickets",custom_components_path_relative);
		//$components->AddComponent("scategs","support","ticketscategs",custom_components_path_relative);
		$components->AddComponent("backup","tools","backup",custom_components_path_relative);
	}
	
	
	if(isset($_GET["captcha"]) && $_GET["captcha"] == "true")
	{
		$width = isset($_GET['width']) ? $_GET['width'] : '120';
		$height = isset($_GET['height']) ? $_GET['height'] : '40';
		$characters = isset($_GET['characters']) && $_GET['characters'] > 1 ? $_GET['characters'] : '6';
		$captcha = new Captcha($width,$height,$characters);
		exit;
	}
	
	else if(isset($_GET["resize"]) && $_GET["resize"] == "true" && isset($_GET["ph"]) && $_GET["ph"] != "")
	{
		if(isset($_GET["t"]))
		{
			$w = "";
			$h = "";
			if($_GET["t"] == 1)
			{
				$w = "90";
				$h = "60";
			}
			
			$p = $config["physicalPath"] . $_GET['ph'];
			if ($w != "" && $h != "" && file_exists($p))
			{
				setModifiedDate( $p ); // HTTP/1.0 304 Not Modified	
				$rimg = new RESIZEIMAGE($p);
				$rimg->resize_limitwh($w,$h);
				$rimg->close();
				exit;
			}
			else
			{
				header('HTTP/1.0 404 ');
				header('Content-Type: text/plain');
				die('File not found ');
			}
		}
	}
}

function formatNav($ret)
{
	$ret = str_replace("ά","α",$ret);
	$ret = str_replace("έ","ε",$ret);
	$ret = str_replace("ή","η",$ret);
	$ret = str_replace("ί","ι",$ret);
	$ret = str_replace("ϊ","ι",$ret);
	$ret = str_replace("ό","ο",$ret);
	$ret = str_replace("ώ","ω",$ret);
	return $ret;
}

function monthGen($month)
{
	if($month==1) $ret="Ιανουαρίου";
	if($month==2) $ret="Φεβρουαρίου";
	if($month==3) $ret="Μαρτίου";
	if($month==4) $ret="Απριλίου";
	if($month==5) $ret="Μαϊου";
	if($month==6) $ret="Ιουνίου";
	if($month==7) $ret="Ιουλίου";
	if($month==8) $ret="Αυγούστου";
	if($month==9) $ret="Σεπτεμβρίου";
	if($month==10) $ret="Οκτωβρίου";
	if($month==11) $ret="Νοεμβρίου";
	if($month==12) $ret="Δεκεμβρίου";
	return $ret;
}

	
	/*
	function showTime($totalSeconds){
		$seconds = intval($total_seconds%60); 
		$total_minutes = intval($total_seconds/60);
		$minutes = $total_minutes%60;
		return "$minutes:$seconds";
	}	
	*/

	
	function showTime($totalSeconds){
		$seconds = intval($totalSeconds%60); 
		$minutes = intval($totalSeconds/60);
		return sprintf('%02d', $minutes).':'.sprintf('%02d', $seconds);
	}

function GetNetwotkData($const)
{
	global $db, $auth, $components;
	
	$Network = array();
	
	$drLoc = $db->RowSelectorQuery("SELECT * FROM networks_spots WHERE const='" . $const . "'");
	if(isset($drLoc["l_id"]))
	{
		$drMun = $db->RowSelectorQuery("SELECT * FROM networks WHERE n_id='" . $drLoc["n_id"] . "'");
		if(isset($drMun["n_id"]))
		{
			$Network["NetworkName"] = $drMun["name"];
			$Network["SpotName"] = $drLoc["name"];
			$Network["comp_u_id"] = $drMun["comp_u_id"];
			$Network["n_id"] = $drMun["n_id"];
			$Network["l_id"] = $drLoc["l_id"];
			$Network["alive_h"] = $drMun["alive_h"];
			$Network["is_valid"] = $drMun["is_valid"];
			SetDefaultsCompany($Network, $drMun["comp_u_id"]);
		}
	}
 
	return $Network;
}

function seconds2human($ss) {
	//$s = $ss % 60; seconds
	$ss = $ss*60;
	$m = (floor(($ss%3600)/60)>0)?floor(($ss%3600)/60).'m':'';
	$h = (floor(($ss % 86400) / 3600)>0)?floor(($ss % 86400) / 3600).'h:':'';
	$d = (floor(($ss % 2592000) / 86400)>0)?floor(($ss % 2592000) / 86400).'d:':'';
	$M = (floor($ss / 2592000)>0)?floor($ss / 2592000).'M:':'';
	
	//return "$M months, $d days, $h hours, $m minutes, $s seconds";
	return "$M $d $h $m";
}

function randomCode($characters) 
{
	/* list all possible characters, similar looking characters and vowels have been removed */
	$possible = '23456789bcdfghjkmnpqrstvwxyz';
	$code = '';
	$i = 0;
	while ($i < $characters) { 
		$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
		$i++;
	}
	return $code;
}

?>
