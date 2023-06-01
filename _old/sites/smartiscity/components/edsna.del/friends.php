<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
		include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
?>
<?
	$config["navigation"] = friends;
	$nav = friends;
	$BaseUrl = "index.php?com=friends";
	$_SESSION['userID']=$auth->UserId;
	//echo '213'.$_SESSION['userID'];
	//$command=array();
	//$command=explode("&",$_POST["Command"]);										
	$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
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
        <div class="span12" style="margin-top:10px; text-align:right;">
			<a href="sites/hotbox/components/hotbox/phpexcel/friends2xls.php"><button type="button" class="btn btn-primary">Export</button></a>
		</div>
	  </div>

      <div class="row-fluid">
        <div class="span12">
            <div class="box box-color box-bordered">
                <div class="box-title">
                    <h3>
                        <i class="icon-table"></i>
                        <?=$config["navigation"]?>
                    </h3>
                </div>
                <div class="box-content nopadding">
                    <table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
					<!--<table class="table table-hover table-nomargin dataTable table-bordered dataTable-scroller dataTable-tools">-->
                        <thead>
                            <tr>
                                <? //echo ($auth->UserType=="Administrator" ? "<th>".customer."</th>" : ""); ?>
                                <th><?=name?></th>
                                <th>email</th>
                                <th class='hidden-480'><?=friendlyName?></th>
                                <th class='hidden-480'><?=devicesConnection?></th>
                                <th class='hidden-480'><?=homeTown?></th>
								<th class='hidden-480'><?=connections?></th>
								<th class='hidden-480'><?=rate?></th>
                                <th class='hidden-480'><?=photo?></th>
                                <th><?=gender?></th>
                            </tr>
                        </thead>
                        <tbody>
							<tr>
                                <td>Name</td>
                                <td>email</td>
                                <td class='hidden-480'>FriendlyName</td>
                                <td class='hidden-480'>DeviceConnection</td>
                                <td class='hidden-480'>Hometown</td>
								<td class='hidden-480'>Connections</td>
								<td class='hidden-480'>Rate</td>
                                <td class='hidden-480'>Photo</td>
                                <td><?=gender?></td>
							</tr>
                            <?
								$filter=($auth->UserType!="Administrator"?" AND t2.id IN (SELECT id FROM devices WHERE user_id=".$auth->UserId.")":"");
								//$filter=($auth->UserType!="Administrator"?" AND t1.device_id IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.")":"");
								/*
								$query="SELECT t1.id, t1.date_insert, t1.device_id,t1.email, t1.first_name, t1.last_name, t1.gender, t1.birthday, t1.hometown_name, t1.link, t1.friend_id, t2.friendlyName, t3.rate1,t3.rate2,t3.rate3,t3.rate4,t3.rate5,t3.rate6,sum(t3.total) AS total
								FROM friends t1
								INNER JOIN friends2mac t4 ON t1.id=t4.id
								INNER JOIN devices t2 ON t1.device_id=t2.mac 
								LEFT JOIN connections t3 ON t3.mac=t4.mac 
								WHERE 1=1 ".$filter." GROUP BY t2.id,t1.friend_id";
								*/
								
								$query="SELECT t1.id, t1.date_insert, t1.device_id,t1.email, t1.first_name, t1.last_name, t1.gender, t1.birthday, 
								t1.hometown_name, t1.link, t1.friend_id, t2.id, t2.friendlyName, t3.rate1,t3.rate2,t3.rate3,t3.rate4,t3.rate5,t3.rate6,sum(t3.total) AS total
								FROM connections t3
								INNER JOIN devices t2 ON t3.device=t2.mac 
								INNER JOIN friends2mac t4 ON t3.mac=t4.mac 
								INNER JOIN friends t1 ON t1.id=t4.id
								WHERE 1=1  AND (t1.email<>'' OR t1.friend_id<>'') ".$filter."  group by t3.mac, t3.device";
							
								//AND t3.device IN(SELECT mac FROM devices WHERE user_id=".$auth->UserId.")
								
                                //$filter=($auth->UserType!="Administrator"?" AND t1.device_id IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.")":"");
								//$query="SELECT t1.id, t1.device_id,t1.email, t1.first_name, t1.last_name, t1.gender, t1.birthday, t1.hometown_name, t1.link, t1.friend_id, t2.friendlyName, t3.rate1,t3.rate2,t3.rate3,t3.rate4,t3.rate5,t3.rate6,sum(t3.total) AS total
								//FROM friends t1
								//LEFT JOIN friends2mac t4 ON t1.id=t4.id
								//INNER JOIN devices t2 ON t1.device_id=t2.mac 
								//LEFT JOIN connections t3 ON t1.mac=t3.mac AND t1.device_id=t3.device ".$filter.")
								//WHERE 1=1 ".$filter." GROUP BY t1.friend_id";
								//echo $query;
								$result = $db->sql_query($query);
                                if($db->sql_numrows($result) > 0)
                                {
                                    while ($dr = $db->sql_fetchrow($result))
									{
                                        ?>
                                         <tr>
                                            <? //if($auth->UserType=="Administrator"){
                                            //$dr_c=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$dr['user_id']);
                                            //echo "<td>".$dr_c['company_name']."</td>";
                                            //}
                                            ?>
											<?
											if($dr['friend_id']!=''){
												$photoLink="<a href='".$dr['link']."' target'_blank'><img src='https://graph.facebook.com/".$dr['friend_id']."/picture?type=square' /></a>";
											} else {
												$photoLink="<img src='/gallery/site/questionmark.jpg'>";
											}
											
											?>
                                            <td><?=$dr["first_name"].' '.$dr["last_name"]?></td>
                                            <td><?=$dr["email"]?></td>
                                            <td class='hidden-480'><?=$dr["friendlyName"]?></td>
                                            <td class='hidden-480'><?=$dr["date_insert"]?></td>
											<!--<td class='hidden-480'><? //=$dr["birthday"]?></td>-->
                                            <td class='hidden-480'><?=$dr["hometown_name"]?></td>
											<td class='hidden-480'><?=$dr["total"]?></td>
											<td class='hidden-480'><?=((($dr["rate1"]+$dr["rate2"]+$dr["rate3"]+$dr["rate4"]+$dr["rate5"]+$dr["rate6"])/6>0)?(($dr["rate1"]+$dr["rate2"]+$dr["rate3"]+$dr["rate4"]+$dr["rate5"]+$dr["rate6"])/6).'<img src="/images/rate.png" style="width:15px;" rel="tooltip" data-placement="bottom" title="'.cleanliness.':'.$dr["rate1"]."\r\n".location.':'.$dr["rate2"]."\r\n".staff.':'.$dr["rate3"]."\r\n".services.':'.$dr["rate4"]."\r\n".facilities.':'.$dr["rate5"]."\r\n".prices.':'.$dr["rate6"].'">':'')?></td>
											<td class='hidden-480'><?=$photoLink?></td>
											<!--<a href='<?//=$dr["link"]?>' target="_blank"><img src="https://graph.facebook.com/<?//=$dr["friend_id"]?>/picture?type=square" /></a>-->
                                            <td class='hidden-480'><?=$dr["gender"]?></td>
                                        </tr>
                                        <?
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>