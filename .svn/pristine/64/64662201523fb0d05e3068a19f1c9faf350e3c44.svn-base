$(function() {
    $("#vehicleno").autoSuggest({
    ajaxFilePath: "../checkpoint/autocomplete.php", 
    ajaxParams: "dummydata=dummyData", 
    autoFill: false, 
    iwidth: "auto",
    opacity: "0.9",
    ilimit: "10",
    idHolder: "id-holder",
    match: "contains"
    });
});
  
function fill(Value, strparam){
    jQuery('#vehicleno').val(strparam);
    jQuery('#vehicleid').val(Value);
    jQuery('#display').hide();
    jQuery('.ajax_response').html('');
    VehicleForRoute_ById(Value, strparam)
}
 
function VehicleForRoute_ById(vehicleid,selected_name) {
    if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
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
    jQuery("#vehicleno").val('');
}

function removeVehicle(vehicleid) {
    jQuery('#to_vehicle_div_' + vehicleid).remove();
}
function addallvehicleForRoute() {
    for (var i = 0; i < jQuery('#vehicleroute option').length; i++) {
        jQuery("#vehicleroute").prop("selectedIndex", i);
	VehicleForRoute();
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

function fill_exception_data(uid){
    var url = "exception_ajax.php";
    if(exception_url!=undefined){
        url =  exception_url;
    }
    
    var params = "todo=getException&uid="+uid;
    jQuery.ajax({
        type: "POST",
        url:url,
        data:params,
        cache: false,
        success:function(html){
            jQuery('#ExceptionpopTable').append(html);
        }
    });
}