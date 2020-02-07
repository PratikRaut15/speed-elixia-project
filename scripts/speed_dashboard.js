function show_vehicle_details(vehicleid,date){
		jQuery.ajax({
		type: "POST",
		url: "route_ajax.php",
		async: true,
		cache: false,
		data:{vehicleid:vehicleid,date:date,work:'vehicledetails'},
		success: function (data) {
			jQuery("#myModal .modal-body").html(data);
			jQuery("#myModal").modal('show');
			
		}
		});

}
