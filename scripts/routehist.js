var gmapsinited = false;
var eviction_list = [];
var route = [];
var vehicleid;
var device;
var play = 0;
var styles =[
{ "featureType": "road", "elementType": "geometry.stroke", "stylers": [ { "visibility": "off" } ] },{ "featureType": "poi.park", "stylers": [ { "visibility": "simplified" }, { "lightness": 46 } ] },{ "featureType": "poi", "elementType": "labels", "stylers": [ { "visibility": "on" } ] },{ "featureType": "road.highway", "elementType": "labels", "stylers": [ { "visibility": "off" } ] },{ "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [ { "color": "#9e9e9f" } ] },{ "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [ { "color": "#bfbfbf" } ] },{ "featureType": "road.local", "elementType": "geometry.fill", "stylers": [ { "color": "#e0e0e0" } ] },{ "featureType": "poi.park", "stylers": [ { "lightness": 38 } ] },{ "stylers": [ { "saturation": -54 } ] }];

jQuery.noConflict();
jQuery(document).ready(function () {
	
	loaded();								 
								 
								 
	// Handler for .ready() called.
	var browserHeight = jQuery(window).height();
	jQuery('#map').css("height", browserHeight - 160);
});

function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
		vars[key] = value;
	});
	return vars;
}

function initialize() {
    
    var styledMap = new google.maps.StyledMapType(styles,
    {name: "Styled Map"});

    var latlng = new google.maps.LatLng(20, 72);
    var mapOptions = {
		zoom: 5,
		center: latlng,
		panControl: true,
		streetViewControl:false,
                mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
		zoomControl: true,
		zoomControlOptions: {
		style: google.maps.ZoomControlStyle.SMALL
		},
		styles:styles
    };
    map = new google.maps.Map(document.getElementById('map'), mapOptions);
	map.mapTypes.set('map_style', styledMap);
	map.setMapTypeId('map_style');
}

function initmap(lat, lng) {
	if (gmapsinited) return;

    var styledMap = new google.maps.StyledMapType(styles,
    {name: "Styled Map"});

	var latlng = new google.maps.LatLng(lat, lng);
        var mapOptions = {
                    zoom: 15,
                    center: latlng,
                    panControl: true,
                    streetViewControl:false,
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
                    zoomControl: true,
                    zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL
                    },
                    styles:styles
        };
        map = new google.maps.Map(document.getElementById('map'), mapOptions);
            map.mapTypes.set('map_style', styledMap);
            map.setMapTypeId('map_style');

    gmapsinited = true;
}

function getroutehistNew() {
            var dtDate1 = jQuery('#SDate').val().split('-');
            var dtDate2 = dtDate1[2]+'/'+dtDate1[1]+'/'+dtDate1[0];
            var dtDate3 = jQuery('#EDate').val().split('-');
            var dtDate4 = dtDate3[2]+'/'+dtDate3[1]+'/'+dtDate3[0];
            var nDifference = Math.abs(new Date(dtDate4) - new Date(dtDate2));
            var one_day = 1000*60*60*24;
            var diff = Math.round(nDifference/one_day)
        
	var SDDate = (jQuery('#SDate').val()).split('-');
	SDDate = new Date(SDDate[2], SDDate[1] - 1, SDDate[0])
	var EDDate = (jQuery('#EDate').val()).split('-');
	EDDate = new Date(EDDate[2], EDDate[1] - 1, EDDate[0])
	if (jQuery('#vehicleid').val() == -1) {
		jQuery('#error4').show();
		jQuery('#error4').fadeOut(3000);
	} else if (SDDate > EDDate) {
		jQuery('#error3').show();
		jQuery('#error3').fadeOut(3000);
	} else if (diff > 3) {
		jQuery('#error5').show();
		jQuery('#error5').fadeOut(3000);
	} else if (play == 0) {
		play = 1;
		vehicleid = "vehicleid=" + encodeURIComponent(jQuery('#vehicleid').val());
		var SDate = "&SDate=" + encodeURIComponent(jQuery('#SDate').val());
		var EDate = "&EDate=" + encodeURIComponent(jQuery('#EDate').val());
		var STime = "&STime=" + jQuery('#STime').val();
		var ETime = "&ETime=" + jQuery('#ETime').val();
		var params = vehicleid + SDate + EDate + STime + ETime;
		
		jQuery.ajax({
                        type: "POST",
                        url: "route_ajax.php?all=1",
                        async: true,
                        cache: false,
                        data:params,
                        success: function (data) {
                                          var cdata = jQuery.parseJSON(data);

                                                var data = cdata.result;
                                                if (data.length != 0) {
                                                        plotvehiclehist(cdata);
                                                        mapcheckpoint()
                                                } else {
                                                        jQuery('#error2').show();
                                                        jQuery('#error2').fadeOut(3000);
                                                        play = 0;
                                                }
                                                }
                            });
	} else
		jQuery('#error').show();
	jQuery('#error').fadeOut(3000);
}

