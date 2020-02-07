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
$todaysDate = new DateTime($date);
$todaysDate->modify('+1 day');
/* By default, the report would pickup all the records between yesterday 00:00 to todays 00:00 */
$reportFromTime = "00:00";
$reportToTime = "00:00";
switch ($customer_id) {
case speedConstants::CUSTNO_RKFOODLANDS:
    $reportFromTime = "07:00";
    $reportToTime = "07:00";
    break;
}

/* Get Log Details */
$objLog = new stdClass();
$objLog->logFromDate = $reportDate->format(speedConstants::DATE_Ymd) . " " . $reportFromTime;
$objLog->logToDate = $todaysDate->format(speedConstants::DATE_Ymd) . " " . $reportToTime;
$objLog->customerno = $customer->customerno;
$objCustomerManager = new CustomerManager();
$logDetails = $objCustomerManager->getChkptOwnerLog($objLog);
//prettyPrint($logDetails);
if (isset($logDetails)) {
    $reportDate = $reportDate->format(speedConstants::DEFAULT_DATE);
    $title = 'Checkpoint Owner SMS / Email Log Report';
    $subTitle = array(
        "Date: $reportDate",
    );
    $finalReport = '';
    if ($type == 'pdf') {
        $finalReport .= pdf_header($title, $subTitle, $customer);
    } else {
        $finalReport .= excel_header($title, $subTitle, $customer);
    }
    $finalReport .= processData($logDetails, $type);
    echo $finalReport;
    $content = ob_get_clean();
    if ($type == 'pdf') {
        try {
            $html2pdf = new HTML2PDF('L', 'A4', 'en');
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($content);
            $html2pdf->Output($date . "_checkpointOwenerLog.pdf");
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    } else {
        $html = str_get_html($content);
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename={$date}_checkpointOwenerLog.xls");
        echo $html;
    }
}

function processData($logDetails, $type) {
    $table = '';

    $Tableheader = reportTableHeader();

    if (isset($logDetails)) {
        $Tablerows = reportTableRows($logDetails, $type);
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

function reportTableHeader() {
    $header = '';
    $header .= "<tr style='background-color:#CCCCCC;font-weight:bold;'>";
    $header .= "<td>Sr. No</td>";
    $header .= "<td>Zone</td>";
    $header .= "<td>Vehicle No</td>";
    $header .= "<td>Outlet Sequence</td>";
    $header .= "<td>Outlet Name</td>";
    $header .= "<td>Message Sent Time</td>";
    $header .= "<td>Mobile No</td>";
    $header .= "<td>Email Id</td>";
    $header .= "<td>SMS Text</td>";
    $header .= "<td>Email Body</td>";
    $header .= "</tr>";
    return $header;
}

function reportTableRows($logDetails, $type) {
    $rows = '';
    $i = 1;
    foreach ($logDetails as $data) {
        //$data = (object) $data;
        $message = $data->message;
        $emailMessage = $data->emailMessage;
        if (strtolower(trim($type)) == 'pdf') {
            $message = wordwrap($data->message, Location_Wrap, "<br/>");
            $emailMessage = wordwrap($data->emailMessage, Location_Wrap, "<br/>");
        }

        $rows .= "<tr>";
        $rows .= "<td> " . $i++ . "</td>";
        $rows .= "<td> " . $data->groupname . "</td>";
        $rows .= "<td> " . $data->vehicleno . "</td>";
        $rows .= "<td> " . $data->sequence . "</td>";
        $rows .= "<td> " . $data->cname . "</td>";
        $rows .= "<td> " . $data->senttime . "</td>";
        $rows .= "<td> " . $data->mobileno . "</td>";
        $rows .= "<td> " . $data->emailid . "</td>";
        $rows .= "<td> " . $message . "</td>";
        $rows .= "<td> " . $emailMessage . "</td>";
        $rows .= "</tr>";

    }
    return $rows;
}
