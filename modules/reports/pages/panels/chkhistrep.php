<?php
$title = 'Checkpoint Report';
$subTitle = array(
    "Vehicle No: {$_POST['vehicleno']}",
    "Start Date: {$STdate} {$_POST['STime']}",
    "End Date: {$EDdate} {$_POST['ETime']}"
);
$columns = array(
    'Checkpoint Name',
    'In Time',
);
if($_SESSION['temp_sensors'] == '1'){
    $columns[] = "In Temperature";
}
$columns[] = "Out Time";
if($_SESSION['temp_sensors'] == '1'){
    $columns[] = "Out Temperature";
}
$columns[] = "Time Spent [Hours:Minutes]";
$columns[] = "ETA";
$columns[] = "Status(HH:MM)";
$columns[] = "Cumulative Distance [KM]";

echo table_header($title, $subTitle, $columns);

?>
