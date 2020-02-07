var geocodeinited = false;
var map;
var geocoder;
jQuery.noConflict();
var styles = [
    { "featureType": "road", "elementType": "geometry.stroke", "stylers": [{ "visibility": "off" }] }, { "featureType": "poi.park", "stylers": [{ "visibility": "simplified" }, { "lightness": 46 }] }, { "featureType": "poi", "elementType": "labels", "stylers": [{ "visibility": "on" }] }, { "featureType": "road.highway", "elementType": "labels", "stylers": [{ "visibility": "off" }] }, { "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{ "color": "#9e9e9f" }] }, { "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [{ "color": "#bfbfbf" }] }, { "featureType": "road.local", "elementType": "geometry.fill", "stylers": [{ "color": "#e0e0e0" }] }, { "featureType": "poi.park", "stylers": [{ "lightness": 38 }] }, { "stylers": [{ "saturation": -54 }] }
];
jQuery(document).ready(function() {
    loaded();
    var browserHeight = jQuery(window).height();
    jQuery('.post').css("padding", 0);
    jQuery('.entry').css("padding", 0);
    jQuery("#gc-topnav2").draggable();
    jQuery('#map').css("height", browserHeight - 250);
    /* Opening modal on click for checkpoint creation code starts here */
    jQuery('#checkpointCreateModal').click(function() {
        jQuery('#checkPointCreationmodal').modal('show')
    });
    /* Opening modal on click for checkpoint creation code ends here */
});

function submitcheckpoint() {
    var chk = jQuery("#chkName").val();
    if (jQuery("#phonenumber").val().replace(/[^a-z]/g, "").length > 0) {
        alert("Please enter phone number");
    } else if (chk == "") {
        alert("Please enter checkpoint name");
    } else if (chk.match("'")) {
        alert("Special cherecters not allowed in checkpoint name");
    } else {
        var chkN = jQuery("#chkName").val();
        jQuery.ajax({
            type: "POST",
            url: "route_ajax.php",
            async: true,
            data: {
                chkN: chkN
            },
            cache: false,
            success: function(statuscheck) {
                if (statuscheck == "ok") {
                    jQuery("#createchk").submit();
                } else {
                    alert("Checkpoint Already exists.");
                }
            }
        });
    }
}

function showform() {
    jQuery('#gc-topnav2').css("display", "block");
    jQuery('#map').css("z-index", "-1000");
    jQuery('#gc-topnav2').css("z-index", "100");
    jQuery('#toggler').val("Hide");
    jQuery('#toggler').css("background", "#000");
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
        vars[key] = value;
    });
    return vars;
}

function initialize() {
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });
    var mumbai = new google.maps.LatLng(19.03590687086149, 72.94649211215824);
    var mapOptions = {
        zoom: 8,
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
}
// Place AutoComplete Widget
function autocomplete() {
    var input = /** @type {HTMLInputElement} */ (
        document.getElementById('chkA'));
    // Autocomplete Bound To map
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        //infowindow.close();
        locate();
        /**
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
          return;
        }
          */
        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17); // Why 17? Because it looks good.
        }
        marker.setIcon( /** @type {google.maps.Icon} */ ({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
        }));
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);
        var address = '';
        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
        infowindow.open(map, marker);
    });
    google.maps.event.addDomListener(window, 'load', initialize);
}
// Distance Widget
function DistanceWidget(map) {
    this.set('map', map);
    this.set('position', map.getCenter());
    var image = new google.maps.MarkerImage("../../images/alldragblk.png",
        new google.maps.Size(16, 16),
        new google.maps.Point(0, 0),
        new google.maps.Point(8, 8));
    var marker = new google.maps.Marker({
        draggable: true,
        title: 'Click and Drag to change the Position',
        icon: image
    });
    // Bind the marker map property to the DistanceWidget map property
    marker.bindTo('map', this);
    // Bind the marker position property to the DistanceWidget position
    // property
    marker.bindTo('position', this);
    // Create a new radius widget
    var radiusWidget = new RadiusWidget();
    // Bind the radiusWidget map to the DistanceWidget map
    radiusWidget.bindTo('map', this);
    // Bind the radiusWidget center to the DistanceWidget position
    radiusWidget.bindTo('center', this, 'position');
    // Bind to the radiusWidgets' distance property
    this.bindTo('distance', radiusWidget);
    // Bind to the radiusWidgets' bounds property
    this.bindTo('bounds', radiusWidget);
}
DistanceWidget.prototype = new google.maps.MVCObject();
// Radius Widget
/**
 * A radius widget that add a circle to a map and centers on a marker.
 *
 * @constructor
 */
