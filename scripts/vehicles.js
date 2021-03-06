var gmapsinited = false;
var eviction_list = [];
var vehicleid = null;
var vehicle_list = [];
var markers = [];
var wmarkers = [];
var markersfordel = {};
var markr = [];
var markrsfordel = {};
var circlesfordel = {};
var fencesfordel = {};
var vehiclesfordel = {};
var warehousesfordel = {};
var id;
var fenid;
var fid;
var vehid;
var counter = 0;
var markerCluster;
var geocodeinited = false;
/////////// INOX DEMO PURPOSE CODE
var map1;
var directionsService;
var marker1 = [];
var polyLine = [];
var poly2 = [];
var startLocation = [];
var endLocation = [];
var timerHandle = [];
var infoWindow = null;
var startLoc = [];
var endLoc = [];
var lastVertex = 1;
var step = 1; // 5; // metres
var eol = [];
var car = "M17.402,0H5.643C2.526,0,0,3.467,0,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759c3.116,0,5.644-2.527,5.644-5.644 V6.584C23.044,3.467,20.518,0,17.402,0z M22.057,14.188v11.665l-2.729,0.351v-4.806L22.057,14.188z M20.625,10.773 c-1.016,3.9-2.219,8.51-2.219,8.51H4.638l-2.222-8.51C2.417,10.773,11.3,7.755,20.625,10.773z M3.748,21.713v4.492l-2.73-0.349 V14.502L3.748,21.713z M1.018,37.938V27.579l2.73,0.343v8.196L1.018,37.938z M2.575,40.882l2.218-3.336h13.771l2.219,3.336H2.575z M19.328,35.805v-7.872l2.729-0.355v10.048L19.328,35.805z";
var icon = {
    path: car,
    scale: .7,
    strokeColor: 'white',
    strokeWeight: .10,
    fillOpacity: 1,
    fillColor: '#404040',
    offset: '5%',
    // rotation: parseInt(heading[i]),
    anchor: new google.maps.Point(10, 25) // orig 10,50 back of car, 10,0 front of car, 10,25 center of car
};
/////////////////////END//////////
if (customerrefreshfrqmap == 267) {
    var periodictimemap = 10000;
} else {
    var periodictimemap = 60000;
}
var styles = [
    { "featureType": "road", "elementType": "geometry.stroke", "stylers": [{ "visibility": "off" }] }, { "featureType": "poi.park", "stylers": [{ "visibility": "simplified" }, { "lightness": 46 }] }, { "featureType": "poi", "elementType": "labels", "stylers": [{ "visibility": "on" }] }, { "featureType": "road.highway", "elementType": "labels", "stylers": [{ "visibility": "off" }] }, { "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{ "color": "#9e9e9f" }] }, { "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [{ "color": "#bfbfbf" }] }, { "featureType": "road.local", "elementType": "geometry.fill", "stylers": [{ "color": "#e0e0e0" }] }, { "featureType": "poi.park", "stylers": [{ "lightness": 38 }] }, { "stylers": [{ "saturation": -54 }] }
];
//aluto height adjust
jQuery.noConflict();
jQuery(document).ready(function() {
    //map init
    jQuery('.veh_all').attr('disabled', true);
    jQuery('.chk_all').attr('disabled', true);
    initialize();
    if ($("#hddn_ecode").val() != '' || $("#hddn_ecode").val() != undefined) {
        jQuery('.chk_all').attr('disabled', false);
    }
    jQuery('#gc-topnav2').css("display", "block");
    // Handler for .ready() called.
    var browserHeight = jQuery(window).height();
    jQuery('#sidebar').css("height", browserHeight - 67);
    jQuery('#map').css("height", browserHeight - 120);
    jQuery('#mapdetails').css("height", 30);
    jQuery('#wrapper').css("height", browserHeight - 117);
    jQuery('#pre').css("display", "block");
    jQuery(".all_select").click(function() {
        selectall(jQuery(this).data('type'));
    });
    jQuery(".all_clear").click(function() {
        clearall(jQuery(this).data('type'));
    });
    jQuery(".scrollablediv").height(browserHeight * 15 / 100);
    jQuery("#gc-topnav2").draggable();
    BindVehicleSearch();
    BindChkPtSearch();
    BindFenceSearch();
    initializeSelect2ForCheckPoints();
    initializeSelect2ForCheckPointTypes();
});
var currentType = null;

function selectall(type) {
    switch (type) {
        case 'vehicles':
            markers = []; //empty array
            markerCluster.clearMarkers();
            jQuery(".veh_all").each(function() {
                jQuery(this).prop('checked', true);
            });
            jQuery.each(vehiclesfordel, function(index, value) {
                marker = vehiclesfordel[index];
                if (marker) {
                    marker.setMap(map);
                }
                // Add in Cluster
                markers.push(marker);
            });
            markerCluster.clearMarkers();
            markerCluster = new MarkerClusterer(map, markers);
            break;
        case 'warehouse':
            jQuery(".wh_all").each(function() {
                jQuery(this).prop('checked', true);
            });
            jQuery.each(vehiclesfordel, function(index, value) {
                marker = vehiclesfordel[index];
                if (marker) {
                    marker.setMap(map);
                }
                // Add in Cluster
                markers.push(marker);
            });
            markerCluster.clearMarkers();
            markerCluster = new MarkerClusterer(map, markers);
            break;
        case 'checkpoints':
            jQuery(".chk_all").each(function() {
                jQuery(this).prop('checked', true);
            });
            jQuery.each(markersfordel, function(index, value) {
                marker = markersfordel[index];
                marker.setMap(map);
                circle = circlesfordel[index];
                circle.setMap(map);
            });
            break;
        case 'fences':
            jQuery(".fence_all").each(function() {
                jQuery(this).prop('checked', true);
            });
            console.log(fencesfordel);
            jQuery.each(fencesfordel, function(index, value) {
                markr = markrsfordel[index];
                markr.setMap(map);
                poly = fencesfordel[index];
                poly.setMap(map);
            });
            //        markerCluster.clearMarkers();
            //        markerCluster = new MarkerClusterer(map, markers);
            break;
    }
}

function clearall(type) {
    switch (type) {
        case 'vehicles':
            jQuery(".veh_all").each(function() {
                jQuery(this).prop('checked', false);
            });
            jQuery.each(vehiclesfordel, function(index, value) {
                marker = vehiclesfordel[index];
                if (marker) {
                    marker.setMap(null);
                }
                // Remove from Cluster
                markerCluster.clearMarkers();
                markers = [];
            });
            //        markerCluster = new MarkerClusterer(map, markers);
            break;
        case 'warehouse':
            jQuery(".wh_all").each(function() {
                jQuery(this).prop('checked', false);
            });
            jQuery.each(vehiclesfordel, function(index, value) {
                marker = vehiclesfordel[index];
                if (marker) {
                    marker.setMap(null);
                }
                // Remove from Cluster
                markerCluster.clearMarkers();
                markers = [];
            });
            //        markerCluster = new MarkerClusterer(map, markers);
            break;
        case 'checkpoints':
            jQuery(".chk_all").each(function() {
                jQuery(this).prop('checked', false);
            });
            jQuery.each(markersfordel, function(index, value) {
                marker = markersfordel[index];
                marker.setMap(null);
                circle = circlesfordel[index];
                circle.setMap(null);
            });
            break;
        case 'fences':
            jQuery(".fence_all").each(function() {
                jQuery(this).prop('checked', false);
            });
            jQuery.each(fencesfordel, function(index, value) {
                markr = markrsfordel[index];
                markr.setMap(null);
                poly = fencesfordel[index];
                poly.setMap(null);
            });
            break;
    }
}

function locate() {
    address = jQuery("#chkA").val();
    if (!geocodeinited) {
        geocoder = new google.maps.Geocoder();
        geocodeinited = true;
    }
    geocoder.geocode({
        'address': address
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            //initmap_search(results[0].geometry.location.lat(), results[0].geometry.location.lng());
            var latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
            map.set('center', latlng);
            map.set('zoom', 15);
            markerlatlng = results[0].geometry.location;
        } else
            alert("Please check your address details or contact an Elixir about the issue : " + status);
    });
}

function autocomplete() {
    var input = (document.getElementById('chkA'));
    // Autocomplete Bound To map
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        locate();
    });
}

