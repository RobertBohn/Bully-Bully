<?php session_start(); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><?php 
include ( 'bully.inc' ); 

if (isset($_POST[name]))
{
    if ($_POST[name] == '')
    {
        translateXml ( PATH . 'xml/newfund.php', 'xml/newfund.xsl' );
    }
    else
    {
        $db = new Database();
        mysql_query("insert into funds (name) values ('$_POST[name]')") or die("Insert error:" . mysql_error());
        translateXml ( PATH . 'xml/funds.php?t=0&m=1&s=y&', 'xml/funds.xsl' );
    }
}
else
{
    translateXml ( PATH . 'xml/newfund.php', 'xml/newfund.xsl' );
}

?>