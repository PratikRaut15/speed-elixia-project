<?php

//Error- Reporting

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../";
require $RELATIVE_PATH_DOTS."lib/system/utilities.php";
require $RELATIVE_PATH_DOTS."lib/comman_function/reports_func.php";
require $RELATIVE_PATH_DOTS.'lib/autoload.php';
$customerno = 2;
$userid = 4;
$routeFenceId = 1;
$startDate = '2017-03-10';
$startTime = '00:00:00';
$endDate = '2017-03-14';
$endTime = '22:00:00';
$unitno = 900271;
$deviceid = 1921;
$totaldays = gendays_cmn($startDate, $endDate);
$days = array();
if (isset($totaldays)) {
    foreach ($totaldays as $userdate) {
        //Date Check Operations
        $data = null;
        $STdate = date("Y-m-d", strtotime($startDate));
        $f_STdate = $userdate . " 00:00:00";
        if ($userdate == $STdate) {
            $f_STdate = $userdate . " " . $startTime;
        }
        $EDdate = date("Y-m-d", strtotime($endDate));
        $f_EDdate = $userdate . " 23:59:59";
        if ($userdate == $EDdate) {
            $f_EDdate = $userdate . " " . $endTime;
        }
        $location = "../../customer/" . $customerno . "/unitno/" . $unitno . "/sqlite/" . $userdate . ".sqlite";
        if (file_exists($location)) {
            $location = "sqlite:" . $location;
            $data = gettempdata_fromsqlite($customerno, $location, $deviceid, $f_STdate, $f_EDdate);
        }
        if ($data != null && count($data) > 1) {
            $days = array_merge($days, $data);
        }
    }
    $arrData = json_decode(json_encode($days),true);
    $arrUniqueData = array_unique($arrData, SORT_REGULAR);
    //prettyPrint($arrUniqueData);
    if(isset($arrUniqueData) && !empty($arrUniqueData)) {
        foreach ($arrUniqueData as $datarow) {
            $objData = new stdClass();
            $objData->customerNo = $customerno;
            $objData->routeFenceId = $routeFenceId;
            $objData->lat = $datarow['devicelat'];
            $objData->lng = $datarow['devicelong'];
            $objData->userId = $userid;
            $objGeoFenceManager = new GeofenceManager($customerno);
            $objGeoFenceManager->insertRouteFence($objData);
        }
    }
}
function gettempdata_fromsqlite($customerno, $location, $deviceid, $startdate, $enddate) {
    $devices = array();
    $last_ignition = null;
    $locationNotFound = " AND devicelat <> '0.000000' AND devicelong <> '0.000000'";
    $query = "SELECT devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong
            , unithistory.unitno
            From devicehistory
            INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.lastupdated BETWEEN '$startdate' AND '$enddate' AND devicehistory.deviceid= $deviceid AND devicehistory.status!='F'  " . $locationNotFound . " group by devicehistory.lastupdated";
    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);
        if (isset($result) && $result != "") {
            $total = 0;
            foreach ($result as $row) {
                $device = new stdClass();
                $device->devicelat = $row['devicelat'];
                $device->devicelong = $row['devicelong'];
                $devices[] = $device;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    return $devices;
}

