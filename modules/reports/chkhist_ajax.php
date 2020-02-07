<?php
include 'reports_chk_functions.php';
if (isset($_POST['STdate']) && isset($_POST['EDdate']) && !isset($_POST['report'])) {
    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    $error8 = "<script>jQuery('#error8').show();jQuery('#error8').fadeOut(3000)</script>";
    $error9 = "<script>jQuery('#error9').show();jQuery('#error9').fadeOut(3000)</script>";
    $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
    $error3 = "<script>jQuery('#error3').show();jQuery('#error3').fadeOut(3000)</script>";
    $vehicleid = GetSafeValueString($_POST['vehicleid'], 'string');
    $vehicleno = GetSafeValueString($_POST['vehicleno'], 'string');
    $STdate = GetSafeValueString($_POST['STdate'], 'string');
    $EDdate = GetSafeValueString($_POST['EDdate'], 'string');
    $reportType = GetSafeValueString($_POST['routetype'], 'string');
    $chkPtId = 0;
    $chkTypeId = 0;
    /*Date And Date Diff Check*/
    $datecheck = datediff($STdate, $EDdate);
    $datediffcheck = date_SDiff($STdate, $EDdate);
    if ($reportType == 2) {
        $chkPtId = GetSafeValueString($_POST['chkId'], 'string');
    } elseif ($reportType == 3) {
        $chkTypeId = GetSafeValueString($_POST['chktype'], 'string');
    }
    //echo $datecheck;
    if ($datecheck == 0) {
        echo $error2;
    } elseif ($datecheck == 1 && $datediffcheck >= 30) {
        echo $error3;
    } elseif (isset($_POST['vehicleid']) && $_POST['vehicleid'] == -1) {
        echo $error1;
    } elseif ($reportType == 2 && $chkPtId == 0) {
        echo $error8;
    } elseif ($reportType == 3 && $chkTypeId == 0) {
        echo $error9;
    } elseif (isset($_SESSION['ecodeid'])) {
        /*  Client Code Validation  */
        $validation = clientCodeValidation($_POST['s_start'], $_POST['e_end'], $_POST['days'], $STdate, $EDdate);
        if (isset($validation) && !empty($validation)) {
            if ($validation['isError'] == 1) {
                echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
                die();
            } else {
                $STdate = date('d-m-Y', strtotime($validation['reportStartDate']));
                $EDdate = date('d-m-Y', strtotime($validation['reportEndDate']));
                echo "<script>jQuery('#SDate').val('" . $STdate . "');</script>";
                echo "<script>jQuery('#EDate').val('" . $EDdate . "');</script>";
            }
        }
        $checkpoints = getcheckpoints($vehicleid, $reportType, $chkPtId, $chkTypeId);
        $objReportRequest = new stdClass();
        $objReportRequest->vehicleId = $vehicleid;
        $objReportRequest->vehicleNo = $vehicleno;
        $objReportRequest->customerNo = $_SESSION['customerno'];
        $objReportRequest->startDate = $STdate;
        $objReportRequest->endDate = $EDdate;
        $objReportRequest->startTime = $_POST['STime'];
        $objReportRequest->endTime = $_POST['ETime'];
        $objReportRequest->startDateTime = $STdate . " " . $_POST['STime'];
        $objReportRequest->endDateTime = $EDdate . " " . $_POST['ETime'];
        $objReportRequest->chktypename = isset($checkpoints->name) ? $checkpoints->name : 'NA';
        $objReportRequest->checkpointDetails = $checkpoints;
        $objReportRequest->reportSpecificCondition = $reportType;
        $objReportRequest->reportType = speedConstants::REPORT_HTML;
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
    } else {
        if ($vehicleid != 0) {
            $checkpoints = getcheckpoints($vehicleid, $reportType, $chkPtId, $chkTypeId);
            $objReportRequest = new stdClass();
            $objReportRequest->vehicleId = $vehicleid;
            $objReportRequest->vehicleNo = $vehicleno;
            $objReportRequest->customerNo = $_SESSION['customerno'];
            $objReportRequest->startDate = $STdate;
            $objReportRequest->endDate = $EDdate;
            $objReportRequest->startTime = $_POST['STime'];
            $objReportRequest->endTime = $_POST['ETime'];
            $objReportRequest->startDateTime = $STdate . " " . $_POST['STime'];
            $objReportRequest->endDateTime = $EDdate . " " . $_POST['ETime'];
            $objReportRequest->chktypename = isset($checkpoints->name) ? $checkpoints->name : 'NA';
            $objReportRequest->checkpointDetails = $checkpoints;
            $objReportRequest->chkPtId = $chkPtId;
            $objReportRequest->reportSpecificCondition = $reportType;
            $objReportRequest->reportType = speedConstants::REPORT_HTML;
            $data = getCheckpointReport($objReportRequest);
            if (isset($data) && !empty($data)) {
                $placehoders['{{TABLE_HEADER}}'] = $data['tableHeader'];
                $placehoders['{{DATA_TABLE_HEADER}}'] = $data['dataTableHeader'];
                $placehoders['{{DATA_ROWS}}'] = $data['tableRows'];
                $placehoders['{{DATA_ROWS}}'] = $data['tableRows'];
                $html = file_get_contents('pages/panels/checkpointReportTemplate.php');
                foreach ($placehoders as $key => $val) {
                    $html = str_replace($key, $val, $html);
                }
                echo $html;
            }
        } else {
            echo $error3;
        }
    }
} elseif (isset($_POST['STdate']) && isset($_POST['EDdate']) && isset($_POST['report'])) {
    //echo "dsgdfg";
    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
    $error3 = "<script>jQuery('#error3').show();jQuery('#error3').fadeOut(3000)</script>";
    $error4 = "<script>jQuery('#error4').show();jQuery('#error4').fadeOut(3000)</script>";
    $errorr5 = "<script>jQuery('#error5').show();jQuery('#error5').fadeOut(3000)</script>";
    $chkptId = GetSafeValueString($_POST['chkptId'], 'string');
    $STdate = GetSafeValueString($_POST['STdate'], 'string');
    $EDdate = GetSafeValueString($_POST['EDdate'], 'string');
    $STime = GetSafeValueString($_POST['STime'], 'string');
    $ETime = GetSafeValueString($_POST['ETime'], 'string');
    $reportType = GetSafeValueString($_POST['report'], 'string');
    $arrVehicles = getvehicles();
    $checkpoint = getCheckpointById($chkptId);
    $objReport = new stdClass();
    $objReport->checkpointId = $chkptId;
    $objReport->checkpointName = $checkpoint->cname;
    $objReport->startDate = date(speedConstants::DATE_Ymd, strtotime($STdate));
    $objReport->endDate = date(speedConstants::DATE_Ymd, strtotime($EDdate));
    $objReport->startDateTime = date(speedConstants::DEFAULT_TIMESTAMP, strtotime($STdate . " " . $STime));
    $objReport->endDateTime = date(speedConstants::DEFAULT_TIMESTAMP, strtotime($EDdate . " " . $ETime));
    $objReport->vehicles = $arrVehicles;

    $objReport->customerNo = $_SESSION['customerno'];

    $objReport->reportType = speedConstants::REPORT_HTML;

    /*Date And Date Diff Check*/
    $datecheck = datediff($STdate, $EDdate);
    $datediffcheck = date_SDiff($STdate, $EDdate);

    if ($datecheck == 0) {
        echo $error2;
    } elseif ($datecheck == 1 && $datediffcheck >= 30) {
        echo $error3;
    } elseif (isset($_POST['vehicleid']) && $_POST['vehicleid'] == -1) {
        echo $error1;
    } else{
        $data = getVehilceInOutReport($objReport);
        if (isset($data) && !empty($data)) {
            $placehoders['{{TABLE_HEADER}}'] = $data['tableHeader'];
            $placehoders['{{DATA_TABLE_HEADER}}'] = $data['dataTableHeader'];
            $placehoders['{{DATA_ROWS}}'] = $data['tableRows'];
            $placehoders['{{DATA_ROWS}}'] = $data['tableRows'];
            $html = file_get_contents('pages/panels/checkpointReportTemplate.php');
            foreach ($placehoders as $key => $val) {
                $html = str_replace($key, $val, $html);
            }
            echo $html;
        }else{
         echo $error;
        }
    }


    //print_r($objReport);die();
}
?>
