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

//if(isset($_GET["item"])) { // get του mac address?>
	<div class="row-fluid">
		<div class="span12">
			<div class="box box-color box-bordered">
				<div class="box-title"><h3><i class="icon-table"></i><?=$nav?></h3>
			</div>
			<div class="box-content nopadding">
				<table class="table table-hover table-nomargin  table-bordered"> <!-- dataTable dataTable-columnfilter -->
					<thead>
						<tr>
							<? echo ($auth->UserType=="Administrator" ? "<th>".customer."</th>" : ""); ?>
							<th><?=friendlyName?></th>
							<th>Stars</th>
							<th>Comment</th>
						</tr>
					</thead>
					<tbody> 
					<?	
						$filter=($auth->UserType!="Administrator"?" AND user_id=".$auth->UserId:"");
						$query = "SELECT t1.*, t2.stars, t2.comment FROM rate t1 LEFT JOIN ratings t2 ON t1.rate_id=t2.rate_id WHERE 1=1 ".$filter." AND is_valid='True'  AND ( parent_id is null OR parent_id = '' OR parent_id = 0) ORDER BY priority";
						$result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
									<? if($auth->UserType=="Administrator"){
											echo "<td><b>Customer</b></td>";
										}
                                    ?>
                                    <td><b><?=$dr["rate_name"]?></b></td>
									<td><b><?=$dr["stars"]?></b></td>
									<td><b><?=$dr["comment"]?></b></td>
                                </tr>
                            <?
							rateDepth($dr["rate_id"]);
                        }
                        $db->sql_freeresult($result);
                    ?>
					</tbody>
				</table>
				<br/>
			</div>                
		</div> 
	</div>
<?
// } 
	function rateDepth($id,$depth=1)
	{
		global $config , $db, $auth;
		$queryDepth = "SELECT t1.*, t2.stars, t2.comment FROM rate t1 LEFT JOIN ratings t2 ON t1.rate_id=t2.rate_id WHERE is_valid='True' AND parent_id= ".$id." ORDER BY priority";

		$resultDepth = $db->sql_query($queryDepth);
		if($db->sql_numrows($resultDepth) > 0)
		{
			while ($drDepth = $db->sql_fetchrow($resultDepth))
			{
				$Response = "<tr>";
				if($auth->UserType=="Administrator"){
					$Response .= "<td>Customer</td>";
				 }
				$Response .= '<td>'.str_repeat("-", $depth).$drDepth["rate_name"].'</td>';
				$Response .= '<td>'.$drDepth["stars"].'</td>';
				$Response .= '<td>'.$drDepth["comment"].'</td>';
				$Response .= "</tr>";
				echo $Response;
				$depth++;
				rateDepth($drDepth["rate_id"],$depth);
			}
			//return $Response;
		}
		$db->sql_freeresult($resultDepth);
	}
?>