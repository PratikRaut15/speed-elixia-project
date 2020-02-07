// JavaScript Document
var toggle_counter = 2;
var previousid = 0;
var globalid = 0;
var eviction_list = [];
var periodic = true;
var styles =[
{ "featureType": "road", "elementType": "geometry.stroke", "stylers": [ { "visibility": "off" } ] },{ "featureType": "poi.park", "stylers": [ { "visibility": "simplified" }, { "lightness": 46 } ] },{ "featureType": "poi", "elementType": "labels", "stylers": [ { "visibility": "on" } ] },{ "featureType": "road.highway", "elementType": "labels", "stylers": [ { "visibility": "off" } ] },{ "featureType": "road.highway", "elementType": "geometry.fill", "stylers": [ { "color": "#9e9e9f" } ] },{ "featureType": "road.arterial", "elementType": "geometry.fill", "stylers": [ { "color": "#bfbfbf" } ] },{ "featureType": "road.local", "elementType": "geometry.fill", "stylers": [ { "color": "#e0e0e0" } ] },{ "featureType": "poi.park", "stylers": [ { "lightness": 38 } ] },{ "stylers": [ { "saturation": -54 } ] }];

function call_row(id) {
	// if unassigned 
	if (previousid == 0 || previousid != id) {
		if (previousid == 0) {
			previousid = id;
			jQuery('#iv_' + id).attr("src", "../../images/show.png");
		}
		if (previousid != id) {
			jQuery('.iv_all').attr("src", "../../images/show.png");
			jQuery('#iv_' + id).attr("src", "../../images/hide.gif");
                        periodic = true;
		}
	}
	if (previousid == id) {
		jQuery('.iv_all').attr("src", "../../images/show.png");
		jQuery('#iv_' + id).attr("src", "../../images/hide.gif");
		jQuery("#floatmap").remove();
	} else {
		if (toggle_counter % 2 !== 0) {
			toggle_counter++;
		}
	}
	if (toggle_counter % 2 === 0) {
		if (jQuery("#floatmap").length) {
			jQuery("#floatmap").remove();
		}
		jQuery("#" + id).toggle();
		jQuery("#" + id).after("<tr id='floatmap'><td  colspan='9'><div id=load></div><div id='map'  style='height:330px;'></div></td><td colspan='5'><div id='v_des' ></div></td></tr>").slideDown('slow');
		if (id) {
			jQuery.ajax({
				type: "GET",
				url: "rtd_ajax.php?vehicleid=" + id,
				async: true,
				cache: false,
				success: function (data) {          
					jQuery("#v_des").html(data);
					var str = jQuery("#latlong" + id).val();
					var vehiclename = jQuery("#vehicle" + id).val();
					var vehicleimage = jQuery("#vehicleimage" + id).val();                                        
					var partsOfStr = str.split(',');
					var latlng = new google.maps.LatLng(partsOfStr[0], partsOfStr[1]);
                                        
                                        jQuery.bind(  data, refreshdata );
                                        
                                    var styledMap = new google.maps.StyledMapType(styles,
                                    {name: "Styled Map"});

                                    var myOptions = {
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
                                    map = new google.maps.Map(document.getElementById('map'), myOptions);
                                        map.mapTypes.set('map_style', styledMap);
                                        map.setMapTypeId('map_style');

                                    var image = new google.maps.MarkerImage(vehicleimage,
                                                new google.maps.Size(48, 48),
                                                new google.maps.Point(0, 0),
                                                new google.maps.Point(8, 20));
                                    
                                    var marker = new MarkerWithLabel({position: latlng, map: map, icon: image, labelContent: vehiclename,
                                                    labelAnchor: new google.maps.Point(9, 45),
                                                    labelClass: "mapslabels" // the CSS class for the label
                                                    });

                                    eviction_list.push(marker);

					jQuery('.chk_latlong').each(function () {
						var circle;
						var rad;
						var chklatlng;
						chklatlng = jQuery(this).val();
						var partsOfStr = chklatlng.split(',');
						var chkrad = partsOfStr[2];
						var chkname = partsOfStr[3];
						var chklatlngp = new google.maps.LatLng(partsOfStr[0], partsOfStr[1]);
                                                
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
							fillColor: '#AA0000',
							strokeColor: '#AA0000',
							strokeweight: 1
						});
						circle.bindTo('center', marker, 'position');
					});
					// fence mapping on map
					jQuery('.fence_latlong').each(function () {});
					//Edit Vehicle checkpoint and fences
					jQuery(".editbox").hide();
					jQuery(".edit_chkname").dblclick(function () {
						var ID = jQuery(this).attr('rel');
						var chkid = jQuery(this).attr('id');
						jQuery("#chkname_" + ID + "_" + chkid).hide();
						jQuery("#chkname_input_" + ID + "_" + chkid).show();
						jQuery("#chkrad_" + ID + "_" + chkid).hide();
						jQuery("#chkrad_input_" + ID + "_" + chkid).show();
					}).change(function () {
						var ID = jQuery(this).attr('rel');
						var chkid = jQuery(this).attr('id');
						var chkname = jQuery("#chkname_input_" + ID + "_" + chkid).val();
						var chkrad = jQuery("#chkrad_input_" + ID + "_" + chkid).val();
						var dataString = 'chkid=' + chkid + '&chkname=' + chkname + '&chkrad=' + chkrad;
						jQuery("#chkname_" + ID + "_" + chkid).html('<img src="load.gif" />'); // Loading image
						jQuery("#chkrad_" + ID + "_" + chkid).html('<img src="load.gif" />'); // Loading image
                                                if(chkname && chkrad)
                                                {
                                                    if (chkname.length > 0 && chkrad.length > 0) {
                                                            jQuery.ajax({
                                                                    type: "POST",
                                                                    url: "../checkpoint/route_ajax.php",
                                                                    data: dataString,
                                                                    cache: false,
                                                                    success: function (html) {
                                                                            jQuery("#chkname_" + ID + "_" + chkid).html(chkname);
                                                                            jQuery("#chkrad_" + ID + "_" + chkid).html(chkrad);
                                                                            call_row_load(ID);
                                                                    }
                                                            });
                                                    } else {
                                                            alert('Enter something.');
                                                    }
                                                }
					});
					//edit fence
					jQuery(".edit_fence").dblclick(function () {
						var ID = jQuery(this).attr('rel');
						var fenceid = jQuery(this).attr('id');
						jQuery("#fencename_" + ID + "_" + fenceid).hide();
						jQuery("#fencename_input_" + ID + "_" + fenceid).show();
					}).change(function () {
						var ID = jQuery(this).attr('rel');
						var fenceid = jQuery(this).attr('id');
						var fencename = jQuery("#fencename_input_" + ID + "_" + fenceid).val();
						var dataString = 'fenceid=' + fenceid + '&fencename=' + fencename;
						jQuery("#fencename_" + ID + "_" + fenceid).html('<img src="load.gif" />'); // Loading image
						if (fencename.length > 0) {
							jQuery.ajax({
								type: "POST",
								url: "../fencing/route_ajax.php",
								data: dataString,
								cache: false,
								success: function (html) {
									jQuery("#fencename_" + ID + "_" + fenceid).html(fencename);
									call_row_load(ID);
								}
							});
						} else {
							alert('Enter something.');
						}
					});
					// Edit input box click action
					jQuery(".editbox").mouseup(function () {
						return false
					});
					// Outside click action
					jQuery(document).mouseup(function () {
						jQuery(".editbox").hide();
						jQuery(".text").show();
					});
					jQuery('.editbox').keypress(function (e) {
						if (e.which == 13) {
							//alert('The enter key was pressed!');
							jQuery(".editbox").hide();
							jQuery(".text").show();
						}
					});

                                            periodic = true;
                                            // Periodic Update
                                            globalid = id;                                        
                                            periodicupdate();                                                
				}
			});
		}
		jQuery("#load").animate({
			width: ['toggle', 'swing']
		}, 2000, function () {
			// Animation complete.
		});
		var str = jQuery("#latlong" + id).val();
		var vehiclename = jQuery("#vehicle" + id).val();
		var partsOfStr = str.split(',');
		//init_map_realtime(partsOfStr[0],partsOfStr[1],vehiclename);
		toggle_counter++;
	} else {
                periodic = false;
		toggle_counter++;
		jQuery('#iv_' + id).attr("src", "../../images/show.png");
	}
	previousid = id;
}

