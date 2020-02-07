// For Checkpoint
var gmapsinited = false;
var gmapsinitedsender = false;
var geocodeinited = false;
var geocodeinitedsender = false;
var map;
var mapsender;
var geocoder;
var geocodersender;
var eviction_list = [];
var counter = 2;
var markerlatlng;
// For Fence
var fcounter = 0;
var drawingManager;
var selectedShape;
//var colors = ['#000000', '#0099FF', '#AC020F'];
var colors = ['#000000'];
var selectedColor;
var colorButtons = {};
var fencename;
var latitudes = "";
var longitudes = "";
//var counter = 0;
// Style
var styles = [
    { "featureType": "road", "elementType": "geometry.stroke", "stylers": [{ "visibility": "off" }] }, { "featureType": "poi.park", "stylers": [{ "visibility": "simplified" }, { "lightness": 46 }] }, { "featureType": "poi", "elementType": "labels", "stylers": [{ "visibility": "on" }] }, { "featureType": "road.highway", "elementType": "labels", "stylers": [{ "visibility": "off" }] }, { "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [{ "color": "#9e9e9f" }] }, { "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [{ "color": "#bfbfbf" }] }, { "featureType": "road.local", "elementType": "geometry.fill", "stylers": [{ "color": "#e0e0e0" }] }, { "featureType": "poi.park", "stylers": [{ "lightness": 38 }] }, { "stylers": [{ "saturation": -54 }] }
];
jQuery.noConflict();
jQuery(document).ready(function() {
    var browserHeight = jQuery(window).height();
    jQuery('.post').css("padding", 0);
    jQuery('.entry').css("padding", 0);
    jQuery("#gc-topnav2").draggable();
    jQuery('#map_chkpoint').css("height", browserHeight - 250);
    /*date: 25th oct 14, ak added*/
    jQuery('.add_button').click(function() {
        var idval = jQuery(this).attr('href');
        var this_id = idval.replace('#test_', '');
        create_map_for_modal_report(this_id);
    });
    /**/
});
// jQuery ok
/*dt: 17th nov 14, ak added*/
function autocomplete(id) {
    var id_chk = (id == undefined) ? 'chkA' : id;
    var input = (document.getElementById(id_chk));
    // Autocomplete Bound To map
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });
    /*google.maps.event.addListener(autocomplete, 'place_changed', function() {
        locate();
    });*/
}
/**/
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

function initmap_new(lat, lng) {
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
    map = new google.maps.Map(document.getElementById('map_chkpoint'), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    autocomplete();
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

function create_map_for_modal(id) {
    jQuery('#addcheckpoint').modal('hide');
    periodic = false;
    var browserHeight = jQuery(window).height();
    var browserWidth = jQuery(window).width();
    jQuery('.post').css("padding", 0);
    jQuery('.entry').css("padding", 0);
    jQuery("#gc-topnav22").draggable();
    jQuery('#map_chkpoint').css("height", browserHeight - 250);
    jQuery('#map_chkpoint').css("width", browserWidth - 250);
    var str = jQuery("#latlong" + id).val();
    jQuery('#gc-topnav22').css("position", "absolute");
    jQuery('#gc-topnav22').css("top", "70%");
    jQuery('#gc-topnav22').css("left", "2%");
    jQuery('#gc-topnav22').css("display", "block");
    jQuery('#gc-topnav22').css("z-index", "1000");
    jQuery("#gc-topnav22").draggable();
    var partsOfStr = str.split(',');
    var latlng = new google.maps.LatLng(partsOfStr[0], partsOfStr[1]);
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });
    var mapOptions = {
        zoom: 12,
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
    map = new google.maps.Map(document.getElementById('map_chkpoint'), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    jQuery(".popup").show();
    jQuery(".overlay").show();
    jQuery('#toggler1').hide();
    showformForCreateCheckpt();
    jQuery("#chkRadField").show();
    jQuery("#locateinp").css("display", "none");
    jQuery("#chkName").show();
    jQuery("#chkptname").show();
    initmap_new(partsOfStr[0], partsOfStr[1]);
    markerlatlng = latlng;
}

function create_map_for_new_location() {
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
    map = new google.maps.Map(document.getElementById('map_chkpoint'), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    jQuery("#chkRadField").hide();
    jQuery("#locateinp").css("display", "block");
    jQuery("#address").css("display", "block");
    jQuery("#chkA").css("display", "block");
    jQuery("#chkName").hide();
    jQuery("#chkptname").hide();
    jQuery("#vehicle_selected").hide();
    jQuery("#add_location_div").hide();
    jQuery("#toggler3").hide();
    jQuery("#createinp").hide();
}

function locatechkp() {
    address = jQuery("#chkA").val();
    if (!geocodeinited) {
        geocoder = new google.maps.Geocoder();
        geocodeinited = true;
    }
    geocoder.geocode({
        'address': address
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            initmap_new(results[0].geometry.location.lat(), results[0].geometry.location.lng());
            markerlatlng = results[0].geometry.location;
            jQuery("#chkRadField").show();
            jQuery("#locateinp").css("display", "none");
            jQuery("#address").css("display", "none");
            jQuery("#chkA").css("display", "none");
            jQuery("#chkName").show();
            jQuery("#chkptname").show();
            jQuery("#vehicle_selected").show();
            jQuery("#toggler3").show();
            jQuery("#createinp").show();
            jQuery("#add_location_div").show();
        } else
            alert("Please check your address details or contact an Elixir about the issue : " + status);
    });
}

function showformForCreateCheckpt() {
    jQuery('#gc-topnav2').css("display", "block");
    jQuery('#map_chkpoint').css("z-index", "-1000");
    jQuery('#gc-topnav2').css("z-index", "1000");
    jQuery('#gc-topnav2').css("height", "inherit");
    jQuery('#gc-topnav2').css("left", "3%");
    jQuery('#gc-topnav2').css("top", "60%");
    jQuery('#toggler').val("Hide");
    jQuery('#toggler').css("background", "#000");
    AddedVehicleForCheckpoint();
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
        vars[key] = value;
    });
    return vars;
}

