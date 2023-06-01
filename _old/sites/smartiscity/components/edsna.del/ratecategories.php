<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

global $nav;
$nav = 'Κατηγορίες αξιολόγησης';
$config["navigation"] = 'Κατηγορίες αξιολόγησης';
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=ratecategories";
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
			$PrimaryKeys["category_id"] = intval($_GET["item"]);
			$QuotFields["category_id"] = true;
		} else {
			$Collector["user_id"] = ($auth->UserType == "Administrator" ? $_POST["user_id"] : $auth->UserId);
			$QuotFields["user_id"] = true;
		}
		
		$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
		$QuotFields["is_valid"] = true;
	
		$Collector["priority"] = $_POST["priority"];
		$QuotFields["priority"] = true;
		
		$Collector["category_name"] = $_POST["category_name"];
		$QuotFields["category_name"] = true;
		
		$Collector["last_change"] = date('Y-m-d H:i:s');
		$QuotFields["last_change"] = true;
		
		$Collector["last_change_user"] = $auth->UserId;
		$QuotFields["last_change_user"] = true;
		
		//$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
		//$QuotFields["description"] = true;
		
		$Collector["language_id"] = 1; //$_POST["language_id"];
		$QuotFields["language_id"] = true;
		
		$db->ExecuteUpdater("rate_categories",$PrimaryKeys,$Collector,$QuotFields);
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
		if(isset($_POST["category1lang"]) && intval($_POST["category1lang"]) >0)
		{
			$drCheck1=$db->RowSelectorQuery("SELECT id FROM rate_categories2l WHERE category_id=".$item." AND lang_id=".$_POST['category1lang']);
			if(intval($drCheck1['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck1['id']);
				$QuotFields["id"] = true;
			}
			$QuotFields["id"] = true;
			$Collector["category_name"] = $_POST["category_name1"];
			$QuotFields["category_name"] = true;
			$Collector["category_id"] = $item;
			$QuotFields["category_id"] = true;
			$Collector["lang_id"] = $_POST["category1lang"];
			$QuotFields["lang_id"] = true;
			$Collector["category_description"] = html_entity_decode($_POST['category_description1'], ENT_QUOTES, "UTF-8");
			$QuotFields["category_description"] = true;
			$db->ExecuteUpdater("rate_categories2l",$PrimaryKeys,$Collector,$QuotFields);
		}
		//lang2
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		if(isset($_POST["category2lang"]) && intval($_POST["category2lang"]) >0)
		{
			$drCheck2=$db->RowSelectorQuery("SELECT id FROM rate_categories2l WHERE category_id=".$item." AND lang_id=".$_POST['category2lang']);
			if(intval($drCheck2['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck2['id']);
				$QuotFields["id"] = true;
			}
			$Collector["category_name"] = $_POST["category_name2"];
			$QuotFields["category_name"] = true;
			$Collector["category_id"] = $item;
			$QuotFields["category_id"] = true;
			$Collector["lang_id"] = $_POST["category2lang"];
			$QuotFields["lang_id"] = true;
			$Collector["category_description"] = html_entity_decode($_POST['category_description2'], ENT_QUOTES, "UTF-8");
			$QuotFields["category_description"] = true;
			$db->ExecuteUpdater("rate_categories2l",$PrimaryKeys,$Collector,$QuotFields);
		}
		//lang3
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		if(isset($_POST["category3lang"]) && intval($_POST["category3lang"]) >0)
		{
			$drCheck3=$db->RowSelectorQuery("SELECT id FROM rate_categories2l WHERE category_id=".$item." AND lang_id=".$_POST['category3lang']);
			if(intval($drCheck3['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck3['id']);
				$QuotFields["id"] = true;
			}
			$Collector["category_name"] = $_POST["category_name3"];
			$QuotFields["category_name"] = true;
			$Collector["category_id"] = $item;
			$QuotFields["category_id"] = true;
			$Collector["lang_id"] = $_POST["category3lang"];
			$QuotFields["lang_id"] = true;
			$Collector["category_description"] = html_entity_decode($_POST['category_description3'], ENT_QUOTES, "UTF-8");
			$QuotFields["category_description"] = true;
			$db->ExecuteUpdater("rate_categories2l",$PrimaryKeys,$Collector,$QuotFields);
		}
		//lang4
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		if(isset($_POST["category4lang"]) && intval($_POST["category4lang"]) >0)
		{
			$drCheck4=$db->RowSelectorQuery("SELECT id FROM rate_categories2l WHERE category_id=".$item." AND lang_id=".$_POST['category4lang']);
			if(intval($drCheck4['id'])>0)
			{
				$PrimaryKeys["id"] = intval($drCheck4['id']);
				$QuotFields["id"] = true;
			}
			$Collector["category_name"] = $_POST["category_name4"];
			$QuotFields["category_name"] = true;
			$Collector["category_id"] = $item;
			$QuotFields["category_id"] = true;
			$Collector["lang_id"] = $_POST["category4lang"];
			$QuotFields["lang_id"] = true;
			$Collector["category_description"] = html_entity_decode($_POST['category_description4'], ENT_QUOTES, "UTF-8");
			$QuotFields["category_description"] = true;
			$db->ExecuteUpdater("rate_categories2l",$PrimaryKeys,$Collector,$QuotFields);
		}
		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") { 
		if($item != ""){
			//Ο χρήστης να είναι διαχειριστής του account, δημιουργός της κατηγορίας??? ή διαχειριστής
			if($auth->UserType != "Administrator"){
				//$filter=" AND user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
				//$filter=($auth->UserRow['parent']!=0?" AND user_id=".$auth->UserRow['user_id']:" AND (user_id=".$auth->UserRow['user_id']." OR user_id IN(SELECT user_id FROM users WHERE parent=".$auth->UserRow['user_id']."))");
				$filter=($auth->UserRow['parent']==0?" AND user_id=".$auth->UserRow['user_id']:" AND (user_id=".$auth->UserRow['user_id']." OR user_id = ".$auth->UserRow['user_id'].")");
			}				
			$query="SELECT * FROM rate_categories WHERE 1=1 ".$filter." AND category_id=".$_GET['item'];
			
			$drAccess=$db->RowSelectorQuery($query);
			if(!isset($drAccess['category_id'])) {
				$messages->addMessage("ACCESS RESTRICTED!!!");
				Redirect($BaseUrl);
			}
			//Να μην υπάρχουν ερωτήσεις που έχουν την κατηγορία
			$checkDelete=$db->sql_query("SELECT * FROM rate_questions WHERE category_id=".$item);
			if ($db->sql_numrows($checkDelete)>0) {
				$messages->addMessage(errorRecordsFound);
				Redirect($BaseUrl);				
			}
	
			$db->sql_query("DELETE FROM rate_categories WHERE category_id=" . $item);
			$db->sql_query("DELETE FROM rate_categories2l WHERE category_id=" . $item);				
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}


if(isset($_GET["item"])) {
	//if($auth->UserType != "Administrator") {  
	//	//$drAccess=$db->RowSelectorQuery("SELECT * FROM devices WHERE id=".$_GET['item']." AND (user_id=". $auth->UserId." OR user_id IN (SELECT parent FROM users WHERE user_id=".$auth->UserId."))");
	//	$drAccess=$db->RowSelectorQuery("SELECT * FROM devices WHERE id=".$_GET['item']." AND (user_id=". $auth->UserId." OR user_id=".$auth->UserRow['parent'].")");
	//	if(!isset($drAccess['id'])) {
	//		$messages->addMessage("ACCESS RESTRICTED!!!");
	//		Redirect("index.php");
	//	}
	//}
	if($_GET["item"]=="") { //Νέα εγγραφή κάνει μόνο ο χρήστης για να βγουν tabs της γλώσσας
		$userID=$auth->UserId;
	} else {
		if($auth->UserType == "Administrator") {
			$dr_user=$db->RowSelectorQuery("SELECT user_id FROM rate_categories WHERE category_id=".$_GET["item"]);
			//$userID=$auth->UserId;
			$userID=$dr_user['user_id'];
			//$filter="";
		} else {
			$userID=$auth->UserId;
		}
		
		//$filter=" AND user_id=".$userID;
		//$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
		if($auth->UserType != "Administrator"){
			//$filter=" AND user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
			//$filter=($auth->UserRow['parent']!=0?" AND user_id=".$auth->UserRow['user_id']:" AND (user_id=".$auth->UserRow['user_id']." OR user_id IN(SELECT user_id FROM users WHERE parent=".$auth->UserRow['user_id']."))");
			$filter=($auth->UserRow['parent']==0?" AND user_id=".$auth->UserRow['user_id']:" AND (user_id=".$auth->UserRow['user_id']." OR user_id =".$auth->UserRow['parent'].")");
		}
		$query="SELECT * FROM rate_categories WHERE 1=1 ".$filter." AND category_id=".$_GET['item'];
		$dr_e = $db->RowSelectorQuery($query);
		if (!isset($dr_e["category_id"])) {
			$messages->addMessage("NOT FOUND!!!");
			Redirect("index.php?com=ratecategories");
		}
	}

	//$query="SELECT * FROM categories WHERE category_id=".$_GET['item'];
	//$dr_e = $db->RowSelectorQuery($query);
	//$dr_User=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$userID);
	
	//Find root user
	if($auth->UserType != "Administrator"){
		$rootUser=($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent']);
		$dr_User=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$rootUser);
	} else {
		$drUserCategories=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$dr_e['user_id']);
		if($drUserCategories['parent']==0){
			$currentUser=$drUserCategories['user_id'];
		} else {
			$currentUser=$drUserCategories['parent'];
		}
		$dr_User=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$currentUser);
	}

	$lang1ID=$dr_User['lang1ID'];
	$lang2ID=$dr_User['lang2ID'];
	$lang3ID=$dr_User['lang3ID'];
	$lang4ID=$dr_User['lang4ID'];

	if(intval($lang1ID)>0) $dr_l1 = $db->RowSelectorQuery("SELECT * FROM rate_categories2l WHERE category_id = ".$dr_e['category_id']." AND lang_id = ".$lang1ID);
	if(intval($lang2ID)>0) $dr_l2 = $db->RowSelectorQuery("SELECT * FROM rate_categories2l WHERE category_id = ".$dr_e['category_id']." AND lang_id = ".$lang2ID);
	if(intval($lang3ID)>0) $dr_l3 = $db->RowSelectorQuery("SELECT * FROM rate_categories2l WHERE category_id = ".$dr_e['category_id']." AND lang_id = ".$lang3ID);
	if(intval($lang4ID)>0) $dr_l4 = $db->RowSelectorQuery("SELECT * FROM rate_categories2l WHERE category_id = ".$dr_e['category_id']." AND lang_id = ".$lang4ID);
	
	if(intval($lang1ID)>0) $dr1 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang1ID);
	if(intval($lang1ID)>0) $lang1=$dr1['native_name'];
	if(intval($lang2ID)>0) $dr2 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang2ID);
	if(intval($lang2ID)>0) $lang2=$dr2['native_name'];
	if(intval($lang3ID)>0) $dr3 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang3ID);
	if(intval($lang3ID)>0) $lang3=$dr3['native_name'];
	if(intval($lang4ID)>0) $dr4 = $db->RowSelectorQuery("SELECT native_name, lang_id FROM lang WHERE lang_id=".$lang4ID);
	if(intval($lang4ID)>0) $lang4=$dr4['native_name'];

					
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
			<div class="box-title">
				<h3><i class="icon-user"></i><?=edit?></h3>
			</div>
			<div class="box-content nopadding">
				<? if($auth->UserType == "Administrator") { ?> 
				<div class="controls">
					<label for="textfield" class="control-label"><?=customer?></label>
					<?
						$query = "SELECT * FROM users WHERE is_valid='True' ORDER BY company_name";
						echo Select::GetDbRender("user_id", $query, "user_id", "company_name", (isset($dr_e["user_id"]) ? $dr_e["user_id"] : ""), true);
					 ?>
				</div> <? } 
				?>
				<div class="check-line">
					<label class="inline" for="is_valid"><?=active?></label>
					<div class="controls">
						<input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>  />
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<label for="textfield" class="control-label"><?=displayOrder?></label>
						<input type="text" name="priority" id="priority" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["priority"]) ? 'value="'.$dr_e['priority'].'"' : "")?> >
					</div>
				</div>
                    
				<div class="control-group">
					<div class="controls">
						<label for="textfield" class="control-label"><?=friendlyName?></label>
						<input type="text" name="category_name" id="category_name" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["category_name"]) ? 'value="'.$dr_e['category_name'].'"' : "")?> >
					</div>
				</div>
				<!--<div class="control-group">
					<label for="textarea" class="control-label"><? //=description?></label>
					<div class="controls">
						<textarea name="description" rows="10" id="description" class="input-block-level"><?// =(isset($dr_e["description"]) ? $dr_e["description"] : "")?></textarea>
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
									<input type="text" name="category_name1" id="category_name1" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l1["category_name"]) ? $dr_l1["category_name"] : "")?>' >
									<input type="hidden" name="category1lang" value=<?=$lang1ID?>>
									<div class="control-group">
										<label for="textarea" class="control-label"><?=description?></label>
										<div class="controls">
											<textarea name="category_description1" rows="10" id="category_description1" class="input-block-level"><?=(isset($dr_l1["category_description"]) ? $dr_l1["category_description"] : "")?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<? if(strlen($lang2)>1) {?>
						<div class="tab-pane" id="lang2">
							<div class="control-group">
								<div class="controls">
									<label for="textfield" class="control-label"><?=name?></label>
									<input type="text" name="category_name2" id="category_name2" class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l2["category_name"]) ? $dr_l2["category_name"] : "")?>' >
									<input type="hidden" name="category2lang" value=<?=$lang2ID?>>
									<div class="control-group">
										<label for="textarea" class="control-label"><?=description?></label>
										<div class="controls">
											<textarea name="category_description2" rows="10" id="category_description2" class="input-block-level"><?=(isset($dr_l2["category_description"]) ? $dr_l2["category_description"] : "")?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<? } ?>
						<? if(strlen($lang3)>1) {?>
						<div class="tab-pane" id="lang3">
							<div class="control-group">
								<div class="controls">
									<label for="textfield" class="control-label"><?=name?></label>
									<input type="text" name="category_name3" id="category_name3" class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l3["category_name"]) ? $dr_l3["category_name"] : "")?>' >
									<input type="hidden" name="category3lang" value=<?=$lang3ID?>>
									<div class="control-group">
										<label for="textarea" class="control-label"><?=description?></label>
										<div class="controls">
											<textarea name="category_description3" rows="10" id="category_description3" class="input-block-level"><?=(isset($dr_l3["category_description"]) ? $dr_l3["category_description"] : "")?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<? } ?>
						<? if(strlen($lang4)>1) {?>
						<div class="tab-pane" id="lang4">
							<div class="control-group">
								<div class="controls">
									<label for="textfield" class="control-label"><?=name?></label>
									<input type="text" name="category_name4" id="category_name4" class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l4["category_name"]) ? $dr_l4["category_name"] : "")?>' >
									<input type="hidden" name="category4lang" value=<?=$lang4ID?>>
									<div class="control-group">
										<label for="textarea" class="control-label"><?=description?></label>
										<div class="controls">
											<textarea name="category_description4" rows="10" id="category_description4" class="input-block-level"><?=(isset($dr_l4["category_description"]) ? $dr_l4["category_description"] : "")?></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<? } ?>
					</div>
				</div>
			</div>
		</div>
			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
			<a href="index.php?com=ratecategories"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#category_name1').val();
		if ( value.length >= 2){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
		} 
		//else {
			//document.getElementById("submitBtn").disabled = true;
			//alert('2 chars');
		//}
	}
