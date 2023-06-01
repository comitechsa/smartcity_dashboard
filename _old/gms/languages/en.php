<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

define('core_login','Login');
define('core_userName','User Name');
define('core_userPassword','User Password');
define('core_loginMessage','Please enter the right credencials.');
define('core_noScript','!Warning! Javascript must be enabled for proper operation of the Administrator');

define('core_recordSelect','Please select record.');
define('core_preview','Preview');
define('core_insert','Insert');
define('core_newRecord','New Record');
define('core_edit','Edit');
define('core_delete','Delete');
define('core_deleteConfirm','Please press `OK` in order to procced');
define('core_save','Save');
define('core_saveConfirm','Please press `OK` in order to procced');
define('core_cancel','Cancel');
define('core_back','Back');
define('core_search','Search');
define('core_insertUpdate','Insert - Update');
define('core_recordSaved','Record saved succefully.');
define('core_user','User');
define('core_insertDate','Insert Date');
define('core_select','Select');
define('core_hits','Counter');

function core_mouths(){return array("January","February","March","April","May","June","July","August","September","October","November","December");};
function core_mouthsIU(){return array("January","February","March","April","May","June","July","August","September","October","November","December");};
function  core_days() {return array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");};

define('core_securityCode','Security Code');
define('core_securityCodeError','Security code is wrong');

?>