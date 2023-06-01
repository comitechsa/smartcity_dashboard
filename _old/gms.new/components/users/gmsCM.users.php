<?php defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<?
	$usersComponentManage = array(
	"DECLARETION" => array (
			"UNIQUEID" => "users"
			,"HEADER" => users_users
			,"TABLE" => "users"
			,"PRIMARY_KEY_COLUMN" => "user_id"
			,"SEARCH_FILTER" => " (email like '%@param%' or user_fullname like '%@param%' or user_name like '%@param%') "
			,"SEARCH_TITLE" => users_searchApplyTo
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
					)
			,"user_auth" => array 
					(
						"FIELD_TYPE" => "SIMPLE" 
						,"TITLE" => users_userAuth
						,"REQUIRE" => 1 
						,"DEFAULT_VALUE" => "Register" 
						,"HTML_RENDER" => "ENUM"
						,"DB_TYPE" => "STRING"
						,"ENUM_PAIRS" => array("Administrator" => "Administrator","Register" => "Register")
						,"DISPLAY_GRID_WIDTH" => "20%"
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
						,"DISPLAY_GRID_WIDTH" => "25%"
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
						,"DISPLAY_GRID_WIDTH" => "20%"
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
						,"HTML_RENDER" => "PASSWORD"
						,"DB_TYPE" => "STRING"
						,"MAXLENGTH" => "100"
					)
			,"date_insert" => array 
					(
						"FIELD_TYPE" => "DISPLAY" 
						,"TITLE" => core_insertDate
						,"DISPLAY_GRID_WIDTH" => "20%"
						,"DEFAULT_INSERT_VALUE" => date('Y-m-d H:i:s')
						,"DB_TYPE" => "STRING"
					)	
			,"activate" => array 
					(
						"FIELD_TYPE" => "SIMPLE" 
						,"TITLE" => users_activate
						,"REQUIRE" => 0
						,"DEFAULT_VALUE" => "" 
						,"HTML_RENDER" => "SINGLELINE"
						,"DB_TYPE" => "STRING"
						,"MAXLENGTH" => "255"
					)	
								
	)
	,"EXTENDED" => array (
		
	)
);

?>