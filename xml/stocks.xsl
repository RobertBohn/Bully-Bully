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
        <p>There are no stocks in the system.</p>  
    </xsl:if>



    <xsl:if test="count(content) &gt; 0">

    <table border="0" cellspacing="1" cellpadding="4">
        <tr>
            <th title="Stock Symbol">Symbol</th>
            <th title="Company Name">Company</th>
            <th title="Last Price">Price</th>
            <th title="10 Day Percentage Gain">10 Day</th>
        </tr>

        <xsl:for-each select="content">
        <xsl:sort select="line"/>

        <xsl:if test="line &gt;= ((../navigation/page) - 1) * 10">
        <xsl:if test="line &lt; ../navigation/page * 10">

            <tr>
                <td>
                    <xsl:element name="a">
                    <xsl:attribute name="href">editstock.php?d=<xsl:value-of select="id"/>&amp;</xsl:attribute>
                    <xsl:attribute name="title">View Detail on <xsl:value-of select="company"/></xsl:attribute>
                    <xsl:value-of select="symbol"/>
                    </xsl:element>
                </td>
                <td><xsl:value-of select="company"/></td>

                <xsl:if test="enabled = 'Y'">
                    <td align="right"><xsl:value-of select="format-number(price, '#.00', 'usd')"/></td>


                    <xsl:if test="price &gt;= day10">
                        <td align="right"><img src="images/up.gif" alt="up" /><xsl:value-of select="format-number((price - day10) div price, '#%', 'usd')"/></td>
                    </xsl:if>

                    <xsl:if test="price &lt; day10">
                        <td align="right"><img src="images/down.gif" alt="down" /><xsl:value-of select="format-number((day10 - price) div price, '#%', 'usd')"/></td>
                    </xsl:if>
                </xsl:if>

                <xsl:if test="enabled != 'Y'">
                    <td></td>
                    <td></td>
                </xsl:if>
            </tr>

        </xsl:if>
        </xsl:if>

        </xsl:for-each>

    </table>

    <!-- Paging -->

    <xsl:if test="count(content) &gt; 10"><p class="paging">page (<xsl:text> </xsl:text><xsl:for-each select="content"><xsl:sort select="line"/>
        <xsl:if test="line mod 10 = 0">
            <xsl:if test="line != 0"><xsl:text> </xsl:text></xsl:if>

            <xsl:if test="../navigation/page != (line div 10) + 1">
                <xsl:element name="a">
                <xsl:attribute name="href">?t=0&amp;m=3&amp;p=<xsl:value-of select="(line div 10) + 1"/>&amp;</xsl:attribute>
                <xsl:value-of select="(line div 10) + 1"/>
            </xsl:element>                      
                        
            </xsl:if>
                <xsl:if test="../navigation/page = (line div 10) + 1">
                    <xsl:value-of select="../navigation/page"/>
                </xsl:if>
            </xsl:if>
        </xsl:for-each><xsl:text> </xsl:text>)</p>
    </xsl:if>

    </xsl:if>



    <xsl:if test="navigation/signedin = 'yes'">
        <ul class="button" style="margin-top:10px;">
            <li><a title="Add a new stock to be tracked" href="newstock.php">Add Stock</a></li>
        </ul>
    </xsl:if>

</xsl:template>


</xsl:stylesheet>