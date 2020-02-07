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

// For Fence
var fcounter = 0;
var drawingManager;
var selectedShape;
var colors = ['#000000', '#0099FF', '#AC020F'];
var selectedColor;
var colorButtons = {};
var fencename;
var latitudes = "";
var longitudes = "";

// Style
var styles =[
{ "featureType": "road", "elementType": "geometry.stroke", "stylers": [ { "visibility": "off" } ] },{ "featureType": "poi.park", "stylers": [ { "visibility": "simplified" }, { "lightness": 46 } ] },{ "featureType": "poi", "elementType": "labels", "stylers": [ { "visibility": "on" } ] },{ "featureType": "road.highway", "elementType": "labels", "stylers": [ { "visibility": "off" } ] },{ "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [ { "color": "#9e9e9f" } ] },{ "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [ { "color": "#bfbfbf" } ] },{ "featureType": "road.local", "elementType": "geometry.fill", "stylers": [ { "color": "#e0e0e0" } ] },{ "featureType": "poi.park", "stylers": [ { "lightness": 38 } ] },{ "stylers": [ { "saturation": -54 } ] }];

jQuery.noConflict();
jQuery(document).ready(function () {
	var browserHeight = jQuery(window).height();
	jQuery('.post').css("padding", 0);
	jQuery('.entry').css("padding", 0);
	jQuery("#gc-topnav2").draggable();
	jQuery('#map_chkpoint').css("height", browserHeight - 250);
});

function create_map_for_new_location() {
                jQuery("#gc-topnav2").hide();
                //jQuery("#toggler1").show();
                jQuery("#toggler3").hide();
                jQuery("#clearb").hide();
                jQuery("#vehicle_selected").css("display", "none");
                $("chkRadField").hide();
			$("chkRadTd").hide();
			jQuery("#nameid").css("display", "none");
			jQuery("#p1").css("display", "block");
			jQuery("#locateinp").css("display", "block");
			jQuery("#checkpts").css("display", "none");
                        jQuery('#chkRad').val("");
                        showform();
	var browserHeight = jQuery(window).height();
	var browserWidth = jQuery(window).width();
	jQuery('.post').css("padding", 0);
	jQuery('.entry').css("padding", 0);
	jQuery("#gc-topnav2").draggable();
	jQuery('#map_chkpoint').css("height", browserHeight - 250);
	jQuery('#map_chkpoint').css("width", browserWidth - 250);
	var latlng = new google.maps.LatLng(19.07, 72.89);
        var styledMap = new google.maps.StyledMapType(styles,
        {name: "Styled Map"});

        var mapOptions = {
            zoom: 12,
            center: latlng,
                    panControl: true,
                    streetViewControl:false,
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
                    zoomControl: true,
                    zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL
                    },
                    styles:styles
        };
        map = new google.maps.Map(document.getElementById('map_chkpoint'), mapOptions);
            map.mapTypes.set('map_style', styledMap);
            map.setMapTypeId('map_style');
	jQuery(".popup").show();
	jQuery(".overlay").show();
}

function create_map_for_modal(id) {
    periodic=false;
	var browserHeight = jQuery(window).height();
	var browserWidth = jQuery(window).width();
	jQuery('.post').css("padding", 0);
	jQuery('.entry').css("padding", 0);
	jQuery("#gc-topnav2").draggable();
	jQuery('#map_chkpoint').css("height", browserHeight - 250);
	jQuery('#map_chkpoint').css("width", browserWidth - 250);
        var str = jQuery("#latlong" + id).val();                                       
        var partsOfStr = str.split(',');
        var latlng = new google.maps.LatLng(partsOfStr[0], partsOfStr[1]);
        var styledMap = new google.maps.StyledMapType(styles,
        {name: "Styled Map"});

        var mapOptions = {
            zoom: 12,
            center: latlng,
                    panControl: true,
                    streetViewControl:false,
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
                    zoomControl: true,
                    zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL
                    },
                    styles:styles
        };
        map = new google.maps.Map(document.getElementById('map_chkpoint'), mapOptions);
            map.mapTypes.set('map_style', styledMap);
            map.setMapTypeId('map_style');
	jQuery(".popup").show();
	jQuery(".overlay").show();
	jQuery('#toggler1').hide();
        showformForCreateCheckpt();
			$("chkRadField").show();
			$("chkRadTd").show();
			jQuery("#nameid").css("display", "block");
			jQuery("#p1").css("display", "none");
			jQuery("#locateinp").css("display", "none");
			jQuery("#checkpts").css("display", "block");
                        jQuery('#chkRad').val("1");
        initmap(partsOfStr[0], partsOfStr[1]);
			markerlatlng = latlng;
			var marker = new google.maps.Marker({
				map: map,
				draggable: true,
				animation: google.maps.Animation.DROP,
				position: latlng
			});
			$("cgeolat").value = marker.getPosition().lat();
			$("cgeolong").value = marker.getPosition().lng();
		map.setCenter(marker.getPosition());
		var rad = '1'
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
			google.maps.event.addListener(circle, 'click', function () {
				circle.setMap(null);
			});
			eviction_list.push(circle);
		}
			eviction_list.push(marker);
}

