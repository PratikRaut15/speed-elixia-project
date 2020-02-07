// JavaScript Document
var h = document.getElementsByTagName('input');
for (var i = 0; i < h.length; i++)
{
    if (h[i].type == 'hidden')
    {            
        if  (h[i].id.startsWith("hid_v")) 
        {
            var vehicleid = h[i].id;
            vehicleid = vehicleid.substr(5);
            var vehicleno = h[i].value;
            ldvehicle(vehicleno, vehicleid);
        }
    }
}    
function ldvehicle(vehicleno, vehicleid)
{
    var selected_name = vehicleno;
    if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null)
    {
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function() { removeVehicle(vehicleid); };
        div.className = 'recipientbox';
        div.id = 'to_vehicle_div_' + vehicleid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
        jQuery('#vehicle_list_route').append(div);
        jQuery(div).append(remove_image);
    }
} 

function VehicleForRoute() {
	var vehicleid = jQuery('#vehicleroute').val();
	if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
		var selected_name = jQuery("#vehicleroute option:selected").text();
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removeVehicle(vehicleid);
		};
		div.className = 'recipientbox';
		div.id = 'to_vehicle_div_' + vehicleid;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
		jQuery("#vehicle_list_route").append(div);
		jQuery(div).append(remove_image);
	}
	jQuery("#vehicleroute").prop("selectedIndex", 0);
}

function addallvehicleForRoute() {
	for (var i = 1; i < jQuery('#vehicleroute option').length; i++) {
		jQuery("#vehicleroute").prop("selectedIndex", i);
		VehicleForRoute();
	}
}

function removeVehicle(vehicleid) {
	jQuery('#to_vehicle_div_' + vehicleid).remove();
}

function AddedVehicleForRoute() {
	var vehicleid = jQuery('#chk_vehid').val();
	var selected_name = jQuery('#vehicleid1 option[value=' + vehicleid + ']').text();
	if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid) == null) {
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removeVehicle(vehicleid);
		};
		div.className = 'recipientbox';
		div.id = 'to_vehicle_div_' + vehicleid;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
		jQuery('#vehicle_list1').append(div);
		jQuery(div).append(remove_image);
	}
	jQuery("#vehicleroute").prop("selectedIndex", 0);
}

function remove_addedVehicleForRoute(vehicleid) {
	jQuery('#to_vehicle_div_' + vehicleid).remove();
}

function addcheckpointtovehicle() {
	var checkpointid = jQuery('#checkpointid').val();
	if (checkpointid > -1 && jQuery('#to_chkpt_div_' + checkpointid).val() == null) {
		var selected_name = jQuery('#checkpointid options:selected').text();
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removeChkpt(checkpointid);
		};
		div.className = 'recipientbox';
		div.id = 'to_chkpt_div_' + checkpointid;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="c_list_element" name="to_chkpt_' + checkpointid + '" value="' + checkpointid + '"/>';
		jQuery('chkpt_list').appendChild(div);
		$(div).appendChild(remove_image);
	}
	$('checkpointid').selectedIndex = 0;
}

function addedcheckpoint() {
	jQuery('.chk_id').each(function () {
		var checkpointid = this.id;
		var selected_name = jQuery('#checkpointid option[value=' + checkpointid + ']').text();
		if (checkpointid > -1 && $('to_chkpt_div_' + checkpointid) == null) {
			var div = document.createElement('div');
			var remove_image = document.createElement('img');
			remove_image.src = "../../images/boxdelete.png";
			remove_image.className = 'clickimage';
			remove_image.onclick = function () {
				removeChkpt(checkpointid);
			};
			div.className = 'recipientbox';
			div.id = 'to_chkpt_div_' + checkpointid;
			div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_added_chkpt_' + checkpointid + '" value="' + checkpointid + '"/>';
			$('chkpt_list').appendChild(div);
			$(div).appendChild(remove_image);
		}
		$('checkpointid').selectedIndex = 0;
	});
}

function addallCheckpointForVehicle() {
	var select_box = $('checkpointid');
	for (var i = 1; i < select_box.options.length; i++) {
		select_box.selectedIndex = i;
		var checkpointid = $('checkpointid').getValue();
		if (checkpointid > -1 && $('to_chkpt_div_' + checkpointid) == null) {
			var selected_name = $('checkpointid').options[$('checkpointid').selectedIndex].text;
			var div = document.createElement('div');
			var remove_image = document.createElement('img');
			remove_image.src = "../../images/boxdelete.png";
			remove_image.className = 'clickimage';
			remove_image.onclick = function () {
				removeChkpt(checkpointid);
			};
			div.className = 'recipientbox';
			div.id = 'to_chkpt_div_' + checkpointid;
			div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="c_list_element" name="to_chkpt_' + checkpointid + '" value="' + checkpointid + '"/>';
			$('chkpt_list').appendChild(div);
			$(div).appendChild(remove_image);
		}
	}
	$('checkpointid').selectedIndex = 0;
}

