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
 * File Name: config.php
 * 	Configuration file for the File Manager Connector for PHP.
 * 
 * Version:  2.0 RC3
 * Modified: 2005-02-08 12:01:53
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
global $Config ;

$Config['AllowedExtensions']	= array() ;
$Config['DeniedExtensions'] = array('php','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg') ;

?>