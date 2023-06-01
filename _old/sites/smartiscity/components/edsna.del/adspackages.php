<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
if($auth->UserType != "Administrator") Redirect("index.php");

//require_once(dirname(__FILE__) . "/common.php");
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
global $nav;
$nav = advPackages;
$config["navigation"] = advPackages;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=adspackages";
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
				$PrimaryKeys["package_id"] = intval($_GET["item"]);
				$QuotFields["package_id"] = true;
			} 

			$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
			$QuotFields["is_valid"] = true;
			
			$Collector["is_free"] = ($_POST["is_free"]=="on" ? "True" : "False");
			$QuotFields["is_free"] = true;
			
			$Collector["name"] = $_POST["name"];
			$QuotFields["name"] = true;
			
			$Collector["views"] = $_POST["views"];
			$QuotFields["views"] = true;
			
			$Collector["sms_alert"] = $_POST["sms_alert"];
			$QuotFields["sms_alert"] = true;
			
			$Collector["price"] = $_POST["price"];
			$QuotFields["price"] = true;
			
			$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
			$QuotFields["description"] = true;
			
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
			
			$db->ExecuteUpdater("ads_packages",$PrimaryKeys,$Collector,$QuotFields);
			
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { 
			if($item != "")
			{
				//ελεγχος πριν τη διαγραφή
				/*
				$checkDelete=$db->sql_query("SELECT * FROM rolesrates WHERE parent=".$item." OR role=".$item);
				if ($db->sql_numrows($checkDelete)>0) {
					$messages->addMessage(errorRecordsFound);
					Redirect($BaseUrl);				
				}
				*/
				$db->sql_query("DELETE FROM ads_packages WHERE package_id=" . $item);
				$messages->addMessage("DELETED!!!");
				Redirect($BaseUrl);
			}
		}
	}


if(isset($_GET["item"])) {
	$query = "SELECT * FROM ads_packages t1 WHERE package_id=".$_GET["item"];
	$dr_e = $db->RowSelectorQuery($query);
	?>
    <div class="breadcrumbs">
        <ul>
            <li>
                <a href="index.php"><?=homePage?></a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="<?=$BaseUrl?>"><?=$nav?></a>
            </li>
        </ul>
    </div>
    
	<div class="row-fluid"> 
		<div class="span12">            
			<div class="box-title">
				<h3><i class="icon-user"></i><?=edit?></h3>
			</div>
			<div class="box-content nopadding">
				<div class="check-line">
					<label class="inline" for="is_valid"><?=active?></label>
					<div class="controls">
						<input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>  />
					</div>
				</div>
				<div class="check-line">
					<label class="inline" for="is_free">Free</label>
					<div class="controls">
						<input id="is_free" name="is_free" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_free"]) && $dr_e["is_free"]=='True') ? 'checked':'')?>  />
					</div>
				</div>
				
				<div class="control-group">
					<label for="name" class="control-label"><?=friendlyName?></label>
					<div class="controls">
						<input type="text" name="name" id="name" class="input-xxsmall" value="<?=(isset($dr_e["name"]) ? $dr_e["name"] : "")?>">
					</div>
				</div>
				<div class="control-group">
					<label for="views" class="control-label"><?=views?></label>
					<div class="controls">
						<input type="text" name="views" id="views" class="input-xxsmall" value="<?=(isset($dr_e["views"]) ? $dr_e["views"] : "")?>">
					</div>
				</div>
				
				<div class="control-group">
					<label for="sms_alert" class="control-label">SMS Alert</label>
					<div class="controls">
						<input type="text" name="sms_alert" id="sms_alert" class="input-xxsmall" value="<?=(isset($dr_e["sms_alert"]) ? $dr_e["sms_alert"] : "")?>">
					</div>
				</div>
				
				<div class="control-group">
					<label for="price" class="control-label"><?=price?></label>
					<div class="controls">
						<input type="text" name="price" id="price" class="input-xxsmall" value="<?=(isset($dr_e["price"]) ? $dr_e["price"] : "")?>">
					</div>
				</div>
				<div class="control-group">
					<label for="textarea" class="control-label"><?=remarks?></label>
					<div class="controls">
						<textarea name="description" rows="6" id="description" class="input-block-level"><?=(isset($dr_e["description"]) ? $dr_e["description"] : "")?></textarea>
					</div>
				</div>
			</div>
		</div>
			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
			<a href="index.php?com=adspackages"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#views').val();
		if ( value.length >= 2){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
		} //else {
			//document.getElementById("submitBtn").disabled = true;
			//alert('2 chars');
		//}
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
							<th><?=title?></th>
							<th><?=views?></th>
							<th><?=price?></th>
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody> 
					<?	
						$filter="";
                        $query = "SELECT * FROM ads_packages WHERE 1=1 ";
						$result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
                                    <td><?=$dr["name"]?></td>
									<td><?=$dr["views"]?></td>
									<td><?=$dr["price"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=adspackages&Command=edit&item=<?=$dr["package_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=adspackages&Command=DELETE&item=<?=$dr["package_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
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
<script language="javascript">
   CKEDITOR.replace( 'description' );
</script>