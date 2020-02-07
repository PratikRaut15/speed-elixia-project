<?php
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../../../";
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
define("DATEFORMAT_DMY", "dmy");
ini_set("max_execution_time", "3000");
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
$firstDate = date('Y-m-d', strtotime('first day of last month'));
$lastDate = date('Y-m-d', strtotime('last day of last month'));
$date = $firstDate;
$end_date = $lastDate;
//$tableName = 'A' . $dateParam->format(DATEFORMAT_DMY);
//$vehicle = $objVehicleMgr->getVehicleByCust($customerno);
$location = "../../../../customer/$customerno/reports/dailyreport.sqlite";
if (file_exists($location)) {
    $location = "sqlite:" . $location;
    // foreach($vehicle as $veh){
    while (strtotime($date) <= strtotime($end_date)) {
        //$data = new stdClass();
        $tableDate = date("dmy", strtotime($date));
        $tableName = 'A' . $tableDate;
        $query = "SELECT vehicleid,overspeed,topspeed,topspeed_lat,topspeed_long, topspeed_datetime FROM '" . $tableName . "'" . " where overspeed > 0";
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
    //}
} else {
    print_r("File Not Present");
    die();
}
//print_r($data);
$response = array();
foreach ($data as $day => $records) {
    foreach ($records as $k => $vehicle) {
        $overSpeedCount = array();
        $topSpeed = array();
        if (isset($response[$vehicle['vehicleid']])) {
            $overSpeedCount['overspeed_count'] = $vehicle['overspeed'];
            $topSpeed['topspeed'] = $vehicle['topspeed'];
            $topSpeed['lat'] = $vehicle['topspeed_lat'];
            $topSpeed['long'] = $vehicle['topspeed_long'];
            $topSpeed['topspeed_datetime'] = date("d-m-Y H:i:s", strtotime($vehicle['topspeed_datetime']));
            $response[$vehicle['vehicleid']]['topSpeed'][] = $topSpeed;
            $response[$vehicle['vehicleid']]['overSpeedCount'][] = $overSpeedCount;
        } else {
            $response[$vehicle['vehicleid']] = array();
            $overSpeedCount['overspeed_count'] = $vehicle['overspeed'];
            $topSpeed['topspeed'] = $vehicle['topspeed'];
            $topSpeed['lat'] = $vehicle['topspeed_lat'];
            $topSpeed['long'] = $vehicle['topspeed_long'];
            $topSpeed['topspeed_datetime'] = date("d-m-Y H:i:s", strtotime($vehicle['topspeed_datetime']));
            $response[$vehicle['vehicleid']]['topSpeed'][] = $topSpeed;
            $response[$vehicle['vehicleid']]['overSpeedCount'][] = $overSpeedCount;
        }
    }
}
//prettyPrint($response);
// die();
$vehicleData = array();
foreach ($response as $vehId => $row) {
    // prettyPrint($row);
    // die();
    $CountData = calcTotalOverSpeeding($row['overSpeedCount']);
    $OverSpeedData = maxTopSpeedLoctn($row['topSpeed']);
    $vehicleData[$vehId] = $OverSpeedData;
    $vehicleData[$vehId]['vehicleid'] = $vehId;
    $vehicleData[$vehId]['totalCount'] = $CountData;
    //die();
}
//prettyPrint($vehicleData);
//die();
$i = 0; //TESTING ITERATOR
$users = $objUserManager->maintenance_userslist($customerno, null, 37);
//prettyPrint($users);
// die();
if (isset($users) && !empty($users)) {
    foreach ($users as $thisuser) {
        $thisuser = (object) $thisuser;
        foreach ($vehicleData as $vehicle) {
            $vehicle = (object) $vehicle;
            if (isset($thisuser->userid) && isset($vehicle->vehicleid)) {
                //echo "First";
                if ($objVehicleMgr->isUserVehicleMappingExists($thisuser->userid, $vehicle->vehicleid)) {
                    //echo "test";
                    //$i++;
                    $vehicle_Data = $objVehicleMgr->getVehiclesById($vehicle->vehicleid);
                    $vehicleNo = $vehicle_Data->vehicleno; //VEHICLE NO FETCH
                    $location = $objGeoCodeMgr->get_location_bylatlong($vehicle->lat, $vehicle->long); //LOCATION
                    if ($customerno == 64) {
                        $heirarchyDetails = $objUserManager->vehicleHeirarchy($customerno, $thisuser->userid, $vehicle->vehicleid);
                        print_r($heirarchyDetails);echo $heirarchyDetails[0]['regionalUserSAP'];//die();
                    }
                    $message = '';
                    $reportPeriod = date("d-m-Y", strtotime($firstDate));
                    $reportPeriod_to = date("d-m-Y", strtotime($lastDate));
                    $month = date("M", strtotime($firstDate));
                    $year = date("Y", strtotime($firstDate));
                    $subject = "OverSpeed Advisory for " . $vehicleNo . " for Period " . $reportPeriod . " To " . $reportPeriod_to;
                    $placehoders['{{REALNAME}}'] = $thisuser->realname;
                    $placehoders['{{REPORT_DATE}}'] = $reportPeriod;
                    $placehoders['{{REPORT_DATE_TOM}}'] = $reportPeriod_to;
                    $placehoders['{{CUSTOMER}}'] = $customerno;
                    $placehoders['{{SUBJECT}}'] = $subject;
                    $placehoders['{{VEHICLENUMBER}}'] = $vehicleNo;
                    $placehoders['{{BRANCHNAME}}'] = $heirarchyDetails[0]['branchname'];
                    $placehoders['{{REGIONNAME}}'] = $heirarchyDetails[0]['regionname'];
                    $placehoders['{{TOTAL_NUMBER}}'] = $vehicle->totalCount;
                    $placehoders['{{TOPSPEED}}'] = $vehicle->topspeed;
                    $placehoders['{{LOCATION}}'] = $location;
                    $placehoders['{{DATETIME}}'] = $vehicle->topspeed_datetime;
                    $placehoders['{{ZONENAME}}'] = $heirarchyDetails[0]['zonename'];
                    $placehoders['{{REGIONAL_USERNAME}}'] = $heirarchyDetails[0]['regionalUserSAP'];
                    $placehoders['{{REGIONAL_USERMOBILE}}'] = $heirarchyDetails[0]['regionalUserSAPPhone'];
                    $placehoders['{{REGIONAL_USEREMAIL}}'] = $heirarchyDetails[0]['regionalUserSAPEmail'];
                    $html = file_get_contents('../../../emailtemplates/cronMonthlyOverSpeedReport.html');
                    foreach ($placehoders as $key => $val) {
                        $html = str_replace($key, $val, $html);
                    }
                    /* Send SMS To Branch User*/
                    //$message = "OTP: " . $otpparam . "\r\n" . "Valid Until: " . date('d-M-Y h:i:s A', strtotime($validuptodate));
                    $smsMessage = "Dear " . $thisuser->realname . ",\r\nVehicle Number " . $vehicleNo . " has recorded " . $vehicle->totalCount . " speed exception for the Month " . $month . "," . $year . "\r\nPlease follow speed limits for company vehicles and stay safe.\r\n\nRegards, Vehicle Mgmt Team- I&S HO.";
                    $response = '';
                    $thisuser->phoneno = $thisuser->phone;
                    //echo $smsMessage;  //TESTING
                    if (isset($thisuser->phoneno) && !empty($thisuser->phoneno)) {
                        //$thisuser->phoneno = '8454055958';
                        $isSMSSent = sendSMSUtil(array($thisuser->phoneno), $smsMessage, $response);
                        $moduleid = 1;
                        if ($isSMSSent == 1) {
                            echo "SMS\n";
                            echo $smsMessage . "<br/>";
                            $objCustomer = new CustomerManager();
                            $smsId = $objCustomer->sentSmsPostProcess($customerno, array($thisuser->phoneno), $message, $response, $isSMSSent, $userid, $vehicle->vehicleid, $moduleid);
                        } else {
                            echo "SMS NOT SENT";
                        }
                    }
                    /* Send Email To Branch User and Regional And Zonal Users in CC*/
                    //$thisuser->email = 'kanade.akash@mahindra.com'; //CLIENT TESTT
                    $message .= $html;
                    $attachmentFilePath = '';
                    $attachmentFileName = '';
                    //$CCEmail = '' . $thisuser->regionalUserSAPEmail . ', kanade.akash@mahindra.com';
                    $CCEmail = 'KANADE.AKASH@mahindra.com, CHOGALE.SAMEER@mahindra.com';
                    $mailid = 'shrikants@elixiatech.com'; //$thisuser->email;
                    $BCCEmail = 'software@elixiatech.com';
                    // $CCEmail = '';
                    // $BCCEmail = 'software@elixiatech.com';
                    // $mailid = 'kanade.akash@mahindra.com';
                    $isMailSent = 0; //sendMailUtil(array($mailid), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
                    echo $message; //TESTING
                    if (isset($isMailSent)) {
                        echo "EMAIL\n";
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
function calcTotalOverSpeeding($records) {
    $value = array_sum(array_column($records, 'overspeed_count'));
    return $value;
}

function maxTopSpeedLoctn($records) {
    $data = array_reduce($records, function ($a, $b) {
        return @$a['topspeed'] > $b['topspeed'] ? $a : $b;
    });
    return $data;
}

// if($thisuser->reportTime == $reportTime && $thisuser->reportId == $reportId){
// }
