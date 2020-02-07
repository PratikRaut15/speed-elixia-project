var lastDeviceId=0;
var lastSalesId=0;
var selectedSalesid=0;
var selectedItemArray = new Array();

function st( trid )
{
    if(jQuery("#t_"+lastSalesId)!=null)
    {
        jQuery("#t_" +lastSalesId).removeClass("selected");
    }
    selectedSalesid = trid;
    jQuery("#t_" +trid).addClass("selected");
    
    var params = "t="+selectedSalesid ;
    
/*    jQuery.ajax({url:"salesEligibilityAjax.php",type: 'POST',data:params,
        success:function(result){
            if(result=="ok"){
                jQuery("btnMapper").disabled=false;
                 jQuery("#manalert").fadeOut(3000);  
            }else{
                jQuery("btnMapper").disabled=true; 
                jQuery("#manalert").fadeIn(3000); 
            }
        },
    });
*/


    new Ajax.Request('salesEligibilityAjax.php',
    {
        parameters: params,
        onSuccess: function(transport)
        {
            var statuscheck = transport.responseText;
            if(statuscheck == "ok")
            {
                jQuery("btnMapper").disabled=false;
                jQuery("#manalert").fadeOut(3000);                    
            }
            else
            {
                jQuery("btnMapper").disabled=true;                
                jQuery("#manalert").fadeIn(3000);                                    
            }
        }
        ,
        onFailure: function()
        {
        }
    });        
    lastSalesId = trid;
}

function ClearPreviousSelection()
{
    for( var i=0; i<selectedItemArray.length; i++)
    {
        DeSelectElement(selectedItemArray[i]);
    }
    selectedItemArray.length = 0;
    selectedSalesid = 0;
}

function DeSelectElement( id )
{
    jQuery("#d_" +id ).removeClass("selected");
}

function SelectElement( id )
{
    jQuery("#d_" + id ).addClass("selected");
    selectedItemArray.push(id);
    
}


function sd(deviceID)
{
    if(jQuery("#t_"+deviceID) != null)
    {
        jQuery("#t_"+deviceID).removeClass("selected");    
    }
    if("d_"+deviceID != null)
    {
        ClearPreviousSelection();
        SelectElement( deviceID );
        if(jQuery("#dl_"+ deviceID).val()!= null)
        {
            if(jQuery("#dl_"+ deviceID).val()>0)
            {
                // Select the Sales Team Meber too
                st(jQuery("#dl_"+ deviceID).val());
            }
        }

    }
    ClearPreviousSelection();
    SelectElement(deviceID);
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
    if(selectedSalesid>0 && selectedItemArray.length>0)
    {
        var params = "t=" + selectedSalesid + "&ds=" + encodeURIComponent( selectedItemArray.toJSON() ) ;

/*        jQuery.ajax({url:"devicemapsaverAjax.php",type: 'POST',data:params,
            success:function(result){
               window.location="mapdevices.php";
            }
        });
  */      
        
        
        new Ajax.Request('devicemapsaverAjax.php',
        {
            parameters: params,
            onSuccess: function(transport)
            {
                window.location="sales.php?pg=map";
            }
            ,
            onFailure: function()
            {
            }
        });
    }
    else
    {
        alert("Please select a device and a sales team member");
    }
}

function demap()
{
    if(selectedItemArray.length>0)
    {
        var params = "ds=" + encodeURIComponent( selectedItemArray.toJSON() ) ;

//        jQuery.ajax({url:"devicedemapAjax.php",type: 'POST',data:params,
//            success:function(result){
//                window.location="mapdevices.php";
//            }
//        });
        new Ajax.Request('devicedemapAjax.php',
       {
            parameters: params,
            onSuccess: function(transport)
            {
                 window.location="sales.php?pg=map";
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

//Event.observe(window,'load', function() { loaded(); });
