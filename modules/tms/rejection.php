<?php

include 'tms_function.php';
require_once 'class/TMSUtility.php';
$customerno = 116;
$userid = 485;
$date = date("Y-m-d H:m:s");
$objTms = new TMS($customerno, $userid);
$objProposedIndent = new ProposedIndent();
$objProposedIndent->customerno = $customerno;
$objProposedIndent->date_required = $date;
$pendingindent = $objTms->get_pending_indent($objProposedIndent);
foreach ($pendingindent as $indent) {
    $objProposedIndent = new ProposedIndent();
    $objProposedIndent->customerno = $customerno;
    $objProposedIndent->proposedindentid = $indent['proposedindentid'];
    $objProposedIndent->remark = 'Indent Expired';

    $pitid = delete_proposed_indent($objProposedIndent);

    /* SEND MAIL AND SMS */
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
    $message .='Below is the vehicle confirmation status from Transporter <br/><br/>';
    $message .= $indent['factoryname'] . ' to ' . $indent['depotname'] . ' ' . $indent['vehiclecode'] . ' on ' . date('d-m-Y', strtotime($indent['date_required'])) . ' Not confirmed by any Transporter / Expired. <br/><br/>';

    $message .= constants::Portallink;
    $message .= constants::Thanks;
    $message .= constants::CompanyName;
    $message .= constants::CompanyImage;
    $message .='';

    $msg = 'Hi,\r\n' . $indent['factoryname'] . ' to ' . $indent['depotname'] . ' ' . $indent['vehiclecode'] . ' on ' . date('d-m-Y', strtotime($indent['date_required'])) . ' Not confirmed by any Transporter / Expired \r\n\r\n'.constants::CompanyName.'';



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
}
?>