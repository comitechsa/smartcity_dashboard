<?
defined( 'HTML_CONTROLS' ) or die( 'Direct Access to this location is not allowed.' );

class Gmap
{	
	function GetRender($id, $value = "", $cssClass = "m_tb", $width = "70%")
	{
		if($value == "") {
			$value = isset($_POST[$id]) && !empty($_POST[$id]) ? $_POST[$id] : "";
		}
		
		return "<input " . ( $width != "" ? "style='width:" . $width  . "'" : "") . " type='text' class='$cssClass' id='$id' name='$id' value='$value'/><img align='top' hspace='4' style='cursor:pointer' onclick='return showMap(\"$id\")' src='/gms/images/prev.gif' border='0'>";
	}
	
	function GetRenderV3($id, $value = "", $cssClass = "m_tb", $width = "70%")
	{
		if($value == "") { $value = isset($_POST[$id]) && !empty($_POST[$id]) ? $_POST[$id] : ""; }
		
		return "<input " . ( $width != "" ? "style='width:" . $width  . "'" : "") . " type='text' class='$cssClass' id='$id' name='$id' value='$value'/><img align='top' hspace='4' style='cursor:pointer' onclick='return showMapV3(\"$id\")' src='/gms/images/prev.gif' border='0'>";
		
	}
}

?>