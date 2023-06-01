<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
	$usersComponentManage = array(
	"DECLARETION" => array (
			"UNIQUEID" => "users"
			,"HEADER" => users_users
			,"TABLE" => "users"
			,"PRIMARY_KEY_COLUMN" => "user_id"
		)
	,"SCHEMA" => array (
			"is_valid" => array 
					(
						"FIELD_TYPE" => "SIMPLE" 
						,"TITLE" => users_isValid
						,"REQUIRE" => 0 
						,"DEFAULT_VALUE" => "True" 
						,"HTML_RENDER" => "CHECKBOX"
						,"DB_TYPE" => "STRING"
						,"CHECKBOX_KEYS" => array("True","False")
						,"DISPLAY_GRID_WIDTH" => "5%"
					)
			,"user_auth" => array 
					(
						"FIELD_TYPE" => "DISPLAY" 
						,"TITLE" =>users_userAuth
						,"DEFAULT_INSERT_VALUE" => "Register"
						,"DB_TYPE" => "STRING"
					)
			,"user_fullname" => array 
					(
						"FIELD_TYPE" => "SIMPLE" 
						,"TITLE" => users_fullname
						,"REQUIRE" => 1
						,"DEFAULT_VALUE" => "" 
						,"HTML_RENDER" => "SINGLELINE"
						,"DB_TYPE" => "STRING"
						,"MAXLENGTH" => "255"
						,"DISPLAY_GRID_WIDTH" => "30%"
					)
			,"email" => array 
					(
						"FIELD_TYPE" => "SIMPLE" 
						,"TITLE" => users_email
						,"REQUIRE" => 1
						,"DEFAULT_VALUE" => "" 
						,"HTML_RENDER" => "SINGLELINE"
						,"DB_TYPE" => "STRING"
						,"MAXLENGTH" => "255"
						,"DISPLAY_GRID_WIDTH" => "15%"
					)
			,"user_name" => array 
					(
						"FIELD_TYPE" => "SIMPLE" 
						,"TITLE" => users_userName
						,"REQUIRE" => 1
						,"DEFAULT_VALUE" => "" 
						,"HTML_RENDER" => "SINGLELINE"
						,"DB_TYPE" => "STRING"
						,"MAXLENGTH" => "100"
						,"REQUIRE_ERROR_MESSAGE" => ""
						,"DISPLAY_GRID_WIDTH" => "15%"
					)
			,"user_password" => array 
					(
						"FIELD_TYPE" => "SIMPLE" 
						,"TITLE" => users_userPassword
						,"REQUIRE" => 1
						,"DEFAULT_VALUE" => "" 
						,"HTML_RENDER" => "SINGLELINE"//PASSWORD
						,"DB_TYPE" => "STRING"
						,"MAXLENGTH" => "100"
					)
			,"date_insert" => array 
					(
						"FIELD_TYPE" => "DISPLAY" 
						,"TITLE" => core_insertDate
						,"DISPLAY_GRID_WIDTH" => "15"
						,"DEFAULT_INSERT_VALUE" => date('Y-m-d H:i:s')
						,"DB_TYPE" => "STRING"
					)
			,"last_login" => array 
					(
						"FIELD_TYPE" => "DISPLAY" 
						,"TITLE" => user_lastlogin
						,"DISPLAY_GRID_WIDTH" => "15%"
						,"DB_TYPE" => "STRING"
					)								
	)
	,"EXTENDED" => array (
		
	)
);

$usersComponentManage["SCHEMA"]["phone"] = array(
	"FIELD_TYPE" => "SIMPLE" 
	,"TITLE" => user_phone
	,"DEFAULT_VALUE" => "" 
	,"HTML_RENDER" => "SINGLELINE"
	,"DB_TYPE" => "STRING"
	,"MAXLENGTH" => "20"
);

$usersComponentManage["SCHEMA"]["discount"] = array(
	"FIELD_TYPE" => "SIMPLE" 
	,"TITLE" => user_discount
	,"DEFAULT_VALUE" => "" 
	,"HTML_RENDER" => "SINGLELINE"
	,"DB_TYPE" => "INTEGER"
	,"MAXLENGTH" => "20"
);

$usersComponentManage["SCHEMA"]["company"] = array(
	"FIELD_TYPE" => "SIMPLE" 
	,"TITLE" => user_company
	,"DEFAULT_VALUE" => "" 
	,"HTML_RENDER" => "SINGLELINE"
	,"DB_TYPE" => "STRING"
	,"MAXLENGTH" => "20"
);

$usersComponentManage["SCHEMA"]["profession"] = array(
	"FIELD_TYPE" => "SIMPLE" 
	,"TITLE" => user_profession
	,"DEFAULT_VALUE" => "" 
	,"HTML_RENDER" => "SINGLELINE"
	,"DB_TYPE" => "STRING"
	,"MAXLENGTH" => "20"
);

$usersComponentManage["SCHEMA"]["afm"] = array(
	"FIELD_TYPE" => "SIMPLE" 
	,"TITLE" => user_afm
	,"DEFAULT_VALUE" => "" 
	,"HTML_RENDER" => "SINGLELINE"
	,"DB_TYPE" => "STRING"
	,"MAXLENGTH" => "20"
);

$usersComponentManage["SCHEMA"]["doy"] = array(
	"FIELD_TYPE" => "SIMPLE" 
	,"TITLE" => user_doy
	,"DEFAULT_VALUE" => "" 
	,"HTML_RENDER" => "SINGLELINE"
	,"DB_TYPE" => "STRING"
	,"MAXLENGTH" => "20"
);
/*
$usersComponentManage["SCHEMA"]["pricelist_id"] = array(
	"FIELD_TYPE" => "LOOKUP" 
	,"TITLE" => user_pricelists
	,"REQUIRE" => 0
	,"HTML_RENDER" => "SELECT"
	,"DB_TYPE" => "INTEGER"
	,"LOOKUP_TABLE_NAME" => "ecm_pricelists"
	,"LOOKUP_KEY" => "pricelist_id"
	,"LOOKUP_VALUE" => "title"
);
*/

?>