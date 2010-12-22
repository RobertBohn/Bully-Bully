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
addNode ( $doc, $navigation, 'menu', '2' ); 
addNode ( $doc, $navigation, 'pagedescr', 'Process New Stock Prices And Market Signals' );
addNode ( $doc, $navigation, 'title', 'Bully Bully - Process' );
addNode ( $doc, $navigation, 'pagename', 'Administration - Process' );

if ($_GET[s] == 'y')
    addNode ( $doc, $navigation, 'signedin', 'yes' );
else
    addNode ( $doc, $navigation, 'signedin', 'no' );



// build content

$sql = 'select current_date() today';
$rows = mysql_query ( $sql );
$cols = mysql_fetch_array ( $rows );
$today = $cols['today'];

$ss = $doc->createElement ( 'settings' );          // <settings>
$ss = $root->appendChild ( $ss );
addNode ( $doc, $ss, 'lastprocess', getorset( '0', 'Last Process Date', '2007-06-13' ) );
addNode ( $doc, $ss, 'today', $today );






// Javascript

$js = $doc->createElement ( 'javascript' );          // <javascript>
$js = $root->appendChild ( $js );
addNode ( $doc, $js, 'script', 'js/process.js' );

echo $doc->saveXML();
?>
