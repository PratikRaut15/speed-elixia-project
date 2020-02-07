<?php

include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/model/TempConversion.php';

//if(!isset($_SESSION['timezone'])){
// $_SESSION['timezone'] = 'Asia/Kolkata';
//}
//date_default_timezone_set(''.$_SESSION['timezone'].'');






function diff($Today, $lastupdated) {

    $diff = abs($Today - $lastupdated);

    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
    $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
    $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
    if ($days > 0) {
        $difference = date("D d-M-Y H:i", $lastupdated);
    } else {
        if ($hours > 0) {
            $difference = $hours . " hr " . $minutes . " min ";
        } elseif ($minutes > 0) {
            $difference = $minutes . " min ";
        } else {
            $difference = $seconds . " sec ago";
        }
    }
    return $difference;
}

function getstatus($stoppage_flag, $stoppage_transit_time, $lastupdated_store, $gpsfixed =null) {
    $ServerIST_less1 = new DateTime();
    $ServerIST_less1->modify('-60 minutes');
    $status = '';
    $lastupdated = new DateTime($lastupdated_store);
    if ($lastupdated < $ServerIST_less1) {
        $status = "Inactive";
    } elseif(isset($gpsfixed) && $gpsfixed == 'V'){
        $status = "GPS Signal Lost";
    } else {
        $diff = getduration($stoppage_transit_time);
        if ($stoppage_flag == '0') {
            $status = "Idle since<br> " . $diff;
        } else {
            $status .= "Running since<br> " . $diff;
        }
    }
    return $status;
}

function getduration($StartTime) {
    $EndTime = date('Y-m-d H:i:s');
//                echo $EndTime.'_'.$StartTime.'<br>';
    $idleduration = strtotime($EndTime) - strtotime($StartTime);
    $years = floor($idleduration / (365 * 60 * 60 * 24));
    $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
    $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
    $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
    if ($years >= '1' || $months >= '1') {
        $diff = date('d-m-Y', strtotime($StartTime));
    } else if ($days > 0) {
        $diff = $days . ' Days ' . $hours . ' hrs ago';
    } else if ($hours > 0) {
        $diff = $hours . ' hrs and ' . $minutes . ' mins ago';
    } else if ($minutes > 0) {
        $diff = $minutes . ' mins ago';
    } else {
        $seconds = strtotime($EndTime) - strtotime($StartTime);
        $diff = $seconds . ' sec ago';
    }
    return $diff;
}

