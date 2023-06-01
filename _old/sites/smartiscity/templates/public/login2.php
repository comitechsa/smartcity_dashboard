<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
	include($config["physicalPath"]."languages/".$auth->LanguageCode.".php");
?>
<?
$Message = "";
//$_SESSION['authstation'] = "http://$_SERVER[HTTP_HOST]";
if(isset($_POST["login"]) && $_POST["login"] != "" && $_POST["uemail"] != ""  && $_POST["upw"] != "")
{

	$usrname = trim(str_replace("'","''",$_POST['uemail']));
	$pass = trim(str_replace("'","''",$_POST['upw']));
	
	$bsRedir = isset($_GET["redir"]) && $_GET["redir"] != "" ? urldecode($_GET["redir"]) : "index.php";
	if(Login($usrname,$pass,'Register'))
	{		
		Redirect($bsRedir);
	}
	else if(Login($usrname,$pass,'Administrator'))
	{
		Redirect($bsRedir);
	}
	else
	{
		$Message = "<br><div class='control-group error' style='font-weight:bold; color:#ff0000;'>".wrongInfo."</div>";
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
		<link rel="stylesheet" type="text/css" href="/sites/<?=$config["site"]?>/theme.css">
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
        <!-- Favicon -->
		<link rel="shortcut icon" href="<?=$config["siteurl"]?>favicon.png">
	</head>
	<!--<body style='background-image: url("img/loginback.jpg");background-size: cover;'>-->
	<body style='background-image: url("img/indexback.jpg");background-size: cover;'>
		<!-- start: page -->
		<section class="body-sign">
			<div class="center-sign">


				<div class="panel card-sign" style="opacity:0.9;">
					<div class="card-title-sign mt-3 text-right">
						<h2 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-user mr-1"></i> <?=entrance?></h2>
					</div>
					<div class="card-body" style="padding-bottom:40px;padding-top:0;">
						<div class="form-group mb-3">
							<a href="/" class="logo float-left">
								<img src='/img/Smart_Speech_logo.png' alt="SmartSpeech" class='retina-ready' width="140px">
							</a>
						</div>
						<form id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();">
							<div class="form-group mb-3">
								<label>Email</label>
								<div class="input-group">
									<input name="uemail" type="text" class="form-control form-control-lg" />
									<span class="input-group-append">
										<span class="input-group-text"><i class="fas fa-user"></i></span>
									</span>
								</div>
							</div>
							
							<div class="form-group mb-3">
								<div class="clearfix">
									<label class="float-left">Password</label>
								</div>
								<div class="input-group">
									<input name="upw" type="password" class="form-control form-control-lg" />
									<span class="input-group-append">
										<span class="input-group-text"><i class="fas fa-lock"></i></span>
									</span>
								</div>
								<br/>
								<div class="clearfix">
									<span class="float-left"><a href="index.php?com=register">Εγγραφή</a></span>
									 <a href="index.php?com=forgot" class="float-right"><?=forgotMyPass?></a>
									 <?=$Message?>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-8">
									<!--
									<div class="checkbox-custom checkbox-default">
										<input id="RememberMe" name="rememberme" type="checkbox"/>
										<label for="RememberMe">Remember Me</label>
									</div>
									-->
								</div>
								<div class="col-sm-4 text-right">
									<button type="submit" name="login" value="<?=entrance?>" class="btn btn-primary mt-2">Είσοδος</button>
								</div>
							</div>
						</form>
					</div>
				</div>

				<p class="text-center text-muted mt-3 mb-3">&copy; Copyright 2020. All Rights Reserved.</p>
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
	<? $validator->RenderValidators();?>
	<? $messages->RenderMessages();?>
</html>