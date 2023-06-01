<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>


<?
session_start();

	//include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."/languages/gr.php");

	$config["navigation"] = appTitle;
?>
<?
	LoadNoCacheHeader();
	LoadCharSetHeader();
?>
<!doctype html>
<html>
	<head>
		<? 
			$site_title = site_title;
			if(isset($config["title"])) {$site_title = strip_tags($config["title"]);}
			else if(isset($config["navigation"])) {$site_title = strip_tags($config["navigation"]);}
		?>
		<title><?=$site_title?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$auth->LanguageCharset?>"/>
		<meta name="description" content="<?=(isset($config["metaDesciption"]) ? $config["metaDesciption"] : "")?>" />
		<meta name="keywords" content="<?=(isset($config["metaKeys"]) ? $config["metaKeys"] : "")?>" />
		<script language="javascript">
			var recordSelect="<?=core_recordSelect;?>"; 
			var CurrentLanguage = "<?=$auth->LanguageCode?>";
			var BaseUrl = "<?=$config["siteurl"]?>";
		</script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/core.js"></script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/public.js"></script>
		<link rel="stylesheet" type="text/css" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/theme.css">
        <script type="text/javascript" src="/gms/client_scripts/ajax/ajaxAgent.js"></script>
        <script type="text/javascript">var ajaxUrl= "<?=$config["ajaxUrl"]?>"</script>
        <script type="text/javascript" src="/gms/client_scripts/swfobject/swfobject.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <!-- Apple devices fullscreen -->
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <!-- Apple devices fullscreen -->
        <meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
		<!--<script src="//cdn.ckeditor.com/4.4.5/full/ckeditor.js"></script>
        <script src="//cdn.ckeditor.com/4.4.5/standard/ckeditor.js"></script>-->
        <script src="//cdn.ckeditor.com/4.4.5/basic/ckeditor.js"></script>
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
        <!-- colorpicker -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/plugins/colorpicker/colorpicker.css">
        <!-- Color CSS -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/themes.css">
        <!-- Datepicker -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/plugins/datepicker/datepicker.css">
        
    	<!-- Plupload -->
		<link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/plugins/plupload/jquery.plupload.queue.css">

    	<!-- Map script css -->
		<link rel="stylesheet" type="text/css" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/jquery-gmaps-latlon-picker.css"/>
		
        <!-- csstree -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/csstree.css">
        
		<!-- fontawesome -->
        <link rel="stylesheet" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/css/font-awesome/css/font-awesome.min.css">
		
        <!-- jQuery -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/jquery.min.js"></script>

        <!-- upload script 
        <script src="<?//=$config["siteurl"]?>sites/<?//=$config["site"]?>/js/uploadscript.js"></script>
        -->
        
        <!-- Nice Scroll -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
        <!-- jQuery UI -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.core.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.widget.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.mouse.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.draggable.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.resizable.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/jquery-ui/jquery.ui.sortable.min.js"></script>

        <!-- PLUpload -->
		<script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/plupload/plupload.full.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/plupload/jquery.plupload.queue.js"></script>
        
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

        <!-- Colorpicker -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/colorpicker/bootstrap-colorpicker.js"></script>
        <!-- imagesLoaded -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/imagesLoaded/jquery.imagesloaded.min.js"></script>
        <!-- PageGuide -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/pageguide/jquery.pageguide.js"></script>
        <!-- FullCalendar -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/fullcalendar/fullcalendar.min.js"></script>
        <!-- Fileupload -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/fileupload/bootstrap-fileupload.min.js"></script>
        <!-- Chosen -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/chosen/chosen.jquery.min.js"></script>
        <!-- select2 -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/select2/select2.min.js"></script>
        <!-- icheck -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/icheck/jquery.icheck.min.js"></script>
    	<!-- Datepicker -->
		<script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/datepicker/bootstrap-datepicker.js"></script>
    
        <!-- dataTables -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/datatable/jquery.dataTables.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/datatable/TableTools.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/datatable/ColReorder.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/datatable/ColVis.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/datatable/jquery.dataTables.columnFilter.js"></script>
        <!-- Chosen -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/chosen/chosen.jquery.min.js"></script>
        <!-- Masked inputs -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/maskedinput/jquery.maskedinput.min.js"></script>
    
        <!-- Theme framework -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/eakroko.js"></script>
        <!-- Theme scripts -->
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/application.min.js"></script>
        
		<script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/validation/jquery.validate.min.js"></script>
        <script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/plugins/validation/additional-methods.min.js"></script>

        <!-- Map scripts -->
		<!--<script src="//maps.googleapis.com/maps/api/js"></script> ?sensor=false-->
		<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyBFpqgM3UtyLtGV-CqIZRfMFmyXms6kgfM"></script>
		<script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/jquery-gmaps-latlon-picker.js"></script>
		
		<!-- Just for demonstration -->
		<script src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/demonstration.js"></script>	
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
		<link rel="shortcut icon" href="<?=$config["siteurl"]?>favicon.png">
        <!--
			<script type="text/javascript" src="<? //=$config["siteurl"]?>sites/<? //=$config["site"]?>/js/lightbox.js"></script>
			<link rel="stylesheet" type="text/css" href="<? //=$config["siteurl"]?>sites/<? //=$config["site"]?>/js/lightbox.css" media="screen" />
        -->
        <!-- Apple devices Homescreen icon -->
        <link rel="apple-touch-icon-precomposed" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/images/apple-touch-icon-precomposed.png" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js"></script>
	</head>

	<body>

