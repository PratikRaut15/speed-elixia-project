var counter = 0;
var geocodeinited = false;
var markers = [];
var allColor = ['#FE6700','#6FABDF','#D73F33','#794F21','#60AAEA'];
var selectedColor = [];

var directionsDisplay;
var directionsService = new google.maps.DirectionsService();
var RmDirection = [];
var slotid=1;
var styles =[
{ "featureType": "road", "elementType": "geometry.stroke", "stylers": [ { "visibility": "off" } ] },{ "featureType": "poi.park", "stylers": [ { "visibility": "simplified" }, { "lightness": 46 } ] },{ "featureType": "poi", "elementType": "labels", "stylers": [ { "visibility": "on" } ] },{ "featureType": "road.highway", "elementType": "labels", "stylers": [ { "visibility": "off" } ] },{ "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [ { "color": "#9e9e9f" } ] },{ "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [ { "color": "#bfbfbf" } ] },{ "featureType": "road.local", "elementType": "geometry.fill", "stylers": [ { "color": "#e0e0e0" } ] },{ "featureType": "poi.park", "stylers": [ { "lightness": 38 } ] },{ "stylers": [ { "saturation": -54 } ] }];

//aluto height adjust
jQuery.noConflict();

jQuery(document).ready(function () {
    //map init							 
    initialize();
    
    jQuery("#orderdate").datepicker({format: "dd-mm-yyyy",autoclose:true});
    jQuery('#gc-topnav2').css("display", "block");
    jQuery("#gc-topnav2").draggable();
    
    // Handler for .ready() called.
    var browserHeight = jQuery(window).height();
    jQuery('#sidebar').css("height", browserHeight - 67);
    jQuery('#map').css("height", browserHeight - 67);
    //jQuery('#mapdetails').css("height", 30);
    jQuery('#wrapper').css("height", browserHeight - 117);
    jQuery('#pre').css("display", "block");
    
    jQuery(".all_select").click(function () {
        selectall(jQuery(this).data('type'));
    });
    jQuery(".all_clear").click(function () {
        clearall(jQuery(this).data('type'));
    });
        
    jQuery(".scrollablediv").height(browserHeight-320);
    
    jQuery(".vehCBox").click(function(){
        var vehid = jQuery(this).attr('id').match(/\d+/);
        var chkd = jQuery(this).attr('checked');
        plotRoute(vehid,chkd);
    });
    jQuery('.slot_val').change(function(){
        slotid = jQuery(this).val();
        onSlotSelect();
    });
 
});

function onSlotSelect(){
    jQuery(".veh_all").each(function() {
        var chkd = jQuery(this).attr('checked');
        if(chkd){
            plotRoute(jQuery(this).attr('id').match(/\d+/),chkd);
        }
    });
}
function selectall(type)
{    
    switch(type)
    {        
        case 'vehicles':
            jQuery(".veh_all").each(function() {        
                jQuery(this).prop('checked', true);
                plotRoute(jQuery(this).attr('id').match(/\d+/),true);
            });
        break;
    }
}

function clearall(type)
{    
    switch(type)
    {
        case 'vehicles':
            jQuery(".veh_all").each(function() {
                jQuery(this).prop('checked', false);
            });
            directionsDisplay.setMap(null);
            setAllMap(null);
        break;
    }
}

function locate(){
    var address = jQuery("#chkA").val();
    if (!geocodeinited) {
        geocoder = new google.maps.Geocoder();
	geocodeinited = true;
    }
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

/*To plot route of certain vehicles*/
function plotRoute(vehid, isChecked){
    if(isChecked){
        var tempRoute;
        var warehouseLatLong;
        var ipdate = jQuery('#orderdate').val();
        var error;
        jQuery.ajax({
            url: 'map_page_ajax.php',
            type: 'POST',
            data: 'vehid='+vehid+'&slotid='+slotid+'&odate='+ipdate,
            async: false,
            success: function (record) {
                var recordDcd = jQuery.parseJSON(record);
                error = recordDcd.error;
                if(error==undefined){
                    tempRoute = recordDcd.finalSeq;
                    warehouseLatLong = recordDcd.start_point;
                }
            }
        });
        if(error!=undefined){alert(error);return false;}
        var cur;
        var loop = 0;
        var mapLoop = tempRoute.length;
        var waypts = [];
        var allMarker = [];
        var mS = warehouseLatLong.split(',');
        var markerStartE = mS[0]+"=="+mS[1]+'=='+1+'==Kurla-Warehouse';
        
        allMarker.push(markerStartE);
        jQuery.each(tempRoute, function (i, value) {
            loop++;
            cur = value['lat']+','+value['longi'];
            allMarker.push(value['lat']+'=='+value['longi']+'=='+value['accuracy']+'=='+value['pop_display']);
            if(loop==mapLoop){
                return false;
            }
            waypts.push({
              location:cur,
              stopover:true
            });

        });
        calcRoute(warehouseLatLong, cur, waypts, allMarker);
    }
    else{
        directionsDisplay.setMap(null);
        setAllMap(null);
        //markers[0].setMap(null);
    }
}

/*display route*/
function calcRoute(start, end, waypts_c, allMarker_c) {
    if(selectedColor.length==0){
        var color = allColor[0];
    }
    else{
        var color = allColor[selectedColor.length];
    }
    selectedColor.push(color);
    
    directionsDisplay = new google.maps.DirectionsRenderer({
        preserveViewport: true, 
        suppressMarkers: true,
        map: map,
        polylineOptions: { strokeColor: color }
    }); //for direction
    //directionsDisplay.setMap(map);
    
    var request = {
      origin:start,
      destination:end,
      waypoints: waypts_c,
      optimizeWaypoints: false,//true,
      travelMode: google.maps.TravelMode.DRIVING //WALKING
    };
    directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
    }
    });
    
    var cleanValue;
    var icon;
    
    jQuery.each(allMarker_c, function (i, value) {
        cleanValue = value.split('==');
        var myLatlng = new google.maps.LatLng(cleanValue[0],cleanValue[1]);
        icon = 'http://www.googlemapsmarkers.com/v1/'+i+'/'+markerColorCases(cleanValue[2])+'/';
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            icon: icon,
        });
        
        var infowindow = new google.maps.InfoWindow();
        google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
            return function() {
                infowindow.setContent(content);
                infowindow.open(map,marker);
            };
        })(marker,cleanValue[3],infowindow));

        markers.push(marker);
    });
}

function markerColorCases(accuracy){
    switch(accuracy){
        case '2':
            return '0099ff';
        case '3':
            return '009999';
        case '4':
            return '005387';
        case '5':
            return 'FFCA4D';
        default:
            return '009900';
    }
}

// Sets the map on all markers in the array.
function setAllMap(map) {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
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

function initialize() {
    
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });

    var mumbai = new google.maps.LatLng(19.03590687086149, 72.94649211215824);
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

}

function onclicktog() {
    jQuery('#sidebar').toggle('fast');
    if (counter % 2 === 0) {
        jQuery('#next').css("display", "block");
        jQuery('#pre').css("display", "none");
        jQuery('#maptoggler').css("left", "0px");

        counter++;
    } else {

        jQuery('#pre').css("display", "block");
        jQuery('#next').css("display", "none");
        jQuery('#maptoggler').css("left", "192px");
        counter++;
    }
}