

jQuery.noConflict();
jQuery(document).ready(function () {

loaded();


});
if (document.all) {
	document.onselectstart = new Function('return false');

	function ds(e) {
		return false;
	}

	function ra() {
		return true;
	}
	document.onmousedown = ds;
	document.onclick = ra;
}
var lastVehicleId = 0;
var lastDriverId = 0;
var selectedDriverId = 0;

function st(DriverId) {
	if (jQuery("#d_" + lastDriverId).val() != null) {
		jQuery("#d_" + lastDriverId).removeClass("selected");
	}
	selectedDriverId = DriverId;
	jQuery("#d_" + DriverId).addClass("selected");
	
	
	jQuery.ajax({
				type: "POST",
				url: "route_ajax.php",
			
				cache: false,
				data:{d:DriverId},
				success: function (statuscheck) {
						if (statuscheck == "ok") {
						jQuery("#mapper").disabled = false;
						} else {
						jQuery("#mapper").disabled = true;
						jQuery("#demap").fadeIn(300);
						jQuery("#demap").fadeOut(600);
						}
				}
			});
	
	
	lastDriverId = DriverId;
	if (jQuery("#dl_" + DriverId).val() != null && jQuery("dl_" + DriverId).val() != "") {
		var vid = jQuery("#dl_" + DriverId).val();
		sd(vid);
	}
}

function DeSelectElement(id) {
	jQuery("#v_" + id).removeClass("selected");
}

function SelectElement(id) {
	jQuery("#v_" + id).addClass("selected");
}

function sd(vehicleID) {
	if (jQuery("#v_" + lastVehicleId) != null) {
		jQuery("#v_" + lastVehicleId).removeClass("selected");
	}
	if ("#v_" + vehicleID != null) {
		var did = jQuery("#vl_" + vehicleID).val();
		SelectElement(vehicleID);
		if (jQuery("#vl_" + vehicleID).val() != "" && lastDriverId == 0) {
			st(did);
		}
		if (did != null && did != '') {
			jQuery("#mapper").disabled = true;
			jQuery("#demap").fadeIn(300);
			jQuery("#demap").fadeOut(600);
		}
	}
	lastVehicleId = vehicleID;
}

function keydown(event) {
	return true;
}

function keyup(event) {
	return true;
}

function mapselection() {
	if (jQuery('#vl_' + lastVehicleId).val() == "") {
		if (selectedDriverId > 0 && lastVehicleId > 0) {
			jQuery.ajax({
				type: "POST",
				url: "route_ajax.php",
				data: {d:selectedDriverId,ds:lastVehicleId},
				cache: false,
				success: function (html) {
					window.location = "driver.php?id=3";
				}
			});
			
		} else {
			alert("Please select a Driver And Vehicle");
		}
	} else {
		jQuery("#demap").fadeIn(300);
		jQuery("#demap").fadeOut(600);
	}
}

function demap() {
	if (selectedDriverId > 0 && lastVehicleId > 0) {
		
		jQuery.ajax({
				type: "POST",
				url: "route_ajax.php",
				data: {ds:lastVehicleId},
				cache: false,
				success: function (html) {
					window.location = "driver.php?id=3";
				}
			});
		
		
	} else {
		alert("Please select a Driver And Vehicle");
	}
}

function loaded() {
	/*if (document.all) {
		Event.observe(document, 'keydown', keydown);
		Event.observe(document, 'keyup', keyup);
	} else {
		Event.observe(window, 'keydown', keydown);
		Event.observe(window, 'keyup', keyup);
	}*/
}
