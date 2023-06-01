<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

	if($auth->UserType != "Administrator" && intval($auth->UserRow['parent'])!=0) {  
			Redirect("index.php");
	}

global $nav;
$nav = 'Χρήστες';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=users";
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
			$PrimaryKeys["user_id"] = intval($_GET["item"]);
			$QuotFields["user_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}
		
		$Collector["is_valid"] = isset($_POST["is_valid"]) && $_POST["is_valid"] == "on" ? "True" : "False";
		$QuotFields["is_valid"] = true;
	
		$Collector["user_auth"] = "Register";
		$QuotFields["user_auth"] = true;
	
		$Collector["user_name"] = $_POST["email"];
		$QuotFields["user_name"] = true;
	
		$Collector["region_id"] = $_POST["region_id"];
		$QuotFields["region_id"] = true;
		
		$Collector["email"] = $_POST["email"];
		$QuotFields["email"] = true;

		$Collector["user_password"] = $_POST["user_password"];
		$QuotFields["user_password"] = true;
			
		$Collector["user_fullname"] = $_POST["user_fullname"];
		$QuotFields["user_fullname"] = true;
			
		$Collector["phone"] = $_POST["phone"];
		$QuotFields["phone"] = true;
			
		$Collector["mobilephone"] = $_POST["mobilephone"];
		$QuotFields["mobilephone"] = true;
		
		$Collector["parent"] = ($auth->UserType == "Administrator"?'0':$auth->UserId);
		$QuotFields["parent"] = true;
			
		$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
		$QuotFields["description"] = true;
		
		$db->ExecuteUpdater("users",$PrimaryKeys,$Collector,$QuotFields);		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") { 
		if($item != ""){
			//SOS Έλεγχο πριν τη διαγραφή
			$filter=($auth->UserType!='Administrator'?' AND parent='.$auth->UserId:'');
			$check=$db->RowSelectorQuery("SELECT * FROM users WHERE 1=1 AND user_id=".$item.$filter);
			if(intval($check['user_id'])>0){
				$db->sql_query("DELETE FROM users WHERE user_id=" . $item);
				$messages->addMessage("DELETED!!!");
			} else {
				$messages->addMessage("NOT FOUND!!!");
			}
			Redirect($BaseUrl);
		}
	}
}


