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


    <xsl:if test="count(content) = 0">
        <p>There is no history for this fund.</p>  
    </xsl:if>

    <xsl:if test="count(content) &gt; 0">

        <table border="0" cellspacing="1" cellpadding="4">
            <tr>
                <th title="Year">Year</th>
                <th title="Money Deposited in the Fund">Deposits</th>
                <th title="Money Earned by the Fund">Earnings</th>
                <th title="Rate of Return">Return</th>
                <th title="Year End Balance">Balance</th>
            </tr>

            <xsl:for-each select="content">
            <xsl:sort select="year"/> 

                <tr>
                    <td><xsl:value-of select="year"/></td>
                    <td align="right"><xsl:value-of select="format-number(deposits, '$#,###', 'usd')"/></td>

                    <xsl:if test="earnings &gt;= 0">
                        <td align="right"><img src="images/up.gif" alt="up" /><xsl:value-of select="format-number(earnings, '$#,###', 'usd')"/></td>
                    </xsl:if>

                    <xsl:if test="earnings &lt; 0">
                        <td align="right"><img src="images/down.gif" alt="down" /><xsl:value-of select="format-number((0 - earnings), '$#,###', 'usd')"/></td>
                    </xsl:if>

                    <xsl:if test="return &gt;= 0">
                        <td align="right"><img src="images/up.gif" alt="up" /><xsl:value-of select="return"/>%</td>
                    </xsl:if>

                    <xsl:if test="return &lt; 0">
                        <td align="right"><img src="images/down.gif" alt="down" /><xsl:value-of select="return"/>%</td>
                    </xsl:if>

                    <td align="right"><xsl:value-of select="format-number(balance, '$#,###', 'usd')"/></td>
                </tr>

            </xsl:for-each>

            <xsl:if test="count(content) &gt; 1">

                <tr>
                    <td><xsl:value-of select="totals/year"/></td>
                    <td align="right"><xsl:value-of select="format-number(totals/deposits, '$#,###', 'usd')"/></td>

                    <xsl:if test="totals/earnings &gt;= 0">
                        <td align="right"><img src="images/up.gif" alt="up" /><xsl:value-of select="format-number(totals/earnings, '$#,###', 'usd')"/></td>
                    </xsl:if>

                    <xsl:if test="totals/earnings &lt; 0">
                        <td align="right"><img src="images/down.gif" alt="down" /><xsl:value-of select="format-number((0 - totals/earnings), '$#,###', 'usd')"/></td>
                    </xsl:if>

                    <xsl:if test="totals/return &gt;= 0">
                        <td align="right"><img src="images/up.gif" alt="up" /><xsl:value-of select="totals/return"/>%</td>
                    </xsl:if>

                    <xsl:if test="totals/return &lt; 0">
                        <td align="right"><img src="images/down.gif" alt="down" /><xsl:value-of select="totals/return"/>%</td>
                    </xsl:if>

                    <td align="right"><xsl:value-of select="format-number(totals/balance, '$#,###', 'usd')"/></td>
                </tr>

            </xsl:if>

        </table>

    </xsl:if>

</xsl:template>

</xsl:stylesheet>