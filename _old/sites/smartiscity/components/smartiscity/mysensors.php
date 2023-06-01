<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

	if($auth->UserType != "Administrator" && intval($auth->UserRow['parent'])!=0) {  
			Redirect("index.php");
	}

global $nav;
$nav = 'Οι αισθητήρες μου';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=mysensors";
$command=array();
$command=explode("&",$_POST["Command"]);

if(isset($_REQUEST["Command"]))
{	
	if($_REQUEST["Command"] == "SAVE")
	{
		
		foreach ($_POST['vars'] as $key=>$value) {
			$varsVar.=$key.',';
		}
		$varsVar = mb_substr($varsVar, 0, -1);

		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		
		if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
		{
			$PrimaryKeys["mysensor_id"] = intval($_GET["item"]);
			$QuotFields["mysensor_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}
		
		if($auth->UserType == "Administrator"){
			$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
			$QuotFields["is_valid"] = true;
		
			$Collector["mysensor_name"] = $_POST["mysensor_name"];
			$QuotFields["mysensor_name"] = true;
				
			$Collector["lat"] = $_POST["lat"];
			$QuotFields["lat"] = true;
				
			$Collector["lng"] = $_POST["lng"];
			$QuotFields["lng"] = true;
		
			$Collector["region_id"] = $_POST["region_id"];
			$QuotFields["region_id"] = true;
			
			$Collector["location"] = $_POST["location"];
			$QuotFields["location"] = true;
			
			$Collector["sensortype_id"] = $_POST["sensortype_id"];
			$QuotFields["sensortype_id"] = true;
			
			$Collector["vars"] = $varsVar;
			$QuotFields["vars"] = true;
		}
		
		if($auth->UserType != "Administrator"){
			$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
			$QuotFields["description"] = true;
		}
		
		$db->ExecuteUpdater("mysensors",$PrimaryKeys,$Collector,$QuotFields);		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") { 
		if($item != ""){
			
			if($auth->UserType != "Administrator") {  
					Redirect("index.php");
			}
			//SOS Έλεγχο πριν τη διαγραφή
			$filter=($auth->UserType!='Administrator'?' AND parent='.$auth->UserId:'');
			//$check=$db->RowSelectorQuery("SELECT * FROM users WHERE 1=1 AND user_id=".$item.$filter);
			if(intval($check['user_id'])>0){
				$db->sql_query("DELETE FROM mysensors WHERE mysensor_id=" . $item);
				$messages->addMessage("DELETED!!!");
			} else {
				$messages->addMessage("NOT FOUND!!!");
			}
			Redirect($BaseUrl);
		}
	}
}


if(isset($_GET["item"])) {
	if(intval($item)>0){
		//$filter=($auth->UserType!='Administrator'?' AND parent='.$auth->UserId:'');		
		$filter=($auth->UserType!='Administrator'?' AND region_id='.$auth->UserRow['region_id']:'');		
	}

	$query="SELECT * FROM mysensors WHERE 1=1 AND mysensor_id=".$_GET['item'].$filter;
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["mysensor_id"]) && intval($_GET['item'])>0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect($BaseUrl);
	}
	?>

	  
	<div id="titlebar" class="gradient">
		<div class="container">
			<div class="row">
				<div class="col-md-12">

					<h2 style="font-family: 'Commissioner', sans-serif;"><i class="sl sl-icon-plus"></i><?=$nav?></h2>
					<!-- Breadcrumbs -->
					<nav id="breadcrumbs">
						<ul>
							<li><a href="<?=$BaseUrl?>">Home</a></li>
							<li><?=$nav?></li>
						</ul>
					</nav>

				</div>
			</div>
		</div>
	</div>
		
	<div class="container" style="margin-bottom:30px;">
		<div class="row">
			<div class="col-lg-12">
				<div id="add-listing" class="separated-form">
					<!-- Section -->
					<div class="add-listing-section">
						<!-- Headline -->
						<div class="add-listing-headline">
							<h3 style="font-family: 'Commissioner', sans-serif;"><i class="sl sl-icon-doc"></i> Στοιχεία αισθητήρα</h3>
						</div>
						<? if($auth->UserType == "Administrator"){?>
						<div class="checkboxes in-row margin-bottom-20">
							<input id="is_valid" class="check-a" type="checkbox" name="is_valid" <?=($dr_e['is_valid']=='True'?'checked':'')?>>
							<label for="is_valid">Ενεργός</label>
						</div>
						<? } ?>
						<div class="row with-forms">
							<div class="col-md-6">
								<label for="mysensor_name">Ονομασία</label>
								<input id="mysensor_name" <?=($auth->UserType != "Administrator"?'readonly':'')?> name="mysensor_name" type="text" <?=(isset($dr_e["mysensor_name"]) ? 'value="'.$dr_e['mysensor_name'].'"' : "")?>>
							</div>
							<div class="col-md-6">
							<? if($auth->UserType != "Administrator") { ?>
								<label for="region_id">Περιοχή</label>
								<select name="region_id" id="region_id" class="chosen-select-no-single" <?=($auth->UserType!='Administrator'?'disabled="disable"':'')?> >
									<option label="blank" value="">Επιλογή περιοχής</option>
									<?
										$filter=" AND is_valid='True'";
										$resultRegions = $db->sql_query("SELECT * FROM regions WHERE 1=1 ".$filter." ORDER BY region_name ");
										while ($drRegions = $db->sql_fetchrow($resultRegions)){
											if($auth->UserType!='Administrator'){
												$currRegion=$auth->UserRow['region_id'];
											} elseif(intval($dr_e['region_id'])>0){
												$currRegion=$dr_e['region_id'];
											}
											echo '<option value="'.$drRegions['region_id'].'" '.($drRegions['region_id']==$currRegion?' selected':'').'>'.$drRegions['region_name'].'</option>';
										}
									?>
								</select>
							<? } ?>
							</div>
						</div>
						
						<div class="row with-forms">
							<div class="col-md-6">
								<label for="location">Διεύθυνση</label>
								
								<input id="location" name="location" <?=($auth->UserType != "Administrator"?'readonly':'')?> type="text" <?=(isset($dr_e["location"]) ? 'value="'.$dr_e['location'].'"' : "")?>>
							</div>
							<div class="col-md-6">
								<label for="sensortype_id">Τύπος αισθητήρα</label>
								<select name="sensortype_id" id="sensortype_id" class="chosen-select-no-single" <?=($auth->UserType!='Administrator'?'disabled="disable"':'')?> >
									<option label="blank" value="">Επιλογή τύπου αισθητήρα</option>
									<?
										$filter=" AND is_valid='True'";
										$resultSensortypes = $db->sql_query("SELECT * FROM sensortypes WHERE 1=1 ".$filter." ORDER BY sensortype_name ");
										while ($drSensortypes = $db->sql_fetchrow($resultSensortypes)){
											echo '<option value="'.$drSensortypes['sensortype_id'].'" '.($drSensortypes['sensortype_id']==$dr_e['sensortype_id']?' selected':'').'>'.$drSensortypes['sensortype_name'].'</option>';
										}
									?>
								</select>
							</div>
					
						</div>
						<? if($auth->UserType == "Administrator") {?>
						<div class="row with-forms" style="margin-top:15px;">
							<?
							$varsArr=explode(",",$dr_e["vars"]);
							$queryVars = "SELECT * FROM sensorvars WHERE 1=1 ".$filter." ORDER BY sensorvar_id ";
							$resultVars = $db->sql_query($queryVars);
							while ($drVars = $db->sql_fetchrow($resultVars))
							{
								?>
								<div class="col-md-3">
									<div class="checkboxes in-row margin-bottom-20">
										<input id="<?=$drVars['sensorvar_id']?>" class="check-a" type="checkbox" name="vars[<?=$drVars['sensorvar_id']?>]" <?=(in_array($drVars['sensorvar_id'], $varsArr)=='True'?'checked':'')?>>
										<label for="<?=$drVars['sensorvar_id']?>"><?=$drVars['sensorvar_name']?></label>
									</div>
								</div>
							<?
							}
							?>
						</div>
						<? } ?>
						<div class="row with-forms" style="margin-top:15px;">
							<fieldset class="gllpLatlonPicker" id="custom_id">
								<? if($auth->UserType == "Administrator") {?>
									<div class="col-sm-8"><input type="text" class="gllpSearchField" style="width:100%;"></div>
									<div class="col-sm-4"><input type="button" class="gllpSearchButton btn btn-primary" value="search" style="margin-bottom:10px;"></div> 
								<? } ?>
								<div class="gllpMap" style="width:100%;height:400px;">Google Maps</div>
								<br/>
								
								<div class="controls">
									<div class="col-sm-6"><input type="text" <?=($auth->UserType != "Administrator"?'readonly':'')?> id="lat" name="lat" class="gllpLatitude" value="<?=(isset($dr_e['lat']) ? $dr_e['lat']:38)?>"/></div>
									<div class="col-sm-6"><input type="text" <?=($auth->UserType != "Administrator"?'readonly':'')?> id="lng" name="lng" class="gllpLongitude" value="<?=(isset($dr_e['lng']) ? $dr_e['lng']:24)?>"/></div>
								</div>
							
								<input type="hidden" class="gllpZoom" value="<?=(isset($dr_e['lat']) ? 14:5)?>"/>
										
							</fieldset>
						</div>
					</div>

					<div class="add-listing-section margin-top-45 padding-top-30">
						<!-- 
						<div class="add-listing-headline">
							<h3><i class="sl sl-icon-docs"></i> Details</h3>
						</div>
						-->

						<div class="form">
							<label for="phone">Σημειώσεις</label>
							<textarea class="WYSIWYG" id="description" name="description" cols="40" rows="3" spellcheck="true"><?=(isset($dr_e["description"]) ? $dr_e['description'] : "")?></textarea>
						</div>
						
						<a href="#" onClick="checkFields();" id="submitBtn" class="button border margin-top-15">Αποθήκευση</a>
						<a href="<?=$BaseUrl?>" class="button border margin-top-15">Επιστροφή</a>
					</div>


				</div>

			</div>
		</div>
	</div>
    


<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#mysensor_name').val();
		if ( value.length >= 2){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
		}
	}
