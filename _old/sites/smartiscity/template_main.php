<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>

<?

session_start();
	//include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."languages/gr.php");
	//include($config["physicalPath"]."/perm.php");
	//$permissions = (intval($auth->UserRow['access'])>0?$auth->UserRow['access']:0);
	
	if(!isset($config["navigation"])) $config["navigation"] = appTitle;
?>
<?
	//LoadNoCacheHeader();
	//LoadCharSetHeader();
?>
<!DOCTYPE html>
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

		
		<link rel="stylesheet" type="text/css" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/theme.css">
        <script type="text/javascript" src="/gms/client_scripts/ajax/ajaxAgent.js"></script>
        <script type="text/javascript">var ajaxUrl= "<?=$config["ajaxUrl"]?>"</script>
		<script type="text/javascript" src="/gms/client_scripts/swfobject/swfobject.js"></script>
		<!-- Basic -->
		<meta charset="UTF-8">
		<meta name="author" content="dotsoft">
		
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

		<link rel="stylesheet" href="css/style.css">
		<!-- 
			<link rel="stylesheet" href="css/leaflet.css">
		-->
		
		<link rel="stylesheet" href="css/main-color.css" id="colors">
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Commissioner:wght@100&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">		
		<link href='css/prettify.css' rel='stylesheet' type='text/css' />
		
		<script type="text/javascript" src="scripts/jquery-3.5.1.min.js"></script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/core.js"></script>
		<script language="javascript" type="text/javascript" src="gms/client_scripts/public.js"></script>
		<script language="javascript" type="text/javascript" src='<?=$config["siteurl"]?>sites/<?=$config["site"]?>/js/func.js'></script>

		<script type="text/javascript" src="js/prettify.js"></script>
		<? if($_GET['com']!='search' && $_GET['com']!='preview'){
			?>
				<script async  
					src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCK7sql4zSHreVAdLPH7t8JoNE1gy5Tuyk" type="text/javascript">
				</script>
				<link rel="stylesheet" type="text/css" href="/css/jquery-gmaps-latlon-picker.css"/>
				<script src="/js/jquery-gmaps-latlon-picker.js"></script>
			<?
		}
		?>
		<? if(isset($_GET['com']) && $_GET['com']=='preview2'){?>
			<!-- 
			<link href="/map/css/app.cd608ae5.css" rel="preload" as="style">
			<link href="/map/css/chunk-vendors.f0e361c3.css" rel="preload" as="style">
			-->

			<link href="/map/js/app.7f97ad73.js" rel="preload" as="script">
			<link href="/map/js/chunk-vendors.72ba3fca.js" rel="preload" as="script">
			<link href="/map/css/chunk-vendors.f0e361c3.css" rel="stylesheet">
			<link href="/map/css/app.cd608ae5.css" rel="stylesheet">
		<? } ?>
</head>
<body style="height:100%;<?=($_GET['com']==''?';':'')?>"><!-- font-family: 'Commissioner', sans-serif;-->
	<!-- 
<form id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();" novalidate="novalidate">
	
	-->
	<!-- <form autocomplete="off" id="__PageForm" name="__PageForm" method="post" enctype="multipart/form-data" onSubmit="return PageIsValid();"  class='form-validate'><input type="hidden" name="Command" id="Command" value="-1"> -->
	<input type="hidden" name="Command" id="Command" value="-1">
<!-- Wrapper -->
<div id="wrapper">

<!-- Header Container
================================================== -->

<?
$cssForHEader = "";
if(isset($_GET["com"]) && $_GET["com"] == "search")
{
	$cssForHEader = "fixed ";
}
?>
<header id="header-container" class="<?=$cssForHEader?>fullwidth">
	<!-- Header -->
	<div id="header" data-background-color="#000" style="margin-bottom:20px;box-shadow:none;">
		<div class="container">
			<!-- Left Side Content -->
			<div class="left-side">
				<!-- Logo -->
				<div id="logo">
					<!-- <a href="index.php"><img src="images/logo2.png" data-sticky-logo="images/logo.png" alt=""></a>-->
					<a href="index.php"><img style="width:300px;" src="gallery/cities/1.png" data-sticky-logo="gallery/cities/1.png" alt=""></a>
				</div>
				<!-- 
				<div class="mmenu-trigger">
					<button class="hamburger hamburger--collapse" type="button">
						<span class="hamburger-box">
							<span class="hamburger-inner"></span>
						</span>
					</button>
				</div>
				<div class="clearfix"></div>
				-->
			</div>
			<span style="float: right;margin-right: 30px;">
				<img src="../gallery/espa_drama.png" style="height:40px;">
			</span>
		</div>
	</div>

