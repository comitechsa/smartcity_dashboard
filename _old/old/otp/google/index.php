<?php


// destroy the session
	session_start();
	//session_unset();
	//session_destroy();

	require_once 'PHPGangsta/GoogleAuthenticator.php';
	$ga = new PHPGangsta_GoogleAuthenticator();
	//$secret = $ga->createSecret();
	//$_SESSION['secret']=$secret;
	if(!isset($_SESSION['secret']) || isset($_GET['new'])){
		$secret = $ga->createSecret();
		$_SESSION['secret']=$secret;	
	}
	$sec=$_SESSION['secret'];
	echo "<p>Secret is: ".$sec."</p><br>";
	
	$qrCodeUrl = $ga->getQRCodeGoogleUrl('OneTimePassword', $sec);
	//echo "Google Charts URL for the QR-Code: ".$qrCodeUrl."\n\n";
	echo '<p><img src='.$qrCodeUrl.'</p><br>';
	//$oneCode = $ga->getCode($sec);
	if($_SESSION['code']==''){
		$_SESSION['code']=$ga->getCode($sec);
	}
	$ondC=$_SESSION['code'];
	//Checking
	echo "<br>Checking Code '$ondC' and Secret '$sec':<br>";
	
	$checkResult = $ga->verifyCode($sec, $ondC, 2);    // 1 = 2*30sec clock tolerance
	if ($checkResult) {
		echo 'OK';
	} else {
		echo 'FAILED';
	}		
	
	/*

	//Checking
	echo "<br>Checking Code '$oneCode' and Secret '$sec':<br>";
	
	$checkResult = $ga->verifyCode($secret, '726611', 4);    // 1 = 2*30sec clock tolerance
	if ($checkResult) {
		echo 'OK';
	} else {
		echo 'FAILED';
		//$secret = $ga->createSecret();
	}
	

	*/

	
?>