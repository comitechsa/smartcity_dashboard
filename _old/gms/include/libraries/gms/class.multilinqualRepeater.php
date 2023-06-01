<?
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

//type { PrimaryKey = 0, SimpleColumn = 1};
//dataTypes {STRING, INTEGER, DECIMAL, CURRENCY, DATETIME, BOOLEAN, BINARY, GUID, EMAIL, ZIP, AFM, PHONE};
//renderTypes {SINGLELINE, MULTILINE, HTMLEDITOR , CALENDAR, FILE, CHECKBOX, RADIOBUTTON, DROPDOWNLIST, POPUP, IMAGE};
//require { Yes = 0, No = 1};
//mode { Tab = 0, Horizontal = 1, Vertical = 2};

class MultilinqualRepeater
{
	var $StopRender = false;
	var $LangTableName;
	var $ColumnsName = array();
	var $ColumnsAliasRender = array();
	var $ColumnsType = array();
	var $ColumnsDataType = array();
	var $ColumnsHtmlRender = array();
	var $ColumnsRequire = array();
	var $ColumnsMaxLength = array();
	var $ColumnsPrimaryKeyValues = array();
	var $ColumnsHtmlEditor = array();
	var $ColumnsPriorities = array();
	var $ColumnsMessages = array();
	var $RenderMode = 0;
	var $TabInstanceName = "F_1";
	var $JavascriptRender;
	var $db;
	var $LanguagesTable;
	var $ErrorFoundInUpdate = "";
	var $RenderTable = "";

	function MultilinqualRepeater()
	{
		$this->Reset();
	}

	function Reset()
	{
		$this->RenderTable = "";
		$this->LanguagesTable = array();
		$this->ColumnsName = array();
		$this->ColumnsType = array();
		$this->ColumnsDataType = array();
		$this->ColumnsHtmlRender = array();
		$this->ColumnsRequire = array();
		$this->ColumnsMaxLength = array();
		$this->ColumnsPrimaryKeyValues = array();
		$this->ColumnsAliasRender = array();
		$this->ColumnsHtmlEditor = array();
		$this->ColumnsPriorities = array();
		$this->ColumnsMessages = array();
		$this->JavascriptRender = "";
	}

	function Init($langTableName,$_mode,&$_conn)
	{
		$this->Reset();

		$this->LangTableName = $langTableName;
		$this->RenderMode = strtoupper($_mode);
		$this->db = &$_conn;

		$query = "SELECT * FROM languages WHERE is_active='True'";
		$this->LanguagesTable = $this->db->sql_query($query);

		if($this->db->sql_numrows($this->LanguagesTable) <= 0){
			$this->StopRender = true;
		}
	}
	
	function ReloadLanguages()
	{
		$query = "SELECT * FROM languages WHERE is_active='True'";
		$this->LanguagesTable = $this->db->sql_query($query);

		if($this->db->sql_numrows($this->LanguagesTable) <= 0){
			$this->StopRender = true;
		}
	}

	function AddColumn()
	{
		$args = func_get_args();
		if(count($args) > 2)
		{
			$_columnName = $args[0];
			$_dataType = strtoupper($args[1]);
			$_columnType = strtoupper($args[2]);			
			$_htmlRender = count($args) > 3 ? $args[3] : "";
			$_columnTitle = count($args) > 4 ? $args[4] : "";
			$_aliasRender = count($args) > 5 ? $args[5] : "";
			$_require = count($args) > 6 ? $args[6] : "";
			$_maxLength = count($args) > 7 ? $args[7] : "";
			$_message = count($args) > 8 ? $args[8] : "";		
			$_htmlEditorMode = count($args) > 9 ? $args[9] : "";		
			
			array_push($this->ColumnsPriorities,$_columnName);
			$this->ColumnsDataType[$_columnName] = $_dataType;
			$this->ColumnsType[$_columnName] = $_columnType;
			$this->ColumnsName[$_columnName] = $_columnTitle;
			if($_aliasRender != '')
				$this->ColumnsAliasRender[$_columnName] = $_aliasRender;
			else
				$this->ColumnsAliasRender[$_columnName] = $_columnName;
			$this->ColumnsRequire[$_columnName] = $_require;
			$this->ColumnsHtmlRender[$_columnName] = strtoupper($_htmlRender);
			$this->ColumnsMaxLength[$_columnName] = $_maxLength;
			$this->ColumnsHtmlEditor[$_columnName] = $_htmlEditorMode;
			$this->ColumnsMessages[$_columnName] = $_message;
		}
	}
	
