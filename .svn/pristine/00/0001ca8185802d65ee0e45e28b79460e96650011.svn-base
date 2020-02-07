<?php

include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/ModelManager.php';


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

function getmake() {
    $modelmanager = new ModelManager($_SESSION['customerno']);
    $modeldata = $modelmanager->get_make();
    return $modeldata;
}

function getmodelofmake($makeid) {
    $modelmanager = new ModelManager($_SESSION['customerno']);
    $modeldata = $modelmanager->get_modelfrommake($makeid);
    return $modeldata;
}

function add_model($modelname, $make_id) {
    $userid = $_SESSION['userid'];
    $modelmanager = new ModelManager($_SESSION['customerno']);
    $modelmanager->addmodel($modelname, $make_id, $userid);
}

function del_model($modelid) {
    $userid = $_SESSION['userid'];
    $modelmanager = new ModelManager($_SESSION['customerno']);
    $modelmanager->delmodel($modelid, $userid);
}

function getmodelid($modelid) {

    $modelmanager = new ModelManager($_SESSION['customerno']);
    $modeldata = $modelmanager->get_model_name($modelid);
    return $modeldata;
}

function edit_model($modelname, $modelid) {
    $userid = $_SESSION['userid'];
    $modelmanager = new ModelManager($_SESSION['customerno']);
    $modelmanager->editmodel($modelname, $modelid, $userid);
}
