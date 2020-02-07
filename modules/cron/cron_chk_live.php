<?php
/*
Name            -   cron_chk_live.php
Description     -   push all offline checkpoint into chkreport.sqlite
Parameters      -   customerno, sdate, edate, stime, etime
Module          -   VTS
Sub-Modules     -   Checkpoint Details IN Route Summary
Created by      -   Shrikant Suryawanshi
Created    on   -   16 Feb, 2016
Change details
1)Updated by    -
Updated    on   -
Reason          -
2)
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
set_time_limit(0);
session_start();
require_once "../../lib/system/utilities.php";
require_once "../../lib/comman_function/reports_func.php";
require_once '../../lib/bo/CronManager.php';
require_once '../../lib/bo/DeviceManager.php';
require_once '../../lib/bo/UnitManager.php';
require_once 'files/calculatedist.php';
require_once 'files/push_sqlite.php';
define("DATEFORMAT_YMD", "Y-m-d");
define("DATEFORMAT_his", "h:i:s");
if (isset($_REQUEST) && $_REQUEST['customerno'] != '') {
    $thiscustomerno = $_REQUEST['customerno'];
    $sdate = $_REQUEST['sdate'];
    $edate = $_REQUEST['edate'];
    $stime = $_REQUEST['stime'];
    $etime = $_REQUEST['etime'];
    //$correctionstarttime = $_REQUEST['cstarttime'];
    //$correctionendtime = $_REQUEST['cendtime'];
    //$iscorrectionrequired = $_REQUEST['iscorreq'];
    $correctionstarttime = $sdate . " " . $stime;
    $correctionendtime = $edate . " " . $etime;
    $iscorrectionrequired = 1;
    //$vehicleno = $_REQUEST['vehicle'];
    $vehicleid = $_REQUEST['vehicleid'];
    $deviceid = 0;
    $unitno = 0;
    $locationchk = null;
    $is_chkid = array(1353, 1359, 1351, 1311, 1361);
    $devicemanager = new DeviceManager($thiscustomerno);
    $devices = $devicemanager->devicesformapping();
    if ($devices) {
        foreach ($devices as $row) {
            if ($row->vehicleid == $vehicleid) {
                $vehicleid = $row->vehicleid;
                $deviceid = $row->deviceid;
                echo "Vehicleid " . $vehicleid . "<br/>";
                echo "Deviceid " . $deviceid . "<br/>";
                if ($deviceid != 0) {
                    $um = new UnitManager($thiscustomerno);
                    $unitdata = $um->getunitdetailsfromdeviceid($deviceid);
                    if (isset($unitdata) && !empty($unitdata)) {
                        $unitno = $unitdata->unitno;
                        echo "Unitno " . $unitno . "<br/>";
                    }
                    $sdate = new DateTime($correctionstarttime);
                    $edate = new DateTime($correctionendtime);
                    $startdate = $sdate->format(DATEFORMAT_YMD);
                    $enddate = $edate->format(DATEFORMAT_YMD);
                    $totaldays = gendays_cmn($startdate, $enddate);
                    $cm = new CronManager();
                    $i = 1;
                    if (isset($vehicleid) && $vehicleid != 0 && $unitno != 0) {
                        $data = Array();
                        foreach ($totaldays as $userdate) {
                            $singledaydata = Array();
                            if ($userdate == $startdate) {
                                $Shour = $stime;
                            } else {
                                $Shour = '00:00:00';
                            }
                            if ($userdate == $enddate) {
                                $Ehour = $etime;
                            } else {
                                $Ehour = '23:59:59';
                            }
                            $singledaydata = new_travel_data($thiscustomerno, $unitno, $userdate, $vehicleid, $Shour, $Ehour);
                            if (isset($data) && isset($singledaydata)) {
                                $data = array_merge($data, $singledaydata);
                            }
                        }
                        /* Code For CheckPoint Calculation */
                        $location = "../../customer/$thiscustomerno/reports/chkreport.sqlite";
                        if (file_exists($location)) {
                            $locationchk = "sqlite:" . $location;
                        }
                        //die();
                        if (file_exists($location)) {
                            if (isset($iscorrectionrequired) && $iscorrectionrequired == 1) {
                                $chkDelete = DeleteChkCorrectionData($locationchk, $vehicleid, $correctionstarttime, $correctionendtime);
                            }
                            //print_r($data);
                            //die();
                            if (isset($data) && !empty($data)) {
                                foreach ($data as $thisdata) {
                                    $chkdata = PullChkData($locationchk, $thisdata->vehicleid, $thisdata->lastupdated);
                                    if (isset($chkdata) && $chkdata->status == '0') {
                                        $chk_mysql_data = $cm->pull_chk_mysql_data($chkdata->chkid, $thisdata->customerno);

                                        $distance = calculate($thisdata->latitude, $thisdata->longitude, $chk_mysql_data->cgeolat, $chk_mysql_data->cgeolong);
                                        $crad = (float) $chk_mysql_data->crad;
                                        if ($distance >= $crad) {
                                            ChkSqlite($thisdata->customerno, $chkdata->chkid, 1, $thisdata->lastupdated, $thisdata->vehicleid, $chkdata->chktype);
                                        }
                                    }
                                    if (isset($chkdata) && $chkdata->status == '1') {
                                        $chkpts = $cm->get_all_checkpoints($thisdata->customerno);
                                        if (isset($chkpts)) {
                                            foreach ($chkpts as $thischkpt) {
                                                $cgeolat = $thischkpt->cgeolat;
                                                $cgeolong = $thischkpt->cgeolong;
                                                $crad = (float) $thischkpt->crad;

                                                $distance = calculate($thisdata->latitude, $thisdata->longitude, $cgeolat, $cgeolong);
                                                if ($distance < $crad) {
                                                    ChkSqlite($thisdata->customerno, $thischkpt->chkid, 0, $thisdata->lastupdated, $thisdata->vehicleid, $thischkpt->chktype);
                                                }
                                            }
                                        }
                                    }
                                    $first_row = true;
                                    $chk_clean = PullComplete($locationchk, $thisdata->vehicleid, $thisdata->lastupdated);
                                    if (isset($chk_clean)) {
                                        foreach ($chk_clean as $this_chk) {
                                            if ($first_row == true) {
                                                $first_row = false;
                                                $first_data = new stdClass();
                                                $first_data->chkid = $this_chk->chkid;
                                                $first_data->status = $this_chk->status;
                                            } else {
                                                if ($first_data->chkid == $this_chk->chkid && $first_data->status == $this_chk->status) {
                                                    DeleteChkData($locationchk, $thisdata->vehicleid, $this_chk->chkrepid);
                                                }
                                                $first_data->chkid = $this_chk->chkid;
                                                $first_data->status = $this_chk->status;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                break;
            }
        }
    }
}

function new_travel_data($customerno, $unitno, $userdate, $vehicleid, $Shour, $Ehour) {
    $devicedata = null;
    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
    if (!file_exists($location)) {
        return null;
    }
    if (filesize($location) == 0) {
        return null;
    }
    if (file_exists($location)) {
        $location = "sqlite:" . $location;
        $devicedata = getdatafromsqliteTimebased($vehicleid, $location, $userdate, $Shour, $Ehour, $customerno);
    }
    return $devicedata;
}

function getdatafromsqliteTimebased($vehicleid, $location, $userdate, $Shour, $Ehour, $customerno) {
    $sqlitedata = array();
    try {
        $database = new PDO($location);
        $query = "SELECT devicehistory.deviceid, devicehistory.devicelat,
      devicehistory.devicelong, devicehistory.ignition, devicehistory.status,
      devicehistory.lastupdated, vehiclehistory.odometer from devicehistory
      INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
      WHERE vehiclehistory.vehicleid=$vehicleid AND devicehistory.lastupdated >= '$userdate $Shour' AND devicehistory.lastupdated <= '$userdate $Ehour'
      ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $lastdevice = new stdClass();
                $lastdevice->customerno = $customerno;
                $lastdevice->lastupdated = $row['lastupdated'];
                $lastdevice->vehicleid = $vehicleid;
                $lastdevice->latitude = $row['devicelat'];
                $lastdevice->longitude = $row['devicelong'];
                $sqlitedata[] = $lastdevice;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    return $sqlitedata;
}

function PullComplete($location, $vehicleid, $lastupdated) {
    $datas = Array();
    $query = "SELECT status,chkid,date,chkrepid
      from V$vehicleid WHERE DATE(`date`) = DATE('$lastupdated')
      ORDER BY date ASC";
    try {
        $database = new PDO($location);
        $result = $database->query($query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $chkdata = new stdClass();
                $chkdata->chkid = $row['chkid'];
                $chkdata->date = $row['date'];
                $chkdata->status = $row['status'];
                $chkdata->chkrepid = $row['chkrepid'];
                $datas[] = $chkdata;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    return $datas;
}

function DeleteChkData($location, $vehicleid, $chkrepid) {
    $db = new PDO($location);
    $Query = "DELETE FROM V$vehicleid WHERE chkrepid = $chkrepid";
    $db->exec('BEGIN IMMEDIATE TRANSACTION');
    $db->exec($Query);
    $db->exec('COMMIT TRANSACTION');
}

function PullChkData($location, $vehicleid, $lastupdated) {
    $chkdatalist = null;
    $table = 'V' . $vehicleid;
    if (IsColumnExistInSqlite($location, $table, 'chktype')) {
        $query = "  SELECT  status
                            ,chkid
                            ,chktype
                            ,date
                    from    $table
                    WHERE   date <= '$lastupdated'
                    ORDER BY date DESC
                    LIMIT   1";
    } else {
        $query = "  SELECT  status
                            ,chkid
                            ,date
                    from    $table
                    WHERE   date <= '$lastupdated'
                    ORDER BY date DESC
                    LIMIT   1";
    }
    try {
        $database = new PDO($location);
        $result = $database->query($query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $chkdata = new stdClass();
                $chkdata->chkid = $row['chkid'];
                $chkdata->chktype = isset($row['chktype']) ? $row['chktype'] : 0;
                $chkdata->date = $row['date'];
                $chkdata->status = $row['status'];
                $chkdatalist = $chkdata;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    return $chkdatalist;
}

function DeleteChkCorrectionData($location, $vehicleid, $starttime, $endate) {
    echo $query = "Delete from V$vehicleid
      WHERE date between '$starttime' AND '$endate'";
    echo "<br/>";
    try {
        $database = new PDO($location);
        $result = $database->query($query);
    } catch (PDOException $e) {
        die($e);
    }
    return true;
}

?>
