<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."/perm.php");
	$permissions = (intval($auth->UserRow['access'])>0?$auth->UserRow['access']:0);
	if (!($permissions & $FLAG_200)&&  !$auth->UserType == "Administrator") {
		Redirect("index.php");
	}
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
if($auth->UserType != "Administrator") Redirect("index.php");

global $nav;
$nav = "Ρόλοι";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=roles";
$command=array();
$command=explode("&",$_POST["Command"]);

//if( $auth->UserType == "Administrator" )
//{
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
				$QuotFields["user_id"] = true;
				
			} else {
				$Collector["date_insert"] = date('Y-m-d H:i:s');
				$QuotFields["date_insert"] = true;
			}

			$Collector["FLAG_000"] = isset($_POST["FLAG_000"]) && $_POST["FLAG_000"] == "on" ? "True" : "False";
			$QuotFields["FLAG_000"] = true;

			$Collector["FLAG_100"] = isset($_POST["FLAG_100"]) && $_POST["FLAG_100"] == "on" ? "True" : "False";
			$QuotFields["FLAG_100"] = true;
			
			$Collector["FLAG_200"] = isset($_POST["FLAG_200"]) && $_POST["FLAG_200"] == "on" ? "True" : "False";
			$QuotFields["FLAG_200"] = true;
			
			$Collector["FLAG_300"] = isset($_POST["FLAG_300"]) && $_POST["FLAG_300"] == "on" ? "True" : "False";
			$QuotFields["FLAG_300"] = true;
			
			$Collector["FLAG_400"] = isset($_POST["FLAG_400"]) && $_POST["FLAG_400"] == "on" ? "True" : "False";
			$QuotFields["FLAG_400"] = true;
			
			$Collector["FLAG_500"] = isset($_POST["FLAG_500"]) && $_POST["FLAG_500"] == "on" ? "True" : "False";
			$QuotFields["FLAG_500"] = true;
			
			$Collector["FLAG_600"] = isset($_POST["FLAG_600"]) && $_POST["FLAG_600"] == "on" ? "True" : "False";
			$QuotFields["FLAG_600"] = true;
			
			
			$acc=0;
			$acc=$acc+($_POST["FLAG_000"] == "on"?1:0);
			$acc=$acc+($_POST["FLAG_100"] == "on"?2:0);
			$acc=$acc+($_POST["FLAG_200"] == "on"?4:0);
			$acc=$acc+($_POST["FLAG_300"] == "on"?8:0);
			$acc=$acc+($_POST["FLAG_400"] == "on"?16:0);
			$acc=$acc+($_POST["FLAG_500"] == "on"?32:0);
			$acc=$acc+($_POST["FLAG_600"] == "on"?64:0);
			$acc=$acc+($_POST["FLAG_700"] == "on"?128:0);
			//$acc=$acc+($_POST["FLAG_800"] == "on"?256:0);
			//$acc=$acc+($_POST["FLAG_900"] == "on"?512:0);
			//$acc=$acc+($_POST["FLAG_1000"] == "on"?1024:0);
			//$acc=$acc+($_POST["FLAG_1100"] == "on"?2048:0);
			//$acc=$acc+($_POST["FLAG_1200"] == "on"?4096:0);
			//$acc=$acc+($_POST["FLAG_1300"] == "on"?8192:0);
			//$acc=$acc+($_POST["FLAG_1400"] == "on"?16384:0);
			
			$Collector["access"] = $acc;
			$QuotFields["access"] = true;
			
			$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
			$QuotFields["is_valid"] = true;
			
			$Collector["name"] = $_POST["name"];
			$QuotFields["name"] = true;
			
			$Collector["description"] = $_POST["description"];
			$QuotFields["description"] = true;
	
			$db->ExecuteUpdater("roles",$PrimaryKeys,$Collector,$QuotFields);
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { //$command[0] ==
			if($item != "")
			{
				$error=0;
				//$result = $db->sql_query('SELECT * FROM devices WHERE user_id='.$item);
				//if($db->sql_numrows($result) > 0) $error++;
				//$result = $db->sql_query('SELECT * FROM messages WHERE user_id='.$item);
				//if($db->sql_numrows($result) > 0) $error++;
				if($error==0) {	
					//$filter=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
					$filter="";
					$db->sql_query("DELETE FROM roles WHERE role_id=" . $item.$filter);
					$messages->addMessage("DELETE!!!");
					Redirect($BaseUrl);
				} else {
					$messages->addMessage("Υπάρχουν συνδεδεμένες εγγραφές. Η διαγραφή δεν μπορεί να ολοκληρωθεί");
					Redirect($BaseUrl);
				}
			}
		}
	}
//}

