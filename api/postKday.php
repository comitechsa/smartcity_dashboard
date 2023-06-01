<?php
	//header("Content-Type: text/html; charset=utf-8");

	// API URL
	$url="http://edsna.wan.gr/api/readKday.php";

	// Create a new cURL resource
	$ch = curl_init($url);

	// Setup request to send json via POST
	//$data = array(
	//	'username' => 'codexworld',
	//	'password' => '123456'
	//);
	
	$data=array();
	$data['meta']['code']="200";
	$data['meta']['date']="31122019";
	$data['meta']['username']="idim";
	$data['meta']['password']="salamaki";
	$data['response']['records'][0]['municipalityId']="11";
	$data['response']['records'][0]['plateNo']="EEM5202";
	$data['response']['records'][0]['dateIn']="311220200520";
	$data['response']['records'][0]['weight']="435";
	$data['response']['records'][1]['municipalityId']="11";
	$data['response']['records'][1]['plateNo']="EEM5202";
	$data['response']['records'][1]['dateIn']="311220200520";
	$data['response']['records'][1]['weight']="435";
	
	echo 'Array:<br><pre>
	$data=array();
	$data["meta"]["code"]="200";
	$data["meta"]["username"]="idim";
	$data["meta"]["password"]="salamaki";
	$data["response"]["records"][0]["municipalityId"]="11";
	$data["response"]["records"][0]["plateNo"]="EEM5202";
	$data["response"]["records"][0]["dateIn"]="311220200520";
	$data["response"]["records"][0]["weight"]="435";
	$data["response"]["records"][1]["municipalityId"]="11";
	$data["response"]["records"][1]["plateNo"]="EEM5202";
	$data["response"]["records"][1]["dateIn"]="311220200520";
	$data["response"]["records"][1]["weight"]="435";
	</pre>';
	
	/*
	$data['meta']['date']='31122019';
	$data['meta']['username']='idim';
	$data['meta']['password']='salamaki';
	$data['response']['records'][0]['municipalityId']='11';
	$data['response']['records'][0]['plateNo']='EEM5202';
	$data['response']['records'][0]['dateIn']='311220200520';
	$data['response']['records'][0]['weight']='435';
	$data['response']['records'][1]['municipalityId']='11';
	$data['response']['records'][1]['plateNo']='EEM5202';
	$data['response']['records'][1]['dateIn']='311220200520';
	$data['response']['records'][1]['weight']='435';
	*/
	$json = json_encode($data);
	

	// Attach encoded JSON string to the POST fields
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

	// Set the content type to application/json
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

	// Return response instead of outputting
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Execute the POST request
	$result = curl_exec($ch);

	// Close cURL resource
	curl_close($ch);
	//echo $result;
	
	
	echo 'json:<br>'.$json;
	echo '<hr>';
	echo '<br>';
	$data2 = json_decode($result,true);
	
	echo '<br>';
	echo 'Records: '.sizeof($data2['response']['records']).'<br>';
	
	//print_r($data2);
	echo '<br><br>';
	
	//echo $data2->meta->code.'<br>';
	//echo $data2->meta->date.'<br>';
	//echo $data2->response->records[1]->municipalityId.'<br>';
	echo 'User details:<br>';
	echo 'Code: '.$data2['meta']['code'].'<br>';
	echo 'Date: '.$data2['meta']['date'].'<br>';
	echo 'Username: '.$data2['meta']['username'].'<br>';
	echo 'Password: '.$data2['meta']['password'].'<br>';
	echo '<hr>';
	for($i=0;$i<sizeof($data2['response']['records']);$i++){
		echo 'MunicipalityId: '.$data2['response']['records'][$i]['municipalityId'].'<br>';	
		echo 'PlateNo: '.$data2['response']['records'][$i]['plateNo'].'<br>';	
		echo 'DateIn: '.$data2['response']['records'][$i]['dateIn'].'<br>';	
		echo 'Weight: '.$data2['response']['records'][$i]['weight'].'<br><br>';	
		echo '<hr>';		
	}

?>