function initmap(lat, lng) {
    if (gmapsinited) return;
    var latlng = new google.maps.LatLng(lat, lng);
    var styledMap = new google.maps.StyledMapType(styles, { name: "Styled Map" });
    var mapOptions = {
        zoom: 12,
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
    map = new google.maps.Map(document.getElementById('map_chkpoint'), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    gmapsinited = true;
    google.maps.event.addListener(map, 'click', function(event) {
        evictMarkers();
        var marker = new google.maps.Marker({
            map: map,
            draggable: false,
            animation: google.maps.Animation.DROP,
            position: event.latLng
        });
        jQuery("#cgeolat").val(event.latLng.lat());
        jQuery("#cgeolong").val(event.latLng.lng());
        map.setCenter(marker.getPosition());
        var rad = jQuery("#chkRad").val()
        if (rad != "" || rad != 0) {
            rad = rad * 1000;
            var circle = new google.maps.Circle({
                map: map,
                radius: rad,
                fillColor: '#AA0000',
                strokeColor: '#AA0000',
                strokeweight: 1
            });
            circle.bindTo('center', marker, 'position');
            google.maps.event.addListener(circle, 'click', function() {
                circle.setMap(null);
            });
            eviction_list.push(circle);
        }
        eviction_list.push(marker);
    });
    google.maps.event.addDomListener(document.getElementById('chkRad'), 'change', function() {
        evictMarkers();
        var myLatLng = new google.maps.LatLng(jQuery("#cgeolat").val(), jQuery("#cgeolong").val());
        var marker = new google.maps.Marker({
            map: map,
            draggable: false,
            animation: google.maps.Animation.DROP,
            position: myLatLng
        });
        var rad = jQuery("#chkRad").val()
        if (rad != "" || rad != 0) {
            rad = rad * 1000;
            var circle = new google.maps.Circle({
                map: map,
                radius: rad,
                fillColor: '#AA0000',
                strokeColor: '#AA0000',
                strokeweight: 1
            });
            circle.bindTo('center', marker, 'position');
            google.maps.event.addListener(circle, 'click', function() {
                circle.setMap(null);
            });
            eviction_list.push(circle);
        }
        eviction_list.push(marker);
    });
}

function initmapsender(lat, lng) {
    if (gmapsinitedsender) return;
    var latlng = new google.maps.LatLng(lat, lng);
    var styledMap = new google.maps.StyledMapType(styles, { name: "Styled Map" });
    var mapOptions = {
        zoom: 12,
        center: latlng,
        panControl: true,
        streetViewControl: false,
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
        mapTypeControl: true,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
        },
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL
        },
        styles: styles
    };
    map = new google.maps.Map(document.getElementById('map_chkpoint'), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    gmapsinitedsender = true;
}

function commaConcat(str, sval) {
    if (sval != "") {
        if (str != "")
            return str + ", " + sval;
        else
            return sval;
    }
    return str;
}
//function locate() {
//  evictMarkers();
//  var address = "";
//  address = commaConcat(address, jQuery("#chkA").val());
//  address = commaConcat(address, jQuery("#chkT").val());
//  address = commaConcat(address, jQuery("#chkRN").val());
//  address = commaConcat(address, jQuery("#chkC").val());
//  address = commaConcat(address, jQuery("#chkS").val());
//  address = commaConcat(address, jQuery("#chkZC").val());
//  var image = new google.maps.MarkerImage('../../images/flag.png',
//    new google.maps.Size(16, 16),
//    new google.maps.Point(0, 0),
//    new google.maps.Point(16, 26),
//    new google.maps.Size(16, 16));
//  if (!geocodeinited) {
//    geocoder = new google.maps.Geocoder();
//    geocodeinited = true;
//  }
//  geocoder.geocode({
//    'address': address
//  }, function (results, status) {
//    if (status == google.maps.GeocoderStatus.OK) {
//      initmap(results[0].geometry.location.lat(), results[0].geometry.location.lng());
//      markerlatlng = results[0].geometry.location;
//      var marker = new google.maps.Marker({
//        map: map,
//        draggable: true,
//        animation: google.maps.Animation.DROP,
//        title: address,
//        position: results[0].geometry.location
//      });
//      jQuery("#cgeolat").val(marker.getPosition().lat()) ;
//      jQuery("#cgeolong").val(marker.getPosition().lng()) ;
//      map.setCenter(marker.getPosition());
//      eviction_list.push(marker);
//      hideform();
//      //jQuery('radius').show();
//      //jQuery('#radius').fadeOut(5000);
//      jQuery("#chkRadField").show();
//      jQuery("#chkRadTd").show();
//      jQuery("#locateinp").css("display", "none");
//      jQuery("#checkpts").css("display", "block");
//    } else
//      alert("Please check your address details or contact an Elixir about the issue : " + status);
//  });
//}
function hideform() {
    jQuery("#gc-topnav2").hide();
    jQuery('#map_chkpoint').css("z-index", "+1000");
    jQuery('#toggler1').hide();
    jQuery('#clearb').hide();
    jQuery('#toggler2').show();
    jQuery('#gc-topnav2').css("height", "145px");
    counter++;
}

function evictMarkers() {
    // clear all markers
    eviction_list.forEach(function(item) {
        item.setMap(null)
    });
    // reset the eviction array 
    eviction_list = [];
}

function VehicleForCheckpoint() {
    var vehicleid = jQuery('#vehicleid1').val();
    if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
        var selected_name = jQuery('#vehicleid1 option:selected').text();
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
        jQuery('#vehicle_list1').append(div);
        jQuery(div).append(remove_image);
    }
    jQuery('#vehicleid1').val(0);
}

