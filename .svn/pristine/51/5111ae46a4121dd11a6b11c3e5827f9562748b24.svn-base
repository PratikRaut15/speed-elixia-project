<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$reportId = speedConstants::REPORT_INACTIVE_VEHICLE;
$today = new DateTime();
$reportDate = $today->sub(new DateInterval('P1D'));
$date = $reportDate->format(speedConstants::DEFAULT_DATE);
$serverPath = "http://www.speed.elixiatech.com";
$download = $serverPath . "/modules/download/report.php?q=";
$routehistpath = $serverPath . "/modules/reports/reports.php";
$fetchSpecificVehicles = 0;
$objReportUser = new stdClass();
$objReportUser->reportId = $reportId;
$objReportUser->reportTime = $today->format('H');
$objUserManager = new UserManager();
$users = $objUserManager->getUsersForReport($objReportUser);
// echo '<pre>';
// print_r($users);
// die();
$customerUserArray = cronCustomerUsers($users);
$objCustomerManager = new CustomerManager();
$objUserManager = new UserManager();
if (isset($customerUserArray) && !empty($customerUserArray)) {
    foreach ($customerUserArray as $customer => $customerDetails) {
        $timezone = $objCustomerManager->timezone_name_cron(speedConstants::IST_TIMEZONE, $customer);
        date_default_timezone_set('' . $timezone . '');
        $customer_details = $objCustomerManager->getcustomerdetail_byid($customer);
        $vehInactiveReport = $vehInactiveWarehouseReport = '' ;
        $vehicleManager = new VehicleManager($customer);
        foreach ($customerDetails as $userDetails) {
            foreach ($userDetails as $user) {
                if ($user['email'] != '') {
                    $fetchSpecificVehicles = 0;
                    if($user['userrole']=='Custom'){
                        $fetchSpecificVehicles = 1;
                    }
                    $vehInactiveReport='';
                    $vehInactiveWarehouseReport='';
                    $wh_class = '';
                    $veh_class = '';
                    $humidity_class ="";
                    $message = "";
                    $tableRows = "";
                    $temperatureReport = '';
                    $temperatureWarehouseReport = '';
                    $temperatureHumidityReport  = '';
                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{REPORT_DATE}}'] = $reportDate->format(speedConstants::DEFAULT_DATE);
                    $placehoders['{{CUSTOMER}}'] = $customer;
                    $placehoders['{{ENCODEKEY}}'] = sha1($user['userkey']);
                    $subject = "Inactive Vehicle Report For  " . $reportDate->format(speedConstants::DEFAULT_DATE);
                    $timestamp = strtotime($today->format(speedConstants::DEFAULT_DATE));
                    $userGroups = $objUserManager->get_groups_fromuser($customer, $user['userid']);
                    $arrGroups = array();
                    if (isset($userGroups) && !empty($userGroups)) {
                        foreach ($userGroups as $group) {
                            $arrGroups[] = $group->groupid;
                        }
                    }
                    if ($customer_details->use_tracking) {
                        $vehicleManager = new VehicleManager($customer);
                        $x=0;
                        $vehicles = $vehicleManager->get_all_vehicles_by_group($arrGroups, $isWarehouse = 0,$fetchSpecificVehicles,$user['userid']);
                        // print_r($vehicles);
                        // die();
                        $lessthan_hour_ago = date("Y-m-d H:i:s",strtotime('-1 hour'));
                        if (isset($vehicles) && !empty($vehicles)) {
                            foreach ($vehicles as $vehicle) {
                                $ServerDate  = date("Y-m-d H:i:s");
                                $totalHours = 24*($user['interval']);
                                $ServerDateIST_LessTotalHrs = add_hours($ServerDate, -($totalHours));
                                $lastupdated = date(speedConstants::DEFAULT_DATETIME,strtotime($vehicle->lastupdated));
                                    // && strtotime($vehicle->lastupdated) > $lessthan_hour_ago
                                if(strtotime($vehicle->lastupdated)<strtotime($ServerDateIST_LessTotalHrs) && strtotime($vehicle->lastupdated) < strtotime($lessthan_hour_ago)){
                                    if($vehicle->simcardno!=''){
                                        $x++;
                                        if($customer == 64){
                                          $heirarchyDetails = $objUserManager->vehicleHeirarchy($customer,$user['userid'],$vehicle->vehicleid);
                                          //print_r($heirarchyDetails);echo $heirarchyDetails[0]['regionalUserSAP'];die();
                                        }

                                        $vehInactiveReport.="<tr>
                                                            <td>".$x."</td>
                                                            <td>".$vehicle->vehicleno."</td>
                                                            <td>".$vehicle->groupname."</td>";

                                                            if($customer == 64){
                                                                $vehInactiveReport.="
                                                                <td>".$heirarchyDetails[0]['regionname']."</td>
                                                                <td>".$heirarchyDetails[0]['zonename']."</td>
                                                                ";
                                                            }


                                                            $vehInactiveReport.="<td>".$vehicle->unitno."</td>
                                                            <td>".$vehicle->simcardno."</td>
                                                            <td>".$lastupdated."</td>
                                                            <td>".$vehicle->inactive_days."</td>
                                                            <td>".$vehicle->bucket_days."</td>
                                                            <td>".$vehicle->reason."</td>";

                                                            if($customer == 64){
                                                                $vehInactiveReport.="
                                                                <td>".$heirarchyDetails[0]['realname']."</td>
                                                                <td>".$heirarchyDetails[0]['username']."</td>
                                                                <td>".$heirarchyDetails[0]['phone']."</td>

                                                                <td>".$heirarchyDetails[0]['regionalUserName']."</td>
                                                                <td>".$heirarchyDetails[0]['regionalUserSAP']."</td>
                                                                <td>".$heirarchyDetails[0]['regionalUserSAPPhone']."</td>

                                                                <td>".$heirarchyDetails[0]['zonalUserName']."</td>
                                                                <td>".$heirarchyDetails[0]['zonalUserSAP']."</td>
                                                                <td>".$heirarchyDetails[0]['zonalUserSAPPhone']."</td>

                                                                ";
                                                            }


                                                            $vehInactiveReport.="</tr>";
                                    }
                                }
                        }
                    }
                } else {
                        $veh_class = " style='display:none' ";
                    }
                  if ($customer_details->use_warehouse) {
                        $vehicleManager = new VehicleManager($customer);
                        $um = new UserManager();
                        $warehouseCaption =  $um->store_custom_name($customer, 'Warehouse', 25);
                        $y=0;
                        $vehicles = $vehicleManager->get_all_vehicles_by_group($arrGroups, $isWarehouse = 1,$fetchSpecificVehicles,$user['userid']);
                        if (isset($vehicles) && !empty($vehicles)) {
                            foreach ($vehicles as $vehicle) {
                                $lastupdated = date(speedConstants::DEFAULT_DATETIME,strtotime($vehicle->lastupdated));
                                $grp_name = ($vehicle->groupname != null) ? $vehicle->groupname : 'Not Allocated';
                                if ($customer_details->temp_sensors >= 1) {
                                    if($vehicle->simcardno!=''){
                                        $y++;
                                        $vehInactiveWarehouseReport .="<tr>
                                                            <td>".$y."</td>
                                                            <td>".$vehicle->vehicleno."</td>
                                                            <td>".$vehicle->groupname."</td>
                                                            <td>".$vehicle->unitno."</td>
                                                            <td>".$vehicle->simcardno."</td>
                                                            <td>".$lastupdated."</td>
                                                            <td>".$vehicle->inactive_days."</td>
                                                            <td>".$vehicle->bucket_days."</td>
                                                            <td>".$vehicle->reason."</td>
                                                            </tr>";
                                    }
                                }
                            }
                            //echo $vehInactiveWarehouseReport;
                        }
                    } else {
                        $wh_class = " style='display:none' ";
                    }
                        if($vehInactiveReport == '') {
                            $vehInactiveReport = "<tr><td colspan='8'>Data Not Available</td></tr>";
                        }
                         if($vehInactiveWarehouseReport == '') {
                            $vehInactiveWarehouseReport = "<tr><td colspan='8    '>Data Not Available</td></tr>";
                        }
                        if($customer == 64){
                            $html = file_get_contents('../emailtemplates/customer/64/cronInactiveVehicleReport.html');
                        }else{
                            $html = file_get_contents('../emailtemplates/cronInactiveVehicleReport.html');
                        }

                        $placehoders['{{DATA_ROWS}}']           = $vehInactiveReport;
                        $placehoders['{{DATA_WAREHOUSE_ROWS}}'] = $vehInactiveWarehouseReport;
                        $placehoders['{{vehiclecaption}}'] = (isset($warehouseCaption) && $warehouseCaption!= '') ? $warehouseCaption : "Warehouse";
                         $placehoders['{{veh_class}}'] = $veh_class;
                         $placehoders['{{wh_class}}'] = $wh_class;
                        unset($vehInactiveReport);
                        unset($vehInactiveWarehouseReport);
                        unset($warehouseCaption);
                        unset($veh_class);
                        unset($wh_class);
                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        $message .= $html;
                        $attachmentFilePath = '';
                        $attachmentFileName = '';
                        $CCEmail = '';
                        if($customer == 664){
                            $CCEmail = speedConstants::FERRERO_664_CC_EMAIL;
                        }
                        $BCCEmail = '';
                        $isMailSent = sendMailUtil(array($user['email']), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
                       if (isset($isMailSent)) {
                            echo $message;
                        }
                }
            }//
        }
    }
}
//</editor-fold>
