var ie;
var wait;
var stocks;
var thedate;
var stockIndex;

function initialize()
{
    if (window.ActiveXObject) ie = true; else ie = false;
    wait = '<img src="images/busy.gif" style="margin-right:5px;" alt="Please Wait..."/>';
}

function CreateXmlHttpRequestObject()
{
    var xmlObj;
    if (ie)
    {
        try
        {
           xmlObj = new ActiveXObject("Microsoft.XMLHTTP");
        } 
        catch (e)
        {
           xmlObj = new ActiveXObject("Msxml2.XMLHTTP");
        }
    }
    else
    {
        xmlObj = new XMLHttpRequest();
    }        
    return xmlObj;
}

function doSignals()
{
    xmlHttpObj = CreateXmlHttpRequestObject();    
    xmlHttpObj.open('GET', 'ws/process.php?p=4&d=' + thedate + '&', false);
    xmlHttpObj.send(null);
    if (xmlHttpObj.status == 200)
    {
        var xmlDoc = xmlHttpObj.responseXML;

        var root_node = xmlDoc.getElementsByTagName('code').item(0);
        if (root_node.firstChild.data != '0')
        {
            root_node = xmlDoc.getElementsByTagName('message').item(0);
            alert(root_node.firstChild.data);
            return;
        }
    }
    else
    {        
        document.getElementById("status").innerHTML = 'Error updating signals: ' + xmlHttpObj.status;
        return;
    } 
    markDone();
}

function markDone()
{
    xmlHttpObj = CreateXmlHttpRequestObject();    
    xmlHttpObj.open('GET', 'ws/process.php?p=3&d=' + thedate + '&', false);
    xmlHttpObj.send(null);
    if (xmlHttpObj.status == 200)
    {
        var xmlDoc = xmlHttpObj.responseXML;

        var root_node = xmlDoc.getElementsByTagName('code').item(0);
        if (root_node.firstChild.data != '0')
        {
            root_node = xmlDoc.getElementsByTagName('message').item(0);
            alert(root_node.firstChild.data);
            return;
        }
    }
    else
    {        
        document.getElementById("status").innerHTML = 'Error getting setting last process date: ' + xmlHttpObj.status;
        return;
    } 
    document.getElementById("status").innerHTML = 'Done.';
    window.location='bully.php?t=0&m=4&';
}

function getPrices()
{
    if (stockIndex < stocks.length)
    {
        document.getElementById('status').innerHTML = wait + 'Getting Prices for ' + thedate + ' (' + (stockIndex+1) + ' of ' + stocks.length + ') ' + stocks[stockIndex];

        xmlHttpObj = CreateXmlHttpRequestObject();
        xmlHttpObj.open('GET', 'ws/process.php?p=2&s=' + stocks[stockIndex] + '&d=' + thedate + '&', true);
        xmlHttpObj.onreadystatechange = function() 
        {
            if (xmlHttpObj.readyState == 4)
            {                            
                if (xmlHttpObj.status == 200)
                {
                    var xmlDoc = xmlHttpObj.responseXML;
                    var root_node = xmlDoc.getElementsByTagName('code').item(0);
                    if (root_node.firstChild.data != '0')
                    {
                        root_node = xmlDoc.getElementsByTagName('message').item(0);
                        document.getElementById("status").innerHTML = root_node.firstChild.data;
                    }
                    else
                    {
                        stockIndex++;
                        getPrices();
                    }
                } 
                else
                {
                     document.getElementById("status").innerHTML = 'Error: ' + xmlHttpObj.responseText;
                }
            }
        }         
        xmlHttpObj.send(null);
    }  
    else
    {
        doSignals();
    }
}

function process()
{
    stocks = new Array();

    // Get Symbols

    xmlHttpObj = CreateXmlHttpRequestObject();    
    xmlHttpObj.open('GET', 'ws/process.php?p=1&', false);
    xmlHttpObj.send(null);
    if (xmlHttpObj.status == 200)
    {
        var xmlDoc = xmlHttpObj.responseXML;

        var root_node = xmlDoc.getElementsByTagName('code').item(0);
        if (root_node.firstChild.data != '0')
        {
            root_node = xmlDoc.getElementsByTagName('message').item(0);
            alert(root_node.firstChild.data);
            return;
        }
    }
    else
    {        
        alert('Error getting symbols: ' + xmlHttpObj.status );
        return;
    } 
 
    root_node = xmlDoc.getElementsByTagName('symbol'); 
    for (i=0; i<root_node.length; i++)
    {
        elem = root_node.item(i);          
        var i = stocks.length;
        stocks[i] = elem.firstChild.data;
    }

    // Get Process Date

    root_node = xmlDoc.getElementsByTagName('nextdate').item(0);
    thedate = root_node.firstChild.data;
    stockIndex = 0;
    getPrices();
}
