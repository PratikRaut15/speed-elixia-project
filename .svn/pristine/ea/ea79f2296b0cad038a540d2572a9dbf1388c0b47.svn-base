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
var isBusStudent = 0;
var allColor = ['#FE6700', '#6FABDF', '#D73F33', '#794F21', '#60AAEA', '#FFFF00'];
if (customerrefreshfrqmap == 267) {
    var periodictimemap = 10000;
} else {
    var periodictimemap = 60000;
}
var styles = [
{"featureType": "road", "elementType": "geometry.stroke", "stylers": [{"visibility": "off"}]}, {"featureType": "poi.park", "stylers": [{"visibility": "simplified"}, {"lightness": 46}]}, {"featureType": "poi", "elementType": "labels", "stylers": [{"visibility": "on"}]}, {"featureType": "road.highway", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#9e9e9f"}]}, {"featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [{"color": "#bfbfbf"}]}, {"featureType": "road.local", "elementType": "geometry.fill", "stylers": [{"color": "#e0e0e0"}]}, {"featureType": "poi.park", "stylers": [{"lightness": 38}]}, {"stylers": [{"saturation": -54}]}];
jQuery.noConflict();
jQuery(document).ready(function () {
    initialize();
    jQuery('#gc-topnav2').css("display", "block");
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
});
function selectall(type)
{
    switch (type)
    {
        case 'vehicles':
        markers = [];
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
            markers.push(marker);
        });
        markerCluster.clearMarkers();
        markerCluster = new MarkerClusterer(map, markers);
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
            markerCluster.clearMarkers();
            markers = [];
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
    }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
            map.set('center', latlng);
            map.set('zoom', 15);
            markerlatlng = results[0].geometry.location;
        }
        else
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
    var mumbai = new google.maps.LatLng(19.250784,72.850693);
    var mapOptions = {
        zoom: 11,
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
    start = new google.maps.LatLng(19.250784,72.850693);
    end = new google.maps.LatLng(19.250784,72.850693);
    createMarker(start);
    var color = allColor[Math.floor(Math.random() * allColor.length)];
    var cityCircle = new google.maps.Circle({
        strokeColor: color,
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: color,
        fillOpacity: 0.05,
        map: map,
        center: start,
        radius: 4900
    });
    color = allColor[Math.floor(Math.random() * allColor.length)];
    var cityCircle = new google.maps.Circle({
        strokeColor: color,
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: color,
        fillOpacity: 0.05,
        map: map,
        center: start,
        radius: 9900
    });
    color = allColor[Math.floor(Math.random() * allColor.length)];
    var cityCircle = new google.maps.Circle({
        strokeColor: color,
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: color,
        fillOpacity: 0.05,
        map: map,
        center: start,
        radius: 14900
    });
    color = allColor[Math.floor(Math.random() * allColor.length)];
    var cityCircle = new google.maps.Circle({
        strokeColor: color,
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: color,
        fillOpacity: 0.05,
        map: map,
        center: start,
        radius: 19900
    });
    color = allColor[Math.floor(Math.random() * allColor.length)];
    var cityCircle = new google.maps.Circle({
        strokeColor: color,
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: color,
        fillOpacity: 0.05,
        map: map,
        center: start,
        radius: 24900
    });
    var trafficLayer = new google.maps.TrafficLayer();
    trafficLayer.setMap(map);

    //periodicupdate();
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
    }
    else if ((jQuery("#veh_" + vehid).length != 0) && jQuery("#veh_" + vehid).is(':checked') == false)
    {
        if (marker)
        {
            marker.setMap(null);
        }
        remove(markers, marker);
    }
    markerCluster.clearMarkers();
    if (markers.length != 0)
    {
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
        //refreshdata();
    }, periodictimemap);
}
function refreshmap()
{
    AjaxData();
}
function refreshdata()
{
    AjaxData();
    //periodicupdate();
}
function AjaxData()
{

    jQuery.each(vehiclesfordel, function (index, value) {
        marker = vehiclesfordel[index];
        if (marker)
        {
            marker.setMap(null);
        }
        markerCluster.clearMarkers();
        markers = [];
    });
    var urldata = "route_ajax.php?all=1&isBusStudent="+isBusStudent;
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
            var bounds = new google.maps.LatLngBounds();
            if (results!= null && results.length > 0) {
                jQuery.each(results, function (i, device) {
                var image = new google.maps.MarkerImage(device.image,
                    new google.maps.Size(48, 48),
                    new google.maps.Point(0, 0),
                    new google.maps.Point(8, 20));
                var latLng = new google.maps.LatLng(device.lat, device.lng);
                var marker = new MarkerWithLabel({'position': latLng, map: map, icon: image, labelContent: device.enrollmentNo,
                    labelAnchor: new google.maps.Point(9, 45),
                    labelClass: "mapslabels" // the CSS class for the label
                });
                bounds.extend(marker.position);
                var contentString = '<h3>' + device.enrollmentNo + '</h3><hr><p>' +
                'StudnetName : ' + device.studentName + '<br>' +
                'Division : ' + device.division + '';
                var boxText = document.createElement("div");
                boxText.style.cssText = "";
                boxText.innerHTML = "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> " + contentString + "<br/></div></div></div>";
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
                google.maps.event.addListener(marker, "click", function (e) {
                    ib.open(map, this);
                });
                var vehid = device.studentId;
                marker.set("id", device.studentId);
                vehiclesfordel[vehid] = marker;
                markers.push(marker);
            });
            map.fitBounds(bounds);
            markerCluster = new MarkerClusterer(map, markers);
            var userkey = jQuery("#userkey").val();
            if (userkey != '' && typeof userkey !== 'undefined') {
                setVehOnMapWithUserkey();
            }
            //plotStudentList(data);
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
            if (markers.length != 0)
            {
                markerCluster.clearMarkers();
                markerCluster = new MarkerClusterer(map, markers);
            }

            plotStudentList(data);

            }else {

                jQuery(".scrollablediv_student").html('');
            }

        }
    });

}

