<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

global $nav;
$nav = 'Χρήστες αξιολόγησης';
$config["navigation"] = 'Χρήστες αξιολόγησης';
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=rateusers";
$command=array();
$command=explode("&",$_POST["Command"]);

//if( $auth->UserType == "Administrator" )
//{
	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "SAVE")
		{

			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
			{
				$PrimaryKeys["rateuser_id"] = intval($_GET["item"]);
				$QuotFields["rateuser_id"] = true;
			}
			
			$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
			$QuotFields["is_valid"] = true;

			$Collector["full_name"] = $_POST["full_name"];
			$QuotFields["full_name"] = true;
			
			$Collector["email"] = $_POST["email"];
			$QuotFields["email"] = true;
			
			$Collector["fromDate"] = $_POST["fromDate"];
			$QuotFields["fromDate"] = true;
			
			$Collector["toDate"] = $_POST["toDate"];
			$QuotFields["toDate"] = true;
			
			$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
			$QuotFields["description"] = true;
			
			$Collector["code"] = ($_POST['code']=='' ? randomCode(15) :$_POST["code"]);
			$QuotFields["code"] = true;
			
			$Collector["last_change"] = date('Y-m-d H:i:s');
			$QuotFields["last_change"] = true;
			
			$Collector["last_change_user"] = $auth->UserId;
			$QuotFields["last_change_user"] = true;			
			
			$db->ExecuteUpdater("rate_users",$PrimaryKeys,$Collector,$QuotFields);
		
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { // Πρέπει να γίνει
			if($item != "")
			{
				$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
				$query="SELECT * FROM rate_categories WHERE 1=1 ".$filter." AND category_id=".$_GET['item'];
				$drAccess=$db->RowSelectorQuery($query);
				if(!isset($drAccess['category_id'])) {
					$messages->addMessage("ACCESS RESTRICTED!!!");
					Redirect($BaseUrl);
				}
				$checkDelete=$db->sql_query("SELECT * FROM rates WHERE category_id=".$item);
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
//}

if(isset($_GET["item"])) {
	if($_GET["item"]=="") {
		$userID=$auth->UserId;
	} else {
		if($auth->UserType == "Administrator") {
			$dr_user=$db->RowSelectorQuery("SELECT user_id FROM rate_users WHERE rateuser_id=".$_GET["item"]);
			$userID=$dr_user['user_id'];
		} else {
			$userID=$auth->UserId;
		}
		
		$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
		$query="SELECT * FROM rate_users WHERE 1=1 ".$filter." AND rateuser_id=".$_GET['item'];

		$dr_e = $db->RowSelectorQuery($query);
		if (!isset($dr_e["rateuser_id"])) {
			$messages->addMessage("NOT FOUND!!!");
			Redirect("index.php?com=rate_users");
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
				</div> 
				<? } ?>
				<div class="check-line">
					<label class="inline" for="is_valid"><?=active?></label>
					<div class="controls">
						<input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>  />
					</div>
				</div>
                    
				<div class="control-group">
					<div class="controls">
						<label for="full_name" class="control-label"><?=fullName?></label>
						<input type="text" name="full_name" id="full_name" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["full_name"]) ? 'value="'.$dr_e['full_name'].'"' : "")?> >
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<label for="email" class="control-label">email</label>
						<input type="text" name="email" id="email" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["email"]) ? 'value="'.$dr_e['email'].'"' : "")?> >
					</div>
				</div>
				
				<div class="control-group">
					<label for="textfield" class="control-label"><?=statFrom?></label>
					<div class="controls">
						<input type="text" name="fromDate" id="fromDate" class="input-medium datepick" data-date-format="yyyy-mm-dd" value="<?=(isset($_REQUEST["fromDate"]) ? $_REQUEST["fromDate"] : date('Y-m-d') )?>">
					</div>
				</div>
				
				<div class="control-group">
					<label for="textfield" class="control-label"><?=statTo?></label>
					<div class="controls">
						<input type="text" name="toDate" id="toDate" class="input-medium datepick" data-date-format="yyyy-mm-dd" value="<?=(isset($_REQUEST["toDate"]) ? $_REQUEST["toDate"] : date('Y-m-d') )?>">
					</div>
				</div>
				
				
				<div class="control-group">
					<label for="textarea" class="control-label"><?=description?></label>
					<div class="controls">
						<textarea name="description" rows="10" id="description" class="input-block-level"><?=(isset($dr_e["description"]) ? $dr_e["description"] : "")?></textarea>
					</div>
				</div>
			</div>
		</div>
			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
			<a href="index.php?com=rateusers"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#full_name').val();
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
							<th><?=site_statusCompleted?></th>
							<th><?=fullName?></th>
							<th>email</th>
							<th><?=insertDate?></th>
							<th><?=completeDate?></th>
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody> 
					<?	
						if($auth->UserType!="Administrator"){
							$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
                        }
						$query = "SELECT * FROM rate_users WHERE 1=1 ".$filter." ORDER BY full_name ";
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
									<td><?=$dr["completed"]?></td>
									<td><?=$dr["full_name"]?></td>
									<td><?=$dr["email"]?></td>
                                    <td><?=$dr["last_change"]?></td>
									<td><?=$dr["dateCompleted"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=rateusers&Command=edit&item=<?=$dr["rateuser_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=rateusers&Command=DELETE&item=<?=$dr["rateuser_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
                                        <a style="padding:4px"  href="index.php?com=rateusers&Command=send&item=<?=$dr["rateuser_id"]?>"><i class="icon-envelope"></i> <?=send?></a>
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
        CKEDITOR.replace( 'description' );
        //CKEDITOR.add;
    </script>