<?php
@session_start();

include_once("connect.php");

// /echo $_POST['cap_data'] . "<br>";

//echo $_POST['mac'] . "<br>";

//echo $_SESSION['security_code'] . "<br>";


if ( ((isset($_POST['cap_data']) && $_POST['cap_data'] == $_SESSION['security_code'])) || isset($_GET["geogr"]))
{
	$cap = (isset($_REQUEST['cap_data']) ? $_REQUEST['cap_data'] : "");
	$municipality = (isset($_REQUEST['municipality']) ? $_REQUEST['municipality'] : "");
	$mac = (isset($_REQUEST['mac']) ? urldecode($_REQUEST['mac']) : "");
	$location = (isset($_REQUEST['location']) ? $_REQUEST['location'] : "");

	if($municipality != "")
	{
		if(isset($_GET["geogr"])) echo $municipality;
		mysql_query("INSERT INTO captcha_data (`timestamp`,`key`,`municipality`,`mac`,`location`) VALUES ('".time()."','".$cap."','".$municipality."','".$mac."','".$location."');");
	}
	
	if(isset($_POST['url']) && $_POST['url'] != "")
	{
		?>
		<script language="javascript">
			window.location.href='<?=$_POST['url']?>';
		</script>
		<?
	}
}
else
{
	?>
	<script language="javascript">
		window.history.go(-1);
	</script>
	<?
}
//:
//echo "done";
//@mysql_query("DELETE FROM captcha_data WHERE `key`=$key_tmp;");
//endif;

?>