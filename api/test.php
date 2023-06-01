<?php
//https://www.php-obfuscator.com/?lang=english&demo
//https://github.com/pl4g4/online-php-obfuscator
//https://www.mobilefish.com/services/php_obfuscator/php_obfuscator.php
/*
INSERT INTO packageitems(package_name, lat, lng, movedate) VALUES ('1588917116','40.7939724','22.4095711','52305-03-18 22:09:07')6.5385155844549 - 2. - 40.7940019 - 22.4094638 - 1588776559827
INSERT INTO packageitems(package_name, lat, lng, movedate) VALUES ('1588917116','40.7940019','22.4094638','52316-04-29 15:10:27')9.6404949597013 - 3. - 40.7939152 - 22.4096897 - 1588790855763
INSERT INTO packageitems(package_name, lat, lng, movedate) VALUES ('1588917116','40.7939152','22.4096897','52316-10-12 02:16:03')5.7603479185527 - 4. - 40.7939719 - 22.4095982 - 1588881604600
INSERT INTO packageitems(package_name, lat, lng, movedate) VALUES ('1588917116','40.7939719','22.4095982','52319-08-28 10:16:40')5.6041301691849 - 5. - 40.7939215 - 22.409673 - 1588881905413
*/
echo strtotime("now").'<br>';


echo '1588776559827 '.date("Y-m-d H:i:s", 1588776559827/1000).'<br>';
echo '1588790855763 '.date("Y-m-d H:i:s", 1588790855763/1000).'<br>';
echo '1588881604600 '.date("Y-m-d H:i:s", 1588881604600/1000).'<br>';
echo '1588881905413 '.date("Y-m-d H:i:s", 1588881905413/1000).'<br>';

?>