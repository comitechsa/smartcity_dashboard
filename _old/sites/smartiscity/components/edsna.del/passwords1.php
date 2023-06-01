<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
$config["navigation"] = "Πρόσβαση";

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

//$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");

if(isset($_POST["Command"]) )
{
	if($_POST["Command"] == "SAVE")
	{
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		//TODO: check login user rights

		
		//if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
		//{
			$PrimaryKeys["id"] = $_POST["id"];
			$QuotFields["id"] = true;
		//}
		$Collector["user_id"] = $auth->UserId;
		$QuotFields["user_id"] = true;
		
		$Collector["room"] = $_POST["room"];
		$QuotFields["room"] = true;
		
		$Collector["password"] = $_POST["password"];
		$QuotFields["password"] = true;
		
		$Collector["expire"] = $_POST["expire"];
		$QuotFields["expire"] = true;
		
		$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
		$QuotFields["is_valid"] = true;
		
		$db->ExecuteUpdater("passwords",$PrimaryKeys,$Collector,$QuotFields);
		$messages->addMessage("SAVED!!! ".$_POST["expire"]);
		Redirect("index.php?com=passwords");
	}
	else if($_POST["Command"] == "DELETE")
	{
		if($_POST["id"] != "")
		{
			$db->sql_query("DELETE FROM passwords WHERE id=" . $_POST["id"]." AND user_id=".$auth->UserId);
			$messages->addMessage("DELETED!!!");
			Redirect("index.php?com=passwords");
		}
	}
}
?>
		<?
		$dr_tmp = $db->RowSelectorQuery("SELECT * FROM passwords WHERE user_id=".$auth->UserId." LIMIT 1");
		if (isset($dr_tmp["id"])) {
			//$messages->addMessage("NOT FOUND!!!");
			//Redirect("index.php?com=passwords");
			//} else {
			$item = $dr_tmp["id"];
		}
       if(isset($item))
        {
			$query="SELECT * FROM passwords WHERE user_id=".$auth->UserId." AND id=".$item;
            $dr_e = $db->RowSelectorQuery($query);
			//if (!isset($dr_e["id"])) {
			//	$messages->addMessage("NOT FOUND!!!");
			//	Redirect("index.php?com=passwords");
		}
        ?>
        <div class="row-fluid">
            <div class="span12">
                <div class="box">
                    <div class="box-content">
                            <!-- <div class="control-group">
                                <label for="textfield" class="control-label">Χώρος</label>
                                <div class="controls">
                                    <input type="text" name="room" id="room" class="input-xxlarge" value="<? //=(isset($dr_e["room"]) ? $dr_e["room"] : "")?>">
                                </div>
                            </div> -->

                            <div class="control-group">
                                <label for="textfield" class="control-label">Κωδικός</label>
                                <div class="controls">
									<input type="hidden" name="room" id="room" class="input-xxlarge" value="main">
									<input type="hidden" name="id" id="id" class="input-xxlarge" value="<?=(isset($item) ? $item : "")?>">
                                    <input type="text" name="password" id="mac" class="input-xxlarge" value="<?=(isset($dr_e["password"]) ? $dr_e["password"] : "")?>">
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="textfield" class="control-label">Λήξη</label>
                                <div class="controls">
                                    <input type="text" name="expire" id="expire" class="input-medium datepick" data-date-format="yyyy-mm-dd" value="<?=(isset($dr_e["expire"]) ? $dr_e["expire"] : date('Y-m-d'))?>">
                                </div>
                            </div>    
                            <!--<div class="control-group">-->
                            <div class="check-line">
								<label class="inline" for="is_valid">Ενεργό</label>
                                <div class="controls">
                                    <input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>
                            <a href="#" onClick="cm('SAVE',1,0,'');">   <button type="button" class="btn btn-primary">Αποθήκευση</button></a>
							<a href="index.php"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
                            
                            <!-- <a href="#" onClick="cm('SAVE',1,0,'');">   <button type="button" class="btn btn-primary">Αποθήκευση</button></a></li> -->
                            <!--<a href="#" onClick="cm('DELETE',1,0,'');">   <button type="button" class="btn btn-primary">Διαγραφή</button></a></li>-->
                    </div>
                </div>
            </div>
        </div>
        <?
        //}
       ?>
                            
			<script language="javascript">
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'remark' );
            </script>
                       <!-- </div>
                    </div>
                </div>
             </div> -->
