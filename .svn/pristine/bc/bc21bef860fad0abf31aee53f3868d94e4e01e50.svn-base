<?php
//file required
//error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');
set_time_limit(60);
$RELATIVE_PATH_DOTS = "../../../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . "lib/autoload.php";
require_once "class/class1.api.php";
//ob_start("ob_gzhandler");
//ojbect creation
$apiobj = new api_new();
$output = null;
//TODO: Need to replace all the Superglobals with the below correct usage:
//$data['vehicleno'] = filter_input(INPUT_REQUEST, 'vehicleno');
/*
  It is not safe. Someone could do something like this: www.example.com?_SERVER['anything']
  or if he has any kind of knowledge he could try to inject something into another variable
 */

	extract($_REQUEST);

	if($action=="pullTeamList"){
	    $getData = $apiobj->fetchTeamList();
		//$getData='';
	    if ($getData == null) {
	        $output = $apiobj->failure(speedConstants::API_ERROR_MESSAGE);
	    }
	    else {
	        $output = $apiobj->success(speedConstants::API_SUCCESS, $getData);
	    }
	    echo json_encode($output);
	    return $output;
	}

	if($action=="pushTeamAttendance"){
		
	    $inputData = json_decode($jsonreq);

	    if ($inputData == null) {
	        $output = $apiobj->failure(speedConstants::API_ERROR_MESSAGE);
	    }
	    else {
		    $attendanceObj = new stdClass();
		    $locationObj = new stdClass();
		    $locationObj->center=$inputData->center;
		    $location=$apiobj->getOfficeLocation($locationObj);
	        
	        $attendanceObj->location 	= $location;
	        $attendanceObj->teamId 		= $inputData->teamid;
	        $attendanceObj->check_value = $inputData->checkValue;
	        $attendanceResult = $apiobj->insertTeamAttendance($attendanceObj);
	        
		        if($attendanceResult){
		        	$output = $apiobj->success(speedConstants::API_SUCCESS,$attendanceResult);
		        }
		        else{
		        	$output = $apiobj->failure(speedConstants::API_ERROR_MESSAGE);
		        }

	    }
	    echo json_encode($output);
	    return $output;
	}

