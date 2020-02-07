<?php
//file required
//error_reporting(E_ALL ^E_STRICT);
//ini_set('display_errors', 'on');
require_once("class/config.inc.php");
require_once("class/class.api.php");

//ojbect creation
$apiobj = new api();

if (isset($_REQUEST['bookingdetail'])) {
    $bookingdetail = json_decode($_REQUEST['bookingdetail']);
    $bookingResult = $apiobj->insert_booking_details($bookingdetail);
    $statusArray=json_encode($bookingResult);
    echo $statusArray;
}

?>