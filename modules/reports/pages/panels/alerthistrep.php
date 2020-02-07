<?php
$title = 'Alert History';
$subTitle = array(
    "Type: $typeText",
    "Vehicle No: $vehno",
    "Date: {$_POST['SDate']}"
);
if($type==2){
    $subTitle[] = "Checkpoint: $chkText";
}
elseif($type==3){
    $subTitle[] =  "Fence: $fncText";
}
$columns = array(
    '',
    'Message',
    'Time',
    'Email Sent',
    'SMS Sent'
);
echo table_header($title, $subTitle, $columns);
?> 