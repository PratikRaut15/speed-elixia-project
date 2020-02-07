<?php

/*
  Name            -    cronDailyDuplicateTimeDeletion.php
  Description     -    Pull data of a dummy unit of customer (644,524,646,206,353,523,674,643,632,613)
  And delete the record with same date time minute and lesser odometer
  but lesser odometer
  Parameters      -
  Module          -    VTS
  Created By      -    Arvind Thakur
  Created On      -    28 December, 2018
  URL             -    http://speed.elixiatech.com/modules/cron/cronDailyOdometerCorrection.php
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
$RELATIVE_PATH_DOTS = "../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
if (!defined('DS')) {
    define('DS', "/");
}

$serverPath = $_SERVER['SERVER_NAME'];
$rt = $serverPath . "/modules/cron/cronOdometerCorrection.php";

/* Uncomment for LOCAL
  if (isset($IsDebugServer) && $IsDebugServer == TRUE) {
  //    echo 'IsDebugServer=>' . $IsDebugServer . '<br><br><br>';
  $rt = $serverPath . "/elixia_speed/modules/cron/cronOdometerCorrection.php";
  }
 */
//echo $rt . '  ========><br><br><br><br><br><br>';
$serverReadPath = "../../customer/";
$cm = new CronManager(2);
$customerList = speedConstants::CUSTNO_ODOMETER_RESET;
$units = $cm->getOdometerResetUnits($customerList);
$yesterday = isset($_REQUEST['date']) ? date('Y-m-d', strtotime($_REQUEST['date'])) : date('Y-m-d', strtotime('yesterday'));
//$yesterday = date('Y-m-d', strtotime('yesterday'));
if (!empty($units)) {
    foreach ($units AS $unit) {
        $file = $unit->customerno . DS . 'unitno' . DS . $unit->unitno . DS . 'sqlite' . DS . $yesterday . '.sqlite';
        $filePath = $serverReadPath . $file;
        if (file_exists($filePath)) {
            $post_data = array(
                'customerno' => $unit->customerno,
                'unit' => $unit->unitno,
                'date' => $yesterday
            );
//            echo $rt;
//            echo '<br>'.$filePath;
//            prettyPrint($post_data);
//            die();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $rt);
//                curl_setopt($ch, CURLOPT_URL, constants::SPEED_API_URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, speedConstants::REQUEST_TIMEOUT);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
            $response = curl_exec($ch);
            if (curl_error($ch)) {
                echo 'error:' . curl_error($ch);
            }
            curl_close($ch);
            if (isset($response)) {
                echo '<br><br>Completed Odometer Reset for unit : ' . $unit->unitno;
                echo '<br>';
                prettyPrint($response);
            }
        }
    }
}

