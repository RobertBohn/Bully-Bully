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

$sql = "select id, symbol, company, enabled, price, day10, low120, high120, process from stocks where id = $_GET[d]";
$rows = mysql_query ( $sql ) or die ( 'select error: ' . mysql_error());
$cols = mysql_fetch_array ( $rows ) or die ( 'stock not found. id: ' . $_GET[d]); 
$stock = $cols['id'];
$symbol = $cols['symbol'];
$company = $cols['company'];
$low120 = $cols['low120'];
$high120 = $cols['high120'];
$price = $cols['price'];
$process = $cols['process'];
$date = $cols['process'];
$fund = $_GET[t];
$stop = $low120 + (($price - $low120) * 0.5); 







// Get Signal Dates & Prices

$sql = "select thedate, price from signals where stock = " . $_GET[d] . " order by thedate";
$rows = mysql_query ( $sql );

$cols = mysql_fetch_array ( $rows );
$s1date = $cols['thedate'];
$s1price = $cols['price'];

$s2date = '';
$s2price = '0';

if ( $cols = mysql_fetch_array ( $rows ) )
{
    $s2date = $cols['thedate'];
    $s2price = $cols['price'];
}












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

// Get Fund Settings
$risk = getorset( $fund, 'Percentage of Equity Risked Per Trade', '2' );
$diversity = getorset( $fund, 'Number of Concurrent Open Positions', '15' );
$maxprice = getorset( $fund, 'Maximum Price Per Share', '30' );

               
// AMOUNT - Set Risk As Percentage Of Equity
$amount = ((($equity) * ($risk / 100)) / ($price - $stop)) * $price;

// AMOUNT - Adjust Amount To Achieve Diversity
if ($amount > ($equity) * (1 / $diversity)) $amount = ($equity) * (1 / $diversity);                
                
// AMOUNT - Set Amount Bases On Available Cash
if ($amount > $cash) $amount = $cash;

// AMOUNT - Set Amount Bases On Available Cash
if ($cash - $amount < 50) $amount = $cash;

// AMOUNT - Cheap Stocks Only
if ($maxprice != 0 && $price > $maxprice) $amount = 0;

// AMOUNT - Out of money
if ($amount < 50) $amount = 0;
               





















// build navigation

$navigation = $doc->createElement ( 'navigation' );          // <navigation>
$navigation = $root->appendChild ( $navigation );

addNode ( $doc, $navigation, 'tab', $_GET[t] );
addNode ( $doc, $navigation, 'menu', 'none' ); 
addNode ( $doc, $navigation, 'pagedescr', $company . ' (' . $symbol . ')');
addNode ( $doc, $navigation, 'title', 'Bully Bully - Signal' );
addNode ( $doc, $navigation, 'pagename', $fundname . ' - Signal Detail' );





// build content - signal detail

$occ = $doc->createElement ( 'content' );        // <content>
$occ = $root->appendChild ( $occ );

addNode ( $doc, $occ, 'symbol', $symbol );
addNode ( $doc, $occ, 'company', $company );
addNode ( $doc, $occ, 'low120', $low120 );
addNode ( $doc, $occ, 'high120', $high120 );
addNode ( $doc, $occ, 'price', $price );
addNode ( $doc, $occ, 'process', $process );

addNode ( $doc, $occ, 's1date', $s1date );
addNode ( $doc, $occ, 's1price', $s1price );
addNode ( $doc, $occ, 's2date', $s2date );
addNode ( $doc, $occ, 's2price', $s2price );

addNode ( $doc, $occ, 'amount', $amount );


echo $doc->saveXML();
?>
