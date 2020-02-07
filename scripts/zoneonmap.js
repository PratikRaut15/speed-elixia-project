var gmapsinited = false;
var eviction_list = [];
var route = [];
var zoneid = getUrlVars()["zid"];
var device;
var drawingManager;
// Style
var styles = [
    {"featureType": "road", "elementType": "geometry.stroke", "stylers": [{"visibility": "off"}]}, {"featureType": "poi.park", "stylers": [{"visibility": "simplified"}, {"lightness": 46}]}, {"featureType": "poi", "elementType": "labels", "stylers": [{"visibility": "on"}]}, {"featureType": "road.highway", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#9e9e9f"}]}, {"featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [{"color": "#bfbfbf"}]}, {"featureType": "road.local", "elementType": "geometry.fill", "stylers": [{"color": "#e0e0e0"}]}, {"featureType": "poi.park", "stylers": [{"lightness": 38}]}, {"stylers": [{"saturation": -54}]}];


function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
        vars[key] = value;
    });
    return vars;
}

function initialize() {
    var latlng = new google.maps.LatLng(19.07, 72.89);
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });
    var myOptions = {
        zoom: 11,
        center: latlng,
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL
        },
        styles: styles
    };
    var map = new google.maps.Map(document.getElementById("map"), myOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
}

function initmap(lat, lng) {
    if (gmapsinited)
        return;
    var latlng = new google.maps.LatLng(lat, lng);
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });
    var myOptions = {
        zoom: 15,
        center: latlng,
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL
        },
        styles: styles
    };
    map = new google.maps.Map(document.getElementById("map"), myOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    gmapsinited = true;
}

function mapvehicle() {
    var fence = "fenceid=" + encodeURIComponent(fenceid);

    var datastring = fence + "&get=vehicle";
    jQuery.ajax({
        type: "POST",
        url: "../../modules/fencing/route_ajax.php",
        data: datastring,
        cache: false,
        datatype: "json",
        success: function (data)
        {
            //alert(data);
            var vdata = data;
            //var vdata = jQuery.evalJSON(data);
            plotvehicle(vdata);
        }
    });

}

function plotvehicle(vdata) {
    evictMarkers();
    var results = vdata.result;
    jQuery.each(results, function (device) {
        try {
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
            eviction_list.push(marker);
            var contentString = '<h3>' + device.cname + '</h3><hr><p>' +
                    'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + ']<br>' +
                    'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : ' + device.clastupdated +
                    '<hr><a href=../history/history.php?id=5&vid=' + device.cvehicleid + '>Map Vehicle</a>' +
                    '<a href=../history/history.php?id=1&vid=' + device.cvehicleid + '> Vehicle History </a>';
            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });
            google.maps.event.addListener(marker, 'click', function () {
                infowindow.open(map, marker);
            });
        } catch (ex) {
            alert(ex);
        }
    });
}

function periodicupdate() {
    mapvehicles.delay(60);
}

function mapzone() {
    var zone = "zoneid=" + encodeURIComponent(zoneid);
    var datastring = zone + "&get=zone";
    jQuery.ajax({
        type: "POST",
        url: "route_ajax.php",
        data: datastring,
        cache: false,
        datatype: "json",
        success: function (data)
        {
            //alert(data);
            //var fdata = data;
            var fdata = jQuery.parseJSON(data);
            plotfence(fdata);
        }
    });
}

function plotfence(fdata) {
    var results = fdata.result;
    jQuery.each(results, function (i, device) {
        try {
            initmap(device.cgeolat, device.cgeolong);
            var fencelatlng = new google.maps.LatLng(device.cgeolat, device.cgeolong);

            route.push(fencelatlng);
        } catch (ex) {
            alert(ex);
        }
    });
    cpolyline();
}
var poly
function cpolyline() {


    poly = new google.maps.Polygon({
        path: route,
        strokeWeight: 1,
        fillColor: '#9e9e9f',
        fillOpacity: 0.3,
        editable: true
    });

    poly.setMap(map);



    google.maps.event.addListener(poly.getPath(), 'set_at', getpath);

    google.maps.event.addListener(poly.getPath(), 'insert_at', getpath);




}
function getpath() {
    //alert(poly.getPath().getArray());
}

