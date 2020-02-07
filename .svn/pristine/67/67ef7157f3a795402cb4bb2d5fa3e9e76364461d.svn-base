var saveit;
var mandatory = [];
function addnewuser()
{
    
    if(jQuery("#role").val() == "0")
    {
        jQuery("#errorrole").show();
        jQuery("#errorrole").fadeOut(3000);        
    }
    else
    {
        if(jQuery("#name").val() == "")
        {
            jQuery("#errorname").show();
            jQuery("#errorname").fadeOut(3000);        
        }
        else
        {
            if(jQuery("#username").val() == "")
            {
                jQuery("#errorusername").show();
                jQuery("#errorusername").fadeOut(3000);        
            }
            else
            {
                if(jQuery("#password").val() == "")
                {
                    jQuery("#errorpassword").show();
                    jQuery("#errorpassword").fadeOut(3000);                        
                }
                else
                {
                    var username = jQuery("#username").val();


                                    jQuery.ajax({
                                                    type: "POST",
                                                    url: "route_ajax.php",
                                                    data:{username:username},
                                                    async: true,
                                                    cache: false,

                                                    success: function (cdata1) {

                                                            var statuscheck = cdata1;
                            if(statuscheck == "ok")
                            {
                                if(jQuery("#email").val() != "")
                                {
                                    var valid_email = jQuery("#email").val().match(/.+@.+\.(.+){2,}/);
                                    if (valid_email == null)
                                    {
                                        jQuery("#erroremail").show();
                                        jQuery("#erroremail").fadeOut(3000);
                                    }
                                    else
                                    {
                                        submituserdata();
                                    }
                                }
                                else
                                {
                                    submituserdata();
                                }
                            }
                            else
                            {
                                jQuery("#errorusername").show();
                                jQuery("#errorusername").fadeOut(3000);                
                            }

                                                    }
                                            });


                }
            }
        }
    }
}    

function submituserdata()
{
    var data = jQuery('#adduser').serialize();
    jQuery.ajax({
                type: "POST",
                url: "route.php",
                data: data,
                cache: false,
                success: function(html)
                {
                    window.location='users.php?id=2';
                }
        });
         
}
function edituser()
{
    if(jQuery("#role").val() == "0")
    {
        jQuery("#errorrole").show();
        jQuery("#errorrole").fadeOut(3000);        
    }
    else
    {
        if(jQuery("#name").val() == "")
        {
            jQuery("#errorname").show();
            jQuery("#errorname").fadeOut(3000);        
        }
        else
        {
            if(jQuery("#password").val() == "")
            {
                jQuery("#errorpassword").show();
                jQuery("#errorpassword").fadeOut(3000);                        
            }
            else
            {
                if(jQuery("#email").val() != "")
                {
                    var valid_email = jQuery("#email").val().match(/.+@.+\.(.+){2,}/);
                    if (valid_email == null)
                    {
                        jQuery("#erroremail").show();
                        jQuery("#erroremail").fadeOut(3000);
                    }
                    else
                    {
                        edituserdata();
                    }
                }
                else
                {
                    edituserdata();
                }
            }
        }
    }
}   

function edituserdata()
{
    var data = jQuery('#edituser').serialize();
    jQuery.ajax({
        type: "POST",
        url: "route.php",
        data: data,
        cache: false,
        success: function(html){
            window.location='users.php?id=2';
        }
    });
}

var h = document.getElementsByTagName('input');
for (var i = 0; i < h.length; i++)
{
	
    if (h[i].type == 'hidden')
    {         
	
        if  (h[i].id.startsWith("hid_g")) 
        {
			
            var groupid = h[i].id;
            groupid = groupid.substr(5);
            var groupname = h[i].value;
            ldgroup(groupname, groupid);
        }
    }
}    
function ldgroup(groupname, groupid)
{
    var selected_name = groupname;
    if (groupid > -1 && jQuery('#to_group_div_' + groupid).val() == null)
    {
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function() { removegroup(groupid); };
        div.className = 'recipientbox';
        div.id = 'to_group_div_' + groupid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_group_' + groupid + '" value="' + groupid + '"/>';
        jQuery('#group_list').append(div);
        jQuery(div).append(remove_image);
    }
} 


