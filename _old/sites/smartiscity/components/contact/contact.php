<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
$config["navigation"] = contact_contact;

$__error = "";

$Contents = "";
if(isset($_POST["sendFrm"]) && $_POST["sendFrm"] != "")
{
	if(isset($_SESSION["security_code"]) && isset($_POST["p_security_code"]) && $_POST["p_security_code"] != "" && $_POST["p_security_code"] == $_SESSION["security_code"])
	{
		unset($_SESSION["security_code"]);

		$MailContent = "<br>" . contact_contact . "<br>";
		$MailContent .= "<br>" . contact_name . ":" . " " . $_POST["c_name"] . "<br>";
		$MailContent .= "<br>" . contact_lastName . ":" . " " . $_POST["c_lname"] . "<br>";
		$MailContent .= "<br>" . contact_phone . ":" . " " . $_POST["c_phone"] . "<br>";
		$MailContent .= "<br>" . contact_email . ":" . " " . $_POST["c_email"] . "<br>";
		$MailContent .= "<br>" . contact_comments . ":" . " " . $_POST["c_comments"] . "<br>";
		
		SendMail($MailContent, contact_contact . " :: " . $config["siteurl"]);
		
		Redirect(CreateUrl(array("com"=>"contact","sendform"=>"true")));
	}
	else
	{
		//$__error = core_securityCodeError;
		$messages->addMessage(core_securityCodeError);
	}
}
else if(isset($_GET["sendform"]) && $_GET["sendform"] == "true")
{
	//$__error = contact_sended;
	$messages->addMessage(contact_sended);
}

if($__error != "") echo "<div class='m_n'>" . $__error . "</div><br>";
$gp = new GetPage(3);
$Contents = "";
if($gp->content != "")
{
	$Contents .= "<div class='m_n' style='padding:5px'>" . $gp->content . "</div><hr>";
}
$Contents .= "<table cellpadding='5' cellspacing='1' class='m_n' width='600px'>";
$Contents .= "<tr><td width='30%'>(*) " . contact_name . ": </td><td>" . TextBox::GetRender("c_name") . "</td></tr>";
$Contents .= "<tr><td>(*) " . contact_lastName . ": </td><td>" . TextBox::GetRender("c_lname") . "</td></tr>";
$Contents .= "<tr><td>(*) " . contact_phone . ": </td><td>" . TextBox::GetRender("c_phone") . "</td></tr>";
$Contents .= "<tr><td>(*) " . contact_email . ": </td><td>" . TextBox::GetRender("c_email") . "</td></tr>";
$Contents .= "<tr><td>" . contact_comments . ": </td><td>" . TextBox::GetRender("c_comments","", "", "70%", "TextArea", "7") . "</td></tr>";
$Contents .= "<tr><td align='center' colspan='2'><br>" . CaptchaRender()  . "<br><br></td></tr>";
$Contents .= "<tr><td colspan='2' align='center'><input onclick='return ValidateOnlyThis(\"c_name;c_lname;c_phone;c_email\");' class='m_b' type='submit' value='" . contact_sendMail . "' name='sendFrm'/>&nbsp;&nbsp;<input class='m_b' type='reset' value='" . contact_reset . "'/></td></tr>";

$Contents .= "</table><br><span class='error'>* τα πεδία είναι υποχρεωτικά</span>";

$validator->AddTagValidator('c_name',1,'String','');
$validator->AddTagValidator('c_lname',1,'String','');
$validator->AddTagValidator('c_phone',1,'String','');
$validator->AddTagValidator('c_email',1,'String','');

echo $Contents;
?>