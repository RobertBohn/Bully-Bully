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

function setting(fund,name)
{
    var value = 0;

    if (name == 10)
    {
        value = document.getElementById("risk").selectedIndex + 1;
    }

    if (name == 11)
    {
        switch (document.getElementById("positions").selectedIndex)
        {
            case 0: 
                value = 10;
                break;
            case 1: 
                value = 15;
                break;
            case 2: 
                value = 20;
                break;
            case 3: 
                value = 25;
                break;
        }
    }

    if (name == 12)
    {
        switch (document.getElementById("maxprice").selectedIndex)
        {
            case 0: 
                value = 0;
                break;
            case 1: 
                value = 25;
                break;
            case 2: 
                value = 30;
                break;
            case 3: 
                value = 35;
                break;
            case 3: 
                value = 40;
                break;
        }
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
