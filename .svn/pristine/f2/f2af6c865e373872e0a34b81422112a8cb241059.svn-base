<?php
include 'state_functions.php';
if(isset($_POST['name']) && isset($_POST['code']) && isset($_POST['address']) && isset($_POST['nationid']) && !isset($_POST['stateid']))
{
    $name = GetSafeValueString($_POST['name'],"string");
    $code = GetSafeValueString($_POST['code'],"string");
    $address = GetSafeValueString($_POST['address'],"string");
    $nationid = GetSafeValueString($_POST['nationid'],"string");
    $status = addstate($name, $code, $address, $nationid);
    echo $status;
}
else if(isset($_POST['stateid']))
{
    $nationid = GetSafeValueString($_POST['nationid'],"string");
    $stateid = GetSafeValueString($_POST['stateid'],"string");
    $name = GetSafeValueString($_POST['name'],"string");
    $code = GetSafeValueString($_POST['code'],"string");
    $address = GetSafeValueString($_POST['address'],"string");
    $status = editstate($stateid, $name, $code, $nationid, $address);
    echo $status;
}
?>
