<?php
include 'delivery_functions.php';

if(isset($_REQUEST['delid']))
{
    $dm = new DeliveryManager($_SESSION['customerno']);
    $dm->delstatus($_REQUEST['delid']);
    header('location: statusmaster.php?id=2');
    
}

if(isset($_REQUEST['delreasonid']))
{
    $dm = new DeliveryManager($_SESSION['customerno']);
    $dm->delreason($_REQUEST['delreasonid']);
    header('location: reasonmaster.php?id=2');
    
}
?>