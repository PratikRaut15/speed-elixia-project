<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once '../../lib/system/utilities.php';
include_once '../../lib/autoload.php';
include_once "../user/exception_functions.php";
include_once '../sales/class/SalesManager.php';
include_once '../hierarchy/constants.php';
include_once '../../lib/comman_function/reports_func.php';

class Hierarchy {
}
function getusers() {
    $usermanager = new UserManager();
    $users = $usermanager->getusersforcustomer($_SESSION['customerno']);
    return $users;
}

function getmenus(){
  $heirmanager = new HierarchyManager($_SESSION['customerno'], $_SESSION['userid']);
  $result = $heirmanager->getmenuslist_new($_SESSION['customerno']);
  return $result;
}

function getcustomerdetailmenu($userid){
  $heirmanager = new HierarchyManager($_SESSION['customerno'], $_SESSION['userid']);
  $result = $heirmanager->getcustomerdetailmenu($userid);
  return $result;
}

function getcustom(){
    $usermanager = new UserManager();
    $custom = $usermanager->get_custom($_SESSION['customerno']);
    return $custom;
}

function get_stoppage_alerts($uid) {
    $usermanager = new UserManager();
    $user = $usermanager->get_stoppage_alerts($_SESSION['customerno'], $uid);
    return $user;
}

function getusersbygrp() {
    $usermanager = new UserManager();
    $users = $usermanager->getusersforcustomerbygrp($_SESSION['customerno']);
    return $users;
}

function getusersbygrp_hierarchy() {
    $usermanager = new UserManager();
    $users = $usermanager->getusersforcustomerbygrp_hierarchy($_SESSION['customerno']);
    return $users;
}

function getRolesByCustomer($objRole) {
    $hm = new HierarchyManager($_SESSION['customerno'], $_SESSION['userid']);
    $users = $hm->getAllRoles($objRole);
    return $users;
}

function getUsersForParentRole($objRole) {
    $hm = new HierarchyManager($_SESSION['customerno'], $_SESSION['userid']);
    $users = $hm->getUsersForParentRole($objRole);
    return $users;
}

function getUserGroups($objRole) {
    $hm = new HierarchyManager($_SESSION['customerno'], $_SESSION['userid']);
    $users = $hm->getUserGroups($objRole);
    return $users;
}

function get_heirarchy_name($roleid, $heirarchy_id) {
    $usermanager = new UserManager();
    $hname = $usermanager->get_heirarchy_name($roleid, $heirarchy_id, $_SESSION['customerno']);
    return $hname;
}

function getuser($uid) {
    $usermanager = new UserManager();
    $userid = GetSafeValueString($uid, 'string');
    $user = $usermanager->get_user($_SESSION['customerno'], $userid);
    return $user;
}

function gettmsuser($uid, $role, $customerno) {
    $usermanager = new UserManager();
    $userid = GetSafeValueString($uid, 'string');
    $user = $usermanager->get_usertms($uid, $role, $customerno);
    return $user;
}

function getheirdetails($userid, $roleid) {
    $usermanager = new UserManager();
    $uid = GetSafeValueString($userid, 'string');
    $rid = GetSafeValueString($roleid, 'string');
    $user = $usermanager->get_heir_details($_SESSION['customerno'], $uid, $rid);
    return $user;
}

function deluser($uid) {
    $usermanager = new UserManager();
    $userid = GetSafeValueString($uid, 'string');
    $usermanager->DeleteUser($userid, $_SESSION['customerno'], $_SESSION['userid']);
}

