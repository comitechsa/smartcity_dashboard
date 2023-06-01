<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

	if($auth->UserType != "Administrator") {  
			Redirect("index.php");
	}

global $nav;
$nav = 'Συντελεστές';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=factors";
$command=array();
$command=explode("&",$_POST["Command"]);

if(isset($_REQUEST["Command"]))
{	
	if($_REQUEST["Command"] == "SAVE")
	{
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		
		if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
		{
			$PrimaryKeys["factor_id"] = intval($_GET["item"]);
			$QuotFields["factor_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}
		
		$Collector["period_id"] = $_POST["period_id"];
		$QuotFields["period_id"] = true;
		
		$Collector["factor1"] = $_POST["factor1"];
		$QuotFields["factor1"] = true;

		$Collector["factor2"] = $_POST["factor2"];
		$QuotFields["factor2"] = true;		
		
		$Collector["factor3"] = $_POST["factor3"];
		$QuotFields["factor3"] = true;

		$Collector["factor4"] = $_POST["factor4"];
		$QuotFields["factor4"] = true;		
		
		$Collector["factor5"] = $_POST["factor5"];
		$QuotFields["factor5"] = true;

		$Collector["factor6"] = $_POST["factor6"];
		$QuotFields["factor6"] = true;		
		
		$Collector["factor7"] = $_POST["factor7"];
		$QuotFields["factor7"] = true;

		$Collector["factor8"] = $_POST["factor8"];
		$QuotFields["factor8"] = true;		

		
		$db->ExecuteUpdater("factors",$PrimaryKeys,$Collector,$QuotFields);		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") { 
		if($item != ""){
			//SOS Έλεγχο πριν τη διαγραφή
			$db->sql_query("DELETE FROM factors WHERE factor_id=" . $item);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}


if(isset($_GET["item"])) {
	$query="SELECT * FROM factors WHERE factor_id=".$_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["factor_id"]) && intval($_GET['item'])>0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=factors");
	}
	?>
    <div class="breadcrumbs">
        <ul>
            <li> <a href="index.php"><?=homePage?></a><i class="icon-angle-right"></i></li>
            <li><a href="<?=$BaseUrl?>"><?=$nav?></a></li>
        </ul>
    </div>
    
	<div class="row-fluid"> 
		<div class="span12">            
			<div class="box-title">
				<h3><i class="icon-user"></i><?=edit?></h3>
			</div>
			<div class="box-content nopadding">
				<div class="controls">
					<label for="period_id" class="control-label">Περίοδος</label>
					<?
						$query = "SELECT * FROM periods WHERE 1=1 ".$filter." ORDER BY year";
						echo Select::GetDbRender("period_id", $query, "period_id", "year", (isset($dr_e["period_id"]) ? $dr_e["period_id"] : ""), true);
					 ?>
				</div>
				<div class="control-group">
					<div class="controls">
						<label for="factor1" class="control-label">Συντελεστής 1</label>
						<input type="text" name="factor1" id="factor1" name class="input-xxlarge valid"<?=(isset($dr_e["factor1"]) ? 'value="'.$dr_e['factor1'].'"' : "")?> >
					</div>
				</div>	
				<div class="control-group">
					<div class="controls">
						<label for="factor1" class="control-label">Συντελεστής 2</label>
						<input type="text" name="factor2" id="factor2" name class="input-xxlarge valid"<?=(isset($dr_e["factor2"]) ? 'value="'.$dr_e['factor2'].'"' : "")?> >
					</div>
				</div>	
				<div class="control-group">
					<div class="controls">
						<label for="factor3" class="control-label">Συντελεστής 3</label>
						<input type="text" name="factor3" id="factor3" name class="input-xxlarge valid"<?=(isset($dr_e["factor3"]) ? 'value="'.$dr_e['factor3'].'"' : "")?> >
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<label for="factor4" class="control-label">Συντελεστής 4</label>
						<input type="text" name="factor4" id="factor4" name class="input-xxlarge valid"<?=(isset($dr_e["factor4"]) ? 'value="'.$dr_e['factor4'].'"' : "")?> >
					</div>
				</div>	
				<div class="control-group">
					<div class="controls">
						<label for="factor5" class="control-label">Συντελεστής 5</label>
						<input type="text" name="factor5" id="factor5" name class="input-xxlarge valid"<?=(isset($dr_e["factor5"]) ? 'value="'.$dr_e['factor5'].'"' : "")?> >
					</div>
				</div>	
				<div class="control-group">
					<div class="controls">
						<label for="factor6" class="control-label">Συντελεστής 6</label>
						<input type="text" name="factor6" id="factor6" name class="input-xxlarge valid"<?=(isset($dr_e["factor6"]) ? 'value="'.$dr_e['factor6'].'"' : "")?> >
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<label for="factor7" class="control-label">Συντελεστής 6</label>
						<input type="text" name="factor7" id="factor7" name class="input-xxlarge valid"<?=(isset($dr_e["factor7"]) ? 'value="'.$dr_e['factor7'].'"' : "")?> >
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<label for="factor8" class="control-label">Συντελεστής 6</label>
						<input type="text" name="factor8" id="factor8" name class="input-xxlarge valid"<?=(isset($dr_e["factor8"]) ? 'value="'.$dr_e['factor8'].'"' : "")?> >
					</div>
				</div>
			</div>
		</div>
			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary">Αποθήκευση</button></a>
			<a href="index.php?com=factors"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#period_id').val();
		if ( value.length > 0){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
		}
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
							<th>#</th>
							<th>Περίοδος</th>
							<th>Συντ.1</th>
							<th>Συντ.2</th>
							<th>Συντ.3</th>
							<th>Συντ.4</th>
							<th>Συντ.5</th>
							<th>Συντ.6</th>
							<th>Συντ.7</th>
							<th>Συντ.8</th>
							<th>Ενέργεια</th>
						</tr>
					</thead>
					<tbody> 
					<?	
						$query = "SELECT t1.*,t2.year FROM factors t1 INNER JOIN periods t2 ON t1.period_id=t2.period_id WHERE 1=1 ".$filter." ORDER BY period_id";
                        $result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
									<td><?=$dr["factor_id"]?></td>
                                    <td><?=$dr["year"]?></td>
									<td><?=$dr["factor1"]?></td>
									<td><?=$dr["factor2"]?></td>
									<td><?=$dr["factor3"]?></td>
									<td><?=$dr["factor4"]?></td>
									<td><?=$dr["factor5"]?></td>
									<td><?=$dr["factor6"]?></td>
									<td><?=$dr["factor7"]?></td>
									<td><?=$dr["factor8"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=factors&Command=edit&item=<?=$dr["factor_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=factors&Command=DELETE&item=<?=$dr["factor_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
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
