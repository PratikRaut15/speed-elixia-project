<?php

include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/CustomerManager.php';
include_once '../../lib/bo/GeoCoder.php';
include_once '../../lib/comman_function/reports_func.php';

if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}

function get_fuel_report($STdate, $STime, $EDdate, $ETime, $vehicleid, $interval, $customerno, $graph = true) {

    $um = new UnitManager($customerno);
    $unit = $um->getunitdetailsfromvehid($vehicleid);

    if ($unit->fuelsensor == 0) {
        return array('status' => 0, 'reason' => 'Fuel Sensor not found in this vehicle');
    } else {

        $totaldays = gendays_cmn($STdate, $EDdate);

        if (isset($totaldays)) {
            $all_data = array();
            $graph_data = array();
            $cm = new CustomerManager($customerno);
            $cust = $cm->getcustomerdetail_byid($customerno);

            $STdate = date("Y-m-d", strtotime($STdate));
            $EDdate = date("Y-m-d", strtotime($EDdate));

            foreach ($totaldays as $userdate) {
                $data = $gdata = NULL;
                $f_STdate = ($userdate == $STdate) ? $userdate . " " . $STime . ":00" : $userdate . " 00:00:00";
                $f_EDdate = ($userdate == $EDdate) ? $userdate . " " . $ETime . ":00" : $userdate . " 23:59:59";

                $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
                if (file_exists($location)) {
                    $location = "sqlite:" . $location;
                    $return = getfueldata_sqlite($location, $f_STdate, $f_EDdate, $interval, $unit, $cust, $graph);
                    $data = $return['tData'];
                    $gdata = $return['gData'];
                }
                if ($data != NULL && count($data) > 1) {
                    $all_data = array_merge($all_data, $data);
                }
                if ($gdata != NULL && count($gdata) > 1) {
                    $graph_data = array_merge($graph_data, $gdata);
                }
            }

            return array(
                'status' => 1,
                'tbl_data' => $all_data,
                'graph' => implode(',', $graph_data),
            );
        } else {
            return array('status' => 0, 'reason' => 'No data found');
        }
    }
}

function get_fuelcomsumption_pdf($customerno, $STdate, $STime, $EDdate, $ETime, $vehicleid, $vehicleno, $interval, $user = null, $vgroupname = null) {
    $STdate = date('d-m-Y', strtotime($STdate));
    $EDdate = date('d-m-Y', strtotime($EDdate));
    $vehicleid = (int) $vehicleid;
    $interval = (int) $interval;

    $datediffcheck = datediff_cmn($STdate, $EDdate);

    if (strtotime($STdate) > strtotime($EDdate)) {
        echo "Start Date is greater then End";
    } elseif ($vehicleid == 0) {
        echo "Vehicle id not found";
    } else {
        $reports = get_fuel_report($STdate, $STime, $EDdate, $ETime, $vehicleid, $interval, $customerno, false);

        $title = 'Fuel Report';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate $STime",
            "End Date: $EDdate $ETime",
        );

        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        echo pdf_header($title, $subTitle, $user);
        include 'pages/display_fuel_new_pdf.php'; //die();
    }
}

function get_fuelcomsumption_xls($customerno, $STdate, $STime, $EDdate, $ETime, $vehicleid, $vehicleno, $interval, $user = null, $vgroupname = null) {
    $STdate = date('d-m-Y', strtotime($STdate));
    $EDdate = date('d-m-Y', strtotime($EDdate));
    $vehicleid = (int) $vehicleid;
    $interval = (int) $interval;
    $datediffcheck = datediff_cmn($STdate, $EDdate);

    if (strtotime($STdate) > strtotime($EDdate)) {
        echo "Start Date is greater then End";
    } elseif ($vehicleid == 0) {
        echo "Vehicle id not found";
    } else {
        $reports = get_fuel_report($STdate, $STime, $EDdate, $ETime, $vehicleid, $interval, $customerno, false);

        $title = 'Fuel Report';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate $STime",
            "End Date: $EDdate $ETime",
        );

        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }

        echo excel_header($title, $subTitle, $user);
        include 'pages/display_fuel_new_xls.php';
    }
}

