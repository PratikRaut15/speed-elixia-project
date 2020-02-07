<?php
$title = 'SKU Wise Order Report';
if(!isset($_POST['STdate'])&& isset($showdate)){
   $startdate =  $showdate;
}elseif(!isset($showdate) && isset($stdate)){
    $startdate =  $stdate;
}elseif(!isset($_POST['STdate'])){
    $startdate=$_REQUEST['stdate'];
}else{
    $startdate=$_POST['STdate'];
}

if(!isset($_POST['EDdate'])&& isset($showdate)){
   $enddate =  $showdate;
}elseif(!isset($showdate) && isset($edate)){
    $enddate =  $edate;
}else{
    $enddate=$_POST['EDdate'];
}

$subTitle = array(
    "Start Date: ".date('d-m-Y',strtotime($startdate)),
    "End Date: ".date('d-m-Y',strtotime($enddate))
    
);
$columns = array();
echo table_header($title, $subTitle, $columns);
?>
