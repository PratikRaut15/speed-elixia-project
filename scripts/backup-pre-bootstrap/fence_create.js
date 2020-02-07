// JavaScript Document
jQuery.noConflict();
var counter = 0;
var drawingManager;
var selectedShape;
var colors = ['#000000', '#0099FF', '#AC020F'];
var selectedColor;
var colorButtons = {};
var fencename;
var latitudes = "";
var longitudes = "";

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
	jQuery('#map').css("height", (browserHeight - 234));
	jQuery('.entry').css("padding", 0);
	jQuery('#wrapper').css("min-height", 0);
	jQuery('.post').css("margin-bottom", 0);
	jQuery('#map').css("width", browserWidth - 8);
	var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 15,
		draggableCursor: 'crosshair',
		center: new google.maps.LatLng(19.07, 72.89),
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		disableDefaultUI: true,
		zoomControl: true
	});
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
google.maps.event.addDomListener(window, 'load', initialize);

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
		if (counter % 2 == 0) {
			jQuery('#map').css("z-index", "-1000");
			jQuery('#gc-topnav2').css("position", "absolute");
			jQuery('#gc-topnav2').css("top", "50%");
			jQuery('#gc-topnav2').css("left", "30%");
			jQuery("#gc-topnav2").draggable();
			counter++;
		} else {
			jQuery('#map').css("z-index", "+1000");
			counter++;
		}
	});
}

function editform() {
	jQuery('#CoordsButton').css("display", "block");
	if (fencename != "") {
		jQuery("#chkp_inp").val(fencename);
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
			long: longitudes
		},
		url: "route_ajax.php",
		success: function (result) {
			window.location = "fencing.php?id=2";
		}
	});
}