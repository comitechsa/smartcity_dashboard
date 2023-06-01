<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

	if($auth->UserType != "Administrator" && $auth->UserRow['can_order']!='True') { 
			Redirect("index.php");
	}

global $nav;
$nav = 'Παραγγελίες';
$config["navigation"] = 'Παραγγελίες';
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=orders";
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
			$PrimaryKeys["order_id"] = intval($_GET["item"]);
			$QuotFields["order_id"] = true;
		} else {

		}
		
		$Collector["is_complete"] = ($_POST["is_complete"]=="on" ? "True" : "False");
		$QuotFields["is_complete"] = true;
		
		if($_POST["is_complete"]=="on"){
			$Collector["date_complete"] = $auth->UserRow['user_id'];
			$QuotFields["date_complete"] = true;
		}
		if($auth->UserType == "Administrator") {  
			$userID=$_POST['user_id'];
		} else {
			$userID=$auth->UserRow['user_id'];
		}		
		$Collector["user_id"] = $userID;
		$QuotFields["user_id"] = true;
		
		$Collector["user_insert"] = $auth->UserRow['user_id'];
		$QuotFields["user_insert"] = true;
		
		$Collector["mobile"] = $_POST["mobile"];
		$QuotFields["mobile"] = true;
		
		$Collector["basic"] = $_POST["basic"];
		$QuotFields["basic"] = true;
		
		$Collector["basicplus"] = $_POST["basicplus"];
		$QuotFields["basicplus"] = true;
		
		$Collector["pro"] = $_POST["pro"];
		$QuotFields["pro"] = true;		
		
		$Collector["vip"] = $_POST["vip"];
		$QuotFields["vip"] = true;	

		if(!isset($_GET["item"]) || intval($_GET["item"])< 1){
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}
	
		$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
		$QuotFields["description"] = true;
		
		
		$db->ExecuteUpdater("orders",$PrimaryKeys,$Collector,$QuotFields);		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") { 
		if($item != ""){
			$db->sql_query("DELETE FROM orders WHERE order_id=" . $item);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}


