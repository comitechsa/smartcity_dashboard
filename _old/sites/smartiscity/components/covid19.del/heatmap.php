<?php
	defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
		include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	//require_once(dirname(__FILE__) . "/common.php");
	//if(($auth->UserRow['admin_type']=='LOCAL')) {
	//	Redirect("index.php");
	//}
	if($auth->UserType != "Administrator") Redirect("index.php");

	global $nav;
	$nav = "Heatmap";
	$config["navigation"] = $nav;
	$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
	$BaseUrl = "index.php?com=heatmap";
	$command=array();
	$command=explode("&",$_POST["Command"]);
	if(isset($_GET["item"])) {
		//$filter=" WHERE 1=1 AND user_auth!='Administrator '";
		//$filter.=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
		$query="SELECT * FROM packageitems WHERE package_id=".$_GET['item'].$filter." LIMIT 1";

		$dr_e = $db->RowSelectorQuery($query);
		if(intval($_GET['item'])==0 || (intval($_GET["item"])> 0 && intval($dr_e['package_id'])==0)){
			$messages->addMessage("NOT FOUND!!!");
			//Redirect("index.php?com=packages");		
		}
	}
?>    
	


            

	<script type="text/javascript">
	/*
			$(function () {
			$('#datetimepicker6').datetimepicker({
					icons: {
						time: "fa fa-clock-o",
						date: "fa fa-calendar",
						up: "fa fa-arrow-up",
						down: "fa fa-arrow-down"
					}
				});
			$('#datetimepicker7').datetimepicker({
				useCurrent: false,
					icons: {
						time: "fa fa-clock-o",
						date: "fa fa-calendar",
						up: "fa fa-arrow-up",
						down: "fa fa-arrow-down"
					}							
			});
			$("#datetimepicker6").on("dp.change", function (e) {
				$('#datetimepicker7').data("DateTimePicker").minDate(e.date);
			});
			$("#datetimepicker7").on("dp.change", function (e) {
				$('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
			});
		});
	*/

	

	</script>
    

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
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
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
						<!-- 
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
						-->
						<div class='col-md-3'>
							<div class="form-group"><label for="datefrom">Από ημ/νια</label>
							   <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
									<input type="text" name="fromDate" id="fromDate" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
									<div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fa fa-calendar"></i></div>
									</div>
								</div>
							</div>
						</div>
						<div class='col-md-3'>
							<div class="form-group">
								<label for="dateto">Εως ημ/νια</label>
							   <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
									<input type="text" name="toDate" id="toDate" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
									<div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
										<div class="input-group-text"><i class="fa fa-calendar"></i></div>
									</div>
								</div>
							</div>
						</div>
						<div class='col-md-2'>
							<div style="display:block;margin-bottom: 1.8rem;"></div>
							<div class="form-group">
								<button id="go" class="btn btn-primary">Go</button>
							</div>
						</div>

					<script type="text/javascript">
						$(function () {
							$('#datetimepicker1').datetimepicker({
								icons: {
									time: "fa fa-clock-o",
									date: "fa fa-calendar",
									up: "fa fa-arrow-up",
									down: "fa fa-arrow-down"
								}
							});
							$('#datetimepicker2').datetimepicker({
								useCurrent: false,
								icons: {
									time: "fa fa-clock-o",
									date: "fa fa-calendar",
									up: "fa fa-arrow-up",
									down: "fa fa-arrow-down"
								}
							});
							$("#datetimepicker1").on("change.datetimepicker", function (e) {
								$('#datetimepicker2').datetimepicker('minDate', e.date);
							});
							$("#datetimepicker2").on("change.datetimepicker", function (e) {
								$('#datetimepicker1').datetimepicker('maxDate', e.date);
							});
						});
					</script>
					</div>
					<!--
					<div class="row">
						<div class="container">
							<div class='col-md-4'>
								<div class="form-group">
									<div class='input-group date' id='datetimepicker1'>
										<input type='text' class="form-control" />
										<span class="input-group-addon">
											 <span class="fas fa-columns"></span>
										</span>
									</div>
								</div>
							</div>
							<div class='col-md-4'>
								<div class="form-group">
									<div class='input-group date' id='datetimepicker2'>
										<input type='text' class="form-control" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					-->

					<hr/>
					<? if(isset($_GET['item'])){?>
					<div class="heatmap leaflet-container leaflet-fade-anim" id="map-canvas" tabindex="0" style="position: relative;min-height:700px;">
						<div class="leaflet-map-pane" style="transform: translate3d(-82px, 321px, 0px);">
							<div class="leaflet-objects-pane">
								<div class="leaflet-shadow-pane"></div>
								<div class="leaflet-overlay-pane">
									<div class="leaflet-zoom-hide" style="width: 834px; height: 400px; position: relative; transform: translate(82px, -321px);">
										<canvas class="heatmap-canvas" width="834" height="400" style="position: absolute; left: 0px; top: 0px;"></canvas>
									</div>
								</div>
							</div>
						</div>
					</div>
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
	$data="";
	
	while ($dr = $db->sql_fetchrow($result)){
		$data.="{lat: ".$dr['lat'].", lng:".$dr['lng'].", count: 1},";
		if(strlen($data)>2){
			$firstLat=$dr['lat'];
			$firstLng=$dr['lng'];
		}
	}
	$data = mb_substr($data, 0, -1);
	?>
	<? if(isset($_GET['item'])){?>
		<script>
			window.onload = function() {
			  var testData = {
				  max: 8,
				  data: [<?=$data?>]
				};

				var baseLayer = L.gridLayer.googleMutant({ type: 'roadmap' });


				var cfg = {
				  // radius should be small ONLY if scaleRadius is true (or small radius is intended)
				  "radius": 0.1,
				  "maxOpacity": .6,
				  // scales the radius based on map zoom
				  "scaleRadius": true,
				  // if set to false the heatmap uses the global maximum for colorization
				  // if activated: uses the data maximum within the current map boundaries
				  //   (there will always be a red spot with useLocalExtremas true)
				  "useLocalExtrema": true,
				  // which field name in your data represents the latitude - default "lat"
				  latField: 'lat',
				  // which field name in your data represents the longitude - default "lng"
				  lngField: 'lng',
				  // which field name in your data represents the data value - default "value"
				  valueField: 'count'
				};

				var heatmapLayer = new HeatmapOverlay(cfg);
				var map = new L.Map('map-canvas', {
				  center: new L.LatLng(<?=$firstLat?>, <?=$firstLng?>),
				  zoom: 8,
				  layers: [baseLayer, heatmapLayer]
				});
				heatmapLayer.setData(testData);
			};
			//var roads = L.gridLayer.googleMutant({ type: 'roadmap' }).addTo('baseLayer');
		</script>
	<? } ?>
		<script>
			$('#package').change(function(){
				var url = "index.php?com=heatmap&item=";
					if($("#package").val()!='Επιλογή')
						url+=encodeURIComponent($("#package").val());
						//url = url.replace(/\&$/,'');
						//alert(url);
						window.location.href=url;
				});
		</script>
		<script>
			$('#period').change(function(){
				var url = "index.php?com=heatmap&item=" + $("#package").val() +"&period=";
					if($("#period").val()!='Επιλογή')
						url+=encodeURIComponent($("#period").val());
						//url = url.replace(/\&$/,'');
						//alert(url);
						window.location.href=url;
				});
		</script>
<script type="text/javascript">
/*$(function () {
	$('#datetimepicker1').datetimepicker();
	$('#datetimepicker2').datetimepicker({
		useCurrent: false //Important! See issue #1075
	});
	$("#datetimepicker1").on("dp.change", function (e) {
		$('#datetimepicker2').data("DateTimePicker").minDate(e.date);
	});
	$("#datetimepicker2").on("dp.change", function (e) {
		$('#datetimepicker1').data("DateTimePicker").maxDate(e.date);
	});
});
*/


</script>
