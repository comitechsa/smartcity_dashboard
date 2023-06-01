<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

$type = "-1";
$typeName = "";
$typeIcon = "";
$typeColor = "";

$all_variables = array();

if(isset($_GET["type"]) && intval($_GET["type"]) > 0)
{
	$dr = $db->RowSelectorQuery("SELECT * FROM sensortypes WHERE sensortype_id=".intval($_GET["type"]));
	if (isset($dr["sensortype_id"])) {
		$type = $dr["sensortype_id"];
		$typeName = $dr["sensortype_name"];
		$typeIcon = $dr["sensortype_icon"];
		$typeColor = $dr["sensortype_color"];
	}

	$query = "
		SELECT * FROM sensorvars
		WHERE EXISTS (
			SELECT 1 FROM mysensors 
			INNER JOIN sensortypes ON sensortypes.sensortype_id = mysensors.sensortype_id
			WHERE mysensors.region_id=" . $auth->UserRow["region_id"] . " 
			AND mysensors.sensortype_id = " . $type . " 
			AND mysensors.is_valid = 'True'
			AND CONCAT(',', mysensors.vars, ',') LIKE CONCAT('%,', sensorvars.sensorvar_id, ',%')
		)
	";
	
	$result = $db->sql_query($query);
	while ($dr = $db->sql_fetchrow($result))
	{
		$all_variables[] = array (
			"id" => $dr["sensorvar_id"],
			//"name" => $dr["sensorvar_name"],
			"name" => $dr["sensorvar_description"],
			"icon" => $dr["sensorvar_icon"],
			"unit" => $dr["sensorvar_unit"],
			"dec" => $dr["sensorvar_dec"],
		);
	}

	$db->sql_freeresult($result);
}

