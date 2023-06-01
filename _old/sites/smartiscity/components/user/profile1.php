<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
$config["navigation"] = editProfile;
global $components;

//if(isset($_GET["logout"]) && $_GET["logout"] == "true")
//{
//	Logout();
//	Redirect(CreateUrl());
//}

if($auth->UserId == "") Redirect(CreateUrl(array("com"=>"login","redir"=> CreateUrl(array("com"=>"profile")))));

$BaseUrl = CreateUrl(array("com"=>"profile"));

$Message = "";
//if($toolBar->CurrentCommand() == "SAVEINFO" && $_POST["reg_email"] != ""  && $_POST["reg_fname"] != "")
if(isset($_POST["Command"]) )
{
	if($_POST["Command"] == "SAVE")
	{
		$PerformRegister = true;

		//file upload
		$myfile=basename($_FILES["fileToUpload"]["name"]);
		$targetFile="";
		if(isset($myfile) && $myfile!="") {
			$targetDir = $config["physicalPath"] . "gallery/customer_logo/".$auth->UserId."/"; 
			$uploadOk = 1;
			$imageFileType = pathinfo($myfile,PATHINFO_EXTENSION);
			$targetFile = $targetDir.$auth->UserId.".".$imageFileType;
			$targetFiletoSave = $auth->UserId.".".$imageFileType;
			// Check if image file is a actual image or fake image
			if(isset($_POST["Command"]) && $_POST["Command"] == "SAVE") {
				$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
				if($check !== false) {
					echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
				} else {
					echo "File is not an image.";
					$uploadOk = 0;
				}
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
					echo "Sorry, there was an error uploading your file.";
				}
			}
		}
		
		/*$dr = $db->RowSelectorQuery("select count(*) from users where email='" . $_POST["reg_email"] . "' AND user_id != " . $auth->UserId );
		if(isset($dr) && $dr[0] > 0)
		{
			$PerformRegister = false;
			$messages->addMessage("saved!!!");
		}*/
		
		if($PerformRegister)
		{
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			$PrimaryKeys["user_id"] = $auth->UserId;
			$QuotFields["user_id"] = true;
			
			if(isset($_POST["pw"]) && $_POST["pw"] != "")
			{
				if ($_POST["pw"]!=$auth->UserRow["user_password"]) {
					$messages->addMessage("Old password incorrect!!!");
				} else {
					if ($_POST["pw_new"]==$_POST["pw_new2"]) {
						$Collector["user_password"] = $_POST["pw_new"];
						$QuotFields["user_password"] = true;
					} else {
						$messages->addMessage("New passwords not same!!!");
					}
				}
			}
			//$Collector["email"] = $_POST["email"];
			//$QuotFields["email"] = true;
			
			$Collector["email2"] = $_POST["email2"];
			$QuotFields["email2"] = true;
			
			$Collector["user_fullname"] = $_POST["user_fullname"];
			$QuotFields["user_fullname"] = true;
			
			$Collector["company_name"] = $_POST["company_name"];
			$QuotFields["company_name"] = true;
			
			$Collector["mobilephone"] = $_POST["mobilephone"];
			$QuotFields["mobilephone"] = true;
						
			$Collector["address"] = $_POST["address"];
			$QuotFields["address"] = true;
			
			$Collector["city"] = $_POST["city"];
			$QuotFields["city"] = true;
			
			$Collector["password_message"] = html_entity_decode($_POST['password_message'], ENT_QUOTES, "UTF-8");
			$QuotFields["password_message"] = true;
			
			$Collector["catalogName"] = $_POST["catalogName"];
			$QuotFields["catalogName"] = true;
			
			$Collector["catalogHomeURL"] = $_POST["catalogHomeURL"];
			$QuotFields["catalogHomeURL"] = true;
			
			$Collector["catalogText"] = $_POST["catalogText"];
			$QuotFields["catalogText"] = true;
			
			$Collector["country"] = $_POST["country"];
			$QuotFields["country"] = true;
		
			//if($auth->UserType == "Administrator") {
			//	$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
			//	$QuotFields["is_valid"] = true;
			//}
			
			if(isset($myfile) && $targetFile!="") {		
				$Collector["user_photo"] = $targetFiletoSave;
				$QuotFields["user_photo"] = true;
			}
			
			$db->ExecuteUpdater("users",$PrimaryKeys,$Collector,$QuotFields);
			$dr = $db->RowSelectorQuery("SELECT * FROM users WHERE user_id=" . $auth->UserId);
			$auth->UserRow = $dr;
			$messages->addMessage("Saved!!!");
		}
	}
}
	?>
