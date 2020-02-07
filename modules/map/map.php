<?php

$vehicleno = isset($_REQUEST['vehicleno']) ? $_REQUEST['vehicleno'] : '';
$userkey = isset($_GET['userkey']) ? $_GET['userkey'] : '';
$mapdetailsnone = "";
if (isset($_REQUEST['userkey']) && !empty($_REQUEST['userkey'])) {
    include_once '../user/user_functions.php';
    loginwithuserkey_map($_GET['userkey']);
    $_SESSION['switch_to'] = 0;
    if (isset($_REQUEST['vehicleno']) && $_REQUEST['vehicleno'] != "") {
        $vehicle = new VehicleManager($_SESSION['customerno']);
        $vehicledetails = $vehicle->get_all_vehicles_byId($vehicleno);
        if (isset($vehicledetails) && $vehicledetails != "") {
            $vehicleid = $vehicledetails[0]->vehicleid;
            $vehicleid = isset($vehicleid) ? $vehicleid : '';
        } else {
            echo "Oops Something Went wrong";die;
        }
        //$mapdetailsnone = ' mapdetailsnone ';
    }
    $mapdetailsnone = ' mapdetailsnone ';
    ?>
    <?php
}
?>
<style>
    .mapdetailsnone{
        display: none;
    }
</style>
<?php
include '../panels/header.php';
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['timezone'])) {
    $_SESSION['timezone'] = 'Asia/Kolkata';
}
date_default_timezone_set($_SESSION['timezone']);

?>

<div id="gc-topnav2"  class="ch_bar"  style="background-color:#ffffff;width:360px;height:auto; display:none;position:absolute; left:20%; z-index:100;">
    <div id="chk_box" style="width:350px; height:auto; float:left; text-align:left;">
        <a class="a" id="address"> Search </a>  <input type="text" name="chkA" id="chkA"  class="chkp_inp" style="width: 280px;">&nbsp;
    </div>
</div>
<!-- search div ends -->
<div id="panelmap" style="margin-top: 80px;">
    <div id="color-palette"></div>
    <div>
        <input type="button"  value="refresh" class="g-button g-button-submit" id="toggler1" onclick="refreshmap();" style="background:#000000;"  >
    </div>
</div>
<div id="map" class="map" style="float:left;  height:450px"></div>
<div style="clear: both;">&nbsp;</div>

<div id="displaydata">
    <div style="float: left;top: 25px;">
        <?php
