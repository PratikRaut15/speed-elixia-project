<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
$reportDate = new DateTime();
$startDate = new DateTime('first day of last month');
$endDate = new DateTime('last day of last month');
$customerno = 64;
$objUserManager = new UserManager();
$groupmanager = new GroupManager($customerno);
$vm = new VehicleManager($customerno);
$groupid = 0;
$userid = 0;
$useMaintenance = 1;
$useHierarchy = 1;
$users = $objUserManager->getusersforcustomerbygrp_hierarchy($customerno, $groupid, $useMaintenance, $useHierarchy);
//prettyPrint($users); die();
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="Customer-User Loop To Send Mail">
if (isset($users)) {
    $startDate = $startDate->format(speedConstants::DEFAULT_DATE);
    $endDate = $endDate->format(speedConstants::DEFAULT_DATE);
    $objInfoManager = new InformaticsManager($customerno, $userid);
    $data = $objInfoManager->getInformatics($startDate, $endDate);

    foreach ($users as $thisuser) {
        $userid = $thisuser->userid;
        if ($thisuser->roleid == 37 && $thisuser->email != '' && $thisuser->vehicle_movement_alert == 1) {

            $vehicles = array();
            $groupid = null;
            $groups = $objUserManager->get_groups_fromuser($customerno, $userid);
            if (isset($groups)) {
                $group_in = array();
                foreach ($groups as $group) {
                    if ($group->groupid != 0) {
                        $group_in[] = $group->groupid;
                    }
                }
                if (isset($group_in) && count($group_in) > 0) {
                    $group_veh = $vm->get_all_vehicles_by_group($group_in);
                    if ($group_veh != null) {
                        $vehicles = array_merge($vehicles, $group_veh);
                    }
                }
            }
            //prettyPrint($vehicles);
            if (isset($vehicles) && !empty($vehicles)) {
                foreach ($vehicles as $vehicle) {
                    foreach ($data as $key => $rowdata) {
                        if ($key == 'overspeedsumVehicleWise') {
                            foreach ($rowdata as $row) {
                                if ($vehicle->vehicleid == $row['vehicleid'] && $row['overspeed'] > 0) {
                                    //prettyPrint($row);
                                    $message = '';
                                    $reportPeriod = $startDate . " To " . $endDate;
                                    $subject = "Safety Advisory regarding use of Office Vehicle " . $row['vehicleno'] . " for Period  " . $reportPeriod;
                                    $placehoders['{{REALNAME}}'] = $thisuser->realname;
                                    $placehoders['{{PARENTUSER}}'] = $thisuser->parentuser;
                                    $placehoders['{{PARENTMOBILENO}}'] = $thisuser->parentphone;
                                    $placehoders['{{PARENTEMAIL}}'] = '';
                                    if ($thisuser->parentemail != '') {
                                        $placehoders['{{PARENTEMAIL}}'] = "<a href='mailto:" . $thisuser->parentemail . "'>" . $thisuser->parentemail . "</a>";
                                    }
                                    $placehoders['{{REPORT_DATE}}'] = $reportPeriod;
                                    $placehoders['{{CUSTOMER}}'] = $customerno;
                                    $placehoders['{{ENCODEKEY}}'] = sha1($thisuser->userkey);
                                    $placehoders['{{SUBJECT}}'] = $subject;
                                    $placehoders['{{VEHICLENUMBER}}'] = $row['vehicleno'];
                                    $placehoders['{{OVERSPEED}}'] = " High Speed for " . $row['overspeed'] . " times";
                                    $html = file_get_contents('../emailtemplates/cronAdvisory.html');
                                    foreach ($placehoders as $key => $val) {
                                        $html = str_replace($key, $val, $html);
                                    }
                                    $message .= $html;
                                    $attachmentFilePath = '';
                                    $attachmentFileName = '';
                                    $CCEmail = '';//$thisuser->parentemail;
                                    $BCCEmail = 'software@elixiatech.com';
                                    $mailid = 'anthony.malcom@mahindra.com';
                                    $isMailSent = sendMailUtil(array($mailid), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
                                    if (isset($isMailSent)) {
                                        echo $message;
                                    }
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }

    }
}
//</editor-fold>