function showform() {
	if (counter % 2 == 0) {
		jQuery('#gc-topnav2').css("display", "block");
		jQuery('#map_chkpoint').css("z-index", "-1000");
		jQuery('#gc-topnav2').css("z-index", "1000");
		jQuery('#toggler').val("Hide");
		jQuery('#toggler').css("background", "#000");
		counter++;
	} else {
		jQuery('#toggler').val("Create Checkpoints");
		jQuery('#map_chkpoint').css("z-index", "+1000");
		jQuery('#gc-topnav2').css("display", "none");
		jQuery('#toggler').css("background", "#4D90FE");
		counter++;
	}
}

function showformForCreateCheckptNew() {
	if (counter % 2 == 0) {
		jQuery('#gc-topnav2').css("display", "block");
		jQuery('#map_chkpoint').css("z-index", "-1000");
		jQuery('#gc-topnav2').css("z-index", "1000");
		jQuery('#gc-topnav2').css("height", "inherit");
		jQuery('#gc-topnav2').css("left", "3%");
		jQuery('#gc-topnav2').css("top", "60%");
		jQuery('#toggler').val("Hide");
		jQuery('#toggler').css("background", "#000");
                jQuery("#toggler3").show();
                jQuery("#clearb").show();
                jQuery("#vehicle_selected").css("display", "block");
		AddedVehicleForCheckpoint();
		counter++;
	} else {
		jQuery('#toggler').val("Create Checkpoints");
		jQuery('#map_chkpoint').css("z-index", "+1000");
		jQuery('#gc-topnav2').css("display", "none");
		jQuery('#toggler').css("background", "#4D90FE");
		counter++;
	}
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

function hideformForCreateCheckptNew() {  
                jQuery("#gc-topnav2").hide();
                jQuery("#toggler1").show();
                jQuery("#toggler2").hide();
		jQuery('#toggler').val("Create Checkpoints");
		jQuery('#map_chkpoint').css("z-index", "+1000");
		jQuery('#gc-topnav2').css("display", "none");
		jQuery('#toggler').css("background", "#4D90FE");
}

function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
		vars[key] = value;
	});
	return vars;
}

