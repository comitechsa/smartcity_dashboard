<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Error Page</title>
	<link rel="stylesheet" href="/gms/common.css" type="text/css" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
	<p>&nbsp;</p>
	<table width="550" align="center" class="m_n">
		<tr>
			<td width="60%" height="50" align="center">
			<img src="/gms/images/logo.jpg" align="middle" />
			</td>
		</tr>
		<tr>
			<td align="center" class="m_error">
				<?php echo (isset($error_Message) ? $error_Message : ""); ?>
			</td>
		</tr>
	</table>
</body>
</html>
