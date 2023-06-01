<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
//require_once(dirname(__FILE__) . "/common.php");

//for($i=1; $i<=((sizeof($_POST)-9)/8);$i++){
//	echo $i.' ' .$_POST['type_'.$i].' '.$_POST['am_'.$i].' '.$_POST['quantity_'.$i].' '.$_POST['code_'.$i].' '.$_POST['details_'.$i].' '.$_POST['point_'.$i].' '.$_POST['disposition_'.$i].' '.$_POST['retrieval_'.$i].'<br>';
//}

//exit;

if($auth->UserType != "Administrator" ) Redirect('index.php');
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

global $nav;
$nav = 'Εισαγωγή XLS';
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=xls";
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
			$PrimaryKeys["xls_id"] = intval($_GET["item"]);
			$QuotFields["xls_id"] = true;
		} else {
			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		}
		
		$Collector["period_id"] = $_SESSION['defaultperiod_id'];
		$QuotFields["period_id"] = true;
		
		$Collector["title"] = $_POST["title"];
		$QuotFields["title"] = true;		
		
		$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
		$QuotFields["description"] = true;
		
		$Collector["year"] = $_POST["year"];
		$QuotFields["year"] = true;
		
		$Collector["organization_name"] = $_POST["organization_name"];
		$QuotFields["organization_name"] = true;
		
		$Collector["vat"] = $_POST["vat"];
		$QuotFields["vat"] = true;

		$Collector["registration_number"] = $_POST["registration_number"];
		$QuotFields["registration_number"] = true;
		
		$Collector["municipality_id"] = $_POST["municipality_id"];
		$QuotFields["municipality_id"] = true;
		
		$db->ExecuteUpdater("xls",$PrimaryKeys,$Collector,$QuotFields);

		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		for($i=1; $i<=((sizeof($_POST)-9)/8);$i++){
			$Collector["xls_id"] = $_POST['xls_id'];
			$QuotFields["xls_id"] = true;
			
			$Collector["type"] = $_POST['type_'.$i];
			$QuotFields["type"] = true;
			
			$Collector["am"] = $_POST['am_'.$i];
			$QuotFields["am"] = true;		
			
			$Collector["quantity"] = $_POST['quantity_'.$i];
			$QuotFields["quantity"] = true;
			
			$Collector["code"] = $_POST['code_'.$i];
			$QuotFields["code"] = true;
			
			$Collector["details"] = $_POST['details_'.$i];
			$QuotFields["details"] = true;
			
			$Collector["point"] = $_POST['point_'.$i];
			$QuotFields["point"] = true;
			
			$Collector["disposition"] = $_POST['disposition_'.$i];
			$QuotFields["disposition"] = true;
			
			$Collector["retrieval"] = $_POST['retrieval_'.$i];
			$QuotFields["retrieval"] = true;

			$Collector["sed"] =($_POST['sed_'.$i]=="on" ? "True" : "False");
			$QuotFields["sed"] = true;
			
			$Collector["calc"] = ($_POST['calc_'.$i]=="on" ? "True" : "False");
			$QuotFields["calc"] = true;
		
			
			$db->ExecuteUpdater("xlsrows",$PrimaryKeys,$Collector,$QuotFields);
		}	
		$messages->addMessage("SAVED!!!");
		Redirect($BaseUrl);
	} else if($_REQUEST["Command"] ==  "DELETE") {
		if($item != "")
		{
			//Θα πρέπει να γίνει έλεγχος αν μπορει να γίνει διαγραφή
			//Αν υπάρχουν εγγραφές στα αγορασμένα πακέτα ή στα rates δεν θα επιτρέπεται
			//$checkDelete=$db->sql_query("SELECT * FROM xls WHERE parent=".$item." OR role=".$item);
			//if ($db->sql_numrows($checkDelete)>0) {
			//	$messages->addMessage(errorRecordsFound);
			//	Redirect($BaseUrl);				
			//}
			$db->sql_query("DELETE FROM xls WHERE xls_id=" . $item);
			$messages->addMessage("DELETED!!!");
			Redirect($BaseUrl);
		}
	}
}

if(isset($_GET["item"])) {
	if(intval($_GET['item']>0)){
		$query="SELECT * FROM xls WHERE 1=1 AND xls_id=".$_GET['item'];
		$dr_e = $db->RowSelectorQuery($query);
		if (!isset($dr_e["xls_id"])) {
			$messages->addMessage("NOT FOUND!!!");
			Redirect("index.php?com=xls");
		}
	}
	$dr_next = $db->RowSelectorQuery("SELECT max(xls_id) AS nextID FROM xls");
	$nextID=intval($dr_next['nextID'])+1;
	echo 'nextID:'.$nextID;
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
					<div class="controls">
						<label for="xls_id" class="control-label">Αρχείο</label>
						<input type="text" readonly name="xls_id" value="<?=$nextID?>" id="xls_id"  >
					</div>
				</div>
				<div class="control-group">
					<label for="textfield" class="control-label">Δήμος</label>
					<div class="controls">
						<select name="municipality_id" id="municipality_id" class='select2-me input-xlarge' required>
						<option value="">Επιλογή δήμου</option>'
						<?
							$resultMunicipalities = $db->sql_query("SELECT * FROM municipalities WHERE 1=1 ".$filter." ORDER BY name ");
							while ($drMunicipalities = $db->sql_fetchrow($resultMunicipalities)){
								echo '<option value="'.$drMunicipalities['municipality_id'].'" '.($drMunicipalities['municipality_id']==$dr_e['municipality_id']?' selected':'').'>'.$drMunicipalities['name'].'</option>';
							}
						?>
						</select>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<label for="title" class="control-label">Τίτλος</label>
						<input type="text" name="title" id="title" required class="input-xxlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["title"]) ? 'value="'.$dr_e['title'].'"' : "")?> >
					</div>
				</div>
				<div class="control-group">
					<label for="textarea" class="control-label">Περιγραφή</label>
					<div class="controls">
						<textarea name="description" rows="10" id="description" class="input-block-level"><?=(isset($dr_e["description"]) ? $dr_e["description"] : "")?></textarea>
					</div>
				</div>
	
				<div class="controls">
					<input name="file" id="file"  style="visibility:none;" type="file">
					<label for="file"><span> Upload your xls file</span></label>
					<input type="submit" value="Upload" id="upload" class="submit" />
					<span class="help-block"></span>
				</div>
			</div>
			<h4 id='loading' style="display:none;">loading..</h4>
			<div id="message"></div>
		</div>
			<a href="#" onClick="checkFields();"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
			<a href="index.php?com=roles"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
    </div>
<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#title').val();
		if ( value.length >= 3){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
		} 
		//else {
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
							<th>Τίτλος</th>
							<th>Ενέργεια</th>
						</tr>
					</thead>
					<tbody> 
					<?	
						$query = "SELECT * FROM xls WHERE 1=1  ORDER BY title ";
                        $result = $db->sql_query($query);
                        $counter = 0;
                        while ($dr = $db->sql_fetchrow($result))
                        {
                            ?>
                                <tr>
                                    <td><?=$dr["title"]?></td>
                                    <td>
                                        <a style="padding:4px"  href="index.php?com=xls&Command=edit&item=<?=$dr["xls_id"]?>"><i class="icon-edit"></i> <?=edit?></a>
                                        <a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=xls&Command=DELETE&item=<?=$dr["xls_id"]?>');"><span><i class="icon-trash"></i><?=delete?></span></a>
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
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        //CKEDITOR.replace( 'description' );
    </script>