function IsBusStudent()
{
    if(jQuery("#isBus").prop("checked") == true && jQuery("#isNonBus").prop("checked") == true){
        isBusStudent = 1;
    }else if(jQuery("#isBus").prop("checked") == true && jQuery("#isNonBus").prop("checked") == false){
       isBusStudent = 2;
    }else if(jQuery("#isBus").prop("checked") == false && jQuery("#isNonBus").prop("checked") == true){
       isBusStudent = 3;
    }else if(jQuery("#isBus").prop("checked") == false && jQuery("#isNonBus").prop("checked") == false){
       isBusStudent = 4;
    }
    refreshdata();
}
function plotStudentList(data){
    var cdata1 = jQuery.parseJSON(data);
    var results = cdata1.result;
    var inputs = "";

    jQuery.each(results, function (i, device) {
         inputs += "<input type='checkbox' class='veh_all' id='veh_"+device.studentId+"' onclick='vehplot("+device.studentId+");' checked>"+ device.enrollmentNo+"</br>";
    });
    jQuery(".scrollablediv_student").html(inputs);

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
function evictMarkers() {
    // clear all markers
    jQuery.each(eviction_list, function (i, item) {
        item.setMap(null);
    });
    eviction_list = [];
}
//
function delete_all() {
    jQuery.each(eviction_list, function (i, item) {
        item.setMap(null);
    });
    eviction_list = [];
    jQuery("#vehicle_list").html("");
}
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
function getselvehicles() {
    var params = "all=1";
    new Ajax.Request('route_ajax.php', {
        parameters: params,
        onSuccess: function (transport) {
            var cdata1 = transport.responseText.evalJSON();
            results = cdata1.result;

        },
        onComplete: function () {
        }
    });
}
function removevehicle(id) {
    jQuery('#to_vehicle_div_' + id).remove();
    delete vehicle_list[vehicle_list.indexOf(id)];
    setTimeout(function () {
        getselvehicles();
    }, 10000);
}
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
    clearall('vehicles');
    jQuery("#veh_" + vehicleid).attr("checked", true);
    vehplot(vehicleid);
}
function routehistopen(vehicleid) {
    window.open("../reports/reports.php?vehicleid=" + vehicleid, "_blank");
}
