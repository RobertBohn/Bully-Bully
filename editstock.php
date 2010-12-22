<?php 
session_start(); 
include ( 'bully.inc' ); 

$detail = $_GET[d];
$signedin = $_SESSION['s'];

if ($signedin == '') $signedin = 'n';

if (isset($_POST[symbol]))
{
    if ($_POST[symbol] != '' && $_POST[company] != '')
    {
        $db = new Database();
        $sql = "update stocks set symbol = '$_POST[symbol]', company = '$_POST[company]', enabled = '$_POST[enabled]' where id = $detail";
        mysql_query($sql) or die("Update error:" . mysql_error());
        header("Location: bully.php?t=0&m=3&p=1&");
    }
    else
    {
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
        translateXml ( PATH . 'xml/editstock.php?d=' . $detail . '&s=' . $signedin . '&', 'xml/editstock.xsl' );
    }
}
else
{
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
    translateXml ( PATH . 'xml/editstock.php?d=' . $detail . '&s=' . $signedin . '&', 'xml/editstock.xsl' );
}
?>