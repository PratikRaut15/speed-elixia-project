<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
include_once "class.phpmailer.php";
$serverPath = "http://www.speed.elixiatech.com";
//$serverPath = "http://localhost/speed";
$download = $serverPath . "/modules/download/report.php?q=";
$reportDate = new DateTime();
$date = '2019-10-01'; //$reportDate->format(speedConstants::DEFAULT_DATE);
$objUserManager = new UserManager();
$objCustomerManager = new CustomerManager();
$allCustomers = array(206, 64, 212); //$objCustomerManager->getcustomernos();
//$allCustomers = array(378); //$objCustomerManager->getcustomernos();

if (isset($allCustomers)) {
    foreach ($allCustomers as $customer) {
        $customerDetails = $objCustomerManager->getcustomerdetail_byid($customer);
        $arrUsers = $objUserManager->getadminforcustomer($customer);
        if (isset($arrUsers)) {
            foreach ($arrUsers as $user) {
                if ($user->email != '') {
                    $message = '';
                    $annexureReport = '';
                    $placehoders['{{REALNAME}}'] = $user->realname;
                    $placehoders['{{CUSTOMER}}'] = $customer;
                    $placehoders['{{ENCODEKEY}}'] = sha1($user->userkey);
                    $subject = "Annexure Report";
                    $timestamp = strtotime($date);
                    $annexurePdfUrl = $download . "annexure-pdf-$customer-$user->userkey-$timestamp";
                    $annexureXlsUrl = $download . "annexure-xls-$customer-$user->userkey-$timestamp";
                    $annexureReport .= '<tr>';
                    $annexureReport .= '<td><a href="' . $annexurePdfUrl . '" target="_blank">Download</a></td>';
                    $annexureReport .= '<td><a href="' . $annexureXlsUrl . '" target="_blank">Download</a></td>';
                    $annexureReport .= '</tr>';

                    if ($annexureReport != '') {
                        $html = file_get_contents('../emailtemplates/cronAnnexureReport.html');
                        $placehoders['{{DATA_ROWS}}'] = $annexureReport;
                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        $message .= $html;
                        $attachmentFilePath = '';
                        $attachmentFileName = '';
                        $CCEmail = '';
                        $BCCEmail = '';
                        $isMailSent = 1; //sendMailUtil(array($user->email), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
                        if (isset($isMailSent)) {
                            echo $message;
                        }
                    }
                }
            }
        }
    }
}
?>
