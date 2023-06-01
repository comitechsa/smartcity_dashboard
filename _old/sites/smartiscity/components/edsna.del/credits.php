<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
//if($auth->UserType != "Administrator") {
//	Redirect("index.php");
//}
//require_once(dirname(__FILE__) . "/common.php");
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");


//permissions
	$FLAG_DEVICES = 1;
	$FLAG_CAMPAINS = 2;
	$FLAG_PASSWORDS = 4;
	$FLAG_PRODUCTS = 8;
	$FLAG_CATEGORIES = 16;
	$FLAG_ADS = 32;
	$FLAG_CREDITS = 64;
	$FLAG_RESELLERS = 128;
	$FLAG_SURVEYS = 256;
	$FLAG_FRIENDS = 512;
	$FLAG_RATING = 1024;
	$FLAG_STATS = 2048;
	$FLAG_TICKETS = 4096;
	$permissions = (intval($auth->UserRow['access'])>0?$auth->UserRow['access']:0);

	if (!($permissions & $FLAG_CREDITS)) {
		$messages->addMessage("INVALID!!!");
		Redirect("index.php");
	}
//end permissions

global $nav;
$nav = views;
$config["navigation"] = views;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=credits";
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
				$PrimaryKeys["id"] = intval($_GET["item"]);
				$QuotFields["id"] = true;
				//$item=$_GET['item'];
			} 
			
			$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
			$QuotFields["description"] = true;

			$Collector["ads_id"] = $_POST["ads_id"];
			$QuotFields["ads_id"] = true;
			
			$Collector["views"] = $_POST["views"];
			$QuotFields["views"] = true;
		
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
			
			$db->ExecuteUpdater("ads_credits",$PrimaryKeys,$Collector,$QuotFields);
			
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { 
			if($item != "")
			{
				$db->sql_query("DELETE FROM ads_credits WHERE id=" . $item);
				$messages->addMessage("DELETED!!!");
				Redirect($BaseUrl);
			}
		}
	}


if(isset($_GET["item"])) {
	//$query="SELECT * FROM ads_credits WHERE id=".$_GET['item'].$filter;
	
	$filter="";
	if($auth->UserType != "Administrator") { 
		//$filter=" AND t2.user_id=". $auth->UserId;
		$filter=" AND (t2.user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR t2.user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
	}
	$query = "SELECT t1.* FROM ads_credits t1 INNER JOIN ads t2 ON t1.ads_id=t2.ads_id WHERE 1=1 ".$filter;
						
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
				<div class="controls">
					<label for="textfield" class="control-label"><?=ads?></label>
					<?
						if($auth->UserType != "Administrator") { 
							//$filter=" AND user_id=". $auth->UserId;
							$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
						}
						$query = "SELECT * FROM ads WHERE is_valid='True' ".$filter." ORDER BY title";
						//echo $query;
						echo Select::GetDbRender("ads_id", $query, "ads_id", "title", (isset($dr_e["ads_id"]) ? $dr_e["ads_id"] : ""), true);
					 ?>
				</div>

				<div class="control-group">
					<label for="views" class="control-label"><?=views?></label>
					<div class="controls">
						<input type="text" name="views" id="views" class="input-xxsmall" value="<?=(isset($dr_e["views"]) ? $dr_e["views"] : "")?>">
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
			<a href="index.php?com=credits"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
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
							<th><?=ticketDate?></th>
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody> 
					<?	
						$filter="";
						if($auth->UserType != "Administrator") { 
							//$filter=" AND t2.user_id=". $auth->UserId;
							$filter=" AND (t2.user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR t2.user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
						}
                        $query = "SELECT t1.*,t2.title FROM ads_credits t1 INNER JOIN ads t2 ON t1.ads_id=t2.ads_id WHERE 1=1 ".$filter." ORDER BY t2.title ";
						$result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
                                    <td><?=$dr["title"]?></td>
									<td><?=$dr["views"]?></td>
									<td><?=$dr["date_insert"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=credits&Command=edit&item=<?=$dr["id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=credits&Command=DELETE&item=<?=$dr["id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
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