function initialize() {
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });
    var mumbai = new google.maps.LatLng(19.03590687086149, 72.94649211215824);
    var mapOptions = {
        zoom: 12,
        center: mumbai,
        panControl: true,
        streetViewControl: false,
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL
        },
        styles: styles
    };
    map = new google.maps.Map(document.getElementById('map'), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    autocomplete();
    var trafficLayer = new google.maps.TrafficLayer();
    trafficLayer.setMap(map);
    var params = "all=1";
    var userRole = jQuery('#loginUserRole').val();
    jQuery.ajax({
        type: "POST",
        url: "route_ajax.php?all=1",
        cache: false,
        success: function(data) {
            var cdata1 = jQuery.parseJSON(data);
            var results = cdata1.result;
            //console.log(results);
            // Marker Clustering
            markers = [];
            var bounds = new google.maps.LatLngBounds();
            jQuery.each(results, function(i, device) {
                //console.log(device.cvehicleid);return false;
                var image = new google.maps.MarkerImage(device.image,
                    new google.maps.Size(48, 48),
                    new google.maps.Point(0, 0),
                    new google.maps.Point(8, 20));
                var latLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
                var marker = new MarkerWithLabel({
                    position: latLng,
                    map: map,
                    icon: image,
                    labelContent: device.cname,
                    labelAnchor: new google.maps.Point(9, 45),
                    labelClass: "mapslabels" // the CSS class for the label
                });
                bounds.extend(marker.position);
                var temperatureString = '';
                if (device.temp_sensors == 1) {
                    var temperature;
                    temperature = device.temp;
                    if (device.tempon == 1) {
                        temperature = temperature + " 0".sup() + "C";
                    }
                    temperatureString += '<br> ' + device.t1 + ' : ' + temperature + ' ' + device.t1Link;
                } else if (device.temp_sensors == 2) {
                    var temperature1, temperature2;
                    temperature1 = device.temp1;
                    temperature2 = device.temp2;
                    if (device.temp1on == 1) {
                        temperature1 = temperature1 + " 0".sup() + "C";
                    }
                    if (device.temp2on == 1) {
                        temperature2 = temperature2 + " 0".sup() + "C";
                    }
                    temperatureString += '<br> ' + device.t1 + ' : ' + temperature1 + ' ' + device.t1Link;
                    temperatureString += '<br> ' + device.t2 + ' : ' + temperature2 + ' ' + device.t2Link;
                } else if (device.temp_sensors == 3) {
                    var temperature1, temperature2, temperature3;
                    temperature1 = device.temp1;
                    temperature2 = device.temp2;
                    temperature3 = device.temp3;
                    if (device.temp1on == 1) {
                        temperature1 = temperature1 + " 0".sup() + "C";
                    }
                    if (device.temp2on == 1) {
                        temperature2 = temperature2 + " 0".sup() + "C";
                    }
                    if (device.temp3on == 1) {
                        temperature3 = temperature3 + " 0".sup() + "C";
                    }
                    temperatureString += '<br> ' + device.t1 + ' : ' + temperature1 + ' ' + device.t1Link;
                    temperatureString += '<br> ' + device.t2 + ' : ' + temperature2 + ' ' + device.t2Link;
                    temperatureString += '<br> ' + device.t3 + ' : ' + temperature3 + ' ' + device.t3Link;
                } else if (device.temp_sensors == 4) {
                    var temperature1, temperature2, temperature3, temperature4;
                    temperature1 = device.temp1;
                    temperature2 = device.temp2;
                    temperature3 = device.temp3;
                    temperature4 = device.temp4;
                    if (device.temp1on == 1) {
                        temperature1 = temperature1 + " 0".sup() + "C";
                    }
                    if (device.temp2on == 1) {
                        temperature2 = temperature2 + " 0".sup() + "C";
                    }
                    if (device.temp3on == 1) {
                        temperature3 = temperature3 + " 0".sup() + "C";
                    }
                    if (device.temp4on == 4) {
                        temperature4 = temperature4 + " 0".sup() + "C";
                    }
                    temperatureString += '<br> ' + device.t1 + ' : ' + temperature1 + ' ' + device.t1Link;
                    temperatureString += '<br> ' + device.t2 + ' : ' + temperature2 + ' ' + device.t2Link;
                    temperatureString += '<br> ' + device.t3 + ' : ' + temperature3 + ' ' + device.t3Link;
                    temperatureString += '<br> ' + device.t4 + ' : ' + temperature4 + ' ' + device.t4Link;
                }
                var humidityString = '';
                if (device.use_humidity == 1) {
                    var humidity;
                    humidity = device.humidity;
                    humidityString += '<br> Humidity : ' + humidity;
                }
                var deviceVehicleNo = 'Vehicle No : ' + device.cname + '<br>';
                var deviceDriver = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + '] <br>';
                var deviceLocation = 'Location : ' + device.clocation + ' <br>';
                var deviceSpeed = 'Current Speed : ' + device.cspeed + ' km/hr<br>';
                var deviceLastUpdated = 'Last Updated : ' + device.clastupdated + '';
                var deviceDistance = 'Distance : ' + device.totaldist + ' Km<br/>';
                if (device.portable == 1) {
                    deviceSpeed = '';
                    deviceDistance = '';
                }
                if (device.ckind == 'Warehouse') {
                    deviceDriver = '';
                    deviceSpeed = '';
                    deviceDistance = '';
                }
                var deviceDescription = '';
                if (device.description != "") {
                    //deviceDescription = 'Description : ' + device.description+'<br/>';
                }
                var deviceCheckpointList = '';
                if (device.checkpointlist != '') {
                    //deviceCheckpointList = 'Checkpointlist : ' + device.checkpointlist + '<br>';
                }
                var deviceTemperature = '';
                if (device.temp_sensors > 0) {
                    deviceTemperature = temperatureString + "<br/>";
                }
                /*
                 var contentString = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + '] <br>'
                 + 'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : '
                 + device.clastupdated + '<br>Distance : ' + device.totaldist + temperatureString;
                 */
                var contentString = deviceVehicleNo + deviceDriver + deviceLocation + deviceSpeed + deviceDistance + deviceLastUpdated + deviceDescription + deviceTemperature + humidityString + deviceCheckpointList;
                //infoboxjs
                var pop_data = "<a href='javascript:void(0);' onclick='map_interface(\"\", " + device.cgeolat + "," + device.cgeolong + "," + device.cvehicleid + ");' >Add as Checkpoint</a>";
                var boxText = document.createElement("div");
                boxText.style.cssText = "";
                boxText.innerHTML = "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> " + contentString + "<br/>" + pop_data + "</div></div></div>";
                boxText.className = "arrow_box";
                var myOptions = {
                    content: boxText,
                    disableAutoPan: false,
                    maxWidth: 0,
                    pixelOffset: new google.maps.Size(-110, -150),
                    zIndex: null,
                    boxStyle: {
                        opacity: 0.99,
                        width: "280px"
                    },
                    closeBoxMargin: "18px 13px 2px",
                    closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
                    infoBoxClearance: new google.maps.Size(1, 1),
                    isHidden: false,
                    pane: "floatPane",
                    enableEventPropagation: false
                };
                var ib = new InfoBox(myOptions);
                //ib.open(map, marker);
                google.maps.event.addListener(marker, "click", function(e) {
                    ib.open(map, this);
                });
                vehid = device.cvehicleid;
                marker.set("id", device.cvehicleid);
                vehiclesfordel[vehid] = marker;
                markers.push(marker);
            });
            map.fitBounds(bounds);
            markerCluster = new MarkerClusterer(map, markers);
            // Checkpoints
            jQuery.ajax({
                type: "POST",
                url: "../common/getcheckpoints.php?all=1",
                cache: false,
                success: function(data) {
                    var cdata1 = jQuery.parseJSON(data);
                    var results = cdata1.result;
                    jQuery.each(results, function(i, device) {
                        try {
                            var myLatLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
                            var marker = new MarkerWithLabel({
                                position: myLatLng,
                                map: map,
                                labelContent: device.cname,
                                labelAnchor: new google.maps.Point(9, 45),
                                labelClass: "mapslabels_chkp" // the CSS class for the label
                            });
                            id = device.checkpointid;
                            marker.set("id", device.checkpointid);
                            markersfordel[id] = marker;
                            var circle = new google.maps.Circle({
                                map: map,
                                radius: device.crad,
                                fillColor: '#000000',
                                strokeColor: '#000000',
                                strokeweight: 1
                            });
                            circle.bindTo('center', marker, 'position');
                            circle.set("id", device.checkpointid);
                            circlesfordel[id] = circle;
                            marker.setMap(null);
                            circle.setMap(null);
                        } catch (ex) {
                            alert(ex);
                        }
                    });
                }
            });
            // fences
            jQuery.ajax({
                type: "POST",
                url: "../common/getfences.php?all=1",
                cache: false,
                success: function(data) {
                    var cdata1 = jQuery.parseJSON(data);
                    var results = cdata1.result;
                    // setting fences
                    //alert(data);
                    for (var key in cdata1) {
                        var route = [];
                        var keyInData = jQuery.parseJSON(cdata1[key].fence_bound); //cdata1[key].fence_bound;
                        for (var keyin in keyInData) {
                            /* console.log("fence bounds lat: "+keyInData[keyin].cgeolat);
                             console.log("fence bounds long: "+keyInData[keyin].cgeolong); */
                            var fencelatlng = new google.maps.LatLng(keyInData[keyin].cgeolat, keyInData[keyin].cgeolong);
                            route.push(fencelatlng);
                        }
                        //console.log("fence bounds lat: "+cdata1[key].fence_bound.cgeolat);
                        //console.log("fence bounds long: "+cdata1[key].fence_bound.cgeolong);
                        // google map settings, initializing and assignment starts here
                        var boundss = new google.maps.LatLngBounds();
                        var f;
                        for (f = 0; f < route.length; f++) {
                            boundss.extend(route[f]);
                        }
                        // The Center of the Polygon
                        var markr = new MarkerWithLabel({
                            position: boundss.getCenter(),
                            map: map,
                            labelContent: cdata1[key].fencename,
                            labelAnchor: new google.maps.Point(9, 45),
                            labelClass: "mapslabels_fence" // the CSS class for the label
                        });
                        fid = cdata1[key].fenceid;
                        markr.set("id", cdata1[key].fenceid);
                        markrsfordel[fid] = markr;
                        markr.setMap(null);
                        var poly = new google.maps.Polygon({
                            path: route,
                            strokeWeight: 1,
                            fillColor: '#55FF55',
                            fillOpacity: 0.3,
                            //editable:true
                        });
                        poly.bindTo('center', markr, 'position');
                        fenid = cdata1[key].fenceid;
                        poly.set("id", cdata1[key].fenceid);
                        fencesfordel[fenid] = poly;
                        poly.setMap(null);
                        // Google map settings, initializing and assignment ends here
                    }
                    /* jQuery.each(results, function (i, fences) {
                     var route = [];
                     // setting bound
                     jQuery.each(fences.fence_bound, function (j, fencesobj) {
                     var fencelatlng = new google.maps.LatLng(fencesobj.geolat, fencesobj.geolong);
                     route.push(fencelatlng);
                     });
                     var boundss = new google.maps.LatLngBounds();
                     var f;
                     for (f = 0; f < route.length; f++) {
                     boundss.extend(route[f]);
                     }
                     // The Center of the Polygon
                     //console.log(boundss.getCenter());
                     var markr = new MarkerWithLabel({
                     position: boundss.getCenter(),
                     map: map,
                     labelContent: fences.fencename,
                     labelAnchor: new google.maps.Point(9, 45),
                     labelClass: "mapslabels_fence" // the CSS class for the label
                     });
                     fid = fences.fencid;
                     markr.set("id", fences.fenceid);
                     markrsfordel[fid] = markr;
                     markr.setMap(null);
                     var poly = new google.maps.Polygon({
                     path: route,
                     strokeWeight: 1,
                     fillColor: '#55FF55',
                     fillOpacity: 0.3
                     //editable:true
                     });
                     poly.bindTo('center', markr, 'position');
                     fenid = fences.fencid;
                     //alert(fenid);
                     poly.set("id", fences.fencid);
                     fencesfordel[fenid] = poly;
                     poly.setMap(null);
                     }); */
                }
            });
            /* share map to 3rd party with userkey */
            var userkey = jQuery("#userkey").val();
            if (userkey != '' && typeof userkey !== 'undefined') {
                setVehOnMapWithUserkey();
            }
            jQuery('.veh_all').attr('disabled', false);
            jQuery('.chk_all').attr('disabled', false);
        }
    });
    if (userRole != 'elixir') {
        periodicupdate();
    }

}

