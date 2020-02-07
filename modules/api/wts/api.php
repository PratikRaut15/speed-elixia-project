<?php
//file required
//error_reporting(E_ALL ^E_STRICT);
//ini_set('display_errors', 'on');
$RELATIVE_PATH_DOTS = "../../../";
require_once "../../../lib/system/utilities.php";
require_once "../../../lib/autoload.php";
require_once "../../../lib/system/Sanitise.php";
require_once "class/class.vtsapi.php";
require_once "class/model/RequestClass.php";
require_once "class/model/RequestFreezeClass.php";
require_once "class/model/RequestImmobilizerClass.php";
require_once "class/model/RequestLocationReportClass.php";
require_once "class/model/RequestBuzzerClass.php";
require_once "class/model/ResponseClass.php";
require_once "class/model/ResponseFreezeClass.php";
require_once "class/model/ResponseImmobilizerClass.php";
require_once "class/model/ResponseBuzzerClass.php";
require_once "class/model/ResponseLocationVehicleClass.php";
require_once "class/model/clsChkPtStatusRequestResponse.php";
require_once "class/model/clsVehSummaryRequest.php";
require_once "class/model/RequestPushVehicleData.php";
require_once "class/model/ResponsePushVehicleData.php";
require_once "class/model/ChkPtReportRequest.php";
require_once "class/model/ChkPtReportResponse.php";
require_once "../../../vendor/autoload.php";
require_once "class/model/clsNonCompTempRequest.php";
$action = "";
$apiobj = new api();
$output = null;
$objJsonMapper = new JsonMapper();
$objJsonMapper->bExceptionOnUndefinedProperty = true;
$objJsonMapper->bExceptionOnMissingData = true;
$objJsonMapper->bEnforceMapType = false;
extract($_REQUEST);
if ($action == "getVehicleList") {
    try {
        $objGetVehicleList = json_decode($jsonreq);
        if (isset($objGetVehicleList->userkey) && $objGetVehicleList->userkey != "") {
            $arrVTSResult = $apiobj->get_userdetails_by_key($objGetVehicleList->userkey);
            if (empty($arrVTSResult)) {
                $output = $apiobj->failure('No Userdata');
                //Log error in DB
            } else {
                $customerno = $arrVTSResult['customerno'];
                $userid = $arrVTSResult['userid'];
                $validDays = $apiobj->checkValidity($customerno);
                if ($validDays > 0) {
                    $objGetVehicleList->customerNo = $customerno;
                    $objGetVehicleList->userId = $userid;
                    $arrResponse = $apiobj->getVehicleList($objGetVehicleList);
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
    } catch (Exception $ex) {
        $output = $apiobj->failure("Whoops!!  Looks like there is an unknown exception");
        //Log error in DB
    }
}
if ($action == "getWarehouseData") {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    try {
        $reqGetLatestVehDetails = json_decode($jsonreq, true);
        //print_r($jsonreq);
        //Log input request in DB
        $objGetLatestVehicleRequest = $objJsonMapper->map($reqGetLatestVehDetails, new RequestClass());
        //print_r($objGetLatestVehicleRequest);
        if (isset($objGetLatestVehicleRequest->userkey) && $objGetLatestVehicleRequest->userkey != "") {
            $objGetLatestVehicleRequest->pageindex = isset($objGetLatestVehicleRequest->pageindex) ? $objGetLatestVehicleRequest->pageindex : 1;
            /* If pagesize is -1, it means we need to give all the records in a single page */
            $objGetLatestVehicleRequest->pagesize = isset($objGetLatestVehicleRequest->pagesize) ? $objGetLatestVehicleRequest->pagesize : -1;
            /* If search string is set, it means we need to return all the records matching this string */
            $objGetLatestVehicleRequest->searchstring = isset($objGetLatestVehicleRequest->searchstring) ? $objGetLatestVehicleRequest->searchstring : '';
            $objGetLatestVehicleRequest->groupid = isset($objGetLatestVehicleRequest->groupid) ? $objGetLatestVehicleRequest->groupid : 0;
            $objGetLatestVehicleRequest->iswarehouse = isset($objGetLatestVehicleRequest->iswarehouse) ? $objGetLatestVehicleRequest->iswarehouse : 1;
            $objGetLatestVehicleRequest->isRequiredThirdParty = isset($objGetLatestVehicleRequest->isRequiredThirdParty) ? $objGetLatestVehicleRequest->isRequiredThirdParty : 0;
            $arrVTSResult = $apiobj->get_userdetails_by_key($objGetLatestVehicleRequest->userkey);
            if (empty($arrVTSResult)) {
                $output = $apiobj->failure('No Userdata');
                //Log error in DB
            } else {
                $customerno = $arrVTSResult['customerno'];
                $validDays = $apiobj->checkValidity($customerno);
                if ($validDays > 0) {
                    $objGetLatestVehicleRequest->customerno = $customerno;
                    $getdata = $apiobj->getlatestvehicledata($objGetLatestVehicleRequest);
                    $message = "Available Records fetched successfully";
                    $output = $apiobj->success($message, $getdata);
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
}
if ($action == "pushvehicledata") {
    try {
        $jsonreq = json_decode($jsonreq, true);
        $today = date('Y-m-d H:i:s');
        $objPushVehicleDataRequest = $objJsonMapper->map($jsonreq, new RequestPushVehicleData());
        if (isset($objPushVehicleDataRequest->userkey) && $objPushVehicleDataRequest->userkey != "") {
            $objPushVehicleDataRequest->vehicleNo = isset($objPushVehicleDataRequest->vehicleNo) ? $objPushVehicleDataRequest->vehicleNo : "";
            $objPushVehicleDataRequest->unitNo = isset($objPushVehicleDataRequest->unitNo) ? $objPushVehicleDataRequest->unitNo : "";
            $objPushVehicleDataRequest->latitude = isset($objPushVehicleDataRequest->latitude) ? $objPushVehicleDataRequest->latitude : "0";
            $objPushVehicleDataRequest->longitude = isset($objPushVehicleDataRequest->longitude) ? $objPushVehicleDataRequest->longitude : "0";
            $objPushVehicleDataRequest->altitude = isset($objPushVehicleDataRequest->altitude) ? $objPushVehicleDataRequest->altitude : "0";
            $objPushVehicleDataRequest->direction = isset($objPushVehicleDataRequest->direction) ? $objPushVehicleDataRequest->direction : "0";
            $objPushVehicleDataRequest->vehicleBattery = isset($objPushVehicleDataRequest->vehicleBattery) ? $objPushVehicleDataRequest->vehicleBattery : "0";
            $objPushVehicleDataRequest->ignition = isset($objPushVehicleDataRequest->ignition) ? $objPushVehicleDataRequest->ignition : "0";
            $objPushVehicleDataRequest->gsmStrength = isset($objPushVehicleDataRequest->gsmStrength) ? $objPushVehicleDataRequest->gsmStrength : "0";
            $objPushVehicleDataRequest->odometer = isset($objPushVehicleDataRequest->odometer) ? $objPushVehicleDataRequest->odometer : "0";
            $objPushVehicleDataRequest->speed = isset($objPushVehicleDataRequest->speed) ? $objPushVehicleDataRequest->speed : "0";
            $objPushVehicleDataRequest->temperature1 = isset($objPushVehicleDataRequest->temperature1) ? ($objPushVehicleDataRequest->temperature1 * 100) : "";
            $objPushVehicleDataRequest->temperature2 = isset($objPushVehicleDataRequest->temperature2) ? ($objPushVehicleDataRequest->temperature2 * 100) : "";
            $objPushVehicleDataRequest->temperature3 = isset($objPushVehicleDataRequest->temperature3) ? ($objPushVehicleDataRequest->temperature3 * 100) : "";
            $objPushVehicleDataRequest->temperature4 = isset($objPushVehicleDataRequest->temperature4) ? ($objPushVehicleDataRequest->temperature4 * 100) : "";
            $objPushVehicleDataRequest->digitalIO = isset($objPushVehicleDataRequest->digitalIO) ? $objPushVehicleDataRequest->digitalIO : "0";
            $objPushVehicleDataRequest->driverName = isset($objPushVehicleDataRequest->driverName) ? $objPushVehicleDataRequest->driverName : "";
            $objPushVehicleDataRequest->isOnline = isset($objPushVehicleDataRequest->isOnline) ? $objPushVehicleDataRequest->isOnline : "";
            $objPushVehicleDataRequest->lastUpdated = isset($objPushVehicleDataRequest->lastUpdated) ? $objPushVehicleDataRequest->lastUpdated : $today;
            $dt = DateTime::createFromFormat("Y-m-d H:i:s", $objPushVehicleDataRequest->lastUpdated);
            if ($dt !== false && !array_sum($dt->getLastErrors())) {
                $arrVTSResult = $apiobj->get_userdetails_by_key($objPushVehicleDataRequest->userkey);
                if (empty($arrVTSResult)) {
                    $output = $apiobj->failure('No Userdata');
                    //Log error in DB
                } else {
                    $customerno = $arrVTSResult['customerno'];
                    $objPushVehicleDataRequest->customerno = $customerno;
                    $result[] = $apiobj->pushvehicledata($objPushVehicleDataRequest);
                    if ($result == false) {
                        $output = $apiobj->failure("Mandatory parameter's value missing. Please resend the request with all parameters.");
                    } else {
                        $message = "Details updated successfully";
                        $output = $apiobj->success($message, $result);
                    }
                }
            } else {
                $output = $apiobj->failure("Date format incorrect. Correct date format: yyyy-mm-dd HH:MM:SS");
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
}
if ($action == "getalerthist") {
    try {
        $reqGetLatestVehDetails = json_decode($jsonreq, true);
        $objGetLatestVehicleRequest = $objJsonMapper->map($reqGetLatestVehDetails, new RequestClass());
        if (isset($objGetLatestVehicleRequest->userkey) && $objGetLatestVehicleRequest->userkey != "") {
            $objGetLatestVehicleRequest->vehicleno = $objGetLatestVehicleRequest->vehicleno;
            $arrVTSResult = $apiobj->get_userdetails_by_key($objGetLatestVehicleRequest->userkey);
            if (empty($arrVTSResult)) {
                $output = $apiobj->failure('No Userdata');
                //Log error in DB
            } else {
                $customerno = $arrVTSResult['customerno'];
                $validDays = $apiobj->checkValidity($customerno);
                if ($validDays > 0) {
                    $objGetLatestVehicleRequest->customerno = $customerno;
                    $getdata = $apiobj->getalerthistory($objGetLatestVehicleRequest);
                    $message = "Available Records fetched successfully";
                    $output = $apiobj->success($message, $getdata);
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
}
if ($action == "freezevehicle") {
    try {
        $reqGetFreezeVehDetails = json_decode($jsonreq, true);
        $objGetFreezeVehicleRequest = $objJsonMapper->map($reqGetFreezeVehDetails, new RequestFreezeClass());
        if (isset($objGetFreezeVehicleRequest->userkey) && $objGetFreezeVehicleRequest->userkey != "") {
            $arrVTSResult = $apiobj->get_userdetails_by_key($objGetFreezeVehicleRequest->userkey);
            if (empty($arrVTSResult)) {
                $output = $apiobj->failure('No Userdata');
                //Log error in DB
            } else {
                $customerno = $arrVTSResult['customerno'];
                $userid = $arrVTSResult['userid'];
                $validDays = $apiobj->checkValidity($customerno);
                if ($validDays > 0) {
                    $objGetFreezeVehicleRequest->customerno = $customerno;
                    $objGetFreezeVehicleRequest->userid = $userid;
                    $getdata = $apiobj->freezevehicledata($objGetFreezeVehicleRequest);
                    if (isset($getdata['result']->vehicleno) && $getdata['result']->vehicleno != '') {
                        $message = $getdata['message'];
                        $getdata1 = $getdata['result'];
                        $output = $apiobj->success($message, $getdata1);
                    } else {
                        $message = $getdata['message'];
                        $getdata1 = $getdata['result'];
                        $output = $apiobj->failure($message, $getdata1);
                    }
                    //Log success response in DB
                } else {
                    $output = $apiobj->failure("Whoops!! Vehicle validity is expired.");
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
}
if ($action == "immobilizevehicle") {
    try {
        $reqGetImobVehDetails = json_decode($jsonreq, true);
        $objGetImobVehicleRequest = $objJsonMapper->map($reqGetImobVehDetails, new RequestImmobilizerClass());
        if (isset($objGetImobVehicleRequest->userkey) && $objGetImobVehicleRequest->userkey != "") {
            $arrVTSResult = $apiobj->get_userdetails_by_key($objGetImobVehicleRequest->userkey);
            if (empty($arrVTSResult)) {
                $output = $apiobj->failure('No Userdata');
                //Log error in DB
            } else {
                $customerno = $arrVTSResult['customerno'];
                $userid = $arrVTSResult['userid'];
                $validDays = $apiobj->checkValidity($customerno);
                if ($validDays > 0) {
                    $objGetImobVehicleRequest->customerno = $customerno;
                    $objGetImobVehicleRequest->userid = $userid;
                    $getdata = $apiobj->immobilizevehicle($objGetImobVehicleRequest);
                    $message = $getdata['message'];
                    $getdata1 = $getdata['result'];
                    $output = $apiobj->success($message, $getdata1);
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
}
if ($action == "buzzvehicle") {
    try {
        $reqBuzzerVehDetails = json_decode($jsonreq, true);
        $objBuzzerVehicleRequest = $objJsonMapper->map($reqBuzzerVehDetails, new RequestBuzzerClass());
        if (isset($objBuzzerVehicleRequest->userkey) && $objBuzzerVehicleRequest->userkey != "") {
            $arrVTSResult = $apiobj->get_userdetails_by_key($objBuzzerVehicleRequest->userkey);
            if (empty($arrVTSResult)) {
                $output = $apiobj->failure('No Userdata');
                //Log error in DB
            } else {
                $customerno = $arrVTSResult['customerno'];
                $userid = $arrVTSResult['userid'];
                $validDays = $apiobj->checkValidity($customerno);
                if ($validDays > 0) {
                    $objBuzzerVehicleRequest->customerno = $customerno;
                    $objBuzzerVehicleRequest->userid = $userid;
                    $getdata = $apiobj->pushbuzzerdata($objBuzzerVehicleRequest);
                    $message = $getdata['message'];
                    $getdata1 = $getdata['result'];
                    $output = $apiobj->success($message, $getdata1);
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
}
if ($action == "getquicksharetext") {
    try {
        $reqGetLatestVehDetails = json_decode($jsonreq, true);
        $objGetLatestVehicleRequest = $objJsonMapper->map($reqGetLatestVehDetails, new RequestClass());
        if (isset($objGetLatestVehicleRequest->userkey) && $objGetLatestVehicleRequest->userkey != "") {
            $arrVTSResult = $apiobj->get_userdetails_by_key($objGetLatestVehicleRequest->userkey);
            if (empty($arrVTSResult)) {
                $output = $apiobj->failure('No Userdata');
                //Log error in DB
            } else {
                $customerno = $arrVTSResult['customerno'];
                $userid = $arrVTSResult['userid'];
                $realname = $arrVTSResult['realname'];
                $validDays = $apiobj->checkValidity($customerno);
                if ($validDays > 0) {
                    $objGetLatestVehicleRequest->customerno = $customerno;
                    $objGetLatestVehicleRequest->userid = $userid;
                    $objGetLatestVehicleRequest->realname = $realname;
                    $output = $apiobj->getquicksharetextapi($objGetLatestVehicleRequest);
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
}
if ($action == "getlocationhistory") {
    try {
        $reqGetLocationVehDetails = json_decode($jsonreq, true);
        $objGetLocationVehicleRequest = $objJsonMapper->map($reqGetLocationVehDetails, new RequestLocationReportClass());
        if (isset($objGetLocationVehicleRequest->userkey) && $objGetLocationVehicleRequest->userkey != "") {
            $arrVTSResult = $apiobj->get_userdetails_by_key($objGetLocationVehicleRequest->userkey);
            if (empty($arrVTSResult)) {
                $output = $apiobj->failure('No Userdata');
                //Log error in DB
            } else {
                $customerno = $arrVTSResult['customerno'];
                $userid = $arrVTSResult['userid'];
                $validDays = $apiobj->checkValidity($customerno);
                if ($validDays > 0) {
                    $objGetLocationVehicleRequest->customerno = $customerno;
                    $objGetLocationVehicleRequest->userid = $userid;
                    $getdata = $apiobj->getlocationreport($objGetLocationVehicleRequest);
                    $message = $getdata['message'];
                    $status = $getdata['status'];
                    $getdata1 = $getdata['result'];
                    if ($status == 0) {
                        $output = $apiobj->failure($message);
                    } else {
                        $output = $apiobj->success($message, $getdata1);
                    }
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
}
if ($action == "getcheckpointstatus") {
    try {
        $reqGetChkPtStatus = json_decode($jsonreq, true);
        $objChkPtStatus = $objJsonMapper->map($reqGetChkPtStatus, new clsChkPtStatusRequestResponse());
        if (isset($objChkPtStatus->userkey) && $objChkPtStatus->userkey != "") {
            $arrVTSResult = $apiobj->get_userdetails_by_key($objChkPtStatus->userkey);
            if (empty($arrVTSResult)) {
                $output = $apiobj->failure('No Userdata');
                //Log error in DB
            } else {
                $customerno = $arrVTSResult['customerno'];
                $userid = $arrVTSResult['userid'];
                $validDays = $apiobj->checkValidity($customerno);
                if ($validDays > 0) {
                    $objChkPtStatus->customerNo = $customerno;
                    $getdata = $apiobj->getcheckpointstatus($objChkPtStatus);
                    $message = "Available Records fetched successfully";
                    $output = $apiobj->success($message, $getdata);
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
}
if ($action == "getHistoricVehSummary") {
    set_time_limit(90);
    include_once $RELATIVE_PATH_DOTS . 'modules/reports/reports_common_functions.php';
    $reqGetHistoricVehSummary = json_decode($jsonreq, true);
    $objVehSummaryReq = $objJsonMapper->map($reqGetHistoricVehSummary, new clsVehSummaryRequest());
    if (isset($objVehSummaryReq->userkey) && $objVehSummaryReq->userkey != "") {
        $arrVTSResult = $apiobj->get_userdetails_by_key($objVehSummaryReq->userkey);
        if (empty($arrVTSResult)) {
            $output = $apiobj->failure('No Userdata');
            //Log error in DB
        } else {
            $customerno = $arrVTSResult['customerno'];
            $userid = $arrVTSResult['userid'];
            $validDays = $apiobj->checkValidity($customerno);
            if ($validDays > 0) {
                $objVehSummaryReq->customerNo = $customerno;
                $getdata = $apiobj->getHistoricVehSummary($objVehSummaryReq);
                if (isset($getdata->days)) {
                    $output = $apiobj->failure("Trip extending more than stipulated days.");
                } elseif ($getdata->vehicleId > 0) {
                    $message = "Available Records fetched successfully";
                    $output = $apiobj->success($message, $getdata);
                } else {
                    $output = $apiobj->failure("Data not available", $getdata);
                }
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
}
if ($action == "getCheckpointReport") {
    try {
        include_once $RELATIVE_PATH_DOTS . 'modules/reports/reports_chk_functions.php';
        $reqGetChkPtReport = json_decode($jsonreq, true);
        $objChkPtReport = $objJsonMapper->map($reqGetChkPtReport, new ChkPtReportRequest());
        if (isset($objChkPtReport->userkey) && $objChkPtReport->userkey != "") {
            $arrVTSResult = $apiobj->get_userdetails_by_key($objChkPtReport->userkey);
            if (empty($arrVTSResult)) {
                $output = $apiobj->failure('No Userdata');
                //Log error in DB
            } else {
                $customerno = $arrVTSResult['customerno'];
                $userid = $arrVTSResult['userid'];
                $validDays = $apiobj->checkValidity($customerno);
                if ($validDays > 0) {
                    $objChkPtReport->customerNo = $customerno;
                    $arrResponse = $apiobj->getCheckpointReport($objChkPtReport);
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
}
if ($action == "getCheckpointList") {
    try {
        $objGetCheckpointList = json_decode($jsonreq);
        if (isset($objGetCheckpointList->userkey) && $objGetCheckpointList->userkey != "") {
            $arrVTSResult = $apiobj->get_userdetails_by_key($objGetCheckpointList->userkey);
            if (empty($arrVTSResult)) {
                $output = $apiobj->failure('No Userdata');
                //Log error in DB
            } else {
                $customerno = $arrVTSResult['customerno'];
                $userid = $arrVTSResult['userid'];
                $validDays = $apiobj->checkValidity($customerno);
                if ($validDays > 0) {
                    $objGetCheckpointList->customerNo = $customerno;
                    $objGetCheckpointList->userId = $userid;
                    $arrResponse = $apiobj->getCheckpointList($objGetCheckpointList);
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
    } catch (Exception $ex) {
        $output = $apiobj->failure("Whoops!!  Looks like there is an unknown exception");
        //Log error in DB
    }
}
if ($action == "getTravelHistory") {
    try {
        include_once $RELATIVE_PATH_DOTS . 'modules/reports/reports_travel_functions.php';
        $getTravelHistory = json_decode($jsonreq);
        if (isset($getTravelHistory->userkey) && $getTravelHistory->userkey != "") {
            $arrVTSResult = $apiobj->get_userdetails_by_key($getTravelHistory->userkey);
            if (empty($arrVTSResult)) {
                $output = $apiobj->failure('No Userdata');
                //Log error in DB
            } else {
                $customerno = $arrVTSResult['customerno'];
                $userid = $arrVTSResult['userid'];
                //var_dump($userid); die;
                $validDays = $apiobj->checkValidity($customerno);
                //print("<pre>"); print_r($validDays); die;
                if ($validDays > 0) {
                    $getTravelHistory->customerNo = $customerno;
                    $getTravelHistory->userId = $userid;
                    $arrResponse = $apiobj->getTravelHistoryReport($getTravelHistory);
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
    } catch (Exception $ex) {
        $output = $apiobj->failure("Whoops!!  Looks like there is an unknown exception");
        //Log error in DB
    }
}
if ($action == "getTempNonCompReport") {
    try {
        include_once $RELATIVE_PATH_DOTS . 'modules/reports/reports_common_functions.php';
        $reqGetNonComReport = json_decode($jsonreq, true);
        $reqGetNonComReport = $objJsonMapper->map($reqGetNonComReport, new clsNonCompTempRequest());
        // print_r($reqGetNonComReport); die;
        if (isset($reqGetNonComReport->userkey) && $reqGetNonComReport->userkey != "") {
            $arrVTSResult = $apiobj->get_userdetails_by_key($reqGetNonComReport->userkey);
            if (empty($arrVTSResult)) {
                $output = $apiobj->failure('No Userdata');
                //Log error in DB
            } else {
                $customerno = $arrVTSResult['customerno'];
                $userid = $arrVTSResult['userid'];
                $validDays = $apiobj->checkValidity($customerno);
                if ($validDays > 0) {
                    $reqGetNonComReport->customerNo = $customerno;
                    // echo "hello"; echo $reqGetNonComReport->customerNo; die;
                    //$devicemanager = new DeviceManager($_SESSION['customerno']);
                    $devices = $apiobj->devicesformapping_byId($reqGetNonComReport->vehicleNo, $reqGetNonComReport->customerNo);
                    //print("<pre>"); print_r($devices); die;
                    if ($devices) {
                        foreach ($devices as $row) {
                            $deviceid = $row->deviceid;
                            $vehicleId = $row->vehicleid;
                        }
                    }
                    $reqGetNonComReport->deviceid = $deviceid;
                    $reqGetNonComReport->vehicleId = $vehicleId;
                    // print("<pre>"); print_r($reqGetNonComReport); die;
                    $arrResponse = $apiobj->gettemptabularreport_nestle($reqGetNonComReport);
                    // print("<pre>"); print_r($arrResponse); die;
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
}
if ($action == "updateMdlzDumpShipmentno") {
    include_once $RELATIVE_PATH_DOTS . 'modules/reports/reports_common_functions.php';
    $objVehSummaryReq = (object) json_decode($jsonreq, true);
    if (isset($objVehSummaryReq->userkey) && $objVehSummaryReq->userkey != "") {
        $arrVTSResult = $apiobj->get_userdetails_by_key($objVehSummaryReq->userkey);
        if (empty($arrVTSResult)) {
            $output = $apiobj->failure('No Userdata');
            //Log error in DB
        } else {
            $customerno = $arrVTSResult['customerno'];
            $userid = $arrVTSResult['userid'];
            $objVehSummaryReq->customerNo = $customerno;
            $getdata = $apiobj->updateMdlzDumpShipmentno($objVehSummaryReq);
            if ($getdata->vehicleId > 0) {
                $message = "Available Records fetched successfully";
                $output = $apiobj->success($message, $getdata);
            } else {
                $output = $apiobj->failure("Data not available", $getdata);
            }
        }
    } else {
        $output = $apiobj->failure("Mandatory parameter's value missing. Please resend the request with all parameters.");
    }
}
if ($action == "pushbulkvehicledata") {
    try {
        $jsonreq = json_decode($jsonreq, true);
        $today = date('Y-m-d H:i:s');
        /*       echo "<pre>";
        print_r($jsonreq);
        exit;*/
        foreach ($jsonreq['vehicledata'] as $key => $val) {
            $val['userkey'] = $jsonreq['userkey'];
            $objPushVehicleDataRequest = $objJsonMapper->map($val, new RequestPushVehicleData());
            if (isset($objPushVehicleDataRequest->userkey) && $objPushVehicleDataRequest->userkey != "") {
                $objPushVehicleDataRequest->vehicleNo = isset($objPushVehicleDataRequest->vehicleNo) ? $objPushVehicleDataRequest->vehicleNo : "";
                $objPushVehicleDataRequest->unitNo = isset($objPushVehicleDataRequest->unitNo) ? $objPushVehicleDataRequest->unitNo : "";
                $objPushVehicleDataRequest->latitude = isset($objPushVehicleDataRequest->latitude) ? $objPushVehicleDataRequest->latitude : "0";
                $objPushVehicleDataRequest->longitude = isset($objPushVehicleDataRequest->longitude) ? $objPushVehicleDataRequest->longitude : "0";
                $objPushVehicleDataRequest->altitude = isset($objPushVehicleDataRequest->altitude) ? $objPushVehicleDataRequest->altitude : "0";
                $objPushVehicleDataRequest->direction = isset($objPushVehicleDataRequest->direction) ? $objPushVehicleDataRequest->direction : "0";
                $objPushVehicleDataRequest->vehicleBattery = isset($objPushVehicleDataRequest->vehicleBattery) ? $objPushVehicleDataRequest->vehicleBattery : "0";
                $objPushVehicleDataRequest->ignition = isset($objPushVehicleDataRequest->ignition) ? $objPushVehicleDataRequest->ignition : "0";
                $objPushVehicleDataRequest->gsmStrength = isset($objPushVehicleDataRequest->gsmStrength) ? $objPushVehicleDataRequest->gsmStrength : "0";
                $objPushVehicleDataRequest->odometer = isset($objPushVehicleDataRequest->odometer) ? $objPushVehicleDataRequest->odometer : "0";
                $objPushVehicleDataRequest->speed = isset($objPushVehicleDataRequest->speed) ? $objPushVehicleDataRequest->speed : "0";
                $objPushVehicleDataRequest->temperature1 = isset($objPushVehicleDataRequest->temperature1) ? ($objPushVehicleDataRequest->temperature1 * 100) : "";
                $objPushVehicleDataRequest->temperature2 = isset($objPushVehicleDataRequest->temperature2) ? ($objPushVehicleDataRequest->temperature2 * 100) : "";
                $objPushVehicleDataRequest->temperature3 = isset($objPushVehicleDataRequest->temperature3) ? ($objPushVehicleDataRequest->temperature3 * 100) : "";
                $objPushVehicleDataRequest->temperature4 = isset($objPushVehicleDataRequest->temperature4) ? ($objPushVehicleDataRequest->temperature4 * 100) : "";
                $objPushVehicleDataRequest->digitalio = isset($objPushVehicleDataRequest->digitalio) ? $objPushVehicleDataRequest->digitalio : "0";
                $objPushVehicleDataRequest->driverName = isset($objPushVehicleDataRequest->driverName) ? $objPushVehicleDataRequest->driverName : "";
                $objPushVehicleDataRequest->isOnline = isset($objPushVehicleDataRequest->isOnline) ? $objPushVehicleDataRequest->isOnline : "";
                $objPushVehicleDataRequest->lastUpdated = isset($objPushVehicleDataRequest->lastUpdated) ? $objPushVehicleDataRequest->lastUpdated : $today;
                $dt = DateTime::createFromFormat("Y-m-d H:i:s", $objPushVehicleDataRequest->lastUpdated);
                if ($dt !== false && !array_sum($dt->getLastErrors())) {
                    $arrVTSResult = $apiobj->get_userdetails_by_key($objPushVehicleDataRequest->userkey);
                    if (empty($arrVTSResult)) {
                        $output = $apiobj->failure('No Userdata');
                        //Log error in DB
                    } else {
                        $customerno = $arrVTSResult['customerno'];
                        $objPushVehicleDataRequest->customerno = $customerno;
                        $result[] = $apiobj->pushvehicledata($objPushVehicleDataRequest);
                        if ($result == false) {
                            $output = $apiobj->failure("Mandatory parameter's value missing. Please resend the request with all parameters.");
                        } else {
                            $message = "Details updated successfully";
                            $output = $apiobj->success($message, $result);
                        }
                    }
                } else {
                    $output = $apiobj->failure("Date format incorrect. Correct date format: yyyy-mm-dd HH:MM:SS");
                }
            } else {
                $output[] = $apiobj->failure("Mandatory parameter's value missing. Please resend the request with all parameters.");
                //Log error in DB
            }
        } //End For each
    } catch (JsonMapper_Exception $jmEx) {
        $output = $apiobj->failure("JSON request mapping failed. Please resend the request with all required parameters.");
        //Log error in DB
    } catch (Exception $ex) {
        $output = $apiobj->failure("Whoops!!  Looks like there is an unknown exception");
        //Log error in DB
    }
}
if (!isset($output)) {
    $output = $apiobj->failure("Seems like you are playing around without any actions !!");
}
echo json_encode($output, JSON_UNESCAPED_SLASHES);
?>