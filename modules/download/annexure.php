<?php
error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
include_once "../../lib/system/utilities.php";
include_once '../../lib/autoload.php';
include_once '../../lib/comman_function/reports_func.php';
require_once '../reports/html2pdf.php';
require_once '../../lib/bo/simple_html_dom.php';
ini_set("memory_limit", "512M");
$customerManager = new CustomerManager($customer_id);
$customer = $customerManager->getcustomerdetail_byid($customer_id);

$reportStartDate = new DATETIME($date);
$reportStartDate = $reportStartDate->sub(new DateInterval('P1M'));
$reportStartDate = $reportStartDate->modify('first day of this month');


$reportEndDate = new DATETIME($date);
$reportEndDate = $reportEndDate->sub(new DateInterval('P1M'));
$reportEndDate = $reportEndDate->modify('last day of this month');

$objRequest = new stdClass();
$objRequest->startDate = $reportStartDate->format(speedConstants::DEFAULT_DATE);
$objRequest->endDate = $reportEndDate->format(speedConstants::DEFAULT_DATE);
$objRequest->customerNo = $customer_id;

$reportData = getAnnexureDetails($objRequest);
//prettyPrint($reportData);die();

if (isset($reportData)) {
    $reportDate = date('d-m-Y', strtotime($date));
    $title = 'Annexure Report';
    $subTitle = array(
        "Date: $reportDate",
    );
    $finalReport = '';
    if ($type == 'pdf') {
        $finalReport .= pdf_header($title, $subTitle, $customer);
    } else {
        $finalReport .= excel_header($title, $subTitle, $customer);
    }
    $finalReport .= processData($reportData, $objRequest, $customer, $type);
    echo $finalReport;die();
    $content = ob_get_clean();
    if ($type == 'pdf') {
        try {
            $html2pdf = new HTML2PDF('L', 'A4', 'en');
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($content);
            $html2pdf->Output($date . "_annexurereport.pdf");
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    } else {
        $html = str_get_html($content);
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename={$date}_annexurereport.xls");
        echo $content;
    }
}

function processData($reportData, $objRequest, $customer, $type) {
    $table = '';
    if (isset($customer)) {
        $Tableheader = annexureHeader($objRequest, $customer);
    }

    if (isset($reportData)) {
        $Tablerows = annexureRows($reportData, $objRequest, $customer, $type);
    }

    if ($Tableheader != '') {
        $table .= "<table id='search_table_2' style='width: 1000px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $table .= "<tbody>";
        $table .= $Tableheader;
        if ($Tablerows != '') {
            $table .= $Tablerows;
        } else {
            $table .= "<tr><td colspan='100%'>No Data</td></tr>";
        }
        $table .= "</tbody>";
        $table .= "</table>";
    }
    return $table;
}

function annexureHeader($objRequest, $customer) {
    $STdate = date('d-m-Y', strtotime($objRequest->startDate));
    $EDdate = date('d-m-Y', strtotime($objRequest->endDate));
    $t_columns = '';
    $colspan = 0;
    while (strtotime($STdate) <= strtotime($EDdate)) {
        $t_columns .= "<td>" . substr($STdate, 0, 5) . "</td>";
        $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
        $colspan++;
    }
    if ($colspan > 15) {
        echo "<style>.newTable th, .newTable td{padding:3px;}</style>";
    }

    $header = '';
    $header .= "<tr>";
    $header .= "<th>#</th>";
    $header .= "<th>Vehicle No</th>";
    $header .= "<th>Status</th>";
    $header .= "<th colspan=" . $colspan . ">Date[DD-MM]</th>";
    $header .= "<th colspan='3'>Total</th>";
    $header .= "</tr>";
    $header .= "<tr class='tableSub' >";
    $header .= "<td></td>";
    $header .= "<td></td>";
    $header .= "<td></td>";
    $header .= $t_columns;
    $header .= "<td>W</td>";
    $header .= "<td>N</td>";
    $header .= "<td>NA</td>";
    $header .= "</tr>";
    return $header;
}

function annexureRows($reportData, $objRequest, $customer, $type) {
    $rows = '';
    $i = 1;
    $objVehicleManager = new VehicleManager($customer->customerno);
    $all_data = array();
    $customer_vehicles = vehicles_array($objVehicleManager->get_all_vehicles());

    foreach ($reportData as $report) {
        if (!array_key_exists($report->vehicleId, $customer_vehicles)) {
            continue;
        }
        $reportDate = $report->reportDate;
        $all_data[$report->vehicleId][$reportDate] = $report;
    }
    $totaldays = gendays_cmn($objRequest->startDate, $objRequest->endDate);
    $count = 1;
    //prettyPrint($all_data);die();
    foreach ($all_data as $key => $data) {
        //prettyPrint($data); die();
        $veh_number = $customer_vehicles[$key]['vehno'];
        $rows .= "<tr>";
        $rows .= "<td style='text-align:right;' >$count</td>";
        $rows .= "<td style='text-align:right;font-weight:bold;'>$veh_number</td>";
        $rows .= "<td>";
        $rows .= "<table>";
        $rows .= "<tr>";
        $rows .= "<td>IsActive</td>";
        $rows .= "</tr>";
        $rows .= "<tr>";
        $rows .= "<td>IsTemeprature</td>";
        $rows .= "</tr>";
        $rows .= "<tr>";
        $rows .= "<td>IsHimidity</td>";
        $rows .= "</tr>";
        $rows .= "<tr>";
        $rows .= "<td>isDigital</td>";
        $rows .= "</tr>";
        $rows .= "</table>";
        $rows .= "</td>";
        $isDeviceWorking = 0;
        $isTemperatureWorking = 0;
        $isHumidityWorking = 0;
        $isDigitalWorking = 0;
        $isDeviceNotWorking = 0;
        $isTemperatureNotWorking = 0;
        $isHumidityNotWorking = 0;
        $isDigitalNotWorking = 0;
        $isDeviceNA = 0;
        $isTemperatureNA = 0;
        $isHumidityNA = 0;
        $isDigitalNA = 0;
        foreach ($totaldays as $single_date) {
            $custom = isset($data[$single_date]) ? $data[$single_date] : 'NA';
            //prettyPrint($custom);die();
            if ($custom == 'NA') {
                $rows .= "<td>" . $custom . "</td>";
                $isDeviceNA++;
                $isTemperatureNA++;
                $isHumidityNA++;
                $isDigitalNA++;
            } else {
                ($custom->isActive == "W") ? $isDeviceWorking++ : (($custom->isActive == "N") ? $isDeviceNotWorking++ : $isDeviceNA++);
                ($custom->isTemperature == "W") ? $isTemperatureWorking++ : (($custom->isTemperature == "N") ? $isTemperatureNotWorking++ : $isTemperatureNA++);
                ($custom->isHumidity == "W") ? $isHumidityWorking++ : (($custom->isHumidity == "N") ? $isHumidityNotWorking++ : $isHumidityNA++);
                ($custom->isDigital == "W") ? $isDigitalWorking++ : (($custom->isDigital == "N") ? $isDigitalNotWorking++ : $isDigitalNA++);

                $rows .= "<td>";
                $rows .= "<table style='width:100%;'>";
                $rows .= "<tr>";
                $rows .= "<td>" . $custom->isActive . "</td>";
                $rows .= "</tr>";
                $rows .= "<tr>";
                $rows .= "<td>" . $custom->isTemperature . "</td>";
                $rows .= "</tr>";
                $rows .= "<tr>";
                $rows .= "<td>" . $custom->isHumidity . "</td>";
                $rows .= "</tr>";
                $rows .= "<tr>";
                $rows .= "<td>" . $custom->isDigital . "</td>";
                $rows .= "</tr>";
                $rows .= "</table>";
                $rows .= "</td>";
            }
        }

        $rows .= "<td style='text-align:right;font-weight:bold;'>";
        $rows .= "<table style='width:100%;'>";
        $rows .= "<tr>";
        $rows .= "<td>" . $isDeviceWorking . "</td>";
        $rows .= "</tr>";
        $rows .= "<tr>";
        $rows .= "<td>" . $isTemperatureWorking . "</td>";
        $rows .= "</tr>";
        $rows .= "<tr>";
        $rows .= "<td>" . $isHumidityWorking . "</td>";
        $rows .= "</tr>";
        $rows .= "<tr>";
        $rows .= "<td>" . $isDigitalWorking. "</td>";
        $rows .= "</tr>";
        $rows .= "</table>";
        $rows .= "</td>";
        $rows .= "<td style='text-align:right;font-weight:bold;'>";
        $rows .= "<table style='width:100%;'>";
        $rows .= "<tr>";
        $rows .= "<td>" . $isDeviceNotWorking . "</td>";
        $rows .= "</tr>";
        $rows .= "<tr>";
        $rows .= "<td>" . $isTemperatureNotWorking . "</td>";
        $rows .= "</tr>";
        $rows .= "<tr>";
        $rows .= "<td>" . $isHumidityNotWorking . "</td>";
        $rows .= "</tr>";
        $rows .= "<tr>";
        $rows .= "<td>" . $isDigitalNotWorking . "</td>";
        $rows .= "</tr>";
        $rows .= "</table>";
        $rows .="</td>";
        $rows .= "<td style='text-align:right;font-weight:bold;'>";
        $rows .= "<table style='width:100%;'>";
        $rows .= "<tr>";
        $rows .= "<td>" . $isDeviceNA . "</td>";
        $rows .= "</tr>";
        $rows .= "<tr>";
        $rows .= "<td>" . $isTemperatureNA . "</td>";
        $rows .= "</tr>";
        $rows .= "<tr>";
        $rows .= "<td>" . $isHumidityNA . "</td>";
        $rows .= "</tr>";
        $rows .= "<tr>";
        $rows .= "<td>" . $isDigitalNA . "</td>";
        $rows .= "</tr>";
        $rows .= "</table>";
        $rows .= "</td>";
        $rows .= "</tr>";
        $count++;
    }
    return $rows;
}

function getAnnexureDetails($objRequest) {
    $totaldays = gendays_cmn($objRequest->startDate, $objRequest->endDate);
    $location = "../../customer/" . $objRequest->customerNo . "/reports/annexure.sqlite";
    $DATA = null;
    if (file_exists($location)) {
        $DATA = getAnnexureDetailsData($location, $totaldays);
    }
    return $DATA;
}

function getAnnexureDetailsData($location, $days) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();
    if (isset($days)) {
        foreach ($days as $day) {
            $sqlday = date("dmy", strtotime($day));
            $query = "SELECT * from A$sqlday order by vehicleid ASC";
            $result = $db->query($query);
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $objData = new stdClass();
                    $objData->reportDate = $day;
                    $objData->unitId = $row['uid'];
                    $objData->vehicleId = $row['vehicleid'];
                    $objData->customerNo = $row['customerno'];
                    $objData->isActive = ($row['isActive'] == 1) ? "W" : (($row['isActive'] == 0) ? "N" : "NA");
                    $objData->isTemperature = ($row['isTemperature'] == 1) ? "W" : (($row['isTemperature'] == 0) ? "N" : "NA");
                    $objData->isHumidity = ($row['isHumidity'] == 1) ? "W" : (($row['isHumidity'] == 0) ? "N" : "NA");
                    $objData->isDigital = ($row['isDigital'] == 1) ? "W" : (($row['isDigital'] == 0) ? "N" : "NA");
                    $REPORT[] = $objData;
                }
            }
        }
    }
    return $REPORT;
}

?>
