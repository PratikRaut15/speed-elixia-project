<?php //include 'pages/panels/createfence.php';
include_once 'fence_functions_modal.php';
?>
<div id="gc-topnavfence"  class="ch_bar drag"  style="background-color: rgb(255, 255, 255); display: block; border: 2px solid rgb(204, 204, 204); width: 600px; padding: 5px; height: inherit; position: absolute; top: 70%; left: 2%; z-index: 1000;">
<div>
<form name="createfence" id="createfence" action="route.php" method="POST">
<div id="p1">
    <span id="fencename" style="display:none;">Please enter a fence name.</span>
            <span id="incompletefence" style="display:none;">Please enter at least 3 points.</span>
            <span id="samename" style="display:none;">Fence name already exists.</span>  
<div class="formline1">

<div style="width:500px; height:auto; float:left; text-align:left;">
    <a class="a" id="addressfence" style="display:none;"> Address </a>
    <input type="text" name="fenceA" id="fenceA"  class="chkp_inp" style="display:none;width: 280px;"><input type="button" style="display:none;" value="Locate" onclick="locatefence();"  id="locateinpfence" class="..btn .btn-primary">
    
    <a class="a" id="fencname">Name </a><input type="text" name="fenceName" id="fenceName"  class="chkp_inp">
    <a class="a" id="sel_veh_fence" style="padding-left:5px;"> Select Vehicles </a>
    <select id="vehicleid_fence" class='vehicle_<?php echo $vehicleid; ?>' name="vehicleid_fence"  onChange="VehicleForFence()">
        <option value="-1">Select Vehicle</option>
        <?php
        $vehicles = getvehicles();
        foreach ($vehicles as $vehicle){
            echo "<option id='v_$vehicle->vehicleid' value='$vehicle->vehicleid'>$vehicle->vehicleno</option>";
        }
	//AddedVehicleForFence()
        ?>
    </select>
    <input type="button" id="addall" value="Add All" onclick="addallvehicleForFence()" class="btn btn-mini btn-primary">
    <div id="selectvehicle" style="display:none; clear:both;">
      
            <table>
                <tr>
                    <td colspan="100%">
                        <div id="vehicle_list_fence"></div>
                    </td>
                </tr>
            </table>
    </div>
    <input type="button"  value="New location" class="btn btn-primary" id="locatenewfence" onclick="create_map_to_locate_fence();" >
    <input type="button" value="Create Fence" style=" float: right;" onclick="savefence(<?php echo $vehicleid; ?>);"  id="createinpfence" class="..btn .btn-primary">
</div>	
</div>
</div>
</form>    
</div>
</div>


<div>
<div id="panel1">
<div id="color-palette"></div>
<div>

</div>
</div>
<div id="gc-topnav23" style="background-color:#ffffff; display:none; " class="ch_bar">
<a   style="float:left; padding-top:7px;">Fence name </a> <input type="text" name="place"  id="chkp_inp" />
<input type="button" name="add marker" value="Save Fence" class="g-button g-button-submit"  id="signIn" onClick="savefence();" />
<input type="button" name="close" id="edit"onclick="editform();" value="Edit" class="g-button g-button-submit" />
</div>

<div id="map_fence" style="background-color: rgb(204, 204, 204);  position: relative; ">
</div>
</div>



