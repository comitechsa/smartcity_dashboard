<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
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
	$FLAG_TICKETS = 4096;
	$permissions = (intval($auth->UserRow['access'])>0?$auth->UserRow['access']:0);

	if (!($permissions & $FLAG_TICKETS)) {
		$messages->addMessage("INVALID!!!");
		Redirect("index.php");
	}
//end permissions

	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");

global $nav;
$nav = support;
$config["navigation"] = support;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=stickets"; //=" . $_GET["item"];
$statusCol = array("",inProgress,closed);
if($auth->UserType != "")
{
	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "SAVE")
		{
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();

			if(isset($_POST["item"]) && $_POST["item"] != "" && intval($_POST["item"])> 0 && $_POST['description2']!='')  //Νέο μνμ στο thread
			{
				if(isset($_POST['status'])) { //βρες το πρώτο μήνυμα του thread και άλλαξέ του κατάσταση
					$PrimaryKeys["ticket_id"] = $_POST["item"];
					$QuotFields["ticket_id"] = true;

					//if($_POST["is_system"] != "off") {
					//	$Collector["new"] = 1;
					//	$QuotFields["new"] = true;
					//}

					$Collector["status"] = $_POST['status2']; //άλλαγή του status με τη νέα κατάσταση
					$QuotFields["status"] = true;

					$db->ExecuteUpdater("com_support_tickets",$PrimaryKeys,$Collector,$QuotFields);	
					unset($PrimaryKeys["ticket_id"]);
					unset($QuotFields["ticket_id"]);
				}
			}

			if(isset($_POST["item"]) && $_POST["item"] != "" && intval($_POST["item"])> 0  && $_POST['description2']!=''){
				$Collector["t_code"] =  $_POST["t_code"];
				$QuotFields["t_code"] = true;

				$Collector["parent_id"] = $_POST["item"];
				$QuotFields["parent_id"] = true;
			} else {			
				$Collector["t_code"] =  $auth->UserId . "/" . makePassword(8, true);
				$QuotFields["t_code"] = true;
			}
			
			if($_POST["is_system"] != "on") {
				$Collector["new"] = 1;
				$QuotFields["new"] = true;
			}

			if($_POST['description2']!='') {						
				$Collector["n_id"] = (isset($_POST["n_id2"]) && $_POST["n_id2"]!=""?$_POST["n_id2"]:$auth->UserId);
				$QuotFields["n_id"] = true;
				
				$Collector["description"] = html_entity_decode($_POST['description2'], ENT_QUOTES, "UTF-8");
				$QuotFields["description"] = true;
				
				$Collector["category"] = ($_POST["categ_id2"] != "" ? $_POST["categ_id2"] : "1");
				$QuotFields["category"] = true;
				
				$Collector["status"] = ($_POST["status2"] != "" ? $_POST["status2"] : "1");
				$QuotFields["status"] = true;
				
				$Collector["title"] = $_POST["title2"];
				$QuotFields["title"] = true;
				
				$Collector["is_system"] = isset($_POST["is_system2"]) && $_POST["is_system2"] == "on" ? "1" : "0";
				$QuotFields["is_system"] = true;
				
				$Collector["send_mail"] =  isset($_POST["send_mail2"]) && $_POST["send_mail2"] == "on" ? "1" : "0";
				$QuotFields["send_mail"] = true;
				
			} else {
				$Collector["n_id"] = (isset($_POST["n_id"]) && $_POST["n_id"]!=""?$_POST["n_id"]:$auth->UserId);
				$QuotFields["n_id"] = true;
				
				$Collector["description"] = html_entity_decode($_POST['description'], ENT_QUOTES, "UTF-8");
				$QuotFields["description"] = true;
				
				$Collector["category"] = ($_POST["categ_id"] != "" ? $_POST["categ_id"] : "1");
				$QuotFields["category"] = true;
				
				$Collector["status"] = ($_POST["status"] != "" ? $_POST["status"] : "1");
				$QuotFields["status"] = true;
				
				$Collector["title"] = $_POST["title"];
				$QuotFields["title"] = true;
				
				$Collector["is_system"] = isset($_POST["is_system"]) && $_POST["is_system"] == "on" ? "1" : "0";
				$QuotFields["is_system"] = true;
				
				$Collector["send_mail"] =  isset($_POST["send_mail"]) && $_POST["send_mail"] == "on" ? "1" : "0";
				$QuotFields["send_mail"] = true;
			}
		
			//$Collector["comp_u_id"] = GetCurrentCompUId();
			//$QuotFields["comp_u_id"] = true;
		
			$Collector["priority"] = ($_POST["priority"] != "" ? $_POST["priority"] : "1");
			$QuotFields["priority"] = true;

			$Collector["date_insert"] = date('Y-m-d H:i:s');
			$QuotFields["date_insert"] = true;
		
			$Collector["user_t"] = ($auth->UserType == "Administrator" ? "1" : "0");
			$QuotFields["user_t"] = true;
		
			$Collector["user_insert"] = $auth->UserId;
			$QuotFields["user_insert"] = true;

			$db->ExecuteUpdater("com_support_tickets",$PrimaryKeys,$Collector,$QuotFields);

			if(isset($_GET["id"]) && $_GET["id"] != "") $Record_ID = $_GET["id"];
			else $Record_ID = $db->sql_nextid();
	
			//Network Selection
			$dr_m = $db->RowSelectorQuery("SELECT * FROM users WHERE user_id=" . $Collector["n_id"]);
	
			$MailContent = site_newTicketInsert . " (" . $Collector["t_code"] . ") από ".($Collector["user_t"]==1?'Administrator':$dr_m["user_fullname"])."<br><br>";
			$MailContent .= 'Δίκτυο:'.$dr_m["company_name"].'<br>';
			$MailContent .= site_subject . ": " . ($_POST['description2']!='' ? $_POST["title2"]:$_POST["title"]) . "<br>";
			$MailContent .= site_description . ": " . ($_POST['description2']!='' ? $_POST["description2"]:$_POST["description2"]) . "<br>";
			
			$MailContent .= "<br><a href='" . $config["siteurl"] . "index.php?com=stickets'>Περισσότερες πληροφορίες</a>";
			
			//Send to Admin
			SendMail($MailContent, " ".site_supportRequest." ( " . $Collector["t_code"] . ") - " . $dr_m["company_name"]." από ".($Collector["user_t"]==1?'Administrator':$dr_m["user_fullname"]) , 'info@spotyy.com');
			//Send to Network
			if(isset($_POST["send_mail"]) && $_POST["send_mail"] == "on")
			{
				$mEmailr = $dr_m["email"];
				if($mEmailr != "") {  ; //$mEmailr .= ";" . $auth->UserRow["email"] ;
					//Send to User
					//Send to Network
					SendMail($MailContent, " " . site_supportRequest . " ( " . $Collector["t_code"] . ")",$mEmailr) ;//substr($mEmailr,1));
				}
				/*
				if(isset($dr_m["email"]) && $dr_m["email"] != "" && $dr_m["email_support"] == "True") $mEmailr .= ";" . $dr_m["email"];
				//Send to all Contact Persons
				$result_d = $db->sql_query("SELECT * FROM networks_rpersons WHERE n_id=" . $dr_m["n_id"] . " AND rp_support='True' AND rp_email IS NOT NULL");
				while ($row = $db->sql_fetchrow($result_d)) { $mEmailr .= ";" . $row["rp_email"]; }
				$db->sql_freeresult($result_d);
				
				if($mEmailr != "") SendMail($MailContent, " " . site_supportRequest . " ( " . $Collector["t_code"] . ")", substr($mEmailr,1));
				*/
			}

			$messages->addMessage("SAVED!!!");
			Redirect($BaseUrl);
		} else if($_REQUEST["Command"] ==  "DELETE") {
			if($item != "")
			{
			if($auth->UserType == "Administrator")
			{
				$dr = $db->RowSelectorQuery("SELECT COUNT(*) FROM com_support_tickets WHERE parent_id=" . $_GET["item"]);
				if($dr[0] > 0)
				{
					$deleteMessage=cannotDelete;
				}
				else
				{
					$db->sql_query("DELETE FROM com_support_tickets WHERE ticket_id=" . $_GET["item"]);
					$deleteMessage=canDelete;
				}
			}
				//$db->sql_query("DELETE FROM com_support_categs WHERE categ_id=" . $item);
				//$messages->addMessage("DELETE!!!");
				$messages->addMessage($deleteMessage);
				Redirect($BaseUrl);
			}
		}
	}
}
?>

	<br class="clr">
	<br />
	<? 
        //$selected_net = (isset($_GET["nid"])? $_GET["nid"] : "");
		$selected_net = ($auth->UserType != "Administrator" ? $auth->UserId : "");
        $selected_status = (isset($_GET["status"]) && intval($_GET["status"]) > 0 ? intval($_GET["status"]) : "");
        $selected_categ = (isset($_GET["categ"]) && intval($_GET["categ"]) > 0 ? intval($_GET["categ"]) : "");
    ?>    
	<?
	
	$filter = "";
	$filter .=($auth->UserType != "Administrator" ? ' AND n_id='.$auth->UserId:'');
	//if(isset($selected_net) && $selected_net != "") 
	//{
	//$selected_net = $config["siteID"];
		//if($selected_net[0] == "n") 
		//{
		//	$selected_net = str_replace("n_","",$selected_net); 
		//	$filter .= " AND n_id='" . intval($selected_net) . "' ";
		//}
	//$filter .= " AND n_id='" . intval($selected_net) . "' ";
	//}

	//if( $auth->UserType == "NetworkUser" ) $filter .= " AND is_system = 0 ";
	//if( $selected_status != "" ) $filter .= " AND status = " . $selected_status;

	if( $selected_categ != "") $filter .= " AND category = " . $selected_categ;
	$filter.=(isset($_GET['status']) && ($_GET['status']==1 || $_GET['status']==2)?' AND status='.$_GET['status']:'');
	$filter.=($auth->UserType != "Administrator"?" AND is_system='0' AND n_id=".$auth->UserId:"");
	$query = "SELECT * FROM com_support_tickets WHERE 1=1 AND isnull(parent_id) ";// WHERE parent_id IS NULL"; // AND comp_u_id=" . GetCurrentCompUId()
	$query .= $filter;	
	//if( $auth->UserType == "Company" ) $query .= " AND comp_u_id=" . $auth->UserId;
	//else 
	// $query .= mFilter();
	//echo $query;
	$query .= "  ORDER BY status ASC, date_insert DESC ";
	$result = $db->sql_query($query . $order); // . $Paging);
	$numRows = $db->sql_numrows($result);
	?>

