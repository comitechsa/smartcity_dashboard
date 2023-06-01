<?php
defined('_VALID_PROCCESS') or die('Direct Access to this location is not allowed.');
//if (!($permissions & $FLAG_DEVICES)){
//	Redirect("index.php");
//}

//require_once(dirname(__FILE__) . "/common.php");
include($config["physicalPath"] . "/languages/" . $auth->LanguageCode . ".php");
global $nav;
$nav = 'Εκπτωτικές ενότητες';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=sections";
$command = array();
$command = explode("&", $_POST["Command"]);

if($auth->UserType != "Administrator") {
	Redirect("index.php");
}
unset($_SESSION["section_id"]);

if(!isset($_SESSION['discount_id'])){
	$discount=$db->RowSelectorQuery("SELECT * FROM discounts WHERE discount_id=".$_REQUEST['discount_id']);
	if(intval($discount['discount_id']==0)){
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=discounts");
	} else {
		$_SESSION['discount_id']=$discount['discount_id'];
	}
} else {
	$discount=$db->RowSelectorQuery("SELECT * FROM discounts WHERE discount_id=".$_SESSION['discount_id']);	
}




//if( $auth->UserType == "Administrator" )
//{
	//http://edsna.wan.gr/index.php?com=sections&item=1&section_item=
if (isset($_REQUEST["Command"])) {
	if ($_REQUEST["Command"] == "SAVE") {
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();

		if (isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"]) > 0) {
			$PrimaryKeys["section_id"] = intval($_GET["item"]);
			$QuotFields["section_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}

		$Collector["priority"] = $_POST["priority"];
		$QuotFields["priority"] = true;

		$Collector["discount_id"] = $_SESSION['discount_id'];
		$QuotFields["discount_id"] = true;
		
		$Collector["description"] = $_POST["description"];
		$QuotFields["description"] = true;

		$db->ExecuteUpdater("discount_sections", $PrimaryKeys, $Collector, $QuotFields);
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "SAVEALL"){
		foreach($_POST as $key => $value) {
			//echo "POST parameter '$key' has '$value'<br>";
			if (strpos($key, 'priority') !== false) {
				$section = explode("_", $key);
				$update_query="UPDATE discount_sections SET priority='".$value."' WHERE section_id='".$section[1]."'";
				//echo $update_query.'<br>';
				$db->sql_query($update_query);
			}
			if (strpos($key, 'description') !== false) {
				$description=explode("_", $key);
				$update_query="UPDATE discount_sections SET description='".$value."' WHERE section_id='".$description[1]."'";
				//echo $update_query.'<br>';
				$db->sql_query($update_query);				
			}
		}
		
	} else if ($_REQUEST["Command"] ==  "DELETE") {
		if ($item != "") {
			$db->sql_query("DELETE FROM discount_sections WHERE section_id=" . $_GET['item']);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}
//}

if (isset($_GET["item"])) {
	$query = "SELECT * FROM discount_sections WHERE 1=1 AND section_id=" . $_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (intval($dr_e['section_id']) == 0 && intval($_GET['item'])>0) {
		$messages->addMessage("NOT FOUND!!!");
		//Redirect($BaseUrl);
	}
	?>
	<div class="breadcrumbs">
		<ul>
			<li>
				<a href="index.php">Αρχική</a>
				<i class="icon-angle-right"></i>
			</li>
			<li>
				<a href="<?= $BaseUrl ?>"><?= $nav ?></a>
			</li>
		</ul>
	</div>

	<div class="row-fluid">
		<div class="span12" style="margin-bottom:20px;">
			<div class="box box-bordered"><!-- box-bordered box-color-->
				<div class="box-title">
					<h3><i class="icon-edit"></i>Εισαγωγή</h3>
				</div>
				<div class="box-content nopadding form-horizontal form-bordered">
					<div class="control-group">						
						<label for="priority" class="control-label">Σειρά εμφάνισης</label>
						<div class="controls">
							<input type="text" name="priority" id="priority" required class="input-small valid" data-rule-minlength="2" data-rule-required="true" <?= (isset($dr_e["priority"]) ? 'value="' . $dr_e['priority'] . '"' : "") ?>>
						</div>
					</div>

					<div class="control-group">
							<label for="description" class="control-label">Περιγραφή</label>
						<div class="controls">
							<input type="text" name="description" id="description" class="input-xxlarge valid" <?= (isset($dr_e["description"]) ? 'value="' . $dr_e['description'] . '"' : "") ?>>
						</div>
					</div>
				</div>
			</div>
		</div>
		
			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary">Αποθήκευση</button></a>
			<a href="index.php?com=sections"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
		
	</div>

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields() {
			var value_description = $('#description').val();
			if (value_description.length>2) {
				cm('SAVE', 1, 0, ''); //document.getElementById("submitBtn").disabled = false;
			} //else {
			//document.getElementById("submitBtn").disabled = true;
			//alert('2 chars');
			//}
		}
	</script>
<?
} else {
	?>
	<div class="row-fluid" style="margin-bottom:20px;">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title">
					<h3><i class="icon-table"></i><?= $nav ?> (<?=$discount['description']?>)</h3>
				</div>
				<div class="box-content nopadding">
					<table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
						<thead>
							<tr>
								<th style="width:15%;">Σειρά</th>
								<th>Περιγραφή</th>
								<th style="width:20%;">Ενέργειες</th>
							</tr>
						</thead>
						<tbody>
							<?
								$filter=" AND discount_id=".$_SESSION['discount_id'];
								$query = "SELECT * FROM discount_sections WHERE 1=1 ".$filter." ORDER BY priority ASC ";
								$result = $db->sql_query($query);
								$counter = 0;
								while ($dr = $db->sql_fetchrow($result)) {
									?>
								<tr>
									<td><?//= $dr["priority"] ?><input type="text" name="priority_<?=$dr['section_id']?>" id="priority_<?=$dr['section_id']?>" required class="input-small valid" data-rule-minlength="2" data-rule-required="true" <?= (isset($dr["priority"]) ? 'value="' . $dr['priority'] . '"' : "") ?>></td>
									<td><?//= $dr["description"] ?><textarea name="description_<?=$dr['section_id']?>" rows="3" id="description_<?=$dr['section_id']?>" class="input-block-level"><?=(isset($dr["description"]) ? $dr["description"] : "")?></textarea></td>
									<td>
										<a style="padding:4px" href="index.php?com=rates&section_id=<?= $dr["section_id"] ?>"><i class="icon-edit"></i> Κλίμακες</a>
										<a href="#" onclick="ConfirmDelete('<?= deleteConfirm ?>','index.php?com=sections&Command=DELETE&item=<?= $dr['section_id'] ?>');"><span><i class="icon-trash"></i> Διαγραφή</a></span></a>
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
		</div>
	</div>
	<a href="#" onClick="checkFieldsAll();"><button id="submitBtn" type="button" class="btn btn-primary">Αποθήκευση</button></a>
	<a href="index.php?com=discounts"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
	<? } ?>
	<script>
		function checkFieldsAll() {
			//var value_description = $('#description').val();
			//if (value_description.length>2) {
				cm('SAVEALL', 1, 0, ''); 
			//}
		}
	</script>