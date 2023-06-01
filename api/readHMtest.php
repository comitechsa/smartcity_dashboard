<?php

	$child=$_GET['child'];
	$iteration=$_GET['iteration'];
	$episode=$_GET['episode'];
	$data=readCard($child);
	
	if($episode==0){
		$video="beecalibration_gaze_screen_recording.mp4";
		$episodeFiles=(explode(";",$data[$iteration]->EpisodeData->{'Episode 00'}->Files));
		$gazeFile=$episodeFiles[0];
		$timeStart=$data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->Start;
		$timeEnd = $data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->End;
	} else if($episode==1){
		$video="rika&bee_gaze_screen_recording.mp4";
		$episodeFiles=(explode(";",$data[$iteration]->EpisodeData->{'Episode 01'}->Files));
		$gazeFile=$episodeFiles[1];
		$timeStart=$data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->Start;
		$timeEnd = $data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->End;
	} else if($episode==2){
		$video="upsidedwarf_gaze_screen.mp4";
		$episodeFiles=(explode(";",$data[$iteration]->EpisodeData->{'Episode 02'}->Files));
		$gazeFile=$episodeFiles[1];
		$timeStart=$data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->Start;
		$timeEnd = $data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->End;
	} else if($episode==3){
		$video="bizzareelements_gaze_screen_recording.mp4";
		$episodeFiles=(explode(";",$data[$iteration]->EpisodeData->{'Episode 03'}->Files));
		$gazeFile=$episodeFiles[0];
		$timeStart=$data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->Start;
		$timeEnd = $data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->End;
	} else if($episode==8){
		$video="dreamthings_gaze_screen_recording.mp4";
		$episodeFiles=(explode(";",$data[$iteration]->EpisodeData->{'Episode 08'}->Files));
		$gazeFile=$episodeFiles[0];
		$timeStart=$data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->Start;
		$timeEnd = $data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->End;
	} else if($episode==11){
		$video="ep11_gaze_screen_recording.mp4";
		$episodeFiles=(explode(";",$data[$iteration]->EpisodeData->{'Episode 11'}->Files));
		$gazeFile=$episodeFiles[0];
		$timeStart=$data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->Start;
		$timeEnd = $data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->End;
	} else if($episode==16){
		$video="gaze_screen_recording_16.mp4";
		$episodeFiles=(explode(";",$data[$iteration]->EpisodeData->{'Episode 16'}->Files));
		$gazeFile=$episodeFiles[0];
		$timeStart=$data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->Start;
		$timeEnd = $data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->End;
	}


	function readCard($id){
		$url="192.168.222.161:3000/pinger/getcard";
		$ch = curl_init( $url );
		$payload = json_encode( array( "PlayerID" => "$id" ) );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$result = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($result);
		return $data;
	}
	

	ini_set('memory_limit', '1024M');
	$data=array();


	$pattern = '/[, ]/';
	$myfile = file_get_contents("http://192.168.222.161/files/".$gazeFile);
	set_time_limit(0);

echo $timeStart.' '. $timeEnd.' '.$myfile;
	