function insertuser($details) {
    $usermanager = new UserManager();
    $user_details = getuser($_SESSION["userid"]);
    $user = new VOUser();
    $user->username = GetSafeValueString($details["username"], "string");
    $user->password = GetSafeValueString($details["password"], "string");
    $user->realname = GetSafeValueString($details["name"], "string");
    $user->email = GetSafeValueString($details["email"], "string");
    $user->phone = GetSafeValueString($details["phoneno"], "string");
    $user->role = GetSafeValueString($details["role"], "string");
    $user->roleid = GetSafeValueString($details["roleid"], "string");
    $user->group = GetSafeValueString($details["group"], "string");
   // $user->delivery_vehicleid = ($details["vehicleno_db"] != '') ? (int) $details["vehicleno_db"] : null;
    $Groups = array();
    if ($user->roleid == '1') {
        $user->h_id = $_SESSION["heirarchy_id"];
        $Groups = $usermanager->getgroupsfromnationid($user->h_id, $_SESSION['customerno']);
    } elseif ($user->roleid == '2') {
        $user->h_id = GetSafeValueString($details["stateid"], "string");
        $Groups = $usermanager->getgroupsfromstateid($user->h_id, $_SESSION['customerno']);
    } elseif ($user->roleid == '3') {
        $user->h_id = GetSafeValueString($details["districtid"], "string");
        $Groups = $usermanager->getgroupsfromdistrictid($user->h_id, $_SESSION['customerno']);
    } elseif ($user->roleid == '4') {
        $user->h_id = GetSafeValueString($details["cityid"], "string");
        $Groups = $usermanager->getgroupsfromcityid($user->h_id, $_SESSION['customerno']);
    } elseif ($user->roleid == '8') {
        $user->h_id = '0';
        $Groups[] = $user->group;
    } elseif ($user->roleid == '10') {
        $user->h_id = '0';
        $Groups = $usermanager->getgroupsfromnationid($user->h_id, $_SESSION['customerno']);
    } else {
        $user->h_id = '0';
    }
    foreach ($details as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 9) == "to_group_") {
            $Groups[] = substr($single_post_name, 9, 10);
        }
    }

    $user->groups = $Groups;

    if ($_SESSION['use_secondary_sales']) {
        if (!empty($details['srid'])) {
            $user->h_id = $details['srid'];
        } elseif (!empty($details['supid'])) {
            $user->h_id = $details['supid'];
        } elseif (!empty($details['asmid'])) {
            $user->h_id = $details['asmid'];
        } else {
            $user->h_id = 0;
        }
    }
    if (isset($details['tempinterval'])) {
        $user->tempinterval = retval_issetor($details['tempinterval']);
    }
    if (isset($details['igninterval'])) {
        $user->igninterval = retval_issetor($details['igninterval']);
    }
    if (isset($details['overinterval'])) {
        $user->speedinterval = retval_issetor($details['overinterval']);
    }
    if (isset($details['acinterval'])) {
        $user->acinterval = retval_issetor($details['acinterval']);
    }
    if (isset($details['doorinterval'])) {
        $user->doorinterval = retval_issetor($details['doorinterval']);
    }
    if ($details['messsms']) {
        $user->mess_sms = 1;
    } else {
        $user->mess_sms = 0;
    }
    if ($details['messemail']) {
        $user->mess_email = 1;
    } else {
        $user->mess_email = 0;
    }
    if ($details['speedsms']) {
        $user->speed_sms = 1;
    } else {
        $user->speed_sms = 0;
    }
    if ($details['speedemail']) {
        $user->speed_email = 1;
    } else {
        $user->speed_email = 0;
    }
    if ($details['powersms']) {
        $user->power_sms = 1;
    } else {
        $user->power_sms = 0;
    }
    if ($details['poweremail']) {
        $user->power_email = 1;
    } else {
        $user->power_email = 0;
    }
    if ($details['tampersms']) {
        $user->tamper_sms = 1;
    } else {
        $user->tamper_sms = 0;
    }
    if ($details['tamperemail']) {
        $user->tamper_email = 1;
    } else {
        $user->tamper_email = 0;
    }
    if ($details['chksms']) {
        $user->chk_sms = 1;
    } else {
        $user->chk_sms = 0;
    }
    if ($details['chkemail']) {
        $user->chk_email = 1;
    } else {
        $user->chk_email = 0;
    }
    if ($details['acsms']) {
        $user->ac_sms = 1;
    } else {
        $user->ac_sms = 0;
    }
    if ($details['acemail']) {
        $user->ac_email = 1;
    } else {
        $user->ac_email = 0;
    }
    if ($details['igsms']) {
        $user->ignition_sms = 1;
    } else {
        $user->ignition_sms = 0;
    }
    if ($details['igemail']) {
        $user->ignition_email = 1;
    } else {
        $user->ignition_email = 0;
    }
    if ($details['tempsms']) {
        $user->temp_sms = 1;
    } else {
        $user->temp_sms = 0;
    }
    if ($details['tempemail']) {
        $user->temp_email = 1;
    } else {
        $user->temp_email = 0;
    }
    if ($details['dailyemail']) {
        $user->dailyemail = 1;
    } else {
        $user->dailyemail = 0;
    }
    if ($details['dailyemail_csv']) {
        $user->dailyemail_csv = 1;
    } else {
        $user->dailyemail_csv = 0;
    }
    if ($details['safcsms']) {
        $user->safcsms = 1;
    } else {
        $user->safcsms = 0;
    }
    if ($details['safcemail']) {
        $user->safcemail = 1;
    } else {
        $user->safcemail = 0;
    }
    if ($details['saftsms']) {
        $user->saftsms = 1;
    } else {
        $user->saftsms = 0;
    }
    if ($details['saftemail']) {
        $user->saftemail = 1;
    } else {
        $user->saftemail = 0;
    }
    if ($user_details->use_advanced_alert == 1) {
        $user->harsh_break_sms = isset($details['harsh_break_sms']) ? 1 : 0;
        $user->harsh_break_mail = isset($details['harsh_break_mail']) ? 1 : 0;
        $user->high_acce_sms = isset($details['high_acce_sms']) ? 1 : 0;
        $user->high_acce_mail = isset($details['high_acce_mail']) ? 1 : 0;
        $user->sharp_turn_sms = isset($details['sharp_turn_sms']) ? 1 : 0;
        $user->sharp_turn_mail = isset($details['sharp_turn_mail']) ? 1 : 0;
        $user->towing_sms = isset($details['towing_sms']) ? 1 : 0;
        $user->towing_mail = isset($details['towing_mail']) ? 1 : 0;
    } else {
        $user->harsh_break_sms = $user->harsh_break_mail = $user->high_acce_sms = $user->high_acce_mail = $user->sharp_turn_sms = $user->sharp_turn_mail = $user->towing_sms = $user->towing_mail = 0;
    }
    $user->door_sms = isset($details['doorsms']) ? 1 : 0;
    $user->door_email = isset($details['dooremail']) ? 1 : 0;
    $user->panic_sms = (isset($details['panic_sms']) && $user_details->panic_sms) ? 1 : 0;
    $user->panic_email = (isset($details['panic_email']) && $user_details->panic_email) ? 1 : 0;
    $user->immob_sms = (isset($details['immob_sms']) && $user_details->immob_sms) ? 1 : 0;
    $user->immob_email = (isset($details['immob_email']) && $user_details->immob_email) ? 1 : 0;
    $user->safcmin = GetSafeValueString($details['safcmin'], "string");
    $user->saftmin = GetSafeValueString($details['saftmin'], "string");
    $user->start_alert_time = GetSafeValueString($details['SDate'], "string");
    $user->stop_alert_time = GetSafeValueString($details['EDate'], "string");
    $user->customerno = $_SESSION['customerno'];
    include_once '../user/new_alerts_func.php';
    $usermanager->SaveUser($user, $_SESSION['userid']);
}