function getData($all_variables, $search_type, $search_vars = "", $search_status = "")
{
	global $db, $auth;

	$region_id = $auth->UserRow["region_id"];

	$query = "
		SELECT mysensors.*, sensortypes.sensortype_icon FROM mysensors 
		INNER JOIN sensortypes ON sensortypes.sensortype_id = mysensors.sensortype_id
		WHERE mysensors.region_id=" . $region_id . " 
		AND mysensors.sensortype_id = " . $search_type . " 
		AND mysensors.is_valid = 'True'
	";

	if($search_vars != "")
	{
		$subquery = "";
		for($sv = 0; $sv < count($search_vars); $sv++)
		{
			$subquery .= " OR CONCAT(',', mysensors.vars, ',') LIKE CONCAT('%,', " . $search_vars[$sv] . ", ',%')";
		}

		$query .= " AND ( " . substr($subquery, 4) . ")";
	}

	//echo $query;exit;

	/*
	1. Περιβαλλοντικοί
	2. Συνάθροισης κοινού
	3. Ποιότητας υδάτων
	4. WiFi Hotspots
	5. Μετεωρολογικά δεδομένα
	*/

	if($search_status != "")
	{
		if( $search_type == 4)
		{
			//wifi hotspot ?
		}
		else if( $search_type == 2)
		{
			$subquery_bluetooth = "";
			$subquery_wf = "";

			if($search_status == 3) //Βλάβη (3), Δεν έστειλε για > 12 ώρες, ή δεν έστειλε πότε
			{
				$subquery_bluetooth = "
					SELECT 1 FROM bluetoothData 
					WHERE mysensors.ref_id = bluetoothData.MeshliumID 
					AND date_insert < DATE_SUB(NOW(), INTERVAL 12 HOUR)
				";

				$subquery_wf = "
					SELECT 1 FROM wifiScan 
					WHERE mysensors.ref_id = wifiScan.MeshliumID 
					AND date_insert < DATE_SUB(NOW(), INTERVAL 12 HOUR)
				";
			}
			else if($search_status == 2) //Σε έλεγχο (2), Δεν έστειλε για > 2 και < 12 ώρες
			{
				$subquery_bluetooth = "
					SELECT 1 FROM bluetoothData 
					WHERE mysensors.ref_id = bluetoothData.MeshliumID 
					AND date_insert < DATE_SUB(NOW(), INTERVAL 2 HOUR)
					AND date_insert >= DATE_SUB(NOW(), INTERVAL 12 HOUR)
				";

				$subquery_wf = "
					SELECT 1 FROM wifiScan 
					WHERE mysensors.ref_id = wifiScan.MeshliumID 
					AND date_insert < DATE_SUB(NOW(), INTERVAL 2 HOUR)
					AND date_insert >= DATE_SUB(NOW(), INTERVAL 12 HOUR)
				";
			}
			else //Ενεργός (1), Τελευταίες 2 ώρες
			{
				$subquery_bluetooth = "
					SELECT 1 FROM bluetoothData 
					WHERE mysensors.ref_id = bluetoothData.MeshliumID 
					AND date_insert >= DATE_SUB(NOW(), INTERVAL 2 HOUR)
				";

				$subquery_wf = "
					SELECT 1 FROM wifiScan 
					WHERE mysensors.ref_id = wifiScan.MeshliumID 
					AND date_insert >= DATE_SUB(NOW(), INTERVAL 2 HOUR)
				";
			}


			if($search_vars != "")
			{
				$search_vars_ar = implode(",", $search_vars);
				if (!in_array("25", $search_vars_ar))
				{
					$subquery_wf = "";
				}

				if (!in_array("26", $search_vars_ar))
				{
					$subquery_bluetooth = "";
				}
			}

			$subquery = "";
			if($subquery_bluetooth != "")
			{
				$subquery .= " OR EXISTS (" . $subquery_bluetooth . " )";
			}
			
			if($subquery_wf != "")
			{
				$subquery .= " OR EXISTS (" . $subquery_wf . " )";
			}
			
			if($subquery != "")
			{
				$query .= " AND ( " . substr($subquery, 4) . " )";
			}

			//echo $query; exit;
		}
		else //1,3,5
		{
			$subquery = " 
				SELECT 1 FROM measurements WHERE 
				mysensors.mysensor_id = measurements.sensor_id 
			";

			if($search_vars != "")
			{
				$subquery .= " AND parameter_id IN (" .  implode(",", $search_vars) . ") ";
			}

			if($search_status == 3) //Βλάβη (3), Δεν έστειλε για > 12 ώρες, ή δεν έστειλε πότε
			{
				$subquery .= " 
					AND date_insert < DATE_SUB(NOW(), INTERVAL 12 HOUR)
				";

				$query .= " AND (EXISTS (" . $subquery . ") OR mysensors.mysensor_id NOT IN (SELECT sensor_id FROM measurements))";
			}
			else if($search_status == 2) //Σε έλεγχο (2), Δεν έστειλε για > 2 και < 12 ώρες
			{
				$subquery .= " 
					AND date_insert < DATE_SUB(NOW(), INTERVAL 2 HOUR)
					AND date_insert >= DATE_SUB(NOW(), INTERVAL 12 HOUR)
				";
				$query .= " AND EXISTS (" . $subquery . ")";
			}
			else //Ενεργός (1), Τελευταίες 2 ώρες
			{
				$subquery .= " AND date_insert >= DATE_SUB(NOW(), INTERVAL 2 HOUR)";
				$query .= " AND EXISTS (" . $subquery . ")";
			}
		}
	}

	$query .= " ORDER BY mysensors.mysensor_name ";

	//echo $query;

	$data = array();
	$result = $db->sql_query($query);
	while ($dr = $db->sql_fetchrow($result))
	{
		$sensor = array (
			"id" => $dr["mysensor_id"],
			"name" => $dr["mysensor_name"],
			"locationName" => $dr["location"] != null ? $dr["location"] : "",
			"subsystem" => array( $dr["sensortype_id"], $dr["sensortype_icon"]),
			"lat" => $dr["lat"],
			"lng" => $dr["lng"]
		);

		$sensor["variables"] = array();
		
		if($dr["vars"] != null && $dr["vars"] != "")
		{
			$sensor_variables = explode(",", $dr["vars"]);
			$sensor_variables_array = array();
			for($v = 0; $v < count($sensor_variables); $v++)
			{
				$key = array_search($sensor_variables[$v], array_column($all_variables, 'id'));
		 		if($key !== false)
				{
					$sensor_variables_array[] = $all_variables[$key]["icon"];
				}
			}
			$sensor["variables"] = $sensor_variables_array;
		}
		

		$data[] = $sensor;
	}
	$db->sql_freeresult($result);

	return $data;
}


