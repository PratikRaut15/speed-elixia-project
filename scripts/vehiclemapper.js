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
var lastUnitId = 0;
var selectedUnitId = 0;

function st(UnitId) {
	if (jQuery("#d_" + lastUnitId).val() != null) {
		jQuery("#d_" + lastUnitId).removeClass("selected");
	}
	selectedUnitId = UnitId;
	jQuery("#d_" + UnitId).addClass("selected");
	
	
			jQuery.ajax({
						type: "POST",
						url: "route_ajax.php",
						data:{d:selectedUnitId},
						async: true,
						cache: false,
						
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
	
	lastUnitId = UnitId;
	if (jQuery("#dl_" + UnitId).val() != null && jQuery("#dl_" + UnitId).val() != "") {
		var vid = jQuery("#dl_" + UnitId).val();
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
	if (jQuery("#v_" + lastVehicleId).val() != null) {
		jQuery("#v_" + lastVehicleId).removeClass("selected");
	}
	if (jQuery("#v_" +vehicleID).val()  != null) {
		var did = jQuery("#vl_" + vehicleID).val();
		SelectElement(vehicleID);
		if (jQuery("#vl_" + vehicleID).val() != "" && lastUnitId == 0) {
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
		if (selectedUnitId > 0 && lastVehicleId > 0) {
						
			
			jQuery.ajax({
						type: "POST",
						url: "route_ajax.php",
						data:{d:selectedUnitId,ds:lastVehicleId},
						async: true,
						cache: false,
						
						success: function (cdata) {
						
								window.location = "vehicle.php?id=3";
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
	if (selectedUnitId > 0 && lastVehicleId > 0) {
		jQuery.ajax({
						type: "POST",
						url: "route_ajax.php",
						data:{ds:lastVehicleId},
						async: true,
						cache: false,
						
						success: function (cdata) {
						
								window.location = "vehicle.php?id=3";
						}
					});
	
		
		
		
		
	} else {
		alert("Please select a Unit And Vehicle");
	}
}

/*function loaded() {
	if (document.all) {
		Event.observe(document, 'keydown', keydown);
		Event.observe(document, 'keyup', keyup);
	} else {
		Event.observe(window, 'keydown', keydown);
		Event.observe(window, 'keyup', keyup);
	}
}
Event.observe(window, 'load', function () {
	loaded();
});*/