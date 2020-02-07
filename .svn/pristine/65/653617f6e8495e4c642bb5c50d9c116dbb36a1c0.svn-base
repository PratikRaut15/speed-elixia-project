var gmapsinited = false;
var eviction_list = [];
var route = [];
var vehicleid;
var device;
var play = 0;
jQuery.noConflict();
jQuery(document).ready(function () {
	// Handler for .ready() called.
	var browserHeight = jQuery(window).height();
	var browserwidth = jQuery(window).width();
	jQuery('#map').css("height", browserHeight);
	jQuery('#map').css("width", browserwidth);
});

function call_row(id) {
	
	if(jQuery('#rem_' + id).length!=0){
		jQuery('#rem_' + id).remove();
		}else{
	var vehicleid = jQuery("#vehicle" + id).val();
	var unitno = jQuery("#unitno" + id).val();
	var date = jQuery("#date" + id).val();
	var timestamp = jQuery("#timestamp" + id).val();
	jQuery.ajax({
		type: "POST",
		url: "route_ajax_history.php",
		async: true,
		data: {
			vehicleid: vehicleid,
			date: date,
			timestamp: timestamp,
			unitno: unitno
		},
		cache: false,
		success: function (data) {
			jQuery('#rem_' + id).remove();
			jQuery("#" + id).after("<tr id='rem_" + id + "'><td  colspan='100%'>" + data + "</td></tr>");
		}
	});
		}
}

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
	map = new google.maps.Map($("map"), myOptions);
	gmapsinited = true;
}

function getroutehist() {
	var SDDate = ($('SDate').value).split('-');
	SDDate = new Date(SDDate[2], SDDate[1] - 1, SDDate[0])
	var EDDate = ($('EDate').value).split('-');
	EDDate = new Date(EDDate[2], EDDate[1] - 1, EDDate[0])
	if ($('vehicleid').value == -1) {
		$('error4').show();
		jQuery('#error4').fadeOut(3000);
	} else if (SDDate > EDDate) {
		$('error3').show();
		jQuery('#error3').fadeOut(3000);
	} else if (play == 0) {
		play = 1;
		vehicleid = "vehicleid=" + encodeURIComponent($('vehicleid').value);
		var SDate = "&SDate=" + encodeURIComponent($('SDate').value);
		var EDate = "&EDate=" + encodeURIComponent($('EDate').value);
		var Shour = "&Shour=" + encodeURIComponent($('Shour').value);
		var Ehour = "&Ehour=" + encodeURIComponent($('Ehour').value);
		var params = vehicleid + SDate + EDate + Shour + Ehour;
		new Ajax.Request('route_ajax_trip.php', {
			parameters: params,
			onSuccess: function (transport) {
				var cdata = transport.responseText.evalJSON();
				var data = cdata.result;
				if (data.length != 0) {
					plotvehiclehist(cdata);
					mapcheckpoint();
				} else {
					$('error2').show();
					jQuery('#error2').fadeOut(3000);
					play = 0;
				}
			},
			onComplete: function () {}
		});
	} else
		$('error').show();
	jQuery('#error').fadeOut(3000);
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
	map.setZoom(13);
}

function mapcheckpoint() {
	new Ajax.Request('../common/getchkforvehicle.php', {
		parameters: vehicleid,
		onSuccess: function (transport) {
			var cdata = transport.responseText.evalJSON();
			plotCheckpoint(cdata);
		},
		onComplete: function () {}
	});
}

function plotCheckpoint(cdata) {
	var results = cdata.result;
	results.each(function (device) {
		try {
			initmap(device.cgeolat, device.cgeolong);
			var myLatLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
			/*var marker = new google.maps.Marker({position: myLatLng,map: map,title: device.name});*/
			var marker = new MarkerWithLabel({
				position: myLatLng,
				map: map,
				labelContent: device.cname,
				labelAnchor: new google.maps.Point(9, 45),
				labelClass: "mapslabels_chkp" // the CSS class for the label
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

function refresh() {
	window.location.href = window.location.href;
}

function loaded() {
	initialize();
	getroutehist();
}
//Event.observe(window,'load', function() {loaded();});