<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
//require_once(dirname(__FILE__) . "/common.php");

	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");


global $nav;
$nav = rate;
$config["navigation"] = rate;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=rate";
$command=array();
$command=explode("&",$_POST["Command"]);

//if( $auth->UserType == "Administrator" )
//{
	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "SAVE")
		{

			//$testFields=(strlen(ltrim($_POST["name"]))>2 && 1==1);
			//echo $_POST["name"].'-'.intval($testFields);
			//exit;

			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
			{
				$PrimaryKeys["rate_id"] = intval($_GET["item"]);
				$QuotFields["rate_id"] = true;
			}
			
			$Collector["parent_id"] = (intval($_POST["parent_id"])>0?$_POST["parent_id"]:0);
			$QuotFields["parent_id"] = true;

			$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
			$QuotFields["is_valid"] = true;
		
			$Collector["priority"] = $_POST["priority"];
			$QuotFields["priority"] = true;
			
			$Collector["rate_name"] = $_POST["rate_name"];
			$QuotFields["rate_name"] = true;
			
			//$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
			//$QuotFields["description"] = true;
			
			$Collector["user_id"] = ($auth->UserType == "Administrator" ? $_POST["user_id"] : $auth->UserId);
			$QuotFields["user_id"] = true;
			
			$Collector["language_id"] = 1; //$_POST["language_id"];
			$QuotFields["language_id"] = true;
			
			$db->ExecuteUpdater("rate",$PrimaryKeys,$Collector,$QuotFields);
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
			if(isset($_POST["rate1lang"]) && intval($_POST["rate1lang"]) >0)
			{
				$drCheck1=$db->RowSelectorQuery("SELECT id FROM rate2l WHERE rate_id=".$item." AND lang_id=".$_POST['rate1lang']);
				if(intval($drCheck1['id'])>0)
				{
					$PrimaryKeys["id"] = intval($drCheck1['id']);
					$QuotFields["id"] = true;
				}
				$QuotFields["id"] = true;
				$Collector["rate_name"] = $_POST["rate_name1"];
				$QuotFields["rate_name"] = true;
				$Collector["rate_id"] = $item;
				$QuotFields["rate_id"] = true;
				$Collector["lang_id"] = $_POST["rate1lang"];
				$QuotFields["lang_id"] = true;
				$db->ExecuteUpdater("rate2l",$PrimaryKeys,$Collector,$QuotFields);
			}
			//lang2
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			if(isset($_POST["rate2lang"]) && intval($_POST["rate2lang"]) >0)
			{
				$drCheck2=$db->RowSelectorQuery("SELECT id FROM rate2l WHERE rate_id=".$item." AND lang_id=".$_POST['rate2lang']);
				if(intval($drCheck2['id'])>0)
				{
					$PrimaryKeys["id"] = intval($drCheck2['id']);
					$QuotFields["id"] = true;
				}
				$Collector["rate_name"] = $_POST["rate_name2"];
				$QuotFields["rate_name"] = true;
				$Collector["rate_id"] = $item;
				$QuotFields["rate_id"] = true;
				$Collector["lang_id"] = $_POST["rate2lang"];
				$QuotFields["lang_id"] = true;
				$db->ExecuteUpdater("rate2l",$PrimaryKeys,$Collector,$QuotFields);
			}
			//lang3
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			if(isset($_POST["rate3lang"]) && intval($_POST["rate3lang"]) >0)
			{
				$drCheck3=$db->RowSelectorQuery("SELECT id FROM rate2l WHERE rate_id=".$item." AND lang_id=".$_POST['rate3lang']);
				if(intval($drCheck3['id'])>0)
				{
					$PrimaryKeys["id"] = intval($drCheck3['id']);
					$QuotFields["id"] = true;
				}
				$Collector["rate_name"] = $_POST["rate_name3"];
				$QuotFields["rate_name"] = true;
				$Collector["rate_id"] = $item;
				$QuotFields["rate_id"] = true;
				$Collector["lang_id"] = $_POST["rate3lang"];
				$QuotFields["lang_id"] = true;
				$db->ExecuteUpdater("rate2l",$PrimaryKeys,$Collector,$QuotFields);
			}
			//lang4
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			if(isset($_POST["rate4lang"]) && intval($_POST["rate4lang"]) >0)
			{
				$drCheck4=$db->RowSelectorQuery("SELECT id FROM rate2l WHERE rate_id=".$item." AND lang_id=".$_POST['rate4lang']);
				if(intval($drCheck4['id'])>0)
				{
					$PrimaryKeys["id"] = intval($drCheck4['id']);
					$QuotFields["id"] = true;
				}
				$Collector["rate_name"] = $_POST["rate_name4"];
				$QuotFields["rate_name"] = true;
				$Collector["rate_id"] = $item;
				$QuotFields["rate_id"] = true;
				$Collector["lang_id"] = $_POST["rate4lang"];
				$QuotFields["lang_id"] = true;
				$db->ExecuteUpdater("rate2l",$PrimaryKeys,$Collector,$QuotFields);
			}
			
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { //$command[0] ==
			if($item != "")
			{
				$checkDelete=$db->sql_query("SELECT * FROM rate WHERE parent_id=".$item);
				if ($db->sql_numrows($checkDelete)>0) {
					$messages->addMessage(errorRecordsFound);
					Redirect($BaseUrl);				
				}
				
				//$db->sql_query("DELETE FROM rate WHERE rate_id=" . $item);
				//$db->sql_query("DELETE FROM rate2l WHERE rate_id=" . $item);				
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
			$dr_user=$db->RowSelectorQuery("SELECT user_id FROM rate WHERE rate_id=".$_GET["item"]);
			//$userID=$auth->UserId;
			$userID=$dr_user['user_id'];
			//$filter="";

		} else {
			$userID=$auth->UserId;
		}
		
		$filter=" AND user_id=".$userID;
		$query="SELECT * FROM rate WHERE 1=1 ".$filter." AND rate_id=".$_GET['item'];

		$dr_e = $db->RowSelectorQuery($query);
		if (!isset($dr_e["rate_id"])) {
			$messages->addMessage("NOT FOUND!!!");
			Redirect("index.php?com=rate");
		}
	}
	
	$dr_User=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$userID);
	$lang1ID=$dr_User['lang1ID'];
	$lang2ID=$dr_User['lang2ID'];
	$lang3ID=$dr_User['lang3ID'];
	$lang4ID=$dr_User['lang4ID'];

	if(intval($lang1ID)>0) $dr_l1 = $db->RowSelectorQuery("SELECT * FROM rate2l WHERE rate_id = ".$dr_e['rate_id']." AND lang_id = ".$lang1ID);
	if(intval($lang2ID)>0) $dr_l2 = $db->RowSelectorQuery("SELECT * FROM rate2l WHERE rate_id = ".$dr_e['rate_id']." AND lang_id = ".$lang2ID);
	if(intval($lang3ID)>0) $dr_l3 = $db->RowSelectorQuery("SELECT * FROM rate2l WHERE rate_id = ".$dr_e['rate_id']." AND lang_id = ".$lang3ID);
	if(intval($lang4ID)>0) $dr_l4 = $db->RowSelectorQuery("SELECT * FROM rate2l WHERE rate_id = ".$dr_e['rate_id']." AND lang_id = ".$lang4ID);
	
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

                <div class="controls">
                    <label for="parent_id" class="control-label"><?=rate?></label>
                    <select name="parent_id" id="parent_id">
					<option value=""><?=choice?></option>
					<?
					$filter="";
					if($_GET['item']!='') $filter=" AND rate_id!=".$dr_e['rate_id'];
					$queryRate = "SELECT * FROM rate WHERE is_valid='True'".$filter." AND user_id = ".$auth->UserId." ORDER BY rate_name";
					
					$resultRate = $db->sql_query($queryRate);
					while ($drr = $db->sql_fetchrow($resultRate)) {?>
					<option value="<?=$drr["rate_id"]; ?>"<?=(($drr['rate_id']==$dr_e['parent_id'] && intval($dr_e['parent_id'])!=0)?' selected':'')?>><?php echo $drr["rate_name"]; ?></option>
					<? 	}?>
                    </select>
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
						<input type="text" name="rate_name" id="rate_name" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["rate_name"]) ? 'value="'.$dr_e['rate_name'].'"' : "")?> >
					</div>
				</div>
					
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
									<input type="text" name="rate_name1" id="rate_name1" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l1["rate_name"]) ? $dr_l1["rate_name"] : "")?>' >
									<input type="hidden" name="rate1lang" value=<?=$lang1ID?>>
								</div>
							</div>
						</div>
						<? if(strlen($lang2)>1) {?>
						<div class="tab-pane" id="lang2">
							<div class="control-group">
								<div class="controls">
									<label for="textfield" class="control-label"><?=name?></label>
									<input type="text" name="rate_name2" id="rate_name2" class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l2["rate_name"]) ? $dr_l2["rate_name"] : "")?>' >
									<input type="hidden" name="rate2lang" value=<?=$lang2ID?>>
								</div>
							</div>
						</div>
						<? } ?>
						<? if(strlen($lang3)>1) {?>
						<div class="tab-pane" id="lang3">
							<div class="control-group">
								<div class="controls">
									<label for="textfield" class="control-label"><?=name?></label>
									<input type="text" name="rate_name3" id="rate_name3" class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l3["rate_name"]) ? $dr_l3["rate_name"] : "")?>' >
									<input type="hidden" name="rate3lang" value=<?=$lang3ID?>>
								</div>
							</div>
						</div>
						<? } ?>
						<? if(strlen($lang4)>1) {?>
						<div class="tab-pane" id="lang4">
							<div class="control-group">
								<div class="controls">
									<label for="textfield" class="control-label"><?=name?></label>
									<input type="text" name="rate_name4" id="rate_name4" class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l4["rate_name"]) ? $dr_l4["rate_name"] : "")?>' >
									<input type="hidden" name="rate4lang" value=<?=$lang4ID?>>
								</div>
							</div>
						</div>
						<? } ?>
					</div>
				</div>
			</div>
		</div>
			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
			<a href="index.php?com=rate"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#description').val();
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
				<div class="box-title"><h3><i class="icon-table"></i><?=$nav?></h3>
			</div>
			<div class="box-content nopadding">
				<? if($auth->UserType=="Administrator"){ ?>
				<table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
					<thead>
						<tr>
							<? echo ($auth->UserType=="Administrator" ? "<th>".customer."</th>" : ""); ?>
							<th><?=friendlyName?></th>
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody> 
					<?	
						$filter=($auth->UserType!="Administrator"?" AND user_id=".$auth->UserId:"");
                        $query = "SELECT * FROM rate WHERE 1=1 ".$filter." ORDER BY rate_name ";
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
                                    <td><?=$dr["rate_name"]?></td>
                                    <td>
										<a style="padding:4px"  href="index.php?com=rate&Command=edit&item=<?=$dr["rate_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=rate&Command=DELETE&item=<?=$dr["rate_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
                                    </td>
                                </tr>
                            <?
                        }
                        $db->sql_freeresult($result);
                    ?>
					</tbody>
				</table>
				<? } ?>
				
				<br/><br/>
				<?
					$filter=($auth->UserType!="Administrator"?" AND user_id=".$auth->UserId:"");
					$queryTree = "SELECT * FROM rate WHERE 1=1 ".$filter." AND is_valid='True'  AND ( parent_id is null OR parent_id = '' OR parent_id = 0) ORDER BY priority";
					$resultTree = $db->sql_query($queryTree);

					$Response = '<div class="css-treeview">';
					$Response .= '<ul>';

					while ($drTree = $db->sql_fetchrow($resultTree)) 
					{
						$Response .= "<li><input type='checkbox' id='item-".$drTree['rate_id']."' checked='checked'/><label for='item-".$drTree['rate_id']."'>".$drTree['rate_name']." <a href='index.php?com=rate&Command=edit&item=".$drTree['rate_id']."'> <i class='icon-edit'> </i></a> <a href='#' onclick=\"ConfirmDelete('".deleteConfirm."','index.php?com=rate&Command=DELETE&item=".$drTree["rate_id"]."')\";><span><i class='icon-trash'></i></span></a></label>";
						$Response .= rateDepth($drTree["rate_id"]);
						$Response .= "</li>";
					}
					$db->sql_freeresult($resultTree);
						
					$Response .= '</ul>';
					$Response .= '</div>';
					echo $Response;
				?>
				<br/>
			</div>                
		</div> 
	</div>
