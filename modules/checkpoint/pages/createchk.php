<!-- <link rel="stylesheet" href="<?php echo  $_SESSION['subdir'];?>/bootstrap/css/bootstrap.css" type="text/css" />; -->
<script>
 $(function () {
   $("#vehicleno").autoSuggest({
     ajaxFilePath: "autocomplete.php",
     ajaxParams: "dummydata=dummyData",
     autoFill: false,
     iwidth: "auto",
     opacity: "0.9",
     ilimit: "10",
     idHolder: "id-holder",
     match: "contains"
   });
 });
 function fill(Value, strparam)
 {
   jQuery('#vehicleno').val(strparam);
   jQuery('#vehicleid').val(Value);
   jQuery('#display').hide();
   VehicleForRoute_ById(Value, strparam)
 }
</script>

<style>
  .ajax_reposnce{
    top:0;
    left:0px;
  }
</style>
<div id="gc-topnav2"  class="ch_bar"  style="background-color:#ffffff;
     display:none;
     width:360px;
     height:auto;
     position:absolute;
     left:7%; z-index:100;">
  <div>
    <form name="createchk" id="createchk" action="route.php" method="POST">
      <?php include 'panels/createchk.php';?>
      <div id="p1">
        <div class="formline">
          <div id="chk_box" style="width:350px; height:auto; float:left; text-align:left;">
            <a class="a" id="address"> Address </a>  <input type="text" name="chkA" id="chkA"  class="chkp_inp" autocomplete="off" style="width: 280px;">&nbsp;
            <!--
            <input type="button" value="Locate" onclick="locate();"  id="locateinp" class="..btn .btn-primary">
            -->
            <div id="create_table" style="display:none">
              <table border="0" style="background-color: white;"><tr>
                  <td><a class="a" id="chkptname"> Name</a></td>
                  <td><input type="text" name="chkName" id="chkName"  class="chkp_inp" value="" ></td>
                  <td>Select Vehicles</td>
                  <td style="display: none;">
                    <select id="vehicleroute" name="vehicleroute" onChange="VehicleForRoute()" style="display: none;">
                      <?php
                          $vehicles = getvehicles_all();
                          if (isset($vehicles)) {
                              foreach ($vehicles as $vehicle) {
                                  echo "<option value='$vehicle->vehicleid'>$vehicle->vehicleno</option>";
                              }
                          }
                      ?>
                    </select>
                  </td>
                  <td>
                    <input  type="text" name="vehicleno" id="vehicleno" size="17" value="" autocomplete="off" placeholder="Enter Vehicle No" required>
                    <input type="hidden" name="vehicleid" id="vehicleid" size="20" value="<?php echo $vehicleid; ?>"/>
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
                  <td>ETA</td>
                  <td><input type="text" name="STime" id="STime"  size="5" class="chkp_inp" value=""/> (HH:MM) </td>
                  <td>Phone Number</td>
                  <td colspan="2"><input type="text" name="phonenumber" id="phonenumber"/></td>
                </tr>
                <tr>
                  <td>Email</td>
                  <td><input type="text" name="cemail" id="cemail"/></td>
                  <td>Select Checkpoint Type</td>
                  <td  colspan="2">
                      <select id="chktypes" name="chktypes" style="width: 90%;">
                          <option value='0'>Select Type</option>
                      <?php
                          $types = getchktypes();
                          if (isset($types)) {
                              foreach ($types as $type) {
                                  echo "<option value='$type->ctid'>$type->name</option>";
                              }
                          }
                      ?>
                    </select>
                  </td>

                </tr>
                <tr>
                  <td colspan="5"><button type="button" class="btn btn-primary" id="checkpointCreateModal">Create checkpoint Type</button></td>
                </tr>
                <br>
                <tr>
                    <td colspan="5"><input type="button" value="Create" onclick="submitcheckpoint();"  id="createinp" class="btn btn-primary">&nbsp;&nbsp;
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <input type="hidden" id="crad" name="crad" value="1">
      <input type="hidden" id="cgeolat" name="cgeolat">
      <input type="hidden" id="cgeolong" name="cgeolong">
    </form>
  </div>
</div>
<div id="map"></div>
<div id="info" align="center"></div>

<!-- checkpoint Creation modal -->
<div class="modal fade" id="checkPointCreationmodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button> 
      <h4 class="modal-title">Create Checkpoint Type</h4>                                                             
      </div> 
      <div class="modal-body">
        <form>
            <div class="form-group">
            <label class="control-label col-sm-2" for="email">Checkpoint Type</label>
            <div class="col-sm-10">
              <input type="email" class="form-control" id="checkPointType" placeholder="Enter Checkpoint Type">
            </div>
          </div>
          <div class="form-group"> 
            <div class="col-sm-offset-2 col-sm-10">
              <button type="button" class="btn btn-primary" onclick="createCheckPointTypeByAjax()">Create</button>
            </div>
          </div>
        </form>
      </div>   
      <div class="modal-footer">
      <button type="button" class="btn" data-dismiss="modal">Close</button>                               
      </div>
    </div>                                                                       
  </div>                                          
</div>