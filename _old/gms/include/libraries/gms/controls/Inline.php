<?
defined( 'HTML_CONTROLS' ) or die( 'Direct Access to this location is not allowed.' );

class Inline
{
	function GetRender($key, $popupUrl, $SelectedLabels="", $SelectedValues= "", $InlineAdmin = false)
	{
		$__ID = "popup_" . $key;
		$PopupCaller = "ShowPopup(this,'" . $popupUrl . "');";
		$PopupHtml = "";		
		
		$PopupHtml .= "<div id='" . $key . "_div'>&nbsp;</div>";
		$PopupHtml .= "<script language='javascript'>";
		$PopupHtml .= $__ID . " = new PopupAdmin(\"" . $key .  "\",\"" . $PopupCaller . "\",\"" . $SelectedValues . "\",\"" . $SelectedLabels . "\"," . ($InlineAdmin?"1":"0") . ");";
		$PopupHtml .= "</script>";			
		return $PopupHtml;
	}
}

?>