	function AddPrimaryKeyValue($_columnName,$_Value)
	{
		$this->ColumnsPrimaryKeyValues[$_columnName] = $_Value;
	}

	function RenderInsert()
	{
		$cell="";

		if(!$this->StopRender)
		{
			if($this->RenderMode == "TAB")
			{
				$Tab_Array = "";
				
				$cell = $this->GetTabStart();

				while ($dr = $this->db->sql_fetchrow($this->LanguagesTable))
				{
					$Tab_ID = $this->TabInstanceName . $dr["language_code"];

					$cell .= "<div id=\"" . $Tab_ID . "\">";
					
					$Tab_Array .= ",['" . $Tab_ID . "','" . str_replace("'","",$dr["language_name"]) . "']";

					$IsDefault = $dr["is_default"] == 'True';
				
					$cell .= "<input type='hidden' name='" . $Tab_ID . "_code' id='" . $Tab_ID . "_code' value='" . $dr["language_code"] . "'>";
					$cell .= "<input type='hidden' name='" . $Tab_ID . "_codeDesc' id='" . $Tab_ID . "_codeDesc' value='" . $dr["language_name"] . "'>";
					$cell .= "<input type='hidden' name='" . $Tab_ID . "_ck' id='" . $Tab_ID . "_ck' value='" . ($IsDefault ? "1" : "0" ) . "'>";
					$cell .= "<table border='0' class='m_n' width='100%' cellpadding='0' cellspacing='0'>";

					for($x=0 ; $x < count($this->ColumnsPriorities) ; $x++)
					{
						$key = $this->ColumnsPriorities[$x];
						$val = "";
						if($this->ErrorFoundInUpdate != "")
						{
							$Idref = $this->TabInstanceName . $dr["language_code"] . $this->ColumnsAliasRender[$key];
							if(isset($_POST[$Idref]) && !empty($_POST[$Idref])) {
								$val = $_POST[$Idref];
							}
						}
						
						if($this->ColumnsType[$key] != "PRIMARYKEY")
							$cell .= $this->RenderControl($key,$dr["language_code"],$val);
					} 
					$cell .= "</table>";
					$cell .= "</div>";
				}

				$cell .= $this->GetTabEnd();
				
				$this->RenderTable = $cell;
				
				$this->JavascriptRender = "<script language=\"javascript\">\n var tab_Array" . $this->TabInstanceName . " = " . "[" . substr($Tab_Array,1) . "];";
				
				$this->JavascriptRender .= "\n var " . $this->TabInstanceName . " = new gtab(0,tab_Array" . $this->TabInstanceName . ",'" . $this->TabInstanceName . "',1);";
				
				$this->JavascriptRender .= $this->GetValidation($this->TabInstanceName);
					
				$this->JavascriptRender .= "\n</script>";
			}
		}
	}
	
