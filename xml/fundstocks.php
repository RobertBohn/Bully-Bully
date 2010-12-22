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
addNode ( $doc, $navigation, 'pagedescr', 'Review Which Stocks Are Being Tracked By The Fund' );
addNode ( $doc, $navigation, 'title', 'Bully Bully - Stocks' );
addNode ( $doc, $navigation, 'pagename', $fundname . ' - Stocks' );
addNode ( $doc, $navigation, 'page', $_GET[p] );

if ($_GET[s] == 'y')
    addNode ( $doc, $navigation, 'signedin', 'yes' );
else
    addNode ( $doc, $navigation, 'signedin', 'no' );




// build content - stock list
$sql = "select s.id, s.symbol, s.company, s.enabled, s.price, s.day10, ifnull(f.id,0) checked from stocks s left outer join fundstocks f on s.id = f.stock and f.fund = $_GET[t] where s.enabled = 'Y' order by symbol";

$i = 0;
$rows = mysql_query ( $sql ) or die ( 'select error: ' . mysql_error());
while ( $cols = mysql_fetch_array ( $rows ) )
{    
    if ($cols['checked'] != '0' || $_GET[s] == 'y')
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

        if ($cols['checked'] == '0')
            addNode ( $doc, $occ, 'checked', 'no' );
        else
            addNode ( $doc, $occ, 'checked', 'yes' );
    }
}


// Javascript

if ($_GET[s] == 'y')
{
    $js = $doc->createElement ( 'javascript' );          // <javascript>
    $js = $root->appendChild ( $js );
    addNode ( $doc, $js, 'script', 'js/fundstocks.js' );
}


echo $doc->saveXML();
?>
