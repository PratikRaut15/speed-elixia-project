<?php
$title = 'First & Last Call Report';
if(!isset($_POST['STdate'])){
   $startdate =  $prevdate;
}else{
    $startdate=$_POST['STdate'];
}

if(!isset($_POST['EDdate'])){
   $enddate =  $prevdate;
}else{
    $enddate=$_POST['EDdate'];
}

$subTitle = array(
    "Start Date: ".$startdate,
    "End Date: ".$enddate,
);
$columns = array();
echo table_header($title, $subTitle, $columns);
?>
