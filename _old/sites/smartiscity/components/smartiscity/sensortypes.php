<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

	if($auth->UserType != "Administrator") {  
			Redirect("index.php");
	}

global $nav;
$nav = 'Τύποι αισθητήρων';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=sensortypes";
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
			$PrimaryKeys["sensortype_id"] = intval($_GET["item"]);
			$QuotFields["sensortype_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}

		$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
		$QuotFields["is_valid"] = true;
		
		$Collector["sensortype_name"] = $_POST["sensortype_name"];
		$QuotFields["sensortype_name"] = true;
		
		$Collector["sensortype_color"] = $_POST["sensortype_color"];
		$QuotFields["sensortype_color"] = true;
		
		$Collector["sensortype_icon"] = $_POST["sensortype_icon"];
		$QuotFields["sensortype_icon"] = true;
		
		$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
		$QuotFields["description"] = true;
		
		$db->ExecuteUpdater("sensortypes",$PrimaryKeys,$Collector,$QuotFields);		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") { 
		if($item != ""){
			//SOS Έλεγχο πριν τη διαγραφή
			$db->sql_query("DELETE FROM sensortypes WHERE sensortype_id=" . $item);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}

if(isset($_GET["item"])) {
	$query="SELECT * FROM sensortypes WHERE sensortype_id=".$_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["sensortype_id"]) && intval($_GET['item'])>0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=sensortypes");
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
							<h3 style="font-family: 'Commissioner', sans-serif;"><i class="sl sl-icon-doc"></i> Τύποι αισθητήρων</h3>
						</div>

						<div class="checkboxes in-row margin-bottom-20">
							<input id="is_valid" class="check-a" type="checkbox" name="is_valid" <?=($dr_e['is_valid']=='True'?'checked':'')?>>
							<label for="is_valid">Ενεργό</label>
						</div>
						<div class="row with-forms">
							<div class="col-md-12">
								<label for="sensortype_name">Ονομασία </label>
								<input id="sensortype_name" name="sensortype_name" type="text" <?=(isset($dr_e["sensortype_name"]) ? 'value="'.$dr_e['sensortype_name'].'"' : "")?>>
							</div>
						</div>
						<div class="row with-forms">
							<div class="col-md-12">
								<label for="sensortype_icon">Εικονίδιο </label>
								<input id="sensortype_icon" name="sensortype_icon" type="text" <?=(isset($dr_e["sensortype_icon"]) ? 'value="'.$dr_e['sensortype_icon'].'"' : "")?>>
							</div>
						</div>
						<div class="row with-forms">
							<div class="col-md-12">
								<label for="sensortype_color">Χρώμα </label>
								<input id="sensortype_color" name="sensortype_color" type="text" <?=(isset($dr_e["sensortype_color"]) ? 'value="'.$dr_e['sensortype_color'].'"' : "")?>>
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
	var value = $('#sensortype_name').val();
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
							<th>Ημ/νία εισαγωγής</th>
							<th style="width:25%;">Ενέργεια</th>
						</tr>
					</thead>
					<tbody>
						<?	
							$query = "SELECT * FROM sensortypes WHERE 1=1 ".$filter." ORDER BY sensortype_name ";
							$result = $db->sql_query($query);
							$counter = 0;
							while ($dr = $db->sql_fetchrow($result))
							{
								?>

									<tr>
										<td><?=$dr["sensortype_id"]?></td>
										<td><?=$dr["is_valid"]?></td>
										<td><?=$dr["sensortype_name"]?></td>
										<td><?=$dr["date_insert"]?></td>
										<td>
											<a style="padding:4px"  href="index.php?com=sensortypes&Command=edit&item=<?=$dr["sensortype_id"]?>"><span style="font-size:22px;" class="im im-icon-File-Edit"></span> </a>
											<a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=sensortypes&Command=DELETE&item=<?=$dr["sensortype_id"]?>');"><span  style="font-size:22px;" class="im im-icon-Delete-File"></span> </a></a>
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
				<a href="index.php?com=sensortypes&item=" class="button border">Εισαγωγή</a><a href="index.php" class="button border">Επιστροφή</a>
			</div>
		</div>
	</div>
<? } ?> 
