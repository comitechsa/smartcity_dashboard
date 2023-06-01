<?php //defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
global $db, $config, $auth;
header( "Content-Type: application/x-javascript; charset=UTF-8");
header( "Access-Control: allow <*>"); 

$device = isset($_GET["device"]) && $_GET["device"] != "" ? $_GET["device"] : "";
$viewpos = isset($_GET["vpos"]) && $_GET["vpos"] != "" ? $_GET["vpos"] : "";/* header, left, top, bottom, right */

//$Network = GetNetwotkData($network."|".$location);

$jsR = "";
$jsDWr = "";
//echo $viewpos;

if($viewpos == "header")
{
	$jsR = "\r\n document.write('<s'+'cript language=\"javascript\" src=\"' + infoUrl + \"sites/viewpanel/jquery/js/plugin.hotspot.js\"+ '\"></s'+'cript>');";
	//$jsR = "\r\n document.write('<s'+'tyle type=\"text/css\">.ban a{text-decoration:none;}</s'+'tyle>');";
	//$jsR = "\r\n document.write('<s'+'cript language=\"javascript\">alert('123');</s'+'cript>');";
	$jsR .= "\r\n document.write('<style>.inputbox{font-size : 14px;}</style>');";
	//$jsR .=	"\r\n document.write('<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">');"; //template 1
	//$jsR .= "\r\n document.write('<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">');";
	   

	//$jsR .= "\r\n function documentLoad() {";	
	//$jsR .= "if($(window).width() <= 400) { $('#main_tbl td:nth-child(1)').hide(); $('#main_tbl td:nth-child(3)').hide()} ";
	//$jsR .= "\r\n }";
}
else if($viewpos == "top")
{
	/*
	if($drMun["hotspot_top_gr"]  != "")
	{
		$jsDWr .= "<div class=\"box\" style=\"text-align:left\"><span id=\"rm_prov_info_t\"></span></div>";
		$jsR .= "\r\n resources_gr['rm_prov_info_t']='" . fHtml($drMun["hotspot_top_gr"]) . "';";
		$jsR .= "\r\n resources_en['rm_prov_info_t']='" . fHtml($drMun["hotspot_top_en"]) . "';";
	}
	*/
	if(count($Network) > 0 && $Network["is_valid"] == "True") renderBanner("top", $Network["comp_u_id"], $Network["n_id"], $jsR, $jsDWr);
	if($jsDWr != "") $jsDWr .= "<br>";
	
	
}
else if($viewpos == "right")
{
	$jsDWr .= "<div style=\"padding-top:81px;width:220px\">";	
	
	if(count($Network) > 0 && $Network["is_valid"] == "True") renderBanner("right", $Network["comp_u_id"], $Network["n_id"], $jsR, $jsDWr);	
	$jsDWr .= '</div>';
}
else if($viewpos == "left")
{
	if(count($Network) > 0 && $Network["is_valid"] == "True") loadResources($Network["comp_u_id"], $Network["n_id"], $jsR);
	else
	{
		$jsR .= "\r\n resources_gr['rm_header']='Σύστημα Ασύρματης Πρόσβασης';";
		$jsR .= "\r\n resources_en['rm_header']='Wireless Network System';";
		$jsR .= "\r\n resources_gr['rm_login']='Είσοδος';";
		$jsR .= "\r\n resources_en['rm_login']='Login';";
		$jsR .= "\r\n resources_gr['rm_copymsg']='Aντιγράψτε τους αριθμούς της εικόνας για να συνδεθείτε ';";
		$jsR .= "\r\n resources_en['rm_copymsg']='Type in the numbers of the image in order to connect to the network';";		
	}
	$jsDWr .= "<div style=\"padding-top:81px;width:220px\">";
	if(count($Network) > 0 && $Network["is_valid"] == "True") renderNews($Network["comp_u_id"], $Network["n_id"], $jsR, $jsDWr);
	if(count($Network) > 0 && $Network["is_valid"] == "True") renderBanner("left", $Network["comp_u_id"], $Network["n_id"], $jsR, $jsDWr);
	$jsDWr .= '</div>';
}
else if($viewpos == "bottom")
{
	/*
	if($drMun["hotspot_i_gr"]  != "")
	{
		$jsDWr .= "<div class=\"footerdef box\" style=\"text-align:left\"><span id=\"rm_prov_info\"></span></div>";
		$jsR .= "\r\n resources_gr['rm_prov_info']='" . fHtml($drMun["hotspot_i_gr"]) . "';";
		$jsR .= "\r\n resources_en['rm_prov_info']='" . fHtml($drMun["hotspot_i_en"]) . "';";
	}
	*/
	
	/* Online 
	if(count($Network) > 0 && $Network["is_valid"] == "True") 
	{
		$interval = ($Network["alive_h"]  != "" ? $Network["alive_h"] : $Network["C_ONLINE_HOUR"]);
		$ml_filter = " AND DATE_FORMAT(date_insert, '%Y-%m-%d %H') >= DATE_FORMAT(DATE_SUB(NOW(),INTERVAL " . $interval . " HOUR), '%Y-%m-%d %H') ";
	
		$dr_MunIn = $db->RowSelectorQuery(" SELECT COUNT(*) FROM " . $Network["networks_stat_data"] . " WHERE n_id=" . $Network["n_id"] . " " . $ml_filter);
		$dr_AllIn = $db->RowSelectorQuery(" SELECT COUNT(*) FROM " . $Network["networks_stat_data"] . " WHERE n_id IN (SELECT n_id FROM networks WHERE comp_u_id = " . $Network["comp_u_id"] . ") " . $ml_filter);	
	
		$jsDWr .= "<div id=\"rm_stats\" class=\"footerdef box\">";
	
		$jsDWr .= "<span id=\"rm_onl_hotspot\"></span>&nbsp;<b>" . $Network["NetworkName"] . " - " . $Network["SpotName"] . "</b><br>";
		$jsDWr .= "<span id=\"rm_onl_prov\"></span>&nbsp;<b>" . $dr_MunIn[0] . "</b><br>";		
		$jsDWr .= "<span id=\"rm_onl_all\"></span>&nbsp;<b>" . $dr_AllIn[0] . "</b>";
	
		$jsDWr .= "</div>";
		$jsDWr .= "<br>";

		renderBanner("bottom",$Network["comp_u_id"], $Network["n_id"], $jsR, $jsDWr);
	}*/
	
	if(count($Network) > 0 && $Network["is_valid"] == "False") 
	{
		$jsR = "document.getElementById('rm_securitymsg').style.display='none';";
	}
}

