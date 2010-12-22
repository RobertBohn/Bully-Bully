<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:template match="/">
<xsl:apply-templates select="root"/> 
</xsl:template>

<xsl:template match="root">
    <xsl:call-template name="_layout"></xsl:call-template>
</xsl:template>

<xsl:template name="_content" match="_content">

    <xsl:if test="navigation/signedin != 'yes'">
        <p>You must be signed in to access this functionality.</p>  
    </xsl:if>

    <xsl:if test="navigation/signedin = 'yes'">

        <table border="0" cellspacing="1" cellpadding="4" style="background:#ffffff;">
            <tr>
                <td>
                    <ul class="button">
                        <li><a title="Backup" href="javascript:process();">Backup</a></li>
                    </ul>
                </td>
            </tr>
        </table>

        <table border="0" cellspacing="1" cellpadding="4" style="background:#ffffff;">
            <tr>
                <td>
                    <p id="status"></p>
               </td>
           </tr>
       </table>
 
    </xsl:if>

</xsl:template>

<xsl:include href="_layout.xsl"/> 

</xsl:stylesheet>