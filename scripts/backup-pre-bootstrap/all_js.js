var type = 1;


jQuery.noConflict();
jQuery(document).ready(function () {


		setTimeout(function () {
		pullnf();
		}, 1500);


		submenu_show();

	var id = '#dialog';
	//Get the screen height and width
	var maskHeight = jQuery(document).height();
	var maskWidth = jQuery(window).width();
	//Set heigth and width to mask to fill up the whole screen
	jQuery('#mask').css({
		'width': maskWidth,
		'height': maskHeight
	});
	jQuery('#mask1').css({
		'width': maskWidth,
		'height': maskHeight
	});
	//transition effect		
	jQuery('#mask1').fadeIn(1000);
	jQuery('#mask1').fadeTo("slow", 0.8);
	//Get the window height and width
	var winH = jQuery(window).height();
	var winW = jQuery(window).width();
	//Set the popup window to center
	jQuery(id).css('top', winH / 2 - jQuery(id).height() / 2);
	jQuery(id).css('left', winW / 2 - jQuery(id).width() / 2);
	//transition effect
	jQuery(id).fadeIn(2000);
	//if close button is clicked
	jQuery('.window #close_popup').click(function (e) {
		//Cancel the link behavior
		e.preventDefault();
		jQuery('.window').hide();
		jQuery('#mask1').fadeOut("slow");
		jQuery('#mask').fadeIn(1000);
		jQuery('#mask').fadeTo("slow", 0.8);
		jQuery('#mask').show();
		jQuery('.window1').show();
	});
	jQuery('.window1 #close_popup1').click(function (e) {
		//Cancel the link behavior
		e.preventDefault();
		jQuery('#mask').hide();
		//jQuery('#mask1').hide();
		//jQuery('.window').hide();
		jQuery('.window1').hide();
	});
	jQuery('#mask1').click(function () {
		jQuery(this).hide();
		jQuery('#mask1').fadeOut("slow");
		jQuery('.window').hide();
		jQuery('.window1').show();
		jQuery('#mask').fadeIn(1000);
		jQuery('#mask').show();
	});
	//if mask is clicked
	jQuery('#mask').click(function () {
		jQuery(this).hide();
		jQuery('#mask1').hide();
		//jQuery('.window').hide();
		jQuery('.window1').hide();
	});
	
	jQuery(".editbox").hide();
	jQuery(".edit_td").dblclick(function () {
		var ID = jQuery(this).attr('id');
		jQuery("#vehicleno_" + ID).hide();
		jQuery("#vehicleno_input_" + ID).show();
	}).change(function () {
		var ID = jQuery(this).attr('id');
		var vehicleno = jQuery("#vehicleno_input_" + ID).val();
		var dataString = 'vehicleid=' + ID + '&vehicleno=' + vehicleno;
		jQuery("#vehicleno_" + ID).html('<img src="load.gif" />'); // Loading image
		if (vehicleno.length > 0) {
			jQuery.ajax({
				type: "POST",
				url: "../vehicle/route_ajax.php",
				data: dataString,
				cache: false,
				success: function (html) {
					jQuery("#vehicleno_" + ID).html(vehicleno);
				}
			});
		} else {
			alert('Enter something.');
		}
	});
	//edit td
	jQuery(".edit_td1").dblclick(function () {
		var ID = jQuery(this).attr('id');
		jQuery("#drivername_" + ID).hide();
		jQuery("#drivername_input_" + ID).show();
		jQuery("#driverno_" + ID).hide();
		jQuery("#driverno_input_" + ID).show();
	}).change(function () {
		var ID = jQuery(this).attr('id');
		var drivername = jQuery("#drivername_input_" + ID).val();
		var driverno = jQuery("#driverno_input_" + ID).val();
		var dataString = 'vehicleid=' + ID + '&drivername=' + drivername + '&driverno=' + driverno;
		jQuery("#drivername_" + ID).html('<img src="load.gif" />'); // Loading image
		jQuery("#driverno_" + ID).html('<img src="load.gif" />'); // Loading image
		if (drivername.length > 0 && driverno.length > 0) {
			jQuery.ajax({
				type: "POST",
				url: "../vehicle/route_ajax.php",
				data: dataString,
				cache: false,
				success: function (html) {
					jQuery("#drivername_" + ID).html(drivername);
					jQuery("#driverno_" + ID).html(driverno);
				}
			});
		} else {
			alert('Enter something.');
		}
	});
	// Edit input box click action
	jQuery(".editbox").mouseup(function () {
		return false
	});
	jQuery('.editbox').keypress(function (e) {
		if (e.which == 13) {
			//alert('The enter key was pressed!');
			jQuery(".editbox").hide();
			jQuery(".text").show();
		}
	});
	// Outside click action
	jQuery(document).mouseup(function () {
		jQuery(".editbox").hide();
		jQuery(".text").show();
	});
	
});





















// JavaScript Document
function updategroupid() {
	var groupid = jQuery('#grouplist').val();
	var data = "groupid=" + groupid;
	jQuery.ajax({
		type: "POST",
		url: "../../modules/user/route.php",
		data: data,
		cache: false,
		success: function (html) {
			window.location.reload();
		}
	});
}
function submenu_show() {
	jQuery("#horizontalmenu1").click(function () { //if mouse leaves the submenu
		//hide_all();
		jQuery("#submenu").toggle(); //hide the open submenu (this is what isn't working)
		//$(this).css('background-color','red'); //this works
	})
}

jQuery(document).click(
	function (e) {
		if (e.target.className == "horizontalmenu1") {
			if (jQuery("#submenu").css("display") == 'undefined' || jQuery("#submenu").css("display") == 'block') {
				jQuery("#submenu").css("display", "block");
			} else {
				jQuery("#submenu").css("display", "none");
			}
			//jQuery("#submenu").css("display","block"); 
			//jQuery("#submenu").toggle();
		} else {
			jQuery("#submenu").hide();
			//jQuery('.always_show').tipsy('hide');
		}
	}
);



//$.noConflict();






function pullnf()
{
    if(type == 2)
    {
        type = 1;
    }
    else
    {
        type = type+1;    
    }
    
	jQuery.ajax({
        type: "GET",
        url: "../notification/pullnf_ajax.php",
		data:{type:type},
        async: true,
        cache: false,
		dataType:"json",
        success: function (cdata1) {
			 
            var results = cdata1.result;
            if(results)
            {
                results.each(function (queue) {
                        if(queue.type == 0)
                        {
                            jQuery.sticky("<font color='#FF0000'>Alert:</font><br/>"+ queue.notif, {autoclose : 5000, position: "top-left", type: "st-topleft1"});                                                    
                        }
                        if(queue.type == 1)
                        {
                            jQuery.sticky("News: "+ queue.notif, {autoclose : 5000, position: "top-left", type: "st-topleft2"});                                                    
                        }
                        if(queue.type == 2)
                        {
                            jQuery.sticky("<font color='#0193CC'>Tip: </font><br/>"+ queue.notif, {autoclose : 5000, position: "top-left", type: "st-topleft3"});                                                    
                        }                        
                });         
            }
            
            refreshmin(type);
		}
	});
	
	
               
}

function refreshmin(type)
{    

setTimeout(function () {
		pullnf();
		}, 60000);

   
}