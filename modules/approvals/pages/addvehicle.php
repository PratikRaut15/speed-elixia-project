


<form  class="form-horizontal well " name="createvehicle" id="createvehicle" action="route.php" method="POST" style="width:70%;">
 <?php include 'panels/addvehicle.php';?>    
			<fieldset>
			<div class="control-group">
			<div class="input-prepend ">
			<span class="add-on">Vehicle No <span class="mandatory">*</span></span><input type="text" name="vehicleno" id="vehicleno" placeholder="License Plate No" autofocus maxlength="20">
			</div>
			<div class="input-prepend ">
			<span class="add-on">Kind </span><select name="type">
								<option value="Car">Car</option>
								<option value="Bus">Bus</option>
							</select>
			</div>
			</div>
			</fieldset>


	<fieldset>
	<div class="control-group">
	
	
	<div class="input-prepend ">
	<span class="add-on">Group </span><select id="groupid" name="groupid" >
						<option value="0">Select Group</option>
						<?php
						$groups = getgroup();
						if(isset($groups))
						{
							foreach ($groups as $group)
							{
								echo "<option value='$group->groupid'>$group->groupname</option>";
							}
						}
						?>
					</select>
	</div>
        <div class="input-prepend ">
            <span class="add-on">Overspeed Limit</span>
            <input type="text" name="overspeed_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">Km/Hr</span>
        </div>
	</div>
	</fieldset>

	
			<div class="formSep ">
			</div>
			<fieldset>
			<div class="control-group formSep span6 ">
			<div class="input-prepend ">
	<span class="add-on">Checkpoint</span><select id="chkid" name="chkid" onchange="addchk()">
						<option value="-1">Select Checkpoint</option>
						<?php
						$checkpoints = getchks();
						if(isset($checkpoints))
						{
							foreach ($checkpoints as $checkpoint)
							{
								echo "<option value='$checkpoint->checkpointid'>$checkpoint->cname</option>";
							}
						}
						?>
					</select>
	</div>
	<input type="button" value="Add All Checkpoints" class="btn  btn-primary" onclick="addallchk()">
					<div id="checkpoint_list" >
					</div>
				
			
			
			</div>
			
								<div class="control-group formSep span6 pull-right">
								
								<div class="input-prepend ">
								<span class="add-on">Fence </span><select id="fenceid" name="fenceid" onchange="addfence()">
								<option value="-1">Select Fence</option>
								<?php
								$fences = getfences();
								if(isset($fences))
								{
								foreach ($fences as $fence)
								{
								echo "<option value='$fence->fenceid'>$fence->fencename</option>";
								}
								}
								?>
								</select>
								</div>
								<input type="button" value="Add All Fences" class="btn  btn-primary" onclick="addallfence()">
								
								
								
								<div id="fence_list" >
								</div>
								</div>
								
			</fieldset>

    <?php
    if($_SESSION['temp_sensors'] == 2)
    {
    ?>
    <p class="f_legend"> <h4>Temperature 1 Limits </h4></p>
    
        <div class="input-prepend ">
            <span class="add-on">Min. Temperature Limit</span>
            <input type="text" name="min_temp1_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
            <span class="add-on">Max. Temperature Limit</span>            
            <input type="text" name="max_temp1_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>            
        </div>

			<div class="formSep ">
			</div>

    <p class="f_legend"> <h4>Temperature 2 Limits </h4></p>
    
        <div class="input-prepend ">
            <span class="add-on">Min. Temperature Limit</span>
            <input type="text" name="min_temp2_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
            <span class="add-on">Max. Temperature Limit</span>            
            <input type="text" name="max_temp2_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>            
        </div>

			<div class="formSep ">
			</div>
    <?php
    }
    if($_SESSION['temp_sensors'] == 1)
    {
    ?>
        <p class="f_legend"> <h4>Temperature Limits </h4></p>

            <div class="input-prepend ">
                <span class="add-on">Min. Temperature Limit</span>
                <input type="text" name="min_temp1_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>
                <span class="add-on">Max. Temperature Limit</span>            
                <input type="text" name="max_temp1_limit" placeholder="Value" maxlength="3" size="5" /><span class="add-on">&deg; C</span>            
            </div>

                        <div class="formSep ">
			</div>
     <?php
    }
     ?>
			<fieldset>
				<div class="control-group pull-right">
				
					<input type="button" value="Add New Vehicle" class="btn  btn-primary" onclick="submitvehicle();">
					
			  </div>      
	
	</fieldset>
</form>
<script>
function submitvehicle()
{
    if(jQuery("#vehicleno").val() == "")
    {
        jQuery("#vehiclecomp").show();
        jQuery("#vehiclecomp").fadeOut(3000);                 
    }
    else
    {
     	
		var vehicleno=jQuery("#vehicleno").val();
		jQuery.ajax({
						type: "POST",
						url: "route_ajax.php",
						data:{vehicleno:vehicleno},
						async: true,
						cache: false,
						
						success: function (statuscheck) {
						
                if(statuscheck == "ok")
                {
                    jQuery("#createvehicle").submit();
                }
                else
                {
                    jQuery("#samename").show();
                    jQuery("#samename").fadeOut(3000);                
                }
						}
					});
		
		
                         
    }
}
</script>