function getroutehist() {
            var dtDate1 = jQuery('#SDate').val().split('-');
            var dtDate2 = dtDate1[2]+'/'+dtDate1[1]+'/'+dtDate1[0];
            var dtDate3 = jQuery('#EDate').val().split('-');
            var dtDate4 = dtDate3[2]+'/'+dtDate3[1]+'/'+dtDate3[0];
            var nDifference = Math.abs(new Date(dtDate4) - new Date(dtDate2));
            var one_day = 1000*60*60*24;
            var diff = Math.round(nDifference/one_day)
        
	var SDDate = (jQuery('#SDate').val()).split('-');
	SDDate = new Date(SDDate[2], SDDate[1] - 1, SDDate[0])
	var EDDate = (jQuery('#EDate').val()).split('-');
	EDDate = new Date(EDDate[2], EDDate[1] - 1, EDDate[0])
	if (jQuery('#vehicleid').val() == -1) {
		jQuery('#error4').show();
		jQuery('#error4').fadeOut(3000);
	} else if (SDDate > EDDate) {
		jQuery('#error3').show();
		jQuery('#error3').fadeOut(3000);
	} else if (diff > 3) {
		jQuery('#error5').show();
		jQuery('#error5').fadeOut(3000);
	} else if (play == 0) {
		play = 1;
		vehicleid = "vehicleid=" + encodeURIComponent(jQuery('#vehicleid').val());
		var SDate = "&SDate=" + encodeURIComponent(jQuery('#SDate').val());
		var EDate = "&EDate=" + encodeURIComponent(jQuery('#EDate').val());
		var Shour = "&Shour=" + encodeURIComponent(jQuery('#Shour').val());
		var Ehour = "&Ehour=" + encodeURIComponent(jQuery('#Ehour').val());
		var Smin = "&Smin=" + encodeURIComponent(jQuery('#Smin').val());
		var Emin = "&Emin=" + encodeURIComponent(jQuery('#Emin').val());
		var params = vehicleid + SDate + EDate + Shour + Ehour + Smin + Emin;
		
		
		jQuery.ajax({
        type: "POST",
        url: "route_ajax.php?all=1",
        async: true,
        cache: false,
		data:params,
        success: function (data) {
			  var cdata = jQuery.parseJSON(data);
                   					
				var data = cdata.result;
				if (data.length != 0) {
					plotvehiclehist(cdata);
					mapcheckpoint()
				} else {
					jQuery('#error2').show();
					jQuery('#error2').fadeOut(3000);
					play = 0;
				}
			
					
					
		}
					});
		
		
		
		
	} else
		jQuery('#error').show();
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
}

function mapcheckpoint() {
	
	jQuery.ajax({
        type: "POST",
        url: "../common/getchkforvehicle.php",
        async: true,
        cache: false,
		data:vehicleid,
        success: function (data) {
			var cdata = jQuery.parseJSON(data);
                   					
			
			plotCheckpoint(cdata);
		}
		
	});
	
}

function plotCheckpoint(cdata) {
	var results = cdata.result;
	 jQuery.each(results, function (i, device) {
		try {
			initmap(device.cgeolat, device.cgeolong);
			var myLatLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
			// var marker = new google.maps.Marker({
			//                position: myLatLng,
			//                map: map,
			//                title: device.name
			//            });
			var marker = new MarkerWithLabel({
				position: myLatLng,
				map: map,
				labelContent: device.cname,
				labelAnchor: new google.maps.Point(9, 45),
				labelClass: "mapslabels" // the CSS class for the label
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
}