function vehicleimage($device) {
//    $images = "../../images/warehouse.png";
//    return $images;
    $temp_coversion = new TempConversion();
    $temp_coversion->unit_type = $device->get_conversion;
    $date = new Date();
    $basedir = "../../images/vehicles/";
    $directionfile = round((int)$device->directionchange / 10);
    if ($device->type == 'Car' || $device->type == 'Cab') {
        $device->type = 'Car';

        $ServerIST_less1 = new DateTime();
        $ServerIST_less1->modify('-60 minutes');
        $lastupdated = new DateTime($device->lastupdated);
        if ($lastupdated < $ServerIST_less1) {
            $image = $device->type . "/Idle/" . $device->type . $directionfile . ".png";
        } elseif ($device->ignition == '0') {

            $image = $device->type . "/IdleIgnOff/" . $device->type . $directionfile . ".png";
        } else {
            //<editor-fold defaultstate="collapsed" desc="Commented out code for temp sensors conflict">
            /*
              if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
              $temp = '';
              $s = "analog" . $device->tempsen1;
              if ($device->tempsen1 != 0 && $device->$s != 0) {
              $temp = gettemp($device->$s);
              } else
              $temp = '';

              if ($temp != '' && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
              $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
              } else if ($device->curspeed > $device->overspeed_limit) {
              $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
              } else {
              if ($device->stoppage_flag == '0') {
              $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
              } else {
              $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
              }
              }
              }
              else if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
              $temp1 = '';
              $temp2 = '';

              $s = "analog" . $device->tempsen1;
              if ($device->tempsen1 != 0 && $device->$s != 0) {
              $temp1 = gettemp($vehicle->$s);
              } else
              $temp1 = '';

              $s = "analog" . $device->tempsen2;
              if ($device->tempsen2 != 0 && $device->$s != 0) {
              $temp2 = gettemp($device->$s);
              } else
              $temp2 = '';

              if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max)) {
              $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
              } else if ($temp2 != '' && ($temp2 < $vehicle->temp2_min || $temp2 > $vehicle->temp2_max)) {
              $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
              } else if ($device->curspeed > $device->overspeed_limit) {
              $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
              } else {
              if ($device->stoppage_flag == '0') {
              $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
              } else {
              $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
              }
              }
              }
              else
             */
            //</editor-fold>
            if ($device->curspeed > $device->overspeed_limit) {
                $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
            } else {
                if ($device->stoppage_flag == '0') {
                    $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                } else {
                    $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
                }
            }
        }
    } else if ($device->type == 'Bus') {
        $device->type = 'Bus';

        $ServerIST_less1 = new DateTime();
        $ServerIST_less1->modify('-60 minutes');
        $lastupdated = new DateTime($device->lastupdated);
        if ($lastupdated < $ServerIST_less1) {
            $image = $device->type . "/Idle/" . $device->type . $directionfile . ".png";
        } elseif ($device->ignition == '0') {

            $image = $device->type . "/IdleIgnOff/" . $device->type . $directionfile . ".png";
        } else {
            //<editor-fold defaultstate="collapsed" desc="Commented out code for temp sensors conflict">
            /*
              if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
              $temp = '';
              $s = "analog" . $device->tempsen1;
              if ($device->tempsen1 != 0 && $device->$s != 0) {
              $temp = gettemp($device->$s);
              } else
              $temp = '';

              if ($temp != '' && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
              $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
              } else if ($device->curspeed > $device->overspeed_limit) {
              $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
              } else {
              if ($device->stoppage_flag == '0') {
              $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
              } else {
              $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
              }
              }
              }
              else if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
              $temp1 = '';
              $temp2 = '';

              $s = "analog" . $device->tempsen1;
              if ($device->tempsen1 != 0 && $device->$s != 0) {
              $temp1 = gettemp($vehicle->$s);
              } else
              $temp1 = '';

              $s = "analog" . $device->tempsen2;
              if ($device->tempsen2 != 0 && $device->$s != 0) {
              $temp2 = gettemp($device->$s);
              } else
              $temp2 = '';

              if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max)) {
              $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
              } else if ($temp2 != '' && ($temp2 < $vehicle->temp2_min || $temp2 > $vehicle->temp2_max)) {
              $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
              } else if ($device->curspeed > $device->overspeed_limit) {
              $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
              } else {
              if ($device->stoppage_flag == '0') {
              $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
              } else {
              $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
              }
              }
              }
              else
             */
            //</editor-fold>
            if ($device->curspeed > $device->overspeed_limit) {
                $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
            } else {
                if ($device->stoppage_flag == '0') {
                    $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                } else {
                    $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
                }
            }
        }
    } else if ($device->type == 'Truck') {
        $device->type = 'Truck';

        $ServerIST_less1 = new DateTime();
        $ServerIST_less1->modify('-60 minutes');
        $lastupdated = new DateTime($device->lastupdated);
        if ($lastupdated < $ServerIST_less1) {
            $image = $device->type . "/Idle/" . $device->type . $directionfile . ".png";
        } elseif ($device->ignition == '0') {

            $image = $device->type . "/IdleIgnOff/" . $device->type . $directionfile . ".png";
        } else {
            //<editor-fold defaultstate="collapsed" desc="Commented out code for temp sensors conflict">
            /*
              if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
              $temp = '';
              $s = "analog" . $device->tempsen1;
              if ($device->tempsen1 != 0 && $device->$s != 0) {
              $temp = gettemp($device->$s);
              } else
              $temp = '';

              if ($temp != '' && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
              $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
              } else if ($device->curspeed > $device->overspeed_limit) {
              $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
              } else {
              if ($device->stoppage_flag == '0') {
              $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
              } else {
              $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
              }
              }
              }
              else if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
              $temp1 = '';
              $temp2 = '';

              $s = "analog" . $device->tempsen1;
              if ($device->tempsen1 != 0 && $device->$s != 0) {
              $temp1 = gettemp($vehicle->$s);
              } else
              $temp1 = '';

              $s = "analog" . $device->tempsen2;
              if ($device->tempsen2 != 0 && $device->$s != 0) {
              $temp2 = gettemp($device->$s);
              } else
              $temp2 = '';

              if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max)) {
              $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
              } else if ($temp2 != '' && ($temp2 < $vehicle->temp2_min || $temp2 > $vehicle->temp2_max)) {
              $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
              } else if ($device->curspeed > $device->overspeed_limit) {
              $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
              } else {
              if ($device->stoppage_flag == '0') {
              $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
              } else {
              $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
              }
              }
              }
              else
             */
            //</editor-fold>
            if ($device->curspeed > $device->overspeed_limit) {
                $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
            } else {
                if ($device->stoppage_flag == '0') {
                    $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                } else {
                    $image = $device->type . "/Running/" . $device->type . $directionfile . ".png";
                }
            }
        }
    } else if ($device->type == 'Warehouse') {
        $device->type = 'Warehouse';

        $ServerIST_less1 = new DateTime();
        $ServerIST_less1->modify('-60 minutes');
        $lastupdated = new DateTime($device->lastupdated);
        if ($lastupdated < $ServerIST_less1) {
            $image = $device->type . "/inactive.png";
        } else {

            if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
                $temp = '';
                $s = "analog" . $device->tempsen1;
                if ($device->tempsen1 != 0 && $device->$s != 0) {
                    $temp_coversion->rawtemp = $device->$s;
                    $temp = getTempUtil($temp_coversion);
                } else
                    $temp = '';

                if ($temp != '' && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
                    $image = $device->type . "/conflict.png";
                } else {
                    $image = $device->type . "/on.png";
                }
            } else if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
                $temp1 = '';
                $temp2 = '';

                $s = "analog" . $device->tempsen1;
                if ($device->tempsen1 != 0 && $device->$s != 0) {
                    $temp_coversion->rawtemp = $device->$s;
                    $temp1 = getTempUtil($temp_coversion);
                } else
                    $temp1 = '';

                $s = "analog" . $device->tempsen2;
                if ($device->tempsen2 != 0 && $device->$s != 0) {
                    $temp_coversion->rawtemp = $device->$s;
                    $temp2 = getTempUtil($temp_coversion);
                } else
                    $temp2 = '';

                if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max)) {
                    $image = $device->type . "/conflict.png";
                } else if ($temp2 != '' && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max)) {
                    $image = $device->type . "/conflict.png";
                } else {
                    $image = $device->type . "/on.png";
                }
            } else {
                $image = $device->type . "/on.png";
            }
        }
    } else {
        if ($device->ignition == '0') {
            $image = $device->type . "/" . $device->type . "I.png";
        } else {
            if ($device->curspeed > $device->overspeed_limit) {
                $image = $device->type . "/" . $device->type . "O.png";
            } else {
                $image = $device->type . "/" . $device->type . "N.png";
            }
        }
    }
    $image = $basedir . $image;
    return $image;
}

