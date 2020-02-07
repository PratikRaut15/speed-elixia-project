<?php

include 'route_functions.php';

if (isset($_REQUEST['action']) && $_REQUEST['action']=='vehicles') {
    if ($_REQUEST['term'] != '') {
        $q = '%' . $_REQUEST['term'] . '%';
    }
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devicemanager->devicesformapping_byId($q);
    if ($devices) {
        $data = array();
        foreach ($devices as $row) {
            $vehicle = new stdClass();
            $vehicle->value = $row->vehicleno;
            $vehicle->vehicleid = $row->vehicleid;
            $vehicle->groupname = $row->groupname;
            $data[] = $vehicle;
        }
        echo json_encode($data);
    }
}
?>
