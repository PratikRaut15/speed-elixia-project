<?php
include_once "session.php";
include_once "db.php";
include_once "../../lib/system/utilities.php";
include "loginorelse.php";
include_once "../../constants/constants.php";
include_once "../../lib/system/DatabaseManager.php";
include_once "../../lib/system/Sanitise.php";
include_once "../../lib/system/class.phpmailer.php";
//include_once '../../lib/system/class.smtp.php';
$db = new DatabaseManager();

if (IsHead()) {
    $msg = "<P>You are an authorized user</p>";
} else {
    header("Location: index.php");
    exit;
}

// Based on details passed in, Create the customer.
$custcompany = GetSafeValueString($_POST["ccompany"], "string");
$custsms = GetSafeValueString($_POST["csmspack"], "long");
$custtelephonicalert = GetSafeValueString($_POST["ctelephonepack"], "long");
$teamid = GetSafeValueString(GetLoggedInUserId(), "long");
$primaryusername = GetSafeValueString($_POST["cprimaryname"], "string");
$primaryphone = GetSafeValueString($_POST["cprimaryphone"], "string");
$primaryuserlogin = GetSafeValueString($_POST["cprimaryusername"], "string");
$primaryuserpassword = GetSafeValueString($_POST["cprimarypassword"], "string");
$timezone = GetSafeValueString($_POST["timezone"], "string");
$advanced_alert = (int) $_POST['advancedAlerts'];
$sales_person = (int) $_POST['sales'];

// Use Loading
$cloading = 1;
if (!isset($_POST["cloading"])) {
    $cloading = 0;
}

// Use AC
$cac = 1;
if (!isset($_POST["cac"])) {
    $cac = 0;
}

// Use Genset
$cgenset = 1;
if (!isset($_POST["cgenset"])) {
    $cgenset = 0;
}

// Use Fuel
$cfuel = 1;
if (!isset($_POST["cfuel"])) {
    $cfuel = 0;
}

// Use Door
$cdoor = 1;
if (!isset($_POST["cdoor"])) {
    $cdoor = 0;
}

// Use Panic
$cpanic = 1;
if (!isset($_POST["cpanic"])) {
    $cpanic = 0;
}

// Use Buzzer
$cbuzzer = 1;
if (!isset($_POST["cbuzzer"])) {
    $cbuzzer = 0;
}

// Use Immobilizer
$cimmo = 1;
if (!isset($_POST["cimmobilizer"])) {
    $cimmo = 0;
}

// Use mobility
$cimob = 1;
if (!isset($_POST["cimobility"])) {
    $cimob = 0;
}

// Use Sales engage
$csalesengage = 1;
if (!isset($_POST["csalesengage"])) {
    $csalesengage = 0;
}

// Use Geo Location
$cgeolocation = 1;
if (!isset($_POST["cgeolocation"])) {
    $cgeolocation = 0;
}

//Use Ttracking
$ctraking = 0;
if (isset($_POST['ctracking'])) {
    $ctraking = 1;
}

// Use Maintenance
$cmaintenance = 1;
if (!isset($_POST["cmaintenance"])) {
    $cmaintenance = 0;
}

// Use Delivery
$cdelivery = 1;
if (!isset($_POST["cdelivery"])) {
    $cdelivery = 0;
}

// Use Routing
$crouting = 1;
if (!isset($_POST["crouting"])) {
    $crouting = 0;
}

// Use Portable
$cportable = 1;
if (!isset($_POST["cportable"])) {
    $cportable = 0;
}

// Use Heirarchy
$cheirarchy = 1;
if (!isset($_POST["cheirarchy"])) {
    $cheirarchy = 0;
} else if ($cmaintenance == 0) {
    $cheirarchy = 0;
}

// Use Trace
$ctrace = 1;
if (!isset($_POST["ctrace"])) {
    $ctrace = 0;
}

// Use Radar
$cradar = 1;
if (!isset($_POST["cradar"])) {
    $cradar = 0;
}


// Temperature Sensors
$ctempsensor = GetSafeValueString($_POST["ctempsensor"], "long");