if(isset($_GET["json"]) && $_GET["json"] != "")
{
	if($_GET["json"] == "data")
	{
		$vars = isset($_GET["vars"]) && $_GET["vars"] != "" ? array_map('intval', explode(',', $_GET["vars"] )) : "";
		$status = isset($_GET["status"]) && $_GET["status"] != "" ? intval($_GET["status"]) : "";
		echo json_encode(getData($all_variables, $type, $vars, $status), JSON_PRETTY_PRINT);
	}
	else if($_GET["json"] == "details" && isset($_GET["id"]) && intval($_GET["id"]) > 0)
	{
		$query = " SELECT mysensors.mysensor_id, mysensors.vars, mysensors.ref_id, mysensors.description FROM mysensors 
			WHERE mysensors.region_id=" . $auth->UserRow["region_id"] . " 
			AND mysensors.mysensor_id = " . intval($_GET["id"]) . " 
			AND mysensors.is_valid = 'True'
		";

		$dr_sensor = $db->RowSelectorQuery($query);

		if(isset($dr_sensor["mysensor_id"]))
		{
			echo '<table>';

			if($dr_sensor["vars"] != null && $dr_sensor["vars"] != "")
			{
				$sensor_variables = explode(",", $dr_sensor["vars"]);
				for($v = 0; $v < count($sensor_variables); $v++)
				{
					$key = array_search($sensor_variables[$v], array_column($all_variables, 'id'));
					
					if($key !== false)
					{
						$image = "";
						if( strpos($all_variables[$key]["icon"], ".svg"))
						{
							$image = '<img src="gallery/vars/Big/' .  $all_variables[$key]["icon"] . '" alt="" style="max-width:inherit;">';
						}
						else {
							$image = '<i class="im ' .  $all_variables[$key]["icon"] . '"></i>';
						}

						echo '<tr>
							<td width="20px"><span >' . $image . '</span></td>
							<td width="75%">' . $all_variables[$key]["name"] . '</td>
							<td width="25%">
						'; //class="like-button"
						
						if( $type == 4)
						{
							//wifi hotspot ?
						}
						else if( $type == 2)
						{
							if($dr_sensor["ref_id"] != null )
							{
								if( $all_variables[$key]["id"] == 25)
								{
									/*
									$dr_wifi = $db->RowSelectorQuery("SELECT countMac FROM wf_lasthour 
										WHERE MeshliumID = '" . $dr_sensor["ref_id"] . "'
										ORDER BY countDate DESC, countHour DESC

									");
									*/
									$dr_wifi = $db->RowSelectorQuery("SELECT countMac FROM wf_totals 
										WHERE MeshliumID = '" . trim($dr_sensor["ref_id"]) . "'
										ORDER BY countDate DESC, countHour DESC LIMIT 1
									");
									
									if(isset($dr_wifi["countMac"]))
									{
										echo $dr_wifi["countMac"];
									}
								}
								else if( $all_variables[$key]["id"] == 26)
								{
									/*
									$dr_bluetooth = $db->RowSelectorQuery("SELECT countMac FROM bt_lasthour 
										WHERE MeshliumID = '" . $dr_sensor["ref_id"] . "'
										ORDER BY countDate DESC, countHour DESC
									");
									*/
									$dr_bluetooth = $db->RowSelectorQuery("SELECT countMac FROM bt_totals 
										WHERE MeshliumID = '" . trim($dr_sensor["ref_id"]) . "'
										ORDER BY countDate DESC, countHour DESC LIMIT 1
									");
									
									if(isset($dr_bluetooth["countMac"]))
									{
										echo $dr_bluetooth["countMac"];
									}
								}
							}
						}
						else //1,3,5
						{
							$dr_measurement = $db->RowSelectorQuery("
								SELECT * FROM measurements 
								WHERE sensor_id = " . $dr_sensor["ref_id"] . "
								AND parameter_id = " . $all_variables[$key]["id"] . "
								ORDER BY `date_insert` DESC LIMIT 1
							");
							if(isset($dr_measurement["measurement"]))
							{
								//echo (($all_variables[$key]["id"]==1 || $all_variables[$key]["id"]==2 || $all_variables[$key]["id"]==3) && $dr_measurement["measurement"]<-100?'n/a':number_format($dr_measurement["measurement"],intval($all_variables[$key]["dec"])).''.$all_variables[$key]["unit"]);
								////number_format($dr_measurement["measurement"],intval($all_variables[$key]["dec"])).''.$all_variables[$key]["unit"];
								if(($all_variables[$key]["id"]==1 || $all_variables[$key]["id"]==2 || $all_variables[$key]["id"]==3) && $dr_measurement["measurement"]<-100){
									echo 'n/a';
								} else if(($all_variables[$key]["id"]==16) && $dr_measurement["measurement"]==0){
									echo 'Κανονική';
								} else if(($all_variables[$key]["id"]==16) && $dr_measurement["measurement"]==1){
									echo 'Υπερχείλιση';
								} else if(($all_variables[$key]["id"]==13) && $dr_measurement["measurement"]==-1){
									echo 'n/a';
								} else if(($all_variables[$key]["id"]==29)){
									/*
									if($dr_measurement["measurement"]==0) echo 'N (Β)';
									if($dr_measurement["measurement"]==90) echo 'E (Α)';
									if($dr_measurement["measurement"]==180) echo 'S (Ν)';
									if($dr_measurement["measurement"]==270) echo 'W (Δ)';
									if($dr_measurement["measurement"]==45) echo 'NE (ΒΑ)';
									if($dr_measurement["measurement"]==135) echo 'SE (ΝΑ)';
									if($dr_measurement["measurement"]==225) echo 'SW (ΝΔ)';
									if($dr_measurement["measurement"]==315) echo 'NW (ΒΔ)';
									if($dr_measurement["measurement"]>0 && $dr_measurement["measurement"]<45) echo 'NNE (ΒΒΑ)';
									if($dr_measurement["measurement"]>45 && $dr_measurement["measurement"]<90) echo 'NNE (ΒΒΑ)';
									if($dr_measurement["measurement"]>90 && $dr_measurement["measurement"]<135) echo 'ESE (ΑΝΑ)';
									if($dr_measurement["measurement"]>135 && $dr_measurement["measurement"]<180) echo 'SSE (ΝΝΑ)';
									if($dr_measurement["measurement"]>180 && $dr_measurement["measurement"]<225) echo 'SSW (ΝΝΔ)';
									if($dr_measurement["measurement"]>225 && $dr_measurement["measurement"]<270) echo 'WSW (ΔΝΔ)';
									if($dr_measurement["measurement"]>270 && $dr_measurement["measurement"]<315) echo 'WNW (ΔΒΔ)';
									*/
									
									if($dr_measurement["measurement"]==0) $direction =  '(Β)';
									if($dr_measurement["measurement"]==90) $direction =  '(Α)';
									if($dr_measurement["measurement"]==180) $direction =  '(Ν)';
									if($dr_measurement["measurement"]==270) $direction =  '(Δ)';
									if($dr_measurement["measurement"]>0 && $dr_measurement["measurement"]<90) $direction =  '(ΒΑ)';
									if($dr_measurement["measurement"]>90 && $dr_measurement["measurement"]<180) $direction =  '(ΝΑ)';
									if($dr_measurement["measurement"]>180 && $dr_measurement["measurement"]<270) $direction =  '(ΝΔ)';
									if($dr_measurement["measurement"]>270 && $dr_measurement["measurement"]<360) $direction =  '(ΒΔ)';
									echo $direction;
									
									
								} else {
									echo number_format($dr_measurement["measurement"],intval($all_variables[$key]["dec"])).''.$all_variables[$key]["unit"];
								}
							}
						}
						
						echo '</td></tr>';
					}
				}
			}
								
			echo '</table>';

			echo '<br>' . $dr_sensor["description"];
		}
	}
	exit;
}


?>

<!-- Content
================================================== -->
<div class="fs-container">

	<div class="fs-inner-container content">
		<div class="fs-content">
			<!-- Search -->
			<section class="search">
				<div class="row">
					<div class="col-md-12">
						<!-- Row With Forms -->
						<div class="row with-forms">

							<!-- Main Search Input -->
							<div class="col-fs-12">
								<div class="input-with-icon">
									<i class="sl sl-icon-magnifier"></i>
									<input type="text" placeholder="Αναζήτηση" value="" id="searchTxt"/>
								</div>
							</div>

							<!-- Filters -->
							<div class="col-fs-12">

								<!-- Panel Dropdown / End -->
								<div class="panel-dropdown wide">
									<a href="#">Υποσύστημα</a>
									<div class="panel-dropdown-content subsystems">
										<div class="row">
											<div class="col-md-6">
												<label data-id="3">Δεδομένα Ροής Υδάτων</label>
												<label data-id="6">Δεδομένα Ποιότητας Υδάτων</label>
												<label data-id="2">Δεδομένα Συνάθροισης Κοινού</label>
												<label data-id="5">Μετεωρολογικά Δεδομένα</label>
											</div>	
											<div class="col-md-6">
												<label data-id="1">Περιβαλλοντικά Δεδομένα</label>
												<label data-id="4">WiFi Hotspots</label>
											</div>
										</div>
									</div>
								</div>
								<!-- Panel Dropdown / End -->

								<!-- Panel Dropdown -->
								<div class="panel-dropdown wide">
									<a href="#">Μεταβλητή</a>
									<div class="panel-dropdown-content variables checkboxes">

										<!-- Checkboxes -->
										<div class="row">
											<div class="col-md-6">
											<?
											for($v = 0; $v < count($all_variables); $v++)
											{
												if($v%2==0)
												{
													?>
														<input id="check-<? echo $all_variables[$v]["id"]?>" type="checkbox" name="check-variables" value="<? echo $all_variables[$v]["id"]?>" checked>
														<label for="check-<? echo $all_variables[$v]["id"]?>"><? echo $all_variables[$v]["name"]?></label>
													<?
												}
											}
											?>
											
											</div>	

											<div class="col-md-6">
												<?
												for($v = 0; $v < count($all_variables); $v++)
												{
													if($v%2!=0)
													{
														?>
															<input id="check-<? echo $all_variables[$v]["id"]?>" type="checkbox" name="check-variables" value="<? echo $all_variables[$v]["id"]?>" checked>
															<label for="check-<? echo $all_variables[$v]["id"]?>"><? echo $all_variables[$v]["name"]?></label>
														<?
													}
												}
												?>
											</div>
										</div>
											
										<!-- Buttons -->
										<div class="panel-buttons">
											<!-- <button type="button" id="uncheck" onclick="uncheckElements('check-variables');" class="">Uncheck all</button>-->
											<!-- 
											<button type="button" data-dismiss="modal" id="uncheckID" class="">Uncheck all</button>
											<button type="button" data-dismiss="modal" id="checkID" class="">Check all</button>
											-->
											<div class="row with-forms">
												<div class="col-md-4">
													<input style="display:inline;" type="radio" id="uncheckID" name="checkuncheck">
													<span>Uncheck all</span>
												</div>
												<div class="col-md-4">
													<input  style="display:inline;" type="radio" id="checkID" name="checkuncheck" value="">
													<span for="checkID">Check all</span>
												</div>
											</div>


											<button class="panel-apply">Apply</button>
										</div>

									</div>
								</div>

								<!-- Panel Dropdown / End -->

								<!-- Panel Dropdown -->
								<!-- 
								<div class="panel-dropdown">
									<a href="#">Κατάσταση</a>
									<div class="panel-dropdown-content status checkboxes">
										<div class="row">
											<div class="col-md-12">
												<input id="check-status-1" type="checkbox" name="check-status" value="1">
												<label for="check-status-1">Ενεργός</label>
												<input id="check-status-2" type="checkbox" name="check-status" value="2">
												<label for="check-status-2">Σε έλεγχο</label>
												<input id="check-status-3" type="checkbox" name="check-status" value="3">
												<label for="check-status-3">Βλάβη</label>
											</div>	
										</div>
										<div class="panel-buttons">
											<button class="panel-apply">Apply</button>
										</div>
									</div>
								</div>
								-->
									
							</div>
							<!-- Filters / End -->
	
						</div>
						<!-- Row With Forms / End -->
					</div>
				</div>
			</section>
			<!-- Search / End -->

			<section class="listings-container pricing-list-container">
				<h4 style="background-color:<?=$typeColor?>"><? echo $typeName;?></h4>
				<ul class="list-container-ul"></ul>
			</section>
		</div>
	</div>
	<div class="fs-inner-container map-fixed">
		<!-- Map -->
		<div id="map-container">
		    <div id="map" data-map-scroll="true">
		        <!-- map goes here -->
		    </div>
		</div>

	</div>
</div>

<script>
    var map, markerCluster;
	var locations = <? echo json_encode(getData($all_variables, $type));?>;
	var markers = [];
	var allMarkersCluster = [];
	var infowindow;
	var searchText = "";

	function isValidLocationBySearch(location)
	{
		var isValid = true;

		if(searchText != "")
		{
			isValid = location["name"].toLowerCase().includes(searchText.toLowerCase());
		}

		return isValid;
	}

	function hideAside()
	{
		$('.fs-inner-container.map-fixed').removeClass("aside");
		$(".aside-container").slideUp( "hide", function() {});
	}

	function showAside(id)
	{
		var location = $.grep(locations, function(e){ return e.id == id; })[0];
		
		$.get( "index.php?com=search&json=details&type=<?=$type?>&id=" + location["id"], function( details ) {

            if(location["subsystem"])
			{
				$(".aside-container .location-list-system").html('<i class="im ' + location["subsystem"][1] + '"></i>');
			}
			else
			{
				$(".aside-container .location-list-system").html('');
			}
		
			$(".location-title").html(location["name"]);
			$(".location-description").html(details);

			$('.fs-inner-container.map-fixed').addClass("aside");
			$(".aside-container").slideDown( "slow", function() {});
        });	
	}

	function addMarkers()
	{
		markers = [];
		allMarkersCluster = [];

		var bounds = new google.maps.LatLngBounds();

		for (i = 0; i < locations.length; i++) 
		{  
			if(!isValidLocationBySearch(locations[i]))
			{
				continue;
			}

			var marker = new google.maps.Marker({
				position: new google.maps.LatLng(locations[i]["lat"], locations[i]["lng"]),
				//map: map,
				icon: '/gallery/mapicons/<?=$_GET['type']?>.svg'
			});
			marker['locationId'] = locations[i]["id"];
			marker['name'] = locations[i]["name"];

			//extend the bounds to include each marker's position
			bounds.extend(marker.position);
			//map.fitBounds(bounds);

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					infowindow.setContent(marker["name"]);
					infowindow.open(map, marker);
					showAside(marker.locationId);
				}
			})(marker, i));

			markers.push({id: locations[i]["id"], marker: marker});
			allMarkersCluster.push(marker);
		}

		var options = {
          imagePath: 'images/',
          styles : [
		    {
			  textColor: 'white',
			  url: '',
			  height: 36,
			  width: 36
			}
		  ],
          minClusterSize : 2,
		  maxZoom: 14
		};
	
		markerCluster = new MarkerClusterer(map, allMarkersCluster, options);
		//(optional) restore the zoom level after the map is done scaling
		var listener = google.maps.event.addListener(map, "idle", function () {
			map.setZoom(14);
			google.maps.event.removeListener(listener);
		});

		map.fitBounds(bounds);
	}

	function GetVarIcon(path)
	{
		if(path.indexOf(".svg") != -1)
		{
			return `<img src="gallery/vars/Small/` + path + `" alt="" width="30px">`;
		}
		else {
			return `<i class="im ` + path + `"></i>`;
		}
	}

	function renderLocations()
	{
		$(".list-container-ul").empty();

		for (i = 0; i < locations.length; i++)
		{
			if(!isValidLocationBySearch(locations[i]))
			{
				continue;
			}

			var testStr = `<li data-marker-id="` + locations[i]["id"] + `">`;

			if(locations[i]["subsystem"])
			{
				testStr += `<div style="font-size:20px;" class="location-list-system"><i class="im ` + locations[i]["subsystem"][1] + `"></i></div>`;
			}

			testStr += `<a href="#" class="location-list-row" onclick="showAside(` + locations[i]["id"] + `);">
						<h5>` + locations[i]["name"] + `</h5>
						<p>` + locations[i]["locationName"] + `</p>
						<div>
					`;

			for (j = 0; j < locations[i]["variables"].length; j++) {
				testStr += `<button class="" style="border:none;background:none;height:inherit;width:inherit; /*background-color:#f4f4f4;width:32px; height:32px */ ;padding:0px;">` + GetVarIcon(locations[i]["variables"][j]) + `</button>`;
			}

			testStr += `</div></a>`;
				
			testStr += `</li>`;

			$(".list-container-ul").append(testStr);
		}

		$(".list-container-ul li").click(function(e) {
			e.preventDefault();
			gotoMarker($(this).data("markerId"))
		});
	}

	function gotoMarker(id) {
		var marker = $.grep(markers, function(e){ return e.id == id; })[0]["marker"];
		map.setZoom(15);
		map.setCenter(marker.getPosition());
		hideAside();
		infowindow.setContent(marker["name"]);  
        infowindow.open(map, marker);
	}

	function initializeData()
	{
		hideAside();
		renderLocations();
		infowindow = new google.maps.InfoWindow({maxWidth: 300});
		map = new google.maps.Map(document.getElementById("map"), {zoom: 14, styles: CustomMapStyles});
		addMarkers();
	}

	function getAjaxFilter()
	{
		var url = "index.php?com=search&json=data&type=<?=$type?>";
		if($("input[name='check-status']:checked").length > 0)
		{
			url += "&status=" + $("input[name='check-status']:checked").val();
		}

		if($("input[name='check-variables']:checked").length != 
			$("input[name='check-variables']").length 
		)
		{
			url += "&vars=" + $.map($("input[name='check-variables']:checked"), function(n, i){
				return n.value;
			}).join(',');
		}

		return url;
	}

	function initializeDocument()
	{
		$(".subsystems label").click(function(e) {
			e.preventDefault();
			window.location.href = "index.php?com=search&type=" + $(this).data("id");
		});

		$(".variables .panel-apply, .status .panel-apply").click(function(e) {
			e.preventDefault();
			$.get( getAjaxFilter(), function( data ) {
                locations = JSON.parse(data);
				initializeData();
				$('.panel-dropdown').removeClass("active");
				$('.fs-inner-container.content').removeClass("faded-out");
            });
		});

		$(".location-header .fm-close a").click(function(e) {
			e.preventDefault();
			hideAside();			
		});

		$("input[name='check-status']").bind('click',function() {
			 $("input[name='check-status']").not(this).prop("checked", false);
		});

		$( "#searchTxt" ).keyup(function() {
			searchText = $(this).val();
			initializeData();
		});
		
		initializeData();
	}
	
