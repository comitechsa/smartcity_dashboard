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
<!DOCTYPE html>
		<? 
		$site_title = site_title;
		if(isset($config["title"])) {$site_title = strip_tags($config["title"]);}
		else if(isset($config["navigation"])) {$site_title = strip_tags($config["navigation"]);}
		?>
		<title><?=$site_title?></title>
		<meta name="description" content="<?=(isset($config["metaDesciption"]) ? $config["metaDesciption"] : "")?>" />
		<meta name="keywords" content="<?=(isset($config["metaKeys"]) ? $config["metaKeys"] : "")?>" />
		<script language="javascript">var recordSelect="<?=core_recordSelect;?>";var CurrentLanguage = "<?=$auth->LanguageCode?>";var BaseUrl = "<?=$config["siteurl"]?>";</script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/core.js"></script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/public.js"></script>
		<link rel="stylesheet" type="text/css" href="/sites/<?=$config["site"]?>/theme.css">
        <script type="text/javascript">var ajaxUrl= "<?=$config["ajaxUrl"]?>"</script>
		<link rel="shortcut icon" href="<?=$config["siteurl"]?>favicon.png">		
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/main-color.css" id="colors">
</head>

<body>

<!-- Wrapper -->
<div id="wrapper">

<!-- Header Container
================================================== -->
<header id="header-container">

	<!-- Header -->
	<div id="header" class="not-sticky">
		<div class="container">
			
			<!-- Left Side Content -->
			<div class="left-side">
				
				<!-- Logo 
				<div id="logo">
					<a href="index.html"><img src="images/logo.png" alt=""></a>
				</div>-->
				<div id="logo">
					<!-- <a href="index.php"><img src="images/logo2.png" data-sticky-logo="images/logo.png" alt=""></a>-->
					<a href="index.php"><img src="gallery/logo/headerlogo3.png" data-sticky-logo="gallery/logo/headerlogo3.png" alt=""></a>
				</div>
				<!-- Mobile Navigation -->
				<div class="mmenu-trigger">
					<button class="hamburger hamburger--collapse" type="button">
						<span class="hamburger-box">
							<span class="hamburger-inner"></span>
						</span>
					</button>
				</div>


				<div class="clearfix"></div>
				<!-- Main Navigation / End -->
				
			</div>


		</div>
	</div>
	<!-- Header / End -->

</header>
<div class="clearfix"></div>
<!-- Header Container / End -->

<!-- Titlebar
================================================== -->
<div id="titlebar" class="" style="padding:5px 0;">
	<div class="container">
		<div class="row">
		</div>
	</div>
</div>


<div class="container">
	<div class="row sticky-wrapper">		
		<div class="content">
			<div class="small-dialog-header">
				<h3>Sign In</h3>
			</div>
			<div class="sign-in-form style-1">

				<ul class="tabs-nav">
					<li class="active"><a href="#tab1">Log In</a></li>
					<!-- <li><a href="#tab2">Register</a></li> -->
				</ul>

				<div class="tabs-container alt">

					<!-- Login -->
					<div class="tab-content" id="tab1" style="">
						<form id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();">

							<p class="form-row form-row-wide">
								<label for="uemail">email:
									<i class="im im-icon-Male"></i>
									<input type="text" class="input-text" name="uemail" id="uemail" value="">
								</label>
							</p>

							<p class="form-row form-row-wide">
								<label for="upw">Password:
									<i class="im im-icon-Lock-2"></i>
									<input class="input-text" type="password" name="upw" id="upw">
								</label>
								<span class="lost_password">
									<a href="#">Lost Your Password?</a>
								</span>
							</p>
							<div class="form-row">
								<input type="submit" class="button border margin-top-5" name="login" value="Login">
								<div class="checkboxes margin-top-10">
									<input id="remember-me" type="checkbox" name="check">
									<label for="remember-me">Remember Me</label>
								</div>
							</div>
							
						</form>
					</div>

					<!-- Register -->
					<div class="tab-content" id="tab2" style="display: none;">

						<form method="post" class="register">
							
						<p class="form-row form-row-wide">
							<label for="username2">Username:
								<i class="im im-icon-Male"></i>
								<input type="text" class="input-text" name="username" id="username2" value="">
							</label>
						</p>
							
						<p class="form-row form-row-wide">
							<label for="email2">Email Address:
								<i class="im im-icon-Mail"></i>
								<input type="text" class="input-text" name="email" id="email2" value="">
							</label>
						</p>

						<p class="form-row form-row-wide">
							<label for="password1">Password:
								<i class="im im-icon-Lock-2"></i>
								<input class="input-text" type="password" name="password1" id="password1">
							</label>
						</p>

						<p class="form-row form-row-wide">
							<label for="password2">Repeat Password:
								<i class="im im-icon-Lock-2"></i>
								<input class="input-text" type="password" name="password2" id="password2">
							</label>
						</p>

						<input type="submit" class="button border fw margin-top-10" name="register" value="Register">

						</form>
					</div>

				</div>
			</div>

		</div>
