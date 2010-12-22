<?php
header ( 'Content-type: text/xml' );
include ( '../bully.inc' ); 

// Variables

$phase = $_GET[p]; if ($phase == '') $phase = '1'; 
$rc = '0';
$message = 'Setting Successfuly Changed.';
$db = new Database();

// Build XML Document

$doc = new DomDocument ( '1.0' );
$root = $doc->createElement ( 'root' );
$root = $doc->appendChild ( $root );

// Process

switch ($phase)
{
    case '1':   // Get Symbols and Next Process Date
    {
        $stocks = $doc->createElement ( 'stocks' );
        $stocks = $root->appendChild ( $stocks );

        $sql = "select symbol from stocks where enabled = 'Y'";
        $rows = mysql_query ( $sql );
        while ( $cols = mysql_fetch_array ( $rows ) )
        {    
            $symbol = $cols['symbol'];
            addNode ( $doc, $stocks, 'symbol', $symbol );
        }

        $date = getorset( '0', 'Last Process Date', '2007-06-13' );
        if ( isToday( $date ) == true )
        {
            $rc = '4';
            $message = 'The system is up-to-date. No processing is required.';
        }
        else
        {
            $date = getNextDay( $date );
            addNode ( $doc, $stocks, 'nextdate', $date );
            if ( isToday( $date ) == true )
            {
                if ( isMarketOpen() == true )
                {
                    $rc = '4';
                    $message = 'The Stock Market is Still Open.';
                }
            }
        }

        break;
    }

    case '2':   // Update One Stock's Prices For One Day
    {
        // Get Price

        $symbol = $_GET[s];
        $date = $_GET[d];
        $price = getPrice( $symbol, $date );
        if ( $price == -1 )
        {
            $rc = '4';
            $message = 'Error getting price for ' . $symbol . ' on ' . $date . '.';
            break;
        }

        // Get Stock ID

        $sql = "select id from stocks where symbol = '" . $symbol . "'";
        $rows = mysql_query ( $sql );
        $cols = mysql_fetch_array ( $rows );
        $stock = $cols['id'];

        // Delete Old Price

        $sql = "delete from prices where stock = " . $stock . " and thedate = '" . $date . "'";
        if ( !mysql_query ( $sql ) )
        {
            $rc = '4';
            $message = mysql_error();
            break;
        }

        // Insert Price

        $sql = "insert into prices (stock, thedate, price) values (" . $stock . ",'" . $date . "'," . $price . ")";
        if ( !mysql_query ( $sql ) )
        {
            $rc = '4';
            $message = mysql_error();
            break;
        }

        // Update High & Low 120

        $sql = "select min(price) min, max(price) max from prices where stock = " . $stock . " and thedate < '" . $date . "' and thedate > DATE_SUB('" . $date . "',INTERVAL 120 DAY)";
        $rows = mysql_query ( $sql );
        $cols = mysql_fetch_array ( $rows );
        $low120 = $cols['min'];
        $high120 = $cols['max'];

        $sql = "update prices set low120 = " . $low120 . ", high120= " . $high120 . " where stock = " . $stock . " and thedate = '" . $date . "'";
        if ( !mysql_query ( $sql ) )
        {
            $rc = '4';
            $message = mysql_error();
            break;
        }

        // Update Price Summary

        $sql = "select thedate, price from prices where stock = " . $stock . " and thedate >= DATE_SUB('" . $date . "',INTERVAL 10 DAY) order by thedate limit 1";
        $rows = mysql_query ( $sql );
        $cols = mysql_fetch_array ( $rows );
        $day10 = $cols['price'];

        $sql = "update stocks set price = " . $price . ", process = '" . $date . "', low120 = " . $low120 . ", high120= " . $high120 . ", day10 = " . $day10 . " where id = " . $stock;
        if ( !mysql_query ( $sql ) )
        {
            $rc = '4';
            $message = mysql_error();
            break;
        }

        break;
    }

    case '3':   // Set 'Last Process' Settings
    {
        $date = $_GET[d];
        $sql = "update settings set value = '$date' where fund = 0 and name = 'Last Process Date'";
        if ( !mysql_query ( $sql ) )
        {
            $rc = '4';
            $message = mysql_error();
        }
        break;
    }

    case '4':   // Handle Signals
    {
        $date = $_GET[d];

        if ( getSignals( $date ) == false )
        {
            $rc = '4';
            $message = "Error getting signals.";
            break;
        }

        cleanSignals();

        if ( getBuys( $date ) == false )
        {
            $rc = '4';
            $message = "Error getting buys.";
            break;
        }

        if ( getSells( $date ) == false )
        {
            $rc = '4';
            $message = "Error getting sells.";
            break;
        }

        break;
    }
}

