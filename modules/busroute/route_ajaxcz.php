<?php
include 'checkpoint_functions.php';

if (isset($_REQUEST['chkN'])) {
    check_chk_name($_REQUEST['chkN']);
} elseif (isset($_REQUEST['d'])) {
    chk_eligibility($_REQUEST['d']);
} elseif (isset($_REQUEST['d']) && isset($_REQUEST['ds'])) {
    mapchk($_REQUEST['d'], $_REQUEST['ds']);
} elseif (isset($_REQUEST['ds'])) {
    demapchk($_REQUEST['ds']);
} elseif (isset($_REQUEST['chk']) && $_REQUEST['chk'] == 'all') {
    chkformapping();
}elseif (isset($_REQUEST['chkException']) && ($_REQUEST['exceptionType'] == 1 || $_REQUEST['exceptionType'] == 2)){
    $details = $_REQUEST;
    $vehicles = array();
    $checkpoints = array();
    $objException = new stdClass();
    $objException->startTime = $details['startTime'];
    $objException->endTime = $details['endTime'];
    $objException->exceptionName = $details['exceptionName'];
    $objException->exception = $details['exceptionType'];
    foreach ($details as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 11) == "to_vehicle_") {
            $vehicles[] = substr($single_post_name, 11, 10);
        }
    }
    foreach ($details as $single_post_name => $single_post_value) {
        if (substr($single_post_name, 0, 14) == "to_checkpoint_") {
            $checkpoints[] = substr($single_post_name, 14, 10);
        }
    }
    $objException->vehicles = implode(",", $vehicles);
    $objException->checkpoints = implode(",", $checkpoints);
    $objException->customerno = $_SESSION['customerno'];
    $objException->userid = $_SESSION['userid'];
    $objException->todaysdate = date('Y-m-d h:m:i');
    $status = insertException($objException);
    echo $status;
} elseif ($_REQUEST['checkpointid']) {
    view_checkpoint_by_id($_REQUEST['checkpointid']);
} elseif (isset($_REQUEST['chkid']) && isset($_REQUEST['chkname']) && isset($_REQUEST['chkrad'])) {
    $chkid = GetSafeValueString($_REQUEST['chkid'], "string");
    $chkname = GetSafeValueString($_REQUEST['chkname'], "string");
    $chkrad = GetSafeValueString($_REQUEST['chkrad'], "string");
    editchk($chkid, $chkname, $chkrad);
} elseif (isset($_REQUEST['chkid']) && isset($_REQUEST['vehicleid'])) {
    $vehicleid = GetSafeValueString($_REQUEST['vehicleid'], "string");
    $chkid = GetSafeValueString($_REQUEST['chkid'], "string");
    DelChkByVehicleid($chkid, $vehicleid);
}
?>