function warehouseimage($device) {
//    $images = "../../images/warehouse.png";
//    return $images;
    $date = new Date();
    $basedir = "../../images/vehicles/";
    $image = "warehouse2.png";
    $image = $basedir . $image;
    return $image;
}
function getVehicleStatus($device) {
    $status = 0;
    $temp_coversion = new TempConversion();
    $temp_coversion->unit_type = $device->get_conversion;
    $date = new Date();
    $basedir = "../../images/vehicles/";
    $directionfile = round((int)$device->directionchange / 10);
    if ($device->type == 'Warehouse') {
        $ServerIST_less1 = new DateTime();
        $ServerIST_less1->modify('-60 minutes');
        $lastupdated = new DateTime($device->lastupdated);
        if ($lastupdated < $ServerIST_less1) {
            $image = $device->type . "/inactive.png";
        } else {
            if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
                $temp = '';
                $s = "analog" . $device->tempsen1;
                if ($device->tempsen1 != 0 && $device->$s != 0) {
                    $temp_coversion->rawtemp = $device->$s;
                    $temp = getTempUtil($temp_coversion);
                } else
                    $temp = '';
                if ($temp != '' && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
                    $status = "conflict";
                } else {
                    $status = "on";
                }
            } else if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
                $temp1 = '';
                $temp2 = '';
                $s = "analog" . $device->tempsen1;
                if ($device->tempsen1 != 0 && $device->$s != 0) {
                    $temp_coversion->rawtemp = $device->$s;
                    $temp1 = getTempUtil($temp_coversion);
                } else
                    $temp1 = '';
                $s = "analog" . $device->tempsen2;
                if ($device->tempsen2 != 0 && $device->$s != 0) {
                    $temp_coversion->rawtemp = $device->$s;
                    $temp2 = getTempUtil($temp_coversion);
                } else
                    $temp2 = '';
                if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max)) {
                    $status =  "conflict";
                } else if ($temp2 != '' && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max)) {
                    $status = "conflict";
                } else {
                    $status = "on";
                }
            } else {
                $status = "on";
            }
        }
    } else {
        $ServerIST_less1 = new DateTime();
        $ServerIST_less1->modify('-60 minutes');
        $lastupdated = new DateTime($device->lastupdated);
        if ($lastupdated < $ServerIST_less1) {
            $status = "Idle";
        } elseif ($device->ignition == '0') {
            $status = "IdleIgnOff";
        } else {
            if ($device->curspeed > $device->overspeed_limit) {
                $status = "Overspeed/" ;
            } else {
                if ($device->stoppage_flag == '0') {
                    $status = "Normal" ;
                } else {
                    $status = "Running" ;
                }
            }
        }
    }
    return $status ;
}
function vehicleimageSqlite($device) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vm = $vehiclemanager->getspeedlimit($device->vehicleid);
    $date = new Date();
    $basedir = "../../images/vehicles/";
    $directionfile = round($device->directionchange / 10);
    if ($device->type == 'Car' || $device->type == 'Cab') {
        $device->type = 'Car';
        if ($device->ignition == '0') {

            $image = $device->type . "/Idle/" . $device->type . $directionfile . ".png";
        } else {
            if ($device->curspeed > $vm->overspeed_limit) {
                $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
            } else {
                $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
            }
        }
    } else if ($device->type == 'Bus') {
        $device->type = 'Bus';
        if ($device->ignition == '0') {
            $image = $device->type . "/Idle/" . $device->type . $directionfile . ".png";
        } else {
            if ($device->curspeed > $vm->overspeed_limit) {
                $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
            } else {
                $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
            }
        }
    } else if ($device->type == 'Truck') {
        $device->type = 'Truck';
        if ($device->ignition == '0') {
            $image = $device->type . "/Idle/" . $device->type . $directionfile . ".png";
        } else {
            if ($device->curspeed > $vm->overspeed_limit) {
                $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
            } else {
                $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
            }
        }
    } else {
        if ($device->ignition == '0') {
            $image = $device->type . "/" . $device->type . "I.png";
        } else {
            if ($device->curspeed > $vm->overspeed_limit) {
                $image = $device->type . "/" . $device->type . "O.png";
            } else {
                $image = $device->type . "/" . $device->type . "N.png";
            }
        }
    }
    $image = $basedir . $image;
    return $image;
}

