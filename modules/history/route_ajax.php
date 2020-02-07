<?php
include 'history_functions.php';
if(isset($_POST['latitude']) && isset($_POST['longitude']))
{
    getlocation($_POST['latitude'],$_POST['longitude']);
}
if(isset($_POST['vehicleid']))
{
    vehiclehistmapping($_POST['vehicleid']);
}
?>