function dosave() {
	if (jQuery("#oldpasswd").val() != "") {
		
			var oldpwd=jQuery("#oldpasswd").val();
			var newpwd=jQuery("#newpasswd").val();
			
			jQuery.ajax({
						type: "POST",
						url: "../../modules/user/route_ajax.php",
						data:{oldpwd:oldpwd,newpwd:newpwd},
						async: true,
						cache: false,
						dataType:"json",
						success: function (statuscheck) {
								
								if (statuscheck == "newempty") {
									jQuery("#newempty").show();
									jQuery("#newempty").fadeOut(3000);
								} else if (statuscheck == "ok") {
									jQuery("#perfect").show();
									jQuery("#perfect").fadeOut(3000);
								} else {
									jQuery("#incorrect").show();
									jQuery("#incorrect").fadeOut(3000);
								}	
					
						}
					});
			
			
			
			
		
	} else {
		jQuery("#oldempty").show();
		jQuery("#oldempty").fadeOut(3000);
	}
}

function dosaveuserdet() {
	
		
		var name=jQuery("#name").val();
		var email=jQuery("#email").val();
		var phoneno=jQuery("#phoneno").val();
		var role=jQuery("#role").val();
		
		
		
		jQuery.ajax({
						type: "POST",
						url: "../../modules/user/route_ajax.php",
						data:{name:name,email:email,phoneno:phoneno,role:role},
						async: true,
						cache: false,
						dataType:"json",
						success: function (statuscheck) {
								if (statuscheck == "ok") {
								jQuery("#perfectinfo").show();
								jQuery("#perfectinfo").fadeOut(3000);
								} else {
								jQuery("#problem").show();
								jQuery("#problem").fadeOut(3000);
								}
						}
					});
			
		
		
	
}

function dosaveuserdet_modal() {
	var params = "email=" + encodeURIComponent(jQuery("#email").val()) +
		"&phoneno=" + encodeURIComponent(jQuery("#phoneno").val());
	params += "&alerts=true";
	
	
	
	// geo sms
	if (jQuery("#geosms").is(':checked')) {
		params += "&geosms=1";
	}
	// geo email
	if (jQuery("#geoemail").is(':checked')) {
		params += "&geoemail=1";
	}
	// on over speed 
	if (jQuery("#ospeedsms").is(':checked')) {
		params += "&ospeedsms=1";
	}
	// on over speed email 
	if (jQuery("#ospeedemail").is(':checked')) {
		params += "&ospeedemail=1";
	}
	// on power cut sms 
	if (jQuery("#powercsms").is(':checked')) {
		params += "&powercsms=1";
	}
	// on power cut email 
	if (jQuery("#powercemail").is(':checked')) {
		params += "&powercemail=1";
	}
	// on tamper sms 
	if (jQuery("#tampersms").is(':checked')) {
		params += "&tampersms=1";
	}
	// on tamper email alert 
	if (jQuery("#tamperemail").is(':checked')) {
		params += "&tamperemail=1";
	}
	if (jQuery("#chksms").is(':checked')) {
		params += "&chksms=1";
	}
	if (jQuery("#chkemail").is(':checked')) {
		params += "&chkemail=1";
	}
	if (jQuery("#acsms").is(':checked')) {
		params += "&acsms=1";
	}
	if (jQuery("#acemail").is(':checked')) {
		params += "&acemail=1";
	}
	if (jQuery("#igsms").is(':checked')) {
		params += "&igsms=1";
	}
	if (jQuery("#igemail").is(':checked')) {
		params += "&igemail=1";
	}
	if (jQuery("#tempsms").is(':checked')) {
		params += "&tempsms=1";
	}
	if (jQuery("#tempemail").is(':checked')) {
		params += "&tempemail=1";
	}
	
	
	jQuery.ajax({
						type: "POST",
						url: "../../modules/user/route_ajax.php?"+params,
						async: true,
						cache: false,
						success: function (cdata1) {
							var statuscheck = cdata1;
							if (statuscheck == "ok") {
							jQuery("#perfectinfo").show();
							jQuery("#perfectinfo").fadeOut(3000);
							jQuery("#perfectinfo").show();
							jQuery("#perfectinfo").fadeOut(3000);
							} else {
							jQuery("#problem").show();
							jQuery("#problem").fadeOut(3000);
							}
						},
						complete:function(){
								jQuery("#saved").show();
								jQuery("#saved").fadeOut(3000);
								jQuery('#mask').hide();
								jQuery('.window1').hide();
							
							}
					});
			
	
	
	
	
	
	
}

