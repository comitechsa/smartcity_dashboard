<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Neapoli_HotSpot &gt; login &quot;Καλώς Ήρθατε!&quot;</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="expires" content="-1" />
<style type="text/css">
body {color: #737373; font-size: 10px; font-family: verdana;}

textarea,input,select {
background-color: #FDFBFB;
border: 1px solid #BBBBBB;
padding: 2px;
margin: 1px;
font-size: 14px;
color: #808080;
}

a, a:link, a:visited, a:active { color: #AAAAAA; text-decoration: none; font-size: 10px; }
a:hover { border-bottom: 1px dotted #c1c1c1; color: #AAAAAA; }
img {border: none;}
td { font-size: 14px; color: #7A7A7A; }
</style>

</head>

<body bgcolor="#005AA0" style="color: #000000">
$(if chap-id)
	<form name="sendin" action="$(link-login-only)" method="post">
		<input type="hidden" name="username" />
		<input type="hidden" name="password" />
		<input type="hidden" name="dst" value="$(link-orig)" />
		<input type="hidden" name="popup" value="true" />
	</form>
	
	<script type="text/javascript" src="/md5.js"></script>
	<script type="text/javascript">
	<!--
	    function doLogin() {
		document.sendin.username.value = document.login.username.value;
		document.sendin.password.value = hexMD5('$(chap-id)' + document.login.password.value + '$(chap-challenge)');
		document.sendin.submit();
		return false;
	    }
	//-->
	</script>
$(endif)

<table width="100%" style="margin-top: 2%; color:#000000">
	<tr>
	<td align="center" style="color: #000000">
		<div class="notice" style="color: #c1c1c1; font-size: 9px">
			<p style="margin-top: 0; margin-bottom: 0">
			<span style="letter-spacing: 1pt"><strong>
			<font face="Verdana" size="3" color="#FFFFFF">ΣΥΣΤΗΜΑ ΔΙΑΧΕΙΡΙΣΗΣ 
			ΣΗΜΕΙΩΝ ΕΛΕΥΘΕΡΗΣ ΑΣΥΡΜΑΤΗΣ ΠΡΟΣΒΑΣΗΣ</font></strong></span></p>
			<font color="#000000">
			<p style="margin-top: 0; margin-bottom: 0">
			&nbsp;</p>
			<p style="margin-top: 0; margin-bottom: 0">
			<font size="2" face="Verdana" color="#000000">Για την δική σας 
			ασφάλεια παρακαλώ μπορείτε να εισέρχεσθε στο σύστημα 
			</font></p>
			<p style="margin-top: 0; margin-bottom: 0">
			<font size="2" face="Verdana">
			πληκτρολογώντας την μεταβλητή που εμφανίζετε παρακάτω</font><span lang="el"><font size="2" face="Verdana">.</font></span></p>
			<p style="margin-top: 0; margin-bottom: 0">
			&nbsp;</p></font></div>
		<p style="margin-top: 0; margin-bottom: 0">
		<br />
		</p>
		<table width="240" height="240" style="border: 1px solid #cccccc; padding: 0px;" cellpadding="0" cellspacing="0" bgcolor="#2094FF">
			<tr>
				<td align="center" valign="bottom" height="100" colspan="3" bgcolor="#FFFFFF" style="color: #000000">
					<a href="http://www.neapolinet.gr">
					<img border="0" src="img/inprogress-technology.jpg" width="375" height="92" border="0"></a></td>
			</tr>
			<tr>
				<td align="center" valign="bottom" colspan="3" bgcolor="#96C33C" style="color: #000000">
					<form name="login" action="$(link-login-only)" method="post"
					    $(if chap-id) onSubmit="return doLogin()" $(endif)>
<input type='hidden' name='mac' value='$(mac-esc)'>



						<input type="hidden" name="dst" value="$(link-orig)" />
						<input type="hidden" name="popup" value="true" />
						
							<p>&nbsp;</p>
						
							<table width="214" style="background-color: #96C33C; border-collapse:collapse" border="1" id="table3" cellspacing="4" bordercolordark="#808080" bordercolorlight="#C0C0C0">
								<!-- MSTableType="nolayout" -->
								<tr><td align="left" style="color: #000000" height="20" width="118">
									<p align="left">
									<font size="1">Μεταβλητή</font></td>
										<td style="color: #737373" align="center" height="20" valign="top">
										<p align="center">


<iframe src='http://www.in-progress.gr/hotspot/captcha_frame.php' width="100" height="40" frameborder="0" scrolling="0"></iframe>
</td>
								</tr>
								<tr><td align="left" style="color: #000000" height="20" width="118">
									<font size="1">Εισαγωγή μεταβλητής</font></td>
										<td style="color: #737373" align="center" height="20" valign="top">
										<p align="center">
<input type='text' name='cap_data' size="11" style="font-size: 14px; color: #808080; border: 1px solid #BBBBBB; margin: 1px; padding: 2px; background-color: #FDFBFB"></td>
								</tr>
								<tr><td height="20" width="118">&nbsp;</td>
										<td style="color: #737373" height="36" align="center">
										<input type='submit' value='Then submit...and validate' style="border:1px solid #BBBBBB; margin:1px; padding:2px; color: #000000; font-size:14px; background-color:#FDFBFB"></td>
								</tr>
							</table>
								<p>
<input type='hidden' name='municipality' value='Neapoli'>
<input type='hidden' name='url' value='index.php?yes=true'>
						
								</p>
						</form>
				</td>
			</tr>
			<tr><td align="center" bgcolor="#FFFFFF" bordercolor="#FFFFFF">
				<font size="2">Διαφημιστείτε εδώ</font><p>
				<span lang="en-us"><font size="2">&nbsp;</font></span><a href="http://www.intcom.gr/"><span lang="en-us"><font size="2"><b>Logo 
				1</b></font></span></a></td>
				<td align="center" bgcolor="#FFFFFF" bordercolor="#FFFFFF">
				<p><a href="http://www.intcom.gr/">
				<img border="0" src="img/yourpoint.jpg" width="197" height="80"></a></td>
				<td align="center" bgcolor="#FFFFFF" bordercolor="#FFFFFF">
				&nbsp;</td></tr>
			<tr><td align="center" bgcolor="#FFFFFF" bordercolor="#FFFFFF" colspan="2">
				<font size="2">Διαφημιστείτε εδώ</font><p><span lang="en-us">
				<font size="2">&nbsp;</font></span><a href="http://www.intcom.gr/"><span lang="en-us"><font size="2"><b>Logo 
				2</b></font></span></a></p>
				<table height="1" width="600" border="1" id="table1" style="color: #000000">
					<tr>
						<td width="590" height="1" style="color: #000000" bgcolor="#FFFFFF">
						<p align="center" style="margin-top: 0; margin-bottom: 0">
						<span lang="el"><font size="1">
						<a href="http://www.intcom.gr/">Πατριάρχου Γρηγορίου Ε&#39; 24, 58200 Έδεσσα, Τηλ. 
						+302381025689, Φάξ +302381028248, Τ.Θ. 07, </a> </font>
						</span>
						<p align="center" style="margin-top: 0; margin-bottom: 0">
						<font size="1">e-mail 
						<a href="mailto:info@yourpoint.gr?subject=Επικοινωνία από το Δίκτυο του Δ. Μενηίδος">info@<span lang="en-us">inprogress</span>.gr</a></font></td>
					</tr>
				</table>
				</td><td align="center" bgcolor="#FFFFFF" bordercolor="#FFFFFF">
				&nbsp;</td></tr>
		</table>
	
		<p style="margin-top: 0; margin-bottom: 0">
			<font size="2">Για πληροφορίες σχετικές με την υπηρεσία 
			επικοινωνήστε με το εταιρία μας&nbsp;
			</font>
			<p style="margin-top: 0; margin-bottom: 0"><font size="2">στο 23810 
			25689, <span lang="en-us">info</span>@<span lang="en-us">in-progress.gr</span></font><br /><div style="color: #c1c1c1; font-size: 9px">
			<a href="http://www.intcom.gr/">Powered by <span lang="en-us">
			in-progress</span> 
			© 20<span lang="en-us">10</span></a><p>&nbsp;</div>
		<table border="0" width="46%" id="table2">
			<tr>
				<td>
				<p style="margin-top: 0; margin-bottom: 0" align="center">
				<a href="http://www.intcom.gr/">
				<font size="2" face="Verdana" color="#000000">Το πρόγραμμα 
				συγχρηματοδοτείται <span lang="el">από</span></font></a></p>
				<p align="center">
				<span style="font-weight: bold; font-style: italic">
				<font face="Arial">
				<img alt="" src="img/spban.jpg" width="394" height="60"></font></span></td>
			</tr>
		</table>
		<p>&nbsp;</td>
	</tr>
</table>

<script type="text/javascript">
<!--
  document.login.username.focus();
//-->
</script>
</body>
</html>