function removeVehicle(vehicleid) {
    jQuery('#to_vehicle_div_' + vehicleid).remove();
}

function AddedVehicleForCheckpoint() {
    var vehicleid = jQuery('#chk_vehid').val();
    var selected_name = jQuery('#vehicleid1 option[value=' + vehicleid + ']').text();
    if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
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
        jQuery('#vehicle_list1').append(div);
        jQuery(div).append(remove_image);
    }
    jQuery('#vehicleid1').val(0);
}

function addallvehicleForCheckpoint() {
    jQuery("#vehicleid1 option").each(function(index, element) {
        jQuery("#vehicleid1").val(jQuery(element).val());
        VehicleForCheckpoint();
    });
}

function remove_addedVehicle(vehicleid) {
    jQuery('#to_vehicle_div_' + vehicleid).remove();
}

function addcheckpointtovehicle() {
    var checkpointid = jQuery('#checkpointid').val();
    if (checkpointid > -1 && jQuery('#to_chkpt_div_' + checkpointid).val() == null) {
        var selected_name = jQuery('#checkpointid option:selected').text();
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function() {
            removeChkpt(checkpointid);
        };
        div.className = 'recipientbox';
        div.id = 'to_chkpt_div_' + checkpointid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="c_list_element" name="to_chkpt_' + checkpointid + '" value="' + checkpointid + '"/>';
        jQuery('#chkpt_list').append(div);
        jQuery(div).append(remove_image);
    }
    jQuery('#checkpointid').val(0);
}