if(isset($_GET["item"])) {
	if(intval($item)>0){
		$filter=($auth->UserType!='Administrator'?' AND parent='.$auth->UserId:'');		
	}

	$query="SELECT * FROM users WHERE 1=1 AND user_id=".$_GET['item'].$filter;
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["user_id"]) && intval($_GET['item'])>0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=users");
	}
	?>

	<div id="titlebar" class="gradient">
		<div class="container">
			<div class="row">
				<div class="col-md-12">

					<h2 style="font-family: 'Commissioner', sans-serif;"><i class="sl sl-icon-plus"></i><?=$nav?></h2>
					<!-- Breadcrumbs -->
					<nav id="breadcrumbs">
						<ul>
							<li><a href="#">Home</a></li>
							<li><?=$nav?></li>
						</ul>
					</nav>

				</div>
			</div>
		</div>
	</div>
		
	<div class="container" style="margin-bottom:30px;">
		<div class="row">
			<div class="col-lg-12">
				<div id="add-listing" class="separated-form">
					<!-- Section -->
					<div class="add-listing-section">
						<!-- Headline -->
						<div class="add-listing-headline">
							<h3 style="font-family: 'Commissioner', sans-serif;"><i class="sl sl-icon-doc"></i> Γενικά στοιχεία χρήστη</h3>
						</div>

						<div class="checkboxes in-row margin-bottom-20">
							<input id="is_valid" class="check-a" type="checkbox" name="is_valid" <?=($dr_e['is_valid']=='True'?'checked':'')?>>
							<label for="is_valid">Ενεργός</label>
						</div>
						<div class="row with-forms">
							<div class="col-md-6">
								<label for="user_fullname">Ονοματεπώνυμο</label>
								<input id="user_fullname" name="user_fullname" type="text" <?=(isset($dr_e["user_fullname"]) ? 'value="'.$dr_e['user_fullname'].'"' : "")?>>
							</div>
							<div class="col-md-6">
								<label for="region_id">Περιοχή</label>
								<select name="region_id" id="region_id" class="chosen-select-no-single" <?=($auth->UserType!='Administrator'?'disabled="disable"':'')?> >
									<option label="blank" value="">Επιλογή περιοχής</option>
									<?
										$filter=" AND is_valid='True'";
										$resultRegions = $db->sql_query("SELECT * FROM regions WHERE 1=1 ".$filter." ORDER BY region_name ");
										while ($drRegions = $db->sql_fetchrow($resultRegions)){
											if($auth->UserType!='Administrator'){
												$currRegion=$auth->UserRow['region_id'];
											} elseif(intval($dr_e['region_id'])>0){
												$currRegion=$dr_e['region_id'];
											}
											echo '<option value="'.$drRegions['region_id'].'" '.($drRegions['region_id']==$currRegion?' selected':'').'>'.$drRegions['region_name'].'</option>';
										}
									?>
								</select>
							</div>
						</div>

						<div class="row with-forms">
							<div class="col-md-6">
								<label for="email">email</label>
								<input id="email" name="email" type="text" <?=(isset($dr_e["email"]) ? 'value="'.$dr_e['email'].'"' : "")?>>
							</div>
							<div class="col-md-6">
								<label for="user_password">Κωδικός</label>
								<input id="user_password" name="user_password" type="text" <?=(isset($dr_e["user_password"]) ? 'value="'.$dr_e['user_password'].'"' : "")?>>
							</div>
						</div>
						
						<div class="row with-forms">
							<!-- Type -->
							<div class="col-md-6">
								<label for="phone">Τηλέφωνο</label>
								<input id="phone" name="phone" type="text" <?=(isset($dr_e["phone"]) ? 'value="'.$dr_e['phone'].'"' : "")?>>
							</div>
							<div class="col-md-6">
								<label for="phone">Κινητό Τηλέφωνο</label>
								<input id="mobilephone" name="mobilephone" type="text" <?=(isset($dr_e["mobilephone"]) ? 'value="'.$dr_e['mobilephone'].'"' : "")?>>
							</div>
						</div>
					</div>

					<div class="add-listing-section margin-top-45 padding-top-30">
						<!-- 
						<div class="add-listing-headline">
							<h3><i class="sl sl-icon-docs"></i> Details</h3>
						</div>
						-->

						<div class="form">
							<label for="phone">Σημειώσεις</label>
							<textarea class="WYSIWYG" id="description" name="description" cols="40" rows="3" spellcheck="true"><?=(isset($dr_e["description"]) ? $dr_e['description'] : "")?></textarea>
						</div>
						
						<a href="#" onClick="checkFields();" id="submitBtn" class="button border margin-top-15">Αποθήκευση</a>
						<a href="<?=$BaseUrl?>" class="button border margin-top-15">Επιστροφή</a>
					</div>


				</div>

			</div>
		</div>
	</div>
    


<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#user_fullname').val();
		if ( value.length >= 2){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
		}
	}
</script>
	<?
} else 	{
	?>    
	
	<div class="container" style="margin-bottom:50px;font-size:14px;">
		<div class="row">
			<div class="col-md-12">
				<h4 class="headline margin-top-70 margin-bottom-30" style="font-family: 'Commissioner', sans-serif;"><?=$nav?></h4>
				<table class="basic-table">
					<thead>
						<tr>
							<th>#</th>
							<th>Ενεργός</th>
							<th>Περιοχή</th>
							<th>email</th>
							<th>Ονοματεπώνυμο</th>
							<th>Κινητό</th>
							<th style="width:15%;">Ενέργεια</th>
						</tr>
					</thead>
					<tbody>
						<?	
							$query = "SELECT * FROM users WHERE 1=1 ".$filter." ORDER BY user_fullname ";
							$result = $db->sql_query($query);
							$counter = 0;
							while ($dr = $db->sql_fetchrow($result))
							{
								?>

									<tr>
										<td><?=$dr["user_id"]?></td>
										<td><?=$dr["is_valid"]?></td>
										<td><?=$dr["region_id"]?></td>
										<td><?=$dr["email"]?></td>
										<td><?=$dr["user_fullname"]?></td>
										<td><?=$dr["mobile_phone"]?></td>
										<td>
											<a style="padding:4px"  href="index.php?com=users&Command=edit&item=<?=$dr["user_id"]?>"><span style="font-size:22px;" class="im im-icon-File-Edit"></span> </a>
											<a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=users&Command=DELETE&item=<?=$dr["user_id"]?>');"><span  style="font-size:22px;" class="im im-icon-Delete-File"></span> </a></a>
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
		<div class="row" style="margin-top:30px;">
			<div class="col-md-12">
				<a href="index.php?com=users&item=" class="button border">Εισαγωγή</a><a href="index.php" class="button border">Επιστροφή</a>
			</div>
		</div>
	</div>
<? } ?> 
