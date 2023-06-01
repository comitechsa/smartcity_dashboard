<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

	if($auth->UserType != "Administrator") {  
			Redirect("index.php");
	}

global $nav;
$nav = 'Μεταβολές κατάστασης εγκαταστάσεων';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
if(isset($_GET['installation_id'])) $_SESSION['installation_id']=$_GET['installation_id'];	
$installationID=$_SESSION['installation_id'];
$drInstallation=$db->RowSelectorQuery("SELECT * FROM installations WHERE installation_id=".$installationID);
if(intval($drInstallation['installation_id'])<1) Redirect("index.php");
$BaseUrl = "index.php?com=installationcondition&installation_id=".$installationID;
$command=array();
$command=explode("&",$_POST["Command"]);

if(intval($installationID)<1) Redirect("index.php?com=installations");

if(isset($_REQUEST["Command"]))
{	
	if($_REQUEST["Command"] == "SAVE")
	{
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		
		if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
		{
			$PrimaryKeys["installationcondition_id"] = intval($_GET["item"]);
			$QuotFields["installationcondition_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}

		
		$Collector["installation_id"] = $installationID;
		$QuotFields["installation_id"] = true;
		
		$Collector["installationstatus_id"] = $_POST["installationstatus_id"];
		$QuotFields["installationstatus_id"] = true;
		
		$Collector["applicationdate"] = $_POST["applicationdate"];
		$QuotFields["applicationdate"] = true;
		
		$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
		$QuotFields["description"] = true;
		

		
		$db->ExecuteUpdater("installationcondition",$PrimaryKeys,$Collector,$QuotFields);		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") { 
		if($item != ""){
			//SOS Έλεγχο πριν τη διαγραφή
			$db->sql_query("DELETE FROM installationcondition WHERE installationcondition_id=" . $item);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}


if(isset($_GET["item"])) {
	$query="SELECT * FROM installationcondition WHERE installationcondition_id=".$_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["installationcondition_id"]) && intval($_GET['item'])>0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect($BaseUrl);
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
					<label for="applicationdate" class="control-label">Ημερομηνία μεταβολής</label>
					<div class="controls">
						<input type="text" name="applicationdate" id="applicationdate" class="input-medium datepick" data-date-format="yyyy-mm-dd" value="<?=(isset($dr_e["applicationdate"]) ? $dr_e["applicationdate"] : date('Y-m-d') )?>">
					</div>
				</div>

				<div class="controls">
					<label for="installationstatus_id" class="control-label">Κατάσταση</label>
					<?
						//$filter=" AND is_valid='True'";
						$query = "SELECT * FROM installationstatus WHERE 1=1 ".$filter." ORDER BY installationstatus_id";
						echo Select::GetDbRender("installationstatus_id", $query, "installationstatus_id", "name", (isset($dr_e["installationstatus_id"]) ? $dr_e["installationstatus_id"] : ""), true);
					 ?>
				</div>

				<div class="control-group">
					<label for="textarea" class="control-label">Περιγραφή</label>
					<div class="controls">
						<textarea name="remark" rows="10" id="description" class="input-block-level"><?=(isset($dr_l1["description"]) ? $dr_l1["description"] : "")?></textarea>
					</div>
				</div>
				
			</div>
		</div>
		<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary">Αποθήκευση</button></a>
		<a href="<?=$BaseUrl?>"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
		var value = $('#installationstatus_id').val();
		//if ( value.length >= 2){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
		//}
	}
</script>
	<?
} else 	{
	?>    
	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title"><h3><i class="icon-table"></i><?=$nav?> - <?=$drInstallation['name']?></h3>
			</div>
			<div class="box-content nopadding">
				<table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
					<thead>
						<tr>
							<th>#</th>
							<th>Κατάσταση</th>
							<th>Ημ/νία μεταβολης</th>
							<th>Ενέργεια</th>
						</tr>
					</thead>
					<tbody> 
					<?	
						$filter=" AND t1.installation_id=".$installationID;
						$query = "SELECT t1.*,t2.name AS statusname FROM installationcondition t1 LEFT JOIN installationstatus t2 ON t1.installationstatus_id=t2.installationstatus_id WHERE 1=1 ".$filter." ORDER BY name ";
                        $result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
									<td><?=$dr["installationcondition_id"]?></td>
                                    <td><?=$dr["statusname"]?></td>
									<td><?=$dr["applicationdate"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=installationcondition&Command=edit&item=<?=$dr["installationcondition_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
										<a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=installationcondition&Command=DELETE&item=<?=$dr["installationcondition_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
                                    </td>
                                </tr>
                            <?
                        }
                        $db->sql_freeresult($result);
                    ?>
					</tbody>
				</table>
				
			</div>  
			<br/><a href="index.php?com=installations"><button type="button" class="btn btn-primary">Επιστροφή στις εγκαταστάσεις</button></a>			
		</div>         
	</div>
<? } ?> 
