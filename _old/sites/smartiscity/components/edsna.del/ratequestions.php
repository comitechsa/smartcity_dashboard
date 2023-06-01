<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
//require_once(dirname(__FILE__) . "/common.php");
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

global $nav;
$nav = 'Ερωτήσεις';
$config["navigation"] = 'Ερωτήσεις';
$set = (intval($_GET["set"]) > 0 ? intval($_GET["set"]) : "");

if(intval($_GET["set"]) <= 0){
	Redirect('index.php?com=ratequestionnaires');	
}

//Check for valid set
$userID=$auth->UserId;
$rootUser=($auth->UserRow['parent']!=0?$auth->UserRow['parent']:$userID=$auth->UserId);
$filter=" AND id=".$set." AND (user_id=".$userID." OR user_id=".$rootUser."  OR user_id IN(SELECT user_id FROM users WHERE parent=".$rootUser."))";
$query="SELECT * FROM  rate_questionnaires WHERE 1=1 ".$filter;

$drCheck = $db->RowSelectorQuery($query);
if (!isset($drCheck["id"])) {
	$messages->addMessage("NOT FOUND!!!");
	Redirect("index.php?com=ratequestionnaires");
}
	
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=ratequestions".(isset($_GET['set'])?'&set='.$_GET['set']:'');
$command=array();
$command=explode("&",$_POST["Command"]);