if(isset($_GET["item"])) {
	//$filter=" WHERE 1=1 AND user_auth!='Administrator '";
	$filter.=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
	$query="SELECT * FROM roles WHERE role_id=".$_GET['item'].$filter;
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["role_id"]) && intval($_GET["item"])> 0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=roles");
	}

	?>
	
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
					</div>
					<h2 class="card-title"><?=edit?></h2>
				</header>
				<div class="card-body">
					<div class="form-horizontal form-bordered" method="get">
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="inputDefault"><?=active?></label>
							<div class="col-lg-6">
								<div class="checkbox-custom checkbox-default">
									<input type="checkbox" name="is_valid" id="is_valid" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>>
									<label for="is_valid"></label>
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="name">Ονομασία</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="name" name="name" value="<?=(isset($dr_e["name"]) ? $dr_e["name"]:'')?>">
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="description">Περιγραφή</label>
							<div class="col-lg-6">
								<textarea class="form-control" name="description" id="description" rows="3"  data-plugin-textarea-autosize><?=$dr_e["description"]?></textarea>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="inputDefault">Δικαιώματα</label>
							<div class="col-lg-6">
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="inputDefault">Χρήστες</label>
							<div class="col-lg-6">
								<div class="checkbox-custom checkbox-default">
									<input type="checkbox" name="FLAG_000" id="FLAG_000" <?=((isset($dr_e["FLAG_000"]) && $dr_e["FLAG_000"]=='True') ? 'checked':'')?>>
									<label for="FLAG_000"></label>
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="inputDefault">Αποτελέσματα</label>
							<div class="col-lg-6">
								<div class="checkbox-custom checkbox-default">
									<input type="checkbox" name="FLAG_100" id="FLAG_100" <?=((isset($dr_e["FLAG_100"]) && $dr_e["FLAG_100"]=='True') ? 'checked':'')?>>
									<label for="FLAG_100"></label>
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="inputDefault">Ρόλοι</label>
							<div class="col-lg-6">
								<div class="checkbox-custom checkbox-default">
									<input type="checkbox" name="FLAG_200" id="FLAG_200" <?=((isset($dr_e["FLAG_200"]) && $dr_e["FLAG_200"]=='True') ? 'checked':'')?>>
									<label for="FLAG_200"></label>
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="inputDefault">Μηνύματα</label>
							<div class="col-lg-6">
								<div class="checkbox-custom checkbox-default">
									<input type="checkbox" name="FLAG_300" id="FLAG_300" <?=((isset($dr_e["FLAG_300"]) && $dr_e["FLAG_300"]=='True') ? 'checked':'')?>>
									<label for="FLAG_300"></label>
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="inputDefault">Ειδοποιήσεις</label>
							<div class="col-lg-6">
								<div class="checkbox-custom checkbox-default">
									<input type="checkbox" name="FLAG_400" id="FLAG_400" <?=((isset($dr_e["FLAG_400"]) && $dr_e["FLAG_400"]=='True') ? 'checked':'')?>>
									<label for="FLAG_400"></label>
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="inputDefault">Οδηγίες</label>
							<div class="col-lg-6">
								<div class="checkbox-custom checkbox-default">
									<input type="checkbox" name="FLAG_500" id="FLAG_500" <?=((isset($dr_e["FLAG_500"]) && $dr_e["FLAG_500"]=='True') ? 'checked':'')?>>
									<label for="FLAG_500"></label>
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="inputDefault">Παιδιά</label>
							<div class="col-lg-6">
								<div class="checkbox-custom checkbox-default">
									<input type="checkbox" name="FLAG_600" id="FLAG_600" <?=((isset($dr_e["FLAG_600"]) && $dr_e["FLAG_600"]=='True') ? 'checked':'')?>>
									<label for="FLAG_600"></label>
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="inputDefault">Δημοσκόπηση</label>
							<div class="col-lg-6">
								<div class="checkbox-custom checkbox-default">
									<input type="checkbox" name="FLAG_700" id="FLAG_700" <?=((isset($dr_e["FLAG_700"]) && $dr_e["FLAG_700"]=='True') ? 'checked':'')?>>
									<label for="FLAG_700"></label>
								</div>
							</div>
						</div>
						
						
					</div>
					<div class="row-fluid" style="margin-top:20px;">
						<a href="#" onClick="checkFields();">   <button type="button" class="btn btn-primary">Αποθήκευση</button></a>
						<a href="index.php?com=roles"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		var name = $('#name').val();
		//var user_name = $('#user_name').val();

			if ( name.length >= 2 ){ //&& user_name.length >= 5 && user_password.length >= 5
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
    
    <?
    
	//echo 'usertype: '.$auth->UserRow['admin_type']
	
	?>
	
		<div class="row">
			<div class="col">
				<section class="card">
					<header class="card-header">
						<div class="card-actions">
							<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
							<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
						</div>
		
						<h2 class="card-title">Basic</h2>
					</header>
					<div class="card-body">
						<table class="table table-bordered table-striped mb-0" id="datatable-default">
							<thead>
								<tr>
									<th><?=active?></th>
									<th>Ονομασία</th>
									<th>Ημ/νία εισαγωγής</th>
									<th>Ενέργεια</th>
								</tr>
							</thead>
							<tbody>
								<?	
								$filter="";
									$query = "SELECT * FROM roles WHERE 1=1 ".$filter." ORDER BY name ";
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												<td><?=$dr["is_valid"]?></td>
												<td><?=$dr["name"]?></td>
												<td><?=$dr["date_insert"]?></td>
												<td>
													<a style="padding:4px"  href="index.php?com=roles&Command=edit&item=<?=$dr["role_id"]?>"><i class="icon-edit"></i> Επεξεργασία</a>
													<a href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=roles&Command=DELETE&item=<?=$dr["role_id"]?>');"><span><i class="icon-trash"></i>Διαγραφή</a></span></a>
												</td>
											</tr>
										<?
									}
									$db->sql_freeresult($result);
								?>
							</tbody>
						</table>
						<div class="row-fluid" style="margin-top:20px;">
							<a href="index.php?com=roles&item="><button type="button" class="btn btn-primary">Νέα εγγραφή</button></a>
						</div>
					</div>
				</section>
			</div>
		</div>


			
<? } ?> 