function chkplot(chkid) {
    //console.log("chkid in chkplot function is: "+chkid);
    /*  map.set('center', markersfordel[chkid].position);
     map.set('zoom', 15);
     marker = markersfordel[chkid];
     marker.setMap(map);
     circle = circlesfordel[chkid];
     circle.setMap(map); */
    if ((jQuery("#chk_" + chkid).length != 0) && jQuery("#chk_" + chkid).is(':checked') == true) {
        map.set('center', markersfordel[chkid].position);
        map.set('zoom', 15);
        marker = markersfordel[chkid];
        marker.setMap(map);
        circle = circlesfordel[chkid];
        circle.setMap(map);
    } else if ((jQuery("#chk_" + chkid).length != 0) && jQuery("#chk_" + chkid).is(':checked') == false) {
        marker = markersfordel[chkid];
        marker.setMap(null);
        circle = circlesfordel[chkid];
        circle.setMap(null);
    }
}

function vehplot(vehid) {
    marker = vehiclesfordel[vehid];
    if ($('input.veh_all:checked').length == 1 && customerrefreshfrqmap == 2) {
        initialize1();
        // empty out the error msg
        //        toggleError("");
        // set the values and check if any is empty, and if yes, show error and return
        var startVal = 'malad'
        var endVal = 'kharghar'
        //        if (!startVal || !endVal) {
        //            toggleError("Please enter both start and end locations.");
        //            return;
        //        }
        //        // just to avoid weird case of same start and end location
        //        if (startVal === endVal) {
        //            toggleError("Please enter different locations in both inputs");
        //            return;
        //        }
        startLoc[0] = startVal;
        endLoc[0] = endVal;
        // empty out previous values
        startLocation = [];
        endLocation = [];
        polyLine = [];
        poly2 = [];
        timerHandle = [];
        var directionsDisplay = new Array();
        for (var i = 0; i < startLoc.length; i++) {
            var rendererOptions = {
                map: map1,
                suppressMarkers: true,
                suppressPolylines: true,
                preserveViewport: true
            };
            directionsService = new google.maps.DirectionsService();
            var travelMode = google.maps.DirectionsTravelMode.DRIVING;
            var request = {
                origin: startLoc[i],
                destination: endLoc[i],
                travelMode: travelMode,
                optimizeWaypoints: true
            };
            directionsService.route(request, makeRouteCallback(i, directionsDisplay[i]), rendererOptions);
        }
        return false;
    }
    if ((jQuery("#veh_" + vehid).length != 0) && jQuery("#veh_" + vehid).is(':checked') == true) {
        if (marker) {
            map.set('center', vehiclesfordel[vehid].position);
            map.set('zoom', 15);
            marker.setMap(map);
        }
        markers.push(marker);
    } else if ((jQuery("#veh_" + vehid).length != 0) && jQuery("#veh_" + vehid).is(':checked') == false) {
        if (marker) {
            marker.setMap(null);
        }
        remove(markers, marker);
    }
    // Add in Cluster
    markerCluster.clearMarkers();
    if (markers.length != 0) {
        markerCluster = new MarkerClusterer(map, markers);
    }
}

function whplot(vehid) {
    if ((jQuery("#wh_" + vehid).length != 0) && jQuery("#wh_" + vehid).is(':checked') === true) {
        marker = vehiclesfordel[vehid];
        if (marker) {
            marker.setMap(map);
        }
        // Add in Cluster
        markerCluster.clearMarkers();
        markers.push(marker);
        markerCluster = new MarkerClusterer(map, markers);
    } else if ((jQuery("#wh_" + vehid).length != 0) && jQuery("#wh_" + vehid).is(':checked') === false) {
        marker = vehiclesfordel[vehid];
        if (marker) {
            marker.setMap(null);
        }
        // Remove from Cluster
        markerCluster.clearMarkers();
        remove(markers, marker);
        if (markers.length != 0) {
            markerCluster = new MarkerClusterer(map, markers);
        }
    }
}

function fenceplot(fenceid) {
    if ((jQuery("#fence_" + fenceid).length != 0) && jQuery("#fence_" + fenceid).is(':checked') == true) {
        marker = markrsfordel[fenceid];
        marker.setMap(map);
        poly = fencesfordel[fenceid];
        poly.setMap(map);
    } else if ((jQuery("#fence_" + fenceid).length != 0) && jQuery("#fence_" + fenceid).is(':checked') == false) {
        marker = markrsfordel[fenceid];
        marker.setMap(null);
        poly = fencesfordel[fenceid];
        poly.setMap(null);
    }
}

function remove(arr, item) {
    for (var i = arr.length; i >= 0; i--) {
        if (arr[i] === item) {
            arr.splice(i, 1);
        }
    }
}

function onclicktog() {
    jQuery('#sidebar').toggle('fast');
    if (counter % 2 === 0) {
        jQuery('#next').css("display", "block");
        jQuery('#pre').css("display", "none");
        jQuery('#maptoggler').css("left", "0px");
        counter++;
    } else {
        jQuery('#pre').css("display", "block");
        jQuery('#next').css("display", "none");
        jQuery('#maptoggler').css("left", "300px");
        counter++;
    }
}

function initmap(lat, lng) {
    if (gmapsinited)
        return;
    var latlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
        zoom: 11,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map"), myOptions);
    gmapsinited = true;
}

function mapvehicles() {
    addAllVehicles();
}

function periodicupdate() {
    setTimeout(function() {
        refreshdata();
    }, periodictimemap);
}

