<?php
header ( 'Content-type: text/xml' );
include ( '../bully.inc' ); 

$db = new Database();

$doc = new DomDocument ( '1.0' );                    // create a new XML document
$root = $doc->createElement ( 'root' );              // <root>
$root = $doc->appendChild ( $root );


// build fund list

$sql = "select id, name from funds order by id desc";
$rows = mysql_query ( $sql ) or die ( 'select error: ' . mysql_error());
while ( $cols = mysql_fetch_array ( $rows ) )
{    
    $occ = $doc->createElement ( 'funds' );          // <funds>
    $occ = $root->appendChild ( $occ );

    addNode ( $doc, $occ, 'id', $cols['id'] );       // <funds><id>
    addNode ( $doc, $occ, 'name', $cols['name'] );   // <funds><name>
}


// build navigation

$navigation = $doc->createElement ( 'navigation' );          // <navigation>
$navigation = $root->appendChild ( $navigation );

addNode ( $doc, $navigation, 'tab', '0' );
addNode ( $doc, $navigation, 'menu', '5' ); 
addNode ( $doc, $navigation, 'pagedescr', 'Signin for Administrative Privileges' );
addNode ( $doc, $navigation, 'title', 'Bully Bully - Signin' );
addNode ( $doc, $navigation, 'pagename', 'Administration - Signin' );
addNode ( $doc, $navigation, 'signedin', 'no' );


// Javascript

$js = $doc->createElement ( 'javascript' );          // <javascript>
$js = $root->appendChild ( $js );
addNode ( $doc, $js, 'script', 'js/signin.js' );

echo $doc->saveXML();
?>
