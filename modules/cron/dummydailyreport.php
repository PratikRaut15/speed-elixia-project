<?php

require "../../lib/system/utilities.php";
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/UserManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/DeviceManager.php';
include_once '../../lib/bo/CommunicationQueueManager.php';
include_once 'files/dailyreport.php';

class VODatacap {

}

$cm = new CustomerManager();
//$customernos = $cm->getcustomernos();
$today = '2015-12-11';
//$customernos = array(91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105);
//$customernos = array(106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120);
//$customernos = array(121, 122, 123, 124, 125, 126, 127, 128, 129, 130, 131, 132, 133, 134, 135);
//$customernos = array(136, 137, 138, 139, 140, 141, 142, 143, 144, 145, 146, 147, 148, 149, 150);
//$customernos = array(151, 152, 153, 154, 155, 156, 157, 158, 159, 160, 161, 162, 163, 164, 165);
//$customernos = array(166, 167, 168, 169, 170, 171, 172, 173, 174, 175, 176, 177, 178, 179, 180);
//$customernos = array(181, 182, 183, 184, 185, 186, 187, 188, 189, 190, 191, 192, 193, 194, 195);
//$customernos = array(196, 197, 198, 199, 200, 201, 202, 203, 204, 205, 206, 207, 208, 209, 210);
//$customernos = array(211, 212, 213, 214, 215, 216, 217, 218, 219, 220, 221, 222, 223, 224, 225);
//$customernos = array(226, 227, 228, 229, 230, 231, 232, 233, 234, 235, 236, 237, 238, 239, 240);
$customernos = array(241, 242, 243, 244, 245, 246, 247, 248, 249, 250, 251);
//
//$customernos = array(3);
//print_r($customernos);
foreach ($customernos as $thiscustomerno) {
//echo $thiscustomer;
//echo "<br/>";
 $dm = new DeviceManager($thiscustomerno);
 $devices = $dm->getlastupdateddatefordevices($thiscustomerno);
 /* Vehicle Count */
//echo count($devices);

 foreach ($devices as $device) {
  /* Unitno */
  $unitno = $device->unitno;
  /* Sqlite Location */

  $location = "../../customer/" . $thiscustomerno . "/unitno/" . $unitno . "/sqlite/" . $today . ".sqlite";

  if (file_exists($location)) {
   $location = "sqlite:" . $location;
   $database = new PDO($location);
   $query = "select vehicleid, odometer, uid from vehiclehistory order by lastupdated DESC limit 1";
   $result = $database->query($query);
   $lastrow;
   if (isset($result) && $result != "")
    foreach ($result as $row) {
     $vm = new VehicleManager($thiscustomerno);
     $vm->updateDailyreport($row['vehicleid'], $row['uid'], $row['odometer'], $thiscustomerno);
    }
  }
 }
}
?>