var counter = 0;
var load2 = 1;
var map;
var markers = [];
var notifications=[];
var panikid=[];
var address = "";


jQuery.noConflict();
jQuery(document).ready(function () {
    var browserHeight = jQuery(window).height();
    var browserwidth = jQuery(window).width();
   // jQuery("#mapcontainer").css("width", browserwidth - (browserwidth * .15));
	  jQuery("#mapcontainer").css("float","right");
    jQuery("#block-2").css("margin", "200px");
   // jQuery("#mapcontainer").css("height", browserHeight);
    jQuery("#sidebar").css("height", browserHeight);
    jQuery("#tabs").tabs();

onclicktog();
    loaded();
});

function loaded() {
    asynctask();
    initialize();
    if (markers.length > 0) {
        jQuery("#block-2").css("display", "none");
        jQuery("#block-1").css("display", "block");
    }
    
        jQuery('.ui-button').click( function (){ 

                    jQuery.ajax({
                        type:"GET",
                        url:"ajaxpulls.php?work=16",
                        async:true,
                        cache:false,
                        success:function(data){
                           jQuery( ".ui-dialog" ).hide();       
                        },
                        error:function(XMLHttpRequest,testStatus,errorThrown){



                       }
                    });

        });
}


function asynctask() {
    //alert("async");

    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
    markers = [];
    var servicelistid = Array();

    jQuery.ajax({
        type: "GET",
        url: "ajaxpulls.php?work=14",
        async: true,
        cache: false,
        success: function (data) {
            var json = eval('(' + data + ')');
            // session check 
            
             if (json.panik != null) {
               
                jQuery.each( json.panik, function( key, value ) {
                 
                  
                    if(jQuery.inArray(value.id ,panikid) == -1){
                     panikid.push(value.id);      
                    jQuery("#panic_ul").append("<li>"+value.msg+value.msg_time+"</li>");

                      jQuery( "#dialog" ).dialog();    
                    }
                    });
                 
               
             }else{
				 
				 
				 jQuery( "#dialog" ).remove();    
			}
            
            
            
            if (json.status == true) {
                var i = 0;
                jQuery.each(json.servicelist, function (key, value) {
                    servicelistid.push(value.sid);

                    if (jQuery("#s_" + value.sid).length == 0) {
                        // addition condition adding service list
                        add_service_data(value.sid,value.clientname,value.phone,value.trackee,value.status,value.trackee_id,value.lat,value.long);
                    } else {
                        // updating  condition updating service status
                        updateservice_calldata(value.sid,value.status,value.trackee,value.trackee_id,value.lat,value.long);
                    }
                });

			


                jQuery.each(json.tlist, function (key, value) {
                    if (value.trackeeid) {

                        var lablecol;

                        if (value.status == "OPEN") {
                            lablecol = "mapslabels";
                        } else {
                            lablecol = "mapslabelsr";
                        }

                        var imgs = "images/marker.png";
                        var locallatlng = new google.maps.LatLng(value.lat, value.long);
                        var marker = new MarkerWithLabel({
                            position: locallatlng,
                            icon: imgs,
                            map: map,
                            labelContent: value.trackee,
                            labelAnchor: new google.maps.Point(8, 55),
                            labelClass: lablecol // the CSS class for the label
                        });
                        var appendstr;
                        var clientname;

                        if (value.status == "BUSY") {
                            appendstr = " || Status :" + value.status + "";
                            clientname = " " + value.trackee + "";
                        } else {
                            appendstr = "";
                            clientname = " ";
                        }
                       
                        var geocoder = new google.maps.Geocoder();

                        geocoder.geocode({
                            'latLng': locallatlng
                        }, function (results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                address = "<span class='loc'> Near  "+results[1].formatted_address+"</span>  ";
						} else {
                                //alert("Geocode was not successful for the following reason: " + status);
                            }
                        });
							
						//infoboxjs 
						var boxText = document.createElement("div");
						boxText.style.cssText = "";
						boxText.innerHTML =  "<div id='info_window_wrapper'><div id='info_window_header'>"+ value.trackee+" </br> "+address+"</div><div id='info_body'>Last updated "+value.lastupdated+"</div></div>" ;
						boxText.className = "circular";
						
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
						
						

				
                        markers.push(marker);
                        map.panTo(marker.getPosition());

                    }

                });
				
              //  map.setZoom(11);
				// panik and notifications 
				
				
				
				
				
				//jQuery.each(json.notif, function (key, value) {
				//add_notificationdata_data(value.msg,value.id);
				
				//});
				
				
				
				
				
				
				
				
                delete_service_data(servicelistid);
                setTimeout(function () {
                    asynctask();
                }, 30000);


            } else {
                jQuery("#online").html("offline");
                window.location = "index.php";

            }


        }

    });

    //alert(load2++);
    load2++;
    if (load2 == 2) {
        jQuery("#block-2").css("display", "none");
        jQuery("#block-1").css("display", "block");

    }

}