function insertuser_hierarchy($details) {
    $usermanager = new UserManager();
    $user_details = getuser($_SESSION["userid"]);
    $user = new VOUser();
    $Groups = array();
    $Vehicles = array();
    $Reports = array();

    $menuconfig = GetSafeValueString($details["menuconfigarr"], "string");
    $menuconfigarr = json_decode($menuconfig);
    $user->menuconfigarr = $menuconfigarr;

    $user->username = GetSafeValueString($details["username"], "string");
    $user->password = GetSafeValueString($details["password"], "string");
    $user->realname = GetSafeValueString($details["name"], "string");
    $user->email = GetSafeValueString($details["email"], "string");
    $user->phone = GetSafeValueString($details["phoneno"], "string");
    $user->role = GetSafeValueString($details["role"], "string");
    $user->roleid = GetSafeValueString($details["roleid"], "string");
    $user->group = GetSafeValueString($details["group"], "string");
    $user->delivery_vehicleid = (isset($details["vehicleno_db"]) && $details["vehicleno_db"] != '') ? (int) $details["vehicleno_db"] : null;
    $user->h_id = '';
    //print_r($user);die();
    if (isset($details["parentuser"])) {
        $user->h_id = GetSafeValueString($details["parentuser"], "string");
    } elseif (isset($details["higheruser"])) {
        $user->h_id = GetSafeValueString($details["higheruser"], "string");
    }
    foreach ($details as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 9) == "to_group_") {
            $Groups[] = substr($single_post_name, 9, 10);
        }
    }
    foreach ($details as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 11) == "to_vehicle_") {
            $Vehicles[] = substr($single_post_name, 11, 13);
        }
    }
    $reportList = $details['reportList'];
    $reportList = explode(',', $reportList);
    if (isset($reportList)) {
        foreach ($reportList as $reportId) {
            if (isset($details["reportTime_" . $reportId]) && $details["reportTime_" . $reportId] != '-1') {
                $time = isset($details["reportTime_" . $reportId])?($details["reportTime_" . $reportId]):'';
                $report = new stdClass();
                $report->reportId = $reportId;
                $report->reportTime = $time;
                $Reports[] = $report;
            }
        }
    }

    $user->groups = $Groups;
    $user->vehicles = $Vehicles;
    $user->reports = $Reports;

    if ($_SESSION['use_secondary_sales']) {
        if (!empty($details['srid'])) {
            $user->h_id = $details['srid'];
        } elseif (!empty($details['supid'])) {
            $user->h_id = $details['supid'];
        } elseif (!empty($details['asmid'])) {
            $user->h_id = $details['asmid'];
        } else {
            $user->h_id = 0;
        }
    }
    $user->tempinterval = retval_issetor($details['tempinterval'], "0");
    $user->igninterval = retval_issetor($details['igninterval'], "0");
    $user->speedinterval = retval_issetor($details['overinterval'], "0");
    $user->acinterval = retval_issetor($details['acinterval'], "0");
    $user->doorinterval = retval_issetor($details['doorinterval'], "0");
    $user->mess_sms = ret_issetor($details['messsms']);
    $user->mess_email = ret_issetor($details['messemail']);
    $user->mess_telephone = ret_issetor($details['messtelephone']);
    $user->mess_mobile = ret_issetor($details['messmobile']);
    $user->speed_sms = ret_issetor($details['speedsms']);
    $user->speed_email = ret_issetor($details['speedemail']);
    $user->speed_telephone = ret_issetor($details['speedtelephone']);
    $user->speed_mobile = ret_issetor($details['speedmobile']);
    $user->power_sms = ret_issetor($details['powersms']);
    $user->power_email = ret_issetor($details['poweremail']);
    $user->power_telephone = ret_issetor($details['powertelephone']);
    $user->power_mobile = ret_issetor($details['powermobile']);
    $user->tamper_sms = ret_issetor($details['tampersms']);
    $user->tamper_email = ret_issetor($details['tamperemail']);
    $user->tamper_telephone = ret_issetor($details['tampertelephone']);
    $user->tamper_mobile = ret_issetor($details['tampermobile']);
    $user->chk_sms = ret_issetor($details['chksms']);
    $user->chk_email = ret_issetor($details['chkemail']);
    $user->chk_telephone = ret_issetor($details['chktelephone']);
    $user->chk_mobile = ret_issetor($details['chkmobile']);
    $user->ac_sms = ret_issetor($details['acsms']);
    $user->ac_email = ret_issetor($details['acemail']);
    $user->ac_telephone = ret_issetor($details['actelephone']);
    $user->ac_mobile = ret_issetor($details['acmobile']);
    $user->ignition_sms = ret_issetor($details['igsms']);
    $user->ignition_email = ret_issetor($details['igemail']);
    $user->ignition_telephone = ret_issetor($details['igtelephone']);
    $user->ignition_mobile = ret_issetor($details['igmobile']);
    $user->temp_sms = ret_issetor($details['tempsms']);
    $user->temp_email = ret_issetor($details['tempemail']);
    $user->temp_telephone = ret_issetor($details['temptelephone']);
    $user->temp_mobile = ret_issetor($details['tempmobile']);
    $user->dailyemail = ret_issetor($details['dailyemail']);
    $user->dailyemail_csv = ret_issetor($details['dailyemail_csv']);
    $user->safcsms = ret_issetor($details['safcsms']);
    $user->safcemail = ret_issetor($details['safcemail']);
    $user->safctelephone = ret_issetor($details['safctelephone']);
    $user->safcmobile = ret_issetor($details['safcmobile']);
    $user->saftsms = ret_issetor($details['saftsms']);
    $user->saftemail = ret_issetor($details['saftemail']);
    $user->safttelephone = ret_issetor($details['safttelephone']);
    $user->saftmobile = ret_issetor($details['saftmobile']);
    if ($user_details->use_advanced_alert == 1) {
        $user->harsh_break_sms = ret_issetor($details['harsh_break_sms']);
        $user->harsh_break_mail = ret_issetor($details['harsh_break_mail']);
        $user->harsh_break_telephone = ret_issetor($details['harsh_break_telephone']);
        $user->harsh_break_mobile = ret_issetor($details['harsh_break_mobile']);
        $user->high_acce_sms = ret_issetor($details['high_acce_sms']);
        $user->high_acce_mail = ret_issetor($details['high_acce_mail']);
        $user->high_acce_telephone = ret_issetor($details['high_acce_telephone']);
        $user->high_acce_mobile = ret_issetor($details['high_acce_mobile']);
        $user->sharp_turn_sms = ret_issetor($details['sharp_turn_sms']);
        $user->sharp_turn_mail = ret_issetor($details['sharp_turn_mail']);
        $user->sharp_turn_telephone = ret_issetor($details['sharp_turn_telephone']);
        $user->sharp_turn_mobile = ret_issetor($details['sharp_turn_mobile']);
        $user->towing_sms = ret_issetor($details['towing_sms']);
        $user->towing_mail = ret_issetor($details['towing_mail']);
        $user->towing_telephone = ret_issetor($details['towing_telephone']);
        $user->towing_mobile = ret_issetor($details['towing_mobile']);
    } else {
        $user->harsh_break_sms = $user->harsh_break_mail = $user->harsh_break_telephone = $user->harsh_break_mobile = $user->high_acce_sms = $user->high_acce_mail = $user->high_acce_telephone = $user->high_acce_mobile = $user->sharp_turn_sms = $user->sharp_turn_mail = $user->sharp_turn_telephone = $user->sharp_turn_mobile = $user->towing_sms = $user->towing_mail = $user->towing_telephone = $user->towing_mobile = 0;
    }
    $user->door_sms = ret_issetor($details['doorsms']);
    $user->door_email = ret_issetor($details['dooremail']);
    $user->door_telephone = ret_issetor($details['doortelephone']);
    $user->door_mobile = ret_issetor($details['doormobile']);
    $user->panic_sms = ret_issetor($details['panic_sms']);
    $user->panic_email = ret_issetor($details['panic_email']);
    $user->panic_telephone = ret_issetor($details['panic_telephone']);
    $user->panic_mobile = ret_issetor($details['panic_mobile']);
    $user->immob_sms = ret_issetor($details['immob_sms']);
    $user->immob_email = ret_issetor($details['immob_email']);
    $user->immob_telephone = ret_issetor($details['immob_telephone']);
    $user->immob_mobile = ret_issetor($details['immob_mobile']);

    /*Checkpoint Exception User Alert Mapping */
    $user->chkExUserMapping = $details['chkExAlertMapping'];

    /* Checkpoint Exception Alerts*/
    $user->chkptExSms = ret_issetor($details['chkptExSms']);
    $user->chkptExEmail = ret_issetor($details['chkptExEmail']);
    $user->chkptExTelephone = ret_issetor($details['chkptExtelephone']);
    $user->chkptExMobile = ret_issetor($details['chkptExMobile']);

    $user->safcmin = GetSafeValueString($details['safcmin'], "string");
    $user->saftmin = GetSafeValueString(isset($details['saftmin'])?$details['saftmin']:"", "string");
    $user->start_alert_time = GetSafeValueString($details['SDate'], "string");
    $user->stop_alert_time = GetSafeValueString($details['EDate'], "string");
    $user->temprepinterval = retval_issetor($details['temprepinterval'], "0");

    $user->customerno = $_SESSION['customerno'];
    include_once '../user/new_alerts_func.php';
    $usermanager->SaveUser($user, $_SESSION['userid']);
}

