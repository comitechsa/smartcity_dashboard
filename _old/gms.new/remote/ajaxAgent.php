<?php
define('_VALID_PROCCESS',true);
define('_VALID_FLASH_PROCCESS',true);
require('../../global.php');
require('../include/gms.php');

class Agent 
{
	function call($aa_sfunc, $aa_cfunc, $aa_sfunc_args) 
	{
		global $db, $config;
		
		$json = new Services_JSON();
		$aa_sfunc_args_dc=array();
		
		if($aa_sfunc_args && sizeof($aa_sfunc_args)>=1) 
		{
			foreach ($aa_sfunc_args as $aa_arg) 
			{
				if ((strpos($aa_arg, "[")!==false) || (strpos($aa_arg, "{")!==false)) 
				{
					if ((strpos($aa_arg, "[")===0) || (strpos($aa_arg, "{")===0)) 
					{
						$aa_arg = str_replace('\"', '"', $aa_arg);
						$aa_arg_dc = $json->decode($aa_arg);
						array_push($aa_sfunc_args_dc,$aa_arg_dc);
					} 
					else 
					{
						array_push($aa_sfunc_args_dc,$aa_arg);
					}
				} 
				else 
				{
					array_push($aa_sfunc_args_dc,$aa_arg);
				}
			}
		}

		@ob_end_clean();

		$aa_ServiceFuncArray = split("::",$aa_sfunc);
		
		if(count($aa_ServiceFuncArray) == 2)
		{
			$servicesPath = "../../sites/" . $config["site"] . "/services/";
			$path = realpath($servicesPath  . '/') . '/';

			if(file_exists($path . $aa_ServiceFuncArray[0] . ".php"))
			{
				require_once($path . $aa_ServiceFuncArray[0] . ".php");
				
				$aa_sfunc = $aa_ServiceFuncArray[1];
				
				$ajaxAgentAllowFunctions = array();
				if(isset($GLOBALS['ajaxAgent']['AllowFunctions']) && $GLOBALS['ajaxAgent']['AllowFunctions'] != "") $ajaxAgentAllowFunctions = $GLOBALS['ajaxAgent']['AllowFunctions'];
				$ret = "";
				if( function_exists( $aa_sfunc ) && in_array($aa_sfunc, $ajaxAgentAllowFunctions) )		
				{
					$ret = call_user_func_array($aa_sfunc, $aa_sfunc_args_dc);
					if(is_array($ret) || is_object($ret)) 
					{
						$ret = $json->encode($ret);
						$ret = str_replace('\"', '"', $ret);
						echo $ret; 
					} 
					else 
					{
						echo $ret;
					}
				}
			}
		}		
		//Close The Database Connection	
		if(isset($db) && $db != "") $db->sql_close();
		
		//Write Authenticate To Session
		WriteAuthenticateToSession();
		exit();
	}

	function listen ($aa_event, $aa_cfunc, $aa_sfunc_args) 
	{
		exit();
	}
}
    
// Server side Ajax Agent implimentation follows
$agent = new Agent;

if (isset($_POST['aa_afunc'])) $aa_afunc = $_POST['aa_afunc']; else $aa_afunc="";
if (isset($_POST['aa_sfunc'])) $aa_sfunc = $_POST['aa_sfunc']; else $aa_sfunc="";
if (isset($_POST['aa_event'])) $aa_event = $_POST['aa_event']; else $aa_event="";
if (isset($_POST['aa_cfunc'])) $aa_cfunc = $_POST['aa_cfunc']; else $aa_cfunc="";
if (isset($_POST['aa_sfunc_args'])) $aa_sfunc_args = $_POST['aa_sfunc_args']; 
else $aa_sfunc_args="";

if($aa_afunc=="call") 
{
	$agent->call($aa_sfunc, $aa_cfunc, $aa_sfunc_args);
}
  
if($aa_afunc=="listen") 
{
	$agent->listen($aa_event, $aa_cfunc, $aa_sfunc_args);
}
?>