function initmap(lat, lng) {
	if (gmapsinited) return;
	var latlng = new google.maps.LatLng(lat, lng);
        
        var styledMap = new google.maps.StyledMapType(styles,
        {name: "Styled Map"});

        var mapOptions = {
            zoom: 12,
            center: latlng,
                    panControl: true,
                    streetViewControl:false,
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
                    zoomControl: true,
                    zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL
                    },
                    styles:styles
        };
        map = new google.maps.Map(document.getElementById('map_chkpoint'), mapOptions);
            map.mapTypes.set('map_style', styledMap);
            map.setMapTypeId('map_style');
        
	gmapsinited = true;
	google.maps.event.addListener(map, 'click', function (event) {
		evictMarkers();
		var marker = new google.maps.Marker({
			map: map,
			draggable: false,
			animation: google.maps.Animation.DROP,
			position: event.latLng
		});
		$("cgeolat").value = event.latLng.lat();
		$("cgeolong").value = event.latLng.lng();
		map.setCenter(marker.getPosition());
		var rad = $("chkRad").getValue()
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
			google.maps.event.addListener(circle, 'click', function () {
				circle.setMap(null);
			});
			eviction_list.push(circle);
		}
		eviction_list.push(marker);
	});
	google.maps.event.addDomListener(document.getElementById('chkRad'), 'change', function () {
		evictMarkers();
		var myLatLng = new google.maps.LatLng($("cgeolat").value, $("cgeolong").value);
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
			google.maps.event.addListener(circle, 'click', function () {
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
        var styledMap = new google.maps.StyledMapType(styles,
        {name: "Styled Map"});

        var mapOptions = {
            zoom: 12,
            center: latlng,
                    panControl: true,
                    streetViewControl:false,
                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
                    mapTypeControl: true,
                    mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                    },
                    zoomControl: true,
                    zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL
                    },
                    styles:styles
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

function locate() {
	evictMarkers();
	var address = "";
	address = commaConcat(address, $("chkA").getValue());
	address = commaConcat(address, $("chkT").getValue());
	address = commaConcat(address, $("chkRN").getValue());
	address = commaConcat(address, $("chkC").getValue());
	address = commaConcat(address, $("chkS").getValue());
	address = commaConcat(address, $("chkZC").getValue());
	var image = new google.maps.MarkerImage('../../images/flag.png',
		new google.maps.Size(16, 16),
		new google.maps.Point(0, 0),
		new google.maps.Point(16, 26),
		new google.maps.Size(16, 16));
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
			var marker = new google.maps.Marker({
				map: map,
				draggable: true,
				animation: google.maps.Animation.DROP,
				title: address,
				position: results[0].geometry.location
			});
			$("cgeolat").value = marker.getPosition().lat();
			$("cgeolong").value = marker.getPosition().lng();
			map.setCenter(marker.getPosition());
			eviction_list.push(marker);
			hideform();
			//$('radius').show();
			//jQuery('#radius').fadeOut(5000);
			$("chkRadField").show();
			$("chkRadTd").show();
			jQuery("#nameid").css("display", "block");
			jQuery("#p1").css("display", "none");
			jQuery("#locateinp").css("display", "none");
			jQuery("#checkpts").css("display", "block");
		} else
			alert("Please check your address details or contact an Elixir about the issue : " + status);
	});
}

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
	eviction_list.forEach(function (item) {
		item.setMap(null)
	});
	// reset the eviction array 
	eviction_list = [];
}

function VehicleForCheckpoint() {
	var vehicleid = $('vehicleid1').getValue();
	if (vehicleid > -1 && $('to_vehicle_div_' + vehicleid) == null) {
		var selected_name = $('vehicleid1').options[$('vehicleid1').selectedIndex].text;
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
		$('vehicle_list1').appendChild(div);
		$(div).appendChild(remove_image);
	}
	$('vehicleid1').selectedIndex = 0;
}

function removeVehicle(vehicleid) {
	$('to_vehicle_div_' + vehicleid).remove();
}

function AddedVehicleForCheckpoint() {
	var vehicleid = jQuery('#chk_vehid').val();
	var selected_name = jQuery('#vehicleid1 option[value=' + vehicleid + ']').text();
	if (vehicleid > -1 && $('to_vehicle_div_' + vehicleid) == null) {
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
		$('vehicle_list1').appendChild(div);
		$(div).appendChild(remove_image);
	}
	$('vehicleid1').selectedIndex = 0;
}

function addallvehicleForCheckpoint() {
	var select_box = $('vehicleid1');
	for (var i = 1; i < select_box.options.length; i++) {
		select_box.selectedIndex = i;
		VehicleForCheckpoint();
	}
}

function remove_addedVehicle(vehicleid) {
	$('to_vehicle_div_' + vehicleid).remove();
}

function addcheckpointtovehicle() {
	var checkpointid = $('checkpointid').getValue();
	if (checkpointid > -1 && $('to_chkpt_div_' + checkpointid) == null) {
		var selected_name = $('checkpointid').options[$('checkpointid').selectedIndex].text;
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removeChkpt(checkpointid);
		};
		div.className = 'recipientbox';
		div.id = 'to_chkpt_div_' + checkpointid;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="c_list_element" name="to_chkpt_' + checkpointid + '" value="' + checkpointid + '"/>';
		$('chkpt_list').appendChild(div);
		$(div).appendChild(remove_image);
	}
	$('checkpointid').selectedIndex = 0;
}

function addedcheckpoint() {
	jQuery('.chk_id').each(function () {
		var checkpointid = this.id;
		var selected_name = jQuery('#checkpointid option[value=' + checkpointid + ']').text();
		if (checkpointid > -1 && $('to_chkpt_div_' + checkpointid) == null) {
			var div = document.createElement('div');
			var remove_image = document.createElement('img');
			remove_image.src = "../../images/boxdelete.png";
			remove_image.className = 'clickimage';
			remove_image.onclick = function () {
				removeChkpt(checkpointid);
			};
			div.className = 'recipientbox';
			div.id = 'to_chkpt_div_' + checkpointid;
			div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_added_chkpt_' + checkpointid + '" value="' + checkpointid + '"/>';
			$('chkpt_list').appendChild(div);
			$(div).appendChild(remove_image);
		}
		$('checkpointid').selectedIndex = 0;
	});
}

