var markers = [];
var markersfordel = {};
var circlesfordel = {};
var markr = [];
var markrsfordel = {};
var zonesfordel = {};
var fenid;
var fid;
var geocodeinited = false;
var counter = 0;
var styles = [
    {"featureType": "road", "elementType": "geometry.stroke", "stylers": [{"visibility": "off"}]}, {"featureType": "poi.park", "stylers": [{"visibility": "simplified"}, {"lightness": 46}]}, {"featureType": "poi", "elementType": "labels", "stylers": [{"visibility": "on"}]}, {"featureType": "road.highway", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{"color": "#9e9e9f"}]}, {"featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [{"color": "#bfbfbf"}]}, {"featureType": "road.local", "elementType": "geometry.fill", "stylers": [{"color": "#e0e0e0"}]}, {"featureType": "poi.park", "stylers": [{"lightness": 38}]}, {"stylers": [{"saturation": -54}]}];

var editedZoneids = [];

//aluto height adjust
jQuery.noConflict();

jQuery(document).ready(function () {
    //map init	
    initialize();
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

    jQuery(".scrollablediv").height(browserHeight - 200);
    jQuery("#gc-topnav2").draggable();
    BindzoneSearch();

});

function selectall(type)
{
    switch (type)
    {
        case 'fences':
            jQuery(".fence_all").each(function () {
                jQuery(this).prop('checked', true);
            });

            jQuery.each(zonesfordel, function (index, value) {
                markr = markrsfordel[index];
                markr.setMap(map);
                poly = zonesfordel[index];
                poly.setMap(map);
                google.maps.event.addListener(poly, 'click', function (event) {
                    zonesfordel[index].setEditable(true);
                    editedZoneids.push(index);
                });

            });

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

    }
}

function clearall(type)
{
    switch (type)
    {
        case 'fences':
            jQuery(".fence_all").each(function () {
                jQuery(this).prop('checked', false);
            });

            jQuery.each(zonesfordel, function (index, value) {
                markr = markrsfordel[index];
                markr.setMap(null);
                poly = zonesfordel[index];
                poly.setMap(null);
            });

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
            var latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
            map.set('center', latlng);
            map.set('zoom', 15);
            markerlatlng = results[0].geometry.location;
            
            addMarker(latlng,map);
        }
        else
            alert("Please check your address details or contact an Elixir about the issue : " + status);
    });
}

// Adds a marker to the map.
function addMarker(location, map){
  // Add the marker at the clicked location, and add the next-available label
  // from the array of alphabetical characters.
   var image2 = new google.maps.MarkerImage("../../images/marker_icon.png");
  var marker = new google.maps.Marker({
    position: location,
    map: map,
    icon: image2
  });
}

function autocomplete() {

    var input = (document.getElementById('chkA'));
    // Autocomplete Bound To map
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

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
    
    // Checkpoints
    jQuery.ajax({
                type: "POST",
                url: "../common/getcircularzones.php?all=1",
                cache: false,
                success: function (data) {
                    var cdata1 = jQuery.parseJSON(data);
                    var results = cdata1.result;



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
                                fillColor: '#000000',
                                strokeColor: '#000000',
                                strokeweight: 1
                            });

                            circle.bindTo('center', marker, 'position');

                            circle.set("id", device.checkpointid);
                            circlesfordel[id] = circle;

                            marker.setMap(null);
                            circle.setMap(null);

                        } catch (ex) {
                            alert(ex);
                        }
                    });



                }
            });
    
    // fences 
    jQuery.ajax({
        type: "POST",
        url: "../common/getzones.php?",
        cache: false,
        success: function (data) {
            var cdata1 = jQuery.parseJSON(data);

            var results = cdata1.result;
            jQuery.each(results, function (i, zones) {
                //console.log('=============================');console.log(fences);return false;
                var route = [];
                // setting bound
                jQuery.each(zones.zone_bound, function (j, zonesobj) {
                    var zonelatlng = new google.maps.LatLng(zonesobj.geolat, zonesobj.geolong);
                    route.push(zonelatlng);
                });

                //console.log('=============================');console.log(route);return false;

                var boundss = new google.maps.LatLngBounds();
                var f;

                for (f = 0; f < route.length; f++) {
                    boundss.extend(route[f]);
                }

                // The Center of the Polygon
                var markr = new MarkerWithLabel({
                    position: boundss.getCenter(),
                    map: map,
                    labelContent: zones.zonename,
                    labelAnchor: new google.maps.Point(9, 45),
                    labelClass: "mapslabels_fence" // the CSS class for the label
                });

                zid = zones.zoneid;
                markr.set("id", zones.zoneid);
                markrsfordel[zid] = markr;
                markr.setMap(null);

                var poly = new google.maps.Polygon({
                    path: route,
                    strokeWeight: 1,
                    fillColor: '#55FF55',
                    fillOpacity: 0.3,
//                    editable:true
                });

                poly.bindTo('center', markr, 'position');
                zonid = zones.zoneid;
                poly.set("id", zones.zoneid);
                zonesfordel[zonid] = poly;
                poly.setMap(null);

            });


        }
    });
}

function zoneplot(zoneid)
{

    if (jQuery("#fence_" + zoneid).is(':checked') == true)
    {

        marker = markrsfordel[zoneid];

        marker.setMap(map);
        poly = zonesfordel[zoneid];
        poly.setMap(map);
        // Add a listener for the click event.
        google.maps.event.addListener(poly, 'click', function (event) {
            zonesfordel[zoneid].setEditable(true);
            editedZoneids.push(zoneid);
        });
    }
    else
    {
        marker = markrsfordel[zoneid];
        marker.setMap(null);
        poly = zonesfordel[zoneid];
        poly.setMap(null);
    }
}

function refreshmap() {
    initialize();
    clearall('fences');
    clearall('checkpoints');
}

function modifyZone() {

    var editZone = jQuery.unique(editedZoneids);

    var zoneDetails = [];

    jQuery.each(editZone, function (j, value) {
        var vertices = zonesfordel[value].getPath();
        var contentString = [];
        // Iterate over the vertices.
        for (var i = 0; i < vertices.getLength(); i++) {
            var xy = vertices.getAt(i);
            contentString.push(xy.lat() + ',' + xy.lng());
        }
        zoneDetails.push({zoneid: value, latLong: contentString});
    });
    if (zoneDetails.length == 0) {
        alert('Zone not edited');
        return false;
    }
    jQuery.ajax({
        type: 'POST',
        url: 'modifyZone_ajax.php',
        data: "data=" + JSON.stringify(zoneDetails),
        success: function (response) {
            alert(response);
        }
    });

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

function onclicktog() {
    jQuery('#sidebar').toggle(200);
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

function BindzoneSearch() {
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