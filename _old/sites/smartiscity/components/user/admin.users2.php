<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
$BaseUrl = "index.php?com=users2";

if($auth->UserType == "Administrator")
{
	$ShowEdit = false;
	$__error = "";
	
	if($toolBar->CurrentCommand() == "DELETE")
	{
		if($__error == "")
		{		
			$__id = $toolBar->CurrentRecord();
			$currentGmsCM = new GmsCM($usersComponentManage,$__id);
			$currentGmsCM->DeleteRow();		
			Redirect($BaseUrl);
		}
	}
	else if($toolBar->CurrentCommand() == "ADDRESES")
	{
		Redirect("index.php?com=uaddresses&item=" . $toolBar->CurrentRecord());
	}
	else if($toolBar->CurrentCommand() == "ORDERS")
	{
		$dr = $db->RowSelectorQuery("SELECT * FROM users WHERE user_id=" . $toolBar->CurrentRecord());
		Redirect("index.php?com=orders&client=" . $dr["user_name"]);
	}
	else if($toolBar->CurrentCommand() == "SAVE")
	{
		$currentGmsCM = new GmsCM($usersComponentManage);
		
		if($currentGmsCM->IsUpdateMode())
			$sql = "SELECT * FROM users WHERE user_name='" . $_POST["user_name"] . "' AND user_id != '" . $currentGmsCM->GetPostPK() . "'";
		else
			$sql = "SELECT * FROM users WHERE user_name='" . $_POST["user_name"] . "'";
		
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);		
		
		if(isset($row["user_name"])) {
			$__error = users_userNameExist;
			$ShowEdit = true;
		}
		
		if($currentGmsCM->IsUpdateMode())
			$sql = "SELECT * FROM users WHERE email='" . $_POST["email"] . "' AND user_id != '" . $currentGmsCM->GetPostPK() . "'";
		else
			$sql = "SELECT * FROM users WHERE email='" . $_POST["email"] . "'";
		
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);		
		
		if(isset($row["user_name"])) {
			$__error = users_userEmailExist;
			$ShowEdit = true;
		}
		
		if($__error == "")
		{
			$currentGmsCM->UpdateUI();
			Redirect($BaseUrl);
		}
	}
	
	if($__error != "")
	{
		echo "<div class='m_error'>$__error</div><br>";	
	}
	
	if($toolBar->CurrentCommand() == "NEWRECORD" || $toolBar->CurrentRecord() != "" || $ShowEdit)
	{
		$__id = "";
		if($toolBar->CurrentCommand() != "NEWRECORD")
		{
			$__id = $toolBar->CurrentRecord();
		}
	
		$currentGmsCM = new GmsCM($usersComponentManage,$__id);
		$currentGmsCM->TableColumns = 1;
		$currentGmsCM->RenderIU();
		
	}
	else
	{

		require_once(dirname(__FILE__) . "/admin.user2-search.php");
		
		$toolBar->AddCommand("ADDRESES","content.png",user_addresses,0,1);
		$toolBar->AddCommand("ORDERS","content.png",user_orders,0,1);
		
		$currentGmsCM = new GmsCM($usersComponentManage);
		$args = array(
			"Order" => " date_insert desc, user_fullname"
			,"Filter" => " user_auth = 'Register'  " . ($src_Filter != "" ? " AND " . $src_Filter : "")
		);
		$currentGmsCM->RenderDisplay($BaseUrl,$args);
	}
}
?>

