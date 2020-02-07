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
	if ($("d_" + lastchkId) != null) {
		$("d_" + lastchkId).removeClassName("selected");
	}
	selectedchkId = chkId;
	$("d_" + chkId).addClassName("selected");
	var params = "d=" + selectedchkId;
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
	lastchkId = chkId;
	chk = $("dl_" + chkId).value;
	if (chk != null && chk != "") {
		var vid = $("dl_" + chkId).value;
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
			if ($("vl_" + vehicleID).value != "" && lastchkId == 0) {
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
		if (selectedchkId > 0 && lastVehicleId > 0) {
			var params = "d=" + selectedchkId + "&ds=" + lastVehicleId;
			new Ajax.Request('route_ajax.php', {
				parameters: params,
				onSuccess: function (transport) {
					window.location = "checkpoint.php?id=3";
				},
				onFailure: function () {}
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
		var params = "ds=" + lastVehicleId;
		new Ajax.Request('route_ajax.php', {
			parameters: params,
			onSuccess: function (transport) {
				window.location = "checkpoint.php?id=3";
			},
			onFailure: function () {}
		});
	} else {
		alert("Please select a chk And Vehicle");
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