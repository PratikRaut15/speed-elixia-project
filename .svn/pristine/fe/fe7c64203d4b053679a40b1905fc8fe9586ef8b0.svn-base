<?php
include_once "../../lib/system/utilities.php";
include_once '../../lib/autoload.php';
include_once '../../lib/comman_function/reports_func.php';
require_once '../reports/html2pdf.php';
require_once '../../lib/bo/simple_html_dom.php';

$customerManager = new CustomerManager($customer_id);
$customer = $customerManager->getcustomerdetail_byid($customer_id);
//prettyPrint($customer);
//DATE_Ymd;
$reportDate = new DATETIME($date);
$devicemanager = new DeviceManager($customer->customerno);
$realtimeData = $devicemanager->getRealtimeData($customer->customerno, $user_id, $reportDate->format(speedConstants::DATE_Ymd));
//prettyPrint($realtimeData);
if (isset($realtimeData)) {
    $reportDate = date('d-m-Y h:i a', strtotime($realtimeData[0]['created_on']));
    $title = 'RealtimeData Report';
    $subTitle = array(
        "Date: $reportDate",
    );
    $finalReport = '';
    if ($type == 'pdf') {
        $finalReport .= pdf_header($title, $subTitle, $customer);
    } else {
        $finalReport .= excel_header($title, $subTitle, $customer);
    }
    $finalReport .= processData($realtimeData, $customer, $type);
    echo $finalReport;
    $content = ob_get_clean();
    if ($type == 'pdf') {
        try {
            $html2pdf = new HTML2PDF('L', 'A4', 'en');
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($content);
            $html2pdf->Output($date . "_realtimedatareport.pdf");
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    } else {
        $html = str_get_html($content);
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename={$date}_realtimedatareport.xls");
        echo $html;
    }
}

function processData($realtimeData, $customer, $type) {
    $table = '';
    if (isset($customer)) {
        $Tableheader = realtimeHeader($customer);
    }

    if (isset($realtimeData)) {
        $Tablerows = realtimeRows($realtimeData, $customer, $type);
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

function realtimeHeader($customer) {
    $header = '';
    $header .= "<tr style='background-color:#CCCCCC;font-weight:bold;'>";
    $header .= "<td>Sr. No</td>";
    $header .= "<td>Vehicle No</td>";
    $header .= "<td>Lastupdated</td>";
    if ($customer->use_buzzer) {
        $header .= "<td>Buzzer</td>";
    }
    if ($customer->use_immobiliser) {
        $header .= "<td>Immobliser</td>";
    }
    if ($customer->use_freeze) {
        $header .= "<td>Freeze</td>";
    }
    $header .= "<td>Group</td>";
    $header .= "<td>Status</td>";
    $header .= "<td>DriverName</td>";
    $header .= "<td>DriverPhone</td>";

    $header .= "<td>Location</td>";
    $header .= "<td>Speed</td>";
    $header .= "<td>Distance</td>";
    $header .= "<td>Power</td>";
    if ($customer->use_ac_sensor) {
        $header .= "<td>AC Status</td>";
    }
    if ($customer->use_genset_sensor) {
        $header .= "<td>Genset Status</td>";
    }
    if ($customer->use_door_sensor) {
        $header .= "<td>Door Status</td>";
    }
    if ($customer->temp_sensors) {
        $dataString = '';
        switch ($customer->temp_sensors) {
        case 4:
            $dataString = "<td>Temperature 4</td>";

        case 3:
            $dataString = "<td>Temperature 3</td>" . $dataString;

        case 2:
            $dataString = "<td>Temperature 2</td>" . $dataString;

        case 1:
            $dataString = "<td>Temperature 1</td>" . $dataString;
            break;
        }
        $header .= $dataString;
    }
    if ($customer->use_extradigital) {
        $header .= "<td>Genset 1</td>";
        $header .= "<td>Genset 2</td>";
    }
    if ($customer->use_humidity) {
        $header .= "<td>Humidity</td>";
    }
    $header .= "</tr>";
    return $header;
}

function realtimeRows($realtimeData, $customer, $type) {
    $rows = '';
    $i = 1;
    foreach ($realtimeData as $data) {
        $data = (object) $data;
        $driverphone = '-';
        if ($data->driverphone != '8888888888' && $data->drivername != '2222222222') {
            $driverphone = $data->driverphone;
        }
        $power = ($data->power) ? "On" : "Off";
        $location = $data->location;
        if (strtolower(trim($type)) == 'pdf') {
            $location = wordwrap($data->location, Location_Wrap, PHP_EOL);
            $status = wordwrap($data->status,10,PHP_EOL);
            $ac_status = wordwrap($data->ac_status,10,PHP_EOL);
            $door_status = wordwrap($data->door_status,10,PHP_EOL);
        }

        $rows .= "<tr>";
        $rows .= "<td> " . $i++ . "</td>";
        $rows .= "<td> " . $data->vehicleno . "</td>";
        $rows .= "<td> " . $data->lastupdated . " </td>";
        if ($customer->use_buzzer) {
            $buzzer = ($data->is_buzzer) ? "On" : "Off";
            $rows .= "<td>" . $buzzer . "</td>";
        }
        if ($customer->use_immobiliser) {
            $mobiliser = ($data->is_mobiliser) ? "On" : "Off";
            $rows .= "<td>" . $mobiliser . "</td>";
        }
        if ($customer->use_freeze) {
            $freeze = ($data->is_freeze) ? "On" : "Off";
            $rows .= "<td>" . $freeze . "</td>";
        }

        if (isset($data->groupname)) {
            $rows .= "<td>" . $data->groupname . "</td>";
        } else {
            $rows .= "<td>Ungrouped</td>";
        }
        $rows .= "<td>" . $status . "</td>";
        $rows .= "<td>" . $data->drivername . "</td>";

        $rows .= "<td>" . $driverphone . "</td>";

        $rows .= "<td>" . $location . "</td>";
        $rows .= "<td>" . $data->speed . "</td>";
        $rows .= "<td>" . $data->distance . "</td>";

        $rows .= "<td>" . $power . "</td>";
        if ($customer->use_ac_sensor) {
            $rows .= "<td>" . $ac_status . "</td>";
        }
        if ($customer->use_genset_sensor) {
            $rows .= "<td>" . $ac_status . "</td>";
        }
        if ($customer->use_door_sensor) {
            $rows .= "<td>" . $door_status . "</td>";
        }
        if ($customer->temp_sensors) {
            $dataString = '';
            switch ($customer->temp_sensors) {
            case 4:
                $dataString = "<td>" . $data->temperature4 . "</td>";

            case 3:
                $dataString = "<td>" . $data->temperature3 . "</td>" . $dataString;

            case 2:
                $dataString = "<td>" . $data->temperature2 . "</td>" . $dataString;

            case 1:
                $dataString = "<td>" . $data->temperature1 . "</td>" . $dataString;
                break;
            }
            $rows .= $dataString;
        }
        if ($customer->use_extradigital) {
            $rows .= "<td>" . $data->genset1 . "</td>";
            $rows .= "<td>" . $data->genset2 . "</td>";
        }
        if ($customer->use_humidity) {
            $rows .= "<td>" . $data->humidity . "</td>";
        }
        $rows .= "</tr>";

    }
    return $rows;
}
