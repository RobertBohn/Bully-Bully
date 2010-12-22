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
addNode ( $doc, $navigation, 'menu', '3' ); 
addNode ( $doc, $navigation, 'pagedescr', 'Review Which Stocks Are Being Tracked' );
addNode ( $doc, $navigation, 'title', 'Bully Bully - Stocks' );
addNode ( $doc, $navigation, 'pagename', 'Administration - Stocks' );
addNode ( $doc, $navigation, 'page', $_GET[p] );

if ($_GET[s] == 'y')
    addNode ( $doc, $navigation, 'signedin', 'yes' );
else
    addNode ( $doc, $navigation, 'signedin', 'no' );


// build content - stock list

$sql = "select id, symbol, company, enabled, price, day10 from stocks where enabled = 'Y' order by symbol";

if ($_GET[s] == 'y') 
    $sql = "select id, symbol, company, enabled, price, day10 from stocks order by symbol";


$i = 0;
$rows = mysql_query ( $sql ) or die ( 'select error: ' . mysql_error());
while ( $cols = mysql_fetch_array ( $rows ) )
{    
    $occ = $doc->createElement ( 'content' );        // <content>
    $occ = $root->appendChild ( $occ );

    addNode ( $doc, $occ, 'id', $cols['id'] );
    addNode ( $doc, $occ, 'symbol', $cols['symbol'] );
    addNode ( $doc, $occ, 'company', $cols['company'] );
    addNode ( $doc, $occ, 'enabled', $cols['enabled'] );
    addNode ( $doc, $occ, 'line', $i++ );

    if ($cols['enabled'] == 'Y')
    {
        addNode ( $doc, $occ, 'price', $cols['price'] );
        addNode ( $doc, $occ, 'day10', $cols['day10'] );
    }
}


echo $doc->saveXML();
?>
