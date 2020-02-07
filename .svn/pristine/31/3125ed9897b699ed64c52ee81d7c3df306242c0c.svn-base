var gmapsinited = false;
var eviction_list = [];
var vehicleid = null;
var vehicle_list = [];
var counter = 0;
//aluto height adjust
jQuery.noConflict();
jQuery(document).ready(function () {
    // Handler for .ready() called.
    var browserHeight = jQuery(window).height();
    jQuery('#sidebar').css("height", browserHeight - 172);
    jQuery('#map').css("height", browserHeight - 160);
    jQuery('#wrapper').css("height", browserHeight - 165);
    jQuery('#pre').css("display", "block");
});




function initialize() {

    var latlng = new google.maps.LatLng(0, 0);
    var myOptions = {
        zoom: 0,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map"), myOptions);

}

function onclicktog() {
    $('sidebar').toggle(900);
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
  if (gmapsinited) return;
    var latlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
        zoom: 11,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map($("map"), myOptions);
    gmapsinited = true;
}

function mapvehicles() {
    addAllVehicles();
}

function periodicupdate() {
    getselvehicles.delay(60);
}

function plotvehicles(cdata) {
    evictMarkers();
    var results = cdata.result;
    results.each(function (device) {
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

            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            google.maps.event.addListener(marker, 'mouseover', function () {
                infowindow.open(map, marker);
                 window.setInterval(closure, 10000);
            });
        } catch (ex) {
            alert(ex);
        }
    });
}

function mapcheckpoints() {
    new Ajax.Request('../../modules/checkpoint/route_ajax.php?chk=all', {
        onSuccess: function (transport) {
            var cdata = transport.responseText.evalJSON();
            if (cdata.result.length !== 0) {
                plotCheckpoints(cdata);
            }
        },
        onComplete: function () {}
    });
}

function plotCheckpoints(cdata) {
    var results = cdata.result;
    results.each(function (device) {
        try {
            var myLatLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);

            /* var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: device.name
                });*/

            var marker = new MarkerWithLabel({
                position: myLatLng,
                map: map,
                labelContent: device.cname,
                labelAnchor: new google.maps.Point(9, 45),
                labelClass: "mapslabels_chkp" // the CSS class for the label
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

            eviction_list.push(marker);
            eviction_list.push(circle);
        } catch (ex) {
            alert(ex);
        }
    });

}

function evictMarkers() {
    // clear all markers
    eviction_list.forEach(function (item) {
        item.setMap(null);
    });

    // reset the eviction array 
    eviction_list = [];
}

function delete_all() {

    eviction_list.forEach(function (item) {
        item.setMap(null);
    });

    // reset the eviction array 
    eviction_list = [];
    jQuery("#vehicle_list").html("");

}

function getchk() {
    var devices = "";
    vehicle_list.forEach(function (item) {
        if (item !== undefined) {
            devices = devices + item + ",";
        }
    });
    var params = "vehicleids=" + encodeURIComponent(devices);
    new Ajax.Request('../common/getchkforvehicles.php', {
        parameters: params,
        onSuccess: function (transport) {
            var cdata = transport.responseText.evalJSON();
            plotCheckpoints(cdata);
        },
        onComplete: function () {}
    });
}

function addVehicle() {
    var vehicle_id = $('to').getValue();

    if (vehicle_id > -1 && $('to_vehicle_div_' + vehicle_id) === null) {
        var selected_name = $('to').options[$('to').selectedIndex].text;
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
        $('vehicle_list').appendChild(div);
        $(div).appendChild(remove_image);
        vehicle_list.push(vehicle_id);
    }
    $('to').selectedIndex = 0;
}

function getselvehicles() {
    var devices = "";
    vehicle_list.forEach(function (item) {
        if (item !== undefined) {
            devices = devices + item + ",";
        }
    });
    var params = "vehicleids=" + encodeURIComponent(devices);
    new Ajax.Request('route_ajax.php', {
        parameters: params,
        onSuccess: function (transport) {
            var cdata = transport.responseText.evalJSON();
            plotvehicles(cdata);
            getchk();
            periodicupdate();
        },
        onComplete: function () {}
    });
}

function removevehicle(id) {
    $('to_vehicle_div_' + id).remove();
    delete vehicle_list[vehicle_list.indexOf(id)];
    getselvehicles.delay(10);
}

function addAllVehicles() {
    var select_box = $('to');
    for (var i = 1; i < select_box.options.length; i++) {
        select_box.selectedIndex = i;
        addVehicle();
    }
    getselvehicles();
}

function loaded() {
    initialize();
    mapvehicles();
    mapcheckpoints();

}


Event.observe(window, 'load', function () {
    loaded();
});