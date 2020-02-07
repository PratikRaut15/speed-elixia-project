<?php
$title = 'Power Status Report';
$calculateddate = isset($_POST['calculateddate']) ? $_POST['calculateddate'] : "";
if ($calculateddate != "") {
    $sdate = $calculateddate;
} else {
    $sdate = $_POST['SDate'];
}   
$STdate = isset($STdate)?$STdate: $sdate;
$subTitle = array(
    "Vehicle No: {$_POST['vehicleno']}",
    "Start Date: {$STdate} {$_POST['STime']}",
    "End Date: {$EDate} {$_POST['ETime']}",
);
$columns = array(
    '',
    'Start Time',
    'End Time',
    'Duration [HH:MM]',
    'Status',
);
echo table_header($title, $subTitle, $columns);
?>
