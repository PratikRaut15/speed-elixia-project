var gmapsinited = false;
var eviction_list = [];
var route = [];
var vehicleid;
var device;
var play = 0;




jQuery.noConflict();
jQuery(document).ready(function () {

loaded();


});



function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
		vars[key] = value;
	});
	return vars;
}

function initialize() {
	var latlng = new google.maps.LatLng(20, 72);
	var myOptions = {
		zoom: 5,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(document.getElementById("map"), myOptions);
}

function initmap(lat, lng) {
	if (gmapsinited) return;
	var latlng = new google.maps.LatLng(lat, lng);
	var myOptions = {
		zoom: 15,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(jQuery("#map"), myOptions);
	gmapsinited = true;
}

function mapcheckpoint() {
	
	
	jQuery.ajax({
						type: "POST",
						url: "../common/getchkforvehicle.php",
						data:{vehicleid:vehicleid},
						async: true,
						cache: false,
						dataType:"json",
						success: function (cdata) {
						
							plotCheckpoint(cdata);
						}
					});
	
	
	
	/*new Ajax.Request('../common/getchkforvehicle.php', {
		parameters: vehicle,
		onSuccess: function (transport) {
			var cdata = transport.responseText.evalJSON();
			plotCheckpoint(cdata);
		},
		onComplete: function () {}
	});*/
}

function plotCheckpoint(cdata) {
	var results = cdata.result;
	
	jQuery(results).each(function (device) {
		try {
			initmap(device.cgeolat, device.cgeolong);
			var myLatLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				title: device.name
			});
			var circle = new google.maps.Circle({
				map: map,
				radius: device.crad,
				fillColor: '#AA0000',
				strokeColor: '#AA0000',
				strokeweight: 1
			});
			circle.bindTo('center', marker, 'position');
			var contentString = '<h3>' + device.cname + '</h3><br>Checkpoint Radius [Meters] = ' + device.crad;
			var infowindow = new google.maps.InfoWindow({
				content: contentString
			});
			google.maps.event.addListener(marker, 'mouseover', function () {
				infowindow.open(map, marker);
			});
			google.maps.event.addListener(marker, 'mouseout', function () {
				infowindow.close();
			});
		} catch (ex) {
			alert(ex);
		}
	});
}

function mapvehiclehistory() {
	mapcheckpoint();
	if (play == 0) {
		play = 1;
		/*vehicle = "vehicleid=" + encodeURIComponent(vehicleid);*/
		jQuery.ajax({
						type: "POST",
						url: "route_ajax.php",
						data:{vehicleid:vehicleid},
						async: true,
						cache: false,
						dataType:"json",
						success: function (cdata) {
						
							plotvehiclehist(cdata);
						}
					});
		
		
		
		
		/*new Ajax.Request('route_ajax.php', {
			parameters: vehicle,
			onSuccess: function (transport) {
				var cdata = transport.responseText.evalJSON();
				plotvehiclehist(cdata);
			},
			onComplete: function () {}
		});*/
	} else {
		$('error').show();
		jQuery('#error').fadeOut(3000);
	}
}

function plotvehiclehist(cdata) {
	evictMarkers();
	var results = cdata.result;
	route = [];
	var index = 0;
	var plots = results.length;

	function plot() {
		var device = results[index];
		if (index < plots) {
			try {
				function closure() {
					infowindow.close();
				}
				initmap(device.cgeolat, device.cgeolong);
				var image = new google.maps.MarkerImage(device.image,
					new google.maps.Size(48, 48),
					new google.maps.Point(0, 0),
					new google.maps.Point(8, 20));
				var myLatLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
				route.push(myLatLng);
				var marker = new google.maps.Marker({
					position: myLatLng,
					map: map,
					icon: image,
					title: device.name
				});
				map.panTo(marker.position);
				eviction_list.push(marker);
				var contentString = '<h3>' + device.cname + '</h3><hr><p>' +
					'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + ']<br>' +
					'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : ' + device.clastupdated;
				var infowindow = new google.maps.InfoWindow({
					content: contentString
				});
				google.maps.event.addListener(marker, 'click', function () {
					infowindow.open(map, marker);
					window.setInterval(closure, 5000);
				});
			} catch (ex) {
				alert(ex);
			}
		} else {
			return;
		}
		index++;
		cpolyline();
	}
	plot();
	if (index < plots) {
		window.setInterval(plot, 1500);
	}
}

function cpolyline() {
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
	vehicleid = getUrlVars()["vid"];
	if (vehicleid == undefined)
		alert("No Vehicles To Map");
}

function refresh() {
	window.location.href = window.location.href;
}
