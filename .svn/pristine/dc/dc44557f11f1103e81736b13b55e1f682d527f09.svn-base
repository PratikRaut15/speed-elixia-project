var gmapsinited=false;
var gmapsinitedsender=false;
var geocodeinited=false;
var geocodeinitedsender=false;
var map;
var mapsender;
var geocoder;
var geocodersender;
var eviction_list = [];

function getUrlVars() 
{
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) 
    {vars[key] = value;});
    return vars;
}
function initialize() 
{
	var latlng = new google.maps.LatLng(19.07, 72.89);
	var myOptions = {zoom: 11, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP};
        map = new google.maps.Map(document.getElementById("map"), myOptions);        
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

    google.maps.event.addListener(map, 'click', function(event) 
    {
        evictMarkers();
        var marker = new google.maps.Marker({
        map: map,
        draggable:false,
        animation: google.maps.Animation.DROP,
        position: event.latLng
        });

        $("cgeolat").value = event.latLng.lat();
        $("cgeolong").value = event.latLng.lng();
        map.setCenter(marker.getPosition());   
        
        var rad = $("chkRad").getValue()
        if(rad!="" || rad!=0)
        {            
            rad = rad * 1000;
            var circle = new google.maps.Circle({ map: map, radius: rad, fillColor:'#AA0000',strokeColor:'#AA0000',strokeweight:1});
            circle.bindTo('center', marker, 'position');
            
            google.maps.event.addListener(circle, 'click', function() {circle.setMap(null);});
            
            eviction_list.push(circle);
        }
        eviction_list.push(marker);
    });
        
    google.maps.event.addDomListener(document.getElementById('chkRad'),'change',function()
    {
        evictMarkers();
        var myLatLng = new google.maps.LatLng($("cgeolat").value, $("cgeolong").value);
        var marker = new google.maps.Marker({
        map: map,
        draggable:false,
        animation: google.maps.Animation.DROP,
        position: myLatLng
        });
        
        var rad = $("chkRad").getValue()
        if(rad!="" || rad!=0)
        {            
            rad = rad * 1000;
            var circle = new google.maps.Circle({ map: map, radius: rad, fillColor:'#AA0000',strokeColor:'#AA0000',strokeweight:1});
            circle.bindTo('center', marker, 'position');
            
            google.maps.event.addListener(circle, 'click', function() {circle.setMap(null);});
            
            eviction_list.push(circle);
        }
        eviction_list.push(marker);
    });
}

function initmapsender( lat,lng )
{
    if(gmapsinitedsender)return;

    var latlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
    zoom: 15,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map($("map"),
    myOptions);
    gmapsinitedsender=true;
}

function commaConcat( str, sval )
{
    if(sval!="")
    {
    if(str!="")
        return str + ", " + sval;
    else
        return sval;
    }
        return str;
}

function locate()
{
    evictMarkers();
    var address = "";
    address = commaConcat(address,$("chkA").getValue());
    address = commaConcat(address,$("chkT").getValue());
    address = commaConcat(address,$("chkRN").getValue());
    address = commaConcat(address,$("chkC").getValue());
    address = commaConcat(address,$("chkS").getValue());
    address = commaConcat(address,$("chkZC").getValue());
    var image = new google.maps.MarkerImage( '../../images/flag.png' ,
            new google.maps.Size(16,16),
            new google.maps.Point(0,0),
            new google.maps.Point(16,26),
            new google.maps.Size(16,16) );

    if(!geocodeinited)
    {
        geocoder = new google.maps.Geocoder();
        geocodeinited=true;
    }

    geocoder.geocode( { 'address': address}, function(results, status)
    {
        if (status == google.maps.GeocoderStatus.OK)
        {
            initmap(results[0].geometry.location.lat(), results[0].geometry.location.lng());
            markerlatlng = results[0].geometry.location;
            var marker = new google.maps.Marker({
            map: map,
            draggable:false,
            animation: google.maps.Animation.DROP,
            title: address,
            position: results[0].geometry.location
        });
        $("cgeolat").value = marker.getPosition().lat();
        $("cgeolong").value = marker.getPosition().lng();
        map.setCenter(marker.getPosition());
        eviction_list.push(marker);
        
        $('radius').show();
        jQuery('#radius').fadeOut(5000);
        $("chkRadField").show();
        $("chkRadTd").show();
        
        }
        else
            alert("Please check your address details or contact an Elixir about the issue : " + status);
    });
}

function plotCheckpoints()
{
    if($("cgeolat").value!="" && $("cgeolong").value!="")
    {
    initmap($("cgeolat").value, $("cgeolong").value);
    var myLatLng = new google.maps.LatLng($("cgeolat").value, $("cgeolong").value);
    var marker = new google.maps.Marker({
        map: map,
        draggable:false,
        animation: google.maps.Animation.DROP,
        position: myLatLng
    });
    
    map.setCenter(marker.getPosition());   
        
    var rad = $("chkRad").getValue()
    if(rad!="" || rad!=0)
    {            
        rad = rad * 1000;
        var circle = new google.maps.Circle({ map: map, radius: rad, fillColor:'#AA0000',strokeColor:'#AA0000',strokeweight:1});
        circle.bindTo('center', marker, 'position');

        google.maps.event.addListener(circle, 'click', function() {circle.setMap(null);});
        eviction_list.push(circle);
    }
    
    eviction_list.push(marker);
    }
    $("chkRadField").show();
    $("chkRadTd").show();
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

function clearfields()
{
    var elements = document.getElementsByTagName("input");
    for (var i=0; i < elements.length; i++)
    {
        if (elements[i].type == "text" && elements[i].id != "chkN")
        {
            elements[i].value = "";
        }
    }
    evictMarkers() 
}

function loaded()
{
	
    initialize();
    var pageid = getUrlVars()["id"];
    if(pageid!=null && pageid==4)
        plotCheckpoints();
}
//Event.observe(window,'load', function() {loaded();});
jQuery(document).ready(function() {
// Handler for .ready() called.
loaded();
});