date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d");
$todaydatetime = date("Y-m-d H:i:s");
$SQL = sprintf("INSERT INTO " . DB_PARENT . ".customer (`customername` ,`customercompany` , `dateadded` , `totalsms`,`smsleft`, `total_tel_alert`,`tel_alertleft`, `teamid`,`use_msgkey`,`use_geolocation`,`use_tracking`,`use_maintenance`,`temp_sensors`,`use_portable`,`use_hierarchy`, `use_advanced_alert`, `use_ac_sensor`, `use_genset_sensor`, `use_fuel_sensor`, `use_door_sensor`, `use_routing`, `use_panic`, `use_buzzer`, `use_immobiliser`,`use_mobility`, `use_delivery`,`use_sales`,`use_trace`,`use_radar`, `timezone`, `salesid`,`createdtime`)"
        . " VALUES ('%s', '%s', '%s', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d','%d', '%d', '%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%s')",
        $primaryusername, $custcompany, Sanitise::Date($today), Sanitise::Long($custsms), Sanitise::Long($custsms), Sanitise::Long($custtelephonicalert), Sanitise::Long($custtelephonicalert), Sanitise::Long($teamid), Sanitise::Long($cloading), Sanitise::Long($cgeolocation), Sanitise::Long($ctraking), Sanitise::Long($cmaintenance), Sanitise::Long($ctempsensor), Sanitise::Long($cportable), Sanitise::Long($cheirarchy), $advanced_alert, $cac, $cgenset, $cfuel, $cdoor, $crouting, $cpanic, $cbuzzer, $cimmo, $cimob, $cdelivery, $csalesengage, $ctrace, $cradar, $timezone,$sales_person,$todaydatetime);

$db->executeQuery($SQL);

$SQL = sprintf("INSERT INTO " . DB_ELIXIATECH . ".customer (`customername` ,`customercompany` , `dateadded` , `totalsms`,`smsleft`, `total_tel_alert`,`tel_alertleft`, `teamid`,`use_msgkey`,`use_geolocation`,`use_tracking`,`use_maintenance`,`temp_sensors`,`use_portable`,`use_hierarchy`, `use_advanced_alert`, `use_ac_sensor`, `use_genset_sensor`, `use_fuel_sensor`, `use_door_sensor`, `use_routing`, `use_panic`, `use_buzzer`, `use_immobiliser`,`use_mobility`,`use_delivery`,`use_sales`,`use_trace`,`use_radar`,`timezone`,`salesid`,`createdtime`)"
        . " VALUES ('%s', '%s', '%s', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d', '%d','%d', '%d', '%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%d','%s')",
        $primaryusername, $custcompany, Sanitise::Date($today), Sanitise::Long($custsms), Sanitise::Long($custsms), Sanitise::Long($custtelephonicalert), Sanitise::Long($custtelephonicalert), Sanitise::Long($teamid), Sanitise::Long($cloading), Sanitise::Long($cgeolocation), Sanitise::Long($ctraking), Sanitise::Long($cmaintenance), Sanitise::Long($ctempsensor), Sanitise::Long($cportable), Sanitise::Long($cheirarchy), $advanced_alert, $cac, $cgenset, $cfuel, $cdoor, $crouting, $cpanic, $cbuzzer, $cimmo, $cimob, $cdelivery, $csalesengage, $ctrace, $cradar, $timezone,$sales_person,$todaydatetime);

$db->executeQuery($SQL);

$customerid = $db->get_insertedId();

$SQL = sprintf("INSERT INTO " . DB_PARENT . ".contactperson_details (`typeid`, `person_name`, `cp_email1`, `cp_phone1`, `customerno`, `isdeleted`) VALUES (1,'%s','%s','%s',%d,0)", $primaryusername, $primaryuserlogin, $primaryphone, $customerid);
$db->executeQuery($SQL);


$SQL = sprintf('SELECT totalsms FROM ' . DB_PARENT . '.customer WHERE customerno = ' . $customerid);
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $totalsms = $row["totalsms"];
    }
}

$SQL = sprintf('SELECT total_tel_alert FROM ' . DB_PARENT . '.customer WHERE customerno = ' . $customerid);
$db->executeQuery($SQL);
if ($db->get_rowCount() > 0) {
    while ($row = $db->get_nextRow()) {
        $total_tel_alert = $row["total_tel_alert"];
    }
}

date_default_timezone_set("Asia/Calcutta");
$today = date("Y-m-d H:i:s");
$SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
`customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`)
VALUES (
$customerid, 0, '%s', 2, '%s', 0, '%s', '%s', '%s', '%s')", GetLoggedInUserId(), Sanitise::DateTime($today), "SMS Added : " . $custsms . ". Total SMS : " . $totalsms, "", "", "");
$db->executeQuery($SQLunit);

$SQLunit = sprintf("INSERT INTO " . DB_PARENT . ".trans_history (
`customerno` ,`unitid`,`teamid`, `type`, `trans_time`, `statusid`, `transaction`, `simcardno`, `invoiceno`, `expirydate`)
VALUES (
$customerid, 0, '%s', 2, '%s', 0, '%s', '%s', '%s', '%s')", GetLoggedInUserId(), Sanitise::DateTime($today), "Telephonic Alerts Added : " . $custtelephonicalert . ". Total SMS : " . $total_tel_alert, "", "", "");
$db->executeQuery($SQLunit);

