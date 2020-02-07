<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script type="text/javascript">

  function autoClick(){
document.getElementById('GetReport').click();
//alert("test");
if(jQuery("#centerDiv").html() !=''){
setTimeout(60000);
}
}
 
//} 

            var geocoder, location1, location2;
            var text= new Array();
            var textEnd = new Array();
            //var text = '';
            function initialize() {
                geocoder = new google.maps();
            }
            
function calculateDistances(f_lt,f_long,n_lt,n_long,count,vehicle,total,location, bound, call)
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
                //alert('Error was: ' + status);
              } else {
                var origins = response.originAddresses;
                var destinations = response.destinationAddresses;

                for (var i = 0; i < origins.length; i++) {
                  var results = response.rows[i].elements;
                 for (var j = 0; j < results.length; j++) {
                   var valuetxt = results[j].distance.text;
                   if(call =='start'){
                       text[count] = vehicle+'='+valuetxt+'='+bound+"="+location;
                   }
                   else{
                       textEnd[count] = vehicle+'='+valuetxt+'='+bound+"="+location;
                   }
                   
                  }
                }
              } 
              
              if((count+1)==total && call=='end'){
                  refined_array(text, textEnd);
              }
              
            });
            
            
           
            }
            
            google.maps.event.addDomListener(window, 'load', initialize);
            
            </script>


<?php
$checkpoint = get_all_checkpoint();
$checkpointopt = "";
foreach($checkpoint as $check){
    if(isset($_POST['checkpoint_start']) && $check->checkpointid == $_POST['checkpoint_start']){
       $checkpointopt .="<option selected='selected' value='$check->checkpointid'>$check->cname</option>";
    }
    else{
        $checkpointopt .="<option value='$check->checkpointid'>$check->cname</option>";
    }
}

$checkpoint_end = get_all_checkpoint();
$checkpointopt_end = "";
foreach($checkpoint_end as $check_end){
    if(isset($_POST['checkpoint_end']) && $check_end->checkpointid == $_POST['checkpoint_end']){
       $checkpointopt_end .="<option selected='selected' value='$check_end->checkpointid'>$check_end->cname</option>";
    }
    else{
        $checkpointopt_end .="<option value='$check_end->checkpointid'>$check_end->cname</option>";
    }
}
?>
<script>
function getDistHistReport(){
    //jQuery('#centerDiv').html('');
    var start = jQuery("#checkpoint_start").val();
    var end = jQuery("#checkpoint_end").val();
    if(start == end)
        {
            jQuery("#error_same").show();
            jQuery('#error_same').fadeOut(3000);
            jQuery('#centerDiv').html('');
        }else{
    jQuery('#pageloaddiv').show();
    var data = jQuery("#distHistreportForm").serialize();

    jQuery.ajax({
        url:"viewvehicles_test.php",
        type: 'POST',
        async: false,
        data: data,
        success:function(result){
            
            var main = jQuery.parseJSON(result);
            var total = main.length;
            jQuery.each(main, function(i){
                //console.log(main[i]);
                var f_lt = main[i].lat;
                var f_long = main[i].clong;
                var n_lt = main[i].start_lat;
                var n_long = main[i].start_long;
                var start_location = main[i].start_location;
                var end_location = main[i].end_location;
                var vehicle = main[i].vehicleno;
                var location = main[i].location;
                calculateDistances(f_lt, f_long, n_lt, n_long, i, vehicle, total, location, start_location, 'start');
                
                var e_lt = main[i].end_lat;
                var e_long = main[i].end_long;
                calculateDistances(f_lt, f_long, e_lt, e_long, i, vehicle, total, location, end_location, 'end');
                
                
            });
            
            
            
           
            
            
            
            
        },
        complete: function(){
            jQuery('#pageloaddiv').hide();
        }
        
    });
        }
    //refined_array();
    
    setTimeout('autoClick();',60000); 
    
}

function refined_array(text,textEnd){

 //var ser = JSON.stringify(text);
 var ser1 = text;
 var ser2 = textEnd;
 var start = jQuery("#checkpoint_start").val();
 var end = jQuery("#checkpoint_end").val();
    jQuery.ajax({
     url:"viewvehicles_test.php",
     type: 'POST',
        data: 'toDo=refinearray&data1='+ser1+"&data2="+ser2+"&start="+start+"&end="+end,
        Datatype:"json",
        success:function(result){
            
            jQuery('#centerDiv').html(result);
            
            
            
        },
        complete: function(){
            jQuery('#pageloaddiv').hide();
        }
        
    });
    
}
</script>

<form action="distance_dashboard.php" method="POST" onsubmit="getDistHistReport();return false;" id="distHistreportForm">
    <br/><br/>
    <table>
        <tr><td colspan="100%" id="error_same" style="display: none;"> Please Select Two Different Checkpoints. </td></tr>
    <tr>
    <td>Checkpoint 1</td>
    <td>
        <select id="checkpoint_start" name="checkpoint_start" style="width: 150px;"  required>
            <option value=''>Select Start Checkpoint</option>
            <?php echo $checkpointopt;?>
            </select>
    </td>
    <td>Checkpoint 2</td>
    <td>
        <select id="checkpoint_end" name="checkpoint_end" style="width: 150px;"  required>
            <option value=''>Select End Checkpoint</option>
            <?php echo $checkpointopt_end;?>
            </select>
    </td>    
    <input type="hidden" name="GetReport" class="g-button g-button-submit" value="GetReport"  />
    <td><input type="submit" name="submit" class="g-button g-button-submit" value="Get Report" id="GetReport"  /></td>    
    </tr>        
    </table>
</form>
<br><br>

<center id='centerDiv'></center>
