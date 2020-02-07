<?php
$RELATIVE_PATH_DOTS = "../../";
require "../../lib/system/utilities.php";
require '../../lib/autoload.php';
//require_once "../../lib/comman_function/reports_func.php";
$reportId = speedConstants::REPORT_REALTIME_DATA;
$today = new DateTime();
//$reportDate = $today->sub(new DateInterval('P1D'));
$date = $today->format(speedConstants::DEFAULT_DATE);
$serverPath = "http://www.speed.elixiatech.com";
//$serverPath = "http://localhost/speed";
$download = $serverPath . "/modules/download/report.php?q=";
//<editor-fold defaultstate="collapsed" desc="Report & Time Specific Users">
$objReportUser = new stdClass();
$objReportUser->reportId = $reportId;
$objReportUser->reportTime = $today->format('H');
$objUserManager = new UserManager();
$users = $objUserManager->getUsersForReport($objReportUser);
$customerUserArray = cronCustomerUsers($users);
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="Customer-User Loop To Send Mail">
$objCustomerManager = new CustomerManager();
$objUserManager = new UserManager();
if (isset($customerUserArray) && !empty($customerUserArray)) {
    foreach ($customerUserArray as $customer => $customerDetails) {
        $timezone = $objCustomerManager->timezone_name_cron(speedConstants::IST_TIMEZONE, $customer);
        date_default_timezone_set('' . $timezone . '');
        $customer_details = $objCustomerManager->getcustomerdetail_byid($customer);
        foreach ($customerDetails as $userDetails) {
            foreach ($userDetails as $user) {
                //print_r($user);die();
                if ($user['email'] != '') {
                    $message = "";
                    $tableRows = "";
                    $placehoders['{{REALNAME}}'] = $user['realname'];
                    $placehoders['{{REPORT_DATE}}'] = $today->format(speedConstants::DEFAULT_DATE);
                    $placehoders['{{CUSTOMER}}'] = $customer;
                    $placehoders['{{ENCODEKEY}}'] = sha1($user['userkey']);
                    $subject = "Realtime Data Report For  " . $today->format(speedConstants::DEFAULT_DATE);
                    $timestamp = strtotime($today->format(speedConstants::DEFAULT_DATE));
                    /* Summary Report URL */
                    $realtimeDataPdfUrl = $download . "realtimedata-pdf-" . $customer . "-" . $user['userkey'] . "-" . $timestamp;
                    $realtimeDataXlsUrl = $download . "realtimedata-xls-" . $customer . "-" . $user['userkey'] . "-" . $timestamp;
                    /* Report Links */
                    $realtimeDataPdf = '<a href="' . $realtimeDataPdfUrl . '" target="_blank">Download</a>';
                    $realtimeDataXls = '<a href="' . $realtimeDataXlsUrl . '" target="_blank">Download</a>';
                    $tableRows .= "<tr>";
                    $tableRows .= "<td>Realtime Data Report</td>";
                    $tableRows .= "<td>" . $realtimeDataPdf . "</td>";
                    $tableRows .= "<td>" . $realtimeDataXls . "</td>";
                    $tableRows .= "</tr>";
                    if ($tableRows != '') {
                        $userGroups = $objUserManager->get_groups_fromuser($customer, $user['userid']);
                        $arrGroups = array();
                        if (isset($userGroups) && !empty($userGroups)) {
                            foreach ($userGroups as $group) {
                                $arrGroups[] = $group->groupid;
                            }
                        }
                        $customer_details->userid = $user['userid'];
                        $customer_details->roleid = $user['roleid'];
                        $customer_details->userGroups = $arrGroups;
                        $vehicleData = getRealtimeDetails($customer_details);
                        if (isset($vehicleData) && !empty($vehicleData)) {
                            foreach ($vehicleData as $vehicle) {
                                $devicemanager = new DeviceManager($customer);
                                $devicemanager->insertRealtimeData($vehicle, $customer, $customer_details->userid, $today->format(speedConstants::DEFAULT_TIMESTAMP));
                            }
                        }
                        $html = file_get_contents('../emailtemplates/cronRealtimeDataReport.html');
                        $placehoders['{{DATA_ROWS}}'] = $tableRows;
                        foreach ($placehoders as $key => $val) {
                            $html = str_replace($key, $val, $html);
                        }
                        $message .= $html;
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
//</editor-fold>
function getRealtimeDetails($customer) {
    $finaloutput = array();
    $devicemanager = new DeviceManager($customer->customerno);
    $devices = $devicemanager->devicesForRealtimeDataReport($customer);

    $vehicles = array();
    $warehouses = array();
    $group_based_vehicle = array();
    if (isset($devices)) {
        foreach ($devices as $device) {
            $temp_conversion = new TempConversion();
            $temp_conversion->unit_type = $device->get_conversion;
            if (isset($device->use_humidity)) {
                $temp_conversion->use_humidity = $device->use_humidity;
            }
            if (isset($device->kind) && $device->kind = 'Warehouse') {
                $temp_conversion->switch_to = 3;
            }
            $output = new stdClass();
            $output->vehicleid = $device->vehicleid;
            $output->uid = $device->uid;
            $output->groupid = $device->groupid;
            $output->lastupdated = getduration($device->lastupdated);
            $output->status = getstatus($device->stoppage_flag, $device->stoppage_transit_time, $device->lastupdated_store);
            $output->driverid = $device->driverid;
            $output->location = location($device->devicelat, $device->devicelong, $customer->use_geolocation, $customer->customerno);
            $output->speed = $device->curspeed;
            $output->distance = round(getdistance_new($device->vehicleid, $customer->customerno), 2);
            if ($output->distance < 0) {
                $output->distance = round($output->distance, 2);
            }
            $output->power = $device->powercut;
            $output->ac_status = "";
            $output->door_status = "";
            $output->temperature1 = 0;
            $output->temperature2 = 0;
            $output->temperature3 = 0;
            $output->temperature4 = 0;
            $output->genset1 = "";
            $output->genset2 = "";
            $output->humidity = "";
            $output->is_buzzer = $device->is_buzzer;
            $output->is_mobiliser = $device->is_mobiliser;
            $output->is_freeze = $device->is_freeze;
            // AC Sensor
            if ($customer->use_ac_sensor == 1 || $customer->use_genset_sensor == 1) {
                if ($device->acsensor == 1) {
                    if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                        $digitaldiff = getdurationDigitalio($device->digitalioupdated, date('Y-m-d H:i:s', strtotime($device->lastupdated)));
                    }
                    if ($device->digitalio == 0) {
                        if ($device->isacopp == 0) {
                            if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                                $output->ac_status = "On since " . $digitaldiff;
                            } else {
                                $output->ac_status = "On ";
                            }
                        } else {
                            if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                                $output->ac_status = "Off since " . $digitaldiff;
                            } else {
                                $output->ac_status = "Off ";
                            }
                        }
                    } elseif ($device->isacopp == 0) {
                        if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                            $output->ac_status = "Off since " . $digitaldiff;
                        } else {
                            $output->ac_status = "Off ";
                        }
                    } else {
                        if ($device->digitalioupdated != '0000-00-00 00:00:00') {
                            $output->ac_status = "On since " . $digitaldiff;
                        } else {
                            $output->ac_status = "On ";
                        }
                    }
                } else {
                    $output->ac_status = "Not Active";
                }
            }
            if ($customer->use_door_sensor) {
                $digitaldiff = 'Not Active';
                if ($device->door_digitalioupdated != '0000-00-00 00:00:00') {
                    $digitaldiff = getdurationDigitalio($device->door_digitalioupdated, date('Y-m-d H:i:s', strtotime($device->lastupdated)));
                    $door_status = door_status($device->is_door_opp, $device->digitalio);
                    if (!$door_status) {
                        $digitaldiff = "Open since $digitaldiff";
                    } else {
                        $digitaldiff = "Closed since $digitaldiff";
                    }
                }
                $output->door_status = $digitaldiff;
            }
            // Temperature Sensor
            $temperature1 = $temperature2 = $temperature3 = $temperature4 = speedConstants::TEMP_WARNING_TEXT;
            switch ($customer->temp_sensors) {
                case 4:
                    if ($device->tempsen4 != 0) {
                        $s = "analog" . $device->tempsen4;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp = getTempUtil($temp_conversion);
                            if ($temp != '' && ($device->temp4_min != 0 && $device->temp4_max != 0) && ($temp < $device->temp4_min || $temp > $device->temp4_max)) {
                                $tdclass_temp4 = "off";
                            }
                            if ($temp != 0) {
                                $temperature4 = $temp . speedConstants::TEMP_DEGREE;
                            }
                        }
                    } else {
                        $temperature4 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $output->temperature4 = $temperature4;
                case 3:
                    if ($device->tempsen3 != 0) {
                        $s = "analog" . $device->tempsen3;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp = getTempUtil($temp_conversion);
                            if ($temp != '' && ($device->temp3_min != 0 && $device->temp3_max != 0) && ($temp < $device->temp3_min || $temp > $device->temp3_max)) {
                                $tdclass_temp3 = "off";
                            }
                            if ($temp != 0) {
                                $temp3 = $temp . speedConstants::TEMP_DEGREE;
                            }
                        }
                    } else {
                        $temp3 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $output->temperature3 = $temp3;
                case 2:
                    if ($device->tempsen2 != 0) {
                        $s = "analog" . $device->tempsen2;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp = getTempUtil($temp_conversion);
                            if ($temp != '' && ($device->temp2_min != 0 && $device->temp2_max != 0) && ($temp < $device->temp2_min || $temp > $device->temp2_max)) {
                                $tdclass_temp2 = "off";
                            }
                            if ($temp != 0) {
                                $temperature2 = $temp . speedConstants::TEMP_DEGREE;
                            }
                        }
                    } else {
                        $temperature2 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $output->temperature2 = $temperature2;
                case 1:
                    if ($device->tempsen1 != 0) {
                        if ($device->temp1_mute == 1) {
                        }
                        $s = "analog" . $device->tempsen1;
                        if ($device->$s != 0) {
                            $temp_conversion->rawtemp = $device->$s;
                            $temp = getTempUtil($temp_conversion);
                            if ($temp != '' && ($device->temp1_min != 0 && $device->temp1_max != 0) && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
                                $tdclass_temp1 = "off";
                            }
                            if ($temp != 0) {
                                $temperature1 = $temp . speedConstants::TEMP_DEGREE;
                            }
                        }
                    } else {
                        $temperature1 = speedConstants::TEMP_NOTACTIVE;
                    }
                    $output->temperature1 = $temperature1;
            }

            /* Double genset */
            if ($customer->use_extradigital == 1) {
                $category_array = Array();
                $digital = Array();
                $digital2 = Array();
                $category = (int) $device->digitalio;
                $binarycategory = sprintf("%08s", DecBin($category));
                for ($shifter = 1; $shifter <= 127; $shifter = $shifter << 1) {
                    $binaryshifter = sprintf("%08s", DecBin($shifter));
                    if ($category & $shifter) {
                        $category_array[] = $shifter;
                    }
                }
                $diffextra1 = '';
                $diffextra2 = '';
                if ($device->extra_digitalioupdated != '0000-00-00 00:00:00') {
                    $diffextra1 = "since " . getduration_digitalio($device->extra_digitalioupdated, Datetime::createFromFormat('Y-m-d H:i:s',$device->lastupdated)->format('Y-m-d H:i:s'));
                }
                if ($device->extra2_digitalioupdated != '0000-00-00 00:00:00') {
                    $diffextra2 = "since " . getduration_digitalio($device->extra2_digitalioupdated, Datetime::createFromFormat('Y-m-d H:i:s',$device->lastupdated)->format('Y-m-d H:i:s'));
                }
                if ($device->extra_digital == '' || $device->extra_digital == '0') {
                    $output->genset1 = '';
                    $output->genset2 = '';
                } else {
                    if ($device->extra_digital >= 1) {
                        $output->genset1 = '';
                        if (in_array(1, $category_array)) {
                            $digital[] = "On  $diffextra1";
                        } else {
                            $digital[] = "Off $diffextra1";
                        }
                        if ($device->genset1 != '' && $device->transmitter1 != '') {
                            if ($device->setcom == 1) {
                                $device->genset1 = 'Syncing';
                            }
                            $output->genset1.=$device->genset1 . "|" . $device->transmitter1;
                        }
                        $output->genset1.=implode(',', $digital);
                    } else {
                        $output->genset1 = 'Not Active';
                    }
                    if ($device->extra_digital == 2) {
                        $output->genset2 = '';
                        if (in_array(2, $category_array)) {
                            $digital2[] = "On $diffextra2";
                        } else {
                            $digital2[] = "Off $diffextra2";
                        }
                        if ($device->genset2 != '' && $device->transmitter2 != '') {
                            if ($device->setcom == 1) {
                                $device->genset2 = 'Syncing';
                            }
                            $output->genset2.=$device->genset2 . "|" . $device->transmitter2;
                        }
                        $output->genset2.=implode(',', $digital2);
                    } else {
                        $output->genset2 = 'Not Active';
                    }
                }
            }

            /* Humidity */
            $humidity = '-';
            if ($customer->use_humidity == 1) {
                $s = "analog" . $device->humidity;
                if ($device->humidity != 0 && $device->$s != 0) {
                    $temp_conversion->rawtemp = $device->$s;
                    $humidity = getTempUtil($temp_conversion);
                }
                $output->humidity = $humidity . " %";
            }
            $finaloutput[] = $output;
        }
    }
    return $finaloutput;
}
function getduration($StartTime) {
    $EndTime = date('Y-m-d H:i:s');
    $idleduration = strtotime($EndTime) - strtotime($StartTime);
    $years = floor($idleduration / (365 * 60 * 60 * 24));
    $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
    $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
    if ($years >= '1' || $months >= '1') {
        $diff = date('d-m-Y', strtotime($StartTime));
    } elseif ($days > 0) {
        $diff = $days . ' Days ' . $hours . ' hrs ago';
    } elseif ($hours > 0) {
        $diff = $hours . ' hrs and ' . $minutes . ' mins ago';
    } elseif ($minutes > 0) {
        $diff = $minutes . ' mins ago';
    } else {
        $seconds = strtotime($EndTime) - strtotime($StartTime);
        $diff = $seconds . ' sec ago';
    }
    return $diff;
}
function getstatus($stoppage_flag, $stoppage_transit_time, $lastupdated_store) {
    $ServerIST_less1 = new DateTime();
    $ServerIST_less1->modify('-60 minutes');
    $status = '';
    $lastupdated = new DateTime($lastupdated_store);
    if ($lastupdated < $ServerIST_less1) {
        $status = "Inactive";
    } else {
        $diff = getduration($stoppage_transit_time);
        if ($stoppage_flag == '0') {
            $status = "Idle since " . $diff;
        } else {
            $status .= "Running since " . $diff;
        }
    }
    return $status;
}
function location($lat, $long, $usegeolocation, $customerno) {
    $address = NULL;
    $GeoCoder_Obj = new GeoCoder($customerno);
    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
    return $address;
}

function getdurationDigitalio($StartTime, $EndTime) {

    $idleduration = strtotime($EndTime) - strtotime($StartTime);
    $years = floor($idleduration / (365 * 60 * 60 * 24));
    $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
    $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
    if ($years >= '1' || $months >= '1') {
        $diff = date('d-m-Y', strtotime($StartTime));
    } else if ($days > 0) {
        $diff = $days . ' days ' . $hours . ' hrs ';
    } else if ($hours > 0) {
        $diff = $hours . ' hrs and ' . $minutes . ' mins ';
    } else {
        $diff = $minutes . ' mins ';
    }
    return $diff;
}
