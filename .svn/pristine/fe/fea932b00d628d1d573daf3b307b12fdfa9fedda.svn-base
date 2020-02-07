<?php

include 'driver_functions.php';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";

if ($action == "checkdrivername" && isset($_POST['drivername'])) {
    checkdrivername($_POST['drivername']);
} else if (isset($_POST["d"]) && isset($_POST["ds"])) {
    mapdriver($_POST["d"], $_POST["ds"]);
} else if (isset($_POST["ds"])) {
    demapdriver($_POST["ds"]);
} else if ($action == "checkdriverusername" && isset($_POST['username'])) {
    checkdriverusername($_POST['username']);
} else if (isset($_POST['d'])) {
    drivereligibility($_POST['d']);
} else if ($action == 'add') {
    $addv = new stdClass();
    $addv->drivername = GetSafeValueString($_POST["drivername"], "string");
    $addv->driverlicno = GetSafeValueString($_POST['drivelicno'], "string");
    $addv->driverphone = GetSafeValueString($_POST['drivephoneno'], "string");
    $addv->birthdate = GetSafeValueString($_POST['BDate'], "string");
    $addv->age = GetSafeValueString($_POST['age'], "string");
    $addv->bloodgroup = GetSafeValueString($_POST['bloodgroup'], "string");
    $addv->LicvalidDate = GetSafeValueString($_POST['LicvalidDate'], "string");
    $addv->lic_issue_auth = GetSafeValueString($_POST['lic_issue_auth'], "string");
    $addv->localadd = GetSafeValueString($_POST['localadd'], "string");
    $addv->loc_tel_no = GetSafeValueString($_POST['loc_tel_no'], "string");
    $addv->loc_mob_no = GetSafeValueString($_POST['loc_mob_no'], "string");
    $addv->emergency_contact_name1 = GetSafeValueString($_POST['emergency_contact_name1'], "string");
    $addv->emergency_contact_no1 = GetSafeValueString($_POST['emergency_contact_no1'], "string");
    $addv->emergency_contact_name2 = GetSafeValueString($_POST['emergency_contact_name2'], "string");
    $addv->emergency_contact_no2 = GetSafeValueString($_POST['emergency_contact_no2'], "string");
    $addv->native_addr = GetSafeValueString($_POST['native_addr'], "string");
    $addv->nat_tel_no = GetSafeValueString($_POST['nat_tel_no'], "string");
    $addv->nat_mob_no = GetSafeValueString($_POST['nat_mob_no'], "string");
    $addv->natemergency_contact_name1 = GetSafeValueString($_POST['natemergency_contact_name1'], "string");
    $addv->natemergency_contact_no1 = GetSafeValueString($_POST['natemergency_contact_no1'], "string");
    $addv->natemergency_contact_name2 = GetSafeValueString($_POST['natemergency_contact_name2'], "string");
    $addv->natemergency_contact_no2 = GetSafeValueString($_POST['natemergency_contact_no2'], "string");
    $addv->pre_employer = GetSafeValueString($_POST['pre_employer'], "string");
    $addv->service_period = GetSafeValueString($_POST['service_period'], "string");
    $addv->oldservice_contact_name = GetSafeValueString($_POST['oldservice_contact_name'], "string");
    $addv->oldservice_contact = GetSafeValueString($_POST['oldservice_contact'], "string");
    $extension = GetSafeValueString($_POST['extension'], "string");
    $other1 = GetSafeValueString($_POST['other1'], "string");
    $addv->alertdays = $_POST['alertdays'];
    $addv->driveralert = $_POST['driveralert'];
    $addv->email = $_POST['email'];
    $addv->sms = $_POST['sms'];
    $addv->username = GetSafeValueString($_POST['username'], "string");
    $addv->pass = sha1(GetSafeValueString($_POST['pwd'], "string"));
    $addv->mail = GetSafeValueString($_POST['mail'], "string");
    $addv->phno = GetSafeValueString($_POST['phno'], "string");
    $addv->userkey = mt_rand();

    if ($other1 != "" && $extension != "") {
        $addv->filename1 = $other1 . '.' . $extension;
    } else {
        $addv->filename1 = "";
    }
    $response = adddriver($addv);
    echo $response;

} else if ($action == 'edit') {
    $addv = new stdClass();
    $addv->driverid = GetSafeValueString($_POST["driverid"], "string");
    $addv->userkey = GetSafeValueString($_POST["userkey"], "string");
    $addv->isDriverApp = ($_POST["edit_rad"] == "no") ? 0 : 1;

    $addv->vehicleid = GetSafeValueString($_POST["vehicleid"], "string");
    $addv->drivername = GetSafeValueString($_POST["dname"], "string");
    $addv->driverlicno = GetSafeValueString($_POST['drivelicno'], "string");
    $addv->driverphone = GetSafeValueString($_POST['drivephoneno'], "string");
    $addv->birthdate = GetSafeValueString($_POST['BDate'], "string");
    $addv->age = GetSafeValueString($_POST['age'], "string");
    $addv->bloodgroup = GetSafeValueString($_POST['bloodgroup'], "string");
    $addv->LicvalidDate = GetSafeValueString($_POST['LicvalidDate'], "string");
    $addv->lic_issue_auth = GetSafeValueString($_POST['lic_issue_auth'], "string");
    $addv->localadd = GetSafeValueString($_POST['localadd'], "string");
    $addv->loc_tel_no = GetSafeValueString($_POST['loc_tel_no'], "string");
    $addv->loc_mob_no = GetSafeValueString($_POST['loc_mob_no'], "string");
    $addv->emergency_contact_name1 = GetSafeValueString($_POST['emergency_contact_name1'], "string");
    $addv->emergency_contact_no1 = GetSafeValueString($_POST['emergency_contact_no1'], "string");
    $addv->emergency_contact_name2 = GetSafeValueString($_POST['emergency_contact_name2'], "string");
    $addv->emergency_contact_no2 = GetSafeValueString($_POST['emergency_contact_no2'], "string");
    $addv->native_addr = GetSafeValueString($_POST['native_addr'], "string");
    $addv->nat_tel_no = GetSafeValueString($_POST['nat_tel_no'], "string");
    $addv->nat_mob_no = GetSafeValueString($_POST['nat_mob_no'], "string");
    $addv->natemergency_contact_name1 = GetSafeValueString($_POST['natemergency_contact_name1'], "string");
    $addv->natemergency_contact_no1 = GetSafeValueString($_POST['natemergency_contact_no1'], "string");
    $addv->natemergency_contact_name2 = GetSafeValueString($_POST['natemergency_contact_name2'], "string");
    $addv->natemergency_contact_no2 = GetSafeValueString($_POST['natemergency_contact_no2'], "string");
    $addv->pre_employer = GetSafeValueString($_POST['pre_employer'], "string");
    $addv->service_period = GetSafeValueString($_POST['service_period'], "string");
    $addv->oldservice_contact_name = GetSafeValueString($_POST['oldservice_contact_name'], "string");
    $addv->oldservice_contact = GetSafeValueString($_POST['oldservice_contact'], "string");
    $extension = GetSafeValueString($_POST['extension'], "string");
    $other1 = GetSafeValueString($_POST['other1'], "string");

    //$addv->alertdays = $_POST['alertdays'];
    //$addv->email = $_POST['email'];
    $addv->driveralert = $_POST['driveralert'];
    $addv->alertdays = isset($_POST['alertdays']) ? $_POST['alertdays'] : "0";
    $addv->email = isset($_POST['email']) ? $_POST['email'] : "0";
    $addv->sms = isset($_POST['sms']) ? $_POST['sms'] : "0";
    $addv->username = GetSafeValueString($_POST['username'], "string");
    if ($_POST['pwd1'] != '' && $_POST['pwd'] == '') {
        $addv->pass = GetSafeValueString($_POST['pwd1'], "string");
    }
    if( $_POST['pwd'] != '')
    {
        $addv->pass = sha1(GetSafeValueString($_POST['pwd'], "string"));
    }

    $addv->mail = GetSafeValueString($_POST['mail'], "string");
    $addv->phno = GetSafeValueString($_POST['phno'], "string");

    if ($other1 == "" && $extension == "") {
        $oldimage = GetSafeValueString($_POST['oldimage'], "string");
        $addv->filename1 = $oldimage;
    } else {
        $addv->filename1 = $other1 . '.' . $extension;
    }
    $response = modifydriver($addv);
    echo $response;

} else if ($action == "deletedriver") {
    $did = $_POST['driverid'];
    $res = deldriver($did);
    echo $res;
}
?>