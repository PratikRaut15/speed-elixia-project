<?php
include 'ecode_functions.php';

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "vehicleList") {
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
        echo json_encode($data);exit;
    }
} elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == "checkpointList") {
    if ($_REQUEST['term'] != '') {
        $q = '%' . $_REQUEST['term'] . '%';
    }
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getcheckpointsforcustomer($q);
    if ($checkpoints) {
        $data = array();
        foreach ($checkpoints as $row) {
            $checkpoint = new stdClass();
            $checkpoint->value = $row->cname;
            $checkpoint->checkpointid = $row->checkpointid;

            $data[] = $checkpoint;
        }
        echo json_encode($data);exit;
    }
} elseif (isset($_POST['data'])) {
    if ($_POST['data'] != '') {
        $q = '%' . $_POST['data'] . '%';
    }
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $devices = $VehicleManager->get_all_vehicles_byId($q);

    if ($devices) {
        $data = array();
        foreach ($devices as $row) {
            $vehicle = new stdClass();
            $vehicle->vehicleno = $row->vehicleno;
            $vehicle->vehicleid = $row->vehicleid;
            $data[] = $vehicle;
        }
        echo json_encode($data);
    }
}

?>
