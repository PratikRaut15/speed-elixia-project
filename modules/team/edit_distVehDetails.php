<?php
include_once("session.php");
include("loginorelse.php");
include_once("db.php");
include_once("../../constants/constants.php");
include_once("../../lib/system/Sanitise.php");
include_once("../../lib/system/DatabaseManager.php");
include("../../lib/bo/TeamManager.php");
include_once("../../lib/system/Date.php");

date_default_timezone_set("Asia/Calcutta");
$today = date("d-m-Y");
$time= date("h:i");
$created_on=date("Y-m-d H:i:s");

$dvId=$_GET['dvId'];
$dcId=$_GET['dcId'];
$teamId = GetLoggedInUserId();
  if($teamId==1){
    $teamId = 0;
  }
include("header.php");
?>
<link rel="stylesheet" href="../../css/distributorDetails.css">
<div class="panel">
  <div class="paneltitle" align="center">Update Vehicle Details</div> 
  <div class="panelcontents">
    <div class="center">  
      <form name="distributor_vehicle_edit" id="distributor_vehicle_edit">
          <input type="hidden" name="dv_id" id="dv_id" value="<?php echo $dvId ?>">
          <label>Vehicle No.</label><input type="text" name="vehicle_no" id="vehicle_no" placeholder="Enter Vehicle No.">
          <label>Engine No.</label><input type="text" name="engine_no" id="engine_no"  placeholder="Enter Engine No.">
          <label>Chasis No.</label><input type="text" name="chasis_no" id="chasis_no"  placeholder="Enter Chasis No."></div>
        </div>
        <input type="button" name="submit_vehicle_form" id="submit_vehicle_form" value="Update" onclick="editVehicle_Details();" style="margin-left:40%;"/>       
      </form>
      
    </div>
  </div>
</div> 

<script>
var dc_id = <?php echo $dcId; ?>;
jQuery(document).ready(function () {  

  var dv_id = <?php echo $dvId; ?>;
  var teamId = <?php echo $teamId; ?>;
    jQuery.ajax({
      type: "POST",
      url: "distributor_functions.php",
      data: "fetch_distributorVehicle_details=1&dvId="+dv_id+"&teamId="+teamId+"&dcId="+dc_id,
      success: function(data){
          var temp_result=JSON.parse(data);
          var result = temp_result[0];
          $("#vehicle_no").val(result.vehicleNo);
          $("#engine_no").val(result.engineNo);
          $("#chasis_no").val(result.chasisNo);
      }
    });
});

function editVehicle_Details(){
  var data= $("#distributor_vehicle_edit").serialize();
  jQuery.ajax({
      type: "POST",
      url: "distributor_functions.php",
      data: "update_distributorVeh_details=1&"+data,
      success: function(data){
          var result=JSON.parse(data);
          if(result>0){
            alert("Vehicle Updated Successfully");  
            window.location.href='edit_distCustDetails.php?dcId='+dc_id;
          }
          else{
            alert("Please Try Again.");
          }
      }
    });
}
</script>

