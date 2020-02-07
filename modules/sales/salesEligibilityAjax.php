<?php
require_once 'sales_function.php';

$customerno = exit_issetor($_SESSION['customerno'], 'Please login');
$userid = exit_issetor($_SESSION['userid'], 'Please login');
//$action = ri($_REQUEST['action']);

$salesid= isset( $_POST["t"])? $_POST["t"]:"";

if(isset($salesid))
{
    $device = getdevicefromsales($_SESSION['customerno'],$_SESSION['userid'],$salesid);
    
    if(isset($device) && $device->salesid > 0)
    {
        echo("notok");
    }
    else 
    {
        echo("ok");
    }
}   
else
{
    echo("notok");
}
?>