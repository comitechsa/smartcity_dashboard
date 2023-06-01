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
		
		$Collector["activate"] = "";
		$QuotFields["activate"] = true;
		
		$Collector["is_valid"] = "True";
		$QuotFields["is_valid"] = true;
	
		$db->ExecuteUpdater("users",$PrimaryKeys,$Collector,$QuotFields);
		
		$bsRedir = isset($_GET["redir"]) && $_GET["redir"] != "" ? urldecode($_GET["redir"]) : CreateUrl(array("com"=>"index"));
		
		if(Login($dr["user_name"], $dr["user_password"]))
		{
			Redirect($bsRedir);
		}
	}
}

if(isset($_POST["register"]) 
	&& $_POST["r_name"] != ""
	&& $_POST["r_email"] != ""
	&& $_POST["r_pwd"] != ""
)
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
			SendMail($MailContentAdmin,"EMAIL FROM " . site_title . ":: Member Registration");
			
			$MailContent = user_activateMsg;		
			$MailContent = str_replace("#SITENAME#",site_title,$MailContent);
			$MailContent = str_replace("#USERNAME#",$_POST["reg_name"],$MailContent);
			$MailContent = str_replace("#USERPASSWORD#",$UserPassword,$MailContent);
			$MailContent = str_replace("#USEREMAIL#",$_POST["reg_email"],$MailContent);
			$MailContent = str_replace("#USERFULLNAME#",$_POST["reg_fname"],$MailContent);
			$MailContent = str_replace("#ACTIVATE#", CreateUrl(array("com"=>"register","activate"=>$Activate)),$MailContent);
			
			SendMail($MailContent,user_regiter . " :: " . site_title,$_POST["reg_email"]);
			$Message = user_regMsg;
			$inNewPanel = true;
		}
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
		<script language="javascript">var recordSelect="<?=core_recordSelect;?>";var CurrentLanguage = "<?=$auth->LanguageCode?>";var BaseUrl = "<?=$config["siteurl"]?>";</script>
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
        <!-- jQuery UI -->
		<script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.core.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.widget.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.mouse.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.resizable.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.sortable.min.js"></script>
        <!-- Nice Scroll -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
        <!-- Bootstrap -->
		<script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/bootstrap.min.js"></script>
        <!-- Bootbox -->
		<script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/bootbox/jquery.bootbox.js"></script>
        <!-- Bootbox -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/form/jquery.form.min.js"></script>
        <!-- Validation -->
		<script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/validation/jquery.validate.min.js"></script>
		<script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/validation/additional-methods.min.js"></script>
    
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
        <link rel="shortcut icon" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/favicon.ico" />
        <!-- Apple devices Homescreen icon -->
        <link rel="apple-touch-icon-precomposed" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/apple-touch-icon-precomposed.png" />
       
	</head>
	<body class='login'>
       <div class="wrapper" style="top:35%">
            <h1><a href="index.php"><img src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/logo-big.png" alt="" class='retina-ready' width="59" height="49"><?=site_title?></a></h1>
            <div class="login-body">
                <h2><?=subscription?></h2>
                <div class="row-fluid">
                <?
                if($inNewPanel)
				{
					?>
						<br />
                        <div style="padding:5px">
							<?=user_regMsg?>
                        </div>
                        <br />
					<?
				}
				else
				{
				?>
                   <form id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();"  class='form-vertical form-validate'><input type="hidden" name="Command" id="Command" value="-1" >
                        <div class="control-group">
                            <label for="textfield" class="control-label"><?=fullName?></label>
                            <div class="controls">
                                <input type="text" name="r_name" value="<?=$_POST["r_name"]?>" id="textfield" class="input-block-level" data-rule-required="true" data-rule-minlength="2">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="emailfield" class="control-label">Email</label>
                            <div class="controls">
                                <input type="text" name="r_email" value="<?=$_POST["r_email"]?>" id="emailfield" class="input-block-level" data-rule-email="true" data-rule-required="true">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="pwfield" class="control-label"><?=password?></label>
                            <div class="controls">
                                <input type="text" name="r_pwd" id="pwfield" class="input-block-level" data-rule-required="true">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="confirmfield" class="control-label"><?=passwordConfirm?></label>
                            <div class="controls">
                                <input type="text" name="r_pwdc" id="confirmfield" class="input-block-level" data-rule-equalTo="#pwfield" data-rule-required="true">
                            </div>
                        </div>
                        <div class="submit">
                            <input type="submit" name="register" class="btn btn-primary" value="Εγγραφή">
                        </div>
                        <?=$Message?>
                        <br>
                    </form>
                 <?
                 }
				 ?>
                 </div>
                <div class="forget" style="margin-top:0px">
                    <a href="index.php"><span><?=entrance?></span></a>
                </div>
                
            </div>
        </div>
  		
	</body>
	<? $validator->RenderValidators();?>
	<? $messages->RenderMessages();?>
</html>