<? } ?> 

    <script language="javascript">
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        //CKEDITOR.replace( 'description' );
    </script>
<?
	function rateDepth($id)
	{
		global $config , $db, $auth;
		$queryDepth = "SELECT * FROM rate WHERE is_valid='True' AND parent_id= ".$id." ORDER BY priority";

		$resultDepth = $db->sql_query($queryDepth);
		if($db->sql_numrows($resultDepth) > 0)
		{
			$Response = "<ul>";
			while ($drDepth = $db->sql_fetchrow($resultDepth))
			{
				$Response .="<li><input type='checkbox' id='item-".$drDepth['rate_id']."' checked='checked'/><label for='item-".$drDepth['rate_id']."'>".$drDepth['rate_name']." <a href='index.php?com=rate&Command=edit&item=".$drDepth['rate_id']."'> <i class='icon-edit'></i></a> <a href='#' onclick=\"ConfirmDelete('".deleteConfirm."','index.php?com=rate&Command=DELETE&item=".$drDepth["rate_id"]."')\";><span><i class='icon-trash'></i></span></a></label>";
				$Response .= rateDepth($drDepth["rate_id"]);
				$Response .= "</li>";
			}
			$Response .= "</ul>";
			//return substr($Response,0,strlen($Response)-1);
			return $Response;
		}
		$db->sql_freeresult($resultDepth);
	}
?>