<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">

<xsl:template name="_menus" match="root">


    <div id="menu">
        <ul>



            <!-- Administration Menus -->

            <xsl:if test="navigation/tab = '0'">

                <!-- Funds -->

                <xsl:if test="navigation/menu = '1'">
                    <li id="selected"><a>Funds</a></li>
                </xsl:if>

                <xsl:if test="navigation/menu != '1'">
                    <li><a title="Review the individual funds" href="bully.php?t=0&amp;m=1&amp;">Funds</a></li>
                </xsl:if>

                <!-- Process -->

                <xsl:if test="navigation/menu = '2'">
                    <li id="selected"><a>Process</a></li>
                </xsl:if>

                <xsl:if test="navigation/menu != '2'">
                    <li><a title="Process new stock prices and market signals" href="bully.php?t=0&amp;m=2&amp;">Process</a></li>
                </xsl:if>

                <!-- Stocks -->

                <xsl:if test="navigation/menu = '3'">
                    <li id="selected"><a>Stocks</a></li>
                </xsl:if>

                <xsl:if test="navigation/menu != '3'">
                    <li><a title="Review which stocks are being tracked" href="bully.php?t=0&amp;m=3&amp;">Stocks</a></li>
                </xsl:if>

                <!-- Signals -->

                <xsl:if test="navigation/menu = '4'">
                    <li id="selected"><a>Signals</a></li>
                </xsl:if>

                <xsl:if test="navigation/menu != '4'">
                    <li><a title="Display market signals and warnings" href="bully.php?t=0&amp;m=4&amp;">Signals</a></li>
                </xsl:if>

                <!-- Signin -->

                <xsl:if test="navigation/signedin != 'yes'">

                    <xsl:if test="navigation/menu = '5'">
                        <li id="selected"><a>Signin</a></li>
                    </xsl:if>

                    <xsl:if test="navigation/menu != '5'">
                        <li><a title="Signin for administrative privileges" href="bully.php?t=0&amp;m=5&amp;">Signin</a></li>
                    </xsl:if>

                </xsl:if>

                <!-- Signout -->

                <xsl:if test="navigation/signedin = 'yes'">

                    <xsl:if test="navigation/menu = '6'">
                        <li id="selected"><a>Signout</a></li>
                    </xsl:if>
 
                    <xsl:if test="navigation/menu != '6'">
                        <li><a title="Signout as administrator" href="bully.php?t=0&amp;m=6&amp;">Signout</a></li>
                    </xsl:if>

                </xsl:if>

                <!-- History -->

                <xsl:if test="navigation/menu = '9'">
                    <li id="selected"><a>History</a></li>
                </xsl:if>

                <xsl:if test="navigation/menu != '9'">
                    <li><a title="Review the system's performance history" href="bully.php?t=0&amp;m=9&amp;">History</a></li>
                </xsl:if>

                <!-- Settings -->

                <xsl:if test="navigation/menu = '7'">
                    <li id="selected"><a>Settings</a></li>
                </xsl:if>

                <xsl:if test="navigation/menu != '7'">
                    <li><a title="Review the system's operational settings" href="bully.php?t=0&amp;m=7&amp;">Settings</a></li>
                </xsl:if>  

            </xsl:if>





            <!-- Fund Menus -->

            <xsl:if test="navigation/tab != '0'">

                <!-- Balance -->
 
                <xsl:if test="navigation/menu = '10'">
                    <li id="selected"><a>Balance</a></li>
                </xsl:if>

                <xsl:if test="navigation/menu != '10'">
                    <li>
                         <xsl:element name="a">
                         <xsl:attribute name="href">bully.php?t=<xsl:value-of select="navigation/tab"/>&amp;m=10&amp;</xsl:attribute>
                         <xsl:attribute name="title">Review the current market value of the fund</xsl:attribute>Balance</xsl:element>
                   </li>
                </xsl:if>

                <!-- Positions -->
 
                <xsl:if test="navigation/menu = '11'">
                    <li id="selected"><a>Positions</a></li>
                </xsl:if>

                <xsl:if test="navigation/menu != '11'">
                    <li>
                         <xsl:element name="a">
                         <xsl:attribute name="href">bully.php?t=<xsl:value-of select="navigation/tab"/>&amp;m=11&amp;</xsl:attribute>
                         <xsl:attribute name="title">Review stocks held by the fund</xsl:attribute>Positions</xsl:element>
                   </li>
                </xsl:if>  

                <!-- Trades -->
 
                <xsl:if test="navigation/menu = '12'">
                    <li id="selected"><a>Trades</a></li>
                </xsl:if>

                <xsl:if test="navigation/menu != '12'">
                    <li>
                         <xsl:element name="a">
                         <xsl:attribute name="href">bully.php?t=<xsl:value-of select="navigation/tab"/>&amp;m=12&amp;</xsl:attribute>
                         <xsl:attribute name="title">Review the history of the fund's completed trades</xsl:attribute>Trades</xsl:element>
                   </li>
                </xsl:if>

                <!-- Deposits -->
 
                <xsl:if test="navigation/menu = '13'">
                    <li id="selected"><a>Deposits</a></li>
                </xsl:if>

                <xsl:if test="navigation/menu != '13'">
                    <li>
                         <xsl:element name="a">
                         <xsl:attribute name="href">bully.php?t=<xsl:value-of select="navigation/tab"/>&amp;m=13&amp;</xsl:attribute>
                         <xsl:attribute name="title">Review the fund's deposits and withdrawals</xsl:attribute>Deposits</xsl:element>
                   </li>
                </xsl:if>  

                <!-- Signals -->
 
                <xsl:if test="navigation/menu = '14'">
                    <li id="selected"><a>Signals</a></li>
                </xsl:if>

                <xsl:if test="navigation/menu != '14'">
                    <li>
                         <xsl:element name="a">
                         <xsl:attribute name="href">bully.php?t=<xsl:value-of select="navigation/tab"/>&amp;m=14&amp;</xsl:attribute>
                         <xsl:attribute name="title">Review stocks that are aproaching a buy signal</xsl:attribute>Signals</xsl:element>
                   </li>
                </xsl:if>  

                <!-- Stocks -->
 
                <xsl:if test="navigation/menu = '15'">
                    <li id="selected"><a>Stocks</a></li>
                </xsl:if>

                <xsl:if test="navigation/menu != '15'">
                    <li>
                         <xsl:element name="a">
                         <xsl:attribute name="href">bully.php?t=<xsl:value-of select="navigation/tab"/>&amp;m=15&amp;</xsl:attribute>
                         <xsl:attribute name="title">Review which stocks are being tracked by the fund</xsl:attribute>Stocks</xsl:element>
                   </li>
                </xsl:if>  

                <!-- History -->
 
                <xsl:if test="navigation/menu = '16'">
                    <li id="selected"><a>History</a></li>
                </xsl:if>

                <xsl:if test="navigation/menu != '16'">
                    <li>
                         <xsl:element name="a">
                         <xsl:attribute name="href">bully.php?t=<xsl:value-of select="navigation/tab"/>&amp;m=16&amp;</xsl:attribute>
                         <xsl:attribute name="title">Review the fund's performance history</xsl:attribute>History</xsl:element>
                   </li>
                </xsl:if>  

                <!-- Settings -->
 
                <xsl:if test="navigation/menu = '17'">
                    <li id="selected"><a>Settings</a></li>
                </xsl:if>

                <xsl:if test="navigation/menu != '17'">
                    <li>
                         <xsl:element name="a">
                         <xsl:attribute name="href">bully.php?t=<xsl:value-of select="navigation/tab"/>&amp;m=17&amp;</xsl:attribute>
                         <xsl:attribute name="title">Review the fund's operational settings</xsl:attribute>Settings</xsl:element>
                   </li>
                </xsl:if>  

            </xsl:if>

        </ul>
    </div>

</xsl:template>

</xsl:stylesheet>



















