<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

if (!isset($_SERVER['REQUEST_URI']))   
{   
	$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
    if (isset($_SERVER['QUERY_STRING'])) { $_SERVER['REQUEST_URI'].='?'.$_SERVER['QUERY_STRING']; }   
}

function WriteAuthenticateToSession()
{
	global $auth,$is_admin,$messages;
	$_SESSION[$is_admin . 'Authenticate'] = $auth;
	$_SESSION['auth'] = $auth;
	$_SESSION[$is_admin . 'msgs'] = $messages;
}

function GoOffline($msg)
{
	global $config;
	$error_Message = $msg;
	include($config["physicalPath"] . "error.php");
	exit();
}	


function LoginSocial($id, $email, $name, $userAuth = "Register", $network="Social")
{
//LoginSocial($_SESSION["userprofile"]['id'],$_SESSION["userprofile"]['email'],$_SESSION["userprofile"]['name'], "Register");
	global $db,$auth;
	$ValidUser = false;
/*
	$query = "SELECT user_id FROM users where social_id = " . $id . " AND email = '".$email."'";

	$dr = $db->RowSelectorQuery($query);
	if(isset($dr['user_id'])) {
		$query = "UPDATE users SET network_type='".$_SESSION['app']."'last_login='" . date('Y-m-d H:i:s') . "' WHERE user_id = " . $dr["user_id"];
		$db->sql_query($query);
		
	} else {
		$query = "INSERT INTO users (user_auth, user_name, user_fullname, email, date_insert, last_login, is_valid,network_type,social_id)
		VALUES ('Register', '".$email."', '".$name."','".$email."','".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."','True','".$_SESSION['app']."','".$id."')";
		$db->sql_query($query);
		$query = "SELECT user_id FROM users where social_id = " . $id . " AND email = '".$email."'";
		$dr = $db->RowSelectorQuery($query);
		//$userID = $db->sql_query($query);
	}
	
		$auth->UserType = "Register";
		$auth->UserId = $dr["user_id"];
		$auth->UserRow = $dr;
	
		WriteAuthenticateToSession();
		$ValidUser = true;
		
		//$query = "UPDATE users SET last_login='" . date('Y-m-d H:i:s') . "' WHERE user_id = " . $dr["user_id"];
		//$db->sql_query($query);

	//$db->sql_freeresult($result);
*/
	return $ValidUser;
}


function Login($userName, $userPassword, $userAuth = "Register", $UseEmail=false)
{
	global $db,$auth;
	$ValidUser = false;
	
	if(is_array($userAuth))
	{
		$query = "SELECT * FROM users where " . ($UseEmail?"email='$userName'":"user_name='$userName' ") . " and user_password='$userPassword' and is_valid='True'";
	}
	else
	{
		$query = "SELECT * FROM users where " . ($UseEmail?"email='$userName'":"user_name='$userName' ") . " and user_password='$userPassword' and is_valid='True' and user_auth='$userAuth'";
	}
	
	$result = $db->sql_query($query);
	if($dr = $db->sql_fetchrow($result)){
		$auth->UserType = $dr["user_auth"];
		$auth->UserId = $dr["user_id"];
		$auth->UserRow = $dr;		
		if(is_array($userAuth))
		{
			if(!isAdminRole())
			{
				$auth->UserType = '';
				$auth->UserId = '';
				$auth->UserRow = '';
				return false;
			}							 
		}
		
		WriteAuthenticateToSession();
		$ValidUser = true;
		
		$query = "UPDATE users SET last_login='" . date('Y-m-d H:i:s') . "' WHERE user_id = " . $dr["user_id"];
		$db->sql_query($query);
	}
	
	$db->sql_freeresult($result);
	//print_r($_SESSION['frAuthenticate']);
	//exit;
	return $ValidUser;
}