$jsDWr = str_replace("'","\\'",$jsDWr);
if($jsDWr != "" ) $jsR .= "\r\n document.write('" . $jsDWr . "');";
echo $jsR;
exit;
function fHtml($str)
{
	$str = str_replace("'","\\'", $str);
	$str = str_replace("\r","", $str);
	$str = str_replace("\n","", $str);
	return $str;
}

function loadResources($comp_u_id, $n_id, &$jsR)
{
	global $db, $config;
	$result = $db->sql_query("SELECT * FROM networks_resources INNER JOIN networks_resourcestol ON networks_resources.rs_id = networks_resourcestol.rs_id INNER JOIN languages ON networks_resourcestol.language_id = languages.language_id WHERE comp_u_id = " . $comp_u_id . " AND n_id IS NULL");
	while ($dr = $db->sql_fetchrow($result))
	{
		$desc = $dr["description"];
		if($n_id != "" && $n_id != "-1")
		{
			$dr_in_m = $db->RowSelectorQuery(" SELECT * FROM networks_resources INNER JOIN networks_resourcestol ON networks_resources.rs_id = networks_resourcestol.rs_id WHERE language_id = " . $dr["language_id"] . " AND n_id = " . $n_id . " AND const='" . $dr["const"] . "'");
			if(isset($dr_in_m["description"])) $desc = $dr_in_m["description"];
		}
		$jsR .= "\r\n resources_" . $dr["language_code"] . "['" . $dr["const"] . "']='" . fHtml($desc) . "';";
	}
	$db->sql_freeresult($result);
}

