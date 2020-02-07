<?php
include '../../lib/bo/DeviceManager.php';
// include '../../lib/system/utilities.php';
if(!isset($_SESSION))
{
    session_start();
}
function getunit($uid)
{
    $uid = GetSafeValueString($uid, "string");
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $unit = $devicemanager->get_device($uid);
    return $unit;
}
function getunits()
{
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $units = $devicemanager->get_all_devices();
    return $units;
}
function modifyunit($uid,$phoneno)
{
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $uid = GetSafeValueString($uid, "string");
    $phoneno = GetSafeValueString($phoneno, "string");
    $devicemanager->modunit($uid,$phoneno);
}
?>
