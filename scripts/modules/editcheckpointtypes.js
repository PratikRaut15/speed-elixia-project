

function editchekpointtypes()
{
    var chk = jQuery("#typename").val();
    if( chk == "")
    {
        alert("Please enter checkpoint name");        
    }
    else if(chk.match("'"))
    {
        alert("Special cherecters not allowed in checkpoint name");
    }
    else
    {
        jQuery("#chktypecreate").submit();                    
    }
}