function Logout()
{
	//social session_destroy();
	unset($_SESSION['userprofile']);
	unset($_SESSION['app']);
	
	unset($_SESSION['ssa_return_url']);
	unset($_SESSION['access_token']);
	unset($_SESSION['oauth_token']);
	unset($_SESSION['oauth_token_secret']);
	unset($_SESSION['authstation']);
	unset($_SESSION['status']);
	//end social session_destroy();

	global $auth;
	$auth->UserType = '';
	$auth->UserId = '';
	$auth->UserRow = '';
	setcookie("gms_arm", "", time()-3600);
	WriteAuthenticateToSession();
}


function LoadNoCacheHeader()
{
	header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
	header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
	header( 'Cache-Control: no-store, no-cache, must-revalidate' );
	header( 'Cache-Control: post-check=0, pre-check=0', false );
	header( 'Pragma: no-cache' );
}


function LoadCharSetHeader()
{
	global $auth;
	header( 'Content-Type: text/html; charset=' . $auth->LanguageCharset );
}


function Redirect( $url, $msg='' ) {

	// specific filters
	$iFilter = new InputFilter();

	$url = $iFilter->process( $url );
			
	if (!empty($msg)) {
		$msg = $iFilter->process( $msg );
	}
	if ($iFilter->badAttributeValue( array( 'href', $url ))) {
		$url = "index.php";
	}

	if (trim( $msg )) {
	 	if (strpos( $url, '?' )) {
			$url .= '&msg=' . urlencode( $msg );
		} else {
			$url .= '?msg=' . urlencode( $msg );
		}
	}

	if (headers_sent()) {
		echo "<script>document.location.href='$url';</script>\n";
	} else {
		@ob_end_clean(); // clear output buffer
		header( 'HTTP/1.1 301 Moved Permanently' );
		header( "Location: $url" );
	}
	WriteAuthenticateToSession();
	//@session_write_close();
	exit();
}


function Paging($base_url, $num_items, $per_page, $start_item, $prefix = "")
{
	$txt = '';

	$displayed_pages = 10;
	
	$total_pages = ceil($num_items/$per_page); 
	$this_page = ceil( ($start_item+1) / $per_page );
	
	$start_loop = (floor(($this_page-1)/$displayed_pages))*$displayed_pages+1;
	if ($start_loop + $displayed_pages - 1 < $total_pages) {
		$stop_loop = $start_loop + $displayed_pages - 1;
	} else {
		$stop_loop = $total_pages;
	}
	
	if($total_pages > 1)
	{
		$link = $base_url . "&amp;" . $prefix . "start";
		if(!defined('_PN_START'))
		{
			define("_PN_START","&lt;&lt;");
			define("_PN_PREVIOUS","&lt;");
			define("_PN_NEXT","&gt;");
			define("_PN_END","&gt;&gt;");
		}
		
		if ($this_page > 1) {
			$page = ($this_page - 2) * $per_page;
			$txt .= '<a href="' . $link . '=0" title="first page" class="navigator">'. _PN_START .'</a> ';
			$txt .= '<a href="' . $link . '=' . $page . '" class="navigator" title="previous page">'. _PN_PREVIOUS .'</a> ';
		} else {
			$txt .= '<span class="navigator">'. _PN_START .'</span> ';
			$txt .= '<span class="navigator">'. _PN_PREVIOUS .'</span> ';
		}
	
		for ($i=$start_loop; $i <= $stop_loop; $i++) {
			$page = ($i - 1) * $per_page;
			if ($i == $this_page) {
				$txt .= '<span class="navigator">'. $i .'</span> ';
			} else {
				$txt .= '<a href="' . $link . '='. $page .'" class="navigator"><strong>'. $i .'</strong></a> ';
			}
		}
	
		if ($this_page < $total_pages) {
			$page = $this_page * $per_page;
			$end_page = ($total_pages-1) * $per_page;
			$txt .= '<a href="'. $link .'='. $page .' " class="navigator" title="next page">'. _PN_NEXT .'</a> ';
			$txt .= '<a href="'. $link .'='. $end_page .' " class="navigator" title="end page">'. _PN_END .'</a>';
		} else {
			$txt .= '<span class="navigator">'. _PN_NEXT .'</span> ';
			$txt .= '<span class="navigator">'. _PN_END .'</span>';
		}
		return $txt;
	}
}

