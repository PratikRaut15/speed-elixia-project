<?php

//file required
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');
$RELATIVE_PATH_DOTS = "../../../";
require_once "../../../lib/system/utilities.php";
require_once "../../../lib/autoload.php";
require_once("class/class.api.php");
require_once("class/class.sqlite.php");
require_once("class/class.geo.coder.php");
//ob_start("ob_gzhandler");
//ojbect creation
$apiobj = new api();
extract($_REQUEST);

if ($action == "pullcredentials") {
    if (isset($username) && isset($password)) {
        $test = $apiobj->check_login($username, $password);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "pullcrm") {
    if (isset($userkey)) {
        $test = $apiobj->pullcrm($userkey);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "whpulldetails") {
    if (isset($userkey)) {
        $pageIndex = isset($pageIndex) ? $pageIndex : 1;
        /* If pageSize is -1, it means we need to give all the records in a single page */
        $pageSize = isset($pageSize) ? $pageSize : -1;
        /* If search string is set, it means we need to return all the records matching this string */
        $searchstring = isset($searchstring) ? $searchstring : '';
        $groupid = isset($groupid) ? $groupid : 0;
        $isRequiredThirdParty = isset($isRequiredThirdParty) ? $isRequiredThirdParty : 0;
        $test = $apiobj->device_list_wh($userkey, $pageIndex, $pageSize, $searchstring, $groupid, $isRequiredThirdParty);
        $phone = isset($phone) ? $phone : '';
        $version = isset($version) ? $version : '';
        $apiobj->updateLogin($userkey, $phone, $version);
        exit;
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "whpullvehicledetails") {
    if (isset($userkey)) {
        $test = $apiobj->device_list_details_wh($userkey, $vehicleid);
        $phone = isset($phone) ? $phone : '';
        $version = isset($version) ? $version : '';
        $apiobj->updateLogin($userkey, $phone, $version);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "pushbuzzer") {  //for buzzer
    if (isset($userkey)) {
        $test = $apiobj->pushbuzzer($userkey, $vehicleid, $status);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "pushmobiliser") {  //for mobiliser
    if (isset($userkey)) {
        $test = $apiobj->pushmobiliser($userkey, $vehicleid, $status);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "registergcm") {
    if (isset($userkey) && isset($regid)) {
        $test = $apiobj->register_gcm($userkey, $regid);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "unregistergcm") {
    if (isset($userkey)) {
        $test = $apiobj->unregister_gcm($userkey);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "whsummary") {
    if (isset($userkey) && isset($vehicleid)) {
        $date = isset($date) ? $date : date('d-m-Y');
        $test = $apiobj->summary_wh($userkey, $vehicleid, $date);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "contractinfo") {
    if (isset($userkey)) {
        $test = $apiobj->contractinfo($userkey);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "get_otp_forgotpwd") {
    if (isset($username)) {
        $result = $apiobj->get_otp_forgotpwd($username);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "update_password") {
    if (isset($userkey) && isset($newpwd)) {
        $result = $apiobj->update_password($userkey, $newpwd);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "whsendsummaryreport") {
    if (isset($userkey) && isset($vehicleno) && isset($deviceid) && isset($startDate) && isset($startTime) && isset($endDate) && isset($endTime) && isset($reportInterval) && isset($toaddresses) && isset($comments) && isset($attachmentType) && isset($isWareHouse) && isset($useHumidity)) {
        $result = $apiobj->sendSummaryReport_wh($userkey, $vehicleno, $deviceid, $startDate, $startTime, $endDate, $endTime, $reportInterval, $toaddresses, $comments, $attachmentType, $isWareHouse, $useHumidity);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "pullusergroup") {
    if (isset($userkey)&& !empty($userkey)) {
        $test = $apiobj->pull_user_group($userkey);
        exit;
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "clientcode") {
    if (isset($clientcode)) {
        $test = $apiobj->client_code_details($clientcode);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "validateLoginOtp") {
    $arrResult['status'] = 0;
    $arrResult['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
    $jsonRequest = json_decode($jsonreq);
    if (isset($jsonRequest->userkey) && $jsonRequest->userkey != "" && is_numeric($jsonRequest->userkey) && isset($jsonRequest->otp) && $jsonRequest->otp != "" && is_numeric($jsonRequest->otp)) {
        $arrResult = $apiobj->validateOtpForAuthentication($jsonRequest);
    }
    echo json_encode($arrResult);
}
?>
