<?php
require_once 'sales_function.php';

$customerno = exit_issetor($_SESSION['customerno'], 'Please login');
$userid = exit_issetor($_SESSION['userid'], 'Please login');

    $salesid= isset( $_POST["t"])? $_POST["t"]:"";
    $jsondeviceid= isset( $_POST["ds"])? $_POST["ds"]:"";
    $deviceid = json_decode($jsondeviceid);
    
    if(isset($deviceid))
    {
        foreach($deviceid as $thisdeviceid)
        {
            mapdevicetosales($_SESSION['customerno'],$_SESSION['userid'],$thisdeviceid, $salesid);
        }
    }
    exit;
?>