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
                    <xsl:attribute name="id">risk</xsl:attribute>
                    <xsl:if test="navigation/signedin != 'yes'">
                        <xsl:attribute name="disabled">disabled</xsl:attribute>
                    </xsl:if>  
                    <xsl:if test="navigation/signedin = 'yes'">
                        <xsl:attribute name="onchange">setting(<xsl:value-of select="navigation/tab"/>,10);</xsl:attribute>
                    </xsl:if>  

                    <xsl:element name="option"><xsl:if test="settings/risk = 1"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>1</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/risk = 2"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>2</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/risk = 3"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>3</xsl:element>
                </xsl:element>
            </td>
            <td>Percentage of equity risked per trade.</td>
        </tr>
        <tr>
            <td>
                <xsl:element name="select">
                    <xsl:attribute name="id">positions</xsl:attribute>
                    <xsl:if test="navigation/signedin != 'yes'">
                        <xsl:attribute name="disabled">disabled</xsl:attribute>
                    </xsl:if>  
                    <xsl:if test="navigation/signedin = 'yes'">
                        <xsl:attribute name="onchange">setting(<xsl:value-of select="navigation/tab"/>,11);</xsl:attribute>
                    </xsl:if>  

                    <xsl:element name="option"><xsl:if test="settings/positions = 10"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>10</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/positions = 15"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>15</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/positions = 20"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>20</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/positions = 25"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>25</xsl:element>
                </xsl:element>
            </td>
            <td>Number of concurrent open positions.</td>
        </tr>
        <tr>
            <td>
                <xsl:element name="select">
                    <xsl:attribute name="id">maxprice</xsl:attribute>
                    <xsl:if test="navigation/signedin != 'yes'">
                        <xsl:attribute name="disabled">disabled</xsl:attribute>
                    </xsl:if>  
                    <xsl:if test="navigation/signedin = 'yes'">
                        <xsl:attribute name="onchange">setting(<xsl:value-of select="navigation/tab"/>,12);</xsl:attribute>
                    </xsl:if>  

                    <xsl:element name="option"><xsl:if test="settings/maxprice = 0"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>none</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/maxprice = 25"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>25</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/maxprice = 30"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>30</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/maxprice = 35"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>35</xsl:element>
                    <xsl:element name="option"><xsl:if test="settings/maxprice = 40"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>40</xsl:element>
                </xsl:element>
            </td>
            <td>Maximum price per share.</td>
        </tr>
    </table>

</xsl:template>

<xsl:include href="_layout.xsl"/> 

</xsl:stylesheet>