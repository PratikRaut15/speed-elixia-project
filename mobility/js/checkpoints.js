function submitcheckpoint()
{
    if($("chkN").value == "")
    {
        $("checkpointname").show();
        jQuery("#checkpointname").fadeOut(3000);                 
    }
    else if($("cgeolat").value == "" && $("cgeolong").value =="")
    {
        $("latlong").show();
        jQuery("#latlong").fadeOut(3000);                 
    }
    else
    {
        var params = "chkN=" + encodeURIComponent($("chkN").value);
        new Ajax.Request('route_ajax.php',
        {
            parameters: params,
            onSuccess: function(transport)
            {
                var statuscheck = transport.responseText;
                if(statuscheck == "ok")
                {
				  
					 					  
					pushdata("chkN="+$("chkN").value+"&chkA="+$("chkA").value+"&chkRN="+$("chkRN").value+"&chkT="+$("chkT").value+"&chkC="+$("chkC").value+"&chkS="+$("chkS").value+"&chkZC="+$("chkZC").value+"&chkRad="+$("chkRad").value+"&cgeolat="+$("cgeolat").value+"&cgeolong="+$("cgeolong").value+"&create=1");
					
				
				
				
				}
                else
                {
                    $("samename").show();
                    jQuery("#samename").fadeOut(3000);                
                }
            },
            onComplete: function()
            {
            }
        });                            
    }
}
jQuery(document).ready(function($) {
// Code using $ as usual goes here.
//alert($("#tab1_content").height());
//alert($(".onecolumn").width());
jQuery("#map").css("width",jQuery(".onecolumn").width()-45);
jQuery("#map").css("height",jQuery("#tab1_content").height()+500);
//;
});
function pushdata(data)
{
		var params2= data;
	 new Ajax.Request('route_ajax.php?',
        {parameters: params2,
            onSuccess: function(transport)
            {
                var statuscheck = transport.responseText;
                if(statuscheck == "ok")
                {
				  
				alert("Check point created");
				
				jQuery("tab1_content").addClass("tab_content hide");
				jQuery("tab2_content").removeClass("tab_content hide");
				
				jQuery("tab2_content").addClass("tab_content");
				}
                else
                {
                    $("samename").show();
                    jQuery("#samename").fadeOut(3000);                
                }
            },
            onComplete: function()
            {
            }
	
	
	
	
	
	});
	
}