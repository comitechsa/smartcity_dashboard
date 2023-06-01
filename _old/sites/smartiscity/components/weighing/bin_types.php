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
$nav = 'Τύποι κάδων';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=bin_types";
$command = array();
$command = explode("&", $_POST["Command"]);

//if( $auth->UserType == "Administrator" )
//{
if (isset($_REQUEST["Command"])) {
	if ($_REQUEST["Command"] == "SAVE") {
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();

		if (isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"]) > 0) {
			$PrimaryKeys["bintype_id"] = intval($_GET["item"]);
			$QuotFields["bintype_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}

		$Collector["weight"] = $_POST["weight"];
		$QuotFields["weight"] = true;		

		$Collector["name"] = $_POST["name"];
		$QuotFields["name"] = true;
		
		$Collector["description"] = $_POST["description"];
		$QuotFields["description"] = true;

		$Collector["municipality_id"] = ($auth->UserType == "Administrator" ? (intval($_POST["municipality_id"])>0?$_POST["municipality_id"]:'0') : $auth->UserRow['municipality_id']);;
		$QuotFields["municipality_id"] = true;

		$db->ExecuteUpdater("bin_types", $PrimaryKeys, $Collector, $QuotFields);
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if ($_REQUEST["Command"] ==  "DELETE") {
		if ($item != "") {
			$filter = ($auth->UserType != "Administrator" ? ' AND municipality_id=' . $auth->UserRow['municipality_id'] : '');
			$checkDelete = $db->sql_query("SELECT * FROM bin_types WHERE 1=1 " . $filter . " AND bintype_id=" . $_GET['item']);
			if ($db->sql_numrows($checkDelete) == 0) {
				$messages->addMessage("NOT FOUND.");
				Redirect($BaseUrl);
			}
			$db->sql_query("DELETE FROM bin_types WHERE bintype_id=" . $_GET['item']);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}
//}

if (isset($_GET["item"])) {
	$filter = ($auth->UserType != "Administrator" ? ' AND municipality_id=' . $auth->UserRow['municipality_id'] : '');
	$query = "SELECT * FROM bin_types WHERE 1=1 " . $filter . " AND bintype_id=" . $_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (intval($dr_e['bintype_id']) == 0 && intval($_GET['item'])>0) {
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
				<div class="control-group">
					<div class="controls">
						<label for="name" class="control-label">Ονομασία</label>
						<input type="text" name="name" id="name" class="input-xxlarge valid" <?= (isset($dr_e["name"]) ? 'value="' . $dr_e['name'] . '"' : "") ?>>
					</div>
				</div> 
				<div class="control-group">
					<div class="controls">
						<label for="weight" class="control-label">Απόβαρο</label>
						<input type="number" name="weight" id="weight" class="input-small valid" <?= (isset($dr_e["weight"]) ? 'value="' . $dr_e['weight'] . '"' : "") ?>>
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
				<div class="control-group">
					<div class="controls">
						<label for="description" class="control-label">Περιγραφή</label>
						<input type="text" name="description" id="description" class="input-xxlarge valid" <?= (isset($dr_e["description"]) ? 'value="' . $dr_e['description'] . '"' : "") ?>>
					</div>
				</div>
			</div>
		</div>
		<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary">Αποθήκευση</button></a>
		<a href="index.php?com=bin_types"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
	</div> 
	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields() {
			var value = $('#name').val();
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
								<? if($auth->UserType == "Administrator") echo '<th>Δήμος</th>';?>
								<th>Ονομασία</th>
								<th>Περιγραφή</th>
								<th>Ενέργειες</th>
							</tr>
						</thead>
						<tbody>
							<?
								$filter = ($auth->UserType != "Administrator" ? ' AND t1.municipality_id=' . $auth->UserRow['municipality_id'] : '');
								$query = "SELECT t1.*,t2.name AS municipality_name FROM bin_types t1 INNER JOIN municipalities t2 ON t1.municipality_id=t2.municipality_id WHERE 1=1  " . $filter . " ORDER BY t1.description ";
								$result = $db->sql_query($query);
								$counter = 0;
								while ($dr = $db->sql_fetchrow($result)) {
									?>
								<tr>
									<td><?= $dr["bintype_id"] ?></td>
									<?=($auth->UserType =="Administrator"?'<td>'.$dr["municipality_name"].'</td>':'') ?>
									<td><?=$dr["name"] ?></td>
									<td><?=$dr["description"] ?></td>
									<td>
										<a style="padding:4px" href="index.php?com=bin_types&Command=edit&item=<?= $dr["bintype_id"] ?>"><i class="icon-edit"></i> Επεξεργασία</a>
										<a href="#" onclick="ConfirmDelete('<?= deleteConfirm ?>','index.php?com=bin_types&Command=DELETE&item=<?= $dr['bintype_id'] ?>');"><span><i class="icon-trash"></i>Διαγραφή</a></span></a>
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
	<script>
	function getCategories(val) {
		$.ajax({
		type: "POST",
		url: "http://weighing.wan.gr/sites/weighing/components/weighing/bintypes_list.php",
		data:'user_id='+val,
		success: function(data){
			$("#category_id").html(data);
		}
		});
	}
    </script>