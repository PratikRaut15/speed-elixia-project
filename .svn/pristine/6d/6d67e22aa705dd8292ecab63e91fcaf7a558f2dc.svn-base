<?php
    include_once "../../lib/system/utilities.php";
    include_once '../../lib/autoload.php';
    include_once '../../lib/bo/PointLocationManager.php';
    include_once '../../lib/comman_function/reports_func.php';
    if (!isset($_SESSION)) {
        session_start();
        if (!isset($_SESSION['timezone'])) {
            $_SESSION['timezone'] = 'Asia/Kolkata';
        }
    } else {
        date_default_timezone_set('' . $_SESSION['timezone'] . '');
    }
    function get_location_detail($lat, $long, $customerno) {
        $geo_location = "N/A";
        if ($lat != "" && $long != "") {
            $geo_obj = new GeoCoder($customerno);
            $geo_location = $geo_obj->get_location_bylatlong($lat, $long, $customerno);
        }
        return $geo_location;
    }

    function getlocation($lat, $long, $geocode, $customerno) {
        $address = '';
        $usegeolocation = get_usegeolocation($customerno);
        $key = $lat . $long;
        if (!isset($GLOBALS[$key])) {
            if ($lat != '0' && $long != '0') {
                if (isset($_SESSION['Session_UserRole']) && $_SESSION['Session_UserRole'] == "elixir" && $geocode == "2") {
                    $API = "http://www.speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
                    $location = json_decode(file_get_contents($API . "&sensor=false"));
                    @$address = $location->results[0]->formatted_address;
                    //
                } else {
                    $GeoCoder_Obj = new GeoCoder($customerno);
                    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
                }
            }
            $output = $address;
            $GLOBALS[$key] = $address;
        } else {
            $output = $GLOBALS[$key];
        }
        return $output;
    }

    function get_usegeolocation($customerno) {
        $GeoCoder_Obj = new GeoCoder($customerno);
        $geolocation = $GeoCoder_Obj->get_use_geolocation();
        return $geolocation;
    }

    function get_location_detail_city($lat, $long, $customerno) {
        $geo_location = "N/A";
        if ($lat != "" && $long != "") {
            $geo_obj = new GeoCoder($customerno);
            $geo_location = $geo_obj->get_location_bylatlong_city($lat, $long, $customerno);
        }
        return $geo_location;
    }

    function get_vehicle_name($vehicleid) {
        $vehiclemanager = new vehiclemanager($_SESSION['customerno']);
        $vehicles = $vehiclemanager->get_vehicle_details($vehicleid);
        return $vehicles;
    }

    function get_vehicle_name_pdf($vehicleid, $customerno) {
        $vehiclemanager = new vehiclemanager($customerno);
        $vehicles = $vehiclemanager->get_vehicle_details_pdf($vehicleid, $customerno);
        return $vehicles;
    }

    function get_comquedata($vehicleid) {
        $comqueuemanager = new ComQueueManager($_SESSION['customerno']);
        $comqueuedatas = $comqueuemanager->getcomqueuedatafor_vehicle($vehicleid);
        return $comqueuedatas;
    }

    function get_vehicle_with_units() {
        $vehiclemanager = new vehiclemanager($_SESSION['customerno']);
        $vehicles = $vehiclemanager->get_all_vehicles_with_unitno();
        return $vehicles;
    }

    function get_vehicle_count() {
        $count = 0;
        $vehiclemanager = new vehiclemanager($_SESSION['customerno']);
        $count = $vehiclemanager->get_all_vehicles_count();
        return $count;
    }

    function get_vehicle_id_by_group() {
        $varray = array();
        $vehiclemanager = new vehiclemanager($_SESSION['customerno']);
        $vehicles = $vehiclemanager->get_all_vehicles();
        foreach ($vehicles as $vehicle) {
            $varray[] = $vehicle->vehicleid;
        }
        return $varray;
    }

    function get_vehicle_id_by_group_new($customerno) {
        $varray = array();
        $vehicles = groupBased_vehicles($customerno);
        if (!empty($vehicles)) {
            foreach ($vehicles as $vehicle) {
                $varray[] = $vehicle->vehicleid;
            }
        } else {
            $varray = array();
        }
        return $varray;
    }

    function get_vehicle_id_by_group_pdf($customerno) {
        $varray = array();
        $vehiclemanager = new vehiclemanager($customerno);
        $vehicles = $vehiclemanager->get_all_vehicles_pdf($customerno);
        foreach ($vehicles as $vehicle) {
            $varray[] = $vehicle->vehicleid;
        }
        return $varray;
    }

    function get_checkpoint($chkid) {
        $chkmanager = new CheckpointManager($_SESSION['customerno']);
        $checkpoints = $chkmanager->get_checkpoint($chkid);
        return $checkpoints;
    }

    function get_data_fordashboard($sdate, $edate) {
        $totaldays = gendays($sdate, $edate);
        $dailyReportChangeDate = "20-02-2015";
        $dailyReportChangeDate = strtotime($dailyReportChangeDate);
        $cm = new CustomerManager();
        $customer_details = $cm->getcustomerdetail_byid($_SESSION['customerno']);
        $location1 = "../../customer/" . $_SESSION['customerno'] . "/reports/dailyreport_new.sqlite";
        $location = "../../customer/" . $_SESSION['customerno'] . "/reports/dailyreport.sqlite";
        if (file_exists($location)) {
            if ($_SESSION['groupid'] != 0) {
                $VehicleManager = new VehicleManager($_SESSION['customerno']);
                $vehicles = $VehicleManager->get_groups_vehicles($_SESSION['groupid']);
                $vehicle_ids = vehicle_id_array($vehicles);
            } else {
                $vehicle_ids = vehicle_id_array(groupBased_vehicles_cron($_SESSION['customerno'], $_SESSION['userid']));
            }

            $dailyreportdata = GetDailyReport_Data($location, $location1, $totaldays, $vehicle_ids);
            html_wrapper($dailyreportdata, $sdate, $edate, $customer_details);
        } else {
            echo "File Not exists";
        }
    }

    function GetDailyReport_Data($location, $location1 = NULL, $days, $vehicle_array) {
        $dayscount = count($days);
        $totalmin = $dayscount * 24 * 60;
        $REPORT = array();
        /*
        Hard coded for new daily report for 2 weeks from 20-Feb-2015
        to 9-Mar-2015 due to new dailyreport table in MySQL
         */
        $startdate1 = "2015-02-20";
        $enddate1 = "2015-03-09";
        if (isset($days)) {
            foreach ($days as $day) {
                if (strtotime($day) >= strtotime($startdate1) && strtotime($day) <= strtotime($enddate1)) {
                    $path = "sqlite:$location1";
                    $db = new PDO($path);
                } else {
                    $path = "sqlite:$location";
                    $db = new PDO($path);
                }
                $sqlday = date("dmy", strtotime($day));
                $datechk = date("Y-m-d", strtotime($day));
                $query = "SELECT * from A$sqlday";
                $result = $db->query($query);
                if (isset($result) && $result != "") {
                    foreach ($result as $row) {
                        if (in_array($row['vehicleid'], $vehicle_array) == true) {
                            $Datacap = new stdClass();
                            $Datacap->uid = $row['uid'];
                            $Datacap->datechk = $datechk;
                            $Datacap->avgspeed = $row['avgspeed'];
                            $Datacap->genset = $row['genset'];
                            $Datacap->overspeed = $row['overspeed'];
                            $Datacap->totaldistance = $row['totaldistance'];
                            $Datacap->fenceconflict = $row['fenceconflict'];
                            $Datacap->idletime = $row['idletime'];
                            $Datacap->runningtime = $row['runningtime'];
                            $Datacap->vehicleid = $row['vehicleid'];
                            $Datacap->dev_lat = $row['dev_lat'];
                            $Datacap->dev_long = $row['dev_long'];
                            $Datacap->first_dev_lat = $row['first_dev_lat'];
                            $Datacap->first_dev_long = $row['first_dev_long'];
                            $Datacap->harsh_break = $row['harsh_break'];
                            $Datacap->sudden_acc = $row['sudden_acc'];
                            if ($row['towing'] == 0) {
                                $Datacap->towing = "No";
                            } else {
                                $Datacap->towing = "Yes";
                            }
                            $Datacap->overspeed = $row['overspeed'];
                            $Datacap->topspeed = $row['topspeed'];
                            $Datacap->topspeed_lat = $row['topspeed_lat'];
                            $Datacap->topspeed_long = $row['topspeed_long'];
                            $Datacap->avgdistance = $row['average_distance'];
                            $Datacap->idleignitionontime = isset($row['idleignitiontime']) ? $row['idleignitiontime'] : '';
                            $Datacap->idleignitionofftime = isset($row['idleignitiontime']) ? $totalmin - $row['idleignitiontime'] - $row['runningtime'] : '';
                            $Datacap->firstodometer = isset($row['first_odometer']) ? $row['first_odometer'] : '';
                            $Datacap->lastodometer = isset($row['last_odometer']) ? $row['last_odometer'] : '';
                            $cm = new CustomerManager();
                            $Datacap->groupname = $cm->getgroupname($row['uid']);
                            $REPORT[] = $Datacap;
                        }
                    }
                }
            }
        }
        return $REPORT;
    }

    function html_wrapper($data, $sdate, $edate, $customerdetails = null) {
        $SDATE = $sdate;
        $EDATE = $edate;
        $dailyReportChangeDate = "20-02-2015";
        $dailyReportChangeDate = strtotime($dailyReportChangeDate);
        $totaldays = gendays($sdate, $edate);
        $totaldaycount = count($totaldays);
        $finalReport = "";
        if (isset($data)) {
            $srno = 1;
            $array = json_decode(json_encode($data), true);
            $summarydata = array_reduce($array, function ($result, $currentItem) {
                if (isset($result[$currentItem['vehicleid']])) {
                    $result[$currentItem['vehicleid']]['totaldistance'] += $currentItem['totaldistance'];
                    $result[$currentItem['vehicleid']]['avgspeed'] += $currentItem['avgspeed'];
                    $result[$currentItem['vehicleid']]['overspeed'] += $currentItem['overspeed'];
                    $result[$currentItem['vehicleid']]['dev_lat'] = $currentItem['dev_lat'];
                    $result[$currentItem['vehicleid']]['dev_long'] = $currentItem['dev_long'];
                    $result[$currentItem['vehicleid']]['groupname'] = $currentItem['groupname'];
                    $result[$currentItem['vehicleid']]['runningtime'] += $currentItem['runningtime'];
                    $result[$currentItem['vehicleid']]['genset'] += $currentItem['genset'];
                    $result[$currentItem['vehicleid']]['harsh_break'] += $currentItem['harsh_break'];
                    $result[$currentItem['vehicleid']]['sudden_acc'] += $currentItem['sudden_acc'];
                    $result[$currentItem['vehicleid']]['towing'] = $currentItem['towing'];
                    $result[$currentItem['vehicleid']]['idleignitionontime'] += $currentItem['idleignitionontime'];
                    $result[$currentItem['vehicleid']]['idleignitionofftime'] += $currentItem['idleignitionofftime'];
                    $result[$currentItem['vehicleid']]['lastodometer'] = $currentItem['lastodometer'];
                } else {
                    $result[$currentItem['vehicleid']] = $currentItem;
                    $result[$currentItem['vehicleid']]['totaldistance'] = $currentItem['totaldistance'];
                    $result[$currentItem['vehicleid']]['avgspeed'] = $currentItem['avgspeed'];
                    $result[$currentItem['vehicleid']]['overspeed'] = $currentItem['overspeed'];
                    $result[$currentItem['vehicleid']]['dev_lat'] = $currentItem['dev_lat'];
                    $result[$currentItem['vehicleid']]['dev_long'] = $currentItem['dev_long'];
                    $result[$currentItem['vehicleid']]['first_dev_long'] = $currentItem['first_dev_long'];
                    $result[$currentItem['vehicleid']]['first_dev_lat'] = $currentItem['first_dev_lat'];
                    $result[$currentItem['vehicleid']]['groupname'] = $currentItem['groupname'];
                    $result[$currentItem['vehicleid']]['runningtime'] = $currentItem['runningtime'];
                    $result[$currentItem['vehicleid']]['genset'] = $currentItem['genset'];
                    $result[$currentItem['vehicleid']]['harsh_break'] = $currentItem['harsh_break'];
                    $result[$currentItem['vehicleid']]['sudden_acc'] = $currentItem['sudden_acc'];
                    $result[$currentItem['vehicleid']]['towing'] = $currentItem['towing'];
                    $result[$currentItem['vehicleid']]['idleignitionontime'] = $currentItem['idleignitionontime'];
                    $result[$currentItem['vehicleid']]['idleignitionofftime'] = $currentItem['idleignitionofftime'];
                    $result[$currentItem['vehicleid']]['max_topspeed'] = $currentItem['topspeed'];
                    $result[$currentItem['vehicleid']]['max_topspeed_date'] = $currentItem['datechk'];
                    $result[$currentItem['vehicleid']]['max_topspeed_lat'] = $currentItem['topspeed_lat'];
                    $result[$currentItem['vehicleid']]['max_topspeed_long'] = $currentItem['topspeed_long'];
                    $result[$currentItem['vehicleid']]['firstodometer'] = $currentItem['firstodometer'];
                    $result[$currentItem['vehicleid']]['lastodometer'] = $currentItem['lastodometer'];
                }
                if ($currentItem['datechk'] != $result[$currentItem['vehicleid']]['max_topspeed_date'] && $currentItem['topspeed'] > $result[$currentItem['vehicleid']]['max_topspeed']) {
                    $result[$currentItem['vehicleid']]['max_topspeed'] = $currentItem['topspeed'];
                    $result[$currentItem['vehicleid']]['max_topspeed_date'] = $currentItem['datechk'];
                    $result[$currentItem['vehicleid']]['max_topspeed_lat'] = $currentItem['topspeed_lat'];
                    $result[$currentItem['vehicleid']]['max_topspeed_long'] = $currentItem['topspeed_long'];
                }
                return $result;
            });
            if (isset($summarydata)) {

                $sdate = strtotime($sdate);
                $edate = strtotime($edate);
                foreach ($summarydata as $table_data) {
                    $basic_details = get_vehicle_name_pdf($table_data['vehicleid'], $customerdetails->customerno);
                    $cm = new CustomerManager();
                    $groupname = $table_data['groupname'];
                    $hours = floor($table_data['runningtime'] / 60);
                    $minutes = $table_data['runningtime'] % 60;
                    if ($minutes < 10) {
                        $minutes = '0' . $minutes;
                    }
                    $hourss = floor($table_data['genset'] / 60);
                    $minutess = $table_data['genset'] % 60;
                    if ($minutess < 10) {
                        $minutess = '0' . $minutess;
                    }
                    $geocode = isset($_POST['geocode']) ? $_POST['geocode'] : null;
                    $finalReport .= "<tr>
                    <td>$srno</td>
                    <td>$basic_details->vehicleno</td>
                    <td>$basic_details->drivername</td>
                    <td>$groupname</td>
                    <td style='width:200px;'>" . getlocation($table_data['first_dev_lat'], $table_data['first_dev_long'], $geocode, $_SESSION['customerno']) . "</td>
                    <td style='width:200px;'>" . getlocation($table_data['dev_lat'], $table_data['dev_long'], $geocode, $_SESSION['customerno']) . "</td>
                    <td style='width:200px;'>" . get_location_detail($table_data['max_topspeed_lat'], $table_data['max_topspeed_long'], $customerdetails->customerno) . "</td>
                    <td>" . abs(round($table_data['totaldistance'] / 1000, 2)) . "</td>";
                    if ($customerdetails->customerno == 126) {
                        $finalReport .= "<td>" . round($table_data['firstodometer'] / 1000, 2) . "</td>";
                        $finalReport .= "<td>" . round($table_data['lastodometer'] / 1000, 2) . "</td>";
                    }
                    if ($customerdetails->customerno != 135 && $dailyReportChangeDate <= $sdate) {
                        //$finalReport .= "<td>" . abs(round(($table_data['avgspeed'] / 1000) / $totaldaycount, 2)) . "</td>";\
                        $min = round($minutes/60,2);
                        $runningTime = abs(round( $hours + $min, 2));
                        $distanceTravelled = abs(round(($table_data['totaldistance'] / 1000), 2));
                        $avgSpeed = 0;
                        if($runningTime > 0){
                            $avgSpeed = abs(round(($distanceTravelled / $runningTime), 2));
                        }


                        $finalReport .= "<td>" . $avgSpeed . "</td>";
                    } elseif ($customerdetails->customerno != 135) {
                        $finalReport .= "<td>" . $table_data['avgspeed'] . "</td>";
                    }
                    $finalReport .= "<td>" . $hours . ":" . $minutes . "</td>";
                    /*
                    Before the below change date, these columns were not present in the database .
                    So report before this date would not show the columns
                     */
                    $changedate = "2016-03-03";
                    if ($sdate >= strtotime($changedate)) {
                        $hourson = floor($table_data['idleignitionontime'] / 60);
                        $minuteson = $table_data['idleignitionontime'] % 60;
                        if ($minuteson < 10) {
                            $minuteson = '0' . $minuteson;
                        }
                        $finalReport .= "<td>" . $hourson . ":" . $minuteson . "</td>";
                        $hoursoff = floor($table_data['idleignitionofftime'] / 60);
                        $minutesoff = $table_data['idleignitionofftime'] % 60;
                        if ($minutesoff < 10) {
                            $minutesoff = '0' . $minutesoff;
                        }
                        $finalReport .= "<td>" . $hoursoff . ":" . $minutesoff . "</td>";
                    }
                    if ($customerdetails->use_ac_sensor == 1 || $customerdetails->use_genset_sensor == 1 || $customerdetails->use_door_sensor == 1) {
                        $finalReport .= "<td>" . $hourss . ":" . $minutess . "</td>";
                    }
                    if ($dailyReportChangeDate <= $sdate) {
                        $higlightOverSpeedCell = '';
                        $higlightOverHarshBreakCell = '';
                        $higlightOverSuddenAccCell = '';
                        $higlightCell = '';
                        if ($table_data['overspeed'] > 0) {
                            $higlightCell = "style='background:#FFE0CC;'";
                        }
                        if ($table_data['harsh_break'] > 0) {
                            $higlightOverHarshBreakCell = "style='background:#FFE0CC;'";
                        }
                        if ($table_data['sudden_acc'] > 0) {
                            $higlightOverSuddenAccCell = "style='background:#FFE0CC;'";
                        }
                        $finalReport .= "<td " . $higlightCell . "><a target='_blank' href='../reports/reports.php?id=14&sdate=$SDATE&edate=$EDATE&stime=00:00&etime=23:59&vehicleno=$basic_details->vehicleno&deviceid=$basic_details->vehicleid' style='text-decoration:underline;'>"
                        . $table_data['overspeed'] . "/(" . $basic_details->overspeed_limit . ")" . "</a></td>";
                        $finalReport .= "<td>" . $table_data['max_topspeed'] . "</td>";

                    }
                    $srno++;
                }
            }
        }
        echo $finalReport;
    }

    function get_data_fordashboard_pdf($sdate, $edate, $customerno, $userid, $groupid=null) {
        $driverLabel = isset($_SESSION['Driver']) ? $_SESSION['Driver'] : "Driver";
        $dailyReportChangeDate = "20-02-2015";
        $dailyReportChangeDate = strtotime($dailyReportChangeDate);
        $totaldays = gendays($sdate, $edate);
        $startdate = strtotime($sdate);
        $enddate = strtotime($edate);
        $cm = new CustomerManager();
        $customer_details = $cm->getcustomerdetail_byid($customerno);
        $title = 'Summary Report';
        $subTitle = array(
            "From Date: $sdate",
            "End Date: $edate",
        );
        echo pdf_header($title, $subTitle, $customer_details);
    ?>
    <table id="search_table_2" style="width: 100%; border: none;padding-left: 20px;">
        <tr>
            <td style="width:30%;border: none;">SL: Start Location</td>
            <td style="width:30%;border: none;"></td>
            <td style="width:30%;border: none;"><?php if ($startdate <= $enddate) {?> TS: Top Speed [KM/HR]<?php }?></td>
        </tr>
        <tr>
            <td style="width:30%;border: none;">EL: End Location </td>
            <td style="width:30%;border: none;"></td>
            <td style="width:30%;border: none;"><?php if ($startdate <= $enddate) {?>TSL: Top Speed Location<?php }?></td>
        </tr>
        <tr>
            <td style="width:30%;border: none;">DT: Distance Travelled [KM]</td>
            <td style="width:30%;border: none;"></td>
            <td style="width:30%;border: none;">ION: Idle Ignition On Time [HH:MM]</td>
        </tr>

        <tr>
            <td style="width:30%;border: none;">RT: Running Time [HH:MM]</td>
            <td style="width:30%;border: none;"></td>
            <td style="width:30%;border: none;">IOFF: Idle Ignition Off Time [HH:MM]</td>
        </tr>
        <?php
            if ($startdate <= $enddate) {
                ?>
            <tr>
                <td style="width:30%;border: none;">OS: Overspeed [Times] / (Overspeed Limit [km / hr])</td>
                <td style="width:40%;border: none;"></td>
                <td style="width:30%;border: none;"></td>
            </tr>
            <tr>
                <td style="width:30%;border: none;"></td>
                <td style="width:40%;border: none;"></td>
                <td style="width:30%;border: none;"></td>
            </tr>
        <?php }?>
<?php if ($customerno == 126) {?>
        <tr>
            <td style="width:30%;border: none;">SK: Start KM</td>
            <td style="width:30%;border: none;"></td>
            <td style="width:30%;border: none;"></td>
        </tr>
        <tr>
            <td style="width:30%;border: none;">EK: End KM</td>
            <td style="width:30%;border: none;"></td>
            <td style="width:30%;border: none;"></td>
        </tr>
        <?php }?>
        <tr>
            <?php if ($customerno != 135) {?>
            <tr>
                <td style="width:30%;border: none;">AS: Average Speed [KM/HR]</td>
                <td style="width:30%;border: none;"></td>
                <td style="width:30%;border: none;"></td>
            </tr>
        <?php }?>
        <td style="width:30%;border: none;">
            <?php
                if ($customer_details->use_door_sensor == 1) {
                        echo "<li>DSU: Door Sensor Usage [HH:MM]</li>";
                    }
                    if ($customer_details->use_ac_sensor == 1) {
                        echo "<li>ACU: AC Usage [HH:MM]</li>";
                    }
                    if ($customer_details->use_genset_sensor == 1) {
                        echo "<li>GNU: Genset Usage [HH:MM]</li>";
                    }
                ?>
        </td>
        <td style="width:40%;border: none;"></td>
        <td style="width:30%;border: none;"></td>
        </tr>
    </table>
    <table id='search_table_2' align='center' style='width: auto;
           font-size:10px;
           text-align:center;
           border-collapse:collapse;
           border:1px solid #000;'>
        <tbody>
            <tr style = 'background-color:#CCCCCC;font-weight:bold;'>
                <td style = "width:10px;">#</td>
                <td style = "width:10px;">Vehicle No</td>
                <td style = "width:25px;"><?php echo $driverLabel;?> Name</td>
                <td style = "width:25px;">Group</td>
                <td style = "width:30px;">SL</td>
                <td style = "width:30px;">EL</td>
                <td style="width:10px;">TSL</td>
                <td style = "width:10px;">DT</td>
                <?php if ($customerno == 126) {?>
                    <td style = "width:10px;">SK</td>
                    <td style = "width:10px;">EK</td>
                <?php }?>
<?php if ($customerno != 135) {?>
                    <td style = "width:10px;">AS</td>
                <?php }?>
                <td style = "width:10px;">RT</td>
                <?php
                    /*
                        Before the below change date, these columns were not present in the database .
                        So report before this date would not show the columns
                         */
                        $changedate = "2016-03-03";
                        if ($startdate >= strtotime($changedate)) {
                        ?>
                    <td style="width:20px;">ION</td>
                    <td style="width:20px;">IOFF</td>
                <?php }

                        if ($customer_details->use_door_sensor == 1) {
                            echo "<td style='width:10px;'>DSU</td>";
                        }
                        if ($customer_details->use_ac_sensor == 1) {
                            echo "<td style='width:10px;'>ACU</td>";
                        }
                        if ($customer_details->use_genset_sensor == 1) {
                            echo "<td style='width:10px;'>GNU</td>";
                        }
                    if ($startdate <= $enddate) {?>
                    <td style="width:10px;">OS</td>
                    <td style="width:10px;">TS</td>

                <?php }?>
            </tr>
            <?php
                $location1 = "../../customer/" . $customerno . "/reports/dailyreport_new.sqlite";
                    $location = "../../customer/" . $customerno . "/reports/dailyreport.sqlite";
                    if (file_exists($location)) {
                        if (isset($groupid) && $groupid!=0) {
                            $VehicleManager = new VehicleManager($customerno);
                            $vehicles = $VehicleManager->get_groups_vehicles($groupid);
                            $vehicle_ids = vehicle_id_array($vehicles);
                        } else {
                            $vehicle_ids = vehicle_id_array(groupBased_vehicles_cron($customerno, $userid));
                        }
                        $dailyreportdata = GetDailyReport_Data($location, $location1, $totaldays, $vehicle_ids);
                        html_wrapper_pdf($dailyreportdata, $sdate, $edate, $customerno, $userid);
                    }
                    echo "</tbody></table>";
                    if ($startdate <= $enddate) {
                    ?>
                Note :  <br/>
            <ul style='float:left;text-align:left;'>
                <li>- Daily Summary Report does not consider offline data. Offline Data is the data wherein the device is under a low network area and device sends data when it comes in network.
                </li>
                <li>- FreeWheeling - FreeWheeling either means riding on a downhill with ignition off to save fuel or there is some issue with the ignition connection. If you see Freewheeling on a frequent basis, please get the ignition wire connection checked.</li>
                <li>- Online data field gives you an approximate indication of the actual time the device sent real time data.</li>
                <li>- Average Distance is calculated in effect from Feb 28, 2015.</li>

                <li>- When unit is replaced, daily summary report will be valid for the new unit only.</li>
                <li>- If you see any erratic data in this report, you may shoot an email to support@elixiatech.com and we will be there to support.
                </li>
            </ul>
            <?php
                }
                }

                function get_data_dashboard_excel($sdate, $edate, $customerno) {
                    $driverLabel = isset($_SESSION["Driver"]) ? $_SESSION["Driver"]: "Driver";
                    $totaldays = gendays($sdate, $edate);
                    $cm = new CustomerManager();
                    $customer_details = $cm->getcustomerdetail_byid($customerno);
                    $title = 'Summary Report';
                    $subTitle = array(
                        "Start Date: $sdate",
                        "End Date: $edate",
                    );
                    $startdate = strtotime($sdate);
                    $enddate = strtotime($edate);
                    echo excel_header($title, $subTitle, $customer_details);
                ?>
        <table>
            <tr><td colspan="2">SL: Start Location</td><td colspan="2"><?php if ($startdate <= $enddate) {?>TS: Top Speed [KM/HR]<?php }?></td></tr>
            <tr><td colspan="2">EL: End Location</td><td colspan="2"><?php if ($startdate <= $enddate) {?>TSL: Top Speed Location<?php }?></td></tr>
            <tr><td colspan="2">DT: Distance Travelled [KM]</td><td colspan="2"><?php if ($startdate <= $enddate) {?>HB: Harsh Break [Times]<?php }?></td></tr>
            <tr><td colspan="2">RT: Running Time [HH:MM]</td> <td colspan="2"><?php if ($startdate <= $enddate) {?>SA: Sudden  Acceleration [Times]<?php }?></td></tr>


            <?php if ($startdate <= $enddate) {?>
                <tr><td colspan="2">OS: Overspeed[Times] / (Overspeed Limit [km / hr])</td> <td colspan="2">ION: Idle Ignition On Time [HH:MM]</td></tr>
                <tr><td colspan="2">TO: Towing/FreeWheeling [Yes/No]</td > <td colspan="2">IOFF: Idle Ignition Off Time [HH:MM]</td></tr>
            <?php }?>
<?php if ($customerno == 126) {?>
            <tr><td colspan="2">SK : Start KM</td> <td colspan="2"></td></tr>
            <tr><td colspan="2">EK : End KM</td> <td colspan="2"></td></tr>
            <?php }?>
            <tr><td colspan="2"><?php if ($customerno != 135) {?>AS: Average Speed [KM/HR]<?php }?></td><td colspan="2"></td></tr>
            <?php
                if ($customer_details->use_door_sensor == 1) {
                        echo "<tr><td>DSU: Door Sensor Usage [HH:MM]</td></tr>";
                    }
                    if ($customer_details->use_ac_sensor == 1) {
                        echo "<tr><td>ACU: AC Usage [HH:MM]</td></tr>";
                    }
                    if ($customer_details->use_genset_sensor == 1) {
                        echo "<tr><td>GNU: Genset Usage [HH:MM]</td></tr>";
                    }
                    $AS = "";
                    if ($customerno != 135) {
                        $AS = "<td style='width:50px;height:auto; text-align: center;'>AS</td>";
                    }
                    echo "</table><br/><br/>";
                    echo "<table  id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
                    $ignonoff = "";
                    /*
                    Before the below change date, these columns were not present in the database .
                    So report before this date would not show the columns
                     */
                    $changedate = "2016-03-03";
                    if ($startdate >= strtotime($changedate)) {
                        $ignonoff = "<td style='width:50px;height:auto; text-align: center;'>ION</td>
                                 <td style='width:50px;height:auto; text-align: center;'>IOFF</td>";
                    }
                    echo "<tr style='background-color:#CCCCCC;'>
            <td style='width:50px;height:auto; text-align: center;'>Sr.No</td>
            <td style='width:50px;height:auto; text-align: center;'>Vehicle No</td>
            <td style='width:50px;height:auto; text-align: center;'>".$driverLabel." Name</td>
            <td style='width:50px;height:auto; text-align: center;'>Group</td>
            <td style='width:50px;height:auto; text-align: center;'>Start Location</td>
            <td style='width:150px;height:auto; text-align: center;'>End Location</td>
            <td style='width:50px;height:auto; text-align: center;'>TSL</td>
            <td style='width:50px;height:auto; text-align: center;'>DT</td>";
                    if ($customerno == 126) {
                        echo " <td style='width:50px;height:auto; text-align: center;'>SK</td>
                <td style='width:50px;height:auto; text-align: center;'>EK</td>";
                    }
                    echo $AS . "
            <td style='width:50px;height:auto; text-align: center;'>RT</td>
            " . $ignonoff;
                    if ($customer_details->use_door_sensor == 1) {
                        echo "<td style='width:50px;height:auto; text-align: center;'>Door Sensor Usage [HH:MM]</td>";
                    }
                    if ($customer_details->use_ac_sensor == 1) {
                        echo "<td style='width:50px;height:auto; text-align: center;'>AC Usage [HH:MM]</td>";
                    }
                    if ($customer_details->use_genset_sensor == 1) {
                        echo "<td style='width:50px;height:auto; text-align: center;'>Genset Usage [HH:MM]</td>";
                    }
                    if ($startdate <= $enddate) {
                        echo "<td style='width:50px;height:auto; text-align: center;'>OS</td>
            <td style='width:50px;height:auto; text-align: center;'>TS</td>

            </tr>
            ";
                    }
                    $location1 = "../../customer/" . $customerno . "/reports/dailyreport_new.sqlite";
                    $location = "../../customer/" . $customerno . "/reports/dailyreport.sqlite";
                    if (file_exists($location)) {
                        if (isset($groupid) && $groupid!=0) {
                            $VehicleManager = new VehicleManager($customerno);
                            $vehicles = $VehicleManager->get_groups_vehicles($groupid);
                            $vehicle_ids = vehicle_id_array($vehicles);
                        } else {
                            $vehicle_ids = vehicle_id_array(groupBased_vehicles_cron($customerno, $userid));
                        }

                        $dailyreportdata = GetDailyReport_Data($location, $location1, $totaldays, $vehicle_ids);
                        html_wrapper_pdf($dailyreportdata, $sdate, $edate, $customerno, $_SESSION['userid']);
                    }
                    echo "</table>";
                    echo "<br/><br/>";
                    if ($startdate <= $enddate) {
                        echo "<table  id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
                        echo "<tr><td colspan='15'>Note :</td></tr>";
                        echo "<tr><td colspan='15'>- Daily Summary Report does not consider offline data. Offline Data is the data wherein the device is under a low network area and device sends data when it comes in network.</td></tr>";
                        echo "<tr><td colspan='15'>- FreeWheeling - FreeWheeling either means riding on a downhill with ignition off to save fuel or there is some issue with the ignition connection. If you see Freewheeling on a frequent basis, please get the ignition wire connection checked.</td></tr>";
                        echo "<tr><td colspan='15'>- Online data field gives you an approximate indication of the actual time the device sent real time data.</td></tr>";
                        echo "<tr><td colspan='15'>- Average Distance is calculated in effect from Feb 28, 2015.</td></tr>";

                        echo "<tr><td colspan='15'>- When unit is replaced, daily summary report will be valid for the new unit only.</td></tr>";
                        echo "<tr><td colspan='15'>- If you see any erratic data in this report, you may shoot an email to support@elixiatech.com and we will be there to support.</td></tr>";
                        echo "</table>";
                    }
                }

                function get_piechart_data_for_checkpoint() {
                    $grouped_vehicles = vehicle_id_array(groupBased_vehicles_cron($_SESSION['customerno'], $_SESSION['userid']));
                    $total_vehicles = count($grouped_vehicles);
                    $grouped_vehicles_csv = implode(',', $grouped_vehicles);
                    $chkmanager = new CheckpointManager($_SESSION['customerno']);
                    $chkpoints = $chkmanager->get_checkpoits_with_vehicle_inside($grouped_vehicles_csv);
                    $inside = 0;
                    if (isset($chkpoints)) {
                        foreach ($chkpoints as $chkpoint) {
                        ?>
                    ['<?php echo $chkpoint['cname']; ?>',<?php echo $chkpoint['count'];
            $inside += $chkpoint['count']; ?>],
                    <?php
                        }
                            }
                            $outside = $total_vehicles - $inside;
                        ?>
            ['Outside',<?php echo $outside; ?>],
            <?php
                }

                function get_piechart_data_for_vehicle_status() {
                    $devicemanager = new DeviceManager($_SESSION['customerno']);
                    $devices = $devicemanager->devicesformapping();
                    if (isset($devices)) {
                        $i = 0;
                        $j = 0;
                        $k = 0;
                        $l = 0;
                        $m = 0;
                        foreach ($devices as $device) {
                            $ServerIST_less1 = new DateTime();
                            $ServerIST_less1->modify('-60 minutes');
                            $lastupdated = new DateTime($device->lastupdated);
                            if ($lastupdated < $ServerIST_less1) {
                                $m++;
                            } else {
                                if ($device->ignition == '0') {
                                    $l++;
                                } else {
                                    if ($device->curspeed > $device->overspeed_limit) {
                                        $i++;
                                    } elseif ($device->stoppage_flag == '0') {
                                        $k++;
                                    } else {
                                        $j++;
                                    }
                                }
                            }
                        }
                    }
                    $response = array();
                    $response['overspeed'] = $i;
                    $response['running'] = $j;
                    $response['idleignon'] = $k;
                    $response['idleignoff'] = $l;
                    $response['inactive'] = $m;
                    return $response;
                }

                function gettest() {
                    $dm = new DeviceManager($_SESSION['customerno']);
                    $tes = $dm->devicesformapping_idleStatus();
                    return $tes;
                }

                function get_piechart_data_for_vehicle_idle() {
                    $devicemanager = new DeviceManager($_SESSION['customerno']);
                    $devices = $devicemanager->devicesformapping();
                    if (isset($devices)) {
                        $h = 0;
                        $i = 0;
                        $j = 0;
                        $k = 0;
                        $l = 0;
                        $m = 0;
                        foreach ($devices as $device) {
                            $hour = date('Y-m-d H:i:s');
                            $hour1 = date('Y-m-d H:i:s', time() - (1 * 3600));
                            $hour3 = date('Y-m-d H:i:s', time() - (3 * 3600));
                            $hour5 = date('Y-m-d H:i:s', time() - (5 * 3600));
                            $hour24 = date('Y-m-d H:i:s', time() - (24 * 3600));
                            $stoppage_transit_time = $device->stoppage_transit_time;
                            $lastupdated = $device->lastupdated;
                            if ($lastupdated < $hour1) {
                                $m++;
                            } else {
                                if ($device->ignition == '0') {
                                    if ($stoppage_transit_time <= $hour && $stoppage_transit_time >= $hour1) {
                                        $h++;
                                    } elseif ($stoppage_transit_time <= $hour1 && $stoppage_transit_time >= $hour3) {
                                        $i++;
                                    } elseif ($stoppage_transit_time <= $hour3 && $stoppage_transit_time >= $hour5) {
                                        $j++;
                                    } elseif ($stoppage_transit_time <= $hour5 && $stoppage_transit_time >= $hour24) {
                                        $k++;
                                    } elseif ($stoppage_transit_time <= $hour24) {
                                        $l++;
                                    }
                                } else {
                                    if ($device->curspeed > $device->overspeed_limit) {
                                        $m++;
                                    } elseif ($device->stoppage_flag == '0') {
                                        if ($stoppage_transit_time <= $hour && $stoppage_transit_time >= $hour1) {
                                            $h++;
                                        } elseif ($stoppage_transit_time <= $hour1 && $stoppage_transit_time >= $hour3) {
                                            $i++;
                                        } elseif ($stoppage_transit_time <= $hour3 && $stoppage_transit_time >= $hour5) {
                                            $j++;
                                        } elseif ($stoppage_transit_time <= $hour5 && $stoppage_transit_time >= $hour24) {
                                            $k++;
                                        } elseif ($stoppage_transit_time <= $hour24) {
                                            $l++;
                                        }
                                    } else {
                                        $m++;
                                    }
                                }
                            }
                        }
                    }
                    $response = array();
                    $response['zero'] = $h;
                    $response['one'] = $i;
                    $response['three'] = $j;
                    $response['five'] = $k;
                    $response['twofour'] = $l;
                    $response['notidle'] = $m;
                    return $response;
                }

                function get_vehicle_details_by_date($vehicleid, $date) {
                    if ($date == date('d-m-Y')) {
                        // echo "todays data call";
                        html_wrapper_vehicledetails(get_comquedata($vehicleid), $date);
                        // todays date
                    } else {
                        date_default_timezone_set("Asia/Calcutta");
                        $date_url = date('MY', strtotime($date));
                        $date = date('d-m-Y', strtotime($date));
                        $location = "../../customer/" . $_SESSION['customerno'] . "/history/" . $date_url . ".sqlite";
                        if (file_exists($location)) {
                            html_wrapper_vehicledetails(Gethistory_Data($location, $date, $vehicleid), $date);
                        }
                        //return $DATA;
                    }
                }

                function Gethistory_Data($location, $date, $vehicleid) {
                    $path = "sqlite:$location";
                    $db = new PDO($path);
                    $date = date('Y-m-d', strtotime($date));
                    $data = array();
                    $sqlday = date("dmy", strtotime($days));
                    $query = "SELECT * from comqueue where vehicleid=" . $vehicleid . " AND timeadded BETWEEN '" . $date . " 00:00:00' AND '" . $date . " 23:59:59' ORDER BY timeadded ASC";
                    $result = $db->query($query);
                    if (isset($result) && $result != "") {
                        foreach ($result as $row) {
                            // for check point
                            if ($row['type'] == 2 && $row['status'] == 1) {
                                $temp = array();
                                $temp['timestamp'] = $row['timeadded'];
                                $temp['location'] = $row['message'];
                                $data['check'][] = $temp;
                            }
                            // for overspeed
                            if ($row['type'] == 5 && $row['status'] == 0) {
                                $temp['timestamp'] = $row['timeadded'];
                                $temp['location'] = get_location_detail($row['lat'], $row['long'], $_SESSION['customerno']);
                                $data['oversped'][] = $temp;
                            }
                        }
                    }
                    return $data;
                }

                function html_wrapper_pdf($data, $sdate, $edate, $customerno, $userid) {
                    if (isset($data)) {
                        $sr = 0;
                        $array = json_decode(json_encode($data), true);
                        $summarydata = array_reduce($array, function ($result, $currentItem) {
                            if (isset($result[$currentItem['vehicleid']])) {
                                $result[$currentItem['vehicleid']]['totaldistance'] += $currentItem['totaldistance'];
                                $result[$currentItem['vehicleid']]['avgspeed'] += $currentItem['avgspeed'];
                                $result[$currentItem['vehicleid']]['overspeed'] += $currentItem['overspeed'];
                                $result[$currentItem['vehicleid']]['dev_lat'] = $currentItem['dev_lat'];
                                $result[$currentItem['vehicleid']]['dev_long'] = $currentItem['dev_long'];
                                $result[$currentItem['vehicleid']]['groupname'] = $currentItem['groupname'];
                                $result[$currentItem['vehicleid']]['runningtime'] += $currentItem['runningtime'];
                                $result[$currentItem['vehicleid']]['genset'] += $currentItem['genset'];
                                $result[$currentItem['vehicleid']]['harsh_break'] += $currentItem['harsh_break'];
                                $result[$currentItem['vehicleid']]['sudden_acc'] += $currentItem['sudden_acc'];
                                $result[$currentItem['vehicleid']]['towing'] = $currentItem['towing'];
                                $result[$currentItem['vehicleid']]['idleignitionontime'] += $currentItem['idleignitionontime'];
                                $result[$currentItem['vehicleid']]['idleignitionofftime'] += $currentItem['idleignitionofftime'];
                                $result[$currentItem['vehicleid']]['lastodometer'] = $currentItem['lastodometer'];
                            } else {
                                $result[$currentItem['vehicleid']] = $currentItem;
                                $result[$currentItem['vehicleid']]['totaldistance'] = $currentItem['totaldistance'];
                                $result[$currentItem['vehicleid']]['avgspeed'] = $currentItem['avgspeed'];
                                $result[$currentItem['vehicleid']]['overspeed'] = $currentItem['overspeed'];
                                $result[$currentItem['vehicleid']]['dev_lat'] = $currentItem['dev_lat'];
                                $result[$currentItem['vehicleid']]['dev_long'] = $currentItem['dev_long'];
                                $result[$currentItem['vehicleid']]['first_dev_long'] = $currentItem['first_dev_long'];
                                $result[$currentItem['vehicleid']]['first_dev_lat'] = $currentItem['first_dev_lat'];
                                $result[$currentItem['vehicleid']]['groupname'] = $currentItem['groupname'];
                                $result[$currentItem['vehicleid']]['runningtime'] = $currentItem['runningtime'];
                                $result[$currentItem['vehicleid']]['genset'] = $currentItem['genset'];
                                $result[$currentItem['vehicleid']]['harsh_break'] = $currentItem['harsh_break'];
                                $result[$currentItem['vehicleid']]['sudden_acc'] = $currentItem['sudden_acc'];
                                $result[$currentItem['vehicleid']]['towing'] = $currentItem['towing'];
                                $result[$currentItem['vehicleid']]['idleignitionontime'] = $currentItem['idleignitionontime'];
                                $result[$currentItem['vehicleid']]['idleignitionofftime'] = $currentItem['idleignitionofftime'];
                                $result[$currentItem['vehicleid']]['max_topspeed'] = $currentItem['topspeed'];
                                $result[$currentItem['vehicleid']]['max_topspeed_date'] = $currentItem['datechk'];
                                $result[$currentItem['vehicleid']]['max_topspeed_lat'] = $currentItem['topspeed_lat'];
                                $result[$currentItem['vehicleid']]['max_topspeed_long'] = $currentItem['topspeed_long'];
                                $result[$currentItem['vehicleid']]['firstodometer'] = $currentItem['firstodometer'];
                                $result[$currentItem['vehicleid']]['lastodometer'] = $currentItem['lastodometer'];
                            }
                            if ($currentItem['datechk'] != $result[$currentItem['vehicleid']]['max_topspeed_date'] && $currentItem['topspeed'] > $result[$currentItem['vehicleid']]['max_topspeed']) {
                                $result[$currentItem['vehicleid']]['max_topspeed'] = $currentItem['topspeed'];
                                $result[$currentItem['vehicleid']]['max_topspeed_date'] = $currentItem['datechk'];
                                $result[$currentItem['vehicleid']]['max_topspeed_lat'] = $currentItem['topspeed_lat'];
                                $result[$currentItem['vehicleid']]['max_topspeed_long'] = $currentItem['topspeed_long'];
                            }
                            return $result;
                        });
                        if (isset($summarydata)) {
                            $startdate = strtotime($sdate);
                            $enddate = strtotime($edate);
                            foreach ($summarydata as $table_data) {
                                $sr++;
                                $basic_details = get_vehicle_name_pdf($table_data['vehicleid'], $customerno);
                                $cm = new CustomerManager();
                                $groupname = $table_data['groupname'];
                                //$chek_count= get_chkpoint_for_vehicle_by_date($date, $table_data->vehicleid, $customerno);
                                $hours = floor($table_data['runningtime'] / 60);
                                $minutes = $table_data['runningtime'] % 60;
                                if ($minutes < 10) {
                                    $minutes = '0' . $minutes;
                                }
                                $hourss = floor($table_data['genset'] / 60);
                                $minutess = $table_data['genset'] % 60;
                                if ($minutess < 10) {
                                    $minutess = '0' . $minutess;
                                }
                                $geocode = isset($_POST['geocode']) ? $_POST['geocode'] : null;
                            ?>
                <tr>
                    <td><?php echo $sr; ?></td>
                    <td><?php echo $basic_details->vehicleno; ?></td>
                    <td style="width:100px;"><?php echo "$basic_details->drivername"; ?></td>
                    <td><?php echo $groupname; ?></td>
                    <td style="width:100px;"><?php echo getlocation($table_data['first_dev_lat'], $table_data['first_dev_long'], $geocode, $customerno); ?></td>
                    <td style="width:100px;"><?php echo getlocation($table_data['dev_lat'], $table_data['dev_long'], $geocode, $customerno); ?></td>
                    <td style="width:100px;"><?php echo get_location_detail($table_data['max_topspeed_lat'], $table_data['max_topspeed_long'], $customerno); ?></td>
                    <td><?php echo abs(round($table_data['totaldistance'] / 1000, 2)); ?></td>
                    <?php if ($customerno == 126) {?>
                    <td><?php echo round($table_data['firstodometer'] / 1000, 2); ?></td>
                    <td><?php echo round($table_data['lastodometer'] / 1000, 2); ?></td>
                    <?php }?>
<?php if ($startdate <= $enddate) {
                    ?>
<?php if ($customerno != 135) {?>
                        <td><?php
                        $min = round($minutes/60,2);
                        $runningTime = abs(round( $hours + $min, 2));
                        $distanceTravelled = abs(round(($table_data['totaldistance'] / 1000), 2));
                        $avgSpeed = 0;
                        if($runningTime > 0){
                            $avgSpeed = abs(round(($distanceTravelled / $runningTime), 2));
                        }


                        echo $avgSpeed ;
                         ?></td>
                    <?PHP }
                                    } else {?>
<?php if ($customerno != 135) {?>
                        <td><?php

                        echo $table_data['avgspeed']; ?></td>
                    <?php }}?>
                    <td><?php echo $hours . ":" . $minutes; ?></td>
                    <?php
                        /*
                                        Before the below change date, these columns were not present in the database .
                                        So report before this date would not show the columns
                                         */
                                        $changedate = "2016-03-03";
                                        if ($startdate >= strtotime($changedate)) {
                                            $hourson = floor($table_data['idleignitionontime'] / 60);
                                            $minuteson = $table_data['idleignitionontime'] % 60;
                                            if ($minuteson < 10) {
                                                $minuteson = '0' . $minuteson;
                                            }
                                        ?>
                                <td><?php echo $hourson . ":" . $minuteson; ?></td>
                                <?php
                                    $hoursoff = floor($table_data['idleignitionofftime'] / 60);
                                                        $minutesoff = $table_data['idleignitionofftime'] % 60;
                                                        if ($minutesoff < 10) {
                                                            $minutesoff = '0' . $minutesoff;
                                                        }
                                                    ?>
                                <td><?php echo $hoursoff . ":" . $minutesoff; ?></td>
                            <?php }?>
<?php
    if ($_SESSION['use_door_sensor'] == 1 || $_SESSION['use_genset_sensor'] == 1 || $_SESSION['use_ac_sensor'] == 1) {
                        echo "<td>$hourss:$minutess</td>";
                    }
                ?>
<?php if ($startdate <= $enddate) {
                    ?>
<?php if ($table_data['overspeed'] > 0) {?>
                                    <td style='background:#FFE0CC;'><?php echo $table_data['overspeed'] . "/(" . $basic_details->overspeed_limit . ")"; ?></td>
                                    <?php
                                        } else {
                                                            ?>
                                    <td><?php echo $table_data['overspeed'] . "/(" . $basic_details->overspeed_limit . ")"; ?></td>
                                <?php }?>
                                <td><?php echo $table_data['max_topspeed']; ?></td>

                            </tr>
                            <?php
                                }
                                            }
                                        }
                                    }
                                }

                                function html_wrapper_vehicledetails($data, $date) {
                                    $check_array = $data['check'];
                                    $oversped_array = $data['oversped'];
                                ?>
            <h4>Checkpoints Details</h4>
            <div style="height:250px; overflow-x:scroll;">
                <table class="table table-stripped table-condensed">
                    <tr>
                        <th>Location</th>
                        <th>Time</th>
                    </tr>
                    <?php
                        if (count($check_array) > 0) {
                                foreach ($check_array as $table_data) {
                                ?>
                            <tr>
                                <td><?php echo $table_data['location']; ?></td>
                                <td><?php echo date("h:m A", strtotime($table_data['timestamp'])); ?></td>
                            </tr>
                            <?php
                                }
                                    } else {
                                    ?>
                        <tr>
                            <td  colspan="2">No data</td>
                        </tr>
                        <?php
                            }
                            ?>
                </table>
            </div>
            <h4>Over Speed Details</h4>
            <div style="height:250px; overflow-x:scroll;">
                <table class="table table-stripped table-condensed">
                    <tr>
                        <th>Location</th>
                        <th>Time</th>
                    </tr>
                    <?php
                        if (count($oversped_array) > 0) {
                                foreach ($oversped_array as $table_data) {
                                ?>
                            <tr>
                                <td><?php echo $table_data['location']; ?></td>
                                <td><?php echo date("h:m A", strtotime($table_data['timestamp'])); ?></td>
                            </tr>
                            <?php
                                }
                                    } else {
                                    ?>
                        <tr>
                            <td  colspan="2">No data</td>
                        </tr>
                    <?php }
                        ?>
                </table>
            </div>
            <?php
                }

                function GetCurrent_date_Data($location, $date) {
                    $date = date('Y-m-d', strtotime($date));
                    $vehicles = groupBased_vehicles($_SESSION['customerno']);
                    if (isset($vehicles)) {
                        $um = new UnitManager($_SESSION['customerno']);
                        foreach ($vehicles as $vehicle) {
                            $unitno = $um->getunitno($vehicle->vehicleid);
                            $path = "$location" . $unitno . "/sqlite/" . $date . ".sqlite";
                            if (file_exists($path)) {
                                $path = "sqlite:$path";
                                $Data = DataFromSqlite($path);
                                if ($Data != 0) {
                                    if (count($Data) > 0) {
                                        $acdata = $um->getacinvertval($unitno);
                                        $acinvertval = $acdata['0'];
                                        $acsensor = $acdata['1'];
                                        $vehiclemanager = new VehicleManager($_SESSION['customerno']);
                                        $vm = $vehiclemanager->getspeedlimit($vehicle->vehicleid);
                                        $vehicle->customerno = $_SESSION['customerno'];
                                        $rowa = DailyReport($vehicle, $date, $Data, $vm->overspeed_limit, $acinvertval, $acsensor);
                                        $Datacap = new stdClass();
                                        $Datacap->date = strtotime($date);
                                        $Datacap->uid = $vehicle->uid;
                                        $Datacap->avgspeed = $rowa['averagespeed'];
                                        $Datacap->genset = $rowa['gensetusage'];
                                        $Datacap->overspeed = $rowa['overspeed'];
                                        $Datacap->totaldistance = $rowa['totaldistance'];
                                        $Datacap->fenceconflict = ret_issetor($rowa['fenceconflict']);
                                        $Datacap->idletime = $rowa['idletime'];
                                        $Datacap->runningtime = $rowa['runningtime'];
                                        $Datacap->vehicleid = $rowa['vehicleid'];
                                        $Datacap->dev_lat = $rowa['lat'];
                                        $Datacap->dev_long = $rowa['long'];
                                        $Datacap->first_dev_lat = $Data[0]->first_dev_lat;
                                        $Datacap->first_dev_long = $Data[0]->first_dev_long;
                                        $REPORT[] = $Datacap;
                                    }
                                } else {
                                    $Bad = 0;
                                }
                            } else {
                                //echo "Nodata";
                            }
                        }
                    }
                    return $REPORT;
                }

                function get_chkpoint_for_vehicle_by_date($STdate, $vehicleid, $customerno) {
                    $date = date('Y-m-d', strtotime($STdate));
                    $path = "sqlite:../../customer/$customerno/reports/chkreport.sqlite";
                    $Query = "select count(distinct(chkid)) as counts from V" . $vehicleid . " WHERE date BETWEEN '$date 00:00:00' AND '$date 23:59:59' AND status=0 ";
                    $count = 0;
                    try {
                        $db = new PDO($path);
                        $result = $db->query($Query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $count = $row["counts"];
                            }
                        }
                    } catch (PDOException $e) {
                    }
                    return $count;
                }

                function DataFromSqlite($location) {
                    $PATH = "$location";
                    $Query = 'select * from devicehistory';
                    $Query .= ' INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated';
                    $Query .= ' INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = unithistory.lastupdated group by devicehistory.lastupdated';
                    $DRMS = array();
                    $lastig;
                    try {
                        $db = new PDO($PATH);
                        $result = $db->query($Query);
                        $firt_ll_set = 0;
                        foreach ($result as $row) {
                            $DRM = new stdClass();
                            if (!isset($lastig) || $lastig != $row['ignition']) {
                                $DRM->condition = 1;
                            } else {
                                $DRM->condition = 0;
                            }
                            //Unit Part
                            if ($firt_ll_set == 0) {
                                $DRM->first_dev_lat = $row['devicelat'];
                                $DRM->first_dev_long = $row['devicelong'];
                                $firt_ll_set = 1;
                            }
                            $DRM->uhid = $row['uhid'];
                            $DRM->uid = $row['uid'];
                            $DRM->unitno = $row['unitno'];
                            $DRM->customerno = $row['customerno'];
                            $DRM->vehicleid = $row['vehicleid'];
                            $DRM->analog1 = $row['analog1'];
                            $DRM->analog2 = $row['analog2'];
                            $DRM->analog3 = $row['analog3'];
                            $DRM->analog4 = $row['analog4'];
                            $DRM->digitalio = $row['digitalio'];
                            $DRM->lastupdated = $row['lastupdated'];
                            $DRM->dhid = ret_issetor($row['dhid']);
                            $DRM->vhid = ret_issetor($row['vhid']);
                            //VehiclePart
                            $DRM->vehiclehistoryid = $row['vehiclehistoryid'];
                            $DRM->vehicleid = $row['vehicleid'];
                            $DRM->vehicleno = $row['vehicleno'];
                            $DRM->devicekey = ret_issetor($row['devicekey']);
                            $DRM->extbatt = $row['extbatt'];
                            $DRM->odometer = $row['odometer'];
                            $DRM->curspeed = $row['curspeed'];
                            $DRM->customerno = $row['customerno'];
                            $DRM->driverid = $row['driverid'];
                            //DevicePart
                            $DRM->devicehistoryid = $row['id'];
                            $DRM->deviceid = $row['deviceid'];
                            $DRM->customerno = $row['customerno'];
                            $DRM->devicelat = $row['devicelat'];
                            $DRM->devicelong = $row['devicelong'];
                            $DRM->devicekey = $row['devicekey'];
                            $DRM->altitude = $row['altitude'];
                            $DRM->directionchange = $row['directionchange'];
                            $DRM->inbatt = $row['inbatt'];
                            $DRM->hwv = $row['hwv'];
                            $DRM->swv = $row['swv'];
                            $DRM->msgid = $row['msgid'];
                            $DRM->uid = $row['uid'];
                            $DRM->status = $row['status'];
                            $DRM->ignition = $row['ignition'];
                            $DRM->powercut = $row['powercut'];
                            $DRM->tamper = $row['tamper'];
                            $DRM->gpsfixed = $row['gpsfixed'];
                            $DRM->online_offline = $row['online/offline'];
                            $DRM->gsmstrength = $row['gsmstrength'];
                            $DRM->gsmregister = $row['gsmregister'];
                            $DRM->gprsregister = $row['gprsregister'];
                            $lastig = $row['ignition'];
                            $DRMS[] = $DRM;
                        }
                    } catch (PDOException $e) {
                        $DRMS = 0;
                    }
                    return $DRMS;
                }

                function DailyReport($device, $date, $info, $overspeed_limit, $acinvertval, $acsensor) {
                    //Device Info
                    $record['vehicleid'] = $device->vehicleid;
                    $record['uid'] = $device->uid;
                    $record['customerno'] = $device->customerno;
                    //Idle & Running Time Calculation
                    $dat = '';
                    $dat = array();
                    foreach ($info as $inf) {
                        if ($inf->condition == 1) {
                            $dat[] = $inf;
                        }
                    }
                    $enddat = end($info);
                    $data = processtraveldata($dat, $enddat);
                    $display = displaytraveldata($data);
                    //$utilizationtime = utilization($data);
                    $record['runningtime'] = $display[0];
                    $record['idletime'] = $display[1];
                    //Odometer Calculation
                    $lastrow = end($info);
                    $firstrow = $info[0];
                    $lastodometer = $lastrow->odometer;
                    $firstodometer = $firstrow->odometer;
                    $last_lat = $lastrow->devicelat;
                    $last_long = $lastrow->devicelong;
                    if ($lastodometer < $firstodometer) {
                        $max = GetOdometer_Max($date, $device->unitno);
                        $lastodometer = $max + $lastodometer;
                    }
                    $record['totaldistance'] = $lastodometer - $firstodometer;
                    $record['totaldistanceKM'] = $lastodometer / 1000 - $firstodometer / 1000;
                    if (isset($record['totaldistanceKM']) && $record['totaldistanceKM'] > 0) {
                        if (isset($record['runningtime']) && $record['runningtime'] != 0) {
                            $AverageSpeed = (int) ($record['totaldistanceKM'] / ($record['runningtime'] / 60));
                        } else {
                            $AverageSpeed = 0;
                        }
                    } else {
                        $AverageSpeed = 0;
                    }
                    $record['averagespeed'] = $AverageSpeed;
                    $acdat = '';
                    $acdat = array();
                    foreach ($info as $inf) {
                        if ($inf->status != 'F') {
                            $acdat[] = $inf;
                        }
                    }
                    if ($acsensor == '1') {
                        $record['gensetusage'] = gensetusage($acdat, $acinvertval);
                    } else {
                        $record['gensetusage'] = 0;
                    }
                    //Tampering PowerCut Overspeed FenceConflict
                    $times = monitoring($device->vehicleid, $device->customerno, $info, $overspeed_limit);
                    $record['overspeed'] = $times[0];
                    $record['date'] = $date;
                    $record['lat'] = $last_lat;
                    $record['long'] = $last_long;
                    return $record;
                }

                function GetOdometer_Max($date, $unitno) {
                    $date = substr($date, 0, 11);
                    $customerno = $_SESSION['customerno'];
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
                    $ODOMETER = 0;
                    if (file_exists($location)) {
                        $path = "sqlite:$location";
                        $db = new PDO($path);
                        $query = "SELECT max(odometer) as odometerm from vehiclehistory";
                        $result = $db->query($query);
                        foreach ($result as $row) {
                            $ODOMETER = $row['odometerm'];
                        }
                    }
                    return $ODOMETER;
                }

                function displaytraveldata($datarows) {
                    $runningtime = 0;
                    $idletime = 0;
                    if (isset($datarows)) {
                        foreach ($datarows as $change) {
                            //Removing Date Details From DateTime
                            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
                            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
                            $hour = floor(($change->duration) / 60);
                            $minutes = ($change->duration) % 60;
                            if ($change->ignition == 1) {
                                $runningtime += $minutes + ($hour * 60);
                            } else {
                                $idletime += $minutes + ($hour * 60);
                            }
                        }
                    }
                    $utilizationtime[0] = $runningtime;
                    $utilizationtime[1] = $idletime;
                    return $utilizationtime;
                }

                function processtraveldata($devicedata, $lastrow) {
                    //    print_r($lastrow);
                    $count = count($devicedata);
                    $devices2 = $devicedata;
                    $data = Array();
                    $datalen = count($devices2);
                    if (isset($devices2) && count($devices2) > 1) {
                        foreach ($devices2 as $device) {
                            $datacap = new stdClass();
                            $laststatus = $device->ignition;
                            $datacap->ignition = $device->ignition;
                            $ArrayLength = count($data);
                            if ($ArrayLength == 0) {
                                //echo $firstidle = '1st starttime'.$device->lastupdated.'_'.$device->id.'<br>';
                                $datacap->starttime = $device->lastupdated;
                                $datacap->startlat = $device->devicelat;
                                $datacap->startlong = $device->devicelong;
                                $datacap->startodo = $device->odometer;
                            } elseif ($ArrayLength == 1) {
                                //Filling Up First Array --- Array[0]
                                //echo $firstidle = '1st starttime'.$device->lastupdated.'_'.$device->id.'<br>';
                                $data[0]->endlat = $device->devicelat;
                                $data[0]->endlong = $device->devicelong;
                                $data[0]->endtime = $device->lastupdated;
                                $data[0]->endodo = $device->odometer;
                                $data[0]->distancetravelled = $data[0]->endodo / 1000 - $data[0]->startodo / 1000;
                                $data[0]->duration = getduration_dashboard($data[0]->endtime, $data[0]->starttime);
                                $datacap->starttime = $data[0]->endtime;
                                $datacap->startlat = $data[0]->endlat;
                                $datacap->startlong = $data[0]->endlong;
                                $datacap->startodo = $data[0]->endodo;
                                $datacap->endtime = $lastrow->lastupdated;
                                $datacap->endlat = $lastrow->devicelat;
                                $datacap->endlong = $lastrow->devicelong;
                                $datacap->endodo = $lastrow->odometer;
                            } else {
                                $last = $ArrayLength - 1;
                                $data[$last]->endtime = $device->lastupdated;
                                $data[$last]->endlat = $device->devicelat;
                                $data[$last]->endlong = $device->devicelong;
                                $data[$last]->endodo = $device->odometer;
                                $data[$last]->duration = getduration_dashboard($data[$last]->endtime, $data[$last]->starttime);
                                $data[$last]->distancetravelled = $data[$last]->endodo / 1000 - $data[$last]->startodo / 1000;
                                $datacap->starttime = $data[$last]->endtime;
                                $datacap->startlat = $data[$last]->endlat;
                                $datacap->startlong = $data[$last]->endlong;
                                $datacap->startodo = $data[$last]->endodo;
                                if ($datalen - 1 == $ArrayLength) {
                                    $datacap->endtime = $lastrow->lastupdated;
                                    $datacap->endlat = $lastrow->devicelat;
                                    $datacap->endlong = $lastrow->devicelong;
                                    $datacap->endodo = $lastrow->odometer;
                                    $datacap->duration = getduration_dashboard($datacap->endtime, $datacap->starttime);
                                    $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
                                    $datacap->ignition = $device->ignition;
                                }
                            }
                            $data[] = $datacap;
                        }
                        if ($data != NULL && count($data) > 0) {
                            $optdata = optimizereptime($data);
                        }
                        return $optdata;
                    } elseif (isset($devices2) && count($devices2) == 1) {
                        $datacap = new stdClass();
                        $datacap->starttime = $devices2[0]->lastupdated;
                        $datacap->startlat = $devices2[0]->devicelat;
                        $datacap->startlong = $devices2[0]->devicelong;
                        $datacap->startodo = $devices2[0]->odometer;
                        $datacap->endtime = $lastrow->lastupdated;
                        $datacap->endlat = $lastrow->devicelat;
                        $datacap->endlong = $lastrow->devicelong;
                        $datacap->endodo = $lastrow->odometer;
                        $datacap->duration = getduration_dashboard($datacap->endtime, $datacap->starttime);
                        $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
                        $datacap->ignition = $devices2[0]->ignition;
                        $data[] = $datacap;
                        return $data;
                    }
                }

                function optimizereptime($data) {
                    $datarows = array();
                    $ArrayLength = count($data);
                    $currentrow = $data[0];
                    $a = 0;
                    while ($currentrow != $data[$ArrayLength - 1]) {
                        $i = 1;
                        while (($i + $a) < $ArrayLength - 1 && $data[$i + $a]->duration < 3) {
                            $i += 2;
                        }
                        $currentrow->endtime = $data[$i + $a - 1]->endtime;
                        $currentrow->endlat = $data[$i + $a - 1]->endlat;
                        $currentrow->endlong = $data[$i + $a - 1]->endlong;
                        $currentrow->endodo = $data[$i + $a - 1]->endodo;
                        $currentrow->duration = getduration_dashboard($currentrow->endtime, $currentrow->starttime);
                        $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
                        $datarows[] = $currentrow;
                        if (($a + $i) <= $ArrayLength - 1) {
                            $currentrow = $data[$i + $a];
                        }
                        $a += $i;
                        if (($a) >= $ArrayLength - 1) {
                            $currentrow = $data[$ArrayLength - 1];
                        }
                    }
                    if ($datarows != NULL) {
                        $checkop = end($datarows);
                        $checkup = end($data);
                        if ($checkop->endtime != $checkup->endtime) {
                            $currentrow->starttime = $checkop->endtime;
                            $currentrow->startlat = $checkop->endlat;
                            $currentrow->startlong = $checkop->endlong;
                            $currentrow->startodo = $checkop->endodo;
                            $currentrow->endtime = $checkup->endtime;
                            $currentrow->endlat = $checkup->endlat;
                            $currentrow->endlong = $checkup->endlong;
                            $currentrow->endodo = $checkup->endodo;
                            $currentrow->duration = getduration_dashboard($currentrow->endtime, $currentrow->starttime);
                            $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
                            $datarows[] = $currentrow;
                        }
                    } else {
                        $currentrow = end($data);
                        $currentrow->endlat = $currentrow->startlat;
                        $currentrow->endlong = $currentrow->startlong;
                        $currentrow->endtime = date('Y-m-d', strtotime($currentrow->starttime));
                        $currentrow->endtime .= " 23:59:59";
                        $currentrow->endodo = $currentrow->startodo;
                        $currentrow->duration = getduration_dashboard($currentrow->endtime, $currentrow->starttime);
                        $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
                        $datarows[] = $currentrow;
                    }
                    return $datarows;
                }

                function gensetusage($info, $acinvertval) {
                    $days = array();
                    $data = getacdata($info);
                    if ($data != NULL && count($data) > 1) {
                        $report = createrep($data);
                        if ($report != NULL) {
                            $days = array_merge($days, $report);
                        }
                    }
                    if ($days != NULL && count($days) > 0) {
                        $finalreport = getacusagereport($days, $acinvertval);
                    }
                    return $finalreport;
                }

                function getacdata($info) {
                    $count = count($info);
                    $devices = array();
                    if ($count > 1) {
                        $DRM2 = new stdClass();
                        $DRM2->ignition = $data[$count - 1]->ignition;
                        $DRM2->status = $data[$count - 1]->status;
                        $DRM2->lastupdated = $data[$count - 1]->lastupdated;
                        $DRM2->digitalio = $data[$count - 1]->digitalio;
                        $devices2 = $DRM2;
                        unset($info[$count - 1]);
                        foreach ($info as $data) {
                            if (@$laststatus['digitalio'] != $data->digitalio) {
                                $DRM = new stdClass();
                                $DRM->ignition = $data->ignition;
                                $DRM->status = $data->status;
                                $DRM->lastupdated = $data->lastupdated;
                                $DRM->digitalio = $data->digitalio;
                                $laststatus['ig'] = $data->ignition;
                                $laststatus['digitalio'] = $data->digitalio;
                                $devices[] = $DRM;
                            }
                        }
                        $devices[] = $devices2;
                        return $devices;
                    }
                }

                function monitoring($vehicleid, $custno, $deviceinfo, $overspeed_limit) {
                    //Statuses
                    $tamper = 0;
                    $powercut = 1;
                    $overspeed = 0;
                    //Counters
                    $tampercount = 0;
                    $overspeedcount = 0;
                    $powercutcount = 0;
                    foreach ($deviceinfo as $device) {
                        if ($device->tamper == 1 && $tamper == 0) {
                            $tampercount += 1;
                            $tamper = 1;
                        } elseif ($device->tamper == 0 && $tamper == 1) {
                            $tamper = 0;
                        }
                        if ($device->powercut == 0 && $powercut == 0) {
                            $powercutcount += 1;
                            $powercut = 1;
                        } elseif ($device->powercut == 1 && $powercut == 1) {
                            $powercut = 0;
                        }
                        if ($device->curspeed > $overspeed_limit && $overspeed == 0) {
                            $overspeedcount += 1;
                            $overspeed = 1;
                        } elseif ($device->curspeed <= $overspeed_limit && $overspeed == 1) {
                            $overspeed = 0;
                        }
                    }
                    $counters[0] = $overspeedcount;
                    $counters[1] = $tampercount;
                    $counters[2] = $powercutcount;
                    return $counters;
                }

                function utilization($ignitionchange) {
                    //Calculation Variables
                    //Idle
                    $off = 0;
                    $idletime = 0;
                    $firstidletime = 0;
                    $lastidletime = 0;
                    //Running
                    $on = 0;
                    $runningtime = 0;
                    $firstrunningtime = 0;
                    $lastrunningtime = 0;
                    $counter = 0;
                    $ArrayLength = count($ignitionchange);
                    if (isset($ignitionchange)) {
                        foreach ($ignitionchange as $change) {
                            //IdleTime Calculation
                            if ($change->ignition == '0' && $off == 0) {
                                $firstidletime = strtotime($change->lastupdated);
                                $off = 1;
                            } elseif ($change->ignition == '1' && $off == 1) {
                                $lastidletime = strtotime($change->lastupdated);
                                $off = 0;
                                $idleduration = $lastidletime - $firstidletime;
                                $idletime += round($idleduration / 60);
                            }
                            //Running Time Calculation
                            if ($change->ignition == '1' && $on == 0) {
                                $firstrunningtime = strtotime($change->lastupdated);
                                $on = 1;
                            } elseif ($change->ignition == '0' && $on == 1) {
                                $lastrunningtime = strtotime($change->lastupdated);
                                $on = 0;
                                $runduration = $lastrunningtime - $firstrunningtime;
                                $runningtime += round($runduration / 60);
                            }
                            // For The Last Row Exception
                            if ($counter == ($ArrayLength - 1)) {
                                if ($off == 1) {
                                    $lastidletime = strtotime($change->lastupdated);
                                    $idleduration = $lastidletime - $firstidletime;
                                    $idletime += round($idleduration / 60);
                                } elseif ($on == 1) {
                                    $lastrunningtime = strtotime($change->lastupdated);
                                    $runduration = $lastrunningtime - $firstrunningtime;
                                    $runningtime += round($runduration / 60);
                                }
                            }
                            $counter += 1;
                        }
                    }
                    $utilizationtime[0] = $runningtime;
                    $utilizationtime[1] = $idletime;
                    return $utilizationtime;
                }

                function createrep($data) {
                    $currentrow = new stdClass();
                    $currentrow->digitalio = $data[0]->digitalio;
                    $currentrow->ignition = $data[0]->ignition;
                    $currentrow->starttime = $data[0]->lastupdated;
                    $currentrow->endtime = 0;
                    $gen_report = array();
                    $a = 0;
                    $counter = 0;
                    //Creating Rows Of Data Where Duration Is Greater Than 3
                    while (TRUE) {
                        $i = 1;
                        /* while(isset($data[$a+$i]) && getduration_dashboard($data[$a+$i]->lastupdated,$currentrow->starttime)<3)
                        {
                        $i+=1;
                        } */
                        while (isset($data[$a + $i]) && checkdate_status($data[$a + $i], $currentrow, $data, ($a + $i))) {
                            $i += 1;
                        }
                        if (isset($data[$a + $i])) {
                            $currentrow->endtime = $data[$a + $i]->lastupdated;
                            $currentrow->duration = round(getduration_dashboard($currentrow->endtime, $currentrow->starttime), 0);
                            $gen_report[] = $currentrow;
                            $currentrow = new stdClass();
                            $currentrow->starttime = $data[$a + $i]->lastupdated;
                            $currentrow_count = $a + $i;
                            //Just To Check That Data For The Next Row Is Not Wrong
                            while (isset($data[$a + $i + 1]) && getduration_dashboard($data[$a + $i + 1]->lastupdated, $currentrow->starttime) < 3) {
                                $i += 1;
                            }
                            if (($a + $i) > $currentrow_count) {
                                $gen_report[$counter]->endtime = $data[$a + $i]->lastupdated;
                                $gen_report[$counter]->duration = round(getduration_dashboard($gen_report[$counter]->endtime, $gen_report[$counter]->starttime), 0);
                                $currentrow->starttime = $data[$a + $i]->lastupdated;
                            }
                            $currentrow->digitalio = $data[$a + $i]->digitalio;
                            $currentrow->ignition = $data[$a + $i]->ignition;
                            $a += $i;
                        } else {
                            break;
                        }
                        $counter += 1;
                    }
                    //var_dump($gen_report);
                    //Clubing Data With Similar AC & Ignition [Both Together]
                    $gen_report = optimizerep_clean($gen_report);
                    return $gen_report;
                }

                function checkdate_status($data, $currentrow, $entire_array, $currentrowcount) {
                    $duration = getduration_dashboard($data->lastupdated, $currentrow->starttime);
                    if ($duration > 3) {
                        return FALSE;
                    } else {
                        if (isset($entire_array[$currentrowcount + 1])) {
                            if (getduration_dashboard($entire_array[$currentrowcount + 1]->lastupdated, $currentrow->starttime) > 3) {
                                return FALSE;
                            } else {
                                return TRUE;
                            }
                        }
                        return FALSE;
                    }
                }

                function optimizerep_clean($gen_report) {
                    while (TRUE) {
                        $gen_report = markremove($gen_report);
                        $remove = 0;
                        $count = count($gen_report);
                        for ($i = 0; $i <= $count; $i++) {
                            if (isset($gen_report[$i]) && $gen_report[$i] == 'Remove') {
                                $remove += 1;
                                unset($gen_report[$i]);
                            }
                        }
                        if ($remove != 0) {
                            $a = $gen_report;
                            $gen_report = array();
                            $i = 0;
                            if (isset($a)) {
                                foreach ($a as $value) {
                                    $gen_report[$i] = $value;
                                    $i += 1;
                                }
                            }
                        } else {
                            break;
                        }
                    }
                    $i = 0;
                    $array_length = count($gen_report);
                    while (TRUE) {
                        if ($i < $array_length - 1) {
                            if (isset($gen_report[$i]) && $gen_report[$i]->duration < 3) {
                                $gen_report[$i - 1]->endtime = $gen_report[$i]->endtime;
                                $gen_report[$i - 1]->duration = round(getduration_dashboard($gen_report[$i - 1]->endtime, $gen_report[$i - 1]->starttime), 0);
                                unset($gen_report[$i]);
                            }
                        } else {
                            break;
                        }
                        $i += 1;
                    }
                    $a = $gen_report;
                    $gen_report = array();
                    $i = 0;
                    if (isset($a)) {
                        foreach ($a as $value) {
                            $gen_report[$i] = $value;
                            $i += 1;
                        }
                    }
                    return $gen_report;
                }

                function markremove($gen_report) {
                    //var_dump($gen_report);
                    $i = 0;
                    while (TRUE) {
                        if (isset($gen_report[$i]) && isset($gen_report[$i + 1]) && $gen_report[$i] != 'Remove') {
                            if ($gen_report[$i]->ignition == $gen_report[$i + 1]->ignition && $gen_report[$i]->digitalio == $gen_report[$i + 1]->digitalio) {
                                $gen_report[$i]->endtime = $gen_report[$i + 1]->endtime;
                                $gen_report[$i]->duration = round(getduration_dashboard($gen_report[$i]->endtime, $gen_report[$i]->starttime), 0);
                                $gen_report[$i + 1] = 'Remove';
                            }
                        } elseif (isset($gen_report[$i]) && $gen_report[$i] == 'Remove') {
                            if (isset($gen_report[$i - 1]) && isset($gen_report[$i + 1])) {
                                if (gettype($gen_report[$i - 1]) == 'object' && $gen_report[$i - 1]->ignition == $gen_report[$i + 1]->ignition && $gen_report[$i - 1]->digitalio == $gen_report[$i + 1]->digitalio) {
                                    $gen_report[$i - 1]->endtime = $gen_report[$i + 1]->endtime;
                                    $gen_report[$i - 1]->duration = round(getduration_dashboard($gen_report[$i + 1]->endtime, $gen_report[$i - 1]->starttime), 0);
                                    $gen_report[$i + 1] = 'Remove';
                                }
                            }
                        } else {
                            break;
                        }
                        $i += 1;
                    }
                    return $gen_report;
                }

                function getacusagereport($datarows, $acinvert) {
                    $runningtime = 0;
                    $idletime = 0;
                    $lastdate = NULL;
                    $display = '';
                    if (isset($datarows)) {
                        foreach ($datarows as $change) {
                            if ($acinvert == 1) {
                                if ($change->digitalio == 0) {
                                    $runningtime += $change->duration;
                                } else {
                                    $idletime += $change->duration;
                                }
                            } else {
                                if ($change->digitalio == 0) {
                                    $runningtime += $change->duration;
                                } else {
                                    $idletime += $change->duration;
                                }
                            }
                        }
                    }
                    if ($acinvert == 1) {
                        $display .= $idletime;
                    } else {
                        $display .= $runningtime;
                    }
                    return $display;
                }

                function getduration_dashboard($EndTime, $StartTime) {
                    $diff = strtotime($EndTime) - strtotime($StartTime);
                    $years = floor($diff / (365 * 60 * 60 * 24));
                    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                    $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                    $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
                    $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                    return $hours * 60 + $minutes;
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

            ?>
