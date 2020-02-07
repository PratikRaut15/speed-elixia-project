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
var periodictimemap = 60000;

var styles = [
    {"featureType": "road", "elementType": "geometry.stroke", "stylers": [{"visibility": "off"}]}, {"featureType": "poi.park", "stylers": [{"visibility": "simplified"}, {"lightness": 46}]}, {"featureType": "poi", "elementType": "labels", "stylers": [{"visibility": "on"}]}, {"featureType": "road.highway", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#9e9e9f"}]}, {"featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [{"color": "#bfbfbf"}]}, {"featureType": "road.local", "elementType": "geometry.fill", "stylers": [{"color": "#e0e0e0"}]}, {"featureType": "poi.park", "stylers": [{"lightness": 38}]}, {"stylers": [{"saturation": -54}]}];
//aluto height adjust
jQuery.noConflict();
jQuery(document).ready(function () {
    //map init
    jQuery('.veh_all').attr('disabled', true);
    jQuery('.chk_all').attr('disabled', true);
    initialize();
    jQuery('#gc-topnav2').css("display", "block");
    // Handler for .ready() called.
    var browserHeight = jQuery(window).height();
    jQuery('#sidebar').css("height", browserHeight);
    jQuery('#map').css("height", browserHeight);
    jQuery('#mapdetails').css("height", 30);
    jQuery('#wrapper').css("height", browserHeight);
    jQuery('#pre').css("display", "block");
    jQuery(".all_select").click(function () {
        selectall(jQuery(this).data('type'));
    });
    jQuery(".all_clear").click(function () {
        clearall(jQuery(this).data('type'));
    });
    jQuery(".scrollablediv").height(browserHeight * 15 / 100);
    jQuery("#gc-topnav2").draggable();

});

function initialize() {
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });
    var mumbai = new google.maps.LatLng(19.03590687086149, 72.94649211215824);
    var minZoomLevel = 20;
    var mapOptions = {
        center: mumbai,
        panControl: true,
        streetViewControl: false,
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
        styles: styles,
          zoom: 20,
          scrollwheel: false,
          zoomControl: false,
          draggable: false,
         scaleControl: false,
    };
    map = new google.maps.Map(document.getElementById('map'), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    // Bounds for North America
   var strictBounds = new google.maps.LatLngBounds(
     new google.maps.LatLng(23.63, 68.14),
     new google.maps.LatLng(28.20, 97.34)
   );

   // Listen for the dragend event
   google.maps.event.addListener(map, 'dragend', function() {
     if (strictBounds.contains(map.getCenter())) return;

     // We're out of bounds - Move the map back within the bounds

     var c = map.getCenter(),
         x = c.lng(),
         y = c.lat(),
         maxX = strictBounds.getNorthEast().lng(),
         maxY = strictBounds.getNorthEast().lat(),
         minX = strictBounds.getSouthWest().lng(),
         minY = strictBounds.getSouthWest().lat();

     if (x < minX) x = minX;
     if (x > maxX) x = maxX;
     if (y < minY) y = minY;
     if (y > maxY) y = maxY;

     map.setCenter(new google.maps.LatLng(y, x));
   });

   // Limit the zoom level
   google.maps.event.addListener(map, 'dragend', function() {
     if (map.getZoom() < minZoomLevel) map.setZoom(minZoomLevel);
   });



    var params = "all=1";
    jQuery.ajax({
        type: "POST",
        url: "route_ajax.php?monitor=1",
        cache: false,
        success: function (data) {
            var cdata1 = jQuery.parseJSON(data);
            var results = cdata1.result;
            //console.log(results);
            // Marker Clustering
            markers = [];
            var bounds = new google.maps.LatLngBounds();
            jQuery.each(results, function (i, deviceList) {

                var device = deviceList[0];
                var contentString = '';

                device.image = device.image.replace("Warehouse", "WarehouseMap");
                var image = new google.maps.MarkerImage(device.image,
                        new google.maps.Size(48, 48),
                        new google.maps.Point(0, 0),
                        new google.maps.Point(8, 20));
                var latLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
                var marker = new MarkerWithLabel({
                    position: latLng,
                    map: map,
                    icon: image,

                    labelAnchor: new google.maps.Point(9, 45)
                });
                bounds.extend(marker.position);


                jQuery.each(deviceList, function (i, device) {
                    var temperatureString = '';
                    if (device.temp_sensors == 1)
                    {
                        var temperature;
                        temperature = device.temp;
                        if (device.tempon == 1)
                        {
                            temperature = temperature + " 0".sup() + "C";
                        }
                        temperatureString += '<br> '+device.t1+' : ' + temperature + ' ' + device.t1Link;
                    } else if(device.temp_sensors == 2) {
                        var temperature1, temperature2;
                        temperature1 = device.temp1;
                        temperature2 = device.temp2;
                            if (device.temp1on == 1)
                            {
                                temperature1 = temperature1 + " 0".sup() + "C";
                            }
                            if (device.temp2on == 1)
                            {
                                temperature2 = temperature2 + " 0".sup() + "C";
                            }
                            temperatureString += '<br> '+device.t1+' : ' + temperature1+ ' ' + device.t1Link;
                            temperatureString += '<br> '+device.t2+' : ' + temperature2+ ' ' + device.t2Link;
                    }
                    else if(device.temp_sensors == 3) {
                        var temperature1, temperature2, temperature3;
                        temperature1 = device.temp1;
                        temperature2 = device.temp2;
                        temperature3 = device.temp3;
                            if (device.temp1on == 1)
                            {
                                temperature1 = temperature1 + " 0".sup() + "C";
                            }
                            if (device.temp2on == 1)
                            {
                                temperature2 = temperature2 + " 0".sup() + "C";
                            }
                            if (device.temp3on == 1)
                            {
                                temperature3 = temperature3 + " 0".sup() + "C";
                            }
                            temperatureString += '<br> '+device.t1+' : ' + temperature1+ ' ' + device.t1Link;
                            temperatureString += '<br> '+device.t2+' : ' + temperature2+ ' ' + device.t2Link;
                            temperatureString += '<br> '+device.t3+' : ' + temperature3+ ' ' + device.t3Link;
                    }
                    else if(device.temp_sensors == 4) {
                        var temperature1, temperature2, temperature3,temperature4;
                            temperature1 = device.temp1;
                            temperature2 = device.temp2;
                            temperature3 = device.temp3;
                            temperature4 = device.temp4;
                            if (device.temp1on == 1)
                            {
                                temperature1 = temperature1 + " 0".sup() + "C";
                            }
                            if (device.temp2on == 1)
                            {
                                temperature2 = temperature2 + " 0".sup() + "C";
                            }
                            if (device.temp3on == 1)
                            {
                                temperature3 = temperature3 + " 0".sup() + "C";
                            }
                            if (device.temp4on == 4)
                            {
                                temperature4 = temperature4 + " 0".sup() + "C";
                            }
                            temperatureString += '<br/> '+device.t1+' : ' + temperature1+ ' ' + device.t1Link;
                            temperatureString += '<br/> '+device.t2+' : ' + temperature2+ ' ' + device.t2Link;
                            temperatureString += '<br/> '+device.t3+' : ' + temperature3+ ' ' + device.t3Link;
                            temperatureString += '<br/> '+device.t4+' : ' + temperature4+ ' ' + device.t4Link;
                    }
                    var humidityString = '';
                    if (device.use_humidity == 1)
                    {
                        var humidity;
                        humidity = device.humidity;
                        humidityString += '<br> Humidity : ' + humidity;
                    }
                    var deviceVehicleNo = '<div id="info_window_header">Warehouse : ' + device.cname + '</div><br>';
                    var deviceLastUpdated = 'Last Updated : '+ device.clastupdated + '';



                    var deviceTemperature ='';
                    if(device.temp_sensors > 0) {
                        deviceTemperature = temperatureString+"<br/>";
                    }

                    contentString += deviceVehicleNo+deviceLastUpdated+deviceTemperature+humidityString;

                });

                //infoboxjs

                var boxText = document.createElement("div");
                boxText.style.cssText = "";
                boxText.innerHTML = "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> " + contentString  + "</div></div></div>";
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
                    closeBoxMargin: "15px 11px 2px",
                    closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
                    infoBoxClearance: new google.maps.Size(1, 1),
                    isHidden: false,
                    pane: "floatPane",
                    enableEventPropagation: false
                };
                var ib = new InfoBox(myOptions);
                //ib.open(map, marker);
                google.maps.event.addListener(marker, "click", function (e) {
                    ib.open(map, this);
                });
                //google.maps.event.addListener(marker, 'mouseout', function (e) {
                    //ib.close();
                //});
                vehid = device.cvehicleid;
                marker.set("id", device.cvehicleid);
                vehiclesfordel[vehid] = marker;


            });
            map.fitBounds(bounds);
            //markerCluster = new MarkerClusterer(map, markers);
            /* share map to 3rd party with userkey */
            var userkey = jQuery("#userkey").val();
            if (userkey != '' && typeof userkey !== 'undefined') {
                setVehOnMapWithUserkey();
            }
        }
    });
    periodicupdate();
}
function vehplot(vehid)
{
    marker = vehiclesfordel[vehid];
    if ((jQuery("#veh_" + vehid).length != 0) && jQuery("#veh_" + vehid).is(':checked') == true)
    {
        if (marker)
        {
            marker.setMap(map);
        }
    } else if ((jQuery("#veh_" + vehid).length != 0) && jQuery("#veh_" + vehid).is(':checked') == false)
    {
        if (marker)
        {
            marker.setMap(null);
        }
        remove(markers, marker);
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
        jQuery('#maptoggler').css("left", "192px");
        counter++;
    }
}
function initmap(lat, lng) {
    var minZoomLevel = 20;
    if (gmapsinited)
        return;
    var latlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
        zoom: 20,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map"), myOptions);
    gmapsinited = true;

    // Bounds for North America
   var strictBounds = new google.maps.LatLngBounds(
     new google.maps.LatLng(23.63, 68.14),
     new google.maps.LatLng(28.20, 97.34)
   );

   // Listen for the dragend event
   google.maps.event.addListener(map, 'dragend', function() {
     if (strictBounds.contains(map.getCenter())) return;

     // We're out of bounds - Move the map back within the bounds

     var c = map.getCenter(),
         x = c.lng(),
         y = c.lat(),
         maxX = strictBounds.getNorthEast().lng(),
         maxY = strictBounds.getNorthEast().lat(),
         minX = strictBounds.getSouthWest().lng(),
         minY = strictBounds.getSouthWest().lat();

     if (x < minX) x = minX;
     if (x > maxX) x = maxX;
     if (y < minY) y = minY;
     if (y > maxY) y = maxY;

     map.setCenter(new google.maps.LatLng(y, x));
   });

   // Limit the zoom level
   google.maps.event.addListener(map, 'dragend', function() {
     if (map.getZoom() < minZoomLevel) map.setZoom(minZoomLevel);
   });



}
function mapvehicles() {
    addAllVehicles();
}
function periodicupdate() {
    setTimeout(function () {
        refreshdata();
    }, periodictimemap);
}

