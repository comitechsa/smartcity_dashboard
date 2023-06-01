<?
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );


class GmsAuthenticate
{
	var $CurrentPage = '';
	var $UserRow = '';
	var $UserType = '';
	var $UserId = '';
	var $LanguageID = '';
	var $LanguageCode = '';
	var $LanguageCharset = '';
	var $DateFormat = '';
	var $DecimalFormat = '';
	
	function Init(&$db)
	{
		$tempLanguageCode = "";
		if(isset($_POST["lang"])) $tempLanguageCode = $_POST["lang"];
		else if(isset($_GET["lang"])) $tempLanguageCode = $_GET["lang"];
		
		if($tempLanguageCode != "") $this->LoadLanguage($db,$tempLanguageCode);
		
		if($this->LanguageID == '')
		{
			$this->LoadLanguage($db,"gr");
		}
		
		if($this->LanguageID == '')
		{
			$this->LoadLanguage($db,"en");
		}
	}
	
	function LoadLanguage(&$db,$LanguageCode)
	{
		$result = $db->sql_query("SELECT * FROM languages WHERE is_active = 'True' AND language_code = '" . $LanguageCode . "'");
		
		if($dr = $db->sql_fetchrow($result)){
			$this->LanguageID = $dr["language_id"];
			$this->LanguageCode = $dr["language_code"];	
			$this->LanguageCharset = $dr["charset"];
			$this->DateFormat = $dr["dateFormat"];
			$this->DecimalFormat = $dr["decimalFormat"];
			
		}
		$db->sql_freeresult($result);		
	}
}


?>