function makePassword($length = 8, $onlyDigits = false, $start_prefix = "", $end_prefix = "") {
	$salt 		= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	if($onlyDigits) $salt 		= "0123456789";
	
	$len 		= strlen($salt);
	$makepass	= '';
	mt_srand(10000000*(double)microtime());
	for ($i = 0; $i < $length; $i++)
		$makepass .= $salt[mt_rand(0,$len - 1)];

	return ($start_prefix != "" ? $start_prefix . "_" : "") . $makepass . ($end_prefix != "" ? "_" . $end_prefix : "");
}

function getParam( $param, $isInt = false, $default = "") 
{
	if($param != "")
	{
		if($isInt)
		{
			$param = intval($param);
		}
		else
		{
			if (!get_magic_quotes_gpc()) 
			{
				$param = addslashes($param);
			}
		}
	}
	else
	{
		$param = $default;
	}
	
	return $param;
}

function initGzip() {
	global $config;
	
	if ($config["gzip_enabled"]) 
	{
		$config["gzip_enabled"] = false;
		$phpver = phpversion();
		$useragent = getParam( $_SERVER, 'HTTP_USER_AGENT', '' );
		$canZip = getParam( $_SERVER, 'HTTP_ACCEPT_ENCODING', '' );

		if ( $phpver >= '4.0.4pl1' &&
				( strpos($useragent,'compatible') !== false ||
				  strpos($useragent,'Gecko')	  !== false
				)
			) {
			if ( extension_loaded('zlib') ) {
				ob_start( 'ob_gzhandler' );
				return;
				
			}
		} else if ( $phpver > '4.0' ) {
			if ( strpos($canZip,'gzip') !== false ) {
				if (extension_loaded( 'zlib' )) {
					$config["gzip_enabled"] = true;
					ob_start();
					ob_implicit_flush(0);
					return;
				}
			}
		}
	}
	ob_start();
}

function doGzip() {
	global $config;
	if ($config["gzip_enabled"] && extension_loaded( 'zlib' )) {
		/**
		*Borrowed from php.net!
		*/
		$gzip_contents = ob_get_contents();
		$gzip_size = strlen($gzip_contents);
		@ob_end_clean();

		header( 'Content-Encoding: gzip' );
		//header( 'Content-Type: text/html; charset=UTF-8');		
		
		echo "\x1f\x8b\x08\x00\x00\x00\x00\x00" . substr(gzcompress($gzip_contents, 7), 0, - 4), // substr -4 isn't needed 
        pack('V', crc32($gzip_contents)),    // crc32 and 
        pack('V', $gzip_size);               // size are ignored by all the browsers i have tested 

	} else {
		
		if(function_exists("MinifyHTML"))
		{
			$contents = ob_get_contents();
			@ob_end_clean();
			echo MinifyHTML($contents);
		}
		else
		{
			@ob_end_flush();
		}
	}
}