function dosave_modal() {
	if (jQuery("#newpasswd").val() != "") {
		if (jQuery("#confirm_newpasswd").val() != "") {
			if (jQuery("#confirm_newpasswd").val() == jQuery("#newpasswd").val()) {
				var params = "newpwd=" + encodeURIComponent(jQuery("#newpasswd").val());
				
				
				
				jQuery.ajax({
						type: "POST",
						url: "../../modules/user/route_ajax.php?"+params,
						async: true,
						cache: false,
						success: function (statuscheck) {
							if (statuscheck == "newempty") {
								jQuery("newempty").show();
								jQuery("#newempty").fadeOut(3000);
							} else if (statuscheck == "ok") {
								jQuery("#perfect").show();
								jQuery("#perfect").fadeOut(3000);
								jQuery(".window").hide();
								jQuery('#mask1').fadeOut("slow");
								jQuery('#mask').fadeIn(1000);
								jQuery('#mask').fadeTo("slow", 0.8);
								jQuery('#mask').show();
								jQuery('.window1').show();
							} else {
								jQuery("incorrect").show();
								jQuery("#incorrect").fadeOut(3000);
							}
						}
					});
				
				
			} else {
				jQuery("#incorrect").show();
				jQuery("#incorrect").fadeOut(3000);
			}
		} else {
			jQuery("#confirmempty").show();
			jQuery("#confirmempty").fadeOut(3000);
		}
	} else {
		jQuery("#newempty").show();
		jQuery("#newempty").fadeOut(3000);
	}
}

function addgrouptouser() {
	var groupid = jQuery('#group').val();
	
	if (jQuery('#to_group_div_0').val() == null) {
		if (groupid > -1 && jQuery('#to_group_div_' + groupid).val() == null) {
			if (groupid == 0)
			
				removeallgroup();
			var selected_name = jQuery('#group option[value=' + groupid + ']').text();
			var div = document.createElement('div');
			var remove_image = document.createElement('img');
			remove_image.src = "../../images/boxdelete.png";
			remove_image.className = 'clickimage';
			remove_image.onclick = function () {
				removegroup(groupid);
			};
			div.className = 'recipientbox';
			div.id = 'to_group_div_' + groupid;
			div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="g_list_element" name="to_group_' + groupid + '" value="' + groupid + '"/>';
			jQuery('#group_list').append(div);
			jQuery(div).append(remove_image);
		}
	}
                else{
					
					
                    if (groupid > -1 && jQuery('#to_group_div_' + groupid).val() == null) {
				removegroup(0);
			var selected_name = jQuery('#group option[value=' + groupid + ']').text();
			
			
			var div = document.createElement('div');
			var remove_image = document.createElement('img');
			remove_image.src = "../../images/boxdelete.png";
			remove_image.className = 'clickimage';
			remove_image.onclick = function () {
				removegroup(groupid);
			};
			div.className = 'recipientbox';
			div.id = 'to_group_div_' + groupid;
			div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="g_list_element" name="to_group_' + groupid + '" value="' + groupid + '"/>';
			jQuery('#group_list').append(div);
			jQuery(div).append(remove_image);
		}
                }
	jQuery('group').selectedIndex = 0;
}

function addedgroup() {
	var groupid = 0;
	var selected_name = jQuery('#group option[value=' + groupid + ']').text();
	if (groupid > -1 && jQuery('#to_group_div_' + groupid) == null) {
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removegroup(groupid);
		};
		div.className = 'recipientbox';
		div.id = 'to_group_div_' + groupid;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_group_' + groupid + '" value="' + groupid + '"/>';
		jQuery('#group_list').append(div);
		jQuery(div).append(remove_image);
	}
	jQuery('#group').selectedIndex = 0;
}

function removegroup(group_no) {
	if (group_no > -1 && jQuery('#to_group_div_' + group_no).val() != null) {
		jQuery('#to_group_div_' + group_no).remove();
	}
}

function removeallgroup() {
	//var select_box = jQuery('#group');
	jQuery( "#group option" ).each(function(index, element) {
		var groupid = jQuery(this).val();
		removegroup(groupid);
	});

}