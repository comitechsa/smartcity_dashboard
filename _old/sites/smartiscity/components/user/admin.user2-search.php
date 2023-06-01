<?
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
if($auth->UserType == "Administrator")
{
	$Criteria["name"] = "";
	$Criteria["user_name"] = "";	
	
	if(isset($_POST["search"]) && $_POST["search"] != "")
	{
		$_GET["prstart"] = 0;
		$Criteria["name"] = $_POST["name"];
		$Criteria["user_name"] = $_POST["user_name"];
	}
	else if(isset($_SESSION["Criteria_Customers"]))
	{
		$Criteria["name"] = $_SESSION["Criteria_Customers"]["name"];
		$Criteria["user_name"] = $_SESSION["Criteria_Customers"]["user_name"];
	}
		
	$_SESSION["Criteria_Customers"] = $Criteria;
	?>
	
	<table border='0' width='80%' cellspacing='1' cellpadding='4' class='m_ct' align="center">
		<tr>
			<td class='m_ng' width="20%"><?=users_fullname?>:<br /><?=TextBox::GetRender("name",$Criteria["name"], "255");?></td>
            <td class='m_ng' width="20%"><?=users_userName?>:<br /><?=TextBox::GetRender("user_name",$Criteria["user_name"], "255");?></td>
			<td align="right" class='m_ng' ><input type='submit' value='Αναζήτηση' name="search" class="m_b"/></td>
		</tr>
	</table>
	<br />
	<?
	$src_Filter = ""; //AND user_id in (select user_id from ecm_orders) 
	
	if($Criteria["name"] != "") $src_Filter .= " AND user_fullname LIKE '%" . $Criteria["name"] . "%'";
	if($Criteria["user_name"] != "") $src_Filter .= " AND user_name LIKE '%" . $Criteria["user_name"] . "%'";
	
	if($src_Filter != "") $src_Filter = substr($src_Filter,4);
}
?>