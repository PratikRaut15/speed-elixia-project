<?php

$sdate = $_GET['startdate'];  
$edate = $_GET['enddate']; 

if(empty($sdate) || empty($edate)){
    echo "Please check start date or end date"; die();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);
set_time_limit(0);
ini_set('memory_limit', '256M');

require "../../lib/system/utilities.php";
require '../../lib/bo/CustomerManager.php';
require '../../lib/bo/UserManager.php';
include '../../lib/bo/simple_html_dom.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../modules/reports/reports_common_functions.php';

function array_sum_byproperty($arrObj, $property) {
    $sum = 0;
    foreach ($arrObj as $data) {
        if (isset($data[$property])) {
            $sum += $data[$property];
        }
    }
    return $sum;
}

$serverpath = "/var/www/html/speed";
$customerno = '177';  // Fassos 
//$sdate = '2016-07-01'; //Jully  
//$edate = '2016-07-31';
$stime = '00:00';
$etime = '23:59';
$interval_p = '1';   // 1min 

$STdate = GetSafeValueString($sdate, 'string');
$EDdate = GetSafeValueString($edate, 'string');
$interval = GetSafeValueString($interval_p, 'long');
$newsdate = date("Y-m-d", strtotime($sdate));
$newedate = date("Y-m-d", strtotime($edate));

$cm = new CustomerManager();
$customernos = $cm->getcustomernos();
$grouplistdata = $cm->getgrouplistby_customer($customerno);
$message = 'Monthly Compliance Report for ' . date('F Y', strtotime($sdate));
$message .='<table border="1"><tr>'
        . '<th>City Name</th>'
        . '<th>Vertical Chiller NonVeg Temperature compliance % (count) </th>'
        . '<th>Vertical Chiller Veg Temperature compliance % (count)</th>'
        . '<th>Veg-Makeline Temperature compliance % (count) </th>'
        . '<th>Non-veg makeline Temperature compliance % (count) </th>'
        . '<th>Deep Freezer Veg Temperature compliance % (count) </th>'
        . '<th>Deep Freezer Non Veg Temperature compliance % (count) </th>'
        . '<th>Vigi Cooler compliance % (count) </th>'
        . '</tr>';
