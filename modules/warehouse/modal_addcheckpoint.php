<?php include_once 'checkpoint_functions_modal.php'; ?>

<div class="modal hide" id="addcheckpoint">
<form class="form-horizontal" id="addcheckpoint1">
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Add Checkpoint</h4>
    </div>
    <div class="modal-body">
            <span id="checkpointarray" style="display:none;">Please Select Checkpoint.</span> 
            <fieldset>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Select Checkpoint </span><select id="checkpointid" class='checkpoint_<?php echo $vehicleid; ?>' name="checkpointid"  onChange="addcheckpointtovehicle()">
                    <option value="-1">Select Checkpoint</option>
                    <?php
                    $checkpoints = get_all_chk();
                   foreach ($checkpoints as $checkpoint)
                    {
                        echo "<option id='v_$checkpoint->checkpointid' value='$checkpoint->checkpointid'>$checkpoint->cname</option>";
                    }
                    ?>
                    </select>

                </div>
                <div class="input-prepend ">    
                    <input type="hidden" id="checkpointtovehicle" name="checkpointtovehicle"  value="<?php echo $vehicleid; ?>">
                    <input type="button" value="Add All" onclick="addallCheckpointForVehicle()" class="btn btn-mini btn-primary" >
                </div>
                </div>
	</fieldset>
        <div class="control-group">
            <div id="chkpt_list"></div>
        </div>
    </div>
</fieldset>
</form>
    <div class="modal-footer">
<!--        <input type="button" value="Save" class="btn btn-success" data-css="g-button g-button-submit" onclick="checkcheckpointforvehicle();" style="margin-left: 30%;">-->
<!--        <a href="#checkpt_popup"  onclick="create_map_for_modal(<?php //echo $vehicledata->vehicleid; ?>);" id="checkpoint_pop" class="add_checkpoint_<?php //echo $vehicledata->vehicleid; ?> btn btn-primary">Create New Checkpoint</a>-->
        <a href="#checkpt_popup" onclick="create_map_for_modal(<?php echo $vehicledata->vehicleid; ?>);" id="checkpoint_pop" class="add_checkpoint_<?php echo $vehicledata->vehicleid; ?> btn btn-primary">Create New Checkpoint</a>
        <button onclick="checkcheckpointforvehicle();" data-dismiss="modal" class="btn btn-success">Modify</button>
    </div>
</div>







<!--<form name="addcheckpoint" id="addcheckpoint">

<table>
    <thead>
        <tr><td colspan="100%"><div style="width:700px !important; height:auto; float:left; text-align:center;">
            <span id="checkpointarray" style="display:none;">Please Select Checkpoint.</span>  
</div></td></tr>
        <tr>
            <th id="formheader" colspan="100%">Add Checkpoint</th>
        </tr>
    </thead>
    <tbody>
        <td>Select Checkpoint</td>
        <td><select id="checkpointid" class='checkpoint_<?php //echo $vehicleid; ?>' name="checkpointid"  onChange="addcheckpointtovehicle()">
                <option value="-1">Select Checkpoint</option>
                <?php
//                $checkpoints = get_all_chk();
//               foreach ($checkpoints as $checkpoint)
//                {
//                    echo "<option id='v_$checkpoint->checkpointid' value='$checkpoint->checkpointid'>$checkpoint->cname</option>";
//                }
                ?>
            </select>
        </td>
        <td><input type="hidden" id="checkpointtovehicle" name="checkpointtovehicle"  value="<?php //echo $vehicleid; ?>">
            <input type="button" value="Add All" onclick="addallCheckpointForVehicle()" class="btn btn-mini btn-primary" >
        </td>
    </tr>
    <tr>
        <td colspan="3"><div id="chkpt_list"></td>
    </tr>
    <tr>
        <td colspan="3"><input type="button" value="Save" class="btn btn-primary" data-css="g-button g-button-submit" onclick="checkcheckpointforvehicle();" style="margin-left: 30%;">
        <a href="#checkpt_popup"  onclick="create_map_for_modal(<?php //echo $vehicledata->vehicleid; ?>);" id="checkpoint_pop" class="add_checkpoint_<?php //echo $vehicledata->vehicleid; ?> btn btn-primary">Create New Checkpoint</a>
        </td>
    </tr>
</tbody>
</table>
</form>-->
<script>
function checkcheckpointforvehicle(){
    var checkarray = new Array();
jQuery(".recipientbox").each(function() {
   checkarray.push(this.id);
});
    if(checkarray == ''){
                                    jQuery("#checkpointarray").show();
                                    jQuery("#checkpointarray").fadeOut(4000);
    }
    else{
        submitcheckpointforvehicle();
    }
}
function submitcheckpointforvehicle()
{
    var vehicleid = '<?php echo $vehicleid; ?>';
    var data = jQuery('#addcheckpoint1').serialize();
    jQuery.ajax({
                type: "POST",
                url: "route.php",
                data: data,
                cache: false,
                success: function(html)
                {    
//                jQuery(".popup").hide();
//                jQuery(".overlay").hide();
//                jQuery("#addcheckpoint").trigger(":reset");
                call_row(vehicleid);
                call_row(vehicleid);
                }
        });
         
}
</script>