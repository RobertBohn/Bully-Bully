<?php
header ( 'Content-type: text/xml' );
include ( '../bully.inc' ); 

$db = new Database();

$doc = new DomDocument ( '1.0' );                    // create a new XML document
$root = $doc->createElement ( 'root' );              // <root>
$root = $doc->appendChild ( $root );


// build fund list

$sql = "select id, name from funds order by id desc";
$rows1 = mysql_query ( $sql ) or die ( 'select error: ' . mysql_error());
while ( $cols1 = mysql_fetch_array ( $rows1 ) )
{    
    $occ = $doc->createElement ( 'funds' );          // <funds>
    $occ = $root->appendChild ( $occ );

    addNode ( $doc, $occ, 'id', $cols1['id'] );       // <funds><id>
    addNode ( $doc, $occ, 'name', $cols1['name'] );   // <funds><name>





    $fund = $cols1['id'];
    $date = getorset( '0', 'Last Process Date', '2007-06-13' );

    // Get Deposits

    $sql = "select ifnull(sum(amount),0) deposits from deposits where fund = " . $fund;
    $rows = mysql_query ( $sql );
    $cols = mysql_fetch_array ( $rows );
    $deposits = $cols['deposits'];

    // Get Profits

    $sql = "select ifnull(sum((amount / buyprice) * (sellprice - buyprice)),0) profit from trades where fund = " . $fund . " and selldate < '" . $date . "'";
    $rows = mysql_query ( $sql );
    $cols = mysql_fetch_array ( $rows );
    $profit = $cols['profit'];

    // Get Gain & Holdings
    $sql = "select ifnull(sum(t.amount),0) holdings, ifnull(sum((t.amount / t.buyprice) * (ifnull(t.sellprice,p.price) - t.buyprice) ),0) gain from trades t, prices p where t.stock = p.stock and p.thedate = '" . $date . "' and t.fund = " . $fund . " and ifnull(t.selldate,'" . $date . "') >= '" . $date . "'";
    $rows = mysql_query ( $sql );
    $cols = mysql_fetch_array ( $rows );
    $holdings = $cols['holdings'];
    $gain = $cols['gain'];

    // Calculate Cash & Equity

    $cash = $deposits + $profit - $holdings;
    $equity = $deposits + $profit + $gain;
    $portfolio = $equity - $cash;


    addNode ( $doc, $occ, 'cash', $cash );   
    addNode ( $doc, $occ, 'stocks', $portfolio );
}


// build navigation

$navigation = $doc->createElement ( 'navigation' );          // <navigation>
$navigation = $root->appendChild ( $navigation );

addNode ( $doc, $navigation, 'tab', $_GET[t] );
addNode ( $doc, $navigation, 'menu', $_GET[m] ); 
addNode ( $doc, $navigation, 'pagedescr', 'Review the Individual Funds' );
addNode ( $doc, $navigation, 'title', 'Bully Bully - Funds' );
addNode ( $doc, $navigation, 'pagename', 'Administration - Funds' );

if ($_GET[s] == 'y')
    addNode ( $doc, $navigation, 'signedin', 'yes' );
else
    addNode ( $doc, $navigation, 'signedin', 'no' );


echo $doc->saveXML();
?>