function RadiusWidget() {
    var circle = new google.maps.Circle({
        strokeWeight: 2,
        //    fillColor: '#0193CC',
        //    strokeColor: '#0193CC'
        fillColor: '#000000',
        strokeColor: '#000000'
    });
    // Set the distance property value, default to 50km.
    this.set('distance', 1);
    // Bind the RadiusWidget bounds property to the circle bounds property.
    this.bindTo('bounds', circle);
    // Bind the circle center to the RadiusWidget center property
    circle.bindTo('center', this);
    // Bind the circle map to the RadiusWidget map
    circle.bindTo('map', this);
    // Bind the circle radius property to the RadiusWidget radius property
    circle.bindTo('radius', this);
    this.addSizer_();
}
RadiusWidget.prototype = new google.maps.MVCObject();
/**
 * Update the radius when the distance has changed.
 */
RadiusWidget.prototype.distance_changed = function() {
    this.set('radius', this.get('distance') * 1000);
};
// Radius Resizer
/**
 * Add the sizer marker to the map.
 *
 * @private
 */
RadiusWidget.prototype.addSizer_ = function() {
    var image = new google.maps.MarkerImage("../../images/hordragblk.png",
        new google.maps.Size(16, 16),
        new google.maps.Point(0, 0),
        new google.maps.Point(8, 8));
    var sizer = new google.maps.Marker({
        draggable: true,
        title: 'Click and Drag to change the Radius',
        icon: image
    });
    sizer.bindTo('map', this);
    sizer.bindTo('position', this, 'sizer_position');
    var me = this;
    google.maps.event.addListener(sizer, 'drag', function() {
        // Set the circle distance (radius)
        me.setDistance();
    });
};
/**
 * Update the center of the circle and position the sizer back on the line.
 *
 * Position is bound to the DistanceWidget so this is expected to change when
 * the position of the distance widget is changed.
 */
RadiusWidget.prototype.center_changed = function() {
    var bounds = this.get('bounds');
    // Bounds might not always be set so check that it exists first.
    if (bounds) {
        var lng = bounds.getNorthEast().lng();
        // Put the sizer at center, right on the circle.
        var position = new google.maps.LatLng(this.get('center').lat(), lng);
        this.set('sizer_position', position);
    }
};
RadiusWidget.prototype.distanceBetweenPoints_ = function(p1, p2) {
    if (!p1 || !p2) {
        return 0;
    }
    var R = 6371; // Radius of the Earth in km
    var dLat = (p2.lat() - p1.lat()) * Math.PI / 180;
    var dLon = (p2.lng() - p1.lng()) * Math.PI / 180;
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(p1.lat() * Math.PI / 180) * Math.cos(p2.lat() * Math.PI / 180) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c;
    return d;
};
/**
 * Set the distance of the circle based on the position of the sizer.
 */
RadiusWidget.prototype.setDistance = function() {
    // As the sizer is being dragged, its position changes.  Because the
    // RadiusWidget's sizer_position is bound to the sizer's position, it will
    // change as well.
    var pos = this.get('sizer_position');
    var center = this.get('center');
    var distance = this.distanceBetweenPoints_(center, pos);
    // Set the distance property for any objects that are bound to it
    this.set('distance', distance);
};

