<?
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

class HtmlHeader
{
	function RenderAdminHeader()
	{
		global $config,$auth;
		?>
		<title><?=site_adminTitle?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$auth->LanguageCharset?>"/>
		<link rel="stylesheet" type="text/css" href="admin.logon.css">
		<script language="javascript">
			var recordSelect="<?=core_recordSelect;?>";
			var CurrentLanguage = "<?=$auth->LanguageCode?>";
			var BaseUrl = "<?=$config["siteurl"]?>";
		</script>
		<script language="javascript" type="text/javascript" src="/gms/client_scripts/core.js"></script>
		<script language="javascript" type="text/javascript" src="/gms/client_scripts/private.js"></script>
		<?
	}
	
	function RenderPublicHeader()
	{
		global $config,$auth;
		/*
		<title>site_title=(isset($config["navigation"]) ? " :: " . strip_tags($config["navigation"]) : "")</title>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=$auth->LanguageCharset"/>
		<meta name="description" content="(isset($config["metaDesciption"]) ? $config["metaDesciption"] : "")" />
        <meta name="keywords" content="(isset($config["metaKeys"]) ? $config["metaKeys"] : "")" />
		*/
		//site_metaDesciption . (isset($config["metaDesciption"]) ? (site_metaDesciption != "" ? ", " : "") . $config["metaDesciption"] : "")
		//site_metaKeys . (isset($config["metaKeys"]) ? (site_metaKeys != "" ? ", " : "") . $config["metaKeys"] : "")
		
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
		<script language="javascript" type="text/javascript" src="/gms/client_scripts/core.js"></script>
		<script language="javascript" type="text/javascript" src="/gms/client_scripts/public.js"></script>
		<link rel="stylesheet" type="text/css" href="<?=$config["siteurl"]?>sites/<?=$config["site"]?>/theme.css">
<?
	}
}

$htmlheader = new HtmlHeader();
?>