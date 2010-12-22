var ie;
var wait;
var phase;

function initialize()
{
    wait = '<img src="images/busy.gif" style="margin-right:5px;" alt="Please Wait..."/>';
    phase = 0;

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

function rebuild(stock)
{
    if (phase == 0)
    { 
        var answer = confirm("Are you sure you want to rebuild this stock?");
        if (answer == false) return;
    }

    if (phase < 4)
    {
        if (phase == 0) document.getElementById("busy").innerHTML = wait + 'Deleting Old Prices';
        if (phase == 1) document.getElementById("busy").innerHTML = wait + 'Getting New Prices';
        if (phase == 2) document.getElementById("busy").innerHTML = wait + 'Get 120 Highs &amp; Lows';
        if (phase == 3) document.getElementById("busy").innerHTML = wait + 'Update Price Summary';
        phase++;

        xmlHttpObj = CreateXmlHttpRequestObject();
        xmlHttpObj.open("GET", "ws/rebuild.php?s=" + stock + "&p=" + phase + "&", true);
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
                        document.getElementById("busy").innerHTML = root_node.firstChild.data;
                    }
                    else
                    {
                        rebuild(stock);
                    }
                } 
                else
                {
                     document.getElementById("busy").innerHTML = 'Error: ' + xmlHttpObj.responseText;
                }
            }
        }         
        xmlHttpObj.send(null);
    }  
    else
    {
        phase = 0;
        document.getElementById("busy").innerHTML = 'Done!';
    }
}