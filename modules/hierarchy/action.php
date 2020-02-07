<?php

include_once 'hierarchy_functions.php';
extract($_REQUEST);

if ($action == 'addrole') {
    $objRole = new Hierarchy();
    $objRole->rolename = GetSafeValueString($_POST['role'], "string");
    $objRole->parentroleid = GetSafeValueString($_POST['roleid'], "int");
    $objRole->moduleid = GetSafeValueString($_POST['moduleid'], "int");
    $objRole->customerno = $_SESSION['customerno'];

    $roleid = insertRole($objRole);
    if ($roleid != 0) {
        
        header("location: hierarchy.php?id=2");
    }
}
if ($action == 'editrole') {
    $objRole = new Hierarchy();
    $objRole->roleid = GetSafeValueString($_POST['rid'], "int");
    $objRole->rolename = GetSafeValueString($_POST['role'], "string");
    $objRole->parentroleid = GetSafeValueString($_POST['roleid'], "int");
    $objRole->moduleid = GetSafeValueString($_POST['moduleid'], "int");
    $objRole->customerno = $_SESSION['customerno'];

    $roleid = updateRole($objRole);
    //if ($roleid != 0) {
        header("location: hierarchy.php?id=2");
    //}
}

if ($action == 'Create-Conditions') {
    
    $conditionids = GetSafeValueString($_POST['conditionids'], "string");
    $approverids = GetSafeValueString($_POST['approverids'], "string");
    $typeids = GetSafeValueString($_POST['typeids'], "string");
    $minvalues = GetSafeValueString($_POST['minvalues'], "string");
    $maxvalues = GetSafeValueString($_POST['maxvalues'], "string");
    
    $rules = array();
    
    $conditionids = explode(",", $conditionids);
    $approverids = explode(",", $approverids);
    $typeids = explode(",", $typeids);
    $minvalues = explode(",", $minvalues);
    $maxvalues = explode(",", $maxvalues);
    $sequncenos = explode(",", $sequncenos);
    
    //print_r($typeids);
    
    foreach ($typeids as $key => $val) {
        $obj = new Hierarchy();
        $obj->typeid = $val;
        $obj->conditionid = $conditionids[$key];
        $obj->minvalue = $minvalues[$key];
        $obj->maxvalue = $maxvalues[$key];
        $obj->approverid = $approverids[$key];
        $obj->sequnceno = $sequncenos[$key];
        $obj->customerno = $_POST['custno'];
        $obj->userid = $_POST['userid'];
        //$rules[] = $obj;
        $ruleid = insertRules($obj);
    }
   
    if ($ruleid != 0) {
        header("location: conditions.php?id=2");
    }
}

if ($action == 'editrule') {
    $objRole = new Hierarchy();
    $objRole->ruleid = GetSafeValueString($_POST['ruleid'], "int");
    $objRole->minvalue = GetSafeValueString($_POST['minvalue'], "string");
    $objRole->maxvalue = GetSafeValueString($_POST['maxvalue'], "int");
    $objRole->sequence = GetSafeValueString($_POST['sequenceno'], "int");
    $objRole->customerno = $_SESSION['customerno'];

    $roleid = updateRule($objRole);
    //if ($roleid != 0) {
        header("location: conditions.php?id=2");
    //}
}
?>