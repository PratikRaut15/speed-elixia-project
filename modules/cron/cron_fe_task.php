<?php
require_once '../../lib/bo/CronManager.php';
require_once '../../lib/bo/CustomerManager.php';
include_once '../../lib/system/utilities.php';
$cronm = new CronManager();
 $objCustomer = new CustomerManager();

$moduleid = speedConstants::MODULE_TEAM;
$tasks = $cronm->get_fe_next_day_tasks();

if (isset($tasks)) {
    foreach ($tasks as $thistask) {
        $message = "";
        $message = "Task for " . $thistask->name . ": " . $thistask->customercompany . "(" . $thistask->timeslot . ") -" . $thistask->location . " ( " . $thistask->vehicleno . " ); " . $thistask->person_name . "( " . $thistask->cp_phone1 . " ). Purpose: " . $thistask->purpose . " ; Unit No: " . $thistask->unitno . "( " . $thistask->simcardno . " )";
        if ($thistask->phone != "") {
            $response = '';
           $isSMSSent= sendSMSUtil($thistask->phone, $message, $response);
             if ($isSMSSent == 1) {
                $smsId = $objCustomer->sentSmsPostProcess($thistask->customerno, array($thistask->phone), $message, $response, $isSMSSent, $thistask->userid, $thistask->vehicleid, $moduleid);
            }
        }
    }
}
?>
