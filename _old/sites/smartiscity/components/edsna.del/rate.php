<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
?>
<?
	$config["navigation"] = rate;
	$nav = rate;
	$BaseUrl = "index.php?com=rate";								
	
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
                        <thead>
                            <tr>
                                <? echo ($auth->UserType=="Administrator" ? "<th>".customer."</th>" : ""); ?>
                                <th>Συσκευή</th>
                                <th>Μ.Ο.</th>
                                <th class='hidden-480'><?=cleanliness?></th>
                                <th class='hidden-480'><?=location?></th>
                                <th class='hidden-480'><?=staff?></th>
                                <th class='hidden-480'><?=services?></th>
                                <th class='hidden-480'><?=facilities?></th>
                                <th class='hidden-480'><?=prices?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?
                                $filter=($auth->UserType!="Administrator"?" AND devices.user_id=".$auth->UserId:"");
                                $result = $db->sql_query("SELECT count(connections.id) AS countRecords,devices.user_id, devices.friendlyName, connections.device, SUM(rate1) AS sumRate1, SUM(rate2) AS sumRate2, SUM(rate3) AS sumRate3, SUM(rate4) AS sumRate4, SUM(rate5) AS sumRate5, SUM(rate6) AS sumRate6,((SUM(rate1)+SUM(rate2)+SUM(rate3)+SUM(rate4)+SUM(rate5)+SUM(rate6))/6) AS totalRate
								FROM connections 
								INNER JOIN devices ON connections.device=devices.mac 
								INNER JOIN users ON devices.user_id=users.user_id 
								WHERE 1=1 AND (rate1+rate2+rate3+rate4+rate5+rate6)>0 ".$filter." GROUP BY connections.device");
 
                                if($db->sql_numrows($result) > 0)
                                {
                                    while ($dr = $db->sql_fetchrow($result))
                                    {
										if($dr["totalRate"]>0){
                                        ?>
                                         <tr>
                                            <? if($auth->UserType=="Administrator"){
                                            $dr_c=$db->RowSelectorQuery("SELECT * FROM users WHERE user_id=".$dr['user_id']);
                                            echo "<td>".$dr_c['company_name']."</td>";
                                            }
                                            ?>
											<? 
												//$drCount=$db->RowSelectorQuery("SELECT count(id) AS countRecords FROM connections WHERE device='".$dr['device']."' AND (rate1+rate2+rate3+rate4+rate5+rate6)>0");
											?>
                                            <td><?=$dr["friendlyName"].' '.$dr["last_name"].'('.$dr['countRecords'].')'?></td>											
                                            <td><?=number_format($dr["totalRate"]/$dr['countRecords'], 2, ',','.')?></td>
                                            <td class='hidden-480'><?=number_format($dr["sumRate1"]/$dr['countRecords'], 2, ',','.')?></td>
                                            <td class='hidden-480'><?=number_format($dr["sumRate2"]/$dr['countRecords'], 2, ',','.')?></td>
                                            <td class='hidden-480'><?=number_format($dr["sumRate3"]/$dr['countRecords'], 2, ',','.')?></td>
                                            <td class='hidden-480'><?=number_format($dr["sumRate4"]/$dr['countRecords'], 2, ',','.')?></td>
                                            <td class='hidden-480'><?=number_format($dr["sumRate5"]/$dr['countRecords'], 2, ',','.')?></td>
                                            <td class='hidden-480'><?=number_format($dr["sumRate6"]/$dr['countRecords'], 2, ',','.')?></td>
                                        </tr>
                                        <?
										}
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>