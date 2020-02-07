var markr = [];
var markrsfordel = {};
var fencesfordel = {};
var fenid;
var fid;
var geocodeinited = false;

var styles =[
{ "featureType": "road", "elementType": "geometry.stroke", "stylers": [ { "visibility": "off" } ] },{ "featureType": "poi.park", "stylers": [ { "visibility": "simplified" }, { "lightness": 46 } ] },{ "featureType": "poi", "elementType": "labels", "stylers": [ { "visibility": "on" } ] },{ "featureType": "road.highway", "elementType": "labels", "stylers": [ { "visibility": "off" } ] },{ "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [ { "color": "#9e9e9f" } ] },{ "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [ { "color": "#bfbfbf" } ] },{ "featureType": "road.local", "elementType": "geometry.fill", "stylers": [ { "color": "#e0e0e0" } ] },{ "featureType": "poi.park", "stylers": [ { "lightness": 38 } ] },{ "stylers": [ { "saturation": -54 } ] }];

var editedFenceids = [];

//aluto height adjust
jQuery.noConflict();

jQuery(document).ready(function () {
		//map init							 
        initialize();
        jQuery('#gc-topnav2').css("display", "block");
        // Handler for .ready() called.
        var browserHeight = jQuery(window).height();
        jQuery('#sidebar').css("height", browserHeight - 67);
        jQuery('#map').css("height", browserHeight - 120);
        jQuery('#mapdetails').css("height", 30);
        jQuery('#wrapper').css("height", browserHeight - 117);
        jQuery('#pre').css("display", "block");
        jQuery(".all_select").click(function () {
            selectall(jQuery(this).data('type'));
        });
        jQuery(".all_clear").click(function () {
            clearall(jQuery(this).data('type'));
        });
        
        jQuery(".scrollablediv").height(browserHeight - 200);
        jQuery("#gc-topnav2").draggable();
								 
});


function selectall(type)
{    
    switch(type)
    {        
        case 'fences':
            jQuery(".fence_all").each(function() {        
            jQuery(this).prop('checked', true);
        });
        
        jQuery.each(fencesfordel, function( index, value ) {
            markr = markrsfordel[index];
            markr.setMap(map);           
            poly = fencesfordel[index];
            poly.setMap(map);
            google.maps.event.addListener(poly, 'click', function(event){
                fencesfordel[index].setEditable(true);
                editedFenceids.push(index);
            });
            
        });
        
        break;
    }
}

function clearall(type)
{    
    switch(type)
    {
        case 'fences':
            jQuery(".fence_all").each(function() {        
            jQuery(this).prop('checked', false);
        });
        
        jQuery.each(fencesfordel, function( index, value ) {
            markr = markrsfordel[index];
            markr.setMap(null);            
            poly = fencesfordel[index];
            poly.setMap(null);                    
        });                
        
        break;
    }
}

/*Date: 24th oct 2014, ak added for working of search input*/
function locate(){
    address = jQuery("#chkA").val();
    if (!geocodeinited) {
        geocoder = new google.maps.Geocoder();
	geocodeinited = true;
    }
    geocoder.geocode({
        'address': address
    }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            var latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
            map.set('center',latlng);
            map.set('zoom',15);
            markerlatlng = results[0].geometry.location;
        } 
        else
            alert("Please check your address details or contact an Elixir about the issue : " + status);
    });
}

