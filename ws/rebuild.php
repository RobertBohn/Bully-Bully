<?php
header ( 'Content-type: text/xml' );
include ( '../bully.inc' ); 

$stock = $_GET[s];
$phase = $_GET[p];
$rc = '0';
$message = 'Setting Successfuly Changed.';
$db = new Database();



// Get Symbol

$sql = 'select symbol from stocks where id = ' . $stock;
$rows = mysql_query ( $sql );
$cols = mysql_fetch_array ( $rows );
$symbol = $cols['symbol'];




// Get Dates

$history = getorset( '0', 'Years of Price History', '5' );

$sql = 'select current_date() Today, date_add(current_date, INTERVAL -' . $history  . ' YEAR) Start, date_add(current_date, INTERVAL -10 DAY) Day10';
$rows = mysql_query ( $sql );
$cols = mysql_fetch_array ( $rows );
$today = $cols['Today'];
$start = $cols['Start'];
$day10 = $cols['Day10'];





switch ($phase)
{

    case '1':   // Delete Old Prices
    {
        $sql = "delete from prices where stock = " . $stock;
        if ( !mysql_query ( $sql ) )
        {
            $rc = '4';
            $message = mysql_error();
            break;
        }
        break;
    }


    case '2':   // Get New Prices
    {
        $start_month =  intval(substr($start,5,2)) - 1;
        $start_day = substr($start,8,2);
        $start_year = substr($start,0,4);

        $end_month =  intval(substr($today,5,2)) - 1;
        $end_day = substr($today,8,2);
        $end_year = substr($today,0,4);

        $url = 'http://ichart.finance.yahoo.com/table.csv?s=' . $symbol . '&a=' . $start_month . '&b=' . $start_day . '&c=' . $start_year . '&d=' . $end_month . '&e=' . $end_day . '&f=' . $end_year . '&g=d&ignore=.csv';
        if ( ($file = fopen("$url", "r")) == false )
        {
            $rc = '4';
            $message = 'Error Scrapping Prices';
            break;
        }

        fgetcsv( $file, 1000, "," );
        while ( ( $data = fgetcsv ( $file, 1000, "," ) ) !== FALSE )
        {
            $sql = "insert into prices (stock, thedate, price) values (" . $stock . ",'" . $data[0] . "'," . $data[6] . ")";
            if ( !mysql_query ( $sql ) )
            {
                $rc = '4';
                $message = mysql_error();
                break;
            }
        }
        break;
    }


    case '3':   // Get 120 Highs & Lows
    {
        $sql = "select date_add(min(thedate), INTERVAL 120 DAY) First120 from prices where stock = " . $stock;
        $rows = mysql_query ( $sql );
        $cols = mysql_fetch_array ( $rows );
        $first120 = $cols['First120'];

        $sql = "select thedate from prices where stock = " . $stock . " and thedate > '" . $first120 . "'";
        $rows = mysql_query ( $sql );
        while ( $cols = mysql_fetch_array ( $rows ) )
        {    
            $thedate = $cols['thedate'];

            $sql = "select min(price) min, max(price) max from prices where stock = " . $stock . " and thedate < '" . $thedate . "' and thedate > DATE_SUB('" . $thedate . "',INTERVAL 120 DAY)";
            $rows1 = mysql_query ( $sql );
            $cols1 = mysql_fetch_array ( $rows1 );
            $low120 = $cols1['min'];
            $high120 = $cols1['max'];

            $sql = "update prices set low120 = " . $low120 . ", high120= " . $high120 . " where stock = " . $stock . " and thedate = '" . $thedate . "'";
            if ( !mysql_query ( $sql ) )
            {
                $rc = '4';
                $message = mysql_error();
                break;
            }
        }

        break;
    }


    case '4':   // Update Price Summary
    {
        $sql = 'select thedate, price from prices where stock = ' . $stock . ' order by thedate desc limit 1';
        $rows = mysql_query ( $sql );
        $cols = mysql_fetch_array ( $rows );
        $price = $cols['price'];
        $process = $cols['thedate'];

        $sql = "update stocks set price = " . $price . ", process = '" . $process . "' where id = " . $stock;
        if ( !mysql_query ( $sql ) )
        {
            $rc = '4';
            $message = mysql_error();
            break;
        }


        $sql = "select min(price) min, max(price) max from prices where stock = " . $stock . " and thedate > DATE_SUB('" . $process . "',INTERVAL 120 DAY)";
        $rows = mysql_query ( $sql );
        $cols = mysql_fetch_array ( $rows );
        $low120 = $cols['min'];
        $high120 = $cols['max'];

        $sql = "update stocks set low120 = " . $low120 . ", high120= " . $high120 . " where id = " . $stock;
        if ( !mysql_query ( $sql ) )
        {
            $rc = '4';
            $message = mysql_error();
            break;
        }


        $sql = "select thedate, price from prices where stock = " . $stock . " and thedate >= DATE_SUB('" . $process . "',INTERVAL 10 DAY) order by thedate limit 1";
        $rows = mysql_query ( $sql );
        $cols = mysql_fetch_array ( $rows );
        $day10 = $cols['price'];

        $sql = "update stocks set day10 = " . $day10 . " where id = " . $stock;
        if ( !mysql_query ( $sql ) )
        {
            $rc = '4';
            $message = mysql_error();
            break;
        }

        break;
    }

} // end phase switch



// Build XML Document

$doc = new DomDocument ( '1.0' );
$root = $doc->createElement ( 'root' );
$root = $doc->appendChild ( $root );

$result = $doc->createElement ( 'result' );
$result = $root->appendChild ( $result );
addNode ( $doc, $result, 'code', $rc );
addNode ( $doc, $result, 'message', $message );

echo $doc->saveXML();
?>