exit;

	$lines = explode("\n", $myfile);
	for($line=0;$line<sizeof($lines);$line++){
		if($line==0){
			$dimentions = explode("x",$lines[$line]);
		} else{
			$values=preg_split( $pattern, $lines[$line] );
			if(sizeof($values)==3 && $line>0){
				$datax[intval($values[2])]['lat'][]=($values[0]>0?$values[0]:0)*1080/$dimentions[0];
				$datax[intval($values[2])]['lng'][]=($values[1]>0?$values[1]:0)*675/$dimentions[1];
				$datax[intval($values[2])]['clk'][]=intval($values[2]);
			}
		}

	}
	
	/*
	//$fp = file_put_contents('gaze3.txt', $myfile, FILE_APPEND);
	//$myfile = fopen("gaze3.txt", "r") or die("Unable to open file!");
	$line=0;
	while(!feof($myfile)) {
		$gazeVar = fgets($myfile);
		if($line==0){
			$dimentions = explode("x",$gazeVar);
			//echo $dimentions[1];
			$line++;
		}
		$values=preg_split( $pattern, $gazeVar );
		if(sizeof($values)==3 && $line>0){
			//$datax[intval($values[2])]['lat'][]=($values[0]>0?$values[0]:0);
			//$datax[intval($values[2])]['lng'][]=($values[1]>0?$values[1]:0);
			////$datax[intval($values[2])]['clk'][]=$values[2];
			//$datax[intval($values[2])]['clk'][]=intval($values[2]);
			
			$datax[intval($values[2])]['lat'][]=($values[0]>0?$values[0]:0)*1080/$dimentions[0];
			$datax[intval($values[2])]['lng'][]=($values[1]>0?$values[1]:0)*675/$dimentions[1];
			//$datax[intval($values[2])]['clk'][]=$values[2];
			$datax[intval($values[2])]['clk'][]=intval($values[2]);
		}
	}
	fclose($myfile);
	*/

	
	
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Animation Heatmap with video</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/gaze/css/common.css" />
  <link rel="stylesheet" href="/gaze/css/example-common.css" />
  <link rel="stylesheet" href="/gaze/css/prism.css" />
  <style>
	/*
		#share {
		  display:none;
		  float:right;
		  font-size:14px;
		  line-height:170%;
		}  
	*/

    .symbol {display:inline !important; float:right;}
    .demo-wrapper { position:relative;}
    /* animation player css */
    .timeline-wrapper {
        position:absolute;
        top: 10px;
        left:10px;
        right:10px;
        height:30px;
        background:white;
        transition:1s ease all;
        border-radius:1px;
        box-shadow:0 1px 5px rgba(0,0,0,.65)
    }
    .heatmap-timeline {
        position:absolute;
        top:0;
        right:15px;
        left:80px;
        height:100%;
    }

    .heatmap-timeline .line {
        position:absolute;
        left:0;
        right:0;
        top:15px;
        height:2px;
        background:#d7d7d7;
    }
    .heatmap-timeline .time-point.active {
        background:black;
    }
    .heatmap-timeline .time-point {
        position:absolute;
        background:white;
        border:2px solid #272727;
        width:8px;
        height:8px;
        border-radius:100%;
        cursor:pointer;
        top:15px;
        transform:translateX(-50%) translateY(-50%);
    }
    .heatmap-timeline .time-point:hover {
        box-shadow:0 0 5px black;
    }
    .timeline-wrapper button {
      position:absolute;
      outline:none;
      color: black;
      background: #f2f2f2;
      width: 65px;
      height: 100%;
      cursor: pointer;
      border: none;
      text-transform:uppercase;
      border-top-left-radius:3px;
      border-bottom-left-radius:3px;
    }
    .heatmap-timeline .time-point.active {
      background:black;
    }
    /* end animation player css */
  </style>
		<!-- Video style-->
		<style>
			#status span.status {
			  display: none;
			  font-weight: bold;
			}
			span.status.complete {
			  color: green;
			}
			span.status.incomplete {
			  color: red;
			}
			#status.complete span.status.complete {
			  display: inline;
			}
			#status.incomplete span.status.incomplete {
			  display: inline;
			}
		</style>

