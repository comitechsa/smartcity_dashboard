<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
$_SESSION['uid']=$auth->UserRow['user_id'];
//require_once(dirname(__FILE__) . "/common.php");
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
global $nav;
$nav = views;
$config["navigation"] = views;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=adscompains";
$command=array();
$command=explode("&",$_POST["Command"]);

	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "SAVE")
		{
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();
			
			if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0){
				$PrimaryKeys["compain_id"] = intval($_GET["item"]);
				$QuotFields["compain_id"] = true;
			} 
			
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
			
			//$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
			//$QuotFields["description"] = true;
		
			$Collector["ads_id"] = $_POST["ads_id"];
			$QuotFields["ads_id"] = true;
			
			$Collector["package_id"] = $_POST["package_id"];
			$QuotFields["package_id"] = true;
			
			$Collector["user_id"] = $auth->UserRow['user_id'];
			$QuotFields["user_id"] = true;
					
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
			
			$db->ExecuteUpdater("ads_compains",$PrimaryKeys,$Collector,$QuotFields);
			$db->sql_freeresult($result);
			
			//$purchasedpackages_id=(intval($_GET['item'])>0?$_GET['item']:$db->sql_nextid());
			//$query="SELECT * FROM rolesrates WHERE parent=".$auth->UserRow['role_id'];
			//$result = $db->sql_query($query);
			//$db->sql_freeresult($result);
			
			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { 
			if($item != "")
			{
				//Πρέπει να μπει έλεγχος
				$db->sql_query("DELETE FROM ads_compains WHERE compain_id=" . $item);
				$messages->addMessage("DELETED!!!");
				Redirect($BaseUrl);
			}
		}
	}


if(isset($_GET["item"])) {
	//$query="SELECT * FROM ads_credits WHERE id=".$_GET['item'].$filter;
	$filter="";
	//if($auth->UserType != "Administrator") { 
		//$filter=" AND t2.user_id=". $auth->UserId;
		$filter=" AND (t2.user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR t2.user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
	//}
	$query = "SELECT * FROM ads_compains WHERE 1=1 AND compain_id=".$_GET['item'].$filter;
	$dr_e = $db->RowSelectorQuery($query);
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
				
				<div class="controls">
					<label for="textfield" class="control-label"><?=ads?></label>
					<?
						//$query = "SELECT * FROM ads_packages WHERE is_valid='True' ".$filter." ORDER BY views";
						//echo Select::GetDbRender("package_id", $query, "package_id", "name", (isset($dr_e["package_id"]) ? $dr_e["package_id"] : ""), true);
						$filter=" AND (t2.user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR t2.user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
						$query = "SELECT * FROM purchasedpackages t1 INNER JOIN ads_orders t2 ON t1.order_id=t2.order_id WHERE t1.active='1' ".$filter." ORDER BY t1.date_insert";
						echo Select::GetDbRender("purchasedpackage_id", $query, "purchasedpackage_id", "friendly_name", (isset($dr_e["purchasedpackage_id"]) ? $dr_e["purchasedpackage_id"] : ""), true);
					 ?>
				</div>
				<hr/>
				<div class="control-group">	
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
				<div class="control-group">
					<label for="sex" class="control-label">Φύλο</label>
					<div class="controls">
						<select name="sex" id="sex" class="input-large">
							<option value='0' selected>Όλοι</option>
							<option value='1'>Άνδρες</option>
							<option value='2'>Γυναίκες</option>
						</select>
					</div>
				</div>
				<hr/>
				<div class="control-group">	
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
									<div class="controls"><input id="region<?=$drRegions['region_id']?>" name="region<?=$drRegions['region_id']?>" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>  /></div>
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
		</div>
		<br><br><br><br>
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

			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
			<a href="index.php?com=purchasedpackages"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
		//var value = $('#views').val();
		//if ( value.length >= 2){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
		//} //else {
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
				<div class="box-title"><h3><i class="icon-table"></i><?=$nav?></h3></div>
			</div>
			<div class="box-content nopadding">
				<table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
					<thead>
						<tr>
							<th>compain_id</th>
							<th>Διαφήμιση</th>
							<th>Πακέτο</th>
							<th>Κατάσταση</th>
							<th>Ημ/νια καταχώρησης</th>
							<th><?=action?></th>
						</tr>
					</thead>
					
					<tbody> 
					<?	
						$filter="";
						//if($auth->UserType != "Administrator") { 
							//$filter=" AND t2.user_id=". $auth->UserId;
							//$filter=" AND (t2.user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR t2.user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))";
							$filter=" AND (t2.user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent']).")";
						//}
                        //$query = "SELECT t1.*,t2.title,t3.views FROM purchasedpackages t1 INNER JOIN ads t2 ON t1.ads_id=t2.ads_id 
						//INNER JOIN ads_packages t3 ON t1.package_id=t3.package_id
						//WHERE 1=1 ".$filter." ORDER BY t1.date_insert ";
						$query = "SELECT t1.*,t2.friendly_name, t3.title FROM ads_compains t1 INNER JOIN purchasedpackages t2 ON t1.package_id=t2.package_id INNER JOIN ads t3 ON t1.ads_id=t3.ads_id WHERE 1=1".$filter;
						//echo $query;
						
						$result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
									<td><?=$dr["compain_id"]?></td>
                                    <td><?=$dr["title"]?></td>
									<td><?=$dr["friendly_name"]?></td>
									<td><?
									//$isPaid=($dr["paid"]==1?'Πληρώθηκε':'Δεν πληρώθηκε');
									$isActive=($dr["active"]==1?'Ενεργό':'Μη ενεργό');
									//$isFinished=($dr["finished"]==1?'Ολοκληρώθηκε':'Δεν Ολοκληρώθηκε');
									//echo $isPaid.'/'.$isActive.'/'.$isFinished;
									echo $isActive;
									?></td>
									<td><?=$dr["date_insert"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=adscompains&Command=edit&item=<?=$dr["compain_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=adscompains&Command=DELETE&item=<?=$dr["compain_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
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