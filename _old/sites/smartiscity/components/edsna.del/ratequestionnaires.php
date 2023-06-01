<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
		include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
?>
<?
$config["navigation"] = ratequestionnaires;
$nav = ratequestionnaires;
$BaseUrl = "index.php?com=ratequestionnaires";

$command=array();
$command=explode("&",$_POST["Command"]);
if($categ_id == "-1") {
	Redirect("index.php");
}											

$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");

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
		//$dr_user=$db->RowSelectorQuery("SELECT user_id FROM rate_questionnaires WHERE id=".$_GET["item"]);
		//$userID=$dr_user['user_id'];
	} else {
		$item=$db->sql_nextid();
		$Collector["user_id"] = ($auth->UserType == "Administrator" ? $_POST["user_id"] : $auth->UserId);
		$QuotFields["user_id"] = true;
		//$userID=($auth->UserType == "Administrator" ? $_POST["user_id"] : $auth->UserId);
	}

	$userID=($auth->UserType == "Administrator" ? $_POST["user_id"] : $auth->UserId);
	
	//$Collector["user_id"] = ($auth->UserType == "Administrator" ? $_POST["user_id"] : $auth->UserId);
	//$Collector["user_id"] = $userID;
	//$QuotFields["user_id"] = true;

	$Collector["friendlyName"] = $_POST["friendlyName"];
	$QuotFields["friendlyName"] = true;

	//$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
	$Collector["is_valid"] = "True";
	$QuotFields["is_valid"] = true;
	
	//$Collector["device"] = $_POST["device"];
	//$QuotFields["device"] = true;
	
	$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
	$QuotFields["description"] = true;
	
	$Collector["last_change_user"] = $auth->UserId;
	$QuotFields["last_change_user"] = true;
	
	$Collector["lastUpdate"] = date('Y-m-d H:i:s');
	$QuotFields["lastUpdate"] = true;
	
	$db->ExecuteUpdater("rate_questionnaires",$PrimaryKeys,$Collector,$QuotFields);
	/*
	if(intval($_GET["item"])<1) {
		$item=$db->sql_nextid();
	} else {
		$item=$_GET["item"];
	}
	*/

	//if($auth->UserRow['parent']!=0){
		$findRootUser=$db->RowSelectorQuery("SELECT user_id,parent FROM users WHERE user_id=".$userID); //$auth->UserRow['parent']);
		$rootUser=($findRootUser['parent']==0?$findRootUser['user_id']:$findRootUser['parent']);
		//$rootUser=$findRootUser['user_id'];
	//} else{
		//$rootUser=$auth->UserId;
	//}	
	
	if($_POST['is_default']=="on") {
		$db->sql_query("UPDATE rate_questionnaires SET is_default='False', lastUpdate='".date('Y-m-d H:i:s')."' WHERE (user_id='".$rootUser."' OR user_id IN (SELECT user_id FROM users WHERE parent=".$rootUser."))");
		$db->sql_query("UPDATE rate_questionnaires SET is_default='True', lastUpdate='".date('Y-m-d H:i:s')."' WHERE id=".$item);
	} else {
		$db->sql_query("UPDATE rate_questionnaires SET is_default='False', lastUpdate='".date('Y-m-d H:i:s')."' WHERE id=".$item);
	}
	$messages->addMessage("SAVED!!!");
	Redirect("index.php?com=ratequestionnaires");
} else if($_REQUEST['Command'] == "DELETE")	{
	if($item != "")
	{
		//Πρέπει να μπει έλεγχος αν υπάρχουν σχετιζόμενα δεδομένα: Κατηγορίες/Ερωτήσεις/Απαντήσεις ?
		//$checkDelete=$db->sql_query("SELECT * FROM rate_categories WHERE category_id=".$item);
		//if ($db->sql_numrows($checkDelete)>0) {
		//	$messages->addMessage(errorRecordsFound);
		//	Redirect($BaseUrl);				
		//}
		$db->sql_query("DELETE FROM rate_questionnaires WHERE id=" . $item);
		$messages->addMessage("DELETED!!!");
		Redirect("index.php?com=ratequestionnaires");
	}
}

?>

