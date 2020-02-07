jQuery(document).ready(function () {

    initialize();
    initialize1();
});


function initialize() {
    var marker1;
    var marker2;
    var marker3;
    var j = 0;
    var car = "M17.402,0H5.643C2.526,0,0,3.467,0,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759c3.116,0,5.644-2.527,5.644-5.644 V6.584C23.044,3.467,20.518,0,17.402,0z M22.057,14.188v11.665l-2.729,0.351v-4.806L22.057,14.188z M20.625,10.773 c-1.016,3.9-2.219,8.51-2.219,8.51H4.638l-2.222-8.51C2.417,10.773,11.3,7.755,20.625,10.773z M3.748,21.713v4.492l-2.73-0.349 V14.502L3.748,21.713z M1.018,37.938V27.579l2.73,0.343v8.196L1.018,37.938z M2.575,40.882l2.218-3.336h13.771l2.219,3.336H2.575z M19.328,35.805v-7.872l2.729-0.355v10.048L19.328,35.805z";
    var icon = {
        path: car,
        scale: .7,
        strokeColor: 'white',
        strokeWeight: .10,
        fillOpacity: 1,
        fillColor: '#404040',
        offset: '5%',
        rotation: 200,
        anchor: new google.maps.Point(10, 25) // orig 10,50 back of car, 10,0 front of car, 10,25 center of car
    };
    // initialize infoWindow
    infoWindow = new google.maps.InfoWindow({
        size: new google.maps.Size(150, 50)
    });
    var options = {
        // max zoom
        zoom: 12
    };
    map1 = new google.maps.Map(document.getElementById("map"), options);
    //
    // initial location which loads up on map1
    address = 'mumbai'

    // Geocoder is used to encode or actually geocode textual addresses to lat long values
    geocoder = new google.maps.Geocoder();
    geocoder.geocode({'address': address}, function (results, status) {
        if (results.length)
            map1.fitBounds(results[0].geometry.viewport);
    });

    var startLoc = [];
    var endLoc = [];

    startLoc[0] = 'malad';
    startLoc[1] = '19.152309, 72.844612';
    endLoc[0] = '19.152309, 72.844612';
    endLoc[1] = '19.119826, 72.905145';

    // empty out previous values
    startLocation = [];
    endLocation = [];
    polyLine = [];
    poly2 = [];
    timerHandle = [];
    var toggle1 = true;
    var toggle = true;
    for (var j = 0; j < startLoc.length; j++) {
        var directionsService = new google.maps.DirectionsService();
        var directionsDisplay = new google.maps.DirectionsRenderer();
        var travelMode = google.maps.DirectionsTravelMode.DRIVING;
        var request = {
            origin: startLoc[j],
            destination: endLoc[j],
            travelMode: travelMode
        };

        directionsService.route(request, function (response, status) {

            if (status == google.maps.DirectionsStatus.OK) {
                var startLocation = new Object();
                var endLocation = new Object();
                var legs = response.routes[0].legs;

                var rendererOptions = {
                    map: map1,
                    suppressMarkers: true,
//                suppressPolylines: true,
//                preserveViewport: true
                };
                // directionsrenderer renders the directions obtained previously by the directions service
                disp = new google.maps.DirectionsRenderer(rendererOptions);
                disp.setMap(map1);
//            disp.setOptions({suppressMarkers: true, suppressPolylines: true});
                disp.setDirections(response);

                // create Markers
                for (k = 0; k <= legs.length; k++) {
                    // for first marker only
                    console.log('Toggle=>'+toggle);
                    if (toggle) {
                        endLocation.latlng = legs[k].end_location;
                        endLocation.address = legs[k].end_address;
                        marker3 = createMarker1(legs[k].end_location, "Current Location", legs[k].end_address, icon);
                        toggle = false;
                    }

                }

                directionsDisplay.setDirections(response);
                directionsDisplay.setOptions({
//                suppressMarkers: true,
                    preserveViewport: true
                });
                if (toggle1) {
                    var color = ''
                } else {
                    var color = '#FFA500'
                }
//                console.log('Toggle=>'+toggle1);
                toggle1 = false;
                var polyline = getPolyline(response, color);
                polyline.setMap(map1);

                map1.setZoom(12);
            }

        });
    }

}

