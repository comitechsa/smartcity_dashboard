<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."/perm.php");
	$permissions = (intval($auth->UserRow['access'])>0?$auth->UserRow['access']:0);
	if (!($permissions & $FLAG_400) &&  !$auth->UserType == "Administrator") {
		Redirect("index.php");
	}
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") Redirect("index.php");

global $nav;
$nav = "Ειδοποιήσεις";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=notificationslist";


if(isset($_GET["item"])) {
	//$filter=" WHERE 1=1 AND user_auth!='Administrator '";
	//$filter.=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
	$query="SELECT * FROM notifications WHERE notification_id=".$_GET['item'].$filter;
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["notification_id"]) && intval($_GET["item"])> 0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=notificationslist");
	}
	?>
	<?
	//check if already readed
	$readed=$db->RowSelectorQuery("SELECT * FROM notificationread WHERE notification_id=".$_GET['item']." AND user_id=".$auth->UserId);
	//echo "SELECT * FROM notificationread WHERE notification_id=".$_GET['item']." AND user_id=".$auth->UserId;
	if(intval($readed['notification_id'])==0){
		$result = $db->sql_query("INSERT INTO notificationread (notification_id, user_id, date_insert) VALUES ('".$_GET['item']."','".$auth->UserId."','".date('Y-m-d H:i:s')."')");
	}
	?>
	<div class="inner-body mailbox-email">
		<div class="mailbox-email-header mb-3">
			<!-- <h3 class="mailbox-email-subject m-0 font-weight-light">Released Porto Admin! (3)</h3> -->
		</div>
		<div class="mailbox-email-container">
			<div class="mailbox-email-screen pt-4">
				<div class="card mb-3">
					<div class="card-header">
						<div class="card-actions">
							<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						</div>
						<p class="card-title">Ειδοποιήσεις <i class="fas fa-angle-right fa-fw"></i> <?=$dr_e['title']?></p>
					</div>
					<div class="card-body">
						<?=$dr_e['description']?>
					</div>
					<div class="card-footer">
						<p class="m-0"><small>Ημερομηνία αποστολής: <?=$dr_e['date_insert']?></small></p>
					</div>
				</div>
			</div>

			<div class="compose pt-3">
				<div class="text-right mt-3">
					<a href="index.php?com=notificationslist" class="btn btn-primary"><i class="fas fa-reply-all mr-1"></i>Επιστροφή</a>
				</div>
			</div>
		</div>
	</div>
	
	   
	<?
} else 	{
	?>    
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
									<?	
										$filter="";
										$query = "SELECT t1.*,t2.user_id,t2.date_insert AS date_read FROM notifications t1 LEFT JOIN notificationread t2 ON t1.notification_id=t2.notification_id
										WHERE t1.is_valid='True' AND ORDER BY t1.date_insert DESC ";
										$result = $db->sql_query($query);
										$counter = 0;
										while ($dr = $db->sql_fetchrow($result))
										{
									?>
										<li style="font-size:12px;border-top:1px dotted #aaa;">
											<a href="index.php?com=notificationslist&item=<?=$dr['notification_id']?>">
												<?=($dr['user_id']==''?'<i class="mail-label" style="border-color: #EA4C89"></i>':'')?>
												<div class="col-sender">
													<div class="checkbox-custom checkbox-text-primary ib">
														<input type="checkbox" <?=($dr['user_id']!=''?'checked':'')?> disabled id="mail2">
														<label for="mail2"></label>
													</div>
													<!-- <p class="m-0 ib"><? //=$dr['title']?></p> -->
													<p class="m-0 mail-date"><?=$dr['date_insert']?></p>
												</div>
												<div class="col-mail">
													<p class="m-0 mail-content"><span class="subject"><?=$dr['title']?></span></p>
													<?
													$dateRead=date_create($dr['date_read']);  
													$newDateRead = date_format($dateRead,"Y/m/d H:i:s");  
													?>
													<!-- <i class="mail-attachment fas fa-paperclip"></i> -->
													<p class="m-0 mail-date" style="width:200px;"><?=$newDateRead?></p>
												</div>
											</a>
										</li>
									<? } ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</section>
</div>
<? } ?> 
