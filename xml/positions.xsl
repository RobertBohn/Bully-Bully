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
        <p>This fund is not holding any stocks.</p>  
    </xsl:if>

    <xsl:if test="count(content) &gt; 0">

        <table border="0" cellspacing="1" cellpadding="4">
            <tr>
                <th title="Stock Symbol">Symbol</th>
                <th align="center" title="Date Purchased">Bought</th>
                <th title="Number of Days Held">Days</th>
                <th title="Number of Shares Owned">Shares</th>
                <th title="Purchase Price" align="center">Paid</th>
                <th title="Current Price" align="center">Last</th>
                <th title="Stop Price" align="center">Stop</th>
                <th title="Dollar Gain">Gain</th>
                <th title="Percentage Gain">Return</th>
            </tr>

            <xsl:for-each select="content">
            <xsl:sort select="buydate"/>

                <tr>
                    <td>
                        <xsl:element name="a">
                        <xsl:attribute name="href">?t=<xsl:value-of select="../navigation/tab"/>&amp;m=21&amp;d=<xsl:value-of select="id"/>&amp;</xsl:attribute>
                        <xsl:attribute name="title">View Detail on <xsl:value-of select="company"/></xsl:attribute>
                        <xsl:value-of select="symbol"/>
                        </xsl:element>
                    </td>

                    <td align="center">
                        <xsl:call-template name="formatdate">
                            <xsl:with-param name="date" select="buydate" />
                        </xsl:call-template>
                    </td>

                    <td align="right"><xsl:value-of select="days"/></td>
                    <td align="right"><xsl:value-of select="format-number(shares, '#', 'usd')"/></td>
                    <td align="right"><xsl:value-of select="format-number(buyprice, '#.00', 'usd')"/></td>
                    <td align="right"><xsl:value-of select="format-number(price, '#.00', 'usd')"/></td>
                    <td align="right"><xsl:value-of select="format-number(stop, '#.00', 'usd')"/></td>

                    <xsl:if test="profit &gt;= 0">
                        <td align="right"><img src="images/up.gif" alt="up" /><xsl:value-of select="format-number(profit, '$#,###', 'usd')"/></td>    
                        <td align="right"><img src="images/up.gif" alt="up" /><xsl:value-of select="format-number((profit) div amount, '#%', 'usd')"/></td>
                    </xsl:if>

                    <xsl:if test="profit &lt; 0">
                        <td align="right"><img src="images/down.gif" alt="down" /><xsl:value-of select="format-number((0 - profit), '$#,###', 'usd')"/></td>    
                        <td align="right"><img src="images/down.gif" alt="down" /><xsl:value-of select="format-number((0 - profit) div amount, '#%', 'usd')"/></td>
                    </xsl:if>
                </tr>

            </xsl:for-each>

            <xsl:if test="count(content) &gt; 1">
                <tr>
                    <td colspan="7" align="right">Total</td>

                    <xsl:if test="sum(content/profit) &gt;= 0">
                        <td align="right"><img src="images/up.gif" alt="up" /><xsl:value-of select="format-number(sum(content/profit), '$#,###', 'usd')"/></td>    
                        <td align="right"><img src="images/up.gif" alt="up" /><xsl:value-of select="format-number((sum(content/profit)) div sum(content/amount), '#%', 'usd')"/></td>
                    </xsl:if>

                    <xsl:if test="sum(content/profit) &lt; 0">
                        <td align="right"><img src="images/down.gif" alt="down" /><xsl:value-of select="format-number((0 - sum(content/profit)), '$#,###', 'usd')"/></td>    
                        <td align="right"><img src="images/down.gif" alt="down" /><xsl:value-of select="format-number((0 - sum(content/profit)) div sum(content/amount), '#%', 'usd')"/></td>
                    </xsl:if>  
                 </tr>
            </xsl:if>

        </table>

    </xsl:if>

</xsl:template>

</xsl:stylesheet>