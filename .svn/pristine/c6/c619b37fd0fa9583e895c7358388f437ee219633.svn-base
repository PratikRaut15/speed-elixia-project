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
var lastchkId = 0;
var selectedchkId = 0;

function st(chkId) {
	if (jQuery("#d_" + lastchkId).val() != null) {
		jQuery("#d_" + lastchkId).removeClass("selected");
	}
	selectedchkId = chkId;
	jQuery("#d_" + chkId).addClass("selected");
	var params = "d=" + selectedchkId;
	
	
	jQuery.ajax({
			type: "GET",
			url: "route_ajax.php",
			async: true,
			data: {
				d: selectedchkId
			},
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
	
	
	lastchkId = chkId;
	chk = jQuery("#dl_" + chkId).val();
	if (chk != null && chk != "") {
		var vid = jQuery("#dl_" + chkId).val();
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
	if (jQuery("v_" + vehicleID).val() != null) {
		if (jQuery("#vl_" + vehicleID).val() != null) {
			var did = jQuery("#vl_" + vehicleID).val();
			SelectElement(vehicleID);
			if (jQuery("#vl_" + vehicleID).val() != "" && lastchkId == 0) {
				st(did);
			}
			if (did != null && did != '') {
				jQuery("#mapper").disabled = true;
				jQuery("#demap").fadeIn(300);
				jQuery("#demap").fadeOut(600);
			}
		}
	}
	//ClearPreviousSelection();
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
		if (selectedchkId > 0 && lastVehicleId > 0) {
			
			
			jQuery.ajax({
			type: "GET",
			url: "route_ajax.php",
			async: true,
			data: {
				d: selectedchkId,ds:lastVehicleId
			},
			cache: false,
			success: function (data) {
				window.location = "checkpoint.php?id=3";
			}
					
		});
		
		} else {
			alert("Please select a chk And Vehicle");
		}
	} else {
		jQuery("#demap").fadeIn(300);
		jQuery("#demap").fadeOut(600);
	}
}

function demap() {
	if (selectedchkId > 0 && lastVehicleId > 0) {
		
		jQuery.ajax({
			type: "GET",
			url: "route_ajax.php",
			async: true,
			data: {
				ds: lastVehicleId
			},
			cache: false,
			success: function (data) {
				window.location = "checkpoint.php?id=3";
			}
					
		});
		
		
		
	} else {
		alert("Please select a chk And Vehicle");
	}
}
