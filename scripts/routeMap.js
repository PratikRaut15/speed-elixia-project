var counter = 0;
var start;
var end;
var markers = [];
var markers2 = [];
var markers3 = [];
var allColor = ['#FE6700', '#6FABDF', '#D73F33', '#794F21', '#60AAEA'];
var directionsDisplay1;
var directionsDisplay2;
var directionsDisplay3;
var directionsService;
var styles = [
    {"featureType": "road", "elementType": "geometry.stroke", "stylers": [{"visibility": "off"}]}, {"featureType": "poi.park", "stylers": [{"visibility": "simplified"}, {"lightness": 46}]}, {"featureType": "poi", "elementType": "labels", "stylers": [{"visibility": "on"}]}, {"featureType": "road.highway", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#9e9e9f"}]}, {"featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [{"color": "#bfbfbf"}]}, {"featureType": "road.local", "elementType": "geometry.fill", "stylers": [{"color": "#e0e0e0"}]}, {"featureType": "poi.park", "stylers": [{"lightness": 38}]}, {"stylers": [{"saturation": -54}]}];
//aluto height adjust
jQuery.noConflict();
jQuery(document).ready(function () {
//map init
    initialize();
    vehplot(1);
    vehplot(2);
    vehplot(3);
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
});

function selectall(type)
{
    switch (type)
    {
        case 'vehicles':
            jQuery(".veh_all").each(function () {
                jQuery(this).prop('checked', true);
                plotRoute(this);
            });
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
            setMapMarker(null);
            directionsDisplay1.setMap(null);
            directionsDisplay2.setMap(null);
            directionsDisplay3.setMap(null);
            break;
    }
}

function initialize() {
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });
    var mumbai = new google.maps.LatLng(19.1594646, 72.8336022);
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
    start = new google.maps.LatLng(19.159514, 72.835841);
    end = new google.maps.LatLng(19.159514, 72.835841);
    createMarker(start);
    var cityCircle = new google.maps.Circle({
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: '#FF0000',
        fillOpacity: 0.05,
        map: map,
        center: start,
        radius: 1000
    });
    var cityCircle = new google.maps.Circle({
        strokeColor: '#00FF00',
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: '#00FF00',
        fillOpacity: 0.05,
        map: map,
        center: start,
        radius: 1500
    });
    var cityCircle = new google.maps.Circle({
        strokeColor: '#0000FF',
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: '#0000FF',
        fillOpacity: 0.05,
        map: map,
        center: start,
        radius: 2000
    });
    var cityCircle = new google.maps.Circle({
        strokeColor: '#000000',
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: '#000000',
        fillOpacity: 0.05,
        map: map,
        center: start,
        radius: 2500
    });
    directionsService = new google.maps.DirectionsService();
}