function refreshmap() {
    var vehicleid = "";
    vehicleid = jQuery("#vehicleid_given").val();
    jQuery.each(vehiclesfordel, function(index, value) {
        marker = vehiclesfordel[index];
        if (marker) {
            marker.setMap(null);
        }
        // Remove from Cluster
        markerCluster.clearMarkers();
        markers = [];
    });
    //console.log(vehicleid);
    if (vehicleid != "") {
        var urldata = "route_ajax.php?all=1&getvehicleid=" + vehicleid;
    } else {
        var urldata = "route_ajax.php?all=1";
    }
    jQuery.ajax({
        type: "POST",
        url: urldata,
        async: true,
        cache: false,
        success: function(data) {
            var cdata1 = jQuery.parseJSON(data);
            var results = cdata1.result;
            vehiclesfordel = {};
            markers = [];
            // Marker Clustering
            jQuery.each(results, function(i, device) {
                var image = new google.maps.MarkerImage(device.image,
                    new google.maps.Size(48, 48),
                    new google.maps.Point(0, 0),
                    new google.maps.Point(8, 20));
                var latLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
                var marker = new MarkerWithLabel({
                    'position': latLng,
                    map: map,
                    icon: image,
                    labelContent: device.cname,
                    labelAnchor: new google.maps.Point(9, 45),
                    labelClass: "mapslabels" // the CSS class for the label
                });
                var temperatureString = '';
                if (device.temp_sensors == 1) {
                    var temperature;
                    temperature = device.temp;
                    if (device.tempon == 1) {
                        temperature = temperature + " 0".sup() + "C";
                    }
                    temperatureString += '<br> ' + device.t1 + ' : ' + temperature + ' ' + device.t1Link;
                } else if (device.temp_sensors == 2) {
                    var temperature1, temperature2;
                    temperature1 = device.temp1;
                    temperature2 = device.temp2;
                    if (device.temp1on == 1) {
                        temperature1 = temperature1 + " 0".sup() + "C";
                    }
                    if (device.temp2on == 1) {
                        temperature2 = temperature2 + " 0".sup() + "C";
                    }
                    temperatureString += '<br> ' + device.t1 + ' : ' + temperature1 + ' ' + device.t1Link;;
                    temperatureString += '<br> ' + device.t2 + ' : ' + temperature2 + ' ' + device.t2Link;
                } else if (device.temp_sensors == 3) {
                    var temperature1, temperature2, temperature3;
                    temperature1 = device.temp1;
                    temperature2 = device.temp2;
                    temperature3 = device.temp3;
                    if (device.temp1on == 1) {
                        temperature1 = temperature1 + " 0".sup() + "C";
                    }
                    if (device.temp2on == 1) {
                        temperature2 = temperature2 + " 0".sup() + "C";
                    }
                    if (device.temp3on == 1) {
                        temperature3 = temperature3 + " 0".sup() + "C";
                    }
                    temperatureString += '<br> ' + device.t1 + ' : ' + temperature1 + ' ' + device.t1Link;
                    temperatureString += '<br> ' + device.t2 + ' : ' + temperature2 + ' ' + device.t2Link;
                    temperatureString += '<br> ' + device.t3 + ' : ' + temperature3 + ' ' + device.t3Link;
                } else if (device.temp_sensors == 4) {
                    var temperature1, temperature2, temperature3, temperature4;
                    temperature1 = device.temp1;
                    temperature2 = device.temp2;
                    temperature3 = device.temp3;
                    temperature4 = device.temp4;
                    if (device.temp1on == 1) {
                        temperature1 = temperature1 + " 0".sup() + "C";
                    }
                    if (device.temp2on == 1) {
                        temperature2 = temperature2 + " 0".sup() + "C";
                    }
                    if (device.temp3on == 1) {
                        temperature3 = temperature3 + " 0".sup() + "C";
                    }
                    if (device.temp4on == 4) {
                        temperature4 = temperature4 + " 0".sup() + "C";
                    }
                    temperatureString += '<br> ' + device.t1 + ' : ' + temperature1 + ' ' + device.t1Link;
                    temperatureString += '<br> ' + device.t2 + ' : ' + temperature2 + ' ' + device.t2Link;
                    temperatureString += '<br> ' + device.t3 + ' : ' + temperature3 + ' ' + device.t3Link;
                    temperatureString += '<br> ' + device.t4 + ' : ' + temperature4 + ' ' + device.t4Link;
                }
                var humidityString = '';
                if (device.use_humidity == 1) {
                    var humidity;
                    humidity = device.humidity;
                    humidityString += '<br> Humidity : ' + humidity;
                }
                var deviceVehicleNo = 'Vehicle No : ' + device.cname + '<br>';
                var deviceDriver = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + '] <br>';
                var deviceLocation = 'Location : ' + device.clocation + ' <br>';
                var deviceSpeed = 'Current Speed : ' + device.cspeed + ' km/hr<br>';
                var deviceLastUpdated = 'Last Updated : ' + device.clastupdated + '';
                var deviceDistance = 'Distance : ' + device.totaldist + ' Km<br/>';
                if (device.portable == 1) {
                    deviceSpeed = '';
                    deviceDistance = '';
                }
                if (device.ckind == 'Warehouse') {
                    deviceDriver = '';
                    deviceSpeed = '';
                    deviceDistance = '';
                }
                var deviceDescription = '';
                if (device.description != "") {
                    //deviceDescription = 'Description : ' + device.description+'<br/>';
                }
                var deviceCheckpointList = '';
                if (device.checkpointlist != '') {
                    //deviceCheckpointList = 'Checkpointlist : ' + device.checkpointlist + '<br>';
                }
                var deviceTemperature = '';
                if (device.temp_sensors > 0) {
                    deviceTemperature = temperatureString + "<br/>";
                }
                /*
                 var contentString = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + '] <br>'
                 + 'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : '
                 + device.clastupdated + '<br>Distance : ' + device.totaldist + temperatureString;
                 */
                var contentString = deviceVehicleNo + deviceDriver + deviceLocation + deviceSpeed + deviceDistance + deviceLastUpdated + deviceDescription + deviceTemperature + humidityString + deviceCheckpointList;
                var pop_data = "<a href='javascript:void(0);' onclick='map_interface(\"hiii\", " + device.cgeolat + "," + device.cgeolong + "," + device.cvehicleid + ");' >Add as Checkpoint</a>";
                var boxText = document.createElement("div");
                boxText.style.cssText = "";
                boxText.innerHTML = "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> " + contentString + "<br/>" + pop_data + "</div></div></div>";
                boxText.className = "arrow_box";
                var myOptions = {
                    content: boxText,
                    disableAutoPan: false,
                    maxWidth: 0,
                    pixelOffset: new google.maps.Size(-110, -130),
                    zIndex: null,
                    boxStyle: {
                        opacity: 0.99,
                        width: "280px"
                    },
                    closeBoxMargin: "18px 13px 2px",
                    closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
                    infoBoxClearance: new google.maps.Size(1, 1),
                    isHidden: false,
                    pane: "floatPane",
                    enableEventPropagation: false
                };
                var ib = new InfoBox(myOptions);
                //ib.open(map, marker);
                google.maps.event.addListener(marker, "click", function(e) {
                    ib.open(map, this);
                });
                vehid = device.cvehicleid;
                marker.set("id", device.cvehicleid);
                vehiclesfordel[vehid] = marker;
                markers.push(marker);
            });
            jQuery.each(vehiclesfordel, function(index, value) {
                if ((jQuery("#veh_" + index).length != 0) && jQuery("#veh_" + index).is(':checked') == false) {
                    marker = vehiclesfordel[index];
                    if (marker) {
                        marker.setMap(null);
                    }
                    remove(markers, marker);
                }
            });
            jQuery.each(vehiclesfordel, function(index, value) {
                if ((jQuery("#wh_" + index).length != 0) && jQuery("#wh_" + index).is(':checked') == false) {
                    marker = vehiclesfordel[index];
                    if (marker) {
                        marker.setMap(null);
                    }
                    remove(markers, marker);
                }
            });
            if (markers.length != 0) {
                markerCluster.clearMarkers();
                markerCluster = new MarkerClusterer(map, markers);
            }
        }
    });
}

function tempreport(vehicleid, sensor, deviceid) {
    window.open("../reports/reports.php?id=13&vehicleid=" + vehicleid + "&tempsen=" + sensor + "&devid=" + deviceid, "_blank");
}

