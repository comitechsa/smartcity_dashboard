<?
defined( 'HTML_CONTROLS' ) or die( 'Direct Access to this location is not allowed.' );
	
class Button
{
	function GetRender($id, $Text, $CssClass = "m_b", $Attributes = "")
	{
		return "<input type='submit' class='$CssClass' id='$id' name='$id' value='$Text' $Attributes />";
	}
}
?>