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
if (customerrefreshfrqmap == 267) {
    var periodictimemap = 10000;
} else {
    var periodictimemap = 60000;
}

var styles = [
    {"featureType": "road", "elementType": "geometry.stroke", "stylers": [{"visibility": "off"}]}, {"featureType": "poi.park", "stylers": [{"visibility": "simplified"}, {"lightness": 46}]}, {"featureType": "poi", "elementType": "labels", "stylers": [{"visibility": "on"}]}, {"featureType": "road.highway", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#9e9e9f"}]}, {"featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [{"color": "#bfbfbf"}]}, {"featureType": "road.local", "elementType": "geometry.fill", "stylers": [{"color": "#e0e0e0"}]}, {"featureType": "poi.park", "stylers": [{"lightness": 38}]}, {"stylers": [{"saturation": -54}]}];

//aluto height adjust
jQuery.noConflict();

jQuery(document).ready(function () {
    //map init
    jQuery('.veh_all').attr('disabled', true);
    jQuery('.chk_all').attr('disabled', true);
    initialize();
    if($("#hddn_ecode").val() != '' || $("#hddn_ecode").val() != undefined){

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
    jQuery(".all_select").click(function () {
        selectall(jQuery(this).data('type'));
    });
    jQuery(".all_clear").click(function () {
        clearall(jQuery(this).data('type'));
    });

    jQuery(".scrollablediv").height(browserHeight * 15 / 100);
    jQuery("#gc-topnav2").draggable();

    BindVehicleSearch();
    BindChkPtSearch();
    BindFenceSearch();
});

function selectall(type)
{
    switch (type)
    {
        case 'vehicles':
            markers = [];  //empty array
            markerCluster.clearMarkers();
            jQuery(".veh_all").each(function () {
                jQuery(this).prop('checked', true);
            });

            jQuery.each(vehiclesfordel, function (index, value) {
                marker = vehiclesfordel[index];
                if (marker)
                {
                    marker.setMap(map);
                }

                // Add in Cluster
                markers.push(marker);

            });

            markerCluster.clearMarkers();
            markerCluster = new MarkerClusterer(map, markers);
            break;

        case 'warehouse':
            jQuery(".wh_all").each(function () {
                jQuery(this).prop('checked', true);
            });

            jQuery.each(vehiclesfordel, function (index, value) {
                marker = vehiclesfordel[index];
                if (marker)
                {
                    marker.setMap(map);
                }

                // Add in Cluster
                markers.push(marker);

            });

            markerCluster.clearMarkers();
            markerCluster = new MarkerClusterer(map, markers);
            break;


        case 'checkpoints':
            jQuery(".chk_all").each(function () {
                jQuery(this).prop('checked', true);
            });

            jQuery.each(markersfordel, function (index, value) {
                marker = markersfordel[index];
                marker.setMap(map);
                circle = circlesfordel[index];
                circle.setMap(map);
            });
            break;

        case 'fences':
            jQuery(".fence_all").each(function () {
                jQuery(this).prop('checked', true);
            });
            console.log(fencesfordel);
            jQuery.each(fencesfordel, function (index, value) {
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

function clearall(type)
{
    switch (type)
    {
        case 'vehicles':
            jQuery(".veh_all").each(function () {
                jQuery(this).prop('checked', false);
            });

            jQuery.each(vehiclesfordel, function (index, value) {
                marker = vehiclesfordel[index];
                if (marker)
                {
                    marker.setMap(null);
                }

                // Remove from Cluster
                markerCluster.clearMarkers();
                markers = [];
            });

//        markerCluster = new MarkerClusterer(map, markers);
            break;

        case 'warehouse':
            jQuery(".wh_all").each(function () {
                jQuery(this).prop('checked', false);
            });

            jQuery.each(vehiclesfordel, function (index, value) {
                marker = vehiclesfordel[index];
                if (marker)
                {
                    marker.setMap(null);
                }

                // Remove from Cluster
                markerCluster.clearMarkers();
                markers = [];
            });

//        markerCluster = new MarkerClusterer(map, markers);
            break;

        case 'checkpoints':
            jQuery(".chk_all").each(function () {
                jQuery(this).prop('checked', false);
            });

            jQuery.each(markersfordel, function (index, value) {
                marker = markersfordel[index];
                marker.setMap(null);
                circle = circlesfordel[index];
                circle.setMap(null);
            });

            break;

        case 'fences':
            jQuery(".fence_all").each(function () {
                jQuery(this).prop('checked', false);
            });

            jQuery.each(fencesfordel, function (index, value) {
                markr = markrsfordel[index];
                markr.setMap(null);
                poly = fencesfordel[index];
                poly.setMap(null);
            });

            break;
    }
}

/*Date: 24th oct 2014, ak added for working of search input*/
function locate() {
    address = jQuery("#chkA").val();
    if (!geocodeinited) {
        geocoder = new google.maps.Geocoder();
        geocodeinited = true;
    }
    geocoder.geocode({
        'address': address
    }, function (results, status) {
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

    google.maps.event.addListener(autocomplete, 'place_changed', function () {
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

    jQuery.ajax({
        type: "POST",
        url: "route_ajax.php?all=1",
        cache: false,
        success: function (data) {

            var cdata1 = jQuery.parseJSON(data);
            var results = cdata1.result;
            //console.log(results);
            // Marker Clustering
            markers = [];
            var bounds = new google.maps.LatLngBounds();

            jQuery.each(results, function (i, device) {
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
                        temperatureString += '<br> '+device.t1+' : ' + temperature1+ ' ' + device.t1Link;
                        temperatureString += '<br> '+device.t2+' : ' + temperature2+ ' ' + device.t2Link;
                        temperatureString += '<br> '+device.t3+' : ' + temperature3+ ' ' + device.t3Link;
                        temperatureString += '<br> '+device.t4+' : ' + temperature4+ ' ' + device.t4Link;
                }
                var humidityString = '';
                if (device.use_humidity == 1)
                {
                    var humidity;
                    humidity = device.humidity;

                    humidityString += '<br> Humidity : ' + humidity;
                }

                var deviceVehicleNo = 'Vehicle No : ' + device.cname + '<br>';
                var deviceDriver = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + '] <br>';
                var deviceLocation = 'Location : ' + device.clocation + ' <br>';
                var deviceSpeed = 'Current Speed : ' + device.cspeed + ' km/hr<br>';
                var deviceLastUpdated = 'Last Updated : '+ device.clastupdated + '';
                var deviceDistance = 'Distance : ' + device.totaldist+' Km<br/>';
                if (device.portable == 1){
                    deviceSpeed = '';
                    deviceDistance = '';
                }
                if (device.ckind == 'Warehouse'){
                    deviceDriver = '';
                    deviceSpeed = '';
                    deviceDistance = '';
                }
                var deviceDescription = '';
                if(device.description != "") {
                    //deviceDescription = 'Description : ' + device.description+'<br/>';
                }
                var deviceCheckpointList = '';
                if(device.checkpointlist!=''){
                   //deviceCheckpointList = 'Checkpointlist : ' + device.checkpointlist + '<br>';
                }
                var deviceTemperature ='';
                if(device.temp_sensors > 0) {
                    deviceTemperature = temperatureString+"<br/>";
                }



                /*
                var contentString = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + '] <br>'
                + 'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : '
                + device.clastupdated + '<br>Distance : ' + device.totaldist + temperatureString;
                */

                var contentString = deviceVehicleNo+deviceDriver+deviceLocation+deviceSpeed+deviceDistance+deviceLastUpdated+deviceDescription+deviceTemperature+humidityString+deviceCheckpointList;



                //infoboxjs
                var pop_data = "";
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

                google.maps.event.addListener(marker, "click", function (e) {
                    ib.open(map, this);

                });



                vehid = device.cvehicleid;
                marker.set("id", device.cvehicleid);
                vehiclesfordel[vehid] = marker;
                markers.push(marker);

            });

            map.fitBounds(bounds);

            markerCluster = new MarkerClusterer(map, markers);



            /* share map to 3rd party with userkey */
            var userkey = jQuery("#userkey").val();
            if (userkey != '' && typeof userkey !== 'undefined') {
                setVehOnMapWithUserkey();
            }
            jQuery('.veh_all').attr('disabled', false);
            jQuery('.chk_all').attr('disabled', false);
        }
    });


    periodicupdate();
}

function chkplot(chkid)
{
    if ((jQuery("#chk_" + chkid).length != 0) && jQuery("#chk_" + chkid).is(':checked') == true)
    {
        map.set('center', markersfordel[chkid].position);
        map.set('zoom', 15);
        marker = markersfordel[chkid];
        marker.setMap(map);
        circle = circlesfordel[chkid];
        circle.setMap(map);
    } else if ((jQuery("#chk_" + chkid).length != 0) && jQuery("#chk_" + chkid).is(':checked') == false)
    {
        marker = markersfordel[chkid];
        marker.setMap(null);
        circle = circlesfordel[chkid];
        circle.setMap(null);
    }
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
        markers.push(marker);
    } else if ((jQuery("#veh_" + vehid).length != 0) && jQuery("#veh_" + vehid).is(':checked') == false)
    {
        if (marker)
        {
            marker.setMap(null);
        }
        remove(markers, marker);
    }
    // Add in Cluster
    markerCluster.clearMarkers();
    if (markers.length != 0)
    {
        markerCluster = new MarkerClusterer(map, markers);
    }
}

function whplot(vehid)
{
    if ((jQuery("#wh_" + vehid).length != 0) && jQuery("#wh_" + vehid).is(':checked') === true)
    {
        marker = vehiclesfordel[vehid];
        if (marker)
        {
            marker.setMap(map);
        }

        // Add in Cluster
        markerCluster.clearMarkers();
        markers.push(marker);
        markerCluster = new MarkerClusterer(map, markers);
    } else if ((jQuery("#wh_" + vehid).length != 0) && jQuery("#wh_" + vehid).is(':checked') === false)
    {
        marker = vehiclesfordel[vehid];
        if (marker)
        {
            marker.setMap(null);
        }

        // Remove from Cluster
        markerCluster.clearMarkers();
        remove(markers, marker);

        if (markers.length != 0)
        {
            markerCluster = new MarkerClusterer(map, markers);
        }
    }
}

function fenceplot(fenceid)
{
    if ((jQuery("#fence_" + fenceid).length != 0) && jQuery("#fence_" + fenceid).is(':checked') == true)
    {
        marker = markrsfordel[fenceid];
        marker.setMap(map);
        poly = fencesfordel[fenceid];
        poly.setMap(map);
    } else if ((jQuery("#fence_" + fenceid).length != 0) && jQuery("#fence_" + fenceid).is(':checked') == false)
    {
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
        jQuery('#maptoggler').css("left", "192px");
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
    setTimeout(function () {
        refreshdata();
    }, periodictimemap);
}

function refreshmap()
{
    var vehicleid = "";
    vehicleid = jQuery("#vehicleid_given").val();
    jQuery.each(vehiclesfordel, function (index, value) {
        marker = vehiclesfordel[index];
        if (marker)
        {
            marker.setMap(null);
        }

        // Remove from Cluster
        markerCluster.clearMarkers();
        markers = [];
    });
    //console.log(vehicleid);


        var urldata = "route_ajax.php?all=1";


    jQuery.ajax({
        type: "POST",
        url: urldata,
        async: true,
        cache: false,
        success: function (data) {
            var cdata1 = jQuery.parseJSON(data);
            var results = cdata1.result;
            vehiclesfordel = {};
            markers = [];
            // Marker Clustering

            jQuery.each(results, function (i, device) {
                var image = new google.maps.MarkerImage(device.image,
                        new google.maps.Size(48, 48),
                        new google.maps.Point(0, 0),
                        new google.maps.Point(8, 20));

                var latLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
                var marker = new MarkerWithLabel({'position': latLng, map: map, icon: image, labelContent: device.cname,
                    labelAnchor: new google.maps.Point(9, 45),
                    labelClass: "mapslabels" // the CSS class for the label
                });


                var temperatureString = '';
                if (device.temp_sensors == 1)
                {
                    var temperature;
                    temperature = device.temp;
                    if (device.tempon == 1)
                    {
                        temperature = temperature + " 0".sup() + "C";

                    }
                    temperatureString += '<br> '+device.t1+' : ' + temperature+ ' ' + device.t1Link;
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
                        temperatureString += '<br> '+device.t1+' : ' + temperature1+ ' ' + device.t1Link;;
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
                        temperatureString += '<br> '+device.t1+' : ' + temperature1+ ' ' + device.t1Link;
                        temperatureString += '<br> '+device.t2+' : ' + temperature2+ ' ' + device.t2Link;
                        temperatureString += '<br> '+device.t3+' : ' + temperature3+ ' ' + device.t3Link;
                        temperatureString += '<br> '+device.t4+' : ' + temperature4+ ' ' + device.t4Link;
                }

                var humidityString = '';
                if (device.use_humidity == 1)
                {
                    var humidity;
                    humidity = device.humidity;

                    humidityString += '<br> Humidity : ' + humidity;
                }

                var deviceVehicleNo = 'Vehicle No : ' + device.cname + '<br>';
                var deviceDriver = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + '] <br>';
                var deviceLocation = 'Location : ' + device.clocation + ' <br>';
                var deviceSpeed = 'Current Speed : ' + device.cspeed + ' km/hr<br>';
                var deviceLastUpdated = 'Last Updated : '+ device.clastupdated + '';
                var deviceDistance = 'Distance : ' + device.totaldist+' Km<br/>';
                if (device.portable == 1){
                    deviceSpeed = '';
                    deviceDistance = '';
                }
                if (device.ckind == 'Warehouse'){
                    deviceDriver = '';
                    deviceSpeed = '';
                    deviceDistance = '';
                }
                var deviceDescription = '';
                if(device.description != "") {
                    //deviceDescription = 'Description : ' + device.description+'<br/>';
                }
                var deviceCheckpointList = '';
                if(device.checkpointlist!=''){
                   //deviceCheckpointList = 'Checkpointlist : ' + device.checkpointlist + '<br>';
                }
                var deviceTemperature ='';
                if(device.temp_sensors > 0) {
                    deviceTemperature = temperatureString+"<br/>";
                }



                /*
                var contentString = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + '] <br>'
                + 'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : '
                + device.clastupdated + '<br>Distance : ' + device.totaldist + temperatureString;
                */

                var contentString = deviceVehicleNo+deviceDriver+deviceLocation+deviceSpeed+deviceDistance+deviceLastUpdated+deviceDescription+deviceTemperature+humidityString+deviceCheckpointList;



                var pop_data = "";
                var boxText = document.createElement("div");
                boxText.style.cssText = "";
                boxText.innerHTML = "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> " + contentString + "<br/>" + pop_data + "</div></div></div>";
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


                vehid = device.cvehicleid;
                marker.set("id", device.cvehicleid);

                vehiclesfordel[vehid] = marker;
                markers.push(marker);
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
                markerCluster.clearMarkers();
                markerCluster = new MarkerClusterer(map, markers);
            }
        }

    });

}
function tempreport(vehicleid, sensor, deviceid) {
    window.open("../reports/reports.php?id=13&vehicleid=" + vehicleid + "&tempsen=" + sensor + "&devid=" + deviceid, "_blank");
}
function refreshdata()
{
    var vehicleid = "";
    vehicleid = jQuery("#vehicleid_given").val();

    var urldata = "route_ajax.php?all=1";


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
                markerCluster.clearMarkers();
                markers = [];
            });
            var cdata1 = jQuery.parseJSON(data);
            var results = cdata1.result;
            vehiclesfordel = {};
            markers = [];

            // Marker Clustering
            jQuery.each(results, function (i, device) {

                var image = new google.maps.MarkerImage(device.image,
                        new google.maps.Size(48, 48),
                        new google.maps.Point(0, 0),
                        new google.maps.Point(8, 20));

                var latLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
                var marker = new MarkerWithLabel({'position': latLng, map: map, icon: image, labelContent: device.cname,
                    labelAnchor: new google.maps.Point(9, 45),
                    labelClass: "mapslabels" // the CSS class for the label
                });

                var temperatureString = '';
                if (device.temp_sensors == 1)
                {
                    var temperature;
                    temperature = device.temp;
                    if (device.tempon == 1)
                    {
                        temperature = temperature + " 0".sup() + "C";

                    }
                    temperatureString += '<br> '+device.t1+' : ' + temperature+ ' ' + device.t1Link;
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
                        temperatureString += '<br> '+device.t1+' : ' + temperature1+ ' ' + device.t1Link;
                        temperatureString += '<br> '+device.t2+' : ' + temperature2+ ' ' + device.t2Link;
                        temperatureString += '<br> '+device.t3+' : ' + temperature3+ ' ' + device.t3Link;
                        temperatureString += '<br> '+device.t4+' : ' + temperature4+ ' ' + device.t4Link;
                }
                var humidityString = '';
                if (device.use_humidity == 1)
                {
                    var humidity;
                    humidity = device.humidity;

                    humidityString += '<br> Humidity : ' + humidity;
                }

                var deviceVehicleNo = 'Vehicle No : ' + device.cname + '<br>';
                var deviceDriver = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + '] <br>';
                var deviceLocation = 'Location : ' + device.clocation + ' <br>';
                var deviceSpeed = 'Current Speed : ' + device.cspeed + ' km/hr<br>';
                var deviceLastUpdated = 'Last Updated : '+ device.clastupdated + '';
                var deviceDistance = 'Distance : ' + device.totaldist+' Km<br/>';
                if (device.portable == 1){
                    deviceSpeed = '';
                    deviceDistance = '';
                }
                if (device.ckind == 'Warehouse'){
                    deviceDriver = '';
                    deviceSpeed = '';
                    deviceDistance = '';
                }
                var deviceDescription = '';
                if(device.description != "") {
                    //deviceDescription = 'Description : ' + device.description+'<br/>';
                }
                var deviceCheckpointList = '';
                if(device.checkpointlist!=''){
                   //deviceCheckpointList = 'Checkpointlist : ' + device.checkpointlist + '<br>';
                }
                var deviceTemperature ='';
                if(device.temp_sensors > 0) {
                    deviceTemperature = temperatureString+"<br/>";
                }



                /*
                var contentString = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + '] <br>'
                + 'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : '
                + device.clastupdated + '<br>Distance : ' + device.totaldist + temperatureString;
                */

                var contentString = deviceVehicleNo+deviceDriver+deviceLocation+deviceSpeed+deviceDistance+deviceLastUpdated+deviceDescription+deviceTemperature+humidityString+deviceCheckpointList;


                var pop_data = "";
                var boxText = document.createElement("div");
                boxText.style.cssText = "";
                boxText.innerHTML = "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> " + contentString + "<br/>" + pop_data + "</div></div></div>";
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

                var vehid = device.cvehicleid;
                marker.set("id", device.cvehicleid);
                vehiclesfordel[vehid] = marker;
                markers.push(marker);
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
    jQuery.each(results, function (i, device) {
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

            var pop_data = "";
            var boxText = document.createElement("div");
            boxText.style.cssText = "";
            boxText.innerHTML = "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> " + contentString + "<br/>" + pop_data + "</div></div></div>";
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
        success: function (data) {
            var cdata = jQuery.parseJSON(data);
            if (cdata.result.length !== 0) {
                plotCheckpoints(cdata);
            }

        }
    });



}

function plotCheckpoints(cdata) {
    var results = cdata.result;

    jQuery.each(results, function (i, device) {
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

            google.maps.event.addListener(marker, 'mouseover', function () {
                infowindow.open(map, marker);
            });

            google.maps.event.addListener(marker, 'mouseout', function () {
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

// ok
function getchk() {
    var devices = "";
    vehicle_list.forEach(function (item) {
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
        success: function (data) {
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
        remove_image.onclick = function () {
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
    jQuery("#footer").hide();
    jQuery("#maptoggler").hide();
    jQuery("#sidebar").hide();
    if(vehicleid!=""){
        clearall('vehicles');
    }

    jQuery("#veh_" + vehicleid).attr("checked", true);
    vehplot(vehicleid);
}

function routehistopen(vehicleid) {
    window.open("../reports/reports.php?vehicleid=" + vehicleid, "_blank");
}

function BindVehicleSearch() {
    jQuery("#txtVehicleNo").on("keyup", function () {
        if (this.value.length > 0) {
            var query = this.value.toLowerCase();
            jQuery('.searchVehicles [id^="veh_"]').each(function (i, elem) {
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
    jQuery("#txtCheckpoint").on("keyup", function () {
        if (this.value.length > 0) {
            var query = this.value.toLowerCase();
            jQuery('.searchChkpts [id^="chk_"]').each(function (i, elem) {
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
    jQuery("#txtFence").on("keyup", function () {
        if (this.value.length > 0) {
            var query = this.value.toLowerCase();
            jQuery('.searchFences [id^="fence_"]').each(function (i, elem) {
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

