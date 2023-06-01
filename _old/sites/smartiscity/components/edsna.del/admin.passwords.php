<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
$BaseUrl = "index.php?com=passwords";

if($auth->UserType == "Administrator")
{
	$ShowEdit = false;
	$__error = "";
	
	if($toolBar->CurrentCommand() == "DELETE")
	{
		if($__error == "")
		{		
			$__id = $toolBar->CurrentRecord();
			$currentGmsCM = new GmsCM($passesComponentManage,$__id);
			$currentGmsCM->DeleteRow();		
			Redirect($BaseUrl);
		}
	}
	else if($toolBar->CurrentCommand() == "SAVE")
	{
		$currentGmsCM = new GmsCM($passesComponentManage);
		
		if($currentGmsCM->IsUpdateMode())
			$sql = "SELECT * FROM passwords WHERE room='" . $_POST["room"] . "' AND id != '" . $currentGmsCM->GetPostPK() . "'";
		else
			$sql = "SELECT * FROM passwords WHERE room='" . $_POST["room"] . "'";
		
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);		
		
		if(isset($row["room"])) {
			$__error = "Έχει ήδη οριστεί κωδικός για τον χώρο";
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
	
		$currentGmsCM = new GmsCM($passesComponentManage,$__id);
		$currentGmsCM->TableColumns = 1;
		$currentGmsCM->RenderIU();
		
	}
	else
	{
		//$Filter = isset($_POST['uf']) && !empty($_POST['uf']) ? " user_fullname like '%" . $_POST['uf'] . "%'" : "";
		$currentGmsCM = new GmsCM($passesComponentManage);
		$args = array(
			"Order" => " room  "
			//,"Filter" => " user_auth = 'Administrator' "
		);
		$currentGmsCM->RenderDisplay($BaseUrl,$args);
	}
}
?>

