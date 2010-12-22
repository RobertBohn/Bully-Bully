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

    <table border="0" cellspacing="1" cellpadding="4">
        <tr>
            <th title="The Name of the Fund">Fund Name</th>
            <th title="Current Cash Holdings">Cash</th>
            <th title="Current Stock Holdings">Stocks</th>
            <th title="Current Total Value">Total</th>
        </tr>

        <xsl:for-each select="funds">
        <xsl:sort select="id" data-type="number"/>
            <tr>
                <td>
                    <xsl:element name="a">
                    <xsl:attribute name="href">?t=<xsl:value-of select="id"/>&amp;m=10&amp;</xsl:attribute>
                    <xsl:attribute name="title">Review <xsl:value-of select="name"/> Portfolio</xsl:attribute>
                    <xsl:value-of select="name"/>
                    </xsl:element>
                </td>
                <td align="right"><xsl:value-of select="format-number(cash, '$#,###', 'usd')"/></td>
                <td align="right"><xsl:value-of select="format-number(stocks, '$#,###', 'usd')"/></td>
                <td align="right"><xsl:value-of select="format-number(cash + stocks, '$#,###', 'usd')"/></td>
            </tr>
        </xsl:for-each>

        <xsl:if test="count(funds) &gt; 1">
            <tr>
                <td>Total</td>
                <td align="right"><xsl:value-of select="format-number(sum(funds/cash), '$#,###', 'usd')"/></td>
                <td align="right"><xsl:value-of select="format-number(sum(funds/stocks), '$#,###', 'usd')"/></td>
                <td align="right"><xsl:value-of select="format-number(sum(funds/cash) + sum(funds/stocks), '$#,###', 'usd')"/></td>
            </tr>
        </xsl:if>

    </table> 

    <xsl:if test="navigation/signedin = 'yes'">
        <table style="margin-top:15px; background:#ffffff;" border="0" cellspacing="1" cellpadding="4">
            <tr>
                <td>
                    <ul class="button">
                        <li><a title="Create a New Fund" href="newfund.php">New Fund</a></li>
                    </ul>
                </td>
            </tr>
        </table>
    </xsl:if>

</xsl:template>


<xsl:include href="_layout.xsl"/> 

</xsl:stylesheet>