<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
define("DATEFORMAT_DMY", "dmy");

$dailydate = date('Y-m-d', strtotime("- 1 day"));
$dailydate_tom = date('Y-m-d');

//  ******TESTING*****
    // $dailydate = date('Y-m-d', strtotime("- 2 day"));
    // $dailydate_tom = date('Y-m-d', strtotime("- 1 day"));
//  ******TESTING*****

$today = new DateTime();
$reportDate = new DateTime($dailydate);
$reportDate_tom = new DateTime($dailydate_tom);
$customerno = 64;
$groupid = 0;
$userid = 0;
$useMaintenance = 1;
$useHierarchy = 1;

$objUserManager = new UserManager();
$objUnitManager = new UnitManager($customerno);
$groupmanager = new GroupManager($customerno);
$objVehicleMgr = new VehicleManager($customerno);


$firstDate = date('Y-m-d', strtotime('first day of last month'));
$lastDate = date('Y-m-d', strtotime('last day of last month'));

$date = $firstDate;
$end_date = $lastDate;

//$tableName = 'A' . $dateParam->format(DATEFORMAT_DMY);


//$vehicle = $objVehicleMgr->getVehicleByCust($customerno);

$location = "../../../../customer/$customerno/reports/dailyreport.sqlite";

    if (file_exists($location)) {

        $location = "sqlite:".$location;
        // foreach($vehicle as $veh){
            while (strtotime($date) <= strtotime($end_date)) {
                //$data = new stdClass();
                $isCurrentDaySunday = (date('N', strtotime($date)) == 7) ? 1 : 0;
                if($isCurrentDaySunday){
                    $tableDate = date("dmy",strtotime($date));
                    $sunday_date = date("Y-m-d",strtotime($date));
                    $saturday_date = date ("Y-m-d", strtotime("-1 day", strtotime($sunday_date)));
                    $tableName = 'A'.$tableDate;
                    $query = "SELECT '".$saturday_date."' as saturday ,'".$sunday_date."' as sunday,vehicleid,weekend_distance FROM '" . $tableName . "'"." WHERE is_weekend_drive=1 AND vehicleid=12989";
                    print_r($query."<br/>");
                    $database = new PDO($location);
                    $temp_result = $database->query($query);
                    
                    if($temp_result !== FALSE){
                        $result = $temp_result->fetchall(PDO::FETCH_ASSOC); 
                        if(!empty($result) && is_array($result)){
                          
                          $data[] = $result;
                        }
                        
                    }
                }
                
                
                $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
            }          
        //}
    }else{
        print_r("File Not Present");
        die();
    }
    
    prettyPrint($data);
    die();

    $response = array();
    foreach($data as $day=>$records){
        foreach($records as $k=>$vehicle){
            $nightDriveDistance = array();
            $driveDate = array();
            if(isset($response[$vehicle['vehicleid']])){
                $vehicleIdArray['weekend_distance']= $vehicle['weekend_distance'];
                $vehicleIdArray['saturday']= date("d-m-Y",strtotime($vehicle['saturday']));
                $vehicleIdArray['sunday']= date("d-m-Y",strtotime($vehicle['sunday']));
                $response[$vehicle['vehicleid']][] = $vehicleIdArray;
                // $response[$vehicle['vehicleid']][] = $driveDate;
            }
            else{
                $response[$vehicle['vehicleid']] = array();
                $vehicleIdArray['weekend_distance']= $vehicle['weekend_distance'];
                $vehicleIdArray['saturday']= date("d-m-Y",strtotime($vehicle['saturday']));
                $vehicleIdArray['sunday']= date("d-m-Y",strtotime($vehicle['sunday']));
                $response[$vehicle['vehicleid']][] = $vehicleIdArray;
                // $response[$vehicle['vehicleid']][] = $driveDate;

            }
        }
    }

    // prettyPrint($response);
    // die();
   
 
    //$reportId = 20; //Night Drive ReportId {{DONT CHECK FOR CUSTOMER 64}}
    //$reportTime = $today->format('H');
        
    //$i=0;   //TESTING ITERATOR
    
    $users = $objUserManager->maintenance_userslist($customerno, null, 37);
    