function refreshdata() {
    var vehicleid = "";
    vehicleid = jQuery("#vehicleid_given").val();
    if (vehicleid != "") {
        var urldata = "route_ajax.php?all=1&getvehicleid=" + vehicleid;
    } else {
        var urldata = "route_ajax.php?all=1";
    }
    jQuery.ajax({
        type: "POST",
        url: urldata,
        async: true,
        cache: false,
        success: function(data) {
            jQuery.each(vehiclesfordel, function(index, value) {
                marker = vehiclesfordel[index];
                if (marker) {
                    marker.setMap(null);
                }
                // Remove from Cluster
                markerCluster.clearMarkers();
                markers = [];
            });
            var cdata1 = jQuery.parseJSON(data);
            var results = cdata1.result;
            vehiclesfordel = {};
            markers = [];
            // Marker Clustering
            jQuery.each(results, function(i, device) {
                var image = new google.maps.MarkerImage(device.image,
                    new google.maps.Size(48, 48),
                    new google.maps.Point(0, 0),
                    new google.maps.Point(8, 20));
                var latLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
                var marker = new MarkerWithLabel({
                    'position': latLng,
                    map: map,
                    icon: image,
                    labelContent: device.cname,
                    labelAnchor: new google.maps.Point(9, 45),
                    labelClass: "mapslabels" // the CSS class for the label
                });
                var temperatureString = '';
                if (device.temp_sensors == 1) {
                    var temperature;
                    temperature = device.temp;
                    if (device.tempon == 1) {
                        temperature = temperature + " 0".sup() + "C";
                    }
                    temperatureString += '<br> ' + device.t1 + ' : ' + temperature + ' ' + device.t1Link;
                } else if (device.temp_sensors == 2) {
                    var temperature1, temperature2;
                    temperature1 = device.temp1;
                    temperature2 = device.temp2;
                    if (device.temp1on == 1) {
                        temperature1 = temperature1 + " 0".sup() + "C";
                    }
                    if (device.temp2on == 1) {
                        temperature2 = temperature2 + " 0".sup() + "C";
                    }
                    temperatureString += '<br> ' + device.t1 + ' : ' + temperature1 + ' ' + device.t1Link;
                    temperatureString += '<br> ' + device.t2 + ' : ' + temperature2 + ' ' + device.t2Link;
                } else if (device.temp_sensors == 3) {
                    var temperature1, temperature2, temperature3;
                    temperature1 = device.temp1;
                    temperature2 = device.temp2;
                    temperature3 = device.temp3;
                    if (device.temp1on == 1) {
                        temperature1 = temperature1 + " 0".sup() + "C";
                    }
                    if (device.temp2on == 1) {
                        temperature2 = temperature2 + " 0".sup() + "C";
                    }
                    if (device.temp3on == 1) {
                        temperature3 = temperature3 + " 0".sup() + "C";
                    }
                    temperatureString += '<br> ' + device.t1 + ' : ' + temperature1 + ' ' + device.t1Link;
                    temperatureString += '<br> ' + device.t2 + ' : ' + temperature2 + ' ' + device.t2Link;
                    temperatureString += '<br> ' + device.t3 + ' : ' + temperature3 + ' ' + device.t3Link;
                } else if (device.temp_sensors == 4) {
                    var temperature1, temperature2, temperature3, temperature4;
                    temperature1 = device.temp1;
                    temperature2 = device.temp2;
                    temperature3 = device.temp3;
                    temperature4 = device.temp4;
                    if (device.temp1on == 1) {
                        temperature1 = temperature1 + " 0".sup() + "C";
                    }
                    if (device.temp2on == 1) {
                        temperature2 = temperature2 + " 0".sup() + "C";
                    }
                    if (device.temp3on == 1) {
                        temperature3 = temperature3 + " 0".sup() + "C";
                    }
                    if (device.temp4on == 4) {
                        temperature4 = temperature4 + " 0".sup() + "C";
                    }
                    temperatureString += '<br> ' + device.t1 + ' : ' + temperature1 + ' ' + device.t1Link;
                    temperatureString += '<br> ' + device.t2 + ' : ' + temperature2 + ' ' + device.t2Link;
                    temperatureString += '<br> ' + device.t3 + ' : ' + temperature3 + ' ' + device.t3Link;
                    temperatureString += '<br> ' + device.t4 + ' : ' + temperature4 + ' ' + device.t4Link;
                }
                var humidityString = '';
                if (device.use_humidity == 1) {
                    var humidity;
                    humidity = device.humidity;
                    humidityString += '<br> Humidity : ' + humidity;
                }
                var deviceVehicleNo = 'Vehicle No : ' + device.cname + '<br>';
                var deviceDriver = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + '] <br>';
                var deviceLocation = 'Location : ' + device.clocation + ' <br>';
                var deviceSpeed = 'Current Speed : ' + device.cspeed + ' km/hr<br>';
                var deviceLastUpdated = 'Last Updated : ' + device.clastupdated + '';
                var deviceDistance = 'Distance : ' + device.totaldist + ' Km<br/>';
                if (device.portable == 1) {
                    deviceSpeed = '';
                    deviceDistance = '';
                }
                if (device.ckind == 'Warehouse') {
                    deviceDriver = '';
                    deviceSpeed = '';
                    deviceDistance = '';
                }
                var deviceDescription = '';
                if (device.description != "") {
                    //deviceDescription = 'Description : ' + device.description+'<br/>';
                }
                var deviceCheckpointList = '';
                if (device.checkpointlist != '') {
                    //deviceCheckpointList = 'Checkpointlist : ' + device.checkpointlist + '<br>';
                }
                var deviceTemperature = '';
                if (device.temp_sensors > 0) {
                    deviceTemperature = temperatureString + "<br/>";
                }
                /*
                 var contentString = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + '] <br>'
                 + 'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : '
                 + device.clastupdated + '<br>Distance : ' + device.totaldist + temperatureString;
                 */
                var contentString = deviceVehicleNo + deviceDriver + deviceLocation + deviceSpeed + deviceDistance + deviceLastUpdated + deviceDescription + deviceTemperature + humidityString + deviceCheckpointList;
                var pop_data = "<a href='javascript:void(0);' onclick='map_interface(\"hiii\", " + device.cgeolat + "," + device.cgeolong + "," + device.cvehicleid + ");' >Add as Checkpoint</a>";
                var boxText = document.createElement("div");
                boxText.style.cssText = "";
                boxText.innerHTML = "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> " + contentString + "<br/>" + pop_data + "</div></div></div>";
                boxText.className = "arrow_box";
                var myOptions = {
                    content: boxText,
                    disableAutoPan: false,
                    maxWidth: 0,
                    pixelOffset: new google.maps.Size(-110, -130),
                    zIndex: null,
                    boxStyle: {
                        opacity: 0.99,
                        width: "280px"
                    },
                    closeBoxMargin: "18px 13px 2px",
                    closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
                    infoBoxClearance: new google.maps.Size(1, 1),
                    isHidden: false,
                    pane: "floatPane",
                    enableEventPropagation: false
                };
                var ib = new InfoBox(myOptions);
                //ib.open(map, marker);
                google.maps.event.addListener(marker, "click", function(e) {
                    ib.open(map, this);
                });
                var vehid = device.cvehicleid;
                marker.set("id", device.cvehicleid);
                vehiclesfordel[vehid] = marker;
                markers.push(marker);
            });
            jQuery.each(vehiclesfordel, function(index, value) {
                if ((jQuery("#veh_" + index).length != 0) && jQuery("#veh_" + index).is(':checked') == false) {
                    marker = vehiclesfordel[index];
                    if (marker) {
                        marker.setMap(null);
                    }
                    remove(markers, marker);
                }
            });
            jQuery.each(vehiclesfordel, function(index, value) {
                if ((jQuery("#wh_" + index).length != 0) && jQuery("#wh_" + index).is(':checked') == false) {
                    marker = vehiclesfordel[index];
                    if (marker) {
                        marker.setMap(null);
                    }
                    remove(markers, marker);
                }
            });
            if (markers.length != 0) {
                markerCluster.clearMarkers();
                markerCluster = new MarkerClusterer(map, markers);
            }
            // Periodic Update
            periodicupdate();
        }
    });
}

function plotvehicles(cdata) {
    evictMarkers();
    var results = cdata.result;
    jQuery.each(results, function(i, device) {
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
            var marker = new MarkerWithLabel({
                position: myLatLng,
                icon: image,
                map: map,
                labelContent: device.cname,
                labelAnchor: new google.maps.Point(9, 45),
                labelClass: "mapslabels" // the CSS class for the label
            });
            map.panTo(marker.getPosition());
            eviction_list.push(marker);
            var contentString = '<h3>' + device.cname + '</h3><hr><p>' +
                'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + ']<br>' +
                'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : ' + device.clastupdated +
                '<hr><a href=../history/history.php?id=5&vid=' + device.cvehicleid + '><u>Vehicle History</u> </a>';
            var pop_data = "<a href='javascript:void(0);' onclick='map_interface(\"hiii\", " + device.cgeolat + "," + device.cgeolong + "," + device.cvehicleid + ");' >Add as Checkpoint</a>";
            var boxText = document.createElement("div");
            boxText.style.cssText = "";
            boxText.innerHTML = "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> " + contentString + "<br/>" + pop_data + "</div></div></div>";
            boxText.className = "arrow_box";
            var myOptions = {
                content: boxText,
                disableAutoPan: false,
                maxWidth: 0,
                pixelOffset: new google.maps.Size(-110, -130),
                zIndex: null,
                boxStyle: {
                    opacity: 0.99,
                    width: "280px"
                },
                closeBoxMargin: "18px 13px 2px",
                closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
                infoBoxClearance: new google.maps.Size(1, 1),
                isHidden: false,
                pane: "floatPane",
                enableEventPropagation: false
            };
            var ib = new InfoBox(myOptions);
            //ib.open(map, marker);
            google.maps.event.addListener(marker, "click", function(e) {
                ib.open(map, this);
            });
        } catch (ex) {
            alert(ex);
        }
    });
}

