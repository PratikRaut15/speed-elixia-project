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
var styles = [{
    "featureType": "road",
    "elementType": "geometry.stroke",
    "stylers": [{
        "visibility": "off"
    }]
}, {
    "featureType": "poi.park",
    "stylers": [{
        "visibility": "simplified"
    }, {
        "lightness": 46
    }]
}, {
    "featureType": "poi",
    "elementType": "labels",
    "stylers": [{
        "visibility": "on"
    }]
}, {
    "featureType": "road.highway",
    "elementType": "labels",
    "stylers": [{
        "visibility": "off"
    }]
}, {
    "featureType": "road.highway",
    "elementType": "geometry.fill",
    "stylers": [{
        "color": "#9e9e9f"
    }]
}, {
    "featureType": "road.arterial",
    "elementType": "geometry.fill",
    "stylers": [{
        "color": "#bfbfbf"
    }]
}, {
    "featureType": "road.local",
    "elementType": "geometry.fill",
    "stylers": [{
        "color": "#e0e0e0"
    }]
}, {
    "featureType": "poi.park",
    "stylers": [{
        "lightness": 38
    }]
}, {
    "stylers": [{
        "saturation": -54
    }]
}];
//aluto height adjust
jQuery.noConflict();
// Replace with your own API key
var API_KEY = 'AIzaSyCIdi3tTTXB0Gtkj0pKEdQbijxZNJF2psU';
// Icons for markers
var RED_MARKER = 'https://maps.google.com/mapfiles/ms/icons/red-dot.png';
var GREEN_MARKER = 'https://maps.google.com/mapfiles/ms/icons/green-dot.png';
var BLUE_MARKER = 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png';
var YELLOW_MARKER = 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
var LINE_MARKER = 'http://localhost/speed/images/truck.png';
// URL for places requests
var PLACES_URL = 'https://maps.googleapis.com/maps/api/place/details/json?key=AIzaSyCIdi3tTTXB0Gtkj0pKEdQbijxZNJF2psU&placeid=';
// URL for Speed limits
var SPEED_LIMIT_URL = 'https://roads.googleapis.com/v1/speedLimits?&key=AIzaSyCIdi3tTTXB0Gtkj0pKEdQbijxZNJF2psU';
var coords;
/**
 * Current Roads API threshold (subject to change without notice)
 * @const {number}
 */
var DISTANCE_THRESHOLD_HIGH = 300;
var DISTANCE_THRESHOLD_LOW = 200;
/**
 * @type Array<ExtendedLatLng>
 */
