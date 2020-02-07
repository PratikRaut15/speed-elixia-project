<?php
include_once 'checkpoint_functions_modal.php';
?>
<div id="gc-topnav22"  class="ch_bar  drag"  style="background-color:#ffffff;
display:none;
border: 2px solid #ccc;
width:600px;
padding: 5px;
height:inherit; 
position:absolute;">
<form name="createchk" id="createchk">
<?php include 'pages/panels/createchk.php';?>
<div class="formline1" id="searchchkp" style="width:550px; height:30px; float:left; text-align:left;">
<div style="width:550px; height:30px; float:left; text-align:left;">
    <a class="a" id="address" style="display:none;padding-right:10px;margin-top:5px;"> Address </a>
    <input type="text" name="chkA" id="chkA" style="display:none;width: 280px;" class="chkp_inp">&nbsp;
    <input type="button" value="Locate" onclick="locatechkp();"  style="display:none; float: left;" id="locateinp" class="btn btn-primary">
    <a class="a"  style="display:none;" id="chkptname"> Name </a><input type="text" name="chkName" id="chkName" style="display:none;" class="chkp_inp">
</div>
</div>
	<div class="formline1" >
		
		<div id = "chkRadField" style="display: none; width:530px; height:30px; float:left; text-align:left;" colspan="2">
                        
                        <table border="0" width="520px">
                             <tr>
                            <td>ETA</td>
                            <td><input type="text" name="STime" id="STime"  size="5" class="input-mini" value="" data-date="00:00" value="00:00"/>(HH:MM) </td>
                             <td>Select Vehicles </td>
                                <td><select id="vehicleid1" class='vehicle_<?php echo $vehicleid; ?>' name="vehicleid1"  onChange="VehicleForCheckpoint()">
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
                                    <input type="button" value="Add All" onclick="addallvehicleForCheckpoint()" class="btn btn-mini btn-primary" >
                                </td>
                            </tr>
                                                      
                        </table>
                    </div><br/>
            <div id="vehicle_selected" style="clear:both;">
                        <table>
                            <tr>
                                <td colspan="100%">
                                    <div id="vehicle_list1"></div>
                                </td>
                            </tr>
                        </table>
            </div>
                    <!--<div style="padding-left: 25px;" id="add_location_div"><input type="checkbox" name="addloc" id="addloc">&nbsp;add to my location</div>-->
                    <input type="button"  value="New location" class="btn btn-primary" id="toggler3" onclick="create_map_for_new_location();" style="padding-left: 15px;" >
<input type="button" value="Create Checkpoint"  id="createinp" style=" float: right;" class="..btn  .btn-primary"  onclick="submitcheckpointmodal(<?php echo $vehicleid; ?>);">
	</div>
<input type="hidden" id="cgeolat" name="cgeolat">
<input type="hidden" id="cgeolong" name="cgeolong">
<input type="hidden" id="crad" name="crad" value="1">
		
</form>
	</div>
<div id="map_chkpoint"></div>
<div id="info" align="center"></div>  
