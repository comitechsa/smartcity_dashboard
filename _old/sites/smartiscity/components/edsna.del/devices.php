<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
	include($config["physicalPath"]."languages/".$auth->LanguageCode.".php");
?>
<?

//permissions
	$FLAG_DEVICES = 1;
	$FLAG_CAMPAINS = 2;
	$FLAG_PASSWORDS = 4;
	$FLAG_PRODUCTS = 8;
	$FLAG_CATEGORIES = 16;
	$FLAG_ADS = 32;
	$FLAG_CREDITS = 64;
	$FLAG_RESELLERS = 128;
	$FLAG_SURVEYS = 256;
	$FLAG_FRIENDS = 512;
	$FLAG_RATING = 1024;
	$FLAG_STATS = 2048;
	$permissions = (intval($auth->UserRow['access'])>0?$auth->UserRow['access']:0);

	if (!($permissions & $FLAG_DEVICES)) {
		$messages->addMessage("INVALID!!!");
		Redirect("index.php");
	}
//end permissions


$config["navigation"] = devicesTitle;
$nav = devicesTitle;
$BaseUrl = "index.php?com=devices";


	//$command=array();
//$command=explode("&",$_POST["Command"]);										


$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");

if(isset($_REQUEST["Command"]))
{
	if($_POST["Command"] == "DELETELOGO")
	{
		$PrimaryKeys["id"] = intval($_GET["item"]);
		$QuotFields["id"] = true;
		
		$Collector["files"] = "";
		$QuotFields["files"] = true;
		
		$db->ExecuteUpdater("devices",$PrimaryKeys,$Collector,$QuotFields);		
		$messages->addMessage("SAVED!!!");
		Redirect("index.php?com=devices");
	}
	if($_POST["Command"] == "DELETEBACKGROUND")
	{
		$PrimaryKeys["id"] = intval($_GET["item"]);
		$QuotFields["id"] = true;
		
		$Collector["background"] = "";
		$QuotFields["background"] = true;
		
		$db->ExecuteUpdater("devices",$PrimaryKeys,$Collector,$QuotFields);		
		$messages->addMessage("SAVED!!!");
		Redirect("index.php?com=devices");
	}
	if($_POST["Command"] == "SAVE")
	{
		//file upload
		$myfile=basename($_FILES["fileToUpload"]["name"]);
		$targetFile="";
		if($auth->UserType == "Administrator") {  
			$userID=$_POST['user_id'];
		} else {
			$userID=$auth->UserRow['user_id'];
		}

		if(isset($myfile) && $myfile!="") {
			$targetDir = $config["physicalPath"] . "gallery/customer_logo/".$userID."/";
			$uploadOk = 1;
			$imageFileType = pathinfo($myfile,PATHINFO_EXTENSION);
			//$targetFile = $targetDir.$auth->UserId.".".$imageFileType;
			$targetFile = $targetDir.$_GET['item'].".".$imageFileType;
			//echo $targetFile;
			//exit;
			$targetFiletoSave = $_GET['item'].".".$imageFileType;
			// Check if image file is a actual image or fake image
				$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
				if($check !== false) {
					echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
				} else {
					echo "File is not an image.";
					$uploadOk = 0;
				}

			 // Check file size
			if ($_FILES["fileToUpload"]["size"] > 500000) {
				echo "Sorry, your file is too large. Our limit is 500kb";
				$uploadOk = 0;
			}

			// Create target dir
			if (!file_exists($targetDir)) {
				@mkdir($targetDir);
			}

			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "JPG" && $imageFileType != "JPEG" && $imageFileType != "GIF" && $imageFileType != "PNG") {
				echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				$uploadOk = 0;
			}
			// Check if file already exists
			//if (file_exists($target_file)) {
			//	echo "Sorry, file already exists.";
			//	$uploadOk = 0;
			//}
			
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded.";
			// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
					echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
				} else {
					echo "Sorry, there was an error uploading your file:";
				}
			}
		}
		
		//background upload
		$myfileBackground=basename($_FILES["fileToUploadBackground"]["name"]);
		if($auth->UserType == "Administrator") {  
			$userID=$_POST['user_id'];
		} else {
			$userID=$auth->UserId;
		}
		$targetFileBackground="";
		if(isset($myfileBackground) && $myfileBackground!="") {
			$targetDir = $config["physicalPath"] . "gallery/customer_logo/".$userID."/";

			$uploadOk = 1;
			$imageFileTypeBackground = pathinfo($myfileBackground,PATHINFO_EXTENSION);

			//$targetFile = $targetDir.$auth->UserId.".".$imageFileType;
			$targetFileBackground = $targetDir.'b'.$_GET['item'].".".$imageFileTypeBackground;
			$targetFiletoSaveBackground = 'b'.$_GET['item'].".".$imageFileTypeBackground;

			// Check if image file is a actual image or fake image
				$check = getimagesize($_FILES["fileToUploadBackground"]["tmp_name"]);
				if($check !== false) {
					//echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
				} else {
					//echo "File is not an image.";
					$uploadOk = 0;
				}

			 // Check file size
			if ($_FILES["fileToUploadBackground"]["size"] > 1000000) {
				//echo "Sorry, your file is too large. Our limit is 500kb";
				$uploadOk = 0;
			}

			// Create target dir
			if (!file_exists($targetDir)) {
				@mkdir($targetDir);
			}

			// Allow certain file formats
			if($imageFileTypeBackground != "jpg" && $imageFileTypeBackground != "png" && $imageFileTypeBackground != "jpeg" && $imageFileTypeBackground != "gif" ) {
				//echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				$uploadOk = 0;
			}
			// Check if file already exists
			//if (file_exists($target_file)) {
			//	echo "Sorry, file already exists.";
			//	$uploadOk = 0;
			//}
			
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo fileNotUploaded;
			// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["fileToUploadBackground"]["tmp_name"], $targetFileBackground)) {
					echo "The file ". basename( $_FILES["fileToUploadBackground"]["name"]). " has been uploaded.";
				} else {
					echo uploadError;
				}
			}
		}

		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();

		if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
		{
			if($auth->UserType != "Administrator") {  
				$drAccess=$db->RowSelectorQuery("SELECT * FROM devices WHERE id=".$_GET['item']." AND (user_id=". $auth->UserId." OR user_id IN (SELECT parent FROM users WHERE user_id=".$auth->UserId."))");
				if(!isset($drAccess['id'])) {
					$messages->addMessage("ACCESS RESTRICTED!!!");
					Redirect("index.php");
				}
			}
			$PrimaryKeys["id"] = intval($_GET["item"]);
			$QuotFields["id"] = true;
			$item=$_GET['item'];
			$dr_user=$db->RowSelectorQuery("SELECT user_id FROM messages WHERE id=".$_GET["item"]);
			$userID=$dr_user['user_id'];
		} else {
			$item=$db->sql_nextid();
			$userID=($auth->UserType == "Administrator" ? $_POST["user_id"] : $auth->UserId);
		}
		
		if($auth->UserType == "Administrator" || $auth->UserRow['parent']==0) {
			$Collector["user_id"] = ($auth->UserType == "Administrator" ? $_POST["user_id"] : $auth->UserId);
			$QuotFields["user_id"] = true;
		}
		
		if($auth->UserType == "Administrator") {
			$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
			$QuotFields["is_valid"] = true;
		}
		
		$Collector["ssid"] = $_POST["ssid"];
		$QuotFields["ssid"] = true;
		
		$Collector["wpakey"] = $_POST["wpakey"];
		$QuotFields["wpakey"] = true;

		$Collector["friendlyName"] = $_POST["friendlyName"];
		$QuotFields["friendlyName"] = true;
		
		$Collector["businessCategory_id"] = $_POST["businessCategory_id"];
		$QuotFields["businessCategory_id"] = true;
		
		$Collector["lat"] = $_POST["lat"];
		$QuotFields["lat"] = true;
		
		$Collector["lng"] = $_POST["lng"];
		$QuotFields["lng"] = true;
		
		$Collector["region_id"] = $_POST["region_id"];
		$QuotFields["region_id"] = true;
		
		if($auth->UserType == "Administrator") {
			$Collector["mac"] = $_POST["mac"];
			$QuotFields["mac"] = true;
			
			$Collector["business_name"] = $_POST["business_name"];
			$QuotFields["business_name"] = true;
		}
				
		$Collector["remark"] = html_entity_decode($_POST['remark'], ENT_QUOTES, "UTF-8");
		$QuotFields["remark"] = true;

		$Collector["title"] = $_POST["title"];
		$QuotFields["title"] = true;
		
		$Collector["subtitle"] = $_POST["subtitle"];
		$QuotFields["subtitle"] = true;

		$Collector["redirect_url"] = $_POST["redirect_url"];
		$QuotFields["redirect_url"] = true;
		
		$Collector["page1"] = html_entity_decode($_POST['page1'], ENT_QUOTES, "UTF-8");
		$QuotFields["page1"] = true;

		$Collector["page2"] = html_entity_decode($_POST['page2'], ENT_QUOTES, "UTF-8");
		$QuotFields["page2"] = true;

		$Collector["page3"] = html_entity_decode($_POST['page3'], ENT_QUOTES, "UTF-8");
		$QuotFields["page3"] = true;

		$Collector["contactTo"] = $_POST["contactTo"];
		$QuotFields["contactTo"] = true;
		
		$Collector["color_back"] = $_POST["color_back"];
		$QuotFields["color_back"] = true;

		$Collector["color_title"] = $_POST["color_title"];
		$QuotFields["color_title"] = true;
		
		$Collector["color_subtitle"] = $_POST["color_subtitle"];
		$QuotFields["color_subtitle"] = true;
		
		$Collector["color_campain"] = $_POST["color_campain"];
		$QuotFields["color_campain"] = true;
		
		$Collector["color_button"] = $_POST["color_button"];
		$QuotFields["color_button"] = true;
		
		$Collector["color_button_text"] = $_POST["color_button_text"];
		$QuotFields["color_button_text"] = true;
		
		$Collector["banner"] = $_POST["banner"];
		$QuotFields["banner"] = true;
		
		$Collector["lastUpdate"] = date('Y-m-d H:i:s');
		$QuotFields["lastUpdate"] = true;
		
		$Collector["sended"] = "False";
		$QuotFields["sended"] = true;

		$Collector["last_change"] = date('Y-m-d H:i:s');
		$QuotFields["last_change"] = true;
		
		$Collector["last_change_user"] = $auth->UserId;
		$QuotFields["last_change_user"] = true;
				
		if(isset($myfile) && $targetFile!="") {		
			$Collector["files"] = $targetFiletoSave;
			$QuotFields["files"] = true;
		}
		
		if(isset($myfileBackground) && $targetFileBackground!="") {		
			$Collector["background"] = $targetFiletoSaveBackground;
			$QuotFields["background"] = true;
		}
		
		$Collector["password_access"] = $_POST["password_access"]; //($_POST["password_access"]=="on" ? "True" : "False");
		$QuotFields["password_access"] = true;
		
		//$Collector["card_access"] = ($_POST["card_access"]=="on" ? "True" : "False");
		//$QuotFields["card_access"] = true;
		
		//$Collector["one_stage"] = ($_POST["one_stage"]=="on" ? "True" : "False");
		//$QuotFields["one_stage"] = true;
		
		$Collector["first_stage"] = ($_POST["first_stage"]=="on" ? "True" : "False");
		$QuotFields["first_stage"] = true;

		$Collector["adv_login"] = ($_POST["adv_login"]=="on" ? "True" : "False");
		$QuotFields["adv_login"] = true;
		
		$Collector["time_limit"] = $_POST["time_limit"];
		$QuotFields["time_limit"] = true;
		
		$Collector["time_reset"] = ($_POST["time_reset"]<$_POST["time_limit"]?$_POST["time_limit"]:$_POST["time_reset"]);
		$QuotFields["time_reset"] = true;

		$Collector["speed_limit"] = $_POST["speed_limit"];
		$QuotFields["speed_limit"] = true;

		$Collector["email_access"] = ($_POST["email_access"]=="on" ? "True" : "False");
		$QuotFields["email_access"] = true;
		
		$Collector["password"] = $_POST["password"];
		$QuotFields["password"] = true;

		//$Collector["like_access"] = ($_POST["like_access"]=="on" ? "True" : "False");
		//$QuotFields["like_access"] = true;
		$Collector["like_button"] = ($_POST["like_button"]=="on" ? "True" : "False");
		$QuotFields["like_button"] = true;
		
		$Collector["like_access"] = $_POST["like_access"];
		$QuotFields["like_access"] = true;
		
		$Collector["checkin_access"] = ($_POST["checkin_access"]=="on" ? "True" : "False");
		$QuotFields["checkin_access"] = true;
		
		$Collector["social_button_text"] = $_POST["social_button_text"];
		$QuotFields["social_button_text"] = true;
		
		$Collector["tweet_access"] = ($_POST["tweet_access"]=="on" ? "True" : "False");
		$QuotFields["tweet_access"] = true;
		
		$Collector["twitter_message"] = $_POST["twitter_message"];
		$QuotFields["twitter_message"] = true;
		
		$Collector["facebookURL"] = $_POST["facebookURL"];
		$QuotFields["facebookURL"] = true;
		
		$Collector["facebookPageID"] = $_POST["facebookPageID"];
		$QuotFields["facebookPageID"] = true;

		$Collector["facebookPixel"] = $_POST["facebookPixel"];
		$QuotFields["facebookPixel"] = true;
		
		$Collector["twitterURL"] = $_POST["twitterURL"];
		$QuotFields["twitterURL"] = true;
		
		$Collector["social_timer"] = $_POST["social_timer"];
		$QuotFields["social_timer"] = true;
		
		$Collector["safe_access"] = ($_POST["safe_access"]=="on" ? "True" : "False");
		$QuotFields["safe_access"] = true;
		
		$Collector["rate"] = ($_POST["rate"]=="on" ? "True" : "False");
		$QuotFields["rate"] = true;
		
		$Collector["menu"] = ($_POST["menu"]=="on" ? "True" : "False");
		$QuotFields["menu"] = true;
		
		$Collector["catalog_color"] = $_POST["catalog_color"];
		$QuotFields["catalog_color"] = true;
		
		$Collector["newCustomerMsg"] = html_entity_decode($_POST['newCustomerMsg'], ENT_QUOTES, "UTF-8");
		$QuotFields["newCustomerMsg"] = true;
		
		$Collector["silverCustomerMsg"] = html_entity_decode($_POST['silverCustomerMsg'], ENT_QUOTES, "UTF-8");
		$QuotFields["silverCustomerMsg"] = true;

		$Collector["goldCustomerMsg"] = html_entity_decode($_POST['goldCustomerMsg'], ENT_QUOTES, "UTF-8");
		$QuotFields["goldCustomerMsg"] = true;
		
		$Collector["silverCustomerVisits"] = $_POST["silverCustomerVisits"];
		$QuotFields["silverCustomerVisits"] = true;
		
		$Collector["goldCustomerVisits"] = $_POST["goldCustomerVisits"];
		$QuotFields["goldCustomerVisits"] = true;

		$Collector["newActive"] = ($_POST["newActive"]=="on" ? "True" : "False");
		$QuotFields["newActive"] = true;

		$Collector["silverActive"] = ($_POST["silverActive"]=="on" ? "True" : "False");
		$QuotFields["silverActive"] = true;

		$Collector["goldActive"] = ($_POST["goldActive"]=="on" ? "True" : "False");
		$QuotFields["goldActive"] = true;
		
		$db->ExecuteUpdater("devices",$PrimaryKeys,$Collector,$QuotFields);		

		// update languages
		//lang1
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();

		if(isset($_POST["text1lang"]) && intval($_POST["text1lang"]) >0)
		{
			echo 'welcome: '.$_POST['welcomeMessage1'];
			$drCheck1=$db->RowSelectorQuery("SELECT id FROM texts2l WHERE device_id=".$item." AND lang_id=".$_POST['text1lang']);
			if(intval($drCheck1['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck1['id']);
				$QuotFields["id"] = true;
			}
			$Collector["welcomeMessage"] = html_entity_decode($_POST['welcomeMessage1'], ENT_QUOTES, "UTF-8");
			$QuotFields["welcomeMessage"] = true;
			$Collector["silverMessage"] = html_entity_decode($_POST['silverMessage1'], ENT_QUOTES, "UTF-8");
			$QuotFields["silverMessage"] = true;
			$Collector["goldMessage"] = html_entity_decode($_POST['goldMessage1'], ENT_QUOTES, "UTF-8");
			$QuotFields["goldMessage"] = true;
			$Collector["lang_id"] = $_POST["text1lang"];
			$QuotFields["lang_id"] = true;
			$Collector["device_id"] = $item;
			$QuotFields["device_id"] = true;
			$db->ExecuteUpdater("texts2l",$PrimaryKeys,$Collector,$QuotFields);
		}

		//lang2
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		if(isset($_POST["text2lang"]) && intval($_POST["text2lang"]) >0)
		{
			$drCheck2=$db->RowSelectorQuery("SELECT id FROM texts2l WHERE device_id=".$item." AND lang_id=".$_POST['text2lang']);
			if(intval($drCheck1['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck2['id']);
				$QuotFields["id"] = true;
			}
			$Collector["welcomeMessage"] = html_entity_decode($_POST['welcomeMessage2'], ENT_QUOTES, "UTF-8");
			$QuotFields["welcomeMessage"] = true;
			$Collector["silverMessage"] = html_entity_decode($_POST['silverMessage2'], ENT_QUOTES, "UTF-8");
			$QuotFields["silverMessage"] = true;
			$Collector["goldMessage"] = html_entity_decode($_POST['goldMessage2'], ENT_QUOTES, "UTF-8");
			$QuotFields["goldMessage"] = true;
			$Collector["lang_id"] = $_POST["text2lang"];
			$QuotFields["lang_id"] = true;
			$Collector["device_id"] = $item;
			$QuotFields["device_id"] = true;
			
			$db->ExecuteUpdater("texts2l",$PrimaryKeys,$Collector,$QuotFields);
		}
		//lang3
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		if(isset($_POST["text3lang"]) && intval($_POST["text3lang"]) >0)
		{
			$drCheck3=$db->RowSelectorQuery("SELECT id FROM texts2l WHERE device_id=".$item." AND lang_id=".$_POST['text3lang']);
			if(intval($drCheck3['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck3['id']);
				$QuotFields["id"] = true;
			}
			$Collector["welcomeMessage"] = html_entity_decode($_POST['welcomeMessage3'], ENT_QUOTES, "UTF-8");
			$QuotFields["welcomeMessage"] = true;
			$Collector["silverMessage"] = html_entity_decode($_POST['silverMessage3'], ENT_QUOTES, "UTF-8");
			$QuotFields["silverMessage"] = true;
			$Collector["goldMessage"] = html_entity_decode($_POST['goldMessage3'], ENT_QUOTES, "UTF-8");
			$QuotFields["goldMessage"] = true;
			$Collector["lang_id"] = $_POST["text3lang"];
			$QuotFields["lang_id"] = true;
			$Collector["device_id"] = $item;
			$QuotFields["device_id"] = true;
			
			$db->ExecuteUpdater("texts2l",$PrimaryKeys,$Collector,$QuotFields);
		}
		//lang4
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		if(isset($_POST["text4lang"]) && intval($_POST["text4lang"]) >0)
		{
			$drCheck4=$db->RowSelectorQuery("SELECT id FROM texts2l WHERE device_id=".$item." AND lang_id=".$_POST['text4lang']);
			if(intval($drCheck4['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck4['id']);
				$QuotFields["id"] = true;
			}
			$Collector["welcomeMessage"] = html_entity_decode($_POST['welcomeMessage4'], ENT_QUOTES, "UTF-8");
			$QuotFields["welcomeMessage"] = true;
			$Collector["silverMessage"] = html_entity_decode($_POST['silverMessage4'], ENT_QUOTES, "UTF-8");
			$QuotFields["silverMessage"] = true;
			$Collector["goldMessage"] = html_entity_decode($_POST['goldMessage4'], ENT_QUOTES, "UTF-8");
			$QuotFields["goldMessage"] = true;
			$Collector["lang_id"] = $_POST["text4lang"];
			$QuotFields["lang_id"] = true;
			$Collector["device_id"] = $item;
			$QuotFields["device_id"] = true;
			
			$db->ExecuteUpdater("texts2l",$PrimaryKeys,$Collector,$QuotFields);
		}

		//if(isset($_GET["id"]) && $_GET["id"] != "") $Record_ID = $_GET["id"];
		//else $Record_ID = $db->sql_nextid();
		$messages->addMessage("SAVED!!!");
		Redirect("index.php?com=devices");
	}
	else if($_REQUEST["Command"] == "DELETE")
	{
		//$item=(isset($command[1]) && $command[1]!="")? $command[1]:"";
		if($item != "" && $auth->UserType == "Administrator")
		{
			$error=0;
			$dr=$db->RowSelectorQuery('SELECT mac FROM devices WHERE id='.$item);
			if($dr['mac']!='') {
				$result = $db->sql_query("SELECT * FROM stats WHERE device='".$dr['mac']."'");
				if($db->sql_numrows($result) > 0) $error++;
				$result = $db->sql_query("SELECT * FROM dailystats WHERE device='".$dr['mac']."'");
				if($db->sql_numrows($result) > 0) $error++;
			}
			if($error==0) {		
				$db->sql_query("DELETE FROM devices WHERE id=" . $item);
				$messages->addMessage("DELETED!!!");
				Redirect("index.php?com=devices");
			} else {
				$messages->addMessage(cannotDelete);
				Redirect($BaseUrl);
			}
		}
	}
}

?>
	<?
    if(isset($_GET["item"]))
    {
		if(intval($_GET["item"]) <= 0) {
			$messages->addMessage("ACCESS RESTRICTED!!!");
			if($auth->UserType != "Administrator" || $auth->UserRow['parent']!=0) Redirect("index.php?com=devices");
		}
		if($_GET["item"]=="") {
			$userID=$auth->UserId;
		} else {
			if($auth->UserType == "Administrator") {
				$dr_user=$db->RowSelectorQuery("SELECT user_id FROM devices WHERE id=".$_GET["item"]);
				$userID=$auth->UserId;
				$userID=$dr_user['user_id'];
				$filter="";
				$query="SELECT * FROM devices WHERE 1=1 ".$filter." AND id=".$_GET['item'];
				$dr_e = $db->RowSelectorQuery($query);
			} else {
				$userID=$auth->UserId;
				//$filter=" AND user_id=".$userID;
				$filter=" AND (user_id=".$userID." OR user_id IN (SELECT parent FROM users WHERE user_id=".$auth->UserId."))";
				$query="SELECT * FROM devices WHERE 1=1 ".$filter." AND id=".$_GET['item'];
				
				$dr_e = $db->RowSelectorQuery($query);
				if (!isset($dr_e["id"])) {
					$messages->addMessage("NOT FOUND!!!");
					Redirect("index.php?com=devices");
				}
			}
		}

		$dr_deviceUser=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$userID);
		$lang1ID=$dr_deviceUser['lang1ID'];
		$lang2ID=$dr_deviceUser['lang2ID'];
		$lang3ID=$dr_deviceUser['lang3ID'];
		$lang4ID=$dr_deviceUser['lang4ID'];
		if(intval($lang1ID)>0) $dr1 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang1ID);
		if(intval($lang1ID)>0) $lang1=$dr1['native_name'];
		if(intval($lang2ID)>0) $dr2 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang2ID);
		if(intval($lang2ID)>0) $lang2=$dr2['native_name'];
		if(intval($lang3ID)>0) $dr3 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang3ID);
		if(intval($lang3ID)>0) $lang3=$dr3['native_name'];
		if(intval($lang4ID)>0) $dr4 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang4ID);
		if(intval($lang4ID)>0) $lang4=$dr4['native_name'];
		if(intval($lang1ID)>0) $dr_l1 = $db->RowSelectorQuery("SELECT * FROM texts2l WHERE device_id = ".$dr_e['id']." AND lang_id = ".$lang1ID);
		if(intval($lang2ID)>0) $dr_l2 = $db->RowSelectorQuery("SELECT * FROM texts2l WHERE device_id = ".$dr_e['id']." AND lang_id = ".$lang2ID);
		if(intval($lang3ID)>0) $dr_l3 = $db->RowSelectorQuery("SELECT * FROM texts2l WHERE device_id = ".$dr_e['id']." AND lang_id = ".$lang3ID);
		if(intval($lang4ID)>0) $dr_l4 = $db->RowSelectorQuery("SELECT * FROM texts2l WHERE device_id = ".$dr_e['id']." AND lang_id = ".$lang4ID);
			
        //if($auth->UserType != "Administrator") {  
        //    $drAccess=$db->RowSelectorQuery("SELECT * FROM devices WHERE id=".$_GET['item']." AND user_id=". $auth->UserId);
        //    if(!isset($drAccess['id'])) Redirect("index.php");
        //}
        //$query="SELECT * FROM devices WHERE id=".$_GET['item'];
        //$dr_e = $db->RowSelectorQuery($query);
    ?>
    <div class="breadcrumbs">
        <ul>
            <li>
                <a href="index.php"><?=homePage?></a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="<?=$BaseUrl?>"><?=$nav?></a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="#"><?=edit?></a>
            </li>
        </ul>
    </div>
    <div class="row-fluid">
        <div class="span12">            
           <div class="box box-color box-bordered">
                <div class="box-title">
                    <h3>
                        <i class="icon-user"></i>
                        <?=deviceEdit?>
                    </h3>
                </div>
                <div class="box-content nopadding">
                    <ul class="tabs tabs-inline tabs-top">
                        <li class='active'>
                            <a href="#profile" data-toggle='tab'><i class="icon-user"></i> <?=general?></a>
                        </li>
                        <li>
                            <a href="#connection" data-toggle='tab'><i class="icon-bolt"></i> <?=devicesConnection?></a>
                        </li>
                        <li>
                            <a href="#security" data-toggle='tab'><i class="icon-lock"></i> <?=security?></a>
                        </li>
                        <li>
                            <a href="#personalMessages" data-toggle='tab'><i class="icon-envelope-alt"></i> <?=personalMessages?></a>
                        </li>
                        <li>
                            <a href="#device" data-toggle='tab'><i class="icon-cog"></i> <?=pages?></a>
                        </li>
                    </ul>
                    <div class="tab-content padding tab-content-inline tab-content-bottom">
                        <div class="tab-pane active" id="profile">
                            <? if($auth->UserType == "Administrator") { ?> 
							<div class="controls">
								<label for="textfield" class="control-label"><?=active?></label>
								<input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?> />
							</div>
							<div class="controls">
								<label for="business_name" class="control-label"><?=businessName?></label>
								<input type="text" name="business_name" id="business_name" class="input-xxlarge" value="<?=(isset($dr_e["business_name"]) ? $dr_e["business_name"] : "")?>">
							</div>
							<br>
                            <div class="controls">
                                <label for="textfield" class="control-label"><?=customer?></label>
                                <?
                                    $query = "SELECT * FROM users WHERE is_valid='True' ORDER BY company_name";
                                    echo Select::GetDbRender("user_id", $query, "user_id", "company_name", (isset($dr_e["user_id"]) ? $dr_e["user_id"] : ""), true);
                                 ?>
                            </div> <? } 
                            ?>
                            <div class="control-group">
                                <label for="textfield" class="control-label"><?=friendlyName?></label>
                                <div class="controls">
                                    <input type="text" name="friendlyName" id="friendlyName" class="input-xxlarge" value="<?=(isset($dr_e["friendlyName"]) ? $dr_e["friendlyName"] : "")?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="textfield" class="control-label"><?=serial?></label>
                                <div class="controls">
                                    <input type="text" name="mac" id="mac" class="input-xxlarge" value="<?=(isset($dr_e["mac"]) ? $dr_e["mac"] : "")?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="textfield" class="control-label"><?=ssid?></label>
                                <div class="controls">
                                    <input type="text" name="ssid" id="ssid" class="input-xxlarge" value="<?=(isset($dr_e["ssid"]) ? $dr_e["ssid"] : "")?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="textfield" class="control-label"><?=privatePassword?></label>
                                <div class="controls">
                                    <input type="text" name="wpakey" id="wpakey" class="input-xxlarge" value="<?=(isset($dr_e["wpakey"]) ? $dr_e["wpakey"] : "12345678")?>">
                                </div>
                            </div>
							<? if($auth->UserType == "Administrator") { ?>
                            <div class="control-group">
                                <label for="textarea" class="control-label"><?=remarks?></label>
                                <div class="controls">
                                    <textarea name="remark" rows="6" id="remark" class="input-block-level"><?=(isset($dr_e["remark"]) ? $dr_e["remark"] : "")?></textarea>
                                </div>
                            </div>
							
                            <div class="controls">
                                <label for="textfield" class="control-label"><?=region?></label>
                                <?
                                    $query = "SELECT * FROM regions WHERE is_valid='True' ORDER BY region_name";
                                    echo Select::GetDbRender("region_id", $query, "region_id", "region_name", (isset($dr_e["region_id"]) ? $dr_e["region_id"] : ""), true);
                                 ?>
                            </div>
							
                            <div class="controls">
                                <label for="textfield" class="control-label"><?=businessCategory?></label>
                                <?
                                    $query = "SELECT * FROM businessCategories WHERE is_valid='True' ORDER BY businessCategoryName";
                                    echo Select::GetDbRender("businessCategory_id", $query, "businessCategory_id", "businessCategoryName", (isset($dr_e["businessCategory_id"]) ? $dr_e["businessCategory_id"] : ""), true);
                                 ?>
                            </div>
                            <? } ?>
							           
								<fieldset class="gllpLatlonPicker" id="custom_id">
									<input type="text" class="gllpSearchField">
									<input type="button" class="gllpSearchButton btn btn-primary" value="search" style="margin-bottom:10px;"> <!--  -->
									<div class="gllpMap" style="width:100%;">Google Maps</div>
									<br/>
									<div class="controls">
										<!-- Lat:--> <input type="hidden" name="lat" class="gllpLatitude" value="<?=(isset($dr_e['lat']) ? $dr_e['lat']:38)?>"/>
										<!-- Lng:--> <input type="hidden" name="lng" class="gllpLongitude" value="<?=(isset($dr_e['lng']) ? $dr_e['lng']:24)?>"/>
									</div>
									<input type="hidden" class="gllpZoom" value="<?=(isset($dr_e['lat']) ? 14:5)?>"/>
								</fieldset>
							
                        </div>

						
						<div class="tab-pane" id="connection">
                            <!--<div class="check-line">
                                <label class="inline" for="one_stage"><?=oneClick?></label>
                                <div class="controls">
                                    <input id="one_stage" name="one_stage" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <? //=((isset($dr_e["one_stage"]) && $dr_e["one_stage"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>-->
                            <div class="check-line">
                                <label class="inline" for="first_stage"><?=simplePage?></label> <!-- firstStage-->
                                <div class="controls">
                                    <input id="first_stage" name="first_stage" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=(($dr_e["first_stage"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>
                            <div class="check-line">
                                <label class="inline" for="adv_login"><?=advRedirect?></label> <!-- adv_login-->
                                <div class="controls">
                                    <input id="adv_login" name="adv_login" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=(($dr_e["adv_login"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>
							
							<hr/>
							<h3>Email</h3>
                            <div class="check-line">
                                <label class="inline" for="email_access">Email Access</label> <!-- adv_login-->
                                <div class="controls">
                                    <input id="email_access" name="email_access" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=(($dr_e["email_access"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>

							<hr/>
							<h3>Passwords</h3>
                            <div class="control-group">                            
                                <div class="check-line">
                                    <input type="radio" id="pass1" value="False"  class="icheck-me" name="password_access" data-skin="square" data-color="blue" <?=((isset($dr_e["password_access"]) && $dr_e["password_access"]=='False') ? 'checked':'')?>> <label class='inline' for="c8"><?=disablePassword?></label>
                                </div>
                                <div class="check-line">
                                    <input type="radio" id="pass2" value="True"  class="icheck-me" name="password_access" data-skin="square" data-color="blue" <?=((isset($dr_e["password_access"]) && $dr_e["password_access"]=='True') ? 'checked':'')?>><label class='inline' for="c7"><?=passwordAccess?></label>
                                </div>
                                <div class="check-line">
                                    <input type="radio" id="pass3" value="Card" class="icheck-me" name="password_access" data-skin="square" data-color="blue" <?=((isset($dr_e["password_access"]) && $dr_e["password_access"]=='Card') ? 'checked':'')?>><label class='inline' for="c7"><?=cardAccess?></label>
                                </div>
							</div> 
                            
                            <!--
                            <div class="check-line">
                                <label class="inline" for="card_access">Card access</label>
                                <div class="controls">
                                    <input id="card_access" name="card_access" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <? //=((isset($dr_e["card_access"]) && $dr_e["card_access"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>
                            -->
                            <!--
                            <div class="check-line">
                                <label class="inline" for="password_access"><? //=passwordProtection?></label>
                                <div class="controls">
                                    <input id="password_access" name="password_access" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <? //=((isset($dr_e["password_access"]) && $dr_e["password_access"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>
                            -->
                            <div class="control-group">
                                <label for="textfield" class="control-label"><?=password?></label>
                                <div class="controls">
                                    <input type="text" name="password" id="password" class="input-xxlarge" value="<?=(isset($dr_e["password"]) ? $dr_e["password"] : "")?>">
                                </div>
                            </div>
                            <!--
                            <div class="check-line">
                                <label class="inline" for="is_valid"><? //=likeAccess?></label>
                                <div class="controls">
                                    <input id="like_access" name="like_access" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <? //=((isset($dr_e["like_access"]) && $dr_e["like_access"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>
                            -->
							<hr/>
							<h3><?=socialNetworks?></h3>
                            <div class="control-group">                            
                                <div class="check-line">
                                    <input type="radio" id="like1" value="False"  class="icheck-me" name="like_access" data-skin="square" data-color="blue" <?=((isset($dr_e["like_access"]) && $dr_e["like_access"]=='False') ? 'checked':'')?>> <label class='inline' for="c8"><?=disabled?></label>
                                </div>
                                <div class="check-line">
                                    <input type="radio" id="like2" value="True"  class="icheck-me" name="like_access" data-skin="square" data-color="blue" <?=((isset($dr_e["like_access"]) && $dr_e["like_access"]=='True') ? 'checked':'')?>><label class='inline' for="c7"><?=likeAccess?></label>
                                </div>
                                <div class="check-line">
                                    <input type="radio" id="like3" value="Button" class="icheck-me" name="like_access" data-skin="square" data-color="blue" <?=((isset($dr_e["like_access"]) && $dr_e["like_access"]=='Button') ? 'checked':'')?>><label class='inline' for="c7"><?=simpleSocialRedirect?></label>
                                </div>
								
								<div class="check-line">
									<label class="inline" for="checkin_access">Facebook like button</label>
									<div class="controls">
										<input id="like_button" name="like_button" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=(($dr_e["like_button"]=='True') ? 'checked':'')?>  />
									</div>
								</div>
								
                                <div class="control-group">
                                    <label for="textfield" class="control-label"><?=socialButtonText?></label>
                                    <div class="controls">
                                        <input type="text" name="social_button_text" id="social_button_text" class="input-xxlarge" value="<?=(isset($dr_e["social_button_text"]) ? $dr_e["social_button_text"] : "")?>">
                                    </div>
                                </div>
								<div class="control-group">
									<label for="textfield" class="control-label">Facebook URL</label>
									<div class="controls">
										<input type="text" name="facebookURL" id="facebookURL" class="input-xxlarge" value="<?=(isset($dr_e["facebookURL"]) ? $dr_e["facebookURL"] : "")?>">
									</div>
								</div>
								<div class="control-group">
									<label for="textfield" class="control-label">Facebook Page ID</label>
									<div class="controls">
										<input type="text" name="facebookPageID" id="facebookPageID" class="input-xxlarge" value="<?=(isset($dr_e["facebookPageID"]) ? $dr_e["facebookPageID"] : "")?>">
									</div>
								</div>

								<div class="control-group">
									<label for="textfield" class="control-label">Facebook Pixel</label>
									<div class="controls">
										<input type="text" name="facebookPixel" id="facebookPixel" class="input-xxlarge" value="<?=(isset($dr_e["facebookPixel"]) ? $dr_e["facebookPixel"] : "")?>">
									</div>
								</div>
								
								<div class="check-line">
									<label class="inline" for="checkin_access">Facebook checkin</label>
									<div class="controls">
										<input id="checkin_access" name="checkin_access" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=(($dr_e["checkin_access"]=='True') ? 'checked':'')?>  />
									</div>
								</div>
							</div>
							<hr/>
							<!--
                            <div class="check-line">
                                <label class="inline" for="is_valid"><?//=tweetAccess?></label>
                                <div class="controls">
                                    <input id="tweet_access" name="tweet_access" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["tweet_access"]) && $dr_e["tweet_access"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="textfield" class="control-label">Twitter URL</label>
                                <div class="controls">
                                    <input type="text" name="twitterURL" id="twitterURL" class="input-xxlarge" value="<? //=(isset($dr_e["twitterURL"]) ? $dr_e["twitterURL"] : "")?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="textfield" class="control-label"><? //=tweetMessage?></label>
                                <div class="controls">
                                    <input type="text" name="twitter_message" id="twitter_message" class="input-xxlarge" value="<? //=(isset($dr_e["twitter_message"]) ? $dr_e["twitter_message"] : "")?>">
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="textfield" class="control-label"><? //=socialTime?></label>
                                <div class="controls">
                                    <input type="text" name="social_timer" id="social_timer" class="input-xxlarge" value="<? //=(isset($dr_e["social_timer"]) ? $dr_e["social_timer"] : "")?>">
                                </div>
                            </div>
							<hr/>
							-->
							
							<h3>Target url</h3>
							<div class="control-group">
                                <label for="textfield" class="control-label"><?=redirectUrl?></label>
                                <div class="controls">
                                    <input type="text" name="redirect_url" id="redirect_url" class="input-xxlarge" value="<?=(isset($dr_e["redirect_url"]) ? $dr_e["redirect_url"] : "")?>">
                                </div>
                            </div>
                        </div> 
						
                        <div class="tab-pane" id="security">
                            <div class="control-group">
                                <label for="select" class="control-label"><?=timeLimit?></label>
                                <div class="controls">
								<? //$varTimeLimit=(intval($dr_e["time_limit"])>0?intval($dr_e["time_limit"]:24) ?>
                                    <select name="time_limit" id="time_limit" class="input-large">
										<? for($i=1; $i<=48; $i++) {
										echo "<option value='".$i."' ".((isset($dr_e["time_limit"]) && $dr_e["time_limit"]==$i) ? 'selected':'').">".$i."</option>";
										//echo "<option value='".$i."' ".(($varTimeLimit==$i) ? 'selected':'').">".$i."</option>";
										} ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label for="select" class="control-label"><?=timeReset?></label>
                                <div class="controls">
									<? //$varTimeReset=(intval($dr_e["time_reset"])>0?intval($dr_e["time_reset"]:24) ?>
                                    <select name="time_reset" id="time_reset" class="input-large">
										<? for($i=1; $i<=48; $i++) {
										echo "<option value='".$i."' ".((isset($dr_e["time_reset"]) && $dr_e["time_reset"]==$i) ? 'selected':'').">".$i."</option>";
										//echo "<option value='".$i."' ".(($varTimeLimit==$i) ? 'selected':'').">".$i."</option>";
										} ?>
                                    </select>
                                </div>
                            </div>
							
                            <div class="control-group">
                                <label for="select" class="control-label"><?=speedLimit?></label>
                                <div class="controls">
                                    <select name="speed_limit" id="speed_limit" class="input-large">
									<option value='1' <?=($dr_e["speed_limit"]=='1' ? 'selected':'');?>><?=speed1?> </option>
									<option value='2' <?=($dr_e["speed_limit"]=='2' ? "selected":"");?>><?=speed2?> (256/64)</option>
									<option value='3' <?=($dr_e["speed_limit"]=='3' ? 'selected':'');?>><?=speed3?> (512/128)</option>
									<option value='4' <?=($dr_e["speed_limit"]=='4' ? 'selected':'');?>><?=speed4?> (1024/256)</option>
									<option value='5' <?=($dr_e["speed_limit"]=='5' ? 'selected':'');?>><?=speed5?> (2048/512)</option>
									<option value='6' <?=($dr_e["speed_limit"]=='6' ? 'selected':'');?>><?=speed6?> (5120/2048)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="check-line">
                                <label class="inline" for="safe_access"><?=secureAccess?></label>
                                <div class="controls">
                                    <input id="safe_access" name="safe_access" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["safe_access"]) && $dr_e["safe_access"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>
                        </div>
						
                        <div class="tab-pane" id="personalMessages">
                            <div class="check-line">
                                <label class="inline" for="newActive"><?=active?> (<?=newCustomerMessage?>)</label>
                                <div class="controls">
                                    <input id="newActive" name="newActive" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["newActive"]) && $dr_e["newActive"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>
							
							<hr/>
                            <div class="check-line">
                                <label class="inline" for="silverActive"><?=active?> (<?=silverMessage?>)</label>
                                <div class="controls">
                                    <input id="silverActive" name="silverActive" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["silverActive"]) && $dr_e["silverActive"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="silverCustomerVisits" class="control-label"><?=silverVisits?></label>
                                <div class="controls">
                                    <input type="text" name="silverCustomerVisits" id="silverCustomerVisits" class="input-xxsmall" value="<?=(isset($dr_e["silverCustomerVisits"]) ? $dr_e["silverCustomerVisits"] : "")?>">
                                </div>
                            </div>

							<hr/>
                            <div class="check-line">
                                <label class="inline" for="goldActive"><?=active?> (<?=goldMessage?>)</label>
                                <div class="controls">
                                    <input id="goldActive" name="goldActive" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["goldActive"]) && $dr_e["goldActive"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>
                            <div class="control-group">
                                <label for="goldCustomerVisits" class="control-label"><?=goldVisits?></label>
                                <div class="controls">
                                    <input type="text" name="goldCustomerVisits" id="goldCustomerVisits" class="input-xxsmall" value="<?=(isset($dr_e["goldCustomerVisits"]) ? $dr_e["goldCustomerVisits"] : "")?>">
                                </div>
                            </div>


						<div class="box box-bordered">
							<div class="box-title">
								<ul class="tabs tabs-left">
									<li class="active">
										<a href="#lang1" data-toggle="tab"><?=$lang1?></a>
									</li>
									<? if(strlen($lang2)>1) {?>
									<li class="">
										<a href="#lang2" data-toggle="tab"><?=$lang2?></a>
									</li>
									<? } ?>
									<? if(strlen($lang3)>1) {?>
									<li class="">
										<a href="#lang3" data-toggle="tab"><?=$lang3?></a>
									</li>
									<? } ?>
									<? if(strlen($lang4)>1) {?>
									<li class="">
										<a href="#lang4" data-toggle="tab"><?=$lang4?></a>
									</li>
									<? } ?>
								</ul>
								<div class="actions">
									<a href="#" class="btn btn-mini content-slideUp"><i class="icon-angle-down"></i></a>
								</div>
							</div>
							<div class="box-content" style="display: block;">
								<div class="tab-content">
									<? if(strlen($lang1)>1) {?>
									<div class="tab-pane active" id="lang1">
										<div class="control-group">
											<label for="welcomeMessage1" class="control-label"><?=newCustomerMessage?></label>
											<div class="controls">
												<textarea name="welcomeMessage1" rows="4" id="welcomeMessage1" class="input-block-level"><?=(isset($dr_l1['welcomeMessage']) ? $dr_l1['welcomeMessage'] : "")?></textarea>
												<input type="hidden" name="text1lang" value=<?=$lang1ID?>>
											</div>
										</div>
										<div class="control-group">
											<label for="silverMessage1" class="control-label"><?=silverMessage?></label>
											<div class="controls">
												<textarea name="silverMessage1" rows="4" id="silverMessage1" class="input-block-level"><?=(isset($dr_l1['silverMessage']) ? $dr_l1['silverMessage'] : "")?></textarea>
											</div>
										</div>
										<div class="control-group">
											<label for="goldMessage1" class="control-label"><?=goldMessage?></label>
											<div class="controls">
												<textarea name="goldMessage1" rows="4" id="goldMessage1" class="input-block-level"><?=(isset($dr_l1['goldMessage']) ? $dr_l1['goldMessage'] : "")?></textarea>
											</div>
										</div>
									</div>
									<? } ?>
									<? if(strlen($lang2)>1) {?>
									<div class="tab-pane" id="lang2">
										<div class="control-group">
											<label for="welcomeMessage2" class="control-label"><?=newCustomerMessage?></label>
											<div class="controls">
												<textarea name="welcomeMessage2" rows="4" id="welcomeMessage2" class="input-block-level"><?=(isset($dr_l2['welcomeMessage']) ? $dr_l2['welcomeMessage'] : "")?></textarea>
												<input type="hidden" name="text2lang" value=<?=$lang2ID?>>
											</div>
										</div>
										<div class="control-group">
											<label for="silverMessage2" class="control-label"><?=silverMessage?></label>
											<div class="controls">
												<textarea name="silverMessage2" rows="4" id="silverMessage2" class="input-block-level"><?=(isset($dr_l2['silverMessage']) ? $dr_l2['silverMessage'] : "")?></textarea>
											</div>
										</div>
										<div class="control-group">
											<label for="goldMessage2" class="control-label"><?=goldMessage?></label>
											<div class="controls">
												<textarea name="goldMessage2" rows="4" id="goldMessage2" class="input-block-level"><?=(isset($dr_l2['goldMessage']) ? $dr_l2['goldMessage'] : "")?></textarea>
											</div>
										</div>
									</div>
									<? } ?>
									<? if(strlen($lang3)>1) {?>
									<div class="tab-pane" id="lang3">
										<div class="control-group">
											<label for="welcomeMessage3" class="control-label"><?=newCustomerMessage?></label>
											<div class="controls">
												<textarea name="welcomeMessage3" rows="4" id="welcomeMessage3" class="input-block-level"><?=(isset($dr_l3['welcomeMessage']) ? $dr_l3['welcomeMessage'] : "")?></textarea>
												<input type="hidden" name="text3lang" value=<?=$lang3ID?>>
											</div>
										</div>
										<div class="control-group">
											<label for="silverMessage3" class="control-label"><?=silverMessage?></label>
											<div class="controls">
												<textarea name="silverMessage3" rows="4" id="silverMessage3" class="input-block-level"><?=(isset($dr_l3['silverMessage']) ? $dr_l3['silverMessage'] : "")?></textarea>
											</div>
										</div>
										<div class="control-group">
											<label for="goldMessage3" class="control-label"><?=goldMessage?></label>
											<div class="controls">
												<textarea name="goldMessage3" rows="4" id="goldMessage3" class="input-block-level"><?=(isset($dr_l3['goldMessage']) ? $dr_l3['goldMessage'] : "")?></textarea>
											</div>
										</div>
									</div>
									<? } ?>
									<? if(strlen($lang4)>1) {?>
									<div class="tab-pane" id="lang4">
										<div class="control-group">
											<label for="welcomeMessage4" class="control-label"><?=newCustomerMessage?></label>
											<div class="controls">
												<textarea name="welcomeMessage4" rows="4" id="welcomeMessage4" class="input-block-level"><?=(isset($dr_l4['welcomeMessage']) ? $dr_l4['welcomeMessage'] : "")?></textarea>
												<input type="hidden" name="text4lang" value=<?=$lang4ID?>>
											</div>
										</div>
										<div class="control-group">
											<label for="silverMessage4" class="control-label"><?=silverMessage?></label>
											<div class="controls">
												<textarea name="silverMessage4" rows="4" id="silverMessage4" class="input-block-level"><?=(isset($dr_l4['silverMessage']) ? $dr_l4['silverMessage'] : "")?></textarea>
											</div>
										</div>
										<div class="control-group">
											<label for="goldMessage4" class="control-label"><?=goldMessage?></label>
											<div class="controls">
												<textarea name="goldMessage4" rows="4" id="goldMessage4" class="input-block-level"><?=(isset($dr_l4['goldMessage']) ? $dr_l4['goldMessage'] : "")?></textarea>
											</div>
										</div>
									</div>
									<? } ?>
								</div>
							</div>
						</div>
						<br><br>
							
						</div>
                        
                        <div class="tab-pane" id="device">
                             <div class="control-group">
                                <label for="pw" class="control-label right"><?=title?></label>
                                <div class="controls">
                                    <input type="text" name="title" id-"title" class='input-xlarge' value="<?=(isset($dr_e["title"]) ? $dr_e["title"] : '')?>">
                                </div>
                            </div>
                             <div class="control-group">
                                <label for="pw" class="control-label right"><?=subtitle?></label>
                                <div class="controls">
                                    <input type="text" name="subtitle" id="subtitle" class='input-xlarge' value="<?=(isset($dr_e["subtitle"]) ? $dr_e["subtitle"] : '')?>">
                                </div>
                            </div>
							<hr/>
                            <div class="controls">
                                <span class="help-block"><?=backColor?></span>
                                <div class="input-append color colorpick" data-color="<?=(isset($dr_e["color_back"]) ? $dr_e["color_back"] : "#2e3842")?>" data-color-format="hex">
                                    <input type="text" class="span12" id="color_back" name="color_back" value="<?=(isset($dr_e['color_back']) ? $dr_e['color_back'] : '#2e3842')?>">
                                    <span class="add-on"><i style="background-color: <?=(isset($dr_e['color_back']) ? $dr_e['color_back'] : '#2e3842')?>;"></i></span>
                                </div>
                            </div>
                            
                            <div class="controls">
                                <span class="help-block"><?=titleColor?></span>
                                <div class="input-append color colorpick" data-color="<?=(isset($dr_e["color_title"]) ? $dr_e["color_title"] : "#ffffff")?>)" data-color-format="hex">
                                    <input type="text" class="span12" name="color_title" value="<?=(isset($dr_e["color_title"]) ? $dr_e["color_title"] : "#ffffff")?>">
                                    <span class="add-on"><i style="background-color: <?=(isset($dr_e["color_title"]) ? $dr_e["color_title"] : "#ffffff")?>;"></i></span>
                                </div>
                            </div>

                            <div class="controls">
                                <span class="help-block"><?=compainColor?></span>
                                <div class="input-append color colorpick" data-color="<?=(isset($dr_e["color_campain"]) ? $dr_e["color_campain"] : "#ed008c")?>" data-color-format="hex">
                                    <input type="text" class="span12" name="color_campain" value="<?=(isset($dr_e["color_campain"]) ? $dr_e["color_campain"] : "#ed008c")?>">
                                    <span class="add-on"><i style="background-color: <?=(isset($dr_e["color_campain"]) ? $dr_e["color_campain"] : "#ed008c")?>;"></i></span>
                                </div>
                            </div>
                            
                            <div class="controls">
                                <span class="help-block"><?=subtitleColor?></span>
                                <div class="input-append color colorpick" data-color="<?=(isset($dr_e["color_subtitle"]) ? $dr_e["color_subtitle"] : "#ed008c")?>" data-color-format="hex">
                                    <input type="text" class="span12" name="color_subtitle" value="<?=(isset($dr_e["color_subtitle"]) ? $dr_e["color_subtitle"] : "#ed008c")?>">
                                    <span class="add-on"><i style="background-color: <?=(isset($dr_e["color_subtitle"]) ? $dr_e["color_subtitle"] : "#ed008c")?>;"></i></span>
                                </div>
                            </div>
                            
                            <div class="controls">
                                <span class="help-block"><?=buttonColor?></span>
                                <div class="input-append color colorpick" data-color="<?=(isset($dr_e["color_button"]) ? $dr_e["color_button"] : "#ed4933")?>" data-color-format="hex">
                                    <input type="text" class="span12" name="color_button" value="<?=(isset($dr_e["color_button"]) ? $dr_e["color_button"] : "#ed4933")?>">
                                    <span class="add-on"><i style="background-color: <?=(isset($dr_e["color_button"]) ? $dr_e["color_button"] : "#ed4933")?>;"></i></span>
                                </div>
                            </div>
                            
                            <div class="controls">
                                <span class="help-block"><?=buttonTextColor?></span>
                                <div class="input-append color colorpick" data-color="<?=(isset($dr_e["color_button_text"]) ? $dr_e["color_button_text"] : "#ffffff")?>" data-color-format="hex">
                                    <input type="text" class="span12" name="color_button_text" value="<?=(isset($dr_e["color_button_text"]) ? $dr_e["color_button_text"] : "#ffffff")?>">
                                    <span class="add-on"><i style="background-color: <?=(isset($dr_e["color_button_text"]) ? $dr_e["color_button_text"] : "#ffffff")?>;"></i></span>
                                </div>
                            </div>
							
                            <div class="control-group">
                                <label for="textfield" class="control-label">Banner</label>
                                <div class="controls">
                                    <input type="text" name="banner" id="banner" class="input-xxlarge" value="<?=(isset($dr_e["banner"]) ? $dr_e["banner"] : "")?>">
                                </div>
                            </div>
                            
							<? if($auth->UserType == "Administrator") { ?>
                            <div class="control-group">
                                <label for="textarea" class="control-label"><?=page1?></label>
                                <div class="controls">
                                    <textarea name="page1" rows="10" id="page1" class="input-block-level"><?=(isset($dr_e["page1"]) ? $dr_e["page1"] : "")?></textarea>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label for="textarea" class="control-label"><?=page2?></label>
                                <div class="controls">
                                    <textarea name="page2" rows="10" id="page2" class="input-block-level"><?=(isset($dr_e["page2"]) ? $dr_e["page2"] : "")?></textarea>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label for="textarea" class="control-label"><?=page3?></label>
                                <div class="controls">
                                    <textarea name="page3" rows="10" id="page3" class="input-block-level"><?=(isset($dr_e["page3"]) ? $dr_e["page3"] : "")?></textarea>
                                </div>
                            </div>
                             
                            <div class="control-group">
                                <label for="textfield" class="control-label"><?=contactFormMail?></label>
                                <div class="controls">
                                    <input type="text" name="contactTo" id="contactTo" class="input-xxlarge" value="<?=(isset($dr_e["contactTo"]) ? $dr_e["contactTo"] : "")?>">
                                </div>
                            </div>
                            <? } ?>
							<hr/>
                            <div class="check-line">
                                <label class="inline" for="rate"><?=rate?></label>
                                <div class="controls">
                                    <input id="rate" name="rate" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["rate"]) && $dr_e["rate"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>
                            <div class="check-line">
                                <label class="inline" for="rate"><?=menu?></label>
                                <div class="controls">
                                    <input id="menu" name="menu" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["menu"]) && $dr_e["menu"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>
							<div class="control-group">
								<label for="select" class="control-label"><?=menuColor?></label>
								<div class="controls">
									<select name="catalog_color" id="catalog_color" class="input-large">
										<option value="red" <?=($dr_e["catalog_color"]=='red'?' selected':'')?>>Κόκκινο</option>
										<option value="pink" <?=($dr_e["catalog_color"]=='pink'?' selected':'')?>>Ρόζ</option>
										<option value="purple" <?=($dr_e["catalog_color"]=='purple'?' selected':'')?>>Μωβ</option>
										<option value="deep-purple" <?=($dr_e["catalog_color"]=='deep-purple'?' selected':'')?>>Βαθύ μωβ</option>
										<option value="indigo" <?=($dr_e["catalog_color"]=='indigo'?' selected':'')?>>Λουλάκι</option>
										<option value="blue" <?=($dr_e["catalog_color"]=='blue'?' selected':'')?>>Μπλε</option>
										<option value="light-blue" <?=($dr_e["catalog_color"]=='light-blue'?' selected':'')?>>Ανοιχτό μπλε</option>
										<option value="cyan" <?=($dr_e["catalog_color"]=='cyan'?' selected':'')?>>Κυανό</option>
										<option value="teal" <?=($dr_e["catalog_color"]=='teal'?' selected':'')?>>Βάσκας</option>
										<option value="green" <?=($dr_e["catalog_color"]=='green'?' selected':'')?>>Πράσινο</option>
										<option value="light-green" <?=($dr_e["catalog_color"]=='light-green'?' selected':'')?>>Ανοιχτό πράσινο</option>
										<option value="lime" <?=($dr_e["catalog_color"]=='lime'?' selected':'')?>>Λαιμ</option>
										<option value="yellow" <?=($dr_e["catalog_color"]=='yellow'?' selected':'')?>>Κίτρινο</option>
										<option value="amber" <?=($dr_e["catalog_color"]=='amber'?' selected':'')?>>Κεχριμπάρι</option>
										<option value="orange" <?=($dr_e["catalog_color"]=='orange'?' selected':'')?>>Πορτοκαλί</option>
										<option value="deep-orange" <?=($dr_e["catalog_color"]=='deep-orange'?' selected':'')?>>Βαθύ πορτοκαλί</option>
										<option value="brown" <?=($dr_e["catalog_color"]=='brown'?' selected':'')?>>Καφέ</option>
										<option value="blue-grey" <?=($dr_e["catalog_color"]=='blue-grey'?' selected':'')?>>Μπλε πράσινο</option>
										
									</select>
								</div>
							</div>
							<hr/>
                            <div class="controls">
                                <a href="#" onClick="cm('DELETELOGO',1,0,'');">   <button type="button"><?=delLogo?></button></a>
                                <input name="fileToUpload" id="fileToUpload"  style="visibility:none;" type="file">
                                <label for="fileToUpload"><span> <?=companyImgDetails?></span></label>
                                <span class="help-block">
                                <? if(isset($dr_e["files"]) && $dr_e["files"]!="") {
                                ?>
                                    <img src='/gallery/customer_logo/<?=$dr_e['user_id']?>/<?=$dr_e["files"]?>' style="width:140px;"/></spam>
                                <? }  //else { echo "Δεν έχει οριστεί αρχείο φωτογραφίας";} ?>
                            </div>
                            <div class="controls">
                                <a href="#" onClick="cm('DELETEBACKGROUND',1,0,'');"><button type="button"><?=delBackground?></button></a>
                                <input name="fileToUploadBackground" id="fileToUploadBackground"  style="visibility:none;" type="file">
                                <label for="fileToUploadBackground"><span> <?=delBackgroundDetails?></span></label>
                                <span class="help-block">
                                <? if(isset($dr_e["background"]) && $dr_e["background"]!="") {
                                ?>
                                    <img src='/gallery/customer_logo/<?=$dr_e['user_id']?>/<?=$dr_e["background"]?>' style="width:140px;"/></spam>
                                <? } ?>
                            </div>
                        </div> 
                        <? if($auth->UserType == "Administrator" || $auth->UserRow['is_admin']=='True') { ?>
                            <a href="#" onClick="cm('SAVE',1,0,'');">   <button type="button" class="btn btn-primary"><?=save?></button></a>
                            <a href="index.php?com=devices"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
                        <? } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?
    }
    else
    {
    ?>
        <div class="breadcrumbs">
            <ul>
                <li>
                    <a href="index.php"><?=homePage?></a>
                    <i class="icon-angle-right"></i>
                </li>
                <li>
                    <a href="<?=$BaseUrl?>"><?=devicesTitle?></a>
                </li>
            </ul>
        </div>
      <div class="row-fluid">
        <div class="span12">
            <div class="box box-color box-bordered">
                <div class="box-title">
                    <h3>
                        <i class="icon-table"></i>
                        <?=$config["navigation"]?>
                    </h3>
                </div>
                <div class="box-content nopadding">
                    <table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
                        <thead>
                            <tr>
                                <? echo ($auth->UserType=="Administrator" ? "<th>".customer."</th>" : ""); ?>
                                <th><?=friendlyName?></th>
                                <th class='hidden-480'><?=serial?></th>
                                <th class='hidden-480'><?=ssid?></th>
                                <th class='hidden-480'><?=uptime?></th>
                                <th class='hidden-480'><?=lastLogin?></th>
                                <th><?=action?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                                $filter=($auth->UserType!="Administrator" ? " AND (devices.user_id=".$auth->UserId.")":"");
                                //OR devices.user_id IN (SELECT parent FROM users WHERE user_id=".$auth->UserId."))":"");
                                //echo 'SELECT * FROM devices WHERE 1=1 '.$filter;
                                //SELECT * FROM devices WHERE 1=1 
                                $result = $db->sql_query("SELECT * FROM devices WHERE 1=1 ".$filter);
								//echo "SELECT * FROM devices WHERE 1=1 ".$filter;
                                if($db->sql_numrows($result) > 0)
                                {
                                    while ($dr = $db->sql_fetchrow($result))
                                    {
                                        ?>
                                         <tr>
                                            <? if($auth->UserType=="Administrator"){
                                            $dr_c=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$dr['user_id']);
                                            echo "<td>".$dr_c['company_name']."</td>";
                                            }
                                            ?>
                                            <td><?=$dr["friendlyName"]?></td>
                                            <td class='hidden-480'><?=$dr["mac"]?></td>
                                            <td class='hidden-480'><?=$dr["ssid"]?></td>
                                            <td class='hidden-480'><? //=seconds2human($dr["uptime"]*15)?> 
                                            <? 
                                            $start_date = new DateTime("now");
                                            $end_date = new DateTime($dr["last_device_uptime"]);
                                            $interval = $start_date->diff($end_date);
											$diff=date_diff($start_date,$end_date);
											$timeFirst  = ($start_date);
											$timeSecond = ($end_date);
											$differenceInSeconds = $timeSecond-$timeFirst;
											$timeDiff='';
											$timeDiff.=(intval($interval->format('%y'))>0?$interval->format('%y Years ') :'');
											$timeDiff.=(intval($interval->format('%m'))>0?$interval->format('%m Months ') :'');
											$timeDiff.=(intval($interval->format('%d'))>0?$interval->format('%d Days ') :'');
											$timeDiff.=(intval($interval->format('%h'))>0?$interval->format('%h Hours ') :'');
											$timeDiff.=$interval->format('%i minutes ago');
											//$timeDiff.=(intval($interval->format('%i')>0?'%i minutes ':'');
											//$minutes=$timeDiff;
                                            //$minutes = $interval->format('%y Yeasr %m Months %d Days %h Hours %i');
											$minutes=0;
											if (intval($interval->format('%y'))>0 || intval($interval->format('%m'))>0 || intval($interval->format('%d'))>0 || intval($interval->format('%h'))>0) $minutes=61;
                                            if ($minutes<60) { echo ' <img src="/images/right.png" style="width:15px;" rel="tooltip" data-placement="bottom" title="'.$timeDiff.'">'.seconds2human($dr["uptime"]*15); } else { echo ' <img src="/images/false.png" style="width:15px;" rel="tooltip" data-placement="bottom" title="'.$timeDiff.'">'.seconds2human($dr["uptime"]*15); } ?></td>
                                            <td class='hidden-480'><?=$dr["lastLogin"]?></td>
                                            <td>
                                                <!--<button class="btn btn-small btn-inverse"><i class="icon-trash"></i> Delete</button>-->
                                               <a style="padding:4px"  href="index.php?com=devices&item=<?=$dr["id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                                <? if($auth->UserType == "Administrator") { ?>
                                                <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=devices&Command=DELETE&item=<?=$dr["id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
                                                <? } ?>
                                               <a href="http://panel.spotyy.com/spotyy/index.php?deviceid=<?=$dr["mac"]?>&preview=1" target='_blank'><i class="icon-zoom-in"></i> <?=preview?></a>
                                            </td>
                                        </tr>
                                        <?
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?
    }
    ?>

    <script language="javascript">
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        //CKEDITOR.replace( 'newCustomerMsg' );
        //CKEDITOR.add;
        //CKEDITOR.replace( 'silverCustomerMsg' );
        //CKEDITOR.add;
        //CKEDITOR.replace( 'goldCustomerMsg' );
        //CKEDITOR.add;
        CKEDITOR.replace( 'remark' );
        CKEDITOR.add;
        CKEDITOR.replace( 'page1' );
        CKEDITOR.add;
        CKEDITOR.replace( 'page2' );
        CKEDITOR.add;
		CKEDITOR.replace( 'page3' );
        CKEDITOR.add;
        CKEDITOR.replace( 'welcomeMessage1' );
        CKEDITOR.add;
        CKEDITOR.replace( 'silverMessage1' );
        CKEDITOR.add;
        CKEDITOR.replace( 'goldMessage1' );
        CKEDITOR.add;
        CKEDITOR.replace( 'welcomeMessage2' );
        CKEDITOR.add;
        CKEDITOR.replace( 'silverMessage2' );
        CKEDITOR.add;
        CKEDITOR.replace( 'goldMessage2' );
        CKEDITOR.add;
        CKEDITOR.replace( 'welcomeMessage3' );
        CKEDITOR.add;
        CKEDITOR.replace( 'silverMessage3' );
        CKEDITOR.add;
        CKEDITOR.replace( 'goldMessage3' );
        CKEDITOR.add;
        CKEDITOR.replace( 'welcomeMessage4' );
        CKEDITOR.add;
        CKEDITOR.replace( 'silverMessage4' );
        CKEDITOR.add;
        CKEDITOR.replace( 'goldMessage4' );
        CKEDITOR.add;
    </script>
