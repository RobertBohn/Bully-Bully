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
addNode ( $doc, $navigation, 'menu', '7' ); 
addNode ( $doc, $navigation, 'pagedescr', "Review the System's Operational Settings" );
addNode ( $doc, $navigation, 'title', 'Bully Bully - Settings' );
addNode ( $doc, $navigation, 'pagename', 'Administration - Settings' );

if ($_GET[s] == 'y')
    addNode ( $doc, $navigation, 'signedin', 'yes' );
else
    addNode ( $doc, $navigation, 'signedin', 'no' );


// build content

$ss = $doc->createElement ( 'settings' );          // <settings>
$ss = $root->appendChild ( $ss );
addNode ( $doc, $ss, 'pricehistory', getorset( '0', 'Years of Price History', '5' ) );
addNode ( $doc, $ss, 'marketsignals', getorset( '0', 'Days of Market Signals', '3' ) );




// Javascript

if ($_GET[s] == 'y')
{
    $js = $doc->createElement ( 'javascript' );          // <javascript>
    $js = $root->appendChild ( $js );
    addNode ( $doc, $js, 'script', 'js/settings.js' );
}



echo $doc->saveXML();
?>