echo '<input type="hidden" name="loginUserRole" id="loginUserRole" value="' . $_SESSION['Session_UserRole'] . '"/>';
if ($_SESSION['portable'] != '1') {
    ?>
            <?php
if (isset($devices1) && !empty($devices1) && $_SESSION['switch_to'] != 3) {
        $type = "";
        $a = 0;
        $b = 0;
        $c = 0;
        $d = 0;
        $e = 0;
        if (isset($devices1)) {
            foreach ($devices1 as $device) {
                // Pull Details for Count
                if ($device->type == 'Truck') {
                    $type = 'Truck';
                } elseif ($type == 'Truck') {
                    $type = 'Truck';
                }
                $ServerIST_less1 = new DateTime();
                $ServerIST_less1->modify('-60 minutes');
                $lastupdated = new DateTime($device->lastupdated_store);
                //$lastupdated = new DateTime($device->lastupdated);
                //$lastupdated = new DateTime($device->lastupdated);
                //echo $device->lastupdated;
                //echo $ServerIST_less1;
                if ($lastupdated < $ServerIST_less1) {
                    $e++;
                } else {
                    if ($device->ignition == '0') {
                        $d++;
                        //$image = $device->type . "/" . $device->type . "I.png";
                    } else {
                        if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
                            $temp = '';
                            $s = "analog" . $device->tempsen1;
                            if ($device->tempsen1 != 0 && $device->$s != 0) {
                                $temp = gettemp($device->$s);
                            } else {
                                $temp = '';
                            }
                            if ($device->curspeed > $device->overspeed_limit) {
                                $a++;
                            } else {
                                if ($device->stoppage_flag == '0') {
                                    $c++;
                                } else {
                                    $b++;
                                }
                            }
                        } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
                            $temp1 = '';
                            $temp2 = '';
                            $s = "analog" . $device->tempsen1;
                            if ($vehicle->tempsen1 != 0 && $device->$s != 0) {
                                $temp1 = gettemp($device->$s);
                            } else {
                                $temp1 = '';
                            }
                            $s = "analog" . $device->tempsen2;
                            if ($device->tempsen2 != 0 && $device->$s != 0) {
                                $temp2 = gettemp($device->$s);
                            } else {
                                $temp2 = '';
                            }
                            if ($device->curspeed > $device->overspeed_limit) {
                                $a++;
                            } else {
                                if ($device->stoppage_flag == '0') {
                                    $c++;
                                } else {
                                    $b++;
                                }
                            }
                        } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 3) {
                            $temp1 = '';
                            $temp2 = '';
                            $temp3 = '';
                            $s = "analog" . $device->tempsen1;
                            if ($device->tempsen1 != 0 && $device->$s != 0) {
                                $temp1 = gettemp($device->$s);
                            } else {
                                $temp1 = '';
                            }
                            $s = "analog" . $device->tempsen2;
                            if ($device->tempsen2 != 0 && $device->$s != 0) {
                                $temp2 = gettemp($device->$s);
                            } else {
                                $temp2 = '';
                            }
                            $s = "analog" . $device->tempsen3;
                            if ($device->tempsen3 != 0 && $device->$s != 0) {
                                $temp3 = gettemp($device->$s);
                            } else {
                                $temp3 = '';
                            }
                            if ($device->curspeed > $device->overspeed_limit) {
                                $a++;
                            } else {
                                if ($device->stoppage_flag == '0') {
                                    $c++;
                                } else {
                                    $b++;
                                }
                            }
                        } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 4) {
                            $temp1 = '';
                            $temp2 = '';
                            $temp3 = '';
                            $temp4 = '';
                            $s = "analog" . $device->tempsen1;
                            if ($vehicle->tempsen1 != 0 && $device->$s != 0) {
                                $temp1 = gettemp($device->$s);
                            } else {
                                $temp1 = '';
                            }
                            $s = "analog" . $device->tempsen2;
                            if ($device->tempsen2 != 0 && $device->$s != 0) {
                                $temp2 = gettemp($device->$s);
                            } else {
                                $temp2 = '';
                            }
                            $s = "analog" . $device->tempsen3;
                            if ($device->tempsen3 != 0 && $device->$s != 0) {
                                $temp3 = gettemp($device->$s);
                            } else {
                                $temp3 = '';
                            }
                            $s = "analog" . $device->tempsen4;
                            if ($device->tempsen4 != 0 && $device->$s != 0) {
                                $temp4 = gettemp($device->$s);
                            } else {
                                $temp4 = '';
                            }
                            if ($device->curspeed > $device->overspeed_limit) {
                                $a++;
                            } else {
                                if ($device->stoppage_flag == '0') {
                                    $c++;
                                } else {
                                    $b++;
                                }
                            }
                        } else {
                            if ($device->curspeed > $device->overspeed_limit) {
                                $a++;
                            } else {
                                if ($device->stoppage_flag == '0') {
                                    $c++;
                                } else {
                                    $b++;
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($type == 'Truck') {
            $transporter = 'Truck/Truck';
        } else {
            $transporter = 'Car/Car';
        }
        ?>
                <div class="lGb  <?php echo $mapdetailsnone; ?>" id="mapdetails"  style="margin-left: 12%; text-align: left;width:500px; ">
                    <div style="border-radius:4px;" class="b-s-t Ye outer-box Overspeed-box"><div class="zc bn ID-tooltipcontent-0"  onclick="setVehicleFilter('Overspeed');"><font color="#FF0000">Overspeed</font></div>
                        <div class="zt"><div><div class="eK"><font color="#FF0000"><?php echo $a; ?></font><img style="padding-left: 10px;" src="../../images/RTD/Vehicles/<?php echo $transporter; ?>O.png"></img></div></div></div>
                    </div>
                    <div style="border-radius:4px;" class="b-s-t Ye outer-box Running-box"><div class="zc bn ID-tooltipcontent-0"  onclick="setVehicleFilter('Running');"><font color="#009900">Running</font></div>
                        <div class="zt"><div><div class="eK"><font color="#009900"><?php echo $b; ?></font><img style="padding-left: 10px;" src="../../images/RTD/Vehicles/<?php echo $transporter; ?>R.png"></img></div></div></div>
                    </div>
                    <div style="border-radius:4px;" class="b-s-t Ye outer-box Idle-box"><div class="zc bn ID-tooltipcontent-0"  onclick="setVehicleFilter('Idle');"><font color="#0066FF">Idle - Ign On</font></div>
                        <div class="zt"><div><div class="eK"><font color="#0066FF"><?php echo $c; ?></font><img style="padding-left: 10px;" src="../../images/RTD/Vehicles/<?php echo $transporter; ?>N.png"></img></div></div></div>
                    </div>
                    <div style="border-radius:4px;" class="b-s-t Ye outer-box IdleIgnOff-box"><div class="zc bn ID-tooltipcontent-0" onclick="setVehicleFilter('IdleIgnOff');"><font color="#FF9900">Idle - Ign Off</font></div>
                        <div class="zt"><div><div class="eK"><font color="#FF9900"><?php echo $d; ?></font><img style="padding-left: 10px;" src="../../images/RTD/Vehicles/<?php echo $transporter; ?>IOn.png"></img></div></div></div>
                    </div>
                    <div style="border-radius:4px;" class="b-s-t Ye outer-box Inactive-box"><div class="zc bn ID-tooltipcontent-0"  onclick="setVehicleFilter('Inactive');"><font color="#707070">Inactive</font></div>
                        <div class="zt"><div><div class="eK"><font color="#707070"><?php echo $e; ?></font><img style="padding-left: 10px;" src="../../images/RTD/Vehicles/<?php echo $transporter; ?>I.png"></img></div></div></div>
                    </div>
                </div>
                <?php
}
}
?>
    </div>
    <div style="float: left; margin-left: 100px;top:-25px;">
        <?php
if ($_SESSION['portable'] != '1' && $_SESSION['use_warehouse'] == 1 && $_SESSION['switch_to'] == 3) {
    ?>
            <?php
if (isset($devices2) && !empty($devices2)) {
        $a = 0;
        $b = 0;
        $c = 0;
        foreach ($devices2 as $device) {
            //$ServerIST_less1 = new DateTime();
            //$ServerIST_less1->modify('690 minutes');
            $ServerIST_less1 = new DateTime();
            $ServerIST_less1->modify('-60 minutes');
            $lastupdated = new DateTime($device->lastupdated);
            if ($lastupdated < $ServerIST_less1) {
                $a++;
            } else {
                if (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 1) {
                    $temp = '';
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp = gettemp($device->$s);
                    } else {
                        $temp = '';
                    }

                    if ($temp != '' && ($temp < $device->temp1_min || $temp > $device->temp1_max)) {
                        $b++;
                    } else {
                        $c++;
                    }
                } elseif (isset($_SESSION['temp_sensors']) && $_SESSION['temp_sensors'] == 2) {
                    $temp1 = '';
                    $temp2 = '';
                    $s = "analog" . $device->tempsen1;
                    if ($device->tempsen1 != 0 && $device->$s != 0) {
                        $temp1 = gettemp($device->$s);
                    } else {
                        $temp1 = '';
                    }
                    $ss = "analog" . $device->tempsen2;
                    if ($device->tempsen2 != 0 && $device->$ss != 0) {
                        $temp2 = gettemp($device->$ss);
                    } else {
                        $temp2 = '';
                    }
                    //var_dump($temp2);echo "----";
                    //var_dump($temp2 != '' && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max));echo "----";
                    if ($temp1 != '' && ($temp1 < $device->temp1_min || $temp1 > $device->temp1_max)) {
                        $b++;
                    } elseif ($temp2 != '' && ($temp2 < $device->temp2_min || $temp2 > $device->temp2_max)) {
                        $b++;
                    } else {
                        //echo "c---";
                        $c++;
                    }
                } else {
                    $c++;
                }
            }
        }
        ?>
                <div class="lGb" id="mapdetails search_table" style="margin-left: 2%; text-align: left;width:500px;">
                    <div class="b-s-t Ye rate3 outer-box inactive-box" style="border-radius:4px;" id="2"><div class="zc bn ID-tooltipcontent-0" onclick="setVehicleFilter('inactive');"><font color="#000">Inactive</font></div>
                        <div class="zt"><div><div class="eK" ><font color="#000" id="inactive"  ><?php echo $a; ?></font><img style="padding-left: 1px;" src="../../images/RTD/Vehicles/Warehouse/inactive.png"></img></div></div></div>
                    </div>
                    <div class="b-s-t Ye rate3 outer-box on-box" style="border-radius:4px;" id="3"><div class="zc bn ID-tooltipcontent-0" onclick="setVehicleFilter('on');"><font color="#009900">On</font></div>
                        <div class="zt"><div><div class="eK" ><font color="#009900" id="w_on"><?php echo $c; ?></font><img style="padding-left: 1px;" src="../../images/RTD/Vehicles/Warehouse/on.png"></img></div></div></div>
                    </div>
                    <?php if ($_SESSION['customerno'] != speedConstants::CUSTNO_NXTDIGITAL) {?>
                        <div class="b-s-t Ye rate3 outer-box conflict-box" style="border-radius:4px;" id="1"><div class="zc bn ID-tooltipcontent-0" onclick="setVehicleFilter('conflict');"><font color="#FF0000">Conflict</font></div>
                            <div class="zt"><div><div class="eK" ><font color="#FF0000" id="conflict"><?php echo $b; ?></font><img style="padding-left: 1px;" src="../../images/RTD/Vehicles/Warehouse/conflict.png"></img></div></div></div>
                        </div>
                        <?php
}
    }
}
?>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>
<input type="hidden" name="userkey" id="userkey" value="<?php echo isset($userkey) ? $userkey : ''; ?>">
<input type="hidden" name="vehicleid_given" id="vehicleid_given" value="<?php echo isset($vehicleid) ? $vehicleid : ''; ?>">
<!-- Date: 17th nov 14, ak added, for add location popup-->
<?php include "../reports/pages/location_pop_html.php";?>
<script type="text/javascript">
    var customerrefreshfrqmap = <?php echo $_SESSION['customerno']; ?>;
</script>
<?php include '../panels/footer.php';?>
<?php
function gettemp($rawtemp) {
    $temp = round((($rawtemp - 1150) / 4.45), 1);
    return $temp;
}

?>