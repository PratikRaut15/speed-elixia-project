<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script type="text/javascript">
var geocoder, location1, location2;
function initialize() {
    geocoder = new google.maps();
}

function calculateDistances(f_lt,f_long,n_lt,n_long, count)
{
    var origin1 = new google.maps.LatLng(f_lt, f_long);
    var destinationA = new google.maps.LatLng(n_lt, n_long);
   var service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix(
    {
      origins: [origin1],
      destinations: [destinationA],
      travelMode: google.maps.TravelMode.DRIVING,
      unitSystem: google.maps.UnitSystem.METRIC,
      avoidHighways: false,
      avoidTolls: false
    }, function callback(response, status) {
  if (status != google.maps.DistanceMatrixStatus.OK) {
    alert('Error was: ' + status);
  } else {
    var origins = response.originAddresses;
    var destinations = response.destinationAddresses;
  
    for (var i = 0; i < origins.length; i++) {
      var results = response.rows[i].elements;
     for (var j = 0; j < results.length; j++) {
       var valuetxt = results[j].distance.text;
       if(count > 1)
       {
         var ct = count - 1;
         var lastdist = jQuery('#distance'+ct).val();
         var total = parseFloat(lastdist) + parseFloat(valuetxt);
         total = parseFloat(total).toFixed(2);
         jQuery('#distance'+count).val(total);
         //alert(total);
       }else{
           jQuery('#distance'+count).val(parseFloat(valuetxt).toFixed(2));
       }
        //alert(jQuery('#distance'+count).val())
      }
    }
  }
});
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
<style>
.column{
	width:49%;
	margin-right:.5%;
	height:500px;
	background:#fff;
	float:left;
        overflow-y: scroll;
}
#column2{
    background-image: url(../../images/drop.png);
    background-position: center;
    background-repeat: no-repeat;
}
.heading{
	width:49%;
	margin-right:.5%;
	min-height:21px;
	background:#cfc;
	float:left;
}
.column .dragbox{
	margin:5px 2px  20px;
	background:#fff;
	position:"relative";
	border:1px solid #946553;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
        width: inherit;
}
.column .dragbox h2{
	margin:0;
	font-size:12px;
	background:#946553;
	color:#fff;
	border-bottom:1px solid #946553;
	font-family:Verdana;
	cursor:move;
	padding:5px;
}

.dragbox-content{
	background:#fff;
	min-height:100px; margin:5px;
	font-family:'Lucida Grande', Verdana; font-size:0.8em; line-height:1.5em;
}
.column  .placeholder{
	background: #EED5B7;
	border:1px dashed #946553;
}
.alert-info {
background-color: #d9edf7;
border-color: #bce8f1;
color: #3a87ad;
cursor:move;
}
/*.clor{
border: 1px solid #d3d3d3;
background: #e6e6e6 url(http://code.jquery.com/ui/1.10.4/themes/smoothness/images/ui-bg_glass_75_e6e6e6_1x400.png) 50% 50% repeat-x;
font-weight: normal;
color: #555555;
	cursor:move;
}*/
</style>

<script>
/**    
jQuery(document).ready(function(){
 jQuery("#vehicleno").keyup(function(){
    var vehi = jQuery('#vehicleno').val();
    if(vehi=='')
        {
            jQuery('#display').hide();
            jQuery('#vehicleid').val()==='';
        }
            jQuery.ajax({
                    type: "GET",
                    url: "autocomplete.php",
                    data: "q="+vehi ,
                    success: function(html){
                        jQuery('#display').show();
                        jQuery("#display").html(html);
                    }
            }); 
 });
});
*/

  $(function() {
	$("#vehicleno").autoSuggest({
		ajaxFilePath	 : "autocomplete.php", 
		ajaxParams	 : "dummydata=dummyData", 
		autoFill	 : false, 
		iwidth		 : "auto",
		opacity		 : "0.9",
		ilimit		 : "10",
		idHolder	 : "id-holder",
		match		 : "contains"
	});
  }); 