</header>
<div class="clearfix"></div>
	<? 
	if(isset($_GET['com'])){
		$components->RenderRequestComponent(); 
	} else {
	?>
	
<section class="fullwidth margin-top-0 padding-top-0 padding-bottom-40"  style="background-image: url(gallery/cities/drama1.jpg);background-repeat: no-repeat;background-size: cover;min-height:94vh;">
	<div class="container">
		<div class="row">
			<!-- 
			<div class="col-md-12">
				<h3 class="headline margin-top-75">
					<strong class="headline-with-separator">Κατηγορίες</strong>
				</h3>
			</div>
			-->
			<div class="col-md-12">
				<div class="categories-boxes-container-alt margin-top-5 margin-bottom-30">
					
					<!-- Box -->
					
					
					<a href="index.php?com=search&type=3" class="category-small-box-alt" style="background-color:#357BEB; color:#fff;">
						<i class="im im-icon-Wave-2" style="color:#fff;"></i>
						<h4 style="color:#fff;font-family: 'Manrope', sans-serif;font-size:14px;margin-top:25px;">Δεδομένα Ροής Υδάτων</h4>
					</a>
					
					<a href="index.php?com=search&type=6" class="category-small-box-alt" style="background-color:#00A1FF; color:#fff;">
						<i class="im im-icon-Drop" style="color:#fff;"></i>
						<h4 style="color:#fff;font-family: 'Manrope', sans-serif;font-size:14px;margin-top:25px;">Δεδομένα Ποιότητας Υδάτων</h4>
					</a>
					
					<a href="index.php?com=search&type=1" class="category-small-box-alt" style="background-color:#33D553; color:#fff;">
						<i class="im im-icon-Globe" style="color:#fff;"></i>
						<h4 style="color:#fff;font-family: 'Manrope', sans-serif;font-size:14px;margin-top:25px;font-weight:500;">Περιβαλλοντικά Δεδομένα</h4>
					</a>

					<!-- Box -->
					<a href="index.php?com=search&type=2" class="category-small-box-alt" style="background-color:#FB5606; color:#fff;">
						<i class="im im-icon-Business-Mens" style="color:#fff;"></i>
						<h4 style="color:#fff;font-family: 'Manrope', sans-serif;font-size:14px;margin-top:25px;">Δεδομένα Συνάθροισης Κοινού</h4>
					</a>

					<!-- Box 
					<a href="index.php?com=search&type=5" class="category-small-box-alt" style="background-color:#FFBE0A; color:#fff;">
						<i class="im im-icon-Cloud-Sun" style="color:#fff;"></i>
						<h4 style="color:#fff;font-family: 'Manrope', sans-serif;font-size:14px;margin-top:25px;">Μετεωρολογικά Δεδομένα</h4>
					</a>
					-->
					<!-- Box -->
					<a href="index.php?com=search&type=4" class="category-small-box-alt" style="background-color:#FF006E; color:#fff;">
						<i class="im im-icon-Wifi" style="color:#fff;"></i>
						<h4 style="color:#fff;font-family: 'Manrope', sans-serif;font-size:14px;margin-top:25px;">WiFi Hotspots</h4>
					</a>
					
					<!-- Box 
					<a href="index.php?com=search&type=5" class="category-small-box-alt" style="background-color:#FFBE0A; color:#fff;width:calc(100% * (4/5) - 25px)">
						<i class="im im-icon-Cloud-Sun" style="color:#fff;"></i>
						<table class="table table-responsive">
							<tbody>
								<tr>
									<td style="width:25%">Θερμοκρασία: 12C</td>
									<td style="width:25%">Υγρασία: 123Ν</td>
									<td style="width:25%">Κατεύθυνση ανέμου:22Κ</td>
									<td style="width:25%">Δείκτης UV: 11Α</td>
								</tr>
								<tr>
									<td style="width:25%">Ταχύτητα ανέμου: 12C</td>
									<td style="width:25%">Βροχόπτωση τελευτ. ώρας: 123Ν</td>
									<td style="width:25%">Δυνατότερος άνεμος:22Κ</td>
									<td style="width:25%">Μηνιαία βροχόπτωση: 11Α</td>
								</tr>
								<tr>
									<td>Αίσθηση θερμοκρασίας</td>
									<td>Πίεση</td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</a>
					-->
					<a href="index.php?com=search&type=5" class="category-small-box-alt" style="background-color:#FFBE0A; color:#fff;width:calc(100% * (5/5) - 25px)">
						<i class="im im-icon-Cloud-Sun" style="color:#fff;"></i>
						<div class="class="row">
							<div class="col-lg-3 col-md-4 col-md-6">
							<? $row1=$db->RowSelectorQuery("SELECT max(t1.date_insert),t2.sensorvar_unit,t1.measurement,t2.sensorvar_description FROM `measurements` t1 INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id WHERE t1.sensor_id=10 AND t1.parameter_id=27 GROUP BY t1.sensor_id,t1.parameter_id");
								echo $row1['sensorvar_description'].': '.number_format($row1['measurement'],2).$row1['sensorvar_unit'];
							?>
							</div>
							<div class="col-lg-3 col-md-4 col-md-6">
							<? $row1=$db->RowSelectorQuery("SELECT max(t1.date_insert),t2.sensorvar_unit,t1.measurement,t2.sensorvar_description FROM `measurements` t1 INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id WHERE t1.sensor_id=10 AND t1.parameter_id=28 GROUP BY t1.sensor_id,t1.parameter_id");
								echo $row1['sensorvar_description'].': '.number_format($row1['measurement'],2).$row1['sensorvar_unit'];
							?>
							</div>
							<div class="col-lg-3 col-md-4 col-md-6">
							<? $row1=$db->RowSelectorQuery("SELECT max(t1.date_insert),t2.sensorvar_unit,t1.measurement,t2.sensorvar_description FROM `measurements` t1 INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id WHERE t1.sensor_id=10 AND t1.parameter_id=29 GROUP BY t1.sensor_id,t1.parameter_id");
								echo $row1['sensorvar_description'].': '.number_format($row1['measurement'],2).$row1['sensorvar_unit'];
							?>
							</div>
							<div class="col-lg-3 col-md-4 col-md-6">
							<? $row1=$db->RowSelectorQuery("SELECT max(t1.date_insert),t2.sensorvar_unit,t1.measurement,t2.sensorvar_description FROM `measurements` t1 INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id WHERE t1.sensor_id=10 AND t1.parameter_id=30 GROUP BY t1.sensor_id,t1.parameter_id");
								echo $row1['sensorvar_description'].': '.number_format($row1['measurement'],2).$row1['sensorvar_unit'];
							?>
							</div>
						</div>
						<div class="class="row">
							<div class="col-lg-3 col-md-4 col-md-6">
							<? $row1=$db->RowSelectorQuery("SELECT max(t1.date_insert),t2.sensorvar_unit,t1.measurement,t2.sensorvar_description FROM `measurements` t1 INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id WHERE t1.sensor_id=10 AND t1.parameter_id=31 GROUP BY t1.sensor_id,t1.parameter_id");
								echo $row1['sensorvar_description'].': '.number_format($row1['measurement'],2).$row1['sensorvar_unit'];
							?>
							</div>
							<div class="col-lg-3 col-md-4 col-md-6">
							<? $row1=$db->RowSelectorQuery("SELECT max(t1.date_insert),t2.sensorvar_unit,t1.measurement,t2.sensorvar_description FROM `measurements` t1 INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id WHERE t1.sensor_id=10 AND t1.parameter_id=32 GROUP BY t1.sensor_id,t1.parameter_id");
								echo $row1['sensorvar_description'].': '.number_format($row1['measurement'],2).$row1['sensorvar_unit'];
							?>
							</div>
							<div class="col-lg-3 col-md-4 col-md-6">
							<? $row1=$db->RowSelectorQuery("SELECT max(t1.date_insert),t2.sensorvar_unit,t1.measurement,t2.sensorvar_description FROM `measurements` t1 INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id WHERE t1.sensor_id=10 AND t1.parameter_id=33 GROUP BY t1.sensor_id,t1.parameter_id");
								echo $row1['sensorvar_description'].': '.number_format($row1['measurement'],2).$row1['sensorvar_unit'];
							?>
							</div>
							<div class="col-lg-3 col-md-4 col-md-6">
							<? $row1=$db->RowSelectorQuery("SELECT max(t1.date_insert),t2.sensorvar_unit,t1.measurement,t2.sensorvar_description FROM `measurements` t1 INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id WHERE t1.sensor_id=10 AND t1.parameter_id=34 GROUP BY t1.sensor_id,t1.parameter_id");
								echo $row1['sensorvar_description'].': '.number_format($row1['measurement'],2).$row1['sensorvar_unit'];
							?>
							</div>
						</div>	
						<div class="class="row">
							<div class="col-lg-3 col-md-4 col-md-6">
							<? $row1=$db->RowSelectorQuery("SELECT max(t1.date_insert),t2.sensorvar_unit,t1.measurement,t2.sensorvar_description FROM `measurements` t1 INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id WHERE t1.sensor_id=10 AND t1.parameter_id=35 GROUP BY t1.sensor_id,t1.parameter_id");
								echo $row1['sensorvar_description'].': '.number_format($row1['measurement'],2).$row1['sensorvar_unit'];
							?>
							</div>
							<div class="col-lg-3 col-md-4 col-md-6">
							<? $row1=$db->RowSelectorQuery("SELECT max(t1.date_insert),t2.sensorvar_unit,t1.measurement,t2.sensorvar_description FROM `measurements` t1 INNER JOIN sensorvars t2 ON t1.parameter_id=t2.sensorvar_id WHERE t1.sensor_id=10 AND t1.parameter_id=36 GROUP BY t1.sensor_id,t1.parameter_id");
								echo $row1['sensorvar_description'].': '.number_format($row1['measurement'],2).$row1['sensorvar_unit'];
							?>
							</div>
							<div class="col-lg-3 col-md-4 col-md-6">
								
							</div>
							<div class="col-lg-3 col-md-4 col-md-6">
								
							</div>
						</div>	
									
						
					</a>



				</div>
			</div>
		</div>
	</div>
</section>

	<!-- 
		<section class="fullwidth padding-top-75 padding-bottom-70" data-background-color="#f9f9f9" >
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<h3 class="headline centered headline-extra-spacing">
							<strong class="headline-with-separator">Plan The Vacation of Your Dreams</strong>
							<span class="margin-top-25">Explore some of the best tips from around the world from our partners and friends. Discover some of the most popular listings!</span>
						</h3>
					</div>
				</div>

				<div class="row icons-container">
					<div class="col-md-4">
						<div class="icon-box-2 with-line">
							<i class="im im-icon-Map2"></i>
							<h3>Κυκλοφορία</h3>
							<p>Proin dapibus nisl ornare diam varius tempus. Aenean a quam luctus, finibus tellus ut, convallis eros sollicitudin.</p>
						</div>
					</div>

					<div class="col-md-4">
						<div class="icon-box-2 with-line">
							<i class="im im-icon-Mail-withAtSign"></i>
							<h3>Συνάθροιση κοινού</h3>
							<p>Maecenas pulvinar, risus in facilisis dignissim, quam nisi hendrerit nulla, id vestibulum metus nullam viverra purus.</p>
						</div>
					</div>

					<div class="col-md-4">
						<div class="icon-box-2">
							<i class="im im-icon-Checked-User"></i>
							<h3>Μετεωρολογικά δεδομένα</h3>
							<p>Faucibus ante, in porttitor tellus blandit et. Phasellus tincidunt metus lectus sollicitudin feugiat consectetur.</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="icon-box-2">
							<i class="im im-icon-Checked-User"></i>
							<h3>Σωματίδια</h3>
							<p>Faucibus ante, in porttitor tellus blandit et. Phasellus tincidunt metus lectus sollicitudin feugiat consectetur.</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="icon-box-2">
							<i class="im im-icon-Checked-User"></i>
							<h3>Ποιότητα αέρα</h3>
							<p>Faucibus ante, in porttitor tellus blandit et. Phasellus tincidunt metus lectus sollicitudin feugiat consectetur.</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="icon-box-2">
							<i class="im im-icon-Checked-User"></i>
							<h3>Διάφορα</h3>
							<p>Faucibus ante, in porttitor tellus blandit et. Phasellus tincidunt metus lectus sollicitudin feugiat consectetur.</p>
						</div>
					</div>
				</div>

			</div>
		</section>
	-->

	<? } ?>



