<? defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
	if(isset($config["https"]) && $config["https"] && !isset($_SERVER['HTTPS']))
	{
		Redirect(str_replace("http://","https://",$config["siteurl"]) . "admin/index.php");
	}
	
	$message = "";
	LoadNoCacheHeader();
	LoadCharSetHeader();
	if (isset( $_POST['submit'] )) 
	{
		$usrname = trim(str_replace("\\","",str_replace("'","",$_POST['usrname'])));
		$pass = trim(str_replace("\\","",str_replace("'","",$_POST['pass'])));
		if(Login($usrname,$pass,(isset($config["adm_access_roles"])?$config["adm_access_roles"]:'Administrator')))
		{
			Redirect(str_replace("https://","http://",$config["siteurl"]) . (isset($config["adm_access_path"])?$config["adm_access_path"]:"admin") . "/index.php");
		}
		else
		{
			$message = '<script type="text/javascript">window.Ext.onReady(function(){window.Ext.InfoWindow.msg("", "' . str_replace("\"","",core_loginMessage) . '",4);}, this);</script>';
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
        <title>view.panel</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <script type="text/javascript">
			if(window.top.location.href != window.location.href) window.top.location.href = window.location.href;
			var recordSelect="<?=core_recordSelect;?>";
			var CurrentLanguage = "<?=$auth->LanguageCode?>";
			var BaseUrl = "<?=$config["siteurl"]?>";
			var enabled_ext=true;
		</script>
		<script language="javascript" type="text/javascript" src="/gms/client_scripts/core.js"></script>
		<script language="javascript" type="text/javascript" src="/gms/client_scripts/private.js"></script>
        <script type="text/javascript" src="js/ext-base.js"></script>
        <script type="text/javascript" src="js/ext-all.js"></script>
        <script type="text/javascript" src="js/InfoWindow.js"></script>
		<link rel="stylesheet" type="text/css" href="admin.login.css">	
        <link rel="stylesheet" type="text/css" href="css/ext-all.css" />
        <link rel="shortcut icon" href="favicon.ico?v=1" >
        <?=$message?>
        <link rel="stylesheet" type="text/css" media="screen" href="js/browser-detection/browser-detection.css" />
		<script type="text/javascript" src="js/browser-detection/browser-detection.js"></script>
        <meta http-equiv="X-UA-Compatible" content="IE=8" />
	</head>
	<body>
    	<form action="index.php" method="post" name="loginForm" id="loginForm">
		<div id="page-wrap">
        	<div id="main">
            	<div id="bd"></div>
	            <div id="login">
	        		<div id="loginMeno"><? 
					if (defined('site_meno')) {
						echo site_meno;
					}
					?></div>                    
                    <div id="loginInput">
                    	<div>
	                        <div class="lbl"><?=core_userName;?>: </div>
                            <div class="txt"><input id="usrname" name="usrname" type="text" class="m_tb2" size="30" /></div>
                            <? $validator->AddTagValidator("usrname",1,"String"); ?>
                            <br style="clear:both;">
                        </div>
                        <br>
                        <div>
	                        <div class="lbl"><?=core_userPassword;?>: </div>
                            <div class="txt"><input id="pass" name="pass" type="password" class="m_tb2" size="30" /></div>
                            <? $validator->AddTagValidator("pass",1,"String"); ?>
                            <br style="clear:both;">
                        </div>
                        <br>
                        <div align="right" class="btn"><input type="submit" name="submit" class="m_b2" value="<?=core_login;?>" onclick='return ValidateOnlyThis("usrname;pass");'/></div>                    
                     </div>
                     <div id="loginFooter">
                     	<div class="date"><?
                        $ar_d = core_days();	
						$ar_m = core_mouthsIU();
						echo $ar_d[date('w')] . ", " . date('d') . " " . $ar_m[intval(date('m'))-1] . " " . date('Y') . ", " . date('H:i');						
						?></div>
                        <div class="lang"><select class="m_tb2" name="lang" onChange="window.location.href='index.php?lang='+this.value;"><option value="gr" <?=$auth->LanguageCode=="gr"?"selected":""?>>Ελληνικά</option><!--<option value="en" <?=$auth->LanguageCode=="en"?"selected":""?>>English</option>--></select></div>
                        <br style="clear:both;">
                     </div>
                </div>
				<noscript><br><?=core_noScript;?></noscript>	
            </div>
        </div>
        <div id="bg"><img src="wallpapers/desktop.jpg"></div>	
        </form>
        <? $validator->RenderValidators();?>
	</body>
</html>