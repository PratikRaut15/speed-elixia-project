<?php 
include '../panels/header.php';
include_once 'trip_functions.php';

$checkpointopt = get_checkpoints();
$genset_text = getcustombyid(1);
$def_date = date('d-m-Y');
?>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script type="text/javascript">
jQuery(function() {
    jQuery('body').click(function(){
        jQuery('#displaySuccess').hide();
    })
    jQuery('.viewC').live('click', function(){
        var data = jQuery(this).closest('tr');
        var tripid = jQuery(this).attr('id');
        var vno = data.children('td').eq(1).text();
        var sdate = data.children('td').eq(2).text();
        var stime = data.children('td').eq(3).text();
        var scp = data.children('td').eq(4).text();
        var ecp = data.children('td').eq(5).text();

        jQuery('#poptitle').html(vno);
        jQuery('#misDetails').html('Start Checkpoint: '+scp+'<br/>End Checkpoint: '+ecp+'<br/>Start Time: '+sdate+' '+ stime+'<br/><span id="endTime"></span>');
        getTripReport(tripid);
    });
    
    $("#vehicleno").autoSuggest({
        ajaxFilePath	 : "../reports/autocomplete.php", 
	ajaxParams	 : "dummydata=dummyData", 
	autoFill	 : false, 
	iwidth		 : "auto",
	opacity		 : "0.9",
	ilimit		 : "10",
	idHolder	 : "id-holder",
	match		 : "contains"
    });
});
function fill(Value, strparam){
    jQuery('#vehicleno').val(strparam);
    jQuery('#vehicleid').val(Value);
    jQuery('#display').hide();
}
function displayError(text){
    jQuery('#displayError').show();
    jQuery('#displayError').html('<span style="font-weight:bold; color:red">'+text+'</span>');
    jQuery('body').click(function(){
        jQuery('#displayError').hide();
        jQuery('#displayError').html('');
    });
}
function saveTripAlert(){
    var data = jQuery("#tripForm").serialize();
    jQuery('#pageloaddiv').show();
    jQuery.ajax({
        url:"trip_ajax.php",
        type: 'POST',
        data: data,
        success:function(result){
            jQuery("#centerDiv").html(result);
        },
        complete: function(){
            jQuery('#pageloaddiv').hide();
        }
    });
}
function getTripReport(tripid){
    jQuery.ajax({
        url:"trip_ajax.php",
        type:'POST',
        data:'todo=getGensetReport&tripid='+tripid,
        success:function(result){
            var final = jQuery.parseJSON(result);
            var f_data = final['data'];
            if(final['status']==0){
                jQuery('#tripDetailsTable').html("<tr><td colspan='100%' style='text-align:center;'>"+f_data+"</td></tr>");
            }
            else{
                jQuery('#endTime').html('End Time: '+final['end_time']);
                jQuery('#tripDetailsTable').html(f_data);
            }
        }
    });
}
function delete_trip(tripid){
    jQuery.ajax({
        url:"trip_ajax.php",
        type:'POST',
        data:'todo=deleteTrip&tripid='+tripid,
        success:function(result){
            jQuery("#centerDiv").html(result);
        },
        complete: function(){
            jQuery('#pageloaddiv').hide();
        }
    });
}

function get_lat_long_submit(){
    var start = jQuery("#checkpoint_start").val();
    var end = jQuery("#checkpoint_end").val();
    if(start==end){
        displayError('Start-End checkpoint should be different');return false;
    }
    var latlongs = '';
    jQuery.ajax({
        url:"trip_ajax.php",
        type:'POST',
        data:'todo=getLatLong&start='+start+'&end='+end,
        async:false,
        success:function(result){
            latlongs = jQuery.parseJSON(result);
        },
    });
    calculateDistances(latlongs[0],latlongs[1],latlongs[2],latlongs[3]);
}

function calculateDistances(f_lt,f_long,n_lt,n_long){
    var origin1 = new google.maps.LatLng(f_lt, f_long);
    var destinationA = new google.maps.LatLng(n_lt, n_long);
    var service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix({
        origins: [origin1],
        destinations: [destinationA],
        travelMode: google.maps.TravelMode.DRIVING,
        unitSystem: google.maps.UnitSystem.METRIC,
        avoidHighways: false,
        avoidTolls: false
    }, function callback(response, status) {
        if (status != google.maps.DistanceMatrixStatus.OK) {
            alert('Error was: ' + status);
        }
        else{
            var origins = response.originAddresses;
            //var destinations = response.destinationAddresses;

            for (var i = 0; i < origins.length; i++) {
                var results = response.rows[i].elements;
                for (var j = 0; j < results.length; j++) {
                    mainTest = results[j].distance.value;//.text;
                    jQuery('#driving_dist').val(mainTest);
                    saveTripAlert();
                    //console.log(mainTest);
                }
            }
        } 
    });
    
}



</script>
<div id="pageloaddiv" style='display:none;'></div>
    <div class="entry">
        <br/>
        <center>
        <!-- starts, input table -->
        <form method="post" action="trips.php" onsubmit="get_lat_long_submit();return false;" id="tripForm">
        <table>
            <thead>
                <tr><th colspan="100%" id="formheader">Trips</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="100%" id="displayError" style="display:none;"></td>
                </tr>
                <tr>
                    <td>Vehicle No.</td>
                    <td>Start Date</td>
                    <td>Start Hour</td>
                    <td>Select Start Checkpoint</td>
                    <td>Select End Checkpoint</td>
                </tr>
                <tr>
                    <input type="hidden" name="addAlert" >
                    <input type="hidden" name='driving_dist' id='driving_dist'/>
                    <td>
                        <input  type="text" name="vehicleno" id="vehicleno" size="18" value="" autocomplete="off" placeholder="Enter Vehicle No" required/>
                        <input type="hidden" name="vehicleid" id="vehicleid" size="20" value=""/>
                        <div id="display" class="listvehicle"></div>
                    </td>
                    <td><input size="10" type="text" required="" value="<?php echo $def_date; ?>" name="SDate" id="SDate"></td>
                    <td><input type="text" data-date="00:00" class="input-mini" name="STime" id="STime"></td>
                    <td><select id="checkpoint_start" name="checkpoint_start" style="width: 150px;"  required><?php echo $checkpointopt;?></select></td>
                    <td><select id="checkpoint_end" name="checkpoint_end" style="width: 150px;"  required><?php echo $checkpointopt;?></select></td>
                    <td><input type="submit" name="submit" value="Add Trip" class="g-button g-button-submit" ></td>
                </tr>
            </tbody>
        </table>
        </form>
        <br/>
	</center>
        
        <center id="centerDiv"><?php echo display_trip_alerts(); ?></center>
    </div>
<!-- View Report modal starts-->
<div class="modal fade" id="tripReportModal" tabindex="-1" role="dialog" aria-hidden="true" >
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"></span></button>
        <h4 class="modal-title" style='text-align: center;'><?php echo $genset_text; ?> Report - <span id="poptitle"></span></h4>
      </div>
      <div class="modal-body" style="min-height: 200px; max-height: 400px; width:500px;" id='popBody'>
        <h5 id='misDetails'></h5>
        <table class="table  table-bordered table-hover" >
            <thead>
                <tr><th>Distance Traveled[KM]</th><th><?php echo $genset_text; ?> Usage[HH:MM]</th></tr>
            </thead>
            <tbody id='tripDetailsTable'>
                
            </tbody>
        </table>
    </div>
  </div>
</div>
<!-- View Report modal ends -->
<?php include '../panels/footer.php';?>