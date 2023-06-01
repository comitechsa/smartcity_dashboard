<?
defined( 'HTML_CONTROLS' ) or die( 'Direct Access to this location is not allowed.' );

class pUpload
{
	function GetRender($id, $val, $width = "70%")
	{
		global $config;
		return "<input type='file' " . ( $width != "" ? "style='width:" . $width  . "'" : "") . " id='$id' name='$id' value='$val' class='m_tb'>" . ($val != "" ? "<img align='top' hspace='1' src='/gms/images/prev.gif' style='cursor:pointer' onclick='window.open(\"index.php?attach=true&id=$val\");' alt='Show Photo'>" : "");
		//<img align='top' hspace='1' src='index.php?attach=true&id=$val'>";
	}
}

?>