</script>
	<?
} else 	{
	?>    
	
	<div class="container" style="margin-bottom:50px;font-size:14px;">
		<div class="row">
			<div class="col-md-12">
				<h4 class="headline margin-top-70 margin-bottom-30" style="font-family: 'Commissioner', sans-serif;"><?=$nav?></h4>
				<table class="basic-table">
					<thead>
						<tr>
							<th>#</th>
							<th>Ενεργό</th>
							<th>Ονομασία</th>
							<th>Τοποθεσία</th>
							<th>Τύπος</th>
							<th>Φορέας</th>
							<th style="width:15%;">Ενέργεια</th>
						</tr>
					</thead>
					<tbody>
						<?	
							$query = "SELECT * FROM mysensors WHERE 1=1 ".$filter." ORDER BY mysensor_name ";
							$result = $db->sql_query($query);
							$counter = 0;
							while ($dr = $db->sql_fetchrow($result))
							{
								?>
									<tr>
										<td><?=$dr["mysensor_id"]?></td>
										<td><?=$dr["is_valid"]?></td>
										<td><?=$dr["mysensor_name"]?></td>
										<td><?=$dr["lat"].','.$dr["lng"]?></td>
										<td><?=$dr["sensortype_id"]?></td>
										<td><?=$dr["region_id"]?></td>
										<td>
											<a style="padding:4px"  href="index.php?com=mysensors&Command=edit&item=<?=$dr["mysensor_id"]?>"><span style="font-size:22px;" class="im im-icon-File-Edit"></span> </a>
											<?
											if($auth->UserType=='Administrator'){
											?>
											<a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=mysensors&Command=DELETE&item=<?=$dr["mysensor_id"]?>');"><span  style="font-size:22px;" class="im im-icon-Delete-File"></span></a>
											<? } ?>
										</td>
									</tr>
								<?
							}
							$db->sql_freeresult($result);
						?>
					</tbody>

				</table>

			</div>
		</div>
		<div class="row" style="margin-top:30px;">
			<div class="col-md-12">
				<a href="index.php?com=mysensors&item=" class="button border">Εισαγωγή</a><a href="index.php" class="button border">Επιστροφή</a>
			</div>
		</div>
	</div>
<? } ?> 
