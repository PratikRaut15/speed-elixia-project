var geocodeinited = false;
var boundaryColor = '#FF0000';
var polyCoordinates = []; // initialize an array where we store latitude and longitude pair
var latitudes = "";
var longitudes = "";
var count = 0;
var countpoly = 0;
var map;
var checkstatus = false;

function initialize() {
	var latlng = new google.maps.LatLng(19.07, 72.89);
	var myOptions = {
		zoom: 11,
		center: latlng,
		draggableCursor: 'crosshair',
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("map"),
		myOptions);
	// Listen Click Event to draw Polygon
	google.maps.event.addListener(map, 'click', function (event) {
		
		if (checkstatus == false) {
			var marker = new google.maps.Marker({
				map: map,
				draggable: false,
				animation: google.maps.Animation.DROP,
				position: event.latLng
			});
			polyCoordinates[count] = event.latLng;
			if (latitudes != "") {
				latitudes += ", " + event.latLng.lat();
			} else {
				latitudes += event.latLng.lat();
			}
			if (longitudes != "") {
				longitudes += ", " + event.latLng.lng();
			} else {
				longitudes += event.latLng.lng();
			}
			createPolyline(polyCoordinates);
			count++;
		} else {
			$("fencename").show();
			jQuery("#fencename").fadeOut(3000);
		}
	});
}

function initmap(lat, lng) {
	$("map").show();
	polyCoordinates = []; // initialize an array where we store latitude and longitude pair
	latitudes = "";
	longitudes = "";
	count = 0;
	countpoly = 0;
	var latlng = new google.maps.LatLng(lat, lng);
	var myOptions = {
		zoom: 13,
		center: latlng,
		draggableCursor: 'crosshair',
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map($("map"),
		myOptions);
	// Listen Click Event to draw Polygon
	google.maps.event.addListener(map, 'click', function (event) {
		if (checkstatus == false) {
			var marker = new google.maps.Marker({
				map: map,
				draggable: true,
				animation: google.maps.Animation.DROP,
				position: event.latLng
			});
			polyCoordinates[count] = event.latLng;
			if (latitudes != "") {
				latitudes += ", " + event.latLng.lat();
			} else {
				latitudes += event.latLng.lat();
			}
			if (longitudes != "") {
				longitudes += ", " + event.latLng.lng();
			} else {
				longitudes += event.latLng.lng();
			}
			createPolyline(polyCoordinates);
			count++;
		} else {
			$("fencename").show();
			jQuery("#fencename").fadeOut(3000);
		}
	});
}

function createPolyline(polyC) {
	Path = new google.maps.Polyline({
		path: polyC,
		strokeColor: boundaryColor,
		strokeOpacity: 1.0,
		strokeWeight: 2
	});
	Path.setMap(map);
}

function connectPoints() {
	
	if (polyCoordinates.length > 2) {
		checkstatus = true;
		var point_add = [];
		var start = polyCoordinates[0];
		var end = polyCoordinates[(polyCoordinates.length - 1)];
		point_add.push(start);
		point_add.push(end);
		createPolyline(point_add);
		var params = "lat=" + latitudes + "&long=" + longitudes + "&fencename=" + encodeURIComponent($("fenceN").value);
		new Ajax.Request('route_ajax.php', {
			parameters: params,
			onSuccess: function (transport) {
				var statuscheck = transport.responseText.evalJSON();
				if (statuscheck.status == "ok") {
					//  window.location="fencing.php?id=2";
				} else if (statuscheck.status == "detailsreqd") {
					$("fencename").show();
					jQuery("#fencename").fadeOut(3000);
				} else if (statuscheck.status == "samename") {
					$("samename").show();
					jQuery("#samename").fadeOut(3000);
				} else {
					alert("Please try again. Click on Restart");
				}
			},
			onComplete: function () {}
		});
	} else {
		$("incompletefence").show();
		jQuery("#incompletefence").fadeOut(3000);
	}
}

function refreshgeofence() {
	window.location.reload(true);
}

function commaConcat(str, sval) {
	if (sval != "") {
		if (str != "") {
			return str + ", " + sval;
		} else {
			return sval;
		}
	}
	return str;
}

function locate() {
	var address = "";
	address = commaConcat(address, $("fenceT").getValue());
	address = commaConcat(address, $("fenceC").getValue());
	if (!geocodeinited) {
		geocoder = new google.maps.Geocoder();
		geocodeinited = true;
	}
	geocoder.geocode({
		'address': address
	}, function (results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			initmap(results[0].geometry.location.lat(), results[0].geometry.location.lng());
		} else {
			alert("Please check your address details or contact an Elixir about the issue: " + status);
		}
	});
}

function loaded() {
	initialize();
}
Event.observe(window, 'load', function () {
	loaded();
});