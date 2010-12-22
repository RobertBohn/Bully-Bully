<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:include href="_layout.xsl"/> 

<xsl:template match="/">
<xsl:apply-templates select="root"/> 
</xsl:template>

<xsl:template match="root">
    <xsl:call-template name="_layout"></xsl:call-template>
</xsl:template>

<xsl:decimal-format name="usd" decimal-separator="." grouping-separator=","/>

<xsl:template name="formatdate">
    <xsl:param name="date"/>
    <xsl:value-of select="format-number(substring($date,6,2), '#', 'usd')" />/<xsl:value-of select="format-number(substring($date,9,2), '#', 'usd')" />/<xsl:value-of select="substring($date,1,4)" />
</xsl:template>

<xsl:template name="_content" match="_content">

    <xsl:if test="count(content) = 0">
        <p>No stocks tracked by this fund are nearing a buy signal.</p>
    </xsl:if>

    <xsl:if test="count(content) &gt; 0">

        <table border="0" cellspacing="1" cellpadding="4">
            <tr>
                <th title="Stock Symbol">Symbol</th>
                <th align="center" title="Signal Date">Date</th>
                <th title="Number of Signals">Signals</th>
                <th title="High Price" align="center">High</th>
                <th title="Last Price" align="center">Last</th>
            </tr>

            <xsl:for-each select="content">
            <xsl:sort select="stock"/>

                <tr>
                    <td>
                        <xsl:element name="a">
                        <xsl:attribute name="href">?t=<xsl:value-of select="../navigation/tab"/>&amp;m=23&amp;d=<xsl:value-of select="stock"/>&amp;</xsl:attribute>
                        <xsl:attribute name="title"><xsl:value-of select="company"/></xsl:attribute><xsl:value-of select="symbol"/></xsl:element>
                    </td>
                    <td align="center"><xsl:call-template name="formatdate"><xsl:with-param name="date" select="date" /></xsl:call-template></td>
                    <td align="center"><xsl:value-of select="signals"/></td>
                    <td align="right"><xsl:value-of select="format-number(high, '#.00', 'usd')"/></td>
                    <td align="right"><xsl:value-of select="format-number(last, '#.00', 'usd')"/></td>
                </tr>

            </xsl:for-each>

        </table>

    </xsl:if>

</xsl:template>

</xsl:stylesheet>