function LogError($ErrorMessage, $file, $line, $type = "PHP")
{
	global $config;
	if(isset($config["errorLogFileName"]) && $config["errorLogFileName"] != "")
	{
		$HTTP_HOST = $_SERVER['HTTP_HOST'];
		$PHP_SELF = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : " ??? ";
		$IP = (getenv("HTTP_X_FORWARDED_FOR")) ? getenv("HTTP_X_FORWARDED_FOR") : getenv("REMOTE_ADDR");
		$PHP_SELF_IP = $IP . "@" . $HTTP_HOST . " (" . $PHP_SELF . (isset($_SERVER['QUERY_STRING']) ? "?" . $_SERVER['QUERY_STRING'] : "") . ")";
			
		$LogFileName = $config["errorLogFileName"];
		for($Retries=0;$Retries<5;$Retries++) //Do some retries if the file is already in use
		{
			$hFile=@fopen($config["physicalPath"] . $LogFileName, "a"); //Create new file, or append to existing one
			if($hFile)
			{
				
				$DateTime=date("Y-m-d H:i:s", time());
				$Log= $type . "\t" . $DateTime . "\t" . $PHP_SELF_IP . "\t " . (isset($file) ? $file : "") . "\t" . (isset($line) ? $line : "") . "\t" . $ErrorMessage . "";
				
				@fwrite($hFile, "\r\n<?/*" . $Log . "*/?>");
				@fclose($hFile);
				return true;
			}
	
			sleep(1);
		}
	}
	return false;
}

function DateConvert($old_date, $layout)
{
	//DateConvert($_POST["fDate"],"dmY")
	//Remove non-numeric characters that might exist (e.g. hyphens and colons)
	$old_date = ereg_replace('[^0-9]', '', $old_date);
	//Extract the different elements that make up the date and time
	$_year = substr($old_date,0,4);
	$_month = substr($old_date,4,2);
	$_day = substr($old_date,6,2);
	$_hour = substr($old_date,8,2);
	$_minute = substr($old_date,10,2);
	$_second = substr($old_date,12,2);
	if($old_date == '') return '';
	//Combine the date function with mktime to produce a user-friendly date & time
	$new_date = @date($layout, mktime($_hour, $_minute, $_second, $_month, $_day, $_year));
	return $new_date;
}

function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
  /*
    $interval can be:
    yyyy - Number of full years
    q - Number of full quarters
    m - Number of full months
    y - Difference between day numbers
      (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
    d - Number of full days
    w - Number of full weekdays
    ww - Number of full weeks
    h - Number of full hours
    n - Number of full minutes
    s - Number of full seconds (default)
  */
  
  if (!$using_timestamps) {
    $datefrom = strtotime($datefrom, 0);
    $dateto = strtotime($dateto, 0);
  }
  $difference = $dateto - $datefrom; // Difference in seconds
   
  switch($interval) {
   
    case 'yyyy': // Number of full years

      $years_difference = floor($difference / 31536000);
      if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
        $years_difference--;
      }
      if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
        $years_difference++;
      }
      $datediff = $years_difference;
      break;

    case "q": // Number of full quarters

      $quarters_difference = floor($difference / 8035200);
      while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
        $months_difference++;
      }
      $quarters_difference--;
      $datediff = $quarters_difference;
      break;

    case "m": // Number of full months

      $months_difference = floor($difference / 2678400);
      while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
        $months_difference++;
      }
      $months_difference--;
      $datediff = $months_difference;
      break;

    case 'y': // Difference between day numbers

      $datediff = date("z", $dateto) - date("z", $datefrom);
      break;

    case "d": // Number of full days

      $datediff = floor($difference / 86400);
      break;

    case "w": // Number of full weekdays

      $days_difference = floor($difference / 86400);
      $weeks_difference = floor($days_difference / 7); // Complete weeks
      $first_day = date("w", $datefrom);
      $days_remainder = floor($days_difference % 7);
      $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
      if ($odd_days > 7) { // Sunday
        $days_remainder--;
      }
      if ($odd_days > 6) { // Saturday
        $days_remainder--;
      }
      $datediff = ($weeks_difference * 5) + $days_remainder;
      break;

    case "ww": // Number of full weeks

      $datediff = floor($difference / 604800);
      break;

    case "h": // Number of full hours

      $datediff = floor($difference / 3600);
      break;

    case "n": // Number of full minutes

      $datediff = floor($difference / 60);
      break;

    default: // Number of full seconds (default)

      $datediff = $difference;
      break;
  }    

  return $datediff;

}