<div id="fb-root"></div>
        <form id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();">
		<!-- <form autocomplete="off" id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();"  class='form-validate'><input type="hidden" name="Command" id="Command" value="-1"> -->
		<input type="hidden" name="Command" id="Command" value="-1">

        <div id="navigation">
            <div class="container-fluid">
                <a href="index.php" id="brand"><?=site_title?></a>
                <a href="#" class="toggle-nav" rel="tooltip" data-placement="bottom" title="Toggle navigation"><i class="icon-reorder"></i></a>
                    <ul class='main-nav'>
                        <li>
                            <a href="#" data-toggle="dropdown" class='dropdown-toggle'>
                                <span><?=settings?></span>
                                <span class="caret"></span>
                            </a>
							<ul class="dropdown-menu">
								<li><a href="index.php?com=employees">Υπάλληλοι</a></li>
								<li><a href="index.php?com=departments">Υπηρεσίες</a></li>
								<li><a href="index.php?com=contractors">Ανάδοχοι</a></li>
								<!--
                                <li class='dropdown-submenu'>
                                    <a href="#" data-toggle="dropdown" class='dropdown-toggle'><?//=stats?></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="index.php?com=stats&item=0"><?//=today?></a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=stats&item=1"><?//=yesterday?></a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=stats&item=2"><?//=lastWeek?></a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=stats&item=3"><?//=lastMonth?></a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=stats&item=4"><?//=fromTheFirstDay?></a>
                                        </li>
                                        <li>
                                            <a href="index.php?com=stats&item=5"><?//=period?></a>
                                        </li>
                                    </ul>
                                </li>
								-->


								<li><a href="index.php?com=projects">Έργα</a></li>
								<li class='dropdown-submenu'>
									<a href="#" data-toggle="dropdown" class='dropdown-toggle'>	Δράσεις</a>
									<ul class="dropdown-menu">
										<li>
											<a href="index.php?com=actions&owner=me">Όλες οι δράσεις</a>
										</li>
										<li>
											<a href="index.php?com=studyphase">Οι δράσεις μου</a>
										</li>
									</ul>
								</li>
								<? if($auth->UserType == "Administrator") { ?>
                                <li class='dropdown-submenu'>
									<a href="#" data-toggle="dropdown" class='dropdown-toggle'>Παραμετροποίηση</a>
                                    <ul class="dropdown-menu">
										<li>
											<a href="index.php?com=statuses">Καταστάσεις δράσεων</a>
										</li>
										<li>
											<a href="index.php?com=actiontypes">Τύποι δράσεων</a>
										</li>
										<li>
											<a href="index.php?com=studyphase">Φάσεις μελέτης</a>
										</li>
										<li>
											<a href="index.php?com=axis">Άξονες</a>
										</li>
										<li>
											<a href="index.php?com=frame">Πλαίσιο</a>
										</li>
										<li>
											<a href="index.php?com=target">Στόχοι πλαισίου</a>
										</li>
										<li>
											<a href="index.php?com=actioncategories">Κατηγορίες δράσεων</a>
										</li>
										<li>
											<a href="index.php?com=procedures">Προκαθορισμένες διαδικασίες</a>
										</li>
										<li>
											<a href="index.php?com=finsources">Πηγές χρηματοδότησης</a>
										</li>
										<li>
											<a href="index.php?com=fincategories">Κατηγορίες χρηματοδότησης</a>
										</li>
										<li>
											<a href="index.php?com=finsubcategories">Υποκατηγορίες χρηματοδότησης</a>
										</li>
                                    </ul>
                                </li>
								<? } ?>

								<li class='dropdown-submenu'>
									<a href="#" data-toggle="dropdown" class='dropdown-toggle'>	Λογιστικό σχέδιο</a>
									<ul class="dropdown-menu">
										<li>
											<a href="index.php?com=plan&type=1">Έσοδα</a>
										</li>
										<li>
											<a href="index.php?com=plan&type=2">Εξοδα</a>
										</li>
										<li>
											<a href="index.php?com=planimport">Εισαγωγή</a>
										</li>
									</ul>
								</li>
								<li class='dropdown-submenu'>
									<a href="#" data-toggle="dropdown" class='dropdown-toggle'>	Προυπολογισμός</a>
									<ul class="dropdown-menu">
										<li>
											<a href="index.php?com=budget&type=1">Έσοδα</a>
										</li>
										<li>
											<a href="index.php?com=budget&type=2">Εξοδα</a>
										</li>
									</ul>
								</li>
								<li class='dropdown-submenu'>
									<a href="#" data-toggle="dropdown" class='dropdown-toggle'>	Έσοδα/Έξοδα</a>
									<ul class="dropdown-menu">
										<li>
											<a href="index.php?com=income">Έσοδα</a>
										</li>
										<li>
											<a href="index.php?com=expenses">Εξοδα</a>
										</li>
									</ul>
								</li>
                                <li class='dropdown-submenu'>
									<a href="#" data-toggle="dropdown" class='dropdown-toggle'>Αναφορές</a>
                                    <ul class="dropdown-menu">
										<li><a href="index.php?com=report1">Ενταλθέντα/εισπραχθέντα</a></li>
										<li><a href="index.php?com=report2">Βεβαιωθέντα/προϋπολογισθέντα</a></li>
										<li><a href="index.php?com=report3">Βεβαιωθέντα / Διαγραφέντα / Εισπραχθέντα / Εισπρακτέα</a></li>
										<li><a href="index.php?com=report4">Κωδικοί εσόδων που δεν κινούνται</a></li>
										<li><a href="index.php?com=report5">Top 10 εισπραχθέντα/βεβαιωθέντα</a></li>
										<li><a href="index.php?com=report6">Top 10 εισπραχθέντων</a></li>
										<li><a href="index.php?com=report7">Bottom 10 εισπραχθέντα/βεβαιωθέντα</a></li>
										<li><a href="index.php?com=report8">Bottom 10 εισπραχθέντα</a></li>
										<hr>
										<li><a href="index.php?com=report9">Ενταλθέντα/προϋπολογισθέντα</a></li>
										<li><a href="index.php?com=report10">Δεσμευθέντα / Ενταλθέντα / Πληρωθέντα / Πληρωτέα Υπόλοιπα</a></li>
										<li><a href="index.php?com=report11">Λίστα με τις εγγραφές που δεν "κινούνται"</a></li>
										<li><a href="index.php?com=report12">Top 10 πληρωθέντων / δεσμευθέντα</a></li>
										<li><a href="index.php?com=report13">Top 10 απόλυτα ποσά  ενταλθέντων</a></li>
										<li><a href="index.php?com=report14">Bottom 10 ποσοστό πληρωθέντων /δεσμευθέντα</a></li>
										<li><a href="index.php?com=report15">Bottom 10 απόλυτα ποσά  ενταλθέντων</a></li>
										<li><a href="index.php?com=report16">Top 10 αδιάθετες πιστώσεις</a></li>
                                    </ul>
                                </li>
                                <li class='dropdown-submenu'>
									<a href="#" data-toggle="dropdown" class='dropdown-toggle'>Πρόσβαση</a>
                                    <ul class="dropdown-menu">
										<li>
											<a href="index.php?com=users">Χρήστες</a>
										</li>
										<? if($auth->UserType == "Administrator") { ?>
										<li>
											<a href="index.php?com=municipalities">Δήμοι</a>
										</li>
										<? } ?>
                                    </ul>
                                </li>
								<? if($auth->UserType == "Administrator") {?>
								<li class='dropdown-submenu'>
									<a href="#" data-toggle="dropdown" class='dropdown-toggle'>Εργαλεία</a>
									<ul class="dropdown-menu">
										<li>
											<a href="index.php?com=backup">Αντίγραφο ασφαλείας</a>
										</li>
									</ul>
								</li>
								<? } ?>
                                <li class='dropdown-submenu'>
									<a href="#" data-toggle="dropdown" class='dropdown-toggle'>Υποστήριξη</a>
                                    <ul class="dropdown-menu">
										<li>
											<a href="index.php?com=stickets">Αιτήματα</a>
										</li>
										<? if($auth->UserType == "Administrator") { ?>
										<li>
											<a href="index.php?com=scategs">Κατηγορίες αιτημάτων</a>
										</li>
										<? } ?>
                                    </ul>
                                </li>
								
                             
                            </ul>
                        </li>
                    	<?
						if (isset($_GET["com"]) && ($_GET["com"] == "profile")) {
							?> <li><a href="#" onClick="cm('SAVE',1,0,'');"><span><?=save?></span></a></li><?
						} else if(isset($_GET["item"]) && $_GET["com"] != "stats") {
							$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
						} else if($_GET["com"] == "stats" || $_GET["com"] == "stickets" || $_GET["com"] == "friends"  ) {
                        }
						else
						{
							if($_GET["com"]=='ratequestions'){
								echo '<li><a href="index.php?com='.$_GET["com"].'&set='.$_GET['set'].'&item="><span>'.newRecord.'</span></a></li>';
							} else {
								if (isset($_GET["com"])) echo '<li><a href="index.php?com='.$_GET["com"].'&item="><span>'.newRecord.'</span></a></li>';
							}
						}
						?>
                    </ul>
                <div class="user">
                    <ul class="icon-nav">
						<li>
                        	<a href="index.php?com=stickets" class='more-messages' rel='tooltip' title="<?=support?>" data-placement="bottom"><i class="icon-envelope-alt"></i>
                                <span class="label label-lightred">
                                <?
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
        <div class="container-fluid" id="content">
            <div id="left">
                <div class="subnav">
                    <div class="subnav-title">
                        <a href="#" class='toggle-subnav'><i class="icon-angle-down"></i><span><?=choices?></span></a>
                    </div>
					<ul class="subnav-menu">
						<li><a href="index.php?com=employees">Υπάλληλοι</a></li>
						<li><a href="index.php?com=departments">Υπηρεσίες</a></li>
						<li><a href="index.php?com=contractors">Ανάδοχοι</a></li>
                        <!--
						<li class='dropdown-submenu'>
                            <a href="#" data-toggle="dropdown" class='dropdown-toggle'><?=stats?></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="index.php?com=stats&item=0"><?//=today?></a>
                                </li>
                                <li>
                                    <a href="index.php?com=stats&item=1"><?//=yesterday?></a>
                                </li>
                                <li>
                                    <a href="index.php?com=stats&item=2"><?//=lastWeek?></a>
                                </li>
                                <li>
                                    <a href="index.php?com=stats&item=3"><?//=lastMonth?></a>
                                </li>
                                <li>
                                    <a href="index.php?com=stats&item=4"><?//=fromTheFirstDay?></a>
                                </li>
                                <li>
                                    <a href="index.php?com=stats&item=5"><?//=period?></a>
                                </li>
                            </ul>
                        </li>
						-->
					</ul>
					
				</div>
				
				<div class="subnav">
                    <ul class="subnav-menu">

                        <?
						//$userValue=decbin($acc);
						//$userValue = '1101';
						//echo 'permissions:'.$permissions.'-';
						//echo 'FLAG_DEVICES:'.$FLAG_CAMPAINS;
						if ($permissions1 & $FLAG_DEVICES) { ?><li><a href="index.php?com=devices"><?=devices?></a></li><? } ?>




						<li><a href="index.php?com=projects">Έργα</a></li>
						<li class='dropdown-submenu'>
							<a href="#" data-toggle="dropdown" class='dropdown-toggle'>Δράσεις</a>
							<ul class="dropdown-menu">
								<li>
									<a href="index.php?com=actions">Όλες οι δράσεις</a>
								</li>
								<li>
									<a href="index.php?com=action&owner=me">Οι δράσεις μου</a>
								</li>
							</ul>
						</li>
						
						<li class='dropdown-submenu'>
							<a href="#" data-toggle="dropdown" class='dropdown-toggle'>Παραμετροποίηση</a>
							<ul class="dropdown-menu">
							<? if($auth->UserType == "Administrator") { ?>
								<li>
									<a href="index.php?com=statuses">Καταστάσεις δράσεων</a>
								</li>
								<li>
									<a href="index.php?com=actiontypes">Τύποι δράσεων</a>
								</li>
								<li>
									<a href="index.php?com=studyphase">Φάσεις μελέτης</a>
								</li>
								<li>
									<a href="index.php?com=axis">Άξονες</a>
								</li>
								<li>
									<a href="index.php?com=frame">Πλαίσιο</a>
								</li>
								<li>
									<a href="index.php?com=target">Στόχοι πλαισίου</a>
								</li>
								<li>
									<a href="index.php?com=actioncategories">Κατηγορίες δράσεων</a>
								</li>
								<li>
									<a href="index.php?com=procedures">Προκαθορισμένες διαδικασίες</a>
								</li>
								<li>
									<a href="index.php?com=finsources">Πηγές χρηματοδότησης</a>
								</li>
								<li>
									<a href="index.php?com=fincategories">Κατηγορίες χρηματοδότησης</a>
								</li>
							<? } ?>
								<li>
									<a href="index.php?com=finsubcategories">Υποκατηγορίες χρηματοδότησης</a>
								</li>
							</ul>
						</li>
						<li class='dropdown-submenu'>
							<a href="#" data-toggle="dropdown" class='dropdown-toggle'>	Λογιστικό σχέδιο</a>
							<ul class="dropdown-menu">
								<li>
									<a href="index.php?com=plan&kind=1">Έσοδα</a>
								</li>
								<li>
									<a href="index.php?com=plan&kind=2">Εξοδα</a>
								</li>
							</ul>
						</li>
						<li class='dropdown-submenu'>
							<a href="#" data-toggle="dropdown" class='dropdown-toggle'>	Προυπολογισμός</a>
							<ul class="dropdown-menu">
								<li>
									<a href="index.php?com=budget&type=1">Έσοδα</a>
								</li>
								<li>
									<a href="index.php?com=budget&type=2">Εξοδα</a>
								</li>
							</ul>
						</li>
						<li class='dropdown-submenu'>
							<a href="#" data-toggle="dropdown" class='dropdown-toggle'>	Έσοδα/Έξοδα</a>
							<ul class="dropdown-menu">
								<li>
									<a href="index.php?com=income">Έσοδα</a>
								</li>
								<li>
									<a href="index.php?com=expenses">Εξοδα</a>
								</li>
							</ul>
						</li>
						<li class='dropdown-submenu'>
							<a href="#" data-toggle="dropdown" class='dropdown-toggle'>Αναφορές</a>
							<ul class="dropdown-menu">
								<li><a href="index.php?com=report1">Ενταλθέντα/εισπραχθέντα</a></li>
								<li><a href="index.php?com=report2">Βεβαιωθέντα/προϋπολογισθέντα</a></li>
								<li><a href="index.php?com=report3">Βεβαιωθέντα / Διαγραφέντα / Εισπραχθέντα / Εισπρακτέα</a></li>
								<li><a href="index.php?com=report4">Κωδικοί εσόδων που δεν κινούνται</a></li>
								<li><a href="index.php?com=report5">Top 10 εισπραχθέντα/βεβαιωθέντα</a></li>
								<li><a href="index.php?com=report6">Top 10 εισπραχθέντων</a></li>
								<li><a href="index.php?com=report7">Bottom 10 εισπραχθέντα/βεβαιωθέντα</a></li>
								<li><a href="index.php?com=report8">Bottom 10 εισπραχθέντα</a></li>
								<hr>
								<li><a href="index.php?com=report9">Ενταλθέντα/προϋπολογισθέντα</a></li>
								<li><a href="index.php?com=report10">Δεσμευθέντα / Ενταλθέντα / Πληρωθέντα / Πληρωτέα Υπόλοιπα</a></li>
								<li><a href="index.php?com=report11">Λίστα με τις εγγραφές που δεν "κινούνται"</a></li>
								<li><a href="index.php?com=report12">Top 10 πληρωθέντων / δεσμευθέντα</a></li>
								<li><a href="index.php?com=report13">Top 10 απόλυτα ποσά  ενταλθέντων</a></li>
								<li><a href="index.php?com=report14">Bottom 10 ποσοστό πληρωθέντων /δεσμευθέντα</a></li>
								<li><a href="index.php?com=report15">Bottom 10 απόλυτα ποσά  ενταλθέντων</a></li>
								<li><a href="index.php?com=report16">Top 10 αδιάθετες πιστώσεις</a></li>
							</ul>
						</li>
						<li class='dropdown-submenu'>
							<a href="#" data-toggle="dropdown" class='dropdown-toggle'>Πρόσβαση</a>
							<ul class="dropdown-menu">
								<li>
									<a href="index.php?com=users">Χρήστες</a>
								</li>
								<? if($auth->UserType == "Administrator") { ?>
								<li>
									<a href="index.php?com=municipalities">Δήμοι</a>
								</li>
								<? } ?>
							</ul>
						</li>
						<? if($auth->UserType == "Administrator") { ?>
						<li class='dropdown-submenu'>
							<a href="#" data-toggle="dropdown" class='dropdown-toggle'>Εργαλεία</a>
							<ul class="dropdown-menu">
								<li>
									<a href="index.php?com=backup">Αντίγραφο ασφαλείας</a>
								</li>
							</ul>
						</li>
						<? } ?>
						<li class='dropdown-submenu'>
							<a href="#" data-toggle="dropdown" class='dropdown-toggle'>Υποστήριξη</a>
							<ul class="dropdown-menu">
								<li>
									<a href="index.php?com=stickets">Αιτήματα</a>
								</li>
								<? if($auth->UserType == "Administrator") { ?>
								<li>
									<a href="index.php?com=scategs">Κατηγορίες αιτημάτων</a>
								</li>
								<? } ?>
							</ul>
						</li>
                        
					</ul>
                </div>
            </div>
            <div id="main">
                <div class="container-fluid">                   
					<? if(!isset($_GET['com'])){?>
					<div class="page-header">
						<div class="pull-left">
							<h1><?=(isset($config["navigation"]) && $config["navigation"] != "" ? $config["navigation"] : site_home)?></h1>
						</div>
						<div class="pull-right">
							<ul class="stats">
								<li class='lightred'>
									<i class="icon-calendar"></i>
									<div class="details">
										<span class="big"><?=date("d F Y")?></span>
										<span><?=date("l, h:i")?></span>
									</div>
								</li>
							</ul>
						</div>
					</div>

					<div class="content">
						<div class="row-fluid">
							<div class="span6">
								<div class="box box-color box-bordered">
									<div class="box-title">
										<h3>
											<i class="icon-bar-chart"></i>
											Ενταλθέντα/Εισπραχθέντα
										</h3>
										<div class="actions">
											<a href="#" class="btn btn-mini content-refresh"><i class="icon-refresh"></i></a>
											<a href="#" class="btn btn-mini content-remove"><i class="icon-remove"></i></a>
											<a href="#" class="btn btn-mini content-slideUp"><i class="icon-angle-down"></i></a>
										</div>
									</div>
									<div class="box-content">
										<div class="statistic-big">
										
											<div id="canvas-holder1" style="width:100%">
												<canvas id="chart-area1"></canvas>
											</div>
											<?
												$filter = ($auth->UserType != "Administrator" ? ' AND municipality_id=' . $auth->UserRow['municipality_id'] : ' AND municipality_id=1');
												$queryMain1 = "SELECT sum(received) AS totalReceived,sum(commanded) AS totalCommanded FROM budget_plan WHERE level=1  " . $filter . " ";
												//$resultMain1 = $db->sql_query($queryMain1);
												$drMain1=$db->RowSelectorQuery($queryMain1);
												//$db->sql_freeresult($resultMain1);
											?>
											<div class="bottom">
												<ul class="stats-overview">
													<li>
														<span class="name">
															Ενταλθέντα
														</span>
														<span class="value">
															<?=number_format($drMain1["totalCommanded"], 2, ',', '.')?>
														</span>
													</li>
												
												
													<li>
														<span class="name">
															Εισπραχθέντα
														</span>
														<span class="value">
															<?=number_format($drMain1["totalReceived"], 2, ',', '.') ?>
														</span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="box box-color lightred box-bordered">
									<div class="box-title">
										<h3>
											<i class="icon-bar-chart"></i>
											Ενταλθέντα/προϋπολογισθέντα
										</h3>
										<div class="actions">
											<a href="#" class="btn btn-mini content-refresh"><i class="icon-refresh"></i></a>
											<a href="#" class="btn btn-mini content-remove"><i class="icon-remove"></i></a>
											<a href="#" class="btn btn-mini content-slideUp"><i class="icon-angle-down"></i></a>
										</div>
									</div>
									<div class="box-content">
										<div class="statistic-big">
											<div id="canvas-holder2" style="width:100%">
												<canvas id="chart-area2"></canvas>
											</div>
											<?
												$filterMain2 = ($auth->UserType != "Administrator" ? ' AND municipality_id=' . $auth->UserRow['municipality_id'] : ' AND municipality_id=1');
												$queryMain2 = "SELECT sum(amount) AS totalAmount,sum(commanded) AS totalCommanded FROM budget_plan WHERE level=1  " . $filterMain2 . " ";
												//$resultMain2 = $db->sql_query($queryMain2);
												$drMain2=$db->RowSelectorQuery($queryMain2);
												//$db->sql_freeresult($result);
											?>
											<div class="bottom">
												<ul class="stats-overview">
													<li>
														<span class="name">
															Ενταλθέντα
														</span>
														<span class="value">
															<?=number_format($drMain2["totalCommanded"], 2, ',', '.') ?>
														</span>
													</li>
													<li>
														<span class="name">
															Προϋπολογισθέντα
														</span>
														<span class="value">
															<?=number_format($drMain2["totalAmount"], 2, ',', '.') ?>
														</span>
													</li>
													<li>
														<span class="name">
															Ποσοστό %
														</span>
														<span class="value">
															<?= number_format($drMain2["totalCommanded"]/$drMain2["totalAmount"]*100, 2, ',', '.') ?>
														</span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--
						<div class="row-fluid">
							<div class="span12">
								<ul class="tiles">
									<li class="orange high long">
										<a href="index.php?com=vehicles"">
											<span class='count'><i class="icon-rss"></i></span><span class='name'>Οχήματα</span>
										</a>
									</li>
									<li class="lime high long">
										<a href="index.php?com=bins">
										<span class='count'><i class="icon-comment"></i></span>
										<span class='name'>Κάδοι</span>
										</a>
									</li>
									<li class="blue long">
										<a href="index.php?com=stats&item=0"><span><i class="icon-bar-chart"></i></span><span class='name'><?=stats?></span></a>
									</li>
									<li class="teal">
										<a href="index.php?com=stickets"><span class='count'><i class="icon-question-sign"></i></span><span class='name'><?=support?></span></a>
									</li>
									
									<li class="blue">
										<a href="index.php?com=users"><span><i class="icon-share"></i></span><span class='name'>Χρήστες</span></a>
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
						-->
					</div>
					<? } else {?>
                    <div class="content" style="margin-top:10px;">
	                    <?=$components->RenderRequestComponent(); ?>
					</div>
					<? } ?>
				</div>
			</div>
            
		</div>      
		</form>

	<? if(!isset($_GET['com'])){?>
	<script>
		var configmain1 = {
			type: 'pie',
			data: {
				datasets: [{
					data: [
						<?=$drMain1["totalCommanded"]?>,
						<?=$drMain1["totalReceived"] ?>
					],
					backgroundColor: [
						"#FF0000",
						"#00FF00"
					],
					label: 'Συνολικά ενταλθέντα σε σχέση με τα συνολικά εισπραχθέντα'
				}],
				labels: [
					'Ενταλθέντα',
					'Εισπραχθέντα'
				]
			},
			options: {
				responsive: true
			}
		};

	
		
		var configmain2 = {
			type: 'pie',
			data: {
				datasets: [{
					data: [
						<?=$drMain2["totalCommanded"]?>,
						<?=$drMain2["totalAmount"] ?>
					],
					backgroundColor: [
						"#FF0000",
						"#00FF00"
					],
					label: 'Ποσοστό Ενταλθέντων (συνολικά) σε σχέση με τα συνολικά Προϋπολογισθέντα (ποσοστό και προβολή πίτας)'
				}],
				labels: [
					'Ενταλθέντα',
					'Προυπολογισθέντα'
				]
			},
			options: {
				responsive: true
			}
		};

		window.onload = function() {
			var ctxMain1 = document.getElementById('chart-area1').getContext('2d');
			window.myPie1 = new Chart(ctxMain1, configmain1);
			
			var ctxMain2 = document.getElementById('chart-area2').getContext('2d');
			window.myPie2 = new Chart(ctxMain2, configmain2);
		};	

	</script>
	<? } ?>
	</body>
	<? $validator->RenderValidators();?>
	<? $messages->RenderMessages();?>
</html>