function editzoning() {
    fencename = jQuery("#fencingName").val();
    if (fencename != "") {
        //var coordinates = (selectedShape.getPath().getArray());
        datapush();
    } else {
        jQuery('#fencingName').css("border-color", "#AC020F");
    }
}

function datapush() {
    //alert(poly.getPath().getArray());exit;
    var coordinates = (poly.getPath().getArray());
    jQuery.each(coordinates, function (key, value) {
        if (latitudes != "") {
            latitudes += ", " + value.lat();
        } else {
            latitudes += value.lat();
        }
        if (longitudes != "") {
            longitudes += ", " + value.lng();
        } else {
            longitudes += value.lng();
        }
    });
    var vehiclearray = new Array();
    jQuery('.v_list_element').each(function () {
        vehiclearray.push(jQuery(this).val());
    });
    var datastring = "zoneid=" + zoneid + "&zonename=" + fencename + "&lat=" + latitudes + "&long=" + longitudes + "&vehiclearray=" + vehiclearray;
    jQuery.ajax({
        type: "POST",
        data: datastring,
        cache: false,
        url: "route_ajax.php",
        success: function (statuscheck) {
            if (statuscheck == "ok") {
                window.location = "zone.php?id=2";
            } else if (statuscheck == "detailsreqd") {
                jQuery("#fencename").show();
                jQuery("#fencename").fadeOut(3000);
            } else if (statuscheck == "notok") {
                jQuery("#samename").show();
                jQuery("#samename").fadeOut(3000);
            } else {
//                alert("Something went wrong.Retry" + statuscheck);
            }
        }
    });
}

function setSelection(shape) {
    clearSelection();
    selectedShape = shape;
    shape.setEditable(true);
    selectColor(shape.get('fillColor') || shape.get('strokeColor'));
}

function evictMarkers() {
    // clear all markers
    eviction_list.forEach(function (item) {
        item.setMap(null)
    });
    // reset the eviction array 
    eviction_list = [];
}

function loaded() {
    initialize();
    mapzone();
    //mapvehicle();
}
//Event.observe(window, 'load', function () {
//});
jQuery(document).ready(function () {
    // On click event 
    jQuery(window).load(function () {
        // Function actions here
        loaded();
        mappedvehicles();
    });
});

function mappedvehicles() {
    jQuery('.mappedvehicles').each(function () {
        var vehicleid = jQuery(this).attr('rel');
        var vehicleno = jQuery(this).val();
        ldvehicle(vehicleno, vehicleid);
    });
}
function ldvehicle(vehicleno, vehicleid)
{
    var selected_name = vehicleno;
    if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null)
    {
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function () {
            removeVehicle(vehicleid);
        };
        div.className = 'recipientbox';
        div.id = 'to_vehicle_div_' + vehicleid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
        jQuery('#vehicle_list_route').append(div);
        jQuery(div).append(remove_image);
    }
}

function VehicleForRoute() {

    var vehicleid = jQuery('#vehicleroute').val();
    if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
        var selected_name = jQuery("#vehicleroute option:selected").text();
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function () {
            removeVehicle(vehicleid);
        };
        div.className = 'recipientbox';
        div.id = 'to_vehicle_div_' + vehicleid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
        jQuery("#vehicle_list_route").append(div);
        jQuery(div).append(remove_image);
    }
    jQuery("#vehicleroute").prop("selectedIndex", 0);
}

function VehicleForRoute_ById(vehicleid, selected_name) {
    //var vehicleid = jQuery('#vehicleroute').val();
    if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
        //var selected_name = jQuery("#vehicleroute option:selected").text();
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function () {
            removeVehicle(vehicleid);
        };
        div.className = 'recipientbox';
        div.id = 'to_vehicle_div_' + vehicleid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="v_list_element" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
        jQuery("#vehicle_list_route").append(div);
        jQuery(div).append(remove_image);
    }
    jQuery("#vehicleno").val('');
}

function addallvehicleForRoute() {
    for (var i = 1; i < jQuery('#vehicleroute option').length; i++) {
        jQuery("#vehicleroute").prop("selectedIndex", i);
        VehicleForRoute();
    }
}

function removeVehicle(vehicleid) {
    jQuery('#to_vehicle_div_' + vehicleid).remove();
}