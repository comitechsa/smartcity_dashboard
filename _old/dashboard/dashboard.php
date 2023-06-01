<?php
	include('../class.db.php');
	$host = 'localhost';
	$database = 'scities';
	$dbuser = 'scities';
	$dbpass = 'qwe#123!@#';
	$db = new sql_db($host, $dbuser, $dbpass, $database);
	//mysensor_name
			
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
		<meta charset="utf-8" />
		<title>Smartiscity - Δράμα</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
		<meta content="" name="description" />
		<meta content="" name="author" />
		<link href="assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
		<link href="assets/plugins/jquery-polymaps/style.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="assets/plugins/jquery-metrojs/MetroJs.css" rel="stylesheet" type="text/css" />
		<!-- <link rel="stylesheet" type="text/css" href="assets/plugins/shape-hover/css/demo.css" />-->
		<link rel="stylesheet" type="text/css" href="assets/plugins/shape-hover/css/component.css" />
		<link rel="stylesheet" type="text/css" href="assets/plugins/owl-carousel/owl.carousel.css" />
		<link rel="stylesheet" type="text/css" href="assets/plugins/owl-carousel/owl.theme.css" />
		<link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="assets/plugins/jquery-slider/css/jquery.sidr.light.css" rel="stylesheet" type="text/css" media="screen" />
		<!-- <link rel="stylesheet" href="assets/plugins/jquery-ricksaw-chart/css/rickshaw.css" type="text/css" media="screen">-->
		<!-- <link rel="stylesheet" href="assets/plugins/Mapplic/mapplic/mapplic.css" type="text/css" media="screen">-->
		<!-- BEGIN PLUGIN CSS -->
		<link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="assets/plugins/bootstrapv3/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/plugins/bootstrapv3/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link href="assets/plugins/animate.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css" />
		<!-- END PLUGIN CSS -->
		<!-- BEGIN CORE CSS FRAMEWORK -->
		<link href="webarch/css/webarch.css" rel="stylesheet" type="text/css" />
		<!-- END CORE CSS FRAMEWORK -->
		<style>
			.row-eq-height {
				display: -webkit-box;
				display: -webkit-flex;
				display: -ms-flexbox;
				display: flex;
			}
		</style>
	</head>
	<body class="condense-menu" style="background-image: url(/gallery/cities/drama1.jpg);background-repeat: no-repeat;background-size: cover;min-height:94vh;">
		<div class="page-container row-fluid">
			<a href="#" class="scrollup">Scroll</a>
			<!-- 
			<div class="footer-widget">
				<div class="progress transparent progress-small no-radius no-margin">
					<div class="progress-bar progress-bar-success animate-progress-bar" data-percentage="79%" style="width: 79%;"></div>
				</div>
				<div class="pull-right">
					<div class="details-status"> <span class="animate-number" data-value="86" data-animation-duration="560">86</span>% </div>
					<a href="lockscreen.html"><i class="material-icons">power_settings_new</i></a>
				</div>
			</div>
		-->
		<!-- END SIDEBAR -->
      
		<!-- BEGIN PAGE CONTAINER-->
		<div class="page-content condensed" style="margin-left:0px;background-color:rgba(0, 0, 255, 0.3);">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<div id="portlet-config" class="modal hide">
				<div class="modal-header">
					<button data-dismiss="modal" class="close" type="button"></button>
					<h3>Widget Settings</h3>
				</div>
				<div class="modal-body"> Widget settings form goes here </div>
			</div>
			<div class="clearfix"></div>
			<div class="content sm-gutter">
				<div class="row 2col">
					<?
						$queryPm = "SELECT max(date_insert),measurement,parameter_id FROM measurements 
						WHERE parameter_id IN(9,10,11) AND measurement>0 
						GROUP BY parameter_id";
						$resultPm = $db->sql_query($queryPm);
						while ($drPm = $db->sql_fetchrow($resultPm)){
							$pmArray[$drPm['parameter_id']]['measurement']=$drPm['measurement'];
							//$pmArray[$drPm['parameter_id']]['unit']=$drPm['sensorvar_unit'];
							//$pmArray[$drPm['parameter_id']]['dec']=$drPm['sensorvar_dec'];
						}
					?>
					<?
						$queryPmAVG = "SELECT avg(measurement) AS measurement,parameter_id FROM measurements 
						WHERE parameter_id IN(9,10,11) AND measurement>0 
						AND date_insert >= DATE_ADD(NOW(),INTERVAL -1 HOUR)
						GROUP BY parameter_id";

						$resultPmAVG = $db->sql_query($queryPmAVG);
						while ($drPmAVG = $db->sql_fetchrow($resultPmAVG)){
							$pmArrayAVG[$drPmAVG['parameter_id']]['measurement']=$drPmAVG['measurement'];
							//$pmArray[$drPm['parameter_id']]['unit']=$drPm['sensorvar_unit'];
							//$pmArray[$drPm['parameter_id']]['dec']=$drPm['sensorvar_dec'];
						}
						//AND date_insert >= DATE_ADD(CURDATE(), INTERVAL -3 DAY) 
					?>
			
					<?
						//last temperature
						$lastTemp=$db->RowSelectorQuery("SELECT * FROM measurements WHERE parameter_id=27 ORDER BY date_insert DESC LIMIT 1");
						$lastDayTemp = $db->RowSelectorQuery("SELECT avg(measurement) AS measurement FROM measurements WHERE parameter_id=27 AND hour(date_insert) = hour(DATE_ADD(now(),INTERVAL -24 HOUR)) AND DATE(date_insert) = DATE(DATE_ADD(now(),INTERVAL -1 DAY))");
						//last uv index
						$lastUv=$db->RowSelectorQuery("SELECT * FROM measurements WHERE parameter_id=30 ORDER BY date_insert DESC LIMIT 1");
					?>
					<?
						//Περιβαλλοντικά
						//Βρες τους αισθητήρες
						//SELECT * FROM mysensors WHERE sensortype_id=1 //1,10,11,12,13 //4,1,3,2,11
						//Για κάθε έναν από αυτούς βρες τις τελευταίες τιμές
						//SELECT * FROM measurements WHERE sensor_id=4 AND date_insert = (SELECT max(date_insert) FROM measurements WHERE sensor_id=4)
						//SELECT t1.sensor_id,t1.measurement,t1.date_insert,t2.mysensor_name,t2.location,t2.description,t2.lat,t2.lng,t2.ref_id,t3.sensorvar_id,t3.is_valid,t3.sensorvar_name,t3.sensorvar_description,t3.sensorvar_icon,t3.sensorvar_unit,t3.sensorvar_dec FROM measurements t1 INNER JOIN mysensors t2 ON t1.sensor_id=t2.ref_id INNER JOIN sensorvars t3 ON t1.parameter_id=t3.sensorvar_id WHERE t2.region_id=1 AND t1.sensor_id=4 AND t1.date_insert = (SELECT max(date_insert) FROM measurements WHERE sensor_id=4)
						
					?>
			
					<div class="row" style="margin-bottom:20px;opacity:0.90;">
						<div class="col-md-3 col-sm-3">
							<div class="tiles red weather-widget ">
								<div class="tiles-body">
									<div class="controller">
										<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
									</div>
									<!-- <div class="tiles-title"> Σωματίδια </div>-->
									<div class="heading">
										<div class="pull-left"> Σωματίδια </div>
										<!-- <div class="pull-right"> 55 </div>-->
										<div class="clearfix"></div>
									</div>
									<!-- 
									<div class="big-icon">
										<canvas id="partly-cloudy-day" width="120" height="120"></canvas>
									</div>
									-->
									<div class=" p-t-25 p-r-10 p-l-10 p-b-15">
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left" style="width: 50%;">
												<span class="bold text-white m-r-15 text-right">PM 1 </span>
												
												<!-- <canvas id="white_widget_06" width="20" height="20" class="inline m-l-10"></canvas>-->
											</div>
											<div style="width: 30%;" class="pull-left"><span>Καλό</span> </div>
											<div class="pull-right">
												<span class="semi-bold text-white"><?=number_format($pmArrayAVG[9]['measurement'],2)?></span>
												<span class="bold text-white"><!-- C&deg;(Καλό)--> </span>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left" style="width: 50%;">
												<span class="bold  text-white m-r-15">PM2.5</span>
												<!-- <canvas id="white_widget_07" width="20" height="20" class="inline m-l-10"></canvas>-->
												<!-- <span>Μέτριο</span> -->
											</div>
											<div style="width: 30%;" class="pull-left"><span>Μέτριο</span> </div>
											<div class="pull-right">
												<span class="semi-bold text-white"><?=number_format($pmArrayAVG[10]['measurement'],2)?></span>
												<span class="bold text-error"><!-- C&deg; (Μέτριο)--></span>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left" style="width: 50%;">
												<span class="bold  text-white m-r-15">PM10</span>
												<!-- <canvas id="white_widget_08" width="20" height="20" class="inline m-l-10"></canvas>-->
												<!-- <span>Μέτριο</span> -->
											</div>
											<div style="width: 30%;" class="pull-left"><span>Κακό</span> </div>
											<div class="pull-right">
												<span class="semi-bold text-white"><?=number_format($pmArrayAVG[11]['measurement'],2)?></span>
												<span class="bold text-error"><!-- C&deg; (Κακό)--></span>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>

									<div class="clearfix"></div>
								</div>
								<!-- 
								<div class="tile-footer">
									<div class="pull-left">
										<canvas id="wind" width="32" height="32"></canvas>
										<span class="text-white small-text-description">Windy</span> 
									</div>
									<div class="pull-right">
										<canvas id="rain" width="32" height="32"></canvas>
										<span class="text-white small-text-description">Humidity</span> 
									</div>
									<div class="clearfix"></div>
								</div>
								-->
							</div>
						</div>
						<div class="col-md-3 col-sm-3">
							<div class="tiles red weather-widget ">
								<div class="tiles-body">
									<div class="controller">
										<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
									</div>
									<!-- <div class="tiles-title"> TODAY’S WEATHER </div>-->
									<div class="heading">
										<div class="pull-left"> Θερμοκρασία </div>
										<!-- <div class="pull-right"> 55 </div>-->
										<div class="clearfix"></div>
									</div>
									<!-- 
									<div class="big-icon">
										<canvas id="partly-cloudy-day" width="120" height="120"></canvas>
									</div>
									<div class="clearfix"></div>							
									-->
									<div class=" p-t-25 p-r-10 p-l-10 p-b-15">
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left" style="width: 50%;">
												<span class="bold text-white m-r-15 text-right">Σήμερα </span>
												<!-- <span>Καλό</span> -->
												<!-- <canvas id="white_widget_06" width="20" height="20" class="inline m-l-10"></canvas>-->
											</div>
											<div style="width: 30%;" class="pull-left"><span>Καλό</span> </div>
											<div class="pull-right">
												<span class="semi-bold text-white"><?=number_format($lastTemp['measurement'],2)?>  C°</span>
												<span class="bold text-white"><!-- C&deg;(Καλό)--> </span>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left" style="width: 50%;">
												<span class="bold  text-white m-r-15">Χθες</span>
												<!-- <canvas id="white_widget_07" width="20" height="20" class="inline m-l-10"></canvas>-->
												<!-- <span>Μέτριο</span> -->
											</div>
											<div style="width: 30%;" class="pull-left"><span>Μέτριο</span> </div>
											<div class="pull-right">
												<span class="semi-bold text-white"><?=number_format($lastDayTemp['measurement'],2)?>  C°</span>
												<span class="bold text-error"><!-- C&deg; (Μέτριο)--></span>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left" style="width: 50%;">
												<span class="bold  text-white m-r-15">Μεταβολή</span>
											</div>
											<?
												$change =  ((($lastTemp['measurement'] / $lastDayTemp['measurement']) - 1)*100);
												$changeIcon = ($change>0?'<i class="icon-custom-up"></i>':'<i class="icon-custom-down"></i>');
											?>
											<div style="width: 30%;" class="pull-left"><span><?=$changeIcon?></span> </div>
											<div class="pull-right">

												<span class="semi-bold text-white"><?=number_format($change,2)?> %</span>
												<span class="bold text-error"></span>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>

								</div>
								<!-- 
								<div class="tile-footer">
									<div class="pull-left">
										<canvas id="wind" width="32" height="32"></canvas>
										<span class="text-white small-text-description">Windy</span> 
									</div>
									<div class="pull-right">
										<canvas id="rain" width="32" height="32"></canvas>
										<span class="text-white small-text-description">Humidity</span> 
									</div>
									<div class="clearfix"></div>
								</div>
								-->

							</div>
						</div>
						<div class="col-md-3 col-sm-3">
							<div class="tiles red weather-widget ">
								<div class="tiles-body">
									<div class="controller">
										<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
									</div>
									<!-- <div class="tiles-title"> TODAY’S WEATHER </div>-->
									<div class="heading">
										<div class="pull-left"> Συνάθροιση </div>
										<!-- <div class="pull-right"> 55 </div>-->
										<div class="clearfix"></div>
									</div>
									<!-- 
									<div class="big-icon">
										<canvas id="partly-cloudy-day" width="120" height="120"></canvas>
									</div>
									

									<div class="clearfix"></div>
									-->
									<div class=" p-t-25 p-r-10 p-l-10 p-b-15">
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left" style="width: 50%;">
											<?
												$drPeopleCount1=$db->RowSelectorQuery("SELECT * FROM wf_totals WHERE MeshliumID='20316110332521' ORDER BY id DESC LIMIT 1");
												$drPeopleCount2=$db->RowSelectorQuery("SELECT * FROM wf_totals WHERE MeshliumID='20316131300518' ORDER BY id DESC LIMIT 1");
												$drPeopleCount3=$db->RowSelectorQuery("SELECT * FROM wf_totals WHERE MeshliumID='20316110336520' ORDER BY id DESC LIMIT 1");
											?>
												<span class="bold text-white m-r-15 text-right">Νέο δημαρχείο </span>
												<!-- <span>Καλό</span> -->
												<!-- <canvas id="white_widget_06" width="20" height="20" class="inline m-l-10"></canvas>-->
											</div>
											<div style="width: 30%;" class="pull-left"><span></span> </div>
											<div class="pull-right">
												<span class="semi-bold text-white"><?=number_format($drPeopleCount1['countMac'],0)?>  </span>
												<span class="bold text-white"><!-- C&deg;(Καλό)--> </span>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left" style="width: 50%;">
												<span class="bold  text-white m-r-15">Παλιό δημαρχείο</span>
												<!-- <canvas id="white_widget_07" width="20" height="20" class="inline m-l-10"></canvas>-->
												<!-- <span>Μέτριο</span> -->
											</div>
											<div style="width: 30%;" class="pull-left"><span></span> </div>
											<div class="pull-right">
												<span class="semi-bold text-white"><?=number_format($drPeopleCount2['countMac'],0)?> </span>
												<span class="bold text-error"><!-- C&deg; (Μέτριο)--></span>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left" style="width: 50%;">
												<span class="bold  text-white m-r-15">Θέατρο</span>
											</div>
											
											<div style="width: 30%;" class="pull-left"><span></span> </div>
											<div class="pull-right">

												<span class="semi-bold text-white"><?=number_format($drPeopleCount3['countMac'],0)?> </span>
												<span class="bold text-error"></span>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>

								<!-- 
								<div class="tile-footer">
									<div class="pull-left">
										<canvas id="wind" width="32" height="32"></canvas>
										<span class="text-white small-text-description">Windy</span> 
									</div>
									<div class="pull-right">
										<canvas id="rain" width="32" height="32"></canvas>
										<span class="text-white small-text-description">Humidity</span> 
									</div>
									<div class="clearfix"></div>
								</div>				
								-->
							</div>
						</div>
						<div class="col-md-3 col-sm-3">
							<div class="tiles red weather-widget ">
								<div class="tiles-body">
									<div class="controller">
										<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
									</div>
									<!-- 
										<div class="tiles-title"> TODAY’S WEATHER </div>
									-->
									<div class="heading">
										<div class="pull-left"> Ηλιακή ακτινοβολία </div>
										<!-- <div class="pull-right"> 55 </div>-->
										<div class="clearfix"></div>
									</div>
									<!-- 
									<div class="big-icon">
										<canvas id="partly-cloudy-day" width="120" height="120"></canvas>
									</div>
									<div class="clearfix"></div>
									-->
									<div class=" p-t-25 p-r-10 p-l-10 p-b-15">
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left" style="width: 50%;">
											
												<span class="bold text-white m-r-15 text-right">Δείκτης UV </span>
												<!-- <span>Καλό</span> -->
												<!-- <canvas id="white_widget_06" width="20" height="20" class="inline m-l-10"></canvas>-->
											</div>
											<div style="width: 30%;" class="pull-left"><span>Μέσος</span> </div>
											<div class="pull-right">
												<span class="semi-bold text-white"><?=number_format($lastUv['measurement'],1)?>  </span>
												<span class="bold text-white"><!-- C&deg;(Καλό)--> </span>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left" style="width: 100%;">
												<span class="bold  text-white m-r-15">Συνίσταται προστασία σε σκιά. Σε εξωτερικό χώρο συνίσταται η χρήση αντηλιακού, προστατευτικών ρούχων, γυαλιών ηλίου και καπέλου.</span>
												<!-- <canvas id="white_widget_07" width="20" height="20" class="inline m-l-10"></canvas>-->
												<!-- <span>Μέτριο</span> -->
											</div>
											
											<div class="clearfix"></div>
										</div>
								
									</div>
								</div>
								<!-- 
								<div class="tile-footer">
									<div class="pull-left">
										<canvas id="wind" width="32" height="32"></canvas>
										<span class="text-white small-text-description">Windy</span> 
									</div>
									<div class="pull-right">
										<canvas id="rain" width="32" height="32"></canvas>
										<span class="text-white small-text-description">Humidity</span> 
									</div>
									<div class="clearfix"></div>
								</div>
								-->

							</div>
						</div>
					</div>
				
					<div class="row row-eq-height" style="margin-bottom:10px;opacity:0.9;">
						<div class="col-md-5  col-sm-12 b-grey b-r " style="background-color:#fff;margin-right:10px;"><!-- <div class="col-md-7  col-sm-7 b-grey b-r ">-->
							<h4 class="semi-bold text-center b-grey b-b no-margin p-t-20 p-b-10">Δράμα</h4>
							<?
								//$queryTemp = "SELECT max(t1.date_insert),t1.measurement,t1.parameter_id,t2.sensorvar_description,t2.sensorvar_unit,t2.sensorvar_dec FROM measurements t1 
								//				INNER JOIN sensorvars t2 ON t1.parameter_id = t2.sensorvar_id
								//				WHERE t1.parameter_id IN (27,28,29,30,31,32,33,34,35,36) GROUP BY parameter_id";
								$queryTemp = "SELECT t1.date_insert,t1.measurement,t1.parameter_id,t2.sensorvar_description,t2.sensorvar_unit,t2.sensorvar_dec FROM measurements t1 
											INNER JOIN sensorvars t2 ON t1.parameter_id = t2.sensorvar_id
											WHERE t1.parameter_id IN (27,28,29,30,31,32,33,34,35,36) 
											AND t1.date_insert = (SELECT max(date_insert) from measurements t2 WHERE parameter_id IN (27,28,29,30,31,32,33,34,35,36))";
								$resultTemp = $db->sql_query($queryTemp);
								while ($drTemp = $db->sql_fetchrow($resultTemp)){
									$meteoArray[$drTemp['parameter_id']]['sensorvar_description']=$drTemp['sensorvar_description'];
									$meteoArray[$drTemp['parameter_id']]['measurement']=$drTemp['measurement'];
									$meteoArray[$drTemp['parameter_id']]['unit']=$drTemp['sensorvar_unit'];
									$meteoArray[$drTemp['parameter_id']]['dec']=$drTemp['sensorvar_dec'];
								}
								/*
								2021-04-23 12:45:03 9.111000000 27 Θερμοκρασία °C 1
								2021-04-23 12:45:03 61.100000000 28 Υγρασία % 1 
								2021-04-23 12:45:03 213.000000000 29 Κατεύθυνση ανέμου NULL NULL
								2021-04-23 12:45:03 0.000000000 30 Δείκτης UV NULL 1
								2021-04-23 12:45:03 0.500000000 31 Ταχύτητα ανέμου m/sec 2
								2021-04-23 12:45:03 0.000000000 32 Βροχόπτωση τελευτ. ώρας mm 2
								2021-04-23 12:45:03 3.000000000 33 Δυνατότερος άνεμος m/sec 2
								2021-04-23 12:45:03 14.800000000 34 Μηνιαία βροχόπτωση mm 1
								2021-04-23 12:45:03 45.800000000 35 Αίσθηση θερμοκρασίας °C 1
								2021-04-23 12:45:03 0.890391575 36 Πίεση mBar 1
								*/
								$date = intval(date('w'));
								$day = array('Κυριακή', 'Δευτέρα', 'Τρίτη', 'Τετάρτη','Πέμπτη','Παρασκεύη', 'Σάββατο');
							?>
							<div class="b-grey b-b">
								<h4 class="semi-bold text-center text-error"><?=$day[$date+1].' '. date('d/m/Y')?></h4>
								<h1 class="semi-bold text-center text-error"> <?=number_format($meteoArray[27]['measurement'],$meteoArray[27]['dec'])?>&deg; </h1>
								<h5 class="text-center text-error">partly cloudy</h5>
								<div class="row auto m-t-10 m-b-10">
									<div class="col-md-3 col-sm-3 col-xs-3  no-padding col-md-offset-2 col-sm-offset-2 col-xs-offset-2">
										<canvas id="white_widget_cloudy_big" width="48" height="48" class="h-align-middle "></canvas>
									</div>
									<!-- 
									<div class="col-md-5 col-sm-5 col-xs-5 no-padding">
										<div class="m-t-10">
											<div class="pull-left m-l-5">
												<canvas id="white_widget_13" width="16" height="16" class="inline"></canvas>
												<div class="inline">
													<h5 class="semi-bold no-margin ">54</h5>
													<p class="bold text-extra-small ">MPH</p>
												</div>
											</div>
											<div class="pull-right m-r-10">
												<canvas id="white_widget_14" width="16" height="16" class="inline"></canvas>
												<div class="inline">
													<h5 class="semi-bold no-margin ">53</h5>
													<p class="bold text-extra-small ">MM</p>
												</div>
											</div>
										</div>
									</div>
									-->
								</div>
							</div>
							<div class="row auto m-t-15">
								<div class="col-md-2 col-sm-2 col-xs-2 no-padding col-md-offset-1 col-xs-offset-1 b-grey b-r">
									<p class="text-center no-margin"><?=$meteoArray[28]['sensorvar_description']?></p><br>
									<!-- <p class="text-center no-margin">PM</p>-->
									<canvas id="white_widget_01" width="20" height="20" class="h-align-middle m-t-10"></canvas>
									<h5 class="semi-bold text-center text-error"><?=number_format($meteoArray[28]['measurement'],$meteoArray[28]['dec'])?> <?=$meteoArray[28]['sensorvar_unit']?></h5>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-2 no-padding b-grey b-r">
									<div class="text-center"><?=$meteoArray[31]['sensorvar_description']?></div>
									<!-- <div class="text-center">PM</div>-->
									<canvas id="white_widget_02" width="20" height="20" class="h-align-middle m-t-10"></canvas>
									<h5 class="semi-bold text-center text-error"><?=number_format($meteoArray[31]['measurement'],$meteoArray[31]['dec'])?> <?=$meteoArray[31]['sensorvar_unit']?></h5>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-2 no-padding b-grey b-r">
									<div class="text-center"><?=$meteoArray[32]['sensorvar_description']?></div>
									<!-- <div class="text-center">PM</div>-->
									<canvas id="white_widget_03" width="20" height="20" class="h-align-middle m-t-10"></canvas>
									<h5 class="semi-bold text-center text-error"><?=number_format($meteoArray[32]['measurement'],$meteoArray[32]['dec'])?> <?=$meteoArray[32]['sensorvar_unit']?></h5>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-2 no-padding b-grey b-r">
									<div class="text-center"><?=$meteoArray[35]['sensorvar_description']?></div>
									<!-- <div class="text-center">PM</div>-->
									<canvas id="white_widget_04" width="20" height="20" class="h-align-middle m-t-10"></canvas>
									<h5 class="semi-bold text-center text-error"><?=number_format($meteoArray[35]['measurement'],$meteoArray[35]['dec'])?> <?=$meteoArray[35]['sensorvar_unit']?></h5>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-2 no-padding b-grey">
									<div class="text-center"><?=$meteoArray[36]['sensorvar_description']?></div>
									<!-- <div class="text-center">PM</div>-->
									<canvas id="white_widget_05" width="20" height="20" class="h-align-middle m-t-10"></canvas>
									<h5 class="semi-bold text-center text-error"><?=number_format($meteoArray[36]['measurement'],$meteoArray[36]['dec'])?> <?=$meteoArray[36]['sensorvar_unit']?></h5>
								</div>
							</div>
						</div>
						<div class="col-md-3 col-sm-6 tiles grey" style="margin-right:10px;"><!-- <div class="col-md-5 col-sm-5 tiles grey">-->
							<div id="image-demo-carl-2" class="m-t-15 owl-carousel owl-theme" data-plugin-options='{"lazyLoad": true,"responsive": {"0": {"items": 1}, "479": {"items": 1}, "768": {"items": 1}, "979": {"items": 1}, "1199": {"items": 1}}, "loop": false, "nav": true, "dots": false, "autoplay": true}'>
								<?
								//Περιβαλλοντικά
								//Βρες τους αισθητήρες
								//SELECT * FROM mysensors WHERE sensortype_id=1 //1,10,11,12,13 //4,1,3,2,11
								//Για κάθε έναν από αυτούς βρες τις τελευταίες τιμές
								//SELECT * FROM measurements WHERE sensor_id=4 AND date_insert = (SELECT max(date_insert) FROM measurements WHERE sensor_id=4)
								//SELECT t1.sensor_id,t1.measurement,t1.date_insert,t2.mysensor_name,t2.location,t2.description,t2.lat,t2.lng,t2.ref_id,t3.sensorvar_id,t3.is_valid,t3.sensorvar_name,t3.sensorvar_description,t3.sensorvar_icon,t3.sensorvar_unit,t3.sensorvar_dec FROM measurements t1 INNER JOIN mysensors t2 ON t1.sensor_id=t2.ref_id INNER JOIN sensorvars t3 ON t1.parameter_id=t3.sensorvar_id WHERE t2.region_id=1 AND t1.sensor_id=4 AND t1.date_insert = (SELECT max(date_insert) FROM measurements WHERE sensor_id=4)
										$queryEnvSensors = "SELECT * FROM mysensors WHERE sensortype_id=1";
										$resultEnvSensors = $db->sql_query($queryEnvSensors);
										while ($drEnvSensors = $db->sql_fetchrow($resultEnvSensors)){
											echo '<div class="item">';
											echo '<p style="color:#000; text-align:center">'.$drEnvSensors['mysensor_name'].'</p>';
											echo '<div class=" p-t-25 p-r-10 p-l-10 p-b-15">';
											$filter='';
											if($drEnvSensors['ref_id']==1) $filter=' AND t3.sensorvar_id IN (1,2,3,9,10,11) ';
											$queryEnv = "SELECT t1.sensor_id,t1.measurement,t1.date_insert,t2.mysensor_name,t2.location,t2.description,t2.lat,t2.lng,t2.ref_id,t3.sensorvar_id,t3.is_valid,t3.sensorvar_name,t3.sensorvar_description,t3.sensorvar_icon,t3.sensorvar_unit,t3.sensorvar_dec 
											FROM measurements t1 
											INNER JOIN mysensors t2 ON t1.sensor_id=t2.ref_id 
											INNER JOIN sensorvars t3 ON t1.parameter_id=t3.sensorvar_id 
											WHERE 1=1  ".$filter." AND t2.region_id=1 AND t1.sensor_id=".$drEnvSensors['ref_id']." AND t1.date_insert = (SELECT max(date_insert) FROM measurements WHERE sensor_id=".$drEnvSensors['ref_id'].")";
											//echo $queryEnv;
											$resultEnv = $db->sql_query($queryEnv);
											while ($drEnv = $db->sql_fetchrow($resultEnv)){
												echo '<div class="p-b-10 m-b-10 b-grey b-b">';
													echo '<div class="pull-left">';
														echo '<span class="bold text-black m-r-15 text-right">'.$drEnv['sensorvar_description'].'</span>';
														//echo '<canvas id="white_widget_06" width="20" height="20" class="inline m-l-10"></canvas>';
														echo '<img style="height:20px;" src="/gallery/vars/Small/'.$drEnv['sensorvar_icon'].'">';
													echo '</div>';
													echo '<div class="pull-right">';
														echo '<span class="semi-bold text-grey">'.number_format($drEnv['measurement'],$drEnv['sensorvar_dec']).'</span>';
														echo '<span class="bold text-error">'.$drEnv['sensorvar_unit'].'</span>';
													echo '</div>';
													echo '<div class="clearfix"></div>';
												echo '</div>';
											}
											echo '</div>';
											echo '</div>';
										}
								?>
								<!-- 
								<div class="item">
									<div class=" p-t-25 p-r-10 p-l-10 p-b-15">
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left">
												<span class="bold text-black m-r-15 text-right">Sun</span>
												<canvas id="white_widget_06" width="20" height="20" class="inline m-l-10"></canvas>
											</div>
											<div class="pull-right">
												<span class="semi-bold text-grey">32 - 28</span>
												<span class="bold text-error">C&deg; </span>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left">
												<span class="bold  text-black m-r-15">Mon</span>
												<canvas id="white_widget_07" width="20" height="20" class="inline m-l-10"></canvas>
											</div>
											<div class="pull-right">
												<span class="semi-bold text-grey">32 - 28</span>
												<span class="bold text-error">C&deg; </span>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left">
												<span class="bold  text-black m-r-15">Tue</span>
												<canvas id="white_widget_08" width="20" height="20" class="inline m-l-10"></canvas>
											</div>
											<div class="pull-right">
												<span class="semi-bold text-grey">32 - 28</span>
												<span class="bold text-error">C&deg; </span>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left">
												<span class="bold  text-black m-r-5">Wed</span>
												<canvas id="white_widget_09" width="20" height="20" class="inline m-l-10"></canvas>
											</div>
											<div class="pull-right">
												<span class="semi-bold text-grey">32 - 28</span>
												<span class="bold text-error">C&deg; </span>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left">
												<span class="bold  text-black m-r-5">Thur</span>
												<canvas id="white_widget_10" width="20" height="20" class="inline m-l-10"></canvas>
											</div>
											<div class="pull-right">
												<span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="p-b-10 m-b-10 b-grey b-b">
											<div class="pull-left">
												<span class="bold  text-black m-r-15">Fri</span>
												<canvas id="white_widget_11" width="20" height="20" class="inline m-l-10"></canvas>
											</div>
											<div class="pull-right">
												<span class="semi-bold text-grey">32 - 28</span>
												<span class="bold text-error">C&deg; </span>
											</div>
											<div class="clearfix"></div>
										</div>
										<div class="p-b-10 m-b-10 b-grey">
											<div class="pull-left">
												<span class="bold  text-black m-r-10">Sat</span>
												<canvas id="white_widget_12" width="20" height="20" class="inline m-l-10"></canvas>
											</div>
											<div class="pull-right">
												<span class="semi-bold text-grey">32 - 28</span>
												<span class="bold text-error">C&deg; </span>
											</div>
											<div class="clearfix"></div>
										</div>
									</div>
								</div>
								-->
								
							</div>
						</div>
						<!-- 
						<div class="col-md-2 col-sm-6 tiles grey"><!- - <div class="col-md-5 col-sm-5 tiles grey">- ->
							<div class=" p-t-25 p-r-10 p-l-10 p-b-15">
								<div class="p-b-10 m-b-10 b-grey b-b">
									<div class="pull-left">
										<span class="bold text-black m-r-15 text-right">Sun</span>
										<canvas id="white_widget_06" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-grey">32 - 28</span>
										<span class="bold text-error">C&deg; </span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="p-b-10 m-b-10 b-grey b-b">
									<div class="pull-left">
										<span class="bold  text-black m-r-15">Mon</span>
										<canvas id="white_widget_07" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-grey">32 - 28</span>
										<span class="bold text-error">C&deg; </span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="p-b-10 m-b-10 b-grey b-b">
									<div class="pull-left">
										<span class="bold  text-black m-r-15">Tue</span>
										<canvas id="white_widget_08" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-grey">32 - 28</span>
										<span class="bold text-error">C&deg; </span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="p-b-10 m-b-10 b-grey b-b">
									<div class="pull-left">
										<span class="bold  text-black m-r-5">Wed</span>
										<canvas id="white_widget_09" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-grey">32 - 28</span>
										<span class="bold text-error">C&deg; </span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="p-b-10 m-b-10 b-grey b-b">
									<div class="pull-left">
										<span class="bold  text-black m-r-5">Thur</span>
										<canvas id="white_widget_10" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="p-b-10 m-b-10 b-grey b-b">
									<div class="pull-left">
										<span class="bold  text-black m-r-15">Fri</span>
										<canvas id="white_widget_11" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-grey">32 - 28</span>
										<span class="bold text-error">C&deg; </span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="p-b-10 m-b-10 b-grey">
									<div class="pull-left">
										<span class="bold  text-black m-r-10">Sat</span>
										<canvas id="white_widget_12" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-grey">32 - 28</span>
										<span class="bold text-error">C&deg; </span>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
						-->
						<div class="col-md-4 m-b-10">
							<div class="col-md-12">
								<div class="row tiles-container">
									<div class="col-md-6 tiles dark-blue" style="height:200px">
										<div class="overlayer top-right">
											<div class="p-r-15 p-t-15"> <i class="fa fa-filter text-white fa-4x"></i> </div>
										</div>
										<div class="overlayer bottom-left">
											<div class="p-l-15 p-b-15">
												<h2 class="text-white">Παλιός Νερόμυλος1</h2>
												<h4 class="text-white">Κανονική στάθμη υδάτων</h4>
												<span class="text-white mini-description ">Ροή υδάτων 25L/min</span>
											</div>
										</div>
									</div>
									<div class="col-md-6 tiles light-red" style="height:200px">
										<div class="overlayer top-right">
											<div class="p-r-15 p-t-15"><i class="fa fa-filter text-white fa-4x"></i> </div>
										</div>
										<div class="overlayer bottom-left">
											<div class="p-l-15 p-b-15">
												<h2 class="text-white">Παλιός Νερόμυλος2</h2>
												<h4 class="text-white">Κανονική στάθμη υδάτων</h4>
												<span class="text-white mini-description ">Ροή υδάτων 25L/min</span> 
											</div>
										</div>
									</div>
								</div>
								<div class="row tiles-container">
									<div class="col-md-6 tiles red" style="height:200px">
										<div class="overlayer top-right">
											<div class="p-r-15 p-t-15"> <i class="fa fa-filter text-white fa-4x"></i> </div>
										</div>
										<div class="overlayer bottom-left">
											<div class="p-l-15 p-b-15">
												<h2 class="text-white">Δημοτικό πάρκο</h2>
												<h4 class="text-white">Κανονική στάθμη υδάτων</h4>
												<span class="text-white mini-description ">Ροή υδάτων 25L/min</span> 
											</div>
										</div>
									</div>
									<div class="col-md-6 tiles light-blue" style="height:200px">
										<div class="overlayer top-right">
											<div class="p-r-15 p-t-15"> <i class="fa fa-filter text-white fa-4x"></i> </div>
										</div>
										<div class="overlayer bottom-left">
											<div class="p-l-15 p-b-15">
												<h2 class="text-white">Νησάκι</h2>
												<h4 class="text-white">Υπερχείλιση</h4>
												<span class="text-white mini-description ">Ροή υδάτων 0L/min</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
			
					<div class="row  row-eq-height" style="opacity:0.9;margin-bottom:10px;">
						<div class="col-md-4">
							<div class="tiles white"> <!-- style="margin-top:10px;"-->
								<div class="tiles-body">
									<div class="controller">
										<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
									</div>
									<div class="tiles-title"> WiFi Hotspots </div>
									<br>
									<div><!-- <div class="overlayer overlay-fixed">-->
									  <div class="overlayer-wrapper">
										<div class="content" style="padding-top:0;padding-left:0;padding-right:0;">
										  <!-- <h2>Google <span class="semi-bold">Maps</span></h2>-->
											<!-- <div id="wifimap" class="demo-map overlay-fixed" style="height:200px;"></div>-->
											<div id="map-canvas" class="demo-map overlay-fixed" style="height:200px;margin-bottom:10px;"></div>
										</div>
									  </div>
									</div>
									
									<?
										$queryWifi = "SELECT * FROM mysensors WHERE 1=1 AND is_valid='True' AND sensortype_id=4";
										$resultWifi = $db->sql_query($queryWifi);
										$location='';
										$mapCounter=0;
										while ($drWifi = $db->sql_fetchrow($resultWifi)){
											
											if($mapCounter==0) $center=$drWifi["lat"].','.$drWifi["lng"];
											$location.="[new google.maps.LatLng(".$drWifi["lat"].",".$drWifi["lng"]."), '".$drWifi["mysensor_name"]."', '".$drWifi["mysensor_name"]."'],";
											$mapCounter++;
											?>
											<script>
											//$(document).ready(function() {
											/*
											  setTimeout( function(){
												var marker =   new google.maps.Marker({
														icon: 'https://drama.smartiscity.gr/dashboard/assets/plugins/Mapplic/mapplic/images/pin-red.png',
														infoWindow: {content: 'Περιγραφή:<?=$drWifi["mysensor_name"]?>' },
														  lat: <?=$drWifi["lat"]?>,
														  lng: <?=$drWifi["lng"]?>,
														  animation: google.maps.Animation.DROP,
														  draggable:false,
														  title: '<?=$drWifi['mysensor_name']?>'
												  });
											  },3000);
											  */
											//});
											</script>
											<?
											echo '<div class="notification-messages info" style="padding:3px 18px 3px;">';
												echo '<div class="message-wrapper">';
													echo '<div class="heading"><a href="javascript:void(0);" class="marker-link" data-markerid="'.($mapCounter-1).'"> '.$drWifi['mysensor_name'].'</a></div>';
													echo '<div class="description"> '.$drWifi['location'].' </div>';
													//$drWifi['lat']
													//$drWifi['lng']
													//$urlLocation='<a target="_blank" href="https://maps.google.com/?daddr='.$drWifi["lat"].','.$drWifi["lng"].'"><i class="fa fa-location-arrow"></i></a>';
													$urlLocation='<a target="_blank" href="https://www.google.com/maps/dir/Current+Location/'.$drWifi["lat"].','.$drWifi["lng"].'"><i class="fa fa-location-arrow"></i></a>';
												
												echo '</div>';
												echo '<div class="date pull-right"> '.$urlLocation.'</div>';
												echo '<div class="clearfix"></div>';
											echo '</div>';
					
										}
										$location = mb_substr($location, 0, -1);
									?>
								</div>
							</div>
						</div>
						<div class="col-md-8"><!-- <div class="col-md-4 m-b-10">-->
							<div class="tiles white m-b-10">
								<div class="tiles-body">
									<div class="controller">
										<a href="javascript:;" class="reload"></a>
										<a href="javascript:;" class="remove"></a>
									</div>
									<div class="tiles-title"> RSS News </div>
									<br>
									<div id="rssOutput"></div>
								</div>
							</div>
						</div>
					</div>
			

			<div class="row">
				<div class="col-md-6">
					<div class="tiles white">
						<div class="tiles-body">
							<div class="controller">
								<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
							</div>
							<div class="tiles-title"> WiFi Hotspots2 </div>
							<br>
							<?
								$queryWifi = "SELECT * FROM `mysensors` WHERE 1=1 AND is_valid='True' AND sensortype_id=4";
								$resultWifi = $db->sql_query($queryWifi);
								while ($drWifi = $db->sql_fetchrow($resultWifi)){
									?>
									<script>
									/*
									  setTimeout( function(){
										  map.addMarker({
												icon: 'https://drama.smartiscity.gr/dashboard/assets/plugins/Mapplic/mapplic/images/pin-red.png',
												infoWindow: {content: 'Περιγραφή:<?=$drWifi["mysensor_name"]?>' },
												  lat: <?=$drWifi["lat"]?>,
												  lng: <?=$drWifi["lng"]?>,
												  animation: google.maps.Animation.DROP,
												  draggable:false,
												  title: '<?=$drWifi['mysensor_name']?>'
										  });
									  },3000);
									*/

									</script>
									<?
									echo '<div class="notification-messages info">';
										echo '<div class="message-wrapper">';
											echo '<div class="heading"> '.$drWifi['mysensor_name'].'</div>';
											echo '<div class="description"> '.$drWifi['location'].' </div>';
											//$drWifi['lat']
											//$drWifi['lng']
											//$urlLocation='<a target="_blank" href="https://maps.google.com/?daddr='.$drWifi["lat"].','.$drWifi["lng"].'"><i class="fa fa-location-arrow"></i></a>';
											$urlLocation='<a target="_blank" href="https://www.google.com/maps/dir/Current+Location/'.$drWifi["lat"].','.$drWifi["lng"].'"><i class="fa fa-location-arrow"></i></a>';
										
										echo '</div>';
										echo '<div class="date pull-right"> '.$urlLocation.'</div>';
										echo '<div class="clearfix"></div>';
									echo '</div>';
			
								}
							?>								
						
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="tiles white">
						<div class="tiles-body">
							<div class="controller">
								<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
							</div>
							<div class="tiles-title"> WiFi Hotspots2 </div>
							<div><!-- <div class="overlayer overlay-fixed">-->
							  <div class="overlayer-wrapper">
								<div class="content" style="padding-top:0;">
								  <h2>Google <span class="semi-bold">Maps</span></h2>
									 <div id="wifimap2" class="demo-map overlay-fixed"></div>
								</div>
							  </div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br/>
			<div class="row">
				<div class="col-md-3 col-sm-3">
					<div class="tiles red weather-widget ">
						<div class="tiles-body">
							<div class="controller">
								<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
							</div>
							<div class="tiles-title"> TODAY’S WEATHER </div>
							<div class="heading">
								<div class="pull-left"> Tuesday </div>
								<div class="pull-right"> 55 </div>
								<div class="clearfix"></div>
							</div>
							<!-- 
							<div class="big-icon">
								<canvas id="partly-cloudy-day" width="120" height="120"></canvas>
							</div>
							
							<div class="big-icon">
								<canvas id="partly-cloudy-day" width="120" height="120"></canvas>
							</div>
							-->
							<div class=" p-t-25 p-r-10 p-l-10 p-b-15">
								<div class="p-b-10 m-b-10 b-grey b-b">
									<div class="pull-left">
										<span class="bold text-white m-r-15 text-right">PM1</span>
										<canvas id="white_widget_06" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-white"><?=number_format($pmArrayAVG[9]['measurement'],2)?></span>
										<span class="bold text-error"><!-- C&deg;--> Καλό</span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="p-b-10 m-b-10 b-grey b-b">
									<div class="pull-left">
										<span class="bold  text-white m-r-15">PM2.5</span>
										<canvas id="white_widget_07" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-white"><?=number_format($pmArrayAVG[10]['measurement'],2)?></span>
										<span class="bold text-error"><!-- C&deg;--> Μέτριο</span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="p-b-10 m-b-10 b-grey b-b">
									<div class="pull-left">
										<span class="bold  text-white m-r-15">PM10</span>
										<canvas id="white_widget_08" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-white"><?=number_format($pmArrayAVG[11]['measurement'],2)?></span>
										<span class="bold text-error"><!-- C&deg;--> Κακό</span>
									</div>
									<div class="clearfix"></div>
								</div>
							
							</div>

							<div class="clearfix"></div>
						</div>
						<div class="tile-footer">
							<div class="pull-left">
								<canvas id="wind" width="32" height="32"></canvas>
								<span class="text-white small-text-description">Windy</span> 
							</div>
							<div class="pull-right">
								<canvas id="rain" width="32" height="32"></canvas>
								<span class="text-white small-text-description">Humidity</span> 
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<div class="tiles red weather-widget ">
						<div class="tiles-body">
							<div class="controller">
								<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
							</div>
							<div class="tiles-title"> TODAY’S WEATHER </div>
							<div class="heading">
								<div class="pull-left"> Tuesday </div>
								<div class="pull-right"> 55 </div>
								<div class="clearfix"></div>
							</div>
							<div class="big-icon">
								<canvas id="partly-cloudy-day" width="120" height="120"></canvas>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="tile-footer">
							<div class="pull-left">
								<canvas id="wind" width="32" height="32"></canvas>
								<span class="text-white small-text-description">Windy</span> 
							</div>
							<div class="pull-right">
								<canvas id="rain" width="32" height="32"></canvas>
								<span class="text-white small-text-description">Humidity</span> 
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<div class="tiles red weather-widget ">
						<div class="tiles-body">
							<div class="controller">
								<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
							</div>
							<div class="tiles-title"> TODAY’S WEATHER </div>
							<div class="heading">
								<div class="pull-left"> Tuesday </div>
								<div class="pull-right"> 55 </div>
								<div class="clearfix"></div>
							</div>
							<div class="big-icon">
								<canvas id="partly-cloudy-day" width="120" height="120"></canvas>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="tile-footer">
							<div class="pull-left">
								<canvas id="wind" width="32" height="32"></canvas>
								<span class="text-white small-text-description">Windy</span> 
							</div>
							<div class="pull-right">
								<canvas id="rain" width="32" height="32"></canvas>
								<span class="text-white small-text-description">Humidity</span> 
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<div class="tiles red weather-widget ">
						<div class="tiles-body">
							<div class="controller">
								<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
							</div>
							<div class="tiles-title"> TODAY’S WEATHER </div>
							<div class="heading">
								<div class="pull-left"> Tuesday </div>
								<div class="pull-right"> 55 </div>
								<div class="clearfix"></div>
							</div>
							<div class="big-icon">
								<canvas id="partly-cloudy-day" width="120" height="120"></canvas>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="tile-footer">
							<div class="pull-left">
								<canvas id="wind" width="32" height="32"></canvas>
								<span class="text-white small-text-description">Windy</span> 
							</div>
							<div class="pull-right">
								<canvas id="rain" width="32" height="32"></canvas>
								<span class="text-white small-text-description">Humidity</span> 
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
			
			<br/>
			<div class="row  row-eq-height">
				<div class="col-md-3 col-sm-3">
					<div class="row">
						<div class="tiles red weather-widget ">
							<div class="tiles-body">
								<div class="controller">
									<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
								</div>
								<div class="tiles-title"> TODAY’S WEATHER </div>
								<div class="heading">
									<div class="pull-left"> Tuesday </div>
									<div class="pull-right"> 55 </div>
									<div class="clearfix"></div>
								</div>
								<div class="big-icon">
									<canvas id="partly-cloudy-day" width="120" height="120"></canvas>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="tile-footer">
								<div class="pull-left">
									<canvas id="wind" width="32" height="32"></canvas>
									<span class="text-white small-text-description">Windy</span> 
								</div>
								<div class="pull-right">
									<canvas id="rain" width="32" height="32"></canvas>
									<span class="text-white small-text-description">Humidity</span> 
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
					<div class="row" style="padding-top:10px">
						<div class="tiles red weather-widget ">
							<div class="tiles-body">
								<div class="controller">
									<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
								</div>
								<div class="tiles-title"> TODAY’S WEATHER22 </div>
								<div class="heading">
									<div class="pull-left"> Tuesday </div>
									<div class="pull-right"> 55 </div>
									<div class="clearfix"></div>
								</div>
								<div class="big-icon">
									<canvas id="partly-cloudy-day" width="120" height="120"></canvas>
								</div>
								<div class="clearfix"></div>
							</div>
							<div class="tile-footer">
								<div class="pull-left">
									<canvas id="wind" width="32" height="32"></canvas>
									<span class="text-white small-text-description">Windy</span> 
								</div>
								<div class="pull-right">
									<canvas id="rain" width="32" height="32"></canvas>
									<span class="text-white small-text-description">Humidity</span> 
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3" style="background-color:#f35958;margin-left:10px;">
					<div class="red weather-widget "><!-- tiles red weather-widget-->
						<div class="tiles-body">
							<div class="controller">
								<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
							</div>
							<div class="tiles-title"> TODAY’S WEATHER11 </div>
							<div class="heading">
								<div class="pull-left"> Tuesday </div>
								<div class="pull-right"> 55 </div>
								<div class="clearfix"></div>
							</div>
							<div class="big-icon">
								<canvas id="partly-cloudy-day" width="120" height="120"></canvas>
							</div>
							
							<div class=" p-t-25 p-r-10 p-l-10 p-b-15">
								<div class="p-b-10 m-b-10 b-grey b-b">
									<div class="pull-left">
										<span class="bold text-black m-r-15 text-right">Sun</span>
										<canvas id="white_widget_06" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-grey">32 - 28</span>
										<span class="bold text-error">C&deg; </span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="p-b-10 m-b-10 b-grey b-b">
									<div class="pull-left">
										<span class="bold  text-black m-r-15">Mon</span>
										<canvas id="white_widget_07" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-grey">32 - 28</span>
										<span class="bold text-error">C&deg; </span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="p-b-10 m-b-10 b-grey b-b">
									<div class="pull-left">
										<span class="bold  text-black m-r-15">Tue</span>
										<canvas id="white_widget_08" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-grey">32 - 28</span>
										<span class="bold text-error">C&deg; </span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="p-b-10 m-b-10 b-grey b-b">
									<div class="pull-left">
										<span class="bold  text-black m-r-5">Wed</span>
										<canvas id="white_widget_09" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-grey">32 - 28</span>
										<span class="bold text-error">C&deg; </span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="p-b-10 m-b-10 b-grey b-b">
									<div class="pull-left">
										<span class="bold  text-black m-r-5">Thur</span>
										<canvas id="white_widget_10" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="p-b-10 m-b-10 b-grey b-b">
									<div class="pull-left">
										<span class="bold  text-black m-r-15">Fri</span>
										<canvas id="white_widget_11" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-grey">32 - 28</span>
										<span class="bold text-error">C&deg; </span>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="p-b-10 m-b-10 b-grey">
									<div class="pull-left">
										<span class="bold  text-black m-r-10">Sat</span>
										<canvas id="white_widget_12" width="20" height="20" class="inline m-l-10"></canvas>
									</div>
									<div class="pull-right">
										<span class="semi-bold text-grey">32 - 28</span>
										<span class="bold text-error">C&deg; </span>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
							
							
							
							<div class="clearfix"></div>
						</div>
						
						<div class=""style="background-color: rgba(0, 0, 0, 0.28);color: #ffffff;font-size: 13px;padding: 8px 15px;position:absolute;bottom:0;left:0;width:100%;"><!-- tile-footer-->
							<div class="pull-left">
								<canvas id="wind" width="32" height="32"></canvas>
								<span class="text-white small-text-description">Windy1</span> 
							</div>
							<div class="pull-right">
								<canvas id="rain" width="32" height="32"></canvas>
								<span class="text-white small-text-description" style="position: relative;">Humidity1</span> 
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					
				</div>
				<div class="col-md-3 col-sm-3">
					<div class="tiles red weather-widget ">
						<div class="tiles-body">
							<div class="controller">
								<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
							</div>
							<div class="tiles-title"> TODAY’S WEATHER </div>
							<div class="heading">
								<div class="pull-left"> Tuesday </div>
								<div class="pull-right"> 55 </div>
								<div class="clearfix"></div>
							</div>
							<div class="big-icon">
								<canvas id="partly-cloudy-day" width="120" height="120"></canvas>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="tile-footer">
							<div class="pull-left">
								<canvas id="wind" width="32" height="32"></canvas>
								<span class="text-white small-text-description">Windy</span> 
							</div>
							<div class="pull-right">
								<canvas id="rain" width="32" height="32"></canvas>
								<span class="text-white small-text-description">Humidity</span> 
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<div class="tiles red weather-widget ">
						<div class="tiles-body">
							<div class="controller">
								<a href="javascript:;" class="reload"></a><a href="javascript:;" class="remove"></a>
							</div>
							<div class="tiles-title"> TODAY’S WEATHER </div>
							<div class="heading">
								<div class="pull-left"> Tuesday </div>
								<div class="pull-right"> 55 </div>
								<div class="clearfix"></div>
							</div>
							<div class="big-icon">
								<canvas id="partly-cloudy-day" width="120" height="120"></canvas>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="tile-footer">
							<div class="pull-left">
								<canvas id="wind" width="32" height="32"></canvas>
								<span class="text-white small-text-description">Windy</span> 
							</div>
							<div class="pull-right">
								<canvas id="rain" width="32" height="32"></canvas>
								<span class="text-white small-text-description">Humidity</span> 
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
			
			  
         
             
            </div>
			  
			  
			  
			  
			<!-- 
			<div class="row">
				<div class="col-md-6 col-vlg-4 col-sm-12 ">
					<div class="tiles overflow-hidden full-height tiles-overlay-hover m-b-10 widget-item">
						<div class="controller overlay right">
							<a href="javascript:;" class="reload"></a>
							<a href="javascript:;" class="remove"></a>
						</div>
						<div class="overlayer tiles-overlay auto blue">
							<div class="overlayer-wrapper  p-t-20 p-l-20 p-r-20 p-b-20">
								<i class="fa fa-map-marker fa-2x"></i>
								<p class="p-t-20 text-white-opacity">21 Jan</p>
								<h3 class="text-white">New Year <span class="semi-bold">UI Bundle <br></span> now on webarch</h3>
								<p class="p-t-20"><span class="bold">214</span> Comments <span class="m-l-10 bold">24k</span> Likes</p>
							</div>
						</div>
						<div class="overlayer bottom-left fullwidth">
							<div class="overlayer-wrapper">
								<div class="tiles gradient-grey p-l-20 p-r-20 p-b-20 p-t-20"> 
									<a href="#" class="hashtags transparent"> #citymax </a>
									<a href="#" class="hashtags transparent"> #new york city </a>
									<a href="#" class="hashtags transparent"> #amazing </a>
									<p class="p-t-10 p-b-10 "><span class="bold">Jane Smith, John Smith, David Jester, pepper</span> post and 214 others like this.</p>
									<div class="profile-img-wrapper inline m-r-5">
										<img src="assets/img/profiles/avatar_small.jpg" alt="" data-src="assets/img/profiles/avatar_small.jpg" data-src-retina="assets/img/profiles/avatar_small2x.jpg" width="35" height="35">
									</div>
									<input type="text" class="dark m-r-5" id="txtinput1" placeholder="Write a comment" style="width:60%">
									<button type="button" class="btn btn-primary">Joing Group</button>
								</div>
							</div>
						</div>
						<img src="assets/img/others/9.jpg" data-src="assets/img/others/9.jpg" data-src-retina="assets/img/others/rob.jpg" alt="" class="image-responsive-width hover-effect-img">
					</div>
				</div>
				<div class="col-md-6 col-vlg-4 col-sm-12">
					<div class="row ">
						<div class="col-md-6 col-sm-6 m-b-10" data-aspect-ratio="true">
							<div class="live-tile slide ha " data-speed="750" data-delay="3000" data-mode="carousel">
								<div class="slide-front ha tiles adjust-text">
									<div class="p-t-20 p-l-20 p-r-20 p-b-20"> <i class="fa fa-map-marker fa-2x"></i>
										<p class="text-white-opacity p-t-10">21 Jan</p>
										<h3 class="text-white no-margin">New Year <span class="semi-bold">UI Bundle <br></span> now on webarch</h3>
										<p class="p-t-20 "><span class="bold">214</span> Comments <span class="m-l-10 bold">24k</span> Likes</p>
									</div>
								</div>
								<div class="slide-back ha tiles adjust-text">
									<div class="p-t-20 p-l-20 p-r-20 p-b-20"> <i class="fa fa-map-marker fa-2x"></i>
										<p class="text-white-opacity p-t-10">21 Jan</p>
										<h3 class="text-white no-margin">New Year <span class="semi-bold">UI Bundle <br></span> now on webarch</h3>
										<p class="p-t-20 "><span class="bold">214</span> Comments <span class="m-l-10 bold">24k</span> Likes</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 m-b-10" data-aspect-ratio="true">
							<div class="live-tile slide ha " data-speed="750" data-delay="4000" data-mode="carousel">
								<div class="slide-front ha tiles blue ">
									<div class="p-t-20 p-l-20 p-r-20 p-b-20">
										<h4 class="text-white no-margin custom-line-height">“Just <span class="semi-bold">Completed</span> the <span class="semi-bold">Heart walk</span> advertiing campaign”</h4>
									</div>
									<div class="overlayer bottom-left fullwidth">
										<div class="overlayer-wrapper">
											<div class="user-comment-wrapper">
												<div class="profile-wrapper">
													<img src="assets/img/profiles/avatar_small.jpg" alt="" data-src="assets/img/profiles/avatar_small.jpg" data-src-retina="assets/img/profiles/avatar_small2x.jpg" width="35" height="35">
												</div>
												<div class="comment">
													<div class="user-name text-white "><span class="bold"> David</span> Cooper </div>
													<p class="text-white-opacity">@ Revox</p>
												</div>
												<div class="clearfix"></div>
											</div>
										</div>
									</div>
								</div>
								<div class="slide-back ha tiles blue">
									<div class="user-comment-wrapper m-t-20">
										<div class="profile-wrapper">
											<img src="assets/img/profiles/d.jpg" alt="" data-src="assets/img/profiles/d.jpg" data-src-retina="assets/img/profiles/d2x.jpg" width="35" height="35">
										</div>
										<div class="comment">
											<div class="user-name text-white "><span class="bold"> Jane</span> Smith </div>
											<p class="text-white-opacity">@ Revox</p>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="overlayer bottom-left fullwidth">
										<div class="overlayer-wrapper">
											<div class="p-t-20 p-l-20 p-r-20 p-b-20">
												<h4 class="text-white no-margin custom-line-height">“Just <span class="semi-bold">Completed</span> the <span class="semi-bold">Heart walk</span> adverting campaign”</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6  col-sm-6 m-b-10" data-aspect-ratio="true">
							<div class="live-tile slide ha" data-speed="750" data-delay="4500" data-mode="carousel">
								<div class="slide-front ha tiles green p-t-20 p-l-20 p-r-20 p-b-20">
									<h1 class="semi-bold text-white">15% <i class="icon-custom-up icon-custom-2x"></i></h1>
									<div class="overlayer bottom-left fullwidth">
										<div class="overlayer-wrapper">
											<div class="p-t-20 p-l-20 p-r-20 p-b-20">
												<p class="bold">Webarch Dashboard</p>
												<p>2,567 USD <span class="m-l-10"><i class="fa fa-sort-desc"></i> 2%</span></p>
												<p class="bold p-t-15">Front-end Design</p>
												<p>1,420 USD <span class="m-l-10"><i class="fa fa-sort-desc"></i> 1%</span></p>
											</div>
										</div>
									</div>
								</div>
								<div class="slide-back ha tiles green">
									<div class="p-t-20 p-l-20 p-r-20 p-b-20">
										<h5 class="text-white semi-bold no-margin p-b-5">Today Sale's</h5>
										<h3 class="text-white no-margin">450 <span class="semi-bold">USD</span></h3> Last Sale 23.45 USD
									</div>
									<div class="overlayer bottom-left fullwidth">
										<div class="overlayer-wrapper">
											<div id="sales-sparkline"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6  col-sm-6 m-b-10" data-aspect-ratio="true">
							<div class="live-tile slide ha " data-speed="750" data-delay="6000" data-mode="carousel">
								<div class="slide-front ha tiles green ">
									<div class="overlayer bottom-left fullwidth">
										<div class="overlayer-wrapper">
											<div class="tiles gradient-black p-l-20 p-r-20 p-b-20 p-t-20">
												<h4 class="text-white semi-bold no-margin">RUN AWAY GO </h4>
												<h5 class="text-white semi-bold ">Queen's favourite</h5>
												<p class="text-white semi-bold no-margin"><i class="icon-custom-up "></i> Read More</p>
											</div>
										</div>
									</div>
									<img src="assets/img/others/11.png" alt="" class="image-responsive-width xs-image-responsive-width">
								</div>
								<div class="slide-back ha tiles green">
									<div class="overlayer bottom-left fullwidth">
										<div class="overlayer-wrapper">
											<div class="tiles gradient-black p-l-20 p-r-20 p-b-20 p-t-20">
												<h5 class="text-white semi-bold ">King's favourite</h5>
												<p class="text-white semi-bold no-margin"><i class="icon-custom-up "></i> Read More</p>
											</div>
										</div>
									</div>
									<img src="assets/img/others/cover.jpg" alt="" class="image-responsive-width xs-image-responsive-width">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-vlg-4 visible-xlg">
					<div class="row tiles-container">
						<div class="col-md-12">
							<div class="tiles white" data-aspect-ratio="true">
								<div class="col-md-7 b-grey b-r no-padding" style="height:100%">
									<div class="p-l-20 p-r-20 p-b-10 p-t-10 b-b b-grey">
										<h5 class="text-success bold inline">MARKET</h5>
										<h5 class="text-black bold inline m-l-10">DOW</h5>
										<div class="">
											<i class="fa fa-sort-asc fa-2x text-error inline p-b-10" style="vertical-align: super;"></i>
											<h1 class="text-error bold inline no-margin"> 15,580.11</h1>
										</div>
									</div>
									<div class="p-l-20 p-r-20 p-b-10 p-t-10 b-b b-grey">
										<div class="pull-left">
											<p class="text-success">Open</p>
											<p class="text-black">16:203.26</p>
										</div>
										<div class="pull-right">
											<p class="text-success">Day Range</p>
											<p class="text-black">15,568.11 - 16,203.25</p>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="overlayer bottom-left fullwidth">
										<div class="overlayer-wrapper">
											<div class="" id="shares-chart-01" style="width:100%"> </div>
										</div>
									</div>
								</div>
								<div class="col-md-5 no-padding full-height">
									<div class="p-l-15 p-r-15 p-b-10 p-t-10 b-b b-grey">
										<h4 class="text-black ">Watchlist</h4>
										<input type="text" class="dark form-control" id="txtinput2" placeholder="Search">
									</div>
									<div class="market-share-widget-innerscroller">
										<div class="scroller scrollbar-dynamic" data-height="100%" data-always-visible="1">
											<div class="p-l-15 p-r-15 p-b-10 p-t-10 b-b b-grey">
												<div class="pull-left">
													<p class="small-text">GMY</p>
												</div>
												<div class="pull-right">
													<p class="small-text">GMY & SKR 100</p>
												</div>
												<div class="clearfix"></div>
												<div class="pull-left">
													<h4 class="semi-bold">18,500.11</h4>
												</div>
												<div class="pull-right" style="line-height: 27px;">
													<span class="label label-important" style="vertical-align: bottom;">-318.2</span>
												</div>
												<div class="clearfix"></div>
											</div>
											<div class="p-l-15 p-r-15 p-b-10 p-t-10 b-b b-grey">
												<div class="pull-left">
													<p class="small-text">KPM</p>
												</div>
												<div class="pull-right">
													<p class="small-text">KPMG 350</p>
												</div>
												<div class="clearfix"></div>
												<div class="pull-left">
													<h4 class="semi-bold">15,425.25</h4>
												</div>
												<div class="pull-right" style="line-height: 27px;">
													<span class="label label-success" style="vertical-align: bottom;">+318.2</span>
												</div>
												<div class="clearfix"></div>
											</div>
											<div class="p-l-15 p-r-15 p-b-10 p-t-10 b-b b-grey">
												<div class="pull-left">
													<p class="small-text">PTR</p>
												</div>
												<div class="pull-right">
													<p class="small-text">PRT & SPR 245</p>
												</div>
												<div class="clearfix"></div>
												<div class="pull-left">
													<h4 class="semi-bold">11,540.11</h4>
												</div>
												<div class="pull-right" style="line-height: 27px;">
													<span class="label label-important" style="vertical-align: bottom;">-345.2</span>
												</div>
												<div class="clearfix"></div>
											</div>
											<div class="p-l-15 p-r-15 p-b-10 p-t-10 b-b b-grey">
												<div class="pull-left"><p class="small-text">HGM</p></div>
												<div class="pull-right"><p class="small-text">HGM & POR 450</p></div>
												<div class="clearfix"></div>
												<div class="pull-left"><h4 class="semi-bold">9,500</h4></div>
												<div class="pull-right" style="line-height: 27px;">
													<span class="label label-success" style="vertical-align: bottom;">+100.2</span>
												</div>
												<div class="clearfix"></div>
											</div>
											<div class="p-l-15 p-r-15 p-b-10 p-t-10 b-b b-grey">
												<div class="pull-left"><p class="small-text">MKR</p></div>
												<div class="pull-right"><p class="small-text">MKR & SPR 547</p></div>
												<div class="clearfix"></div>
												<div class="pull-left"><h4 class="semi-bold">15,855.11</h4></div>
												<div class="pull-right" style="line-height: 27px;">
													<span class="label label-important" style="vertical-align: bottom;">-318.2</span>
												</div>
												<div class="clearfix"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			-->
			<!-- 
			<div class="row">
				<div class="col-md-8 col-vlg-8 m-b-10">
					<div class="row">
						<div class="col-md-12" data-sync-height="true">
							<div class="col-md-7 col-vlg-8 col-sm-8 no-padding -height">
								<div class="tiles green" id="mapplic_demo"></div>
							</div>
							<div class="col-md-5 col-vlg-4 col-sm-4 no-padding">
								<div class="tiles black">
									<div class="tiles-body">
										<h5 class="text-white"><span class="semi-bold">QUICK</span> VIEW</h5>
										<input type="text" placeholder="Search..." class="form-control input-sm m-t-20">
										<div class="m-t-40">
											<div class="widget-stats">
												<div class="wrapper"> <span class="item-title">Overall Visits</span> <span class="item-count animate-number semi-bold" data-value="2415" data-animation-duration="700">0</span> </div>
											</div>
											<div class="widget-stats">
												<div class="wrapper">
													<span class="item-title">Today's</span> <span class="item-count animate-number semi-bold" data-value="751" data-animation-duration="700">0</span>
												</div>
											</div>
											<div class="widget-stats ">
												<div class="wrapper last">
													<span class="item-title">Monthly</span> <span class="item-count animate-number semi-bold" data-value="1547" data-animation-duration="700">0</span>
												</div>
											</div>
											<div class="progress transparent progress-small no-radius m-t-20" style="width:90%">
												<div class="progress-bar progress-bar-success animate-progress-bar" data-percentage="64.8%"></div>
											</div>
											<div class="description"> 
												<span class="text-white mini-description ">4% higher <span class="blend">than last month</span></span>
											</div>
										</div>
									</div>
									<div id="chart" style="height:123px"> </div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4 col-vlg-4 m-b-10 ">
					<div class="tiles white">
						<div class="row">
							<div class="sales-graph-heading">
								<div class="col-md-5 col-sm-5">
									<h5 class="no-margin">You have earned</h5>
									<h4><span class="item-count animate-number semi-bold" data-value="21451" data-animation-duration="700">0</span> USD</h4>
								</div>
								<div class="col-md-3 col-sm-3">
									<p class="semi-bold">TODAY</p>
									<h4><span class="item-count animate-number semi-bold" data-value="451" data-animation-duration="700">0</span> USD</h4>
								</div>
								<div class="col-md-4 col-sm-3">
									<p class="semi-bold">THIS MONTH</p>
									<h4><span class="item-count animate-number semi-bold" data-value="8514" data-animation-duration="700">0</span> USD</h4>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<h5 class="semi-bold m-t-30 m-l-30">LAST SALE</h5>
						<table class="table no-more-tables m-t-20 m-l-20 m-b-30">
							<thead style="display:none">
								<tr>
									<th style="width:9%">Project Name</th>
									<th style="width:22%">Description</th>
									<th style="width:6%">Price</th>
									<th style="width:1%"> </th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="v-align-middle bold text-success">25601</td>
									<td class="v-align-middle"><span class="muted">Redesign project template</span> </td>
									<td><span class="muted bold text-success">$4,500</span> </td>
									<td class="v-align-middle"></td>
								</tr>
								<tr>
									<td class="v-align-middle bold text-success">25601</td>
									<td class="v-align-middle"><span class="muted">Redesign project template</span> </td>
									<td><span class="muted bold text-success">$4,500</span> </td>
									<td class="v-align-middle"></td>
								</tr>
							</tbody>
						</table>
						<div id="sales-graph"> </div>
					</div>
				</div>
			</div>
			-->
		
		
          <div class="row">
            <div class="col-md-6 col-vlg-4 visible-xlg">
              <div class="row tiles-container tiles white m-b-10 visible-xlg">
                <div class="col-md-7  col-sm-7 b-grey b-r ">
                  <h4 class="semi-bold text-center b-grey b-b no-margin p-t-20 p-b-10">California USA</h4>
                  <div class="b-grey b-b">
                    <h4 class="semi-bold text-center text-error">Sunday</h4>
                    <h1 class="semi-bold text-center text-error"> 32&deg; </h1>
                    <h5 class="text-center text-error">partly cloudy</h5>
                    <div class="row auto m-t-10 m-b-10">
                      <div class="col-md-3 col-sm-3 col-xs-3  no-padding col-md-offset-2 col-sm-offset-2 col-xs-offset-2">
                        <canvas id="white_widget_cloudy_big" width="48" height="48" class="h-align-middle "></canvas>
                      </div>
                      <div class="col-md-5 col-sm-5 col-xs-5 no-padding">
                        <div class="m-t-10">
                          <div class="pull-left m-l-5">
                            <canvas id="white_widget_13" width="16" height="16" class="inline"></canvas>
                            <div class="inline">
                              <h5 class="semi-bold no-margin ">54</h5>
                              <p class="bold text-extra-small ">MPH</p>
                            </div>
                          </div>
                          <div class="pull-right m-r-10">
                            <canvas id="white_widget_14" width="16" height="16" class="inline"></canvas>
                            <div class="inline">
                              <h5 class="semi-bold no-margin ">53</h5>
                              <p class="bold text-extra-small ">MM</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row auto m-t-15">
                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding col-md-offset-1 col-xs-offset-1 b-grey b-r">
                      <p class="text-center no-margin">11:30</p>
                      <p class="text-center no-margin">PM</p>
                      <canvas id="white_widget_01" width="20" height="20" class="h-align-middle m-t-10"></canvas>
                      <h5 class="semi-bold text-center text-error">32&deg;</h5>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding b-grey b-r">
                      <div class="text-center">11:30</div>
                      <div class="text-center">PM</div>
                      <canvas id="white_widget_02" width="20" height="20" class="h-align-middle m-t-10"></canvas>
                      <h5 class="semi-bold text-center text-error">32&deg;</h5>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding b-grey b-r">
                      <div class="text-center">11:30</div>
                      <div class="text-center">PM</div>
                      <canvas id="white_widget_03" width="20" height="20" class="h-align-middle m-t-10"></canvas>
                      <h5 class="semi-bold text-center text-error">32&deg;</h5>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding b-grey b-r">
                      <div class="text-center">11:30</div>
                      <div class="text-center">PM</div>
                      <canvas id="white_widget_04" width="20" height="20" class="h-align-middle m-t-10"></canvas>
                      <h5 class="semi-bold text-center text-error">32&deg;</h5>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding b-grey">
                      <div class="text-center">11:30</div>
                      <div class="text-center">PM</div>
                      <canvas id="white_widget_05" width="20" height="20" class="h-align-middle m-t-10"></canvas>
                      <h5 class="semi-bold text-center text-error">32&deg;</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-5 col-sm-5 tiles grey">
                  <div class=" p-t-25 p-r-10 p-l-10 p-b-15">
                    <div class="p-b-10 m-b-10 b-grey b-b">
                      <div class="pull-left"> <span class="bold text-black m-r-15 text-right">Sun</span>
                        <canvas id="white_widget_06" width="20" height="20" class="inline m-l-10"></canvas>
                      </div>
                      <div class="pull-right"> <span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span> </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="p-b-10 m-b-10 b-grey b-b">
                      <div class="pull-left"> <span class="bold  text-black m-r-15">Mon</span>
                        <canvas id="white_widget_07" width="20" height="20" class="inline m-l-10"></canvas>
                      </div>
                      <div class="pull-right"> <span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span> </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="p-b-10 m-b-10 b-grey b-b">
                      <div class="pull-left"> <span class="bold  text-black m-r-15">Tue</span>
                        <canvas id="white_widget_08" width="20" height="20" class="inline m-l-10"></canvas>
                      </div>
                      <div class="pull-right"> <span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span> </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="p-b-10 m-b-10 b-grey b-b">
                      <div class="pull-left"> <span class="bold  text-black m-r-5">Wed</span>
                        <canvas id="white_widget_09" width="20" height="20" class="inline m-l-10"></canvas>
                      </div>
                      <div class="pull-right"> <span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span> </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="p-b-10 m-b-10 b-grey b-b">
                      <div class="pull-left"> <span class="bold  text-black m-r-5">Thur</span>
                        <canvas id="white_widget_10" width="20" height="20" class="inline m-l-10"></canvas>
                      </div>
                      <div class="pull-right"> <span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span> </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="p-b-10 m-b-10 b-grey b-b">
                      <div class="pull-left"> <span class="bold  text-black m-r-15">Fri</span>
                        <canvas id="white_widget_11" width="20" height="20" class="inline m-l-10"></canvas>
                      </div>
                      <div class="pull-right"> <span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span> </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="p-b-10 m-b-10 b-grey">
                      <div class="pull-left"> <span class="bold  text-black m-r-10">Sat</span>
                        <canvas id="white_widget_12" width="20" height="20" class="inline m-l-10"></canvas>
                      </div>
                      <div class="pull-right"> <span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span> </div>
                      <div class="clearfix"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row tiles-container  m-b-10 visible-xlg">
                <div class="col-md-12">
                  <div class="m-l-10 ">
                    <div class="tiles grey p-t-15 p-b-15 p-l-25 ">
                      <h5 class="text-black semi-bold">MOST POPULAR</h5>
                    </div>
                    <div class="tiles white ">
                      <div class="p-t-20 p-b-15 b-b b-grey">
                        <div class="post overlap-left-10">
                          <div class="user-profile-pic-wrapper">
                            <div class="user-profile-pic-2x white-border">
                              <img width="45" height="45" src="assets/img/profiles/avatar.jpg" data-src="assets/img/profiles/avatar.jpg" data-src-retina="assets/img/profiles/avatar2x.jpg" alt="">
                            </div>
                          </div>
                          <div class="info-wrapper small-width inline">
                            <div class="info text-black ">
                              <p>Jane Smith Commented on webarch new year bundle “Would you like to display collections on your...” </p>
                              <p class="muted small-text"> 2 mins ago </p>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                          <div class="inline pull-right">
                            <div class="tiles text-white p-t-5 p-l-5 p-b-5 p-r-5 inline"> <i class="fa fa-heart-o fa-lg"></i> </div>
                            <div class="tiles white p-t-5 p-l-5 p-b-5 p-r-5 inline"> <i class="fa fa-comment-o fa-lg"></i> </div>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      </div>
                      <div class="p-t-20 p-b-15 b-b b-grey">
                        <div class="post overlap-left-10">
                          <div class="user-profile-pic-wrapper">
                            <div class="user-profile-pic-2x tiles blue white-border">
                              <div class="text-white inherit-size p-t-10 p-l-15"> <i class="fa fa-map-marker fa-lg"></i> </div>
                            </div>
                          </div>
                          <div class="info-wrapper small-width">
                            <div class="info text-black ">
                              <p>You’ve got 302 Followers in 59 Diffrent places. region legally identified as a distinct entity in ....” </p>
                              <p class="muted small-text"> 2 mins ago </p>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                          <div class="inline pull-right">
                            <div class="tiles text-white p-t-5 p-l-5 p-b-5 p-r-5 inline"> <i class="fa fa-heart-o fa-lg"></i> </div>
                            <div class="tiles white p-t-5 p-l-5 p-b-5 p-r-5 inline"> <i class="fa fa-comment-o fa-lg"></i> </div>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      </div>
                      <div class="p-t-20 p-b-15 ">
                        <div class="post overlap-left-10">
                          <div class="user-profile-pic-wrapper">
                            <div class="user-profile-pic-2x tiles grey white-border">
                              <div class="text-grey inherit-size p-t-10 p-l-10"> <i class="fa fa-clock-o fa-lg"></i> </div>
                            </div>
                          </div>
                          <div class="info-wrapper small-width">
                            <div class="info text-black ">
                              <p>Jane Smith Commented on webarch new year bundle “Would you like to display collections on your...” </p>
                              <p class="muted small-text"> 2 mins ago </p>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                          <div class="inline pull-right">
                            <div class="tiles text-white p-t-5 p-l-5 p-b-5 p-r-5 inline"> <i class="fa fa-heart-o fa-lg"></i> </div>
                            <div class="tiles white p-t-5 p-l-5 p-b-5 p-r-5 inline"> <i class="fa fa-comment-o fa-lg"></i> </div>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      </div>
                    </div>
                    <div class="tiles grey p-t-5 p-b-5 ">
                      <p class="text-center"> <a href="javascript:;" class="text-black semi-bold  small-text">Load More</a></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row tiles-container  m-b-10 visible-xlg">
                <div class="col-md-12">
                  <div class="tiles white p-t-20 p-l-15 p-r-15 p-b-30">
                    <h2 class="text-center">Sign up, it's <span class="semi-bold text-success">free</span></h2>
                    <h4 class="text-center muted m-l-10 m-r-10">The talent of success is nothing more than doing 
              what you can do, well. </h4>
                    <form id="frm_signup_form" class="m-t-30 m-l-15 m-r-15">
                      <div class="form-group">
                        <label class="form-label">Email address</label>
                        <div class="controls">
                          <input type="text" class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="controls">
                          <input type="text" class="form-control" placeholder="Min. 8 characters">
                        </div>
                        <div class="controls">
                          <input type="text" class="form-control" placeholder="Confirm password">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label">Date of birth</label>
                            <div class="controls">
                              <input type="text" class="form-control ">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label">Gender</label>
                            <div class="controls">
                              <div class="radio">
                                <input id="male" type="radio" name="gender" value="male" checked="checked">
                                <label for="male">Male</label>
                                <input id="female" type="radio" name="gender" value="female">
                                <label for="female">Female</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="checkbox checkbox check-success 	">
                        <input type="checkbox" value="1" id="chkTerms">
                        <label for="chkTerms">I Here by agree on the Term and condition. </label>
                      </div>
                      <button class="btn btn-block btn-primary m-t-10 " type="button"><i class="icon-cloud-download"></i>&nbsp;&nbsp;Sign Up</button>
                    </form>
                  </div>
                </div>
              </div>
              <div class="row m-b-10 tiles-container visible-xlg">
                <div class="col-md-12">
                  <!-- BEGIN SALES WIDGET WITH FLOT CHART -->
                  <div class="tiles white add-margin">
                    <div class="p-t-20 p-l-20 p-r-20 p-b-20">
                      <div class="row b-grey b-b xs-p-b-20">
                        <div class="col-md-4">
                          <h4 class="text-black semi-bold">Total Income</h4>
                          <h3 class="text-success semi-bold">$<span data-animation-duration="600" data-value="15458" class="animate-number"></span></h3>
                        </div>
                        <div class="col-md-3">
                          <div class="m-t-20">
                            <h5 class="text-black semi-bold">Total due</h5>
                            <h4 class="text-success semi-bold">$<span data-animation-duration="600" data-value="4569" class="animate-number"></span></h4>
                          </div>
                        </div>
                        <div class="col-md-5 xs-m-b-20">
                          <div class="m-t-20">
                            <input type="text" class="dark form-control" id="txtinput3" placeholder="Search">
                          </div>
                        </div>
                      </div>
                      <div class="row b-grey">
                        <div class="col-md-3">
                          <div class="m-t-10">
                            <p class="text-success">Open</p>
                            <p class="text-black">16:203.26</p>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="m-t-10">
                            <p class="text-success">Day Range</p>
                            <p class="text-black">01.12.13 - 01.01.14</p>
                          </div>
                        </div>
                        <div class="col-md-5">
                          <div class="m-t-10">
                            <div class="pull-left"> Cash </div>
                            <div class="pull-right"> <span class="text-success">$10,525</span> </div>
                            <div class="clearfix"></div>
                            <div class="pull-left"> Visa Classic </div>
                            <div class="pull-right"> <span class="text-success">$5,989</span> </div>
                            <div class="clearfix"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tiles grey" id="chart3" style="height: 260px;width:100%"> </div>
                  </div>
                  <!-- END SALES WIDGET WITH FLOT CHART -->
                </div>
              </div>
            </div>
            <!-- END ONLY VISIBLE AT 1800 -->
            <!-- START ONLY VISIBLE AT 1600 BELOW -->
            <div class="col-md-6 hidden-xlg ">
              <div class="row tiles-container tiles white m-b-10 ">
                <div class="col-md-7  col-sm-7 b-grey b-r ">
                  <h4 class="semi-bold text-center b-grey b-b no-margin p-t-20 p-b-10">California USA</h4>
                  <div class="b-grey b-b">
                    <h4 class="semi-bold text-center text-error">Sunday</h4>
                    <h1 class="semi-bold text-center text-error"> 32&deg; </h1>
                    <h5 class="text-center text-error">partly cloudy</h5>
                    <div class="row auto m-t-10 m-b-10">
                      <div class="col-md-3 col-sm-3 col-xs-3  no-padding col-md-offset-2 col-sm-offset-2 col-xs-offset-2">
                        <canvas id="white_widget2_cloudy_big" width="48" height="48" class="h-align-middle "></canvas>
                      </div>
                      <div class="col-md-5 col-sm-5 col-xs-5 no-padding">
                        <div class="m-t-10">
                          <div class="pull-left m-l-5">
                            <canvas id="white_widget2_13" width="16" height="16" class="inline"></canvas>
                            <div class="inline">
                              <h5 class="semi-bold no-margin ">54</h5>
                              <p class="bold text-extra-small ">MPH</p>
                            </div>
                          </div>
                          <div class="pull-right m-r-10">
                            <canvas id="white_widget2_14" width="16" height="16" class="inline"></canvas>
                            <div class="inline">
                              <h5 class="semi-bold no-margin ">53</h5>
                              <p class="bold text-extra-small ">MM</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row auto m-t-15">
                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding col-md-offset-1 col-xs-offset-1 b-grey b-r">
                      <p class="text-center no-margin">11:30</p>
                      <p class="text-center no-margin">PM</p>
                      <canvas id="white_widget2_01" width="20" height="20" class="h-align-middle m-t-10"></canvas>
                      <h5 class="semi-bold text-center text-error">32&deg;</h5>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding b-grey b-r">
                      <div class="text-center">11:30</div>
                      <div class="text-center">PM</div>
                      <canvas id="white_widget2_02" width="20" height="20" class="h-align-middle m-t-10"></canvas>
                      <h5 class="semi-bold text-center text-error">32&deg;</h5>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding b-grey b-r">
                      <div class="text-center">11:30</div>
                      <div class="text-center">PM</div>
                      <canvas id="white_widget2_03" width="20" height="20" class="h-align-middle m-t-10"></canvas>
                      <h5 class="semi-bold text-center text-error">32&deg;</h5>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding b-grey b-r">
                      <div class="text-center">11:30</div>
                      <div class="text-center">PM</div>
                      <canvas id="white_widget2_04" width="20" height="20" class="h-align-middle m-t-10"></canvas>
                      <h5 class="semi-bold text-center text-error">32&deg;</h5>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding b-grey">
                      <div class="text-center">11:30</div>
                      <div class="text-center">PM</div>
                      <canvas id="white_widget2_05" width="20" height="20" class="h-align-middle m-t-10"></canvas>
                      <h5 class="semi-bold text-center text-error">32&deg;</h5>
                    </div>
                  </div>
                </div>
                <div class="col-md-5 col-sm-5 tiles grey">
                  <div class=" p-t-25 p-r-10 p-l-10 p-b-15">
                    <div class="p-b-10 m-b-10 b-grey b-b">
                      <div class="pull-left"> <span class="bold text-black m-r-15 text-right">Sun</span>
                        <canvas id="white_widget2_06" width="20" height="20" class="inline m-l-10"></canvas>
                      </div>
                      <div class="pull-right"> <span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span> </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="p-b-10 m-b-10 b-grey b-b">
                      <div class="pull-left"> <span class="bold  text-black m-r-15">Mon</span>
                        <canvas id="white_widget2_07" width="20" height="20" class="inline m-l-10"></canvas>
                      </div>
                      <div class="pull-right"> <span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span> </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="p-b-10 m-b-10 b-grey b-b">
                      <div class="pull-left"> <span class="bold  text-black m-r-15">Tue</span>
                        <canvas id="white_widget2_08" width="20" height="20" class="inline m-l-10"></canvas>
                      </div>
                      <div class="pull-right"> <span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span> </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="p-b-10 m-b-10 b-grey b-b">
                      <div class="pull-left"> <span class="bold  text-black m-r-5">Wed</span>
                        <canvas id="white_widget2_09" width="20" height="20" class="inline m-l-10"></canvas>
                      </div>
                      <div class="pull-right"> <span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span> </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="p-b-10 m-b-10 b-grey b-b">
                      <div class="pull-left"> <span class="bold  text-black m-r-5">Thur</span>
                        <canvas id="white_widget2_10" width="20" height="20" class="inline m-l-10"></canvas>
                      </div>
                      <div class="pull-right"> <span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span> </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="p-b-10 m-b-10 b-grey b-b">
                      <div class="pull-left"> <span class="bold  text-black m-r-15">Fri</span>
                        <canvas id="white_widget2_11" width="20" height="20" class="inline m-l-10"></canvas>
                      </div>
                      <div class="pull-right"> <span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span> </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="p-b-10 m-b-10 b-grey">
                      <div class="pull-left"> <span class="bold  text-black m-r-10">Sat</span>
                        <canvas id="white_widget2_12" width="20" height="20" class="inline m-l-10"></canvas>
                      </div>
                      <div class="pull-right"> <span class="semi-bold text-grey">32 - 28</span> <span class="bold text-error">C&deg; </span> </div>
                      <div class="clearfix"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row m-b-10 tiles-container ">
                <div class="col-md-12">
                  <!-- BEGIN SALES WIDGET WITH FLOT CHART -->
                  <div class="tiles white add-margin">
                    <div class="p-t-20 p-l-20 p-r-20 p-b-20">
                      <div class="row b-grey b-b">
                        <div class="col-md-4 col-sm-4">
                          <h4 class="text-black semi-bold">Total Income</h4>
                          <h3 class="text-success semi-bold">$<span data-animation-duration="600" data-value="15458" class="animate-number"></span></h3>
                        </div>
                        <div class="col-md-3 col-sm-3">
                          <div class="m-t-20">
                            <h5 class="text-black semi-bold">Total due</h5>
                            <h4 class="text-success semi-bold">$<span data-animation-duration="600" data-value="4569" class="animate-number"></span></h4>
                          </div>
                        </div>
                        <div class="col-md-5 col-sm-5 xs-m-b-20">
                          <div class="m-t-20">
                            <input type="text" class="dark form-control" id="txtinput34" placeholder="Search">
                          </div>
                        </div>
                      </div>
                      <div class="row b-grey">
                        <div class="col-md-3 col-sm-3">
                          <div class="m-t-10">
                            <p class="text-success">Open</p>
                            <p class="text-black">16:203.26</p>
                          </div>
                        </div>
                        <div class="col-md-4 col-sm-4 ">
                          <div class="m-t-10">
                            <p class="text-success">Day Range</p>
                            <p class="text-black">01.12.13 - 01.01.14</p>
                          </div>
                        </div>
                        <div class="col-md-5 col-sm-5">
                          <div class="m-t-10">
                            <div class="pull-left"> Cash </div>
                            <div class="pull-right"> <span class="text-success">$10,525</span> </div>
                            <div class="clearfix"></div>
                            <div class="pull-left"> Visa Classic </div>
                            <div class="pull-right"> <span class="text-success">$5,989</span> </div>
                            <div class="clearfix"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tiles grey" id="sales_chart_alt" style="height: 260px; width:100%"> </div>
                  </div>
                  <!-- END SALES WIDGET WITH FLOT CHART -->
                </div>
              </div>
            </div>
            <div class="col-md-6 hidden-xlg">
              <div class="row tiles-container  m-b-10 hidden-xlg">
                <div class="col-md-12">
                  <div class="m-l-10 ">
                    <div class="tiles grey p-t-15 p-b-15 p-l-25 ">
                      <h5 class="text-black semi-bold">MOST POPULAR</h5>
                    </div>
                    <div class="tiles white ">
                      <div class="p-t-20 p-b-15 b-b b-grey">
                        <div class="post overlap-left-10">
                          <div class="user-profile-pic-wrapper">
                            <div class="user-profile-pic-2x white-border">
                              <img width="45" height="45" src="assets/img/profiles/avatar.jpg" alt="" data-src="assets/img/profiles/avatar.jpg" data-src-retina="assets/img/profiles/avatar2x.jpg">
                            </div>
                          </div>
                          <div class="info-wrapper small-width inline">
                            <div class="info text-black ">
                              <p>Jane Smith Commented on webarch new year bundle “Would you like to display collections on your...” </p>
                              <p class="muted small-text"> 2 mins ago </p>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                          <div class="inline pull-right">
                            <div class="tiles text-white p-t-5 p-l-5 p-b-5 p-r-5 inline"> <i class="fa fa-heart-o fa-lg"></i> </div>
                            <div class="tiles white p-t-5 p-l-5 p-b-5 p-r-5 inline"> <i class="fa fa-comment-o fa-lg"></i> </div>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      </div>
                      <div class="p-t-20 p-b-15 b-b b-grey">
                        <div class="post overlap-left-10">
                          <div class="user-profile-pic-wrapper">
                            <div class="user-profile-pic-2x tiles blue white-border">
                              <div class="text-white inherit-size p-t-10 p-l-15"> <i class="fa fa-map-marker fa-lg"></i> </div>
                            </div>
                          </div>
                          <div class="info-wrapper small-width">
                            <div class="info text-black ">
                              <p>You’ve got 302 Followers in 59 Diffrent places. region legally identified as a distinct entity in ....” </p>
                              <p class="muted small-text"> 2 mins ago </p>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                          <div class="inline pull-right">
                            <div class="tiles text-white p-t-5 p-l-5 p-b-5 p-r-5 inline"> <i class="fa fa-heart-o fa-lg"></i> </div>
                            <div class="tiles white p-t-5 p-l-5 p-b-5 p-r-5 inline"> <i class="fa fa-comment-o fa-lg"></i> </div>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      </div>
                      <div class="p-t-20 p-b-15 ">
                        <div class="post overlap-left-10">
                          <div class="user-profile-pic-wrapper">
                            <div class="user-profile-pic-2x tiles grey white-border">
                              <div class="text-grey inherit-size p-t-10 p-l-10"> <i class="fa fa-clock-o fa-lg"></i> </div>
                            </div>
                          </div>
                          <div class="info-wrapper small-width">
                            <div class="info text-black ">
                              <p>Jane Smith Commented on webarch new year bundle “Would you like to display collections on your...” </p>
                              <p class="muted small-text"> 2 mins ago </p>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                          <div class="inline pull-right">
                            <div class="tiles text-white p-t-5 p-l-5 p-b-5 p-r-5 inline"> <i class="fa fa-heart-o fa-lg"></i> </div>
                            <div class="tiles white p-t-5 p-l-5 p-b-5 p-r-5 inline"> <i class="fa fa-comment-o fa-lg"></i> </div>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      </div>
                    </div>
                    <div class="tiles grey p-t-5 p-b-5 ">
                      <p class="text-center"> <a href="javascript:;" class="text-black semi-bold  small-text">Load More</a></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row tiles-container  m-b-10 ">
                <div class="col-md-12">
                  <div class="tiles white p-t-15 p-l-15 p-r-15 p-b-25">
                    <h2 class="text-center">Sign up, it's <span class="semi-bold text-success">free</span></h2>
                    <h4 class="text-center muted m-l-10 m-r-10">The talent of success is nothing more than doing 
              what you can do, well. </h4>
                    <form id="frm_signup_form_2" class="m-t-30 m-l-15 m-r-15">
                      <div class="form-group">
                        <label class="form-label">Email address</label>
                        <div class="controls">
                          <input type="text" class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="controls">
                          <input type="password" class="form-control" placeholder="Min. 8 characters">
                        </div>
                        <div class="controls">
                          <input type="password" class="form-control" placeholder="Confirm password">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label">Date of birth</label>
                            <div class="controls">
                              <input type="text" class="form-control ">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label class="form-label">Gender</label>
                            <div class="controls">
                              <div class="radio">
                                <input id="genderm" type="radio" name="gender" value="male" checked="checked">
                                <label for="genderm">Male</label>
                                <input id="genderf" type="radio" name="gender" value="female">
                                <label for="genderf">Female</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="checkbox checkbox check-success 	">
                        <input type="checkbox" value="1" id="chkTerms1">
                        <label for="chkTerms1">I Here by agree on the Term and condition. </label>
                      </div>
                      <button class="btn btn-block btn-primary m-t-10 " type="button"><i class="icon-cloud-download"></i>&nbsp;&nbsp;Iconic Button Block</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <!-- END ONLY VISIBLE AT 1600 BELOW -->
            <div class="col-md-8 col-vlg-8 visible-xlg">
              <div class="tiles white m-b-10 clearfix">
                <div class="col-md-4 no-padding">
                  <div id="world-map" style="height:450px"></div>
                </div>
                <div class="col-md-8 p-t-55 p-r-20 p-b-20">
                  <div class="col-md-6">
                    <div class="row b-b b-grey p-b-10">
                      <div class="col-md-6">
                        <p class="bold small-text">USERS</p>
                        <h3 class="bold text-success">2,455,559</h3>
                      </div>
                      <div class="col-md-6">
                        <p class="bold small-text">NEW USERS</p>
                        <h3 class="bold text-black">548</h3>
                      </div>
                    </div>
                    <div class="row m-t-15">
                      <div class="col-md-6">
                        <p class="bold text-black">Finland</p>
                      </div>
                      <div class="col-md-6">
                        <p class="text-black">245 <i class="fa fa-sort-asc fa-lg text-error " style="vertical-align: super;"></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10">
                      <div class="col-md-6">
                        <p class="bold text-black">France</p>
                      </div>
                      <div class="col-md-6">
                        <p class="text-black">599 <i class="fa fa-sort-desc fa-lg text-success "></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10">
                      <div class="col-md-6">
                        <p class="bold text-black">United Kingdom</p>
                      </div>
                      <div class="col-md-6">
                        <p class="text-black">800 <i class="fa fa-sort-desc fa-lg text-success "></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10">
                      <div class="col-md-6">
                        <p class="bold text-black">Italy</p>
                      </div>
                      <div class="col-md-6">
                        <p class="text-black">450 <i class="fa fa-sort-asc fa-lg text-black " style="vertical-align: super;"></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10">
                      <div class="col-md-6">
                        <p class="bold text-black">Canada</p>
                      </div>
                      <div class="col-md-6">
                        <p class="text-error">155 <i class="fa fa-sort-asc fa-lg text-error " style="vertical-align: super;"></i></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="row b-b b-grey p-b-10">
                      <div class="col-md-6">
                        <p class="bold small-text">SESSIONS</p>
                        <h3 class="bold text-success">549</h3>
                      </div>
                      <div class="col-md-6">
                        <p class="bold small-text">AVG.SESSIONS</p>
                        <h3 class="bold text-black">15.58%</h3>
                      </div>
                    </div>
                    <div class="row m-t-15">
                      <div class="col-md-6">
                        <p class="bold text-black">Finland</p>
                      </div>
                      <div class="col-md-6">
                        <p class="text-black">245 <i class="fa fa-sort-asc fa-lg text-error " style="vertical-align: super;"></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10">
                      <div class="col-md-6">
                        <p class="bold text-black">France</p>
                      </div>
                      <div class="col-md-6">
                        <p class="text-black">599 <i class="fa fa-sort-desc fa-lg text-success "></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10">
                      <div class="col-md-6">
                        <p class="bold text-black">United Kingdom</p>
                      </div>
                      <div class="col-md-6">
                        <p class="text-black">800 <i class="fa fa-sort-desc fa-lg text-success "></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10">
                      <div class="col-md-6">
                        <p class="bold text-black">Italy</p>
                      </div>
                      <div class="col-md-6">
                        <p class="text-black">450 <i class="fa fa-sort-asc fa-lg text-black " style="vertical-align: super;"></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10">
                      <div class="col-md-6">
                        <p class="bold text-black">Canada</p>
                      </div>
                      <div class="col-md-6">
                        <p class="text-error">155 <i class="fa fa-sort-asc fa-lg text-error " style="vertical-align: super;"></i></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
 
              <div class="row tiles-container m-b-10 tiles grey">
                <div class="col-md-12">
                  <div class="tiles white col-md-8 col-sm-8 no-padding">
                    <div class="tiles-body">
                      <div class="row">
                        <div class="col-md-6 col-sm-6">
                          <div class="mini-chart-wrapper">
                            <div class="chart-details-wrapper">
                              <div class="chartname"> New Orders </div>
                              <div class="chart-value"> 17,555 </div>
                            </div>
                            <div class="mini-chart">
                              <div id="mini-chart-orders"></div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                          <div class="mini-chart-wrapper">
                            <div class="chart-details-wrapper">
                              <div class="chartname"> My Balance </div>
                              <div class="chart-value"> $17,555 </div>
                            </div>
                            <div class="mini-chart">
                              <div id="mini-chart-other"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br>
                    <div id="ricksaw" class="ricksaw"></div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="col-md-4 col-sm-4 no-padding">
                    <div class="tiles grey ">
                      <div class="tiles white no-margin">
                        <div class="tiles-body">
                          <div class="tiles-title blend"> OVERALL VIEWS </div>
                          <div class="heading"> <span data-animation-duration="1000" data-value="432852" class="animate-number">0</span> </div>
                          44% higher <span class="blend">than last month</span> </div>
                      </div>
                      <div id="legend"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row ">
                <div class="col-md-4 col-sm-6">
                  <div class="row ">
                    <!-- BEGIN BLOG POST SIMPLE-->
                    <div class="col-md-12 m-b-10">
                      <div class="widget-item ">
                        <div class="controller overlay right">
                          <a href="javascript:;" class="reload"></a>
                          <a href="javascript:;" class="remove"></a>
                        </div>
                        <div class="tiles green " style="max-height:345px">
                          <div class="tiles-body">
                            <h3 class="text-white m-t-50 m-b-30 m-r-20"> Webarch <span class="semi-bold">UI Bundle
                        highly customizable UI
                        elements</span> </h3>
                            <div class="overlayer bottom-right fullwidth">
                              <div class="overlayer-wrapper">
                                <div class=" p-l-20 p-r-20 p-b-20 p-t-20">
                                  <div class="pull-right"> <a href="#" class="hashtags transparent"> #Art Design </a> </div>
                                  <div class="clearfix"></div>
                                </div>
                              </div>
                            </div>
                            <br>
                          </div>
                        </div>
                        <div class="tiles white ">
                          <div class="tiles-body">
                            <div class="row">
                              <div class="user-comment-wrapper pull-left">
                                <div class="profile-wrapper"> <img src="assets/img/profiles/avatar_small.jpg" alt="" data-src="assets/img/profiles/avatar_small.jpg" data-src-retina="assets/img/profiles/avatar_small2x.jpg" width="35" height="35"> </div>
                                <div class="comment">
                                  <div class="user-name text-black bold"> David <span class="semi-bold">Cooper</span> </div>
                                  <div class="preview-wrapper">@ revox </div>
                                </div>
                                <div class="clearfix"></div>
                              </div>
                              <div class="pull-right m-r-20"> <span class="bold text-black small-text">24m</span> </div>
                              <div class="clearfix"></div>
                              <div class="p-l-15 p-t-10 p-r-20">
                                <p>The attention to detail and the end product is stellar! I enjoyed the process </p>
                                <div class="post p-t-10 p-b-10">
                                  <ul class="action-bar no-margin p-b-20 ">
                                    <li><a href="#" class="muted bold"><i class="fa fa-comment  m-r-10"></i>1584</a> </li>
                                    <li><a href="#" class="text-error bold"><i class="fa fa-heart  m-r-10"></i>47k</a> </li>
                                  </ul>
                                  <div class="clearfix"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- END BLOG POST SIMPLE-->
                  </div>
                  <div class="row">
                    <!-- BEGIN BLOG POST WITH CAROUSEL IMAGE -->
                    <div class="col-md-12 m-b-10">
                      <div class="widget-item ">
                        <div class="controller overlay right">
                          <a href="javascript:;" class="reload"></a>
                          <a href="javascript:;" class="remove"></a>
                        </div>
                        <div class="tiles white p-t-15">
                          <div class="row">
                            <div class="col-md-2">
                              <div class="profile-img-wrapper pull-left m-l-10">
                                <div class=" p-r-10"> <img src="assets/img/profiles/c.jpg" alt="" data-src="assets/img/profiles/c.jpg" data-src-retina="assets/img/profiles/c2x.jpg" width="35" height="35"> </div>
                              </div>
                            </div>
                            <div class="col-md-10">
                              <div class="user-name text-black bold large-text"> John <span class="semi-bold">Smith</span> </div>
                              <div class="preview-wrapper">shared a picture with <span class="bold">Jane Smith</span>.</div>
                            </div>
                          </div>
                          <div id="image-demo-carl-2" class="m-t-15 owl-carousel owl-theme">
                            <div class="item"><img src="assets/img/others/1.jpg" alt=""></div>
                            <div class="item"><img src="assets/img/others/2.jpg" alt=""></div>
                          </div>
                          <div class="post p-b-15 p-t-15 p-l-15 b-b b-grey">
                            <ul class="action-bar no-margin ">
                              <li><a href="#" class="muted"><i class="fa fa-comment m-r-5"></i> 24</a> </li>
                              <li>
                                <a href="#" class="text-error bold"> <i class="fa fa-heart-o  m-r-5"></i> 5</a>
                              </li>
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <p class="p-t-10 p-b-10 p-l-15 p-r-15"><span class="bold">Jane Smith, John Smith, David Jester, pepper</span> post and 214 others like this.</p>
                          <div class="clearfix"></div>
                          <div class="p-b-10 p-l-10 p-r-10">
                            <div class="profile-img-wrapper pull-left"> <img src="assets/img/profiles/avatar_small.jpg" alt="" data-src="assets/img/profiles/avatar_small.jpg" data-src-retina="assets/img/profiles/avatar_small2x.jpg" width="35" height="35"> </div>
                            <div class="inline pull-right" style="width:86%">
                              <div class="input-group transparent ">
                                <input type="text" class="form-control" placeholder="Write a comment">
                                <span class="input-group-addon"> <i class="fa fa-camera"></i> </span> </div>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- END BLOG POST WITH CAROUSEL IMAGE -->
                  </div>
                </div>
                <div class="col-md-4 col-sm-6 hidden-sm">
                  <div class="row">
                    <!-- BEGIN BLOG POST WITH IMAGE -->
                    <div class="col-md-12 m-b-10">
                      <div class="widget-item ">
                        <div class="controller overlay right">
                          <a href="javascript:;" class="reload"></a>
                          <a href="javascript:;" class="remove"></a>
                        </div>
                        <div class="tiles green  overflow-hidden full-height" style="max-height:214px">
                          <div class="overlayer bottom-right fullwidth">
                            <div class="overlayer-wrapper">
                              <div class="tiles gradient-black p-l-20 p-r-20 p-b-20 p-t-20">
                                <div class="pull-right"> <a href="#" class="hashtags transparent"> #Art Design </a> </div>
                                <div class="clearfix"></div>
                              </div>
                            </div>
                          </div>
                          <img src="assets/img/others/10.png" alt="" class="lazy hover-effect-img image-responsive-width"> </div>
                        <div class="tiles white ">
                          <div class="tiles-body">
                            <div class="row">
                              <div class="user-profile-pic text-left"> <img width="69" height="69" data-src-retina="assets/img/profiles/avatar2x.jpg" data-src="assets/img/profiles/avatar.jpg" src="assets/img/profiles/avatar.jpg" alt="">
                                <div class="pull-right m-r-20 m-t-35"> <span class="bold text-black small-text">24m</span> </div>
                              </div>
                              <div class="col-md-5 no-padding">
                                <div class="user-comment-wrapper">
                                  <div class="comment">
                                    <div class="user-name text-black bold"> David <span class="semi-bold">Jester</span> </div>
                                    <div class="preview-wrapper">@ revox </div>
                                  </div>
                                  <div class="clearfix"></div>
                                </div>
                              </div>
                              <div class="col-md-7 no-padding">
                                <div class="clearfix"></div>
                                <div class="m-r-20 m-t-20 m-b-10  m-l-10">
                                  <p class="p-b-10">The attention to detail and the end product is stellar! I enjoyed the process </p>
                                  <a href="#" class="hashtags m-b-5"> #new york city </a> <a href="#" class="hashtags m-b-5"> #amazing </a> <a href="#" class="hashtags m-b-5"> #citymax </a> </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- END BLOG POST WITH IMAGE -->
                  </div>
                  <div class="row">
                    <!-- BEGIN BLOG POST SIMPLE -->
                    <div class="col-md-12 m-b-10">
                      <div class="widget-item ">
                        <div class="controller overlay right">
                          <a href="javascript:;" class="reload"></a>
                          <a href="javascript:;" class="remove"></a>
                        </div>
                        <div class="tiles purple " style="max-height:345px">
                          <div class="tiles-body">
                            <h3 class="text-white m-t-50 m-b-30 m-r-20"> Webarch <span class="semi-bold">UI Bundle
                        highly customizable UI
                        elements</span> </h3>
                            <div class="overlayer bottom-right fullwidth">
                              <div class="overlayer-wrapper">
                                <div class=" p-l-20 p-r-20 p-b-20 p-t-20">
                                  <div class="pull-right"> <a href="#" class="hashtags transparent"> #Art Design </a> </div>
                                  <div class="clearfix"></div>
                                </div>
                              </div>
                            </div>
                            <br>
                          </div>
                        </div>
                        <div class="tiles white ">
                          <div class="tiles-body">
                            <div class="row">
                              <div class="user-comment-wrapper pull-left">
                                <div class="profile-wrapper"> <img src="assets/img/profiles/d.jpg" alt="" data-src="assets/img/profiles/d.jpg" data-src-retina="assets/img/profiles/d2x.jpg" width="35" height="35"> </div>
                                <div class="comment">
                                  <div class="user-name text-black bold"> Jane <span class="semi-bold">Smith</span> </div>
                                  <div class="preview-wrapper">@ webarch </div>
                                </div>
                                <div class="clearfix"></div>
                              </div>
                              <div class="pull-right m-r-20"> <span class="bold text-black small-text">24m</span> </div>
                              <div class="clearfix"></div>
                              <div class="p-l-15 p-t-10 p-r-20">
                                <p>The attention to detail and the end product is stellar! I enjoyed the process </p>
                                <div class="post p-t-10 p-b-10">
                                  <ul class="action-bar no-margin p-b-20 ">
                                    <li><a href="#" class="muted bold"><i class="fa fa-comment  m-r-10"></i>1584</a> </li>
                                    <li><a href="#" class="text-error bold"><i class="fa fa-heart  m-r-10"></i>47k</a> </li>
                                  </ul>
                                  <div class="clearfix"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- END BLOG POST SIMPLE -->
                  </div>
                </div>
                <div class="col-md-4 col-sm-6">
                  <div class="row">
                    <!-- BEGIN BLOG POST WITH MAP -->
                    <div class="col-md-12 m-b-10">
                      <div class="widget-item ">
                        <div class="controller overlay right">
                          <a href="javascript:;" class="reload"></a>
                          <a href="javascript:;" class="remove"></a>
                        </div>
                        <div class="tiles white p-t-15">
                          <div class="row">
                            <div class="col-md-2">
                              <div class="profile-img-wrapper pull-left m-l-10">
                                <div class=" p-r-10"> <img src="assets/img/profiles/h.jpg" alt="" data-src="assets/img/profiles/h.jpg" data-src-retina="assets/img/profiles/h2x.jpg" width="35" height="35"> </div>
                              </div>
                            </div>
                            <div class="col-md-10">
                              <div class="user-name text-black bold large-text"> David <span class="semi-bold">Jester</span> </div>
                              <div class="preview-wrapper">was with <span class="bold">Jane Smith</span> and 4 others at <span class="bold">The Shore By O</span>.</div>
                            </div>
                          </div>
                          <div id="location-map-2" class="m-t-15 " style="height: 200px"> </div>
                          <div class="post p-b-15 p-t-15 p-l-15 b-b b-grey">
                            <ul class="action-bar no-margin ">
                              <li><a href="#" class="muted"><i class="fa fa-comment m-r-5"></i> 24</a> </li>
                              <li>
                                <a href="#" class="text-error bold"> <i class="fa fa-heart-o  m-r-5"></i> 5</a>
                              </li>
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <p class="p-t-10 p-b-10 p-l-15 p-r-15"><span class="bold">Jane Smith, John Smith, David Jester, pepper</span> post and 214 others like this.</p>
                          <div class="clearfix"></div>
                          <div class="p-b-10 p-l-10 p-r-10">
                            <div class="profile-img-wrapper pull-left"> <img width="35" height="35" alt="" src="assets/img/profiles/e.jpg" data-src="assets/img/profiles/e.jpg" data-src-retina="assets/img/profiles/e2x.jpg"> </div>
                            <div class="inline pull-right" style="width:86%">
                              <div class="input-group transparent ">
                                <input type="text" class="form-control" placeholder="Write a comment">
                                <span class="input-group-addon"> <i class="fa fa-camera"></i> </span> </div>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- END BLOG POST WITH MAP -->
                  </div>
                  <div class="row">
                    <!-- BEGIN BLOG POST WITH IMAGE -->
                    <div class="col-md-12 m-b-10">
                      <div class="widget-item ">
                        <div class="controller overlay right">
                          <a href="javascript:;" class="reload"></a>
                          <a href="javascript:;" class="remove"></a>
                        </div>
                        <div class="tiles green  overflow-hidden full-height" style="max-height:214px">
                          <div class="overlayer bottom-right fullwidth">
                            <div class="overlayer-wrapper">
                              <div class="tiles gradient-black p-l-20 p-r-20 p-b-20 p-t-20">
                                <div class="pull-right"> <a href="#" class="hashtags transparent"> #Art Design </a> </div>
                                <div class="clearfix"></div>
                              </div>
                            </div>
                          </div>
                          <img src="assets/img/others/10.png" alt="" class="lazy hover-effect-img image-responsive-width"> </div>
                        <div class="tiles white ">
                          <div class="tiles-body">
                            <div class="row">
                              <div class="user-profile-pic text-left"> <img width="69" height="69" data-src-retina="assets/img/profiles/avatar2x.jpg" data-src="assets/img/profiles/avatar.jpg" src="assets/img/profiles/avatar.jpg" alt="">
                                <div class="pull-right m-r-20 m-t-35"> <span class="bold text-black small-text">24m</span> </div>
                              </div>
                              <div class="col-md-5 no-padding">
                                <div class="user-comment-wrapper">
                                  <div class="comment">
                                    <div class="user-name text-black bold"> David <span class="semi-bold">Jester</span> </div>
                                    <div class="preview-wrapper">@ revox </div>
                                  </div>
                                  <div class="clearfix"></div>
                                </div>
                              </div>
                              <div class="col-md-7 no-padding">
                                <div class="clearfix"></div>
                                <div class="m-r-20 m-t-20 m-b-10  m-l-10">
                                  <p class="p-b-10">The attention to detail and the end product is stellar! I enjoyed the process </p>
                                  <a href="#" class="hashtags m-b-5"> #new york city </a> <a href="#" class="hashtags m-b-5"> #amazing </a> <a href="#" class="hashtags m-b-5"> #citymax </a> </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- BEGIN BLOG POST WITH IMAGE -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row hidden-xlg">
            <div class="col-md-12 ">
              <div class="tiles white m-b-10 clearfix">
                <div class="col-md-4 col-sm-12 no-padding">
                  <div id="world2" style="height:405px"></div>
                </div>
                <div class="col-md-8 p-t-35 p-r-20 p-b-30 col-sm-12">
                  <div class="col-md-6 col-sm-6 ">
                    <div class="row b-b b-grey p-b-10">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="bold small-text">USERS</p>
                        <h3 class="bold text-success">2,455,559</h3>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="bold small-text">NEW USERS</p>
                        <h3 class="bold text-black">548</h3>
                      </div>
                    </div>
                    <div class="row m-t-15">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="bold text-black">Finland</p>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="text-black">245 <i class="fa fa-sort-asc fa-lg text-error " style="vertical-align: super;"></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="bold text-black">France</p>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="text-black">599 <i class="fa fa-sort-desc fa-lg text-success "></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="bold text-black">United Kingdom</p>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="text-black">800 <i class="fa fa-sort-desc fa-lg text-success "></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="bold text-black">Italy</p>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="text-black">450 <i class="fa fa-sort-asc fa-lg text-black " style="vertical-align: super;"></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10 xs-m-b-20">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="bold text-black">Canada</p>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="text-error">155 <i class="fa fa-sort-asc fa-lg text-error " style="vertical-align: super;"></i></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-6">
                    <div class="row b-b b-grey p-b-10">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="bold small-text">SESSIONS</p>
                        <h3 class="bold text-success">549</h3>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="bold small-text">AVG.SESSIONS</p>
                        <h3 class="bold text-black">15.58%</h3>
                      </div>
                    </div>
                    <div class="row m-t-15">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="bold text-black">Finland</p>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="text-black">245 <i class="fa fa-sort-asc fa-lg text-error " style="vertical-align: super;"></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="bold text-black">France</p>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="text-black">599 <i class="fa fa-sort-desc fa-lg text-success "></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="bold text-black">United Kingdom</p>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="text-black">800 <i class="fa fa-sort-desc fa-lg text-success "></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="bold text-black">Italy</p>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="text-black">450 <i class="fa fa-sort-asc fa-lg text-black " style="vertical-align: super;"></i></p>
                      </div>
                    </div>
                    <div class="row m-t-10">
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="bold text-black">Canada</p>
                      </div>
                      <div class="col-md-6 col-sm-6 col-xs-6">
                        <p class="text-error">155 <i class="fa fa-sort-asc fa-lg text-error " style="vertical-align: super;"></i></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row ">
                <div class="col-md-4 m-b-10 ">
                  <div class="col-md-12">
                    <div class="row tiles-container">
                      <div class="col-md-6 col-sm-6 tiles dark-blue" style="height:200px">
                        <div class="overlayer top-right">
                          <div class="p-r-15 p-t-15"> <i class="fa fa-facebook text-white fa-4x"></i> </div>
                        </div>
                        <div class="overlayer bottom-left">
                          <div class="p-l-15 p-b-15">
                            <h2 class="text-white">Παλιός Νερόμυλος</h2>
                            <h4 class="text-white">Likes</h4>
                            <span class="text-white mini-description ">3% higher than last month</span> </div>
                        </div>
                      </div>
                      <div class="col-md-6 col-sm-6 tiles light-blue" style="height:200px">
                        <div class="overlayer top-right">
                          <div class="p-r-15 p-t-15"> <i class="fa fa-twitter text-white fa-4x"></i> </div>
                        </div>
                        <div class="overlayer bottom-left">
                          <div class="p-l-15 p-b-15">
                            <h2 class="text-white">14k</h2>
                            <h4 class="text-white">tweets</h4>
                            <span class="text-white mini-description ">2% higher than last month</span> </div>
                        </div>
                      </div>
                    </div>
                    <div class="row tiles-container">
                      <div class="col-md-6 col-sm-6 tiles red" style="height:200px">
                        <div class="overlayer top-right">
                          <div class="p-r-15 p-t-15"> <i class="fa fa-google-plus text-white fa-4x"></i> </div>
                        </div>
                        <div class="overlayer bottom-left">
                          <div class="p-l-15 p-b-15">
                            <h2 class="text-white">154</h2>
                            <h4 class="text-white">circles</h4>
                            <span class="text-white mini-description ">2% higher than last month</span> </div>
                        </div>
                      </div>
                      <div class="col-md-6 col-sm-6 tiles light-red" style="height:200px">
                        <div class="overlayer top-right">
                          <div class="p-r-15 p-t-15"> <i class="fa fa-dribbble text-white fa-4x"></i> </div>
                        </div>
                        <div class="overlayer bottom-left">
                          <div class="p-l-15 p-b-15">
                            <h2 class="text-white">1550</h2>
                            <h4 class="text-white">Subscribers</h4>
                            <span class="text-white mini-description ">2% higher than last month</span> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-8 ">
                  <div class="tiles white m-b-10">
                    <div class="tiles-body">
                      <div class="controller">
                        <a href="javascript:;" class="reload"></a>
                        <a href="javascript:;" class="remove"></a>
                      </div>
                      <div class="tiles-title"> NOTIFICATIONS </div>
                      <br>
                      <div class="notification-messages info">
                        <div class="user-profile"> <img src="assets/img/profiles/c.jpg" alt="" data-src="assets/img/profiles/c.jpg" data-src-retina="assets/img/profiles/c2x.jpg" width="35" height="35"> </div>
                        <div class="message-wrapper">
                          <div class="heading"> David Nester - Commented on your wall </div>
                          <div class="description"> Meeting postponed to tomorrow </div>
                        </div>
                        <div class="date pull-right"> Just now </div>
                        <div class="clearfix"></div>
                      </div>
                      <div class="notification-messages danger">
                        <div class="iconholder"> <i class="icon-warning-sign"></i> </div>
                        <div class="message-wrapper">
                          <div class="heading"> Server load limited </div>
                          <div class="description"> Database server has reached its daily capicity </div>
                        </div>
                        <div class="date pull-right"> Yesterday </div>
                        <div class="clearfix"></div>
                      </div>
                      <div class="notification-messages success">
                        <div class="user-profile"> <img src="assets/img/profiles/h.jpg" alt="" data-src="assets/img/profiles/h.jpg" data-src-retina="assets/img/profiles/h2x.jpg" width="35" height="35"> </div>
                        <div class="message-wrapper">
                          <div class="heading"> You have've got 150 messages </div>
                          <div class="description"> 150 newly unread messages in your inbox </div>
                        </div>
                        <div class="date pull-right"> Yesterday </div>
                        <div class="clearfix"></div>
                      </div>
                      <div class="notification-messages info">
                        <div class="user-profile"> <img src="assets/img/profiles/d.jpg" alt="" data-src="assets/img/profiles/d.jpg" data-src-retina="assets/img/profiles/d2x.jpg" width="35" height="35"> </div>
                        <div class="message-wrapper">
                          <div class="heading"> Jane Smith - Commented on your wall </div>
                          <div class="description"> How did the meeting go? </div>
                        </div>
                        <div class="date pull-right"> Just now </div>
                        <div class="clearfix"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row tiles-container m-b-10 tiles grey">
                <div class="col-md-12">
                  <div class="tiles white col-md-8 col-sm-8 no-padding">
                    <div class="tiles-body">
                      <div class="row">
                        <div class="col-md-6 col-sm-6">
                          <div class="mini-chart-wrapper">
                            <div class="chart-details-wrapper">
                              <div class="chartname"> New Orders </div>
                              <div class="chart-value"> 17,555 </div>
                            </div>
                            <div class="mini-chart">
                              <div id="mini-chart-orders_2"></div>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6 col-sm-6">
                          <div class="mini-chart-wrapper">
                            <div class="chart-details-wrapper">
                              <div class="chartname"> My Balance </div>
                              <div class="chart-value"> $17,555 </div>
                            </div>
                            <div class="mini-chart">
                              <div id="mini-chart-other_2"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br>
                    <div id="ricksaw_2" class="ricksaw"></div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="col-md-4 col-sm-4 no-padding">
                    <div class="tiles grey ">
                      <div class="tiles white no-margin">
                        <div class="tiles-body">
                          <div class="tiles-title blend"> OVERALL VIEWS </div>
                          <div class="heading"> <span data-animation-duration="1000" data-value="432852" class="animate-number">0</span> </div>
                          44% higher <span class="blend">than last month</span> </div>
                      </div>
                      <div id="legend_2"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row ">
                <div class="col-md-4 col-sm-6">
                  <div class="row ">
                    <!-- BEGIN BLOG POST SIMPLE-->
                    <div class="col-md-12 m-b-10">
                      <div class="widget-item ">
                        <div class="controller overlay right">
                          <a href="javascript:;" class="reload"></a>
                          <a href="javascript:;" class="remove"></a>
                        </div>
                        <div class="tiles green " style="max-height:345px">
                          <div class="tiles-body">
                            <h3 class="text-white m-t-50 m-b-30 m-r-20"> Webarch <span class="semi-bold">UI Bundle
                        highly customizable UI
                        elements</span> </h3>
                            <div class="overlayer bottom-right fullwidth">
                              <div class="overlayer-wrapper">
                                <div class=" p-l-20 p-r-20 p-b-20 p-t-20">
                                  <div class="pull-right"> <a href="#" class="hashtags transparent"> #Art Design </a> </div>
                                  <div class="clearfix"></div>
                                </div>
                              </div>
                            </div>
                            <br>
                          </div>
                        </div>
                        <div class="tiles white ">
                          <div class="tiles-body">
                            <div class="row">
                              <div class="user-comment-wrapper pull-left">
                                <div class="profile-wrapper"> <img src="assets/img/profiles/avatar_small.jpg" alt="" data-src="assets/img/profiles/avatar_small.jpg" data-src-retina="assets/img/profiles/avatar_small2x.jpg" width="35" height="35"> </div>
                                <div class="comment">
                                  <div class="user-name text-black bold"> David <span class="semi-bold">Cooper</span> </div>
                                  <div class="preview-wrapper">@ revox </div>
                                </div>
                                <div class="clearfix"></div>
                              </div>
                              <div class="pull-right m-r-20"> <span class="bold text-black small-text">24m</span> </div>
                              <div class="clearfix"></div>
                              <div class="p-l-15 p-t-10 p-r-20">
                                <p>The attention to detail and the end product is stellar! I enjoyed the process </p>
                                <div class="post p-t-10 p-b-10">
                                  <ul class="action-bar no-margin p-b-20 ">
                                    <li><a href="#" class="muted bold"><i class="fa fa-comment  m-r-10"></i>1584</a> </li>
                                    <li><a href="#" class="text-error bold"><i class="fa fa-heart  m-r-10"></i>47k</a> </li>
                                  </ul>
                                  <div class="clearfix"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- END BLOG POST SIMPLE-->
                  </div>
                  <div class="row">
                    <!-- BEGIN BLOG POST WITH CAROUSEL IMAGE -->
                    <div class="col-md-12 m-b-10">
                      <div class="widget-item ">
                        <div class="controller overlay right">
                          <a href="javascript:;" class="reload"></a>
                          <a href="javascript:;" class="remove"></a>
                        </div>
                        <div class="tiles white p-t-15">
                          <div class="row">
                            <div class="col-md-2">
                              <div class="profile-img-wrapper pull-left m-l-10">
                                <div class=" p-r-10"> <img src="assets/img/profiles/c.jpg" alt="" data-src="assets/img/profiles/c.jpg" data-src-retina="assets/img/profiles/c2x.jpg" width="35" height="35"> </div>
                              </div>
                            </div>
                            <div class="col-md-10">
                              <div class="user-name text-black bold large-text"> John <span class="semi-bold">Smith</span> </div>
                              <div class="preview-wrapper">shared a picture with <span class="bold">Jane Smith</span>.</div>
                            </div>
                          </div>
                          <div id="image-demo-carl" class="m-t-15 owl-carousel owl-theme">
                            <div class="item"><img src="assets/img/others/1.jpg" alt=""></div>
                            <div class="item"><img src="assets/img/others/2.jpg" alt=""></div>
                          </div>
                          <div class="post p-b-15 p-t-15 p-l-15 b-b b-grey">
                            <ul class="action-bar no-margin ">
                              <li><a href="#" class="muted"><i class="fa fa-comment m-r-5"></i> 24</a> </li>
                              <li>
                                <a href="#" class="text-error bold"> <i class="fa fa-heart-o  m-r-5"></i> 5</a>
                              </li>
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <p class="p-t-10 p-b-10 p-l-15 p-r-15"><span class="bold">Jane Smith, John Smith, David Jester, pepper</span> post and 214 others like this.</p>
                          <div class="clearfix"></div>
                          <div class="p-b-10 p-l-10 p-r-10">
                            <div class="profile-img-wrapper pull-left"> <img src="assets/img/profiles/avatar_small.jpg" alt="" data-src="assets/img/profiles/avatar_small.jpg" data-src-retina="assets/img/profiles/avatar_small2x.jpg" width="35" height="35"> </div>
                            <div class="inline pull-right" style="width:86%">
                              <div class="input-group transparent ">
                                <input type="text" class="form-control" placeholder="Write a comment">
                                <span class="input-group-addon"> <i class="fa fa-camera"></i> </span> </div>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- END BLOG POST WITH CAROUSEL IMAGE -->
                  </div>
                </div>
                <div class="col-md-4 col-sm-6 hidden-sm">
                  <div class="row">
                    <!-- BEGIN BLOG POST WITH IMAGE -->
                    <div class="col-md-12 m-b-10">
                      <div class="widget-item ">
                        <div class="controller overlay right">
                          <a href="javascript:;" class="reload"></a>
                          <a href="javascript:;" class="remove"></a>
                        </div>
                        <div class="tiles green  overflow-hidden full-height" style="max-height:214px">
                          <div class="overlayer bottom-right fullwidth">
                            <div class="overlayer-wrapper">
                              <div class="tiles gradient-black p-l-20 p-r-20 p-b-20 p-t-20">
                                <div class="pull-right"> <a href="#" class="hashtags transparent"> #Art Design </a> </div>
                                <div class="clearfix"></div>
                              </div>
                            </div>
                          </div>
                          <img src="assets/img/others/10.png" alt="" class="lazy hover-effect-img image-responsive-width"> </div>
                        <div class="tiles white ">
                          <div class="tiles-body">
                            <div class="row">
                              <div class="user-profile-pic text-left"> <img width="69" height="69" data-src-retina="assets/img/profiles/avatar2x.jpg" data-src="assets/img/profiles/avatar.jpg" src="assets/img/profiles/avatar.jpg" alt="">
                                <div class="pull-right m-r-20 m-t-35"> <span class="bold text-black small-text">24m</span> </div>
                              </div>
                              <div class="col-md-5 no-padding">
                                <div class="user-comment-wrapper">
                                  <div class="comment">
                                    <div class="user-name text-black bold"> David <span class="semi-bold">Jester</span> </div>
                                    <div class="preview-wrapper">@ revox </div>
                                  </div>
                                  <div class="clearfix"></div>
                                </div>
                              </div>
                              <div class="col-md-7 no-padding">
                                <div class="clearfix"></div>
                                <div class="m-r-20 m-t-20 m-b-10  m-l-10">
                                  <p class="p-b-10">The attention to detail and the end product is stellar! I enjoyed the process </p>
                                  <a href="#" class="hashtags m-b-5"> #new york city </a> <a href="#" class="hashtags m-b-5"> #amazing </a> <a href="#" class="hashtags m-b-5"> #citymax </a> </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- END BLOG POST WITH IMAGE -->
                  </div>
                  <div class="row">
                    <!-- BEGIN BLOG POST SIMPLE -->
                    <div class="col-md-12 m-b-10">
                      <div class="widget-item ">
                        <div class="controller overlay right">
                          <a href="javascript:;" class="reload"></a>
                          <a href="javascript:;" class="remove"></a>
                        </div>
                        <div class="tiles purple " style="max-height:345px">
                          <div class="tiles-body">
                            <h3 class="text-white m-t-50 m-b-30 m-r-20"> Webarch <span class="semi-bold">UI Bundle
                        highly customizable UI
                        elements</span> </h3>
                            <div class="overlayer bottom-right fullwidth">
                              <div class="overlayer-wrapper">
                                <div class=" p-l-20 p-r-20 p-b-20 p-t-20">
                                  <div class="pull-right"> <a href="#" class="hashtags transparent"> #Art Design </a> </div>
                                  <div class="clearfix"></div>
                                </div>
                              </div>
                            </div>
                            <br>
                          </div>
                        </div>
                        <div class="tiles white ">
                          <div class="tiles-body">
                            <div class="row">
                              <div class="user-comment-wrapper pull-left">
                                <div class="profile-wrapper"> <img src="assets/img/profiles/d.jpg" alt="" data-src="assets/img/profiles/d.jpg" data-src-retina="assets/img/profiles/d2x.jpg" width="35" height="35"> </div>
                                <div class="comment">
                                  <div class="user-name text-black bold"> Jane <span class="semi-bold">Smith</span> </div>
                                  <div class="preview-wrapper">@ webarch </div>
                                </div>
                                <div class="clearfix"></div>
                              </div>
                              <div class="pull-right m-r-20"> <span class="bold text-black small-text">24m</span> </div>
                              <div class="clearfix"></div>
                              <div class="p-l-15 p-t-10 p-r-20">
                                <p>The attention to detail and the end product is stellar! I enjoyed the process </p>
                                <div class="post p-t-10 p-b-10">
                                  <ul class="action-bar no-margin p-b-20 ">
                                    <li><a href="#" class="muted bold"><i class="fa fa-comment  m-r-10"></i>1584</a> </li>
                                    <li><a href="#" class="text-error bold"><i class="fa fa-heart  m-r-10"></i>47k</a> </li>
                                  </ul>
                                  <div class="clearfix"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- END BLOG POST SIMPLE -->
                  </div>
                </div>
                <div class="col-md-4 col-sm-6">
                  <div class="row">
                    <!-- BEGIN BLOG POST WITH MAP -->
                    <div class="col-md-12 m-b-10">
                      <div class="widget-item ">
                        <div class="controller overlay right">
                          <a href="javascript:;" class="reload"></a>
                          <a href="javascript:;" class="remove"></a>
                        </div>
                        <div class="tiles white p-t-15  m-b-20">
                          <div class="row">
                            <div class="col-md-2">
                              <div class="profile-img-wrapper pull-left m-l-10">
                                <div class=" p-r-10"> <img src="assets/img/profiles/h.jpg" alt="" data-src="assets/img/profiles/h.jpg" data-src-retina="assets/img/profiles/h2x.jpg" width="35" height="35"> </div>
                              </div>
                            </div>
                            <div class="col-md-10">
                              <div class="user-name text-black bold large-text"> David <span class="semi-bold">Jester</span> </div>
                              <div class="preview-wrapper">was with <span class="bold">Jane Smith</span> and 4 others at <span class="bold">The Shore By O</span>.</div>
                            </div>
                          </div>
                          <div id="location-map" class="m-t-15 " style="height: 200px"> </div>
                          <div class="post p-b-15 p-t-15 p-l-15 b-b b-grey">
                            <ul class="action-bar no-margin ">
                              <li><a href="#" class="muted"><i class="fa fa-comment m-r-5"></i> 24</a> </li>
                              <li>
                                <a href="#" class="text-error bold"> <i class="fa fa-heart-o  m-r-5"></i> 5</a>
                              </li>
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <p class="p-t-10 p-b-10 p-l-15 p-r-15"><span class="bold">Jane Smith, John Smith, David Jester, pepper</span> post and 214 others like this.</p>
                          <div class="clearfix"></div>
                          <div class="p-b-10 p-l-10 p-r-10">
                            <div class="profile-img-wrapper pull-left"> <img width="35" height="35" alt="" src="assets/img/profiles/e.jpg" data-src="assets/img/profiles/e.jpg" data-src-retina="assets/img/profiles/e2x.jpg"> </div>
                            <div class="inline pull-right" style="width:86%">
                              <div class="input-group transparent ">
                                <input type="text" class="form-control" placeholder="Write a comment">
                                <span class="input-group-addon"> <i class="fa fa-camera"></i> </span> </div>
                            </div>
                            <div class="clearfix"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- END BLOG POST WITH MAP -->
                  </div>
                  <div class="row">
                    <!-- BEGIN BLOG POST WITH IMAGE -->
                    <div class="col-md-12 m-b-10">
                      <div class="widget-item ">
                        <div class="controller overlay right">
                          <a href="javascript:;" class="reload"></a>
                          <a href="javascript:;" class="remove"></a>
                        </div>
                        <div class="tiles green  overflow-hidden full-height" style="max-height:214px">
                          <div class="overlayer bottom-right fullwidth">
                            <div class="overlayer-wrapper">
                              <div class="tiles gradient-black p-l-20 p-r-20 p-b-20 p-t-20">
                                <div class="pull-right"> <a href="#" class="hashtags transparent"> #Art Design </a> </div>
                                <div class="clearfix"></div>
                              </div>
                            </div>
                          </div>
                          <img src="assets/img/others/10.png" alt="" class="lazy hover-effect-img image-responsive-width"> </div>
                        <div class="tiles white ">
                          <div class="tiles-body">
                            <div class="row">
                              <div class="user-profile-pic text-left"> <img width="69" height="69" data-src-retina="assets/img/profiles/avatar2x.jpg" data-src="assets/img/profiles/avatar.jpg" src="assets/img/profiles/avatar.jpg" alt="">
                                <div class="pull-right m-r-20 m-t-35"> <span class="bold text-black small-text">24m</span> </div>
                              </div>
                              <div class="col-md-5 no-padding">
                                <div class="user-comment-wrapper">
                                  <div class="comment">
                                    <div class="user-name text-black bold"> David <span class="semi-bold">Jester</span> </div>
                                    <div class="preview-wrapper">@ revox </div>
                                  </div>
                                  <div class="clearfix"></div>
                                </div>
                              </div>
                              <div class="col-md-7 no-padding">
                                <div class="clearfix"></div>
                                <div class="m-r-20 m-t-20 m-b-10  m-l-10">
                                  <p class="p-b-10">The attention to detail and the end product is stellar! I enjoyed the process </p>
                                  <a href="#" class="hashtags m-b-5"> #new york city </a> <a href="#" class="hashtags m-b-5"> #amazing </a> <a href="#" class="hashtags m-b-5"> #citymax </a> </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- BEGIN BLOG POST WITH IMAGE -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row 2col">
            <div class="col-md-3 col-sm-6 m-b-10">
              <div class="tiles blue ">
                <div class="tiles-body">
                  <div class="controller">
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                  </div>
                  <div class="tiles-title"> TODAY’S SERVER LOAD </div>
                  <div class="heading"> <span class="animate-number" data-value="26.8" data-animation-duration="1200">0</span>% </div>
                  <div class="progress transparent progress-small no-radius">
                    <div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="26.8%"></div>
                  </div>
                  <div class="description"><i class="icon-custom-up"></i><span class="text-white mini-description ">&nbsp; 4% higher <span class="blend">than last month</span></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 m-b-10">
              <div class="tiles green ">
                <div class="tiles-body">
                  <div class="controller">
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                  </div>
                  <div class="tiles-title"> TODAY’S VISITS </div>
                  <div class="heading"> <span class="animate-number" data-value="2545665" data-animation-duration="1000">0</span> </div>
                  <div class="progress transparent progress-small no-radius">
                    <div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="79%"></div>
                  </div>
                  <div class="description"><i class="icon-custom-up"></i><span class="text-white mini-description ">&nbsp; 2% higher <span class="blend">than last month</span></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 m-b-10">
              <div class="tiles red ">
                <div class="tiles-body">
                  <div class="controller">
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                  </div>
                  <div class="tiles-title"> TODAY’S SALES </div>
                  <div class="heading"> $ <span class="animate-number" data-value="14500" data-animation-duration="1200">0</span> </div>
                  <div class="progress transparent progress-white progress-small no-radius">
                    <div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="45%"></div>
                  </div>
                  <div class="description"><i class="icon-custom-up"></i><span class="text-white mini-description ">&nbsp; 5% higher <span class="blend">than last month</span></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 m-b-10">
              <div class="tiles purple  ">
                <div class="tiles-body">
                  <div class="controller">
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                  </div>
                  <div class="tiles-title"> TODAY’S FEEDBACKS </div>
                  <div class="row-fluid">
                    <div class="heading"> <span class="animate-number" data-value="1600" data-animation-duration="700">0</span> </div>
                    <div class="progress transparent progress-white progress-small no-radius">
                      <div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="12%"></div>
                    </div>
                  </div>
                  <div class="description"><i class="icon-custom-up"></i><span class="text-white mini-description ">&nbsp; 3% higher <span class="blend">than last month</span></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- BEGIN DASHBOARD TILES -->
            <div class="col-md-4 col-vlg-3 col-sm-6">
              <div class="tiles green   m-b-10">
                <div class="tiles-body">
                  <div class="controller">
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                  </div>
                  <div class="tiles-title text-black">OVERALL SALES </div>
                  <div class="widget-stats">
                    <div class="wrapper transparent"> <span class="item-title">Overall Visits</span> <span class="item-count animate-number semi-bold" data-value="2415" data-animation-duration="700">0</span> </div>
                  </div>
                  <div class="widget-stats">
                    <div class="wrapper transparent"> <span class="item-title">Today's</span> <span class="item-count animate-number semi-bold" data-value="751" data-animation-duration="700">0</span> </div>
                  </div>
                  <div class="widget-stats ">
                    <div class="wrapper last"> <span class="item-title">Monthly</span> <span class="item-count animate-number semi-bold" data-value="1547" data-animation-duration="700">0</span> </div>
                  </div>
                  <div class="progress transparent progress-small no-radius m-t-20" style="width:90%">
                    <div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="64.8%"></div>
                  </div>
                  <div class="description"> <span class="text-white mini-description ">4% higher <span class="blend">than last month</span></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-vlg-3 col-sm-6">
              <div class="tiles blue   m-b-10">
                <div class="tiles-body">
                  <div class="controller">
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                  </div>
                  <div class="tiles-title text-black">OVERALL VISITS </div>
                  <div class="widget-stats">
                    <div class="wrapper transparent"> <span class="item-title">Overall Visits</span> <span class="item-count animate-number semi-bold" data-value="15489" data-animation-duration="700">0</span> </div>
                  </div>
                  <div class="widget-stats">
                    <div class="wrapper transparent"> <span class="item-title">Today's</span> <span class="item-count animate-number semi-bold" data-value="551" data-animation-duration="700">0</span> </div>
                  </div>
                  <div class="widget-stats ">
                    <div class="wrapper last"> <span class="item-title">Monthly</span> <span class="item-count animate-number semi-bold" data-value="1450" data-animation-duration="700">0</span> </div>
                  </div>
                  <div class="progress transparent progress-small no-radius m-t-20" style="width:90%">
                    <div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="54%"></div>
                  </div>
                  <div class="description"> <span class="text-white mini-description ">4% higher <span class="blend">than last month</span></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-vlg-3 col-sm-6">
              <div class="tiles purple   m-b-10">
                <div class="tiles-body">
                  <div class="controller">
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                  </div>
                  <div class="tiles-title text-black">SERVER LOAD </div>
                  <div class="widget-stats">
                    <div class="wrapper transparent"> <span class="item-title">Overall LOAD</span> <span class="item-count animate-number semi-bold" data-value="5695" data-animation-duration="700">0</span> </div>
                  </div>
                  <div class="widget-stats">
                    <div class="wrapper transparent"> <span class="item-title">Today's</span> <span class="item-count animate-number semi-bold" data-value="568" data-animation-duration="700">0</span> </div>
                  </div>
                  <div class="widget-stats ">
                    <div class="wrapper last"> <span class="item-title">Monthly</span> <span class="item-count animate-number semi-bold" data-value="12459" data-animation-duration="700">0</span> </div>
                  </div>
                  <div class="progress transparent progress-small no-radius m-t-20" style="width:90%">
                    <div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="90%"></div>
                  </div>
                  <div class="description"> <span class="text-white mini-description ">4% higher <span class="blend">than last month</span></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-vlg-3 visible-xlg visible-sm col-sm-6">
              <div class="tiles red   m-b-10">
                <div class="tiles-body">
                  <div class="controller">
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                  </div>
                  <div class="tiles-title text-black">OVERALL SALES </div>
                  <div class="widget-stats">
                    <div class="wrapper transparent"> <span class="item-title">Overall Visits</span> <span class="item-count animate-number semi-bold" data-value="2415" data-animation-duration="700">0</span> </div>
                  </div>
                  <div class="widget-stats">
                    <div class="wrapper transparent"> <span class="item-title">Today's</span> <span class="item-count animate-number semi-bold" data-value="751" data-animation-duration="700">0</span> </div>
                  </div>
                  <div class="widget-stats ">
                    <div class="wrapper last"> <span class="item-title">Monthly</span> <span class="item-count animate-number semi-bold" data-value="1547" data-animation-duration="700">0</span> </div>
                  </div>
                  <div class="progress transparent progress-small no-radius m-t-20" style="width:90%">
                    <div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="64.8%"></div>
                  </div>
                  <div class="description"> <span class="text-white mini-description ">4% higher <span class="blend">than last month</span></span>
                  </div>
                </div>
              </div>
            </div>
            <!-- END DASHBOARD TILES -->
          </div>
          <div class="row">
            <!-- BEGIN DASHBOARD TILES -->
            <div class="col-md-3 col-sm-6">
              <div class="live-tile slide ha m-b-10" data-speed="750" data-delay="3000" data-mode="carousel">
                <div class="slide-front ha tiles green  ">
                  <div class="p-t-15 p-l-15 p-r-15 p-b-15">
                    <h4 class="text-black no-margin semi-bold">Today's Visits</h4>
                    <h2 class="text-white no-margin p-t-15 bold"><span data-animation-duration="900" data-value="810" class="animate-number">0</span></h2>
                    <p class="text-white  p-b-5">views</p>
                    <div class="progress transparent progress-small">
                      <div class="progress-bar progress-bar-white animate-progress-bar" data-percentage="26.8%"></div>
                    </div>
                    <i class="fa fa-camera m-r-10"></i> <i class="fa fa-cog m-r-10 fa-lg"></i> <i class="fa fa-map-marker m-r-10 fa-lg"></i>
                    <div class="overlayer bottom-right">
                      <div class="p-r-15 p-b-15"> <i class="fa fa-globe fa fa-5x"></i> </div>
                    </div>
                  </div>
                </div>
                <div class="slide-back ha tiles green ">
                  <div class="p-t-15 p-l-15 p-r-15 p-b-15">
                    <h4 class="text-black no-margin semi-bold">Overview</h4>
                  </div>
                  <div class="overlayer bottom-left fullwidth">
                    <div class="overlayer-wrapper">
                      <div class="p-t-20 p-l-20 p-r-20 p-b-20">
                        <p class="bold">Webarch Dashboard</p>
                        <p>2,567 USD <span class="m-l-10"><i class="fa fa-sort-desc"></i> 2%</span></p>
                        <p class="bold p-t-15">Front-end Design</p>
                        <p>1,420 USD <span class="m-l-10"><i class="fa fa-sort-desc"></i> 1%</span></p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
            </div>
            <div class="col-md-3 col-sm-6">
              <div class="tiles blue    m-b-10">
                <div class="tiles-body">
                  <div class="controller">
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                  </div>
                  <h4 class="text-black no-margin semi-bold">Today's Sales</h4>
                  <h2 class="text-white bold "><span data-animation-duration="900" data-value="245" class="animate-number">0</span></h2>
                  <h4 class="text-black semi-bold  ">New comments</h4>
                  <div class="description"><i class="icon-custom-up"></i><span class="text-white mini-description ">&nbsp; 4% higher <span class="blend">than last month</span></span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6">
              <div class="tiles white   m-b-10">
                <div class="tiles-body">
                  <div class="controller">
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                  </div>
                  <div class="tiles-title text-black">OVERALL SALES </div>
                  <h3 class="text-black bold "><span data-animation-duration="900" data-value="4458" class="animate-number">0</span></h3>
                  <div class="progress transparent progress-small no-radius no-margin">
                    <div class="progress-bar progress-bar-black animate-progress-bar" data-percentage="79%"></div>
                  </div>
                  <p class="text-black  p-t-5 p-b-5 small-text">webarch 258 USD</p>
                  <div class="progress transparent progress-small no-radius no-margin ">
                    <div class="progress-bar progress-bar-black animate-progress-bar" data-percentage="58%"></div>
                  </div>
                  <p class="text-black p-t-5 small-text">revox 258 USD</p>
                </div>
              </div>
            </div>
            <div class="col-md-3 col-sm-6">
              <div class="tiles white   m-b-10">
                <div class="tiles-body">
                  <div class="controller">
                    <a href="javascript:;" class="reload"></a>
                    <a href="javascript:;" class="remove"></a>
                  </div>
                  <div class="tiles-title text-black">OVERALL VISITS </div>
                  <h3 class="text-black bold "><span data-animation-duration="900" data-value="15489" class="animate-number">0</span></h3>
                  <div class="progress transparent progress-small no-radius no-margin">
                    <div class="progress-bar progress-bar-black animate-progress-bar" data-percentage="45%"></div>
                  </div>
                  <p class="text-black  p-t-5 p-b-5 small-text">webarch 258 USD</p>
                  <div class="progress transparent progress-small no-radius no-margin ">
                    <div class="progress-bar progress-bar-black animate-progress-bar" data-percentage="20%"></div>
                  </div>
                  <p class="text-black p-t-5 small-text">revox 258 USD</p>
                </div>
              </div>
            </div>
            <!-- END DASHBOARD TILES -->
          </div>
        </div>
      </div>
      <!-- BEGIN CHAT -->
	  
        <div class="clearfix"></div>

        <div class="overlayer overlay-fixed top-right ">
          <div class="overlayer-wrapper">
            <div class="content">
              <div class="btn-group" data-toggle="buttons-radio">
                <button type="button" class="btn btn-primary " id="map-zoom-in"><i class="fa fa-plus"></i></button>
                <button type="button" class="btn btn-primary" id="map-zoom-out"><i class="fa fa-minus"></i> </button>
              </div>
            </div>
          </div>
        </div>
     
		
		

      <!-- END CHAT -->
    </div>
    <!-- END CONTAINER -->
    <script src="assets/plugins/pace/pace.min.js" type="text/javascript"></script>
    <!-- BEGIN JS DEPENDECENCIES-->
    <script src="assets/plugins/jquery/jquery-1.11.3.min.js" type="text/javascript"></script>
    <script src="assets/plugins/bootstrapv3/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-block-ui/jqueryblockui.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="assets/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
    <!-- END CORE JS DEPENDECENCIES-->
    <!-- BEGIN CORE TEMPLATE JS -->
    <script src="webarch/js/webarch.js" type="text/javascript"></script>
    <script src="assets/js/chat.js" type="text/javascript"></script>
    <!-- END CORE TEMPLATE JS -->
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js" type="text/javascript"></script>
    <!-- 
	<script src="assets/plugins/jquery-ricksaw-chart/js/raphael-min.js"></script>
    <script src="assets/plugins/jquery-ricksaw-chart/js/d3.v2.js"></script>
    <script src="assets/plugins/jquery-ricksaw-chart/js/rickshaw.min.js"></script>
    -->
	<script src="assets/plugins/jquery-sparkline/jquery-sparkline.js"></script>
    <script src="assets/plugins/skycons/skycons.js"></script>
    <script src="assets/plugins/owl-carousel/owl.carousel.min.js" type="text/javascript"></script>
    <!-- <script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=true"></script>-->
			<!-- AIzaSyBdlB03OpJdyH_mmsBVg6OC0BdP-8c8cTk-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCK7sql4zSHreVAdLPH7t8JoNE1gy5Tuyk" type="text/javascript"></script>
    <script src="assets/plugins/jquery-gmap/gmaps.js" type="text/javascript"></script>
    <script src="assets/plugins/shape-hover/js/snap.svg-min.js"></script>
    <script src="assets/plugins/jquery-flot/jquery.flot.js" type="text/javascript"></script>\
    <script src="assets/plugins/jquery-metrojs/MetroJs.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-jvectormap/js/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-jvectormap/js/jquery-jvectormap-us-lcc-en.js" type="text/javascript"></script>

    <!-- 
    <script src="assets/plugins/Mapplic/js/jquery.easing.js" type="text/javascript"></script>
    <script src="assets/plugins/Mapplic/js/jquery.mousewheel.js" type="text/javascript"></script>
	<script src="assets/plugins/Mapplic/js/hammer.js" type="text/javascript"></script>
    <script src="assets/plugins/Mapplic/mapplic/mapplic.js" type="text/javascript"></script>	
	-->

    <!-- END PAGE LEVEL PLUGINS -->
    <script src="assets/js/widgets.js" type="text/javascript"></script>
    <script type="text/javascript">
    $(document).ready(function()
    {
      $(".live-tile,.flip-list").liveTile();
    });
    </script>
    <!-- END CORE TEMPLATE JS -->
		<script>
		function showRSS() {
		  if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		  } else {  // code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  xmlhttp.onreadystatechange=function() {
			if (this.readyState==4 && this.status==200) {
			  document.getElementById("rssOutput").innerHTML=this.responseText;
			}
		  }
		  xmlhttp.open("GET","/rss/getrss.php?q=drama",true);
		  xmlhttp.send();
		}
		</script>
		<script>
			$(document).ready(function(){
				showRSS();
			});
		</script>
		
	<script src="assets/js/google_maps.js" type="text/javascript"></script>
