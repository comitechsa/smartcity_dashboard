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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" > 
<html>
	<head>
		<? $htmlheader->RenderAdminHeader(); ?>
    </head>
	<body class="m_body">
	<form id="__PageForm" name="__PageForm" enctype="multipart/form-data" method="post" onSubmit="return PageIsValid();">
	<input type="hidden" name="Command" id="Command" value="-1">
    <iframe class="IfrDis" style="display:none;top:0px;left:0px;position:absolute;" id="OverlapFrame" name="OverlapFrame" src="about:blank" frameBorder=0 scrolling=no></iframe>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="m_topmenu">
			<tr>
				<td><?=site_adminMenu;?></td>
			</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" class="m_toolbar">
			<tr>
				<td align="right">
					<?
						$toolBar->RenderToolBar();
					?>
				</td>
			</tr>
		</table>
		<table width="100%" align="center" border="0">
			<tr>
				<td align="center"><br>
					<?
						$components->RenderRequestComponent();
					?>
				</td>
			</tr>
		</table>
		<? $validator->RenderValidators();?>
		<? $messages->RenderMessages();?>
	</form>
	</body>
</html>