<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
    
<div class="row-fluid">
    <div class="span12">
        <div class="box box-bordered box-color">
            <div class="box-title">
                <h3>
                    <i class="icon-envelope"></i>
                    <?=support?>
                </h3>
            </div>
            <div class="box-content nopadding">
                <ul class="tabs tabs-inline tabs-left">
                    <li class='write hidden-480' id="newmessageli">
                       <a href="#newmessage" data-toggle="tab" style="background:#e63a3a; color:#fff; margin:10px;"><?=newMessage?></a>
                    </li>
                    <li class='active' id='allli'>
                        <a href="#inbox" onclick="window.location.href=PrepareUrl({status:0});" data-toggle="tab"><i class="icon-inbox"></i> <?=all?></a>
                    </li>
                    <li id='editli'>
                        <a href="#sent" onclick="window.location.href=PrepareUrl({status:1});" data-toggle="tab"><i class="icon-share-alt"></i> <?=inProgress?></a>
                    </li>
                    <li id='completedli'>
                        <a href="#trash" onclick="window.location.href=PrepareUrl({status:2});" data-toggle="tab"><i class="icon-ok"></i> <?=closed?></a>
                    </li>
                </ul>
                <div class="tab-content tab-content-inline">

                    <!-- first choice --> 
                    <div class="tab-pane active" id="inbox" <?=($_GET['status']!=-1?'style="min-height:400px;"':'')?>>
						<? if($_GET['status']!=-1) {?>
                            <div class="highlight-toolbar">
                                <div class="pull-left">
                                    <div class="btn-toolbar">
                                        <div class="btn-group visible-480">
                                            <a href="" class="btn btn-danger"><?=newRec?></a>
                                        </div>
                                        <!--<div class="btn-group">
                                            <span style="float:left;width:200px; margin-left:40px;">
                                                    <label class="control-label" for="select" style="float:left; margin-right:20px; padding-top:5px;">Κατηγορία</label>
                                                    <select class="btn" rel="tooltip" data-placement="bottom" title="Κατηγορίες" onchange="window.location.href=PrepareUrl({categ:this.value});">
                                                    <option value="">Όλες οι κατηγορίες</option>
                                                    <?
                                                    //$query2 = "SELECT * FROM com_support_categs ORDER BY name"; //WHERE comp_u_id=" . GetCurrentCompUId() . "
                                                    //$result2 = $db->sql_query($query2);
                                                    //while ($dr2 = $db->sql_fetchrow($result2))
                                                    //{
                                                        ?><option value="<?//=$dr2["categ_id"]?>" <?//=($dr2["categ_id"])==$selected_categ?"selected":""?>><? //=$dr2["name"]?></option><?
                                                    //}
                                                    //$db->sql_freeresult($result2);
                                                    ?>
                                                </select>
                                            </span> 
                                        </div>  -->
                                    </div>
                                </div>
                                <div class="pull-right">
                                </div>
                            </div>
                            <table class="table table-striped table-nomargin table-hover dataTable dataTable-columnfilter table-bordered">
                                <thead>
                                    <tr>
                                        <th class='table-checkbox hidden-480'>#</th>
                                        <?=($auth->UserType == "Administrator" ? '<th>'.customer.'</th>':''); ?>
                                        <th><?=category?></th>
                                        <th><?=site_subject?></th>
                                        <th><?=status?></th>
                                        <th><?=ticketDate?></th>
                                        <th><?=action?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?
                                while ($dr = $db->sql_fetchrow($result))
                                {
                                    ?>
                                      <tr class='unread'> 
                                        <td class='table-fixed-medium'>
											<? echo '123';
											$drNew=$db->RowSelectorQuery("SELECT * FROM `com_support_tickets` where ticket_id=".$dr['ticket_id']." or parent_id=".$dr['ticket_id']." ORDER by date_insert DESC limit 1");
											if($drNew['user_insert']!=$auth->UserId && $drNew['new']==1) {
												echo '<strong>'.$dr["t_code"].'</strong>';
											} else {
												echo $dr["t_code"];
											}
											?>
                                                </td>
                                        <?	if($auth->UserType == "Administrator"){
                                                $dr_n = $db->RowSelectorQuery("SELECT * FROM users WHERE user_id=" . $dr["n_id"]);
                                                echo "<td>".$dr_n["company_name"]."</td>";
                                            }
                                         ?>
                                        <td class='table-fixed-medium'>
                                            <?
                                                $dr_c = $db->RowSelectorQuery("SELECT * FROM com_support_categs WHERE categ_id=" . $dr["category"]);
                                                echo $dr_c["name"];
                                            ?>
                                        </td>
                                        <td class='table-fixed-medium'><?=$dr["title"]?></td>
                                        <td class='table-fixed-medium'><?=$statusCol[$dr["status"]]?></td>
                            
                                        <td class='table-fixed-medium'><?=formatDate($dr["date_insert"],false)?></td>
                                        <td class='table-fixed-medium'>
                                            <nobr>
                                                <a href='index.php?com=stickets&item=<?=$dr['ticket_id']?>&status=-1' class="btn" title="<?=details?>" data-placement="bottom" rel="tooltip" href="#" data-original-title="<?=details?>"><i class="icon-search"></i></a>
                                              <?
                                              if($auth->UserType == "Administrator")
                                              {
                                                  ?>
                                                      <a href='javascript:void(0);' class="btn" title="<?=delete?>" data-placement="bottom" rel="tooltip" href="#" data-original-title="<?=delete?>" onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=stickets&Command=DELETE&item=<?=$dr["ticket_id"]?>');">
                                                       <i class="icon-trash"></i></a>
                                                  <?
                                              }
                                              ?>
                                            </nobr>
                                        </td>
                                    </tr>
                                    <?
                                }
                                ?>
                                </tbody>
                            </table>
						<? } ?>
                    </div>
					<!-- end first --->
                    
                    <!-- second and third choice 
					<div class="tab-pane" id="sent" style="min-height:400px;">
					</div>
					<div class="tab-pane" id="trash" style="min-height:400px;">
					</div>
                    // end  --> 
                
                <!-- forth choice  / new message -->
					<div class="tab-pane" id="newmessage" style="min-height:400px;margin: 0 20px;">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="box-title">
                                    <h3><i class="icon-envelope"></i><?=newMessage?></h3>
                                </div>
                                    <? if($auth->UserType == "Administrator"){ ?> 
                                    <div class="controls">
                                        <label for="textfield" class="control-label"><?=customer?></label>
                                        <?
                                            $query = "SELECT *, user_id AS n_id FROM users WHERE is_valid='True' AND user_auth!='Administrator' ORDER BY user_fullname";
                                            echo Select::GetDbRender("n_id", $query, "n_id", "user_fullname", (isset($dr_e["n_id"]) ? $dr_e["n_id"] : ""), true,'input-xlarge valid');
                                         ?>
                                    </div>
                                    <div class="controls">
                                        <label for="textfield" class="control-label"><?=site_system?></label>
                                        <input id="is_system" name="is_system" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["is_system"]) && $dr_e["is_system"]=='True') ? 'checked':'')?> />
                                    </div>                                    
                                    <div class="controls">
                                        <label for="textfield" class="control-label"><?=site_networkSendMail?></label>
                                        <input id="send_mail" name="send_mail" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <?=((isset($dr_e["send_mail"]) && $dr_e["send_mail"]=='True') ? 'checked':'')?> />
                                    </div>
									<? } ?>
                                    <div class="controls">
                                        <label for="textfield" class="control-label"><?=category?></label>
                                        <?
                                            $query = "SELECT * FROM com_support_categs ORDER BY name";
                                            echo Select::GetDbRender("categ_id", $query, "categ_id", "name", (isset($dr_e["categ_id"]) ? $dr_e["categ_id"] : ""), true,'input-xlarge valid');
                                         ?>
                                    </div>
                                    <div class="controls">
                                        <label for="textfield" class="control-label"><?=site_subject?></label>
                                        <input type="text" name="title" id="title" required class="input-xlarge valid" data-rule-minlength="2" data-rule-required="true" <?=(isset($dr_e["title"]) ? 'value="'.$dr_e['title'].'"' : "")?> >
                                    </div>
									<div class="control-group">
										<div class="controls">
											<label for="textfield" class="control-label"><?=site_description?></label>
											<textarea name="description" rows="6" id="description" class="input-block-level"></textarea>
										</div>
									</div>
                                    <div class="controls">
										<label for="textfield"><?=status?></label>
                                        <select id="status" name="status">
                                        	<option value="1" selected><?=inProgress?></option>
                                            <option value="2"><?=closed?></option>
										</select>
                                    </div>
                                    <a href="#" onClick="cm('SAVE',1,0,'');">   <button type="button" class="btn btn-primary"><?=save?></button></a>
                                    <a href="index.php?com=stickets"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
                                    <br><br>
							</div>
						</div>
					</div>
                <!-- end forth -->

				<!-- fifth-->
					<div class="tab-pane" id="newrecord" style="min-height:400px;margin: 0 20px;">
    				<div class="row-fluid">
                        <div class="span12">
                            <div class="box">
                                <div class="box-title">
                                <? if(isset($_GET['item']) && $_GET['item']!='') {
										$drNew=$db->RowSelectorQuery("SELECT * FROM `com_support_tickets` where ticket_id=".$_GET['item']." or parent_id=".$_GET['item']." ORDER by date_insert DESC limit 1");
										if($drNew['user_insert']!=$auth->UserId ) {
											$result = mysql_query('UPDATE com_support_tickets SET new = 0 WHERE ticket_id = '.$_GET['item'].' OR parent_id='.$_GET['item']); // AND isnull(parent_id)');
										}
										$drTitle=$db->RowSelectorQuery("SELECT * FROM com_support_tickets WHERE 1=1 AND ticket_id=".$_GET['item']);
									}
									?>
                                    <h3>
                                        <i class="icon-comments"></i>
                                        <?=$drTitle['title'].($drTitle['status']==1?' | ('.inProgress.')':' | ('.closed.')')?>
                                    </h3>
                                    <div class="actions">
                                        <a href="#" class="btn btn-mini content-refresh"><i class="icon-refresh"></i></a>
                                        <a href="#" class="btn btn-mini content-remove"><i class="icon-remove"></i></a>
                                        <a href="#" class="btn btn-mini content-slideUp"><i class="icon-angle-down"></i></a>
                                    </div>
                                </div>
                                <?
								if($_GET['status']==-1 && intval($_GET['item'])>0) {
									$queryDiscussion="SELECT com_support_tickets.*, users.user_id, users.user_photo, users.company_name,users.user_fullname FROM `com_support_tickets` INNER JOIN users ON com_support_tickets.user_insert = users.user_id WHERE (ticket_id=".$_GET['item']." or parent_id=".$_GET['item'].") ".($auth->UserType != "Administrator"?' AND is_system=0':'')." ORDER BY com_support_tickets.date_insert";
									$resultDiscussion = $db->sql_query($queryDiscussion);
								//}
                                ?>
                                <div style="position: relative; overflow: hidden; width: auto;" class="slimScrollDiv"><div style="overflow: hidden; width: auto;" class="box-content nopadding">
                                    <ul class="messages">
									<?
									while ($drD = $db->sql_fetchrow($resultDiscussion))
									{
										echo ($drD['user_t']==1?'<li class="left">':'<li class="right">'); ?>
                                            <div class="image">
												<? echo ($drD['user_t']==1? '<img src="/favicon.png" alt="">':'<img src="/gallery/customer_logo/'.$drD["user_id"]."/".$drD['user_photo'].'" alt="" width="30px">'); ?>
                                                <? if($auth->UserType == "Administrator"){?>
                                                <br> <a href='javascript:void(0);' onclick="ConfirmDelete('<?=deleteConfirm?>','index.php?com=stickets&Command=DELETE&item=<?=$drD["ticket_id"]?>');"><i class="icon-remove"></i></a>
                                                <? } ?> 
                                            </div>
                                            <div class="message" <?=($drD["is_system"]==1?'style="background-color:#d3a2bf"':'')?>>
                                                <span class="caret"></span>
                                                <span class="name"><?=$drD['company_name'].' - '.$drD['user_fullname']?></span>
                                                <p><?=$drD['description']?></p>
                                                <span class="time">
                                                    <?=$drD['date_insert']?>
													<? if($drD['parent_id']!=''){
															echo ($drD['status']==1?' ('.inProgress.')':' ('.closed.')');
														}
													?>
                                                </span>
                                            </div>
                                        </li>
									<? } ?>
                                        <li class="insert">
											<!-- code for insert-->
                                        </li>
                                    </ul>
                                </div><div style="background: none repeat scroll 0% 0% rgb(102, 102, 102); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 404px;" class="slimScrollBar ui-draggable"></div><div style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: none repeat scroll 0% 0% rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;" class="slimScrollRail"></div></div>
                            </div>
                        </div>
                    </div>
                        <div class="row-fluid">
                            <div class="span12">            
                                <div class="box-title">
                                    <h3><i class="icon-envelope"></i><?=newMessage?></h3>
                                </div>
                                	<? $drTicket=$db->RowSelectorQuery("SELECT * FROM com_support_tickets WHERE ticket_id=".$_GET['item']); ?>
									<input type="hidden" name="t_code" id="t_code" value='<?=$drTicket['t_code']?>' />
									<input type="hidden" name="n_id2" id="n_id2" value='<?=$drTicket['n_id']?>' />
									<input type="hidden" name="title2" id="title2" value='<?=$drTicket['title']?>' />
									<input type="hidden" name="parent_id" id="parent_id" value='<?=$drTicket['ticket_id']?>' />
									<input type="hidden" name="categ_id2" id="categ_id2" value='<?=$drTicket['category']?>' />
									<input type="hidden" name="item" id="item" value='<?=$drTicket['ticket_id']?>' />
                                    
                                    <? if($auth->UserType == "Administrator"){ ?> 
                                    <div class="controls">
                                        <label for="textfield" class="control-label"><?=site_system?></label>
                                        <input id="is_system2" name="is_system2" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <? //=((isset($dr_e["is_system"]) && $dr_e["is_system"]=='True') ? 'checked':'')?> />
                                    </div>
                                    <div class="controls">
                                        <label for="textfield" class="control-label"><?=site_networkSendMail?></label>
                                        <input id="send_mail2" name="send_mail2" class="icheck-me" data-skin="square" data-color="blue" type="checkbox" <? //=((isset($dr_e["send_mail"]) && $dr_e["send_mail"]=='True') ? 'checked':'')?> />
                                    </div>
									<? } ?>
									<div class="control-group">
										<div class="controls">
											<label for="textfield" class="control-label"><?=description?></label>
											<textarea name="description2" rows="6" id="description2" class="input-block-level"></textarea>
										</div>
									</div>
                                    <div class="controls">
										<label for="textfield"><?=status?></label>
                                        <select id="status2" name="status2">
                                        	<option value="1" <? //=(isset($drTicket['status'])&& $drTicket['status']==1?'selected':'')?>><?=site_statusEdit?></option>
                                            <option value="2" <? //=(isset($drTicket['status'])&& $drTicket['status']==2?'selected':'')?>><?=site_statusCompleted?></option>
										</select>
                                    </div>
                                    <a href="#" onClick="cm('SAVE',1,0,'');"><button type="button" class="btn btn-primary"><?=save?></button></a>
                                    <a href="index.php?com=stickets"><button type="button" class="btn btn-primary"><?=pageReturn?></button></a>
								<? } ?>
							</div>
						</div>
					</div>
                <!-- end fifth --> 
				</div>
			</div>
        </div>
    </div>
