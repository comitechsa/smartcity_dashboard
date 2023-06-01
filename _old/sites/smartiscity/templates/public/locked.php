<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
?>
<?
$Message = "";
if(isset($_POST["login"]) && $_POST["login"] != "" && $_POST["upw"] != "")
{
	$usrname = trim(str_replace("'","''",$_SESSION["locked_user"]));
	$pass = trim(str_replace("'","''",$_POST['upw']));
	
	$bsRedir = isset($_GET["redir"]) && $_GET["redir"] != "" ? urldecode($_GET["redir"]) : CreateUrl(array("com"=>"devices"));
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
		$Message = "<br><div class='control-group error'>".wrongInfo."</div>";
	}
}

	LoadNoCacheHeader();
	LoadCharSetHeader();
?>
<!doctype html>
<html><head>
		<? 
		$site_title = site_title;
		if(isset($config["title"])) {$site_title = strip_tags($config["title"]);}
		else if(isset($config["navigation"])) {$site_title = strip_tags($config["navigation"]);}
		?>
		<title><?=$site_title?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta name="description" content="<?=(isset($config["metaDesciption"]) ? $config["metaDesciption"] : "")?>" />
		<meta name="keywords" content="<?=(isset($config["metaKeys"]) ? $config["metaKeys"] : "")?>" />
		<script language="javascript">var recordSelect="<?=core_recordSelect;?>";var CurrentLanguage = "<?=$auth->LanguageCode?>";var BaseUrl = "<?=$config["siteurl"]?>";
		</script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/core.js"></script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/public.js"></script>
		<link rel="stylesheet" type="text/css" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/theme.css">		
        <script type="text/javascript">var ajaxUrl= "<?=$config["ajaxUrl"]?>"</script>
    
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <!-- Apple devices fullscreen -->
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <!-- Apple devices fullscreen -->
        <meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
        
        
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/bootstrap.min.css">
        <!-- Bootstrap responsive -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/bootstrap-responsive.min.css">
        <!-- Theme CSS -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/style.css">
        <!-- Color CSS -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/themes.css">
        
         <!-- jQuery -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/jquery.min.js"></script>
        <!-- Nice Scroll -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
        <!-- Bootstrap -->
		<script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/bootstrap.min.js"></script>
       
        <!-- Theme framework -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/eakroko.js"></script>
			
        <!--[if lte IE 9]>
            <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/placeholder/jquery.placeholder.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('input, textarea').placeholder();
                });
            </script>
        <![endif]-->
    
        <!-- Favicon -->
		<link rel="shortcut icon" href="<?=$config["siteurl"]?>favicon.png">
        <!-- Apple devices Homescreen icon -->
        <link rel="apple-touch-icon-precomposed" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/apple-touch-icon-precomposed.png" />
       
	</head>
	<body class='locked'>
    
    <div class="wrapper">
		<div class="pull-left">
			<img src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/demo/pr.png" alt="" width="200" height="200">
			<a href="index.php"><?=$_SESSION["locked_user_fullname"]?>?</a>
		</div>
		<div class="right">
			<div class="upper">
				<h2><? //=$_SESSION["locked_user_fullname"]?></h2>
				<span><?=lock?></span>
			</div>
			 <form id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();"><input type="hidden" name="Command" id="Command" value="-1">
             	<input type="password" placeholder="<?=password?>" name="upw">
				<div>
					<input type="submit" value="<?=unlock?>" class='btn btn-inverse' name="login">
				</div>
                 <?=$Message?>
			</form>
		</div>
	</div>
    
  		
	</body>
	<? $validator->RenderValidators();?>
	<? $messages->RenderMessages();?>
</html>