	function RenderUpdate()
	{
		 $cell = "";

		if(!$this->StopRender)
		{
			if($this->RenderMode == "TAB")
			{
				$Tab_Array = "";
					
				$cell .= $this->GetTabStart();

				$statement = "SELECT * from languages inner join " . $this->LangTableName . " on languages.language_id = " . $this->LangTableName . ".language_id";
				
				$WhereSt = "";

				foreach($this->ColumnsPrimaryKeyValues as $key=>$val)
				{
					$WhereSt .= " AND " . $key . " = " . $this->GetQuot($key) . $this->ColumnsPrimaryKeyValues[$key] . $this->GetQuot($key);
				}				
				
				while ($dr = $this->db->sql_fetchrow($this->LanguagesTable))
				{
					$drVals = "";
					$query = $statement . " WHERE " . $this->LangTableName . ".language_id=" . $dr["language_id"] . $WhereSt;
					
					$CurrentTable = $this->db->sql_query($query);
					
					if($this->db->sql_numrows($CurrentTable) > 0){
						$drVals = $this->db->sql_fetchrow($CurrentTable);
					}
		
					$Tab_ID = $this->TabInstanceName . $dr["language_code"];

					$Tab_Array .= ",['" . $Tab_ID . "','" . str_replace("'","",$dr["language_name"]) . "']";
						
					$cell .= "<div id=\"" . $Tab_ID . "\">";
					
					$IsDefault = false;//$dr["is_default"] == 'True';
					
					$cell .= "<input type='hidden' name='" . $Tab_ID . "_code' id='" . $Tab_ID . "_code' value='" . $dr["language_code"] . "'>";
					$cell .= "<input type='hidden' name='" . $Tab_ID . "_codeDesc' id='" . $Tab_ID . "_codeDesc' value='" . $dr["language_name"] . "'>";
					$cell .= "<input type='hidden' name='" . $Tab_ID . "_ck' id='" . $Tab_ID . "_ck' value='" . (!empty($drVals) ? "1" : $IsDefault ? "1" : "0" ) . "'>";
					
					
					$cell .= "<table border='0' class='m_n' width='100%' cellpadding='0' cellspacing='0'>";

					for($x=0 ; $x < count($this->ColumnsPriorities) ; $x++)
					{
						$key = $this->ColumnsPriorities[$x];
						$val = "";
						if($this->ErrorFoundInUpdate != "")
						{
							//if error found keep the values user insert
							$Idref = $this->TabInstanceName . $dr["LanguageCode"] . $this->ColumnsAliasRender[$key];
							if(isset($_POST[$Idref]) && !empty($_POST[Idref]))
							{
								$val = $_POST[$Idref];
							}
						}
						else
						{
							$val = (isset($drVals) && !empty($drVals) ? $drVals[$key] : "");
						}

						if($this->ColumnsType[$key] != "PRIMARYKEY")
							$cell .= $this->RenderControl($key,$dr["language_code"],$val);
					} 

					$cell .= "</table>";
					$cell .= "</div>";
				}

				$cell .= $this->GetTabEnd();
				
				$this->RenderTable = $cell;
				$this->JavascriptRender = "<script language=\"javascript\">\n var tab_Array" . $this->TabInstanceName . " = " . "[" . substr($Tab_Array,1) . "];";

				$this->JavascriptRender .= "\n var " . $this->TabInstanceName . " = new gtab(0,tab_Array" . $this->TabInstanceName . ",'" . $this->TabInstanceName . "',1);";
				$this->JavascriptRender .= $this->GetValidation($this->TabInstanceName);
				
				$this->JavascriptRender .= " \n</script>";
			}
		}
	}

	function GetRender()
	{
		global $validator;
		$validator->AddMultiligualValidate($this->TabInstanceName);
		return $this->RenderTable . "<div>" . $this->JavascriptRender . "</div>";
	}

	function GetValidation($prefix)
	{
		$Validation_Array = "";
		foreach($this->ColumnsName as $key=>$val)
		{
			if($this->ColumnsType[$key] != "PRIMARYKEY")
			{
				$requir = $this->ColumnsRequire[$key] ? "1" : "0";
				$_type = $this->ColumnsDataType[$key];
				$Validation_Array .= ",['" . $this->ColumnsAliasRender[$key] . "','" . $requir . "','" . $_type . "','" . $this->ColumnsMessages[$key] . "']";
			}
		}

		if($Validation_Array != "")
		{
			return "\n var validation" . $prefix . " = [" . substr($Validation_Array,1) . "];";
		}

		return "";
	}

	function RenderControl($key,$subId,$val)
	{
		global $config;
		$ret = "<tr>";
		$ret .= "<td valign='middle' align='right'><nobr>" . $this->ColumnsName[$key] . "&nbsp;" . "</nobr></td>";
		
		$maxLength = "";
		if ($this->ColumnsMaxLength[$key] != "-1")
		{
			$maxLength = "maxlength='" . $this->ColumnsMaxLength[$key] . "'";
		}
		
		$Idref = $this->TabInstanceName . $subId . $this->ColumnsAliasRender[$key];

		$ret .= "<td width='100%'>";
		if(strtoupper($this->ColumnsHtmlRender[$key]) == "HTMLEDITOR")
		{			
			$ret .= HtmlEditor::GetRender($Idref,$val,($this->ColumnsHtmlEditor[$key] != "" ? $this->ColumnsHtmlEditor[$key] : ""));
		}		
		else if(strtoupper($this->ColumnsHtmlRender[$key]) == "MULTILINE")
		{
			$ret .= TextBox::GetRender($Idref,$val,$maxLength,"70%","TextArea","5");
		}
		else if(strtoupper($this->ColumnsHtmlRender[$key]) == "UPLOAD")
		{
			$ret .= Upload::GetRender($Idref,$val);
		}
		else if(strtoupper($this->ColumnsHtmlRender[$key]) == "SINGLELINE")
		{
			$ret .= TextBox::GetRender($Idref,$val,$maxLength);
		}
		$ret .= "</td>";

		$ret .= "</tr>";
		return $ret;
	}

	function GetTabStart()
	{
		return "<div id='" . $this->TabInstanceName . "'>";
	}

	function GetTabEnd()
	{
		return "</div>";
	}