function add_service_data(sid,clientname,phone,trackee,status,trackee_id,lat,long) {
    var tname = '"' + trackee + '"';
    var tlat = '"' + lat + '"';
    var tlong = '"' + long + '"';

    var payload = "";
    payload = "<div id='s_" + sid + "' class='list-item' style='display: block;' onclick='display_trackee_data(" + sid + "," + trackee_id + "," + tname + "," + tlat + "," + tlong + ");'>";
    payload += "<div class='wrapper'>";
     payload += "<div id='cimage'><img src='images/client.png'/></div><div style='float:right;width:83%'>";
    
    payload += "<span class='heading'>" + clientname + " <span style='color:#777;'>(" + phone + ")</span></span> ";
    payload += "<div id='trackeedata'>"+trackee+"</div>";
    payload += "<span class='text'><span class='badge'>";
    payload += "<span id='ss_" + sid + "'>" + status;
    payload += "</span></span></span></div></div></div>";

    jQuery(".list-view").append(payload);
}

function add_notificationdata_data(data,mid) {
	//alert(notifications.toString()+"*"+mid+"*"+jQuery.inArray(notifications, mid));
	 if (jQuery.inArray( mid,notifications) == -1) {
		notifications.push(mid); 
		var payload = "<li>"+data+"</li>";
		jQuery("#notifications2").prepend(payload);
	 }
}



function updateservice_calldata(sid, status, trackee, trackeeid, lat, long) {

    if (jQuery("#ss_" + sid).html() != status) {
        jQuery("#ss_" + sid).html(status);
    };


}

function delete_service_data(servicelistid) {
    var div = jQuery(".list-item");
    jQuery('.list-item').attr('id', function (i, val) {
        var str = val;
        var n = str.split("_");

        //jQuery("#online").append("#"+jQuery.inArray(n[1], servicelistid));
        if (jQuery.inArray(n[1], servicelistid) == -1) {
            //jQuery("#online").append("not found for d_Id"+n[1]+" : delete follows");

            jQuery("#s_" + n[1]).fadeOut(3000, function () {
                jQuery(this).remove();
            })

        }

    });
}

function display_trackee_data(sid, trackee_id, name, lat, long) {

    highlightmarker(name);

    jQuery.ajax({
        type: "POST",
        url: "ajaxpulls.php?work=15",
        async: true,
        data: {
            sid: sid,
            tid: trackee_id,
            name: name
        },
        cache: false,
        success: function (data) {

            jQuery('#trackee').slideDown(500, function () {
                jQuery("#trackee").fadeIn(5500, function () {
                    jQuery("#trackee").css("display", "block");
                    jQuery("#trackee").css("z-index", 1000);
					jQuery(".blue").attr('href',"servicecalls.php?index=View&serviceid="+sid);
					jQuery(".grey").attr('href',"servicecalls.php?index=edit&serviceid="+sid);
					var str=data;
					var n=str.split("**+**");
					jQuery("#trackee_header").html(name);
					
                    jQuery("#tabs-1").html(n[0]);
					 jQuery("#tabs-2").html(n[1]);
                    jQuery("#remicon").html("<a href='javascript: void(0);' class='cross' onclick='javascript:rem_trackee();'></a>");
                });
            });


        }
    });



}



jQuery(document).on("keypress", "#searchbox", function (e) {
    var userVal = jQuery(this).val();

    jQuery(".list-item").each(function () {
        if (jQuery(this).text().match(new RegExp(userVal, "i"))) {
            jQuery(this).show();
        } else if (userVal == "") {
            jQuery(this).show();
        } else {
            jQuery(this).hide();
        }
    });

    e.stopPropagation();
});


function rem_trackee() {
    jQuery('#trackee').css('display', 'none');

}


function onclicktog() {
    $('sidebar').toggle(900);
    if (counter % 2 == 0) {
        jQuery('#next').css("display", "block");
        jQuery('#pre').css("display", "none");
        jQuery('#maptoggler').css("left", "0px");
        jQuery('#mapcontainer').css("width", "100%");

        counter++;
    } else {

        jQuery('#pre').css("display", "block");
        jQuery('#next').css("display", "none");
        jQuery('#maptoggler').css("left", "17.59%");
        jQuery('#mapcontainer').css("width", "82.2%");
        counter++;
    }

}




function initialize() {
    var mumbai = new google.maps.LatLng(18.9647, 72.8258);
    var mapOptions = {
        zoom: 11,
        center: mumbai,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById('mapcontainer'), mapOptions);
}


function highlightmarker(name) {
    for (var i = 0; i < markers.length; i++) {
        if (markers[i].labelContent.toString() == name) {
            var pos = markers[i].position;
            map.panTo(pos);
           map.setZoom(13);
        }
    }
}
