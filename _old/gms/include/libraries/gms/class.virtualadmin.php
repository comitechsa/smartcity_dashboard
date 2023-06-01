<?
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

class VirtualAdmin
{
	var $dr;
	var $TableWidth = "85%";
	var $ColumnsAlias = array();
	var $ColumnsRenderType = array();
	var $ColumnsValidator = array();
	var $ColumnsFormRenderName = array();
	var $HiddenArea = "";
	var $MultiligualRender = "";
	var $ModeDisplay = "";
	var $MetaEngineFields;
	var $MetaEngineDataRow;
	
	function GetRender()
	{
		$MultiSet = $this->MultiligualRender != "" && $this->ModeDisplay != "UP_DOWN" ? true : false;
		
		if(count($this->ColumnsAlias) <= 0 && !$MultiSet)
			return "";
			
			
		$res = "<table align='center' width='" . $this->TableWidth . "' cellspacing='1' cellpadding='3'>";
		$res .= "<tr>";
			
		if($MultiSet)
			$res .= "<td valign='top'>". $this->MultiligualRender . "</td>";
		
		$res .= "<td width='" . ($MultiSet != "" ? "200" : "100%") . "' height='100%' valign='top'>";
		
		$res .= "<table align='center' width='100%' height='100%' cellspacing='1' cellpadding='0' class='tab_border'><tr><td>";
		
		$res .= "<table align='center' width='100%' height='100%' cellspacing='1' cellpadding='3' class='tab_content m_n'>";
		
		$res .= "<tr><td class='tab_grad_head' " . ( $MultiSet != "" ? "" : " colspan='2' " ) . ">" . _CONTENT_PROPERTIES . "</td></tr>";
		
		$res .= $MultiSet != "" ? "<tr><td>" : "";
		
		$Seperator = $MultiSet != "" ? ":<br>" : "";
		
		$WidthHasInited = false;
		foreach($this->ColumnsAlias as $key=>$val)
		{
			$Id_Name = isset($this->ColumnsFormRenderName[$key]) ? $this->ColumnsFormRenderName[$key] : $key;
			
			$RenderTxt = "";
			if(isset($this->ColumnsRenderType[$key]))
			{
				if($this->ColumnsRenderType[$key] == "DatePicker")
				{
					$newdp = new DatePicker($Id_Name,( isset($this->dr) && isset($this->dr[$key]) ?  $this->dr[$key] : ""));
					$RenderTxt = $newdp->GetRender();
				}
				else if($this->ColumnsRenderType[$key] == "TextBox")
				{
					$tb = new TextBox($Id_Name, ( isset($this->dr) && isset($this->dr[$key]) ?  $this->dr[$key] : ""));
					$RenderTxt = $tb->GetRender();
				}
				else if($this->ColumnsRenderType[$key] == "Label")
				{
					$lb = new Label(isset($this->dr) && isset($this->dr[$key]) ?  $this->dr[$key] : "","m_nb");
					$RenderTxt = $lb->GetRender();
				}
				else if($this->ColumnsRenderType[$key] == "Upload")
				{
					$Up = new Upload($Id_Name,isset($this->dr) && isset($this->dr[$key]) ?  $this->dr[$key] : "");
					$RenderTxt = $Up->GetRender();
				}
				else
				{
					$RenderTxt = $this->ColumnsRenderType[$key];
				}
			}
			else
			{
				$lb = new Label(isset($this->dr) && isset($this->dr[$key]) ?  $this->dr[$key] : "");
				$RenderTxt = $lb->GetRender();
			}
			
			if($Seperator != "")
				$res .= $val . $Seperator . $RenderTxt . "<hr>" ;
			else
				$res .= "<tr><td align='right' " . ($WidthHasInited ? "" : "width='45%'") . ">" . $val . ":</td><td>" . $RenderTxt . "</td></tr>";
				
			$WidthHasInited = true;
		}
		
		//parse meta fields
		if(!empty($this->MetaEngineFields))
		{
			foreach($this->MetaEngineFields as $key=>$val)
			{
				if(!empty($this->MetaEngineDataRow))
				{
					$res .= "<input type='hidden' name='MetaEditMode' id='MetaEditMode' value='true' />";
				}
					
				$tb = new TextBox("meta_" . $key,( isset($this->MetaEngineDataRow) && isset($this->MetaEngineDataRow[$key]) ? $this->MetaEngineDataRow[$key] : ""));
				$RenderTxt = $tb->GetRender();
				
				if($Seperator != "")
					$res .= $val . $Seperator . $RenderTxt . "<hr>" ;
				else
					$res .= "<tr><td align='right'>" . $val . ":</td><td>" . $RenderTxt . "</td></tr>";
				
			}
		}	
	
		$res .= $MultiSet != "" ? "</td></tr>" : "";
		if($this->ModeDisplay == "UP_DOWN")
		{
			$res .= "<tr><td width='100%' colspan='2'>" . $this->MultiligualRender . "</td></tr>";
		}
		$res .= $MultiSet != "" ? "<tr><td height='100%'>&nbsp;</td></tr>" : "";
		$res .= "</table></td></tr></table>";
		$res .= "</td></tr>";
		
		$res .= "</table>";
		$res .= $this->HiddenArea;
		
		global $validator;
		foreach($this->ColumnsValidator as $key=>$val)
		{
			$Id_Name = isset($this->ColumnsFormRenderName[$key]) ? $this->ColumnsFormRenderName[$key] : $key;
			
			$validator->AddTagValidator($Id_Name,1,$val);
		}
		
		return $res;
	}
}
?>