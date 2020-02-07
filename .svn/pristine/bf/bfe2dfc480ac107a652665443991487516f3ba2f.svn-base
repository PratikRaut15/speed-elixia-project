<script>
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
    VehicleForCheckpoint_byId(Value, strparam)
    
}
</script>
<div style="float:none; width:74%;">
<form name="chkptform" id="chkptform">
    <table width="100%">
        <tr>
            <td colspan="6" id="emailerror" style="display: none">Please enter correct email</td>
            <td colspan="6" id="problem" style="display: none">Please Retry</td>
            <td colspan="6" id="vehiclearray" style="display: none">Please Select Vehicle</td>  
            <td colspan="6" id="chkptarray" style="display: none">Please Select Checkpoint</td>   
        </tr>    
        <tr>
            <td colspan="6" id="perfectinfo" style="display: none">Phone no. added</td>
            <td colspan="6" id="samephone" style="display: none">Phone no. already exist</td>
            <td colspan="6" id="smserror" style="display: none">Phone no. should be 10 digit</td> 
        </tr>    
        <tr>
<!--            <td>Select Checkpoint </td><td><select id="checkpointid" class='checkpoint_<?php echo $vehicleid; ?>' name="checkpointid"  onChange="addcheckpointtovehicle()">-->
                
            <td >Select Checkpoint </td><td><select id="checkpointid" class='checkpoint_<?php echo $vehicleid; ?>' name="checkpointid">
                    <option value="">Select Checkpoint</option>
                    <?php
                    $checkpoints = get_all_chk();
                   foreach ($checkpoints as $checkpoint)
                    {
                        echo "<option id='v_$checkpoint->checkpointid' value='$checkpoint->checkpointid'>$checkpoint->cname</option>";
                    }
                    ?>
                </select>
            </td>
<!--            <td>
                <input type="button" value="Add All" onclick="addallCheckpointForVehicle()" class="btn btn-mini btn-primary" >
            </td>-->
            <td>Select Vehicles </td><td style="display: none;"><select id="vehicleid1" class='vehicle_<?php echo $vehicleid; ?>' name="vehicleid1"  onChange="VehicleForCheckpoint()">
                    <option value="-1">Select Vehicle</option>
                    <?php
                    $vehicles = getvehicles();
                    foreach ($vehicles as $vehicle)
                    {
                        echo "<option id='v_$vehicle->vehicleid' value='$vehicle->vehicleid'>$vehicle->vehicleno</option>";
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
                <input type="button" value="Add All" onclick="addallvehicleForCheckpoint()" class="btn btn-mini btn-primary" >
            </td>
        </tr>
                            <tr>
<!--                                <td colspan="3">
                                    <div id="chkpt_list"></div>
                                </td>-->
                                <td colspan="5">
                                    <div id="vehicle_list1"></div>
                                </td>
                            </tr>
                        </table>
    <table width="55%">
        <tbody id="theBody">
        <tr>
            <td>Email</td>
            <td><input type="text" name="email" id="email" class="email" size="30" value="" placeholder="Email Id"></td>
            <td>Phone no</td>
            <td><input type="text" name="phoneno" id="phoneno" class="phone" size="30" value="" placeholder="Phone Number"></td>
            <td><a href="javascript:addRow()">Add Row</a></td>
        </tr>
        </tbody>
        <tr>
            <td colspan="100%" align="center"><input type="button" name="userdetails" class="btn  btn-primary" value="Save" onclick="submitecodedata();"></td>
        </tr>
    </table>
            </div>
</form>
</div>