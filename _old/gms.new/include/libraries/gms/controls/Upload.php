<?
defined( 'HTML_CONTROLS' ) or die( 'Direct Access to this location is not allowed.' );

class Upload
{
	function GetRender($id,$val,$width = "70%")
	{
		global $config;
		$PathGet = $config["siteurl"] . "gms/client_scripts/fckeditor/editor/filemanager/browser.html?Connector=" . $config["siteurl"] . "gms/filemanager/connector.php";
		return "<input type='text' " . ( $width != "" ? "style='width:" . $width  . "'" : "") . " id='$id' name='$id' value='$val' class='m_tb'><img align='absmiddle' hspace='4' src='/gms/images/upload.png' style='cursor:pointer' onclick='GetFile(\"$id\",\"$PathGet\")'><img align='absmiddle' hspace='1' src='/gms/images/prev.gif' style='cursor:pointer' onclick='PreviewFile(\"$id\")'>";
	}
}

?>