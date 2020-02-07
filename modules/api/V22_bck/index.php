<?php

//file required
//error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');
set_time_limit(60);
$RELATIVE_PATH_DOTS = "../../../";
require_once "../../../lib/system/utilities.php";
require_once "../../../lib/autoload.php";
require_once "class/class.api.php";

//ob_start("ob_gzhandler");
//ojbect creation
$apiobj = new api();

//TODO: Need to replace all the Superglobals with the below correct usage:
//$data['vehicleno'] = filter_input(INPUT_REQUEST, 'vehicleno');
/*
It is not safe. Someone could do something like this: www.example.com?_SERVER['anything']
or if he has any kind of knowledge he could try to inject something into another variable
 */
extract($_REQUEST);
if ($action == "pullcredentials") {
    if (isset($username) && isset($password)) {
        $test = $apiobj->check_login($username, $password);
    } else {

        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "pullcrm") {
    if (isset($userkey)) {
        $test = $apiobj->pullcrm($userkey);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "pulldetails") {
    if (isset($userkey)) {
        $pageIndex = isset($pageIndex) ? $pageIndex : 1;
        /* If pageSize is -1, it means we need to give all the records in a single page */
        $pageSize = isset($pageSize) ? $pageSize : -1;
        /* If search string is set, it means we need to return all the records matching this string */
        $searchstring = isset($searchstring) ? $searchstring : '';
        $groupid = isset($groupid) ? $groupid : 0;
        $isRequiredThirdParty = isset($isRequiredThirdParty) ? $isRequiredThirdParty : 0;

        $test = $apiobj->device_list($userkey, $pageIndex, $pageSize, $searchstring, $groupid, $isRequiredThirdParty);
        $phone = isset($phone) ? $phone : '';
        $version = isset($version) ? $version : '';

        exit;
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
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
        $test = $apiobj->device_list_wh($userkey, $pageIndex, $pageSize, $searchstring, $groupid);
        $phone = isset($phone) ? $phone : '';
        $version = isset($version) ? $version : '';

        exit;
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "pullvehicledetails") {
    if (isset($userkey) && isset($vehicleid)) {
        $test = $apiobj->device_list_details($userkey, $vehicleid);
        $phone = isset($phone) ? $phone : '';
        $version = isset($version) ? $version : '';
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "whpullvehicledetails") {
    if (isset($userkey)) {
        $test = $apiobj->device_list_details_wh($userkey, $vehicleid);
        $phone = isset($phone) ? $phone : '';
        $version = isset($version) ? $version : '';
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "pushbuzzer") {
    //for buzzer
    if (isset($userkey)) {
        $test = $apiobj->pushbuzzer($userkey, $vehicleid, $status);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "pushmobiliser") {
    //for mobiliser
    if (isset($userkey)) {
        $test = $apiobj->pushmobiliser($userkey, $vehicleid, $status);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "freeze") {
    //for freeze  vehicle location  (fstatus=1 => freeze vehicle // fstatus=0 => Unfreeze)
    if (isset($userkey)) {
        $test = $apiobj->freezevehicle($userkey, $vehicleid, $status);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "pullvehiclebyname") {
    if (isset($userkey)) {
        $test = $apiobj->device_list_byname($userkey, $vehicleno);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "clientcode") {
    if (isset($clientcode)) {
        $searchstring = isset($searchstring) ? $searchstring : '';
        $test = $apiobj->client_code_details($clientcode, $searchstring);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "createclientcode") {
    if (isset($userkey)) {
        $test = $apiobj->create_client_code($userkey, $clientcode);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "registergcm") {
    if (isset($userkey) && isset($regid)) {
        $test = $apiobj->register_gcm($userkey, $regid);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "unregistergcm") {
    if (isset($userkey)) {
        $test = $apiobj->unregister_gcm($userkey);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "summary") {
    if (isset($userkey) && isset($vehicleid)) {
        $date = isset($date) ? $date : date('d-m-Y');
        $test = $apiobj->summary($userkey, $vehicleid, $date);
        echo $test;
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "whsummary") {
    if (isset($userkey) && isset($vehicleid)) {
        $date = isset($date) ? $date : date('d-m-Y');
        $test = $apiobj->summary_wh($userkey, $vehicleid, $date);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "contractinfo") {
    if (isset($userkey)) {
        $test = $apiobj->contractinfo($userkey);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "dashboard") {
    if (isset($userkey)) {
        $test = $apiobj->dashboard($userkey);
        $phone = isset($phone) ? $phone : '';
        $version = isset($version) ? $version : '';
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "get_otp_forgotpwd") {
    if (isset($username)) {
        $result = $apiobj->get_otp_forgotpwd($username);
        echo json_encode($result);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "update_password") {
    if (isset($userkey) && isset($newpwd)) {
        $result = $apiobj->update_password($userkey, $newpwd);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "pullvehiclesdriversusers") {
    if (isset($userkey)) {
        $result = $apiobj->get_vehicles_drivers_users($userkey);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "mapvehicledriveruser") {
    if (isset($userkey) && isset($vehicleid) && isset($userid) && isset($driverid)) {
        if ($driverid == 0 && isset($drivername) && $drivername == '') {
            $arr_p['status'] = "unsuccessful";
            $arr_p['error'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        } else {
            $drivername = isset($drivername) ? $drivername : '';
            $result = $apiobj->map_vehicle_driver_user($userkey, $vehicleid, $userid, $driverid, $drivername);
        }
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "sendsummaryreport") {
    if (isset($userkey) && isset($vehicleid) && isset($date) && isset($toaddresses) && isset($comments) && isset($attachmentType)) {
        //$userkey, $vehicleno, $deviceid, $startDate, $startTime, $endDate, $endTime, $reportInterval, $toaddresses, $comments,
        //$attachmentType, $isWareHouse, $useHumidity

        $result = $apiobj->sendSummaryReport($userkey, $vehicleid, $date, $toaddresses, $comments, $attachmentType);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "whsendsummaryreport") {
    if (isset($userkey) && isset($vehicleno) && isset($deviceid) && isset($startDate) && isset($startTime) && isset($endDate) && isset($endTime) && isset($reportInterval) && isset($toaddresses) && isset($comments) && isset($attachmentType) && isset($isWareHouse) && isset($useHumidity)) {
        $result = $apiobj->sendSummaryReport_wh($userkey, $vehicleno, $deviceid, $startDate, $startTime, $endDate, $endTime, $reportInterval, $toaddresses, $comments, $attachmentType, $isWareHouse, $useHumidity);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "getquicksharetext") {
    if (isset($userkey) && isset($vehicleid)) {
        $result = $apiobj->getquicksharetext($userkey, $vehicleid);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "sendquicksharesms") {
    if (isset($userkey) && isset($quickShareText) && isset($mobilenolist) && isset($vehicleid)) {
        $result = $apiobj->sendquicksharesms($userkey, $vehicleid, $quickShareText, $mobilenolist);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "pullusergroup") {
    if (isset($userkey) && !empty($userkey)) {
        $test = $apiobj->pull_user_group($userkey);
        exit;
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "pullroutehistory") {
    if (isset($userkey) && isset($vehicleid) && isset($sdate) && isset($stime) && isset($edate) && isset($etime) && isset($reporttype) && isset($overspeed) && isset($holdtime)) {
        $validation = $apiobj->check_userkey($userkey);
        if ($validation['status'] == 'successful') {
            $userid = $validation['userid'];
            $customerno = $validation['customerno'];
            $roleid = $validation['roleid'];
            //route details
            $flag = 1;
            $sdatetime = $sdate . $stime;
            $edatetime = $edate . $etime;

            $data = $apiobj->route_histNewRefined_checkpointdetails($vehicleid, $sdatetime, $edatetime, $holdtime, $flag, $validation);
        } else {
            $arr_p['status'] = "unsuccessful";
            $arr_p['message'] = "Userkey not valid.";
            echo json_encode($arr_p);
        }
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "push_notification_status") {
    if (isset($userkey) && isset($notification_status)) {
        $validation = $apiobj->check_userkey($userkey);
        if ($validation['status'] == 'successful') {
            $userid = $validation['userid'];
            $customerno = $validation['customerno'];
            $roleid = $validation['roleid'];
            $data = $apiobj->update_user_notification_status($userid, $notification_status);
        } else {
            $arr_p['status'] = "unsuccessful";
            $arr_p['message'] = "Userkey not valid.";
            echo json_encode($arr_p);
        }
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "pushgcmid") {
    if (isset($userkey) && isset($gcmid)) {
        $validation = $apiobj->check_userkey($userkey);
        if ($validation['status'] == 'successful') {
            $userid = $validation['userid'];
            $customerno = $validation['customerno'];
            $roleid = $validation['roleid'];
            $data = $apiobj->update_gcmid($userid, $gcmid);
        } else {
            $arr_p['status'] = "unsuccessful";
            $arr_p['message'] = "Userkey not valid.";
            echo json_encode($arr_p);
        }
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "pullaccountswitchdetails") {
    $arrResult['status'] = 0;
    $arrResult['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
    $jsonRequest = json_decode($jsonreq);
    if (isset($jsonRequest->userkey) && $jsonRequest->userkey != "" && is_numeric($jsonRequest->userkey)) {
        $arrResult = $apiobj->PullAccountSwitchDetails($jsonRequest);
    }
    echo json_encode($arrResult);
}

if ($action == "getReport") {
    $arrResult['status'] = 0;
    $arrResult['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
    $jsonRequest = json_decode($jsonreq);
    if (isset($jsonRequest->userkey) && $jsonRequest->userkey != "") {
        $arrResult = $apiobj->PullReportDetails($jsonRequest);
    }
    echo json_encode($arrResult);
}

if ($action == "insertchkptdetails") {
    $arrResult['status'] = 0;
    $arrResult['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
    $jsonRequest = json_decode($jsonreq);
    if (isset($jsonRequest->userkey) && $jsonRequest->userkey != "" && is_numeric($jsonRequest->userkey) && isset($jsonRequest->chkPtName) && $jsonRequest->chkPtName != "" && isset($jsonRequest->vehicleIdList) && $jsonRequest->vehicleIdList != "" && isset($jsonRequest->chkPtLat) && $jsonRequest->chkPtLat != "" && $jsonRequest->chkPtLat != 0 && isset($jsonRequest->chkPtLng) && $jsonRequest->chkPtLng != "" && $jsonRequest->chkPtLng != 0 && isset($jsonRequest->chkPtRadius) && $jsonRequest->chkPtRadius != "") {
        $arrResult = $apiobj->InsertChkptDetails($jsonRequest);
    }
    echo json_encode($arrResult);
}

if ($action == "pullalerthistory") {
    $arrResult['status'] = 0;
    $arrResult['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
    $jsonRequest = json_decode($jsonreq);
    if (isset($jsonRequest->userkey) && $jsonRequest->userkey != "" && is_numeric($jsonRequest->userkey)) {
        $arrResult = $apiobj->PullAlertHistory($jsonRequest);
    }
    echo json_encode($arrResult);
}

if ($action == "updateLoginHistory") {
    $arrResult['status'] = 0;
    $arrResult['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
    $jsonRequest = json_decode($jsonreq);
    if (isset($jsonRequest->userkey) && $jsonRequest->userkey != "" && is_numeric($jsonRequest->userkey)) {
        $arrResult = $apiobj->UpdateLoginHistory($jsonRequest);
    }
    echo json_encode($arrResult);
}
if ($action == "pullCheckPoints") {
    $getObjectData = new stdClass();
    $inputData = json_decode($jsonreq, true);

    if (isset($inputData['userkey'])) {
        $getObjectData->userkey = $inputData['userkey'];
//        $getObjectData->simno = $inputData['simno'];
        $getData = $apiobj->getAllCheckpoints($getObjectData);
        if ($getData == "WrongUserkey") {
            $output = $apiobj->failure("Invalid Login");
        } elseif ($getData == NULL) {
            $output = $apiobj->failure("Data Not Available");
        } else {
            $message = "Data fetched successfully";
            $output = $apiobj->success($message, $getData);
        }
    } else {
        $output = $apiobj->failure("Mandatory parameter's value missing. Please resend the request with all parameters.");
    }
    echo json_encode($output);
    return $output;
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

if ($action == "setRestoreId") {
    $arrResult['status'] = 0;
    $arrResult['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;

    $jsonRequest = json_decode($jsonreq);

    if (isset($jsonRequest->userkey) && ($jsonRequest->userkey != "")) {
        //print_r($arrResult);
        $arrResult = $apiobj->setRestoreId($jsonRequest);
    }

    echo json_encode($arrResult);
}

if ($action == "getRestoreId") {
    $arrResult['status'] = 0;
    $arrResult['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;

    $jsonRequest = json_decode($jsonreq);

    if (isset($jsonRequest->userkey) && ($jsonRequest->userkey != "")) {
        $arrResult = $apiobj->getRestoreId($jsonRequest);
    }

    echo json_encode($arrResult);
}

if ($action == "sendEnquiry") {
    $arrResult['status'] = 0;
    $arrResult['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
    $jsonRequest = json_decode($jsonreq);
    //print_r($jsonRequest);
    if (isset($jsonRequest->userkey) && isset($jsonRequest->vehicleid)) {
        $arrResult = $apiobj->sendEnquiry($jsonRequest);
    }
    echo json_encode($arrResult);
}

if ($action == "getTempNonCompReport") {
    try {
        include_once $RELATIVE_PATH_DOTS . 'modules/reports/reports_common_functions.php';
        $reqGetNonComReport = json_decode($jsonreq);

        if (isset($reqGetNonComReport->userkey) && $reqGetNonComReport->userkey != "") {
            $arrVTSResult = $apiobj->get_userdetails_by_key($reqGetNonComReport->userkey);

            if (empty($arrVTSResult)) {
                $output = $apiobj->failure('No Userdata');
                //Log error in DB
            } else {
                $customerno = $arrVTSResult['customerno'];
                
                $userid = $arrVTSResult['userid'];
                $validDays = $apiobj->checkValidity_customer($customerno);

                if ($validDays > 0) {
                    $reqGetNonComReport->customerNo = $customerno;
                    // echo "hello"; echo $reqGetNonComReport->customerNo; die;
                    //$devicemanager = new DeviceManager($_SESSION['customerno']);
                    $devices = $apiobj->devicesformapping_byId($reqGetNonComReport->vehicleNo, $reqGetNonComReport->customerNo);
                    // print("<pre>"); print_r($devices); die;

                    if ($devices) {
                        foreach ($devices as $row) {
                            $deviceid = $row->deviceid;
                            $vehicleId = $row->vehicleid;
                        }
                    }
                    $reqGetNonComReport->deviceid = $deviceid;
                    $reqGetNonComReport->vehicleId = $vehicleId;
                    // print("<pre>"); print_r($reqGetNonComReport); die; 
                    $arrResponse = $apiobj->gettemptabularreport($reqGetNonComReport);
                    //print("<pre>"); print_r($arrResponse); die;

                    $message = "Available Records fetched successfully";
                    $output = $apiobj->success($message, $arrResponse);
                    //Log success response in DB
                } else {
                    $output = $apiobj->failure("Device Validity expired.");
                    //Log error in DB
                }
            }
        } else {
            $output = $apiobj->failure("Mandatory parameter's value missing. Please resend the request with all parameters.");
            //Log error in DB
        }
    } catch (JsonMapper_Exception $jmEx) {
        $output = $apiobj->failure("JSON request mapping failed. Please resend the request with all required parameters.");
        //Log error in DB
    } catch (Exception $ex) {
        $output = $apiobj->failure("Whoops!!  Looks like there is an unknown exception");
        //Log error in DB
    }

    if (!isset($output)) {
        $output = $apiobj->failure("Seems like you are playing around without any actions !!");
    }
        echo json_encode($output,JSON_FORCE_OBJECT);
}
?>
