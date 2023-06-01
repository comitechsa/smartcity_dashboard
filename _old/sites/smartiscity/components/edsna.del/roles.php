<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
//require_once(dirname(__FILE__) . "/common.php");

if($auth->UserType != "Administrator" ) Redirect('index.php');
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

global $nav;
$nav = 'Ρόλοι';
$config["navigation"] = 'Ρόλοι';
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=roles";
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
			$PrimaryKeys["role_id"] = intval($_GET["item"]);
			$QuotFields["role_id"] = true;
		}
		
		$Collector["priority"] = $_POST["priority"];
		$QuotFields["priority"] = true;
		
		$Collector["name"] = $_POST["name"];
		$QuotFields["name"] = true;
		
		$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
		$QuotFields["description"] = true;
		
		$Collector["last_change"] = date('Y-m-d H:i:s');
		$QuotFields["last_change"] = true;
		
		$db->ExecuteUpdater("roles",$PrimaryKeys,$Collector,$QuotFields);
		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") {
		if($item != "")
		{
			//Θα πρέπει να γίνει έλεγχος αν μπορει να γίνει διαγραφή
			$db->sql_query("DELETE FROM roles WHERE role_id=" . $item);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}

if(isset($_GET["item"])) {
	if(intval($_GET['item']>0)){
		$query="SELECT * FROM roles WHERE 1=1 AND role_id=".$_GET['item'];
		$dr_e = $db->RowSelectorQuery($query);
		if (!isset($dr_e["role_id"])) {
			$messages->addMessage("NOT FOUND!!!");
			Redirect("index.php?com=roles");
		}
	}
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
				<div class="control-group">
					<div class="controls">
						<label for="textfield" class="control-label"><?=displayOrder?></label>
						<input type="text" name="priority" id="priority" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["priority"]) ? 'value="'.$dr_e['priority'].'"' : "")?> >
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<label for="textfield" class="control-label"><?=friendlyName?></label>
						<input type="text" name="name" id="name" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["name"]) ? 'value="'.$dr_e['name'].'"' : "")?> >
					</div>
				</div>
				<div class="control-group">
					<label for="textarea" class="control-label"><?=description?></label>
					<div class="controls">
						<textarea name="description" rows="10" id="description" class="input-block-level"><?=(isset($dr_e["description"]) ? $dr_e["description"] : "")?></textarea>
					</div>
				</div>
			</div>
		</div>
			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
			<a href="index.php?com=roles"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
    </div>
<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#name').val();
		if ( value.length >= 5){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
		} 
		//else {
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
							<th><?=friendlyName?></th>
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody> 
					<?	
						$query = "SELECT * FROM roles WHERE 1=1  ORDER BY name ";
                        $result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
                                    <td><?=$dr["name"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=roles&Command=edit&item=<?=$dr["role_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=roles&Command=DELETE&item=<?=$dr["role_id"]?>');"><span><i class="icon-trash"></i><?=delete?></span></a>
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
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'description' );
    </script>