function periodicupdate() {
if(periodic == true)
{
    refreshdata.delay(60);
}
}

function refreshdata()
{
    if(periodic == true)
    {
        var params = "vehicleids=" + globalid;
        new Ajax.Request('rtd_map_ajax.php', {
            parameters: params,
            onSuccess: function (transport) {
                evictmarkers();
                var cdata1 = transport.responseText.evalJSON();
                var results = cdata1.result;

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
                  map.setCenter(latLng);
                                
                eviction_list.push(marker);
                                
                jQuery('#map_table .ebcl_'+globalid).html(device.extbatt);                                                                                                                                                                                                                    
                jQuery('#map_table .ibcl_'+globalid).html(device.inbatt);                                                                                                                                                                                                                    
                jQuery('#map_table .gsmcl_'+globalid).attr("src", device.gsmimg);                                                                                                                                                                                                                    
                jQuery('#map_table .gsmcl_'+globalid).attr("title", device.network);                                                                                                                                                                                                                                                                
            });             
            
            periodicupdate();
            },
            onComplete: function () {}
        });        
    }
}

function deletefence(fenceid, fencename) {
	var fenceid = jQuery("#del_fence").val();
	var vehicleid = jQuery("#del_fence_vehid").val();
	var dataString = 'fenceid=' + fenceid + '&vehicleid=' + vehicleid;
	jQuery.ajax({
		type: "POST",
		url: "../fencing/route_ajax.php",
		data: dataString,
		cache: false,
		success: function (html) {
			jQuery("#f_" + fenceid).remove();
			jQuery("#delete_popup").hide();
			jQuery(".popup").hide();
			call_row_load(vehicleid);
		}
	});
}