if (isset($grouplistdata)) {
    foreach ($grouplistdata as $row) {
        $groupid = $row['groupid'];
        $veh = new VehicleManager($customerno);
        $vehicledata = $veh->get_all_vehicles_by_group(array($groupid), 1);
        $temperature_compliance_data = array();
        $vc = array();  //vc - vc non veg - 4 
        $vcv = array();  //vcv - vcv veg - 7 
        $vml = array();  //veg make line - 2
        $nvml = array();  //veg make line - 3 
        $deepfs = array();   // deep freeze small - 6
        $deepfb = array();   // // deep freeze big  - 5
        $vigicooler = array();  // vigi cooler as make line 1 
        $tempdata = array();
        $vc_non_comp_percent = 0;
        $vcm_non_comp_percent = 0;
        $vml_non_comp_percent = 0;
        $nvml_non_comp_percent = 0;
        $deepfs_non_comp_percent = 0;
        $deepfb_non_comp_percent = 0;
        $vigicooler_non_comp_percent = 0;

        foreach ($vehicledata as $row1) {
            $temperature_compliance_data[] = gettemptabularreport_fassos_cron($STdate, $EDdate, $row1->deviceid, $interval, $stime, $etime, $customerno);
        }

        if (isset($temperature_compliance_data)) {
            $storemessage = '';
            foreach ($temperature_compliance_data as $temp_comp_data_for_vehicle) {
                if (is_array($temp_comp_data_for_vehicle) || is_object($temp_comp_data_for_vehicle)) {

                    $vc_compliance_count_store = $vc_compliance_percent_store = $vcv_compliance_count_store = $vcv_compliance_percent_store = $vml_compliance_count_store = $vml_compliance_percent_store = $nvml_compliance_count_store = $nvml_compliance_percent_store = $deefs_compliance_count_store = $deefs_compliance_percent_store = $deefb_compliance_count_store = $deefb_compliance_percent_store = $vigicooler_compliance_count_store = $vigicooler_compliance_percent_store = '';
                    $goodCountvcstore = $badcountvcstore = $totalcountvcstore = $mutedvcstore = '';
                    $goodCountvcvstore = $badcountvcvstore = $totalcountvcvstore = $mutedvcvstore = '';
                    $goodCountvmlstore = $badcountvmlstore = $totalcountvmlstore = $mutedvmlstore = '';
                    $goodCountnvmlstore = $badcountnvmlstore = $totalcountnvmlstore = $mutednvmlstore = '';
                    $goodCountdeepfsstore = $badcountdeepfsstore = $totalcountdeepfsstore = $muteddeepfstore = '';
                    $goodCountdeepfbstore = $badcountdeepfbstore = $totalcountdeepfbstore = $muteddeepfbstore = '';
                    $goodCountvigicoolerstore = $badcountvigicoolerstore = $totalcountvigicoolerstore = $mutedvigicoolerstore = '';


                    foreach ($temp_comp_data_for_vehicle as $temp_comp_detail) {
                        $unitno = $temp_comp_detail['unitno'];
                        //if ($temp_comp_detail['title'] == 'Vertical Chiller') {
                        if ($temp_comp_detail['titleid'] ==4) {  
                            $vc[$unitno] = array(
                                'nonComplianceCount' => $temp_comp_detail['totalabvcount'],
                                'goodCount' => $temp_comp_detail['totalnonmutereading'] - $temp_comp_detail['badcount'],
                                'totalCount' => $temp_comp_detail['totalreading'],
                                'badCount' => $temp_comp_detail['badcount'],
                                'mutedCount' => $temp_comp_detail['mutedcount']
                            );
                            $goodCountvcstore = $vc[$unitno]['goodCount'];
                            $badcountvcstore = $vc[$unitno]['badCount'];
                            $totalcountvcstore = $vc[$unitno]['totalCount'];
                            $mutedvcstore = $vc[$unitno]['mutedCount'];
                            if ($temp_comp_detail['totalnonmutereading'] == 0) {
                                $vc_compliance_percent_store = "Not Applicable";
                            }else {
                                $vc_compliance_count_store = $vc[$unitno]['goodCount'] - $vc[$unitno]['nonComplianceCount'];
                                $vc_compliance_percent_store = round($vc_compliance_count_store / $vc[$unitno]['goodCount'] * 100, 2);
                            }
                        }

                        //if ($temp_comp_detail['title'] == 'Vertical Chiller Veg') {
                        if ($temp_comp_detail['titleid'] ==7) {
                            $vcv[$unitno] = array(
                                'nonComplianceCount' => $temp_comp_detail['totalabvcount'],
                                'goodCount' => $temp_comp_detail['totalnonmutereading'] - $temp_comp_detail['badcount'],
                                'totalCount' => $temp_comp_detail['totalreading'],
                                'badCount' => $temp_comp_detail['badcount'],
                                'mutedCount' => $temp_comp_detail['mutedcount']
                            );
                            $goodCountvcvstore = $vcv[$unitno]['goodCount'];
                            $badcountvcvstore = $vcv[$unitno]['badCount'];
                            $totalcountvcvstore = $vcv[$unitno]['totalCount'];
                            $mutedvcvstore = $vcv[$unitno]['mutedCount'];
                            if ($temp_comp_detail['totalnonmutereading'] == 0) {
                                $vcv_compliance_percent_store = "Not Applicable";
                            }
                            else {
                                $vcv_compliance_count_store = $vcv[$unitno]['goodCount'] - $vcv[$unitno]['nonComplianceCount'];
                                $vcv_compliance_percent_store = round($vcv_compliance_count_store / $vcv[$unitno]['goodCount'] * 100, 2);
                            }
                        }

                        //if ($temp_comp_detail['title'] == 'Veg Make Line') {
                        if ($temp_comp_detail['titleid'] ==2) {
                            $vml[$unitno] = array(
                                'nonComplianceCount' => $temp_comp_detail['totalabvcount'],
                                'goodCount' => $temp_comp_detail['totalnonmutereading'] - $temp_comp_detail['badcount'],
                                'totalCount' => $temp_comp_detail['totalreading'],
                                'badCount' => $temp_comp_detail['badcount'],
                                'mutedCount' => $temp_comp_detail['mutedcount']
                            );
                            $goodCountvmlstore = $vml[$unitno]['goodCount'];
                            $badcountvmlstore = $vml[$unitno]['badCount'];
                            $totalcountvmlstore = $vml[$unitno]['totalCount'];
                            $mutedvmlstore = $vml[$unitno]['mutedCount'];
                            if ($temp_comp_detail['totalnonmutereading'] == 0) {
                                $vml_compliance_percent_store = "Not Applicable";
                            }
                            else {
                                $vml_compliance_count_store = $vml[$unitno]['goodCount'] - $vml[$unitno]['nonComplianceCount'];
                                $vml_compliance_percent_store = round($vml_compliance_count_store / $vml[$unitno]['goodCount'] * 100, 2);
                            }
                        }
                        //if ($temp_comp_detail['title'] == 'Non-Veg Make Line') {
                        if ($temp_comp_detail['titleid'] == 3){
                            $nvml[$unitno] = array(
                                'nonComplianceCount' => $temp_comp_detail['totalabvcount'],
                                'goodCount' => $temp_comp_detail['totalnonmutereading'] - $temp_comp_detail['badcount'],
                                'totalCount' => $temp_comp_detail['totalreading'],
                                'badCount' => $temp_comp_detail['badcount'],
                                'mutedCount' => $temp_comp_detail['mutedcount']
                            );

                            $goodCountnvmlstore = $nvml[$unitno]['goodCount'];
                            $badcountnvmlstore = $nvml[$unitno]['badCount'];
                            $totalcountnvmlstore = $nvml[$unitno]['totalCount'];
                            $mutednvmlstore = $nvml[$unitno]['mutedCount'];
                            if ($temp_comp_detail['totalnonmutereading'] == 0) {
                                $nvml_compliance_percent_store = "Not Applicable";
                            }
                            else {
                                $nvml_compliance_count_store = $nvml[$unitno]['goodCount'] - $nvml[$unitno]['nonComplianceCount'];
                                $nvml_compliance_percent_store = round($nvml_compliance_count_store / $nvml[$unitno]['goodCount'] * 100, 2);
                            }
                        }
                        //if ($temp_comp_detail['title'] == 'Deep Freezer Veg') {
                        if ($temp_comp_detail['titleid'] ==6){
                            $deepfs[$unitno] = array(
                                'nonComplianceCount' => $temp_comp_detail['totalabvcount'],
                                'goodCount' => $temp_comp_detail['totalnonmutereading'] - $temp_comp_detail['badcount'],
                                'totalCount' => $temp_comp_detail['totalreading'],
                                'badCount' => $temp_comp_detail['badcount'],
                                'mutedCount' => $temp_comp_detail['mutedcount']
                            );
                            $goodCountdeepfsstore = $deepfs[$unitno]['goodCount'];
                            $badcountdeepfsstore = $deepfs[$unitno]['badCount'];
                            $totalcountdeepfsstore = $deepfs[$unitno]['totalCount'];
                            $muteddeepfsstore = $deepfs[$unitno]['mutedCount'];
                            if ($temp_comp_detail['totalnonmutereading'] == 0) {
                                $deefs_compliance_percent_store = "Not Applicable";
                            }
                            else {
                                $deefs_compliance_count_store = $deepfs[$unitno]['goodCount'] - $deepfs[$unitno]['nonComplianceCount'];
                                $deefs_compliance_percent_store = round($deefs_compliance_count_store / $deepfs[$unitno]['goodCount'] * 100, 2);
                            }
                        }
                        //if ($temp_comp_detail['title'] == 'Deep Freezer Non Veg') {
                        if ($temp_comp_detail['titleid'] == 5) {
                            $deepfb[$unitno] = array(
                                'nonComplianceCount' => $temp_comp_detail['totalabvcount'],
                                'goodCount' => $temp_comp_detail['totalnonmutereading'] - $temp_comp_detail['badcount'],
                                'totalCount' => $temp_comp_detail['totalreading'],
                                'badCount' => $temp_comp_detail['badcount'],
                                'mutedCount' => $temp_comp_detail['mutedcount']
                            );
                            $goodCountdeepfbstore = $deepfb[$unitno]['goodCount'];
                            $badcountdeepfbstore = $deepfb[$unitno]['badCount'];
                            $totalcountdeepfbstore = $deepfb[$unitno]['totalCount'];
                            $muteddeepfbstore = $deepfb[$unitno]['mutedCount'];
                            if ($temp_comp_detail['totalnonmutereading'] == 0) {
                                $deefb_compliance_percent_store = "Not Applicable";
                            }
                            else {
                                $deefb_compliance_count_store = $deepfb[$unitno]['goodCount'] - $deepfb[$unitno]['nonComplianceCount'];
                                $deefb_compliance_percent_store = round($deefb_compliance_count_store / $deepfb[$unitno]['goodCount'] * 100, 2);
                            }
                        }

                        //if ($temp_comp_detail['title'] == 'Vigi Cooler') {
                        if ($temp_comp_detail['titleid'] ==1) {
                            $vigicooler[$unitno] = array(
                                'nonComplianceCount' => $temp_comp_detail['totalabvcount'],
                                'goodCount' => $temp_comp_detail['totalnonmutereading'] - $temp_comp_detail['badcount'],
                                'totalCount' => $temp_comp_detail['totalreading'],
                                'badCount' => $temp_comp_detail['badcount'],
                                'mutedCount' => $temp_comp_detail['mutedcount']
                            );
                            $goodCountvigicoolerstore = $vigicooler[$unitno]['goodCount'];
                            $badcountvigicoolerstore = $vigicooler[$unitno]['badCount'];
                            $totalcountvigicoolerstore = $vigicooler[$unitno]['totalCount'];
                            $mutedvigicoolerstore = $vigicooler[$unitno]['mutedCount'];
                            if ($temp_comp_detail['totalnonmutereading'] == 0) {
                                $vigicooler_compliance_percent_store = "Not Applicable";
                            }
                            else {
                                $vigicooler_compliance_count_store = $vigicooler[$unitno]['goodCount'] - $vigicooler[$unitno]['nonComplianceCount'];
                                $vigicooler_compliance_percent_store = round($vigicooler_compliance_count_store / $vigicooler[$unitno]['goodCount'] * 100, 2);
                            }
                        }
                    }
                    if (!empty($temp_comp_detail['vehicleno'])) {
                        $storemessage .="<tr><td style='background-color:#CCCCCC;'>" . $temp_comp_detail['vehicleno'] . "</td>"
                                . "<td>" . $vc_compliance_percent_store . " (" . $vc_compliance_count_store . ")</td>"
                                . "<td>" . $vcv_compliance_percent_store . " (" . $vcv_compliance_count_store . ")</td>"
                                . "<td>" . $vml_compliance_percent_store . " (" . $vml_compliance_count_store . ")</td>"
                                . "<td>" . $nvml_compliance_percent_store . " (" . $nvml_compliance_count_store . ")</td>"
                                . "<td>" . $deefs_compliance_percent_store . " (" . $deefs_compliance_count_store . ")</td>"
                                . "<td>" . $deefb_compliance_percent_store . " (" . $deefb_compliance_count_store . ")</td>"
                                . "<td>" . $vigicooler_compliance_percent_store . " (" . $vigicooler_compliance_count_store . ")</td>"
                                . "</tr>";
                        $storemessage .="<tr><td>Good / Bad / Muted / Total Count</td>"
                                . "<td>" . $goodCountvcstore . "/" . $badcountvcstore . "/" . $mutedvcstore . "/" . $totalcountvcstore . "</td>"
                                . "<td>" . $goodCountvcvstore . "/" . $badcountvcvstore . "/" . $mutedvcvstore . "/" . $totalcountvcvstore . "</td>"
                                . "<td>" . $goodCountvmlstore . "/" . $badcountvmlstore . "/" . $mutedvmlstore . "/" . $totalcountvmlstore . "</td>"
                                . "<td>" . $goodCountnvmlstore . "/" . $badcountnvmlstore . "/" . $mutednvmlstore . "/" . $totalcountnvmlstore . "</td>"
                                . "<td>" . $goodCountdeepfsstore . "/" . $badcountdeepfsstore . "/" . $muteddeepfsstore . "/" . $totalcountdeepfsstore . "</td>"
                                . "<td>" . $goodCountdeepfbstore . "/" . $badcountdeepfbstore . "/" . $muteddeepfbstore . "/" . $totalcountdeepfbstore . "</td>"
                                . "<td>" . $goodCountvigicoolerstore . "/" . $badcountvigicoolerstore . "/" . $mutedvigicoolerstore . "/" . $totalcountvigicoolerstore . "</td>"
                                . "</tr>";
                        $storemessage .="<tr><td colspan=100%>&nbsp;</td></tr>";
                    }
                }
            }
        }
        $withvc = $withvcv = $withvml = $withnvml = $withdeepfs = $withdeepfb = $withvgcooler = '';
        $goodCountvc = $goodCountvcv = $goodCountvml = $goodCountnvml = $goodCountdeepfs = $goodCountdeepfb = $goodCountdeepfb = $goodCountvigicooler = '';
        $totalcountvc = $badcountvc = $totalcountvcv = $badcountvcv = $totalcountvml = $badcountvml = $totalcountnvml = $badcountnvml = $totalcountdeepfs = $badcountdeepfs = $totalcountdeepfb = $badcountdeepfb = $totalcountvigicooler = $badcountvigicooler = '';
        $noncompliancecountvc = $noncompliancecountvcv = $noncompliancecountvml = $noncompliancecountnvml = $noncompliancecountdeepfs = $noncompliancecountdeepfb = $noncompliancecountvigicooler = '';
        $vc_compliance_percent = $vcv_compliance_percent = $vml_compliance_percent = $nvml_compliance_percent = $deepfs_compliance_percent = $deepfb_compliance_percent = $vigicooler_compliance_percent = '';
        $compliancecountvc = $compliancecountvcv = $compliancecountvml = $compliancecountnvml = $compliancecountdeepfs = $compliancecountdeepfb = $compliancecountvigicooler = '';
        $mutedcountvc = $mutedcountvcv = $mutedcountvml = $mutedcountnvml = $mutedcountdeepfs = $mutedcountdeepfb = $mutedcountvigicooler = '';
        if (!empty($vc)) {
            $totalcountvc = array_sum_byproperty($vc, 'totalCount');
            $badcountvc = array_sum_byproperty($vc, 'badCount');
            $goodCountvc = array_sum_byproperty($vc, 'goodCount');
            $mutedcountvc = array_sum_byproperty($vc, 'mutedCount');
            if ($goodCountvc > 0) {
                $noncompliancecountvc = array_sum_byproperty($vc, 'nonComplianceCount');
                $compliancecountvc = $goodCountvc - $noncompliancecountvc;
                $vc_compliance_percent = round($compliancecountvc / $goodCountvc * 100, 2);
            }
            else {
                $vc_compliance_percent = "Not Applicable";
            }
        }

        if (!empty($vcv)) {
            $totalcountvcv = array_sum_byproperty($vcv, 'totalCount');
            $badcountvcv = array_sum_byproperty($vcv, 'badCount');
            $goodCountvcv = array_sum_byproperty($vcv, 'goodCount');
            $mutedcountvcv = array_sum_byproperty($vcv, 'mutedCount');
            if ($goodCountvcv > 0) {
                $noncompliancecountvcv = array_sum_byproperty($vcv, 'nonComplianceCount');
                $compliancecountvcv = $goodCountvcv - $noncompliancecountvcv;
                $vcv_compliance_percent = round($compliancecountvcv / $goodCountvcv * 100, 2);
            }
            else {
                $vcv_compliance_percent = "Not Applicable";
            }
        }

        if (!empty($vml)) {
            $totalcountvml = array_sum_byproperty($vml, 'totalCount');
            $badcountvml = array_sum_byproperty($vml, 'badCount');
            $goodCountvml = array_sum_byproperty($vml, 'goodCount');
            $mutedcountvml = array_sum_byproperty($vml, 'mutedCount');
            if ($goodCountvml > 0) {
                $noncompliancecountvml = array_sum_byproperty($vml, 'nonComplianceCount');
                $compliancecountvml = $goodCountvml - $noncompliancecountvml;
                $vml_compliance_percent = round($compliancecountvml / $goodCountvml * 100, 2);
            }
            else {
                $vml_compliance_percent = "Not Applicable";
            }
        }
        if (!empty($nvml)) {
            $totalcountnvml = array_sum_byproperty($nvml, 'totalCount');
            $badcountnvml = array_sum_byproperty($nvml, 'badCount');
            $goodCountnvml = array_sum_byproperty($nvml, 'goodCount');
            $mutedcountnvml = array_sum_byproperty($nvml, 'mutedCount');
            if ($goodCountnvml > 0) {
                $noncompliancecountnvml = array_sum_byproperty($nvml, 'nonComplianceCount');
                $compliancecountnvml = $goodCountnvml - $noncompliancecountnvml;
                $nvml_compliance_percent = round($compliancecountnvml / $goodCountnvml * 100, 2);
            }
            else {
                $nvml_compliance_percent = "Not Applicable";
            }
        }
        if (!empty($deepfs)) {
            $totalcountdeepfs = array_sum_byproperty($deepfs, 'totalCount');
            $badcountdeepfs = array_sum_byproperty($deepfs, 'badCount');
            $goodCountdeepfs = array_sum_byproperty($deepfs, 'goodCount');
            $mutedcountdeepfs = array_sum_byproperty($deepfs, 'mutedCount');
            if ($goodCountdeepfs > 0) {
                $noncompliancecountdeepfs = array_sum_byproperty($deepfs, 'nonComplianceCount');
                $compliancecountdeepfs = $goodCountdeepfs - $noncompliancecountdeepfs;
                $deepfs_compliance_percent = round($compliancecountdeepfs / $goodCountdeepfs * 100, 2);
            }
            else {
                $deepfs_compliance_percent = "Not Applicable";
            }
        }
        if (!empty($deepfb)){
            $totalcountdeepfb = array_sum_byproperty($deepfb, 'totalCount');
            $badcountdeepfb = array_sum_byproperty($deepfb, 'badCount');
            $goodCountdeepfb = array_sum_byproperty($deepfb, 'goodCount');
            $mutedcountdeepfb = array_sum_byproperty($deepfb, 'mutedCount');
            if ($goodCountdeepfb > 0) {
                $noncompliancecountdeepfb = array_sum_byproperty($deepfb, 'nonComplianceCount');
                $compliancecountdeepfb = $goodCountdeepfb - $noncompliancecountdeepfb;
                $deepfb_compliance_percent = round($compliancecountdeepfb / $goodCountdeepfb * 100, 2);
            }
            else {
                $deepfb_compliance_percent = "Not Applicable";
            }
        }

        if (!empty($vigicooler)) {
            $totalcountvigicooler = array_sum_byproperty($vigicooler, 'totalCount');
            $badcountvigicooler = array_sum_byproperty($vigicooler, 'badCount');
            $goodCountvigicooler = array_sum_byproperty($vigicooler, 'goodCount');
            $mutedcountvigicooler = array_sum_byproperty($vigicooler, 'mutedCount');
            if ($goodCountvigicooler > 0) {
                $noncompliancecountvigicooler = array_sum_byproperty($vigicooler, 'nonComplianceCount');
                $compliancecountvigicooler = $goodCountvigicooler - $noncompliancecountvigicooler;
                $vigicooler_compliance_percent = round($compliancecountvigicooler / $goodCountvigicooler * 100, 2);
            }
            else {
                $vigicooler_compliance_percent = "Not Applicable";
            }
        }

        $message .="<tr><td style='background-color:yellow;'>" . $row['groupname'] . "</td>"
                . "<td>" . $vc_compliance_percent . " (" . $compliancecountvc . ")</td>"
                . "<td>" . $vcv_compliance_percent . " (" . $compliancecountvcv . ")</td>"
                . "<td>" . $vml_compliance_percent . " (" . $compliancecountvml . ")</td>"
                . "<td>" . $nvml_compliance_percent . " (" . $compliancecountnvml . ")</td>"
                . "<td>" . $deepfs_compliance_percent . " (" . $compliancecountdeepfs . ")</td>"
                . "<td>" . $deepfb_compliance_percent . " (" . $compliancecountdeepfb . ")</td>"
                . "<td>" . $vigicooler_compliance_percent . " (" . $compliancecountvigicooler . ")</td>"
                . "</tr>";
        $message .="<tr><td>Good / Bad / Muted / Total Count</td>"
                . "<td>" . $goodCountvc . "/" . $badcountvc . "/" . $mutedcountvc . "/" . $totalcountvc . "</td>"
                . "<td>" . $goodCountvcv . "/" . $badcountvcv . "/" . $mutedcountvcv . "/" . $totalcountvcv . "</td>"
                . "<td>" . $goodCountvml . "/" . $badcountvml . "/" . $mutedcountvml . "/" . $totalcountvml . "</td>"
                . "<td>" . $goodCountnvml . "/" . $badcountnvml . "/" . $mutedcountnvml . "/" . $totalcountnvml . "</td>"
                . "<td>" . $goodCountdeepfs . "/" . $badcountdeepfs . "/" . $mutedcountdeepfs . "/" . $totalcountdeepfs . "</td>"
                . "<td>" . $goodCountdeepfb . "/" . $badcountdeepfb . "/" . $mutedcountdeepfb . "/" . $totalcountdeepfb . "</td>"
                . "<td>" . $goodCountvigicooler . "/" . $badcountvigicooler . "/" . $mutedcountvigicooler . "/" . $totalcountvigicooler . "</td>"
                . "</tr>";
        $message .="<tr><td colspan=100%>&nbsp;</td></tr>";
        $message .= $storemessage;
    }
}
$message .='</table>';