function removeChkpt(checkpointid) {
	$('to_chkpt_div_' + checkpointid).remove();
}

	function checkroute()
	{   
            var routearray=jQuery('#column2').sortable('toArray');
		if(jQuery("#routename").val() == "")
		{
			jQuery("#namecomp").show();
			jQuery("#namecomp").fadeOut(3000);                 
		}
                else if(routearray == "")
		{
			jQuery("#routelist").show();
			jQuery("#routelist").fadeOut(3000);                 
		}
                else
                    {
                        var params = "routen=" + encodeURIComponent(jQuery("#routename").val());
                    new Ajax.Request('route_ajax.php',
                    {
                        parameters: params,
                        onSuccess: function(transport)
                        {
                            var statuscheck = transport.responseText;
                            if(statuscheck == "ok")
                            {
                                submitroute(); 
                            }
                            else
                            {
                                jQuery("#samename").show();
                                jQuery("#samename").fadeOut(3000);                
                            }
                        },
                        onComplete: function()
                        {
                        }
                    });
                    }
	} 

function submitroute()
		{
				//$(ui.item).find('h2').click();
				//var sortorder='';
				var routename = jQuery("#routename").val();
				var routearray=jQuery('#column2').sortable('toArray');
                                var vehiclearray = new Array();
                                jQuery('.v_list_element').each(function() {
                                                                        vehiclearray.push(jQuery(this).val());
                                                                    });
				var datastring = "routename=" + routename + "&routearray=" + routearray + "&vehiclearray=" + vehiclearray;
                        jQuery.ajax({
										type: "POST",
										url: "route_ajax.php",
										data: datastring,
										cache: false,
										success: function(html)
										{ 
                                                                                jQuery("#saved").show();
                                                                                jQuery("#saved").fadeOut(3000);
                                                                                window.location.href = "route.php?id=2";
										}
								});   

			}
                        
function modifyroutechk()
	{
            var routearray=jQuery('#column2').sortable('toArray');
            var vehiclearray = new Array();
            jQuery('.v_list_element').each(function() {
                                                    vehiclearray.push(jQuery(this).val());
                                                });
                                    
                if(routearray == "")
		{
			jQuery("#routelist").show();
			jQuery("#routelist").fadeOut(3000);                 
		}
                else
                    {
                        modifyroute(); 
                    }
	} 
        
function modifyroute()
		{
				//$(ui.item).find('h2').click();
				//var sortorder='';
				var routeid = jQuery("#routeid").val();
				var routename = jQuery("#routename").val();
				var routearray=jQuery('#column2').sortable('toArray');
                                var vehiclearray = new Array();
                                jQuery('.v_list_element').each(function() {
                                                                        vehiclearray.push(jQuery(this).val());
                                                                    });
				var datastring = "routeid=" + routeid + "&routename=" + routename + "&routearray=" + routearray + "&vehiclearray=" + vehiclearray;
                        jQuery.ajax({
										type: "POST",
										url: "route_ajax.php",
										data: datastring,
										cache: false,
										success: function(html)
										{ 
                                                                                jQuery("#saved").show();
                                                                                jQuery("#saved").fadeOut(3000);
                                                                                window.location.href = "route.php?id=2";
										}
								});   

			}
jQuery.noConflict();
jQuery( 
	function(){
		jQuery('a.maxmin').click(
		function(){
			jQuery(this).parent().siblings('.dragbox-content').toggle();
		});

		jQuery('.column').sortable({
		connectWith: '.column',
		handle: 'h2',
		cursor: 'move',
		placeholder: 'placeholder',
		forcePlaceholderSize: true,
		opacity: 0.4,	
		stop: function(event, ui)
			{
				jQuery(ui.item).find('h2').click();
				var sortorder='';
				var itemorder=jQuery('#column2').sortable('serialize');
				var columnId=jQuery('#column2').attr('id');
				sortorder+=columnId+'='+itemorder.toString()+'&';
				//$('.column').each(function(){
				//	var itemorder=$(this).sortable('toArray');
				//	var columnId=$(this).attr('id');
				//	sortorder+=columnId+'='+itemorder.toString()+'&';
				//});
				sortorder = sortorder.substring(0, sortorder.length - 1)
				//alert(itemorder);

			}
		}).disableSelection();
	});
