<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>

<?
session_start();
	//include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."languages/gr.php");
	include($config["physicalPath"]."/perm.php");
	$permissions = (intval($auth->UserRow['access'])>0?$auth->UserRow['access']:0);
	
	if(!isset($config["navigation"])) $config["navigation"] = appTitle;
?>
<?
	LoadNoCacheHeader();
	LoadCharSetHeader();
?>
<!doctype html>
<html class="sidebar-light sidebar-left-big-icons">
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
		<!-- Basic -->
		<meta charset="UTF-8">
		<meta name="author" content="dotsoft">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css" />
		<!-- <link rel="stylesheet" type="text/css" media="screen" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />-->
		<link rel="stylesheet" href="vendor/animate/animate.css">

		<link rel="stylesheet" href="vendor/font-awesome/css/all.min.css" />
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"> <!--new-->
		
		
		<link rel="stylesheet" href="vendor/magnific-popup/magnific-popup.css" />
		<!-- 
		<link rel="stylesheet" href="vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
		-->

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="vendor/jquery-ui/jquery-ui.css" />
		<link rel="stylesheet" href="vendor/jquery-ui/jquery-ui.theme.css" />
		<link rel="stylesheet" href="vendor/bootstrap-multiselect/css/bootstrap-multiselect.css" />
		<link rel="stylesheet" href="vendor/morris/morris.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="css/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="css/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="css/custom.css">

		<!-- Head Libs -->
		<script src="vendor/modernizr/modernizr.js"></script>
		
		<!-- heatmap -->
		<!-- <link rel="stylesheet" href="css/heatmap.css"> -->
		<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAzYeS10uHq9OvcW4G3NKRoNT3AJmt1RI0"></script> -->
		<?if(isset($_GET['com']) && $_GET['com']=='heatmap'){?>
			<link rel="stylesheet" href="https://unpkg.com/leaflet@latest/dist/leaflet.css" />
			<script src="https://unpkg.com/leaflet@latest/dist/leaflet.js"></script>
			<script src='https://unpkg.com/leaflet.gridlayer.googlemutant@latest/Leaflet.GoogleMutant.js'></script>
			<script src="js/heatmap.min.js"></script>
			<script src="js/leaflet-heatmap.js"></script>
			<!-- 
			<style>
				.demo1-wrapper {position: absolute;top: 0;bottom: 0;width: 99%;height:99%;}
			</style>
			-->
		<? } ?>
		<?if(isset($_GET['com']) && $_GET['com']=='analogcluster'){?>
			<style>
				.cluster {
				  display: table;
				  box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.5);
				}
				.cluster img {
				  display: none
				}
				.cluster div {
				  color: inherit !important;
				  display: table-cell;
				  vertical-align: middle;
				  width: 100% !important;
				  height: 100% !important;
				  line-height: inherit !important;
				}
				/*custom cluster-styles*/
				.cluster_blue {
				  background: rgba(0,0,255,0.3);
				  /*
				  width:50px !important;
				  height:50px !important;				  
				  */

				}
			</style>
		
			<script src="js/markerclusterer.js"></script>
		<? } ?>

		<?if(isset($_GET['com']) && $_GET['com']=='cluster'){?>
			<script src="js/markerclusterer.js"></script>
			<style>
			  #map-container {
				width: 100%;
			  }
			  #map {
				width: 1200px;
				width: 100%;
				position:relative;
				height: 400px;
			  }
			</style>
		<? } ?>

		<!-- end heatmap
		<script src="vendor/jquery/jquery.js"></script>		
		<script src="vendor/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" type="text/css" media="screen" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />-->
	<script src="vendor/jquery/jquery.js"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
	<!--<script src="vendor/bootstrap/js/bootstrap.js"></script>-->
	
    <!--<script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
     <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
	<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
    <script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
    -->
	<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>- ->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
	<! --<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />- ->
		<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">-->

	<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
	<!----><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
	<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">	
	<style>
		canvas {
			-moz-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}
	</style>	
	</head>
	<body>
	<?
	echo 'showtime:'.showTime(365);
	?>
        <form id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();" novalidate="novalidate">
		<!-- <form autocomplete="off" id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();"  class='form-validate'><input type="hidden" name="Command" id="Command" value="-1"> -->
		<input type="hidden" name="Command" id="Command" value="-1">
		
		<section class="body">

			<!-- start: header -->
			<header class="header">
				<div class="logo-container">
					<a href="index.php" class="logo" style="margin:2px 0 0 10px;">
						<img src="images/Smart_Speech_logo.png" width="60" alt="SmartSpeech" />
					</a>
					<div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fas fa-bars" aria-label="Toggle sidebar"></i>
					</div>
				</div>
			
				<!-- start: search & user box -->
				<div class="header-right">
					<!-- 
					<div action="pages-search-results.html" class="search nav-form">
						<div class="input-group">
							<input type="text" class="form-control" name="q" id="q" placeholder="Search...">
							<span class="input-group-append">
								<button class="btn btn-default" type="submit"><i class="fas fa-search"></i></button>
							</span>
						</div>
					</div>
					-->

			
					<span class="separator"></span>
			
					<ul class="notifications">
						<li>
							<a href="index.php?com=messageslist" class="dropdown-toggle notification-icon">
								<i class="fas fa-envelope"></i>
								<span class="badge">
								<?
								$totalUnread=$db->RowSelectorQuery("SELECT count(*) AS totalUnread FROM messages WHERE user_id=".$auth->UserId." AND readed='False'");
								echo intval($totalUnread['totalUnread']);
								?>
								</span>
							</a>
						</li>
						<li>
							<a href="index.php?com=notificationslist" class="dropdown-toggle notification-icon"><!-- data-toggle="dropdown"-->
								<i class="fas fa-bell"></i>
									<?
										$totalNotifications=$db->RowSelectorQuery("SELECT count(*) AS totalAll FROM notifications WHERE is_valid='True'");
										$totalRead=$db->RowSelectorQuery("SELECT count(*) AS totalRead FROM notifications t1 LEFT JOIN notificationread t2 ON t1.notification_id=t2.notification_id WHERE t1.is_valid='True' AND t2.user_id=".$auth->UserRow['user_id']);
										//echo "SELECT count(*) AS totalRead FROM notifications t1 LEFT JOIN notificationread t2 ON t1.notification_id=t2.notification_id WHERE t1.is_valid='True' AND t2.user_id=".$auth->UserRow['user_id'];
									?>
								<span class="badge"><?=($totalNotifications['totalAll']-$totalRead['totalRead'])?></span>
							</a>
						</li>
					</ul>
			
					<span class="separator"></span>
			
					<div id="userbox" class="userbox">
						<a href="#" data-toggle="dropdown">
							<figure class="profile-picture">
								<img src="img/logo-person.png" alt="Joseph Doe" class="rounded-circle" data-lock-picture="img/!logged-user.jpg" />
							</figure>
							<div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
								<span class="name"><?=$auth->UserRow['user_fullname']?></span>
								<span class="role"><?=$auth->UserRow['user_auth']?></span>
							</div>
			
							<i class="fa custom-caret"></i>
						</a>
			
						<div class="dropdown-menu">
							<ul class="list-unstyled mb-2">
								<li class="divider"></li>
								<li>
									<a role="menuitem" tabindex="-1" href="index.php?com=profile"><i class="fas fa-user"></i> Προφιλ</a>
								</li>
								<!--<li><a role="menuitem" tabindex="-1" href="#" data-lock-screen="true"><i class="fas fa-lock"></i> Lock Screen</a></li>-->
								<li><a role="menuitem" tabindex="-1" href="index.php?com=login&logout=true"><i class="fas fa-power-off"></i> Αποσύνδεση</a></li>
							</ul>
						</div>
					</div>
				</div>
				<!-- end: search & user box -->
			</header>
			<!-- end: header -->

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<aside id="sidebar-left" class="sidebar-left">
				
				    <div class="sidebar-header">
				        <div class="sidebar-title">
				            Επιλογές
				        </div>
						
				        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
				            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
				        </div>
				    </div>
				
				    <div class="nano">
				        <div class="nano-content">
				            <nav id="menu" class="nav-main" role="navigation">
				            
				                <ul class="nav nav-main">
									<!-- 
				                    <li>
				                        <a class="nav-link" href="index.php">
				                            <i class="fas fa-home" aria-hidden="true"></i><span>Αρχική</span>
				                        </a>                        
				                    </li>
									-->
									<? if (($permissions & $FLAG_600) || $auth->UserType == "Administrator") {?>
				                    <li>
				                        <a class="nav-link" href="index.php?com=children">
				                            <i class="fas fa-child" aria-hidden="true"></i><span>Παιδιά</span>
				                        </a>                        
				                    </li>
									<? } ?>
									<? //if($auth->UserType == "Administrator"){?>
									<? if (($permissions & $FLAG_000) || $auth->UserType == "Administrator") {?>
				                    <li>
				                        <a class="nav-link" href="index.php?com=users">
				                            <i class="fas fa-user" aria-hidden="true"></i><span>Χρήστες</span>
				                        </a>                        
				                    </li>
									<? } ?>
									<? //} ?>

									<? if (($permissions & $FLAG_200) || $auth->UserType == "Administrator") {?>
				                    <li>
				                        <a class="nav-link" href="index.php?com=roles">
				                            <i class="fas fa-tasks" aria-hidden="true"></i><span>Ρόλοι</span>
				                        </a>                        
				                    </li>
									<? } ?>
									<!--
				                    <li>
				                        <a class="nav-link" href="index.php?com=passwords">
				                            <i class="fas fa-lock" aria-hidden="true"></i><span>Κωδικοί</span>
				                        </a>                        
				                    </li>
									-->
									<? //if($auth->UserType == "Administrator"){?>
									<? if (($permissions & $FLAG_300) || $auth->UserType == "Administrator") {?>
				                    <li>
										<? if($auth->UserType == "Administrator"){?>
											<a class="nav-link" href="index.php?com=messages">
										<?} else{?>
											<a class="nav-link" href="index.php?com=messageslist">
										<? } ?>
				                            <!-- <span class="float-right badge badge-primary">15</span> -->
				                            <i class="fas fa-envelope" aria-hidden="true"></i><span>Μηνύματα</span>
				                        </a>                        
				                    </li>
									<? } ?>
									<? if (($permissions & $FLAG_400) || $auth->UserType == "Administrator") {?>
				                    <li>
										<? if($auth->UserType == "Administrator"){?>
											<a class="nav-link" href="index.php?com=notifications">
										<?} else{?>
											<a class="nav-link" href="index.php?com=notificationslist">
										<? } ?>
				                
				                            <!-- <span class="float-right badge badge-primary">2</span> -->
				                            <i class="fas fa-bell" aria-hidden="true"></i><span>Ειδοποιήσεις</span>
				                        </a>                        
				                    </li>
									<? } ?>
									
									<? if (($permissions & $FLAG_100) || $auth->UserType == "Administrator") {?>
				                    <li>
				                        <a class="nav-link" href="index.php?com=results">
				                            <i class="fas fa-chart-pie" aria-hidden="true"></i><span>Αποτελέσματα</span>
				                        </a>                        
				                    </li>
									<? } ?>
									
									<? if (($permissions & $FLAG_500) || $auth->UserType == "Administrator") {?>
				                    <li>
				                        <a class="nav-link" href="index.php?com=info">
				                            <i class="fas fa-info" aria-hidden="true"></i><span>Οδηγίες</span>
				                        </a>                        
				                    </li>
									<? } ?>

									<? if (($permissions & $FLAG_700) || $auth->UserType == "Administrator") {?>
				                    <li>
				                        <a class="nav-link" href="https://www.surveymonkey.com/r/YLV6JGK" target="_blank">
				                            <i class="fas fa-poll" aria-hidden="true"></i><span>Αξιολόγηση</span>
				                        </a>                        
				                    </li>
									<? } ?>
									<? if($auth->UserType == "Administrator"){?>
									<!--
				                    <li class="nav-parent">
				                        <a class="nav-link" href="#">
				                            <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
				                            <span>Χάρτες</span>
				                        </a>
				                        <ul class="nav nav-children">
				                            <li><a class="nav-link" href="index.php?com=heatmap">Heatmap</a></li>
											<li><a class="nav-link" href="index.php?com=analogcluster">Analog cluster</a></li>
											<li><a class="nav-link" href="index.php?com=cluster">Cluster</a></li>
				                        </ul>
				                    </li>
				                    <li class="nav-parent nav-expanded nav-active">
				                        <a class="nav-link" href="index.php?com=packages">
				                            <i class="fas fa-columns" aria-hidden="true"></i><span>Πακέτα</span>
				                        </a>
				                    </li>
									-->
									<? } ?>

				
				                </ul>
				            </nav>
							<!-- 
				            <hr class="separator" />
				            <div class="sidebar-widget widget-tasks">
				                <div class="widget-header">
				                    <h6>Projects</h6>
				                    <div class="widget-toggle">+</div>
				                </div>
				                <div class="widget-content">
				                    <ul class="list-unstyled m-0">
				                        <li><a href="#">Porto HTML5 Template</a></li>
				                        <li><a href="#">Tucson Template</a></li>
				                        <li><a href="#">Porto Admin</a></li>
				                    </ul>
				                </div>
				            </div>
							-->

							<!-- 
				            <hr class="separator" />
				
				            <div class="sidebar-widget widget-stats">
				                <div class="widget-header">
				                    <h6>Company Stats</h6>
				                    <div class="widget-toggle">+</div>
				                </div>
				                <div class="widget-content">
				                    <ul>
				                        <li>
				                            <span class="stats-title">Stat 1</span>
				                            <span class="stats-complete">85%</span>
				                            <div class="progress">
				                                <div class="progress-bar progress-bar-primary progress-without-number" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 85%;">
				                                    <span class="sr-only">85% Complete</span>
				                                </div>
				                            </div>
				                        </li>
				                        <li>
				                            <span class="stats-title">Stat 2</span>
				                            <span class="stats-complete">70%</span>
				                            <div class="progress">
				                                <div class="progress-bar progress-bar-primary progress-without-number" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%;">
				                                    <span class="sr-only">70% Complete</span>
				                                </div>
				                            </div>
				                        </li>
				                        <li>
				                            <span class="stats-title">Stat 3</span>
				                            <span class="stats-complete">2%</span>
				                            <div class="progress">
				                                <div class="progress-bar progress-bar-primary progress-without-number" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="width: 2%;">
				                                    <span class="sr-only">2% Complete</span>
				                                </div>
				                            </div>
				                        </li>
				                    </ul>
				                </div>
				            </div>
							-->
				        </div>
				
				        <script>
				            // Maintain Scroll Position
				            if (typeof localStorage !== 'undefined') {
				                if (localStorage.getItem('sidebar-left-position') !== null) {
				                    var initialPosition = localStorage.getItem('sidebar-left-position'),
				                        sidebarLeft = document.querySelector('#sidebar-left .nano-content');
				                    
				                    sidebarLeft.scrollTop = initialPosition;
				                }
				            }
				        </script>
				        
				
				    </div>
				
				</aside>
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<header class="page-header">
						<h2>SmartSpeech</h2>
						<div class="right-wrapper text-right">
							<ol class="breadcrumbs" style="margin-right:50px;">
								<li>
									<a href="index.php">
										<i class="fas fa-home"></i>
									</a>
								</li>
								<li><span><?=$config["navigation"]?></span></li>
							</ol>
							<!-- <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a> -->
						</div>
					</header>

					<? if(!isset($_GET['com'])){?>
					<div class="row">
						<div class="col-lg-12 mb-12">
							<section class="card">
								<div class="card-body">
								Εφαρμογή smartspeech<br>
								<img style="width:100%;" src="/images/home_img.png">
								</div>
							</section>
						</div>
					</div>
					<!--
					<div class="row">
						<div class="col-lg-6 mb-3">
							<section class="card">
								<div class="card-body">
									<div class="row">
										<div class="col-xl-8">
											<div class="chart-data-selector" id="salesSelectorWrapper">
												<h2>
													Sales:
													<strong>
														<select class="form-control" id="salesSelector">
															<option value="Porto Admin" selected>Admin</option>
														</select>
													</strong>
												</h2>
					
												<div id="salesSelectorItems" class="chart-data-selector-items mt-3">
													<!-- Flot: Sales Porto Admin -->
													<!-- 
													<div class="chart chart-sm" data-sales-rel="Porto Admin" id="flotDashSales1" class="chart-active" style="height: 203px;"></div>
													<script>
					
														var flotDashSales1Data = [{
														    data: [
														        ["Jan", 140],
														        ["Feb", 240],
														        ["Mar", 190],
														        ["Apr", 140],
														        ["May", 180],
														        ["Jun", 320],
														        ["Jul", 270],
														        ["Aug", 180]
														    ],
														    color: "#0088cc"
														}];
					
														// See: js/examples/examples.dashboard.js for more settings.
					
													</script>
					
											
													<div class="chart chart-sm" data-sales-rel="Porto Drupal" id="flotDashSales2" class="chart-hidden"></div>
													<script>
					
														var flotDashSales2Data = [{
														    data: [
														        ["Jan", 240],
														        ["Feb", 240],
														        ["Mar", 290],
														        ["Apr", 540],
														        ["May", 480],
														        ["Jun", 220],
														        ["Jul", 170],
														        ["Aug", 190]
														    ],
														    color: "#2baab1"
														}];
					
														// See: js/examples/examples.dashboard.js for more settings.
					
													</script>
					
										
													<div class="chart chart-sm" data-sales-rel="Porto Wordpress" id="flotDashSales3" class="chart-hidden"></div>
													<script>
					
														var flotDashSales3Data = [{
														    data: [
														        ["Jan", 840],
														        ["Feb", 740],
														        ["Mar", 690],
														        ["Apr", 940],
														        ["May", 1180],
														        ["Jun", 820],
														        ["Jul", 570],
														        ["Aug", 780]
														    ],
														    color: "#734ba9"
														}];
					
														// See: js/examples/examples.dashboard.js for more settings.
					
													</script>
												</div>
					
											</div>
										</div>
										<div class="col-xl-4 text-center">
											<h2 class="card-title mt-3">Sales Goal</h2>
											<div class="liquid-meter-wrapper liquid-meter-sm mt-3">
												<div class="liquid-meter">
													<meter min="0" max="100" value="35" id="meterSales"></meter>
												</div>
												<div class="liquid-meter-selector mt-4 pt-1" id="meterSalesSel">
													<a href="#" data-val="35" class="active">Monthly Goal</a>
													<a href="#" data-val="28">Annual Goal</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>
						<div class="col-lg-6">
							<div class="row mb-3">
								<div class="col-xl-6">
									<section class="card card-featured-left card-featured-primary mb-3">
										<div class="card-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-primary">
														<i class="fas fa-life-ring"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Support Questions</h4>
														<div class="info">
															<strong class="amount">1281</strong>
															<span class="text-primary">(14 unread)</span>
														</div>
													</div>
													<div class="summary-footer">
														<a class="text-muted text-uppercase" href="#">(view all)</a>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="col-xl-6">
									<section class="card card-featured-left card-featured-secondary">
										<div class="card-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-secondary">
														<i class="fas fa-dollar-sign"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Total Profit</h4>
														<div class="info">
															<strong class="amount">$ 14,890.30</strong>
														</div>
													</div>
													<div class="summary-footer">
														<a class="text-muted text-uppercase" href="#">(withdraw)</a>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
							</div>
							<div class="row">
								<div class="col-xl-6">
									<section class="card card-featured-left card-featured-tertiary mb-3">
										<div class="card-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-tertiary">
														<i class="fas fa-shopping-cart"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Today's Orders</h4>
														<div class="info">
															<strong class="amount">38</strong>
														</div>
													</div>
													<div class="summary-footer">
														<a class="text-muted text-uppercase" href="#">(statement)</a>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
								<div class="col-xl-6">
									<section class="card card-featured-left card-featured-quaternary">
										<div class="card-body">
											<div class="widget-summary">
												<div class="widget-summary-col widget-summary-col-icon">
													<div class="summary-icon bg-quaternary">
														<i class="fas fa-user"></i>
													</div>
												</div>
												<div class="widget-summary-col">
													<div class="summary">
														<h4 class="title">Today's Visitors</h4>
														<div class="info">
															<strong class="amount">3765</strong>
														</div>
													</div>
													<div class="summary-footer">
														<a class="text-muted text-uppercase" href="#">(report)</a>
													</div>
												</div>
											</div>
										</div>
									</section>
								</div>
							</div>
						</div>
					</div>
					
					
					<div class="row pt-4">
						<div class="col-lg-6 mb-4 mb-lg-0">
							<section class="card">
								<header class="card-header">
									<div class="card-actions">
										<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
										<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
									</div>
					
									<h2 class="card-title">Best Seller</h2>
									<p class="card-subtitle">Customize the graphs as much as you want, there are so many options and features to display information using Porto Admin Template.</p>
								</header>
								<div class="card-body">
					
									<div class="chart chart-md" id="flotDashBasic"></div>
									<script>
					
										var flotDashBasicData = [{
											data: [
												[0, 170],
												[1, 169],
												[2, 173],
												[3, 188],
												[4, 147],
												[5, 113],
												[6, 128],
												[7, 169],
												[8, 173],
												[9, 128],
												[10, 128]
											],
											label: "Series 1",
											color: "#0088cc"
										}, {
											data: [
												[0, 115],
												[1, 124],
												[2, 114],
												[3, 121],
												[4, 115],
												[5, 83],
												[6, 102],
												[7, 148],
												[8, 147],
												[9, 103],
												[10, 113]
											],
											label: "Series 2",
											color: "#2baab1"
										}, {
											data: [
												[0, 70],
												[1, 69],
												[2, 73],
												[3, 88],
												[4, 47],
												[5, 13],
												[6, 28],
												[7, 69],
												[8, 73],
												[9, 28],
												[10, 28]
											],
											label: "Series 3",
											color: "#734ba9"
										}];
					
										// See: js/examples/examples.dashboard.js for more settings.
					
									</script>
					
								</div>
							</section>
						</div>
						<div class="col-lg-6">
							<section class="card">
								<header class="card-header">
									<div class="card-actions">
										<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
										<a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
									</div>
									<h2 class="card-title">Server Usage</h2>
									<p class="card-subtitle">It's easy to create custom graphs on Porto Admin Template, there are several graph types that you can use.</p>
								</header>
								<div class="card-body">
									<div class="chart chart-md" id="flotDashRealTime"></div>
								</div>
							</section>
						</div>
					</div>
					-->
					<? } else {?>
                    <div class="content" style="margin-top:10px;">
	                    <?=$components->RenderRequestComponent(); ?>
					</div>
					<? } ?>
					<!-- end: page -->
				</section>
			</div>

		</section>
		</form>
		<!-- Vendor -->
		
		<script language="javascript" type="text/javascript" src="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/func.js"></script>
		<script src="vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="vendor/popper/umd/popper.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.js"></script>
		<!-- 
		<script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		-->
		
		<script src="vendor/common/common.js"></script>
		<script src="vendor/nanoscroller/nanoscroller.js"></script>
		<script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		<!-- Specific Page Vendor -->
		<script src="vendor/jquery-ui/jquery-ui.js"></script>
		<script src="vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js"></script>
		<script src="vendor/jquery-appear/jquery.appear.js"></script>
		<script src="vendor/bootstrap-multiselect/js/bootstrap-multiselect.js"></script>
		<script src="vendor/jquery.easy-pie-chart/jquery.easypiechart.js"></script>
		<script src="vendor/flot/jquery.flot.js"></script>
		<script src="vendor/flot.tooltip/jquery.flot.tooltip.js"></script>
		<script src="vendor/flot/jquery.flot.pie.js"></script>
		<script src="vendor/flot/jquery.flot.categories.js"></script>
		<script src="vendor/flot/jquery.flot.resize.js"></script>
		<script src="vendor/jquery-sparkline/jquery.sparkline.js"></script>
		<script src="vendor/raphael/raphael.js"></script>
		<script src="vendor/morris/morris.js"></script>
		<script src="vendor/gauge/gauge.js"></script>
		<script src="vendor/snap.svg/snap.svg.js"></script>
		<script src="vendor/liquid-meter/liquid.meter.js"></script>
		<script src="vendor/jqvmap/jquery.vmap.js"></script>
		<script src="vendor/jqvmap/data/jquery.vmap.sampledata.js"></script>
		<script src="vendor/jqvmap/maps/jquery.vmap.world.js"></script>
		<script src="vendor/jqvmap/maps/continents/jquery.vmap.africa.js"></script>
		<script src="vendor/jqvmap/maps/continents/jquery.vmap.asia.js"></script>
		<script src="vendor/jqvmap/maps/continents/jquery.vmap.australia.js"></script>
		<script src="vendor/jqvmap/maps/continents/jquery.vmap.europe.js"></script>
		<script src="vendor/jqvmap/maps/continents/jquery.vmap.north-america.js"></script>
		<script src="vendor/jqvmap/maps/continents/jquery.vmap.south-america.js"></script>

		<script src="vendor/jquery-validation/jquery.validate.js"></script>
		<script src="vendor/bootstrap-wizard/jquery.bootstrap.wizard.js"></script>
		<script src="js/examples/examples.wizard.js"></script>
		<!-- Theme Base, Components and Settings -->
		<script src="js/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="js/custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="js/theme.init.js"></script>

		<!-- Examples -->
		<script src="js/examples/examples.dashboard.js"></script>

	</body>
	<? $validator->RenderValidators();?>
	<? $messages->RenderMessages();?>
</html>