function autocomplete(){
    var input = (document.getElementById('chkA'));
    
    // Autocomplete Bound To map
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
  
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

    // fences 
    jQuery.ajax({
        type: "POST",
        url: "../common/getfences.php?all=1",
        cache: false,
        success: function (data) {
            var cdata1 = jQuery.parseJSON(data);
            var results = cdata1.result;
            //console.log("Parsed Data is: "+cdata1);
             for (var key in cdata1) {
                var route = [];
                    
                   console.log("fencid: "+cdata1[key].fenceid);
                   console.log("fencename: "+cdata1[key].fencename);
                   console.log("fence_bound: "+cdata1[key].fence_bound);
                   var keyInData = jQuery.parseJSON(cdata1[key].fence_bound); //cdata1[key].fence_bound;
                    for(var keyin in keyInData)
                   {
                     /* console.log("fence bounds lat: "+keyInData[keyin].cgeolat);
                     console.log("fence bounds long: "+keyInData[keyin].cgeolong); */
                      var fencelatlng = new google.maps.LatLng(keyInData[keyin].cgeolat, keyInData[keyin].cgeolong);
                      route.push(fencelatlng);
                   } 
                   //console.log("fence bounds lat: "+cdata1[key].fence_bound.cgeolat);
                  //console.log("fence bounds long: "+cdata1[key].fence_bound.cgeolong);
                  
                  // google map settings, initializing and assignment starts here

                    var boundss = new google.maps.LatLngBounds();
                    var f;
    
                    for (f = 0; f < route.length; f++) {
                        boundss.extend(route[f]);
                    }
    
                    // The Center of the Polygon
                    var markr = new MarkerWithLabel({
                        position: boundss.getCenter(),
                        map: map,
                        labelContent: cdata1[key].fencename,
                        labelAnchor: new google.maps.Point(9, 45),
                        labelClass: "mapslabels_fence" // the CSS class for the label
                    });
    
                    fid = cdata1[key].fenceid;
                    markr.set("id", cdata1[key].fenceid);
                    markrsfordel[fid] = markr;
                    markr.setMap(null);
                                                            
                    var poly = new google.maps.Polygon({
                        path: route,       
                        strokeWeight: 1,
                        fillColor: '#55FF55',
                        fillOpacity: 0.3,
                        //editable:true
                    });
                                                            
                    poly.bindTo('center', markr, 'position');
                    fenid = cdata1[key].fenceid;
                    poly.set("id", cdata1[key].fenceid);
                    fencesfordel[fenid] = poly;
                    poly.setMap(null);

                  // Google map settings, initializing and assignment ends here
        
             }
              
         /*   jQuery.each(results, function (i, fences) {
                //console.log('=============================');console.log(fences);return false;
                var route = [];
		// setting bound
		jQuery.each(fences.fence_bound, function (j, fencesobj) {
            //console.log("lat: "+fencesobj);
                    var fencelatlng = new google.maps.LatLng(fencesobj.geolat, fencesobj.geolong);
                    route.push(fencelatlng);
                });
                
                //console.log('=============================');console.log(route);return false;
                
                var boundss = new google.maps.LatLngBounds();
                var f;

                for (f = 0; f < route.length; f++) {
                    boundss.extend(route[f]);
                }

                // The Center of the Polygon
                var markr = new MarkerWithLabel({
                    position: boundss.getCenter(),
                    map: map,
                    labelContent: fences.fencename,
                    labelAnchor: new google.maps.Point(9, 45),
                    labelClass: "mapslabels_fence" // the CSS class for the label
                });

                fid = fences.fencid;
                markr.set("id", fences.fenceid);
                markrsfordel[fid] = markr;
                markr.setMap(null);
                                                        
                var poly = new google.maps.Polygon({
                    path: route,       
                    strokeWeight: 1,
                    fillColor: '#55FF55',
                    fillOpacity: 0.3,
                    //editable:true
                });
                                                        
                poly.bindTo('center', markr, 'position');
                fenid = fences.fencid;
                poly.set("id", fences.fencid);
                fencesfordel[fenid] = poly;
                poly.setMap(null);
            }); */
            
            //console.log(fencesfordel[70]);
        }
    });
    
    

  
}

function fenceplot(fenceid)
{  
    if(jQuery("#fence_"+fenceid).is(':checked') == true)
    {
        marker = markrsfordel[fenceid];
        marker.setMap(map);
        poly = fencesfordel[fenceid];
        poly.setMap(map);    
        // Add a listener for the click event.
        google.maps.event.addListener(poly, 'click', function(event){
            fencesfordel[fenceid].setEditable(true);
            editedFenceids.push(fenceid);
        });
    }
    else
    {
        marker = markrsfordel[fenceid];
        marker.setMap(null);
        poly = fencesfordel[fenceid];
        poly.setMap(null);
    }
}

function refreshmap(){
    initialize();
    clearall('fences');
}

function modifyFence(){
    
    var editFence = jQuery.unique(editedFenceids);
    var fenceDetails = [];
    
    jQuery.each(editFence, function (j, value) {
        var vertices = fencesfordel[value].getPath();
        var contentString = [];
        /* var contentString = {}; */
        // Iterate over the vertices.
        for(var i =0; i < vertices.getLength(); i++){
            var xy = vertices.getAt(i);
            contentString.push(xy.lat() + ',' + xy.lng());
            /* contentString['cgeolat'] = xy.lat();
            contentString['cgeolong'] = xy.lng(); */
        }
        fenceDetails.push({fenceid: value, latLong:  contentString});
        /* fenceDetails.push({fenceid: value, latLong:  JSON.stringify(contentString)}); */
    });
    if(fenceDetails.length==0){
        alert('Fence not edited');return false;
    }
  /*   else if(fenceDetails.length>1)
    {
        fenceDetails = [];
        alert('You can edit 1 fence at a time');return false;
    } */
    else
    {
        jQuery.ajax({
            type:'POST',
            url:'modifyFence_ajax.php',
            data:"data="+JSON.stringify(fenceDetails),
            success:function(response){
                alert(response);
            }
        });    
    }
    console.log("Checkboxes selected are: "+fenceDetails.length);
   /*  jQuery.ajax({
        type:'POST',
        url:'modifyFence_ajax.php',
        data:"data="+JSON.stringify(fenceDetails),
        success:function(response){
            alert(response);
        }
    }); */
    
}
