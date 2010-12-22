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

    <form action="newfund.php" method="post">
    <table style="margin-top:15px; background:#ffffff;" border="0" cellspacing="1" cellpadding="4">
        <tr>
            <td align="right">Fund Name:</td>
            <td><input name="name" type="text" /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <ul class="button">
                    <li><a title="Create a New Fund" href="javascript:document.forms[0].submit();">Create Fund</a></li>
                </ul>
            </td>
        </tr>
    </table>
    </form>

</xsl:template>

<xsl:include href="_layout.xsl"/> 

</xsl:stylesheet>