if(isset($_REQUEST["Command"]))
{	
	if($_REQUEST["Command"] == "SAVE")
	{
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		
		if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
		{
			$item=$_GET["item"];
			$PrimaryKeys["question_id"] = intval($_GET["item"]);
			$QuotFields["question_id"] = true;
		} else {
			$item=$db->sql_nextid();
			$Collector["user_id"] = ($auth->UserType == "Administrator" ? $_POST["user_id"] : $auth->UserId);
			$QuotFields["user_id"] = true;
		}
		
		$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
		$QuotFields["is_valid"] = true;

		$Collector["questionnaire"] = $set;
		$QuotFields["questionnaire"] = true;
		
		$Collector["priority"] = $_POST["priority"];
		$QuotFields["priority"] = true;
	
		$Collector["question_name"] = $_POST["question_name"];
		$QuotFields["question_name"] = true;
		
		//$Collector["question_description"] = html_entity_decode($_POST['product_description'], ENT_QUOTES, "UTF-8");
		//$QuotFields["question_description"] = true;
		
		$Collector["category_id"] = $_POST["category_id"];
		$QuotFields["category_id"] = true;
		
		//$Collector["user_id"] = ($auth->UserType == "Administrator" ? $_POST["user_id"] : $auth->UserId);
		//$QuotFields["user_id"] = true;
		
		//$Collector["language_id"] = 1; //$_POST["language_id"];
		//$QuotFields["language_id"] = true;
		
		$Collector["last_change"] = date('Y-m-d H:i:s');
		$QuotFields["last_change"] = true;
		
		$Collector["last_change_user"] = $auth->UserId;
		$QuotFields["last_change_user"] = true;
		
		$db->ExecuteUpdater("rate_questions",$PrimaryKeys,$Collector,$QuotFields);
		/*
		if(intval($_GET["item"])<1) {
			$item=$db->sql_nextid();
		} else {
			$item=$_GET["item"];
		}			
		*/
		
		// update languages
		//lang1
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		if(isset($_POST["question1lang"]) && intval($_POST["question1lang"]) >0)
		{
			$drCheck1=$db->RowSelectorQuery("SELECT id FROM rate_questions2l WHERE question_id=".$item." AND lang_id=".$_POST['question1lang']);
			if(intval($drCheck1['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck1['id']);
				$QuotFields["id"] = true;
			}
			$Collector["question_name"] = $_POST["question_name1"];
			$QuotFields["question_name"] = true;
			$Collector["question_description"] = html_entity_decode($_POST['question_description1'], ENT_QUOTES, "UTF-8");
			$QuotFields["question_description"] = true;
			$Collector["question_id"] = $item;
			$QuotFields["question_id"] = true;
			$Collector["lang_id"] = $_POST["question1lang"];
			$QuotFields["lang_id"] = true;
			$db->ExecuteUpdater("rate_questions2l",$PrimaryKeys,$Collector,$QuotFields);
		}
		//lang2
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		if(isset($_POST["question2lang"]) && intval($_POST["question2lang"]) >0)
		{
			$drCheck2=$db->RowSelectorQuery("SELECT id FROM rate_questions2l WHERE question_id=".$item." AND lang_id=".$_POST['question2lang']);
			if(intval($drCheck2['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck2['id']);
				$QuotFields["id"] = true;
			}
			$Collector["question_name"] = $_POST["question_name2"];
			$QuotFields["question_name"] = true;
			$Collector["question_description"] = html_entity_decode($_POST['question_description2'], ENT_QUOTES, "UTF-8");
			$QuotFields["question_description"] = true;
			$Collector["question_id"] = $item;
			$QuotFields["question_id"] = true;
			$Collector["lang_id"] = $_POST["question2lang"];
			$QuotFields["lang_id"] = true;
			$db->ExecuteUpdater("rate_questions2l",$PrimaryKeys,$Collector,$QuotFields);
		}
		//lang3
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		if(isset($_POST["question3lang"]) && intval($_POST["question3lang"]) >0)
		{
			$drCheck3=$db->RowSelectorQuery("SELECT id FROM rate_questions2l WHERE question_id=".$item." AND lang_id=".$_POST['question3lang']);
			if(intval($drCheck3['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck3['id']);
				$QuotFields["id"] = true;
			}
			$Collector["question_name"] = $_POST["question_name3"];
			$QuotFields["question_name"] = true;
			$Collector["question_description"] = html_entity_decode($_POST['question_description3'], ENT_QUOTES, "UTF-8");
			$QuotFields["question_description"] = true;
			$Collector["question_id"] = $item;
			$QuotFields["question_id"] = true;
			$Collector["lang_id"] = $_POST["question3lang"];
			$QuotFields["lang_id"] = true;
			$db->ExecuteUpdater("rate_questions2l",$PrimaryKeys,$Collector,$QuotFields);
		}
		//lang4
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		if(isset($_POST["question4lang"]) && intval($_POST["question4lang"]) >0)
		{
			$drCheck4=$db->RowSelectorQuery("SELECT id FROM rate_questions2l WHERE question_id=".$item." AND lang_id=".$_POST['question4lang']);
			if(intval($drCheck4['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck4['id']);
				$QuotFields["id"] = true;
			}
			$Collector["question_name"] = $_POST["question_name4"];
			$QuotFields["question_name"] = true;
			$Collector["question_description"] = html_entity_decode($_POST['question_description4'], ENT_QUOTES, "UTF-8");
			$QuotFields["question_description"] = true;
			$Collector["question_id"] = $item;
			$QuotFields["question_id"] = true;
			$Collector["lang_id"] = $_POST["question4lang"];
			$QuotFields["lang_id"] = true;
			$db->ExecuteUpdater("rate_questions2l",$PrimaryKeys,$Collector,$QuotFields);
		}
		
		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") { //$command[0] ==
		if($item != "")
		{
			//$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
			//Ο χρήστης να είναι διαχειριστής του account, δημιουργός της κατηγορίας ή διαχειριστής
			if($auth->UserType != "Administrator"){
				$filter=($auth->UserRow['parent']!=0?" AND user_id=".$auth->UserRow['user_id']:" AND (user_id=".$auth->UserRow['user_id']." OR user_id IN(SELECT user_id FROM users WHERE parent=".$auth->UserRow['user_id']."))");
			}
			$query="SELECT * FROM rate_questions WHERE 1=1 ".$filter." AND question_id=".$_GET['item'];
			
			$drAccess=$db->RowSelectorQuery($query);
			if(!isset($drAccess['question_id'])) {
				$messages->addMessage("ACCESS RESTRICTED!!!");
				Redirect($BaseUrl);
			}
			//Πρέπει να γίνει έλεγχος αν υπάρχουν απαντήσεις στις απαντήσεις των χρηστών
			//$checkDelete=$db->sql_query("SELECT * FROM rates WHERE question_id=".$item);
			//if ($db->sql_numrows($checkDelete)<1) {
			//	$messages->addMessage(errorRecordsFound);
			//	Redirect($BaseUrl);				
			//}

			$db->sql_query("DELETE FROM rate_questions WHERE question_id=" . $item);
			$db->sql_query("DELETE FROM rate_questions2l WHERE question_id=" . $item);
			$messages->addMessage("DELETE!!!");
			Redirect($BaseUrl);
		}
	}
}

if(isset($_GET["item"])) {
	if($_GET["item"]=="") {
		$userID=$auth->UserId;
		$rootUser=($auth->UserRow['parent']!=0?$auth->UserRow['parent']:$userID=$auth->UserId);
	} else {
		if($auth->UserType == "Administrator") {
			$dr_user=$db->RowSelectorQuery("SELECT user_id FROM rate_questions WHERE question_id=".$_GET["item"]);
			$userID=$dr_user['user_id'];
			//$filter="";
		} else {
			$userID=$auth->UserId;
			$rootUser=($auth->UserRow['parent']!=0?$auth->UserRow['parent']:$userID=$auth->UserId);
		}
		
		if($auth->UserType != "Administrator"){
			$filter=" AND (user_id=".$auth->UserRow['user_id']." OR user_id=".$rootUser."  OR user_id IN(SELECT user_id FROM users WHERE parent=".$rootUser."))";
		}
		$query="SELECT * FROM rate_questions WHERE 1=1 ".$filter." AND question_id=".$_GET['item'];
			
		$dr_e = $db->RowSelectorQuery($query);
		if (!isset($dr_e["question_id"])) {
			$messages->addMessage("NOT FOUND!!!");
			Redirect("index.php?com=ratequestions&set=".$set);
		}
	}
	//$query="SELECT * FROM products WHERE question_id=".$_GET['item'];
	//$dr_e = $db->RowSelectorQuery($query);
	$dr_User=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$userID);
	$lang1ID=$dr_User['lang1ID'];
	$lang2ID=$dr_User['lang2ID'];
	$lang3ID=$dr_User['lang3ID'];
	$lang4ID=$dr_User['lang4ID'];

	if(intval($lang1ID)>0) $dr_l1 = $db->RowSelectorQuery("SELECT * FROM rate_questions2l WHERE question_id = ".$dr_e['question_id']." AND lang_id = ".$lang1ID);
	if(intval($lang2ID)>0) $dr_l2 = $db->RowSelectorQuery("SELECT * FROM rate_questions2l WHERE question_id = ".$dr_e['question_id']." AND lang_id = ".$lang2ID);
	if(intval($lang3ID)>0) $dr_l3 = $db->RowSelectorQuery("SELECT * FROM rate_questions2l WHERE question_id = ".$dr_e['question_id']." AND lang_id = ".$lang3ID);
	if(intval($lang4ID)>0) $dr_l4 = $db->RowSelectorQuery("SELECT * FROM rate_questions2l WHERE question_id = ".$dr_e['question_id']." AND lang_id = ".$lang4ID);
	
	if(intval($lang1ID)>0) $dr1 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang1ID);
	if(intval($lang1ID)>0) $lang1=$dr1['native_name'];
	if(intval($lang2ID)>0) $dr2 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang2ID);
	if(intval($lang2ID)>0) $lang2=$dr2['native_name'];
	if(intval($lang3ID)>0) $dr3 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang3ID);
	if(intval($lang3ID)>0) $lang3=$dr3['native_name'];
	if(intval($lang4ID)>0) $dr4 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang4ID);
	if(intval($lang4ID)>0) $lang4=$dr4['native_name'];
	?>
	<!--
    <div class="breadcrumbs">
        <ul>
            <li>
                <a href="index.php"><? //=homePage?></a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="<? //=$BaseUrl?>"><? //=$nav?></a>
            </li>
        </ul>
    </div>
	-->
	<div class="breadcrumbs">
		<ul>
			<li>
				<a href="index.php"><?=homePage?></a>
				<i class="icon-angle-right"></i>
			</li>
			<li>
				<a href="index.php?com=ratequestionnaires"><?=ratequestionnaires?></a>
			</li>
			<li>
				<a href="index.php?com=ratequestions&set=<?=$_GET['set']?>"><?=currentSurvey?></a>
			</li>
		</ul>
	</div>
	
	<div class="row-fluid">
		<div class="span12">            
            <div class="box-title">
                <h3><i class="icon-user"></i><?=edit?></h3>
            </div>
            <div class="box-content nopadding">
                <div class="check-line">
                    <label class="inline" for="is_valid"><?=active?></label>
                    <div class="controls">
                        <input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>  />
                    </div>
                </div>
				
                <? if($auth->UserType == "Administrator") { ?> 
                <div class="controls">
                    <label for="textfield" class="control-label"><?=customer?></label>
                    <select name="user_id" id="user_id" onChange="getRateCategories(this.value);">
                        <option value=""><?=choice?></option>
                        <?
                        $query = "SELECT * FROM users WHERE is_valid='True' ORDER BY company_name";
                        $result = $db->sql_query($query);
                        while ($dr = $db->sql_fetchrow($result)) {?>
                            <option value="<?php echo $dr["user_id"]; ?>" <?=$dr_e['user_id']?><?=($dr_e['user_id']==$dr['user_id']?' selected':'')?>><?php echo $dr["company_name"]; ?></option>
                        <? } ?>
                    </select>
                </div> <? } ?>

                <div class="controls">
                    <label for="textfield" class="control-label"><?=category?></label>
                    <?
						//$filter=($auth->UserType!="Administrator"?" AND user_id=".$auth->UserId:"");
						if($auth->UserType!="Administrator"){
							$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
                        }
						
                        $query = "SELECT * FROM rate_categories WHERE is_valid='True' ".$filter." ORDER BY category_name";
                        echo Select::GetDbRender("category_id", $query, "category_id", "category_name", (isset($dr_e["category_id"]) ? $dr_e["category_id"] : ""), true);
                     ?>
                </div> 
				
                <div class="control-group">
                    <div class="controls">
                        <label for="textfield" class="control-label"><?=displayOrder?></label>
                        <input type="text" name="priority" id="priority" required class="input-xxlarge" <?=(isset($dr_e["priority"]) ? 'value="'.$dr_e['priority'].'"' : "")?> >
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <label for="textfield" class="control-label"><?=friendlyName?></label>
                        <input type="text" name="question_name" id="question_name" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["question_name"]) ? 'value="'.$dr_e['question_name'].'"' : "")?> >
                    </div>
                </div>

                <!--<div class="control-group">
                    <label for="textarea" class="control-label"><? //=description?></label>
                    <div class="controls">
                        <textarea name="product_description" rows="10" id="product_description" class="input-block-level"><? //=(isset($dr_e["product_description"]) ? $dr_e["product_description"] : "")?></textarea>
                    </div>
                </div>-->


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
								<div class="controls">
									<label for="textfield" class="control-label"><?=name?></label>
									<input type="text" name="question_name1" id="question_name1" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l1["question_name"]) ? $dr_l1["question_name"] : "")?>' >
									<input type="hidden" name="question1lang" value=<?=$lang1ID?>>
								</div>
							</div>
							<div class="control-group">
								<label for="textarea" class="control-label"><?=description?></label>
								<div class="controls">
									<textarea name="question_description1" rows="6" id="question_description1" class="input-block-level"><?=(isset($dr_l1["question_description"]) ? $dr_l1["question_description"] : "")?></textarea>
								</div>
							</div>
						</div>
						<? if(strlen($lang2)>1) {?>
						<div class="tab-pane" id="lang2">
							<div class="control-group">
								<div class="controls">
									<label for="textfield" class="control-label"><?=name?></label>
									<input type="text" name="question_name2" id="question_name2" class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l2["question_name"]) ? $dr_l2["question_name"] : "")?>' >
									<input type="hidden" name="question2lang" value=<?=$lang2ID?>>
								</div>
							</div>
							<div class="control-group">
								<label for="textarea" class="control-label"><?=description?></label>
								<div class="controls">
									<textarea name="question_description2" rows="6" id="question_description2" class="input-block-level"><?=(isset($dr_l2["question_description"]) ? $dr_l2["question_description"] : "")?></textarea>
								</div>
							</div>
						</div>
						<? } ?>
						<? if(strlen($lang3)>1) {?>
						<div class="tab-pane" id="lang3">
							<div class="control-group">
								<div class="controls">
									<label for="textfield" class="control-label"><?=name?></label>
									<input type="text" name="question_name3" id="question_name3" class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l3["question_name"]) ? $dr_l3["question_name"] : "")?>' >
									<input type="hidden" name="question3lang" value=<?=$lang3ID?>>
								</div>
							</div>
							<div class="control-group">
								<label for="textarea" class="control-label"><?=description?></label>
								<div class="controls">
									<textarea name="question_description3" rows="6" id="question_description3" class="input-block-level"><?=(isset($dr_l3["question_description"]) ? $dr_l3["question_description"] : "")?></textarea>
								</div>
							</div>
						</div>
						<? } ?>
						<? if(strlen($lang4)>1) {?>
						<div class="tab-pane" id="lang4">
							<div class="control-group">
								<div class="controls">
									<label for="textfield" class="control-label"><?=name?></label>
									<input type="text" name="question_name4" id="question_name4" class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l4["question_name"]) ? $dr_l4["question_name"] : "")?>' >
									<input type="hidden" name="question4lang" value=<?=$lang4ID?>>
								</div>
							</div>
							<div class="control-group">
								<label for="textarea" class="control-label"><?=description?></label>
								<div class="controls">
									<textarea name="question_description4" rows="6" id="question_description4" class="input-block-level"><?=(isset($dr_l4["question_description"]) ? $dr_l4["question_description"] : "")?></textarea>
								</div>
							</div>
						</div>
						<? } ?>
					</div>
				</div>
            </div>
		</div>
		<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
		<a href="index.php?com=ratequestions&set=<?=$set?>"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#question_name').val();
		if ( value.length >= 2){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
		} //else {
			//document.getElementById("submitBtn").disabled = true;
			//alert('2 chars');
		//}
	}
