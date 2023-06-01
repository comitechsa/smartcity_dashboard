<?php

if(intval($_POST['child'])>0) {
	$servername = "localhost";
	$username = "wangrjz_sspeech";
	$password = "qwe#123!@#";
	$db="wangrjz_sspeech";
	// Create connection
	$conn = mysqli_connect($servername, $username, $password,$db);

	$sql = "SELECT * FROM children WHERE children_id=".$_POST['child'];
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	//echo 'valid::'.$row['is_valid'];
	$newStatus=($row['is_valid']=='True'?'False':'True');
	$updateQuery="UPDATE children SET is_valid=".$newStatus." WHERE children_id=".$_POST['child'];
	$result = $conn->query($updateQuery);
	echo $newStatus;
	/*
	UPDATE `lang` 
	SET `is_valid`='$is_valid',
	`language_family`='$language_family',
	`language_name`='$language_name',
	`native_name`='$native_name' 
	`iso_name`='$iso_name' 
	WHERE lang_id=$id";
	if (mysqli_query($conn, $sql)) {
	*/

  
}
?>