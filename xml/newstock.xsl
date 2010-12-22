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

    <form action="newstock.php" method="post">
    <table border="0" cellspacing="1" cellpadding="4" style="background:#ffffff;">
        <tr>
            <td align="right">Symbol:</td>
            <td><input name="symbol" type="text" /></td>
        </tr>
        <tr>
            <td align="right">Company:</td>
            <td><input name="company" type="text" /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <ul class="button">
                    <li><a title="Add a new Stock to be tracked" href="javascript:document.forms[0].submit();">Add</a></li>
                </ul>
            </td>
        </tr>
    </table>
    </form>

</xsl:template>

<xsl:include href="_layout.xsl"/> 

</xsl:stylesheet>