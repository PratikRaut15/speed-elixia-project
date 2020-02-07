<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once '../../lib/system/utilities.php';
require_once '../../lib/autoload.php';
$cronm = new CronManager();
$db = new DatabaseManager();
$objCustomer = new CustomerManager();
$smsStatus = new SmsStatus();
$customers = $cronm->getMonthlyProformaDetails();
$todaysdate = date('Y-m-d H:i:s');
$serverPath = "https://www.speed.elixiatech.com";
$download = $serverPath . "/modules/download/report.php?q=invoice-pdf-";
foreach ($customers as $customer) {
    $invoiceType = "Taxed";

    $link = 'Click <a href="' . $download . $customer['customerno'] . '-' . $customer['userkey'] . '-0&invoiceid=' . $customer['invoiceid'] . '">here</a> to download ' . $invoiceType . ' invoice';
    $message = file_get_contents('../emailtemplates/invoiceMailTemplate.html');
    $message = str_replace("{{LINK}}", $link, $message);
    if (!empty($customer['emailid'])) {
        $strCCMailIds = "";
        $strCCMailIds = "accounts@elixiatech.com"; /// remove comment
		$strBCCMailIds = "";
        $strBCCMailIds = "support@elixiatech.com";
        $subject = $invoiceType . " invoice for " . $customer['ledgername'] . "(Customer No." . $customer['customerno'] . ")";

        $attachmentFilePath = "";
        $attachmentFileName = "";
        $isTemplatedMessage = 1;
        $isemailsent = 1;
        $isemailsent = sendMailUtil(array($customer['emailid']), $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
//        $isemailsent = sendMailUtil(array("sanketsheth@elixiatech.com"), $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage);
        if ($isemailsent == 1) {
            $cronm->updateProformaProcessed($customer['invoiceid']);
            echo $message . "<br/>";
        }
        $objEmail = new stdClass();
        $objEmail->email = $customer['emailid'];
        $objEmail->subject = $subject;
        $objEmail->message = $message;
        $objEmail->vehicleid = 0;
        $objEmail->userid = $customer['userid'];
        $objEmail->type = 2;
        $objEmail->moduleid = speedConstants::MODULE_VTS;
        $objEmail->customerno = $customer['customerno'];
        $objEmail->isMailSent = $isemailsent;
        $objEmail->today = $todaysdate;
        $emailId = $objCustomer->insertCustomerEmailLog($objEmail);
    }

    $response = '';
    $message1 = "Dear Customer Your invoice has been generated and sent to your registered email ids.Please contact our customer support team in case you haven't received it.";
    $smsStatus->customerno = $customer['customerno'];
    $smsStatus->userid = $customer['userid'];
    $smsStatus->vehicleid = 0;
    $smsStatus->mobileno = $customer['mobileno'];
    $smsStatus->message = $message1;
    $smsStatus->cqid = 0;
    $smsstat = 0;
    $smsstat = $objCustomer->getSMSStatus($smsStatus);
    if ($smsstat == 0) {
        if ($customer['mobileno'] != '') {
            $isSMSSent = 1;
            $isSMSSent = sendSMSUtil($customer['mobileno'], $message1, $response);
            if ($isSMSSent == 1) {
                $objCustomer->sentSmsPostProcess($customer['customerno'], $customer['mobileno'], $message1, $response, $isSMSSent, $customer['userid'], 0, 1);
                $status = "smsSent";
            } else {
                $status = "smsNotSent";
            }
        }
    }
}
?>


