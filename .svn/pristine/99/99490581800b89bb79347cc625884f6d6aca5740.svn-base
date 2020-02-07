<?php
$title = 'Location Analysis Report';
$subTitle = array(
    "Start Date: {$STdate}",
    "End Date: {$EDdate}"
);
$columns = array('Vehicle No.');
$SDate = $STdate;
$STdate = date('d-m-Y', strtotime($STdate));
while(strtotime($STdate)<=strtotime($EDdate)){
    $columns[]  =   substr($STdate, 0,5);
    $STdate = date("d-m-Y",strtotime('+1 day', strtotime($STdate)));
}
echo table_header($title, $subTitle, $columns, true);
?>
