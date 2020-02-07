<?php

include 'genset_functions.php';
if (isset($_POST['vehicleid'])) {

  $vehicle = new stdClass();
  $vehicle->vehicleid = $_POST['vehicleid'];
  $vehicle->uid = $_POST['uid'];
  $vehicle->vehicleno = $_POST['vehicleno'];
  $vehicle->gensetid1 = $_POST['gensetid1'];
  $vehicle->gensetid2 = $_POST['gensetid2'];
  $vehicle->genset1 = $_POST['genset1'];
  $vehicle->genset2 = $_POST['genset2'];
  $vehicle->transmitterid1 = $_POST['transmitterid1'];
  $vehicle->transmitterid2 = $_POST['transmitterid2'];
  $vehicle->transmitter1 = $_POST['transmitter1'];
  $vehicle->transmitter2 = $_POST['transmitter2'];
  $vehicle->userid = $_SESSION['userid'];



  modifyvehicle($vehicle);
  gensetMappingLog($vehicle);

  header("location: genset.php?id=1");
}
?>
