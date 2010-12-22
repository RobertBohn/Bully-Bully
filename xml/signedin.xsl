<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:template match="/">
<xsl:apply-templates select="root"/> 
</xsl:template>

<xsl:template match="root">
    <xsl:call-template name="_layout"></xsl:call-template>
</xsl:template>


<xsl:decimal-format name="usd" decimal-separator="." grouping-separator=","/>

<xsl:template name="_content" match="_content">

    <p>Successfully signed in as Administrator.</p>  

</xsl:template>


<xsl:include href="_layout.xsl"/> 

</xsl:stylesheet>