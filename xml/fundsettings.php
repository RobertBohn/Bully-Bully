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
addNode ( $doc, $navigation, 'pagedescr', "Review the Fund's Operational Settings" );
addNode ( $doc, $navigation, 'title', 'Bully Bully - Settings' );
addNode ( $doc, $navigation, 'pagename', $fundname . ' - Settings' );

if ($_GET[s] == 'y')
    addNode ( $doc, $navigation, 'signedin', 'yes' );
else
    addNode ( $doc, $navigation, 'signedin', 'no' );






// build content

$ss = $doc->createElement ( 'settings' );          // <settings>
$ss = $root->appendChild ( $ss );
addNode ( $doc, $ss, 'risk', getorset( $_GET[t], 'Percentage of Equity Risked Per Trade', '2' ) );
addNode ( $doc, $ss, 'positions', getorset( $_GET[t], 'Number of Concurrent Open Positions', '15' ) );
addNode ( $doc, $ss, 'maxprice', getorset( $_GET[t], 'Maximum Price Per Share', '30' ) );




// Javascript

if ($_GET[s] == 'y')
{
    $js = $doc->createElement ( 'javascript' );          // <javascript>
    $js = $root->appendChild ( $js );
    addNode ( $doc, $js, 'script', 'js/fundsettings.js' );
}








echo $doc->saveXML();
?>