function fill(Value, strparam)
{
    jQuery('#vehicleno').val(strparam);
    jQuery('#vehicleid').val(Value);
    jQuery('#display').hide();
    VehicleForRoute_ById(Value, strparam);
    
}
function fillchk(Value, strparam, count, lat, longi)
{
    //alert("test");
    if(count == 0){
    jQuery('#chkdisplay').hide();
    jQuery('#checkpoint').val(strparam);
    jQuery('#checkpointid').val(Value);
    jQuery('#checkpoint_lat').val(lat);
    jQuery('#checkpoint_long').val(longi);
    }else {
     jQuery('#chkdisplay'+count).hide();   
     jQuery('#checkpoint'+count).val(strparam);
     jQuery('#checkpointid'+count).val(Value);
     jQuery('#checkpoint_lat'+count).val(lat);
     jQuery('#checkpoint_long'+count).val(longi);
     if(count == 1){
     var first_lat = jQuery("#checkpoint_lat").val();
     var first_long = jQuery("#checkpoint_long").val();
     }else{
     var countlast = count-1;
     var first_lat = jQuery("#checkpoint_lat"+countlast).val();
     var first_long = jQuery("#checkpoint_long"+countlast).val();   
     }
     var next_lat = jQuery("#checkpoint_lat"+count).val();
     var next_long = jQuery("#checkpoint_long"+count).val();
     
     calculateDistances(first_lat, first_long, next_lat, next_long,count);
     //alert(first +"AND"+ next);
     //calculateDistance(first,next);
    }
       
 }

</script>
<form id="add_route" method="post">
<div style="width:100%;">
    <div style="width: 45%; float: left;">
        <table width="55%">
        <tbody id="theBody">
        <tr>
            <td>Checkpoint</td>
            <td>Hour(HH) From Start Checkpoint</td>
            <td>Min(MM) From Start Checkpoint</td>
            <td>Distance From Start Checkpoint(Km)</td>
            <td>+</td>
        </tr>    
        <tr>’
            <td>
                <input type="text" name="checkpoint" class="checkpoint" id="checkpoint" onkeyup="auto(0)" size="15" autocomplete="off" value="" placeholder="Checkpoint">
                <input type="hidden" name="checkpointid" class="checkpointid" id="checkpointid" size="15" value="">
                <input type="hidden" name="checkpoint_lat" class="chk_lat" id="checkpoint_lat" size="15" value="">
                <input type="hidden" name="checkpoint_long" class="chk_long" id="checkpoint_long" size="15" value="">
                <div id="chkdisplay" class="checkpointlist"></div>
            </td>
            
            <td><input type="text" name="thour" id="thour" class="thour" size="5" value="0" placeholder="HH" readonly></td>
            <td><input type="text" name="tmin" id="tmin" class="tmin" size="5" value="0" placeholder="MM" readonly></td>
            <td><input type="text" name="distance" id="distance" class="distance" size="5" value="00" placeholder="Distance" readonly></td>
            <td><a href="javascript:addRow()">Add Row</a></td>
        </tr>
        </tbody>
       
        </table>
        
        
           <?php include 'panels/addroute.php'; ?>
           <tr>
           <td>Route Name</td>
           <td><input type="text" name="routename" id="routename" placeholder="Route Name" maxlength="20" style="width:102px;"></td>
           <td>Select Vehicles</td>
           <td style="display: none;"><select id="vehicleroute" name="vehicleroute" onChange="VehicleForRoute()">
                   <option value=''>Select Vehicle</option>
           <?php
           $vehicles = getvehicles();
           if(isset($vehicles))
           {
                   foreach ($vehicles as $vehicle)
                   {
                       echo "<option value='$vehicle->vehicleid'>$vehicle->vehicleno</option>";
                   }
           }
           ?> 
               </select>
           </td>
           <td>
               <input  type="text" name="vehicleno" id="vehicleno" size="17" value="<?php echo $vehicleno;?>" autocomplete="off" placeholder="Enter Vehicle No" required>
               <input type="hidden" name="vehicleid" id="vehicleid" size="20" value="<?php echo $vehicleid;?>"/>
               <div id="display" class="listvehicle"></div>
           </td>
           <td>
               <input type="button" value="Add All" onclick="addallvehicleForRoute()" class="g-button g-button-submit">
           </td>
           </tr>
           <tr>
               <td colspan="100%"><div id="vehicle_list_route"></div></td>
           </tr>
           <tr>
           <td colspan="5" align="center"><input type="button" value="Save" onclick="checkroute_new();"></td>
           </tr>
           </tbody>
           </table>


    </div>
    <div style="width: 45%; float: left;">MAP Placeholder</div>
    <div style="clear: both;"></div>
</div>
</form>
   