<?php

include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/MakeManager.php';


if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}

if (isset($_SESSION['lastvisit']) && (time() - $_SESSION['lastvisit'] > 1800)) {
    // last request was more than 30 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage
    session_regenerate_id(true);
}

class VODatacap {
    
}

function getmakes() {
    $makemanager = new MakeManager($_SESSION['customerno']);
    $makedata = $makemanager->get_make();
    return $makedata;
}

function add_make($makename) {
    $makename = GetSafeValueString($makename, "string");
    $makemanager = new MakeManager($_SESSION['customerno']);
    $makemanager->add_make($makename, $_SESSION['userid']);
}

function delmake($makeid) {

    $makemanager = new MakeManager($_SESSION['customerno']);
    $makemanager->del_make($makeid, $_SESSION['userid']);
}

function getmakeid($makeid) {

    $makemanager = new MakeManager($_SESSION['customerno']);
    $makename = $makemanager->get_makebyid($makeid);
    return $makename;
}

function edit_make($makeid, $makename) {
    $makeid = GetSafeValueString($makeid, "string");
    $makename = GetSafeValueString($makename, "string");
    $makemanager = new MakeManager($_SESSION['customerno']);
    $makemanager->edit_make($makeid, $makename, $_SESSION['userid']);
}
