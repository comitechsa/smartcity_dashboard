<?php
defined('_VALID_PROCCESS') or die('Direct Access to this location is not allowed.');
//if (!($permissions & $FLAG_DEVICES)){
//	Redirect("index.php");
//}

//require_once(dirname(__FILE__) . "/common.php");
include($config["physicalPath"] . "/languages/" . $auth->LanguageCode . ".php");
global $nav;
$nav = 'Τύποι αποβλήτων';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=wastetypes";
$command = array();
$command = explode("&", $_POST["Command"]);

if($auth->UserType != "Administrator") {
	Redirect("index.php");
}


//if( $auth->UserType == "Administrator" )
//{
if (isset($_REQUEST["Command"])) {
	if ($_REQUEST["Command"] == "SAVE") {
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();

		if (isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"]) > 0) {
			$PrimaryKeys["wastetype_id"] = intval($_GET["item"]);
			$QuotFields["wastetype_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}
		
		$Collector["description"] = $_POST["description"];
		$QuotFields["description"] = true;

		$db->ExecuteUpdater("wastetypes", $PrimaryKeys, $Collector, $QuotFields);
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if ($_REQUEST["Command"] ==  "DELETE") {
		//διαγραφή μόνο αν δεν υπάρχουν σχετιζόμενα στοιχεία
		if ($item != "") {
			$db->sql_query("DELETE FROM wastetypes WHERE wastetype_id=" . $_GET['item']);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}
//}

if (isset($_GET["item"])) {
	$query = "SELECT * FROM wastetypes WHERE 1=1 AND wastetype_id=" . $_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (intval($dr_e['wastetype_id']) == 0 && intval($_GET['item'])>0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect($BaseUrl);
	}
	?>
	<div class="breadcrumbs">
		<ul>
			<li><a href="index.php">Αρχική</a><i class="icon-angle-right"></i></li>
			<li><a href="<?=$BaseUrl ?>"><?=$nav?></a><i class="icon-angle-right"></i></li>
			<li><a href="#">Επεξεργασία</a></li>
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
							<label for="description" class="control-label">Περιγραφή</label>
						<div class="controls">
							<input type="text" name="description" id="description" class="input-xxlarge valid" <?= (isset($dr_e["description"]) ? 'value="' . $dr_e['description'] . '"' : "") ?>>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary">Αποθήκευση</button></a>
		<a href="index.php?com=wastetypes"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
		
	</div>

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields() {
			//var value_code = $('#code').val();
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
	<div class="row-fluid">
		<div class="span12">
		
			<div class="breadcrumbs">
				<ul>
					<li><a href="index.php">Αρχική</a><i class="icon-angle-right"></i></li>
					<li><a href="<?=$baseURL?>"><?=$nav?></a></li>
				</ul>
				<div class="close-bread">
					<a href="#"><i class="icon-remove"></i></a>
				</div>
			</div>
				
			<div class="box box-color box-bordered">
				<div class="box-title">
					<h3><i class="icon-table"></i><?= $nav ?></h3>
				</div>
				<div class="box-content nopadding">
					<table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
						<thead>
							<tr>
								<th style="width:10%;">#</th>
								<th>Περιγραφή</th>
								<th style="width:30%;">Ενέργεια</th>
							</tr>
						</thead>
						<tbody>
							<?
								$query = "SELECT * FROM wastetypes WHERE 1=1 ORDER BY description ASC ";
								$result = $db->sql_query($query);
								$counter = 0;
								while ($dr = $db->sql_fetchrow($result)) {
									?>
								<tr>
									<td><?= $dr["wastetype_id"] ?></td>
									<td><?= $dr["description"] ?></td>
									<td>
										<a style="padding:4px" href="index.php?com=wastetypes&Command=edit&item=<?= $dr["wastetype_id"] ?>"><i class="icon-edit"></i> Επεξεργασία</a>
										<a href="#" onclick="ConfirmDelete('<?= deleteConfirm ?>','index.php?com=wastetypes&Command=DELETE&item=<?= $dr['wastetype_id'] ?>');"><span><i class="icon-trash"></i> Διαγραφή</a></span></a>
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