<?
if(isset($_GET["com"]) && $_GET["com"] != "search")
{
?>

<!-- Footer
================================================== -->
<div id="footer" class="sticky-footer" style="
/*position: fixed;*/
border-top:none;
  left: 0;
  bottom: 0;
  width: 100%;
  color: white;
  text-align: center;
  padding:0;
  ">
	<!-- Main -->
	<div class="container">
		<!-- Copyright -->
		<div class="row">
			<div class="col-md-12">
				<div class="copyrights">© 2021 Dotsoft. All Rights Reserved.</div>
			</div>
		</div>

	</div>

</div>
<!-- Footer / End -->

<!-- Back To Top Button -->
<div id="backtotop"><a href="#"></a></div>
<?
}
?>

</div>
<!-- Wrapper / End -->

<!-- 
</form>
-->
<!-- Scripts
================================================== -->

<script type="text/javascript" src="scripts/jquery-migrate-3.3.1.min.js"></script>
<script type="text/javascript" src="scripts/mmenu.min.js"></script>
<script type="text/javascript" src="scripts/chosen.min.js"></script>
<script type="text/javascript" src="scripts/slick.min.js"></script>
<script type="text/javascript" src="scripts/rangeslider.min.js"></script>
<script type="text/javascript" src="scripts/magnific-popup.min.js"></script>
<script type="text/javascript" src="scripts/waypoints.min.js"></script>
<script type="text/javascript" src="scripts/counterup.min.js"></script>
<script type="text/javascript" src="scripts/jquery-ui.min.js"></script>
<script type="text/javascript" src="scripts/tooltips.min.js"></script>
<script type="text/javascript" src="scripts/custom.js"></script>




