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

function update(trade)
{
    var price = document.getElementById("price").value;
    var shares = document.getElementById("shares").value;

    xmlHttpObj = CreateXmlHttpRequestObject();    
    xmlHttpObj.open('GET', 'ws/editposition.php?d=' + trade + '&p=' + price + '&s=' + shares + '&', false);
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
        window.location.reload();
    }
    else
    {        
        alert('Error Updating Settings: ' + xmlHttpObj.status );
    } 
}
