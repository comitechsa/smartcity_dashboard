<?php 
/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2004 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * File Name: connector.php
 * 	This is the File Manager Connector for PHP.
 * 
 * Version:  2.0 RC3
 * Modified: 2005-02-08 11:48:55
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */
define('_VALID_PROCCESS',true);
define('_ADMIN_PROCCESS',true);

define('_BASE_PATH','../../');

require(_BASE_PATH . 'global.php');

require_once(_BASE_PATH . 'gms/include/gmsLibrary.php');

require_once(_BASE_PATH . 'gms/include/gmsFunctions.php');

require_once(_BASE_PATH . 'gms/include/gmsSites.php');

require_once($config["physicalPath"] . 'sites/' . $config["site"] . '/config.php');

//Open The Database Connection
$db = new sql_db($config["dbhost"], $config["dbuser"], $config["dbpasswd"], $config["dbname"], false);

if(!$db->db_connect_id)
{
	GoOffline("Could not connect to the database.");
}

//Load Authentication Object
@session_start();
$auth = null;
$SesPrefix = (isset($config["ses_const"]) ? $config["ses_const"] : "");
if(isset($_SESSION[$SesPrefix.'admAuthenticate']))
{
	$auth = $_SESSION[$SesPrefix.'admAuthenticate'];
	$auth->Init($db,$config);
}
else if(isset($_SESSION[$SesPrefix.'frAuthenticate']))
{
	$auth = $_SESSION[$SesPrefix.'frAuthenticate'];
	$auth->Init($db,$config);
}

$GLOBALS["UserFilesPath"]  = '';
$GLOBALS["User_ResourceType"] = '' ;

if(isset($auth) && $auth->UserType != "")// && ($auth->UserType == "Administrator" || $auth->UserType == "Register")
{
	if($auth->UserType != "Administrator")
	{	
		$GLOBALS["User_Quota"] = 10;
		$GLOBALS["User_ResourceType"] = '/' . "temp";
		
		if(isset($auth->UserRow["user_quota"]) && $auth->UserRow["user_quota"] != "")
		{
			$GLOBALS["User_Quota"] = $auth->UserRow["user_quota"];
		}		
		if(isset($auth->UserRow["physical_folder"]) && $auth->UserRow["physical_folder"] != "")
		{
			$GLOBALS["User_ResourceType"] = '/users/' . $auth->UserRow["physical_folder"] ;
		}
		else
		{
			/*
			AttachmentUploadDir($auth->UserId);
			if(isset($auth->UserRow["physical_folder"]) && $auth->UserRow["physical_folder"] != "")
			{
				$GLOBALS["User_ResourceType"] = '/users/' . $auth->UserRow["physical_folder"] ;
			} else GoOffline( 'Please enter user path' );
			*/
			GoOffline( 'Please enter user path' );
		}
	}
}
else {GoOffline( 'Direct Access to this location is not allowed.' );}

include('config.php');
include('util.php');
include('io.php');
include('basexml.php');
include('commands.php');

$GLOBALS["UserFilesDirectory"] = $config["physicalPath"] . "gallery" ;
//"sites/" . $config["site"] . 

DoResponse() ;

function DoResponse()
{
	if ( !isset( $_GET['Command'] ) || !isset( $_GET['Type'] ) || !isset( $_GET['CurrentFolder'] ) )
		return ;

	// Get the main request informaiton.
	$sCommand		= $_GET['Command'] ;
	$sResourceType	= $GLOBALS["User_ResourceType"];
	//$_GET['Type'] .
	$sCurrentFolder	= $_GET['CurrentFolder'] ;

	// Check the current folder syntax (must begin and start with a slash).
	if ( ! ereg( '/$', $sCurrentFolder ) ) $sCurrentFolder .= '/' ;
	if ( strpos( $sCurrentFolder, '/' ) !== 0 ) $sCurrentFolder = '/' . $sCurrentFolder ;

	// File Upload doesn't have to Return XML, so it must be intercepted before anything.
	if ( $sCommand == 'FileUpload' )
	{
		FileUpload( $sResourceType, $sCurrentFolder ) ;
		return ;
	}
	
	/*
	case 'Delete' :
			Delete( $sResourceType, $sCurrentFolder ) ;
			break ;
	*/

	// Prevent the browser from caching the result.
	// Date in the past
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
	// always modified
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
	// HTTP/1.1
	header('Cache-Control: no-store, no-cache, must-revalidate') ;
	header('Cache-Control: post-check=0, pre-check=0', false) ;
	// HTTP/1.0
	header('Pragma: no-cache') ;

	
	// Set the response format.
	header( 'Content-Type:text/xml; charset=utf-8' ) ;

	CreateXmlHeader( $sCommand, $sResourceType, $sCurrentFolder ) ;
	// Execute the required command.
	switch ( $sCommand )
	{
		case 'GetFolders' :
			GetFolders( $sResourceType, $sCurrentFolder ) ;
			break ;
		case 'GetFoldersAndFiles' :
			GetFoldersAndFiles( $sResourceType, $sCurrentFolder ) ;
			break ;
		case 'CreateFolder' :
			CreateFolder( $sResourceType, $sCurrentFolder ) ;
			break ;
		case 'Delete' :
			Delete( $sResourceType, $sCurrentFolder ) ;
			break ;
	}

	CreateXmlFooter() ;

	exit ;
}
//Close The Database Connection
$db->sql_close();

//Write Authenticate To Session
WriteAuthenticateToSession();
?>