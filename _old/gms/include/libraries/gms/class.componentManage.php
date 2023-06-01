<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

class GmsCM
{
	var $PrimatyKeyValue = "";
	var $Collection = array();
	var $TableColumns = 2;
		
	function GmsCM($_collection, $_primatyKeyValue = "")
	{
		$this->Collection = $_collection;
		$this->PrimatyKeyValue = $_primatyKeyValue;
	}
	
	//$args is hash Array of arguments
	function RenderDisplay($BaseUrl, $args = null, $divAttr = "")
	{
		global $db,$toolBar,$validator,$auth;
		
		$__UNIQUEID = isset($this->Collection["DECLARETION"]["UNIQUEID"]) ? $this->Collection["DECLARETION"]["UNIQUEID"] : "";
		$__Filter = isset($args["Filter"]) ? $args["Filter"] : "";
		$__Order = isset($args["Order"]) ? $args["Order"] : "";
		$__AllowSorting = isset($args["AllowSorting"]) ? $args["AllowSorting"] : false;
		$__AllowPaging = isset($args["AllowPaging"]) ? $args["AllowPaging"] : true;
		$__RowPerPage = isset($args["RowPerPage"]) ? $args["RowPerPage"] : 20;
		$__ThemePath = isset($args["ThemePath"]) ? $args["ThemePath"] : "";
		$__InnerJoinLanguageTable = isset($args["InnerJoinLanguageTable"]) && $args["InnerJoinLanguageTable"] == true ? true : false;
		$__ShowSearchPanel = isset($args["ShowSearchPanel"]) ? $args["ShowSearchPanel"] : false;
		$__ShowSelector = isset($args["ShowSelector"]) ? $args["ShowSelector"] : true;
		$__ShowToolbar = isset($args["ShowToolbar"]) ? $args["ShowToolbar"] : true;
		
		if(isset($this->Collection["DECLARETION"]["SEARCH_FILTER"]) && $this->Collection["DECLARETION"]["SEARCH_FILTER"] != "" && isset($_POST["src_txt"]) && $_POST["src_txt"] != "")
		{
			if($__Filter != "")
				$__Filter .= " AND ";
			
			$__Filter .= str_replace("@param",str_replace("'","''",$_POST["src_txt"]),$this->Collection["DECLARETION"]["SEARCH_FILTER"]);
			
		}
		////********** Paging Const ***********/////
		$Paging = "";
		$CurrentPageNumber = 0;
		if($__AllowPaging)
		{
			$CurrentPageNumber = isset($_GET[$__UNIQUEID . "start"]) ? intval($_GET[$__UNIQUEID . "start"]) : 0;
			$Paging = " LIMIT " . $CurrentPageNumber . " , " . $__RowPerPage;
		}
		////*********************************/////
		
		////********** Query Builder ***********/////
		$__GRID_SELECT_FIELDS = isset($this->Collection["DECLARETION"]["GRID_SELECT_FIELDS"]) ? $this->Collection["DECLARETION"]["GRID_SELECT_FIELDS"] : "*";
		
		$ColumnSelection = " ".$__GRID_SELECT_FIELDS.", " . $this->Collection["DECLARETION"]["TABLE"] . "." . $this->Collection["DECLARETION"]["PRIMARY_KEY_COLUMN"] . " AS PRIMARY_KEY ";
		
		$query = "SELECT " . $ColumnSelection ;
		$query .= " FROM " . $this->Collection["DECLARETION"]["TABLE"];
		
		if(isset($this->Collection["DECLARETION"]["LANGUAGE_TABLE"]) && $this->Collection["DECLARETION"]["LANGUAGE_TABLE"] != "")
		{
			$query .= $__InnerJoinLanguageTable ? " INNER JOIN " : " LEFT JOIN ";
			
			$query .= $this->Collection["DECLARETION"]["LANGUAGE_TABLE"];
			$query .= " ON " . $this->Collection["DECLARETION"]["TABLE"] . "." . $this->Collection["DECLARETION"]["PRIMARY_KEY_COLUMN"];
			$query .= " = " . $this->Collection["DECLARETION"]["LANGUAGE_TABLE"] . "." . $this->Collection["DECLARETION"]["PRIMARY_KEY_COLUMN"];
			$query .= " AND " . $this->Collection["DECLARETION"]["LANGUAGE_TABLE"] . ".language_id=" . $auth->LanguageID;
			
		}
		
		if($__Filter != "")
			$query .= " WHERE " . $__Filter;
		
		if($__Order != "")
			$query .= " ORDER BY " . $__Order;
		
		////*********************************/////

		$result = $db->sql_query($query . " " . $Paging);
		
		$TotalRows = -1;
		if($__AllowPaging)
		{
			////********** Paging Parse ***********/////
			$pagingresult = $db->sql_query(str_replace($ColumnSelection," count(*) ",$query));
			$TotalRows = ( $row = $db->sql_fetchrow($pagingresult) ) ? intval($row[0]) : 0;
			$db->sql_freeresult($pagingresult);
			////**************************/////	
		}

		////********** Generator ***********/////
		if($__ThemePath != "")
		{
			if(file_exists($__ThemePath))
				include($__ThemePath );
			else
			{
				LogError("File not found: " . $__ThemePath,__FILE__,__LINE__,"PHP");
			}
		}
		else
		{
			$myGrid = new DataGrid($BaseUrl ,$result);
			$myGrid->TotalRows = $TotalRows;
			$myGrid->Start = $CurrentPageNumber;
			$myGrid->RowPerPage = $__RowPerPage;
			$myGrid->PrimaryKey = "PRIMARY_KEY";
			$myGrid->ShowSelector = $__ShowSelector; 
			$myGrid->Header = $this->Collection["DECLARETION"]["HEADER"];
			$myGrid->UNIQUEID = $__UNIQUEID;
			if(isset($this->Collection["DECLARETION"]["SEARCH_FILTER"]) && $this->Collection["DECLARETION"]["SEARCH_FILTER"] != "")
			{
				$myGrid->ShowSearchFilter = true;
				if(isset($this->Collection["DECLARETION"]["SEARCH_TITLE"]) && $this->Collection["DECLARETION"]["SEARCH_TITLE"] != "")
				{
					$myGrid->ShowSearchTitle = $this->Collection["DECLARETION"]["SEARCH_TITLE"];
				}
			}
			
			$__ColumnWidth = array();
			$__ColumnsAlias = array();
			$__RealValuesFunc = array();
			foreach($this->Collection["SCHEMA"] as $key=>$value)
			{
				if(isset($value["DISPLAY_GRID_WIDTH"]) && $value["DISPLAY_GRID_WIDTH"] != "")
				{
					$__ColumnWidth[$key] = $value["DISPLAY_GRID_WIDTH"] ;
					$__ColumnsAlias[$key] = $value["TITLE"] ;
					if(isset($value["REAL_VALUE_FUNC"]) && $value["REAL_VALUE_FUNC"] != "")
						$__RealValuesFunc[$key] = $value["REAL_VALUE_FUNC"] ;
				}			
			}
			$myGrid->ColumnWidth = $__ColumnWidth;
			$myGrid->ColumnsAlias = $__ColumnsAlias;
			$myGrid->RealValuesFunc = $__RealValuesFunc;
			
			echo $myGrid->GetRender($divAttr);
		}
		
		$db->sql_freeresult($result);
		
		if($__ShowToolbar)
		{
			$toolBar->AddCommand("NEWRECORD","new.gif",core_newRecord);
			$toolBar->AddCommand("EDIT","edit.gif",core_edit,0,1);
			$toolBar->AddCommand("DELETE","delete.gif",core_delete,0,1,core_deleteConfirm);
		}
	}
	
