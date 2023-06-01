<?
defined( 'HTML_CONTROLS' ) or die( 'Direct Access to this location is not allowed.' );

class CheckBox
{
	function GetRender($id, $Checked = false)
	{
		if($Checked == "") {
			$Checked = isset($_POST[$Checked]) && !empty($_POST[$Checked]) && $_POST[$Checked] == "1" ? true : false;
		}		
		return "<input type='checkbox' " . ( $Checked ? " checked " : "") . " id='$id' name='$id' value='1' />";
	}	
}
?>