function getfueldata_sqlite($location, $f_STdate, $f_EDdate, $interval, $unit, $cust, $graph = false) {
    $min_c = $unit->fuel_min_volt; //value for empty voltage
    $max_c = $unit->fuel_max_volt; //value for max voltage
    //    $one = ($min_c+$max_c)/100; //value for 1 %
    $devices = array();
    $graph_devices = array();
    $query = "SELECT devicehistory.ignition, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong,
		unithistory.analog$unit->fuelsensor as fuelvalue from devicehistory
		INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
		WHERE devicehistory.lastupdated BETWEEN '$f_STdate' AND '$f_EDdate'
		ORDER BY devicehistory.lastupdated ASC";

    try {
        $database = new PDO($location);

        $result = $database->query($query);

        if (isset($result) && $result != "") {
            $total = 0;
            foreach ($result as $row) {
                $total++;
                $fuel_ltr = 0;
                if (!isset($lastupdated)) {
                    $lastupdated = $row['lastupdated'];
                }
                if (isset($unit->fuelMaxVoltCapacity) && $unit->fuel_max_volt > 0 && $unit->fuel_min_volt > 0 && ($unit->fuel_max_volt != $unit->fuel_min_volt)) {
                    $voltageValue = $row['fuelvalue'] / 100;
                    if ($voltageValue >= $unit->fuel_max_volt) {
                        $voltageValue = $unit->fuel_max_volt;
                    } elseif ($voltageValue <= $unit->fuel_min_volt) {
                        $voltageValue = $unit->fuel_min_volt;
                    }
                    $fuelInaccuracyPercentVar = 2;
                    // Restrict uptp 2 digits after the decimal point
                    // Using substr as don't want to round off the values.
                    $voltageNthPart = (float) substr('' . (($unit->fuel_max_volt - $unit->fuel_min_volt) / (100 / $fuelInaccuracyPercentVar)), 0, 4);
                    $floorNValue = floor(($voltageValue - $unit->fuel_min_volt) / $voltageNthPart);
                    $floorFuelVoltageValue = ($floorNValue * $voltageNthPart) + $unit->fuel_min_volt;
                    $fuel_ltr = ($unit->fuelMaxVoltCapacity / ($unit->fuel_max_volt - $unit->fuel_min_volt)) * ($floorFuelVoltageValue - $unit->fuel_min_volt);
                    $fuel_ltr = round($fuel_ltr, 2);
                }

                // $fuel_consumed = round($row['fuelvalue']/$one,2);
                //                $dimension = 256.122;  /// [17cm*162cm*93cm = 256122 cm to ltr 256.122]
                //                $fuel_ltr = round(256.122 * ($fuel_consumed/100),2);
                //                if($fuel_consumed>100){ //skipping wrong data
                //                   continue;
                //              }
                //if($unit->maxvoltage!=0){
                //  $fuel_consumed = ($row['fuelvalue']*$unit->fuelcapacity)/$unit->maxvoltage;
                //}
                $interval_diff = round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2);

                if ($graph && $interval_diff >= 1) {
                    $graph_devices[] = graph_value($row['lastupdated'], $fuel_ltr);
                }

                if ($interval_diff >= $interval) {
                    $device = new stdClass();
                    //$device->analog = $row['fuelvalue'];
                    $device->date = $row['lastupdated'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->ignition = $row['ignition'];
                    $device->fuel_consumed = $fuel_ltr;
                    $device->location = location_cmn($row['devicelat'], $row['devicelong'], $cust->use_geolocation, $cust->customerno);
                    $device->time = date(speedConstants::DEFAULT_TIME, strtotime($row['lastupdated']));
                    $devices[] = $device;
                    $lastupdated = $row['lastupdated'];
                }
            }
        }
    } catch (PDOException $e) {
        die($e);
    }

    return array(
        'tData' => $devices,
        'gData' => $graph_devices,
    );
}

function graph_value($updated_date, $value) {

    $str_ch = strtotime($updated_date);
    $yr = date('Y', $str_ch);
    $mth = date('m', $str_ch) - 1;
    $day = date('d', $str_ch);
    $hour = date('H', $str_ch);
    $mins = date('i', $str_ch);

    return "[Date.UTC($yr, $mth, $day, $hour, $mins), $value]";
}

?>
