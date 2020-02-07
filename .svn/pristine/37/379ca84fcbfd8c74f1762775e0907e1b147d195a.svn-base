var find = 0;

function pulllocation(latitude, longitude) {
	if (latitude != '' || longitude != '') {
		if (find == 0) {
			find = 1;
			var a = "latitude=" + encodeURIComponent(latitude);
			var b = "&longitude=" + encodeURIComponent(longitude);
			var params = a + b;
			$('waitmessage').show();
			jQuery('#waitmessage').fadeOut(2000);
			new Ajax.Request('route_ajax.php', {
				parameters: params,
				onSuccess: function (transport) {
					var location = transport.responseText;
					if (location.length != 0)
						alert(location);
					else
						alert('Location Unavailable');
				},
				onComplete: function () {}
			});
			find = 0;
		} else {
			jQuery('#waitmessage').fadeIn(1000);
			jQuery('#waitmessage').fadeOut(2000);
		}
	} else
		alert('GPS Unavailable');
}