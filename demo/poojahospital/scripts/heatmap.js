var map, pointarray, heatmap;
var markers = [];
var taxiData = [];

function initialize() {
    var mapOptions = {
        zoom: 10,
        center: new google.maps.LatLng(19.03590687086149, 72.94649211215824),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('map'),
            mapOptions);

    var pointArray = new google.maps.MVCArray(taxiData);
    heatmap = new google.maps.visualization.HeatmapLayer({
        data: pointArray
    });
    heatmap.setMap(map);
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

var taxiDatajson = getLatlongJson();
jQuery(taxiDatajson).each(function (index, val) {
    var temp = new google.maps.LatLng(val.lat, val.lng);
    taxiData.push(temp);
});

function getLatlongJson() {
    var hasLL = getParameterByName('hasLL');
    if(hasLL === ''){
        hasLL = 1;
    }
    var datar = [];
    jQuery.ajax({
        url: 'demoajax.php',
        type: 'POST',
        data: {action: 'getHeatData', hasLL: hasLL},
        async: false,
        success: function (response) {
            datar = jQuery.parseJSON(response);
        }
    });
    return datar;
}

function showMarker() {
    jQuery(taxiDatajson).each(function (index, val) {
        var temp2 = new google.maps.LatLng(val.lat, val.lng);
        var marker = new google.maps.Marker({
            position: temp2,
            map: map,
            scale: 4,
            title: val.address
        });
        markers.push(marker);
        var infowindow = new google.maps.InfoWindow();
        google.maps.event.addListener(marker, 'click', (function (marker, content, infowindow) {
            return function () {
                infowindow.setContent(content);
                infowindow.open(map, marker);
            };
        })(marker, val.address, infowindow));
    });
}

function hideMarker() {
    setAllMap(null);
}

function setAllMap(map) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}

google.maps.event.addDomListener(window, 'load', initialize);

