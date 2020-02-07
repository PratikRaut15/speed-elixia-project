<?php

error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');
require_once("../../../../config.inc.php");
require_once("class/class.api.php");

//ob_start("ob_gzhandler");
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

if ($action == "dashboard") {
 if (isset($userkey)) {
  $pageIndex = isset($pageIndex) ? $pageIndex : 1;
  /* If pageSize is -1, it means we need to give all the records in a single page */
  $pageSize = isset($pageSize) ? $pageSize : -1;
  /* If search string is set, it means we need to return all the records matching this string */
  $todaysdate = date('Y-m-d');
  $pickupid = isset($pickupid) ? $pickupid : 0;
  $clientid = isset($clientid) ? $clientid : 0;
  $vendorno = isset($vendorno) ? $vendorno : 0;
  $pickupboyid = isset($pickupboyid) ? $pickupboyid : 0;
  $pickupdate = isset($pickupdate) ? $pickupdate : $todaysdate;
  $status = isset($status) ? $status : 0;
  $test = $apiobj->getDashboard($userkey, $pickupid, $clientid, $vendorno, $pickupboyid, $pickupdate, $status, $pageIndex, $pageSize);
  $apiobj->updateLogin($userkey);
 } else {
  $arr_p['status'] = "unsuccessful";
  $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
  echo json_encode($arr_p);
 }
}


if ($action == "pullpickups") {
 if (isset($userkey)) {
  $pageIndex = isset($pageIndex) ? $pageIndex : 1;
  /* If pageSize is -1, it means we need to give all the records in a single page */
  $pageSize = isset($pageSize) ? $pageSize : -1;
  /* If search string is set, it means we need to return all the records matching this string */
  $todaysdate = date('Y-m-d');
  $pickupid = isset($pickupid) ? $pickupid : 0;
  $clientid = isset($clientid) ? $clientid : 0;
  $vendorno = isset($vendorno) ? $vendorno : 0;
  $pickupboyid = isset($pickupboyid) ? $pickupboyid : 0;
  $pickupdate = isset($pickupdate) ? $pickupdate : $todaysdate;
  $status = isset($status) ? $status : 0;
  $test = $apiobj->pullorders_pending($userkey, $pickupid, $clientid, $vendorno, $pickupboyid, $pickupdate, $status, $pageIndex, $pageSize);
  $apiobj->updateLogin($userkey);
 } else {
  $arr_p['status'] = "unsuccessful";
  $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
  echo json_encode($arr_p);
 }
}

if ($action == "pullpickupdetails") {
 if (isset($userkey) && isset($vendorno)) {
  $pageIndex = isset($pageIndex) ? $pageIndex : 1;
  /* If pageSize is -1, it means we need to give all the records in a single page */
  $pageSize = isset($pageSize) ? $pageSize : -1;
  /* If search string is set, it means we need to return all the records matching this string */
  $todaysdate = date('Y-m-d');
  $pickupid = isset($pickupid) ? $pickupid : 0;
  $clientid = isset($clientid) ? $clientid : 0;
  $vendorno = isset($vendorno) ? $vendorno : 0;
  $pickupboyid = isset($pickupboyid) ? $pickupboyid : 0;
  $pickupdate = isset($pickupdate) ? $pickupdate : $todaysdate;
  $status = isset($status) ? $status : 0;
  $test = $apiobj->pullvendorpickups($userkey, $pickupid, $clientid, $vendorno, $pickupboyid, $pickupdate, $status, $pageIndex, $pageSize);
  $apiobj->updateLogin($userkey);
 } else {
  $arr_p['status'] = "unsuccessful";
  $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
  echo json_encode($arr_p);
 }
}

if ($action == "pulldelivery") {
 if (isset($userkey)) {
  $pageIndex = isset($pageIndex) ? $pageIndex : 1;
  /* If pageSize is -1, it means we need to give all the records in a single page */
  $pageSize = isset($pageSize) ? $pageSize : -1;
  /* If search string is set, it means we need to return all the records matching this string */
  $todaysdate = date('Y-m-d');
  $pickupid = isset($pickupid) ? $pickupid : 0;
  $clientid = isset($clientid) ? $clientid : 0;
  $vendorno = isset($vendorno) ? $vendorno : 0;
  $pickupboyid = isset($pickupboyid) ? $pickupboyid : 0;
  $pickupdate = isset($pickupdate) ? $pickupdate : $todaysdate;
  $status = isset($status) ? $status : 0;
  $test = $apiobj->pulldelivery_pending($userkey, $pickupid, $clientid, $vendorno, $pickupboyid, $pickupdate, $status, $pageIndex, $pageSize);
  $apiobj->updateLogin($userkey);
 } else {
  $arr_p['status'] = "unsuccessful";
  $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
  echo json_encode($arr_p);
 }
}