$result = $doc->createElement ( 'result' );
$result = $root->appendChild ( $result );
addNode ( $doc, $result, 'code', $rc );
addNode ( $doc, $result, 'message', $message );

echo $doc->saveXML();



// ------------------ Functions -----------------------------------------------------------------

function cleanSignals()
{
    $sql = "select stock, count(*) from signals group by stock having count(*) > 3";
    $rows = mysql_query ( $sql );
    while ( $cols = mysql_fetch_array ( $rows ) )
    {
        $stock = $cols['stock'];

        $sql = "select thedate from signals where stock = " . $stock . " order by thedate limit 1 offset 3";
        $rows1 = mysql_query ( $sql );
        if ( $cols1 = mysql_fetch_array ( $rows1 ) )
        {
            $thedate = $cols1['thedate'];

            $sql = "delete from signals where stock = " . $stock . " and thedate >= '" . $thedate . "'";
            mysql_query($sql);
        }
    }
}

function getSells( $date )
{
    $sql = "select t.id, t.fund, t.stock, p.price from trades t, prices p where t.stock = p.stock and p.thedate = '" . $date . "' and (p.price < t.stop or p.price < p.low120) and t.selldate is null";
    $rows1 = mysql_query ( $sql );
    while ( $cols1 = mysql_fetch_array ( $rows1 ) )
    {
        $id = $cols1['id'];
        $fund = $cols1['fund'];
        $stock = $cols1['stock'];
        $price = $cols1['price'];

        $sql = "update trades set selldate = '" . $date . "', sellprice = " . $price . " where id = " . $id;
        if ( !mysql_query ( $sql ) ) return 0;

        $sql = "delete from signals where stock = " . $stock;
        if ( !mysql_query ( $sql ) ) return 0;
    }

    $sql = "delete from signals where stock in (select stock from prices where thedate = '" . $date . "' and price < low120)";
    if ( !mysql_query ( $sql ) ) return 0;

    return 1;
}

function getBuys( $date )
{
    // Get 3rd High Signals

    $sql = "select stock, count(*), max(thedate), max(price) price from signals group by stock having count(*) = 3 and max(thedate) = '" . $date . "'";
    $rows1 = mysql_query ( $sql );
    while ( $cols1 = mysql_fetch_array ( $rows1 ) )
    {
        // Get Funds That Track it

        $stock = $cols1['stock'];
        $price = $cols1['price'];
        $sql = "select fund from fundstocks where stock = " . $stock;
        $rows2 = mysql_query ( $sql );
        while ( $cols2 = mysql_fetch_array ( $rows2 ) )
        {    
            $fund = $cols2['fund'];

            // Get Signal Dates & Prices

            $sql = "select thedate, price from signals where stock = " . $stock . " order by thedate";
            $rows = mysql_query ( $sql );

            $cols = mysql_fetch_array ( $rows );
            $s1date = $cols['thedate'];
            $s1price = $cols['price'];

            $cols = mysql_fetch_array ( $rows );
            $s2date = $cols['thedate'];
            $s2price = $cols['price'];

            // Get High120 & Low120

            $sql = "select low120, high120 from prices where stock = " . $stock . " and thedate = '" . $date . "'";
            $rows = mysql_query ( $sql );
            $cols = mysql_fetch_array ( $rows );
            $low120 = $cols['low120'];
            $high120 = $cols['high120'];
            $stop = $low120 + (($price - $low120) * 0.5); 

            // Get Deposits

            $sql = "select ifnull(sum(amount),0) deposits from deposits where fund = " . $fund . " and thedate < '" . $date . "'";
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
               
            if ($amount > 0)
            {
                $sql = "insert into trades ( fund, stock, s1date, s1price, s2date, s2price, buydate, buyprice, amount, stop ) values ( " . $fund . ", " . $stock . ", '" . $s1date . "', " . $s1price . ", '" . $s2date . "', " . $s2price . ", '" . $date . "', " . $price . ", " . $amount . ", " . $stop . " )";
                if ( !mysql_query ( $sql ) ) return 0;
            }
        }
    }
    return 1;
}

