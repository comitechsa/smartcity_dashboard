<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

//require_once(dirname(__FILE__) . "/common.php");
if($auth->UserType != "Administrator") {
	Redirect("index.php");
}

global $nav;
$nav = resellers;
$config["navigation"] = resellers;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=resellers";
$command=array();
$command=explode("&",$_POST["Command"]);

if( $auth->UserType == "Administrator" )
{
	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "SAVE")
		{
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
			{
				$PrimaryKeys["reseller_id"] = intval($_GET["item"]);
				$QuotFields["reseller_id"] = true;
			}
			
			$Collector["name"] = $_POST["name"];
			$QuotFields["name"] = true;
			
			$Collector["phone"] = $_POST["phone"];
			$QuotFields["phone"] = true;
			
			$Collector["email"] = $_POST["email"];
			$QuotFields["email"] = true;
			
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
			
			$db->ExecuteUpdater("resellers",$PrimaryKeys,$Collector,$QuotFields);
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
				$db->sql_query("DELETE FROM resellers WHERE reseller_id=" . $item);
				$messages->addMessage("DELETED!!!");
				Redirect($BaseUrl);
			}
		}
	}
}

if(isset($_GET["item"])) {
	$query="SELECT * FROM resellers WHERE reseller_id=".$_GET['item'];
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
                            <input type="text" id="name" name="name" class='input-xlarge' value="<?=(isset($dr_e["name"]) ? $dr_e["name"]:'')?>">
                        </div>
					</div>
					<div class="control-group">
                        <div class="controls">
                            <label for="textfield" class="control-label"><?=phone?></label>
                            <input type="text" id="phone" name="phone" class='input-xlarge' value="<?=(isset($dr_e["phone"]) ? $dr_e["phone"]:'')?>">
                        </div>
					</div>
					<div class="control-group">
                        <div class="controls">
                            <label for="textfield" class="control-label">email</label>
                            <input type="text" id="email" name="email" class='input-xlarge' value="<?=(isset($dr_e["email"]) ? $dr_e["email"]:'')?>">
                        </div>
					</div>
				</div>
		</div>
                <a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
                <a href="index.php?com=resellers"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
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
							<th><?=fullName?></th>
							<th><?=phone?></th>
							<th>email</th>
							<th><?=lastChange?></th>
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody> 
					<?	
                        $query = "SELECT * FROM resellers ORDER BY name ";
                        $result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
                                    <td><?=$dr["name"]?></td>
                                    <td><?=$dr["phone"]?></td>
                                    <td><?=$dr["email"]?></td>
                                    <td><?=$dr["date_insert"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=resellers&Command=edit&item=<?=$dr["reseller_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=resellers&Command=DELETE&item=<?=$dr["reseller_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
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