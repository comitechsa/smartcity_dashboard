<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
		include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
?>
<?

$Message = "";
$inNewPanel = false;
if(isset($_GET["activate"]) && $_GET["activate"] != "")
{
	$PrimaryKeys = array();
	$QuotFields = array();
	$PrimaryKeys["activate"] = $_GET["activate"];
	$QuotFields["activate"] = true;
	$dr = $db->RowSelector("users",$PrimaryKeys,$QuotFields);
	if(isset($dr) && isset($dr["activate"]))
	{
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		
		$PrimaryKeys["user_id"] = $dr["user_id"];
		$QuotFields["user_id"] = true;
		
		//$Collector["activate"] = "";
		//$QuotFields["activate"] = true;
		
		//$Collector["is_valid"] = "True";
		//$QuotFields["is_valid"] = true;
	
		//$db->ExecuteUpdater("users",$PrimaryKeys,$Collector,$QuotFields);
		
		//$bsRedir = isset($_GET["redir"]) && $_GET["redir"] != "" ? urldecode($_GET["redir"]) : CreateUrl(array("com"=>"index"));
		$bsRedir = isset($_GET["redir"]) && $_GET["redir"] != "" ? urldecode($_GET["redir"]) : CreateUrl(array("com"=>"info"));
		//https://get.smartspeech.eu/index.php?com=info
		Redirect("index.php?com=info");

		if(Login($dr["user_name"], $dr["user_password"]))
		{
			Redirect($bsRedir);
		}
	}
}


if(isset($_POST["register"]) && $_POST["r_name"] != "" && $_POST["r_email"] != "" && $_POST["r_pwd"] != "")
{
	$PerformRegister = true;
	
	$PrimaryKeys = array();
	$QuotFields = array();
	$PrimaryKeys["user_name"] = $_POST["r_email"];
	$QuotFields["user_name"] = true;
	$dr = $db->RowSelector("users",$PrimaryKeys,$QuotFields);
	if(isset($dr) && isset($dr["user_name"]))
	{
		$PerformRegister = false;
		$Message = mailExist;
	}

	$PrimaryKeys = array();
	$QuotFields = array();
	$PrimaryKeys["email"] = $_POST["r_email"];
	$QuotFields["email"] = true;
	$dr = $db->RowSelector("users",$PrimaryKeys,$QuotFields);
	if(isset($dr) && isset($dr["email"]))
	{
		$PerformRegister = false;
		$Message = mailExist;
	}

	if($PerformRegister)
	{		
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		
		$Collector["user_auth"] = "Register";
		$QuotFields["user_auth"] = true;
		
		$Collector["user_name"] = $_POST["r_email"];
		$QuotFields["user_name"] = true;
		
		$Collector["user_password"] = $_POST["r_pwd"];
		$QuotFields["user_password"] = true;
		
		$Collector["user_fullname"] = $_POST["r_name"];
		$QuotFields["user_fullname"] = true;
		
		$Collector["email"] = $_POST["r_email"];
		$QuotFields["email"] = true;
		$Collector["date_insert"] = date('Y-m-d H:i:s');
		$QuotFields["date_insert"] = true;	
		
		$Collector["is_valid"] = 'False';
		$QuotFields["is_valid"] = true;
		
		$Activate = makePassword();
		$Collector["activate"] = $Activate;
		$QuotFields["activate"] = true;
		
		$db->ExecuteUpdater("users",$PrimaryKeys,$Collector,$QuotFields);
		
		$__id = $db->sql_nextid();
		if($__id != "")
		{
			$MailContentAdmin = newRecord."<br><br>";
			$MailContentAdmin .= "email: " . $_POST["r_email"];
			$MailContentAdmin .= "<br>Fullname: " . $_POST["r_name"];
			SendMail($MailContentAdmin,"admin-EMAIL FROM " . site_title . ":: Member Registration");
			
			$MailContent = user_activateMsg;		
			$MailContent = str_replace("#SITENAME#",site_title,$MailContent);
			$MailContent = str_replace("#USERNAME#",$_POST["r_email"],$MailContent);
			$MailContent = str_replace("#USERPASSWORD#",$_POST['r_pwd'],$MailContent);
			$MailContent = str_replace("#USEREMAIL#",$_POST["reg_email"],$MailContent);
			$MailContent = str_replace("#USERFULLNAME#",$_POST["r_name"],$MailContent);
			$MailContent = str_replace("#ACTIVATE#", CreateUrl(array("com"=>"register","activate"=>$Activate)),$MailContent);
			
			SendMail($MailContent,'Registration' . " :: " . site_title,$_POST["r_email"]);
			//$mailsend = 'email sendto:'.$_POST["r_email"];
			$Message = user_regMsg;
			$inNewPanel = true;
		}
	}
}


	LoadNoCacheHeader();
	LoadCharSetHeader();
