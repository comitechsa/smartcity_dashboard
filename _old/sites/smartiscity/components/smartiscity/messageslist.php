<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

	if($auth->UserType != "Administrator") {  
			Redirect("index.php");
	}

global $nav;
$nav = 'Μηνύματα';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=messageslist";
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
			$PrimaryKeys["message_id"] = intval($_GET["item"]);
			$QuotFields["message_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}
		$Collector["user_id"] = $_POST["user_id"];
		$QuotFields["user_id"] = true;
		
		$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
		$QuotFields["is_valid"] = true;
		
		$Collector["title"] = $_POST["title"];
		$QuotFields["title"] = true;
		
		$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
		$QuotFields["description"] = true;
		
		$db->ExecuteUpdater("messages",$PrimaryKeys,$Collector,$QuotFields);		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") { 
		if($item != ""){
			//SOS Έλεγχο πριν τη διαγραφή
			$db->sql_query("DELETE FROM messages WHERE message_id=" . $item);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}

if(isset($_GET["item"])) {
	$query="SELECT * FROM messages WHERE message_id=".$_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["message_id"]) && intval($_GET['item'])>0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=messageslist");
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
							<h3 style="font-family: 'Commissioner', sans-serif;"><i class="sl sl-icon-doc"></i> Μηνύματα</h3>
						</div>

						<div class="checkboxes in-row margin-bottom-20">
							<input id="is_valid" class="check-a" type="checkbox" name="is_valid" <?=($dr_e['is_valid']=='True'?'checked':'')?>>
							<label for="is_valid">Ενεργό</label>
						</div>
						
						<div class="row with-forms">
							<div class="col-md-12">
								<label for="user_id">Χρήστης</label>
								<select name="user_id" id="user_id" class="chosen-select-no-single">
									<option label="blank" value="">Επιλογή χρήστη</option>
									<?
										$filter=" AND is_valid='True'";
										$resultUsers = $db->sql_query("SELECT * FROM users WHERE 1=1 ".$filter." ORDER BY user_fullname ");
										while ($drUsers = $db->sql_fetchrow($resultUsers)){
											echo '<option value="'.$drUsers['user_id'].'" '.($drUsers['user_id']==$dr_e['user_id']?' selected':'').'>'.$drUsers['user_fullname'].'</option>';
										}
									?>
								</select>
							</div>
						</div>
						
						<div class="row with-forms">
							<div class="col-md-12">
								<label for="title">Τιτλος</label>
								<input id="title" name="title" type="text" <?=(isset($dr_e["title"]) ? 'value="'.$dr_e['title'].'"' : "")?>>
							</div>
						</div>
					</div>

					<div class="add-listing-section margin-top-45 padding-top-30">
						<div class="form">
							<label for="description">Περιγραφή</label>
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
	var value = $('#title').val();
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
							<th>Ενεργό</th>
							<th>Τίτλος</th>
							<th>Χρήστης</th>
							<th>Ημ/νία εισαγωγής</th>
							<th style="width:25%;">Ενέργεια</th>
						</tr>
					</thead>
					<tbody>
						<?	$filter='';
							$query = "SELECT * FROM messages WHERE 1=1 ".$filter." ORDER BY date_insert DESC ";
							$result = $db->sql_query($query);
							$counter = 0;
							while ($dr = $db->sql_fetchrow($result))
							{
								?>

									<tr>
										<td><?=$dr["message_id"]?></td>
										<td><?=$dr["is_valid"]?></td>
										<td><?=$dr["title"]?></td>
										<td>
										<?
										$userRow=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$dr['user_id']);
										echo $userRow['user_fullname'];
										?>
										</td>
										<td><?=$dr["date_insert"]?></td>
										<td>
											<a style="padding:4px"  href="index.php?com=messageslist&Command=edit&item=<?=$dr["message_id"]?>"><span style="font-size:22px;" class="im im-icon-File-Edit"></span> </a>
											<a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=messageslist&Command=DELETE&item=<?=$dr["message_id"]?>');"><span  style="font-size:22px;" class="im im-icon-Delete-File"></span> </a>
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
				<a href="index.php?com=messageslist&item=" class="button border">Εισαγωγή</a><a href="index.php" class="button border">Επιστροφή</a>
			</div>
		</div>
	</div>
<? } ?> 