</script>

<script async defer 
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCK7sql4zSHreVAdLPH7t8JoNE1gy5Tuyk&callback=initializeDocument" type="text/javascript">
</script>
<link rel="stylesheet" type="text/css" href="/css/jquery-gmaps-latlon-picker.css"/>
<script src="/js/jquery-gmaps-latlon-picker.js"></script>
<script src="/scripts/markerclusterer.js"></script>

<div class="aside-container">
	<div class="location-detail">
		<div class="location-header">
			<div class="location-list-system" style="font-size:24px;width:50px;height:50px;padding:10px;background-color:<?=$typeColor?>;"></div>
			<span class="location-title"></span>
			<div class="fm-close"><a href="#"><i class="fa fa-close"></i></a></div>
		</div>
		<hr>
		<div class="location-description">
			
		</div>
	</div>
</div>

<style>

.aside-container
{
	z-index: 100;
	top: 0;
	right: 0;
	bottom: 0;
	background-color: #686868;
	color:#ffffff;
	position: fixed;
	padding-top: 80px;
	display:none;
}

.location-detail 
{
	width: 360px;
	height: 100%;
}

.location-header
{
	display: flex;
	width: 100%;
	position: relative;
	min-height: 30px;
	padding: 10px 20px;
	-ms-flex-negative: 0;
	flex-shrink: 0;
}

