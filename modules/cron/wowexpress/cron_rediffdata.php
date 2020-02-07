<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
$date1 = date("d-m-Y");
$date = date('d-m-Y',strtotime($date1));
$today = date('Y-m-d');


require_once '../../../lib/system/Validator.php';
require_once '../../../lib/system/Sanitise.php';
require_once '../../../lib/system/Date.php';
require_once 'class/RediffManager.php';

$hubkey = new RediffManager(127,536);
$hubkeydata = $hubkey->gethubkeys();
//print_r($hubkeydata);

/**
 * This CRON would run every hour to get the orders from rediff webservice 
 * and inserts these pickups in pickup db
 *
 * @author Mrudang Vora
 */
$hubkey = new RediffManager(127,536);

foreach($hubkeydata  as $row){
    $transactionList = $hubkey->GetData($row['hubkey']);
    $hubkey->InsertPickupOrders($transactionList);
}