function mapcheckpoints() {
    jQuery.ajax({
        type: "POST",
        url: "../../modules/checkpoint/route_ajax.php?chk=all",
        cache: false,
        success: function(data) {
            var cdata = jQuery.parseJSON(data);
            if (cdata.result.length !== 0) {
                plotCheckpoints(cdata);
            }
        }
    });
}

function plotCheckpoints(cdata) {
    var results = cdata.result;
    jQuery.each(results, function(i, device) {
        try {
            var myLatLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
            var marker = new MarkerWithLabel({
                position: myLatLng,
                map: map,
                labelContent: device.cname,
                labelAnchor: new google.maps.Point(9, 45),
                labelClass: "mapslabels_chkp" // the CSS class for the label
            });
            id = device.checkpointid;
            marker.set("id", device.checkpointid);
            markersfordel[id] = marker;
            var circle = new google.maps.Circle({
                map: map,
                radius: device.crad,
                fillColor: '#AA0000',
                strokeColor: '#AA0000',
                strokeweight: 1
            });
            circle.set("id", device.checkpointid);
            circlesfordel[id] = circle;
            circle.bindTo('center', marker, 'position');
            var contentString = '<h3>' + device.cname + '</h3><br>Checkpoint Radius [Meters] = ' + device.crad;
            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });
            google.maps.event.addListener(marker, 'mouseover', function() {
                infowindow.open(map, marker);
            });
            google.maps.event.addListener(marker, 'mouseout', function() {
                infowindow.close();
            });
            eviction_list.push(marker);
            eviction_list.push(circle);
        } catch (ex) {
            alert(ex);
        }
    });
}
// need testting not confirmed
function evictMarkers() {
    // clear all markers
    jQuery.each(eviction_list, function(i, item) {
        item.setMap(null);
    });
    // reset the eviction array
    eviction_list = [];
}
//
function delete_all() {
    jQuery.each(eviction_list, function(i, item) {
        item.setMap(null);
    });
    // reset the eviction array
    eviction_list = [];
    jQuery("#vehicle_list").html("");
}
// ok
function getchk() {
    var devices = "";
    vehicle_list.forEach(function(item) {
        if (item !== undefined) {
            devices = devices + item + ",";
        }
    });
    jQuery.ajax({
        type: "POST",
        url: "../common/getchkforvehicles.php",
        cache: false,
        data: {
            vehicleids: devices
        },
        success: function(data) {
            var cdata1 = jQuery.parseJSON(data);
            plotCheckpoints(cdata1);
        }
    });
}
// jquery ok
function addVehicle() {
    var vehicle_id = jQuery('#to').val();
    if (vehicle_id > -1 && jQuery('#to_vehicle_div_' + vehicle_id).val() === null) {
        var selected_name = jQuery('#to option:selected').text();
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function() {
            removevehicle(vehicle_id);
        };
        div.className = 'recipientbox';
        div.id = 'to_vehicle_div_' + vehicle_id;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_vehicle_' + vehicle_id + '" value="' + vehicle_id + '"/>';
        jQuery('vehicle_list').append(div);
        jQuery(div).append(remove_image);
        vehicle_list.push(vehicle_id);
    }
    jQuery('#to').val(0);
}
// unidentified
function getselvehicles() {
    var params = "all=1";
    new Ajax.Request('route_ajax.php', {
        parameters: params,
        onSuccess: function(transport) {
            var cdata1 = transport.responseText.evalJSON();
            results = cdata1.result;
            //            plotvehicles(cdata);
            //            getchk();
            //            periodicupdate();
        },
        onComplete: function() {}
    });
}
// jQuery ok
function removevehicle(id) {
    jQuery('#to_vehicle_div_' + id).remove();
    delete vehicle_list[vehicle_list.indexOf(id)];
    setTimeout(function() {
        getselvehicles();
    }, 10000);
}
// jquery ok
function addAllVehicles() {
    jQuery("#to option").each(function(index, element) {
        jQuery("#to").val(jQuery(element).val());
        addVehicle();
    });
    getselvehicles();
}

function setVehOnMapWithUserkey() {
    var vehicleid = jQuery("#vehicleid_given").val();
    jQuery('#divheader').hide();
    jQuery("#footer").hide();
    jQuery("#maptoggler").hide();
    jQuery("#sidebar").hide();
    if (vehicleid != "") {
        clearall('vehicles');
    }
    jQuery("#veh_" + vehicleid).attr("checked", true);
    vehplot(vehicleid);
}

function routehistopen(vehicleid) {
    window.open("../reports/reports.php?vehicleid=" + vehicleid, "_blank");
}

function BindVehicleSearch() {
    jQuery("#txtVehicleNo").on("keyup", function() {
        if (this.value.length > 0) {
            var query = this.value.toLowerCase();
            jQuery('.searchVehicles [id^="veh_"]').each(function(i, elem) {
                if (elem.value.toLowerCase().indexOf(query) !== -1) {
                    jQuery(elem).parents(".searchVehicles").show();
                } else {
                    jQuery(elem).parents(".searchVehicles").hide();
                }
            });
        } else {
            jQuery(".searchVehicles").show();
        }
    });
}

function BindChkPtSearch() {
    jQuery("#txtCheckpoint").on("keyup", function() {
        if (this.value.length > 0) {
            var query = this.value.toLowerCase();
            jQuery('.searchChkpts [id^="chk_"]').each(function(i, elem) {
                if (elem.value.toLowerCase().indexOf(query) !== -1) {
                    jQuery(elem).parents(".searchChkpts").show();
                } else {
                    jQuery(elem).parents(".searchChkpts").hide();
                }
            });
        } else {
            jQuery(".searchChkpts").show();
        }
    });
}

function BindFenceSearch() {
    jQuery("#txtFence").on("keyup", function() {
        if (this.value.length > 0) {
            var query = this.value.toLowerCase();
            jQuery('.searchFences [id^="fence_"]').each(function(i, elem) {
                if (elem.value.toLowerCase().indexOf(query) !== -1) {
                    jQuery(elem).parents(".searchFences").show();
                } else {
                    jQuery(elem).parents(".searchFences").hide();
                }
            });
        } else {
            jQuery(".searchFences").show();
        }
    });
}
//initializing select2 for check points
function initializeSelect2ForCheckPoints() {
    $("#checkPointsSelect2").select2({
        width: '90%',
        placeholder: 'Select checkpoints'
    });
}
//initializing select2 for check points types
function initializeSelect2ForCheckPointTypes() {
    $("#checkPointTypeSelect2").select2({
        width: '90%',
        placeholder: 'Select checkpoints'
    });
}
/* Select2 events starts here */
$('#checkPointTypeSelect2').on('select2:select', function(e) {
    var data = e.params.data;
    // console.log($('#checkPointTypeSelect2').val());
    /* Invoking http call to get checkpoints against check point types */
    var checkPointTypeId = $('#checkPointTypeSelect2').val();
    UrlSpc = "map_functions.php";
    if (checkPointTypeId != -1) {
        jQuery.ajax({
            type: "POST",
            url: UrlSpc,
            cache: false,
            data: { action: 'fetchCheckPoints', checkPointTypeId: checkPointTypeId },
            success: function(data) {
                //console.log("Data is: "+data);
                initializeSelect2ForCheckPoints();
                var obj = jQuery.parseJSON(data);
                var selectOptionData;
                $.each(obj, function(index, element) {
                    /* alert(element.timeStamp); */
                    // console.log("checkpoint name: "+element.cname);
                    selectOptionData += '<option id="' + element.checkpointid + '" value="' + element.checkpointid + '">' + element.cname + '</option>';
                });
                $('#checkPointsSelect2').html(selectOptionData);
            }
        });
    } else {
        initializeSelect2ForCheckPoints();
        $('#checkPointsSelect2').html('');
    }
});
$('#checkPointsSelect2').on('select2:select', function(e) {
    var data = e.params.data;
    var selectedValues = $('#checkPointsSelect2').val();
    var onlySelectedValue = selectedValues[Object.keys(selectedValues)[Object.keys(selectedValues).length - 1]];
    drawCircleOnMap(onlySelectedValue, 'plot');
});
var onlyUnSelectedValue;
$('#checkPointsSelect2').on("select2:unselecting", function(e) {
    var unselected_value = $('#checkPointsSelect2').val();
    var onlyUnSelectedValue = unselected_value[Object.keys(unselected_value)[Object.keys(unselected_value).length - 1]];
    console.log('only unselected value from data array: ' + onlyUnSelectedValue);
    $("#checkPointsSelect2").trigger("change");
    drawCircleOnMap(onlyUnSelectedValue, 'unplot');
}) /* .trigger('change').drawCircleOnMap(onlyUnSelectedValue,'unplot') */ ;
$("#checkboxForSelectAllCheckPoints").click(function() {
    if ($("#checkboxForSelectAllCheckPoints").is(':checked')) {
        $("#checkPointsSelect2 > option").prop("selected", "selected");
        $("#checkPointsSelect2").trigger("change");
        console.log($('#checkPointsSelect2').val());
        Object.entries($('#checkPointsSelect2').val()).forEach(([key, value]) => {
            drawCircleOnMap(value, 'plot');
        });
    } else {
        $("#checkPointsSelect2 > option").removeAttr("selected");
        $("#checkPointsSelect2").trigger("change");
        drawBlankMap();
    }
});

