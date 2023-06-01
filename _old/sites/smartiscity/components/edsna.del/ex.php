<?php
defined( '_VALID_PROCCESS' ) or die( 'Direct Access to this location is not allowed.' );

include($config["physicalPath"]."/languages/".$auth->LanguageCode.".php");
global $nav;
$item = (intval($_GET["item"]) > 0 ? intval($_GET["item"]) : "");
$BaseUrl = "index.php?com=ex";
//$query="SELECT * FROM `friends` where device_id='4FB1040CC9BE' AND (isnull(friend_id) or friend_id='') and email<>''";
$query="SELECT * FROM `friends` where device_id='4FB1040CC9BE' AND friend_id<>''";
$result = $db->sql_query($query);
$counter = 0;
	echo "<style>table, th, td {border: 1px solid black;}</style>";
	echo '<table>';
		echo '<tr>';
		echo '<td>id</td><td>Device id</td><td>Friend id</td><td>Birthday</td><td>email</td><td>First name</td><td>Last name</td><td>Gender</td><td>Hometown ID</td><td>Hometown name</td><td>Link</td><td>Location ID</td><td>Location Name</td><td>Quotes</td><td>Timezone</td><td>Facebook Verified</td><td>Email Verified</td><td>Date insert</td><td>IP address</td><td>HTTP USER AGENT</td>';
		echo '</tr>';
	while ($dr = $db->sql_fetchrow($result))
	{
		//echo $dr['id'].','.$dr['device_id'].','.$dr['friend_id'].','.$dr['birthday'].','.$dr['email'].','.$dr['first_name'].','.$dr['last_name'].','.$dr['gender'].','.$dr['hometown_id'].','.$dr['hometown_name'].','.$dr['link'].','.$dr['location_id'].','.$dr['location_name'].','.$dr['quotes'].','.$dr['timezone'].','.$dr['verified'].','.$dr['verifiedEmail'].','.$dr['date_insert'].','.$dr['ip'].',"'.$dr['HTTP_USER_AGENT'].'"<br>';
		echo '<tr>';
		echo '<td>'.$dr['id'].'</td><td>'.$dr['device_id'].'</td><td>"'.$dr['friend_id'].'"</td><td>'.$dr['birthday'].'</td><td>'.$dr['email'].'</td><td>'.$dr['first_name'].'</td><td>'.$dr['last_name'].'</td><td>'.$dr['gender'].'</td><td>'.$dr['hometown_id'].'</td><td>'.$dr['hometown_name'].'</td><td>'.$dr['link'].'</td><td>'.$dr['location_id'].'</td><td>'.$dr['location_name'].'</td><td>'.$dr['quotes'].'</td><td>'.$dr['timezone'].'</td><td>'.$dr['verified'].'</td><td>'.$dr['verifiedEmail'].'</td><td>'.$dr['date_insert'].'</td><td>'.$dr['ip'].'</td><td>'.$dr['HTTP_USER_AGENT'].'</td>';
		//echo '<td>"'.$dr['id'].'"</td><td>"'.$dr['device_id'].'"</td><td>"'.$dr['friend_id'].'"</td><td>"'.$dr['birthday'].'"</td><td>"'.$dr['email'].'"</td><td>"'.$dr['first_name'].'"</td><td>"'.$dr['last_name'].'"</td><td>"'.$dr['gender'].'"</td><td>"'.$dr['hometown_id'].'"</td><td>"'.$dr['hometown_name'].'"</td><td>"'.$dr['link'].'"</td><td>"'.$dr['location_id'].'"</td><td>"'.$dr['location_name'].'"</td><td>"'.$dr['quotes'].'"</td><td>"'.$dr['timezone'].'"</td><td>"'.$dr['verified'].'"</td><td>"'.$dr['verifiedEmail'].'"</td><td>"'.$dr['date_insert'].'"</td><td>"'.$dr['ip'].'"</td><td>"'.$dr['HTTP_USER_AGENT'].'"</td>';
		echo '</tr>';
	}
	echo '</table>';
	$db->sql_freeresult($result);
?>

