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
addNode ( $doc, $navigation, 'pagedescr', "Review The System's Performance History" );
addNode ( $doc, $navigation, 'title', 'Bully Bully - History' );
addNode ( $doc, $navigation, 'pagename', 'Administration - History' );

if ($_GET[s] == 'y')
    addNode ( $doc, $navigation, 'signedin', 'yes' );
else
    addNode ( $doc, $navigation, 'signedin', 'no' );





// build content



$date = getorset( '0', 'Last Process Date', '2007-06-13' );

// Get Deposits

$sql = "select ifnull(sum(amount),0) deposits from deposits";
$rows = mysql_query ( $sql );
$cols = mysql_fetch_array ( $rows );
$deposits = $cols['deposits'];

// Get Profits

$sql = "select ifnull(sum((amount / buyprice) * (sellprice - buyprice)),0) profit from trades where selldate < '" . $date . "'";
$rows = mysql_query ( $sql );
$cols = mysql_fetch_array ( $rows );
$profit = $cols['profit'];

// Get Gain & Holdings

$sql = "select ifnull(sum(t.amount),0) holdings, ifnull(sum((t.amount / t.buyprice) * (ifnull(t.sellprice,p.price) - t.buyprice) ),0) gain from trades t, prices p where t.stock = p.stock and p.thedate = '" . $date . "' and ifnull(t.selldate,'" . $date . "') >= '" . $date . "'";
$rows = mysql_query ( $sql );
$cols = mysql_fetch_array ( $rows );
$holdings = $cols['holdings'];
$gain = $cols['gain'];

// Calculate Cash & Equity

$cash = $deposits + $profit - $holdings;
$equity = $deposits + $profit + $gain;
$portfolio = $equity - $cash;




$occ = $doc->createElement ( 'content' );        // 2006
$occ = $root->appendChild ( $occ );
addNode ( $doc, $occ, 'year', '2006' );            
addNode ( $doc, $occ, 'deposits', '5000' );
addNode ( $doc, $occ, 'earnings', '85' );
addNode ( $doc, $occ, 'return', '4' );
addNode ( $doc, $occ, 'balance', '5085' );

$occ = $doc->createElement ( 'content' );        // 2007
$occ = $root->appendChild ( $occ );
addNode ( $doc, $occ, 'year', '2007' );            
addNode ( $doc, $occ, 'deposits', 6000 );
addNode ( $doc, $occ, 'earnings', 1906 );
addNode ( $doc, $occ, 'return', '28' );
addNode ( $doc, $occ, 'balance', 12991 );

$occ = $doc->createElement ( 'content' );        // 2008
$occ = $root->appendChild ( $occ );
addNode ( $doc, $occ, 'year', '2008' );            
addNode ( $doc, $occ, 'deposits', $deposits - 11000 );
addNode ( $doc, $occ, 'earnings', $equity - ($deposits - 11000) - 12991 );
addNode ( $doc, $occ, 'return', '0' );
addNode ( $doc, $occ, 'balance', $equity );

$occ = $doc->createElement ( 'totals' );        // Totals
$occ = $root->appendChild ( $occ );
addNode ( $doc, $occ, 'year', 'Total' );            
addNode ( $doc, $occ, 'deposits', $deposits );
addNode ( $doc, $occ, 'earnings', $equity - $deposits );
addNode ( $doc, $occ, 'return', '22' );
addNode ( $doc, $occ, 'balance', $equity );




echo $doc->saveXML();
?>
