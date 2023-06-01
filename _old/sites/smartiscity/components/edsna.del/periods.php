<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

	if($auth->UserType != "Administrator") {  
			Redirect("index.php");
	}

global $nav;
$nav = 'Περίοδοι';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=periods";
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
			$PrimaryKeys["period_id"] = intval($_GET["item"]);
			$QuotFields["period_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}
		
		$Collector["year"] = $_POST["year"];
		$QuotFields["year"] = true;
		
		$Collector["cost"] = $_POST["cost"];
		$QuotFields["cost"] = true;
		
		$Collector["installation_status_date"] = $_POST["installation_status_date"];
		$QuotFields["installation_status_date"] = true;

		$Collector["env_contribution"] = $_POST["env_contribution"];
		$QuotFields["env_contribution"] = true;
		
		$Collector["multiplier1"] = $_POST["multiplier1"];
		$QuotFields["multiplier1"] = true;

		$Collector["multiplier2"] = $_POST["multiplier2"];
		$QuotFields["multiplier2"] = true;
		
		$Collector["multiplier3"] = $_POST["multiplier3"];
		$QuotFields["multiplier3"] = true;
		
		$Collector["multiplier4"] = $_POST["multiplier4"];
		$QuotFields["multiplier4"] = true;
		
		$Collector["multiplier5"] = $_POST["multiplier5"];
		$QuotFields["multiplier5"] = true;
		
		$Collector["multiplier6"] = $_POST["multiplier6"];
		$QuotFields["multiplier6"] = true;
		
		$Collector["multiplier7"] = $_POST["multiplier7"];
		$QuotFields["multiplier7"] = true;
		
		$Collector["multiplier8"] = $_POST["multiplier8"];
		$QuotFields["multiplier8"] = true;	
		
		$Collector["description"] = $_POST["description"];
		$QuotFields["description"] = true;
		
		//$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
		//$QuotFields["description"] = true;
		
		$db->ExecuteUpdater("periods",$PrimaryKeys,$Collector,$QuotFields);		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") { 
		if($item != ""){
			//SOS Έλεγχο πριν τη διαγραφή
			$db->sql_query("DELETE FROM periods WHERE period_id=" . $item);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}


if(isset($_GET["item"])) {
	$query="SELECT * FROM periods WHERE period_id=".$_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["period_id"]) && intval($_GET['item'])>0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=periods");
	}
	?>
    <div class="breadcrumbs">
        <ul>
            <li> <a href="index.php"><?=homePage?></a><i class="icon-angle-right"></i></li>
            <li><a href="<?=$BaseUrl?>"><?=$nav?></a></li>
        </ul>
    </div>
    
	<div class="row-fluid"> 
		<div class="span12">            
			<div class="box-title">
				<h3><i class="icon-user"></i><?=edit?></h3>
			</div>
			<div class="box-content nopadding">
				<div class="control-group">
					<div class="controls">
						<label for="year" class="control-label">Έτος</label>
						<input type="text" name="year" id="year" name class="input-small valid"<?=(isset($dr_e["year"]) ? 'value="'.$dr_e['year'].'"' : "")?> >
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<label for="description" class="control-label">Περιγραφή</label>
						<input type="text" name="description" id="description" name class="input-xxlarge valid"<?=(isset($dr_e["description"]) ? 'value="'.$dr_e['description'].'"' : "")?> >
					</div>
				</div>	
				<div class="control-group">
					<div class="controls">
						<label for="cost" class="control-label">Κόστος υπολογισμού</label>
						<input type="number" name="cost" id="cost" name class="input-small valid"<?=(isset($dr_e["cost"]) ? 'value="'.$dr_e['cost'].'"' : "")?> >
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<label for="env_contribution" class="control-label">Περιβαλλοντική εισφορά</label>
						<input type="number" name="env_contribution" id="env_contribution" name class="input-small valid"<?=(isset($dr_e["env_contribution"]) ? 'value="'.$dr_e['env_contribution'].'"' : "")?> >
					</div>
				</div>
				
				<div class="control-group">
					<label for="installation_status_date" class="control-label">Ημερομηνία κατάστασης εγκαταστάσεων</label>
					<div class="controls">
						<input type="text" name="installation_status_date" id="installation_status_date" class="input-medium datepick" data-date-format="yyyy-mm-dd" value="<?=(isset($dr_e["installation_status_date"]) ? $dr_e["installation_status_date"] : date('Y-m-d') )?>">
					</div>
				</div>
				<div class="form-horizontal form-column form-bordered" style="border: 2px solid #aaa;margin-bottom:20px;">
					<div class="row-fluid"> 
						<div class="span6"> 
								<div class="control-group">
									<label for="multiplier1" class="control-label" style="width:60%;">Πολλαπλασιαστής 1</label>
									<div class="controls" style="margin-left:280px;">
										<input type="number" name="multiplier1" id="multiplier1" class="input-small" <?= (isset($dr_e["multiplier1"]) ? 'value="' . $dr_e['multiplier1'] . '"' : "") ?>>
									</div>
								</div>
								
								<div class="control-group">
									<label for="multiplier2" class="control-label" style="width:60%;">Πολλαπλασιαστής 2</label>
									<div class="controls" style="margin-left:280px;">
										<input type="number" name="multiplier2" id="multiplier2" class="input-small" <?= (isset($dr_e["multiplier2"]) ? 'value="' . $dr_e['multiplier2'] . '"' : "") ?>>
									</div>
								</div>
								
								<div class="control-group">
									<label for="multiplier3" class="control-label" style="width:60%;">Πολλαπλασιαστής 3</label>
									<div class="controls" style="margin-left:280px;">
										<input type="number" name="multiplier3" id="multiplier3" class="input-small" <?= (isset($dr_e["multiplier3"]) ? 'value="' . $dr_e['multiplier3'] . '"' : "") ?>>
									</div>
								</div>
								
								<div class="control-group">
									<label for="multiplier4" class="control-label" style="width:60%;">Πολλαπλασιαστής 4</label>
									<div class="controls" style="margin-left:280px;">
										<input type="number" name="multiplier4" id="multiplier4" class="input-small" <?= (isset($dr_e["multiplier4"]) ? 'value="' . $dr_e['multiplier4'] . '"' : "") ?>>
									</div>
								</div>
						</div>
						<div class="span6"> 
								<div class="control-group">
									<label for="multiplier5" class="control-label" style="width:60%;">Πολλαπλασιαστής 5</label>
									<div class="controls" style="margin-left:280px;">
										<input type="number" name="multiplier5" id="multiplier5" class="input-small" <?= (isset($dr_e["multiplier5"]) ? 'value="' . $dr_e['multiplier5'] . '"' : "") ?>>
									</div>
								</div>
								
								<div class="control-group">
									<label for="multiplier6" class="control-label" style="width:60%;">Πολλαπλασιαστής 6</label>
									<div class="controls" style="margin-left:280px;">
										<input type="number" name="multiplier6" id="multiplier6" class="input-small" <?= (isset($dr_e["multiplier6"]) ? 'value="' . $dr_e['multiplier6'] . '"' : "") ?>>
									</div>
								</div>
								
								<div class="control-group">
									<label for="multiplier7" class="control-label" style="width:60%;">Πολλαπλασιαστής 7</label>
									<div class="controls" style="margin-left:280px;">
										<input type="number" name="multiplier7" id="multiplier7" class="input-small" <?= (isset($dr_e["multiplier7"]) ? 'value="' . $dr_e['multiplier7'] . '"' : "") ?>>
									</div>
								</div>		

								<div class="control-group">
									<label for="multiplier8" class="control-label" style="width:60%;">Πολλαπλασιαστής 8</label>
									<div class="controls" style="margin-left:280px;">
										<input type="number" name="multiplier8" id="multiplier8" class="input-small" <?= (isset($dr_e["multiplier8"]) ? 'value="' . $dr_e['multiplier8'] . '"' : "") ?>>
									</div>
								</div>				
						</div>
					</div>
				</div>
			</div>
		</div>
			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary">Αποθήκευση</button></a>
			<a href="index.php?com=periods"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#year').val();
		if ( value.length == 4){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
		}
	}
</script>
	<?
} else 	{
	?>    
	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title"><h3><i class="icon-table"></i><?=$nav?></h3>
			</div>
			<div class="box-content nopadding">
				<table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
					<thead>
						<tr>
							<th style="width:10%;">#</th>
							<th style="width:10%;">Έτος</th>
							<th style="width:15%;">Κόστος υπολογισμού</th>
							<th style="width:40%;">Περιγραφή</th>
							<th style="width:25%;">Ενέργεια</th>
						</tr>
					</thead>
					<tbody> 
					<?	
						$query = "SELECT * FROM periods WHERE 1=1 ".$filter." ORDER BY year ";
                        $result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
									<td><?=$dr["period_id"]?></td>
                                    <td><?=$dr["year"]?></td>
									<td><?=$dr["cost"]?></td>
									<td><?=$dr["description"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=periods&Command=edit&item=<?=$dr["period_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=periods&Command=DELETE&item=<?=$dr["period_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
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
