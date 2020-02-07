<?php

//error_reporting(0);
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
require_once __DIR__ . "/../../../config.inc.php";
require_once __DIR__ . "/class/database.inc.php";
require_once __DIR__ . "/SalesengageManager.php";
require_once __DIR__ . "/constants.php";

// object of class driver
$apiobj = new SALES();

extract($_REQUEST);

if ($action == 'login') {
    $jsondata = json_decode($logindetails, true);
    $sales = $apiobj->getLoginData($jsondata);
}

if ($action == 'getclientrecipient') {
    $jsondata = json_decode($clientdetails, true);
    $sales = $apiobj->getRecipientData($jsondata);
}

if ($action == 'addclient') {
    $jsondata = json_decode($insertclient, true);
    $sales = $apiobj->InsertClientData($jsondata);
}

if ($action == 'addrecipient') {
    $jsondata = json_decode($insertrecipient, true);
    $sales = $apiobj->InsertRecipientData($jsondata);
}
/*
  if ($action == 'sendportfolio') {
  $jsondata = json_decode($portfoliodetails, true);
  $sales = $apiobj->getPortfolioData($jsondata);
  }
 * 
 */


if ($action == 'editclient') {
    $jsondata = json_decode($clientdata, true);
    $sales = $apiobj->EditClientData($jsondata);
}

if ($action == 'editrecipient') {
    $jsondata = json_decode($recipientdata, true);
    $sales = $apiobj->EditRecipientData($jsondata);
}

if ($action == 'addorder') {
    $jsondata = json_decode($orderdata, true);
    $sales = $apiobj->InsertOrderData($jsondata);
}

if ($action == 'deleteclient') {
    $jsondata = json_decode($clientdetails, true);
    $sales = $apiobj->DeleteClient($jsondata);
}

if ($action == 'deleterecipient') {
    $jsondata = json_decode($recipientdetails, true);
    $sales = $apiobj->DeleteRecipient($jsondata);
}

if ($action == 'searchclient') {
    $jsondata = json_decode($findclientname, true);
    $sales = $apiobj->SearchClient($jsondata);
}

if ($action == 'pagewiseclient') {
    $jsondata = json_decode($pagedetails, true);
    $sales = $apiobj->PaginationClientData($jsondata);
}

if ($action == 'getclient') {
    $jsondata = json_decode($clientiddata, true);
    $sales = $apiobj->GetOneClientData($jsondata);
}

if ($action == 'getorder') {
    $jsondata = json_decode($userdata, true);
    $sales = $apiobj->ViewOrder($jsondata);
}

if ($action == 'searchorder') {
    $jsondata = json_decode($searchclientname, true);
    $sales = $apiobj->SearchOrder($jsondata);
}

if ($action == 'pullorderdetails') {
    $jsondata = json_decode($orderiddata, true);
    $sales = $apiobj->PullOrderDetails($jsondata);
}

if ($action == 'editorder') {
    $jsondata = json_decode($orderdata, true);
    $sales = $apiobj->EditOrderDetails($jsondata);
}

if ($action == 'getreminder') {
    $jsondata = json_decode($reminderdata, true);
    $sales = $apiobj->GetReminder($jsondata);
}
/*
  if ($action == 'viewactivity') {
  $jsondata = json_decode($activityorderid, true);
  $sales = $apiobj->ViewActivity_ByOrder($jsondata);
  }
 */
if ($action == 'pullactivity') {
    $jsondata = json_decode($activityiddata, true);
    $sales = $apiobj->GetActivityDetails($jsondata);
}
if ($action == 'editactivity') {
    $jsondata = json_decode($activitydata, true);
    $sales = $apiobj->EditActivityDetails($jsondata);
}

if ($action == 'addactivity') {
    $jsondata = json_decode($addactivitydata, true);
    $sales = $apiobj->InsertActivityDetails($jsondata);
}

if ($action == "forgotpassword_request") {
    $jsondata = json_decode($forgotdetails, true);
    $sales = $apiobj->get_otp_forgotpwd($jsondata);
}

if ($action == "update_password") {
    $jsondata = json_decode($password_details, true);
    $sales = $apiobj->update_password($jsondata);
}

if ($action == "addticket") {
    $jsondata = json_decode($ticket_details, true);
    $sales = $apiobj->add_supportapi($jsondata);
}
/*
  if ($action == "pulltickettype") {
  $jsondata = json_decode($tickettype_data, true);
  $sales = $apiobj->getAlltickettypevalue($jsondata);
  }

  if ($action == "pullticketpriority") {
  $jsondata = json_decode($ticketpriority_data, true);
  $sales = $apiobj->getAllpriorityvalue($jsondata);
  }
 * 
 */
if ($action == "pullticketdata") {
    $jsondata = json_decode($ticket_data, true);
    $sales = $apiobj->GetTicketData($jsondata);
}

if ($action == "pullattachmentdata") {
    $jsondata = json_decode($attachment_data, true);
    $sales = $apiobj->GetAttachmentData($jsondata);
}
?>