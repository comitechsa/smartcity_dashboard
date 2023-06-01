<?php

class sql_db
{
	var $connection;
	var $query_result;
	
	//var $num_queries = 0;
	//var $sql_queries = array();

	function sql_db($sqlserver, $sqluser, $sqlpassword, $database)
	{
		global $debugbar;
		
		try {
			if (!in_array("mysql",PDO::getAvailableDrivers(),TRUE))
			{
				throw new PDOException ("PDO connection could not find driver mysql");
			}
		}
		catch (PDOException $pdoEx)
		{
			echo "Database Error: <br /> {$pdoEx->getMessage()}";
			exit;
		}

		try {
			
			if(defined( '_DEBUG' ) )
			{
				//$this->connection = new DebugBar\DataCollector\PDO\TraceablePDO(new PDO('mysql:host='.$sqlserver.';dbname='.$database.';charset=utf8', $sqluser, $sqlpassword, array(PDO::MYSQL_ATTR_FOUND_ROWS => true)));
			
				$debugbar->addCollector(new DebugBar\DataCollector\PDO\PDOCollector($this->connection));
			}
			else
			{
				$this->connection = new PDO('mysql:host='.$sqlserver.';dbname='.$database.';charset=utf8', $sqluser, $sqlpassword, array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
				return $this->connection;
			}
			
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
		} catch(PDOException $e) {
			LogError($e->getMessage(), "", "connection open","PDO / sql_db");
			return false;
		}
	}
	
	function quote($value)
	{
		return $this->connection->quote($value);
	}

	function sql_close()
	{
		try {
			if($this->connection)
			{
				if($this->query_result)
				{
					$this->query_result->closeCursor();
				}
				$this->connection = null;
				return true;
			}
			else
			{
				return false;
			}
		} catch (PDOException $e) {
			LogError($e->getMessage(), "", "connection close","PDO / sql_close");
			return false;
		}

	}

	function sql_query($query = "", $args = "")
	{
		$query_result;
		/*if($this->query_result)
		{
			$this->query_result->closeCursor();
		}*/
		
		if($query != "")
		{
			//$this->num_queries++;
			//$this->sql_queries[] = $query;

			try { 
			if($args != "") 
			{
				$query_result = $this->connection->prepare($query); 
				$query_result->execute($args); 
			}
			else
			{
				$query_result = $this->connection->query($query);
			}
			
			//echo $query;
				
			//$sth = $pdh->query("SELECT * FROM sys.tables");
			//echo $this->query_result->fetchColumn();
				
			} catch(PDOExecption $e) { 
			echo $e->getMessage();
				LogError($e->getMessage(), "",$query, "PDO / sql_query");
			}
		}
		
		if($query_result)
		{
			$this->query_result = $query_result;
			return $this->query_result;
		}
		else
		{
			return false;
		}
	}

	function sql_numrows($query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			
			if($query_id)
			{
				return $query_id->rowCount();
				//$num_rows = $res->fetchColumn();
			}
			else
			{
				return false;
			}
			
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_numrows");
			return false;
		}
	}
	
	function sql_affectedrows()
	{
		try 
		{
			if($this->query_result)
			{
				return $this->query_result->rowCount();
			}
			else
			{
				return false;
			}
			
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_affectedrows");
			return false;
		}
	}
	
