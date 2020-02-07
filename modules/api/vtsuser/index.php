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
//New Vts api - 19 august 2016 

if ($action == "pushlocation") {  //remaining temprature parameter 
    if(isset($userkey) && isset($latitude) && isset($longitude) && isset($ignition) && isset($speed) && isset($lastupdated) && isset($vehicleno) && isset($odometer) && isset($unitno) && isset($online_offline)) {
        $validation = $apiobj->check_userkey($userkey);
        $data = $_REQUEST;
        $validation = $apiobj->pushlocation($validation,$data);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "getlocation"){  // getlocation - Done 
    if (isset($userkey) && isset($vehicleno)) {
        $validation = $apiobj->check_userkey($userkey);
        $getlocation = $apiobj->getlocation_vehicle($validation, $vehicleno);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "getvehlist") {  // getvehlist - Done  
    if (isset($userkey)) {
        $validation = $apiobj->check_userkey($userkey);
        $getvehiclelist = $apiobj->getvehiclelist($validation);
    }else{
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "getsummary") {  // getsummary - testing remaining 
    if (isset($userkey) && isset($vehicleno)) {
        $validation = $apiobj->check_userkey($userkey);
        $getsummary = $apiobj->getsummary_details($validation, $vehicleno);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "getalerthist") {  // getalerthist - Done  
    if (isset($userkey) && isset($vehicleno)) {
        $validation = $apiobj->check_userkey($userkey);
        $getalerthistory = $apiobj->getalerthistory($validation, $vehicleno);
    }else{
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "showlocation") {  // showlocation - Done 
    if (isset($userkey) && isset($vehicleno)) {
        $validation = $apiobj->check_userkey($userkey);
        $getsummary = $apiobj->getlocation_vehicle($validation, $vehicleno);
    }else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == 'getclientcode') {  // getclientcode - not compelte   
    if (isset($userkey) && isset($vehicleno)) {
        $validation = $apiobj->check_userkey($userkey);
        $data = array(
         'vehicleno'=>$vehicleno,
         'includereports'=>isset($includereports)?$includereports:0   
        );
        $getclientcode = $apiobj->generate_clientcode($validation,$data);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}


if ($action == "freezevehicle") {  // freezevehicle - Done  
    if (isset($userkey) && isset($vehicleno) && isset($freeze)) {
        $freezevehicle = $apiobj->freezevehicleapi($userkey, $vehicleno, $freeze);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "immobilizevehicle") {  // immobilizevehicle - Done  
    if (isset($userkey) && isset($vehicleno) && isset($immobilize)) {
        $immobilizevehicle = $apiobj->pushmobiliser($userkey,$vehicleno, $immobilize);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if ($action == "buzzvehicle") {  // buzzvehicle - Done  
    if (isset($userkey) && isset($vehicleno)) {
        $buzzvehicle = $apiobj->pushbuzzer($userkey, $vehicleno,$status);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if($action=="contractinfo"){  // remaining contractinfo 
    if (isset($userkey)) {
        $validation = $apiobj->check_userkey($userkey);
        $contractinfo = $apiobj->contractinfo($validation);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if($action=="getquicksharetext"){  // remaining get quicksharetext 
    if (isset($userkey) && isset($vehicleno)) {
        $validation = $apiobj->check_userkey($userkey);
        $getquicksharetext = $apiobj->getquicksharetext($validation, $vehicleno);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}

if($action=="getquicksharesms"){  // remaining getquicksharesms 
    if (isset($userkey) && isset($vehicleno)&&isset($mobilenolist)) {
        $validation = $apiobj->check_userkey($userkey);
        $getquicksharetext = $apiobj->getquicksharetext($validation, $vehicleno,$mobilenolist);
    } else {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        echo json_encode($arr_p);
    }
}
?>