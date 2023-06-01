<?php
	ini_set('memory_limit', '1024M');
	//$url="http://94.130.246.21:16103/pinger/getspechearts";
	$url="https://smartspeech.ddns.net/pinger/getspechearts";
	$ch = curl_init( $url );
	# Setup request to send json via POST.
	//$payload = json_encode( array( "AccountID" => "7" ) );
	$payload = json_encode( array( "PlayerID" => "2" ) ); //14
	
	curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	# Return response instead of printing.
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	# Send request.
	$result = curl_exec($ch);
	curl_close($ch);
	# Print response.
	//echo "<pre>$result</pre>";
	$data = json_decode($result);
	//print_r($data);
	echo 'Total records: '.sizeof($data).'<br>';
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
		//echo 'time:'.$key.'- records:'.$val['count'].'- total:'.$val['heartrate'].'- Avg:'.($val['heartrate']/$val['count']).'<br>';
		$labels.="'".$key."',";
		$dataGraph.=intval($val['heartrate']/$val['count']).',';
		// to know what's in $item
		//echo '<pre>'; var_dump($item);
	}
	$labels=mb_substr($labels, 0, -1);
	$dataGraph=mb_substr($dataGraph, 0, -1);
?>
<!doctype html>
<html>

<head>
	<title>Line Chart</title>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
	
	<!-- <style src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css"></style> -->
	<style>
		canvas {
			-moz-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}
	</style>
</head>

<body>
	<div class="chart-container" style="position: relative; height:20vh; width:100%">
		<canvas id="myChart"></canvas>
	</div>

	<br>
	
	<script>
	var ctx = document.getElementById('myChart').getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph?>],
				backgroundColor: ['rgba(255, 99, 132, 0.2)'],
				borderColor: ['rgba(255, 99, 132, 1)'],
				borderWidth: 3,
				lineTension: 0.01,
				fill: false,
				lineWidth: 10
				
			}],
			options: {
				lineWidth: 10
			}
		}
	});
	</script>
</body>

</html>
