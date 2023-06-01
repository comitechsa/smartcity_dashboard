<?php
if (!isset($HTTP_POST_VARS['URI']) || !$HTTP_POST_VARS['URI']){
    header("Location: index.html");
    die;
}
$uri = urlencode($HTTP_POST_VARS['URI']);

$frameset = <<<FRAMESET
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title></title>
</head>
<!-- frames -->
<frameset  rows="8%,*">
    <frame name="php_browser_location" src="location.html" marginwidth="10" marginheight="10" scrolling="no" frameborder="1" noresize>
    <frame name="php_browser_main" src="main.php?php_browser_uri=$uri" marginwidth="10" marginheight="10" scrolling="auto" frameborder="0">
</frameset>

</html>

FRAMESET;
echo $frameset;

?>