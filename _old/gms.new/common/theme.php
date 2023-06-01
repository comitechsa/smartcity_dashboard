<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?

function GetThemeHeader($title = "", $tableAttributes = " height='200' width='200'", $path = "blue")
{
	global $config;
	?>
	<table border="0" <?=$tableAttributes?> cellpadding="0" cellspacing="0">
		<tr> 
			<td>
				<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
					<tr> 
						<td valign="top">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" height="20">
								<tr>
									<td><img src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/<?=$path?>/tbl_top_left.jpg"/></td>
									<td width="100%" background="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/<?=$path?>/tbl_top_rep.jpg" class="m_nb">
										<?=$title?>
									</td>
									<td><img src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/<?=$path?>/tbl_top_right.jpg"/></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr><td height="10" background="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/<?=$path?>/tbl_top.jpg"><img src="/gms/images/none.gif" height="10"/></td></tr>
					<tr valign="top"> 
						<td  height="100%" >
							<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
								<tr>
									<td background="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/<?=$path?>/tbl_left_rep.jpg"><img src="/gms/images/none.gif" width="8"/></td>
									<td valign="top" width="100%" background="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/<?=$path?>/tbl_rep.jpg" class="m_n" style="padding:5 5 5 5;">
	
	<?
}

function GetThemeFooter($footerContent = "", $path = "blue")
{
	global $config;
	?>
										</td>
									<td background="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/<?=$path?>/tbl_rigth_in_rep.jpg"><img src="/gms/images/none.gif" width="7"/></td>
								</tr>
							</table>					
						</td>
					</tr>
					<tr>
						<td height="27" valign="bottom" background="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/<?=$path?>/tbl_bottom.jpg" class="m_n"><?
								if($footerContent!= "")
									echo footerContent;
								else 
									echo '<img src="/gms/images/none.gif"/>';
							?></td>
					</tr>
				</table>
			</td>
			<td width="29" valign="top" background="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/<?=$path?>/tbl_right_rep.jpg">
				<img src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/<?=$path?>/tbl_right.jpg"/>
			</td>
		</tr>
	</table>
	<?
}

function GetGridThemeHeader()
{
	?>
		<table class='m_bg' border='0' width='100%' cellpadding='0' cellspacing='0'>
			<tr>
				<td>
					<table class='m_cg' border='0' width='100%' cellpadding='3' cellspacing='1'>
						
	<?
}

function GetGridThemeFooter()
{
	?>
					</table>
				</td>
			</tr>
		</table>
	<?
}


function SendMail($Body, $Subject = "Djzone", $To="")
{
	$MailContent = "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>";
	$MailContent .= "<html>";
	$MailContent .= "<head>";
	$MailContent .= "<style type='text/css'>";
		$MailContent .= ".n {font-family: Verdana, Arial;font-size: 12px;}";
		$MailContent .= ".ns {font-family: Verdana, Arial;font-size: 9px;}";
		$MailContent .= ".h {font-family: Verdana, Arial;font-size: 12px;font-weight: bold;color: #000099;}";
		$MailContent .= ".tbl {background-color:  #dddddd;}";
		$MailContent .= ".c {background-color:  #f1f1f1;}";
	$MailContent .= "</style>";
	$MailContent .= "</head>";
	$MailContent .= "<body class='n'>";

	$MailContent .= $Body;

	$MailContent .= "</body>";
	$MailContent .= "</html>";	
	
	global $config;
	
	$mail = new PHPMailer();
	$mail->IsMail();
	$mail->Host     = $config["mailServer"];
	$mail->SMTPAuth = false;
	$mail->CharSet = "UTF-8";	
	$mail->From     = $config["contactMail"];
	$mail->FromName = site_title;
	$mail->Subject  = $Subject;
	$mail->Body     = $MailContent;
	$To = ($To != "" ? $To : $config["contactMail"]);
	$mail->AddAddress($To);	
	$mail->IsHTML(true);
	if(!$mail->Send())
	{
		LogError("Error during send mail","to: " . $To,$mail->ErrorInfo,"PHP");
	}
}


?>