<script>
    /** 
    * Define an array of custom styles.
    */
    var CustomMapStyles = [
  {
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#f5f5f5"
      }
    ]
  },
  {
    "elementType": "geometry.fill",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "elementType": "labels.icon",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#616161"
      }
    ]
  },
  {
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#f5f5f5"
      }
    ]
  },
  {
    "featureType": "administrative.country",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#161a20"
      }
    ]
  },
  {
    "featureType": "administrative.country",
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "featureType": "administrative.land_parcel",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#bdbdbd"
      }
    ]
  },
  {
    "featureType": "administrative.locality",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#161a20"
      },
      {
        "weight": 2.5
      }
    ]
  },
  {
    "featureType": "administrative.locality",
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "featureType": "administrative.province",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#161a20"
      }
    ]
  },
  {
    "featureType": "administrative.province",
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "featureType": "poi",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#eeeeee"
      }
    ]
  },
  {
    "featureType": "poi",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#757575"
      }
    ]
  },
  {
    "featureType": "poi.park",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#e5e5e5"
      }
    ]
  },
  {
    "featureType": "poi.park",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#9e9e9e"
      }
    ]
  },
  {
    "featureType": "road",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "featureType": "road.arterial",
    "elementType": "geometry.fill",
    "stylers": [
      {
        "color": "#a1a1a1"
      }
    ]
  },
  {
    "featureType": "road.arterial",
    "elementType": "geometry.stroke",
    "stylers": [
      {
        "color": "#161a20"
      }
    ]
  },
  {
    "featureType": "road.arterial",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#161a20"
      }
    ]
  },
  {
    "featureType": "road.arterial",
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#dadada"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "geometry.fill",
    "stylers": [
      {
        "color": "#686868"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "geometry.stroke",
    "stylers": [
      {
        "color": "#161a20"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#161a20"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "featureType": "road.highway.controlled_access",
    "elementType": "geometry.fill",
    "stylers": [
      {
        "color": "#161a20"
      }
    ]
  },
  {
    "featureType": "road.local",
    "elementType": "geometry.fill",
    "stylers": [
      {
        "color": "#bfbfbf"
      }
    ]
  },
  {
    "featureType": "road.local",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#2f343c"
      }
    ]
  },
  {
    "featureType": "road.local",
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "featureType": "transit.line",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#e5e5e5"
      }
    ]
  },
  {
    "featureType": "transit.station",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#eeeeee"
      }
    ]
  },
  {
    "featureType": "water",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#c9c9c9"
      }
    ]
  },
  {
    "featureType": "water",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#9e9e9e"
      }
    ]
  }
]
;
</script>

	<script>
	function initialize() {
		var markers = new Array();

		var mapOptions = {
			zoom: 10,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			center: new google.maps.LatLng(<?=$center?>)
		};

		var locations = [
			<?=$location?>
		];

		var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

		var infowindow = new google.maps.InfoWindow();
		
		for (var i = 0; i < locations.length; i++) {

			// Append a link to the markers DIV for each marker
			//$('#markers').append('<a class="marker-link" data-markerid="' + i + '" href="#">' + locations[i][1] + '</a> ');

			var marker = new google.maps.Marker({
				position: locations[i][0],
				map: map,
				title: locations[i][1],
			});
			  google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
				  //infowindow.setContent(locations[i][0]);
				  //infowindow.open(map, marker);
					map.panTo(this.getPosition());
					map.setZoom(15);
					infowindow.setContent(locations[i][2]);
					infowindow.open(map, marker);
				}
			  })(marker, i));
			  
			// Register a click event listener on the marker to display the corresponding infowindow content
			//google.maps.event.addListener(marker, 'click', (function (marker, i) {
			//	return function () {
			//		infowindow.setContent(locations[i][2]);
			//		infowindow.open(map, marker);
			//	}

			//})(marker, i));

			// Add marker to markers array
			markers.push(marker);
			//alert(markers);
		}

		// Trigger a click event on each marker when the corresponding marker link is clicked
		$('.marker-link').on('click', function () {
			//alert($(this).data('markerid'));
			google.maps.event.trigger(markers[$(this).data('markerid')], 'click');
		});
	}

	initialize();
	</script>


  </body>
</html>