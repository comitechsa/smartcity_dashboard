<?php
	header("Content-Type: text/html; charset=utf-8");
    $params = array(
		"p1" => "2045"
    );
	$url="http://telematics.oasa.gr/api/?act=getBusLocation";
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
	var_dump( $result );
	//$data = json_decode($result);
	//echo "<pre>"; var_dump( $data ); echo "</pre>"; 
	curl_close($curl);

	//print  a specific entry
	//echo "<br>Farmer id: ".$data[1]->farmerId;
	//echo "<br>Farmer fullname: ".$data[1]->fullname;
?>
