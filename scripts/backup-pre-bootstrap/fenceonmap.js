var gmapsinited = false;
var eviction_list = [];
var route = [];
var fenceid = getUrlVars()["fid"];
var device;

function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
		vars[key] = value;
	});
	return vars;
}

function initialize() {
	var latlng = new google.maps.LatLng(19.07, 72.89);
	var myOptions = {
		zoom: 11,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(document.getElementById("map"), myOptions);
}

function initmap(lat, lng) {
	if (gmapsinited) return;
	var latlng = new google.maps.LatLng(lat, lng);
	var myOptions = {
		zoom: 11,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map($("map"), myOptions);
	gmapsinited = true;
}

function mapvehicle() {
	fence = "fenceid=" + encodeURIComponent(fenceid);
	new Ajax.Request('../../modules/fencing/route_ajax.php?get=vehicle', {
		parameters: fence,
		onSuccess: function (transport) {
			var vdata = transport.responseText.evalJSON();
			plotvehicle(vdata);
		},
		onComplete: function () {}
	});
}

function plotvehicle(vdata) {
	evictMarkers();
	var results = vdata.result;
	results.each(function (device) {
		try {
			var image = new google.maps.MarkerImage(device.image,
				new google.maps.Size(32, 32),
				new google.maps.Point(0, 0),
				new google.maps.Point(8, 20));
			var myLatLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				icon: image,
				title: device.name
			});
			eviction_list.push(marker);
			var contentString = '<h3>' + device.cname + '</h3><hr><p>' +
				'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + ']<br>' +
				'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : ' + device.clastupdated +
				'<hr><a href=../history/history.php?id=5&vid=' + device.cvehicleid + '>Map Vehicle</a>' +
				'<a href=../history/history.php?id=1&vid=' + device.cvehicleid + '> Vehicle History </a>';
			var infowindow = new google.maps.InfoWindow({
				content: contentString
			});
			google.maps.event.addListener(marker, 'click', function () {
				infowindow.open(map, marker);
			});
		} catch (ex) {
			alert(ex);
		}
	});
}

function periodicupdate() {
	mapvehicles.delay(60);
}

function mapfence() {
	fence = "fenceid=" + encodeURIComponent(fenceid);
	new Ajax.Request('../../modules/fencing/route_ajax.php?get=fence', {
		parameters: fence,
		onSuccess: function (transport) {
			var fdata = transport.responseText.evalJSON();
			plotfence(fdata);
		},
		onComplete: function () {}
	});
}

function plotfence(fdata) {
	var results = fdata.result;
	results.each(function (device) {
		try {
			initmap(device.cgeolat, device.cgeolong);
			var fencelatlng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
			var marker = new google.maps.Marker({
				position: fencelatlng,
				map: map,
				title: device.name
			});
			route.push(fencelatlng);
		} catch (ex) {
			alert(ex);
		}
	});
	cpolyline();
}

function cpolyline() {
	route.push(route[0]);
	var polyline = new google.maps.Polyline({
		path: route,
		strokeColor: "#ff0000",
		strokeWeight: 3,
		strokeOpacity: 0.4
	});
	polyline.setMap(map);
}

function evictMarkers() {
	// clear all markers
	eviction_list.forEach(function (item) {
		item.setMap(null)
	});
	// reset the eviction array 
	eviction_list = [];
}

function loaded() {
	initialize();
	mapfence();
	mapvehicle();
}
Event.observe(window, 'load', function () {
	loaded();
});