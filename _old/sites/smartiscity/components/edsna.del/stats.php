<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

	
//permissions
	$FLAG_DEVICES = 1;
	$FLAG_CAMPAINS = 2;
	$FLAG_PASSWORDS = 4;
	$FLAG_PRODUCTS = 8;
	$FLAG_CATEGORIES = 16;
	$FLAG_ADS = 32;
	$FLAG_CREDITS = 64;
	$FLAG_RESELLERS = 128;
	$FLAG_SURVEYS = 256;
	$FLAG_FRIENDS = 512;
	$FLAG_RATING = 1024;
	$FLAG_STATS = 2048;
	$permissions = (intval($auth->UserRow['access'])>0?$auth->UserRow['access']:0);

	if (!($permissions & $FLAG_STATS)) {
		$messages->addMessage("INVALID!!!");
		Redirect("index.php");
	}
//end permissions

$config["navigation"] = stats;

//$categ_id = "-1";
//$categ_name = "";
/*if(isset($_GET["c"]) && intval($_GET["c"]) > 0)
{
	$dr_tmp = $db->RowSelectorQuery("SELECT name FROM msgs_categs WHERE categ_id=" . intval($_GET["c"] ));
	if(isset($dr_tmp["name"]))
	{
		$categ_id = intval($_GET["c"]);
		$categ_name = $dr_tmp["name"];
	}
}
*/
//$categ_id = "1";
//$categ_name = "";

//if($categ_id == "-1")
//{
//	Redirect("index.php");
//}											

$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");

