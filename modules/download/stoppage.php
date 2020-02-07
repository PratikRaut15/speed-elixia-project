<?php
include_once '../../lib/system/utilities.php';
include_once '../../lib/autoload.php';
include_once '../reports/reports_stoppage_functions.php';
require_once '../reports/html2pdf.php';
require_once '../../lib/bo/simple_html_dom.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$user_details = $umanager->get_user($customer_id, $user_id);
$_SESSION['report_gen_user'] = $user_details->username;
$geocode = '1';
$vehiclemanager = new VehicleManager($customer_id);
$objUserManager = new UserManager();
$content = ob_start();
$sqliteDay = date('Y-m-d', strtotime($date)); //date('d-m-Y', strtotime("- 1 day"))
if ($veh_id == '-1') {
    $min = $vehiclemanager->getTimezoneDiffInMin($customer_id);
    if (!empty($min)) {
        $date = date('Y-m-d H:i:s', time() + $min);
        $date = date('Y-m-d H:i:s', strtotime($date) - 3600);
    } else {
        $date = date('Y-m-d H:i:s', time() - 3600);
    }
    $message = "";
    $arrGroupIds = array();
    $groups = $objUserManager->get_groups_fromuser($customer_id, $user_id);
    if (isset($groups)) {
        foreach ($groups as $group) {
            $arrGroupIds[] = $group->groupid;
        }
    } else {
        $arrGroupIds[] = 0;
    }
    $devices = $vehiclemanager->get_all_vehicles_by_group($arrGroupIds);
    $arrCustomersData = array();
    if (isset($devices) && !empty($devices)) {
        foreach ($devices as $device) {
            $location = "../../customer/" . $customer_id . "/unitno/" . $device->unitno . "/sqlite/" . $sqliteDay . ".sqlite";
            if (file_exists($location)) {
                //$location = "sqlite:" . $location;
                $objReport = new stdClass();
                $objReport->location = $location;
                $objReport->deviceId = $device->deviceid;
                $objReport->reportDate = $sqliteDay;
                $objReport->startTime = "00:00:00";
                $objReport->endTime = "23:59:59";
                $objReport->interval = 360;
                $arrData = getStoppageReportCron($objReport);
                if (isset($arrData) && !empty($arrData)) {
                    $result['status'] = 1;
                    $result['vehicleNo'] = $device->vehicleno;
                    $result['vehicleData'] = $arrData;
                    $arrCustomersData[] = $result;
                } else {
                    $result['status'] = 0;
                    $result['vehicleNo'] = $device->vehicleno;
                    $result['vehicleData'] = "";
                    $arrCustomersData[] = $result;
                }
            } else {
                $result['status'] = 0;
                $result['vehicleNo'] = $device->vehicleno;
                $result['vehicleData'] = "";
                $arrCustomersData[] = $result;
            }
        }
    }
    //print_r($arrCustomersData);
    if (isset($arrCustomersData) && !empty($arrCustomersData)) {
        $message .= "<table id='search_table_2' align='center' style='width: 1120px; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'> <tbody>";
        $message .= "<tr>";
        $message .= "<th colspan='4' > Stoppage Report </th>";
        $message .= "</tr>";
        foreach ($arrCustomersData as $data) {
            $message .= "<tr style='background-color:#CCCCCC;font-weight:bold;'><th colspan='4' >" . $data['vehicleNo'] . "</th></tr>";
            if ($data['status'] == 1) {
                $message .= "<tr style='background-color:#CCCCCC;font-weight:bold;'>";
                $message .= "<th style='height:auto;' >Start Time</th>";
                $message .= "<th style='height:auto;'>End Time</th>";
                $message .= "<th style='height:auto;'>Location</th>";
                $message .= "<th style='height:auto;'>Hold Time [HH:MM]</th>";
                $message .= "</tr>";
                foreach ($data['vehicleData'] as $row) {
                    $GeoCoder_Obj = new GeoCoder($customer_id);
                    $stoppageLocation = $GeoCoder_Obj->get_location_bylatlong($row->devicelat, $row->devicelong);
                    $secdiff = strtotime($row->endtime) - strtotime($row->starttime);
                    $minutesdiff = floor($secdiff / 60);
                    if (floor($minutesdiff / 60) < 10) {
                        $hourdiff = "0" . floor($minutesdiff / 60);
                    } else {
                        $hourdiff = floor($minutesdiff / 60);
                    }
                    if (floor($minutesdiff % 60) < 10) {
                        $hourremainder = "0" . floor($minutesdiff % 60);
                    } else {
                        $hourremainder = floor($minutesdiff % 60);
                    }
                    $minutesdiff = $hourdiff . ":" . $hourremainder;
                    $message .= "<tr>";
                    $message .= "<td style='height:auto;'>" . date(speedConstants::DEFAULT_TIME, strtotime($row->starttime)) . "</td>";
                    $message .= "<td style='height:auto;'>" . date(speedConstants::DEFAULT_TIME, strtotime($row->endtime)) . "</td>";
                    $message .= "<td style='width:350px;height:auto;'>" . wordwrap($stoppageLocation, Location_Wrap, PHP_EOL) . "</td>";
                    $message .= "<td style='height:auto;'>" . $minutesdiff . "</td>";
                    $message .= "</tr>";
                }
            } else {
                $message .= "<tr><td colspan='4'>Data Not Available / Vehicle Has Not Exceeded Hold Time Of 6 Hours.</td></tr>";
            }
        }
        $message .= "</tbody></table><br/>";
        $vehicleno = "AllVehicles";
        $cat = getCompleteStoppageReport($customer_id, $date, $date, $message, $vehicleno, 360, '00:00', '23:59', $type);
    }
} else {
    $vehicle = $vehiclemanager->get_vehicle_details_by_id($veh_id);
    if ($vehicle == null) {
        exit('Vehicle record is missing');
    }
    $vehicleno = $vehicle->vehicleno;
    $cat = getstoppagereportpdf($customer_id, $date, $date, $vehicle->deviceid, $vehicle->vehicleno, 360, '00:00', '23:59', $type);
}
$cat = trim($cat);
$content = ob_get_clean();
if ($type == 'pdf') {
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($vehicleno . "_" . $sqliteDay . "_stoppageanalysis.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else {
    //echo $date;
    $xls_filename = $vehicleno . "_" . $sqliteDay . "_stoppageanalysis.xls";
    $html = str_get_html($content);
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=$xls_filename");
    echo $html;
}

function getStoppageReportCron($objReport) {
    $location = "sqlite:" . $objReport->location;
    $devices = array();
    $query = "SELECT devicehistory.lastupdated, vehiclehistory.odometer, vehiclehistory.vehicleno, devicehistory.devicelat, vehiclehistory.vehicleid, devicehistory.devicelong
            FROM devicehistory INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $objReport->deviceId AND devicehistory.status!='F'
            AND devicehistory.lastupdated > '$objReport->reportDate $objReport->startTime'
            AND devicehistory.lastupdated < '$objReport->reportDate $objReport->endTime'
            ORDER BY devicehistory.lastupdated ASC";
    try
    {
        $database = new PDO($location);
        $result = $database->query($query);
        if (isset($result) && $result != "") {
            $lastupdated = "";
            $lastodometer = "";
            $pusharray = 1;
            foreach ($result as $row) {
                if ($lastodometer == "") {
                    $lastodometer = $row["odometer"];
                    $lastupdated = $row['lastupdated'];
                    $device = new stdClass();
                    $device->vehicleid = $row['vehicleid'];
                    $device->vehicleno = $row['vehicleno'];
                    $device->starttime = $row['lastupdated'];
                    $device->deviceid = $row['vehicleid'];
                }
                if (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) > $objReport->interval && $row["odometer"] - $lastodometer < 100 && $pusharray == 1) {
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->deviceid = $row['vehicleid'];
                    $lastodometer = $row["odometer"];
                    $lastupdated = $row['lastupdated'];
                    $pusharray = 0;
                } else {
                    if ($row["odometer"] - $lastodometer > 25) {
                        if ($pusharray == 0) {
                            $device->endtime = $row['lastupdated'];
                            $devices[] = $device;
                        }
                        $lastodometer = $row["odometer"];
                        $lastupdated = $row['lastupdated'];
                        $device = new stdClass();
                        $device->vehicleid = $row['vehicleid'];
                        $device->vehicleno = $row['vehicleno'];
                        $device->starttime = $row['lastupdated'];
                        $device->deviceid = $row['vehicleid'];
                        $pusharray = 1;
                    }
                }
            }
            if ($pusharray == 0) {
                $device->endtime = $row['lastupdated'];
                $device->deviceid = $row['vehicleid'];
                $devices[] = $device;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    return $devices;
}

?>