function addedcheckpoint() {
    jQuery('.chk_id').each(function() {
        var checkpointid = this.id;
        var selected_name = jQuery('#checkpointid option[value=' + checkpointid + ']').text();
        if (checkpointid > -1 && jQuery('#to_chkpt_div_' + checkpointid).val() == null) {
            var div = document.createElement('div');
            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';
            remove_image.onclick = function() {
                removeChkpt(checkpointid);
            };
            div.className = 'recipientbox';
            div.id = 'to_chkpt_div_' + checkpointid;
            div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_added_chkpt_' + checkpointid + '" value="' + checkpointid + '"/>';
            jQuery('#chkpt_list').append(div);
            jQuery(div).append(remove_image);
        }
        jQuery('checkpointid').val(0);
    });
}

function addallCheckpointForVehicle() {
    jQuery("#checkpointid option").each(function(index, element) {
        jQuery("#checkpointid").val(jQuery(element).val());
        var checkpointid = jQuery('#checkpointid').val();
        if (checkpointid > -1 && jQuery('#to_chkpt_div_' + checkpointid).val() == null) {
            var selected_name = jQuery(" #checkpointid option:selected").text();
            var div = document.createElement('div');
            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';
            remove_image.onclick = function() {
                removeChkpt(checkpointid);
            };
            div.className = 'recipientbox';
            div.id = 'to_chkpt_div_' + checkpointid;
            div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="c_list_element" name="to_chkpt_' + checkpointid + '" value="' + checkpointid + '"/>';
            jQuery('#chkpt_list').append(div);
            jQuery(div).append(remove_image);
        }
    });
    jQuery('checkpointid').val(0);
}

function removeChkpt(checkpointid) {
    jQuery('#to_chkpt_div_' + checkpointid).remove();
}
// Fence
function clearSelection() {
    if (selectedShape) {
        selectedShape.setEditable(false);
        selectedShape = null;
    }
}

function setSelection(shape) {
    clearSelection();
    selectedShape = shape;
    shape.setEditable(true);
    selectColor(shape.get('fillColor') || shape.get('strokeColor'));
}

function deleteSelectedShape() {
    checkpointform();
    colors = "";
    initialize();
    if (selectedShape) {
        selectedShape.setMap(null);
    }
}

function selectColor(color) {
    //  selectedColor = color;
    //  for (var i = 0; i < colors.length; ++i) {
    //    var currColor = colors[i];
    //    colorButtons[currColor].style.border = currColor == color ? '2px solid #789' : '2px solid #fff';
    //  }
    // Retrieves the current options from the drawing manager and replaces the
    // stroke or fill color as appropriate.
    var polylineOptions = drawingManager.get('polylineOptions');
    polylineOptions.strokeColor = color;
    drawingManager.set('polylineOptions', polylineOptions);
    var rectangleOptions = drawingManager.get('rectangleOptions');
    rectangleOptions.fillColor = color;
    drawingManager.set('rectangleOptions', rectangleOptions);
    var circleOptions = drawingManager.get('circleOptions');
    circleOptions.fillColor = color;
    drawingManager.set('circleOptions', circleOptions);
    var polygonOptions = drawingManager.get('polygonOptions');
    polygonOptions.fillColor = color;
    drawingManager.set('polygonOptions', polygonOptions);
}

function setSelectedShapeColor(color) {
    if (selectedShape) {
        if (selectedShape.type == google.maps.drawing.OverlayType.POLYLINE) {
            selectedShape.set('strokeColor', color);
        } else {
            selectedShape.set('fillColor', color);
        }
    }
}

function makeColorButton(color) {
    var button = document.createElement('span');
    button.className = 'color-button';
    button.style.backgroundColor = color;
    google.maps.event.addDomListener(button, 'click', function() {
        selectColor(color);
        setSelectedShapeColor(color);
    });
    return button;
}

function buildColorPalette() {
    var colorPalette = document.getElementById('color-palette');
    for (var i = 0; i < colors.length; ++i) {
        var currColor = colors[i];
        var colorButton = makeColorButton(currColor);
        jQuery("#color-palette").append(colorButton);
        colorButtons[currColor] = colorButton;
    }
    selectColor(colors[0]);
}

