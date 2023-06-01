<?php
	
	define('_VALID_PROCCESS',true);
	define('_VALID_FLASH_PROCCESS',true);

	require('../../global.php');
	require('../include/gms.php');
	
	include "amf-core/app/Gateway.php";
	
	define("PRODUCTION_SERVER", false);

	$gateway = new Gateway();
	
	$servicesPath = "../../sites/" . $config["site"] . "/services/";
	$gateway->setBaseClassPath($servicesPath);
	$gateway->setLooseMode(true);
	$gateway->setErrorHandling(E_ALL ^ E_NOTICE);
	$gateway->setWebServiceHandler('php5');
	$gateway->addAdapterMapping('db_result', 'peardb');
	$gateway->addAdapterMapping('pdostatement', 'pdo');
	$gateway->addAdapterMapping('mysqli_result', 'mysqli');
	$gateway->addAdapterMapping('arrayf', 'arrayf');
	$gateway->addAdapterMapping('arrayft', 'arrayft');
	
	if(PRODUCTION_SERVER)
	{
		$gateway->disableTrace();
		$gateway->disableDebug();
		$gateway->disableServiceDescription();
	}
	include_once('advancedsettings.php');
	$gateway->service();
	$db->sql_close();
	WriteAuthenticateToSession();
?>