<?
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

$useSimpleFileNames = true;

$arAllowed	= array();
$arDenied	= array('php','php3','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi','fla') ;
$arImages	= array('jpg','gif','png','bmp');

function AttachmentUploadDir($userID) 
{	
	global $db, $config, $auth;
	
	$attachmentUploadDir = $config["physicalPath"] . "gallery/users/";
	$sql = "SELECT * FROM users WHERE user_id=" . $userID;
	$result = $db->sql_query($sql);
	$physical_folder = ( $row = $db->sql_fetchrow($result) ) ? ((isset($row) && $row["physical_folder"] != "") ? $row["physical_folder"] : "-1") : "-1";
	$db->sql_freeresult($result);
	
	$UserDirectory = $attachmentUploadDir . $physical_folder . "/";
	if(is_dir($UserDirectory))
	{
		return $UserDirectory;
	}
	else if(isset($row))
	{
		// To create the folder with 0777 permissions, we need to set umask to zero.
		$UserDirectory = $attachmentUploadDir . $row["user_name"] . "/";
		$oldumask = umask(0) ;
		mkdir( $UserDirectory, 0777 ) ;
		umask( $oldumask ) ;		
		if(is_dir($UserDirectory))
		{
			$sql = "UPDATE users SET physical_folder = '" . $row["user_name"] . "' WHERE user_id=" . $userID;
			$db->sql_query($sql);
			return $UserDirectory;
		}
		else
		{
			$salt 	= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$len 	= strlen($salt);
			$pf		= '';
			
			mt_srand(10000000*(double)microtime());
			for ($i = 0; $i < 10; $i++) $pf .= $salt[mt_rand(0,$len - 1)];
				
			$UserDirectory = $attachmentUploadDir . $pf . "/";
			$oldumask = umask(0) ;
			mkdir( $UserDirectory, 0777 ) ;
			umask( $oldumask ) ;
			
			if(is_dir($UserDirectory))
			{
				$sql = "UPDATE users SET physical_folder = '" . $pf . "' WHERE user_id=" . $userID;
				$db->sql_query($sql);
				return $UserDirectory;
			}			
		}
	}
}

function DeleteAttachment($ID)
{
	global $db, $auth, $config;
	$request = $db->sql_query("SELECT filename, attach_id, type, user_id FROM users_attachments WHERE attach_id = $ID LIMIT 1");
	if (mysql_num_rows($request) != 0)
	{
		list ($real_filename, $attach_id, $type, $user_id) = mysql_fetch_row($request);
		
		$attachmentUploadDir = AttachmentUploadDir($user_id);
		$file = getAttachmentFilename($real_filename,$attach_id,$attachmentUploadDir,false);
		$sql = "DELETE FROM users_attachments WHERE attach_id=$ID LIMIT 1";
		$db->sql_query($sql);
		unlink($file) ;
	}
}

function CopyAttachment($ID,$NewName)
{
	global $arImages, $db, $auth, $config;
	$request = $db->sql_query("SELECT filename, attach_id, type, user_id FROM users_attachments WHERE attach_id = $ID LIMIT 1");
			
	if (mysql_num_rows($request) != 0)
	{
		list ($real_filename, $attach_id, $type, $user_id) = mysql_fetch_row($request);
		
		$attachmentUploadDir = AttachmentUploadDir($user_id);
		
		$file = getAttachmentFilename($real_filename,$attach_id,$attachmentUploadDir,false);
		
		$size = filesize($file);
		$sExtension = substr( $NewName, ( strrpos($NewName, '.') + 1 ) ) ;
		$filetype = in_array( strtolower($sExtension), $arImages ) ? "image" : "file";
		
		$sql = "INSERT INTO users_attachments (type, user_id, filename, size) VALUES ('" . $filetype . "','" . $auth->UserId . "','" . str_replace("'","",$NewName) . "','" . $size . "')";
		$db->sql_query($sql);
		$AttachID = $db->sql_nextid();
		
		$sFilePath = $attachmentUploadDir . getAttachmentFilename($NewName,$AttachID,$attachmentUploadDir,true);
		
		copy($file,$sFilePath);
		return array($AttachID,$sFilePath,$NewName);
	}

	mysql_free_result($request);
}

