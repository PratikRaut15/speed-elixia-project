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
	if ($("d_" + lastDriverId) != null) {
		$("d_" + lastDriverId).removeClassName("selected");
	}
	selectedDriverId = DriverId;
	$("d_" + DriverId).addClassName("selected");
	var params = "d=" + selectedDriverId;
	new Ajax.Request('route_ajax.php', {
		parameters: params,
		onSuccess: function (transport) {
			var statuscheck = transport.responseText;
			if (statuscheck == "ok") {
				$("mapper").disabled = false;
			} else {
				$("mapper").disabled = true;
				jQuery("#demap").fadeIn(300);
				jQuery("#demap").fadeOut(600);
			}
		},
		onFailure: function () {}
	});
	lastDriverId = DriverId;
	if ($("dl_" + DriverId).value != null && $("dl_" + DriverId).value != "") {
		var vid = $("dl_" + DriverId).value;
		sd(vid);
	}
}

function DeSelectElement(id) {
	$("v_" + id).removeClassName("selected");
}

function SelectElement(id) {
	$("v_" + id).addClassName("selected");
}

function sd(vehicleID) {
	if ($("v_" + lastVehicleId) != null) {
		$("v_" + lastVehicleId).removeClassName("selected");
	}
	if ("v_" + vehicleID != null) {
		var did = $("vl_" + vehicleID).value;
		SelectElement(vehicleID);
		if ($("vl_" + vehicleID).value != "" && lastDriverId == 0) {
			st(did);
		}
		if (did != null && did != '') {
			$("mapper").disabled = true;
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
	if ($('vl_' + lastVehicleId).value == "") {
		if (selectedDriverId > 0 && lastVehicleId > 0) {
			var params = "d=" + selectedDriverId + "&ds=" + lastVehicleId;
			new Ajax.Request('route_ajax.php', {
				parameters: params,
				onSuccess: function (transport) {
					window.location = "driver.php?id=3";
				},
				onFailure: function () {}
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
		var params = "ds=" + lastVehicleId;
		new Ajax.Request('route_ajax.php', {
			parameters: params,
			onSuccess: function (transport) {
				window.location = "driver.php?id=3";
			},
			onFailure: function () {}
		});
	} else {
		alert("Please select a Driver And Vehicle");
	}
}

function loaded() {
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
});