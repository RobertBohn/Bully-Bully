<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:template name="_tabs" match="root">

    <div id="tabs">
         <ul>

             <!-- loop through the funds -->

             <xsl:for-each select="funds">

                 <xsl:if test="../navigation/tab = id">
                     <li id="current">
                         <xsl:element name="a">
                         <xsl:value-of select="name"/>
                         </xsl:element>
                     </li>
                 </xsl:if>

                 <xsl:if test="../navigation/tab != id">
                     <li>
                         <xsl:element name="a">
                         <xsl:attribute name="href">bully.php?t=<xsl:value-of select="id"/>&amp;m=<xsl:value-of select="../navigation/menu"/>&amp;</xsl:attribute>
                         <xsl:attribute name="title">Review <xsl:value-of select="name"/> Portfolio</xsl:attribute>
                         <xsl:value-of select="name"/>
                         </xsl:element>
                     </li>
                 </xsl:if>

             </xsl:for-each>

             <!-- administration -->

             <xsl:if test="navigation/tab = '0'">
                 <li id="current"><a>Administration</a></li>
             </xsl:if>
             <xsl:if test="navigation/tab != '0'">
                 <li><a title="Perform Administrative Functions" href="bully.php?t=0&amp;m=1&amp;">Administration</a></li>
             </xsl:if>

             <!-- home page -->

             <li><a title="Bully Bully Home Page" href="index.php">Home Page</a></li>

        </ul>
    </div>

</xsl:template>

</xsl:stylesheet>