	function RenderIU($divAttr="")
	{
		global $db,$toolBar,$validator,$config,$auth;
		$ret = "";
		$ret .= "<div class='m_dbt m_ium' " . ($divAttr != "" ? $divAttr : "") . ">";
		$ret .= "<table align='center' width='100%' cellspacing='1' cellpadding='4' class='m_ct'>";
		$ret .= "<tr><td class='m_wt' colspan='" . ($this->TableColumns*2) . "'>" . $this->Collection["DECLARETION"]["HEADER"] . " :: " . core_insertUpdate . "</td></tr>";
		
		if($this->PrimatyKeyValue != "")
		{
			$PrimaryKeys = array();
			$QuotFields = array();
			$PrimaryKeys[$this->Collection["DECLARETION"]["PRIMARY_KEY_COLUMN"]] = $this->PrimatyKeyValue ;
			$QuotFields[$this->Collection["DECLARETION"]["PRIMARY_KEY_COLUMN"]] = false;
			
			$dr = $db->RowSelector($this->Collection["DECLARETION"]["TABLE"],$PrimaryKeys,$QuotFields);
			$ret .= "<input type='hidden' name='pk_id' id='pk_id' value='" . $this->PrimatyKeyValue . "'>";
		}
		
		$SetInitRow = false;
		$FoundLanguageField = false;
		$i = 0;

		foreach($this->Collection["SCHEMA"] as $key=>$value)
		{
			if((!isset($value["LANG_DEPEND"]) || $value["LANG_DEPEND"] == 0) && $value["FIELD_TYPE"] != "DISPLAY")
			{
				if($i % $this->TableColumns == 0)
					$ret .= "<tr class='m_ng'>";
					
				$ret .= "<td align='right' nowrap>" . $value["TITLE"] . ": </td>";
				
				$ret .= "<td " . (!$SetInitRow ? "width='" . ($this->TableColumns > 1 ? 100/$this->TableColumns : "70") . "%'" : "") . ">";
				
				$DefaultValue = isset($value["DEFAULT_VALUE"]) ? $value["DEFAULT_VALUE"] : "";
				$actualValue = (isset($dr) && isset($dr[$key]) ? $dr[$key] : $DefaultValue );
				$MaxLength =  isset($value["MAXLENGTH"]) ? $value["MAXLENGTH"] : "";
				$Require = isset($value["REQUIRE"]) ? $value["REQUIRE"] : "0";
								
				if(isset($value["REAL_RENDER_FUNC"]) && $value["REAL_RENDER_FUNC"] != "")
				{
					$RealRender = "";
					$args = array($actualValue,$this->PrimatyKeyValue,$DefaultValue);
					if (function_exists( $value["REAL_RENDER_FUNC"] )) {
						$RealRender = call_user_func_array( $value["REAL_RENDER_FUNC"], $args );
					}
					$ret .= $RealRender;
				}
				else if($value["FIELD_TYPE"] == "LOOKUP")
				{
					if(isset($value["LOOKUP_MANY_TO_MANY_TABLE_NAME"]) && $value["LOOKUP_MANY_TO_MANY_TABLE_NAME"] != "")
					{
						
					}
					else
					{
						$query = "SELECT * FROM " . $value["LOOKUP_TABLE_NAME"];
						if(isset($value["LOOKUP_LANG_TABLE_NAME"]) && $value["LOOKUP_LANG_TABLE_NAME"] != "")
						{
							$query .= " INNER JOIN " . $value["LOOKUP_LANG_TABLE_NAME"];
							$query .= " ON " . $value["LOOKUP_TABLE_NAME"] . "." . $value["LOOKUP_KEY"];
							$query .= " = " . $value["LOOKUP_LANG_TABLE_NAME"] . "." . $value["LOOKUP_KEY"];
							$query .= " AND " . $value["LOOKUP_LANG_TABLE_NAME"] . ".language_id=" . $auth->LanguageID;							
						}
						
						
						if(strtoupper($value["HTML_RENDER"]) == "POPUP" && isset($value["POPUP_BASEURL"]) && $value["POPUP_BASEURL"] != "")
						{
							$SelectedLabels = "";
							$SelectedValues = "";
							if($actualValue != "")
							{
								$query .=  " WHERE " . $value["LOOKUP_TABLE_NAME"] . "." . $value["LOOKUP_KEY"] . "=" . $actualValue;
								$result = $db->sql_query($query);
								$Lrow = $db->sql_fetchrow($result);
								if($Lrow)
								{
									$SelectedLabels = $Lrow[$value["LOOKUP_VALUE"]];
									$SelectedValues = $Lrow[$value["LOOKUP_KEY"]];
								}
								$db->sql_freeresult($result);
								
							}
							
							
							$PopupCaller = "ShowPopup(this,'" . $value["POPUP_BASEURL"] . "');";
							$PopupHtml = "";
							
							$__ID = "popup_" . $key;
							$PopupHtml .= "<div id='" . $key . "_div'>&nbsp;</div>";
							$PopupHtml .= "<script language='javascript'>";
							$PopupHtml .= $__ID . " = new PopupAdmin(\"" . $key .  "\",\"" . $PopupCaller . "\",\"" . $SelectedValues . "\",\"" . $SelectedLabels . "\",1);";
							$PopupHtml .= "</script>";			
							$ret .= $PopupHtml;
						}
						else
						{
							// ($Require == 1 ? false : true)
							if(isset($value["LOOKUP_FILTER"]) && $value["LOOKUP_FILTER"] != "")
							{
								$query .= " WHERE " . $value["LOOKUP_FILTER"];
							}
							
							if(isset($value["LOOKUP_ORDER"]) && $value["LOOKUP_ORDER"] != "")
							{
								$query .= " ORDER BY " . $value["LOOKUP_ORDER"];
							}
							$ret .= Select::GetDbRender($key, $query,$value["LOOKUP_KEY"], $value["LOOKUP_VALUE"], $actualValue,true);
						}
					}					
				}
				else
				{
					if(strtoupper($value["HTML_RENDER"]) == "ENUM")
					{
						$ret .= Select::GetEnumRender($key,$value["ENUM_PAIRS"],$actualValue,false,"");
					}
					else if(strtoupper($value["HTML_RENDER"]) == "CHECKBOX")
					{
						$ret .= CheckBox::GetRender($key,($actualValue == $value["CHECKBOX_KEYS"][0] ? true : false));
					}
					else if(strtoupper($value["HTML_RENDER"]) == "SINGLELINE")
					{
						$ret .= TextBox::GetRender($key,$actualValue,$MaxLength);
					}
					else if(strtoupper($value["HTML_RENDER"]) == "MULTILINE")
					{
						$ret .= TextBox::GetRender($key,$actualValue,$MaxLength,"70%","TextArea","5");
					}
					else if(strtoupper($value["HTML_RENDER"]) == "PASSWORD")
					{
						$ret .= TextBox::GetRender($key,$actualValue,$MaxLength,"70%","password");
					}
					else if(strtoupper($value["HTML_RENDER"]) == "CALENDAR")
					{
						$ret .= Calendar::GetRender($key,$actualValue);
					}
					else if(strtoupper($value["HTML_RENDER"]) == "UPLOAD")
					{
						$ret .= Upload::GetRender($key,$actualValue);
					}
					else if(strtoupper($value["HTML_RENDER"]) == "PUPLOAD")
					{
						$ret .= pUpload::GetRender($key,$actualValue);
					}
					else if(strtoupper($value["HTML_RENDER"]) == "HTMLEDITOR")
					{
						$ret .= HtmlEditor::GetRender($key,$actualValue,'');
					}
					else if(strtoupper($value["HTML_RENDER"]) == "GMAP")
					{
						$ret .= Gmap::GetRender($key,$actualValue);
					}
				}
							
				$RequireErrorMessage = isset($value["REQUIRE_ERROR_MESSAGE"]) ? $value["REQUIRE_ERROR_MESSAGE"] : "";
				
				$val_DB_TYPE = isset($value["DB_TYPE"]) ? $value["DB_TYPE"] : "String";
				if($val_DB_TYPE == "DOUBLE") $val_DB_TYPE = "Double";
				else if($val_DB_TYPE == "INTEGER") $val_DB_TYPE = "Integer";
				else $val_DB_TYPE = "String";
				
				$validator->AddTagValidator($key,$Require ,$val_DB_TYPE,$RequireErrorMessage);
				
				if($i % $this->TableColumns+1 == $this->TableColumns)
				{
					$ret .= "</tr>";
					$SetInitRow = true;
				}
				
				$i++;
			}
			else if ($value["FIELD_TYPE"] == "DISPLAY")
			{
			
			}
			else
			{
				$FoundLanguageField = true;
			}
		}		
		
		if($FoundLanguageField && isset($this->Collection["DECLARETION"]["LANGUAGE_TABLE"]) && $this->Collection["DECLARETION"]["LANGUAGE_TABLE"] != "")
		{
			$ret .= "<tr class='m_ng'><td colspan='" . ($this->TableColumns*2) . "'>";
			$keyValue = (isset($dr) ? $dr[$this->Collection["DECLARETION"]["PRIMARY_KEY_COLUMN"]] : "" );
			$ret .= $this->ParseMulitilingual($keyValue);
			$ret .= "</td></tr>";
		}
		
		$ret .= "</table>";
		$ret .= "</div><br>";
		
		$toolBar->AddCommand("SAVE","save.gif",core_save,true,false);
		$toolBar->AddCommand("BACK","back.gif",core_back);
		
		echo $ret;
	}
	
