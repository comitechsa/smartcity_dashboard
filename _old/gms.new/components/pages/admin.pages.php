<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?

//if(isset($_GET["geogr"]) || isset($_SESSION["geogr"]) )
//{
//	$_SESSION["geogr"] = "geogr";
//}
//else
//{
//	Redirect("index.php");
//}

$BaseUrl = "index.php?com=pages";
$Content = "";
if($auth->UserType == "Administrator")
{
	$m = new MultilinqualRepeater();
	$m->Init('pagestolanguages','tab',$db);
	$m->AddColumn('page_id','INTEGER','primarykey');
	$m->AddColumn('page_title','STRING','simplecolumn','singleline', pages_title.": ",'',true,'250');
	$m->AddColumn('content','STRING','simplecolumn','htmleditor', pages_content.": ",'',false,'','','');
	$m->AddColumn('meta_keys','STRING','simplecolumn','singleline',pages_metaKeys.": ",'',false,'250');
	$m->AddColumn('meta_desc','STRING','simplecolumn','singleline',pages_metaDescription.": ",'', false,'250');
	
	if($toolBar->CurrentCommand() == "BACK")
	{
		Redirect($BaseUrl);
	}
	else if($toolBar->CurrentCommand() == "DELETE")
	{
		$code = isset($_POST["__Record"]) ? $_POST["__Record"] : "";
		
		$PrimaryKeys = array();
		$QuotFields = array();
		$PrimaryKeys["parent_id"] = $code ;
		$QuotFields["parent_id"] = false;
		$dr = $db->RowSelector("pages",$PrimaryKeys,$QuotFields);
		
		if(!empty($dr))
		{
			$Content .= "<br><center><span class='m_error'>" . pages_errorMsg . "</span></center>";
			$toolBar->AddCommand("BACK","back.gif",$rm_back);
		}
		else
		{
			$PrimaryKeys = array();
			$QuotFields = array();
			$PrimaryKeys["page_id"] = $code;
			$QuotFields["page_id"] = false;
			
			$db->ExecuteDeleter("pages",$PrimaryKeys,$QuotFields);
			$db->ExecuteDeleter("pagestolanguages",$PrimaryKeys,$QuotFields);
			Redirect($BaseUrl);
		}		
	}
	else if($toolBar->CurrentCommand() == "SAVE")
	{
		$id = "";
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		
		if(isset($_POST["re_id"]) && $_POST["re_id"]  != "" )
		{
			$id = $_POST["re_id"] ;
			$PrimaryKeys["page_id"] = $id;
			$QuotFields["page_id"] = false;
		} else {
		
			if(isset($_POST["parent_id"]) && $_POST["parent_id"]  != "" && $_POST["parent_id"]  != "0")
			{
				$Collector["parent_id"] = $_POST["parent_id"];
				$QuotFields["parent_id"] = false;
			}
		}
				
		$Collector["is_system"] = isset($_POST["is_system"]) && $_POST["is_system"] != "" ? "1" : "0";
		$QuotFields["is_system"] = false;
		
		$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] != "" ? "1" : "0";
		$QuotFields["is_valid"] = false;
		
		$Collector["priority"] = isset($_POST["priority"]) && $_POST["priority"] != "" ? $_POST["priority"] : "0";
		$QuotFields["priority"] = false;
		
		$Collector["url"] = $_POST["url"];
		$QuotFields["url"] = true;
		
		$db->ExecuteUpdater("pages",$PrimaryKeys,$Collector,$QuotFields);
		if($id == "")
		{
			$id = $db->sql_nextid();
		}
		
		$m->AddPrimaryKeyValue("page_id",$id);
		$m->Update();
		
		if( isset($_GET["id"]))
		{
			Redirect($BaseUrl . "&id=" . $_GET["id"]);
		}
		else
		{
			Redirect($BaseUrl);
		}
	}
	else if(isset($_POST["__Record"]) || isset($_GET["id"]) || $toolBar->CurrentCommand() == "NEWRECORD")
	{
		$dr = array();
		$id = "";
		if($toolBar->CurrentCommand() != "NEWRECORD")
		{
			$id = isset($_POST["__Record"]) ? $_POST["__Record"] : $_GET["id"];
			if($id == "0") $id = "1";
			$PrimaryKeys = array();
			$QuotFields = array();
			$PrimaryKeys["page_id"] = $id;
			$QuotFields["page_id"] = false;
			$dr = $db->RowSelector("pages",$PrimaryKeys,$QuotFields);
			$m->AddPrimaryKeyValue("page_id",$id);
			$m->RenderUpdate();
			$Content .= "<input type='hidden' name='re_id' id='re_id' value='" . $id  . "'>";
		}
		else
		{
			$m->RenderInsert();
			if(isset($_POST["__Record"]))
				$Content .= "<input type='hidden' name='parent_id' id='parent_id' value='" . $_POST["__Record"]  . "'>";
		}	
		
		$Content .= "<div class='m_dbt'>";
		$Content .= "<table align='center' width='100%' cellspacing='1' cellpadding='4' class='m_ct'>";
		$Content .= "<tr><td class='m_wt' colspan='2'>" . pages_pages . " :: " . core_insertUpdate . " ::</td></tr>";
		
		$Content .= "<tr class='m_ng'><td align='right' width='30%'>" . pages_isActive . ":</td><td><input type='checkbox' id='is_valid' name='is_valid' " .  (isset($dr["is_valid"]) && $dr["is_valid"] == 1? " checked "  : "") . "></td></tr>";
		
		$Content .= "<tr class='m_ng'><td align='right'>" . pages_isSystem . ":</td><td><input type='checkbox' id='is_system' name='is_system' " .  (isset($dr["is_system"]) && $dr["is_system"] == 1? " checked "  : "") . "></td></tr>";
		
		$Content .= "<tr class='m_ng'><td align='right'>" . pages_priority . ":</td><td><input type='text' id='priority' name='priority' class='m_tb' value='" .  (isset($dr["priority"]) ? $dr["priority"] : "0") . "'></td></tr>";
		
		$Content .= "<tr class='m_ng'><td align='right'>" . pages_link . ":</td><td><input type='text' style='width:90%' id='url' name='url' class='m_tb' value='" .  (isset($dr["url"]) ? $dr["url"] : "") . "'></td></tr>";
		
		$Content .= "<tr><td class='m_ng' colspan='2'>";		
		$Content .= $m->GetRender();
		$Content .= "</td></tr>";
		
		
		$Content .= "</table>";
		$Content .= "</div>";
		
		$toolBar->AddCommand("SAVE","save.gif",core_save,true);
		$toolBar->AddCommand("BACK","back.gif",core_cancel);
	}
	else
	{
		$Content .= "<div class='m_dbt'>";
		$Content .= "<table align='center' width='100%' cellspacing='1' cellpadding='4' class='m_ct'>";
		$Content .= "<tr><td class='m_wt'>" . pages_pages . "</td></tr>";
		$Content .= "<tr class='m_ag'><td>";
		$Content .= "\n\t\t<script language='javascript' type='text/javascript'>";
		$Content .= "\n\t\t<!--";
		$Content .= "\n\t\td = new dTree('d','Radio','__Record');";
		$Content .= "\n\t\td.add('0','-1','" . pages_root . "');";
		
		$query = "select * from pages inner join pagestolanguages on pages.page_id=pagestolanguages.page_id WHERE language_id=" . $auth->LanguageID . " ORDER BY priority, page_title";
		$result = $db->sql_query($query);
	
		while ($dr = $db->sql_fetchrow($result))
		{
			$Content .= "\n\t\td.add(" . $dr["page_id"] . "," . (isset($dr["parent_id"]) && !empty($dr["parent_id"]) ? $dr["parent_id"]  : "0") . ",'" . str_replace("'","",$dr["page_title"]) . " <i>(index.php?com=pages&item=" . $dr["page_id"] . ")</i>','','index.php?com=page&item=" .  $dr["page_id"] . "');";
		}
		
		$db->sql_freeresult($result);
		
		$Content .= "\n\t\tdocument.write(d);";
		$Content .= "\n\t\t//-->";
		$Content .= "\n\t\t</script>";
		$Content .= "\n\t\t</div>";
	
		$Content .= "</td></tr>";
		$Content .= "</table>";
		$Content .= "</div>";
		

		$toolBar->AddCommand("NEWRECORD","new.gif",core_newRecord);
		$toolBar->AddCommand("EDIT","edit.gif",core_edit,0,1);
		$toolBar->AddCommand("DELETE","delete.gif",core_delete,0,1,core_deleteConfirm);
	}
}

echo $Content;
?>

