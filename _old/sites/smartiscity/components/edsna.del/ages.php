<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

	if($auth->UserType != "Administrator") {  
			Redirect("index.php");
	}

global $nav;
$nav = 'Ηλικίες';
$config["navigation"] = 'Ηλικίες';
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=ages";
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
			$PrimaryKeys["age_id"] = intval($_GET["item"]);
			$QuotFields["age_id"] = true;
		}
		
		$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
		$QuotFields["is_valid"] = true;
		
		$Collector["age_name"] = $_POST["age_name"];
		$QuotFields["age_name"] = true;

		$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
		$QuotFields["description"] = true;
		
		$db->ExecuteUpdater("ages",$PrimaryKeys,$Collector,$QuotFields);		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") { 
		if($item != ""){
			$db->sql_query("DELETE FROM ages WHERE age_id=" . $item);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}


if(isset($_GET["item"])) {

	$query="SELECT * FROM ages WHERE age_id=".$_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	//if (!isset($dr_e["age_id"])) {
	//	$messages->addMessage("NOT FOUND!!!");
	//	Redirect("index.php?com=ages");
	//}
			
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
                    
				<div class="control-group">
					<div class="controls">
						<label for="textfield" class="control-label"><?=friendlyName?></label>
						<input type="text" name="age_name" id="age_name" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["age_name"]) ? 'value="'.$dr_e['age_name'].'"' : "")?> >
					</div>
				</div>

				<div class="control-group">
					<label for="textarea" class="control-label"><?=description?></label>
					<div class="controls">
						<textarea name="remark" rows="10" id="description" class="input-block-level"><?=(isset($dr_l1["description"]) ? $dr_l1["description"] : "")?></textarea>
					</div>
				</div>
				
			</div>
		</div>
			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
			<a href="index.php?com=ages"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#age_name').val();
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
							<th><?=active?></th>
							<th><?=friendlyName?></th>
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody> 
					<?	
						$query = "SELECT * FROM ages WHERE 1=1 ".$filter." ORDER BY age_name ";
                        $result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
									<td><?=$dr["is_valid"]?></td>
                                    <td><?=$dr["age_name"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=ages&Command=edit&item=<?=$dr["age_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <!--<a href="#" onclick="ConfirmDelete('<? //=deleteConfirm?>','index.php?com=ages&Command=DELETE&item=<? //=$dr["age_id"]?>');"><span><i class="icon-trash"></i><? //=delete?></a></span></a>-->
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
        CKEDITOR.add;
    </script>