<style>
input[type=file]{
    width:120px;
    color:transparent;
}
</style>
		<div class="row-fluid">
            <div class="span12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="icon-user"></i>
                            <?=editProfile?>
                        </h3>
                    </div>
                    <div class="box-content nopadding">
                        <ul class="tabs tabs-inline tabs-top">
                            <li class='active'>
                                <a href="#profile" data-toggle='tab'><i class="icon-user"></i> <?=general?></a>
                            </li>
                            <li>
                                <a href="#security" data-toggle='tab'><i class="icon-lock"></i> <?=security?></a>
                            </li>
							<li>
								<a href="#printer" data-toggle='tab'><i class="icon-print"></i> <?=printer?></a>
							</li>
                        </ul>
                        <div class="tab-content padding tab-content-inline tab-content-bottom">
                            <div class="tab-pane active" id="profile">
                                <!--Φωτογραφία-->
                                <div class="row-fluid">
                                    <div class="">
                                        <!-- <div class="check-line">
											
                                            <label class="inline" for="is_valid">Ενεργός</label>
                                            <div class="controls">
                                                <input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <? //=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>  />
                                            </div>
                                        </div>-->
                                        <div style="border-bottom: 1px solid #ccc;clear: both;"></div>
                                        <br>
                                        <div class="control-group">
                                            <label for="name" class="control-label right">Επωνυμία επιχείρησης:</label>
                                            <div class="controls">
                                                <input type="text" name="company_name" class='input-xlarge' value="<?=(isset($_POST["company_name"]) ? $_POST["company_name"] : $auth->UserRow["company_name"])?>">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label for="name" class="control-label right"><?=fullName?>:</label>
                                            <div class="controls">
                                                <input type="text" name="user_fullname" class='input-xlarge' value="<?=(isset($_POST["user_fullname"]) ? $_POST["user_fullname"] : $auth->UserRow["user_fullname"])?>">
                                            </div>
                                        </div>
										<!--
                                        <div class="control-group">
                                            <label for="email" class="control-label right">Email:</label>
                                            <div class="controls">
                                                <div class="controls">
                                                    <input type="text" name="email" class='input-xlarge' value="<? //=(isset($_POST["email"]) ? $_POST["email"] : $auth->UserRow["email"])?>">
                                                </div>
                                            </div>
                                        </div>
										-->
                                        <div class="control-group">
                                            <label for="email" class="control-label right">2ο Email:</label>
                                            <div class="controls">
                                                <div class="controls">
                                                    <input type="text" name="email2" class='input-xlarge' value="<?=(isset($_POST["email2"]) ? $_POST["email2"] : $auth->UserRow["email2"])?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label for="textfield" class="control-label"><?=phone?></label>
                                            <div class="controls">
                                                <input type="text" name="mobilephone" id="mobilephone" class="input-xlarge mask_phone" value="<?=(isset($_POST["mobilephone"]) ? $_POST["mobilephone"] : $auth->UserRow["mobilephone"])?>">
                                                <span class="help-block">Format: (999) 999-9999</span>
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label for="name" class="control-label right">Διεύθυνση:</label>
                                            <div class="controls">
                                                <input type="text" name="address" class='input-xlarge' value="<?=(isset($_POST["address"]) ? $_POST["address"] : $auth->UserRow["address"])?>">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label for="name" class="control-label right">Πόλη:</label>
                                            <div class="controls">
                                                <input type="text" name="city" class='input-xlarge' value="<?=(isset($_POST["city"]) ? $_POST["city"] : $auth->UserRow["city"])?>">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label for="name" class="control-label right">Χώρα:</label>
                                            <div class="controls">
                                                <input type="text" name="country" class='input-xlarge' value="<?=(isset($_POST["country"]) ? $_POST["country"] : $auth->UserRow["country"])?>">
                                            </div>
                                        </div>
										
										<div class="control-group">
											<label for="timezone" class="control-label right"><?=timezone?>:</label>
											<div class="controls">
												<select name='timezone' id='timezone'>
												  <option value="0" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==0) ? 'selected':'')?>>0</option>
												  <option value="1" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==1) ? 'selected':'')?>>+1</option>
												  <option value="2" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==2) ? 'selected':'')?>>+2</option>
												  <option value="3" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==3) ? 'selected':'')?>>+3</option>
												  <option value="4" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==4) ? 'selected':'')?>>+4</option>
												  <option value="5" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==5) ? 'selected':'')?>>+5</option>
												  <option value="6" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==6) ? 'selected':'')?>>+6</option>
												  <option value="7" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==7) ? 'selected':'')?>>+7</option>
												  <option value="8" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==8) ? 'selected':'')?>>+8</option>
												  <option value="9" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==9) ? 'selected':'')?>>+9</option>  
												  <option value="10" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==10) ? 'selected':'')?>>+10</option>
												  <option value="11" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==11) ? 'selected':'')?>>+11</option>
												  <option value="12" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==12) ? 'selected':'')?>>+12</option>
												  <option value="-12" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==-12) ? 'selected':'')?>>-12</option>
												  <option value="-11" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==-11) ? 'selected':'')?>>-11</option>
												  <option value="-10" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==-10) ? 'selected':'')?>>-10</option>
												  <option value="-9" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==-9) ? 'selected':'')?>>-9</option>
												  <option value="-8" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==-8) ? 'selected':'')?>>-8</option>
												  <option value="-7" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==-7) ? 'selected':'')?>>-7</option>
												  <option value="-6" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==-6) ? 'selected':'')?>>-6</option>
												  <option value="-5" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==-5) ? 'selected':'')?>>-5</option>
												  <option value="-4" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==-4) ? 'selected':'')?>>-4</option>
												  <option value="-3" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==-3) ? 'selected':'')?>>-3</option>
												  <option value="-2" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==-2) ? 'selected':'')?>>-2</option>
												  <option value="-1" <?=((isset($dr_e["timezone"]) && $dr_e['timezone']==-1) ? 'selected':'')?>>-1</option>
												</select>
											</div>
										</div>  
                                        <div class="control-group">
                                            <label for="catalogName" class="control-label right">Τίτλος καταλόγου:</label>
                                            <div class="controls">
                                                <input type="text" name="catalogName" class='input-xlarge' value="<?=(isset($_POST["catalogName"]) ? $_POST["catalogName"] : $auth->UserRow["catalogName"])?>">
                                            </div>
                                        </div>
                                        <div class="control-group">
                                            <label for="catalogHomeURL" class="control-label right">URL Καταλόγου:</label>
                                            <div class="controls">
                                                <input type="text" name="catalogHomeURL" class='input-xlarge' value="<?=(isset($_POST["catalogHomeURL"]) ? $_POST["catalogHomeURL"] : $auth->catalogHomeURL["country"])?>">
                                            </div>
                                        </div>
										
                                        <div class="control-group">
                                            <label for="catalogText" class="control-label right">Κείμενο footer καταλόγου:</label>
                                            <div class="controls">
                                                <input type="text" name="catalogText" class='input-xlarge' value="<?=(isset($_POST["catalogText"]) ? $_POST["catalogText"] : $auth->UserRow["catalogText"])?>">
                                            </div>
                                        </div>
                                        <!--
                                        <div class="control-group">
                                            <label for="name" class="control-label right">URL Καταλόγου προϊόντων / υπηρεσιών:</label>
                                            <div class="controls">
                                                <input type="text" name="catalog_URL" class='input-xlarge' value="<? //=(isset($_POST["catalog_URL"]) ? $_POST["catalog_URL"] : $auth->UserRow["catalog_URL"])?>">
                                            </div>
                                        </div>
										<div class="control-group">
										<label for="select" class="control-label">Χρώμα καταλόγου</label>
                                            <div class="controls">
                                                <select name="catalog_color" id="catalog_color" class="input-large">
                                                    <option value="red" <? //=($auth->UserRow["catalog_color"]=='red'?' selected':'')?>>Κόκκινο</option>
                                                    <option value="pink" <? //=($auth->UserRow["catalog_color"]=='pink'?' selected':'')?>>Ρόζ</option>
                                                    <option value="purple" <? //=($auth->UserRow["catalog_color"]=='purple'?' selected':'')?>>Μωβ</option>
                                                    <option value="deep-purple" <? //=($auth->UserRow["catalog_color"]=='deep-purple'?' selected':'')?>>Βαθύ μωβ</option>
                                                    <option value="indigo" <? //=($auth->UserRow["catalog_color"]=='indigo'?' selected':'')?>>Λουλάκι</option>
                                                    <option value="blue" <? //=($auth->UserRow["catalog_color"]=='blue'?' selected':'')?>>Μπλε</option>
                                                    <option value="light-blue" <? //=($auth->UserRow["catalog_color"]=='light-blue'?' selected':'')?>>Ανοιχτό μπλε</option>
                                                    <option value="cyan" <? //=($auth->UserRow["catalog_color"]=='cyan'?' selected':'')?>>Κυανό</option>
                                                    <option value="teal" <? //=($auth->UserRow["catalog_color"]=='teal'?' selected':'')?>>Βάσκας</option>
                                                    <option value="green" <? //=($auth->UserRow["catalog_color"]=='green'?' selected':'')?>>Πράσινο</option>
                                                    <option value="light-green" <? //=($auth->UserRow["catalog_color"]=='light-green'?' selected':'')?>>Ανοιχτό πράσινο</option>
                                                    <option value="lime" <? //=($auth->UserRow["catalog_color"]=='lime'?' selected':'')?>>Λαιμ</option>
                                                    <option value="yellow" <? //=($auth->UserRow["catalog_color"]=='yellow'?' selected':'')?>>Κίτρινο</option>
                                                    <option value="amber" <? //=($auth->UserRow["catalog_color"]=='amber'?' selected':'')?>>Κεχριμπάρι</option>
                                                    <option value="orange" <? //=($auth->UserRow["catalog_color"]=='orange'?' selected':'')?>>Πορτοκαλί</option>
                                                    <option value="deep-orange" <? //=($auth->UserRow["catalog_color"]=='deep-orange'?' selected':'')?>>Βαθύ πορτοκαλί</option>
                                                    <option value="brown" <? //=($auth->UserRow["catalog_color"]=='brown'?' selected':'')?>>Καφέ</option>
                                                    <option value="blue-grey" <? //=($auth->UserRow["catalog_color"]=='blue-grey'?' selected':'')?>>Μπλε πράσινο</option>
                                                    
                                                </select>
                                            </div>
										</div>
										-->
                                        <div style="border-bottom: 1px solid #ccc;clear: both;"></div><br>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="security">
                                 <div class="control-group">
                                    <label for="pw" class="control-label right"><?=oldPassword?>:</label>
                                    <div class="controls">
                                        <input type="password" name="pw" class='input-xlarge' value="">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label for="pw_new" class="control-label right"><?=newPassword?>:</label>
                                    <div class="controls">
                                        <input type="password" name="pw_new" class='input-xlarge' value="">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label for="pw_new2" class="control-label right"><?=passwordConfirm?>:</label>
                                    <div class="controls">
                                        <input type="password" name="pw_new2" class='input-xlarge' value="">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls">
                                        <h5><?=photo?></h5>
                                        <? if(isset($auth->UserRow["user_photo"]) && $auth->UserRow["user_photo"]!="") {
                                        ?>
                                            <img src='/gallery/customer_logo/<?=$auth->UserId?>/<?=$auth->UserRow["user_photo"]?>' style="width:140px;"/>
                                        <? }  else { echo "Δεν έχει οριστεί αρχείο φωτογραφίας";}?>
                                        <br>
                                        <!-- <label for="fileToUpload"></label> -->
                                        <input name="fileToUpload" id="fileToUpload" id="aa" type="file">
                                        <span class="help-block">Only .jpg (Max Size: 500KB)</span>
                                    </div>
                                </div>
                            </div>
                            
							<div class="tab-pane" id="printer">
								<div class="control-group">
									<label for="password_message" class="control-label"><?=message?></label>
									<div class="controls">
										<textarea name="password_message" rows="4" id="password_message" class="input-block-level"><?=(isset($auth->UserRow['password_message']) && $auth->UserRow['password_message']!='' ? $auth->UserRow['password_message'] : "")?></textarea>
									</div>
								</div>
                            </div>
							
                            <a href="#" onClick="cm('SAVE',1,0,'');">   <button type="button" class="btn btn-primary"><?=save?></button></a>
							<a href="index.php?com=profile"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
                            <!--
                            Όνομα	
                            Επώνυμο	
                            Εναλλακτικό email (για επαναφορά κωδικού)	
                            -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?
	
	//$validator->AddTagValidator('reg_fname',1,'String');
	//$validator->AddTagValidator('reg_email',1,'String');
	//$validator->AddTagValidator('reg_phone',1,'String');
	
	?>
	<!--<script language="javascript">
		function CheckInfP()
		{
			if(ValidateOnlyThis('reg_fname;reg_email;reg_phone'))
			{
				if(GetObject("reg_pass").value != GetObject("reg_passr").value)
				{
					alert("<? //=user_passwordnotSame?>");
				}
				else
				{
					cm("SAVEINFO",1,0,"");
				}
			}
		}
		
	</script>-->
<script language="javascript">
window.pressed = function(){
    var a = document.getElementById('aa');
    if(a.value == "")
    {
        fileLabel.innerHTML = "Choose file";
    }
    else
    {
        var theSplit = a.value.split('\\');
        fileLabel.innerHTML = theSplit[theSplit.length-1];
    }
};
</script>
<script>
	CKEDITOR.replace( 'password_message' );
	CKEDITOR.add;
</script>