<?php
echo '123';
exit;
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

	if($auth->UserType != "Administrator") {  
			Redirect("index.php");
	}

global $nav;
$nav = 'Περιφέρειες';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=regions";
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
			$PrimaryKeys["region_id"] = intval($_GET["item"]);
			$QuotFields["region_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}

		//$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
		//$QuotFields["is_valid"] = true;
		
		$Collector["code"] = $_POST["code"];
		$QuotFields["code"] = true;
		
		$Collector["name"] = $_POST["name"];
		$QuotFields["name"] = true;
		
		$Collector["population"] = $_POST["population"];
		$QuotFields["population"] = true;
		
		$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
		$QuotFields["description"] = true;
		
		$db->ExecuteUpdater("regions",$PrimaryKeys,$Collector,$QuotFields);		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") { 
		if($item != ""){
			//SOS Έλεγχο πριν τη διαγραφή
			$db->sql_query("DELETE FROM regions WHERE region_id=" . $item);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}


if(isset($_GET["item"])) {
	$query="SELECT * FROM regions WHERE region_id=".$_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["region_id"]) && intval($_GET['item'])>0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=regions");
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
						<label for="code" class="control-label">Κωδικός</label>
						<input type="number" name="code" id="code" name class="input-xxlarge valid"<?=(isset($dr_e["code"]) ? 'value="'.$dr_e['code'].'"' : "")?> >
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<label for="name" class="control-label">Ονομασία</label>
						<input type="text" name="name" id="name" name class="input-xxlarge valid"<?=(isset($dr_e["name"]) ? 'value="'.$dr_e['name'].'"' : "")?> >
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<label for="population" class="control-label">Πληθυσμός</label>
						<input type="number" name="population" id="population" name class="input-xxlarge valid"<?=(isset($dr_e["population"]) ? 'value="'.$dr_e['population'].'"' : "")?> >
					</div>
				</div>

				<div class="control-group">
					<label for="description" class="control-label">Περιγραφή</label>
					<div class="controls">
						<textarea name="description" rows="10" id="description" class="input-block-level"><?=(isset($dr_l1["description"]) ? $dr_l1["description"] : "")?></textarea>
					</div>
				</div>
				
			</div>
		</div>
			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary">Αποθήκευση</button></a>
			<a href="index.php?com=regions"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#name').val();
		if ( value.length >= 2){
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
							<th>Κωδικός</th>
							<th>Ονομασία</th>
							<th>Πληθυσμός</th>
							<th>Ενέργεια</th>
						</tr>
					</thead>
					<tbody> 
					<?	
						$query = "SELECT * FROM regions WHERE 1=1 ".$filter." ORDER BY name ";
                        $result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
									<td><?=$dr["code"]?></td>
                                    <td><?=$dr["name"]?></td>
									<td><?=$dr["population"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=regions&Command=edit&item=<?=$dr["region_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=regions&Command=DELETE&item=<?=$dr["region_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
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
