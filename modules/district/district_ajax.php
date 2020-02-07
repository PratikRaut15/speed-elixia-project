<?php
include 'district_functions.php';
if(isset($_POST['name']) && isset($_POST['code']) && isset($_POST['address']) && isset($_POST['nationid']) && isset($_POST['stateid']) && !isset($_POST['districtid']))
{
    $stateid = GetSafeValueString($_POST['stateid'],"string");
    $name = GetSafeValueString($_POST['name'],"string");
    $code = GetSafeValueString($_POST['code'],"string");
    $address = GetSafeValueString($_POST['address'],"string");
    $nationid = GetSafeValueString($_POST['nationid'],"string");
    $status = adddistrict($name, $code, $address, $nationid, $stateid);
    echo $status;
}
else if(isset($_POST['districtid']))
{
    $districtid = GetSafeValueString($_POST['districtid'],"string");
    $nationid = GetSafeValueString($_POST['nationid'],"string");
    $stateid = GetSafeValueString($_POST['stateid'],"string");
    $name = GetSafeValueString($_POST['name'],"string");
    $code = GetSafeValueString($_POST['code'],"string");
    $address = GetSafeValueString($_POST['address'],"string");
    $status = editdistrict($districtid, $stateid, $name, $code, $nationid, $address);
    echo $status;
}
?>