function deletechkpoint(chkid, chkname) {
	var chkid = jQuery("#del_chkpoint").val();
	var vehicleid = jQuery("#del_chk_vehid").val();
	var dataString = 'chkid=' + chkid + '&vehicleid=' + vehicleid;
	jQuery.ajax({
		type: "POST",
		url: "../checkpoint/route_ajax.php",
		data: dataString,
		cache: false,
		success: function (html) {
			jQuery("#c_" + chkid).remove();
			jQuery("#delete_chkpopup").hide();
			jQuery(".popup").hide();
			call_row_load(vehicleid);
		}
	});
}

function deleteecode(ecodeid, vehid) {
	var ecodeid = jQuery("#del_ecode").val();
	var vehicleid = jQuery("#del_ecode_vehid").val();
	var dataString = 'ecodeid=' + ecodeid + '&vehicleid=' + vehicleid;
	jQuery.ajax({
		type: "POST",
		url: "../ecode/route_ajax.php",
		data: dataString,
		cache: false,
		success: function (html) {
			jQuery("#e_" + ecodeid).remove();
			jQuery("#delete_ecodepopup").hide();
			jQuery(".popup").hide();
			call_row_load(vehicleid);
		}
	});
}
//function close_modal()
//{
//    jQuery("#delete_popup").hide();
//    jQuery(".popup").toggle();
//}
jQuery(document).ready(function () {
	jQuery('.helper').tipsy({
		gravity: 'se'
	});
	jQuery('.always_show').tipsy({
		gravity: 'se'
	});
	jQuery('.tooltip-top').tipsy({
		gravity: 'se'
	});
	jQuery('.tooltip-right').tipsy({
		gravity: 'w'
	});
});
//function close_chkmodal()
//{
//    jQuery("#delete_chkpopup").toggle();
//    jQuery(".popup").toggle();
//}
//function close_ecodemodal()
//{
//    jQuery("#delete_ecodepopup").toggle();
//    jQuery(".popup").toggle();
//}
function call_row_load(id) {
	jQuery.ajax({
		type: "GET",
		url: "rtd_ajax.php?vehicleid=" + id,
		async: false,
		cache: false,
		success: function (data) {
			jQuery("#v_des").html(data);
			var str = jQuery("#latlong" + id).val();
			var vehiclename = jQuery("#vehicle" + id).val();
                        var vehicleimage = jQuery("#vehicleimage" + id).val();                                                                
			var partsOfStr = str.split(',');
			var latlng = new google.maps.LatLng(partsOfStr[0], partsOfStr[1]);
                                    var styledMap = new google.maps.StyledMapType(styles,
                                    {name: "Styled Map"});

                                    var myOptions = {
                                                zoom: 15,
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
                                    map = new google.maps.Map(document.getElementById('map'), myOptions);
                                        map.mapTypes.set('map_style', styledMap);
                                        map.setMapTypeId('map_style');

                                    var image = new google.maps.MarkerImage(vehicleimage,
                                                new google.maps.Size(48, 48),
                                                new google.maps.Point(0, 0),
                                                new google.maps.Point(8, 20));

                                    var imagechk = new google.maps.MarkerImage("http://elixiatech.com/speed/images/marker_icon.png");
                                    
                                      var marker = new MarkerWithLabel({position: latlng, map: map, icon: image, labelContent: vehiclename,
                                                    labelAnchor: new google.maps.Point(9, 45),
                                                    labelClass: "mapslabels" // the CSS class for the label
                                                    });
			jQuery('.chk_latlong').each(function () {
						var circle;
						var rad;
						var chklatlng;
						chklatlng = jQuery(this).val();
						var partsOfStr = chklatlng.split(',');
						var chkrad = partsOfStr[2];
						var chkname = partsOfStr[3];
						var chklatlngp = new google.maps.LatLng(partsOfStr[0], partsOfStr[1]);
                                                
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
							fillColor: '#AA0000',
							strokeColor: '#AA0000',
							strokeweight: 1
						});
						circle.bindTo('center', marker, 'position');
			});
			//Edit Vehicle checkpoint and fences
			jQuery(".editbox").hide();
			jQuery(".edit_chkname").dblclick(function () {
				var ID = jQuery(this).attr('rel');
				var chkid = jQuery(this).attr('id');
				jQuery("#chkname_" + ID + "_" + chkid).hide();
				jQuery("#chkname_input_" + ID + "_" + chkid).show();
				jQuery("#chkrad_" + ID + "_" + chkid).hide();
				jQuery("#chkrad_input_" + ID + "_" + chkid).show();
			}).change(function () {
				var ID = jQuery(this).attr('rel');
				var chkid = jQuery(this).attr('id');
				var chkname = jQuery("#chkname_input_" + ID + "_" + chkid).val();
				var chkrad = jQuery("#chkrad_input_" + ID + "_" + chkid).val();
				var dataString = 'chkid=' + chkid + '&chkname=' + chkname + '&chkrad=' + chkrad;
				jQuery("#chkname_" + ID + "_" + chkid).html('<img src="load.gif" />'); // Loading image
				jQuery("#chkrad_" + ID + "_" + chkid).html('<img src="load.gif" />'); // Loading image
                                if(chkname && chkrad)
                                {
                                    if (chkname.length > 0 && chkrad.length > 0) {
                                            jQuery.ajax({
                                                    type: "POST",
                                                    url: "../checkpoint/route_ajax.php",
                                                    data: dataString,
                                                    cache: false,
                                                    success: function (html) {
                                                            jQuery("#chkname_" + ID + "_" + chkid).html(chkname);
                                                            jQuery("#chkrad_" + ID + "_" + chkid).html(chkrad);
                                                            call_row_load(ID);
                                                    }
                                            });
                                    } else {
                                            alert('Enter something.');
                                    }
                                }
			});
			//edit fence
			jQuery(".edit_fence").dblclick(function () {
				var ID = jQuery(this).attr('rel');
				var fenceid = jQuery(this).attr('id');
				jQuery("#fencename_" + ID + "_" + fenceid).hide();
				jQuery("#fencename_input_" + ID + "_" + fenceid).show();
			}).change(function () {
				var ID = jQuery(this).attr('rel');
				var fenceid = jQuery(this).attr('id');
				var fencename = jQuery("#fencename_input_" + ID + "_" + fenceid).val();
				var dataString = 'fenceid=' + fenceid + '&fencename=' + fencename;
				jQuery("#fencename_" + ID + "_" + fenceid).html('<img src="load.gif" />'); // Loading image
				if (fencename.length > 0) {
					jQuery.ajax({
						type: "POST",
						url: "../fencing/route_ajax.php",
						data: dataString,
						cache: false,
						success: function (html) {
							jQuery("#fencename_" + ID + "_" + fenceid).html(fencename);
							call_row_load(ID);
						}
					});
				} else {
					alert('Enter something.');
				}
			});
			// Edit input box click action
			jQuery(".editbox").mouseup(function () {
				return false
			});
			// Outside click action
			jQuery(document).mouseup(function () {
				jQuery(".editbox").hide();
				jQuery(".text").show();
			});
			jQuery('.editbox').keypress(function (e) {
				if (e.which == 13) {
					//alert('The enter key was pressed!');
					jQuery(".editbox").hide();
					jQuery(".text").show();
				}
			});
			//edit chkpoint and fences complete here
		}
	});
	jQuery("#load").animate({
		width: ['toggle', 'swing']
	}, 2000, function () {
		// Animation complete.
	});
	var str = jQuery("#latlong" + id).val();
	var vehiclename = jQuery("#vehicle" + id).val();
	var partsOfStr = str.split(',');
	//init_map_realtime(partsOfStr[0],partsOfStr[1],vehiclename);
	toggle_counter++;
	previousid = id;
}