function initialize1() {
    var marker1;
    var marker2;
    var marker3;
    var j = 0;
    var car = "M17.402,0H5.643C2.526,0,0,3.467,0,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759c3.116,0,5.644-2.527,5.644-5.644 V6.584C23.044,3.467,20.518,0,17.402,0z M22.057,14.188v11.665l-2.729,0.351v-4.806L22.057,14.188z M20.625,10.773 c-1.016,3.9-2.219,8.51-2.219,8.51H4.638l-2.222-8.51C2.417,10.773,11.3,7.755,20.625,10.773z M3.748,21.713v4.492l-2.73-0.349 V14.502L3.748,21.713z M1.018,37.938V27.579l2.73,0.343v8.196L1.018,37.938z M2.575,40.882l2.218-3.336h13.771l2.219,3.336H2.575z M19.328,35.805v-7.872l2.729-0.355v10.048L19.328,35.805z";
    var icon = {
        path: car,
        scale: .7,
        strokeColor: 'white',
        strokeWeight: .10,
        fillOpacity: 1,
        fillColor: '#404040',
        offset: '5%',
        rotation: 200,
        anchor: new google.maps.Point(10, 25) // orig 10,50 back of car, 10,0 front of car, 10,25 center of car
    };
    // initialize infoWindow
    infoWindow = new google.maps.InfoWindow({
        size: new google.maps.Size(150, 50)
    });
    var options = {
        // max zoom
        zoom: 12
    };
    map1 = new google.maps.Map(document.getElementById("map"), options);
    //
    // initial location which loads up on map1
    address = 'mumbai'

    // Geocoder is used to encode or actually geocode textual addresses to lat long values
    geocoder = new google.maps.Geocoder();
    geocoder.geocode({'address': address}, function (results, status) {
        if (results.length)
            map1.fitBounds(results[0].geometry.viewport);
    });
    // empty out previous values
    startLocation = [];
    endLocation = [];
    polyLine = [];
    poly2 = [];
    timerHandle = [];
    var directionsService1 = new google.maps.DirectionsService();
    var travelMode = google.maps.DirectionsTravelMode.DRIVING;
    var request = {
        origin: 'malad',
        destination: '19.119826, 72.905145',
        travelMode: travelMode
    };


    directionsService1.route(request, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            var startLocation = new Object();
            var endLocation = new Object();
            var legs1 = response.routes[0].legs;

//            var rendererOptions = {
//                map: map1,
//                suppressMarkers: true,
////                suppressPolylines: true,
////                preserveViewport: true
//            };
            // directionsrenderer renders the directions obtained previously by the directions service
//            disp = new google.maps.DirectionsRenderer(rendererOptions);
//            disp.setMap(map1);
//            disp.setOptions({suppressMarkers: true, suppressPolylines: true});
//            disp.setDirections(response);

            // create Markers
//            for (k = 0; k <= legs1.length; k++) {
            // for first marker only
//                if (k == 0) {
//                    startLocation.latlng = legs1[k].start_location;
//                    startLocation.address = legs1[k].start_address;
            marker1 = createMarker(legs1[0].start_location, "start", legs1[0].start_address, '../../images/Sflag.png');
//                }

//                endLocation.latlng = legs1[k].end_location;
//                endLocation.address = legs1[k].end_address;
            marker2 = createMarker(legs1[0].end_location, "end", legs1[0].end_address, '../../images/Eflag.png');


//            }

        }

    });




}

function getPolyline(result, color) {
    var polyline = new google.maps.Polyline({
        path: [],
        geodesic: true,
        strokeColor: color,
        strokeOpacity: 1.0,
        strokeWeight: 4,
        zIndex: 100

    });
    var path = result.routes[0].overview_path;
    var legs = result.routes[0].legs;
    for (var t = 0; t < legs.length; t++) {
        var steps = legs[t].steps;
        for (var s = 0; s < steps.length; s++) {
            var nextSegment = steps[s].path;
            for (var k = 0; k < nextSegment.length; k++) {
                polyline.getPath().push(nextSegment[k]);
            }
        }
    }
    return polyline;
}

// returns the marker
function createMarker(latlng, label, html, path_url) {
//    console.log('latlng'+html);
    var contentString = '<b>' + label + '</b><br>' + html;
    // using Marker api, marker is created
    var marker1 = new google.maps.Marker({
        position: latlng,
        map: map1,
        title: label,
        zIndex: 10,
        icon: new google.maps.MarkerImage(path_url)
    });
    marker1.myname = label;
    // adding click listener to open up info window when marker is clicked
    google.maps.event.addListener(marker1, 'click', function () {
        infoWindow.setContent(contentString);
        infoWindow.open(map1, marker1);
    });
    return marker1;
}

function createMarker1(latlng, label, html, icon) {
//    console.log(latlng);
//    console.log(label);
//    console.log(html);
//    console.log(html);

    var contentString = '<table><tr><td><b>' + label + '</b></td><td>' + html + '</td></tr><tr><td><b>Distance Travelled</b></td><td>4.9 Km</td></tr><tr><td><b>Remaining Distance</b></td><td>9.2 Km </td></tr><tr><td> <b>ETA</b></td><td>35 min</td></tr></table>';
    // using Marker api, marker is created
    var marker2 = new google.maps.Marker({
        position: latlng,
        map: map1,
        title: label,
        zIndex: 10,
        icon: icon,
        visible: true
    });
    marker2.myname = label;
    // adding click listener to open up info window when marker is clicked
    google.maps.event.addListener(marker2, 'click', function () {
        infoWindow.setContent(contentString);
        infoWindow.open(map1, marker2);
    });
    return marker2;
}