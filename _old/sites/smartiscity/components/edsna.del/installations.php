<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

	if($auth->UserType != "Administrator") {  
			Redirect("index.php");
	}

unset ($_SESSION["installation_id"]);
global $nav;
$nav = 'Εγκαταστάσεις';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=installations";
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
			$PrimaryKeys["installation_id"] = intval($_GET["item"]);
			$QuotFields["installation_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}
		
		$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
		$QuotFields["is_valid"] = true;
		
		$Collector["name"] = $_POST["name"];
		$QuotFields["name"] = true;
		
		$Collector["capacity"] = $_POST["capacity"];
		$QuotFields["capacity"] = true;
		
		$Collector["installationtype_id"] = $_POST["installationtype_id"];
		$QuotFields["installationtype_id"] = true;
		
		$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
		$QuotFields["description"] = true;
		

		
		$db->ExecuteUpdater("installations",$PrimaryKeys,$Collector,$QuotFields);		
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") { 
		if($item != ""){
			//SOS Έλεγχο πριν τη διαγραφή
			$db->sql_query("DELETE FROM installations WHERE installation_id=" . $item);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}


if(isset($_GET["item"])) {
	$query="SELECT * FROM installations WHERE installation_id=".$_GET['item'];
	$dr_e = $db->RowSelectorQuery($query);
	if (!isset($dr_e["installation_id"]) && intval($_GET['item'])>0) {
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=installations");
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
				<div class="check-line">
					<label class="inline" for="is_valid"><?=active?></label>
					<div class="controls">
						<input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>  />
					</div>
				</div>
				<div class="check-line">
					<label class="inline" for="discount">Υπολογισμός έκπτωσης περιβαλλοντικής εισφοράς</label>
					<div class="controls">
						<input id="discount" name="discount" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["discount"]) && $dr_e["discount"]=='True') ? 'checked':'')?>  />
					</div>
				</div>

				<div class="control-group">
					<label for="textfield" class="control-label">Τύπος εγκατάστασης</label>
					<div class="controls">
						<select name="municipality_id" id="municipality_id" class='select2-me input-xlarge' required>
						<option value="">Επιλογή τύπου εγκατάστασης</option>'
						<?
							$resultInstallationtypes = $db->sql_query("SELECT * FROM installationtypes WHERE 1=1 ".$filter." ORDER BY name ");
							while ($drInstallationtypes = $db->sql_fetchrow($resultInstallationtypes)){
								echo '<option value="'.$drInstallationtypes['municipality_id'].'">'.$drInstallationtypes['name'].'</option>';
							}
						?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<label for="name" class="control-label">Ονομασία</label>
						<input type="text" name="name" id="name" name class="input-xxlarge valid"<?=(isset($dr_e["name"]) ? 'value="'.$dr_e['name'].'"' : "")?> >
					</div>
				</div>
				
				<div class="control-group">
					<div class="controls">
						<label for="capacity" class="control-label">Δυναμικότητα</label>
						<input type="text" name="capacity" id="capacity" name class="input-xxlarge valid"<?=(isset($dr_e["capacity"]) ? 'value="'.$dr_e['capacity'].'"' : "")?> >
					</div>
				</div>
				<div class="control-group">
					<label for="textarea" class="control-label">Περιγραφή</label>
					<div class="controls">
						<textarea name="remark" rows="10" id="description" class="input-block-level"><?=(isset($dr_l1["description"]) ? $dr_l1["description"] : "")?></textarea>
					</div>
				</div>
				
			</div>
		</div>
			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary">Αποθήκευση</button></a>
			<a href="index.php?com=installations"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
    </div>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#name').val();
		if ( value.length >= 2){
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
							<th>Κατάσταση</th>
							<th>Ονομασία</th>
							<th>Δυναμικότητα</th>
							<th>Τύπος</th>
							<th>Ενέργεια</th>
						</tr>
					</thead>
					<tbody> 
					<?	
						$query = "SELECT t1.*,t2.name AS typename FROM installations t1 LEFT JOIN installationtypes t2 ON t1.installationtype_id=t2.installationtype_id WHERE 1=1 ".$filter." ORDER BY name ";
                        $result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
									<td><?=$dr["installation_id"]?></td>
									<td><?=$dr["is_valid"]?></td>
                                    <td><?=$dr["name"]?></td>
									<td><?=$dr["capacity"]?></td>
									<td><?=$dr["typename"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=installations&Command=edit&item=<?=$dr["installation_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a style="padding:4px"  href="index.php?com=installationcondition&installation_id=<?=$dr["installation_id"]?>"><i class="icon-edit"></i> Κατάσταση</a>
										<a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=installations&Command=DELETE&item=<?=$dr["installation_id"]?>');"><span><i class="icon-trash"></i><?=delete?></a></span></a>
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
