var styles =[
{ "featureType": "road", "elementType": "geometry.stroke", "stylers": [ { "visibility": "off" } ] },{ "featureType": "poi.park", "stylers": [ { "visibility": "simplified" }, { "lightness": 46 } ] },{ "featureType": "poi", "elementType": "labels", "stylers": [ { "visibility": "on" } ] },{ "featureType": "road.highway", "elementType": "labels", "stylers": [ { "visibility": "off" } ] },{ "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [ { "color": "#9e9e9f" } ] },{ "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [ { "color": "#bfbfbf" } ] },{ "featureType": "road.local", "elementType": "geometry.fill", "stylers": [ { "color": "#e0e0e0" } ] },{ "featureType": "poi.park", "stylers": [ { "lightness": 38 } ] },{ "stylers": [ { "saturation": -54 } ] }];

jQuery(document).ready(function () {
    //map init							 
    initialize();
    
    jQuery('#gc-topnav2').css("display", "block");
    jQuery("#gc-topnav2").draggable();
    
    // Handler for .ready() called.
    var browserHeight = jQuery(window).height();
    jQuery('#map').css("height", browserHeight - 67);
    jQuery('#wrapper').css("height", browserHeight - 117);
    jQuery('#pre').css("display", "block");
    
    jQuery('body').click(function(){
        jQuery('#ajaxStatus td').html('');
    });
    
								 
});

function initialize() {
    
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });

    var mumbai = new google.maps.LatLng(initStartLat, initStartLong);
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
    
    var myLatlng = new google.maps.LatLng(initStartLat,initStartLong);
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        draggable: true 
    });

    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({'latLng': myLatlng }, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
        var infowindow = new google.maps.InfoWindow();
        infowindow.setContent(results[0].formatted_address);
        infowindow.open(map, marker);
        }
    }
    });
    
    google.maps.event.addListener(marker, 'dragend', function() {
        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
            if (results[0]) {
                jQuery('#address').val(results[0].formatted_address);
                jQuery('#latitude').val(marker.getPosition().lat());
                jQuery('#longitude').val(marker.getPosition().lng());
                var infowindow = new google.maps.InfoWindow();
                infowindow.setContent(results[0].formatted_address);
                infowindow.open(map, marker);
            }
            }
        });
    });

}

function autocomplete(){
    
    var input = (document.getElementById('chkA'));
    
    // Autocomplete Bound To map
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
  
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });
    
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        locate();
    });
}

function locate(){
    var address = jQuery("#chkA").val();
    var geocoder = new google.maps.Geocoder();

    geocoder.geocode({
        'address': address
    }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            //initmap_search(results[0].geometry.location.lat(), results[0].geometry.location.lng());
            var latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
            map.set('center',latlng);
            map.set('zoom',15);
            markerlatlng = results[0].geometry.location;
        } 
        else
            alert("Please check your address details or contact an Elixir about the issue : " + status);
    });
}


function updateLocation(){
    var data = jQuery('#updateLocation').serialize();
    if(jQuery('#latitude').val()=='' || jQuery('#longitude').val()==''){
        alert('Latitude/Longitude cannot be empty');return false;
    }
    jQuery.ajax({
        url:'update_location_ajax.php',
        type:'POST',
        data: data,
        success: function(response){
            jQuery('#ajaxStatus td').html(response);
        }
    });
}

function updateArea(){
    var data = jQuery('#updateArea').serialize();
    if(jQuery('#latitude').val()=='' || jQuery('#longitude').val()==''){
        alert('Latitude/Longitude cannot be empty');return false;
    }
    jQuery.ajax({
        url:'update_area_ajax.php',
        type:'POST',
        data: data,
        success: function(response){
            jQuery('#ajaxStatus td').html(response);
        }
    });
}