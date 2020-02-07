<?php

require_once 'batterysrno_functions.php';

if ($_POST['dummydata'] == 'vehicleno') {
    if ($_POST['data'] != '') {
        $q = '%' . $_POST['data'] . '%';
    }
    $data = getVehicledata($q);

    if (isset($data)) {
        echo json_encode($data);
        exit;
    }
}
if ($_POST['dummydata'] == 'battvno') {
    if ($_POST['data'] != '') {
        $q = '%' . $_POST['data'] . '%';
    }
    $data = getBatteryVehicledata($q);

    if (isset($data)) {
        echo json_encode($data);
        exit;
    }
}

if (isset($_POST['edit_srno'])) {
    editbatt_srno($_POST);
    header("location:battery_srno.php?id=2");
}

if (isset($_POST['add_srno'])) {
    addbatt_srno($_POST);
    header("location:battery_srno.php?id=2");
}
