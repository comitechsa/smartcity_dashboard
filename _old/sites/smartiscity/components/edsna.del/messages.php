<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
		include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
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

	if (!($permissions & $FLAG_CAMPAINS)) {
		$messages->addMessage("INVALID!!!");
		Redirect("index.php");
	}
//end permissions

$config["navigation"] = messagesTitle;
$nav = messagesTitle;
$BaseUrl = "index.php?com=messages";

$command=array();
$command=explode("&",$_POST["Command"]);
if($categ_id == "-1")
{
	Redirect("index.php");
}											

$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");

//if(isset($_REQUEST["Operation"]) )
//{
	if($_REQUEST["Command"] == "SAVE")
	{
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		
		if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
		{
			$PrimaryKeys["id"] = intval($_GET["item"]);
			$QuotFields["id"] = true;
			$item=$_GET['item'];
			$dr_user=$db->RowSelectorQuery("SELECT user_id FROM messages WHERE id=".$_GET["item"]);
			$userID=$dr_user['user_id'];
		} else {
			$item=$db->sql_nextid();
			$userID=($auth->UserType == "Administrator" ? $_POST["user_id"] : $auth->UserId);
		}


		//$Collector["user_id"] = ($auth->UserType == "Administrator" ? $_POST["user_id"] : $auth->UserId);
		$Collector["user_id"] = $userID;
		$QuotFields["user_id"] = true;

		$Collector["friendlyName"] = $_POST["friendlyName"];
		$QuotFields["friendlyName"] = true;
		
		//$Collector["message"] = html_entity_decode($_POST['message'], ENT_QUOTES, "UTF-8");
		//$QuotFields["message"] = true;
		
		//$Collector["message2"] = html_entity_decode($_POST['message2'], ENT_QUOTES, "UTF-8");
		//$QuotFields["message2"] = true;
		
		//$Collector["message3"] = html_entity_decode($_POST['message3'], ENT_QUOTES, "UTF-8");
		//$QuotFields["message3"] = true;
		
		//$Collector["message4"] = html_entity_decode($_POST['message4'], ENT_QUOTES, "UTF-8");
		//$QuotFields["message4"] = true;

		//$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
		$Collector["is_valid"] = "True";
		$QuotFields["is_valid"] = true;
		
		$Collector["device"] = $_POST["device"];
		$QuotFields["device"] = true;
		
		$Collector["last_change_user"] = $auth->UserId;
		$QuotFields["last_change_user"] = true;
		
		$Collector["lastUpdate"] = date('Y-m-d H:i:s');
		$QuotFields["lastUpdate"] = true;
		
		$db->ExecuteUpdater("messages",$PrimaryKeys,$Collector,$QuotFields);
		if(intval($_GET["item"])<1) {
			$item=$db->sql_nextid();
		} else {
			$item=$_GET["item"];
		}
		
		// update languages
		//lang1
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		if(isset($_POST["message1lang"]) && intval($_POST["message1lang"]) >0)
		{
			$drCheck1=$db->RowSelectorQuery("SELECT id FROM messages2l WHERE message_id=".$item." AND lang_id=".$_POST['message1lang']);
			if(intval($drCheck1['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck1['id']);
				$QuotFields["id"] = true;
			}
			$Collector["message"] = html_entity_decode($_POST['message1'], ENT_QUOTES, "UTF-8");
			$QuotFields["message"] = true;
			$Collector["lang_id"] = $_POST["message1lang"];
			$QuotFields["lang_id"] = true;
			$Collector["message_id"] = $item;
			$QuotFields["message_id"] = true;
			
			$db->ExecuteUpdater("messages2l",$PrimaryKeys,$Collector,$QuotFields);
		}
		//lang2
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		if(isset($_POST["message2lang"]) && intval($_POST["message2lang"]) >0)
		{
			$drCheck1=$db->RowSelectorQuery("SELECT id FROM messages2l WHERE message_id=".$item." AND lang_id=".$_POST['message2lang']);
			if(intval($drCheck1['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck1['id']);
				$QuotFields["id"] = true;
			}
			$Collector["message"] = html_entity_decode($_POST['message2'], ENT_QUOTES, "UTF-8");
			$QuotFields["message"] = true;
			$Collector["lang_id"] = $_POST["message2lang"];
			$QuotFields["lang_id"] = true;
			$Collector["message_id"] = $item;
			$QuotFields["message_id"] = true;
			
			$db->ExecuteUpdater("messages2l",$PrimaryKeys,$Collector,$QuotFields);
		}
		//lang3
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		if(isset($_POST["message3lang"]) && intval($_POST["message3lang"]) >0)
		{
			$drCheck1=$db->RowSelectorQuery("SELECT id FROM messages2l WHERE message_id=".$item." AND lang_id=".$_POST['message3lang']);
			if(intval($drCheck1['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck1['id']);
				$QuotFields["id"] = true;
			}
			$Collector["message"] = html_entity_decode($_POST['message3'], ENT_QUOTES, "UTF-8");
			$QuotFields["message"] = true;
			$Collector["lang_id"] = $_POST["message3lang"];
			$QuotFields["lang_id"] = true;
			$Collector["message_id"] = $item;
			$QuotFields["message_id"] = true;
			
			$db->ExecuteUpdater("messages2l",$PrimaryKeys,$Collector,$QuotFields);
		}
		//lang4
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		if(isset($_POST["message4lang"]) && intval($_POST["message4lang"]) >0)
		{
			$drCheck1=$db->RowSelectorQuery("SELECT id FROM messages2l WHERE message_id=".$item." AND lang_id=".$_POST['message4lang']);
			if(intval($drCheck1['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck1['id']);
				$QuotFields["id"] = true;
			}
			$Collector["message"] = html_entity_decode($_POST['message4'], ENT_QUOTES, "UTF-8");
			$QuotFields["message"] = true;
			$Collector["lang_id"] = $_POST["message4lang"];
			$QuotFields["lang_id"] = true;
			$Collector["message_id"] = $item;
			$QuotFields["message_id"] = true;
			
			$db->ExecuteUpdater("messages2l",$PrimaryKeys,$Collector,$QuotFields);
		}

		//if($auth->UserRow['parent']!=0){
			$findRootUser=$db->RowSelectorQuery("SELECT user_id,parent FROM users WHERE user_id=".$userID); //$auth->UserRow['parent']);
			$rootUser=($findRootUser['parent']==0?$findRootUser['user_id']:$findRootUser['parent']);
			
			//$rootUser=$findRootUser['user_id'];
		//} else{
			//$rootUser=$auth->UserId;
		//}	
		
		if($_POST['is_default']=="on") {
			//$db->sql_query("UPDATE messages SET is_default='False', lastUpdate='".date('Y-m-d H:i:s')."' WHERE user_id='".($auth->UserType=='Administrator'?$_POST["user_id"]."'":$rootUser."' OR user_id IN (SELECT user_id FROM users WHERE parent=".$rootUser.")"));
			//$db->sql_query("UPDATE messages SET is_default='True', lastUpdate='".date('Y-m-d H:i:s')."' WHERE id=".$_GET["item"]);
			//$db->sql_query("UPDATE messages SET is_default='False', lastUpdate='".date('Y-m-d H:i:s')."' WHERE device='".$_POST["device"]."' AND (user_id='".($auth->UserType=='Administrator'?$_POST["user_id"]."'":$rootUser)."' OR user_id IN (SELECT user_id FROM users WHERE parent=".($auth->UserType=='Administrator'?$_POST["user_id"]."'":$rootUser)."))");
			$db->sql_query("UPDATE messages SET is_default='False', lastUpdate='".date('Y-m-d H:i:s')."' WHERE device='".$_POST["device"]."' AND (user_id='".$rootUser."' OR user_id IN (SELECT user_id FROM users WHERE parent=".$rootUser."))");

			//echo "UPDATE messages SET is_default='False', lastUpdate='".date('Y-m-d H:i:s')."' WHERE 1=1 AND device='".$_POST["device"]."' AND (user_id='".($auth->UserType=='Administrator'?$_POST["user_id"]."'":$rootUser."' OR user_id IN (SELECT user_id FROM users WHERE parent=".$rootUser."))");
			
			$db->sql_query("UPDATE messages SET is_default='True', lastUpdate='".date('Y-m-d H:i:s')."' WHERE id=".$item);			
		} else {
			$db->sql_query("UPDATE messages SET is_default='False', lastUpdate='".date('Y-m-d H:i:s')."' WHERE id=".$item);
		}
		$messages->addMessage("SAVED!!!");
		Redirect("index.php?com=messages");

	} else if($_POST["Command"] == "SAVEDEF") {

		$db->sql_query("UPDATE messages SET is_default='False', lastUpdate='".date('Y-m-d H:i:s')."' WHERE user_id=".$auth->UserId);
		$db->sql_query("UPDATE messages SET is_default='True', lastUpdate='".date('Y-m-d H:i:s')."' WHERE id=".$_POST["defaultmessageid"]);
		//$item=$db->RowSelectorQuery("SELECT id FROM defaultmessage WHERE user_id=".$auth->UserId);
		//if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
		//{
			//$PrimaryKeys["id"] = intval($_GET["item"]);
			//$PrimaryKeys["id"] = $item['id'];
			//$QuotFields["id"] = true;
		//}
		
		//$Collector["user_id"] = $auth->UserId;
		//$QuotFields["user_id"] = true;
			
		//$Collector["defaultmessageid"] = $_POST["defaultmessageid"];
		//$QuotFields["defaultmessageid"] = true;
		
		//$Collector["is_valid"] = "True";//($_POST["is_valid"]=="on" ? "True" : "False");
		//$QuotFields["is_valid"] = true;
		
		//$Collector["lastUpdate"] = date('Y-m-d H:i:s');
		//$QuotFields["lastUpdate"] = true;
		
		//$db->ExecuteUpdater("defaultmessage",$PrimaryKeys,$Collector,$QuotFields);
		$messages->addMessage("saved!!!");
		Redirect("index.php?com=messages");
	} else if($_REQUEST['Command'] == "DELETE")	{
		//$item=(isset($command[1]) && $command[1]!="")? $command[1]:"";
		if($item != "")
		{
			$db->sql_query("DELETE FROM messages WHERE id=" . $item);
			$db->sql_query("DELETE FROM messages2l WHERE message_id=" . $item);
			$messages->addMessage("DELETED!!!");
			Redirect("index.php?com=messages");
		}
	}
//}

?>
		<?

        if(isset($_GET["item"]))
        {

			if($_GET["item"]=="") {
				$userID=$auth->UserId;
				if($auth->UserRow['parent']!=0){
					$findRootUser=$db->RowSelectorQuery("SELECT user_id FROM users WHERE user_id=".$auth->UserRow['parent']);
					$rootUser=$findRootUser['user_id'];
				} else{
					$rootUser=$auth->UserId;
				}
			} else {
				if($auth->UserType == "Administrator") {
					$dr_user=$db->RowSelectorQuery("SELECT user_id FROM messages WHERE id=".$_GET["item"]);
					//$userID=$auth->UserId;
					$userID=$dr_user['user_id'];
					//$filter="";
				} else {
					$userID=$auth->UserId;
				}
							
				if($auth->UserRow['parent']!=0){
					$findRootUser=$db->RowSelectorQuery("SELECT user_id FROM users WHERE user_id=".$auth->UserRow['parent']);
					$rootUser=$findRootUser['user_id'];
				} else{
					$rootUser=$auth->UserId;
				}
				$filter = ($auth->UserType != "Administrator" ? " AND (messages.user_id=".$rootUser." OR messages.user_id IN (SELECT user_id FROM users WHERE parent=".$rootUser."))" :'');
				//$filter=" AND user_id=".$userID;
				$query="SELECT * FROM messages WHERE 1=1 ".$filter." AND id=".$_GET['item'];
				$dr_e = $db->RowSelectorQuery($query);
				if (!isset($dr_e["id"])) {
					$messages->addMessage("NOT FOUND!!!");
					Redirect("index.php?com=messages");
				}
			}
			//echo $userID;
			//exit;
			//$filter=($auth->UserType != "Administrator" ? " AND user_id=".$auth->UserId:"");
			//echo "SELECT * FROM users WHERE user_id=".$userID;
			//$lang1=$auth->UserRow['lang1ID'];
			//$lang2=$auth->UserRow['lang2ID'];
			//$lang3=$auth->UserRow['lang3ID'];
			//$lang4=$auth->UserRow['lang4ID'];
			$dr_messageUser=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$userID);
			$lang1ID=$dr_messageUser['lang1ID'];
			$lang2ID=$dr_messageUser['lang2ID'];
			$lang3ID=$dr_messageUser['lang3ID'];
			$lang4ID=$dr_messageUser['lang4ID'];

			if(intval($lang1ID)>0) $dr_l1 = $db->RowSelectorQuery("SELECT * FROM messages2l WHERE message_id = ".$dr_e['id']." AND lang_id = ".$lang1ID);
			if(intval($lang2ID)>0) $dr_l2 = $db->RowSelectorQuery("SELECT * FROM messages2l WHERE message_id = ".$dr_e['id']." AND lang_id = ".$lang2ID);
			if(intval($lang3ID)>0) $dr_l3 = $db->RowSelectorQuery("SELECT * FROM messages2l WHERE message_id = ".$dr_e['id']." AND lang_id = ".$lang3ID);
			if(intval($lang4ID)>0) $dr_l4 = $db->RowSelectorQuery("SELECT * FROM messages2l WHERE message_id = ".$dr_e['id']." AND lang_id = ".$lang4ID);

		//}
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
                <div class="box">
                    <div class="box-content">
                            <!--
							<div class="control-group">
								<label class="inline" for="is_valid"><? //=active?></label>
                                <div class="controls">
                                    <input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <? //=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>
                            -->
                            <div class="control-group">
								<label class="inline" for="is_default"><?=defaultChoice?></label>
                                <div class="controls">
                                    <input id="is_default" name="is_default" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_default"]) && $dr_e["is_default"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>
							
							<? if($auth->UserType == "Administrator") { ?> 
                            <div class="controls">
                                <label for="textfield" class="control-label"><?=customer?></label>
                                <?
                                    $query = "SELECT * FROM users WHERE is_valid='True' ORDER BY company_name"; // AND parent=0
                                    echo Select::GetDbRender("user_id", $query, "user_id", "company_name", (isset($dr_e["user_id"]) ? $dr_e["user_id"] : ""), true);
                                 ?>
                            </div> <? } //else { ?>
								
                            <div class="control-group">
								<label class="inline" for="device"><?=allDevices?></label>
                                <div class="controls">
								<?
									if($auth->UserType != "Administrator") { //Select device combo
										//$filter = ($auth->UserType != "Administrator" ? " AND (messages.user_id=".$rootUser." OR messages.user_id IN (SELECT user_id FROM users WHERE parent=".$rootUser."))" :'');
										$query="SELECT id,friendlyName FROM devices WHERE 1=1 AND (user_id=".$rootUser." OR user_id IN (SELECT user_id FROM users WHERE parent=".$rootUser."))";
									} else {
										$query="SELECT id,friendlyName FROM devices WHERE 1=1 ".$filter;
									}
									
									$result = $db->sql_query($query);
									if($db->sql_numrows($result) > 0) {
										echo "<select name='device' id='device' class='input-large' ".((isset($dr_e["device"]) && $dr_e["device"]=='0') ? 'selected':'').">";
										
										echo "<option value='0'>".allDevices."</option>";
											while ($dr = $db->sql_fetchrow($result))
											{
												echo "<option value=".$dr["id"]." ".((isset($dr_e["device"]) && $dr_e["device"]==$dr['id']) ? 'selected':'').">".$dr['friendlyName']."</option>";
											}
										echo "</select>";
									}
								
								?>
                                </div>
                            </div>
							<? //} ?>
                            <div class="control-group">
                                <label for="textfield" class="control-label"><?=friendlyName?></label>
                                <div class="controls">
                                    <input type="text" name="friendlyName" id="friendlyName" class="input-xxlarge" value="<?=(isset($dr_e["friendlyName"]) ? $dr_e["friendlyName"] : "")?>">
                                </div>
                            </div>
					<?
						//$dr1 = $db->RowSelectorQuery("SELECT t1.native_name, t1.lang_id FROM lang t1 INNER JOIN users t2 ON t1.lang_id=t2.lang1ID WHERE user_id=".$auth->UserId);
						//$dr2 = $db->RowSelectorQuery("SELECT t1.native_name, t1.lang_id FROM lang t1 INNER JOIN users t2 ON t1.lang_id=t2.lang2ID WHERE user_id=".$auth->UserId);
						//$dr3 = $db->RowSelectorQuery("SELECT t1.native_name, t1.lang_id FROM lang t1 INNER JOIN users t2 ON t1.lang_id=t2.lang3ID WHERE user_id=".$auth->UserId);
						//$dr4 = $db->RowSelectorQuery("SELECT t1.native_name, t1.lang_id FROM lang t1 INNER JOIN users t2 ON t1.lang_id=t2.lang4ID WHERE user_id=".$auth->UserId);

						if(intval($lang1ID)>0) $dr1 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang1ID);
						if(intval($lang1ID)>0) $lang1=$dr1['native_name'];
						if(intval($lang2ID)>0) $dr2 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang2ID);
						if(intval($lang2ID)>0) $lang2=$dr2['native_name'];
						if(intval($lang3ID)>0) $dr3 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang3ID);
						if(intval($lang3ID)>0) $lang3=$dr3['native_name'];
						if(intval($lang4ID)>0) $dr4 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang4ID);
						if(intval($lang4ID)>0) $lang4=$dr4['native_name'];
					?>
                    <div class="box-content nopadding">
                        <ul class="tabs tabs-inline tabs-top">
                            <li class='active'>
                                <a href="#lang1" data-toggle='tab'><i class="icon-user"></i> <?=$lang1?></a>
                            </li>
							<? if(strlen($lang2)>1) {?>
                            <li>
                                <a href="#lang2" data-toggle='tab'><i class="icon-user"></i> <?=$lang2?></a>
                            </li>
							<? } if(strlen($lang3)>1) {?>
                            <li>
                                <a href="#lang3" data-toggle='tab'><i class="icon-user"></i> <?=$lang3?></a>
                            </li>
							<? } if(strlen($lang4)>1) {?>
                            <li>
                                <a href="#lang4" data-toggle='tab'><i class="icon-user"></i> <?=$lang4?></a>
                            </li>
							<? } ?>
                        </ul>
                        <div class="tab-content padding tab-content-inline tab-content-bottom">
                            <div class="tab-pane active" id="lang1">
								<div class="control-group">
									<label for="textarea" class="control-label"><?=message?></label>
									<!--
									<div class="controls">
										<textarea name="message" rows="6" id="message" class="input-block-level"><? //=(isset($dr_e["message"]) ? $dr_e["message"] : "")?></textarea>
									</div>
									-->
									<div class="controls">
										<textarea name="message1" rows="6" id="message1" class="input-block-level"><?=(isset($dr_l1["message"]) ? $dr_l1["message"] : "")?></textarea>
										<input type="hidden" name="message1lang" value=<?=$lang1ID?>>
									</div>
								</div>
							</div>
							<? if(strlen($lang2)>1) {?>
                            <div class="tab-pane" id="lang2">
								<div class="control-group">
									<label for="textarea" class="control-label"><?=message?></label>
									<!--
									<div class="controls">
										<textarea name="message2" rows="6" id="message2" class="input-block-level"><? //=(isset($dr_e["message2"]) ? $dr_e["message2"] : "")?></textarea>
									</div>
									-->
									<div class="controls">
										<textarea name="message2" rows="6" id="message2" class="input-block-level"><?=(isset($dr_l2["message"]) ? $dr_l2["message"] : "")?></textarea>
										<input type="hidden" name="message2lang" value=<?=$lang2ID?>>
									</div>
								</div>
							</div>
							<? } ?>
							<? if(strlen($lang3)>1) {?>
                            <div class="tab-pane" id="lang3">
								<div class="control-group">
									<label for="textarea" class="control-label"><?=message?></label>
									<!--
									<div class="controls">
										<textarea name="message3" rows="6" id="message3" class="input-block-level"><? //=(isset($dr_e["message3"]) ? $dr_e["message3"] : "")?></textarea>
									</div>
									-->
									<div class="controls">
										<textarea name="message3" rows="6" id="message3" class="input-block-level"><?=(isset($dr_l3["message"]) ? $dr_l3["message"] : "")?></textarea>
										<input type="hidden" name="message3lang" value=<?=$lang3ID?>>
									</div>
								</div>
							</div>
							<? } ?>
							<? if(strlen($lang4)>1) {?>
                            <div class="tab-pane" id="lang4">
								<div class="control-group">
									<label for="textarea" class="control-label"><?=message?></label>
									<!--
									<div class="controls">
										<textarea name="message4" rows="6" id="message4" class="input-block-level"><? //=(isset($dr_e["message4"]) ? $dr_e["message4"] : "")?></textarea>
									</div>
									-->
									<div class="controls">
										<textarea name="message4" rows="6" id="message4" class="input-block-level"><?=(isset($dr_l4["message"]) ? $dr_l4["message"] : "")?></textarea>
										<input type="hidden" name="message4lang" value=<?=$lang4ID?>>
									</div>
								</div>
							</div>
							<? } ?>
						</div>

                            <!--<div class="control-group">
                                <label for="textfield" class="control-label">Από</label>
                                <div class="controls">
                                    <input type="text" name="fromdate" id="fromdate" class="input-medium datepick" data-date-format="yyyy-dd-mm" value="<?=(isset($dr_e["fromdate"]) ? $dr_e["fromdate"] : date('Y-d-m'))?>">
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="textfield" class="control-label">Έως</label>
                                <div class="controls">
                                    <input type="text" name="todate" id="todate" class="input-medium datepick" data-date-format="yyyy-dd-mm" value="<?=(isset($dr_e["todate"]) ? $dr_e["todate"] : date('Y-d-m'))?>">
                                </div>
                            </div>-->

                            <a href="#" onClick="cm('SAVE',1,0,'');"><button type="button" class="btn btn-primary"><?=save?></button></a></li>
							<a href="index.php?com=messages"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
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
                    <a href="<?=$BaseUrl?>"><?=$nav?></a>
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
									<th><?=active?></th>
                                    <? echo ($auth->UserType=="Administrator" ? "<th>".customer."</th>" : ""); ?>
                                    <th><?=friendlyName?></th>
									<th><?=device?></th>
                                    <!--<th class='hidden-480'><? //=active?></th>-->
                                    <th><?=action?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
									//$def = $db->RowSelectorQuery("SELECT defaultmessageid FROM defaultmessage WHERE user_id=".$auth->UserId);
									//$defVar=$def['defaultmessageid'];
									if($auth->UserRow['parent']!=0){
										//$findRootUser=$db->RowSelectorQuery("SELECT user_id FROM users WHERE user_id=".$auth->UserRow['parent']);
										//$rootUser=$findRootUser['user_id'];
										$rootUser=$auth->UserRow['parent'];
									} else{
										$rootUser=$auth->UserId;
									}
									$filter = ($auth->UserType != "Administrator" ? " AND (messages.user_id=".$rootUser." OR messages.user_id IN (SELECT user_id FROM users WHERE parent=".$rootUser."))" :'');

									$result = $db->sql_query("SELECT messages.*, devices.friendlyName AS deviceFriendlyName FROM messages LEFT JOIN devices ON messages.device=devices.id WHERE 1=1 ".$filter);
									//echo "SELECT messages.*, devices.friendlyName AS deviceFriendlyName FROM messages LEFT JOIN devices ON messages.device=devices.id WHERE 1=1 ".$filter;
									//echo "SELECT messages.*, devices.friendlyName AS deviceFriendlyName FROM messages LEFT JOIN devices ON messages.device=devices.id WHERE 1=1 ".$filter;
									//echo "SELECT messages.*, devices.friendlyName AS deviceFriendlyName FROM messages LEFT JOIN devices ON messages.device=devices.id WHERE 1=1 ".$filter;
                                    if($db->sql_numrows($result) > 0)
                                    {
                                        while ($dr = $db->sql_fetchrow($result))
                                        {
                                            ?>
                                             <tr>
                                                <? //if($auth->UserType!="Administrator"){ ?>
												<!--<td><input type="radio" name="defaultmessageid" value="<? //=$dr['id']?>" <? //=($dr['is_default']=='True'?'checked':'')?>></td>-->
                                                <? //} else { echo '<td>'.$dr['is_default'].'</td>';}?>
												<td><?=($dr['is_default']=='True'?'True':'')?></td>
                                                <? if($auth->UserType=="Administrator"){
												$dr_c=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$dr['user_id']);
												echo "<td>".$dr_c['company_name']."</td>";
												}
												?>
                                                <td><?=$dr["friendlyName"]?></td>
												<td><?=$dr["deviceFriendlyName"]?></td>
                                                <!-- <td><? //=$dr["fromdate"]?></td>
                                                <td><? //=$dr["todate"]?></td> -->
                                                <!--<td class='hidden-480'><? //=$dr["is_valid"]?></td>-->
                                                <td>
                                                    <a style="padding:4px"  href="index.php?com=messages&Operation=EDIT&item=<?=$dr["id"]?>"><i class="icon-edit"></i> <?=edit?></a>
													<a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=messages&Command=DELETE&item=<?=$dr["id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
												</td>
                                            </tr>
                                            <?
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <? //if($auth->UserType!="Administrator"){ ?>
                    <!--<div class="form-actions">
                        <button type="submit" class="btn btn-primary" name="Command" value="SAVEDEF"><? //=save?></button>
                    </div> -->
                    <? //} ?>
                </div>
            </div>
        </div>
        <?
        }
        ?>
                            
<script language="javascript">
	// Replace the <textarea id="editor1"> with a CKEditor
	// instance, using default configuration.
        //CKEDITOR.replace( 'message' );
        //CKEDITOR.add;
        CKEDITOR.replace( 'message1' );
        CKEDITOR.add;
        CKEDITOR.replace( 'message2' );
        CKEDITOR.add;
        CKEDITOR.replace( 'message3' );
        CKEDITOR.add;
        CKEDITOR.replace( 'message4' );
        CKEDITOR.add;
</script>
