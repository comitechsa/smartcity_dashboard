<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
function GetThemeHeader($title = "", $tableAttributes = " height='200' width='200'", $path = "")
{
	global $config;
	?>
	<table class='m_bg' border='0' <?=$tableAttributes?> cellpadding='3' cellspacing='1'>
        <tr>
            <td class="m_hg"><?=$title?></td>
        </tr>
        <tr>
            <td class="m_cg">
	<?
}

function GetThemeFooter($footerContent = "", $path = "")
{
	global $config;
	?>
							
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

function SendMail($Body, $Subject = "", $To="")
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
	$mail->Host = $config["mailServer"];

	if(isset($config["mailuser"]) && $config["mailpassword"] != "")
	{
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->Username = $config["mailuser"];
		$mail->Password = $config["mailpassword"];
	}
	else
	{
		$mail->IsMail();
		$mail->SMTPAuth = false;
	}
	
	//$mail->SMTPDebug  = 5;

	//$mail = new PHPMailer();
	//$mail->IsMail();
	//$mail->Host     = $config["mailServer"];
	//$mail->SMTPAuth = false;
	$mail->CharSet = "UTF-8";	
	$mail->From     = $config["contactMail"];
	$mail->FromName = site_title;
	//$mail->SetFrom($config["contactMail"], site_title);
	$mail->Subject  = $Subject != "" ? $Subject : site_title;
	$mail->Body     = $MailContent;
	$To = ($To != "" ? $To : $config["contactMail"]);
	$mail->AddAddress($To);	
	//$mail->AddAddress($address, "George");
	$mail->IsHTML(true);
	if(!$mail->Send())
	{
		LogError("Error during send mail","to: " . $To,$mail->ErrorInfo,"PHP");
	}
}


?>