?>
<!-- graph style-->
<style>
.flot-container {
	box-sizing: border-box;
	width: 850px;
	height: 450px;
	padding: 20px 15px 15px 15px;
	margin: 15px auto 30px auto;
	border: 1px solid #ddd;
	background: #fff;
	background: linear-gradient(#f6f6f6 0, #fff 50px);
	background: -o-linear-gradient(#f6f6f6 0, #fff 50px);
	background: -ms-linear-gradient(#f6f6f6 0, #fff 50px);
	background: -moz-linear-gradient(#f6f6f6 0, #fff 50px);
	background: -webkit-linear-gradient(#f6f6f6 0, #fff 50px);
	box-shadow: 0 3px 10px rgba(0,0,0,0.15);
	-o-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	-ms-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	-moz-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
	-webkit-box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.flot-placeholder {
	width: 100%;
	height: 100%;
	font-size: 14px;
	line-height: 1.2em;
}

</style>
<!-- end graph style-->
		<?
        if(isset($_GET["item"]) && $_GET["item"]==0) //today
        {
			if($auth->UserType != "Administrator") {
				$query="SELECT hour(date_insert) as Hour, count(*) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP by hour(date_insert)";
			} else {
				$query="SELECT hour(date_insert) as Hour, count(*) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()) AND device IN (SELECT mac FROM devices ".(isset($_GET['uid'])?' WHERE user_id='.$_GET['uid']:'').") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP by hour(date_insert)";	//user_id=".$auth->UserId.") 
			}
			$result = $db->sql_query($query);
			$statTotal=0;
			$chartCounter=0;
			if($db->sql_numrows($result) > 0) {
				while ($dr = $db->sql_fetchrow($result))
				{
					$chartCounter++;
					$num=$dr["Hour"];
					$stat[$num]=$dr["Count"];
					$statTotal=$statTotal+$dr["Count"];
					//$g2=$g2."[".$num.",".$stat[$num]."],";
					$tickName=$tickName."[".$num.",'".$num ."'],";
				}
				//for ($x = 0; $x <= 23; $x++)  {
					//$g2=$g2."[".$x.",".($stat[$x]>0?$stat[$x]:0)."],";
					//$g1=$g1."[".$x.",".($stat[$x]>0?$stat[$x]:0)."],";
					//$tickName=$tickName."[".$x.",'".$x ."'],";
				//}
			}
			if($auth->UserType != "Administrator") {
				$query="SELECT hour(date_insert) as Hour, count(distinct mac) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP by hour(date_insert)";
			} else {
				$query="SELECT hour(date_insert) as Hour, count(distinct mac) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()) AND device IN (SELECT mac FROM devices WHERE 1=1 ".(isset($_GET['uid'])?' AND user_id='.$_GET['uid']:'').") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP by hour(date_insert)";
			}
			$result = $db->sql_query($query);
			$statTotalDistinct=0;
			$max=0;
			if($db->sql_numrows($result) > 0) {
				while ($dr = $db->sql_fetchrow($result))
				{
					$num=$dr["Hour"];
					$statDistinct[$num]=$dr["Count"];
					$statTotalDistinct=$statTotalDistinct+$dr["Count"];
				}
				for ($x = 0; $x <= 23; $x++)  {
					$g1=$g1."[".$x.",".($stat[$x]>0?$stat[$x]:0)."],";
					$g2=$g2."[".$x.",".($statDistinct[$x]>0?$statDistinct[$x]:0)."],";
					$max=($stat[$x]>$max?$stat[$x]:$max);					
					$tickName=$tickName."[".$x.",'".$x ."'],";
				}
			}

			$max1=floor($max/4);
			$max2=floor($max/2);
				
			if($auth->UserType != "Administrator") {
				$query="SELECT count(distinct mac) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"");
			} else {
				$query="SELECT count(distinct mac) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()) AND device IN (SELECT mac FROM devices WHERE 1=1".(isset($_GET['uid'])?' AND user_id='.$_GET['uid']:'').") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"");			
			}
			$result = $db->RowSelectorQuery($query);
			$dailyDisting=$result["Count"];



			//Combo boxes
			if($auth->UserType == "Administrator") { // Select network combo
				$resultNetworks=$db->sql_query('SELECT * FROM users WHERE user_auth!="Administrator"');
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";				
				echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=0'>".allNetworks."</option>";
				while ($drn = $db->sql_fetchrow($resultNetworks)){
					echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=0&uid=".$drn["user_id"]."'".((isset($_GET['uid'])&&$_GET['uid']==$drn['user_id'])?" selected":"").">".$drn['company_name']."</option>";
				}
				echo "</select>";
			}
			
			if($auth->UserType != "Administrator") { //Select device combo
				$query="SELECT DISTINCT mac AS mac, friendlyName FROM devices WHERE user_id=".$auth->UserId."";
			} else {
				$filter=(isset($_GET['uid'])?' AND users.user_id='.$_GET['uid']:'');
				$query="SELECT DISTINCT mac AS mac, friendlyName, users.company_name AS companyName FROM devices INNER JOIN users ON devices.user_id=users.user_id WHERE 1=1 ".$filter;
			}
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";
				echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=0'>".allDevices."</option>";
					while ($dr = $db->sql_fetchrow($result))
					{
						echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=0&mac=".$dr["mac"]."'".((isset($_GET['mac'])&&$_GET['mac']==$dr['mac'])?" selected":"").">".$dr['friendlyName']."</option>";
					}
				echo "</select>";
			}
        ?>
        <!-- Graph- ->
            <div class="flot-container">
                <div id="placeholder" class="flot-placeholder"></div>
            </div>
            
            <script type="text/javascript">
            $(function() {
                var d1 = [<? //=$g1?>];
                var d2 = [<? //=$g2?>];
                $.plot("#placeholder", [ d1 ,  d2 ]);
            });
            </script>
        <!- - end Graph-->

        <!-- Graph-->
            <div class="row-fluid">
                <div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-bar-chart"></i>
								<?=graphToday?>
							</h3>
							<div class="actions">
								<a href="#" class="btn btn-mini content-refresh"><i class="icon-refresh"></i></a>
								<a href="#" class="btn btn-mini content-remove"><i class="icon-remove"></i></a>
								<a href="#" class="btn btn-mini content-slideUp"><i class="icon-angle-down"></i></a>
							</div>
						</div>
						<div class="box-content">
							<div class="statistic-big">
								<div class="top">
									<div class="right">
										<?=$statTotal." / ".$statTotalDistinct?> <span><i class="icon-circle-arrow-up"></i></span>
									</div>
								</div>
								<div class="bottom">
									<div style="padding: 0px; position: relative;" class="flot medium" id="placeholder">
									</div>
								</div>
							</div>
						</div>
					</div>                
                </div>
            </div>
            
		<script type="text/javascript">
        $(function() {
            var d1 = [<?=$g1?>];
            $.plot("#placeholder", [{
                data: d1,
                bars: { show: true } //lines, bars, points,lines: { show: true, fill: true }
            }]);
        });
        </script>
        <!-- flot chart -->
		<script>
        $(document).ready(function() {  
        var d1_1 = [
            [1, 10],
            [2, 20],
            [3, 30],
            [4, 40],
            [5, 35],
        ];
		var d1_1=[<?=$g1?>];
        var data1 = [
            {
                label: " ",
                data: d1_1,
                bars: {
                    show: true,
                    barWidth: 0.3,
                    fill: true,
                    lineWidth:0,
                    order: 1,
					align: "center",
                    fillColor:  {
                     colors: ["#F39494", "#f14d4d"]  //colors: ["#80C3FD", "#0089FF"] 
                }
                },
                color: "rgba(243, 89, 88, 0.7)"
            },
        ];
        
        $.plot($("#placeholder"), data1, {
            xaxis: {
                tickLength: 0, // hide gridlines
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                //axisLabelPadding: 5,
                ticks:[<?=$tickName?>]
            },
            yaxis: {
				max: <?=$max?>, ticks: [<?=$max1?>, <?=$max2?>, <?=($max1+$max2)?>, <?=$max?>],
                axisLabel: '#',
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                axisLabelPadding: 5,
                tickSize: 10,
                tickFormatter: function (val, axis) {
                        return val; // + "#";
				},
            },
            grid: {
                hoverable: true,
                clickable: false,
                borderWidth: 0,
                borderColor:'#f0f0f0',
                labelMargin:8,
            },
            series: {
                shadowSize: 1,
            },
        
            legend: {
                show: false,
            },
        });
        
        });
        
        </script>  
        <!-- end Graph-->
        
          <div class="row-fluid">
            <div class="span12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="icon-table"></i>
                            <?=$config["navigation"]." (".today.")"?>
                        </h3>
                    </div>
                    <div class="box-content nopadding">
                        <table class="table table-hover table-nomargin  table-bordered">
                            <thead>
                            <? 	echo "<tr><td>".graphTotal."</td><td>".$statTotal."</td><td>".$dailyDisting."</td></tr>";?>
                                <tr>
                                    <th><?=graphTime?></th>
                                    <th><?=connections?></th>
                                    <th><?=unique?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                            for ($i = 0; $i <= 23; $i++) {
								if ($stat[$i]>0) {
									echo"<tr>";
									echo "<td>".$i.":00-".($i+1).":00</td>";
									echo "<td>".(isset($stat[$i])?$stat[$i]:'')."</td>";
									echo "<td>".(isset($statDistinct[$i])?$statDistinct[$i]:'')."</td>";
									echo "</tr>";
								}
							}
							?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?
        }  else if(isset($_GET["item"]) && $_GET["item"]==1) //yesterday
        {
			if($auth->UserType != "Administrator") {
				$query="SELECT hour(date_insert) as Hour, count(*) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()-1) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP by hour(date_insert)";
			} else {
				$query="SELECT hour(date_insert) as Hour, count(*) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()-1) AND device IN (SELECT mac FROM devices ".(isset($_GET['uid'])?' WHERE user_id='.$_GET['uid']:'').") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP by hour(date_insert)";	//user_id=".$auth->UserId.") 
			}
			$result = $db->sql_query($query);
			$statTotal=0;
			if($db->sql_numrows($result) > 0) {
				while ($dr = $db->sql_fetchrow($result))
				{
					$chartCounter++;
					$num=$dr["Hour"];
					$stat[$num]=$dr["Count"];
					$statTotal=$statTotal+$dr["Count"];
					//$g2=$g2."[".$num.",".$stat[$num]."],";
					$tickName=$tickName."[".$num.",'".$num ."'],";
				}
				for ($x = 0; $x <= 23; $x++)  {
					$g2=$g2."[".$x.",".($stat[$x]>0?$stat[$x]:0)."],";
					$tickName=$tickName."[".$x.",'".$x ."'],";
					$max=($stat[$x]>$max?$stat[$x]:$max);
				}
			}
			$max1=floor($max/4);
			$max2=floor($max/2);
			
			if($auth->UserType != "Administrator") {
				$query="SELECT hour(date_insert) as Hour, count(distinct mac) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()- INTERVAL 1 DAY) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP by hour(date_insert)";
			} else {
				$query="SELECT hour(date_insert) as Hour, count(distinct mac) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()- INTERVAL 1 DAY) AND device IN (SELECT mac FROM devices ".(isset($_GET['uid'])?' WHERE user_id='.$_GET['uid']:'').") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP by hour(date_insert)";
			}
				//$query="SELECT hour(date_insert) as Hour, count(distinct mac) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE() - INTERVAL 1 DAY) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP by hour(date_insert)";
			$result = $db->sql_query($query);
			$statTotalDistinct=0;
			if($db->sql_numrows($result) > 0) {
				while ($dr = $db->sql_fetchrow($result))
				{
					$num=$dr["Hour"];
					$statDistinct[$num]=$dr["Count"];
					$statTotalDistinct=$statTotalDistinct+$dr["Count"];
					//$g2=$g2."[".$num.",".$stat[$num]."],";
					$tickName=$tickName."[".$num.",'".$num ."'],";
				}
				for ($x = 0; $x <= 23; $x++)  {
					$g1=$g1."[".$x.",".($stat[$x]>0?$stat[$x]:0)."],";
					//$g2=$g2."[".$x.",".($statDistinct[$x]>0?$statDistinct[$x]:0)."],";
				}
			}
			
			if($auth->UserType != "Administrator") {
				$query="SELECT count(distinct mac) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()-1) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"");
			} else {
				$query="SELECT count(distinct mac) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()-1) AND device IN (SELECT mac FROM devices WHERE 1=1".(isset($_GET['uid'])?' AND user_id='.$_GET['uid']:'').") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"");			
			}			
			//	$query="SELECT count(distinct mac) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()-1) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"");
			$result = $db->RowSelectorQuery($query);
			$dailyDisting=$result["Count"];
			
			
			//Combo boxes
			if($auth->UserType == "Administrator") { // Select network combo
				$resultNetworks=$db->sql_query('SELECT * FROM users WHERE user_auth!="Administrator"');
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";				
				echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=1'>".allNetworks."</option>";
				while ($drn = $db->sql_fetchrow($resultNetworks)){
						echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=1&uid=".$drn["user_id"]."'".((isset($_GET['uid'])&&$_GET['uid']==$drn['user_id'])?" selected":"").">".$drn['company_name']."</option>";
				}
				echo "</select>";
			}
			
			if($auth->UserType != "Administrator") { //Select device combo
				$query="SELECT DISTINCT mac AS mac, friendlyName FROM devices WHERE user_id=".$auth->UserId."";
			} else {
				$filter=(isset($_GET['uid'])?' AND users.user_id='.$_GET['uid']:'');
				$query="SELECT DISTINCT mac AS mac, friendlyName, users.company_name AS companyName FROM devices INNER JOIN users ON devices.user_id=users.user_id WHERE 1=1 ".$filter;
			}
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";
				echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=1'>".allDevices."</option>";
				while ($dr = $db->sql_fetchrow($result))
				{
					echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=1&mac=".$dr["mac"]."'".((isset($_GET['mac'])&&$_GET['mac']==$dr['mac'])?" selected":"").">".$dr['friendlyName']."</option>";
				}
				echo "</select>";
			}
        ?>
        <!-- Graph-->
            <div class="row-fluid">
                <div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-bar-chart"></i>
								<?=graphYesterday?>
							</h3>
							<div class="actions">
								<a href="#" class="btn btn-mini content-refresh"><i class="icon-refresh"></i></a>
								<a href="#" class="btn btn-mini content-remove"><i class="icon-remove"></i></a>
								<a href="#" class="btn btn-mini content-slideUp"><i class="icon-angle-down"></i></a>
							</div>
						</div>
						<div class="box-content">
							<div class="statistic-big">
								<div class="top">
									<div class="right">
										<?=$statTotal." / ".$statTotalDistinct?> <span><i class="icon-circle-arrow-up"></i></span>
									</div>
								</div>
								<div class="bottom">
									<div style="padding: 0px; position: relative;" class="flot medium" id="placeholder">
									</div>
								</div>
							</div>
						</div>
					</div>                
                </div>
            </div>
            
        <!-- flot chart -->
		<script>
        $(document).ready(function() {  
        var d1_1 = [
            [1, 10],
            [2, 20],
            [3, 30],
            [4, 40],
            [5, 35],
        ];
		var d1_1=[<?=$g2?>];
		var d1_1=[<?=$g1?>];
        var data1 = [
            {
                label: " ",
                data: d1_1,
                bars: {
                    show: true,
                    barWidth: 0.3,
                    fill: true,
                    lineWidth:0,
                    order: 1,
					align: "center",
                    fillColor:  {
                     colors: ["#F39494", "#f14d4d"]  //colors: ["#80C3FD", "#0089FF"] 
                }
                },
                color: "rgba(243, 89, 88, 0.7)"
            },
        ];
        
        $.plot($("#placeholder"), data1, {
            xaxis: {
                tickLength: 0, // hide gridlines
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                //axisLabelPadding: 5,
                ticks:[<?=$tickName?>]
            },
            yaxis: {
				max: <?=$max?>, ticks: [<?=$max1?>, <?=$max2?>, <?=($max1+$max2)?>, <?=$max?>],
                axisLabel: '#',
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                axisLabelPadding: 5,
                tickSize: 10,
                tickFormatter: function (val, axis) {
                        return val; // + "#";
				},
            },
            grid: {
                hoverable: true,
                clickable: false,
                borderWidth: 0,
                borderColor:'#f0f0f0',
                labelMargin:8,
            },
            series: {
                shadowSize: 1,
            },
        
            legend: {
                show: false,
            },
        });
        
        });
        
        </script>  
        <!-- end Graph-->
          <div class="row-fluid">
            <div class="span12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="icon-table"></i>
                            <?=$config["navigation"]." (".yesterday.")" ?>
                        </h3>
                    </div>
                    <div class="box-content nopadding">
                        <table class="table table-hover table-nomargin  table-bordered">
                            <thead>
								<? 	echo "<tr><td>".graphTotal."</td><td>".$statTotal."</td><td>".$dailyDisting."</td></tr>";?>
                                <tr>
                                    <th><?=graphTime?></th>
                                    <th><?=connections?></th>
                                    <th><?=unique?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                            for ($i = 0; $i <= 23; $i++) {
								if ($stat[$i]>0) {
									echo"<tr>";
									echo "<td>".$i.":00-".($i+1).":00</td>";
									echo "<td>".(isset($stat[$i])?$stat[$i]:'')."</td>";
									echo "<td>".(isset($statDistinct[$i])?$statDistinct[$i]:'')."</td>";
									echo "</tr>";
								}
							}
							?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?
        }  else if(isset($_GET["item"]) && $_GET["item"]==2) //last week
        {
			//$query="SELECT day(date_insert) as Day, hour(date_insert) as Hour, count(*) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") GROUP hour(date_insert)";
			if($auth->UserType != "Administrator") {
				//$query="SELECT * FROM stats WHERE (date_insert)>(CURDATE()- INTERVAL 7 DAY) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." ORDER BY date_insert DESC";
				$query="SELECT sum(total) AS total, sum(total_unique) AS total_unique, date_insert FROM stats WHERE (date_insert)>(CURDATE()- INTERVAL 9 DAY) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP by date_insert ORDER BY date_insert DESC";
			} else {
				//$query="SELECT * FROM stats WHERE (date_insert)>(CURDATE()- INTERVAL 7 DAY) AND device IN (SELECT mac FROM devices ".(isset($_GET['uid'])?' WHERE user_id='.$_GET['uid']:'').") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." ORDER BY date_insert DESC";	//user_id=".$auth->UserId.") 
				$query="SELECT sum(total) AS total, sum(total_unique) AS total_unique, date_insert FROM stats WHERE (date_insert)>(CURDATE()- INTERVAL 9 DAY) AND device IN (SELECT mac FROM devices ".(isset($_GET['uid'])?' WHERE user_id='.$_GET['uid']:'').") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP by date_insert ORDER BY date_insert DESC";	//user_id=".$auth->UserId.") 
			}
			
			//echo $query;
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				$statTotal=0;
				$statTotalDistinct=0;
				$chartCounter=0;
				$max=0;
				while ($dr = $db->sql_fetchrow($result))
				{
					$chartCounter++;
					$string = $dr["date_insert"];
					$date = DateTime::createFromFormat("Y-m-d", $string);
					$detailsday[$chartCounter]=date("Y-m-d",strtotime($dr["date_insert"]));
					$num=intval($date->format("d"));
					$stat[$chartCounter]=$dr["total"];
					$max=($dr["total"]>$max?$dr["total"]:$max);	
					$statDistinct[$chartCounter]=$dr["total_unique"];
					$g1=$g1."[".$chartCounter.",".$stat[$chartCounter]."],";
					$g2=$g2."[".$chartCounter.",".$statDistinct[$chartCounter]."],";
					$statTotal=$statTotal+$dr["total"];
					$statTotalDistinct=$statTotalDistinct+$dr["total_unique"];
					$tickName=$tickName."[".$chartCounter.",'".date("d-m-y",strtotime($dr["date_insert"])) ."'],";
				}
			}
			$max1=floor($max/4);
			$max2=floor($max/2);
			
			//Combo boxes
			if($auth->UserType == "Administrator") { // Select network combo
				$resultNetworks=$db->sql_query('SELECT * FROM users WHERE user_auth!="Administrator"');
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";				
				echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=2'>".allNetworks."</option>";
				while ($drn = $db->sql_fetchrow($resultNetworks)){
						echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=2&uid=".$drn["user_id"]."'".((isset($_GET['uid'])&&$_GET['uid']==$drn['user_id'])?" selected":"").">".$drn['company_name']."</option>";
				}
				echo "</select>";
			}
			
			if($auth->UserType != "Administrator") { //Select device combo
				$query="SELECT DISTINCT mac AS mac, friendlyName FROM devices WHERE user_id=".$auth->UserId."";
			} else {
				$filter=(isset($_GET['uid'])?' AND users.user_id='.$_GET['uid']:'');
				$query="SELECT DISTINCT mac AS mac, friendlyName, users.company_name AS companyName FROM devices INNER JOIN users ON devices.user_id=users.user_id WHERE 1=1 ".$filter;
			}
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";
				echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=2'>".allDevices."</option>";
					while ($dr = $db->sql_fetchrow($result))
					{
						echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=2&mac=".$dr["mac"]."'".((isset($_GET['mac'])&&$_GET['mac']==$dr['mac'])?" selected":"").">".$dr['friendlyName']."</option>";
					}
				echo "</select>";
			}
        ?>
        <!-- Graph-->
            <div class="row-fluid">
                <div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-bar-chart"></i>
								<?=graphLastWeek?>
							</h3>
							<div class="actions">
								<a href="#" class="btn btn-mini content-refresh"><i class="icon-refresh"></i></a>
								<a href="#" class="btn btn-mini content-remove"><i class="icon-remove"></i></a>
								<a href="#" class="btn btn-mini content-slideUp"><i class="icon-angle-down"></i></a>
							</div>
						</div>
						<div class="box-content">
							<div class="statistic-big">
								<div class="top">
									<div class="right">
										<?=$statTotal." / ".$statTotalDistinct?> <span><i class="icon-circle-arrow-up"></i></span>
									</div>
								</div>
								<div class="bottom">
									<div style="padding: 0px; position: relative;" class="flot medium" id="placeholder">
									</div>
								</div>
							</div>
						</div>
					</div>                
                </div>
            </div>
  
		<script>
        $(document).ready(function() {  
		var d1_1=[<?=$g1?>];
        var d1_2=[<?=$g2?>];
        /* 
        // second column
        var d1_2 = [
            [1325376000000, 80],
            [1328054400000, 60],
            [1330560000000, 20],
            [1333238400000, 90],
            [1335830400000, 30]
        ];
        */
        var data1 = [
            {
                label: " ",
                data: d1_1,
                bars: {
                    show: true,
                    barWidth: 0.3,
                    fill: true,
                    lineWidth:0,
                    order: 1,
					align: "center",
                    fillColor:  {
                     colors: ["#F39494", "#f14d4d"]  //colors: ["#80C3FD", "#0089FF"] 
                }
                },
                color: "rgba(243, 89, 88, 0.7)"
            },

            {
                label: " ",
                data: d1_2,
                bars: {
                    show: true,
                    barWidth: 0.3,
                    fill: true,
                    lineWidth: 0,
                    order: 2,
					align: "center",
                    fillColor:  {
                    colors: ["#80C3FD", "#0089FF"] 
                }
                },
                color: "rgba(251, 176, 94, 0.7)"
            },
        ];
        
        $.plot($("#placeholder"), data1, {
            xaxis: {
                //min: (new Date(2011, 11, 15)).getTime(),
                //max: (new Date(2012, 04, 18)).getTime(),
                //mode: "time",
                //timeformat: "%b",
                // tickSize: [1, "month"],
                //monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                tickLength: 0, // hide gridlines
                //axisLabel: 'Month',
                //axisLabelUseCanvas: true,
                //axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                //axisLabelPadding: 5,
                ticks:[<?=$tickName?>]
            },
            yaxis: {
				max: <?=$max?>, ticks: [<?=$max1?>, <?=$max2?>, <?=($max1+$max2)?>, <?=$max?>],
                axisLabel: '#',
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                axisLabelPadding: 5,
                tickSize: 10,
                tickFormatter: function (val, axis) {
                        return val; // + "#";
                    },
        
            },
            grid: {
                hoverable: true,
                clickable: false,
                borderWidth: 0,
                borderColor:'#f0f0f0',
                labelMargin:8,
            },
            series: {
                shadowSize: 1,
            },
        
            legend: {
                show: false,
            },
            /*
            tooltip:true,
            tooltipOpts: {
                id:"chart-tooltip",
                content: "<p><b>20</b> Outgoing Filings</p>" +
                     "<p>Out of <b>10</b> committed;</p>" +
                     "<br />" +
                     "<p><b>30%</b>% Ratio</p>",
                shifts: {
                    x:-74,
                    y:-125
                },
                lines:{
                track: true
                },
                compat: true,
            },
            */
        
        });
        
        });
        
        </script>           
        <!-- end Graph-->
        
          <div class="row-fluid">
            <div class="span12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="icon-table"></i>
                             <?=$config["navigation"]." (".lastWeek.")"?>
                        </h3>
                    </div>
                    <div class="box-content nopadding">
                        <table class="table table-hover table-nomargin  table-bordered">
                            <thead>
								<? 	echo "<tr><td>Σύνολο</td><td>".$statTotal."</td><td>".$statTotalDistinct."</td></tr>";?>
                                <tr>
                                    <th><?=statDate?></th>
                                    <th><?=connections?></th>
                                    <th><?=unique?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                            for ($i = 0; $i <= 7; $i++) {
								$myDay=intval(date("d",strtotime($Date. ' - '.$i.' days')));
								if ($stat[$i]>0) {
									echo"<tr>";
									$detailsDay=date("Y-m-d",strtotime($Date. ' - '.$i.' days'));
									echo "<td><a href='index.php?com=stats&item=6&detailsDay=".$detailsday[$i]."'>".$detailsday[$i]."</a></td>";
									echo "<td>".(isset($stat[$i]) ? $stat[$i]:'')."</td>";
									echo "<td>".(isset($statDistinct[$i])?$statDistinct[$i]:'')."</td>";
									echo "</tr>";
								}
							}
							?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?
        }  else if(isset($_GET["item"]) && $_GET["item"]==3) //last month
        {
			//$query="SELECT day(date_insert) as Day, hour(date_insert) as Hour, count(*) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") GROUP hour(date_insert)";
			//$query="SELECT * FROM stats WHERE (date_insert)>(CURDATE()- INTERVAL 31 DAY) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." ORDER BY date_insert DESC";
			if($auth->UserType != "Administrator") {
				$query="SELECT sum(total) AS total, sum(total_unique) AS total_unique, date_insert FROM stats WHERE (date_insert)>(CURDATE()- INTERVAL 31 DAY) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP BY date_insert ORDER BY date_insert DESC";
			} else {
				$query="SELECT sum(total) AS total, sum(total_unique) AS total_unique, date_insert FROM stats WHERE (date_insert)>(CURDATE()- INTERVAL 31 DAY) AND device IN (SELECT mac FROM devices ".(isset($_GET['uid'])?' WHERE user_id='.$_GET['uid']:'').") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP BY date_insert ORDER BY date_insert DESC";	//user_id=".$auth->UserId.") 
			}
			$result = $db->sql_query($query);
			$chartCounter=0;
			$max=0;
			if($db->sql_numrows($result) > 0) {
				while ($dr = $db->sql_fetchrow($result))
				{
					$chartCounter++;
					$string = $dr["date_insert"];
					$date = DateTime::createFromFormat("Y-m-d", $string);
					$detailsday[$chartCounter]=date("Y-m-d",strtotime($dr["date_insert"]));
					$num=intval($date->format("d"))+intval($date->format("m"));
					$stat[$chartCounter]=$dr["total"];
					$max=($dr["total"]>$max?$dr["total"]:$max);
					$statDistinct[$chartCounter]=$dr["total_unique"];
					$statTotal=$statTotal+$dr["total"];
					$statTotalDistinct=$statTotalDistinct+$dr["total_unique"];
					$g1=$g1."[".$chartCounter.",".$stat[$chartCounter]."],";
					$g2=$g2."[".$chartCounter.",".$statDistinct[$chartCounter]."],";
					$tickName=$tickName."[".$chartCounter.",'".date("d-m-y",strtotime($dr["date_insert"])) ."'],";
				}
			}
			$max1=floor($max/4);
			$max2=floor($max/2);
			
			//Combo boxes
			if($auth->UserType == "Administrator") { // Select network combo
				$resultNetworks=$db->sql_query('SELECT * FROM users WHERE user_auth!="Administrator"');
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";				
				echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=3'>".allNetworks."</option>";
				while ($drn = $db->sql_fetchrow($resultNetworks)){
					echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=3&uid=".$drn["user_id"]."'".((isset($_GET['uid'])&&$_GET['uid']==$drn['user_id'])?" selected":"").">".$drn['company_name']."</option>";
				}
				echo "</select>";
			}
			
			if($auth->UserType != "Administrator") { //Select device combo
				$query="SELECT DISTINCT mac AS mac, friendlyName FROM devices WHERE user_id=".$auth->UserId."";
			} else {
				$filter=(isset($_GET['uid'])?' AND users.user_id='.$_GET['uid']:'');
				$query="SELECT DISTINCT mac AS mac, friendlyName, users.company_name AS companyName FROM devices INNER JOIN users ON devices.user_id=users.user_id WHERE 1=1 ".$filter;
			}
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";
				echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=3'>".allDevices."</option>";
					while ($dr = $db->sql_fetchrow($result))
					{
						echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=3&mac=".$dr["mac"]."'".((isset($_GET['mac'])&&$_GET['mac']==$dr['mac'])?" selected":"").">".$dr['friendlyName']."</option>";
					}
				echo "</select>";
			}
        ?>
        <!-- Graph-->
            <div class="row-fluid">
                <div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-bar-chart"></i>
								<?=graphLastMonth?>
							</h3>
							<div class="actions">
								<a href="#" class="btn btn-mini content-refresh"><i class="icon-refresh"></i></a>
								<a href="#" class="btn btn-mini content-remove"><i class="icon-remove"></i></a>
								<a href="#" class="btn btn-mini content-slideUp"><i class="icon-angle-down"></i></a>
							</div>
						</div>
						<div class="box-content">
							<div class="statistic-big">
								<div class="top">
									<div class="right">
										<?=$statTotal." / ".$statTotalDistinct?> <span><i class="icon-circle-arrow-up"></i></span>
									</div>
								</div>
								<div class="bottom">
									<div style="padding: 0px; position: relative;" class="flot medium" id="placeholder">
									</div>
								</div>
							</div>
						</div>
					</div>                
                </div>
            </div>
            
		<script>
        $(document).ready(function() {  
		var d1_1=[<?=$g1?>];
        var d1_2=[<?=$g2?>];
        /* 
        // second column
        var d1_2 = [
            [1325376000000, 80],
            [1328054400000, 60],
            [1330560000000, 20],
            [1333238400000, 90],
            [1335830400000, 30]
        ];
        */
        var data1 = [
            {
                label: " ",
                data: d1_1,
                bars: {
                    show: true,
                    barWidth: 0.3,
                    fill: true,
                    lineWidth:0,
                    order: 1,
					align: "center",
                    fillColor:  {
                     colors: ["#F39494", "#f14d4d"]  //colors: ["#80C3FD", "#0089FF"] 
                }
                },
                color: "rgba(243, 89, 88, 0.7)"
            },

            {
                label: " ",
                data: d1_2,
                bars: {
                    show: true,
                    barWidth: 0.3,
                    fill: true,
                    lineWidth: 0,
                    order: 2,
					align: "center",
                    fillColor:  {
                    colors: ["#80C3FD", "#0089FF"] 
                }
                },
                color: "rgba(251, 176, 94, 0.7)"
            },
        ];
        
        $.plot($("#placeholder"), data1, {
            xaxis: {
                //min: (new Date(2011, 11, 15)).getTime(),
                //max: (new Date(2012, 04, 18)).getTime(),
                //mode: "time",
                //timeformat: "%b",
                // tickSize: [1, "month"],
                //monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                tickLength: 0, // hide gridlines
                //axisLabel: 'Month',
                //axisLabelUseCanvas: true,
                //axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                //axisLabelPadding: 5,
                ticks:[<?=$tickName?>]
            },
            yaxis: {
				max: <?=$max?>, ticks: [<?=$max1?>, <?=$max2?>, <?=($max1+$max2)?>, <?=$max?>],
                axisLabel: '#',
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                axisLabelPadding: 5,
                tickSize: 10,
                tickFormatter: function (val, axis) {
                        return val; // + "#";
                    },
        
            },
            grid: {
                hoverable: true,
                clickable: false,
                borderWidth: 0,
                borderColor:'#f0f0f0',
                labelMargin:8,
            },
            series: {
                shadowSize: 1,
            },
        
            legend: {
                show: false,
            },
            /*
            tooltip:true,
            tooltipOpts: {
                id:"chart-tooltip",
                content: "<p><b>20</b> Outgoing Filings</p>" +
                     "<p>Out of <b>10</b> committed;</p>" +
                     "<br />" +
                     "<p><b>30%</b>% Ratio</p>",
                shifts: {
                    x:-74,
                    y:-125
                },
                lines:{
                track: true
                },
                compat: true,
            },
            */
        });
      });
        
        </script>           
        <!-- end Graph-->
          <div class="row-fluid">
            <div class="span12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="icon-table"></i>
                            <?=$config["navigation"]." (".lastMonth.")"?>
                        </h3>
                    </div>
                    <div class="box-content nopadding">
                        <table class="table table-hover table-nomargin  dataTable table-bordered">
                            <thead>
								<? 	echo "<tr><td>Σύνολο</td><td>".$statTotal."</td><td>".$statTotalDistinct."</td></tr>";?>
                                <tr>
                                    <th><?=statDate?></th>
                                    <th><?=connections?></th>
                                    <th><?=unique?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                            for ($i = 31; $i >= 1; $i--) {
								$myDay=intval(date("d",strtotime($Date. ' - '.$i.' days')))+intval(date("m",strtotime($Date. ' - '.$i.' days')));
								if ($stat[$i]>0) {
									echo"<tr>";
									$detailsDay=date("Y-m-d",strtotime($Date. ' - '.$i.' days'));
									echo "<td><a href='index.php?com=stats&item=6&detailsDay=".$detailsday[$i]."'>".$detailsday[$i]."</a></td>";
									echo "<td>".(isset($stat[$i]) ? $stat[$i]:'')."</td>";
									echo "<td>".(isset($statDistinct[$i])?$statDistinct[$i]:'')."</td>";
									echo "</tr>";
								}
							}
								
							?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <? 
		}  else if(isset($_GET["item"]) && $_GET["item"]==4) //from the begining;
		{
			//$query="SELECT day(date_insert) as Day, hour(date_insert) as Hour, count(*) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") GROUP hour(date_insert)";
			//$query="SELECT * FROM stats WHERE device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." ORDER BY date_insert DESC";
			if($auth->UserType != "Administrator") {
				$query="SELECT sum(total) AS total, sum(total_unique) AS total_unique, date_insert FROM stats WHERE device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP BY date_insert ORDER BY date_insert DESC";
			} else {
				$query="SELECT sum(total) AS total, sum(total_unique) AS total_unique, date_insert FROM stats WHERE device IN (SELECT mac FROM devices ".(isset($_GET['uid'])?' WHERE user_id='.$_GET['uid']:'').") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP BY date_insert ORDER BY date_insert DESC";	//user_id=".$auth->UserId.") 
			}
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				$chartCounter=0;
				$max=0;
				while ($dr = $db->sql_fetchrow($result))
				{
					$chartCounter++;
					$string = $dr["date_insert"];
					$date = DateTime::createFromFormat("Y-m-d", $string);
					$detailsday[$chartCounter]=date("Y-m-d",strtotime($dr["date_insert"]));
					$num=intval($date->format("d"));
					$stat[$chartCounter]=$dr["total"];
					$max=($dr["total"]>$max?$dr["total"]:$max);
					$statDistinct[$chartCounter]=$dr["total_unique"];
					$statTotal=$statTotal+$dr["total"];
					$g1=$g1."[".$chartCounter.",".$stat[$chartCounter]."],";
					$g2=$g2."[".$chartCounter.",".$statDistinct[$chartCounter]."],";
					$statTotalDistinct=$statTotalDistinct+$dr["total_unique"];
					if($db->sql_numrows($result) > 10) {
						$tickName=$tickName."[".$chartCounter.",'".date("d",strtotime($dr["date_insert"])) ."'],";
					} else {
						$tickName=$tickName."[".$chartCounter.",'".date("d-m-y",strtotime($dr["date_insert"])) ."'],";
					}
				}
				$max1=floor($max/4);
				$max2=floor($max/2);
			}
					
			//Combo boxes
			if($auth->UserType == "Administrator") { // Select network combo
				$resultNetworks=$db->sql_query('SELECT * FROM users WHERE user_auth!="Administrator"');
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";				
				echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=4'>".allNetworks."</option>";
				while ($drn = $db->sql_fetchrow($resultNetworks)){
						echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=4&uid=".$drn["user_id"]."'".((isset($_GET['uid'])&&$_GET['uid']==$drn['user_id'])?" selected":"").">".$drn['company_name']."</option>";
				}
				echo "</select>";
			}
			
			if($auth->UserType != "Administrator") { //Select device combo
				$query="SELECT DISTINCT mac AS mac, friendlyName FROM devices WHERE user_id=".$auth->UserId."";
			} else {
				$filter=(isset($_GET['uid'])?' AND users.user_id='.$_GET['uid']:'');
				$query="SELECT DISTINCT mac AS mac, friendlyName, users.company_name AS companyName FROM devices INNER JOIN users ON devices.user_id=users.user_id WHERE 1=1 ".$filter;
			}
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";
				echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=4'>".allDevices."</option>";
					while ($dr = $db->sql_fetchrow($result))
					{
						echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=4&mac=".$dr["mac"]."'".((isset($_GET['mac'])&&$_GET['mac']==$dr['mac'])?" selected":"").">".$dr['friendlyName']."</option>";
					}
				echo "</select>";
			}
        ?>
        <!-- Graph-->
            <div class="row-fluid">
                <div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-bar-chart"></i>
								<?=graphFromTheBeginning?>
							</h3>
							<div class="actions">
								<a href="#" class="btn btn-mini content-refresh"><i class="icon-refresh"></i></a>
								<a href="#" class="btn btn-mini content-remove"><i class="icon-remove"></i></a>
								<a href="#" class="btn btn-mini content-slideUp"><i class="icon-angle-down"></i></a>
							</div>
						</div>
						<div class="box-content">
							<div class="statistic-big">
								<div class="top">
									<div class="right">
										<?=$statTotal." / ".$statTotalDistinct?> <span><i class="icon-circle-arrow-up"></i></span>
									</div>
								</div>
								<div class="bottom">
									<div style="padding: 0px; position: relative;" class="flot medium" id="placeholder">
									</div>
								</div>
							</div>
						</div>
					</div>                
                </div>
            </div>
            
		<script>
        $(document).ready(function() {  
		var d1_1=[<?=$g1?>];
        var d1_2=[<?=$g2?>];
        /* 
        // second column
        var d1_2 = [
            [1325376000000, 80],
            [1328054400000, 60],
            [1330560000000, 20],
            [1333238400000, 90],
            [1335830400000, 30]
        ];
        */
        var data1 = [
            {
                label: " ",
                data: d1_1,
                bars: {
                    show: true,
                    barWidth: 0.3,
                    fill: true,
                    lineWidth:0,
                    order: 1,
					align: "center",
                    fillColor:  {
                     colors: ["#F39494", "#f14d4d"]  //colors: ["#80C3FD", "#0089FF"] 
                }
                },
                color: "rgba(243, 89, 88, 0.7)"
            },

            {
                label: " ",
                data: d1_2,
                bars: {
                    show: true,
                    barWidth: 0.3,
                    fill: true,
                    lineWidth: 0,
                    order: 2,
					align: "center",
                    fillColor:  {
                    colors: ["#80C3FD", "#0089FF"] 
                }
                },

                color: "rgba(251, 176, 94, 0.7)"
            },
        ];
        
        $.plot($("#placeholder"), data1, {
            xaxis: {
                //min: (new Date(2011, 11, 15)).getTime(),
                //max: (new Date(2012, 04, 18)).getTime(),
                //mode: "time",
                //timeformat: "%b",
                // tickSize: [1, "month"],
                //monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                tickLength: 0, // hide gridlines
                //axisLabel: 'Month',
                //axisLabelUseCanvas: true,
                //axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                //axisLabelPadding: 5,
                ticks:[<?=$tickName?>]
            },
            yaxis: {
				max: <?=$max?>, ticks: [<?=$max1?>, <?=$max2?>, <?=($max1+$max2)?>, <?=$max?>],
                axisLabel: '#',
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                axisLabelPadding: 5,
                tickSize: 10,
                tickFormatter: function (val, axis) {
                        return val; // + "#";
                    },
        
            },
            grid: {
                hoverable: true,
                clickable: false,
                borderWidth: 0,
                borderColor:'#f0f0f0',
                labelMargin:8,
            },
            series: {
                shadowSize: 1,
            },
        
            legend: {
                show: false,
            },
            /*
            tooltip:true,
            tooltipOpts: {
                id:"chart-tooltip",
                content: "<p><b>20</b> Outgoing Filings</p>" +
                     "<p>Out of <b>10</b> committed;</p>" +
                     "<br />" +
                     "<p><b>30%</b>% Ratio</p>",
                shifts: {
                    x:-74,
                    y:-125
                },
                lines:{
                track: true
                },
                compat: true,
            },
            */
        });
      });
        
        </script>           
        <!-- end Graph-->
          <div class="row-fluid">
            <div class="span12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="icon-table"></i>
                            <?=$config["navigation"]." (".fromTheBegenning.")"?>
                        </h3>
                    </div>
                    <div class="box-content nopadding">
                        <table class="table table-hover table-nomargin  dataTable table-bordered">
                            <thead>
								<? 	echo "<tr><td>Σύνολο</td><td>".$statTotal."</td><td>".$statTotalDistinct."</td></tr>";?>
                                <tr>
                                    <th><?=statDate?></th>
                                    <th><?=connections?></th>
                                    <th><?=unique?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                            for ($i = 1; $i <=$chartCounter; $i++) {
								$myDay=intval(date("d",strtotime($Date. ' - '.$i.' days')));
								if ($stat[$i]>0) {
									echo"<tr>";
									//$detailsDay=date("Y-m-d",strtotime($Date. ' - '.$i.' days'));
									echo "<td><a href='index.php?com=stats&item=6&detailsDay=".$detailsday[$i]."'>".$detailsday[$i]."</a></td>";
									echo "<td>".(isset($stat[$i]) ? $stat[$i]:'')."</td>";
									echo "<td>".(isset($statDistinct[$i])?$statDistinct[$i]:'')."</td>";
									echo "</tr>";
								}
							}
							?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <?
        }  else if(isset($_GET["item"]) && $_GET["item"]==5) //Specify days
        {
			if($auth->UserType != "Administrator") {
				if (!isset($_REQUEST['fromdate']) || !isset($_REQUEST['todate'])) {
					$query="SELECT sum(total) AS total, sum(total_unique) AS total_unique, date_insert FROM stats WHERE (date_insert)>(CURDATE()- INTERVAL 7 DAY) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP BY date_insert ORDER BY date_insert DESC";
	
				} else {
					$query="SELECT sum(total) AS total, sum(total_unique) AS total_unique, date_insert FROM stats WHERE date_insert>='".$_REQUEST['fromdate']."' AND date_insert<='".$_REQUEST['todate']. "' AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP BY date_insert ORDER BY date_insert DESC";
				}
			} else {
				if (!isset($_REQUEST['fromdate']) || !isset($_REQUEST['todate'])) {
					$query="SELECT sum(total) AS total, sum(total_unique) AS total_unique, date_insert FROM stats WHERE (date_insert)>(CURDATE()- INTERVAL 7 DAY) AND device IN (SELECT mac FROM devices ".(isset($_GET['uid'])?' WHERE user_id='.$_GET['uid']:'').") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP BY date_insert ORDER BY date_insert DESC";
	
				} else {
					$query="SELECT sum(total) AS total, sum(total_unique) AS total_unique, date_insert FROM stats WHERE date_insert>='".$_REQUEST['fromdate']."' AND date_insert<='".$_REQUEST['todate']. "' AND device IN (SELECT mac FROM devices".(isset($_GET['uid'])?' WHERE user_id='.$_GET['uid']:'').") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP BY date_insert ORDER BY date_insert DESC";
				}
				//$query="SELECT * FROM stats WHERE device IN (SELECT mac FROM devices ".(isset($_GET['uid'])?' WHERE user_id='.$_GET['uid']:'').") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." ORDER BY date_insert DESC";	//user_id=".$auth->UserId.") 
			}

			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				$statTotal=0;
				$statTotalDistinct=0;
				$chartCounter=0;
				$max=0;
				while ($dr = $db->sql_fetchrow($result))
				{
					$chartCounter++;
					$string = $dr["date_insert"];
					$date = DateTime::createFromFormat("Y-m-d", $string);
					$detailsday[$chartCounter]=date("Y-m-d",strtotime($dr["date_insert"]));
					$num=intval($date->format("d"));
					$stat[$chartCounter]=$dr["total"];
					$max=($dr["total"]>$max?$dr["total"]:$max);
					$statDistinct[$chartCounter]=$dr["total_unique"];
					$g1=$g1."[".$chartCounter.",".$stat[$chartCounter]."],";
					$g2=$g2."[".$chartCounter.",".$statDistinct[$chartCounter]."],";
					$statTotal=$statTotal+$dr["total"];
					$statTotalDistinct=$statTotalDistinct+$dr["total_unique"];
					if($db->sql_numrows($result) > 10) {
						$tickName=$tickName."[".$chartCounter.",'".date("d",strtotime($dr["date_insert"])) ."'],";
					} else {
						$tickName=$tickName."[".$chartCounter.",'".date("d-m-y",strtotime($dr["date_insert"])) ."'],";
					}
				}
			}
			$max1=floor($max/4);
			$max2=floor($max/2);	
								
			//mac addresses combo box
			$query="SELECT DISTINCT mac AS mac, friendlyName FROM devices WHERE user_id=".$auth->UserId."";
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";
				echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=5'>".allDevices."</option>";
				while ($dr = $db->sql_fetchrow($result))
				{
					echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=5&mac=".$dr["mac"]."'".((isset($_GET['mac'])&&$_GET['mac']==$dr['mac'])?" selected":"").">".$dr['friendlyName']."</option>";
				}
				echo "</select>";
			}
        ?>
        
        <div class="control-group">
            <label for="textfield" class="control-label"><?=statFrom?></label>
            <div class="controls">
                <input type="text" name="fromdate" id="fromdate" class="input-medium datepick" data-date-format="yyyy-mm-dd" value="<?=(isset($_REQUEST["fromdate"]) ? $_REQUEST["fromdate"] : date('Y-m-d', strtotime(' -7 day')) )?>">
            </div>
        </div>
        
        <div class="control-group">
            <label for="textfield" class="control-label"><?=statΤο?></label>
            <div class="controls">
                <input type="text" name="todate" id="todate" class="input-medium datepick" data-date-format="yyyy-mm-dd" value="<?=(isset($_REQUEST["todate"]) ? $_REQUEST["todate"] : date('Y-m-d') )?>">
            </div>
        </div>
        <input type="hidden" value="9" name="item">
		<input type="submit" value="Search" class="button" name="search" id="search">
        <!-- Graph-->
            <div class="row-fluid">
                <div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-bar-chart"></i>
								<?=graphFromTo?>
							</h3>
							<div class="actions">
								<a href="#" class="btn btn-mini content-refresh"><i class="icon-refresh"></i></a>
								<a href="#" class="btn btn-mini content-remove"><i class="icon-remove"></i></a>
								<a href="#" class="btn btn-mini content-slideUp"><i class="icon-angle-down"></i></a>
							</div>
						</div>
						<div class="box-content">
							<div class="statistic-big">
								<div class="top">
									<div class="right">
										<?=$statTotal." / ".$statTotalDistinct?> <span><i class="icon-circle-arrow-up"></i></span>
									</div>
								</div>
								<div class="bottom">
									<div style="padding: 0px; position: relative;" class="flot medium" id="placeholder">
									</div>
								</div>
							</div>
						</div>
					</div>
                </div>
            </div>
            
		<script>
        $(document).ready(function() {  
		var d1_1=[<?=$g1?>];
        var d1_2=[<?=$g2?>];
        /* 
        // second column
        var d1_2 = [
            [1325376000000, 80],
            [1328054400000, 60],
            [1330560000000, 20],
            [1333238400000, 90],
            [1335830400000, 30]
        ];
        */
        var data1 = [
            {
                label: " ",
                data: d1_1,
                bars: {
                    show: true,
                    barWidth: 0.3,
                    fill: true,
                    lineWidth:0,
                    order: 1,
					align: "center",
                    fillColor:  {
                     colors: ["#F39494", "#f14d4d"]  //colors: ["#80C3FD", "#0089FF"] 
                }
                },
                color: "rgba(243, 89, 88, 0.7)"
            },

            {
                label: " ",
                data: d1_2,
                bars: {
                    show: true,
                    barWidth: 0.3,
                    fill: true,
                    lineWidth: 0,
                    order: 2,
					align: "center",
                    fillColor:  {
                    colors: ["#80C3FD", "#0089FF"] 
                }
                },
                color: "rgba(251, 176, 94, 0.7)"
            },
        ];
        
        $.plot($("#placeholder"), data1, {
            xaxis: {
			 	//autoscaleMargin:0.5
                //min: (new Date(2011, 11, 15)).getTime(),
                //max: (new Date(2012, 04, 18)).getTime(),
                //mode: "time",
                //timeformat: "%b",
                // tickSize: [1, "month"],
                //monthNames: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                tickLength: 0, // hide gridlines
                //axisLabel: 'Month',
                //axisLabelUseCanvas: true,
                //axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                //axisLabelPadding: 5,
                ticks:[<?=$tickName?>]
            },
            yaxis: {
				max: <?=$max?>, ticks: [<?=$max1?>, <?=$max2?>, <?=($max1+$max2)?>, <?=$max?>],
                axisLabel: '#',
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                axisLabelPadding: 5,
                tickSize: 10,
                tickFormatter: function (val, axis) {
                        return val; // + "#";
                    },
        
            },
            grid: {
                hoverable: true,
                clickable: false,
                borderWidth: 0,
                borderColor:'#f0f0f0',
                labelMargin:8,
            },
            series: {
                shadowSize: 1,
            },
        
            legend: {
                show: false,
            },
            /*
            tooltip:true,
            tooltipOpts: {
                id:"chart-tooltip",
                content: "<p><b>20</b> Outgoing Filings</p>" +
                     "<p>Out of <b>10</b> committed;</p>" +
                     "<br />" +
                     "<p><b>30%</b>% Ratio</p>",
                shifts: {
                    x:-74,
                    y:-125
                },
                lines:{
                track: true
                },
                compat: true,
            },
            */
        });
      });
        
        </script>           
        <!-- end Graph-->
          <div class="row-fluid">
            <div class="span12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="icon-table"></i>
                             <?=$config["navigation"]." (".fromTo.")"?>
                        </h3>
                    </div>
                    <div class="box-content nopadding">
                        <table class="table table-hover table-nomargin  table-bordered">
                            <thead>
								<? 	echo "<tr><td>Σύνολο</td><td>".$statTotal."</td><td>".$statTotalDistinct."</td></tr>";?>
                                <tr>
                                    <th><?=statDate?></th>
                                    <th><?=connections?></th>
                                    <th><?=unique?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                            for ($i = 1; $i <= $chartCounter; $i++) {
								$myDay=intval(date("d",strtotime($Date. ' - '.$i.' days')));
								if ($stat[$i]>0) {
									echo"<tr>";
									$detailsDay=date("Y-m-d",strtotime($Date. ' - '.$i.' days'));
									echo "<td><a href='index.php?com=stats&item=6&detailsDay=".$detailsday[$i]."'>".$detailsday[$i]."</a></td>";
									echo "<td>".(isset($stat[$i]) ? $stat[$i]:'')."</td>";
									echo "<td>".(isset($statDistinct[$i])?$statDistinct[$i]:'')."</td>";
									echo "</tr>";
								}
							}
							?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <? }else if(isset($_GET["item"]) && $_GET["item"]==6) //Specific day;
		{	
			$filter=($auth->UserType != "Administrator"?'WHERE user_id='.$auth->UserId:'');
			//$filter='';
			$query="SELECT 
			sum(`total`) AS total,
			sum(`total_unique`) AS total_unique,
			sum(`1`) AS c1,
			sum(`2`) AS c2,
			sum(`3`) AS c3,
			sum(`4`) AS c4,
			sum(`5`) AS c5,
			sum(`6`) AS c6,
			sum(`7`) AS c7,
			sum(`8`) AS c8,
			sum(`9`) AS c9,
			sum(`10`) AS c10,
			sum(`11`) AS c11,
			sum(`12`) AS c12,
			sum(`13`) AS c13,
			sum(`14`) AS c14,
			sum(`15`) AS c15,
			sum(`16`) AS c16,
			sum(`17`) AS c17,
			sum(`18`) AS c18,
			sum(`19`) AS c19,
			sum(`20`) AS c20,
			sum(`21`) AS c21,
			sum(`22`) AS c22,
			sum(`23`) AS c23,
			sum(`24`) AS c24
			FROM stats WHERE date_insert='".$_GET["detailsDay"]."' AND device IN (SELECT mac FROM devices ".$filter." ) ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":""); //GROUP by date_insert
			//$query="SELECT hour(date_insert) as Hour, FROM dailystats WHERE day(date_insert)=day(CURDATE()-1) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") GROUP by hour(date_insert)";
			$result = $db->RowSelectorQuery($query);
			//echo $query;
			$chartCounter=0;
			if(isset($result['total'])) {
				for($i=1; $i<=24; $i++)// ($dr = $db->sql_fetchrow($result))
				{
					$chartCounter++;
					$num=$i;
					$stat[$i]=$result["c".$i];
					$tickName=$tickName."[".$i.",'".$i ."'],";
					$g1=$g1."[".$i.",".($stat[$i]>0?$stat[$i]:0)."],";
					$max=($stat[$i]>$max?$stat[$i]:$max);					
				}
			}
			$max1=floor($max/4);
			$max2=floor($max/2);
			//mac addresses combo box
			$query="SELECT DISTINCT mac AS mac, friendlyName FROM devices WHERE user_id=".$auth->UserId."";
			$result2 = $db->sql_query($query);
			if($db->sql_numrows($result2) > 0) {
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";
				echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=6&detailsDay=".$_GET["detailsDay"]."'>".allDevices."</option>";
				while ($dr = $db->sql_fetchrow($result2))
				{
					echo "<option value='http://panel.spotyy.com/index.php?com=stats&item=6&detailsDay=".$_GET["detailsDay"]."&mac=".$dr["mac"]."'".((isset($_GET['mac'])&&$_GET['mac']==$dr['mac'])?" selected":"").">".$dr['friendlyName']."</option>";
				}
				echo "</select>";
			}
        ?>
        
        <!-- Graph-->
            <div class="row-fluid">
                <div class="span12">
					<div class="box box-color box-bordered">
						<div class="box-title">
							<h3>
								<i class="icon-bar-chart"></i>
								<?=graphDay?>
							</h3>
							<div class="actions">
								<a href="#" class="btn btn-mini content-refresh"><i class="icon-refresh"></i></a>
								<a href="#" class="btn btn-mini content-remove"><i class="icon-remove"></i></a>
								<a href="#" class="btn btn-mini content-slideUp"><i class="icon-angle-down"></i></a>
							</div>
						</div>
						<div class="box-content">
							<div class="statistic-big">
								<div class="top">
									<div class="right">
										<?=$statTotal." / ".$statTotalDistinct?> <span><i class="icon-circle-arrow-up"></i></span>
									</div>
								</div>
								<div class="bottom">
									<div style="padding: 0px; position: relative;" class="flot medium" id="placeholder">
									</div>
								</div>
							</div>
						</div>
					</div>                
                </div>
            </div>
		<script type="text/javascript">
        $(function() {
            var d1 = [<?=$g1?>];
            $.plot("#placeholder", [{
                data: d1,
                bars: { show: true } //lines, bars, points,lines: { show: true, fill: true }
            }]);
        });
        </script>
        <!-- flot chart -->
		<script>
        $(document).ready(function() {  
			var d1_1=[<?=$g1?>];
			var data1 = [
				{
					label: " ",
					data: d1_1,
					bars: {
						show: true,
						barWidth: 0.3,
						fill: true,
						lineWidth:0,
						order: 1,
						align: "center",
						fillColor:  {
						 colors: ["#F39494", "#f14d4d"]  //colors: ["#80C3FD", "#0089FF"] 
					}
					},
					color: "rgba(243, 89, 88, 0.7)"
				},
			];
        
        $.plot($("#placeholder"), data1, {
            xaxis: {
                tickLength: 0, // hide gridlines
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                //axisLabelPadding: 5,
                ticks:[<?=$tickName?>]
            },
            yaxis: {
				max: <?=$max?>, ticks: [<?=$max1?>, <?=$max2?>, <?=($max1+$max2)?>, <?=$max?>],
                axisLabel: '#',
                axisLabelUseCanvas: true,
                axisLabelFontSizePixels: 12,
                axisLabelFontFamily: 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                axisLabelPadding: 5,
                tickSize: 10,
                tickFormatter: function (val, axis) {
                        return val; // + "#";
				},
            },
            grid: {
                hoverable: true,
                clickable: false,
                borderWidth: 0,
                borderColor:'#f0f0f0',
                labelMargin:8,
            },
            series: {
                shadowSize: 1,
            },
        
            legend: {
                show: false,
            },
        });
        
        });
        
        </script>  
        <!-- end Graph-->
          <div class="row-fluid">
            <div class="span12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="icon-table"></i>
                            <?=$config["navigation"]." (".statDate.")"?>
                        </h3>
                    </div>
                    <div class="box-content nopadding"> 
                        <table class="table table-hover table-nomargin   table-bordered"> <!--  dataTable-->
                            <thead>
								<? 	echo "<tr><td>Σύνολο</td><td>".$result["total"]."</td><td>".$result["total_unique"]."</td></tr>";?>
                                <tr>
                                    <th><?=graphTime?></th>
                                    <th><?=connections?></th>
                                    <th><?=unique?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                            for ($i = 1; $i <= 24; $i++) {
								if ($result["c".$i]>0) {
									echo"<tr>";
									echo "<td>".($i<=10?"0".($i-1):$i-1).":00-".($i<10?"0".$i:$i).":00</td>";
									echo "<td>".(isset($result["c".$i])?$result["c".$i]:'')."</td>";
									echo "<td>".(isset($result["total_unique"])?$result["total_unique"]:'')."</td>";
									echo "</tr>";
								}
							}
							?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <? }
		?>