function renderBanner($pos, $comp_u_id,  $n_id, &$jsR, &$jsDWr)
{
	global $db, $config;

	$result = $db->sql_query("SELECT * FROM networks_banners WHERE is_valid = 'True' AND position='" . $pos . "' AND ( (comp_u_id = " . $comp_u_id . " AND n_id IS NULL ) OR n_id ='" . $n_id . "') ORDER BY priority");
	while ($dr = $db->sql_fetchrow($result))
	{
		$result_lang = $db->sql_query("SELECT * FROM networks_bannerstol INNER JOIN languages ON networks_bannerstol.language_id = languages.language_id WHERE banner_id='" . $dr["banner_id"] . "'");
		while ($dr_lang = $db->sql_fetchrow($result_lang))
		{
			$jsR .= "\r\n resources_" . $dr_lang["language_code"] . "['rm_b_" . $dr["banner_id"] . "']='";			
			if($dr["url"] != "" || $dr_lang["content"] != "") $jsR .= "<a style=\"text-decoration:none;\" class=\"modal\" title=\"" . $dr_lang["title"] . "\" target=\"_blank\" href=\"" . $config["siteurl"] . "index.php?com=hbanners_m&item=" . $dr["banner_id"] . "\" >";
			$jsR .= fGUrl(fHtml($dr_lang["description"]));			
			if($dr["url"] != "" || $dr_lang["content"] != "") $jsR .= "</a>";			
			$jsR .= "';";
		}
		$db->sql_freeresult($result_lang);
		
		$jsDWr .= "<div class=\"box leftdef\" id=\"rm_b_" . $dr["banner_id"] . "\"></div>";		
	}
	$db->sql_freeresult($result);
}

function renderNews($comp_u_id, $n_id, &$jsR, &$jsDWr)
{
	global $db, $config;

	$result = $db->sql_query("SELECT * FROM networks_news WHERE is_valid = 'True' AND ( (comp_u_id = " . $comp_u_id . " AND n_id IS NULL) OR n_id ='" . $n_id . "') ORDER BY new_date DESC, networks_news.new_id DESC LIMIT 5");
	while ($dr = $db->sql_fetchrow($result))
	{
		$result_lang = $db->sql_query("SELECT * FROM networks_newstol INNER JOIN languages ON networks_newstol.language_id = languages.language_id WHERE new_id='" . $dr["new_id"] . "'");
		while ($dr_lang = $db->sql_fetchrow($result_lang))
		{
			$jsR .= "\r\n resources_" . $dr_lang["language_code"] . "['rm_n_" . $dr["new_id"] . "']='";			
			if($dr_lang["new_content"] != "") $jsR .= "<a style=\"text-decoration:none;\" class=\"modal\" title=\"" . $dr_lang["new_title"] . "\" target=\"_blank\" href=\"" . $config["siteurl"] . "index.php?com=hnews_m&item=" . $dr["new_id"] . "\" >";
			$jsR .= fGUrl(fHtml("<img src=\"/gallery/vp/inprog/hotspot/rss-icon.jpg\" border='0' align='left' hspace='3'>".$dr_lang["new_title"]));			
			if($dr_lang["new_content"] != "") $jsR .= "</a>";			
			$jsR .= "';";
		}
		$db->sql_freeresult($result_lang);
		
		$jsDWr .= "<div style=\"text-align:justify\" class=\"box leftdef\" id=\"rm_n_" . $dr["new_id"] . "\"></div>";		
	}
	$db->sql_freeresult($result);
}

?>
