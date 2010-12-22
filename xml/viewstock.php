<?php
header ( 'Content-type: text/xml' );
include ( '../bully.inc' ); 

$db = new Database();

$doc = new DomDocument ( '1.0' );                    // create a new XML document
$root = $doc->createElement ( 'root' );              // <root>
$root = $doc->appendChild ( $root );

$fundname = '';


// build fund list

$sql = "select id, name from funds order by id desc";
$rows = mysql_query ( $sql ) or die ( 'select error: ' . mysql_error());
while ( $cols = mysql_fetch_array ( $rows ) )
{    
    $occ = $doc->createElement ( 'funds' );          // <funds>
    $occ = $root->appendChild ( $occ );

    addNode ( $doc, $occ, 'id', $cols['id'] );       // <funds><id>
    addNode ( $doc, $occ, 'name', $cols['name'] );   // <funds><name>

    if ($cols['id'] == $_GET[t]) $fundname = $cols['name'];
}




// get stock

$sql = "select id, symbol, company, enabled, price, day10, low120, high120 from stocks where id = $_GET[d]";
$rows = mysql_query ( $sql ) or die ( 'select error: ' . mysql_error());
$cols = mysql_fetch_array ( $rows ) or die ( 'stock not found. id: ' . $_GET[d]); 





// build navigation

$navigation = $doc->createElement ( 'navigation' );          // <navigation>
$navigation = $root->appendChild ( $navigation );

addNode ( $doc, $navigation, 'tab', $_GET[t] );
addNode ( $doc, $navigation, 'menu', 'none' ); 
addNode ( $doc, $navigation, 'pagedescr', $cols['company'] . ' (' . $cols['symbol'] . ')');
addNode ( $doc, $navigation, 'title', 'Bully Bully - Stock' );
addNode ( $doc, $navigation, 'pagename', $fundname . ' - Stock Detail' );

if ($_GET[s] == 'y')
    addNode ( $doc, $navigation, 'signedin', 'yes' );
else
    addNode ( $doc, $navigation, 'signedin', 'no' );





// build content - stock detail

$occ = $doc->createElement ( 'content' );        // <content>
$occ = $root->appendChild ( $occ );

addNode ( $doc, $occ, 'id', $cols['id'] );
addNode ( $doc, $occ, 'symbol', $cols['symbol'] );
addNode ( $doc, $occ, 'company', $cols['company'] );
addNode ( $doc, $occ, 'enabled', $cols['enabled'] );

if ($cols['enabled'] == 'Y')
{
    addNode ( $doc, $occ, 'price', $cols['price'] );
    addNode ( $doc, $occ, 'day10', $cols['day10'] );
    addNode ( $doc, $occ, 'low120', $cols['low120'] );
    addNode ( $doc, $occ, 'high120', $cols['high120'] );
}



echo $doc->saveXML();
?>
