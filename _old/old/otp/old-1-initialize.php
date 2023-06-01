<?php
	$user=$_GET['id'];
	// (1) INCLUDE OTP LIBRARY
	require __DIR__ . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "config.php";
	require PATH_LIB . "lib-otp.php";
	$libOTP = new OTP();

	// (2) GENERATE OTP
	// Using a dummy ID of 999 here
	// It can be the user ID, order ID, whatever is applicable to your project
	$result = $libOTP->generate($user);

	// (3) SEND THE ONE-TIME PASSWORD TO THE USER

	// OTP generation OK
	if ($result['status'] == 1) {
		// Send message via email, SMS, messaging, or whatever communication gateway you use
		$message = "This password is valid for " . OTP_VALID . " minutes - " . $result['pass'];
		// mail("john@doe.com", "Verify to continue", $message);
		// For testing - We will just output the OTP as text here.
		echo $message;
	}
	// OTP generation Failed
	else {
		echo "ERROR - ". $libOTP->error;
	}
?>