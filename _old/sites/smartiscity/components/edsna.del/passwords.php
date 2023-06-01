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

	if (!($permissions & $FLAG_PASSWORDS)) {
		$messages->addMessage("INVALID!!!");
		Redirect("index.php");
	}
//end permissions
	
	
$config["navigation"] = access;
$nav =access;	
$BaseUrl = "index.php?com=passwords";										

$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");

if($_POST["Command"] == "SAVE")
{	
	$PrimaryKeys = array();
	$Collector = array();
	$QuotFields = array();
	if(isset($_POST['recid']) && intval($_POST['recid'])>0) {

		$PrimaryKeys["id"] = intval($_POST["recid"]);
		$QuotFields["id"] = true;
	
		$Collector["is_valid"] = ($_POST["is_valid"]=="on" ? "True" : "False");
		$QuotFields["is_valid"] = true;
			
		$Collector["user_id"] = $auth->UserId;
		$QuotFields["user_id"] = true;
		
		$Collector["period"] = (isset($_POST["period"]) && intval($_POST["period"])>0?$_POST["period"]:'0');
		$QuotFields["period"] = true;
		
		$Collector["customerName"] = (isset($_POST["customerName"]) ?$_POST["customerName"]:'');
		$QuotFields["customerName"] = true;
		
		$Collector["room"] = (isset($_POST['room']) ? $_POST['room']:'0');
		$QuotFields["room"] = true;
		
		$Collector["max_logins"] = (isset($_POST['max_logins']) ? $_POST['max_logins']:'1');
		$QuotFields["max_logins"] = true;
		
		$Collector["checkin"] = (isset($_POST["checkin"]) ? $_POST["checkin"]:'');
		$QuotFields["checkin"] = true;

		$Collector["checkout"] = (isset($_POST["checkout"]) ? $_POST["checkout"]:'');
		$QuotFields["checkout"] = true;

		//$Collector["password"] = $_POST["password"];
		//$QuotFields["password"] = true;
		
		//$Collector["is_valid"] = "True"; //($_POST["is_valid"]=="on" ? "True" : "False");
		//$QuotFields["is_valid"] = true;
		
		//$Collector["expire"] = ""; //$_POST["expire"];
		//$QuotFields["expire"] = true;
		
		$db->ExecuteUpdater("passwords",$PrimaryKeys,$Collector,$QuotFields);
	} else {
		$PrimaryKeys = array();
		$Collector = array();
		$QuotFields = array();
		$result = $db->sql_query("SELECT * FROM passwords WHERE user_id=".$auth->UserId." AND is_valid='True' AND expire='' AND period=".$_POST['period']); //AND expire<CURDATE()

		if($db->sql_numrows($result) >= 100 || $_POST['qty']>100) {
			$messages->addMessage(tooManyRecords);
			Redirect("index.php?com=passwords");
		}

		$date_insert=date('Y-m-d H:i:s');
		for($i=0; $i<$_POST['qty']; $i++ ) {
			if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
			{
				$PrimaryKeys["id"] = intval($_GET["item"]);
				$QuotFields["id"] = true;
			}
			
			$Collector["user_id"] = $auth->UserId;
			$QuotFields["user_id"] = true;
			
			$Collector["period"] = $_POST["period"];
			$QuotFields["period"] = true;
			
			$Collector["room"] = (isset($_POST['room']) && intval($_POST['room'])>0?$_POST['room']:'');
			$QuotFields["room"] = true;
			
			//$check=0;
			//while($check<1) {
			//	$tempPass=randomCode(8);
			//	$resultCheck = $db->sql_query("SELECT * FROM passwords WHERE password=".$tempPass);
			//	if($db->sql_numrows($resultCheck) == 0) $check=1;
			//}
			
			$Collector["password"] = randomCode(8); //$_POST["password"];
			$QuotFields["password"] = true;
			
			$Collector["is_valid"] = "True"; //($_POST["is_valid"]=="on" ? "True" : "False");
			$QuotFields["is_valid"] = true;
			
			$Collector["expire"] = ""; //$_POST["expire"];
			$QuotFields["expire"] = true;
			
			$Collector["date_insert"] = $date_insert;
			$QuotFields["date_insert"] = true;
		
			$db->ExecuteUpdater("passwords",$PrimaryKeys,$Collector,$QuotFields);
		}		
	}
	$messages->addMessage("SAVED!!!");
	Redirect("index.php?com=passwords");

}
else if($_REQUEST["Command"] == "DELETE")
{

	if($item != "")
	{
		$drAccess=$db->RowSelectorQuery("SELECT *  FROM passwords WHERE id=" . $item." AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))");
		if(!isset($drAccess['id'])) {
			$messages->addMessage("ACCESS RESTRICTED!!!");
		} else {
			$db->sql_query("DELETE FROM passwords WHERE id=" . $item." AND (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))"); //user_id=".$auth->UserId		
			$messages->addMessage("DELETED!!!");			
		}
		Redirect("index.php?com=passwords");
	}
}

