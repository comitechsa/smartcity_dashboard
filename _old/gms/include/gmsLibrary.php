<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

//require_once(dirname(__FILE__). '/libraries/gms/class.mysql.php');
require_once(dirname(__FILE__). '/libraries/gms/class.db.php');

require_once(dirname(__FILE__). '/libraries/gms/class.authenticate.php');

require_once(dirname(__FILE__). '/libraries/gms/class.toolbar.php');
require_once(dirname(__FILE__). '/libraries/gms/class.validator.php');
require_once(dirname(__FILE__). '/libraries/gms/class.components.php');

require_once(dirname(__FILE__). '/libraries/gms/class.events.php');

require_once(dirname(__FILE__). '/libraries/gms/class.multilinqualRepeater.php');

require_once(dirname(__FILE__). '/libraries/gms/class.controls.php');
require_once(dirname(__FILE__). '/libraries/gms/class.htmlheader.php');
require_once(dirname(__FILE__). '/libraries/gms/class.virtualadmin.php');

require_once(dirname(__FILE__). '/libraries/gms/class.messages.php');
require_once(dirname(__FILE__). '/libraries/gms/class.componentManage.php');
require_once(dirname(__FILE__). '/libraries/gms/class.attachment.php');
require_once(dirname(__FILE__). '/libraries/gms/class.xmlparser.php');
require_once(dirname(__FILE__). '/libraries/gms/class.timer.php');

require_once(dirname(__FILE__). '/libraries/phpInputFilter/class.inputfilter.php');

require_once(dirname(__FILE__). '/libraries/phpmailer/class.phpmailer.php');

require_once(dirname(__FILE__). '/libraries/Resize/class.resizeimage.php');

require_once(dirname(__FILE__). '/libraries/zip/zip.lib.php');

//require_once(dirname(__FILE__). '/libraries/nusoap/nusoap.php');
	
//require_once(dirname(__FILE__). '/libraries/excel/reader.php');

require_once(dirname(__FILE__). '/libraries/gms/class.captcha.php');

require_once(dirname(__FILE__). '/libraries/gms/class.json.php');


require_once(dirname(__FILE__). '/libraries/Pear/pear.php');
require_once(dirname(__FILE__). '/libraries/Pear/getopt.php');
require_once(dirname(__FILE__). '/libraries/Pear/system.php');
require_once(dirname(__FILE__). '/libraries/excel/ole/OLE.php');
require_once(dirname(__FILE__). '/libraries/excel/ole/PPS.php');
require_once(dirname(__FILE__). '/libraries/excel/ole/ChainedBlockStream.php');
require_once(dirname(__FILE__). '/libraries/excel/ole/PPS/Root.php');
require_once(dirname(__FILE__). '/libraries/excel/ole/PPS/File.php');
//require_once(dirname(__FILE__). '/libraries/excel/writer.php');

?>