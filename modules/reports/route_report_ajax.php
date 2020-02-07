<?php
include_once 'route_functions.php';
if(isset($_POST) && !isset($_POST['tripreport']))
{
    $routeid = GetSafeValueString($_POST['routeid'],"string");
    $startdate = GetSafeValueString($_POST['STdate'],"string");
    $enddate = GetSafeValueString($_POST['EDdate'],"string");
    route_report_hist($routeid, $startdate, $enddate);
}
else if(isset($_POST['tripreport']))
{
    $routeid = GetSafeValueString($_POST['routeid'],"string");
    $startdate = GetSafeValueString($_POST['STdate'],"string");
    $enddate = GetSafeValueString($_POST['EDdate'],"string");
    trip_route_report($routeid, $startdate, $enddate);
}
?>
