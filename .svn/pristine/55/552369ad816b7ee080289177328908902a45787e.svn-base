<?php
$title = $extraval.' Sensor History';
$subTitle = array(
    "Vehicle No: {$_POST['vehicleno']}",
    "Start Date: $STdate",
    "End Date: $EDdate"
);
$_SESSION['extraval'] = $extraval;
$columns = array(
    'Start Time',
    'Start Location',
    'End Time',
    'End Location',
    $_SESSION['extraval'] .' Status',
    'Duration [HH:MM]'
);
echo table_header($title, $subTitle, $columns);
?>