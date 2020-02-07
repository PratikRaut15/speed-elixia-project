var counter = 0;
var geocodeinited = false;
var markers = [];
var allColor = ['#FE6700', '#6FABDF', '#D73F33', '#794F21', '#60AAEA'];

var directionsDisplay;
var directionsService = new google.maps.DirectionsService();

var styles =[
{ "featureType": "road", "elementType": "geometry.stroke", "stylers": [ { "visibility": "off" } ] },{ "featureType": "poi.park", "stylers": [ { "visibility": "simplified" }, { "lightness": 46 } ] },{ "featureType": "poi", "elementType": "labels", "stylers": [ { "visibility": "on" } ] },{ "featureType": "road.highway", "elementType": "labels", "stylers": [ { "visibility": "off" } ] },{ "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [ { "color": "#9e9e9f" } ] },{ "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [ { "color": "#bfbfbf" } ] },{ "featureType": "road.local", "elementType": "geometry.fill", "stylers": [ { "color": "#e0e0e0" } ] },{ "featureType": "poi.park", "stylers": [ { "lightness": 38 } ] },{ "stylers": [ { "saturation": -54 } ] }];

//aluto height adjust
jQuery.noConflict();

jQuery(document).ready(function () {
    //map init
    initialize();

    jQuery('#gc-topnav2').css("display", "block");
    jQuery("#gc-topnav2").draggable();

    // Handler for .ready() called.
    var browserHeight = jQuery(window).height();
    jQuery('#sidebar').css("height", browserHeight - 67);
    jQuery('#map').css("height", browserHeight - 67);
    //jQuery('#mapdetails').css("height", 30);
    jQuery('#wrapper').css("height", browserHeight - 117);
    jQuery('#pre').css("display", "block");

    jQuery(".all_select").click(function () {
        selectall(jQuery(this).data('type'));
    });
    jQuery(".all_clear").click(function () {
        clearall(jQuery(this).data('type'));
    });

    jQuery(".scrollablediv").height(browserHeight-120);

});


function selectall(type)
{
    switch(type)
    {
        case 'vehicles':
            jQuery(".veh_all").each(function() {
                jQuery(this).prop('checked', true);
                plotRoute(this);
            });
        break;
    }
}

function clearall(type)
{
    switch(type)
    {
        case 'vehicles':
            jQuery(".veh_all").each(function() {
            jQuery(this).prop('checked', false);
        });
        directionsDisplay.setMap(null);
        setAllMap(null);
        break;
    }
}



/*To plot route of certain vehicles*/
function plotRoute(){


        var tempRoute;
        var warehouseLatLong;

        var error;
        jQuery.ajax({
            url: 'route_ajax.php',
            type: 'POST',
            data: 'busRoute=1',
            async: false,
            success: function (record) {
                var recordDcd = jQuery.parseJSON(record);
                error = recordDcd.error;
                if(error==undefined){
                    tempRoute = recordDcd.finalSeq;
                    warehouseLatLong = recordDcd.start_point;
                }
            }
        });
        if(error!=undefined){alert(error);return false;}
        var cur;
        var loop = 0;
        var mapLoop = tempRoute.length;
        var waypts = [];
        var allMarker = [];

        allMarker.push(warehouseLatLong);
        jQuery.each(tempRoute, function (i, value) {
            loop++;
            cur = value['lat']+','+value['lng'];
            allMarker.push(cur+','+2);
            if(loop==mapLoop){
                return false;
            }
            waypts.push({
              location:cur,
              stopover:true
            });

        });

        calcRoute(warehouseLatLong, cur, waypts, allMarker);

}

/*display route*/
function calcRoute(start, end, waypts, allMarker) {
    var color = allColor[Math.floor(Math.random()*allColor.length)];

    directionsDisplay = new google.maps.DirectionsRenderer({
        preserveViewport: true,
        suppressMarkers: true,
        polylineOptions: { strokeColor: color }
    }); //for direction
    directionsDisplay.setMap(map);

    var request = {
      origin:start,
      destination:end,
      waypoints: waypts,
      optimizeWaypoints: true,
      travelMode: google.maps.TravelMode.DRIVING
    };
    directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
    }
    });


    var cleanValue;
    var icon;

    jQuery.each(allMarker, function (i, value) {
        cleanValue = value.split(',');
        var myLatlng = new google.maps.LatLng(cleanValue[0],cleanValue[1]);
        //alpha = String.fromCharCode('A'.charCodeAt(0) + i);
        icon = 'http://www.googlemapsmarkers.com/v1/'+i+'/'+markerColorCases(cleanValue[2])+'/';
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            icon: icon,
        });
        markers.push(marker);
    });
}

function markerColorCases(accuracy){
    switch(accuracy){
        case '2':
            return '0099ff';
        case '3':
            return '009999';
        default:
            return '009900';
    }
}

// Sets the map on all markers in the array.
function setAllMap(map) {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}



function initialize() {

    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });

    var mumbai = new google.maps.LatLng(19.250784,72.850693);
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
    //autocomplete();
    start = new google.maps.LatLng(19.250784, 72.850693);
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

    plotRoute();

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
