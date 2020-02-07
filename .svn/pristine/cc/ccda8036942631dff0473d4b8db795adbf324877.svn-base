<?php $vehicle = getvehicle($_GET['vid']);


?>
<form  class="form-horizontal well "  action="route.php" method="POST" style="width:70%;">
<input  type="hidden" name="vehicleid" value="<?php echo $_GET['vid']; ?>"  />
    <?php include 'panels/editvehicle.php';?>
	<fieldset>
			<div class="control-group">
			<div class="input-prepend ">
			<span class="add-on">Vehicle No <span class="mandatory">*</span></span><input type="text" name="vehicleno"  value="<?php echo $vehicle->vehicleno;?>" id="vehicleno" placeholder="License Plate No" autofocus maxlength="20">
			</div>
			<div class="input-prepend ">
			<span class="add-on">Kind </span><select name="type">
								<option value="Car" <?php if($vehicle->type=='Car') echo "selected=selected";?>>Car</option>
								<option value="Bus" <?php if($vehicle->type=='Bus') echo "selected=selected";?>>Bus</option>
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
foreach ($groups as $group){
    ?><option value='<?php echo $group->groupid;?>' <?php if($vehicle->groupid==$group->groupid){echo "selected=selected";}?> ><?php echo $group->groupname; ?></option>
<?php
}
}
?>
</select>
                            </div>
                            <div class="input-prepend ">
                                <span class="add-on">Overspeed Limit</span>
                                <input type="text" name="overspeed_limit" value="<?php echo $vehicle->overspeed_limit; ?>" placeholder="Value" maxlength="3" size="5" /><span class="add-on">Km/Hr</span>
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
					<?php
                    $mapedchks = getmappedchks($_GET['vid']);
                    if(isset($mapedchks))
                    {
                        foreach($mapedchks as $thischeckpoint)
                        {
                            ?>
                            <input type="hidden" id="hid_c<?php echo($thischeckpoint->checkpointid); ?>" value="<?php echo($thischeckpoint->cname); ?>">
                            <?php
                        }
                    }
                    ?>
					
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
								
								 <?php
                    $mapedfences = getmappedfences($_GET['vid']);
                    if(isset($mapedfences))
                    {
                        foreach($mapedfences as $thisfence)
                        {
                            ?>
                            <input type="hidden" id="hid_f<?php echo($thisfence->fenceid); ?>" value="<?php echo($thisfence->fencename); ?>">
                            <?php
                        }
                    }?>
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
            <input type="text" name="min_temp1_limit" placeholder="Value" maxlength="3" size="5" value="<?php echo $vehicle->temp1_min;?>"/><span class="add-on">&deg; C</span>
            <span class="add-on">Max. Temperature Limit</span>            
            <input type="text" name="max_temp1_limit" placeholder="Value" maxlength="3" size="5" value="<?php echo $vehicle->temp1_max;?>"/><span class="add-on">&deg; C</span>            
        </div>

			<div class="formSep ">
			</div>

    <p class="f_legend"> <h4>Temperature 2 Limits </h4></p>
    
        <div class="input-prepend ">
            <span class="add-on">Min. Temperature Limit</span>
            <input type="text" name="min_temp2_limit" placeholder="Value" maxlength="3" size="5" value="<?php echo $vehicle->temp2_min;?>"/><span class="add-on">&deg; C</span>
            <span class="add-on">Max. Temperature Limit</span>            
            <input type="text" name="max_temp2_limit" placeholder="Value" maxlength="3" size="5" value="<?php echo $vehicle->temp2_max;?>"/><span class="add-on">&deg; C</span>            
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
                <input type="text" name="min_temp1_limit" placeholder="Value" maxlength="3" size="5" value="<?php echo $vehicle->temp1_min;?>"/><span class="add-on">&deg; C</span>
                <span class="add-on">Max. Temperature Limit</span>            
                <input type="text" name="max_temp1_limit" placeholder="Value" maxlength="3" size="5" value="<?php echo $vehicle->temp1_max;?>"/><span class="add-on">&deg; C</span>            
            </div>

                        <div class="formSep ">
			</div>
     <?php
    }
     ?>

			<fieldset>
				<div class="control-group pull-right">
				
					<input type="submit" class="btn  btn-primary" value="Modify Vehicle">&nbsp;<input type="submit" name="delete" class="btn  btn-danger" value="Delete Vehicle">
					
			  </div>      
	
	</fieldset>
			
       
</form>