	function IsUpdateMode()
	{
		return ((isset($this->PrimatyKeyValue) && $this->PrimatyKeyValue != "") || (isset($_POST["pk_id"]) && $_POST["pk_id"] != ""));
	}
	
	function GetPostPK()
	{
		return isset($_POST["pk_id"]) && $_POST["pk_id"] != "" ? $_POST["pk_id"] : "-1";
	}
	
	function UpdateUI()
	{
		global $db;
		
		if($this->PrimatyKeyValue == "" && isset($_POST["pk_id"]) && $_POST["pk_id"] != "")
		{
			$this->PrimatyKeyValue = $_POST["pk_id"] ;
		} 

		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		
		if($this->PrimatyKeyValue != "")
		{
			$PrimaryKeys[$this->Collection["DECLARETION"]["PRIMARY_KEY_COLUMN"]] = $this->PrimatyKeyValue;
			$QuotFields[$this->Collection["DECLARETION"]["PRIMARY_KEY_COLUMN"]] = false;
		}	
		
		$FoundLanguageField = false;
		
		foreach($this->Collection["SCHEMA"] as $key=>$value)
		{
			if(!isset($value["LANG_DEPEND"]) || $value["LANG_DEPEND"] == 0)
			{
				if(isset($value["REAL_SAVE_FUNC"]) && $value["REAL_SAVE_FUNC"] != "")
				{
					
				}
				else if( $value["FIELD_TYPE"] != "DISPLAY")
				{
					if($value["FIELD_TYPE"] == "LOOKUP")
					{
						if(isset($value["LOOKUP_MANY_TO_MANY_TABLE_NAME"]) && $value["LOOKUP_MANY_TO_MANY_TABLE_NAME"] != "")
						{
							
						}
						else
						{
							if(is_array($_POST[$key]))
							{
								$Collector[$key] = $_POST[$key][0];
							}
							else
							{
								$Collector[$key] = $_POST[$key];
							}
						}				
					}
					else if(strtoupper($value["HTML_RENDER"]) == "CHECKBOX")
					{
						$Collector[$key] = isset($_POST[$key]) && $_POST[$key] == "1" ? $value["CHECKBOX_KEYS"][0] : $value["CHECKBOX_KEYS"][1];							
					}
					else
					{
						$Collector[$key] = $_POST[$key];
					}

//					if(!isset($value["DB_TYPE"])) echo $key;
					$QuotFields[$key] = $this->GetQuot($value["DB_TYPE"]);		
				}
				else if ($value["FIELD_TYPE"] == "DISPLAY")
				{
					if(isset($value["DEFAULT_INSERT_VALUE"]) && $value["DEFAULT_INSERT_VALUE"] != "" && $this->PrimatyKeyValue == "")
					{
						$Collector[$key] = $value["DEFAULT_INSERT_VALUE"];
						$QuotFields[$key] = $this->GetQuot($value["DB_TYPE"]);		
					}
					
					if(isset($value["DEFAULT_UPDATE_VALUE"]) && $value["DEFAULT_UPDATE_VALUE"] != "" && $this->PrimatyKeyValue != "")
					{
						$Collector[$key] = $value["DEFAULT_UPDATE_VALUE"];
						$QuotFields[$key] = $this->GetQuot($value["DB_TYPE"]);		
					}
				}
			}
			else
			{
				$FoundLanguageField = true;
			}
		}
		
		
		$db->ExecuteUpdater($this->Collection["DECLARETION"]["TABLE"],$PrimaryKeys,$Collector,$QuotFields);
		if($this->PrimatyKeyValue == "")
		{
			$this->PrimatyKeyValue = $db->sql_nextid();
		}
		
		if($FoundLanguageField && isset($this->Collection["DECLARETION"]["LANGUAGE_TABLE"]) && $this->Collection["DECLARETION"]["LANGUAGE_TABLE"] != "")
			$this->ParseMulitilingual($this->PrimatyKeyValue,false);
			
		
		foreach($this->Collection["SCHEMA"] as $key=>$value)
		{
			if(isset($value["REAL_SAVE_FUNC"]) && $value["REAL_SAVE_FUNC"] != "")
			{
				$args = array($this->PrimatyKeyValue);
				if (function_exists( $value["REAL_SAVE_FUNC"] )) {
					call_user_func_array( $value["REAL_SAVE_FUNC"], $args );
				}
			}	
		}
	}
	
