<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head><title>Admin - Funds</title></head>
<body>
<?php
$from = $_GET[f];
$to = $_GET[t];
$subject = $_GET[s];
$body = $_GET[b];
 
mail( $to, urldecode($subject), urldecode($body), "From: ".$from );
?> 
</body>
</html>