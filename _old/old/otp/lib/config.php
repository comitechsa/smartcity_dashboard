<?php
	// MUTE NOTICES
	error_reporting(E_ALL & ~E_NOTICE);

	// PATH
	define('PATH_LIB', __DIR__ . DIRECTORY_SEPARATOR);

	// DATABASE SETTINGS - CHANGE THESE TO YOUR OWN!
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'wangrjz_otp');
	define('DB_CHARSET', 'utf8');
	define('DB_USER', 'wangrjz_otp');
	define('DB_PASSWORD', 'qwe#123!@#');

	// ONE-TIME PASSWORD
	define('OTP_VALID', "2"); // VALID FOR X MINUTES
	define('OTP_TRIES', "1"); // MAX TRIES
	define('OTP_LEN', "9"); // PASSWORD LENGTH
?>