function vehicleimageidle($device) {
    $ServerIST_less1 = new DateTime();
    $ServerIST_less1->modify('-60 minutes');
    $lastupdated = new DateTime($device->lastupdated);

    $temp_coversion = new TempConversion();
    $temp_coversion->unit_type = $device->get_conversion;
    $temp_coversion->use_humidity = $_SESSION['use_humidity'];
    $temp_coversion->switch_to = $_SESSION['switch_to'];
    $basedir = "../../images/RTD/Vehicles/";
    $directionfile = round((int)$device->directionchange / 10);
    if ($device->type == 'Car' || $device->type == 'Cab' || $device->type == 'SUV') {
        $device->type = 'Car';
        if ($_SESSION['portable'] != '1') {
            if ($lastupdated < $ServerIST_less1) {
                $image = "Car/CarI.png";
            } else {
                if ($device->ignition == '0') {
                    $image = "Car/CarIOn.png";
                } else {

                    if ($device->curspeed > $device->overspeed_limit) {
                        $image = "Car/CarO.png";
                    } else {
                        if ($device->stoppage_flag == '0') {
                            $image = "Car/CarN.png";
                        } else {
                            $image = "Car/CarR.png";
                        }
                    }
                }
            }
        } else {
            $image = "Car/CarR.png";
        }
    } else if ($device->type == 'Bus') {
        $device->type = 'Bus';
        if ($_SESSION['portable'] != '1') {
            if ($lastupdated < $ServerIST_less1) {
                $image = "Bus/BusI.png";
            } else {
                if ($device->ignition == '0') {
                    $image = "Bus/BusIOn.png";
                } else {

                    if ($device->curspeed > $device->overspeed_limit) {
                        $image = "Bus/BusO.png";
                    } else {
                        if ($device->stoppage_flag == '0') {
                            $image = "Bus/BusN.png";
                        } else {
                            $image = "Bus/BusR.png";
                        }
                    }
                }
            }
        } else {
            $image = "Bus/BusR.png";
        }
    } else if ($device->type == 'Truck') {
        $device->type = 'Truck';
        if ($_SESSION['portable'] != '1') {
            if ($lastupdated < $ServerIST_less1) {
                $image = "Truck/TruckI.png";
            } else {
                if ($device->ignition == '0') {
                    $image = "Truck/TruckIOn.png";
                } else {


                    if ($device->curspeed > $device->overspeed_limit) {
                        $image = "Truck/TruckO.png";
                    } else {
                        if ($device->stoppage_flag == '0') {
                            $image = "Truck/TruckN.png";
                        } else {
                            $image = "Truck/TruckR.png";
                        }
                    }
                }
            }
        } else {
            $image = "Truck/TruckR.png";
        }
    } else if ($device->type == 'Warehouse') {
        $device->type = 'Warehouse';
        if ($lastupdated < $ServerIST_less1) {
            $image = "Warehouse/inactive.png";
        } else {
            if (isset($_SESSION['temp_sensors'])) {
                $image = "Warehouse/on.png";
                switch ($_SESSION['temp_sensors']) {
                    case 4:
                        $temp4 = '';
                        $s = "analog" . $device->tempsen4;
                        if ($device->tempsen4 != 0 && $device->$s != 0) {
                            $temp_coversion->rawtemp = $device->$s;
                            $temp4 = getTempUtil($temp_coversion);
                        }
                        if ($temp4 != 0 && ($temp4 < $device->temp4_min || $temp4 > $device->temp4_max) && $device->temp4_min != $device->temp4_max) {
                            $image = "Warehouse/conflict.png";
                        }
                    case 3:
                        $temp3 = '';
                        $s = "analog" . $device->tempsen3;
                        if ($device->tempsen3 != 0 && $device->$s != 0) {
                            $temp_coversion->rawtemp = $device->$s;
                            $temp3 = getTempUtil($temp_coversion);
                        }
                        if ($temp3 != 0 && ($temp3 < $device->temp3_min || $temp3 > $device->temp3_max) && $device->temp3_min != $device->temp3_max) {
                            $image = "Warehouse/conflict.png";
                        }
                    case 2:
                        $temp2 = '';
                        $s = "analog" . $device->tempsen2;
                        if ($device->tempsen2 != 0 && $device->$s != 0) {
                            $temp_coversion->rawtemp = $device->$s;
                            $temp2 = getTempUtil($temp_coversion);
                        }
                        if ($temp2 != 0 && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max) && $device->temp2_min != $device->temp2_max) {
                            $image = "Warehouse/conflict.png";
                        }

                    case 1:
                        $temp1 = '';
                        $s = "analog" . $device->tempsen1;
                        if ($device->tempsen1 != 0 && $device->$s != 0) {
                            $temp_coversion->rawtemp = $device->$s;
                            $temp1 = getTempUtil($temp_coversion);
                        }
                        if ($temp1 != 0 && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max) && $device->temp1_min != $device->temp1_max) {
                            $image = "Warehouse/conflict.png";
                        }
                }
            }
        }
    }
    $image = $basedir . $image;
    return $image;
}

