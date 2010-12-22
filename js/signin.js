
function initialize()
{
    document.getElementById("username").focus();
}

function onEnter(e)
{
    var keynum;
    if(window.event) // IE
    {
        keynum = e.keyCode;
    }
    else if(e.which) // Netscape/Firefox/Opera
    {
        keynum = e.which;
    }
    if (keynum==13) document.forms[0].submit();
    return (keynum!=13);
}
