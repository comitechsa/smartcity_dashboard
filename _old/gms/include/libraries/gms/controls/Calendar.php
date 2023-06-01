<?
defined( 'HTML_CONTROLS' ) or die( 'Direct Access to this location is not allowed.' );

class Calendar
{	
	function GetRender($id, $value = "", $cssClass = "m_tb", $width = "70%", $autoPostBack = false)
	{
		if($value == "") {
			$value = isset($_POST[$id]) && !empty($_POST[$id]) ? $_POST[$id] : "";
		}
		
		return "<input " . ( $width != "" ? "style='width:" . $width  . "'" : "") . " type='text' " . ($autoPostBack ? " onkeydown='__KeyDown(\"__PostBack__\",0);' " : "") . " class='$cssClass' id='$id' name='$id' value='$value' /><img align='absmiddle' hspace='4' style='cursor:pointer' onclick='return showCalendar(\"$id\")' src='/gms/client_scripts/calendar/images/cal.gif' width='16' height='16' border='0'>";
	}
}

?>