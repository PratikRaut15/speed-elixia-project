<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
/*
   Cron customer specific - 328  
   Load End - 3 
   Unloading End - 10 
 * 
 *  */

require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
require_once '../../modules/trips/class/TripsManager.php';
$curtime = date('Y-m-d H:i:s');
$objcustomerManager = new CustomerManager();
$customerlist = $objcustomerManager->getCustomerUseTrip();
/* Get All Open Trips */
foreach($customerlist as $row){
    if($row['customerno']==328){
        $userid = 5497;
        $customerno = $row['customerno'];
        $vehobj = new VehicleManager($customerno);
        $objTripManager = new Trips($customerno, $userid);
        //Get viewtriprecords 
        $openTrips = $objTripManager->get_viewtriprecords();
        if(isset($openTrips)){
            foreach($openTrips as $row){
                $vehicleid = $row['vehicleid']; 
                $conchkid = $row['conchkid']; 
                $consrchkid = $row['consrchkid']; 
                $tripstatusid = $row['tripstatusid'];
                $statuscnsr = $vehobj->chkstatustrip($vehicleid,$consrchkid,1);
                if($statuscnsr==1 && $tripstatusid!=4){
                    $statusid = 4;
                    $istripend=0;
                    $update = $objTripManager->edittripdetailscron($row,$statusid,$istripend);
                }
                $status = $vehobj->chkstatustrip($vehicleid,$conchkid,0);
                if($status==1 && $tripstatusid!=10){
                    $statusid = 10;
                    $istripend=1;
                    $update = $objTripManager->edittripdetailscron($row,$statusid,$istripend);
                }
            }
        }
    }
}
?>

