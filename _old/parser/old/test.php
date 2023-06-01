<?php
// example of how to use basic selector to retrieve HTML contents

$url='https://www.vrisko.gr/efimeries-farmakeion/giannitsa';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, TRUE); // We'll parse redirect url from header.
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE); // We want to just get redirect url but not to follow it.
$response = curl_exec($ch);
echo $response;
exit;
preg_match_all('/^Location:(.*)$/mi', $response, $matches);
curl_close($ch);
exit;

include('./simple_html_dom.php');

// get DOM from URL or file
//$html = file_get_html('https://www.google.com/');
$h=file_get_contents('https://www.vrisko.gr/efimeries-farmakeion/giannitsa');
echo $h;
$html = file_get_html('https://www.vrisko.gr/efimeries-farmakeion/giannitsa/');
 echo $html;
exit;
// find all link
foreach($html->find('a') as $e) 
    echo $e->href . '<br>';

// find all image
foreach($html->find('img') as $e)
    echo $e->src . '<br>';

// find all image with full tag
foreach($html->find('img') as $e)
    echo $e->outertext . '<br>';

// find all div tags with id=gbar
foreach($html->find('div#gbar') as $e)
    echo $e->innertext . '<br>';

// find all span tags with class=gb1
foreach($html->find('span.gb1') as $e)
    echo $e->outertext . '<br>';

// find all td tags with attribite align=center
foreach($html->find('td[align=center]') as $e)
    echo $e->innertext . '<br>';
    
// extract text from table
echo $html->find('td[align="center"]', 1)->plaintext.'<br><hr>';

// extract text from HTML
echo $html->plaintext;
?>