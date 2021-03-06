<?php
include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/DeviceManager.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/GeoCoder.php';
include_once '../../lib/comman_function/reports_func.php';

class VODatacap {}
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}
function getvehicles_overspeed() {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devicemanager->devicesformapping();
    return $devices;
}

function getunitno($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getuidfromdeviceid($deviceid);
    return $unitno;
}

function getunitnopdf($customerno, $deviceid) {
    $um = new UnitManager($customerno);
    $unitno = $um->getuidfromdeviceid($deviceid);
    return $unitno;
}

function getoverspeed_limit($deviceid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $overspeed_limit = $vm->getoverspeed_limit_deviceid($deviceid);
    return $overspeed_limit;
}

function getoverspeed_limit_pdf($customerno, $deviceid) {
    $vm = new VehicleManager($customerno);
    $overspeed_limit = $vm->getoverspeed_limit_deviceid($deviceid);
    return $overspeed_limit;
}

function getdate_IST() {
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}

function getoverspeedreport($STdate, $EDdate, $deviceid, $Shour, $Ehour) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $all_data = array();
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    $overspeed_limit = getoverspeed_limit($deviceid);
    $count = count($totaldays);
    $endelement = end($totaldays);
    $reports_Days = array_values($totaldays);
    $firstelement = array_shift($reports_Days);

    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (!file_exists($location)) {
                continue;
            }

            $location = "sqlite:" . $location;
            if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
                $main_data = getoverspeed_fromsqlite_graph($location, $deviceid, $overspeed_limit, $Shour, Null, $userdate);
            } elseif ($count > 1 && $userdate == $endelement) {
                $main_data = getoverspeed_fromsqlite_graph($location, $deviceid, $overspeed_limit, Null, $Ehour, $userdate);
            } elseif ($count == 1) {
                $main_data = getoverspeed_fromsqlite_graph($location, $deviceid, $overspeed_limit, $Shour, $Ehour, $userdate);
            } else {
                $main_data = getoverspeed_fromsqlite_graph($location, $deviceid, $overspeed_limit, Null, Null, $userdate);
            }

            $data = $main_data[0];
            $graph_data = $main_data[1];

            if (count($data) > 0) {
                $days = array_merge($days, $data);
            }
            if (count($graph_data) > 0) {
                $all_data = array_merge($all_data, $graph_data);
            }
        }
    }

    if (count($days) > 0) {
        $finalreport = create_overspeedhtml_from_report($days);
    } else {
        $finalreport = "<tr><td colspan=6 style='text-align:center;'>0 Vehicles crossed Overspeed Limit</td></tr>";
    }

    if (!empty($all_data)) {
        $graph_final_data = implode(',', $all_data);
    }
    return array($finalreport, $graph_final_data);
}

/**
 * To generate overspeed-pdf report of single vehicle
 */
function getoverspeedreportpdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $Shour, $Ehour, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $overspeed_limit = getoverspeed_limit_pdf($customerno, $deviceid);
    $count = count($totaldays);
    $endelement = end($totaldays);
    $firstelement = array_shift(array_values($totaldays));
    $finalreport = '';

    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (!file_exists($location)) {
                continue;
            }

            $location = "sqlite:" . $location;

            if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
                $data = getoverspeed_fromsqlite($location, $deviceid, $overspeed_limit, $Shour, Null, $userdate);
            } elseif ($count > 1 && $userdate == $endelement) {
                $data = getoverspeed_fromsqlite($location, $deviceid, $overspeed_limit, Null, $Ehour, $userdate);
            } elseif ($count == 1) {
                $data = getoverspeed_fromsqlite($location, $deviceid, $overspeed_limit, $Shour, $Ehour, $userdate);
            } else {
                $data = getoverspeed_fromsqlite($location, $deviceid, $overspeed_limit, Null, Null, $userdate);
            }

            if (count($data) > 0) {
                $days = array_merge($days, $data);
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $title = 'Overspeed Report';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate $Shour",
            "End Date: $EDdate $Ehour",
            "Overspeed Limit: $overspeed_limit Km/hr"
        );

        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }

        $finalreport = pdf_header($title, $subTitle);

        $finalreport .= "
        <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
        <tbody>";
        $finalreport .= create_overspeed_pdf_from_report($days, $customerno);
    }

    echo $finalreport;
}

