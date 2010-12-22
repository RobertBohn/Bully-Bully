var ie;
var wait;

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

function process()
{
    document.getElementById('status').innerHTML = wait + 'Performing Backup';
}
