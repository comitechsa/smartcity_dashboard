<?php
defined('_VALID_PROCCESS') or die('Direct Access to this location is not allowed.');
//if (!($permissions & $FLAG_DEVICES)){
//	Redirect("index.php");
//}

//require_once(dirname(__FILE__) . "/common.php");
include($config["physicalPath"] . "/languages/" . $auth->LanguageCode . ".php");
global $nav;
$nav = 'Εκπτώσεις';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=discounts";
$command = array();
$command = explode("&", $_POST["Command"]);

if($auth->UserType != "Administrator") {
	Redirect("index.php");
}
unset($_SESSION["discount_id"]);


//if( $auth->UserType == "Administrator" )
//{
if (isset($_REQUEST["Command"])) {
	if ($_REQUEST["Command"] == "SAVE") {
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();

		if (isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"]) > 0) {
			$PrimaryKeys["discount_id"] = intval($_GET["item"]);
			$QuotFields["discount_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}

		$Collector["is_valid"] = ($_POST["is_valid"] == "on" ? "True" : "False");
		$QuotFields["is_valid"] = true;

		$Collector["description"] = $_POST["description"];
		$QuotFields["description"] = true;

		$Collector["period_id"] = $_POST["period_id"];
		$QuotFields["period_id"] = true;

		$db->ExecuteUpdater("discounts", $PrimaryKeys, $Collector, $QuotFields);
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if ($_REQUEST["Command"] ==  "DELETE") {
		if ($item != "") {
			$db->sql_query("DELETE FROM discounts WHERE discount_id=" . $_GET['item']);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}
//}

if (isset($_GET["item"])) {
	$query = "SELECT * FROM discounts WHERE 1=1 AND discount_id=" . $_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (intval($dr_e['discount_id']) == 0 && intval($_GET['item'])>0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect($BaseUrl);
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
					<h3><i class="icon-edit"></i>Επεξεργασία</h3>
				</div>
				<div class="box-content nopadding form-horizontal form-bordered">
					<div class="control-group">
						<label class="control-label" for="is_valid">Ενεργό</label>
						<div class="controls">
							<input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?= ((isset($dr_e["is_valid"]) && $dr_e["is_valid"] == 'True') ? 'checked' : '') ?> />
						</div>
						<!--
						<label class="inline" for="is_valid" class="control-label">Ενεργό</label>
						<div class="check-line">
						</div>
						-->
					</div>

					<div class="control-group">
						<label for="period_id" class="control-label">Περίοδος</label>
						<div class="controls">
						<?
							$query = "SELECT * FROM periods WHERE 1=1 ".$filter." ORDER BY year";
							echo Select::GetDbRender("period_id", $query, "period_id", "year", (isset($dr_e["period_id"]) ? $dr_e["period_id"] : ""), true);
						 ?>
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
			<a href="index.php?com=discounts"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
		
	</div>

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields() {
			var value_year = $('#year').val();
			var value_description = $('#description').val();
			if (value_year.length == 4 && value_description.length>2) {
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
	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title">
					<h3><i class="icon-table"></i><?= $nav ?></h3>
				</div>
				<div class="box-content nopadding">
					<table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
						<thead>
							<tr>
								<th style="width:10%;">#</th>
								<th style="width:10%;">Ενεργό</th>
								<th style="width:10%;">Έτος</th>
								<th>Περιγραφή</th>
								<th style="width:30%;">Ενέργειες</th>
							</tr>
						</thead>
						<tbody>
							<?
								$query = "SELECT t1.*,t2.year FROM discounts t1 INNER JOIN periods t2 ON t1.period_id=t2.period_id ";
								$result = $db->sql_query($query);
								$counter = 0;
								while ($dr = $db->sql_fetchrow($result)) {
									?>
								<tr>
									<td><?= $dr["discount_id"] ?></td>
									<td><?= $dr["is_valid"] ?></td>
									<td><?= $dr["year"] ?></td>
									<td><?= $dr["description"] ?></td>
									<td>
										<a style="padding:4px" href="index.php?com=discounts&Command=edit&item=<?= $dr["discount_id"] ?>"><i class="icon-edit"></i> Επεξεργασία</a>
										<a style="padding:4px" href="index.php?com=sections&discount_id=<?= $dr["discount_id"] ?>"><i class="icon-edit"></i> Ενότητες</a>
										<a href="#" onclick="ConfirmDelete('<?= deleteConfirm ?>','index.php?com=discounts&Command=DELETE&item=<?= $dr['discount_id'] ?>');"><span><i class="icon-trash"></i> Διαγραφή</a></span></a>
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
	<? } ?>