function getoverspeedreportcsv($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $Shour, $Ehour, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $overspeed_limit = getoverspeed_limit_pdf($customerno, $deviceid);
    $count = count($totaldays);
    $endelement = end($totaldays);
    $firstelement = array_shift(array_values($totaldays));

    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (!file_exists($location)) {
                continue;
            }

            $location = "sqlite:" . $location;

            if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
                $data = getoverspeed_fromsqlite($location, $deviceid, $overspeed_limit, $Shour, Null, $userdate);
            } elseif ($count > 1 && $userdate == $endelement) {
                $data = getoverspeed_fromsqlite($location, $deviceid, $overspeed_limit, Null, $Ehour, $userdate);
            } elseif ($count == 1) {
                $data = getoverspeed_fromsqlite($location, $deviceid, $overspeed_limit, $Shour, $Ehour, $userdate);
            } else {
                $data = getoverspeed_fromsqlite($location, $deviceid, $overspeed_limit, Null, Null, $userdate);
            }

            if ($data != NULL && count($data) > 0) {
                $days = array_merge($days, $data);
            }
        }
    }

    if ($days != NULL && count($days) > 0) {
        $title = 'Overspeed Report';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate $Shour",
            "End Date: $EDdate $Ehour",
            "Overspeed Limit: $overspeed_limit Km/hr"
        );
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $finalreport = excel_header($title, $subTitle);
        $finalreport .= "
            <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
            <tbody>
            <tr style='background-color:#CCCCCC;font-weight:bold;'>
                <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                <td style='width:200px;height:auto; text-align: center;'>Location</td>
                <td style='width:100px;height:auto; text-align: center;'>Top Speed [km/hr]</td>
                <td style='width:100px;height:auto; text-align: center;'>Duration [sec]</td>
            </tr>";
        $finalreport .= create_overspeed_csv_from_report($days, $customerno);
    }

    echo $finalreport;
}

/**
 * To generate overspeed-pdf report of single vehicle
 */
function getoverspeedreportpdf_allveh($customerno, $inpdate, $all_vehicles, $user_details) {
    $userdate = date('Y-m-d', strtotime($inpdate));

    $finalreport = '';

    if (!empty($all_vehicles)) {
        $title = 'Overspeed Report';
        $subTitle = array(
            "Vehicle No: All Vehicles",
            "Start Date: $inpdate 00:00",
            "End Date: $inpdate 23:59",
            "Overspeed Limit: {$all_vehicles[0]->overspeed_limit} Km/hr"
        );
        echo pdf_header($title, $subTitle, $user_details);

        foreach ($all_vehicles as $vehicle) {
            $location = "../../customer/$customerno/unitno/$vehicle->unitno/sqlite/$userdate.sqlite";

            if (!file_exists($location)) {
                continue;
            }

            $location = "sqlite:" . $location;
            $data = getoverspeed_fromsqlite($location, $vehicle->deviceid, $vehicle->overspeed_limit, '00:00', '23:59', $userdate);

            if ($data != NULL && count($data) > 0) {
                $finalreport .= "
                <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>";
                $finalreport .= create_overspeed_pdf_from_report($data, $customerno, $vehicle->vehicleno);
            }
        }
    }

    echo $finalreport;
}

/**
 * To generate overspeed-pdf report of single vehicle
 */
