<?php

require_once '../../lib/system/utilities.php';
require_once '../../lib/bo/InsuranceManager.php';

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

function getinsurance_company() {
    $ins = new InsuranceManager($_SESSION['customerno']);
    $insdata = $ins->getInsuranceCompany();
    return $insdata;
}

function addinsurance($inscompany) {
    $inscompany = GetSafeValueString($inscompany, "string");
    $ins = new InsuranceManager($_SESSION['customerno']);
    $userid = $_SESSION['userid'];
    $ins->add_insurance($inscompany, $userid);
}

function delinsurance($inscompid) {
    $inscompid = GetSafeValueString($inscompid, "string");
    $ins = new InsuranceManager($_SESSION['customerno']);
    $userid = $_SESSION['userid'];
    $ins->del_insurance($inscompid, $userid);
}

function getinsuranceid($inscompid) {
    $inscompid = GetSafeValueString($inscompid, "string");
    $ins = new InsuranceManager($_SESSION['customerno']);
    $userid = $_SESSION['userid'];
    $insdata = $ins->get_byinsuranceid($inscompid, $userid);
    return $insdata;
}

function editinsurance($insname, $insid) {
    $insid = GetSafeValueString($insid, "string");
    $insname = GetSafeValueString($insname, "string");
    $ins = new InsuranceManager($_SESSION['customerno']);
    $userid = $_SESSION['userid'];
    $ins->edit_insurance($insname, $insid, $userid);
}
