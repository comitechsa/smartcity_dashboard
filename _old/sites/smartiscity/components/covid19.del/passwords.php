<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") Redirect("index.php");

global $nav;
$nav = "Κωδικοί";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=passwords";
$command=array();
$command=explode("&",$_POST["Command"]);

//if( $auth->UserType == "Administrator" )
//{
	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "CREATE")
		{
			require_once 'otp/GoogleAuthenticator.php';
			$ga = new PHPGangsta_GoogleAuthenticator();
			$secret = $ga->createSecret();
			//$qrCodeUrl = $ga->getQRCodeGoogleUrl('OneTimePassword', $sec);
			//echo '<p><img src='.$qrCodeUrl.'</p><br>';
			$code=$ga->getCode($sec);
			$usercode=$_POST["usercode"];
	
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
			
			$Collector["user_id"] = $auth->UserRow['user_id'];
			$QuotFields["user_id"] = true;

			$Collector["usercode"] = $_POST["usercode"];
			$QuotFields["usercode"] = true;
			
			$Collector["code"] = $code;
			$QuotFields["code"] = true;
			
			$Collector["date_expire"] = date('Y-m-d H:i:s', time() + 86400);
			$QuotFields["date_expire"] = true;
			
			$Collector["secret"] = $secret;
			$QuotFields["secret"] = true;
			
			$Collector["description"] = $_POST["description"];
			$QuotFields["description"] = true;
	
			$db->ExecuteUpdater("passwords",$PrimaryKeys,$Collector,$QuotFields);
			$messages->addMessage("Αποθηκευτηκε!!!");
			//Redirect($BaseUrl);
			
		} else if($_REQUEST["Command"] == "SAVE")
		{
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();

			$PrimaryKeys["password_id"] = intval($_GET["item"]);
			$QuotFields["user_id"] = true;
			
			$Collector["description"] = $_POST["description"];
			$QuotFields["description"] = true;
	
			$db->ExecuteUpdater("passwords",$PrimaryKeys,$Collector,$QuotFields);
			$messages->addMessage("Αποθηκευτηκε!!!");
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
	//$filter.=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
	$query="SELECT * FROM passwords WHERE password_id=".$_GET['item'].$filter;
	$dr_e = $db->RowSelectorQuery($query);
	if(intval($_GET["item"])> 0){
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=passwords");		
	}
	//if (!isset($dr_e["password_id"]) && intval($_GET["item"])> 0) {
	//	$messages->addMessage("NOT FOUND!!!");
	//	Redirect("index.php?com=passwords");
	//}
	?>
<?

	
	//$checkResult = $ga->verifyCode($sec, $ondC, 2);    // 1 = 2*30sec clock tolerance
	//if ($checkResult) {
	//	echo 'OK';
	//} else {
	//	echo 'FAILED';
	//}	

?>
	
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
					</div>
					<h2 class="card-title">Δημιουργία νέου κωδικού</h2>
				</header>
				<div class="card-body">
					<div class="form-horizontal form-bordered" method="get">
						<?
						if($code!=''){
							echo '<h1>Κωδικός για ασθενή:'.$code.'</h1><br>';
						}
						?>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="usercode">Εισαγωγή κωδικού ασθενή</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="usercode" name="usercode" <?=($usercode!=''?'readonly ':'')?>value="<?=$usercode?>">
							</div>
						</div>
						<!-- 
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="code">Κωδικός</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="code" name="code" value="<?=$code?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="secret">Κλειδι</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" id="secret" name="secret" value="<?=$secret?>">
							</div>
						</div>	
						-->
						<!-- 
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="description">Περιγραφή</label>
							<div class="col-lg-6">
								<textarea class="form-control" name="description" id="description" rows="3"  data-plugin-textarea-autosize><?=($_POST['description']!=''?$_POST['description']:$dr_e["description"])?></textarea>
							</div>
						</div>
						-->

					</div>
					<div class="row-fluid" style="margin-top:20px;">
						<?
						if(isset($_REQUEST['usercode'])){
							?>
							<!-- <a href="#" onClick="checkFields();">   <button type="button" class="btn btn-primary">Αποθήκευση</button></a> -->
							<?	
						} else {
							?>
							<a href="#" onClick="checkFieldsCreate();">   <button type="button" class="btn btn-primary">Καταχώρηση</button></a>
							<?
						}
						?>
						
						<a href="index.php?com=passwords"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
					</div>
				</div>
			</section>
		</div>
	</div>
	
	

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		//var usercode = $('#usercode').val();
		//var usercode = $('#code').val();
		//var secret = $('#secret').val();
			//if ( usercode.length >= 5  && code.length >= 5){ //&& secret.length >= 5
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
			//} //else {
				//document.getElementById("submitBtn").disabled = true;
				//alert('2 chars');
			//}
		}
		function checkFieldsCreate(){
		var usercode = $('#usercode').val();
			if ( usercode.length >= 5){
				cm('CREATE',1,0,'');
			}
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
		
						<h2 class="card-title">Δημιουργία κωδικού</h2>
					</header>
					<div class="card-body">
						<table class="table table-bordered table-striped mb-0" id="datatable-default">
							<thead>
								<tr>
									<th>Κωδικός</th>
									<th>Δημιουργία</th>
									<th>Λήξη</th>
									<!-- <th>Κλειδί</th> -->
								</tr>
							</thead>
							<tbody>
								<?	
									$filter=" AND user_id=".$auth->UserRow['user_id'];
									$query = "SELECT * FROM passwords WHERE 1=1 ".$filter." ORDER BY date_insert DESC ";
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												<td><?=$dr["code"]?></td>
												<td><?=$dr["date_insert"]?></td>
												<td><?=$dr["date_expire"]?></td>
												<!-- <td><? //=$dr["secret"]?></td> -->
											</tr>
										<?
									}
									$db->sql_freeresult($result);
								?>
							</tbody>
						</table>
						<div class="row-fluid" style="margin-top:20px;">
							<a href="index.php?com=passwords&item="><button type="button" class="btn btn-primary">Νέα εγγραφή</button></a>
						</div>
					</div>
				</section>
			</div>
		</div>


			
<? } ?> 