<?
if(isset($_GET["item"])){
	if($_GET["item"]=="") {
		if($auth->UserType == "Administrator") {
			$userID=$auth->UserId;
			$rootUser=($auth->UserRow['parent']!=0?$auth->UserRow['parent']:$userID=$auth->UserId);
		} else {
			Redirect($BaseUrl);
		}
	} else {
		if($auth->UserType == "Administrator") {
			$dr_user=$db->RowSelectorQuery("SELECT user_id FROM rate_questionnaires WHERE id=".$_GET["item"]);
			$userID=$dr_user['user_id'];
			//$filter="";
		} else {
			$userID=$auth->UserId;
			$rootUser=($auth->UserRow['parent']!=0?$auth->UserRow['parent']:$userID=$auth->UserId);
		}
		/*
		if($auth->UserRow['parent']!=0){
			$findRootUser=$db->RowSelectorQuery("SELECT user_id FROM users WHERE user_id=".$auth->UserRow['parent']);
			$rootUser=$findRootUser['user_id'];
		} else{
			$rootUser=$auth->UserId;
		}
		*/
		$filter = ($auth->UserType != "Administrator" ? " AND (rate_questionnaires.user_id=".$userID." OR rate_questionnaires.user_id=".$rootUser." OR rate_questionnaires.user_id IN (SELECT user_id FROM users WHERE parent=".$rootUser."))" :'');
		//$filter=" AND user_id=".$userID;
		$query="SELECT * FROM rate_questionnaires WHERE 1=1 ".$filter." AND id=".$_GET['item'];
		$dr_e = $db->RowSelectorQuery($query);
		if (!isset($dr_e["id"])) {
			$messages->addMessage("NOT FOUND!!!");
			Redirect("index.php?com=ratequestionnaires");
		}
	}
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
					<label for="textfield" class="control-label"><?=friendlyName?></label>
					<div class="controls">
						<input type="text" name="friendlyName" id="friendlyName" class="input-xxlarge" value="<?=(isset($dr_e["friendlyName"]) ? $dr_e["friendlyName"] : "")?>">
					</div>
				</div>
				<div class="controls">
					<textarea name="description" rows="6" id="description" class="input-block-level"><?=(isset($dr_e["description"]) ? $dr_e["description"] : "")?></textarea>
				</div>
			<div class="box-content nopadding">
				<a href="#" onClick="cm('SAVE',1,0,'');"><button type="button" class="btn btn-primary"><?=save?></button></a></li>
				<a href="index.php?com=ratequestionnaires"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
			</div>
		</div>
	</div>
</div>
<?
} else {
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
							<!--<th><?//=device?></th>-->
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody>
						<?
							if($auth->UserRow['parent']!=0){
								$rootUser=$auth->UserRow['parent'];
							} else{
								$rootUser=$auth->UserId;
							}
							$filter = ($auth->UserType != "Administrator" ? " AND (users.user_id=".$rootUser." OR users.parent=".$rootUser.")" :'');
							$result = $db->sql_query("SELECT rate_questionnaires.*, users.user_id, users.parent FROM rate_questionnaires INNER JOIN users ON rate_questionnaires.user_id=users.user_id WHERE 1=1 ".$filter);
							if($db->sql_numrows($result) > 0)
							{
								while ($dr = $db->sql_fetchrow($result))
								{
									?>
									 <tr>
										<td><?=($dr['is_default']=='True'?'True':'')?></td>
										<? if($auth->UserType=="Administrator"){
										$dr_c=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$dr['user_id']);
										echo "<td>".$dr_c['company_name']."</td>";
										}
										?>
										<td><?=$dr["friendlyName"]?></td>
										<!--<td><?//=$dr["deviceFriendlyName"]?></td>-->
										<td>
											<a style="padding:4px"  href="index.php?com=ratequestionnaires&Operation=EDIT&item=<?=$dr["id"]?>"><i class="icon-edit"></i> <?=edit?></a>
											<a style="padding:4px"  href="index.php?com=ratequestions&set=<?=$dr["id"]?>"><i class="icon-edit"></i> <?=questions?></a>
											<a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=ratequestionnaires&Command=DELETE&item=<?=$dr["id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
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
<? } ?>
<script language="javascript">
        //CKEDITOR.replace( 'message' );
        //CKEDITOR.add;
        CKEDITOR.replace( 'description' );
</script>