function addallCheckpointForVehicle() {
	var select_box = $('checkpointid');
	for (var i = 1; i < select_box.options.length; i++) {
		select_box.selectedIndex = i;
		var checkpointid = $('checkpointid').getValue();
		if (checkpointid > -1 && $('to_chkpt_div_' + checkpointid) == null) {
			var selected_name = $('checkpointid').options[$('checkpointid').selectedIndex].text;
			var div = document.createElement('div');
			var remove_image = document.createElement('img');
			remove_image.src = "../../images/boxdelete.png";
			remove_image.className = 'clickimage';
			remove_image.onclick = function () {
				removeChkpt(checkpointid);
			};
			div.className = 'recipientbox';
			div.id = 'to_chkpt_div_' + checkpointid;
			div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="c_list_element" name="to_chkpt_' + checkpointid + '" value="' + checkpointid + '"/>';
			$('chkpt_list').appendChild(div);
			$(div).appendChild(remove_image);
		}
	}
	$('checkpointid').selectedIndex = 0;
}

function removeChkpt(checkpointid) {
	$('to_chkpt_div_' + checkpointid).remove();
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
	selectedColor = color;
	for (var i = 0; i < colors.length; ++i) {
		var currColor = colors[i];
		colorButtons[currColor].style.border = currColor == color ? '2px solid #789' : '2px solid #fff';
	}
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
	google.maps.event.addDomListener(button, 'click', function () {
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
		colorPalette.appendChild(colorButton);
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
        
        var styledMap = new google.maps.StyledMapType(styles,
        {name: "Styled Map"});

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
	google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape);
	google.maps.event.addDomListener(document.getElementById('CoordsButton'), 'click', gerSelectedShape);
	buildColorPalette();
	google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {
		var coordinates = (polygon.getPath().getArray());
		checkpointform();
	});
	google.maps.event.addListener(drawingManager, 'PolyMouseEvent', function (polygon) {
		var coordinates = (polygon.getPath().getArray());
		checkpointform();
	});

	function gerSelectedShape() {
		var coordinates = (selectedShape.getPath().getArray());
		checkpointform();
	}
}

function savefence() {
	fencename = jQuery("#chkp_inp").val();
	if (fencename != "") {
		//var coordinates = (selectedShape.getPath().getArray());
		datapush();
		checkpointform();
	} else {
		jQuery('#chkp_inp').css("border-color", "#AC020F");
	}
}

