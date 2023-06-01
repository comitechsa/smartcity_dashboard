<?php
	session_start();
	$code=$_GET['code'];
	require_once 'PHPGangsta/GoogleAuthenticator.php';
	$ga = new PHPGangsta_GoogleAuthenticator();
	//$secret = $ga->createSecret();
	//$_SESSION['secret']=$secret;
	/*
	if(!isset($_SESSION['secret'])){
		$secret = $ga->createSecret();
		$_SESSION['secret']=$secret;	
	}
	*/

	$sec=$_SESSION['secret'];
	echo "<p>Secret is: ".$sec."</p><br>";
	
	/*
	$qrCodeUrl = $ga->getQRCodeGoogleUrl('OneTimePassword', $sec);
	//echo "Google Charts URL for the QR-Code: ".$qrCodeUrl."\n\n";
	echo '<p><img src='.$qrCodeUrl.'</p><br>';
	$oneCode = $ga->getCode($sec);
	
	*/


	$checkResult = $ga->verifyCode($sec, $code, 4);    // 1 = 2*30sec clock tolerance
	if ($checkResult) {
		echo 'OK';
	} else {
		echo 'FAILED';
	}
?>