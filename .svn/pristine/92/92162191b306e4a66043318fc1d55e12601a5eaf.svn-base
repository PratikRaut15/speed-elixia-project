var gmapsinited = false;
var eviction_list = [];
var ecodeid;
//var vehicleid;
var candy;
var cdata;
var firsttime = 0;

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

jQuery.noConflict();
jQuery(document).ready(function () {
	var browserHeight = jQuery(window).height();
	jQuery('#content').css("height", browserHeight - 190);
	jQuery('#sidebar').css("height", browserHeight - 190);
	jQuery('#map').css("height", browserHeight - 160);
        jQuery(".all_select").click(function () {
            selectall(jQuery(this).data('type'));
        });
        jQuery(".all_clear").click(function () {
            clearall(jQuery(this).data('type'));
        });
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

function initialize_OLD() {
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });
	var latlng = new google.maps.LatLng(20, 72);
	var myOptions = {
		zoom: 5,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	var map = new google.maps.Map(document.getElementById("map"), myOptions);
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

function initmap(lat, lng) {
    var styledMap = new google.maps.StyledMapType(styles, {
        name: "Styled Map"
    });
	if (gmapsinited) return;
	var latlng = new google.maps.LatLng(lat, lng);
	var myOptions = {
		zoom: 11,
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
	map = new google.maps.Map(document.getElementById("map"), myOptions);
        map.mapTypes.set('map_style', styledMap);
        map.setMapTypeId('map_style');
	gmapsinited = true;
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

    ecodeid = jQuery('#ecodeid').val();
    var params = "ecodeid=" + encodeURIComponent(ecodeid);                
    jQuery.ajax({
    type: "POST",
    data: params,
    url: "modules/ecode/route_ajax.php?get=all",
    cache: false,
    datatype: "json",
    success: function (data) {
            var cdata1 = jQuery.parseJSON(data);
            var results = cdata1.result;
            
            var count = cdata1.result.length;
            if (count != 0) {
                                if (firsttime == 0) {
                                        GetDropDownValues(cdata1);
                                }
                                firsttime = 1;
            jQuery('#eshow').show();
            jQuery('#android').hide();
            jQuery('#map').show();
            //GetDropDownValues(cdata1);
            // Marker Clustering
            markers = [];
            var bounds = new google.maps.LatLngBounds();

            jQuery.each(results, function (i, device) {
                var image = new google.maps.MarkerImage(device.image,
                new google.maps.Size(48, 48),
                new google.maps.Point(0, 0),
                new google.maps.Point(8, 20));

                var latLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);

                var marker = new MarkerWithLabel({
                    position: latLng,
                    map: map,
                    icon: image,
                    labelContent: device.cname,
                    labelAnchor: new google.maps.Point(9, 45),
                    labelClass: "mapslabels" // the CSS class for the label
                });


                bounds.extend(marker.position);
                var contentString = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + ']<br>' +
                    'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : ' + device.clastupdated;

                //infoboxjs 
                var boxText = document.createElement("div");
                boxText.style.cssText = "";
                boxText.innerHTML = "<div class='circular'><div id='info_window_wrapper'><div id='info_body'> " + contentString + "</div></div></div>";
                boxText.className = "arrow_box";

                var myOptions = {
                    content: boxText,
                    disableAutoPan: false,
                    maxWidth: 0,
                    pixelOffset: new google.maps.Size(-110, -130),
                    zIndex: null,
                    boxStyle: {
                        opacity: 0.99,
                        width: "280px"
                    },
                    closeBoxMargin: "18px 13px 2px",
                    closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
                    infoBoxClearance: new google.maps.Size(1, 1),
                    isHidden: false,
                    pane: "floatPane",
                    enableEventPropagation: false
                };

                var ib = new InfoBox(myOptions);
                //ib.open(map, marker);

                google.maps.event.addListener(marker, "click", function (e) {
                    ib.open(map, this);

                });



                vehid = device.cvehicleid;
                marker.set("id", device.cvehicleid);
                vehiclesfordel[vehid] = marker;
                markers.push(marker);

            });

            map.fitBounds(bounds);

            markerCluster = new MarkerClusterer(map, markers);
            periodicupdate();
            
            } else {
                        alert("Incorrect/ Invalid code");
                        jQuery('#eshow').hide();
                        var candy = 0;
                        jQuery('#map').hide();
                        jQuery('#ehide').show();
                        jQuery("#eecode").show();
                        jQuery("#eecode").fadeOut(5000);
                    }
        
                    },
            complete: function () {
//                    if (candy == 0) {
//                            jQuery('#ehide').show();
//                            jQuery("#eecode").show();
//                            jQuery("#eecode").fadeOut(5000);
//                    }
            }
    });


}

function mapvehicles() {
	vehicleid = jQuery('#vehicleid').val()
	if (vehicleid == "Select Vehicle" || vehicleid == 'all') {
		ecodeid = jQuery('#ecodeid').val();
		var params = "ecodeid=" + encodeURIComponent(ecodeid);                
                jQuery.ajax({
                    type: "POST",
                    data: params,
                    url: "modules/ecode/route_ajax.php?get=all",
                    cache: false,
                    datatype: "json",
                    success: function (data) {
                        var cdata = jQuery.parseJSON(data);
                        var count = cdata.result.length;
                        if (count != 0) {
                                            plotvehicles(cdata);
                                            if (firsttime == 0) {
                                                    GetDropDownValues(cdata);
                                            }
                                            firsttime = 1;
                                            periodicupdate();
                                            jQuery('#eshow').show();
                                    } else {
                                            alert("Incorrect/ Invalid code");
                                            jQuery('#eshow').hide(100);
                                            var candy = 0;
                                            jQuery('#map').hide();
                                            //jQuery('#ehide').show();
                                            jQuery("#eecode").show();
                                            jQuery("#eecode").fadeOut(5000);
                                    }
                            },
                    complete: function () {
                            if (candy == 0) {
                                    //jQuery('#ehide').show();
                                    jQuery("#eecode").show();
                                    jQuery("#eecode").fadeOut(5000);
                            }
                    }
                });
                
	} else {
		getvehicle();
		periodicupdate();
	}
        
}

function periodicupdate_OLD() {
	//mapvehicles.delay(30);
        
setTimeout(function() {
	mapvehicles();      // Do something after 2 seconds
	}, 30000);
}

function periodicupdate() {
	setTimeout(function () {
                    refreshdata();
                }, 60000);

	
   
}

function refreshmap()
{
    //jQuery("#displaydata").load();  
    ecodeid = jQuery('#ecodeid').val();
		var params = "ecodeid=" + encodeURIComponent(ecodeid);                
                jQuery.ajax({
                    type: "POST",
                    data: params,
                    url: "modules/ecode/route_ajax.php?get=all",
                    async: true,
                    cache: false,
                    datatype: "json",
                    success: function (data) {
			  var cdata1 = jQuery.parseJSON(data);
                    var results = cdata1.result;

			
            vehiclesfordel = [];
            markers = [];
            
            // Marker Clustering
         
			 jQuery.each(results, function (i, device) {
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
        
			
		}
		
	});
	
	
	
   
}

function refreshdata()
{    
    ecodeid = jQuery('#ecodeid').val();
		var params = "ecodeid=" + encodeURIComponent(ecodeid);                
                jQuery.ajax({
                    type: "POST",
                    data: params,
                    url: "modules/ecode/route_ajax.php?get=all",
                    async: true,
                    cache: false,
                    datatype: "json",
                    success: function (data) {
                    var cdata1 = jQuery.parseJSON(data);
                    var results = cdata1.result;
			

            vehiclesfordel = [];
            markers = [];
            
            // Marker Clustering
             jQuery.each(results, function (i, device) {

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
            
        	
		}
		
	});


     
}


function plotvehicles(cdata) {
	evictMarkers();
	var results = cdata.result;
        jQuery.each(results, function (i, device) {
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
                        var marker = new MarkerWithLabel({'position': myLatLng, map: map, icon: image, labelContent: device.cname,
                            labelAnchor: new google.maps.Point(9, 45),
                            labelClass: "mapslabels" // the CSS class for the label
                            });
                            
			map.panTo(marker.getPosition());
			eviction_list.push(marker);
                            
            var contentString = 'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + ']<br>' +
                'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : ' + device.clastupdated;


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
//			var marker = new google.maps.Marker({
//				position: myLatLng,
//				map: map,
//				icon: image,
//				title: device.cname
//			});
//			map.panTo(marker.getPosition());
//			eviction_list.push(marker);
//			var contentString = '<h3>' + device.cname + '</h3><hr><p>' +
//				'Driver : ' + device.cdrivername + ' [' + device.cdriverphone + ']<br>' +
//				'Current Speed : ' + device.cspeed + ' km/hr<br> Last Updated : ' + device.clastupdated;
//			var infowindow = new google.maps.InfoWindow({
//				content: contentString
//			});
//			google.maps.event.addListener(marker, 'mouseover', function () {
//				infowindow.open(map, marker);
//				window.setInterval(closure, 5000);
//			});
        } catch (ex) {
            alert(ex);
        }
    });

}

function getvehicle() {
	vehicleid = jQuery('#vehicleid').val()
	if (vehicleid != "Select Vehicle" && vehicleid != 'all') {
		evictMarkers();
		var params = "vehicleid=" + encodeURIComponent(vehicleid);
                
                jQuery.ajax({
                    type: "POST",
                    url: "modules/ecode/route_ajax.php?get=1",
                    cache: false,
                    data: params,
                    success: function (data) {
                        var cdata = jQuery.parseJSON(data);
				plotvehicles(cdata);
                    },
                    complete: function () {}
                });
                

	} else if (vehicleid == 'all') {
		mapvehicles()
	}
}

function evictMarkers() {
	// clear all markers
	eviction_list.forEach(function (item) {
		item.setMap(null)
	});
	// reset the eviction array 
	eviction_list = [];
}

function GetDropDownValues(values) {
	var results = values.result;
//	results.each(function (device) {
//		AddItem(device.cname, device.cvehicleid);
//	});
        jQuery.each(results,function(i,device){
		try {
			//AddItem1(device.cname, device.cvehicleid);
                        AddItem(device.cname, device.cvehicleid);
		} catch (ex) {
			alert(ex);
		}
	});
}

function AddItem(name,id) {
   var container = jQuery('.scrollablediv');
   //var inputs = container.find('input');

   //jQuery('<input />', { type: 'checkbox', class:'veh_all', onclick:'vehplot('+id+');', id: 'veh_'+id, value: name, checked:'checked' }).appendTo(container);
   //jQuery("<label>"+name+"<label />").appendTo(container);
   
   var ctrl =  jQuery(document.createElement("input")).attr({
                     id:    'veh_'+id
                    ,value: name
                    ,name: name
                    ,onclick: 'vehplot('+id+');'
                    ,class:'veh_all'
                    ,text :'my testing'
                    ,type:  'checkbox'
                    ,checked:true
            })

var lbl =  '<label>'+name+'</label>';


 jQuery('.scrollablediv').append(ctrl.after(lbl));
   
}

//function AddItem1(Text, Value) {
//	// Create an Option object
//	var opt = document.createElement("option");
//	// Add an Option object to Drop Down/List Box
//	document.getElementById("vehicleid").options.add(opt);
//	// Assign text and value to Option object
//	opt.text = Text;
//	opt.value = Value;
//}

function getout() {
	window.location = 'index.php';
	
}