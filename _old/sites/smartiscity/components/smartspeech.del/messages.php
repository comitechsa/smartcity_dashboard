<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."/perm.php");
	$permissions = (intval($auth->UserRow['access'])>0?$auth->UserRow['access']:0);
	if (!($permissions & $FLAG_300) &&  !$auth->UserType == "Administrator") {
		Redirect("index.php");
	}
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") Redirect("index.php");
if($auth->UserType != "Administrator" && !($_SESSION["permissions"] & $FLAG_300)) Redirect("index.php");

global $nav;
$nav = "Μηνύματα";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=messages";
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
				$PrimaryKeys["message_id"] = intval($_GET["item"]);
				$QuotFields["message_id"] = true;
				
			} else {
				$Collector["date_insert"] = date('Y-m-d H:i:s');
				$QuotFields["date_insert"] = true;
			}

			$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
			$QuotFields["is_valid"] = true;
			
			$Collector["title"] = $_POST["title"];
			$QuotFields["title"] = true;
			
			$Collector["user_id"] = $_POST["user_id"];
			$QuotFields["user_id"] = true;
			
			$Collector["description"] = $_POST["description"];
			$QuotFields["description"] = true;
	
			$db->ExecuteUpdater("messages",$PrimaryKeys,$Collector,$QuotFields);
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
					$db->sql_query("DELETE FROM messages WHERE message_id=" . $item.$filter);
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
	//$filter.=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
	$filter=" AND user_id=".$auth->UserId;
	$query="SELECT * FROM messages WHERE 1=1 AND message_id=".$_GET['item'].$filter;
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["message_id"]) && intval($_GET["item"])> 0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=messages");
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
							<label class="col-lg-3 control-label text-lg-right pt-2" for="name">Επιλογή χρήστη</label>
							<div class="col-lg-6">
								<select id='user_id' name="user_id" class="form-control mb-3">
									<option >Επιλογή</option>
									<?
										$filter="";
										$query = "SELECT * FROM users WHERE 1=1 ".$filter." ORDER BY user_fullname ";
										$result = $db->sql_query($query);
										while ($dr = $db->sql_fetchrow($result)){
											echo '<option value="'.$dr['user_id'].'" '.($dr['user_id']==$_GET['item']?" selected":"").'>'.$dr['user_fullname'].' / ('.$dr['email'].')'.'</option>';
										}
									?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="name">Τίτλος</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="title" name="title" value="<?=(isset($dr_e["title"]) ? $dr_e["title"]:'')?>">
							</div>
						</div>
						
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="description">Περιγραφή</label>
							<div class="col-lg-6">
								<textarea class="form-control" name="description" id="description" rows="3"  data-plugin-textarea-autosize><?=$dr_e["description"]?></textarea>
							</div>
						</div>
					</div>
					<div class="row-fluid" style="margin-top:20px;">
						<a href="#" onClick="checkFields();">   <button type="button" class="btn btn-primary">Αποθήκευση</button></a>
						<a href="index.php?com=messages"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		var title = $('#title').val();
		var user = $('#user_id').val();
		//var user_name = $('#user_name').val();

			if ( title.length >= 2 && user.length>=1){ //&& user_name.length >= 5 && user_password.length >= 5
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
		<div class="row">
			<div class="col">
				<section class="card">
					<header class="card-header">
						<div class="card-actions">
							<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
							<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
						</div>
						<h2 class="card-title"><?=$nav?></h2>
					</header>
					<div class="card-body">
						<table class="table table-bordered table-striped mb-0" id="datatable-default">
							<thead>
								<tr>
									<th><?=active?></th>
									<th>Χρήστης</th>
									<th>Τίτλος</th>
									<th>Ημ/νία εισαγωγής</th>
									<th>Ενέργεια</th>
								</tr>
							</thead>
							<tbody>
								<?	
									$filter="";
									$query = "SELECT t1.*,t2.user_fullname FROM messages t1 INNER JOIN users t2 ON t1.user_id=t2.user_id WHERE 1=1 ".$filter." ORDER BY t1.date_insert DESC ";
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												<td><?=($dr["is_valid"]=='True'?'Ναι':'Οχι')?></td>
												<td><?=$dr["user_fullname"]?></td>
												<td><?=$dr["title"]?></td>
												<td><?=$dr["date_insert"]?></td>
												<td>
													<a style="padding:4px"  href="index.php?com=messages&Command=edit&item=<?=$dr["message_id"]?>"><i class="icon-edit"></i> Επεξεργασία</a>
													<a href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=messages&Command=DELETE&item=<?=$dr["message_id"]?>');"><span><i class="icon-trash"></i>Διαγραφή</a></span></a>
												</td>
											</tr>
										<?
									}
									$db->sql_freeresult($result);
								?>
							</tbody>
						</table>
						<div class="row-fluid" style="margin-top:20px;">
							<a href="index.php?com=messages&item="><button type="button" class="btn btn-primary">Νέο μήνυμα</button></a>
						</div>
					</div>
				</section>
			</div>
		</div>
<? } ?> 

