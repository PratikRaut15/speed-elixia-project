<?php
include 'city_functions.php';
if(isset($_POST['name']) && isset($_POST['code']) && isset($_POST['address']) && isset($_POST['nationid']) && isset($_POST['stateid']) && isset($_POST['districtid']) && !isset($_POST['cityid']))
{
    $districtid = GetSafeValueString($_POST['districtid'],"string");
    $stateid = GetSafeValueString($_POST['stateid'],"string");
    $name = GetSafeValueString($_POST['name'],"string");
    $code = GetSafeValueString($_POST['code'],"string");
    $address = GetSafeValueString($_POST['address'],"string");
    $nationid = GetSafeValueString($_POST['nationid'],"string");
    $status = addcity($name, $code, $address, $nationid, $stateid, $districtid);
    echo $status;
}
else if(isset($_POST['cityid']))
{
    $cityid = GetSafeValueString($_POST['cityid'],"string");
    $districtid = GetSafeValueString($_POST['districtid'],"string");
    $nationid = GetSafeValueString($_POST['nationid'],"string");
    $stateid = GetSafeValueString($_POST['stateid'],"string");
    $name = GetSafeValueString($_POST['name'],"string");
    $code = GetSafeValueString($_POST['code'],"string");
    $address = GetSafeValueString($_POST['address'],"string");
    $status = editcity($cityid, $districtid, $stateid, $name, $code, $nationid, $address);
    echo $status;
}
?>
