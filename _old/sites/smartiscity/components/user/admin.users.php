<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
$BaseUrl = "index.php?com=users";

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
		//$Filter = isset($_POST['uf']) && !empty($_POST['uf']) ? " user_fullname like '%" . $_POST['uf'] . "%'" : "";
		$currentGmsCM = new GmsCM($usersComponentManage);
		$args = array(
			"Order" => " user_auth desc, user_name  "
			,"Filter" => " user_auth = 'Administrator' "
		);
		$currentGmsCM->RenderDisplay($BaseUrl,$args);
	}
}
?>

