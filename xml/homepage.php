<?php
header ( 'Content-type: text/xml' );
include ( '../bully.inc' ); 


$db = new Database();
$sql = "select id, name from funds order by id desc";
$rows = mysql_query ( $sql ) or die ( 'select error: ' . mysql_error());


$doc = new DomDocument ( '1.0' );                    // create a new XML document

$root = $doc->createElement ( 'root' );              // <root>
$root = $doc->appendChild ( $root );

while ( $cols = mysql_fetch_array ( $rows ) )
{    
    $occ = $doc->createElement ( 'funds' );          // <funds>
    $occ = $root->appendChild ( $occ );

    addNode ( $doc, $occ, 'id', $cols['id'] );       // <funds><id>
    addNode ( $doc, $occ, 'name', $cols['name'] );   // <funds><name>
}

echo $doc->saveXML();
?>
