<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
//if($auth->UserType != "Administrator") {
//	Redirect("index.php");
//}

//require_once(dirname(__FILE__) . "/common.php");
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
global $nav;
$nav = ads;
$config["navigation"] = ads;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=freepaidmac";
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
				$PrimaryKeys["ads_id"] = intval($_GET["item"]);
				$QuotFields["ads_id"] = true;
				$item=$_GET['item'];
			} 
			
			$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
			$QuotFields["is_valid"] = true;
			
			$Collector["text_display"] = ($_POST["text_display"]=="on" ? "True" : "False");
			$QuotFields["text_display"] = true;
			
			$Collector["title"] = $_POST["title"];
			$QuotFields["title"] = true;
			
			$Collector["video"] = $_POST["video"];
			$QuotFields["video"] = true;

			$Collector["smallDescription"] = html_entity_decode($_POST['smallDescription'], ENT_QUOTES, "UTF-8");
			$QuotFields["smallDescription"] = true;
			
			$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
			$QuotFields["description"] = true;
			
			$Collector["businessCategory_id"] = $_POST["businessCategory_id"];
			$QuotFields["businessCategory_id"] = true;
			
			//$Collector["views"] = $_POST["views"];
			//$QuotFields["views"] = true;

			$Collector["device"] = $_POST["device"];
			$QuotFields["device"] = true;
			
			$Collector["user_id"] = ($auth->UserType == "Administrator" ? $_POST["user_id"] : $auth->UserId);
			$QuotFields["user_id"] = true;
		
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
			
			$db->ExecuteUpdater("ads",$PrimaryKeys,$Collector,$QuotFields);
			if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
			{
				$item=$_GET['item'];
			} else {
				$item=$db->sql_nextid();
			}
			
			//file upload
			$myfile=basename($_FILES["fileToUpload"]["name"]);
			$targetFile="";

			if(isset($myfile) && $myfile!="") {
				$targetDir = $config["physicalPath"] . "gallery/ads/";
				$uploadOk = 1;
				$imageFileType = pathinfo($myfile,PATHINFO_EXTENSION);
				$targetFile = $targetDir.$item.".".$imageFileType;
				$targetFiletoSave = $item.".".$imageFileType;
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
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
					echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
					$uploadOk = 0;
				}
				
				
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
			//write image to database
			$PrimaryKeys["ads_id"] = intval($item);
			$QuotFields["ads_id"] = true;

			if(isset($myfile) && $targetFile!="") {	
				$Collector["photo"] = $targetFiletoSave;
				$QuotFields["photo"] = true;
			}
			
			//$Collector["photo"] = $targetFiletoSave;
			//$QuotFields["photo"] = true;
			
			$db->ExecuteUpdater("ads",$PrimaryKeys,$Collector,$QuotFields);
			
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { //$command[0] ==
			if($item != "")
			{
				//$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
				//$query="SELECT * FROM ads WHERE 1=1 ".$filter." AND ads_id=" . $item;
				//$drAccess=$db->RowSelectorQuery($query);
				//if(!isset($drAccess['ads_id'])) {
				//	$messages->addMessage("ACCESS RESTRICTED!!!");
				//	Redirect($BaseUrl);
				//}
				$db->sql_query("DELETE FROM freepaidmac WHERE freepaidmac_id=" . $item);
				$messages->addMessage("DELETED!!!");
				Redirect($BaseUrl);
			}
		}
	}

	if(isset($_GET["item"])) {
		$filter="";
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
					$dr_user=$db->RowSelectorQuery("SELECT user_id FROM ads WHERE ads_id=".$_GET["item"]);
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
				//$filter = ($auth->UserType != "Administrator" ? " AND (messages.user_id=".$rootUser." OR messages.user_id IN (SELECT user_id FROM users WHERE parent=".$rootUser."))" :'');
				//$query="SELECT * FROM messages WHERE 1=1 ".$filter." AND id=".$_GET['item'];
				//$dr_e = $db->RowSelectorQuery($query);
				//if (!isset($dr_e["id"])) {
				//	$messages->addMessage("NOT FOUND!!!");
				//	Redirect("index.php?com=messages");
				//}
			}

	if($auth->UserType != "Administrator") { 
		//$filter=" AND user_id=". $auth->UserId;
		$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
	}
	$query="SELECT * FROM ads WHERE ads_id=".$_GET['item'].$filter;
	$dr_e = $db->RowSelectorQuery($query);
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
				<div class="check-line">
					<label class="inline" for="is_valid"><?=active?></label>
					<div class="controls">
						<input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>  />
					</div>
				</div>
				<? if($auth->UserType == "Administrator") { ?> 
				<div class="controls">
					<label for="textfield" class="control-label"><?=customer?></label>
					<?
						$query = "SELECT * FROM users WHERE is_valid='True' ORDER BY company_name";
						echo Select::GetDbRender("user_id", $query, "user_id", "company_name", (isset($dr_e["user_id"]) ? $dr_e["user_id"] : ""), true);
					 ?>
				</div> 
				<? } ?>
				
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
						//echo $query;
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
				<div class="check-line">
					<label class="inline" for="text_display"><?=textDisplay?></label>
					<div class="controls">
						<input id="text_display" name="text_display" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["text_display"]) && $dr_e["text_display"]=='True') ? 'checked':'')?>  />
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<label for="title" class="control-label"><?=title?></label>
						<input type="text" name="title" id="title" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["title"]) ? 'value="'.$dr_e['title'].'"' : "")?> >
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<label for="video" class="control-label">Video</label>
						<input type="text" name="video" id="video" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["video"]) ? 'value="'.$dr_e['video'].'"' : "")?> >
					</div>
				</div>
				
				<div class="control-group">
					<label for="smallDescription" class="control-label"><?=description?></label>
					<div class="controls">
						<textarea name="smallDescription" rows="6" id="smallDescription" class="input-block-level"><?=(isset($dr_e["smallDescription"]) ? $dr_e["smallDescription"] : "")?></textarea>
					</div>
				</div>
				<div class="control-group">
					<label for="description" class="control-label"><?=description?></label>
					<div class="controls">
						<textarea name="description" rows="6" id="description" class="input-block-level"><?=(isset($dr_e["description"]) ? $dr_e["description"] : "")?></textarea>
					</div>
				</div>
				<div class="controls">
					<label for="textfield" class="control-label"><?=businessCategory?></label>
					<?
						$query = "SELECT * FROM businessCategories WHERE is_valid='True' ORDER BY businessCategoryName";
						echo Select::GetDbRender("businessCategory_id", $query, "businessCategory_id", "businessCategoryName", (isset($dr_e["businessCategory_id"]) ? $dr_e["businessCategory_id"] : ""), true);
					 ?>
				</div>
				<!--
				<div class="control-group">
					<label for="views" class="control-label"><? //=views?></label>
					<div class="controls">
						<input type="text" name="views" id="views" class="input-xxsmall" value="<? //=(isset($dr_e["views"]) ? $dr_e["views"] : "")?>">
					</div>
				</div>
				-->
				<div class="controls">
					<!--<a href="#" onClick="cm('DELETEPHOTO',1,0,'');">   <button type="button"><? //=delBackground?></button></a>-->
					<input name="fileToUpload" id="fileToUpload"  style="visibility:none;" type="file">
					<label for="fileToUpload"><span> Upload your image file</span></label>
					<span class="help-block">
					<? if(isset($dr_e["photo"]) && $dr_e["photo"]!="") {
					?>
						<img src='/gallery/ads/<?=$dr_e["photo"]?>' style="width:140px;"/></spam>
					<? } ?>
				</div>
			</div>
		</div>
			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
			<a href="index.php?com=ads"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#title').val();
		if ( value.length >= 2){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
		} //else {
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
				<div class="box-title"><h3><i class="icon-table"></i>Free / Paid Connections</h3>
			</div>
			<div class="box-content nopadding">
				<table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
					<thead>
						<tr>
							<th>Reservation id</th>
							<!--<th class='hidden-480'><? //=active?></th>-->
							<th>Room id</th>
							<th>Amount</th>
							<th>Mac address</th>
							<th>Date inserted</th>
							<th>Expires</th>
							<th>Fias update</th>
							<th>Type</th>
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody> 
					<?	
						if($auth->UserType != "Administrator") { 
							//$filter=" AND user_id=". $auth->UserId;
							//$filter=" AND (ads.user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR ads.user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
						}
						//$query = "SELECT ads.*,users.company_name FROM ads INNER JOIN users ON ads.user_id=users.user_id WHERE 1=1 ".$filter." ORDER BY title ";
                        $query="SELECT t1.reservation_id, t1.room_id,t1.amount, t1.date_insert, t1.date_expire, t1.updated, t1.type, t2.mac, t2.freepaidmac_id  FROM freepaid t1 INNER JOIN freepaidmac t2 ON t1.id=t2.freepaid_id";
						//echo $query;
						$result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
							//$queryT="SELECT SUM(views) AS totalViews FROM ads_credits WHERE ads_id=".$dr['ads_id'];
							//$dr_t = $db->RowSelectorQuery($queryT);
                            ?>
                                <tr>
									<? //if($auth->UserType == "Administrator") { ?> 
										<!--<td><?//=$dr["company_name"]?></td>-->
									<? //}?>								
									<!--<td class='hidden-480'><?//=$dr["is_valid"]?></td>-->
                                    <td><?=$dr["reservation_id"]?></td>
									<td><?=$dr["room_id"]?></td>
									<td><?=$dr["amount"]?></td>
									<td><?=$dr["mac"]?></td>
									<td><?=$dr["date_insert"]?></td>
									<td><?=$dr["date_expire"]?></td>
									<td><?=$dr["updated"]?></td>
									<td><?=($dr["type"]==1?'Free':'Paid')?></td>
									<td>
										<!--<button class="btn btn-small btn-inverse"><i class="icon-trash"></i> Delete</button>-->
										<? //if($auth->UserType == "Administrator") { ?>
										<a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=freepaidmac&Command=DELETE&item=<?=$dr["freepaidmac_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
										<? //} ?>
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
	CKEDITOR.replace( 'description' );
	CKEDITOR.add;
	CKEDITOR.replace( 'smallDescription' );
</script>