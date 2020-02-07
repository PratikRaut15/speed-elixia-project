var geocodeinited = false;
var map;
var geocoder;
jQuery.noConflict();

var styles =[
{ "featureType": "road", "elementType": "geometry.stroke", "stylers": [ { "visibility": "off" } ] },{ "featureType": "poi.park", "stylers": [ { "visibility": "simplified" }, { "lightness": 46 } ] },{ "featureType": "poi", "elementType": "labels", "stylers": [ { "visibility": "on" } ] },{ "featureType": "road.highway", "elementType": "labels", "stylers": [ { "visibility": "off" } ] },{ "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [ { "color": "#9e9e9f" } ] },{ "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [ { "color": "#bfbfbf" } ] },{ "featureType": "road.local", "elementType": "geometry.fill", "stylers": [ { "color": "#e0e0e0" } ] },{ "featureType": "poi.park", "stylers": [ { "lightness": 38 } ] },{ "stylers": [ { "saturation": -54 } ] }];


jQuery(document).ready(function () 
{
    loaded();
    var browserHeight = jQuery(window).height();
    jQuery('#map').css("height", browserHeight - 250);
});

function editchekpoints()
{
    var chk = jQuery("#chkName").val();
    if(jQuery("#cphone").val().replace(/[^a-z]/g, "").length > 0)
    {
        alert("Please enter phone number");
    }else if( chk == "")
    {
        alert("Please enter checkpoint name");        
    }
    else if(chk.match("'"))
    {
        alert("Special cherecters not allowed in checkpoint name");
    }
    else
    {
        jQuery("#chkcreate").submit();                    
    }
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
            vars[key] = value;
    });
    return vars;
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
  this.set('distance', jQuery("#crad").val());

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

google.maps.event.addListener(distanceWidget, 'distance_changed', function() {
  displayInfo(distanceWidget);
});

google.maps.event.addListener(distanceWidget, 'position_changed', function() {
  displayInfo(distanceWidget);
});        
}

function displayInfo(widget) {
  var rawradius = widget.get('distance');
  var radius = parseFloat(rawradius).toFixed(2);
    jQuery("#cgeolat").val(widget.get('position').lat()) ;
    jQuery("#cgeolong").val(widget.get('position').lng());
    jQuery("#crad").val(radius);
}

function loaded() 
{
    var lat = jQuery("#cgeolat").val() ;
    var lng = jQuery("#cgeolong").val();
    initmap(lat,lng);        
}

mappedvehicles();
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
        remove_image.onclick = function() { removeVehicle(vehicleid); };
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

function VehicleForRoute_ById(vehicleid,selected_name) {
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