Event.observe(window, 'load', function () {
    loadrefresh();
});

function loadrefresh()
{
        var params = "all=1";
        new Ajax.Request('rtd_map_ajax.php', {
            parameters: params,
            onSuccess: function (transport) {
                var cdata1 = transport.responseText.evalJSON();
                var results = cdata1.result;

                results.each(function (device) {
                    jQuery('#'+device.cvehicleid+' .lupd').html(device.clastupdated);
                    jQuery('#'+device.cvehicleid+' .loccl').html(device.location);                                                        
                    jQuery('#'+device.cvehicleid+' .speedcl').html(device.cspeed);                                                                                    
                    jQuery('#'+device.cvehicleid+' .distcl').html(device.totaldist);                                                                                                                
                    jQuery('#'+device.cvehicleid+' .tampercl').attr("src",device.tamper);                                                                                                                                            
                    jQuery('#'+device.cvehicleid+' .tampercl').attr("title",device.tampertitle);                                                                                                                                                                        
                    jQuery('#'+device.cvehicleid+' .pccl').attr("src",device.pc);                                                                                                                                            
                    jQuery('#'+device.cvehicleid+' .pccl').attr("title",device.pctitle);                                                                                                                                                                                                                    
                    jQuery('#'+device.cvehicleid+' .loadcl').html(device.msgkey);                                                                                                                                    
                    jQuery('#'+device.cvehicleid+' .accl').html(device.acsensor);             
                    if(device.tempon == 1)
                    {
                        jQuery('#'+device.cvehicleid+' .tempcl').html(device.temp + " 0".sup() + "C");                                                                                                                                                                            
                    }
                    else
                    {
                        jQuery('#'+device.cvehicleid+' .tempcl').html(device.temp);                                                                                                                                                                            
                    }
            });         

                periodicupdateloadrefresh();
            },
            onComplete: function () {}
        });            
}

function periodicupdateloadrefresh()
{
    loadrefresh.delay(60);
}

function evictmarkers() {
    // clear all markers
    eviction_list.forEach(function (item) {
        item.setMap(null);
    });

    // reset the eviction array 
    eviction_list = [];
}