function vehplot(vehid) {
    plotRoute("#veh_" + vehid);
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

function createMarker(latlng) {
    var infowindow = new google.maps.InfoWindow({
        content: "Vibgyor School"
    });
    var iconBase = 'http://speed.elixiatech.com/images/';
    var marker = new google.maps.Marker({
        position: latlng,
        icon: iconBase + 'school.png',
        map: map
    });
    infowindow.open(map, marker);
}

function plotRoute(data) {
    var isChecked = jQuery(data).attr('checked');
    var vehid = jQuery(data).attr('id');
    if (isChecked) {
        var display;
        switch (vehid) {
            case "veh_1":
                var waypts = [];
                // <editor-fold defaultstate="collapsed" desc="Veh 1 bus stops - route 107">
                var stop = new google.maps.LatLng(19.177391, 72.863730)
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow0 = new google.maps.InfoWindow({
                    content: "Vasant Valley"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker0 = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker0.addListener('click', function () {
                    infowindow0.open(map, marker0);
                });
                markers.push(marker0);
                stop = new google.maps.LatLng(19.1771095, 72.8621824);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow1 = new google.maps.InfoWindow({
                    content: "Vasant Valley"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker1 = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker1.addListener('click', function () {
                    infowindow1.open(map, marker1);
                });
                markers.push(marker1);
                stop = new google.maps.LatLng(19.176773, 72.872579);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow2 = new google.maps.InfoWindow({
                    content: "Riddhi Gardens"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker2 = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker2.addListener('click', function () {
                    infowindow2.open(map, marker2);
                });
                markers.push(marker2);
                stop = new google.maps.LatLng(19.17834, 72.871863)
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow3 = new google.maps.InfoWindow({
                    content: "Valentine Apartments"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker3 = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker3.addListener('click', function () {
                    infowindow3.open(map, marker3);
                });
                markers.push(marker3);
                stop = new google.maps.LatLng(19.17491, 72.863462)
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow4 = new google.maps.InfoWindow({
                    content: "Sudama Bungalow"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker4 = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker4.addListener('click', function () {
                    infowindow4.open(map, marker4);
                });
                markers.push(marker4);
                stop = new google.maps.LatLng(19.175188, 72.864389)
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow5 = new google.maps.InfoWindow({
                    content: "Shruti Building"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker5 = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker5.addListener('click', function () {
                    infowindow5.open(map, marker5);
                });
                markers.push(marker5);
                stop = new google.maps.LatLng(19.175989, 72.863531)
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow6 = new google.maps.InfoWindow({
                    content: "Shagun Towers"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker6 = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker6.addListener('click', function () {
                    infowindow6.open(map, marker6);
                });
                markers.push(marker6);

                // </editor-fold>
                var color = allColor[Math.floor(Math.random() * allColor.length)];
                directionsDisplay1 = new google.maps.DirectionsRenderer({
                    map: map,
                    polylineOptions: {strokeColor: color},
                    routeIndex: 1,
                    suppressMarkers: true
                });
                var request = {
                    origin: start,
                    destination: end,
                    waypoints: waypts,
                    optimizeWaypoints: true,
                    travelMode: google.maps.DirectionsTravelMode.DRIVING
                };
                directionsService.route(request, function (response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay1.setDirections(response);
                        directionsDisplay1.setMap(map);
                    }
                });
                break;
            case "veh_2":
                var waypts = [];
                // <editor-fold defaultstate="collapsed" desc="Veh 2 bus stops - route 111">
                var stop = new google.maps.LatLng(19.180105, 72.839629);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow0_vehid = new google.maps.InfoWindow({
                    content: "DHEERAJ GANGA APT CHINCHOLI BUNDER"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker0_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker0_vehid.addListener('click', function () {
                    infowindow0_vehid.open(map, marker0_vehid);
                });
                markers2.push(marker0_vehid);

                stop = new google.maps.LatLng(19.180362, 72.838982);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow1_vehid = new google.maps.InfoWindow({
                    content: "DHEERAJ JAMUNA CHINCHOLI BUNDER"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker1_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker1_vehid.addListener('click', function () {
                    infowindow1_vehid.open(map, marker1_vehid);
                });
                markers2.push(marker1_vehid);

                stop = new google.maps.LatLng(19.175872, 72.838807);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow2_vehid = new google.maps.InfoWindow({
                    content: "SUNBEAM APT"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker2_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker2_vehid.addListener('click', function () {
                    infowindow2_vehid.open(map, marker2_vehid);
                });
                markers2.push(marker2_vehid);

                stop = new google.maps.LatLng(19.175872, 72.838807);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow3_vehid = new google.maps.InfoWindow({
                    content: "AHIMSA MARG"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker3_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker3_vehid.addListener('click', function () {
                    infowindow3_vehid.open(map, marker3_vehid);
                });
                markers2.push(marker3_vehid);

                stop = new google.maps.LatLng(19.176062, 72.838245);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow4_vehid = new google.maps.InfoWindow({
                    content: "USHA GARDEN"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker4_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker4_vehid.addListener('click', function () {
                    infowindow4_vehid.open(map, marker4_vehid);
                });
                markers2.push(marker4_vehid);

                stop = new google.maps.LatLng(19.176774, 72.836925);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow5_vehid = new google.maps.InfoWindow({
                    content: "LINK PLAZA"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker5_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker5_vehid.addListener('click', function () {
                    infowindow5_vehid.open(map, marker5_vehid);
                });
                markers2.push(marker5_vehid);

                stop = new google.maps.LatLng(19.175519, 72.838152);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow6_vehid = new google.maps.InfoWindow({
                    content: "BHOOMI CLASSIC"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker6_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker6_vehid.addListener('click', function () {
                    infowindow6_vehid.open(map, marker6_vehid);
                });
                markers2.push(marker6_vehid);

                stop = new google.maps.LatLng(19.168942, 72.833446);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow7_vehid = new google.maps.InfoWindow({
                    content: "BANGUR NAGAR  KRISHNA LEELA"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker7_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker7_vehid.addListener('click', function () {
                    infowindow7_vehid.open(map, marker7_vehid);
                });
                markers2.push(marker7_vehid);

                stop = new google.maps.LatLng(19.166743, 72.834507);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow8_vehid = new google.maps.InfoWindow({
                    content: "GANGOTRI SADAN"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker8_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker8_vehid.addListener('click', function () {
                    infowindow8_vehid.open(map, marker8_vehid);
                });
                markers2.push(marker8_vehid);

                stop = new google.maps.LatLng(19.169476, 72.833431);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow9_vehid = new google.maps.InfoWindow({
                    content: "KAVERI"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker9_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker9_vehid.addListener('click', function () {
                    infowindow9_vehid.open(map, marker9_vehid);
                });
                markers2.push(marker9_vehid);

                stop = new google.maps.LatLng(19.168787, 72.832809);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow10_vehid = new google.maps.InfoWindow({
                    content: "GIRIRAJ DARSHAN CHS"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker10_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker10_vehid.addListener('click', function () {
                    infowindow10_vehid.open(map, marker10_vehid);
                });
                markers2.push(marker10_vehid);

                stop = new google.maps.LatLng(19.165907, 72.830585);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow11_vehid = new google.maps.InfoWindow({
                    content: "SUVIDHI APT"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker11_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker11_vehid.addListener('click', function () {
                    infowindow11_vehid.open(map, marker11_vehid);
                });
                markers2.push(marker11_vehid);

                stop = new google.maps.LatLng(19.162747, 72.833949);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow12_vehid = new google.maps.InfoWindow({
                    content: "VASANT GALAXY"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker12_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker12_vehid.addListener('click', function () {
                    infowindow12_vehid.open(map, marker12_vehid);
                });
                markers2.push(marker12_vehid);

                stop = new google.maps.LatLng(19.162747, 72.833949);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow13_vehid = new google.maps.InfoWindow({
                    content: "VASTU TOWER"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker13_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker13_vehid.addListener('click', function () {
                    infowindow13_vehid.open(map, marker13_vehid);
                });
                markers2.push(marker13_vehid);
                // </editor-fold>
                color = allColor[Math.floor(Math.random() * allColor.length)];
                directionsDisplay2 = new google.maps.DirectionsRenderer({
                    map: map,
                    polylineOptions: {strokeColor: color},
                    routeIndex: 2,
                    suppressMarkers: true
                });
                var request = {
                    origin: start,
                    destination: end,
                    waypoints: waypts,
                    optimizeWaypoints: true,
                    travelMode: google.maps.DirectionsTravelMode.DRIVING
                };
                directionsService.route(request, function (response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay2.setDirections(response);
                        directionsDisplay2.setMap(map);
                    }
                });
                break;
            case "veh_3":
                var waypts = [];
                // <editor-fold defaultstate="collapsed" desc="Veh 3 bus stops - route 904">
                var stop = new google.maps.LatLng(19.182239, 72.836397);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow0_vehid = new google.maps.InfoWindow({
                    content: "BHOOMI CASTLE"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker0_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker0_vehid.addListener('click', function () {
                    infowindow0_vehid.open(map, marker0_vehid);
                });
                markers3.push(marker0_vehid);

                stop = new google.maps.LatLng(19.193649, 72.831318);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow1_vehid = new google.maps.InfoWindow({
                    content: "ABROL VASTU PARK"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker1_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker1_vehid.addListener('click', function () {
                    infowindow1_vehid.open(map, marker1_vehid);
                });
                markers3.push(marker1_vehid);

                stop = new google.maps.LatLng(19.189213, 72.835153);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow2_vehid = new google.maps.InfoWindow({
                    content: "ABROL HOUSE"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker2_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker2_vehid.addListener('click', function () {
                    infowindow2_vehid.open(map, marker2_vehid);
                });
                markers3.push(marker2_vehid);

                stop = new google.maps.LatLng(19.19029, 72.834318);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow3_vehid = new google.maps.InfoWindow({
                    content: "KHANDELWAL LAYOUT"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker3_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker3_vehid.addListener('click', function () {
                    infowindow3_vehid.open(map, marker3_vehid);
                });
                markers3.push(marker3_vehid);

                stop = new google.maps.LatLng(19.190695, 72.832589);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow4_vehid = new google.maps.InfoWindow({
                    content: "EVERSHINE NAGAR"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker4_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker4_vehid.addListener('click', function () {
                    infowindow4_vehid.open(map, marker4_vehid);
                });
                markers3.push(marker4_vehid);

                stop = new google.maps.LatLng(19.19382, 72.830062);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow5_vehid = new google.maps.InfoWindow({
                    content: "ABROL VASTU PARK"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker5_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker5_vehid.addListener('click', function () {
                    infowindow5_vehid.open(map, marker5_vehid);
                });
                markers3.push(marker5_vehid);

                stop = new google.maps.LatLng(19.193799, 72.830094);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow6_vehid = new google.maps.InfoWindow({
                    content: "VASTU TOWER"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker6_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker6_vehid.addListener('click', function () {
                    infowindow6_vehid.open(map, marker6_vehid);
                });
                markers3.push(marker6_vehid);

                stop = new google.maps.LatLng(19.19453, 72.826601);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow7_vehid = new google.maps.InfoWindow({
                    content: "MAANAVSTHAL NEW MAHAKALI RD"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker7_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker7_vehid.addListener('click', function () {
                    infowindow7_vehid.open(map, marker7_vehid);
                });
                markers3.push(marker7_vehid);

                stop = new google.maps.LatLng(19.196828, 72.824638);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow8_vehid = new google.maps.InfoWindow({
                    content: "ASMITA JYOTI MARVE ROAD"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker8_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker8_vehid.addListener('click', function () {
                    infowindow8_vehid.open(map, marker8_vehid);
                });
                markers3.push(marker8_vehid);

                stop = new google.maps.LatLng(19.201123, 72.81697);
                waypts.push({
                    location: stop,
                    stopover: true
                });
                var infowindow9_vehid = new google.maps.InfoWindow({
                    content: "JANKALYAN NAGAR"
                });
                var iconBase = 'http://speed.elixiatech.com/images/';
                var marker9_vehid = new google.maps.Marker({
                    position: stop,
                    icon: iconBase + 'pickup_point.png',
                    map: map
                });
                marker9_vehid.addListener('click', function () {
                    infowindow9_vehid.open(map, marker9_vehid);
                });
                markers3.push(marker9_vehid);

                // </editor-fold>
                color = allColor[Math.floor(Math.random() * allColor.length)];
                directionsDisplay3 = new google.maps.DirectionsRenderer({
                    map: map,
                    polylineOptions: {strokeColor: color},
                    routeIndex: 3,
                    suppressMarkers: true
                });
                var request = {
                    origin: start,
                    destination: end,
                    waypoints: waypts,
                    optimizeWaypoints: true,
                    travelMode: google.maps.DirectionsTravelMode.DRIVING
                };
                directionsService.route(request, function (response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay3.setDirections(response);
                        directionsDisplay3.setMap(map);
                    }
                });
                break;
        }

    }
    else {
        switch (vehid) {
            case "veh_1":
                display = directionsDisplay1;
                setMapMarker(null, 1);
                break;
            case "veh_2":
                display = directionsDisplay2;
                setMapMarker(null, 2);
                break;
            case "veh_3":
                display = directionsDisplay3;
                setMapMarker(null, 3);
                break;
        }
        display.setMap(null);
    }
}

// Sets the map on all markers in the array.
function setMapMarker(map, vehid) {
    switch (vehid) {
        case 1:
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
            break;
        case 2:
            for (var i = 0; i < markers2.length; i++) {
                markers2[i].setMap(map);
            }
            break;
        case 3:
            for (var i = 0; i < markers3.length; i++) {
                markers3[i].setMap(map);
            }
            break;
        default:
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
            for (var i = 0; i < markers2.length; i++) {
                markers2[i].setMap(map);
            }
            for (var i = 0; i < markers3.length; i++) {
                markers3[i].setMap(map);
            }
    }

}