function initmap(lat, lng) {
    var latlng = new google.maps.LatLng(lat, lng);
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });
    var mapOptions = {
        zoom: 15,
        center: latlng,
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
    var distanceWidget = new DistanceWidget(map);
    jQuery("#cgeolat").val(lat);
    jQuery("#cgeolong").val(lng);
    jQuery("#crad").val(1);
    google.maps.event.addListener(distanceWidget, 'distance_changed', function() {
        displayInfo(distanceWidget);
    });
    google.maps.event.addListener(distanceWidget, 'position_changed', function() {
        displayInfo(distanceWidget);
    });
}

function displayInfo(widget) {
    var info = document.getElementById('info');
    var rawradius = widget.get('distance');
    var radius = parseFloat(rawradius).toFixed(2);
    info.innerHTML = 'Radius: ' +
        radius + " km";
    jQuery("#cgeolat").val(widget.get('position').lat());
    jQuery("#cgeolong").val(widget.get('position').lng());
    jQuery("#crad").val(radius);
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
            initmap(results[0].geometry.location.lat(), results[0].geometry.location.lng());
            markerlatlng = results[0].geometry.location;
            jQuery("#address").hide();
            jQuery("#chkA").hide();
            jQuery("#locateinp").hide();
            jQuery("#createinp").show();
            jQuery("#chkName").show();
            jQuery("#chkptname").show();
            jQuery('#gc-topnav2').css("width", "580px");
            jQuery("#create_table").show();
        } else
            alert("Please check your address details or contact an Elixir about the issue : " + status);
    });
}

function loaded() {
    initialize();
    jQuery('#gc-topnav2').css("display", "block");
    jQuery('#gc-topnav2').css("z-index", "100");
    jQuery('#toggler').val("Hide");
    jQuery('#toggler').css("background", "#000");
    jQuery('#createinp').hide();
    jQuery("#chkName").hide();
    jQuery("#chkptname").hide();
    jQuery("#create_table").hide();
}

function VehicleForRoute() {
    var vehicleid = jQuery('#vehicleroute').val();
    if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
        var selected_name = jQuery("#vehicleroute option:selected").text();
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function() {
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
        remove_image.onclick = function() {
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
    for (var i = 0; i < jQuery('#vehicleroute option').length; i++) {
        jQuery("#vehicleroute").prop("selectedIndex", i);
        VehicleForRoute();
    }
}

function removeVehicle(vehicleid) {
    jQuery('#to_vehicle_div_' + vehicleid).remove();
}

function createCheckPointTypeByAjax() {
    var checkPointType = jQuery("#checkPointType").val();
    if (checkPointType === '' || checkPointType === null || checkPointType === undefined) {
        alert("Please enter checkpoint type");
    } else {
        jQuery.ajax({
            type: "POST",
            url: "route_ajax.php",
            async: true,
            data: {
                chkN: checkPointType
            },
            cache: false,
            success: function(statuscheck) {
                if (statuscheck === "ok") {
                    //jQuery("#createchk").submit();
                    createCheckPointAfterValidation(checkPointType);
                    updateCheckpointTypes();
                    jQuery('#checkPointCreationmodal').modal('hide');
                    //console.log("Here in success and checkpoint type is new: "+statuscheck);                    
                } else {
                    alert("Checkpoint Already exists.");
                }
            }
        });
    }
}

function createCheckPointAfterValidation(checkPointType) {
    var action = "ajaxCheckPointTypeCreation";
    jQuery.ajax({
        type: "POST",
        url: "checkpoint_functions.php",
        async: true,
        data: {
            chkN: checkPointType,
            action: action
        },
        cache: false,
        success: function(statuscheck) {
            alert("Checkpoint type Added successfully.");
        }
    });
}

function updateCheckpointTypes() {
    var action = "updateCheckpointTypes";
    jQuery.ajax({
                type: "POST",
                url: "checkpoint_functions.php",
                async: true,
                data: {
                    action: action
                },
                cache: false,
                success: function(data) {
                        console.log("Data from ajax response is : " + data);
                    $("#chktypes").html(data);
                 } 
    });  
}