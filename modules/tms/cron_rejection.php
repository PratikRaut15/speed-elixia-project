<?php

include 'tms_function.php';

$customerno = 116;
$userid = 485;
$date = date("Y-m-d H:m:s");
//$date = date("2015-11-06 00:00:00");
$objTms = new TMS($customerno, $userid);
$objProposedIndent = new ProposedIndent();
$objProposedIndent->customerno = $customerno;
$objProposedIndent->date_required = $date;
$pendingindent = $objTms->get_pending_indent($objProposedIndent);
//print_r($pendingindent);
foreach ($pendingindent as $indent) {


 $objProposedTransporterIndent = new ProposedIndentTransporterMapping();
 $objProposedTransporterIndent->customerno = $customerno;
 $objProposedTransporterIndent->proposedindentid = $indent['proposedindentid'];
 $objProposedTransporterIndent->vehicleno = $indent['vehicleno'];
 $objProposedTransporterIndent->actual_vehicletypeid = $indent['actual_vehicletypeid'];
 $objProposedTransporterIndent->proposed_vehicletypeid = $indent['proposed_vehicletypeid'];
 $objProposedTransporterIndent->proposed_transporterid = $indent['proposed_transporterid'];
 $objProposedTransporterIndent->drivermobileno = $indent['drivermobileno'];
 $objProposedTransporterIndent->pitmappingid = $indent['pitmappingid'];
 $objProposedTransporterIndent->remarks = 'Indent Expired';
 $objProposedTransporterIndent->isAccepted = $indent['isAccepted'];
 $objProposedTransporterIndent->isAutoRejected = 1;
 $objTms->update_pitmapping($objProposedTransporterIndent);



 //$pitid = $objTms->delete_proposed_indent($objProposedIndent);
 // <editor-fold defaultstate="collapsed" desc="COMMENTED OUT - SEND EMAIL AND SMS">
 /* SEND MAIL AND SMS */
 /**
   $AdminEmail = '';
   $BCCEmail = '';
   $factoryEmailArr = array();
   $factoryPhoneArr = array();
   $factoryemails = array();


   $objFactory = new Factory();
   $objFactory->customerno = $customerno;
   $objFactory->factoryid = $indent['factoryid'];
   $factoryemails = get_factory_officials($objFactory);
   if (isset($factoryemails) && !empty($factoryemails)) {
   foreach ($factoryemails as $emailTransporter) {
   if (isset($emailTransporter['email']) && trim($emailTransporter['email']) != '') {
   $factoryEmailArr[] = trim($emailTransporter['email']);
   }
   if (isset($emailTransporter['phone']) && trim($emailTransporter['phone']) != '') {
   $factoryPhoneArr[] = $emailTransporter['phone'];
   }
   }
   }

   $AdminEmail = constants::adminemail;
   $BCCEmail = constants::bccemail;

   $subject = "Indent update status - #" . $indent['proposedindentid'] . "";
   $message = '';
   $message .='Hi<br/><br/>';
   $message .='Below is the vehicle confirmation status from Transporter <br/><br/>';
   $message .= $indent['factoryname'] . ' to ' . $indent['depotname'] . ' ' . $indent['proposedvehiclecode'] . '-' . $indent['proposedvehicledescription'] . ' on ' . date('d-m-Y', strtotime($indent['date_required'])) . ' Not confirmed by any Transporter / Expired. <br/><br/>';

   $message .= constants::Portallink;
   $message .= constants::Thanks;
   $message .= constants::CompanyName;
   $message .= constants::CompanyImage;
   $message .='';

   $msg = "Hi," . "\r\n" . $indent['factoryname'] . " to " . $indent['depotname'] . $indent['proposedvehiclecode'] . "-" . $indent['proposedvehicledescription'] . " on " . date('d-m-Y', strtotime($indent['date_required'])) . " Not confirmed by any Transporter / Expired" . "\r\n" . constants::CompanyName;



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
 //</editor-fold>
}
?>