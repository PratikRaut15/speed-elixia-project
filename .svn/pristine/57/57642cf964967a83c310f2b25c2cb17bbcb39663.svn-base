<?php

//error_reporting(0);
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$reportId = speedConstants::REPORT_MONTHLY_ROUTE_SUMMARY_REPORT;
$today = new DateTime();
$reportDate = $today->sub(new DateInterval('P1M'));
$prev_month =   $reportDate->format('F Y');
$serverPath = "http://www.speed.elixiatech.com";
//$serverPath = "http://localhost/speed";
$download = $serverPath . "/modules/download/report.php?q=";


//<editor-fold defaultstate="collapsed" desc="Report & Time Specific Users">
$objReportUser = new stdClass();
$objReportUser->reportId = $reportId;
$objReportUser->reportTime = $today->format('H');
//$objReportUser->reportTime = 1;
$objUserManager = new UserManager();
$users = $objUserManager->getUsersForReport($objReportUser);
$customerUserArray = cronCustomerUsers($users);
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="Customer-User Loop To Send Mail">
$objCustomerManager = new CustomerManager();
$objUserManager = new UserManager();

if (isset($customerUserArray) && !empty($customerUserArray)) {
    foreach ($customerUserArray as $customer => $customerDetails) {
        $timezone = $objCustomerManager->timezone_name_cron(speedConstants::IST_TIMEZONE, $customer);
        date_default_timezone_set('' . $timezone . '');
        $customer_details = $objCustomerManager->getcustomerdetail_byid($customer);
        $vehicleManager = new VehicleManager($customer);
        foreach ($customerDetails as $userDetails) {
            foreach ($userDetails as $user) {

                if ($user['email'] != '') {
                    $message = "";
                    $tableRows = "";
                    $RouteReport = '';
                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{REPORT_DATE}}'] = $reportDate->format(speedConstants::DEFAULT_DATE);
                    $placehoders['{{CUSTOMER}}'] = $customer;
                    $placehoders['{{ENCODEKEY}}'] = sha1($user['userkey']);
                    $subject = "Stoppage Analysis Report For  " . $reportDate->format(speedConstants::DEFAULT_DATE);
                    $timestamp = strtotime($today->format(speedConstants::DEFAULT_DATE));

                    $objRouteManager = new RouteManager($customer);
                    $CustomerRoutes = $objRouteManager->get_route_fromcustomer($customer);
                    $arrRoutes = array();
                    if (isset($CustomerRoutes) && !empty($CustomerRoutes)) {
                        foreach ($CustomerRoutes as $route) {
                            $arrRoutes[] = $route->routeid;
                        }
                    }
                    //$allXLS = $download . "routesummary-xls-$customer-" . $user['userkey'] . "-$timestamp&g=-1";
                    //$RouteReport .= '<tr><td>All Groups</td><td><a href="' . $allXLS . '" target="_blank">Download</a></td></tr>';
                    //$RouteReport .= '<tr><td>&nbsp;</td><td>&nbsp;</td></tr>';
                    if($customer_details->use_tracking){
                        $vehicleManager = new VehicleManager($customer);
                        $objRouteManager = new RouteManager($customer);
                        $CustomerRoutes = $objRouteManager->get_route_fromcustomer($customer);
                        if (isset($CustomerRoutes) && !empty($CustomerRoutes)){
                            foreach($CustomerRoutes as $routedata){
                                $routeSummaryPdf = $routeSummaryXls = '';
                                //$routeSummaryPdfUrl = $download . "stoppage-pdf-$customer-" . $user['userkey'] . "-$timestamp&v=";
                                $routeSummaryXlsUrl = $download . "routesummary-xls-$customer-" . $user['userkey'] . "-$timestamp&routename=". $routedata->routename . "&g=";
                                //$routesumPdf = '<td><a href="' . $routeSummaryPdfUrl . $routedata['groupid'] . '" target="_blank">Download</a></td>';
                                $routesumXls = '<td><a href="' . $routeSummaryXlsUrl . $routedata->routeid . '" target="_blank">Download</a></td>';
                                $grp_name = ($routedata->routename != null) ? $routedata->routename : 'Not Allocated';
                                $RouteReport .= '<tr><td>' . $grp_name . '</td>' . $routesumXls . '</tr>';
                            }
                        }
                    }

                    if ($RouteReport != '') {
                        $html = file_get_contents('../emailtemplates/cronMonthlyRouteSummaryReport.html');
                        $placehoders['{{DATA_ROWS}}'] = $RouteReport;
                        $placehoders['{{PREV_MONTH_NAME}}'] = $prev_month;

                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        $toId = $user['email'];
                        $message .= $html;
                        $attachmentFilePath = '';
                        $attachmentFileName = '';
                        $CCEmail = '';
                        $BCCEmail = 'support@elixiatech.com';
                        $isMailSent = sendMailUtil(array($toId), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
                        if (isset($isMailSent)) {
                            echo $message;
                        }
                    }
                }
            }
        }
    }
}
//</editor-fold>
