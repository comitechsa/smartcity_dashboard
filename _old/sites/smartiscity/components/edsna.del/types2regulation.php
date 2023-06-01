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
$BaseUrl = "index.php?com=types2regulation";
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
			$PrimaryKeys["types2regulation_id"] = intval($_GET["item"]);
			$QuotFields["types2regulation_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}
		
		$Collector["regulation_id"] = $_POST["regulation_id"];
		$QuotFields["regulation_id"] = true;

		$Collector["wastetype_id"] = $_POST["wastetype_id"];
		$QuotFields["wastetype_id"] = true;

		
		$Collector["remark"] = $_POST["remark"];
		$QuotFields["remark"] = true;	
							
		$db->ExecuteUpdater("types2regulation", $PrimaryKeys, $Collector, $QuotFields);
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if ($_REQUEST["Command"] ==  "DELETE") {
		//διαγραφή μόνο αν δεν υπάρχουν σχετιζόμενα στοιχεία
		if ($item != "") {
			$db->sql_query("DELETE FROM types2regulation WHERE types2regulation_id=" . $_GET['item']);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}
//}

if (isset($_GET["item"])) {
	$query = "SELECT * FROM types2regulation WHERE 1=1 AND types2regulation_id=" . $_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (intval($dr_e['types2regulation_id']) == 0 && intval($_GET['item'])>0) {
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
						<label for="regilation_id" class="control-label">Κανονισμός</label>
						<div class="controls">
						<?
						$queryRegulation="SELECT * FROM regulation WHERE is_valid='True' ORDER BY code ASC";
						$resultRequlation = $db->sql_query($queryRegulation);
							if($db->sql_numrows($resultRequlation) > 0) {
								echo "<select name='regulation_id' id='regulation_id' class='input-large'>";
									while ($dr = $db->sql_fetchrow($resultRequlation))
									{
										echo "<option value=".$dr["regulation_id"]." ".((isset($dr_e["regulation_id"]) && $dr_e["regulation_id"]==$dr['regulation_id']) ? 'selected':'').">".$dr['code'].'-'.$dr['description']."</option>";
									}
								echo "</select>";	
							}
						?>
						</div>
					</div>				

					<div class="control-group">
						<label for="regilation_id" class="control-label">Τύπος αποβλήτου</label>
						<div class="controls">
						<?
						$queryWastetypes="SELECT * FROM wastetypes WHERE is_valid='True' ORDER BY priority ASC";
						$resultWastetypes = $db->sql_query($queryWastetypes);
							if($db->sql_numrows($resultWastetypes) > 0) {
								echo "<select name='wastetype_id' id='wastetype_id' class='input-large'>";
									while ($dr = $db->sql_fetchrow($resultWastetypes))
									{
										echo "<option value=".$dr["wastetype_id"]." ".((isset($dr_e["wastetype_id"]) && $dr_e["wastetype_id"]==$dr['wastetype_id']) ? 'selected':'').">".$dr['description']."</option>";
									}
								echo "</select>";	
							}
						?>
						</div>
					</div>
					
					<div class="control-group">
							<label for="remark" class="control-label">Σημείωση</label>
						<div class="controls">
							<input type="text" name="remark" id="remark" class="input-xxlarge valid" <?= (isset($dr_e["remark"]) ? 'value="' . $dr_e['remark'] . '"' : "") ?>>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary">Αποθήκευση</button></a>
		<a href="index.php?com=types2regulation"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
		
	</div>

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields() {
			//var value_code = $('#code').val();
			//var value_description = $('#description').val();
			//if (value_description.length>2) {
				cm('SAVE', 1, 0, ''); //document.getElementById("submitBtn").disabled = false;
			//} //else {
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
								<th>Κωδικός</th>
								<th>Περιγραφή κανονισμού</th>
								<th>Τύπος αποβλήτου</th>
								<th style="width:30%;">Ενέργεια</th>
							</tr>
						</thead>
						<tbody>
							<?
								$query = "SELECT t1.*,t2.description AS wastetypeDescription,t3.code,t3.description AS regulationDescription
								FROM types2regulation t1 INNER JOIN wastetypes t2 ON t1.wastetype_id=t2.wastetype_id INNER JOIN regulation t3
								ON t1.regulation_id=t3.regulation_id WHERE 1=1 ORDER BY t3.code ASC ";
								$result = $db->sql_query($query);
								$counter = 0;
								while ($dr = $db->sql_fetchrow($result)) {
									?>
								<tr>
									<td><?= $dr["types2regulation_id"] ?></td>
									<td><?= $dr["code"] ?></td>
									<td><?= $dr["regulationDescription"] ?></td>
									<td><?= $dr["wastetypeDescription"] ?></td>
									<td>
										<a style="padding:4px" href="index.php?com=types2regulation&Command=edit&item=<?= $dr["types2regulation_id"] ?>"><i class="icon-edit"></i> Επεξεργασία</a>
										<a href="#" onclick="ConfirmDelete('<?=deleteConfirm ?>','index.php?com=types2regulation&Command=DELETE&item=<?= $dr['types2regulation_id'] ?>');"><span><i class="icon-trash"></i> Διαγραφή</a></span></a>
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