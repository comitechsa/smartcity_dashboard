<?
defined( 'HTML_CONTROLS' ) or die( 'Direct Access to this location is not allowed.' );

class HtmlEditor
{
	function GetRender($id,$val,$editorMode = "")
	{
		global $auth,$config;
		
		$HTML_EDITOR_MODE = $editorMode != "" ? $editorMode : (isset($config["HTML_EDITOR_MODE"]) ? $config["HTML_EDITOR_MODE"] : "Private");
		
		$ret = "<textarea id='" . $id . "' name='" . $id . "' class='m_tb' rows='14' style='width:70%'>" . $val . "</textarea>";
		$ret .= "<script type='text/javascript'>var oFCKeditor = new FCKeditor( '" . $id . "' ) ; \n oFCKeditor['BasePath'] = '/gms/client_scripts/fckeditor/' ;oFCKeditor.Config['DefaultLanguage']='" .	$auth->LanguageCode . "'; \n oFCKeditor.ToolbarSet = '" . $HTML_EDITOR_MODE . "';\n oFCKeditor.ReplaceTextarea();</script>";
		return $ret;
	}
}
?>