<?xml version="1.0" encoding="ISO-8859-1"?><xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
<xsl:apply-templates select="root"/> 
</xsl:template>




<xsl:template match="root">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta name="description" content="Personal Stock Market Portfolio Management." />
    <meta name="robots" content="index,follow" />
    <link rel="Shortcut Icon" type="image/ico" href="favicon.ico" />
    <link href="bully.css" rel="stylesheet" type="text/css" />
    <link href="home.css" rel="stylesheet" type="text/css" />
    <title>Bully Bully</title>
</head>
<body>
    <div id="wrapper">

        <div id="header">            
            <img src="images/logo.gif" alt="" />
        </div>

        <div id="tabholder">

            <div id="tabs">
                <ul>

    <xsl:for-each select="funds">
        <li>
            <xsl:element name="a">
                <xsl:attribute name="href">bully.php?t=<xsl:value-of select="id"/>&amp;m=10&amp;</xsl:attribute>
                <xsl:attribute name="title">Review <xsl:value-of select="name"/> Portfolio</xsl:attribute>
                <xsl:value-of select="name"/>
            </xsl:element>
        </li>
    </xsl:for-each>
    <li><a title="Perform Administrative Functions" href="bully.php?t=0&amp;m=1&amp;">Administration</a></li>
    <li id="current"><a>Home Page</a></li>


                </ul>
            </div>

            <div id="opentab">        

                <div id="homepage">

                    <h1 id="pagename">Bully Bully - Home Page</h1>
                    <h2 id="pagedescr">Personal Stock Market Portfolio Manager</h2>

                    <div id="siteinfo">                 
                        <h2>About This Website</h2>
                        <p>Bully Bully is a website I built for my own amusement. I needed an easy way to manage multiple portfolios online, and I thought this would be a fun way to do it.</p>
                    </div>

                    <div id="stockinfo">
                        <h2>About My Portfolios</h2>
                        <p>I designed this trend following system myself. My program gathers market data, then generate buy or sell signals. A risk management routine calculates position sizes. All I have to do is place the orders.</p>
                        <ul class="button">
                            <li><a title="Signin Here" href="bully.php?t=0&amp;m=5&amp;">Start Here</a></li>
                        </ul>
                    </div>

                </div> <!-- homepage -->
                
                <div id="footer">
                    <a href="http://validator.w3.org/check/referrer" title="Valid XHTML 1.0 Strict">XHTML</a> | <a href="http://jigsaw.w3.org/css-validator/validator?uri=http://www.bullybully.us/" title="Valid CSS Version 3">CSS</a> | <a href="http://creativecommons.org/licenses/by-sa/3.0/" title="Creative Commons License">(CC) Creative Commons</a> | <a href="http://www.robertbohn.com/" title="Website design and programming by Robert Bohn">Designed By</a>
                </div> <!-- footer -->
                
            </div> <!-- opentab --> 

        </div> <!-- tabholder -->

    </div> <!-- wrapper -->
</body>
</html>
</xsl:template>

</xsl:stylesheet>