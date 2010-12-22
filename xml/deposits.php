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
addNode ( $doc, $navigation, 'pagedescr', "Review The Fund's Deposits And Withdrawals" );
addNode ( $doc, $navigation, 'title', 'Bully Bully - Deposits' );
addNode ( $doc, $navigation, 'pagename', $fundname . ' - Deposits' );

if ($_GET[s] == 'y')
    addNode ( $doc, $navigation, 'signedin', 'yes' );
else
    addNode ( $doc, $navigation, 'signedin', 'no' );


// build content - deposits

$sql = "select thedate, amount from deposits where fund = " . $_GET[t];

$rows = mysql_query ( $sql ) or die ( 'select error: ' . mysql_error());
while ( $cols = mysql_fetch_array ( $rows ) )
{    
    $occ = $doc->createElement ( 'content' );        // <content>
    $occ = $root->appendChild ( $occ );

    addNode ( $doc, $occ, 'thedate', $cols['thedate'] );
    addNode ( $doc, $occ, 'amount', $cols['amount'] );
}



echo $doc->saveXML();
?>
