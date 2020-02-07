<?php

include_once 'reports_fuelefficiency_functions.php';
if (isset($_POST["filter"]) && $_POST["filter"] == '1') {
    $data = getFuelHistoryData($_POST);
    echo json_encode($data[0]);
}
if (isset($_POST["table_header"]) && $_POST["table_header"] == '1') {
    $sdate1 = GetSafeValueString($_POST["STdate"], "string");
    $edate1 = GetSafeValueString($_POST["ETdate"], "string");
    $vehicleno = isset($_POST["vehicleno"]) ? $_POST["vehicleno"] : '';
    $fuels = getFuelHistoryData($_POST);
    $middlecolumn = null;
    if (isset($fuels[1]) && !empty($fuels[1])) {
        $middlecolumn = '<div class="newTableSubHeader" style="width:28%; text-align:left;"> <span style="text-align:center; padding-left:20%;"> <u>Fuel Summary</u> </span><br> 
                    Fuel Consumed(lts) : ' . number_format(round($fuels[1]['totalfuel'],2),2) . '<br/> '
                . ' Total Amount(Rs.) : ' . number_format($fuels[1]['totalamount'],2) . '<br/>'
                . ' Total Net Kms : ' . number_format($fuels[1]['totalnet'],2) . '<br/>'
                . 'Consolidated Average : ' . $fuels[1]['consolavg'] . '<br/>'
                . '  </div>';
    }
    $title = 'Fuel History Report';
    $subTitle = array(
        "Vehicle No: $vehicleno",
        "Start Date: $sdate1",
        "End Date: $edate1"
    );
    $columns = array();
    echo table_header($title, $subTitle, null, false, $middlecolumn);
}

function getFuelHistoryData($form) {
    $vehicleid = (int) GetSafeValueString($form["vehicleid"], "string");
    $dealerid = GetSafeValueString($form["dealerid"], "string");
    $sdate1 = GetSafeValueString($form["STdate"], "string");
    $edate1 = GetSafeValueString($form["ETdate"], "string");
    $sdate = strtotime($sdate1 . ' 00:00');
    $edate = strtotime($edate1 . ' 23:59');
    $vehicleno = isset($form["vehicleno"]) ? $form["vehicleno"] : '';
    if ($vehicleno == '') {
        $fuels = getfilteredfuelmaintenance($vehicleno = null, $dealerid, $sdate, $edate,$sort_arr=NULL);
    } else {
        $fuels = getfilteredfuelmaintenance($vehicleno, $dealerid, $sdate, $edate,$sort_arr=NULL);
    }
    return $fuels;
}
