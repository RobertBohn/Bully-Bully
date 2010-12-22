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



// get trade

$sql = "select id, stock, s1date, s1price, s2date, s2price, buydate, buyprice, amount, stop, selldate, sellprice, datediff(selldate,buydate) days from trades where id = $_GET[d]";
$rows = mysql_query ( $sql ) or die ( 'select error: ' . mysql_error());
$cols = mysql_fetch_array ( $rows ) or die ( 'trade not found. id: ' . $_GET[d]); 
$stock = $cols['stock'];
$s1date = $cols['s1date'];
$s1price = $cols['s1price'];
$s2date = $cols['s2date'];
$s2price = $cols['s2price'];
$buydate = $cols['buydate'];
$buyprice = $cols['buyprice'];
$amount = $cols['amount'];
$stop = $cols['stop'];
$days = $cols['days'];
$selldate = $cols['selldate'];
$sellprice = $cols['sellprice'];
$id = $cols['id'];



// get stock

$sql = "select id, symbol, company, enabled, price, day10, low120, high120, process from stocks where id = $stock";
$rows = mysql_query ( $sql ) or die ( 'select error: ' . mysql_error());
$cols = mysql_fetch_array ( $rows ) or die ( 'stock not found. id: ' . $stock); 





// build navigation

$navigation = $doc->createElement ( 'navigation' );          // <navigation>
$navigation = $root->appendChild ( $navigation );

addNode ( $doc, $navigation, 'tab', $_GET[t] );
addNode ( $doc, $navigation, 'menu', 'none' ); 
addNode ( $doc, $navigation, 'pagedescr', $cols['company'] . ' (' . $cols['symbol'] . ')');
addNode ( $doc, $navigation, 'title', 'Bully Bully - Trades' );
addNode ( $doc, $navigation, 'pagename', $fundname . ' - Trade Detail' );

if ($_GET[s] == 'y')
    addNode ( $doc, $navigation, 'signedin', 'yes' );
else
    addNode ( $doc, $navigation, 'signedin', 'no' );





// build content - stock detail

$occ = $doc->createElement ( 'content' );        // <content>
$occ = $root->appendChild ( $occ );

addNode ( $doc, $occ, 'id', $id );
addNode ( $doc, $occ, 'symbol', $cols['symbol'] );
addNode ( $doc, $occ, 'company', $cols['company'] );
addNode ( $doc, $occ, 'enabled', $cols['enabled'] );
addNode ( $doc, $occ, 'price', $cols['price'] );
addNode ( $doc, $occ, 'day10', $cols['day10'] );
addNode ( $doc, $occ, 'low120', $cols['low120'] );
addNode ( $doc, $occ, 'high120', $cols['high120'] );
addNode ( $doc, $occ, 'process', $cols['process'] );

addNode ( $doc, $occ, 's1date', $s1date );
addNode ( $doc, $occ, 's1price', $s1price );
addNode ( $doc, $occ, 's2date', $s2date );
addNode ( $doc, $occ, 's2price', $s2price );
addNode ( $doc, $occ, 'buydate', $buydate );
addNode ( $doc, $occ, 'buyprice', $buyprice );
addNode ( $doc, $occ, 'amount', $amount );
addNode ( $doc, $occ, 'days', $days );
addNode ( $doc, $occ, 'selldate', $selldate );
addNode ( $doc, $occ, 'sellprice', $sellprice );


if ( floatval( $stop ) > floatval( $cols['low120'] ) ) 
    addNode ( $doc, $occ, 'stop', $stop );
else
    addNode ( $doc, $occ, 'stop', $cols['low120'] );



// Javascript

if ($_GET[s] == 'y')
{
    $js = $doc->createElement ( 'javascript' );          // <javascript>
    $js = $root->appendChild ( $js );
    addNode ( $doc, $js, 'script', 'js/viewtrade.js' );
}



echo $doc->saveXML();
?>
