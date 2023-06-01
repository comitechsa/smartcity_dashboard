<?php



?>
<html>
	<head>
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

		<video width="1080" height="640" controls="true" autoplay poster="" id="video">
			<source type="video/mp4" src="screen_gaze_screen_recording_2020_08_12_12_58_33_223.mp4"></source>
			<!-- <source type="video/mp4" src="http://94.130.246.21:16103/files/bizzareelements_gaze_screen_recording_2020_08_28_17_44_59_930.mp4"></source>-->
			<!-- <source type="video/mp4" src="http://www.w3schools.com/html/mov_bbb.mp4"></source>-->
		</video>

		<div id="status" class="incomplete">
		<span>Play status: </span>
		<span class="status complete">COMPLETE</span>
		<span class="status incomplete">INCOMPLETE</span>
		<br />
		</div>
		<div>
		<span id="played">0</span> seconds out of 
		<span id="duration"></span> seconds. (only updates when the video pauses)
		</div>
		<script>
			var video = document.getElementById("video");

			var timeStarted = -1;
			var timePlayed = 0;
			var duration = 0;
			// If video metadata is laoded get duration
			if(video.readyState > 0)
			  getDuration.call(video);
			//If metadata not loaded, use event to get it
			else
			{
			  video.addEventListener('loadedmetadata', getDuration);
			}
			// remember time user started the video
			function videoStartedPlaying() {
			  timeStarted = new Date().getTime()/1000;
			}
			function videoStoppedPlaying(event) {
			  // Start time less then zero means stop event was fired vidout start event
			  if(timeStarted>0) {
				var playedFor = new Date().getTime()/1000 - timeStarted;
				timeStarted = -1;
				// add the new ammount of seconds played
				timePlayed+=playedFor;
			  }
			  document.getElementById("played").innerHTML = Math.round(timePlayed)+"";
			  // Count as complete only if end of video was reached
			  if(timePlayed>=duration && event.type=="ended") {
				document.getElementById("status").className="complete";
			  }
			}

			function getDuration() {
			  duration = video.duration;
			  document.getElementById("duration").appendChild(new Text(Math.round(duration)+""));
			  console.log("Duration: ", duration);
			}

			video.addEventListener("play", videoStartedPlaying);
			video.addEventListener("playing", videoStartedPlaying);

			video.addEventListener("ended", videoStoppedPlaying);
			video.addEventListener("pause", videoStoppedPlaying);
		</script>
	</body>
</html>