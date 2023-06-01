<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

	if($auth->UserType != "Administrator") {  
			Redirect("index.php");
	}

global $nav;
$nav = 'Μεταβλητές αισθητήρων';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=sensorvars";
$command=array();
$command=explode("&",$_POST["Command"]);

if(isset($_REQUEST["Command"]))
{	
	if($_REQUEST["Command"] == "SAVE")
	{
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		
		if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
		{
			$PrimaryKeys["sensorvar_id"] = intval($_GET["item"]);
			$QuotFields["sensorvar_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}

		$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
		$QuotFields["is_valid"] = true;
		
		$Collector["sensorvar_name"] = $_POST["sensorvar_name"];
		$QuotFields["sensorvar_name"] = true;

		$Collector["sensorvar_description"] = $_POST["sensorvar_description"];
		$QuotFields["sensorvar_description"] = true;
			
		$Collector["sensorvar_unit"] = $_POST["sensorvar_unit"];
		$QuotFields["sensorvar_unit"] = true;
		
		$Collector["sensorvar_dec"] = $_POST["sensorvar_dec"];
		$QuotFields["sensorvar_dec"] = true;
		


		$Collector["sensorvar_icon"] = $_POST["sensorvar_icon"];
		$QuotFields["sensorvar_icon"] = true;
		
		$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
		$QuotFields["description"] = true;
		
		$db->ExecuteUpdater("sensorvars",$PrimaryKeys,$Collector,$QuotFields);		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") { 
		if($item != ""){
			//SOS Έλεγχο πριν τη διαγραφή
			$db->sql_query("DELETE FROM sensorvars WHERE sensorvar_id=" . $item);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}

if(isset($_GET["item"])) {
	$query="SELECT * FROM sensorvars WHERE sensorvar_id=".$_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["sensorvar_id"]) && intval($_GET['item'])>0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=sensorvars");
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
							<li><a href="#">Home</a></li>
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
							<h3 style="font-family: 'Commissioner', sans-serif;"><i class="sl sl-icon-doc"></i> <?=$nav?></h3>
						</div>

						<div class="checkboxes in-row margin-bottom-20">
							<input id="is_valid" class="check-a" type="checkbox" name="is_valid" <?=($dr_e['is_valid']=='True'?'checked':'')?>>
							<label for="is_valid">Ενεργό</label>
						</div>
						<div class="row with-forms">
							<div class="col-md-12">
								<label for="sensorvar_name">Ονομασία </label>
								<input id="sensorvar_name" name="sensorvar_name" type="text" <?=(isset($dr_e["sensorvar_name"]) ? 'value="'.$dr_e['sensorvar_name'].'"' : "")?>>
							</div>
						</div>
						
						<div class="row with-forms">
							<div class="col-md-12">
								<label for="sensorvar_description">Περιγραφή </label>
								<input id="sensorvar_description" name="sensorvar_description" type="text" <?=(isset($dr_e["sensorvar_description"]) ? 'value="'.$dr_e['sensorvar_description'].'"' : "")?>>
							</div>
						</div>
						
						
						<div class="row with-forms">
							<div class="col-md-6">
								<label for="sensorvar_unit">Μονάδα μέτρησης </label>
								<input id="sensorvar_unit" name="sensorvar_unit" type="text" <?=(isset($dr_e["sensorvar_unit"]) ? 'value="'.$dr_e['sensorvar_unit'].'"' : "")?>>
							</div>
							<div class="col-md-6">
								<label for="sensorvar_dec">Δεκαδικά ψηφία </label>
								<input id="sensorvar_dec" name="sensorvar_dec" type="text" <?=(isset($dr_e["sensorvar_dec"]) ? 'value="'.$dr_e['sensorvar_dec'].'"' : "")?>>
							</div>							
							
						</div>	
						
						<div class="row with-forms">
							<div class="col-md-12">
								<label for="sensorvar_icon">Εικονίδιο </label>
								<input id="sensorvar_icon" name="sensorvar_icon" type="text" <?=(isset($dr_e["sensorvar_icon"]) ? 'value="'.$dr_e['sensorvar_icon'].'"' : "")?>>
							</div>
						</div>			
						
					</div>

					<div class="add-listing-section margin-top-45 padding-top-30">
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
	var value = $('#sensorvar_name').val();
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
							<th>Περιγραφή</th>
							<th>Ημ/νία εισαγωγής</th>
							<th style="width:10%;">Ενέργεια</th>
						</tr>
					</thead>
					<tbody>
						<?	
							$query = "SELECT * FROM sensorvars WHERE 1=1 ".$filter." ORDER BY sensorvar_name ";
							$result = $db->sql_query($query);
							$counter = 0;
							while ($dr = $db->sql_fetchrow($result))
							{
								?>

									<tr>
										<td><?=$dr["sensorvar_id"]?></td>
										<td><?=$dr["is_valid"]?></td>
										<td><?=$dr["sensorvar_name"]?></td>
										<td><?=$dr["sensorvar_description"]?></td>
										<td><?=$dr["date_insert"]?></td>
										<td>
											<a style="padding:4px"  href="index.php?com=sensorvars&Command=edit&item=<?=$dr["sensorvar_id"]?>"><span style="font-size:22px;" class="im im-icon-File-Edit"></span> </a>
											<a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=sensorvars&Command=DELETE&item=<?=$dr["sensorvar_id"]?>');"><span  style="font-size:22px;" class="im im-icon-Delete-File"></span> </a></a>
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
				<a href="index.php?com=sensorvars&item=" class="button border">Εισαγωγή</a><a href="index.php" class="button border">Επιστροφή</a>
			</div>
		</div>
	</div>
<? } ?> 
