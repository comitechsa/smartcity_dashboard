<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?

	


//login, logout, lock checking here

//if ( isset($_SESSION["userprofile"]) ) {
//	LoginSocial($_SESSION["userprofile"]['id'],$_SESSION["userprofile"]['email'],$_SESSION["userprofile"]['name'], "Register");
//	//echo ($_SESSION["userprofile"]['id']."-".$_SESSION["userprofile"]['email']."-".$_SESSION["userprofile"]['name']."-". "Register");
//}
//Redirect("index.php?com=locked");

if(isset($_GET["logout"]) && $_GET["logout"] == "true")
{
	Logout();
	Redirect(CreateUrl());
}
/*
if($auth->UserType != "" && isset($_GET["com"]) && $_GET["com"] == "locked")
{
	$_SESSION["locked_id"] = $auth->UserRow["user_id"];
	$_SESSION["user_photo"] = $auth->UserRow["user_photo"];
	$_SESSION["locked_user"] = $auth->UserRow["user_name"];
	$_SESSION["locked_user_fullname"] = $auth->UserRow["user_fullname"];
	Logout();
	Redirect("index.php?com=locked");
}
*/
//$resLogin = (Login('jordan.air@gmail.com','12345','Register'));
$resLogin = (Login('system@drama.gr','12345','Register'));
	//if($auth->UserType != "")
	//{

		if(isset($_GET["com"]) && ($_GET["com"] != "")){
			include("template_main.php");
		} else {
			include("template_index.php");
		}
		
	//}


	//else
	//{
	//	include(dirname(__FILE__) . "/templates/public/login.php");
	//}
?>