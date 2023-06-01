<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

	if($auth->UserType != "Administrator") {  
			Redirect("index.php");
	}

global $nav;
$nav = 'Περιφέρειες';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=regions";
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
			$PrimaryKeys["region_id"] = intval($_GET["item"]);
			$QuotFields["region_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}

		//$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
		//$QuotFields["is_valid"] = true;
		
		$Collector["region_name"] = $_POST["region_name"];
		$QuotFields["region_name"] = true;
		
		$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
		$QuotFields["description"] = true;
		
		$db->ExecuteUpdater("regions",$PrimaryKeys,$Collector,$QuotFields);		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") { 
		if($item != ""){
			//SOS Έλεγχο πριν τη διαγραφή
			$db->sql_query("DELETE FROM regions WHERE region_id=" . $item);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}


if(isset($_GET["item"])) {
	$query="SELECT * FROM regions WHERE region_id=".$_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["region_id"]) && intval($_GET['item'])>0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=regions");
	}
	?>
	<div  style="padding:30px;"> <!-- class="dashboard-content"-->

		<!-- Titlebar -->
		<div id="titlebar" style="padding: 15px;margin-bottom: 6px;margin-left: 30px;margin-right: 30px;background-color:#eee;">
			<div class="row">
				<div class="col-md-12">
					<h2>Περιοχές</h2>
					<!-- Breadcrumbs -->
					<nav id="breadcrumbs">
						<ul>
							<li><a href="#">Home</a></li>
							<li><a href="#"><?=$nav?></a></li>
							<li><?=edit?></li>
						</ul>
					</nav>
				</div>
			</div>
		</div>

		<div class="row">

			<!-- Profile -->
			<div class="col-lg-12 col-md-12">
				<div class="dashboard-list-box margin-top-0">
					<div class="dashboard-list-box-static">
						<div class="my-profile">

							<label>Ονομασία περιοχής</label>
							<input id="region_name" name="region_name" type="text" <?=(isset($dr_e["region_name"]) ? 'value="'.$dr_e['region_name'].'"' : "")?>>

							<label>Περιγραφή</label>
							<textarea id="description" name="description" cols="30" rows="10"><?=(isset($dr_e["description"]) ? $dr_e['description'] : "")?></textarea>
						</div>
						<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="button margin-top-15">Αποθήκευση</button></a>
						<a href="index.php?com=regions"><button type="button" class="button margin-top-15">Επιστροφή</button></a>

					</div>
				</div>
			</div>
		</div>
	</div>
    


<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#region_name').val();
		if ( value.length >= 2){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
		}
	}
</script>
	<?
} else 	{
	?>    
	
	<div class="container" style="margin-bottom:50px;">
		<div class="row">
			<div class="col-md-12">
				<h4 class="headline margin-top-70 margin-bottom-30">Περιοχές</h4>
				<table class="basic-table">
					<thead>
						<tr>
							<th>#</th>
							<th>Ονομασία</th>
							<th>Ημ/νία εισαγωγής</th>
							<th style="width:25%;">Ενέργεια</th>
						</tr>
					</thead>
					<tbody>
						<?	
							$query = "SELECT * FROM regions WHERE 1=1 ".$filter." ORDER BY region_name ";
							$result = $db->sql_query($query);
							$counter = 0;
							while ($dr = $db->sql_fetchrow($result))
							{
								?>

									<tr>
										<td><?=$dr["region_id"]?></td>
										<td><?=$dr["region_name"]?></td>
										<td><?=$dr["date_insert"]?></td>
										<td>
											<a style="padding:4px"  href="index.php?com=regions&Command=edit&item=<?=$dr["region_id"]?>"><span style="font-size:28px;" class="im im-icon-File-Edit"></span> <?=edit?></a>
											<a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=regions&Command=DELETE&item=<?=$dr["region_id"]?>');"><span  style="font-size:28px;" class="im im-icon-Delete-File"></span><?=delete?></a></a>
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
				<a href="index.php?com=regions&item=" class="button border">Εισαγωγή</a><a href="index.php" class="button border">Επιστροφή</a>
			</div>
		</div>
	</div>
<? } ?> 
