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
    VehicleForRoute_ById(Value, strparam)
    
}
</script>
<?php $geofence = getfencenamebyid($_GET['fid']);
?>
<form name="fencecreate" id="fencecreate" action="route.php" method="POST">
<?php include 'panels/editfence.php';?>
    <tr>
        <td>Name</td>
        <td><input type="text" name="fencingName" id="fencingName" value="<?php echo $geofence->fencename;?>">
        <input type="hidden" name="fenceId" id="fenceId" value="<?php echo $_GET['fid'];?>"></td>       
        <td>Select Vehicles</td>
        <td style="display: none;"><select id="vehicleroute" name="vehicleroute" onChange="VehicleForRoute()">
                <option value=''>Select Vehicle</option>
       <?php
	$vehicles = getvehicles_all();
	if(isset($vehicles))
	{
		foreach ($vehicles as $vehicle)
		{
                    echo "<option value='$vehicle->vehicleid'>$vehicle->vehicleno</option>";
                }
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
            <input type="button" value="Add All" onclick="addallvehicleForRoute()" class="g-button g-button-submit">
        </td>
        </tr>
        <tr>
            <td colspan="100%"><div id="vehicle_list_route">
                <?php
                $addedvehicles = getaddedvehicles_fence($_GET['fid']);
                if(isset($addedvehicles))
                {
                        foreach ($addedvehicles as $vehicle)
                        {
                            ?>
                            <input type="hidden" class="mappedvehicles" id="hid_v<?php echo($vehicle->vehicleid); ?>" rel="<?php echo($vehicle->vehicleid); ?>" value="<?php echo($vehicle->vehicleno); ?>">
                            <?php
                        }
                }
                ?> 
                </div></td>
        </tr>
        <tr>
        <td colspan="100%" align="center">
            <input class="btn  btn-primary" type="button" name="modifyfence" id="modifyfence" value="Modify Fence" onclick="editfencing();">&nbsp;
            <input type="hidden" id="cgeolat" name="cgeolat" value="<?php //echo $checkpoint->cgeolat;?>">
            <input type="hidden" id="cgeolong" name="cgeolong" value="<?php //echo $checkpoint->cgeolong;?>">
        </td>        
    </tr>
    </tbody>
</table>
</form>
<div id="map"></div>