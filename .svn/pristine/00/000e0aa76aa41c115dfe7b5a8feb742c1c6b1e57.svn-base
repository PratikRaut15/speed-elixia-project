var gmapsinited = false;
var eviction_list = [];
var vehicleid = null;
var vehicle_list = [];
var markers = [];
var markersfordel = {};
var circlesfordel = {};
var vehiclesfordel = {};
var id;
var vehid;
var counter = 0;
var markerCluster;
var styles =[
{ "featureType": "road", "elementType": "geometry.stroke", "stylers": [ { "visibility": "off" } ] },{ "featureType": "poi.park", "stylers": [ { "visibility": "simplified" }, { "lightness": 46 } ] },{ "featureType": "poi", "elementType": "labels", "stylers": [ { "visibility": "on" } ] },{ "featureType": "road.highway", "elementType": "labels", "stylers": [ { "visibility": "off" } ] },{ "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [ { "color": "#9e9e9f" } ] },{ "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [ { "color": "#bfbfbf" } ] },{ "featureType": "road.local", "elementType": "geometry.fill", "stylers": [ { "color": "#e0e0e0" } ] },{ "featureType": "poi.park", "stylers": [ { "lightness": 38 } ] },{ "stylers": [ { "saturation": -54 } ] }];

//aluto height adjust
jQuery.noConflict();
jQuery(document).ready(function () {
    // Handler for .ready() called.
    var browserHeight = jQuery(window).height();
    jQuery('#sidebar').css("height", browserHeight - 67);
    jQuery('#map').css("height", browserHeight - 120);
    jQuery('#mapdetails').css("height", 30);
    jQuery('#wrapper').css("height", browserHeight - 117);
    jQuery('#pre').css("display", "block");
    jQuery( ".all_select" ).click(function() {
selectall(jQuery(this).data('type'));    
});
    jQuery( ".all_clear" ).click(function() {
clearall(jQuery(this).data('type'));    
});

jQuery(".scrollablediv").height(browserHeight*25/100);


});


function selectall(type)
{    
    switch(type)
    {        
        case 'vehicles':
            jQuery(".veh_all").each(function() {        
            jQuery(this).prop('checked', true);
        });
        
        jQuery.each(vehiclesfordel, function( index, value ) {
            marker = vehiclesfordel[index];
            if(marker)
            {
                marker.setMap(map);   
            }
            
            // Add in Cluster
            markers.push(marker);
            
        });                        

        markerCluster.clearMarkers();                
        markerCluster = new MarkerClusterer(map, markers);                        
        break;
        
        case 'checkpoints':
            jQuery(".chk_all").each(function() {        
            jQuery(this).prop('checked', true);            
        });
        
        jQuery.each(markersfordel, function( index, value ) {
            marker = markersfordel[index];
            marker.setMap(map);           
            circle = circlesfordel[index];
            circle.setMap(map);                    
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
        
        jQuery.each(vehiclesfordel, function( index, value ) {
            marker = vehiclesfordel[index];
            if(marker)
            {
               marker.setMap(null);      
            }
            
            // Remove from Cluster
            markerCluster.clearMarkers();        
            markers = [];
        });                
        
//        markerCluster = new MarkerClusterer(map, markers);                                    
        break;
        
        case 'checkpoints':
            jQuery(".chk_all").each(function() {        
            jQuery(this).prop('checked', false);
        });
        
        jQuery.each(markersfordel, function( index, value ) {
            marker = markersfordel[index];
            marker.setMap(null);            
            circle = circlesfordel[index];
            circle.setMap(null);                    
        });                
        
        break;
    }
}

function initialize() 
{
    var styledMap = new google.maps.StyledMapType(styles,
    {name: "Styled Map"});

    var mumbai = new google.maps.LatLng(19.03590687086149, 72.94649211215824);
    var mapOptions = {
        zoom: 12,
        center: mumbai,
		panControl: true,
		streetViewControl:false,
                mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style'],
		zoomControl: true,
		zoomControlOptions: {
		style: google.maps.ZoomControlStyle.SMALL
		},
		styles:styles
    };
    map = new google.maps.Map(document.getElementById('map'), mapOptions);
	map.mapTypes.set('map_style', styledMap);
	map.setMapTypeId('map_style');

    var params = "all=1";
    new Ajax.Request('route_ajax.php', {
        parameters: params,
        onSuccess: function (transport) {
            var cdata1 = transport.responseText.evalJSON();
            var results = cdata1.result;

            // Marker Clustering
            markers = [];
            var bounds = new google.maps.LatLngBounds();              
            
            results.each(function (device) {

            var image = new google.maps.MarkerImage(device.image,
                        new google.maps.Size(48, 48),
                        new google.maps.Point(0, 0),
                        new google.maps.Point(8, 20));

              var latLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
              
              var marker = new MarkerWithLabel({position: latLng, map: map, icon: image, labelContent: device.cname,
                            labelAnchor: new google.maps.Point(9, 45),
                            labelClass: "mapslabels" // the CSS class for the label
                            });
              
                            
            bounds.extend(marker.position);   
            var contentString = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + ']<br>' +
                'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : ' + device.clastupdated;
            
            //infoboxjs 
                var boxText = document.createElement("div");
                boxText.style.cssText = "";
                boxText.innerHTML =  "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> "+contentString+"</div></div></div>" ;
                boxText.className = "arrow_box";

                var myOptions = {
                content: boxText
                ,disableAutoPan: false
                ,maxWidth: 0
                ,pixelOffset: new google.maps.Size(-110,-130)
                ,zIndex: null
                ,boxStyle: { 
                opacity: 0.99
                ,width: "280px"
                }
                ,closeBoxMargin: "18px 13px 2px"
                ,closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
                ,infoBoxClearance: new google.maps.Size(1, 1)
                ,isHidden: false
                ,pane: "floatPane"
                ,enableEventPropagation: false
                };

                var ib = new InfoBox(myOptions);
                //ib.open(map, marker);

                google.maps.event.addListener(marker, "click", function (e) {
                ib.open(map, this);

                });
      
//            var infowindow = new google.maps.InfoWindow({
//                content: contentString
//            });

//            google.maps.event.addListener(marker, 'click', function () {
//                infowindow.open(map, marker);
//                infowindow.setPosition(new google.maps.Point(20, 75)); 
//            });
            
            vehid = device.cvehicleid;
            marker.set("id",device.cvehicleid);
            vehiclesfordel[vehid] = marker;     
            markers.push(marker);
    });  

    map.fitBounds(bounds);
    
    markerCluster = new MarkerClusterer(map, markers);        

    // Checkpoints
    var params = "all=1";
    new Ajax.Request('../common/getcheckpoints.php', {
        parameters: params,
        onSuccess: function (transport) {
            var cdata = transport.responseText.evalJSON();
        var results = cdata.result;
        results.each(function (device) {
            try {
                var myLatLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);

                /* var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    title: device.name
                    });*/

                var marker = new MarkerWithLabel({
                    position: myLatLng,
                    map: map,
                    labelContent: device.cname,
                    labelAnchor: new google.maps.Point(9, 45),
                    labelClass: "mapslabels_chkp" // the CSS class for the label
                });

                id = device.checkpointid;
                marker.set("id",device.checkpointid);
                markersfordel[id] = marker;     

                var circle = new google.maps.Circle({
                    map: map,
                    radius: device.crad,
                    fillColor: '#AA0000',
                    strokeColor: '#AA0000',
                    strokeweight: 1
                });

                circle.bindTo('center', marker, 'position');

                circle.set("id",device.checkpointid);
                circlesfordel[id] = circle;     

            marker.setMap(null);
            circle.setMap(null);

            } catch (ex) {
                alert(ex);
            }
        });        
        },
        onComplete: function () {}
    });

        },
        onComplete: function () {}
    });

//            plotvehicles(cdata);
//            getchk();

    periodicupdate();                        
	//map.setZIndex(zIndex:100);

}

/*function initialize() {

    var latlng = new google.maps.LatLng(0, 0);
    var myOptions = {
        zoom: 0,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map"), myOptions);

}
*/

function chkplot(chkid)
{
    if(jQuery("#chk_"+chkid).is(':checked') == true)
    {
        marker = markersfordel[chkid];
        marker.setMap(map);
        circle = circlesfordel[chkid];
        circle.setMap(map);        
    }
    else
    {
        marker = markersfordel[chkid];
        marker.setMap(null);
        circle = circlesfordel[chkid];
        circle.setMap(null);
    }
}

function vehplot(vehid)
{
    if(jQuery("#veh_"+vehid).is(':checked') == true)
    {
        marker = vehiclesfordel[vehid];
        if(marker)
        {    
            marker.setMap(map);
        }

        // Add in Cluster
        markerCluster.clearMarkers();        
        markers.push(marker);
        markerCluster = new MarkerClusterer(map, markers);                        
    }
    else
    {
        marker = vehiclesfordel[vehid];        
        if(marker)
        {
            marker.setMap(null);
        }

        // Remove from Cluster
        markerCluster.clearMarkers();        
        remove(markers, marker);
        if(markers.length != 0)
        {
            markerCluster = new MarkerClusterer(map, markers);                
        }
    }
}

function remove(arr, item) {
      for(var i = arr.length; i--;) {
          if(arr[i] === item) {
              arr.splice(i, 1);
          }
      }
  }
function onclicktog() {
    $('sidebar').toggle(900);
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


function initmap(lat, lng) {
  if (gmapsinited) return;
    var latlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
        zoom: 11,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map($("map"), myOptions);
    gmapsinited = true;
}

function mapvehicles() {
    addAllVehicles();
}

function periodicupdate() {
    refreshdata.delay(60);
}

function refreshmap()
{
    var params = "all=1";
    new Ajax.Request('route_ajax.php', {
        parameters: params,
        onSuccess: function (transport) {
            var cdata1 = transport.responseText.evalJSON();
            var results = cdata1.result;

            vehiclesfordel = [];
            markers = [];
            
            // Marker Clustering
            results.each(function (device) {

            var image = new google.maps.MarkerImage(device.image,
                        new google.maps.Size(48, 48),
                        new google.maps.Point(0, 0),
                        new google.maps.Point(8, 20));

              var latLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
              var marker = new MarkerWithLabel({'position': latLng, map: map, icon: image, labelContent: device.cname,
                            labelAnchor: new google.maps.Point(9, 45),
                            labelClass: "mapslabels" // the CSS class for the label
                            });
                            
            var contentString = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + ']<br>' +
                'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : ' + device.clastupdated;

//            var infowindow = new google.maps.InfoWindow({
//                content: contentString
//            });
//
//            google.maps.event.addListener(marker, 'click', function () {
//                infowindow.open(map, marker);
//            });

//infoboxjs 
                var boxText = document.createElement("div");
                boxText.style.cssText = "";
                boxText.innerHTML =  "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> "+contentString+"</div></div></div>" ;
                boxText.className = "arrow_box";

                var myOptions = {
                content: boxText
                ,disableAutoPan: false
                ,maxWidth: 0
                ,pixelOffset: new google.maps.Size(-110,-130)
                ,zIndex: null
                ,boxStyle: { 
                opacity: 0.99
                ,width: "280px"
                }
                ,closeBoxMargin: "18px 13px 2px"
                ,closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
                ,infoBoxClearance: new google.maps.Size(1, 1)
                ,isHidden: false
                ,pane: "floatPane"
                ,enableEventPropagation: false
                };

                var ib = new InfoBox(myOptions);
                //ib.open(map, marker);

                google.maps.event.addListener(marker, "click", function (e) {
                ib.open(map, this);

                });
                            

            vehid = device.cvehicleid;
            marker.set("id",device.cvehicleid);
            vehiclesfordel[vehid] = marker;     
            markers.push(marker);
        });  

        jQuery.each(vehiclesfordel, function( index, value ) {        
            if(jQuery("#veh_"+index).is(':checked') == false)
            {
                marker = vehiclesfordel[index];
                if(marker)
                {
                    marker.setMap(null);                          
                }
                
                remove(markers, marker);                
            }
        });                

        if(markers.length != 0)
        {
            markerCluster.clearMarkers();                                
            markerCluster = new MarkerClusterer(map, markers);                
        }
        },
        onComplete: function () {}
    });
}
function refreshdata()
{            
    var params = "all=1";
    new Ajax.Request('route_ajax.php', {
        parameters: params,
        onSuccess: function (transport) {
            var cdata1 = transport.responseText.evalJSON();
            var results = cdata1.result;

            vehiclesfordel = [];
            markers = [];
            
            // Marker Clustering
            results.each(function (device) {

            var image = new google.maps.MarkerImage(device.image,
                        new google.maps.Size(48, 48),
                        new google.maps.Point(0, 0),
                        new google.maps.Point(8, 20));

              var latLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
              var marker = new MarkerWithLabel({'position': latLng, map: map, icon: image, labelContent: device.cname,
                            labelAnchor: new google.maps.Point(9, 45),
                            labelClass: "mapslabels" // the CSS class for the label
                            });

            var contentString = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + ']<br>' +
                'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : ' + device.clastupdated;

//            var infowindow = new google.maps.InfoWindow({
//                content: contentString
//            });
//
//            google.maps.event.addListener(marker, 'click', function () {
//                infowindow.open(map, marker);
//            });

            //infoboxjs 
                var boxText = document.createElement("div");
                boxText.style.cssText = "";
                boxText.innerHTML =  "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> "+contentString+"</div></div></div>" ;
                boxText.className = "arrow_box";

                var myOptions = {
                content: boxText
                ,disableAutoPan: false
                ,maxWidth: 0
                ,pixelOffset: new google.maps.Size(-110,-130)
                ,zIndex: null
                ,boxStyle: { 
                opacity: 0.99
                ,width: "280px"
                }
                ,closeBoxMargin: "18px 13px 2px"
                ,closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
                ,infoBoxClearance: new google.maps.Size(1, 1)
                ,isHidden: false
                ,pane: "floatPane"
                ,enableEventPropagation: false
                };

                var ib = new InfoBox(myOptions);
                //ib.open(map, marker);

                google.maps.event.addListener(marker, "click", function (e) {
                ib.open(map, this);

                });

            vehid = device.cvehicleid;
            marker.set("id",device.cvehicleid);
            vehiclesfordel[vehid] = marker;     
            markers.push(marker);
        });  

        jQuery.each(vehiclesfordel, function( index, value ) {        
            if(jQuery("#veh_"+index).is(':checked') == false)
            {
                marker = vehiclesfordel[index];
                if(marker)
                {
                    marker.setMap(null);                          
                }
                
                remove(markers, marker);                
            }
        });                

        if(markers.length != 0)
        {
            markerCluster.clearMarkers();                                
            markerCluster = new MarkerClusterer(map, markers);                
        }

        // Periodic Update
        periodicupdate();        
            
        },
        onComplete: function () {}
    });    
}

function plotvehicles(cdata) {
    evictMarkers();
    var results = cdata.result;
    results.each(function (device) {
        try {
            function closure() {
                infowindow.close();
            }

            initmap(device.cgeolat, device.cgeolong);
            var image = new google.maps.MarkerImage(device.image,
            new google.maps.Size(48, 48),
            new google.maps.Point(0, 0),
            new google.maps.Point(8, 20));

            var myLatLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);




            var marker = new MarkerWithLabel({
                position: myLatLng,
                icon: image,
                map: map,
                labelContent: device.cname,
                labelAnchor: new google.maps.Point(9, 45),
                labelClass: "mapslabels" // the CSS class for the label
            });



            map.panTo(marker.getPosition());
            eviction_list.push(marker);

            var contentString = '<h3>' + device.cname + '</h3><hr><p>' +
                'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + ']<br>' +
                'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : ' + device.clastupdated +
                '<hr><a href=../history/history.php?id=5&vid=' + device.cvehicleid + '><u>Vehicle History</u> </a>';

//            var infowindow = new google.maps.InfoWindow({
//                content: contentString
//            });
//
//            google.maps.event.addListener(marker, 'mouseover', function () {
//                infowindow.open(map, marker);
//                 window.setInterval(closure, 10000);
//            });

            //infoboxjs 
                var boxText = document.createElement("div");
                boxText.style.cssText = "";
                boxText.innerHTML =  "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> "+contentString+"</div></div></div>" ;
                boxText.className = "arrow_box";

                var myOptions = {
                content: boxText
                ,disableAutoPan: false
                ,maxWidth: 0
                ,pixelOffset: new google.maps.Size(-110,-130)
                ,zIndex: null
                ,boxStyle: { 
                opacity: 0.99
                ,width: "280px"
                }
                ,closeBoxMargin: "18px 13px 2px"
                ,closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
                ,infoBoxClearance: new google.maps.Size(1, 1)
                ,isHidden: false
                ,pane: "floatPane"
                ,enableEventPropagation: false
                };

                var ib = new InfoBox(myOptions);
                //ib.open(map, marker);

                google.maps.event.addListener(marker, "click", function (e) {
                ib.open(map, this);

                });

        } catch (ex) {
            alert(ex);
        }
    });
}

function mapcheckpoints() {
    new Ajax.Request('../../modules/checkpoint/route_ajax.php?chk=all', {
        onSuccess: function (transport) {
            var cdata = transport.responseText.evalJSON();
            if (cdata.result.length !== 0) {
                plotCheckpoints(cdata);
            }
        },
        onComplete: function () {}
    });
}

function plotCheckpoints(cdata) {
    var results = cdata.result;
    results.each(function (device) {
        try {
            var myLatLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);

            /* var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: device.name
                });*/

            var marker = new MarkerWithLabel({
                position: myLatLng,
                map: map,
                labelContent: device.cname,
                labelAnchor: new google.maps.Point(9, 45),
                labelClass: "mapslabels_chkp" // the CSS class for the label
            });

            id = device.checkpointid;
            marker.set("id",device.checkpointid);
            markersfordel[id] = marker;     

            var circle = new google.maps.Circle({
                map: map,
                radius: device.crad,
                fillColor: '#AA0000',
                strokeColor: '#AA0000',
                strokeweight: 1
            });
            circle.set("id",device.checkpointid);
            circlesfordel[id] = circle;     

            
            circle.bindTo('center', marker, 'position');


            var contentString = '<h3>' + device.cname + '</h3><br>Checkpoint Radius [Meters] = ' + device.crad;

            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            google.maps.event.addListener(marker, 'mouseover', function () {
                infowindow.open(map, marker);
            });

            google.maps.event.addListener(marker, 'mouseout', function () {
                infowindow.close();
            });

            eviction_list.push(marker);
            eviction_list.push(circle);
        } catch (ex) {
            alert(ex);
        }
    });

}

function evictMarkers() {
    // clear all markers
    eviction_list.forEach(function (item) {
        item.setMap(null);
    });

    // reset the eviction array 
    eviction_list = [];
}

function delete_all() {

    eviction_list.forEach(function (item) {
        item.setMap(null);
    });

    // reset the eviction array 
    eviction_list = [];
    jQuery("#vehicle_list").html("");

}

function getchk() {
    var devices = "";
    vehicle_list.forEach(function (item) {
        if (item !== undefined) {
            devices = devices + item + ",";
        }
    });
    var params = "vehicleids=" + encodeURIComponent(devices);
    new Ajax.Request('../common/getchkforvehicles.php', {
        parameters: params,
        onSuccess: function (transport) {
            var cdata = transport.responseText.evalJSON();
            plotCheckpoints(cdata);
        },
        onComplete: function () {}
    });
}

function addVehicle() {
    var vehicle_id = $('to').getValue();

    if (vehicle_id > -1 && $('to_vehicle_div_' + vehicle_id) === null) {
        var selected_name = $('to').options[$('to').selectedIndex].text;
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "../../images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function () {
            removevehicle(vehicle_id);
        };
        div.className = 'recipientbox';
        div.id = 'to_vehicle_div_' + vehicle_id;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_vehicle_' + vehicle_id + '" value="' + vehicle_id + '"/>';
        $('vehicle_list').appendChild(div);
        $(div).appendChild(remove_image);
        vehicle_list.push(vehicle_id);
    }
    $('to').selectedIndex = 0;
}

function getselvehicles() {
    var params = "all=1";
    new Ajax.Request('route_ajax.php', {
        parameters: params,
        onSuccess: function (transport) {
            var cdata1 = transport.responseText.evalJSON();
            results = cdata1.result;
            
//            plotvehicles(cdata);
//            getchk();
//            periodicupdate();            
        },
        onComplete: function () {}
    });
}

function removevehicle(id) {
    $('to_vehicle_div_' + id).remove();
    delete vehicle_list[vehicle_list.indexOf(id)];
    getselvehicles.delay(10);
}

function addAllVehicles() {
    var select_box = $('to');
    for (var i = 1; i < select_box.options.length; i++) {
        select_box.selectedIndex = i;
        addVehicle();
    }
    getselvehicles();
}

//function loaded() {
//    initialize();
//    mapvehicles();
//    mapcheckpoints();

//}


Event.observe(window, 'load', function () {
    initialize();
});