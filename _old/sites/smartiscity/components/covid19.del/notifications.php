<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
if($auth->UserType != "Administrator") Redirect("index.php");

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
				$PrimaryKeys["notification_id"] = intval($_GET["item"]);
				$QuotFields["notification_id"] = true;
				
			} else {
				$Collector["date_insert"] = date('Y-m-d H:i:s');
				$QuotFields["date_insert"] = true;
			}

			$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
			$QuotFields["is_valid"] = true;
			
			$Collector["title"] = $_POST["title"];
			$QuotFields["title"] = true;
			
			$Collector["description"] = $_POST["description"];
			$QuotFields["description"] = true;
	
			$db->ExecuteUpdater("notifications",$PrimaryKeys,$Collector,$QuotFields);
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
					$db->sql_query("DELETE FROM notifications WHERE notification_id=" . $item.$filter);
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
	$query="SELECT * FROM notifications WHERE notification_id=".$_GET['item'].$filter;
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["notification_id"]) && intval($_GET["item"])> 0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=notifications");
	}

	?>
	
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
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
						<a href="index.php?com=notifications"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		var title = $('#title').val();
		//var user_name = $('#user_name').val();

			if ( title.length >= 2 ){ //&& user_name.length >= 5 && user_password.length >= 5
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
							<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
						</div>
						<h2 class="card-title"><?=$nav?></h2>
					</header>
					<div class="card-body">
						<table class="table table-bordered table-striped mb-0" id="datatable-default">
							<thead>
								<tr>
									<th><?=active?></th>
									<th>Τίτλος</th>
									<th>Ημ/νία εισαγωγής</th>
									<th>Ενέργεια</th>
								</tr>
							</thead>
							<tbody>
								<?	
								$filter="";
									$query = "SELECT * FROM notifications WHERE 1=1 ".$filter." ORDER BY date_insert DESC ";
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												<td><?=$dr["is_valid"]?></td>
												<td><?=$dr["title"]?></td>
												<td><?=$dr["date_insert"]?></td>
												<td>
													<a style="padding:4px"  href="index.php?com=notifications&Command=edit&item=<?=$dr["notification_id"]?>"><i class="icon-edit"></i> Επεξεργασία</a>
													<a href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=notifications&Command=DELETE&item=<?=$dr["notification_id"]?>');"><span><i class="icon-trash"></i>Διαγραφή</a></span></a>
												</td>
											</tr>
										<?
									}
									$db->sql_freeresult($result);
								?>
							</tbody>
						</table>
						<div class="row-fluid" style="margin-top:20px;">
							<a href="index.php?com=notifications&item="><button type="button" class="btn btn-primary">Νέα εγγραφή</button></a>
						</div>
					</div>
				</section>
			</div>
		</div>
<? } ?> 
			<div class="inner-wrapper">
				<section role="main" class="content-body">
				
<section class="content-with-menu mailbox">
	<div class="content-with-menu-container" data-mailbox data-mailbox-view="folder">
		<div class="inner-body mailbox-folder">
			<!-- START: .mailbox-header -->
			<header class="mailbox-header">
				<div class="row">
					<div class="col-md-6">
						<h1 class="mailbox-title font-weight-light m-0">
							<a id="mailboxToggleSidebar" class="sidebar-toggle-btn trigger-toggle-sidebar">
								<span class="line"></span>
								<span class="line"></span>
								<span class="line"></span>
								<span class="line line-angle1"></span>
								<span class="line line-angle2"></span>
							</a>
							<?=$nav?>
						</h1>
					</div>
					<div class="col-md-6">
					</div>
				</div>
			</header>

			<div id="mailbox-email-list" class="mailbox-email-list">
				<div class="nano">
					<div class="nano-content" style="position:inherit;">
						<ul id="" class="list-unstyled">
							<li>
								<a href="mailbox-email.html">
									<div class="col-sender">
										<div class="checkbox-custom checkbox-text-primary ib">
											<input type="checkbox" id="mail3">
											<label for="mail3"></label>
										</div>
										<p class="m-0 ib">Okler Themes</p>
									</div>
									<div class="col-mail">
										<p class="m-0 mail-content">
											<span class="subject">Check out our new Porto Admin theme! &nbsp;–&nbsp;</span>
											<span class="mail-partial">We are proud to announce that our new theme Porto Admin is ready, wants to know more about it?</span>
										</p>
										<p class="m-0 mail-date">Jul 03</p>
									</div>
								</a>
							</li>
							<li>
								<a href="mailbox-email.html">
									<i class="mail-label" style="border-color: #EA4C89"></i>
									<div class="col-sender">
										<div class="checkbox-custom checkbox-text-primary ib">
											<input type="checkbox" id="mail2">
											<label for="mail2"></label>
										</div>
										<p class="m-0 ib">Okler Themes</p>
									</div>
									<div class="col-mail">
										<p class="m-0 mail-content">
											<span class="subject">Porto Admin theme! &nbsp;–&nbsp;</span>
											<span class="mail-partial">Check it out.</span>
										</p>
										<!-- <i class="mail-attachment fas fa-paperclip"></i> -->
										<p class="m-0 mail-date">3:35 pm</p>
									</div>
								</a>
							</li>

						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
				</section>
			</div>