if ($action == "pulldeliverydetails") {
 if (isset($userkey) && isset($vendorno)) {
  $pageIndex = isset($pageIndex) ? $pageIndex : 1;
  /* If pageSize is -1, it means we need to give all the records in a single page */
  $pageSize = isset($pageSize) ? $pageSize : -1;
  /* If search string is set, it means we need to return all the records matching this string */
  $todaysdate = date('Y-m-d');
  $pickupid = isset($pickupid) ? $pickupid : 0;
  $vendorno = isset($vendorno) ? $vendorno : 0;
  $pickupboyid = isset($pickupboyid) ? $pickupboyid : 0;
  $pickupdate = isset($pickupdate) ? $pickupdate : $todaysdate;
  $status = isset($status) ? $status : 0;
  $test = $apiobj->pullvendordelivery($userkey, $pickupid, $vendorno, $pickupboyid, $pickupdate, $status, $pageIndex, $pageSize);
  $apiobj->updateLogin($userkey);
 } else {
  $arr_p['status'] = "unsuccessful";
  $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
  echo json_encode($arr_p);
 }
}



if ($action == "vendors") {
 if (isset($userkey)) {
  $test = $apiobj->pullvendors($userkey);
 } else {
  $arr_p['status'] = "unsuccessful";
  $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
  echo json_encode($arr_p);
 }
}

if ($action == "clients") {
 if (isset($userkey)) {
  $test = $apiobj->pullclients($userkey);
 } else {
  $arr_p['status'] = "unsuccessful";
  $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
  echo json_encode($arr_p);
 }
}

if ($action == "pickupdetails") {
 if (isset($userkey) && isset($pickupid)) {
  $todaysdate = date('Y-m-d');
  $clientid = isset($clientid) ? $clientid : 0;
  $vendorno = isset($vendorno) ? $vendorno : 0;
  $pickupboyid = isset($pickupboyid) ? $pickupboyid : 0;
  $pickupdate = isset($pickupdate) ? $pickupdate : $todaysdate;
  $status = isset($status) ? $status : 0;
  $test = $apiobj->pullorder_details($userkey, $pickupid, $clientid, $vendorno, $pickupboyid, $pickupdate, $status);
 } else {
  $arr_p['status'] = "unsuccessful";
  $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
  echo json_encode($arr_p);
 }
}


if ($action == "pullreasons") {
 if (isset($userkey)) {
  $test = $apiobj->pull_reasons($userkey);
 } else {
  $arr_p['status'] = "unsuccessful";
  $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
  echo json_encode($arr_p);
 }
}

if ($action == "cancel") {
 if (isset($userkey) && isset($pickupids) && isset($reasonid) && isset($type) && isset($vendorno) && isset($clientid)) {
  if ($type == 'pickup') {
   $test = $apiobj->pushstatus($userkey, $pickupids, $reasonid, $vendorno, $clientid);
  } elseif ($type == 'delivery') {
   $test = $apiobj->pushcancellation($userkey, $pickupids, $reasonid, $vendorno, $clientid);
  } else {
   $arr_p['status'] = "unsuccessful";
   $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
   echo json_encode($arr_p);
  }
 } else {
  $arr_p['status'] = "unsuccessful";
  $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
  echo json_encode($arr_p);
 }
}

if ($action == "signature") {
 if (isset($userkey) && isset($pickupids) && isset($type) && isset($vendorno) && isset($clientid) && (isset($signature))) {
  $test = $apiobj->pushsignature($userkey, $pickupids, $type, $vendorno, $clientid, $signature);
 } else {
  $arr_p['status'] = "unsuccessful";
  $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
  echo json_encode($arr_p);
 }
}


if ($action == "pushphoto") {
 if (isset($userkey) && isset($pickupids) && isset($type) && isset($vendorno) && isset($clientid) && (isset($photo))) {
  $test = $apiobj->pushphoto($userkey, $pickupids, $type, $vendorno, $clientid, $photo);
 } else {
  $arr_p['status'] = "unsuccessful";
  $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
  echo json_encode($arr_p);
 }
}
?>