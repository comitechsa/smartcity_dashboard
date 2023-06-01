<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
//require_once(dirname(__FILE__) . "/common.php");

	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");


global $nav;
$nav = productsandservices;
$config["navigation"] = productsandservices;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=products";
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
				$PrimaryKeys["product_id"] = intval($_GET["item"]);
				$QuotFields["product_id"] = true;
			}
			
			$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
			$QuotFields["is_valid"] = true;

			$Collector["priority"] = $_POST["priority"];
			$QuotFields["priority"] = true;
		
			$Collector["product_name"] = $_POST["product_name"];
			$QuotFields["product_name"] = true;
			
			//$Collector["product_description"] = html_entity_decode($_POST['product_description'], ENT_QUOTES, "UTF-8");
			//$QuotFields["product_description"] = true;
			
			$Collector["price"] = $_POST["price"];
			$QuotFields["price"] = true;
			
			$Collector["offer_price"] = $_POST["offer_price"];
			$QuotFields["offer_price"] = true;
			
			$Collector["category_id"] = $_POST["category_id"];
			$QuotFields["category_id"] = true;
			
			$Collector["user_id"] = ($auth->UserType == "Administrator" ? $_POST["user_id"] : $auth->UserId);
			$QuotFields["user_id"] = true;
			
			$Collector["language_id"] = 1; //$_POST["language_id"];
			$QuotFields["language_id"] = true;
			
			$Collector["last_change"] = date('Y-m-d H:i:s');
			$QuotFields["last_change"] = true;
			
			$Collector["last_change_user"] = $auth->UserId;
			$QuotFields["last_change_user"] = true;
			
			$db->ExecuteUpdater("products",$PrimaryKeys,$Collector,$QuotFields);
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
			if(isset($_POST["product1lang"]) && intval($_POST["product1lang"]) >0)
			{
				$drCheck1=$db->RowSelectorQuery("SELECT id FROM products2l WHERE product_id=".$item." AND lang_id=".$_POST['product1lang']);
				if(intval($drCheck1['id'])>0)
				{
					$PrimaryKeys["id"] = intval($drCheck1['id']);
					$QuotFields["id"] = true;
				}
				$Collector["product_name"] = $_POST["product_name1"];
				$QuotFields["product_name"] = true;
				$Collector["product_description"] = html_entity_decode($_POST['product_description1'], ENT_QUOTES, "UTF-8");
				$QuotFields["product_description"] = true;
				$Collector["product_id"] = $item;
				$QuotFields["product_id"] = true;
				$Collector["lang_id"] = $_POST["product1lang"];
				$QuotFields["lang_id"] = true;
				$db->ExecuteUpdater("products2l",$PrimaryKeys,$Collector,$QuotFields);
			}
			//lang2
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			if(isset($_POST["product2lang"]) && intval($_POST["product2lang"]) >0)
			{
				$drCheck2=$db->RowSelectorQuery("SELECT id FROM products2l WHERE product_id=".$item." AND lang_id=".$_POST['product2lang']);
				if(intval($drCheck2['id'])>0)
				{
					$PrimaryKeys["id"] = intval($drCheck2['id']);
					$QuotFields["id"] = true;
				}
				$Collector["product_name"] = $_POST["product_name2"];
				$QuotFields["product_name"] = true;
				$Collector["product_description"] = html_entity_decode($_POST['product_description2'], ENT_QUOTES, "UTF-8");
				$QuotFields["product_description"] = true;
				$Collector["product_id"] = $item;
				$QuotFields["product_id"] = true;
				$Collector["lang_id"] = $_POST["product2lang"];
				$QuotFields["lang_id"] = true;
				$db->ExecuteUpdater("products2l",$PrimaryKeys,$Collector,$QuotFields);
			}
			//lang3
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			if(isset($_POST["product3lang"]) && intval($_POST["product3lang"]) >0)
			{
				$drCheck3=$db->RowSelectorQuery("SELECT id FROM products2l WHERE product_id=".$item." AND lang_id=".$_POST['product3lang']);
				if(intval($drCheck3['id'])>0)
				{
					$PrimaryKeys["id"] = intval($drCheck3['id']);
					$QuotFields["id"] = true;
				}
				$Collector["product_name"] = $_POST["product_name3"];
				$QuotFields["product_name"] = true;
				$Collector["product_description"] = html_entity_decode($_POST['product_description3'], ENT_QUOTES, "UTF-8");
				$QuotFields["product_description"] = true;
				$Collector["product_id"] = $item;
				$QuotFields["product_id"] = true;
				$Collector["lang_id"] = $_POST["product3lang"];
				$QuotFields["lang_id"] = true;
				$db->ExecuteUpdater("products2l",$PrimaryKeys,$Collector,$QuotFields);
			}
			//lang4
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			if(isset($_POST["product4lang"]) && intval($_POST["product4lang"]) >0)
			{
				$drCheck4=$db->RowSelectorQuery("SELECT id FROM products2l WHERE product_id=".$item." AND lang_id=".$_POST['product4lang']);
				if(intval($drCheck4['id'])>0)
				{
					$PrimaryKeys["id"] = intval($drCheck4['id']);
					$QuotFields["id"] = true;
				}
				$Collector["product_name"] = $_POST["product_name4"];
				$QuotFields["product_name"] = true;
				$Collector["product_description"] = html_entity_decode($_POST['product_description4'], ENT_QUOTES, "UTF-8");
				$QuotFields["product_description"] = true;
				$Collector["product_id"] = $item;
				$QuotFields["product_id"] = true;
				$Collector["lang_id"] = $_POST["product4lang"];
				$QuotFields["lang_id"] = true;
				$db->ExecuteUpdater("products2l",$PrimaryKeys,$Collector,$QuotFields);
			}
			
			
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { //$command[0] ==
			//$item=(isset($command[1]) && $command[1]!="")? $command[1]:"";
			if($item != "")
			{
				$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
				$query="SELECT * FROM products WHERE 1=1 ".$filter." AND product_id=".$_GET['item'];
				
				$drAccess=$db->RowSelectorQuery($query);
				if(!isset($drAccess['product_id'])) {
					$messages->addMessage("ACCESS RESTRICTED!!!");
					Redirect($BaseUrl);
				}
				$checkDelete=$db->sql_query("SELECT * FROM products WHERE product_id=".$item);
				if ($db->sql_numrows($checkDelete)<1) {
					$messages->addMessage(errorRecordsFound);
					Redirect($BaseUrl);				
				}

				$db->sql_query("DELETE FROM products WHERE product_id=" . $item);
				$db->sql_query("DELETE FROM products2l WHERE product_id=" . $item);
				$messages->addMessage("DELETE!!!");
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
			$dr_user=$db->RowSelectorQuery("SELECT user_id FROM products WHERE product_id=".$_GET["item"]);
			//$userID=$auth->UserId;
			$userID=$dr_user['user_id'];
			//$filter="";
		} else {
			$userID=$auth->UserId;
		}
		
		//old
		//$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
		//new
		$filter=" AND (user_id=".($auth->UserRow['parent']==0?$userID:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$userID:$auth->UserRow['parent'])."))";
		
		$query="SELECT * FROM products WHERE 1=1 ".$filter." AND product_id=".$_GET['item'];

		$dr_e = $db->RowSelectorQuery($query);
		if (!isset($dr_e["product_id"])) {
			$messages->addMessage("NOT FOUND!!!");
			Redirect("index.php?com=products");
		}
	}
	//$query="SELECT * FROM products WHERE product_id=".$_GET['item'];
	//$dr_e = $db->RowSelectorQuery($query);
	$dr_User=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$userID);
	$lang1ID=$dr_User['lang1ID'];
	$lang2ID=$dr_User['lang2ID'];
	$lang3ID=$dr_User['lang3ID'];
	$lang4ID=$dr_User['lang4ID'];

	if(intval($lang1ID)>0) $dr_l1 = $db->RowSelectorQuery("SELECT * FROM products2l WHERE product_id = ".$dr_e['product_id']." AND lang_id = ".$lang1ID);
	if(intval($lang2ID)>0) $dr_l2 = $db->RowSelectorQuery("SELECT * FROM products2l WHERE product_id = ".$dr_e['product_id']." AND lang_id = ".$lang2ID);
	if(intval($lang3ID)>0) $dr_l3 = $db->RowSelectorQuery("SELECT * FROM products2l WHERE product_id = ".$dr_e['product_id']." AND lang_id = ".$lang3ID);
	if(intval($lang4ID)>0) $dr_l4 = $db->RowSelectorQuery("SELECT * FROM products2l WHERE product_id = ".$dr_e['product_id']." AND lang_id = ".$lang4ID);
	
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
                    <select name="user_id" id="user_id" onChange="getCategories(this.value);">
                        <option value=""><?=choice?></option>
                        <?
                        $query = "SELECT * FROM users WHERE is_valid='True' ORDER BY company_name";
                        $result = $db->sql_query($query);
                        while ($dr = $db->sql_fetchrow($result)) {?>
                            <option value="<?php echo $dr["user_id"]; ?>" <?=$dr_e['user_id']?><?=($dr_e['user_id']==$dr['user_id']?' selected':'')?>><?php echo $dr["company_name"]; ?></option>
                        <? } ?>
                    </select>
                </div> <? } ?>
                <div class="check-line">
                    <label class="inline" for="is_valid"><?=active?></label>
                    <div class="controls">
                        <input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>  />
                    </div>
                </div>
                <div class="controls">
                    <label for="textfield" class="control-label"><?=category?></label>
                    <?
						//$filter=($auth->UserType!="Administrator"?" AND user_id=".$auth->UserId:"");
						if($auth->UserType!="Administrator"){
							$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
                        }
                        $query = "SELECT * FROM categories WHERE is_valid='True' ".$filter." ORDER BY category_name";
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
                        <input type="text" name="product_name" id="product_name" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["product_name"]) ? 'value="'.$dr_e['product_name'].'"' : "")?> >
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <label for="textfield" class="control-label"><?=price?></label>
                        <input type="text" name="price" id="price" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["price"]) ? 'value="'.$dr_e['price'].'"' : "")?> >
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
									<input type="text" name="product_name1" id="product_name1" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l1["product_name"]) ? $dr_l1["product_name"] : "")?>' >
									<input type="hidden" name="product1lang" value=<?=$lang1ID?>>
								</div>
							</div>
							<div class="control-group">
								<label for="textarea" class="control-label"><?=description?></label>
								<div class="controls">
									<textarea name="product_description1" rows="6" id="product_description1" class="input-block-level"><?=(isset($dr_l1["product_description"]) ? $dr_l1["product_description"] : "")?></textarea>
								</div>
							</div>
						</div>
						<? if(strlen($lang2)>1) {?>
						<div class="tab-pane" id="lang2">
							<div class="control-group">
								<div class="controls">
									<label for="textfield" class="control-label"><?=name?></label>
									<input type="text" name="product_name2" id="product_name2" class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l2["product_name"]) ? $dr_l2["product_name"] : "")?>' >
									<input type="hidden" name="product2lang" value=<?=$lang2ID?>>
								</div>
							</div>
							<div class="control-group">
								<label for="textarea" class="control-label"><?=description?></label>
								<div class="controls">
									<textarea name="product_description2" rows="6" id="product_description2" class="input-block-level"><?=(isset($dr_l2["product_description"]) ? $dr_l2["product_description"] : "")?></textarea>
								</div>
							</div>
						</div>
						<? } ?>
						<? if(strlen($lang3)>1) {?>
						<div class="tab-pane" id="lang3">
							<div class="control-group">
								<div class="controls">
									<label for="textfield" class="control-label"><?=name?></label>
									<input type="text" name="product_name3" id="product_name3" class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l3["product_name"]) ? $dr_l3["product_name"] : "")?>' >
									<input type="hidden" name="product3lang" value=<?=$lang3ID?>>
								</div>
							</div>
							<div class="control-group">
								<label for="textarea" class="control-label"><?=description?></label>
								<div class="controls">
									<textarea name="product_description3" rows="6" id="product_description3" class="input-block-level"><?=(isset($dr_l3["product_description"]) ? $dr_l3["product_description"] : "")?></textarea>
								</div>
							</div>
						</div>
						<? } ?>
						<? if(strlen($lang4)>1) {?>
						<div class="tab-pane" id="lang4">
							<div class="control-group">
								<div class="controls">
									<label for="textfield" class="control-label"><?=name?></label>
									<input type="text" name="product_name4" id="product_name4" class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" value='<?=(isset($dr_l4["product_name"]) ? $dr_l4["product_name"] : "")?>' >
									<input type="hidden" name="product4lang" value=<?=$lang4ID?>>
								</div>
							</div>
							<div class="control-group">
								<label for="textarea" class="control-label"><?=description?></label>
								<div class="controls">
									<textarea name="product_description4" rows="6" id="product_description4" class="input-block-level"><?=(isset($dr_l4["product_description"]) ? $dr_l4["product_description"] : "")?></textarea>
								</div>
							</div>
						</div>
						<? } ?>
					</div>
				</div>
            </div>
		</div>
                <a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
                <a href="index.php?com=products"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#product_name').val();
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
				<table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
					<thead>
						<tr>
							<? echo ($auth->UserType=="Administrator" ? "<th>".customer."</th>" : ""); ?>
							<th><?=displayOrder?></th>
							<th><?=category?></th>
							<th><?=friendlyName?></th>
							<th><?=price?></th>
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody> 
					<?	
						//$filter=($auth->UserType!="Administrator"?" AND user_id=".$auth->UserId:"");
						if($auth->UserType!="Administrator"){
							$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
                        }
                        $query = "SELECT * FROM products WHERE 1=1 ".$filter." ORDER BY product_name ";
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
									<td>
										<?=$dr_c['priority'];?>
									</td>
                                    <td>
									<?  
									
									$dr_c=$db->RowSelectorQuery("SELECT * FROM categories WHERE category_id=".$dr['category_id']);
                                    echo $dr_c['category_name'];
                                    ?>
                                    </td>
                                    <td><?=$dr["product_name"]?></td>
                                    <td><?=$dr["price"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=products&Command=edit&item=<?=$dr["product_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=products&Command=DELETE&item=<?=$dr["product_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
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
        CKEDITOR.replace( 'product_description1' );
        CKEDITOR.add;
        CKEDITOR.replace( 'product_description2' );
        CKEDITOR.add;
        CKEDITOR.replace( 'product_description3' );
        CKEDITOR.add;
        CKEDITOR.replace( 'product_description4' );
		
	function getCategories(val) {
		$.ajax({
		type: "POST",
		url: "http://panel.spotyy.com/sites/hotbox/components/hotbox/categories_list.php",
		data:'user_id='+val,
		success: function(data){
			$("#category_id").html(data);
		}
		});
	}
    </script>