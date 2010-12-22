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

    <xsl:element name="form">
    <xsl:attribute name="action">newdeposit.php?t=<xsl:value-of select="navigation/tab"/>&amp;</xsl:attribute>
    <xsl:attribute name="method">post</xsl:attribute>

        <table style="margin-top:15px; background:#ffffff;" border="0" cellspacing="1" cellpadding="4">
            <tr>
                <td align="right">Amount:</td>
                <td><input name="amount" type="text" /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <ul class="button">
                        <li><a title="Make a New Deposit Entry" href="javascript:document.forms[0].submit();">Enter Deposit</a></li>
                    </ul>
                </td>
            </tr>
        </table>

    </xsl:element>

</xsl:template>

</xsl:stylesheet>