/*
#$newdate = dateadd("d",3,"2006-12-12");    #  add 3 days to date
#$newdate = dateadd("s",3,"2006-12-12");    #  add 3 seconds to date
#$newdate = dateadd("n",3,"2006-12-12");    #  add 3 minutes to date
#$newdate = dateadd("h",3,"2006-12-12");    #  add 3 hours to date
#$newdate = dateadd("ww",3,"2006-12-12");    #  add 3 weeks days to date
#$newdate = dateadd("m",3,"2006-12-12");    #  add 3 months to date
#$newdate = dateadd("yyyy",3,"2006-12-12");    #  add 3 years to date
#$newdate = dateadd("d",-3,"2006-12-12");    #  subtract 3 days from date
*/
function dateAdd($interval,$number,$dateTime) {
        
    $dateTime = (strtotime($dateTime) != -1) ? strtotime($dateTime) : $dateTime;       
    $dateTimeArr=getdate($dateTime);
                
    $yr=$dateTimeArr["year"];
    $mon=$dateTimeArr["mon"];
    $day=$dateTimeArr["mday"];
    $hr=$dateTimeArr["hours"];
    $min=$dateTimeArr["minutes"];
    $sec=$dateTimeArr["seconds"];

//echo  implode("-",$dateTimeArr) . "<br>";
    switch($interval) {
        case "s"://seconds
            $sec += $number; 
            break;

        case "n"://minutes
            $min += $number; 
            break;

        case "h"://hours
			//echo $hr . "<br>";
            $hr += $number; 
			//echo $number;
            break;

        case "d"://days
            $day += $number; 
            break;

        case "ww"://Week
            $day += ($number * 7); 
            break;

        case "m": //similar result "m" dateDiff Microsoft
            $mon += $number; 
            break;

        case "yyyy": //similar result "yyyy" dateDiff Microsoft
            $yr += $number; 
            break;

        default:
            $day += $number; 
         }       
        
        $dateTime = mktime($hr,$min,$sec,$mon,$day,$yr);
        $dateTimeArr=getdate($dateTime);
        
        $nosecmin = 0;
        $min=$dateTimeArr["minutes"];
        $sec=$dateTimeArr["seconds"];

        if ($hr==0){$nosecmin += 1;}
        if ($min==0){$nosecmin += 1;}
        if ($sec==0){$nosecmin += 1;}
        
        if ($nosecmin>2){     return(date("Y-m-d",$dateTime));} else {     return(date("Y-m-d H:i:s",$dateTime));}
} 

function getMonthName($_mouth)
{
	global $auth;	
	$ar = core_mouths();	
	return $ar[$_mouth];
}

function getDayName($d="")
{
	$ar = core_days();	
	if($d != "") return $ar[DateConvert($d, "w")];// 0 = Sunday, 6 = Saterday
	else return $ar[date("w")];// 0 = Sunday, 6 = Saterday
}

function formatDate($_d, $mouthName=true, $showTime=true, $sepator = "/")
{	
	if( $_d != "" )
	{
		$old_date = ereg_replace('[^0-9]', '', $_d);
		
		$_year = substr($old_date,0,4);
		$_month = substr($old_date,4,2);
		$_day = substr($old_date,6,2);
		
		$hm = "";
		if($showTime)
		{
			$_hour = substr($old_date,8,2);
			$_min = substr($old_date,10,2);
			$_sec = substr($old_date,12,2);	
			if($_hour != "") 
			{
				$hm = $_hour;
				if($_min != "") $hm .= ":" . $_min;
				if($_sec != "") $hm .= ":" . $_sec;
				
				$hm = " - " . $hm;
			}
		}
		
		return $_day . $sepator . ($mouthName ? getMonthName(intval($_month)-1) : $_month) . $sepator . $_year  . $hm;
	}
}