var originals = []; // the original input points, a list of ExtendedLatLng
var interpolate = true;
var map;
var placesService;
var originalCoordsLength;
// Settingup Arrays
var infoWindows = [];
var markers = [];
var placeIds = [];
var polylines = [];
var snappedCoordinates = [];
var distPolylines = [];
// Symbol that gets animated along the polyline
var lineSymbol = {
    path: car,
    scale: 0.4,
    strokeColor: '#005db5',
    strokeWidth: '#005db5',
    fillOpacity: 1,
};
//lineSymbol.setIcon(BLUE_MARKER);
// Example 1 - Frolick around Sydney
var eg1 = '19.08111,72.89824|19.08291,72.8973|19.08262,72.89625|19.08098,72.89622|19.08055,72.89635|19.08011,72.89546|19.07848,72.89168|19.07271,72.88435|19.07362,72.88303|19.07374,72.88194|19.07386,72.88126|19.07372,72.88137|' + '19.07352,72.88295|19.07184,72.88509|19.06807,72.8883|19.0666,72.88978|19.06585,72.89177|19.06463,72.89628|19.0648,72.89774|19.06863,72.90377|19.07078,72.90709|19.07058,72.9075|19.06985,72.90886|19.06877,72.90965|' + '19.06706,72.90997|19.06555,72.91049|19.06393,72.91239|19.06315,72.91527|19.06222,72.91775|19.05923,72.92396|19.05666,72.92907|19.05633,72.93162|19.05604,72.93269|19.05499,72.93423|19.0518,72.93671|19.05135,72.93691|' + '19.05212,72.93902|19.05283,72.94099|19.05837,72.95654|19.06389,72.97928|19.06476,72.98118|19.06586,72.98261|19.0685,72.98528|19.06956,72.98709|19.06882,72.99599|19.06701,73.01944|19.06663,73.01999|19.06506,73.01986|' + '19.06395,73.01937|19.06077,73.01924|19.05327,73.01956|19.05206,73.0202|19.0446,73.02673|19.04257,73.02837|19.03845,73.029|19.03056,73.03015|19.02985,73.03084|19.03002,73.03238|19.0298,73.03358|19.02661,73.03701|' + '19.02426,73.03996|19.02362,73.04132|19.02271,73.04678|19.02243,73.05038|19.02414,73.05404|19.02862,73.0606|19.03587,73.07125|19.03806,73.07441|19.03852,73.07581|19.03844,73.07676|19.03765,73.07843|19.03458,73.08254|' + '19.02883,73.09119|19.01844,73.10601|19.01603,73.11135|19.01176,73.11851|19.00491,73.12565|19.00042,73.13014|18.99741,73.13181|18.99214,73.13784|18.98416,73.14519|18.96883,73.15894|18.96763,73.15952|18.966,73.15974|' + '18.96342,73.15901|18.95765,73.15685|18.95667,73.1565|18.95288,73.15507|18.95021,73.15477|18.93584,73.15871|18.93297,73.15987|18.93022,73.16181|18.92467,73.16678|18.91654,73.17572|18.91095,73.18258|18.90892,73.18617|' + '18.90099,73.20064|18.89961,73.20218|18.89744,73.20326|18.89406,73.20449|18.88417,73.21015|18.86389,73.22169|18.85074,73.22917|18.84815,73.23075|18.84504,73.23457|18.83814,73.24353|18.83384,73.2494|18.83099,73.25358|' + '18.82734,73.26185|18.82586,73.26345|18.82175,73.26629|18.82014,73.26793|18.81355,73.27752|18.80846,73.28083|18.80187,73.285|18.79648,73.28837|18.7944,73.29072|18.78881,73.30664|18.78803,73.30861|18.78661,73.31062|' + '18.78534,73.31167|18.78171,73.31366|18.78067,73.31444|18.77933,73.31615|18.77717,73.31964|18.77577,73.32067|18.77393,73.32162|18.77247,73.32353|18.77238,73.32577|18.77305,73.32869|18.7706,73.33689|18.76933,73.33878|' + '18.76832,73.33984|18.76841,73.34036|18.77043,73.34254|18.77087,73.34312|18.77091,73.34396|18.7704,73.34465|18.76932,73.34495|18.76659,73.34521|18.76495,73.34603|18.76424,73.34662|18.76299,73.3475|18.76285,73.34798|' + '18.76352,73.34915|18.76398,73.34997|18.76478,73.35053|18.76574,73.35123|18.76562,73.35173|18.765,73.35214|18.76477,73.35312|18.76805,73.35613|18.76885,73.3575|18.77172,73.35937|18.77202,73.35986|18.77187,73.36044|' + '18.77124,73.3607|18.76833,73.35918|18.76672,73.3578|18.76396,73.35391|18.76324,73.35372|18.76265,73.35428|18.76292,73.35522|18.76375,73.35562|18.76503,73.35611|18.76548,73.35664|18.76622,73.35871|18.76684,73.35967|' + '18.76702,73.36055|18.76908,73.3621|18.77019,73.3627|18.77122,73.3641|18.77115,73.36616|18.76995,73.36753|18.76913,73.36794|18.76625,73.36949|18.76337,73.37081|18.75873,73.37206|18.75822,73.37258|18.75876,73.37403|' + '18.75862,73.37529|18.75767,73.37756|18.75978,73.37992|18.76047,73.38147|18.76176,73.38513|18.76512,73.38773|18.76725,73.38896|18.76819,73.3901|18.76881,73.39212|18.76941,73.39716|18.76934,73.40316|18.76926,73.40467|' + '18.76856,73.40723|18.76669,73.41101|18.76399,73.41603|18.76301,73.41848|18.76139,73.42233|18.76067,73.42597|18.76013,73.42829|18.75946,73.42896|18.7581,73.4292|18.75529,73.42919|18.75367,73.42958|18.75034,73.43091|' + '18.74797,73.43092|18.73988,73.42929|18.73903,73.42914|18.73694,73.42966|18.73548,73.43134|18.73524,73.43424|18.73526,73.43771|18.73482,73.43934|18.73382,73.44222|18.73342,73.44449|18.73387,73.44708|18.73999,73.46507|' + '18.74143,73.46995|18.74274,73.47723|18.74512,73.49057|18.74837,73.50769|18.75055,73.51566|18.75151,73.51952|18.75159,73.52177|18.75122,73.52406|18.74967,73.52754|18.74785,73.52966|18.74447,73.53165|18.74212,73.53227|' + '18.73782,73.53262|18.73538,73.53342|18.7323,73.53572|18.73175,73.53642|18.72707,73.54278|18.72441,73.5475|18.71737,73.55693|18.71131,73.56526|18.70952,73.57209|18.70557,73.59121|18.70537,73.5938|18.70566,73.59566|' + '18.70907,73.60739|18.71013,73.6126|18.71096,73.61506|18.71329,73.62168|18.71426,73.62589|18.71671,73.63681|18.71672,73.63892|18.71613,73.64106|18.70977,73.65541|18.70453,73.66776|18.70466,73.67265|18.70434,73.67465|' + '18.70364,73.67625|18.7026,73.67763|18.70109,73.6788|18.69148,73.6838|18.68997,73.68496|18.68622,73.68935|18.68155,73.69471|18.67231,73.70294|18.66433,73.71023|18.66348,73.71203|18.66323,73.71353|18.66306,73.72354|' + '18.66242,73.73011|18.6607,73.73054|18.65206,73.73389|18.64189,73.73795|18.6169,73.74796|18.56823,73.76618|18.55183,73.77205|18.5459,73.77417|18.5443,73.77514|18.54168,73.77829|18.53971,73.78075|18.53845,73.78129|' + '18.53699,73.78119|18.53382,73.78029|18.52989,73.77893|18.52843,73.77799|18.52816,73.77792|18.52759,73.77868|18.52659,73.78022|18.52605,73.78108|18.52577,73.78155|18.52612,73.78192|18.52679,73.78263|18.52708,73.78305|' + '18.52732,73.78367|18.52738,73.78406|18.52723,73.78474|18.52657,73.78659|18.52608,73.78746|18.526,73.788|18.52519,73.78784|18.52464,73.7878|18.52401,73.78809|18.5237,73.78885|18.5236,73.79013|18.5242,73.79099|' + '18.52529,73.79184';
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
        } else alert("Please check your address details or contact an Elixir about the issue : " + status);
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
                var image = new google.maps.MarkerImage(device.image, new google.maps.Size(48, 48), new google.maps.Point(0, 0), new google.maps.Point(8, 20));
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
                var deviceVehicleNo = 'Vehicle No : ' + device.cname + '<br>';
                var deviceDriver = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + '] <br>';
                var deviceLocation = 'Location : ' + device.clocation + ' <br>';
                var deviceSpeed = 'Current Speed : ' + device.cspeed + ' km/hr<br>';
                var deviceLastUpdated = 'Last Updated : ' + device.clastupdated + '';
                var deviceDistance = 'Distance : ' + device.totaldist + ' Km<br/>';
                var humidityString = '';
                var deviceDescription = '';
                var deviceCheckpointList = '';
                var deviceTemperature = '';
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

                if (device.use_humidity == 1) {
                    var humidity;
                    humidity = device.humidity;
                    humidityString += '<br> Humidity : ' + humidity;
                }

                if (device.portable == 1) {
                    deviceSpeed = '';
                    deviceDistance = '';
                }

                if (device.description != "") {
                    //deviceDescription = 'Description : ' + device.description+'<br/>';
                }

                if (device.checkpointlist != '') {
                    //deviceCheckpointList = 'Checkpointlist : ' + device.checkpointlist + '<br>';
                }

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
                    onclicktog(device.cvehicleid);
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
function remove(arr, item) {
    for (var i = arr.length; i >= 0; i--) {
        if (arr[i] === item) {
            arr.splice(i, 1);
        }
    }
}
function initmap(lat, lng) {
    if (gmapsinited) return;
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
                var image = new google.maps.MarkerImage(device.image, new google.maps.Size(48, 48), new google.maps.Point(0, 0), new google.maps.Point(8, 20));
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
                var deviceVehicleNo = 'Vehicle No : ' + device.cname + '<br>';
                var deviceDriver = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + '] <br>';
                var deviceLocation = 'Location : ' + device.clocation + ' <br>';
                var deviceSpeed = 'Current Speed : ' + device.cspeed + ' km/hr<br>';
                var deviceLastUpdated = 'Last Updated : ' + device.clastupdated + '';
                var deviceDistance = 'Distance : ' + device.totaldist + ' Km<br/>';
                var humidityString = '';
                var deviceDescription = '';
                var deviceCheckpointList = '';
                var deviceTemperature = '';
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

                if (device.use_humidity == 1) {
                    var humidity;
                    humidity = device.humidity;
                    humidityString += '<br> Humidity : ' + humidity;
                }

                if (device.portable == 1) {
                    deviceSpeed = '';
                    deviceDistance = '';
                }

                if (device.description != "") {
                    //deviceDescription = 'Description : ' + device.description+'<br/>';
                }

                if (device.checkpointlist != '') {
                    //deviceCheckpointList = 'Checkpointlist : ' + device.checkpointlist + '<br>';
                }

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
                    onclicktog(device.cvehicleid);
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
                var image = new google.maps.MarkerImage(device.image, new google.maps.Size(48, 48), new google.maps.Point(0, 0), new google.maps.Point(8, 20));
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
                    onclicktog(device.cvehicleid);
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
            var image = new google.maps.MarkerImage(device.image, new google.maps.Size(48, 48), new google.maps.Point(0, 0), new google.maps.Point(8, 20));
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
            var contentString = '<h3>' + device.cname + '</h3><hr><p>' + 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + ']<br>' + 'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : ' + device.clastupdated + '<hr><a href=../history/history.php?id=5&vid=' + device.cvehicleid + '><u>Vehicle History</u> </a>';
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
                onclicktog(device.cvehicleid);
            });
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
            data: {
                action: 'fetchCheckPoints',
                checkPointTypeId: checkPointTypeId
            },
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
            var vehId = v.id.replace("veh_", '');
            vehplot(vehId);
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
    geocoder.geocode({
        'address': address
    }, function(results, status) {
        if (results.length) map1.fitBounds(results[0].geometry.viewport);
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
            disp.setOptions({
                suppressMarkers: true,
                suppressPolylines: true
            });
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
    if (timerHandle[index]) clearTimeout(timerHandle[index]);
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
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
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
        if (((this.getPath().getAt(i).lat() < y) && (this.getPath().getAt(j).lat() >= y)) || ((this.getPath().getAt(j).lat() < y) && (this.getPath().getAt(i).lat() >= y))) {
            if (this.getPath().getAt(i).lng() + (y - this.getPath().getAt(i).lat()) / (this.getPath().getAt(j).lat() - this.getPath().getAt(i).lat()) * (this.getPath().getAt(j).lng() - this.getPath().getAt(i).lng()) < x) {
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
    if (metres == 0) return this.getPath().getAt(0);
    if (metres < 0) return null;
    if (this.getPath().getLength() < 2) return null;
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
    if (metres <= 0) return points;
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
    if (metres == 0) return this.getPath().getAt(0);
    if (metres < 0) return null;
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
    if (angle < 0.0) angle += Math.PI * 2.0;
    angle = angle * 180.0 / Math.PI;
    return parseFloat(angle.toFixed(1));
}
function onclicktog(vehicleId) {
    jQuery('#sidebar').toggle('fast');
    if (counter % 2 === 0) {
        jQuery('#pre').css("display", "block");
        jQuery('#next').css("display", "none");
        jQuery('#maptoggler').css("left", "350px");
        counter++;
        radarMap(vehicleId);
    } else {
        jQuery('#next').css("display", "block");
        jQuery('#pre').css("display", "none");
        jQuery('#maptoggler').css("left", "0px");
        counter++;
    }
}
function radarMap(vehicleId) {
    var urldata = "route_ajax.php?radar=1&vehicleId=" + vehicleId;
    jQuery.ajax({
        type: "POST",
        url: urldata,
        async: true,
        cache: false,
        success: function(data) {
            var veh = jQuery.parseJSON(data);
            //console.log(veh.vehicleNo);
            jQuery('#sidebar').css("display", "block");
            jQuery('#maptoggler').css("display", "block");
            jQuery('#sidepane').css("display", "block");
            jQuery('.scrollheader').html("Vehicle No : " + veh.vehicleNo);
            jQuery('.scrollgroup').html("Group : " + veh.group);
            jQuery('.vehFrom').html(veh.from);
            jQuery('.vehTo').html(veh.to);
            jQuery('.schFrom').html("Scheduled : " + veh.scheduleFrom);
            jQuery('.schTo').html("Scheduled : " + veh.scheduleTo);
            jQuery('.actFrom').html("Actual : " + veh.actualFrom);
            jQuery('.actTo').html("Estimated : " + veh.actualTo);
            jQuery('.rangetxt').html('<input style="width: 300px;" id="ageInputId" type="range" min="0" max="' + veh.totalDistance + '" value="' + veh.travelledDistance + '"><output name="ageOutputName" id="ageOutputId">' + veh.travelledDistance + ' km (' + veh.totalDistance + ' km )</output>');
            jQuery('.rtdLoc').html("Location : " + veh.realtimeLocation);
            jQuery('.curspeed').html("Speed : " + veh.speed);
            getRoutePloting();
        }
    });
}
// Centre the map on Sydney
var mapOptions = {
    //19.0767252,72.9106087
    center: {
        'lat': 19.0767252,
        'lng': 72.9106087
    },
    zoom: 14
};
// Map object
map = new google.maps.Map(document.getElementById('map'), mapOptions);
// Places object
placesService = new google.maps.places.PlacesService(map);
// Reset the map to a clean state and reset all variables
// used for displaying each request
function clearMap() {
    // Clear the polyline
    for (var i = 0; i < polylines.length; i++) {
        polylines[i].setMap(null);
    }
    // Clear all markers
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    // Clear all the distance polylines
    for (var i = 0; i < distPolylines.length; i++) {
        distPolylines[i].setMap(null);
    }
    // Clear all info windows
    for (var i = 0; i < infoWindows.length; i++) {
        infoWindows[i].close();
    }
    // Empty everything
    polylines = [];
    markers = [];
    distPolylines = [];
    snappedCoordinates = [];
    placeIds = [];
    infoWindows = [];
    //originals = [];
    $('#unsnappedPoints').empty();
    $('#warningMessage').empty();
}
// Parse the value in the input element
// to get all coordinates
function parseCoordsFromQuery(input) {
    //var coords;
    input = input;
    if (input.split('path=').length > 1) {
        input = decodeURIComponent(input);
        // Split on the ampersand to get all params
        var parts = input.split('&');
        // Check each part to see if it starts with 'path='
        // grabbing out the coordinates if it does
        for (var i = 0; i < parts.length; i++) {
            if (parts[i].split('path=').length > 1) {
                coords = parts[i].split('path=')[1];
                break;
            }
        }
    } else {
        coords = input;
    }
    // Parse the "Lat,Lng|..." coordinates into an array of ExtendedLatLng
    originals = [];
    var points = coords.split('|');
    for (var i = 0; i < points.length; i++) {
        var point = points[i].split(',');
        originals.push({
            lat: Number(point[0]),
            lng: Number(point[1]),
            index: i
        });
    }
    //return coords;
}
// Clear the map of any old data and plot the request
$('#plot').click(function(e) {
    clearMap();
    var ecoords = Array();
    getRoutePloting();
    //setTimeout(function(){ alert("Hello"); }, 3000);
    //console.log(ecoords);
    e.preventDefault();
});
function getRoutePloting() {
    var allLatLongs = Array();
    var pointsData = '';
    var cords = '';
    $.ajax({
        type: 'GET',
        url: 'http://localhost/speed/modules/map/direction.php',
        data: {
            origin: '19.0813075,72.898741',
            destination: '28.6468935,76.9531796',
            key: API_KEY
        },
        success: function(gdata) {
            //console.log(gdata);
            //console.log(decodelat(gdata));
            allLatLongs = (decodelat(gdata));
            console.log(allLatLongs.length);
            //chunk = allLatLongs.splice(0,100);
            var strlatString = '';
            var arrDetails = Array();
            while (allLatLongs.length > 0) {
                chunk = allLatLongs.splice(0, 100);
                //console.log(chunk)
                for (var j = 0; j < chunk.length; j++) {
                    //console.log(allLatLongs[j]);
                    //var strlat = allLatLongs[j].latitude+','+allLatLongs[j].longitude;
                    //console.log(strlat);
                    if (j == 0) {
                        var strlat = chunk[j].latitude + ',' + chunk[j].longitude;
                    } else {
                        var strlat = '|' + chunk[j].latitude + ',' + chunk[j].longitude;
                    }
                    pointsData += strlat;
                    strlatString += strlat;
                    if (j == chunk.length - 1) {
                        var rowData = getSnapToRoad(pointsData);
                        //console.log(rowData);
                        arrDetails = arrDetails.concat(rowData.snappedPoints);
                        // arrDetails.push(rowData);
                        //drawDistance();
                        pointsData = '';
                    }
                }
            }
            //console.log(arrDetails);
            plotSnapToRoad(arrDetails, strlatString);
        },
        error: function() {
            $('#requestURL').html('<strong>That query didn\'t work :(</strong>' + '<p>Try looking at the <a href="' + this.url + '">Request URL</a></p>');
            //clearMap();
        }
    });
    return pointsData;
}
function plotSnapToRoad(data, strlatString) {
    parseCoordsFromQuery(strlatString);
    processSnapToRoadResponse(data, strlatString);
    drawSnappedPolyline(snappedCoordinates);
    //drawOriginals(originals);
    fitBounds(markers);
}
// Make AJAX request to the snapToRoadsAPI
// with coordinates parsed from text input element.
function bendAndSnap(coords) {
    console.log(coords);
    //var ecoords = Array();
    //console.log(ecoords);
    //console.log('demo');
    //parseCoordsFromQuery(coords);
    //console.log(ecoords);
    $.ajax({
        type: 'GET',
        url: 'https://roads.googleapis.com/v1/snapToRoads',
        data: {
            //interpolate: $('#interpolate').is(':checked'),
            path: coords,
            key: API_KEY
        },
        success: function(data) {
            $('#requestURL').html('<a target="blank" href="' + this.url + '">Request URL</a>');
            processSnapToRoadResponse(data);
            //drawSnappedPolyline(snappedCoordinates);
            //drawOriginals(originals);
            fitBounds(markers);
        },
        error: function() {
            $('#requestURL').html('<strong>That query didn\'t work :(</strong>' + '<p>Try looking at the <a href="' + this.url + '">Request URL</a></p>');
            clearMap();
        }
    });
}
function getSnapToRoad(coords) {
    var arrData = Array();
    //location.hash = coords;
    $.ajax({
        type: 'GET',
        url: 'https://roads.googleapis.com/v1/snapToRoads',
        async: false,
        data: {
            //interpolate: $('#interpolate').is(':checked'),
            path: coords,
            key: API_KEY
        },
        success: function(data) {
            arrData = data;
            //console.log(arrData);
        },
        error: function() {
            alert('Snap To Road Fail');
        }
    });
    return arrData;
}
// Toggle the distance polylines of the original points to show on the maps
$('#distance').click(function(e) {
    for (var i = 0; i < distPolylines.length; i++) {
        distPolylines[i].setVisible(!distPolylines[i].getVisible());
    }
    // Clear all infoWindows associated with distance polygons on toggle
    for (var i = 0; i < infoWindows.length; i++) {
        if (infoWindows[i].dist) {
            infoWindows[i].close();
        }
    }
    e.preventDefault();
});
/**
 * Compute the distance between each original point and create a polyline
 * for each pair. Polylines are initially hidden on creation
 */
function drawDistance() {
    for (var i = 0; i < originals.length - 1; i++) {
        var origin = new google.maps.LatLng(originals[i]);
        var destination = new google.maps.LatLng(originals[i + 1]);
        var distance = google.maps.geometry.spherical.computeDistanceBetween(origin, destination);
        // Round the distance value to two decimal places
        distance = Math.round(distance * 100) / 100;
        var color;
        var weight;
        if (distance > DISTANCE_THRESHOLD_HIGH) {
            color = '#CC0022';
            weight = 7;
        } else if (distance < DISTANCE_THRESHOLD_HIGH && distance > DISTANCE_THRESHOLD_LOW) {
            color = '#FF6600';
            weight = 6;
        } else {
            color = '#22CC00';
            weight = 5;
        }
        var polyline = new google.maps.Polyline({
            strokeColor: color,
            strokeOpacity: 0.4,
            strokeWeight: weight,
            geodesic: true,
            visible: false,
            map: map
        });
        polyline.setPath([origin, destination]);
        distPolylines.push(polyline);
        infoWindows.push(addPolyWindow(polyline, distance, i));
    }
}
/**
 * Add an info window to the polyline displaying the original
 * points and the distance
 */
function addPolyWindow(polyline, distance, index) {
    var infoWindow = new google.maps.InfoWindow();
    var content = '<div style="width:100%"><p>' + '<strong>Original Index: </strong>' + index + '<br>' + '<strong>Coords:</strong> (' + originals[index].lat + ',' + originals[index].lng + ')' + '<br>to<br>' + '<strong>Original Index: </strong>' + (index + 1) + '<br>' + '<strong>Coords:</strong> (' + originals[index + 1].lat + ',' + originals[index + 1].lng + ')<br><br>' + '<strong>Distance:  </strong>' + distance + ' m<br>';
    if (distance > DISTANCE_THRESHOLD_HIGH) {
        content += '<span style="color:#CC0022;font-style:italic">' + '*Large distance (>300m) may affect snapping</span><br>' + 'Please see <a href="https://developers.google.com/maps/' + 'documentation/roads/snap#parameter_usage" ' + 'target="_blank">Roads API documentation</a>';
    }
    content += '</p></div>';
    infoWindow.setContent(content);
    infoWindow.dist = true;
    polyline.addListener('click', function(e) {
        infoWindow.setPosition(e.latLng);
        infoWindow.open(map);
    });
    polyline.addListener('mouseover', function(e) {
        polyline.setOptions({
            strokeOpacity: 1.0
        });
    });
    polyline.addListener('mouseout', function(e) {
        polyline.setOptions({
            strokeOpacity: 0.4
        });
    });
    return infoWindow;
}
// Parse the value in the input element
// to get all coordinates
function getMissingPoints(originalIndexes, originalCoordsLength) {
    var unsnappedPoints = [];
    var coordsArray = coords.split('|');
    var hasMissingCoords = false;
    for (var i = 0; i < originalCoordsLength; i++) {
        if (originalIndexes.indexOf(i) < 0) {
            hasMissingCoords = true;
            var latlng = {
                'lat': parseFloat(coordsArray[i].split(',')[0]),
                'lng': parseFloat(coordsArray[i].split(',')[1])
            };
            unsnappedPoints.push(latlng);
            latlng.unsnapped = true;
        }
    }
    return unsnappedPoints;
}
// Parse response from snapToRoads API request
// Store all coordinates in response
// Calls functions to add markers to map for unsnapped coordinates
function processSnapToRoadResponse(data, strlatString) {
    var originalIndexes = [];
    var unsnappedMessage = '';
    //var originals = [];
    for (var i = 0; i < data.length; i++) {
        var latlng = {
            'lat': data[i].location.latitude,
            'lng': data[i].location.longitude
        };
        var interpolated = true;
        if (data[i].originalIndex != undefined) {
            interpolated = false;
            originalIndexes.push(data[i].originalIndex);
            latlng.originalIndex = data[i].originalIndex;
        }
        latlng.interpolated = interpolated;
        snappedCoordinates.push(latlng);
        placeIds.push(data[i].placeId);
        // Cross-reference the original point and this snapped point.
        latlng.related = originals[latlng.originalIndex];
        //console.log(originals);
        originals[latlng.originalIndex].related = latlng;
    }
    var unsnappedPoints = getMissingPoints(originalIndexes, strlatString.split('|').length);
}
// Draw the polyline for the snapToRoads API response
// Call functions to add markers and infowindows for each snapped
// point along the polyline.
function drawSnappedPolyline(snappedCoords) {
    var snappedPolyline = new google.maps.Polyline({
        path: snappedCoords,
        strokeColor: '#005db5',
        strokeWeight: 4,
        icons: [{
            icon: lineSymbol,
            offset: '100%'
        }]
    });
    snappedPolyline.setMap(map);
    animateCircle(snappedPolyline);
    polylines.push(snappedPolyline);
    for (var i = 0; i < snappedCoords.length; i++) {
        var marker = addMarker(snappedCoords[i]);
        //var infoWindow = addDetailedInfoWindow(marker, snappedCoords[i], placeIds[i]);
        //infoWindows.push(infoWindow);
    }
}
// Draw the original input.
// Call functions to add markers and infowindows for each point.
function drawOriginals(originalCoords) {
    for (var i = 0; i < originalCoords.length; i++) {
        var marker = addMarker(originalCoords[i]);
        var infoWindow = addBasicInfoWindow(marker, originalCoords[i], i);
        infoWindows.push(infoWindow);
    }
}
// Infowindow used for unsnappable coordinates
function addBasicInfoWindow(marker, coords, index) {
    var infowindow = new google.maps.InfoWindow();
    var content = '<div style="width:99%"><p>' + '<strong>Lat/Lng:</strong><br>' + '(' + coords.lat + ',' + coords.lng + ')<br>' + (index != undefined ? '<strong>Index: </strong>' + index : '') + '</p></div>';
    infowindow.setContent(content);
    google.maps.event.addListener(marker, 'click', function() {
        openInfoWindow(infowindow, marker);
    });
    return infowindow;
}
// Infowindow used for snapped points
// Makes request to Places Details API to get data about each
// Place ID.
// Requests speed limit of each location using Roads SpeedLimit API
function addDetailedInfoWindow(marker, coords, placeId) {
    var infowindow = new google.maps.InfoWindow();
    var placesRequestUrl = PLACES_URL + placeId;
    var detailsUrl = '<a target="_blank" href="' + placesRequestUrl + '">' + placeId + '</a></li>';
    // On click we make a request to the Places API
    // This is to avoid OVER_QUERY_LIMIT if we just requested everything
    // at the same time
    google.maps.event.addListener(marker, 'click', function() {
        content = '<div style="width:99%"><p>';
        function finishInfoWindow(placeDetails) {
            content += '<strong>Place Details: </strong>' + placeDetails + '<br>' + '<strong>' + (coords.interpolated ? 'Coords' : 'Snapped coords') + ': </strong>' + '(' + coords.lat.toFixed(5) + ',' + coords.lng.toFixed(5) + ')<br>';
            if (!(coords.interpolated)) {
                var original = originals[coords.originalIndex];
                content += '<strong>Original coords: </strong>' + '(' + original.lat + ',' + original.lng + ')<br>' + '<strong>Original Index: </strong>' + coords.originalIndex;
            }
            content += '</p></div>';
            infowindow.setContent(content);
            openInfoWindow(infowindow, marker);
        };
        getPlaceDetails(placeId, function(place) {
            if (place.name) {
                content += '<strong>' + place.name + '</strong><br>';
            }
            getSpeedLimit(placeId, function(data) {
                if (data.speedLimits) {
                    content += '<strong>Speed Limit: </strong>' + data.speedLimits[0].speedLimit + ' km/h <br>';
                }
                finishInfoWindow(detailsUrl);
            });
        }, function() {
            finishInfoWindow("<em>None available</em>");
        });
    });
    return infowindow;
}
// Avoid infoWindows staying open if the pano changes
listenForPanoChange();
// If the user came to the page with a particular path or URL,
// immediately plot it.
// End init function
// Call the initialize function once everything has loaded
google.maps.event.addDomListener(window, 'load', initialize);
// Load the control panel in a floating div if it is not loaded in an iframe
// after the textarea has been rendered
jQuery("#coords").ready(function() {
    if (!window.frameElement) {
        $('#panel').addClass("floating panel");
        $('#button-div').addClass("button-div");
        $('#coords').removeClass("coords-large").addClass("coords-small");
        $('#toggle').show();
        $('#map').height('100%');
    }
});
/**
 *  latlng literal with extra properties to use with the RoadsAPI
 *  @typedef {Object} ExtendedLatLng
 *   lat:string|float
 *   lng:string|float
 *   interpolated:boolean
 *   unsnapped:boolean
 */
/**
 * Add a line to the map for highlighting the connection between two
 * markers while the mouse is over it.
 * @param {ExtendedLatLng} from - The origin of the line
 * @param {ExtendedLatLng} to - The destination of the line
 * @return {!Object} line - the polyline object created
 */
function addOverline(from, to) {
    return addLine("overline", from, to, '#ff77ff', 4, 1.0, 2.0, false);
}
/**
 * Add a line to the map for highlighting the connection between two
 * markers while the mouse is NOT over it.
 * @param {ExtendedLatLng} from - The origin of the line
 * @param {ExtendedLatLng} to - The destination of the line
 * @return {!Object} line - the polyline object created
 */
function addOutline(from, to) {
    //return addLine("outline", from, to, '#bb33bb', 2, 0.5, 1.35, true);
}
/**
 * Add a line to the map for highlighting the connection between two
 * markers.
 * @param {string}         attrib  - The attribute to use for managing the line
 * @param {ExtendedLatLng} from    - The origin of the line
 * @param {ExtendedLatLng} to      - The destination of the line
 * @param {string}         color   - The color of the line
 * @param {number}         weight  - The weight of the line
 * @param {number}         opacity - The opacity of the line (0..1)
 * @param {number}         scale   - The scale of the arrow-head (pt)
 * @param {boolean}        visible - The visibility of the line
 * @return {!Object}       line    - the polyline object created
 */
function addLine(attrib, from, to, color, weight, opacity, scale, visible) {
    from[attrib] = new google.maps.Polyline({
        path: [from, to],
        strokeColor: color,
        strokeWeight: weight,
        strokeOpacity: opacity,
        icons: [{
            offset: "0%",
            icon: {
                scale: scale /*pt*/ ,
                path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW
            }
        }]
    });
    from[attrib].setVisible(visible);
    from[attrib].setMap(map);
    to[attrib] = from[attrib];
    polylines.push(from[attrib]);
    return from[attrib];
}
/**
 * Add a pair of lines to the map for highlighting the connection between two
 * markers; one visible while the mouse is over the marker (the "overline"),
 * the other while it is not (the "outline").
 * @param {ExtendedLatLng} from - The origin of the line (the original input)
 * @param {ExtendedLatLng} to - The destination of the line (the snapped point)
 * @return {!Object} line - the polyline object created
 */
function addCorrespondence(coords, marker) {
    if (!coords.overline) {
        addOverline(coords, coords.related);
    }
    if (!coords.outline) {
        addOutline(coords, coords.related);
    }
    marker.addListener('mouseover', function(mevt) {
        coords.outline.setVisible(false);
        coords.overline.setVisible(true);
        coords.related.marker.setOpacity(1.0);
    });
    marker.addListener('mouseout', function(mevt) {
        coords.overline.setVisible(false);
        coords.outline.setVisible(true);
        coords.related.marker.setOpacity(0.5);
    });
}
/**
 * Add a marker to the map and check for special 'interpolated'
 * and 'unsnapped' properties to control which colour marker is used
 * @param {ExtendedLatLng} coords - Coords of where to add the marker
 * @return {!Object} marker - the marker object created
 */
function addMarker(coords) {

    var marker = new google.maps.Marker({
        position: coords,
        title: coords.lat + ',' + coords.lng,
        map: map,
        opacity: 0,
        //icon: RED_MARKER
    });
    // Coord should NEVER be interpolated AND unsnapped
    if (coords.interpolated) {
        //marker.setIcon(BLUE_MARKER);
    } else if (!coords.related) {
        //marker.setIcon(YELLOW_MARKER);
    } else if (coords.originalIndex != undefined) {
        //marker.setIcon(RED_MARKER);
        addCorrespondence(coords, marker);
    } else {
        marker.setIcon({
            url: GREEN_MARKER,
            scaledSize: {
                width: 20,
                height: 20
            }
        });
        addCorrespondence(coords, marker);
    }
    // Make markers change opacity when the mouse scrubs across them
    marker.addListener('mouseover', function(mevt) {
        marker.setOpacity(0);
    });
    marker.addListener('mouseout', function(mevt) {
        marker.setOpacity(0);
    });
    coords.marker = marker; // Save a reference for easy access later
    markers.push(marker);
    return marker;
}
/**
 * Animate an icon along a polyline
 * @param {Object} polyline The line to animate the icon along
 */
function animateCircle(polyline) {
    var count = 0;
    // fallback icon if the poly has no icon to animate
    var defaultIcon = [{
        icon: lineSymbol,
        offset: '-150%'
    }];
    window.setInterval(function() {
        count = (count + 1) % 200;
        var icons = polyline.get('icons') || defaultIcon;
        icons[0].offset = (count / 2) + '%';
        polyline.set('icons', icons);
    }, 70);
}
/**
 * Fit the map bounds to the current set of markers
 * @param {Array<Object>} markers Array of all map markers
 */
function fitBounds(markers) {
    var bounds = new google.maps.LatLngBounds;
    for (var i = 0; i < markers.length; i++) {
        bounds.extend(markers[i].getPosition());
    }
    map.fitBounds(bounds);
}
/**
 * Uses Places library to get Place Details for a Place ID
 * @param {string}   placeId         The Place ID to look up
 * @param {Function} foundCallback   Called if the place is found
 * @param {Function} missingCallback Called if nothing is found
 * @param {Function} errorCallback   Called if request fails
 */
function getPlaceDetails(placeId, foundCallback, missingCallback, errorCallback) {
    var request = {
        placeId: placeId
    };
    placesService.getDetails(request, function(place, status) {
        if (status == google.maps.places.PlacesServiceStatus.OK) {
            foundCallback(place);
        } else if (status == google.maps.places.PlacesServiceStatus.NOT_FOUND) {
            missingCallback();
        } else if (errorCallback) {
            errorCallback();
        }
    });
}
/**
 * AJAX request to the Roads Speed Limit API.
 * Request the speed limit for the Place ID
 * @param {string}   placeId         Place ID to request the speed limit for
 * @param {Function} successCallback Called if request is successful
 * @param {Function} errorCallback   Called if request fails
 */
function getSpeedLimit(placeId, successCallback, errorCallback) {
    $.ajax({
        type: 'GET',
        url: SPEED_LIMIT_URL,
        data: {
            placeId: placeId,
            key: API_KEY
        },
        success: successCallback,
        error: errorCallback
    });
}
/**
 * Open an infowindow on either the map or the active streetview pano
 * @param {Object} infowindow Infowindow to be opened
 * @param {Object} marker Marker the infowindow is anchored to
 */
function openInfoWindow(infowindow, marker) {
    // If streetView is visible display the infoWindow over the pano
    // and anchor to the marker
    if (map.getStreetView().getVisible()) {
        infowindow.open(map.getStreetView(), marker);
    }
    // Otherwise open it on the map and anchor to the marker
    else {
        infowindow.open(map, marker);
    }
}
/**
 * Add event listener to for when the active pano changes
 */
function listenForPanoChange() {
    var pano = map.getStreetView();
    // Close all open markers when the pano changes
    google.maps.event.addListener(pano, 'position_changed', function() {
        closeAllInfoWindows(infoWindows);
    });
}
/**
 * Close all open infoWindows
 * @param {Array<Object>} infoWindows - all infowindow objects
 */
function closeAllInfoWindows(infoWindows) {
    for (var i = 0; i < infoWindows.length; i++) {
        infoWindows[i].close();
    }
}
function decodelat(encoded) {
    // array that holds the points
    var points = []
    var index = 0,
        len = encoded.length;
    var lat = 0,
        lng = 0;
    while (index < len) {
        var b, shift = 0,
            result = 0;
        do {
            b = encoded.charAt(index++).charCodeAt(0) - 63; //finds ascii //and substract it by 63
            result |= (b & 0x1f) << shift;
            shift += 5;
        } while (b >= 0x20);
        var dlat = ((result & 1) != 0 ? ~(result >> 1) : (result >> 1));
        lat += dlat;
        shift = 0;
        result = 0;
        do {
            b = encoded.charAt(index++).charCodeAt(0) - 63;
            result |= (b & 0x1f) << shift;
            shift += 5;
        } while (b >= 0x20);
        var dlng = ((result & 1) != 0 ? ~(result >> 1) : (result >> 1));
        lng += dlng;
        points.push({
            latitude: (lat / 1E5),
            longitude: (lng / 1E5)
        })
    }
    //console.log(points);
    return points;
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