function checkpointform() {
	if (fencename != "") {
		jQuery("#chkp_inp").val(fencename);
	}
	jQuery('.ch_bar').toggle(0, function () {
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
	jQuery('.ch_bar').toggle(0, function () {
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
	jQuery.ajax({
		type: "POST",
		data: {
			fencename: fencename,
			lat: latitudes,
			lng: longitudes
		},
		url: "route_ajax.php",
		success: function (result) {
			window.location = "fencing.php?id=2";
		}
	});
}

function create_map_for_modal_fence() {
	var browserHeight = jQuery(window).height();
	var browserWidth = jQuery(window).width();
	jQuery('.post').css("padding", 0);
	jQuery('.entry').css("padding", 0);
	//jQuery("#gc-topnav2").draggable();
	jQuery("#fenceform").draggable();
	jQuery('#map_fence').css("height", browserHeight - 250);
	jQuery('#map_fence').css("width", browserWidth - 250);
	jQuery('#wrapper').css("min-height", 0);
	initialize();
	AddedVehicleForFence();
}

function VehicleForFence() {
	var vehicleid = $('vehicleid_fence').getValue();
	if (vehicleid > -1 && $('to_vehicle_div_' + vehicleid) == null) {
		var selected_name = $('vehicleid_fence').options[$('vehicleid_fence').selectedIndex].text;
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
		$('vehicle_list_fence').appendChild(div);
		$(div).appendChild(remove_image);
	}
	$('vehicleid_fence').selectedIndex = 0;
}

function AddedVehicleForFence() {
	var vehicleid = jQuery('#fence_vehid').val();
	var selected_name = jQuery('#vehicleid_fence option[value=' + vehicleid + ']').text();
	if (vehicleid > -1 && $('to_vehicle_div_' + vehicleid) == null) {
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
		$('vehicle_list_fence').appendChild(div);
		$(div).appendChild(remove_image);
	}
	$('vehicleid_fence').selectedIndex = 0;
}

function addallvehicleForFence() {
	var select_box = $('vehicleid_fence');
	for (var i = 1; i < select_box.options.length; i++) {
		select_box.selectedIndex = i;
		VehicleForFence();
	}
}

function addfencetovehicle() {
	var fenceid = $('fenceid').getValue();
	if (fenceid > -1 && $('to_fence_div_' + fenceid) == null) {
		var selected_name = $('fenceid').options[$('fenceid').selectedIndex].text;
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removefence(fenceid);
		};
		div.className = 'recipientbox';
		div.id = 'to_fence_div_' + fenceid;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="f_list_element" name="to_fence_' + fenceid + '" value="' + fenceid + '"/>';
		$('fence_list').appendChild(div);
		$(div).appendChild(remove_image);
	}
	$('fenceid').selectedIndex = 0;
}

function addedfence() {
	jQuery('.fence_id').each(function () {
		var fence_id = this.id;
		var selected_name = jQuery('#fenceid option[value=' + fence_id + ']').text();
		if (fence_id > -1 && $('to_fence_div_' + fence_id) == null) {
			var div = document.createElement('div');
			var remove_image = document.createElement('img');
			remove_image.src = "../../images/boxdelete.png";
			remove_image.className = 'clickimage';
			remove_image.onclick = function () {
				removefence(fence_id);
			};
			div.className = 'recipientbox';
			div.id = 'to_fence_div_' + fence_id;
			div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_added_fence_' + fence_id + '" value="' + fence_id + '"/>';
			$('fence_list').appendChild(div);
			$(div).appendChild(remove_image);
		}
		$('fenceid').selectedIndex = 0;
	});
}

function addallfenceforVehicle() {
	var select_box = $('fenceid');
	for (var i = 1; i < select_box.options.length; i++) {
		select_box.selectedIndex = i;
		var fenceid = $('fenceid').getValue();
		if (fenceid > -1 && $('to_fence_div_' + fenceid) == null) {
			var selected_name = $('fenceid').options[$('fenceid').selectedIndex].text;
			var div = document.createElement('div');
			var remove_image = document.createElement('img');
			remove_image.src = "../../images/boxdelete.png";
			remove_image.className = 'clickimage';
			remove_image.onclick = function () {
				removefence(fenceid);
			};
			div.className = 'recipientbox';
			div.id = 'to_fence_div_' + fenceid;
			div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" class="f_list_element" name="to_fence_' + fenceid + '" value="' + fenceid + '"/>';
			$('fence_list').appendChild(div);
			$(div).appendChild(remove_image);
		}
	}
	$('fenceid').selectedIndex = 0;
}

function removefence(fenceid) {
	$('to_fence_div_' + fenceid).remove();
}

// Realtime JS

function addvehicle() {
	var vehicleid = $('vehicleid').getValue();
	if (vehicleid > -1 && $('to_vehicle_div_' + vehicleid) == null) {
		var selected_name = $('vehicleid').options[$('vehicleid').selectedIndex].text;
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removeVehicle(vehicleid);
		};
		div.className = 'recipientbox';
		div.id = 'to_vehicle_div_' + vehicleid;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
		$('vehicle_list').appendChild(div);
		$(div).appendChild(remove_image);
	}
	$('vehicleid').selectedIndex = 0;
}

function addedvehicle(vehicleid) {
	var selected_name = jQuery('#vehicleid option[value=' + vehicleid + ']').text();
	if (vehicleid > -1 && $('to_vehicle_div_' + vehicleid) == null) {
		var div = document.createElement('div');
		var remove_image = document.createElement('img');
		remove_image.src = "../../images/boxdelete.png";
		remove_image.className = 'clickimage';
		remove_image.onclick = function () {
			removeVehicle(vehicleid);
		};
		div.className = 'recipientbox';
		div.id = 'to_vehicle_div_' + vehicleid;
		div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_vehicle_' + vehicleid + '" value="' + vehicleid + '"/>';
		$('vehicle_list').appendChild(div);
		$(div).appendChild(remove_image);
	}
	$('vehicleid').selectedIndex = 0;
}

function addallvehicle() {
	var select_box = $('vehicleid');
	for (var i = 1; i < select_box.options.length; i++) {
		select_box.selectedIndex = i;
		addvehicle();
	}
}
