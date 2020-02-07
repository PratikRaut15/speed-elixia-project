<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$RELATIVE_PATH_DOTS = "../../../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
define("DATEFORMAT_DMY", "dmy");

$lastSunday = date('Y-m-d',strtotime('last sunday'));
$lastSaturday =  date('Y-m-d',strtotime('last saturday'));


$today = new DateTime();
$startDate = new DateTime($lastSaturday);
$endDate = new DateTime($lastSunday);
$customerno = 64;
$groupid = 0;
$userid = 0;
$useMaintenance = 1;
$useHierarchy = 1;

$objUserManager = new UserManager();
$objUnitManager = new UnitManager($customerno);
$groupmanager = new GroupManager($customerno);
$objVehicleMgr = new VehicleManager($customerno);


$dateParam = new DateTime($lastSunday);
$tableName = 'A' . $dateParam->format(DATEFORMAT_DMY);

$location = "../../../../customer/$customerno/reports/dailyreport.sqlite";

    if (file_exists($location)) {
        $location = "sqlite:".$location;

        $query = "SELECT * FROM '" . $tableName . "'"." WHERE is_weekend_drive='1'";
        $database = new PDO($location);
        $temp_result = $database->query($query);
        $result = $temp_result->fetchall(PDO::FETCH_ASSOC);
    }
    $i=0;  //ITERATOR

    



    //$reportId = 22; //Weekend Drive ReportId {{DONT CHECK FOR CUSTOMER 64}}
    //$reportTime = $today->format('H');
    $users = $objUserManager->maintenance_userslist($customerno, null, 37);
   
    
if (isset($users) && !empty($users)) {
    foreach ($users as $thisuser) {
        if($i==3){
            break;
        }
        $thisuser = (object) $thisuser;
            foreach ($result as $unit) {
                $vehicleId = $objUnitManager->getvehiclefromunit($unit['uid']);
               
                    if(isset($thisuser->userid) && isset($vehicleId->vehicleid)){
                        if ($objVehicleMgr->isUserVehicleMappingExists($thisuser->userid,$vehicleId->vehicleid)) {
                             $i++;
                            //prettyPrint($thisdata);

                            if($customerno == 64){
                              $heirarchyDetails = $objUserManager->vehicleHeirarchy($customerno,$thisuser->userid,$vehicleId->vehicleid);

                              //print_r($heirarchyDetails);echo $heirarchyDetails[0]['regionalUserSAP'];die();
                            }

                            $message = '';
                            $reportPeriod = $startDate->format(speedConstants::DEFAULT_DATE);
                            $reportPeriod_tom = $endDate->format(speedConstants::DEFAULT_DATE);

                            $subject = "Office Vehicle " . $vehicleId->vehicleno . " Weekend Drive Advisory " . $reportPeriod;

                            $placehoders['{{REALNAME}}'] = $thisuser->realname;
                            $placehoders['{{REPORT_DATE}}'] = $reportPeriod;
                            $placehoders['{{REPORT_DATE_TOM}}'] = $reportPeriod_tom;
                            $placehoders['{{CUSTOMER}}'] = $customerno;
                            $placehoders['{{SUBJECT}}'] = $subject;
                            $placehoders['{{VEHICLENUMBER}}'] = $vehicleId->vehicleno;



                            $placehoders['{{BRANCHNAME}}'] = $heirarchyDetails[0]['branchname'];
                            $placehoders['{{REGIONNAME}}'] = $heirarchyDetails[0]['regionname'];
                            $placehoders['{{ZONENAME}}'] = $heirarchyDetails[0]['zonename'];

                            $distance_travelled = round($unit['weekend_distance']/1000,2);
                            $placehoders['{{DISTANCE_WEEKEND}}'] = $distance_travelled;

                            $html = file_get_contents('../../../emailtemplates/cronWeekendDriveTemplate.html');
                            foreach ($placehoders as $key => $val) {
                                $html = str_replace($key, $val, $html);
                            }

                            /* Send SMS To Branch User*/
                            //$message = "OTP: " . $otpparam . "\r\n" . "Valid Until: " . date('d-M-Y h:i:s A', strtotime($validuptodate));
                            $smsMessage = "Dear " . $thisuser->realname ."\r\n, Weekend Drive Advisory!\r\nVehicle Number " . $vehicleId->vehicleno . " has travelled distance of ".$distance_travelled." kms from ".$reportPeriod ." to ".$reportPeriod_tom."\r\nPlease take Reporting Manager's approval for using vehicles on weekend.\r\nRegards, \r\nVehicle Management Team- I&S HO.";
                            $response = '';
                            $thisuser->phoneno = $thisuser->phone;
                            
                            
                            //echo $smsMessage;  //TESTING

                            if (isset($thisuser->phoneno) && !empty($thisuser->phoneno)) {
                                $thisuser->phoneno = '8454055958';
                                $isSMSSent = sendSMSUtil(array($thisuser->phoneno), $smsMessage, $response);
                                $moduleid = 1;
                                if ($isSMSSent == 1) {
                                    echo "SMS\n";
                                    echo $smsMessage;
                                    $objCustomer = new CustomerManager();
                                    $smsId = $objCustomer->sentSmsPostProcess($customerno, array($thisuser->phoneno), $message, $response, $isSMSSent, $userid,$vehicleId->vehicleid, $moduleid);
                                }else{
                                    echo "SMS NOT SENT";
                                }
                            }

                            /* Send Email To Branch User and Regional And Zonal Users in CC*/
                            $message .= $html;
                            $attachmentFilePath = '';
                            $attachmentFileName = '';
                            // $CCEmail = '' . $thisuser->regionalUserSAPEmail . ', kanade.akash@mahindra.com';
                            // $mailid = $thisuser->email;
                            // $BCCEmail = 'software@elixiatech.com';

                            $CCEmail = '';
                            $BCCEmail = 'software@elixiatech.com';
                            $mailid = 'kanade.akash@mahindra.com';
                            $isMailSent = sendMailUtil(array($mailid), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
                            //echo $message;  //TESTING


                            if (isset($isMailSent)) {
                                echo "EMAIL\n";
                                echo $message;
                            } else {
                                echo "Test";
                            }
                        }
                    }
                //prettyPrint($arrOverspeed);
            }
        
    }
}
//</editor-fold>


// if($thisuser->reportTime == $reportTime && $thisuser->reportId == $reportId){

// }