?>
	<?
    if(isset($_GET["item"]) && intval($_GET["item"])>0 && $_REQUEST['Command']=='edit')
    {
		//$query="SELECT * FROM passwords WHERE user_id=".$auth->UserId." AND id=".$_GET['item'];
		$query="SELECT * FROM passwords WHERE (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent']).")) AND id=".$_GET['item'];
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
						<div class="check-line">
							<label class="inline" for="is_valid"><?=active?></label>
							<div class="controls">
								<input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>  />
							</div>
						</div>
                        <div class="control-group">
                            <label for="period" class="control-label"><?=period?></label>
                            <div class="controls">
                                <input type="text" name="period" id="period" class="input-xxsmall" value="<?=(isset($dr_e["period"]) ? $dr_e["period"] : 1)?>">
                                <input type="hidden" name="recid" id="recid" value="<?=$_GET['item']?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="room" class="control-label"><?=room?></label>
                            <div class="controls">
                                <input type="text" name="room" id="room" class="input-xxsmall" value="<?=(isset($dr_e["room"]) ? $dr_e["room"] : '0')?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="max_logins" class="control-label">Max logins</label>
                            <div class="controls">
                                <input type="text" name="max_logins" id="max_logins" class="input-xxsmall" value="<?=(isset($dr_e["max_logins"]) ? $dr_e["max_logins"] : '1')?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="customerName" class="control-label"><?=fullName?></label>
                            <div class="controls">
                                <input type="text" name="customerName" id="customerName" class="input-xxsmall" value="<?=(isset($dr_e["customerName"]) ? $dr_e["customerName"] : "")?>">
                            </div>
                        </div>

						<div class="control-group">
							<label for="checkin" class="control-label">Checkin</label>
							<div class="controls">
								<input type="text" name="checkin" id="checkin" class="input-medium datepick" data-date-format="yyyy-mm-dd" value="<?=(isset($dr_e["checkin"]) ? $dr_e["checkin"] : '' )?>"> <!--date('Y-m-d')-->
							</div>
						</div>
						
						<div class="control-group">
							<label for="checkin" class="control-label">Checkout</label>
							<div class="controls">
								<input type="text" name="checkout" id="checkout" class="input-medium datepick" data-date-format="yyyy-mm-dd" value="<?=(isset($dr_e["checkout"]) ? $dr_e["checkout"] : '' )?>"> <!-- date('Y-m-d')-->
							</div>
						</div>
					</div>
				</div>
			</div>
			<a href="#" onClick="cm('SAVE',1,0,'');"><button id="submitBtn" type="button" class="btn btn-primary"><?=save?></button></a>
			<a href="index.php?com=passwords"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
		</div>
	<?
    } else if(isset($_GET["item"]))
    {
    ?>
    <div class="breadcrumbs">
        <ul>
            <li>
                <a href="index.php"><?=homePage?></a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="<?=$BaseUrl?>"><?=$nav?></a>
                <i class="icon-angle-right"></i>
            </li>
            <li>
                <a href="#"><?=edit?></a>
            </li>
        </ul>
    </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="box">
                    <div class="box-content">
						<div class="control-group">
							<label for="select" class="control-label"><?=durationInDays?></label>
                            <div class="controls">
                                <select name="period" id="period" class="input-large">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                </select>
                            </div>
						</div>
                        <div class="control-group">
                            <label for="textfield" class="control-label"><?=qty?></label>
                            <div class="controls">
                                <input type="text" name="qty" id="qty" class="input-xxsmall" value="1">
                            </div>
                        </div>
                        <!-- 
                        <div class="control-group">
                            <label class="inline" for="is_valid">Ενεργό</label>
                            <div class="controls">
                                <input id="is_valid" name="is_valid" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <? //=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>  />
                            </div>
                        </div>
                        <div class="control-group">
                            <label for="textfield" class="control-label">Χώρος</label>
                            <div class="controls">
                                <input type="text" name="room" id="room" class="input-xxlarge" value="<? //=(isset($dr_e["room"]) ? $dr_e["room"] : "")?>">
                            </div>
                        </div> 
                        <div class="control-group">
                            <label for="textfield" class="control-label">Κωδικός</label>
                            <div class="controls">
                                <input type="text" name="password" id="mac" class="input-xxlarge" value="<? //=(isset($dr_e["password"]) ? $dr_e["password"] : "")?>">
                            </div>
                        </div>

                        <div class="control-group">
                            <label for="textfield" class="control-label">Λήξη</label>
                            <div class="controls">
                                <input type="text" name="expire" id="expire" class="input-medium datepick" data-date-format="yyyy-dd-mm" value="<? //=(isset($dr_e["todate"]) ? $dr_e["todate"] : date('Y-d-m'))?>">
                            </div>
                        </div>-->
						<a href="#" onClick="cm('SAVE',1,0,'');">   <button type="button" class="btn btn-primary"><?=createPasswords?></button></a></li>
						<a href="index.php?com=passwords"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
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
						<table class="table table-hover table-nomargin dataTable dataTable-columnfilter table-bordered">
                            <thead>
                                <tr>
                                    <th><?=duration?></th>
                                    <th><?=password?></th>
                                    <th class='hidden-480'><?=expire?></th>
									<th>Expired</th>
                                    <th class='hidden-480'><?=active?></th>
                                    <th><?=room?></th>
                                    <th><?=fullname?></th>
									<th><?=insertDate?></th>
									<th><?=action?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                    $result = $db->sql_query("SELECT * FROM passwords WHERE (user_id=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])." OR user_id IN(SELECT user_id FROM users WHERE parent=".($auth->UserRow['parent']==0?$auth->UserRow['user_id']:$auth->UserRow['parent'])."))");
									
                                    if($db->sql_numrows($result) > 0)
                                    {
                                        while ($dr = $db->sql_fetchrow($result))
                                        {
                                            ?>
                                             <tr>
                                                <td><?=$dr["period"]?></td>
                                                <td><?=$dr["password"]?></td>
                                                <td class='hidden-480'><?=$dr["expire"]?></td>
												<td><?=(($dr["expire"]!='' && date("Y-m-d")>=$dr['expire'])?'True':'False')?></td>
                                                <td class='hidden-480'><?=$dr["is_valid"]?></td>
                                                <td><?=$dr["room"]?></td>
                                                <td><?=$dr["customerName"]?></td>
												<td><?=$dr["date_insert"]?></td>
												<td>
													<a style="padding:4px"  href="index.php?com=passwords&Command=edit&item=<?=$dr["id"]?>"><i class="icon-edit"></i> <?=edit?> </a>
													<!--<a style="padding:4px"  href='http://panel.spotyy.com/sites/hotbox/components/hotbox/passwords-pdf.php?id=<? //=$auth->UserId?>&recID=<? //=$dr["id"]?>'><i class="icon-print"></i> <? //=printer?> </a>-->
													<a style="padding:4px"  target="_blank" href='http://panel.spotyy.com/sites/hotbox/components/hotbox/tcpdf/passwords-pdf.php?id=<?=$auth->UserId?>&recID=<?=$dr["id"]?>'><i class="icon-print"></i> <?=printer?> </a>
													<a href="#" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=passwords&Command=DELETE&item=<?=$dr["id"]?>');"><span><i class="icon-trash"></i> <?=delete?> </a></span></a>
												</td>
                                            </tr>
                                            <?
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <? if($auth->UserType!="Administrator"){ ?>
                     <div class="form-actions">
                         <a href="http://panel.spotyy.com/sites/hotbox/components/hotbox/passwords-pdf.php?id=<?=$auth->UserId?>" target="_blank"><button type="button" class="btn btn-primary"><?=printPasswords?></button></a>
                    </div>
                    <? } ?>
                </div>
            </div>
        </div>
        <?
        }
        ?>