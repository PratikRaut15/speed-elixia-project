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
var lastFenceId = 0;
var selectedFenceId = 0;

function st(FenceId) {
	if ($("d_" + lastFenceId) != null) {
		$("d_" + lastFenceId).removeClassName("selected");
	}
	selectedFenceId = FenceId;
	$("d_" + FenceId).addClassName("selected");
	var params = "d=" + selectedFenceId;
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
	lastFenceId = FenceId;
	fence = $("dl_" + FenceId).value;
	if (fence != null && fence != "") {
		var vid = $("dl_" + FenceId).value;
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
		if ($("vl_" + vehicleID) != null) {
			var did = $("vl_" + vehicleID).value;
			SelectElement(vehicleID);
			if ($("vl_" + vehicleID).value != "" && lastFenceId == 0) {
				st(did);
			}
			if (did != null && did != '') {
				$("mapper").disabled = true;
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
	if ($('vl_' + lastVehicleId).value == "") {
		if (selectedFenceId > 0 && lastVehicleId > 0) {
			var params = "d=" + selectedFenceId + "&ds=" + lastVehicleId;
			new Ajax.Request('route_ajax.php', {
				parameters: params,
				onSuccess: function (transport) {
					window.location = "fencing.php?id=3";
				},
				onFailure: function () {}
			});
		} else {
			alert("Please select a fence And Vehicle");
		}
	} else {
		jQuery("#demap").fadeIn(300);
		jQuery("#demap").fadeOut(600);
	}
}

function demap() {
	if (selectedFenceId > 0 && lastVehicleId > 0) {
		var params = "ds=" + lastVehicleId;
		new Ajax.Request('route_ajax.php', {
			parameters: params,
			onSuccess: function (transport) {
				window.location = "fencing.php?id=3";
			},
			onFailure: function () {}
		});
	} else {
		alert("Please select a fence And Vehicle");
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