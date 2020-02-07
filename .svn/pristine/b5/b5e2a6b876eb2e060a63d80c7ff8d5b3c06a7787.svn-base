function getUrlVars() 
{
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) 
    {vars[key] = value;});
    return vars;
}
function addarticle()
{
    var artname = jQuery('#artname').val();
    var maxtemp = Number(jQuery('#maxtemp').val());
    var mintemp = Number(jQuery('#mintemp').val());
    var pageid = getUrlVars()['id'];
    if(pageid=='4')
    {
        var artid = jQuery('#artid').val();
    }
    if(artname=='')
    {
        jQuery("#error").show();
        jQuery("#error").fadeOut(3000);                 
    }
    else if(maxtemp <= mintemp)
    {
        jQuery("#error0").show();
        jQuery("#error0").fadeOut(3000);
    }
    else
    {
        var params = "artname=" + encodeURIComponent(artname);
        if(pageid=='4')
        {
            params += "&artid=" + encodeURIComponent(artid);
        }
		
			
		jQuery.ajax({
		type: "POST",		
		url: "route_ajax.php?"+params,
		success: function (statuscheck) {
			 if(statuscheck == "ok")
                {
                    jQuery("#aeart").submit();
                }
                else
                {
                    jQuery("#error1").show();
                    jQuery("#error1").fadeOut(3000);                
                }
		}
	});
		
                         
    }
}