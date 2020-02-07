<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
$reportId = speedConstants::REPORT_TEMPERATURE_COMPLIANCE;
$today = new DateTime();
$reportDate = new DateTime();
$reportDate = $reportDate->sub(new DateInterval('P1D'));
/*Get Report & Time Specific Users*/
$objReportUser = new stdClass();
$objReportUser->reportId = $reportId;
$objReportUser->reportTime = $today->format('H');
$objUserManager = new UserManager();
$users = $objUserManager->getUsersForReport($objReportUser);
$customerUserArray = cronCustomerUsers($users);
//prettyPrint($customerUserArray);
if (isset($customerUserArray) && !empty($customerUserArray)) {
    foreach ($customerUserArray as $customer => $customerDetails) {
        $objTemp = new stdClass();
        $objTemp->customerno = $customer;
        $objTemp->startDate = $reportDate->format(speedConstants::DATE_Ymd);
        $objTemp->todaysDate = $today->format(speedConstants::DATE_Ymd);
        $unitManager = new UnitManager($customer);
        $tempNonCompliance = $unitManager->getTemperatureNonComplianceData($objTemp);
        foreach ($customerDetails as $userDetails) {
            foreach ($userDetails as $user) {

                if ($user['email'] != '') {
                    $wh_class = '';
                    $tableRows = "";
                    $tableWhRows = "";
                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{REPORT_DATE}}'] = $reportDate->format(speedConstants::DEFAULT_DATE);
                    $subject = "Temperature Non Compliance For  " . $reportDate->format(speedConstants::DEFAULT_DATE);
                    $message = '';
                    $html = file_get_contents('../emailtemplates/cronTemperatureNonCompliance.html');

                    if (isset($tempNonCompliance) && !empty($tempNonCompliance)) {
                        $userGroups = $objUserManager->get_groups_fromuser($customer, $user['userid']);
                        $arrGroups = array();
                        if (isset($userGroups) && !empty($userGroups)) {
                            foreach ($userGroups as $group) {
                                $arrGroups[] = $group->groupid;
                            }
                        }
                        $vehicleManager = new VehicleManager($customer);
                        $vehicles = $vehicleManager->get_all_vehicles_by_group($arrGroups, $isWarehouse = 0);
                        $warehouses = $vehicleManager->get_all_vehicles_by_group($arrGroups, $isWarehouse = 1);

                        if (isset($vehicles) && !empty($vehicles)) {
                            foreach ($vehicles as $vehicle) {
                                $arrVehicles[] = $vehicle->vehicleid;
                            }
                            if (isset($arrVehicles) && !empty($arrVehicles)) {
                                $recordList = array();
                                foreach ($tempNonCompliance as $data) {
                                    if (in_array($data->vehicleid, $arrVehicles)) {
                                        $data->endTime = ($data->endTime == '0000-00-00 00:00:00') ? '' : date(speedConstants::DEFAULT_DATETIME, strtotime($data->endTime));
                                        $recordList[] = $data;

                                    }
                                }

                                if (isset($recordList) && !empty($recordList)) {
                                    $recordList = json_decode(json_encode($recordList), true);
                                    multiKeySortUtil($recordList, array('groupName', 'startTime'), array(false, true));

                                    foreach ($recordList as $data) {
                                        $tableRows .= "<tr>";
                                        $tableRows .= "<td>" . $data['vehicleno'] . "</td>";
                                        $tableRows .= "<td>" . $data['groupName'] . "</td>";
                                        $tableRows .= "<td>" . $data['temperature'] . "</td>";
                                        $tableRows .= "<td>" . date(speedConstants::DEFAULT_DATETIME, strtotime($data['startTime'])) . "</td>";
                                        $tableRows .= "<td>" . $data['endTime'] . "</td>";
                                        $tableRows .= "</tr>";
                                    }
                                }
                            }
                        }

                        if (isset($warehouses) && !empty($warehouses)) {
                            foreach ($warehouses as $vehicle) {
                                $arrWarehouse[] = $vehicle->vehicleid;
                            }
                            if (isset($arrWarehouse) && !empty($arrWarehouse)) {
                                $recordWarehouseList = array();
                                foreach ($tempNonCompliance as $data) {
                                    if (in_array($data->vehicleid, $arrWarehouse)) {
                                        $data->endTime = ($data->endTime == '0000-00-00 00:00:00') ? '' : date(speedConstants::DEFAULT_DATETIME, strtotime($data->endTime));
                                        $recordWarehouseList[] = $data;

                                    }
                                }
                                if (isset($recordWarehouseList) && !empty($recordWarehouseList)) {
                                    $recordWarehouseList = json_decode(json_encode($recordWarehouseList), true);
                                    multiKeySortUtil($recordWarehouseList, array('groupName', 'startTime'), array(false, true));

                                    foreach ($recordWarehouseList as $data) {
                                        $tableWhRows .= "<tr>";
                                        $tableWhRows .= "<td>" . $data['vehicleno'] . "</td>";
                                        $tableWhRows .= "<td>" . $data['groupName'] . "</td>";
                                        $tableWhRows .= "<td>" . $data['temperature'] . "</td>";
                                        $tableWhRows .= "<td>" . date(speedConstants::DEFAULT_DATETIME, strtotime($data['startTime'])) . "</td>";
                                        $tableWhRows .= "<td>" . $data['endTime'] . "</td>";
                                        $tableWhRows .= "</tr>";
                                    }
                                }
                            }
                        } else {
                            $wh_class = " style='display:none' ";
                        }
                    } else {
                        $tableRows = "<tr><td colspan='5'>Data Not Available</td></tr>";
                        $tableWhRows = "<tr><td colspan='5'>Data Not Available</td></tr>";
                        $wh_class = " style='display:none' ";
                    }

                    if ($tableRows != '' || $tableWhRows != '') {
                        if($tableRows == '') {
                            $tableRows = "<tr><td colspan='5'>Data Not Available</td></tr>";
                        }
                        if($tableWhRows == '') {
                            $tableWhRows = "<tr><td colspan='5'>Data Not Available</td></tr>";
                        }
                        $placehoders['{{DATA_ROWS}}'] = $tableRows;
                        $placehoders['{{DATA_WH_ROWS}}'] = $tableWhRows;
                        $placehoders['{{WH_CLASS}}'] = $wh_class;
                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        $message .= $html;
                        if ($message != '') {
                            $attachmentFilePath = '';
                            $attachmentFileName = '';
                            $CCEmail = '';
                            $BCCEmail = '';
                            $isMailSent = sendMailUtil(array($user['email']), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
                            if (isset($isMailSent)) {
                                echo $message;
                            }
                        }
                    }
                }
            }
        }
    }
}
