<?php
	$id=$_GET['id'];
	if(intval($id)==0) {
		$id=19;
	}
//14
	//$url="http://94.130.246.21:16103/pinger/getcard";
	//$url="https://smartspeech.ddns.net/pinger/getcard";
	$url="192.168.222.161:3000/pinger/getcard";
	$ch = curl_init( $url );
	# Setup request to send json via POST.
	//$payload = json_encode( array( "AccountID" => "14","PlayerID" => "14", ) );
	//$payload = json_encode( array( "AccountID" => "16","PlayerID" => "20", ) );
	$payload = json_encode( array( "PlayerID" => $id, ) );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	# Return response instead of printing.
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	# Send request.
	$result = curl_exec($ch);
	curl_close($ch);
	# Print response.
	echo "<pre>$result</pre>";
	exit;
	$data = json_decode($result);
	echo '<br><br><br>';
	echo $data[0]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->Start.'<br>';
	print_r($data[0]->EpisodeData->{'Episode 05'}->{'Game 03'});
	exit;
	print_r($data);
	echo '<br><br><br>';
	//echo sizeof($data);
	echo $data[0]->AccountID.'<br>';
	echo ($data[0]->Completed=='true'?'1':'0').'<br>';
	echo $data[0]->EpisodeData->{'Episode 00'}->Files.'<br>';
	echo $data[0]->EpisodeData->{'Episode 00'}->Status.'<br>';
	echo $data[0]->EpisodeData->{'Episode 00'}->{'Time'}->Start.'<br>';
	echo $data[0]->EpisodeData->{'Episode 00'}->{'Time'}->End.'<br>';
	echo '<br>';
	
	echo $data[0]->EpisodeData->{'Episode 01'}->Files.'<br>';
	echo $data[0]->EpisodeData->{'Episode 01'}->Status.'<br>';
	echo $data[0]->EpisodeData->{'Episode 01'}->{'Time'}->Start.'<br>';
	echo $data[0]->EpisodeData->{'Episode 01'}->{'Time'}->End.'<br>';
	echo '<br>';
	
	echo $data[0]->EpisodeData->{'Episode 02'}->Files.'<br>';
	echo $data[0]->EpisodeData->{'Episode 02'}->Status.'<br>';
	echo $data[0]->EpisodeData->{'Episode 02'}->{'Time'}->Start.'<br>';
	echo $data[0]->EpisodeData->{'Episode 02'}->{'Time'}->End.'<br>';
	echo $data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 01'}->Correct.'<br>';
	echo $data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 01'}->Selected.'<br>';
	echo ($data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 01'}->Correct==$data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 01'}->Selected?'1':'0').'<br>';
	echo $data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 02'}->Correct.'<br>';
	echo $data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 02'}->Selected.'<br>';
	echo ($data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 02'}->Correct==$data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 02'}->Selected?'1':'0').'<br>';
	echo $data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 03'}->Correct.'<br>';
	echo $data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 03'}->Selected.'<br>';
	echo ($data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 03'}->Correct==$data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 03'}->Selected?'1':'0').'<br>';
	echo $data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->Start.'<br>';
	echo $data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->End.'<br>';
	echo '<br>';
	echo $data[0]->EpisodeData->{'Episode 03'}->Files.'<br>';
	echo $data[0]->EpisodeData->{'Episode 03'}->Status.'<br>';
	echo $data[0]->EpisodeData->{'Episode 03'}->{'Time'}->Start.'<br>';	
	echo $data[0]->EpisodeData->{'Episode 03'}->{'Time'}->End.'<br>';

	echo '<br>';
	echo $data[0]->EpisodeData->{'Episode 04'}->Files.'<br>';
	echo $data[0]->EpisodeData->{'Episode 04'}->Status.'<br>';
	echo $data[0]->EpisodeData->{'Episode 04'}->{'Time'}->Start.'<br>';	
	echo $data[0]->EpisodeData->{'Episode 04'}->{'Time'}->End.'<br>';
	echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 01'}->{'Sack Size 0'}.'<br>';
	echo ($data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 01'}->{'Sack Size 0'}=='Plank Size 0'?'1':'0').'<br>';
	echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 01'}->{'Sack Size 1'}.'<br>';
	echo ($data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 01'}->{'Sack Size 1'}=='Plank Size 1'?'1':'0').'<br>';
	echo '<br>';
	echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 02'}->{'Sack Size 0'}.'<br>';
	echo ($data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 02'}->{'Sack Size 0'}=='Plank Size 0'?'1':'0').'<br>';
	echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 02'}->{'Sack Size 1'}.'<br>';
	echo ($data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 02'}->{'Sack Size 1'}=='Plank Size 1'?'1':'0').'<br>';
	echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 02'}->{'Sack Size 2'}.'<br>';
	echo ($data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 02'}->{'Sack Size 2'}=='Plank Size 2'?'1':'0').'<br>';
	echo '<br>';
	echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 0'}.'<br>';
	echo ($data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 0'}=='Plank Size 0'?'1':'0').'<br>';
	echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 1'}.'<br>';
	echo ($data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 1'}=='Plank Size 1'?'1':'0').'<br>';
	echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 2'}.'<br>';
	echo ($data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 2'}=='Plank Size 2'?'1':'0').'<br>';
	echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 3'}.'<br>';
	echo ($data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 3'}=='Plank Size 3'?'1':'0').'<br>';
	echo '<br>';
	echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->Start.'<br>';	
	echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->End.'<br>';


	echo '<br>';
	echo $data[0]->Iteration.'<br>';
	echo $data[0]->PlayerID.'<br>';
	echo $data[0]->_id.'<br>';
	echo $data[0]->hideFlags.'<br>';
	echo $data[0]->name.'<br>';
	
	
	//echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 02'}->Correct.'<br>';
	//echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 02'}->Selected.'<br>';
	//echo ($data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 02'}->Correct==$data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 02'}->Selected?'1':'0').'<br>';
	//echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->Correct.'<br>';
	//echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->Selected.'<br>';
	//echo ($data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->Correct==$data[0]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 03'}->Selected?'1':'0').'<br>';
	//echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->Start.'<br>';
	//echo $data[0]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->End.'<br>';
	
	echo $data[0]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 01'}.'<br>';
	exit;
	//echo sizeof($data);
	//print_r($data[0]);
	//echo $data[0]->id;
	$counter=0;
	$result = [];
	for($i=0;$i<sizeof($data);$i++){
		$result[$data[$i]->timestamp]['count']=$result[$data[$i]->timestamp]['count']+1;
		$result[$data[$i]->timestamp]['heartrate']=$result[$data[$i]->timestamp]['heartrate']+$data[$i]->heartrate;	
		//$result[$data[$i]->timestamp]['heartrate']=$result[$data[$i]->timestamp]['heartrate']+$data[$i]->heartrate;		
	}
//print_r($result);
foreach($result as $key=>$val) {
    echo 'time:'.$key.'- records:'.$val['count'].'- total:'.$val['heartrate'].'- Avg:'.($val['heartrate']/$val['count']).'<br>';

    // to know what's in $item
    //echo '<pre>'; var_dump($item);
}
exit;

foreach($result as $i => $item) {
    echo $item[$i]['count'].$item[$i]['heartrate'].'<br>';

    // $array[$i] is same as $item
}

exit;


	header("Content-Type: text/html; charset=utf-8");
    $params = array(
		"AccountID" => "11"
    );
	//AccountID":"11
	$url="http://94.130.246.21:16103/pinger/getcard";
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
