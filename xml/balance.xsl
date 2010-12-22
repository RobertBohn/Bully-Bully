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


<xsl:template name="_content" match="_content">

    <table border="0" cellspacing="1" cellpadding="4">
        <tr>
            <th colspan="2">Current Balance</th>
        </tr>
        <tr>
            <td>Cash</td>
            <td align="right"><xsl:value-of select="format-number(content/cash, '$#,###', 'usd')"/></td>
        </tr>
        <tr>
            <td>

                <xsl:if test="content/stocks != 0">
                    <xsl:element name="a">
                    <xsl:attribute name="href">?t=<xsl:value-of select="navigation/tab"/>&amp;m=11&amp;</xsl:attribute>
                    <xsl:attribute name="title">Review stocks held by the fund</xsl:attribute>Positions</xsl:element>
                </xsl:if>

                <xsl:if test="content/stocks = 0">Positions</xsl:if>

            </td>
            <td align="right"><xsl:value-of select="format-number(content/stocks, '$#,###', 'usd')"/></td>
        </tr>
        <tr>
            <td>Total</td>
            <td align="right"><xsl:value-of select="format-number(content/cash + content/stocks, '$#,###', 'usd')"/></td>
        </tr>
    </table>

</xsl:template>


</xsl:stylesheet>