</div>


<!-- Footer
================================================== -->
<!-- Footer
================================================== -->
<div id="footer" class="sticky-footer" style="
/*position: fixed;*/
border-top:none;
  left: 0;
  bottom: 0;
  width: 100%;
  color: white;
  text-align: center;
  padding:0;
  ">
	<!-- Main -->
	<div class="container">
	
		<!-- 
		<div class="row">
			<div class="col-md-5 col-sm-6">
				<img class="footer-logo" src="images/logo.png" alt="">
				<br><br>
				<p>Morbi convallis bibendum urna ut viverra. Maecenas quis consequat libero, a feugiat eros. Nunc ut lacinia tortor morbi ultricies laoreet ullamcorper phasellus semper.</p>
			</div>

			<div class="col-md-4 col-sm-6 ">
				<h4>Helpful Links</h4>
				<ul class="footer-links">
					<li><a href="#">Login</a></li>
					<li><a href="#">Sign Up</a></li>
					<li><a href="#">My Account</a></li>
					<li><a href="#">Add Listing</a></li>
					<li><a href="#">Pricing</a></li>
					<li><a href="#">Privacy Policy</a></li>
				</ul>

				<ul class="footer-links">
					<li><a href="#">FAQ</a></li>
					<li><a href="#">Blog</a></li>
					<li><a href="#">Our Partners</a></li>
					<li><a href="#">How It Works</a></li>
					<li><a href="#">Contact</a></li>
				</ul>
				<div class="clearfix"></div>
			</div>		

			<div class="col-md-3  col-sm-12">
				<h4>Contact Us</h4>
				<div class="text-widget">
					<span>12345 Little Lonsdale St, Melbourne</span> <br>
					Phone: <span>(123) 123-456 </span><br>
					E-Mail:<span> <a href="#">office@example.com</a> </span><br>
				</div>

				<ul class="social-icons margin-top-20">
					<li><a class="facebook" href="#"><i class="icon-facebook"></i></a></li>
					<li><a class="twitter" href="#"><i class="icon-twitter"></i></a></li>
					<li><a class="gplus" href="#"><i class="icon-gplus"></i></a></li>
					<li><a class="vimeo" href="#"><i class="icon-vimeo"></i></a></li>
				</ul>

			</div>
		
		</div>
		-->
		<!-- Copyright -->
		<div class="row">
			<div class="col-md-12">
				<div class="copyrights">© 2021 Dotsoft. All Rights Reserved.</div>
			</div>
		</div>

	</div>

</div>
<!-- Footer / End -->


<!-- Back To Top Button -->
<div id="backtotop"><a href="#"></a></div>


</div>
<!-- Wrapper / End -->


<!-- Scripts
================================================== -->
<script type="text/javascript" src="scripts/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="scripts/jquery-migrate-3.3.1.min.js"></script>
<script type="text/javascript" src="scripts/mmenu.min.js"></script>
<script type="text/javascript" src="scripts/chosen.min.js"></script>
<script type="text/javascript" src="scripts/slick.min.js"></script>
<script type="text/javascript" src="scripts/rangeslider.min.js"></script>
<script type="text/javascript" src="scripts/magnific-popup.min.js"></script>
<script type="text/javascript" src="scripts/waypoints.min.js"></script>
<script type="text/javascript" src="scripts/counterup.min.js"></script>
<script type="text/javascript" src="scripts/jquery-ui.min.js"></script>
<script type="text/javascript" src="scripts/tooltips.min.js"></script>
<script type="text/javascript" src="scripts/custom.js"></script>


<!-- Style Switcher
================================================== -->
<script src="scripts/switcher.js"></script>

<!-- Style Switcher / End -->


</body>
</html>