<?php
include 'ecode_functions.php';
if($_GET['get']=='all' && $_POST['ecodeid'])
{
    getvehiclesforecode($_POST['ecodeid']);
}
else if($_GET['get']=='1' && $_POST['vehicleid'])
{
    getvehicleforecode($_POST['vehicleid']);
}
else if(isset($_POST['ecodeid']) && isset($_POST['vehicleid']))
{
    $vehicleid = GetSafeValueString($_POST['vehicleid'],"string");
    $ecodeid = GetSafeValueString($_POST['ecodeid'],"string");
    deleteecodebyvehicleid($ecodeid, $vehicleid);
}
?>
