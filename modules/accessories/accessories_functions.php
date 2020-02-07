<?php
include_once '../../lib/system/utilities.php';
include_once '../../lib/autoload.php';
if (!isset($_SESSION)) {
    session_start();
}
function addaccessory($accname, $accamount) {
    $accname = GetSafeValueString($accname, "string");
    $accamount = GetSafeValueString($accamount, "long");
    $accmanager = new AccessoryManager($_SESSION['customerno']);
    $accmanager->add_accessory($accname, $accamount, $_SESSION['userid']);
}
function editaccessory($name, $amount, $id) {
    $id = GetSafeValueString($id, "string");
    $taskname = GetSafeValueString($taskname, "string");
    $name = GetSafeValueString($name, "string");
    $amount = GetSafeValueString($amount, "long");
    $accmanager = new AccessoryManager($_SESSION['customerno']);
    $accmanager->edit_accessory($id, $name, $amount, $_SESSION['userid']);
}
function getaccessories() {
    $AccManager = new AccessoryManager($_SESSION['customerno']);
    $tasks = $AccManager->get_all_accessories();
    return $tasks;
}

function delaccessory($accid) {
    $AccManager = new AccessoryManager($_SESSION['customerno']);
    $AccManager->DeleteAccessory($accid, $_SESSION['userid']);
}

function getaccsbyid($id) {
    $AccManager = new AccessoryManager($_SESSION['customerno']);
    $accs = $AccManager->get_accessory($id);
    return $accs;
}
?>
