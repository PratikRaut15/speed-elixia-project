var gmapsinited=false;
var eviction_list = [];
var vehicleid = null;
var vehicle_list = [];
var counter=0;
//aluto height adjust
jQuery.noConflict();
jQuery(document).ready(function() {
loaded();
// Handler for .ready() called.
var browserHeight = jQuery(window).height();

jQuery("#mapcontainer").css("width",jQuery(".onecolumn").width()-45);
jQuery("#mapcontainer").css("height",jQuery("#tab1_content").height()+500);
});




function initialize() 
{
	
	var latlng = new google.maps.LatLng(0, 0);
	var myOptions = {zoom: 0, center: latlng, mapTypeId: google.maps.MapTypeId.ROADMAP};
	var map = new google.maps.Map(document.getElementById("mapcontainer"), myOptions);    
	
}
function onclicktog(){
$('sidebar').toggle(900);
if(counter%2==0){
jQuery('#next').css("display","block");
jQuery('#pre').css("display","none");
jQuery('#maptoggler').css("left","0px");

counter++;
}else{

jQuery('#pre').css("display","block");
jQuery('#next').css("display","none");
jQuery('#maptoggler').css("left","300px");
counter++;
} 
		
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
    map = new google.maps.Map($("mapcontainer"), myOptions);
    gmapsinited=true;
}
 
function mapvehicles()
{
    addAllVehicles();
}

function periodicupdate()
{
    getselvehicles.delay(60);        
}

function plotvehicles(cdata)
{
    evictMarkers();
    var results = cdata.result;    
    results.each( function(device)
    {
        try
        {
            function closure()
            {
                infowindow.close();
            }
            
            initmap(device.cgeolat, device.cgeolong);
            var image = new google.maps.MarkerImage(device.image,
                new google.maps.Size(48,48),
                new google.maps.Point(0,0),
                new google.maps.Point(0,0));
			
            var myLatLng = new google.maps.LatLng(device.cgeolat, device.cgeolong);
			
			var lablecol;
			if(device.status=="BUSY")
			{
				lablecol="mapslabelsr";
			}else{
				
				lablecol="mapslabels";
				}
			
            var imgs="images/marker.png";
			var marker = new MarkerWithLabel({
				position: myLatLng,
				icon:imgs,
				map: map,
				labelContent: device.cname,
				labelAnchor: new google.maps.Point(8,55),
				labelClass: lablecol // the CSS class for the label
				});

			
			
            map.panTo(marker.getPosition());
            eviction_list.push(marker);
			var appendstr;
			var clientname;
			if(device.status=="BUSY")
			{
			appendstr=" || Status :"+device.status+"";	
			clientname=" "+device.clientname+"";	
			}else{
				appendstr="";
				clientname=" ";
				}
            
        var contentString = '<h3 id="maphead">'+ device.cname+appendstr+'</h3><hr><p>' + clientname+'<br/>'+device.lastupdated + '<br/>' + device.geotag;
 
        var infowindow = new google.maps.InfoWindow({
            content: contentString
        });
 
        google.maps.event.addListener(marker, 'mouseover', function() {
          infowindow.open(map,marker);
          window.setInterval(closure, 5000);
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


function addVehicle()
{
    var vehicle_id = $('to').getValue();
    
    if (vehicle_id > -1 && $('to_vehicle_div_' + vehicle_id) == null)
    {
        var selected_name = $('to').options[$('to').selectedIndex].text;
        var div = document.createElement('div');
        var remove_image = document.createElement('img');
        remove_image.src = "images/boxdelete.png";
        remove_image.className = 'clickimage';
        remove_image.onclick = function() {removevehicle(vehicle_id); };
        div.className = 'recipientbox';
        div.id = 'to_vehicle_div_' + vehicle_id;
        div.innerHTML = '<span>' + selected_name + '</span><input type="hidden" name="to_vehicle_' + vehicle_id + '" value="' + vehicle_id + '"/>';
        $('vehicle_list').appendChild(div);
        $(div).appendChild(remove_image);
        vehicle_list.push(vehicle_id);
    }
    $('to').selectedIndex = 0;
}

function getselvehicles()
{
    var devices = "";
    vehicle_list.forEach(function(item) 
    {
        if(item != undefined)
        {
            devices = devices + item + ",";            
        }
    });        
    var params = "vehicleids=" + encodeURIComponent( devices ); 
    new Ajax.Request('ajaxpulls.php?work=1',
    {
        parameters: params,        
        onSuccess: function(transport)
        {
            var cdata = transport.responseText.evalJSON();
            plotvehicles(cdata);
//            getchk();
            periodicupdate();            
        },
        onComplete: function()
        {
        }
    });            
}

function removevehicle(id)
{
    $('to_vehicle_div_' + id).remove();    
    delete vehicle_list[vehicle_list.indexOf(id)];    
    getselvehicles();        
}

function addAllVehicles()
{
    var select_box = $('to');
    for (var i=1; i<select_box.options.length; i++)
    {
       
        select_box.selectedIndex = i;
        addVehicle();
    }
    getselvehicles();        
}

function loaded()
{
    initialize();
    mapvehicles();
//    mapcheckpoints();
		
}






  
