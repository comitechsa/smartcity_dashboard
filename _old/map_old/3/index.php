<?php
		
?>
<!DOCTYPE html>
<html>
	<head>
		<title>map cluster</title>	
		<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyAzYeS10uHq9OvcW4G3NKRoNT3AJmt1RI0&libraries=places"></script>
		<!--<script src="js/data.json"></script>-->
		<script type="text/javascript" src="js/markerclusterer.js"></script>
		<style>
		  #map-container {
			width: 100%;
		  }
		  #map {
			width: 1200px;
			width: 100%;
			position:relative;
			height: 400px;
		  }
		</style>
    <script>
	<?php

		date_default_timezone_set("Europe/Athens");
		//$host="localhost";
		//$user="waspmote";
		//$database="waspmote";
		//$pass="qwe#123!@#";
		//$connection=mysql_connect($host,$user,$pass) or die ("could not connect");
		//$db=mysql_select_db($database,$connection) or die ("could not connect to database");
		//mysql_set_charset('utf8');
		//$query = "SELECT * FROM `mysensors` where sensor_location!='Wearable' AND sensortype_id=2"; 
		//$result= mysql_query($query) or die ("could not execute");
		//$num_rows = mysql_num_rows($result);
		$resp='';
		$urls="<a href='#'>Παράδειγμα link</a><br>Άλλο κείμενο<br>";
		$infoboxTxt="<b>Informations</b><br><br>".$urls."";
		//while ($row = mysql_fetch_assoc($result)) {
		//	$latlng=(explode(",",$row['sensor_location']));
		//	$resp.='{"dataset":"'.$infoboxTxt.'","sensorname":"'.$row['sensor_name'].'","pin":"img/map/pin/accountancy.png", "longitude": "'.$latlng[1].'", "latitude": "'.$latlng[0].'"},';
		//}
		$resp.='{"infoboxTxt":"'.$infoboxTxt.'","sensorname":"Αθήνα","pin":"img/icons8-marker-16.png", "longitude": "23.735249", "latitude": "37.981902"},';
		$resp.='{"infoboxTxt":"'.$infoboxTxt.'","sensorname":"Θεσ/νίκη","pin":"img/icons8-marker-16.png","longitude": "22.957185", "latitude": "40.624705"}';
		//$resp = substr($resp, 0, -1);

		?>
		var data = [<?=$resp?>];

      function initialize() {
        var center = new google.maps.LatLng(39.803618, 22.606404);
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 6,
          center: center,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var markers = [];
		var InfoWindows = new google.maps.InfoWindow({});
        //for (var i = 0; i < <?=($num_rows)?>; i++) {
		data.forEach(function(dataPhoto) {	
          //var dataPhoto = data1[i];
		 
          var latLng = new google.maps.LatLng(dataPhoto.latitude, dataPhoto.longitude);
          var marker = new google.maps.Marker({
            position: latLng,
			icon: dataPhoto.pin,
			title: dataPhoto.sensorname,
          });
		  
			marker.addListener('click', function() {
				InfoWindows.open(map, this);
				InfoWindows.setContent(dataPhoto.infoboxTxt);
			});
          markers.push(marker);
        });
        var markerCluster = new MarkerClusterer(map, markers, {imagePath: 'img/map/m'});
      }
      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
	</head>
	<body class="loading-overlay-showing" data-plugin-page-transition data-loading-overlay data-plugin-options="{'hideDelay': 500}">

		<div class="body">
			<div role="main" class="main">
				<section class="section section-height-3 section-text-light bg-color-primary border-0 m-0" style="padding:20px;background-color:#fff !important;">					
					<div class="row">
						<div class="col-md-12 col-lg-12 mb-12 mb-lg-12">
							<div id="map-container">
								<div id="map"></div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>

		<!-- Vendor 
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/jquery.appear/jquery.appear.min.js"></script>
		<script src="vendor/jquery.easing/jquery.easing.min.js"></script>
		<script src="vendor/jquery.cookie/jquery.cookie.min.js"></script>
		<script src="vendor/popper/umd/popper.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="vendor/common/common.min.js"></script>
		<script src="vendor/jquery.validation/jquery.validate.min.js"></script>
		<script src="vendor/jquery.easy-pie-chart/jquery.easypiechart.min.js"></script>
		<script src="vendor/jquery.gmap/jquery.gmap.min.js"></script>
		<script src="vendor/jquery.lazyload/jquery.lazyload.min.js"></script>
		<script src="vendor/isotope/jquery.isotope.min.js"></script>
		<script src="vendor/owl.carousel/owl.carousel.min.js"></script>
		<script src="vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
		<script src="vendor/vide/jquery.vide.min.js"></script>
		<script src="vendor/vivus/vivus.min.js"></script>
-->

	</body>
</html>
