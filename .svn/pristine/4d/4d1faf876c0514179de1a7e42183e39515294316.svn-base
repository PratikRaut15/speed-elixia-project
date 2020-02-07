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
<style>
    .listshow a{
        color: black;
    }
    .selected a{
	color : white;
}
</style>
<?php include 'panels/createfence.php';?>

<div id="gc-topnav2"  class="ch_bar"  style="background-color:#ffffff;
display:none;
width:360px;
height:auto; 
position:absolute; ;
left:7%;">
<div>
<form name="createchk" id="createchk" action="route.php" method="POST">
<div id="p1">
    <span id="fencename" style="display:none;">Please enter a fence name.</span>
            <span id="incompletefence" style="display:none;">Please enter at least 3 points.</span>
            <span id="samename" style="display:none;">Fence name already exists.</span>  
<div class="formline">

<div style="width:350px; height:auto; float:left; text-align:left;">
    <a class="a" id="address"> Address </a>  <input type="text" name="fenceA" id="fenceA"  autocomplete="off" class="chkp_inp" placeholder="Enter a location">&nbsp;
    <!--
    <input type="button" value="Locate" onclick="locate();"  id="locateinp" class="..btn .btn-primary">
    -->
<div id="create_table" style="display:none">
        <table border="0" style="background-color: white;"><tr>
        <td><a class="a" id="chkptname"> Name </a></td>
        <td><input type="text" name="fenceName" id="fenceName"  class="chkp_inp"></td>
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
            <td colspan="100%"><div id="vehicle_list_route"></div></td>
        </tr>
        <tr>
        <td colspan="5" align="center"><input type="button" value="Create" onclick="savefence();"  id="createinp" class="..btn .btn-primary"></td>
        </tr>
        </tbody>
        </table>
    </div>
</div>	
</div>
</div>
</form>    
</div>
</div>


<div>
<div id="panel">
<div id="color-palette"></div>
<div>

</div>
</div>
<div id="gc-topnav2" style="background-color:#ffffff; display:none; " class="ch_bar">
<a   style="float:left; padding-top:7px;">Fence name </a> <input type="text" name="place"  id="chkp_inp" />
<input type="button" name="add marker" value="Save Fence" class="g-button g-button-submit"  id="signIn" onClick="savefence();" />
<input type="button" name="close" id="edit"onclick="editform();" value="Edit" class="g-button g-button-submit" />
</div>

<div id="map" style="background-color: rgb(204, 204, 204);  position: relative; ">
</div>
</div>