function tempreport(vehicleid, sensor, deviceid) {
    window.open("../reports/reports.php?id=13&vehicleid=" + vehicleid + "&tempsen=" + sensor + "&devid=" + deviceid, "_blank");
}
function refreshdata()
{
    var vehicleid = "";
    vehicleid = jQuery("#vehicleid_given").val();

    var urldata = "route_ajax.php?monitor=1";

    jQuery.ajax({
        type: "POST",
        url: urldata,
        async: true,
        cache: false,
        success: function (data) {
            jQuery.each(vehiclesfordel, function (index, value) {
                marker = vehiclesfordel[index];
                if (marker)
                {
                    marker.setMap(null);
                }
                // Remove from Cluster

                markers = [];
            });
            var cdata1 = jQuery.parseJSON(data);
            var results = cdata1.result;
            vehiclesfordel = {};
            markers = [];
            var bounds = new google.maps.LatLngBounds();
            // Marker Clustering
            jQuery.each(results, function (i, deviceList) {

                var device = deviceList[0];
                var contentString = '';

                device.image = device.image.replace("Warehouse", "WarehouseMap");
                var image = new google.maps.MarkerImage(device.image,
                        new google.maps.Size(48, 48),
                        new google.maps.Point(0, 0),
                        new google.maps.Point(8, 20));
                var latLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
                var marker = new MarkerWithLabel({
                    position: latLng,
                    map: map,
                    icon: image,

                    labelAnchor: new google.maps.Point(9, 45)
                });
                bounds.extend(marker.position);


                jQuery.each(deviceList, function (i, device) {
                    var temperatureString = '';
                    if (device.temp_sensors == 1)
                    {
                        var temperature;
                        temperature = device.temp;
                        if (device.tempon == 1)
                        {
                            temperature = temperature + " 0".sup() + "C";
                        }
                        temperatureString += '<br> '+device.t1+' : ' + temperature + ' ' + device.t1Link;
                    } else if(device.temp_sensors == 2) {
                        var temperature1, temperature2;
                        temperature1 = device.temp1;
                        temperature2 = device.temp2;
                            if (device.temp1on == 1)
                            {
                                temperature1 = temperature1 + " 0".sup() + "C";
                            }
                            if (device.temp2on == 1)
                            {
                                temperature2 = temperature2 + " 0".sup() + "C";
                            }
                            temperatureString += '<br> '+device.t1+' : ' + temperature1+ ' ' + device.t1Link;
                            temperatureString += '<br> '+device.t2+' : ' + temperature2+ ' ' + device.t2Link;
                    }
                    else if(device.temp_sensors == 3) {
                        var temperature1, temperature2, temperature3;
                        temperature1 = device.temp1;
                        temperature2 = device.temp2;
                        temperature3 = device.temp3;
                            if (device.temp1on == 1)
                            {
                                temperature1 = temperature1 + " 0".sup() + "C";
                            }
                            if (device.temp2on == 1)
                            {
                                temperature2 = temperature2 + " 0".sup() + "C";
                            }
                            if (device.temp3on == 1)
                            {
                                temperature3 = temperature3 + " 0".sup() + "C";
                            }
                            temperatureString += '<br> '+device.t1+' : ' + temperature1+ ' ' + device.t1Link;
                            temperatureString += '<br> '+device.t2+' : ' + temperature2+ ' ' + device.t2Link;
                            temperatureString += '<br> '+device.t3+' : ' + temperature3+ ' ' + device.t3Link;
                    }
                    else if(device.temp_sensors == 4) {
                        var temperature1, temperature2, temperature3,temperature4;
                            temperature1 = device.temp1;
                            temperature2 = device.temp2;
                            temperature3 = device.temp3;
                            temperature4 = device.temp4;
                            if (device.temp1on == 1)
                            {
                                temperature1 = temperature1 + " 0".sup() + "C";
                            }
                            if (device.temp2on == 1)
                            {
                                temperature2 = temperature2 + " 0".sup() + "C";
                            }
                            if (device.temp3on == 1)
                            {
                                temperature3 = temperature3 + " 0".sup() + "C";
                            }
                            if (device.temp4on == 4)
                            {
                                temperature4 = temperature4 + " 0".sup() + "C";
                            }
                            temperatureString += '<br/> '+device.t1+' : ' + temperature1+ ' ' + device.t1Link;
                            temperatureString += '<br/> '+device.t2+' : ' + temperature2+ ' ' + device.t2Link;
                            temperatureString += '<br/> '+device.t3+' : ' + temperature3+ ' ' + device.t3Link;
                            temperatureString += '<br/> '+device.t4+' : ' + temperature4+ ' ' + device.t4Link;
                    }
                    var humidityString = '';
                    if (device.use_humidity == 1)
                    {
                        var humidity;
                        humidity = device.humidity;
                        humidityString += '<br> Humidity : ' + humidity;
                    }
                    var deviceVehicleNo = '<div id="info_window_header">Warehouse : ' + device.cname + '</div><br>';
                    var deviceLastUpdated = 'Last Updated : '+ device.clastupdated + '';



                    var deviceTemperature ='';
                    if(device.temp_sensors > 0) {
                        deviceTemperature = temperatureString+"<br/>";
                    }

                    contentString += deviceVehicleNo+deviceLastUpdated+deviceTemperature+humidityString;

                });

                //infoboxjs

                var boxText = document.createElement("div");
                boxText.style.cssText = "";
                boxText.innerHTML = "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> " + contentString  + "</div></div></div>";
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
                    closeBoxMargin: "15px 11px 2px",
                    closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
                    infoBoxClearance: new google.maps.Size(1, 1),
                    isHidden: false,
                    pane: "floatPane",
                    enableEventPropagation: false
                };
                var ib = new InfoBox(myOptions);
                //ib.open(map, marker);
                google.maps.event.addListener(marker, "click", function (e) {
                    ib.open(map, this);
                });
                //google.maps.event.addListener(marker, 'mouseout', function (e) {
                    //ib.close();
                //});
                vehid = device.cvehicleid;
                marker.set("id", device.cvehicleid);
                vehiclesfordel[vehid] = marker;


            });
            jQuery.each(vehiclesfordel, function (index, value) {
                if ((jQuery("#veh_" + index).length != 0) && jQuery("#veh_" + index).is(':checked') == false)
                {
                    marker = vehiclesfordel[index];
                    if (marker)
                    {
                        marker.setMap(null);
                    }
                    remove(markers, marker);
                }
            });
            jQuery.each(vehiclesfordel, function (index, value) {
                if ((jQuery("#wh_" + index).length != 0) && jQuery("#wh_" + index).is(':checked') == false)
                {
                    marker = vehiclesfordel[index];
                    if (marker)
                    {
                        marker.setMap(null);
                    }
                    remove(markers, marker);
                }
            });
            if (markers.length != 0)
            {
                //
                //markerCluster = new MarkerClusterer(map, markers);
            }
            // Periodic Update
            periodicupdate();
        }
    });
}
function plotvehicles(cdata) {
    evictMarkers();
    var results = cdata.result;
    jQuery.each(results, function (i, device) {
        try {
            function closure() {
                infowindow.close();
            }
            initmap(device.cgeolat, device.cgeolong);
            device.image = device.image.replace("Warehouse", "WarehouseMap");
            var image = new google.maps.MarkerImage(device.image,
                    new google.maps.Size(48, 48),
                    new google.maps.Point(0, 0),
                    new google.maps.Point(8, 20));
            var myLatLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
            var marker = new MarkerWithLabel({
                position: myLatLng,
                icon: image,
                map: map,

                labelAnchor: new google.maps.Point(9, 45),
                labelClass: "mapslabels" // the CSS class for the label
            });
            map.panTo(marker.getPosition());
            eviction_list.push(marker);
            var contentString = '<h3>' + device.cname + '</h3><hr><p>' +
                    'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + ']<br>' +
                    'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : ' + device.clastupdated +
                    '<hr><a href=../history/history.php?id=5&vid=' + device.cvehicleid + '><u>Vehicle History</u> </a>';

            var boxText = document.createElement("div");
            boxText.style.cssText = "";
            boxText.innerHTML = "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> " + contentString  + "</div></div></div>";
            boxText.className = "arrow_box";
            var myOptions = {
                content: boxText
                , disableAutoPan: false
                , maxWidth: 0
                , pixelOffset: new google.maps.Size(-110, -130)
                , zIndex: null
                , boxStyle: {
                    opacity: 0.99
                    , width: "280px"
                }
                , closeBoxMargin: "18px 13px 2px"
                , closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
                , infoBoxClearance: new google.maps.Size(1, 1)
                , isHidden: false
                , pane: "floatPane"
                , enableEventPropagation: false
            };
            var ib = new InfoBox(myOptions);
            //ib.open(map, marker);
            google.maps.event.addListener(marker, "click", function (e) {
                ib.open(map, this);
            });
            //google.maps.event.addListener(marker, 'mouseout', function (e) {
                //    ib.close();
               // });
        } catch (ex) {
            alert(ex);
        }
    });
}
// need testting not confirmed
function evictMarkers() {
    // clear all markers
    jQuery.each(eviction_list, function (i, item) {
        item.setMap(null);
    });
    // reset the eviction array
    eviction_list = [];
}
//
function delete_all() {
    jQuery.each(eviction_list, function (i, item) {
        item.setMap(null);
    });
    // reset the eviction array
    eviction_list = [];
    jQuery("#vehicle_list").html("");
}

