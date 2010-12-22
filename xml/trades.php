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
addNode ( $doc, $navigation, 'menu', '12' ); 
addNode ( $doc, $navigation, 'pagedescr', "Review the History of the Fund's Completed Trades" );
addNode ( $doc, $navigation, 'title', 'Bully Bully - Trades' );
addNode ( $doc, $navigation, 'pagename', $fundname . ' - Trades' );
addNode ( $doc, $navigation, 'page', $_GET[p] );

if ($_GET[s] == 'y')
    addNode ( $doc, $navigation, 'signedin', 'yes' );
else
    addNode ( $doc, $navigation, 'signedin', 'no' );




// build content - trades

$sql = "select t.id, t.stock, s.symbol, s.company, t.amount, t.buyprice, t.buydate, datediff(t.selldate,t.buydate) days, (t.amount / t.buyprice) shares, t.sellprice, (t.amount / t.buyprice) * (t.sellprice - t.buyprice) profit from trades t, stocks s where t.fund = " . $_GET[t] . " and t.stock = s.id and not t.selldate is null order by buydate";
$i = 0;
$rows = mysql_query ( $sql ) or die ( 'select error: ' . mysql_error());
while ( $cols = mysql_fetch_array ( $rows ) )
{    
    $occ = $doc->createElement ( 'content' );        // <content>
    $occ = $root->appendChild ( $occ );

    addNode ( $doc, $occ, 'id', $cols['id'] );
    addNode ( $doc, $occ, 'stock', $cols['stock'] );
    addNode ( $doc, $occ, 'company', $cols['company'] );
    addNode ( $doc, $occ, 'symbol', $cols['symbol'] );
    addNode ( $doc, $occ, 'buydate', $cols['buydate'] );
    addNode ( $doc, $occ, 'days', $cols['days'] );
    addNode ( $doc, $occ, 'shares', $cols['shares'] );
    addNode ( $doc, $occ, 'buyprice', $cols['buyprice'] );
    addNode ( $doc, $occ, 'sellprice', $cols['sellprice'] );
    addNode ( $doc, $occ, 'amount', $cols['amount'] );
    addNode ( $doc, $occ, 'profit', $cols['profit'] );
    addNode ( $doc, $occ, 'line', $i++ );
}



echo $doc->saveXML();
?>