function modifyuser($details) {
    $usermanager = new UserManager();
    $user_details = getuser($_SESSION["userid"]);
    $user = new VOUser();
    $user->userid = GetSafeValueString($details["userid"], "string");
    $user->password = GetSafeValueString($details["password"], "string");
    $user->realname = GetSafeValueString($details["name"], "string");
    $user->username = GetSafeValueString($details["username"], "string");
    $user->email = GetSafeValueString($details["email"], "string");
    $user->phone = GetSafeValueString($details["phoneno"], "string");
    $user->role = GetSafeValueString($details["role"], "string");
    $user->roleid = GetSafeValueString($details["roleid"], "string");
    $user->group = GetSafeValueString($details["group"], "string");
   // $user->delivery_vehicleid = ($details["vehicleno_db"] != '') ? (int) $details["vehicleno_db"] : null;
    $Groups = array();
    if ($user->roleid == '1') {
        $user->h_id = $_SESSION["heirarchy_id"];
        $Groups = $usermanager->getgroupsfromnationid($user->h_id, $_SESSION['customerno']);
    } elseif ($user->roleid == '2') {
        $user->h_id = GetSafeValueString($details["stateid"], "string");
        $Groups = $usermanager->getgroupsfromstateid($user->h_id, $_SESSION['customerno']);
    } elseif ($user->roleid == '3') {
        $user->h_id = GetSafeValueString($details["districtid"], "string");
        $Groups = $usermanager->getgroupsfromdistrictid($user->h_id, $_SESSION['customerno']);
    } elseif ($user->roleid == '4') {
        $user->h_id = GetSafeValueString($details["cityid"], "string");
        $Groups = $usermanager->getgroupsfromcityid($user->h_id, $_SESSION['customerno']);
    } elseif ($user->roleid == '8') {
        $user->h_id = '0';
        $Groups[] = $user->group;
    } else {
        $user->h_id = '0';
    }
    foreach ($details as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 9) == "to_group_") {
            $Groups[] = substr($single_post_name, 9, 10);
        }
    }
    if ($user->role == 'transporter' || $user->role == 'factoryofficial' || $user->role == 'depotofficial') {
        $Groups[] = -1;
    }
    $user->groups = $Groups;
    if ($_SESSION['use_secondary_sales']) {
        if (!empty($details['srid'])) {
            $user->h_id = $details['srid'];
        } elseif (!empty($details['supid'])) {
            $user->h_id = $details['supid'];
        } elseif (!empty($details['asmid'])) {
            $user->h_id = $details['asmid'];
        } else {
            $user->h_id = 0;
        }
    }
    $user->transporterid = GetSafeValueString($details["transporterid"], "string");
    $user->factoryid = GetSafeValueString($details["factoryid"], "string");
    $user->depotid = GetSafeValueString($details["depotid"], "string");
    $user->customerno = $_SESSION['customerno'];
    $usermanager->SaveUser($user, $_SESSION['userid']);
}

