<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
//$_SESSION['uid']=$auth->UserRow['user_id'];
//require_once(dirname(__FILE__) . "/common.php");
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
global $nav;
$nav = 'Αγορές';
$config["navigation"] = 'Αγορές';
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=buypackages";
$command=array();
$command=explode("&",$_POST["Command"]);

	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "SAVEREC" && array_sum($_POST)>0){
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
			{
				$PrimaryKeys["order_id"] = intval($_GET["item"]);
				$QuotFields["order_id"] = true;
			} 

			$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
			$QuotFields["is_valid"] = true;
			
			$Collector["paid"] = ($_POST["paid"]=="on" ? "True" : "False");
			$QuotFields["paid"] = true;
			
			$Collector["user_id"] = ($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent']); //$_POST["user_id"];
			$QuotFields["user_id"] = true;
			
			$Collector["amount"] = $_POST["amount"];
			$QuotFields["amount"] = true;
			
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
			
			$db->ExecuteUpdater("ads_orders",$PrimaryKeys,$Collector,$QuotFields);
			
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else
		if($_REQUEST["Command"] == "SAVE" && array_sum($_POST)>0)
		{
		//Καταχώρηση της παραγγελίας
			
			//Υπολογισμός συνόλου
			foreach ($_POST as $key => $value) {
				$myKey=intval(ltrim($key, 'p'));
				$dr_e = $db->RowSelectorQuery("SELECT * FROM ads_packages WHERE package_id=".intval($myKey)." AND is_valid='True'");
				$total=$total+($dr_e['price']*$value);
				//echo $dr_e['price'].'*'.$value.'<br>';
			}
			//echo $total;
			//exit;
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			$Collector["user_id"] = $auth->UserRow['user_id'];
			$QuotFields["user_id"] = true;		
			
			$Collector["amount"] = $total;
			$QuotFields["amount"] = true;				
			
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
			
			$db->ExecuteUpdater("ads_orders",$PrimaryKeys,$Collector,$QuotFields);
			$pk = $db->sql_nextid();
			$db->sql_freeresult($result);

			//save order items
			foreach ($_POST as $key => $value) {
				$myKey=intval(ltrim($key, 'p'));
				if($myKey>0){					
					$PrimaryKeys = array();
					$Collector = array();
					$QuotFields = array();
					
					$Collector["order_id"] = $pk;
					$QuotFields["order_id"] = true;
					
					$Collector["user_id"] = $auth->UserRow['user_id'];
					$QuotFields["user_id"] = true;	
					
					$Collector["role_id"] = $auth->UserRow['role_id'];
					$QuotFields["role_id"] = true;
					
					$Collector["package_id"] = $myKey;
					$QuotFields["package_id"] = true;
				
					$Collector["quantity"] = $value;
					$QuotFields["quantity"] = true;
					
					$dr_e = $db->RowSelectorQuery("SELECT * FROM ads_packages WHERE package_id=".intval($myKey)." AND is_valid='True'");
					$price=($dr_e['price']*$value);
					//$price=$dr_e['price'];
					$Collector["price"] = $price;
					$QuotFields["price"] = true;
					
					$db->ExecuteUpdater("ads_orderitems",$PrimaryKeys,$Collector,$QuotFields);

					//
					
					for ($i = 1; $i <= $value; $i++) {
						$db->sql_query("INSERT INTO purchasedpackages (order_id, user_id, friendly_name, package_id,views,date_insert) VALUES ('".$pk."','".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."','".$dr_e['name']."','".intval($myKey)."','".$dr_e['views']."','".date('Y-m-d H:i:s')."') ");

						//Βάλε τα rates
						//$purchasedpackages_id=(intval($_GET['item'])>0?$_GET['item']:$db->sql_nextid());
						$purchasedpackages_id=$db->sql_nextid();
						
						$query="SELECT * FROM rolesrates WHERE parent=".$auth->UserRow['role_id'];
						
						$result = $db->sql_query($query);
						while ($dr = $db->sql_fetchrow($result))
						{
							$db->sql_query("INSERT INTO purchasedpackagerates (purchasedpackage_id,role_id,percent) VALUES 
							('".$purchasedpackages_id."','".$dr['role']."',".$dr['percent'].") ");
						}
					}

					//$db->sql_freeresult($result);
					
				}
			}
			$messages->addMessage("SAVED!!!");
			
			//Send the invoice
			// create curl resource 
			//$ch = curl_init(); 

			// set url 
			//curl_setopt($ch, CURLOPT_URL, "panel.spotyy.com/viva/buypackage.php?id=".$pk); 

			//return the transfer as a string 
			//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

			// $output contains the output string 
			//$output = curl_exec($ch); 

			// close curl resource to free up system resources 
			//curl_close($ch);
			
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { 
			if($item != "")
			{
				//Αν είναι πληρωμένο ή ενεργό δεν διαγράφεται
				//ελεγχος πριν τη διαγραφή
				$checkDelete=$db->sql_query("SELECT * FROM ads_orders WHERE order_id=".$item." AND (paid='True' OR is_valid='True')");
				if ($db->sql_numrows($checkDelete)>0) {
					$messages->addMessage(errorRecordsFound);
					Redirect($BaseUrl);				
				}
				
				$db->sql_query("DELETE FROM ads_orders WHERE order_id=" . $item);
				$db->sql_query("DELETE FROM purchasedpackagerates WHERE purchasedpackage_id IN (SELECT purchasedpackage_id FROM purchasedpackages WHERE order_id=".$item.")");
				$db->sql_query("DELETE FROM purchasedpackages WHERE order_id=" . $item);
				$db->sql_query("DELETE FROM ads_orderitems WHERE order_id=" . $item);
				$messages->addMessage("DELETED!!!");
				Redirect($BaseUrl);
			}
		}
	}
?>

<?
if(isset($_GET["item"])) {
	if($auth->UserType == "Administrator") { 
		$query = "SELECT * FROM ads_orders WHERE order_id=".$_GET["item"];
		$dr_e = $db->RowSelectorQuery($query);
	?>
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
					<label class="inline" for="paid">Paid</label>
					<div class="controls">
						<input id="paid" name="paid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["paid"]) && $dr_e["paid"]=='True') ? 'checked':'')?>  />
					</div>
				</div>
				<div class="control-group">
					<label for="user_id" class="control-label">User_id</label>
					<div class="controls">
						<input type="text" name="user_id" id="user_id" class="input-xxsmall" value="<?=(isset($dr_e["user_id"]) ? $dr_e["user_id"] : "")?>">
					</div>
				</div>
				<div class="control-group">
					<label for="amount" class="control-label">Amount</label>
					<div class="controls">
						<input type="text" name="amount" id="amount" class="input-xxsmall" value="<?=(isset($dr_e["amount"]) ? $dr_e["amount"] : "")?>">
					</div>
				</div>

			</div>
		</div>
		<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
		<a href="index.php?com=buypackages"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>	
	</div>
	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		//var value = $('#views').val();
		//	if ( value.length >= 2){
					cm('SAVEREC',1,0,'');//document.getElementById("submitBtn").disabled = false;
		//	} //else {
				//document.getElementById("submitBtn").disabled = true;
				//alert('2 chars');
			//}
		}
	</script>
	<? } 
} else { ?>
	<div class="row-fluid">
		<div class="span12">
			<div class="box>
				<div class="box-title"><h3><i class="icon-table"></i> <?=buy?></h3>
			</div>
			<div class="box-content nopadding">
				<table class="table table-hover table-nomargin">
					<thead>
						<tr>
							<th>Πακέτο</th>
							<th>Προβολές</th>
							<th>Τιμή</th>
							<th>Ποσότητα</th>
							<!--<th><? //=action?></th>-->
						</tr>
					</thead>
					
					<tbody> 
					<?	
						$filter="";
                        $query = "SELECT * FROM ads_packages WHERE 1=1 ".$filter." ORDER BY views ";
						$result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
                                    <td><?=$dr["name"]?></td>
									<td><?=$dr["views"]?></td>
									<td><?=$dr["price"]?></td>
									<td style="width:100px;">
										<select style="padding:1px; width:100px;" name='p<?=$dr["package_id"]?>' id='p<?=$dr["package_id"]?>'>
										  <option value="0" selected>0</option>
										  <option value="1">1</option>
										  <option value="2">2</option>
										  <option value="3">3</option>
										  <option value="4">4</option>
										  <option value="5">5</option>
										  <option value="6">6</option>
										  <option value="7">7</option>
										  <option value="8">8</option>
										  <option value="9">9</option>
										  <option value="10">10</option>  
										</select>
									</td>
									<!--
                                    <td>
                                        <a style="padding:4px" target="_blank" href="http://www.spotyy.com/viva/buypackage.php?item=<? //=$dr["package_id"]?>"><i class="icon-edit"></i> <?//=buy?></a>
                                    </td>
									-->
                                </tr>
                            <?
                        }
                        $db->sql_freeresult($result);
                    ?>
					</tbody>
				</table>
				<div class="form-actions">
					<button type="submit" class="btn btn-primary" name="Command" value="SAVE"><?=buy?></button>
				</div>
			</div>                
		</div>        
	</div>

	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title"><h3><i class="icon-table"></i><?=$nav?></h3></div>
			</div>
			<div class="box-content nopadding">
				<table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
					<thead>
						<tr>
							<th>Διαφήμιση</th>
							<th>Τιμή</th>
							<th>Κατάσταση</th>
							<th>Ημ/νια καταχώρησης</th>
							<th><?=action?></th>
						</tr>
					</thead>
					
					<tbody> 
					<?	
						$filter="";
						if($auth->UserType != "Administrator") { 
							//$filter=" AND t2.user_id=". $auth->UserId;
							//$filter=" AND (t2.user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR t2.user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
							$filter=" AND (t2.user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent']).")";
						}
                        $query = "SELECT * FROM ads_orders
						WHERE 1=1 ".$filter." ORDER BY date_insert ";
						$result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
                                    <td><?=$dr["order_id"]?></td>
									<td><?=$dr["amount"]?></td>
									<td><?
									$isPaid=($dr["paid"]=='True'?'Πληρώθηκε':'Δεν πληρώθηκε');
									$isValid=($dr["paid"]=='True'?'Ενεργό':'Ανενεργό');
									echo $isPaid.'/'.$isValid;
									?></td>
									<td><?=$dr["date_insert"]?></td>
                                    <td>
										<? if($auth->UserType == "Administrator") { ?>
											<a style="padding:4px"  href="index.php?com=buypackages&item=<?=$dr["order_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <? } ?>
										<a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=buypackages&Command=DELETE&item=<?=$dr["order_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
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
