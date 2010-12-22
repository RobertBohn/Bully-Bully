<?php
header ( 'Content-type: text/xml' );
include ( '../bully.inc' ); 

$rc = '0';

$message = 'Setting Successfuly Changed.';

$db = new Database();

$sql = '';
if ($_GET[c] == '1')
{
    $sql = 'insert into fundstocks (fund, stock) values ( '. $_GET[f] . ', ' . $_GET[s] . ' )';
}
else
{
    $sql = 'delete from fundstocks where fund = ' . $_GET[f] . ' and stock = ' . $_GET[s];
}


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