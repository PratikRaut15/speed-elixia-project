<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
include_once '../../modules/reports/reports_common_functions.php';
include_once '../../vendor/phpclasses/phpdialgauge/phpdial_gauge_elixia.php';
//echo $customer_id; die;
$sdate = $date;
$edate = $date;
$stime = '00:00';
$etime = '23:59';
$interval_p = '15';
$STdate = GetSafeValueString($sdate, 'string');
$EDdate = GetSafeValueString($edate, 'string');
$interval = GetSafeValueString($interval_p, 'long');
$newsdate = date("Y-m-d", strtotime($sdate));
$newedate = date("Y-m-d", strtotime($edate));
$user_details = $umanager->get_user($customer_id, $user_id);
$userid = $user_details->id;
$_SESSION['report_gen_user'] = $user_details->username;
$roleid = $user_details->roleid;
$cm = new CustomerManager();
$customernos = $cm->getcustomernos();
$objUserManager = new UserManager();
$tempsensors = $cm->get_tempsesors($customer_id);
$userGroups = $objUserManager->get_groups_fromuser($customer_id, $userid);
// print_r($userGroups); die;
$arrGroups = array();
if (isset($userGroups) && !empty($userGroups)) {
    foreach ($userGroups as $group) {
        $arrGroups[] = $group->groupid;
    }
}
$vehicleManager = new VehicleManager($customer_id);
$vehicledata = $vehicleManager->get_vehicle_details($veh_id);
$tempdetails = array();
$vehiclecount = 1;
$vehicleno = '';
$vehicleno = $vehicledata->vehicleno;
$temperature_compliance_data[] = gettemptabularreport_daily_report_cron($STdate, $EDdate, $vehicledata->deviceid, $interval, $stime, $etime, $customer_id, $tempsensors);
$temprature_table[$vehicledata->vehicleno] = gettempreportpdf_cron($customer_id, $STdate, $EDdate, $vehicledata->deviceid, $vehicledata->vehicleno, 15, $stime, $etime, 3, $vehicledata->groupname, 'pdf');
$temp1 = array();
$temp2 = array();
$temp1_compliance_count_store = $temp1_compliance_percent_store = $temp2_compliance_count_store = $temp2_compliance_percent_store = '';
if (isset($temperature_compliance_data)) {
    $storemessage = '';
    foreach ($temperature_compliance_data as $temp_comp_data_for_warehouse) {
        if (is_array($temp_comp_data_for_warehouse) || is_object($temp_comp_data_for_warehouse)) {
            foreach ($temp_comp_data_for_warehouse as $temp_comp_detail) {
                // print("<pre>"); print_r($temp_comp_detail); die;
                $unitno = $temp_comp_detail['unitno'];
                if ((isset($temp_comp_detail['titleid'])) && ($temp_comp_detail['titleid'] == 42 || $temp_comp_detail['titleid'] == 47)) {
                    $temp1[$unitno] = array(
                        'nonComplianceCount' => $temp_comp_detail['totalabvcount'],
                        'goodCount' => $temp_comp_detail['totalnonmutereading'] - $temp_comp_detail['badcount'],
                        'totalCount' => $temp_comp_detail['totalreading'],
                        'badCount' => $temp_comp_detail['badcount'],
                        'mutedCount' => $temp_comp_detail['mutedcount'],
                        'temp_max' => $temp_comp_detail['temp_max'],
                        'temp_min' => $temp_comp_detail['temp_min']
                    );
                    $goodCounttemp1store = $temp1[$unitno]['goodCount'];
                    $badcounttemp1store = $temp1[$unitno]['badCount'];
                    $totalcounttemp1store = $temp1[$unitno]['totalCount'];
                    $mutedtemp1store = $temp1[$unitno]['mutedCount'];
                    $temp1_compliance_count_store = $temp1[$unitno]['goodCount'] - $temp1[$unitno]['nonComplianceCount'];
                    $temp1_compliance_percent_store = round($temp1_compliance_count_store / $temp1[$unitno]['goodCount'] * 100, 2);
                } elseif ((isset($temp_comp_detail['title'])) && ($temp_comp_detail['title'] == "Temperature 1")) {
                    $temp1[$unitno] = array(
                        'nonComplianceCount' => $temp_comp_detail['totalabvcount'],
                        'goodCount' => $temp_comp_detail['totalnonmutereading'] - $temp_comp_detail['badcount'],
                        'totalCount' => $temp_comp_detail['totalreading'],
                        'badCount' => $temp_comp_detail['badcount'],
                        'mutedCount' => $temp_comp_detail['mutedcount'],
                        'temp_max' => $temp_comp_detail['temp_max'],
                        'temp_min' => $temp_comp_detail['temp_min']
                    );
                    $goodCounttemp1store = $temp1[$unitno]['goodCount'];
                    $badcounttemp1store = $temp1[$unitno]['badCount'];
                    $totalcounttemp1store = $temp1[$unitno]['totalCount'];
                    $mutedtemp1store = $temp1[$unitno]['mutedCount'];
                    $temp1_compliance_count_store = $temp1[$unitno]['goodCount'] - $temp1[$unitno]['nonComplianceCount'];
                    $temp1_compliance_percent_store = round($temp1_compliance_count_store / $temp1[$unitno]['goodCount'] * 100, 2);
                } elseif ($temp_comp_detail['titleid'] == 41 || $temp_comp_detail['titleid'] == 46) {
                    $temp2[$unitno] = array(
                        'nonComplianceCount' => $temp_comp_detail['totalabvcount'],
                        'goodCount' => $temp_comp_detail['totalnonmutereading'] - $temp_comp_detail['badcount'],
                        'totalCount' => $temp_comp_detail['totalreading'],
                        'badCount' => $temp_comp_detail['badcount'],
                        'mutedCount' => $temp_comp_detail['mutedcount'],
                        'temp_max' => $temp_comp_detail['temp_max'],
                        'temp_min' => $temp_comp_detail['temp_min']
                    );
                    $goodCounttemp2store = $temp2[$unitno]['goodCount'];
                    $badcounttemp2store = $temp2[$unitno]['badCount'];
                    $totalcounttemp2store = $temp2[$unitno]['totalCount'];
                    $mutedtemp2store = $temp2[$unitno]['mutedCount'];
                    $temp2_compliance_count_store = $temp2[$unitno]['goodCount'] - $temp2[$unitno]['nonComplianceCount'];
                    $temp2_compliance_percent_store = round($temp2_compliance_count_store / $temp2[$unitno]['goodCount'] * 100, 2);
                } elseif ((isset($temp_comp_detail['title'])) && ($temp_comp_detail['title'] == "Temperature 2")) {
                    // echo "else2 "; die;
                    $temp2[$unitno] = array(
                        'nonComplianceCount' => $temp_comp_detail['totalabvcount'],
                        'goodCount' => $temp_comp_detail['totalnonmutereading'] - $temp_comp_detail['badcount'],
                        'totalCount' => $temp_comp_detail['totalreading'],
                        'badCount' => $temp_comp_detail['badcount'],
                        'mutedCount' => $temp_comp_detail['mutedcount'],
                        'temp_max' => $temp_comp_detail['temp_max'],
                        'temp_min' => $temp_comp_detail['temp_min']
                    );
                    $goodCounttemp2store = $temp2[$unitno]['goodCount'];
                    $badcounttemp2store = $temp2[$unitno]['badCount'];
                    $totalcounttemp2store = $temp2[$unitno]['totalCount'];
                    $mutedtemp2store = $temp2[$unitno]['mutedCount'];
                    $temp2_compliance_count_store = $temp2[$unitno]['goodCount'] - $temp2[$unitno]['nonComplianceCount'];
                    $temp2_compliance_percent_store = round($temp2_compliance_count_store / $temp2[$unitno]['goodCount'] * 100, 2);
                }
            }
        }
    }
}
$withtemp1 = $withtemp2 = '';
$goodCounttemp1 = $goodCounttemp2 = '';
$totalcounttemp1 = $badcounttemp2 = '';
$noncompliancecounttemp1 = $noncompliancecounttemp2 = '';
$temp1_compliance_percent = $temp2_compliance_percent = '';
$compliancecounttemp1 = $compliancecounttemp2 = '';
$temp_maxtemp1 = $temp_maxtemp2 = '0';
$temp_mintemp1 = $temp_mintemp2 = '0';
if (!empty($temp1)) {
    //42 - Inside
    $totalcounttemp1 = array_sum_byproperty($temp1, 'totalCount');
    $badcounttemp1 = array_sum_byproperty($temp1, 'badCount');
    $goodCounttemp1 = array_sum_byproperty($temp1, 'goodCount');
    $mutedcounttemp1 = array_sum_byproperty($temp1, 'mutedCount');
    $numbermax = array_map(function ($details) {
        return $details['temp_max'];
    }, $temp1);
    $numbermin = array_map(function ($details) {
        return $details['temp_min'];
    }, $temp1);
    $temp_maxtemp1 = max($numbermax);
    $temp_mintemp1 = min($numbermin);
    if ($goodCounttemp1 > 0) {
        $noncompliancecounttemp1 = array_sum_byproperty($temp1, 'nonComplianceCount');
        $compliancecounttemp1 = $goodCounttemp1 - $noncompliancecounttemp1;
        $temp1_compliance_percent = round($compliancecounttemp1 / $goodCounttemp1 * 100, 2);
        $temp1_noncompliance_percent = (100 - $temp1_compliance_percent);
    } else {
        $temp1_compliance_percent = "Not Applicable";
        $temp1_noncompliance_percent = "Not Applicable";
    }
}
if (!empty($temp2)) {
    // 41  - Gate
    $totalcounttemp2 = array_sum_byproperty($temp2, 'totalCount');
    $badcounttemp2 = array_sum_byproperty($temp2, 'badCount');
    $goodCounttemp2 = array_sum_byproperty($temp2, 'goodCount');
    $mutedcounttemp2 = array_sum_byproperty($temp2, 'mutedCount');
    $numbermax = array_map(function ($details) {
        return $details['temp_max'];
    }, $temp2);
    $numbermin = array_map(function ($details) {
        return $details['temp_min'];
    }, $temp2);
    $temp_maxtemp2 = max($numbermax);
    $temp_mintemp2 = min($numbermin);
    if ($goodCounttemp2 > 0) {
        $noncompliancecounttemp2 = array_sum_byproperty($temp2, 'nonComplianceCount');
        $compliancecounttemp2 = $goodCounttemp2 - $noncompliancecounttemp2;
        $temp2_compliance_percent = round($compliancecounttemp2 / $goodCounttemp2 * 100, 2);
        $temp2_noncompliance_percent = (100 - $temp2_compliance_percent);
    } else {
        $temp2_compliance_percent = "Not Applicable";
        $temp2_noncompliance_percent = "Not Applicable";
    }
}
if ($temp_maxtemp1 != 0 && $temp_maxtemp2 != 0) {
    $array_max_temp = array($temp_maxtemp1, $temp_maxtemp2);
    $temp_maxtemp1_temp2 = max($array_max_temp);
} elseif ($temp_maxtemp1 == 0 && $temp_maxtemp2 != 0) {
    $temp_maxtemp1_temp2 = $temp_maxtemp2;
} elseif ($temp_maxtemp1 != 0 && $temp_maxtemp2 == 0) {
    $temp_maxtemp1_temp2 = $temp_maxtemp1;
}
if ($temp_mintemp1 != 0 && $temp_mintemp2 != 0) {
    $array_min_temp = array($temp_mintemp1, $temp_mintemp2);
    $temp_mintemp1_temp2 = min($array_min_temp);
} elseif ($temp_mintemp1 == 0 && $temp_mintemp2 != 0) {
    $temp_mintemp1_temp2 = $temp_mintemp2;
} elseif ($temp_mintemp1 != 0 && $temp_mintemp2 == 0) {
    $temp_mintemp1_temp2 = $temp_mintemp1;
}
/*echo $temp1_compliance_percent ." - ".$temp2_compliance_percent;
 echo "<br>".$temp1_noncompliance_percent ." - ". $temp2_noncompliance_percent; die;*/
