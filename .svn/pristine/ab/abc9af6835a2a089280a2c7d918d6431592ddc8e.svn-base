<?php
require_once("class/config.inc.php");
require_once("class/class.api.php");
//print_r($_SESSION);
extract($_REQUEST);
//ojbect creation
$apiobj = new api();
//
if(!isset($_SESSION)){
die();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>elixiatech maps api console</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
      html, body, #map-canvas {
        margin: 0;
        padding: 0;
        height: 100%;
      }
	  #error{
	  position:absolute;
	  height:20px;
	  width:200px;
	  background-color:#EDCC67;
	  color:#000000;
	  top:20px;
	  left:45%;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	padding:10px;
	font:15px;
	  z-index:10000000;
	  display:none;
	  }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
		var mumbai = new google.maps.LatLng(19.017656 , 72.81223);
		var latm = new google.maps.LatLng(19.017656 , 72.8124234);
		var markers = [];
		
		var map;
		var i=0;
		var vehicleno=null;
		<?php
		if ($vehicleno!=""){
		?>
		
		vehicleno='<?php  echo $vehicleno;?>';
		<?php
		}
		
		 ?>
      function initialize() {
        var mapOptions = {
          zoom: 13,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          center: mumbai
        };

        map = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);
		marker_timer();	
      }
	  
	  
	function marker_timer(){
		
			jQuery.ajax({
						type: "GET",
						url: "index.php?action=map_timer&vehicleno="+vehicleno,
						async: true,
						cache: false,
						success: function (data) {
								var json = eval('(' + data + ')');
											if(json.status=="successful" && json.result!='null'){
												
													jQuery.each(json.result, function (key, value) {
														if(value.devicelat!=0 && value.devicelat!=null && value.devicelong!=0 && value.devicelong!=null){
														for (var j = 0; j < markers.length; j++ ) {
														markers[j].setMap(null);
														}
																	var marker = new google.maps.Marker({
																	map:map,
																	draggable:true,
																	position: new google.maps.LatLng(value.devicelat , value.devicelong),
																	});
																	i=i+1;
																	marker.info = new google.maps.InfoWindow({
																	content: '<h4>'+value.drivername+'</h4><p>phone :'+value.driverphoneno+',vehicle no :'+value.vehicleno+'<br>last updated :' + value.lastupdated + ' </p> '
																	});
																	
																	google.maps.event.addListener(marker, 'click', function() {
																	marker.info.open(map, marker);
																	});
																	markers.push(marker);
																	
																	map.panTo(marker.getPosition());
																	
															}
															
															
													
													});
													if(json.dcount=="0"){
													$("#map-canvas").css("zindex","100");
													$("#error").html("Sorry device not found");
													$("#error").css("display","block");
													
													}	
											
											}
									setTimeout(function () {	marker_timer();	}, 30000);
							}
				});
					
		}
	
	
	
	

    </script>
  </head>
   <body onLoad="initialize()">
   <span id="error"></span>
    <div id="map-canvas"></div>
  </body>
</html>