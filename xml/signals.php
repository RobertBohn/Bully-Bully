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



// build navigation

$navigation = $doc->createElement ( 'navigation' );          // <navigation>
$navigation = $root->appendChild ( $navigation );

addNode ( $doc, $navigation, 'tab', $_GET[t] );
addNode ( $doc, $navigation, 'menu', $_GET[m] ); 
addNode ( $doc, $navigation, 'pagedescr', 'Review Stocks That Are Aproaching A Buy Signal' );
addNode ( $doc, $navigation, 'title', 'Bully Bully - Signals' );
addNode ( $doc, $navigation, 'pagename', $fundname . ' - Signals' );




// build content

$sql = "select s.id stock, s.symbol, s.company, s.price last, count(*) signals, max(x.thedate) date, max(x.price) high from signals x, stocks s, fundstocks f where x.stock = f.stock and x.stock = s.id and f.fund = " . $_GET[t] . " group by s.id, s.symbol, s.company, s.price having count(*) < 3";

$rows = mysql_query ( $sql ) or die ( 'select error: ' . mysql_error());
while ( $cols = mysql_fetch_array ( $rows ) )
{    
    $occ = $doc->createElement ( 'content' );        // <content>
    $occ = $root->appendChild ( $occ );

    addNode ( $doc, $occ, 'stock', $cols['stock'] );
    addNode ( $doc, $occ, 'symbol', $cols['symbol'] );
    addNode ( $doc, $occ, 'company', $cols['company'] );
    addNode ( $doc, $occ, 'last', $cols['last'] );
    addNode ( $doc, $occ, 'signals', $cols['signals'] );
    addNode ( $doc, $occ, 'date', $cols['date'] );
    addNode ( $doc, $occ, 'high', $cols['high'] );
}




echo $doc->saveXML();
?>
