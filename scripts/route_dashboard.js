var map;
// Style
var styles =[
{ "featureType": "road", "elementType": "geometry.stroke", "stylers": [ { "visibility": "off" } ] },{ "featureType": "poi.park", "stylers": [ { "visibility": "simplified" }, { "lightness": 46 } ] },{ "featureType": "poi", "elementType": "labels", "stylers": [ { "visibility": "on" } ] },{ "featureType": "road.highway", "elementType": "labels", "stylers": [ { "visibility": "off" } ] },{ "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [ { "color": "#9e9e9f" } ] },{ "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [ { "color": "#bfbfbf" } ] },{ "featureType": "road.local", "elementType": "geometry.fill", "stylers": [ { "color": "#e0e0e0" } ] },{ "featureType": "poi.park", "stylers": [ { "lightness": 38 } ] },{ "stylers": [ { "saturation": -54 } ] }];

jQuery(document).ready(function (){

	jQuery('.route-tooltip-top').tipsy({
		html: true,
                gravity: 'se'
	});
        
});



function create_map_for_modal(id) {
	var browserHeight = jQuery(window).height();
	var browserWidth = jQuery(window).width();
	jQuery('.post').css("padding", 0);
	jQuery('.entry').css("padding", 0);
	jQuery('#map_route').css("height", browserHeight - 250);
	jQuery('#map_route').css("width", browserWidth - 250);
        var str = jQuery("#latlong" + id).val();                                       
        var partsOfStr = str.split(',');
        var vehiclename = jQuery("#vehicle" + id).val();
        var vehicleimage = jQuery("#vehicleimage" + id).val();
        var image = new google.maps.MarkerImage(vehicleimage,
        new google.maps.Size(48, 48),
        new google.maps.Point(0, 0),
        new google.maps.Point(8, 20));
        
        var latlng = new google.maps.LatLng(partsOfStr[0], partsOfStr[1]);
        var styledMap = new google.maps.StyledMapType(styles,
        {name: "Styled Map"});


        var mapOptions = {
            zoom: 15,
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
        map = new google.maps.Map(document.getElementById('map_route'), mapOptions);
            map.mapTypes.set('map_style', styledMap);
            map.setMapTypeId('map_style');
	jQuery(".popup").show();
	jQuery(".overlay").show();
        
                    var marker = new MarkerWithLabel({
                        position: latlng,
                        map: map,
                        icon: image,
                        labelContent: vehiclename,
                        labelAnchor: new google.maps.Point(9, 45),
                        labelClass: "mapslabels" // the CSS class for the label
                    });
                    
       jQuery.ajax({
        type: "POST",
        url: "../reports/route_ajax.php?work=checkpointlist",
        async: true,
        cache: false,
        data: {
            vehicleid: id
        },
        success: function (data) {
			 var cdata = jQuery.parseJSON(data);
//alert(data);
            var results = cdata.result;
			
    //checkpoint start
        
                    jQuery.each(results, function (i, device) {
                        var circle;
                        var rad;
                        var chklatlng;
                        var chkrad = device.crad;
                        var chkname = device.cname;
                        var chklatlngp = new google.maps.LatLng(device.cgeolat, device.cgeolong);

                        var marker = new MarkerWithLabel({
                            position: chklatlngp,
                            map: map,
                            labelContent: chkname,
                            labelAnchor: new google.maps.Point(45, 50),
                            labelClass: "mapslabels_chkp" // the CSS class for the label
                        });

                        rad = chkrad * 1000;
                        circle = new google.maps.Circle({
                            map: map,
                            radius: rad,
                            fillColor: '#000000',
                            strokeColor: '#000000',
                            strokeweight: 1
                        });
                        circle.bindTo('center', marker, 'position');
                    });
                    
    //checkpoint end
			}
    });

}