<?php 
//char passphrase[] = "ewicsnpocje14cs9cs2cs";
//print_r($_GET);
//waspmote/index.php?device_id=1&data=xxx&temp=20.01
//waspmote/index.php?data=20.02:45.86:101690.35
//waspmote.php?data=%s:%s:%s:%s:%s:%s:%s:%s", deviceID,tempString,humidityString,pressureString,concentrationString,noiseString,o3String,passphrase
//2:13.98:80.53:100572.92:0.180256:72.143699:-1.000000:ewicsnpocje14cs9cs2c
//wan.gr/ppcity/waspmote.php?data=2:13.98:80.53:100572.92:0.180256:72.143699:-1.000000:ewicsnpocje14cs9cs2cs
//wan.gr/ppcity/waspmote.php?data=2:13.84:80.91:100607.12:0.167358:73.000198:-1.000000:ewicsnpocje14cs9cs2c
	$log =  '==='.date("Y-d-m H:i:s ")." ".$arr."\n";
	
	$req_dump = print_r($_REQUEST, true);
	file_put_contents('request.log', $log, FILE_APPEND);
	$fp = file_put_contents('request.log', $req_dump, FILE_APPEND);
	echo 'Done<br>';
	var_dump(http_response_code());
exit;
$whatIWant = substr($_GET['data'], strpos($data, "=") + 0); 
$arr=(explode(":",$whatIWant));
print_r($arr);
//print_r($arr);
//exit;
	$host="localhost";
	//$user="wangrjz_waspmote";
	$user="waspmote";
	//$database="wangrjz_waspmote";
	$database="waspmote";
	$pass="qwe#123!@#";
	mysql_set_charset('utf8');
	$connection=mysql_connect($host,$user,$pass) or die ("could not connect");
	$db=mysql_select_db($database,$connection) or die ("could not connect to database");
	mysql_set_charset('utf8');
	$myData =  $_SERVER["REQUEST_URI"];
	//$myData =  str_replace("/waspmote/","",$myData);
	//$myData =  str_replace("index.php","",$myData);
	$passphrase = "ewicsnpocje14cs9cs2cs";
	$passphrase2 = "ewicsnpocje14cs9cs2c";
	$passphrase3 = "ewicsnpocje14cs9cs2cs";
	$passphrase = "ewicsnpocje14";
	if($passphrase==$arr[7] || $passphrase2==$arr[7] || $passphrase3==$arr[7]){
		date_default_timezone_set("Europe/Athens");
		$insertRes = mysql_query("INSERT INTO data (device_id, mydata, temperature,humidity,pressure,concentration,noise,o3,date_insert) 
		VALUES ('".$arr[0]."','" . $myData . "','".$arr[1]."','".$arr[2]."','".$arr[3]."','".$arr[4]."','".$arr[5]."','".$arr[6]."','" . date('Y-m-d H:i:s') . "')");

		//echo "INSERT INTO data (device_id, mydata, date_insert) VALUES ('1','" . str_replace("/waspmote","",$myData) . "', '" . date('Y-m-d H:i:s') . "')";
		//echo 'Data:'.$myData;
		//echo "INSERT INTO data (device_id, mydata, date_insert) VALUES ('".$arr[0]."','" . $myData . "','".$arr[0]."','".$arr[1]."','".$arr[2]."','" . date('Y-m-d H:i:s') . "')";
		if($arr[0]==1){
			$curlUrl = "https://api.thingspeak.com/update/?api_key=0EPJD5SMTH6JCP80&field1=".$arr[1]."&field2=".$arr[2]."&field3=".$arr[3]."&field4=".$arr[4]."&field5=".$arr[5]."&field6=".$arr[6];
		}
		if($arr[0]==2){
			$curlUrl = "https://api.thingspeak.com/update/?api_key=LDWNC3YRO8LOAJPX&field1=".$arr[1]."&field2=".$arr[2]."&field3=".$arr[3]."&field4=".$arr[4]."&field5=".$arr[5]."&field6=".$arr[6];
		}
		
		if($arr[0]==3){
			$curlUrl = "https://api.thingspeak.com/update/?api_key=USR09AZ7JG67DX1W&field1=".$arr[1]."&field2=".$arr[2]."&field3=".$arr[3]."&field4=".$arr[4]."&field5=".$arr[5]."&field6=".$arr[6];
		}
		
		if($arr[0]==4){
			$curlUrl = "https://api.thingspeak.com/update/?api_key=6HFFW4ZL9NV3WGS0&field1=".$arr[1]."&field2=".$arr[2]."&field3=".$arr[3]."&field4=".$arr[4]."&field5=".$arr[5]."&field6=".$arr[6];
		}
		
		//https://api.thingspeak.com/update?api_key=0EPJD5SMTH6JCP80&field1=0
		//step1
		$cSession = curl_init(); 
		//step2
		curl_setopt($cSession,CURLOPT_URL,$curlUrl);
		curl_setopt($cSession,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($cSession,CURLOPT_HEADER, false); 
		//step3
		$result=curl_exec($cSession);
		//step4
		curl_close($cSession);
		//step5
		echo $insertRes.'/'.$result;
	}

?>