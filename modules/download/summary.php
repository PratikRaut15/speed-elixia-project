<?php
include_once '../../lib/bo/CustomerManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/GeoCoder.php';
include_once '../../lib/comman_function/reports_func.php';


$s_date = "27-02-2015";
$s_date = strtotime($s_date);
$s_date1 = "09-03-2015";
$s_date1 = strtotime($s_date1);
$c_date = strtotime($date);

class VODatacap {

}

$cm = new CustomerManager($customer_id);
$customer = $cm->getcustomerdetail_byid($customer_id);

if ($s_date1 == $c_date) {
    $location = "../../customer/$customer_id/reports/dailyreport_new.sqlite";
    //$location = "../../customer/$customer_id/reports/dailyreport.sqlite";
} else {
    $location = "../../customer/$customer_id/reports/dailyreport.sqlite";
}

$cust_pass = strtolower(substr(preg_replace('/[\s\.]/', '', $customer->customername), 0, 3)) . $customer_id;

if (file_exists($location)) {
    if ($customer->use_location_summary == 1) {
        $begin = new DateTime($date);
        $begin->modify("last day of previous month");
        $end = new DateTime($date);
        $endCopy = new DateTime($date);
        $end->add(new DateInterval('P1D'));
        $interval = DateInterval::createFromDateString('1 day');
        $daterange = new DatePeriod($begin, $interval, $end);
        $diff = $endCopy->diff($begin);
        $dateArray = array();
        $DATA = NULL;
        if (($diff->days) > 0) {
            foreach ($daterange as $data) {
                $date = $data->format("Y-m-d");
                $dateArray[] = $data->format("Y-m-d");
                $DATA[$date] = GetDailyReport_Data($location, $date);
            }
        }
        $vehicle_group = array();
        $vehiclemanager = new VehicleManager($customer_id);
        $vehicle_group = $vehiclemanager->get_grouped_vehicles_by_groupid($customer_id, $groupid);

        if (is_array($DATA)) {
            foreach ($DATA as $keydate => $dates) {
                foreach ($dates as $vehicle_data) {
                    if (in_array($vehicle_data->vehicleid, $vehicle_group)) {
                        $dataPrepare1[$vehicle_data->vehicleid][] = $vehicle_data;
                    }
                }
            }
        }

        foreach ($dataPrepare1 as $vehicleid => $vehicles) {
            $datecount = 0;
            $vehicleCount = count($vehicles);
            foreach ($dateArray as $date1) {
                if ($datecount < $vehicleCount) {
                    if (in_array($date1, (array) ($vehicles[$datecount]))) {
                        $dataPrepare[$vehicleid][] = $vehicles[$datecount];
                        $datecount++;
                    } else {
                        $vehicledata = new stdClass();
                        $vehicledata->dates = $date1;
                        $vehicledata->totaldistance = 0;
                        $vehicledata->startlocation = '';
                        $vehicledata->endlocation = '';
                        $vehicledata->driverid = 0;
                        $vehicledata->extra = 0;
                        $dataPrepare[$vehicleid][] = $vehicledata;
                    }
                } else {
                    $vehicledata = new stdClass();
                    $vehicledata->dates = $date1;
                    $vehicledata->totaldistance = 0;
                    $vehicledata->startlocation = '';
                    $vehicledata->endlocation = '';
                    $vehicledata->driverid = 0;
                    $vehicledata->extra = 0;
                    $dataPrepare[$vehicleid][] = $vehicledata;
                }
            }
        }

        if (!empty($dataPrepare)) {
            $table = '<table style="width:100%;" border="1" cellpadding="5" cellspacing="1"><tr><td></td><td></td><td></td>';
            $counter = 0;
            foreach ($daterange as $data) {
                if ($counter++ == 0)
                    continue;
                $table .= '<td colspan="4">' . date('d M y', strtotime($data->format("Y-m-d"))) . '</td>';
            }
            $table .= '</tr><tr><td>Sr No</td><td>Vehicle Number</td><td>Group</td>';
            $counter = 0;
            foreach ($daterange as $data) {
                if ($counter++ == 0)
                    continue;
                $table .= '<td>Driver</td><td>Location (FROM to TO)</td><td>' . date('d-m-y', strtotime($data->format("Y-m-d") . '-1 day')) . ' (Previous day)Cumulative KM</td><td>' . date('d-m-y', strtotime($data->format("Y-m-d"))) . ' (Current day) KM</td>';
            }
            $table .= '</tr>';
            $i = 1;

            foreach ($dataPrepare as $vehicleid => $vehicle) {
                if (!empty($groupid)) {
                    if (in_array($vehicleid, $vehicle_group)) {
                        $vehicles = $vehiclemanager->get_vehicle_details_pdf($vehicleid, $customer->customerno);
                    } else {
                        continue;
                    }
                } else {
                    $vehicles = $vehiclemanager->get_vehicle_details_pdf($vehicleid, $customer->customerno);
                }
                $vehicleno = isset($vehicles->vehicleno) ? $vehicles->vehicleno : '';
                if ($vehicleno != '') {
                    $groupname = $cm->getgroupname_new(0, $customer->customerno, $vehicleid);
                    $table .= '<tr><td>' . $i . '</td><td>' . $vehicleno . '</td><td>' . $groupname . '</td>';
                    $previousDayKM = 0;
                    $count = 0;
                    if (is_array($vehicle)) {
                        foreach ($vehicle as $vehData) {

                            if ($count == 0) {
                                $previousDayKM = round(($vehData->totaldistance / 1000), 2);
                            }

                            if ($count > 0) {
                                if (!empty($vehData->startlocation) && !empty($vehData->endlocation)) {
                                    $vehlocation = $vehData->startlocation . ' To ' . $vehData->endlocation;
                                } else {
                                    $vehlocation = '';
                                }
                                $table .= '<td>' . $vehiclemanager->get_driver_by_driverid($vehData->driverid) . '</td>';
                                $table .= '<td>' . $vehlocation . '</td>';
                                $table .= "<td >" . $previousDayKM . "</td>";
                                $table .= "<td >" . round(($vehData->totaldistance / 1000), 2) . "</td>";
                                $previousDayKM = (($previousDayKM) + (round(($vehData->totaldistance / 1000), 2)));
                            }

                            $count++;
                        }
                    }

                    $table .= '</tr>';
                    $i++;
                }
            }
            require_once '../../lib/bo/simple_html_dom.php';
            $table .= '</table>';
            $html = file_get_contents('../emailtemplates/customer/' . $customer->customerno . '/templatename.html');

            $html = str_replace("{{DATE}}", date('d-m-Y', strtotime($date)), $html);
            $html = str_replace("{{CUSTOMERCOMPANY}}", $customer->customercompany, $html);
            $html = str_replace("{{CUSTOMERNO}}", $customer->customerno, $html);
            $html = str_replace("{{CURRDATE}}", date('d-m-Y'), $html);
            $html = str_replace("{{TABLE}}", $table, $html);

            $html = str_get_html($html);
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=" . date('d-m-Y', strtotime($date)) . "_summaryreport.xls");
            echo $html;
        }
    } else {
        $DATA = GetDailyReport_Data($location, $date);
        $data = '';
        if (isset($DATA)) {
            $vehiclemanager = new VehicleManager($customer_id);
            $geocode = '1';
            $i = 1;
            $data = '';
            $geocode = isset($_POST['geocode']) ? $_POST['geocode'] : null;
            $vehicle_group = array();
            if (!empty($groupid)) {
                $vehicle_group = $vehiclemanager->get_grouped_vehicles_by_groupid($customer_id, $groupid);
            }

            foreach ($DATA as $vehicle) {

                if (!empty($groupid)) {
                    if (in_array($vehicle->vehicleid, $vehicle_group)) {
                        $vehicles = $vehiclemanager->get_vehicle_details_pdf($vehicle->vehicleid, $customer->customerno);
                    } else {
                        continue;
                    }
                } else {
                    $vehicles = $vehiclemanager->get_vehicle_details_pdf($vehicle->vehicleid, $customer->customerno);
                }
                $groupname = $cm->getgroupname_new($vehicle->uid, $customer->customerno);
                $hours = floor($vehicle->runningtime / 60);
                $minutes = $vehicle->runningtime % 60;
                if ($minutes < 10) {
                    $minutes = '0' . $minutes;
                }
                $hourss = floor($vehicle->genset / 60);
                $minutess = $vehicle->genset % 60;
                if ($minutess < 10) {
                    $minutess = '0' . $minutess;
                }
                $data .= "<tr><td >$i</td><td >$vehicles->vehicleno</td>";
                $data .= "<td >$vehicles->drivername</td>";
                $data .= "<td >$groupname</td>";
                $location_first = getlocation($vehicle->first_dev_lat, $vehicle->first_dev_long, $geocode, $customer->customerno);
                $location = getlocation($vehicle->dev_lat, $vehicle->dev_long, $geocode, $customer->customerno);
                $data .= "<td style='width:150px;' >$location_first</td>";
                $data .= "<td style='width:150px;'>$location</td>";
                $data .= "<td >" . round(($vehicle->totaldistance / 1000), 2) . "</td>";
                if ($s_date <= $c_date) {
                    $data .= "<td>" . round(($vehicle->avgspeed / 1000), 2) . "</td>";
                } else {
                    $data .= "<td>" . $vehicle->avgspeed . "</td>";
                }


                $data .= "<td >$hours:$minutes</td>";
                if ($customer->use_door_sensor == 1 || $customer->use_genset_sensor == 1 || $customer->use_ac_sensor == 1) {
                    $data .= "<td >$hourss:$minutess</td>";
                }

                if ($s_date <= $c_date) {
                    $tsl = get_location_detail($vehicle->topspeed_lat, $vehicle->topspeed_long, $customer->customerno);
                    $data .= " <td> $vehicle->overspeed</td>";
                    //$data .= " <td> ".round(($vehicle->avgdistance / 1000) , 2)."</td>";
                    $data .= " <td>$vehicle->topspeed</td>";
                    $data .= " <td style='width:125px;' >$tsl</td>";

                }
                $data .= "</tr>";
                $i++;
            }
        }

        if ($type == 'pdf') {
            require_once('../reports/html2pdf.php');
            $title = 'Summary Report';
            $subTitle = array(
                "Date: $date"
            );
            echo pdf_header($title, $subTitle, $customer);
            ?>
            <br/><br/>
            <table id="search_table_2" style="width: 95%; border: none;padding-left: 30px;">
                <tr>
                    <td style="width:30%;border: none;">SL: Start Location</td>
                    <td style="width:40%;border: none;"></td>
                    <td style="width:30%;border: none;"></td>
                </tr>
                <tr>
                    <td style="width:30%;border: none;">EL: End Location </td>
                    <td style="width:40%;border: none;"></td>
                    <td style="width:30%;border: none;"></td>
                </tr>
                <tr>
                    <td style="width:30%;border: none;">DT: Distance Travelled [KM]</td>
                    <td style="width:40%;border: none;"></td>
                    <td style="width:30%;border: none;">  <?php if ($s_date <= $c_date) { ?> TS: Top Speed [KM] <?php } ?></td>
                </tr>
                <tr>
                    <td style="width:30%;border: none;">RT: Running Time [HH:MM]</td>
                    <td style="width:40%;border: none;"></td>
                    <td style="width:30%;border: none;"> <?php if ($s_date <= $c_date) { ?>TSL: Top Speed Location <?php } ?></td>
                </tr>
                <tr>
                    <td style="width:30%;border: none;">AS: Average Speed [KM/HR]</td>
                    <td style="width:40%;border: none;"></td>
                    <td style="width:30%;border: none;"> </td>
                </tr>
                <?php if ($s_date <= $c_date) { ?>
                    <tr>
                        <td style="width:30%;border: none;">OS: Overspeed [Times]</td>
                        <td style="width:40%;border: none;"></td>
                        <td style="width:30%;border: none;"></td>
                    </tr>
                    <tr>
                        <td style="width:30%;border: none;"></td>
                        <td style="width:40%;border: none;"></td>
                        <td style="width:30%;border: none;"></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td style="width:30%;border: none;">
                        <?php
                        if ($customer->use_door_sensor == 1) {
                            echo "<li>DSU: Door Sensor Usage [HH:MM]</li>";
                        }
                        if ($customer->use_ac_sensor == 1) {
                            echo "<li>ACU: AC Usage [HH:MM]</li>";
                        }
                        if ($customer->use_genset_sensor == 1) {
                            echo "<li>GNU: Genset Usage [HH:MM]</li>";
                        }
                        ?>
                    </td>
                    <td style="width:40%;border: none;"></td>
                    <td style="width:30%;border: none;"></td>
                </tr>
            </table>
            <br/><br/>
            <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>
                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                        <td >#</td>
                        <td  >Vehicle No</td>
                        <td style="width: 75px;">Driver Name</td>
                        <td style="width: 100px;">Group</td>
                        <td style="width: 150px;">SL</td>
                        <td style="width: 150px;">EL</td>
                        <td >DT</td>
                        <td >AS</td>
                        <td>RT</td>
                        <?php
                        if ($customer->use_door_sensor == 1) {
                            echo "<td >DSU</td>";
                        }
                        if ($customer->use_ac_sensor == 1) {
                            echo "<td >ACU</td>";
                        }
                        if ($customer->use_genset_sensor == 1) {
                            echo "<td >GNU</td>";
                        }
                        ?>
                        <?php if ($s_date <= $c_date) { ?>
                            <td>OS</td>

                            <td>TS</td>
                            <td style="width: 125px;">TSL</td>

                        <?php } ?>

                    </tr>
                    <?php
                    if (isset($data)) {
                        echo $data;
                    }
                    ?>
                </tbody>
            </table>
            <hr style='margin-top:5px;'>
            <?php if ($s_date <= $c_date) { ?>
                Note :  <br/>
                <ul style='float:left;text-align:left;'>
                    <li>- Daily Summary Report does not consider offline data. Offline Data is the data wherein the device is under a low network area and device sends data when it comes in network.
                    </li>
                    <li>- FreeWheeling - FreeWheeling either means riding on a downhill with ignition off to save fuel or there is some issue with the ignition connection. If you see Freewheeling on a frequent basis, please get the ignition wire connection checked.</li>
                    <li>- Online data field gives you an approximate indication of the actual time the device sent real time data.</li>
                    <li>- If you see any erratic data in this report, you may shoot an email to support@elixiatech.com and we will be there to support.
                    </li>

                    <li>- Towing is vehicle moving at 15 km / hr and ignition is off.</li>
                    <li>- When unit is replaced, daily summary report will be valid for the new unit only.</li>
                </ul>
            <?php } ?>
            <!--
            <div align='right' style='text-align:center;'> Report Generated On: <?php //echo date(speedConstants::DEFAULT_DATETIME);                                ?></div><hr>
            -->
            <?php
            $content = ob_get_clean();

            try {
                $html2pdf = new HTML2PDF('L', 'A4', 'en');
                $html2pdf->pdf->SetDisplayMode('fullpage');
                $html2pdf->writeHTML($content);
                $html2pdf->Output($date . "_summaryreport.pdf");
            } catch (HTML2PDF_exception $e) {
                echo $e;
                exit;
            }
        } else {
            require_once '../../lib/bo/simple_html_dom.php';
            $title = 'Summary Report';
            $subTitle = array(
                "Date: $date"
            );

            $finalreport = excel_header($title, $subTitle, $customer);
            $finalreport .= "<table><tr><td>DT: Distance Travelled [KM]</td></tr><tr><td>RT: Running Time [HH:MM]</td></tr><tr><td>AS: Average Speed [KM/HR]</td></tr></table>";
            $finalreport .= "
    <table id='search_table_2' style='width: 1000px; font-size:9px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
        <tbody>
        <tr style='background-color:#CCCCCC;font-weight:bold;'>
            <td >#</td>
            <td >Vehicle No</td>
            <td >Driver Name</td>
            <td>Group</td>
            <td >Start Location</td>
            <td >End Location</td>
            <td >DT</td>
            <td >AS</td>
            <td >RT</td>";

            if ($customer->customerno != 135) {
                if ($customer->use_door_sensor == 1) {
                    $finalreport .= "<td >Door Open [HH:MM]</td></tr>";
                } else {
                    $finalreport .= "<td >Genset/AC Usage [HH:MM]</td></tr>";
                }
            }
            if ($s_date <= $c_date) {
                $finalreport .= "<td>OS</td>";
                $finalreport .= '<td>AD</td>';
                $finalreport .= '<td>TS</td>';
                $finalreport .= '<td>TSL</td>';

            }
            $contentcsv = $finalreport;
            $contentcsv .= $data;
            $contentcsv .= "</tbody></table><hr style='margin-top:5px;'>";

            $html = str_get_html($contentcsv);
            header("Content-type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename={$date}_summaryreport.xls");
            echo $html;
        }
    }
}

function GetDailyReport_Data($location, $days) {
    $path = "sqlite:$location";
    $db = new PDO($path);
    $REPORT = array();
    $sqlday = date("dmy", strtotime($days));
    $query = "SELECT * from A$sqlday";
    $result = $db->query($query);

    if (isset($result) && $result != "") {
        foreach ($result as $row) {
            $Datacap = new VODatacap();
            $Datacap->date = strtotime($days);
            $Datacap->dates = $days;
            $Datacap->uid = $row['uid'];
            $Datacap->avgspeed = $row['avgspeed'];
            $Datacap->genset = $row['genset'];
            $Datacap->overspeed = $row['overspeed'];
            $Datacap->totaldistance = isset($row['totaldistance']) ? $row['totaldistance'] : 0;
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
            $Datacap->driverid = isset($row['driverid']) ? $row['driverid'] : 0;
            $Datacap->startlocation = isset($row['startlocation']) ? $row['startlocation'] : '';
            $Datacap->endlocation = isset($row['endlocation']) ? $row['endlocation'] : '';
            $REPORT[] = $Datacap;
        }
    }
    return $REPORT;
}

function get_location_detail($lat, $long, $customerno) {
    $geo_location = "N/A";
    if ($lat != "" && $long != "") {
        $geo_obj = new GeoCoder($customerno);
        $geo_location = $geo_obj->get_location_bylatlong($lat, $long);
    }
    return $geo_location;
}

function getlocation($lat, $long, $geocode, $customerno) {
    $address = null;
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
?>
