<?php

session_start();
include 'rtd_functions.php';
include_once '../../lib/comman_function/reports_func.php';

if (isset($_REQUEST['vehicleid'])) {
    get_vehicledesc_by_vehicleid($_REQUEST['vehicleid']);
}

if (isset($_REQUEST['driverno'])) {
    $cm = new CustomerManager();
    $smsStatus = new SmsStatus();

    $flag = 0;
    $userphone = $_REQUEST['driverno'];
    $smsText = $_REQUEST['driversms'];
    $vehicleid = $_REQUEST['vehicleid1'];
    $useridparam = isset($_SESSION['userid']) ? $_SESSION['userid'] : '';
    $moduleid = 3;
    $todaysdate = date('Y-m-d H:i:s');
    $customerno = $_SESSION['customerno'];
    $smsStatus->customerno = $customerno;
    $smsStatus->userid = $useridparam;
    $smsStatus->vehicleid = $vehicleid;
    $smsStatus->mobileno = $userphone;
    $smsStatus->message = $smsText;
    $smsStatus->cqid = 0;
    $smsStat = $cm->getSMSStatus($smsStatus);
    if ($smsStat == 0) {
        $response = '';
        $isSMSSent = sendSMSUtil($userphone, $smsText, $response);
        if ($isSMSSent == 1) {
            $cm->sentSmsPostProcess($customerno, $userphone, $smsText, $response, $isSMSSent, $useridparam, $vehicleid, 1);
        }
    }
    echo $smsStat;
} else if (isset($_REQUEST['refreshTime'])) {
    $refreshTime = GetSafeValueString($_REQUEST['refreshTime'], "string");
    updateRefreshTime($refreshTime);
    $_SESSION['refreshtime'] = $refreshTime;
} else if (isset($_REQUEST['history_vehicleid'])) {
    $rowid = GetSafeValueString($_REQUEST['history_vehicleid'], "string");
    $vm = new VehicleManager(2);
    $historyData = $vm->rtdVehicleHistory($rowid);
    echo json_encode($historyData);
}
?>
