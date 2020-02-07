<?php 
include_once 'fence_functions_modal.php'; ?>

<div class="modal hide" id="addfence">
<form class="form-horizontal" id="addfence1">
<fieldset>
    <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h4 style="color:#0679c0">Add Fence</h4>
    </div>
    <div class="modal-body">
            <span id="fencearray" style="display:none;">Please Select Fence.</span> 
            <fieldset>
                <div class="control-group">
                <div class="input-prepend ">
                <span class="add-on" style="color:#000000">Select Fence </span><select id="fenceid" class='fence_<?php echo $vehicleid; ?>' name="fenceid"  onChange="addfencetovehicle()">
                    <option value="-1">Select Fence</option>
                    <?php
                    $fences = get_all_fence();
                   foreach ($fences as $fence)
                    {
                        echo "<option id='v_$fence->fenceid' value='$fence->fenceid'>$fence->fencename</option>";
                    }
                    ?>
                    </select>

                </div>
                <div class="input-prepend ">    
                    <input type="hidden" id="fencetovehicle" name="fencetovehicle"  value="<?php echo $vehicleid; ?>">
                    <input type="button" value="Add All" onclick="addallfenceforVehicle()" class="btn btn-mini btn-primary" >
                </div>
                </div>
	</fieldset>
        <div class="control-group">
            <div id="fence_list"></div>
        </div>
    </div>
</fieldset>
</form>
    <div class="modal-footer">
<!--        <input type="button" value="Save" class="btn btn-success" data-css="g-button g-button-submit" onclick="checkcheckpointforvehicle();" style="margin-left: 30%;">-->
<!--        <a href="#checkpt_popup"  onclick="create_map_for_modal(<?php //echo $vehicledata->vehicleid; ?>);" id="checkpoint_pop" class="add_checkpoint_<?php //echo $vehicledata->vehicleid; ?> btn btn-primary">Create New Checkpoint</a>-->
        <a href="#fence_popup" onclick="create_map_for_modal_fence(<?php echo $vehicledata->vehicleid; ?>);" id="fence_pop" class="add_fencing_<?php echo $vehicledata->vehicleid; ?> btn btn-primary">Create New Fence</a>
        <button onclick="checkfenceforvehicle();" data-dismiss="modal" class="btn btn-success">Modify</button>
    </div>
</div>





<!--<form name="addfence" id="addfence">

<table>
    <thead>
        <tr>
        <td colspan="100%"><div style="width:700px !important; height:auto; float:left; text-align:center;">
            <span id="fencearray" style="display:none;">Please Select Fence.</span>  
        </div></td>
        </tr>
        <tr>
            <th id="formheader" colspan="100%">Add Fence</th>
        </tr>
    </thead>
    <tbody>
        <td>Select Fences</td>
        <td><select id="fenceid" class='fence_<?php //echo $vehicleid; ?>' name="fenceid"  onChange="addfencetovehicle()">
                <option value="-1">Select Fence</option>
                <?php
//                $fences = get_all_fence();
//               foreach ($fences as $fence)
//                {
//                    echo "<option id='v_$fence->fenceid' value='$fence->fenceid'>$fence->fencename</option>";
//                }
                ?>
            </select>
        </td>
        <td><input type="hidden" id="fencetovehicle" name="fencetovehicle" value="<?php //echo $vehicleid; ?>">
            <input type="button" value="Add All" onclick="addallfenceforVehicle()" class="btn btn-mini btn-primary" >
        </td>
    </tr>
    <tr>
        <td colspan="100%"><div id="fence_list"></td>
    </tr>
    <tr>
        <td colspan="3"><input type="button" value="Save" class="btn btn-primary" onclick="checkfenceforvehicle();" style="margin-left: 30%;">
        <a href="#fence_popup"  onclick="create_map_for_modal_fence(<?php //echo $vehicledata->vehicleid; ?>);" id="fence_pop" class="add_fencing_<?php //echo $vehicledata->vehicleid; ?> btn btn-primary">Create New Fence</a>
        </td>
    </tr>
</tbody>
</table>
</form>-->
<script>
function checkfenceforvehicle(){
    var fencearray = new Array();
jQuery(".recipientbox").each(function() {
   fencearray.push(this.id);
});
    if(fencearray == ''){
                                    jQuery("#fencearray").show();
                                    jQuery("#fencearray").fadeOut(4000);
    }
    else{
        submitfenceforvehicle();
    }
}
function submitfenceforvehicle()
{
    var vehicleid = '<?php echo $vehicleid; ?>';
    var data = jQuery('#addfence1').serialize();
    jQuery.ajax({
                type: "POST",
                url: "fence_ajax.php",
                data: data,
                cache: false,
                success: function(html)
                {
                //jQuery("#addfence").trigger(":reset");
                call_row(vehicleid);
                call_row(vehicleid);
                }
        });
         
}
</script>