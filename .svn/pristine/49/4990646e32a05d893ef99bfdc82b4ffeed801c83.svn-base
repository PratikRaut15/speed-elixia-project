<?php

if ($_SESSION['switch_to'] == 3) {
    if (isset($_SESSION['Warehouse'])) {
        $vehicle = $_SESSION['Warehouse'];
    } else {
        $vehicle = 'Warehouse';
    }
} else {
    $vehicle = 'Vehicle No.';
}
$title = $_SESSION["digitalcon"] . ' Sensor History Details';
$subTitle = array(
    "$vehicle: {$_POST['vehicleno']}",
    "Start Date: $STdate",
    "End Date: $EDdate"
);

if ($_SESSION['use_fuel_sensor'] != 0) {
    $columns = array(
        'Start Time',
        'Start Location',
        'End Time',
        'End Location',
        $_SESSION["digitalcon"] . ' Status',
        'Ignition Status',
        'Duration [HH:MM]',
        'Fuel Level (In litres)'
    );
} else {
    $columns = array(
        'Start Time',
        'Start Location',
        'End Time',
        'End Location',
        $_SESSION["digitalcon"] . ' Status',
        'Ignition Status',
        'Duration [HH:MM]'
    );
}

echo table_header($title, $subTitle, $columns);
?>