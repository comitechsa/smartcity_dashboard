<?php
	session_start();
	$_SESSION['defaultperiod_id']=$_POST['period'];
	echo '<script>$("#session_period").text("period: '.$_SESSION['defaultperiod_id'].'");</script>';
?>