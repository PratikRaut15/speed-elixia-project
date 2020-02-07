<?php

include 'tms_function.php';
$customerno = 116;
$userid = 485;
$date = date("Y-m-d H:m:s", strtotime('-24 hours', time()));
//$date = date("2015-11-05 00:00:00");
$userid = $objTms = new TMS($customerno, $userid);
$objProposedIndent = new ProposedIndent();
$objProposedIndent->customerno = $customerno;
$objProposedIndent->date_required = $date;
$pendingindent = $objTms->get_autoreject_indent($objProposedIndent);

foreach ($pendingindent as $indent) {
 /* Proposed Indent Transporter Mapping */
 $objProposedTransporterIndent = new ProposedIndentTransporterMapping();
 $objProposedTransporterIndent->customerno = $customerno;
 $objProposedTransporterIndent->proposedindentid = $indent['proposedindentid'];
 $objProposedTransporterIndent->vehicleno = $indent['vehicleno'];
 $objProposedTransporterIndent->actual_vehicletypeid = $indent['actual_vehicletypeid'];
 $objProposedTransporterIndent->proposed_vehicletypeid = $indent['proposed_vehicletypeid'];
 $objProposedTransporterIndent->proposed_transporterid = $indent['proposed_transporterid'];
 $objProposedTransporterIndent->drivermobileno = $indent['drivermobileno'];
 $objProposedTransporterIndent->pitmappingid = $indent['pitmappingid'];
 $objProposedTransporterIndent->remarks = 'Auto Rejected';
 $objProposedTransporterIndent->isAccepted = -1;
 $objProposedTransporterIndent->isAutoRejected = 1;

 /* Proposed Indent */
 $objProposedIndent = new ProposedIndent();
 $objProposedIndent->customerno = $customerno;
 $objProposedIndent->proposedindentid = $indent['proposedindentid'];
 $objProposedIndent->depotid = $indent['depotid'];
 $objProposedIndent->factoryid = $indent['factoryid'];
 $objProposedIndent->hasTransporterAccepted = -1;

 /* UPDATE Proposed Indent */
 //$pitid = update_proposed_indent($objProposedIndent, $objProposedTransporterIndent);
 $objTms->update_proposed_indent($objProposedIndent);
 $objTms->update_pitmapping($objProposedTransporterIndent);

 /* Reject Proposed Indent */
 $rejection = new Object();
 $rejection->proposedindentid = $indent['proposedindentid'];
 $rejection->transporterid = $indent['proposed_transporterid'];
 $rejection->vehicletypeid = $indent['proposed_vehicletypeid'];
 $rejection->depotid = $indent['depotid'];
 $rejection->factoryid = $indent['factoryid'];
 $rejection->customerno = $customerno;
 $objTms->reject_proposed_indent($rejection);

 // <editor-fold defaultstate="collapsed" desc="COMMENTED OUT - SEND EMAIL AND SMS">
 /*
   $transporterlist = array();
   $objPI = new ProposedIndent();
   $objPI->customerno = $customerno;
   $objPI->proposedindentid = $indent['proposedindentid'];
   $transporterlist = get_assigned_transporter($objPI);
  */
 /* SEND MAIL AND SMS TO FACTORY OFFICIAL */
 /**
   $AdminEmail = '';
   $BCCEmail = '';
   $CCEmail = '';
   $factoryEmail = '';
   $factoryEmailArr = array();
   $factoryPhoneArr = array();
   $factoryemails = array();
   $email_Transporter = array();
   $objFactory = new Factory();
   $objFactory->customerno = $customerno;
   $objFactory->factoryid = $indent['factoryid'];
   $factoryemails = get_factory_officials($objFactory);
   if (isset($factoryemails) && !empty($factoryemails)) {
   foreach ($factoryemails as $emailFactory) {
   if (isset($emailFactory['email']) && trim($emailFactory['email']) != '') {
   $factoryEmailArr[] = trim($emailFactory['email']);
   }
   if (isset($emailFactory['phone']) && trim($emailFactory['phone']) != '') {
   $factoryPhoneArr[] = $emailFactory['phone'];
   }
   if ($emailFactory === end($factoryemails)) {
   $factoryEmail .= $emailFactory['email'];
   } else {
   $factoryEmail .= $emailFactory['email'] . ",";
   }
   }
   }
   $subject = "Indent update status - #" . $indent['proposedindentid'] . " ";
   $message = '';
   $message .='Hi<br/><br/>';
   $message .='Please follow Vehicle requirement schedule Update <br/><br/>';
   if (!empty($transporterlist)) {
   $message .= $indent['factoryname'] . ' to ' . $indent['depotname'] . ' ' . $indent['proposedvehiclecode'] . '-' . $indent['proposedvehicledescription'] . ' on ' . date('d-m-Y', strtotime($indent['date_required'])) . ' Not Confirmed in 24 hrs so Now indent Diverted To ' . $transporterlist[0]['transportername'] . '. <br/><br/>';
   } else {
   $message .= $indent['factoryname'] . ' to ' . $indent['depotname'] . ' ' . $indent['proposedvehiclecode'] . '-' . $indent['proposedvehicledescription'] . ' on ' . date('d-m-Y', strtotime($indent['date_required'])) . ' Rejected By All Transporters . <br/><br/>';
   }
   $message .= constants::Portallink;
   $message .= constants::Thanks;
   $message .= constants::CompanyName;
   $message .= constants::CompanyImage;
   $message .='';
   $msg = "Hi," . "\r\n" . $indent['factoryname'] . " to " . $indent['depotname'] . $indent['proposedvehiclecode'] . "-" . $indent['proposedvehicledescription'] . " on " . date('d-m-Y', strtotime($indent['date_required'])) . " Not Confirmed in 24 hrs so Now indent Diverted." . "\r\n" . constants::CompanyName;
   if (!empty($factoryEmailArr)) {
   $attachmentFilePath = '';
   $attachmentFileName = '';
   //sendMail($factoryEmailArr, $AdminEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName);
   }
   if (!empty($factoryPhoneArr)) {
   $smscount = getSMSCount($customerno);
   if ($smscount > 0) {
   $isSMSSent = 0;//sendSMS($factoryPhoneArr, $msg, $response);
   if ($isSMSSent) {
   updateSMSCount($smscount, $msg, $customerno);
   $todaysdate = date("Y-m-d H:i:s");
   foreach ($factoryPhoneArr as $phone) {
   $smsLogId = insertSMSLog($phone, $msg, $response, $indent['proposedindentid'], $isSMSSent, $customerno, $todaysdate);
   }
   }
   }
   }
  *
  */
 /* SEND MAIL AND SMS TO TRANSPORTER */
 /*
   $transporterEmailArr = array();
   $transporterPhoneArr = array();
   $objTransporter = new Transporter();
   $objTransporter->customerno = $customerno;
   $objTransporter->transporterid = $indent['proposed_transporterid'];
   $email_Transporter = get_transporter_officials($objTransporter);
   if (isset($email_Transporter) && !empty($email_Transporter)) {
   foreach ($email_Transporter as $emailTransporter) {
   if (isset($emailTransporter['email']) && trim($emailTransporter['email']) != '') {
   $transporterEmailArr[] = trim($emailTransporter['email']);
   }
   if (isset($emailTransporter['phone']) && trim($emailTransporter['phone']) != '') {
   $transporterPhoneArr[] = $emailTransporter['phone'];
   }
   }
   }
   $messageTransporter = '';
   $messageTransporter .='Hi<br/><br/>';
   $messageTransporter .='Please follow Vehicle requirement schedule Update <br/><br/>';
   $messageTransporter .= $indent['factoryname'] . ' to ' . $indent['depotname'] . ' ' . $indent['proposedvehiclecode'] . '-' . $indent['proposedvehicledescription'] . ' on ' . date('d-m-Y', strtotime($indent['date_required'])) . ' Not Confirmed from your side in 24 hrs so Now Indent Cancel . <br/><br/>';
   $messageTransporter .= constants::Portallink;
   $messageTransporter .= constants::Thanks;
   $messageTransporter .= constants::CompanyName;
   $messageTransporter .= constants::CompanyImage;
   $messageTransporter .='';
   $msgTransporter = "Hi," . "\r\n" . $indent['factoryname'] . " to " . $indent['depotname'] . "" . $indent['proposedvehiclecode'] . "-" . $indent['proposedvehicledescription'] . " on " . date('d-m-Y', strtotime($indent['date_required'])) . " Not Confirmed from your side in 24 hrs so Now Indent Cancel." . "\r\n" . constants::CompanyName;
   if (!empty($transporterEmailArr)) {
   $attachmentFilePath = '';
   $attachmentFileName = '';
   //sendMail($transporterEmailArr, $CCEmail, $BCCEmail, $subject, $messageTransporter, $attachmentFilePath, $attachmentFileName);
   }
   if (!empty($transporterPhoneArr)) {
   $smscount = getSMSCount($customerno);
   if ($smscount > 0) {
   $isSMSSent = 0;//sendSMS($transporterPhoneArr, $msgTransporter, $response);
   if ($isSMSSent) {
   updateSMSCount($smscount, $msgTransporter, $customerno);
   $todaysdate = date("Y-m-d H:i:s");
   foreach ($transporterPhoneArr as $phone) {
   $smsLogId = insertSMSLog($phone, $msgTransporter, $response, $indent['proposedindentid'], $isSMSSent, $customerno, $todaysdate);
   }
   }
   }
   }
  *
  */
 /* SEND MAIL AND SMS TO NEW ASSIGNED TRANSPORTER */
 /**
   $newtransporterEmailArr = array();
   $newtransporterPhoneArr = array();
   if (isset($transporterlist) && !empty($transporterlist)) {
   foreach ($transporterlist as $emailTransporter) {
   if (isset($emailTransporter['email']) && trim($emailTransporter['email']) != '') {
   $newtransporterEmailArr[] = trim($emailTransporter['email']);
   }
   if (isset($emailTransporter['phone']) && trim($emailTransporter['phone']) != '') {
   $newtransporterPhoneArr[] = $emailTransporter['phone'];
   }
   }
   }
   $newsubject = "Vehicle Requirement for Mondelez - #" . $transporterlist[0]['proposedindentid'] . " ";
   $newmessage = '';
   $newmessage .='Hi<br/><br/>';
   $newmessage .='Please follow Vehicle requirement schedule as below <br/><br/>';
   $newmessage .='<table border="1">';
   $newmessage .='<tr>';
   $newmessage .='<th>ID</th>';
   $newmessage .='<th>Vehicle Requirement Date</th>';
   $newmessage .='<th>Factory</th>';
   $newmessage .='<th>Depot</th>';
   $newmessage .='<th>Transporter</th>';
   $newmessage .='<th>Proposed Vehicle</th>';
   $newmessage .='</tr>';
   $newmessage .='<tr>';
   $newmessage .='<td>' . $transporterlist[0]['proposedindentid'] . '</td>';
   $newmessage .='<td>' . $transporterlist[0]['date_required'] . '</td>';
   $newmessage .='<td>' . $transporterlist[0]['factoryname'] . '</td>';
   $newmessage .='<td>' . $transporterlist[0]['depotname'] . '</td>';
   $newmessage .='<td>' . $transporterlist[0]['transportername'] . '</td>';
   $newmessage .='<td>' . $transporterlist[0]['proposedvehiclecode'] . '-' . $transporterlist[0]['proposedvehicledescription'] . '</td>';
   $newmessage .='</tr>';
   $newmessage .='</table><br/><br/>';
   $newmessage .= constants::Portallink;
   $newmessage .= constants::Thanks;
   $newmessage .= constants::CompanyName;
   $newmessage .= constants::CompanyImage;
   $newmessage .='';
   $newmsg = "Hi" . "\r\n" . "Please follow Vehicle requirement schedule as below." . "\r\n" . $transporterlist[0]['factoryname'] . " To " . $transporterlist[0]['depotname'] . " " . $transporterlist[0]['proposedvehiclecode'] . '-' . $transporterlist[0]['proposedvehicledescription'] . " On " . date('d-m-Y', strtotime($transporterlist[0]['date_required'])) . "\r\n" . constants::CompanyName;
   if (!empty($newtransporterEmailArr)) {
   $attachmentFilePath = '';
   $attachmentFileName = '';
   //sendMail($newtransporterEmailArr, $CCEmail, $BCCEmail, $newsubject, $newmessage, $attachmentFilePath, $attachmentFileName);
   }
   if (!empty($newtransporterPhoneArr)) {
   $smscount = getSMSCount($customerno);
   if ($smscount > 0) {
   $isSMSSent = 0;//sendSMS($newtransporterPhoneArr, $newmsg, $response);
   if ($isSMSSent) {
   updateSMSCount($smscount, $newmsg, $customerno);
   $todaysdate = date("Y-m-d H:i:s");
   foreach ($newtransporterPhoneArr as $phone) {
   $smsLogId = insertSMSLog($phone, $newmsg, $response, $transporterlist[0]['proposedindentid'], $isSMSSent, $customerno, $todaysdate);
   }
   }
   }
   }
  *
  */
 //</editor-fold>
}
?>