function initialize() {
    var browserWidth = jQuery(window).width();
    var browserHeight = jQuery(window).height();
    jQuery('#map_fence').css("height", browserHeight - 250);
    jQuery('#map_fence').css("width", browserWidth - 290);
    jQuery('.entry').css("padding", 0);
    jQuery('#wrapper').css("min-height", 0);
    jQuery('.post').css("margin-bottom", 0);
    var styledMap = new google.maps.StyledMapType(styles, { name: "Styled Map" });
    var mapOptions = {
        zoom: 12,
        draggableCursor: 'crosshair',
        center: new google.maps.LatLng(19.07, 72.89),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true,
        zoomControl: true,
        mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
    };
    var map = new google.maps.Map(document.getElementById('map_fence'), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    var polyOptions = {
        strokeWeight: 1,
        fillOpacity: 0.60,
        editable: true
    };
    // Creates a drawing manager attached to the map that allows the user to draw
    // markers, lines, and shapes.
    drawingManager = new google.maps.drawing.DrawingManager({
        drawingControl: false,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: [google.maps.drawing.OverlayType.POLYGON]
        },
        drawingMode: google.maps.drawing.OverlayType.POLYGON,
        markerOptions: {
            draggable: true
        },
        polylineOptions: {
            editable: true
        },
        rectangleOptions: polyOptions,
        circleOptions: polyOptions,
        polygonOptions: polyOptions,
        map: map
    });
    google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
        if (e.type != google.maps.drawing.OverlayType.MARKER) {
            // Switch back to non-drawing mode after drawing a shape.
            drawingManager.setDrawingMode(null);
            // Add an event listener that selects the newly-drawn shape when the user
            // mouses down on it.
            var newShape = e.overlay;
            newShape.type = e.type;
            google.maps.event.addListener(newShape, 'click', function() {
                setSelection(newShape);
            });
            setSelection(newShape);
        }
    });
    // Clear the current selection when the drawing mode is changed, or when the
    // map is clicked.
    google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
    google.maps.event.addListener(map, 'click', clearSelection);
    google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape);
    google.maps.event.addDomListener(document.getElementById('CoordsButton'), 'click', gerSelectedShape);
    buildColorPalette();
    google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
        var coordinates = (polygon.getPath().getArray());
        checkpointform();
    });
    google.maps.event.addListener(drawingManager, 'PolyMouseEvent', function(polygon) {
        var coordinates = (polygon.getPath().getArray());
        checkpointform();
    });

    function gerSelectedShape() {
        var coordinates = (selectedShape.getPath().getArray());
        checkpointform();
    }
}

function savefence(id) {
    fencename = jQuery("#fenceName").val();
    if (fencename != "") {
        datapush(id);
    } else {
        jQuery('#fenceName').css("border-color", "#AC020F");
    }
}

function checkpointform() {
    if (fencename != "") {
        jQuery("#chkp_inp").val(fencename);
    }
    jQuery('.ch_bar').toggle(0, function() {
        if (fcounter % 2 == 0) {
            jQuery('#map_fence').css("z-index", "-1000");
            jQuery('#gc-topnav2').css("position", "absolute");
            jQuery('#gc-topnav2').css("top", "50%");
            jQuery('#gc-topnav2').css("left", "30%");
            fcounter++;
        } else {
            jQuery('#map_fence').css("z-index", "+1000");
            fcounter++;
        }
    });
}

function editform() {
    jQuery('#CoordsButton').css("display", "block");
    if (fencename != "") {
        jQuery("#chkp_inp").val(fencename);
    }
    jQuery('.ch_bar').toggle(0, function() {
        if (fcounter % 2 == 0) {
            jQuery('#map_fence').css("z-index", "-1000");
            jQuery('#gc-topnav2').css("position", "absolute");
            jQuery('#gc-topnav2').css("top", "30%");
            jQuery('#gc-topnav2').css("left", "30%");
            fcounter++;
        } else {
            jQuery('#map_fence').css("z-index", "+1000");
            fcounter++;
        }
    });
}