</script>
<? } else 	{ ?> 
	<div class="breadcrumbs">
		<ul>
			<li>
				<a href="index.php"><?=homePage?></a>
				<i class="icon-angle-right"></i>
			</li>
			<li>
				<a href="index.php?com=ratequestionnaires"><?=ratequestionnaires?></a>
			</li>
			<li>
				<a href="index.php?com=ratequestions&set=<?=$_GET['set']?>"><?=currentSurvey?></a>
			</li>
		</ul>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title"><h3><i class="icon-table"></i><?=$nav?></h3>
			</div>
			<div class="box-content nopadding">
				<table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
					<thead>
						<tr>
							<? echo ($auth->UserType=="Administrator" ? "<th>".customer."</th>" : ""); ?>
							<th><?=displayOrder?></th>
							<th><?=category?></th>
							<th><?=friendlyName?></th>
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody> 
					<?	
						//$filter=($auth->UserType!="Administrator"?" AND user_id=".$auth->UserId:"");
						if($auth->UserType!="Administrator"){
							$filter=" AND rate_questions.questionnaire=".$set." AND (rate_questions.user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR rate_questions.user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
                        }
                        $query = "SELECT rate_questions.*,rate_categories.category_name FROM rate_questions INNER JOIN rate_categories ON rate_questions.category_id=rate_categories.category_id WHERE 1=1 ".$filter." ORDER BY question_name ";
                        $result = $db->sql_query($query);
                        $counter = 0;
						
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
									<? if($auth->UserType=="Administrator"){
                                    $dr_u=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$dr['user_id']);
                                    echo "<td>".$dr_u['company_name']."</td>";
                                    }
                                    ?>
									<td><?=$dr["priority"]?></td>
                                    <td>
									<? 
									//$dr_c=$db->RowSelectorQuery("SELECT * FROM categories WHERE category_id=".$dr['category_id']);
                                    //echo $dr_c['category_name'];
									echo $dr['category_name'];
                                    ?>
                                    </td>
                                    <td><?=$dr["question_name"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=ratequestions&Command=edit&set=<?=$set?>&item=<?=$dr["question_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=ratequestions&Command=DELETE&item=<?=$dr["question_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
                                    </td>
                                </tr>
                            <?
                        }
                        $db->sql_freeresult($result);
                    ?>
					</tbody>
				</table>
			</div>                
		</div>        
	</div>
<? } ?> 

    <script language="javascript">
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        //CKEDITOR.replace( 'product_description' );
        //CKEDITOR.add;
        CKEDITOR.replace( 'question_description1' );
        CKEDITOR.add;
        CKEDITOR.replace( 'question_description2' );
        CKEDITOR.add;
        CKEDITOR.replace( 'question_description3' );
        CKEDITOR.add;
        CKEDITOR.replace( 'question_description4' );
		
	function getRateCategories(val) {
		$.ajax({
		type: "POST",
		url: "http://panel.spotyy.com/sites/hotbox/components/hotbox/ratecategories_list.php",
		data:'user_id='+val,
		success: function(data){
			$("#category_id").html(data);
		}
		});
	}
    </script>