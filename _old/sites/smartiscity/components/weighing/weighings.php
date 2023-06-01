<?php
defined('_VALID_PROCCESS') or die('Direct Access to this location is not allowed.');
//if (!($permissions & $FLAG_DEVICES)){
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") {
//	Redirect("index.php");
//}

//require_once(dirname(__FILE__) . "/common.php");
include($config["physicalPath"] . "/languages/" . $auth->LanguageCode . ".php");
global $nav;
$nav = 'Ζυγίσεις';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=vehicles";
$command = array();
$command = explode("&", $_POST["Command"]);

if( $auth->UserType == "Administrator" ){
	if (isset($_REQUEST["Command"])) {
		if ($_REQUEST["Command"] == "SAVE") {
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();

			if (isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"]) > 0) {
				$PrimaryKeys["vehicle_id"] = intval($_GET["item"]);
				$QuotFields["vehicle_id"] = true;
			} else {
				$Collector["date_insert"] = date('Y-m-d H:i:s');
				$QuotFields["date_insert"] = true;
			}

			$Collector["is_valid"] = ($_POST["is_valid"] == "on" ? "True" : "False");
			$QuotFields["is_valid"] = true;

			$Collector["plate"] = $_POST["plate"];
			$QuotFields["plate"] = true;

			$Collector["imei"] = $_POST["imei"];
			$QuotFields["imei"] = true;		
			

			$Collector["description"] = $_POST["description"];
			$QuotFields["description"] = true;

			$Collector["municipality_id"] = $_POST["municipality_id"];
			$QuotFields["municipality_id"] = true;

			$db->ExecuteUpdater("vehicles", $PrimaryKeys, $Collector, $QuotFields);
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if ($_REQUEST["Command"] ==  "DELETE") {
			if ($item != "") {
				$filter = ($auth->UserType != "Administrator" ? ' AND municipality_id=' . $auth->UserRow['municipality_id'] : '');
				$checkDelete = $db->sql_query("SELECT * FROM vehicles WHERE 1=1 " . $filter . " AND vehicle_id=" . $_GET['item']);
				if ($db->sql_numrows($checkDelete) == 0) {
					$messages->addMessage("NOT FOUND.");
					Redirect($BaseUrl);
				}
				$db->sql_query("DELETE FROM vehicles WHERE vehicle_id=" . $_GET['item']);
				$messages->addMessage("DELETED!!!");
				Redirect($BaseUrl);
			}
		}
	}
}

if (isset($_GET["item"])) {
	$filter = ($auth->UserType != "Administrator" ? ' AND municipality_id=' . $auth->UserRow['municipality_id'] : '');
	$query = "SELECT * FROM vehicles WHERE 1=1 " . $filter . " AND vehicle_id=" . $_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (intval($dr_e['vehicle_id']) == 0 && intval($_GET['item'])>0) {
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
		<div class="span12">
			<div class="box-title">
				<h3><i class="icon-user"></i>Επεξεργασία</h3>
			</div>
			<div class="box-content nopadding">
				<div class="check-line">
					<label class="inline" for="is_valid"><?= active ?></label>
					<div class="controls">
						<input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?= ((isset($dr_e["is_valid"]) && $dr_e["is_valid"] == 'True') ? 'checked' : '') ?> />
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<label for="plate" class="control-label">Πινακίδα</label>
						<input type="text" name="plate" id="plate" required class="input-small valid" data-rule-minlength="2" data-rule-required="true" <?= (isset($dr_e["plate"]) ? 'value="' . $dr_e['plate'] . '"' : "") ?>>
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<label for="imei" class="control-label">ΙΜΕΙ</label>
						<input type="text" name="imei" id="imei" required class="input-small valid" data-rule-minlength="2" data-rule-required="true" <?= (isset($dr_e["imei"]) ? 'value="' . $dr_e['imei'] . '"' : "") ?>>
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<label for="description" class="control-label">Περιγραφή</label>
						<input type="text" name="description" id="description" class="input-xxlarge valid" <?= (isset($dr_e["description"]) ? 'value="' . $dr_e['description'] . '"' : "") ?>>
					</div>
				</div>
				<?if($auth->UserType == "Administrator") {?>
				<div class="control-group">
					<div class="controls">
						<label for="textfield" class="control-label">Δήμος</label>
						<?
							$query = "SELECT * FROM municipalities WHERE 1=1 " . $filter . " ORDER BY name";
							echo Select::GetDbRender("municipality_id", $query, "municipality_id", "name", (isset($dr_e["municipality_id"]) ? $dr_e["municipality_id"] : ""), true);
							?>
					</div>
				</div>
				<? } ?>
			</div>
		</div>
		<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary">Αποθήκευση</button></a>
		<a href="index.php?com=vehicles"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
	</div>

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields() {
			var value = $('#plate').val();
			if (value.length >= 2) {
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
								<th>#</th>
								<th>Πινακίδα</th>
								<th>Ζύγιση</th>
								<!--<th>Καύσιμα</th>-->
								<th>Ημερομηνία</th>
								<!--<th>Ενέργειες</th>-->
							</tr>
						</thead>
						<tbody>
							<?
								$filter = ($auth->UserType != "Administrator" ? ' AND t2.municipality_id=' . $auth->UserRow['municipality_id'] : '');
								$filter .=' AND weight>0 ';
								$query = "SELECT t1.*,t2.vehicle_id,t2.plate FROM data t1 INNER JOIN vehicles t2 ON t1.gpsID=t2.imei WHERE 1=1  " . $filter . " ORDER BY t1.gpsdate ";
								
								$result = $db->sql_query($query);
								$counter = 0;
								while ($dr = $db->sql_fetchrow($result)) {
									?>
								<tr>
									<td><?= $dr["id"] ?></td>
									<td><?= $dr["plate"] ?></td>
									<td><?= number_format($dr["weight"],0) ?></td>
									<!--<td><? //=$dr["fuel_norm"] ?></td>-->
									<td><?= $dr["gpsdate"] ?></td>
									<!--<td>-->
									<?if($auth->UserType == "Administrator") {?>
										<!--<a style="padding:4px" href="index.php?com=data&Command=edit&item=<?//= $dr["vehicle_id"] ?>"><i class="icon-edit"></i> Επεξεργασία</a>-->
										<!--<a href="#" onclick="ConfirmDelete('<?//= deleteConfirm ?>','index.php?com=data&Command=DELETE&item=<?//= $dr['vehicle_id'] ?>');"><span><i class="icon-trash"></i>Διαγραφή</a></span></a>-->
									<? } ?>
									<!--</td>-->
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