var gmapsinited = false;
var eviction_list = [];
var ecodeid;
var cdata;
var firsttime = 0;
jQuery.noConflict();
jQuery(document).ready(function () {
	var browserHeight = jQuery(window).height();
	jQuery('#content').css("height", browserHeight - 190);
	jQuery('#sidebar').css("height", browserHeight - 190);
	jQuery('#map').css("height", browserHeight - 160);
});

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
		zoom: 11,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map($("map"), myOptions);
	gmapsinited = true;
}

function mapvehicles() {
	vehicleid = $('vehicleid').getValue()
	if (vehicleid == "Select Vehicle" || vehicleid == 'all') {
		ecodeid = $('ecodeid').getValue();
		var params = "ecodeid=" + encodeURIComponent(ecodeid);
		new Ajax.Request('modules/ecode/route_ajax.php?get=all', {
			parameters: params,
			onSuccess: function (transport) {
				cdata = transport.responseText.evalJSON();
				var count = cdata.result.length;
				if (count > 0) {
					plotvehicles(cdata);
					if (firsttime == 0) {
						GetDropDownValues(cdata);
					}
					firsttime = 1;
					periodicupdate();
					jQuery('#eshow').show();
				} else {
					alert("Incorrect/ Invalid code");
					jQuery('#eshow').hide(100);
					var candy = 0;
					jQuery('#map').hide();
					jQuery('#ehide').show();
					$("eecode").show();
					jQuery("#eecode").fadeOut(5000);
				}
			},
			onComplete: function () {
				if (candy == 0) {
					jQuery('#ehide').show();
					$("eecode").show();
					jQuery("#eecode").fadeOut(5000);
				}
			}
		});
	} else {
		getvehicle();
		periodicupdate();
	}
}

function periodicupdate() {
	mapvehicles.delay(30);
}

function plotvehicles(cdata) {
	evictMarkers();
	var results = cdata.result;
	results.each(function (device) {
		try {
			function closure() {
				infowindow.close();
			}
			initmap(device.cgeolat, device.cgeolong);
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
			map.panTo(marker.getPosition());
			eviction_list.push(marker);
			var contentString = '<h3>' + device.cname + '</h3><hr><p>' +
				'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + ']<br>' +
				'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : ' + device.clastupdated;
			var infowindow = new google.maps.InfoWindow({
				content: contentString
			});
			google.maps.event.addListener(marker, 'mouseover', function () {
				infowindow.open(map, marker);
				window.setInterval(closure, 5000);
			});
		} catch (ex) {
			alert(ex);
		}
	});
}

function getvehicle() {
	vehicleid = $('vehicleid').getValue()
	if (vehicleid != "Select Vehicle" && vehicleid != 'all') {
		evictMarkers();
		var params = "vehicleid=" + encodeURIComponent(vehicleid);
		new Ajax.Request('modules/ecode/route_ajax.php?get=1', {
			parameters: params,
			onSuccess: function (transport) {
				var cdata = transport.responseText.evalJSON();
				plotvehicles(cdata);
			},
			onComplete: function () {}
		});
	} else if (vehicleid == 'all') {
		mapvehicles()
	}
}

function evictMarkers() {
	// clear all markers
	eviction_list.forEach(function (item) {
		item.setMap(null)
	});
	// reset the eviction array 
	eviction_list = [];
}

function GetDropDownValues(values) {
	var results = values.result;
	results.each(function (device) {
		AddItem(device.cname, device.cvehicleid);
	});
}

function AddItem(Text, Value) {
	// Create an Option object
	var opt = document.createElement("option");
	// Add an Option object to Drop Down/List Box
	document.getElementById("vehicleid").options.add(opt);
	// Assign text and value to Option object
	opt.text = Text;
	opt.value = Value;
}

function getout() {
	location.reload();
}