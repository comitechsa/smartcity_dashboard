<?php ?>
<html>
<body>
Welcome <?php echo $_POST["name"]; ?><br>
Your email address is: <?php echo $_POST["email"]; ?>

<form action="formHandler.php" name="yourForm" id="theForm" method="post">    
<input type="text" name="fname" id="fname" style="width:90;font-size:10"> 
<input type="submit" value="submit"/>
</form>
</body>
</html>