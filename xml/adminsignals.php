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
addNode ( $doc, $navigation, 'menu', '4' ); 
addNode ( $doc, $navigation, 'pagedescr', "Display Market Signals And Warnings" );
addNode ( $doc, $navigation, 'title', 'Bully Bully - Signals' );
addNode ( $doc, $navigation, 'pagename', 'Administration - Signals' );

if ($_GET[s] == 'y')
    addNode ( $doc, $navigation, 'signedin', 'yes' );
else
    addNode ( $doc, $navigation, 'signedin', 'no' );



// content

$days = getorset( '0', 'Days of Market Signals', '3' );
if ($days == 0) $days = 30;

for ($i=0; $i<$days; $i++)
{
    $today = time() - ($i * 24 * 60 * 60);
    $date = date("Y-m-d", $today );

    $occ = $doc->createElement ( 'content' );
    $occ = $root->appendChild ( $occ );
    addNode ( $doc, $occ, 'date', $date );
    addNode ( $doc, $occ, 'datestring',  date("l, F j, Y", $today ) );

    $signals = Array();

    if ($i == 0) getAlerts( &$signals );
    // getSignals( &$signals, $date );
    getBuys( &$signals, $date );
    getSells( &$signals, $date );

    foreach ($signals as $detail)
    {
        $det = $doc->createElement ( 'detail' );
        $det = $occ->appendChild ( $det );
        addNode ( $doc, $det, 'signal', $detail );
    }
}

echo $doc->saveXML();


// --------- Functions -------------------------


function getAlerts( $signals )
{
    // System Alert - GM 47% Price Change
    // System Alert - GM is in multiple funds

    $sql = "select symbol, price, day10, ( 100 * (price - day10) / price) gain from stocks where enabled = 'Y' and ( ( 100 * (price - day10) / price) > 35 or ( 100 * (price - day10) / price) < -35 )";
    $rows = mysql_query ( $sql );
    while ( $cols = mysql_fetch_array ( $rows ) )
    {    
        $symbol = $cols['symbol'];
        $gain =  intval($cols['gain']);
        if ($gain < 0) $gain = 0 - $gain;

        $signals[] = 'System Alert - ' . $symbol . ' ' . $gain . '% Price Change';
    }


    $sql = "select s.symbol, count(*) nbr from fundstocks f, stocks s where f.stock = s.id group by s.symbol having count(*) > 1";
    $rows = mysql_query ( $sql );
    while ( $cols = mysql_fetch_array ( $rows ) )
    {    
        $symbol = $cols['symbol'];
        $signals[] = 'System Alert - ' . $symbol . ' is in multiple funds';
    }
}


function getSignals( $signals, $date )
{
    // System Alert - (Initial,Second,Third) Signal for MSOF at 17.32

    $sql = "select s.symbol, count(*) signal, max(thedate) date, max(x.price) price from signals x, stocks s where x.stock = s.id and x.thedate <= '" . $date . "' group by s.symbol having max(thedate) = '" . $date . "'";
    $rows = mysql_query ( $sql );
    while ( $cols = mysql_fetch_array ( $rows ) )
    {    
        $symbol = $cols['symbol'];
        $signal = $cols['signal'];
        $price = $cols['price'];

        $type = 'Initial';
        if ($signal == 2) $type = 'Second';
        if ($signal == 3) $type = 'Third';

        $signals[] = 'System Alert - ' . $type . ' Signal for ' . $symbol . ' at ' . $price ;
    }
}

function getBuys( $signals, $date )
{
    // The Deb Fund - Buy $792 of YHOO at 23.21

    $sql = "select f.name, s.symbol, t.amount, t.buyprice from trades t, funds f, stocks s where t.fund = f.id and t.stock = s.id and t.buydate = '" . $date . "'";
    $rows = mysql_query ( $sql );
    while ( $cols = mysql_fetch_array ( $rows ) )
    {    
        $symbol = $cols['symbol'];
        $fund = $cols['name'];
        $price = $cols['buyprice'];
        $amount = intval( $cols['amount'] );

        $signals[] = $fund .' - Buy $' . $amount . ' of ' . $symbol . ' at ' . $price;
    }
}

function getSells( $signals, $date )
{
    // The Bob Fund - Sell IBM at 32.23

    $sql = "select f.name, s.symbol, t.sellprice from trades t, funds f, stocks s where t.fund = f.id and t.stock = s.id and t.selldate = '" . $date . "'";
    $rows = mysql_query ( $sql );
    while ( $cols = mysql_fetch_array ( $rows ) )
    {    
        $symbol = $cols['symbol'];
        $fund = $cols['name'];
        $price = $cols['sellprice'];

        $signals[] = $fund .' - Sell ' . $symbol . ' at ' . $price;
    }
}

?>