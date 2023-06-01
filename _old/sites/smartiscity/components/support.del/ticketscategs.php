<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
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

	if (!($permissions & $FLAG_TICKETS)) {
		$messages->addMessage("INVALID!!!");
		Redirect("index.php");
	}
//end permissions
	
//require_once(dirname(__FILE__) . "/common.php");
if($auth->UserType != "Administrator") {
	Redirect("index.php");
}

global $nav;
$nav = ticketCategories;
$config["navigation"] = ticketCategories;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=scategs";
$command=array();
$command=explode("&",$_POST["Command"]);

if( $auth->UserType == "Administrator" )
{
	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "SAVE")
		{

			//$testFields=(strlen(ltrim($_POST["name"]))>2 && 1==1);
			//echo $_POST["name"].'-'.intval($testFields);
			//exit;

			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
			{
				$PrimaryKeys["categ_id"] = intval($_GET["item"]);
				$QuotFields["categ_id"] = true;
			}
			
			$Collector["name"] = $_POST["name"];
			$QuotFields["name"] = true;
			
			$db->ExecuteUpdater("com_support_categs",$PrimaryKeys,$Collector,$QuotFields);
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { //$command[0] ==
			//$item=(isset($command[1]) && $command[1]!="")? $command[1]:"";
			if($item != "")
			{
				//$checkDelete=$db->sql_query("SELECT * FROM events_orgs WHERE agency_id=".$item);
				//if ($db->sql_numrows($checkDelete)>0) {
				//	$messages->addMessage("Σφάλμα: Υπάρχνουν συνδεδεμένοι οργανισμοί με τον φορέα.");
				//	Redirect($BaseUrl);				
				//}
				$db->sql_query("DELETE FROM com_support_categs WHERE categ_id=" . $item);
				$messages->addMessage("DELETED!!!");
				Redirect($BaseUrl);
			}
		}
	}
}

if(isset($_GET["item"])) {
	$query="SELECT * FROM com_support_categs WHERE categ_id=".$_GET['item'];
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
					<div class="control-group">
                        <div class="controls">
                            <label for="textfield" class="control-label"><?=name?></label>
                            <input type="text" name="name" id="name" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["name"]) ? 'value="'.$dr_e['name'].'"' : "")?> >
                        </div>
					</div>
				</div>
		</div>
                <a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
                <a href="index.php?com=scategs"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#name').val();
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
							<th><?=friendlyName?></th>
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody> 
					<?	
                        $query = "SELECT * FROM com_support_categs ORDER BY name ";
                        $result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
                                    <td><?=$dr["name"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=scategs&Command=edit&item=<?=$dr["categ_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=scategs&Command=DELETE&item=<?=$dr["categ_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
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