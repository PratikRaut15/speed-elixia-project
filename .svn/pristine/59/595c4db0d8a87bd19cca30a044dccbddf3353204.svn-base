<?php

/*
  Name            -    cronOdometerCorrection.php
  Description     -    Pull odometer reset units from dailyreport table and correct the odometer of unit for perticular date
  Parameters      -    customerno, unit, date
  Module          -    VTS

  Created By      -    Shrikant Suryawanshi
  Created On      -    12 April, 2017
  URL             -    http://speed.elixiatech.com/modules/cron/cronOdometerCorrection.php?customerno=328&unit=907953&date=2017-04-08
  Change details
  1)
  Updated By    -
  Updated On    -
  Reason        -
  2)
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
set_time_limit(0);
define('DS', "/");
$RELATIVE_PATH_DOTS = "../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
require_once 'files/push_sqlite.php';
//define('DS', "/");

$serverPath = $_SERVER['SERVER_NAME'];
//$serverPath = $serverPath . "/customer/";

//Uncomment for LOCAL
if (isset($IsDebugServer) && $IsDebugServer == TRUE) {
    //echo 'IsDebugServer=>' . $IsDebugServer . '<br><br><br>';
    $serverPath = $serverPath . "/speed/customer/";
}

//$serverPath = "http://speed.elixiatech.com/customer/";
$serverReadPath = "../../customer/";
$localSaveToPath = "../../../ProductionBackup/odm-reset/customer/";
if (isset($_REQUEST) && $_REQUEST['customerno']) {
    $customerNo = $_REQUEST['customerno'];
    $unitNo = $_REQUEST['unit'];
    $sqliteDate = $_REQUEST['date'];
    $sqliteDate = date(speedConstants::DATE_Ymd, strtotime($sqliteDate));
    $file = $customerNo . DS . 'unitno' . DS . $unitNo . DS . 'sqlite' . DS . $sqliteDate . '.sqlite';
    $filePath = $serverPath . $file;
    $fileName = $sqliteDate . '.sqlite';
    $sqlitePath = download($customerNo, $unitNo, $sqliteDate, $serverPath, $serverReadPath);
    if (file_exists($sqlitePath)) {
        echo "Sqlite File Downloaded<br/>";
        $path = "sqlite:" . $sqlitePath;
        $db = new PDO($path);
        $Query = "SELECT vehiclehistoryid, odometer, uid, vehicleid FROM vehiclehistory order by lastupdated ASC";
        $result = $db->query($Query)->fetchAll(PDO::FETCH_ASSOC);
        //prettyPrint($result);
        if (isset($result) && !empty($result)) {
            $lastOdometer = 0;
            $lastResetOdometer = 0;
            $arrReset = array();
            $arrCorrect = array();
            foreach ($result as $row) {
                if ($lastOdometer > $row['odometer']) {
                    /* Check For Same Odometer */
                    if ($lastResetOdometer == $row['odometer']) {
                        $lastOdometer = $lastOdometer;
                    } elseif ($lastResetOdometer < $row['odometer']) {
                        $odometerDiff = ($row['odometer'] - $lastResetOdometer);
                        $lastOdometer = ($odometerDiff + $lastOdometer);
                    } else {
                        $lastOdometer = $lastOdometer;
                    }
                    $lastResetOdometer = $row['odometer'];
                    //echo "--" . $lastOdometer . "----" . $row['odometer'] . "<br/>";
                    $row['odometer'] = $lastOdometer;
                    $arrReset[] = $row;
                } else {
                    $arrCorrect[] = $row;
                    $lastOdometer = $row['odometer'];
                    $lastResetOdometer = $row['odometer'];
                }
            }
            if (isset($arrReset) && !empty($arrReset)) {
                echo "Data Correction Started<br/>";
                //prettyPrint($arrReset);die();
                $db = new PDO($path);
                $isDataCorrected = 0;
                foreach ($arrReset as $data) {
                    updateOdometer($db, $data['vehiclehistoryid'], $data['odometer']);
                    $isDataCorrected = 1;
                }
                if ($isDataCorrected) {
                    echo "Unit Sqlite Data Is Corrected<br/>";
                    $arrDailyReportData = getFirstLastOdometers($db);
                    $isDailyReportUpdaeted = 0;
                    if (isset($arrDailyReportData) && !empty($arrDailyReportData)) {
                        $dailyReportPath = "sqlite:" . $serverReadPath . DS . $customerNo . DS . "reports" . DS . "dailyreport.sqlite";
                        $dbDaily = new PDO($dailyReportPath);
                        $isDailyReportUpdaeted = updateDailyReport($dbDaily, $arrDailyReportData, $sqliteDate);
                        if ($isDailyReportUpdaeted) {
                            echo "DailyReport Sqlite Data Updated<br/>";
                        }
                    }
                }
                echo "Data Correction End<br/>";
            }
        }
    }
}

