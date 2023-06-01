<?
defined( 'HTML_CONTROLS' ) or die( 'Direct Access to this location is not allowed.' );

class Select
{
	function GetDbRender($id, $query, $key, $value, $Selected = "", $ShowNullRow = false, $Width,$Mode = "", $CssClass = "m_tb",$SelectNullLabel = "", $Properties ="", $sel_array =array())
	{
		global $config,$db;
		
		$SelectNullLabel = $SelectNullLabel != "" ? $SelectNullLabel : core_select;
		
		$result = $db->sql_query($query);
		$ret = "<select id='$id' name='$id' class='$CssClass' " . ($Width != "" ? " style='width:$Width' " : "") ." " . ($Properties != "" ? $Properties : "") . ">";
		
		if($ShowNullRow)
			$ret .= "<option value=''>" . $SelectNullLabel . "</option>";
		
		if($Selected == "") {
			$Selected = isset($_POST[$id]) && !empty($_POST[$id]) ? $_POST[$id] : "";
		}
		
		while($dr = $db->sql_fetchrow($result)) {
			$ret .= "<option value='" . $dr[$key] . "'" . ($dr[$key] == $Selected || in_array($dr[$key],$sel_array)? " selected " : "") . ">" . $dr[$value] . "</option>";
		}
		
		$ret .= "</select>";
		$db->sql_freeresult($result);
		return $ret;
	}
	
	function GetEnumRender($id, $Collection, $Selected = "", $ShowNullRow = false, $Width = "90%",$Mode = "", $CssClass = "m_tb", $Properties ="", $NullValue = "")
	{
		global $config;
		
		$ret = "<select id='$id' name='$id' class='$CssClass' " . ($Width != "" ? " style='width:$Width' " : "")  . " " . ($Properties != "" ? $Properties : "") . ">";
		
		if($ShowNullRow) $ret .= "<option value=''>" . core_select . "</option>";
		else $ret .= "<option value=''>" . $NullValue . "</option>";
		
		if($Selected == "") {
			$Selected = isset($_POST[$id]) && !empty($_POST[$id]) ? $_POST[$id] : "";
		}
		
		foreach($Collection as $key=>$value)
		{
			$ret .= "<option value='$key' " . ($key == $Selected ? " selected " : "") . ">$value</option>";
		}
		
		$ret .= "</select>";
		return $ret;		
	}
}
?>