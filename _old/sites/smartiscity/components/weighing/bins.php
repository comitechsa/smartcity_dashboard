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
$nav = 'Κάδοι';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=bins";
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
			$PrimaryKeys["bin_id"] = intval($_GET["item"]);
			$QuotFields["bin_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}

		$Collector["is_valid"] = ($_POST["is_valid"] == "on" ? "True" : "False");
		$QuotFields["is_valid"] = true;

		$Collector["municipality_id"] = ($auth->UserType == "Administrator" ? (intval($_POST["municipality_id"])>0?$_POST["municipality_id"]:'0') : $auth->UserRow['municipality_id']);
		$QuotFields["municipality_id"] = true;
		
		$Collector["bintype_id"] = $_POST["bintype_id"];
		$QuotFields["bintype_id"] = true;
		
		$Collector["tag"] = $_POST["tag"];
		$QuotFields["tag"] = true;

		$Collector["remarks"] = $_POST["remarks"];
		$QuotFields["remarks"] = true;
		
		$db->ExecuteUpdater("bins", $PrimaryKeys, $Collector, $QuotFields);
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
			$db->sql_query("DELETE FROM bins WHERE bin_id=" . $_GET['item']);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}
//}

if (isset($_GET["item"])) {
	$filter = ($auth->UserType != "Administrator" ? ' AND t1.municipality_id=' . $auth->UserRow['municipality_id'] : '');
	$query = "SELECT t1.* FROM bins t1 INNER JOIN municipalities t2 ON t1.municipality_id=t2.municipality_id WHERE 1=1 " . $filter . " AND bin_id=" . $_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (intval($dr_e['bin_id']) == 0 && intval($_GET['item'])>0) {
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
				  
                <? if($auth->UserType == "Administrator") { ?> 
                <div class="controls">
                    <label for="textfield" class="control-label">Δήμος</label>
                    <select name="municipality_id" id="municipality_id" onChange="getBintypes(this.value);">
                        <option value="">Δήμος</option>
                        <?
                        $queryMunicipalities = "SELECT * FROM municipalities WHERE is_valid='True' ORDER BY name";
                        $resultMunicipalities = $db->sql_query($queryMunicipalities);
                        while ($drMunicipalities = $db->sql_fetchrow($resultMunicipalities)) {?>
                            <option value="<?=$drMunicipalities["municipality_id"]; ?>" <?=($dr_e['municipality_id']==$drMunicipalities['municipality_id']?' selected':'')?>><?=$drMunicipalities["name"]; ?></option>
                        <? } ?>
                    </select>
                </div> 
				<? } ?>
	
				<div class="control-group">
					<div class="controls">
						<label for="textfield" class="control-label">Τύπος κάδου</label>
						<select name="bintype_id" id="bintype_id" >
						<option value="0">Επιλογή</option>
                        <?
						$filter=($auth->UserType != "Administrator"?" AND municipality_id=".$auth->UserRow['municipality_id']:"");
                        $queryBintypes = "SELECT * FROM bin_types WHERE 1=1 ".$filter." ORDER BY name";
						
                        $resultBintypes = $db->sql_query($queryBintypes);
                        while ($drBintypes = $db->sql_fetchrow($resultBintypes)) {?>
                            <option value="<?=$drBintypes["bintype_id"]; ?>" <?=($dr_e['bintype_id']==$drBintypes['bintype_id']?' selected':'')?>><?=$drBintypes["name"]; ?></option>
                        <? } ?>
						</select>
					</div> 
				</div>

				<div class="control-group">
					<div class="controls">
						<label for="tag" class="control-label">RFID Tag</label>
						<input type="text" name="tag" id="tag" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?= (isset($dr_e["tag"]) ? 'value="' . $dr_e['tag'] . '"' : "") ?>>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<label for="remarks" class="control-label">Σημείωση</label>
						<input type="text" name="remarks" id="remarks" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?= (isset($dr_e["remarks"]) ? 'value="' . $dr_e['remarks'] . '"' : "") ?>>
					</div>
				</div>
				
			</div>
		</div>
		<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary">Αποθήκευση</button></a>
		<a href="index.php?com=bins"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
	</div>

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields() {
			var value = $('#bintype_id').val();
			if (value.length >= 0) {
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
								<th>Ενεργό</th>
								<? if($auth->UserType == "Administrator") echo '<th>Δήμος</th>';?>
								<th>Rfid tag</th>
								<th>Βάρος</th>
								<th>Τύπος</th>
								<th>Περιγραφή</th>
								<th>Ενέργειες</th>
							</tr>
						</thead>
						<tbody>
							<?
								$filter = ($auth->UserType != "Administrator" ? ' AND t1.municipality_id=' . $auth->UserRow['municipality_id'] : '');
								$query = "SELECT t1.*,t2.name, t3.weight,t3.name AS bintype_name,t3.bintype_id FROM bins t1 INNER JOIN municipalities t2 ON t1.municipality_id=t2.municipality_id LEFT JOIN bin_types t3 ON t1.bintype_id=t3.bintype_id WHERE 1=1  " . $filter . " ORDER BY t1.tag ";
								$result = $db->sql_query($query);
								$counter = 0;
								while ($dr = $db->sql_fetchrow($result)) {
									?>
								<tr>
									<td><?=$dr["bin_id"] ?></td>
									<td><?=$dr["is_valid"] ?></td>
									<?=($auth->UserType =="Administrator"?'<td>'.$dr["name"].'</td>':'') ?>
									<td><?=$dr["tag"] ?></td>
									<td><?=intval($dr["weight"]) ?></td>
									<td><?=(intval($dr['bintype_id'])>0?$dr["bintype_name"]:'Αγνωστος') ?></td>
									<td><?=$dr["remarks"] ?></td>
									<td>
										<a style="padding:4px" href="index.php?com=bins&Command=edit&item=<?= $dr["bin_id"] ?>"><i class="icon-edit"></i> Επεξεργασία</a>
										<a href="#" onclick="ConfirmDelete('<?= deleteConfirm ?>','index.php?com=bins&Command=DELETE&item=<?= $dr['bin_id'] ?>');"><span><i class="icon-trash"></i>Διαγραφή</a></span></a>
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
	function getBintypes(val) {
		$.ajax({
		type: "POST",
		url: "http://weighing.wan.gr/sites/weighing/components/weighing/bintypes_list.php",
		data:'municipality_id='+val,
		success: function(data){
			$("#bintype_id").html(data);
		}
		});
	}
    </script>