function modifyuser_hierarchy($details) {
    $usermanager = new UserManager();
    $user_details = getuser($_SESSION["userid"]);
    $user = new VOUser();

    $menuconfig = GetSafeValueString($details["menuconfigarr"], "string");
    $menuconfigarr = json_decode($menuconfig);
    $user->menuconfigarr = $menuconfigarr;

    $user->userid = GetSafeValueString($details["userid"], "string");
    $user->password = GetSafeValueString($details["password"], "string");
    $user->realname = GetSafeValueString($details["name"], "string");
    $user->username = GetSafeValueString($details["username"], "string");
    $user->email = GetSafeValueString($details["email"], "string");
    $user->phone = GetSafeValueString($details["phoneno"], "string");
    $user->role = GetSafeValueString($details["role"], "string");
    $user->roleid = GetSafeValueString($details["roleid"], "string");
    $user->group = GetSafeValueString($details["group"], "string");
    $user->delivery_vehicleid = (isset($details["vehicleno_db"]) && !empty($details["vehicleno_db"])) ? (int) $details["vehicleno_db"] : null;
    $user->delivery_vehicleid = null;
    $Vehicles = array();
    if (isset($details["parentuser"])) {
        $user->h_id = GetSafeValueString($details["parentuser"], "string");
    } elseif (isset($details["higheruser"])) {
        $user->h_id = GetSafeValueString($details["higheruser"], "string");
    } else {
        $user->h_id = '';
    }
    foreach ($details as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 9) == "to_group_") {
            $Groups[] = substr($single_post_name, 9, 10);
        }
    }
    foreach ($details as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 11) == "to_vehicle_") {
            $Vehicles[] = substr($single_post_name, 11, 13);
        }
    }
    if ($user->role == 'transporter' || $user->role == 'factoryofficial' || $user->role == 'depotofficial') {
        $Groups[] = -1;
    }

    $user->groups = $Groups;
    $user->vehicles = $Vehicles;
    if ($_SESSION['use_secondary_sales']) {
        if (!empty($details['srid'])) {
            $user->h_id = $details['srid'];
        } elseif (!empty($details['supid'])) {
            $user->h_id = $details['supid'];
        } elseif (!empty($details['asmid'])) {
            $user->h_id = $details['asmid'];
        } else {
            $user->h_id = 0;
        }
    }
    //print_r($Groups);die();
    $user->transporterid = GetSafeValueString(isset($details["transporterid"])?$details["transporterid"]:0, "string");
    $user->factoryid = GetSafeValueString(isset($details["factoryid"])?$details["factoryid"]:0, "string");
    $user->depotid = GetSafeValueString(isset($details["depotid"])?$details["depotid"]:0, "string");
    $user->customerno = $_SESSION['customerno'];
    $usermanager->SaveUser($user, $_SESSION['userid']);
    echo json_encode("true");
}

