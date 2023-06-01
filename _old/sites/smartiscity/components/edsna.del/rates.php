<?php
defined('_VALID_PROCCESS') or die('Direct Access to this location is not allowed.');
//if (!($permissions & $FLAG_DEVICES)){
//	Redirect("index.php");
//}

//require_once(dirname(__FILE__) . "/common.php");
include($config["physicalPath"] . "/languages/" . $auth->LanguageCode . ".php");
global $nav;
$nav = 'Εκπτωτικές κλίμακες';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=rates";
$command = array();
$command = explode("&", $_POST["Command"]);

if($auth->UserType != "Administrator") {
	Redirect("index.php");
}

if(!isset($_SESSION['section_id'])){
	$section=$db->RowSelectorQuery("SELECT * FROM discount_sections WHERE section_id=".$_REQUEST['section_id']);
	if(intval($section['section_id']==0)){
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=sections");
	} else {
		$_SESSION['section_id']=$section['section_id'];
	}
} else {
	$section=$db->RowSelectorQuery("SELECT * FROM discount_sections WHERE section_id=".$_SESSION['section_id']);	
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
			$PrimaryKeys["rate_id"] = intval($_GET["item"]);
			$QuotFields["rate_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}
		$Collector["section_id"] = $_SESSION['section_id'];
		$QuotFields["section_id"] = true;
		
		$Collector["performance"] = $_POST['performance'];
		$QuotFields["performance"] = true;
		
		$Collector["discount"] = $_POST["discount"];
		$QuotFields["discount"] = true;

		$db->ExecuteUpdater("discount_section_rates", $PrimaryKeys, $Collector, $QuotFields);
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "SAVEALL"){
		foreach($_POST as $key => $value) {
			//echo "POST parameter '$key' has '$value'<br>";
			if (strpos($key, 'performance') !== false) {
				$performance = explode("_", $key);
				$update_query="UPDATE discount_section_rates SET performance='".$value."' WHERE rate_id='".$performance[1]."'";
				$db->sql_query($update_query);
			}
			if (strpos($key, 'discount') !== false) {
				$discount=explode("_", $key);
				$update_query="UPDATE discount_section_rates SET discount='".$value."' WHERE rate_id='".$discount[1]."'";
				//echo $update_query.'<br>';
				$db->sql_query($update_query);				
			}
		}
		
	} else if ($_REQUEST["Command"] ==  "DELETE") {
		if ($item != "") {
			$db->sql_query("DELETE FROM discount_section_rates WHERE rate_id=" . $_GET['item']);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}
//}

if (isset($_GET["item"])) {
	$query = "SELECT * FROM discount_section_rates WHERE 1=1 AND rate_id=" . $_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (intval($dr_e['rate_id']) == 0 && intval($_GET['item'])>0) {
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
						<label for="performance" class="control-label">Βαθμός επίδοσης Ο.Τ.Α.</label>
						<div class="controls">
							<input type="text" name="performance" id="performance" required class="input-small valid" data-rule-minlength="2" data-rule-required="true" <?= (isset($dr_e["performance"]) ? 'value="' . $dr_e['performance'] . '"' : "") ?>>%
						</div>
					</div>
					<div class="control-group">						
						<label for="discount" class="control-label">Ποσοστό μείωσης συντελεστών Ο.Τ.Α.</label>
						<div class="controls">
							<input type="text" name="discount" id="discount" required class="input-small valid" data-rule-minlength="2" data-rule-required="true" <?= (isset($dr_e["discount"]) ? 'value="' . $dr_e['discount'] . '"' : "") ?>>%
						</div>
					</div>
				</div>
			</div>
		</div>
		
			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary">Αποθήκευση</button></a>
			<a href="index.php?com=rates"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
		
	</div>

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields() {
			var value_discount = $('#discount').val();
			var value_performance = $('#performance').val();
			if (value_performance.length>=1 && value_discount.length>=1) {
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
					<h3><i class="icon-table"></i><?= $nav ?> (<?=$section['description']?>)</h3>
				</div>
				<div class="box-content nopadding">
					<table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
						<thead>
							<tr>
								<th style="width:15%;">Απόδοση</th>
								<th>Έκπτωση</th>
								<th style="width:20%;">Ενέργειες</th>
							</tr>
						</thead>
						<tbody>
							<?
								$filter=" AND section_id=".$_SESSION['section_id'];
								$query = "SELECT * FROM discount_section_rates WHERE 1=1 ".$filter." ORDER BY performance ASC ";
								$result = $db->sql_query($query);
								$counter = 0;
								while ($dr = $db->sql_fetchrow($result)) {
									?>
								<tr>
									<td><input type="text" name="performance_<?=$dr['rate_id']?>" id="performance_<?=$dr['rate_id']?>" required class="input-small valid" data-rule-minlength="2" data-rule-required="true" <?= (isset($dr["performance"]) ? 'value="' . $dr['performance'] . '"' : "") ?>>%</td>
									<td><input type="text" name="discount_<?=$dr['rate_id']?>" id="discount_<?=$dr['rate_id']?>" required class="input-small valid" data-rule-minlength="2" data-rule-required="true" <?= (isset($dr["discount"]) ? 'value="' . $dr['discount'] . '"' : "") ?>>%</td>
									<td>
										<a href="#" onclick="ConfirmDelete('<?= deleteConfirm ?>','index.php?com=rates&Command=DELETE&item=<?= $dr['rate_id'] ?>');"><span><i class="icon-trash"></i>Διαγραφή</a></span></a>
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
	<a href="index.php?com=sections"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
	<? } ?>
	<script>
		function checkFieldsAll() {
			//var value_description = $('#description').val();
			//if (value_description.length>2) {
				cm('SAVEALL', 1, 0, ''); 
			//}
		}
	</script>