// JavaScript Document
jQuery.noConflict();
var counter = 0;
var drawingManager;
var selectedShape;
var colors = ['#000000'];
var selectedColor;
var colorButtons = {};
var zonename;
var latitudes = "";
var longitudes = "";
var geocodeinited = false;
var geocoder;

var styles =[
{ "featureType": "road", "elementType": "geometry.stroke", "stylers": [ { "visibility": "off" } ] },{ "featureType": "poi.park", "stylers": [ { "visibility": "simplified" }, { "lightness": 46 } ] },{ "featureType": "poi", "elementType": "labels", "stylers": [ { "visibility": "on" } ] },{ "featureType": "road.highway", "elementType": "labels", "stylers": [ { "visibility": "off" } ] },{ "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [ { "color": "#9e9e9f" } ] },{ "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [ { "color": "#bfbfbf" } ] },{ "featureType": "road.local", "elementType": "geometry.fill", "stylers": [ { "color": "#e0e0e0" } ] },{ "featureType": "poi.park", "stylers": [ { "lightness": 38 } ] },{ "stylers": [ { "saturation": -54 } ] }];


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

/*function deleteSelectedShape() {
	checkpointform();
	colors = "";
	initialize();
	if (selectedShape) {
		selectedShape.setMap(null);
	}
}
*/

function selectColor(color) {
/*	selectedColor = color;
	for (var i = 0; i < colors.length; ++i) {
		var currColor = colors[i];
		colorButtons[currColor].style.border = currColor == color ? '2px solid #789' : '2px solid #fff';
	}
*/    
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

/*
function makeColorButton(color) {
	var button = document.createElement('span');
	button.className = 'color-button';
	button.style.backgroundColor = color;
	google.maps.event.addDomListener(button, 'click', function () {
		selectColor(color);
		setSelectedShapeColor(color);
	});
	return button;
}
*/

/*function buildColorPalette() {
	var colorPalette = document.getElementById('color-palette');
	for (var i = 0; i < colors.length; ++i) {
		var currColor = colors[i];
		var colorButton = makeColorButton(currColor);
		colorPalette.appendChild(colorButton);
		colorButtons[currColor] = colorButton;
	}
	selectColor(colors[0]);
}
*/

function initmap(lat,lng){
    var latlng = new google.maps.LatLng(lat, lng);
    jQuery('#gc-topnav2').css("display", "block");
    jQuery('#gc-topnav2').css("z-index", "100");
	jQuery("#gc-topnav2").draggable();
    jQuery('#toggler').val("Hide");
    jQuery('#toggler').css("background", "#000");
    jQuery('#createinp').hide();
    jQuery("#fenceName").hide();
    jQuery("#chkptname").hide();
    jQuery("#create_table").hide();
    
	var browserWidth = jQuery(window).width();
	var browserHeight = jQuery(window).height();
	jQuery('#map').css("height", (browserHeight - 234));
	jQuery('.entry').css("padding", 0);
	jQuery('#wrapper').css("min-height", 0);
	jQuery('.post').css("margin-bottom", 0);
	jQuery('#map').css("width", browserWidth - 8);
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
	google.maps.event.addListener(drawingManager, 'overlaycomplete', function (e) {
		if (e.type != google.maps.drawing.OverlayType.MARKER) {
			// Switch back to non-drawing mode after drawing a shape.
			drawingManager.setDrawingMode(null);
			// Add an event listener that selects the newly-drawn shape when the user
			// mouses down on it.
			var newShape = e.overlay;
			newShape.type = e.type;
			google.maps.event.addListener(newShape, 'click', function () {
				setSelection(newShape);
			});
			setSelection(newShape);
		}
	});
	// Clear the current selection when the drawing mode is changed, or when the
	// map is clicked.
	google.maps.event.addListener(drawingManager, 'drawingmode_changed', clearSelection);
	google.maps.event.addListener(map, 'click', clearSelection);
//	buildColorPalette();
	google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
		var coordinates = (polygon.getPath().getArray());
//		checkpointform();
	});
	google.maps.event.addListener(drawingManager, 'PolyMouseEvent', function (polygon) {
		var coordinates = (polygon.getPath().getArray());
//		checkpointform();
	});

/*	function gerSelectedShape() {
		var coordinates = (selectedShape.getPath().getArray());
//		checkpointform();
	}    
*/        
}
function editform() {
	jQuery('#CoordsButton').css("display", "block");
	if (zonename != "") {
		jQuery("#chkp_inp").val(zonename);
	}
	jQuery('.ch_bar').toggle(0, function () {
		if (counter % 2 == 0) {
			jQuery('#map').css("z-index", "-1000");
			jQuery('#gc-topnav2').css("position", "absolute");
			jQuery('#gc-topnav2').css("top", "30%");
			jQuery('#gc-topnav2').css("left", "30%");
			counter++;
		} else {
			jQuery('#map').css("z-index", "+1000");
			counter++;
		}
	});
}
function initialize() {
    jQuery('#gc-topnav2').css("display", "block");
    jQuery('#gc-topnav2').css("z-index", "100");
	jQuery("#gc-topnav2").draggable();
    jQuery('#toggler').val("Hide");
    jQuery('#toggler').css("background", "#000");
    jQuery('#createinp').hide();
    jQuery("#fenceName").hide();
    jQuery("#chkptname").hide();
    jQuery("#create_table").hide();
    
	var browserWidth = jQuery(window).width();
	var browserHeight = jQuery(window).height();
	jQuery('#map').css("height", (browserHeight - 234));
	jQuery('.entry').css("padding", 0);
	jQuery('#wrapper').css("min-height", 0);
	jQuery('.post').css("margin-bottom", 0);
	jQuery('#map').css("width", browserWidth - 8);
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
    
    var input = /** @type {HTMLInputElement} */(
      document.getElementById('fenceA'));


  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.bindTo('bounds', map);

  var infowindow = new google.maps.InfoWindow();
  var marker = new google.maps.Marker({
    map: map,
    anchorPoint: new google.maps.Point(0, -29)
  });

  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    infowindow.close();
    locate();
    marker.setVisible(false);
    var place = autocomplete.getPlace();
   
    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    }
    else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);  // Why 17? Because it looks good.
    }
    var address = '';
    infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
    infowindow.open(map, marker);
  });
  
  
    
}
 google.maps.event.addDomListener(window, 'load', initialize); 


