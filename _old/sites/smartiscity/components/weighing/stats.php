<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
$config["navigation"] = stats;
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
				$query="SELECT hour(gpsdate) as Hour, sum(weight) as Count FROM data WHERE day(gpsdate)=day(CURDATE()) AND gpsID IN (SELECT imei FROM vehicles WHERE municipality_id=".$auth->UserRow['municipality_id'].") ".(isset($_GET['imei'])?" AND gpsID='".$_GET['imei']."'":"")." GROUP by hour(gpsdate)";
			} else {
				$query="SELECT hour(gpsdate) as Hour, sum(weight) as Count FROM data WHERE day(gpsdate)=day(CURDATE()) AND gpsID IN (SELECT imei FROM vehicles ".(isset($_GET['uid'])?' WHERE municipality_id='.$_GET['uid']:'').") ".(isset($_GET['imei'])?" AND gpsID='".$_GET['imei']."'":"")." GROUP by hour(gpsdate)";	//user_id=".$auth->UserId.") 
			}
			$result = $db->sql_query($query);
			$statTotal=0;
			$chartCounter=0;
			$max=0;
			if($db->sql_numrows($result) > 0) {
				while ($dr = $db->sql_fetchrow($result))
				{
					$chartCounter++;
					$num=$dr["Hour"];
					$stat[$num]=$dr["Count"];
					$statTotal=$statTotal+$dr["Count"];
					$tickName=$tickName."[".$num.",'".$num ."'],";
				}
				for ($x = 0; $x <= 23; $x++)  {
					$g1=$g1."[".$x.",".($stat[$x]>0?$stat[$x]:0)."],";
					$max=($stat[$x]>$max?$stat[$x]:$max);	
					$tickName=$tickName."[".$x.",'".$x ."'],";
				}
			}
			$max1=floor($max/4);
			$max2=floor($max/2);
				
			//Combo boxes
			if($auth->UserType == "Administrator") { // Select network combo
				$resultNetworks=$db->sql_query('SELECT * FROM municipalities');
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";				
				echo "<option value='index.php?com=stats&item=0'>Όλοι οι δήμοι</option>";
				while ($drn = $db->sql_fetchrow($resultNetworks)){
					echo "<option value='index.php?com=stats&item=0&uid=".$drn["municipality_id"]."'".((isset($_GET['uid'])&&$_GET['uid']==$drn['municipality_id'])?" selected":"").">".$drn['name']."</option>";
				}
				echo "</select>";
			}
			
			if($auth->UserType != "Administrator") { //Select device combo
				$query="SELECT imei, plate FROM vehicles WHERE municipality_id=".$auth->UserRow['municipality_id']."";
			} else {
				$filter=(isset($_GET['uid'])?' AND t2.municipality_id='.$_GET['uid']:'');
				$query="SELECT t1.imei, t1.plate, t2.name AS municipalityName FROM vehicles t1 INNER JOIN municipalities t2 ON t1.municipality_id=t2.municipality_id WHERE 1=1 ".$filter;
			}
			
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";
				echo "<option value='index.php?com=stats&item=0'>Όλα τα οχήματα</option>";
					while ($dr = $db->sql_fetchrow($result))
					{
						$uid=((isset($_GET['uid']) && intval($_GET['uid'])>0)?'&uid='.$_GET['uid']:'');
						echo "<option value='index.php?com=stats&item=0".$uid."&imei=".$dr["imei"]."'".((isset($_GET['imei'])&&$_GET['imei']==$dr['imei'])?" selected":"").">".$dr['municipalityName'].'-'.$dr['plate']."</option>";
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
										<?=$statTotal." "?> <span><i class="icon-circle-arrow-up"></i></span>
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
                            <? 	echo "<tr><td>".graphTotal."</td><td>".$statTotal."</td></tr>";?>
                                <tr>
                                    <th><?=graphTime?></th>
                                    <th>Ζυγίσεις</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                            for ($i = 0; $i <= 23; $i++) {
								if ($stat[$i]>0) {
									echo"<tr>";
									echo "<td>".$i.":00-".($i+1).":00</td>";
									echo "<td>".(isset($stat[$i])?number_format($stat[$i],0):'')."</td>";
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
				$query="SELECT hour(gpsdate) as Hour, sum(weight) as Count FROM data WHERE day(gpsdate)=day(CURDATE()-1) AND gpsID IN (SELECT imei FROM vehicles WHERE municipality_id=".$auth->UserRow['municipality_id'].") ".(isset($_GET['imei'])?" AND gpsID='".$_GET['imei']."'":"")." GROUP by hour(gpsdate)";
			} else {
				$query="SELECT hour(gpsdate) as Hour, sum(weight) as Count FROM data WHERE day(gpsdate)=day(CURDATE()-1) AND gpsID IN (SELECT imei FROM vehicles ".(isset($_GET['uid'])?' WHERE municipality_id='.$_GET['uid']:'').") ".(isset($_GET['imei'])?" AND gpsID='".$_GET['imei']."'":"")." GROUP by hour(gpsdate)";	//user_id=".$auth->UserId.") 
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
					$tickName=$tickName."[".$num.",'".$num ."'],";
				}
				for ($x = 0; $x <= 23; $x++)  {
					$g1=$g1."[".$x.",".($stat[$x]>0?$stat[$x]:0)."],";
					$tickName=$tickName."[".$x.",'".$x ."'],";
					$max=($stat[$x]>$max?$stat[$x]:$max);
				}
			}
			$max1=floor($max/4);
			$max2=floor($max/2);
			/*
			if($auth->UserType != "Administrator") {
				$query="SELECT hour(date_insert) as Hour, count(distinct device) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()- INTERVAL 1 DAY) AND device IN (SELECT device FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP by hour(date_insert)";
			} else {
				$query="SELECT hour(date_insert) as Hour, count(distinct device) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()- INTERVAL 1 DAY) AND device IN (SELECT device FROM devices ".(isset($_GET['uid'])?' WHERE user_id='.$_GET['uid']:'').") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"")." GROUP by hour(date_insert)";
			}

			$result = $db->sql_query($query);
			$statTotalDistinct=0;
			if($db->sql_numrows($result) > 0) {
				while ($dr = $db->sql_fetchrow($result))
				{
					$num=$dr["Hour"];
					$statDistinct[$num]=$dr["Count"];
					$statTotalDistinct=$statTotalDistinct+$dr["Count"];
					$tickName=$tickName."[".$num.",'".$num ."'],";
				}
				for ($x = 0; $x <= 23; $x++)  {
					$g1=$g1."[".$x.",".($stat[$x]>0?$stat[$x]:0)."],";
				}
			}
			
			if($auth->UserType != "Administrator") {
				$query="SELECT count(distinct mac) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()-1) AND device IN (SELECT mac FROM devices WHERE user_id=".$auth->UserId.") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"");
			} else {
				$query="SELECT count(distinct mac) as Count FROM dailystats WHERE day(date_insert)=day(CURDATE()-1) AND device IN (SELECT mac FROM devices WHERE 1=1".(isset($_GET['uid'])?' AND user_id='.$_GET['uid']:'').") ".(isset($_GET['mac'])?" AND device='".$_GET['mac']."'":"");			
			}			
			$result = $db->RowSelectorQuery($query);
			$dailyDisting=$result["Count"];
			*/
			
			//Combo boxes
			if($auth->UserType == "Administrator") { // Select network combo
				$resultNetworks=$db->sql_query('SELECT * FROM municipalities');
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";				
				echo "<option value='index.php?com=stats&item=1'>Όλοι οι δήμοι</option>";
				while ($drn = $db->sql_fetchrow($resultNetworks)){
					echo "<option value='index.php?com=stats&item=1&uid=".$drn["municipality_id"]."'".((isset($_GET['uid'])&&$_GET['uid']==$drn['municipality_id'])?" selected":"").">".$drn['name']."</option>";
				}
				echo "</select>";
			}
			
			if($auth->UserType != "Administrator") { //Select device combo
				$query="SELECT imei, plate FROM vehicles WHERE municipality_id=".$auth->UserRow['municipality_id']."";
			} else {
				$filter=(isset($_GET['uid'])?' AND t2.municipality_id='.$_GET['uid']:'');
				$query="SELECT t1.imei, t1.plate, t2.name AS municipalityName FROM vehicles t1 INNER JOIN municipalities t2 ON t1.municipality_id=t2.municipality_id WHERE 1=1 ".$filter;
			}
			
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";
				echo "<option value='index.php?com=stats&item=1'>Όλα τα οχήματα</option>";
					while ($dr = $db->sql_fetchrow($result))
					{
						$uid=((isset($_GET['uid']) && intval($_GET['uid'])>0)?'&uid='.$_GET['uid']:'');
						echo "<option value='index.php?com=stats&item=1".$uid."&imei=".$dr["imei"]."'".((isset($_GET['imei'])&&$_GET['imei']==$dr['imei'])?" selected":"").">".$dr['municipalityName'].'-'.$dr['plate']."</option>";
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
										<?=$statTotal." / "?> <span><i class="icon-circle-arrow-up"></i></span>
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
                            <?=$config["navigation"]." (Χθες)" ?>
                        </h3>
                    </div>
                    <div class="box-content nopadding">
                        <table class="table table-hover table-nomargin  table-bordered">
                            <thead>
								<? 	echo "<tr><td>".graphTotal."</td><td>".$statTotal."</td></tr>";?>
                                <tr>
                                    <th><?=graphTime?></th>
                                    <th>Ζυγίσεις</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                            for ($i = 0; $i <= 23; $i++) {
								if ($stat[$i]>0) {
									echo"<tr>";
									echo "<td>".$i.":00-".($i+1).":00</td>";
									echo "<td>".(isset($stat[$i])?number_format($stat[$i],0):'')."</td>";
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
			if($auth->UserType != "Administrator") {
				$query="SELECT sum(weight) AS total, gpsdate FROM data WHERE (gpsdate)>(CURDATE()- INTERVAL 9 DAY) AND gpsID IN (SELECT imei FROM vehicles WHERE municipality_id=".$auth->UserRow['municipality_id'].") ".(isset($_GET['imei'])?" AND gpsID='".$_GET['imei']."'":"")." GROUP BY date(gpsdate) ORDER BY gpsdate DESC";
			} else {
				$query="SELECT sum(weight) AS total, gpsdate FROM data WHERE (gpsdate)>(CURDATE()- INTERVAL 9 DAY) AND gpsID IN (SELECT imei FROM vehicles ".(isset($_GET['uid'])?' WHERE municipality_id='.$_GET['uid']:'').") ".(isset($_GET['imei'])?" AND gpsID='".$_GET['imei']."'":"")." GROUP BY date(gpsdate) ORDER BY gpsdate DESC";	//user_id=".$auth->UserId.") 
			}

			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				$statTotal=0;
				$chartCounter=0;
				$max=0;
				while ($dr = $db->sql_fetchrow($result))
				{
					$chartCounter++;
					$string = $dr["gpsdate"];
					//$date = DateTime::createFromFormat("Y-m-d", $string);
					$date=date("Y-m-d",strtotime($dr["gpsdate"]));
					$detailsday[$chartCounter]=date("Y-m-d",strtotime($dr["gpsdate"]));
					$num=intval(date("d",strtotime($dr["gpsdate"])));
					//$num=intval($date->format("d"));
					
					$stat[$chartCounter]=$dr["total"];
					$max=($dr["total"]>$max?$dr["total"]:$max);	
					$g1=$g1."[".$chartCounter.",".$stat[$chartCounter]."],";
					$statTotal=$statTotal+$dr["total"];
					$tickName=$tickName."[".$chartCounter.",'".date("d-m-y",strtotime($dr["gpsdate"])) ."'],";
				}
			}
			$max1=floor($max/4);
			$max2=floor($max/2);
			
			//Combo boxes
			if($auth->UserType == "Administrator") { // Select network combo
				$resultNetworks=$db->sql_query('SELECT * FROM municipalities');
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";				
				echo "<option value='index.php?com=stats&item=2'>Όλοι οι δήμοι</option>";
				while ($drn = $db->sql_fetchrow($resultNetworks)){
					echo "<option value='index.php?com=stats&item=2&uid=".$drn["municipality_id"]."'".((isset($_GET['uid'])&&$_GET['uid']==$drn['municipality_id'])?" selected":"").">".$drn['name']."</option>";
				}
				echo "</select>";
			}
			
			if($auth->UserType != "Administrator") { //Select device combo
				$query="SELECT imei, plate FROM vehicles WHERE municipality_id=".$auth->UserRow['municipality_id']."";
			} else {
				$filter=(isset($_GET['uid'])?' AND t2.municipality_id='.$_GET['uid']:'');
				$query="SELECT t1.imei, t1.plate, t2.name AS municipalityName FROM vehicles t1 INNER JOIN municipalities t2 ON t1.municipality_id=t2.municipality_id WHERE 1=1 ".$filter;
			}
			
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";
				echo "<option value='index.php?com=stats&item=2'>Όλα τα οχήματα</option>";
					while ($dr = $db->sql_fetchrow($result))
					{
						$uid=((isset($_GET['uid']) && intval($_GET['uid'])>0)?'&uid='.$_GET['uid']:'');
						echo "<option value='index.php?com=stats&item=2".$uid."&imei=".$dr["imei"]."'".((isset($_GET['imei'])&&$_GET['imei']==$dr['imei'])?" selected":"").">".$dr['municipalityName'].'-'.$dr['plate']."</option>";
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
										<?=$statTotal?> <span><i class="icon-circle-arrow-up"></i></span>
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
                             <?=$config["navigation"]." (".lastWeek.")"?>
                        </h3>
                    </div>
                    <div class="box-content nopadding">
                        <table class="table table-hover table-nomargin  table-bordered">
                            <thead>
								<? 	echo "<tr><td>Σύνολο</td><td>".$statTotal."</td></tr>";?>
                                <tr>
                                    <th><?=statDate?></th>
                                    <th>Ζυγίσεις</th>
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
									echo "<td>".(isset($stat[$i]) ? number_format($stat[$i],0):'')."</td>";
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
			if($auth->UserType != "Administrator") {
				$query="SELECT sum(weight) AS total, gpsdate FROM data WHERE (gpsdate)>(CURDATE()- INTERVAL 31 DAY) AND gpsID IN (SELECT imei FROM vehicles WHERE municipality_id=".$auth->UserRow['municipality_id'].") ".(isset($_GET['imei'])?" AND gpsID='".$_GET['imei']."'":"")." GROUP BY date(gpsdate) ORDER BY gpsdate DESC";
			} else {
				$query="SELECT sum(weight) AS total, gpsdate FROM data WHERE (gpsdate)>(CURDATE()- INTERVAL 31 DAY) AND gpsID IN (SELECT imei FROM vehicles ".(isset($_GET['uid'])?' WHERE municipality_id='.$_GET['uid']:'').") ".(isset($_GET['imei'])?" AND gpsID='".$_GET['imei']."'":"")." GROUP BY date(gpsdate) ORDER BY gpsdate DESC";	//user_id=".$auth->UserId.") 
			}
			$result = $db->sql_query($query);
			$chartCounter=0;
			$max=0;
			if($db->sql_numrows($result) > 0) {
				while ($dr = $db->sql_fetchrow($result))
				{
					$chartCounter++;
					$string = $dr["gpsdate"];
					//$date = DateTime::createFromFormat("Y-m-d", $string);
					$date=date("Y-m-d",strtotime($dr["gpsdate"]));
					$detailsday[$chartCounter]=date("Y-m-d",strtotime($dr["gpsdate"]));
					//$num=intval($date->format("d"))+intval($date->format("m"));
					$num=intval(date("m",strtotime($dr["gpsdate"])));
					$stat[$chartCounter]=$dr["total"];
					$max=($dr["total"]>$max?$dr["total"]:$max);
					$statTotal=$statTotal+$dr["total"];
					$g1=$g1."[".$chartCounter.",".$stat[$chartCounter]."],";
					$tickName=$tickName."[".$chartCounter.",'".date("d-m-y",strtotime($dr["gpsdate"])) ."'],";
				}
			}
					
			$max1=floor($max/4);
			$max2=floor($max/2);
			
			//Combo boxes
			if($auth->UserType == "Administrator") { // Select network combo
				$resultNetworks=$db->sql_query('SELECT * FROM municipalities');
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";				
				echo "<option value='index.php?com=stats&item=3'>Όλοι οι δήμοι</option>";
				while ($drn = $db->sql_fetchrow($resultNetworks)){
					echo "<option value='index.php?com=stats&item=3&uid=".$drn["municipality_id"]."'".((isset($_GET['uid'])&&$_GET['uid']==$drn['municipality_id'])?" selected":"").">".$drn['name']."</option>";
				}
				echo "</select>";
			}
			
			if($auth->UserType != "Administrator") { //Select device combo
				$query="SELECT imei, plate FROM vehicles WHERE municipality_id=".$auth->UserRow['municipality_id']."";
			} else {
				$filter=(isset($_GET['uid'])?' AND t2.municipality_id='.$_GET['uid']:'');
				$query="SELECT t1.imei, t1.plate, t2.name AS municipalityName FROM vehicles t1 INNER JOIN municipalities t2 ON t1.municipality_id=t2.municipality_id WHERE 1=1 ".$filter;
			}
			
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";
				echo "<option value='index.php?com=stats&item=3'>Όλα τα οχήματα</option>";
					while ($dr = $db->sql_fetchrow($result))
					{
						$uid=((isset($_GET['uid']) && intval($_GET['uid'])>0)?'&uid='.$_GET['uid']:'');
						echo "<option value='index.php?com=stats&item=3".$uid."&imei=".$dr["imei"]."'".((isset($_GET['imei'])&&$_GET['imei']==$dr['imei'])?" selected":"").">".$dr['municipalityName'].'-'.$dr['plate']."</option>";
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
										<?=$statTotal." / "?> <span><i class="icon-circle-arrow-up"></i></span>
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
                            <?=$config["navigation"]." (".lastMonth.")"?>
                        </h3>
                    </div>
                    <div class="box-content nopadding">
                        <table class="table table-hover table-nomargin  dataTable table-bordered">
                            <thead>
								<? 	echo "<tr><td>Σύνολο</td><td>".$statTotal."</td></tr>";?>
                                <tr>
                                    <th><?=statDate?></th>
                                    <th>Ζυγίσεις</th>
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
									echo "<td>".(isset($stat[$i]) ? number_format($stat[$i],0):'')."</td>";
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
			if($auth->UserType != "Administrator") {
				$query="SELECT sum(weight) AS total, gpsdate FROM data WHERE gpsID IN (SELECT imei FROM vehicles WHERE municipality_id=".$auth->UserRow['municipality_id'].") ".(isset($_GET['imei'])?" AND gpsID='".$_GET['imei']."'":"")." GROUP BY date(gpsdate) ORDER BY gpsdate DESC";
			} else {
				$query="SELECT sum(weight) AS total, gpsdate FROM data WHERE gpsID IN (SELECT imei FROM vehicles ".(isset($_GET['uid'])?' WHERE municipality_id='.$_GET['uid']:'').") ".(isset($_GET['imei'])?" AND gpsID='".$_GET['imei']."'":"")." GROUP BY date(gpsdate) ORDER BY gpsdate DESC";	//user_id=".$auth->UserId.") 
			}
			
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				$chartCounter=0;
				$max=0;
				while ($dr = $db->sql_fetchrow($result))
				{
					$chartCounter++;
					$string = $dr["gpsdate"];
					//$date = DateTime::createFromFormat("Y-m-d", $string);
					$date=date("Y-m-d",strtotime($dr["gpsdate"]));
					$detailsday[$chartCounter]=date("Y-m-d",strtotime($dr["gpsdate"]));
					//$num=intval($date->format("d"));
					$num=intval(date("d",strtotime($dr["gpsdate"])));
					$stat[$chartCounter]=$dr["total"];
					$max=($dr["total"]>$max?$dr["total"]:$max);
					$statTotal=$statTotal+$dr["total"];
					$g1=$g1."[".$chartCounter.",".$stat[$chartCounter]."],";
					if($db->sql_numrows($result) > 10) {
						$tickName=$tickName."[".$chartCounter.",'".date("d",strtotime($dr["gpsdate"])) ."'],";
					} else {
						$tickName=$tickName."[".$chartCounter.",'".date("d-m-y",strtotime($dr["gpsdate"])) ."'],";
					}
				}
				$max1=floor($max/4);
				$max2=floor($max/2);
			}
				
			//Combo boxes
			if($auth->UserType == "Administrator") { // Select network combo
				$resultNetworks=$db->sql_query('SELECT * FROM municipalities');
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";				
				echo "<option value='index.php?com=stats&item=4'>Όλοι οι δήμοι</option>";
				while ($drn = $db->sql_fetchrow($resultNetworks)){
					echo "<option value='index.php?com=stats&item=4&uid=".$drn["municipality_id"]."'".((isset($_GET['uid'])&&$_GET['uid']==$drn['municipality_id'])?" selected":"").">".$drn['name']."</option>";
				}
				echo "</select>";
			}
			
			if($auth->UserType != "Administrator") { //Select device combo
				$query="SELECT imei, plate FROM vehicles WHERE municipality_id=".$auth->UserRow['municipality_id']."";
			} else {
				$filter=(isset($_GET['uid'])?' AND t2.municipality_id='.$_GET['uid']:'');
				$query="SELECT t1.imei, t1.plate, t2.name AS municipalityName FROM vehicles t1 INNER JOIN municipalities t2 ON t1.municipality_id=t2.municipality_id WHERE 1=1 ".$filter;
			}
			
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";
				echo "<option value='index.php?com=stats&item=4'>Όλα τα οχήματα</option>";
					while ($dr = $db->sql_fetchrow($result))
					{
						$uid=((isset($_GET['uid']) && intval($_GET['uid'])>0)?'&uid='.$_GET['uid']:'');
						echo "<option value='index.php?com=stats&item=4".$uid."&imei=".$dr["imei"]."'".((isset($_GET['imei'])&&$_GET['imei']==$dr['imei'])?" selected":"").">".$dr['municipalityName'].'-'.$dr['plate']."</option>";
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
										<?=$statTotal." / "?> <span><i class="icon-circle-arrow-up"></i></span>
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
                            <?=$config["navigation"]." (".fromTheBegenning.")"?>
                        </h3>
                    </div>
                    <div class="box-content nopadding">
                        <table class="table table-hover table-nomargin  dataTable table-bordered">
                            <thead>
								<? 	echo "<tr><td>Σύνολο</td><td>".$statTotal."</td></tr>";?>
                                <tr>
                                    <th><?=statDate?></th>
                                    <th>Ζυγίσεις</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                            for ($i = 1; $i <=$chartCounter; $i++) {
								$myDay=intval(date("d",strtotime($Date. ' - '.$i.' days')));
								if ($stat[$i]>0) {
									echo"<tr>";
									echo "<td><a href='index.php?com=stats&item=6&detailsDay=".$detailsday[$i]."'>".$detailsday[$i]."</a></td>";
									echo "<td>".(isset($stat[$i]) ? number_format($stat[$i],0):'')."</td>";
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
					$query="SELECT sum(weight) AS total, gpsdate FROM data WHERE (gpsdate)>(CURDATE()- INTERVAL 7 DAY) AND gpsID IN (SELECT imei FROM vehicles WHERE municipality_id=".$auth->UserRow['municipality_id'].") ".(isset($_GET['imei'])?" AND gpsID='".$_GET['imei']."'":"")." GROUP BY date(gpsdate) ORDER BY gpsdate DESC";
	
				} else {
					$query="SELECT sum(weight) AS total, gpsdate FROM data WHERE gpsdate>='".$_REQUEST['fromdate']."' AND gpsdate<='".$_REQUEST['todate']. "' AND gpsID IN (SELECT imei FROM vehicles WHERE municipality_id=".$auth->UserId.") ".(isset($_GET['imei'])?" AND gpsID='".$_GET['imei']."'":"")." GROUP BY date(gpsdate) ORDER BY gpsdate DESC";
				}
			} else {
				if (!isset($_REQUEST['fromdate']) || !isset($_REQUEST['todate'])) {
					$query="SELECT sum(weight) AS total, gpsdate FROM data WHERE (gpsdate)>(CURDATE()- INTERVAL 7 DAY) AND gpsID IN (SELECT imei FROM vehicles ".(isset($_GET['uid'])?' WHERE municipality_id='.$_GET['uid']:'').") ".(isset($_GET['imei'])?" AND gpsID='".$_GET['imei']."'":"")." GROUP BY date(gpsdate) ORDER BY gpsdate DESC";
	
				} else {
					$query="SELECT sum(weight) AS total, gpsdate FROM data WHERE gpsdate>='".$_REQUEST['fromdate']."' AND gpsdate<='".$_REQUEST['todate']. "' AND gpsID IN (SELECT imei FROM vehicles".(isset($_GET['uid'])?' WHERE municipality_id='.$_GET['uid']:'').") ".(isset($_GET['imei'])?" AND gpsID='".$_GET['imei']."'":"")." GROUP BY date(gpsdate) ORDER BY gpsdate DESC";
				}
			}
			
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				$statTotal=0;
				$chartCounter=0;
				$max=0;
				while ($dr = $db->sql_fetchrow($result))
				{
					$chartCounter++;
					$string = $dr["gpsdate"];
					//$date = DateTime::createFromFormat("Y-m-d", $string);
					$date=date("Y-m-d",strtotime($dr["gpsdate"]));
					$detailsday[$chartCounter]=date("Y-m-d",strtotime($dr["gpsdate"]));
					//$num=intval($date->format("d"));
					$num=intval(date("d",strtotime($dr["gpsdate"])));
					$stat[$chartCounter]=$dr["total"];
					$max=($dr["total"]>$max?$dr["total"]:$max);
					$g1=$g1."[".$chartCounter.",".$stat[$chartCounter]."],";
					$statTotal=$statTotal+$dr["total"];
					if($db->sql_numrows($result) > 10) {
						$tickName=$tickName."[".$chartCounter.",'".date("d",strtotime($dr["gpsdate"])) ."'],";
					} else {
						$tickName=$tickName."[".$chartCounter.",'".date("d-m-y",strtotime($dr["gpsdate"])) ."'],";
					}
				}
			}
			$max1=floor($max/4);
			$max2=floor($max/2);	
		
			//Combo boxes
			if($auth->UserType == "Administrator") { // Select network combo
				$resultNetworks=$db->sql_query('SELECT * FROM municipalities');
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";				
				echo "<option value='index.php?com=stats&item=5'>Όλοι οι δήμοι</option>";
				while ($drn = $db->sql_fetchrow($resultNetworks)){
					echo "<option value='index.php?com=stats&item=5&uid=".$drn["municipality_id"]."'".((isset($_GET['uid'])&&$_GET['uid']==$drn['municipality_id'])?" selected":"").">".$drn['name']."</option>";
				}
				echo "</select>";
			}
			
			if($auth->UserType != "Administrator") { //Select device combo
				$query="SELECT imei, plate FROM vehicles WHERE municipality_id=".$auth->UserRow['municipality_id']."";
			} else {
				$filter=(isset($_GET['uid'])?' AND t2.municipality_id='.$_GET['uid']:'');
				$query="SELECT t1.imei, t1.plate, t2.name AS municipalityName FROM vehicles t1 INNER JOIN municipalities t2 ON t1.municipality_id=t2.municipality_id WHERE 1=1 ".$filter;
			}
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";
				echo "<option value='index.php?com=stats&item=5'>Όλα τα οχήματα</option>";
					while ($dr = $db->sql_fetchrow($result))
					{
						$uid=((isset($_GET['uid']) && intval($_GET['uid'])>0)?'&uid='.$_GET['uid']:'');
						echo "<option value='index.php?com=stats&item=5".$uid."&imei=".$dr["imei"]."'".((isset($_GET['imei'])&&$_GET['imei']==$dr['imei'])?" selected":"").">".$dr['municipalityName'].'-'.$dr['plate']."</option>";
					}
				echo "</select>";
			}
        ?>
		<div class="row-fluid">
			<div class="span3">
				<div class="control-group">
					<label for="textfield" class="control-label">Από ημ/νία</label>
					<div class="controls">
						<input type="text" name="fromdate" id="fromdate" class="input-medium datepick" data-date-format="yyyy-mm-dd" value="<?=(isset($_REQUEST["fromdate"]) ? $_REQUEST["fromdate"] : date('Y-m-d', strtotime(' -7 day')) )?>">
					</div>
				</div>
			</div>
			<div class="span3">
				<div class="control-group">
					<label for="textfield" class="control-label">Εως ημ/νία</label>
					<div class="controls">
						<input type="text" name="todate" id="todate" class="input-medium datepick" data-date-format="yyyy-mm-dd" value="<?=(isset($_REQUEST["todate"]) ? $_REQUEST["todate"] : date('Y-m-d') )?>">
					</div>
				</div>
			</div>
			<div class="span6">
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<input type="hidden" value="9" name="item">
				<input type="submit" value="Αναζήτηση" class="button" name="search" id="search">
			</div>
		</div>
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
										<?=$statTotal." / "?> <span><i class="icon-circle-arrow-up"></i></span>
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
                             <?=$config["navigation"]." (".fromTo.")"?>
                        </h3>
                    </div>
                    <div class="box-content nopadding">
                        <table class="table table-hover table-nomargin  table-bordered">
                            <thead>
								<? 	echo "<tr><td>Σύνολο</td><td>".$statTotal."</td></tr>";?>
                                <tr>
                                    <th><?=statDate?></th>
                                    <th>Ζυγίσεις</th>
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
									echo "<td>".(isset($stat[$i]) ? number_format($stat[$i],0):'')."</td>";
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
			$filter=($auth->UserType != "Administrator"?'WHERE municipality_id='.$auth->UserRow['municipality_id']:'');
			//$filter='';
			$query="SELECT sum(`weight`) AS total,hour(gpsdate) AS hour
			FROM data WHERE date(gpsdate)='".$_GET["detailsDay"]."' AND gpsID IN (SELECT imei FROM vehicles ".$filter." ) ".(isset($_GET['imei'])?" AND gpsID='".$_GET['imei']."'":"") ." GROUP BY hour(gpsdate)";
			//$result = $db->RowSelectorQuery($query);
			$total=0;
			$result = $db->sql_query($query);
			
			if($db->sql_numrows($result) > 0) {

				while ($dr = $db->sql_fetchrow($result)){
					$i=intval($dr['hour']);
					$stat[$i]=$dr['total'];
					$total=$total+intval($dr['total']);
					$tickName=$tickName."[".$i.",'".$i ."'],";
					$g1=$g1."[".$i.",".($stat[$i]>0?$stat[$i]:0)."],";
					$max=($stat[$i]>$max?$stat[$i]:$max);	
				}
			}	
			
			/*
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
			*/
			$max1=floor($max/4);
			$max2=floor($max/2);
			
			if($auth->UserType != "Administrator") { //Select device combo
				$query="SELECT imei, plate FROM vehicles WHERE municipality_id=".$auth->UserRow['municipality_id']."";
			} else {
				$filter=(isset($_GET['uid'])?' AND t2.municipality_id='.$_GET['uid']:'');
				$query="SELECT t1.imei, t1.plate, t2.name AS municipalityName FROM vehicles t1 INNER JOIN municipalities t2 ON t1.municipality_id=t2.municipality_id WHERE 1=1 ".$filter;
			}
			$result = $db->sql_query($query);
			if($db->sql_numrows($result) > 0) {
				echo "<select onchange='this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);'>";
				echo "<option value='index.php?com=stats&item=5'>Όλα τα οχήματα</option>";
					while ($dr = $db->sql_fetchrow($result))
					{
						$uid=((isset($_GET['uid']) && intval($_GET['uid'])>0)?'&uid='.$_GET['uid']:'');
						echo "<option value='index.php?com=stats&item=5".$uid."&imei=".$dr["imei"]."'".((isset($_GET['imei'])&&$_GET['imei']==$dr['imei'])?" selected":"").">".$dr['municipalityName'].'-'.$dr['plate']."</option>";
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
										<?=$total." "?> <span><i class="icon-circle-arrow-up"></i></span>
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
								<? 	echo "<tr><td>Σύνολο</td><td>".$total."</td></tr>";?>
                                <tr>
                                    <th><?=graphTime?></th>
                                    <th>Ζυγίσεις</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                            <?
                            for ($i = 1; $i <= 24; $i++) {
								if ($result["c".$i]>0) {
									echo"<tr>";
									echo "<td>".($i<=10?"0".($i-1):$i-1).":00-".($i<10?"0".$i:$i).":00</td>";
									echo "<td>".(isset($result["c".$i])?$result["c".$i]:'')."</td>";
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