<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
	if(isset($_GET["logout"]))
	{
		Logout();
		Redirect("index.php");
	}
	
	LoadNoCacheHeader();
	LoadCharSetHeader();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>view.panel</title>        
        <link rel="stylesheet" type="text/css" href="css/ext-all.css" />
        <link rel="stylesheet" type="text/css" href="css/desktop.css" />
        <script type="text/javascript" src="js/ext-base.js"></script>
        <script type="text/javascript" src="js/ext-all.js"></script>
        <script type="text/javascript" src="js/StartMenu.js"></script>
        <script type="text/javascript" src="js/TaskBar.js"></script>
        <script type="text/javascript" src="js/Desktop.js"></script>
        <script type="text/javascript" src="js/App.js"></script>
        <script type="text/javascript" src="js/Module.js"></script>
        <script type="text/javascript" src="js/InfoWindow.js"></script>
        <script language="javascript">var recordSelect="<?=core_recordSelect;?>";var CurrentLanguage = "<?=$auth->LanguageCode?>";var BaseUrl = "<?=$config["siteurl"]?>";</script>
        <meta http-equiv="X-UA-Compatible" content="IE=8" />
        <link rel="shortcut icon" href="favicon.ico?v=1" >
	</head>
    <body scroll="no">    	
        <div id="page-wrap">
            <div id="x-desktop">
                <a href="javascript:void(0);" style="margin:5px; float:right;"><img src="images/logo.png" /></a>    
                <?
					$components->RenderRequestComponent();
				?>
            </div>    
            <div id="ux-taskbar">
                <div id="ux-taskbar-start"></div>
                <div id="ux-taskbuttons-panel"></div>
                <div class="x-clear"></div>
            </div>
         </div>
         <div id="bg">
            <img src="wallpapers/desktop.jpg">
        </div>	
    </body>
</html>
<script type="text/javascript" src="js/InfoWindow.js"></script>
<script type="text/javascript" src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/adminMenu.js"></script>
