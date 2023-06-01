<?php
	//header("Content-Type: text/html; charset=utf-8");
	header('Content-Type: application/json');
	$params=array();
	$params['usercode']="123123";
	$params['code']="861576";
	
	$url="http://covid19.wan.gr/api/api.php?func=userValidation";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);    
	curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
	curl_setopt($curl, CURLOPT_TIMEOUT, 60);

	$result = curl_exec ($curl) or die(curl_error($curl)); 
	$result=str_replace('{{','[{',$result);
	$result=str_replace("}}","}]",$result);
	echo $result;
?>
