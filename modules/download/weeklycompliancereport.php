<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
include_once '../../modules/reports/reports_common_functions.php';
include_once '../../vendor/phpclasses/phpdialgauge/phpdial_gauge_elixia.php';

$enddate = $date;

$date = new DateTime($date);
$date->sub(new DateInterval('P7D'));
$startdate = $date->format('Y-m-d');

$sdate = $startdate;
$edate = $enddate;
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
$arrGroups = array();
if (isset($userGroups) && !empty($userGroups)) {
    foreach ($userGroups as $group) {
        $arrGroups[] = $group->groupid;
    }
}
$vehicleManager = new VehicleManager($customer_id);
$vehicles = $vehicleManager->get_all_vehicles_by_group_withusermapping($arrGroups, $isWarehouse = 1, $roleid, $userid);

$reportStartDate = date('d-M-Y', strtotime($sdate));
$reportEndDate = date('d-M-Y', strtotime($edate));
$reportDate = "(" . $reportStartDate . " To " . $reportEndDate . ")";
$message = '';
$message = file_get_contents('../emailtemplates/customer/473/weeklycompliance_customer_template.html');
$message = str_replace("{{REPORT_DATE}}", $reportDate, $message);
$message_data = '';
$my_gaugeobj = new dial();
$totalvehicles = count($vehicles);
if (isset($vehicles) && !empty($vehicles)) {
    $i = 1;
    $message_data .='<table border=1 style="width:100%; border-collapse: collapse;"><tr>';
    foreach ($vehicles as $vehicle) {
        $tempdetails = array();
        $vehiclecount = 1;
        $vehicleno = $vehicle->vehicleno;
        $temperature_compliance_data[] = gettemptabularreport_daily_report_cron($STdate, $EDdate, $vehicle->deviceid, $interval, $stime, $etime, $customer_id, $tempsensors);

        $temp1 = array();
        $temp2 = array();
        $temp1_compliance_count_store = $temp1_compliance_percent_store = $temp2_compliance_count_store = $temp2_compliance_percent_store = '';

        if (isset($temperature_compliance_data) && !empty($temperature_compliance_data)) {
            $storemessage = '';
            foreach ($temperature_compliance_data as $temp_comp_data_for_warehouse) {
                if (is_array($temp_comp_data_for_warehouse) || is_object($temp_comp_data_for_warehouse)) {
                    if (isset($temp_comp_data_for_warehouse) && !empty($temp_comp_data_for_warehouse)) {
                        foreach ($temp_comp_data_for_warehouse as $temp_comp_detail) {
                            $unitno = $temp_comp_detail['unitno'];
                            if (isset($temp_comp_detail['titleid']) && $temp_comp_detail['titleid'] == 42) {
                                $temp1[$unitno] = array(
                                    'nonComplianceCount' => $temp_comp_detail['totalabvcount'],
                                    'goodCount' => $temp_comp_detail['totalnonmutereading'] - $temp_comp_detail['badcount'],
                                    'totalCount' => $temp_comp_detail['totalreading'],
                                    'badCount' => $temp_comp_detail['badcount'],
                                    'mutedCount' => $temp_comp_detail['mutedcount'],
                                    'temp_max' => $temp_comp_detail['temp_max'],
                                    'temp_min' => $temp_comp_detail['temp_min'],
                                );
                            }

                            if ($temp_comp_detail['titleid'] == 41) {
                                $temp2[$unitno] = array(
                                    'nonComplianceCount' => $temp_comp_detail['totalabvcount'],
                                    'goodCount' => $temp_comp_detail['totalnonmutereading'] - $temp_comp_detail['badcount'],
                                    'totalCount' => $temp_comp_detail['totalreading'],
                                    'badCount' => $temp_comp_detail['badcount'],
                                    'mutedCount' => $temp_comp_detail['mutedcount'],
                                    'temp_max' => $temp_comp_detail['temp_max'],
                                    'temp_min' => $temp_comp_detail['temp_min'],
                                );
                            }
                        }
                    }
                }
            }
        }
        $withtemp1 = $withtemp2 = '';
        $goodCounttemp1 = $goodCounttemp2 = '';
        $totalcounttemp1 = $badcounttemp2 = $totalcounttemp1_temp2 = '';
        $noncompliancecounttemp1 = $noncompliancecounttemp2 = '';
        $temp1_compliance_percent = $temp2_compliance_percent = '';
        $compliancecounttemp1 = $compliancecounttemp2 = '';
        $temp_maxtemp1 = $temp_maxtemp2 = $temp_maxtemp1_temp2 = '0';
        $temp_mintemp1 = $temp_mintemp2 = $temp_mintemp1_temp2 = '0';
        $totalcounttemp2 = $compliancecounttemp1_temp2 = $goodCounttemp1_temp2 = $temp1_temp2_compliance_percent = 0;

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
        $totalcounttemp1_temp2 = $totalcounttemp1 + $totalcounttemp2;
        $noncompliancecounttemp1_temp2 = $noncompliancecounttemp1 + $noncompliancecounttemp2;
        $goodCounttemp1_temp2 = $goodCounttemp1 + $goodCounttemp2;
        $compliancecounttemp1_temp2 = $compliancecounttemp1 + $compliancecounttemp2;

        $temp1_temp2_compliance_percent = round($compliancecounttemp1_temp2 / $goodCounttemp1_temp2 * 100, 2);

        $temp1_temp2_noncompliance_percent = round(100 - $temp1_temp2_compliance_percent, 2);


        $my_gauge_image = $my_gaugeobj->dial_gauge($temp1_temp2_noncompliance_percent, true, 0, 100);

        if ($vehiclecount == 1 && $vehicleno != '') {
            $msg_vehiclename = $vehicleno;
        } else {
            $msg_vehiclename = '';
        }
        $imgguage = base64_encode($my_gauge_image);
         $message_data .="<td style='width:30%; text-align:center;'><img src='data:image/png;base64," . $imgguage . "' alt='Gauge' /><br><h4>". $msg_vehiclename . "</h4></td>";
        if ($i % 3 == 0) {
            $message_data .="</tr><tr>";
        }
        if($totalvehicles== $i){
            $message_data .="<td style='width:30%;'>&nbsp;</td></tr>";
        }
        $i++;
    }
    $message_data .='</table>';
}

$message = str_replace("{{TABLEDATA}}", $message_data, $message);
$message = str_replace("<td style='width:30%;'>&nbsp;</td>","", $message);
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