function getSignals( $date )
{
    // Delete Old Signals

    $sql = "delete from signals where thedate = '" . $date . "'";
    if ( !mysql_query ( $sql ) ) return 0;

    // Insert New High Signals

    $sql = "insert into signals (stock, thedate, price) select stock, thedate, price from prices where price > high120 and thedate = '" . $date . "'";
    if ( !mysql_query ( $sql ) ) return 0;

    // Delete New Low Signals

    $sql = "delete from signals where stock in ( select stock from prices where price < low120 and thedate = '" . $date . "' )";
    if ( !mysql_query ( $sql ) ) return 0;

    return 1;
}

function getNextDay( $date )
{
    $sql = "select date_add('" . $date . "', INTERVAL 1 DAY) tomorrow";
    $rows = mysql_query ( $sql );
    $cols = mysql_fetch_array ( $rows );
    $tomorrow = $cols['tomorrow'];

    return $tomorrow;
}

function isToday( $date )
{
    $sql = 'select current_date() today';
    $rows = mysql_query ( $sql );
    $cols = mysql_fetch_array ( $rows );
    $today = $cols['today'];

    if ($date == $today) return 1;
    return 0;
}

// Return -1 on error,  price otherwise
function getPrice( $symbol, $date )
{
    if ( isToday( $date ) == true )
    {
        $url = 'http://finance.yahoo.com/q/ecn?s=' . $symbol;
        if ( ($file = fopen($url, "r")) == false ) return -1;

        $r = "";
        do 
        {
            $data = fread( $file, 8192 );
            $r .= $data;
        }
        while(strlen($data) != 0);   

        $idx = strpos ( $r, 'Last Trade:', 0 );
        $idx = strpos ( $r, '<b>', $idx );
        $end = strpos ( $r, '</b>', $idx );

        $val = substr ( $r, $idx + 3, $end - $idx - 3 );

        if (is_numeric($val) == false) return -1;

        return $val;
    }
    else
    {
        $mm =  intval(substr($date,5,2)) - 1;
        $dd = substr($date,8,2);
        $yyyy = substr($date,0,4);

        $url = 'http://finance.yahoo.com/q/hp?s=' . $symbol . '&a=' . $mm . '&b=' . $dd . '&c=' . $yyyy . '&d=' . $mm . '&e=' . $dd . '&f=' . $yyyy . '&g=d';
        if ( ($file = fopen($url, "r")) == false ) return -1;

        $r = "";
        do 
        {
            $data = fread( $file, 8192 );
            $r .= $data;
        }
        while(strlen($data) != 0);   

        $idx = strpos ( $r, '>Adj Close*<', 0 );
        $idx = strpos ( $r, '<td class=', $idx );
        $idx = strpos ( $r, '<td class=', $idx + 1 );
        $idx = strpos ( $r, '<td class=', $idx + 1 );
        $idx = strpos ( $r, '<td class=', $idx + 1 );
        $idx = strpos ( $r, '<td class=', $idx + 1 );
        $idx = strpos ( $r, '<td class=', $idx + 1 );
        $idx = strpos ( $r, '<td class=', $idx + 1 );
        $idx = strpos ( $r, '>', $idx );

        $end = strpos ( $r, '</td>', $idx );

        $val = substr ( $r, $idx + 1, $end - $idx - 1 );

        if (is_numeric($val) == false) return -1;

        return $val;
    }
}

// Return -1 on error,   0 if Closed,   1 if Open
function isMarketOpen()
{
    if ( ($file = fopen("http://finance.yahoo.com/q?s=ibm", "r")) == false ) return -1;

    $r = "";
    do 
    {
        $data = fread( $file, 8192 );
        $r .= $data;
    }
    while(strlen($data) != 0);   

    if ( strstr( $r, " - U.S. Markets  close in " ) == false ) return 0;
    return 1;
}

?>