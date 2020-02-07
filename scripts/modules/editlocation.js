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

function editlocation()
{
    if(jQuery("#locName").val() == "")
    {
        alert("Please enter location name");        
    }
    else
    {
        jQuery("#loccreate").submit();                    
    }
}
function checklocname()
{
    if(jQuery("#locName").val() == "")
    {
        alert("Please enter location name");
    }
    else if(jQuery("#geolong").val() == "" && jQuery("#geolat").val() == "")
    {
        alert("Please select location");
    }
    else if(jQuery("#locName").val() != jQuery("#locNameOld").val())
    {
        var locN = jQuery("#locName").val();
        jQuery.ajax({
                type: "POST",
                url: "loc_ajax.php",
                async: true,
                data: {
                        locN: locN
                },
                cache: false,
                success: function (statuscheck) {
            if(statuscheck == "ok")
            {
               jQuery("#loccreate").submit();                 
            }
            else
            {
                alert("name already exist");
            }
        }
        });
    }
    else{
        jQuery("#loccreate").submit(); 
    }
}

function initmapNew(lat, lng) {
var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });
    
    var latlng = new google.maps.LatLng(lat, lng);

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
    map = new google.maps.Map(document.getElementById('map'), mapOptions);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');
    
    
    var marker = new google.maps.Marker(
    {
        map:map,
        draggable:true,
        animation: google.maps.Animation.DROP,
        position: latlng
    });
    geocodePosition(marker.getPosition(latlng));
    google.maps.event.addListener(marker, 'dragend', function() 
    {
        geocodePosition(marker.getPosition());
    });
}


function geocodePosition(pos) 
{
   var info = document.getElementById('info');
   geocoder = new google.maps.Geocoder();
   geocoder.geocode
    ({
        latLng: pos
    }, 
        function(results, status) 
        {
            if (status == google.maps.GeocoderStatus.OK) 
            {
                var arrAddress = results[0].address_components;
                for (ac = 0; ac < arrAddress.length; ac++) {
                    if (arrAddress[ac].types[0] == "street_number") { document.getElementById("tbUnit").value = arrAddress[ac].long_name }
                    if (arrAddress[ac].types[0] == "route") { document.getElementById("tbStreet").value = arrAddress[ac].short_name }
                    if (arrAddress[ac].types[0] == "locality") { document.getElementById("tbCity").value = arrAddress[ac].long_name }
                    if (arrAddress[ac].types[0] == "administrative_area_level_1") { document.getElementById("tbState").value = arrAddress[ac].short_name }
                    if (arrAddress[ac].types[0] == "postal_code") { document.getElementById("tbZip").value = arrAddress[ac].long_name }
                }
                document.getElementById("tbAddress").value = results[0].formatted_address;
                jQuery("#geolat").val(pos.lat()) ;
                jQuery("#geolong").val(pos.lng());
            } 
            else 
            {
                info.innerHTML = 'Cannot determine address at this location.'+status;
            }
        }
    );
}

function loaded() 
{
    var lat = jQuery("#geolat").val() ;
    var lng = jQuery("#geolong").val();
    initmapNew(lat,lng);        
}



