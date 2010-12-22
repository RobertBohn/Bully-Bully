<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:template match="/">
<xsl:apply-templates select="root"/> 
</xsl:template>

<xsl:template match="root">
    <xsl:call-template name="_layout"></xsl:call-template>
</xsl:template>

<xsl:decimal-format name="usd" decimal-separator="." grouping-separator=","/>
<xsl:variable name="lcletters">abcdefghijklmnopqrstuvwxyz</xsl:variable>
<xsl:variable name="ucletters">ABCDEFGHIJKLMNOPQRSTUVWXYZ</xsl:variable>

<xsl:template name="formatdate">
    <xsl:param name="date"/>
    <xsl:value-of select="format-number(substring($date,6,2), '#', 'usd')" />/<xsl:value-of select="format-number(substring($date,9,2), '#', 'usd')" />/<xsl:value-of select="substring($date,1,4)" />
</xsl:template>

<xsl:template name="_content" match="_content">

    <table border="0" cellspacing="0" cellpadding="0"><tr><td valign="top">

        <table border="0" cellspacing="1" cellpadding="4">
            <tr>
                <th colspan="2">Current Position</th>
            </tr>
            <tr>
                <td>Shares Held</td>
                <td align="right">
                    <xsl:if test="navigation/signedin = 'yes'">
                        <xsl:element name="input">
                        <xsl:attribute name="id">shares</xsl:attribute>
                        <xsl:attribute name="class">editinline</xsl:attribute>
                        <xsl:attribute name="type">text</xsl:attribute>
                        <xsl:attribute name="value"><xsl:value-of select="format-number(content/amount div content/buyprice, '#', 'usd')"/></xsl:attribute>
                        </xsl:element>
                    </xsl:if>

                    <xsl:if test="navigation/signedin != 'yes'">
                        <xsl:value-of select="format-number(content/amount div content/buyprice, '#', 'usd')"/>
                    </xsl:if>
                </td>
            </tr>
            <tr>
                <td>Purchase Price</td>
                <td align="right">
                    <xsl:if test="navigation/signedin = 'yes'">
                        <xsl:element name="input">
                        <xsl:attribute name="id">price</xsl:attribute>
                        <xsl:attribute name="class">editinline</xsl:attribute>
                        <xsl:attribute name="type">text</xsl:attribute>
                        <xsl:attribute name="value"><xsl:value-of select="format-number(content/buyprice, '#.00', 'usd')"/></xsl:attribute>
                        </xsl:element>
                    </xsl:if>

                    <xsl:if test="navigation/signedin != 'yes'">
                        <xsl:value-of select="format-number(content/buyprice, '#.00', 'usd')"/>
                    </xsl:if>
                </td>
            </tr>
            <tr>
                <td>Current Price</td>
                <td align="right"><xsl:value-of select="format-number(content/price, '#.00', 'usd')"/></td>
            </tr>

            <tr>
                <td>Current Value</td>
                <td align="right"><xsl:value-of select="format-number((content/amount div content/buyprice) * content/price, '$#,###', 'usd')"/></td>
            </tr>
            <tr>
                <td>Days Held</td>
                <td align="right"><xsl:value-of select="format-number(content/days, '#', 'usd')"/></td>
            </tr>

            <xsl:if test="content/price &gt;= content/buyprice">
                <tr>
                    <td>Dollar Gain</td>
                    <td align="right"><img src="images/up.gif" alt="up" /><xsl:value-of select="format-number((content/amount div content/buyprice) * (content/price - content/buyprice), '$#,###', 'usd')"/></td>
                </tr>
                <tr>
                    <td>Percent Gain</td>
                    <td align="right"><img src="images/up.gif" alt="up" /><xsl:value-of select="format-number((content/price - content/buyprice) div content/buyprice, '#%', 'usd')"/></td>
                </tr>
            </xsl:if>

            <xsl:if test="content/price &lt; content/buyprice">
                <tr>
                    <td>Dollar Gain</td>
                    <td align="right"><img src="images/down.gif" alt="down" /><xsl:value-of select="format-number((content/amount div content/buyprice) * (content/buyprice - content/price), '$#,###', 'usd')"/></td>
                </tr>
                <tr>
                    <td>Percent Gain</td>
                    <td align="right"><img src="images/down.gif" alt="down" /><xsl:value-of select="format-number((content/buyprice - content/price) div content/buyprice, '#%', 'usd')"/></td>
                </tr>
            </xsl:if>

        </table>

    </td><td style="width:15px;"></td><td valign="top">

        <table border="0" cellspacing="1" cellpadding="4">
            <tr>
                <th colspan="3">Position History</th>
            </tr>
            <tr>
                <td>Initial Signal</td>
                <td align="center">
                    <xsl:call-template name="formatdate">
                        <xsl:with-param name="date" select="content/s1date" />
                    </xsl:call-template>
                </td>
                <td align="right"><xsl:value-of select="format-number(content/s1price, '#.00', 'usd')"/></td>
            </tr>
            <tr>
                <td>Second Signal</td>
                <td align="center">
                    <xsl:call-template name="formatdate">
                        <xsl:with-param name="date" select="content/s2date" />
                    </xsl:call-template>
                </td>
                <td align="right"><xsl:value-of select="format-number(content/s2price, '#.00', 'usd')"/></td>
            </tr>
            <tr>
                <td>Purchased</td>
                <td align="center">
                    <xsl:call-template name="formatdate">
                        <xsl:with-param name="date" select="content/buydate" />
                    </xsl:call-template>
                </td>
                <td align="right"><xsl:value-of select="format-number(content/buyprice, '#.00', 'usd')"/></td>
            </tr>
            <tr>
                <td>Current Price</td>
                <td align="center">
                    <xsl:call-template name="formatdate">
                        <xsl:with-param name="date" select="content/process" />
                    </xsl:call-template>
                </td>
                <td align="right"><xsl:value-of select="format-number(content/price, '#.00', 'usd')"/></td>
            </tr>
            <tr>
                <td>Stop Price</td>
                <td align="center"></td>
                <td align="right"><xsl:value-of select="format-number(content/stop, '#.00', 'usd')"/></td>
            </tr>
            <tr>
                <td>120 Day High</td>
                <td align="center"></td>
                <td align="right"><xsl:value-of select="format-number(content/high120, '#.00', 'usd')"/></td>
            </tr>
            <tr>
                <td>120 Day Low</td>
                <td align="center"></td>
                <td align="right"><xsl:value-of select="format-number(content/low120, '#.00', 'usd')"/></td>
            </tr>
        </table>

    </td><td style="width:15px;"></td><td valign="top">

        <ul class="backbutton">
            <li><a title="Return to the positions page" href="javascript:history.go(-1);">Back</a></li>
        </ul>

        <xsl:if test="navigation/signedin = 'yes'">
            <br /><br />
            <ul class="button">
                <li>
                    <xsl:element name="a">
                    <xsl:attribute name="title">Update Trade Information</xsl:attribute>
                    <xsl:attribute name="href">javascript:update(<xsl:value-of select="content/id"/>);</xsl:attribute>Update</xsl:element>
                </li>
            </ul>
        </xsl:if>

    </td></tr></table>

    <xsl:element name="div">
    <xsl:attribute name="title">(<xsl:value-of select="content/symbol"/>) <xsl:value-of select="content/company"/></xsl:attribute>
    <xsl:attribute name="style">cursor:pointer; margin-top:15px; width:498px; height:183px; background:url(http://chart.finance.yahoo.com/c/2y/m/<xsl:value-of select="translate(content/symbol,$ucletters,$lcletters)"/>) no-repeat -15px -25px;</xsl:attribute>
    <xsl:attribute name="onclick">window.location='http://finance.yahoo.com/q?d=t&amp;s=<xsl:value-of select="content/symbol"/>';</xsl:attribute>
    </xsl:element>

</xsl:template>

<xsl:include href="_layout.xsl"/> 

</xsl:stylesheet>