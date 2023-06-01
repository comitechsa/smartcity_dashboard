<?
defined( 'HTML_CONTROLS' ) or die( 'Direct Access to this location is not allowed.' );

class DataGrid
{
	var $Url;
	var $Result;
	var $ColumnsAlias = array();
	var $ColumnsRenderType = array();
	var $ColumnWidth = array();
	var $RealValuesFunc = array();
	var $Header = "";
	var $PrimaryKey = "";
	var $ShowSelector = false;
	var $ShowPK = true;
	var $TotalRows = -1;
	var $Start = 1;
	var $RowPerPage = 10;
	var $UNIQUEID = "";
	var $ShowSearchFilter = false;
	var $ShowSearchTitle = "";
	var $ShowPopup = false;
	
	function DataGrid($url,$result)
	{
		$this->Url = $url;
		$this->Result = $result;
	}
	
	function GetRender($divAttr = "")
	{
		if(count($this->ColumnsAlias) <= 0)
			return "";
			
		global $db,$toolBar;
	
		$res = "\n<div class='m_dbt' " . ($divAttr != "" ? $divAttr : "") . ">";
		$res .= "\n<table align='center' width='100%' cellspacing='1' cellpadding='4' class='m_ct'>";
		
		if($this->Header != "" || $this->ShowSearchFilter) 
		{
			$res .= "\n<tr><td class='m_wt' colspan='" . (count($this->ColumnsAlias) + ($this->ShowSelector ? 1 : 0) + ($this->ShowPK ? 1 : 0) + ($this->ShowPopup ? 1 : 0)) . "'>";
			
			$res .= "<table cellpadding='0' cellspacing='0' width='100%' class='m_nb'><tr>";
			
			$res .= "<td>" . $this->Header . "</td>";
			
			if($this->ShowSearchFilter)
			{
				$res .= "<td align='right'>" . core_search . ": " . TextBox::GetRender("src_txt","","","150","text","","m_tb",true,$this->ShowSearchTitle) . "</td>";
			}
						
			$res .= "</tr></table>";
			$res .= "</td></tr>";
		}
		
		//$ShowSearchFilter
		$res .= "\n<tr class='m_hg'>";
		$res .= $this->ShowSelector ? "<td width='1'></td>" : "";
		$res .= $this->ShowPK ? "<td width='1'>PK</td>" : "";
		$res .= $this->ShowPopup ? "<td></td>" : "";
		foreach($this->ColumnsAlias as $key=>$val)
		{
			$res .= "<td " . ( $this->ColumnWidth != "" && isset($this->ColumnWidth[$key]) ? " width='" . $this->ColumnWidth[$key] . "' " : "") . ">" . $val . "</td>";
		}
		
		$res .= "\n</tr>";		
		
		$class_const = 0;
		while ($dr = $db->sql_fetchrow($this->Result))
		{
			$onclick = "onDblClick='SDbClick(\"" . $dr[$this->PrimaryKey] . "\");'";
			$res .= "\n<tr class='" . ($class_const++ % 2 == 0 ? "m_ng" : "m_ag" ) . "' " . ($this->ShowSelector ? $onclick : "") . ">";

			if($this->ShowSelector)
				$res .= "<td>" . $toolBar->GetSelector($dr[$this->PrimaryKey]) . "</td>";
			if($this->ShowPK)
				$res .= "<td>" . $dr[$this->PrimaryKey] . "</td>";
			if($this->ShowPopup)
				$res .=  "<td><img style='cursor:hand' onclick='cmINLINE(\"" . $dr[$this->PrimaryKey] . "\");' src='/gms/images/select.gif' width='20' alt='Επιλογή'/></td>";
					
			foreach($this->ColumnsAlias as $key=>$val)
			{
				if(!empty($this->ColumnsRenderType) && !empty($this->ColumnsRenderType[$key]))
				{
					if(strtolower($this->ColumnsRenderType[$key]) == "link")
						$res .= "<td>" . "<a class='l' href='" . $this->Url . "&item=" . $dr[$this->PrimaryKey] . "'>". $dr[$key] . "</a></td>";
					else if(strtolower($this->ColumnsRenderType[$key]) == "image" && !empty($dr[$key]))
						$res .= "<td><img src='" . $dr[$key] . "' border='0' vspace='1' hspace='1'></td>";
					else
						$res .= "<td>" . $dr[$key] . "</td>";
				}
				else
				{
					if(isset($this->RealValuesFunc[$key]) && $this->RealValuesFunc[$key] != "")
					{
						$RealValue = "";
						$args = array((isset($dr[$key]) ? $dr[$key] : ""),$dr[$this->PrimaryKey]);
						if (function_exists( $this->RealValuesFunc[$key] )) {
							$RealValue = call_user_func_array( $this->RealValuesFunc[$key], $args );
						}
						$res .= "<td>" . $RealValue . "</td>";
					}
					else
					{					
						$res .= "<td>" . $dr[$key] . "</td>";
					}
				}
			}
			
			$res .= "\n</tr>";
		}
		
		if($this->TotalRows != -1 && ($CurrentPaging = Paging($this->Url,$this->TotalRows,$this->RowPerPage,$this->Start,$this->UNIQUEID)) != "" )
		{
			$res .= "\n<tr><td class='m_wt' align='right' colspan='" . (count($this->ColumnsAlias) + ($this->ShowSelector ? 1 : 0) + ($this->ShowPK ? 1 : 0) + ($this->ShowPopup ? 1 : 0)) . "'>" . $CurrentPaging . "</td></tr>";			
		}
		
		$res .= "\n</table>";
		$res .= "\n</div>";
		return $res;	
	}
}
?>