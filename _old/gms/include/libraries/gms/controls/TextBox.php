<?
defined( 'HTML_CONTROLS' ) or die( 'Direct Access to this location is not allowed.' );

class TextBox
{
	function GetRender($id = "", $Value = "", $MaxLength = "",  $width = "70%", $Type = "text", $rows = "14", $CssClass = "m_tb", $autoPostBack = false, $title = "")
	{
		if($Value == "") {
			$Value = isset($_POST[$id]) && !empty($_POST[$id]) ? $_POST[$id] : "";
		}
		
		if($Type == "TextArea")
		{
			return "<textarea " . ( $width != "" ? "style='width:" . $width . "'" : "") . ( $rows != "" ? " rows='" . $rows . "'" : "")  . " class='$CssClass' id='$id' name='$id'>$Value</textarea>";
		}
		else
		{
			return "<input type='$Type' " . ( $width != "" ? "style='width:" . $width . "' " : "") . ( $MaxLength != "" ? "maxlength='" . $MaxLength  . "' " : "") . " class='$CssClass' id='$id' name='$id' value=\"$Value\"" . ($autoPostBack ? "  onkeydown='__KeyDown(\"__PostBack__\",0); ' " : "") . ($title != "" ? " title='" . $title . "' " : "") . "/>";
		}
	}
	
	function GetSimpleRender($id = "", $Value = "", $MaxLength = "", $attributes = "",  $width = "70%")
	{
		if($Value == "") { $Value = isset($_POST[$id]) && !empty($_POST[$id]) ? $_POST[$id] : "";}
		
		return "<input type='text' " . ( $width != "" ? "style='width:" . $width . "'" : "") . ( $MaxLength != "" ? "maxlength='" . $MaxLength  . "'" : "") . " class='m_tb' id='$id' name='$id' value=\"$Value\" " . $attributes. "/>";
	}
}
?>