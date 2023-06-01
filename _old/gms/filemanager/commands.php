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
 * File Name: commands.php
 * 	This is the File Manager Connector for ASP.
 * 
 * Version:  2.0 RC3
 * Modified: 2005-02-19 16:02:38
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

function Delete( $resourceType, $currentFolder )
{
	$sErrorNumber	= '0' ;
	$sErrorMsg		= '' ;

	if ( isset( $_GET['pathName'] ) )
	{
		$sNewFolderName = $_GET['pathName'] ;
		// Map the virtual path to the local server path of the current folder.
		$sServerDir = $GLOBALS["UserFilesDirectory"];

		if ( is_writable( $sServerDir ) )
		{
			$sServerDir .= $sNewFolderName ;

			$sErrorMsg = DeleteFolderOrFile( $sServerDir ) ;

			switch ( $sErrorMsg )
			{
				case '' :
					$sErrorNumber = '0' ;
					break ;
				case 'Invalid argument' :
				case 'No such file or directory' :
					$sErrorNumber = '102' ;		// Path too long.
					break ;
				default :
					$sErrorNumber = '110' ;
					break ;
			}
		}
		else
			$sErrorNumber = '103' ;
	}
	else
		$sErrorNumber = '102' ;

	// Create the "Error" node.
	echo '<Error number="' . $sErrorNumber . '" originalDescription="' . ConvertToXmlAttribute( $sErrorMsg ) . '" />' ;
}

function GetFolders( $resourceType, $currentFolder )
{
	// Map the virtual path to the local server path.
	$sServerDir = ServerMapFolder( $resourceType, $currentFolder ) ;

	// Open the "Folders" node.
	echo "<Folders>" ;

	$oCurrentFolder = opendir( $sServerDir ) ;

	while ( $sFile = readdir( $oCurrentFolder ) )
	{
		if ( $sFile != '.' && $sFile != '..' && is_dir( $sServerDir . $sFile ) )
			echo '<Folder name="' . ConvertToXmlAttribute( $sFile ) . '" />' ;
	}

	closedir( $oCurrentFolder ) ;

	// Close the "Folders" node.
	echo "</Folders>" ;
}

function GetFoldersAndFiles( $resourceType, $currentFolder )
{
	// Map the virtual path to the local server path.
	$sServerDir = ServerMapFolder( $resourceType, $currentFolder ) ;

	// Initialize the output buffers for "Folders" and "Files".
	$sFolders	= '<Folders>' ;
	$sFiles		= '<Files>' ;

	$oCurrentFolder = opendir( $sServerDir ) ;

	$dir_array = array();  
	while($sFile = readdir( $oCurrentFolder ))
	{   
		$dir_array[] = $sFile;
	}   

	natsort($dir_array);
	//while ( $sFile = readdir( $oCurrentFolder ) )
	
	foreach($dir_array as $sFile)
	{
		if ( $sFile != '.' && $sFile != '..' )
		{
			if ( is_dir( $sServerDir . $sFile ) )
				$sFolders .= '<Folder name="' . ConvertToXmlAttribute( $sFile ) . '" />' ;
			else
			{
				$iFileSize = filesize( $sServerDir . $sFile ) ;
				if ( $iFileSize > 0 )
				{
					$iFileSize = round( $iFileSize / 1024 ) ;
					if ( $iFileSize < 1 ) $iFileSize = 1 ;
				}

				$sFiles	.= '<File name="' . ConvertToXmlAttribute( $sFile ) . '" size="' . $iFileSize . '" />' ;
			}
		}
	}

	echo $sFolders ;
	// Close the "Folders" node.
	echo '</Folders>' ;

	echo $sFiles ;
	// Close the "Files" node.
	echo '</Files>' ;
}

