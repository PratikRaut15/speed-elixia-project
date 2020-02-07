<?php

//file required
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');
require_once("../../../../config.inc.php");
require_once("class/class.api.php");
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

if ($action == "get_proposed_indents") {
  if (isset($userkey) && isset($startdate) && isset($enddate) && isset($isaccepted) && isset($isautorejected)) {
    $pageIndex = isset($pageIndex) ? $pageIndex : 1;
    /* If pageSize is -1, it means we need to give all the records in a single page */
    $pageSize = isset($pageSize) ? $pageSize : -1;
    /* If search string is set, it means we need to return all the records matching this string */
    $searchstring = isset($searchstring) ? $searchstring : '';
    $test = $apiobj->get_proposed_indents($userkey, $startdate, $enddate, $isaccepted, $isautorejected, $pageIndex, $pageSize, $searchstring);
  } else {
    $arr_p['status'] = "unsuccessful";
    $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
    echo json_encode($arr_p);
  }
}

if ($action == "get_proposed_indent_details") {
  if (isset($userkey) && isset($indentid) && isset($transporterid) && isset($pitmappingid)) {
    $pageIndex = isset($pageIndex) ? $pageIndex : 1;
    /* If pageSize is -1, it means we need to give all the records in a single page */
    $pageSize = isset($pageSize) ? $pageSize : -1;
    /* If search string is set, it means we need to return all the records matching this string */
    $searchstring = isset($searchstring) ? $searchstring : '';
    $test = $apiobj->get_proposed_indents_details($userkey, $indentid, $transporterid, $pitmappingid, $pageIndex, $pageSize, $searchstring);
  } else {
    $arr_p['status'] = "unsuccessful";
    $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
    echo json_encode($arr_p);
  }
}

if ($action == "modify_proposed_indent") {
  $details = json_decode($details);
  if (isset($userkey) && isset($details)) {
    $test = $apiobj->modify_proposed_indent($userkey, $details);
  } else {
    $arr_p['status'] = "unsuccessful";
    $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
    echo json_encode($arr_p);
  }
}

if ($action == "dashboard") {
  if (isset($userkey)) {
    $test = $apiobj->get_dashboard($userkey);
  } else {
    $arr_p['status'] = "unsuccessful";
    $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
    echo json_encode($arr_p);
  }
}
?>