function savezone() {
	zonename = jQuery("#fenceName").val();
	if (zonename != "") {
		//var coordinates = (selectedShape.getPath().getArray());
		datapush();
	} else {
		jQuery('#fenceName').css("border-color", "#AC020F");
	}
}

function datapush() {
	var coordinates = (selectedShape.getPath().getArray());
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
        jQuery('.v_list_element').each(function() {
                                                vehiclearray.push(jQuery(this).val());
                                            });
        var datastring = "zonename=" + zonename + "&lat=" + latitudes + "&long=" + longitudes + "&vehiclearray=" + vehiclearray;
                        
	jQuery.ajax({
		type: "POST",
		data: datastring,
                cache: false,
		url: "route_ajax.php",
		success: function (statuscheck) {
                    console.log(statuscheck);
                    if(statuscheck == "ok")
                    {
                        window.location = "zone.php?id=2";                
                    }
                    else if(statuscheck == "detailsreqd"){
                        jQuery("#fencename").show();
                        jQuery("#fencename").fadeOut(3000); 
                    }
                    else if(statuscheck == "samename"){
                        jQuery("#samename").show();
                        jQuery("#samename").fadeOut(3000);                
                    } 
                    else{
                        alert(statuscheck);
                        jQuery("#fencename").show();
                        jQuery("#fencename").fadeOut(3000); 
                    }
		}
	});
}

function locate() {    
	address = jQuery("#fenceA").val();
	if (!geocodeinited) {
		geocoder = new google.maps.Geocoder();
		geocodeinited = true;
	}
	geocoder.geocode({
		'address': address
	}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
                initmap(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                markerlatlng = results[0].geometry.location;
                jQuery("#address").hide();
                jQuery("#fenceA").hide();
                jQuery("#locateinp").hide();
                jQuery("#createinp").show();
                jQuery("#fenceName").show();
                jQuery("#chkptname").show();  
                jQuery('#gc-topnav2').css("width", "580px");
                jQuery("#create_table").show();
        } else
        alert("Please check your address details or contact an Elixir about the issue : " + status);
	});
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