$content = ob_get_clean();
$content = str_replace("()", "", $message);
$content = str_replace("//", "", $content);
//echo $content;die();
$dest1 = "../../customer";
$dest2 = "../../customer/$customerno/temperaturereports/";

if (!file_exists($dest1)) {
    mkdir($dest1, 0777, true);
}
if (!file_exists($dest2)) {
    mkdir($dest2, 0777, true);
}
$ext = ".xls";
$file_name = "citywisedata_fassos_monthly_report_" . date('F Y', strtotime($sdate)) . $ext;
$fullpath = $dest2 . "/" . $file_name;
save_xls($fullpath, $content);

$subject = 'Monthly Compliance Report for ' . date('F Y', strtotime($sdate));

function save_xls($full_path, $content) {
    include_once'../../lib/bo/simple_html_dom.php';
    $html = str_get_html($content);
    $fp = fopen($full_path, "w");
    fwrite($fp, $html);
    fclose($fp);
}

$toArr = array(
    'satyajit.puri@faasos.com'
    , 'prashant.nair@faasos.com'
    , 'vipin@faasos.com'
    , 'ashwini.patil@faasos.com'
    , 'swapnil.perane@faasos.com'
);
$CCEmail = '';
$BCCEmail = 'mrudang.vora@elixiatech.com';
$emailmessage = "Monthly Compliance Report for " . date('F Y', strtotime($sdate));