function checkusername($username) {
    $username = GetSafeValueString($username, 'string');
    $usermanager = new UserManager();
    $users = $usermanager->getallusers();
    if (isset($users)) {
        foreach ($users as $thisuser) {
            if ($thisuser->username == $username) {
                $status = trim("notok");
                break;
            }
        }
        if (!isset($status)) {
            $status = trim("ok");
        }
    } else {
        $status = trim("ok");
    }
    echo trim($status);
}

function getgroups() {
    $GroupManager = new GroupManager($_SESSION['customerno']);
    $groups = $GroupManager->getallgroups();
    return $groups;
}

function getmappedgroup($userid) {
    $groupmanager = new GroupManager($_SESSION['customerno']);
    $groups = $groupmanager->getgroupsbyuserid($userid);
    return $groups;
}

function getmappedvehicles($userid) {
    $isWarehouse = null;
    if ($_SESSION['switch_to'] == '3') {
        $isWarehouse = 1;
    }
    $vehiclemgr = new VehicleManager($_SESSION['customerno'], $_SESSION['userid']);
    $groups = $vehiclemgr->getvehiclesbyuserid($userid, $isWarehouse);
    return $groups;
}

function getnations($userid) {
    $nationmanager = new NationManager($_SESSION['customerno']);
    $nations = $nationmanager->get_all_nations($userid);
    return $nations;
}

function getstates($userid) {
    $statemanager = new StateManager($_SESSION['customerno']);
    $states = $statemanager->get_all_states($userid);
    return $states;
}

function getdistricts($userid) {
    $districtmanager = new DistrictManager($_SESSION['customerno']);
    $districts = $districtmanager->get_all_districts($userid);
    return $districts;
}

function getcities() {
    $CityManager = new CityManager($_SESSION['customerno']);
    $cities = $CityManager->get_all_cities($_SESSION['userid']);
    return $cities;
}

function getallasm() {
    $sales = new Sales($_SESSION['customerno'], $_SESSION['userid']);
    $asms = $sales->getallasm();
    return $asms;
}

function getallsupervisor() {
    $sales = new Sales($_SESSION['customerno'], $_SESSION['userid']);
    $supervisors = $sales->getallsupervisor();
    return $supervisors;
}

function getallsr() {
    $sales = new Sales($_SESSION['customerno'], $_SESSION['userid']);
    $srs = $sales->getallsr();
    return $srs;
}

function get_vehicles_option($vehid = '') {
    $customerno = $_SESSION['customerno'];
    $vehiclemanager = new vehiclemanager($customerno);
    $vehiclesbygroup = $vehiclemanager->get_all_vehicles();
    $option = '<select name="vehicleno_db" id="vehicleno_db" ><option value="">--select--</option>';
    if (isset($vehiclesbygroup)) {
        foreach ($vehiclesbygroup as $data) {
            if ($vehid == $data->vehicleid) {
                $option .= "<option value='$data->vehicleid' selected>$data->vehicleno</option>";
            } else {
                $option .= "<option value='$data->vehicleid'>$data->vehicleno</option>";
            }
        }
    }
    $option .= "</select>";
    return $option;
}

function getHigherUsersForRole($objRole) {
    $hm = new HierarchyManager($_SESSION['customerno'], $_SESSION['userid']);
    $users = $hm->getHigherUsersForRoleid($objRole);
    return $users;
}

function getReportsMaster($customerno) {
    $objUserManager = new UserManager();
    $reportsMaster = $objUserManager->getReportsMaster($customerno);
    return $reportsMaster;
}

function getUserReports($userid, $customerno) {
    $objUserManager = new UserManager();
    $mappedReports = $objUserManager->getUserReports($userid, $customerno);
    return $mappedReports;
}

function updateUserReports($objUser) {
    $objUserManager = new UserManager();
    $mappedReports = $objUserManager->updateUserReports($objUser);
    return $mappedReports;
}



