<?php
header ( 'Content-type: text/xml' );
include ( '../bully.inc' ); 

$rc = '0';

$message = 'Setting Successfuly Changed.';

$db = new Database();

$name = '';

switch ($_GET[n])
{
   case '1':   
       $name = 'Years of Price History';
       break;

   case '2':   
       $name = 'Days of Market Signals';
       break;

   case '10':   
       $name = 'Percentage of Equity Risked Per Trade';
       break;

   case '11':   
       $name = 'Number of Concurrent Open Positions';
       break;

   case '12':   
       $name = 'Maximum Price Per Share';
       break;
}

$sql = "update settings set value = '$_GET[v]' where fund = $_GET[f] and name = '$name'";

if ( !mysql_query ( $sql ) )
{
    $rc = '4';
    $message = mysql_error();
}


$doc = new DomDocument ( '1.0' );
$root = $doc->createElement ( 'root' );
$root = $doc->appendChild ( $root );

$result = $doc->createElement ( 'result' );
$result = $root->appendChild ( $result );
addNode ( $doc, $result, 'code', $rc );
addNode ( $doc, $result, 'message', $message );

echo $doc->saveXML();
?>