function datapush(id) {
    var vehicleid = id;
    var coordinates = (selectedShape.getPath().getArray());
    jQuery.each(coordinates, function(key, value) {
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
    var data = jQuery('#createfence').serialize();
    var datastr = data + '&lt=' + latitudes + '&lng=' + longitudes;
    jQuery.ajax({
        type: "POST",
        data: datastr,
        cache: false,
        url: "fence_ajax.php",
        success: function(statuscheck) {
            //alert(statuscheck);
            if (statuscheck == "ok") {
                jQuery(".popup").hide();
                jQuery(".overlay").hide();
                jQuery("#createchk").trigger(":reset");
                jQuery("#gc-topnav2").hide();
                jQuery("#toggler1").show();
                jQuery("#toggler2").hide();
                call_row(vehicleid);
                call_row(vehicleid);
            } else if (statuscheck == "detailsreqd") {
                jQuery("#fencename").show();
                jQuery("#fencename").fadeOut(3000);
            } else if (statuscheck == "samename") {
                jQuery("#samename").show();
                jQuery("#samename").fadeOut(3000);
            } else {
                jQuery("#fencename").show();
                jQuery("#fencename").fadeOut(3000);
            }
        }
    });
}

function create_map_for_modal_fence(id) {
    jQuery('#addfence').modal('hide');
    var browserHeight = jQuery(window).height();
    var browserWidth = jQuery(window).width();
    jQuery('.post').css("padding", 0);
    jQuery('.entry').css("padding", 0);
    jQuery("#gc-topnavfence").draggable();
    jQuery('#map_fence').css("height", browserHeight - 250);
    jQuery('#map_fence').css("width", browserWidth - 250);
    jQuery('#wrapper').css("min-height", 0);
    jQuery('#gc-topnavfence').css("display", "block");
    jQuery('#gc-topnavfence').css("z-index", "1000");
    jQuery("#gc-topnavfence").draggable();
    jQuery('#gc-topnavfence').css("position", "absolute");
    jQuery('#gc-topnavfence').css("top", "50%");
    jQuery('#gc-topnavfence').css("left", "30%");
    jQuery("#selectvehicle").show();
    initialize_fence(id);
    AddedVehicleForFence();
}

function initialize_fence(id) {
    periodic = false;
    jQuery('#toggler').val("Hide");
    jQuery('#toggler').css("background", "#000");
    //jQuery('#createinp').hide();
    //jQuery("#chkptname").hide();
    //jQuery('.post').css("margin-bottom", 0);
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });
    var str = jQuery("#latlong" + id).val();
    var partsOfStr = str.split(',');
    var latlng = new google.maps.LatLng(partsOfStr[0], partsOfStr[1]);
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
    map = new google.maps.Map(document.getElementById('map_fence'), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    var polyOptions = {
        strokeWeight: 1,
        fillOpacity: 0.60,
        editable: true
    };
    // Creates a drawing manager attached to the map that allows the user to draw
    // markers, lines, and shapes.
    drawingManager = new google.maps.drawing.DrawingManager({
        drawingControl: false,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: [google.maps.drawing.OverlayType.POLYGON]
        },
        drawingMode: google.maps.drawing.OverlayType.POLYGON,
        markerOptions: {
            draggable: true
        },
        polylineOptions: {
            editable: true
        },
        rectangleOptions: polyOptions,
        circleOptions: polyOptions,
        polygonOptions: polyOptions,
        map: map
    });
    google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
        if (e.type != google.maps.drawing.OverlayType.MARKER) {
            // Switch back to non-drawing mode after drawing a shape.
            drawingManager.setDrawingMode(null);
            // Add an event listener that selects the newly-drawn shape when the user
            // mouses down on it.
            var newShape = e.overlay;
            newShape.type = e.type;
            google.maps.event.addListener(newShape, 'click', function() {
                setSelection(newShape);
            });
            setSelection(newShape);
        }
    });
    // Clear the current selection when the drawing mode is changed, or when the
    // map is clicked.
    google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
    google.maps.event.addListener(map, 'click', clearSelection);
    //  buildColorPalette();
    google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
        var coordinates = (polygon.getPath().getArray());
        //    checkpointform();
    });
    google.maps.event.addListener(drawingManager, 'PolyMouseEvent', function(polygon) {
        var coordinates = (polygon.getPath().getArray());
        //    checkpointform();
    });
}

function create_map_to_locate_fence(id) {
    jQuery("#selectvehicle").hide();
    jQuery("#sel_veh_fence").hide();
    jQuery("#vehicleid_fence").hide();
    jQuery("#addall").hide();
    jQuery("#fenceName").hide();
    jQuery("#fencname").css("display", "none");
    jQuery("#locatenewfence").css("display", "none");
    jQuery("#createinpfence").css("display", "none");
    jQuery("#addressfence").css("display", "block");
    jQuery('#locateinpfence').css("display", "block");
    jQuery("#fenceA").show();
    autocomplete('fenceA');
}
var address;

function locatefence() {
    address = jQuery("#fenceA").val();
    if (!geocodeinited) {
        geocoder = new google.maps.Geocoder();
        geocodeinited = true;
    }
    geocoder.geocode({
        'address': address
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            initmap_fence(results[0].geometry.location.lat(), results[0].geometry.location.lng());
            markerlatlng = results[0].geometry.location;
            jQuery("#addressfence").hide();
            jQuery("#fenceA").hide();
            jQuery("#locateinpfence").hide();
            jQuery("#locatenewfence").css("display", "block");
            jQuery("#createinpfence").show();
            jQuery("#fenceName").show();
            jQuery("#fencname").show();
            jQuery("#addall").show();
            jQuery("#selectvehicle").show();
            jQuery("#sel_veh_fence").show();
            jQuery("#vehicleid_fence").show();
        } else
            alert("Please check your address details or contact an Elixir about the issue : " + status);
    });
}