if(isset($_GET["item"])) {
	$query="SELECT * FROM orders WHERE order_id=".$_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
			
	?>
	
	<script>
	$(function() {
		getamount();
	});
	</script>
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
				<h3><i class="icon-user"></i><?=edit?> <span id="amount"></span></h3>
			</div>
			<div class="box-content nopadding">
				<? if($auth->UserType == "Administrator") { ?> 
				<div class="check-line">
					<label class="inline" for="is_complete"><?=active?></label>
					<div class="controls">
						<input id="is_complete" name="is_complete" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_complete"]) && $dr_e["is_complete"]=='True') ? 'checked':'')?>  />
					</div>
				</div>

				<div class="controls">
					<label for="textfield" class="control-label"><?=customer?></label>
					<?
						$query = "SELECT * FROM users WHERE is_valid='True' ORDER BY company_name";
						echo Select::GetDbRender("user_id", $query, "user_id", "company_name", (isset($dr_e["user_id"]) ? $dr_e["user_id"] : ""), true);
					 ?>
				</div> 
				<? } ?>
				<div class="control-group">
					<div class="controls">
						<label for="mobile" class="control-label">Mobile</label>
						<input type="text" name="mobile" id="mobile" class="input-xxsmall" <?=(isset($dr_e["mobile"]) ? 'value="'.$dr_e['mobile'].'"' : "")?>  onchange='getamount();'>
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<label for="basic" class="control-label">Basic</label>
						<input type="text" name="basic" id="basic" class="input-xxsmall" <?=(isset($dr_e["basic"]) ? 'value="'.$dr_e['basic'].'"' : "")?> onchange='getamount();'>
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<label for="basicplus" class="control-label">BasicPlus</label>
						<input type="text" name="basicplus" id="basicplus" class="input-xxsmall" <?=(isset($dr_e["basicplus"]) ? 'value="'.$dr_e['basicplus'].'"' : "")?> onchange='getamount();'>
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<label for="pro" class="control-label">Pro</label>
						<input type="text" name="pro" id="pro" class="input-xxsmall" <?=(isset($dr_e["pro"]) ? 'value="'.$dr_e['pro'].'"' : "")?> onchange='getamount();'>
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<label for="vip" class="control-label">VIP</label>
						<input type="text" name="vip" id="vip" class="input-xxsmall" <?=(isset($dr_e["vip"]) ? 'value="'.$dr_e['vip'].'"' : "")?> onchange='getamount();'>
					</div>
				</div>
				<div class="controls">
					<label for="textfield" class="control-label">Τρόπος πληρωμής</label>
					<select id="user_id" name="user_id" class="m_tb">
						<option value="">Επιλογή</option>
						<option value="1">Κατάθεση σε τραπεζικό λογαριασμό</option>
						<option value="2">Πιστωτική κάρτα</option>
					</select>
				</div>
				<? if($auth->UserType == "Administrator") { ?> 
				<div class="control-group">
					<div class="controls">
						<label for="textfield" class="control-label">VIP price</label>
						<input type="text" name="vipprice" id="vipprice" class="input-xxsmall" <?=(isset($dr_e["vipprice"]) ? 'value="'.$dr_e['vipprice'].'"' : "")?> onchange='getamount();'>
					</div>
				</div>
				<? } ?>
				<div class="controls">
					<label for="textfield" class="control-label">Τρόπος πληρωμής</label>
					<select id="user_id" name="user_id" class="m_tb">
						<option value="">Επιλογή</option>
						<option value="1">Κατάθεση σε τραπεζικό λογαριασμό</option>
						<option value="2">Πιστωτική κάρτα</option>
					</select>
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
	//var value = $('#age_name').val();
	//	if ( value.length >= 2){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
	//	}
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
							<?=$auth->UserType == "Administrator"?"<th>".customer."</th>":''?>
							<th>M/B/B+/P/V</th>
							<th>Date Insert</th>
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody> 
					<?	
						$filter=($auth->UserType != "Administrator"?" AND user_id=".$auth->UserRow['user_id']:"");
						$query = "SELECT * FROM orders WHERE 1=1 ".$filter." ORDER BY date_insert ";
                        $result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
									<td><?=$dr["is_complete"]?></td>
                                            <? if($auth->UserType=="Administrator"){
                                            $dr_c=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$dr['user_id']);
                                            echo "<td>".$dr_c['company_name']."</td>";
                                            }
                                            ?>
                                    <td><?=$dr["mobile"].'/'.$dr["basic"].'/'.$dr["basicplus"].'/'.$dr["pro"].'/'.$dr["vip"]?></td>
									<td><?=$dr["date_insert"]?></td>
                                    <td>
										<? if($auth->UserType == "Administrator"){?>
										<a style="padding:4px"  href="index.php?com=orders&Command=edit&item=<?=$dr["order_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
										<a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=orders&Command=DELETE&item=<?=$dr["order_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
										<? } ?>
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
<script>
function getamount(value)
{
    //var price=$("#price").html().replace ( /[^\d.]/g, '' );
	var price1=295; //175+120;
	var price2=210; //120+90;
	var price3=310; //120+190;
	var price4=530; //160+370;
	//var price5=540+370;
	
	//var e1 = document.getElementById("mobile");
	//var q1 = e1.options[e1.selectedIndex].text;
	
	//var e2 = document.getElementById("basic");
	//var q2 = e2.options[e2.selectedIndex].text;

	//var e3 = document.getElementById("basicplus");
	//var q3 = e3.options[e3.selectedIndex].text;
	
	//var e4 = document.getElementById("pro");
	//var q4 = e4.options[e4.selectedIndex].text;

	//var e5 = document.getElementById("vip");
	//var q5 = e5.options[e5.selectedIndex].text;
	
	var q1=document.getElementById("mobile").value;
	var q2=document.getElementById("basic").value;
	var q3=document.getElementById("basicplus").value;
	var q4=document.getElementById("pro").value;
	var q5=document.getElementById("vip").value;
	var q5Price=document.getElementById("vipprice").value;
	
	var quantity1=parseInt(q1);
	var quantity2=parseInt(q2);
	var quantity3=parseInt(q3);
	var quantity4=parseInt(q4);
	var quantity5=parseInt(q5);
	var q5Price=parseInt(q5Price);
	
	if (isNaN(quantity1)) quantity1=0;
	if (isNaN(quantity2)) quantity2=0;
	if (isNaN(quantity3)) quantity3=0;
	if (isNaN(quantity4)) quantity4=0;
	if (isNaN(quantity5)) quantity5=0;
	if (isNaN(q5Price)) q5Price=0;
	
    //var amount=parseInt(value)*parseInt(price);
	var amount1=parseInt(price1)*parseInt(quantity1);
	var amount2=parseInt(price2)*parseInt(quantity2);
	var amount3=parseInt(price3)*parseInt(quantity3);
	var amount4=parseInt(price4)*parseInt(quantity4);
	var amount5=parseInt(q5Price)*parseInt(quantity5);
	var amount=parseInt(amount1)+parseInt(amount2)+amount3+amount4+amount5;
	//alert(amount5);
	
    $("#amount").html("Σύνολο: " +amount);
	//document.getElementById("total_amount").value = amount;
}
</script>