<?php session_start(); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><?php 
include ( 'bully.inc' ); 

if (isset($_POST[amount]))
{
    if ($_POST[amount] == '')
    {
        translateXml ( PATH . 'xml/newdeposit.php?t=' . $_GET[t] . '&', 'xml/newdeposit.xsl' );
    }
    else
    {
        $db = new Database();
        mysql_query('insert into deposits ( fund, thedate, amount ) values (' . $_GET[t] . ',now(),' . $_POST[amount] . ')') or die("Insert error:" . mysql_error());
        translateXml ( PATH . 'xml/deposits.php?t=' . $_GET[t] . '&m=13&s=y&', 'xml/deposits.xsl' );
    }
}
else
{
    translateXml ( PATH . 'xml/newdeposit.php?t=' . $_GET[t] . '&', 'xml/newdeposit.xsl' );
}

?>