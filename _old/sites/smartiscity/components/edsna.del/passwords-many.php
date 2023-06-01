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

$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");

if(isset($_REQUEST["Operation"]) )
{
	if($_POST["Command"] == "SAVE")
	{
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		//TODO: check login user rights
		
		if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
		{
			$PrimaryKeys["id"] = intval($_GET["item"]);
			$QuotFields["id"] = true;
		}
		
		$Collector["user_id"] = $auth->UserId;
		$QuotFields["user_id"] = true;
		
		$Collector["room"] = $_POST["room"];
		$QuotFields["room"] = true;
		
		$Collector["password"] = $_POST["password"];
		$QuotFields["password"] = true;
		
		$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
		$QuotFields["is_valid"] = true;
		
		$Collector["expire"] = $_POST["expire"];;
		$QuotFields["expire"] = true;

		
		$db->ExecuteUpdater("passwords",$PrimaryKeys,$Collector,$QuotFields);
		$messages->addMessage("SAVED!!!");
		Redirect("index.php?com=passwords");		

	}
	else if($_REQUEST["Operation"] == "DELETE")
	{
		if($item != "")
		{
			$db->sql_query("DELETE FROM passwords WHERE id=" . $item." AND user_id=".$auth->UserId);
			$messages->addMessage("DELETED!!!");
			Redirect("index.php?com=passwords");
		}
	}
}

?>
		<?
        if(isset($_GET["item"]))
        {
			$query="SELECT * FROM passwords WHERE user_id=".$auth->UserId." AND id=".$_GET['item'];
            $dr_e = $db->RowSelectorQuery($query);
			if (!isset($dr_e["id"])) {
				$messages->addMessage("NOT FOUND!!!");
				Redirect("index.php?com=passwords");
			}
        ?>
        <div class="row-fluid">
            <div class="span12">
                <div class="box">
                    <div class="box-content">
                            <div class="control-group">
                                <label for="textfield" class="control-label">Χώρος</label>
                                <div class="controls">
                                    <input type="text" name="room" id="room" class="input-xxlarge" value="<?=(isset($dr_e["room"]) ? $dr_e["room"] : "")?>">
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="textfield" class="control-label">Κωδικός</label>
                                <div class="controls">
                                    <input type="text" name="password" id="mac" class="input-xxlarge" value="<?=(isset($dr_e["password"]) ? $dr_e["password"] : "")?>">
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="textfield" class="control-label">Λήξη</label>
                                <div class="controls">
                                    <input type="text" name="expire" id="expire" class="input-medium datepick" data-date-format="yyyy-dd-mm" value="<?=(isset($dr_e["todate"]) ? $dr_e["todate"] : date('Y-d-m'))?>">
                                </div>
                            </div>    
                            <div class="control-group">
								<label class="inline" for="is_valid">Ενεργό</label>
                                <div class="controls">
                                    <input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>  />
                                </div>
                            </div>
                            <a href="#" onClick="cm('SAVE',1,0,'');">   <button type="button" class="btn btn-primary">Αποθήκευση</button></a></li>
                    </div>
                </div>
            </div>
        </div>
        <?
        }
        else
        {
        ?>
    
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
                        <table class="table table-hover table-nomargin dataTable table-bordered">
                            <thead>
                                <tr>
                                    <th>Χώρος</th>
                                    <th>Κωδικός</th>
                                    <th class='hidden-480'>Λήξη</th>
                                    <th class='hidden-480'>Ενεργός</th>
                                    <th>Ενέργεια</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                    $result = $db->sql_query("select * FROM passwords WHERE user_id=".$auth->UserId);
                                    if($db->sql_numrows($result) > 0)
                                    {
                                        while ($dr = $db->sql_fetchrow($result))
                                        {
                                            ?>
                                             <tr>
                                                <td><?=$dr["room"]?></td>
                                                <td><?=$dr["password"]?></td>
                                                <td class='hidden-480'><?=$dr["expire"]?></td>
                                                <td class='hidden-480'><?=$dr["is_valid"]?></td>
                                                <td>
                                                    <!--<button class="btn btn-small btn-inverse"><i class="icon-trash"></i> Delete</button>-->
                                                    <a style="padding:4px"  href="index.php?com=passwords&Operation=EDIT&item=<?=$dr["id"]?>"><i class="icon-edit"></i> Επεξεργασία</a>
                                                    <a style="padding:4px"  href="index.php?com=passwords&Operation=DELETE&item=<?=$dr["id"]?>"><i class="icon-trash"></i> Διαγραφή</a>
                                                </td>
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
        <?
        }
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
