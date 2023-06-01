<?php
$id=1;
echo resCounter($id).'<br>';
$id=2;
echo resCounter($id).'<br>';
$id=3;
echo resCounter($id).'<br>';
$id=4;
echo resCounter($id).'<br>';
$id=5;
echo resCounter($id).'<br>';
$id=6;
echo resCounter($id).'<br>';
$id=7;
echo resCounter($id).'<br>';
$id=8;
echo resCounter($id).'<br>';
$id=9;
echo resCounter($id).'<br>';
$id=10;
echo resCounter($id).'<br>';
$id=11;
echo resCounter($id).'<br>';
$id=12;
echo resCounter($id).'<br>';
$id=13;
echo resCounter($id).'<br>';
$id=14;
echo resCounter($id).'<br>';
$id=15;
echo resCounter($id).'<br>';
$id=16;
echo resCounter($id).'<br>';
	function resCounter($id){
		$url="http://94.130.246.21:16103/pinger/getcard";
		$ch = curl_init( $url );
		$payload = json_encode( array( "PlayerID" => "$id" ) );
		echo $payload.'<br>';
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$result = curl_exec($ch);
		//echo "<pre>$result</pre>";
		curl_close($ch);
		$data = json_decode($result);
		//echo 'size:'.sizeof($data);
		return sizeof($data);
	}

?>