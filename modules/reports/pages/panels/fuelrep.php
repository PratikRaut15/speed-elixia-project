<?php
$title = 'Fuel History';
$subTitle = array(
    "Vehicle No: $vehno",
    "Start Date: {$STdate}",
    "End Date: {$EDdate}"
);
$columns = array(
    'Vehicle No',
    'Liters',
    'Date & Time'
);
echo table_header($title, $subTitle, $columns);
?>
