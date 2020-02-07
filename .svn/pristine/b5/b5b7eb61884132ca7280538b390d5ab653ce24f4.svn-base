<?php
include_once "../../lib/system/utilities.php";
include_once '../../lib/autoload.php';
include_once '../../lib/comman_function/reports_func.php';
require_once '../reports/html2pdf.php';
require_once '../../lib/bo/simple_html_dom.php';

$objUserManager = new UserManager();
$customerManager = new CustomerManager($customer_id);
$customer = $customerManager->getcustomerdetail_byid($customer_id);

$userGroups = $objUserManager->get_groups_fromuser($customer_id, $user_id);
$arrGroups = array();
if (isset($userGroups) && !empty($userGroups)) {
    foreach ($userGroups as $group) {
        $arrGroups[] = $group->groupid;
    }
}
$customer->userid = $user_id;
$customer->roleid = '';
$customer->userGroups = $arrGroups;
$devicemanager = new DeviceManager($customer->customerno);
$devices = $devicemanager->devicesForRealtimeDataReport($customer);

if (isset($devices)) {
    $reportDate = date('d-m-Y', strtotime($date));
    $title = 'Temperature Min-Max Summary Report';
    $subTitle = array(
        "Date: $reportDate",

    );
    $sqliteDay = date('Y-m-d', strtotime($reportDate));
    $finalReport = '';
    if ($type == 'pdf') {
        $finalReport .= pdf_header($title, $subTitle, $customer);
    } else {
        $finalReport .= excel_header($title, $subTitle, $customer);
    }
    $finalReport .= processData($devices, $sqliteDay, $customer, $type);
    echo $finalReport; //die();
    $content = ob_get_clean();
    if ($type == 'pdf') {
        try {
            $html2pdf = new HTML2PDF('L', 'A4', 'en');
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($content);
            $html2pdf->Output($date . "_TemperatureMinMaxSummary.pdf");
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    } else {
        $html = str_get_html($content);
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename={$date}_TemperatureMinMaxSummary.xls");
        echo $html;
    }
}

function processData($reportData, $sqliteDay, $customer, $type) {
    $table = '';
    if (isset($customer)) {
        $Tableheader = summaryHeader();
    }

    if (isset($reportData)) {
        $Tablerows = summaryRows($reportData, $sqliteDay, $customer, $type);
    }

    if ($Tableheader != '') {
        if ($type == 'pdf') {
            $table .= "<div style='text-align:center;>";
        }

        $table .= "<table id='search_table_2' align='center' style='width: 1000px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $table .= "<thead>";
        $table .= $Tableheader;
        $table .= "</thead>";
        $table .= "<tbody>";
        if ($Tablerows != '') {
            $table .= $Tablerows;
        } else {
            $table .= "<tr><td colspan='100%'>No Data</td></tr>";
        }
        $table .= "</tbody>";
        $table .= "</table>";
        if ($type == 'pdf') {
            $table .= "</div>";
        }

    }
    return $table;
}

function summaryHeader() {
    $header = '';
    $header .= "<tr style='background-color:#CCCCCC;font-weight:bold;'>";
    $header .= "<td width='300px;'>Sensor Name</td>";
    $header .= "<td>State</td>";
    $header .= "<td>Min</td>";
    $header .= "<td>Max</td>";
    $header .= "<td>Last Reading</td>";
    $header .= "</tr>";
    return $header;
}

function summaryRows($reportData, $sqliteDay, $customer, $type) {
    $rows = '';
    $temp_coversion = new TempConversion();
    foreach ($reportData as $data) {
        // prettyPrint($data);die();

        $t1 = getName($data->n1, $customer->customerno);
        $t2 = getName($data->n2, $customer->customerno);
        $t3 = getName($data->n3, $customer->customerno);
        $t4 = getName($data->n4, $customer->customerno);
        $t1 = isset($t1) ? $t1 : 'Temperature1 ';
        $t2 = isset($t2) ? $t2 : 'Temperature2 ';
        $t3 = isset($t3) ? $t3 : 'Temperature3 ';
        $t4 = isset($t4) ? $t4 : 'Temperature4 ';

        $temp_coversion->unit_type = $data->get_conversion;
        $temp_coversion->use_humidity = $data->use_humidity;
        $temp_coversion->switch_to = 3;

        $objData = new stdClass();
        $objData->device = $data->vehicleno;
        $objData->sensor1 = $data->vehicleno . " " . $t1;
        $objData->sensor2 = $data->vehicleno . " " . $t2;
        $objData->sensor3 = $data->vehicleno . " " . $t3;
        $objData->sensor4 = $data->vehicleno . " " . $t4;

        $tempStatus1 = $tempStatus2 = $tempStatus3 = $tempStatus4 = "Inactive";

        $location = "../../customer/" . $customer->customerno . "/unitno/" . $data->unitno . "/sqlite/$sqliteDay.sqlite";
        $DATA = null;
        $min1 = $min2 = $min3 = $min4 = $max1 = $max2 = $max3 = $max4 = $analog1 = $analog2 = $analog3 = $analog4 = $last1 = $last2 = $last3 = $last4 = '';

        if (file_exists($location)) {
            $tempStatus1 = $tempStatus2 = $tempStatus3 = $tempStatus4 = speedConstants::TEMP_NOTACTIVE;
            $DATA = getSqliteData($location);
            if (isset($DATA)) {

                switch ($customer->temp_sensors) {
                case 4:
                    if ($data->tempsen4 != 0) {
                        $tempStatus4 = speedConstants::TEMP_WIRECUT;
                        $s_min = "minanalog" . $data->tempsen4;
                        $analogValueMin = isset($DATA[$s_min]) ? $DATA[$s_min] : 0;
                        $temp_coversion->rawtemp = $analogValueMin;
                        if ($analogValueMin != 0) {
                            $min4 = getTempUtil($temp_coversion) . " <sup>0</sup>C";
                        }

                        $s_max = "maxanalog" . $data->tempsen4;
                        $analogValueMax = isset($DATA[$s_max]) ? $DATA[$s_max] : 0;
                        $temp_coversion->rawtemp = $analogValueMax;
                        if ($analogValueMax != 0) {
                            $max4 = getTempUtil($temp_coversion) . " <sup>0</sup>C";
                        }

                        $s_analog = "lastanalog" . $data->tempsen4;
                        $analogValueAnalog = isset($DATA[$s_analog]) ? $DATA[$s_analog] : 0;
                        $temp_coversion->rawtemp = $analogValueAnalog;
                        if ($analogValueAnalog != 0) {
                            $analog4 = getTempUtil($temp_coversion) . " <sup>0</sup>C";
                        }
                        $last4 = $DATA['lastupdated' . $data->tempsen4];

                        if ($analogValueMin != 0 || $analogValueMax != 0 || $analogValueAnalog != 0) {
                            $tempStatus4 = 'Active';
                        }

                    }
                case 3:
                    if ($data->tempsen3 != 0) {
                        $tempStatus3 = speedConstants::TEMP_WIRECUT;
                        $s_min = "minanalog" . $data->tempsen3;
                        $analogValueMin = isset($DATA[$s_min]) ? $DATA[$s_min] : 0;
                        $temp_coversion->rawtemp = $analogValueMin;
                        if ($analogValueMin != 0) {
                            $min3 = getTempUtil($temp_coversion) . " <sup>0</sup>C";
                        }

                        $s_max = "maxanalog" . $data->tempsen3;
                        $analogValueMax = isset($DATA[$s_max]) ? $DATA[$s_max] : 0;
                        $temp_coversion->rawtemp = $analogValueMax;
                        if ($analogValueMax != 0) {
                            $max3 = getTempUtil($temp_coversion) . " <sup>0</sup>C";
                        }

                        $s_analog = "lastanalog" . $data->tempsen3;
                        $analogValueAnalog = isset($DATA[$s_analog]) ? $DATA[$s_analog] : 0;
                        $temp_coversion->rawtemp = $analogValueAnalog;
                        if ($analogValueAnalog != 0) {
                            $analog3 = getTempUtil($temp_coversion) . " <sup>0</sup>C";
                        }
                        $last3 = $DATA['lastupdated' . $data->tempsen3];

                        if ($analogValueMin != 0 || $analogValueMax != 0 || $analogValueAnalog != 0) {
                            $tempStatus3 = 'Active';
                        }

                    }
                case 2:
                    if ($data->tempsen2 != 0) {
                        $tempStatus2 = speedConstants::TEMP_WIRECUT;
                        $s_min = "minanalog" . $data->tempsen2;
                        $analogValueMin = isset($DATA[$s_min]) ? $DATA[$s_min] : 0;
                        $temp_coversion->rawtemp = $analogValueMin;
                        if ($analogValueMin != 0) {
                            $min2 = getTempUtil($temp_coversion) . " <sup>0</sup>C";
                        }

                        $s_max = "maxanalog" . $data->tempsen2;
                        $analogValueMax = isset($DATA[$s_max]) ? $DATA[$s_max] : 0;
                        $temp_coversion->rawtemp = $analogValueMax;
                        if ($analogValueMax != 0) {
                            $max2 = getTempUtil($temp_coversion) . " <sup>0</sup>C";
                        }

                        $s_analog = "lastanalog" . $data->tempsen2;
                        $analogValueAnalog = isset($DATA[$s_analog]) ? $DATA[$s_analog] : 0;
                        $temp_coversion->rawtemp = $analogValueAnalog;
                        if ($analogValueAnalog != 0) {
                            $analog2 = getTempUtil($temp_coversion) . " <sup>0</sup>C";
                        }
                        $last2 = $DATA['lastupdated' . $data->tempsen2];

                        if ($analogValueMin != 0 || $analogValueMax != 0 || $analogValueAnalog != 0) {
                            $tempStatus2 = 'Active';
                        }

                    }
                case 1;
                    if ($data->tempsen1 != 0) {
                        $tempStatus1 = speedConstants::TEMP_WIRECUT;

                        $s_min = "minanalog" . $data->tempsen1;
                        $analogValueMin = isset($DATA[$s_min]) ? $DATA[$s_min] : 0;
                        $temp_coversion->rawtemp = $analogValueMin;
                        if ($analogValueMin != 0) {
                            $min1 = getTempUtil($temp_coversion) . " <sup>0</sup>C";
                        }

                        $s_max = "maxanalog" . $data->tempsen1;
                        $analogValueMax = isset($DATA[$s_max]) ? $DATA[$s_max] : 0;
                        $temp_coversion->rawtemp = $analogValueMax;
                        if ($analogValueMax != 0) {
                            $max1 = getTempUtil($temp_coversion) . " <sup>0</sup>C";
                        }

                        $s_analog = "lastanalog" . $data->tempsen1;
                        $analogValueAnalog = isset($DATA[$s_analog]) ? $DATA[$s_analog] : 0;
                        $temp_coversion->rawtemp = $analogValueAnalog;
                        if ($analogValueAnalog != 0) {
                            $analog1 = getTempUtil($temp_coversion) . " <sup>0</sup>C";
                        }
                        $last1 = $DATA['lastupdated' . $data->tempsen1];

                        if ($analogValueMin != 0 || $analogValueMax != 0 || $analogValueAnalog != 0) {
                            $tempStatus1 = 'Active';
                        }

                    }
                    break;
                }

            }
        }
        $objData->sensor1Status = $tempStatus1;
        $objData->sensor2Status = $tempStatus2;
        $objData->sensor3Status = $tempStatus3;
        $objData->sensor4Status = $tempStatus4;
        $objData->minanalog1 = $min1;
        $objData->minanalog2 = $min2;
        $objData->minanalog3 = $min3;
        $objData->minanalog4 = $min4;
        $objData->maxanalog1 = $max1;
        $objData->maxanalog2 = $max2;
        $objData->maxanalog3 = $max3;
        $objData->maxanalog4 = $max4;
        $objData->analog1 = $analog1;
        $objData->analog2 = $analog2;
        $objData->analog3 = $analog3;
        $objData->analog4 = $analog4;
        $objData->lastupdated1 = ($last1 != '' && $last1 != '0000-00-00 00:00:00') ? date('d M y H:i', strtotime($last1)) : '';
        $objData->lastupdated2 = ($last2 != '' && $last2 != '0000-00-00 00:00:00') ? date('d M y H:i', strtotime($last2)) : '';
        $objData->lastupdated3 = ($last3 != '' && $last3 != '0000-00-00 00:00:00') ? date('d M y H:i', strtotime($last3)) : '';
        $objData->lastupdated4 = ($last4 != '' && $last4 != '0000-00-00 00:00:00') ? date('d M y H:i', strtotime($last4)) : '';
        $objData->lastreading1 = ($objData->analog1 != '') ? $objData->analog1 . " (" . $objData->lastupdated1 . ")" : '';
        $objData->lastreading2 = ($objData->analog2 != '') ? $objData->analog2 . " (" . $objData->lastupdated2 . ")" : '';
        $objData->lastreading3 = ($objData->analog3 != '') ? $objData->analog3 . " (" . $objData->lastupdated3 . ")" : '';
        $objData->lastreading4 = ($objData->analog4 != '') ? $objData->analog4 . " (" . $objData->lastupdated4 . ")" : '';

        //prettyPrint($objData);
        $rows .= printRowData($objData, $customer);

    }
    return $rows;
}

function printRowData($objData, $customer) {
    $rows = '';
    $colspan = 1 + $customer->temp_sensors;
    $rows .= "<tr>";
    $rows .= "<td style='text-align:left;background-color:#CCCCCC;font-weight:bold;' colspan='5'>" . $objData->device . "</td>";
    $rows .= "</tr>";

    $sensorRows = '';
    $sensor1 = $sensor2 = $sensor3 = $sensor4 = '';
    switch ($customer->temp_sensors) {
    case 4:
        if ($objData->sensor4Status != speedConstants::TEMP_NOTACTIVE) {
            $sensor4 .= "<tr>";
            $sensor4 .= "<td style='text-align:left;' >" . $objData->sensor4 . "</td>";
            $sensor4 .= "<td>" . $objData->sensor4Status . "</td>";
            $sensor4 .= "<td>" . $objData->minanalog4 . "</td>";
            $sensor4 .= "<td>" . $objData->maxanalog4 . "</td>";
            $sensor4 .= "<td>" . $objData->lastreading4 . "</td>";
            $sensor4 .= "</tr>";
        }
        $sensorRows = $sensor4;
    case 3:
        if ($objData->sensor3Status != speedConstants::TEMP_NOTACTIVE) {
            $sensor3 .= "<tr>";
            $sensor3 .= "<td style='text-align:left;' >" . $objData->sensor3 . "</td>";
            $sensor3 .= "<td>" . $objData->sensor3Status . "</td>";
            $sensor3 .= "<td>" . $objData->minanalog3 . "</td>";
            $sensor3 .= "<td>" . $objData->maxanalog3 . "</td>";
            $sensor3 .= "<td>" . $objData->lastreading3 . "</td>";
            $sensor3 .= "</tr>";
        }
        $sensorRows = $sensor3 . $sensorRows;
    case 2:
        if ($objData->sensor2Status != speedConstants::TEMP_NOTACTIVE) {
            $sensor2 .= "<tr>";
            $sensor2 .= "<td style='text-align:left;' >" . $objData->sensor2 . "</td>";
            $sensor2 .= "<td>" . $objData->sensor2Status . "</td>";
            $sensor2 .= "<td>" . $objData->minanalog2 . "</td>";
            $sensor2 .= "<td>" . $objData->maxanalog2 . "</td>";
            $sensor2 .= "<td>" . $objData->lastreading2 . "</td>";
            $sensor2 .= "</tr>";
        }
        $sensorRows = $sensor2 . $sensorRows;
    case 1:
        if ($objData->sensor1Status != speedConstants::TEMP_NOTACTIVE) {
            $sensor1 .= "<tr>";
            $sensor1 .= "<td style='text-align:left;' >" . $objData->sensor1 . "</td>";
            $sensor1 .= "<td>" . $objData->sensor1Status . "</td>";
            $sensor1 .= "<td>" . $objData->minanalog1 . "</td>";
            $sensor1 .= "<td>" . $objData->maxanalog1 . "</td>";
            $sensor1 .= "<td>" . $objData->lastreading1 . "</td>";
            $sensor1 .= "</tr>";
        }

        $sensorRows = $sensor1 . $sensorRows;
        break;
    }

    $rows .= $sensorRows;

    return $rows;
}

function getName($nid, $customerno) {
    $vehiclemanager = new VehicleManager($customerno);
    $vehicledata = $vehiclemanager->getNameForTemp($nid);
    return $vehicledata;
}

function getSqliteData($location) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();

    $queryAnalog1 = "SELECT min(cast(uh.analog1 as integer))as minanalog1, max(cast(uh.analog1 as integer))as maxanalog1 , latest.analog1, latest.lastupdated
    FROM unithistory  uh
    JOIN (
    SELECT unithistory.analog1, unithistory.lastupdated
    FROM unithistory
    WHERE unithistory.analog1 NOT IN (0,1150)
    ORDER BY uhid DESC
    LIMIT 1) latest
    WHERE uh.analog1 NOT IN (0,1150)";
    $resultAnalog1 = $db->query($queryAnalog1);
    if ($resultAnalog1 !== false) {
        $arrQueryAnalog1 = $resultAnalog1->fetch();
        if (is_array($arrQueryAnalog1) && !empty($arrQueryAnalog1)) {
            $REPORT['minanalog1'] = isset($arrQueryAnalog1['minanalog1']) ? $arrQueryAnalog1['minanalog1'] : null;
            $REPORT['maxanalog1'] = isset($arrQueryAnalog1['maxanalog1']) ? $arrQueryAnalog1['maxanalog1'] : null;
            $REPORT['lastanalog1'] = isset($arrQueryAnalog1['analog1']) ? $arrQueryAnalog1['analog1'] : null;
            $REPORT['lastupdated1'] = isset($arrQueryAnalog1['lastupdated']) ? $arrQueryAnalog1['lastupdated'] : null;
        }
    }

    $queryAnalog2 = "SELECT min(cast(uh.analog2 as integer))as minanalog2, max(cast(uh.analog2 as integer))as maxanalog2 , latest.analog2, latest.lastupdated
    FROM unithistory  uh
    JOIN (
    SELECT unithistory.analog2, unithistory.lastupdated
    FROM unithistory
    WHERE unithistory.analog2 NOT IN (0,1150)
    ORDER BY uhid DESC
    LIMIT 1) latest
    WHERE uh.analog2 NOT IN (0,1150)";
    $resultAnalog2 = $db->query($queryAnalog2);
    if ($resultAnalog2 !== false) {
        $arrQueryAnalog2 = $resultAnalog2->fetch();
        if (is_array($arrQueryAnalog2) && !empty($arrQueryAnalog2)) {
            $REPORT['minanalog2'] = isset($arrQueryAnalog2['minanalog2']) ? $arrQueryAnalog2['minanalog2'] : null;
            $REPORT['maxanalog2'] = isset($arrQueryAnalog2['maxanalog2']) ? $arrQueryAnalog2['maxanalog2'] : null;
            $REPORT['lastanalog2'] = isset($arrQueryAnalog2['analog2']) ? $arrQueryAnalog2['analog2'] : null;
            $REPORT['lastupdated2'] = isset($arrQueryAnalog2['lastupdated']) ? $arrQueryAnalog2['lastupdated'] : null;
        }
    }

    $queryAnalog3 = "SELECT min(cast(uh.analog3 as integer))as minanalog3, max(cast(uh.analog3 as integer))as maxanalog3 , latest.analog3, latest.lastupdated
    FROM unithistory  uh
    JOIN (
    SELECT unithistory.analog3, unithistory.lastupdated
    FROM unithistory
    WHERE unithistory.analog3 NOT IN (0,1150)
    ORDER BY uhid DESC
    LIMIT 1) latest
    WHERE uh.analog3 NOT IN (0,1150)";
    $resultAnalog3 = $db->query($queryAnalog3);
    if ($resultAnalog3 !== false) {
        $arrQueryAnalog3 = $resultAnalog3->fetch();
        if (is_array($arrQueryAnalog3) && !empty($arrQueryAnalog3)) {
            $REPORT['minanalog3'] = isset($arrQueryAnalog3['minanalog3']) ? $arrQueryAnalog3['minanalog3'] : null;
            $REPORT['maxanalog3'] = isset($arrQueryAnalog3['maxanalog3']) ? $arrQueryAnalog3['maxanalog3'] : null;
            $REPORT['lastanalog3'] = isset($arrQueryAnalog3['analog3']) ? $arrQueryAnalog3['analog3'] : null;
            $REPORT['lastupdated3'] = isset($arrQueryAnalog3['lastupdated']) ? $arrQueryAnalog3['lastupdated'] : null;
        }
    }

    $queryAnalog4 = "SELECT min(cast(uh.analog4 as integer))as minanalog4, max(cast(uh.analog4 as integer))as maxanalog4 , latest.analog4, latest.lastupdated
    FROM unithistory  uh
    JOIN (
    SELECT unithistory.analog4, unithistory.lastupdated
    FROM unithistory
    WHERE unithistory.analog4 NOT IN (0,1150)
    ORDER BY uhid DESC
    LIMIT 1) latest
    WHERE uh.analog4 NOT IN (0,1150)";
    $resultAnalog4 = $db->query($queryAnalog4);
    if ($resultAnalog4 !== false) {
        $arrQueryAnalog4 = $resultAnalog4->fetch();
        if (is_array($arrQueryAnalog4) && !empty($arrQueryAnalog4)) {
            $REPORT['minanalog4'] = isset($arrQueryAnalog4['minanalog4']) ? $arrQueryAnalog4['minanalog4'] : null;
            $REPORT['maxanalog4'] = isset($arrQueryAnalog4['maxanalog4']) ? $arrQueryAnalog4['maxanalog4'] : null;
            $REPORT['lastanalog4'] = isset($arrQueryAnalog4['analog4']) ? $arrQueryAnalog4['analog4'] : null;
            $REPORT['lastupdated4'] = isset($arrQueryAnalog4['lastupdated']) ? $arrQueryAnalog4['lastupdated'] : null;
        }
    }

    return $REPORT;
}

?>
