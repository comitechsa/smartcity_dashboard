<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
?>
<?

$Message = "";
if(isset($_POST["fpass"]) && $_POST["fpass"] != "" && $_POST["uemail"] != "" )
{
	$usrname = trim(str_replace("'","''",$_POST['uemail']));
	$PrimaryKeys = array();
	$QuotFields = array();
	$PrimaryKeys["email"] = $usrname;
	$QuotFields["email"] = true;
	$dr = $db->RowSelector("users",$PrimaryKeys,$QuotFields);
	if(isset($dr["email"]))
	{
		$MailContentAdmin = forgotMyPass;
		$MailContentAdmin .= "<br>".password.": " . $dr["user_password"];
		SendMail($MailContentAdmin,forgotMyPass." :: " . site_title, $usrname);
		Redirect(CreateUrl(array("com"=>"login")));
	}
	else
	{
		$Message = "<br><div class='control-group error'>".mailNotExist."</div>";
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

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="description" content="<?=(isset($config["metaDesciption"]) ? $config["metaDesciption"] : "")?>" />
		<meta name="keywords" content="<?=(isset($config["metaKeys"]) ? $config["metaKeys"] : "")?>" />
		<script language="javascript">var recordSelect="<?=core_recordSelect;?>";var CurrentLanguage = "<?=$auth->LanguageCode?>";var BaseUrl = "<?=$config["siteurl"]?>";
		</script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/core.js"></script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/public.js"></script>
		<link rel="stylesheet" type="text/css" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/theme.css">		
        <script type="text/javascript">var ajaxUrl= "<?=$config["ajaxUrl"]?>"</script>
		
		<!-- Basic -->
		<meta charset="UTF-8">

		<title><?=$site_title?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$auth->LanguageCharset?>"/>
		<meta name="description" content="<?=(isset($config["metaDesciption"]) ? $config["metaDesciption"] : "")?>" />
		<meta name="keywords" content="<?=(isset($config["metaKeys"]) ? $config["metaKeys"] : "")?>" />

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
	<body>
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">
					<a href="index.php" class="logo" style="margin:2px 0 0 10px;">
						<img src="images/Smart_Speech_logo.png" width="60" alt="SmartSpeech" />
					</a>

				<div class="panel card-sign">
					<div class="card-title-sign mt-3 text-right">
						<h2 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-user mr-1"></i> <?=forgotMyPass?></h2>
					</div>
					<div class="card-body">
						<div class="alert alert-info">
							<p class="m-0">Παρακαλούμε εισάγετε το e-mail επικοινωνίας και ακολουθείστε τις οδηγίες που θα λάβετε</p>
						</div>

						<form id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();">
							<input type="hidden" name="Command" id="Command" value="-1">
							<div class="form-group mb-0">
								<div class="input-group">
									<input type="text" name='uemail' placeholder="Email" class="form-control form-control-lg">
									
									<span class="input-group-append">
										<button class="btn btn-primary btn-lg" type="submit">Reset!</button>
									</span>
								</div>
							</div>

							<p class="text-center mt-3">Remembered? <a href="index.php?com=login">Sign In!</a></p>
						</form>
					</div>
				</div>

				<p class="text-center text-muted mt-3 mb-3">&copy; Copyright 2017. All Rights Reserved.</p>
			</div>
		</section>
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

	</body>
</html>