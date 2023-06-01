<? defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); 

?>
<?
	if(isset($config["https"]) && $config["https"] && !isset($_SERVER['HTTPS']))
	{
		Redirect(str_replace("http://","https://",$config["siteurl"]) . "admin/index.php");
	}

	LoadNoCacheHeader();
	LoadCharSetHeader();
	if (isset( $_POST['submit'] )) 
	{
		$usrname = trim(str_replace("\\","",str_replace("'","",$_POST['usrname'])));
		$pass = trim(str_replace("\\","",str_replace("'","",$_POST['pass'])));
		if(Login($usrname,$pass,'Administrator'))
		{
			Redirect(str_replace("https://","http://",$config["siteurl"]) . "admin/index.php");
		}
		else
		{
			echo "<script>alert('" . core_loginMessage . "');</script>\n";
		}
	}
?>
<html>
	<head>
		<? $htmlheader->RenderAdminHeader(); ?>
		<link rel="stylesheet" type="text/css" href="admin.login.css">		
	</head>
	<body>
	<div id="wrapper">
		<div id="ctr" align="center">
			<div class="login">
				<div class="login-form"><b><?=core_login;?></b>
					<form action="index.php" method="post" name="loginForm" id="loginForm">
						<div class="form-block">
							
							<div class="inputlabel"><?=core_userName;?></div>
							<div><input name="usrname" type="text" class="inputbox" size="15" /></div>
							<div class="inputlabel"><?=core_userPassword;?></div>
							<div><input name="pass" type="password" class="inputbox" size="15" /></div>
							<div align="left"><input type="submit" name="submit" class="button" value="<?=core_login;?>" /></div>
						</div>
					</form>
				</div>
				<div class="login-text"><div class="ctr"><img src="/gms/images/security.png" width="64" height="64" alt="security" /></div>
					<p><?=core_loginMessage;?></p>
				</div>
				<div class="clr"></div>
			</div>
		</div>
		<div id="break"></div>
		<noscript><?=core_noScript;?></noscript>	
		<div class="footer" align="center"></div>
	</body>
</html>
