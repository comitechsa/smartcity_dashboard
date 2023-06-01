<?
defined( 'HTML_CONTROLS' ) or die( 'Direct Access to this location is not allowed.' );

class Label
{
	function GetRender($Text = "", $CssClass = "m_n")
	{
		return "<span class='$CssClass'>$Text</span>";
	}
}
?>