?>
<!doctype html>
<html class="fixed">
	<head>
		<? 
		$site_title = site_title;
		if(isset($config["title"])) {$site_title = strip_tags($config["title"]);}
		else if(isset($config["navigation"])) {$site_title = strip_tags($config["navigation"]);}
		?>
		<title><?=$site_title?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="description" content="<?=(isset($config["metaDesciption"]) ? $config["metaDesciption"] : "")?>" />
		<meta name="keywords" content="<?=(isset($config["metaKeys"]) ? $config["metaKeys"] : "")?>" />
		<script language="javascript">var recordSelect="<?=core_recordSelect;?>";var CurrentLanguage = "<?=$auth->LanguageCode?>";var BaseUrl = "<?=$config["siteurl"]?>";</script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/core.js"></script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/public.js"></script>
		<link rel="stylesheet" type="text/css" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/theme.css">		
        <script type="text/javascript">var ajaxUrl= "<?=$config["ajaxUrl"]?>"</script>
		
		<!-- Basic -->
		<meta charset="UTF-8">

		<meta name="keywords" content="HTML5 Admin Template" />
		<meta name="description" content="Porto Admin - Responsive HTML5 Template">
		<meta name="author" content="okler.net">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css" />
		<link rel="stylesheet" href="vendor/animate/animate.css">

		<link rel="stylesheet" href="vendor/font-awesome/css/all.min.css" />
		<link rel="stylesheet" href="vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="css/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="css/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="css/custom.css">

		<!-- Head Libs -->
		<script src="vendor/modernizr/modernizr.js"></script>

	</head>
	<body style='background-image: url("images/backreg1.jpg");background-size: cover;'>
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
				<div class="panel card-sign">

					<div class="card-title-sign mt-3 text-right">
						<h2 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-user mr-1"></i> Εγγραφή</h2>
					</div>
					<div class="card-body" style="padding-bottom:40px;padding-top:0;">
						<div class="form-group mb-3">
							<a href="/" class="logo float-left">
								<img src='/img/Smart_Speech_logo.png' alt="SmartSpeech" class='retina-ready' width="140px">
							</a>
						</div>
						<?
						if($inNewPanel)
						{
							?>
								<br />
								<div style="padding:5px">
									<? //=user_regMsg?>
									<?=$Message?>
								</div>
								<br />
							<?
						}
						else
						{?>
						<!-- <form id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();"  class='form-vertical form-validate'>-->
						<form id="__PageForm" name="__PageForm" method="post">
						<input type="hidden" name="Command" id="Command" value="-1" >
							<div class="form-group mb-3">
								<label>Ονοματεπώνυμο</label>
								<input name="r_name" id="r_name" value="<?=$_POST["r_name"]?>" type="text" class="form-control form-control-lg" />
							</div>

							<div class="form-group mb-3">
								<label>Διεύθυνση E-mail</label>
								<input name="r_email" id="r_email" value="<?=$_POST["r_email"]?>" type="email" class="form-control form-control-lg" />
							</div>

							<div class="form-group mb-0">
								<div class="row">
									<div class="col-sm-6 mb-3">
										<label>Κωδικός</label>
										<input name="r_pwd" id="pwfield" type="password" class="form-control form-control-lg" />
									</div>
									<div class="col-sm-6 mb-3">
										<label>Επαλήθευση κωδικού</label>
										<input name="r_pwdc" id="confirmfield"  type="password" class="form-control form-control-lg" />
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-8">
									<div class="checkbox-custom checkbox-default">
										<input id="AgreeTerms" name="agreeterms" type="checkbox" />
										<label for="AgreeTerms">Συμφωνώ με τους 
										<a class="mb-1 mt-1 mr-1 modal-sizes" href="#modalLG">όρους χρήσης και την Πολιτική για τα Προσωπικά Δεδομένα</a></label>
									</div>
								</div>
								<div class="col-sm-4 text-right">
									<div id="registerButton">
										<button type="submit" id="register" name="register" disabled class="btn btn-primary mt-2 ">Εγγραφή</button>
									</div>
								</div>
							</div>
							<!-- 
							<span class="mt-3 mb-3 line-thru text-center text-uppercase">
								<span>or</span>
							</span>

							<div class="mb-1 text-center">
								<a class="btn btn-facebook mb-3 ml-1 mr-1" href="#">Connect with <i class="fab fa-facebook-f"></i></a>
								<a class="btn btn-twitter mb-3 ml-1 mr-1" href="#">Connect with <i class="fab fa-twitter"></i></a>
							</div>							
							-->


							<p class="text-center">Έχετε ήδη λογαριασμό? <a href="index.php?com=login">Είσοδος</a></p>

						</form>
						<? } ?>
					</div>
				</div>

				<p class="text-center text-muted mt-3 mb-3">&copy; Copyright 2020. All Rights Reserved.</p>
			</div>
		</section>
		<!-- terms-->
		<div id="modalLG" class="modal-block modal-block-lg mfp-hide">
			<section class="card">
				<header class="card-header">
					<h2 class="card-title">Όροι χρήσης και Πολιτική για τα Προσωπικά Δεδομένα</h2>
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
						<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>-->
						<button class="card-action card-action-dismiss  modal-dismiss" style="border:none;"></button>
					</div>
				</header>
				<div class="card-body" style="max-height:400px;overflow:scroll;overflow-x: hidden;">
					<div class="modal-wrapper">
						<div class="modal-text">
							<!-- <p class="mb-0">Terms</p> -->
							<ul>
								<li>Τα στοιχεία που συλλέγονται βρίσκονται από επεξεργασία από τους εταίρους του έργου ένα δίκτυο συνεργατών (τεχνικών υπηρεσιών, κλινικών, κλπ) οι οποίοι έχουν δεσμευθεί για την εχεμύθεια και εμπιστευτικότητα των στοιχείων</li>
								<li>Θα εφαρμοσθούν τεχνικές ψευδωνυμοποίησης ώστε κάθε γονέας και κάθε παιδί να λαμβάνουν έναν κωδικό ID, μόνο το οποίο θα είναι γνωστό στα μέλη της ομάδας που εργάζονται στο έργο. Μόνο οι κλινικοί οι οποίοι δεσμεύονται με το ιατρικό απόρρητο και οι δάσκαλοι θα έχουν τη δυνατότητα “re-identification” των παιδιών και γονέων, δλδ ταυτοποίησης κωδικού με όνομα και επίθετο.</li>
								<li>Κανένα στοιχείο της ολοκληρωμένης βάσης δεν θα μεταβιβασθεί σε τρίτους για εμπορικούς και μη λόγους. Τα μόνα στοιχεία που θα δημοσιευθούν θα είναι συγκεντρωτικά στοιχεία για τα αποτελέσματα της έρευνας. Ακόμη και η στατιστική ανάλυση των στοιχείων βασίζεται σε στοιχεία ID.</li>
								<li>Σε τακτά χρονικά διαστήματα θα ζητείται επικαιροποίηση των στοιχείων του χρήστη (e-mail), για ένα χρονικό διάστημα έως το πέρας του ερευνητικού έργου.</li>
								<li>Εάν σε οποιαδήποτε σημείο στην πορεία του χρόνου, αλλάξει γνώμη για τη συμμετοχή του στην έρευνα, τα στοιχεία και το προφίλ του θα διαγραφούν και θα ενημερωθεί για τη διαγραφή τους. </li>
								<li>Τα δεδομένα θα διατηρηθούν στη βάση δεδομένων του έργου για τα επόμενα 3 χρόνια, στο «CLOUD». Τα δεδομένα αυτά θα είναι:
									<ul>
										<li>Δεδομένα υγείας – ιστορικού παιδιού / παιδιών</li>
										<li>Βιομετρικά Δεδομένα παιδιού / παιδιών</li>
										<li>Δεδομένα «scoring» στο παιχνίδι</li>
										<li>Δεδομένα σχετικά με «Clinician’s Assessment Report» για κάθε παιδί που παίζει το παιχνίδι.</li>
									</ul>
								</li>
								<li>Οποιαδήποτε στιγμή ο ενδιαφερόμενος χρήστης μπορεί να ζητήσει αντίγραφο του ψηφιακού του «αποτυπώματος» στην εφαρμογή.</li>
							</ul>

						</div>
					</div>
				</div>
				<footer class="card-footer">
					<div class="row">
						<div class="col-md-12 text-right">
							<!-- <button class="btn btn-primary modal-confirm">Confirm</button>-->
							<button class="btn btn-default modal-dismiss">Close</button>
						</div>
					</div>
				</footer>
			</section>
		</div>
		<!-- end: page -->

		<!-- Vendor -->
		<script src="vendor/jquery/jquery.js"></script>
		<script src="vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="vendor/popper/umd/popper.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.js"></script>
		<script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="vendor/common/common.js"></script>
		<script src="vendor/nanoscroller/nanoscroller.js"></script>
		<script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		<!-- Theme Base, Components and Settings -->
		<script src="js/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="js/custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="js/theme.init.js"></script>
		<script src="js/examples/examples.modals.js"></script>
		<script>
		/*
		//$('#register').attr('disabled', true);
		$('#register').click(function (event) {
		if ($(this).hasClass('disabled')) {
				alert('Πρέπει να αποδεχθείτε τους όρους χρήσης');
			} else {
				//alert('Not disabled. =)');
				$("#__PageForm").submit();
			}
		});
		$('#AgreeTerms').click(function () {
			if ($(this).is(':checked')) {
				$('#register').removeClass("disabled");
			} else {
				$('#register').addClass("disabled");
			}
		});
		
		*/
//$("#registerButton").click(function(){
//	alert("123");
//});
		$('#register').attr('disabled', true);
		$('#AgreeTerms').click(function () {
				//check if checkbox is checked
				if ($(this).is(':checked')) {
					$('#register').removeAttr('disabled'); //enable input
				} else {
					$('#register').attr('disabled', true); //disable input
					$('#registerButton').click(function(){
						alert("Πρέπει να αποδεχθείτε τους όρους χρήσης");
					});
				}
			});
		

		</script>
	</body>
	<? $validator->RenderValidators();?>
	<? $messages->RenderMessages();?>
</html>