function CreateFolder( $resourceType, $currentFolder )
{
	$sErrorNumber	= '0' ;
	$sErrorMsg		= '' ;

	global $auth;
	
	if($auth->UserType == "Register")
	{
		$sErrorNumber = '103' ;
	}
	else
	{
		if ( isset( $_GET['NewFolderName'] ) )
		{
			$sNewFolderName = $_GET['NewFolderName'] ;
	
			// Map the virtual path to the local server path of the current folder.
			$sServerDir = ServerMapFolder( $resourceType, $currentFolder ) ;
	
			if ( is_writable( $sServerDir ) )
			{
				$sServerDir .= $sNewFolderName ;
	
				$sErrorMsg = CreateServerFolder( $sServerDir ) ;
	
				switch ( $sErrorMsg )
				{
					case '' :
						$sErrorNumber = '0' ;
						break ;
					case 'Invalid argument' :
					case 'No such file or directory' :
						$sErrorNumber = '102' ;		// Path too long.
						break ;
					default :
						$sErrorNumber = '110' ;
						break ;
				}
			}
			else
				$sErrorNumber = '103' ;
		}
		else
			$sErrorNumber = '102' ;
	}

	// Create the "Error" node.
	echo '<Error number="' . $sErrorNumber . '" originalDescription="' . ConvertToXmlAttribute( $sErrorMsg ) . '" />' ;
}

function GetFolderSize( $resourceType, $currentFolder )
{
	// Map the virtual path to the local server path.
	$sServerDir = ServerMapFolder( $resourceType, $currentFolder ) ;

	$FolderSize		=  0 ;

	$oCurrentFolder = opendir( $sServerDir ) ;

	while ( $sFile = readdir( $oCurrentFolder ) )
	{
		if ( $sFile != '.' && $sFile != '..' )
		{
			if ( !is_dir( $sServerDir . $sFile ) )
			{
				$iFileSize = filesize( $sServerDir . $sFile ) ;
				if ( $iFileSize > 0 )
				{
					$FolderSize += $iFileSize;
				}
			}
		}
	}

	return round( $FolderSize / 1024 ) ;
}

function FileUpload( $resourceType, $currentFolder )
{
	$folderSize = GetFolderSize( $resourceType, $currentFolder);
	$sErrorNumber = '0' ;
	$sFileName = '' ;

	//in MB
	$quota = isset($GLOBALS["User_Quota"]) && $GLOBALS["User_Quota"] > 0 ? $GLOBALS["User_Quota"] : 200;
	if(($folderSize / 1024) > $quota )
	{
		$sErrorNumber = '303' ;
	}
	else
	{		
		if ( isset( $_FILES['NewFile'] ) && !is_null( $_FILES['NewFile']['tmp_name'] ) )
		{
			$oFile = $_FILES['NewFile'] ;
	
			// Map the virtual path to the local server path.
			$sServerDir = ServerMapFolder( $resourceType, $currentFolder ) ;
	
			// Get the uploaded file name.
			$sFileName = $oFile['name'] ;
			$sOriginalFileName = $sFileName ;
			$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
	
			global $Config ;
	
			$arAllowed	= $Config['AllowedExtensions'];
			$arDenied	= $Config['DeniedExtensions'];
	
			if ( ( count($arAllowed) == 0 || in_array( $sExtension, $arAllowed ) ) && ( count($arDenied) == 0 || !in_array( $sExtension, $arDenied ) ) )
			{
				$iCounter = 0 ;
	
				while ( true )
				{
					$sFilePath = $sServerDir . $sFileName ;
	
					if ( is_file( $sFilePath ) )
					{
						$iCounter++ ;
						$sFileName = RemoveExtension( $sOriginalFileName ) . '(' . $iCounter . ').' . $sExtension ;
						$sErrorNumber = '201' ;
					}
					else
					{
						move_uploaded_file( $oFile['tmp_name'], $sFilePath ) ;
	
						if ( is_file( $sFilePath ) )
						{
							$oldumask = umask(0) ;
							chmod( $sFilePath, 0777 ) ;
							umask( $oldumask ) ;
						}
	
						break ;
					}
				}
			}
			else
				$sErrorNumber = '202' ;
		}
		else
			$sErrorNumber = '202' ;
	}
	
	echo '<script type="text/javascript">' ;
	echo 'window.parent.frames["frmUpload"].OnUploadCompleted(' . $sErrorNumber . ',"' . str_replace( '"', '\\"', $sFileName ) . '") ;' ;
	echo '</script>' ;

	exit ;
}
?>