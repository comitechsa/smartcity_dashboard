<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."/perm.php");
	//$permissions = (intval($auth->UserRow['access'])>0?$auth->UserRow['access']:0);
	//if (!($permissions & $FLAG_300) &&  !$auth->UserType == "Administrator") {
	//	Redirect("index.php");
	//}
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") Redirect("index.php");
//if($auth->UserType != "Administrator" && !($_SESSION["permissions"] & $FLAG_300)) Redirect("index.php");

global $nav;
$nav = "Ειδοποιήσεις";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=notifications";
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
<div class="container" style="margin-bottom:50px;font-size:14px;">	
	<div class="dashboard-content" style="margin-left:0;padding:0;">
		<div class="row">
			<div class="col-md-12">
				<h4 class="headline margin-top-70 margin-bottom-30" style="font-family: 'Commissioner', sans-serif;"><?=$nav?></h4>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div class="messages-container margin-top-0">
					<div class="messages-headline" style="background-color:#f91942; color:#fff;padding:14px 30px;">
						<h4 style="color:#fff;">Inbox</h4>
					</div>
					
					<div class="messages-inbox">
						<ul>
						<?	$filter=" AND is_valid='True'";
							$query = "SELECT * FROM notifications WHERE 1=1 ".$filter." ORDER BY date_insert DESC ";
							$result = $db->sql_query($query);
							$counter = 0;
							while ($dr = $db->sql_fetchrow($result))
							{
								?>
							<li class="unread">
								<a href="dashboard-messages-conversation.html">
									<div class="message-avatar"><img src="http://www.gravatar.com/avatar/00000000000000000000000000000000?d=mm&amp;s=70" alt="" /></div>

									<div class="message-by">
										<div class="message-by-headline">
											<h5>Διαχειριστής <?=($dr['readed']=='False'?'<i>Unread</i>':'')?></h5>
											<span><?=$dr['date_insert']?></span>
										</div>
										<p><?=$dr['title']?></p>
									</div>
								</a>
							</li>
							<? } ?>
						</ul>
					</div>
				</div>

				<!-- Pagination
				<div class="clearfix"></div>
				<div class="pagination-container margin-top-30 margin-bottom-0">
					<nav class="pagination">
						<ul>
							<li><a href="#" class="current-page">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#"><i class="sl sl-icon-arrow-right"></i></a></li>
						</ul>
					</nav>
				</div>
				 -->
			</div>
		</div>
	</div>	
</div>	
	
	
<? } ?> 

