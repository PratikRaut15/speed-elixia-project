<?php

require_once("global.config.php");
require_once("database.inc.php");

if (!class_exists('VODevices')) {

    class VODevices {

    }

}
if (!class_exists('object')) {

    class object {

    }

}

class reports {

    function __construct() {
        $this->db = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

    function gettempreport($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $switchto, $mail_type, $vgroupname = null) {

        $totaldays = $this->gendays($STdate, $EDdate);
        $finalreport = '';

        $days = array();

        $unit = $this->getunitdetailsfromdeviceid($deviceid, $customerno);

        if (isset($totaldays)) {
            foreach ($totaldays as $userdate) {
                //Date Check Operations
                $data = NULL;
                $STdate = date("Y-m-d", strtotime($STdate));
                if ($userdate == $STdate) {
                    $f_STdate = $userdate . " " . $stime . ":00";
                } else {
                    $f_STdate = $userdate . " 00:00:00";
                }
                $EDdate = date("Y-m-d", strtotime($EDdate));
                if ($userdate == $EDdate) {
                    $f_EDdate = $userdate . " " . $etime . ":00";
                } else {
                    $f_EDdate = $userdate . " 23:59:59";
                }
                $unitno = $unit['unitno'];
                $location = "../../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                if (file_exists($location)) {
                    $location = "sqlite:" . $location;
                    $data = $this->gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, false, null, $customerno);
                    $vehicleid = $data['vehicleid'];
                }
                if ($data[0] != NULL && count($data[0]) > 1) {
                    $days = array_merge($days, $data[0]);
                }
            }
        }
        if ($days != NULL && count($days) > 0) {

            $customer_details = $this->getcustomerdetail_byid($customerno);
            $fromdate = date('d-m-Y H:i', strtotime($STdate . ' ' . $stime));
            $todate = date('d-m-Y H:i', strtotime($EDdate . ' ' . $etime));
            $title = 'Temperature Report';
            if ($switchto == 3) {
                $subTitle = array(
                    "Warehouse: $vehicleno",
                    "Start Date: $fromdate",
                    "End Date: $todate",
                    "Interval: $interval mins"
                );
            } else {
                $subTitle = array(
                    "Vehicle No: $vehicleno",
                    "Start Date: $fromdate",
                    "End Date: $todate",
                    "Interval: $interval mins"
                );
            }
            if (!is_null($vgroupname)) {
                $subTitle[] = "Group-name: $vgroupname";
            }
            if ($mail_type == 'pdf') {
                $finalreport = $this->pdf_header($title, $subTitle, $customer_details);
            } else if ($mail_type == 'xls') {
                $finalreport = $this->excel_header($title, $subTitle, $customer_details);
            } else {
                return;
            }
            $finalreport .= "<hr /><br/><br/>
                    <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>";
            $veh_temp_details = '';
            if (isset($vehicleid)) {
                $veh_temp_details = $this->getunitdetailsfromvehid($vehicleid, $customerno);
            }
            $finalreport .= $this->create_temp_from_report($days, $unit, $customerno, $veh_temp_details);
        }

