<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once "../../lib/system/utilities.php";
require_once '../../lib/bo/CronManager.php';
require_once '../../lib/bo/ComQueueManager.php';
require_once '../../lib/bo/UnitManager.php';
echo "<br/> Cron Start On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
$cm = new CronManager();


$devicesAdv = $cm->getalldevicesforAdvancetempsensor();

if (!empty($devicesAdv)) {
    foreach ($devicesAdv as $advDevice) {
        $temp1_below_include = TRUE;
        $temp1_bet_include = TRUE;
        $temp1_above_include = TRUE;
        $temp2_below_include = TRUE;
        $temp2_bet_include = TRUE;
        $temp2_above_include = TRUE;
        $temp3_below_include = TRUE;
        $temp3_bet_include = TRUE;
        $temp3_above_include = TRUE;
        $temp4_below_include = TRUE;
        $temp4_bet_include = TRUE;
        $temp4_above_include = TRUE;

        $intervalObj = new stdClass();
        $intervalObj->vehicleid = $advDevice->vehicleid;
        $intervalObj->lastupdated = $advDevice->lastupdated;
        $intervalObj->customerno = $advDevice->customerno;
        $intervalObj->userid = $advDevice->userid;
        // <editor-fold defaultstate="collapsed" desc="For Advance range of SMS">
        if (isset($advDevice->temp1_min_sms)) {
            $intervalObj->type = 1;
            $cust_array = explode(',', speedConstants::TEMP_CONFLICT_BUZZER_CUSTOMER);
            $Is_Buzzer_Set = FALSE;
            if (in_array($advDevice->customerno, $cust_array)) {
                $Is_Buzzer_Set = TRUE;
            }
            if ($advDevice->status != 'H' || $advDevice->status != 'F') {
                $um = new UnitManager($advDevice->customerno);
                $temp_coversion = new TempConversion();
                $temp_coversion->unit_type = $advDevice->get_conversion;
                $temp_coversion->use_humidity = $advDevice->use_humidity;
                $temp_coversion->switch_to = 0;
                if ($advDevice->kind == 'Warehouse') {
                    $temp_coversion->switch_to = 3;
                }
                $timeFirst = strtotime($advDevice->lastupdated);
                $temp_interval = $cm->getTempInterval($advDevice->customerno);

                if ($advDevice->temp_sensors == 1) {
                    $differenceInMin = 0;
                    if ($advDevice->temp1_intv_sms != '0000-00-00 00:00:00') {
                        $timeSecond = strtotime($advDevice->temp1_intv_sms);
                        $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                    }
                    $tempcheck = '0';
                    $s = "analog" . $advDevice->tempsen1;
                    if ($advDevice->tempsen1 != 0 && $advDevice->$s != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s;
                        $tempcheck = checktemp($temp_coversion, $advDevice->temp1_min_sms, $advDevice->temp1_max_sms, $advDevice->temp1_mute);
                        $temp = getTempUtil($temp_coversion);
                    } else {
                        $tempcheck = '-';
                    }

                    $intervalObj->tempSensor = 1;
                    if (($tempcheck == '1,1' || $tempcheck == '-1,0')) {
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($tempcheck == '1,1') {
                            $cvo->message = $advDevice->vehicleno . " - Temperature is above " . $advDevice->temp1_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temp . "]";
                        } elseif ($tempcheck == '-1,0') {
                            $cvo->message = $advDevice->vehicleno . " - Temperature is below " . $advDevice->temp1_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temp . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp1_intv_sms == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktempoff($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp1_intv_sms != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, 'Temperature is above') !== false) {
                                        $temp1_above_include = FALSE;
                                    } elseif (strpos($cvo->message, 'Temperature is below') !== false) {
                                        $temp1_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    if ($Is_Buzzer_Set) {
                                        $command = new stdClass();
                                        $command->uid = $advDevice->uid;
                                        $command->command = 'BUZZ=10';
                                        $um->setCommand($command);
                                    }
                                    $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                                    $intervalObj->switch = 0;
                                    $cm->markAdvTemperatureInterval($intervalObj);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp_status_sms == 0) {
                                if (strpos($cvo->message, 'Temperature is above') !== false) {
                                    $temp1_above_include = FALSE;
                                } elseif (strpos($cvo->message, 'Temperature is below') !== false) {
                                    $temp1_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            }
                        }
                    } elseif ($tempcheck == '0' && $advDevice->temp_status_sms == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - Temperature is between  " . $advDevice->temp1_min_sms . "&deg; C and " . $advDevice->temp1_max_sms . "&deg; C[" . $temp . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '') {
                            $temp1_bet_include = FALSE;
                            $cqm->InsertQ($cvo);
                            $cm->marktempoff($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            if ($temp_interval > 0) {
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        }
                    } elseif ($tempcheck == '0' && $advDevice->temp_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }
                }

                // Temperature Sensor 2
                if ($advDevice->temp_sensors == 2) {
                    $temp1 = '0';
                    $temp2 = '0';
                    $s1 = "analog" . $advDevice->tempsen1;
                    if ($advDevice->tempsen1 != 0 && $advDevice->$s1 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s1;
                        $temp1 = checktemp($temp_coversion, $advDevice->temp1_min_sms, $advDevice->temp1_max_sms, $advDevice->temp1_mute);
                        $temps1 = getTempUtil($temp_coversion);
                    } else {
                        $temp1 = '-';
                    }
                    $s2 = "analog" . $advDevice->tempsen2;
                    if ($advDevice->tempsen2 != 0 && $advDevice->$s2 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s2;
                        $temp2 = checktemp($temp_coversion, $advDevice->temp2_min_sms, $advDevice->temp2_max_sms, $advDevice->temp2_mute);
                        $temps2 = getTempUtil($temp_coversion);
                    } else {
                        $temp2 = '-';
                    }
                    $intervalObj->tempSensor = 1;
                    if (($temp1 == '1,1' || $temp1 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp1_intv_sms != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp1_intv_sms);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp1 == '1,1') {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 1 is above " . $advDevice->temp1_max_sms . "&deg; C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        } elseif ($temp1 == '-1,0') {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 1 is below " . $advDevice->temp1_min_sms . "&deg; C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp1_intv_sms == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktempoff($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp1_intv_sms != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, 'Temperature 1 is above') !== false) {
                                        $temp1_above_include = FALSE;
                                    } elseif (strpos($cvo->message, 'Temperature 1 is below') !== false) {
                                        $temp1_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    if ($Is_Buzzer_Set) {
                                        $command = new stdClass();
                                        $command->uid = $advDevice->uid;
                                        $command->command = 'BUZZ=10';
                                        $um->setCommand($command);
                                    }
                                    $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                                    $intervalObj->switch = 0;
                                    $cm->markAdvTemperatureInterval($intervalObj);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp_status_sms == 0) {
                                if (strpos($cvo->message, 'Temperature 1 is above') !== false) {
                                    $temp1_above_include = FALSE;
                                } elseif (strpos($cvo->message, 'Temperature 1 is below') !== false) {
                                    $temp1_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            }
                        }
                    } elseif ($temp1 == '0' && $advDevice->temp_status_sms == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - Temperature 1 is between " . $advDevice->temp1_min_sms . " &deg;C and " . $advDevice->temp1_max_sms . "&deg; C[" . $temps1 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '') {
                            $temp1_bet_include = FALSE;
                            $cqm->InsertQ($cvo);
                            $cm->marktempoff($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            if ($temp_interval > 0) {
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        }
                    } elseif ($temp1 == '0' && $advDevice->temp_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }

                    $intervalObj->tempSensor = 2;
                    if (($temp2 == '1,1' || $temp2 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp2_intv_sms != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp2_intv_sms);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 2;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp2 == '1,1') {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 2 is above " . $advDevice->temp2_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        } elseif ($temp2 == '-1,0') {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 2 is below " . $advDevice->temp2_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp2_intv_sms == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktemp2off($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp2_intv_sms != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, 'Temperature 2 is above') !== false) {
                                        $temp2_above_include = FALSE;
                                    } elseif (strpos($cvo->message, 'Temperature 2 is below') !== false) {
                                        $temp2_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    if ($Is_Buzzer_Set) {
                                        $command = new stdClass();
                                        $command->uid = $advDevice->uid;
                                        $command->command = 'BUZZ=10';
                                        $um->setCommand($command);
                                    }
                                    $cm->marktemp2on($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                                    $intervalObj->switch = 0;
                                    $cm->markAdvTemperatureInterval($intervalObj);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp2_status_sms == 0) {
                                if (strpos($cvo->message, 'Temperature 2 is above') !== false) {
                                    $temp2_above_include = FALSE;
                                } elseif (strpos($cvo->message, 'Temperature 2 is below') !== false) {
                                    $temp2_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                $cm->marktemp2on($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            }
                        }
                    } elseif ($temp2 == '0' && $advDevice->temp2_status_sms == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - Temperature 2 is between " . $advDevice->temp2_min_sms . "&deg;C and " . $advDevice->temp2_max_sms . "&deg;C[" . $temps2 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 2;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '') {
                            $temp2_bet_include = FALSE;
                            $cqm->InsertQ($cvo);
                            $cm->marktemp2off($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            if ($temp_interval > 0) {
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        }
                    } elseif ($temp2 == '0' && $advDevice->temp2_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }
                }

                if ($advDevice->temp_sensors == 3) {
                    $temp1 = '0';
                    $temp2 = '0';
                    $temp3 = '0';
                    $s1 = "analog" . $advDevice->tempsen1;
                    if ($advDevice->tempsen1 != 0 && $advDevice->$s1 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s1;
                        $temp1 = checktemp($temp_coversion, $advDevice->temp1_min_sms, $advDevice->temp1_max_sms, $advDevice->temp1_mute);
                        $temps1 = getTempUtil($temp_coversion);
                    } else {
                        $temp1 = '-';
                    }
                    $s2 = "analog" . $advDevice->tempsen2;
                    if ($advDevice->tempsen2 != 0 && $advDevice->$s2 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s2;
                        $temp2 = checktemp($temp_coversion, $advDevice->temp2_min_sms, $advDevice->temp2_max_sms, $advDevice->temp2_mute);
                        $temps2 = getTempUtil($temp_coversion);
                    } else {
                        $temp2 = '-';
                    }
                    $s3 = "analog" . $advDevice->tempsen3;
                    if ($advDevice->tempsen3 != 0 && $advDevice->$s3 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s3;
                        $temp3 = checktemp($temp_coversion, $advDevice->temp3_min_sms, $advDevice->temp3_max_sms, $advDevice->temp3_mute);
                        $temps3 = getTempUtil($temp_coversion);
                    } else {
                        $temp3 = '-';
                    }
                    $intervalObj->tempSensor = 1;
                    if (($temp1 == '1,1' || $temp1 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp1_intv_sms != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp1_intv_sms);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp1 == '1,1') {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 1 is above " . $advDevice->temp1_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        } elseif ($temp1 == '-1,0') {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 1 is below " . $advDevice->temp1_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp1_intv_sms == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktempoff($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp1_intv_sms != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, 'Temperature 1 is above') !== false) {
                                        $temp1_above_include = FALSE;
                                    } elseif (strpos($cvo->message, 'Temperature 1 is below') !== false) {
                                        $temp1_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                                    $intervalObj->switch = 0;
                                    $cm->markAdvTemperatureInterval($intervalObj);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp_status_sms == 0) {
                                if (strpos($cvo->message, 'Temperature 1 is above') !== false) {
                                    $temp1_above_include = FALSE;
                                } elseif (strpos($cvo->message, 'Temperature 1 is below') !== false) {
                                    $temp1_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $advDevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            }
                        }
                    } elseif ($temp1 == '0' && $advDevice->temp_status_sms == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - Temperature 1 is between " . $advDevice->temp1_min_sms . "&deg;C and " . $advDevice->temp1_max_sms . "&deg;C[" . $temps1 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '') {
                            $temp1_bet_include = FALSE;
                            $cqm->InsertQ($cvo);
                            $cm->marktempoff($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            if ($temp_interval > 0) {
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        }
                    } elseif ($temp1 == '0' && $advDevice->temp_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }

                    $intervalObj->tempSensor = 2;
                    if (($temp2 == '1,1' || $temp2 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp2_intv_sms != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp2_intv_sms);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 2;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp2 == '1,1') {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 2 is above " . $advDevice->temp2_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        } elseif ($temp2 == '-1,0') {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 2 is below " . $advDevice->temp2_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp2_intv_sms == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktemp2off($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp2_intv_sms != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, 'Temperature 2 is above') !== false) {
                                        $temp2_above_include = FALSE;
                                    } elseif (strpos($cvo->message, 'Temperature 2 is below') !== false) {
                                        $temp2_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    $cm->marktemp2on($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                                    $intervalObj->switch = 0;
                                    $cm->markAdvTemperatureInterval($intervalObj);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp2_status_sms == 0) {
                                if (strpos($cvo->message, 'Temperature 2 is above') !== false) {
                                    $temp2_above_include = FALSE;
                                } elseif (strpos($cvo->message, 'Temperature 2 is below') !== false) {
                                    $temp2_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $advDevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp2on($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            }
                        }
                    } elseif ($temp2 == '0' && $advDevice->temp2_status_sms == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - Temperature 2 is between " . $advDevice->temp2_min_sms . "&deg;C and " . $advDevice->temp2_max_sms . "&deg;C[" . $temps2 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 2;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '') {
                            $temp2_bet_include = FALSE;
                            $cqm->InsertQ($cvo);
                            $cm->marktemp2off($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            if ($temp_interval > 0) {
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        }
                    } elseif ($temp2 == '0' && $advDevice->temp2_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }

                    $intervalObj->tempSensor = 3;
                    if (($temp3 == '1,1' || $temp3 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp3_intv_sms != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp3_intv_sms);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 3;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp3 == '1,1') {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 3 is above " . $advDevice->temp3_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        } elseif ($temp3 == '-1,0') {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 3 is below " . $advDevice->temp3_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp3_intv_sms == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktemp3off($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp3_intv_sms != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, 'Temperature 3 is above') !== false) {
                                        $temp3_above_include = FALSE;
                                    } elseif (strpos($cvo->message, 'Temperature 3 is below') !== false) {
                                        $temp3_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    $cm->marktemp3on($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                                    $intervalObj->switch = 0;
                                    $cm->markAdvTemperatureInterval($intervalObj);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp3_status_sms == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $advDevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp3on($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            }
                        }
                    } elseif ($temp3 == '0' && $advDevice->temp3_status_sms == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - Temperature 3 is between " . $advDevice->temp3_min_sms . "&deg;C and " . $advDevice->temp3_max_sms . "&deg;C[" . $temps3 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 3;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '') {
                            $temp3_bet_include = FALSE;
                            $cqm->InsertQ($cvo);
                            $cm->marktemp3off($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            if ($temp_interval > 0) {
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        }
                    } elseif ($temp3 == '0' && $advDevice->temp3_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }
                }

                if ($advDevice->temp_sensors == 4) {
                    $temp1 = '0';
                    $temp2 = '0';
                    $temp3 = '0';
                    $temp4 = '0';
                    $t1 = $cm->getNameForTempCron($advDevice->n1, $advDevice->customerno);
                    if ($t1 == '') {
                        $t1 = 'Temperature 1';
                    }
                    $t2 = $cm->getNameForTempCron($advDevice->n2, $advDevice->customerno);
                    if ($t2 == '') {
                        $t2 = 'Temperature 2';
                    }
                    $t3 = $cm->getNameForTempCron($advDevice->n3, $advDevice->customerno);
                    if ($t3 == '') {
                        $t3 = 'Temperature 3';
                    }
                    $t4 = $cm->getNameForTempCron($advDevice->n4, $advDevice->customerno);
                    if ($t4 == '') {
                        $t4 = 'Temperature 4';
                    }
                    $s1 = "analog" . $advDevice->tempsen1;
                    if ($advDevice->tempsen1 != 0 && $advDevice->$s1 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s1;
                        $temp1 = checktemp($temp_coversion, $advDevice->temp1_min_sms, $advDevice->temp1_max_sms, $advDevice->temp1_mute);
                        $temps1 = getTempUtil($temp_coversion);
                    } else {
                        $temp1 = '-';
                    }
                    $s2 = "analog" . $advDevice->tempsen2;
                    if ($advDevice->tempsen2 != 0 && $advDevice->$s2 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s2;
                        $temp2 = checktemp($temp_coversion, $advDevice->temp2_min_sms, $advDevice->temp2_max_sms, $advDevice->temp2_mute);
                        $temps2 = getTempUtil($temp_coversion);
                    } else {
                        $temp2 = '-';
                    }
                    $s3 = "analog" . $advDevice->tempsen3;
                    if ($advDevice->tempsen3 != 0 && $advDevice->$s3 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s3;
                        $temp3 = checktemp($temp_coversion, $advDevice->temp3_min_sms, $advDevice->temp3_max_sms, $advDevice->temp3_mute);
                        $temps3 = getTempUtil($temp_coversion);
                    } else {
                        $temp3 = '-';
                    }
                    $s4 = "analog" . $advDevice->tempsen4;
                    if ($advDevice->tempsen4 != 0 && $advDevice->$s4 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s4;
                        $temp4 = checktemp($temp_coversion, $advDevice->temp4_min_sms, $advDevice->temp4_max_sms, $advDevice->temp4_mute);
                        $temps4 = getTempUtil($temp_coversion);
                    } else {
                        $temp4 = '-';
                    }
                    $intervalObj->tempSensor = 1;
                    if (($temp1 == '1,1' || $temp1 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp1_intv_sms != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp1_intv_sms);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp1 == '1,1') {
                            $cvo->message = $advDevice->vehicleno . " - " . $t1 . " is above " . $advDevice->temp1_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        } elseif ($temp1 == '-1,0') {
                            $cvo->message = $advDevice->vehicleno . " - " . $t1 . " is below " . $advDevice->temp1_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp1_intv_sms == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktempoff($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp1_intv_sms != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, $t1 . ' is above') !== false) {
                                        $temp1_above_include = FALSE;
                                    } elseif (strpos($cvo->message, $t1 . ' is below') !== false) {
                                        $temp1_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                                    $intervalObj->switch = 0;
                                    $cm->markAdvTemperatureInterval($intervalObj);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp_status_sms == 0) {
                                if (strpos($cvo->message, $t1 . ' is above') !== false) {
                                    $temp1_above_include = FALSE;
                                } elseif (strpos($cvo->message, $t1 . ' is below') !== false) {
                                    $temp1_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $advDevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            }
                        }
                    } elseif ($temp1 == '0' && $advDevice->temp_status_sms == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - " . $t1 . " is between " . $advDevice->temp1_min_sms . "&deg;C and " . $advDevice->temp1_max_sms . "&deg;C[" . $temps1 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '') {
                            $temp1_bet_include = FALSE;
                            $cqm->InsertQ($cvo);
                            $cm->marktempoff($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            if ($temp_interval > 0) {
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        }
                    } elseif ($temp1 == '0' && $advDevice->temp_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }

                    $intervalObj->tempSensor = 2;
                    if (($temp2 == '1,1' || $temp2 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp2_intv_sms != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp2_intv_sms);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 2;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp2 == '1,1') {
                            $cvo->message = $advDevice->vehicleno . " - " . $t2 . " is above " . $advDevice->temp2_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        } elseif ($temp2 == '-1,0') {
                            $cvo->message = $advDevice->vehicleno . " - " . $t2 . " is below " . $advDevice->temp2_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp2_intv_sms == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktemp2off($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp2_intv_sms != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, $t2 . ' is above') !== false) {
                                        $temp2_above_include = FALSE;
                                    } elseif (strpos($cvo->message, $t2 . ' is below') !== false) {
                                        $temp2_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    $cm->marktemp2on($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                                    $intervalObj->switch = 0;
                                    $cm->markAdvTemperatureInterval($intervalObj);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp2_status_sms == 0) {
                                if (strpos($cvo->message, $t2 . ' is above') !== false) {
                                    $temp2_above_include = FALSE;
                                } elseif (strpos($cvo->message, $t2 . ' is below') !== false) {
                                    $temp2_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $advDevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp2on($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            }
                        }
                    } elseif ($temp2 == '0' && $advDevice->temp2_status_sms == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - " . $t2 . " is between " . $advDevice->temp2_min_sms . "&deg;C and " . $advDevice->temp2_max_sms . "&deg;C[" . $temps2 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 2;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '') {
                            $temp2_bet_include = FALSE;
                            $cqm->InsertQ($cvo);
                            $cm->marktemp2off($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            if ($temp_interval > 0) {
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        }
                    } elseif ($temp2 == '0' && $advDevice->temp2_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }

                    $intervalObj->tempSensor = 3;
                    if (($temp3 == '1,1' || $temp3 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp3_intv_sms != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp3_intv_sms);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 3;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp3 == '1,1') {
                            $cvo->message = $advDevice->vehicleno . " - " . $t3 . " is above " . $advDevice->temp3_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        } elseif ($temp3 == '-1,0') {
                            $cvo->message = $advDevice->vehicleno . " - " . $t3 . " is below " . $advDevice->temp3_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp3_intv_sms == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktemp3off($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp3_intv_sms != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, $t3 . ' is above') !== false) {
                                        $temp3_above_include = FALSE;
                                    } elseif (strpos($cvo->message, $t3 . ' is below') !== false) {
                                        $temp3_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    $cm->marktemp3on($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                                    $intervalObj->switch = 0;
                                    $cm->markAdvTemperatureInterval($intervalObj);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp3_status_sms == 0) {
                                if (strpos($cvo->message, $t3 . ' is above') !== false) {
                                    $temp3_above_include = FALSE;
                                } elseif (strpos($cvo->message, $t3 . ' is below') !== false) {
                                    $temp3_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $advDevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp3on($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            }
                        }
                    } elseif ($temp3 == '0' && $advDevice->temp3_status_sms == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - " . $t3 . " is between " . $advDevice->temp3_min_sms . "&deg;C and " . $advDevice->temp3_max_sms . "&deg;C[" . $temps3 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 3;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '') {
                            $temp3_bet_include = FALSE;
                            $cqm->InsertQ($cvo);
                            $cm->marktemp3off($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            if ($temp_interval > 0) {
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        }
                    } elseif ($temp3 == '0' && $advDevice->temp3_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }

                    $intervalObj->tempSensor = 4;
                    if (($temp4 == '1,1' || $temp4 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp4_intv_sms != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp4_intv_sms);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 4;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp4 == '1,1') {
                            $cvo->message = $advDevice->vehicleno . " - " . $t4 . " is above " . $advDevice->temp4_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps4 . "]";
                        } elseif ($temp4 == '-1,0') {
                            $cvo->message = $advDevice->vehicleno . " - " . $t4 . " is below " . $advDevice->temp4_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps4 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp4_intv_sms == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktemp4off($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp4_intv_sms != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, $t4 . ' is above') !== false) {
                                        $temp4_above_include = FALSE;
                                    } elseif (strpos($cvo->message, $t4 . ' is below') !== false) {
                                        $temp4_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    $cm->marktemp4on($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                                    $intervalObj->switch = 0;
                                    $cm->markAdvTemperatureInterval($intervalObj);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp4_status_sms == 0) {
                                if (strpos($cvo->message, $t4 . ' is above') !== false) {
                                    $temp4_above_include = FALSE;
                                } elseif (strpos($cvo->message, $t4 . ' is below') !== false) {
                                    $temp4_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $advDevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp4on($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            }
                        }
                    } elseif ($temp4 == '0' && $advDevice->temp4_status_sms == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - " . $t4 . " is between " . $advDevice->temp4_min_sms . "&deg;C and " . $advDevice->temp4_max_sms . "&deg;C[" . $temps4 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 4;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '') {
                            $temp4_bet_include = FALSE;
                            $cqm->InsertQ($cvo);
                            $cm->marktemp4off($advDevice->vehicleid, $advDevice->customerno, '_sms', $advDevice->userid);
                            if ($temp_interval > 0) {
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        }
                    } elseif ($temp4 == '0' && $advDevice->temp4_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }
                }
            }
        }
        //</editor-fold>
        // <editor-fold defaultstate="collapsed" desc="For Advance range of Email">
        if (isset($advDevice->temp1_min_email)) {
            $intervalObj->type = 2;
            $cust_array = explode(',', speedConstants::TEMP_CONFLICT_BUZZER_CUSTOMER);
            $Is_Buzzer_Set = FALSE;
            if (in_array($advDevice->customerno, $cust_array)) {
                $Is_Buzzer_Set = TRUE;
            }
            if ($advDevice->status != 'H' || $advDevice->status != 'F') {
                $um = new UnitManager($advDevice->customerno);
                $temp_coversion = new TempConversion();
                $temp_coversion->unit_type = $advDevice->get_conversion;
                $temp_coversion->use_humidity = $advDevice->use_humidity;
                $temp_coversion->switch_to = 0;
                if ($advDevice->kind == 'Warehouse') {
                    $temp_coversion->switch_to = 3;
                }
                $timeFirst = strtotime($advDevice->lastupdated);
                $temp_interval = $cm->getTempInterval($advDevice->customerno);

                if ($advDevice->temp_sensors == 1) {
                    $differenceInMin = 0;
                    if ($advDevice->temp1_intv_email != '0000-00-00 00:00:00') {
                        $timeSecond = strtotime($advDevice->temp1_intv_email);
                        $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                    }
                    $tempcheck = '0';
                    $s = "analog" . $advDevice->tempsen1;
                    if ($advDevice->tempsen1 != 0 && $advDevice->$s != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s;
                        $tempcheck = checktemp($temp_coversion, $advDevice->temp1_min_email, $advDevice->temp1_max_email, $advDevice->temp1_mute);
                        $temp = getTempUtil($temp_coversion);
                    } else {
                        $tempcheck = '-';
                    }

                    $intervalObj->tempSensor = 1;
                    if (($tempcheck == '1,1' || $tempcheck == '-1,0')) {
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($tempcheck == '1,1' && $temp1_above_include) {
                            $cvo->message = $advDevice->vehicleno . " - Temperature is above " . $advDevice->temp1_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temp . "]";
                        } elseif ($tempcheck == '-1,0' && $temp1_below_include) {
                            $cvo->message = $advDevice->vehicleno . " - Temperature is below " . $advDevice->temp1_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temp . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp1_intv_email == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktempoff($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp1_intv_email != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                    if ($Is_Buzzer_Set) {
                                        $command = new stdClass();
                                        $command->uid = $advDevice->uid;
                                        $command->command = 'BUZZ=10';
                                        $um->setCommand($command);
                                    }
                                }
                                $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp_status_email == 0) {
                                $cqm->InsertQ($cvo);
                            }
                            $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        }
                    } elseif ($tempcheck == '0' && $advDevice->temp_status_email == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - Temperature is between  " . $advDevice->temp1_min_email . "&deg; C and " . $advDevice->temp1_max_email . "&deg; C[" . $temp . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '' && $temp1_bet_include) {
                            $cqm->InsertQ($cvo);
                        }
                        $cm->marktempoff($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    } elseif ($tempcheck == '0' && $advDevice->temp_status_email == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }
                }

                // Temperature Sensor 2
                if ($advDevice->temp_sensors == 2) {
                    $temp1 = '0';
                    $temp2 = '0';
                    $s1 = "analog" . $advDevice->tempsen1;
                    if ($advDevice->tempsen1 != 0 && $advDevice->$s1 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s1;
                        $temp1 = checktemp($temp_coversion, $advDevice->temp1_min_email, $advDevice->temp1_max_email, $advDevice->temp1_mute);
                        $temps1 = getTempUtil($temp_coversion);
                    } else {
                        $temp1 = '-';
                    }
                    $s2 = "analog" . $advDevice->tempsen2;
                    if ($advDevice->tempsen2 != 0 && $advDevice->$s2 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s2;
                        $temp2 = checktemp($temp_coversion, $advDevice->temp2_min_email, $advDevice->temp2_max_email, $advDevice->temp2_mute);
                        $temps2 = getTempUtil($temp_coversion);
                    } else {
                        $temp2 = '-';
                    }

                    $intervalObj->tempSensor = 1;
                    if (($temp1 == '1,1' || $temp1 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp1_intv_email != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp1_intv_email);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp1 == '1,1' && $temp1_above_include) {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 1 is above " . $advDevice->temp1_max_email . "&deg; C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        } elseif ($temp1 == '-1,0' && $temp1_below_include) {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 1 is below " . $advDevice->temp1_min_email . "&deg; C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp1_intv_email == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktempoff($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp1_intv_email != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                    if ($Is_Buzzer_Set) {
                                        $command = new stdClass();
                                        $command->uid = $advDevice->uid;
                                        $command->command = 'BUZZ=10';
                                        $um->setCommand($command);
                                    }
                                }
                                $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp_status_email == 0) {
                                $cqm->InsertQ($cvo);
                            }
                            $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        }
                    } elseif ($temp1 == '0' && $advDevice->temp_status_email == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - Temperature 1 is between " . $advDevice->temp1_min_email . " &deg;C and " . $advDevice->temp1_max_email . "&deg; C[" . $temps1 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '' && $temp1_bet_include) {
                            $cqm->InsertQ($cvo);
                        }
                        $cm->marktempoff($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    } elseif ($temp1 == '0' && $advDevice->temp_status_email == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }

                    $intervalObj->tempSensor = 2;
                    if (($temp2 == '1,1' || $temp2 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp2_intv_email != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp2_intv_email);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 2;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp2 == '1,1' && $temp2_above_include) {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 2 is above " . $advDevice->temp2_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        } elseif ($temp2 == '-1,0' && $temp2_below_include) {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 2 is below " . $advDevice->temp2_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp2_intv_email == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktemp2off($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp2_intv_email != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                    if ($Is_Buzzer_Set) {
                                        $command = new stdClass();
                                        $command->uid = $advDevice->uid;
                                        $command->command = 'BUZZ=10';
                                        $um->setCommand($command);
                                    }
                                }
                                $cm->marktemp2on($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp2_status_email == 0) {
                                $cqm->InsertQ($cvo);
                            }
                            $cm->marktemp2on($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        }
                    } elseif ($temp2 == '0' && $advDevice->temp2_status_email == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - Temperature 2 is between " . $advDevice->temp2_min_email . "&deg;C and " . $advDevice->temp2_max_email . "&deg;C[" . $temps2 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 2;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '' && $temp2_bet_include) {
                            $cqm->InsertQ($cvo);
                        }
                        $cm->marktemp2off($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    } elseif ($temp2 == '0' && $advDevice->temp2_status_email == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }
                }

                if ($advDevice->temp_sensors == 3) {
                    $temp1 = '0';
                    $temp2 = '0';
                    $temp3 = '0';
                    $s1 = "analog" . $advDevice->tempsen1;
                    if ($advDevice->tempsen1 != 0 && $advDevice->$s1 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s1;
                        $temp1 = checktemp($temp_coversion, $advDevice->temp1_min_email, $advDevice->temp1_max_email, $advDevice->temp1_mute);
                        $temps1 = getTempUtil($temp_coversion);
                    } else {
                        $temp1 = '-';
                    }
                    $s2 = "analog" . $advDevice->tempsen2;
                    if ($advDevice->tempsen2 != 0 && $advDevice->$s2 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s2;
                        $temp2 = checktemp($temp_coversion, $advDevice->temp2_min_email, $advDevice->temp2_max_email, $advDevice->temp2_mute);
                        $temps2 = getTempUtil($temp_coversion);
                    } else {
                        $temp2 = '-';
                    }
                    $s3 = "analog" . $advDevice->tempsen3;
                    if ($advDevice->tempsen3 != 0 && $advDevice->$s3 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s3;
                        $temp3 = checktemp($temp_coversion, $advDevice->temp3_min_email, $advDevice->temp3_max_email, $advDevice->temp3_mute);
                        $temps3 = getTempUtil($temp_coversion);
                    } else {
                        $temp3 = '-';
                    }

                    $intervalObj->tempSensor = 1;
                    if (($temp1 == '1,1' || $temp1 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp1_intv_email != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp1_intv_email);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp1 == '1,1' && $temp1_above_include) {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 1 is above " . $advDevice->temp1_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        } elseif ($temp1 == '-1,0' && $temp1_below_include) {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 1 is below " . $advDevice->temp1_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp1_intv_email == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktempoff($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp1_intv_email != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                }
                                $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $advDevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                            }
                            $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        }
                    } elseif ($temp1 == '0' && $advDevice->temp_status_email == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - Temperature 1 is between " . $advDevice->temp1_min_email . "&deg;C and " . $advDevice->temp1_max_email . "&deg;C[" . $temps1 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '' && $temp1_bet_include) {
                            $cqm->InsertQ($cvo);
                        }
                        $cm->marktempoff($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    } elseif ($temp1 == '0' && $advDevice->temp_status_email == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }

                    $intervalObj->tempSensor = 2;
                    if (($temp2 == '1,1' || $temp2 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp2_intv_email != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp2_intv_email);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 2;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp2 == '1,1' && $temp2_above_include) {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 2 is above " . $advDevice->temp2_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        } elseif ($temp2 == '-1,0' && $temp2_below_include) {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 2 is below " . $advDevice->temp2_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp2_intv_email == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktemp2off($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp2_intv_email != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                }
                                $cm->marktemp2on($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp2_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $advDevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                            }
                            $cm->marktemp2on($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        }
                    } elseif ($temp2 == '0' && $advDevice->temp2_status_email == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - Temperature 2 is between " . $advDevice->temp2_min_email . "&deg;C and " . $advDevice->temp2_max_email . "&deg;C[" . $temps2 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 2;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '' && $temp2_bet_include) {
                            $cqm->InsertQ($cvo);
                        }
                        $cm->marktemp2off($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    } elseif ($temp2 == '0' && $advDevice->temp2_status_email == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }

                    $intervalObj->tempSensor = 3;
                    if (($temp3 == '1,1' || $temp3 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp3_intv_email != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp3_intv_email);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 3;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp3 == '1,1' && $temp3_above_include) {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 3 is above " . $advDevice->temp3_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        } elseif ($temp3 == '-1,0' && $temp3_below_include) {
                            $cvo->message = $advDevice->vehicleno . " - Temperature 3 is below " . $advDevice->temp3_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp3_intv_email == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktemp3off($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp3_intv_email != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                }
                                $cm->marktemp3on($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp3_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $advDevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                            }
                            $cm->marktemp3on($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        }
                    } elseif ($temp3 == '0' && $advDevice->temp3_status_email == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - Temperature 3 is between " . $advDevice->temp3_min_email . "&deg;C and " . $advDevice->temp3_max_email . "&deg;C[" . $temps3 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 3;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '' && $temp2_bet_include) {
                            $cqm->InsertQ($cvo);
                        }
                        $cm->marktemp3off($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    } elseif ($temp3 == '0' && $advDevice->temp3_status_email == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }
                }

                if ($advDevice->temp_sensors == 4) {
                    $temp1 = '0';
                    $temp2 = '0';
                    $temp3 = '0';
                    $temp4 = '0';
                    $t1 = $cm->getNameForTempCron($advDevice->n1, $advDevice->customerno);
                    if ($t1 == '') {
                        $t1 = 'Temperature 1';
                    }
                    $t2 = $cm->getNameForTempCron($advDevice->n2, $advDevice->customerno);
                    if ($t2 == '') {
                        $t2 = 'Temperature 2';
                    }
                    $t3 = $cm->getNameForTempCron($advDevice->n3, $advDevice->customerno);
                    if ($t3 == '') {
                        $t3 = 'Temperature 3';
                    }
                    $t4 = $cm->getNameForTempCron($advDevice->n4, $advDevice->customerno);
                    if ($t4 == '') {
                        $t4 = 'Temperature 4';
                    }
                    $s1 = "analog" . $advDevice->tempsen1;
                    if ($advDevice->tempsen1 != 0 && $advDevice->$s1 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s1;
                        $temp1 = checktemp($temp_coversion, $advDevice->temp1_min_email, $advDevice->temp1_max_email, $advDevice->temp1_mute);
                        $temps1 = getTempUtil($temp_coversion);
                    } else {
                        $temp1 = '-';
                    }
                    $s2 = "analog" . $advDevice->tempsen2;
                    if ($advDevice->tempsen2 != 0 && $advDevice->$s2 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s2;
                        $temp2 = checktemp($temp_coversion, $advDevice->temp2_min_email, $advDevice->temp2_max_email, $advDevice->temp2_mute);
                        $temps2 = getTempUtil($temp_coversion);
                    } else {
                        $temp2 = '-';
                    }
                    $s3 = "analog" . $advDevice->tempsen3;
                    if ($advDevice->tempsen3 != 0 && $advDevice->$s3 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s3;
                        $temp3 = checktemp($temp_coversion, $advDevice->temp3_min_email, $advDevice->temp3_max_email, $advDevice->temp3_mute);
                        $temps3 = getTempUtil($temp_coversion);
                    } else {
                        $temp3 = '-';
                    }
                    $s4 = "analog" . $advDevice->tempsen4;
                    if ($advDevice->tempsen4 != 0 && $advDevice->$s4 != 0) {
                        $temp_coversion->rawtemp = $advDevice->$s4;
                        $temp4 = checktemp($temp_coversion, $advDevice->temp4_min_email, $advDevice->temp4_max_email, $advDevice->temp4_mute);
                        $temps4 = getTempUtil($temp_coversion);
                    } else {
                        $temp4 = '-';
                    }

                    $intervalObj->tempSensor = 1;
                    if (($temp1 == '1,1' || $temp1 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp1_intv_email != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp1_intv_email);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp1 == '1,1' && $temp1_above_include) {
                            $cvo->message = $advDevice->vehicleno . " - " . $t1 . " is above " . $advDevice->temp1_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        } elseif ($temp1 == '-1,0' && $temp1_below_include) {
                            $cvo->message = $advDevice->vehicleno . " - " . $t1 . " is below " . $advDevice->temp1_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp1_intv_email == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktempoff($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp1_intv_email != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                }
                                $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $advDevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                            }
                            $cm->marktempon($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        }
                    } elseif ($temp1 == '0' && $advDevice->temp_status_email == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - " . $t1 . " is between " . $advDevice->temp1_min_email . "&deg;C and " . $advDevice->temp1_max_email . "&deg;C[" . $temps1 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 1;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '' && $temp1_bet_include) {
                            $cqm->InsertQ($cvo);
                        }
                        $cm->marktempoff($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    } elseif ($temp1 == '0' && $advDevice->temp_status_email == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }

                    $intervalObj->tempSensor = 2;
                    if (($temp2 == '1,1' || $temp2 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp2_intv_email != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp2_intv_email);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 2;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp2 == '1,1' && $temp2_above_include) {
                            $cvo->message = $advDevice->vehicleno . " - " . $t2 . " is above " . $advDevice->temp2_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        } elseif ($temp2 == '-1,0' && $temp2_below_include) {
                            $cvo->message = $advDevice->vehicleno . " - " . $t2 . " is below " . $advDevice->temp2_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp2_intv_email == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktemp2off($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp2_intv_email != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                }
                                $cm->marktemp2on($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp2_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $advDevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                            }
                            $cm->marktemp2on($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        }
                    } elseif ($temp2 == '0' && $advDevice->temp2_status_email == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - " . $t2 . " is between " . $advDevice->temp2_min_email . "&deg;C and " . $advDevice->temp2_max_email . "&deg;C[" . $temps2 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 2;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '' && $temp2_bet_include) {
                            $cqm->InsertQ($cvo);
                        }
                        $cm->marktemp2off($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    } elseif ($temp2 == '0' && $advDevice->temp2_status_email == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }

                    $intervalObj->tempSensor = 3;
                    if (($temp3 == '1,1' || $temp3 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp3_intv_email != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp3_intv_email);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 3;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp3 == '1,1' && $temp3_above_include) {
                            $cvo->message = $advDevice->vehicleno . " - " . $t3 . " is above " . $advDevice->temp3_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        } elseif ($temp3 == '-1,0' && $temp3_below_include) {
                            $cvo->message = $advDevice->vehicleno . " - " . $t3 . " is below " . $advDevice->temp3_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        }

                        if ($temp_interval > 0) {
                            if ($advDevice->temp3_intv_email == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktemp3off($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp3_intv_email != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                }
                                $cm->marktemp3on($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp3_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $advDevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                            }
                            $cm->marktemp3on($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        }
                    } elseif ($temp3 == '0' && $advDevice->temp3_status_email == 1) {
                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - " . $t3 . " is between " . $advDevice->temp3_min_email . "&deg;C and " . $advDevice->temp3_max_email . "&deg;C[" . $temps3 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 3;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '' && $temp3_bet_include) {
                            $cqm->InsertQ($cvo);
                        }
                        $cm->marktemp3off($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    } elseif ($temp3 == '0' && $advDevice->temp3_status_email == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }

                    $intervalObj->tempSensor = 4;
                    if (($temp4 == '1,1' || $temp4 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($advDevice->temp4_intv_email != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($advDevice->temp4_intv_email);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 4;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if ($temp4 == '1,1' && $temp4_above_include) {
                            $cvo->message = $advDevice->vehicleno . " - " . $t4 . " is above " . $advDevice->temp4_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps4 . "]";
                        } elseif ($temp4 == '-1,0' && $temp4_below_include) {
                            $cvo->message = $advDevice->vehicleno . " - " . $t4 . " is below " . $advDevice->temp4_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps4 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($advDevice->temp4_intv_email == '0000-00-00 00:00:00') {
                                $intervalObj->switch = 1;
                                $cm->markAdvTemperatureInterval($intervalObj);
                                //$cm->marktemp4off($advDevice->vehicleid, $advDevice->customerno);
                            } elseif ($advDevice->temp4_intv_email != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                }
                                $cm->marktemp4on($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                                $intervalObj->switch = 0;
                                $cm->markAdvTemperatureInterval($intervalObj);
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $advDevice->temp4_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $advDevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                            }
                            $cm->marktemp4on($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        }
                    } elseif ($temp4 == '0' && $advDevice->temp4_status_email == 1) {

                        // Populate communication queue
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $advDevice->customerno;
                        $cvo->lat = $advDevice->devicelat;
                        $cvo->long = $advDevice->devicelong;
                        $cvo->message = $advDevice->vehicleno . " - " . $t4 . " is between " . $advDevice->temp4_min_email . "&deg;C and " . $advDevice->temp4_max_email . "&deg;C[" . $temps4 . "]";
                        $cvo->type = 8;
                        $cvo->status = 0;
                        $cvo->vehicleid = $advDevice->vehicleid;
                        $cvo->tempSensor = 4;
                        $cvo->userid = isset($advDevice->userid) ? $advDevice->userid : 0;
                        if (isset($cvo->message) && $cvo->message != '' && $temp4_bet_include) {
                            $cqm->InsertQ($cvo);
                        }
                        $cm->marktemp4off($advDevice->vehicleid, $advDevice->customerno, '_email', $advDevice->userid);
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    } elseif ($temp4 == '0' && $advDevice->temp4_status_email == 0) {
                        if ($temp_interval > 0) {
                            $intervalObj->switch = 0;
                            $cm->markAdvTemperatureInterval($intervalObj);
                        }
                    }
                }
            }
        }
        //</editor-fold>
    }
}

function checktemp($tempObj, $min, $max, $isMute) {
    $temp = 0;
    if ($isMute == 0) {
        $temp = getTempUtil($tempObj);
    }
    if ($min == $max || $temp == 0 || $isMute == 1) {
        return '-1';
    } elseif ($temp <= $max && $temp >= $min) {
        return '0';
    } elseif ($temp > $max) {
        return '1,1';
    } elseif ($temp < $min) {
        return '-1,0';
    }
}

echo "<br/> Cron Completed On " . date(speedConstants::DEFAULT_TIMESTAMP) . " <br/>";
?>
