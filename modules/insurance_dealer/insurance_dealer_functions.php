<?php

require_once '../../lib/system/utilities.php';
require_once '../../lib/bo/InsuranceDealerManager.php';

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

function getAllInsdealer() {
    $insdealermanager = new InsuranceDealerManager($_SESSION['customerno']);
    $dealers = $insdealermanager->getAllinsDealers();
    return $dealers;
}

function addInsDealer($insdealername) {
    $insdealername = GetSafeValueString($insdealername, "string");
    $insdealermanager = new InsuranceDealerManager($_SESSION['customerno']);
    $insdealermanager->addInsdealer($insdealername);
}

function editInsDealer($form) {
    $insdealername = GetSafeValueString($form['insdealername'], "string");
    $insdealerid = GetSafeValueString($form['insdealerid'], "int");
    $insdealermanager = new InsuranceDealerManager($_SESSION['customerno']);
    $insdealermanager->editInsdealer($insdealername, $insdealerid);
}

function deleteInsDealer($insdealerid) {
    $insdealerid = GetSafeValueString($insdealerid, "int");
    $insdealermanager = new InsuranceDealerManager($_SESSION['customerno']);
    $insdealermanager->deleteInsdealer($insdealerid);
}

function getInsdealerByid($insdealerid) {
    $insdealerid = GetSafeValueString($insdealerid, "int");
    $insdealermanager = new InsuranceDealerManager($_SESSION['customerno']);
    $dealers = $insdealermanager->getInsdealerByID($insdealerid);
    return $dealers;
}
