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
    jQuery('.post').css("padding", 0);
    jQuery('.entry').css("padding", 0);
    jQuery("#gc-topnav2").draggable();
    jQuery('#map').css("height", browserHeight - 250);
});

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
    else
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
               jQuery("#createloc").submit();                 
            }
            else
            {
                alert("same name");
            }
        }
        });
    }
}

function submitloc()
{
    if(jQuery("#geolong").val() == "" && jQuery("#geolat").val() == "")
    {
        alert("Please select location");
    }
    else
    {
        var data = jQuery("#createloc").serialize();
        jQuery.ajax({
                type: "POST",
                url: "route.php",
                async: true,
                data: data,
                cache: false,
                success: function (statuscheck) {
            if(statuscheck == "ok")
            {
               jQuery("#createchk").submit();                    
            }
            else
            {
                alert("same name");
            }
        }
        });
    }
}
    
function showform() 
{
    jQuery('#gc-topnav2').css("display", "block");
    jQuery('#map').css("z-index", "-1000");
    jQuery('#gc-topnav2').css("z-index", "1000");
    jQuery('#toggler').val("Hide");
    jQuery('#toggler').css("background", "#000");
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
/*dt: 17th nov 14, ak added*/
function autocomplete(){
   var input = (document.getElementById('locA'));
    
    // Autocomplete Bound To map
   var autocomplete = new google.maps.places.Autocomplete(input);
   autocomplete.bindTo('bounds', map);
  
   var infowindow = new google.maps.InfoWindow();
   var marker = new google.maps.Marker({
       map: map,
       anchorPoint: new google.maps.Point(0, -29)
    });
  
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        locate();
    });
}
/**/

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

function displayInfo(widget) {
  var info = document.getElementById('info');
  var rawradius = widget.get('distance');
  var radius = parseFloat(rawradius).toFixed(2);
  info.innerHTML = 'Radius: ' +
    radius + " km";
    jQuery("#cgeolat").val(widget.get('position').lat()) ;
    jQuery("#cgeolong").val(widget.get('position').lng());
    jQuery("#crad").val(radius);
}


function locate() {
	address = jQuery("#locA").val();
	if (!geocodeinited) {
		geocoder = new google.maps.Geocoder();
		geocodeinited = true;
	}
	geocoder.geocode({
		'address': address
	}, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
                initmapNew(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                markerlatlng = results[0].geometry.location;
                jQuery("#address").hide();
                jQuery("#locA").hide();
                jQuery("#locateinp").hide();
                jQuery("#createinp").show();
                jQuery("#locName").show();
                jQuery("#lname").show();
//                jQuery("#mapSearchInput").show();
//                jQuery("#mapErrorMsg").hide(100);

        } else
        alert("Please check your address details or contact an Elixir about the issue : " + status);
	});
}

function loaded() 
{
    initialize();        
    jQuery('#gc-topnav2').css("display", "block");
    jQuery('#gc-topnav2').css("z-index", "1000");
    jQuery('#toggler').val("Hide");
    jQuery('#toggler').css("background", "#000");
    jQuery('#createinp').hide();
    jQuery("#locName").hide();
    jQuery("#lname").hide();
}



