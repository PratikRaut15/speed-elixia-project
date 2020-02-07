// For SMS TRACK
function checkphoneno()
{
    var phonen = jQuery("#phoneno").val();
    phonen = phonen.replace(/[^0-9]/g, '');
    if(jQuery("#name").val() == "")
    {
        jQuery("#nameerror").show();
        jQuery("#nameerror").fadeOut(3000);          
    }
    else if(phonen.length != 10) { 
                            jQuery("#smserror").show();
                            jQuery("#smserror").fadeOut(3000);
    }
    else
    {        
        // get values for each vehicle 
        
		jQuery.ajax({
                type: "POST",
                url: "sms_ajax.php",
                cache: false,
                data:{phonen:phonen},
                success: function(statuscheck)
                        { 
                            if(statuscheck == "ok")
                            {
                            addsmstracking();               
                            }
                            else
                            {
                            jQuery("#samephone").show();
                            jQuery("#samephone").fadeOut(3000);                
                            }    
                        }		
                });
		
		
        
                        
    }
}
  

function addsmstracking()
{
    var data = jQuery('#smsform').serialize();
	//alert(data);exit;
    jQuery.ajax({
                type: "POST",
                url: "sms_ajax.php",
                data: data,
                cache: false,
                success: function(html)
                {  
                    jQuery("#perfectinfo").show();
                    jQuery("#perfectinfo").fadeOut(3000); 
                    window.location.href = "smstracking.php?id=2";
                }
        });
         
}

function checkphonenoedit()
{
    var phonen = jQuery("#editphoneno").val();
    phonen = phonen.replace(/[^0-9]/g, '');
    if(jQuery("#name").val() == "")
    {
        jQuery("#nameerror").show();
        jQuery("#nameerror").fadeOut(3000);          
    }
    else if(phonen.length != 10) { 
                            jQuery("#smserror").show();
                            jQuery("#smserror").fadeOut(3000);
    }
    else
    {        
        // get values for each vehicle 
        
		jQuery.ajax({
                type: "POST",
                url: "sms_ajax.php",
                cache: false,
                data:{phonen:phonen},
                success: function(statuscheck)
                        { 
                            if(statuscheck == "ok")
                            {
                            editsmstracking();               
                            }
                            else
                            {
                            jQuery("#samephone").show();
                            jQuery("#samephone").fadeOut(3000);  
                            }    
                        }		
                });
		
		
        
                        
    }
}

function editsmstracking()
{
    var data = jQuery('#editsmsform').serialize();
	//alert(data);exit;
    jQuery.ajax({
                type: "POST",
                url: "sms_ajax.php",
                data: data,
                cache: false,
                success: function(html)
                {  
                    jQuery("#perfectinfo").show();
                    jQuery("#perfectinfo").fadeOut(3000); 
                    window.location.href = "smstracking.php?id=2";
                }
        });
         
}