function AttachmentVirtual($ID)
{
	$ret = ""; 
	global $arImages, $db, $auth, $config;
	$request = $db->sql_query("SELECT filename, attach_id, type, user_id FROM users_attachments WHERE attach_id = $ID LIMIT 1");
			
	if (mysql_num_rows($request) != 0)
	{
		list ($real_filename, $attach_id, $type, $user_id) = mysql_fetch_row($request);
		
		$attachmentUploadDir = "/gallery/users/";
		$sql = "SELECT * FROM users WHERE user_id=" . $user_id;
		$result = $db->sql_query($sql);
		$attachmentUploadDir .= ( $row = $db->sql_fetchrow($result) ) ? ((isset($row) && $row["physical_folder"] != "") ? $row["physical_folder"] : "-1") : "-1";
		$db->sql_freeresult($result);
		
		$ret = $attachmentUploadDir . "/" . getAttachmentFilename($real_filename, $ID,"");
	}

	mysql_free_result($request);
	return $ret;
}

function UploadAttachment($ID, $user_id = -1)
{
	global $config, $arAllowed, $arDenied, $arImages, $db, $auth;
	$oFile = $_FILES[$ID] ;
	
	if($oFile['name'] == "") return "";
	
	$attachmentUploadDir = AttachmentUploadDir(($user_id == -1 ? $auth->UserId : $user_id));
	
	//echo $attachmentUploadDir;
	//Get the uploaded file name.
	$sFileName = $oFile['name'] ;
	$sOriginalFileName = $sFileName ;
	$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;

	if ( ( count($arAllowed) == 0 || in_array( strtolower($sExtension), $arAllowed ) ) && ( count($arDenied) == 0 || !in_array( strtolower($sExtension), $arDenied ) ) )
	{		
		$size = filesize($oFile['tmp_name']);
		$filetype = in_array( strtolower($sExtension), $arImages ) ? "image" : "file";
		
		// Sorry, no spaces, dots, or anything else but letters allowed.
		$clean_name = preg_replace(array('/\s/', '/[^\w_\.\-]/'), array('_', ''), $sFileName);
		$clean_name = preg_replace('~\.[\.]+~', '.', $clean_name);
	
		$sql = "INSERT INTO users_attachments (type, user_id, filename, size) VALUES ('" . $filetype . "','" . ($user_id == -1 ? $auth->UserId : $user_id) . "','" . str_replace("'","",$clean_name) . "','" . $size . "')";
		$db->sql_query($sql);
		$AttachID = $db->sql_nextid();
		
		$sFilePath = $attachmentUploadDir . getAttachmentFilename($sFileName,$AttachID,$attachmentUploadDir,true) ;

		move_uploaded_file($oFile['tmp_name'], $sFilePath ) ;

		if ( is_file( $sFilePath ) )
		{
			$oldumask = umask(0) ;
			chmod( $sFilePath, 0777 ) ;
			umask( $oldumask ) ;
		}
		
		return array($AttachID,$sFilePath,$sFileName);
	}
	else
	{
		return "";
	}
	
	return "";
}

// Get an attachment's encrypted filename.  If $new is true, won't check for file existence.
function getAttachmentFilename($filename, $attachment_id, $attachmentUploadDir, $new = false )
{
	global $config, $useSimpleFileNames;
	
	// Remove special accented characters - ie. s.
	$clean_name = $filename;
		
	// Sorry, no spaces, dots, or anything else but letters allowed.
	$clean_name = preg_replace(array('/\s/', '/[^\w_\.\-]/'), array('_', ''), $clean_name);

	$enc_name = $attachment_id . '_' . str_replace('.', '_', $clean_name) . md5($clean_name);
	$clean_name = preg_replace('~\.[\.]+~', '.', $clean_name);

	if ($new && $useSimpleFileNames)
		return "(" . $attachment_id . ")" . $clean_name;
	else if ($new)
		return $enc_name;

	if (file_exists($attachmentUploadDir . $enc_name))
		$filename = $attachmentUploadDir . $enc_name;
	else
		$filename = $attachmentUploadDir . "(" . $attachment_id . ")" . $clean_name;

	return $filename;
}


