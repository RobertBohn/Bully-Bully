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

    <table border="0" cellspacing="1" cellpadding="4" style="background:#ffffff;">
        <tr>
            <td>
                <xsl:element name="select">
                    <xsl:attribute name="id">pricehistory</xsl:attribute>
                    <xsl:if test="navigation/signedin != 'yes'">
                        <xsl:attribute name="disabled">disabled</xsl:attribute>
                    </xsl:if>  
                    <xsl:if test="navigation/signedin = 'yes'">
                        <xsl:attribute name="onchange">setting(1);</xsl:attribute>
                    </xsl:if>  

                    <xsl:element name="option"><xsl:if test="settings/pricehistory = 5"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>5</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/pricehistory = 6"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>6</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/pricehistory = 7"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>7</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/pricehistory = 8"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>8</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/pricehistory = 9"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>9</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/pricehistory = 10"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>10</xsl:element>
                </xsl:element>
            </td>
            <td>Years of price history to download for new stocks.</td>
        </tr>
        <tr>
            <td>
                <xsl:element name="select">
                    <xsl:attribute name="id">marketsignals</xsl:attribute>
                    <xsl:if test="navigation/signedin != 'yes'">
                        <xsl:attribute name="disabled">disabled</xsl:attribute>
                    </xsl:if>
                    <xsl:if test="navigation/signedin = 'yes'">
                        <xsl:attribute name="onchange">setting(2);</xsl:attribute>
                    </xsl:if>
                    <xsl:element name="option"><xsl:if test="settings/marketsignals = 0"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>all</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/marketsignals = 1"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>1</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/marketsignals = 2"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>2</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/marketsignals = 3"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>3</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/marketsignals = 4"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>4</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/marketsignals = 5"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>5</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/marketsignals = 6"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>6</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/marketsignals = 7"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>7</xsl:element>
                </xsl:element>
            </td>
            <td>Days of market signals to display.</td>
        </tr>
    </table>

</xsl:template>

<xsl:include href="_layout.xsl"/> 

</xsl:stylesheet>