function drawCircleOnMap(chkid, status) {
    if (status == 'plot') {
        map.set('center', markersfordel[chkid].position);
        map.set('zoom', 15);
        marker = markersfordel[chkid];
        marker.setMap(map);
        circle = circlesfordel[chkid];
        circle.setMap(map);
        return true;
    } else if (status == 'unplot') {
        marker = markersfordel[chkid];
        marker.setMap(null);
        circle = circlesfordel[chkid];
        circle.setMap(null);
        return true;
    } else if (status == 'unplotall') {
        marker = markersfordel[chkid];
        marker.setMap(null);
        circle = circlesfordel[chkid];
        circle.setMap(null);
        return true;
    }
}

function drawBlankMap() {
    var latlng = new google.maps.LatLng(22.7536789, 73.2873131);
    var myOptions = {
        zoom: 5,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map"), myOptions);
}
/* select2 events ends here */
function setVehicleFilter(status) {
    var type = (jQuery('.all_select').data('type'));
    //clearing other divs
    clearall(type);
    var outerDiv = jQuery('.' + status + '-box');
    var otherBoxes = outerDiv.parent().children(".outer-box");
    jQuery.each(otherBoxes, function(k, v) {
        v.style.backgroundColor = "";
    });
    if (status == null) {
        outerDiv.css("background-color", "");
        selectall(type);
        return;
    }
    if (currentType == status) {
        outerDiv.css("background-color", "");
        setVehicleFilter(null);
        currentType = null;
        return;
    }
    currentType = status;
    outerDiv.css("background-color", "LightGrey");
    var outerDiv = jQuery('.' + status + '-box');
    var vehiclesForThisStatus = jQuery('[data-status=' + status + ']');
    if (vehiclesForThisStatus.length == 0) {
        clearall(type);
    }
    jQuery.each(vehiclesForThisStatus, function(k, v) {
        jQuery(v).prop('checked', true);
        if (type == 'warehouse') {
            var vehId = v.id.replace("wh_", '');
            whplot(vehId);
        } else {
            var vehId = v.id.replace("veh_", '');
            vehplot(vehId);
        }
    });
}
// INOX DEMO PURPOSE CODE
//window.initialize = initialize;
//window.setRoutes = setRoutes;
///
function initialize1() {
    // initialize infoWindow
    infoWindow = new google.maps.InfoWindow({
        size: new google.maps.Size(150, 50)
    });
    var options = {
        // max zoom
        zoom: 16
    };
    map1 = new google.maps.Map(document.getElementById("map"), options);
    //
    // initial location which loads up on map1
    address = 'mumbai'
    // Geocoder is used to encode or actually geocode textual addresses to lat long values
    geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'address': address }, function(results, status) {
        if (results.length)
            map1.fitBounds(results[0].geometry.viewport);
    });
}

