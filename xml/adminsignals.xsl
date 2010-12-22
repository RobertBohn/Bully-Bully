<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:template match="/">
<xsl:apply-templates select="root"/> 
</xsl:template>

<xsl:template match="root">
    <xsl:call-template name="_layout"></xsl:call-template>
</xsl:template>

<xsl:template name="_content" match="_content">

    <xsl:for-each select="content">
    <xsl:sort select="date" order="descending"/>
        <p><xsl:value-of select="datestring"/></p>
        <ul>
            <xsl:for-each select="detail"><li><xsl:value-of select="signal"/></li></xsl:for-each>
            <xsl:if test="count(detail) = 0"><li>No Changes</li></xsl:if>
        </ul>
    </xsl:for-each>

</xsl:template>

<xsl:include href="_layout.xsl"/> 

</xsl:stylesheet>