        return $finalreport;
    }

    // Temprature and Humidity In Pdf
    function gettemphumidityreport($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $switchto, $mail_type, $vgroupname = null) {

        $totaldays = $this->gendays($STdate, $EDdate);
        $finalreport = '';
        $veh_temp_details = Array();
        $days = array();

        $unit = $this->getunitdetailsfromdeviceid($deviceid, $customerno);

        if (isset($totaldays)) {
            foreach ($totaldays as $userdate) {
                //Date Check Operations
                $data = NULL;
                $STdate = date("Y-m-d", strtotime($STdate));
                if ($userdate == $STdate) {
                    $f_STdate = $userdate . " " . $stime . ":00";
                } else {
                    $f_STdate = $userdate . " 00:00:00";
                }
                $EDdate = date("Y-m-d", strtotime($EDdate));
                if ($userdate == $EDdate) {
                    $f_EDdate = $userdate . " " . $etime . ":00";
                } else {
                    $f_EDdate = $userdate . " 23:59:59";
                }
                $unitno = $unit['unitno'];
                $location = "../../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                if (file_exists($location)) {
                    $location = "sqlite:" . $location;
                    $data = $this->gethumiditydata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, false, null, $customerno);
                    $vehicleid = $data['vehicleid'];
                }
                if ($data[0] != NULL && count($data[0]) > 1) {
                    $days = array_merge($days, $data[0]);
                }
            }
        }
        if ($days != NULL && count($days) > 0) {
            $customer_details = $this->getcustomerdetail_byid($customerno);
            $fromdate = date('d-m-Y H:i', strtotime($STdate . ' ' . $stime));
            $todate = date('d-m-Y H:i', strtotime($EDdate . ' ' . $etime));
            $title = 'Humidity And Temperature Report';
            if ($switchto == 3) {
                $subTitle = array(
                    "Warehouse: $vehicleno",
                    "Start Date: $fromdate",
                    "End Date: $todate",
                    "Interval: $interval mins"
                );
            } else {
                $subTitle = array(
                    "Vehicle No: $vehicleno",
                    "Start Date: $fromdate",
                    "End Date: $todate",
                    "Interval: $interval mins"
                );
            }
            if (!is_null($vgroupname)) {
                $subTitle[] = "Group-name: $vgroupname";
            }
            if ($mail_type == 'pdf') {
                $finalreport = $this->pdf_header($title, $subTitle, $customer_details);
            } else if ($mail_type == 'xls') {
                $finalreport = $this->excel_header($title, $subTitle, $customer_details);
            } else {
                return;
            }

            $finalreport .= "<hr /><br/><br/>
                    <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>";
            //$veh_temp_details = '';
            if (isset($vehicleid)) {
                $veh_temp_details = $this->getunitdetailsfromvehid($vehicleid, $customerno);
            }
            $finalreport .= $this->create_humiditytemp_from_report($days, $unit, $veh_temp_details, $switchto, $customerno);
        }
        return $finalreport;
    }

    function create_temp_from_report($datarows, $vehicle, $custID = NULL, $veh_temp_details = null) {
        $i = 1;
        $tr = 0;
        $tr_abv_max = 0;
        $tr2_abv_max = 0;
        $tr3_abv_max = 0;
        $tr4_abv_max = 0;
        $totalminute = 0;
        $lastdate = NULL;
        $display = '';
        $tempconversion=new TempConversion();
        $tempconversion->unit_type=$vehicle->get_conversion;
        if (isset($datarows)) {
            $customerno = ($custID == null) ? $_SESSION['customerno'] : $custID;

            $cm_details = $this->getcustomerdetail_byid($customerno);
            $min_max_temp1 = $this->get_min_max_temp(1, $veh_temp_details, $cm_details->temp_sensors);
            $min_max_temp2 = $this->get_min_max_temp(2, $veh_temp_details, $cm_details->temp_sensors);
            $min_max_temp3 = $this->get_min_max_temp(3, $veh_temp_details, $cm_details->temp_sensors);
            $min_max_temp4 = $this->get_min_max_temp(4, $veh_temp_details, $cm_details->temp_sensors);

            $t1 = $this->getNameForTemp($vehicle['n1'], $customerno);
            if ($t1 == '') {
                $t1 = "Temperature 1";
            }
            $t2 = $this->getNameForTemp($vehicle['n2'], $customerno);
            if ($t2 == '') {
                $t2 = "Temperature 2";
            }
            $t3 = $this->getNameForTemp($vehicle['n3'], $customerno);
            if ($t3 == '') {
                $t3 = "Temperature 3";
            }
            $t4 = $this->getNameForTemp($vehicle['n4'], $customerno);
            if ($t4 == '') {
                $t4 = "Temperature 4";
            }

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

                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                    $display .= "
                        <tr style='background-color:#CCCCCC;font-weight:bold;'>
                            <td style='width:150px;height:auto;'>Time</td>
                            <td style='width:250px;height:auto;'>Location</td>";
                    $tdstring = '';
                    switch ($cm_details->temp_sensors) {
                        case 4:
                            $tdstring = "<td style='width:150px;height:auto;'>" . $t4 . "</td>";
                        case 3:
                            $tdstring = "<td style='width:150px;height:auto;'>" . $t3 . "</td>" . $tdstring;
                        case 2:
                            $tdstring = "<td style='width:150px;height:auto;'>" . $t2 . "</td>" . $tdstring;
                        case 1:
                            $tdstring = "<td style='width:150px;height:auto;'>" . $t1 . "</td>" . $tdstring;
                            break;
                    }
                    $display .= $tdstring;
                    $display .= "</tr>";

                    if ($cm_details->temp_sensors == 4) {
                        $display .= "<tr><td colspan='6' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                    }
                    if ($cm_details->temp_sensors == 3) {
                        $display .= "<tr><td colspan='5' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                    }
                    if ($cm_details->temp_sensors == 2) {
                        $display .= "<tr><td colspan='4' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                    }
                    if ($cm_details->temp_sensors == 1) {
                        $display .= "<tr><td colspan='3' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                    }

                    $i++;
                }

                //Removing Date Details From DateTimespeedConstants::DEFAULT_TIME
                $change->starttime = date(speedConstants::DEFAULT_DATETIME,strtotime($change->starttime));
                $change->endtime = date(speedConstants::DEFAULT_DATETIME,strtotime($change->endtime));

                $display .= "<tr><td style='width:150px;height:auto;'>" . date(speedConstants::DEFAULT_TIME, strtotime($change->starttime)) . "</td>";
                $location = $this->get_location_bylatlong($change->devicelat, $change->devicelong, $customerno);
                $display .= "<td style='width:250px;height:auto;'>$location</td>";

                // Temperature Sensors
                // Temperature Sensor

                $tdstringrecord = '';
                switch ($cm_details->temp_sensors) {
                    case 4 :

                        $temp4 = 'Not Active';
                        $s = "analog" . $vehicle['tempsen4'];
                        if ($vehicle['tempsen4'] != 0 && $change->$s != 0) {
                            $tempconversion->rawtemp=$change->$s;
                            $temp4 = getTempUtil($tempconversion);
                        } else
                            $temp4 = '-';

                        if ($temp4 != '-' && $temp4 != "Not Active") {
                            $tdstringrecord = "<td style='width:150px;height:auto;'>$temp4 &deg; C</td>";
                        } else {
                            $tdstringrecord = "<td style='width:150px;height:auto;'>$temp4</td>";
                        }

                        /* min temp */
                        $temp4_min = $this->set_summary_min_temp($temp4);
                        /* maximum temp */
                        $temp4_max = $this->set_summary_max_temp($temp4);
                        /* above max */
                        if ($temp4 > $min_max_temp4['temp_max_limit']) {
                            $tr4_abv_max++;
                        }

                    case 3:

                        $temp3 = 'Not Active';
                        $s = "analog" . $vehicle['tempsen3'];
                        if ($vehicle['tempsen3'] != 0 && $change->$s != 0) {
                            $tempconversion->rawtemp=$change->$s;
                            $temp3 = getTempUtil($tempconversion);
                        } else
                            $temp3 = '-';

                        if ($temp3 != '-' && $temp3 != "Not Active") {
                            $tdstringrecord = "<td style='width:150px;height:auto;'>$temp3 &deg; C</td>" . $tdstringrecord;
                        } else {
                            $tdstringrecord = "<td style='width:150px;height:auto;'>$temp3</td>" . $tdstringrecord;
                        }

                        /* min temp */
                        $temp3_min = $this->set_summary_min_temp($temp3);
                        /* maximum temp */
                        $temp3_max = $this->set_summary_max_temp($temp3);
                        /* above max */
                        if ($temp3 > $min_max_temp3['temp_max_limit']) {
                            $tr3_abv_max++;
                        }

                    case 2:

                        $temp2 = 'Not Active';
                        $s = "analog" . $vehicle['tempsen2'];
                        if ($vehicle['tempsen2'] != 0 && $change->$s != 0) {
                            $tempconversion->rawtemp=$change->$s;
                            $temp2 = getTempUtil($tempconversion);
                        } else
                            $temp2 = '-';

                        if ($temp2 != '-' && $temp2 != "Not Active") {
                            $tdstringrecord = "<td style='width:150px;height:auto;'>$temp2 &deg; C</td>" . $tdstringrecord;
                        } else {
                            $tdstringrecord = "<td style='width:150px;height:auto;'>$temp2</td>" . $tdstringrecord;
                        }

                        /* min temp */
                        $temp2_min = $this->set_summary_min_temp($temp2);
                        /* maximum temp */
                        $temp2_max = $this->set_summary_max_temp($temp2);
                        /* above max */
                        if ($temp2 > $min_max_temp2['temp_max_limit']) {
                            $tr2_abv_max++;
                        }

                    case 1:

                        $temp1 = 'Not Active';
                        $s = "analog" . $vehicle['tempsen1'];
                        if ($vehicle['tempsen1'] != 0 && $change->$s != 0) {
                            $tempconversion->rawtemp=$change->$s;
                            $temp1 = getTempUtil($tempconversion);
                        } else
                            $temp1 = '-';

                        if ($temp1 != '-' && $temp1 != "Not Active") {
                            $tdstringrecord = "<td style='width:150px;height:auto;'>$temp1 &deg; C</td>" . $tdstringrecord;
                        } else {
                            $tdstringrecord = "<td style='width:150px;height:auto;'>$temp1</td>" . $tdstringrecord;
                        }

                        /* min temp */
                        $temp1_min = $this->set_summary_min_temp($temp1);
                        /* maximum temp */
                        $temp1_max = $this->set_summary_max_temp($temp1);
                        /* above max */
                        if ($temp1 > $min_max_temp1['temp_max_limit']) {
                            $tr_abv_max++;
                        }
                }

                $display .= $tdstringrecord;
                $display .= '</tr>';
                $tr++;
            }
        }
        $display .= '</tbody>';
        $display .= '</table><br/>';
        $temp_data = '';
        switch ($cm_details->temp_sensors) {
            case 4:

                $abv_compliance = round(($tr4_abv_max / $tr) * 100, 2);
                $within_compliance = round((($tr - $tr4_abv_max) / $tr) * 100, 2);

                $temp_data = "<td style='text-align:center;'>
                    <table class='table newTable'><thead><tr><th><u>" . $t4 . "</u></th></tr></thead>
                    <tbody><tr><td>Minimum Temperature: $temp4_min &deg;C</td></tr>
                    <tr><td>Maximum Temperature: $temp4_max &deg;C</td></tr>
                    <tr><td>Total Reading: $tr</td></tr>
                    <tr><td>Total reading above {$min_max_temp4['temp_max_limit']} &deg;C: $tr4_abv_max</td></tr>
                    <tr><td>% above compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                    <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
                    </td>";

            case 3:

                $abv_compliance = round(($tr3_abv_max / $tr) * 100, 2);
                $within_compliance = round((($tr - $tr3_abv_max) / $tr) * 100, 2);

                $temp_data = "<td style='text-align:center;'>
            <table class='table newTable'><thead><tr><th><u>" . $t3 . "</u></th></tr></thead>
            <tbody><tr><td>Minimum Temperature: $temp3_min &deg;C</td></tr>
            <tr><td>Maximum Temperature: $temp3_max &deg;C</td></tr>
            <tr><td>Total Reading: $tr</td></tr>
            <tr><td>Total reading above {$min_max_temp3['temp_max_limit']} &deg;C: $tr3_abv_max</td></tr>
            <tr><td>% above compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
            <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
            </td>" . $temp_data;

            case 2:

                $abv_compliance = round(($tr2_abv_max / $tr) * 100, 2);
                $within_compliance = round((($tr - $tr2_abv_max) / $tr) * 100, 2);

                $temp_data = "<td style='text-align:center;'>
            <table class='table newTable'><thead><tr><th><u>Temperature2</u></th></tr></thead>
            <tbody><tr><td>Minimum Temperature: $temp2_min &deg;C</td></tr>
            <tr><td>Maximum Temperature: $temp2_max &deg;C</td></tr>
            <tr><td>Total Reading: $tr</td></tr>
            <tr><td>Total reading above {$min_max_temp2['temp_max_limit']} &deg;C: $tr2_abv_max</td></tr>
            <tr><td>% above compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
            <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
            </td>" . $temp_data;

            case 1:
                $abv_compliance = round(($tr_abv_max / $tr) * 100, 2);
                $within_compliance = round((($tr - $tr_abv_max) / $tr) * 100, 2);
                $temp_data = "<td style='text-align:center;'>
            <table class='table newTable'><thead><tr><th><u>" . $t1 . "</u></th></tr></thead>
            <tbody><tr><td>Minimum Temperature: $temp1_min &deg;C</td></tr>
            <tr><td>Maximum Temperature: $temp1_max &deg;C</td></tr>
            <tr><td>Total Reading: $tr</td></tr>
            <tr><td>Total reading above {$min_max_temp1['temp_max_limit']} &deg;C: $tr_abv_max</td></tr>
            <tr><td>% above compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
            <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
            </td>" . $temp_data;
                break;
        }

        $summary = "<table align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
        <thead>
            <tr><td colspan='100%' style='background-color:#CCCCCC;font-weight:bold;'>Statistics</td></tr>
        </thead>
        <tbody>
            <tr>$temp_data</tr>
        </tbody>
        </table>";

        $display .="$summary";

        return $display;
    }

    // format report to pdf format for temperature and humidity
    function create_humiditytemp_from_report($datarows, $vehicle, $veh_temp_details = null, $switchto, $custNo = NULL) {
        $i = 1;
        $tr = 0;
        $tr_abv_max = 0;
        $tr2_abv_max = 0;
        $tr3_abv_max = 0;
        $tr4_abv_max = 0;
        $totalminute = 0;
        $lastdate = NULL;
        $display = '';
        $tempconversion=new TempConversion();
        $tempconversion->unit_type=$vehicle->get_conversion;
        if (isset($datarows)) {
            $customerno = ($custNo == null) ? $_SESSION['customerno'] : $custNo;
            $cust_details = $this->getcustomerdetail_byid($customerno);

            $min_max_temp1 = $this->get_min_max_temp(1, $veh_temp_details, $cust_details->temp_sensors);
            $min_max_temp2 = $this->get_min_max_temp(1, $veh_temp_details, $cust_details->temp_sensors);
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

                    $display .= "<tr style='background-color:#CCCCCC;font-weight:bold;'>
                                                <td style='width:150px;height:auto;'>Time</td>";
                    if ($switchto != 3) {
                        $display .= "<td style='width:550px;height:auto;'>Location</td>";
                    }
                    $display .= "<td style='width:150px;height:auto;'>Humidity %</td>
                                                <td style='width:150px;height:auto;'>Temperature &deg;C</td>
                                                </tr>";
                    if ($switchto != 3) {
                        $display .= "<tr><td colspan='4' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>"
                                . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                    } else {
                        $display .= "<tr><td colspan='3' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>"
                                . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                    }
                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                    $i++;
                }

                //Removing Date Details From DateTime
                $change->starttime = date(speedConstants::DEFAULT_DATETIME,strtotime($change->starttime));
                $change->endtime = date(speedConstants::DEFAULT_DATETIME,strtotime($change->endtime));

                $starttime_disp = date(speedConstants::DEFAULT_TIME, strtotime($change->starttime));
                $display .= "<tr><td>$starttime_disp</td>";
                if ($switchto != 3) {
                    $location = get_location_detail($change->devicelat, $change->devicelong);
                    $display .= "<td>$location</td>";
                }
                // Temperature Sensor
                //print_r($_SESSION);
                if ($cust_details->use_humidity == 1) {
                    $temp = 'Not Active';
                    $s = "analog" . $vehicle['humidity'];
                    if ($vehicle['humidity'] != 0 && $change->$s != 0) {
                        $tempconversion->rawtemp=$change->$s;
                        $temp = getTempUtil($tempconversion);
                    } else
                        $temp = '-';

                    if ($temp != '-' && $temp != "Not Active")
                        $display .= "<td>$temp</td>";
                    else
                        $display .= "<td>$temp</td>";

                    /* min temp */
                    $temp1_min = $this->set_summary_min_temp($temp);
                    /* maximum temp */
                    $temp1_max = $this->set_summary_max_temp($temp);
                    /* above max */
                    if ($temp > $min_max_temp1['temp_max_limit']) {
                        $tr_abv_max++;
                    }
                }

                if ($cust_details->temp_sensors == 1) {
                    $temp1 = 'Not Active';
                    $s = "analog" . $vehicle['tempsen1'];
                    if ($vehicle['tempsen1'] != 0 && $change->$s != 0) {
                        $tempconversion->rawtemp=$change->$s;
                        $temp1 = getTempUtil($tempconversion);
                    } else {
                        $temp1 = '-';
                    }

                    if ($temp1 != '-' && $temp1 != "Not Active")
                        $display .= "<td>$temp1</td>";
                    else
                        $display .= "<td>$temp1</td>";

                    /* min temp */
                    $temp2_min = $this->set_summary_min_temp($temp1);
                    /* maximum temp */
                    $temp2_max = $this->set_summary_max_temp($temp1);
                    /* above max */
                    if ($temp1 > $min_max_temp2['temp_max_limit']) {
                        $tr2_abv_max++;
                    }
                }
                $display .= '</tr>';
                $tr++;
            }
        }
        $display .= '</tbody>';
        $display .= '</table>';

        $temp1_data = "<td style='text-align:center;'>
                                            <table class='table newTable' cellspacing =0;cellpadding=0; >
                                                <thead>
                                                <tr><th style='background-color:#CCCCCC;'><u>Humidity</u></th></tr>
                                                </thead>
                                                <tbody>
                                                <tr><td>Minimum Humidity: $temp1_min %</td></tr>
                                                <tr><td>Maximum Humidity: $temp1_max %</td></tr>
                                                <tr><td>Total Reading: $tr</td></tr>
                                                </tbody>
                                            </table>
                                        </td>";

        $temp2_data = "<td style='text-align:center;'>
                                            <table class='table newTable' cellspacing =0;cellpadding=0;>
                                                <thead>
                                                <tr><th style='background-color:#CCCCCC;'><u>Temperature</u></th></tr>
                                                </thead>
                                                <tbody>
                                                <tr><td>Minimum Temperature: $temp2_min &deg;C</td></tr>
                                                <tr><td>Maximum Temperature: $temp2_max &deg;C</td></tr>
                                                <tr><td>Total Reading: $tr</td></tr>
                                                </tbody>
                                            </table>
                                        </td>";
        $span = 2;

        $summary = "<br/> <table align='center' style='width: auto; font-size:13px; text-align:center;
                                                border:1px solid #000;' cellspacing =0;cellpadding=0;>
                                            <thead>
                                                <tr><th colspan=$span style='background-color:#CCCCCC;'>Statistics</th></tr>
                                            </thead>
                                            <tbody>
                                                <tr>$temp1_data$temp2_data</tr>
                                            </tbody>
                                            </table>";
        $display .= "$summary";
        return $display;
    }

    function getunitdetailsfromdeviceid($deviceid, $customerno) {

        $Query = "SELECT vehicle.fuel_min_volt, vehicle.fuel_max_volt, vehicle.fuelcapacity,vehicle.max_voltage,unit.unitno, unit.tempsen1, unit.tempsen2,unit.tempsen3,unit.tempsen4,unit.n1,unit.n2, unit.n3,unit.n4,unit.humidity,unit.acsensor, unit.fuelsensor, unit.is_ac_opp,unit.get_conversion FROM unit
            INNER JOIN devices ON devices.uid = unit.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            WHERE unit.customerno = '$customerno' AND devices.deviceid = '$deviceid'";
        $record = $this->db->query($Query, __FILE__, __LINE__);


        while ($row = $this->db->fetch_array($record)) {

            $unit['unitno'] = $row['unitno'];
            $unit['tempsen1'] = $row['tempsen1'];
            $unit['tempsen2'] = $row['tempsen2'];
            $unit['tempsen3'] = $row['tempsen3'];
            $unit['tempsen4'] = $row['tempsen4'];
            $unit['n1'] = $row['n1'];
            $unit['n2'] = $row['n2'];
            $unit['n3'] = $row['n3'];
            $unit['n4'] = $row['n4'];
            $unit['humidity'] = $row['humidity'];
            $unit['acsensor'] = $row['acsensor'];
            $unit['fuelsensor'] = $row['fuelsensor'];
            $unit['fuelcapacity'] = $row['fuelcapacity'];
            $unit['maxvoltage'] = $row['max_voltage'];
            $unit['fuel_min_vol'] = $row['fuel_min_volt'];
            $unit['fuel_max_volt'] = $row['fuel_max_volt'];
            $unit['isacopp'] = $row['is_ac_opp'];
            $unit['get_conversion'] = $row['get_conversion'];
            return $unit;
        }

        return NULL;
    }

    function gettempdata_fromsqlite($location, $deviceid, $interval, $startdate, $enddate, $graph = false, $unit = null, $customerno = null) {
        $devices = array();
        $graph_devices = array();
        $graph_ignition = array();
        $last_ignition = null;
        $vehicleid = null;
        $query = "SELECT devicehistory.ignition, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong, unithistory.vehicleid,unithistory.digitalio,
            unithistory.analog1, unithistory.analog2, unithistory.analog3, unithistory.analog4 from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.lastupdated BETWEEN '$startdate' AND '$enddate' AND devicehistory.deviceid= $deviceid AND devicehistory.status!='F' group by devicehistory.lastupdated";
        try {
            $database = new PDO($location);
            $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";

            $result = $database->query($query1);

            if (isset($result) && $result != "") {
                $total = 0;
                foreach ($result as $row) {

                    $total++;

                    if (!isset($lastupdated)) {
                        $lastupdated = $row['lastupdated'];
                    }
                    if (!isset($vehicleid)) {
                        $vehicleid = $row['vehicleid'];
                    }

                    if (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= $interval) {
                        $device = new VODevices();
                        $device->analog1 = $row['analog1'];
                        $device->analog2 = $row['analog2'];
                        $device->analog3 = $row['analog3'];
                        $device->analog4 = $row['analog4'];
                        $device->starttime = $row['lastupdated'];
                        $device->endtime = $row['lastupdated'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->digitalio = $row['digitalio'];
                        $devices[] = $device;
                        $lastupdated = $row['lastupdated'];
                    }
                }
            }
        } catch (PDOException $e) {
            die($e);
        }
        return array($devices, 'vehicleid' => $vehicleid);
    }

    function gethumiditydata_fromsqlite($location, $deviceid, $interval, $startdate, $enddate, $graph = false, $unit = null, $customerno = null) {
        $devices = array();
        $graph_devices = array();
        $graph_ignition = array();
        $last_ignition = null;
        //print_r($unit);die();
        $query = "SELECT devicehistory.ignition, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong, unithistory.vehicleid,unithistory.digitalio,
            unithistory.analog1, unithistory.analog2, unithistory.analog3, unithistory.analog4 from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.lastupdated BETWEEN '$startdate' AND '$enddate' AND devicehistory.deviceid= $deviceid AND devicehistory.status!='F' group by devicehistory.lastupdated";
        $SQL = "select unit.get_conversion FROM unit INNER JOIN devices on devices.uid=unit.uid WHERE devices.deviceid=$deviceid";
        $this1 = new DatabaseManager();
        $this1->executeQuery($SQL);
        if ($this1->get_rowCount() > 0) {
            while ($row = $this1->get_nextRow()) {
                $unit_type = $row['get_conversion'];
            }
        }
        try {
            $database = new PDO($location);
            $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
            $result = $database->query($query1);
            if (isset($result) && $result != "") {
                $total = 0;
                foreach ($result as $row) {

                    $total++;

                    if (!isset($lastupdated)) {
                        $lastupdated = $row['lastupdated'];
                    }

                    if ($graph && round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= 1) {
                        $temp_val = set_humidity_graph_data($row['lastupdated'],$unit_type, $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], false);
                        if (!is_null($temp_val)) {
                            $graph_devices[] = $temp_val;
                        }
                    }

                    if (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= $interval) {
                        $device = new VODevices();
                        $device->analog1 = $row['analog1'];
                        $device->analog2 = $row['analog2'];
                        $device->analog3 = $row['analog3'];
                        $device->analog4 = $row['analog4'];
                        $device->starttime = $row['lastupdated'];
                        $device->endtime = $row['lastupdated'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->digitalio = $row['digitalio'];
                        $devices[] = $device;
                        $lastupdated = $row['lastupdated'];
                    }

                    if ($graph && $last_ignition != $row['ignition']) {
                        if (isset($ig_lastupdated)) {
                            $graph_ignition[] = $this->set_humidity_graph_data($row['lastupdated'],$unit_type, null, null, null, null, null, "#$last_ignition#");
                        }
                        $graph_ignition[] = $this->set_humidity_graph_data($row['lastupdated'],$unit_type, null, null, null, null, null, "#{$row['ignition']}#");
                        $last_ignition = $row['ignition'];
                        $ig_lastupdated = $row['lastupdated'];
                    }
                }
                if ($graph) {
                    $graph_ignition[] = $this->set_humidity_graph_data($row['lastupdated'],$unit_type, null, null, null, null, null, "#{$row['ignition']}#");
                }
            }
        } catch (PDOException $e) {
            die($e);
        }
        if ($graph) {
            return array($devices, $graph_devices, 'vehicleid' => $row['vehicleid'], 'ig_graph' => $graph_ignition);
        } else {
            return array($devices, 'vehicleid' => $row['vehicleid']);
        }
    }

    function set_humidity_graph_data($updated_date,$unit_type, $unit, $analog1, $analog2, $analog3, $analog4, $only_date = false) {

        $str_ch = strtotime($updated_date);
        $yr = date('Y', $str_ch);
        $mth = date('m', $str_ch) - 1;
        $day = date('d', $str_ch);
        $hour = date('H', $str_ch);
        $mins = date('i', $str_ch);
        $temp = null;
        $tempconversion=new TempConversion();
        $tempconversion->unit_type=$unit_type;
        if ($only_date) {
            return "[Date.UTC($yr, $mth, $day, $hour, $mins), $only_date]";
        }
        if ($_SESSION['use_humidity'] == 1) {
            $s = "analog" . $unit->humidity;
            if ($unit->humidity != 0 && $$s != 0) {
                $tempconversion->rawtemp=$$s;
                $temp = getTempUtil($tempconversion);
            }
        }

        /**/

        if (!is_null($temp) && $temp != '0' && $temp != '-') {
            $this->get_min_temperature($temp);
            $this->get_max_temperature($temp);
            return "[Date.UTC($yr, $mth, $day, $hour, $mins), $temp]";
        } else {
            return null;
        }
    }

    function getunitdetailsfromvehid($vehicleid, $customerno) {
        $Query = "SELECT vehicle.fuel_min_volt, vehicle.fuel_max_volt, vehicle.fuelcapacity,vehicle.max_voltage,unit.fuelsensor, vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max,vehicle.temp3_min, vehicle.temp3_max,vehicle.temp4_min, vehicle.temp4_max, unit.unitno, unit.tempsen1, unit.tempsen2, unit.tempsen3, unit.tempsen4 FROM unit INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            WHERE unit.customerno = '$customerno' AND unit.vehicleid = '$vehicleid'";

        $record = $this->db->query($Query, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($record)) {
            $unit = new VODevices();
            $unit->unitno = $row['unitno'];
            $unit->tempsen1 = $row['tempsen1'];
            $unit->tempsen2 = $row['tempsen2'];
            $unit->tempsen3 = $row['tempsen3'];
            $unit->tempsen4 = $row['tempsen4'];
            $unit->temp1_min = $row['temp1_min'];
            $unit->temp1_max = $row['temp1_max'];
            $unit->temp2_min = $row['temp2_min'];
            $unit->temp2_max = $row['temp2_max'];
            $unit->temp3_min = $row['temp3_min'];
            $unit->temp3_max = $row['temp3_max'];
            $unit->temp4_min = $row['temp4_min'];
            $unit->temp4_max = $row['temp4_max'];
            $unit->fuelsensor = $row['fuelsensor'];
            $unit->fuelcapacity = $row['fuelcapacity'];
            $unit->maxvoltage = $row['max_voltage'];
            $unit->fuel_min_volt = $row['fuel_min_volt'];
            $unit->fuel_max_volt = $row['fuel_max_volt'];
            return $unit;
        }

        return NULL;
    }

    function getcustomerdetail_byid($custid) {
        $SQL = sprintf("SELECT * FROM customer where customerno='$custid'");
        $record = $this->db->query($SQL, __FILE__, __LINE__);

        while ($row = $this->db->fetch_array($record)) {
            $customer = new VODevices();
            $customer->customerno = $row['customerno'];
            $customer->customername = $row['customername'];
            $customer->customercompany = $row['customercompany'];
            $customer->temp_sensors = $row['temp_sensors'];
            $customer->use_humidity = $row['use_humidity'];
            $customer->use_door_sensor = $row['use_door_sensor'];
            $customer->use_ac_sensor = $row['use_ac_sensor'];
            $customer->use_genset_sensor = $row['use_genset_sensor'];
            $customer->use_geolocation = $row['use_geolocation'];
        }
        return $customer;

        return false;
    }

    function getNameForTemp($nid, $customerno) {
        $Query = "Select * from nomens where nid='$nid' and customerno='$customerno'";
        $record = $this->db->query($Query, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($record)) {
            if ($row['name'] == 'Make Line') {
                $row['name'] = 'Vigi Cooler';
                return $row['name'];
            } else {
                return $row['name'];
            }
        }

        return null;
    }

    function get_min_max_temp($tempselect, $return, $temp_sensors = null) {
        $sess_temp_sensors = ($temp_sensors != null) ? $temp_sensors : $_SESSION['temp_sensors'];

        if ($sess_temp_sensors == 4) {
            if (isset($tempselect) && $tempselect == 1) {
                $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : 10;
                $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : 1;
            }
            if (isset($tempselect) && $tempselect == 2) {
                $temp_max_limit = isset($return->temp2_max) ? $return->temp2_max : 10;
                $temp_min_limit = isset($return->temp2_min) ? $return->temp2_min : 1;
            }
            if (isset($tempselect) && $tempselect == 3) {
                $temp_max_limit = isset($return->temp3_max) ? $return->temp3_max : 10;
                $temp_min_limit = isset($return->temp3_min) ? $return->temp3_min : 1;
            } else {
                $temp_max_limit = isset($return->temp4_max) ? $return->temp4_max : 10;
                $temp_min_limit = isset($return->temp4_min) ? $return->temp4_min : 1;
            }
        }

        if ($sess_temp_sensors == 3) {
            if (isset($tempselect) && $tempselect == 1) {
                $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : 10;
                $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : 1;
            }
            if (isset($tempselect) && $tempselect == 2) {
                $temp_max_limit = isset($return->temp2_max) ? $return->temp2_max : 10;
                $temp_min_limit = isset($return->temp2_min) ? $return->temp2_min : 1;
            } else {
                $temp_max_limit = isset($return->temp3_max) ? $return->temp3_max : 10;
                $temp_min_limit = isset($return->temp3_min) ? $return->temp3_min : 1;
            }
        }

        if ($sess_temp_sensors == 2) {
            if (isset($tempselect) && $tempselect == 1) {
                $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : 10;
                $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : 1;
            } else {
                $temp_max_limit = isset($return->temp2_max) ? $return->temp2_max : 10;
                $temp_min_limit = isset($return->temp2_min) ? $return->temp2_min : 1;
            }
        } else if ($sess_temp_sensors == 1) {

            $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : 10;
            $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : 1;
        }
        return array('temp_max_limit' => $temp_max_limit, 'temp_min_limit' => $temp_min_limit);
    }

    function set_summary_min_temp($temp) {
        static $minTemp;

        if ($minTemp == null) {
            $minTemp = $temp;
        }

        if ($temp < $minTemp) {
            $minTemp = $temp;
        }
        return $minTemp;
    }

    function set_summary_max_temp($temp) {
        static $maxTemp;

        if ($maxTemp == null) {
            $maxTemp = $temp;
        }

        if ($temp > $maxTemp) {
            $maxTemp = $temp;
        }
        return $maxTemp;
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

    function pdf_header($title, $subTitle, $customer_details = null) {
        $header = '<div style="width:auto; height:30px;">
<table style="width: auto; border:none;">
    <tr>
    <td style="width:430px; border:none;"><img style="width:50%; height: 50%;" src="../../../images/elixiaspeed_logo.png" /></td>
    <td style="width:420px; border:none;"><h3 style="text-transform:uppercase;">' . $title . '</h3><br /> </td>
    <td style="width:230px;border:none;"><img src="../../../images/elixia_logo_75.png"  /></td>
    </tr>
</table>
</div><hr />
<style type="text/css">
table, td { border: solid 1px   }
hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
</style>';

        $header .= $this->pdf_filter_details($title, $subTitle, $customer_details);
        return $header;
    }

    function excel_header($title, $subTitle, $customer_details = null) {
        $header = '';
        $currentdate = date(speedConstants::DEFAULT_DATETIME);
        $text_format = implode('<br/>', $subTitle);
        $company_name = '';
        $generated_by = '';

        if ($customer_details != null) {
            $company_name = "Company Name: $customer_details->customercompany<br/>Customer No: $customer_details->customerno<br/>";
        }

        $header = '
<div style="width:1120px;">
    <table style="width: 1120px;  border:1px solid;background-color:#CCCCCC;border-collapse:collapse;">
        <tr><td colspan="4" style="width:1120px; text-align: center; text-transform:uppercase;"><h4 style="text-transform:uppercase;">' . $title . '</h4></td></tr>
        <tr><td colspan="2" style="text-align:left;font-weight:bold;">' . $text_format . '<br/>' . $company_name . '<br/></td>
        <td colspan="2" style="text-align:right;font-weight:bold;">Generated On: ' . $currentdate . '</td>
        </tr>
    </table>
</div><hr/>
<style type="text/css">
table, td { border: solid 1px  #999999; color:#000000; }
hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
</style>
';
        return $header;
    }

    function pdf_filter_details($title, $subTitle, $customer_details = null) {
        $currentdate = date(speedConstants::DEFAULT_DATETIME);
        $text_format = implode('<br/>', $subTitle);
        $company_name = '';
        $generated_by = '';
        if (isset($_SESSION['customerno'])) {
            $company_name = "Company Name: " . $_SESSION['customercompany'] . "<br/>Customer No: " . $_SESSION['customerno'] . '<br/>';
            $generated_by = "Generated By: {$_SESSION['username']}<br/>";
        } elseif ($customer_details != null) {
            $company_name = "Company Name: $customer_details->customercompany<br/>Customer No: $customer_details->customerno<br/>";
            if (isset($_SESSION['report_gen_user'])) {
                $generated_by = "Generated By: {$_SESSION['report_gen_user']}<br/>";
            }
        }


        $finalreport = '
<table align="center" style="background-color:#CCCCCC;width: auto;font-weight:bold;font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;">
<tbody>
    <tr><td colspan="2" style="width:600px;height:auto;">' . $title . '</td></tr>
    <tr><td style="text-align:left;border-right:none;">' . $text_format . '<br/>' . $company_name . '<br/></td>
    <td>' . $generated_by . 'Generated on: ' . $currentdate . '</td>
    </tr>
</tbody></table><br/>';
        return $finalreport;
    }

    function get_location_bylatlong($lat, $long, $customerno) {
        $location_string = '';
        $latint = floor($lat);
        $longint = floor($long);
        $pdo = $this->db->CreatePDOConn();
        $geoloc_query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `lat` ) * PI( ) /180 /2 ) , 2 ) +
                         COS( " . $lat . " * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
                         AS distance FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " HAVING distance <2 AND customerno = " . $customerno . " ORDER BY distance LIMIT 0,1 ";
        $arrResult = $pdo->query($geoloc_query)->fetchAll(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
        if (count($arrResult) == 1) {
            if ($arrResult[0]['distance'] > 1) {
                $location_string = round($arrResult[0]['distance'], 2) . " Km from " . $arrResult[0]['location'] . ", " . $arrResult[0]['city'] . ", " . $arrResult[0]['state'];
            } else {
                $location_string = "Near " . $arrResult[0]['location'] . ", " . $arrResult[0]['city'] . ", " . $arrResult[0]['state'];
            }
        } else {
            $geolocation_query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `lat` ) * PI( ) /180 /2 ) , 2 ) +
                             COS( " . $lat . " * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
                             AS distance FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " HAVING distance <10 AND customerno IN(0, " . $customerno . ") ORDER BY distance LIMIT 0,1 ";

            $pdo = $this->db->CreatePDOConn();
            $arrResult = $pdo->query($geolocation_query)->fetchAll(PDO::FETCH_ASSOC);
            $this->db->ClosePDOConn($pdo);
            if (count($arrResult) == 1) {
                if ($arrResult[0]['distance'] > 1) {
                    $location_string = round($arrResult[0]['distance'], 2) . " Km from " . $arrResult[0]['location'] . ", " . $arrResult[0]['city'] . ", " . $arrResult[0]['state'];
                } else {
                    $location_string = "Near " . $arrResult[0]['location'] . ", " . $arrResult[0]['city'] . ", " . $arrResult[0]['state'];
                }
            } else {
                $location_string = "google temporarily down";
            }
        }
        return $location_string;
    }

    function save_pdf($full_path, $content) {

        require_once('../../reports/html2pdf.php');
        try {
            $html2pdf = new HTML2PDF('L', 'A4', 'en');
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($content);
            $html2pdf->Output($full_path, 'F');
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
    }

    function save_xls($full_path, $filename, $content) {
        require_once('../../../lib/bo/simple_html_dom.php');
        $html = str_get_html($content);
        $fp = fopen($full_path . $filename, "w");
        fwrite($fp, $html);
        fclose($fp);
    }
}

?>
