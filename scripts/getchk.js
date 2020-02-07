var gmapsinited = false;
var gmapsinitedsender = false;
var geocodeinited = false;
var geocodeinitedsender = false;
var map;
var mapsender;
var geocoder;
var geocodersender;
var eviction_list = [];
var counter = 2;
jQuery.noConflict();
jQuery(document).ready(function () {
	loaded();
	var browserHeight = jQuery(window).height();
	jQuery('.post').css("padding", 0);
	jQuery('.entry').css("padding", 0);
	jQuery("#gc-topnav2").draggable();
	jQuery('#map').css("height", browserHeight - 250);
});

function showform() {
	if (counter % 2 == 0) {
		jQuery('#gc-topnav2').css("display", "block");
		jQuery('#map').css("z-index", "-1000");
		jQuery('#gc-topnav2').css("z-index", "1000");
		jQuery('#toggler').val("Hide");
		jQuery('#toggler').css("background", "#000");
		counter++;
	} else {
		jQuery('#toggler').val("Create Checkpoints");
		jQuery('#map').css("z-index", "+1000");
		jQuery('#gc-topnav2').css("display", "none");
		jQuery('#toggler').css("background", "#4D90FE");
		counter++;
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
	var latlng = new google.maps.LatLng(19.07, 72.89);
	var myOptions = {
		zoom: 11,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(document.getElementById("map"), myOptions);
}

function initmap(lat, lng) {
	if (gmapsinited) return;
	var latlng = new google.maps.LatLng(lat, lng);
	var myOptions = {
		zoom: 15,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(jQuery("map"), myOptions);
	gmapsinited = true;
	google.maps.event.addListener(map, 'click', function (event) {
		evictMarkers();
		var marker = new google.maps.Marker({
			map: map,
			draggable: false,
			animation: google.maps.Animation.DROP,
			position: event.latLng
		});
		jQuery("cgeolat").val() = event.latLng.lat();
		jQuery("cgeolong").val() = event.latLng.lng();
		map.setCenter(marker.getPosition());
		var rad = jQuery("chkRad").value()
		if (rad != "" || rad != 0) {
			rad = rad * 1000;
			var circle = new google.maps.Circle({
				map: map,
				radius: rad,
				fillColor: '#AA0000',
				strokeColor: '#AA0000',
				strokeweight: 1
			});
			circle.bindTo('center', marker, 'position');
			google.maps.event.addListener(circle, 'click', function () {
				circle.setMap(null);
			});
			eviction_list.push(circle);
		}
		eviction_list.push(marker);
	});
	google.maps.event.addDomListener(document.getElementById('chkRad'), 'change', function () {
		evictMarkers();
		var myLatLng = new google.maps.LatLng(jQuery("cgeolat").val(), jQuery("cgeolong").val());
		var marker = new google.maps.Marker({
			map: map,
			draggable: false,
			animation: google.maps.Animation.DROP,
			position: myLatLng
		});
		var rad = jQuery("#chkRad").val()
		if (rad != "" || rad != 0) {
			rad = rad * 1000;
			var circle = new google.maps.Circle({
				map: map,
				radius: rad,
				fillColor: '#AA0000',
				strokeColor: '#AA0000',
				strokeweight: 1
			});
			circle.bindTo('center', marker, 'position');
			google.maps.event.addListener(circle, 'click', function () {
				circle.setMap(null);
			});
			eviction_list.push(circle);
		}
		eviction_list.push(marker);
	});
}

function initmapsender(lat, lng) {
	if (gmapsinitedsender) return;
	var latlng = new google.maps.LatLng(lat, lng);
	var myOptions = {
		zoom: 15,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	map = new google.maps.Map(jQuery("map"),
		myOptions);
	gmapsinitedsender = true;
}

function commaConcat(str, sval) {
	if (sval != "") {
		if (str != "")
			return str + ", " + sval;
		else
			return sval;
	}
	return str;
}

function locate() {
	evictMarkers();
	var address = "";
	address = commaConcat(address, jQuery("chkA").val());
	address = commaConcat(address, jQuery("chkT").val());
	address = commaConcat(address, jQuery("chkRN").val());
	address = commaConcat(address, jQuery("chkC").val());
	address = commaConcat(address, jQuery("chkS").val());
	address = commaConcat(address, jQuery("chkZC").val());
	var image = new google.maps.MarkerImage('../../images/flag.png',
		new google.maps.Size(16, 16),
		new google.maps.Point(0, 0),
		new google.maps.Point(16, 26),
		new google.maps.Size(16, 16));
	if (!geocodeinited) {
		geocoder = new google.maps.Geocoder();
		geocodeinited = true;
	}
	geocoder.geocode({
		'address': address
	}, function (results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			initmap(results[0].geometry.location.lat(), results[0].geometry.location.lng());
			markerlatlng = results[0].geometry.location;
			var marker = new google.maps.Marker({
				map: map,
				draggable: true,
				animation: google.maps.Animation.DROP,
				title: address,
				position: results[0].geometry.location
			});
			jQuery("cgeolat").val() = marker.getPosition().lat();
			jQuery("cgeolong").val() = marker.getPosition().lng();
			map.setCenter(marker.getPosition());
			eviction_list.push(marker);
			hideform();
			//$('radius').show();
			//jQuery('#radius').fadeOut(5000);
			jQuery("chkRadField").show();
			jQuery("chkRadTd").show();
			jQuery("sel_veh").show();
			jQuery("#nameid").css("display", "block");
			jQuery("#p1").css("display", "none");
			jQuery("#locateinp").css("display", "none");
			jQuery("#checkpts").css("display", "block");
		} else
			alert("Please check your address details or contact an Elixir about the issue : " + status);
	});
}

function view_checkpoints(checkpointid) {
	if (checkpointid) {
		jQuery.ajax({
			type: "GET",
			url: "route_ajax.php",
			async: true,
			data: {
				checkpointid: checkpointid
			},
			cache: false,
			success: function (data) {
				//alert(data);
				var json = eval('(' + data + ')');
				var latlng = new google.maps.LatLng(json.cgeolat, json.cgeolong);
				var myOptions = {
					zoom: 15,
					center: latlng,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				map = new google.maps.Map(jQuery("map"),
					myOptions);
				var marker = new google.maps.Marker({
					map: map,
					draggable: false,
					animation: google.maps.Animation.DROP,
					position: latlng
				});
				var rad = json.crad * 1000;
				var circle = new google.maps.Circle({
					map: map,
					radius: rad,
					fillColor: '#AA0000',
					strokeColor: '#AA0000',
					strokeweight: 1
				});
				circle.bindTo('center', marker, 'position');
			}
		});
	}
}

function hideform() {
	jQuery("#gc-topnav2").hide();
	jQuery('#map').css("z-index", "+1000");
	jQuery('#toggler1').hide();
	jQuery('#clearb').hide();
	jQuery('#toggler2').show();
	jQuery('#gc-topnav2').css("height", "42px");
	counter++;
}

function plotCheckpoints() {
	if (jQuery("cgeolat").val() != "" && jQuery("cgeolong").val() != "") {
		initmap(jQuery("cgeolat").val(), jQuery("cgeolong").val());
		var myLatLng = new google.maps.LatLng(jQuery("cgeolat").val(), jQuery("cgeolong").val());
		var marker = new google.maps.Marker({
			map: map,
			draggable: true,
			animation: google.maps.Animation.DROP,
			position: myLatLng
		});
		map.setCenter(marker.getPosition());
		var rad = jQuery("#chkRad").val()
		if (rad != "" || rad != 0) {
			rad = rad * 1000;
			var circle = new google.maps.Circle({
				map: map,
				radius: rad,
				fillColor: '#AA0000',
				strokeColor: '#AA0000',
				strokeweight: 1
			});
			circle.bindTo('center', marker, 'position');
			google.maps.event.addListener(circle, 'click', function () {
				circle.setMap(null);
			});
			eviction_list.push(circle);
		}
		eviction_list.push(marker);
	}
	jQuery("chkRadField").show();
	jQuery("chkRadTd").show();
}

function evictMarkers() {
	// clear all markers
	eviction_list.forEach(function (item) {
		item.setMap(null)
	});
	// reset the eviction array 
	eviction_list = [];
}

function clearfields() {
	loaded();
	var elements = document.getElementsByTagName("input");
	jQuery().each(function(index,element){
				if (elements[i].type == "text" && elements[i].id != "chkN") {
			elements[i].val() = "";
		}		   
						   });
	
	/*for (var i = 0; i < elements.length; i++) {
		if (elements[i].type == "text" && elements[i].id != "chkN") {
			elements[i].val() = "";
		}
	}*/
	evictMarkers()
}

function loaded() {
	initialize();
	var pageid = getUrlVars()["id"];
	if (pageid != null && pageid == 4)
		plotCheckpoints();
}