	function GetQuot($key)
	{
		return $this->ColumnsDataType[$key] == "STRING" || $this->ColumnsDataType[$key] == "DATE" || $this->ColumnsDataType[$key] == "DATETIME" ? "'" : "";
	}

	function Update()
	{
		$replace_quot = false;
		
		if(!$this->StopRender)
		{
			if(count($this->ColumnsPrimaryKeyValues) > 0)
			{
				$statement = "";
							
				while ($dr = $this->db->sql_fetchrow($this->LanguagesTable))
				{
					$IsInsertMode = true;
					$statement = "SELECT count(*) from " . $this->LangTableName;
					$WhereSt = " WHERE language_id=" . $dr["language_id"] . " ";

					foreach($this->ColumnsPrimaryKeyValues as $key=>$val)
					{	
						$WhereSt .= " AND " . $key . " = " . $this->GetQuot($key) . $this->ColumnsPrimaryKeyValues[$key] . $this->GetQuot($key);
					}
					$query = $statement . $WhereSt;
					
					$r = $this->db->sql_query($query);
		
					$oneLang = count($this->LanguagesTable) == 1;
					
					$IsInsertMode = (int)($this->db->sql_fetchfield(0,0,$r)) > 0 ? 1 : 0;		
					$this->db->sql_freeresult($r);
					
					$Check_ID = $this->TabInstanceName . $dr["language_code"] . "_ck";
									
					if( (isset($_POST[$Check_ID]) && !empty($_POST[$Check_ID]) && $_POST[$Check_ID] == "1") 
					|| $oneLang
					)
					{
						$statement = "";
						
						$ExecuteStatement = true;
						if($IsInsertMode == 0)
						{
							$statement = "INSERT INTO " . $this->LangTableName;
							$Columns = "";
							$ColumnsValues = "";

							foreach($this->ColumnsName as $key=>$val)
							{
								if($this->ColumnsType[$key] != "PRIMARYKEY")
								{
									$Idref = $this->TabInstanceName . $dr["language_code"] . $this->ColumnsAliasRender[$key];
							
									if(isset($_POST[$Idref]) && !empty($_POST[$Idref]))
									{
										$Columns .= "," . $key;
										$ColumnsValues .= "," . $this->GetQuot($key) . ($replace_quot ? str_replace("'","''",$_POST[$Idref]) : $_POST[$Idref] ) . $this->GetQuot($key);
									}
									else
									{
										if((bool)$this->ColumnsRequire[$key])
										{
											$ExecuteStatement = false;
											break;
										}
										else
										{
											$Columns .= "," . $key;
											$ColumnsValues .= ",NULL";
										}
									}
								}
							}

							$Columns .= ",language_id";
							$ColumnsValues .= ",'" . $dr["language_id"] . "'";
							
							foreach($this->ColumnsPrimaryKeyValues as $key=>$val)
							{
								$Columns .= "," . $key;
								$ColumnsValues .= "," . $this->GetQuot($key) . $this->ColumnsPrimaryKeyValues[$key] . $this->GetQuot($key);
							}

							$statement .= "(" . substr($Columns,1) . ") VALUES (" . substr($ColumnsValues,1) . ")";
						}
						else
						{
							$statement = "UPDATE " . $this->LangTableName . " SET ";
							$ColumnsAndValues = "";

							foreach($this->ColumnsName as $key=>$val)
							{
								if($this->ColumnsType[$key] != "PRIMARYKEY")
								{
									$Idref = $this->TabInstanceName . $dr["language_code"] . $this->ColumnsAliasRender[$key];
							
									if(isset($_POST[$Idref]) && !empty($_POST[$Idref]))
									{
										$ColumnsAndValues .= "," . $key . "=" . $this->GetQuot($key) . ($replace_quot ? str_replace("'","''",$_POST[$Idref]) : $_POST[$Idref] ) . $this->GetQuot($key);
									}
									else
									{
										if((bool)$this->ColumnsRequire[$key])
										{
											$ExecuteStatement = false;
											break;
										}
										else
										{
											$ColumnsAndValues .= "," . $key . "=NULL";
										}
									}
								}
							}
							$statement .= substr($ColumnsAndValues,1) . " " . $WhereSt;
						}
						
						if($ExecuteStatement)
						{
							//echo "<xmp>$statement</xmp>";
							$this->db->sql_query($statement);
						}
					}
					else
					{
						$this->db->sql_query(" DELETE FROM " . $this->LangTableName . $WhereSt);						
					}
				}
				return true;
			}
			else
			{
				echo "<!--no primary keys enter exit-->";
			}
		}
		return false;
	}
}
?>