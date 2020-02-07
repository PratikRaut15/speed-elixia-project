<style type='text/css'>
    body{
      font-family:Arial;
      font-size: 11pt;
    }
    table{
      text-align: center;
      border-right:1px solid black;
      border-bottom:1px solid black;
      border-collapse:collapse;
      font-family:Arial;
      font-size: 10pt;
      width: 50%;
    }
    td, th{
      border-left:1px solid black;
      border-top:1px solid black;
    }
    .colHeading{
      background-color: #D6D8EC;
    }
    span{
      font-weight:bold;
    }
</style>
<?php
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$reportId = speedConstants::REPORT_GENERIC;
$today = new DateTime();
$reportDate = $today->sub(new DateInterval('P1D'));
$date = $reportDate->format(speedConstants::DEFAULT_DATE);
$serverPath = "http://www.speed.elixiatech.com";
//$serverpath = "http://localhost/speed";
$download = $serverPath . "/modules/download/report.php?q=";
$routehistpath = $serverPath . "/modules/reports/reports.php";
//<editor-fold defaultstate="collapsed" desc="Report & Time Specific Users">
$objReportUser = new stdClass();
$objReportUser->reportId = $reportId;
$objReportUser->reportTime = $today->format('H');
$objUserManager = new UserManager();
$users = $objUserManager->getUsersForReport($objReportUser);
$array = json_decode(json_encode($users), true);
$customerUserArray = array_reduce($array, function ($result, $currentItem) {
    if (isset($result[$currentItem['customerno']])) {
        $user = array();
        $user['userid'] = $currentItem['userid'];
        $user['email'] = $currentItem['email'];
        $user['realname'] = $currentItem['realname'];
        $user['userkey'] = $currentItem['userkey'];
        $user['reportId'] = $currentItem['reportId'];
        $user['reportTime'] = $currentItem['reportTime'];
        $result[$currentItem['customerno']]['users'][] = $user;
    } else {
        $result[$currentItem['customerno']] = array();
        $user = array();
        $user['userid'] = $currentItem['userid'];
        $user['email'] = $currentItem['email'];
        $user['realname'] = $currentItem['realname'];
        $user['userkey'] = $currentItem['userkey'];
        $user['reportId'] = $currentItem['reportId'];
        $user['reportTime'] = $currentItem['reportTime'];
        $result[$currentItem['customerno']]['users'][] = $user;
    }
    return $result;
});
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="Customer-User Loop To Send Mail">
$objCustomerManager = new CustomerManager();
$objUserManager = new UserManager();
$grouplist = "";
if (isset($customerUserArray) && !empty($customerUserArray)) {
    $cntMailSent = 0;
    $cntMailNotSent = 0;
    foreach ($customerUserArray as $customer => $customerDetails) {
        $timezone = $objCustomerManager->timezone_name_cron('Asia/Kolkata', $customer);
        date_default_timezone_set('' . $timezone . '');
        $customer_details = $objCustomerManager->getcustomerdetail_byid($customer);
        $vehicleManager = new VehicleManager($customer);
        foreach ($customerDetails as $userDetails) {
            foreach ($userDetails as $user) {
                if ($user['email'] != '') {
                    $custom = $objUserManager->get_custom($customer);
                    $customname = 'AC Sensor';
                    if ($custom != null) {
                        if ($custom->usecustom == 1 && $custom->custom_id == 1 && $custom->customname != '') {
                            $customname = $custom->customname;
                        }
                    }
                    $message = '';
                    $timestamp = strtotime($today->format(speedConstants::DEFAULT_DATE));
                    $summary_pdf_url = $download . "summary-pdf-$customer-" . $user['userkey'] . "-$timestamp";
                    $summary_xls_url = $download . "summary-xls-$customer-" . $user['userkey'] . "-$timestamp";
                    $genset_pdf_url = $download . "genset-pdf-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    $genset_xls_url = $download . "genset-xls-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    $travel_pdf_url = $download . "travel-pdf-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    $travel_xls_url = $download . "travel-xls-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    $stoppage_pdf_url = $download . "stoppage-pdf-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    $stoppage_xls_url = $download . "stoppage-xls-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    /* temperature pdf-csv url */
                    $temp_pdf_url = $download . "temperature-pdf-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    $temp_xls_url = $download . "temperature-xls-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    /* Warehouse  temperature pdf-csv url */
                    $wh_temp_pdf_url = $download . "wh_temperature-pdf-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    $wh_temp_xls_url = $download . "wh_temperature-xls-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    /* Warehouse  temperature - humidity pdf-csv url */
                    $tempHumidityPdfUrl = $download . "wh_temperaturehumidity-pdf-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    $tempHumidityXlsUrl = $download . "wh_temperaturehumidity-xls-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    /* Overspeed pdf-csv url */
                    $overspeed_pdf_url = $download . "overspeed-pdf-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    $overspeed_xls_url = $download . "overspeed-xls-$customer-" . $user['userkey'] . "-$timestamp&v=";
                    /* Exception Report pdf-csv url */
                    $exception_pdf_url = $download . "exception-pdf-$customer-" . $user['userkey'] . "-$timestamp";
                    $exception_xls_url = $download . "exception-xls-$customer-" . $user['userkey'] . "-$timestamp";
                    $group_header = $pdf_header = $csv_header = $summary_pdf = $summary_csv = $routehistory_link = $exception_pdf = $exception_csv = $os_pdf = $os_excel = '';
                    $header_span = 1;
                    $groupspan = 0;
                    $groupclmn = "";
                    $encodekey = sha1($user['userkey']);
                    $summary_pdf = '<a href="' . $summary_pdf_url . '" target="_blank">Download</a>';
                    $summary_csv = '<a href="' . $summary_xls_url . '" target="_blank">Download</a>';
                    $os_pdf = '<a href="' . $overspeed_pdf_url . '" target="_blank">Download</a>';
                    $os_excel = '<a href="' . $overspeed_xls_url . '" target="_blank">Download</a>';
                    $exception_pdf = '<a href="' . $exception_pdf_url . '" target="_blank">Download</a>';
                    $exception_csv = '<a href="' . $exception_xls_url . '" target="_blank">Download</a>';
                    $routehistory_link = "<b>Route History</b>";
                    $travel_history_report = $sensor_report = $temp_report = $wh_temp_report = $tempHumidityReport = $stoppage_analysis_report = '';
                    $userGroups = $objUserManager->get_groups_fromuser($customer, $user['userid']);
                    if (isset($userGroups) && !empty($userGroups)) {
                        $groupspan++;
                        $groupclmn = "<td><b>Group Name</b></td>";
                        foreach ($userGroups as $group) {
                            $summary_pdf1 = '<a href="' . $summary_pdf_url . "-" . $group->groupid . '" target="_blank">Download</a>';
                            $summary_csv2 = '<a href="' . $summary_xls_url . "-" . $group->groupid . '" target="_blank">Download</a>';
                            $grouplist .= "<tr><td>" . $group->groupname . "</td>" . $summary_pdf1 . $summary_csv2 . "</tr>";
                            if ($customer_details->use_tracking) {
                                $vehicles = $vehicleManager->get_all_vehicles_by_group(array($group->groupid), $isWarehouse = 0);
                                if (isset($vehicles) && !empty($vehicles)) {
                                    foreach ($vehicles as $vehicle) {
                                        $pdf_h = $csv_h = $pdf_s = $csv_s = $pdf_t = $csv_t = $pdf_stoppage = $csv_stoppage = '';
                                        $pdf_h = '<td><a href="' . $travel_pdf_url . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                        $csv_h = '<td><a href="' . $travel_xls_url . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                        $pdf_s = '<td><a href="' . $genset_pdf_url . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                        $csv_t = '<td><a href="' . $temp_xls_url . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                        $pdf_t = '<td><a href="' . $temp_pdf_url . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                        $csv_s = '<td><a href="' . $genset_xls_url . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                        $pdf_stoppage = '<td><a href="' . $stoppage_pdf_url . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                        $csv_stoppage = '<td><a href="' . $stoppage_xls_url . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                        $grp_name = ($vehicle->groupname != null) ? $vehicle->groupname : 'Not Allocated';
                                        $routehisttd = "<td style='text-align:center;'><a href='$routehistpath?userkey=" . $user['userkey'] . "&vehicleid=$vehicle->vehicleid&date=$date&report=view' target='_blank'>View</a></td>";
                                        $travel_history_report .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $pdf_h . $csv_h . $routehisttd . '</tr>';
                                        $stoppage_analysis_report .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $pdf_stoppage . $csv_stoppage  . '</tr>';
                                        if ($customer_details->temp_sensors >= 1) {
                                            $temp_report .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $pdf_t . $csv_t . '</tr>';
                                        }
                                        if ($vehicle->acsensor == '1') {
                                            $sensor_report .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $pdf_s . $csv_s . '</tr>';
                                        }
                                    }
                                }
                            }
                            if ($customer_details->use_warehouse) {
                                $warehouses = $vehicleManager->get_all_vehicles_by_group(array($group->groupid), $isWarehouse = 1);
                                if (isset($warehouses) && !empty($warehouses)) {
                                    foreach ($warehouses as $vehicle) {
                                        $wh_pdf_t = $wh_csv_t = $tempHumidityPdf = $tempHumidityCsv = '';
                                        $wh_pdf_t = '<td><a href="' . $wh_temp_pdf_url . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                        $tempHumidityPdf = '<td><a href="' . $tempHumidityPdfUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                        $wh_csv_t = '<td><a href="' . $wh_temp_xls_url . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                        $tempHumidityCsv = '<td><a href="' . $tempHumidityXlsUrl . $vehicle->vehicleid . '" target="_blank">Download</a></td>';
                                        $grp_name = ($vehicle->groupname != null) ? $vehicle->groupname : 'Not Allocated';
                                        if ($customer_details->temp_sensors >= 1) {
                                            $wh_temp_report .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $wh_pdf_t . $wh_csv_t . '</tr>';
                                        }
                                        if ($customer_details->use_humidity == 1) {
                                            $tempHumidityReport .= '<tr><td>' . $vehicle->vehicleno . '</td><td>' . $grp_name . '</td>' . $tempHumidityPdf . $tempHumidityCsv . '</tr>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $content = '
                        Dear ' . $user['realname'] . ' ,<br><p></p></br>
                        Greetings from Elixia Tech!<br/><br/>
                        Customer No: <b>' . $customer . '</b><br/><br/>
                        Please find the auto-generated Summary Report for all your vehicles. In case of any queries, you may contact us at 25137470 / 71. Thank you for choosing Elixia Tech!<br/><br/>';
                    $content .= "<center>Reports</center><hr/><br/>";
                    $content .= "<table>";
                    $content .= "<tr>";
                    $content .= "<th class='colHeading'>Report</th>";
                    $content .= "<th class='colHeading'>PDF</th>";
                    $content .= "<th class='colHeading'>CSV</th>";
                    $content .= "</tr>";
                    $content .= "<tr>";
                    $content .= "<td>Summary Report</td>";
                    $content .= "<td>" . $summary_pdf . "</td>";
                    $content .= "<td>" . $summary_csv . "</td>";
                    $content .= "</tr>";
                    $content .= "<tr>";
                    $content .= "<td>Exception Report</td>";
                    $content .= "<td>" . $exception_pdf . "</td>";
                    $content .= "<td>" . $exception_csv . "</td>";
                    $content .= "</tr>";
                    $content .= "<tr>";
                    $content .= "<td>Overspeed Report</td>";
                    $content .= "<td>" . $os_pdf . "</td>";
                    $content .= "<td>" . $os_excel . "</td>";
                    $content .= "</tr>";
                    $content .= "</table><br/>";
                    $content .= "<center>Vehicle Wise Reports</center><hr/><br/>";
                    if ($temp_report != '') {
                        $content .= "<table>";
                        $content .= "<tr><th colspan='4' class='colHeading'>Vehicle Temperature Report</th></tr>";
                        $content .= "<tr>";
                        $content .= "<th class='colHeading'>Vehicle No</th>";
                        $content .= "<th class='colHeading'>Group Name</th>";
                        $content .= "<th class='colHeading'>PDF</th>";
                        $content .= "<th class='colHeading'>CSV</th>";
                        $content .= "</tr>";
                        $content .= $temp_report;
                        $content .= "</table><br/>";
                    }
                    if ($wh_temp_report != '') {
                        $content .= "<table border=1>";
                        $content .= "<tr><th colspan='4' class='colHeading'>Warehouse Temperature Report</th></tr>";
                        $content .= "<tr>";
                        $content .= "<th class='colHeading'>Warehouse</th>";
                        $content .= "<th class='colHeading'>Group Name</th>";
                        $content .= "<th class='colHeading'>PDF</th>";
                        $content .= "<th class='colHeading'>CSV</th>";
                        $content .= "</tr>";
                        $content .= $wh_temp_report;
                        $content .= "</table><br/>";
                    }
                    if ($tempHumidityReport != '') {
                        $content .= "<table border=1>";
                        $content .= "<tr><th colspan='4' class='colHeading'>Warehouse Humidity And Temperature Report</th></tr>";
                        $content .= "<tr>";
                        $content .= "<th class='colHeading'>Warehouse</th>";
                        $content .= "<th class='colHeading'>Group Name</th>";
                        $content .= "<th class='colHeading'>PDF</th>";
                        $content .= "<th class='colHeading'>CSV</th>";
                        $content .= "</tr>";
                        $content .= $tempHumidityReport;
                        $content .= "</table><br/>";
                    }
                    if ($travel_history_report != '') {
                        $content .= "<table border=1>";
                        $content .= "<tr><th colspan='5' class='colHeading'>Travel History Report</th></tr>";
                        $content .= "<tr>";
                        $content .= "<th class='colHeading'>Vehicle No</th>";
                        $content .= "<th class='colHeading'>Group Name</th>";
                        $content .= "<th class='colHeading'>PDF</th>";
                        $content .= "<th class='colHeading'>CSV</th>";
                        $content .= "<th class='colHeading'>Route</th>";
                        $content .= "</tr>";
                        $content .= $travel_history_report;
                        $content .= "</table><br/>";
                    }
                    if ($stoppage_analysis_report != '') {
                        $content .= "<table border=1>";
                        $content .= "<tr><th colspan='4' class='colHeading'>Stoppage Analysis Report (6 Hrs Interval Time)</th></tr>";
                        $content .= "<tr>";
                        $content .= "<th class='colHeading'>Vehicle No</th>";
                        $content .= "<th class='colHeading'>Group Name</th>";
                        $content .= "<th class='colHeading'>PDF</th>";
                        $content .= "<th class='colHeading'>CSV</th>";
                        $content .= "</tr>";
                        $content .= $stoppage_analysis_report;
                        $content .= "</table><br/>";
                    }
                    if ($sensor_report != '') {
                        $content .= "<table border=1>";
                        $content .= "<tr><th colspan='4' class='colHeading'>" . $customname . " Report</th></tr>";
                        $content .= "<tr>";
                        $content .= "<th class='colHeading'>Vehicle No</th>";
                        $content .= "<th class='colHeading'>Group Name</th>";
                        $content .= "<th class='colHeading'>PDF</th>";
                        $content .= "<th class='colHeading'>CSV</th>";
                        $content .= "</tr>";
                        $content .= $sensor_report;
                        $content .= "</table><br/>";
                    }
                    $content .= '<br/><font size="smaller">To unsubscribe, please uncheck your alerts <a href="http://www.speed.elixiatech.com/modules/api/?action=login_userkey_unsub&key=' . $encodekey . '">here</a></font>';
                    if ($content != '') {
                        $subject = "Reports";
                        $attachmentFilePath = '';
                        $attachmentFileName = '';
                        $CCEmail = '';
                        $BCCEmail = 'sshrikanth@elixiatech.com';
                        //$user['email'] = "sshrikanth@elixiatech.com";
                        $isMailSent = sendMailUtil(array($user['email']), $CCEmail, $BCCEmail, $subject, $content, $attachmentFilePath, $attachmentFileName);
                        if (isset($isMailSent)) {
                            echo $content;
                        }
                    }
                }
            }
        }
    }
}
//</editor-fold>