function initmap_fence(lat, lng) {
    var latlng = new google.maps.LatLng(lat, lng);
    jQuery('#gc-topnavfence').css("display", "block");
    jQuery('#gc-topnavfence').css("z-index", "1000");
    jQuery("#gc-topnavfence").draggable();
    jQuery('#toggler').val("Hide");
    jQuery('#toggler').css("background", "#000");
    var browserWidth = jQuery(window).width();
    var browserHeight = jQuery(window).height();
    jQuery('#map_fence').css("height", (browserHeight - 234));
    jQuery('.entry').css("padding", 0);
    jQuery('#wrapper').css("min-height", 0);
    jQuery('.post').css("margin-bottom", 0);
    jQuery('#map_fence').css("width", browserWidth - 8);
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
    map = new google.maps.Map(document.getElementById('map_fence'), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    var polyOptions = {
        strokeWeight: 1,
        fillOpacity: 0.60,
        editable: true
    };
    // Creates a drawing manager attached to the map that allows the user to draw
    // markers, lines, and shapes.
    drawingManager = new google.maps.drawing.DrawingManager({
        drawingControl: false,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: [google.maps.drawing.OverlayType.POLYGON]
        },
        drawingMode: google.maps.drawing.OverlayType.POLYGON,
        markerOptions: {
            draggable: true
        },
        polylineOptions: {
            editable: true
        },
        rectangleOptions: polyOptions,
        circleOptions: polyOptions,
        polygonOptions: polyOptions,
        map: map
    });
    google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
        if (e.type != google.maps.drawing.OverlayType.MARKER) {
            // Switch back to non-drawing mode after drawing a shape.
            drawingManager.setDrawingMode(null);
            // Add an event listener that selects the newly-drawn shape when the user
            // mouses down on it.
            var newShape = e.overlay;
            newShape.type = e.type;
            google.maps.event.addListener(newShape, 'click', function() {
                setSelection(newShape);
            });
            setSelection(newShape);
        }
    });
    // Clear the current selection when the drawing mode is changed, or when the
    // map is clicked.
    google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
    google.maps.event.addListener(map, 'click', clearSelection);
    //  buildColorPalette();
    google.maps.event.addListener(drawingManager, 'polygoncomplete', function(polygon) {
        var coordinates = (polygon.getPath().getArray());
        //    checkpointform();
    });
    google.maps.event.addListener(drawingManager, 'PolyMouseEvent', function(polygon) {
        var coordinates = (polygon.getPath().getArray());
        //    checkpointform();
    });
    /*  function gerSelectedShape() {
        var coordinates = (selectedShape.getPath().getArray());
    //    checkpointform();
      }    
    */
}

function VehicleForFence() {
    var vehicleid = jQuery('#vehicleid_fence').val();
    if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
        var selected_name = jQuery('#vehicleid_fence option:selected').text();
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
        jQuery('#vehicle_list_fence').append(div);
        jQuery(div).append(remove_image);
    }
    jQuery('#vehicleid_fence').val(0);
}

function AddedVehicleForFence() {
    var vehicleid = jQuery('#fence_vehid').val();
    var selected_name = jQuery('#vehicleid_fence option[value=' + vehicleid + ']').text();
    if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
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
        jQuery('#vehicle_list_fence').append(div);
        jQuery(div).append(remove_image);
    }
    jQuery('#vehicleid_fence').val(0);
}

function addallvehicleForFence() {
    jQuery("#vehicleid_fence option").each(function(index, element) {
        jQuery("#vehicleid_fence").val(jQuery(element).val());
        VehicleForFence();
    });
}

function addfencetovehicle() {
    var fenceid = jQuery('#fenceid').val();
    if (fenceid > -1 && jQuery('#to_fence_div_' + fenceid).val() == null) {
        var selected_name = jQuery('#fenceid option[value=' + fenceid + ']').text();
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function() {
            removefence(fenceid);
        };
        div.className = 'recipientbox';
        div.id = 'to_fence_div_' + fenceid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="f_list_element" name="to_fence_' + fenceid + '" value="' + fenceid + '"/>';
        jQuery('#fence_list').append(div);
        jQuery(div).append(remove_image);
    }
    jQuery('#fenceid').val(0);
}

