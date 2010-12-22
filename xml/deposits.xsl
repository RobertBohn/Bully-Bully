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
        <p>No money has been deposited into this fund.</p>  
    </xsl:if>

    <xsl:if test="count(content) &gt; 0">

        <table border="0" cellspacing="1" cellpadding="4">
            <tr>
                <th align="center" title="Transaction Date">Date</th>
                <th title="Deposit Amount">Amount</th>
            </tr>

            <xsl:for-each select="content">
            <xsl:sort select="thedate"/>
                <tr>
                    <td align="center">
                        <xsl:call-template name="formatdate">
                            <xsl:with-param name="date" select="thedate" />
                        </xsl:call-template>
                    </td>
                    <td align="right"><xsl:value-of select="format-number(amount, '$#,###', 'usd')"/></td>
                </tr>
            </xsl:for-each>

            <xsl:if test="count(content) &gt; 1">
                <tr>
                    <td align="center">Total</td>
                    <td align="right"><xsl:value-of select="format-number(sum(content/amount), '$#,###', 'usd')"/></td>
                </tr>
            </xsl:if>
        </table>

    </xsl:if>

    <xsl:if test="navigation/signedin = 'yes'">
        <table style="margin-top:15px; background:#ffffff;" border="0" cellspacing="1" cellpadding="4">
            <tr>
                <td>
                    <ul class="button">
                        <li>
                            <xsl:element name="a">
                            <xsl:attribute name="title">Enter a new deposit</xsl:attribute>
                            <xsl:attribute name="href">newdeposit.php?t=<xsl:value-of select="navigation/tab"/>&amp;</xsl:attribute>New Deposit</xsl:element>
                        </li>
                    </ul>
                </td>
            </tr>
        </table>
    </xsl:if>

</xsl:template>


</xsl:stylesheet>