</script>
	<?
} else 	{
	?>    
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
							<th><?=active?></th>
							<th><?=displayOrder?></th>
							<th><?=friendlyName?></th>
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody> 
					<?	
						//$filter=($auth->UserType!="Administrator"?" AND user_id=".$auth->UserId:"");
						if($auth->UserType!="Administrator"){
							//$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
							//$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
							$filter=($auth->UserRow['parent']==0?" AND user_id=".$auth->UserRow['user_id']:" AND (user_id=".$auth->UserRow['user_id']." OR user_id =".$auth->UserRow['parent'].")");
						}
						$query = "SELECT * FROM rate_categories WHERE 1=1 ".$filter." ORDER BY category_name ";
                        $result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
									<? if($auth->UserType=="Administrator"){
                                    $dr_c=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$dr['user_id']);
                                    echo "<td>".$dr_c['company_name']."</td>";
                                    }
                                    ?>
									<td><?=$dr["is_valid"]?></td>
									<td><?=$dr["priority"]?></td>
                                    <td><?=$dr["category_name"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=ratecategories&Command=edit&item=<?=$dr["category_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=ratecategories&Command=DELETE&item=<?=$dr["category_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
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
        CKEDITOR.replace( 'category_description1' );
        CKEDITOR.add;
        CKEDITOR.replace( 'category_description2' );
        CKEDITOR.add;
        CKEDITOR.replace( 'category_description3' );
        CKEDITOR.add;
        CKEDITOR.replace( 'category_description4' );
    </script>