</div>
<br><br>

<script>
	//document.getElementById("submitBtn").disabled = true;
	function checkFields(){
	var value = $('#description').val();
	var value2 = $('#cke_1_contents') .val();
		//if ( value.length >= 2 && value2.length >= 2){
		if ( value.length >= 2){
				cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
		} else {
			alert(value2.length);
		}
		//else {
			//document.getElementById("submitBtn").disabled = true;
			//alert('2 chars');
		//}
	}
</script>
<? if (!isset($_GET['status']) || $_GET['status']==0) {
	?>  <script language="javascript">
        $('#allli').removeClass('active');
		$('#completedli').removeClass('active');
		$('#editli').removeClass('active');
		$('#newrecord').removeClass('active');
        $('#allli').addClass('active');
		
        </script>
	<? 	} elseif(isset($_GET['status']) && $_GET['status']==1) {
	?>  <script language="javascript">
        $('#allli').removeClass('active');
		$('#completedli').removeClass('active');
		$('#editli').removeClass('active');
		$('#newrecord').removeClass('active');
		$('#editli').addClass('active');
        </script>
	<? } elseif(isset($_GET['status']) && $_GET['status']==2) {
	?> 	<script language="javascript">
        $('#allli').removeClass('active');
		$('#completedli').removeClass('active');
		$('#editli').removeClass('active');
		$('#newrecord').removeClass('active');
        $('#completedli').addClass('active');
        </script>
	<? } elseif(isset($_GET['status']) && $_GET['status']==-1) {
	?> 	<script language="javascript">
        $('#allli').removeClass('active');
		$('#completedli').removeClass('active');
		$('#editli').removeClass('active');
		$('#newrecord').removeClass('active');
        $('#newrecord').addClass('active');
        </script>
	<? } else {
	?> 	<script language="javascript">
		$('#completedli').removeClass('active');
        $('#editli').removeClass('active');
        $('#allli').addClass('active');
        </script>
	<?
	}
?>
	<script language="javascript">
        CKEDITOR.replace( 'description' );
        CKEDITOR.add;
        CKEDITOR.replace( 'description2' );
        CKEDITOR.add; 
    </script>
