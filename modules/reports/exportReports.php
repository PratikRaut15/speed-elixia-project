<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
set_time_limit(0);
date_default_timezone_set("Asia/Calcutta");
include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/CustomerManager.php';
// <editor-fold defaultstate="collapsed" desc="Default-Variables">

$customerno = (int) $_REQUEST['customerno'];
$mail_type = isset($_REQUEST['mailType']) ? $_REQUEST['mailType'] : 'pdf';
$ext = ($mail_type == 'pdf') ? '.pdf' : '.xls';
$reportType = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'PDF';
$report_name = isset($_REQUEST['report']) ? $_REQUEST['report'] : 'exit';
$filesuffix = $subject = '';
$mail_body = isset($_REQUEST['mail_content']) ? $_REQUEST['mail_content'] : 'Please find attached report.';
$serverpath = "../..";
$def_mail = 'sanketsheth@elixiatech.com';
$to = isset($_REQUEST['emailid']) ? $_REQUEST['emailid'] : $def_mail;
$arrToMailIds = explode(",", $to);
//Remove empty elements of an array
$arrToMailIds = array_filter($arrToMailIds);
$vehicleno = isset($_REQUEST['vehicleno']) ? str_replace(' ', '', $_REQUEST['vehicleno']) : '';
$subTitle = array();
$columns = array();
$savefile = 0;
/*Send Mail Report*/
$objEmail = new stdClass();
$objEmail->arrToMailIds = $arrToMailIds;
$objEmail->strBCCMailIds = '';
$objEmail->strCCMailIds = '';
$objEmail->mail_body = $mail_body;
$objEmail->ext = $ext;
$cm = new CustomerManager($customerno);
$customer_details = $cm->getcustomerdetail_byid($customerno);
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Toggle Switch History">
if ($_REQUEST['report'] == 'toggleswitchhistory') {
    ob_start();
    include_once 'reports_toggleswitch_functions.php';
    $filesuffix = "toggleswitchhistory";
    if ($_REQUEST['vehicleno'] != '') {
        $subject = "Toggle Switch History for " . $_REQUEST['vehicleno'];
    } else {
        $subject = "Toggle Switch History";
    }
    $title = getTitle("Toggle Switch History");
    $subTitle = getSubtitle($_REQUEST);
    $columns = getColumns($reportType);
    $arrToggleSwitchDetails = isset($_SESSION['arrToggleSwitchDetails']) ? $_SESSION['arrToggleSwitchDetails'] : '';
    if ($arrToggleSwitchDetails == '') {
        $arrToggleSwitchDetails = getToggleSwitchReport($_REQUEST['STdate'], $_REQUEST['EDdate'], $_REQUEST['STime'], $_REQUEST['ETime'], $_REQUEST['vehicleid'], $_REQUEST['groupid'], $customerno);
    }
    toggleswitchhistory_html($title, $subTitle, $columns, $customer_details, $arrToggleSwitchDetails, $reportType);
    $content = ob_get_clean();
    if ($reportType == 'EMAIL') {
        if ($filesuffix == '' && $subject == '') {
            echo "Please select Report";
            exit;
        }
        $file_name = ($vehicleno != '') ? $vehicleno . "_" . date("d-m-Y") . "_" . $filesuffix : date("d-m-Y") . "_" . $filesuffix;
        $full_path = $serverpath . "/customer/" . $customerno . "/" . $file_name;
        $savefile = 1;
        renderReport($mail_type, $content, $full_path, $savefile);
        $objEmail->subject = $subject;
        $objEmail->full_path = $full_path;
        $objEmail->file_name = $file_name;
        sendReport($objEmail);
    } else {
        $full_path = $filesuffix;
        renderReport($reportType, $content, $full_path, $savefile);
    }
}
if ($_REQUEST['report'] == 'vehicleSummary') {
    ob_start();
    include_once 'vehicle_summary_functions.php';
    $filesuffix = "vehicleSummary";
    if ($_REQUEST['vehicleno'] != '') {
        $subject = "Vehicle Summary Report for " . $_REQUEST['vehicleno'];
    } else {
        $subject = "Vehicle Summary Report";
    }
    $data = getVehicleSummary($_REQUEST['STdate'], $_REQUEST['EDdate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $customerno, $reportType);
    if (isset($data) && !empty($data)) {
        $placehoders['{{TABLE_HEADER}}'] = $data['tableHeader'];
        $placehoders['{{CONDITIONAL_HEADER}}'] = $data['conditionalHeader'];
        $placehoders['{{CONDITIONAL_ABBREVIATIONS}}'] = $data['conditional_abbreviations'];
        $placehoders['{{DATA_ROWS}}'] = $data['tableRows'];
        $html = file_get_contents('pages/panels/vehicleSummaryReportTemplate.php');
        foreach ($placehoders as $key => $val) {
            $html = str_replace($key, $val, $html);
        }
        echo $html;
    }
    $content = ob_get_clean();
    if ($reportType == 'EMAIL') {
        if ($filesuffix == '' && $subject == '') {
            echo "Please select Report";
            exit;
        }
        $file_name = ($vehicleno != '') ? $vehicleno . "_" . date("d-m-Y") . "_" . $filesuffix : date("d-m-Y") . "_" . $filesuffix;
        $full_path = $serverpath . "/customer/" . $customerno . "/" . $file_name;
        $savefile = 1;
        renderReport($mail_type, $content, $full_path, $savefile);
        $objEmail->subject = $subject;
        $objEmail->full_path = $full_path;
        $objEmail->file_name = $file_name;
        sendReport($objEmail);
    } else {
        $full_path = $filesuffix;
        renderReport($reportType, $content, $full_path, $savefile);
    }
}
// </editor-fold>
//

//<editor-fold defaultstate="collapsed" desc="checkpoint Report">
if ($_REQUEST['report'] == 'checkpointReport') {
    ob_start();
    include_once 'reports_chk_functions.php';
    $filesuffix = "checkpointReport";
    if ($_REQUEST['vehicleno'] != '') {
        $subject = "Checkpoint Report for " . $_REQUEST['vehicleno'];
    } else {
        $subject = "Checkpoint Report";
    }
    $checkpoints = getcheckpoints($_REQUEST['vehicleid'], $_REQUEST['reportSpecificCondition']);
    $objReportRequest = new stdClass();
    $objReportRequest->vehicleId = $_REQUEST['vehicleid'];
    $objReportRequest->vehicleNo = $_REQUEST['vehicleno'];
    $objReportRequest->customerNo = $customerno;
    $objReportRequest->startDate = $_REQUEST['STdate'];
    $objReportRequest->endDate = $_REQUEST['EDdate'];
    $objReportRequest->startTime = $_REQUEST['STime'];
    $objReportRequest->endTime = $_REQUEST['ETime'];
    $objReportRequest->startDateTime = $_REQUEST['STdate'] . " " . $_REQUEST['STime'];
    $objReportRequest->endDateTime = $_REQUEST['EDdate'] . " " . $_REQUEST['ETime'];
    $objReportRequest->checkpointDetails = $checkpoints;
    $objReportRequest->chkPtId = $_REQUEST['chkPtId'];
    $objReportRequest->reportSpecificCondition = $_REQUEST['reportSpecificCondition'];
    $objReportRequest->reportType = $reportType;

    $data = getCheckpointReport($objReportRequest);

    if (isset($data) && !empty($data)) {
        $placehoders['{{TABLE_HEADER}}'] = $data['tableHeader'];
        $placehoders['{{DATA_TABLE_HEADER}}'] = $data['dataTableHeader'];
        $placehoders['{{DATA_ROWS}}'] = $data['tableRows'];
        $html = file_get_contents('pages/panels/checkpointReportTemplate.php');
        foreach ($placehoders as $key => $val) {
            $html = str_replace($key, $val, $html);
        }
        echo $html;
    }

    $content = ob_get_clean();
    if ($reportType == 'EMAIL') {
        if ($filesuffix == '' && $subject == '') {
            echo "Please select Report";
            exit;
        }
        $file_name = ($vehicleno != '') ? $vehicleno . "_" . date("d-m-Y") . "_" . $filesuffix : date("d-m-Y") . "_" . $filesuffix;
        $full_path = $serverpath . "/customer/" . $customerno . "/" . $file_name;
        $savefile = 1;
        renderReport($mail_type, $content, $full_path, $savefile);
        $objEmail->subject = $subject;
        $objEmail->full_path = $full_path;
        $objEmail->file_name = $file_name;
        sendReport($objEmail);
    } else {
        $full_path = $filesuffix;
        renderReport($reportType, $content, $full_path, $savefile);
    }
}
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Installation Reports">
if ($_REQUEST['report'] == 'installDeviceReport') {

    ob_start();
    include_once '../user/user_functions.php';

    if ($_REQUEST['reportId'] == 1) {
        $filesuffix = "InstallDeviceReport";
        $subject = "Install Device Report";
    } elseif ($_REQUEST['reportId'] == 2) {
        $filesuffix = "CapexPaymentReport";
        $subject = "Capex payment Report";
    } elseif ($_REQUEST['reportId'] == 3) {
        $filesuffix = "InventoryReport";
        $subject = "Inventory Report";
    } elseif ($_REQUEST['reportId'] == 4) {
        $filesuffix = "InactiveVehicleReport";
        $subject = "  Inactive Vehicle Report";
    } elseif ($_REQUEST['reportId'] == 5) {
        $filesuffix = "DeviceInOutWarrantyReport";
        $subject = "Device In / Out  warranty";
    } elseif ($_REQUEST['reportId'] == 6) {
        $filesuffix = "MonthlyDeviceActivityReport";
        $subject = "Monthly Device Activity Report";
    }

    $userid = $_REQUEST['userid'];

    $user_groups = get_user_groups($customerno, $userid, 'csv');
    $devmanager = new DeviceManager($customerno);
    $devices = $devmanager->get_all_devices($user_groups);

    $objReportRequest = new stdClass();
    $objReportRequest->customerNo = $customerno;
    $objReportRequest->userId = $userid;
    $objReportRequest->reportType = $reportType;
    $objReportRequest->reportId = $_REQUEST['reportId'];
    $objReportRequest->deviceList = $devices;

    $data = getInstallDeviceDetails($objReportRequest);

    if (isset($data) && !empty($data)) {
        $placehoders['{{TABLE_HEADER}}'] = $data['tableHeader'];
        $placehoders['{{DATA_TABLE_HEADER}}'] = $data['dataTableHeader'];
        $placehoders['{{DATA_ROWS}}'] = $data['tableRows'];
        $html = file_get_contents('../emailtemplates/commanReportTemplate.html');
        foreach ($placehoders as $key => $val) {
            $html = str_replace($key, $val, $html);
        }
        echo $html;
    }

    $content = ob_get_clean();
    if ($reportType == 'EMAIL') {
        if ($filesuffix == '' && $subject == '') {
            echo "Please select Report";
            exit;
        }
        $file_name = ($vehicleno != '') ? $vehicleno . "_" . date("d-m-Y") . "_" . $filesuffix : date("d-m-Y") . "_" . $filesuffix;
        $full_path = $serverpath . "/customer/" . $customerno . "/" . $file_name;
        $savefile = 1;
        renderReport($mail_type, $content, $full_path, $savefile);
        $objEmail->subject = $subject;
        $objEmail->full_path = $full_path;
        $objEmail->file_name = $file_name;
        sendReport($objEmail);
    } else {
        $full_path = $filesuffix;
        renderReport($reportType, $content, $full_path, $savefile);
    }

}
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="Description For Code">
if ($_REQUEST['report'] == 'stoppageanalysis') {
    ob_start();
    include_once 'reports_stoppage_functions.php';
    $filesuffix = "stoppageAnalysis";
    if (isset($_REQUEST['vehicleno']) && $_REQUEST['vehicleno'] != '') {
        $subject = "Stoppage Analysis Report for " . $_REQUEST['vehicleno'];
    } else {
        $subject = "Stoppage Analysis Report";
    }

    $data = getstoppageanalysis($_REQUEST['STdate'], $_REQUEST['EDdate'], $_REQUEST['STime'], $_REQUEST['ETime'], $_REQUEST['interval'], $_REQUEST['groupid'], $customerno, $_REQUEST['userid'], $reportType);
    if (isset($data) && !empty($data)) {
        $placehoders['{{TABLE_HEADER}}'] = $data['tableHeader'];
        $placehoders['{{DATA_TABLE_HEADER}}'] = $data['tableColumns'];
        $placehoders['{{DATA_ROWS}}'] = $data['tableRows'];
        $html = file_get_contents('pages/panels/stoppagerepanalysis.php');
        foreach ($placehoders as $key => $val) {
            $html = str_replace($key, $val, $html);
        }
        echo $html;
    }
    //die();
    $content = ob_get_clean();
    if ($reportType == 'EMAIL') {
        if ($filesuffix == '' && $subject == '') {
            echo "Please select Report";
            exit;
        }
        $file_name = ($vehicleno != '') ? $vehicleno . "_" . date("d-m-Y") . "_" . $filesuffix : date("d-m-Y") . "_" . $filesuffix;
        $full_path = $serverpath . "/customer/" . $customerno . "/" . $file_name;
        $savefile = 1;
        renderReport($mail_type, $content, $full_path, $savefile);
        $objEmail->subject = $subject;
        $objEmail->full_path = $full_path;
        $objEmail->file_name = $file_name;
        sendReport($objEmail);
    } else {
        $full_path = $filesuffix;
        renderReport($reportType, $content, $full_path, $savefile);
    }
}
//</editor-fold>

if ($_REQUEST['report'] == 'vehicleInOut') {
    ob_start();
    include_once 'reports_chk_functions.php';
    $filesuffix = "vehicleInOut";
    $arrVehicles = getvehicles();
    $checkpoint = getCheckpointById($_REQUEST['chkptId']);
    $subject = "Vehicle In Out Report for " . $checkpoint->cname;



    $objReport = new stdClass();
    $objReport->checkpointId = $_REQUEST['chkptId'];
    $objReport->checkpointName = $checkpoint->cname;
    $objReport->startDate = date(speedConstants::DATE_Ymd, strtotime($_REQUEST['STdate']));
    $objReport->endDate = date(speedConstants::DATE_Ymd, strtotime($_REQUEST['EDdate']));
    $objReport->startDateTime = date(speedConstants::DEFAULT_TIMESTAMP, strtotime($_REQUEST['STdate'] . " " . $_REQUEST['STime']));
    $objReport->endDateTime = date(speedConstants::DEFAULT_TIMESTAMP, strtotime($_REQUEST['EDdate'] . " " . $_REQUEST['ETime']));
    $objReport->vehicles = $arrVehicles;
    $objReport->customerNo = $customerno;
    $objReport->reportType = $reportType;




    $data = getVehilceInOutReport($objReport);
    //print_r($data);die();
    if (isset($data) && !empty($data)) {
        $placehoders['{{TABLE_HEADER}}'] = $data['tableHeader'];
        $placehoders['{{DATA_TABLE_HEADER}}'] = $data['dataTableHeader'];
        $placehoders['{{DATA_ROWS}}'] = $data['tableRows'];
        $html = file_get_contents('pages/panels/checkpointReportTemplate.php');
        foreach ($placehoders as $key => $val) {
            $html = str_replace($key, $val, $html);
        }
        echo $html;
    }

    $content = ob_get_clean();
    //die();
    if ($reportType == 'EMAIL') {
        if ($filesuffix == '' && $subject == '') {
            echo "Please select Report";
            exit;
        }
        $file_name = ($vehicleno != '') ? $vehicleno . "_" . date("d-m-Y") . "_" . $filesuffix : date("d-m-Y") . "_" . $filesuffix;
        $full_path = $serverpath . "/customer/" . $customerno . "/" . $file_name;
        $savefile = 1;
        renderReport($mail_type, $content, $full_path, $savefile);
        $objEmail->subject = $subject;
        $objEmail->full_path = $full_path;
        $objEmail->file_name = $file_name;
        sendReport($objEmail);
    } else {
        $full_path = $filesuffix;
        renderReport($reportType, $content, $full_path, $savefile);
    }
}

if ($_REQUEST['report'] == 'freezeIgnitionOn') {
    ob_start();
    include_once 'freeze_ignitionOn_function.php';
    $filesuffix = "freezeIgnitionOn";
    if ($_REQUEST['vehicleno'] != '') {
        $subject = "Freeze Ignition On Report for " . $_REQUEST['vehicleno'];
    } else {
        $subject = "Freeze Ignition On Report";
    }
    $data = getVehicleFreezeIgnOn($_REQUEST['STdate'], $_REQUEST['EDdate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $customerno, $reportType);
    if (isset($data) && !empty($data)) {
        $placehoders['{{TABLE_HEADER}}'] = $data['tableHeader'];
        $placehoders['{{CONDITIONAL_HEADER}}'] = $data['conditionalHeader'];
        $placehoders['{{CONDITIONAL_ABBREVIATIONS}}'] = $data['conditional_abbreviations'];
        $placehoders['{{DATA_ROWS}}'] = $data['tableRows'];
        $html = file_get_contents('pages/panels/freezeIgnOnReportTemplate.php');
        foreach ($placehoders as $key => $val) {
            $html = str_replace($key, $val, $html);
        }
        echo $html;
    }
    $content = ob_get_clean();
    if ($reportType == 'EMAIL') {
        if ($filesuffix == '' && $subject == '') {
            echo "Please select Report";
            exit;
        }
        $file_name = ($vehicleno != '') ? $vehicleno . "_" . date("d-m-Y") . "_" . $filesuffix : date("d-m-Y") . "_" . $filesuffix;
        $full_path = $serverpath . "/customer/" . $customerno . "/" . $file_name;
        $savefile = 1;
        renderReport($mail_type, $content, $full_path, $savefile);
        $objEmail->subject = $subject;
        $objEmail->full_path = $full_path;
        $objEmail->file_name = $file_name;
        sendReport($objEmail);
    } else {
        $full_path = $filesuffix;
        renderReport($reportType, $content, $full_path, $savefile);
    }
}


// <editor-fold defaultstate="collapsed" desc="Helper Functions">
function sendReport($objEmail) {
    $isMailSent = sendMailUtil($objEmail->arrToMailIds, $objEmail->strCCMailIds, $objEmail->strBCCMailIds, $objEmail->subject, $objEmail->mail_body, $objEmail->full_path . $objEmail->ext, $objEmail->file_name . $objEmail->ext);
    if ($isMailSent) {
        echo "<span style='color:green;'>Mail sent</span>";
    } else {
        echo "<span style='color:red;'>One or more e-mail sending failed</span>";
    }
}

// </editor-fold>
//
?>
