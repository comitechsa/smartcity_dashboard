<?php
//get the q parameter from URL
$q=$_GET["q"];

//find out which feed was selected
if($q=="drama") {
  $xml=("https://dimos-dramas.gr/feed/");
}
// elseif($q=="ZDN") {
//  $xml=("https://www.zdnet.com/news/rss.xml");
//}

$xmlDoc = new DOMDocument();
$xmlDoc->load($xml);

//get elements from "<channel>"
$channel=$xmlDoc->getElementsByTagName('channel')->item(0);
$channel_title = $channel->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
$channel_link = $channel->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
$channel_desc = $channel->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;

//output elements from "<channel>"
echo("<p><a href='" . $channel_link. "'>" . $channel_title . "</a>");
echo("<br>");
echo($channel_desc . "</p>");

//get and output "<item>" elements
$x=$xmlDoc->getElementsByTagName('item');
for ($i=0; $i<8; $i++) {
  $item_title=$x->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
  $item_link=$x->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
  $item_desc=$x->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
  //echo ("<p><a target='blank' href='" . $item_link. "'>" . $item_title . "</a>");
  
 echo '<div class="notification-messages info">';
 echo '<div class="message-wrapper">';
 echo '<div class="heading"> <a style="font-weight: 400;font-size: 14px;" target="_blank" href="'. $item_link.'">'. $item_title.'</a></div>';

 echo '<div class="description"> '.$item_desc.'</div>';
echo '</div>';
echo '<div class="date pull-right"> Just now </div>';
echo '<div class="clearfix"></div>';
echo '</div>';
					  
 // echo ("<br>");
  //echo ($item_desc . "</p>");
}
?>