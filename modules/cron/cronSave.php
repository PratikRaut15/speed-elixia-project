<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
set_time_limit(0);
ini_set('memory_limit', '2048M');
require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
require_once 'files/push_sqlite.php';
$objCustomer = new CustomerManager();
if (isset($_REQUEST['date']) && $_REQUEST['date'] != '') {
    $date = $_REQUEST['date'];
} else {
    $dt = date('Y-m-d');
    $date = date('Y-m-d', strtotime("-1 days", strtotime($dt)));
}
$customerNo = 0;
if (isset($_REQUEST['customerno']) && $_REQUEST['customerno'] != '') {
    $customerNo = $_REQUEST['customerno'];
}
$dateTime = $date;
$cqm = new ComQueueManager($customerNo);
echo '------------ Pull Comqueue Data ---------------<br/>';
$comqueueData = $cqm->getComqueueHistory($dateTime, $customerNo);
if (isset($comqueueData) && !empty($comqueueData)) {
    $array = json_decode(json_encode($comqueueData), true);
    echo '------------ Reduce Comqueue Data On Customer Wise ---------------<br/>';
    $arrCustomerData = array_reduce($array, function ($result, $currentItem) {
        if (isset($result[$currentItem['customerno']])) {
            $result[$currentItem['customerno']][] = $currentItem;
        } else {
            $result[$currentItem['customerno']][] = $currentItem;
        }
        return $result;
    });
    echo '------------ Start Customer Loop ---------------<br/><br/><br/><br/>';
    if (isset($arrCustomerData) && !empty($arrCustomerData)) {
        foreach ($arrCustomerData as $key => $arrData) {
            echo ' Start Customer Data - ' . $key . '<br/>';
            $timezone = $objCustomer->timezone_name_cron_savesqlite('Asia/Kolkata', $key);
            date_default_timezone_set('' . $timezone . '');
            $dt = strtotime(date("Y-m-d H:i:s", strtotime($dateTime)));
            $date = date("MY", $dt);
            echo ' Create Sqlite File Object <br/>';
            /* Create DB Object For Sqlite File */
            $db = GetCHSqlite($key, $date);
            echo ' Create Comqueue Table In Sqlite File <br/>';
            /* Create Table In Sqlite File For Requird Date */
            $table = createComqueueTable($db);
            echo ' Start Loop On Customer Comqueue <br/>';
            if (isset($arrData) && !empty($arrData)) {
                echo ' Insert Data In Sqlite <br/>';
                foreach ($arrData as $data) {
                    $objData = (object) $data;
                    $exeStatement = insertComqueueInSqlite($db, $objData);
                }
                $firstObj = reset($arrData);
                $lastObj = end($arrData);
                $objDelComqueue = new stdClass();
                $objDelComqueue->fromcqid = $firstObj['cqid'];
                $objDelComqueue->tocqid = $lastObj['cqid'];
                $objDelComqueue->customerno = $key;
                $objDelComqueue->date = date("Y-m-d", strtotime($dateTime));
                echo ' Delete Data From Comqueue <br/>';
                $cqm->deleteComQueueDetails($objDelComqueue);
            }
            echo ' End Customer Data - ' . $key . ' <br/><br/><br/><br/>';
        }
    }
    echo '------------ End Customer Loop ---------------<br/><br/><br/><br/>';
} else {
    echo '------------ Comqueue Data Not Found ---------------<br/>';
}