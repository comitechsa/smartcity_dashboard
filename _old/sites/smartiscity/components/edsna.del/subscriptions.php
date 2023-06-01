<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

//require_once(dirname(__FILE__) . "/common.php");
if($auth->UserType != "Administrator") {
	Redirect("index.php");
}

global $nav;
$nav = subscriptions;
$config["navigation"] = subscriptions;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=subscriptions";
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
				$PrimaryKeys["id"] = intval($_GET["item"]);
				$QuotFields["id"] = true;
			}
			
			$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
			$QuotFields["is_valid"] = true;
			
			$Collector["is_paid"] = isset($_POST["is_paid"]) && $_POST["is_paid"] == "on" ? "True" : "False";
			$QuotFields["is_paid"] = true;
			
			$Collector["user_id"] = $_POST["user_id"];
			$QuotFields["user_id"] = true;
			
			$Collector["reseller_id"] = $_POST["reseller_id"];
			$QuotFields["reseller_id"] = true;
			
			$Collector["end_date"] = $_POST["end_date"];
			$QuotFields["end_date"] = true;
			
			$Collector["amount"] = $_POST["amount"];
			$QuotFields["amount"] = true;
			
			$Collector["remark"] = html_entity_decode($_POST['remark'], ENT_QUOTES, "UTF-8");
			$QuotFields["remark"] = true;
		
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
			
			$db->ExecuteUpdater("subscriptions",$PrimaryKeys,$Collector,$QuotFields);
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
	$query="SELECT * FROM subscriptions WHERE id=".$_GET['item'];
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
                            <label for="textfield" class="control-label"><?=active?></label>
                            <input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?> />
                        </div>
					</div>
					<div class="control-group">
                        <div class="controls">
                            <label for="textfield" class="control-label"><?=paid?></label>
                            <input id="is_paid" name="is_paid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_paid"]) && $dr_e["is_paid"]=='True') ? 'checked':'')?> />
                        </div>
					</div>
                    <div class="control-group">
                        <label for="textfield" class="control-label"><?=expire?></label>
                        <div class="controls">
                            <input type="text" name="end_date" id="end_date" class="input-medium datepick" data-date-format="yyyy-mm-dd" value="<?=(isset($dr_e["end_date"]) ? $dr_e["end_date"] : date('Y-m-d') )?>">
                        </div>
                    </div>
					<div class="control-group">
                        <div class="controls">
                            <label for="textfield" class="control-label"><?=amount?></label>
                            <input type="text" id="amount" name="amount" class='input-xlarge' value="<?=(isset($dr_e["amount"]) ? $dr_e["amount"]:'')?>">
                        </div>
					</div>
					<div class="control-group">
                        <div class="controls">
                            <label for="textfield" class="control-label"><?=customer?></label>
                            <?
                                $query = "SELECT * FROM users WHERE is_valid='True' ORDER BY company_name";
                                echo Select::GetDbRender("user_id", $query, "user_id", "company_name", (isset($dr_e["user_id"]) ? $dr_e["user_id"] : ""), true);
                             ?>
                        </div>
					</div>
					<div class="control-group">
                        <div class="controls">
                            <label for="textfield" class="control-label"><?=reseller?></label>
                            <?
                                $query = "SELECT * FROM resellers WHERE 1=1 ORDER BY name";
                                echo Select::GetDbRender("reseller_id", $query, "reseller_id", "name", (isset($dr_e["reseller_id"]) ? $dr_e["reseller_id"] : ""), true);
                             ?>
                        </div>
					</div>
					<div class="control-group">
						<label for="textarea" class="control-label"><?=remarks?></label>
						<div class="controls">
							<textarea name="remark" rows="6" id="remark" class="input-block-level"><?=(isset($dr_e["remark"]) ? $dr_e["remark"] : "")?></textarea>
						</div>
					</div>
                    
				</div>
		</div>
                <a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
                <a href="index.php?com=subscriptions"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#amount').val();
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
							<th><?=active?></th>
							<th><?=paid?></th>
							<th><?=expire?></th>
							<th><?=amount?></th>
							<th><?=customer?></th>
							<th><?=reseller?></th>
							<th><?=phone?></th>
							<th>email</th>
							<th>Παρατήρηση</th>
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody> 
					<?	
                        $query = "SELECT subscriptions.*, users.company_name, users.user_fullname, users.mobilephone, users.email, resellers.name AS reseller FROM subscriptions INNER JOIN users ON subscriptions.user_id = users.user_id LEFT JOIN resellers ON subscriptions.reseller_id = resellers.reseller_id ORDER BY end_date ";
						$result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
                                    <td><?=$dr["is_valid"]?></td>
                                    <td><?=$dr["is_paid"]?></td>
                                    <td><?=$dr["end_date"]?></td>
                                    <td><?=$dr["amount"]?></td>
                                    <td><?=$dr["company_name"].' '.$dr["users_fullname"]?></td>
                                    <td><?=$dr["reseller"]?></td>
                                    <td><?=$dr["mobilephone"]?></td>
                                    <td><?=$dr["email"]?></td>
									<td><?=($dr["remark"]!=''?'True':'False')?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=subscriptions&Command=edit&item=<?=$dr["id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=subscriptions&Command=DELETE&item=<?=$dr["id"]?>');"><span><i class="icon-trash"></i>Διαγραφή</a></span></a>
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