</head>
<body>
	<div class="wrapper" style="width:1080px;">
		<div class="demo-wrapper" style="height:675px;border:1px solid black;">
			<div class="heatmap">
				<video width="1080" height="675"   poster="" id="video" style="z-index:-1;"><!-- controls="false"-->
					<source type="video/mp4" src="https://smartspeech.ddns.net/files/<?=$video?>"></source>
					<!-- <source type="video/mp4" src="http://94.130.246.21:16103/files/bizzareelements_gaze_screen_recording_2020_08_28_17_44_59_930.mp4"></source>-->
				</video>
				<!-- 
				<div id="status" class="incomplete">
				<span>Play status: </span>
				<span class="status complete">COMPLETE</span>
				<span class="status incomplete">INCOMPLETE</span>
				<br />		
				-->
			</div>
			<div>
				<!-- <span id="played">0</span> seconds out of -->
				<!-- <span id="duration"></span> seconds. (only updates when the video pauses)-->
			</div>
		</div>
		<div class="timeline-wrapper"></div>
	</div>
	<div class="demo-controls">
		<!-- <button class="trigger-refresh btn" data-fps="1">Set speed to 1 frame per second</button> -->
		<br style="clear:both" />
	</div>
	<!-- </div>-->
	<script src="/gaze/js/heatmap.min.js"></script>
	<script>
	window.onload = function() {
		/*
			function generateRandomData(len) {
			// generate some random data
			var points = [];
			var max = 0;
			var width = 1080;
			var height = 675;

			while (len--) {
				//var val = Math.floor(Math.random()*100);
				var val = 1;
				max = Math.max(max, val);
				var point = {
					x: Math.floor(Math.random()*width),
					y: Math.floor(Math.random()*height),
					value: val
				};
				points.push(point);
			}
			var data = { max: max, data: points };
			//alert(JSON.stringify(data, null));
			//{"max":99,"data":[{"x":800,"y":553,"value":86},{"x":596,"y":376,"value":8},{"x":503,"y":553,"value":26},{"x":326,"y":5,"value":64},{"x":632,"y":503,"value":39},{"x":620,"y":163,"value":89},{"x":515,"y":5,"value":95},
			//alert(object.data());
			//console.dir(data);
			return data;
			//}
		*/


		function $(selector) {
			return document.querySelectorAll(selector);
		}
		
		function AnimationPlayer(options) {
			this.heatmap = options.heatmap;
			this.data = options.data;
			this.interval = null;
			this.animationSpeed = options.animationSpeed || 300;
			this.wrapperEl = options.wrapperEl;
			this.isPlaying = false;
			this.init();
		};

		AnimationPlayer.prototype = {
			init: function() {
				var dataLen = this.data.length;
				this.wrapperEl.innerHTML = '';
				var playButton = this.playButton = document.createElement('button');
				playButton.onclick = function() {
					if (this.isPlaying) {
						this.stop();
						video.pause();
					} else {
						this.play();
						video.play();
					}
					this.isPlaying = !this.isPlaying;
				}.bind(this);
				playButton.innerText = 'play';

				this.wrapperEl.appendChild(playButton);
				var events = document.createElement('div');
				events.className = 'heatmap-timeline';
				events.innerHTML = '<div class="line"></div>';
				
				for (var i = 0; i < dataLen; i++) {
					var xOffset = 100/(dataLen - 1) * i;
					var ev = document.createElement('div');
					ev.className = 'time-point';
					ev.style.left = xOffset+'%';
					ev.onclick = (function(i) {
						return function() {
							this.isPlaying = false;
							this.stop();
							this.setFrame(i);
							video.currentTime = i;
						}.bind(this);
					}.bind(this))(i);
					events.appendChild(ev);
			
				}
				this.wrapperEl.appendChild(events);
				this.setFrame(0);
			},
			play: function() {
				var dataLen = this.data.length;
				this.playButton.innerText = 'pause';
				this.interval = setInterval(function() {
					this.setFrame(++this.currentFrame%dataLen);
				}.bind(this), this.animationSpeed);
			},
			stop: function() {
				clearInterval(this.interval);
				this.playButton.innerText = 'play';
			},
			setFrame: function(frame) {
				this.currentFrame = frame;
				var snapshot = this.data[frame];
				this.heatmap.setData(snapshot);
				var timePoints = $('.heatmap-timeline .time-point');
				for (var i = 0; i < timePoints.length; i++) {
					timePoints[i].classList.remove('active');
				}
				timePoints[frame].classList.add('active');
				if(frame==(timePoints.length-1)) this.stop();
			},
			setAnimationData: function(data) {
				this.isPlaying = false;
				this.stop();
				this.data = data;
				this.init();
			},
			setAnimationSpeed: function(speed) {
				this.isPlaying = false;
				this.stop();
				this.animationSpeed = speed;
			}
		};

		var heatmapInstance = h337.create({
			container: document.querySelector('.heatmap')
		});

		// animationData contains an array of heatmap states
		var animationData = [];
		var points = [];
		<?
			for($i=0;$i<sizeof($datax);$i++){
				$point="";
				for($n=0;$n<sizeof($datax[$i]['lat']);$n++){
					?>
					var point = {
						x: <?=intval($datax[$i]['lat'][$n])?>,
						y: <?=intval($datax[$i]['lng'][$n])?>,
						value: 1
					};
					points.push(point);
					<?
				}
				?>
				var data = { max: 1, data: points }; 
				animationData.push(data);
				var points = [];
				<?
			}
		?>	
		//for (var i = 0; i < 20; i++) {
		//	animationData.push(generateRandomData(10));
		//}
		var player = new AnimationPlayer({
			heatmap: heatmapInstance,
			wrapperEl: document.querySelector('.timeline-wrapper'),
			data: animationData//,
			//animationSpeed: 500
		});

		//var controlButtons = $('.trigger-refresh');
		//for(var i = 0; i < controlButtons.length; i++) {
		//controlButtons[i].onclick = function() {
		//var fps = this.dataset.fps;
		var fps = 1;
		player.setAnimationSpeed(1/(+fps) * 1000);
		//};
		// }
    };
	</script>
	<script src="/gaze/js/prism/prism.js"></script>
	<!-- Video script-->
	<script>
		var video = document.getElementById("video");
		var timeStarted = -1;
		var timePlayed = 0;
		var duration = 0;
		// If video metadata is laoded get duration
		if(video.readyState > 0)
			getDuration.call(video);
			//If metadata not loaded, use event to get it
		else {
			video.addEventListener('loadedmetadata', getDuration);
		}
		// remember time user started the video
		function videoStartedPlaying() {
			timeStarted = new Date().getTime()/1000;
		}
		function videoStoppedPlaying(event) {
			// Start time less then zero means stop event was fired vidout start event
			//if(timeStarted>0) {
			//	var playedFor = new Date().getTime()/1000 - timeStarted;
			//	timeStarted = -1;
			//	// add the new ammount of seconds played
			//	timePlayed+=playedFor;
			//}
			//document.getElementById("played").innerHTML = Math.round(timePlayed)+"";
			// Count as complete only if end of video was reached
			//if(timePlayed>=duration && event.type=="ended") {
			//document.getElementById("status").className="complete";
			//}
		}

		function getDuration() {
			//duration = video.duration;
			//document.getElementById("duration").appendChild(new Text(Math.round(duration)+""));
			//console.log("Duration: ", duration);
		}

		//video.addEventListener("play", videoStartedPlaying);
		//video.addEventListener("playing", videoStartedPlaying);
		//video.addEventListener("ended", videoStoppedPlaying);
		//video.addEventListener("pause", videoStoppedPlaying);
	</script>
</body>
</html>