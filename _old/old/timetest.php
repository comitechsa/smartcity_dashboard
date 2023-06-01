<?php
//$total_seconds=65;
//echo showTime(65);
//echo intval(65%60);
//$seconds = intval($total_seconds%60); 
//$total_minutes = intval($total_seconds/60);
//$minutes = $total_minutes%60;
//echo $minutes.':'.$total_minutes.':'.$seconds;
$a=showTime(65);
echo $a;

	function showTime($totalSeconds){
		$seconds = intval($totalSeconds%60); 
//		echo $seconds;
		$minutes = intval($totalSeconds/60);
		//$minutes = $total_minutes%60;
		return sprintf('%02d', $minutes).':'.sprintf('%02d', $seconds);
	}
?>