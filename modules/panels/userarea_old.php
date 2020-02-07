<li>
<?php 
        include_once("../../lib/bo/DeviceManager.php");
        $devicemanager = new DeviceManager($_SESSION['customerno']);
        $devices = $devicemanager->devicesformapping();
        echo "<h2> Track Vehicle </h2>";
?>
    <ul>
        
<?php
$device_list="";
if(isset($devices))
{
    foreach($devices as $device)
    {
        $device_list .= "<option value=" . $device->vehicleid . ">" . trim($device->vehicleno) . "</option>\n";        
    }
}

?>
    <select id="to" name="to" onchange="addVehicle();">
    <option value=-1>Select Vehicles</option>    
    <?php echo $device_list;?>
    </select>
    <input type="button" value="Add all" class="g-button g-button-submit" onclick="addAllVehicles();" />
	<input type="button" value="Delete all" class="recipientbox" style="background-color:#8B0505;" onclick="delete_all();" />
    <div class="padding" id="vehicle_list"></div>                            
        
    </ul>
</li>    