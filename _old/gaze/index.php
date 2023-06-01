<?php

$data=array();
//$data[0][]='xx';
//$data[0][]='yy';
//echo $data[0][0];
//exit;
//https://www.patrick-wied.at/static/heatmapjs/
	$t1 = '1035.897,602.9077 0.3117676';
	//$t2 = '1019.327,611.0301 0.3460083';
	//$t3 = '1019.327,611.0301 0.3754272';

	$pattern = '/[, ]/';
	//$string = $t1;

	//echo '<pre>', print_r( preg_split( $pattern, $string ), 1 ), '</pre>';
	//exit;
	
	$myfile = fopen("gaze.txt", "r") or die("Unable to open file!");

	while(!feof($myfile)) {
		$gazeVar = fgets($myfile);
		//echo ($gazeVar).'<br>';

		//echo fgets($myfile) . "<br>";
		//echo '<pre>', print_r( preg_split( $pattern, $gazeVar ), 1 ), '</pre>';
		$values=preg_split( $pattern, $gazeVar );
		
		if(sizeof($values)==3){
			//echo 'size:'.sizeof($values).'<br>';
			//print_r($values);	
			//echo floatval($values[2]);
			$data[floatval($values[2])]['lat'][]=$values[0];
			$data[floatval($values[2])]['lng'][]=$values[1];
			$data[floatval($values[2])]['clk'][]=$values[2];
			//echo 'check:'.$data[0]['lat'][0];
		}
	}
	fclose($myfile);

	for($i=0;$i<sizeof($data);$i++){
		for($n=0;$n<sizeof($data[$i]['lat']);$n++){
			echo $i.'<br>';
			?>
			<script>
				  var point = {
					x: <?=$data[$i]['lat'][$n]?>,
					y: <?=$data[$i]['lng'][$n]?>,
					value: 1
				  };
				  points.push(point);


			</script>
			<?		
		}
		?>
		<script>
			var data = { max: max, data: points };
			animationData.push(data);
		</script>
	<?
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Point Configuration Example</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="index, follow" />
  <meta name="description" content="This example shows configuration on a point basis with minimal configuration. This gives you the freedom of having datapoints with different radi of influence" />
  <meta name="keywords" content="point basis config heatmap, heatmap.js, heatmap config, radius influence heatmap" />
  <link rel="stylesheet" href="css/common.css" />
  <link rel="stylesheet" href="css/example-common.css" />
  <link rel="stylesheet" href="css/prism.css" />
      <style>
    #share {
      display:none;
      float:right;
      font-size:14px;
      line-height:170%;
    }
    .symbol {display:inline !important; float:right;}
  </style>

</head>
<body>
	<div class="wrapper">
		<br style="clear:both" />
		<div class="demo-wrapper">
			<div class="heatmap"></div>
		</div>

  </div>
  <script src="js/heatmap.min.js"></script>
  <script>
    window.onload = function() {

      function generateRandomData(len) {
        // generate some random data
        var points = [];
        var max = 0;
        var width = 840;
        var height = 400;

        while (len--) {
          var val = Math.floor(Math.random()*100);
          var radius = Math.floor(Math.random()*60);

          max = Math.max(max, val);
          var point = {
            x: Math.floor(Math.random()*width),
            y: Math.floor(Math.random()*height),
            value: val,
            // radius configuration on a point basis
            radius: radius
          };
          points.push(point);
        }

        var data = { max: max, data: points };
        return data;
      }

      var heatmapInstance = h337.create({
        container: document.querySelector('.heatmap')
      });

      // generate 200 random datapoints
      var data = generateRandomData(300);
      heatmapInstance.setData(data);


      document.querySelector('.trigger-refresh').onclick = function() {
        heatmapInstance.setData(generateRandomData(400));
      };

        document.querySelector('.thankyou').onclick = function() {
          ga('send','event', 'social', 'thanks');
          document.querySelector('.thankyou').style.display = 'none';
          document.querySelector('#share').style.display = 'block';
        };
        var btns = document.querySelectorAll('.symbol');
        for (var i = 0; i < btns.length; i++) {
          btns[i].onclick = function() {
            ga('send', 'event','social', 'share');
          };
        }

    };
  </script>
  <script src="js/prism.js"></script>

</body>
</html>