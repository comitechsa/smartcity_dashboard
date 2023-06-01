<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") Redirect("index.php");

global $nav;
$nav = "Πακέτα";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=packages";
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

			$PrimaryKeys["password_id"] = intval($_GET["item"]);
			$QuotFields["user_id"] = true;
			
			$Collector["description"] = $_POST["description"];
			$QuotFields["description"] = true;
	
			$db->ExecuteUpdater("passwords",$PrimaryKeys,$Collector,$QuotFields);
			$messages->addMessage("Αποθηκευτηκε!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") { //$command[0] ==
			$queryDel1='DELETE FROM packages';
			$queryDel2='DELETE FROM packageitems';
			$db->sql_query($queryDel1);
			$db->sql_query($queryDel2);
			/*
			if($item != "")
			{
				$error=0;
				//$result = $db->sql_query('SELECT * FROM devices WHERE user_id='.$item);
				//if($db->sql_numrows($result) > 0) $error++;
				//$result = $db->sql_query('SELECT * FROM messages WHERE user_id='.$item);
				//if($db->sql_numrows($result) > 0) $error++;
				if($error==0) {	
					//$filter=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
					$filter="";
					$db->sql_query("DELETE FROM roles WHERE role_id=" . $item.$filter);
					$messages->addMessage("DELETE!!!");
					Redirect($BaseUrl);
				} else {
					$messages->addMessage("Υπάρχουν συνδεδεμένες εγγραφές. Η διαγραφή δεν μπορεί να ολοκληρωθεί");
					Redirect($BaseUrl);
				}
			}
			*/

		}
	}
//}

if(isset($_GET["item"])) {
	//$filter=" WHERE 1=1 AND user_auth!='Administrator '";
	//$filter.=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
	$query="SELECT * FROM packageitems WHERE package_id=".$_GET['item'].$filter." LIMIT 1";

	$dr_e = $db->RowSelectorQuery($query);
	if(intval($_GET['item'])==0 || (intval($_GET["item"])> 0 && intval($dr_e['packageitem_id'])==0)){
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=packages");		
	}
?>
		<div class="card-body">
			<table class="table table-bordered table-striped mb-0" id="datatable-default">
				<thead>
					<tr>
						<th>latitude</th>
						<th>Longitude</th>
						<th>Ημερομηνία μετακίνησης</th>
					</tr>
				</thead>
				<tbody>
					<?	
						//$filter=" AND user_id=".$auth->UserRow['user_id'];
						$filter="";
						$query = "SELECT * FROM packageitems WHERE 1=1 AND package_id='".$_GET['item']."' ORDER BY movedate DESC ";
						$result = $db->sql_query($query);
						$counter = 0;
						while ($dr = $db->sql_fetchrow($result))
						{
							?>
								<tr>
									<td><?=$dr["lat"]?></td>
									<td><?=$dr["lng"]?></td>
									<td><?=$dr["movedate"]?></td>
								</tr>
							<?
						}
						$db->sql_freeresult($result);
					?>
				</tbody>
			</table>
			<div class="row-fluid" style="margin-top:20px;">
				<a href="index.php?com=packages"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
			</div>						
		</div>
	<?
} else 	{
	?>    
    
    <?
    
	//echo 'usertype: '.$auth->UserRow['admin_type']
	
	?>
	
		<div class="row">
			<div class="col">
				<section class="card">
					<header class="card-header">
						<div class="card-actions">
							<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
							<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
						</div>
		
						<h2 class="card-title"><?=$nav?></h2>
					</header>
					<div class="card-body">
						<table class="table table-bordered table-striped mb-0" id="datatable-default">
							<thead>
								<tr>
									<th>Ονομασία</th>
									<!-- <th>Σχετικό Id</th>-->
									<th>Ημερομηνία εισαγωγής</th>
									<th>Λεπτομέρειες</th>
								</tr>
							</thead>
							<tbody>
								<?	
									//$filter=" AND user_id=".$auth->UserRow['user_id'];
									$filter="";
									//$filter = ($auth->UserType != "Administrator"? " AND user_id=".$auth->UserRow['user_id']:"");
									$query = "SELECT * FROM packages WHERE 1=1 ".$filter." ORDER BY date_insert DESC ";
									$result = $db->sql_query($query);
									$counter = 0;
									while ($dr = $db->sql_fetchrow($result))
									{
										?>
											<tr>
												<td><?=$dr["package_name"]?></td>
												<!-- <td><? //=$dr["relative_id"]?></td>-->
												<td><?=$dr["date_insert"]?></td>
												<td><a href="index.php?com=packages&item=<?=$dr["package_id"]?>">Λεπτομέρειες</a> / <a href="index.php?com=heatmap"> Χάρτης</a></td>
											</tr>
										<?
									}
									$db->sql_freeresult($result);
								?>
							</tbody>
						</table>
						<!-- 
						<div class="row-fluid" style="margin-top:20px;">
							<a href="index.php?com=passwords&item="><button type="button" class="btn btn-primary">Νέα εγγραφή</button></a>
						</div>						
						-->

					</div>
				</section>
			</div>
		</div>


			
<? } ?> 