if (isset($users) && !empty($users)) {
    foreach ($users as $thisuser) {
            // if($i==3){
            //     break;
            // }
            $thisuser = (object) $thisuser;
            $total_trips = 0;   
            $table = '';
            foreach ($response as $key=>$vehicle) {
                $total_trips = count($vehicle); //Take count before objectCasting
                $vehicle = (object) $vehicle;
                $vehicle_id = $key;

                if(isset($thisuser->userid) && isset($vehicle_id)){
                    if ($objVehicleMgr->isUserVehicleMappingExists($thisuser->userid,$vehicle_id)) {
                        //$i++;

                        $vehicleData=$objVehicleMgr->getVehiclesById($vehicle_id);
                        $vehicleNo = $vehicleData->vehicleno;//VEHICLE NO FETCH

                        if($customerno == 64){
                          $heirarchyDetails = $objUserManager->vehicleHeirarchy($customerno,$thisuser->userid,$vehicle_id);
                        }
                       

                        $table_cols = 0;
                        $total_drives = 0;
                        $total_weekend_distance = 0;
                        $table .= "<table border=1 style='text-align:center;'>"; 
                        $table .= "<th>SrNo.</th><th>Date(Saturday)</th><th>Date(Sunday)</th><th>No.of Kilometres</th>";
                        foreach($vehicle as $veH){
                            $table_cols++;
                            $table .= "<tr>
                                            <td>".$table_cols."</td>
                                            <td>".$veH['saturday']."</td>
                                            <td>".$veH['sunday']."</td>
                                            <td>".floatval(round($veH['weekend_distance']/1000,2))." kms</td>
                                        </tr>";
                            $total_weekend_distance += floatval(round($veH['weekend_distance']/1000,2));
                                        
                        }
                        $total_drives = $table_cols; 
                        $table .= "</table>";


                        $message = '';
                        $reportPeriod = date("d-m-Y",strtotime($firstDate));
                        $reportPeriod_to = date("d-m-Y",strtotime($lastDate));
                        $month = date("M",strtotime($firstDate));
                        $year = date("Y",strtotime($firstDate));


                        $subject = "Weekend Drive Advisory for Vehicle " . $vehicleNo . " for Period " . $reportPeriod." to ".$reportPeriod_to;

                        $placehoders['{{REALNAME}}'] = $thisuser->realname;
                        $placehoders['{{REPORT_DATE}}'] = $reportPeriod;
                        $placehoders['{{REPORT_DATE_TOM}}'] = $reportPeriod_to;
                        $placehoders['{{CUSTOMER}}'] = $customerno;
                        $placehoders['{{SUBJECT}}'] = $subject;
                        $placehoders['{{VEHICLENUMBER}}'] = $vehicleNo;



                        $placehoders['{{BRANCHNAME}}'] = $heirarchyDetails[0]['branchname'];
                        $placehoders['{{REGIONNAME}}'] = $heirarchyDetails[0]['regionname'];
                        $placehoders['{{ZONENAME}}'] = $heirarchyDetails[0]['zonename'];
                        $placehoders['{{REGIONAL_USERNAME}}'] = $heirarchyDetails[0]['regionalUserSAP'];

                        $placehoders['{{REGIONAL_USERMOBILE}}'] = $heirarchyDetails[0]['regionalUserSAPPhone'];

                        $placehoders['{{REGIONAL_USEREMAIL}}'] = $heirarchyDetails[0]['regionalUserSAPEmail'];


                        $placehoders['{{TOTAL_DRIVES}}'] = $total_drives;
                        $placehoders['{{TOTAL_WEEKEND_DISTANCE}}'] = $total_weekend_distance;
                        $placehoders['{{WEEKEND_DRIVE_TABLE}}'] = $table;
                        $placehoders['{{MONTH}}'] = $month;
                        $placehoders['{{YEAR}}'] = $year;

                        $html = file_get_contents('../../../emailtemplates/cronMonthlyWeekendDrive.html');
                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }

                        /* Send SMS To Branch User*/
                        //$message = "OTP: " . $otpparam . "\r\n" . "Valid Until: " . date('d-M-Y h:i:s A', strtotime($validuptodate));
                        $smsMessage = "Dear " . $thisuser->realname . ",\r\nSafety Advisory!!\r\nVehicle Number " . $vehicleNo." has recorded ".$total_drives." times during vehicle has travelled total distance of ". $total_weekend_distance ."kms  for the ".$month.",".$year. "\r\nPlease follow night drive limits for company vehicle and stay safe.\r\nRegards, \r\nVehicle Management Team- I&S HO.";
            
                        $thisuser->phoneno = $thisuser->phone;
                        
                       
                        echo $smsMessage."<br/>"; //TESTING
                        // if (isset($thisuser->phoneno) && !empty($thisuser->phoneno)){
                        //     $thisuser->phoneno = '8454055958';
                        //     $isSMSSent = sendSMSUtil(array($thisuser->phoneno), $smsMessage, $response);
                        //     $moduleid = 1;
                        //     if ($isSMSSent == 1) {
                        //          echo $smsMessage;
                        //         $objCustomer = new CustomerManager();
                        //         $smsId = $objCustomer->sentSmsPostProcess($customerno, array($thisuser->phoneno), $message, $response, $isSMSSent, $userid, $vehicleId->vehicleid, $moduleid);
                        //     }
                        // }

                        /* Send Email To Branch User and Regional And Zonal Users in CC*/
                        $message .= $html;
                        $attachmentFilePath = '';
                        $attachmentFileName = '';
                        // $CCEmail = '' . $thisuser->regionalUserSAPEmail . ', kanade.akash@mahindra.com';
                        // $mailid = $thisuser->email;
                        // $BCCEmail = 'software@elixiatech.com';
                        

                        echo $message."<br/><br/>";// TESTING
                        $CCEmail = '';
                        $BCCEmail = 'software@elixiatech.com';
                        $mailid = 'kanade.akash@mahindra.com';
                        //$isMailSent = sendMailUtil(array($mailid), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
                        if (isset($isMailSent)) {
                            echo $message;
                        } else {
                            echo "Test";
                        }
                    }
                }
            }
    }
}
//</editor-fold>





// ADD THE BELOW CONDITION IF THIS IS USER SPECIFIC.

// if($thisuser->reportTime == $reportTime && $thisuser->reportId == $reportId){

// }