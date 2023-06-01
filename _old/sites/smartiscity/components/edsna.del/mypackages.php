<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
//if($auth->UserType != "Administrator") Redirect("index.php");

//require_once(dirname(__FILE__) . "/common.php");
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
global $nav;
$nav = advPackages;
$config["navigation"] = advPackages;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=mypackages";
$command=array();
$command=explode("&",$_POST["Command"]);

	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "SAVE")
		{
			if($auth->UserType != "Administrator") {  
				$drAccess=$db->RowSelectorQuery("SELECT t1.user_id, t2.purchasedpackage_id FROM ads_orders t1 INNER JOIN purchasedpackages t2 ON t1.order_id=t2.order_id WHERE purchasedpackage_id=".$_GET['item']." AND (user_id=". $auth->UserId." OR user_id IN (SELECT parent FROM users WHERE user_id=".$auth->UserId."))");
				if(!isset($drAccess['purchasedpackage_id'])) {
					$messages->addMessage("ACCESS RESTRICTED!!!");
					Redirect("index.php");
				}
			}
			
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
			{
				$PrimaryKeys["purchasedpackage_id"] = intval($_GET["item"]);
				$QuotFields["purchasedpackage_id"] = true;
			} 
			
			$Collector["friendly_name"] = $_POST["friendly_name"];
			$QuotFields["friendly_name"] = true;

			$Collector["ads_id"] = $_POST["ads_id"];
			$QuotFields["ads_id"] = true;
			
			$Collector["sex"] = $_POST["sex"];
			$QuotFields["sex"] = true;
			
			$ages='';
			$resultAges = $db->sql_query("SELECT * FROM ages");
			while ($drAges = $db->sql_fetchrow($resultAges)){
				if($_POST['age'.$drAges['age_id']]=='on'){
					$ages.=$drAges['age_id'].',';
				}
			}
			$ages = rtrim($ages,",");
			
			$regions='';
			$resultRegions = $db->sql_query("SELECT * FROM regions");
			while ($drRegions = $db->sql_fetchrow($resultRegions)){
				if($_POST['region'.$drRegions['region_id']]=='on'){
					$regions.=$drRegions['region_id'].',';
				}
			}			
			$regions = rtrim($regions,",");
			
			$Collector["ages"] = $ages;
			$QuotFields["ages"] = true;
			
			$Collector["regions"] = $regions;
			$QuotFields["regions"] = true;
			
			$Collector["last_user"] = $auth->UserRow['user_id'];
			$QuotFields["last_user"] = true;
			
			$Collector["last_change"] = date('Y-m-d H:i:s');
			$QuotFields["last_change"] = true;
			
			$db->ExecuteUpdater("purchasedpackages",$PrimaryKeys,$Collector,$QuotFields);
			if($auth->UserType == "Administrator") { 
				foreach ($_POST as $key => $value) {
					if(intval($key)>0){
						//echo "{$key} => {$value} ";
						//echo "SELECT * FROM rolesrates WHERE parent=".$_GET['item']." AND role=".$key." <br>";
						//$dr_e = $db->RowSelectorQuery("SELECT * FROM rolesrates WHERE parent=".$_GET['item']." AND role=".$key);
						//update
						$db->sql_query("UPDATE purchasedpackagerates SET percent=".$value." WHERE purchasedpackagerate_id=".$key);
						//echo "UPDATE rolesrate set percent=".$value." WHERE id=".$dr_e['id'].'<br>';
						//echo "UPDATE purchasedpackagerates SET percent=".$value." WHERE purchasedpackagerate_id=".$key.'<br>';
					}
				}
			}
				
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { 
			if($item != "")
			{
				//ελεγχος πριν τη διαγραφή
				/*
				$checkDelete=$db->sql_query("SELECT * FROM rolesrates WHERE parent=".$item." OR role=".$item);
				if ($db->sql_numrows($checkDelete)>0) {
					$messages->addMessage(errorRecordsFound);
					Redirect($BaseUrl);				
				}
				*/
				//$db->sql_query("DELETE FROM ads_packages WHERE package_id=" . $item);
				//$messages->addMessage("DELETED!!!");
				//Redirect($BaseUrl);
			}
		}
	}


