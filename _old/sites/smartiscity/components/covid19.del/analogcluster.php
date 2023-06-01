<?php
	defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
		include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	//require_once(dirname(__FILE__) . "/common.php");
	//if(($auth->UserRow['admin_type']=='LOCAL')) {
	//	Redirect("index.php");
	//}
	if($auth->UserType != "Administrator") Redirect("index.php");

	global $nav;
	$nav = "Analog cluster";
	$config["navigation"] = $nav;
	$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
	$BaseUrl = "index.php?com=analogcluster";
	$command=array();
	$command=explode("&",$_POST["Command"]);
	if(isset($_GET["item"])) {
		//$filter=" WHERE 1=1 AND user_auth!='Administrator '";
		//$filter.=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
		$query="SELECT * FROM packageitems WHERE package_id=".$_GET['item'].$filter." LIMIT 1";

		$dr_e = $db->RowSelectorQuery($query);
		if(intval($_GET['item'])==0 || (intval($_GET["item"])> 0 && intval($dr_e['packageitem_id'])==0)){
			$messages->addMessage("NOT FOUND!!!");
			Redirect("index.php?com=packages");		
		}
	}
?>    


	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
					</div>
					<h2 class="card-title"><?=$nav?></h2>
				</header>
				<div class="card-body" style="width:100%; height:600px;">
					<div class="row">
						<div class="col-lg-6 mb-3">
							<label for="package">Επιλογή πακέτου</label>
							<select id='package' name="package" class="form-control mb-3">
								<option >Επιλογή</option>
								<?
									$filter="";
									$query = "SELECT * FROM packages WHERE 1=1 ".$filter." ORDER BY date_insert DESC ";
									$result = $db->sql_query($query);
									while ($dr = $db->sql_fetchrow($result)){
										echo '<option value="'.$dr['package_id'].'" '.($dr['package_id']==$_GET['item']?" selected":"").'>'.$dr['package_name'].' / ('.$dr['date_insert'].')'.'</option>';
									}
								?>
							</select>
						</div>
						<div class="col-lg-6 mb-3">
							<label for="package">Επιλογή περιόδου</label>
							<select id='period' name="period" class="form-control mb-3">
								<option >Επιλογή</option>
								<option value="1">Σήμερα</option>
								<option value="2">Χθες</option>
								<option value="3">Τελευταία εβδομάδα</option>
								<option value="4">Τελευταίος μήνας</option>
								<option value="5">Ολα τα δεδομένα</option>
							</select>
						</div>
					</div>
					<hr/>
					<? if(isset($_GET['item'])){?>
						<div id="map" style="height:500px;"></div>
					<? } ?>
					<div class="row" style="margin-top:20px;">
						<a href="index.php?com=packages"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
					</div>
				</div>
			</section>
		</div>
	</div>
	<?
	$query = "SELECT * FROM packageitems WHERE package_id='".$_GET['item']."' ORDER BY movedate DESC ";
	$result = $db->sql_query($query);
	//$data="";
	$counter=0;
	while ($dr = $db->sql_fetchrow($result)){
		//$data.="{lat: ".$dr['lat'].", lng:".$dr['lng'].", count: 1},";
		$data[$counter]['lat']=$dr['lat'];
		$data[$counter]['lng']=$dr['lng'];
		$data[$counter]['movedate']=$dr['movedate'];
		$counter++;
	}
	?>

		<script type="text/javascript">
			ClusterIcon.prototype.createCss = function(pos) {
				//var size = Math.min(this.cluster_.getMarkers().length ,
				var size = Math.min(this.cluster_.getMarkers().length * 10,
				//var size = Math.min(((this.cluster_.getMarkers().length>1) ? this.cluster_.getMarkers().length * 10 : 0),
					100 //possible max-size of a cluster-icon
				),

				style = ['border-radius : 80%',
					'line-height   : ' + size + 'px',
					'cursor        : pointer',
					'position      : absolute',
					'top           : ' + pos.y + 'px !important',
					'left          : ' + pos.x + 'px !important',
					'width         : ' + size + 'px !important',
					'height        : ' + size + 'px !important'
				];
				return style.join(";") + ';';
			};

			//function randPos() {
			//	return new google.maps.LatLng(((Math.random() * 16000 - 8000) / 100), ((Math.random() * 34000 - 17000) / 100));
			//}

			function initialize() {
				var center = new google.maps.LatLng(<?=$data[0]["lat"]?>, <?=$data[0]["lng"]?>);

				var map = new google.maps.Map(document.getElementById('map'), {
					zoom: 8,
					center: center,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				});
				var infoWin = new google.maps.InfoWindow();

				var markers = [];
				//var InfoWindows = new google.maps.InfoWindow({});
					<?
						for($i=0;$i<sizeof($data);$i++){
					?>
						 var marker = new google.maps.Marker({
							position: {lat: <?=$data[$i]["lat"]?>, lng: <?=$data[$i]["lng"]?>},
							icon: '/img/icons8-marker-16.png',
							map: map,
							title: '<?=$data[$i]['movedate']?>'
						  });
						marker.addListener('click', function() {
							infoWin.open(map, this);
							infoWin.setContent('Test infowindow text message');
						});
						markers.push(marker); 
					<? } ?>
					
					var markerCluster = new MarkerClusterer(map,markers, {enableRetinaIcons: true,clusterClass: 'cluster cluster_blue'});
			}
			google.maps.event.addDomListener(window, 'load', initialize);
		</script>
		<script>
			$('#package').change(function(){
				var url = "index.php?com=analogcluster&item=";
					if($("#package").val()!='Επιλογή')
						url+=encodeURIComponent($("#package").val());
						//url = url.replace(/\&$/,'');
						//alert(url);
						window.location.href=url;
				});
		</script>
		<script>
			$('#period').change(function(){
				var url = "index.php?com=analogcluster&item=" + $("#package").val() +"&period=";
					if($("#period").val()!='Επιλογή')
						url+=encodeURIComponent($("#period").val());
						//url = url.replace(/\&$/,'');
						//alert(url);
						window.location.href=url;
				});
		</script>