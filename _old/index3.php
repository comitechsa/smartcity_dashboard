<?php
	include('class.db.php');
	$host = 'localhost';
	$database = 'scities';
	$dbuser = 'scities';
	$dbpass = 'qwe#123!@#';
	$db = new sql_db($host, $dbuser, $dbpass, $database);
	//mysensor_name
			
?>
<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="UTF-8">
	
	<meta name="apple-mobile-web-app-title" content="CodePen">
	<title>Smartiscity - Quickview</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
	<script src="https://kit.fontawesome.com/67a08bc08e.js" crossorigin="anonymous"></script>
	<style>
	@import url(https://fonts.googleapis.com/css?family=Montserrat:400,700);

	body {
		background: #363B48;
		font-family: Montserrat;
		overflow:hidden;
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}

	h2 { 
	  margin:150px auto 50px;
	  text-align:center;
	  font-size:18px;
	  text-transform:uppercase;
	  background:#2e333f;
	  padding:25px;
	  display:block;
	  cursor:default;
	  width:370px;
	  color:#cacaca;
	  border:1px solid rgba(173, 173, 173, 0.15);
	}

	.metro {
		width: 95%;
		margin: 0 auto 0;
	}


	.metro li {
		-webkit-transform: perspective(600px);
		-webkit-transform-style: preserve-3d;
		-webkit-transform-origin-x: 50%;
		-webkit-transform-origin-y: 50%;
		-ms-transform: perspective(600px);
		-ms-transform-style: preserve-3d;
		-ms-transform-origin-x: 50%;
		-ms-transform-origin-y: 50%;
		transform: perspective(600px);
		transform-style: preserve-3d;
		transform-origin-x: 50%;
		transform-origin-y: 50%;
		cursor: default;
		position: relative;
		text-align: center;
		margin: 0 10px 10px 0;
		width: 200px;
		height: 200px;
		color: #ffffff;
		float: left;
		-webkit-transition: .2s -webkit-transform, 1s opacity;
		-ms-transition: .2s -ms-transform, 1s opacity;
		transition: .2s transform, 1s opacity;
		cursor:pointer;
	}
	/*
	.metro li i {
		font-size: 54px;
		margin: 35px 0 0;
	}
	*/


	.metro li span {
		color: rgba(255, 255, 255, 0.8);
		text-transform: uppercase;
		position: absolute;
		left: 15px;
		bottom: 15px;
		font-size: 14px;
	}

	.metro li:first-child {
		background: #00b6de;
	}

	.metro li:nth-child(2) {
		background: #56dea7;
		width: 410px;
	}

	.metro li:nth-child(3) {
		background: #ff7659;
	   /* margin: 0;*/
	}

	.metro li:nth-child(4) {
		background: #f8cd36;
	}

	.metro li:nth-child(5) {
		background: #f26175;
	}

	.metro li:nth-child(6) {
		background: #5ca7df;
	}

	.metro li:last-child {
		background: #9e7ac2;
		margin: 0;
	}


	/*
	.metro li:nth-child(5):active, .metro li:first-child:active {
		-webkit-transform: scale(0.95);
		-ms-transform: scale(0.95);
		transform: scale(0.95);
	}

	.metro li:nth-child(7):active, .metro li:nth-child(2):active {
		-webkit-transform: perspective(600px) rotate3d(1, 0, 0, -10deg);
		-ms-transform: perspective(600px) rotate3d(1, 0, 0, -10deg);
		transform: perspective(600px) rotate3d(1, 0, 0, -10deg);
	}

	.metro li:nth-child(3):active {
		-webkit-transform: perspective(600px) rotate3d(0, 1, 0, 10deg);
		-ms-transform: perspective(600px) rotate3d(0, 1, 0, 10deg);  
		transform: perspective(600px) rotate3d(0, 1, 0, 10deg); 
	}

	.metro li:nth-child(4):active {
		-webkit-transform: perspective(600px) rotate3d(0, 1, 0, -10deg);
		-ms-transform: perspective(600px) rotate3d(0, 1, 0, -10deg);
		transform: perspective(600px) rotate3d(0, 1, 0, -10deg);
	}

	.metro li:nth-child(6):active {
		-webkit-transform: perspective(600px) rotate3d(1, 0, 0, 10deg);
		-ms-transform: perspective(600px) rotate3d(1, 0, 0, 10deg);
		transform: perspective(600px) rotate3d(1, 0, 0, 10deg);
	}
	*/


	/* POPUP */

	.box {
		display: table;
		top: 0;
		visibility: hidden;
		-webkit-transform: perspective(1200px) rotateY(180deg) scale(0.1);
		-ms-transform: perspective(1200px) rotateY(180deg) scale(0.1);
		transform: perspective(1200px) rotateY(180deg) scale(0.1);
		top: 0;
		left: 0;
		z-index: -1;
		position: absolute;
		width: 100%;
		height: 100%;
		opacity: 0;
		transition: 1s all;
	}

	.box p {
		display: table-cell;
		vertical-align: middle;
		font-size: 64px;
		color: #ffffff;
		text-align: center;
		margin: 0;
		opacity: 0;
		transition: .2s;
		-webkit-transition-delay: 0.2s;
		-ms-transition-delay: 0.2s;
		transition-delay: 0.2s;
	}

	.box p i {
		font-size: 128px;
		margin:0 0 20px;
		display:block;
	}

	.box .close {
	  display:block;
	  cursor:pointer;
	  border:3px solid rgba(255, 255, 255, 1);
	  border-radius:50%;
	  position:absolute;
	  top:50px;
	  right:50px;
	  width:50px;
	  height:50px;
	  -webkit-transform:rotate(45deg);
	  -ms-transform:rotate(45deg)
	  transform:rotate(45deg);
	  transition: .2s;
	  -webkit-transition-delay: 0.2s;
	  -ms-transition-delay: 0.2s;
	  transition-delay: 0.2s;
	  opacity:0;
	}

	.box .close:active {
		top:51px;
	}

	.box .close::before {
	  content: "";
	  display: block;
	  position: absolute;
	  background-color: rgba(255, 255, 255, 1);
	  width: 80%;
	  height: 6%;
	  left: 10%;
	  top: 47%;
	}

	.box .close::after {
	  content: "";
	  display: block;
	  position: absolute;
	  background-color: rgba(255, 255, 255, 1);
	  width: 6%;
	  height: 80%;
	  left: 47%;
	  top: 10%;
	}

	.box.open {
		left: 0;
		top: 0;
		visibility: visible;
		opacity: 1;
		z-index: 999;
		-webkit-transform: perspective(1200px) rotateY(0deg) scale(1);
		-ms-transform: perspective(1200px) rotateY(0deg) scale(1);
		transform: perspective(1200px) rotateY(0deg) scale(1);
		width: 100%;
		height: 100%;
	}

	.box.open .close, .box.open p {
		opacity: 1;
	}
	.owl-theme .owl-nav{
		margin-top:0;
	}
	</style>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/owl.carousel.min.css">
	<link rel="stylesheet" href="css/owl.theme.default.min.css">
	<style>
	.owl-theme .owl-nav{
		margin-top:0;
	}
	</style>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script src="js/owl.carousel.js"></script>
	<style type="text/css" media="screen">

	table{
	border-collapse:collapse;
	border:1px solid #FFF;
	}

	table td{
	border:1px dotted #FFF;
	}
</style>
</head>

<body translate="no" style="background-image: url(img/drama1.jpg);background-repeat: no-repeat;background-size: cover;min-height:94vh;" >
<div class="container">
	<div class="row">
		<div class="col-sm-12"> 
			<ul class="metro" style="margin-top:100px;">
				<li data-id="1" style="height:310px;">
					<div id="1" class="owl-carousel owl-theme">
					<?php
						$result = $db->sql_query("SELECT * FROM mysensors WHERE region_id=1 AND sensortype_id=2");
						while ($dr = $db->sql_fetchrow($result)){
							$btRow=$db->RowSelectorQuery('SELECT * FROM bt_totals WHERE MeshliumID='.$dr['ref_id'].' ORDER BY countDate DESC,countHour DESC LIMIT 1');
							$wfRow=$db->RowSelectorQuery('SELECT * FROM wf_totals WHERE MeshliumID='.$dr['ref_id'].' ORDER BY countDate DESC,countHour DESC LIMIT 1');
							echo '<div class="item" style="width:200px;height:170px;">
									<i style="font-size:54px;margin: 35px 0 0;" class="fa fa-gamepad"></i>
									<div style="padding:0 30px;">
										<table style="border-collapse: collapse;border:1px dotted;width:100%;">
											<tr><td><img style="width:40px;" src="/img/vars/Small/25.svg"></td><td>'.$wfRow['countMac'].'</td></tr>
											<tr><td><img style="width:40px;" src="/img/vars/Small/26.svg"></td><td>'.$btRow['countMac'].'</td></tr>
											<tr colspan="2"><td>'.$dr['mysensor_name'].'</td></tr>
										</table>
									</div>
								</div>';
						}	
					?>
					
						<div class="item" style="width:200px;height:170px;"><i style="font-size:54px;margin: 35px 0 0;" class="fa fa-gamepad"></i><span>Θερμοκρασία1</span></div>
						<div class="item" style="width:200px;height:170px;"><i style="font-size:54px;margin: 35px 0 0;" class="fa fa-gamepad"></i><span>Θερμοκρασία2</span></div>
						<div class="item" style="width:200px;height:170px;"><i style="font-size:54px;margin: 35px 0 0;" class="fa fa-gamepad"></i><span>Θερμοκρασία3</span></div>
						<div class="item" style="width:200px;height:170px;"><i style="font-size:54px;margin: 35px 0 0;" class="fa fa-gamepad"></i><span>Θερμοκρασία4</span></div>
						<div class="item" style="width:200px;height:170px;"><i style="font-size:54px;margin: 35px 0 0;" class="fa fa-gamepad"></i><span>Θερμοκρασία5</span></div>
					</div>
				</li>
				
				<li data-id="2">
					<div id="2" class="owl-carousel owl-theme">
						<div class="item" style="width:200px;height:170px;"><i style="font-size:54px;margin: 35px 0 0;" class="fa fa-cogs"></i><span>Περιβάλλον1</span></div>
						<div class="item" style="width:200px;height:170px;"><i style="font-size:54px;margin: 35px 0 0;" class="fa fa-cogs"></i><span>Περιβάλλον2</span></div>
						<div class="item" style="width:200px;height:170px;"><i style="font-size:54px;margin: 35px 0 0;" class="fa fa-cogs"></i><span>Περιβάλλον</span></div>
						<div class="item" style="width:200px;height:170px;"><i style="font-size:54px;margin: 35px 0 0;" class="fa fa-cogs"></i><span>Περιβάλλον4</span></div>
						<div class="item" style="width:200px;height:170px;"><i style="font-size:54px;margin: 35px 0 0;" class="fa fa-cogs"></i><span>Περιβάλλον5</span></div>
					</div>
				</li>
				<li data-id="3"><i style="font-size:54px;margin: 35px 0 0;" class="fa fa-envelope-o"></i><span>WiFi Spots</span></li>
				<li data-id="4"><i style="font-size:54px;margin: 35px 0 0;" class="fa fa-comments-o"></i><span>Ποιότητα υδάτων</span></li>
				<li data-id="5"><i style="font-size:54px;margin: 35px 0 0;" class="fa fa-music"></i><span>Ροή υδάτων</span></li>
				<li data-id="6"><i style="font-size:54px;margin: 35px 0 0;" class="fa fa-heart-o"></i><span>Συννάθροιση κοινού</span></li>
				<li data-id="7"><i style="font-size:54px;margin: 35px 0 0;" class="fa fa-picture-o"></i><span>Κίνηση</span></li>
			</ul>
		</div>
	</div>
</div>
  


	<div class="box">
		<span class="close"></span>
		<p></p>
	</div>

	<!-- <script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-157cd5b220a5c80d4ff8e0e70ac069bffd87a61252088146915e8726e5d9f147.js"></script>-->
	<!-- <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>-->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<!-- 
		<script id="rendered-js" >
			$(document).ready(function () {
				var $box = $('.box');
				$('.metro li').each(function () {
					var color = $(this).css('backgroundColor');
					var content = 'Δεδομένα??'; //$(this).html();
					$(this).click(function () {
						$box.css('backgroundColor', color);
						$box.addClass('open');
						$box.find('p').html(content);
					});
					//$(this).click(function () {
					//	$.get( "index2.html?id=" + $(this).data("id"), function( data ) {
					//		$box.css('backgroundColor', color);
					//		$box.addClass('open');
					//		$box.find('p').html(data );
					//	});
					//});

					$('.close').click(function () {
						$box.removeClass('open');
						$box.css('backgroundColor', 'transparent');
					});
				});
			});
		</script>
	-->
		<script>
		$('#1').owlCarousel({
			loop:true,
			margin:0,
			nav:true,
			pagination: false,
			dots: false,
			responsive:{
				0:{
					items:1
				},
				600:{
					items:1
				},
				1000:{
					items:1
				}
			}
		})
		$('#2').owlCarousel({
			loop:true,
			margin:0,
			nav:true,
			pagination: false,
			dots: false,
			responsive:{
				0:{
					items:1
				},
				600:{
					items:1
				},
				1000:{
					items:1
				}
			}
		})
		</script>
</body>
</html>
 