function getoverspeedreportcsv_allveh($customerno, $inpdate, $all_vehicles, $user_details) {
    $userdate = date('Y-m-d', strtotime($inpdate));

    $finalreport = '';

    if (!empty($all_vehicles)) {
        $title = 'Overspeed Report';
        $subTitle = array(
            "Vehicle No: All Vehicles",
            "Start Date: $inpdate 00:00",
            "End Date: $inpdate 23:59",
            "Overspeed Limit: {$all_vehicles[0]->overspeed_limit} Km/hr"
        );
        echo excel_header($title, $subTitle, $user_details);

        foreach ($all_vehicles as $vehicle) {
            $location = "../../customer/$customerno/unitno/$vehicle->unitno/sqlite/$userdate.sqlite";

            if (!file_exists($location)) {
                continue;
            }

            $location = "sqlite:" . $location;
            $data = getoverspeed_fromsqlite($location, $vehicle->deviceid, $vehicle->overspeed_limit, '00:00', '23:59', $userdate);

            if ($data != NULL && count($data) > 0) {
                $finalreport .= "
            <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
            <tbody>
            <tr style='background-color:#CCCCCC;font-weight:bold;'>
                <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                <td style='width:200px;height:auto; text-align: center;'>Location</td>
                <td style='width:100px;height:auto; text-align: center;'>Top Speed [km/hr]</td>
                <td style='width:100px;height:auto; text-align: center;'>Duration [sec]</td>
            </tr>";
                $finalreport .= create_overspeed_csv_from_report($data, $customerno, $vehicle->vehicleno);
            }
        }
    }

    echo $finalreport;
}

function gendays($STdate, $EDdate) {
    $TOTALDAYS = Array();

    $STdate = date("Y-m-d", strtotime($STdate));
    $EDdate = date("Y-m-d", strtotime($EDdate));
    while (strtotime($STdate) <= strtotime($EDdate)) {
        $TOTALDAYS[] = $STdate;
        $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
    }
    return $TOTALDAYS;
}

