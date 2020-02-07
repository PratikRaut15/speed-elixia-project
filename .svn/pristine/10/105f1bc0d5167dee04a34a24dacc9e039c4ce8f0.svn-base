<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
//echo "<pre>";
class VODatacap1{
    
}
$vehiclemanager = new VehicleManager(161);
$um = new UnitManager(161);
$VEHICLES = $vehiclemanager->GetAllVehicles();
$REPORT_Final = Array();

echo $cnt=count($VEHICLES);
//print_r($VEHICLES);

foreach ($VEHICLES as $vehicle) {
    $unitno = $um->getuidfromvehicleid($vehicle->vehicleid);
    $locationstart = "../../customer/161/unitno/$unitno/sqlite/2015-08-01.sqlite";
    //if (file_exists($locationstart)) {
        $firstodometer = get_Odometer($vehicle->vehicleid, $locationstart, 1);
        $lastodometer = get_Odometer($vehicle->vehicleid, $locationstart, 5);
        if ($lastodometer < $firstodometer) {
            $max = GetOdometerMax($location);
            $lastodometer = $max + $lastodometer;
        }
        $distance = round(($lastodometer - $firstodometer) / 1000, 2);

        
            $vehicle1 = new VODatacap1();

            $vehicle1->vehicleno = $vehicle->vehicleno;
            $vehicle1->distance = $distance;
            $REPORT_Final[] = $vehicle1;
        
    //}
}

//print_r($REPORT_Final);

if($REPORT_Final){
    ?>
<table>
    <?php
    foreach($REPORT_Final as $rf){
        ?>
    <tr>
        <td><?php echo $rf->vehicleno;?></td>
        <td><?php echo $rf->distance;?></td>
        
    </tr>
        <?php
    }
    ?>
</table>
    <?php
}

function get_Odometer($vehicleid, $location, $date) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $Query = "SELECT * FROM vehiclehistory where vehicleid = $vehicleid order by lastupdated ASC Limit $date, 1 ";
    $result = $db->query($Query);
    if (isset($result) && $result != '') {
        foreach ($result as $row) {
            return $row['odometer'];
        }
    } else {
        return 0;
    }
}
