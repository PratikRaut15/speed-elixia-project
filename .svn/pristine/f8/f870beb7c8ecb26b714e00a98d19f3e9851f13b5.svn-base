<?php

require_once 'tyresrno_functions.php';

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

if ($_POST['edit_srno']) {
    EditTyreSrno($_POST);
    header('location:tyre_srno.php?id=2');
}

