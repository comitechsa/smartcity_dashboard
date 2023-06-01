<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

$BaseUrl = "index.php?com=pageone";
$Response = "";
if($auth->UserType == "Administrator" && isset($_GET["item"]))
{
	$editor = isset($_GET["editor"]) ? $_GET["editor"] : "";
	$pk = $_GET["item"];
	$PrimaryKeys = array();
	$QuotFields = array();
	$PrimaryKeys["page_id"] = $pk;
	$QuotFields["page_id"] = false;
	$dr = $db->RowSelector("pages",$PrimaryKeys,$QuotFields);
	if(isset($dr["is_system"]) && $dr["is_system"] == 1)
	{
		//Redirect("index.php");
	}
	
	$m = new MultilinqualRepeater();
	$m->Init('pagestolanguages','tab',$db);
	$m->AddColumn('page_id','INTEGER','primarykey');
	$m->AddColumn('page_title','STRING','simplecolumn','singleline', pages_title.": ",'',true,'250');
	if($editor == "1")
	{
		$m->AddColumn('content','STRING','simplecolumn','singleline', pages_content.": ",'',true,'');
	}
	else
	{
		$m->AddColumn('content','STRING','simplecolumn','htmleditor', pages_content.": ",'',true,'','','');
		//$m->AddColumn('meta_keys','STRING','simplecolumn','singleline',pages_metaKeys.": ",'',false,'250');
		//$m->AddColumn('meta_desc','STRING','simplecolumn','singleline',pages_metaDescription.": ",'', false,'250');
	}
	
	if($toolBar->CurrentCommand() == "SAVE")
	{
		$pk = $_GET["item"];
		$PrimaryKeys = array();
		$QuotFields = array();
		$PrimaryKeys["page_id"] = $pk;
		$QuotFields["page_id"] = false;
		$dr = $db->RowSelector("pages",$PrimaryKeys,$QuotFields);
		
		if(!isset($dr["page_id"]))
		{
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			$Collector["page_id"] = $pk;
			$QuotFields["page_id"] = false;
			
			$Collector["is_system"] = "0";
			$QuotFields["is_system"] = false;		
			
			$Collector["url"] = "";
			$QuotFields["url"] = true;
			
			$Collector["is_valid"] = 1;
			$QuotFields["is_valid"] = false;
			
			$db->ExecuteUpdater("pages",$PrimaryKeys,$Collector,$QuotFields);
		}
			
		$m->AddPrimaryKeyValue("page_id",$pk);
		$m->Update();
		
		$messages->addMessage(core_recordSaved);
		Redirect($BaseUrl . "&item=" . $pk . ($editor != "" ?  "&editor=$editor" : ""));
	}
	else
	{
		$PrimaryKeys = array();
		$QuotFields = array();
		$PrimaryKeys["page_id"] = $_GET["item"];
		$QuotFields["page_id"] = false;
		$PrimaryKeys["language_id"] = $auth->LanguageID;
		$QuotFields["language_id"] = false;
		$dr = $db->RowSelector("pagestolanguages",$PrimaryKeys,$QuotFields);

		$Response .= "<div class='m_dbt'>";
		$Response .= "<table align='center' width='100%' cellspacing='1' cellpadding='4' class='m_ct'>";
		$Response .= "<tr><td class='m_wt'>" . (isset($dr) ? $dr["page_title"] : "") . " :: " . core_insertUpdate . " ::</td></tr>";
		
		$Response .= "<tr><td class='m_ng'>";
		$m->AddPrimaryKeyValue("page_id",$_GET["item"]);
		$m->RenderUpdate();
		$Response .= $m->GetRender();
		$Response .= "</td></tr>";
		
		$Response .= "</table>";
		$Response .= "</div><br>";
		
		$toolBar->AddCommand("SAVE","save.gif",core_save,true);
	}
}

echo $Response;
?>