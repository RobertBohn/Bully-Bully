<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" xmlns="http://www.w3.org/1999/xhtml">

<xsl:import href="_tabs.xsl"/> 

<xsl:import href="_menus.xsl"/> 

<xsl:template name="_layout" match="root">

<html xml:lang="en">
<head>
    <title><xsl:value-of select="navigation/title"/></title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="description" content="Personal Stock Market Portfolio Management." />
    <meta name="robots" content="index,follow" />
    <link rel="Shortcut Icon" type="image/ico" href="favicon.ico" />
    <link href="bully.css" rel="stylesheet" type="text/css" />

    <xsl:for-each select="javascript">
        <xsl:element name="script">
        <xsl:attribute name="type">text/javascript</xsl:attribute>
        <xsl:attribute name="src"><xsl:value-of select="script"/></xsl:attribute>;</xsl:element>
    </xsl:for-each>

</head>

<xsl:element name="body">
<xsl:if test="count(javascript) != 0">
   <xsl:attribute name="onload">initialize();</xsl:attribute>
</xsl:if>

    <div id="wrapper">

        <div id="header">            
            <a href="index.php" title="Bully Bully Home Page"><img src="images/logo.gif" alt="Bully Bully Home Page" /></a>
        </div>

        <div id="tabholder">

                <xsl:call-template name="_tabs"/>

            <div id="opentab">
            
                <table border="0" cellpadding="0" cellspacing="0"><tr><td valign="top">

                    <xsl:call-template name="_menus"/>

                </td><td valign="top">

                    <h1 id="pagename"><xsl:value-of select="navigation/pagename"/></h1>
                    
                    <h2 id="pagedescr"><xsl:value-of select="navigation/pagedescr"/></h2>
                    
                    <div id="content">

                        <xsl:call-template name="_content"/>

                    </div> <!-- content -->

                </td></tr></table>
                
                <div id="footer">
                    <a href="http://validator.w3.org/check/referrer" title="Valid XHTML 1.0 Strict">XHTML</a> | <a href="http://jigsaw.w3.org/css-validator/validator?uri=http://www.bullybully.us/" title="Valid CSS Version 3">CSS</a> | <a href="http://creativecommons.org/licenses/by-sa/3.0/" title="Creative Commons License">(CC) Creative Commons</a> | <a href="http://www.robertbohn.com/" title="Website design and programming by Robert Bohn">Designed By</a>
                </div> <!-- footer -->
                
            </div> <!-- opentab -->

        </div> <!-- tabholder -->

    </div> <!-- wrapper -->

</xsl:element>

</html>

</xsl:template>

</xsl:stylesheet>