function makeRouteCallback(routeNum, disp, rendererOptions) {
    // check if polyline and map exists, if yes, no need to do anything else, just start the animation
    if (polyLine[routeNum] && (polyLine[routeNum].getMap() != null)) {
        startAnimation(routeNum);
        return;
    }
    return function(response, status) {
        // if directions service successfully returns and no polylines exist already, then do the following
        if (status == google.maps.DirectionsStatus.ZERO_RESULTS) {
            toggleError("No routes available for selected locations");
            return;
        }
        if (status == google.maps.DirectionsStatus.OK) {
            startLocation[routeNum] = new Object();
            endLocation[routeNum] = new Object();
            // set up polyline for current route
            polyLine[routeNum] = new google.maps.Polyline({
                path: [],
                strokeColor: '#FFFF00',
                strokeWeight: 3
            });
            poly2[routeNum] = new google.maps.Polyline({
                path: [],
                strokeColor: '#FFFF00',
                strokeWeight: 3
            });
            // For each route, display summary information.
            var legs = response.routes[0].legs;
            // directionsrenderer renders the directions obtained previously by the directions service
            disp = new google.maps.DirectionsRenderer(rendererOptions);
            disp.setMap(map1);
            disp.setOptions({ suppressMarkers: true, suppressPolylines: true });
            disp.setDirections(response);
            // create Markers
            for (i = 0; i < legs.length; i++) {
                // for first marker only
                if (i == 0) {
                    startLocation[routeNum].latlng = legs[i].start_location;
                    startLocation[routeNum].address = legs[i].start_address;
                    marker1[routeNum] = createMarker(legs[i].start_location, "start", legs[i].start_address, "black");
                }
                endLocation[routeNum].latlng = legs[i].end_location;
                endLocation[routeNum].address = legs[i].end_address;
                var steps = legs[i].steps;
                for (j = 0; j < steps.length; j++) {
                    var nextSegment = steps[j].path;
                    for (k = 0; k < nextSegment.length; k++) {
                        polyLine[routeNum].getPath().push(nextSegment[k]);
                    }
                }
            }
        }
        if (polyLine[routeNum]) {
            // render the line to map
            //            polyLine[routeNum].setMap(map1);
            // and start animation
            startAnimation(routeNum);
        }
    }
}
// returns the marker
function createMarker(latlng, label, html) {
    var contentString = '<b>' + label + '</b><br>' + html;
    // using Marker api, marker is created
    //    console.log(vehiclesfordel[vehid].icon);
    var marker1 = new google.maps.Marker({
        position: latlng,
        map: map1,
        title: label,
        zIndex: 10,
        icon: icon
    });
    marker1.myname = label;
    // adding click listener to open up info window when marker is clicked
    google.maps.event.addListener(marker1, 'click', function() {
        infoWindow.setContent(contentString);
        infoWindow.open(map1, marker1);
    });
    return marker1;
}
// Spawn a new polyLine every 20 vertices
function updatePoly(i, d) {
    if (poly2[i].getPath().getLength() > 20) {
        poly2[i] = new google.maps.Polyline([polyLine[i].getPath().getAt(lastVertex - 1)]);
    }
    if (polyLine[i].GetIndexAtDistance(d) < lastVertex + 2) {
        if (poly2[i].getPath().getLength() > 1) {
            poly2[i].getPath().removeAt(poly2[i].getPath().getLength() - 1)
        }
        poly2[i].getPath().insertAt(poly2[i].getPath().getLength(), polyLine[i].GetPointAtDistance(d));
    } else {
        poly2[i].getPath().insertAt(poly2[i].getPath().getLength(), endLocation[i].latlng);
    }
}
// updates marker position to make the animation and update the polyline
function animate(index, d, tick) {
    if (d > eol[index]) {
        marker1[index].setPosition(endLocation[index].latlng);
        return;
    }
    var p = polyLine[index].GetPointAtDistance(d);
    map1.setCenter(p);
    map1.panTo(p);
    if (d == 11) {
        map1.set('zoom', 18);
    }
    var lastPosn = marker1[index].getPosition();
    marker1[index].setPosition(p);
    var heading = google.maps.geometry.spherical.computeHeading(lastPosn, p);
    icon.rotation = heading;
    marker1[index].setIcon(icon);
    updatePoly(index, d);
    if (d % 100 == 0) {
        timerHandle[index] = setTimeout("animate(" + index + "," + (d + step) + ")", tick || 200);
    } else if (d % 50 == 0) {
        timerHandle[index] = setTimeout("animate(" + index + "," + (d + step) + ")", tick || 50);
    } else if (d % 20 == 0) {
        timerHandle[index] = setTimeout("animate(" + index + "," + (d + step) + ")", tick || 100);
    } else {
        timerHandle[index] = setTimeout("animate(" + index + "," + (d + step) + ")", tick || 150);
    }
}
// start marker movement by updating marker position every 100 milliseconds i.e. tick value
function startAnimation(index) {
    if (timerHandle[index])
        clearTimeout(timerHandle[index]);
    eol[index] = polyLine[index].Distance();
    poly2[index] = new google.maps.Polyline({
        path: [polyLine[index].getPath().getAt(0)],
        strokeColor: "#FFFF00",
        strokeWeight: 8
    });
    timerHandle[index] = setTimeout("animate(" + index + ",1)", 50); // Allow time for the initial map display
}
google.maps.LatLng.prototype.distanceFrom = function(newLatLng) {
    var EarthRadiusMeters = 6378137.0; // meters
    var lat1 = this.lat();
    var lon1 = this.lng();
    var lat2 = newLatLng.lat();
    var lon2 = newLatLng.lng();
    var dLat = (lat2 - lat1) * Math.PI / 180;
    var dLon = (lon2 - lon1) * Math.PI / 180;
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = EarthRadiusMeters * c;
    return d;
}
google.maps.LatLng.prototype.latRadians = function() {
    return this.lat() * Math.PI / 180;
}
google.maps.LatLng.prototype.lngRadians = function() {
    return this.lng() * Math.PI / 180;
}
// === A method for testing if a point is inside a polygon
// === Returns true if poly contains point
// === Algorithm shamelessly stolen from http://alienryderflex.com/polygon/ 
google.maps.Polygon.prototype.Contains = function(point) {
    var j = 0;
    var oddNodes = false;
    var x = point.lng();
    var y = point.lat();
    for (var i = 0; i < this.getPath().getLength(); i++) {
        j++;
        if (j == this.getPath().getLength()) {
            j = 0;
        }
        if (((this.getPath().getAt(i).lat() < y) && (this.getPath().getAt(j).lat() >= y)) ||
            ((this.getPath().getAt(j).lat() < y) && (this.getPath().getAt(i).lat() >= y))) {
            if (this.getPath().getAt(i).lng() + (y - this.getPath().getAt(i).lat()) /
                (this.getPath().getAt(j).lat() - this.getPath().getAt(i).lat()) *
                (this.getPath().getAt(j).lng() - this.getPath().getAt(i).lng()) < x) {
                oddNodes = !oddNodes
            }
        }
    }
    return oddNodes;
}
// === A method which returns the approximate area of a non-intersecting polygon in square metres ===
// === It doesn't fully account for spherical geometry, so will be inaccurate for large polygons ===
// === The polygon must not intersect itself ===
google.maps.Polygon.prototype.Area = function() {
    var a = 0;
    var j = 0;
    var b = this.Bounds();
    var x0 = b.getSouthWest().lng();
    var y0 = b.getSouthWest().lat();
    for (var i = 0; i < this.getPath().getLength(); i++) {
        j++;
        if (j == this.getPath().getLength()) {
            j = 0;
        }
        var x1 = this.getPath().getAt(i).distanceFrom(new google.maps.LatLng(this.getPath().getAt(i).lat(), x0));
        var x2 = this.getPath().getAt(j).distanceFrom(new google.maps.LatLng(this.getPath().getAt(j).lat(), x0));
        var y1 = this.getPath().getAt(i).distanceFrom(new google.maps.LatLng(y0, this.getPath().getAt(i).lng()));
        var y2 = this.getPath().getAt(j).distanceFrom(new google.maps.LatLng(y0, this.getPath().getAt(j).lng()));
        a += x1 * y2 - x2 * y1;
    }
    return Math.abs(a * 0.5);
}
// === A method which returns the length of a path in metres ===
google.maps.Polygon.prototype.Distance = function() {
    var dist = 0;
    for (var i = 1; i < this.getPath().getLength(); i++) {
        dist += this.getPath().getAt(i).distanceFrom(this.getPath().getAt(i - 1));
    }
    return dist;
}
// === A method which returns the bounds as a GLatLngBounds ===
google.maps.Polygon.prototype.Bounds = function() {
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0; i < this.getPath().getLength(); i++) {
        bounds.extend(this.getPath().getAt(i));
    }
    return bounds;
}
// === A method which returns a GLatLng of a point a given distance along the path ===
// === Returns null if the path is shorter than the specified distance ===
google.maps.Polygon.prototype.GetPointAtDistance = function(metres) {
    // some awkward special cases
    if (metres == 0)
        return this.getPath().getAt(0);
    if (metres < 0)
        return null;
    if (this.getPath().getLength() < 2)
        return null;
    var dist = 0;
    var olddist = 0;
    for (var i = 1;
        (i < this.getPath().getLength() && dist < metres); i++) {
        olddist = dist;
        dist += this.getPath().getAt(i).distanceFrom(this.getPath().getAt(i - 1));
    }
    if (dist < metres) {
        return null;
    }
    var p1 = this.getPath().getAt(i - 2);
    var p2 = this.getPath().getAt(i - 1);
    var m = (metres - olddist) / (dist - olddist);
    return new google.maps.LatLng(p1.lat() + (p2.lat() - p1.lat()) * m, p1.lng() + (p2.lng() - p1.lng()) * m);
}
// === A method which returns an array of GLatLngs of points a given interval along the path ===
google.maps.Polygon.prototype.GetPointsAtDistance = function(metres) {
    var next = metres;
    var points = [];
    // some awkward special cases
    if (metres <= 0)
        return points;
    var dist = 0;
    var olddist = 0;
    for (var i = 1;
        (i < this.getPath().getLength()); i++) {
        olddist = dist;
        dist += this.getPath().getAt(i).distanceFrom(this.getPath().getAt(i - 1));
        while (dist > next) {
            var p1 = this.getPath().getAt(i - 1);
            var p2 = this.getPath().getAt(i);
            var m = (next - olddist) / (dist - olddist);
            points.push(new google.maps.LatLng(p1.lat() + (p2.lat() - p1.lat()) * m, p1.lng() + (p2.lng() - p1.lng()) * m));
            next += metres;
        }
    }
    return points;
}
// === A method which returns the Vertex number at a given distance along the path ===
// === Returns null if the path is shorter than the specified distance ===
google.maps.Polygon.prototype.GetIndexAtDistance = function(metres) {
    // some awkward special cases
    if (metres == 0)
        return this.getPath().getAt(0);
    if (metres < 0)
        return null;
    var dist = 0;
    var olddist = 0;
    for (var i = 1;
        (i < this.getPath().getLength() && dist < metres); i++) {
        olddist = dist;
        dist += this.getPath().getAt(i).distanceFrom(this.getPath().getAt(i - 1));
    }
    if (dist < metres) {
        return null;
    }
    return i;
}
// === A function which returns the bearing between two vertices in decgrees from 0 to 360===
// === If v1 is null, it returns the bearing between the first and last vertex ===
// === If v1 is present but v2 is null, returns the bearing from v1 to the next vertex ===
// === If either vertex is out of range, returns void ===
google.maps.Polygon.prototype.Bearing = function(v1, v2) {
    if (v1 == null) {
        v1 = 0;
        v2 = this.getPath().getLength() - 1;
    } else if (v2 == null) {
        v2 = v1 + 1;
    }
    if ((v1 < 0) || (v1 >= this.getPath().getLength()) || (v2 < 0) || (v2 >= this.getPath().getLength())) {
        return;
    }
    var from = this.getPath().getAt(v1);
    var to = this.getPath().getAt(v2);
    if (from.equals(to)) {
        return 0;
    }
    var lat1 = from.latRadians();
    var lon1 = from.lngRadians();
    var lat2 = to.latRadians();
    var lon2 = to.lngRadians();
    var angle = -Math.atan2(Math.sin(lon1 - lon2) * Math.cos(lat2), Math.cos(lat1) * Math.sin(lat2) - Math.sin(lat1) * Math.cos(lat2) * Math.cos(lon1 - lon2));
    if (angle < 0.0)
        angle += Math.PI * 2.0;
    angle = angle * 180.0 / Math.PI;
    return parseFloat(angle.toFixed(1));
}
// === Copy all the above functions to GPolyline ===
google.maps.Polyline.prototype.Contains = google.maps.Polygon.prototype.Contains;
google.maps.Polyline.prototype.Area = google.maps.Polygon.prototype.Area;
google.maps.Polyline.prototype.Distance = google.maps.Polygon.prototype.Distance;
google.maps.Polyline.prototype.Bounds = google.maps.Polygon.prototype.Bounds;
google.maps.Polyline.prototype.GetPointAtDistance = google.maps.Polygon.prototype.GetPointAtDistance;
google.maps.Polyline.prototype.GetPointsAtDistance = google.maps.Polygon.prototype.GetPointsAtDistance;
google.maps.Polyline.prototype.GetIndexAtDistance = google.maps.Polygon.prototype.GetIndexAtDistance;
google.maps.Polyline.prototype.Bearing = google.maps.Polygon.prototype.Bearing;