// Download an attachment.
function Download()
{
	global $config,$db;

	// Make sure some attachment was requested!
	if (!isset($_REQUEST['id']))
	{
		header('HTTP/1.0 404 ');
		header('Content-Type: text/plain');
		die('404 - ');
	}

	$_REQUEST['id'] = (int) $_REQUEST['id'];

	$request = $db->sql_query("SELECT filename, attach_id, type, user_id FROM users_attachments WHERE attach_id = $_REQUEST[id] LIMIT 1");
			
	if (mysql_num_rows($request) == 0)
	{
		header('Content-Disposition: attachment; filename="File not found"');
		header('Content-Type: application/octet-stream');
		
		if (readfile($config["physicalPath"] . "/gms/images/none.gif") === null) echo implode('', file($config["physicalPath"] . "/gms/images/none.gif"));
		
		header('HTTP/1.0 404 ');
		header('Content-Type: text/plain');
		die('File not found ');
	}
	
	list ($real_filename, $attach_id, $type, $user_id) = mysql_fetch_row($request);
	mysql_free_result($request);

	// Update the download counter.
	UpdateHit("users_attachments", "attach_id", $attach_id, "downloads");
	
	// This is done to clear any output that was made before now. (would use ob_clean(), but that's PHP 4.2.0+...)
	@ob_end_clean();
	if (!empty($config["gzip_enabled"]) && $config["gzip_enabled"] && @version_compare(PHP_VERSION, '4.2.0') >= 0) @ob_start('ob_gzhandler');
	else @ob_start();

	$attachmentUploadDir = AttachmentUploadDir($user_id);
	$filename = getAttachmentFilename($real_filename, $_REQUEST['id'], $attachmentUploadDir);

	// No point in a nicer message, because this is supposed to be an attachment anyway...
	if (!file_exists($filename))
	{
		header('Content-Disposition: attachment; filename="File not found"');
		header('Content-Type: application/octet-stream');
		
		if (readfile($config["physicalPath"] . "/gms/images/none.gif") === null)
			echo implode('', file($config["physicalPath"] . "/gms/images/none.gif"));
		
		header('HTTP/1.0 404 ');
		header('Content-Type: text/plain');
		die('File not found ');
	}

/*
	setModifiedDate( $filename ); // HTTP/1.0 304 Not Modified	
	
	$p = $config["physicalPath"] . $_GET['ph'];
	if ($w != "" && $h != "" && file_exists($p))
	{
		$rimg = new RESIZEIMAGE($p);
		$rimg->resize_limitwh($w,$h);
		$rimg->close();
		exit;
	}
*/
	// Send the attachment headers.
	header('Pragma: ');
	header('Cache-Control: max-age=' . (525600 * 60) . ', private');

	$browser = array(
		'is_opera' => strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false,
		'is_opera6' => strpos($_SERVER['HTTP_USER_AGENT'], 'Opera 6') !== false,
		'is_ie4' => strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 4') !== false,
		'is_safari' => strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== false,
		'is_mac_ie' => strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 5.') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Mac') !== false
	);

	$is_gecko = strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko') !== false && !$browser;
	if (!$is_gecko) header('Content-Transfer-Encoding: binary');
		
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 525600 * 60) . ' GMT');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filename)) . ' GMT');
	header('Accept-Ranges: bytes');
	header('Set-Cookie:');
	header('Connection: close');

	if ($type != "image")
	{	
		header('Content-Disposition: attachment; filename="' . $real_filename . '"');
		header('Content-Type: application/octet-stream');
		//header('Content-Disposition: inline; filename="' . $real_filename . '"');
		//header('Content-Type: Image/pjpeg');
	}

	if (filesize($filename) != 0)
	{
		$size = @getimagesize($filename);
		if (!empty($size) && $size[2] > 0 && $size[2] < 4) header('Content-Type: image/' . ($size[2] != 1 ? ($size[2] != 2 ? 'png' : 'jpeg') : 'gif'));
	}

	if (empty( $config["gzip_enabled"]) && ! $config["gzip_enabled"]) header('Content-Length: ' . filesize($filename));

	// Try to buy some time...
	@set_time_limit(0);
	@ini_set('memory_limit', '128M');

	// On some of the less-bright hosts, readfile() is disabled.  It's just a faster, more byte safe, version of what's in the if.
	if (@readfile($filename) === null) echo implode('', file($filename));

	exit;
}

?>