function sendMailNewUser($details) {
    $username = GetSafeValueString($details["username"], "string");
    $password = GetSafeValueString($details["password"], "string");
    $realname = GetSafeValueString($details["name"], "string");
    $email = GetSafeValueString($details["email"], "string");
    $phone = GetSafeValueString($details["phoneno"], "string");
    $role = GetSafeValueString($details["role"], "string");
    $to = $email;
    $subject = "Welcome to Elixia";
    $content = '<!DOCTYPE html><html>
            <head><meta charset="utf-8"></head>
            <body>
                <div style="color: #0099ff">
                    <h4>Dear Sir,</h4>
                    <p>Greetings from Elixia Tech Solutions Pvt Ltd…..!!!!</p>
                    <p>Thanks a lot for choosing Elixia Speed to manage your fleet .
                        You can now track your vehicles by following two ways:</p>
                    <h4>WEB:</h4>
                    <p>1.Visit <a href="http://www.speed.elixiatech.com/">http://www.speed.elixiatech.com/</a></p>
                    <p>2.Login using the credentials mentioned below.</p>
                    <p>3.Contact us so that we can explain how to make the best use of the system</p>
                    <h4>ANDROID AND IPHONE APP:</h4>
                    <p>Lets you track your vehicles right from your phone.</p>
                    <span style="font-weight: bold">The apps are free of cost for all our customers.</span>
                    <p>You may download the app by searching for "Elixia Speed" on
                        Play Store (for Android users) and App Store (for iphone users).</p>
                    <p>Your confidential credentials are as follows:</p>
                    <br>
                    <p><span style="font-weight: bold;color: #592726"> Username:</span> {{USERNAME}} </p>
                    <p><span style="font-weight: bold;color: #592726">Password:</span> {{PASSWORD}} </p>
                    <p><span style="font-weight: bold;">Please change the password as soon as you login.</span>
                        You may go to &#60; Username &#62; -> My Account -> Change Password to do the same.</p>
                    <p>You may add more users from the interface provided by going to Masters -> Users -> Add Users</p>
                    <p>Also, you may update the email and telephone number to receive requested alerts by going to &#60; Username&#62; -> My Account</p>
                    <p>Please find below a brief about the system:</p>
                    <br>
                    <p>The following tabs will be of most importance to you:</p>
                    <br>
                    <p>1. <span style="font-weight: bold">Dashboard:</span> This gives you the pie charts with vehicles distributed according to their statuses.</p>
                    <p>In addition, you have one more pie chart which distributes vehicles according to checkpoints.</p>
                    <p>This helps you to understand if your vehicles are in warehouse/parking/client site etc. at one go, provided you have created checkpoints for such locations.</p>
                    <br>
                    <p>2. <span style="font-weight: bold">Map:</span> By clicking on map, you will be able to see the real-time location of your vehicle.</p>
                    <p>By hovering on the vehicle\'s icon, you will be able to see the current speed and driver details.</p>
                    <p>The vehicle icon changes colour depending on the current status of the vehicle.</p>
                    <br>
                    <blockquote>
                        <p><span style="color: #FF0000;">&#9899; Red colour indicates over speeding vehicles</span></p>
                        <p><span style="color: #009900;">&#9899; Green colour indicates vehicles running at normal speed</span></p>
                        <p>&#9899 Blue colour indicates vehicles with ignition on, but standing idle at the same location</p>
                        <p><span style="color: #C64E00;">&#9899; Orange colour indicates that the vehicle has ignition turned off</span></p>
                        <p><span style="color: #2F4F4F;">&#9899; Grey colour indicates the vehicles that are not sending data, which may be due to low network/power cut</span></p>
                    </blockquote>
                    <p>You may contact an Elixir to get more details for these vehicles.</p>
                    <p>3. <span style="font-weight: bold">Real-Time Data:</span> Vehicle & Device tab shows the real-time location, current speed, distance travelled during the day, external battery status,</p>
                    <p>driver details, network strength, device tamper status, power status and several similar details.</p>
                    <p>4. <span style="font-weight: bold">Masters->Fencing:</span> Fencing tab will enable you to create fences which will give alerts in case the fence is breached.</p>
                    <p>5. <span style="font-weight: bold">Masters->Checkpoints:</span> Checkpoints will enable you to place holders on map to get alerts where the fleet has reached.</p>
                    <p>6. <span style="font-weight: bold">Reports:</span> Vehicle history will show the route on map which the vehicle had taken on a particular date. Daily travel history will show the same information in a table format.</p>
                    <p>Several more reports are available which you may explore for better utilization of your account</p>
                    <br>
                    <blockquote>
                        <p>&#9899; <span style="font-weight: bold">Location Report</span> gives you the location update for every vehicle for the selected time period.</p>
                        <p>You may select whether you want the update every defined distance, or for every defined time period (i.e every 5 kms or every 10 minutes).</p>
                        <p>&#9899; <span style="font-weight: bold">Stoppage Report</span> specifies where all the vehicle was standing idle for more than the specified time period, so that you can check unscheduled stoppages and control them.</p>
                        <p>&#9899; <span style="font-weight: bold">Over speed Report</span> helps you understand the different locations where the driver over speed the vehicle, along with the over speeding duration and top speed.</p>
                    </blockquote>
                    <p>7. <span style="font-weight: bold">Alerts:</span> Unnecessary idling needs to be checked and hence, we have a new alert for you. You can define the maximum time a vehicle can remain idle,</p>
                    <p>if within a checkpoint and if in transit and you will receive an alert every time the maximum duration is breached.</p>
                    <br>
                    <p>I hope the above changes will help you better manage your fleet. In case of any queries or feedback, please feel free to write back to us.</p>
                    <p>More updates and more exciting news are coming your way!! So enjoy tracking while we bring more features to your Elixia Speed account.</p>
                    <br>
                    <p>Please feel free to contact us on 022-2513-7471 or email us at support@elixiatech.com in case you have any queries regarding using the system. </p>
                    <br>
                    <p> <span style="color: #592726">Regards,</span></p>
                    <p> <span style="color: #592726">Elixia Tech Support Team</span></p>
                    <p><img src="http://speed.elixiatech.com/images/elixia_logo_75.png" alt="elixialogo"></p>
                </div>
            </body>
        </html>
        ';
    $content = str_replace("{{USERNAME}}", $username, $content);
    $content = str_replace("{{PASSWORD}}", $password, $content);
    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    if (!@mail($to, $subject, $content, $headers)) {
        // message sending failed
        return false;
    }
    return true;
}


