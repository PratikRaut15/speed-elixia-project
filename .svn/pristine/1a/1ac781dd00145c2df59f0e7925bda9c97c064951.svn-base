<?php
include 'nation_functions.php';
if(isset($_POST['name']) && isset($_POST['code']) && isset($_POST['address']) && !isset($_POST['nationid']))
{
    $name = GetSafeValueString($_POST['name'],"string");
    $code = GetSafeValueString($_POST['code'],"string");
    $address = GetSafeValueString($_POST['address'],"string");
    $status = addnation($name, $code, $address);
    echo $status;
}
elseif(isset($_POST['nationid']))
{
    $nationid = GetSafeValueString($_POST['nationid'],"string");
    $name = GetSafeValueString($_POST['name'],"string");
    $code = GetSafeValueString($_POST['code'],"string");
    $address = GetSafeValueString($_POST['address'],"string");
    $status = editnation($nationid, $name, $code, $address);
    echo $status;
}
?>
