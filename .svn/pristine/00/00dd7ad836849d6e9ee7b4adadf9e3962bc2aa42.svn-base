<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//error_reporting(-1);

ini_set('memory_limit', '256M');

include_once '../../lib/bo/DailyReportManager.php';
include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/PointLocationManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/UnitManager.php';
//include_once '../../lib/bo/CommunicationQueueManager.php';
include_once 'files/dailyreport.php';

class VODatacap {
}

$drm = new DailyReportManager(0);
$customerno = $_GET['customerno'];
$date = $_GET['date']; //'2017-04-28';
$devices = $drm->GetDevicesForReport_by_limit($customerno);
$Bad = 1;
//print_r($devices);
$email = 'sanketsheth@elixiatech.com';
$subject = "Error CronDaily Report";
set_time_limit(0);

if (isset($devices) && !empty($devices)) {
    $reports = array();
    $ids = array();
    foreach ($devices as $device) {
        if ($Bad != 0) {
            $unitno = $device->unitno;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";

            if (file_exists($location)) {
                echo $location;
                $Data = DataFromSqlite($location);
                if ($Data != 0) {
                    if (count($Data) > 0) {
                        $um = new UnitManager($customerno);
                        $acdata = $um->getacinvertval($unitno);
                        $acinvertval = $acdata['0'];
                        $acsensor = $acdata['1'];
                        //echo $unitno.'_'.$customerno.'_'.$acinvertval.'_'.$acsensor;
                        $reports[$customerno][] = DailyReport($device, $date, $Data, $device->overspeed_limit, $acinvertval, $acsensor);
                    }
                } else {
                    $Bad = 0;
                }
            }
        } else {
            break;
        }
    }

    if (isset($reports) && !empty($reports)) {
        foreach ($reports as $customer_report) {
            $location = "../../customer/$customerno/reports/dailyreport.sqlite";
            $PATH = "sqlite:$location";
            $tablename = "A" . date('dmy', strtotime($date));
            $dber = new PDO($PATH) or die("Can Not Open Database");
            try {
                DELETEDAILYTABLE($tablename, $dber);
            } catch (PDOException $e) {
                $Bad = 0;
            }
        }
    }
    if ($Bad != 0 && !empty($reports)) {
        foreach ($reports as $customer_report) {
            $customerno = $customer_report[0]['customerno'];
            $path = "sqlite:../../customer/$customerno/reports/dailyreport.sqlite";
            if ($Bad != 0) {
                try {
                    $db = new PDO($path);
                    $db->exec('BEGIN IMMEDIATE TRANSACTION');
                    $datecheck = array();
                    foreach ($customer_report as $report) {
                        TRANSACTIONG($report, $db, 1);
                    }
                    $db->exec('COMMIT TRANSACTION');
                } catch (PDOException $e) {
                    $Bad = 0;
                }
            } else {
                $message = "Error while Pushing Data into sqlite for report ids - " . implode(',', $ids);
                //sendMail($email, $subject, $message);
                break;
            }
        }
        if ($Bad != 0 && !empty($ids)) {
            $drm->DeleteRepMan($ids);
        }

        echo "DailyReport Updated ";
    } else {
        $message = "Error while fetching data from sqlite for reportids - " . implode(',', $ids);
        //sendMail($email, $subject, $message);
    }
}

function sendMail($to, $subject, $content) {
    $subject = $subject;

    $headers = "From: noreply@elixiatech.com\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    if (!@mail($to, $subject, $content, $headers)) {
        // message sending failed
        return false;
    }
    return true;
}

?>
