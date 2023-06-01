<?
	//$currpath = realpath(__DIR__ . '/../../../..');
	
	$host="localhost";
	$user="wangrjz_weighing";
	$database="wangrjz_weighing";
	$pass="qwe#123!@#";
	
	//include($currpath."/connection.php");
	
	//include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	//include($currpath."/languages/".$auth->LanguageCode.".php");
	//other variables
	//$code=""; //password access variable

	$connection=mysql_connect($host,$user,$pass) or die ("could not connect");
	$db=mysql_select_db($database,$connection) or die ("could not connect to database");
	mysql_set_charset('utf8');
	if(!empty($_POST["municipality_id"])) {
		$query ="SELECT * FROM bin_types WHERE municipality_id = '" . $_POST["municipality_id"] . "'";
		$result= mysql_query($query) or die ("could not execute");
		?>
			<option value="0">Επιλογή</option>
			<?
			while ($row = mysql_fetch_assoc($result)) {
			?>
				<option value="<?php echo $row["bintype_id"]; ?>"><?php echo $row["name"]; ?></option>
			<?
			}
	}
?>
