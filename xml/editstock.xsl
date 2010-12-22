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


<xsl:template name="_content" match="_content">
         
    <xsl:if test="navigation/signedin = 'yes'">

    <xsl:element name="form">
    <xsl:attribute name="action">editstock.php?d=<xsl:value-of select="content/id"/>&amp;</xsl:attribute>
    <xsl:attribute name="method">post</xsl:attribute>
         
    <table border="0" cellspacing="0" cellpadding="0"><tr><td valign="top">

	<table border="0" cellspacing="1" cellpadding="3" style="background:#ffffff;">
	    <tr>
	        <td align="right">Stock Symbol:</td>
	        <td>
                    <xsl:element name="input">
                    <xsl:attribute name="type">text</xsl:attribute>
                    <xsl:attribute name="name">symbol</xsl:attribute>
                    <xsl:attribute name="value"><xsl:value-of select="content/symbol"/></xsl:attribute>
                    </xsl:element>
                </td>
	    </tr>
	    <tr>
	        <td align="right">Company Name:</td>
	        <td>
                    <xsl:element name="input">
                    <xsl:attribute name="type">text</xsl:attribute>
                    <xsl:attribute name="name">company</xsl:attribute>
                    <xsl:attribute name="value"><xsl:value-of select="content/company"/></xsl:attribute>
                    </xsl:element>
                </td>
	    </tr>
	    <tr>
	        <td align="right">Enabled:</td>
	        <td>
                    <xsl:if test="content/enabled = 'Y'">
                        <select name="enabled">
                            <option value="Y" selected="selected">Yes</option>
                            <option value="N">No</option>
                        </select>
                    </xsl:if>

                    <xsl:if test="content/enabled != 'Y'">
                        <select name="enabled">
                            <option value="Y">Yes</option>
                            <option value="N" selected="selected">No</option>
                        </select>
                    </xsl:if>
	        </td>
	    </tr>
	    <tr>
	        <td align="right">120 Day Low:</td>
	        <td>
                    <xsl:if test="content/enabled = 'Y'"><xsl:value-of select="format-number(content/low120, '#.00', 'usd')"/></xsl:if>
                </td>
	    </tr>
	    <tr>
	        <td align="right">Current Price:</td>
	        <td>
                    <xsl:if test="content/enabled = 'Y'"><xsl:value-of select="format-number(content/price, '#.00', 'usd')"/></xsl:if>
                </td>

	    </tr>
	    <tr>
	        <td align="right">120 Day High:</td>
	        <td>
                    <xsl:if test="content/enabled = 'Y'"><xsl:value-of select="format-number(content/high120, '#.00', 'usd')"/></xsl:if>
                </td>

	    </tr>
	</table>

    </td><td style="width:15px;"></td><td valign="top">

        <ul class="backbutton">
            <li><a title="Return to the stock list page" href="javascript:history.go(-1);">Back</a></li>
        </ul>

        <br /><br />

        <ul class="button">
            <li><a title="Update Stock Information" href="#" onclick="document.forms[0].submit();">Update</a></li>
        </ul>

        <br /><br />

        <ul class="button">
            <li>
                <xsl:element name="a">
                <xsl:attribute name="title">Rebuild Historical Price Information</xsl:attribute>
                <xsl:attribute name="href">#</xsl:attribute>
                <xsl:attribute name="onclick">rebuild(<xsl:value-of select="content/id"/>);</xsl:attribute>Rebuild</xsl:element>
            </li>
        </ul>

        <br /><br />

        <p id="busy"></p>

    </td></tr></table>

    </xsl:element>

    </xsl:if>










    <xsl:if test="navigation/signedin != 'yes'">

    <table border="0" cellspacing="0" cellpadding="0"><tr><td valign="top">

	<table border="0" cellspacing="1" cellpadding="3" style="background:#ffffff;">
	    <tr>
	        <td align="right">Stock Symbol:</td>
	        <td><xsl:value-of select="content/symbol"/></td>
	    </tr>
	    <tr>
	        <td align="right">Company Name:</td>
	        <td><xsl:value-of select="content/company"/></td>
	    </tr>
	    <tr>
	        <td align="right">Enabled:</td>
	        <td>
                    <xsl:if test="content/enabled = 'Y'">Yes</xsl:if>
                    <xsl:if test="content/enabled != 'Y'">No</xsl:if>
                </td>
	    </tr>
	    <tr>
	        <td align="right">120 Day Low:</td>
	        <td>
                    <xsl:if test="content/enabled = 'Y'"><xsl:value-of select="format-number(content/low120, '#.00', 'usd')"/></xsl:if>
                </td>
	    </tr>
	    <tr>
	        <td align="right">Current Price:</td>
	        <td>
                    <xsl:if test="content/enabled = 'Y'"><xsl:value-of select="format-number(content/price, '#.00', 'usd')"/></xsl:if>
                </td>
	    </tr>
	    <tr>
	        <td align="right">120 Day High:</td>
	        <td>
                    <xsl:if test="content/enabled = 'Y'"><xsl:value-of select="format-number(content/high120, '#.00', 'usd')"/></xsl:if>
                </td>
	    </tr>
	</table>

    </td><td style="width:15px;"></td><td valign="top">

        <ul class="backbutton">
            <li><a title="Return to the stock list page" href="javascript:history.go(-1);">Back</a></li>
        </ul>

    </td></tr></table>

    </xsl:if>

    <xsl:element name="div">
    <xsl:attribute name="title">(<xsl:value-of select="content/symbol"/>) <xsl:value-of select="content/company"/></xsl:attribute>
    <xsl:attribute name="style">cursor:pointer; margin-top:15px; width:498px; height:183px; background:url(http://chart.finance.yahoo.com/c/2y/m/<xsl:value-of select="translate(content/symbol,$ucletters,$lcletters)"/>) no-repeat -15px -25px;</xsl:attribute>
    <xsl:attribute name="onclick">window.location='http://finance.yahoo.com/q?d=t&amp;s=<xsl:value-of select="content/symbol"/>';</xsl:attribute>
    </xsl:element>


</xsl:template>

<xsl:include href="_layout.xsl"/> 

</xsl:stylesheet>