// unidentified
function getselvehicles() {
    var params = "all=1";
    new Ajax.Request('route_ajax.php', {
        parameters: params,
        onSuccess: function (transport) {
            var cdata1 = transport.responseText.evalJSON();
            results = cdata1.result;
//            plotvehicles(cdata);
//            getchk();
//            periodicupdate();
        },
        onComplete: function () {
        }
    });
}
// jQuery ok
function removevehicle(id) {
    jQuery('#to_vehicle_div_' + id).remove();
    delete vehicle_list[vehicle_list.indexOf(id)];
    setTimeout(function () {
        getselvehicles();
    }, 10000);
}
// jquery ok
function addAllVehicles() {
    jQuery("#to option").each(function (index, element) {
        jQuery("#to").val(jQuery(element).val());
        addVehicle();
    });
    getselvehicles();
}
function setVehOnMapWithUserkey() {
    var vehicleid = jQuery("#vehicleid_given").val();
    jQuery('#divheader').hide();
    jQuery('.bs-docs-example').hide();
    jQuery("#footer").hide();
    jQuery("#maptoggler").hide();
    jQuery("#sidebar").hide();
    if(vehicleid!=""){
        clearall('vehicles');
    }
    jQuery("#veh_" + vehicleid).attr("checked", true);
    vehplot(vehicleid);
}