$totalcounttemp1_temp2 = $totalcounttemp1 + $totalcounttemp2;
$noncompliancecounttemp1_temp2 = $noncompliancecounttemp1 + $noncompliancecounttemp2;
$goodCounttemp1_temp2 = $goodCounttemp1 + $goodCounttemp2;
$compliancecounttemp1_temp2 = $compliancecounttemp1 + $compliancecounttemp2;
$temp1_temp2_compliance_percent = round($compliancecounttemp1_temp2 / $goodCounttemp1_temp2 * 100, 2);
$temp1_temp2_noncompliance_percent = (100 - $temp1_temp2_compliance_percent);
//echo "<br>". $temp1_temp2_compliance_percent." - ".$temp1_temp2_noncompliance_percent; die;
$reportDate = date('d-M-Y', strtotime($date));
$my_gaugeobj = new dial();
$my_gauge_image = $my_gaugeobj->dial_gauge($temp1_temp2_noncompliance_percent, true, 0, 100);
//Add template
$msg_table = '';
if (isset($temprature_table)) {
    foreach ($temprature_table as $key => $row) {
        if ($row != '') {
            if ($vehiclecount > 1) {
                $msg_table .= "<h4>DC NAME -" . $key . "</h4>";
            } else {
                $msg_table .= " ";
            }
            $msg_table .= $row;
        }
    }
}
// $message = file_get_contents('../emailtemplates/customer/473/dailycompliance_customer_template.html');
$message = file_get_contents('../emailtemplates/customer/' . $customer_id . '/dailycompliance_customer_template.html');
$message = str_replace("{{REPORT_DATE}}", $reportDate, $message);
$message = str_replace("{{TEMP_MAX}}", $temp_maxtemp1_temp2, $message);
$message = str_replace("{{TEMP_MIN}}", $temp_mintemp1_temp2, $message);
$message = str_replace("{{TOTAL_COUNT}}", $goodCounttemp1_temp2, $message);
$message = str_replace("{{COMPLIANCE_COUNT}}", $compliancecounttemp1_temp2, $message);
$message = str_replace("{{NON_COMPLIANCE_COUNT}}", $noncompliancecounttemp1_temp2, $message);
$message = str_replace("{{COMPLIANCE_PERCENT}}", $temp1_temp2_compliance_percent, $message);
$message = str_replace("{{NON_COMPLIANCE_PERCENT}}", $temp1_temp2_noncompliance_percent, $message);
$message = str_replace("{{BASE64IMAGE_GAUGE}}", base64_encode($my_gauge_image), $message);
if ($msg_table != '') {
    $message = str_replace("{{TABLEVIEW}}", $msg_table, $message);
} else {
    $msg_table = '';
    $message = str_replace("{{TABLEVIEW}}", $msg_table, $message);
}
if ($vehiclecount == 1 && $vehicleno != '') {
    $msg_vehiclename = "<h4>DC NAME -  " . $vehicleno . "</h4>";
    $message = str_replace("{{VEHICLENAME}}", $msg_vehiclename, $message);
} else {
    $msg_vehiclename = '';
    $message = str_replace("{{VEHICLENAME}}", $msg_vehiclename, $message);
}
echo $message;
require_once '../reports/html2pdf.php';
$content = ob_get_clean();
try {
    $html2pdf = new HTML2PDF('L', 'A4', 'en');
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    $html2pdf->Output($sdate . "_DailyTempCompliance.pdf");
} catch (HTML2PDF_exception $e) {
    echo $e;
    exit;
}
?>
