<?php


/* System Files */
include_once '../../lib/system/Log.php';
include_once '../../lib/system/utilities.php';

include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/CheckpointManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/GroupManager.php';

include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/DealerManager.php';
include_once '../../lib/bo/PartManager.php';
include_once '../../lib/bo/TaskManager.php';
include_once '../../lib/bo/AccessoryManager.php';
include_once '../../lib/bo/HierarchyManager.php';
include_once "../../lib/comman_function/reports_func.php";
include_once "constants.php";
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}

class Hierarchy{
    
}
$_customer = $_SESSION['customerno'];
$_user = $_SESSION['userid'];

// <editor-fold defaultstate="collapsed" desc="Hierarchy Functions">

function getRolesByCustomer($objRole) {
    $hm = new HierarchyManager($_SESSION['customerno'],$_SESSION['userid']);
    $groups = $hm->getAllRoles($objRole);
    return $groups;
}

function insertRole($objRole){
    $hm= new HierarchyManager($_SESSION['customerno'],$_SESSION['userid']);
    $roleid = $hm->insertRole($objRole);
    return $roleid;
}

function updateRole($objRole){
    $hm= new HierarchyManager($_SESSION['customerno'],$_SESSION['userid']);
    $roleid = $hm->updateRole($objRole);
    return $roleid;
}

function deleteRole($objRole){
    $hm= new HierarchyManager($_SESSION['customerno'],$_SESSION['userid']);
    $roleid = $hm->deleteRole($objRole);
    return $roleid;
}

// </editor-fold>
// 

// <editor-fold defaultstate="collapsed" desc="GET MAINTENANCE TRANSACTION TYPE">
function getTrasactionTypes($objRole) {
    $hm = new HierarchyManager($_SESSION['customerno'],$_SESSION['userid']);
    $groups = $hm->getTransactionTypes($objRole);
    return $groups;
}

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="GET MAINTENANCE TRANSACTION CONDITIONS">
function getTrasactionConditions($objRole) {
    $hm = new HierarchyManager($_SESSION['customerno'],$_SESSION['userid']);
    $groups = $hm->getTransactionConditions($objRole);
    return $groups;
}

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="INSERT RULES">
function insertRules($objRole) {
    $hm = new HierarchyManager($_SESSION['customerno'],$_SESSION['userid']);
    $groups = $hm->insertRule($objRole);
    return $groups;
}

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="GET MAINTENANCE TRANSACTION RULES">
function getTransactionRules($objRole) {
    $hm = new HierarchyManager($_SESSION['customerno'],$_SESSION['userid']);
    $groups = $hm->getTransactionRules($objRole);
    return $groups;
}

// </editor-fold>

// <editor-fold defaultstate="collapsed" desc="DELETE TRANSACTION RULES">
function deleteRule($objRole){
    $hm= new HierarchyManager($_SESSION['customerno'],$_SESSION['userid']);
    $roleid = $hm->deleteRule($objRole);
    return $roleid;
}
// </editor-fold>

function updateRule($objRole){
    $hm= new HierarchyManager($_SESSION['customerno'],$_SESSION['userid']);
    $roleid = $hm->updateRule($objRole);
    return $roleid;
}
?>