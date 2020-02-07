var lastDeviceId=0;
var lastTrackeeId=0;
var selectedTrackeeid=0;
var selectedItemArray = new Array();

function st( trid )
{
    if($("t_"+lastTrackeeId)!=null)
    {
        $("t_" + lastTrackeeId).removeClassName("selected");
    }
    selectedTrackeeid = trid;
    $("t_" + trid).addClassName("selected");
    
    var params = "t=" + selectedTrackeeid ;

    new Ajax.Request('trackeeEligibilityAjax.php',
    {
        parameters: params,
        onSuccess: function(transport)
        {
            var statuscheck = transport.responseText;
            if(statuscheck == "ok")
            {
                $("btnMapper").disabled=false;
                jQuery("#manalert").fadeOut(3000);                    
            }
            else
            {
                $("btnMapper").disabled=true;                
                jQuery("#manalert").fadeIn(3000);                                    
            }
        }
        ,
        onFailure: function()
        {
        }
    });        
    lastTrackeeId = trid;
}

function ClearPreviousSelection()
{
    for( var i=0; i<selectedItemArray.length; i++)
    {
        DeSelectElement(selectedItemArray[i]);
    }
    selectedItemArray.length = 0;
    selectedTrackeeid = 0;
}

function DeSelectElement( id )
{
    $("d_" + id ).removeClassName("selected");
}

function SelectElement( id )
{
    $("d_" + id ).addClassName("selected");
    selectedItemArray.push(id);
    
}


function sd( deviceID )
{
    if($("t_" + lastTrackeeId) != null)
    {
        $("t_" + lastTrackeeId).removeClassName("selected");    
    }
    if("d_"+deviceID != null)
    {
        ClearPreviousSelection();
        SelectElement( deviceID );
        if($("dl_"+ deviceID)!= null)
        {
            if($("dl_"+ deviceID).getValue()>0)
            {
                // Select the Trackee too
                st($("dl_"+ deviceID).getValue());
            }
        }

    }
    ClearPreviousSelection();
    SelectElement( deviceID );
    lastDeviceId = deviceID;
}

function keydown( event )
{
    return true;
}

function keyup( event )
{
    return true;
}

function mapselection()
{
    if(selectedTrackeeid>0 && selectedItemArray.length>0)
    {
        var params = "t=" + selectedTrackeeid + "&ds=" + encodeURIComponent( selectedItemArray.toJSON() ) ;

        new Ajax.Request('devicemapsaverAjax.php',
        {
            parameters: params,
            onSuccess: function(transport)
            {
                window.location="mapdevices.php";
            }
            ,
            onFailure: function()
            {
            }
        });
    }
    else
    {
        alert("Please select a device and a trackee");
    }
}

function demap()
{
    if(selectedItemArray.length>0)
    {
        var params = "ds=" + encodeURIComponent( selectedItemArray.toJSON() ) ;

        new Ajax.Request('devicedemapAjax.php',
        {
            parameters: params,
            onSuccess: function(transport)
            {
                window.location="mapdevices.php";
            }
            ,
            onFailure: function()
            {
            }
        });
    }
    else
    {
        alert("Please select a device");
    }
}

function loaded()
{
    if(document.all)
    {
        Event.observe(document,'keydown', keydown );
        Event.observe(document,'keyup', keyup );
    }
    else
    {
        Event.observe(window,'keydown', keydown );
        Event.observe(window,'keyup', keyup );
    }
}
jQuery(document).ready(function() {
// Handler for .ready() called.
loaded();
});
