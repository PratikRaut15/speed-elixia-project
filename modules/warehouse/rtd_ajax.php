<?php
session_start();
include 'whrtd_functions.php';

if(isset($_REQUEST['vehicleid']))
{
    get_vehicledesc_by_vehicleid($_REQUEST['vehicleid']);
}else if(isset($_REQUEST['refreshTime']))
{
    $refreshTime=GetSafeValueString($_REQUEST['refreshTime'],"string");
    updateRefreshTime($refreshTime);
    $_SESSION['refreshtime']=$refreshTime;
}
?>
