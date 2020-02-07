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

$devices = $cm->getalldevicesfortempsensor();
if (isset($devices)) {
    foreach ($devices as $thisdevice) {
        if($thisdevice->customerno == 2 && $thisdevice->kind == 'Warehouse' && $thisdevice->vehicleno = 'Test'){

        //echo $thisdevice->vehicleno;
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

        // <editor-fold defaultstate="collapsed" desc="For General Range">
        $cust_array = explode(',', speedConstants::TEMP_CONFLICT_BUZZER_CUSTOMER);
        $Is_Buzzer_Set = FALSE;
        if (in_array($thisdevice->customerno, $cust_array)) {
            $Is_Buzzer_Set = TRUE;
        }
        if ($thisdevice->status != 'H' || $thisdevice->status != 'F') {
            $um = new UnitManager($thisdevice->customerno);
            $temp_coversion = new TempConversion();
            $temp_coversion->unit_type = $thisdevice->get_conversion;
            $temp_coversion->use_humidity = $thisdevice->use_humidity;
            $temp_coversion->switch_to = 0;
            if ($thisdevice->kind == 'Warehouse') {
                $temp_coversion->switch_to = 3;
            }
            $timeFirst = strtotime($thisdevice->lastupdated);
            $temp_interval = $cm->getTempInterval($thisdevice->customerno);

            if ($thisdevice->temp_sensors == 1) {
                $differenceInMin = 0;
                if ($thisdevice->temp1_intv != '0000-00-00 00:00:00') {
                    $timeSecond = strtotime($thisdevice->temp1_intv);
                    $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                }
                $tempcheck = '0';
                $s = "analog" . $thisdevice->tempsen1;
                if ($thisdevice->tempsen1 != 0 && $thisdevice->$s != 0) {
                    $temp_coversion->rawtemp = $thisdevice->$s;
                    $tempcheck = checktemp($temp_coversion, $thisdevice->temp1_min, $thisdevice->temp1_max, $thisdevice->temp1_mute);
                    $temp = getTempUtil($temp_coversion);
                } else {
                    $tempcheck = '-';
                }
                echo $tempcheck."--<br/>";
                if (($tempcheck == '1,1' || $tempcheck == '-1,0')) {
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->type = 8;
                    $cvo->status = 1;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 1;
                    if ($tempcheck == '1,1') {
                        $cvo->message = $thisdevice->vehicleno . " - Temperature is above " . $thisdevice->temp1_max . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    } elseif ($tempcheck == '-1,0') {
                        $cvo->message = $thisdevice->vehicleno . " - Temperature is below " . $thisdevice->temp1_min . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    }
                    $cvo->message .= "[" . $temp . "]";
                    if ($temp_interval > 0) {
                        if ($thisdevice->temp1_intv == '0000-00-00 00:00:00') {
                            $cm->marktempInton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            //$cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                        } elseif ($thisdevice->temp1_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                            if (isset($cvo->message) && $cvo->message != '') {
                                if (strpos($cvo->message, 'Temperature is above') !== false) {
                                    $temp1_above_include = FALSE;
                                } elseif (strpos($cvo->message, 'Temperature is below') !== false) {
                                    $temp1_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno);
                                $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            }
                        }
                    } else {
                        if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp_status == 0) {
                            if (strpos($cvo->message, 'Temperature is above') !== false) {
                                $temp1_above_include = FALSE;
                            } elseif (strpos($cvo->message, 'Temperature is below') !== false) {
                                $temp1_below_include = FALSE;
                            }
                            $cqm->InsertQ($cvo);
                            $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno);
                        }
                    }
                } elseif ($tempcheck == '0' && $thisdevice->temp_status == 1) {
                    // Populate communication queue
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->message = $thisdevice->vehicleno . " - Temperature is between  " . $thisdevice->temp1_min . "&deg; C and " . $thisdevice->temp1_max . "&deg; C[" . $temp . "]";
                    $cvo->type = 8;
                    $cvo->status = 0;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 1;
                    if (isset($cvo->message) && $cvo->message != '') {
                        $temp1_bet_include = FALSE;
                        $cqm->InsertQ($cvo);
                        $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                        if ($temp_interval > 0) {
                            $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                } elseif ($tempcheck == '0' && $thisdevice->temp_status == 0) {
                    if ($temp_interval > 0) {
                        $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                    }
                }
            }

            // Temperature Sensor 2
            if ($thisdevice->temp_sensors == 2) {
                $temp1 = '0';
                $temp2 = '0';
                $s1 = "analog" . $thisdevice->tempsen1;
                if ($thisdevice->tempsen1 != 0 && $thisdevice->$s1 != 0) {
                    $temp_coversion->rawtemp = $thisdevice->$s1;
                    $temp1 = checktemp($temp_coversion, $thisdevice->temp1_min, $thisdevice->temp1_max, $thisdevice->temp1_mute);
                    $temps1 = getTempUtil($temp_coversion);
                } else {
                    $temp1 = '-';
                }
                $s2 = "analog" . $thisdevice->tempsen2;
                if ($thisdevice->tempsen2 != 0 && $thisdevice->$s2 != 0) {
                    $temp_coversion->rawtemp = $thisdevice->$s2;
                    $temp2 = checktemp($temp_coversion, $thisdevice->temp2_min, $thisdevice->temp2_max, $thisdevice->temp2_mute);
                    $temps2 = getTempUtil($temp_coversion);
                } else {
                    $temp2 = '-';
                }
                if (($temp1 == '1,1' || $temp1 == '-1,0')) {
                    // Populate communication queue
                    $differenceInMin = 0;
                    if ($thisdevice->temp1_intv != '0000-00-00 00:00:00') {
                        $timeSecond = strtotime($thisdevice->temp1_intv);
                        $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                    }
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->type = 8;
                    $cvo->status = 1;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 1;
                    if ($temp1 == '1,1') {
                        $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is above " . $thisdevice->temp1_max . "&deg; C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    } elseif ($temp1 == '-1,0') {
                        $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is below " . $thisdevice->temp1_min . "&deg; C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    }
                    $cvo->message .= "[" . $temps1 . "]";
                    if ($temp_interval > 0) {
                        if ($thisdevice->temp1_intv == '0000-00-00 00:00:00') {
                            $cm->marktempInton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            //$cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                        } elseif ($thisdevice->temp1_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                            if (isset($cvo->message) && $cvo->message != '') {
                                if (strpos($cvo->message, 'Temperature 1 is above') !== false) {
                                    $temp1_above_include = FALSE;
                                } elseif (strpos($cvo->message, 'Temperature 1 is below') !== false) {
                                    $temp1_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno);
                                $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            }
                        }
                    } else {
                        if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp_status == 0) {
                            if (strpos($cvo->message, 'Temperature 1 is above') !== false) {
                                $temp1_above_include = FALSE;
                            } elseif (strpos($cvo->message, 'Temperature 1 is below') !== false) {
                                $temp1_below_include = FALSE;
                            }
                            $cqm->InsertQ($cvo);
                            $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp1 == '0' && $thisdevice->temp_status == 1) {
                    // Populate communication queue
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is between " . $thisdevice->temp1_min . " &deg;C and " . $thisdevice->temp1_max . "&deg; C[" . $temps1 . "]";
                    $cvo->type = 8;
                    $cvo->status = 0;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 1;
                    if (isset($cvo->message) && $cvo->message != '') {
                        $temp1_bet_include = FALSE;
                        $cqm->InsertQ($cvo);
                        $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                        if ($temp_interval > 0) {
                            $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp1 == '0' && $thisdevice->temp_status == 0) {
                    if ($temp_interval > 0) {
                        $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                    }
                }
                if (($temp2 == '1,1' || $temp2 == '-1,0')) {
                    // Populate communication queue
                    $differenceInMin = 0;
                    if ($thisdevice->temp2_intv != '0000-00-00 00:00:00') {
                        $timeSecond = strtotime($thisdevice->temp2_intv);
                        $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                    }
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->type = 8;
                    $cvo->status = 1;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 2;
                    if ($temp2 == '1,1') {
                        $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is above " . $thisdevice->temp2_max . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    } elseif ($temp2 == '-1,0') {
                        $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is below " . $thisdevice->temp2_min . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    }
                    $cvo->message .= "[" . $temps2 . "]";
                    if ($temp_interval > 0) {
                        if ($thisdevice->temp2_intv == '0000-00-00 00:00:00') {
                            $cm->marktemp2Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            //$cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno);
                        } elseif ($thisdevice->temp2_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                            if (isset($cvo->message) && $cvo->message != '') {
                                if (strpos($cvo->message, 'Temperature 2 is above') !== false) {
                                    $temp2_above_include = FALSE;
                                } elseif (strpos($cvo->message, 'Temperature 2 is below') !== false) {
                                    $temp2_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno);
                                $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            }
                        }
                    } else {
                        if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp2_status == 0) {
                            if (strpos($cvo->message, 'Temperature 2 is above') !== false) {
                                $temp2_above_include = FALSE;
                            } elseif (strpos($cvo->message, 'Temperature 2 is below') !== false) {
                                $temp2_below_include = FALSE;
                            }
                            $cqm->InsertQ($cvo);
                            $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp2 == '0' && $thisdevice->temp2_status == 1) {
                    // Populate communication queue
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is between " . $thisdevice->temp2_min . "&deg;C and " . $thisdevice->temp2_max . "&deg;C[" . $temps2 . "]";
                    $cvo->type = 8;
                    $cvo->status = 0;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 2;
                    if (isset($cvo->message) && $cvo->message != '') {
                        $temp2_bet_include = FALSE;
                        $cqm->InsertQ($cvo);
                        $cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno);
                        if ($temp_interval > 0) {
                            $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp2 == '0' && $thisdevice->temp2_status == 0) {
                    if ($temp_interval > 0) {
                        $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                    }
                }
            }

            if ($thisdevice->temp_sensors == 3) {
                $temp1 = '0';
                $temp2 = '0';
                $temp3 = '0';
                echo "1)".$thisdevice->temp1_mute."2)".$thisdevice->temp2_mute."3)".$thisdevice->temp3_mute."<br/>";
                $s1 = "analog" . $thisdevice->tempsen1;
                if ($thisdevice->tempsen1 != 0 && $thisdevice->$s1 != 0) {
                    $temp_coversion->rawtemp = $thisdevice->$s1;
                    $temp1 = checktemp($temp_coversion, $thisdevice->temp1_min, $thisdevice->temp1_max, $thisdevice->temp1_mute);
                    $temps1 = getTempUtil($temp_coversion);
                } else {
                    $temp1 = '-';
                }
                $s2 = "analog" . $thisdevice->tempsen2;
                if ($thisdevice->tempsen2 != 0 && $thisdevice->$s2 != 0) {
                    $temp_coversion->rawtemp = $thisdevice->$s2;
                    $temp2 = checktemp($temp_coversion, $thisdevice->temp2_min, $thisdevice->temp2_max, $thisdevice->temp2_mute);
                    $temps2 = getTempUtil($temp_coversion);
                } else {
                    $temp2 = '-';
                }
                $s3 = "analog" . $thisdevice->tempsen3;
                if ($thisdevice->tempsen3 != 0 && $thisdevice->$s3 != 0) {
                    $temp_coversion->rawtemp = $thisdevice->$s3;
                    $temp3 = checktemp($temp_coversion, $thisdevice->temp3_min, $thisdevice->temp3_max, $thisdevice->temp3_mute);
                    $temps3 = getTempUtil($temp_coversion);
                } else {
                    $temp3 = '-';
                }
                echo "temp1 ---".$temp1."--<br/>";
                if (($temp1 == '1,1' || $temp1 == '-1,0')) {
                    // Populate communication queue
                    $differenceInMin = 0;
                    if ($thisdevice->temp1_intv != '0000-00-00 00:00:00') {
                        $timeSecond = strtotime($thisdevice->temp1_intv);
                        $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                    }
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->type = 8;
                    $cvo->status = 1;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 1;
                    if ($temp1 == '1,1') {
                        $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is above " . $thisdevice->temp1_max . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    } elseif ($temp1 == '-1,0') {
                        $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is below " . $thisdevice->temp1_min . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    }
                    $cvo->message .= "[" . $temps1 . "]";
                    if ($temp_interval > 0) {
                        if ($thisdevice->temp1_intv == '0000-00-00 00:00:00') {
                            $cm->marktempInton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            //$cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                        } elseif ($thisdevice->temp1_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                            if (isset($cvo->message) && $cvo->message != '') {
                                if (strpos($cvo->message, 'Temperature 1 is above') !== false) {
                                    $temp1_above_include = FALSE;
                                } elseif (strpos($cvo->message, 'Temperature 1 is below') !== false) {
                                    $temp1_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno);
                                $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            }
                        }
                    } else {
                        if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp_status == 0) {
                            if (strpos($cvo->message, 'Temperature 1 is above') !== false) {
                                $temp1_above_include = FALSE;
                            } elseif (strpos($cvo->message, 'Temperature 1 is below') !== false) {
                                $temp1_below_include = FALSE;
                            }
                            $cqm->InsertQ($cvo);
                            if ($Is_Buzzer_Set) {
                                $command = new stdClass();
                                $command->uid = $thisdevice->uid;
                                $command->command = 'BUZZ=10';
                                $um->setCommand($command);
                            }
                            $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp1 == '0' && $thisdevice->temp_status == 1) {
                    // Populate communication queue
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is between " . $thisdevice->temp1_min . "&deg;C and " . $thisdevice->temp1_max . "&deg;C[" . $temps1 . "]";
                    $cvo->type = 8;
                    $cvo->status = 0;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 1;
                    if (isset($cvo->message) && $cvo->message != '') {
                        $temp1_bet_include = FALSE;
                        $cqm->InsertQ($cvo);
                        $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                        if ($temp_interval > 0) {
                            $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp1 == '0' && $thisdevice->temp_status == 0) {
                    if ($temp_interval > 0) {
                        $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                    }
                }
                echo "temp2 ---".$temp2."--<br/>";
                if (($temp2 == '1,1' || $temp2 == '-1,0')) {
                    // Populate communication queue
                    $differenceInMin = 0;
                    if ($thisdevice->temp2_intv != '0000-00-00 00:00:00') {
                        $timeSecond = strtotime($thisdevice->temp2_intv);
                        $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                    }
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->type = 8;
                    $cvo->status = 1;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 2;
                    if ($temp2 == '1,1') {
                        $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is above " . $thisdevice->temp2_max . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    } elseif ($temp2 == '-1,0') {
                        $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is below " . $thisdevice->temp2_min . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    }
                    $cvo->message .= "[" . $temps2 . "]";
                    if ($temp_interval > 0) {
                        if ($thisdevice->temp2_intv == '0000-00-00 00:00:00') {
                            $cm->marktemp2Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            //$cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno);
                        } elseif ($thisdevice->temp2_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                            if (isset($cvo->message) && $cvo->message != '') {
                                if (strpos($cvo->message, 'Temperature 2 is above') !== false) {
                                    $temp2_above_include = FALSE;
                                } elseif (strpos($cvo->message, 'Temperature 2 is below') !== false) {
                                    $temp2_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno);
                                $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            }
                        }
                    } else {
                        if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp2_status == 0) {
                            if (strpos($cvo->message, 'Temperature 2 is above') !== false) {
                                $temp2_above_include = FALSE;
                            } elseif (strpos($cvo->message, 'Temperature 2 is below') !== false) {
                                $temp2_below_include = FALSE;
                            }
                            $cqm->InsertQ($cvo);
                            if ($Is_Buzzer_Set) {
                                $command = new stdClass();
                                $command->uid = $thisdevice->uid;
                                $command->command = 'BUZZ=10';
                                $um->setCommand($command);
                            }
                            $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp2 == '0' && $thisdevice->temp2_status == 1) {
                    // Populate communication queue
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is between " . $thisdevice->temp2_min . "&deg;C and " . $thisdevice->temp2_max . "&deg;C[" . $temps2 . "]";
                    $cvo->type = 8;
                    $cvo->status = 0;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 2;
                    if (isset($cvo->message) && $cvo->message != '') {
                        $temp2_bet_include = FALSE;
                        $cqm->InsertQ($cvo);
                        $cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno);
                        if ($temp_interval > 0) {
                            $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp2 == '0' && $thisdevice->temp2_status == 0) {
                    if ($temp_interval > 0) {
                        $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                    }
                }
                echo "temp3 ---".$temp3."--<br/>";
                if (($temp3 == '1,1' || $temp3 == '-1,0')) {
                    // Populate communication queue
                    $differenceInMin = 0;
                    if ($thisdevice->temp3_intv != '0000-00-00 00:00:00') {
                        $timeSecond = strtotime($thisdevice->temp3_intv);
                        $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                    }
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->type = 8;
                    $cvo->status = 1;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 3;
                    if ($temp3 == '1,1') {
                        $cvo->message = $thisdevice->vehicleno . " - Temperature 3 is above " . $thisdevice->temp3_max . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    } elseif ($temp3 == '-1,0') {
                        $cvo->message = $thisdevice->vehicleno . " - Temperature 3 is below " . $thisdevice->temp3_min . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    }
                    $cvo->message .= "[" . $temps3 . "]";
                    if ($temp_interval > 0) {
                        if ($thisdevice->temp3_intv == '0000-00-00 00:00:00') {
                            $cm->marktemp3Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            //$cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno);
                        } elseif ($thisdevice->temp3_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                            if (isset($cvo->message) && $cvo->message != '') {
                                if (strpos($cvo->message, 'Temperature 3 is above') !== false) {
                                    $temp3_above_include = FALSE;
                                } elseif (strpos($cvo->message, 'Temperature 3 is below') !== false) {
                                    $temp3_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno);
                                $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            }
                        }
                    } else {
                        if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp3_status == 0) {
                            if (strpos($cvo->message, 'Temperature 3 is above') !== false) {
                                $temp3_above_include = FALSE;
                            } elseif (strpos($cvo->message, 'Temperature 3 is below') !== false) {
                                $temp3_below_include = FALSE;
                            }
                            $cqm->InsertQ($cvo);
                            if ($Is_Buzzer_Set) {
                                $command = new stdClass();
                                $command->uid = $thisdevice->uid;
                                $command->command = 'BUZZ=10';
                                $um->setCommand($command);
                            }
                            $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp3 == '0' && $thisdevice->temp3_status == 1) {
                    // Populate communication queue
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->message = $thisdevice->vehicleno . " - Temperature 3 is between " . $thisdevice->temp3_min . "&deg;C and " . $thisdevice->temp3_max . "&deg;C[" . $temps3 . "]";
                    $cvo->type = 8;
                    $cvo->status = 0;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 3;
                    if (isset($cvo->message) && $cvo->message != '') {
                        $temp3_bet_include = FALSE;
                        $cqm->InsertQ($cvo);
                        $cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno);
                        if ($temp_interval > 0) {
                            $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp3 == '0' && $thisdevice->temp3_status == 0) {
                    if ($temp_interval > 0) {
                        $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                    }
                }
            }

            if ($thisdevice->temp_sensors == 4) {
                $temp1 = '0';
                $temp2 = '0';
                $temp3 = '0';
                $temp4 = '0';
                $t1 = $cm->getNameForTempCron($thisdevice->n1, $thisdevice->customerno);
                if ($t1 == '') {
                    $t1 = 'Temperature 1';
                }
                $t2 = $cm->getNameForTempCron($thisdevice->n2, $thisdevice->customerno);
                if ($t2 == '') {
                    $t2 = 'Temperature 2';
                }
                $t3 = $cm->getNameForTempCron($thisdevice->n3, $thisdevice->customerno);
                if ($t3 == '') {
                    $t3 = 'Temperature 3';
                }
                $t4 = $cm->getNameForTempCron($thisdevice->n4, $thisdevice->customerno);
                if ($t4 == '') {
                    $t4 = 'Temperature 4';
                }
                $s1 = "analog" . $thisdevice->tempsen1;
                if ($thisdevice->tempsen1 != 0 && $thisdevice->$s1 != 0) {
                    $temp_coversion->rawtemp = $thisdevice->$s1;
                    $temp1 = checktemp($temp_coversion, $thisdevice->temp1_min, $thisdevice->temp1_max, $thisdevice->temp1_mute);
                    $temps1 = getTempUtil($temp_coversion);
                } else {
                    $temp1 = '-';
                }
                $s2 = "analog" . $thisdevice->tempsen2;
                if ($thisdevice->tempsen2 != 0 && $thisdevice->$s2 != 0) {
                    $temp_coversion->rawtemp = $thisdevice->$s2;
                    $temp2 = checktemp($temp_coversion, $thisdevice->temp2_min, $thisdevice->temp2_max, $thisdevice->temp2_mute);
                    $temps2 = getTempUtil($temp_coversion);
                } else {
                    $temp2 = '-';
                }
                $s3 = "analog" . $thisdevice->tempsen3;
                if ($thisdevice->tempsen3 != 0 && $thisdevice->$s3 != 0) {
                    $temp_coversion->rawtemp = $thisdevice->$s3;
                    $temp3 = checktemp($temp_coversion, $thisdevice->temp3_min, $thisdevice->temp3_max, $thisdevice->temp3_mute);
                    $temps3 = getTempUtil($temp_coversion);
                } else {
                    $temp3 = '-';
                }
                $s4 = "analog" . $thisdevice->tempsen4;
                if ($thisdevice->tempsen4 != 0 && $thisdevice->$s4 != 0) {
                    $temp_coversion->rawtemp = $thisdevice->$s4;
                    $temp4 = checktemp($temp_coversion, $thisdevice->temp4_min, $thisdevice->temp4_max, $thisdevice->temp4_mute);
                    $temps4 = getTempUtil($temp_coversion);
                } else {
                    $temp4 = '-';
                }
                if (($temp1 == '1,1' || $temp1 == '-1,0')) {
                    // Populate communication queue
                    $differenceInMin = 0;
                    if ($thisdevice->temp1_intv != '0000-00-00 00:00:00') {
                        $timeSecond = strtotime($thisdevice->temp1_intv);
                        $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                    }
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->type = 8;
                    $cvo->status = 1;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 1;
                    if ($temp1 == '1,1') {
                        $cvo->message = $thisdevice->vehicleno . " - " . $t1 . " is above " . $thisdevice->temp1_max . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    } elseif ($temp1 == '-1,0') {
                        $cvo->message = $thisdevice->vehicleno . " - " . $t1 . " is below " . $thisdevice->temp1_min . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    }
                    $cvo->message .= "[" . $temps1 . "]";
                    if ($temp_interval > 0) {
                        if ($thisdevice->temp1_intv == '0000-00-00 00:00:00') {
                            $cm->marktempInton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            //$cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                        } elseif ($thisdevice->temp1_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                            if (isset($cvo->message) && $cvo->message != '') {
                                if (strpos($cvo->message, $t1 . ' is above') !== false) {
                                    $temp1_above_include = FALSE;
                                } elseif (strpos($cvo->message, $t1 . ' is below ') !== false) {
                                    $temp1_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno);
                                $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            }
                        }
                    } else {
                        if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp_status == 0) {
                            if (strpos($cvo->message, $t1 . ' is above') !== false) {
                                $temp1_above_include = FALSE;
                            } elseif (strpos($cvo->message, $t1 . ' is below ') !== false) {
                                $temp1_below_include = FALSE;
                            }
                            $cqm->InsertQ($cvo);
                            if ($Is_Buzzer_Set) {
                                $command = new stdClass();
                                $command->uid = $thisdevice->uid;
                                $command->command = 'BUZZ=10';
                                $um->setCommand($command);
                            }
                            $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp1 == '0' && $thisdevice->temp_status == 1) {
                    // Populate communication queue
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->message = $thisdevice->vehicleno . " - " . $t1 . " is between " . $thisdevice->temp1_min . "&deg;C and " . $thisdevice->temp1_max . "&deg;C[" . $temps1 . "]";
                    $cvo->type = 8;
                    $cvo->status = 0;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 1;
                    if (isset($cvo->message) && $cvo->message != '') {
                        $temp1_bet_include = FALSE;
                        $cqm->InsertQ($cvo);
                        $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                        if ($temp_interval > 0) {
                            $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp1 == '0' && $thisdevice->temp_status == 0) {
                    if ($temp_interval > 0) {
                        $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                    }
                }
                if (($temp2 == '1,1' || $temp2 == '-1,0')) {
                    // Populate communication queue
                    $differenceInMin = 0;
                    if ($thisdevice->temp2_intv != '0000-00-00 00:00:00') {
                        $timeSecond = strtotime($thisdevice->temp2_intv);
                        $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                    }
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->type = 8;
                    $cvo->status = 1;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 2;
                    if ($temp2 == '1,1') {
                        $cvo->message = $thisdevice->vehicleno . " - " . $t2 . " is above " . $thisdevice->temp2_max . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    } elseif ($temp2 == '-1,0') {
                        $cvo->message = $thisdevice->vehicleno . " - " . $t2 . " is below " . $thisdevice->temp2_min . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    }
                    $cvo->message .= "[" . $temps2 . "]";
                    if ($temp_interval > 0) {
                        if ($thisdevice->temp2_intv == '0000-00-00 00:00:00') {
                            $cm->marktemp2Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            //$cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno);
                        } elseif ($thisdevice->temp2_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                            if (isset($cvo->message) && $cvo->message != '') {
                                if (strpos($cvo->message, $t2 . ' is above') !== false) {
                                    $temp2_above_include = FALSE;
                                } elseif (strpos($cvo->message, $t2 . ' is below ') !== false) {
                                    $temp2_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno);
                                $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            }
                        }
                    } else {
                        if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp2_status == 0) {
                            if (strpos($cvo->message, $t2 . ' is above') !== false) {
                                $temp2_above_include = FALSE;
                            } elseif (strpos($cvo->message, $t2 . ' is below ') !== false) {
                                $temp2_below_include = FALSE;
                            }
                            $cqm->InsertQ($cvo);
                            if ($Is_Buzzer_Set) {
                                $command = new stdClass();
                                $command->uid = $thisdevice->uid;
                                $command->command = 'BUZZ=10';
                                $um->setCommand($command);
                            }
                            $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp2 == '0' && $thisdevice->temp2_status == 1) {
                    // Populate communication queue
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->message = $thisdevice->vehicleno . " - " . $t2 . " is between " . $thisdevice->temp2_min . "&deg;C and " . $thisdevice->temp2_max . "&deg;C[" . $temps2 . "]";
                    $cvo->type = 8;
                    $cvo->status = 0;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 2;
                    if (isset($cvo->message) && $cvo->message != '') {
                        $temp2_bet_include = FALSE;
                        $cqm->InsertQ($cvo);
                        $cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno);
                        if ($temp_interval > 0) {
                            $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp2 == '0' && $thisdevice->temp2_status == 0) {
                    if ($temp_interval > 0) {
                        $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                    }
                }
                if (($temp3 == '1,1' || $temp3 == '-1,0')) {
                    // Populate communication queue
                    $differenceInMin = 0;
                    if ($thisdevice->temp3_intv != '0000-00-00 00:00:00') {
                        $timeSecond = strtotime($thisdevice->temp3_intv);
                        $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                    }
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->type = 8;
                    $cvo->status = 1;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 3;
                    if ($temp3 == '1,1') {
                        $cvo->message = $thisdevice->vehicleno . " - " . $t3 . " is above " . $thisdevice->temp3_max . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    } elseif ($temp3 == '-1,0') {
                        $cvo->message = $thisdevice->vehicleno . " - " . $t3 . " is below " . $thisdevice->temp3_min . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    }
                    $cvo->message .= "[" . $temps3 . "]";
                    if ($temp_interval > 0) {
                        if ($thisdevice->temp3_intv == '0000-00-00 00:00:00') {
                            $cm->marktemp3Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            //$cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno);
                        } elseif ($thisdevice->temp3_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                            if (isset($cvo->message) && $cvo->message != '') {
                                if (strpos($cvo->message, $t3 . ' is above') !== false) {
                                    $temp3_above_include = FALSE;
                                } elseif (strpos($cvo->message, $t3 . ' is below ') !== false) {
                                    $temp3_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno);
                                $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            }
                        }
                    } else {
                        if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp3_status == 0) {
                            if (strpos($cvo->message, $t3 . ' is above') !== false) {
                                $temp3_above_include = FALSE;
                            } elseif (strpos($cvo->message, $t3 . ' is below ') !== false) {
                                $temp3_below_include = FALSE;
                            }
                            $cqm->InsertQ($cvo);
                            if ($Is_Buzzer_Set) {
                                $command = new stdClass();
                                $command->uid = $thisdevice->uid;
                                $command->command = 'BUZZ=10';
                                $um->setCommand($command);
                            }
                            $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp3 == '0' && $thisdevice->temp3_status == 1) {
                    // Populate communication queue
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->message = $thisdevice->vehicleno . " - " . $t3 . " is between " . $thisdevice->temp3_min . "&deg;C and " . $thisdevice->temp3_max . "&deg;C[" . $temps3 . "]";
                    $cvo->type = 8;
                    $cvo->status = 0;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 3;
                    if (isset($cvo->message) && $cvo->message != '') {
                        $temp3_bet_include = FALSE;
                        $cqm->InsertQ($cvo);
                        $cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno);
                        if ($temp_interval > 0) {
                            $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp3 == '0' && $thisdevice->temp3_status == 0) {
                    if ($temp_interval > 0) {
                        $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                    }
                }
                if (($temp4 == '1,1' || $temp4 == '-1,0')) {
                    // Populate communication queue
                    $differenceInMin = 0;
                    if ($thisdevice->temp4_intv != '0000-00-00 00:00:00') {
                        $timeSecond = strtotime($thisdevice->temp4_intv);
                        $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                    }
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->type = 8;
                    $cvo->status = 1;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 4;
                    if ($temp4 == '1,1') {
                        $cvo->message = $thisdevice->vehicleno . " - " . $t4 . " is above " . $thisdevice->temp4_max . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    } elseif ($temp4 == '-1,0') {
                        $cvo->message = $thisdevice->vehicleno . " - " . $t4 . " is below " . $thisdevice->temp4_min . "&deg;C";
                        if ($temp_interval > 0) {
                            $cvo->message .= " for more than " . $temp_interval . " mins";
                        }
                    }
                    $cvo->message .= "[" . $temps4 . "]";
                    if ($temp_interval > 0) {
                        if ($thisdevice->temp4_intv == '0000-00-00 00:00:00') {
                            $cm->marktemp4Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            //$cm->marktemp4off($thisdevice->vehicleid, $thisdevice->customerno);
                        } elseif ($thisdevice->temp4_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                            if (isset($cvo->message) && $cvo->message != '') {
                                if (strpos($cvo->message, $t4 . ' is above') !== false) {
                                    $temp4_above_include = FALSE;
                                } elseif (strpos($cvo->message, $t4 . ' is below ') !== false) {
                                    $temp4_below_include = FALSE;
                                }
                                $cqm->InsertQ($cvo);
                                $cm->marktemp4on($thisdevice->vehicleid, $thisdevice->customerno);
                                $cm->marktemp4Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                            }
                        }
                    } else {
                        if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp4_status == 0) {
                            if (strpos($cvo->message, $t4 . ' is above') !== false) {
                                $temp4_above_include = FALSE;
                            } elseif (strpos($cvo->message, $t4 . ' is below ') !== false) {
                                $temp4_below_include = FALSE;
                            }
                            $cqm->InsertQ($cvo);
                            if ($Is_Buzzer_Set) {
                                $command = new stdClass();
                                $command->uid = $thisdevice->uid;
                                $command->command = 'BUZZ=10';
                                $um->setCommand($command);
                            }
                            $cm->marktemp4on($thisdevice->vehicleid, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp4 == '0' && $thisdevice->temp4_status == 1) {
                    // Populate communication queue
                    $cqm = new ComQueueManager();
                    $cvo = new VOComQueue();
                    $cvo->customerno = $thisdevice->customerno;
                    $cvo->lat = $thisdevice->devicelat;
                    $cvo->long = $thisdevice->devicelong;
                    $cvo->message = $thisdevice->vehicleno . " - " . $t4 . " is between " . $thisdevice->temp4_min . "&deg;C and " . $thisdevice->temp4_max . "&deg;C[" . $temps4 . "]";
                    $cvo->type = 8;
                    $cvo->status = 0;
                    $cvo->vehicleid = $thisdevice->vehicleid;
                    $cvo->tempSensor = 4;
                    if (isset($cvo->message) && $cvo->message != '') {
                        $temp4_bet_include = FALSE;
                        $cqm->InsertQ($cvo);
                        $cm->marktemp4off($thisdevice->vehicleid, $thisdevice->customerno);
                        if ($temp_interval > 0) {
                            $cm->marktemp4Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                } elseif ($temp4 == '0' && $thisdevice->temp4_status == 0) {
                    if ($temp_interval > 0) {
                        $cm->marktemp4Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                    }
                }
            }
        }
        //</editor-fold>
        // <editor-fold defaultstate="collapsed" desc="For Advance range of SMS">
        if (isset($thisdevice->temp1_min_sms)) {
            $cust_array = explode(',', speedConstants::TEMP_CONFLICT_BUZZER_CUSTOMER);
            $Is_Buzzer_Set = FALSE;
            if (in_array($thisdevice->customerno, $cust_array)) {
                $Is_Buzzer_Set = TRUE;
            }
            if ($thisdevice->status != 'H' || $thisdevice->status != 'F') {
                $um = new UnitManager($thisdevice->customerno);
                $temp_coversion = new TempConversion();
                $temp_coversion->unit_type = $thisdevice->get_conversion;
                $temp_coversion->use_humidity = $thisdevice->use_humidity;
                $temp_coversion->switch_to = 0;
                if ($thisdevice->kind == 'Warehouse') {
                    $temp_coversion->switch_to = 3;
                }
                $timeFirst = strtotime($thisdevice->lastupdated);
                $temp_interval = $cm->getTempInterval($thisdevice->customerno);

                if ($thisdevice->temp_sensors == 1) {
                    $differenceInMin = 0;
                    if ($thisdevice->temp1_intv != '0000-00-00 00:00:00') {
                        $timeSecond = strtotime($thisdevice->temp1_intv);
                        $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                    }
                    $tempcheck = '0';
                    $s = "analog" . $thisdevice->tempsen1;
                    if ($thisdevice->tempsen1 != 0 && $thisdevice->$s != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s;
                        $tempcheck = checktemp($temp_coversion, $thisdevice->temp1_min_sms, $thisdevice->temp1_max_sms, $thisdevice->temp1_mute);
                        $temp = getTempUtil($temp_coversion);
                    } else {
                        $tempcheck = '-';
                    }
                    if (($tempcheck == '1,1' || $tempcheck == '-1,0')) {
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 1;
                        if ($tempcheck == '1,1' && $temp1_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature is above " . $thisdevice->temp1_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temp . "]";
                        } elseif ($tempcheck == '-1,0' && $temp1_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature is below " . $thisdevice->temp1_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temp . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp1_intv == '0000-00-00 00:00:00') {
                                $cm->marktempInton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp1_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, 'Temperature is above') !== false) {
                                        $temp1_above_include = FALSE;
                                    } elseif (strpos($cvo->message, 'Temperature is below') !== false) {
                                        $temp1_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    if ($Is_Buzzer_Set) {
                                        $command = new stdClass();
                                        $command->uid = $thisdevice->uid;
                                        $command->command = 'BUZZ=10';
                                        $um->setCommand($command);
                                    }
                                    $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp_status_sms == 0) {
                                $cqm->InsertQ($cvo);
                                $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                            }
                        }
                        if (!$temp1_above_include || !$temp1_below_include) {
                            $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($tempcheck == '0' && $thisdevice->temp_status_sms == 1) {
                        if ($temp1_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - Temperature is between  " . $thisdevice->temp1_min_sms . "&deg; C and " . $thisdevice->temp1_max_sms . "&deg; C[" . $temp . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 1;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $temp1_bet_include = FALSE;
                                $cqm->InsertQ($cvo);
                                $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                if ($temp_interval > 0) {
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($tempcheck == '0' && $thisdevice->temp_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                }

                // Temperature Sensor 2
                if ($thisdevice->temp_sensors == 2) {
                    $temp1 = '0';
                    $temp2 = '0';
                    $s1 = "analog" . $thisdevice->tempsen1;
                    if ($thisdevice->tempsen1 != 0 && $thisdevice->$s1 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s1;
                        $temp1 = checktemp($temp_coversion, $thisdevice->temp1_min_sms, $thisdevice->temp1_max_sms, $thisdevice->temp1_mute);
                        $temps1 = getTempUtil($temp_coversion);
                    } else {
                        $temp1 = '-';
                    }
                    $s2 = "analog" . $thisdevice->tempsen2;
                    if ($thisdevice->tempsen2 != 0 && $thisdevice->$s2 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s2;
                        $temp2 = checktemp($temp_coversion, $thisdevice->temp2_min_sms, $thisdevice->temp2_max_sms, $thisdevice->temp2_mute);
                        $temps2 = getTempUtil($temp_coversion);
                    } else {
                        $temp2 = '-';
                    }
                    if (($temp1 == '1,1' || $temp1 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp1_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp1_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 1;
                        if ($temp1 == '1,1' && $temp1_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is above " . $thisdevice->temp1_max_sms . "&deg; C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        } elseif ($temp1 == '-1,0' && $temp1_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is below " . $thisdevice->temp1_min_sms . "&deg; C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp1_intv == '0000-00-00 00:00:00') {
                                $cm->marktempInton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp1_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, 'Temperature 1 is above') !== false) {
                                        $temp1_above_include = FALSE;
                                    } elseif (strpos($cvo->message, 'Temperature 1 is below') !== false) {
                                        $temp1_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    if ($Is_Buzzer_Set) {
                                        $command = new stdClass();
                                        $command->uid = $thisdevice->uid;
                                        $command->command = 'BUZZ=10';
                                        $um->setCommand($command);
                                    }
                                    $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp_status_sms == 0) {
                                $cqm->InsertQ($cvo);
                                $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                            }
                        }
                        if (!$temp1_above_include || !$temp1_below_include) {
                            $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp1 == '0' && $thisdevice->temp_status_sms == 1) {
                        if ($temp1_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is between " . $thisdevice->temp1_min_sms . " &deg;C and " . $thisdevice->temp1_max_sms . "&deg; C[" . $temps1 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 1;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $temp1_bet_include = FALSE;
                                $cqm->InsertQ($cvo);
                                $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                if ($temp_interval > 0) {
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp1 == '0' && $thisdevice->temp_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }

                    if (($temp2 == '1,1' || $temp2 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp2_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp2_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 2;
                        if ($temp2 == '1,1' && $temp2_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is above " . $thisdevice->temp2_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        } elseif ($temp2 == '-1,0' && $temp2_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is below " . $thisdevice->temp2_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp2_intv == '0000-00-00 00:00:00') {
                                $cm->marktemp2Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp2_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, 'Temperature 2 is above') !== false) {
                                        $temp2_above_include = FALSE;
                                    } elseif (strpos($cvo->message, 'Temperature 2 is below') !== false) {
                                        $temp2_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    if ($Is_Buzzer_Set) {
                                        $command = new stdClass();
                                        $command->uid = $thisdevice->uid;
                                        $command->command = 'BUZZ=10';
                                        $um->setCommand($command);
                                    }
                                    $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                    $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp2_status_sms == 0) {
                                $cqm->InsertQ($cvo);
                                $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                            }
                        }
                        if (!$temp2_above_include || !$temp2_below_include) {
                            $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp2 == '0' && $thisdevice->temp2_status_sms == 1) {
                        if ($temp2_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is between " . $thisdevice->temp2_min_sms . "&deg;C and " . $thisdevice->temp2_max_sms . "&deg;C[" . $temps2 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 2;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $temp2_bet_include = FALSE;
                                $cqm->InsertQ($cvo);
                                $cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                if ($temp_interval > 0) {
                                    $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp2 == '0' && $thisdevice->temp2_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                }

                if ($thisdevice->temp_sensors == 3) {
                    $temp1 = '0';
                    $temp2 = '0';
                    $temp3 = '0';
                    $s1 = "analog" . $thisdevice->tempsen1;
                    if ($thisdevice->tempsen1 != 0 && $thisdevice->$s1 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s1;
                        $temp1 = checktemp($temp_coversion, $thisdevice->temp1_min_sms, $thisdevice->temp1_max_sms, $thisdevice->temp1_mute);
                        $temps1 = getTempUtil($temp_coversion);
                    } else {
                        $temp1 = '-';
                    }
                    $s2 = "analog" . $thisdevice->tempsen2;
                    if ($thisdevice->tempsen2 != 0 && $thisdevice->$s2 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s2;
                        $temp2 = checktemp($temp_coversion, $thisdevice->temp2_min_sms, $thisdevice->temp2_max_sms, $thisdevice->temp2_mute);
                        $temps2 = getTempUtil($temp_coversion);
                    } else {
                        $temp2 = '-';
                    }
                    $s3 = "analog" . $thisdevice->tempsen3;
                    if ($thisdevice->tempsen3 != 0 && $thisdevice->$s3 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s3;
                        $temp3 = checktemp($temp_coversion, $thisdevice->temp3_min_sms, $thisdevice->temp3_max_sms, $thisdevice->temp3_mute);
                        $temps3 = getTempUtil($temp_coversion);
                    } else {
                        $temp3 = '-';
                    }
                    if (($temp1 == '1,1' || $temp1 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp1_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp1_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 1;
                        if ($temp1 == '1,1' && $temp1_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is above " . $thisdevice->temp1_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        } elseif ($temp1 == '-1,0' && $temp1_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is below " . $thisdevice->temp1_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp1_intv == '0000-00-00 00:00:00') {
                                $cm->marktempInton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp1_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, 'Temperature 1 is above') !== false) {
                                        $temp1_above_include = FALSE;
                                    } elseif (strpos($cvo->message, 'Temperature 1 is below') !== false) {
                                        $temp1_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp_status_sms == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                            }
                        }
                        if (!$temp1_above_include || !$temp1_below_include) {
                            $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp1 == '0' && $thisdevice->temp_status_sms == 1) {
                        if ($temp1_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is between " . $thisdevice->temp1_min_sms . "&deg;C and " . $thisdevice->temp1_max_sms . "&deg;C[" . $temps1 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 1;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $temp1_bet_include = FALSE;
                                $cqm->InsertQ($cvo);
                                $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                if ($temp_interval > 0) {
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp1 == '0' && $thisdevice->temp_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }

                    if (($temp2 == '1,1' || $temp2 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp2_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp2_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 2;
                        if ($temp2 == '1,1' && $temp2_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is above " . $thisdevice->temp2_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        } elseif ($temp2 == '-1,0' && $temp2_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is below " . $thisdevice->temp2_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp2_intv == '0000-00-00 00:00:00') {
                                $cm->marktemp2Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp2_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, 'Temperature 2 is above') !== false) {
                                        $temp2_above_include = FALSE;
                                    } elseif (strpos($cvo->message, 'Temperature 2 is below') !== false) {
                                        $temp2_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                    $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp2_status_sms == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                            }
                        }
                        if (!$temp2_above_include || !$temp2_below_include) {
                            $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp2 == '0' && $thisdevice->temp2_status_sms == 1) {
                        if ($temp2_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is between " . $thisdevice->temp2_min_sms . "&deg;C and " . $thisdevice->temp2_max_sms . "&deg;C[" . $temps2 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 2;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $temp2_bet_include = FALSE;
                                $cqm->InsertQ($cvo);
                                $cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                if ($temp_interval > 0) {
                                    $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp2 == '0' && $thisdevice->temp2_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                    if (($temp3 == '1,1' || $temp3 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp3_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp3_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 3;
                        if ($temp3 == '1,1' && $temp3_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 3 is above " . $thisdevice->temp3_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        } elseif ($temp3 == '-1,0' && $temp3_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 3 is below " . $thisdevice->temp3_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp3_intv == '0000-00-00 00:00:00') {
                                $cm->marktemp3Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp3_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, 'Temperature 3 is above') !== false) {
                                        $temp3_above_include = FALSE;
                                    } elseif (strpos($cvo->message, 'Temperature 3 is below') !== false) {
                                        $temp3_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                    $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp3_status_sms == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                            }
                        }
                        if (!$temp3_above_include || !$temp3_below_include) {
                            $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp3 == '0' && $thisdevice->temp3_status_sms == 1) {
                        if ($temp3_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 3 is between " . $thisdevice->temp3_min_sms . "&deg;C and " . $thisdevice->temp3_max_sms . "&deg;C[" . $temps3 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 3;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $temp3_bet_include = FALSE;
                                $cqm->InsertQ($cvo);
                                $cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                if ($temp_interval > 0) {
                                    $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp3 == '0' && $thisdevice->temp3_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                }

                if ($thisdevice->temp_sensors == 4) {
                    $temp1 = '0';
                    $temp2 = '0';
                    $temp3 = '0';
                    $temp4 = '0';
                    $t1 = $cm->getNameForTempCron($thisdevice->n1, $thisdevice->customerno);
                    if ($t1 == '') {
                        $t1 = 'Temperature 1';
                    }
                    $t2 = $cm->getNameForTempCron($thisdevice->n2, $thisdevice->customerno);
                    if ($t2 == '') {
                        $t2 = 'Temperature 2';
                    }
                    $t3 = $cm->getNameForTempCron($thisdevice->n3, $thisdevice->customerno);
                    if ($t3 == '') {
                        $t3 = 'Temperature 3';
                    }
                    $t4 = $cm->getNameForTempCron($thisdevice->n4, $thisdevice->customerno);
                    if ($t4 == '') {
                        $t4 = 'Temperature 4';
                    }
                    $s1 = "analog" . $thisdevice->tempsen1;
                    if ($thisdevice->tempsen1 != 0 && $thisdevice->$s1 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s1;
                        $temp1 = checktemp($temp_coversion, $thisdevice->temp1_min_sms, $thisdevice->temp1_max_sms, $thisdevice->temp1_mute);
                        $temps1 = getTempUtil($temp_coversion);
                    } else {
                        $temp1 = '-';
                    }
                    $s2 = "analog" . $thisdevice->tempsen2;
                    if ($thisdevice->tempsen2 != 0 && $thisdevice->$s2 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s2;
                        $temp2 = checktemp($temp_coversion, $thisdevice->temp2_min_sms, $thisdevice->temp2_max_sms, $thisdevice->temp2_mute);
                        $temps2 = getTempUtil($temp_coversion);
                    } else {
                        $temp2 = '-';
                    }
                    $s3 = "analog" . $thisdevice->tempsen3;
                    if ($thisdevice->tempsen3 != 0 && $thisdevice->$s3 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s3;
                        $temp3 = checktemp($temp_coversion, $thisdevice->temp3_min_sms, $thisdevice->temp3_max_sms, $thisdevice->temp3_mute);
                        $temps3 = getTempUtil($temp_coversion);
                    } else {
                        $temp3 = '-';
                    }
                    $s4 = "analog" . $thisdevice->tempsen4;
                    if ($thisdevice->tempsen4 != 0 && $thisdevice->$s4 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s4;
                        $temp4 = checktemp($temp_coversion, $thisdevice->temp4_min_sms, $thisdevice->temp4_max_sms, $thisdevice->temp4_mute);
                        $temps4 = getTempUtil($temp_coversion);
                    } else {
                        $temp4 = '-';
                    }
                    if (($temp1 == '1,1' || $temp1 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp1_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp1_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 1;
                        if ($temp1 == '1,1' && $temp1_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t1 . " is above " . $thisdevice->temp1_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        } elseif ($temp1 == '-1,0' && $temp1_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t1 . " is below " . $thisdevice->temp1_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp1_intv == '0000-00-00 00:00:00') {
                                $cm->marktempInton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp1_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, $t1 . " is above ") !== false) {
                                        $temp1_above_include = FALSE;
                                    } elseif (strpos($cvo->message, $t1 . " is below ") !== false) {
                                        $temp1_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp_status_sms == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                            }
                        }
                        if (!$temp1_above_include || !$temp1_below_include) {
                            $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp1 == '0' && $thisdevice->temp_status_sms == 1) {
                        if ($temp1_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - " . $t1 . " is between " . $thisdevice->temp1_min_sms . "&deg;C and " . $thisdevice->temp1_max_sms . "&deg;C[" . $temps1 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 1;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $temp1_bet_include = FALSE;
                                $cqm->InsertQ($cvo);
                                $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                if ($temp_interval > 0) {
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp1 == '0' && $thisdevice->temp_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }

                    if (($temp2 == '1,1' || $temp2 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp2_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp2_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 2;
                        if ($temp2 == '1,1' && $temp2_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t2 . " is above " . $thisdevice->temp2_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        } elseif ($temp2 == '-1,0' && $temp2_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t2 . " is below " . $thisdevice->temp2_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp2_intv == '0000-00-00 00:00:00') {
                                $cm->marktemp2Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp2_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, $t2 . " is above ") !== false) {
                                        $temp2_above_include = FALSE;
                                    } elseif (strpos($cvo->message, $t2 . " is below ") !== false) {
                                        $temp2_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                    $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp2_status_sms == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                            }
                        }
                        if (!$temp2_above_include || !$temp2_below_include) {
                            $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp2 == '0' && $thisdevice->temp2_status_sms == 1) {
                        if ($temp2_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - " . $t2 . " is between " . $thisdevice->temp2_min_sms . "&deg;C and " . $thisdevice->temp2_max_sms . "&deg;C[" . $temps2 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 2;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $temp2_bet_include = FALSE;
                                $cqm->InsertQ($cvo);
                                $cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                if ($temp_interval > 0) {
                                    $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp2 == '0' && $thisdevice->temp2_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }

                    if (($temp3 == '1,1' || $temp3 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp3_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp3_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 3;
                        if ($temp3 == '1,1' && $temp3_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t3 . " is above " . $thisdevice->temp3_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        } elseif ($temp3 == '-1,0' && $temp3_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t3 . " is below " . $thisdevice->temp3_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        }

                        if ($temp_interval > 0) {
                            if ($thisdevice->temp3_intv == '0000-00-00 00:00:00') {
                                $cm->marktemp3Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp3_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, $t3 . " is above ") !== false) {
                                        $temp3_above_include = FALSE;
                                    } elseif (strpos($cvo->message, $t3 . " is below ") !== false) {
                                        $temp3_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                    $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp3_status_sms == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                            }
                        }
                        if (!$temp3_above_include || !$temp3_below_include) {
                            $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp3 == '0' && $thisdevice->temp3_status_sms == 1) {
                        if ($temp3_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - " . $t3 . " is between " . $thisdevice->temp3_min_sms . "&deg;C and " . $thisdevice->temp3_max_sms . "&deg;C[" . $temps3 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 3;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $temp3_bet_include = FALSE;
                                $cqm->InsertQ($cvo);
                                $cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                if ($temp_interval > 0) {
                                    $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp3 == '0' && $thisdevice->temp3_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }

                    if (($temp4 == '1,1' || $temp4 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp4_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp4_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 4;
                        if ($temp4 == '1,1' && $temp4_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t4 . " is above " . $thisdevice->temp4_max_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps4 . "]";
                        } elseif ($temp4 == '-1,0' && $temp4_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t4 . " is below " . $thisdevice->temp4_min_sms . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps4 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp4_intv == '0000-00-00 00:00:00') {
                                $cm->marktemp4Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktemp4off($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp4_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    if (strpos($cvo->message, $t4 . " is above ") !== false) {
                                        $temp4_above_include = FALSE;
                                    } elseif (strpos($cvo->message, $t4 . " is below ") !== false) {
                                        $temp4_below_include = FALSE;
                                    }
                                    $cqm->InsertQ($cvo);
                                    $cm->marktemp4on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                    $cm->marktemp4Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp4_status_sms == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp4on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                            }
                        }
                        if (!$temp4_above_include || !$temp4_below_include) {
                            $cm->marktemp4on($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp4 == '0' && $thisdevice->temp4_status_sms == 1) {
                        if ($temp4_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - " . $t4 . " is between " . $thisdevice->temp4_min_sms . "&deg;C and " . $thisdevice->temp4_max_sms . "&deg;C[" . $temps4 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 4;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $temp4_bet_include = FALSE;
                                $cqm->InsertQ($cvo);
                                $cm->marktemp4off($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                                if ($temp_interval > 0) {
                                    $cm->marktemp4Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktemp4off($thisdevice->vehicleid, $thisdevice->customerno, '_sms');
                        }
                    } elseif ($temp4 == '0' && $thisdevice->temp4_status_sms == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktemp4Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                }
            }
        }
        //</editor-fold>
        // <editor-fold defaultstate="collapsed" desc="For Advance range of Email">
        if (isset($thisdevice->temp1_min_email)) {
            $cust_array = explode(',', speedConstants::TEMP_CONFLICT_BUZZER_CUSTOMER);
            $Is_Buzzer_Set = FALSE;
            if (in_array($thisdevice->customerno, $cust_array)) {
                $Is_Buzzer_Set = TRUE;
            }
            if ($thisdevice->status != 'H' || $thisdevice->status != 'F') {
                $um = new UnitManager($thisdevice->customerno);
                $temp_coversion = new TempConversion();
                $temp_coversion->unit_type = $thisdevice->get_conversion;
                $temp_coversion->use_humidity = $thisdevice->use_humidity;
                $temp_coversion->switch_to = 0;
                if ($thisdevice->kind == 'Warehouse') {
                    $temp_coversion->switch_to = 3;
                }
                $timeFirst = strtotime($thisdevice->lastupdated);
                $temp_interval = $cm->getTempInterval($thisdevice->customerno);

                if ($thisdevice->temp_sensors == 1) {
                    $differenceInMin = 0;
                    if ($thisdevice->temp1_intv != '0000-00-00 00:00:00') {
                        $timeSecond = strtotime($thisdevice->temp1_intv);
                        $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                    }
                    $tempcheck = '0';
                    $s = "analog" . $thisdevice->tempsen1;
                    if ($thisdevice->tempsen1 != 0 && $thisdevice->$s != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s;
                        $tempcheck = checktemp($temp_coversion, $thisdevice->temp1_min_email, $thisdevice->temp1_max_email, $thisdevice->temp1_mute);
                        $temp = getTempUtil($temp_coversion);
                    } else {
                        $tempcheck = '-';
                    }
                    if (($tempcheck == '1,1' || $tempcheck == '-1,0')) {
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 1;
                        if ($tempcheck == '1,1' && $temp1_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature is above " . $thisdevice->temp1_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temp . "]";
                        } elseif ($tempcheck == '-1,0' && $temp1_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature is below " . $thisdevice->temp1_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temp . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp1_intv == '0000-00-00 00:00:00') {
                                $cm->marktempInton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp1_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                    if ($Is_Buzzer_Set) {
                                        $command = new stdClass();
                                        $command->uid = $thisdevice->uid;
                                        $command->command = 'BUZZ=10';
                                        $um->setCommand($command);
                                    }
                                    $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                            }
                        }
                        if (!$temp1_above_include || !$temp1_below_include) {
                            $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($tempcheck == '0' && $thisdevice->temp_status_email == 1) {
                        if ($temp1_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - Temperature is between  " . $thisdevice->temp1_min_email . "&deg; C and " . $thisdevice->temp1_max_email . "&deg; C[" . $temp . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 1;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $cqm->InsertQ($cvo);
                                $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                if ($temp_interval > 0) {
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($tempcheck == '0' && $thisdevice->temp_status_email == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                }

                // Temperature Sensor 2
                if ($thisdevice->temp_sensors == 2) {
                    $temp1 = '0';
                    $temp2 = '0';
                    $s1 = "analog" . $thisdevice->tempsen1;
                    if ($thisdevice->tempsen1 != 0 && $thisdevice->$s1 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s1;
                        $temp1 = checktemp($temp_coversion, $thisdevice->temp1_min_email, $thisdevice->temp1_max_email, $thisdevice->temp1_mute);
                        $temps1 = getTempUtil($temp_coversion);
                    } else {
                        $temp1 = '-';
                    }
                    $s2 = "analog" . $thisdevice->tempsen2;
                    if ($thisdevice->tempsen2 != 0 && $thisdevice->$s2 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s2;
                        $temp2 = checktemp($temp_coversion, $thisdevice->temp2_min_email, $thisdevice->temp2_max_email, $thisdevice->temp2_mute);
                        $temps2 = getTempUtil($temp_coversion);
                    } else {
                        $temp2 = '-';
                    }
                    if (($temp1 == '1,1' || $temp1 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp1_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp1_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 1;
                        if ($temp1 == '1,1' && $temp1_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is above " . $thisdevice->temp1_max_email . "&deg; C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        } elseif ($temp1 == '-1,0' && $temp1_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is below " . $thisdevice->temp1_min_email . "&deg; C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp1_intv == '0000-00-00 00:00:00') {
                                $cm->marktempInton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp1_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                    if ($Is_Buzzer_Set) {
                                        $command = new stdClass();
                                        $command->uid = $thisdevice->uid;
                                        $command->command = 'BUZZ=10';
                                        $um->setCommand($command);
                                    }
                                    $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                            }
                        }
                        if (!$temp1_above_include || !$temp1_below_include) {
                            $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp1 == '0' && $thisdevice->temp_status_email == 1) {
                        if ($temp1_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is between " . $thisdevice->temp1_min_email . " &deg;C and " . $thisdevice->temp1_max_email . "&deg; C[" . $temps1 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 1;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $cqm->InsertQ($cvo);
                                $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                if ($temp_interval > 0) {
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp1 == '0' && $thisdevice->temp_status_email == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }

                    if (($temp2 == '1,1' || $temp2 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp2_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp2_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 2;
                        if ($temp2 == '1,1' && $temp2_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is above " . $thisdevice->temp2_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        } elseif ($temp2 == '-1,0' && $temp2_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is below " . $thisdevice->temp2_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp2_intv == '0000-00-00 00:00:00') {
                                $cm->marktemp2Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp2_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                    if ($Is_Buzzer_Set) {
                                        $command = new stdClass();
                                        $command->uid = $thisdevice->uid;
                                        $command->command = 'BUZZ=10';
                                        $um->setCommand($command);
                                    }
                                    $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                    $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp2_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                            }
                        }
                        if (!$temp2_above_include || !$temp2_below_include) {
                            $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp2 == '0' && $thisdevice->temp2_status_email == 1) {
                        if ($temp2_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is between " . $thisdevice->temp2_min_email . "&deg;C and " . $thisdevice->temp2_max_email . "&deg;C[" . $temps2 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 2;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $cqm->InsertQ($cvo);
                                $cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                if ($temp_interval > 0) {
                                    $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp2 == '0' && $thisdevice->temp2_status_email == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                }

                if ($thisdevice->temp_sensors == 3) {
                    $temp1 = '0';
                    $temp2 = '0';
                    $temp3 = '0';
                    $s1 = "analog" . $thisdevice->tempsen1;
                    if ($thisdevice->tempsen1 != 0 && $thisdevice->$s1 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s1;
                        $temp1 = checktemp($temp_coversion, $thisdevice->temp1_min_email, $thisdevice->temp1_max_email, $thisdevice->temp1_mute);
                        $temps1 = getTempUtil($temp_coversion);
                    } else {
                        $temp1 = '-';
                    }
                    $s2 = "analog" . $thisdevice->tempsen2;
                    if ($thisdevice->tempsen2 != 0 && $thisdevice->$s2 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s2;
                        $temp2 = checktemp($temp_coversion, $thisdevice->temp2_min_email, $thisdevice->temp2_max_email, $thisdevice->temp2_mute);
                        $temps2 = getTempUtil($temp_coversion);
                    } else {
                        $temp2 = '-';
                    }
                    $s3 = "analog" . $thisdevice->tempsen3;
                    if ($thisdevice->tempsen3 != 0 && $thisdevice->$s3 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s3;
                        $temp3 = checktemp($temp_coversion, $thisdevice->temp3_min_email, $thisdevice->temp3_max_email, $thisdevice->temp3_mute);
                        $temps3 = getTempUtil($temp_coversion);
                    } else {
                        $temp3 = '-';
                    }
                    if (($temp1 == '1,1' || $temp1 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp1_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp1_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 1;
                        if ($temp1 == '1,1' && $temp1_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is above " . $thisdevice->temp1_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        } elseif ($temp1 == '-1,0' && $temp1_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is below " . $thisdevice->temp1_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp1_intv == '0000-00-00 00:00:00') {
                                $cm->marktempInton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp1_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                    $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                            }
                        }
                        if (!$temp1_above_include || !$temp1_below_include) {
                            $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp1 == '0' && $thisdevice->temp_status_email == 1) {
                        if ($temp1_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 1 is between " . $thisdevice->temp1_min_email . "&deg;C and " . $thisdevice->temp1_max_email . "&deg;C[" . $temps1 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 1;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $cqm->InsertQ($cvo);
                                $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                if ($temp_interval > 0) {
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp1 == '0' && $thisdevice->temp_status_email == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                    if (($temp2 == '1,1' || $temp2 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp2_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp2_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 2;
                        if ($temp2 == '1,1' && $temp2_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is above " . $thisdevice->temp2_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        } elseif ($temp2 == '-1,0' && $temp2_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is below " . $thisdevice->temp2_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp2_intv == '0000-00-00 00:00:00') {
                                $cm->marktemp2Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp2_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                    $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                    $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp2_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                            }
                        }
                        if (!$temp2_above_include || !$temp2_below_include) {
                            $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp2 == '0' && $thisdevice->temp2_status_email == 1) {
                        if ($temp2_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 2 is between " . $thisdevice->temp2_min_email . "&deg;C and " . $thisdevice->temp2_max_email . "&deg;C[" . $temps2 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 2;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $cqm->InsertQ($cvo);
                                $cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                if ($temp_interval > 0) {
                                    $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp2 == '0' && $thisdevice->temp2_status_email == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }

                    if (($temp3 == '1,1' || $temp3 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp3_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp3_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 3;
                        if ($temp3 == '1,1' && $temp3_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 3 is above " . $thisdevice->temp3_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        } elseif ($temp3 == '-1,0' && $temp3_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 3 is below " . $thisdevice->temp3_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp3_intv == '0000-00-00 00:00:00') {
                                $cm->marktemp3Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp3_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                    $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                    $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp3_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                            }
                        }
                        if (!$temp3_above_include || !$temp3_below_include) {
                            $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp3 == '0' && $thisdevice->temp3_status_email == 1) {
                        if ($temp3_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - Temperature 3 is between " . $thisdevice->temp3_min_email . "&deg;C and " . $thisdevice->temp3_max_email . "&deg;C[" . $temps3 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 3;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $cqm->InsertQ($cvo);
                                $cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                if ($temp_interval > 0) {
                                    $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp3 == '0' && $thisdevice->temp3_status_email == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                }

                if ($thisdevice->temp_sensors == 4) {
                    $temp1 = '0';
                    $temp2 = '0';
                    $temp3 = '0';
                    $temp4 = '0';
                    $t1 = $cm->getNameForTempCron($thisdevice->n1, $thisdevice->customerno);
                    if ($t1 == '') {
                        $t1 = 'Temperature 1';
                    }
                    $t2 = $cm->getNameForTempCron($thisdevice->n2, $thisdevice->customerno);
                    if ($t2 == '') {
                        $t2 = 'Temperature 2';
                    }
                    $t3 = $cm->getNameForTempCron($thisdevice->n3, $thisdevice->customerno);
                    if ($t3 == '') {
                        $t3 = 'Temperature 3';
                    }
                    $t4 = $cm->getNameForTempCron($thisdevice->n4, $thisdevice->customerno);
                    if ($t4 == '') {
                        $t4 = 'Temperature 4';
                    }
                    $s1 = "analog" . $thisdevice->tempsen1;
                    if ($thisdevice->tempsen1 != 0 && $thisdevice->$s1 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s1;
                        $temp1 = checktemp($temp_coversion, $thisdevice->temp1_min_email, $thisdevice->temp1_max_email, $thisdevice->temp1_mute);
                        $temps1 = getTempUtil($temp_coversion);
                    } else {
                        $temp1 = '-';
                    }
                    $s2 = "analog" . $thisdevice->tempsen2;
                    if ($thisdevice->tempsen2 != 0 && $thisdevice->$s2 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s2;
                        $temp2 = checktemp($temp_coversion, $thisdevice->temp2_min_email, $thisdevice->temp2_max_email, $thisdevice->temp2_mute);
                        $temps2 = getTempUtil($temp_coversion);
                    } else {
                        $temp2 = '-';
                    }
                    $s3 = "analog" . $thisdevice->tempsen3;
                    if ($thisdevice->tempsen3 != 0 && $thisdevice->$s3 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s3;
                        $temp3 = checktemp($temp_coversion, $thisdevice->temp3_min_email, $thisdevice->temp3_max_email, $thisdevice->temp3_mute);
                        $temps3 = getTempUtil($temp_coversion);
                    } else {
                        $temp3 = '-';
                    }
                    $s4 = "analog" . $thisdevice->tempsen4;
                    if ($thisdevice->tempsen4 != 0 && $thisdevice->$s4 != 0) {
                        $temp_coversion->rawtemp = $thisdevice->$s4;
                        $temp4 = checktemp($temp_coversion, $thisdevice->temp4_min_email, $thisdevice->temp4_max_email, $thisdevice->temp4_mute);
                        $temps4 = getTempUtil($temp_coversion);
                    } else {
                        $temp4 = '-';
                    }
                    if (($temp1 == '1,1' || $temp1 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp1_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp1_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 1;
                        if ($temp1 == '1,1' && $temp1_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t1 . " is above " . $thisdevice->temp1_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        } elseif ($temp1 == '-1,0' && $temp1_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t1 . " is below " . $thisdevice->temp1_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps1 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp1_intv == '0000-00-00 00:00:00') {
                                $cm->marktempInton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp1_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                    $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                            }
                        }
                        if (!$temp1_above_include || !$temp1_below_include) {
                            $cm->marktempon($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp1 == '0' && $thisdevice->temp_status_email == 1) {
                        if ($temp1_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - " . $t1 . " is between " . $thisdevice->temp1_min_email . "&deg;C and " . $thisdevice->temp1_max_email . "&deg;C[" . $temps1 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 1;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $cqm->InsertQ($cvo);
                                $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                if ($temp_interval > 0) {
                                    $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktempoff($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp1 == '0' && $thisdevice->temp_status_email == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktempIntoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                    if (($temp2 == '1,1' || $temp2 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp2_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp2_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 2;
                        if ($temp2 == '1,1' && $temp2_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t2 . " is above " . $thisdevice->temp2_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        } elseif ($temp2 == '-1,0' && $temp2_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t2 . " is below " . $thisdevice->temp2_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps2 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp2_intv == '0000-00-00 00:00:00') {
                                $cm->marktemp2Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp2_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                    $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                    $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp2_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                            }
                        }
                        if (!$temp2_above_include || !$temp2_below_include) {
                            $cm->marktemp2on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp2 == '0' && $thisdevice->temp2_status_email == 1) {
                        if ($temp2_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - " . $t2 . " is between " . $thisdevice->temp2_min_email . "&deg;C and " . $thisdevice->temp2_max_email . "&deg;C[" . $temps2 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 2;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $cqm->InsertQ($cvo);
                                $cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                if ($temp_interval > 0) {
                                    $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktemp2off($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp2 == '0' && $thisdevice->temp2_status_email == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktemp2Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }

                    if (($temp3 == '1,1' || $temp3 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp3_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp3_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 3;
                        if ($temp3 == '1,1' && $temp3_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t3 . " is above " . $thisdevice->temp3_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        } elseif ($temp3 == '-1,0' && $temp3_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t3 . " is below " . $thisdevice->temp3_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps3 . "]";
                        }

                        if ($temp_interval > 0) {
                            if ($thisdevice->temp3_intv == '0000-00-00 00:00:00') {
                                $cm->marktemp3Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp3_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                    $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                    $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp3_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                            }
                        }
                        if (!$temp3_above_include || !$temp3_below_include) {
                            $cm->marktemp3on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp3 == '0' && $thisdevice->temp3_status_email == 1) {
                        if ($temp3_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - " . $t3 . " is between " . $thisdevice->temp3_min_email . "&deg;C and " . $thisdevice->temp3_max_email . "&deg;C[" . $temps3 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 3;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $cqm->InsertQ($cvo);
                                $cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                if ($temp_interval > 0) {
                                    $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktemp3off($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp3 == '0' && $thisdevice->temp3_status_email == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktemp3Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                    if (($temp4 == '1,1' || $temp4 == '-1,0')) {
                        // Populate communication queue
                        $differenceInMin = 0;
                        if ($thisdevice->temp4_intv != '0000-00-00 00:00:00') {
                            $timeSecond = strtotime($thisdevice->temp4_intv);
                            $differenceInMin = round(($timeFirst - $timeSecond) / 60);
                        }
                        $cqm = new ComQueueManager();
                        $cvo = new VOComQueue();
                        $cvo->customerno = $thisdevice->customerno;
                        $cvo->lat = $thisdevice->devicelat;
                        $cvo->long = $thisdevice->devicelong;
                        $cvo->type = 8;
                        $cvo->status = 1;
                        $cvo->vehicleid = $thisdevice->vehicleid;
                        $cvo->tempSensor = 4;
                        if ($temp4 == '1,1' && $temp4_above_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t4 . " is above " . $thisdevice->temp4_max_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps4 . "]";
                        } elseif ($temp4 == '-1,0' && $temp4_below_include) {
                            $cvo->message = $thisdevice->vehicleno . " - " . $t4 . " is below " . $thisdevice->temp4_min_email . "&deg;C";
                            if ($temp_interval > 0) {
                                $cvo->message .= " for more than " . $temp_interval . " mins";
                            }
                            $cvo->message .= "[" . $temps4 . "]";
                        }
                        if ($temp_interval > 0) {
                            if ($thisdevice->temp4_intv == '0000-00-00 00:00:00') {
                                $cm->marktemp4Inton($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                //$cm->marktemp4off($thisdevice->vehicleid, $thisdevice->customerno);
                            } elseif ($thisdevice->temp4_intv != '0000-00-00 00:00:00' && $differenceInMin > $temp_interval) {
                                if (isset($cvo->message) && $cvo->message != '') {
                                    $cqm->InsertQ($cvo);
                                    $cm->marktemp4on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                    $cm->marktemp4Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            if (isset($cvo->message) && $cvo->message != '' && $thisdevice->temp4_status_email == 0) {
                                $cqm->InsertQ($cvo);
                                if ($Is_Buzzer_Set) {
                                    $command = new stdClass();
                                    $command->uid = $thisdevice->uid;
                                    $command->command = 'BUZZ=10';
                                    $um->setCommand($command);
                                }
                                $cm->marktemp4on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                            }
                        }
                        if (!$temp4_above_include || !$temp4_below_include) {
                            $cm->marktemp4on($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp4 == '0' && $thisdevice->temp4_status_email == 1) {
                        if ($temp4_bet_include) {
                            // Populate communication queue
                            $cqm = new ComQueueManager();
                            $cvo = new VOComQueue();
                            $cvo->customerno = $thisdevice->customerno;
                            $cvo->lat = $thisdevice->devicelat;
                            $cvo->long = $thisdevice->devicelong;
                            $cvo->message = $thisdevice->vehicleno . " - " . $t4 . " is between " . $thisdevice->temp4_min_email . "&deg;C and " . $thisdevice->temp4_max_email . "&deg;C[" . $temps4 . "]";
                            $cvo->type = 8;
                            $cvo->status = 0;
                            $cvo->vehicleid = $thisdevice->vehicleid;
                            $cvo->tempSensor = 4;
                            if (isset($cvo->message) && $cvo->message != '') {
                                $cqm->InsertQ($cvo);
                                $cm->marktemp4off($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                                if ($temp_interval > 0) {
                                    $cm->marktemp4Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                                }
                            }
                        } else {
                            $cm->marktemp4off($thisdevice->vehicleid, $thisdevice->customerno, '_email');
                        }
                    } elseif ($temp4 == '0' && $thisdevice->temp4_status_email == 0) {
                        if ($temp_interval > 0) {
                            $cm->marktemp4Intoff($thisdevice->uid, $thisdevice->lastupdated, $thisdevice->customerno);
                        }
                    }
                }
            }
        }
        //</editor-fold>

        }
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