$emailstatus = sendMailPHPMAILER($toArr, $CCEmail, $BCCEmail, $subject, $emailmessage, $dest2, $file_name);

function sendMailPHPMAILER(array $arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName) {
    include_once("class.phpmailer.php");
    $isEmailSent = 0;
    $completeFilePath = '';
    if ($attachmentFilePath != '' && $attachmentFileName != '') {
        $completeFilePath = $attachmentFilePath . "/" . $attachmentFileName;
    }
    $mail = new PHPMailer();
    $mail->IsMail();
    /* Clear Email Addresses */
    $mail->ClearAddresses();
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();
    $mail->ClearCustomHeaders();
    //unset($arrToMailIds);
    //$arrToMailIds = array('ganeshp@elixiatech.com','sshrikanth@elixiatech.com', 'mrudangvora@gmail.com', 'shrisurya24@gmail.com');
    //$strCCMailIds = '';
    if (!empty($arrToMailIds)) {
        foreach ($arrToMailIds as $mailto) {
            $mail->AddAddress($mailto);
        }
        if (!empty($strCCMailIds)) {
            $mail->AddCustomHeader("CC: " . $strCCMailIds);
        }
        if (!empty($strBCCMailIds)) {
            $mail->AddCustomHeader("BCC: " . $strBCCMailIds);
        }
    }
    $mail->From = "noreply@elixiatech.com";
    $mail->FromName = "Elixia Speed";
    $mail->Sender = "noreply@elixiatech.com";
    //$mail->AddReplyTo($from,"Elixia Speed");
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->IsHtml(true);
    if ($completeFilePath != '' && $attachmentFileName != '') {
        $mail->AddAttachment($completeFilePath, $attachmentFileName);
    }
    //SEND Mail
    if ($mail->Send()) {
        $isEmailSent = 1; // or use booleans here
    }
    /* Clear Email Addresses */
    $mail->ClearAddresses();
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();
    $mail->ClearCustomHeaders();
    return $isEmailSent;
}

if ($emailstatus == 1) {
    echo "Mail sent";
}
else {
    echo "Error sending mail ";
}
?>