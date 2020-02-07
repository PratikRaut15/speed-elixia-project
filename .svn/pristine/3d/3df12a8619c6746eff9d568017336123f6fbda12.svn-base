var gmapsinited=false;
var eviction_list = [];
var route = [];
var vehicleid;
var device;
var play=0;

//aluto height adjust
jQuery.noConflict();
jQuery(document).ready(function() {
loaded();
// Handler for .ready() called.
var browserHeight = jQuery(window).height();
jQuery("#map").css("width",jQuery(".onecolumn").width()-45);
jQuery("#map").css("height",jQuery("#tab1_content").height()+500);
});


function getUrlVars() 
{
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) 
    {vars[key] = value;});
    return vars;
}
function initialize() 
{
	var latlng = new google.maps.LatLng(20, 72);
	var myOptions = {zoom: 5, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP};
	var map = new google.maps.Map(document.getElementById("map"), myOptions);        
}

function initmap( lat,lng )
{
    if(gmapsinited)return;
    var latlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
      zoom: 15,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map($("map"), myOptions);
    gmapsinited=true;
}

function getroutehist()
{
    var SDDate = ($('SDate').value).split('-');
    SDDate = new Date(SDDate[2],SDDate[1]-1,SDDate[0])
    var EDDate = ($('EDate').value).split('-');
    EDDate = new Date(EDDate[2],EDDate[1]-1,EDDate[0])
    if(SDDate>EDDate)
    {
        $('error3').show();
        jQuery('#error3').fadeOut(3000);
    }
    else if(play==0)
    {
        play=1;
        vehicleid = "vehicleid=" + encodeURIComponent($('trackeeid').value);
        var SDate = "&SDate=" + encodeURIComponent($('SDate').value);
        var EDate = "&EDate=" + encodeURIComponent($('EDate').value);
        var Shour = "&Shour=" + encodeURIComponent($('Shour').value);
        var Ehour = "&Ehour=" + encodeURIComponent($('Ehour').value);
        var params = vehicleid + SDate + EDate + Shour + Ehour;
        new Ajax.Request('routehistory_ajax.php',
        {
            parameters: params,
            onSuccess: function(transport)
            {
                var cdata = transport.responseText.evalJSON();
                if(cdata.length!=0)
                {
                    plotvehiclehist(cdata);
                }
                else
                {
                    $('error2').show();
                    jQuery('#error2').fadeOut(3000);
                    play=0;
                }
                
            },
            onComplete: function()
            {
            }
        });
    }
    else
        $('error').show();
        jQuery('#error').fadeOut(3000);
        
}

function plotvehiclehist(results)
{
    evictMarkers();
    route = [];
    var index = 0;
    var plots = results.length;
    function plot()
    {
        var device = results[index];
        if(index<plots)
        {
            try
            {   
                function closure()
                {
                    infowindow.close();
                }
                
                initmap(device.cgeolat, device.cgeolong);
                var image = new google.maps.MarkerImage( device.image,
                    new google.maps.Size(48,48),
                    new google.maps.Point(0,0),
                    new google.maps.Point(8,20));

                var myLatLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
                route.push(myLatLng);
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    icon: image,
                    title: device.ctname
                    });
                map.panTo(marker.position);

                eviction_list.push(marker);   

                var contentString = '<h3 id="maphead">'+ device.ctname +'</h3><hr><p>' + device.clastupdated + '<br/>' + device.geotag;

                var infowindow = new google.maps.InfoWindow({content: contentString});
                google.maps.event.addListener(marker,'click',function(){
                    infowindow.open(map,marker);
                    window.setInterval(closure, 5000);

                });
                
            }
            catch( ex)
            {
                alert(ex);
            }
        }
        else
        {
        return;
        }
    index++;
    cpolyline();
    }
    plot();
    if(index<plots)
    {
        window.setInterval(plot, 1500);
    }
}

function cpolyline()
{
    var polyline = new google.maps.Polyline(
    {
        path: route,
        strokeColor: "#ff0000",
        strokeWeight: 3,
        strokeOpacity: 0.4

    });
    polyline.setMap(map);
}

function evictMarkers()
{
    // clear all markers
    eviction_list.forEach(function(item) 
    { 
        item.setMap(null) 
    });

    // reset the eviction array 
    eviction_list = [];
}

function refreshrh()
{
    window.location.href = window.location.href;
}

function loaded()
{
    initialize();
}


jQuery(document).ready(function($) {
// Code using $ as usual goes here.
loaded();
});