function getFirstWords($str, $count=4, $moreSet = "")
{	
	$ret = "";

	$str_ar = split(" ",trim($str));
	for($i=0; $i < count($str_ar) && $i < $count; $i++)
	{
		$ret .= $str_ar[$i] . " ";
	}

	if( $i != count($str_ar) ) $ret .= $moreSet;
	
	return $ret;
}

function LookUpPreview($Query)
{
	global $db;

	$ret_Value = "";
	$result = $db->sql_query($Query);
	
	if($db->sql_numfields($result) > 0)
	{
		$dr = $db->sql_fetchrow($result);
		//$db->sql_fieldname[0]
		$ret_Value = $dr[0];
	}
	
	$db->sql_freeresult($result);
	return $ret_Value;
}

function GetUserFullName()
{
	global $db;
	$args = func_get_args();
	$sql = "SELECT user_fullname from users where user_id=" . $args[0];
	$result = $db->sql_query($sql);
	$UserFullName = ( $row = $db->sql_fetchrow($result) ) ? $row[0] : "";
	$db->sql_freeresult($result);
	return $UserFullName;
}

function GetUserInfo($UserID, $Column = "")
{
	global $db;
	$sql = "SELECT * from users where user_id=$UserID";
	$result = $db->sql_query($sql);
	if($Column != "")
		$return_val = ( $row = $db->sql_fetchrow($result) ) ? (isset($row[$Column]) ? $row[$Column] : "") : "";
	else 
		$return_val = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);
	return $return_val;
}

function UpdateHit($Table, $Pk, $Pk_Value, $Hitfield = "hits")
{
	global $db;
	$sql = "UPDATE $Table SET $Hitfield=$Hitfield+1 WHERE $Pk=$Pk_Value";
	$db->sql_query($sql);
}

function PopupRender($key, $popupUrl, $SelectedLabels="", $SelectedValues= "", $InlineAdmin = false)
{
	$__ID = "popup_" . $key;
	$PopupCaller = "ShowPopup(this,'" . $popupUrl . "');";
	$PopupHtml = "";		
	
	$PopupHtml .= "<div id='" . $key . "_div'>&nbsp;</div>";
	$PopupHtml .= "<script language='javascript'>";
	$PopupHtml .= $__ID . " = new PopupAdmin(\"" . $key .  "\",\"" . $PopupCaller . "\",\"" . $SelectedValues . "\",\"" . $SelectedLabels . "\"," . ($InlineAdmin?"1":"0") . ");";
	$PopupHtml .= "</script>";			
	return $PopupHtml;
}

function GetM2MValues(&$SelectedLabels, &$SelectedValues, $query, $LabelKey, $ValueKey)
{
	global $db;
	$found_result = $db->sql_query($query);
	while ($dr = $db->sql_fetchrow($found_result))
	{
		$SelectedLabels .= str_replace(",","",$dr[$LabelKey]) . ",";
		$SelectedValues .= $dr[$ValueKey] . ",";
	}	
	$db->sql_freeresult($found_result);
}

function UpdateM2M($PK_Label, $PK_Value, $M2MTable, $PostKey, $L_Label)
{
	global $db;

	$PrimaryKeys = array();
	$QuotFields = array();
	$PrimaryKeys[$PK_Label] = $PK_Value;
	$QuotFields[$PK_Label] = false;
	
	$db->ExecuteDeleter($M2MTable,$PrimaryKeys,$QuotFields);
		
	if(isset($_POST[$PostKey]) && is_array($_POST[$PostKey]))
	{
		for($i=0;$i<count($_POST[$PostKey]);$i++)
		{
			if($_POST[$PostKey][$i] != "")
			{
				$PrimaryKeys = array();
				$Collector = array();
				$QuotFields = array();
				
				$Collector[$PK_Label] = $PK_Value;
				$QuotFields[$PK_Label] = false;
		
				$Collector[$L_Label] = $_POST[$PostKey][$i];
				$QuotFields[$L_Label] = false;
				
				$db->ExecuteUpdater($M2MTable,$PrimaryKeys,$Collector,$QuotFields);
			}
		}
	}
	
	//echo $_POST[$PostKey];
	//exit;
}

