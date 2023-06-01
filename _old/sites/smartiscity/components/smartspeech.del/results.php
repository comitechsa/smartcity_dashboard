<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."/perm.php");
	$permissions = (intval($auth->UserRow['access'])>0?$auth->UserRow['access']:0);

	if (!($permissions & $FLAG_100) && $auth->UserType != "Administrator") {
		Redirect("index.php");
	} 
	if($auth->UserType != "Administrator"){
		$filter=" AND t1.user_id=".$auth->UserRow['user_id'];
	}
		
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
ini_set('memory_limit', '1024M');
$starttime = microtime(true); // Top of page
// Code
//$endtime = microtime(true); // Bottom of page
//printf("1.Loaded in %f seconds", $endtime - $starttime );
/*
beecalibration_gaze__screen_recording.mp4 (0)
rika&bee_gaze_screen_recording.mp4 (1)
upsidedwarf_gaze_screen.mp4 (2)
*/

//0095
//0134
//$a=readHeartrate('0134');

//if($auth->UserType != "Administrator") Redirect("index.php");

global $nav;
$nav = "Αποτελέσματα";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=users";
$command=array();
$command=explode("&",$_POST["Command"]);

//if( $auth->UserType == "Administrator" )
//{
	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "SAVE")
		{
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
			{
				$PrimaryKeys["user_id"] = intval($_GET["item"]);
				$QuotFields["user_id"] = true;
				
			} else {
				$Collector["date_insert"] = date('Y-m-d H:i:s');
				$QuotFields["date_insert"] = true;
			}

			$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
			$QuotFields["is_valid"] = true;
			
			$Collector["user_fullname"] = $_POST["user_fullname"];
			$QuotFields["user_fullname"] = true;
			
			$Collector["email"] = $_POST["email"];
			$QuotFields["email"] = true;
			
			$Collector["user_auth"] = "Register";
			$QuotFields["user_auth"] = true;
			
			$Collector["user_name"] = $_POST["email"];
			$QuotFields["user_name"] = true;
			
			$Collector["user_password"] = $_POST["user_password"];
			$QuotFields["user_password"] = true;
			
			$Collector["phone"] = $_POST["phone"];
			$QuotFields["phone"] = true;
			
			//$Collector["parent"] = ($auth->UserType == "Administrator"?'0':$auth->UserId);
			//$QuotFields["parent"] = true;
			
			$Collector["role_id"] =  $_POST["role_id"]; //($auth->UserType == "Administrator"?$_POST["role_id"]:$auth->UserRow['role_id']);
			$QuotFields["role_id"] = true;
			
			$Collector["description"] =  $_POST["description"]; 
			$QuotFields["description"] = true;
			
			$db->ExecuteUpdater("users",$PrimaryKeys,$Collector,$QuotFields);
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { //$command[0] ==
			if($item != "")
			{
				$error=0;
				//$result = $db->sql_query('SELECT * FROM devices WHERE user_id='.$item);
				//if($db->sql_numrows($result) > 0) $error++;
				//$result = $db->sql_query('SELECT * FROM messages WHERE user_id='.$item);
				//if($db->sql_numrows($result) > 0) $error++;
				if($error==0) {	
					//$filter=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
					$filter="";
					$db->sql_query("DELETE FROM users WHERE user_id=" . $item.$filter);
					$messages->addMessage("DELETE!!!");
					Redirect($BaseUrl);
				} else {
					$messages->addMessage("Υπάρχουν συνδεδεμένες εγγραφές. Η διαγραφή δεν μπορεί να ολοκληρωθεί");
					Redirect($BaseUrl);
				}
			}
		}
	}
//}

