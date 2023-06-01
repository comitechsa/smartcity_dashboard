<?php

class sql_db
{
	var $db_connect_id;
	var $query_result;
	var $row = array();
	var $rowset = array();
	var $num_queries = 0;
	var $sql_queries = array();

	function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true)
	{

		$this->persistency = $persistency;
		$this->user = $sqluser;
		$this->password = $sqlpassword;
		$this->server = $sqlserver;
		$this->dbname = $database;

		if($this->persistency)
		{
			$this->db_connect_id = @mysql_pconnect($this->server, $this->user, $this->password);
		}
		else
		{
			$this->db_connect_id = @mysql_connect($this->server, $this->user, $this->password);
		}
		if($this->db_connect_id)
		{
			if($database != "")
			{
				$this->dbname = $database;
				$dbselect = @mysql_select_db($this->dbname);
				if(!$dbselect)
				{
					@mysql_close($this->db_connect_id);
					$this->db_connect_id = $dbselect;
				}
			}

			@mysql_query("SET NAMES utf8;", $this->db_connect_id);
			return $this->db_connect_id;
		}
		else
		{
			return false;
		}
	}

	function sql_close()
	{
		if($this->db_connect_id)
		{
			if($this->query_result)
			{
				@mysql_free_result($this->query_result);
			}
			$result = @mysql_close($this->db_connect_id);
			return $result;
		}
		else
		{
			return false;
		}
	}

	function sql_query($query = "")
	{
		unset($this->query_result);
		if($query != "")
		{
			//mysql_real_escape_string
			
			$_injection = false;
			/*
			if(preg_match("/drop table /",strtolower($query)))
			{
				$_injection = true;
			}
			
			if(preg_match("/truncate /",strtolower($query)))
			{
				$_injection = true;
			}	
			
			if(preg_match("/union select/",strtolower($query)))
			{
				$_injection = true;
			}
			*/
			//ALTER
			
			$HTTP_HOST = $_SERVER['HTTP_HOST'];
			$PHP_SELF = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : " ??? ";
			$IP = (getenv("HTTP_X_FORWARDED_FOR")) ? getenv("HTTP_X_FORWARDED_FOR") : getenv("REMOTE_ADDR");
			$PHP_SELF_IP = $IP . "@" . $HTTP_HOST . " (" . $PHP_SELF . ")";
			
			if(!$_injection)
			{
				$this->num_queries++;
				$this->sql_queries[] = $query;
	
				$this->query_result = @mysql_query($query, $this->db_connect_id);
				
				$message = @mysql_error($this->db_connect_id);
				$code = @mysql_errno($this->db_connect_id);
				if($message != "")
				{
					LogError($message,$PHP_SELF_IP,$query,"MYSQL");
				}
			}
			else
			{				
				LogError("",$PHP_SELF_IP,$query,"INJECTION ATTACH");
				$this->query_result = "";
			}
		}
		
		if($this->query_result)
		{
			unset($this->row[$this->query_result]);
			unset($this->rowset[$this->query_result]);
			return $this->query_result;
		}
		else
		{
			return false;
		}
	}

	function sql_numrows($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_num_rows($query_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_affectedrows()
	{
		if($this->db_connect_id)
		{
			$result = @mysql_affected_rows($this->db_connect_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_numfields($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_num_fields($query_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fieldname($offset, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_field_name($query_id, $offset);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fieldtype($offset, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_field_type($query_id, $offset);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fetchrow($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$this->row[$query_id] = @mysql_fetch_array($query_id);
			return $this->row[$query_id];
		}
		else
		{
			return false;
		}
	}
	function sql_fetchrowset($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			unset($this->rowset[$query_id]);
			unset($this->row[$query_id]);
			while($this->rowset[$query_id] = @mysql_fetch_array($query_id))
			{
				$result[] = $this->rowset[$query_id];
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fetchfield($field, $rownum = -1, $query_id = 0)
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
	function sql_nextid(){
		if($this->db_connect_id)
		{
			$result = @mysql_insert_id($this->db_connect_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_freeresult($query_id = 0){
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}

		if ( $query_id )
		{
			unset($this->row[$query_id]);
			unset($this->rowset[$query_id]);

			@mysql_free_result($query_id);

			return true;
		}
		else
		{
			return false;
		}
	}
	
	function sql_error($query_id = 0)
	{
		$result["message"] = @mysql_error($this->db_connect_id);
		$result["code"] = @mysql_errno($this->db_connect_id);

		return $result;
	}
	
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
	
		//echo $Statement.'<br><br>';
		
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
}

?>