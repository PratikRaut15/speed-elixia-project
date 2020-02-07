<?php
/*
Name            -   cronoBackdatedRealtimeData.php
Description     -    push all offline checkpoint into chkreport.sqlite
Parameters    -    customerno, sdate, edate, stime, etime
Module        -   VTS
Sub-Modules     -    Checkpoint Details IN Route Summary
Created by    -    Shrikant Suryawanshi
Created    on
Change details
1)Updated by    -
Updated    on    -
Reason        -
2)
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
set_time_limit(0);
session_start();
require_once "../../lib/system/utilities.php";
require_once "../../lib/autoload.php";
require_once "../../lib/comman_function/reports_func.php";
define("DATEFORMAT_YMD", "Y-m-d");
define("DATEFORMAT_his", "h:i:s");
if (isset($_REQUEST) && $_REQUEST['customerNo'] != '') {
    $thiscustomerno = $_REQUEST['customerNo'];
    $startDate = $_REQUEST['startDate'];
    $endDate = $_REQUEST['endDate'];
    $unitNo = $_REQUEST['unitNo'];
    $Shour = '00:00:00';
    $Ehour = '23:59:59';
    $objUnitManager = new UnitManager($thiscustomerno);
    $unitDetails = $objUnitManager->getUnitDetailByUnitNo($unitNo);
    if (isset($unitDetails) && !empty($unitDetails)) {
        //print_r($unitDetails);
        $totalDays = gendays_cmn($startDate, $endDate);
        $data = array();
        if (isset($totalDays) && !empty($totalDays)) {
            foreach ($totalDays as $userdate) {
                $singledaydata = Array();
                $singledaydata = new_travel_data($thiscustomerno, $unitNo, $userdate, $unitDetails);
                if (isset($data) && isset($singledaydata)) {
                    $data = array_merge($data, $singledaydata);
                }
            }
        }

        if(isset($data) && !empty($data)){
            foreach($data as $row){
                $objUnitManager->insertUnitBackdatedRealtimeDate($row);
            }
        }
    }
}
function new_travel_data($customerno, $unitno, $userdate, $unitDetails) {
    $devicedata = null;
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
    if (!file_exists($location)) {
        echo "File Not Exists For ".$userdate." <br/>";
        return null;
    }
    if (filesize($location) == 0) {
        return null;
    }
    if (file_exists($location)) {
        $location = $location;
        $objReport = new stdClass();
        $objReport->location = $location;
        $objReport->deviceId =$unitDetails->deviceid;
        $objReport->reportDate = $userdate;
        $objReport->startTime = "00:00:00";
        $objReport->endTime = "23:59:59";
        $objReport->interval = 15;
        $devicedata = getStoppageReport($objReport,$unitDetails);
        echo "File Exists For ".$userdate." <br/>";
        //prettyPrint($devicedata);
    }
    return $devicedata;
}
function getStoppageReport($objReport,$unitDetails) {
    $location = "sqlite:" . $objReport->location;
    $devices = array();
    $query = "SELECT devicehistory.lastupdated, vehiclehistory.odometer, vehiclehistory.vehicleno, devicehistory.devicelat, vehiclehistory.vehicleid, devicehistory.devicelong,unithistory.analog1,unithistory.analog2
            FROM devicehistory
            INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
            INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
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
                $device = new stdClass();
                $device->vehicleNo = $unitDetails->vehicleno;
                $device->unitNo = $unitDetails->unitno;
                $device->vehicleId = $unitDetails->vehicleid;
                $device->unitId = $unitDetails->uid;
                $device->deviceId = $unitDetails->deviceid;
                $device->groupId = $unitDetails->groupid;
                $device->groupName = $unitDetails->groupname;
                $device->kind = $unitDetails->kind;
                $device->customerNo = $unitDetails->customerno;
                $device->timestamp = date('Y-m-d H:i:s');
                $device->temp1 = '';
                $device->temp2 = '';
                $objTemp = new TempConversion();
                $objTemp->unit_type = $unitDetails->get_conversion;
                $objTemp->use_humidity = 0;//$unitDetails->use_humidity;
                $objTemp->switch_to = 0;
                if($lastupdated == ""){
                    $device->lat = $row['devicelat'];
                    $device->long = $row['devicelong'];
                    $device->lastupdated = $row['lastupdated'];
                    $analog1 = 'analog'.$unitDetails->tempsen1;
                    $objTemp->rawtemp = $row[$analog1];
                    $device->temp1 = getTempUtil($objTemp);
                    $analog2 = 'analog'.$unitDetails->tempsen2;
                    $objTemp->rawtemp = $row[$analog2];
                    $device->temp2 = getTempUtil($objTemp);
                    $lastupdated = $row['lastupdated'];
                    $devices[] = $device;
                }elseif (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) > $objReport->interval) {
                    $device->lat = $row['devicelat'];
                    $device->long = $row['devicelong'];
                    $device->lastupdated = $row['lastupdated'];
                    $analog1 = 'analog'.$unitDetails->tempsen1;
                    $objTemp->rawtemp = $row[$analog1];
                    $device->temp1 = getTempUtil($objTemp);
                    $analog2 = 'analog'.$unitDetails->tempsen2;
                    $objTemp->rawtemp = $row[$analog2];
                    $device->temp2 = getTempUtil($objTemp);
                    $lastupdated = $row['lastupdated'];
                    $devices[] = $device;
                }
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    return $devices;
}
?>