if(isset($_GET["item"])) {
	
	//$filter=" WHERE 1=1 AND user_auth!='Administrator '";
	//$filter.=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
	//$query="SELECT * FROM users WHERE user_id=".$_GET['item'].$filter;
	//$dr_e = $db->RowSelectorQuery($query);
	//if (!isset($dr_e["user_id"]) && intval($_GET["item"])> 0) {
	//	$messages->addMessage("NOT FOUND!!!");
	//	Redirect("index.php?com=users");
	//}
	
	

	/*
	ini_set('memory_limit', '1024M');
	//$url="http://94.130.246.21:16103/pinger/getspechearts";
	//$url="https://smartspeech.ddns.net/pinger/getspechearts";
	$url="192.168.222.161:3000/pinger/getspechearts";
	$ch = curl_init( $url );
	$payload = json_encode( array( "PlayerID" => $_GET["item"] ) );
	//echo 'payload:'.$payload;
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
	
	*/
	

	//print_r($data);
	//echo $data[0]->id;
	/*
	$counter=0;
	$result = [];
	for($i=0;$i<sizeof($data);$i++){
		$result[$data[$i]->timestamp]['count']=$result[$data[$i]->timestamp]['count']+1;
		$result[$data[$i]->timestamp]['heartrate']=$result[$data[$i]->timestamp]['heartrate']+$data[$i]->heartrate;	
		$result[$counter]['timeval']=intval(substr($dataRes[$counter]['time'],0,4).substr($dataRes[$counter]['time'],5,2).substr($dataRes[$counter]['time'],8,2).substr($dataRes[$counter]['time'],11,2).substr($dataRes[$counter]['time'],14,2).substr($dataRes[$counter]['time'],17,2));
	}

	$counter=0;
	$dataRes=array();
	foreach($result as $key=>$val) {
		$dataRes[$counter]['time']=$key;
		$dataRes[$counter]['heartrate']=($val['heartrate']/$val['count']);
		$dataRes[$counter]['timeval']=intval(substr($dataRes[$counter]['time'],0,4).substr($dataRes[$counter]['time'],5,2).substr($dataRes[$counter]['time'],8,2).substr($dataRes[$counter]['time'],11,2).substr($dataRes[$counter]['time'],14,2).substr($dataRes[$counter]['time'],17,2));
		$counter++;
	}	
	*/
	if($auth->UserType != "Administrator"){
		$check=$db->RowSelectorQuery("SELECT * FROM children WHERE children_id=".$_GET['item']." AND user_id=".$auth->UserRow['user_id']." LIMIT 1");
		//echo "SELECT * FROM children WHERE children_id=".$_GET['item']." AND user_id=".$auth->UserRow['user_id']." LIMIT 1";
		if(intval($check['children_id'])<=0) {
			$messages->addMessage("Λυπάμαι, δεν έχετε πρόσβαση στη σελίδα αυτή!!!");
			Redirect("index.php");	
		}
	}
	
	$data=readCard($_GET['item']);
	//print_r($data);
	//echo $data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->Pin;

//exit;
$pinArray=array();

if(($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->Pin; 
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->Pin;
if(($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->Pin!='') && (!in_array($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->Pin, $pinArray))) $pinArray[]=$data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->Pin;

$dataRes=readHeartrateArray($pinArray);

	//echo readHeartrate('0141'); //megethos:99756
	//echo readHeartrate('0142'); //megethos:99756
	//0141
	//0142

	
	//$endtime = microtime(true); // Bottom of page
	//printf("3.Loaded in %f seconds", $endtime - $starttime );
	//print_r($data[$_GET['iteration']]);
	//echo '<br><br><br>';
	//echo $data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->Files.'<br>';
	$episode00Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->Files));
	$episode01Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->Files));
	$episode02Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->Files));
	$episode03Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->Files));
	$episode04Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->Files));
	$episode05Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->Files));
	$episode06Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->Files));
	$episode07Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->Files));
	$episode08Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->Files));
	$episode09Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->Files));
	$episode10Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->Files));
	$episode11Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->Files));
	$episode12Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->Files));
	$episode13Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->Files));
	$episode14Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->Files));
	$episode15Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->Files));
	$episode16Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->Files));
	$episode17Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->Files));
	$episode18Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->Files));
	$episode19Files=(explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->Files));
	//print_r($episode01Files);
	?>

	<div class="row">
		<div class="col-lg-6 col-xl-12">
			<div class="toggle toggle-primary" data-plugin-toggle="">
				
				<section class="toggle">
					<label>0. Calibration </label>
					<div class="toggle-content">
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->Status=='Complete'){
								$episode00=True;
								$episode00Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->Start,17,2));
								$episode00End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->End,17,2));
								$n=0;
								
								//$dataRes=readHeartrate(($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->Pin));
								
								for($i=0;$i<sizeof($dataRes);$i++){
									//echo $dataRes[$i]['timeval'].'-'.$episode00Start.'-'.$episode00End.'<br>';
									if($dataRes[$i]['timeval']>=$episode00Start && $dataRes[$i]['timeval']<=$episode00End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels00.="'".showTime($n)."',";
										$dataGraph00.=$dataRes[$i]['heartrate'].',';
									}
								}
								//exit;
								$labels00=mb_substr($labels00, 0, -1);
								$dataGraph00=mb_substr($dataGraph00, 0, -1);							
							}
							//echo $dataGraph00;
							//exit;
						?>
						<? if($episode00){ 
							$heatmap0Url="https://get.smartspeech.eu/heatmap.php?child=".$_GET['item']."&iteration=".$_GET['iteration']."&episode=0";
						?>
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 00'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td style="vertical-align:middle;">Κοίτα τη μέλισσα</td>
														<td style="vertical-align:middle;">Eye tracking - calibration </td>
														<td style="vertical-align:middle;"><a href="#" onClick="MyWindow=window.open('<?=$heatmap0Url?>','MyWindow','width=1200,height=600'); return false;"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-search"></i></button></td>
														<td style="vertical-align:middle;">Εστίαση προσοχής</td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							<!-- 
							<div class="col-sm-12">
								<a href="#" onClick="MyWindow=window.open('<? //=$heatmap0Url?>','MyWindow','width=1200,height=600'); return false;">Heatmap</a>
							</div>
							-->
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode00"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>				
				<section class="toggle">
					<label>1. Ξέφωτο </label>
					<div class="toggle-content" style="display: none;">
						<br>
						<?
						if($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->Status=='Complete'){
							$episode01=True;
							$episode01Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->Start,17,2));
							$episode01End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->End,17,2));
							$n=0;
							//new
							//$dataRes=readHeartrate(($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->Pin));
							for($i=0;$i<sizeof($dataRes);$i++){
								if($dataRes[$i]['timeval']>=$episode01Start && $dataRes[$i]['timeval']<=$episode01End){
									$n++;
									//$labels01.="'".$dataRes[$i]['time']."',";
									//$labels01.="'".$n."',";
									$labels01.="'".showTime($n)."',";
									$dataGraph01.=$dataRes[$i]['heartrate'].',';
									
								}
							}
							$labels01=mb_substr($labels01, 0, -1);
							$dataGraph01=mb_substr($dataGraph01, 0, -1);							
						}
						/*
						foreach($result as $key=>$val) {
							$labels.="'".$key."',";
							$dataGraph.=intval($val['heartrate']/$val['count']).',';
						}
						$labels=mb_substr($labels, 0, -1);
						$dataGraph=mb_substr($dataGraph, 0, -1);
						*/
						?>
						<? if($episode01){ 						
							$heatmap1Url="https://get.smartspeech.eu/heatmap.php?child=".$_GET['item']."&iteration=".$_GET['iteration']."&episode=1";
						?>
							<!-- 
							<div class="col-sm-12">
								<a href="#" onClick="MyWindow=window.open('<? //=$heatmap1Url?>','MyWindow','width=1200,height=600'); return false;">Heatmap</a>
							</div>
							-->

							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td style="vertical-align:middle;">Πες ΠΑΜΕ: έως 3 secs</td>
														<td style="vertical-align:middle;">Ηχογράφηση
														<!--
															<span style="margin-left:30px; vertical-align:middle;">
																<audio controls>
																	<source src="https://smartspeech.ddns.net/files/<? //=$episode01Files[0]?>" type="audio/wav">
																	Your browser does not support the audio element.
																</audio>
															</span> 
														-->

														</td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode01Files[0]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td style="vertical-align:middle;">Άρθρωση /p/ σε αρχική θέση <br>και /m/ σε τελική θέση, εκτέλεση απλής εντολής</td>
													</tr>
													<tr class="b-top-1">
														<td style="vertical-align:middle;">Η Ρίκα θα λέει μια πρόταση </td>
														<td style="vertical-align:middle;">Eye tracking - calibration </td>
														<td><a href="#" onClick="MyWindow=window.open('<?=$heatmap1Url?>','MyWindow','width=1200,height=600'); return false;"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-search"></i></button></td>
														<td style="vertical-align:middle;">Εστίαση προσοχής, βλεματική επαφή</td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>

							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode01"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
							<!-- 
							<div class="chart-container" style="position: relative; height:80vh; width:100%">
								<canvas id="episode01"></canvas>
							</div>						
							-->
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>2. Μεγάλος μαγεμένος βράχος </label>
					<div class="toggle-content">
						<?
						
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->Status=='Complete'){
								$episode02=True;
								$episode02Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->Start,17,2));
								$episode02End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->End,17,2));
								$e2g1Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->Start,17,2));

								$e2g1End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->End,17,2));
							
								$e2g1bc = '';
								$n=0;
								//new
								//$dataRes=readHeartrate(($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->Pin));
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode02Start && $dataRes[$i]['timeval']<=$episode02End){
										
										if($dataRes[$i]['timeval']>=$e2g1Start && $dataRes[$i]['timeval']<=$e2g1End){
											$e2g1bc .= '"#3e95cd",';
										}
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels02.="'".showTime($n)."',";
										$dataGraph02.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels02=mb_substr($labels02, 0, -1);
								$dataGraph02=mb_substr($dataGraph02, 0, -1);
								$e2g1bc = mb_substr($e2g1bc, 0, -1);
							}
						?>

					
						<? if($episode02){ 
							$heatmap2Url="https://get.smartspeech.eu/heatmap.php?child=".$_GET['item']."&iteration=".$_GET['iteration']."&episode=2";
						?>
							<div id="episode02Video" style="display:none;">
								<video width="1024" height="576" controls preload="none">
									<source src="https://smartspeech.ddns.net/files/<?=$episode02Files[0]?>" type="video/mp4">
									Your browser does not support the video tag.
								</video>
								<? //echo '111'.intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->Start;?>
							</div>
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td>Μόλις μπαίνει ο νάνος</td>
														<td>Video recording</td>
														<td><a href="#" onclick="open_in_new_window('location=1,status=1,toolbar=1,resizeable=1,width=1200,height=600','episode02Video');"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-video"></i></button></a></td>
														<td>Εκφραση συναισθήματος</td>
													</tr>
													<tr class="b-top-0">
														<td>Παίρνω το σωστό ζώο</td>
														<td>Drag & drop</td>
														<td><a href="#episode02Game01"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>Αντιστοίχιση σχήματος </td>
													</tr>
													
													<tr class="b-top-0">
														<td>Νάνος ανάποδα</td>
														<td>Eyetracking</td>
														<td><a href="#" onClick="MyWindow=window.open('<?=$heatmap2Url?>','MyWindow','width=1200,height=600'); return false;"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-search"></i></button></td>
														<td>Eστίαση προσοχής </td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
																					
							<div style='margin-top:3px;' class="row" id="episode02Game01">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 1: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Στάδιο</th>
														<th>Σωστή επιλογή</th>
														<th>Επιλογή χρήστη</th>
														<th>Αποτέλεσμα</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 01'}->Correct?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 01'}->Selected?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 01'}->Correct==$data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 01'}->Selected?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 02'}->Correct?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 02'}->Selected?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 02'}->Correct==$data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 02'}->Selected?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 03'}->Correct?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 03'}->Selected?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 03'}->Correct==$data[$_GET['iteration']]->EpisodeData->{'Episode 02'}->{'Game 01'}->{'Level 03'}->Selected?'Σωστό':'Λάθος')?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode02"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>3. Σκιερό μονοπάτι </label>
					<div class="toggle-content">
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->Status=='Complete'){
								$episode03=True;
								$episode03Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->Start,17,2));
								$episode03End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->End,17,2));
								$n=0;
								//new
								//$dataRes=readHeartrate(($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->Pin));
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode03Start && $dataRes[$i]['timeval']<=$episode03End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels03.="'".showTime($n)."',";
										$dataGraph03.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels03=mb_substr($labels03, 0, -1);
								$dataGraph03=mb_substr($dataGraph03, 0, -1);							
							}
						?>
						<? if($episode03){ 
							$heatmap3Url="https://get.smartspeech.eu/heatmap.php?child=".$_GET['item']."&iteration=".$_GET['iteration']."&episode=3"; 
						?>	
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 03'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td>Ξωτικά αντικρυστά </td>
														<td>Eyetracking</td>
														<td><a href="#" onClick="MyWindow=window.open('<?=$heatmap3Url?>','MyWindow','width=1200,height=600'); return false;"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-search"></i></button></td>
														<td>Eστίαση προσοχής </td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode03"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>4. Σκιερό μονοπάτι – πεσμένο δέντρο </label>
					<div class="toggle-content">				
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->Status=='Complete'){
								$episode04=True;
								$episode04Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->Start,17,2));
								$episode04End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->End,17,2));
								$n=0;
								//new
								//$dataRes=readHeartrate(($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->Pin));
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode04Start && $dataRes[$i]['timeval']<=$episode04End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels04.="'".showTime($n)."',";
										$dataGraph04.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels04=mb_substr($labels04, 0, -1);
								$dataGraph04=mb_substr($dataGraph04, 0, -1);							
							}
						?>
						<? if($episode04){ ?>
							<div id="episode04Video" style="display:none;">
								<video width="1024" height="576" controls preload="none">
									<source src="https://smartspeech.ddns.net/files/<?=$episode04Files[0]?>" type="video/mp4">
									Your browser does not support the video tag.
								</video>
							</div>
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td>Νάνος κόβει δέντρο</td>
														<td>Video recording</td>
														<td><a href="#" onclick="open_in_new_window('location=1,status=1,toolbar=1,resizeable=1,width=1200,height=600','episode04Video');"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-video"></i></button></a></td>
														<td>Συναισθηματική αντίδραση</td>
													</tr>
													
													
													<tr class="b-top-0">
														<td>Ο χρήστης βάζει τις σανίδες   </td>
														<td>drag & drop </td>
														<td><a href="#episode04Game01"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>Eστίαση προσοχής </td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							

						
							<div style='margin-top:3px;' class="row" id="episode04Game01">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 1: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Στάδιο</th>
														<th>Σωστή επιλογή</th>
														<th>Επιλογή χρήστη</th>
														<th>Αποτέλεσμα</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td>Plank Size 0</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 01'}->{'Sack Size 0'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 01'}->{'Sack Size 0'}=='Plank Size 0'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 1</td>
														<td>Plank Size 1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 01'}->{'Sack Size 1'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 01'}->{'Sack Size 1'}=='Plank Size 1'?'Σωστό':'Λάθος')?></td>
													</tr>
													
													
													<tr>
														<td>Level 2</td>
														<td>Plank Size 0</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 02'}->{'Sack Size 0'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 02'}->{'Sack Size 0'}=='Plank Size 0'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td>Plank Size 1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 02'}->{'Sack Size 1'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 02'}->{'Sack Size 1'}=='Plank Size 1'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td>Plank Size 2</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 02'}->{'Sack Size 2'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 02'}->{'Sack Size 2'}=='Plank Size 2'?'Σωστό':'Λάθος')?></td>
													</tr>
					
													<tr>
														<td>Level 3</td>
														<td>Plank Size 0</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 0'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 0'}=='Plank Size 0'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td>Plank Size 1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 1'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 1'}=='Plank Size 1'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td>Plank Size 2</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 2'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 2'}=='Plank Size 2'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td>Plank Size 3</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 3'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 04'}->{'Game 01'}->{'Level 03'}->{'Sack Size 3'}=='Plank Size 3'?'Σωστό':'Λάθος')?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode04"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>5. Το ποτάμι στο δάσος </label>
					<div class="toggle-content">
						<?
						$game1Level1=explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 01'});
						$game1Level2=explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 02'});
						$game1Level3=explode(";",$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 03'});
						//echo $data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 01'}->{'Colors Painted Off Target'};
						//exit;
						//$game1Level1Val1=explode(":",$game1Level1[0]);
						//$game1Level1Val2=explode(":",$game1Level1[1]);
						//$game1Level2Val1=explode(":",$game1Level2[0]);
						//$game1Level2Val2=explode(":",$game1Level2[1]);
						//$game1Level3Val1=explode(":",$game1Level3[0]);
						//$game1Level3Val2=explode(":",$game1Level3[1]);
						?>
						<!-- 
						<p>Παιχνίδι 3</p>
						<p>Αρχή: <? //=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->Start,17,2);?> / Τέλος: <? //=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->End,17,2);?></p>
						<p>Αποτέλεσμα: <?//=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}?></p>						
						-->
						
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->Status=='Complete'){
								$episode05=True;
								$episode05Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->Start,17,2));
								$episode05End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->End,17,2));
								$n=0;
								//new
								//$dataRes=readHeartrate(($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->Pin));
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode05Start && $dataRes[$i]['timeval']<=$episode05End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels05.="'".showTime($n)."',";
										$dataGraph05.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels05=mb_substr($labels05, 0, -1);
								$dataGraph05=mb_substr($dataGraph05, 0, -1);							
							}
						?>
						<? if($episode05){ ?>	
							<div id="episode05Video" style="display:none;">
								<video width="1024" height="576" controls preload="none">
									<source src="https://smartspeech.ddns.net/files/<?=$episode05Files[0]?>" type="video/mp4">
									Your browser does not support the video tag.
								</video>
							</div>
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td>Βάφονται οι σανίδες με τα σωστά χρώματα </td>
														<td>drag & drop </td>
														<td><a href="#episode05Game01"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>Oπτική αντίληψη, αναγνώριση χρωμάτων </td>
													</tr>
													<tr class="b-top-0">
														<td>Τοποθετούνται οι σανίδες  </td>
														<td>drag & drop </td>
														<td><a href="#episode05Game02"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>Aναγνώριση μοτίβων, οπτικοκινητικός συντονισμός </td>
													</tr>													
													<tr class="b-top-0">
														<td>Ο νάνος χάνει τα εργαλεία </td>
														<td>Video recording</td>
														<td><a href="#" onclick="open_in_new_window('location=1,status=1,toolbar=1,resizeable=1,width=1200,height=600','episode05Video');"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-video"></i></button></a></td>
														<td>Συναισθηματική αντίδραση</td>
													</tr>
													<tr class="b-top-0">
														<td>Τι νοιώθει ο νάνος  </td>
														<td>????? </td>
														<td><a href="#episode05Game03"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>???????? </td>
													</tr>	
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>

							
							<div style='margin-top:3px;' class="row" id="episode05Game01">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 1: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Στάδιο</th>
														<th>Colors Painted Off Target</th>
														<th>Colors Painted On Target</th>
														<th>% Of Target Painted</th>
														<th>% Painted On Target</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 01'}->{'Colors Painted Off Target'};?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 01'}->{'Colors Painted On Target'};?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 01'}->{'Percentage Of Target Painted'};?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 01'}->{'Percentage Painted On Target'};?></td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 02'}->{'Colors Painted Off Target'};?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 02'}->{'Colors Painted On Target'};?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 02'}->{'Percentage Of Target Painted'};?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 02'}->{'Percentage Painted On Target'};?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 03'}->{'Colors Painted Off Target'};?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 03'}->{'Colors Painted On Target'};?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 03'}->{'Percentage Of Target Painted'};?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 01'}->{'Level 03'}->{'Percentage Painted On Target'};?></td>
													</tr>
											</table>
										</div>
										<!-- 
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Στάδιο</th>
														<th>Target Percentage</th>
														<th>Inside Percentage</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td><?//=number_format($game1Level1Val1[1],2).'%'?></td>
														<td><?//=number_format($game1Level1Val2[1],2).'%'?></td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td><?//=number_format($game1Level2Val1[1],2).'%'?></td>
														<td><?//=number_format($game1Level2Val2[1],2).'%'?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td><?//=number_format($game1Level3Val1[1],2).'%'?></td>
														<td><?//=number_format($game1Level3Val2[1],2).'%'?></td>
													</tr>													
												</tbody>
											</table>
										</div>
										-->
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row" id="episode05Game02">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 2: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Στάδιο</th>
														<th>Σωστό χρώμα</th>
														<th>Επιλεγμένο χρώμα</th>
														<th>Αποτέλεσμα</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td>Yellow</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Level 01'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Level 01'}=='Yellow'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td>Red</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Level 02'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Level 02'}=='Red'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td>Blue</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Level 03'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 02'}->{'Level 03'}=='Blue'?'Σωστό':'Λάθος')?></td>
													</tr>													
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							<div style='margin-top:3px;' class="row" id="episode05Game03">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 3: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Επιλογή χρήστη</th>
														<th>Σωστή επιλογή</th>
														<th>Αποτέλεσμα</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Result'}?></td>
														<td>Sad</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 05'}->{'Game 03'}->{'Result'}=='Sad'?'Σωστό':'Λάθος')?></td>
													</tr>													
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode05"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>6. Μονοπάτι προς Χώρα των ξωτικών – μενταγιόν δράκου </label>
					<div class="toggle-content">
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->Status=='Complete'){
								$episode06=True;
								$episode06Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->Start,17,2));
								$episode06End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->End,17,2));
								$n=0;
								//new
								//$dataRes=readHeartrate(($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->Pin));
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode06Start && $dataRes[$i]['timeval']<=$episode06End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels06.="'".showTime($n)."',";
										$dataGraph06.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels06=mb_substr($labels06, 0, -1);
								$dataGraph06=mb_substr($dataGraph06, 0, -1);							
							}
						?>
						<? if($episode06){ ?>	
						
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 06'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td colspan="4">Mενταγιόν – φαίνονται τα αντικείμενα και κατανονομάζονται</td>
													</tr>
													<tr class="b-top-0">
														<td>Αστροναυτης  </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[0]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
													<tr class="b-top-0">
														<td>Μπάλα </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[1]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>												
													<tr class="b-top-0">
														<td>Ζώνη </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[2]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
													<tr class="b-top-0">
														<td>Ποδήλατο </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[3]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
													<tr class="b-top-0">
														<td>Δένδρο </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[4]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
													<tr class="b-top-0">
														<td>Κοτόπουλο </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[5]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
													<tr class="b-top-0">
														<td>Δεινόσαυρος </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[6]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
													<tr class="b-top-0">
														<td>Δράκος </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[7]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
													<tr class="b-top-0">
														<td>Πάπια </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[8]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
													<tr class="b-top-0">
														<td>Φωτιά </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[9]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
													<tr class="b-top-0">
														<td>Κλειδιά </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[10]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
													<tr class="b-top-0">
														<td>Μαχαίρι </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[11]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
													<tr class="b-top-0">
														<td>Πρόβατο </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[12]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
													<tr class="b-top-0">
														<td>Τσουλήθρα </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[13]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
								
													<tr class="b-top-0">
														<td>Τραπέζι </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[14]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
													<tr class="b-top-0">
														<td>Ρόδα </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode06Files[14]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode06"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
							
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>7. Μονοπάτι προς Χώρα των ξωτικών – κλειστή πόρτα </label>
					<div class="toggle-content">
						<!-- 
							<p>Παιχνίδι 1</p>			
							<p>Level 1: <?//=$data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Level 01'}?></p>
							<p>Level 2: <?//=$data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Level 02'}?></p>
							<p>Level 3: <?//=$data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Level 03'}?></p>		
							<br>
							<p>Παιχνίδι 2</p>
							<p>Αρχή: <?//=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 02'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 02'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 02'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 02'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 02'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 02'}->{'Time'}->Start,17,2);?> / Τέλος: <?//=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 02'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 02'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 02'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 02'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 02'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 02'}->{'Time'}->End,17,2);?></p>										
						-->

						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->Status=='Complete'){
								$episode07=True;
								$episode07Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->Start,17,2));
								$episode07End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->End,17,2));
								$n=0;
								//new
								//$dataRes=readHeartrate(($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->Pin));
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode07Start && $dataRes[$i]['timeval']<=$episode07End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels07.="'".showTime($n)."',";
										$dataGraph07.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels07=mb_substr($labels07, 0, -1);
								$dataGraph07=mb_substr($dataGraph07, 0, -1);							
							}
						?>
						<? if($episode07){ ?>
						
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td>Διατύπωση ερώτησης  </td>
														<td>Αιώρηση ποντικιού </td>
														<td><a href="#episode07Game01"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>Κανόνες ευγένειας </td>
													</tr>
													<tr class="b-top-0">
														<td>Διατύπωση ερώτησης  </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode07Files[0]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
															<br>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode07Files[1]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
															<br>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode07Files[2]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Μνήμη, κατανόηση ιστορίας </td>
													</tr>													
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row" id="episode07Game01">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 1: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Στάδιο</th>
														<th>Σωστή επιλογή</th>
														<th>Επιλογή χρήστη</th>
														<th>Αποτέλεσμα</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td>Rika</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Level 01'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Level 01'}=='Rika'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td>Rika</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Level 02'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Level 02'}=='Rika'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td>Themis</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Level 03'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Game 01'}->{'Level 03'}=='Themis'?'Σωστό':'Λάθος')?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode07"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
							<!-- 
						Κανόνες ευγένειας 
						<audio controls>
							<source src="https://smartspeech.ddns.net/files/<? //=$episode07Files[9]?>" type="audio/wav">
							Your browser does not support the audio element.
						</audio>
						<br>
							-->

						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>8. Μονοπάτι προς Χώρα των ξωτικών </label>
					<div class="toggle-content">
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->Status=='Complete'){
								$episode08=True;
								$episode08Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->Start,17,2));
								$episode08End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->End,17,2));
								$n=0;
								//new
								//$dataRes=readHeartrate(($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->Pin));
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode08Start && $dataRes[$i]['timeval']<=$episode08End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels08.="'".showTime($n)."',";
										$dataGraph08.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels08=mb_substr($labels08, 0, -1);
								$dataGraph08=mb_substr($dataGraph08, 0, -1);							
							}
						?>
						<? if($episode08){ 
								$heatmap81Url="https://get.smartspeech.eu/heatmap.php?child=".$_GET['item']."&iteration=".$_GET['iteration']."&episode=81";
								$heatmap82Url="https://get.smartspeech.eu/heatmap.php?child=".$_GET['item']."&iteration=".$_GET['iteration']."&episode=82";
								$heatmap83Url="https://get.smartspeech.eu/heatmap.php?child=".$_GET['item']."&iteration=".$_GET['iteration']."&episode=83";
								$heatmap84Url="https://get.smartspeech.eu/heatmap.php?child=".$_GET['item']."&iteration=".$_GET['iteration']."&episode=84";
						?>	
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 07'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td>Ψάχνοντας τη Σόφη  </td>
														<td>xxxΑιώρηση ποντικιού </td>
														<td><a href="#episode08Game01"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>xxxΚανόνες ευγένειας </td>
													</tr>
													<tr class="b-top-0">
														<td>1Ανεμιστήρας, βρέφος και λοιπές φωτογραφίες</td>
														<td>Eyetracking</td>
														<td><a href="#" onClick="MyWindow=window.open('<?=$heatmap81Url?>','MyWindow','width=1200,height=600'); return false;"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-search"></i></button></td>
														<td>Еστίαση προσοχής με χρήση αντικειμένου υψηλού ενδιαφέροντος  </td>
													</tr>	
													<tr class="b-top-0">
														<td>2Ανεμιστήρας, βρέφος και λοιπές φωτογραφίες</td>
														<td>Eyetracking</td>
														<td><a href="#" onClick="MyWindow=window.open('<?=$heatmap82Url?>','MyWindow','width=1200,height=600'); return false;"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-search"></i></button></td>
														<td>Еστίαση προσοχής με χρήση αντικειμένου υψηλού ενδιαφέροντος  </td>
													</tr>		
													<tr class="b-top-0">
														<td>3Ανεμιστήρας, βρέφος και λοιπές φωτογραφίες</td>
														<td>Eyetracking</td>
														<td><a href="#" onClick="MyWindow=window.open('<?=$heatmap83Url?>','MyWindow','width=1200,height=600'); return false;"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-search"></i></button></td>
														<td>Еστίαση προσοχής με χρήση αντικειμένου υψηλού ενδιαφέροντος  </td>
													</tr>	
													<tr class="b-top-0">
														<td>4Ανεμιστήρας, βρέφος και λοιπές φωτογραφίες</td>
														<td>Eyetracking</td>
														<td><a href="#" onClick="MyWindow=window.open('<?=$heatmap84Url?>','MyWindow','width=1200,height=600'); return false;"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-search"></i></button></td>
														<td>Еστίαση προσοχής με χρήση αντικειμένου υψηλού ενδιαφέροντος  </td>
													</tr>														
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row" id="episode08Game01">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 1: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Στάδιο</th>
														<th>Αποτέλεσμα επιλογής χρήστη</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Game 01'}->{'Level 01'}?></td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Game 01'}->{'Level 02'}?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 08'}->{'Game 01'}->{'Level 03'}?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode08"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>9. Χώρα των ξωτικών - Πύλη </label>
					<div class="toggle-content">
						<!-- 
						<p>Παιχνίδι 1</p>
						<p>Αρχή: <? //=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <? //=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->End,17,2);?></p>					
						<p>Level 1: <? //=$data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Level 01'}?></p>
						<p>Level 2: <? //=$data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Level 02'}?></p>
						<p>Level 3: <? //=$data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Level 03'}?></p>	
						-->

						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->Status=='Complete'){
								$episode09=True;
								$episode09Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->Start,17,2));
								$episode09End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->End,17,2));
								$n=0;
								//new
								//$dataRes=readHeartrate(($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->Pin));
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode09Start && $dataRes[$i]['timeval']<=$episode09End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels09.="'".showTime($n)."',";
										$dataGraph09.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels09=mb_substr($labels09, 0, -1);
								$dataGraph09=mb_substr($dataGraph09, 0, -1);							
							}
						?>
						<? if($episode09){ ?>	
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td>Τοποθέτηση λέξεων στη σωστή σειρά </td>
														<td>drag & drop </td>
														<td><a href="#episode09Game01"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>Σύνταξη προτάσεων </td>
													</tr>												
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>

							<div style='margin-top:3px;' class="row" id="episode09Game01">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 1: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Στάδιο</th>
														<th>Σωστή επιλογή</th>
														<th>Επιλογή χρήστη</th>
														<th>Αποτέλεσμα</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td>Sto;Kastro;Ftasame</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Level 01'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Level 01'}=='Sto;Kastro;Ftasame'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td>Psahnoume;Ton;Drako;Mas</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Level 02'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Level 02'}=='Psahnoume;Ton;Drako;Mas'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td>O;Drakos;Einai;Sto;Kastro</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Level 03'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 09'}->{'Game 01'}->{'Level 03'}=='O;Drakos;Einai;Sto;Kastro'?'Σωστό':'Λάθος')?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode09"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>10. Χώρα των ξωτικών – Δρόμος με καταστήματα </label>
					<div class="toggle-content">
						<p></p>
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->Status=='Complete'){
								$episode10=True;
								$episode10Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->Start,17,2));
								$episode10End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->End,17,2));
								$n=0;
								//new
								//$dataRes=readHeartrate(($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->Pin));
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode10Start && $dataRes[$i]['timeval']<=$episode10End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels10.="'".showTime($n)."',";
										$dataGraph10.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels10=mb_substr($labels10, 0, -1);
								$dataGraph10=mb_substr($dataGraph10, 0, -1);							
							}
						?>
						<? if($episode10){ ?>	
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td>Ξωτικό ρωτάει πως έφτασαν εκεί  </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode10Files[0]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Περιγραφικός/αφηγηματικός λόγος. Ροή ομιλίας, Προσωδία </td>
													</tr>
													<tr class="b-top-0">
														<td>Ο φούρναρης περιγράφει τα υλικά που χρειάζονται για να φτιαχτεί το κείκ </td>
														<td>Drag & drop </td>
														<td><a href="#episode10Game01"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>Οπτικοκινητικός συντονισμός </td>
													</tr>
													<tr class="b-top-0">
														<td>Μανάβισσα πετάει τα φρούτα χιαστί </td>
														<td>touchscreen/ click </td>
														<td><a href="#episode10Game02"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>Οπτικοκινητικός συντονισμός </td>
													</tr>	
													<tr class="b-top-0">
														<td>H μανάβισσα ζητά από τον παίκτη να αναφέρει τα φρούτα και λαχανικά και να τα βάλει στα σωστά καλάθια </td>
														<td>ηχογράφηση, click/ tap </td>
														<td><a href="#episode10Game03"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>άρθρωση, Οπτικοκινητικός συντονισμός,Εκτελεστικές λειτουργίες </td>
													</tr>	
													<tr class="b-top-0">
														<td>Το ξωτικό νιώθει αηδία από το σάπιο μήλο</td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode10Files[16]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>αναγνώριση συναισθήματος, άρθρωση </td>
													</tr>													
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row" id="episode10Game01">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 2: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 02'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 02'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 02'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 02'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 02'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 02'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 02'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 02'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 02'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 02'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 02'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 02'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Bowl</th>
														<th>Left Turning</th>
														<th>Right Turning</th>
														<th>Αποτέλεσμα</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 02'}->{'Bowl'}?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 02'}->{'Left Turning'}?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 02'}->{'Right Turning'}?></td>
														<td>---</td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>

							<div style='margin-top:3px;' class="row" id="episode10Game02">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 3: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Ιδιότητα</th>
														<th>Αποτέλεσμα</th>
													</tr>
												</thead>
												<tbody>
													<tr><td>Log</td><td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Log'}?></td></tr>
													<tr><td>Missed</td><td><?= substr_count($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Log'},"Missed");?></td></tr>
													<tr><td>Tapped</td><td><?= substr_count($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Log'},"Tapped");?></td></tr>
													<tr><td>Caught</td><td><?= substr_count($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 03'}->{'Log'},"Caught");?></td></tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
										
							<div style='margin-top:3px;' class="row" id="episode10Game03">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 4: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 04'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 04'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 04'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 04'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 04'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 04'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 04'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 04'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 04'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 04'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 04'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 04'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<?
													//Διάβασε τα αρχεία από τις ηχογραφήσεις
													for($i=1;$i<=15;$i++){
														$arr1 = explode("-", $episode10Files[$i]);
														$arr2 = explode("_", $arr1[1]);
														$type[$i]=$arr1[0];
														$kind[$i]=$arr2[0];
													}
													?>
													<tr>
														<th>Ονομασία</th>
														<th>Είδος</th>
														<th>Καλάθι που τοποθετήθηκε</th>
														<th>Ηχογράφηση</th>
													</tr>
												</thead>
												<tbody>
												<? for($n=1;$n<=15;$n++){?>
													<tr>
														<td><?=$kind[$n]?></td>
														<td><?=$type[$n]?></td>
														<?
														if(strpos($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->{'Game 04'}->{'Fruit Basket'}, $kind[$n]) !== false){
															$basket='Φρούτα';
														} else{
															$basket='Λαχανικά';
														}
														?>
														<td><?=$basket?></td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode10Files[$n]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
													</tr>
												<? } ?>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>		
							<!-- 
							Επεισόδιο 12 
							level 1 Χαρά το σωστό στο game01
							To 2 έχει τις ηχογραφήσεις
							το 3 λαγός-αλεπου-ψάρι Rabbit-Fox-Fish
						
							Λαχανικά: πράσο, λάχανο, κολοκύθι, μαρούλι, μπρόκολο, καρότο, παντζάρι, 
							Φρούτα: μήλο, αχλάδι, πορτοκάλια, ροδάκινο, μπανάνες, κεράσι, ακτινίδιο, μανταρίνι, φράουλα, βερίκοκο, πεπόνι, ανανάς, αχλάδι
							
							playerinterview_microphone_recording_2020_10_19_14_48_34_069.wav

							Vegetable-Leek_microphone_recording_2020_10_19_14_52_01_617.wav
							Vegetable-Cucumber_microphone_recording_2020_10_19_14_52_05_242.wav
							Vegetable-Onion_microphone_recording_2020_10_19_14_52_07_360.wav
							Fruit-Apple_microphone_recording_2020_10_19_14_52_09_410.wav
							Vegetable-Banana_microphone_recording_2020_10_19_14_52_14_147.wav
							Vegatable-Brocoli_microphone_recording_2020_10_19_14_52_16_040.wav
							Vegetable-Pepper_microphone_recording_2020_10_19_14_52_18_778.wav
							Fruit-Grapes_microphone_recording_2020_10_19_14_52_21_624.wav
							Fruit-Orange_microphone_recording_2020_10_19_14_52_23_764.wav
							Fruit-Orange_microphone_recording_2020_10_19_14_52_25_953.wav
							Fruit-Orange_microphone_recording_2020_10_19_14_52_28_051.wav
							Vegetable-Letuce_microphone_recording_2020_10_19_14_52_30_330.wav
							Vegetable-Turnip_microphone_recording_2020_10_19_14_52_32_477.wav
							Vegetable-Carrot_microphone_recording_2020_10_19_14_52_34_716.wav
							Vegatable-Cauliflower_microphone_recording_2020_10_19_14_52_38_119.wav
							Fruit-Watermelon_microphone_recording_2020_10_19_14_52_40_253.wav
							Fruit-Lemon_microphone_recording_2020_10_19_14_52_42_409.wav
							Fruit-Pear_microphone_recording_2020_10_19_14_52_44_515.wav
							Vegetable-Garlic_microphone_recording_2020_10_19_14_52_48_932.wav
							Fruit-Kiwi_microphone_recording_2020_10_19_14_52_52_249.wav
							Vegetable-Zuchini_microphone_recording_2020_10_19_14_52_54_401.wav
							Vegetable-Chinese Lettuce_microphone_recording_2020_10_19_14_52_56_621.wav
							Fruit-Melon_microphone_recording_2020_10_19_14_52_58_800.wav
							Fruit-Cherry_microphone_recording_2020_10_19_14_53_01_076.wav
							Fruit-Strawberry_microphone_recording_2020_10_19_14_53_06_575.wav
							Fruit-Pineapple_microphone_recording_2020_10_19_14_53_10_060.wav

							_microphone_recording_2020_10_19_14_53_42_246.wav"
							-->
							<!-- 
						
							<td>Πράσο  </td>
							
							<td>Αγγούρι </td>
							<td>Ηχογράφηση </td>
							<td>Κρεμμύδι </td>
							<td>Μήλο </td>
							<td>Μπανάνα </td>
							<td>Μπρόκολο </td>
							<td>Πιπέρι </td>
							<td>Σταφύλια </td>
							<td>Πορτοκάλι </td>
							<td>Πορτοκάλι </td>
							<td>Πορτοκάλι </td>
							<td>Letuce </td>
							<td>Ρεπανάκι </td>
							<td>Καρότο </td>
							<td>Κουνουπίδι </td>
							<td>Καρπούζι </td>
							<td>Λεμόνι </td>
							<td>Αχλάδι </td>
							<td>Σκόρδο </td>
							<td>Ακτινίδια </td>
							<td>Κολοκύθι </td>
							<td>Κινέζικο μαρούλι </td>
							<td>Πεπόνι</td>
							<td>Κεράσι</td>
							<td>Φράουλα</td>
							<td>Ανανάς</td>
							<td>Ηχογράφηση </td>
							-->				
									
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode10"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
							
				<section class="toggle">
					<label>11. Χώρα των ξωτικών – είσοδος Κάστρου </label>
					<div class="toggle-content">
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->Status=='Complete'){
								$episode11=True;
								$episode11Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->Start,17,2));
								$episode11End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->End,17,2));
								$n=0;
								//$dataRes=readHeartrate(($data[$_GET['iteration']]->EpisodeData->{'Episode 10'}->Pin));
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode11Start && $dataRes[$i]['timeval']<=$episode11End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels11.="'".showTime($n)."',";
										$dataGraph11.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels11=mb_substr($labels11, 0, -1);
								$dataGraph11=mb_substr($dataGraph11, 0, -1);							
							}
						?>
						<? if($episode11){ 
							$heatmap11Url="https://get.smartspeech.eu/heatmap.php?child=".$_GET['item']."&iteration=".$_GET['iteration']."&episode=11";
							?>	
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td>Παιχνίδι μνήμης με πλέγματα  </td>
														<td>click/ tap </td>
														<td><a href="#episode11Game01"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>εκτελεστικές λειτουργίες (μνήμη εργασίας) </td>
													</tr>
													<tr class="b-top-0">
														<td>Εστίαση στο πρόσωπο του Θέμη   </td>
														<td>eyetracking </td>		
														<td><a href="#" onClick="MyWindow=window.open('<?=$heatmap11Url?>','MyWindow','width=1200,height=600'); return false;"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-search"></i></button></td>
														<td>εστίαση προσοχής, βλεμματική επαφή </td>
													</tr>
													<tr class="b-top-0">
														<td>Ο χρήστης δημιουργεί το μονοπάτι για να λύσει, να βγει, από τον λαβύρινθο.</td>
														<td>point n click ή με ταπ </td>
														<td><a href="#episode11Game02"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>Οπτικοκινητικός συντονισμός, Κινητικός σχεδιασμός, Λογική </td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
						
							<div style='margin-top:3px;' class="row" id="episode11Game01">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 1: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Στάδιο</th>
														<th>Σωστή επιλογή</th>
														<th>Επιλογή χρήστη</th>
														<th>Αποτέλεσμα</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td>Token: Y1,X0;Token: Y0,X1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Level 01'}->{'Selected'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Level 01'}=='Token: Y1,X0;Token: Y0,X1'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td>Token: Y1,X2;Token: Y1,X1;Token: Y2,X1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Level 02'}->{'Selected'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Level 02'}=='Token: Y1,X2;Token: Y1,X1;Token: Y2,X1'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td>Token: Y1,X2;Token: Y2,X0;Token: Y0,X1;Token: Y1,X1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Level 03'}->{'Selected'}?></td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 01'}->{'Level 03'}=='Token: Y1,X2;Token: Y2,X0;Token: Y0,X1;Token: Y1,X1'?'Σωστό':'Λάθος')?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>						
						
						
							<div style='margin-top:3px;' class="row" id="episode11Game02">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 2: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 02'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 02'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 02'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 02'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 02'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 02'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 02'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 02'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 02'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 02'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 02'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 02'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Στάδιο</th>
														<th>Απόκριση</th>	
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Completion Status</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 11'}->{'Game 02'}->{'Completion Status'}?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>	
						
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode11"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>12. Χώρα των ξωτικών – Κάστρο </label>
					<div class="toggle-content">
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->Status=='Complete'){
								$episode12=True;
								$episode12Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->Start,17,2));
								$episode12End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->End,17,2));
								$n=0;
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode12Start && $dataRes[$i]['timeval']<=$episode12End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels12.="'".showTime($n)."',";
										$dataGraph12.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels12=mb_substr($labels12, 0, -1);
								$dataGraph12=mb_substr($dataGraph12, 0, -1);							
							}
						?>
						<? if($episode12){ ?>
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td>Επιλογή συναισθήματος - EMOTICON</td>
														<td>click/ tap </td>
														<td><a href="#episode12Game01"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>αναγνώριση συναισθήματος (χαρά) </td>
													</tr>
													<tr class="b-top-0">
														<td>Ξωτικό ρωτάει ποιος έσπασε τη γέφυρα, σε ποια χώρα βρίσκεται ο ΑΨΟΥ και τι εποχή είναι  </td>
														<td>ηχογράφηση</td>		
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode12Files[0]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
															<br>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode12Files[1]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
															<br>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode12Files[2]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατανόηση ιστορίας</td>
													</tr>
													<tr class="b-top-0">
														<td>3 ερωτήσεις με εικόνες (κυριολεκτικά και μεταφορικά)</td>
														<td>Ακουστική Αντίληψη – Ηχογράφηση </td>
														<td><a href="#episode12Game02"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>Αντίληψη</td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
						
							<div style='margin-top:3px;' class="row" id="episode12Game01">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 1: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Επιλογή χρήστη</th>
														<th>Σωστή επιλογή</th>	
														<th>Αποτέλεσμα</th>	
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 01'}->{'Selection'}?></td>
														<td>Happy</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 01'}->{'Selection'}=='Happy'?'Σωστό':'Λάθος')?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>	
						
							<div style='margin-top:3px;' class="row" id="episode12Game02">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 2: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 02'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 02'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 02'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 02'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 02'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 02'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 02'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 02'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 02'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 02'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 02'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 02'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Επίπεδο</th>
														<th>Επιλογή χρήστη</th>
														<th>Σωστή επιλογή</th>	
														<th>Αποτέλεσμα</th>	
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 03'}->{'Level 01'}?></td>
														<td>Rabbit</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 03'}->{'Level 01'}=='Rabbit'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 03'}->{'Level 02'}?></td>
														<td>Fox</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 03'}->{'Level 02'}=='Fox'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 03'}->{'Level 03'}?></td>
														<td>Fish</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 12'}->{'Game 03'}->{'Level 03'}=='Fish'?'Σωστό':'Λάθος')?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode12"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>13. Πτήση στον ουρανό </label>
					<div class="toggle-content">
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->Status=='Complete'){
								$episode13=True;
								$episode13Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->Start,17,2));
								$episode13End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->End,17,2));
								$n=0;
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode13Start && $dataRes[$i]['timeval']<=$episode13End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels13.="'".showTime($n)."',";
										$dataGraph13.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels13=mb_substr($labels13, 0, -1);
								$dataGraph13=mb_substr($dataGraph13, 0, -1);							
							}
						?>
						<? if($episode13){ ?>
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td>Πτήση με τον δράκο και έλεγχος πόσα συννεφάκια δεν έχουν «χτυπηθεί». </td>
														<td>Έλεγχος σκορ </td>
														<td><a href="#episode13Game01"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>Έλεγχος αναπνοής </td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
						
							<div style='margin-top:3px;' class="row" id="episode13Game01">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 1: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Total</th>
														<th>Hit</th>
														<th>Ηχογράφηση</th>	
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Game 01'}->{'Total'}?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 13'}->{'Game 01'}->{'Hit'}?></td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode13Files[0]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
						
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode13"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>14. Πολιτεία των αερόστατων – αροπροβλήτα αερόστατων </label>
					<div class="toggle-content">
						<p></p>
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->Status=='Complete'){
								$episode14=True;
								$episode14Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->Start,17,2));
								$episode14End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->End,17,2));
								$n=0;
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode14Start && $dataRes[$i]['timeval']<=$episode14End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels14.="'".showTime($n)."',";
										$dataGraph14.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels14=mb_substr($labels14, 0, -1);
								$dataGraph14=mb_substr($dataGraph14, 0, -1);							
							}
						?>
						<? if($episode14){ ?>
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td>Ερώτηση στον χρήστη για το τι νιώθει η Ρίκα </td>
														<td>ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode14Files[0]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Φωνολογία, ακουστική διάκριση </td>
													</tr>
													<tr class="b-top-0">
														<td>Εμφάνιση 3 ζευγών εικόνων </td>
														<td>click/ tap </td>
														<td><a href="#episode14Game01"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>Φωνολογία, συλλαβές</td>
													</tr>
													<tr class="b-top-0">
														<td>Ακούγονται συλλαβές και ο χρήστης επιλέγει την εικόνα </td>
														<td>click/ tap </td>
														<td><a href="#episode14Game02"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>Φωνολογία, συλλαβές</td>
													</tr>
													<tr class="b-top-0">
														<td>Ακούγονται συλλαβές και εμφανίζονται εικόνες  </td>
														<td>click/ tap </td>
														<td><a href="#episode14Game03"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>Φωνολογία, συλλαβές</td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row" id="episode14Game01">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 1: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Επίπεδο</th>
														<th>Επιλογή χρήστη</th>
														<th>Σωστή επιλογή</th>	
														<th>Αποτέλεσμα</th>	
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Level 01'}?></td>
														<td>Μπότα</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Level 01'}=='Bota'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Level 02'}?></td>
														<td>Φύλλο</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Level 02'}=='Fillo'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Level 03'}?></td>
														<td>Χελώνα</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 01'}->{'Level 03'}=='Helona'?'Σωστό':'Λάθος')?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row" id="episode14Game02">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 2: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Επίπεδο</th>
														<th>Επιλογή χρήστη</th>
														<th>Σωστή επιλογή</th>	
														<th>Αποτέλεσμα</th>	
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Level 01'}?></td>
														<td>Μήλο</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Level 01'}=='milo'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Level 02'}?></td>
														<td>Ρόδα</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Level 02'}=='roda'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Level 03'}?></td>
														<td>Τραπέζι</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 02'}->{'Level 03'}=='trapezi'?'Σωστό':'Λάθος')?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row" id="episode14Game02">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 3: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Επίπεδο</th>
														<th>Επιλογή χρήστη</th>
														<th>Σωστή επιλογή</th>	
														<th>Αποτέλεσμα</th>	
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Level 01'}?></td>
														<td>Χέρι</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Level 01'}=='heri'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Level 02'}?></td>
														<td>Φίδι</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Level 02'}=='fidi'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Level 03'}?></td>
														<td>Δράκος</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 14'}->{'Game 03'}->{'Level 03'}=='drakos'?'Σωστό':'Λάθος')?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode14"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>15. Πολιτεία των αερόστατων – είσοδος χωριού </label>
					<div class="toggle-content">
						<p></p>
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->Status=='Complete'){
								$episode15=True;
								$episode15Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->Start,17,2));
								$episode15End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->End,17,2));
								$n=0;
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode15Start && $dataRes[$i]['timeval']<=$episode15End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels15.="'".showTime($n)."',";
										$dataGraph15.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels15=mb_substr($labels15, 0, -1);
								$dataGraph15=mb_substr($dataGraph15, 0, -1);							
							}
						?>

						<? if($episode15){ ?>
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
		
													<tr class="b-top-0">
														<td>Παιχνίδι σειροθέτησης </td>
														<td>click/ tap </td>
														<td><a href="#episode14Game01"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>Σειροθέτηση – χρονικές ακολουθίες.</td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row" id="episode14Game02">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 1: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Επίπεδο</th>
														<th>Επιλογή χρήστη</th>
														<th>Σωστή επιλογή</th>	
														<th>Αποτέλεσμα</th>	
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Level 01'}?></td>
														<td>1_2;1_1;1_3</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Level 01'}=='1_2;1_1;1_3'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Level 02'}?></td>
														<td>2_1;2_2;2_3;2_4</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Level 02'}=='2_1;2_2;2_3;2_4'?'Σωστό':'Λάθος')?></td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Level 03'}?></td>
														<td>3_1;3_2;3_3;3_4;3_5</td>
														<td><?=($data[$_GET['iteration']]->EpisodeData->{'Episode 15'}->{'Game 01'}->{'Level 03'}=='3_1;3_2;3_3;3_4;3_5'?'Σωστό':'Λάθος')?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode15"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>16. Πολιτεία των αερόστατων – χωριό </label>
					<div class="toggle-content">
						<p></p>
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->Status=='Complete'){
								$episode16=True;
								$episode16Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->Start,17,2));
								$episode16End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->End,17,2));
								$n=0;
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode16Start && $dataRes[$i]['timeval']<=$episode16End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels16.="'".showTime($n)."',";
										$dataGraph16.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels16=mb_substr($labels16, 0, -1);
								$dataGraph16=mb_substr($dataGraph16, 0, -1);							
							}
						?>
						<? if($episode16){ 
							$heatmap16Url="https://get.smartspeech.eu/heatmap.php?child=".$_GET['item']."&iteration=".$_GET['iteration']."&episode=16";
						?>
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 16'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
		
													<tr class="b-top-0">
														<td>H Ρίκα μαζεύει ένα ταμπλετ και στη συνέχεια δίνεται προσοχή  </td>
														<td>Eye tracking </td>
														<td style="vertical-align:middle;"><a href="#" onClick="MyWindow=window.open('<?=$heatmap16Url?>','MyWindow','width=1200,height=600'); return false;"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-search"></i></button></td>
														<td>Σειροθέτηση – χρονικές ακολουθίες.</td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode16"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>17. Πολιτεία των Αερόστατων – είσοδος στην ανεμότρυπα </label>
					<div class="toggle-content">
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->Status=='Complete'){
								$episode17=True;
								$episode17Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->Start,17,2));
								$episode17End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->End,17,2));
								$n=0;
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode17Start && $dataRes[$i]['timeval']<=$episode17End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels17.="'".showTime($n)."',";
										$dataGraph17.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels17=mb_substr($labels17, 0, -1);
								$dataGraph17=mb_substr($dataGraph17, 0, -1);							
							}
						?>
						<? if($episode17){ ?>
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td>xxxPuzzle  </td>
														<td>xxxPuzzle </td>
														<td><a href="#episode17Game01"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>xxxΣειροθέτηση – χρονικές ακολουθίες.</td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							<div style='margin-top:3px;' class="row" id="episode17Game01">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 1: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Επίπεδο</th>
														<th>Επιλογή χρήστη</th>
														<th>Σωστές επιλογές</th>	
														<th>Αποτέλεσμα</th>	
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Level 01'}?></td>
														<td><?=substr_count($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Level 01'},"True");?></td>
														<td><?=substr_count($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Level 01'},"True");?> / 4</td>
													</tr>
													<tr>
														<td>Level 2</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Level 02'}?></td>
														<td><?=substr_count($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Level 02'},"True");?></td>
														<td><?=substr_count($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Level 02'},"True");?> / 8</td>
													</tr>
													<tr>
														<td>Level 3</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Level 03'}?></td>
														<td><?=substr_count($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Level 03'},"True");?></td>
														<td><?=substr_count($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Level 03'},"True");?> / 9</td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode17"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>18. Πολιτεία των Αερόστατων – Έξοδος από την Ανεμότρυπα </label>
					<div class="toggle-content">
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->Status=='Complete'){
								$episode18=True;
								$episode18Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->Start,17,2));
								$episode18End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->End,17,2));
								$n=0;
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode18Start && $dataRes[$i]['timeval']<=$episode18End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels18.="'".showTime($n)."',";
										$dataGraph18.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels18=mb_substr($labels18, 0, -1);
								$dataGraph18=mb_substr($dataGraph18, 0, -1);							
							}
						?>
						<? if($episode18){ ?>
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td>xxxTeleferik  </td>
														<td>xxxTeleferik </td>
														<td><a href="#episode18Game01"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>xxxΣειροθέτηση – χρονικές ακολουθίες.</td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							<div style='margin-top:3px;' class="row" id="episode17Game01">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 1: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Επίπεδο</th>
														<th>Αποτέλεσμα (% του χρόνο έμεινε εκτός γραμμής)</th>	
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>Level 1</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 18'}->{'Game 01'}->{'Result'}?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode18"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
				<section class="toggle">
					<label>19. Πολιτεία των Αερόστατων – Μεγάλο τρύπιο αερόστατο </label>
					<div class="toggle-content">
						<p></p>
						<?
							if($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->Status=='Complete'){
								$episode19=True;
								$episode19Start=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->Start,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->Start,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->Start,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->Start,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->Start,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->Start,17,2));
								$episode19End=intval(substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->End,0,4).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->End,5,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->End,8,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->End,11,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->End,14,2).substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->End,17,2));
								$n=0;
								for($i=0;$i<sizeof($dataRes);$i++){
									if($dataRes[$i]['timeval']>=$episode19Start && $dataRes[$i]['timeval']<=$episode19End){
										$n++;
										//$labels01.="'".$dataRes[$i]['time']."',";
										
										$labels19.="'".showTime($n)."',";
										$dataGraph19.=$dataRes[$i]['heartrate'].',';
									}
								}
								$labels19=mb_substr($labels19, 0, -1);
								$dataGraph19=mb_substr($dataGraph19, 0, -1);							
							}
						?>
						<? if($episode19){ 
								$heatmap19Url="https://get.smartspeech.eu/heatmap.php?child=".$_GET['item']."&iteration=".$_GET['iteration']."&episode=19";
						
							?>
							<div class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style="font-size:14px;" class="card-title">Κατάσταση: <?=($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->Status=='Complete'?'Ολοκληρώθηκε':'Δεν ολοκληρώθηκε')?> - Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Δραστηριότητα</th>
														<th>Τεχνολογικά μέσα</th>
														<th></th>
														<th>Τομέας αξιολόγησης</th>
													</tr>
												</thead>
												<tbody>
													<tr class="b-top-0">
														<td>???Ηχογράφηση  </td>
														<td>Ηχογράφηση </td>
														<td>
															<audio controls preload="none">
																<source src="https://smartspeech.ddns.net/files/<?=$episode19Files[0]?>" type="audio/wav">
																Your browser does not support the audio element.
															</audio>
														</td>
														<td>Κατονομασία - Άρθρωση </td>
													</tr>
													<tr class="b-top-0">
														<td>xxxΑκουστικές Συχνότητες </td>
														<td>xxxΑκουστικές Συχνότητες </td>
														<td><a href="#episode19Game01"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>xxxΑκουστικές Συχνότητες.</td>
													</tr>
													<tr class="b-top-0">
														<td>xxxΕπιλογή αντικειμένων </td>
														<td>xxxΕπιλογή αντικειμένων </td>
														<td><a href="#episode19Game02"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>xxxΑκουστικές Συχνότητες.</td>
													</tr>
													<tr class="b-top-0">
														<td>xxxΜπαλόνια που σκάνε </td>
														<td>xxxΜπαλόνια που σκάνε </td>
														<td><a href="#episode19Game03"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-link"></i></button></a></td>
														<td>xxxΑκουστικές Συχνότητες.</td>
													</tr>

													<tr class="b-top-0">
														<td style="vertical-align:middle;">gaze</td>
														<td style="vertical-align:middle;">gaze </td>
														<td style="vertical-align:middle;"><a href="#" onClick="MyWindow=window.open('<?=$heatmap19Url?>','MyWindow','width=1200,height=600'); return false;"><button class="btn btn-default" style="margin-left:30px;" type="button"><i class="fas fa-search"></i></button></td>
														<td style="vertical-align:middle;">Εστίαση προσοχής</td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							<div style='margin-top:3px;' class="row" id="episode19Game01">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 1: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Συχνότητα</th>
														<th>Αποτέλεσμα</th>	
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>1000</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Game 01'}->{'Result'}->{'1000'}?></td>
													</tr>
													<tr>
														<td>2000</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Game 01'}->{'Result'}->{'2000'}?></td>
													</tr>
													<tr>
														<td>3000</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Game 01'}->{'Result'}->{'3000'}?></td>
													</tr>
													<tr>
														<td>4000</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Game 01'}->{'Result'}->{'4000'}?></td>
													</tr>
													<tr>
														<td>8000</td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Game 01'}->{'Result'}->{'8000'}?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row" id="episode19Game02">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 2: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Επιλογή χρήστη</th>
														<th>Σωστή επιλογή</th>	
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
														<?
														$jsonVal = $data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Game 02'}->{'Result'};
														echo $jsonVal[0].','.$jsonVal[1].','.$jsonVal[2];
														?>
														</td>
														<td><? //=$data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Game 02'}->{'Result'}?>
														Glue, Fabric, Scissors
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row" id="episode19Game03">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 style='font-size:14px;' class="card-title">Παιχνίδι 3: Αρχή: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->Start,17,2);?> / Τέλος: <?=substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,8,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,5,2).'/'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,0,4).' '.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,11,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,14,2).':'.substr($data[$_GET['iteration']]->EpisodeData->{'Episode 17'}->{'Game 01'}->{'Time'}->End,17,2);?></h2>
										</header>
										<div class="card-body">
											<table class="table table-responsive-md table-striped mb-0">
												<thead>
													<tr>
														<th>Μπαλόνια</th>
														<th>Σύνολο</th>	
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Game 03'}->{'Result'}->{'Hit'}?></td>
														<td><?=$data[$_GET['iteration']]->EpisodeData->{'Episode 19'}->{'Game 03'}->{'Result'}->{'Total'}?></td>
													</tr>
												</tbody>
											</table>
										</div>
									</section>
								</div>
							</div>
							
							<div style='margin-top:3px;' class="row">
								<div class="col">
									<section class="card">
										<header class="card-header">
											<div class="card-actions">
												<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
												<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
											</div>
											<h2 class="card-title">Καρδιακός παλμός</h2>
										</header>
										<div class="card-body">
											<div class="chart-container"><!--  style="position: relative; height:80vh; width:100%" -->
												<canvas id="episode19"></canvas>
											</div>
										</div>
									</section>
								</div>
							</div>
						<? } ?>
					</div>
				</section>
			</div>
		</div>
	</div>
	<script>
	<? if($episode00){ ?>
	var ctx00 = document.getElementById('episode00').getContext('2d');
	var myChart00 = new Chart(ctx00, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels00?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph00?>],
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
	<? }?>
	<? if($episode01){ ?>
	var ctx01 = document.getElementById('episode01').getContext('2d');
	var myChart01 = new Chart(ctx01, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels01?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph01?>],
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
	<? }?>
	<? if($episode02){ ?>
	var ctx02 = document.getElementById('episode02').getContext('2d');
	var myChart02 = new Chart(ctx02, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels02?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph02?>],
				//backgroundColor: ['rgba(255, 99, 132, 0.2)'],
				backgroundColor: [<?=$e2g1bc?>],
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
	<? } ?>
	<? if($episode03){ ?>
	var ctx03 = document.getElementById('episode03').getContext('2d');
	var myChart03 = new Chart(ctx03, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels03?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph03?>],
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
	<? } ?>
	<? if($episode04){?>
	var ctx04 = document.getElementById('episode04').getContext('2d');
	var myChart04 = new Chart(ctx04, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels04?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph04?>],
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
	<? } ?>	
	<? if($episode05){ ?>
	var ctx05 = document.getElementById('episode05').getContext('2d');
	var myChart05 = new Chart(ctx05, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels05?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph05?>],
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
	<? } ?>	
	<? if($episode06){ ?>
	var ctx06 = document.getElementById('episode06').getContext('2d');
	var myChart06 = new Chart(ctx06, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels06?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph06?>],
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
	<? } ?>
	<? if($episode07){ ?>	
	var ctx07 = document.getElementById('episode07').getContext('2d');
	var myChart07 = new Chart(ctx07, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels07?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph07?>],
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
	<? } ?>	
	<? if($episode08){ ?>
	var ctx08 = document.getElementById('episode08').getContext('2d');
	var myChart08 = new Chart(ctx08, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels08?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph08?>],
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
	<? } ?>
	<? if($episode09){ ?>	
	var ctx09 = document.getElementById('episode09').getContext('2d');
	var myChart09 = new Chart(ctx09, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels09?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph09?>],
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
	<? } ?>
	<? if($episode10){ ?>	
	var ctx10 = document.getElementById('episode10').getContext('2d');
	var myChart10 = new Chart(ctx10, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels10?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph10?>],
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
	<? } ?>
	<? if($episode11){ ?>		
	var ctx11 = document.getElementById('episode11').getContext('2d');
	var myChart11 = new Chart(ctx11, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels11?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph11?>],
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
	<? } ?>
	<? if($episode12){ ?>		
	var ctx12 = document.getElementById('episode12').getContext('2d');
	var myChart12 = new Chart(ctx12, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels12?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph12?>],
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
	<? } ?>
	<? if($episode13){ ?>
	var ctx13 = document.getElementById('episode13').getContext('2d');
	var myChart13 = new Chart(ctx13, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels13?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph13?>],
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
	<? } ?>	
	<? if($episode14){ ?>
	var ctx14 = document.getElementById('episode14').getContext('2d');
	var myChart14 = new Chart(ctx14, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels14?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph14?>],
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
	<? } ?>	
	<? if($episode15){ ?>
	var ctx15 = document.getElementById('episode15').getContext('2d');
	var myChart15 = new Chart(ctx15, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels15?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph15?>],
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
	<? } ?>	
	<? if($episode16){ ?>
	var ctx16 = document.getElementById('episode16').getContext('2d');
	var myChart16 = new Chart(ctx16, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels16?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph16?>],
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
	<? } ?>
	<? if($episode17){ ?>
	var ctx17 = document.getElementById('episode17').getContext('2d');
	var myChart17 = new Chart(ctx17, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels17?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph17?>],
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
	<? } ?>
	<? if($episode18){ ?>
	var ctx18 = document.getElementById('episode18').getContext('2d');
	var myChart18 = new Chart(ctx18, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels18?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph18?>],
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
	<? } ?>
	<? if($episode19){ ?>		
	var ctx19 = document.getElementById('episode19').getContext('2d');
	var myChart19 = new Chart(ctx19, {
		type: 'line',
		fill: true,
		data: {
			labels: [<?=$labels19?>],
			datasets: [{
				label: '#',
				data: [<?=$dataGraph19?>],
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
	<? } ?>
	</script>	    
	<?
} else 	{
	//echo 'usertype: '.$auth->UserRow['admin_type'
	?>
		
	<div class="row pt-4 mt-1">
		<div class="col-xl-12">
			<section class="card">
				<header class="card-header card-header-transparent">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
					</div>
	
					<h2 class="card-title">Αποτελέσματα</h2>
				</header>
				<div class="card-body">
					<table class="table table-responsive-md table-striped mb-0">
						<thead>
							<tr>
								<th>#</th>
								<th>Παρατσούκλι</th>
								<th>Ονοματεπώνυμο γονιού</th>
								<th>email</th>
								<th>Κατάσταση</th>
								<th>Παιχνίδια</th>
							</tr>
						</thead>
						<tbody>
						<?
						
							$query = "SELECT t1.*,t2.user_fullname,t2.email FROM children t1 INNER JOIN users t2 ON t1.user_id=t2.user_id WHERE 1=1 ".$filter;
							$result = $db->sql_query($query);
							while ($dr = $db->sql_fetchrow($result)){
								echo '<tr>';
								echo '<td>'.$dr['children_id'].'</td>';
								echo '<td>'.$dr['nickname'].'</td>';
								echo '<td>'.$dr['user_fullname'].'</td>';
								echo '<td>'.$dr['email'].'</td>';
								$resCounterVal=resCounter($dr['children_id']);
								echo '<td>'.($resCounterVal>0?'<span class="badge badge-success">':'<span class="badge badge-danger">').$resCounterVal.($resCounterVal>1?' παιχνίδια':' παιχνίδι').'</span></td>';
								echo '<td>';
								if($resCounterVal>0){
									$data=readCard($dr['children_id']);
									for($i=0;$i<$resCounterVal;$i++){
										echo '<a href=index.php?com=results&item='.$dr['children_id'].'&iteration='.intval($data[$i]->Iteration).'>';
										echo 'Προσπάθεια '.(intval($data[$i]->Iteration)+1).'  '.($data[$i]->EpisodeData->{'Episode 00'}->{'Time'}->Start!=''?substr($data[$i]->EpisodeData->{'Episode 00'}->{'Time'}->Start,8,2).'/'.substr($data[$i]->EpisodeData->{'Episode 00'}->{'Time'}->Start,5,2).'/'.substr($data[$i]->EpisodeData->{'Episode 00'}->{'Time'}->Start,0,4).' '.substr($data[$i]->EpisodeData->{'Episode 00'}->{'Time'}->Start,11,2).':'.substr($data[$i]->EpisodeData->{'Episode 00'}->{'Time'}->Start,14,2).':'.substr($data[$i]->EpisodeData->{'Episode 00'}->{'Time'}->Start,17,2).'<br>':'<br>');
										echo '</a>';
									}
									
								}
								echo '</td>';
								echo '</tr>';										
							}
						?>
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
			
<? } ?> 
				<script>
				$('#openBtn2').on('click',function(){
					$('.modal-body').load('https://get.smartspeech.eu/heatmap.php?child=4&iteration=0&episode=3',function(){
						$('#myModal').modal({show:true});
					});
				});
				</script>
				
<script>
function open_in_new_window(features,element) {
	var new_window;
	if(features !== undefined && features !== '') {
		new_window = window.open('', '_blank', features);
	}
	else {
		new_window = window.open('', '_blank');
	}

	var html_contents = document.getElementById(element);
	if(html_contents !== null) {
		new_window.document.write('<!doctype html><html><head><title>Video Player</title><meta charset="UTF-8" /></head><body style="padding: 0; margin: 0;">' + html_contents.innerHTML + '</body></html>');
	}
}
</script>