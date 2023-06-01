<?php
echo '123';
require_once('./browser.php');
    
$browser = new SimpleBrowser();
$a=$browser->get('http://www.vrisko.gr/efimeries-farmakeion/giannitsa');
var_dump($a);
exit;
$browser->click('reporting bugs');
$browser->click('statistics');
$page = $browser->click('PHP 5 bugs only');
preg_match('/status=Open.*?by=Any.*?(\d+)<\/a>/', $page, $matches);
print $matches[1];
?>