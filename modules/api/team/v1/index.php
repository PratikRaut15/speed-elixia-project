<?php
//file required
//error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');
set_time_limit(60);
$RELATIVE_PATH_DOTS = "../../../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . "lib/autoload.php";
require_once "class/class.api.php";
//ob_start("ob_gzhandler");
//ojbect creation
$apiobj = new api();
$output = null;
//TODO: Need to replace all the Superglobals with the below correct usage:
//$data['vehicleno'] = filter_input(INPUT_REQUEST, 'vehicleno');
/*
It is not safe. Someone could do something like this: www.example.com?_SERVER['anything']
or if he has any kind of knowledge he could try to inject something into another variable
 */
extract($_REQUEST);
if ($action == "pullcredentials") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->username) && isset($inputData->password)) {
        $getData = $apiobj->check_login($inputData);
        if ($getData->userkey == '0') {
            $output = $apiobj->failure("Incorrect Username Or Password");
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullunit") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey) && isset($jsonreq->customerno)) {
        $getData = $apiobj->pullunit($jsonreq);
        if ($getData == null) {
            $output = $apiobj->failure("Invalid Customer Number");
        } elseif ($getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullcustomer") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey)) {
        $getData = $apiobj->pullCustomer($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullquery") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->unitno)) {
        $getData = $apiobj->pullQuery($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "Empty") {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "searchUnit") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->unitno)) {
        $getData = $apiobj->searchUnit($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "Empty") {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "searchSim") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->simno)) {
        $getData = $apiobj->searchSim($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "Empty") {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "suspectUnit") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->unitid)) {
        $inputData->simcardid = isset($inputData->simcardid) ? $inputData->simcardid : '0';
        $inputData->aptdate = isset($inputData->aptdate) ? date("Y-m-d", strtotime($inputData->aptdate)) : date("Y-m-d", strtotime('+1 day'));
        $inputData->coname = isset($inputData->coname) ? $inputData->coname : ' ';
        $inputData->cophone = isset($inputData->cophone) ? $inputData->cophone : '0';
        $inputData->priority = isset($inputData->priority) ? $inputData->priority : '1';
        $inputData->location = isset($inputData->location) ? $inputData->location : '';
        $inputData->timeslot = isset($inputData->timeslot) ? $inputData->timeslot : '0';
        $inputData->purpose = isset($inputData->purpose) ? $inputData->purpose : '0';
        $inputData->details = isset($inputData->details) ? $inputData->details : '';
        $inputData->coordinatorid = isset($inputData->coordinatorid) ? $inputData->coordinatorid : '0';
        $inputData->sendmail = isset($inputData->sendmail) ? $inputData->sendmail : '0';
        
        $inputData->comment = isset($inputData->comment) ? $inputData->comment : '';
        $inputData->lteamid = isset($inputData->lteamid) ? $inputData->lteamid : '0';
        $getData = $apiobj->suspect_Device($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "Suspect Fail") {
            $output = $apiobj->failure("Suspect Fail");
        } elseif ($getData == "Successful") {
            $message = "Successfully Suspect";
            $output = $apiobj->success($message, $getData);
        } elseif ($getData == "Successful Sent") {
            $message = "Mail Sent Successfully";
            $output = $apiobj->success($message, $getData);
        } else {
            $message = "Successfully Suspect.Mail Not Sent";
            $output = $apiobj->success($message, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "newInstallRequest") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->customerno)) {
        $inputData->aptDate = isset($inputData->aptDate) ? date("Y-m-d", strtotime($inputData->aptDate)) : date("Y-m-d", strtotime('+1 day'));
        $inputData->priority = isset($inputData->priority) ? $inputData->priority : '1';
        $inputData->location = isset($inputData->location) ? $inputData->location : '';
        $inputData->timeslot = isset($inputData->timeslot) ? $inputData->timeslot : '0';
        $inputData->details = isset($inputData->details) ? $inputData->details : '';
        $inputData->coordinatorid = isset($inputData->coordinatorid) ? $inputData->coordinatorid : '0';
        $inputData->coname = isset($inputData->coname) ? $inputData->coname : ' ';
        $inputData->cophone = isset($inputData->cophone) ? $inputData->cophone : '0';
        $inputData->installCount = isset($inputData->installCount) ? $inputData->installCount : '1';
        $inputData->lteamid = isset($inputData->lteamid) ? $inputData->lteamid : '0';
        $getData = $apiobj->newInstallRequest($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "Fail") {
            $output = $apiobj->failure("Request Failed");
        } elseif ($getData == "Successful") {
            $message = "Successfully requested";
            $output = $apiobj->success($message, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullBucketList") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey)) {
        $inputData->userkey = $inputData->userkey;
        $inputData->date = isset($inputData->date) ? $inputData->date : date("Y-m-d", strtotime('+1 day'));
        $getData = $apiobj->pullBucketList($inputData);

        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "Empty") {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullCoordinator") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->customerno)) {
        $inputData->userkey = $inputData->userkey;
        $inputData->customerno = $inputData->customerno;
        $getData = $apiobj->pullCoordinator($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "Empty") {
            $output = $apiobj->failure('Cordinator not available');
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullReason") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey)) {
        $inputData->userkey = $inputData->userkey;
        $getData = $apiobj->pullReason($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "Empty") {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "editBucketOperation") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->bucketid)) {
        $inputData->userkey = $inputData->userkey;
        $inputData->bucketid = $inputData->bucketid;
        $inputData->status = $inputData->status;
        if ($inputData->status == 1) {
            $inputData->data = date('Y-m-d', strtotime($inputData->data));
        } else {
            $inputData->data = $inputData->data;
        }
        $getData = $apiobj->editBucketOperation($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "fail") {
            $output = $apiobj->failure("Edit failed");
        } elseif ($getData == "success") {
            $message = "Successfully Edited";
            $output = $apiobj->success($message, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "editBucketCRM") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->bucketid)) {
        $inputData->userkey = $inputData->userkey;
        $inputData->status = $inputData->status;
        $inputData->customerno = $inputData->customerno;
        $inputData->vehicleid = $inputData->vehicleid;
        $inputData->createdby = $inputData->createdby;
        $inputData->priorityid = $inputData->priorityid;
        $inputData->location = $inputData->location;
        $inputData->timeslot = $inputData->timeslot;
        $inputData->purposeid = $inputData->purposeid;
        $inputData->details = $inputData->details;
        $inputData->data = $inputData->data;
        $inputData->coordinator = $inputData->coordinator;
        $inputData->aptdate = date('Y-m-d', strtotime($inputData->aptdate));
        $inputData->coname = $inputData->coname;
        $inputData->cophone = $inputData->cophone;
        $inputData->bucketid = $inputData->bucketid;
        $getData = $apiobj->editBucketCRM($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "fail") {
            $output = $apiobj->failure("Edit Fail");
        } elseif ($getData == "success") {
            $message = "Successfully Edited";
            $output = $apiobj->success($message, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "registerDevice") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->unitid) && isset($inputData->simcardid) && isset($inputData->cvehicleno) && isset($inputData->status) && isset($inputData->bucketid)) {
        $inputData->userkey = $inputData->userkey;
        $inputData->status = $inputData->status;
        $inputData->bucketid = $inputData->bucketid;
        $inputData->pono = (isset($inputData->pono) && !empty($inputData->pono)) ? $inputData->pono : '0';
        $inputData->podate = (isset($inputData->podate) && !empty($inputData->podate)) ? $inputData->podate : '0000-00-00';
        $inputData->eteamid = isset($inputData->eteamid) ? $inputData->eteamid : '0';
        $inputData->unitid = $inputData->unitid;
        $inputData->utype = isset($inputData->utype) ? $inputData->utype : '23';
        $inputData->sendmailr = isset($inputData->sendmailr) ? $inputData->sendmailr : '0';
        $inputData->simcardid = $inputData->simcardid;
        $inputData->customerno = isset($inputData->customerno) ? $inputData->customerno : '0';
        $inputData->cinstalldate = (isset($inputData->cinstalldate) && !empty($inputData->cinstalldate)) ? $inputData->cinstalldate : date("Y-m-d");
        $a_date = date("Y-m-d");
        $end_date=date("Y-m-t", strtotime($a_date));
        $inputData->end_date = (isset($inputData->end_date) && !empty($inputData->end_date)) ? $inputData->end_date : $end_date;
        $inputData->cexpirydate = (isset($inputData->cexpirydate) && !empty($inputData->cexpirydate)) ? $inputData->cexpirydate : date('Y-m-d', strtotime('+1 year'));
        $inputData->cinvoiceno = isset($inputData->cinvoiceno) ? $inputData->cinvoiceno : '0';
        $inputData->cvehicleno = $inputData->cvehicleno;
        $inputData->kind = isset($inputData->kind) ? $inputData->kind : 'Truck';
        $inputData->lease = (isset($inputData->lease) && !empty($inputData->pono)) ? $inputData->lease : '0';
        $inputData->lteamid = isset($inputData->lteamid) ? $inputData->lteamid : '0';
        $inputData->unsuccess_problem = isset($inputData->unsuccess_problem) ? $inputData->unsuccess_problem : '';
        $inputData->incomplete_date = isset($inputData->incomplete_date) ? $inputData->incomplete_date : '';
        $inputData->reschedule_date = isset($inputData->reschedule_date) ? $inputData->reschedule_date : '';
        $inputData->comment = isset($inputData->comment) ? $inputData->comment : '';
        $inputData->docketid = isset($inputData->docketid) ? $inputData->docketid : '0';
        $getData = $apiobj->registerDevice($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "Fail") {
            $output = $apiobj->failure("Bucket updation failed");
        } elseif ($getData == "Successful installed") {
            $message = "Successfully installed";
            $output = $apiobj->success($message, $getData);
        } elseif ($getData == "Successful") {
            $message = "Successfully updated bucket";
            $output = $apiobj->success($message, $getData);
        } elseif ($getData == "Successful Sent") {
            $message = "Successfully installed.Mail Sent Successfully";
            $output = $apiobj->success($message, $getData);
        } else {
            $message = "Successfully installed.Mail Not Sent";
            $output = $apiobj->success($message, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "replaceDevice") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->oldunitid) && isset($inputData->newunitid) && isset($inputData->bucketid)) {
        $inputData->userkey = $inputData->userkey;
        $inputData->customerno = $inputData->customerno;
        $inputData->oldvehicleid = $inputData->oldvehicleid;
        $inputData->oldunitid = $inputData->oldunitid;
        $inputData->eteamid = $inputData->eteamid;
        $inputData->newunitid = $inputData->newunitid;
        $inputData->lteamid = $inputData->lteamid;
        $inputData->sendmailr = $inputData->sendmailr;
        $inputData->bucketid = $inputData->bucketid;
        $inputData->comments = $inputData->comments;
        $getData = $apiobj->replaceDevice($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "Replace failed") {
            $output = $apiobj->failure("Unit replacement failed");
        } elseif ($getData == "Successful") {
            $message = "Unit successfully replaced";
            $output = $apiobj->success($message, $inputData);
        } elseif ($getData == "Successful sent") {
            $message = "Unit successfully replaced.Mail sent";
            $output = $apiobj->success($message, $inputData);
        } else {
            $message = "Unit successfully replaced.Mail not sent";
            $output = $apiobj->success($message, $inputData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "replaceSimcard") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->unitid) && isset($inputData->newsimid) && isset($inputData->bucketid)) {
        $inputData->userkey = $inputData->userkey;
        $inputData->customerno = $inputData->customerno;
        $inputData->oldvehicleid = $inputData->oldvehicleid;
        $inputData->unitid = $inputData->unitid;
        $inputData->eteamid = $inputData->eteamid;
        $inputData->newsimid = $inputData->newsimid;
        $inputData->lteamid = $inputData->lteamid;
        $inputData->sendmailr = $inputData->sendmailr;
        $inputData->bucketid = $inputData->bucketid;
        $inputData->comments = $inputData->comments;
        $getData = $apiobj->replaceSimcard($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "Replace failed") {
            $output = $apiobj->failure("Simcard replacement failed");
        } elseif ($getData == "Successful") {
            $message = "Simcard successfully replaced";
            $output = $apiobj->success($message, $inputData);
        } elseif ($getData == "Successful sent") {
            $message = "Simcard successfully replaced.Mail sent";
            $output = $apiobj->success($message, $inputData);
        } else {
            $message = "Simcard successfully replaced.Mail not sent";
            $output = $apiobj->success($message, $inputData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "replaceUnitSim") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->oldunitid) && isset($inputData->newunitid) && isset($inputData->newsimcardid) && isset($inputData->status) && isset($inputData->bucketid)) {
        $inputData->userkey = $inputData->userkey;
        $inputData->customerno = $inputData->customerno;
        $inputData->oldvehicleid = $inputData->oldvehicleid;
        $inputData->oldunitid = $inputData->oldunitid;
        $inputData->eteamid = $inputData->eteamid;
        $inputData->newunitid = $inputData->newunitid;
        $inputData->newsimcardid = $inputData->newsimcardid;
        $inputData->lteamid = $inputData->lteamid;
        $inputData->status = $inputData->status;
        $inputData->unsuccess_problem = $inputData->unsuccess_problem;
        $inputData->incomplete_date = $inputData->incomplete_date;
        $inputData->reschedule_date = $inputData->reschedule_date;
        $inputData->bucketid = $inputData->bucketid;
        $inputData->sendmailr = $inputData->sendmailr;
        $inputData->comments = $inputData->comments;
        $getData = $apiobj->replaceBoth($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "Failed") {
            $output = $apiobj->failure("Bucket updatation failed");
        } elseif ($getData == "Successful replace") {
            $message = "Unit and simcard successfully replaced";
            $output = $apiobj->success($message, $inputData);
        } elseif ($getData == "Successful") {
            $message = "Successfully updated bucket";
            $output = $apiobj->success($message, $inputData);
        } elseif ($getData == "Successful sent") {
            $message = "Unit and simcard successfully replaced.Mail sent";
            $output = $apiobj->success($message, $inputData);
        } else {
            $message = "Unit and simcard successfully replaced.Mail not sent";
            $output = $apiobj->success($message, $inputData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "removeUnitSim") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->unitid) && isset($inputData->status) && isset($inputData->bucketid)) {
        $inputData->userkey = $inputData->userkey;
        $inputData->customerno = $inputData->customerno;
        $inputData->unitid = $inputData->unitid;
        $inputData->eteamid = $inputData->eteamid;
        $inputData->lteamid = $inputData->lteamid;
        $inputData->status = $inputData->status;
        $inputData->unsuccess_problem = $inputData->unsuccess_problem;
        $inputData->incomplete_date = $inputData->incomplete_date;
        $inputData->reschedule_date = $inputData->reschedule_date;
        $inputData->bucketid = $inputData->bucketid;
        $inputData->sendmailr = $inputData->sendmailr;
        $inputData->comments = $inputData->comments;
        $getData = $apiobj->removeBoth($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "Failed") {
            $output = $apiobj->failure("Bucket updation failed");
        } elseif ($getData == "Successful remove") {
            $message = "Successfully removed device";
            $output = $apiobj->success($message, $inputData);
        } elseif ($getData == "Successful") {
            $message = "Successfully updated bucket";
            $output = $apiobj->success($message, $inputData);
        } elseif ($getData == "Successful sent") {
            $message = "Successfully removed device.Mail sent";
            $output = $apiobj->success($message, $inputData);
        } else {
            $message = "Successfully removed device.Mail not sent";
            $output = $apiobj->success($message, $inputData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "repair") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->unitid) && isset($inputData->status) && isset($inputData->bucketid)) {
        $inputData->userkey = $inputData->userkey;
        $inputData->unitid = $inputData->unitid;
        $inputData->simcardid = $inputData->simcardid;
        $inputData->eteamid = $inputData->eteamid;
        $inputData->lteamid = $inputData->lteamid;
        $inputData->customerno = $inputData->customerno;
        $inputData->status = $inputData->status;
        $inputData->unsuccess_problem = $inputData->unsuccess_problem;
        $inputData->incomplete_date = $inputData->incomplete_date;
        $inputData->reschedule_date = $inputData->reschedule_date;
        $inputData->bucketid = $inputData->bucketid;
        $inputData->sendmailr = $inputData->sendmailr;
        $inputData->comments = $inputData->comments;
        $getData = $apiobj->repair($inputData);
        if ($getData == null) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "Failed") {
            $output = $apiobj->failure("Bucket updatation failed");
        } elseif ($getData == "Successful repair") {
            $message = "Successfully repaired device";
            $output = $apiobj->success($message, $inputData);
        } elseif ($getData == "Successful") {
            $message = "Successfully updated bucket";
            $output = $apiobj->success($message, $inputData);
        } elseif ($getData == "Successful sent") {
            $message = "Successfully repaired device.Mail sent";
            $output = $apiobj->success($message, $inputData);
        } else {
            $message = "Successfully repaired device.Mail not sent";
            $output = $apiobj->success($message, $inputData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "reinstall") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->unitid) && isset($inputData->newvehicleno) && isset($inputData->status) && isset($inputData->bucketid)) {
        $inputData->userkey = $inputData->userkey;
        $inputData->unitid = $inputData->unitid;
        $inputData->eteamid = $inputData->eteamid;
        $inputData->newvehicleno = $inputData->newvehicleno;
        $inputData->lteamid = $inputData->lteamid;
        $inputData->status = $inputData->status;
        $inputData->unsuccess_problem = $inputData->unsuccess_problem;
        $inputData->incomplete_date = $inputData->incomplete_date;
        $inputData->reschedule_date = $inputData->reschedule_date;
        $inputData->bucketid = $inputData->bucketid;
        $inputData->sendmailr = $inputData->sendmailr;
        $inputData->comments = $inputData->comments;
        $getData = $apiobj->reinstall($inputData);
        if ($getData == NULL) {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == "Failed") {
            $output = $apiobj->failure("Bucket updation failed");
        } elseif ($getData == "Successful reinstall") {
            $message = "Successfully reinstalled device";
            $output = $apiobj->success($message, $inputData);
        } elseif ($getData == "Successful") {
            $message = "Successfully updated bucket";
            $output = $apiobj->success($message, $inputData);
        } elseif ($getData == "Successful sent") {
            $message = "Successfully reinstalled device.Mail sent";
            $output = $apiobj->success($message, $inputData);
        } else {
            $message = "Successfully reinstalled device.Mail not sent";
            $output = $apiobj->success($message, $inputData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullTeam") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey)) {
        $inputData->userkey = $inputData->userkey;
        $getData = $apiobj->pullTeam($inputData);
        if ($getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "elixirUnitSim") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->teamid)) {
        $inputData->userkey = $inputData->userkey;
        $inputData->teamid = $inputData->teamid;
        $getData = $apiobj->elixir_Unit_Sim($inputData);
        if ($getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == NULL) {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "customerUnitSimVeh") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->customerno)) {
        $inputData->userkey = $inputData->userkey;
        $inputData->customerno = $inputData->customerno;
        $getData = $apiobj->cust_Unit_Sim_Veh($inputData);
        if ($getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == NULL) {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullmyticket") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey) && isset($inputData->teamid)) {
        $getData = $apiobj->myticket($inputData);
        if ($getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == NULL) {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullStatusPriorityType") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey)) {
        $getData = $apiobj->pullStatusPriorityType($inputData);
        if ($getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == NULL) {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "addTicket") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey, $inputData->title, $inputData->customerno, $inputData->desc, $inputData->allot_to, $inputData->raise_on_date, $inputData->send_mail_to_cust)) {
        $getData = $apiobj->addTicket($inputData);
        if ($getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == 'Fail') {
            $output = $apiobj->failure("Ticket creation failed");
        } else {
            $output = $apiobj->success('Ticket created successfully', '');
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "editTicket") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey, $inputData->ticketid, $inputData->customerno, $inputData->ticketdesc, $inputData->ticket_allot, $inputData->title, $inputData->sendemailstatus)) {
        $getData = $apiobj->editTicket($inputData);
        if ($getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == 'Fail') {
            $output = $apiobj->failure("Ticket updation failed");
        } else {
            $output = $apiobj->success('Ticket updated successfully', $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "addNote") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey, $inputData->ticketid, $inputData->note, $inputData->lteamid)) {
        $getData = $apiobj->addNote($inputData);
        if ($getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == 'Fail') {
            $output = $apiobj->failure("Note addition failed");
        } else {
            $output = $apiobj->success('Note added successfully', $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullNote") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey, $inputData->ticketid)) {
        $getData = $apiobj->pullNote($inputData);
        if ($getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == NULL) {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullemail") {
    $inputData = json_decode($jsonreq);
    if (isset($inputData->userkey)) {
        $getData = $apiobj->pullemail($inputData);
        if ($getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } elseif ($getData == NULL) {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
// ***** SALES MOULE ***** //
if ($action == "pullstage") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->pullstage($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullsource") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->pullsource($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullproduct") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->pullproduct($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullindustry") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->pullindustry($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullmode") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->pullmode($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullmode") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->pullmode($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullcustomers") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->pullcustomers($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullcustomers_frozen") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->pullcustomers_frozen($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullcustomerdetails") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->pullcustomerdetails($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullpipelinehistory") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->pullpipelinehistory($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullreminders") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->pullreminders($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullusers") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->pullusers($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_DATA_NOT_FOUND);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "setstage") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->setstage($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "updatereminder") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->updatereminder($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "updateuser") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->updateuser($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "updatecustomer") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->updatecustomer($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "deletecustomer") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->deletecustomer($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "deleteuser") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->deleteuser($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "deletereminder") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->deletereminder($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pull_sales_dashboard") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->pull_sales_dashboard($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pull_elixia_game_result") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->pull_elixia_game_result($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "setreminder") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->setreminder($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "setuser") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->setuser($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "setcustomer") {
    $jsonreq = json_decode($jsonreq);
    if (isset($jsonreq->userkey)) {
        $getData = $apiobj->setcustomer($jsonreq);
        if ($getData == null || $getData == 'WrongUserkey') {
            $output = $apiobj->failure(speedConstants::API_INVALID_USERKEY);
        } else {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
        }
    } else {
        $output = $apiobj->failure(speedConstants::API_MANDATORY_PARAM_MISSING);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pullTeamList") {
    $getData = $apiobj->fetchTeamList();
    //$getData='';
    if ($getData == null) {
        $output = $apiobj->failure(speedConstants::API_ERROR_MESSAGE);
    } else {
        $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
    }
    echo json_encode($output);
    return $output;
}
if ($action == "pushTeamAttendance") {
    $inputData = json_decode($jsonreq);
    if ($inputData == null) {
        $output = $apiobj->failure(speedConstants::API_ERROR_MESSAGE);
    } else {
        $attendanceObj = new stdClass();
        $locationObj = new stdClass();
        $locationObj->center = $inputData->center;
        $location = $apiobj->getOfficeLocation($locationObj);
        $attendanceObj->location = $location;
        $attendanceObj->teamId = $inputData->teamid;
        $attendanceObj->check_value = $inputData->checkValue;
        $attendanceResult = $apiobj->insertTeamAttendance($attendanceObj);
        if ($attendanceResult) {
            $output = $apiobj->success(speedConstants::API_SUCCESS, $attendanceResult);
        } else {
            $output = $apiobj->failure(speedConstants::API_ERROR_MESSAGE);
        }
    }
    echo json_encode($output);
    return $output;
}
?>