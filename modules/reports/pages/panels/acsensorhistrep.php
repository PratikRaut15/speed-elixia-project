<?php
$title = $_SESSION["digitalcon"].' Sensor History';
$subTitle = array(
    "Vehicle No: {$_POST['vehicleno']}",
    "Start Date: $STdate",
    "End Date: $EDdate"
);
$columns = array(
    'Start Time',
    'Start Location',
    'End Time',
    'End Location',
    $_SESSION["digitalcon"].' Status',
    'Duration [HH:MM]',
    'Fuel Consumed (In litre)'
);
echo table_header($title, $subTitle, $columns);
?>