if(isset($_GET["item"])) {
	if($auth->UserType != "Administrator") {  
		$drAccess=$db->RowSelectorQuery("SELECT t1.user_id, t2.purchasedpackage_id FROM ads_orders t1 INNER JOIN purchasedpackages t2 ON t1.order_id=t2.order_id WHERE purchasedpackage_id=".$_GET['item']." AND (user_id=". $auth->UserId." OR user_id IN (SELECT parent FROM users WHERE user_id=".$auth->UserId."))");
		if(!isset($drAccess['purchasedpackage_id'])) {
			$messages->addMessage("ACCESS RESTRICTED!!!");
			Redirect("index.php");
		}
	}
	$query = "SELECT * FROM purchasedpackages WHERE purchasedpackage_id=".$_GET["item"];
	$dr_e = $db->RowSelectorQuery($query);
	
	if(intval($dr_e['hits'])>0){
		$messages->addMessage("ACCESS RESTRICTED!!!");
		Redirect("index.php?com=mypackages");
	}
	if(intval($_GET['item'])>0){
		$agesArray=explode(',',$dr_e['ages']);
		$regionsArray=explode(',',$dr_e['regions']);
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
				<div class="control-group">
					<label for="name" class="control-label"><?=friendlyName?></label>
					<div class="controls">
						<input type="text" name="friendly_name" id="friendly_name" class="input-xxsmall" value="<?=(isset($dr_e["friendly_name"]) ? $dr_e["friendly_name"] : "")?>">
					</div>
				</div>
				<div class="controls">
					<label for="textfield" class="control-label"><?=ads?></label>
					<?
						//if($auth->UserType != "Administrator") { 
							$filter=" AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
						//}
						$query = "SELECT * FROM ads WHERE is_valid='True' ".$filter." ORDER BY title";
						echo Select::GetDbRender("ads_id", $query, "ads_id", "title", (isset($dr_e["ads_id"]) ? $dr_e["ads_id"] : ""), true);
					 ?>
				</div>
				<div class="control-group">
					<label for="sex" class="control-label">Φύλο</label>
					<div class="controls">
						<select name="sex" id="sex" class="input-large">
							<option value='0' <?=($dr_e['sex']==0 ||($dr_e['sex']!=1 && $dr_e['sex']!=2)?'selected':'')?>>Όλοι</option>
							<option value='1' <?=($dr_e['sex']==1?'selected':'')?>>Άνδρες</option>
							<option value='2' <?=($dr_e['sex']==2?'selected':'')?>>Γυναίκες</option>
						</select>
					</div>
				</div>
				<hr/>
				
				<div class="control-group">
					<label for="name" class="control-label">Ηλικία</label>
					<div class="controls">
						<!--<div class="check-demo-col">-->
						<?	
							$queryAges = "SELECT * FROM ages";
							$resultAges = $db->sql_query($queryAges);
							$counter = 0;
							while ($drAges = $db->sql_fetchrow($resultAges))
							{
								?>
								<div class="check-line">
									<label class="inline" for="<?=$drAges['age_id']?>"><?=$drAges["age_name"]?></label>
									<div class="controls"><input id="age<?=$drAges['age_id']?>" name="age<?=$drAges['age_id']?>" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=(in_array($drAges['age_id'],$agesArray)?'checked':'')?> <?//=((isset($dr_e["age_id"]) && $dr_e["age_id"]=='True') ? 'checked':'')?>  /></div>
								</div>
								<?
							}
							$db->sql_freeresult($resultRegions);
						?>
						<!--</div>-->
					</div>
				</div>
				
				<hr/>
				<div class="row-fluid">
					<div class="control-group">	
						<label for="name" class="control-label">Περιοχή</label>
						<div class="controls">
							<div class="check-demo-col">
							<?	
								$queryRegions = "SELECT * FROM regions";
								$resultRegions = $db->sql_query($queryRegions);
								$counter = 0;
								while ($drRegions = $db->sql_fetchrow($resultRegions))
								{
									?>
									<div class="check-line">
										<label class="inline" for="region<?=$drRegions['region_id']?>"><?=$drRegions["region_name"]?></label>
										<div class="controls"><input id="region<?=$drRegions['region_id']?>" name="region<?=$drRegions['region_id']?>" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=(in_array($drRegions['region_id'],$regionsArray)?'checked':'')?> <?//=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>  /></div>
									</div>
									<?
									$counter++;
									if($counter==13) echo '</div><div class="check-demo-col">';
									if($counter==26) echo '</div><div class="check-demo-col">';
									if($counter==39) echo '</div><div class="check-demo-col">';
								}
								$db->sql_freeresult($resultRegions);
							?>
							</div>
						</div>
					</div>
				</div>	
				<?	if($auth->UserType == "Administrator") {  ?>
					<hr/>
					<div class="control-group">	
						<label for="name" class="control-label">Rates</label>
						<div class="controls">
							<?	
								if(intval($dr_e['purchasedpackage_id'])>0){
									$query = "SELECT t1.role_id,t1.name, t2.purchasedpackagerate_id, t2.percent,purchasedpackagerate_id FROM roles t1 INNER JOIN purchasedpackagerates t2 ON t1.role_id=t2.role_id WHERE 1=1 AND t2.purchasedpackage_id=".$dr_e['purchasedpackage_id'];
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<div class="row-fluid" style="margin-bottom:15px;"> 
												<label for="<?=$dr["role_id"]?>" class="control-label col-sm-2"><?=$dr["name"]?></label>
												<div class="col-sm-10">
													<input type="text" name="<?=$dr["purchasedpackagerate_id"]?>" id="<?=$dr["purchasedpackagerate_id"]?>" class="form-control" <?=(isset($dr["percent"]) ? 'value="'.$dr['percent'].'"': "")?>>
												</div>
											</div>
										<?
									}
									$db->sql_freeresult($result);
								}
							?>
						</div>
					</div>
				<? } ?>
				
			</div>
		</div>
			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
			<a href="index.php?com=mypackages"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
    </div>

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		var value = $('#friendly_name').val();
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
							<th><?=title?></th>
							<th><?=views?></th>
							<th><?=hits?></th>
							<th><?=order?></th>
							<th><?=insertDate?></th>
							<th><?=action?></th>
						</tr>
					</thead>
					<tbody> 
					<?	
						//$filter="";
						//if($auth->UserType != "Administrator") {  
							//$userID=$auth->UserId;
							//$filter=" AND (user_id=".$userID." OR user_id IN (SELECT parent FROM users WHERE user_id=".$auth->UserId."))";
							$filter=" AND user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent']); //." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
						//}
                        $query = "SELECT * FROM purchasedpackages WHERE 1=1 ".$filter;
						$result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
                                    <td><?=$dr["friendly_name"]?></td>
									<td><?=$dr["views"]?></td>
									<td><?=(intval($dr["hits"])<1?0:$dr["hits"])?></td>
									<td><?=$dr["order_id"]?></td>
									<td><?=$dr["date_insert"]?></td>
                                    <td>
										<?if(intval($dr['hits'])<1){?>
											<a style="padding:4px"  href="index.php?com=mypackages&Command=edit&item=<?=$dr["purchasedpackage_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
										<? } ?>
                                        <!--<a href="#" onclick="ConfirmDelete('<? //=deleteConfirm?>','index.php?com=mypackages&Command=DELETE&item=<? //=$dr["package_id"]?>');"><span><i class="icon-trash"></i><? //=delete?></a></span></a>-->
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
</script>