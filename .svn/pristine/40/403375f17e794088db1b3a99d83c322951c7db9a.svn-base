<?php
$title = 'Stoppage Report';
$subTitle = array(
    "Vehicle No: ".$_POST['vehicleno'],
    "Start Date: $STdate ".$_POST['STime'],
    "End Date: $EDdate ".$_POST['ETime'],
    "Min. Hold Time: <b>".$_POST['interval']." mins</b>"
);
$columns = array(
    'Start Time',
    'End Time',
    'Location',
    'Hold Time [HH:MM]',
    'Add Reason',
    'Add As Checkpoint'
);
echo table_header($title, $subTitle, $columns);
?>