// If the customer record was saved OK, Create their directories.
$relativepath = "../..";
if (!is_dir($relativepath . '/customer/' . $customerid)) {
    // Directory doesn't exist.
    mkdir($relativepath . '/customer/' . $customerid, 0777, true) or die("Could not create directory");
}
if (!is_dir($relativepath . '/customer/' . $customerid . '/unitno')) {
    // Directory doesn't exist.
    mkdir($relativepath . '/customer/' . $customerid . '/unitno', 0777, true) or die("Could not create directory");
}
if (!is_dir($relativepath . '/customer/' . $customerid . '/reports')) {
    // Directory doesn't exist.
    mkdir($relativepath . '/customer/' . $customerid . '/reports', 0777, true) or die("Could not create directory");
}

// Add the primary user.
$userkey1 = mt_rand();
$today = date("Y-m-d H:i:s");
$todaydatetime = date("Y-m-d H:i:s");
$moduleidparam = 2;

if($cmaintenance == 1 && $ctraking==1 && $cheirarchy==1){
$sql = "INSERT into user (customerno, realname, username, password, role, roleid, email, userkey)
VALUES ($customerid,'$primaryusername','$primaryuserlogin', sha1('$primaryuserpassword'),'Administrator', 5,'$primaryuserlogin', $userkey1)";
 $db->executeQuery($sql);
//Add default menus for user in usermenumapping
$sqluser = "INSERT INTO usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT menuid,$lastuserid,1,1,1,$customerid,$lastuserid,'$today',1 FROM menu_master where moduleid=$moduleidparam AND isdeleted=0 AND (customerno=0 OR customerno=".$customerid.")";
$db->executeQuery($sqluser);
}elseif($cmaintenance == 1 && $cheirarchy == 1 && $ctraking!=1){
$sql = "INSERT into user (customerno, realname, username, password, role, roleid,email, userkey)
VALUES ($customerid,'$primaryusername','$primaryuserlogin', sha1('$primaryuserpassword'),'Master', 1,'$primaryuserlogin', $userkey1)";
$db->executeQuery($sql);
$lastuserid = $db->get_insertedId();
//Add default menus for user in usermenumapping
$sqluser = "INSERT INTO usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
SELECT menuid,$lastuserid,1,1,1,$customerid,$lastuserid,'$today',1 FROM menu_master where moduleid=$moduleidparam AND isdeleted=0 AND (customerno=0 OR customerno=".$customerid.")";
$db->executeQuery($sqluser);
}else{
$sql = "INSERT into user (customerno, realname, username, password, role, roleid, email, userkey)
VALUES ($customerid,'$primaryusername','$primaryuserlogin', sha1('$primaryuserpassword'),'Administrator', 5,'$primaryuserlogin', $userkey1)";
 $db->executeQuery($sql);
}


// Add the support user.
$userkey2 = mt_rand();
$sql = "INSERT into user (customerno, realname, username, password, role, userkey)
VALUES ($customerid, 'Elixir', 'elixir$customerid', sha1('el!365x!@'),'elixir', $userkey2)";
$db->executeQuery($sql);
$last_elixir_userid = $db->get_insertedId();
//set user menus default add for maintenance
if($cmaintenance == 1 && $cheirarchy == 1 && $ctraking==1){
    $sqluserex = "INSERT INTO usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
    SELECT menuid,$last_elixir_userid,1,1,1,$customerid,$last_elixir_userid,'$today',1 FROM menu_master where moduleid=$moduleidparam AND isdeleted=0 AND (customerno=0 OR customerno=".$customerid.")";
    $db->executeQuery($sqluserex);
}

if($cmaintenance == 1 && $cheirarchy == 1 && $ctraking!=1){
    $sqluserex = "INSERT INTO usermenu_mapping (menuid,userid,add_permission,edit_permission,delete_permission,customerno,created_by,created_on,isactive)
    SELECT menuid,$last_elixir_userid,1,1,1,$customerid,$last_elixir_userid,'$today',1 FROM menu_master where moduleid=$moduleidparam AND isdeleted=0 AND (customerno=0 OR customerno=".$customerid.")";
    $db->executeQuery($sqluserex);
}
// send email
$arrTo = array($primaryuserlogin);
$strCCMailIds = "support@elixiatech.com";
$strBCCMailIds = "sanketsheth@elixiatech.com";
$subject = "Welcome To Elixia Family";
$message = file_get_contents('../emailtemplates/teamCreateCustomer.html');
$message = str_replace("{{CUSTOMERNAME}}", $primaryusername, $message);
$message = str_replace("{{USERNAME}}", $primaryuserlogin, $message);
$message = str_replace("{{PASSWORD}}", $primaryuserpassword, $message);
$attachmentFilePath = "";
$attachmentFileName = "";
$isTemplatedMessage = 1;
sendMailUtil($arrTo, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);

header("Location: customers.php");
?>
