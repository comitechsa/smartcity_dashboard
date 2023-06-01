<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
?>
<?
$config["navigation"] = appTitle;

	LoadNoCacheHeader();
	LoadCharSetHeader();
?>
<!doctype html>
<html><head>
		<? 
		$site_title = site_title;
		if(isset($config["title"])) {$site_title = strip_tags($config["title"]);}
		else if(isset($config["navigation"])) {$site_title = strip_tags($config["navigation"]);}
		?>
		<title><?=$site_title?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$auth->LanguageCharset?>"/>
		<meta name="description" content="<?=(isset($config["metaDesciption"]) ? $config["metaDesciption"] : "")?>" />
		<meta name="keywords" content="<?=(isset($config["metaKeys"]) ? $config["metaKeys"] : "")?>" />
		<script language="javascript">var recordSelect="<?=core_recordSelect;?>";var CurrentLanguage = "<?=$auth->LanguageCode?>";var BaseUrl = "<?=$config["siteurl"]?>";
		</script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/core.js"></script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/public.js"></script>
		<link rel="stylesheet" type="text/css" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/theme.css">		
        <script type="text/javascript">var ajaxUrl= "<?=$config["ajaxUrl"]?>"</script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <!-- Apple devices fullscreen -->
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <!-- Apple devices fullscreen -->
        <meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/bootstrap.min.css">
        <!-- Bootstrap responsive -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/bootstrap-responsive.min.css">
        <!-- jQuery UI -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/plugins/jquery-ui/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/plugins/jquery-ui/smoothness/jquery.ui.theme.css">
        <!-- PageGuide -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/plugins/pageguide/pageguide.css">
        <!-- Fullcalendar -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/plugins/fullcalendar/fullcalendar.css">
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/plugins/fullcalendar/fullcalendar.print.css" media="print">
        <!-- chosen -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/plugins/chosen/chosen.css">
        <!-- select2 -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/plugins/select2/select2.css">
        <!-- icheck -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/plugins/icheck/all.css">
        <!-- Theme CSS -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/style.css">
        <!-- Color CSS -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/themes.css">
    
        <!-- jQuery -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/jquery.min.js"></script>
        
        <!-- Nice Scroll -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
        <!-- jQuery UI -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.core.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.widget.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.mouse.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.draggable.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.resizable.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.sortable.min.js"></script>
        <!-- Touch enable for jquery UI -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/touch-punch/jquery.touch-punch.min.js"></script>
        <!-- slimScroll -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
        <!-- Bootstrap -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/bootstrap.min.js"></script>
        <!-- vmap -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/vmap/jquery.vmap.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/vmap/jquery.vmap.world.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/vmap/jquery.vmap.sampledata.js"></script>
        <!-- Bootbox -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/bootbox/jquery.bootbox.js"></script>
        <!-- Flot -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/flot/jquery.flot.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/flot/jquery.flot.bar.order.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/flot/jquery.flot.pie.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/flot/jquery.flot.resize.min.js"></script>
        <!-- imagesLoaded -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/imagesLoaded/jquery.imagesloaded.min.js"></script>
        <!-- PageGuide -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/pageguide/jquery.pageguide.js"></script>
        <!-- FullCalendar -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/fullcalendar/fullcalendar.min.js"></script>
        <!-- Chosen -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/chosen/chosen.jquery.min.js"></script>
        <!-- select2 -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/select2/select2.min.js"></script>
        <!-- icheck -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/icheck/jquery.icheck.min.js"></script>
    
        <!-- dataTables -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/datatable/jquery.dataTables.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/datatable/TableTools.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/datatable/ColReorder.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/datatable/ColVis.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>
        <!-- Chosen -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/chosen/chosen.jquery.min.js"></script>
    
        <!-- Theme framework -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/eakroko.js"></script>
        <!-- Theme scripts -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/application.min.js"></script>
        <!-- Just for demonstration -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/demonstration.min.js"></script>
        
		<script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/validation/jquery.validate.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/validation/additional-methods.min.js"></script>
        
        <script language="javascript" type="text/javascript" src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/func.js"></script>
        
        <!--[if lte IE 9]>
            <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/placeholder/jquery.placeholder.min.js"></script>
            <script>
                $(document).ready(function() {
                    $('input, textarea').placeholder();
                });
            </script>
        <![endif]-->
    
        <!-- Favicon -->
        <link rel="shortcut icon" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/favicon.ico" />
		<link rel="shortcut icon" href="<?=$config["siteurl"]?>favicon.png">
        <!-- Apple devices Homescreen icon -->
        <link rel="apple-touch-icon-precomposed" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/apple-touch-icon-precomposed.png" />
       
	</head>
	<body>
        <form id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();">
		<input type="hidden" name="Command" id="Command" value="-1">
            <div id="navigation">
                <div class="container-fluid">
                    <a href="index.php" id="brand"><?=site_title?></a>
                    <a href="#" class="toggle-nav" rel="tooltip" data-placement="bottom" title="Toggle navigation"><i class="icon-reorder"></i></a>
                    <ul class='main-nav'>
                        <li class='active'><a href="index.php"><span><?=homePage?></span></a></li>
                        <li>
                            <a href="#" data-toggle="dropdown" class='dropdown-toggle'>
                                <span><?=settings?></span>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="index.php?com=devices"><?=devices?></a>
                                </li>
                                <li>
                                    <a href="index.php?com=messages"><?=compains?></a>
                                </li>
								<!--<li>
									<a href="index.php?com=defaultmessage">Προεπιλεγμένη καμπάνια</a>
								</li>-->
                                <li>
                                    <a href="index.php?com=passwords"><?=accessControl?></a>
                                </li>
                                <li>
                                    <a href="index.php?com=friends"><?=friends?></a>
                                </li>
                                <li>
                                    <a href="index.php?com=rate"><?=rate?></a>
                                </li>
								<!--
                                <li>
                                    <a href="index.php?com=questionnaire">Questionnaire</a>
                                </li>
								-->
                                <li class='dropdown-submenu'>
                                    <a href="#" data-toggle="dropdown" class='dropdown-toggle'><?=stats?></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="index.php?com=stats&item=0"><?=today?></a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=stats&item=1"><?=yesterday?></a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=stats&item=2"><?=lastWeek?></a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=stats&item=3"><?=lastMonth?></a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=stats&item=4"><?=fromTheFirstDay?></a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=stats&item=5"><?=period?></a>
                                        </li>
                                    </ul>
                                </li>
                                <li class='dropdown-submenu'>
                                    <a href="#" data-toggle="dropdown" class='dropdown-toggle'><?=productsandservices?></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="index.php?com=categories"><?=categories?></a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=products"><?=products?></a>
                                        </li>
                                    </ul>
                                </li>
                                <li class='dropdown-submenu'>
                                    <a href="#" data-toggle="dropdown" class='dropdown-toggle'><?=adsManagements?></a>
                                    <ul class="dropdown-menu">
											<? if($auth->UserType == "Administrator") { ?>
											<li>
												<a href="index.php?com=businesscategories"><?=businessCategories?></a>
											</li>
											<li>
												<a href="index.php?com=roles"><?=userRoles?></a>
											</li>
											<li>
												<a href="index.php?com=adspackages"><?=advPackages?></a>
											</li>
											
											<? } ?>
										<li>
											<a href="index.php?com=ads"><?=ads?></a>
										</li>
										<li>
											<a href="index.php?com=credits"><?=views?></a>
										</li>
                                    </ul>
                                </li>
                                <? if($auth->UserType == "Administrator") { ?>
                                <li>
                                    <a href="index.php?com=users"><?=users?></a>
                                </li>
                                <li class='dropdown-submenu'>
									<a href="#" data-toggle="dropdown" class='dropdown-toggle'><?=survey?></a>
                                    <ul class="dropdown-menu">
										<li>
											<a href="index.php?com=ratecategories"><?=categories?></a>
										</li>
										<li>
											<a href="index.php?com=ratequestions"><?=questions?></a>
										</li>
										<li>
											<a href="index.php?com=rateusers"><?=sendedSurveys?></a>
										</li>
										<li>
											<a href="index.php?com=ratings"><?=surveys?></a>
										</li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="index.php?com=subscriptions"><?=subscriptions?></a>
                                </li>
                                <li>
                                    <a href="index.php?com=resellers"><?=resellers?></a>
                                </li>

                                <li>
                                    <a href="index.php?com=scategs"><?=ticketCategories?></a>
                                </li>
                                <li>
                                    <a href="index.php?com=dbprepare"><?=dbPrepare?></a>
                                </li>
                                <li>
                                    <a href="index.php?com=backup"><?=backup?></a>
                                </li>
                                <? } ?>
                            </ul>
                        </li>
                    </ul>
                    <div class="user">
                        <ul class="icon-nav">
                            <li>
                            	<a href="index.php?com=stickets" class='more-messages' rel='tooltip' title="<?=support?>" data-placement="bottom"><i class="icon-envelope-alt"></i>
                                    <span class="label label-lightred">
									<?
                                    //$query = "SELECT * FROM com_support_tickets WHERE status=1";
                                    //$result = $db->sql_query("SELECT * FROM com_support_tickets WHERE status=1");
                                    if($auth->UserType == "Administrator") {
                                        $query="SELECT * FROM com_support_tickets WHERE 1=1 AND isnull(parent_id) AND status=1";
                                    } else {
                                        $query="SELECT * FROM com_support_tickets WHERE 1=1 AND n_id=".$auth->UserId." AND isnull(parent_id) AND status=1";
                                    }
                                    //echo $db->sql_numrows($db->sql_query("SELECT * FROM com_support_tickets WHERE status=1"));
                                    echo $db->sql_numrows($db->sql_query($query));
                                    ?>
                                    </span>
                                </a>
                            </li>
                            <li><a href="index.php?com=locked" class='lock-screen' rel='tooltip' title="<?=screenLock?>" data-placement="bottom"><i class="icon-lock"></i></a></li>
                        </ul>
                        <div class="dropdown">
						<? 
						if(isset($auth->UserRow["user_photo"]) && $auth->UserRow["user_photo"]!="") {
                            $userPhoto='/gallery/customer_logo/'.$auth->UserId.'/'.$auth->UserRow["user_photo"];
                        } else {
                            $userPhoto=$config["siteurl"].'sites/'.$config["site"].'/images/demo/user-avatar.jpg';
                        }
                        ?>
                        <a href="#" class='dropdown-toggle' data-toggle="dropdown"><?=$auth->UserRow["user_fullname"]?><img src="<?=$userPhoto?>" alt="" style='width:25px; height:25px; background-color:#fff;padding:2px;'></a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="index.php?com=profile"><?=editProfile?></a></li>
                                <li><a href="index.php?logout=true"><?=appExit?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="container-fluid nav-hidden" id="content">
				
                <div id="left">
                    <div class="subnav">
                    <div class="subnav-title">
                        <a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span><?=choices?></span></a>
                    </div>
                        <ul class="subnav-menu">
                            <li><a href="index.php?com=devices"><?=devices?></a></li>
                            <li><a href="index.php?com=messages"><?=compains?></a></li>
							<!--<li><a href="index.php?com=defaultmessage">Προεπιλεγμένη καμπάνια</a></li>
                            <li><a href="index.php?com=passwords">Έλεγχος πρόσβασης</a></li>-->
                            <li class='dropdown-submenu'>
                                <a href="#" data-toggle="dropdown" class='dropdown-toggle'><?=stats?></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="index.php?com=stats&item=0"><?=today?></a>
                                    </li>
                                    <li>
                                        <a href="index.php?com=stats&item=1"><?=yesterday?></a>
                                    </li>
                                    <li>
                                        <a href="index.php?com=stats&item=2"><?=lastWeek?></a>
                                    </li>
                                    <li>
                                        <a href="index.php?com=stats&item=3"><?=lastMonth?></a>
                                    </li>
                                    <li>
                                        <a href="index.php?com=stats&item=4"><?=fromTheFirstDay?></a>
                                    </li>
                                    <li>
                                        <a href="index.php?com=stats&item=5"><?=period?></a>
                                    </li>
                                </ul>
                            </li>
                            <li class='dropdown-submenu'>
                                <a href="#" data-toggle="dropdown" class='dropdown-toggle'><?=productsandservices?></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="index.php?com=categories"><?=categories?></a>
                                    </li>
                                    <li>
                                        <a href="index.php?com=products"><?=products?></a>
                                    </li>
                                </ul>
                            </li>
							<? if($auth->UserType == "Administrator") { ?>
                            <li>
                                <a href="index.php?com=users"><?=users?></a>
                            </li>
							<li class='dropdown-submenu'>
								<a href="#" data-toggle="dropdown" class='dropdown-toggle'><?=survey?></a>
								<ul class="dropdown-menu">
									<li>
										<a href="index.php?com=ratecategories"><?=categories?></a>
									</li>
									<li>
										<a href="index.php?com=ratequestions"><?=questions?></a>
									</li>
									<li>
										<a href="index.php?com=rateusers"><?=sendedSurveys?></a>
									</li>
									<li>
										<a href="index.php?com=ratings"><?=surveys?></a>
									</li>
								</ul>
							</li>
                            <li>
                                <a href="index.php?com=subscriptions"><?=subscriptions?></a>
                            </li>
                            <li>
                                <a href="index.php?com=resellers"><?=resellers?></a>
                            </li>
                            <li>
                                <a href="index.php?com=scategs"><?=ticketCategories?></a>
                            </li>
                            <li>
                                <a href="index.php?com=dbprepare"><?=dbPrepare?></a>
                            </li>
                            <li>
                                <a href="index.php?com=backup"><?=backup?></a>
                            </li>
                            <? } ?>

                        </ul>
                    </div>
                </div>
                
                <div id="main">
                    <div class="container-fluid">
                    	<?
                        //$dr_lastm = $db->RowSelectorQuery("SELECT unique_id FROM msgs_messages JOIN msgs_contacts on find_in_set(msgs_contacts.con_id, msgs_messages.sendto) WHERE sended='True' AND msgs_contacts.email='" . $auth->UserRow["email"] . "' ORDER BY idate DESC LIMIT 1 ");
						//$unique_id = "";
						//if(isset($dr_lastm["unique_id"]))
						//{
						//	$unique_id = $dr_lastm["unique_id"];
						//}
						?>
                        <div class="page-header">
                            <div class="pull-left">
                                <h1><?=(isset($config["navigation"]) && $config["navigation"] != "" ? $config["navigation"] : site_home)?></h1>
                            </div>
                            <div class="pull-right">
                               
						<ul class="stats">
							<!-- <li class='satgreen'>
                            	<a href="#"><i class="icon-envelope"></i>
									<div class="details"><span>Δεν έχεται μηνύματα</span></div>
                                </a>
							</li> -->
							<li class='lightred'>
								<i class="icon-calendar"></i>
								<div class="details">
									<span class="big">February 22, 2013</span>
									<span>Wednesday, 13:56</span>
								</div>
							</li>
						</ul>
                            </div>
                        </div>
                        <div class="content">
                        	<div class="row-fluid">
                                <div class="span12">
                                    <ul class="tiles">
                                        <li class="orange high long">
                                            <a href="index.php?com=devices"">
                                            <span class='count'><i class="icon-rss"></i></span>
                                            <span class='name'>
                                            <span class='name'><?=devices?></span>
                                             </span></a>
                                        </li>
                                        <li class="lime high long">
                                            <a href="index.php?com=messages">
                                            <span class='count'><i class="icon-comment"></i></span>
                                            <span class='name'><?=compains?></span>
                                            </a>
                                        </li>
                                        <li class="blue long">
                                            <a href="index.php?com=stats&item=0"><span><i class="icon-bar-chart"></i></span><span class='name'><?=stats?></span></a>
                                        </li>
                                        <li class="teal">
                                            <a href="index.php?com=stickets"><span class='count'><i class="icon-question-sign"></i></span><span class='name'><?=support?></span></a>
                                        </li>
                                         <li class="satblue">
                                            <a href="index.php?subscriptions"><span><i class="icon-credit-card"></i></span><span class='name'><?=subscriptions?></span></a>
                                        </li>
                                        <li class="blue">
                                            <a href="index.php?com=passwords"><span><i class="icon-share"></i></span><span class='name'><?=accessControl?></span></a>
                                        </li>
                                        <li class="red">
                                            <a href="index.php?com=locked"><span class='count'><i class="icon-lock"></i></span><span class='name'><?=lock?></span></a>
                                        </li>
                                         <li class="orange">
                                            <a href="index.php?logout=true"><span><i class="icon-signout"></i></span><span class='name'><?=appExit?></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
  		</form>
	</body>
	<? $validator->RenderValidators();?>
	<? $messages->RenderMessages();?>
</html>