<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
define("DATEFORMAT_DMY", "dmy");





$dailydate = date('Y-m-d');

//$dailydate = '2019-03-31';  //TESTING

// $reportDate = new DateTime($dailydate);
// $reportDate_tom = new DateTime($dailydate_tom);
$customerno = 64;
$groupid = 0;
$userid = 0;
$useMaintenance = 1;
$useHierarchy = 1;

$objUserManager = new UserManager();
$objUnitManager = new UnitManager($customerno);
$groupmanager = new GroupManager($customerno);
$objVehicleMgr = new VehicleManager($customerno);
$objGeoCodeMgr = new GeoCoder($customerno);

$dateParam = new DateTime($dailydate);
$tableName = 'A' . $dateParam->format(DATEFORMAT_DMY);

$vehicleNo = $objVehicleMgr->getVehicleByCust($customerno);

$vehicles = array();
if(!empty($vehicleNo) && is_array($vehicleNo)){
    foreach($vehicleNo as $vehNo){
        //print_r($vehNo);
        $vehiclesdata = $objVehicleMgr->getVehiclesById($vehNo->vehicleid);
            if(!empty($vehiclesdata)){
               $vehicles[] = $vehiclesdata;   
        }
    }
}else{
    echo "No Vehicles Found";
}

    $i=0;
$users = $objUserManager->maintenance_userslist($customerno, null, 37);

if (isset($users) && !empty($users)) {
    foreach ($users as $thisuser) {
        $thisuser = (object) $thisuser;
        
        foreach($vehicles as $vehicle){
            //&& $vehicle->stoppage_flag!=0
            if($vehicle->ignition==0 && $vehicle->curspeed>0 && (strtotime($dailydate)==strtotime($vehicle->lastupdated_date))){
                if(isset($thisuser->userid) && isset($vehicle->vehicleid)){

                    if ($objVehicleMgr->isUserVehicleMappingExists($thisuser->userid,$vehicle->vehicleid)) {

                        if($customerno == 64){
                          $heirarchyDetails = $objUserManager->vehicleHeirarchy($customerno,$thisuser->userid,$vehicle->vehicleid);
                          //print_r($heirarchyDetails);echo $heirarchyDetails[0]['regionalUserSAP'];die();
                        }

                        $currentDateTime = date("Y-m-d H:i:s");  //Today's DateTime
                        $towingSince = getRunningTime($vehicle->stoppage_transit_time,$currentDateTime);
                        
                        $location = $objGeoCodeMgr->get_location_bylatlong($vehicle->lat,$vehicle->long);

                        if($towingSince<5){
                            $thisuser->realname = $thisuser->realname;
                            $thisuser->phone = $thisuser->phone;
                            $thisuser->email = $thisuser->email;
                        }

                        if($towingSince>5 && $towingSince<10){
                            $thisuser->realname = $thisuser->regionalUserName;
                            $thisuser->phone = $thisuser->regionalUserSAPPhone;
                            $thisuser->email = $thisuser->regionalUserSAPEmail;
                        }


                        if($towingSince>10){
                            $thisuser->realname = $thisuser->zonalUserName;
                            $thisuser->phone = $thisuser->zonalUserSAPPhone;
                            $thisuser->email = $thisuser->zonalUserSAPEmail ;
                        }
                        





                            $message = '';


                            $towingDate =  date("d-m-Y",strtotime($vehicle->lastupdated));
                            $towingTime =  date("H:i:s",strtotime($vehicle->lastupdated));

                            $subject = "Office Vehicle " . $vehicle->vehicleno . " Towing Alerts " . $towingDate;

                            $placehoders['{{REALNAME}}'] = $thisuser->realname;
                            $placehoders['{{DATE}}'] = $towingDate;
                            $placehoders['{{TIME}}'] = $towingTime;
                            $placehoders['{{CUSTOMER}}'] = $customerno;
                            $placehoders['{{SUBJECT}}'] = $subject;
                            $placehoders['{{VEHICLENUMBER}}'] = $vehicle->vehicleno;
                            $placehoders['{{LOCATION}}'] = $location;


                            $placehoders['{{BRANCHNAME}}'] = $heirarchyDetails[0]['branchname'];
                            $placehoders['{{REGIONNAME}}'] = $heirarchyDetails[0]['regionname'];
                            $placehoders['{{ZONENAME}}'] = $heirarchyDetails[0]['zonename'];
                            $distance_travelled = round($vehicle->total_distance/1000,2);
                            $placehoders['{{DISTANCE_NIGHT}}'] = $distance_travelled;

                            $html = file_get_contents('../../../emailtemplates/cronTowingAlerts.html');
                            foreach ($placehoders as $key => $val) {
                                $html = str_replace($key, $val, $html);
                            }

                            
                            $smsMessage = "Dear " . $thisuser->realname . ",\r\n Towing Alert!!\r\nVehicle Number " . $vehicle->vehicleno." is being towed on ".$towingDate." at ".$towingTime."\r\nLast known location ".$location."\r\nPlease take corrective action if vehicle is not being towed by you.\r\n\nRegards Vehicle Management Team-I & HO.";
                            $response = '';
                            $thisuser->phoneno = $thisuser->phone;
                            
                            //echo $smsMessage.'<br/>';   //TESTING 

                            if (isset($thisuser->phoneno) && !empty($thisuser->phoneno)){
                                $thisuser->phoneno = '8454055958'; 

                                $isSMSSent = sendSMSUtil(array($thisuser->phoneno), $smsMessage, $response);
                                $moduleid = 1;
                                if ($isSMSSent == 1) {
                                    echo $smsMessage;
                                    $objCustomer = new CustomerManager();
                                    $smsId = $objCustomer->sentSmsPostProcess($customerno, array($thisuser->phoneno), $message, $response, $isSMSSent, $userid, $vehicle->vehicleid, $moduleid);
                                }
                                else{
                                    echo "SMS Not Sent to :-".$thisuser->phoneno."<br/>";     
                                }
                            }

                            /* Send Email To Branch User and Regional And Zonal Users in CC*/
                            $message .= $html;
                            $attachmentFilePath = '';
                            $attachmentFileName = '';

                            // $CCEmail = 'kanade.akash@mahindra.com';

                            // $mailid = $thisuser->email;

                            // $BCCEmail = 'software@elixiatech.com';
                            
                            // $CCEmail = '';
                            $BCCEmail = 'software@elixiatech.com';
                            $mailid = 'kanade.akash@mahindra.com';
                            //echo $message;  //TESTING


                            $isMailSent = sendMailUtil(array($mailid), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
                            if (isset($isMailSent)) {
                                echo $message;
                            } else {
                                echo "Email Not Sent to :-".$mailid."<br/>";
                            }
                        

                    }//
                }
            }
           
        }
    }
}
//</editor-fold>


function getRunningTime($startdate,$enddate){
    $seconds = strtotime($enddate) - strtotime($startdate);

    $days    = floor($seconds / 86400);
    $hours   = floor(($seconds - ($days * 86400)) / 3600);
    $minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);
    $seconds = floor(($seconds - ($days * 86400) - ($hours * 3600) - ($minutes*60)));

    return $minutes;
}


// ADD THE BELOW CONDITION IF THIS IS USER SPECIFIC.

// if($thisuser->reportTime == $reportTime && $thisuser->reportId == $reportId){

// }