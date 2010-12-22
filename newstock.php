<?php 
session_start(); 
include ( 'bully.inc' ); 

if (isset($_POST[symbol]))
{
    if ($_POST[symbol] == '' || $_POST[company] == '')
    {
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
        translateXml ( PATH . 'xml/newstock.php', 'xml/newstock.xsl' );
    }
    else
    {
        $db = new Database();
        mysql_query("insert into stocks (symbol,company,enabled) values ('$_POST[symbol]','$_POST[company]','N')") or die("Insert error:" . mysql_error());
        header("Location: bully.php?t=0&m=3&p=1&");
    }
}
else
{
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
    translateXml ( PATH . 'xml/newstock.php', 'xml/newstock.xsl' );
}
?>