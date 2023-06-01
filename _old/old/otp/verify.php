<?php
	// (1) INCLUDE OTP LIBRARY
	require __DIR__ . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "config.php";
	require PATH_LIB . "lib-otp.php";
	$libOTP = new OTP();

	// (2) CHECK THE GIVEN OTP
	$result = false;
	if (isset($_POST['pass']) && isset($_POST['id'])) {
		$result = $libOTP->challenge($_POST['id'], $_POST['pass']);
	} else {
		$libOTP->error = "Invalid OTP password and ID";
	}

	// (3) RESULTS
	echo json_encode(["status" => $result ? 1 : 0,"message" => $result ? "OK" : $libOTP->error]);
?>