<!-- Date Range Picker - docs: http://www.daterangepicker.com/ -->
<script src="scripts/moment.min.js"></script>
<script src="scripts/daterangepicker.js"></script>
<script>

    /** 
    * Define an array of custom styles.
    */
    var CustomMapStyles = [
  {
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#f5f5f5"
      }
    ]
  },
  {
    "elementType": "geometry.fill",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "elementType": "labels.icon",
    "stylers": [
      {
        "visibility": "off"
      }
    ]
  },
  {
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#616161"
      }
    ]
  },
  {
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#f5f5f5"
      }
    ]
  },
  {
    "featureType": "administrative.country",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#161a20"
      }
    ]
  },
  {
    "featureType": "administrative.country",
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "featureType": "administrative.land_parcel",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#bdbdbd"
      }
    ]
  },
  {
    "featureType": "administrative.locality",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#161a20"
      },
      {
        "weight": 2.5
      }
    ]
  },
  {
    "featureType": "administrative.locality",
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "featureType": "administrative.province",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#161a20"
      }
    ]
  },
  {
    "featureType": "administrative.province",
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "featureType": "poi",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#eeeeee"
      }
    ]
  },
  {
    "featureType": "poi",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#757575"
      }
    ]
  },
  {
    "featureType": "poi.park",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#e5e5e5"
      }
    ]
  },
  {
    "featureType": "poi.park",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#9e9e9e"
      }
    ]
  },
  {
    "featureType": "road",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "featureType": "road.arterial",
    "elementType": "geometry.fill",
    "stylers": [
      {
        "color": "#a1a1a1"
      }
    ]
  },
  {
    "featureType": "road.arterial",
    "elementType": "geometry.stroke",
    "stylers": [
      {
        "color": "#161a20"
      }
    ]
  },
  {
    "featureType": "road.arterial",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#161a20"
      }
    ]
  },
  {
    "featureType": "road.arterial",
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#dadada"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "geometry.fill",
    "stylers": [
      {
        "color": "#686868"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "geometry.stroke",
    "stylers": [
      {
        "color": "#161a20"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#161a20"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "featureType": "road.highway.controlled_access",
    "elementType": "geometry.fill",
    "stylers": [
      {
        "color": "#161a20"
      }
    ]
  },
  {
    "featureType": "road.local",
    "elementType": "geometry.fill",
    "stylers": [
      {
        "color": "#bfbfbf"
      }
    ]
  },
  {
    "featureType": "road.local",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#2f343c"
      }
    ]
  },
  {
    "featureType": "road.local",
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#ffffff"
      }
    ]
  },
  {
    "featureType": "transit.line",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#e5e5e5"
      }
    ]
  },
  {
    "featureType": "transit.station",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#eeeeee"
      }
    ]
  },
  {
    "featureType": "water",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#c9c9c9"
      }
    ]
  },
  {
    "featureType": "water",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#9e9e9e"
      }
    ]
  }
]
;

  
</script>
		<? if(isset($_GET['com']) && $_GET['com']=='preview2'){?>
			<script src="/map/js/chunk-vendors.72ba3fca.js"></script>
			<script src="/map/js/app.7f97ad73.js"></script>
		<? } ?>
</body>
	<? $validator->RenderValidators();?>
	<? $messages->RenderMessages();?>
</html>