function ValidateSecurePages()
{
	global $config;
	if(isset($config["SecurePages"]))
	{
		if(isset($_GET["com"]) && in_array($_GET["com"],$config["SecurePages"]))
		{
			if(!(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on"))
			{
				Redirect("https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
				//Redirect("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
			}
		}	
		else if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")
		{
			Redirect("https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		}
	}
}


function AbsoluteUri($push = "")
{
	$ret = "";

	$url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$urlA = split("\?",$url);

	if(count($urlA) > 0)
	{
		$ret .= $urlA[0];
		$h = array();
		if(count($urlA) > 1)
		{
			$urlA2 = split("&",$urlA[1]);
			foreach($urlA2 as $val=>$str)
			{
				if($str != "")
				{
					$strA = split("=",$str);
					if(count($strA) > 1)
					{
						$h[$strA[0]] = $strA[1];
					}
				}
			}
		}

		if($push != "")
		{
			foreach($push as $key=>$val)
			{
				$h[$key] = $val;
			}
		}

		$urlAdd = "";
		
		foreach($h as $key=>$val)
		{
			if($val != "") $urlAdd .= "&" . $key . "=" . $val;
		}
		if($urlAdd != "") $ret .= "?" . substr($urlAdd,1);

	}
	
	return $ret;

}


function CreateUrl($push = "")
{
	global $config, $auth;
	
	$url = $config["realurl"];	
	$url .= "index.php";
	$url .= "?lang=" . $auth->LanguageCode;

	if($push != "")
	{
		foreach($push as $key=>$val)
		{
			$url .= "&amp;".$key."=".urlencode($val);
		}
	}
	
	return $url;
}

function ValidateUrl($url)
{
	global $config, $auth;

	$lang = "lang=" . $auth->LanguageCode . "&";
	
	if(strpos($url, "index.php") === 0)
	{
		if(strpos($url, "lang=") === false)
		{
			$url = str_replace("index.php?","index.php",$url);
			$url = str_replace("index.php","index.php?".$lang,$url);
		}
	}
	return $url;
}

function GetRefParents($table, $pk, $pk_val)
{
	global $db;
	
	$depth = $pk_val . ",";
	
	$sql = "SELECT parent_id FROM " . $table . " where " . $pk . "=" . $pk_val;
	$dr = $db->RowSelectorQuery($sql);
	
	if(isset($dr["parent_id"]) && $dr["parent_id"] != "") $depth .= GetRefParents($table, $pk, $dr["parent_id"]);
	
	$db->sql_freeresult($__result);
	return $depth;
}

function UpdateRefParents($table, $pk, $pk_val = "", $ref_prefix = "")
{
	global $db;
	
	$sql = "SELECT " . $pk . " FROM " . $table . " where parent_id " . ($pk_val != "" ?  "=" . $pk_val : " IS NULL");
	$__result = $db->sql_query($sql);

	while($dr = $db->sql_fetchrow($__result))
	{
		$ref_p = $ref_prefix . $dr[$pk] . ",";
		$db->sql_query("UPDATE " . $table . " SET ref_parents = '" . $ref_p . "' WHERE " . $pk . " = " . $dr[$pk]);
		UpdateRefParents($table, $pk, $dr[$pk], $ref_p );
	}

	$db->sql_freeresult($__result);
}

function setModifiedDate($filePath) 
{
	$HashID = md5($filePath);
	clearstatcache(); 
	$contentDate = filemtime($filePath);

	header('ETag: ' . $HashID);
	header('Cache-Control: must-revalidate');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s', $contentDate).' GMT');
		
    $ifModifiedSince = isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE']) : false;
	if ($ifModifiedSince && strtotime($ifModifiedSince) >= $contentDate) 
	{
        header('HTTP/1.0 304 Not Modified');
        die; // stop processing
    }
}
?>