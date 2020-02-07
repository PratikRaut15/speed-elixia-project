<?php

include 'tms_function.php';
require_once 'class/TMSUtility.php';
$customerno = 116;
$userid = 485;
$date = date("Y-m-d H:m:s", strtotime('-24 hours', time()));
$datereminder = date("Y-m-d H:m:s", strtotime('-3 hours', time()));
$userid = $objTms = new TMS($customerno, $userid);
$objProposedIndent = new ProposedIndent();
$objProposedIndent->customerno = $customerno;
$objProposedIndent->date_required = $date;
$pendingindent = $objTms->get_autoreject_indent($objProposedIndent);



foreach ($pendingindent as $indent) {

    /* Proposed Indent Transporter Mapping */
    $objProposedTransporterIndent = new ProposedIndentTransporterMapping();
    $objProposedTransporterIndent->customerno = $_SESSION['customerno'];
    $objProposedTransporterIndent->proposedindentid = $indent['proposedindentid'];
    $objProposedTransporterIndent->vehicleno = $indent['vehicleno'];
    $objProposedTransporterIndent->actual_vehicletypeid = $indent['actual_vehicletypeid'];
    $objProposedTransporterIndent->proposed_vehicletypeid = $indent['proposed_transporterid'];
    $objProposedTransporterIndent->proposed_transporterid = $indent['proposed_transporterid'];
    $objProposedTransporterIndent->drivermobileno = $indent['drivermobileno'];
    $objProposedTransporterIndent->pitmappingid = $indent['pitmappingid'];
    $objProposedTransporterIndent->remarks = $indent['remarks'];
    $objProposedTransporterIndent->isAccepted = -1;

    /* Proposed Indent */
    $objProposedIndent = new ProposedIndent();
    $objProposedIndent->customerno = $_SESSION['customerno'];
    $objProposedIndent->proposedindentid = $indent['proposedindentid'];
    $objProposedIndent->depotid = $indent['depotid'];
    $objProposedIndent->factoryid = $indent['factoryid'];
    $objProposedIndent->hasTransporterAccepted = -1;


    /* UPDATE Proposed Indent */
    $pitid = update_proposed_indent($objProposedIndent, $objProposedTransporterIndent);

    /* Reject Proposed Indent */
    $rejection = new Object();
    $rejection->proposedindentid = $indent['proposedindentid'];
    $rejection->transporterid = $indent['proposed_transporterid'];
    $rejection->vehicletypeid = $indent['proposed_transporterid'];
    $rejection->depotid = $indent['depotid'];
    $rejection->factoryid = $indent['factoryid'];
    $rejection->customerno = $customerno;
    reject_proposed_indent($rejection);


    $objPI = new ProposedIndent();
    $objPI->customerno = $customerno;
    $objPI->proposedindentid = $indent['proposedindentid'];
    $transporterlist = get_assigned_transporter($objPI);


    /* SEND MAIL AND SMS TO FACTORY OFFICIAL */
    $factoryEmailArr = array();
    $factoryPhoneArr = array();
    $AdminEmailArr = array();
    $BCCEmailArr = array();

    $objFactory = new Factory();
    $objFactory->customerno = $customerno;
    $objFactory->factoryid = $indent['factoryid'];
    $factoryemails = get_factory_officials($objFactory);
    foreach ($factoryemails as $emailFactory) {
        $factoryEmailArr[] = $emailFactory['email'];
        $factoryPhoneArr[] = $emailFactory['phone'];
    }

    $AdminEmailArr[] = explode(',', constants::adminemail);
    $BCCEmailArr[] = explode(',', constants::bccemail);

    $subject = "Indent update status.";
    $message = '';
    $message .='Hi<br/><br/>';
    $message .='Please follow Vehicle requirement schedule Update <br/><br/>';

    if (!empty($transporterlist)) {
        $message .= $indent['factoryname'] . ' to ' . $indent['depotname'] . ' ' . $indent['vehiclecode'] . ' on ' . date('d-m-Y', strtotime($indent['date_required'])) . ' Not Confirmed in 24 hrs so Now indent Diverted To ' . $transporterlist[0]['transportername'] . '. <br/><br/>';
    } else {
        $message .= $indent['factoryname'] . ' to ' . $indent['depotname'] . ' ' . $indent['vehiclecode'] . ' on ' . date('d-m-Y', strtotime($indent['date_required'])) . ' Rejected By All Transporters . <br/><br/>';
    }


    $message .= constants::Portallink;
    $message .= constants::Thanks;
    $message .= constants::CompanyName;
    $message .= constants::CompanyImage;
    $message .='';

    $msg = 'Hi,\r\n' . $indent['factoryname'] . ' to ' . $indent['depotname'] . ' ' . $indent['vehiclecode'] . ' on ' . date('d-m-Y', strtotime($indent['date_required'])) . ' Not Confirmed in 24 hrs so Now indent Diverted.\r\n\r\n' . constants::CompanyName . '';
    if (!empty($factoryEmailArr)) {
        $attachmentFilePath = '';
        $attachmentFileName = '';
        sendMail($factoryEmailArr, $AdminEmailArr, $BCCEmailArr, $subject, $message, $attachmentFilePath, $attachmentFileName);
    }

    if (!empty($factoryPhoneArr)) {
        $smscount = getSMSCount($customerno);
        if ($smscount > 0) {
            $isSMSSent = 0;//TMSUtility::sendSMS($factoryPhoneArr, $msg, $response);
            if ($isSMSSent) {
                updateSMSCount($smscount, $msg, $customerno);
            }
        }
    }


    /* SEND MAIL AND SMS TO TRANSPORTER */
    $transporterEmailArr = array();
    $transporterPhoneArr = array();
    
    $CCEmailArr = array_merge($factoryEmailArr, $AdminEmailArr);
    
    $objTransporter = new Transporter();
    $objTransporter->customerno = $customerno;
    $objTransporter->transporterid = $indent['proposed_transporterid'];
    $email_Transporter = get_transporter($objTransporter);
    foreach ($email_Transporter as $emailTransporter) {
        $transporterEmailArr[] = $emailTransporter['transportermail'];
        $transporterPhoneArr[] = $emailTransporter['transportermobileno'];
    }

    $messageTransporter = '';
    $messageTransporter .='Hi<br/><br/>';
    $messageTransporter .='Please follow Vehicle requirement schedule Update <br/><br/>';
    $messageTransporter .= $indent['factoryname'] . ' to ' . $indent['depotname'] . ' ' . $indent['vehiclecode'] . ' on ' . date('d-m-Y', strtotime($indent['date_required'])) . ' Not Confirmed from your side in 24 hrs so Now Indent Cancel . <br/><br/>';

    $messageTransporter .= constants::Portallink;
    $messageTransporter .= constants::Thanks;
    $messageTransporter .= constants::CompanyName;
    $messageTransporter .= constants::CompanyImage;
    $messageTransporter .='';

    $msgTransporter = 'Hi,\r\n' . $indent['factoryname'] . ' to ' . $indent['depotname'] . ' ' . $indent['vehiclecode'] . ' on ' . date('d-m-Y', strtotime($indent['date_required'])) . ' Not Confirmed from your side in 24 hrs so Now Indent Cancel.\r\n\r\n' . constants::CompanyName . '';

    if (!empty($transporterEmailArr)) {
        $attachmentFilePath = '';
        $attachmentFileName = '';
        sendMail($transporterEmailArr, $CCEmailArr, $BCCEmailArr, $subject, $messageTransporter, $attachmentFilePath, $attachmentFileName);
    }

    if (!empty($transporterPhoneArr)) {
        $smscount = getSMSCount($customerno);
        if ($smscount > 0) {
            $isSMSSent = 0;//TMSUtility::sendSMS($transporterPhoneArr, $msgTransporter, $response);
            if ($isSMSSent) {
                updateSMSCount($smscount, $msgTransporter, $customerno);
            }
        }
    }


    /* SEND MAIL AND SMS TO NEW ASSIGNED TRANSPORTER */
    $newtransporterEmailArr = array();
    $newtransporterPhoneArr = array();
    $newtransporterEmailArr[] = $transporterlist[0]['transportermail'];
    $newtransporterPhoneArr[] = $transporterlist[0]['transportermobileno'];
    
    $newsubject = "Vehicle Requirement for Mondelez";
    $newmessage = '';
    $newmessage .='Hi<br/><br/>';
    $newmessage .='Please follow Vehicle requirement schedule as below <br/><br/>';
    $newmessage .='<table border="1">';
    $newmessage .='<tr>';
    $newmessage .='<th>ID</th>';
    $newmessage .='<th>Vehcile Requirement Date</th>';
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
    $newmessage .='<td>' . $transporterlist[0]['vehiclecode'] . '</td>';
    $newmessage .='</tr>';
    $newmessage .='<table><br/><br/>';
    $newmessage .= constants::Portallink;
    $newmessage .= constants::Thanks;
    $newmessage .= constants::CompanyName;
    $newmessage .= constants::CompanyImage;
    $newmessage .='';

    $newmsg = 'Hi\r\nPlease follow Vehicle requirement schedule as below\r\n' . $transporterlist[0]['factoryname'] . ' To ' . $transporterlist[0]['depotname'] . ' ' . $transporterlist[0]['vehiclecode'] . ' On ' . date('d-m-Y', strtotime($transporterlist[0]['date_required'])) . '\r\n\r\n' . constants::CompanyName . '';


    if (!empty($newtransporterEmailArr)) {
        $attachmentFilePath = '';
        $attachmentFileName = '';
        sendMail($newtransporterEmailArr, $CCEmailArr, $BCCEmailArr, $newsubject, $newmessage, $attachmentFilePath, $attachmentFileName);
    }
    if (!empty($newtransporterPhoneArr)) {
        $smscount = getSMSCount($customerno);
        if ($smscount > 0) {
            $isSMSSent = 0;//TMSUtility::sendSMS($newtransporterEmailArr, $newmsg, $response);
            if ($isSMSSent) {
                updateSMSCount($smscount, $newmsg, $customerno);
            }
        }
    }
}