function getmaintenanceusers($customerno, $userid,$roleid){
    $objUserManager = new UserManager();
    $usersdata = $objUserManager->maintenance_userslist($customerno,$userid,$roleid);

    $html = '';
    if(isset($usersdata)){
        $html .= "<table>";
        $html .= "<tr>";
        $html .= "<th>Username</th>";
        $html .= "<th>Email</th>";
        $html .= "<th>Realname</th>";
        $html .= "<th>Phone</th>";
        $html .= "<th>Role</th>";
        $html .= "<th>Branchcode</th>";
        $html .= "<th>Branchname</th>";
        $html .= "<th>Regioncode</th>";
        $html .= "<th>Regionname</th>";
        $html .= "<th>Zonecode</th>";
        $html .= "<th>Zonename</th>";
        $html .= "<th>ReginalUserSAP</th>";
        $html .= "<th>ZonalUserSAP</th>";
        $html .= "</tr>";

        foreach ($usersdata as $row){
            $html .= "<tr>";
            $html .= "<td>".htmlspecialchars($row["username"])."</td>";
            $html .= "<td>".htmlspecialchars($row["email"])."</td>";
            $html .= "<td>".htmlspecialchars($row["realname"])."</td>";
            $html .= "<td>".htmlspecialchars($row["phone"])."</td>";
            $html .= "<td>".htmlspecialchars($row["role"])."</td>";
            $html .= "<td>".htmlspecialchars($row["branchcode"])."</td>";
            $html .= "<td>".htmlspecialchars($row["branchname"])."</td>";
            $html .= "<td>".htmlspecialchars($row["regioncode"])."</td>";
            $html .= "<td>".htmlspecialchars($row["regionname"])."</td>";
            $html .= "<td>".htmlspecialchars($row["zonecode"])."</td>";
            $html .= "<td>".htmlspecialchars($row["zonename"])."</td>";
            $html .= "<td>".htmlspecialchars($row["regionalUserSAP"])."</td>";
            $html .= "<td>".htmlspecialchars($row["zonalUserSAP"])."</td>";
            $html .= "</tr>";
        }
         $html .= "</table>";
         echo $html;
    }
    return $html;
}


function getvehiclelist($customerno, $userid,$roleid){

    $objUserManager = new UserManager();
    $usersdata = $objUserManager->vehiclelist($customerno,$userid,$roleid);
    $html = '';
    if(isset($usersdata) && !empty($usersdata)){
        $html .= "<table>";
        $html .= "<tr>";
        $html .= "<th>Vehicleid</th>";
        $html .= "<th>Vehicleno</th>";
        $html .= "<th>Userid</th>";
        $html .= "<th>Role</th>";
        $html .= "<th>Username</th>";
        $html .= "<th>Email</th>";
        $html .= "<th>Groupid</th>";
        $html .= "<th>Branchcode</th>";
        $html .= "<th>Branchname</th>";
        $html .= "<th>Regioncode</th>";
        $html .= "<th>Regionname</th>";
        $html .= "<th>Zonecode</th>";
        $html .= "<th>Zonename</th>";
        $html .= "<th>ReginalUserSAP</th>";
        $html .= "<th>ZonalUserSAP</th>";
        $html .= "</tr>";

        foreach ($usersdata as $row){
            $html .= "<tr>";
            $html .= "<td>".htmlspecialchars($row["vehicleid"])."</td>";
            $html .= "<td>".htmlspecialchars($row["vehicleno"])."</td>";
            $html .= "<td>".htmlspecialchars($row["userid"])."</td>";
            $html .= "<td>".htmlspecialchars($row["role"])."</td>";
            $html .= "<td>".htmlspecialchars($row["username"])."</td>";
            $html .= "<td>".htmlspecialchars($row["email"])."</td>";
            $html .= "<td>".htmlspecialchars($row["groupid"])."</td>";
            $html .= "<td>".htmlspecialchars($row["branchcode"])."</td>";
            $html .= "<td>".htmlspecialchars($row["branchname"])."</td>";
            $html .= "<td>".htmlspecialchars($row["regioncode"])."</td>";
            $html .= "<td>".htmlspecialchars($row["regionname"])."</td>";
            $html .= "<td>".htmlspecialchars($row["zonecode"])."</td>";
            $html .= "<td>".htmlspecialchars($row["zonename"])."</td>";
            $html .= "<td>".htmlspecialchars($row["regionalUserSAP"])."</td>";
            $html .= "<td>".htmlspecialchars($row["zonalUserSAP"])."</td>";
            $html .= "</tr>";
        }
         $html .= "</table>";
         echo $html;
    }
    return $html;
}

function getVehiclesByUser($userid, $term) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $vehicleno = $vm->getVehiclesByUser($userid, $term);
    return $vehicleno;
}
function get_deleted_users() {
    $usermanager = new UserManager();
    $users = $usermanager->getDeletedUsers($_SESSION['customerno']);
    return $users;
}
function convert_to_utf8(&$data){
    if(isset($data)){
        if(is_array($data)){
            foreach($data as $k=>&$v){
                $v = convert_to_utf8($v);
            }
        }elseif(is_object($data)){
            foreach($data as $k=>&$v){
                $v = convert_to_utf8($v);
            }
        }else{
            $data = iconv("ISO-8859-1","UTF-8",$data);
        }
    }
    return $data;
}
?>
