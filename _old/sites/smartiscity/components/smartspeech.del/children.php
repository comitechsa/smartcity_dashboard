<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );
	include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
	include($config["physicalPath"]."/perm.php");

	$drPerm=$db->RowSelectorQuery("SELECT * FROM roles WHERE role_id=".$auth->UserRow['role_id']);	
	$auth->UserRow['access']=$drPerm['access'];
	
	
	$permissions = (intval($auth->UserRow['access'])>0?$auth->UserRow['access']:0);
	if (!($permissions & $FLAG_600)&&  !$auth->UserType == "Administrator") {
		Redirect("index.php");
	}
//require_once(dirname(__FILE__) . "/common.php");
//if(($auth->UserRow['admin_type']=='LOCAL')) {
//	Redirect("index.php");
//}
//if($auth->UserType != "Administrator") Redirect("index.php");

global $nav;
$nav = "Παιδιά";
$config["navigation"] = $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=children";
$command=array();
$command=explode("&",$_POST["Command"]);

//if( $auth->UserType == "Administrator" )
//{
	if(isset($_REQUEST["Command"]))
	{	
		if($_REQUEST["Command"] == "SAVE")
		{
			//print_r($_POST);
			//exit;
		//Array ( [Command] => SAVE [nickname] => test1 [sex] => 1 [birthdate] => 2020-05-13 [city] => 1o Δημοτικό [region] => Γ1 [school] => [schoolclass] => [schoolclassid] => [description] => παρατηρήσεις 
		//[q1] => on [q2] => on [q3-1] => on [q3-3] => on [email] => )
			$PrimaryKeys = array();
			$Collector = array();
			$QuotFields = array();

			if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0)
			{
				$PrimaryKeys["children_id"] = intval($_GET["item"]);
				$QuotFields["children_id"] = true;
				
			} else {
				$Collector["date_insert"] = date('Y-m-d H:i:s');
				$QuotFields["date_insert"] = true;
			}
			
			$Collector["user_id"] = $auth->UserId;
			$QuotFields["user_id"] = true;
			
		if($_POST["myOption"]==0){
			$Collector["nickname"] = $_POST["nickname"];
			$QuotFields["nickname"] = true;

			$Collector["weight"] = str_replace(",",".",$_POST["weight"]);
			$QuotFields["weight"] = true;

			$Collector["height"] = $_POST["height"];
			$QuotFields["height"] = true;

			$languages=($_POST['languages-1']=='on'?'1':'0').($_POST['languages-2']=='on'?'1':'0').($_POST['languages-3']=='on'?'1':'0').($_POST['languages-4']=='on'?'1':'0').($_POST['languages-5']=='on'?'1':'0');
			$Collector["languages"] = $languages;
			$QuotFields["languages"] = true;
			

			$Collector["parentagree"] = ($_POST['parentagree']=='on'?'1':'0');
			$QuotFields["parentagree"] = true;
			
			$Collector["parentname"] = $_POST["parentname"];
			$QuotFields["parentname"] = true;
			
			$Collector["parentemail"] = $_POST["parentemail"];
			$QuotFields["parentemail"] = true;			
			
			$Collector["parentphone"] = $_POST["parentphone"];
			$QuotFields["parentphone"] = true;			
			
			$Collector["nativelang"] = $_POST["nativelang"];
			$QuotFields["nativelang"] = true;
			
			$Collector["otherlang"] = $_POST["otherlang"];
			$QuotFields["otherlang"] = true;
			
			$Collector["birthdate"] = $_POST["birthdate"];
			$QuotFields["birthdate"] = true;

			$Collector["sex"] = $_POST["sex"];
			$QuotFields["sex"] = true;

			$Collector["city"] = $_POST["city"];
			$QuotFields["city"] = true;

			$Collector["region"] = $_POST["region"];
			$QuotFields["region"] = true;

			$Collector["school"] = $_POST["school"];
			$QuotFields["school"] = true;		

			$Collector["schoolclass"] = $_POST["schoolclass"];
			$QuotFields["schoolclass"] = true;	
		
			$Collector["schoolclassid"] = $_POST["schoolclassid"];
			$QuotFields["schoolclassid"] = true;	
			
			$Collector["custody"] = $_POST["custody"];
			$QuotFields["custody"] = true;	
			
			$Collector["custodyagree"] = ($_POST['custodyagree']=='on'?'1':'0');
			$QuotFields["custodyagree"] = true;	
												
			$Collector["description"] = $_POST["description"];
			$QuotFields["description"] = true;
			
		} else if($_POST["myOption"]==1){	
			$Collector["q1"] = (intval($_POST['q1'])>0?$_POST['q1']:'');
			$QuotFields["q1"] = true;
			
			$Collector["q2"] = (intval($_POST['q2'])>0?$_POST['q2']:'');
			$QuotFields["q2"] = true;

			
			$q3=($_POST['q3-1']=='on'?'1':'0').($_POST['q3-2']=='on'?'1':'0').($_POST['q3-3']=='on'?'1':'0').($_POST['q3-4']=='on'?'1':'0').($_POST['q3-5']=='on'?'1':'0').($_POST['q3-6']=='on'?'1':'0').($_POST['q3-7']=='on'?'1':'0').($_POST['q3-8']=='on'?'1':'0').($_POST['q3-9']=='on'?'1':'0');
			$Collector["q3"] = $q3;
			$QuotFields["q3"] = true;



			$q4=($_POST['q4-1']=='on'?'1':'0').($_POST['q4-2']=='on'?'1':'0').($_POST['q4-3']=='on'?'1':'0').($_POST['q4-4']=='on'?'1':'0').($_POST['q4-5']=='on'?'1':'0').($_POST['q4-6']=='on'?'1':'0').($_POST['q4-7']=='on'?'1':'0').($_POST['q4-8']=='on'?'1':'0').($_POST['q4-9']=='on'?'1':'0').($_POST['q4-10']=='on'?'1':'0').
			($_POST['q4-11']=='on'?'1':'0').($_POST['q4-12']=='on'?'1':'0').($_POST['q4-13']=='on'?'1':'0').($_POST['q4-14']=='on'?'1':'0').($_POST['q4-15']=='on'?'1':'0').($_POST['q4-16']=='on'?'1':'0').($_POST['q4-17']=='on'?'1':'0').($_POST['q4-18']=='on'?'1':'0').($_POST['q4-19']=='on'?'1':'0').($_POST['q4-20']=='on'?'1':'0').
			($_POST['q4-21']=='on'?'1':'0').($_POST['q4-22']=='on'?'1':'0').($_POST['q4-23']=='on'?'1':'0').($_POST['q4-24']=='on'?'1':'0').($_POST['q4-25']=='on'?'1':'0').($_POST['q4-26']=='on'?'1':'0').($_POST['q4-27']=='on'?'1':'0').($_POST['q4-28']=='on'?'1':'0').($_POST['q4-29']=='on'?'1':'0').($_POST['q4-30']=='on'?'1':'0').
			($_POST['q4-31']=='on'?'1':'0').($_POST['q4-32']=='on'?'1':'0').($_POST['q4-33']=='on'?'1':'0').($_POST['q4-34']=='on'?'1':'0').($_POST['q4-35']=='on'?'1':'0').($_POST['q4-36']=='on'?'1':'0').($_POST['q4-37']=='on'?'1':'0').($_POST['q4-38']=='on'?'1':'0').($_POST['q4-39']=='on'?'1':'0').($_POST['q4-40']=='on'?'1':'0').
			($_POST['q4-41']=='on'?'1':'0').($_POST['q4-42']=='on'?'1':'0').($_POST['q4-43']=='on'?'1':'0').($_POST['q4-44']=='on'?'1':'0').($_POST['q4-45']=='on'?'1':'0').($_POST['q4-46']=='on'?'1':'0').($_POST['q4-47']=='on'?'1':'0').($_POST['q4-48']=='on'?'1':'0').($_POST['q4-49']=='on'?'1':'0').($_POST['q4-50']=='on'?'1':'0').
			($_POST['q4-51']=='on'?'1':'0').($_POST['q4-52']=='on'?'1':'0').($_POST['q4-53']=='on'?'1':'0').($_POST['q4-54']=='on'?'1':'0').($_POST['q4-55']=='on'?'1':'0').($_POST['q4-56']=='on'?'1':'0');

			$Collector["q4"] = $q4;
			$QuotFields["q4"] = true;
			
			$Collector["q5"] = (intval($_POST['q5'])>0?$_POST['q5']:'');
			$QuotFields["q5"] = true;
			
			$Collector["q6"] = (intval($_POST['q6'])>0?$_POST['q6']:'');
			$QuotFields["q6"] = true;

			//End section 1
			
		} else if($_POST["myOption"]==2){		
			//Section 2
			$Collector["q7"] = (intval($_POST['q7'])>0?$_POST['q7']:'');
			$QuotFields["q7"] = true;
			
			$Collector["q8"] = (intval($_POST['q8'])>0?$_POST['q8']:'');
			$QuotFields["q8"] = true;

			
			$Collector["q9"] = (intval($_POST['q9'])>0?$_POST['q9']:'');
			$QuotFields["q9"] = true;
			
			$Collector["q10"] = (intval($_POST['q10'])>0?$_POST['q10']:'');
			$QuotFields["q10"] = true;
			
			$Collector["q11"] = (intval($_POST['q11'])>0?$_POST['q11']:'');
			$QuotFields["q11"] = true;
			
			$Collector["q12"] = (intval($_POST['q12'])>0?$_POST['q12']:'');
			$QuotFields["q12"] = true;
			
			$Collector["q13"] = (intval($_POST['q13'])>0?$_POST['q13']:'');
			$QuotFields["q13"] = true;

			
			$Collector["q14"] = (intval($_POST['q14'])>0?$_POST['q14']:'');
			$QuotFields["q14"] = true;
			
			$Collector["q15"] = (intval($_POST['q15'])>0?$_POST['q15']:'');
			$QuotFields["q15"] = true;
			
			$Collector["q16"] = (intval($_POST['q16'])>0?$_POST['q16']:'');
			$QuotFields["q16"] = true;
			
			$Collector["q17"] = (intval($_POST['q17'])>0?$_POST['q17']:'');
			$QuotFields["q17"] = true;
			
			$Collector["q18"] = (intval($_POST['q18'])>0?$_POST['q18']:'');
			$QuotFields["q18"] = true;
			
			$Collector["q19"] = (intval($_POST['q19'])>0?$_POST['q19']:'');
			$QuotFields["q19"] = true;
			
			$Collector["q20"] = (intval($_POST['q20'])>0?$_POST['q20']:'');
			$QuotFields["q20"] = true;
			
			$Collector["q21"] = (intval($_POST['q21'])>0?$_POST['q21']:'');
			$QuotFields["q21"] = true;
			
			$Collector["q22"] = (intval($_POST['q22'])>0?$_POST['q22']:'');
			$QuotFields["q22"] = true;
			
			$Collector["q23"] = (intval($_POST['q23'])>0?$_POST['q23']:'');
			$QuotFields["q23"] = true;
			
			$Collector["q24"] = (intval($_POST['q24'])>0?$_POST['q24']:'');
			$QuotFields["q24"] = true;
			
			$Collector["q25"] = (intval($_POST['q25'])>0?$_POST['q25']:'');
			$QuotFields["q25"] = true;
			
			$Collector["q26"] = (intval($_POST['q26'])>0?$_POST['q26']:'');
			$QuotFields["q26"] = true;
			
			$Collector["q27"] = (intval($_POST['q27'])>0?$_POST['q27']:'');
			$QuotFields["q27"] = true;
			
			$Collector["q28"] = (intval($_POST['q28'])>0?$_POST['q28']:'');
			$QuotFields["q28"] = true;
			
			$Collector["q29"] = (intval($_POST['q29'])>0?$_POST['q29']:'');
			$QuotFields["q29"] = true;
			
			$Collector["q30"] = (intval($_POST['q30'])>0?$_POST['q30']:'');
			$QuotFields["q30"] = true;
			
			$Collector["q31"] = (intval($_POST['q31'])>0?$_POST['q31']:'');
			$QuotFields["q31"] = true;
			
			$Collector["q32"] = (intval($_POST['q32'])>0?$_POST['q32']:'');
			$QuotFields["q32"] = true;
			
			$Collector["q33"] = (intval($_POST['q33'])>0?$_POST['q33']:'');
			$QuotFields["q33"] = true;
			
			$Collector["q34"] = (intval($_POST['q34'])>0?$_POST['q34']:'');
			$QuotFields["q34"] = true;
			
			$Collector["q35"] = (intval($_POST['q35'])>0?$_POST['q35']:'');
			$QuotFields["q35"] = true;
			
			$Collector["q36"] = (intval($_POST['q36'])>0?$_POST['q36']:'');
			$QuotFields["q36"] = true;
			
			$Collector["q37"] = (intval($_POST['q37'])>0?$_POST['q37']:'');
			$QuotFields["q37"] = true;
			
			$Collector["q38"] = (intval($_POST['q38'])>0?$_POST['q38']:'');
			$QuotFields["q38"] = true;
			
			$Collector["q39"] = (intval($_POST['q39'])>0?$_POST['q39']:'');
			$QuotFields["q39"] = true;
			
			$Collector["q40"] = (intval($_POST['q40'])>0?$_POST['q40']:'');
			$QuotFields["q40"] = true;
			
			$Collector["q41"] = (intval($_POST['q41'])>0?$_POST['q41']:'');
			$QuotFields["q41"] = true;
			
			$Collector["q42"] = (intval($_POST['q42'])>0?$_POST['q42']:'');
			$QuotFields["q42"] = true;
			
			$Collector["q43"] = (intval($_POST['q43'])>0?$_POST['q43']:'');
			$QuotFields["q43"] = true;
			
			$Collector["q44"] = (intval($_POST['q44'])>0?$_POST['q44']:'');
			$QuotFields["q44"] = true;
			
			$Collector["q45"] = (intval($_POST['q45'])>0?$_POST['q45']:'');
			$QuotFields["q45"] = true;
			
			$Collector["q46"] = (intval($_POST['q46'])>0?$_POST['q46']:'');
			$QuotFields["q46"] = true;
			
			$Collector["q47"] = (intval($_POST['q47'])>0?$_POST['q47']:'');
			$QuotFields["q47"] = true;
			
			$Collector["q48"] = (intval($_POST['q48'])>0?$_POST['q48']:'');
			$QuotFields["q48"] = true;

			//End section 2
		} else if($_POST["myOption"]==3){		
			//Section 3
			$Collector["q49"] = (intval($_POST['q49'])>0?$_POST['q49']:'');
			$QuotFields["q49"] = true;
			
			$Collector["q50"] = (intval($_POST['q50'])>0?$_POST['q50']:'');
			$QuotFields["q50"] = true;
			
			$Collector["q51"] = (intval($_POST['q51'])>0?$_POST['q51']:'');
			$QuotFields["q51"] = true;
			
			$Collector["q52"] = (intval($_POST['q52'])>0?$_POST['q52']:'');
			$QuotFields["q52"] = true;
			
			$Collector["q53"] = (intval($_POST['q53'])>0?$_POST['q53']:'');
			$QuotFields["q53"] = true;
			
			$Collector["q54"] = (intval($_POST['q54'])>0?$_POST['q54']:'');
			$QuotFields["q54"] = true;
			
			$Collector["q55"] = (intval($_POST['q55'])>0?$_POST['q55']:'');
			$QuotFields["q55"] = true;
		
			$Collector["q56"] = (intval($_POST['q56'])>0?$_POST['q56']:'');
			$QuotFields["q56"] = true;

			$Collector["q57"] = (intval($_POST['q57'])>0?$_POST['q57']:'');
			$QuotFields["q57"] = true;
			
			$Collector["q58"] = (intval($_POST['q58'])>0?$_POST['q58']:'');
			$QuotFields["q58"] = true;
			
			$Collector["q59"] = (intval($_POST['q59'])>0?$_POST['q59']:'');
			$QuotFields["q59"] = true;
			
			$Collector["q60"] = (intval($_POST['q60'])>0?$_POST['q60']:'');
			$QuotFields["q60"] = true;
			
			$Collector["q61"] = (intval($_POST['q61'])>0?$_POST['q61']:'');
			$QuotFields["q61"] = true;
			
			$Collector["q62"] = (intval($_POST['q62'])>0?$_POST['q62']:'');
			$QuotFields["q62"] = true;
			
			$Collector["q63"] = (intval($_POST['q63'])>0?$_POST['q63']:'');
			$QuotFields["q63"] = true;
			
			$Collector["q64"] = (intval($_POST['q64'])>0?$_POST['q64']:'');
			$QuotFields["q64"] = true;
			
			$Collector["q65"] = (intval($_POST['q65'])>0?$_POST['q65']:'');
			$QuotFields["q65"] = true;
			
			$Collector["q66"] = (intval($_POST['q66'])>0?$_POST['q66']:'');
			$QuotFields["q66"] = true;
			
			$Collector["q67"] = (intval($_POST['q67'])>0?$_POST['q67']:'');
			$QuotFields["q67"] = true;
			
			//$q68=($_POST['q68-1']=='on'?'1':'0').($_POST['q68-2']=='on'?'1':'0').($_POST['q68-3']=='on'?'1':'0').($_POST['q68-4']=='on'?'1':'0').($_POST['q68-5']=='on'?'1':'0').($_POST['q68-6']=='on'?'1':'0').($_POST['q68-7']=='on'?'1':'0').($_POST['q68-8']=='on'?'1':'0').($_POST['q68-9']=='on'?'1':'0');
			//$Collector["q68"] = $q68;
			//$QuotFields["q68"] = true;
			
			$Collector["q68"] = (intval($_POST['q68'])>0?$_POST['q68']:'');
			$QuotFields["q68"] = true;
			
			$Collector["q190"] = (intval($_POST['q190'])>0?$_POST['q190']:'');
			$QuotFields["q190"] = true;	

			$Collector["q191"] = (intval($_POST['q191'])>0?$_POST['q191']:'');
			$QuotFields["q191"] = true;	

			$Collector["q192"] = (intval($_POST['q192'])>0?$_POST['q192']:'');
			$QuotFields["q192"] = true;	
			
			$Collector["q69"] = (intval($_POST['q69'])>0?$_POST['q69']:'');
			$QuotFields["q69"] = true;
			
			$Collector["q70"] = (intval($_POST['q70'])>0?$_POST['q70']:'');
			$QuotFields["q70"] = true;
			
			$Collector["q71"] = (intval($_POST['q71'])>0?$_POST['q71']:'');
			$QuotFields["q71"] = true;
			
			$q72=($_POST['q72-1']=='on'?'1':'0').($_POST['q72-2']=='on'?'1':'0').($_POST['q72-3']=='on'?'1':'0').($_POST['q72-4']=='on'?'1':'0').($_POST['q72-5']=='on'?'1':'0').($_POST['q72-6']=='on'?'1':'0').($_POST['q72-7']=='on'?'1':'0').($_POST['q72-8']=='on'?'1':'0'); //.($_POST['q72-8']=='on'?'1':'0').($_POST['q72-9']=='on'?'1':'0');
			$Collector["q72"] = $q72;
			$QuotFields["q72"] = true;

			$Collector["q193"] = (intval($_POST['q193'])>0?$_POST['q193']:'');
			$QuotFields["q193"] = true;	
			
			$Collector["q73"] = (intval($_POST['q73'])>0?$_POST['q73']:'');
			$QuotFields["q73"] = true;	
			
			$Collector["q74"] = (intval($_POST['q74'])>0?$_POST['q74']:'');
			$QuotFields["q74"] = true;	
			
			$Collector["q75"] = (intval($_POST['q75'])>0?$_POST['q75']:'');
			$QuotFields["q75"] = true;	
			
			$q76=($_POST['q76-1']=='on'?'1':'0').($_POST['q76-2']=='on'?'1':'0').($_POST['q76-3']=='on'?'1':'0').($_POST['q76-4']=='on'?'1':'0').($_POST['q76-5']=='on'?'1':'0').($_POST['q76-6']=='on'?'1':'0');
			$Collector["q76"] = $q76;
			$QuotFields["q76"] = true;
			
			$Collector["q77"] = (intval($_POST['q77'])>0?$_POST['q77']:'');
			$QuotFields["q77"] = true;	
			
			$Collector["q78"] = (intval($_POST['q78'])>0?$_POST['q78']:'');
			$QuotFields["q78"] = true;	

			//End section
		} else if($_POST["myOption"]==4){			
			//Section 4
			$Collector["q79"] = (intval($_POST['q79'])>0?$_POST['q79']:'');
			$QuotFields["q79"] = true;	

			$Collector["q80"] = (intval($_POST['q80'])>0?$_POST['q80']:'');
			$QuotFields["q80"] = true;	

			$Collector["q81"] = (intval($_POST['q81'])>0?$_POST['q81']:'');
			$QuotFields["q81"] = true;
			
			$Collector["q82"] = (intval($_POST['q82'])>0?$_POST['q82']:'');
			$QuotFields["q82"] = true;	
			
			$Collector["q83"] = (intval($_POST['q83'])>0?$_POST['q83']:'');
			$QuotFields["q83"] = true;
			
			$Collector["q84"] = (intval($_POST['q84'])>0?$_POST['q84']:'');
			$QuotFields["q84"] = true;
			
			$Collector["q85"] = (intval($_POST['q85'])>0?$_POST['q85']:'');
			$QuotFields["q85"] = true;	
			
			$Collector["q86"] = (intval($_POST['q86'])>0?$_POST['q86']:'');
			$QuotFields["q86"] = true;	
			
			$Collector["q87"] = (intval($_POST['q87'])>0?$_POST['q87']:'');
			$QuotFields["q87"] = true;	
			
			$q88=($_POST['q88-1']=='on'?'1':'0').($_POST['q88-2']=='on'?'1':'0').($_POST['q88-3']=='on'?'1':'0').($_POST['q88-4']=='on'?'1':'0').($_POST['q88-5']=='on'?'1':'0').($_POST['q88-6']=='on'?'1':'0').($_POST['q88-7']=='on'?'1':'0').($_POST['q88-8']=='on'?'1':'0').($_POST['q88-9']=='on'?'1':'0');
			$Collector["q88"] = $q88;
			$QuotFields["q88"] = true;	

			//End section
		} else if($_POST["myOption"]==5){			
			//Section 5
			$Collector["q89"] = (intval($_POST['q89'])>0?$_POST['q89']:'');
			$QuotFields["q89"] = true;
			
			$Collector["q90"] = (intval($_POST['q90'])>0?$_POST['q90']:'');
			$QuotFields["q90"] = true;	
			
			$Collector["q91"] = (intval($_POST['q91'])>0?$_POST['q91']:'');
			$QuotFields["q91"] = true;	
			
			$Collector["q92"] = (intval($_POST['q92'])>0?$_POST['q92']:'');
			$QuotFields["q92"] = true;	
			
			$Collector["q93"] = (intval($_POST['q93'])>0?$_POST['q93']:'');
			$QuotFields["q93"] = true;	
			
			$Collector["q94"] = (intval($_POST['q94'])>0?$_POST['q94']:'');
			$QuotFields["q94"] = true;	
			
			$Collector["q94"] = (intval($_POST['q94'])>0?$_POST['q94']:'');
			$QuotFields["q94"] = true;	
			
			$Collector["q96"] = (intval($_POST['q96'])>0?$_POST['q96']:'');
			$QuotFields["q96"] = true;	
			
			$Collector["q97"] = (intval($_POST['q97'])>0?$_POST['q97']:'');
			$QuotFields["q97"] = true;	
			
			$Collector["q98"] = (intval($_POST['q98'])>0?$_POST['q98']:'');
			$QuotFields["q98"] = true;	
			
			$Collector["q99"] = (intval($_POST['q99'])>0?$_POST['q99']:'');
			$QuotFields["q99"] = true;
			
			$Collector["q100"] = (intval($_POST['q100'])>0?$_POST['q100']:'');
			$QuotFields["q100"] = true;	
			
			$Collector["q101"] = (intval($_POST['q101'])>0?$_POST['q101']:'');
			$QuotFields["q101"] = true;	
			
			$Collector["q102"] = (intval($_POST['q102'])>0?$_POST['q102']:'');
			$QuotFields["q102"] = true;	
			
			$Collector["q103"] = (intval($_POST['q103'])>0?$_POST['q103']:'');
			$QuotFields["q103"] = true;	
			
			$Collector["q104"] = (intval($_POST['q104'])>0?$_POST['q104']:'');
			$QuotFields["q104"] = true;	
			
			$Collector["q105"] = (intval($_POST['q105'])>0?$_POST['q105']:'');
			$QuotFields["q105"] = true;	
			
			$Collector["q106"] = (intval($_POST['q106'])>0?$_POST['q106']:'');
			$QuotFields["q106"] = true;	
			
			$Collector["q107"] = (intval($_POST['q107'])>0?$_POST['q107']:'');
			$QuotFields["q107"] = true;	
			
			$Collector["q108"] = (intval($_POST['q108'])>0?$_POST['q108']:'');
			$QuotFields["q108"] = true;	
			
			$Collector["q109"] = (intval($_POST['q109'])>0?$_POST['q109']:'');
			$QuotFields["q109"] = true;	
			
			$Collector["q110"] = (intval($_POST['q110'])>0?$_POST['q110']:'');
			$QuotFields["q110"] = true;	
			
			$Collector["q111"] = (intval($_POST['q111'])>0?$_POST['q111']:'');
			$QuotFields["q111"] = true;	
			
			$Collector["q112"] = (intval($_POST['q112'])>0?$_POST['q112']:'');
			$QuotFields["q112"] = true;	
			
			$Collector["q113"] = (intval($_POST['q113'])>0?$_POST['q113']:'');
			$QuotFields["q113"] = true;	
			
			$Collector["q114"] = (intval($_POST['q114'])>0?$_POST['q114']:'');
			$QuotFields["q114"] = true;	
			
			$Collector["q115"] = (intval($_POST['q115'])>0?$_POST['q115']:'');
			$QuotFields["q115"] = true;	
			
			$Collector["q116"] = (intval($_POST['q116'])>0?$_POST['q116']:'');
			$QuotFields["q116"] = true;
			
			$Collector["q117"] = (intval($_POST['q117'])>0?$_POST['q117']:'');
			$QuotFields["q117"] = true;	
			
			$Collector["q118"] = (intval($_POST['q118'])>0?$_POST['q118']:'');
			$QuotFields["q118"] = true;	
			//Emd section
		} else if($_POST["myOption"]==6){			
			//Section 6
			$Collector["q119"] = (intval($_POST['q119'])>0?$_POST['q119']:'');
			$QuotFields["q119"] = true;	

			$Collector["q120"] = (intval($_POST['q120'])>0?$_POST['q120']:'');
			$QuotFields["q120"] = true;	

			$Collector["q121"] = (intval($_POST['q121'])>0?$_POST['q121']:'');
			$QuotFields["q121"] = true;	

			$Collector["q122"] = (intval($_POST['q122'])>0?$_POST['q122']:'');
			$QuotFields["q122"] = true;

			$Collector["q123"] = (intval($_POST['q123'])>0?$_POST['q123']:'');
			$QuotFields["q123"] = true;	

			$Collector["q124"] = (intval($_POST['q124'])>0?$_POST['q124']:'');
			$QuotFields["q124"] = true;	

			$Collector["q125"] = (intval($_POST['q125'])>0?$_POST['q125']:'');
			$QuotFields["q125"] = true;	

			$Collector["q126"] = (intval($_POST['q126'])>0?$_POST['q126']:'');
			$QuotFields["q126"] = true;	

			$Collector["q127"] = (intval($_POST['q127'])>0?$_POST['q127']:'');
			$QuotFields["q127"] = true;	

			$Collector["q128"] = (intval($_POST['q128'])>0?$_POST['q128']:'');
			$QuotFields["q128"] = true;	

			$Collector["q129"] = (intval($_POST['q129'])>0?$_POST['q129']:'');
			$QuotFields["q129"] = true;	

			$Collector["q130"] = (intval($_POST['q130'])>0?$_POST['q130']:'');
			$QuotFields["q130"] = true;	

			$Collector["q131"] = (intval($_POST['q131'])>0?$_POST['q131']:'');
			$QuotFields["q131"] = true;	

			$Collector["q132"] = (intval($_POST['q132'])>0?$_POST['q132']:'');
			$QuotFields["q132"] = true;		

			$Collector["q133"] = (intval($_POST['q133'])>0?$_POST['q133']:'');
			$QuotFields["q133"] = true;	

			$Collector["q134"] = (intval($_POST['q134'])>0?$_POST['q134']:'');
			$QuotFields["q134"] = true;	

			$Collector["q135"] = (intval($_POST['q135'])>0?$_POST['q135']:'');
			$QuotFields["q135"] = true;	

			$Collector["q136"] = (intval($_POST['q136'])>0?$_POST['q136']:'');
			$QuotFields["q136"] = true;	

			$Collector["q137"] = (intval($_POST['q137'])>0?$_POST['q137']:'');
			$QuotFields["q137"] = true;	

			$Collector["q138"] = (intval($_POST['q138'])>0?$_POST['q138']:'');
			$QuotFields["q138"] = true;	

			$Collector["q139"] = (intval($_POST['q139'])>0?$_POST['q139']:'');
			$QuotFields["q139"] = true;	

			$Collector["q140"] = (intval($_POST['q140'])>0?$_POST['q140']:'');
			$QuotFields["q140"] = true;	

			$Collector["q141"] = (intval($_POST['q141'])>0?$_POST['q141']:'');
			$QuotFields["q141"] = true;	

			$Collector["q142"] = (intval($_POST['q142'])>0?$_POST['q142']:'');
			$QuotFields["q142"] = true;	

			$Collector["q143"] = (intval($_POST['q143'])>0?$_POST['q143']:'');
			$QuotFields["q143"] = true;	

			$Collector["q144"] = (intval($_POST['q144'])>0?$_POST['q144']:'');
			$QuotFields["q144"] = true;	

			$Collector["q145"] = (intval($_POST['q145'])>0?$_POST['q145']:'');
			$QuotFields["q145"] = true;	

			$Collector["q146"] = (intval($_POST['q146'])>0?$_POST['q146']:'');
			$QuotFields["q146"] = true;	

			$Collector["q147"] = (intval($_POST['q147'])>0?$_POST['q147']:'');
			$QuotFields["q147"] = true;	
			//End section
		} else if($_POST["myOption"]==7){			
			//Section 7
			$Collector["q148"] = (intval($_POST['q148'])>0?$_POST['q148']:'');
			$QuotFields["q148"] = true;		

			$Collector["q149"] = (intval($_POST['q149'])>0?$_POST['q149']:'');
			$QuotFields["q149"] = true;	

			$Collector["q150"] = (intval($_POST['q150'])>0?$_POST['q150']:'');
			$QuotFields["q150"] = true;	

			$Collector["q151"] = (intval($_POST['q151'])>0?$_POST['q151']:'');
			$QuotFields["q151"] = true;	

			$Collector["q152"] = (intval($_POST['q152'])>0?$_POST['q152']:'');
			$QuotFields["q152"] = true;	
			
			$Collector["q153"] = (intval($_POST['q153'])>0?$_POST['q153']:'');
			$QuotFields["q153"] = true;	
			
			$q154=($_POST['q154-1']=='on'?'1':'0').($_POST['q154-2']=='on'?'1':'0').($_POST['q154-3']=='on'?'1':'0').($_POST['q154-4']=='on'?'1':'0').($_POST['q154-5']=='on'?'1':'0').($_POST['q154-6']=='on'?'1':'0').($_POST['q154-7']=='on'?'1':'0').($_POST['q154-8']=='on'?'1':'0').($_POST['q154-9']=='on'?'1':'0');
			$Collector["q154"] = $q154;
			$QuotFields["q154"] = true;	

			$Collector["q155"] = (intval($_POST['q155'])>0?$_POST['q155']:'');
			$QuotFields["q155"] = true;	

			$Collector["q156"] = (intval($_POST['q156'])>0?$_POST['q156']:'');
			$QuotFields["q156"] = true;	

			$Collector["q157"] = (intval($_POST['q157'])>0?$_POST['q157']:'');
			$QuotFields["q157"] = true;	

			$Collector["q158"] = (intval($_POST['q158'])>0?$_POST['q158']:'');
			$QuotFields["q158"] = true;	

			$Collector["q159"] = (intval($_POST['q159'])>0?$_POST['q159']:'');
			$QuotFields["q159"] = true;	

			$Collector["q160"] = (intval($_POST['q160'])>0?$_POST['q160']:'');
			$QuotFields["q160"] = true;	

			$Collector["q161"] = (intval($_POST['q161'])>0?$_POST['q161']:'');
			$QuotFields["q161"] = true;	

			$Collector["q162"] = (intval($_POST['q162'])>0?$_POST['q162']:'');
			$QuotFields["q162"] = true;	

			$Collector["q163"] = (intval($_POST['q163'])>0?$_POST['q163']:'');
			$QuotFields["q163"] = true;	
			
			$q164=($_POST['q164-1']=='on'?'1':'0').($_POST['q164-2']=='on'?'1':'0').($_POST['q164-3']=='on'?'1':'0').($_POST['q164-4']=='on'?'1':'0').($_POST['q164-5']=='on'?'1':'0').($_POST['q164-6']=='on'?'1':'0').($_POST['q164-7']=='on'?'1':'0').($_POST['q164-8']=='on'?'1':'0').($_POST['q164-9']=='on'?'1':'0');
			$Collector["q164"] = $q164;
			$QuotFields["q164"] = true;	
			
			$q165=($_POST['q165-1']=='on'?'1':'0').($_POST['q165-2']=='on'?'1':'0').($_POST['q165-3']=='on'?'1':'0').($_POST['q165-4']=='on'?'1':'0').($_POST['q165-5']=='on'?'1':'0');
			$Collector["q165"] = $q165;
			$QuotFields["q165"] = true;	
			
			$Collector["q166"] = (intval($_POST['q166'])>0?$_POST['q166']:'');
			$QuotFields["q166"] = true;	
						
			$Collector["q167"] = (intval($_POST['q167'])>0?$_POST['q167']:'');
			$QuotFields["q167"] = true;	
			
			$Collector["q168"] = (intval($_POST['q168'])>0?$_POST['q168']:'');
			$QuotFields["q168"] = true;	
			
			$Collector["q169"] = (intval($_POST['q169'])>0?$_POST['q169']:'');
			$QuotFields["q169"] = true;	
			
			$Collector["q170"] = (intval($_POST['q170'])>0?$_POST['q170']:'');
			$QuotFields["q170"] = true;	
			
			$Collector["q171"] = (intval($_POST['q171'])>0?$_POST['q171']:'');
			$QuotFields["q171"] = true;	
			
			$Collector["q172"] = (intval($_POST['q172'])>0?$_POST['q172']:'');
			$QuotFields["q172"] = true;	

			$Collector["q173"] = (intval($_POST['q173'])>0?$_POST['q173']:'');
			$QuotFields["q173"] = true;	
			//End section
		} else if($_POST["myOption"]==8){			
			//Section 8
			$Collector["q174"] = (intval($_POST['q174'])>0?$_POST['q174']:'');
			$QuotFields["q174"] = true;	

			$Collector["q175"] = (intval($_POST['q175'])>0?$_POST['q175']:'');
			$QuotFields["q175"] = true;	

			$Collector["q176"] = (intval($_POST['q176'])>0?$_POST['q176']:'');
			$QuotFields["q176"] = true;	

			$Collector["q177"] = (intval($_POST['q177'])>0?$_POST['q177']:'');
			$QuotFields["q177"] = true;	

			$Collector["q178"] = (intval($_POST['q178'])>0?$_POST['q178']:'');
			$QuotFields["q178"] = true;	

			$Collector["q179"] = (intval($_POST['q179'])>0?$_POST['q179']:'');
			$QuotFields["q179"] = true;	

			$Collector["q180"] = (intval($_POST['q180'])>0?$_POST['q180']:'');
			$QuotFields["q180"] = true;	

			$Collector["q181"] = (intval($_POST['q181'])>0?$_POST['q181']:'');
			$QuotFields["q181"] = true;	

			//End section
		} else if($_POST["myOption"]==9){			
			//Section 9
			$Collector["q182"] = (intval($_POST['q182'])>0?$_POST['q182']:'');
			$QuotFields["q182"] = true;	

			$Collector["q183"] = (intval($_POST['q183'])>0?$_POST['q183']:'');
			$QuotFields["q183"] = true;	

			$Collector["q184"] = (intval($_POST['q184'])>0?$_POST['q184']:'');
			$QuotFields["q184"] = true;	

			$Collector["q185"] = (intval($_POST['q185'])>0?$_POST['q185']:'');
			$QuotFields["q185"] = true;	

			$Collector["q186"] = (intval($_POST['q186'])>0?$_POST['q186']:'');
			$QuotFields["q186"] = true;	

			$Collector["q187"] = (intval($_POST['q187'])>0?$_POST['q187']:'');
			$QuotFields["q187"] = true;	

			$Collector["q188"] = (intval($_POST['q188'])>0?$_POST['q188']:'');
			$QuotFields["q188"] = true;	

			$Collector["q189"] = (intval($_POST['q189'])>0?$_POST['q189']:'');
			$QuotFields["q189"] = true;	
			
			/*
				q1 13
				q2 6
				q3 42
				q4 34
				q5 10
				q6 30
				q7 29
				q8 26
				q9 8
				q10 8
				Total 206
			*/
		}
		
		
		
			//calculate total completed 
	if(isset($_GET["item"]) && $_GET["item"] != "" && intval($_GET["item"])> 0){
		$rowCompleted=$db->RowSelectorQuery("SELECT * FROM children WHERE children_id=".$_GET['item']);
		$languages=$rowCompleted['languages'];
		$q3=$rowCompleted['q3'];
		$q4=$rowCompleted['q4'];
		$q72=$rowCompleted['q72'];
		$q76=$rowCompleted['q76'];
		$q88=$rowCompleted['q88'];
		$q154=$rowCompleted['q154'];
		$q164=$rowCompleted['q154']; 
		$q165=$rowCompleted['q154'];	

		$completed1=$completed1+($rowCompleted["nickname"]!=''?1:0); //1
		$completed1=$completed1+($rowCompleted["weight"]!=''?1:0); //2
		$completed1=$completed1+($rowCompleted["height"]!=''?1:0); //3
		$completed1=$completed1+(bindec($languages)>0?1:0); //4
		$completed1=$completed1+($rowCompleted["nativelang"]!=''?1:0); //5
		$completed1=$completed1+($rowCompleted["otherlang"]!=''?1:0); //6
		$completed1=$completed1+($rowCompleted["birthdate"]!=''?1:0); //7
		$completed1=$completed1+($rowCompleted["sex"]!=''?1:0); //8
		$completed1=$completed1+($rowCompleted["city"]!=''?1:0); //9
		$completed1=$completed1+($rowCompleted["region"]!=''?1:0); //10
		$completed1=$completed1+($rowCompleted["school"]!=''?1:0); //11
		$completed1=$completed1+($rowCompleted["schoolclass"]!=''?1:0); //12	
		$completed1=$completed1+($rowCompleted["schoolclassid"]!=''?1:0); //13	

		$completed2=$completed2+($rowCompleted["q1"]!=''?1:0); //1	
		$completed2=$completed2+($rowCompleted["q2"]!=''?1:0); //2
		$completed2=$completed2+(bindec($q3)>0?1:0); //3
		$completed2=$completed2+(bindec($q4)>0?1:0); //4
		$completed2=$completed2+($rowCompleted["q5"]!=''?1:0); //5
		$completed2=$completed2+($rowCompleted["q6"]!=''?1:0); //6
		
		$completed3=$completed3+($rowCompleted["q7"]!=''?1:0); //1
		$completed3=$completed3+($rowCompleted["q8"]!=''?1:0); //2
		$completed3=$completed3+($rowCompleted["q9"]!=''?1:0); //3
		$completed3=$completed3+($rowCompleted["q10"]!=''?1:0); //4
		$completed3=$completed3+($rowCompleted["q11"]!=''?1:0); //5
		$completed3=$completed3+($rowCompleted["q12"]!=''?1:0); //6
		$completed3=$completed3+($rowCompleted["q13"]!=''?1:0); //7
		$completed3=$completed3+($rowCompleted["q14"]!=''?1:0); //8
		$completed3=$completed3+($rowCompleted["q15"]!=''?1:0); //9
		$completed3=$completed3+($rowCompleted["q16"]!=''?1:0); //10
		$completed3=$completed3+($rowCompleted["q17"]!=''?1:0); //11
		$completed3=$completed3+($rowCompleted["q18"]!=''?1:0); //12
		$completed3=$completed3+($rowCompleted["q19"]!=''?1:0); //13
		$completed3=$completed3+($rowCompleted["q20"]!=''?1:0); //14
		$completed3=$completed3+($rowCompleted["q21"]!=''?1:0); //15
		$completed3=$completed3+($rowCompleted["q22"]!=''?1:0); //16
		$completed3=$completed3+($rowCompleted["q23"]!=''?1:0); //17
		$completed3=$completed3+($rowCompleted["q24"]!=''?1:0); //18
		$completed3=$completed3+($rowCompleted["q25"]!=''?1:0); //19
		$completed3=$completed3+($rowCompleted["q26"]!=''?1:0); //20
		$completed3=$completed3+($rowCompleted["q27"]!=''?1:0); //21
		$completed3=$completed3+($rowCompleted["q28"]!=''?1:0); //22
		$completed3=$completed3+($rowCompleted["q29"]!=''?1:0); //23
		$completed3=$completed3+($rowCompleted["q30"]!=''?1:0); //24
		$completed3=$completed3+($rowCompleted["q31"]!=''?1:0); //25
		$completed3=$completed3+($rowCompleted["q32"]!=''?1:0); //26
		$completed3=$completed3+($rowCompleted["q33"]!=''?1:0); //27
		$completed3=$completed3+($rowCompleted["q34"]!=''?1:0); //28
		$completed3=$completed3+($rowCompleted["q35"]!=''?1:0); //29
		$completed3=$completed3+($rowCompleted["q36"]!=''?1:0); //30
		$completed3=$completed3+($rowCompleted["q37"]!=''?1:0); //31
		$completed3=$completed3+($rowCompleted["q38"]!=''?1:0); //32
		$completed3=$completed3+($rowCompleted["q39"]!=''?1:0); //33
		$completed3=$completed3+($rowCompleted["q40"]!=''?1:0); //34
		$completed3=$completed3+($rowCompleted["q41"]!=''?1:0); //35
		$completed3=$completed3+($rowCompleted["q42"]!=''?1:0); //36
		$completed3=$completed3+($rowCompleted["q43"]!=''?1:0); //37
		$completed3=$completed3+($rowCompleted["q44"]!=''?1:0); //38
		$completed3=$completed3+($rowCompleted["q45"]!=''?1:0); //39
		$completed3=$completed3+($rowCompleted["q46"]!=''?1:0); //40
		$completed3=$completed3+($rowCompleted["q47"]!=''?1:0); //41
		$completed3=$completed3+($rowCompleted["q48"]!=''?1:0); //42
		

		
		$completed4=$completed4+($rowCompleted["q49"]!=''?1:0); //1
		$completed4=$completed4+($rowCompleted["q50"]!=''?1:0); //2
		$completed4=$completed4+($rowCompleted["q51"]!=''?1:0); //3
		$completed4=$completed4+($rowCompleted["q52"]!=''?1:0); //4
		$completed4=$completed4+($rowCompleted["q53"]!=''?1:0); //5
		$completed4=$completed4+($rowCompleted["q54"]!=''?1:0); //6
		$completed4=$completed4+($rowCompleted["q55"]!=''?1:0); //7
		$completed4=$completed4+($rowCompleted["q56"]!=''?1:0); //8
		$completed4=$completed4+($rowCompleted["q57"]!=''?1:0); //9
		$completed4=$completed4+($rowCompleted["q58"]!=''?1:0); //10
		$completed4=$completed4+($rowCompleted["q59"]!=''?1:0); //11
		$completed4=$completed4+($rowCompleted["q60"]!=''?1:0); //12
		$completed4=$completed4+($rowCompleted["q61"]!=''?1:0); //13
		$completed4=$completed4+($rowCompleted["q62"]!=''?1:0); //14
		$completed4=$completed4+($rowCompleted["q63"]!=''?1:0); //15
		$completed4=$completed4+($rowCompleted["q64"]!=''?1:0); //16
		$completed4=$completed4+($rowCompleted["q65"]!=''?1:0); //17
		$completed4=$completed4+($rowCompleted["q66"]!=''?1:0); //18
		$completed4=$completed4+($rowCompleted["q67"]!=''?1:0); //19
		$completed4=$completed4+($rowCompleted["q68"]!=''?1:0); //20
		$completed4=$completed4+($rowCompleted["q190"]!=''?1:0); //21
		$completed4=$completed4+($rowCompleted["q191"]!=''?1:0); //22
		$completed4=$completed4+($rowCompleted["q192"]!=''?1:0); //23
		$completed4=$completed4+($rowCompleted["q69"]!=''?1:0); //24
		$completed4=$completed4+($rowCompleted["q70"]!=''?1:0); //25
		$completed4=$completed4+($rowCompleted["q71"]!=''?1:0); //26
		$completed4=$completed4+(bindec($q72)>0?1:0); //27
		$completed4=$completed4+($rowCompleted["q193"]!=''?1:0); //28
		$completed4=$completed4+($rowCompleted["q73"]!=''?1:0); //29
		$completed4=$completed4+($rowCompleted["q74"]!=''?1:0); //30
		$completed4=$completed4+($rowCompleted["q75"]!=''?1:0); //31
		$completed4=$completed4+(bindec($q76)>0?1:0); //32
		$completed4=$completed4+($rowCompleted["q77"]!=''?1:0); //33
		$completed4=$completed4+($rowCompleted["q78"]!=''?1:0); //34

		$completed5=$completed5+($rowCompleted["q79"]!=''?1:0); //1
		$completed5=$completed5+($rowCompleted["q80"]!=''?1:0); //2
		$completed5=$completed5+($rowCompleted["q81"]!=''?1:0); //3
		$completed5=$completed5+($rowCompleted["q82"]!=''?1:0); //4
		$completed5=$completed5+($rowCompleted["q83"]!=''?1:0); //5
		$completed5=$completed5+($rowCompleted["q84"]!=''?1:0); //6
		$completed5=$completed5+($rowCompleted["q85"]!=''?1:0); //7
		$completed5=$completed5+($rowCompleted["q86"]!=''?1:0); //8
		$completed5=$completed5+($rowCompleted["q87"]!=''?1:0); //9
		$completed5=$completed5+(bindec($q88)>0?1:0); //10

		
		$completed6=$completed6+($rowCompleted["q89"]!=''?1:0); //1	
		$completed6=$completed6+($rowCompleted["q90"]!=''?1:0); //2
		$completed6=$completed6+($rowCompleted["q91"]!=''?1:0); //3	
		$completed6=$completed6+($rowCompleted["q92"]!=''?1:0); //4
		$completed6=$completed6+($rowCompleted["q93"]!=''?1:0); //5
		$completed6=$completed6+($rowCompleted["q94"]!=''?1:0); //6
		$completed6=$completed6+($rowCompleted["q94"]!=''?1:0); //7
		$completed6=$completed6+($rowCompleted["q96"]!=''?1:0); //8
		$completed6=$completed6+($rowCompleted["q97"]!=''?1:0); //9
		$completed6=$completed6+($rowCompleted["q98"]!=''?1:0); //10
		$completed6=$completed6+($rowCompleted["q99"]!=''?1:0); //11	
		$completed6=$completed6+($rowCompleted["q100"]!=''?1:0); //12
		$completed6=$completed6+($rowCompleted["q101"]!=''?1:0); //13
		$completed6=$completed6+($rowCompleted["q102"]!=''?1:0); //14
		$completed6=$completed6+($rowCompleted["q103"]!=''?1:0); //15
		$completed6=$completed6+($rowCompleted["q104"]!=''?1:0); //16
		$completed6=$completed6+($rowCompleted["q105"]!=''?1:0); //17
		$completed6=$completed6+($rowCompleted["q106"]!=''?1:0); //18
		$completed6=$completed6+($rowCompleted["q107"]!=''?1:0); //19
		$completed6=$completed6+($rowCompleted["q108"]!=''?1:0); //20
		$completed6=$completed6+($rowCompleted["q109"]!=''?1:0); //21
		$completed6=$completed6+($rowCompleted["q110"]!=''?1:0); //22
		$completed6=$completed6+($rowCompleted["q111"]!=''?1:0); //23
		$completed6=$completed6+($rowCompleted["q112"]!=''?1:0); //24
		$completed6=$completed6+($rowCompleted["q113"]!=''?1:0); //25
		$completed6=$completed6+($rowCompleted["q114"]!=''?1:0); //26
		$completed6=$completed6+($rowCompleted["q115"]!=''?1:0); //27
		$completed6=$completed6+($rowCompleted["q116"]!=''?1:0); //28	
		$completed6=$completed6+($rowCompleted["q117"]!=''?1:0); //29	
		$completed6=$completed6+($rowCompleted["q118"]!=''?1:0); //30			

		$completed7=$completed7+($rowCompleted["q119"]!=''?1:0); //1
		$completed7=$completed7+($rowCompleted["q120"]!=''?1:0); //2
		$completed7=$completed7+($rowCompleted["q121"]!=''?1:0); //3
		$completed7=$completed7+($rowCompleted["q122"]!=''?1:0); //4
		$completed7=$completed7+($rowCompleted["q123"]!=''?1:0); //5
		$completed7=$completed7+($rowCompleted["q124"]!=''?1:0); //6
		$completed7=$completed7+($rowCompleted["q125"]!=''?1:0); //7
		$completed7=$completed7+($rowCompleted["q126"]!=''?1:0); //8
		$completed7=$completed7+($rowCompleted["q127"]!=''?1:0); //9
		$completed7=$completed7+($rowCompleted["q128"]!=''?1:0); //10
		$completed7=$completed7+($rowCompleted["q129"]!=''?1:0); //11
		$completed7=$completed7+($rowCompleted["q130"]!=''?1:0); //12
		$completed7=$completed7+($rowCompleted["q131"]!=''?1:0); //13
		$completed7=$completed7+($rowCompleted["q132"]!=''?1:0); //14	
		$completed7=$completed7+($rowCompleted["q133"]!=''?1:0); //15
		$completed7=$completed7+($rowCompleted["q134"]!=''?1:0); //16
		$completed7=$completed7+($rowCompleted["q135"]!=''?1:0); //17
		$completed7=$completed7+($rowCompleted["q136"]!=''?1:0); //18
		$completed7=$completed7+($rowCompleted["q137"]!=''?1:0); //19
		$completed7=$completed7+($rowCompleted["q138"]!=''?1:0); //20
		$completed7=$completed7+($rowCompleted["q139"]!=''?1:0); //21
		$completed7=$completed7+($rowCompleted["q140"]!=''?1:0); //22
		$completed7=$completed7+($rowCompleted["q141"]!=''?1:0); //23
		$completed7=$completed7+($rowCompleted["q142"]!=''?1:0); //24
		$completed7=$completed7+($rowCompleted["q143"]!=''?1:0); //25
		$completed7=$completed7+($rowCompleted["q144"]!=''?1:0); //26
		$completed7=$completed7+($rowCompleted["q145"]!=''?1:0); //27
		$completed7=$completed7+($rowCompleted["q146"]!=''?1:0); //28
		$completed7=$completed7+($rowCompleted["q147"]!=''?1:0); //29		

		$completed8=$completed8+($rowCompleted["q148"]!=''?1:0); //1	
		$completed8=$completed8+($rowCompleted["q149"]!=''?1:0); //2
		$completed8=$completed8+($rowCompleted["q150"]!=''?1:0); //3
		$completed8=$completed8+($rowCompleted["q151"]!=''?1:0); //4
		$completed8=$completed8+($rowCompleted["q152"]!=''?1:0); //5
		$completed8=$completed8+($rowCompleted["q153"]!=''?1:0); //6
		$completed8=$completed8+(bindec($q154)>0?1:0); //7
		$completed8=$completed8+($rowCompleted["q155"]!=''?1:0); //8
		$completed8=$completed8+($rowCompleted["q156"]!=''?1:0); //9
		$completed8=$completed8+($rowCompleted["q157"]!=''?1:0); //10
		$completed8=$completed8+($rowCompleted["q158"]!=''?1:0); //11
		$completed8=$completed8+($rowCompleted["q159"]!=''?1:0); //12
		$completed8=$completed8+($rowCompleted["q160"]!=''?1:0); //13
		$completed8=$completed8+($rowCompleted["q161"]!=''?1:0); //14
		$completed8=$completed8+($rowCompleted["q162"]!=''?1:0); //15
		$completed8=$completed8+($rowCompleted["q163"]!=''?1:0); //16
		$completed8=$completed8+(bindec($q164)>0?1:0); //17
		$completed8=$completed8+(bindec($q165)>0?1:0); //18
		$completed8=$completed8+($rowCompleted["q166"]!=''?1:0); //19
		$completed8=$completed8+($rowCompleted["q167"]!=''?1:0); //20
		$completed8=$completed8+($rowCompleted["q168"]!=''?1:0); //21
		$completed8=$completed8+($rowCompleted["q169"]!=''?1:0); //22
		$completed8=$completed8+($rowCompleted["q170"]!=''?1:0); //23
		$completed8=$completed8+($rowCompleted["q171"]!=''?1:0); //24
		$completed8=$completed8+($rowCompleted["q172"]!=''?1:0); //25
		$completed8=$completed8+($rowCompleted["q173"]!=''?1:0); //26	

		$completed9=$completed9+($rowCompleted["q174"]!=''?1:0); //1
		$completed9=$completed9+($rowCompleted["q175"]!=''?1:0); //2
		$completed9=$completed9+($rowCompleted["q176"]!=''?1:0); //3
		$completed9=$completed9+($rowCompleted["q177"]!=''?1:0); //4
		$completed9=$completed9+($rowCompleted["q178"]!=''?1:0); //5
		$completed9=$completed9+($rowCompleted["q179"]!=''?1:0); //6
		$completed9=$completed9+($rowCompleted["q180"]!=''?1:0); //7
		$completed9=$completed9+($rowCompleted["q181"]!=''?1:0); //8

		$completed10=$completed10+($rowCompleted["q182"]!=''?1:0); //1
		$completed10=$completed10+($rowCompleted["q183"]!=''?1:0); //2
		$completed10=$completed10+($rowCompleted["q184"]!=''?1:0); //3
		$completed10=$completed10+($rowCompleted["q185"]!=''?1:0); //4
		$completed10=$completed10+($rowCompleted["q186"]!=''?1:0); //5
		$completed10=$completed10+($rowCompleted["q187"]!=''?1:0); //6
		$completed10=$completed10+($rowCompleted["q188"]!=''?1:0); //7
		$completed10=$completed10+($rowCompleted["q189"]!=''?1:0); //8

		/*
		
		1=13
		2=6
		3=42
		4=34
		5=40		
		6=30
		7=29
		8=26
		9=8
		10=8
		*/
		
		$completed=$completed1+$completed2+$completed3+$completed4+$completed5+$completed6+$completed7+$completed8+$completed9+$completed110;
		$Collector["completed"] = $completed;
		$QuotFields["completed"] = true;
		
		if(intval($completed)==206 && $rowCompleted["date_completed"]==''){
			$Collector["date_completed"] = date('Y-m-d H:i:s');
			$QuotFields["date_completed"] = true;
		}
			
	}


			$db->ExecuteUpdater("children",$PrimaryKeys,$Collector,$QuotFields);
			$messages->addMessage("Αποθηκευτηκε!!!");
			Redirect($BaseUrl);
			
			
		} else if($_REQUEST["Command"] ==  "DELETE") { //$command[0] ==
			//$queryDel1='DELETE FROM packages';
			//$queryDel2='DELETE FROM packageitems';
			//$db->sql_query($queryDel1);
			//$db->sql_query($queryDel2);
			
			if($item != "")
			{
				$error=0;
				$check = $db->RowSelectorQuery('SELECT user_id FROM children WHERE children_id='.$item.' AND user_id='.$auth->UserId);
				if(intval($check['user_id'])==0) $error++;
				if($error==0) {	
					//$filter=($auth->UserType != "Administrator"?' AND user_id IN (SELECT user_id FROM users WHERE parent='.$auth->UserId.')':'');
					$filter="";
					$db->sql_query("DELETE FROM children WHERE children_id=" . $item);
					$messages->addMessage("DELETE!!!");
					Redirect($BaseUrl);
				} else {
					$messages->addMessage("Η διαγραφή δεν μπορεί να ολοκληρωθεί");
					Redirect($BaseUrl);
				}
			}
			/**/

		}
	}
