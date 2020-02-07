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
    $location = "sqlite:" . $location;

    while (strtotime($date) <= strtotime($end_date)) {
        //$data = new stdClass();
        $tableDate = date("dmy", strtotime($date));
        $tableName = 'A' . $tableDate;
        $query = "SELECT vehicleid,topspeed_datetime,night_distance FROM '" . $tableName . "'" . " WHERE is_night_drive=1 AND night_distance>2000";
        //print_r($query."<br/>");
        $database = new PDO($location);
        $temp_result = $database->query($query);

        if ($temp_result !== FALSE) {
            $result = $temp_result->fetchall(PDO::FETCH_ASSOC);
            if (!empty($result) && is_array($result)) {
                $data[] = $result;
            }
        }

        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
    }
} else {
    print_r("File Not Present");
    die();
}

// prettyPrint($data);
// die();

$response = array();
foreach ($data as $day => $records) {
    foreach ($records as $k => $vehicle) {
        $nightDriveDistance = array();
        $driveDate = array();
        if (isset($response[$vehicle['vehicleid']])) {
            $vehicleIdArray['night_distance'] = $vehicle['night_distance'];
            $vehicleIdArray['night_drive_date'] = date("d-m-Y", strtotime($vehicle['topspeed_datetime']));
            $response[$vehicle['vehicleid']][] = $vehicleIdArray;
        } else {
            $response[$vehicle['vehicleid']] = array();
            $vehicleIdArray['night_distance'] = $vehicle['night_distance'];
            $vehicleIdArray['night_drive_date'] = date("d-m-Y", strtotime($vehicle['topspeed_datetime']));
            $response[$vehicle['vehicleid']][] = $vehicleIdArray;
        }
    }
}

// prettyPrint($response);
// die();

//$reportId = 20; //Night Drive ReportId {{DONT CHECK FOR CUSTOMER 64}}
//$reportTime = $today->format('H');
$i = 0; //TESTING ITERATOR

$users = $objUserManager->maintenance_userslist($customerno, null, 37);

if (isset($users) && !empty($users)) {
    foreach ($users as $thisuser) {
        // if($i==3){
        //     break;
        // }
        $thisuser = (object) $thisuser;
        $total_trips = 0;
        $table = '';
        foreach ($response as $key => $vehicle) {
            $total_trips = count($vehicle); //Take count before objectCasting
            $vehicle = (object) $vehicle;
            $vehicle_id = $key;

            if (isset($thisuser->userid) && isset($vehicle_id)) {
                if ($objVehicleMgr->isUserVehicleMappingExists($thisuser->userid, $vehicle_id)) {
                    $i++;

                    $vehicleData = $objVehicleMgr->getVehiclesById($vehicle_id);
                    $vehicleNo = $vehicleData->vehicleno; //VEHICLE NO FETCH

                    if ($customerno == 64) {
                        $heirarchyDetails = $objUserManager->vehicleHeirarchy($customerno, $thisuser->userid, $vehicle_id);
                    }

                    $table_cols = 0;
                    $total_drives = 0;
                    $total_nightDrive_distance = 0;
                    $table .= "<table border=1 style='text-align:center;'>";
                    $table .= "<th>SrNo.</th><th>Date</th><th>No.of Kilometres</th>";
                    foreach ($vehicle as $veH) {
                        $table_cols++;
                        $table .= "<tr>
                                            <td>" . $table_cols . "</td>
                                            <td>" . $veH['night_drive_date'] . "</td>
                                            <td>" . floatval(round($veH['night_distance'] / 1000, 2)) . " kms</td>
                                        </tr>";
                        $total_nightDrive_distance += floatval(round($veH['night_distance'] / 1000, 2));
                    }
                    $total_drives = $table_cols;
                    $table .= "</table>";

                    $message = '';
                    $reportPeriod = date("d-m-Y", strtotime($firstDate));
                    $reportPeriod_to = date("d-m-Y", strtotime($lastDate));
                    $month = date("M", strtotime($firstDate));
                    $year = date("Y", strtotime($firstDate));

                    $subject = "Night Drive Advisory for Vehicle " . $vehicleNo . " for Period " . $reportPeriod . " to " . $reportPeriod_to;

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
                    $placehoders['{{TOTAL_NIGHT_DISTANCE}}'] = $total_nightDrive_distance;
                    $placehoders['{{NIGHT_DRIVE_TABLE}}'] = $table;
                    $placehoders['{{MONTH}}'] = $month;
                    $placehoders['{{YEAR}}'] = $year;

                    $html = file_get_contents('../../../emailtemplates/cronMonthlyNightTravelReport.html');
                    foreach ($placehoders as $key => $val) {
                        $html = str_replace($key, $val, $html);
                    }

                    /* Send SMS To Branch User*/
                    //$message = "OTP: " . $otpparam . "\r\n" . "Valid Until: " . date('d-M-Y h:i:s A', strtotime($validuptodate));
                    $smsMessage = "Dear " . $thisuser->realname . ",\r\nSafety Advisory!!\r\nVehicle Number " . $vehicleNo . " has recorded " . $total_drives . " times during vehicle has travelled total distance of " . $total_nightDrive_distance . "kms  for the " . $month . "," . $year . " i.e. exceeds night drive limit.\r\nPlease follow night drive limits for company vehicle and stay safe.\r\nRegards, \r\nVehicle Management Team- I&S HO.";

                    $thisuser->phoneno = $thisuser->phone;

                    //echo $smsMessage."<br/>"; //TESTING
                    if (isset($thisuser->phoneno) && !empty($thisuser->phoneno)) {
                        //$thisuser->phoneno = '8454055958';
                        $isSMSSent = sendSMSUtil(array($thisuser->phoneno), $smsMessage, $SMS_response);
                        $moduleid = 1;
                        if ($isSMSSent == 1) {
                            echo $smsMessage . "<br/>";
                            $objCustomer = new CustomerManager();
                            $smsId = $objCustomer->sentSmsPostProcess($customerno, array($thisuser->phoneno), $message, $response, $isSMSSent, $userid, $vehicleId->vehicleid, $moduleid);
                        }
                    }
//
                    /* Send Email To Branch User and Regional And Zonal Users in CC*/
                    $message .= $html;
                    $attachmentFilePath = '';
                    $attachmentFileName = '';
                    $CCEmail = '' . $thisuser->regionalUserSAPEmail . ', kanade.akash@mahindra.com';
                    //$CCEmail = 'KANADE.AKASH@mahindra.com, CHOGALE.SAMEER@mahindra.com';
                    $mailid = $thisuser->email;
                    $BCCEmail = '';

                    //echo $message."<br/><br/>";// TESTING
                    // $CCEmail = '';
                    // $BCCEmail = 'software@elixiatech.com';
                    // $mailid = 'kanade.akash@mahindra.com';
                    $isMailSent = sendMailUtil(array($mailid), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
                    if (isset($isMailSent)) {
                        echo $message . "<br/>";
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
