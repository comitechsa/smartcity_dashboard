<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>

<?php
backup_tables('localhost','wangrjz_hotbox','qwe#123!@#','wangrjz_hotbox');
$mysqlExportPath ="backup/backup_" . date("Ymd").".sql.tar";
echo 'Database <b>library</b> successfully exported to <b>~/' .$mysqlExportPath .'</b>';
/* backup the db OR just a table */
function backup_tables($host,$user,$pass,$name,$tables = '*')
{
	
	$link = mysql_connect($host,$user,$pass);
    mysql_query("SET character_set_results=utf8", $link);
    //mb_language('uni'); 
    mb_internal_encoding('UTF-8');
	mysql_select_db($name,$link);
   // mysql_query("set names 'utf8'",$link);
	
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	
	//cycle through
	foreach($tables as $table)
	{
		$result = mysql_query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
		
		$return.= 'DROP TABLE '.$table.';';
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$return.= "\n\n".$row2[1].";\n\n";
		
		for ($i = 0; $i < $num_fields; $i++) 
		{
			while($row = mysql_fetch_row($result))
			{
				$return.= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
					if ($j<($num_fields-1)) { $return.= ','; }
				}
				$return.= ");\n";
			}
		}
		$return.="\n\n\n";
	}
	
	//save file
	$mysqlExportPath ="backup/backup_" . date("Ymd").".sql.tar";
	//$handle = fopen('backup/db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
	$handle = fopen($mysqlExportPath,'w+');
	fwrite($handle,$return);
	fclose($handle);
	$file_url = 'http://panel.spotyy.com/'.$mysqlExportPath;
	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary"); 
	header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\""); 
	//echo readfile($file_url);
	header("Location: $file_url");
	unlink($file_url);
}


?>