.location-header .fm-close
{
	position: absolute;right: 20px;color: #fff;
}

.location-header .fm-close a
{
	color: #fff;
}

.location-title
{
	font-weight: 400;
	font-size: 20px;
	line-height: 20px;
	margin-right: 28px;
	margin-left: 8px;
	overflow: hidden;
}

.location-description
{
	padding: 20px;
}

.location-description table tr td
{
	padding:5px;
	font-size: 14px;
}

.location-description table tr td:nth-child(2)
,.location-description table tr td:nth-child(3)
{
	border-bottom: 1px solid #797979;
}

.location-description table tr td:nth-child(2)
{
	border-right: 1px solid #797979;
}

.fs-inner-container.content
{
	width: 25%;
}

.fs-inner-container
{
	width: 75%;
}

.fs-inner-container.aside
{
	width: calc(75% - 360px);
}

.listings-container h4 {
	color:#161A20;
	border: none;
}

.pricing-list-container ul li
{
	padding: 10px 30px;
}

.pricing-list-container ul li:hover
{
	background-color:#eae6e6;
}

.like-button
{
	margin-bottom: 0px;
    padding: 2px;
	line-height: 18px;
}

a.location-list-row
{
	margin-left: 30px;
    display: block;
}

div.location-list-system
{
	float: left;
}

.aside-container .location-list-system
{
	width:20px;
}

.subsystems label,
.variables label,
.status label
{
	cursor:pointer;
}

@media (max-width: 991px)
{
	.fs-inner-container.content, .fs-inner-container.aside {
		width: 100%;
	}

	.aside-container
	{
		z-index: 1220;
	}
}


.cluster-visible
{
	<?php
	if($_GET['type'] == 1)
	{
		echo "background-color: #33D553 !important;";
	}
	else if($_GET['type'] == 2)
	{
		echo "background-color: #FB5606 !important;";
	}
	else if($_GET['type'] == 3)
	{
		echo "background-color: #357BEB !important;";
	}
	else if($_GET['type'] == 4)
	{
		echo "background-color: #FF006E !important;";
	}
	else if($_GET['type'] == 5)
	{
		echo "background-color: #FFBE0A !important;";
	}

	?>
	/*border: 7px solid #000;*/

}
	.cluster-visible:before  {border:7px solid #000;}

</style>