$objIndent = new ProposedIndent();
$objIndent->customerno = $customerno;
$objIndent->date_required = $datereminder;
$indents = $objTms->get_autoreject_indent($objIndent);
foreach ($indents as $indent) {

    /* SEND MAIL AND SMS TO TRANSPORTER */
    $factoryEmailArr = array();
    $factoryPhoneArr = array();
    $BCCEmailArr = array();
    $transporterEmailArr = array();
    $transporterPhoneArr = array();

    $objFactory = new Factory();
    $objFactory->customerno = $customerno;
    $objFactory->factoryid = $indent['factoryid'];
    $factoryemails = get_factory_officials($objFactory);
    foreach ($factoryemails as $emailFactory) {
        $factoryEmailArr[] = $emailFactory['email'];
        $factoryPhoneArr[] = $emailFactory['phone'];
    }

    $factoryEmailArr[] = explode(',', constants::adminemail);
    $BCCEmailArr[] = explode(',', constants::bccemail);


    $objTransporter = new Transporter();
    $objTransporter->customerno = $customerno;
    $objTransporter->transporterid = $indent['proposed_transporterid'];
    $email_Transporter = get_transporter($objTransporter);
    foreach ($email_Transporter as $emailTransporter) {
        $transporterEmailArr[] = $emailTransporter['transportermail'];
        $transporterPhoneArr[] = $emailTransporter['transportermobileno'];
    }

    $subject = "Indent update status.";

    $messageTransporter = '';
    $messageTransporter .='Hi<br/><br/>';
    $messageTransporter .='Reminder<br/>';
    $messageTransporter .='Please follow Vehicle requirement schedule as below <br/><br/>';
    $messageTransporter .= $indent['factoryname'] . ' to ' . $indent['depotname'] . ' ' . $indent['vehiclecode'] . ' on ' . date('d-m-Y', strtotime($indent['date_required'])) . '.<br/><br/>';

    $messageTransporter .= constants::Portallink;
    $messageTransporter .= constants::Thanks;
    $messageTransporter .= constants::CompanyName;
    $messageTransporter .= constants::CompanyImage;
    $messageTransporter .='';

    $msgTransporter = 'Hi,\r\n' . $indent['factoryname'] . ' to ' . $indent['depotname'] . ' ' . $indent['vehiclecode'] . ' on ' . date('d-m-Y', strtotime($indent['date_required'])) . '.\r\n\r\n' . constants::CompanyName . '';

    if (!empty($transporterEmailArr)) {
        $attachmentFilePath = '';
        $attachmentFileName = '';
        sendMail($transporterEmailArr, $factoryEmailArr, $BCCEmailArr, $subject, $messageTransporter, $attachmentFilePath, $attachmentFileName);
    }

    if (!empty($transporterPhoneArr)) {
        $smscount = getSMSCount($customerno);
        if ($smscount > 0) {
            $isSMSSent = 0;//TMSUtility::sendSMS($transporterPhoneArr, $msgTransporter, $response);
            if ($isSMSSent) {
                updateSMSCount($smscount, $msgTransporter, $customerno);
            }
        }
    }
}
?>