function updateOdometer($db, $vehiclehistoryid, $odometer) {
    $Query = "UPDATE vehiclehistory SET odometer = " . $odometer . " WHERE vehiclehistoryid = " . $vehiclehistoryid;
    //echo "<br/>";
    $db->exec('BEGIN IMMEDIATE TRANSACTION');
    $db->exec($Query);
    $db->exec('COMMIT TRANSACTION');
}

function getFirstLastOdometers($db) {
    $arrResult = null;
    $QueryFirstOdometer = "SELECT vehicleid,uid,odometer FROM vehiclehistory ORDER BY lastupdated ASC LIMIT 1";
    $resultFirstOdometer = $db->query($QueryFirstOdometer)->fetchAll(PDO::FETCH_ASSOC);
    $arrResult['vehicleid'] = $resultFirstOdometer[0]['vehicleid'];
    $arrResult['uid'] = $resultFirstOdometer[0]['uid'];
    $arrResult['firstOdometer'] = $resultFirstOdometer[0]['odometer'];
    $QueryLastOdometer = "SELECT odometer FROM vehiclehistory ORDER BY lastupdated DESC LIMIT 1";
    $resultLastOdometer = $db->query($QueryLastOdometer)->fetchAll(PDO::FETCH_ASSOC);
    $arrResult['lastOdometer'] = $resultLastOdometer[0]['odometer'];
    $arrResult['totalDistance'] = ($arrResult['lastOdometer'] - $arrResult['firstOdometer']);
    return $arrResult;
}

function updateDailyReport($db, $arrDailyReportData, $sqliteDate) {
    $isUpdated = 0;
    $tableName = "A" . date('dmy', strtotime($sqliteDate));

    $checkQuery = "SELECT uid FROM " . $tableName . " WHERE uid = " . $arrDailyReportData['uid'] . " and vehicleid=" . $arrDailyReportData['vehicleid'];
    $checkResult = $db->query($checkQuery)->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($checkResult)) {
        $Query = "UPDATE " . $tableName . " SET totaldistance=" . $arrDailyReportData['totalDistance'] . ",first_odometer=" . $arrDailyReportData['firstOdometer'] . ",last_odometer=" . $arrDailyReportData['lastOdometer'] . " WHERE uid=" . $arrDailyReportData['uid'] . " and vehicleid=" . $arrDailyReportData['vehicleid'];
    } else {
        $Query = "  INSERT INTO " . $tableName . "(uid
                        ,vehicleid
                        ,totaldistance
                        ,first_odometer
                        ,last_odometer)
                    VALUES('" . $arrDailyReportData['uid'] . "'
                        ,'" . $arrDailyReportData['vehicleid'] . "'
                        ,'" . $arrDailyReportData['totalDistance'] . "'
                        ,'" . $arrDailyReportData['firstOdometer'] . "'
                        ,'" . $arrDailyReportData['lastOdometer'] . "');";
    }
    $db->exec('BEGIN IMMEDIATE TRANSACTION');
    $db->exec($Query);
    $isUpdated = 1;
    $db->exec('COMMIT TRANSACTION');
    return $isUpdated;
}

function download($customerNo, $unitNo, $sqliteDate, $serverPath, $serverReadPath) {
    $returnPath = 0;
    $file = $customerNo . DS . 'unitno' . DS . $unitNo . DS . 'sqlite' . DS . $sqliteDate . '.sqlite';
    $filePath = $serverPath . $file;
    $fileReadPath = $serverReadPath . $file;
    $fileReadPathBck = $serverPath . DS . 'odmreset' . DS . 'unitno' . DS . $unitNo . DS . 'sqlite' . DS . $sqliteDate . '.sqlite';
    $fileName = $sqliteDate . '.sqlite';

    /* Prodauction BackUp */
    $localCustomerDirBck = $serverReadPath . $customerNo . DS . 'odmreset' . DS;
    $localUnitnoDirBck = $localCustomerDirBck . 'unitno' . DS;
    $localUnitDirBck = $localUnitnoDirBck . $unitNo . DS;
    $localSqliteDirBck = $localUnitDirBck . DS . 'sqlite' . DS;
    if (!file_exists($localCustomerDirBck)) {
        mkdir($localCustomerDirBck, 0777);
    }
    if (!file_exists($localUnitnoDirBck)) {
        mkdir($localUnitnoDirBck, 0777);
    }
    if (!file_exists($localUnitDirBck)) {
        mkdir($localUnitDirBck, 0777);
    }
    if (!file_exists($localSqliteDirBck)) {
        mkdir($localSqliteDirBck, 0777);
    }

    if (!empty($filePath)) {
        if (!file_exists($localSqliteDirBck . $fileName)) {
            file_put_contents(
                $localSqliteDirBck . $fileName, file_get_contents($filePath)
            );
        }

        //echo $fileReadPath;
        $returnPath = $fileReadPath;
    }

    return $returnPath;
}

?>