	function DeleteRow()
	{
		global $db;
		if($this->PrimatyKeyValue != "")
		{
			$PrimaryKeys[$this->Collection["DECLARETION"]["PRIMARY_KEY_COLUMN"]] = $this->PrimatyKeyValue;
			$QuotFields[$this->Collection["DECLARETION"]["PRIMARY_KEY_COLUMN"]] = false;
			
			$db->ExecuteDeleter($this->Collection["DECLARETION"]["TABLE"],$PrimaryKeys,$QuotFields);
			if(isset($this->Collection["DECLARETION"]["LANGUAGE_TABLE"]) && $this->Collection["DECLARETION"]["LANGUAGE_TABLE"] != "")
			$db->ExecuteDeleter($this->Collection["DECLARETION"]["LANGUAGE_TABLE"],$PrimaryKeys,$QuotFields);
		}
	}
	
	function ParseMulitilingual($keyValue,$IsRender = true)
	{
		global $db;
		
		$m = new MultilinqualRepeater();
		$m->Init($this->Collection["DECLARETION"]["LANGUAGE_TABLE"],'tab',$db);
		$m->AddColumn($this->Collection["DECLARETION"]["PRIMARY_KEY_COLUMN"],'INTEGER','primarykey');
		foreach($this->Collection["SCHEMA"] as $key=>$value)
		{
			if(isset($value["LANG_DEPEND"]) && $value["LANG_DEPEND"] == 1)
			{
				$m->AddColumn($key,$value["DB_TYPE"],'simplecolumn',$value["HTML_RENDER"], $value["TITLE"].": ",'',(isset($value["REQUIRE"]) && $value["REQUIRE"] == 1 ? true : false),(isset($value["MAXLENGTH"]) && $value["MAXLENGTH"] == "" ? $value["MAXLENGTH"] : ""));
			}
		}
		
		if($IsRender)
		{			
			if($keyValue != "")
			{
				$m->AddPrimaryKeyValue($this->Collection["DECLARETION"]["PRIMARY_KEY_COLUMN"],$keyValue);
				$m->RenderUpdate();
			}
			else
			{
				$m->RenderInsert();
			}
			
			return $m->GetRender();
		}
		else
		{
			$m->AddPrimaryKeyValue($this->Collection["DECLARETION"]["PRIMARY_KEY_COLUMN"],$keyValue);
			$m->Update();
		}
	}
	
	function GetQuot($Type)
	{
		return $Type == "STRING" || $Type == "DATE" || $Type == "DATETIME" ? true : false;
	}

}

?>