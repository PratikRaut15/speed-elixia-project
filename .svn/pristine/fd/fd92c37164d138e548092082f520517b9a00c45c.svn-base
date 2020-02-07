var gmapsinited=false;
var eviction_list = [];
var i = 0;
var trackee_list = [];

function initialize() {
var latlng = new google.maps.LatLng(19.77, 72.89);
var myOptions = {
  zoom: 11,
  center: latlng,
  mapTypeId: google.maps.MapTypeId.ROADMAP
};
var map = new google.maps.Map(document.getElementById("mapcontainer"),
    myOptions);        
}

function initmap( lat,lng )
{
    if(gmapsinited)return;
    var latlng = new google.maps.LatLng(lat, lng);
    var myOptions = {
      zoom: 11,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map($("mapcontainer"),
        myOptions);
    gmapsinited=true;    
}

function getcheckpoints()
{
    new Ajax.Request('getcheckpointsAjax.php',
    {
        onSuccess: function(transport)
        {
            var cdata = transport.responseText.evalJSON();
            plotCurrentCheckpoints(cdata);
        },
        onComplete: function()
        {
        }
    });    
}

function gettrackees()
{
    if(i == 0)
    {
        new Ajax.Request('getTrackeesAjax.php',
        {
            onSuccess: function(transport)
            {
                var cdata = transport.responseText.evalJSON();
                plotCurrentTrackees(cdata);  
                periodicupdate()
            },
            onComplete: function()
            {
            }
        });    
    }
}

function periodicupdate()
{
    if(i == 0)
    {
        gettrackees.delay(60);    
    }
    else
    {
        getseltrackees.delay(60);
    }
}

function getseltrackees()
{
    if(i == 1)
    {
        var trackees = "";
        trackee_list.forEach(function(item) 
        {
            if(item != undefined)
            {
                trackees = trackees + item + ",";            
            }
        });        
        var params = "trackeeids=" + encodeURIComponent( trackees );    
        new Ajax.Request('getSelectedTrackeesAjax.php',
        {
            parameters: params,        
            onSuccess: function(transport)
            {
                var cdata = transport.responseText.evalJSON();
                plotCurrentTrackees(cdata);  
                periodicupdate()
            },
            onComplete: function()
            {
            }
        });        
    }
}

function plotCurrentTrackees( cdata )
{
    evictMarkers();    
    var results = cdata.result;
    results.each( function(device)
    {
        try
        {
            initmap(device.tgeolat, device.tgeolong);
            var iconfile = "thumb48t_" + device.ticonimage;
            var imageone = "customer/1/images/trackee/thumb48/" + iconfile;
            var image = new google.maps.MarkerImage( imageone ,
                new google.maps.Size(32,27),
                new google.maps.Point(0,0),
                new google.maps.Point(16,26),
                new google.maps.Size(32,27) );

            var myLatLng = new google.maps.LatLng(device.tgeolat, device.tgeolong);

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                icon: image,
                title: device.name
                });                               
         map.setCenter(marker.getPosition());    
        eviction_list.push(marker);     
         
        var contentString = '<div id="bodyContent">'+            
            '<p><font size=1>Name: '+ device.tname +'<br/>'+            
            'Last Updated: '+ device.difference +'<br/>'+ device.geotag +'</font></p></div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });

        google.maps.event.addListener(marker, 'mouseover', function() {
          infowindow.open(map,marker);
        });         
        
        google.maps.event.addListener(marker, 'mouseout', function() {
          infowindow.close();
        });                 
    }
    catch( ex)
    {
        alert(ex);
    }
    });
}

function plotCurrentCheckpoints( cdata )
{
    var results = cdata.result;
    results.each( function(device)
    {
        try
        {
            initmap(device.cgeolat, device.cgeolong);
            var iconfile = "thumb48_" + device.ciconimage;
            var imageone = "customer/1/images/checkpoint/thumb48/" + iconfile;
            var image = new google.maps.MarkerImage( imageone ,
                new google.maps.Size(32,27),
                new google.maps.Point(0,0),
                new google.maps.Point(16,26),
                new google.maps.Size(32,27) );

            var myLatLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                icon: image,
                title: device.name
                });
//         map.setCenter(marker.getPosition());

        var contentString = '<div id="content">'+
            '<h6 id="firstHeading" class="firstHeading">'+ device.cname +'</h6>'+
            '</div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });

        google.maps.event.addListener(marker, 'mouseover', function() {
          infowindow.open(map,marker);
        });         
        
        google.maps.event.addListener(marker, 'mouseout', function() {
          infowindow.close();
        });         
    }
    catch( ex)
    {
        alert(ex);
    }
    });
}

function evictMarkers() {
// clear all markers
eviction_list.forEach(function(item) 
{ 
    item.setMap(null) 
});

// reset the eviction array 
eviction_list = [];
}

function loaded()
{
    initialize();
    getcheckpoints();
    gettrackees();
}

function reloaded()
{
    gmapsinited=false;
    eviction_list = [];
/*    trackee_list.forEach(function(item) 
    { 
        removeTrackee(item);
    });    
    trackee_list = [];
*/    
//    i = 0;
//    initialize();
    getcheckpoints();
    var seltrackee = false;
    trackee_list.forEach(function(item) 
    {
        if(item != undefined)
        {
            seltrackee = true;
        }
        else
        {
            // Do nothing
        }
    });             
    if(seltrackee == false)
    {
        i=0;
        gettrackees();        
    }
    else
    {
        i=1;            
        getseltrackees();
    }
}

function addTrackee()
{
    var trackee_id = $('to').getValue();
    if (trackee_id > -1 && $('to_trackee_div_' + trackee_id) == null)
    {
        var selected_name = $('to').options[$('to').selectedIndex].text;
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function() { removeTrackee(trackee_id); };
        div.className = 'recipientbox';
        div.id = 'to_trackee_div_' + trackee_id;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_trackee_' + trackee_id + '" value="' + trackee_id + '"/>';
        $('trackee_list').appendChild(div);
        $(div).appendChild(remove_image);
        trackee_list.push(trackee_id);             
    }
    i=1;    
    getseltrackees();    
    $('to').selectedIndex = 0;
}

function removeTrackee(trackee_no)
{
    $('to_trackee_div_' + trackee_no).remove();
    delete trackee_list[trackee_list.indexOf(trackee_no)];    
    i=1;    
    getseltrackees();    
}

function addAllTrackees()
{
    var select_box = $('to');
    for (var i=1; i<select_box.options.length; i++)
    {
        select_box.selectedIndex = i;
        addTrackee();
    }
}
jQuery(document).ready(function($) {

jQuery("#mapcontainer").css("width",jQuery(".onecolumn").width()-45);
jQuery("#mapcontainer").css("height",jQuery("#tab1_content").height()+500);
//;
});