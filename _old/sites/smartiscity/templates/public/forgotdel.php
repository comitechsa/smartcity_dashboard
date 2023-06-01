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
	<body class='login'>
        <div class="wrapper">
            <h1><a href="index.php"><img src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/viewpanelSUITE.png" alt="" class='retina-ready' width="180px"></a></h1>
            <div class="login-body">
                <h2><?=forgotMyPass?>?</h2>
                <form id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();">
                    <input type="hidden" name="Command" id="Command" value="-1">
                    <div class="control-group">
                    <div class="email controls">
                        <input type="text" name='uemail' placeholder="Email" class='input-block-level' data-rule-required="true" data-rule-email="true">
                    </div>
                </div>
                <div class="submit">
                    <input type="submit" value="<?=send?>" class='btn btn-primary' name="fpass">
                </div>
                <?=$Message?>
                </form>
                <div class="forget">
                    <a href="index.php"><span><?=entrance?></span></a>
                </div>
            </div>
        </div>  		
	</body>
	<? $validator->RenderValidators();?>
	<? $messages->RenderMessages();?>
</html>