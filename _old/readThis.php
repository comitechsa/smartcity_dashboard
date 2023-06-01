<?php
ini_set('memory_limit', '1024M');
$test = '{number: +306988544560, datetime: 2020-12-14 11:34:39.159}';

 //$pieces= explode(",", $test);
 $substring1 = substr($test, 8, strpos($test, ",")-8);
echo ($substring1).'<br>';
//echo strpos($test, ",");
 $substring2 = substr($test, (strpos($test, ",")+12),23);
echo ($substring2);
echo '<br><br><br><br>';
			$substring2 = substr($test, (strpos($test, ",")+12),23);
			$substringDate = substr($substring2, 0,10);
			$substringTime = substr($substring2, 11,8);
echo $substringDate.'<br>';
echo $substringTime.'<br>';
		exit;


	$url="https://bd4qol.ddns.net/testing/mobile_test_data";
	$ch = curl_init( $url );
	
	//curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	# Return response instead of printing.
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	# Send request.
	$result = curl_exec($ch);
	curl_close($ch);
	# Print response.
	//echo "<pre>$result</pre>";
	//exit;
	$data = json_decode($result);
	//print_r($data);
	//print_r($data);
	echo 'Total records: '.sizeof($data).'<br>';
	print_r($data[0]);
	echo '<br><br>';
	$var = get_object_vars($data[0]);
	var_dump($var);
	echo $var["lat"].'<br>';
	echo $var["lng"].'<br>';
	echo $var["wifi"].'<br>';
	echo $var["datetime"].'<br>';
	echo $var["activity"].'<br>';
	echo $var["id"].'<br>';
	echo $var["lastcall"].'<br>';
	echo $var["lastmessage"].'<br>';
	echo sizeof($var["usage"]);
	
	//App Usage: asana - app, duration: 0:24:18.000000 [2018-01-01 00:00:00.000, 2020-12-14 13:00:47.090158], 
	//App Usage: com - tinder, duration: 0:21:51.000000 [2018-01-01 00:00:00.000, 2020-12-14 13:00:47.090158], 
	//App Usage: facebook - orca, duration: 1:46:41.000000 [2018-01-01 00:00:00.000, 2020-12-14 13:00:47.090158], 
	//App Usage: google - gm, duration: 0:00:41.000000 [2018-01-01 00:00:00.000, 2020-12-14 13:00:47.090158]]" 
	 //["id"]=> string(1) "1" ["lastcall"]=> string(58) "{number: +306988544560, datetime: 2020-12-14 11:34:39.159}" 
	 //["lastmessage"]=> string(66) "{datetime: 2020-12-14 12:24:48.256, kind: SmsMessageKind.Received}" } 
	
	//echo $data[0]->['lat'];
	//echo $data[0]["lat"];
	//echo $data[0]->lat;
	//echo sizeof($data[0]->{"usage"});
	exit;
	//print_r($data[0]);
	//echo $data[0]->id;
	$counter=0;
	$result = [];
	for($i=0;$i<sizeof($data);$i++){
		$result[$data[$i]->timestamp]['count']=$result[$data[$i]->timestamp]['count']+1;
		$result[$data[$i]->timestamp]['heartrate']=$result[$data[$i]->timestamp]['heartrate']+$data[$i]->heartrate;	
		
		//$result[$data[$i]->timestamp]['heartrate']=$result[$data[$i]->timestamp]['heartrate']+$data[$i]->heartrate;		
	}
	
	$dataRes=array();
	$counter=0;
	foreach($result as $key=>$val) {
		//echo 'time:'.$key.'- records:'.$val['count'].'- total:'.$val['heartrate'].'- Avg:'.($val['heartrate']/$val['count']).'<br>';
		$dataRes[$counter]['time']=$key;
		$dataRes[$counter]['heartrate']=($val['heartrate']/$val['count']);
		$dataRes[$counter]['timeval']=intval(substr($dataRes[$counter]['time'],0,4).substr($dataRes[$counter]['time'],5,2).substr($dataRes[$counter]['time'],8,2).substr($dataRes[$counter]['time'],11,2).substr($dataRes[$counter]['time'],14,2).substr($dataRes[$counter]['time'],17,2));
		$counter++;
		//echo '<pre>'; var_dump($item);
		//echo $dataRes[0]['time'].'-'.$dataRes[0]['heartrate'].' - '.$dataRes[0]['timeval'].'<br>';
	}
//echo $dataRes[0]['time'].'-'.$dataRes[0]['heartrate'].'///'.$dataRes[0]['timeval'].'<br>';
//print_r($dataRes);
//exit;
//Array ( [0] => Array ( [time] => 2020/09/18-11:02:29 [heartrate] => 43.812210083008 [timeval] => 20200918110229 )
echo 'Ομαδοποιημένες εγγραφές: '.sizeof($dataRes).'<br>';
$counter=0;
foreach($dataRes as $item){
	$counter++;
	echo $counter.') '.$item['time'].' '.intval($item['heartrate']).' '.$item['timeval'].'<br>';
}
exit;





foreach($dataRes as $i => $item) {
    echo $item[$i]['count'].$item[$i]['heartrate'].'<br>';

    // $array[$i] is same as $item
}
exit;
foreach($dataRes as $i => $item) {
    echo $i;$item[$i]['time'].$item[$i]['heartrate'].'<br>';
}
exit;


	//foreach($result as $key=>$val) {
	//	$dataRes[$counter]['time']=$key;
	//	$dataRes[$counter]['heartrate']=($val['heartrate']/$val['count']);
	//	$dataRes[$counter]['timeval']=intval(substr($dataRes[$counter]['time'],0,4).substr($dataRes[$counter]['time'],5,2).substr($dataRes[$counter]['time'],8,2).substr($dataRes[$counter]['time'],11,2).substr($dataRes[$counter]['time'],14,2).substr($dataRes[$counter]['time'],17,2));
	//	$counter++;
	//}	
	

exit;


foreach($dataRes as $i => $item) {
    echo $item[$i]['count'].$item[$i]['heartrate'].'<br>';

    // $array[$i] is same as $item
}

exit;


	header("Content-Type: text/html; charset=utf-8");
    $params = array(
		"AccountID" => "11"
    );
	//AccountID":"11
	$url="http://94.130.246.21:16103/pinger/getspechearts";
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);    
	curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
	curl_setopt($curl, CURLOPT_TIMEOUT, 60);

	$result = curl_exec ($curl) or die(curl_error($curl)); 
	var_dump($result);
	exit;
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
