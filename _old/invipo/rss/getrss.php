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
//echo("<p><a href='" . $channel_link. "'>" . $channel_title . "</a>");
//echo("<br>");
//echo($channel_desc . "</p>");


//<div class="cd__tile__content d-flex flex-column h-100" style="" id="snippet--rssData">        
//	<div class="cd__tile__rss__item">
//		<span>May 5, 2021, 1:34:50 PM</span>
//		<a href="http://www.trnava.sk/sk/aktualita/mobilne-odberove-miesta-zriadene-mz-sr-v-trnave" class="rss-data-list-item-link" target="_blank">Mobilné odberové miesta zriadené MZ SR v Trnave</a>
//	</div>
//</div>

//get and output "<item>" elements
$x=$xmlDoc->getElementsByTagName('item');
for ($i=0; $i<15; $i++) {
  $item_title=$x->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;
  $item_link=$x->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;
  $item_desc=$x->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;
  //echo ("<p><a target='blank' href='" . $item_link. "'>" . $item_title . "</a>");
  
 //echo '<div class="notification-messages info">';
 //echo '<div class="message-wrapper">';
 //echo '<div class="heading"> <a target="_blank" href="'. $item_link.'">'. $item_title.'</a></div>';

//echo '<div class="description"> '.$item_desc.'</div>';
//echo '</div>';
//echo '<div class="date pull-right"> Just now </div>';
//echo '<div class="clearfix"></div>';
//echo '</div>';

echo '<div class="cd__tile__rss__item">';
//echo '<span>May 5, 2021, 1:34:50 PM</span>';
echo '<a target="_blank"  class="" style="font-weight: 400;font-size: 14px;" href="'. $item_link.'">'. $item_title.'</a>';
echo '</div>';
					  
 // echo ("<br>");
  //echo ($item_desc . "</p>");
}
?>