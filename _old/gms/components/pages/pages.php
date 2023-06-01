<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
if(isset($_GET["item"]) && intval($_GET["item"]) > 0)
{	
	$gp = new GetPage(intval($_GET["item"]));
	echo $gp->content;
	$config["navigation"] = $gp->title;
	$config["metaKeys"] = $gp->meta_keys;
	$config["metaDesciption"] = $gp->meta_desc;
}
?>

