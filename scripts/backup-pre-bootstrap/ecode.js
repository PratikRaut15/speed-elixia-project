function addvehicle() {
	var vehicleid = $('vehicleid').getValue();
	if (vehicleid > -1 && $('to_vehicle_div_' + vehicleid) == null) {
		var selected_name = $('vehicleid').options[$('vehicleid').selectedIndex].text;
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removeVehicle(vehicleid);
		};
		div.className = 'recipientbox';
		div.id = 'to_vehicle_div_' + vehicleid;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
		$('vehicle_list').appendChild(div);
		$(div).appendChild(remove_image);
	}
	$('vehicleid').selectedIndex = 0;
}

function removeVehicle(checkpoint_no) {
	$('to_vehicle_div_' + checkpoint_no).remove();
}

function addallvehicle() {
	var select_box = $('vehicleid');
	for (var i = 1; i < select_box.options.length; i++) {
		select_box.selectedIndex = i;
		addvehicle();
	}
}