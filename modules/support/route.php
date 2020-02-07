<?php
include 'support_functions.php';
if (isset($_POST['modify'])) {
    modifysupport($_POST);
    header('location: support.php?id=2');
} elseif (isset($_REQUEST['work']) && $_REQUEST['work'] == 'getmail') {
    $customerno = $_REQUEST['customerno'];
    $term = $_REQUEST['term'];
    $devicemanager = new DeviceManager($customerno);
    $mailIds = $devicemanager->getEmailList($term);
    echo $mailIds;
} elseif (isset($_REQUEST['work']) && $_REQUEST['work'] == 'insertmail') {
    $emailText = $_REQUEST['dataTest'];
    $customerno = $_REQUEST['customerno1'];
    $devicemanager = new DeviceManager($customerno);
    $devices = $devicemanager->insertEmailId($emailText, $customerno);
    echo $devices;
} elseif (isset($_POST) && isset($_POST['send_request'])) {
    addsupport($_POST);
    header('location: support.php');
} elseif (isset($_POST) && isset($_POST['test']) && $_POST['test'] == 'quickTicket') {
    $details = array();
    $details['issuetitle'] = $_POST['type_text'] . "- Quick Ticket";
    $details['tickettype'] = $_POST['type'];
    if (isset($_POST['type_text'])) {
        $details['typeName'] = $_POST['type_text'];
    }
    $details['notes_support'] = $_POST['desc'];
    $details['priority'] = 1;
    $details['priorityName'] = "Very High";
    $details['sentoEmail'] = $_POST['email'];
    $details['callback'] = isset($_POST['callback']) ? $_POST['callback'] : '0';
    $details['phone'] = $_POST['phone'];
    addsupport($details);
    echo 'ok';
} elseif (isset($_POST['history_note'])) {
    $ticketid = $_POST['history_note'];
    view_notes($ticketid);
} elseif (isset($_POST['add_note'])) {
    $ticketid = $_POST['add_note'];
    $note = $_POST['note'];
    add_note($ticketid, $note);
} elseif (isset($_REQUEST['work']) && $_REQUEST['work'] == 'getuser') {
    $customerno = $_REQUEST['customerno'];
    $term = $_REQUEST['term'];
    $devicemanager = new DeviceManager($customerno);
    $users = $devicemanager->getUserList($term);
    echo $users;
}
?>