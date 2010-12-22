var ie;

function initialize()
{
    if (window.ActiveXObject)
        ie = true;
    else
        ie = false;
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

function setting(who)
{
    var value = 0;
    var name = 1;
    var fund = 0;

    if (who == 1)
    {
        name = 1;
        value = document.getElementById("pricehistory").selectedIndex + 5;
    }
    else
    {
        name = 2;
        value = document.getElementById("marketsignals").selectedIndex;
    }

    xmlHttpObj = CreateXmlHttpRequestObject();    
    xmlHttpObj.open('GET', 'ws/settings.php?f=' + fund + '&n=' + name + '&v=' + value + '&', false);
    xmlHttpObj.send(null);
    if (xmlHttpObj.status == 200)
    {
        var xmlDoc = xmlHttpObj.responseXML;

        var root_node = xmlDoc.getElementsByTagName('code').item(0);
        if (root_node.firstChild.data != '0')
        {
            root_node = xmlDoc.getElementsByTagName('message').item(0);
            alert(root_node.firstChild.data);
        }
    }
    else
    {        
        alert('Error Updating Settings: ' + xmlHttpObj.status );
    } 
}