function addedfence() {
    jQuery('.fence_id').each(function() {
        var fence_id = this.id;
        var selected_name = jQuery('#fenceid option[value=' + fence_id + ']').text();
        if (fence_id > -1 && jQuery('#to_fence_div_' + fence_id).val() == null) {
            var div = document.createElement('div');
            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';
            remove_image.onclick = function() {
                removefence(fence_id);
            };
            div.className = 'recipientbox';
            div.id = 'to_fence_div_' + fence_id;
            div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_added_fence_' + fence_id + '" value="' + fence_id + '"/>';
            jQuery('#fence_list').append(div);
            jQuery(div).append(remove_image);
        }
        jQuery('#fenceid').val(0);
    });
}

function addallfenceforVehicle() {
    jQuery("#fenceid option").each(function(index, element) {
        jQuery("#fenceid").val(jQuery(element).val());
        var fenceid = jQuery('#fenceid').val();
        if (fenceid > -1 && jQuery('#to_fence_div_' + fenceid).val() == null) {
            var selected_name = jQuery('#fenceid option[value=' + fenceid + ']').text();
            var div = document.createElement('div');
            var remove_image = document.createElement('img');
            remove_image.src = "../../images/boxdelete.png";
            remove_image.className = 'clickimage';
            remove_image.onclick = function() {
                removefence(fenceid);
            };
            div.className = 'recipientbox';
            div.id = 'to_fence_div_' + fenceid;
            div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="f_list_element" name="to_fence_' + fenceid + '" value="' + fenceid + '"/>';
            jQuery('#fence_list').append(div);
            jQuery(div).append(remove_image);
        }
    });
    jQuery('#fenceid').val(0);
}

function removefence(fenceid) {
    jQuery('#to_fence_div_' + fenceid).remove();
}
// Realtime JS
function addvehicle() {
    var vehicleid = jQuery('#vehicleid').val();
    if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
        var selected_name = jQuery('#vehicleid option[value=' + vehicleid + ']').text();
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function() {
            removeVehicle(vehicleid);
        };
        div.className = 'recipientbox';
        div.id = 'to_vehicle_div_' + vehicleid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
        jQuery('#vehicle_list').append(div);
        jQuery(div).append(remove_image);
    }
    jQuery('#vehicleid').val(0);
}

function addedvehicle(vehicleid) {
    gebo_datepicker.init();
    var selected_name = jQuery('#vehicleid option[value=' + vehicleid + ']').text();
    if (vehicleid > -1 && jQuery('#to_vehicle_div_' + vehicleid).val() == null) {
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function() {
            removeVehicle(vehicleid);
        };
        div.className = 'recipientbox';
        div.id = 'to_vehicle_div_' + vehicleid;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
        jQuery('#vehicle_list').append(div);
        jQuery(div).append(remove_image);
    }
    jQuery('#vehicleid').val(0);
}

function addallvehicle() {
    jQuery("#vehicleid option").each(function(index, element) {
        jQuery("#vehicleid").val(jQuery(element).val());
        addvehicle();
    });
}

function submitcheckpointmodal(id) {
    if (jQuery("#chkName").val() == "") {
        jQuery("#checkpointname").show();
        jQuery("#checkpointname").fadeOut(3000);
        jQuery('#chkN').css("border-color", "#ff0000");
    } else if (jQuery("#crad").val() == "") {
        jQuery("#radius").show();
        jQuery("#radius").fadeOut(3000);
        jQuery('#chkRad').css("border-color", "#ff0000");
    } else if (jQuery("#cgeolat").val() == "" && jQuery("#cgeolong").val() == "") {
        jQuery("#latlong").show();
        jQuery("#latlong").fadeOut(3000);
    } else {        var chkN = jQuery("#chkName").val();
        //alert(jQuery('#STime').val());
        // get values for each vehicle 
    jQuery.ajax({
                type: "POST",
                url: "route_ajax.php",
                cache: false,
        data:{chkN:chkN},
                success: function(statuscheck)
                { 
          if(statuscheck == "ok")
                {
                    submitcheckpointdata(id)                    
                }
                else
                {
                    jQuery("#samename").show();
                    jQuery("#samename").fadeOut(3000);                
                } 
        }
      });
    }
}
function submitcheckpointdata(id)
{
    var vehicleid = id;
    var data = jQuery('#createchk').serialize();
    jQuery.ajax({
                type: "POST",
                url: "route.php",
                data: data,
                cache: false,
                success: function(html)
                {    
                jQuery(".popup").hide();
                jQuery(".overlay").hide();
                jQuery("#createchk").trigger(":reset");
                jQuery("#gc-topnav2").hide();
                jQuery("#toggler1").show();
                jQuery("#toggler2").hide();
                call_row(vehicleid);
                call_row(vehicleid);
                }
        });
}