	function sql_numfields($query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				return $query_id->columnCount();
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_numfields");
			return false;
		}
	}
	
	function sql_fieldname($offset, $query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				$col = $query_id->getColumnMeta($offset);
     			return $col['name'];
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_fieldname");
			return false;
		}
	}
	
	function sql_fieldtype($offset, $query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				$col = $query_id->getColumnMeta($offset);
     			return $col['type'];
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_fieldtype");
			return false;
		}
	}
	
	function sql_fetchrow($query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				$row = $query_id->fetch(PDO::FETCH_BOTH);
				//print_r($row);
				return $row;
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_fetchrow");
			return false;
		}
	}
	
	function sql_fetchrowset($query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				return $query_id->fetchAll(PDO::FETCH_BOTH);
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_fetchrowset");
			return false;
		}
		
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
	}
	
	/*function sql_fetchfield($field, $rownum = -1, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			if($rownum > -1)
			{
				$result = @mysql_result($query_id, $rownum, $field);
			}
			else
			{
				if(empty($this->row[$query_id]) && empty($this->rowset[$query_id]))
				{
					if($this->sql_fetchrow())
					{
						$result = $this->row[$query_id][$field];
					}
				}
				else
				{
					if($this->rowset[$query_id])
					{
						$result = $this->rowset[$query_id][$field];
					}
					else if($this->row[$query_id])
					{
						$result = $this->row[$query_id][$field];
					}
				}
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	
	function sql_rowseek($rownum, $query_id = 0){
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_data_seek($query_id, $rownum);
			return $result;
		}
		else
		{
			return false;
		}
	}
	*/
	
	function sql_nextid()
	{
		try 
		{
			if($this->connection)
			{
				return $this->connection->lastInsertId();
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_nextid");
			return false;
		}
	}
	
	function sql_freeresult($query_id = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
	
			if ( $query_id )
			{
				$query_id->closeCursor();
				return true;
			}
			else
			{
				return false;
			}
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_freeresult");
			return false;
		}
	}
	
	/*function sql_error($query_id = 0)
	{
		$result["message"] = @mysql_error($this->connection);
		$result["code"] = @mysql_errno($this->connection);

		return $result;
	}
	*/
	function RowSelectorQuery($Statement)
	{
		$result = $this->sql_query($Statement);
		$dr = $this->sql_fetchrow($result);
		$this->sql_freeresult($result);
		return $dr;
	}
	
	function RowSelector($TableName = "", $PrimaryKeys, $QuotFields)
	{
		$Statement = "";
		$WhereStatement = "";
		
		if(!empty($PrimaryKeys))
		{
			foreach($PrimaryKeys as $key=>$val)
			{
				if($PrimaryKeys[$key] != "")
					$WhereStatement .= " " . $key . "=" . ((bool)($QuotFields[$key]) ? "'" : "") . ($PrimaryKeys[$key]) . ((bool)($QuotFields[$key]) ? "'" : "") . " AND " ;
			}
			
			if($WhereStatement != "")
			{
				$WhereStatement = " WHERE " . substr($WhereStatement,0,strlen($WhereStatement)-4);
			}
	
			if($WhereStatement != "")
			{
				$Statement = "SELECT * FROM " . $TableName . $WhereStatement . " LIMIT 1 ";
			}
			
			if($Statement != "")
			{
				$result = $this->sql_query($Statement);
				$dr = $this->sql_fetchrow($result);
				$this->sql_freeresult($result);
				return $dr;
			}
		}
	}
	
	function ExecuteUpdater($TableName = "", $PrimaryKeys, $Collector, $QuotFields)
	{
		$Statement = "";
		$WhereStatement = "";
		
		if(!empty($PrimaryKeys))
		{
			foreach($PrimaryKeys as $key=>$val)
			{
				if($PrimaryKeys[$key] != "")
					$WhereStatement .= " `" . $key . "`=" . ((bool)($QuotFields[$key]) ? "'" : "") . $PrimaryKeys[$key] . ((bool)($QuotFields[$key]) ? "'" : "") . " AND " ;
			}
		}
	
		if($WhereStatement != "")
		{
			$WhereStatement = " WHERE " . substr($WhereStatement,0,strlen($WhereStatement)-4);
		}
		
		if($WhereStatement != "")
		{
			$Statement = "UPDATE `" . $TableName . "` SET ";
			foreach($Collector as $key=>$val)
			{
				$Statement .= "`" . $key . "`=" . ($Collector[$key] != "" ? ((bool)($QuotFields[$key]) ? "'" : "") . $Collector[$key] . ((bool)($QuotFields[$key]) ? "'" : "") : " null " ) . ",";
			}
			//str_replace("'","''",$Collector[$key])
	
			$Statement = substr($Statement,0,strlen($Statement)-1) . $WhereStatement;
		}
		else
		{
			$Statement = "INSERT INTO `" . $TableName . "` (";
			foreach($Collector as $key=>$val)
			{
				$Statement .=  "`" . $key . "`,";
			}
	
			$Statement = substr($Statement,0,strlen($Statement)-1) . ") VALUES (";
	
			foreach($Collector as $key=>$val)
			{
				$Statement .= ($Collector[$key] != "" ? ((bool)($QuotFields[$key]) ? "'" : "") . $Collector[$key] . ((bool)($QuotFields[$key]) ? "'" : "") : " null " ) . ",";
			}
			//str_replace("'","''",$Collector[$key])
	
			$Statement = substr($Statement,0,strlen($Statement)-1) . ")";
		}
	
		//echo $Statement;
		//exit;
		$this->sql_query($Statement);
	}

	function ExecuteDeleter($TableName = "", $PrimaryKeys , $QuotFields)
	{
		$Statement = "";
		$WhereStatement = "";
		
		if(!empty($PrimaryKeys))
		{
			foreach($PrimaryKeys as $key=>$val)
			{
				if($PrimaryKeys[$key] != "")
					$WhereStatement .= " `" . $key . "`=" . ((bool)($QuotFields[$key]) ? "'" : "") . $PrimaryKeys[$key] . ((bool)($QuotFields[$key]) ? "'" : "") . " AND " ;
			}
	
			if($WhereStatement != "")
			{
				$WhereStatement = " WHERE " . substr($WhereStatement,0,strlen($WhereStatement)-4);
				$Statement = "DELETE FROM `" . $TableName . "` " . $WhereStatement . " ";
				$this->sql_query($Statement);
				
				//echo $Statement;
			}
		}
	}
	
	function ExecuteScalar($query_id = 0, $columnIndex = 0)
	{
		try 
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
				//print_r($row);
				return $query_id->fetchColumn($columnIndex);
			}
			else
			{
				return false;
			}
		
		} catch(PDOExecption $e) { 
			LogError($e->getMessage(), "",$query, "PDO / sql_fetchrow");
			return false;
		}
	}
}

?>