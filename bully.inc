<?php

define ( 'DBHOST', 'XXX' );
define ( 'DBUSER', 'XXX' );
define ( 'DBPSW', 'XXX' );
define ( 'DBNAME', 'XXX' );
define ( 'PATH', 'XXX' );

class Database
{
    function Database() 
    {
        mysql_connect ( DBHOST, DBUSER, DBPSW ) or die ('sql connect error: ' . mysql_error());
        mysql_select_db( DBNAME ) or die ('sql select database error: ' . mysql_error());
    }
}

function addNode( $doc, $node, $name, $val )
{
    $child = $doc->createElement( $name );
    $child = $node->appendChild( $child );

    $value = $doc->createTextNode( $val );
    $value = $child->appendChild( $value );
}


function translateXml( $data, $style )
{
    $xp = new XsltProcessor();
  
    $xsl = new DomDocument;         // create a DOM document
    $xsl->load ( $style );          // Load the XSL stylesheet  
 
    $xp->importStylesheet ( $xsl ); // Import the XSL styelsheet into the XSLT process
  
    $xml_doc = new DomDocument;     // create a DOM document
    $xml_doc->load ( $data );       // Load the XML data
 
    if ( $html = $xp->transformToXML ( $xml_doc ) )  // transform XML into HTML using XSL file
        echo substr($html,21);
    else 
        trigger_error ( 'XSL transformation failed.', E_USER_ERROR );
}

function k($s)
{
   return htmlspecialchars(stripcslashes($s));
}

function getorset( $fund, $name, $value )
{
    $sql = "select value from settings where fund = $fund and name = '$name'";
    $rows = mysql_query ( $sql ) or die ( 'select error: ' . mysql_error());
    if ( $cols = mysql_fetch_array ( $rows ) )
    {    
        return $cols['value'];
    }

    $sql = "insert into settings ( fund, name, value ) values ( $fund, '$name', '$value' )";
    mysql_query($sql) or die("insert error:" . mysql_error());

    return $value;
}

?>