//}

if(isset($_GET["item"])) {
	//$filter=" WHERE 1=1 AND user_auth!='Administrator '";
	//$filter=($auth->UserType != "Administrator"?' AND user_id='.$auth->UserId:'');
	$query="SELECT * FROM children WHERE children_id=".$_GET['item'].$filter." LIMIT 1";

	$dr_e = $db->RowSelectorQuery($query);
	if(intval($_GET["item"])> 0 && intval($dr_e['children_id'])==0){
		$messages->addMessage("NOT FOUND!!!");
		Redirect("index.php?com=children");		
	}
?>

<? if($_GET['option']=='0'){?>
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
					</div>
					<h2 class="card-title">Γενικά στοιχεία</h2>
				</header>
				<div class="card-body">
					<div class="form-horizontal form-bordered" method="get">
					<!--
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="inputDefault"><? //=active?></label>
							<div class="col-lg-6">
								<div class="checkbox-custom checkbox-default">
									<input type="checkbox" checked="" name="is_valid" id="is_valid" <? //=((isset($dr_e["is_valid"]) && $dr_e["is_valid"]=='True') ? 'checked':'')?>>
									<label for="is_valid"></label>
								</div>
							</div>
						</div>
						-->
						<input type='hidden' name='myOption' value='0'>
						<div class="row form-group">
							<div class="col-lg-4">
								<div class="form-group">
									<label class="col-form-label" for="nickname">Υποκοριστικό</label>
									<input type="text" class="form-control" id="nickname" name="nickname" value="<?=(isset($dr_e["nickname"]) ? $dr_e["nickname"]:'')?>">
								</div>
							</div>
							<div class="col-lg-2">
								<div class="form-group">
									<label class="col-form-label" for="sex">Φύλο</label>
									<select name="sex" id="sex" class="form-control mb-3">
										<option value="">Επιλογή Φύλου</option>
										<option value="1" <?=(isset($dr_e["sex"]) && $dr_e['sex']==1 ? 'selected':'')?>>Αρρεν</option>
										<option value="2" <?=(isset($dr_e["sex"]) && $dr_e['sex']==2 ? 'selected':'')?>>Θήλυ</option>
										<?
											//$filter=" AND is_valid='True'";
											//$resultRoles = $db->sql_query("SELECT * FROM roles WHERE 1=1 ".$filter." ORDER BY name ");
											//while ($drRoles = $db->sql_fetchrow($resultRoles)){
											//	echo '<option value="'.$drRoles['role_id'].'" '.($drRoles['role_id']==$dr_e['role_id']?' selected':'').'>'.$drRoles['name'].'</option>';
											//}
										?>
									</select>
								</div>
							</div>
							<div class="col-lg-2">
								<div class="form-group">
									<label class="col-form-label" for="weight">Βάρος (kgr)</label>
									<input type="text" class="form-control" id="weight" name="weight" value="<?=(isset($dr_e["weight"]) ? $dr_e["weight"]:'')?>">
								</div>
							</div>
							<div class="col-lg-2">
								<div class="form-group">
									<label class="col-form-label" for="height">Υψος (cm)</label>
									<input type="text" class="form-control" id="height" name="height" value="<?=(isset($dr_e["height"]) ? $dr_e["height"]:'')?>">
								</div>
							</div>
							<div class="col-lg-2">
								<div class="form-group">
									<div class="form-group">
										<label class="col-form-label" for="birthdate">Ημερομηνία Γέννησης</label>
									   <div class="input-group date" id="birthdate" data-target-input="nearest">
											<input type="text" name="birthdate" id="birthdate" class="form-control datetimepicker-input" data-target="#birthdate" value="<?=(isset($dr_e["birthdate"]) ? $dr_e["birthdate"]:'')?>" onkeydown="event.preventDefault()"/>
											<div class="input-group-append" data-target="#birthdate" data-toggle="datetimepicker">
												<div class="input-group-text"><i class="fa fa-calendar"></i></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<hr/>
						<div class="row form-group">
							<div class="col-lg-3">
								<div class="form-group">
									<label class="col-form-label" for="nativelang">η ελληνική γλώσσα είναι μητρική</label>
									<select name="nativelang" id="nativelang" class="form-control mb-3">
										<option value="">Επιλογή</option>
										<option value="1" <?=(isset($dr_e["nativelang"]) && $dr_e['nativelang']==1 ? 'selected':'')?>>Ναι</option>
										<option value="2" <?=(isset($dr_e["nativelang"]) && $dr_e['nativelang']==2 ? 'selected':'')?>>Οχι</option>
									</select>
								</div>
							</div>
							<div class="col-lg-6">
								<label class="col-form-label" for="nativelang">ΆΛλες γλώσσες</label>
								<div class="form-group row">
									<!-- <div class="control-label text-sm-right pt-1">ΆΛλες γλώσσες</div> -->
									<div class="checkbox-custom checkbox-default" style="margin-right:15px;">
										<input type="checkbox" id="languages-1" <?=(substr($dr_e['languages'],0,1)=='1'?'checked':'')?> name="languages-1">
										<label for="languages-1">Αγγλική</label>
									</div> 
									<div class="checkbox-custom checkbox-default" style="margin-right:15px;">
										<input type="checkbox"  id="languages-2" <?=(substr($dr_e['languages'],1,1)=='1'?'checked':'')?> name="languages-2">
										<label for="languages-2">Αλβανική</label>
									</div> 
									<div class="checkbox-custom checkbox-default" style="margin-right:15px;">
										<input type="checkbox"  id="languages-3" <?=(substr($dr_e['languages'],2,1)=='1'?'checked':'')?> name="languages-3">
										<label for="languages-3">Τούρκικη</label>
									</div> 
									<div class="checkbox-custom checkbox-default" style="margin-right:15px;">
										<input type="checkbox" id="languages-4" <?=(substr($dr_e['languages'],3,1)=='1'?'checked':'')?> name="languages-4">
										<label for="languages-4">Γερμανική</label>
									</div> 
									<div class="checkbox-custom checkbox-default" style="margin-right:15px;">
										<input type="checkbox"  id="languages-5" <?=(substr($dr_e['languages'],4,1)=='1'?'checked':'')?> name="languages-5">
										<label for="languages-5">Γαλλική</label>
									</div>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="form-group">
									<label class="col-form-label" for="otherlang">Άλλη</label>
									<input type="text" class="form-control" id="otherlang" name="otherlang" value="<?=(isset($dr_e["otherlang"]) ? $dr_e["otherlang"]:'')?>">
								</div>
							</div>
						</div>
						
						<script type="text/javascript">
						/*
							$(function () {

								$('#birthdate').datetimepicker({
									useCurrent: false,
									icons: {
										time: "fa fa-clock-o",
										date: "fa fa-calendar",
										up: "fa fa-arrow-up",
										down: "fa fa-arrow-down"
									}
								});
								//$("#birthdate").on("change.datetimepicker", function (e) {
									$('#birthdate').datetimepicker({
										format: 'LT'
									});
								//});
	   
							});
						*/
						$(function () {
							$('#birthdate').datetimepicker({
							  format: 'YYYY-MM-DD'
							});
						});
						</script>
					
						<div class="row form-group">
							<div class="col-lg-6">
								<div class="form-group">
									<label class="col-form-label" for="city">Τόπος διαμονής - πόλη</label>
									<input type="text" class="form-control" id="city" name="city" value="<?=(isset($dr_e["city"]) ? $dr_e["city"]:'')?>">
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group">
									<label class="col-form-label" for="region">Περιφερειακή ενότητα </label>
									<input type="text" class="form-control" id="region" name="region" value="<?=(isset($dr_e["region"]) ? $dr_e["region"]:'')?>">
								</div>
							</div>
						</div>
					
						<div class="row form-group">
							<div class="col-lg-4">
								<div class="form-group">
									<label class="col-form-label" for="school">Σχολείο που είναι εγγεγραμμένο</label>
									<input type="text" class="form-control" id="school" name="school" value="<?=(isset($dr_e["school"]) ? $dr_e["school"]:'')?>">
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label class="col-form-label" for="schoolclass">Τάξη σχολείου</label>
									<input type="text" class="form-control" id="schoolclass" name="schoolclass" value="<?=(isset($dr_e["schoolclass"]) ? $dr_e["schoolclass"]:'')?>">
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label class="col-form-label" for="schoolclassid">Τμήμα τάξης</label>
									<input type="text" class="form-control" id="schoolclassid" name="schoolclassid" value="<?=(isset($dr_e["schoolclassid"]) ? $dr_e["schoolclassid"]:'')?>">
								</div>
							</div>
						</div>
						<hr/>

						<script>
						$(document ).ready(function() {
							$("#custody").on('change', function() {
								if( this.value==1 ){
									//alert( this.value );
									$("#custodyagreeshow").show();
									$("#showparent").hide();
									$("#showparentagree").hide();
								} else if(this.value==2){
									$("#custodyagreeshow").hide();
									$("#showparent").show();	
									$("#showparentagree").show();	
								} else {
									$("#custodyagreeshow").hide();
									$("#showparent").hide();
									$("#showparentagree").hide();
								}
							});
						});
						</script>
						<? if($dr_e["custodyagree"]=='1'){
							
						?>
						<script>
						$(document ).ready(function() {
							$("#custodyagreeshow").show();
							$("#showparent").show();	
							$("#showparentagree").show();
						});
						</script>
						<?
						}
						
						?>
						<div class="row form-group">
							<div class="col-lg-5">
								<div class="form-group">
									<label class="col-form-label" for="custody">Έχετε πλήρη κηδεμονία και επιμέλεια του παιδιού ?:</label>
									<select name="custody" id="custody" class="form-control mb-3" >
										<option value="">Επιλογή</option>
										<option value="1" <?=(isset($dr_e["custody"]) && $dr_e['custody']==1 ? 'selected':'')?>>Ναι</option>
										<option value="2" <?=(isset($dr_e["custody"]) && $dr_e['custody']==2 ? 'selected':'')?>>Οχι</option>
									</select>
								</div>
							</div>
							<div class="col-lg-7" id="custodyagreeshow" style="display:none;">
								<label class="col-form-label" for="custodyagree">Δηλώνω υπεύθυνα ότι έχω πλήρη επιμέλεια και κηδεμονία του παιδιού</label>
								<div class="checkbox-custom checkbox-default">
									<input type="checkbox" id="custodyagree" name="custodyagree" <?=(substr($dr_e['custodyagree'],0,1)=='1'?'checked':'')?> >
									<label for="custodyagree">Συμφωνώ</label>
								</div>
							</div>
							<hr/>
						</div>
						
						<div class="row form-group" id="showparent" style="display:none;">
							<div class="col-lg-4">
								<div class="form-group">
									<label class="col-form-label" for="parentname">Ονοματεπώνυμο</label>
									<input type="text" class="form-control" id="parentname" name="parentname" value="<?=(isset($dr_e["parentname"]) ? $dr_e["parentname"]:'')?>">
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label class="col-form-label" for="parentemail">email</label>
									<input type="text" class="form-control" id="parentemail" name="parentemail" value="<?=(isset($dr_e["parentemail"]) ? $dr_e["parentemail"]:'')?>">
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label class="col-form-label" for="parentphone">Κινητό τηλέφωνο</label>
									<input type="text" class="form-control" id="parentphone" name="parentphone" value="<?=(isset($dr_e["parentphone"]) ? $dr_e["parentphone"]:'')?>">
								</div>
							</div>
						</div>
						<div class="row form-group" id="showparentagree" style="display:none;">
							<div class="col-lg-12">
								<div class="checkbox-custom checkbox-default">
									<input type="checkbox" id="parentagree" <?=(substr($dr_e['parentagree'],0,1)=='1'?'checked':'')?> name="parentagree">
									<label class="col-form-label" for="parentagree">Δηλώνω υπεύθυνα ότι ο ως άνω γονέας έχει ενημερωθεί και συναινεί πλήρως στη συλλογή στοιχείων για το παιδί</label>																			
								</div>								
							</div>
							<hr/>
						</div>
						
						<div class="form-group row">
							<div class="col-lg-12">
								<label class="col-form-label" for="region">Λοιπές παρατηρήσεις</label>
								<textarea class="form-control" name="description" id="description" rows="3"  data-plugin-textarea-autosize><?=$dr_e["description"]?></textarea>
							</div>
						</div>
						<div class="form-group row" style="margin-top:20px;">
							<a href="#" onClick="checkFields();"><button type="button" class="btn btn-primary">Αποθήκευση</button></a> 
							<a href="index.php?com=children"><button type="button" class="btn btn-primary" style="margin-left:3px;">Επιστροφή</button></a>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
<? } else if($_GET['option']=='1'){?>
	<div class="row">
		<div class="col">
			<section class="card form-wizard" id="w4">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
					</div>
					<h2 class="card-title">Δημογραφικά στοιχεία</h2>
				</header>
				<div class="card-body">
					<div class="wizard-progress wizard-progress-lg">
						<div class="steps-progress">
							<div class="progress-indicator"></div>
						</div>
						<ul class="nav wizard-steps">
							<li class="nav-item active">
								<a class="nav-link" href="#w4-1" data-toggle="tab"><span>1</span>Ενότητα 1</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-2" data-toggle="tab"><span>2</span>Ενότητα 2</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-3" data-toggle="tab"><span>3</span>Ενότητα 3</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-4" data-toggle="tab"><span>4</span>Ενότητα 4</a>
							</li>
						</ul>
					</div>
					<input type='hidden' name='myOption' value='1'>
					<div class="tab-content">
						<div id="w4-1" class="tab-pane active">
							<!-- 
							<div class="form-group row">
								<label class="col-sm-3 control-label text-sm-right pt-1" for="w4-username">Username</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="username" id="w4-username" required>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 control-label text-sm-right pt-1" for="w4-password">Password</label>
								<div class="col-sm-9">
									<input type="password" class="form-control" name="password" id="w4-password" required minlength="6">
								</div>
							</div>
							<hr/>
							-->
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1" >Παρακολουθεί τάξη: </div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q1-1" <?=($dr_e['q1']=='1'?'checked':'')?> value='1' name="q1">
										<label for="q1">κανονικό σχολείο</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q1-2"  <?=($dr_e['q1']=='2'?'checked':'')?> value='2' name="q1">
										<label for="q1-2">τάξη ένταξης - κανονικό σχολείο</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q1-3"  <?=($dr_e['q1']=='3'?'checked':'')?> value='3' name="q1">
										<label for="q1-3">ειδικό σχολείο</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Η φοίτηση είναι:</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q2-1"  <?=($dr_e['q2']=='1'?'checked':'')?> value="1" name="q2">
										<label for="q2">κανονική (συνεπής)</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q2-2"  <?=($dr_e['q2']=='2'?'checked':'')?>  value="2" name="q2">
										<label for="q2-2">σποραδική / ελλειπής</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q2-3"  <?=($dr_e['q2']=='3'?'checked':'')?> value="3" name="q2">
										<label for="q2-3">επανάληψη τάξης</label>
									</div>
								</div>
							</div>
							
						</div>
						<div id="w4-2" class="tab-pane">
							<!-- 							
							<div class="form-group row">
								<label class="col-sm-3 control-label text-sm-right pt-1" for="w4-first-name">First Name</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="first-name" id="w4-first-name" required>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 control-label text-sm-right pt-1" for="w4-last-name">Last Name</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="last-name" id="w4-last-name" required>
								</div>
							</div>
							-->
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Υπάρχει διάγνωση για κάποια από τις ακόλουθες διαταραχές;</div>
								<div class="col-lg-9">
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" id="q3-1" <?=(substr($dr_e['q3'],0,1)=='1'?'checked':'')?> name="q3-1">
										<label for="q3-1">πρόβλημα ακοής</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q3-2" <?=(substr($dr_e['q3'],1,1)=='1'?'checked':'')?> name="q3-2">
										<label for="q3-2">πρόβλημα όρασης</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q3-3" <?=(substr($dr_e['q3'],2,1)=='1'?'checked':'')?> name="q3-3">
										<label for="q3-3">Πρώιμες μαθησιακές δυσκολίες</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" id="q3-4" <?=(substr($dr_e['q3'],3,1)=='1'?'checked':'')?> name="q3-4">
										<label for="q3-4">αργοπορημένη εξέλιξη</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q3-5" <?=(substr($dr_e['q3'],4,1)=='1'?'checked':'')?> name="q3-5">
										<label for="q3-5">αυτισμός</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q3-6" <?=(substr($dr_e['q3'],5,1)=='1'?'checked':'')?> name="q3-6">
										<label for="q3-6">στέρηση/ παραμέληση</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q3-7" <?=(substr($dr_e['q3'],6,1)=='1'?'checked':'')?> name="q3-7">
										<label for="q3-7">εγκεφαλική παράλυση/παιδική αφασία</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q3-8" <?=(substr($dr_e['q3'],7,1)=='1'?'checked':'')?> name="q3-8">
										<label for="q3-8">Nοητική στέρηση</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q3-9" <?=(substr($dr_e['q3'],8,1)=='1'?'checked':'')?> name="q3-9">
										<label for="q3-9">Κανένα από τα παραπάνω</label>
									</div>
								</div>
							</div>

						</div>
						<div id="w4-3" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Υπάρχει διάγνωση για κάποια από τα ακόλουθα;</div>
								<div class="col-lg-9">
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-1" <?=(substr($dr_e['q4'],0,1)=='1'?'checked':'')?> name="q4-1">
										<label for="q4-1">Σύνδρομο Alport</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-2" <?=(substr($dr_e['q4'],1,1)=='1'?'checked':'')?> name="q4-2">
										<label for="q4-2">Σύνδρομο Apert</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-3"  <?=(substr($dr_e['q4'],2,1)=='1'?'checked':'')?> name="q4-3">
										<label for="q4-3">Σύνδρομο Cornelia – de Lange</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-4"  <?=(substr($dr_e['q4'],3,1)=='1'?'checked':'')?> name="q4-4">
										<label for="q4-4">Σύνδρομο Cri du Chat (της γαλής)</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-5" <?=(substr($dr_e['q4'],4,1)=='1'?'checked':'')?> name="q4-5">
										<label for="q4-5">Σύνδρομο Crouzon</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-6" <?=(substr($dr_e['q4'],5,1)=='1'?'checked':'')?> name="q4-6">
										<label for="q4-6">Σύνδρομο Down (Τρισωμία 21)</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-7" <?=(substr($dr_e['q4'],6,1)=='1'?'checked':'')?> name="q4-7">
										<label for="q4-7">Σύνδρομο Εκτροδακτυλία</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-8" <?=(substr($dr_e['q4'],7,1)=='1'?'checked':'')?> name="q4-8">
										<label for="q4-8">Σύνδρομο Fetal Alcohol (FAS)</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-9" <?=(substr($dr_e['q4'],8,1)=='1'?'checked':'')?> name="q4-9">
										<label for="q4-9">Σύνδρομο Ευθραύστου Χ γονιδίου</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-10" <?=(substr($dr_e['q4'],9,1)=='1'?'checked':'')?> name="q4-10">
										<label for="q4-10">Σύνδρομο Goldenhar</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-11" <?=(substr($dr_e['q4'],10,1)=='1'?'checked':'')?> name="q4-11">
										<label for="q4-11">Βλενοπολυζακχαρίδοσης Σύνδρομα / Σύνδρομο Huler/ Hunter/ Maroteaux- Lamy/ Morquio</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-12" <?=(substr($dr_e['q4'],11,1)=='1'?'checked':'')?> name="q4-12">
										<label for="q4-12">Σύνδρομο Moebius</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-13" <?=(substr($dr_e['q4'],12,1)=='1'?'checked':'')?> name="q4-13">
										<label for="q4-13">Σύνδρομο Nooman</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-14" <?=(substr($dr_e['q4'],13,1)=='1'?'checked':'')?> name="q4-14">
										<label for="q4-14">Σύνδρομα Oro-Facial-Digital</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-15" <?=(substr($dr_e['q4'],14,1)=='1'?'checked':'')?> name="q4-15">
										<label for="q4-15">Σύνδρομο Pendred</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-16" <?=(substr($dr_e['q4'],15,1)=='1'?'checked':'')?> name="q4-16">
										<label for="q4-16">Σύνδρομο Pierre-Robin</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-17" <?=(substr($dr_e['q4'],16,1)=='1'?'checked':'')?> name="q4-17">
										<label for="q4-17">Σύνδρομο Prader-Willy</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-18" <?=(substr($dr_e['q4'],17,1)=='1'?'checked':'')?> name="q4-18">
										<label for="q4-18">Σύνδρομο Refsum</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-19" <?=(substr($dr_e['q4'],18,1)=='1'?'checked':'')?> name="q4-19">
										<label for="q4-19">Σύνδρομο Stickler</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-20" <?=(substr($dr_e['q4'],19,1)=='1'?'checked':'')?> name="q4-20">
										<label for="q4-20">Σύνδρομο Teacher Collins</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-21" <?=(substr($dr_e['q4'],20,1)=='1'?'checked':'')?> name="q4-21">
										<label for="q4-21">Σύνδρομο Turner</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-22" <?=(substr($dr_e['q4'],21,1)=='1'?'checked':'')?> name="q4-22">
										<label for="q4-22">Σύνδρομο Usher</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-23" <?=(substr($dr_e['q4'],22,1)=='1'?'checked':'')?> name="q4-23">
										<label for="q4-23">Σύνδρομο Van der Woude</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-24" <?=(substr($dr_e['q4'],23,1)=='1'?'checked':'')?> name="q4-24">
										<label for="q4-24">Σύνδρομο Waardenburg</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-25" <?=(substr($dr_e['q4'],24,1)=='1'?'checked':'')?> name="q4-25">
										<label for="q4-25">Σύνδρομο Επίκτητης Νοσολογικής Ανεπάρκειας (AIDS)</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-26" <?=(substr($dr_e['q4'],25,1)=='1'?'checked':'')?> name="q4-26">
										<label for="q4-26">Αδενοειδεκτομή</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-27" <?=(substr($dr_e['q4'],26,1)=='1'?'checked':'')?> name="q4-27">
										<label for="q4-27">Αδενοειδεκτομή</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-28" <?=(substr($dr_e['q4'],27,1)=='1'?'checked':'')?> name="q4-28">
										<label for="q4-28">Αλλεργίες</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-29" <?=(substr($dr_e['q4'],28,1)=='1'?'checked':'')?> name="q4-29">
										<label for="q4-29">Άσθμα</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-30" <?=(substr($dr_e['q4'],29,1)=='1'?'checked':'')?> name="q4-30">
										<label for="q4-30">Ανεμοβλογιά</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-31" <?=(substr($dr_e['q4'],30,1)=='1'?'checked':'')?> name="q4-31">
										<label for="q4-31">Κρυώματα</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-32" <?=(substr($dr_e['q4'],31,1)=='1'?'checked':'')?> name="q4-32">
										<label for="q4-32">Σπασμοί</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-33" <?=(substr($dr_e['q4'],32,1)=='1'?'checked':'')?> name="q4-33">
										<label for="q4-33">Λαρυγγίτιδα (παιδιών)</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-34" <?=(substr($dr_e['q4'],33,1)=='1'?'checked':'')?> name="q4-34">
										<label for="q4-34">Λαρυγγίτιδα (παιδιών)</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-35" <?=(substr($dr_e['q4'],34,1)=='1'?'checked':'')?> name="q4-35">
										<label for="q4-35">Ίλιγγος</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-36" <?=(substr($dr_e['q4'],35,1)=='1'?'checked':'')?> name="q4-36">
										<label for="q4-36">Εκκρίσεις ώτων</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-37" <?=(substr($dr_e['q4'],36,1)=='1'?'checked':'')?> name="q4-37">
										<label for="q4-37">Εκκρίσεις ώτων</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-38" <?=(substr($dr_e['q4'],37,1)=='1'?'checked':'')?> name="q4-38">
										<label for="q4-38">Εγκεφαλίτιδα</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-39" <?=(substr($dr_e['q4'],38,1)=='1'?'checked':'')?> name="q4-39">
										<label for="q4-39">Ερυθρά</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-40" <?=(substr($dr_e['q4'],39,1)=='1'?'checked':'')?> name="q4-40">
										<label for="q4-40">Πονοκέφαλοι</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-41" <?=(substr($dr_e['q4'],40,1)=='1'?'checked':'')?> name="q4-41">
										<label for="q4-41">Πονοκέφαλοι</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-42"  <?=(substr($dr_e['q4'],41,1)=='1'?'checked':'')?> name="q4-42">
										<label for="q4-42">Υψηλός Πυρετός</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-43" <?=(substr($dr_e['q4'],42,1)=='1'?'checked':'')?> name="q4-43">
										<label for="q4-43">Γρίπη</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-44" <?=(substr($dr_e['q4'],43,1)=='1'?'checked':'')?> name="q4-44">
										<label for="q4-44">Φλεγμονή της μαστοειδούς απόφυσης</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-45" <?=(substr($dr_e['q4'],44,1)=='1'?'checked':'')?> name="q4-45">
										<label for="q4-45">Ιλαρά</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-46" <?=(substr($dr_e['q4'],45,1)=='1'?'checked':'')?> name="q4-46">
										<label for="q4-46">Μηνιγγίτιδα</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-47" <?=(substr($dr_e['q4'],46,1)=='1'?'checked':'')?> name="q4-47">
										<label for="q4-47">Παρωτίτιδα</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-48" <?=(substr($dr_e['q4'],47,1)=='1'?'checked':'')?> name="q4-48">
										<label for="q4-48">Μέση Ωτίτιδα</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-49" <?=(substr($dr_e['q4'],48,1)=='1'?'checked':'')?> name="q4-49">
										<label for="q4-49">Πνευμονία</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-50"  <?=(substr($dr_e['q4'],49,1)=='1'?'checked':'')?> name="q4-50">
										<label for="q4-50">Παραρρινοκολπίτιδα</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-51" <?=(substr($dr_e['q4'],50,1)=='1'?'checked':'')?> name="q4-51">
										<label for="q4-51">Παραρρινοκολπίτιδα</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-52" <?=(substr($dr_e['q4'],51,1)=='1'?'checked':'')?> name="q4-52">
										<label for="q4-52">εγκεφαλικό</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-53" <?=(substr($dr_e['q4'],52,1)=='1'?'checked':'')?> name="q4-53">
										<label for="q4-53">Εμβοές</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-54" <?=(substr($dr_e['q4'],53,1)=='1'?'checked':'')?> name="q4-54">
										<label for="q4-54">Αμυγδαλίτιδα</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-55" <?=(substr($dr_e['q4'],54,1)=='1'?'checked':'')?> name="q4-55">
										<label for="q4-55">Ισχαιμική προσβολή</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q4-56" <?=(substr($dr_e['q4'],55,1)=='1'?'checked':'')?> name="q4-56">
										<label for="q4-56">Κανένα από τα παραπάνω</label>
									</div>
								</div>
							</div>

							<!--
							<div class="form-group row">
								<label class="col-sm-3 control-label text-sm-right pt-1" for="w4-cc">Card Number</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="cc-number" id="w4-cc" required>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 control-label text-sm-right pt-1" for="inputSuccess">Expiration</label>
								<div class="col-sm-5">
									<select class="form-control" name="exp-month" required>
										<option>January</option>
										<option>February</option>
										<option>March</option>
										<option>April</option>
										<option>May</option>
										<option>June</option>
										<option>July</option>
										<option>August</option>
										<option>September</option>
										<option>October</option>
										<option>November</option>
										<option>December</option>
									</select>
								</div>
								<div class="col-sm-4">
									<select class="form-control" name="exp-year" required>
										<option>2014</option>
										<option>2015</option>
										<option>2017</option>
										<option>2017</option>
										<option>2018</option>
										<option>2019</option>
										<option>2020</option>
										<option>2021</option>
										<option>2022</option>
									</select>
								</div>
							</div>
							-->
						</div>
						<div id="w4-4" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ως προς το ύψος θα χαρακτηρίζατε το παιδί:</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q5-1" value='1' <?=($dr_e['q5']=='1'?'checked':'')?>  name="q5">
										<label for="q5-1">Κοντό</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q5-2" value='2' <?=($dr_e['q5']=='2'?'checked':'')?> name="q5">
										<label for="q5-2">Φυσιολογικό</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q5-3" value='3' <?=($dr_e['q5']=='3'?'checked':'')?> name="q5">
										<label for="q5-3">Ψηλό</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ως προς το βάρος θα χαρακτηρίζατε το παιδί:</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q6-1" value='1' <?=($dr_e['q6']=='1'?'checked':'')?> name="q6">
										<label for="q6-1">Αδύνατο</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q6-2" value='2' <?=($dr_e['q6']=='2'?'checked':'')?> name="q6">
										<label for="q6-2">Φυσιολογικό</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q6-3" value='3' <?=($dr_e['q6']=='3'?'checked':'')?> name="q6">
										<label for="q6-3">Παχύ</label>
									</div>
								</div>
							</div>
							<!--
							<div class="form-group row">
								<label class="col-sm-3 control-label text-sm-right pt-1" for="w4-email">Email</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="email" id="w4-email" required>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-3"></div>
								<div class="col-sm-9">
									<div class="checkbox-custom">
										<input type="checkbox" name="terms" id="w4-terms" required>
										<label for="w4-terms">I agree to the terms of service</label>
									</div>
								</div>
							</div>
							-->
						</div>
					</div>
				</div>
				<div class="card-footer">
					<ul class="pager">
						<li class="previous disabled">
							<a><i class="fas fa-angle-left"></i> Προηγούμενο</a>
						</li>
						
						<li class="finish hidden float-right">
							<a href="#" onClick="checkFields();">Ολοκλήρωση</a>
						</li>
						<li class="next">
							<a>Επόμενο <i class="fas fa-angle-right"></i></a>
						</li>
					</ul>
				</div>
			</section>
			<div class="form-group row" style="margin-top:20px;">
				<a href="#" onClick="checkFields();"><button type="button" class="btn btn-primary">Αποθήκευση</button></a> 
				<a style="margin-left:10px;" href="index.php?com=children"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
			</div>
		</div>
	</div>
<? } else if($_GET['option']=='2'){?>
	<div class="row">
		<div class="col">
			<section class="card form-wizard" id="w4">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
					</div>
					<h2 class="card-title">Λεκτικές επικοινωνιακές εκφάνσεις</h2>
				</header>
				<div class="card-body">
					<div class="wizard-progress wizard-progress-lg">
						<div class="steps-progress">
							<div class="progress-indicator"></div>
						</div>
						<ul class="nav wizard-steps">
							<li class="nav-item active">
								<a class="nav-link" href="#w4-1" data-toggle="tab"><span>1</span>Ενότητα 1</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-2" data-toggle="tab"><span>2</span>Ενότητα 2</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-3" data-toggle="tab"><span>3</span>Ενότητα 3</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-4" data-toggle="tab"><span>4</span>Ενότητα 4</a>
							</li>
						</ul>
					</div>
					<input type='hidden' name='myOption' value='2'>
					<div class="tab-content">
						<div id="w4-1" class="tab-pane active">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1" >Συχνότητα επικοινωνιακής πρόθεσης σε σχέση με την υπόλοιπη τάξη: </div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q7-1" <?=($dr_e['q7']=='1'?'checked':'')?> value='1' name="q7">
										<label for="q7-1">Καμία</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q7-2" <?=($dr_e['q7']=='2'?'checked':'')?> value='2' name="q7">
										<label for="q7-2">Λιγότερο από τους υπόλοιπους</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q7-3" <?=($dr_e['q7']=='3'?'checked':'')?> value='3' name="q7">
										<label for="q7-3">Περίπου ίδια</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q7-4" <?=($dr_e['q7']=='4'?'checked':'')?> value='4' name="q7">
										<label for="q7-4">Περισσότερο από</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Όταν το παιδί εισάγει ένα θέμα για συζήτηση... Έχει την Ικανότητα να τραβήξει την προσοχή του ομιλητή;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q8-1"  <?=($dr_e['q8']=='1'?'checked':'')?> value="1" name="q8">
										<label for="q8-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q8-2"  <?=($dr_e['q8']=='2'?'checked':'')?>  value="2" name="q8">
										<label for="q8-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q8-3"  <?=($dr_e['q8']=='3'?'checked':'')?> value="3" name="q8">
										<label for="q8-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q8-4"  <?=($dr_e['q8']=='4'?'checked':'')?> value="4" name="q8">
										<label for="q8-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Όταν το παιδί εισάγει ένα θέμα για συζήτηση... Επαναλαμβάνει παλιά θέματα σε καθημερινή βάση;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q9-1"  <?=($dr_e['q9']=='1'?'checked':'')?> value="1" name="q9">
										<label for="q9-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q9-2"  <?=($dr_e['q9']=='2'?'checked':'')?>  value="2" name="q9">
										<label for="q9-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q9-3"  <?=($dr_e['q9']=='3'?'checked':'')?> value="3" name="q9">
										<label for="q9-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q9-4"  <?=($dr_e['q9']=='4'?'checked':'')?> value="4" name="q9">
										<label for="q9-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Όταν το παιδί εισάγει ένα θέμα για συζήτηση... Αρχίζει νέα θεματολογία σε καθημερινή βάση;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q10-1"  <?=($dr_e['q10']=='1'?'checked':'')?> value="1" name="q10">
										<label for="q10-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q10-2"  <?=($dr_e['q10']=='2'?'checked':'')?>  value="2" name="q10">
										<label for="q10-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q10-3"  <?=($dr_e['q10']=='3'?'checked':'')?> value="3" name="q10">
										<label for="q10-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q10-4"  <?=($dr_e['q10']=='4'?'checked':'')?> value="4" name="q10">
										<label for="q10-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Όταν το παιδί εισάγει ένα θέμα για συζήτηση... Είναι ικανό να χαιρετάει άλλους;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q11-1"  <?=($dr_e['q11']=='1'?'checked':'')?> value="1" name="q11">
										<label for="q11-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q11-2"  <?=($dr_e['q11']=='2'?'checked':'')?>  value="2" name="q11">
										<label for="q11-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q11-3"  <?=($dr_e['q11']=='3'?'checked':'')?> value="3" name="q11">
										<label for="q11-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q11-4"  <?=($dr_e['q11']=='4'?'checked':'')?> value="4" name="q11">
										<label for="q11-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Όταν το παιδί εισάγει ένα θέμα για συζήτηση... Είναι ικανό να αποχαιρετάει;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q12-1"  <?=($dr_e['q12']=='1'?'checked':'')?> value="1" name="q12">
										<label for="q12-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q12-2"  <?=($dr_e['q12']=='2'?'checked':'')?>  value="2" name="q12">
										<label for="q12-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q12-3"  <?=($dr_e['q12']=='3'?'checked':'')?> value="3" name="q12">
										<label for="q12-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q12-4"  <?=($dr_e['q12']=='4'?'checked':'')?> value="4" name="q12">
										<label for="q12-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Όταν το παιδί εισάγει ένα θέμα για συζήτηση... Είναι ικανό να κάνει συστάσεις / δίνει οδηγίες;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q13-1"  <?=($dr_e['q13']=='1'?'checked':'')?> value="1" name="q13">
										<label for="q13-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q13-2"  <?=($dr_e['q13']=='2'?'checked':'')?>  value="2" name="q13">
										<label for="q13-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q13-3"  <?=($dr_e['q13']=='3'?'checked':'')?> value="3" name="q13">
										<label for="q13-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q13-4"  <?=($dr_e['q13']=='4'?'checked':'')?> value="4" name="q13">
										<label for="q13-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Όταν το παιδί εισάγει ένα θέμα για συζήτηση... Είναι ικανό να εκφράζει τις ανάγκες του;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q14-1"  <?=($dr_e['q14']=='1'?'checked':'')?> value="1" name="q14">
										<label for="q14-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q14-2"  <?=($dr_e['q14']=='2'?'checked':'')?>  value="2" name="q14">
										<label for="q14-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q14-3"  <?=($dr_e['q14']=='3'?'checked':'')?> value="3" name="q14">
										<label for="q14-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q14-4"  <?=($dr_e['q14']=='4'?'checked':'')?> value="4" name="q14">
										<label for="q14-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ικανότητα να ζητά οδηγίες</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q15-1"  <?=($dr_e['q15']=='1'?'checked':'')?> value="1" name="q15">
										<label for="q15-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q15-2"  <?=($dr_e['q15']=='2'?'checked':'')?>  value="2" name="q15">
										<label for="q15-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q15-3"  <?=($dr_e['q15']=='3'?'checked':'')?> value="3" name="q15">
										<label for="q15-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q15-4"  <?=($dr_e['q15']=='4'?'checked':'')?> value="4" name="q15">
										<label for="q15-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ικανότητα να ζητά επανάληψη / διευκρίνιση</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q16-1"  <?=($dr_e['q16']=='1'?'checked':'')?> value="1" name="q16">
										<label for="q16-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q16-2"  <?=($dr_e['q16']=='2'?'checked':'')?>  value="2" name="q16">
										<label for="q16-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q16-3"  <?=($dr_e['q16']=='3'?'checked':'')?> value="3" name="q16">
										<label for="q16-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q16-4"  <?=($dr_e['q16']=='4'?'checked':'')?> value="4" name="q16">
										<label for="q16-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ικανότητα να ζητά κάποια δραστηριότητα</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q17-1"  <?=($dr_e['q17']=='1'?'checked':'')?> value="1" name="q17">
										<label for="q17-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q17-2"  <?=($dr_e['q17']=='2'?'checked':'')?>  value="2" name="q17">
										<label for="q17-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q17-3"  <?=($dr_e['q17']=='3'?'checked':'')?> value="3" name="q17">
										<label for="q17-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q17-4"  <?=($dr_e['q17']=='4'?'checked':'')?> value="4" name="q17">
										<label for="q17-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
						</div>
						<div id="w4-2" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ικανότητα να ζητά άδεια</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q18-1"  <?=($dr_e['q18']=='1'?'checked':'')?> value="1" name="q18">
										<label for="q18-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q18-2"  <?=($dr_e['q18']=='2'?'checked':'')?>  value="2" name="q18">
										<label for="q18-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q18-3"  <?=($dr_e['q18']=='3'?'checked':'')?> value="3" name="q18">
										<label for="q18-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q18-4"  <?=($dr_e['q18']=='4'?'checked':'')?> value="4" name="q18">
										<label for="q18-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μιλά κυρίως για τον εαυτό του</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q19-1"  <?=($dr_e['q19']=='1'?'checked':'')?> value="1" name="q19">
										<label for="q19-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q19-2"  <?=($dr_e['q19']=='2'?'checked':'')?>  value="2" name="q19">
										<label for="q19-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q19-3"  <?=($dr_e['q19']=='3'?'checked':'')?> value="3" name="q19">
										<label for="q19-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q19-4"  <?=($dr_e['q19']=='4'?'checked':'')?> value="4" name="q19">
										<label for="q19-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μιλά για τους άλλους, όπως και για τον εαυτό του</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q20-1"  <?=($dr_e['q20']=='1'?'checked':'')?> value="1" name="q20">
										<label for="q20-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q20-2"  <?=($dr_e['q20']=='2'?'checked':'')?>  value="2" name="q20">
										<label for="q20-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q20-3"  <?=($dr_e['q20']=='3'?'checked':'')?> value="3" name="q20">
										<label for="q20-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q20-4"  <?=($dr_e['q20']=='4'?'checked':'')?> value="4" name="q20">
										<label for="q20-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Mιλά και κάνει αναφορές στον παρελθόν</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q21-1"  <?=($dr_e['q21']=='1'?'checked':'')?> value="1" name="q21">
										<label for="q21-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q21-2"  <?=($dr_e['q21']=='2'?'checked':'')?>  value="2" name="q21">
										<label for="q21-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q21-3"  <?=($dr_e['q21']=='3'?'checked':'')?> value="3" name="q21">
										<label for="q21-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q21-4"  <?=($dr_e['q21']=='4'?'checked':'')?> value="4" name="q21">
										<label for="q21-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Mιλά και κάνει αναφορές στο μέλλον</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q22-1"  <?=($dr_e['q22']=='1'?'checked':'')?> value="1" name="q22">
										<label for="q22-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q22-2"  <?=($dr_e['q22']=='2'?'checked':'')?>  value="2" name="q22">
										<label for="q22-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q22-3"  <?=($dr_e['q22']=='3'?'checked':'')?> value="3" name="q22">
										<label for="q22-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q22-4"  <?=($dr_e['q22']=='4'?'checked':'')?> value="4" name="q22">
										<label for="q22-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Mιλά και κάνει αναφορές στο παρόν</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q23-1"  <?=($dr_e['q23']=='1'?'checked':'')?> value="1" name="q23">
										<label for="q23-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q23-2"  <?=($dr_e['q23']=='2'?'checked':'')?>  value="2" name="q23">
										<label for="q23-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q23-3"  <?=($dr_e['q23']=='3'?'checked':'')?> value="3" name="q23">
										<label for="q23-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q23-4"  <?=($dr_e['q23']=='4'?'checked':'')?> value="4" name="q23">
										<label for="q23-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Mιλά και κάνει φανταστικές αναφορές</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q24-1"  <?=($dr_e['q24']=='1'?'checked':'')?> value="1" name="q24">
										<label for="q24-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q24-2"  <?=($dr_e['q24']=='2'?'checked':'')?>  value="2" name="q24">
										<label for="q24-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q24-3"  <?=($dr_e['q24']=='3'?'checked':'')?> value="3" name="q24">
										<label for="q24-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q24-4"  <?=($dr_e['q24']=='4'?'checked':'')?> value="4" name="q24">
										<label for="q24-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Kαλεί τους ανθρώπους/ τα αντικείμενα με το όνομά τους</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q25-1"  <?=($dr_e['q25']=='1'?'checked':'')?> value="1" name="q25">
										<label for="q25-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q25-2"  <?=($dr_e['q25']=='2'?'checked':'')?>  value="2" name="q25">
										<label for="q25-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q25-3"  <?=($dr_e['q25']=='3'?'checked':'')?> value="3" name="q25">
										<label for="q25-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q25-4"  <?=($dr_e['q25']=='4'?'checked':'')?> value="4" name="q25">
										<label for="q25-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Xρησιμοποιεί ήχους ή ηχομιμητικές λέξεις σε κατάλληλες περιπτώσεις</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q26-1"  <?=($dr_e['q26']=='1'?'checked':'')?> value="1" name="q26">
										<label for="q26-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q26-2"  <?=($dr_e['q26']=='2'?'checked':'')?>  value="2" name="q26">
										<label for="q26-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q26-3"  <?=($dr_e['q26']=='3'?'checked':'')?> value="3" name="q26">
										<label for="q26-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q26-4"  <?=($dr_e['q26']=='4'?'checked':'')?> value="4" name="q26">
										<label for="q26-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ικανό στη διατήρηση θέματος συζήτησης. Συγκεκριμένα: Απαντάει σε ερωτήσεις</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q27-1"  <?=($dr_e['q27']=='1'?'checked':'')?> value="1" name="q27">
										<label for="q27-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q27-2"  <?=($dr_e['q27']=='2'?'checked':'')?>  value="2" name="q27">
										<label for="q27-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q27-3"  <?=($dr_e['q27']=='3'?'checked':'')?> value="3" name="q27">
										<label for="q27-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q27-4"  <?=($dr_e['q27']=='4'?'checked':'')?> value="4" name="q27">
										<label for="q27-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ικανό στη διατήρηση θέματος συζήτησης. Συγκεκριμένα: Καταλαβαίνει το θέμα</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q28-1"  <?=($dr_e['q28']=='1'?'checked':'')?> value="1" name="q28">
										<label for="q28-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q28-2"  <?=($dr_e['q28']=='2'?'checked':'')?>  value="2" name="q28">
										<label for="q28-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q28-3"  <?=($dr_e['q28']=='3'?'checked':'')?> value="3" name="q28">
										<label for="q28-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q28-4"  <?=($dr_e['q28']=='4'?'checked':'')?> value="4" name="q28">
										<label for="q28-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
						</div>
						<div id="w4-3" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ικανό στη διατήρηση θέματος συζήτησης. Συγκεκριμένα: Προσφέρει νέες σχετικές πληροφορίες για ένα θέμα</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q29-1"  <?=($dr_e['q29']=='1'?'checked':'')?> value="1" name="q29">
										<label for="q29-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q29-2"  <?=($dr_e['q29']=='2'?'checked':'')?>  value="2" name="q29">
										<label for="q29-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q29-3"  <?=($dr_e['q29']=='3'?'checked':'')?> value="3" name="q29">
										<label for="q29-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q29-4"  <?=($dr_e['q29']=='4'?'checked':'')?> value="4" name="q29">
										<label for="q29-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ικανό στη διατήρηση θέματος συζήτησης. Συγκεκριμένα: Ζητά περισσότερες πληροφορίες για ένα θέμα</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q30-1"  <?=($dr_e['q30']=='1'?'checked':'')?> value="1" name="q30">
										<label for="q30-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q30-2"  <?=($dr_e['q30']=='2'?'checked':'')?>  value="2" name="q30">
										<label for="q30-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q30-3"  <?=($dr_e['q30']=='3'?'checked':'')?> value="3" name="q30">
										<label for="q30-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q30-4"  <?=($dr_e['q30']=='4'?'checked':'')?> value="4" name="q30">
										<label for="q30-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ικανό στη διατήρηση θέματος συζήτησης. Συγκεκριμένα: Ικανό να ζητά επανάληψη / διευκρινήσεις εάν ένα θέμα δεν είναι ξεκάθαρο</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q31-1"  <?=($dr_e['q31']=='1'?'checked':'')?> value="1" name="q31">
										<label for="q31-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q31-2"  <?=($dr_e['q31']=='2'?'checked':'')?>  value="2" name="q31">
										<label for="q31-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q31-3"  <?=($dr_e['q31']=='3'?'checked':'')?> value="3" name="q31">
										<label for="q31-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q31-4"  <?=($dr_e['q31']=='4'?'checked':'')?> value="4" name="q31">
										<label for="q31-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ικανό στη διατήρηση θέματος συζήτησης. Συγκεκριμένα: Ικανό να επαναλάβει ή να απαντήσει ερωτήσεις για το θέμα που μίλησε κάποιος άλλος</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q32-1"  <?=($dr_e['q32']=='1'?'checked':'')?> value="1" name="q32">
										<label for="q32-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q32-2"  <?=($dr_e['q32']=='2'?'checked':'')?>  value="2" name="q32">
										<label for="q32-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q32-3"  <?=($dr_e['q32']=='3'?'checked':'')?> value="3" name="q32">
										<label for="q32-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q32-4"  <?=($dr_e['q32']=='4'?'checked':'')?> value="4" name="q32">
										<label for="q32-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ικανό στη διατήρηση θέματος συζήτησης. Συγκεκριμένα: Συμφωνεί με άλλους</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q33-1"  <?=($dr_e['q33']=='1'?'checked':'')?> value="1" name="q33">
										<label for="q33-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q33-2"  <?=($dr_e['q33']=='2'?'checked':'')?>  value="2" name="q33">
										<label for="q33-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q33-3"  <?=($dr_e['q33']=='3'?'checked':'')?> value="3" name="q33">
										<label for="q33-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q33-4"  <?=($dr_e['q33']=='4'?'checked':'')?> value="4" name="q33">
										<label for="q33-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ικανό στη διατήρηση θέματος συζήτησης. Συγκεκριμένα: Διαφωνεί με άλλους</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q34-1"  <?=($dr_e['q34']=='1'?'checked':'')?> value="1" name="q34">
										<label for="q34-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q34-2"  <?=($dr_e['q34']=='2'?'checked':'')?>  value="2" name="q34">
										<label for="q34-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q34-3"  <?=($dr_e['q34']=='3'?'checked':'')?> value="3" name="q34">
										<label for="q34-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q34-4"  <?=($dr_e['q34']=='4'?'checked':'')?> value="4" name="q34">
										<label for="q34-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Δεν είναι ικανό στη διατήρηση θέματος συζήτησης. Συγκεκριμένα: Εσκεμμένα αποφεύγει ή αγνοεί ερώτηση</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q35-1"  <?=($dr_e['q35']=='1'?'checked':'')?> value="1" name="q35">
										<label for="q35-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q35-2"  <?=($dr_e['q35']=='2'?'checked':'')?>  value="2" name="q35">
										<label for="q35-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q35-3"  <?=($dr_e['q35']=='3'?'checked':'')?> value="3" name="q35">
										<label for="q35-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q35-4"  <?=($dr_e['q35']=='4'?'checked':'')?> value="4" name="q35">
										<label for="q35-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Δεν είναι ικανό στη διατήρηση θέματος συζήτησης. Συγκεκριμένα: Αγνοεί την εισαγωγή θέματος από τον προηγούμενο ομιλητή & αλλάζει θέμα</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q36-1"  <?=($dr_e['q36']=='1'?'checked':'')?> value="1" name="q36">
										<label for="q36-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q36-2"  <?=($dr_e['q36']=='2'?'checked':'')?>  value="2" name="q36">
										<label for="q36-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q36-3"  <?=($dr_e['q36']=='3'?'checked':'')?> value="3" name="q36">
										<label for="q36-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q36-4"  <?=($dr_e['q36']=='4'?'checked':'')?> value="4" name="q36">
										<label for="q36-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Δεν είναι ικανό στη διατήρηση θέματος συζήτησης. Συγκεκριμένα: κάνει μονόλογο ενώ είναι σε ομάδα</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q37-1"  <?=($dr_e['q37']=='1'?'checked':'')?> value="1" name="q37">
										<label for="q37-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q37-2"  <?=($dr_e['q37']=='2'?'checked':'')?>  value="2" name="q37">
										<label for="q37-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q37-3"  <?=($dr_e['q37']=='3'?'checked':'')?> value="3" name="q37">
										<label for="q37-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q37-4"  <?=($dr_e['q37']=='4'?'checked':'')?> value="4" name="q37">
										<label for="q37-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Εναλλαγή σειράς στο διάλογο - Διακόπτει τους άλλους</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q38-1"  <?=($dr_e['q38']=='1'?'checked':'')?> value="1" name="q38">
										<label for="q38-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q38-2"  <?=($dr_e['q38']=='2'?'checked':'')?>  value="2" name="q38">
										<label for="q38-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q38-3"  <?=($dr_e['q38']=='3'?'checked':'')?> value="3" name="q38">
										<label for="q38-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q38-4"  <?=($dr_e['q38']=='4'?'checked':'')?> value="4" name="q38">
										<label for="q38-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>

						</div>
						<div id="w4-4" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Εναλλαγή σειράς στο διάλογο - Απαντά ερωτήσεις για άλλους</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q39-1"  <?=($dr_e['q39']=='1'?'checked':'')?> value="1" name="q39">
										<label for="q39-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q39-2"  <?=($dr_e['q39']=='2'?'checked':'')?>  value="2" name="q39">
										<label for="q39-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q39-3"  <?=($dr_e['q39']=='3'?'checked':'')?> value="3" name="q39">
										<label for="q39-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q39-4"  <?=($dr_e['q39']=='4'?'checked':'')?> value="4" name="q39">
										<label for="q39-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Εναλλαγή σειράς στο διάλογο - Έχει μεγάλα διαστήματα ομιλίας (δεν παίρνει εύκολα κάποιος σειρά να μιλήσει)</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q40-1"  <?=($dr_e['q40']=='1'?'checked':'')?> value="1" name="q40">
										<label for="q40-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q40-2"  <?=($dr_e['q40']=='2'?'checked':'')?>  value="2" name="q40">
										<label for="q40-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q40-3"  <?=($dr_e['q40']=='3'?'checked':'')?> value="3" name="q40">
										<label for="q40-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q40-4"  <?=($dr_e['q40']=='4'?'checked':'')?> value="4" name="q40">
										<label for="q40-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Εναλλαγή σειράς στο διάλογο - Υποδεικνύει τη σειρά για το ποιος θα μιλήσει</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q41-1"  <?=($dr_e['q41']=='1'?'checked':'')?> value="1" name="q41">
										<label for="q41-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q41-2"  <?=($dr_e['q41']=='2'?'checked':'')?>  value="2" name="q41">
										<label for="q41-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q41-3"  <?=($dr_e['q41']=='3'?'checked':'')?> value="3" name="q41">
										<label for="q41-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q41-4"  <?=($dr_e['q41']=='4'?'checked':'')?> value="4" name="q41">
										<label for="q41-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Εναλλαγή σειράς στο διάλογο - Ευαισθησία στα συναισθήματα των ακροατών (μπορεί να καταλάβει πότε ο ακροατής ενδιαφέρεται ή βαριέται)</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q42-1"  <?=($dr_e['q42']=='1'?'checked':'')?> value="1" name="q42">
										<label for="q42-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q42-2"  <?=($dr_e['q42']=='2'?'checked':'')?>  value="2" name="q42">
										<label for="q42-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q42-3"  <?=($dr_e['q42']=='3'?'checked':'')?> value="3" name="q42">
										<label for="q42-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q42-4"  <?=($dr_e['q42']=='4'?'checked':'')?> value="4" name="q42">
										<label for="q42-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Εναλλαγή σειράς στο διάλογο - Ζητά συγγνώμη όταν διακόπτει</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q43-1"  <?=($dr_e['q43']=='1'?'checked':'')?> value="1" name="q43">
										<label for="q43-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q43-2"  <?=($dr_e['q43']=='2'?'checked':'')?>  value="2" name="q43">
										<label for="q43-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q43-3"  <?=($dr_e['q43']=='3'?'checked':'')?> value="3" name="q43">
										<label for="q43-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q43-4"  <?=($dr_e['q43']=='4'?'checked':'')?> value="4" name="q43">
										<label for="q43-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ευγένεια - Ικανό να κάνει πλάγια αιτήματα</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q44-1"  <?=($dr_e['q44']=='1'?'checked':'')?> value="1" name="q44">
										<label for="q44-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q44-2"  <?=($dr_e['q44']=='2'?'checked':'')?>  value="2" name="q44">
										<label for="q44-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q44-3"  <?=($dr_e['q44']=='3'?'checked':'')?> value="3" name="q44">
										<label for="q44-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q44-4"  <?=($dr_e['q44']=='4'?'checked':'')?> value="4" name="q44">
										<label for="q44-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ευγένεια - Χρησιμοποιεί εντολές</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q45-1"  <?=($dr_e['q45']=='1'?'checked':'')?> value="1" name="q45">
										<label for="q45-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q45-2"  <?=($dr_e['q45']=='2'?'checked':'')?>  value="2" name="q45">
										<label for="q45-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q45-3"  <?=($dr_e['q45']=='3'?'checked':'')?> value="3" name="q45">
										<label for="q45-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q45-4"  <?=($dr_e['q45']=='4'?'checked':'')?> value="4" name="q45">
										<label for="q45-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ευγένεια - Χρησιμοποιεί φράσεις ευγενείας (ευχαριστώ, παρακαλώ) και αντιλαμβάνεται τα κοινωνικά πρέπει;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q46-1"  <?=($dr_e['q46']=='1'?'checked':'')?> value="1" name="q46">
										<label for="q46-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q46-2"  <?=($dr_e['q46']=='2'?'checked':'')?>  value="2" name="q46">
										<label for="q46-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q46-3"  <?=($dr_e['q46']=='3'?'checked':'')?> value="3" name="q46">
										<label for="q46-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q46-4"  <?=($dr_e['q46']=='4'?'checked':'')?> value="4" name="q46">
										<label for="q46-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατηρείτε το παιδί να επαναλαμβάνει πανομοιότυπα τις ίδιες φράσεις (ίδιο ρεπερτόριο) δηλαδή να ηχολαλεί (σαν παπαγάλος)</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q47-1"  <?=($dr_e['q47']=='1'?'checked':'')?> value="1" name="q47">
										<label for="q47-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q47-2"  <?=($dr_e['q47']=='2'?'checked':'')?>  value="2" name="q47">
										<label for="q47-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q47-3"  <?=($dr_e['q47']=='3'?'checked':'')?> value="3" name="q47">
										<label for="q47-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q47-4"  <?=($dr_e['q47']=='4'?'checked':'')?> value="4" name="q47">
										<label for="q47-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χαρακτηρίστε την καταληπτότητα του λόγου του παιδιού από μη οικείους (σε ποσοστά κατά προσέγγιση):</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q48-1"  <?=($dr_e['q48']=='1'?'checked':'')?> value="1" name="q48">
										<label for="q48-1">Παράγει συνήθως ακατάληπτη ομιλία</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q48-2"  <?=($dr_e['q48']=='2'?'checked':'')?>  value="2" name="q48">
										<label for="q48-2">0-25% του λόγου του</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q48-3"  <?=($dr_e['q48']=='3'?'checked':'')?> value="3" name="q48">
										<label for="q48-3">25-50% του λόγου του</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q48-4"  <?=($dr_e['q48']=='4'?'checked':'')?> value="4" name="q48">
										<label for="q48-4">50-75% του λόγου του</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q48-5"  <?=($dr_e['q48']=='5'?'checked':'')?> value="5" name="q48">
										<label for="q48-5">88% του λόγου του</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q48-6"  <?=($dr_e['q48']=='6'?'checked':'')?> value="6" name="q48">
										<label for="q48-6">Πλήρως κατανοητός από ξένους/μη οικείους</label>
									</div>
								</div>

								<!--
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q48-1"  <?//=($dr_e['q48']=='1'?'checked':'')?> value="1" name="q48">
										<label for="q48-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q48-2"  <?//=($dr_e['q48']=='2'?'checked':'')?>  value="2" name="q48">
										<label for="q48-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q48-3"  <?//=($dr_e['q48']=='3'?'checked':'')?> value="3" name="q48">
										<label for="q48-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q48-4"  <?//=($dr_e['q48']=='4'?'checked':'')?> value="4" name="q48">
										<label for="q48-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
								-->
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<ul class="pager">
						<li class="previous disabled">
							<a><i class="fas fa-angle-left"></i> Προηγούμενο</a>
						</li>
						<li class="finish hidden float-right">
							<a href="#" onClick="checkFields();">Ολοκλήρωση</a>
						</li>
						<li class="next">
							<a>Επόμενο <i class="fas fa-angle-right"></i></a>
						</li>
					</ul>
				</div>
			</section>
			<div class="form-group row" style="margin-top:20px;">
				<a href="#" onClick="checkFields();"><button type="button" class="btn btn-primary">Αποθήκευση</button></a> 
				<a style="margin-left:10px;" href="index.php?com=children"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
			</div>
		</div>
	</div>
<? } else if($_GET['option']=='3'){?>
	<div class="row">
		<div class="col">
			<section class="card form-wizard" id="w4">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
					</div>
					<h2 class="card-title">Μη λεκτικές επικοινωνιακές εκφάνσεις</h2>
				</header>
				<div class="card-body">
					<div class="wizard-progress wizard-progress-lg">
						<div class="steps-progress">
							<div class="progress-indicator"></div>
						</div>
						<ul class="nav wizard-steps">
							<li class="nav-item active">
								<a class="nav-link" href="#w4-1" data-toggle="tab"><span>1</span>Ενότητα 1</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-2" data-toggle="tab"><span>2</span>Ενότητα 2</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-3" data-toggle="tab"><span>3</span>Ενότητα 3</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-4" data-toggle="tab"><span>4</span>Ενότητα 4</a>
							</li>
						</ul>
					</div>
					<input type='hidden' name='myOption' value='3'>
					<div class="tab-content">
						<div id="w4-1" class="tab-pane active">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1" >Παρατήρηση μη λεκτικών συμπεριφορών - Στέκεται ή κάθεται πολύ κοντά στους άλλους ενώ μιλάει. </div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q49-1" <?=($dr_e['q49']=='1'?'checked':'')?> value='1' name="q49">
										<label for="q49-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q49-2" <?=($dr_e['q49']=='2'?'checked':'')?> value='2' name="q49">
										<label for="q49-2">Οχι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q49-3" <?=($dr_e['q49']=='3'?'checked':'')?> value='3' name="q49">
										<label for="q49-3">Μερικές φορές</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q49-4" <?=($dr_e['q49']=='4'?'checked':'')?> value='4' name="q49">
										<label for="q49-4">Δεν ταιριάζει</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατήρηση μη λεκτικών συμπεριφορών - Στέκεται ή κάθεται πολύ μακριά στους άλλους ενώ μιλάει</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q50-1"  <?=($dr_e['q50']=='1'?'checked':'')?> value="1" name="q50">
										<label for="q50-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q50-2"  <?=($dr_e['q50']=='2'?'checked':'')?>  value="2" name="q50">
										<label for="q50-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q50-3"  <?=($dr_e['q50']=='3'?'checked':'')?> value="3" name="q50">
										<label for="q50-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q50-4"  <?=($dr_e['q50']=='4'?'checked':'')?> value="4" name="q50">
										<label for="q50-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατήρηση μη λεκτικών συμπεριφορών - Στέκεται ή κάθεται στην κατάλληλη κοινωνικά απόσταση καθώς μιλά</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q51-1"  <?=($dr_e['q51']=='1'?'checked':'')?> value="1" name="q51">
										<label for="q51-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q51-2"  <?=($dr_e['q51']=='2'?'checked':'')?>  value="2" name="q51">
										<label for="q51-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q51-3"  <?=($dr_e['q51']=='3'?'checked':'')?> value="3" name="q51">
										<label for="q51-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q51-4"  <?=($dr_e['q51']=='4'?'checked':'')?> value="4" name="q51">
										<label for="q51-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατήρηση μη λεκτικών συμπεριφορών - Χρησιμοποιεί μη λεκτικές κινήσεις του κεφαλιού για να δείξει ότι κατάλαβε</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q52-1"  <?=($dr_e['q52']=='1'?'checked':'')?> value="1" name="q52">
										<label for="q52-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q52-2"  <?=($dr_e['q52']=='2'?'checked':'')?>  value="2" name="q52">
										<label for="q52-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q52-3"  <?=($dr_e['q52']=='3'?'checked':'')?> value="3" name="q52">
										<label for="q52-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q52-4"  <?=($dr_e['q52']=='4'?'checked':'')?> value="4" name="q52">
										<label for="q52-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατήρηση μη λεκτικών συμπεριφορών - Χρησιμοποιεί μη λεκτικά μέσα για να τραβήξει την προσοχή όταν εισάγει ένα θέμα (άγγιγμα στη πλάτη, δείχνει με δάχτυλο)</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q53-1"  <?=($dr_e['q53']=='1'?'checked':'')?> value="1" name="q53">
										<label for="q53-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q53-2"  <?=($dr_e['q53']=='2'?'checked':'')?>  value="2" name="q53">
										<label for="q53-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q53-3"  <?=($dr_e['q53']=='3'?'checked':'')?> value="3" name="q53">
										<label for="q53-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q53-4"  <?=($dr_e['q53']=='4'?'checked':'')?> value="4" name="q53">
										<label for="q53-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παίζει: συμβολικό παιχνίδι (αυτοκίνητα, κούκλες, κ.λπ.)</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q54-1"  <?=($dr_e['q54']=='1'?'checked':'')?> value="1" name="q54">
										<label for="q54-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q54-2"  <?=($dr_e['q54']=='2'?'checked':'')?>  value="2" name="q54">
										<label for="q54-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q54-3"  <?=($dr_e['q54']=='3'?'checked':'')?> value="3" name="q54">
										<label for="q54-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q54-4"  <?=($dr_e['q54']=='4'?'checked':'')?> value="4" name="q54">
										<label for="q54-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παίζει: παιχνίδι ρόλων (κλέφτες - αστυνόμοι)</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q55-1"  <?=($dr_e['q55']=='1'?'checked':'')?> value="1" name="q55">
										<label for="q55-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q55-2"  <?=($dr_e['q55']=='2'?'checked':'')?>  value="2" name="q55">
										<label for="q55-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q55-3"  <?=($dr_e['q55']=='3'?'checked':'')?> value="3" name="q55">
										<label for="q55-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q55-4"  <?=($dr_e['q55']=='4'?'checked':'')?> value="4" name="q55">
										<label for="q55-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παίζει: λογικό παιχνίδι (π.χ. παζλ)</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q56-1"  <?=($dr_e['q56']=='1'?'checked':'')?> value="1" name="q56">
										<label for="q56-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q56-2"  <?=($dr_e['q56']=='2'?'checked':'')?>  value="2" name="q56">
										<label for="q56-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q56-3"  <?=($dr_e['q56']=='3'?'checked':'')?> value="3" name="q56">
										<label for="q56-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q56-4"  <?=($dr_e['q56']=='4'?'checked':'')?> value="4" name="q56">
										<label for="q56-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>

						</div>
						<div id="w4-2" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παίζει: ομαδικό παιχνίδι</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q57-1"  <?=($dr_e['q57']=='1'?'checked':'')?> value="1" name="q57">
										<label for="q57-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q57-2"  <?=($dr_e['q57']=='2'?'checked':'')?>  value="2" name="q57">
										<label for="q57-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q57-3"  <?=($dr_e['q57']=='3'?'checked':'')?> value="3" name="q57">
										<label for="q57-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q57-4"  <?=($dr_e['q57']=='4'?'checked':'')?> value="4" name="q57">
										<label for="q57-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παίζει: παίζει συνήθως μόνο του</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q58-1"  <?=($dr_e['q58']=='1'?'checked':'')?> value="1" name="q58">
										<label for="q58-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q58-2"  <?=($dr_e['q58']=='2'?'checked':'')?>  value="2" name="q58">
										<label for="q58-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q58-3"  <?=($dr_e['q58']=='3'?'checked':'')?> value="3" name="q58">
										<label for="q58-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q58-4"  <?=($dr_e['q58']=='4'?'checked':'')?> value="4" name="q58">
										<label for="q58-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παίζει: έχει ακατάλληλη σχέση με τα αντικείμενα (π.χ. μυρίζει συνεχώς αντικείμενα, περιστροφή τροχών αυτοκινήτων κ.λπ.)</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q59-1"  <?=($dr_e['q59']=='1'?'checked':'')?> value="1" name="q59">
										<label for="q59-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q59-2"  <?=($dr_e['q59']=='2'?'checked':'')?>  value="2" name="q59">
										<label for="q59-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q59-3"  <?=($dr_e['q59']=='3'?'checked':'')?> value="3" name="q59">
										<label for="q59-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q59-4"  <?=($dr_e['q59']=='4'?'checked':'')?> value="4" name="q59">
										<label for="q59-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Προσκόλληση/ εμμονή με ασυνήθιστα αντικείμενα (π.χ. εμμονή σε έντονα χρώματα, έντονους ήχους)</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q60-1"  <?=($dr_e['q60']=='1'?'checked':'')?> value="1" name="q60">
										<label for="q60-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q60-2"  <?=($dr_e['q60']=='2'?'checked':'')?>  value="2" name="q60">
										<label for="q60-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q60-3"  <?=($dr_e['q60']=='3'?'checked':'')?> value="3" name="q60">
										<label for="q60-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q60-4"  <?=($dr_e['q60']=='4'?'checked':'')?> value="4" name="q60">
										<label for="q60-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατηρείτε το παιδί να έχει έντονη προσκόληση/εμμονή σε συγκεκριμένο πρόσωπο και η απουσία αυτού να προκαλεί εκρήξεις;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q61-1"  <?=($dr_e['q61']=='1'?'checked':'')?> value="1" name="q61">
										<label for="q61-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q61-2"  <?=($dr_e['q61']=='2'?'checked':'')?>  value="2" name="q61">
										<label for="q61-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q61-3"  <?=($dr_e['q61']=='3'?'checked':'')?> value="3" name="q61">
										<label for="q61-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q61-4"  <?=($dr_e['q61']=='4'?'checked':'')?> value="4" name="q61">
										<label for="q61-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατηρείτε το παιδί να έχει έντονη προσκόληση/εμμονή σε συγκεκριμένο αντικείμενο και η έλλειψή του να προκαλεί εκρήξεις;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q62-1"  <?=($dr_e['q62']=='1'?'checked':'')?> value="1" name="q62">
										<label for="q62-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q62-2"  <?=($dr_e['q62']=='2'?'checked':'')?>  value="2" name="q62">
										<label for="q62-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q62-3"  <?=($dr_e['q62']=='3'?'checked':'')?> value="3" name="q62">
										<label for="q62-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q62-4"  <?=($dr_e['q62']=='4'?'checked':'')?> value="4" name="q62">
										<label for="q62-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατηρείτε έντονη αλλαγή συναισθημάτων αν συμβούν αλλαγές στο πρόγραμμα του / στις ρουτίνες που ακολουθεί;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q63-1"  <?=($dr_e['q63']=='1'?'checked':'')?> value="1" name="q63">
										<label for="q63-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q63-2"  <?=($dr_e['q63']=='2'?'checked':'')?>  value="2" name="q63">
										<label for="q63-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q63-3"  <?=($dr_e['q63']=='3'?'checked':'')?> value="3" name="q63">
										<label for="q63-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q63-4"  <?=($dr_e['q63']=='4'?'checked':'')?> value="4" name="q63">
										<label for="q63-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατηρείτε το παιδί να κάνει στερεότυπες επαναλαμβανόμενες κινήσεις;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q64-1"  <?=($dr_e['q64']=='1'?'checked':'')?> value="1" name="q64">
										<label for="q64-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q64-2"  <?=($dr_e['q64']=='2'?'checked':'')?>  value="2" name="q64">
										<label for="q64-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q64-3"  <?=($dr_e['q64']=='3'?'checked':'')?> value="3" name="q64">
										<label for="q64-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q64-4"  <?=($dr_e['q64']=='4'?'checked':'')?> value="4" name="q64">
										<label for="q64-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
						</div>
						
						<div id="w4-3" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Το παιδί μπορεί καθισμένο σε καρέκλα να ολοκληρώσει μια δραστηριότητα;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q65-1"  <?=($dr_e['q65']=='1'?'checked':'')?> value="1" name="q65">
										<label for="q65-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q65-2"  <?=($dr_e['q65']=='2'?'checked':'')?>  value="2" name="q65">
										<label for="q65-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q65-3"  <?=($dr_e['q65']=='3'?'checked':'')?> value="3" name="q65">
										<label for="q65-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q65-4"  <?=($dr_e['q65']=='4'?'checked':'')?> value="4" name="q65">
										<label for="q65-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Το παιδί μπορεί να ασχολείται με μια δραστηριότητα για τον ίδιο χρόνο με τους υπολοίπους;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q66-1"  <?=($dr_e['q66']=='1'?'checked':'')?> value="1" name="q66">
										<label for="q66-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q66-2"  <?=($dr_e['q66']=='2'?'checked':'')?>  value="2" name="q66">
										<label for="q66-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q66-3"  <?=($dr_e['q66']=='3'?'checked':'')?> value="3" name="q66">
										<label for="q66-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q66-4"  <?=($dr_e['q66']=='4'?'checked':'')?> value="4" name="q66">
										<label for="q66-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Το παιδί αυτοερεθίζεται / αυτοτραυματίζεται κατά την διάρκεια έντονων συναισθημάτων</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q67-1"  <?=($dr_e['q67']=='1'?'checked':'')?> value="1" name="q67">
										<label for="q67-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q67-2"  <?=($dr_e['q67']=='2'?'checked':'')?>  value="2" name="q67">
										<label for="q67-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q67-3"  <?=($dr_e['q67']=='3'?'checked':'')?> value="3" name="q67">
										<label for="q67-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q67-4"  <?=($dr_e['q67']=='4'?'checked':'')?> value="4" name="q67">
										<label for="q67-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Σχετικά με την σίτιση ...</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q68-1"  <?=($dr_e['q68']=='1'?'checked':'')?> value="1" name="q68">
										<label for="q68-1">Σιτίζεται μόνο του</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q68-2"  <?=($dr_e['q68']=='2'?'checked':'')?>  value="2" name="q68">
										<label for="q68-2">Δεν σιτίζεται μόνο του</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Σχετικά με την σίτιση ...</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q190-1"  <?=($dr_e['q190']=='1'?'checked':'')?> value="1" name="q190">
										<label for="q190-1">Μασάει το φαγητό του</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q190-2"  <?=($dr_e['q190']=='2'?'checked':'')?>  value="2" name="q190">
										<label for="q190-2">Δεν μασάει το φαγητό του</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Σχετικά με την σίτιση ...</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q191-1"  <?=($dr_e['q191']=='1'?'checked':'')?> value="1" name="q191">
										<label for="q191-1">Χειρίζεται φυσιολογικά το πηρούνι/κουτάλι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q191-2"  <?=($dr_e['q191']=='2'?'checked':'')?>  value="2" name="q191">
										<label for="q191-2">Δεν χειρίζεται φυσιολογικά το πηρούνι/κουτάλι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Σχετικά με την σίτιση ...</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q192-1"  <?=($dr_e['q192']=='1'?'checked':'')?> value="1" name="q192">
										<label for="q192-1">Κόβει με το μαχαίρι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q192-2"  <?=($dr_e['q192']=='2'?'checked':'')?>  value="2" name="q192">
										<label for="q192-2">Δεν κόβει με το μαχαίρι</label>
									</div>
								</div>
							</div>
							<!--
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Σχετικά με την σίτιση ...</div>
								<div class="col-lg-9">
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" id="q68-1" <?//=(substr($dr_e['q68'],0,1)=='1'?'checked':'')?> name="q68-1">
										<label for="q68-1">Σιτίζεται μόνο του</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q68-2" <?//=(substr($dr_e['q68'],1,1)=='1'?'checked':'')?> name="q68-2">
										<label for="q68-2">Δεν σιτίζεται μόνο του</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q68-3" <?//=(substr($dr_e['q68'],2,1)=='1'?'checked':'')?> name="q68-3">
										<label for="q68-3">Μασάει το φαγητό του</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" id="q68-4" <?//=(substr($dr_e['q68'],3,1)=='1'?'checked':'')?> name="q68-4">
										<label for="q68-4">Δεν μασάει το φαγητό του</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q68-5" <?//=(substr($dr_e['q68'],4,1)=='1'?'checked':'')?> name="q68-5">
										<label for="q68-5">Χειρίζεται φυσιολογικά το πηρούνι/κουτάλι</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q68-6" <?//=(substr($dr_e['q68'],5,1)=='1'?'checked':'')?> name="q68-6">
										<label for="q68-6">Δεν χειρίζεται φυσιολογικά το πηρούνι/κουτάλι</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q68-7" <?//=(substr($dr_e['q68'],6,1)=='1'?'checked':'')?> name="q68-7">
										<label for="q68-7">Κόβει με το μαχαίρι</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q68-8" <?//=(substr($dr_e['q68'],7,1)=='1'?'checked':'')?> name="q68-8">
										<label for="q68-8">Δεν κόβει με το μαχαίρι</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q68-9" <?//=(substr($dr_e['q68'],8,1)=='1'?'checked':'')?> name="q68-9">
										<label for="q68-9">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							-->
							<hr>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Το παιδί αυτοεξυπηρετείται στην τουαλέτα;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q69-1"  <?=($dr_e['q69']=='1'?'checked':'')?> value="1" name="q69">
										<label for="q69-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q69-2"  <?=($dr_e['q69']=='2'?'checked':'')?>  value="2" name="q69">
										<label for="q69-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q69-3"  <?=($dr_e['q69']=='3'?'checked':'')?> value="3" name="q69">
										<label for="q69-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q69-4"  <?=($dr_e['q69']=='4'?'checked':'')?> value="4" name="q69">
										<label for="q69-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Το κλάμα / χαμόγελο / εκφάνσεις συναισθημάτων είναι μονότονο;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q70-1"  <?=($dr_e['q70']=='1'?'checked':'')?> value="1" name="q70">
										<label for="q70-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q70-2"  <?=($dr_e['q70']=='2'?'checked':'')?>  value="2" name="q70">
										<label for="q70-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q70-3"  <?=($dr_e['q70']=='3'?'checked':'')?> value="3" name="q70">
										<label for="q70-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q70-4"  <?=($dr_e['q70']=='4'?'checked':'')?> value="4" name="q70">
										<label for="q70-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Αντιλαμβάνεται το συναίσθημα του ομιλητή;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q71-1"  <?=($dr_e['q71']=='1'?'checked':'')?> value="1" name="q71">
										<label for="q71-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q71-2"  <?=($dr_e['q71']=='2'?'checked':'')?>  value="2" name="q71">
										<label for="q71-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q71-3"  <?=($dr_e['q71']=='3'?'checked':'')?> value="3" name="q71">
										<label for="q71-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q71-4"  <?=($dr_e['q71']=='4'?'checked':'')?> value="4" name="q71">
										<label for="q71-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
						</div>
						<div id="w4-4" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ως προς τα συναισθήματα ...</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q193-1"  <?=($dr_e['q193']=='1'?'checked':'')?> value="1" name="q193">
										<label for="q193-1">εκφράζει στο πρόσωπό του τα ανάλογα συναισθήματα (π.χ. γέλιο, κλάμα, θυμό, …);</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q193-2"  <?=($dr_e['q193']=='2'?'checked':'')?>  value="2" name="q193">
										<label for="q193-2">δεν εκφράζει στο πρόσωπό του τα ανάλογα συναισθήματα</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q193-3"  <?=($dr_e['q193']=='3'?'checked':'')?> value="3" name="q193">
										<label for="q193-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ως προς τα συναισθήματα ...</div>
								<div class="col-lg-9">
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q72-1" <?=(substr($dr_e['q72'],0,1)=='1'?'checked':'')?> name="q72-1">
										<label for="q72-1">έχει αδικαιολόγητες κρίσεις θυμού</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" id="q72-2" <?=(substr($dr_e['q72'],1,1)=='1'?'checked':'')?> name="q72-2">
										<label for="q72-2">έχει αδικαιολόγητο ενθουσιασμό</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q72-3" <?=(substr($dr_e['q72'],2,1)=='1'?'checked':'')?> name="q72-3">
										<label for="q72-3">έχει επιθετικές τάσεις</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q72-4" <?=(substr($dr_e['q72'],3,1)=='1'?'checked':'')?> name="q72-4">
										<label for="q72-4">παρουσιάζει υποταγή / απόσυρση</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q72-5" <?=(substr($dr_e['q72'],4,1)=='1'?'checked':'')?> name="q72-5">
										<label for="q72-5">ψυχολογικές μεταπτώσεις</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q72-6" <?=(substr($dr_e['q72'],5,1)=='1'?'checked':'')?> name="q72-6">
										<label for="q72-6">υπερβολικά ντροπαλό</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q72-7" <?=(substr($dr_e['q72'],6,1)=='1'?'checked':'')?> name="q72-7">
										<label for="q72-7">Αλλο</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q72-8" <?=(substr($dr_e['q72'],7,1)=='1'?'checked':'')?> name="q72-8">
										<label for="q72-8">Κανένα από τα παραπάνω</label>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Είναι φυσιολογική η αντίδραση στον πόνο;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q73-1"  <?=($dr_e['q73']=='1'?'checked':'')?> value="1" name="q73">
										<label for="q73-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q73-2"  <?=($dr_e['q73']=='2'?'checked':'')?>  value="2" name="q73">
										<label for="q73-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q73-3"  <?=($dr_e['q73']=='3'?'checked':'')?> value="3" name="q73">
										<label for="q73-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q73-4"  <?=($dr_e['q73']=='4'?'checked':'')?> value="4" name="q73">
										<label for="q73-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μπορεί να μιμείται χειρονομίες</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q74-1"  <?=($dr_e['q74']=='1'?'checked':'')?> value="1" name="q74">
										<label for="q74-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q74-2"  <?=($dr_e['q74']=='2'?'checked':'')?>  value="2" name="q74">
										<label for="q74-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q74-3"  <?=($dr_e['q74']=='3'?'checked':'')?> value="3" name="q74">
										<label for="q74-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q74-4"  <?=($dr_e['q74']=='4'?'checked':'')?> value="4" name="q74">
										<label for="q74-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Περπατάει συνεχώς στις μύτες των ποδιών</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q75-1"  <?=($dr_e['q75']=='1'?'checked':'')?> value="1" name="q75">
										<label for="q75-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q75-2"  <?=($dr_e['q75']=='2'?'checked':'')?>  value="2" name="q75">
										<label for="q75-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q75-3"  <?=($dr_e['q75']=='3'?'checked':'')?> value="3" name="q75">
										<label for="q75-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q75-4"  <?=($dr_e['q75']=='4'?'checked':'')?> value="4" name="q75">
										<label for="q75-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Σχετικά με την ισορροπία:</div>
								<div class="col-lg-9">
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" id="q76-1" <?=(substr($dr_e['q76'],0,1)=='1'?'checked':'')?> name="q76-1">
										<label for="q76-1">Περπατά ανάποδα, κάνει πατίνι, ποδήλατο, σχοινάκι (δυναμική ισορροπία)</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q76-2" <?=(substr($dr_e['q76'],1,1)=='1'?'checked':'')?> name="q76-2">
										<label for="q76-2">Ισορροπεί για 5 δευτερόλεπτα στο ένα πόδι (στατική ισορροπία)</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q76-3" <?=(substr($dr_e['q76'],2,1)=='1'?'checked':'')?> name="q76-3">
										<label for="q76-3">Ισορροπεί και περπατά στις μύτες</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" id="q76-4" <?=(substr($dr_e['q76'],3,1)=='1'?'checked':'')?> name="q76-4">
										<label for="q76-4">Ισορροπεί για ένα δευτερόλεπτο στο ένα πόδι</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q76-5" <?=(substr($dr_e['q76'],4,1)=='1'?'checked':'')?> name="q76-5">
										<label for="q76-5">Στέκεται με βοήθεια στο ένα πόδι</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q76-6" <?=(substr($dr_e['q76'],5,1)=='1'?'checked':'')?> name="q76-6">
										<label for="q76-6">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Έχει έλεγχο των σφυγκτήρων</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q34-1"  <?=($dr_e['q77']=='1'?'checked':'')?> value="1" name="q77">
										<label for="q77-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q34-2"  <?=($dr_e['q77']=='2'?'checked':'')?>  value="2" name="q77">
										<label for="q77-2">ΌΧΙ</label>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρουσιάζει προβλήματα νυχτερινής ενούρησης;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q34-1"  <?=($dr_e['q78']=='1'?'checked':'')?> value="1" name="q78">
										<label for="q78-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q34-2"  <?=($dr_e['q78']=='2'?'checked':'')?>  value="2" name="q78">
										<label for="q78-2">ΌΧΙ</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<ul class="pager">
						<li class="previous disabled">
							<a><i class="fas fa-angle-left"></i> Προηγούμενο</a>
						</li>
						<li class="finish hidden float-right">
							<a href="#" onClick="checkFields();">Ολοκλήρωση</a>
						</li>
						<li class="next">
							<a>Επόμενο <i class="fas fa-angle-right"></i></a>
						</li>
					</ul>
				</div>
			</section>
			<div class="form-group row" style="margin-top:20px;">
				<a href="#" onClick="checkFields();"><button type="button" class="btn btn-primary">Αποθήκευση</button></a> 
				<a style="margin-left:10px;" href="index.php?com=children"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
			</div>
		</div>
	</div>
<? } else if($_GET['option']=='4'){?>
	<div class="row">
		<div class="col">
			<section class="card form-wizard" id="w4">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
					</div>
					<h2 class="card-title">Στοματοπροσωπικός Έλεγχος/Ποιοτικά Στοιχεία Ομιλίας </h2>
				</header>
				<div class="card-body">
					<div class="wizard-progress wizard-progress-lg">
						<div class="steps-progress">
							<div class="progress-indicator"></div>
						</div>
						<ul class="nav wizard-steps">
							<li class="nav-item active">
								<a class="nav-link" href="#w4-1" data-toggle="tab"><span>1</span>Ενότητα 1</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-2" data-toggle="tab"><span>2</span>Ενότητα 2</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-3" data-toggle="tab"><span>3</span>Ενότητα 3</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-4" data-toggle="tab"><span>4</span>Ενότητα 4</a>
							</li>
						</ul>
					</div>
					<hr/>
					<input type='hidden' name='myOption' value='4'>
					<div class="tab-content">
						<div id="w4-1" class="tab-pane active">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατηρείστε το πρόσωπο του παιδιού σε ήρεμη κατάσταση. Τι παρατηρείτε;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q79-1" <?=($dr_e['q79']=='1'?'checked':'')?> value='1' name="q79">
										<label for="q79-1">φυσιολογικό</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q79-2" <?=($dr_e['q79']=='2'?'checked':'')?> value='2' name="q79">
										<label for="q79-2">τρέμουλο, τικ, σπασμούς</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q79-3" <?=($dr_e['q79']=='3'?'checked':'')?> value='3' name="q79">
										<label for="q79-3">νωθρότητα, υποτονία</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q79-4" <?=($dr_e['q79']=='4'?'checked':'')?> value='4' name="q79">
										<label for="q79-4">σιελλόροια</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q79-5" <?=($dr_e['q79']=='5'?'checked':'')?> value='5' name="q79">
										<label for="q79-5">ασυμμετρία προσώπου</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q79-6" <?=($dr_e['q79']=='6'?'checked':'')?> value='6' name="q79">
										<label for="q79-6">διαταραχή γεύσης (ξινό, πικρό, γλυκό, αλμυρό)</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q79-7" <?=($dr_e['q79']=='7'?'checked':'')?> value='7' name="q79">
										<label for="q79-7">ελλιπής / ακανόνιστη και καθυστερημένη οδοντοφυΐα</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατηρήστε το πρόσωπο του παιδιού. Σας φαίνεται συνεχώς θυμωμένο;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q80-1"  <?=($dr_e['q80']=='1'?'checked':'')?> value="1" name="q80">
										<label for="q80-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q80-2"  <?=($dr_e['q80']=='2'?'checked':'')?>  value="2" name="q80">
										<label for="q80-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q80-3"  <?=($dr_e['q80']=='3'?'checked':'')?> value="3" name="q80">
										<label for="q80-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q80-4"  <?=($dr_e['q80']=='4'?'checked':'')?> value="4" name="q80">
										<label for="q80-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατηρείστε το χρώμα γλώσσας, ουρανίσκο (υπερώα), φάρυγγα</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q81-1"  <?=($dr_e['q81']=='1'?'checked':'')?> value="1" name="q81">
										<label for="q81-1">κόκκινο</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q81-2"  <?=($dr_e['q81']=='2'?'checked':'')?>  value="2" name="q81">
										<label for="q81-2">γκριζωπό</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q81-3"  <?=($dr_e['q81']=='3'?'checked':'')?> value="3" name="q81">
										<label for="q81-3">γαλαζωπό</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q81-4"  <?=($dr_e['q81']=='4'?'checked':'')?> value="4" name="q81">
										<label for="q81-4">υπόλευκο χρώμα/ γραμμή στη σκληρή & μαλακή υπερώα (ουρανίσκος)</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q81-5"  <?=($dr_e['q81']=='5'?'checked':'')?> value="5" name="q81">
										<label for="q81-5">μη φυσιολογικό σκούρο ή ημιδιαφανές στον ουρανίσκο (υπερώα)</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q81-6"  <?=($dr_e['q81']=='6'?'checked':'')?> value="6" name="q81">
										<label for="q81-6">σκούρα στίγματα</label>
									</div>
								</div>
							</div>
						</div>
						
						<div id="w4-2" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Δώστε ένα χαρακτηρισμό για τη ροή της ομιλίας</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q82-1"  <?=($dr_e['q82']=='1'?'checked':'')?> value="1" name="q82">
										<label for="q82-1">πολύ γρήγορη</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q82-2"  <?=($dr_e['q82']=='2'?'checked':'')?>  value="2" name="q82">
										<label for="q82-2">γρήγορη</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q82-3"  <?=($dr_e['q82']=='3'?'checked':'')?> value="3" name="q82">
										<label for="q82-3">φυσιολογική</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q82-4"  <?=($dr_e['q82']=='4'?'checked':'')?> value="4" name="q82">
										<label for="q82-4">αργή</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q82-5"  <?=($dr_e['q82']=='5'?'checked':'')?> value="5" name="q82">
										<label for="q82-5">πολύ αργή</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατηρείστε τη φωνή του παιδιού: Τόνος / Ύψος</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q83-1"  <?=($dr_e['q83']=='1'?'checked':'')?> value="1" name="q83">
										<label for="q83-1">Πολύ χαμηλός</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q83-2"  <?=($dr_e['q83']=='2'?'checked':'')?>  value="2" name="q83">
										<label for="q83-2">Φυσιολογικός</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q83-3"  <?=($dr_e['q83']=='3'?'checked':'')?> value="3" name="q83">
										<label for="q83-3">Πολύ υψηλός</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατηρείστε τη φωνή του παιδιού: Ένταση</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q84-1"  <?=($dr_e['q84']=='1'?'checked':'')?> value="1" name="q84">
										<label for="q84-1">Ανεπαρκής (διαφυγή αέρα από τη γλωττίδα/σταδιακή πτώση της έντασης)</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q84-2"  <?=($dr_e['q84']=='2'?'checked':'')?>  value="2" name="q84">
										<label for="q84-2">Φυσιολογική</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q84-3"  <?=($dr_e['q84']=='3'?'checked':'')?> value="3" name="q84">
										<label for="q84-3">Πολύ υψηλή</label>
									</div>
								</div>
							</div>
						</div>
						
						<div id="w4-3" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατηρείστε τη φωνή του παιδιού: Ποιότητα</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q85-1"  <?=($dr_e['q85']=='1'?'checked':'')?> value="1" name="q85">
										<label for="q85-1">Βραχνή / Με Θόρυβο</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q85-2"  <?=($dr_e['q85']=='2'?'checked':'')?>  value="2" name="q85">
										<label for="q85-2">Φυσιολογική</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q85-3"  <?=($dr_e['q85']=='3'?'checked':'')?> value="3" name="q85">
										<label for="q85-3">Σφιχτή / Σκληρή</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατηρείστε τη φωνή του παιδιού: Ρινική Αντήχηση</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q86-1"  <?=($dr_e['q86']=='1'?'checked':'')?> value="1" name="q86">
										<label for="q86-1">Υπερρινική (μοιάζει τσιριχτή)</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q86-2"  <?=($dr_e['q86']=='2'?'checked':'')?>  value="2" name="q86">
										<label for="q86-2">Φυσιολογική</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q86-3"  <?=($dr_e['q86']=='3'?'checked':'')?> value="3" name="q86">
										<label for="q86-3">Υπορινική (μοιάζει μπουκωμένη)</label>
									</div>
								</div>
							</div>
						</div>
						
						<div id="w4-4" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατηρείστε τη φωνή του παιδιού: Στοματική Αντήχηση</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q87-1"  <?=($dr_e['q87']=='1'?'checked':'')?> value="1" name="q87">
										<label for="q87-1">Υπερβολικά οπίσθια μεταφορά της γλώσσας με αποτέλεσμα την ανεπάρκεια στοματικής απήχησης</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q87-2"  <?=($dr_e['q87']=='2'?'checked':'')?>  value="2" name="q87">
										<label for="q87-2">Φυσιολογική</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q87-3"  <?=($dr_e['q87']=='3'?'checked':'')?> value="3" name="q87">
										<label for="q87-3">Υπερβολικά πρόσθια μεταφορά της γλώσσας με αποτέλεσμα την ΄αδύναμη΄ ή ΄μωρουδίστικη΄ φωνή</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατηρείτε στην αναπνοή του παιδιού κάποιο από τα παρακάτω;</div>
								<div class="col-lg-9">
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" id="q88-1" <?=(substr($dr_e['q88'],0,1)=='1'?'checked':'')?> name="q88-1">
										<label for="q88-1">κλεφτές ανάσες κατά την ομιλία</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q88-2" <?=(substr($dr_e['q88'],1,1)=='1'?'checked':'')?> name="q88-2">
										<label for="q88-2">άγχος</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q88-3" <?=(substr($dr_e['q88'],2,1)=='1'?'checked':'')?> name="q88-3">
										<label for="q88-3">κομπιάσματα (μπλοκαρίσματα)</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" id="q88-4" <?=(substr($dr_e['q88'],3,1)=='1'?'checked':'')?> name="q88-4">
										<label for="q88-4">επαναλήψεις</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q88-5" <?=(substr($dr_e['q88'],4,1)=='1'?'checked':'')?> name="q88-5">
										<label for="q88-5">επιμήκυνση</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q88-6" <?=(substr($dr_e['q88'],5,1)=='1'?'checked':'')?> name="q88-6">
										<label for="q88-6">εναλλαγή στην ένταση</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q88-7" <?=(substr($dr_e['q88'],6,1)=='1'?'checked':'')?> name="q88-7">
										<label for="q88-7">παύσεις</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q88-8" <?=(substr($dr_e['q88'],7,1)=='1'?'checked':'')?> name="q88-8">
										<label for="q88-8">ανολοκλήρωτη λέξη</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q88-9" <?=(substr($dr_e['q88'],8,1)=='1'?'checked':'')?> name="q88-9">
										<label for="q88-9">Κανένα από τα παραπάνω</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<ul class="pager">
						<li class="previous disabled">
							<a><i class="fas fa-angle-left"></i> Προηγούμενο</a>
						</li>
						<li class="finish hidden float-right">
							<a href="#" onClick="checkFields();">Ολοκλήρωση</a>
						</li>
						<li class="next">
							<a>Επόμενο <i class="fas fa-angle-right"></i></a>
						</li>
					</ul>
				</div>
			</section>
			<div class="form-group row" style="margin-top:20px;">
				<a href="#" onClick="checkFields();"><button type="button" class="btn btn-primary">Αποθήκευση</button></a> 
				<a style="margin-left:10px;" href="index.php?com=children"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
			</div>
		</div>
	</div>
<? } else if($_GET['option']=='5'){?>
	<div class="row">
		<div class="col">
			<section class="card form-wizard" id="w4">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
					</div>
					<h2 class="card-title">Άρθρωση/ Φωνολογία</h2>
				</header>
				<div class="card-body">
					<div class="wizard-progress wizard-progress-lg">
						<div class="steps-progress">
							<div class="progress-indicator"></div>
						</div>
						<ul class="nav wizard-steps">
							<li class="nav-item active">
								<a class="nav-link" href="#w4-1" data-toggle="tab"><span>1</span>Ενότητα 1</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-2" data-toggle="tab"><span>2</span>Ενότητα 2</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-3" data-toggle="tab"><span>3</span>Ενότητα 3</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-4" data-toggle="tab"><span>4</span>Ενότητα 4</a>
							</li>
						</ul>
					</div>
					<input type='hidden' name='myOption' value='5'>
					<div class="tab-content">
						<div id="w4-1" class="tab-pane active">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Το παιδί αρθρώνει σωστά όλους τους ήχους;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q89-1" <?=($dr_e['q89']=='1'?'checked':'')?> value='1' name="q89">
										<label for="q89-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q89-2" <?=($dr_e['q89']=='2'?'checked':'')?> value='2' name="q89">
										<label for="q89-2">Παράγει σύμφωνα με ακρίβεια 90 %</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q89-3" <?=($dr_e['q89']=='3'?'checked':'')?> value='3' name="q89">
										<label for="q89-3">Χρησιμοποιεί το 50% των συμφώνων & των συμπλεγμάτων</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q89-4" <?=($dr_e['q89']=='4'?'checked':'')?> value='4' name="q89">
										<label for="q89-4">Αρθρώνει μόνο φωνήεντα</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q89-5" <?=($dr_e['q89']=='5'?'checked':'')?> value='5' name="q89">
										<label for="q89-5">Δεν μιλάει καθόλου</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Οι λάθος ήχοι προφέρονται πάντα με τον ίδιο τρόπο;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q90-1"  <?=($dr_e['q90']=='1'?'checked':'')?> value="1" name="q90">
										<label for="q90-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q90-2"  <?=($dr_e['q90']=='2'?'checked':'')?>  value="2" name="q90">
										<label for="q90-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q90-3"  <?=($dr_e['q90']=='3'?'checked':'')?> value="3" name="q90">
										<label for="q90-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q90-4"  <?=($dr_e['q90']=='4'?'checked':'')?> value="4" name="q90">
										<label for="q90-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Πως προφέρει το [ρ];</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q91-1"  <?=($dr_e['q91']=='1'?'checked':'')?> value="1" name="q91">
										<label for="q91-1">Κανονικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q91-2"  <?=($dr_e['q91']=='2'?'checked':'')?>  value="2" name="q91">
										<label for="q91-2">μοιάζει με [γ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q91-3"  <?=($dr_e['q91']=='3'?'checked':'')?> value="3" name="q91">
										<label for="q91-3">μοιάζει με [ντ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q91-4"  <?=($dr_e['q91']=='4'?'checked':'')?> value="4" name="q91">
										<label for="q91-4">μοιάζει με [λ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q91-5"  <?=($dr_e['q91']=='5'?'checked':'')?> value="5" name="q91">
										<label for="q91-5">παραλείπεται</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q91-6"  <?=($dr_e['q91']=='6'?'checked':'')?> value="6" name="q91">
										<label for="q91-6">άλλο, δεν ταιριάζει με κάποιο από τα παραπάνω</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Πως προφέρει το [σ];</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q92-1"  <?=($dr_e['q92']=='1'?'checked':'')?> value="1" name="q92">
										<label for="q92-1">Κανονικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q92-2"  <?=($dr_e['q92']=='2'?'checked':'')?>  value="2" name="q92">
										<label for="q92-2">μοιάζει με [τ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q92-3"  <?=($dr_e['q92']=='3'?'checked':'')?> value="3" name="q92">
										<label for="q92-3">μοιάζει με [ντ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q92-4"  <?=($dr_e['q92']=='4'?'checked':'')?> value="4" name="q92">
										<label for="q92-4">μοιάζει με [κι]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q92-5"  <?=($dr_e['q92']=='5'?'checked':'')?> value="5" name="q92">
										<label for="q92-5">μοιάζει με [γι]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q92-6"  <?=($dr_e['q92']=='5'?'checked':'')?> value="6" name="q92">
										<label for="q92-6">μοιάζει με [θ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q92-6"  <?=($dr_e['q92']=='5'?'checked':'')?> value="7" name="q92">
										<label for="q92-7">άλλο, δεν ταιριάζει με κάποιο από τα παραπάνω</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q92-8"  <?=($dr_e['q92']=='5'?'checked':'')?> value="8" name="q92">
										<label for="q92-8">μοιάζει με [χι]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q92-9"  <?=($dr_e['q92']=='5'?'checked':'')?> value="9" name="q92">
										<label for="q92-9">παραλείπεται</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Πως προφέρει το [β];</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q93-1"  <?=($dr_e['q93']=='1'?'checked':'')?> value="1" name="q93">
										<label for="q93-1">Κανονικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q93-2"  <?=($dr_e['q93']=='2'?'checked':'')?>  value="2" name="q93">
										<label for="q93-2">μοιάζει με [φ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q93-3"  <?=($dr_e['q93']=='3'?'checked':'')?> value="3" name="q93">
										<label for="q93-3">μοιάζει με [μπ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q93-4"  <?=($dr_e['q93']=='4'?'checked':'')?> value="4" name="q93">
										<label for="q93-4">μοιάζει με [μπ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q93-5"  <?=($dr_e['q93']=='5'?'checked':'')?> value="5" name="q93">
										<label for="q93-5">μοιάζει με [μπ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q93-6"  <?=($dr_e['q93']=='6'?'checked':'')?> value="6" name="q93">
										<label for="q93-6">μοιάζει με [μπ]</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Πως προφέρει το [δ];</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q94-1"  <?=($dr_e['q94']=='1'?'checked':'')?> value="1" name="q94">
										<label for="q94-1">Κανονικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q94-2"  <?=($dr_e['q94']=='2'?'checked':'')?>  value="2" name="q94">
										<label for="q94-2">μοιάζει με [β]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q94-3"  <?=($dr_e['q94']=='3'?'checked':'')?> value="3" name="q94">
										<label for="q94-3">μοιάζει με [μπ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q94-4"  <?=($dr_e['q94']=='4'?'checked':'')?> value="4" name="q94">
										<label for="q94-4">μοιάζει με [ντ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q94-5"  <?=($dr_e['q94']=='5'?'checked':'')?> value="5" name="q94">
										<label for="q94-5">μοιάζει με [γι]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q94-6"  <?=($dr_e['q94']=='6'?'checked':'')?> value="6" name="q94">
										<label for="q94-6">μοιάζει με [ζ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q94-7"  <?=($dr_e['q94']=='7'?'checked':'')?> value="7" name="q94">
										<label for="q94-7">παραλείπεται</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q94-8"  <?=($dr_e['q94']=='8'?'checked':'')?> value="8" name="q94">
										<label for="q94-8">άλλο, δεν ταιριάζει με κάποιο από τα παραπάνω</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Πως προφέρει το [φ];</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q95-1"  <?=($dr_e['q95']=='1'?'checked':'')?> value="1" name="q95">
										<label for="q95-1">Κανονικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q95-2"  <?=($dr_e['q95']=='2'?'checked':'')?>  value="2" name="q95">
										<label for="q95-2">μοιάζει με [π]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q95-3"  <?=($dr_e['q95']=='3'?'checked':'')?> value="3" name="q95">
										<label for="q95-3">μοιάζει με [κι]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q95-4"  <?=($dr_e['q95']=='4'?'checked':'')?> value="4" name="q95">
										<label for="q95-4">παραλείπεται</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q95-5"  <?=($dr_e['q95']=='5'?'checked':'')?> value="5" name="q95">
										<label for="q95-5">άλλο, δεν ταιριάζει με κάποιο από τα παραπάνω</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Πως προφέρει το [θ];</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q96-1"  <?=($dr_e['q96']=='1'?'checked':'')?> value="1" name="q96">
										<label for="q96-1">Κανονικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q96-2"  <?=($dr_e['q96']=='2'?'checked':'')?>  value="2" name="q96">
										<label for="q96-2">μοιάζει με [σ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q96-3"  <?=($dr_e['q96']=='3'?'checked':'')?> value="3" name="q96">
										<label for="q96-3">μοιάζει με [φ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q96-4"  <?=($dr_e['q96']=='4'?'checked':'')?> value="4" name="q96">
										<label for="q96-4">μοιάζει με [π]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q96-5"  <?=($dr_e['q96']=='5'?'checked':'')?> value="5" name="q96">
										<label for="q96-5">μοιάζει με [τ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q96-6"  <?=($dr_e['q96']=='6'?'checked':'')?> value="6" name="q96">
										<label for="q96-6">μοιάζει με [κι]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q96-7"  <?=($dr_e['q96']=='7'?'checked':'')?> value="7" name="q96">
										<label for="q96-7">παραλείπεται</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q96-8"  <?=($dr_e['q96']=='8'?'checked':'')?> value="8" name="q96">
										<label for="q96-8">άλλο, δεν ταιριάζει με κάποιο από τα παραπάνω</label>
									</div>
								</div>
							</div>
						</div>
						<div id="w4-2" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Πως προφέρει το [ζ];</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q97-1"  <?=($dr_e['q97']=='1'?'checked':'')?> value="1" name="q97">
										<label for="q97-1">Κανονικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q97-2"  <?=($dr_e['q97']=='2'?'checked':'')?>  value="2" name="q97">
										<label for="q97-2">μοιάζει με [σ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q97-3"  <?=($dr_e['q97']=='3'?'checked':'')?> value="3" name="q97">
										<label for="q97-3">μοιάζει με [ντ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q97-4"  <?=($dr_e['q97']=='4'?'checked':'')?> value="4" name="q97">
										<label for="q97-4">μοιάζει με αηχοποίηση του [σ] (πιο χαμηλόφωνο</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q97-5"  <?=($dr_e['q97']=='5'?'checked':'')?> value="5" name="q97">
										<label for="q97-5">μοιάζει με [γι]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q97-6"  <?=($dr_e['q97']=='6'?'checked':'')?> value="6" name="q97">
										<label for="q97-6">παραλείπεται</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q97-7"  <?=($dr_e['q97']=='7'?'checked':'')?> value="7" name="q97">
										<label for="q97-7">άλλο, δεν ταιριάζει με κάποιο από τα παραπάνω</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Πως προφέρει το [κ];</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q98-1"  <?=($dr_e['q98']=='1'?'checked':'')?> value="1" name="q98">
										<label for="q98-1">Κανονικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q98-2"  <?=($dr_e['q98']=='2'?'checked':'')?>  value="2" name="q98">
										<label for="q98-2">μοιάζει με [τ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q98-3"  <?=($dr_e['q98']=='3'?'checked':'')?> value="3" name="q98">
										<label for="q98-3">παραλείπεται</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q98-4"  <?=($dr_e['q98']=='4'?'checked':'')?> value="4" name="q98">
										<label for="q98-4">άλλο, δεν ταιριάζει με κάποιο από τα παραπάνω</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Πως προφέρει το [τ];</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q99-1"  <?=($dr_e['q99']=='1'?'checked':'')?> value="1" name="q99">
										<label for="q99-1">Κανονικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q99-2"  <?=($dr_e['q99']=='2'?'checked':'')?>  value="2" name="q99">
										<label for="q99-2">μοιάζει με [π]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q99-3"  <?=($dr_e['q99']=='3'?'checked':'')?> value="3" name="q99">
										<label for="q99-3">μοιάζει με [ντ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q99-4"  <?=($dr_e['q99']=='4'?'checked':'')?> value="4" name="q99">
										<label for="q99-4">μοιάζει με [κ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q99-5"  <?=($dr_e['q99']=='5'?'checked':'')?> value="5" name="q99">
										<label for="q99-5">μοιάζει με [χ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q99-6"  <?=($dr_e['q99']=='6'?'checked':'')?> value="6" name="q99">
										<label for="q99-6">παραλείπεται</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q99-7"  <?=($dr_e['q99']=='7'?'checked':'')?> value="7" name="q99">
										<label for="q99-7">άλλο, δεν ταιριάζει με κάποιο από τα παραπάνω</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Πως προφέρει το [γ];</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q100-1"  <?=($dr_e['q100']=='1'?'checked':'')?> value="1" name="q100">
										<label for="q100-1">Κανονικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q100-2"  <?=($dr_e['q100']=='2'?'checked':'')?>  value="2" name="q100">
										<label for="q100-2">μοιάζει με [κ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q100-3"  <?=($dr_e['q100']=='3'?'checked':'')?> value="3" name="q100">
										<label for="q100-3">μοιάζει με [χ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q100-4"  <?=($dr_e['q100']=='4'?'checked':'')?> value="4" name="q100">
										<label for="q100-4">μοιάζει με [γκ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q100-5"  <?=($dr_e['q100']=='5'?'checked':'')?> value="5" name="q100">
										<label for="q100-5">μοιάζει με [γι]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q100-6"  <?=($dr_e['q100']=='6'?'checked':'')?> value="6" name="q100">
										<label for="q100-6">παραλείπεται</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q100-7"  <?=($dr_e['q100']=='7'?'checked':'')?> value="7" name="q100">
										<label for="q100-7">άλλο, δεν ταιριάζει με κάποιο από τα παραπάνω</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Πως προφέρει το [χ];</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q101-1"  <?=($dr_e['q101']=='1'?'checked':'')?> value="1" name="q101">
										<label for="q101-1">Κανονικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q101-2"  <?=($dr_e['q101']=='2'?'checked':'')?>  value="2" name="q101">
										<label for="q101-2">μοιάζει με [κ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q101-3"  <?=($dr_e['q101']=='3'?'checked':'')?> value="3" name="q101">
										<label for="q101-3">μοιάζει με [κι]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q101-4"  <?=($dr_e['q101']=='4'?'checked':'')?> value="4" name="q101">
										<label for="q101-4">παραλείπεται</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q101-5"  <?=($dr_e['q101']=='5'?'checked':'')?> value="5" name="q101">
										<label for="q101-5">άλλο, δεν ταιριάζει με κάποιο από τα παραπάνω</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Πως προφέρει το [λ];</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q102-1"  <?=($dr_e['q102']=='1'?'checked':'')?> value="1" name="q102">
										<label for="q102-1">Κανονικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q102-2"  <?=($dr_e['q102']=='2'?'checked':'')?>  value="2" name="q102">
										<label for="q102-2">μοιάζει με [ντ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q102-3"  <?=($dr_e['q102']=='3'?'checked':'')?> value="3" name="q102">
										<label for="q102-3">μοιάζει με [γι]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q102-4"  <?=($dr_e['q102']=='4'?'checked':'')?> value="4" name="q102">
										<label for="q102-4">μοιάζει με [ν]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q102-5"  <?=($dr_e['q102']=='5'?'checked':'')?> value="5" name="q102">
										<label for="q102-5">παραλείπεται</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q102-6"  <?=($dr_e['q102']=='6'?'checked':'')?> value="6" name="q102">
										<label for="q102-6">παραλείπεται</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Πως προφέρει το [ξ];</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q103-1"  <?=($dr_e['q103']=='1'?'checked':'')?> value="1" name="q103">
										<label for="q103-1">Κανονικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q103-2"  <?=($dr_e['q103']=='2'?'checked':'')?>  value="2" name="q103">
										<label for="q103-2">μοιάζει με [τσ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q103-3"  <?=($dr_e['q103']=='3'?'checked':'')?> value="3" name="q103">
										<label for="q103-3">παραλείπεται</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q103-4"  <?=($dr_e['q103']=='4'?'checked':'')?> value="4" name="q103">
										<label for="q103-4">άλλο, δεν ταιριάζει με κάποιο από τα παραπάνω</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Πως προφέρει το [ψ];</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q104-1"  <?=($dr_e['q104']=='1'?'checked':'')?> value="1" name="q104">
										<label for="q104-1">Κανονικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q104-2"  <?=($dr_e['q104']=='2'?'checked':'')?>  value="2" name="q104">
										<label for="q104-2">μοιάζει με [τσ]</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q104-3"  <?=($dr_e['q104']=='3'?'checked':'')?> value="3" name="q104">
										<label for="q104-3">παραλείπεται</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q104-4"  <?=($dr_e['q104']=='4'?'checked':'')?> value="4" name="q104">
										<label for="q104-4">άλλο, δεν ταιριάζει με κάποιο από τα παραπάνω</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q104-5"  <?=($dr_e['q104']=='5'?'checked':'')?> value="5" name="q104">
										<label for="q104-5">άλλο, δεν ταιριάζει με κάποιο από τα παραπάνω</label>
									</div>
								</div>
							</div>
						</div>
						
						<div id="w4-3" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί διαφορετικό τόνο στη φωνή αναλόγως σε ποιον μιλάει ή τι θέλει να πει;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q105-1"  <?=($dr_e['q105']=='1'?'checked':'')?> value="1" name="q105">
										<label for="q105-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q105-2"  <?=($dr_e['q105']=='2'?'checked':'')?>  value="2" name="q105">
										<label for="q105-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q105-3"  <?=($dr_e['q105']=='3'?'checked':'')?> value="3" name="q105">
										<label for="q105-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q105-4"  <?=($dr_e['q105']=='4'?'checked':'')?> value="4" name="q105">
										<label for="q105-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Το παιδί διακρίνει όλους τους ήχους, αν του ζητηθεί;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q106-1"  <?=($dr_e['q106']=='1'?'checked':'')?> value="1" name="q106">
										<label for="q106-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q106-2"  <?=($dr_e['q106']=='2'?'checked':'')?>  value="2" name="q106">
										<label for="q106-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q106-3"  <?=($dr_e['q106']=='3'?'checked':'')?> value="3" name="q106">
										<label for="q106-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q106-4"  <?=($dr_e['q106']=='4'?'checked':'')?> value="4" name="q106">
										<label for="q106-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Αναγνωρίζει όλα τα φωνήματα (π.χ. αναγνωρίζει τη διαφορά β-δ);</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q107-1"  <?=($dr_e['q107']=='1'?'checked':'')?> value="1" name="q107">
										<label for="q107-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q107-2"  <?=($dr_e['q107']=='2'?'checked':'')?>  value="2" name="q107">
										<label for="q107-2">ΌΧΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Αναγνωρίζει το κοινό φώνημα σε λέξη (π.χ. πατάτα);</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q108-1"  <?=($dr_e['q108']=='1'?'checked':'')?> value="1" name="q108">
										<label for="q108-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q108-2"  <?=($dr_e['q108']=='2'?'checked':'')?>  value="2" name="q108">
										<label for="q108-2">ΌΧΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Εντοπίζει και αναγνωρίζει την ομοιοκαταληξία (κάνει ρίμα);</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q109-1"  <?=($dr_e['q109']=='1'?'checked':'')?> value="1" name="q109">
										<label for="q109-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q109-2"  <?=($dr_e['q109']=='2'?'checked':'')?>  value="2" name="q109">
										<label for="q109-2">ΌΧΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μπορεί να κάνει κατάτμιση (ανάλυση) λέξεων σε φωνές ή συλλαβές όταν του ζητηθεί ( π.χ. γάλα => γ-ά-λ-α)</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q110-1"  <?=($dr_e['q110']=='1'?'checked':'')?> value="1" name="q110">
										<label for="q110-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q110-2"  <?=($dr_e['q110']=='2'?'checked':'')?>  value="2" name="q110">
										<label for="q110-2">ΌΧΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μπορεί να κάνει συγκερασμό (σύνθεση) φωνών ή συλλαβών για να φτιάξει μια λέξη όταν του ζητηθεί (π.χ. γ-ά-λ-α => γάλα)</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q111-1"  <?=($dr_e['q111']=='1'?'checked':'')?> value="1" name="q111">
										<label for="q111-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q111-2"  <?=($dr_e['q111']=='2'?'checked':'')?>  value="2" name="q111">
										<label for="q111-2">ΌΧΙ</label>
									</div>
								</div>
							</div>
							
						</div>
						<div id="w4-4" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μπορεί να κάνει επιλογή και απαρίθμηση συλλαβών σε μια λέξη όταν του ζητηθεί (π.χ. γάλα => γά[1] λα[2])</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q112-1"  <?=($dr_e['q112']=='1'?'checked':'')?> value="1" name="q112">
										<label for="q112-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q111-2"  <?=($dr_e['q112']=='2'?'checked':'')?>  value="2" name="q112">
										<label for="q112-2">ΌΧΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μπορεί να εντοπίζει το φώνημα/συλλαβή που έχει διαγραφεί από λέξη (π.χ. ράουλα =>φράουλα);</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q113-1"  <?=($dr_e['q113']=='1'?'checked':'')?> value="1" name="q113">
										<label for="q113-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q113-2"  <?=($dr_e['q113']=='2'?'checked':'')?>  value="2" name="q113">
										<label for="q113-2">ΌΧΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μπορεί να εντοπίζει αντικατάσταση, προσθήκη, διαγραφή φώνηματος/συλλαβής σε λέξη (π.χ. βόδι - πόδι, τραμπάλα - μπάλα);</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q114-1"  <?=($dr_e['q114']=='1'?'checked':'')?> value="1" name="q114">
										<label for="q114-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q114-2"  <?=($dr_e['q114']=='2'?'checked':'')?>  value="2" name="q114">
										<label for="q114-2">ΌΧΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Στον αυθόρμητο λόγο παρατηρείτε το παιδί να χάνει συλλαβές (πτώσεις συλλαβών) (π.χ. άλα αντί γάλα, μεμέρι αντί μεσημέρι);</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q115-1"  <?=($dr_e['q115']=='1'?'checked':'')?> value="1" name="q115">
										<label for="q115-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q115-2"  <?=($dr_e['q115']=='2'?'checked':'')?>  value="2" name="q115">
										<label for="q115-2">ΌΧΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Στον αυθόρμητο λόγο παρατηρείτε το παιδί να απλουστεύει συμπλέγματα συμφώνων (π.χ. κάτα αντί κράτα);</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q116-1"  <?=($dr_e['q116']=='1'?'checked':'')?> value="1" name="q116">
										<label for="q116-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q116-2"  <?=($dr_e['q116']=='2'?'checked':'')?>  value="2" name="q116">
										<label for="q116-2">ΌΧΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρατηρείτε αλλαγές στη δομή των λέξεων (αντιμεταθέσεις: π.χ. [κορκόδειλος] αντί κροκόδειλος, συγχωνεύσεις: [καληφέρα] αντί καλησπέρα και [επένθεση] χίλισε αντί κλείσε)</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q117-1"  <?=($dr_e['q117']=='1'?'checked':'')?> value="1" name="q117">
										<label for="q117-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q117-2"  <?=($dr_e['q117']=='2'?'checked':'')?>  value="2" name="q117">
										<label for="q117-2">ΌΧΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Τραγουδάει τραγούδια/ απαγγέλει στοίχους με ομοιοκαταληξία</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q118-1"  <?=($dr_e['q118']=='1'?'checked':'')?> value="1" name="q118">
										<label for="q118-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q118-2"  <?=($dr_e['q118']=='2'?'checked':'')?>  value="2" name="q118">
										<label for="q118-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q118-3"  <?=($dr_e['q118']=='3'?'checked':'')?>  value="3" name="q118">
										<label for="q118-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<ul class="pager">
						<li class="previous disabled">
							<a><i class="fas fa-angle-left"></i> Προηγούμενο</a>
						</li>
						<li class="finish hidden float-right">
							<a href="#" onClick="checkFields();">Ολοκλήρωση</a>
						</li>
						<li class="next">
							<a>Επόμενο <i class="fas fa-angle-right"></i></a>
						</li>
					</ul>
				</div>
			</section>
			<div class="form-group row" style="margin-top:20px;">
				<a href="#" onClick="checkFields();"><button type="button" class="btn btn-primary">Αποθήκευση</button></a> 
				<a style="margin-left:10px;" href="index.php?com=children"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
			</div>
		</div>
	</div>
<? } else if($_GET['option']=='6'){?>
	<div class="row">
		<div class="col">
			<section class="card form-wizard" id="w4">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
					</div>
					<h2 class="card-title">Έκφραση της Γλώσσας</h2>
				</header>
				<div class="card-body">
					<div class="wizard-progress wizard-progress-lg">
						<div class="steps-progress">
							<div class="progress-indicator"></div>
						</div>
						<ul class="nav wizard-steps">
							<li class="nav-item active">
								<a class="nav-link" href="#w4-1" data-toggle="tab"><span>1</span>Ενότητα 1</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-2" data-toggle="tab"><span>2</span>Ενότητα 2</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-3" data-toggle="tab"><span>3</span>Ενότητα 3</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-4" data-toggle="tab"><span>4</span>Ενότητα 4</a>
							</li>
						</ul>
					</div>
					<input type='hidden' name='myOption' value='6'>
					<div class="tab-content">
						<div id="w4-1" class="tab-pane active">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί προτάσεις ...</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q119-1" <?=($dr_e['q119']=='1'?'checked':'')?> value='1' name="q119">
										<label for="q119-1">1 - 3 λέξεων</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q119-2" <?=($dr_e['q119']=='2'?'checked':'')?> value='2' name="q119">
										<label for="q119-2">4 - 6 λέξεων</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q119-3" <?=($dr_e['q119']=='3'?'checked':'')?> value='3' name="q119">
										<label for="q119-3">περισσότερες από 6 λέξεις</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q119-4" <?=($dr_e['q119']=='4'?'checked':'')?> value='4' name="q119">
										<label for="q119-4">Δεν μιλάει</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Έχει φτωχό λεξιλόγιο;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q120-1"  <?=($dr_e['q120']=='1'?'checked':'')?> value="1" name="q120">
										<label for="q120-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q120-2"  <?=($dr_e['q120']=='2'?'checked':'')?>  value="2" name="q120">
										<label for="q120-2">ΌΧΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί άρθρα, μην/δεν;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q121-1"  <?=($dr_e['q121']=='1'?'checked':'')?> value="1" name="q121">
										<label for="q121-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q121-2"  <?=($dr_e['q121']=='2'?'checked':'')?>  value="2" name="q121">
										<label for="q121-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί ρήματα;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q122-1"  <?=($dr_e['q122']=='1'?'checked':'')?> value="1" name="q122">
										<label for="q122-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q122-2"  <?=($dr_e['q122']=='2'?'checked':'')?>  value="2" name="q122">
										<label for="q122-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί ενεστώτα;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q123-1"  <?=($dr_e['q123']=='1'?'checked':'')?> value="1" name="q123">
										<label for="q123-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q123-2"  <?=($dr_e['q123']=='2'?'checked':'')?>  value="2" name="q123">
										<label for="q123-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί αόριστο;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q124-1"  <?=($dr_e['q124']=='1'?'checked':'')?> value="1" name="q124">
										<label for="q124-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q124-2"  <?=($dr_e['q124']=='2'?'checked':'')?>  value="2" name="q124">
										<label for="q124-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί μέλλοντα;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q125-1"  <?=($dr_e['q125']=='1'?'checked':'')?> value="1" name="q125">
										<label for="q125-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q125-2"  <?=($dr_e['q125']=='2'?'checked':'')?>  value="2" name="q125">
										<label for="q125-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί ρηματικό βίωμα (σωστή κλήση ρημάτων);</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q126-1"  <?=($dr_e['q126']=='1'?'checked':'')?> value="1" name="q126">
										<label for="q126-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q126-2"  <?=($dr_e['q126']=='2'?'checked':'')?>  value="2" name="q126">
										<label for="q126-2">Όχι</label>
									</div>
								</div>
							</div>
						</div>
						<div id="w4-2" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί παθητική φωνή;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q127-1"  <?=($dr_e['q127']=='1'?'checked':'')?> value="1" name="q127">
										<label for="q127-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q127-2"  <?=($dr_e['q127']=='2'?'checked':'')?>  value="2" name="q127">
										<label for="q127-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί ουσιαστικά;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q128-1"  <?=($dr_e['q128']=='1'?'checked':'')?> value="1" name="q128">
										<label for="q128-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q128-2"  <?=($dr_e['q128']=='2'?'checked':'')?>  value="2" name="q128">
										<label for="q128-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί ενικό-πληθυντικό;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q129-1"  <?=($dr_e['q129']=='1'?'checked':'')?> value="1" name="q129">
										<label for="q129-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q129-2"  <?=($dr_e['q129']=='2'?'checked':'')?>  value="2" name="q129">
										<label for="q129-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί ανώμαλους πληθυντικούς;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q130-1"  <?=($dr_e['q130']=='1'?'checked':'')?> value="1" name="q130">
										<label for="q130-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q130-2"  <?=($dr_e['q130']=='2'?'checked':'')?>  value="2" name="q130">
										<label for="q130-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί κτητικές αντωνυμίες;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q131-1"  <?=($dr_e['q131']=='1'?'checked':'')?> value="1" name="q131">
										<label for="q131-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q131-2"  <?=($dr_e['q131']=='2'?'checked':'')?>  value="2" name="q131">
										<label for="q131-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί αυτοπαθείς αντωνυμίες;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q132-1"  <?=($dr_e['q132']=='1'?'checked':'')?> value="1" name="q132">
										<label for="q132-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q132-2"  <?=($dr_e['q132']=='2'?'checked':'')?>  value="2" name="q132">
										<label for="q132-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί προθέσεις;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q133-1"  <?=($dr_e['q133']=='1'?'checked':'')?> value="1" name="q133">
										<label for="q133-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q133-2"  <?=($dr_e['q133']=='2'?'checked':'')?>  value="2" name="q133">
										<label for="q133-2">Όχι</label>
									</div>
								</div>
							</div>
						</div>
						<div id="w4-3" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί προστακτική;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q134-1"  <?=($dr_e['q134']=='1'?'checked':'')?> value="1" name="q134">
										<label for="q134-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q134-2"  <?=($dr_e['q134']=='2'?'checked':'')?>  value="2" name="q134">
										<label for="q134-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί συγκριτικό βαθμό;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q135-1"  <?=($dr_e['q135']=='1'?'checked':'')?> value="1" name="q135">
										<label for="q135-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q135-2"  <?=($dr_e['q135']=='2'?'checked':'')?>  value="2" name="q135">
										<label for="q135-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Κάνει ελάχιστα γραμματικά λάθη;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q136-1"  <?=($dr_e['q136']=='1'?'checked':'')?> value="1" name="q136">
										<label for="q136-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q136-2"  <?=($dr_e['q136']=='2'?'checked':'')?>  value="2" name="q136">
										<label for="q136-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί σωστά γραμματικά προτάσεις;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q137-1"  <?=($dr_e['q137']=='1'?'checked':'')?> value="1" name="q137">
										<label for="q137-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q137-2"  <?=($dr_e['q137']=='2'?'checked':'')?>  value="2" name="q137">
										<label for="q137-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί γραμματικά πλήρεις προτάσεις;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q138-1"  <?=($dr_e['q138']=='1'?'checked':'')?> value="1" name="q138">
										<label for="q138-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q138-2"  <?=($dr_e['q138']=='2'?'checked':'')?>  value="2" name="q138">
										<label for="q138-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί προτάσεις με σύνθετα γραμματικά χαρακτηριστικά;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q139-1"  <?=($dr_e['q139']=='1'?'checked':'')?> value="1" name="q139">
										<label for="q139-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q139-2"  <?=($dr_e['q139']=='2'?'checked':'')?>  value="2" name="q139">
										<label for="q139-2">Όχι</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί μορφολογικούς κανόνες;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q140-1"  <?=($dr_e['q140']=='1'?'checked':'')?> value="1" name="q140">
										<label for="q140-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q140-2"  <?=($dr_e['q140']=='2'?'checked':'')?>  value="2" name="q140">
										<label for="q140-2">Όχι</label>
									</div>
								</div>
							</div>
						</div>
						
						<div id="w4-4" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Παρουσιάζει προβλήματα στη σύνταξη;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q141-1"  <?=($dr_e['q141']=='1'?'checked':'')?> value="1" name="q141">
										<label for="q141-1">Ναι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q141-2"  <?=($dr_e['q141']=='2'?'checked':'')?>  value="2" name="q141">
										<label for="q141-2">Όχι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q141-3"  <?=($dr_e['q141']=='3'?'checked':'')?>  value="3" name="q141">
										<label for="q141-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q141-4"  <?=($dr_e['q141']=='4'?'checked':'')?>  value="4" name="q141">
										<label for="q141-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Γνωρίζει τη χρήση των: γιατί, ποιος, ποιου και πόσα είναι;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q142-1"  <?=($dr_e['q142']=='1'?'checked':'')?> value="1" name="q142">
										<label for="q142-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q142-2"  <?=($dr_e['q142']=='2'?'checked':'')?>  value="2" name="q142">
										<label for="q142-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q142-3"  <?=($dr_e['q142']=='3'?'checked':'')?>  value="3" name="q142">
										<label for="q142-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q142-4"  <?=($dr_e['q142']=='4'?'checked':'')?>  value="4" name="q142">
										<label for="q142-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Γνωρίζει αντίθετα;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q143-1"  <?=($dr_e['q143']=='1'?'checked':'')?> value="1" name="q143">
										<label for="q143-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q143-2"  <?=($dr_e['q143']=='2'?'checked':'')?>  value="2" name="q143">
										<label for="q143-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q143-3"  <?=($dr_e['q143']=='3'?'checked':'')?>  value="3" name="q143">
										<label for="q143-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q143-4"  <?=($dr_e['q143']=='4'?'checked':'')?>  value="4" name="q143">
										<label for="q143-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Γνωρίζει συνώνυμα;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q144-1"  <?=($dr_e['q144']=='1'?'checked':'')?> value="1" name="q144">
										<label for="q144-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q144-2"  <?=($dr_e['q144']=='2'?'checked':'')?>  value="2" name="q144">
										<label for="q144-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q144-3"  <?=($dr_e['q144']=='3'?'checked':'')?>  value="3" name="q144">
										<label for="q144-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q144-4"  <?=($dr_e['q144']=='4'?'checked':'')?>  value="4" name="q144">
										<label for="q144-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μπορεί να κάνει γενίκευση του λεξιλογίου που μαθαίνει στο σχολείο;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q145-1"  <?=($dr_e['q145']=='1'?'checked':'')?> value="1" name="q145">
										<label for="q145-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q145-2"  <?=($dr_e['q145']=='2'?'checked':'')?>  value="2" name="q145">
										<label for="q145-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q145-3"  <?=($dr_e['q145']=='3'?'checked':'')?>  value="3" name="q145">
										<label for="q145-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Κατανοεί παρομοιώσεις/ μεταφορές;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q146-1"  <?=($dr_e['q146']=='1'?'checked':'')?> value="1" name="q146">
										<label for="q146-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q146-2"  <?=($dr_e['q146']=='2'?'checked':'')?>  value="2" name="q146">
										<label for="q146-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q146-3"  <?=($dr_e['q146']=='3'?'checked':'')?>  value="3" name="q146">
										<label for="q146-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Κατανοεί το χιούμορ;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q147-1"  <?=($dr_e['q147']=='1'?'checked':'')?> value="1" name="q147">
										<label for="q147-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q147-2"  <?=($dr_e['q147']=='2'?'checked':'')?>  value="2" name="q147">
										<label for="q147-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q147-3"  <?=($dr_e['q147']=='3'?'checked':'')?>  value="3" name="q147">
										<label for="q147-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<ul class="pager">
						<li class="previous disabled">
							<a><i class="fas fa-angle-left"></i> Προηγούμενο</a>
						</li>
						<li class="finish hidden float-right">
							<a href="#" onClick="checkFields();">Ολοκλήρωση</a>
						</li>
						<li class="next">
							<a>Επόμενο <i class="fas fa-angle-right"></i></a>
						</li>
					</ul>
				</div>
			</section>
			<div class="form-group row" style="margin-top:20px;">
				<a href="#" onClick="checkFields();"><button type="button" class="btn btn-primary">Αποθήκευση</button></a> 
				<a style="margin-left:10px;" href="index.php?com=children"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
			</div>
		</div>
	</div>
<? } else if($_GET['option']=='7'){?>
	<div class="row">
		<div class="col">
			<section class="card form-wizard" id="w4">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
					</div>
					<h2 class="card-title">Ψυχοκινητική Ανάπτυξη</h2>
				</header>
				<div class="card-body">
					<div class="wizard-progress wizard-progress-lg">
						<div class="steps-progress">
							<div class="progress-indicator"></div>
						</div>
						<ul class="nav wizard-steps">
							<li class="nav-item active">
								<a class="nav-link" href="#w4-1" data-toggle="tab"><span>1</span>Ενότητα 1</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-2" data-toggle="tab"><span>2</span>Ενότητα 2</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-3" data-toggle="tab"><span>3</span>Ενότητα 3</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-4" data-toggle="tab"><span>4</span>Ενότητα 4</a>
							</li>
						</ul>
					</div>
					<input type='hidden' name='myOption' value='7'>
					<div class="tab-content">
						<div id="w4-1" class="tab-pane active">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Όταν ζητάτε από το παιδί να κάνει κάτι (εντολή), αυτό ακολουθεί:</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q148-1" <?=($dr_e['q148']=='1'?'checked':'')?> value='1' name="q148">
										<label for="q148-1">Μόνο απλές εντολές</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q148-2" <?=($dr_e['q148']=='2'?'checked':'')?> value='2' name="q148">
										<label for="q148-2">εντολές με 2-3 μέρη</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q148-3" <?=($dr_e['q148']=='3'?'checked':'')?> value='3' name="q148">
										<label for="q148-3">πολύπλοκες εντολές</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q148-4" <?=($dr_e['q148']=='4'?'checked':'')?> value='4' name="q148">
										<label for="q148-4">δεν ακολουθεί εντολές</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q148-5" <?=($dr_e['q148']=='5'?'checked':'')?> value='5' name="q148">
										<label for="q148-5">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μετράει;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q149-1" <?=($dr_e['q149']=='1'?'checked':'')?> value='1' name="q149">
										<label for="q149-1">μέχρι το 5</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q149-2" <?=($dr_e['q149']=='2'?'checked':'')?> value='2' name="q149">
										<label for="q149-2">μέχρι το 10</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q149-3" <?=($dr_e['q149']=='3'?'checked':'')?> value='3' name="q149">
										<label for="q149-3">μέχρι το 30</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q149-4" <?=($dr_e['q149']=='4'?'checked':'')?> value='4' name="q149">
										<label for="q149-4">Εκατοντάδες</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q149-5" <?=($dr_e['q149']=='5'?'checked':'')?> value='5' name="q149">
										<label for="q149-5">ΌΧΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μπορεί να εντοπίσει την αρχή τη μέση και το τέλος μιας ιστορίας;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q150-1" <?=($dr_e['q150']=='1'?'checked':'')?> value='1' name="q150">
										<label for="q150-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q150-2" <?=($dr_e['q150']=='2'?'checked':'')?> value='2' name="q150">
										<label for="q150-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q150-3" <?=($dr_e['q150']=='3'?'checked':'')?> value='3' name="q150">
										<label for="q150-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q150-4" <?=($dr_e['q150']=='4'?'checked':'')?> value='4' name="q150">
										<label for="q150-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μπορεί να κάνει ταύτιση μιας ιστορίας με εικόνες;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q151-1"  <?=($dr_e['q151']=='1'?'checked':'')?> value="1" name="q151">
										<label for="q151-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q151-2"  <?=($dr_e['q151']=='2'?'checked':'')?>  value="2" name="q151">
										<label for="q151-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q151-3"  <?=($dr_e['q151']=='3'?'checked':'')?>  value="3" name="q151">
										<label for="q151-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Με πόσους κύβους/τουβλάκια φτιάχνει πύργο;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q152-1"  <?=($dr_e['q152']=='1'?'checked':'')?> value="1" name="q152">
										<label for="q152-1">Σύνθετες κατασκευές</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q152-2"  <?=($dr_e['q152']=='2'?'checked':'')?>  value="2" name="q152">
										<label for="q152-2">Πάνω από 9 κύβους/τουβλάκια</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q152-3"  <?=($dr_e['q152']=='3'?'checked':'')?>  value="3" name="q152">
										<label for="q152-3">Περιπου 6 κύβους/τουβλάκια</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q152-4"  <?=($dr_e['q152']=='4'?'checked':'')?>  value="4" name="q152">
										<label for="q152-4">εώς 3 κύβους/τουβλάκια</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q152-5"  <?=($dr_e['q152']=='5'?'checked':'')?>  value="5" name="q152">
										<label for="q152-5">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μπορεί να γυρίζει σελίδες βιβλίου;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q153-1"  <?=($dr_e['q153']=='1'?'checked':'')?> value="1" name="q153">
										<label for="q153-1">Μία κάθε φορά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q153-2"  <?=($dr_e['q153']=='2'?'checked':'')?>  value="2" name="q153">
										<label for="q153-2">Δύο με τρεις κάθε φορά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q153-3"  <?=($dr_e['q153']=='3'?'checked':'')?>  value="3" name="q153">
										<label for="q153-3">Πολλές κάθε φορά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q153-4"  <?=($dr_e['q153']=='4'?'checked':'')?>  value="4" name="q153">
										<label for="q153-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
						</div>
						<div id="w4-2" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Όταν ζωγραφίζει παρατηρείτε κάποια από τα παρακάτω; Επιλέξτε όσα ταιριάζουν καλύτερα.</div>
								<div class="col-lg-9">
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" id="q154-1" <?=(substr($dr_e['q154'],0,1)=='1'?'checked':'')?> name="q154-1">
										<label for="q154-1">Χρωματίζει εκτός των περιθωρίων/γραμμών</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q154-2" <?=(substr($dr_e['q154'],1,1)=='1'?'checked':'')?> name="q154-2">
										<label for="q154-2">Ζωγραφίζει εικόνες που δεν είναι αναλογικές</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q154-3" <?=(substr($dr_e['q154'],2,1)=='1'?'checked':'')?> name="q154-3">
										<label for="q154-3">Χρησιμοποιεί γράψιμο όπως των ενηλίκων, μόνο που είναι αργό και κάνει κόπο</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" id="q154-4" <?=(substr($dr_e['q154'],3,1)=='1'?'checked':'')?> name="q154-4">
										<label for="q154-4">Κάνει ακριβή σχέδια με μολυβι/κηρομπογία</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q154-5" <?=(substr($dr_e['q154'],4,1)=='1'?'checked':'')?> name="q154-5">
										<label for="q154-5">Σφίγγει το μολυβι/κηρομπογία με τον αντίχειρα καιτο μεσαίο δάχτυλο</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q154-6" <?=(substr($dr_e['q154'],5,1)=='1'?'checked':'')?> name="q154-6">
										<label for="q154-6">Κρατώντας το μολυβι/κηρομπογία με τον αντίχειρα και τα δάχτυλα</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q154-7" <?=(substr($dr_e['q154'],6,1)=='1'?'checked':'')?> name="q154-7">
										<label for="q154-7">κινώντας ολόκληρο το χέρι</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q154-8" <?=(substr($dr_e['q154'],7,1)=='1'?'checked':'')?> name="q154-8">
										<label for="q154-8">ΔΕΝ Ζωγραφίζει</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q154-9" <?=(substr($dr_e['q154'],8,1)=='1'?'checked':'')?> name="q154-9">
										<label for="q154-9">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Σχεδιάζει ...</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q155-1"  <?=($dr_e['q155']=='1'?'checked':'')?> value="1" name="q155">
										<label for="q155-1">Σχεδιάζει αναγνωρίσιμο άνθρωπο, δένδρο, σπίτι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q155-2"  <?=($dr_e['q155']=='2'?'checked':'')?>  value="2" name="q155">
										<label for="q155-2">κύκλο, τετράγωνο, οριζόντιες γραμμές, σταυρούς, τρίγωνα και ρόμβους</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q155-3"  <?=($dr_e['q155']=='3'?'checked':'')?>  value="3" name="q155">
										<label for="q155-3">κύκλο, τετράγωνο, οριζόντιες γραμμές</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q155-4"  <?=($dr_e['q155']=='4'?'checked':'')?>  value="4" name="q155">
										<label for="q155-4">Μουτζουρώνει</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q155-5"  <?=($dr_e['q155']=='5'?'checked':'')?>  value="5" name="q155">
										<label for="q155-5">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Όταν το παιδί γράφει (π.χ. το όνομά του) θεωρείτε ότι τα γράμματά του είναι:</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q156-1"  <?=($dr_e['q156']=='1'?'checked':'')?> value="1" name="q156">
										<label for="q156-1">υπερμεγέθη</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q156-2"  <?=($dr_e['q156']=='2'?'checked':'')?>  value="2" name="q156">
										<label for="q156-2">μεγάλα</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q156-3"  <?=($dr_e['q156']=='3'?'checked':'')?>  value="3" name="q156">
										<label for="q156-3">φυσιολογικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q156-4"  <?=($dr_e['q156']=='4'?'checked':'')?>  value="4" name="q156">
										<label for="q156-4">μικρά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q156-5"  <?=($dr_e['q156']=='5'?'checked':'')?>  value="5" name="q156">
										<label for="q156-5">ανάκατα</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q156-6"  <?=($dr_e['q156']=='6'?'checked':'')?>  value="6" name="q156">
										<label for="q156-6">διάσπαρτα</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Προτιμώμενο χέρι κατά τη ζωγραφική/ γραφή ...</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q157-1"  <?=($dr_e['q157']=='1'?'checked':'')?> value="1" name="q157">
										<label for="q157-1">δεξί</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q157-2"  <?=($dr_e['q157']=='2'?'checked':'')?>  value="2" name="q157">
										<label for="q157-2">αριστερό</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q157-3"  <?=($dr_e['q157']=='3'?'checked':'')?>  value="3" name="q157">
										<label for="q157-3">εναλλαγή</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q157-4"  <?=($dr_e['q157']=='4'?'checked':'')?>  value="4" name="q157">
										<label for="q157-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Κατανοεί το αριστερά δεξιά;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q158-1"  <?=($dr_e['q158']=='1'?'checked':'')?> value="1" name="q158">
										<label for="q158-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q158-2"  <?=($dr_e['q158']=='2'?'checked':'')?>  value="2" name="q158">
										<label for="q158-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q158-3"  <?=($dr_e['q158']=='3'?'checked':'')?>  value="3" name="q158">
										<label for="q158-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q158-4"  <?=($dr_e['q158']=='4'?'checked':'')?>  value="4" name="q158">
										<label for="q158-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Δείχνει ξεκάθαρη προτίμηση αριστερού ή δεξιού χεριού, ποδιού ματιού;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q159-1"  <?=($dr_e['q159']=='1'?'checked':'')?> value="1" name="q159">
										<label for="q159-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q159-2"  <?=($dr_e['q159']=='2'?'checked':'')?>  value="2" name="q159">
										<label for="q159-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q159-3"  <?=($dr_e['q159']=='3'?'checked':'')?>  value="3" name="q159">
										<label for="q159-3">εναλλαγή</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Χρησιμοποιεί το ένα συνήθως χέρι για όλες τις δραστηριότητες;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q160-1"  <?=($dr_e['q160']=='1'?'checked':'')?> value="1" name="q160">
										<label for="q160-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q160-2"  <?=($dr_e['q160']=='2'?'checked':'')?>  value="2" name="q160">
										<label for="q160-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q160-3"  <?=($dr_e['q160']=='3'?'checked':'')?>  value="3" name="q160">
										<label for="q160-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Αφήνει αντικείμενα με ακρίβεια;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q161-1"  <?=($dr_e['q161']=='1'?'checked':'')?> value="1" name="q161">
										<label for="q161-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q161-2"  <?=($dr_e['q161']=='2'?'checked':'')?>  value="2" name="q161">
										<label for="q161-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q161-3"  <?=($dr_e['q161']=='3'?'checked':'')?>  value="3" name="q161">
										<label for="q161-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
						</div>
						<div id="w4-3" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Κρατά το χαρτί με το χέρι όταν ζωγραφίζει/ γράφει;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q162-1"  <?=($dr_e['q162']=='1'?'checked':'')?> value="1" name="q162">
										<label for="q162-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q162-2"  <?=($dr_e['q162']=='2'?'checked':'')?>  value="2" name="q162">
										<label for="q162-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q162-3"  <?=($dr_e['q162']=='3'?'checked':'')?>  value="3" name="q162">
										<label for="q162-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μπορεί να διπλώνει το χαρτί στη μέση;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q163-1"  <?=($dr_e['q163']=='1'?'checked':'')?> value="1" name="q163">
										<label for="q163-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q163-2"  <?=($dr_e['q163']=='2'?'checked':'')?>  value="2" name="q163">
										<label for="q163-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q163-3"  <?=($dr_e['q163']=='3'?'checked':'')?>  value="3" name="q163">
										<label for="q163-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q163-4"  <?=($dr_e['q163']=='4'?'checked':'')?>  value="4" name="q163">
										<label for="q163-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr>
							
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Σχετικά με τον ρυθμό της κίνησης ...</div>
								<div class="col-lg-9">
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" id="q164-1" <?=(substr($dr_e['q164'],0,1)=='1'?'checked':'')?> name="q164-1">
										<label for="q164-1">Ακολουθεί ανεπτυγμένους ρυθμούς</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q164-2" <?=(substr($dr_e['q164'],1,1)=='1'?'checked':'')?> name="q164-2">
										<label for="q164-2">Περπατά σε ίσα γραμμή</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q164-3" <?=(substr($dr_e['q164'],2,1)=='1'?'checked':'')?> name="q164-3">
										<label for="q164-3">Χοροπηδά στο άκουσμα της μουσικής</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" id="q164-4" <?=(substr($dr_e['q164'],3,1)=='1'?'checked':'')?> name="q164-4">
										<label for="q164-4">Τρέχει γύρω από εμπόδια</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q164-5" <?=(substr($dr_e['q164'],4,1)=='1'?'checked':'')?> name="q164-5">
										<label for="q164-5">Εναλλάσει τα πόδια όταν ανεβοκατεβαίνει σκαλιά</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q164-6" <?=(substr($dr_e['q164'],5,1)=='1'?'checked':'')?> name="q164-6">
										<label for="q164-6">Τρέχει και παίζει ζωηρά παιχνίδια</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q164-7" <?=(substr($dr_e['q164'],6,1)=='1'?'checked':'')?> name="q164-7">
										<label for="q164-7">Ξεκινά να αναπτύσσει ρυθμό</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q164-8" <?=(substr($dr_e['q164'],7,1)=='1'?'checked':'')?> name="q164-8">
										<label for="q164-8">Δεν έχει ρυθμό ή έχει ακανόνιστο ρυθμό</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q164-9" <?=(substr($dr_e['q164'],8,1)=='1'?'checked':'')?> name="q164-9">
										<label for="q164-9">Κανένα από τα παραπάνω</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Η σχέση του με το ψαλίδι ...</div>
								<div class="col-lg-9">
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" id="q165-1" <?=(substr($dr_e['q165'],0,1)=='1'?'checked':'')?> name="q165-1">
										<label for="q165-1">Σχηματίζει σχήματα κόβοντας με το ψαλίδι</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q165-2" <?=(substr($dr_e['q165'],1,1)=='1'?'checked':'')?> name="q165-2">
										<label for="q165-2">Κόβει με το ψαλίδι πάνω σε ίσια γραμμή</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q165-3" <?=(substr($dr_e['q165'],2,1)=='1'?'checked':'')?> name="q165-3">
										<label for="q165-3">Διασκεδάζει με το κόψιμο και την επικόλληση χαρτιών</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox" id="q165-4" <?=(substr($dr_e['q165'],3,1)=='1'?'checked':'')?> name="q165-4">
										<label for="q165-4">Μαθαίνει να χρησιμοποιεί το παιδικό ψαλίδι</label>
									</div>
									<div class="checkbox-custom checkbox-default">
										<input type="checkbox"  id="q165-5" <?=(substr($dr_e['q165'],4,1)=='1'?'checked':'')?> name="q165-5">
										<label for="q165-5">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Σχετικά με το ντύσιμο ...</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q166-1"  <?=($dr_e['q166']=='1'?'checked':'')?> value="1" name="q166">
										<label for="q166-1">Φοράει μόνο του όλα τα ρούχα του</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q166-2"  <?=($dr_e['q166']=='2'?'checked':'')?>  value="2" name="q166">
										<label for="q166-2">Κουμπώνει τα ρούχα του και δένει τα κορδόνια του</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q166-3"  <?=($dr_e['q166']=='3'?'checked':'')?>  value="3" name="q166">
										<label for="q166-3">Προσπαθεί να φορέσει τα ρούχα του</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q166-4"  <?=($dr_e['q166']=='4'?'checked':'')?>  value="4" name="q166">
										<label for="q166-4">Βάζει παπούτσια όχι απαραίτητα στο σωστό πόδι</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q166-5"  <?=($dr_e['q166']=='5'?'checked':'')?>  value="5" name="q166">
										<label for="q166-5">Ξεντύνεται μόνο του</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q166-6"  <?=($dr_e['q166']=='6'?'checked':'')?>  value="6" name="q166">
										<label for="q166-6">Μη ικανό</label>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Σχετικά με τα χρώματα ...</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q167-1"  <?=($dr_e['q167']=='1'?'checked':'')?> value="1" name="q167">
										<label for="q167-1">Αναγνωρίζει 3 βασικά χρώματα</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q167-2"  <?=($dr_e['q167']=='2'?'checked':'')?>  value="2" name="q167">
										<label for="q167-2">Αναγνωρίζει 6 βασικά χρώματα</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q167-3"  <?=($dr_e['q167']=='3'?'checked':'')?>  value="3" name="q167">
										<label for="q167-3">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q167-4"  <?=($dr_e['q167']=='4'?'checked':'')?>  value="4" name="q167">
										<label for="q167-4">δεν βλέπει / πρόβλημα όρασης</label>
									</div>
								</div>
							</div>
						</div>
						
						<div id="w4-4" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Αναγνωρίζει τα βασικά γεωμετρικά σχήματα;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q168-1"  <?=($dr_e['q168']=='1'?'checked':'')?> value="1" name="q168">
										<label for="q168-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q168-2"  <?=($dr_e['q168']=='2'?'checked':'')?>  value="2" name="q168">
										<label for="q168-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q168-3"  <?=($dr_e['q168']=='3'?'checked':'')?>  value="3" name="q168">
										<label for="q168-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Αντιλαμβάνεται έννοιες μεγέθους και ποσότητας (π.χ. μεγάλο - μικρό, πολύ - λίγο);</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q169-1"  <?=($dr_e['q169']=='1'?'checked':'')?> value="1" name="q169">
										<label for="q169-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q169-2"  <?=($dr_e['q169']=='2'?'checked':'')?>  value="2" name="q169">
										<label for="q169-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q169-3"  <?=($dr_e['q169']=='3'?'checked':'')?>  value="3" name="q169">
										<label for="q169-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Αντιλαμβάνεται έννοιες του χώρου (π.χ. πάνω - κάτω);</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q170-1"  <?=($dr_e['q170']=='1'?'checked':'')?> value="1" name="q170">
										<label for="q170-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q170-2"  <?=($dr_e['q170']=='2'?'checked':'')?>  value="2" name="q170">
										<label for="q170-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q170-3"  <?=($dr_e['q170']=='3'?'checked':'')?>  value="3" name="q170">
										<label for="q170-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Αντιλαμβάνεται έννοιες του χρόνου (π.χ. πρωί - μεσημέρι - βράδυ);</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q171-1"  <?=($dr_e['q171']=='1'?'checked':'')?> value="1" name="q171">
										<label for="q171-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q171-2"  <?=($dr_e['q171']=='2'?'checked':'')?>  value="2" name="q171">
										<label for="q171-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q171-3"  <?=($dr_e['q171']=='3'?'checked':'')?>  value="3" name="q171">
										<label for="q171-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q171-4"  <?=($dr_e['q171']=='4'?'checked':'')?>  value="4" name="q171">
										<label for="q171-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Γνωρίζει και ονοματίζει τα μέρη του σώματος;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q172-1"  <?=($dr_e['q172']=='1'?'checked':'')?> value="1" name="q172">
										<label for="q172-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q172-2"  <?=($dr_e['q172']=='2'?'checked':'')?>  value="2" name="q172">
										<label for="q172-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q172-3"  <?=($dr_e['q172']=='3'?'checked':'')?>  value="3" name="q172">
										<label for="q172-3">τα μπερδεύει</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q172-4"  <?=($dr_e['q172']=='4'?'checked':'')?>  value="4" name="q172">
										<label for="q172-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr>
							
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Όταν το παιδί αντιγράφει θεωρείτε ότι τα γράμματά του είναι:</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q173-1"  <?=($dr_e['q173']=='1'?'checked':'')?> value="1" name="q173">
										<label for="q173-1">υπερμεγέθη</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q173-2"  <?=($dr_e['q173']=='2'?'checked':'')?>  value="2" name="q173">
										<label for="q173-2">μεγάλα</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q173-3"  <?=($dr_e['q173']=='3'?'checked':'')?>  value="3" name="q173">
										<label for="q173-3">φυσιολογικά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q173-4"  <?=($dr_e['q173']=='4'?'checked':'')?>  value="4" name="q173">
										<label for="q173-4">μικρά</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q173-5"  <?=($dr_e['q173']=='5'?'checked':'')?>  value="5" name="q173">
										<label for="q173-5">ανάκατα</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q173-6"  <?=($dr_e['q173']=='6'?'checked':'')?>  value="6" name="q173">
										<label for="q173-6">διάσπαρτα</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<ul class="pager">
						<li class="previous disabled">
							<a><i class="fas fa-angle-left"></i> Προηγούμενο</a>
						</li>
						<li class="finish hidden float-right">
							<a href="#" onClick="checkFields();">Ολοκλήρωση</a>
						</li>
						<li class="next">
							<a>Επόμενο <i class="fas fa-angle-right"></i></a>
						</li>
					</ul>
				</div>
			</section>
			<div class="form-group row" style="margin-top:20px;">
				<a href="#" onClick="checkFields();"><button type="button" class="btn btn-primary">Αποθήκευση</button></a> 
				<a style="margin-left:10px;" href="index.php?com=children"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
			</div>
		</div>
	</div>
<? } else if($_GET['option']=='8'){?>
	<div class="row">
		<div class="col">
			<section class="card form-wizard" id="w4">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
					</div>
					<h2 class="card-title">Ακουστική Κατανόηση</h2>
				</header>
				<div class="card-body">
					<div class="wizard-progress wizard-progress-lg">
						<div class="steps-progress">
							<div class="progress-indicator"></div>
						</div>
						<ul class="nav wizard-steps">
							<li class="nav-item active">
								<a class="nav-link" href="#w4-1" data-toggle="tab"><span>1</span>Ενότητα 1</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-2" data-toggle="tab"><span>2</span>Ενότητα 2</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-3" data-toggle="tab"><span>3</span>Ενότητα 3</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-4" data-toggle="tab"><span>4</span>Ενότητα 4</a>
							</li>
						</ul>
					</div>
					<input type='hidden' name='myOption' value='8'>
					<div class="tab-content">
						<div id="w4-1" class="tab-pane active">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ικανό να χρησιμοποιήσει βλεμματική επαφή για να εντοπίσει έναν ακροατή σε κοινό καθώς εισάγει ένα θέμα</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q174-1" <?=($dr_e['q174']=='1'?'checked':'')?> value='1' name="q174">
										<label for="q174-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q174-2" <?=($dr_e['q174']=='2'?'checked':'')?> value='2' name="q174">
										<label for="q174-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q174-3" <?=($dr_e['q174']=='3'?'checked':'')?> value='3' name="q174">
										<label for="q174-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q174-4" <?=($dr_e['q174']=='4'?'checked':'')?> value='4' name="q174">
										<label for="q174-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Στρέφει το βλέμμα του προς αυτόν που του μιλάει</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q175-1" <?=($dr_e['q175']=='1'?'checked':'')?> value='1' name="q175">
										<label for="q175-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q175-2" <?=($dr_e['q175']=='2'?'checked':'')?> value='2' name="q175">
										<label for="q175-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q175-3" <?=($dr_e['q175']=='3'?'checked':'')?> value='3' name="q175">
										<label for="q175-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q175-4" <?=($dr_e['q175']=='4'?'checked':'')?> value='4' name="q175">
										<label for="q175-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
						</div>
						<div id="w4-2" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μπορεί να επαναλάβει μια ιστορία με δικά του λόγια;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q176-1" <?=($dr_e['q176']=='1'?'checked':'')?> value='1' name="q176">
										<label for="q176-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q176-2" <?=($dr_e['q176']=='2'?'checked':'')?> value='2' name="q176">
										<label for="q176-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q176-3" <?=($dr_e['q176']=='3'?'checked':'')?> value='3' name="q176">
										<label for="q176-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q176-4" <?=($dr_e['q176']=='4'?'checked':'')?> value='4' name="q176">
										<label for="q176-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Ανταποκρίνεται λεκτικά όταν του μιλάνε;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q177-1" <?=($dr_e['q177']=='1'?'checked':'')?> value='1' name="q177">
										<label for="q177-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q177-2" <?=($dr_e['q177']=='2'?'checked':'')?> value='2' name="q177">
										<label for="q177-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q177-3" <?=($dr_e['q177']=='3'?'checked':'')?> value='3' name="q177">
										<label for="q177-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
						</div>
						<div id="w4-3" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Του αρέσει να ακούει ιστορίες/ παραμύθια;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q178-1" <?=($dr_e['q178']=='1'?'checked':'')?> value='1' name="q178">
										<label for="q178-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q178-2" <?=($dr_e['q178']=='2'?'checked':'')?> value='2' name="q178">
										<label for="q178-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q178-3" <?=($dr_e['q178']=='3'?'checked':'')?> value='3' name="q178">
										<label for="q178-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Αναμεταδίδει με λεπτομέρειες μια ιστορία;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q179-1" <?=($dr_e['q179']=='1'?'checked':'')?> value='1' name="q179">
										<label for="q179-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q179-2" <?=($dr_e['q179']=='2'?'checked':'')?> value='2' name="q179">
										<label for="q179-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q179-3" <?=($dr_e['q179']=='3'?'checked':'')?> value='3' name="q179">
										<label for="q179-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
						</div>
						<div id="w4-4" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Του αρέσει να ακούει μουσική;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q180-1" <?=($dr_e['q180']=='1'?'checked':'')?> value='1' name="q180">
										<label for="q180-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q180-2" <?=($dr_e['q180']=='2'?'checked':'')?> value='2' name="q180">
										<label for="q180-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q180-3" <?=($dr_e['q180']=='3'?'checked':'')?> value='3' name="q180">
										<label for="q180-3">ΔΕΝ ΑΚΟΥΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Το λεξιλόγιο που αντιλαμβάνεται το παιδί είναι</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q181-1" <?=($dr_e['q181']=='1'?'checked':'')?> value='1' name="q181">
										<label for="q181-1">έως 30% του λόγου των ενηλίκων</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q181-2" <?=($dr_e['q181']=='2'?'checked':'')?> value='2' name="q181">
										<label for="q181-2">έως 50% του λόγου των ενηλίκων</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q181-3" <?=($dr_e['q181']=='3'?'checked':'')?> value='3' name="q181">
										<label for="q181-3">έως 80% του λόγου των ενηλίκων</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q181-4" <?=($dr_e['q181']=='4'?'checked':'')?> value='4' name="q181">
										<label for="q181-4">περίπου 100% του λόγου των ενηλίκων</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<ul class="pager">
						<li class="previous disabled">
							<a><i class="fas fa-angle-left"></i> Προηγούμενο</a>
						</li>
						<li class="finish hidden float-right">
							<a href="#" onClick="checkFields();">Ολοκλήρωση</a>
						</li>
						<li class="next">
							<a>Επόμενο <i class="fas fa-angle-right"></i></a>
						</li>
					</ul>
				</div>
			</section>
			<div class="form-group row" style="margin-top:20px;">
				<a href="#" onClick="checkFields();"><button type="button" class="btn btn-primary">Αποθήκευση</button></a> 
				<a style="margin-left:10px;" href="index.php?com=children"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
			</div>
		</div>
	</div>
<? } else if($_GET['option']=='9'){?>
	<div class="row">
		<div class="col">
			<section class="card form-wizard" id="w4">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle></a>
						<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>-->
					</div>
					<h2 class="card-title">Μνήμη</h2>
				</header>
				<div class="card-body">
					<div class="wizard-progress wizard-progress-lg">
						<div class="steps-progress">
							<div class="progress-indicator"></div>
						</div>
						<ul class="nav wizard-steps">
							<li class="nav-item active">
								<a class="nav-link" href="#w4-1" data-toggle="tab"><span>1</span>Ενότητα 1</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-2" data-toggle="tab"><span>2</span>Ενότητα 2</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-3" data-toggle="tab"><span>3</span>Ενότητα 3</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#w4-4" data-toggle="tab"><span>4</span>Ενότητα 4</a>
							</li>
						</ul>
					</div>
					<input type='hidden' name='myOption' value='9'>
					<div class="tab-content">
						<div id="w4-1" class="tab-pane active">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Εξιστορεί γεγονότα με χρονολογική σειρά;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q182-1" <?=($dr_e['q182']=='1'?'checked':'')?> value='1' name="q182">
										<label for="q182-1">3 γεγονότα</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q182-2" <?=($dr_e['q182']=='2'?'checked':'')?> value='2' name="q182">
										<label for="q182-2">5 γεγονότα</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q182-3" <?=($dr_e['q182']=='3'?'checked':'')?> value='3' name="q182">
										<label for="q182-3">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q182-4" <?=($dr_e['q182']=='4'?'checked':'')?> value='4' name="q182">
										<label for="q182-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μπορεί να πει το πρόγραμμα της ημέρας του (να κάνει χρονική διαδοχή);</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q183-1" <?=($dr_e['q183']=='1'?'checked':'')?> value='1' name="q183">
										<label for="q183-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q183-2" <?=($dr_e['q183']=='2'?'checked':'')?> value='2' name="q183">
										<label for="q183-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q183-3" <?=($dr_e['q183']=='3'?'checked':'')?> value='3' name="q183">
										<label for="q183-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
						</div>
						<div id="w4-2" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Λέει τις ημέρες της εβδομάδας στη σειρά;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q184-1" <?=($dr_e['q184']=='1'?'checked':'')?> value='1' name="q184">
										<label for="q184-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q184-2" <?=($dr_e['q184']=='2'?'checked':'')?> value='2' name="q184">
										<label for="q184-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q184-3" <?=($dr_e['q184']=='3'?'checked':'')?> value='3' name="q184">
										<label for="q184-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q184-4" <?=($dr_e['q184']=='4'?'checked':'')?> value='4' name="q184">
										<label for="q184-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Λέει τις εποχές με τη σειρά;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q185-1" <?=($dr_e['q185']=='1'?'checked':'')?> value='1' name="q185">
										<label for="q185-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q185-2" <?=($dr_e['q185']=='2'?'checked':'')?> value='2' name="q185">
										<label for="q185-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q185-3" <?=($dr_e['q185']=='3'?'checked':'')?> value='3' name="q185">
										<label for="q185-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q185-4" <?=($dr_e['q185']=='4'?'checked':'')?> value='4' name="q185">
										<label for="q185-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
						</div>
						<div id="w4-3" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Λέει τους μήνες στη σειρά;</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q186-1" <?=($dr_e['q186']=='1'?'checked':'')?> value='1' name="q186">
										<label for="q186-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q186-2" <?=($dr_e['q186']=='2'?'checked':'')?> value='2' name="q186">
										<label for="q186-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q186-3" <?=($dr_e['q186']=='3'?'checked':'')?> value='3' name="q186">
										<label for="q186-3">ΜΕΡΙΚΕΣ ΦΟΡΕΣ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q186-4" <?=($dr_e['q186']=='4'?'checked':'')?> value='4' name="q186">
										<label for="q186-4">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Συγκράτηση πληροφοριών</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q187-1" <?=($dr_e['q187']=='1'?'checked':'')?> value='1' name="q187">
										<label for="q187-1">Καμία</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q187-2" <?=($dr_e['q187']=='2'?'checked':'')?> value='2' name="q187">
										<label for="q187-2">Από 1-3</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q187-3" <?=($dr_e['q187']=='3'?'checked':'')?> value='3' name="q187">
										<label for="q187-3">από 3-5</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q187-4" <?=($dr_e['q187']=='4'?'checked':'')?> value='4' name="q187">
										<label for="q187-4">από 5-7</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q187-5" <?=($dr_e['q187']=='5'?'checked':'')?> value='5' name="q187">
										<label for="q187-5">πάνω από 7</label>
									</div>
								</div>
							</div>
						</div>
						<div id="w4-4" class="tab-pane">
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μπορεί να συγκρατεί πληροφορίες για εκείνη τη στιγμή (βραχυπρόθεσμα);</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q188-1" <?=($dr_e['q188']=='1'?'checked':'')?> value='1' name="q188">
										<label for="q188-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q188-2" <?=($dr_e['q188']=='2'?'checked':'')?> value='2' name="q188">
										<label for="q188-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q188-3" <?=($dr_e['q188']=='3'?'checked':'')?> value='3' name="q188">
										<label for="q188-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
							<hr/>
							<div class="form-group row">
								<div class="col-sm-3 control-label text-sm-right pt-1">Μπορεί να συγκρατεί πληροφορίες για πολύ διάστημα (μακροπρόθεσμα);</div>
								<div class="col-lg-9">
									<div class="radio-custom radio-primary">
										<input type="radio" id="q189-1" <?=($dr_e['q189']=='1'?'checked':'')?> value='1' name="q189">
										<label for="q189-1">ΝΑΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q189-2" <?=($dr_e['q189']=='2'?'checked':'')?> value='2' name="q189">
										<label for="q189-2">ΌΧΙ</label>
									</div>
									<div class="radio-custom radio-primary">
										<input type="radio" id="q189-3" <?=($dr_e['q189']=='3'?'checked':'')?> value='3' name="q189">
										<label for="q189-3">ΔΕΝ ΤΑΙΡΙΑΖΕΙ</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<ul class="pager">
						<li class="previous disabled">
							<a><i class="fas fa-angle-left"></i> Προηγούμενο</a>
						</li>
						<li class="finish hidden float-right">
							<a href="#" onClick="checkFields();">Ολοκλήρωση</a>
						</li>
						<li class="next">
							<a>Επόμενο <i class="fas fa-angle-right"></i></a>
						</li>
					</ul>
				</div>
			</section>
			<div class="form-group row" style="margin-top:20px;">
				<a href="#" onClick="checkFields();"><button type="button" class="btn btn-primary">Αποθήκευση</button></a> 
				<a style="margin-left:10px;" href="index.php?com=children"><button type="button" class="btn btn-primary">Επιστροφή</button></a>
			</div>
		</div>
	</div>
<? } ?>

	<script>
		//document.getElementById("submitBtn").disabled = true;
		function checkFields(){
		var nickname = $('#nickname').val();
		var sex = $('#sex').val();
		var birthdate = $('#birthdate').val();
		var city = $('#city').val();
		var region = $('#region').val();
		var school = $('#school').val();
		var schoolclass = $('#schoolclass').val();
		var schoolclassid = $('#schoolclassid').val();
		//var email = $('#email').val();
		//var user_name = $('#user_name').val();
		//var user_password = $('#user_password').val();
			//if ( nickname.length >= 2 && sex.length >= 1 && birthdate.length >= 1 && city.length >= 1 && region.length >= 1 && schoolclass.length >= 1 && schoolclassid.length >= 1){ //&& user_name.length >= 5 && user_password.length >= 5
					cm('SAVE',1,0,'');//document.getElementById("submitBtn").disabled = false;
			//} //else {
				//document.getElementById("submitBtn").disabled = true;
				//alert('2 chars');
			//}
		}
	</script>  
	<?
} else 	{
	?>    
	
	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
						<!-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>-->
					</div>

					<h2 class="card-title"><?=$nav?></h2>
					<div style="margin-top:20px;">Εισάγετε τα γενικά στοιχεία για κάθε παιδί που θα παίξει το παιχνίδι και συμπληρώστε όλα τα σχετικά ερωτηματολόγια. </div>
				</header>
				<div class="card-body">
					<table class="table table-responsive-md mb-0">
						<thead>
							<tr>
								<!-- <th>#</th>-->
								<th>ID</th>
								<th>Υποκοριστικό</th>
								<th>Φύλο</th>
								<th>Ημερομηνία γέννησης</th>
								<th>Ημερομηνία εισαγωγής</th>
								<th>Ερωτηματολόγιο</th>
								<th>Ενέργεια</th>
							</tr>
						</thead>
						<tbody>
							<?	
								//$filter=" AND user_id=".$auth->UserRow['user_id'];
								//$filter="";
								$filter = ($auth->UserType != "Administrator"? " AND user_id=".$auth->UserRow['user_id']:"");
								$query = "SELECT * FROM children WHERE 1=1 ".$filter." ORDER BY date_insert DESC ";
								$result = $db->sql_query($query);
								$counter = 0;
								while ($dr = $db->sql_fetchrow($result))
								{
									?>
										<tr>
											<td><?=$dr["children_id"]?></td>
											<td><?=$dr["nickname"]?></td>
											<td><?=($dr["sex"]==1?'Αρρεν':($dr["sex"]==2?'Θήλυ':''))?></td>
											<td><?=$dr["birthdate"]?></td>
											<td><?=$dr["date_insert"]?></td>
											<td>
											<?
											$arr=kidstatus($dr["children_id"]);
											//print_r($arr);
											?>
												<select class="form-control" name='editOption<?=$dr["children_id"]?>' id='editOption<?=$dr["children_id"]?>'  onchange="changeURL(<?=$dr["children_id"]?>)">
													<option>Επιλογή</option>
													<option value="0" style="color:<?=($arr[1]==1?'#000':'#000')?>">Γενικά στοιχεία</option>
													<option value="1" style="color:<?=($arr[2]==1?'#000':'#ff0000')?>">Δημογραφικά στοιχεία</option>
													<option value="2" style="color:<?=($arr[3]==1?'#000':'#ff0000')?>">Λεκτικές επικοινωνιακές εκφάνσεις</option>
													<option value="3" style="color:<?=($arr[4]==1?'#000':'#ff0000')?>">Μη λεκτικές επικοινωνιακές εκφάνσεις</option>
													<option value="4" style="color:<?=($arr[5]==1?'#000':'#ff0000')?>">Στοματοπροσωπικός Έλεγχος/Ποιοτικά Στοιχεία Ομιλίας</option>
													<option value="5" style="color:<?=($arr[6]==1?'#000':'#ff0000')?>">Άρθρωση/ Φωνολογία</option>
													<option value="6" style="color:<?=($arr[7]==1?'#000':'#ff0000')?>">Έκφραση της Γλώσσας</option>
													<option value="7" style="color:<?=($arr[8]==1?'#000':'#ff0000')?>">Ψυχοκινητική Ανάπτυξη</option>
													<option value="8" style="color:<?=($arr[9]==1?'#000':'#ff0000')?>">Ακουστική Κατανόηση</option>
													<option value="9" style="color:<?=($arr[10]==1?'#000':'#ff0000')?>">Μνήμη</option>
												</select>
											</td>
											<td>
												<!-- <a style="padding:4px"  href="index.php?com=children&Command=edit&item=<?=$dr["children_id"]?>"><i class="fas fa-edit"></i> Επεξεργασία</a>-->
												<a style="padding:4px"  href="#" id="editLink<?=$dr["children_id"]?>"><i class="fas fa-edit"></i> Επεξεργασία</a>
												<a href="#" onclick="ConfirmDelete('Επιβεβαίωση διαγραφής','index.php?com=children&Command=DELETE&item=<?=$dr["children_id"]?>');"><span><i class="fas fa-trash"></i> Διαγραφή</a></span></a>
											</td>
										</tr>
									<?
								}
								$db->sql_freeresult($result);
							?>
						</tbody>
					</table>
					<hr/>
					<a href="index.php?com=children&option=0&item="><button type="button" class="btn btn-primary">Νέα εγγραφή</button></a> 
				</div>
			</section>
		</div>
	</div>


			
<? } ?> 
<script>
function changeURL(id,option){
	var url = new URL('https://get.smartspeech.eu/index.php?com=children&Command=edit&item=' + id + '&option=0');
	var search_params = url.searchParams;

	var e = document.getElementById("editOption"+id);
	var optionValue = e.options[e.selectedIndex].value;
	search_params.set('option', optionValue);

	// change the search property of the main url
	url.search = search_params.toString();

	// the new url string
	var new_url = url.toString();

	// output : http://demourl.com/path?id=101&topic=main
	console.log(new_url);
    document.getElementById("editLink"+id).href=new_url; 
    return false;
}
  

  
</script>
<?
	function kidstatus($id){
		global $db, $auth, $components;
		$rowCompleted=$db->RowSelectorQuery("SELECT * FROM children WHERE children_id=".$id);

		//bin values
		$languages=$rowCompleted['languages'];
		$q3=$rowCompleted['q3'];
		$q4=$rowCompleted['q4'];
		$q72=$rowCompleted['q72'];
		$q76=$rowCompleted['q76'];
		$q88=$rowCompleted['q88'];
		$q154=$rowCompleted['q154'];
		$q164=$rowCompleted['q154']; 
		$q165=$rowCompleted['q154'];
		
		
		$completed1=$completed1+($rowComp["nickname"]!=''?1:0); //1	
		$completed1=$completed1+($rowCompleted["sex"]!=''?1:0); //2
		$completed1=$completed1+($rowCompleted["weight"]!=''?1:0); //3
		$completed1=$completed1+($rowCompleted["height"]!=''?1:0); //4
		$completed1=$completed1+($rowCompleted["birthdate"]!=''?1:0); //5
		$completed1=$completed1+($rowCompleted["nativelang"]!=''?1:0); //6
		$completed1=$completed1+($rowCompleted["city"]!=''?1:0); //7
		$completed1=$completed1+($rowCompleted["region"]!=''?1:0); //8
		$completed1=$completed1+($rowCompleted["school"]!=''?1:0); //9
		$completed1=$completed1+($rowCompleted["schoolclass"]!=''?1:0); //10
		$completed1=$completed1+($rowCompleted["schoolclassid"]!=''?1:0); //11
		$completed1=$completed1+($rowCompleted["custody"]!=''?1:0); //12
		$completed1=$completed1+($rowCompleted["custody"]!=''?1:0); //13	
		$completed1=$completed1+(intval($rowCompleted["custodyagree"])!='1'?0:1); //14
		//$completed1=$completed1+(bindec($languages)>0?1:0); //4
		//$completed1=$completed1+($rowCompleted["otherlang"]!=''?1:0); //6
		
		


		$completed2=$completed2+(intval($rowCompleted["q1"])>0?1:0); //1	
		$completed2=$completed2+(intval($rowCompleted["q2"])>0?1:0); //2
		$completed2=$completed2+(bindec($q3)>0?1:0); //3
		$completed2=$completed2+(bindec($q4)>0?1:0); //4
		$completed2=$completed2+(intval($rowCompleted["q5"])>0?1:0); //5
		$completed2=$completed2+(intval($rowCompleted["q6"])>0?1:0); //6

		$completed3=$completed3+(intval($rowCompleted["q7"])>0?1:0); //1
		$completed3=$completed3+(intval($rowCompleted["q8"])>0?1:0); //2
		$completed3=$completed3+(intval($rowCompleted["q9"])>0?1:0); //3
		$completed3=$completed3+(intval($rowCompleted["q10"])>0?1:0); //4
		$completed3=$completed3+(intval($rowCompleted["q11"])>0?1:0); //5
		$completed3=$completed3+(intval($rowCompleted["q12"])>0?1:0); //6
		$completed3=$completed3+(intval($rowCompleted["q13"])>0?1:0); //7
		$completed3=$completed3+(intval($rowCompleted["q14"])>0?1:0); //8
		$completed3=$completed3+(intval($rowCompleted["q15"])>0?1:0); //9
		$completed3=$completed3+(intval($rowCompleted["q16"])>0?1:0); //10
		$completed3=$completed3+(intval($rowCompleted["q17"])>0?1:0); //11
		$completed3=$completed3+(intval($rowCompleted["q18"])>0?1:0); //12
		$completed3=$completed3+(intval($rowCompleted["q19"])>0?1:0); //13
		$completed3=$completed3+(intval($rowCompleted["q20"])>0?1:0); //14
		$completed3=$completed3+(intval($rowCompleted["q21"])>0?1:0); //15
		$completed3=$completed3+(intval($rowCompleted["q22"])>0?1:0); //16
		$completed3=$completed3+(intval($rowCompleted["q23"])>0?1:0); //17
		$completed3=$completed3+(intval($rowCompleted["q24"])>0?1:0); //18
		$completed3=$completed3+(intval($rowCompleted["q25"])>0?1:0); //19
		$completed3=$completed3+(intval($rowCompleted["q26"])>0?1:0); //20
		$completed3=$completed3+(intval($rowCompleted["q27"])>0?1:0); //21
		$completed3=$completed3+(intval($rowCompleted["q28"])>0?1:0); //22
		$completed3=$completed3+(intval($rowCompleted["q29"])>0?1:0); //23
		$completed3=$completed3+(intval($rowCompleted["q30"])>0?1:0); //24
		$completed3=$completed3+(intval($rowCompleted["q31"])>0?1:0); //25
		$completed3=$completed3+(intval($rowCompleted["q32"])>0?1:0); //26
		$completed3=$completed3+(intval($rowCompleted["q33"])>0?1:0); //27
		$completed3=$completed3+(intval($rowCompleted["q34"])>0?1:0); //28
		$completed3=$completed3+(intval($rowCompleted["q35"])>0?1:0); //29
		$completed3=$completed3+(intval($rowCompleted["q36"])>0?1:0); //30
		$completed3=$completed3+(intval($rowCompleted["q37"])>0?1:0); //31
		$completed3=$completed3+(intval($rowCompleted["q38"])>0?1:0); //32
		$completed3=$completed3+(intval($rowCompleted["q39"])>0?1:0); //33
		$completed3=$completed3+(intval($rowCompleted["q40"])>0?1:0); //34
		$completed3=$completed3+(intval($rowCompleted["q41"])>0?1:0); //35
		$completed3=$completed3+(intval($rowCompleted["q42"])>0?1:0); //36
		$completed3=$completed3+(intval($rowCompleted["q43"])>0?1:0); //37
		$completed3=$completed3+(intval($rowCompleted["q44"])>0?1:0); //38
		$completed3=$completed3+(intval($rowCompleted["q45"])>0?1:0); //39
		$completed3=$completed3+(intval($rowCompleted["q46"])>0?1:0); //40
		$completed3=$completed3+(intval($rowCompleted["q47"])>0?1:0); //41
		$completed3=$completed3+(intval($rowCompleted["q48"])>0?1:0); //42
		
		$completed4=$completed4+(intval($rowCompleted["q49"])>0?1:0); //1
		$completed4=$completed4+(intval($rowCompleted["q50"])>0?1:0); //2
		$completed4=$completed4+(intval($rowCompleted["q51"])>0?1:0); //3
		$completed4=$completed4+(intval($rowCompleted["q52"])>0?1:0); //4
		$completed4=$completed4+(intval($rowCompleted["q53"])>0?1:0); //5
		$completed4=$completed4+(intval($rowCompleted["q54"])>0?1:0); //6
		$completed4=$completed4+(intval($rowCompleted["q55"])>0?1:0); //7
		$completed4=$completed4+(intval($rowCompleted["q56"])>0?1:0); //8
		$completed4=$completed4+(intval($rowCompleted["q57"])>0?1:0); //9
		$completed4=$completed4+(intval($rowCompleted["q58"])>0?1:0); //10
		$completed4=$completed4+(intval($rowCompleted["q59"])>0?1:0); //11
		$completed4=$completed4+(intval($rowCompleted["q60"])>0?1:0); //12
		$completed4=$completed4+(intval($rowCompleted["q61"])>0?1:0); //13
		$completed4=$completed4+(intval($rowCompleted["q62"])>0?1:0); //14
		$completed4=$completed4+(intval($rowCompleted["q63"])>0?1:0); //15
		$completed4=$completed4+(intval($rowCompleted["q64"])>0?1:0); //16
		$completed4=$completed4+(intval($rowCompleted["q65"])>0?1:0); //17
		$completed4=$completed4+(intval($rowCompleted["q66"])>0?1:0); //18
		$completed4=$completed4+(intval($rowCompleted["q67"])>0?1:0); //19
		$completed4=$completed4+(intval($rowCompleted["q68"])>0?1:0); //20
		$completed4=$completed4+(intval($rowCompleted["q190"])>0?1:0); //21
		$completed4=$completed4+(intval($rowCompleted["q191"])>0?1:0); //22
		$completed4=$completed4+(intval($rowCompleted["q192"])>0?1:0); //23
		$completed4=$completed4+(intval($rowCompleted["q69"])>0?1:0); //24
		$completed4=$completed4+(intval($rowCompleted["q70"])>0?1:0); //25
		$completed4=$completed4+(intval($rowCompleted["q71"])>0?1:0); //26
		$completed4=$completed4+(intval($rowCompleted["q193"])>0?1:0); //27
		$completed4=$completed4+(bindec($q72)>0?1:0); //28
		$completed4=$completed4+(intval($rowCompleted["q73"])>0?1:0); //29
		$completed4=$completed4+(intval($rowCompleted["q74"])>0?1:0); //30
		$completed4=$completed4+(intval($rowCompleted["q75"])>0?1:0); //31
		$completed4=$completed4+(bindec($q76)>0?1:0); //32
		$completed4=$completed4+(intval($rowCompleted["q77"])>0?1:0); //33
		$completed4=$completed4+(intval($rowCompleted["q78"])>0?1:0); //34

		
		$completed5=$completed5+(intval($rowCompleted["q79"])>0?1:0); //1
		$completed5=$completed5+(intval($rowCompleted["q80"])>0?1:0); //2
		$completed5=$completed5+(intval($rowCompleted["q81"])>0?1:0); //3
		$completed5=$completed5+(intval($rowCompleted["q82"])>0?1:0); //4
		$completed5=$completed5+(intval($rowCompleted["q83"])>0?1:0); //5
		$completed5=$completed5+(intval($rowCompleted["q84"])>0?1:0); //6
		$completed5=$completed5+(intval($rowCompleted["q85"])>0?1:0); //7
		$completed5=$completed5+(intval($rowCompleted["q86"])>0?1:0); //8
		$completed5=$completed5+(intval($rowCompleted["q87"])>0?1:0); //9
		$completed5=$completed5+(bindec($q88)>0?1:0); //10

		
		$completed6=$completed6+(intval($rowCompleted["q89"])>0?1:0); //1	
		$completed6=$completed6+(intval($rowCompleted["q90"])>0?1:0); //2
		$completed6=$completed6+(intval($rowCompleted["q91"])>0?1:0); //3	
		$completed6=$completed6+(intval($rowCompleted["q92"])>0?1:0); //4
		$completed6=$completed6+(intval($rowCompleted["q93"])>0?1:0); //5
		$completed6=$completed6+(intval($rowCompleted["q94"])>0?1:0); //6
		$completed6=$completed6+(intval($rowCompleted["q94"])>0?1:0); //7
		$completed6=$completed6+(intval($rowCompleted["q96"])>0?1:0); //8
		$completed6=$completed6+(intval($rowCompleted["q97"])>0?1:0); //9
		$completed6=$completed6+(intval($rowCompleted["q98"])>0?1:0); //10
		$completed6=$completed6+(intval($rowCompleted["q99"])>0?1:0); //11	
		$completed6=$completed6+(intval($rowCompleted["q100"])>0?1:0); //12
		$completed6=$completed6+(intval($rowCompleted["q101"])>0?1:0); //13
		$completed6=$completed6+(intval($rowCompleted["q102"])>0?1:0); //14
		$completed6=$completed6+(intval($rowCompleted["q103"])>0?1:0); //15
		$completed6=$completed6+(intval($rowCompleted["q104"])>0?1:0); //16
		$completed6=$completed6+(intval($rowCompleted["q105"])>0?1:0); //17
		$completed6=$completed6+(intval($rowCompleted["q106"])>0?1:0); //18
		$completed6=$completed6+(intval($rowCompleted["q107"])>0?1:0); //19
		$completed6=$completed6+(intval($rowCompleted["q108"])>0?1:0); //20
		$completed6=$completed6+(intval($rowCompleted["q109"])>0?1:0); //21
		$completed6=$completed6+(intval($rowCompleted["q110"])>0?1:0); //22
		$completed6=$completed6+(intval($rowCompleted["q111"])>0?1:0); //23
		$completed6=$completed6+(intval($rowCompleted["q112"])>0?1:0); //24
		$completed6=$completed6+(intval($rowCompleted["q113"])>0?1:0); //25
		$completed6=$completed6+(intval($rowCompleted["q114"])>0?1:0); //26
		$completed6=$completed6+(intval($rowCompleted["q115"])>0?1:0); //27
		$completed6=$completed6+(intval($rowCompleted["q116"])>0?1:0); //28	
		$completed6=$completed6+(intval($rowCompleted["q117"])>0?1:0); //29	
		$completed6=$completed6+(intval($rowCompleted["q118"])?1:0); //30			

		$completed7=$completed7+(intval($rowCompleted["q119"])>0?1:0); //1
		$completed7=$completed7+(intval($rowCompleted["q120"])>0?1:0); //2
		$completed7=$completed7+(intval($rowCompleted["q121"])>0?1:0); //3
		$completed7=$completed7+(intval($rowCompleted["q122"])>0?1:0); //4
		$completed7=$completed7+(intval($rowCompleted["q123"])>0?1:0); //5
		$completed7=$completed7+(intval($rowCompleted["q124"])>0?1:0); //6
		$completed7=$completed7+(intval($rowCompleted["q125"])>0?1:0); //7
		$completed7=$completed7+(intval($rowCompleted["q126"])>0?1:0); //8
		$completed7=$completed7+(intval($rowCompleted["q127"])>0?1:0); //9
		$completed7=$completed7+(intval($rowCompleted["q128"])>0?1:0); //10
		$completed7=$completed7+(intval($rowCompleted["q129"])>0?1:0); //11
		$completed7=$completed7+(intval($rowCompleted["q130"])>0?1:0); //12
		$completed7=$completed7+(intval($rowCompleted["q131"])>0?1:0); //13
		$completed7=$completed7+(intval($rowCompleted["q132"])>0?1:0); //14	
		$completed7=$completed7+(intval($rowCompleted["q133"])>0?1:0); //15
		$completed7=$completed7+(intval($rowCompleted["q134"])>0?1:0); //16
		$completed7=$completed7+(intval($rowCompleted["q135"])>0?1:0); //17
		$completed7=$completed7+(intval($rowCompleted["q136"])>0?1:0); //18
		$completed7=$completed7+(intval($rowCompleted["q137"])>0?1:0); //19
		$completed7=$completed7+(intval($rowCompleted["q138"])>0?1:0); //20
		$completed7=$completed7+(intval($rowCompleted["q139"])>0?1:0); //21
		$completed7=$completed7+(intval($rowCompleted["q140"])>0?1:0); //22
		$completed7=$completed7+(intval($rowCompleted["q141"])>0?1:0); //23
		$completed7=$completed7+(intval($rowCompleted["q142"])>0?1:0); //24
		$completed7=$completed7+(intval($rowCompleted["q143"])>0?1:0); //25
		$completed7=$completed7+(intval($rowCompleted["q144"])>0?1:0); //26
		$completed7=$completed7+(intval($rowCompleted["q145"])>0?1:0); //27
		$completed7=$completed7+(intval($rowCompleted["q146"])>0?1:0); //28
		$completed7=$completed7+(intval($rowCompleted["q147"])>0?1:0); //29		

		$completed8=$completed8+(intval($rowCompleted["q148"])>0?1:0); //1	
		$completed8=$completed8+(intval($rowCompleted["q149"])>0?1:0); //2
		$completed8=$completed8+(intval($rowCompleted["q150"])>0?1:0); //3
		$completed8=$completed8+(intval($rowCompleted["q151"])>0?1:0); //4
		$completed8=$completed8+(intval($rowCompleted["q152"])>0?1:0); //5
		$completed8=$completed8+(intval($rowCompleted["q153"])>0?1:0); //6
		$completed8=$completed8+(bindec($q154)>0?1:0); //7
		$completed8=$completed8+(intval($rowCompleted["q155"])>0?1:0); //8
		$completed8=$completed8+(intval($rowCompleted["q156"])>0?1:0); //9
		$completed8=$completed8+(intval($rowCompleted["q157"])>0?1:0); //10
		$completed8=$completed8+(intval($rowCompleted["q158"])>0?1:0); //11
		$completed8=$completed8+(intval($rowCompleted["q159"])>0?1:0); //12
		$completed8=$completed8+(intval($rowCompleted["q160"])>0?1:0); //13
		$completed8=$completed8+(intval($rowCompleted["q161"])>0?1:0); //14
		$completed8=$completed8+(intval($rowCompleted["q162"])>0?1:0); //15
		$completed8=$completed8+(intval($rowCompleted["q163"])>0?1:0); //16
		$completed8=$completed8+(bindec($q164)>0?1:0); //17
		$completed8=$completed8+(bindec($q165)>0?1:0); //18
		$completed8=$completed8+(intval($rowCompleted["q166"])>0?1:0); //19
		$completed8=$completed8+(intval($rowCompleted["q167"])>0?1:0); //20
		$completed8=$completed8+(intval($rowCompleted["q168"])>0?1:0); //21
		$completed8=$completed8+(intval($rowCompleted["q169"])>0?1:0); //22
		$completed8=$completed8+(intval($rowCompleted["q170"])>0?1:0); //23
		$completed8=$completed8+(intval($rowCompleted["q171"])>0?1:0); //24
		$completed8=$completed8+(intval($rowCompleted["q172"])>0?1:0); //25
		$completed8=$completed8+(intval($rowCompleted["q173"])>0?1:0); //26	

		$completed9=$completed9+(intval($rowCompleted["q174"])>0?1:0); //1
		$completed9=$completed9+(intval($rowCompleted["q175"])>0?1:0); //2
		$completed9=$completed9+(intval($rowCompleted["q176"])>0?1:0); //3
		$completed9=$completed9+(intval($rowCompleted["q177"])>0?1:0); //4
		$completed9=$completed9+(intval($rowCompleted["q178"])>0?1:0); //5
		$completed9=$completed9+(intval($rowCompleted["q179"])>0?1:0); //6
		$completed9=$completed9+(intval($rowCompleted["q180"])>0?1:0); //7
		$completed9=$completed9+(intval($rowCompleted["q181"])>0?1:0); //8

		$completed10=$completed10+(intval($rowCompleted["q182"])>0?1:0); //1
		$completed10=$completed10+(intval($rowCompleted["q183"])>0!=''?1:0); //2
		$completed10=$completed10+(intval($rowCompleted["q184"])>0?1:0); //3
		$completed10=$completed10+(intval($rowCompleted["q185"])>0?1:0); //4
		$completed10=$completed10+(intval($rowCompleted["q186"])>0?1:0); //5
		$completed10=$completed10+(intval($rowCompleted["q187"])>0?1:0); //6
		$completed10=$completed10+(intval($rowCompleted["q188"])>0?1:0); //7
		$completed10=$completed10+(intval($rowCompleted["q189"])>0?1:0); //8	
		
		$completed[1]=($completed1==14?1:0);
		$completed[2]=($completed2==6?1:0);
		$completed[3]=($completed3==42?1:0);
		$completed[4]=($completed4==34?1:0);
		$completed[5]=($completed5==10?1:0);
		$completed[6]=($completed6==30?1:0);
		$completed[7]=($completed7==29?1:0);
		$completed[8]=($completed8==26?1:0);
		$completed[9]=($completed9==8?1:0);
		$completed[10]=($completed10==8?1:0);
		return $completed;
		/*
			1=13
			2=6
			3=42
			4=34
			5=40		
			6=30
			7=29
			8=26
			9=8
			10=8
		*/	
	}
?>