function getoverspeed_fromsqlite($location, $deviceid, $overspeed_limit, $Shour, $Ehour, $userdate) {
    $devices = array();
    $query = "SELECT devicehistory.lastupdated, vehiclehistory.curspeed, vehiclehistory.vehicleid, devicehistory.devicelat, devicehistory.devicelong
              from devicehistory INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid AND devicehistory.status!='F'";
    if ($Shour != Null) {
        $query .= " AND devicehistory.lastupdated > '$userdate $Shour'";
    }
    if ($Ehour != Null) {
        $query .= " AND devicehistory.lastupdated < '$userdate $Ehour'";
    }
    $query .= "  ORDER BY devicehistory.lastupdated ASC";
    try
    {
        $database = new PDO($location);
        $result = $database->query($query);
        if (isset($result) && $result != "") {
            $overspeed_flag = 0;
            $lastspeed = 0;
            $pusharray = 0;
            foreach ($result as $row) {
                if ($row['curspeed'] > $overspeed_limit && $overspeed_flag == 0) {
                    $lastspeed = $row['curspeed'];
                    $overspeed_flag = 1;
                    $device = new VODatacap();
                    $device->curspeed = $row['curspeed'];
                    $device->starttime = $row['lastupdated'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->deviceid = $row['vehicleid'];
                } elseif ($overspeed_flag == 1 && $row['curspeed'] < $overspeed_limit) {
                    $overspeed_flag = 0;
                    $device->endtime = $row['lastupdated'];

                    $pusharray = 1;
                }
                if ($overspeed_flag == 1) {
                    if ($row['curspeed'] >= $lastspeed) {
                        $device->maxspeed = $row['curspeed'];
                        $lastspeed = $row['curspeed'];
                    }
                }
                if ($pusharray == 1) {
                    $devices[] = $device;
                    $pusharray = 0;
                }
            }
            if ($overspeed_flag == 1) {
                $device->endtime = $row['lastupdated'];
                $devices[] = $device;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    return $devices;
}

function set_overspeed_graph_data($updated_date, $speed) {
    $str_ch = strtotime($updated_date);
    $yr = date('Y', $str_ch);
    $mth = date('m', $str_ch) - 1;
    $day = date('d', $str_ch);
    $hour = date('H', $str_ch);
    $mins = date('i', $str_ch);

    return "[Date.UTC($yr, $mth, $day, $hour, $mins), $speed]";
}

function getoverspeed_fromsqlite_graph($location, $deviceid, $overspeed_limit, $Shour, $Ehour, $userdate) {
    $devices = array();
    $graph_devices = array();
    $query = "SELECT devicehistory.lastupdated, vehiclehistory.curspeed, vehiclehistory.vehicleid, devicehistory.devicelat, devicehistory.devicelong
              from devicehistory INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid AND devicehistory.status!='F'";
    if ($Shour != Null) {
        $query .= " AND devicehistory.lastupdated > '$userdate $Shour'";
    }
    if ($Ehour != Null) {
        $query .= " AND devicehistory.lastupdated < '$userdate $Ehour'";
    }
    $query .= "  ORDER BY devicehistory.lastupdated ASC";
    try
    {
        $database = new PDO($location);
        $result = $database->query($query);
        if (isset($result) && $result != "") {
            $overspeed_flag = 0;
            $lastspeed = 0;
            $pusharray = 0;
            $interval = 1;
            $lastupdated = "";
            foreach ($result as $row) {
                if ($lastupdated == "") {
                    $lastupdated = $row["lastupdated"];
                }
                //if($interval == 1){
                if (true) {
                    //echo $row['curspeed'].'===='.date('Y-m-d H:i:s', strtotime($row['lastupdated'])).'<br>';
                    $graph_devices[] = set_overspeed_graph_data($row['lastupdated'], $row['curspeed']);
                    $lastupdated = $row["lastupdated"];
                }
                /*elseif(round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60,2) > $interval){
                //echo $row['curspeed'].'===='.date('Y-m-d H:i:s', strtotime($row['lastupdated'])).'<br>';
                $graph_devices[] = set_overspeed_graph_data($row['lastupdated'], $row['curspeed']);
                $lastupdated = $row["lastupdated"];
                }*/

                if ($row['curspeed'] >= $overspeed_limit && $overspeed_flag == 0) {
                    $lastspeed = $row['curspeed'];
                    $overspeed_flag = 1;
                    $device = new VODatacap();
                    $device->curspeed = $row['curspeed'];
                    $device->starttime = $row['lastupdated'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->deviceid = $row['vehicleid'];
                } elseif ($overspeed_flag == 1 && $row['curspeed'] < $overspeed_limit) {
                    $overspeed_flag = 0;
                    $device->endtime = $row['lastupdated'];

                    $pusharray = 1;
                }
                if ($overspeed_flag == 1) {
                    if ($row['curspeed'] >= $lastspeed) {
                        $device->maxspeed = $row['curspeed'];
                        $lastspeed = $row['curspeed'];
                    }
                }
                if ($pusharray == 1) {
                    $devices[] = $device;
                    $pusharray = 0;
                }
            }
            if ($overspeed_flag == 1) {
                $device->endtime = $row['lastupdated'];
                $devices[] = $device;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    return array($devices, $graph_devices);
}

function create_overspeedhtml_from_report($datarows) {
    $display = $lastdate = '';
    $i = 0;

    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            $today = date('d-m-Y', strtotime('Now'));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                if ($today == $comparedate) {
                    $todays = date('Y-m-d');
                    $todayhms = date('Y-m-d H:i:s');
                    $to_time = strtotime("$todayhms");
                    $from_time = strtotime("$todays 00:00:00");
                    $totalminute = round(abs($to_time - $from_time) / 60, 2);
                } else {
                    $count = $i;
                }
                $display .= "<tr><th align='center' colspan = '100%' style='background:#d8d5d6'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $i++;
            }

            $currentdate = substr(date("Y-m-d H:i:s"), '0', 11);
            $test = strtotime($change->starttime);
            $display .= "<tr><td>" . date(speedConstants::DEFAULT_TIME, strtotime($change->starttime)) . "</td><td>" . date(speedConstants::DEFAULT_TIME, strtotime($change->endtime)) . "</td>";
            $use_geolocation = get_usegeolocation($_SESSION['customerno']);
            $location = location($change->devicelat, $change->devicelong, $use_geolocation);
            $display .= "<td>$location</td>";
            $display .= "<td>$change->maxspeed</td>";

            $secdiff = strtotime($change->endtime) - strtotime($change->starttime);
            $display .= "<td>$secdiff</td>";
            $display .= "<td><a id='added_$test' style='display:none;'><img src='../../images/added.png' alt='added as checkpoint' width='18' height='18'/></a>
                        <a href='#test_$test' id='add_$test' data-toggle='modal'><img src='../../images/add.png' alt='add as checkpoint' width='18' height='18'/></a> </td>";

            $display .= '</tr>';
            $display .= "<div id='test_$test' class='modal hide in' style='width:550px; height:350px; display:none;'>

                <form>
                <div class='modal-header'>
                <button class='close' data-dismiss='modal'>×</button>
                <h4 style='color:#0679c0'>Add Checkpoint</h4>
                </div>
                <div class='modal-body'>

                </br>
                <span class='add-on' style='color:#000000'>Enter Checkpoint Name</span>&nbsp;
                <input type='text' name='cname' id='cname_$test' value='' onkeyup='checkname($test)'/></br>
                <span id='checkpointarray_$test' style='display:none;'>Please Enter Checkpoint Name.</span>
                <span id='check_$test'></span>
                <input type='hidden' name='cadd' id='cadd_$test' value='$location'/>
                <input type='hidden' name='clat' id='lat_$test' value='$change->devicelat'/>
                <input type='hidden' name='clong' id='long_$test' value='$change->devicelong'/>
                <input type='hidden' name='device' id='getdevice_$test' value='$change->deviceid'/>
                <input type='hidden' name='last' id='lastup' value='$test'/>
                <input type='hidden' name='customer' id='customer' value='$_SESSION[customerno]'/>
                <input type='hidden' name='usreid' id='userid' value='$_SESSION[userid]'/>
                </div>

                </form>
                </div>";
        }
    }
    $display .= '</tbody>';
    $display .= '</table></div>';
    return $display;
}

function create_overspeed_pdf_from_report($datarows, $customerno, $vehicleno = '') {
    $display = '';
    $lastdate = '';
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                $display .= "</tbody></table>
                <hr  id='style-six' /><br/><table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse;'>
                <tbody>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $display .= "
                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                        <td style='width:100px;height:auto;'>Start Time</td>
                        <td style='width:100px;height:auto;'>End Time</td>
                        <td style='width:400px;height:auto;'>Location</td>
                        <td style='width:100px;height:auto;'>Top Speed [km/hr]</td>
                        <td style='width:100px;height:auto;'>Duration [sec]</td>
                    </tr>
                    <tr style='background-color:#d8d5d6;font-weight:bold;'><td colspan='5' >$vehicleno - Date " . date('d-m-Y', strtotime($change->endtime)) . "</td></tr>";
            }

            $display .= "<tr><td style='width:100px;height:auto;'>" . date(speedConstants::DEFAULT_TIME, strtotime($change->starttime)) . "</td><td style='width:100px;height:auto;'>" . date(speedConstants::DEFAULT_TIME, strtotime($change->endtime)) . "</td>";
            $use_geolocation = get_usegeolocation($customerno);
            $location = locationpdf($customerno, $change->devicelat, $change->devicelong, $use_geolocation);
            $display .= "<td style='width:400px;height:auto;'>" . $location . "</td>";
            $display .= "<td style='width:100px;height:auto;'>$change->maxspeed</td>";
            $secdiff = strtotime($change->endtime) - strtotime($change->starttime);
            $display .= "<td style='width:100px;height:auto;'>$secdiff</td>";
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= "</table>";
    if ($vehicleno == '') {
        $display .= "
	<page_footer>
                    [[page_cu]]/[[page_nb]]
    </page_footer>";
        $display .= "<hr style='margin-top:5px;'>";
        $formatdate1 = date(speedConstants::DEFAULT_DATETIME);
        $display .= "<div align='right' style='text-align:center;'> Report Generated On: $formatdate1 </div>";
    }
    return $display;
}

function location($lat, $long, $usegeolocation) {
    $address = NULL;
    if ($lat != '0' && $long != '0') {
        if ($usegeolocation == 1) {
            $API = "http://www.speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
            $location = json_decode(file_get_contents("$API&sensor=false"));
            @$address = "Near " . $location->results[0]->formatted_address;
            if ($location->results[0]->formatted_address == "") {
                $GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
                $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
            }
        } else {
            $GeoCoder_Obj = new GeoCoder($_SESSION['customerno']);
            $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
        }
    } else {
        $address = "Unable to fetch location";
    }
    return $address;
}

function locationpdf($customerno, $lat, $long, $usegeolocation) {
    $address = NULL;
    if ($lat != '0' && $long != '0') {
        if ($usegeolocation == 1) {
            $API = "http://www.speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
            $location = json_decode(file_get_contents("$API&sensor=false"));
            @$address = "Near " . $location->results[0]->formatted_address;
            if ($location->results[0]->formatted_address == "") {
                $GeoCoder_Obj = new GeoCoder($customerno);
                $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
            }
        } else {
            $GeoCoder_Obj = new GeoCoder($customerno);
            $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
        }
    } else {
        $address = "Unable to fetch location";
    }
    return $address;
}

function locationpdf_slow($customerno, $lat, $long, $usegeolocation) {
    $address = NULL;
    if ($lat != '0' && $long != '0') {
        if ($usegeolocation == 1) {
            $API = "http://www.speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
            $location = json_decode(file_get_contents("$API&sensor=false"));
            @$address = "Near " . $location->results[0]->formatted_address;
            if ($location->results[0]->formatted_address == "") {
                $GeoCoder_Obj = new GeoCoder($customerno);
                $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
            }
        } else {
            $GeoCoder_Obj = new GeoCoder($customerno);
            $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
        }
    } else {
        $address = "Unable to fetch location";
    }
    return $address;
}

function get_usegeolocation($customerno) {
    $GeoCoder_Obj = new GeoCoder($customerno);
    $geolocation = $GeoCoder_Obj->get_use_geolocation();
    return $geolocation;
}

function create_overspeed_csv_from_report($datarows, $customerno, $vehicleno = '') {
    $display = $lastdate = '';
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                $display .= "<tr><td style='width:335px;height:auto;font-weight:bold;text-align: center;background-color:#d8d5d6;' colspan='5'>$vehicleno-  $comparedate</td></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
            } else {
                $display .= "<tr>";
            }
            $display .= "<td style='width:50px;height:auto; text-align: center;'>" . date(speedConstants::DEFAULT_TIME, strtotime($change->starttime)) . "</td>
                    <td style='width:50px;height:auto;text-align=center;'>" . date(speedConstants::DEFAULT_TIME, strtotime($change->endtime)) . "</td>";
            $use_geolocation = get_usegeolocation($customerno);
            $location = locationpdf_slow($customerno, $change->devicelat, $change->devicelong, $use_geolocation);
            $display .= "<td style='width:300px;height:auto; text-align: center;'>" . $location . "</td>";
            $display .= "<td style='width:100px;height:auto; text-align: center;'>" . $change->maxspeed . "</td>";
            $secdiff = strtotime($change->endtime) - strtotime($change->starttime);
            $display .= "<td style='width:100px;height:auto; text-align: center;'>" . $secdiff . "</td>";
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= "</table>";
    return $display;
}

?>
