<?
	$currpath = realpath(__DIR__ . '/../../../..');
	
	//$host="localhost";
	//$user="wangrjz_hotbox";
	//$database="wangrjz_hotbox";
	//$pass="qwe#123!@#";
	
	include($currpath."/connection.php");
	
	//include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($currpath."/languages/".$auth->LanguageCode.".php");
	//other variables
	//$code=""; //password access variable

	$connection=mysql_connect($host,$user,$pass) or die ("could not connect");
	$db=mysql_select_db($database,$connection) or die ("could not connect to database");
	mysql_set_charset('utf8');

	if(!empty($_POST["user_id"])) {
		$query ="SELECT * FROM categories WHERE user_id = '" . $_POST["user_id"] . "'";
		$result= mysql_query($query) or die ("could not execute");

		?>
			<option value=""><?=choice?></option>
			<?
			while ($row = mysql_fetch_assoc($result)) {
			?>
				<option value="<?php echo $row["category_id"]; ?>"><?php echo $row["category_name"]; ?></option>
			<?
			}
	}
?>