function vehicleimage_maintenance($device) {
    $date = new Date();
    $ServerIST_less1 = strtotime(date("Y-m-d H:i:s"));
    $basedir = "../../images/vehicles/";
    $directionfile = round($device->directionchange / 10);
    $lastupdated2 = strtotime($device->lastupdated);
    $diffinmin = floor(round(abs($ServerIST_less1 - $lastupdated2) / 60, 2));
    if ($device->type == 'Car' || $device->type == 'Cab') {
        $device->type = 'Car';
        if ($device->ignition == '0') {
            if ($diffinmin >= '60') {
                $image = $device->type . "/Idle/break.png";
            } else {
                $image = $device->type . "/Idle/" . $device->type . $directionfile . ".png";
            }
        } else {
            if ($device->status == 'B' || $device->status == 'D') {
                if ($diffinmin >= '60') {
                    $image = $device->type . "/Idle/break.png";
                } else {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                }
            } else {
                if ($diffinmin >= '60') {
                    $image = $device->type . "/Idle/break.png";
                } else {
                    $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                }
            }
        }
    } else if ($device->type == 'Bus' || $device->type == 'Truck') {
        $device->type = 'Bus';
        if ($device->ignition == '0') {
            if ($diffinmin >= '60') {
                $image = $device->type . "/Idle/break.png";
            } else {
                $image = $device->type . "/Idle/" . $device->type . $directionfile . ".png";
            }
        } else {
            if ($device->status == 'B' || $device->status == 'D') {
                if ($diffinmin >= '60') {
                    $image = $device->type . "/Idle/break.png";
                } else {
                    $image = $device->type . "/Overspeed/" . $device->type . $directionfile . ".png";
                }
            } else {
                if ($diffinmin >= '60') {
                    $image = $device->type . "/Idle/break.png";
                } else {
                    $image = $device->type . "/Normal/" . $device->type . $directionfile . ".png";
                }
            }
        }
    } else {
        if ($device->ignition == '0') {
            $image = $device->type . "/" . $device->type . "I.png";
        } else {
            if ($device->status == 'B' || $device->status == 'D') {
                $image = $device->type . "/" . $device->type . "O.png";
            } else {
                $image = $device->type . "/" . $device->type . "N.png";
            }
        }
    }
    $image = $basedir . $image;
    return $image;
}

?>
