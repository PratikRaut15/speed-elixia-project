<?php
include_once '../../lib/system/utilities.php';
include_once '../../lib/comman_function/reports_func.php';
include_once '../../lib/autoload.php';
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}
function getVehicleSummary($sdate, $edate, $vehicleid, $vehicleno, $customerno, $reportType) {
    $arrSummaryData = array();
    $tableHeader = '';
    $tableRows = '';
    $conditionalHeader  = '';
    $conditional_abbreviations = '';
    $totaldays = gendays_cmn($sdate, $edate);
    $objCustomerManager = new CustomerManager();
    $customer_details = $objCustomerManager->getcustomerdetail_byid($customerno);
    $location = "../../customer/" . $customerno . "/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $arrSummaryData = getVehicleWiseDailyReportData($location, $totaldays, $vehicleid);
        if (isset($arrSummaryData)) {
            $title = 'Vehicle Summary Report';
            $subTitle = array(
                "Vehicle No: {$vehicleno}",
                "Start Date: {$sdate}",
                "End Date: {$edate}",
            );
            if ($reportType == speedConstants::REPORT_PDF) {
                $tableHeader .= pdf_header($title, $subTitle, $customer_details);
            } elseif ($reportType == speedConstants::REPORT_XLS) {
                $tableHeader .= excel_header($title, $subTitle, $customer_details);
            } else {
                $tableHeader .= table_header($title, $subTitle);
            }
            if ($customer_details->use_ac_sensor) {
                $conditionalHeader .= "<td>ACU</td>";
                $conditional_abbreviations .= "<tr><td style='border;none;'>ACU: AC Usage [HH:MM]</td></tr>";
            }
            if ($customer_details->use_genset_sensor) {
                $conditionalHeader .= "<td>GNU</td>";
                $conditional_abbreviations .= "<tr><td style='border;none;'>GNU: Genset Usage [HH:MM]</td></tr>";
            }
            if ($customer_details->use_door_sensor) {
                $conditionalHeader .= "<td>DSU</td>";
                $conditional_abbreviations .= " <tr><td style='border;none;'>DSU: Door Sensor Usage [HH:MM]</td></tr>";
            }
            $tableRows .= processVehicleSummary($arrSummaryData, $customer_details, $reportType);
            $arrSummaryData['tableHeader'] = $tableHeader;
            $arrSummaryData['conditionalHeader'] = $conditionalHeader;
            $arrSummaryData['conditional_abbreviations'] = $conditional_abbreviations;
            $arrSummaryData['tableRows'] = $tableRows;
        }
    } else {
        echo "File Not exists";
    }
    //prettyPrint($arrSummaryData);
    return $arrSummaryData;
}
function getVehicleWiseDailyReportData($location, $days, $vehicleid) {
    $dayscount = count($days);
    $totalmin = $dayscount * 24 * 60;
    $REPORT = array();
    if (isset($days)) {
        foreach ($days as $day) {
            $path = "sqlite:$location";
            $db = new PDO($path);
            $sqlday = date("dmy", strtotime($day));
            $datechk = date("Y-m-d", strtotime($day));
            $query = "SELECT * from A" . $sqlday . " WHERE vehicleid = " . $vehicleid;
            $result = $db->query($query);
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $objSummaryData = new stdClass();
                    $objSummaryData->uid = $row['uid'];
                    $objSummaryData->reportDate = $datechk;
                    $objSummaryData->avgspeed = $row['avgspeed'];
                    $objSummaryData->genset = $row['genset'];
                    $objSummaryData->overspeed = $row['overspeed'];
                    $objSummaryData->totaldistance = $row['totaldistance'];
                    $objSummaryData->fenceconflict = $row['fenceconflict'];
                    $objSummaryData->idletime = $row['idletime'];
                    $objSummaryData->runningtime = $row['runningtime'];
                    $objSummaryData->vehicleid = $row['vehicleid'];
                    $objSummaryData->dev_lat = $row['dev_lat'];
                    $objSummaryData->dev_long = $row['dev_long'];
                    $objSummaryData->first_dev_lat = $row['first_dev_lat'];
                    $objSummaryData->first_dev_long = $row['first_dev_long'];
                    $objSummaryData->harsh_break = $row['harsh_break'];
                    $objSummaryData->sudden_acc = $row['sudden_acc'];
                    if ($row['towing'] == 0) {
                        $objSummaryData->towing = "No";
                    } else {
                        $objSummaryData->towing = "Yes";
                    }
                    $objSummaryData->overspeed = $row['overspeed'];
                    $objSummaryData->topspeed = $row['topspeed'];
                    $objSummaryData->topspeed_lat = $row['topspeed_lat'];
                    $objSummaryData->topspeed_long = $row['topspeed_long'];
                    $objSummaryData->avgdistance = $row['average_distance'];
                    $objSummaryData->idleignitionontime = isset($row['idleignitiontime']) ? $row['idleignitiontime'] : '';
                    $objSummaryData->idleignitionofftime = isset($row['idleignitiontime']) ? $totalmin - $row['idleignitiontime'] - $row['runningtime'] : '';
                    $objSummaryData->firstodometer = isset($row['first_odometer']) ? $row['first_odometer'] : '';
                    $objSummaryData->lastodometer = isset($row['last_odometer']) ? $row['last_odometer'] : '';
                    $objSummaryData->freezeIgnitionOnTime = $row['freezeIgnitionOnTime'];
                    $objCustomerManager = new CustomerManager();
                    $objSummaryData->groupname = $objCustomerManager->getgroupname($row['uid']);
                    $REPORT[] = $objSummaryData;
                }
            }
        }
    }
    return $REPORT;
}
function processVehicleSummary($arrReportData, $customerDetails, $reportType) {
    $table = '';
    if (isset($arrReportData)) {
        $Tablerows = reportTableRows($arrReportData, $customerDetails, $reportType);
    }
    if ($Tablerows != '') {
        $table .= $Tablerows;
    } else {
        $table .= "<tr><td colspan='100%'>Data Not Available</td></tr>";
    }

    return $table;
}
function reportTableRows($arrReportData, $customerDetails, $reportType) {
    $rows = '';
    $i = 1;
    $width = '';
    if($reportType == speedConstants::REPORT_PDF) {
        $width = "style='width:120px;'";
    }
    $geocode = isset($_POST['geocode']) ? $_POST['geocode'] : null;
    foreach ($arrReportData as $data) {
        $data = (object) $data;
        $basic_details = getVehicleDetails($data->vehicleid, $customerDetails->customerno);
        $startLocation = location_cmn($data->first_dev_lat, $data->first_dev_long, $geocode, $customerDetails->customerno);
        $endLocation = location_cmn($data->dev_lat, $data->dev_long, $geocode, $customerDetails->customerno);
        $topSpeedLocation = location_cmn($data->topspeed_lat, $data->topspeed_long, $geocode, $customerDetails->customerno);
        $distanceTravel = abs(round($data->totaldistance / 1000, 2));
        
        /* HighLight Cell*/
        $higlightOverSpeedCell = '';
        $higlightOverHarshBreakCell = '';
        $higlightOverSuddenAccCell = '';
        $higlightCell = '';
        if ($data->overspeed > 0) {
            $higlightCell = "style='background:#FFE0CC;'";
        }
        if ($data->harsh_break > 0) {
            $higlightOverHarshBreakCell = "style='background:#FFE0CC;'";
        }
        if ($data->sudden_acc > 0) {
            $higlightOverSuddenAccCell = "style='background:#FFE0CC;'";
        }
        $rows .= "<tr>";
        $rows .= "<td> " . $i++ . "</td>";
        $rows .= "<td > " . date("d-m-Y",strtotime($data->reportDate)) . "</td>";
        $rows .= "<td> " . $basic_details->drivername . "</td>";
        $rows .= "<td> " . $data->groupname . "</td>";
        $rows .= "<td ".$width."> " . $startLocation . "</td>";
        $rows .= "<td ".$width."> " . $endLocation . "</td>";
        $rows .= "<td ".$width."> " . $topSpeedLocation . "</td>";
        $rows .= "<td> " . $distanceTravel . "</td>";
        $rows .= "<td> " . round(($data->firstodometer / 1000), 2) . "</td>";
        $rows .= "<td> " . round(($data->lastodometer / 1000), 2). "</td>";
        $rows .= "<td> " . abs(round(($data->avgspeed / 1000), 2)) . "</td>";
        $rows .= "<td> " . convertMinsToHours($data->runningtime) . "</td>";
        $rows .= "<td> " . convertMinsToHours($data->idleignitionontime) . "</td>";
        $rows .= "<td> " . convertMinsToHours($data->idleignitionofftime). "</td>";
        if ($customerDetails->use_ac_sensor == 1 || $customerDetails->use_genset_sensor == 1 || $customerDetails->use_door_sensor == 1) {
            $rows .= "<td>" . convertMinsToHours($data->genset) . "</td>";
        }
        $rows .= "<td " . $higlightCell . ">
            <a target='_blank' href='../reports/reports.php?id=14&sdate=$data->reportDate&edate=$data->reportDate&stime=00:00&etime=23:59&
            vehicleno=$basic_details->vehicleno&deviceid=$basic_details->vehicleid' style='text-decoration:underline;'>"
        . $data->overspeed . "/(" . $basic_details->overspeed_limit . ")" . "</a></td>";
        $rows .= "<td> " . $data->topspeed . "</td>";
        $rows .= "<td " . $higlightOverHarshBreakCell . ">" . $data->harsh_break . "</td>";
        $rows .= "<td " . $higlightOverSuddenAccCell . ">" . $data->sudden_acc . "</td>";
        $rows .= "<td> " . $data->towing . "</td>";
        $rows .= "<td> " . convertMinsToHours($data->freezeIgnitionOnTime) . "</td>";
        $rows .= "</tr>";
    }
    return $rows;
}
//<editor-fold defaultstate="collapsed" desc="Report Helper Function">
function getVehicleDetails($vehicleid, $customerno) {
    $objVehicle = new VehicleManager($customerno);
    $vehicles = $objVehicle->get_vehicle_details_pdf($vehicleid, $customerno);
    return $vehicles;
}

function convertMinsToHours($time){
    $time = $time*60;
    $dt = new DateTime("@$time");
    return $dt->format('H:i');
}

//</editor-fold>
?>
