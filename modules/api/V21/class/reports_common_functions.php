<?php
include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/DeviceManager.php';
include_once '../../lib/bo/DriverManager.php';
include_once '../../lib/bo/CustomerManager.php';
include_once '../../lib/bo/UserManager.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/GroupManager.php';
include_once '../../lib/bo/CheckpointManager.php';
include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/GeoCoder.php';
include_once '../../lib/comman_function/reports_func.php';

class VODatacap {

}

if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}

function datediff($STdate, $EDdate) {
    if (strtotime($STdate) > strtotime($EDdate)) {
        return 0;
    } else
        return 1;
}

function date_SDiff($dt1, $dt2, $timeZone = 'GMT') {
    $tZone = new DateTimeZone($timeZone);
    $dt1 = new DateTime($dt1, $tZone);
    $dt2 = new DateTime($dt2, $tZone);
    $ts1 = $dt1->format('Y-m-d');
    $ts2 = $dt2->format('Y-m-d');
    $diff = abs(strtotime($ts1) - strtotime($ts2));
    $diff/= 3600 * 24;
    return $diff;
}

function get_location($lat, $long) {
    $geo_location = "N/A";
    if ($lat != "" && $long != "") {
        $geo_obj = new GeoCoder($_SESSION['customerno']);
        $geo_location = $geo_obj->get_city_bylatlong($lat, $long);
    }
    return $geo_location;
}

function get_location_pdf($lat, $long, $customerno) {
    $geo_location = "N/A";
    if ($lat != "" && $long != "") {
        $geo_obj = new GeoCoder($customerno);
        $geo_location = $geo_obj->get_city_bylatlong($lat, $long);
    }
    return $geo_location;
}

function get_usegeolocation($customerno) {
    $GeoCoder_Obj = new GeoCoder($customerno);
    $geolocation = $GeoCoder_Obj->get_use_geolocation($customerno);
    return $geolocation;
}

function get_location_detail($lat, $long, $custID = null) {
    $customerno = ($custID == null) ? $_SESSION['customerno'] : $custID;
    $usegeolocation = get_usegeolocation($customerno);
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

function getunitno($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getuidfromdeviceid($deviceid);
    return $unitno;
}

function getextra($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getextrafromdeviceid($deviceid);
    return $unitno;
}

function getunitdetails($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getunitdetailsfromdeviceid($deviceid);
    return $unitno;
}

function getName_ByType($nid) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicledata = $vehiclemanager->getNameForTemp($nid);
    return $vehicledata;
}

function getName_ByType_pdf($nid, $customerno) {
    $vehiclemanager = new VehicleManager($customerno);
    $vehicledata = $vehiclemanager->getNameForTemp($nid);
    return $vehicledata;
}

function getunitdetailsfromvehid($deviceid, $customerno = null) {
    if (isset($_SESSION['customerno'])) {
        $um = new UnitManager($_SESSION['customerno']);
    } else {
        $um = new UnitManager($customerno);
    }
    $unitno = $um->getunitdetailsfromvehid($deviceid);
    return $unitno;
}

function getunitdetailspdf($customerno, $deviceid) {
    $um = new UnitManager($customerno);
    $unitno = $um->getunitdetailsfromdeviceid($deviceid);
    return $unitno;
}

function getunitnopdf($customerno, $deviceid) {
    $um = new UnitManager($customerno);
    $unitno = $um->getuidfromdeviceid($deviceid);
    return $unitno;
}

function getacinvertval($unitno) {
    $um = new UnitManager($_SESSION['customerno']);
    $invertval = $um->getacinvertval($unitno);
    return $invertval['0'];
}

function getacinvertvalpdf($customerno, $unitno) {
    $um = new UnitManager($customerno);
    $invertval = $um->getacinvertval($unitno);
    return $invertval['0'];
}

function getvehicleno($deviceid) {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $vehicleno = $devicemanager->getvehiclenofromdeviceid($deviceid);
    return $vehicleno;
}

function getacreport($STdate, $EDdate, $deviceid) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    $acinvertval = getacinvertval($unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $finalreport = create_html_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

//full genset report start here
function getgensetreport($STdate, $EDdate, $deviceid) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    $acinvertval = getacinvertval($unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $finalreport = create_gensethtml_from_report($days, $acinvertval);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

//get genset report summary only -ganesh
function getgensetreportsummary($STdate, $EDdate, $deviceid) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    $acinvertval = getacinvertval($unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $finalreport = create_gensethtml_summary_from_report($days, $acinvertval);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

//genset summary end here
//get gensert report details only -ganesh
function getgensetreportdetails($STdate, $EDdate, $deviceid) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    $acinvertval = getacinvertval($unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $finalreport = create_genset_detail_html_from_report($days, $acinvertval);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

function getextragensetreport($STdate, $EDdate, $deviceid, $extraid, $extraval) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    //$acinvertval = getacinvertval($unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getextragensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $finalreport = create_extragensethtml_from_report($days, $extraid, $extraval);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr></tbody></table>";
    }
    echo $finalreport;
}

function gettemptabularreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime, $tripmin = null, $tripmax = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $graph_days = array();
    $graph_days_ig = array();
    $veh_temp_details = array();
    $customerno = $_SESSION['customerno'];
    $unit = getunitdetails($deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $graph_data = null;
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
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $return = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                $data = $return[0];
                $graph_data = $return[1];
                $graph_ig = $return['ig_graph'];
            }
            if ($data != NULL && count($data) > 1) {
                $days = array_merge($days, $data);
            }
            if ($graph_data != NULL && count($graph_data) > 1) {
                $graph_days = array_merge($graph_days, $graph_data);
                $graph_days_ig = array_merge($graph_days_ig, $graph_ig);
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        if (isset($return['vehicleid'])) {
            $veh_temp_details = getunitdetailsfromvehid($return['vehicleid'], $customerno);
        }

        if (isset($tripmin)) {
            $unit->temp1_min = $tripmin;
            $unit->temp2_min = $tripmin;
            $unit->temp3_min = $tripmin;
            $unit->temp4_min = $tripmin;
        }
        if (isset($tripmax)) {
            $unit->temp1_max = $tripmax;
            $unit->temp2_max = $tripmax;
            $unit->temp3_max = $tripmax;
            $unit->temp4_max = $tripmax;
        }
        $finalreport = create_temphtml_from_report($days, $unit, $veh_temp_details);
    } else {
        $finalreport = "<tr><td colspan='3'>No Data</td></tr>";
    }
    $graph_days_final = '';
    $graph_ig_final = '';
    if (!empty($graph_days)) {
        $graph_days_final = implode(',', $graph_days);
        $graph_ig_final = implode(',', $graph_days_ig);
    }
    return array($finalreport, $graph_days_final, $veh_temp_details, $graph_ig_final);
}

function gethumidityreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $graph_days = array();
    $graph_days_ig = array();
    $veh_temp_details = array();
    $customerno = $_SESSION['customerno'];
    $unit = getunitdetails($deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $graph_data = null;
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
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $return = gethumiditydata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                $data = $return[0];
                $graph_data = $return[1];
                $graph_ig = $return['ig_graph'];
            }
            if ($data != NULL && count($data) > 1) {
                $days = array_merge($days, $data);
            }
            if ($graph_data != NULL && count($graph_data) > 1) {
                $graph_days = array_merge($graph_days, $graph_data);
                $graph_days_ig = array_merge($graph_days_ig, $graph_ig);
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        if (isset($return['vehicleid'])) {
            $veh_temp_details = getunitdetailsfromvehid($return['vehicleid'], $customerno);
        }
        $finalreport = create_humidityhtml_from_report($days, $unit, $veh_temp_details);
    } else {
        $finalreport = "<tr><td colspan='3'>No Data</td></tr>";
    }
    $graph_days_final = '';
    $graph_ig_final = '';
    if (!empty($graph_days)) {
        $graph_days_final = implode(',', $graph_days);
        $graph_ig_final = implode(',', $graph_days_ig);
    }
    return array($finalreport, $graph_days_final, $veh_temp_details, $graph_ig_final);
}

function gethumiditytempreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $sqliteDeviceData = array();
    $graph_data_humidity = array();
    $graph_data_temperature = array();
    $veh_temp_details = array();
    $customerno = $_SESSION['customerno'];
    $unit = getunitdetails($deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $deviceData = null;
            $humidityReadings = null;
            $temperatureReadings = null;
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
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $returnTempData = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                $returnHumidityData = gethumiditydata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                $deviceData = $returnHumidityData[0];
                $humidityReadings = $returnHumidityData[1];
                $temperatureReadings = $returnTempData[1];
            }
            if ($deviceData != NULL && count($deviceData) > 1) {
                $sqliteDeviceData = array_merge($sqliteDeviceData, $deviceData);
            }
            if ($humidityReadings != NULL && count($humidityReadings) > 1) {
                $graph_data_humidity = array_merge($graph_data_humidity, $humidityReadings);
            }
            if ($temperatureReadings != NULL && count($temperatureReadings) > 1) {
                $graph_data_temperature = array_merge($graph_data_temperature, $temperatureReadings);
            }
        }
    }
    if ($sqliteDeviceData != NULL && count($sqliteDeviceData) > 0) {
        if (isset($returnHumidityData['vehicleid'])) {
            $veh_temp_details = getunitdetailsfromvehid($returnHumidityData['vehicleid'], $customerno);
        }
        $finalreport = create_humiditytemphtml_from_report($sqliteDeviceData, $unit, $veh_temp_details);
    } else {
        $finalreport = "<tr><td colspan='3'>No Data</td></tr>";
    }
    $humidityReadingsFinal = '';
    $temperatureReadingsFinal = '';
    if (!empty($graph_data_humidity)) {
        $humidityReadingsFinal = implode(',', $graph_data_humidity);
    }
    if (!empty($graph_data_temperature)) {
        $temperatureReadingsFinal = implode(',', $graph_data_temperature);
    }
    return array($finalreport, $humidityReadingsFinal, $veh_temp_details, $temperatureReadingsFinal);
}

function gettempExcepreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime, $tempselect) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $veh_temp_details = array();
    $customerno = $_SESSION['customerno'];
    $unit = getunitdetails($deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $graph_data = null;
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
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $return = gettempExcep_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                $data = $return[0];
            }
            if ($data != NULL && count($data) > 1) {
                $days = array_merge($days, $data);
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        if (isset($return['vehicleid'])) {
            $veh_temp_details = getunitdetailsfromvehid($return['vehicleid'], $customerno);
        }
        $datediff = strtotime($EDdate . " $etime") - strtotime($STdate . " $stime");
        $finalreport = create_temphtml_Excep_report($days, $unit, $veh_temp_details, $tempselect, count($totaldays), $datediff);
    } else {
        $finalreport = "<tr><td colspan='3'>No Data</td></tr>";
    }
    return array($finalreport, $veh_temp_details);
}

function getacreportpdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date('Y-m-d H:i:s');
        $formatdate = date('F j, Y', strtotime($STdate));
        $finalreport = '<div style="width:auto; height:30px;">
                        <table style="width: auto; border:none;">
                        <tr>
                        <td style="width:430px; border:none;"><img style="width:50%; height: 50%;" src="../../images/elixiaspeed_logo.png" /></td>
                        <td style="width:420px; border:none;">
                                                <h3 style="text-transform:uppercase;">Gen Set Sensor Report</h3><br />
                        </td>
                        <td style="width:230px;border:none;">
                        <img src="../../images/elixia_logo_75.png"  /></td>
                        </tr>
                        </table>
                        </div>';
        $finalreport .= "<hr />
<h4>
<div align='center' style='text-align:center;'>
Vehicle No. $vehicleno</div><div align='right' style='text-align:center;' >
 		$formatdate
</div>
</h4>
<style type='text/css'>
table, td { border: solid 1px  #999999; color:#000000; }
hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
</style>
                    <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>
                    <tr style='background-color:#CCCCCC;'>
                        <td style='width:100px;height:auto;'>Start Time</td>
                        <td style='width:100px;height:auto;'>End Time</td>
                        <td style='width:150px;height:auto;'>Ignition Status</td>
                        <td style='width:150px;height:auto;'>Gen Set Status</td>
                        <td style='width:150px;height:auto;'>Duration[Hours:Minutes]</td>
                    </tr>";
        $finalreport .= create_pdf_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

function getgensetreportpdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $finalreport = '';
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $filesize = filesize($location);
                if ($filesize > 0) {
                    $location = "sqlite:" . $location;
                    $data = getgensetdata_fromsqlite($location, $deviceid);
                }
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date('Y-m-d H:i:s');
        $formatdate = date('F j, Y', strtotime($STdate));
        $finalreport = '<div style="width:auto; height:30px;">
                        <table style="width: auto; border:none;">
                        <tr>
                        <td style="width:430px; border:none;"><img style="width:50%; height: 50%;" src="../../images/elixiaspeed_logo.png" /></td>
                        <td style="width:420px; border:none;">
                                                <h3 style="text-transform:uppercase;">Gen Set Sensor Report</h3><br />
                        </td>
                        <td style="width:230px;border:none;">
                        <img src="../../images/elixia_logo_75.png"  /></td>
                        </tr>
                        </table>
                        </div>';
        $finalreport .= "<hr />
<h4>
<div align='center' style='text-align:center;'>
Vehicle No. $vehicleno</div><div align='right' style='text-align:center;' >
 		$formatdate
</div>
</h4>
<style type='text/css'>
table, td { border: solid 1px  #999999; color:#000000; }
hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
</style>
                    <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>
                    <tr style='background-color:#CCCCCC;'>
                        <td style='width:100px;height:auto;'>Start Time</td>
                        <td style='width:100px;height:auto;'>End Time</td>
                        <td style='width:150px;height:auto;'>Gen Set Status</td>
                        <td style='width:150px;height:auto;'>Duration[Hours:Minutes]</td>
                    </tr>";
        $finalreport .= create_gensetpdf_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

function getacreportpdfMultipleDays($customerno, $STdate, $EDdate, $deviceid, $vehicleno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date('Y-m-d H:i:s');
        $formatdate = date('F j, Y', strtotime($STdate));
        $fromdate = date('F j, Y', strtotime($STdate));
        $todate = date('F j, Y', strtotime($EDdate));
        $finalreport = '<div style="width:auto; height:30px;">
                        <table style="width: auto; border:none;">
                        <tr>
                        <td style="width:430px; border:none;"><img style="width:50%; height: 50%;" src="../../images/elixiaspeed_logo.png" /></td>
                        <td style="width:420px; border:none;">
                                                <h3 style="text-transform:uppercase;">Gen Set Sensor Report</h3><br />
                        </td>
                        <td style="width:230px;border:none;">
                        <img src="../../images/elixia_logo_75.png"/></td>
                        </tr>
                        </table>
                        </div>';
        $finalreport .= "Vehicle No. $vehicleno
 			From :  $fromdate to : $todate";
        $finalreport .= "<hr />
<style type='text/css'>
table, td { border: solid 1px  #999999; color:#000000; }
hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
</style>
                    <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>";
        $finalreport .= create_pdf_for_multipledays($days, $acinvertval);
    }
    echo $finalreport;
}

function getgensetreportpdfMultipleDays($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $cm = new CustomerManager();
        $cd = $cm->getcustomerdetail_byid($customerno);
        $title = getcustombyid(1, 'Digital', $customerno) . ' Sensor History';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $finalreport = pdf_header($title, $subTitle, $cd);
        $finalreport .= "
           <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>";
        $finalreport .= create_gensetpdf_for_multipledays($days, $acinvertval, $customerno, $cd->use_geolocation);
    } else {
        $finalreport = "No Data";
    }
    echo $finalreport;
}

function getgensetreportpdfMultipleDays_details($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $cm = new CustomerManager();
        $cd = $cm->getcustomerdetail_byid($customerno);
        $title = getcustombyid(1, 'Digital', $customerno) . ' Sensor History Details';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $finalreport = pdf_header($title, $subTitle, $cd);
        $finalreport .= "
           <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>";
        $finalreport .= create_gensetpdf_for_multipledays_details($days, $acinvertval, $customerno, $cd->use_geolocation);
    } else {
        $finalreport = "No Data";
    }
    echo $finalreport;
}

function getgensetreportpdfMultipleDays_summary($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $cm = new CustomerManager();
        $cd = $cm->getcustomerdetail_byid($customerno);
        $title = getcustombyid(1, 'Digital', $customerno) . ' Sensor Summary';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $finalreport = pdf_header($title, $subTitle, $cd);
        $finalreport .= "<table id='search_table_2' align='center' style='  width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";
        $finalreport .= create_gensetpdf_for_multipledays_summary($days, $acinvertval, $customerno, $cd->use_geolocation);
    } else {
        $finalreport = "No Data";
    }
    echo $finalreport;
}

function getextrareportpdfMultipleDays($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $extraid, $extraval) {
    //var_dump($extraid);die();
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    //$acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getextradata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $title = $_SESSION["digitalcon"] . ' Sensor History';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        $finalreport = pdf_header($title, $subTitle);
        $finalreport .= "
           <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>";
        $finalreport .= create_extrapdf_for_multipledays($days, $extraid, $extraval);
    } else {
        $finalreport = "No Data";
    }
    echo $finalreport;
}

function gettempreportpdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $switchto, $vgroupname = null, $reporttype = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport ='';
    $days = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
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
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, false, null, $customerno);
                $vehicleid = $data['vehicleid'];
            }
            if ($data[0] != NULL && count($data[0]) > 1) {
                $days = array_merge($days, $data[0]);
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
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
        if ($reporttype == 'pdf') {
            $finalreport = pdf_header($title, $subTitle, $customer_details);
        } else if ($reporttype == 'xls') {
            $finalreport = excel_header($title, $subTitle, $customer_details);
        }
        $finalreport .= "<hr /><br/><br/>
                    <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>";
        $veh_temp_details = '';
        if (isset($vehicleid)) {
            $veh_temp_details = getunitdetailsfromvehid($vehicleid, $customerno);
        }
        $finalreport .= create_temppdf_from_report($days, $unit, $customerno, $veh_temp_details, $switchto);
    }
    return $finalreport;
}

function gethumidityreportpdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $switchto, $vgroupname = null, $reporttype = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
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
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gethumiditydata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, false, null, $customerno);
                $vehicleid = $data['vehicleid'];
            }
            if ($data[0] != NULL && count($data[0]) > 1) {
                $days = array_merge($days, $data[0]);
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
        $fromdate = date('d-m-Y H:i', strtotime($STdate . ' ' . $stime));
        $todate = date('d-m-Y H:i', strtotime($EDdate . ' ' . $etime));
        $title = 'Humidity Report';
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
        if ($reporttype == 'pdf') {
            $finalreport = pdf_header($title, $subTitle, $customer_details);
        } else if ($reporttype == 'xls') {
            $finalreport = excel_header($title, $subTitle, $customer_details);
        }
        $finalreport .= "<hr /><br/><br/>
                    <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>";
        $veh_temp_details = '';
        if (isset($vehicleid)) {
            $veh_temp_details = getunitdetailsfromvehid($vehicleid, $customerno);
        }
        $finalreport .= create_humiditypdf_from_report($days, $unit, $customerno, $veh_temp_details, $switchto);
    }
    echo $finalreport;
}

// Temprature and Humidity In Pdf
function gettemphumidityreportpdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $switchto, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $veh_temp_details = Array();
    $days = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
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
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gethumiditydata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, false, null, $customerno);
                $vehicleid = $data['vehicleid'];
            }
            if ($data[0] != NULL && count($data[0]) > 1) {
                $days = array_merge($days, $data[0]);
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
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
        $finalreport = pdf_header($title, $subTitle, $customer_details);
        $finalreport .= "<hr /><br/><br/>
                    <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>";
        //$veh_temp_details = '';
        if (isset($vehicleid)) {
            $veh_temp_details = getunitdetailsfromvehid($vehicleid, $customerno);
        }
        $finalreport .= create_humiditytemp_pdf_from_report($days, $unit, $veh_temp_details, $switchto);
    }
    echo $finalreport;
}

// Temprature and Humidity In Excel
function gettemphumidityreportxls($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $switchto, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $veh_temp_details = Array();
    $days = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
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
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gethumiditydata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, false, null, $customerno);
                $vehicleid = $data['vehicleid'];
            }
            if ($data[0] != NULL && count($data[0]) > 1) {
                $days = array_merge($days, $data[0]);
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
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
        $finalreport = excel_header($title, $subTitle, $customer_details);
        $finalreport .= "<hr /><br/><br/>
                    <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>";
        //$veh_temp_details = '';
        if (isset($vehicleid)) {
            $veh_temp_details = getunitdetailsfromvehid($vehicleid, $customerno);
        }
        $finalreport .= create_humiditytemp_pdf_from_report($days, $unit, $veh_temp_details, $switchto);
    }
    echo $finalreport;
}

function gettempreportpdf_Excep($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $tempselect, $switchto, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $finalreport = '';
    $days = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
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
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, false, null, $customerno);
                $vehicleid = $data['vehicleid'];
            }
            if ($data[0] != NULL && count($data[0]) > 1) {
                $days = array_merge($days, $data[0]);
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
        $fromdate = date('d-m-Y H:i', strtotime($STdate . ' ' . $stime));
        $todate = date('d-m-Y H:i', strtotime($EDdate . ' ' . $etime));
        $title = 'Temperature Exception Report';
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
        $finalreport = pdf_header($title, $subTitle, $customer_details);
        $finalreport .= "<hr /><br/><br/>
                    <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>";
        $veh_temp_details = '';
        if (isset($vehicleid)) {
            $veh_temp_details = getunitdetailsfromvehid($vehicleid, $customerno);
        }
        $datediff = strtotime($EDdate . " $etime") - strtotime($STdate . " $stime");
        $finalreport .= create_temppdf_Excep_report($days, $unit, $customerno, $veh_temp_details, $tempselect, count($totaldays), $datediff);
    }
    echo $finalreport;
}

function gettempreportxlsExcep($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime, $tempselect, $switchto, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $unit = getunitdetailspdf($customerno, $deviceid);
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
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate);
                $vehicleid = $data['vehicleid'];
            }
            if ($data[0] != NULL && count($data[0]) > 1) {
                $days = array_merge($days, $data[0]);
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
        $fromdate = date('F j, Y H:i A', strtotime($STdate . ' ' . $stime));
        $todate = date('F j, Y H:i A', strtotime($EDdate . ' ' . $etime));
        $title = "Temperature Exception Report";
        if ($switchto == 3) {
            $subTitle = array(
                "Warehouse: $vehicleno",
                "Start Date: $fromdate",
                "End Date: $todate",
                "Interval: <b>$interval</b> mins"
            );
        } else {
            $subTitle = array(
                "Vehicle No: $vehicleno",
                "Start Date: $fromdate",
                "End Date: $todate",
                "Interval: <b>$interval</b> mins"
            );
        }
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        $finalreport = excel_header($title, $subTitle, $customer_details);
        $finalreport .= "
<table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
    <tbody>";
        $veh_temp_details = '';
        if (isset($vehicleid)) {
            $veh_temp_details = getunitdetailsfromvehid($vehicleid, $customerno);
        }
        $datediff = strtotime($EDdate . " $etime") - strtotime($STdate . " $stime");
        $finalreport .= create_temppdf_Excep_report($days, $unit, $customerno, $veh_temp_details, $tempselect, count($totaldays), $datediff);
    }
    echo $finalreport;
}

function getacreportcsv($customerno, $STdate, $EDdate, $deviceid, $vehicleno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = substr(date("Y-m-d H:i:s"), '0', 11);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date("F j, Y, g:i a");
        $repdate = date("F j, Y", strtotime($STdate));
        $finalreport = '<div style="width:1120px; height:30px;">
                        <table style="width: 1120px;  border:1px solid #000;">
                        <tr>
                        <td colspan="7" style="width:1120px; text-align: center; text-transform:uppercase; border:none;"><h4 style="text-transform:uppercase;">Gen Set Sensor Report</h4></td>
                        </tr>
                        </table>
                        </div>';
        $finalreport .= "<div style='width:1120px; height:30px;'>
                        <table id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tr>
                        <td colspan='2' style='text-align:center;'><b>Vehicle No. $vehicleno</b></td>
                        <td colspan='3' style='text-align:center;'><b>Date : $repdate</b></td>
                        <td colspan='2' style='text-align:center;'><b>Report Generated On : $today</b></td>
                        </tr>
                        </table>
                        </div>
                        <hr />
                        <style type='text/css'>
                        table, td { border: solid 1px  #999999; color:#000000; }
                        hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
                        </style>
                        <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tbody>
                        <tr style='background-color:#CCCCCC;'>
                            <td style='width:50px;height:auto; text-align: center;'></td>
                            <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                            <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                            <td style='width:50px;height:auto; text-align: center;'>Ignition Status</td>
                            <td style='width:50px;height:auto; text-align: center;'>Gen Set Status</td>
                            <td style='width:150px;height:auto; text-align: center;'>Duration[Hours:Minutes]</td>
                            <td style='width:50px;height:auto; text-align: center;'></td>
                        </tr>";
        $finalreport .= create_csv_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

function getgensetreportcsv($customerno, $STdate, $EDdate, $deviceid, $vehicleno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $finalreport = '';
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date("F j, Y, g:i a");
        $repdate = date("F j, Y", strtotime($STdate));
        $finalreport = '<div style="width:1120px; height:30px;">
                        <table style="width: 1120px;  border:1px solid #000;">
                        <tr>
                        <td colspan="7" style="width:1120px; text-align: center; text-transform:uppercase; border:none;"><h4 style="text-transform:uppercase;">Gen Set Sensor Report</h4></td>
                        </tr>
                        </table>
                        </div>';
        $finalreport .= "<div style='width:1120px; height:30px;'>
                        <table id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tr>
                        <td colspan='2' style='text-align:center;'><b>Vehicle No. $vehicleno</b></td>
                        <td colspan='3' style='text-align:center;'><b>Date : $repdate</b></td>
                        <td colspan='2' style='text-align:center;'><b>Report Generated On : $today</b></td>
                        </tr>
                        </table>
                        </div>
                        <hr />
                        <style type='text/css'>
                        table, td { border: solid 1px  #999999; color:#000000; }
                        hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
                        </style>
                        <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tbody>
                        <tr style='background-color:#CCCCCC;'>
                            <td style='width:50px;height:auto; text-align: center;'></td>
                            <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                            <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                            <td style='width:50px;height:auto; text-align: center;'>Gen Set Status</td>
                            <td style='width:150px;height:auto; text-align: center;'>Duration[Hours:Minutes]</td>
                            <td style='width:50px;height:auto; text-align: center;'></td>
                        </tr>";
        $finalreport .= create_gensetcsv_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

function getacreportexcel($customerno, $STdate, $EDdate, $deviceid, $vehicleno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date("F j, Y, g:i a");
        $repdate = date("F j, Y", strtotime($STdate));
        $fromdate = date("F j, Y", strtotime($STdate));
        $todate = date("F j, Y", strtotime($EDdate));
        $finalreport = '<div style="width:1120px; height:30px;">
                        <table style="width: 1120px;  border:1px solid #000;">
                        <tr>
                        <td colspan="7" style="width:1120px; text-align: center; text-transform:uppercase; border:none;"><h4 style="text-transform:uppercase;">Gen Set Sensor Report</h4></td>
                        </tr>
                        </table>
                        </div>';
        $finalreport .= "<div style='width:1120px; height:30px;'>
                        <table id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tr>
                        <td colspan='2' style='text-align:center;'><b>Vehicle No. $vehicleno</b></td>
                        <td colspan='3' style='text-align:center;'><b>From :  $fromdate To : $todate</b></td>
                        <td colspan='2' style='text-align:center;'><b>Report Generated On : $today</b></td>
                        </tr>
                        </table>
                        </div>
                        <hr />
                        <style type='text/css'>
                        table, td { border: solid 1px  #999999; color:#000000; }
                        hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
                        </style>
                        <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tbody>";
        $finalreport .= create_excel_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

function getgensetreportexcel($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $cm = new CustomerManager();
        $cd = $cm->getcustomerdetail_byid($customerno);
        $title = getcustombyid(1, 'Digital', $customerno) . ' Sensor History';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        $finalreport = excel_header($title, $subTitle, $cd);
        $finalreport .= "<table  id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";
        $finalreport .= create_gensetexcel_from_report($days, $acinvertval, $customerno, $cd->use_geolocation);
    }
    echo $finalreport;
}

function getgensetreportexcelsummary($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $cm = new CustomerManager();
        $cd = $cm->getcustomerdetail_byid($customerno);
        $title = getcustombyid(1, 'Digital', $customerno) . ' Sensor History';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        $finalreport = excel_header($title, $subTitle, $cd);
        $finalreport .= "<table  id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";
        $finalreport .= create_gensetexcel_summary_from_report($days, $acinvertval, $customerno, $cd->use_geolocation);
    }
    echo $finalreport;
}

function getgensetreportexceldetails($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $finalreport = '';
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $cm = new CustomerManager();
        $cd = $cm->getcustomerdetail_byid($customerno);
        $title = getcustombyid(1, 'Digital', $customerno) . ' Sensor History Details';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        $finalreport = excel_header($title, $subTitle, $cd);
        $finalreport .= "<table  id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";
        $finalreport .= create_gensetexcel_from_reportdetails($days, $acinvertval, $customerno, $cd->use_geolocation);
    }
    echo $finalreport;
}

function getextrareportexcel($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $extraid, $extraval) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = substr(date("Y-m-d H:i:s"), '0', 11);
    $days = array();
    $finalreport = '';
    $unitno = getunitnopdf($customerno, $deviceid);
    //$acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getextradata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $title = $_SESSION["digitalcon"] . ' Sensor History';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate",
            "End Date: $EDdate"
        );
        $finalreport = excel_header($title, $subTitle);
        $finalreport .= "<table  id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";
        $finalreport .= create_extraexcel_from_report($days, $extraid, $extraval);
    }
    echo $finalreport;
}

function gettripreport($STdate, $EDdate, $deviceid) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    $acinvertval = getacinvertval($unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = NULL;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != NULL && count($data) > 1) {
                $report = createrep($data);
                if ($report != NULL) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $finalreport = create_html_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

function createrep($data) {
    $currentrow = new VODatacap();
    $currentrow->digitalio = $data[0]->digitalio;
    $currentrow->ignition = $data[0]->ignition;
    $currentrow->starttime = $data[0]->lastupdated;
    $currentrow->endtime = 0;
    $currentrow->fuelltr = 0;
    $currentrow->startcgeolat = $data[0]->cgeolat;
    $currentrow->startcgeolong = $data[0]->cgeolong;
    $gen_report = array();
    $a = 0;
    $counter = 0;
    //Creating Rows Of Data Where Duration Is Greater Than 3
    while (TRUE) {
        $i = 1;
        while (isset($data[$a + $i]) && checkdate_status($data[$a + $i], $currentrow, $data, ($a + $i))) {
            $i+=1;
        }
        if (isset($data[$a + $i])) {
            $currentrow->endtime = $data[$a + $i]->lastupdated;
            if (isset($data[$a + $i]->fuelltr)) {
                $currentrow->fuelltr = $data[$a + $i]->fuelltr;
            }
            $currentrow->endcgeolat = $data[$a + $i]->cgeolat;
            $currentrow->endcgeolong = $data[$a + $i]->cgeolong;
            $currentrow->duration = round(getduration($currentrow->endtime, $currentrow->starttime), 0);
            $gen_report[] = $currentrow;
            $currentrow = new VODatacap();
            $currentrow->starttime = $data[$a + $i]->lastupdated;
            $currentrow->startcgeolat = $data[$a + $i]->cgeolat;
            $currentrow->startcgeolong = $data[$a + $i]->cgeolong;
            $currentrow_count = $a + $i;
            //Just To Check That Data For The Next Row Is Not Wrong
            while (isset($data[$a + $i + 1]) && getduration($data[$a + $i + 1]->lastupdated, $currentrow->starttime) < 3) {
                $i+=1;
            }
            if (($a + $i) > $currentrow_count) {
                $gen_report[$counter]->endtime = $data[$a + $i]->lastupdated;
                $currentrow->endcgeolat = $data[$a + $i]->cgeolat;
                $currentrow->endcgeolong = $data[$a + $i]->cgeolong;
                $gen_report[$counter]->duration = round(getduration($gen_report[$counter]->endtime, $gen_report[$counter]->starttime), 0);
                $currentrow->starttime = $data[$a + $i]->lastupdated;
                $currentrow->startcgeolat = $data[$a + $i]->cgeolat;
                $currentrow->startcgeolong = $data[$a + $i]->cgeolong;
            }
            $currentrow->digitalio = $data[$a + $i]->digitalio;
            $currentrow->ignition = $data[$a + $i]->ignition;
            $a+=$i;
        } else {
            break;
        }
        $counter+=1;
    }
    //var_dump($gen_report);
    //Clubing Data With Similar AC & Ignition [Both Together]
    $gen_report = optimizerep_clean($gen_report);
    return $gen_report;
}

function checkdate_status($data, $currentrow, $entire_array, $currentrowcount) {
    $duration = getduration($data->lastupdated, $currentrow->starttime);
    if ($duration > 3) {
        return FALSE;
    } else {
        if (isset($entire_array[$currentrowcount + 1])) {
            if (getduration($entire_array[$currentrowcount + 1]->lastupdated, $currentrow->starttime) > 3) {
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
                $remove+=1;
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
                    $i+=1;
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
                $gen_report[$i - 1]->duration = round(getduration($gen_report[$i - 1]->endtime, $gen_report[$i - 1]->starttime), 0);
                unset($gen_report[$i]);
            }
        } else {
            break;
        }
        $i+=1;
    }
    $a = $gen_report;
    $gen_report = array();
    $i = 0;
    if (isset($a)) {
        foreach ($a as $value) {
            $gen_report[$i] = $value;
            $i+=1;
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
                $gen_report[$i]->duration = round(getduration($gen_report[$i]->endtime, $gen_report[$i]->starttime), 0);
                $gen_report[$i + 1] = 'Remove';
            }
        } else if (isset($gen_report[$i]) && $gen_report[$i] == 'Remove') {
            if (isset($gen_report[$i - 1]) && isset($gen_report[$i + 1])) {
                if (gettype($gen_report[$i - 1]) == 'object' && $gen_report[$i - 1]->ignition == $gen_report[$i + 1]->ignition && $gen_report[$i - 1]->digitalio == $gen_report[$i + 1]->digitalio) {
                    $gen_report[$i - 1]->endtime = $gen_report[$i + 1]->endtime;
                    $gen_report[$i - 1]->duration = round(getduration($gen_report[$i + 1]->endtime, $gen_report[$i - 1]->starttime), 0);
                    $gen_report[$i + 1] = 'Remove';
                }
            }
        } else {
            break;
        }
        $i+=1;
    }
    return $gen_report;
}

function getacdata_fromsqlite($location, $deviceid) {
    $devices = array();
    $query = "SELECT devicehistory.ignition, devicehistory.status, devicehistory.lastupdated,
            unithistory.digitalio from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid AND devicehistory.status!='F' group by devicehistory.lastupdated";
    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);
        $laststatus = array();
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if (@$laststatus['digitalio'] != $row['digitalio'] || @$laststatus['ig'] != $row['ignition']) {
                    $device = new VODevices();
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->digitalio = $row['digitalio'];
                    $laststatus['ig'] = $row['ignition'];
                    $laststatus['digitalio'] = $row['digitalio'];
                    $devices[] = $device;
                }
            }
        }
        $query2 = $query . " ORDER BY devicehistory.lastupdated DESC Limit 0,1";
        $result = $database->query($query2);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $device = new VODevices();
                $device->ignition = $row['ignition'];
                $device->status = $row['status'];
                $device->lastupdated = $row['lastupdated'];
                $device->digitalio = $row['digitalio'];
                $devices2 = $device;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    $devices[] = $devices2;
    return $devices;
}

function getgensetdata_fromsqlite($location, $deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unit = $um->getunitdetailsfromdeviceid($deviceid);
    $devices = array();
    if ($unit->fuelsensor != 0) {
        $min_c = $unit->fuel_min_volt; //value for empty voltage
        $max_c = $unit->fuel_max_volt; //value for max voltage
        $one = ($min_c + $max_c) / 100; //value for 1 %
        $fuelsensor = ",unithistory.analog$unit->fuelsensor as fuelvalue";
    } else {
        $fuelsensor = "";
    }
    $query = "SELECT devicehistory.ignition, devicehistory.status, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong,
            unithistory.digitalio " . $fuelsensor . " from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid AND devicehistory.status!='F' group by devicehistory.lastupdated";
    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);
        $laststatus = array();
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if (@$laststatus['digitalio'] != $row['digitalio']) {
                    $fuel_ltr = "";
                    if ($unit->fuelsensor != 0) {
                        $fuel_consumed = round($row['fuelvalue'] / $one, 2);
                        $dimension = 256.122;  /// [17cm*162cm*93cm = 256122 cm to ltr 256.122]
                        $fuel_ltr = round(256.122 * ($fuel_consumed / 100), 2);
                        if ($fuel_consumed > 100) { //skipping wrong data
                            continue;
                        }
                    }
                    $device = new VODevices();
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->digitalio = $row['digitalio'];
                    $device->cgeolat = $row['devicelat'];
                    $device->cgeolong = $row['devicelong'];
                    $laststatus['ig'] = $row['ignition'];
                    $laststatus['digitalio'] = $row['digitalio'];
                    $device->fuelltr = $fuel_ltr;
                    $devices[] = $device;
                }
            }
        }
        $query2 = $query . " ORDER BY devicehistory.lastupdated DESC Limit 0,1";
        $result = $database->query($query2);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $device = new VODevices();
                $device->ignition = $row['ignition'];
                $device->status = $row['status'];
                $device->lastupdated = $row['lastupdated'];
                $device->digitalio = $row['digitalio'];
                $device->cgeolat = $row['devicelat'];
                $device->cgeolong = $row['devicelong'];
                $devices2 = $device;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    $devices[] = $devices2;
    return $devices;
}

function getextradata_fromsqlite($location, $deviceid) {
    $devices = array();
    $query = "SELECT devicehistory.ignition, devicehistory.status, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong,
            unithistory.digitalio from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid AND devicehistory.status!='F' group by devicehistory.lastupdated";
    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);
        $laststatus = array();
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if (@$laststatus['digitalio'] != $row['digitalio']) {
                    $device = new VODevices();
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->digitalio = $row['digitalio'];
                    $device->cgeolat = $row['devicelat'];
                    $device->cgeolong = $row['devicelong'];
                    if (isset($_SESSION['customerno'])) {
                        $device->uselocation = get_usegeolocation($_SESSION['customerno']);
                    }
                    $laststatus['ig'] = $row['ignition'];
                    $laststatus['digitalio'] = $row['digitalio'];
                    $devices[] = $device;
                }
            }
        }
        $query2 = $query . " ORDER BY devicehistory.lastupdated DESC Limit 0,1";
        $result = $database->query($query2);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $device = new VODevices();
                $device->ignition = $row['ignition'];
                $device->status = $row['status'];
                $device->lastupdated = $row['lastupdated'];
                $device->digitalio = $row['digitalio'];
                $device->cgeolat = $row['devicelat'];
                $device->cgeolong = $row['devicelong'];
                $devices2 = $device;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    $devices[] = $devices2;
    return $devices;
}

function getextragensetdata_fromsqlite($location, $deviceid) {
    $devices = array();
    $query = "SELECT devicehistory.ignition, devicehistory.status, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong,
            unithistory.digitalio from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid AND devicehistory.status!='F' group by devicehistory.lastupdated";
    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);
        $laststatus = array();
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if (@$laststatus['digitalio'] != $row['digitalio']) {
                    $device = new VODevices();
                    $device->ignition = $row['ignition'];
                    $device->status = $row['status'];
                    $device->lastupdated = $row['lastupdated'];
                    $device->digitalio = $row['digitalio'];
                    $device->cgeolat = $row['devicelat'];
                    $device->cgeolong = $row['devicelong'];
                    if (isset($_SESSION['customerno'])) {
                        $device->uselocation = get_usegeolocation($_SESSION['customerno']);
                    }
                    $laststatus['ig'] = $row['ignition'];
                    $laststatus['digitalio'] = $row['digitalio'];
                    $devices[] = $device;
                }
            }
        }
        $query2 = $query . " ORDER BY devicehistory.lastupdated DESC Limit 0,1";
        $result = $database->query($query2);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $device = new VODevices();
                $device->ignition = $row['ignition'];
                $device->status = $row['status'];
                $device->lastupdated = $row['lastupdated'];
                $device->digitalio = $row['digitalio'];
                $device->cgeolat = $row['devicelat'];
                $device->cgeolong = $row['devicelong'];
                $devices2 = $device;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    $devices[] = $devices2;
    return $devices;
}

function get_min_temperature($temp, $get_data = false) {
    static $min_temp = 50;
    if (!is_null($temp) && !$get_data && $temp < $min_temp) {
        $min_temp = round($temp);
    }
    return $min_temp;
}

function get_max_temperature($temp, $get_data = false) {
    static $max_temp;
    if (!$get_data && $temp > $max_temp) {
        $max_temp = round($temp);
    }
    return $max_temp;
}

function set_temp_graph_data($updated_date, $unit, $analog1, $analog2, $analog3, $analog4, $only_date = false) {
    $str_ch = strtotime($updated_date);
    $yr = date('Y', $str_ch);
    $mth = date('m', $str_ch) - 1;
    $day = date('d', $str_ch);
    $hour = date('H', $str_ch);
    $mins = date('i', $str_ch);
    $temp = null;
    if ($only_date) {
        return "[Date.UTC($yr, $mth, $day, $hour, $mins), $only_date]";
    }
    $temp1 = null;
    $temp2 = null;
    $temp3 = null;
    $temp4 = null;
    switch ($_SESSION['temp_sensors']) {
        case 4:
            $s = "analog" . $unit->tempsen4;
            if ($unit->tempsen4 != 0 && $$s != 0) {
                $temp4 = gettemp($$s);
            }
        case 3:
            $s = "analog" . $unit->tempsen3;
            if ($unit->tempsen3 != 0 && $$s != 0) {
                $temp3 = gettemp($$s);
            }
        case 2:
            $s = "analog" . $unit->tempsen2;
            if ($unit->tempsen2 != 0 && $$s != 0) {
                $temp2 = gettemp($$s);
            }
        case 1:
            $s = "analog" . $unit->tempsen1;
            if ($unit->tempsen1 != 0 && $$s != 0) {
                $temp1 = gettemp($$s);
            }
        default:
            $s = "analog" . $unit->tempsen1;
            if ($unit->tempsen1 != 0 && $$s != 0) {
                $temp1 = gettemp($$s);
            }
    }
    if (isset($_POST['tempsel']) && $_POST['tempsel'] == 1) {
        $temp = $temp1;
    } else if (isset($_POST['tempsel']) && $_POST['tempsel'] == 2) {
        $temp = $temp2;
    } else
    if (isset($_POST['tempsel']) && $_POST['tempsel'] == 3) {
        $temp = $temp3;
    } else
    if (isset($_POST['tempsel']) && $_POST['tempsel'] == 4) {
        $temp = $temp4;
    } else {
        $temp = $temp1;
    }
    /**/
    if (!is_null($temp) && $temp != '0' && $temp != '-') {
        get_min_temperature($temp);
        get_max_temperature($temp);
        return "[Date.UTC($yr, $mth, $day, $hour, $mins), $temp]";
    } else {
        return null;
    }
}

function set_humidity_graph_data($updated_date, $unit, $analog1, $analog2, $analog3, $analog4, $only_date = false) {
    $str_ch = strtotime($updated_date);
    $yr = date('Y', $str_ch);
    $mth = date('m', $str_ch) - 1;
    $day = date('d', $str_ch);
    $hour = date('H', $str_ch);
    $mins = date('i', $str_ch);
    $temp = null;
    if ($only_date) {
        return "[Date.UTC($yr, $mth, $day, $hour, $mins), $only_date]";
    }
    if ($_SESSION['use_humidity'] == 1) {
        $s = "analog" . $unit->humidity;
        if ($unit->humidity != 0 && $$s != 0) {
            $temp = gettemp($$s);
        }
    }
    /**/
    if (!is_null($temp) && $temp != '0' && $temp != '-') {
        get_min_temperature($temp);
        get_max_temperature($temp);
        return "[Date.UTC($yr, $mth, $day, $hour, $mins), $temp]";
    } else {
        return null;
    }
}

function gettempdata_fromsqlite($location, $deviceid, $interval, $startdate, $enddate, $graph = false, $unit = null, $customerno = null) {
    $devices = array();
    $graph_devices = array();
    $graph_ignition = array();
    $last_ignition = null;
    $query = "SELECT devicehistory.ignition, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong
             , unithistory.unitno ,unithistory.vehicleid,unithistory.digitalio, unithistory.analog1, unithistory.analog2, unithistory.analog3, unithistory.analog4
             from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.lastupdated BETWEEN '$startdate' AND '$enddate' AND devicehistory.deviceid= $deviceid AND devicehistory.status!='F' group by devicehistory.lastupdated";
    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);
        if (isset($result) && $result != "") {
            $total = 0;
            foreach ($result as $row) {
                if (!isset($vehicleid)) {
                    $vehicleid = $row['vehicleid'];
                }
                $total++;
                $device = new VODevices();
                if ((!isset($lastupdated)) || (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= $interval)) {
                    $device->unitno = $row['unitno'];
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
                if ($graph && round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= 1) {
                    $temp_val = set_temp_graph_data($row['lastupdated'], $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], false);
                    if (!is_null($temp_val)) {
                        $graph_devices[] = $temp_val;
                    }
                }
                if ($last_ignition != $row['ignition']) {
                    if (isset($ig_lastupdated)) {
                        $graph_ignition[] = set_temp_graph_data($row['lastupdated'], null, null, null, null, null, "#$last_ignition#");
                    }
                    $graph_ignition[] = set_temp_graph_data($row['lastupdated'], null, null, null, null, null, "#{$row['ignition']}#");
                    $last_ignition = $row['ignition'];
                    $ig_lastupdated = $row['lastupdated'];
                }
            }
            //$graph_ignition[] = set_temp_graph_data($row['lastupdated'], null, null, null, null, null, "#{$row['ignition']}#");
        }
    } catch (PDOException $e) {
        die($e);
    }
    $vehicleid = isset($vehicleid) ? $vehicleid : 0;
    if ($graph) {
        return array($devices, $graph_devices, 'vehicleid' => $vehicleid, 'ig_graph' => $graph_ignition);
    } else {
        return array($devices, 'vehicleid' => $vehicleid);
    }
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
    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);
        if (isset($result) && $result != "") {
            $total = 0;
            foreach ($result as $row) {
                if (!isset($vehicleid)) {
                    $vehicleid = $row['vehicleid'];
                }
                $total++;
                if (!isset($lastupdated)) {
                    $lastupdated = $row['lastupdated'];
                }
                if ($graph && round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= 1) {
                    $temp_val = set_humidity_graph_data($row['lastupdated'], $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], false);
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
                if ($last_ignition != $row['ignition']) {
                    if (isset($ig_lastupdated)) {
                        $graph_ignition[] = set_humidity_graph_data($row['lastupdated'], null, null, null, null, null, "#$last_ignition#");
                    }
                    $graph_ignition[] = set_humidity_graph_data($row['lastupdated'], null, null, null, null, null, "#{$row['ignition']}#");
                    $last_ignition = $row['ignition'];
                    $ig_lastupdated = $row['lastupdated'];
                }
            }
            //$graph_ignition[] = set_humidity_graph_data($row['lastupdated'], null, null, null, null, null, "#{$row['ignition']}#");
        }
    } catch (PDOException $e) {
        die($e);
    }
    $vehicleid = isset($vehicleid) ? $vehicleid : 0;
    if ($graph) {
        return array($devices, $graph_devices, 'vehicleid' => $vehicleid, 'ig_graph' => $graph_ignition);
    } else {
        return array($devices, 'vehicleid' => $vehicleid);
    }
}

function gettempExcep_fromsqlite($location, $deviceid, $interval, $startdate, $enddate, $graph = false, $unit = null, $customerno = null) {
    $devices = array();
    $graph_devices = array();
    $graph_ignition = array();
    $last_ignition = null;
    try {
        $query = "SELECT devicehistory.ignition, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong, unithistory.vehicleid,unithistory.digitalio,
            unithistory.analog1, unithistory.analog2, unithistory.analog3, unithistory.analog4 from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.lastupdated BETWEEN '$startdate' AND '$enddate' AND devicehistory.deviceid= $deviceid AND devicehistory.status!='F' group by devicehistory.lastupdated";
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
                    $temp_val = set_temp_graph_data($row['lastupdated'], $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], false);
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
                if ($last_ignition != $row['ignition']) {
                    if (isset($ig_lastupdated)) {
                        $graph_ignition[] = set_temp_graph_data($row['lastupdated'], null, null, null, null, null, "#$last_ignition#");
                    }
                    $graph_ignition[] = set_temp_graph_data($row['lastupdated'], null, null, null, null, null, "#{$row['ignition']}#");
                    $last_ignition = $row['ignition'];
                    $ig_lastupdated = $row['lastupdated'];
                }
            }
            $graph_ignition[] = set_temp_graph_data($row['lastupdated'], null, null, null, null, null, "#{$row['ignition']}#");
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

function genhours($STdate, $EDdate) {
    $TOTALDAYS = Array();
    $STdate = date("Y-m-d 00:00:01", strtotime($STdate));
    $EDdate = date("Y-m-d 23:59:59", strtotime($EDdate));
    while (strtotime($STdate) <= strtotime($EDdate)) {
        $TOTALDAYS[] = $STdate;
        $STdate = date("Y-m-d H:s:i", strtotime($STdate . ' + 1 hour'));
    }
    //echo "<pre>".  print_r($TOTALDAYS)."</pre>";
    return $TOTALDAYS;
}

function getFuelTank($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $tank = $vm->get_Fuel_Tank($vehicleid);
    return $tank;
}

function getFuelGauge($vehicleid) {
    $vm = new VehicleManager($_SESSION['customerno']);
    $fuel = $vm->getFueledVehicle_byID($vehicleid);
    return $fuel;
}

function getdailyreport($STdate, $EDdate, $vehicleid = NULL) {
    $totaldays = gendays($STdate, $EDdate);
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data($location, $totaldays, $vehicleid);
    }
    return $DATA;
}

function getdailyreport_All($STdate, $EDdate) {
    $totaldays = gendays($STdate, $EDdate);
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    $DATA = null;
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_All($location, $totaldays);
    }
    return $DATA;
}

function generate_genset_graph($DATA) {
    $all_data = array();
    $chart_height = 200;
    foreach ($DATA as $report) {
        if (isset($all_data[$report->vehicleid])) {
            $all_data[$report->vehicleid] += $report->genset;
        } else {
            $all_data[$report->vehicleid] = $report->genset;
        }
    }
    $vehs = $total = '';
    $vm = new VehicleManager($_SESSION['customerno']);
    foreach ($all_data as $vehid => $os) {
        $veh_no = $vm->get_vehicle_details($vehid);
        if (!isset($veh_no->vehicleno)) {
            continue;
        }
        $vehs .= "'$veh_no->vehicleno', ";
        $total .= "$os, ";
        $chart_height += 30;
    }
    return array('vehs' => $vehs, 'data' => $total, 'height' => $chart_height);
}

function generate_os_graph($DATA) {
    $all_data = array();
    $chart_height = 200;
    foreach ($DATA as $report) {
        if (isset($all_data[$report->vehicleid])) {
            $all_data[$report->vehicleid] += (int) $report->overspeed;
        } else {
            $all_data[$report->vehicleid] = (int) $report->overspeed;
        }
    }
    $vehs = $total = '';
    $vm = new VehicleManager($_SESSION['customerno']);
    foreach ($all_data as $vehid => $os) {
        $veh_no = $vm->get_vehicle_details($vehid);
        if (!isset($veh_no->vehicleno)) {
            continue;
        }
        $vehs .= "'$veh_no->vehicleno', ";
        $total .= "$os, ";
        $chart_height += 30;
    }
    return array('vehs' => $vehs, 'data' => $total, 'height' => $chart_height);
}

function generate_idletime_graph($DATA) {
    $all_data = array();
    $chart_height = 200;
    foreach ($DATA as $report) {
        if (isset($all_data[$report->vehicleid])) {
            $all_data[$report->vehicleid] += $report->idletime;
        } else {
            $all_data[$report->vehicleid] = $report->idletime;
        }
    }
    $vehs = $total = '';
    $vm = new VehicleManager($_SESSION['customerno']);
    foreach ($all_data as $vehid => $os) {
        $veh_no = $vm->get_vehicle_details($vehid);
        if (!isset($veh_no->vehicleno)) {
            continue;
        }
        $os = round(m2h($os));
        $vehs .= "'$veh_no->vehicleno', ";
        $total .= "$os, ";
        $chart_height += 30;
    }
    return array('vehs' => $vehs, 'data' => $total, 'height' => $chart_height);
}

function generate_fuel_graph($DATA) {
    $all_data = array();
    $chart_height = 200;
    foreach ($DATA as $report) {
        $fuel_consumed = ($report->average != 0) ? abs(round(($report->totaldistance / 1000) / $report->average, 2)) : 0;
        if (isset($all_data[$report->vehicleid])) {
            $all_data[$report->vehicleid] += $fuel_consumed;
        } else {
            $all_data[$report->vehicleid] = $fuel_consumed;
        }
    }
    $vehs = $total = '';
    $vm = new VehicleManager($_SESSION['customerno']);
    foreach ($all_data as $vehid => $os) {
        $veh_no = $vm->get_vehicle_details($vehid);
        if (!isset($veh_no->vehicleno)) {
            continue;
        }
        $vehs .= "'$veh_no->vehicleno', ";
        $total .= "$os, ";
        $chart_height += 30;
    }
    return array('vehs' => $vehs, 'data' => $total, 'height' => $chart_height);
}

function generate_fence_graph($DATA) {
    $all_data = array();
    $chart_height = 200;
    foreach ($DATA as $report) {
        if (isset($all_data[$report->vehicleid])) {
            $all_data[$report->vehicleid] += (int) $report->fenceconflict;
        } else {
            $all_data[$report->vehicleid] = (int) $report->fenceconflict;
        }
    }
    $vehs = $total = '';
    $vm = new VehicleManager($_SESSION['customerno']);
    foreach ($all_data as $vehid => $os) {
        $veh_no = $vm->get_vehicle_details($vehid);
        if (!isset($veh_no->vehicleno)) {
            continue;
        }
        $vehs .= "'$veh_no->vehicleno', ";
        $total .= "$os, ";
        $chart_height += 30;
    }
    return array('vehs' => $vehs, 'data' => $total, 'height' => $chart_height);
}

function getideltimereportpdf($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate) {
    $totaldays = gendays($STdate, $EDdate);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
    }
    if (isset($DATA)) {
        $title = 'IdleTime Analysis Report';
        $subTitle = array("Vehicle No: All Vehicles", "Start Date: $STdate", "End Date: $EDdate", 'Unit: HH:MM');
        echo pdf_header($title, $subTitle);
        ?>
        <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
            <tr style="background-color: #CCCCCC;font-weight:bold;">
                <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                <?php
                $SDate = $STdate;
                $STdate = date('d-m-Y', strtotime($STdate));
                $lastvehicle = Array();
                $vehicles = GetVehicles_SQLite();
                while (strtotime($STdate) <= strtotime($EDdate)) {
                    echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
                    $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                }
                echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
                ?>
            </tr>
            <?php
            $firstdate = $SDate;
            foreach ($DATA as $report) {
                $getin = true;
                if (isset($lastvehicle)) {
                    foreach ($lastvehicle as $thisvehicle) {
                        if ($thisvehicle == $report->vehicleid) {
                            $getin = false;
                        }
                    }
                }
                if (isset($vehicles[$report->vehicleid]['vehicleno']) == NULL) {
                    $getin = false;
                }
                if ($getin == true) {
                    $total = 0;
                    $CompareDate = strtotime($firstdate);
                    $SDatetemp = $firstdate;
                    echo '<tr>';
                    $id = $report->vehicleid;
                    echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                    foreach ($DATA as $variablerep) {
                        if ($report->vehicleid == $variablerep->vehicleid) {
                            while ($CompareDate <= $variablerep->date) {
                                if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                    echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                                } else if ($CompareDate == $variablerep->date) {
                                    if ($variablerep->idletime != 0)
                                        echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                    else {
                                        $variablerep->idletime = 1440;
                                        echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                    }
                                    $total += $variablerep->idletime;
                                }
                                $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                $CompareDate = strtotime($SDatetemp);
                            }
                        }
                    }
                    while ($CompareDate <= strtotime($EDdate)) {
                        echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                        $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                        $CompareDate = strtotime($SDatetemp);
                    }
                    echo "<td style='height: 30px; vertical-align: middle;'><b>" . m2h($total) . "</b></td>";
                    echo "</tr>";
                }
                $lastvehicle[] = $report->vehicleid;
            }
            echo "</table>";
        }
    }

    function getideltimereportcsv($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate) {
        $totaldays = gendays($STdate, $EDdate);
        $location = "../../customer/$customerno/reports/dailyreport.sqlite";
        if (file_exists($location)) {
            $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
        }
        if (isset($DATA)) {
            $title = 'Idle-Time Analysis Report';
            $subTitle = array("Vehicle No: All Vehicles", "Start Date: $STdate", "End Date: $EDdate", 'Unit: HH:MM');
            echo excel_header($title, $subTitle);
            ?>
            <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tr style="background-color: #CCCCCC;font-weight:bold;">
                    <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                    <?php
                    $SDate = $STdate;
                    $STdate = date('d-m-Y', strtotime($STdate));
                    $lastvehicle = Array();
                    $vehicles = GetVehicles_SQLite();
                    while (strtotime($STdate) <= strtotime($EDdate)) {
                        echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
                        $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                    }
                    echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
                    ?>
                </tr>
                <?php
                $firstdate = $SDate;
                foreach ($DATA as $report) {
                    $getin = true;
                    if (isset($lastvehicle)) {
                        foreach ($lastvehicle as $thisvehicle) {
                            if ($thisvehicle == $report->vehicleid) {
                                $getin = false;
                            }
                        }
                    }
                    if (isset($vehicles[$report->vehicleid]['vehicleno']) == NULL) {
                        $getin = false;
                    }
                    if ($getin == true) {
                        $total = 0;
                        $CompareDate = strtotime($firstdate);
                        $SDatetemp = $firstdate;
                        echo '<tr>';
                        $id = $report->vehicleid;
                        echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                        foreach ($DATA as $variablerep) {
                            if ($report->vehicleid == $variablerep->vehicleid) {
                                while ($CompareDate <= $variablerep->date) {
                                    if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                        echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                                    } else if ($CompareDate == $variablerep->date) {
                                        if ($variablerep->idletime != 0)
                                            echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                        else {
                                            $variablerep->idletime = 1440;
                                            echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                        }
                                        $total += $variablerep->idletime;
                                    }
                                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                    $CompareDate = strtotime($SDatetemp);
                                }
                            }
                        }
                        while ($CompareDate <= strtotime($EDdate)) {
                            echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                            $CompareDate = strtotime($SDatetemp);
                        }
                        echo "<td style='height: 30px; vertical-align: middle;'><b>" . m2h($total) . "</b></td>";
                        echo "</tr>";
                    }
                    $lastvehicle[] = $report->vehicleid;
                }
                echo "</table>";
            }
        }

        function getoverspeed_reportpdf($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate) {
            $totaldays = gendays($STdate, $EDdate);
            $location = "../../customer/$customerno/reports/dailyreport.sqlite";
            if (file_exists($location)) {
                $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
            }
            if (isset($DATA)) {
                $title = 'Overspeed Analysis Report';
                $subTitle = array("Vehicle No: All Vehicles", "Start Date: $STdate", "End Date: $EDdate");
                echo pdf_header($title, $subTitle);
                ?>
                <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                    <tbody>
                        <tr style="background-color: #CCCCCC;font-weight:bold;">
                            <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                            <?php
                            $SDate = $STdate;
                            $STdate = date('d-m-Y', strtotime($STdate));
                            $lastvehicle = Array();
                            $vehicles = GetVehicles_SQLite();
                            $conf_count = 0;
                            while (strtotime($STdate) <= strtotime($EDdate)) {
                                $disp_date = substr($STdate, 0, 5);
                                if ($conf_count > 22) {
                                    $disp_date = str_replace('-', '<br/>-<br/>', $disp_date);
                                }
                                echo "<td style='height: 30px; vertical-align: middle;' >$disp_date</td>";
                                $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                                $conf_count++;
                            }
                            echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
                            ?>
                        </tr>
                        <?php
                        $firstdate = $SDate;
                        foreach ($DATA as $report) {
                            $getin = true;
                            if (isset($lastvehicle)) {
                                foreach ($lastvehicle as $thisvehicle) {
                                    if ($thisvehicle == $report->vehicleid) {
                                        $getin = false;
                                    }
                                }
                            }
                            if (isset($vehicles[$report->vehicleid]['vehicleno']) == NULL) {
                                $getin = false;
                            }
                            if ($getin == true) {
                                $total = 0;
                                $CompareDate = strtotime($firstdate);
                                $SDatetemp = $firstdate;
                                echo '<tr>';
                                $id = $report->vehicleid;
                                echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                                foreach ($DATA as $variablerep) {
                                    if ($report->vehicleid == $variablerep->vehicleid) {
                                        while ($CompareDate <= $variablerep->date) {
                                            if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                                echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                                            } else if ($CompareDate == $variablerep->date) {
                                                echo "<td>$variablerep->overspeed</td>";
                                                $total += $variablerep->overspeed;
                                            }
                                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                            $CompareDate = strtotime($SDatetemp);
                                        }
                                    }
                                }
                                while ($CompareDate <= strtotime($EDdate)) {
                                    echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                    $CompareDate = strtotime($SDatetemp);
                                }
                                echo "<td style='height: 30px; vertical-align: middle;'><b>" . $total . "</b></td>";
                                echo "</tr>";
                            }
                            $lastvehicle[] = $report->vehicleid;
                        }
                        ?>
                    </tbody></table>
                <?php
            }
        }

        function getoverspeed_reportcsv($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate) {
            $totaldays = gendays($STdate, $EDdate);
            $location = "../../customer/$customerno/reports/dailyreport.sqlite";
            if (file_exists($location)) {
                $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
            }
            if (isset($DATA)) {
                $title = 'Overspeed Analysis Report';
                $subTitle = array("Vehicle No: All Vehicles", "Start Date: $STdate", "End Date: $EDdate");
                echo excel_header($title, $subTitle);
                ?>
                <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                    <tr style="background-color: #CCCCCC;font-weight:bold;">
                        <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                        <?php
                        $SDate = $STdate;
                        $STdate = date('d-m-Y', strtotime($STdate));
                        $lastvehicle = Array();
                        $vehicles = GetVehicles_SQLite();
                        while (strtotime($STdate) <= strtotime($EDdate)) {
                            echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
                            $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                        }
                        echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
                        ?>
                    </tr>
                    <?php
                    $firstdate = $SDate;
                    foreach ($DATA as $report) {
                        $getin = true;
                        if (isset($lastvehicle)) {
                            foreach ($lastvehicle as $thisvehicle) {
                                if ($thisvehicle == $report->vehicleid) {
                                    $getin = false;
                                }
                            }
                        }
                        if (isset($vehicles[$report->vehicleid]['vehicleno']) == NULL) {
                            $getin = false;
                        }
                        if ($getin == true) {
                            $total = 0;
                            $CompareDate = strtotime($firstdate);
                            $SDatetemp = $firstdate;
                            echo '<tr>';
                            $id = $report->vehicleid;
                            echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                            foreach ($DATA as $variablerep) {
                                if ($report->vehicleid == $variablerep->vehicleid) {
                                    while ($CompareDate <= $variablerep->date) {
                                        if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                            echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                                        } else if ($CompareDate == $variablerep->date) {
                                            echo "<td>$variablerep->overspeed</td>";
                                            $total += $variablerep->overspeed;
                                        }
                                        $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                        $CompareDate = strtotime($SDatetemp);
                                    }
                                }
                            }
                            while ($CompareDate <= strtotime($EDdate)) {
                                echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                                $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                $CompareDate = strtotime($SDatetemp);
                            }
                            echo "<td style='height: 30px; vertical-align: middle;'><b>" . $total . "</b></td>";
                            echo "</tr>";
                        }
                        $lastvehicle[] = $report->vehicleid;
                    }
                    ?>
                </table>
                <?php
            }
        }

        function getfence_reportpdf($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate) {
            $totaldays = gendays($STdate, $EDdate);
            $location = "../../customer/$customerno/reports/dailyreport.sqlite";
            if (file_exists($location)) {
                $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
            }
            if (isset($DATA)) {
                $subTitle = array("Start Date: $STdate", "End Date: $EDdate");
                $title = 'Fence Conflict Analysis Report';
                echo pdf_header($title, $subTitle);
                ?>
                <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                    <tr style="background-color: #CCCCCC;font-weight:bold;">
                        <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                        <?php
                        $SDate = $STdate;
                        $STdate = date('d-m-Y', strtotime($STdate));
                        $lastvehicle = Array();
                        $vehicles = GetVehicles_SQLite();
                        $conf_count = 0;
                        while (strtotime($STdate) <= strtotime($EDdate)) {
                            $disp_date = substr($STdate, 0, 5);
                            if ($conf_count > 20) {
                                $disp_date = str_replace('-', '<br/>-<br/>', $disp_date);
                            }
                            echo "<td style='height: 30px; vertical-align: middle;' >$disp_date</td>";
                            $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                            $conf_count++;
                        }
                        echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
                        ?>
                    </tr>
                    <?php
                    $firstdate = $SDate;
                    foreach ($DATA as $report) {
                        $getin = true;
                        if (isset($lastvehicle)) {
                            foreach ($lastvehicle as $thisvehicle) {
                                if ($thisvehicle == $report->vehicleid) {
                                    $getin = false;
                                }
                            }
                        }
                        if (isset($vehicles[$report->vehicleid]['vehicleno']) == NULL) {
                            $getin = false;
                        }
                        if ($getin == true) {
                            $total = 0;
                            $CompareDate = strtotime($firstdate);
                            $SDatetemp = $firstdate;
                            echo '<tr>';
                            $id = $report->vehicleid;
                            echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                            foreach ($DATA as $variablerep) {
                                if ($report->vehicleid == $variablerep->vehicleid) {
                                    while ($CompareDate <= $variablerep->date) {
                                        if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                            echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                                        } else if ($CompareDate == $variablerep->date) {
                                            echo "<td>$variablerep->fenceconflict</td>";
                                            $total += $variablerep->fenceconflict;
                                        }
                                        $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                        $CompareDate = strtotime($SDatetemp);
                                    }
                                }
                            }
                            while ($CompareDate <= strtotime($EDdate)) {
                                echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                                $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                $CompareDate = strtotime($SDatetemp);
                            }
                            echo "<td style='height: 30px; vertical-align: middle;'><b>" . $total . "</b></td>";
                            echo "</tr>";
                        }
                        $lastvehicle[] = $report->vehicleid;
                    }
                    ?>
                </table>
                <?php
            }
        }

        function getfence_reportcsv($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate) {
            $totaldays = gendays($STdate, $EDdate);
            $location = "../../customer/$customerno/reports/dailyreport.sqlite";
            if (file_exists($location)) {
                $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
            }
            if (isset($DATA)) {
                $title = 'Fence Conflict Analysis Report';
                $subTitle = array("Start Date: $STdate", "End Date: $EDdate");
                echo excel_header($title, $subTitle);
                ?>
                <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                    <tr style="background-color: #CCCCCC;font-weight:bold;">
                        <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                        <?php
                        $SDate = $STdate;
                        $STdate = date('d-m-Y', strtotime($STdate));
                        $lastvehicle = Array();
                        $vehicles = GetVehicles_SQLite();
                        while (strtotime($STdate) <= strtotime($EDdate)) {
                            echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
                            $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                        }
                        echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
                        ?>
                    </tr>
                    <?php
                    $firstdate = $SDate;
                    foreach ($DATA as $report) {
                        $getin = true;
                        if (isset($lastvehicle)) {
                            foreach ($lastvehicle as $thisvehicle) {
                                if ($thisvehicle == $report->vehicleid) {
                                    $getin = false;
                                }
                            }
                        }
                        if (isset($vehicles[$report->vehicleid]['vehicleno']) == NULL) {
                            $getin = false;
                        }
                        if ($getin == true) {
                            $total = 0;
                            $CompareDate = strtotime($firstdate);
                            $SDatetemp = $firstdate;
                            echo '<tr>';
                            $id = $report->vehicleid;
                            echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                            foreach ($DATA as $variablerep) {
                                if ($report->vehicleid == $variablerep->vehicleid) {
                                    while ($CompareDate <= $variablerep->date) {
                                        if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                            echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                                        } else if ($CompareDate == $variablerep->date) {
                                            echo "<td>$variablerep->fenceconflict</td>";
                                            $total += $variablerep->fenceconflict;
                                        }
                                        $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                        $CompareDate = strtotime($SDatetemp);
                                    }
                                }
                            }
                            while ($CompareDate <= strtotime($EDdate)) {
                                echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                                $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                $CompareDate = strtotime($SDatetemp);
                            }
                            echo "<td style='height: 30px; vertical-align: middle;'><b>" . $total . "</b></td>";
                            echo "</tr>";
                        }
                        $lastvehicle[] = $report->vehicleid;
                    }
                    echo "</table>";
                }
            }

            function getlocation_reportpdf($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate) {
                $totaldays = gendays($STdate, $EDdate);
                $currentdate = substr(date("Y-m-d H:i:s"), '0', 11);
                $location = "../../customer/$customerno/reports/dailyreport.sqlite";
                if (file_exists($location)) {
                    $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
                }
                if (isset($DATA)) {
                    $subTitle = array("Start Date: $STdate", "End Date: $EDdate");
                    $title = 'Location Analysis Report';
                    echo pdf_header($title, $subTitle);
                    ?>
                    <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tbody>
                            <tr style="background-color: #CCCCCC;font-weight:bold;">
                                <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                                <?php
                                $SDate = $STdate;
                                $STdate = date('d-m-Y', strtotime($STdate));
                                $lastvehicle = Array();
                                $vehicles = GetVehicles_SQLite();
                                while (strtotime($STdate) <= strtotime($EDdate)) {
                                    echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
                                    $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                                }
                                ?>
                            </tr>
                            <?php
                            $firstdate = $SDate;
                            foreach ($DATA as $report) {
                                $getin = true;
                                if (isset($lastvehicle)) {
                                    foreach ($lastvehicle as $thisvehicle) {
                                        if ($thisvehicle == $report->vehicleid) {
                                            $getin = false;
                                        }
                                    }
                                }
                                if (isset($vehicles[$report->vehicleid]['vehicleno']) == NULL) {
                                    $getin = false;
                                }
                                if ($getin == true) {
                                    $total = 0;
                                    $CompareDate = strtotime($firstdate);
                                    $SDatetemp = $firstdate;
                                    echo '<tr>';
                                    $id = $report->vehicleid;
                                    echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                                    foreach ($DATA as $variablerep) {
                                        if ($report->vehicleid == $variablerep->vehicleid) {
                                            while ($CompareDate <= $variablerep->date) {
                                                if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                                    echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                                                } else if ($CompareDate == $variablerep->date) {
                                                    echo "<td>$variablerep->location</td>";
                                                }
                                                $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                                $CompareDate = strtotime($SDatetemp);
                                            }
                                        }
                                    }
                                    while ($CompareDate <= strtotime($EDdate)) {
                                        echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                                        $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                        $CompareDate = strtotime($SDatetemp);
                                    }
                                    echo "</tr>";
                                }
                                $lastvehicle[] = $report->vehicleid;
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                }
            }

            function getlocation_reportcsv($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate) {
                $totaldays = gendays($STdate, $EDdate);
                $location = "../../customer/$customerno/reports/dailyreport.sqlite";
                if (file_exists($location)) {
                    $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
                }
                if (isset($DATA)) {
                    $title = 'Location Analysis Report';
                    $subTitle = array("Start Date: $STdate", "End Date: $EDdate");
                    echo excel_header($title, $subTitle);
                    ?>
                    <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tr style="background-color: #CCCCCC;font-weight:bold;">
                            <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                            <?php
                            $SDate = $STdate;
                            $STdate = date('d-m-Y', strtotime($STdate));
                            $lastvehicle = Array();
                            $vehicles = GetVehicles_SQLite();
                            while (strtotime($STdate) <= strtotime($EDdate)) {
                                echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
                                $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                            }
                            ?>
                        </tr>
                        <?php
                        $firstdate = $SDate;
                        foreach ($DATA as $report) {
                            $getin = true;
                            if (isset($lastvehicle)) {
                                foreach ($lastvehicle as $thisvehicle) {
                                    if ($thisvehicle == $report->vehicleid) {
                                        $getin = false;
                                    }
                                }
                            }
                            if (isset($vehicles[$report->vehicleid]['vehicleno']) == NULL) {
                                $getin = false;
                            }
                            if ($getin == true) {
                                $total = 0;
                                $CompareDate = strtotime($firstdate);
                                $SDatetemp = $firstdate;
                                echo '<tr>';
                                $id = $report->vehicleid;
                                echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                                foreach ($DATA as $variablerep) {
                                    if ($report->vehicleid == $variablerep->vehicleid) {
                                        while ($CompareDate <= $variablerep->date) {
                                            if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                                echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                                            } else if ($CompareDate == $variablerep->date) {
                                                echo "<td>$variablerep->location</td>";
                                            }
                                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                            $CompareDate = strtotime($SDatetemp);
                                        }
                                    }
                                }
                                while ($CompareDate <= strtotime($EDdate)) {
                                    echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                    $CompareDate = strtotime($SDatetemp);
                                }
                                echo "</tr>";
                            }
                            $lastvehicle[] = $report->vehicleid;
                        }
                        ?>
                    </table>
                    <?php
                }
            }

            function getFuel_reportpdf($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate) {
                $totaldays = gendays($STdate, $EDdate);
                $location = "../../customer/$customerno/reports/dailyreport.sqlite";
                if (file_exists($location)) {
                    $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
                }
                if (isset($DATA)) {
                    $subTitle = array("Start Date: $STdate", "End Date: $EDdate");
                    $title = 'Fuel Consumption Analysis Report';
                    echo pdf_header($title, $subTitle);
                    ?>
                    <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tr style="background-color: #CCCCCC;font-weight:bold;">
                            <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                            <?php
                            $SDate = $STdate;
                            $STdate = date('d-m-Y', strtotime($STdate));
                            $lastvehicle = Array();
                            $vehicles = GetVehicles_SQLite();
                            while (strtotime($STdate) <= strtotime($EDdate)) {
                                echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
                                $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                            }
                            echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
                            ?>
                        </tr>
                        <?php
                        $firstdate = $SDate;
                        foreach ($DATA as $report) {
                            $getin = true;
                            if (isset($lastvehicle)) {
                                foreach ($lastvehicle as $thisvehicle) {
                                    if ($thisvehicle == $report->vehicleid) {
                                        $getin = false;
                                    }
                                }
                            }
                            if (isset($vehicles[$report->vehicleid]['vehicleno']) == NULL) {
                                $getin = false;
                            }
                            if ($getin == true) {
                                $total = 0;
                                $CompareDate = strtotime($firstdate);
                                $SDatetemp = $firstdate;
                                echo '<tr>';
                                $id = $report->vehicleid;
                                echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                                foreach ($DATA as $variablerep) {
                                    if ($report->vehicleid == $variablerep->vehicleid) {
                                        while ($CompareDate <= $variablerep->date) {
                                            if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                                echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                                            } else if ($CompareDate == $variablerep->date) {
                                                if ($variablerep->average > 0) {
                                                    $variablerep->totaldistancetravelled = round(($variablerep->totaldistance / 1000) / $variablerep->average, 2);
                                                    echo "<td style='height: 30px; vertical-align: middle;'>$variablerep->totaldistancetravelled</td>";
                                                    $total += $variablerep->totaldistancetravelled;
                                                } else {
                                                    echo "<td style='height: 30px; vertical-align: middle;'>0</td>";
                                                }
                                            }
                                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                            $CompareDate = strtotime($SDatetemp);
                                        }
                                    }
                                }
                                while ($CompareDate <= strtotime($EDdate)) {
                                    echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                    $CompareDate = strtotime($SDatetemp);
                                }
                                echo "<td style='height: 30px; vertical-align: middle;' >" . $total . "</td>";
                                echo "</tr>";
                            }
                            $lastvehicle[] = $report->vehicleid;
                        }
                        echo "</table>";
                    }
                }

                function getFuel_reportcsv($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate) {
                    $totaldays = gendays($STdate, $EDdate);
                    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
                    if (file_exists($location)) {
                        $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
                    }
                    if (isset($DATA)) {
                        $title = 'Fuel Consumption Analysis Report';
                        $subTitle = array("Start Date: $STdate", "End Date: $EDdate");
                        echo excel_header($title, $subTitle);
                        ?>
                        <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                            <tr style="background-color: #CCCCCC;font-weight:bold;">
                                <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                                <?php
                                $SDate = $STdate;
                                $STdate = date('d-m-Y', strtotime($STdate));
                                $lastvehicle = Array();
                                $vehicles = GetVehicles_SQLite();
                                while (strtotime($STdate) <= strtotime($EDdate)) {
                                    echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
                                    $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                                }
                                echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
                                ?>
                            </tr>
                            <?php
                            $firstdate = $SDate;
                            foreach ($DATA as $report) {
                                $getin = true;
                                if (isset($lastvehicle)) {
                                    foreach ($lastvehicle as $thisvehicle) {
                                        if ($thisvehicle == $report->vehicleid) {
                                            $getin = false;
                                        }
                                    }
                                }
                                if (isset($vehicles[$report->vehicleid]['vehicleno']) == NULL) {
                                    $getin = false;
                                }
                                if ($getin == true) {
                                    $total = 0;
                                    $CompareDate = strtotime($firstdate);
                                    $SDatetemp = $firstdate;
                                    echo '<tr>';
                                    $id = $report->vehicleid;
                                    echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                                    foreach ($DATA as $variablerep) {
                                        if ($report->vehicleid == $variablerep->vehicleid) {
                                            while ($CompareDate <= $variablerep->date) {
                                                if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                                    echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                                                } else if ($CompareDate == $variablerep->date) {
                                                    if ($variablerep->average > 0) {
                                                        $variablerep->totaldistancetravelled = round(($variablerep->totaldistance / 1000) / $variablerep->average, 2);
                                                        echo "<td style='height: 30px; vertical-align: middle;'>$variablerep->totaldistancetravelled</td>";
                                                        $total += $variablerep->totaldistancetravelled;
                                                    } else {
                                                        echo "<td style='height: 30px; vertical-align: middle;'>0</td>";
                                                    }
                                                }
                                                $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                                $CompareDate = strtotime($SDatetemp);
                                            }
                                        }
                                    }
                                    while ($CompareDate <= strtotime($EDdate)) {
                                        echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                                        $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                        $CompareDate = strtotime($SDatetemp);
                                    }
                                    echo "<td style='height: 30px; vertical-align: middle;' >" . $total . "</td>";
                                    echo "</tr>";
                                }
                                $lastvehicle[] = $report->vehicleid;
                            }
                            echo "</table>";
                        }
                    }

                    function getgensetreportpdf_All($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate) {
                        $totaldays = gendays($STdate, $EDdate);
                        $location = "../../customer/$customerno/reports/dailyreport.sqlite";
                        if (file_exists($location)) {
                            $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
                        }
                        if (isset($DATA)) {
                            $subTitle = array("Start Date: $STdate", "End Date: $EDdate", "( Unit - Hours : Minutes )");
                            $title = 'Genset Analysis Report';
                            echo pdf_header($title, $subTitle);
                            ?>
                            <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                <tr style="background-color: #CCCCCC;font-weight:bold;">
                                    <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                                    <?php
                                    $SDate = $STdate;
                                    $STdate = date('d-m-Y', strtotime($STdate));
                                    $lastvehicle = Array();
                                    $vehicles = GetGensetVehicles_SQLite();
                                    while (strtotime($STdate) <= strtotime($EDdate)) {
                                        echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
                                        $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                                    }
                                    echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
                                    ?>
                                </tr>
                                <?php
                                $firstdate = $SDate;
                                foreach ($DATA as $report) {
                                    $getin = true;
                                    if (isset($lastvehicle)) {
                                        foreach ($lastvehicle as $thisvehicle) {
                                            if ($thisvehicle == $report->vehicleid) {
                                                $getin = false;
                                            }
                                        }
                                    }
                                    if (isset($vehicles[$report->vehicleid]['vehicleno']) == NULL) {
                                        $getin = false;
                                    }
                                    if ($getin == true) {
                                        $total = 0;
                                        $CompareDate = strtotime($firstdate);
                                        $SDatetemp = $firstdate;
                                        echo '<tr>';
                                        $id = $report->vehicleid;
                                        echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                                        foreach ($DATA as $variablerep) {
                                            if ($report->vehicleid == $variablerep->vehicleid) {
                                                while ($CompareDate <= $variablerep->date) {
                                                    if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                                        echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                                                    } else if ($CompareDate == $variablerep->date) {
                                                        if ($variablerep->genset != 0)
                                                            echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                                        else {
                                                            $variablerep->genset = 1440;
                                                            echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                                        }
                                                        $total += $variablerep->genset;
                                                    }
                                                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                                    $CompareDate = strtotime($SDatetemp);
                                                }
                                            }
                                        }
                                        while ($CompareDate <= strtotime($EDdate)) {
                                            echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                            $CompareDate = strtotime($SDatetemp);
                                        }
                                        echo "<td style='height: 30px; vertical-align: middle;'><b>" . m2h($total) . "</b></td>";
                                        echo "</tr>";
                                    }
                                    $lastvehicle[] = $report->vehicleid;
                                }
                                ?>
                            </table>
                            <?php
                        }
                    }

                    function getgensetreportcsv_All($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate) {
                        $totaldays = gendays($STdate, $EDdate);
                        $location = "../../customer/$customerno/reports/dailyreport.sqlite";
                        if (file_exists($location)) {
                            $DATA = GetDailyReport_Data_All_PDF($location, $totaldays, $customerno, $usemaintainance, $usehierarchy, $groupid);
                        }
                        if (isset($DATA)) {
                            $title = 'Genset Analysis Report';
                            $subTitle = array("Start Date: $STdate", "End Date: $EDdate", "( Unit - Hours : Minutes )");
                            echo excel_header($title, $subTitle);
                            ?>
                            <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                <tr style="background-color: #CCCCCC;font-weight:bold;">
                                    <td style="height: 30px; vertical-align: middle;">Vehicle No</td>
                                    <?php
                                    $SDate = $STdate;
                                    $STdate = date('d-m-Y', strtotime($STdate));
                                    $lastvehicle = Array();
                                    $vehicles = GetGensetVehicles_SQLite();
                                    while (strtotime($STdate) <= strtotime($EDdate)) {
                                        echo "<td style='height: 30px; vertical-align: middle;' >" . substr($STdate, 0, 5) . "</td>";
                                        $STdate = date("d-m-Y", strtotime('+1 day', strtotime($STdate)));
                                    }
                                    echo "<td style='height: 30px; vertical-align: middle;' >Total</td>";
                                    ?>
                                </tr>
                                <?php
                                $firstdate = $SDate;
                                foreach ($DATA as $report) {
                                    $getin = true;
                                    if (isset($lastvehicle)) {
                                        foreach ($lastvehicle as $thisvehicle) {
                                            if ($thisvehicle == $report->vehicleid) {
                                                $getin = false;
                                            }
                                        }
                                    }
                                    if (isset($vehicles[$report->vehicleid]['vehicleno']) == NULL) {
                                        $getin = false;
                                    }
                                    if ($getin == true) {
                                        $total = 0;
                                        $CompareDate = strtotime($firstdate);
                                        $SDatetemp = $firstdate;
                                        echo '<tr>';
                                        $id = $report->vehicleid;
                                        echo '<td style="height: 30px; vertical-align: middle;">' . $vehicles[$id]['vehicleno'] . '</td>';
                                        foreach ($DATA as $variablerep) {
                                            if ($report->vehicleid == $variablerep->vehicleid) {
                                                while ($CompareDate <= $variablerep->date) {
                                                    if ($CompareDate < $variablerep->date && $CompareDate != $variablerep->date) {
                                                        echo "<td style='height: 30px; vertical-align: middle;'>N/A</td>";
                                                    } else if ($CompareDate == $variablerep->date) {
                                                        if ($variablerep->genset != 0)
                                                            echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                                        else {
                                                            $variablerep->genset = 1440;
                                                            echo "<td style='height: 30px; vertical-align: middle;'>" . m2h($variablerep->idletime) . "</td>";
                                                        }
                                                        $total += $variablerep->genset;
                                                    }
                                                    $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                                    $CompareDate = strtotime($SDatetemp);
                                                }
                                            }
                                        }
                                        while ($CompareDate <= strtotime($EDdate)) {
                                            echo '<td style="height: 30px; vertical-align: middle;">N/A</td>';
                                            $SDatetemp = date("Y-m-d", strtotime($SDatetemp . " + 1 day"));
                                            $CompareDate = strtotime($SDatetemp);
                                        }
                                        echo "<td style='height: 30px; vertical-align: middle;'><b>" . m2h($total) . "</b></td>";
                                        echo "</tr>";
                                    }
                                    $lastvehicle[] = $report->vehicleid;
                                }
                                ?>
                            </table>
                            <?php
                        }
                    }

                    function getdailyreport_byID($STdate, $EDdate, $vehicleid) {
                        $totaldays = gendays($STdate, $EDdate);
                        $customerno = $_SESSION['customerno'];
                        $location = "../../customer/$customerno/reports/dailyreport.sqlite";
                        if (file_exists($location)) {
                            $DATA = GetDailyReport_Data_ByID($location, $totaldays, $vehicleid);
                        }
                        return $DATA;
                    }

                    function getFuel_Report($STdate, $EDdate, $vehicleid) {
                        $ST = date('Y-m-d 00:00:00', strtotime($STdate));
                        $ED = date('Y-m-d 23:59:59', strtotime($EDdate));
                        $vm = new VehicleManager($_SESSION['customerno']);
                        $DATA = $vm->GetDailyFuelReport_Data($vehicleid, $ST, $ED);
                        //print_r($DATA);
                        return $DATA;
                    }

                    function getFuel_ReportAll($STdate, $EDdate) {
                        $ST = date('Y-m-d 00:00:00', strtotime($STdate));
                        $ED = date('Y-m-d 23:59:59', strtotime($EDdate));
                        $vm = new VehicleManager($_SESSION['customerno']);
                        $DATA = $vm->GetDailyFuelReportAll_Data($ST, $ED);
                        return $DATA;
                    }

                    function getunitnotemp($vehicleid) {
                        $um = new UnitManager($_SESSION['customerno']);
                        $unitno = $um->getuidfromvehicleid($vehicleid);
                        return $unitno;
                    }

                    function gethourlyreportfortemp($STdate, $vehicleid, $STHour) {
                        $date = date("Y-m-d", strtotime($STdate));
                        $date = substr($date, 0, 11);
                        $unitno = getunitnotemp($vehicleid);
                        //$totaldays = genminutes($STdate, $EDdate);
                        $customerno = $_SESSION['customerno'];
                        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
                        if (file_exists($location)) {
                            $DATA = GetHourlyReport_Temp($location, $STdate, $STHour);
                        }
                        return $DATA;
                    }

                    function getdailyreportfortemp($STdate, $EDdate, $vehicleid, $stime = null, $etime = null) {
                        $start = $STdate;
                        $DATAS = Array();
                        $date = date("Y-m-d", strtotime($STdate));
                        $date = substr($date, 0, 11);
                        $unitno = getunitnotemp($vehicleid);
                        //$totaldays = genminutes($STdate, $EDdate);
                        $customerno = $_SESSION['customerno'];
                        //$location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
                        $SDate = GetSafeValueString($STdate, 'string');
                        $SDate = explode('-', $SDate);
                        $SDate = $SDate[2] . "-" . $SDate[1] . "-" . $SDate[0];
                        $EDate = GetSafeValueString($EDdate, 'string');
                        $EDate = explode('-', $EDate);
                        $EDate = $EDate[2] . "-" . $EDate[1] . "-" . $EDate[0];
                        $totaldays = Array();
                        $STdate = date('Y-m-d', strtotime($SDate));
                        while (strtotime($STdate) <= strtotime($EDate)) {
                            $totaldays[] = $STdate;
                            $STdate = date("Y-m-d", strtotime('+1 day', strtotime($STdate)));
                        }
                        //print_r($totaldays);
                        if (isset($totaldays)) {
                            foreach ($totaldays as $userdate) {
                                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                                if (file_exists($location)) {
                                    $STdate = date("Y-m-d", strtotime($start));
                                    if ($userdate == $STdate) {
                                        $STdate = $userdate . " " . $stime . ":00";
                                    } else {
                                        $STdate = $userdate . " 00:00:00";
                                    }
                                    $EDdate = date("Y-m-d", strtotime($EDdate));
                                    if ($userdate == $EDdate) {
                                        $EDdate = $userdate . " " . $etime . ":00";
                                    } else {
                                        $EDdate = $userdate . " 23:59:59";
                                    }
                                    $DATA = GetDailyReport_Temp($location, $STdate, $EDdate);
                                    if ($DATA != NULL)
                                        $DATAS = array_merge($DATAS, $DATA);
                                    //$DATAS[] = $DATA;
                                }
                            }
                        }
                        //print_r($DATAS);
                        return $DATAS;
                    }

                    function getdailyreportfortempselected($STdate, $EDdate, $vehicleid, $analogtype, $stime = null, $etime = null) {
                        // echo $stime,$etime;
                        $start = $STdate;
                        $DATAS = Array();
                        $date = date("Y-m-d", strtotime($STdate));
                        $date = substr($date, 0, 11);
                        $unitno = getunitnotemp($vehicleid);
                        //$totaldays = genminutes($STdate, $EDdate);
                        $customerno = $_SESSION['customerno'];
                        //$location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
                        $SDate = GetSafeValueString($STdate, 'string');
                        $SDate = explode('-', $SDate);
                        $SDate = $SDate[2] . "-" . $SDate[1] . "-" . $SDate[0];
                        $EDate = GetSafeValueString($EDdate, 'string');
                        $EDate = explode('-', $EDate);
                        $EDate = $EDate[2] . "-" . $EDate[1] . "-" . $EDate[0];
                        $totaldays = Array();
                        $STdate = date('Y-m-d', strtotime($SDate));
                        while (strtotime($STdate) <= strtotime($EDate)) {
                            $totaldays[] = $STdate;
                            $STdate = date("Y-m-d", strtotime('+1 day', strtotime($STdate)));
                        }
                        //print_r($totaldays);
                        if (isset($totaldays)) {
                            foreach ($totaldays as $userdate) {
                                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                                if (file_exists($location)) {
                                    $STdate = date("Y-m-d", strtotime($start));
                                    $EDdate = date('Y-m-d', strtotime($EDdate));
                                    if ($userdate == $STdate) {
                                        $STdate = $userdate . " " . $stime . ":00";
                                    } else {
                                        $STdate = $userdate . " 00:00:00";
                                    }
                                    $EDdate = date("Y-m-d", strtotime($EDdate));
                                    if ($userdate == $EDdate) {
                                        $EDdate = $userdate . " " . $etime . ":00";
                                    } else {
                                        $EDdate = $userdate . " 23:59:59";
                                    }
                                    $DATA = GetDailyReport_Temp_Analog($location, $STdate, $EDdate, $analogtype);
                                    if ($DATA != NULL)
                                        $DATAS = array_merge($DATAS, $DATA);
                                    //$DATAS[] = $DATA;
                                }
                            }
                        }
                        //print_r($DATAS);
                        return $DATAS;
                    }

                    function getvehicles() {
                        $devicemanager = new DeviceManager($_SESSION['customerno']);
                        $devices = $devicemanager->devicesformapping();
                        return $devices;
                    }

                    function getwarehouses() {
                        $devicemanager = new DeviceManager($_SESSION['customerno']);
                        $devices = $devicemanager->devicesformapping_wh();
                        return $devices;
                    }

                    function getvehiclesforteam($customerno) {
                        $devicemanager = new DeviceManager($customerno);
                        $devices = $devicemanager->devicesformapping();
                        return $devices;
                    }

                    function get_all_chk() {
                        $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
                        $checkpoints = $checkpointmanager->getallchkpts();
                        return $checkpoints;
                    }

                    function get_all_chkforteam($customerno) {
                        $checkpointmanager = new CheckpointManager($customerno);
                        $checkpoints = $checkpointmanager->getallchkpts();
                        return $checkpoints;
                    }

                    function get_all_checkpoint() {
                        $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
                        $checkpoints = $checkpointmanager->getallcheckpoints();
                        return $checkpoints;
                    }

                    function get_all_fence() {
                        $geofencemanager = new GeofenceManager($_SESSION['customerno']);
                        $fences = $geofencemanager->getallfencenames();
                        return $fences;
                    }

                    function get_all_fenceteam($customerno) {
                        $geofencemanager = new GeofenceManager($customerno);
                        $fences = $geofencemanager->getallfencenames();
                        return $fences;
                    }

                    function get_all_alerttype() {
                        $devicemanager = new DeviceManager($_SESSION['customerno']);
                        $status = $devicemanager->getallalerttype();
                        return $status;
                    }

                    function GetVehicles_SQLite() {
                        $vehiclemanager = new VehicleManager($_SESSION['customerno']);
                        $VEHICLES = $vehiclemanager->Get_All_Vehicles_SQLite();
                        return $VEHICLES;
                    }

                    function GetGensetVehicles_SQLite() {
                        $vehiclemanager = new VehicleManager($_SESSION['customerno']);
                        $VEHICLES = $vehiclemanager->vehicles_acsensor();
                        return $VEHICLES;
                    }

                    function getvehicles_acsensor() {
                        $devicemanager = new DeviceManager($_SESSION['customerno']);
                        $devices = $devicemanager->devicesformapping_acsensor();
                        return $devices;
                    }

                    function getdate_IST() {
                        $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
                        return $ServerDate_IST;
                    }

                    function getduration($EndTime, $StartTime) {
                        $diff = strtotime($EndTime) - strtotime($StartTime);
                        $years = floor($diff / (365 * 60 * 60 * 24));
                        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
                        $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
                        $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
                        return $hours * 60 + $minutes;
                    }

                    function create_html_from_report($datarows, $acinvert) {
                        $i = 1;
                        $runningtime = 0;
                        $idletime = 0;
                        $lastdate = NULL;
                        $display = '';
                        if (isset($datarows)) {
                            foreach ($datarows as $change) {
                                $comparedate = date('d-m-Y', strtotime($change->endtime));
                                if (strtotime($lastdate) != strtotime($comparedate)) {
                                    $count = $i;
                                    $display .= "<tr><th align='center' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                                    $i++;
                                }
                                $currentdate = substr(date("Y-m-d H:i:s"), '0', 11);
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                $display .= "<tr><td>$change->starttime</td><td>$change->endtime</td>";
                                if ($change->ignition == 1) {
                                    $display .= '<td>ON</td>';
                                } else {
                                    $display .= '<td>OFF</td>';
                                }
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        $display .= '<td>OFF</td>';
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= '<td>ON</td>';
                                        $idletime += $change->duration;
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        $display .= '<td>ON</td>';
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= '<td>OFF</td>';
                                        $idletime += $change->duration;
                                    }
                                }
                                $dt = new DateTime();
                                $currDate = date_format($dt, 'Y-m-d');
                                //$startT = gmdate("H:i", $change->duration);
                                $startT = date('H:i', strtotime($change->duration . ' minutes'));
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td>$hour : $minute</td>";
                                $display .= '</tr>';
                            }
                        }
                        $display .= '</tbody>';
                        $display .= '</table><br><br>';
                        $display .="<table align = 'center'><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
                        $totaltime = 1440 * $count;
                        if ($acinvert == 1) {
                            $offtime = $totaltime - $idletime;
                            $display .= "<tbody><tr><td>Total Gen Set ON Time = $idletime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr></tbody></table>";
                        } else {
                            $offtime = $totaltime - $runningtime;
                            $display .= "<tbody><tr><td>Total Gen Set ON Time = $runningtime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr></tbody></table>";
                        }
                        return $display;
                    }

                    function create_gensethtml_from_report($datarows, $acinvert) {
                        $i = 1;
                        $runningtime = 0;
                        $idletime = 0;
                        $totalminute = 0;
                        $lastdate = NULL;
                        $display = '';
                        $date_wise_arr = array();
                        if (isset($datarows)) {
                            foreach ($datarows as $change) {
                                $thisdate = date('d-m-Y', strtotime($change->endtime));
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
                                    $display .= "<tr><th align='center' style='background:#d8d5d6' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                                    $i++;
                                }
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                if (!empty($change->startcgeolat) || !empty($change->startcgeolong)) {
                                    $use_location = isset($change->uselocation) ? $change->uselocation : 0;
                                    $change->startlocation = location($change->startcgeolat, $change->startcgeolong, $use_location);
                                    $change->endlocation = location($change->endcgeolat, $change->endcgeolong, $use_location);
                                } else {
                                    $change->startlocation = "Unable to Pull Location";
                                    $change->endlocation = "Unable to Pull Location";
                                }
                                $start_time_disp = date('h:i A', strtotime($change->starttime));
                                $end_time_disp = date('h:i A', strtotime($change->endtime));
                                $display .= "<tr><td>$start_time_disp</td><td>$change->startlocation</td><td>$end_time_disp</td><td>$change->endlocation</td>";
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        $display .= '<td>OFF</td>';
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= '<td>ON</td>';
                                        $idletime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        $display .= '<td>ON</td>';
                                        $runningtime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    } else {
                                        $display .= '<td>OFF</td>';
                                        $idletime += $change->duration;
                                    }
                                }
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td>$hour : $minute</td>";
                                if (isset($change->fuelltr)) {
                                    $fuelltr[] = $change->fuelltr;
                                    $display .= "<td>" . $change->fuelltr . "</td>";
                                } else {
                                    $display .= "<td> </td>";
                                }
                                $display .= '</tr>';
                            }
                        }
                        $display .= '</tbody>';
                        $display .= '</table><br><br>';
                        $datewise = ac_datewise($count, $date_wise_arr);
                        $display .= "$datewise<table class='table newTable' style='width:50%;'><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
                        $totaltime = round(1440 * $count + $totalminute);
                        if ($acinvert == 1) {
                            $offtime = $totaltime - $idletime;
                            $display .= "<tbody><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total Fuel Consumed = " . array_sum($fuelltr) . "</td></tr></tbody></table>";
                        } else {
                            $offtime = $totaltime - $runningtime;
                            $display .= "<tbody><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr><tr><td colspan='100%'></td></tr></tbody></table>";
                        }
                        $display .= "</div>";
                        return $display;
                    }

//gensert summary function -start
                    function create_gensethtml_summary_from_report($datarows, $acinvert) {
                        $i = 1;
                        $runningtime = 0;
                        $idletime = 0;
                        $totalminute = 0;
                        $lastdate = NULL;
                        $display = '';
                        $date_wise_arr = array();
                        if (isset($datarows)) {
                            foreach ($datarows as $change) {
                                $thisdate = date('d-m-Y', strtotime($change->endtime));
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
                                    //$display .= "<tr><th align='center' style='background:#d8d5d6' colspan = '100%'>".date('d-m-Y',strtotime($change->endtime))."</th></tr>";
                                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                                    $i++;
                                }
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                if (!empty($change->startcgeolat) || !empty($change->startcgeolong)) {
                                    $use_location = isset($change->uselocation) ? $change->uselocation : 0;
                                    $change->startlocation = location($change->startcgeolat, $change->startcgeolong, $use_location);
                                    $change->endlocation = location($change->endcgeolat, $change->endcgeolong, $use_location);
                                } else {
                                    $change->startlocation = "Unable to Pull Location";
                                    $change->endlocation = "Unable to Pull Location";
                                }
                                $start_time_disp = date('h:i A', strtotime($change->starttime));
                                $end_time_disp = date('h:i A', strtotime($change->endtime));
                                //$display .= "<tr><td>$start_time_disp</td><td>$change->startlocation</td><td>$end_time_disp</td><td>$change->endlocation</td>";
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        //$display .= '<td>OFF</td>';
                                        $runningtime += $change->duration;
                                    } else {
                                        //$display .= '<td>ON</td>';
                                        $idletime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        //$display .= '<td>ON</td>';
                                        $runningtime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    } else {
                                        //$display .= '<td>OFF</td>';
                                        $idletime += $change->duration;
                                    }
                                }
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                // $display .= "<td>$hour : $minute</td>";
                                if (isset($change->fuelltr)) {
                                    $fuelltr[] = $change->fuelltr;
                                    //  $display .= "<td>".$change->fuelltr."</td>";
                                } else {
                                    // $display .= "<td> </td>";
                                }
                                // $display .= '</tr>';
                            }
                        }
                        //$display .= '</tbody>';
                        // $display .= '</table><br><br>';
                        $datewise = ac_datewise($count, $date_wise_arr);
                        $display .= "$datewise<table class='table newTable' style='width:50%;'><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
                        $totaltime = round(1440 * $count + $totalminute);
                        if ($acinvert == 1) {
                            $offtime = $totaltime - $idletime;
                            if ($_SESSION['use_fuel_sensor'] != 0) {
                                $fuelsummary = "<td style='text-align:center;'>Total Fuel Consumed = " . array_sum($fuelltr) . " ltr</td>";
                            } else {
                                $fuelsummary = "";
                            }
                            $display .= "<tbody><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr><tr>" . $fuelsummary . "</tr></tbody></table>";
                        } else {
                            $offtime = $totaltime - $runningtime;
                            $display .= "<tbody><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr><tr><td colspan='100%'></td></tr><tr>" . $fuelsummary . "</tr></tbody></table>";
                        }
                        $display .= "</div>";
                        return $display;
                    }

////genset summary function -end
//genset details function -start -ganesh
                    function create_genset_detail_html_from_report($datarows, $acinvert) {
                        $i = 1;
                        $runningtime = 0;
                        $idletime = 0;
                        $totalminute = 0;
                        $lastdate = NULL;
                        $display = '';
                        $date_wise_arr = array();
                        if (isset($datarows)) {
                            foreach ($datarows as $change) {
                                $thisdate = date('d-m-Y', strtotime($change->endtime));
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
                                    $display .= "<tr><th align='center' style='background:#d8d5d6' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                                    $i++;
                                }
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                if (!empty($change->startcgeolat) || !empty($change->startcgeolong)) {
                                    $use_location = isset($change->uselocation) ? $change->uselocation : 0;
                                    $change->startlocation = location($change->startcgeolat, $change->startcgeolong, $use_location);
                                    $change->endlocation = location($change->endcgeolat, $change->endcgeolong, $use_location);
                                } else {
                                    $change->startlocation = "Unable to Pull Location";
                                    $change->endlocation = "Unable to Pull Location";
                                }
                                $start_time_disp = date('h:i A', strtotime($change->starttime));
                                $end_time_disp = date('h:i A', strtotime($change->endtime));
                                $display .= "<tr><td>$start_time_disp</td><td>$change->startlocation</td><td>$end_time_disp</td><td>$change->endlocation</td>";
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        $display .= '<td>OFF</td>';
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= '<td>ON</td>';
                                        $idletime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        $display .= '<td>ON</td>';
                                        $runningtime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    } else {
                                        $display .= '<td>OFF</td>';
                                        $idletime += $change->duration;
                                    }
                                }
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td>$hour : $minute</td>";
                                if (isset($change->fuelltr) && $change->fuelltr != 0) {
                                    $fuelltr[] = $change->fuelltr;
                                    $display .= "<td>" . $change->fuelltr . "</td>";
                                } else {
                                    $display .= "";
                                }
                                $display .= '</tr>';
                            }
                        }
                        $display .= '</tbody>';
                        $display .= '</table><br><br>';
                        //$datewise = ac_datewise($count,$date_wise_arr);
                        //$display .= "$datewise<table class='table newTable' style='width:50%;'><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
                        $display .= "<table class='table newTable' style='width:50%;'><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
                        $totaltime = round(1440 * $count + $totalminute);
                        if ($acinvert == 1) {
                            $offtime = $totaltime - $idletime;
                            if ($_SESSION['use_fuel_sensor'] != 0) {
                                $array_sum_fuel = "<td style='text-align:center;'>Total Fuel Consumed = " . array_sum($fuelltr) . "</td>";
                            } else {
                                $array_sum_fuel = "";
                            }
                            $display .= "<tbody><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr><tr>" . $array_sum_fuel . "</tr></tbody></table>";
                        } else {
                            $offtime = $totaltime - $runningtime;
                            $display .= "<tbody><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr><tr><td style='text-align:center;'>Total " . $_SESSION["digitalcon"] . " OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr><tr><td colspan='100%'></td></tr></tbody></table>";
                        }
                        $display .= "</div>";
                        return $display;
                    }

//genset detail function -end

                    function ac_datewise($count, $date_wise_arr, $from = 'html') {
                        $datewise = "";
                        if ($count > 1) {
                            if ($from == 'pdf') {
                                $datewise .= "<table align='center'  style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
                                $datewise .= "<tbody><tr><td style='background-color:#CCCCCC;font-weight:bold;' colspan='3'>Datewise</td></tr>";
                                $datewise .= "<tr style='background-color:#CCCCCC;font-weight:bold;'><td>Date</td><td>" . $_SESSION["digitalcon"] . " ON Time</td><td>" . $_SESSION["digitalcon"] . " OFF Time</td></tr>";
                            } else {
                                $datewise .= "<table class='table newTable' style='width:50%;'>";
                                $datewise .= "<thead><tr><th colspan='3'>Datewise</th></tr>";
                                $datewise .= "<tr><th>Date</th><th>" . $_SESSION["digitalcon"] . " ON Time</th><th>" . $_SESSION["digitalcon"] . " OFF Time</th></tr></thead>";
                                $datewise .= "<tbody>";
                            }
                            foreach ($date_wise_arr as $date => $val) {
                                $on = get_hh_mm($val * 60);
                                if ($date == date('d-m-Y')) {
                                    $now = (date('H') * 60) + date('i');
                                    $off_val = $now - $val;
                                } else {
                                    $off_val = 1440 - $val;
                                }
                                $off = get_hh_mm($off_val * 60);
                                $datewise .= "<tr><td>$date</td><td>$on Hours</td><td>$off Hours</td></tr>";
                            }
                            $datewise .= "</tbody></table><br/>";
                        }
                        return $datewise;
                    }

                    function create_extragensethtml_from_report($datarows, $extraid, $extraval) {
                        $i = 1;
                        $runningtime = 0;
                        $idletime = 0;
                        $totalminute = 0;
                        $lastdate = NULL;
                        $display = '';
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
                                    $display .= "<tr><th align='center' style='background:#d8d5d6' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                                    $i++;
                                }
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                if (!empty($change->startcgeolat) || !empty($change->startcgeolong)) {
                                    $use_location = isset($change->uselocation) ? $change->uselocation : 0;
                                    $change->startlocation = location($change->startcgeolat, $change->startcgeolong, $use_location);
                                    $change->endlocation = location($change->endcgeolat, $change->endcgeolong, $use_location);
                                } else {
                                    $change->startlocation = "Unable to Pull Location";
                                    $change->endlocation = "Unable to Pull Location";
                                }
                                $start_time_disp = date('h:i A', strtotime($change->starttime));
                                $end_time_disp = date('h:i A', strtotime($change->endtime));
                                $display .= "<tr><td>$start_time_disp</td><td>$change->startlocation</td><td>$end_time_disp</td><td>$change->endlocation</td>";
                                $category_array = Array();
                                $digital = Array();
                                $category = (int) $change->digitalio;
                                $binarycategory = sprintf("%08s", DecBin($category));
                                for ($shifter = 1; $shifter <= 127; $shifter = $shifter << 1) {
                                    $binaryshifter = sprintf("%08s", DecBin($shifter));
                                    if ($category & $shifter) {
                                        $category_array[] = $shifter;
                                    }
                                }
                                if (in_array($extraid, $category_array)) {
                                    $display .= '<td>On</td>';
                                    $runningtime += $change->duration;
                                } else {
                                    $display .= '<td>OFF</td>';
                                    $idletime += $change->duration;
                                }
                                $dt = new DateTime();
                                $currDate = date_format($dt, 'Y-m-d');
                                //$startT = gmdate("H:i", $change->duration);
                                $startT = date('H:i', strtotime($change->duration . ' minutes'));
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td>$hour : $minute</td>";
                                $display .= '</tr>';
                            }
                        }
                        $display .= '</tbody>';
                        $display .= '</table><br><br>';
                        $display .="<div class='container' style='width:45%;'><table class='table newTable' ><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
                        $totaltime = 1440 * $count + $totalminute;
                        $totaltime = round($totaltime);
                        if ($acinvert == 1) {
                            $offtime = $totaltime - $idletime;
                            $display .= "<tbody><tr><td style='text-align:center;'>Total " . $extraval . " ON Time = " . m2h($idletime) . " HH:MM  </td></tr><tr><td style='text-align:center;'>Total " . $extraval . " OFF Time = " . m2h($offtime) . " HH:MM </td></tr></tbody></table>";
                        } else {
                            $offtime = $totaltime - $runningtime;
                            $display .= "<tbody><tr><td style='text-align:center;'>Total " . $extraval . " ON Time = " . m2h($runningtime) . " HH:MM </td></tr><tr><td style='text-align:center;'>Total " . $extraval . " OFF Time = " . m2h($offtime) . " HH:MM</td></tr></tbody></table>";
                        }
                        return $display;
                    }

                    function create_temphtml_from_report($datarows, $vehicle, $veh_temp_details = null) {
                        $i = 1;
                        $tr = 0;
                        $temp1_non_comp_count = 0;
                        $temp2_non_comp_count = 0;
                        $temp3_non_comp_count = 0;
                        $temp4_non_comp_count = 0;
                        $totalminute = 0;

                        $temp1_bad_reading = 0;
                        $temp2_bad_reading = 0;
                        $temp3_bad_reading = 0;
                        $temp4_bad_reading = 0;

                        $lastdate = NULL;
                        $temp1_data = "";
                        $temp2_data = "";
                        $temp3_data = "";
                        $temp4_data = "";
                        $display = '';
                        $restemp1 = array(0);
                        $restemp2 = array(0);
                        $restemp3 = array(0);
                        $restemp4 = array(0);
                        $min_max_temp1 = get_min_max_temp(1, $veh_temp_details);
                        $min_max_temp2 = get_min_max_temp(2, $veh_temp_details);
                        $min_max_temp3 = get_min_max_temp(3, $veh_temp_details);
                        $min_max_temp4 = get_min_max_temp(4, $veh_temp_details);
                        $temp1counter = 0;
                        $temp2counter = 0;
                        $temp3counter = 0;
                        $temp4counter = 0;
                        $goodcount = 0;
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
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                if ($_SESSION['customerno'] == 116) {
                                    $starttime_disp = date('d-m-Y h:i A', strtotime($change->starttime));
                                } else {
                                    $starttime_disp = date('h:i A', strtotime($change->starttime));
                                }
                                $display .= "<tr><td>$starttime_disp</td>";
                                $location = get_location_detail($change->devicelat, $change->devicelong);
                                if ($_SESSION['switch_to'] != 3) {
                                    $display .= "<td>$location</td>";
                                }
                                // Temperature Sensor
                                //print_r($_SESSION);
                                $temp = 'Not Active';
                                $temp1 = 'Not Active';
                                $temp2 = 'Not Active';
                                $temp3 = 'Not Active';
                                $temp4 = 'Not Active';

                                $tdstring = '';
                                switch ($_SESSION['temp_sensors']) {
                                    case 4:
                                        $s4 = "analog" . $vehicle->tempsen4;
                                        if ($vehicle->tempsen4 != 0 && $change->$s4 != 0) {
                                            $temp4 = gettemp($change->$s4);
                                            /* min temp */
                                            $temp4_min = set_summary_min_temp($temp4);
                                            /* maximum temp */
                                            $temp4_max = set_summary_max_temp($temp4);
                                            /* above max */
                                            //if ($temp1 > $min_max_temp1['temp_max_limit'] ) {
                                            if ($temp4 > $min_max_temp4['temp_max_limit'] || $temp4 < $min_max_temp4['temp_min_limit']) {
                                                $temp4_non_comp_count++;
                                            }
                                        } else {
                                            $temp4 = '-';
                                            $temp4_bad_reading++;
                                        }

                                        if ($temp4 != '-' && $temp4 != "Not Active") {
                                            $tdstring = "<td>$temp4</td>";
                                            $restemp4[] = $temp4;
                                            $temp4counter = 0;
                                        } else {
                                            $temp4counter ++;
                                            if ($temp4counter <= 5) {
                                                $tdstring = "<td>" . end($restemp4) . "</td>";
                                            } else {
                                                $tdstring = '<td><img title="Unable To Fetch Temperature" src="../../images/warning.png" alt="Error"></td>';
                                                unset($restemp4);
                                            }
                                        }

                                    case 3:
                                        $s3 = "analog" . $vehicle->tempsen3;

                                        if ($vehicle->tempsen3 != 0 && $change->$s3 != 0) {
                                            $temp3 = gettemp($change->$s3);
                                            /* min temp */
                                            $temp3_min = set_summary_min_temp($temp3);
                                            /* maximum temp */
                                            $temp3_max = set_summary_max_temp($temp3);
                                            /* above max */
                                            //if ($temp1 > $min_max_temp1['temp_max_limit'] ) {
                                            if ($temp3 > $min_max_temp3['temp_max_limit'] || $temp3 < $min_max_temp3['temp_min_limit']) {
                                                $temp3_non_comp_count++;
                                            }
                                        } else {
                                            $temp3 = '-';
                                            $temp3_bad_reading++;
                                        }

                                        if ($temp3 != '-' && $temp3 != "Not Active") {
                                            $tdstring = "<td>$temp3</td>" . $tdstring;
                                            $restemp3[] = $temp3;
                                            $temp3counter = 0;
                                        } else {
                                            $temp3counter ++;
                                            if ($temp3counter <= 5) {
                                                $tdstring = "<td>" . end($restemp3) . "</td>" . $tdstring;
                                            } else {
                                                $tdstring = '<td> <img title="Unable To Fetch Temperature" src="../../images/warning.png" alt="Error"></td>' . $tdstring;
                                                unset($restemp3);
                                            }
                                        }

                                    case 2:
                                        $s2 = "analog" . $vehicle->tempsen2;

                                        if ($vehicle->tempsen2 != 0 && $change->$s2 != 0) {
                                            $temp2 = gettemp($change->$s2);
                                            /* min temp */
                                            $temp2_min = set_summary_min_temp($temp2);
                                            /* maximum temp */
                                            $temp2_max = set_summary_max_temp($temp2);
                                            /* above max */
                                            //if ($temp1 > $min_max_temp1['temp_max_limit'] ) {
                                            if ($temp2 > $min_max_temp2['temp_max_limit'] || $temp2 < $min_max_temp2['temp_min_limit']) {
                                                $temp2_non_comp_count++;
                                            }
                                        } else {
                                            $temp2 = '-';
                                            $temp2_bad_reading++;
                                        }
                                        if ($temp2 != '-' && $temp2 != "Not Active") {
                                            $tdstring = "<td>$temp2</td>" . $tdstring;
                                            ;
                                            $restemp2[] = $temp2;
                                            $temp2counter = 0;
                                        } else {
                                            $temp2counter ++;
                                            if ($temp2counter <= 5) {
                                                $tdstring = "<td>" . end($restemp2) . "</td>" . $tdstring;
                                                ;
                                            } else {
                                                $tdstring = '<td> <img title="Unable To Fetch Temperature" src="../../images/warning.png" alt="Error"></td>' . $tdstring;
                                                unset($restemp2);
                                            }
                                        }

                                    case 1:
                                        $s1 = "analog" . $vehicle->tempsen1;
                                        if ($vehicle->tempsen1 != 0 && $change->$s1 != 0) {
                                            $temp1 = gettemp($change->$s1);
                                            /* min temp */
                                            $temp1_min = set_summary_min_temp($temp1);
                                            /* maximum temp */
                                            $temp1_max = set_summary_max_temp($temp1);
                                            /* above max */
                                            //if ($temp1 > $min_max_temp1['temp_max_limit'] ) {
                                            if ($temp1 > $min_max_temp1['temp_max_limit'] || $temp1 < $min_max_temp1['temp_min_limit']) {
                                                $temp1_non_comp_count++;
                                            }
                                        } else {
                                            $temp1 = '-';
                                            $temp1_bad_reading++;
                                        }

                                        if ($temp1 != '-' && $temp1 != "Not Active") {
                                            $tdstring = "<td>$temp1</td>" . $tdstring;
                                            $restemp1[] = $temp1;
                                            $temp1counter = 0;
                                        } else {
                                            $temp1counter ++;
                                            if ($temp1counter <= 5) {
                                                $tdstring = "<td>" . end($restemp1) . "</td>" . $tdstring;
                                            } else {
                                                $tdstring = '<td> <img title="Unable To Fetch Temperature" src="../../images/warning.png" alt="Error"></td>' . $tdstring;
                                                unset($restemp1);
                                            }
                                        }
                                        break;
                                }
                                $display .= $tdstring;
                                if ($_SESSION['use_ac_sensor'] == 1) {
                                    if ($vehicle->acsensor == 1) {
                                        if ($change->digitalio == 0 && $vehicle->isacopp == 0) {
                                            $display .="<td>On</td>";
                                        } elseif ($change->digitalio == 0 && $vehicle->isacopp == 1) {
                                            $display .="<td>Off</td>";
                                        } elseif ($change->digitalio == 1 && $vehicle->isacopp == 0) {
                                            $display .="<td>Off</td>";
                                        } elseif ($change->digitalio == 1 && $vehicle->isacopp == 1) {
                                            $display .="<td>On</td>";
                                        }
                                    } else {
                                        $display .="<td>Not Active</td>";
                                    }
                                }
                                $display .= '</tr>';
                                $tr++;
                            }
                        }
                        $display .= '</tbody>';
                        $display .= '</table>';
                        $t1 = getName_ByType($vehicle->n1);
                        if ($t1 == '') {
                            $t1 = 'Temperature 1';
                        }
                        $t2 = getName_ByType($vehicle->n2);
                        if ($t2 == '') {
                            $t2 = 'Temperature 1';
                        }
                        $t3 = getName_ByType($vehicle->n3);
                        if ($t3 == '') {
                            $t3 = 'Temperature 1';
                        }
                        $t4 = getName_ByType($vehicle->n4);
                        if ($t4 == '') {
                            $t4 = 'Temperature 1';
                        }
                        $temphtml = '';
                        $span=null;
                        switch ($_SESSION['temp_sensors']) {

                        case 4:
                        $span = isset($span)?$span:4;
                        $goodcount = $tr - $temp4_bad_reading;
                        $abv_compliance = round(($temp4_non_comp_count / $tr) * 100, 2);
                        $compliance_count = $goodcount - $temp4_non_comp_count;
                        $within_compliance = round($compliance_count / $goodcount * 100, 2);

                        $temphtml = "<td style='text-align:center;'>
                        <table class='table newTable'><thead><tr><th><u>" . $t4 . "</u></th></tr></thead>
                        <tbody>
                        <tr><td>Range :".$min_max_temp4['temp_min_limit']." &deg;C to ".$min_max_temp4['temp_max_limit']." &deg;C</td></tr>
                        <tr><td>Minimum Temperature: $temp4_min &deg;C</td></tr>
                        <tr><td>Maximum Temperature: $temp4_max &deg;C</td></tr>
                        <tr><td>Total Reading: $tr</td></tr>
                        <tr><td>Non compliance count : $temp4_non_comp_count</td></tr>
                        <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                        <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
                        </td>";


                        case 3:
                        $span = isset($span)?$span:3;
                        $goodcount = $tr - $temp3_bad_reading;
                        $abv_compliance = round(($temp3_non_comp_count / $tr) * 100, 2);
                        $compliance_count = $goodcount - $temp3_non_comp_count;
                        $within_compliance = round($compliance_count / $goodcount * 100, 2);

                        $temphtml =  "<td style='text-align:center;'>
                        <table class='table newTable'><thead><tr><th><u>" . $t3 . "</u></th></tr></thead>
                        <tbody>
                        <tr><td>Range :".$min_max_temp3['temp_min_limit']." &deg;C to ".$min_max_temp3['temp_max_limit']." &deg;C</td></tr>
                        <tr><td>Minimum Temperature: $temp3_min &deg;C</td></tr>
                        <tr><td>Maximum Temperature: $temp3_max &deg;C</td></tr>
                        <tr><td>Total Reading: $tr</td></tr>
                        <tr><td>Non compliance readings : $temp3_non_comp_count</td></tr>
                        <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                        <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
                        </td>".$temphtml;


                        case 2:
                        $span = isset($span)?$span:2;
                        $goodcount = $tr - $temp2_bad_reading;
                        $abv_compliance = round(($temp2_non_comp_count / $tr) * 100, 2);
                        $compliance_count = $goodcount - $temp2_non_comp_count;
                        $within_compliance = round($compliance_count / $goodcount * 100, 2);

                        $temphtml = "<td style='text-align:center;'>
                        <table class='table newTable'><thead><tr><th><u>" . $t2 . "</u></th></tr></thead>
                        <tbody>
                        <tr><td>Range :".$min_max_temp2['temp_min_limit']." &deg;C to ".$min_max_temp2['temp_max_limit']." &deg;C</td></tr>
                        <tr><td>Minimum Temperature: $temp2_min &deg;C</td></tr>
                        <tr><td>Maximum Temperature: $temp2_max &deg;C</td></tr>
                        <tr><td>Total Reading: $tr</td></tr>
                        <tr><td> Non compliance readings : $temp2_non_comp_count</td></tr>
                        <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                        <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
                        </td>".$temphtml;


                        case 1:
                        $span = isset($span)?$span:1;
                        $goodcount = $tr - $temp1_bad_reading;
                        $abv_compliance = round(($temp1_non_comp_count / $tr) * 100, 2);
                        $compliance_count = $goodcount - $temp1_non_comp_count;
                        $within_compliance = round($compliance_count / $goodcount * 100, 2);

                        $temphtml = "<td style='text-align:center;'>
                        <table class='table newTable'><thead>
                        <tr><th><u>" . $t1 . "</u></th></tr></thead>
                        <tbody>
                        <tr><td>Range :".$min_max_temp1['temp_min_limit']." &deg;C to ".$min_max_temp1['temp_max_limit']." &deg;C</td></tr>
                        <tr><td>Minimum Temperature: $temp1_min &deg;C</td></tr>
                        <tr><td>Maximum Temperature: $temp1_max &deg;C</td></tr>
                        <tr><td>Total Reading: $tr</td></tr>
                        <tr><td>Non compliance readings: $temp1_non_comp_count</td></tr>
                        <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                        <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
                        </td>".$temphtml;
                        break;

                        }

                        $summary = "<table class='table newTable'>
        <thead>
            <tr><th colspan=$span>Statistics</th></tr>
        </thead>
        <tbody>
            <tr> $temphtml</tr>
        </tbody>
        </table>";
                        $display .= "$summary</div>";
                        return $display;
                    }

                    function create_humidityhtml_from_report($datarows, $vehicle, $veh_temp_details = null) {
                        $i = 1;
                        $tr = 0;
                        $tr_abv_max = 0;
                        $tr2_abv_max = 0;
                        $tr3_abv_max = 0;
                        $tr4_abv_max = 0;
                        $totalminute = 0;
                        $lastdate = NULL;
                        $display = '';
                        $min_max_temp1 = get_min_max_temp(1, $veh_temp_details);
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
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                $starttime_disp = date('h:i A', strtotime($change->starttime));
                                $display .= "<tr><td>$starttime_disp</td>";
                                if ($_SESSION['switch_to'] != 3) {
                                    $location = get_location_detail($change->devicelat, $change->devicelong);
                                    $display .= "<td>$location</td>";
                                }
                                // Temperature Sensor
                                //print_r($_SESSION);
                                if ($_SESSION['use_humidity'] == 1) {
                                    $temp = 'Not Active';
                                    $s = "analog" . $vehicle->humidity;
                                    if ($vehicle->humidity != 0 && $change->$s != 0) {
                                        $temp = gettemp($change->$s);
                                    } else
                                        $temp = '-';
                                    if ($temp != '-' && $temp != "Not Active")
                                        $display .= "<td>$temp</td>";
                                    else
                                        $display .= "<td>$temp</td>";
                                    /* min temp */
                                    $temp1_min = set_summary_min_temp($temp);
                                    /* maximum temp */
                                    $temp1_max = set_summary_max_temp($temp);
                                    /* above max */
                                    if ($temp > $min_max_temp1['temp_max_limit']) {
                                        $tr_abv_max++;
                                    }
                                }
                                $display .= '</tr>';
                                $tr++;
                            }
                        }
                        $display .= '</tbody>';
                        $display .= '</table>';
                        $abv_compliance = round(($tr_abv_max / $tr) * 100, 2);
                        $within_compliance = round((($tr - $tr_abv_max) / $tr) * 100, 2);
                        $temp1_data = "<td style='text-align:center;'>
            <table class='table newTable'><thead><tr><th><u>" . $t1 . "</u></th></tr></thead>
            <tbody><tr><td>Minimum Humidity: $temp1_min %</td></tr>
            <tr><td>Maximum Humidity: $temp1_max %</td></tr>
            <tr><td>Total Reading: $tr</td></tr>
            </td>";
                        $span = 1;
                        $temp2_data = '';

                        $summary = "<table class='table newTable'>
        <thead>
            <tr><th colspan=$span>Statistics</th></tr>
        </thead>
        <tbody>
            <tr>$temp1_data</tr>
        </tbody>
        </table>";
                        $display .= "$summary</div>";
                        return $display;
                    }

// format report to pdf format for temperature and humidity
                    function create_humiditytemp_pdf_from_report($datarows, $vehicle, $veh_temp_details = null, $switchto) {
                        $i = 1;
                        $tr = 0;
                        $tr_abv_max = 0;
                        $tr2_abv_max = 0;
                        $tr3_abv_max = 0;
                        $tr4_abv_max = 0;
                        $totalminute = 0;
                        $lastdate = NULL;
                        $display = '';
                        $min_max_temp1 = get_min_max_temp(1, $veh_temp_details);
                        $min_max_temp2 = get_min_max_temp(1, $veh_temp_details);
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
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                $starttime_disp = date('h:i A', strtotime($change->starttime));
                                $display .= "<tr><td>$starttime_disp</td>";
                                if ($switchto != 3) {
                                    $location = get_location_detail($change->devicelat, $change->devicelong);
                                    $display .= "<td>$location</td>";
                                }
                                // Temperature Sensor
                                //print_r($_SESSION);
                                if ($_SESSION['use_humidity'] == 1) {
                                    $temp = 'Not Active';
                                    $s = "analog" . $vehicle->humidity;
                                    if ($vehicle->humidity != 0 && $change->$s != 0) {
                                        $temp = gettemp($change->$s);
                                    } else
                                        $temp = '-';
                                    if ($temp != '-' && $temp != "Not Active")
                                        $display .= "<td>$temp</td>";
                                    else
                                        $display .= "<td>$temp</td>";
                                    /* min temp */
                                    $temp1_min = set_summary_min_temp($temp);
                                    /* maximum temp */
                                    $temp1_max = set_summary_max_temp($temp);
                                    /* above max */
                                    if ($temp > $min_max_temp1['temp_max_limit']) {
                                        $tr_abv_max++;
                                    }
                                }
                                if ($_SESSION['temp_sensors'] == 1) {
                                    $temp1 = 'Not Active';
                                    $s = "analog" . $vehicle->tempsen1;
                                    if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                                        $temp1 = gettemp($change->$s);
                                    } else
                                        $temp1 = '-';
                                    if ($temp1 != '-' && $temp1 != "Not Active")
                                        $display .= "<td>$temp1</td>";
                                    else
                                        $display .= "<td>$temp1</td>";
                                    /* min temp */
                                    $temp2_min = set_summary_min_temp2($temp1);
                                    /* maximum temp */
                                    $temp2_max = set_summary_max_temp2($temp1);
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

                    function create_humiditytemphtml_from_report($datarows, $vehicle, $veh_temp_details = null) {
                        $i = 1;
                        $tr = 0;
                        $tr_abv_max = 0;
                        $tr2_abv_max = 0;
                        $tr3_abv_max = 0;
                        $tr4_abv_max = 0;
                        $totalminute = 0;
                        $lastdate = NULL;
                        $display = '';
                        $min_max_temp1 = get_min_max_temp(1, $veh_temp_details);
                        $min_max_temp2 = get_min_max_temp(1, $veh_temp_details);
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
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                $starttime_disp = date('h:i A', strtotime($change->starttime));
                                $display .= "<tr><td>$starttime_disp</td>";
                                // Temperature Sensor
                                if ($_SESSION['use_humidity'] == 1) {
                                    $temp = 'Not Active';
                                    $s = "analog" . $vehicle->humidity;
                                    if ($vehicle->humidity != 0 && $change->$s != 0) {
                                        $temp = gettemp($change->$s);
                                    } else
                                        $temp = '-';
                                    if ($temp != '-' && $temp != "Not Active")
                                        $display .= "<td>$temp</td>";
                                    else
                                        $display .= "<td>$temp</td>";
                                    /* min temp */
                                    $temp1_min = set_summary_min_temp($temp);
                                    /* maximum temp */
                                    $temp1_max = set_summary_max_temp($temp);
                                    /* above max */
                                    if ($temp > $min_max_temp1['temp_max_limit']) {
                                        $tr_abv_max++;
                                    }
                                }
                                if ($_SESSION['temp_sensors'] == 1) {
                                    $temp1 = 'Not Active';
                                    $s = "analog" . $vehicle->tempsen1;
                                    if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                                        $temp1 = gettemp($change->$s);
                                    } else
                                        $temp1 = '-';
                                    if ($temp1 != '-' && $temp1 != "Not Active")
                                        $display .= "<td>$temp1</td>";
                                    else
                                        $display .= "<td>$temp1</td>";
                                    /* min temp */
                                    $temp2_min = set_summary_min_temp2($temp1);
                                    /* maximum temp */
                                    $temp2_max = set_summary_max_temp2($temp1);
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
            <table class='table newTable'><thead><tr><th><u>Humidity</u></th></tr></thead>
            <tbody><tr><td>Minimum Humidity: $temp1_min %</td></tr>
            <tr><td>Maximum Humidity: $temp1_max %</td></tr>
            <tr><td>Total Reading: $tr</td></tr>
             </tbody></table>
            </td>";
                        $temp2_data = "<td style='text-align:center;'>
            <table class='table newTable'><thead><tr><th><u>Temperature</u></th></tr></thead>
            <tbody><tr><td>Minimum Temperature: $temp2_min &deg;C</td></tr>
            <tr><td>Maximum Temperature: $temp2_max &deg;C</td></tr>
            <tr><td>Total Reading: $tr</td></tr>
            </tbody></table>
            </td>";
                        $span = 2;
                        $summary = "<table class='table newTable'>
        <thead>
            <tr><th colspan=$span>Statistics</th></tr>
        </thead>
        <tbody>
            <tr>$temp1_data$temp2_data</tr>
        </tbody>
        </table>";
                        $display .= "$summary</div>";
                        return $display;
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

                    function set_summary_min_temp2($temp) {
                        static $minTemp;
                        if ($minTemp == null) {
                            $minTemp = $temp;
                        }
                        if ($temp < $minTemp) {
                            $minTemp = $temp;
                        }
                        return $minTemp;
                    }

                    function set_summary_min_temp3($temp) {
                        static $minTemp;
                        if ($minTemp == null) {
                            $minTemp = $temp;
                        }
                        if ($temp < $minTemp) {
                            $minTemp = $temp;
                        }
                        return $minTemp;
                    }

                    function set_summary_min_temp4($temp) {
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

                    function set_summary_max_temp2($temp) {
                        static $maxTemp;
                        if ($maxTemp == null) {
                            $maxTemp = $temp;
                        }
                        if ($temp > $maxTemp) {
                            $maxTemp = $temp;
                        }
                        return $maxTemp;
                    }

                    function set_summary_max_temp3($temp) {
                        static $maxTemp;
                        if ($maxTemp == null) {
                            $maxTemp = $temp;
                        }
                        if ($temp > $maxTemp) {
                            $maxTemp = $temp;
                        }
                        return $maxTemp;
                    }

                    function set_summary_max_temp4($temp) {
                        static $maxTemp;
                        if ($maxTemp == null) {
                            $maxTemp = $temp;
                        }
                        if ($temp > $maxTemp) {
                            $maxTemp = $temp;
                        }
                        return $maxTemp;
                    }

                    /* get min and max temp-limit for this customer */

                    function get_min_max_temp($tempselect, $return, $temp_sensors = null) {
                        $sess_temp_sensors = ($temp_sensors != null) ? $temp_sensors : $_SESSION['temp_sensors'];
                        $temp_max_limit = 7;
                        $temp_min_limit = 0;
                        if ($sess_temp_sensors == 4) {
                            if (isset($tempselect) && $tempselect == 1) {
                                $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : $temp_max_limit;
                                $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : $temp_min_limit;
                            }
                            if (isset($tempselect) && $tempselect == 2) {
                                $temp_max_limit = isset($return->temp2_max) ? $return->temp2_max : $temp_max_limit;
                                $temp_min_limit = isset($return->temp2_min) ? $return->temp2_min : $temp_min_limit;
                            }
                            if (isset($tempselect) && $tempselect == 3) {
                                $temp_max_limit = isset($return->temp3_max) ? $return->temp3_max : $temp_max_limit;
                                $temp_min_limit = isset($return->temp3_min) ? $return->temp3_min : $temp_min_limit;
                            }
                            if (isset($tempselect) && $tempselect == 4) {
                                $temp_max_limit = isset($return->temp4_max) ? $return->temp4_max : $temp_max_limit;
                                $temp_min_limit = isset($return->temp4_min) ? $return->temp4_min : $temp_min_limit;
                            }
                        }
                        if ($sess_temp_sensors == 3) {
                            if (isset($tempselect) && $tempselect == 1) {
                                $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : $temp_max_limit;
                                $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : $temp_min_limit;
                            }
                            if (isset($tempselect) && $tempselect == 2) {
                                $temp_max_limit = isset($return->temp2_max) ? $return->temp2_max : $temp_max_limit;
                                $temp_min_limit = isset($return->temp2_min) ? $return->temp2_min : $temp_min_limit;
                            }
                            if (isset($tempselect) && $tempselect == 3) {
                                $temp_max_limit = isset($return->temp3_max) ? $return->temp3_max : $temp_max_limit;
                                $temp_min_limit = isset($return->temp3_min) ? $return->temp3_min : $temp_min_limit;
                            }
                        }
                        if ($sess_temp_sensors == 2) {
                            if (isset($tempselect) && $tempselect == 1) {
                                $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : $temp_max_limit;
                                $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : $temp_min_limit;
                            }
                            if (isset($tempselect) && $tempselect == 2) {
                                $temp_max_limit = isset($return->temp2_max) ? $return->temp2_max : $temp_max_limit;
                                $temp_min_limit = isset($return->temp2_min) ? $return->temp2_min : $temp_min_limit;
                            }
                        }
                        if ($sess_temp_sensors == 1) {
                            $temp_max_limit = isset($return->temp1_max) ? $return->temp1_max : $temp_max_limit;
                            $temp_min_limit = isset($return->temp1_min) ? $return->temp1_min : $temp_min_limit;
                        }
                        return array('temp_max_limit' => $temp_max_limit, 'temp_min_limit' => $temp_min_limit);
                    }

                    function create_temppdf_from_report($datarows, $vehicle, $custID = NULL, $veh_temp_details = null, $switchto = null) {
                        $i = 1;
                        $tr = 0;
                        $temp1_non_comp_count = 0;
                        $temp2_non_comp_count = 0;
                        $temp3_non_comp_count = 0;
                        $temp4_non_comp_count = 0;

                        $temp1_bad_reading = 0;
                        $temp2_bad_reading = 0;
                        $temp3_bad_reading = 0;
                        $temp4_bad_reading = 0;

                        $totalminute = 0;
                        $lastdate = NULL;
                        $display = '';
                        $restemp1 = Array(0);
                        $restemp2 = Array(0);
                        $restemp3 = Array(0);
                        $restemp4 = Array(0);
                        $temp1_data = '';
                        $temp2_data = '';
                        $temp3_data = '';
                        $temp4_data = '';
                        $temp1counter = 0;
                        $temp2counter = 0;
                        $temp3counter = 0;
                        $temp4counter = 0;
                        if (isset($datarows)) {
                            $customerno = ($custID == null) ? $_SESSION['customerno'] : $custID;
                            $cm = new CustomerManager(null);
                            $cm_details = $cm->getcustomerdetail_byid($customerno);
                            $min_max_temp1 = get_min_max_temp(1, $veh_temp_details, $cm_details->temp_sensors);
                            $min_max_temp2 = get_min_max_temp(2, $veh_temp_details, $cm_details->temp_sensors);
                            $min_max_temp3 = get_min_max_temp(3, $veh_temp_details, $cm_details->temp_sensors);
                            $min_max_temp4 = get_min_max_temp(4, $veh_temp_details, $cm_details->temp_sensors);
                            $t1 = getName_ByType_pdf($vehicle->n1, $customerno);
                            if ($t1 == '') {
                                $t1 = "Temperature 1";
                            }
                            $t2 = getName_ByType_pdf($vehicle->n2, $customerno);
                            if ($t2 == '') {
                                $t2 = "Temperature 2";
                            }
                            $t3 = getName_ByType_pdf($vehicle->n3, $customerno);
                            if ($t3 == '') {
                                $t3 = "Temperature 3";
                            }
                            $t4 = getName_ByType_pdf($vehicle->n4, $customerno);
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
                            <td style='width:150px;height:auto;'>Time</td>";
                                    if ($switchto != 3) {
                                        $display .="<td style='width:250px;height:auto;'>Location</td>";
                                    }

                                    if ($cm_details->temp_sensors == 4) {
                            $display .="<td style='width:150px;height:auto;'>" . $t1 . "</td>
                            <td style='width:150px;height:auto;'>" . $t2 . "</td>
                            <td style='width:150px;height:auto;'>" . $t3 . "</td>
                            <td style='width:150px;height:auto;'>" . $t4 . "</td>";
                                    }
                                    if ($cm_details->temp_sensors == 3) {
                                        $display .= "<td style='width:150px;height:auto;'>Temperature 1</td>
                            <td style='width:150px;height:auto;'>Temperature 2</td>
                            <td style='width:150px;height:auto;'>Temperature 3</td>";
                                    }
                                    if ($cm_details->temp_sensors == 2) {
                                        $display .= "<td style='width:150px;height:auto;'>Temperature 1</td>
                            <td style='width:150px;height:auto;'>Temperature 2</td>";
                                    } elseif ($cm_details->temp_sensors == 1) {
                                        $display .= "<td style='width:150px;height:auto;'>Temperature</td>";
                                    }
                                    $display .= "</tr>";
                                    if ($cm_details->temp_sensors == 4) {
                                        $display .= "<tr><td colspan='6' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                                    }
                                    if ($cm_details->temp_sensors == 3) {
                                        $display .= "<tr><td colspan='5' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                                    }
                                    if ($cm_details->temp_sensors == 2) {
                                        $display .= "<tr><td colspan='4' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                                    } elseif ($cm_details->temp_sensors == 1) {
                                        $display .= "<tr><td colspan='3' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                                    }
                                    $i++;
                                }
                                //Removing Date Details From DateTime//h:i A
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                if ($customerno == 116) {
                                    $display .= "<tr><td style='width:150px;height:auto;'>" . date('d-m-Y h:i A', strtotime($change->starttime)) . "</td>";
                                } else {
                                    $display .= "<tr><td style='width:150px;height:auto;'>" . date('h:i A', strtotime($change->starttime)) . "</td>";
                                }
                                $location = get_location_detail($change->devicelat, $change->devicelong, $custID);
                                if ($switchto != 3) {
                                    $display .= "<td style='width:250px;height:auto;'>$location</td>";
                                }
                                // Temperature Sensors
                                // Temperature Sensor
                                 $temp = 'Not Active';
                                $temp1 = 'Not Active';
                                $temp2 = 'Not Active';
                                $temp3 = 'Not Active';
                                $temp4 = 'Not Active';

                                $tdstring = '';
                                switch ($cm_details->temp_sensors) {
                                    case 4:
                                        $s4 = "analog" . $vehicle->tempsen4;
                                        if ($vehicle->tempsen4 != 0 && $change->$s4 != 0) {
                                            $temp4 = gettemp($change->$s4);
                                            /* min temp */
                                            $temp4_min = set_summary_min_temp($temp4);
                                            /* maximum temp */
                                            $temp4_max = set_summary_max_temp($temp4);
                                            /* above max */
                                            //if ($temp1 > $min_max_temp1['temp_max_limit'] ) {
                                            if ($temp4 > $min_max_temp4['temp_max_limit'] || $temp4 < $min_max_temp4['temp_min_limit']) {
                                                $temp4_non_comp_count++;
                                            }
                                        } else {
                                            $temp4 = '-';
                                            $temp4_bad_reading++;
                                        }

                                        if ($temp4 != '-' && $temp4 != "Not Active") {
                                            $tdstring = "<td>$temp4</td>";
                                            $restemp4[] = $temp4;
                                            $temp4counter = 0;
                                        } else {
                                            $temp4counter ++;
                                            if ($temp4counter <= 5) {
                                                $tdstring = "<td>" . end($restemp4) . "</td>";
                                            } else {
                                                $tdstring = '<td> - </td>';
                                                unset($restemp4);
                                            }
                                        }

                                    case 3:
                                        $s3 = "analog" . $vehicle->tempsen3;

                                        if ($vehicle->tempsen3 != 0 && $change->$s3 != 0) {
                                            $temp3 = gettemp($change->$s3);
                                            /* min temp */
                                            $temp3_min = set_summary_min_temp($temp3);
                                            /* maximum temp */
                                            $temp3_max = set_summary_max_temp($temp3);
                                            /* above max */
                                            //if ($temp1 > $min_max_temp1['temp_max_limit'] ) {
                                            if ($temp3 > $min_max_temp3['temp_max_limit'] || $temp3 < $min_max_temp3['temp_min_limit']) {
                                                $temp3_non_comp_count++;
                                            }
                                        } else {
                                            $temp3 = '-';
                                            $temp3_bad_reading++;
                                        }

                                        if ($temp3 != '-' && $temp3 != "Not Active") {
                                            $tdstring = "<td>$temp3</td>" . $tdstring;
                                            $restemp3[] = $temp3;
                                            $temp3counter = 0;
                                        } else {
                                            $temp3counter ++;
                                            if ($temp3counter <= 5) {
                                                $tdstring = "<td>" . end($restemp3) . "</td>" . $tdstring;
                                            } else {
                                                $tdstring = '<td> - </td>' . $tdstring;
                                                unset($restemp3);
                                            }
                                        }

                                    case 2:
                                        $s2 = "analog" . $vehicle->tempsen2;

                                        if ($vehicle->tempsen2 != 0 && $change->$s2 != 0) {
                                            $temp2 = gettemp($change->$s2);
                                            /* min temp */
                                            $temp2_min = set_summary_min_temp($temp2);
                                            /* maximum temp */
                                            $temp2_max = set_summary_max_temp($temp2);
                                            /* above max */
                                            //if ($temp1 > $min_max_temp1['temp_max_limit'] ) {
                                            if ($temp2 > $min_max_temp2['temp_max_limit'] || $temp2 < $min_max_temp2['temp_min_limit']) {
                                                $temp2_non_comp_count++;
                                            }
                                        } else {
                                            $temp2 = '-';
                                            $temp2_bad_reading++;
                                        }
                                        if ($temp2 != '-' && $temp2 != "Not Active") {
                                            $tdstring = "<td>$temp2</td>" . $tdstring;
                                            ;
                                            $restemp2[] = $temp2;
                                            $temp2counter = 0;
                                        } else {
                                            $temp2counter ++;
                                            if ($temp2counter <= 5) {
                                                $tdstring = "<td>" . end($restemp2) . "</td>" . $tdstring;
                                                ;
                                            } else {
                                                $tdstring = '<td> - </td>' . $tdstring;
                                                unset($restemp2);
                                            }
                                        }

                                    case 1:
                                        $s1 = "analog" . $vehicle->tempsen1;
                                        if ($vehicle->tempsen1 != 0 && $change->$s1 != 0) {
                                            $temp1 = gettemp($change->$s1);
                                            /* min temp */
                                            $temp1_min = set_summary_min_temp($temp1);
                                            /* maximum temp */
                                            $temp1_max = set_summary_max_temp($temp1);
                                            /* above max */
                                            //if ($temp1 > $min_max_temp1['temp_max_limit'] ) {
                                            if ($temp1 > $min_max_temp1['temp_max_limit'] || $temp1 < $min_max_temp1['temp_min_limit']) {
                                                $temp1_non_comp_count++;
                                            }
                                        } else {
                                            $temp1 = '-';
                                            $temp1_bad_reading++;
                                        }

                                        if ($temp1 != '-' && $temp1 != "Not Active") {
                                            $tdstring = "<td>$temp1</td>" . $tdstring;
                                            $restemp1[] = $temp1;
                                            $temp1counter = 0;
                                        } else {
                                            $temp1counter ++;
                                            if ($temp1counter <= 5) {
                                                $tdstring = "<td>" . end($restemp1) . "</td>" . $tdstring;
                                            } else {
                                                $tdstring = '<td> - </td>' . $tdstring;
                                                unset($restemp1);
                                            }
                                        }
                                        break;
                                }
                                 $display .= $tdstring;

                                $display .= '</tr>';
                                $tr++;
                            }
                        }
                        $display .= '</tbody>';
                        $display .= '</table><br/><br/>';

                        $temphtml = '';
                        $span=null;
                        switch ($cm_details->temp_sensors) {
                        case 4:
                        $span = isset($span)?$span:4;
                        $goodcount = $tr - $temp4_bad_reading;
                        $abv_compliance = round(($temp4_non_comp_count / $tr) * 100, 2);
                        $compliance_count = $goodcount - $temp4_non_comp_count;
                        $within_compliance = round($compliance_count / $goodcount * 100, 2);

                        $temphtml = "
                        <td style='text-align:center;'>
                        <table class='table newTable'><thead><tr><th><u>" . $t4 . "</u></th></tr></thead>
                        <tbody>
                        <tr><td>Range :".$min_max_temp4['temp_min_limit']." &deg;C to ".$min_max_temp4['temp_max_limit']." &deg;C</td></tr>
                        <tr><td>Minimum Temperature: $temp4_min &deg;C</td></tr>
                        <tr><td>Maximum Temperature: $temp4_max &deg;C</td></tr>
                        <tr><td>Total Reading: $tr</td></tr>
                        <tr><td>Non compliance count : $temp4_non_comp_count</td></tr>
                        <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                        <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
                        </td>";


                        case 3:
                        $span = isset($span)?$span:3;
                        $goodcount = $tr - $temp3_bad_reading;
                        $abv_compliance = round(($temp3_non_comp_count / $tr) * 100, 2);
                        $compliance_count = $goodcount - $temp3_non_comp_count;
                        $within_compliance = round($compliance_count / $goodcount * 100, 2);

                        $temphtml =  "<td style='text-align:center;'>
                        <table class='table newTable'><thead><tr><th><u>" . $t3 . "</u></th></tr></thead>
                        <tbody>
                        <tr><td>Range :".$min_max_temp3['temp_min_limit']." &deg;C to ".$min_max_temp3['temp_max_limit']." &deg;C</td></tr>
                        <tr><td>Minimum Temperature: $temp3_min &deg;C</td></tr>
                        <tr><td>Maximum Temperature: $temp3_max &deg;C</td></tr>
                        <tr><td>Total Reading: $tr</td></tr>
                        <tr><td>Non compliance readings : $temp3_non_comp_count</td></tr>
                        <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                        <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
                        </td>".$temphtml;


                        case 2:
                        $span = isset($span)?$span:2;
                        $goodcount = $tr - $temp2_bad_reading;
                        $abv_compliance = round(($temp2_non_comp_count / $tr) * 100, 2);
                        $compliance_count = $goodcount - $temp2_non_comp_count;
                        $within_compliance = round($compliance_count / $goodcount * 100, 2);

                        $temphtml = "<td style='text-align:center;'>
                        <table class='table newTable'><thead><tr><th><u>" . $t2 . "</u></th></tr></thead>
                        <tbody>
                        <tr><td>Range :".$min_max_temp2['temp_min_limit']." &deg;C to ".$min_max_temp2['temp_max_limit']." &deg;C</td></tr>
                        <tr><td>Minimum Temperature: $temp2_min &deg;C</td></tr>
                        <tr><td>Maximum Temperature: $temp2_max &deg;C</td></tr>
                        <tr><td>Total Reading: $tr</td></tr>
                        <tr><td> Non compliance readings : $temp2_non_comp_count</td></tr>
                        <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                        <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
                        </td>".$temphtml;


                        case 1:
                        $span = isset($span)?$span:1;
                        $goodcount = $tr - $temp1_bad_reading;
                        $abv_compliance = round(($temp1_non_comp_count / $tr) * 100, 2);
                        $compliance_count = $goodcount - $temp1_non_comp_count;
                        $within_compliance = round($compliance_count / $goodcount * 100, 2);

                        $temphtml = "<td style='text-align:center;'>
                        <table class='table newTable'><thead>
                        <tr><th><u>" . $t1 . "</u></th></tr></thead>
                        <tbody>
                        <tr><td>Range :".$min_max_temp1['temp_min_limit']." &deg;C to ".$min_max_temp1['temp_max_limit']." &deg;C</td></tr>
                        <tr><td>Minimum Temperature: $temp1_min &deg;C</td></tr>
                        <tr><td>Maximum Temperature: $temp1_max &deg;C</td></tr>
                        <tr><td>Total Reading: $tr</td></tr>
                        <tr><td>Non compliance readings: $temp1_non_comp_count</td></tr>
                        <tr><td>% Non compliance: <span style='color:red;'>$abv_compliance%</span></td></tr>
                        <tr><td>% within compliance: <span style='color:green;'>$within_compliance%</span></td></tr></tbody></table>
                        </td>".$temphtml;
                        break;
                        }

                        $summary = "<table align='center' style='width: auto; font-size:13px; text-align:center; border:1px solid #000;'>
                            <thead>
                                <tr><td colspan='$span' style='background-color:#CCCCCC;font-weight:bold;'>Statistics</td></tr>
                            </thead>
                            <tbody>
                                <tr>$temphtml</tr>
                            </tbody>
                            </table>";
                        $display .="$summary";
                        return $display;
                    }

                    function create_humiditypdf_from_report($datarows, $vehicle, $custID = NULL, $veh_temp_details = null, $switchto = null) {
                        $i = 1;
                        $tr = 0;
                        $tr_abv_max = 0;
                        $tr2_abv_max = 0;
                        $tr3_abv_max = 0;
                        $tr4_abv_max = 0;
                        $totalminute = 0;
                        $lastdate = NULL;
                        $display = '';
                        if (isset($datarows)) {
                            $customerno = ($custID == null) ? $_SESSION['customerno'] : $custID;
                            $cm = new CustomerManager(null);
                            $cm_details = $cm->getcustomerdetail_byid($customerno);
                            $min_max_temp1 = get_min_max_temp(1, $veh_temp_details, $cm_details->temp_sensors);
                            $min_max_temp2 = get_min_max_temp(2, $veh_temp_details, $cm_details->temp_sensors);
                            $min_max_temp3 = get_min_max_temp(3, $veh_temp_details, $cm_details->temp_sensors);
                            $min_max_temp4 = get_min_max_temp(4, $veh_temp_details, $cm_details->temp_sensors);
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
                                    if ($cm_details->use_humidity == 1) {
                                        $display .= "
                        <tr style='background-color:#CCCCCC;font-weight:bold;'>
                            <td style='width:150px;height:auto;'>Time</td>
                            <td style='width:150px;height:auto;'>Humidity</td>
                        </tr>";
                                    }
                                    if ($cm_details->use_humidity == 1) {
                                        $display .= "<tr><td colspan='2' style='background-color:#D8D5D6;font-weight:bold;width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                                    }
                                    $i++;
                                }
                                //Removing Date Details From DateTime//h:i A
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                $display .= "<tr><td style='width:150px;height:auto;'>" . date('h:i A', strtotime($change->starttime)) . "</td>";
                                //$location = get_location_detail($change->devicelat, $change->devicelong, $custID);
                                //$display .= "<td style='width:250px;height:auto;'>$location</td>";
                                // Temperature Sensors
                                // Temperature Sensor
                                if ($cm_details->use_humidity == 1) {
                                    $temp = 'Not Active';
                                    $s = "analog" . $vehicle->humidity;
                                    if ($vehicle->humidity != 0 && $change->$s != 0) {
                                        $temp = gettemp($change->$s);
                                    } else
                                        $temp = '-';
                                    if ($temp != '-' && $temp != "Not Active")
                                        $display .= "<td style='width:150px;height:auto;'>$temp %</td>";
                                    else
                                        $display .= "<td style='width:150px;height:auto;'>$temp</td>";
                                    /* min temp */
                                    $temp1_min = set_summary_min_temp($temp);
                                    /* maximum temp */
                                    $temp1_max = set_summary_max_temp($temp);
                                    /* above max */
                                    if ($temp > $min_max_temp1['temp_max_limit']) {
                                        $tr_abv_max++;
                                    }
                                }
                                $display .= '</tr>';
                                $tr++;
                            }
                        }
                        $display .= '</tbody>';
                        $display .= '</table><br/>';
                        $abv_compliance = round(($tr_abv_max / $tr) * 100, 2);
                        $within_compliance = round((($tr - $tr_abv_max) / $tr) * 100, 2);
                        $temp1_data = "<td style='text-align:center;'>
            <table class='table newTable'><thead><tr><th><u>Humidity</u></th></tr></thead>
            <tbody><tr><td>Minimum Humidity: $temp1_min%</td></tr>
            <tr><td>Maximum Humidity: $temp1_max %</td></tr>
            <tr><td>Total Reading: $tr</td></tr>
           </tbody></table>
            </td>";
                        $summary = "<table align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
        <thead>
            <tr><td colspan=$span style='background-color:#CCCCCC;font-weight:bold;'>Statistics</td></tr>
        </thead>
        <tbody>
            <tr>$temp1_data</tr>
        </tbody>
        </table>";
                        $display .="$summary";
                        return $display;
                    }

                    function datewise_temp_excep($totaldays, $datewise_arr, $for = 'html') {
                        $datewise = '';
                        if ($totaldays > 1) {
                            if ($for == 'pdf') {
                                $datewise .= "<table align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody><tr><td colspan='3'  style='background-color:#CCCCCC;font-weight:bold;'>Datewise</td></tr>";
                                $datewise .= "<tr style='background-color:#CCCCCC;font-weight:bold;'><td>Date</td><td>Exception time</td><td>Normal time</td></tr>";
                            } else {
                                $datewise .= "<table class='table newTable' style='width:50%;'><thead><tr><th colspan='3'>Datewise</th></tr>";
                                $datewise .= "<tr><th>Date</th><th>Exception time</th><th>Normal time</th></tr>";
                                $datewise .= "</thead>";
                                $datewise .= "<tbody>";
                            }
                            foreach ($datewise_arr as $date => $val) {
                                $exception = get_hh_mm($val * 60);
                                if ($date == date('d-m-Y')) {
                                    $now = (date('H') * 60) + date('i');
                                    $normal_val = $now - $val;
                                } else {
                                    $normal_val = 1440 - $val;
                                }
                                $normal = get_hh_mm($normal_val * 60);
                                $datewise .= "<tr><td>$date</td><td>$exception Hours</td><td>$normal Hours</td></tr>";
                            }
                            $datewise .= "</tbody></table><br/>";
                        }
                        return $datewise;
                    }

                    function create_temphtml_Excep_report($datarows, $vehicle, $veh_temp_details = null, $tempselect = null, $totaldays = 1, $datediff = 24) {
                        $tr = 0;
                        $display = '';
                        $blank = false;
                        $min_max_temp1 = get_min_max_temp($tempselect, $veh_temp_details);
                        $final = array();
                        $setstart = 0;
                        $count = 0;
                        foreach ($datarows as $change) {
                            $k = "tempsen$tempselect";
                            $s = "analog{$vehicle->$k}";
                            $temp = gettemp($change->$s);
                            if ($temp != '') {
                                get_min_temperature($temp);
                                get_max_temperature($temp);
                            }
                            $v = date("Y-m-d", strtotime($change->starttime)) . " 23:59:00 ";
                            $conflict = false;
                            if ($temp != '' && ($temp < $min_max_temp1['temp_min_limit'] || $temp > $min_max_temp1['temp_max_limit'] ) && strtotime($change->starttime) < strtotime($v)) {
                                $conflict = true;
                            }
                            if ($conflict) {
                                if ($setstart == 0) {
                                    $final[$count] = array('starttime' => $change->starttime, 'starttemp' => $temp);
                                }
                                $setstart++;
                                $prevTime = $change->starttime;
                            } else {
                                if (isset($prevTime)) {
                                    $final[$count]['endtime'] = $prevTime;
                                    $final[$count]['endtemp'] = $temp;
                                    $count++;
                                    unset($prevTime);
                                }
                                $setstart = 0;
                            }
                        }
                        if (!empty($final)) {
                            $tr = 0;
                            $datewise_arr = array();
                            foreach ($final as $datalist) {
                                $display .= "<tr><td>" . date('d-m-Y', strtotime($datalist['starttime'])) . "</td>";
                                $display .= "<td>" . date('h:i A', strtotime($datalist['starttime'])) . "</td>";
                                $display .= "<td>" . $datalist['starttemp'] . " &deg;C</td>";
                                if (empty($datalist['endtime'])) {
                                    $datalist['endtime'] = $v;
                                    $datalist['endtemp'] = $datalist['starttemp'];
                                    $display .= "<td>" . date('d-m-Y', strtotime($datalist['endtime'])) . "</td>";
                                    $display .= "<td>" . date('h:i A', strtotime($datalist['endtime'])) . "</td>";
                                    $display .= "<td>" . $datalist['endtemp'] . " &deg;C</td>";
                                    $time = getduration($datalist['endtime'], $datalist['starttime']);
                                } else {
                                    $display .= "<td>" . date('d-m-Y', strtotime($datalist['starttime'])) . "</td>";
                                    $display .= "<td>" . date('h:i A', strtotime($datalist['starttime'])) . "</td>";
                                    $display .= "<td>" . $datalist['endtemp'] . " &deg;C</td>";
                                    $time = getduration($datalist['endtime'], $datalist['starttime']);
                                }
                                $tr += $time;
                                $hour = floor(($time) / 60);
                                $minutes = ($time) % 60;
                                if ($minutes < 10) {
                                    $minutes = "0" . $minutes;
                                }
                                $time1 = $hour . ":" . $minutes;
                                $display .= "<td>$time1</td><tr>";
                                $thisdate = date('d-m-Y', strtotime($datalist['starttime']));
                                if (isset($datewise_arr[$thisdate])) {
                                    $datewise_arr[$thisdate] += $time;
                                } else {
                                    $datewise_arr[$thisdate] = $time;
                                }
                            }
                            $display .= '</tbody>';
                            $display .= '</table>';
                            $hour = floor(($tr) / 60);
                            $minutes = ($tr) % 60;
                            if ($minutes < 10) {
                                $minutes = "0" . $minutes;
                            }
                            $tr1 = $hour . ":" . $minutes;
                            $datewise = datewise_temp_excep($totaldays, $datewise_arr);
                            $normaltime = get_hh_mm($datediff - ($tr * 60));
                            $static_min_temp = get_min_temperature(null, true);
                            $static_max_temp = get_max_temperature(null, true);
                            $totalhours = round($datediff / 60 / 60);
                            $summary = "$datewise<table class='table newTable' style='width:45%;'><thead><tr><th>Statistics(Temperature" . $tempselect . ")</th></tr></thead>";
                            $summary .= "<tbody><tr><td style='text-align:center;'>Total number of selected days: $totaldays</td></tr>";
                            $summary .= "<tr><td style='text-align:center;'>Total hours: " . $totalhours . " Hours</td></tr>";
                            $summary .= "<tr><td style='text-align:center;'>Total normal temperature time: $normaltime Hours</td></tr>";
                            $summary .= "<tr><td style='text-align:center;'>Total exception temperature time: $tr1 Hours</td></tr>";
                            $summary .= "<tr><td style='text-align:center;'>Minimum temperature: $static_min_temp &deg;C</td></tr>";
                            $summary .= "<tr><td style='text-align:center;'>Maximum temperature: $static_max_temp &deg;C</td></tr>";
                            $summary .= "</tbody></table>";
                            $display .= "$summary</div>";
                        } else {
                            $display.="<tr><td colspan='100%' style='text-align:center;'>No Data</td></tr>";
                        }
                        return $display;
                    }

                    function create_temppdf_Excep_report($datarows, $vehicle, $custID = NULL, $veh_temp_details = null, $tempselect = 1, $totaldays = 1, $datediff = 24) {
                        $customerno = ($custID == null) ? $_SESSION['customerno'] : $custID;
                        $cm = new CustomerManager(null);
                        $cm_details = $cm->getcustomerdetail_byid($customerno);
                        $min_max_temp1 = get_min_max_temp($tempselect, $veh_temp_details, $cm_details->temp_sensors);
                        $final = array();
                        $setstart = 0;
                        $count = 0;
                        foreach ($datarows as $change) {
                            $k = "tempsen$tempselect";
                            $s = "analog{$vehicle->$k}";
                            $temp = gettemp($change->$s);
                            if ($temp != '') {
                                get_min_temperature($temp);
                                get_max_temperature($temp);
                            }
                            $v = date("Y-m-d", strtotime($change->starttime)) . " 23:59:00 ";
                            $conflict = false;
                            if ($temp != '' && ($temp < $min_max_temp1['temp_min_limit'] || $temp > $min_max_temp1['temp_max_limit'] ) && strtotime($change->starttime) < strtotime($v)) {
                                $conflict = true;
                            }
                            if ($conflict) {
                                if ($setstart == 0) {
                                    $final[$count] = array('starttime' => $change->starttime, 'starttemp' => $temp);
                                }
                                $setstart++;
                                $prevTime = $change->starttime;
                            } else {
                                if (isset($prevTime)) {
                                    $final[$count]['endtime'] = $prevTime;
                                    $final[$count]['endtemp'] = $temp;
                                    $count++;
                                    unset($prevTime);
                                }
                                $setstart = 0;
                            }
                        }
                        $display = "
        <tr style='background-color:#CCCCCC;font-weight:bold;'>
            <td style='width:150px;height:auto;'>Start Date</td>
            <td style='width:150px;height:auto;'>Start Time</td>
            <td style='width:150px;height:auto;'>Start Temp</td>
            <td style='width:150px;height:auto;'>End Date</td>
            <td style='width:150px;height:auto;'>End Time</td>
            <td style='width:150px;height:auto;'>End Temp</td>
            <td style='width:150px;height:auto;'>Duration [HH:MM]</td>
        </tr>";
                        $tr = 0;
                        $datewise_arr = array();
                        if (!empty($final)) {
                            foreach ($final as $datalist) {
                                $display .= "<tr><td>" . date("d-m-Y", strtotime($datalist['starttime'])) . "</td>";
                                $display .= "<td>" . date("h:i A", strtotime($datalist['starttime'])) . "</td>";
                                $display .= "<td>" . $datalist['starttemp'] . " &deg;C</td>";
                                if (empty($datalist['endtime'])) {
                                    $datalist['endtime'] = $v;
                                    $datalist['endtemp'] = $datalist['starttemp'];
                                    $display .= "<td>" . date("d-m-Y", strtotime($datalist['endtime'])) . "</td>";
                                    $display .= "<td>" . date("h:i A", strtotime($datalist['endtime'])) . "</td>";
                                    $display .= "<td>" . $datalist['endtemp'] . " &deg;C</td>";
                                } else {
                                    $display .= "<td>" . date("d-m-Y", strtotime($datalist['starttime'])) . "</td>";
                                    $display .= "<td>" . date("h:i A", strtotime($datalist['starttime'])) . "</td>";
                                    $display .= "<td>" . $datalist['endtemp'] . " &deg;C</td>";
                                }
                                $time = getduration($datalist['endtime'], $datalist['starttime']);
                                $tr += $time;
                                $hour = floor(($time) / 60);
                                $minutes = ($time) % 60;
                                if ($minutes < 10) {
                                    $minutes = "0" . $minutes;
                                }
                                $time1 = $hour . ":" . $minutes;
                                $display .= "<td>$time1</td></tr>";
                                $thisdate = date('d-m-Y', strtotime($datalist['starttime']));
                                if (isset($datewise_arr[$thisdate])) {
                                    $datewise_arr[$thisdate] += $time;
                                } else {
                                    $datewise_arr[$thisdate] = $time;
                                }
                            }
                            $hour = floor(($tr) / 60);
                            $minutes = ($tr) % 60;
                            if ($minutes < 10) {
                                $minutes = "0" . $minutes;
                            }
                            $tr1 = $hour . ":" . $minutes;
                            $datewise = datewise_temp_excep($totaldays, $datewise_arr, 'pdf');
                            $normaltime = get_hh_mm($datediff - ($tr * 60));
                            $static_min_temp = get_min_temperature(null, true);
                            $static_max_temp = get_max_temperature(null, true);
                            $totalhours = round($datediff / 60 / 60);
                            $display .= "</tbody></table><br/><br/>";
                            $display .= "$datewise<table align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
                            $display .= "<thead><tr><td style='background-color:#CCCCCC;font-weight:bold;' >Statistics(Temperature" . $tempselect . ")</td></tr></thead>";
                            $display .= "<tbody><tr><td style='text-align:center;'>Total number of selected days: $totaldays</td></tr>";
                            $display .= "<tr><td style='text-align:center;'>Total hours: " . $totalhours . " Hours</td></tr>";
                            $display .= "<tr><td style='text-align:center;'>Total normal temperature time: $normaltime Hours</td></tr>";
                            $display .= "<tr><td style='text-align:center;'>Total exception temperature time: $tr1 Hours</td></tr>";
                            $display .= "<tr><td style='text-align:center;'>Minimum Temperature: $static_min_temp &deg;C</td></tr>";
                            $display .= "<tr><td style='text-align:center;'>Maximum Temperature: $static_max_temp &deg;C</td></tr>";
                        } else {
                            $display .="<tr><td colspan='100%' style='text-align:center;'> No Data </td></tr>";
                        }
                        $display .= '</tbody>';
                        $display .= '</table>';
                        return $display;
                    }

                    function create_pdf_from_report($datarows, $acinvert) {
                        $runningtime = 0;
                        $idletime = 0;
                        $lastdate = NULL;
                        $display = '';
                        if (isset($datarows)) {
                            foreach ($datarows as $change) {
//            $comparedate = date('d-m-Y',strtotime($change->endtime));
//            if(strtotime($lastdate) != strtotime($comparedate))
//            {
//                $display .= "<tr style='background-color:#CCCCCC;'><th align='center' colspan = '100%'>".date('d-m-Y',strtotime($change->endtime))."</th></tr>";
//                $lastdate = date('d-m-Y',strtotime($change->endtime));
//            }
                                $currentdate = date("Y-m-d H:i:s");
                                $currentdate = substr($currentdate, '0', 11);
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                $display .= "<tr><td style='width:100px;height:auto;'>$change->starttime</td><td style='width:100px;height:auto;'>$change->endtime</td>";
                                if ($change->ignition == 1) {
                                    $display .= "<td style='width:150px;height:auto;'>ON</td>";
                                } else {
                                    $display .= "<td style='width:150px;height:auto;'>OFF</td>";
                                }
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:150px;height:auto;'>OFF</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:150px;height:auto;'>ON</td>";
                                        $idletime += $change->duration;
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:150px;height:auto;'>ON</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:150px;height:auto;'>OFF</td>";
                                        $idletime += $change->duration;
                                    }
                                }
                                $dt = new DateTime();
                                $currDate = date_format($dt, 'Y-m-d');
                                //$startT = gmdate("H:i", $change->duration);
                                $startT = date('H:i', strtotime($change->duration . ' minutes'));
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td style='width:100px;height:auto;'>$hour : $minute</td>";
                                $display .= '</tr>';
                            }
                        }
                        $display .= '</tbody>';
                        $display .= "</table>";
                        $display .="
	<page_footer>
                    [[page_cu]]/[[page_nb]]
    </page_footer>
	<div style='float:right;margin:15px;margin-right:60px;'>
    <table align='right' style='font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tr style='background-color:#CCCCCC;'><th><h4>Statistics</h4></th></tr>";
                        if ($acinvert == 1) {
                            $offtime = 1440 - $idletime;
                            $display .= "<tr><td>Total Gen Set ON Time = $idletime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr>";
                        } else {
                            $offtime = 1440 - $runningtime;
                            $display .= "<tr><td>Total Gen Set ON Time = $runningtime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr>";
                        }
                        $display .= "</table></div><hr style='margin-top:5px;'>";
                        $formatdate1 = date('F j, Y, g:i a');
                        $display .= "<div align='right' style='text-align:center;'> Report Generated On: $formatdate1 </div>";
                        return $display;
                    }

                    function create_gensetpdf_from_report($datarows, $acinvert) {
                        $runningtime = 0;
                        $idletime = 0;
                        $lastdate = NULL;
                        $display = '';
                        if (isset($datarows)) {
                            foreach ($datarows as $change) {
//            $comparedate = date('d-m-Y',strtotime($change->endtime));
//            if(strtotime($lastdate) != strtotime($comparedate))
//            {
//                $display .= "<tr style='background-color:#CCCCCC;'><th align='center' colspan = '100%'>".date('d-m-Y',strtotime($change->endtime))."</th></tr>";
//                $lastdate = date('d-m-Y',strtotime($change->endtime));
//            }
                                $currentdate = date("Y-m-d H:i:s");
                                $currentdate = substr($currentdate, '0', 11);
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                $display .= "<tr><td style='width:100px;height:auto;'>$change->starttime</td><td style='width:100px;height:auto;'>$change->endtime</td>";
//            if($change->ignition==1)
//            {
//                $display .= "<td style='width:150px;height:auto;'>ON</td>";
//            }
//            else
//            {
//                $display .= "<td style='width:150px;height:auto;'>OFF</td>";
//            }
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:150px;height:auto;'>OFF</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:150px;height:auto;'>ON</td>";
                                        $idletime += $change->duration;
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:150px;height:auto;'>ON</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:150px;height:auto;'>OFF</td>";
                                        $idletime += $change->duration;
                                    }
                                }
                                $dt = new DateTime();
                                $currDate = date_format($dt, 'Y-m-d');
                                //$startT = gmdate("H:i", $change->duration);
                                $startT = date('H:i', strtotime($change->duration . ' minutes'));
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td style='width:100px;height:auto;'>$hour : $minute</td>";
                                $display .= '</tr>';
                            }
                        }
                        $display .= '</tbody>';
                        $display .= "</table>";
                        $display .="
	<page_footer>
                    [[page_cu]]/[[page_nb]]
    </page_footer>
	<div style='float:right;margin:15px;margin-right:60px;'>
    <table align='right' style='font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tr style='background-color:#CCCCCC;'><th><h4>Statistics</h4></th></tr>";
                        if ($acinvert == 1) {
                            $offtime = 1440 - $idletime;
                            $display .= "<tr><td>Total Gen Set ON Time = $idletime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr>";
                        } else {
                            $offtime = 1440 - $runningtime;
                            $display .= "<tr><td>Total Gen Set ON Time = $runningtime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr>";
                        }
                        $display .= "</table></div><hr style='margin-top:5px;'>";
                        $formatdate1 = date('F j, Y, g:i a');
                        $display .= "<div align='right' style='text-align:center;'> Report Generated On: $formatdate1 </div>";
                        return $display;
                    }

                    function create_pdf_for_multipledays($datarows, $acinvert) {
                        $runningtime = 0;
                        $idletime = 0;
                        $lastdate = NULL;
                        $display = '';
                        if (isset($datarows)) {
                            foreach ($datarows as $change) {
                                $comparedate = date('d-m-Y', strtotime($change->endtime));
                                if (strtotime($lastdate) != strtotime($comparedate)) {
                                    $display .= "</tbody></table><h4><div> Date " . date('d-m-Y', strtotime($change->endtime)) . "</div></h4>
						<hr  id='style-six' /><table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tbody>";
                                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                                    $display .= "
                    <tr style='background-color:#CCCCCC;'>
                        <td style='width:100px;height:auto;'>Start Time</td>
                        <td style='width:100px;height:auto;'>End Time</td>
                        <td style='width:150px;height:auto;'>Ignition Status</td>
                        <td style='width:150px;height:auto;'>Gen Set Status</td>
                        <td style='width:150px;height:auto;'>Duration[Hours:Minutes]</td>
                    </tr>";
                                }
                                $currentdate = date("Y-m-d H:i:s");
                                $currentdate = substr($currentdate, '0', 11);
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                $display .= "<tr><td style='width:100px;height:auto;'>$change->starttime</td><td style='width:100px;height:auto;'>$change->endtime</td>";
                                if ($change->ignition == 1) {
                                    $display .= "<td style='width:150px;height:auto;'>ON</td>";
                                } else {
                                    $display .= "<td style='width:150px;height:auto;'>OFF</td>";
                                }
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:150px;height:auto;'>OFF</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:150px;height:auto;'>ON</td>";
                                        $idletime += $change->duration;
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:150px;height:auto;'>ON</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:150px;height:auto;'>OFF</td>";
                                        $idletime += $change->duration;
                                    }
                                }
                                $dt = new DateTime();
                                $currDate = date_format($dt, 'Y-m-d');
                                //$startT = gmdate("H:i", $change->duration);
                                $startT = date('H:i', strtotime($change->duration . ' minutes'));
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td style='width:100px;height:auto;'>$hour : $minute</td>";
                                $display .= '</tr>';
                            }
                        }
                        $display .= '</tbody>';
                        $display .= "</table>";
                        $display .="
	<page_footer>
                    [[page_cu]]/[[page_nb]]
    </page_footer>
	<div style='float:right;margin:15px;margin-right:60px;'>
    <table align='right' style='font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tr style='background-color:#CCCCCC;'><th><h4>Statistics</h4></th></tr>";
                        if ($acinvert == 1) {
                            $display .= "<tr><td>Total Gen Set ON Time = $idletime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $runningtime Minutes</td></tr>";
                        } else {
                            $display .= "<tr><td>Total Gen Set ON Time = $runningtime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $idletime Minutes</td></tr>";
                        }
                        $display .= "</table></div><hr style='margin-top:5px;'>";
                        $formatdate1 = date('F j, Y, g:i a');
                        $display .= "<div align='right' style='text-align:center;'> Report Generated On: $formatdate1 </div>";
                        return $display;
                    }

                    function create_gensetpdf_for_multipledays($datarows, $acinvert, $customerno, $uselocation) {
                        $i = 1;
                        $runningtime = 0;
                        $idletime = 0;
                        $lastdate = NULL;
                        $totalminute = 0;
                        $display = '';
                        if (isset($datarows)) {
                            $date_wise_arr = array();
                            foreach ($datarows as $change) {
                                $thisdate = date('d-m-Y', strtotime($change->starttime));
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
                                    $display .= "</tbody></table>
		<hr  id='style-six' /><br/>
                <table  id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;' ><tbody>";
                                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                                    $display .= "
                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                        <td style='width:50px;height:auto;'>Start Time</td>
                        <td style='width:300px;height:auto;'>Start Location</td>
                        <td style='width:50px;height:auto;'>End Time</td>
                        <td style='width:300px;height:auto;'>End Location</td>
                        <td style='width:100px;height:auto;'>Genset Status</td>
                        <td style='width:100px;height:auto;'>Duration[HH:MM]</td>
                        <td style='width:100px;height:auto;'>Fuel Consumed<br>(In litre)</td>
                    </tr>
                    <tr style='background-color:#D8D5D6;font-weight:bold;'><td colspan='7' >Date $lastdate</td></tr>";
                                    $i++;
                                }
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                                    $change->startlocation = 'Unable to fetch Location';
                                    $change->endlocation = 'Unable to fetch Location';
                                } else {
                                    $change->startlocation = location_cmn($change->startcgeolat, $change->startcgeolong, $uselocation, $customerno);
                                    $change->endlocation = location_cmn($change->endcgeolat, $change->endcgeolong, $uselocation, $customerno);
                                }
                                $start_time_disp = date('h:i A', strtotime($change->starttime));
                                $end_time_disp = date('h:i A', strtotime($change->endtime));
                                $display .= "<tr><td style='width:50px;height:auto;'>$start_time_disp</td><td style='width:300px;height:auto;'>$change->startlocation</td>
                    <td style='width:50px;height:auto;' >$end_time_disp</td><td style='width:300px;height:auto;'>$change->endlocation</td>";
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:100px;height:auto;'>OFF</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:100px;height:auto;'>ON</td>";
                                        $idletime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:100px;height:auto;'>ON</td>";
                                        $runningtime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    } else {
                                        $display .= "<td style='width:100px;height:auto;'>OFF</td>";
                                        $idletime += $change->duration;
                                    }
                                }
                                $dt = new DateTime();
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td style='width:150px;height:auto;'>$hour : $minute</td>";
                                if (isset($change->fuelltr)) {
                                    $fuelltr[] = $change->fuelltr;
                                    $display .= "<td>" . $change->fuelltr . "</td>";
                                } else {
                                    $display .= "<td> </td>";
                                }
                                $display .= '</tr>';
                            }
                        }
                        $display .= '</tbody></table>';
                        $display .="<page_footer>[[page_cu]]/[[page_nb]]</page_footer>";
                        $totaltime = 1440 * $count + $totalminute;
                        $totaltime = round($totaltime);
                        if ($acinvert == 1) {
                            $offtime = $totaltime - $idletime;
                            $last = "<tr><td colspan = '9'>Total Genset ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr><tr><td colspan = '9'>Total Genset OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>	<tr><td style='text-align:center;'>Total Fuel Consumed = " . array_sum($fuelltr) . " ltr</td></tr>";
                        } else {
                            $offtime = $totaltime - $runningtime;
                            $last .= "<tr><td colspan = '9'>Total Genset ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr><tr><td colspan = '9'>Total Genset OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>	<tr><td style='text-align:center;'>Total Fuel Consumed = " . array_sum($fuelltr) . " ltr</td></tr>";
                        }
                        $datewise = ac_datewise($count, $date_wise_arr, 'pdf');
                        $display .="
<div style='float:right;margin:15px;margin-right:60px;'>
    $datewise
    <table align='center'  style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
	<tbody>
            <tr  style='background-color:#CCCCCC;font-weight:bold;'><td colspan = '9'>Statistics</td></tr>
            $last
        </tbody>
  </table>
</div>";
                        return $display;
                    }

                    function create_gensetpdf_for_multipledays_details($datarows, $acinvert, $customerno, $uselocation) {
                        $i = 1;
                        $runningtime = 0;
                        $idletime = 0;
                        $lastdate = NULL;
                        $totalminute = 0;
                        $display = '';
                        if (isset($datarows)) {
                            $date_wise_arr = array();
                            foreach ($datarows as $change) {
                                $thisdate = date('d-m-Y', strtotime($change->starttime));
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
                                    $display .= "</tbody></table>
		<hr  id='style-six' /><br/>
                <table  id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;' ><tbody>";
                                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                                    $fuelsensor = "";
                                    if ($_SESSION['use_fuel_sensor'] != 0) {
                                        $fuelsensor .= "<td style='width:100px;height:auto;'>Fuel Consumed<br>(In litre)</td></tr>
                               <tr style='background-color:#D8D5D6;font-weight:bold;'><td colspan='7' >Date $lastdate</td></tr>";
                                    } else {
                                        $fuelsensor .= "</tr><tr style='background-color:#D8D5D6;font-weight:bold;'><td colspan='6' >Date $lastdate</td></tr>";
                                    }
                                    $display .= "
                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                        <td style='width:50px;height:auto;'>Start Time</td>
                        <td style='width:300px;height:auto;'>Start Location</td>
                        <td style='width:50px;height:auto;'>End Time</td>
                        <td style='width:300px;height:auto;'>End Location</td>
                        <td style='width:100px;height:auto;'>Genset Status</td>
                        <td style='width:100px;height:auto;'>Duration[HH:MM]</td>
                        $fuelsensor ";
                                    $i++;
                                }
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                                    $change->startlocation = 'Unable to fetch Location';
                                    $change->endlocation = 'Unable to fetch Location';
                                } else {
                                    $change->startlocation = location_cmn($change->startcgeolat, $change->startcgeolong, $uselocation, $customerno);
                                    $change->endlocation = location_cmn($change->endcgeolat, $change->endcgeolong, $uselocation, $customerno);
                                }
                                $start_time_disp = date('h:i A', strtotime($change->starttime));
                                $end_time_disp = date('h:i A', strtotime($change->endtime));
                                $display .= "<tr><td style='width:50px;height:auto;'>$start_time_disp</td><td style='width:300px;height:auto;'>$change->startlocation</td>
                    <td style='width:50px;height:auto;' >$end_time_disp</td><td style='width:300px;height:auto;'>$change->endlocation</td>";
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:100px;height:auto;'>OFF</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:100px;height:auto;'>ON</td>";
                                        $idletime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:100px;height:auto;'>ON</td>";
                                        $runningtime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    } else {
                                        $display .= "<td style='width:100px;height:auto;'>OFF</td>";
                                        $idletime += $change->duration;
                                    }
                                }
                                $dt = new DateTime();
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td style='width:150px;height:auto;'>$hour : $minute</td>";
                                if (isset($change->fuelltr) && $_SESSION['use_fuel_sensor'] != 0) {
                                    $fuelltr[] = $change->fuelltr;
                                    $display .= "<td>" . $change->fuelltr . "</td>";
                                } else {
                                    $display .= "";
                                }
                                $display .= '</tr>';
                            }
                        }
                        $display .= '</tbody></table>';
                        $display .="<page_footer>[[page_cu]]/[[page_nb]]</page_footer>";
                        $totaltime = 1440 * $count + $totalminute;
                        $totaltime = round($totaltime);
                        if ($acinvert == 1) {
                            $offtime = $totaltime - $idletime;
                            if ($_SESSION['use_fuel_sensor'] != 0) {
                                $fuelcons = "<td style='text-align:center;'>Total Fuel Consumed = " . array_sum($fuelltr) . " ltr</td>";
                            } else {
                                $fuelcons = "";
                            }
                            $last = "<tr><td colspan = '9'>Total Genset ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr><tr><td colspan = '9'>Total Genset OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>	<tr>" . $fuelcons . "</tr>";
                        } else {
                            $offtime = $totaltime - $runningtime;
                            $last .= "<tr><td colspan = '9'>Total Genset ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr><tr><td colspan = '9'>Total Genset OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>	<tr>" . $fuelcons . "</tr>";
                        }
                        $display .=" <div style='float:right;margin:15px;margin-right:60px;'> <table align='center'  style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'> <tbody> <tr  style='background-color:#CCCCCC;font-weight:bold;'><td colspan = '9'>Statistics</td></tr> $last </tbody> </table></div>";
                        return $display;
                    }

                    function create_gensetpdf_for_multipledays_summary($datarows, $acinvert, $customerno, $uselocation) {
                        $i = 1;
                        $runningtime = 0;
                        $idletime = 0;
                        $lastdate = NULL;
                        $totalminute = 0;
                        $display = '';
                        if (isset($datarows)) {
                            $date_wise_arr = array();
                            foreach ($datarows as $change) {
                                $thisdate = date('d-m-Y', strtotime($change->starttime));
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
                                    //$display .="<table  id='search_table_2' align='center' style='display:none;' ><tbody>";
                                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                                    //$display .= "<tr style='background-color:#D8D5D6;font-weight:bold;'><td colspan='7' >Date $lastdate</td></tr>";
                                    $i++;
                                }
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                                    $change->startlocation = 'Unable to fetch Location';
                                    $change->endlocation = 'Unable to fetch Location';
                                } else {
                                    $change->startlocation = location_cmn($change->startcgeolat, $change->startcgeolong, $uselocation, $customerno);
                                    $change->endlocation = location_cmn($change->endcgeolat, $change->endcgeolong, $uselocation, $customerno);
                                }
                                $start_time_disp = date('h:i A', strtotime($change->starttime));
                                $end_time_disp = date('h:i A', strtotime($change->endtime));
//            $display .= "<tr><td style='width:50px;height:auto;'>$start_time_disp</td><td style='width:300px;height:auto;'>$change->startlocation</td>
//                    <td style='width:50px;height:auto;' >$end_time_disp</td><td style='width:300px;height:auto;'>$change->endlocation</td>";
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        //$display .= "<td style='width:100px;height:auto;'>OFF</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        //$display .= "<td style='width:100px;height:auto;'>ON</td>";
                                        $idletime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        //$display .= "<td style='width:100px;height:auto;'>ON</td>";
                                        $runningtime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    } else {
                                        //$display .= "<td style='width:100px;height:auto;'>OFF</td>";
                                        $idletime += $change->duration;
                                    }
                                }
                                $dt = new DateTime();
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                //$display .= "<td style='width:150px;height:auto;'>$hour : $minute</td>";
                                if (isset($change->fuelltr)) {
                                    $fuelltr[] = $change->fuelltr;
                                    //$display .= "<td>".$change->fuelltr."</td>";
                                } else {
                                    //$display .= "<td> </td>";
                                }
                                //   $display .= '</tr></tbody></table>';
                            }
                        }
                        $display .= '</tbody></table>';
                        $display .="<page_footer>[[page_cu]]/[[page_nb]]</page_footer>";
                        $totaltime = 1440 * $count + $totalminute;
                        $totaltime = round($totaltime);
                        if ($acinvert == 1) {
                            $offtime = $totaltime - $idletime;
                            if ($_SESSION['use_fuel_session'] != 0) {
                                $summary_fuel = "<td style='text-align:center;'>Total Fuel Consumed = " . array_sum($fuelltr) . " ltr</td>";
                            } else {
                                $summary_fuel = "";
                            }
                            $last = "<tr><td colspan = '9'>Total Genset ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr><tr><td colspan = '9'>Total Genset OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>	<tr>" . $summary_fuel . "</tr>";
                        } else {
                            $offtime = $totaltime - $runningtime;
                            $last .= "<tr><td colspan = '9'>Total Genset ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr><tr><td colspan = '9'>Total Genset OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>	<tr>" . $summary_fuel . "</tr>";
                        }
                        $datewise = ac_datewise($count, $date_wise_arr, 'pdf');
                        $display .="
<div style='float:right;margin:15px;margin-right:60px;'>
    $datewise
    <table align='center'  style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
	<tbody>
            <tr  style='background-color:#CCCCCC;font-weight:bold;'><td colspan = '9'>Statistics</td></tr>
            $last
        </tbody>
  </table>
</div>";
                        return $display;
                    }

                    function create_extrapdf_for_multipledays($datarows, $extraid, $extraval) {
                        $i = 1;
                        $runningtime = 0;
                        $idletime = 0;
                        $lastdate = NULL;
                        $totalminute = 0;
                        $display = '';
                        if (isset($datarows)) {
                            $test = 0;
                            foreach ($datarows as $change) {
                                $test++;
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
                                    $display .= "</tbody></table>
		<hr  id='style-six' /><br/>
                <table  id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;' ><tbody>";
                                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                                    $display .= "
                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                        <td style='width:50px;height:auto;'>Start Time</td>
                        <td style='width:300px;height:auto;'>Start Location</td>
                        <td style='width:50px;height:auto;'>End Time</td>
                        <td style='width:300px;height:auto;'>End Location</td>
                        <td style='width:100px;height:auto;'>$extraval Status</td>
                        <td style='width:150px;height:auto;'>Duration[HH:MM]</td>
                    </tr>
                    <tr style='background-color:#D8D5D6;font-weight:bold;'><td colspan='6' >Date $lastdate</td></tr>";
                                    $i++;
                                }
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                                    $change->startlocation = 'Unable to fetch Location';
                                    $change->endlocation = 'Unable to fetch Location';
                                } else {
                                    $uselocation = retval_issetor($change->uselocation);
                                    $change->startlocation = location($change->startcgeolat, $change->startcgeolong, $uselocation);
                                    $change->endlocation = location($change->endcgeolat, $change->endcgeolong, $uselocation);
                                }
                                $start_time_disp = date('h:i A', strtotime($change->starttime));
                                $end_time_disp = date('h:i A', strtotime($change->endtime));
                                $display .= "<tr><td style='width:50px;height:auto;'>$start_time_disp</td><td style='width:300px;height:auto;'>$change->startlocation</td>
                    <td style='width:50px;height:auto;' >$end_time_disp</td><td style='width:300px;height:auto;'>$change->endlocation</td>";
                                $category_array = Array();
                                $digital = Array();
                                $category = (int) $change->digitalio;
                                $binarycategory = sprintf("%08s", DecBin($category));
                                for ($shifter = 1; $shifter <= 127; $shifter = $shifter << 1) {
                                    $binaryshifter = sprintf("%08s", DecBin($shifter));
                                    if ($category & $shifter) {
                                        $category_array[] = $shifter;
                                    }
                                }
                                if (in_array($extraid, $category_array)) {
                                    $display .= '<td>On</td>';
                                    $runningtime += $change->duration;
                                } else {
                                    $display .= '<td>OFF</td>';
                                    $idletime += $change->duration;
                                }
                                $dt = new DateTime();
                                $currDate = date_format($dt, 'Y-m-d');
                                $startT = date('H:i', strtotime($change->duration . ' minutes'));
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td style='width:150px;height:auto;'>$hour : $minute</td>";
                                $display .= '</tr>';
                            }
                        }
                        $display .= '</tbody></table>';
                        $display .="<page_footer>[[page_cu]]/[[page_nb]]</page_footer>";
                        $totaltime = 1440 * $count + $totalminute;
                        $totaltime = round($totaltime);
                        if ($acinvert == 1) {
                            $offtime = $totaltime - $idletime;
                            $last = "<tr><td colspan = '9'>Total $extraval ON Time = " . m2h($idletime) . " HH:MM </td></tr><tr><td colspan = '9'>Total $extraval OFF Time = " . m2h($offtime) . "HH:MM </td></tr>";
                        } else {
                            $offtime = $totaltime - $runningtime;
                            $last .= "<tr><td colspan = '9'>Total $extraval ON Time = " . m2h($runningtime) . " HH:MM </td></tr><tr><td colspan = '9'>Total $extraval OFF Time = " . m2h($offtime) . " HH:MM </td></tr>";
                        }
                        $display .="
<div style='float:right;margin:15px;margin-right:60px;'>
    <table align='center'  style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
	<tbody>
            <tr  style='background-color:#CCCCCC;font-weight:bold;'><td colspan = '9'><h4>Statistics</h4></td></tr>
            $last
        </tbody>
  </table>
</div>";
                        return $display;
                    }

                    function create_csv_from_report($datarows, $acinvert) {
                        $runningtime = 0;
                        $idletime = 0;
                        $lastdate = NULL;
                        $display = '';
                        if (isset($datarows)) {
                            foreach ($datarows as $change) {
//            $comparedate = date('d-m-Y',strtotime($change->endtime));
//            if(strtotime($lastdate) != strtotime($comparedate))
//            {
//                $display .= "<tr style='background-color:#CCCCCC;'><th align='center' colspan = '100%'>".date('d-m-Y',strtotime($change->endtime))."</th></tr>";
//                $lastdate = date('d-m-Y',strtotime($change->endtime));
//            }
                                $currentdate = date("Y-m-d H:i:s");
                                $currentdate = substr($currentdate, '0', 11);
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                $display .= "<tr><td style='width:50px;height:auto; text-align: center;'></td><td style='width:50px;height:auto; text-align: center;'>$change->starttime</td><td style='width:50px;height:auto; text-align: center;'>$change->endtime</td>";
                                if ($change->ignition == 1) {
                                    $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                                } else {
                                    $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                                }
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                                        $idletime += $change->duration;
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                                        $idletime += $change->duration;
                                    }
                                }
                                $dt = new DateTime();
                                $currDate = date_format($dt, 'Y-m-d');
                                //$startT = gmdate("H:i", $change->duration);
                                $startT = date('H:i', strtotime($change->duration . ' minutes'));
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td style='width:100px;height:auto; text-align: center;'>$hour : $minute</td>";
                                $display .= '<td style="width:50px;height:auto; text-align: center;"></td></tr>';
                            }
                        }
                        $display .= '</tbody>';
                        $display .= "</table><hr style='margin-top:20px;'>";
                        $display .="<table align = 'center' style='text-align:center;'><thead><tr style='background-color:#CCCCCC;'><th style='width:50px;height:auto; text-align: center;'></th><th colspan='5'>Statistics</th><th style='width:50px;height:auto; text-align: center;'></th></tr></thead>";
                        if ($acinvert == 1) {
                            $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $idletime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $runningtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
                        } else {
                            $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $runningtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $idletime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
                        }
                        return $display;
                    }

                    function create_gensetcsv_from_report($datarows, $acinvert) {
                        $runningtime = 0;
                        $idletime = 0;
                        $lastdate = NULL;
                        $display = '';
                        if (isset($datarows)) {
                            foreach ($datarows as $change) {
//            $comparedate = date('d-m-Y',strtotime($change->endtime));
//            if(strtotime($lastdate) != strtotime($comparedate))
//            {
//                $display .= "<tr style='background-color:#CCCCCC;'><th align='center' colspan = '100%'>".date('d-m-Y',strtotime($change->endtime))."</th></tr>";
//                $lastdate = date('d-m-Y',strtotime($change->endtime));
//            }
                                $currentdate = date("Y-m-d H:i:s");
                                $currentdate = substr($currentdate, '0', 11);
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                $display .= "<tr><td style='width:50px;height:auto; text-align: center;'></td><td style='width:50px;height:auto; text-align: center;'>$change->starttime</td><td style='width:50px;height:auto; text-align: center;'>$change->endtime</td>";
//            if($change->ignition==1)
//            {
//                $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
//            }
//            else
//            {
//                $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
//            }
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                                        $idletime += $change->duration;
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                                        $idletime += $change->duration;
                                    }
                                }
                                $dt = new DateTime();
                                $currDate = date_format($dt, 'Y-m-d');
                                //$startT = gmdate("H:i", $change->duration);
                                $startT = date('H:i', strtotime($change->duration . ' minutes'));
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td style='width:100px;height:auto; text-align: center;'>$hour : $minute</td>";
                                $display .= '<td style="width:50px;height:auto; text-align: center;"></td></tr>';
                            }
                        }
                        $display .= '</tbody>';
                        $display .= "</table><hr style='margin-top:20px;'>";
                        $display .="<table align = 'center' style='text-align:center;'><thead><tr style='background-color:#CCCCCC;'><th style='width:50px;height:auto; text-align: center;'></th><th colspan='5'>Statistics</th><th style='width:50px;height:auto; text-align: center;'></th></tr></thead>";
                        if ($acinvert == 1) {
                            $offtime = 1440 - $idletime;
                            $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $idletime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $offtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
                        } else {
                            $offtime = 1440 - $runningtime;
                            $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $runningtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $offtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
                        }
                        return $display;
                    }

                    function create_excel_from_report($datarows, $acinvert) {
                        $runningtime = 0;
                        $idletime = 0;
                        $lastdate = NULL;
                        $display = '';
                        if (isset($datarows)) {
                            foreach ($datarows as $change) {
                                $comparedate = date('d-m-Y', strtotime($change->endtime));
                                if (strtotime($lastdate) != strtotime($comparedate)) {
                                    $display .= "<tr style='background-color:#CCCCCC;'><th align='center' colspan = '7'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                                    $lastdate = date('d-m-Y', strtotime($change->endtime));
                                    $display .= "
                        <tr style='background-color:#CCCCCC;'>
                            <td style='width:50px;height:auto; text-align: center;'></td>
                            <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                            <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                            <td style='width:50px;height:auto; text-align: center;'>Ignition Status</td>
                            <td style='width:50px;height:auto; text-align: center;'>Gen Set Status</td>
                            <td style='width:150px;height:auto; text-align: center;'>Duration[Hours:Minutes]</td>
                            <td style='width:50px;height:auto; text-align: center;'></td>
                        </tr>";
                                }
                                $currentdate = date("Y-m-d H:i:s");
                                $currentdate = substr($currentdate, '0', 11);
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                $display .= "<tr><td style='width:50px;height:auto; text-align: center;'></td><td style='width:50px;height:auto; text-align: center;'>$change->starttime</td><td style='width:50px;height:auto; text-align: center;'>$change->endtime</td>";
                                if ($change->ignition == 1) {
                                    $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                                } else {
                                    $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                                }
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                                        $idletime += $change->duration;
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                                        $idletime += $change->duration;
                                    }
                                }
                                $dt = new DateTime();
                                $currDate = date_format($dt, 'Y-m-d');
                                //$startT = gmdate("H:i", $change->duration);
                                $startT = date('H:i', strtotime($change->duration . ' minutes'));
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td style='width:100px;height:auto; text-align: center;'>$hour : $minute</td>";
                                $display .= '<td style="width:50px;height:auto; text-align: center;"></td></tr>';
                            }
                        }
                        $display .= '</tbody>';
                        $display .= "</table><hr style='margin-top:20px;'>";
                        $display .="<table align = 'center' style='text-align:center;'><thead><tr style='background-color:#CCCCCC;'><th style='width:50px;height:auto; text-align: center;'></th><th colspan='5'>Statistics</th><th style='width:50px;height:auto; text-align: center;'></th></tr></thead>";
                        if ($acinvert == 1) {
                            $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $idletime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $runningtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
                        } else {
                            $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $runningtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $idletime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
                        }
                        return $display;
                    }

                    function create_gensetexcel_from_report($datarows, $acinvert, $customerno, $uselocation) {
                        $i = 1;
                        $runningtime = 0;
                        $idletime = 0;
                        $lastdate = NULL;
                        $totalminute = 0;
                        $display = '';
                        if (isset($datarows)) {
                            $start = 0;
                            $date_wise_arr = array();
                            foreach ($datarows as $change) {
                                $start++;
                                $thisdate = date('d-m-Y', strtotime($change->endtime));
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
                                    if ($start != 1) {
                                        $display .= "<tr><td colspan = '6'></td></tr>";
                                    }
                                    $display .= "
                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                        <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                        <td style='width:150px;height:auto; text-align: center;'>Start Location</td>
                        <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                        <td style='width:150px;height:auto; text-align: center;'>End Location</td>
                        <td style='width:50px;height:auto; text-align: center;'>Gen Set Status</td>
                        <td style='width:150px;height:auto; text-align: center;'>Duration[Hours:Minutes]</td>
                        <td style='width:150px;height:auto; text-align: center;'>Fuel Consumed(In litre)</td>
                    </tr>";
                                    $display .= "<tr style='background-color:#D8D5D6;'><th align='center' colspan = '7'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                                    $i++;
                                }
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                                    $change->startlocation = 'Unable to fetch Location';
                                    $change->endlocation = 'Unable to fetch Location';
                                } else {
                                    $change->startlocation = location_cmn($change->startcgeolat, $change->startcgeolong, $uselocation, $customerno);
                                    $change->endlocation = location_cmn($change->endcgeolat, $change->endcgeolong, $uselocation, $customerno);
                                }
                                $start_time_disp = date('h:i A', strtotime($change->starttime));
                                $end_time_disp = date('h:i A', strtotime($change->endtime));
                                $display .= "<tr><td style='width:50px;height:auto; text-align: center;'>$start_time_disp</td><td>$change->startlocation</td><td style='width:50px;height:auto; text-align: center;'>$end_time_disp</td><td>$change->endlocation</td>";
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                                        $idletime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                                        $runningtime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    } else {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                                        $idletime += $change->duration;
                                    }
                                }
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td style='width:100px;height:auto; text-align: center;'>$hour : $minute</td>";
                                if (isset($change->fuelltr)) {
                                    $fuelltr[] = $change->fuelltr;
                                    $display .= "<td>" . $change->fuelltr . "</td>";
                                } else {
                                    $display .= "<td> </td>";
                                }
                                $display .= '</tr>';
                            }
                        }
                        $display .= '</tbody>';
                        $display .= "</table><hr style='margin-top:20px;'>";
                        $datewise = ac_datewise($count, $date_wise_arr, 'pdf');
                        $display .="$datewise<table align = 'center' style='text-align:center;'>
           <thead><tr style='background-color:#CCCCCC;'><th colspan='6'>Statistics</th></tr></thead>";
                        $totaltime = 1440 * $count + $totalminute;
                        $totaltime = round($totaltime);
                        if ($acinvert == 1) {
                            $offtime = $totaltime - $idletime;
                            $display .= "<tbody>
        <tr><td colspan = '6' style=' text-align: center;'>Total Gen Set ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr>
        <tr><td colspan = '6' style=' text-align: center;'>Total Gen Set OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>
        <tr><td colspan = '6' style=' text-align: center;'>Total Fuel Consumed = " . array_sum($fuelltr) . "ltr</td></tr></tbody></table>";
                        } else {
                            $offtime = $totaltime - $runningtime;
                            $display .= "<tbody>
        <tr><td colspan = '6' style='text-align: center;'>Total Gen Set ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr>
        <tr><td colspan = '6' style='text-align: center;'>Total Gen Set OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>
        <tr><td colspan = '6' style=' text-align: center;'>Total Fuel Consumed = " . array_sum($fuelltr) . "ltr</td></tr></tbody></table>";
                        }
                        return $display;
                    }

//genset excel summary report - start
                    function create_gensetexcel_summary_from_report($datarows, $acinvert, $customerno, $uselocation) {
                        $i = 1;
                        $runningtime = 0;
                        $idletime = 0;
                        $lastdate = NULL;
                        $totalminute = 0;
                        $display = '';
                        if (isset($datarows)) {
                            $start = 0;
                            $date_wise_arr = array();
                            foreach ($datarows as $change) {
                                $start++;
                                $thisdate = date('d-m-Y', strtotime($change->endtime));
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
                                    if ($start != 1) {
                                        $display .= "<tr><td colspan = '6'></td></tr>";
                                    }
//                $display .= "
//                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
//                        <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
//                        <td style='width:150px;height:auto; text-align: center;'>Start Location</td>
//                        <td style='width:50px;height:auto; text-align: center;'>End Time</td>
//                        <td style='width:150px;height:auto; text-align: center;'>End Location</td>
//                        <td style='width:50px;height:auto; text-align: center;'>Gen Set Status</td>
//                        <td style='width:150px;height:auto; text-align: center;'>Duration[Hours:Minutes]</td>
//                        <td style='width:150px;height:auto; text-align: center;'>Fuel Consumed(In litre)</td>
//                    </tr>";
                                    //$display .= "<tr style='background-color:#D8D5D6;'><th align='center' colspan = '7'>".date('d-m-Y',strtotime($change->endtime))."</th></tr>";
                                    $i++;
                                }
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                                    $change->startlocation = 'Unable to fetch Location';
                                    $change->endlocation = 'Unable to fetch Location';
                                } else {
                                    $change->startlocation = location_cmn($change->startcgeolat, $change->startcgeolong, $uselocation, $customerno);
                                    $change->endlocation = location_cmn($change->endcgeolat, $change->endcgeolong, $uselocation, $customerno);
                                }
                                $start_time_disp = date('h:i A', strtotime($change->starttime));
                                $end_time_disp = date('h:i A', strtotime($change->endtime));
                                //$display .= "<tr><td style='width:50px;height:auto; text-align: center;'>$start_time_disp</td><td>$change->startlocation</td><td style='width:50px;height:auto; text-align: center;'>$end_time_disp</td><td>$change->endlocation</td>";
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        //$display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        //$display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                                        $idletime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        //$display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                                        $runningtime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    } else {
                                        //$display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                                        $idletime += $change->duration;
                                    }
                                }
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                //$display .= "<td style='width:100px;height:auto; text-align: center;'>$hour : $minute</td>";
                                if (isset($change->fuelltr)) {
                                    $fuelltr[] = $change->fuelltr;
                                    //  $display .= "<td>".$change->fuelltr."</td>";
                                } else {
                                    //$display .= "<td> </td>";
                                }
                                //$display .= '</tr>';
                            }
                        }
                        $display .= '</tbody>';
                        $display .= "</table><hr style='margin-top:20px;'>";
                        $datewise = ac_datewise($count, $date_wise_arr, 'pdf');
                        $display .="$datewise<table align = 'center' style='text-align:center;'>
           <thead><tr style='background-color:#CCCCCC;'><th colspan='6'>Statistics</th></tr></thead>";
                        $totaltime = 1440 * $count + $totalminute;
                        $totaltime = round($totaltime);
                        if ($acinvert == 1) {
                            $offtime = $totaltime - $idletime;
                            if ($_SESSION['use_fuel_sensor'] != 0) {
                                $fuel_summary = "<td colspan = '6' style=' text-align: center;'>Total Fuel Consumed = " . array_sum($fuelltr) . "ltr</td>";
                            } else {
                                $fuel_summary = "";
                            }
                            $display .= "<tbody>
        <tr><td colspan = '6' style=' text-align: center;'>Total Gen Set ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr>
        <tr><td colspan = '6' style=' text-align: center;'>Total Gen Set OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>
        <tr>" . $fuel_summary . "</tr></tbody></table>";
                        } else {
                            $offtime = $totaltime - $runningtime;
                            $display .= "<tbody>
        <tr><td colspan = '6' style='text-align: center;'>Total Gen Set ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr>
        <tr><td colspan = '6' style='text-align: center;'>Total Gen Set OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>
        <tr>" . $fuel_summary . "</tr></tbody></table>";
                        }
                        return $display;
                    }

//gensert excel sumamry report - end
//genset excel report -details start
                    function create_gensetexcel_from_reportdetails($datarows, $acinvert, $customerno, $uselocation) {
                        $i = 1;
                        $runningtime = 0;
                        $idletime = 0;
                        $lastdate = NULL;
                        $totalminute = 0;
                        $display = '';
                        if (isset($datarows)) {
                            $start = 0;
                            $date_wise_arr = array();
                            foreach ($datarows as $change) {
                                $start++;
                                $thisdate = date('d-m-Y', strtotime($change->endtime));
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
                                    if ($start != 1) {
                                        $display .= "<tr><td colspan = '6'></td></tr>";
                                    }
                                    $fuelsensor = "";
                                    if ($_SESSION['use_fuel_sensor'] != 0) {
                                        $fuelsensor .= "<td style='width:150px;height:auto; text-align: center;'>Fuel Consumed(In litre)</td></tr><tr style='background-color:#D8D5D6;'><th align='center' colspan = '7'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                                    } else {
                                        $fuelsensor .= "</tr><tr style='background-color:#D8D5D6;'><th align='center' colspan = '6'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                                    }
                                    $display .= "
                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                        <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                        <td style='width:150px;height:auto; text-align: center;'>Start Location</td>
                        <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                        <td style='width:150px;height:auto; text-align: center;'>End Location</td>
                        <td style='width:50px;height:auto; text-align: center;'>Gen Set Status</td>
                        <td style='width:150px;height:auto; text-align: center;'>Duration[Hours:Minutes]</td>
                       $fuelsensor ";
                                    $i++;
                                }
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                                    $change->startlocation = 'Unable to fetch Location';
                                    $change->endlocation = 'Unable to fetch Location';
                                } else {
                                    $change->startlocation = location_cmn($change->startcgeolat, $change->startcgeolong, $uselocation, $customerno);
                                    $change->endlocation = location_cmn($change->endcgeolat, $change->endcgeolong, $uselocation, $customerno);
                                }
                                $start_time_disp = date('h:i A', strtotime($change->starttime));
                                $end_time_disp = date('h:i A', strtotime($change->endtime));
                                $display .= "<tr><td style='width:50px;height:auto; text-align: center;'>$start_time_disp</td><td>$change->startlocation</td><td style='width:50px;height:auto; text-align: center;'>$end_time_disp</td><td>$change->endlocation</td>";
                                if ($acinvert == 1) {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                                        $runningtime += $change->duration;
                                    } else {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                                        $idletime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    }
                                } else {
                                    if ($change->digitalio == 0) {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>ON</td>";
                                        $runningtime += $change->duration;
                                        if (isset($date_wise_arr[$thisdate])) {
                                            $date_wise_arr[$thisdate] += $change->duration;
                                        } else {
                                            $date_wise_arr[$thisdate] = $change->duration;
                                        }
                                    } else {
                                        $display .= "<td style='width:50px;height:auto; text-align: center;'>OFF</td>";
                                        $idletime += $change->duration;
                                    }
                                }
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td style='width:100px;height:auto; text-align: center;'>$hour : $minute</td>";
                                if (isset($change->fuelltr) && $_SESSION['use_fuel_sensor'] != 0) {
                                    $fuelltr[] = $change->fuelltr;
                                    $display .= "<td>" . $change->fuelltr . "</td>";
                                } else {
                                    $display .= "";
                                }
                                $display .= '</tr>';
                            }
                        }
                        $display .= '</tbody>';
                        $display .= "</table><hr style='margin-top:20px;'>";
                        //$datewise = ac_datewise($count,$date_wise_arr,'pdf');
                        $display .="<table align = 'center' style='text-align:center;'><thead><tr style='background-color:#CCCCCC;'><th colspan='6'>Statistics</th></tr></thead>";
                        $totaltime = 1440 * $count + $totalminute;
                        $totaltime = round($totaltime);
                        if ($acinvert == 1) {
                            $offtime = $totaltime - $idletime;
                            if ($_SESSION['use_fuel_sensor'] != 0) {
                                $fuelsum = "<td colspan = '6' style=' text-align: center;'>Total Fuel Consumed = " . array_sum($fuelltr) . "ltr</td>";
                            } else {
                                $fuelsum = "";
                            }
                            $display .= "<tbody>
        <tr><td colspan = '6' style=' text-align: center;'>Total Gen Set ON Time = " . get_hh_mm($idletime * 60) . " Hours</td></tr>
        <tr><td colspan = '6' style=' text-align: center;'>Total Gen Set OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>
        <tr>" . $fuelsum . "</tr></tbody></table>";
                        } else {
                            $offtime = $totaltime - $runningtime;
                            $display .= "<tbody>
        <tr><td colspan = '6' style='text-align: center;'>Total Gen Set ON Time = " . get_hh_mm($runningtime * 60) . " Hours</td></tr>
        <tr><td colspan = '6' style='text-align: center;'>Total Gen Set OFF Time = " . get_hh_mm($offtime * 60) . " Hours</td></tr>
        <tr>" . $fuelsum . "</tr></tbody></table>";
                        }
                        return $display;
                    }

//gensert excel report -details end
                    function create_extraexcel_from_report($datarows, $extraid, $extraval) {
                        $i = 1;
                        $runningtime = 0;
                        $idletime = 0;
                        $lastdate = NULL;
                        $totalminute = 0;
                        $display = '';
                        if (isset($datarows)) {
                            $start = 0;
                            foreach ($datarows as $change) {
                                $start++;
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
                                    if ($start != 1) {
                                        $display .= "<tr><td colspan = '6'></td></tr>";
                                    }
                                    $display .= "
                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                        <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                        <td style='width:150px;height:auto; text-align: center;'>Start Location</td>
                        <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                        <td style='width:150px;height:auto; text-align: center;'>End Location</td>
                        <td style='width:50px;height:auto; text-align: center;'>$extraval Status</td>
                        <td style='width:150px;height:auto; text-align: center;'>Duration[Hours:Minutes]</td>
                    </tr>";
                                    $display .= "<tr style='background-color:#D8D5D6;'><th align='center' colspan = '6'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                                    $i++;
                                }
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                if (!isset($change->startcgeolat) || !isset($change->startcgeolong)) {
                                    $change->startlocation = 'Unable to fetch Location';
                                    $change->endlocation = 'Unable to fetch Location';
                                } else {
                                    $change->startlocation = location($change->startcgeolat, $change->startcgeolong, $change->uselocation);
                                    $change->endlocation = location($change->endcgeolat, $change->endcgeolong, $change->uselocation);
                                }
                                $start_time_disp = date('h:i A', strtotime($change->starttime));
                                $end_time_disp = date('h:i A', strtotime($change->endtime));
                                $display .= "<tr><td style='width:50px;height:auto; text-align: center;'>$start_time_disp</td><td>$change->startlocation</td><td style='width:50px;height:auto; text-align: center;'>$end_time_disp</td><td>$change->endlocation</td>";
                                $category_array = Array();
                                $digital = Array();
                                $category = (int) $change->digitalio;
                                $binarycategory = sprintf("%08s", DecBin($category));
                                for ($shifter = 1; $shifter <= 127; $shifter = $shifter << 1) {
                                    $binaryshifter = sprintf("%08s", DecBin($shifter));
                                    if ($category & $shifter) {
                                        $category_array[] = $shifter;
                                    }
                                }
                                if (in_array($extraid, $category_array)) {
                                    $display .= '<td>On</td>';
                                    $runningtime += $change->duration;
                                } else {
                                    $display .= '<td>OFF</td>';
                                    $idletime += $change->duration;
                                }
                                $startT = date('H:i', strtotime($change->duration . ' minutes'));
                                $hours = floor($change->duration / 60); //round down to nearest minute.
                                $minutes = $change->duration % 60;
                                if ($hours <= 9) {
                                    $hour = '0' . $hours;
                                } else {
                                    $hour = $hours;
                                }
                                if ($minutes <= 9) {
                                    $minute = '0' . $minutes;
                                } else {
                                    $minute = $minutes;
                                }
                                $display .= "<td style='width:100px;height:auto; text-align: center;'>$hour : $minute</td>";
                                $display .= '</tr>';
                            }
                        }
                        $display .= '</tbody>';
                        $display .= "</table><hr style='margin-top:20px;'>";
                        $display .="<table align = 'center' style='text-align:center;'>
           <thead><tr style='background-color:#CCCCCC;'><th colspan='6'>Statistics</th></tr></thead>";
                        $totaltime = 1440 * $count + $totalminute;
                        $totaltime = round($totaltime);
                        if ($acinvert == 1) {
                            $offtime = $totaltime - $idletime;
                            $display .= "<tbody>
        <tr><td colspan = '6' style=' text-align: center;'>Total $extraval ON Time = " . m2h($idletime) . " HH:MM</td></tr>
        <tr><td colspan = '6' style=' text-align: center;'>Total $extraval OFF Time = " . m2h($offtime) . " HH:MM</td></tr></tbody></table>";
                        } else {
                            $offtime = $totaltime - $runningtime;
                            $display .= "<tbody>
        <tr><td colspan = '6' style='text-align: center;'>Total $extraval ON Time = " . m2h($runningtime) . " HH:MM</td></tr>
        <tr><td colspan = '6' style='text-align: center;'>Total $extraval OFF Time = " . m2h($offtime) . " HH:MM</td></tr></tbody></table>";
                        }
                        return $display;
                    }

                    function getvehiclesbygroup($groupid) {
                        $VehicleManager = new VehicleManager($_SESSION['customerno']);
                        $vehicles = $VehicleManager->get_groups_vehicles($groupid);
                        return $vehicles;
                    }

                    function getvehiclesbygroup_ecode() {
                        $VehicleManager = new VehicleManager($_SESSION['customerno']);
                        $vehicles = $VehicleManager->get_groups_vehicles_ecode();
                        return $vehicles;
                    }

                    function get_all_vehiclesbyheirarchy() {
                        $VehicleManager = new VehicleManager($_SESSION['customerno']);
                        $vehicles = $VehicleManager->get_all_vehiclesbyheirarchy();
                        return $vehicles;
                    }

                    function GetDailyReport_Data($location, $days, $vehicleid = NULL) {
                        $path = "sqlite:$location";
                        $db = new PDO($path);
                        $REPORT = array();
                        if (isset($days)) {
                            foreach ($days as $day) {
                                if ($_SESSION['groupid'] != 0) {
                                    $vehiclesbygroup = getvehiclesbygroup($_SESSION['groupid']);
                                    if (isset($vehiclesbygroup)) {
                                        foreach ($vehiclesbygroup as $vehicle) {
                                            $sqlday = date("dmy", strtotime($day));
                                            $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid";
                                            $result = $db->query($query);
                                            if (isset($result) && $result != "") {
                                                foreach ($result as $row) {
                                                    $Datacap = new VODatacap();
                                                    $Datacap->date = strtotime($day);
                                                    $Datacap->uid = $row['uid'];
                                                    $Datacap->tamper = $row['tamper'];
                                                    $Datacap->overspeed = $row['overspeed'];
                                                    $Datacap->totaldistance = $row['totaldistance'];
                                                    $Datacap->fenceconflict = $row['fenceconflict'];
                                                    $Datacap->idletime = $row['idletime'];
                                                    $Datacap->genset = $row['genset'];
                                                    $Datacap->runningtime = $row['runningtime'];
                                                    $Datacap->vehicleid = $row['vehicleid'];
                                                    $Datacap->dev_lat = $row['dev_lat'];
                                                    $Datacap->dev_long = $row['dev_long'];
                                                    $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                                    $Datacap->average = getAverage($row['vehicleid']);
                                                    $REPORT[] = $Datacap;
                                                }
                                            }
                                        }
                                    }
                                } else if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
                                    $vehiclesbygroup = get_all_vehiclesbyheirarchy();
                                    if (isset($vehiclesbygroup)) {
                                        foreach ($vehiclesbygroup as $vehicle) {
                                            $sqlday = date("dmy", strtotime($day));
                                            $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid";
                                            $result = $db->query($query);
                                            if (isset($result) && $result != "") {
                                                foreach ($result as $row) {
                                                    $Datacap = new VODatacap();
                                                    $Datacap->date = strtotime($day);
                                                    $Datacap->uid = $row['uid'];
                                                    $Datacap->tamper = $row['tamper'];
                                                    $Datacap->overspeed = $row['overspeed'];
                                                    $Datacap->totaldistance = $row['totaldistance'];
                                                    $Datacap->fenceconflict = $row['fenceconflict'];
                                                    $Datacap->idletime = $row['idletime'];
                                                    $Datacap->genset = $row['genset'];
                                                    $Datacap->runningtime = $row['runningtime'];
                                                    $Datacap->vehicleid = $row['vehicleid'];
                                                    $Datacap->dev_lat = $row['dev_lat'];
                                                    $Datacap->dev_long = $row['dev_long'];
                                                    $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                                    $Datacap->average = getAverage($row['vehicleid']);
                                                    $REPORT[] = $Datacap;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $sqlday = date("dmy", strtotime($day));
                                    $query = "SELECT * from A$sqlday";
                                    if ($vehicleid != NULL) {
                                        $query .= " where vehicleid=$vehicleid";
                                    }
                                    $result = $db->query($query);
                                    if (isset($result) && $result != "") {
                                        foreach ($result as $row) {
                                            $Datacap = new VODatacap();
                                            $Datacap->date = strtotime($day);
                                            $Datacap->uid = $row['uid'];
                                            $Datacap->tamper = $row['tamper'];
                                            $Datacap->overspeed = $row['overspeed'];
                                            $Datacap->totaldistance = $row['totaldistance'];
                                            $Datacap->fenceconflict = $row['fenceconflict'];
                                            $Datacap->idletime = $row['idletime'];
                                            $Datacap->genset = $row['genset'];
                                            $Datacap->runningtime = $row['runningtime'];
                                            $Datacap->vehicleid = $row['vehicleid'];
                                            $Datacap->dev_lat = $row['dev_lat'];
                                            $Datacap->dev_long = $row['dev_long'];
                                            $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                            $Datacap->average = getAverage($row['vehicleid']);
                                            $REPORT[] = $Datacap;
                                        }
                                    }
                                }
                            }
                        }
                        return $REPORT;
                    }

                    /*
                      function get_daily_informatics($location,$days)
                      {
                      $path = "sqlite:$location";
                      $db = new PDO($path);
                      $REPORT = array();
                      if(isset($days))
                      {
                      foreach ($days as $day)
                      {
                      if($_SESSION['groupid'] != 0){
                      $vehiclesbygroup = getvehiclesbygroup($_SESSION['groupid']);
                      if(isset($vehiclesbygroup))
                      {
                      foreach ($vehiclesbygroup as $vehicle)
                      {
                      $sqlday = date("dmy",strtotime($day));
                      $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                      $result = $db->query($query);
                      if(isset($result) && $result!="")
                      {
                      foreach ($result as $row)
                      {
                      $Datacap = new VODatacap();
                      $Datacap->date = strtotime($day);
                      $Datacap->info_date = $day;
                      $Datacap->uid = $row['uid'];
                      $Datacap->tamper = $row['tamper'];
                      $Datacap->overspeed = $row['overspeed'];
                      $Datacap->totaldistance = $row['totaldistance'];
                      $Datacap->fenceconflict = $row['fenceconflict'];
                      $Datacap->idletime = $row['idletime'];
                      $Datacap->genset = $row['genset'];
                      $Datacap->runningtime = $row['runningtime'];
                      $Datacap->vehicleid = $row['vehicleid'];
                      //$Datacap->dev_lat = $row['dev_lat'];
                      //$Datacap->dev_long = $row['dev_long'];
                      //$Datacap->location=get_location($row['dev_lat'],$row['dev_long']);
                      //$Datacap->average = getAverage($row['vehicleid']);
                      $REPORT[] = $Datacap;
                      }
                      }
                      }
                      }
                      }
                      else if($_SESSION['use_maintenance']=='1' && $_SESSION['use_hierarchy'] == '1'){
                      $vehiclesbygroup = get_all_vehiclesbyheirarchy();
                      if(isset($vehiclesbygroup))
                      {
                      foreach ($vehiclesbygroup as $vehicle)
                      {
                      $sqlday = date("dmy",strtotime($day));
                      $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                      $result = $db->query($query);
                      if(isset($result) && $result!="")
                      {
                      foreach ($result as $row)
                      {
                      $Datacap = new VODatacap();
                      $Datacap->date = strtotime($day);
                      $Datacap->info_date = $day;
                      $Datacap->uid = $row['uid'];
                      $Datacap->tamper = $row['tamper'];
                      $Datacap->overspeed = $row['overspeed'];
                      $Datacap->totaldistance = $row['totaldistance'];
                      $Datacap->fenceconflict = $row['fenceconflict'];
                      $Datacap->idletime = $row['idletime'];
                      $Datacap->genset = $row['genset'];
                      $Datacap->runningtime = $row['runningtime'];
                      $Datacap->vehicleid = $row['vehicleid'];
                      //$Datacap->dev_lat = $row['dev_lat'];
                      //$Datacap->dev_long = $row['dev_long'];
                      //$Datacap->location=get_location($row['dev_lat'],$row['dev_long']);
                      //$Datacap->average = getAverage($row['vehicleid']);
                      $REPORT[] = $Datacap;
                      }
                      }
                      }
                      }
                      }
                      else if(isset($_SESSION['ecodeid']))
                      {
                      $vehiclesbygroup = getvehiclesbygroup_ecode();
                      if(isset($vehiclesbygroup))
                      {
                      foreach ($vehiclesbygroup as $vehicle)
                      {
                      $sqlday = date("dmy",strtotime($day));
                      $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                      $result = $db->query($query);
                      if(isset($result) && $result!="")
                      {
                      foreach ($result as $row)
                      {
                      $Datacap = new VODatacap();
                      $Datacap->date = strtotime($day);
                      $Datacap->info_date = $day;
                      $Datacap->uid = $row['uid'];
                      $Datacap->tamper = $row['tamper'];
                      $Datacap->overspeed = $row['overspeed'];
                      $Datacap->totaldistance = $row['totaldistance'];
                      $Datacap->fenceconflict = $row['fenceconflict'];
                      $Datacap->idletime = $row['idletime'];
                      $Datacap->genset = $row['genset'];
                      $Datacap->runningtime = $row['runningtime'];
                      $Datacap->vehicleid = $row['vehicleid'];
                      //$Datacap->dev_lat = $row['dev_lat'];
                      //$Datacap->dev_long = $row['dev_long'];
                      //$Datacap->location=get_location($row['dev_lat'],$row['dev_long']);
                      //$Datacap->average = getAverage($row['vehicleid']);
                      $REPORT[] = $Datacap;
                      }
                      }
                      }
                      }
                      }
                      else {
                      $sqlday = date("dmy",strtotime($day));
                      $query = "SELECT * from A$sqlday order by vehicleid ASC";
                      $result = $db->query($query);
                      if(isset($result) && $result!="")
                      {
                      foreach ($result as $row)
                      {
                      $Datacap = new VODatacap();
                      $Datacap->date = strtotime($day);
                      $Datacap->info_date = $day;
                      $Datacap->uid = $row['uid'];
                      $Datacap->tamper = isset($row['tamper']) ? $row['tamper'] : '';
                      $Datacap->overspeed = $row['overspeed'];
                      $Datacap->totaldistance = $row['totaldistance'];
                      $Datacap->fenceconflict = $row['fenceconflict'];
                      $Datacap->idletime = $row['idletime'];
                      $Datacap->genset = $row['genset'];
                      $Datacap->runningtime = $row['runningtime'];
                      $Datacap->vehicleid = $row['vehicleid'];
                      //$Datacap->dev_lat = $row['dev_lat'];
                      //$Datacap->dev_long = $row['dev_long'];
                      //$Datacap->location=get_location($row['dev_lat'],$row['dev_long']);
                      //$Datacap->average = getAverage($row['vehicleid']);
                      $REPORT[] = $Datacap;
                      }
                      }
                      }
                      }
                      }
                      return $REPORT;
                      } */

                    function GetDailyReport_Data_All($location, $days) {
                        $path = "sqlite:$location";
                        $db = new PDO($path);
                        $REPORT = array();
                        if (isset($days)) {
                            foreach ($days as $day) {
                                if ($_SESSION['groupid'] != 0) {
                                    $vehiclesbygroup = getvehiclesbygroup($_SESSION['groupid']);
                                    if (isset($vehiclesbygroup)) {
                                        foreach ($vehiclesbygroup as $vehicle) {
                                            $sqlday = date("dmy", strtotime($day));
                                            $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                                            $result = $db->query($query);
                                            if (isset($result) && $result != "") {
                                                foreach ($result as $row) {
                                                    $Datacap = new VODatacap();
                                                    $Datacap->date = strtotime($day);
                                                    $Datacap->info_date = $day;
                                                    $Datacap->uid = $row['uid'];
                                                    $Datacap->tamper = retval_issetor($row['tamper']);
                                                    $Datacap->overspeed = $row['overspeed'];
                                                    $Datacap->totaldistance = $row['totaldistance'];
                                                    $Datacap->fenceconflict = $row['fenceconflict'];
                                                    $Datacap->idletime = $row['idletime'];
                                                    $Datacap->genset = $row['genset'];
                                                    $Datacap->runningtime = $row['runningtime'];
                                                    $Datacap->vehicleid = $row['vehicleid'];
                                                    $Datacap->dev_lat = $row['dev_lat'];
                                                    $Datacap->dev_long = $row['dev_long'];
                                                    $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                                    $Datacap->average = getAverage($row['vehicleid']);
                                                    $REPORT[] = $Datacap;
                                                }
                                            }
                                        }
                                    }
                                } else if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
                                    $vehiclesbygroup = get_all_vehiclesbyheirarchy();
                                    if (isset($vehiclesbygroup)) {
                                        foreach ($vehiclesbygroup as $vehicle) {
                                            $sqlday = date("dmy", strtotime($day));
                                            $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                                            $result = $db->query($query);
                                            if (isset($result) && $result != "") {
                                                foreach ($result as $row) {
                                                    $Datacap = new VODatacap();
                                                    $Datacap->date = strtotime($day);
                                                    $Datacap->info_date = $day;
                                                    $Datacap->uid = $row['uid'];
                                                    $Datacap->tamper = $row['tamper'];
                                                    $Datacap->overspeed = $row['overspeed'];
                                                    $Datacap->totaldistance = $row['totaldistance'];
                                                    $Datacap->fenceconflict = $row['fenceconflict'];
                                                    $Datacap->idletime = $row['idletime'];
                                                    $Datacap->genset = $row['genset'];
                                                    $Datacap->runningtime = $row['runningtime'];
                                                    $Datacap->vehicleid = $row['vehicleid'];
                                                    $Datacap->dev_lat = $row['dev_lat'];
                                                    $Datacap->dev_long = $row['dev_long'];
                                                    $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                                    $Datacap->average = getAverage($row['vehicleid']);
                                                    $REPORT[] = $Datacap;
                                                }
                                            }
                                        }
                                    }
                                } else if (isset($_SESSION['ecodeid'])) {
                                    $vehiclesbygroup = getvehiclesbygroup_ecode();
                                    if (isset($vehiclesbygroup)) {
                                        foreach ($vehiclesbygroup as $vehicle) {
                                            $sqlday = date("dmy", strtotime($day));
                                            $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                                            $result = $db->query($query);
                                            if (isset($result) && $result != "") {
                                                foreach ($result as $row) {
                                                    $Datacap = new VODatacap();
                                                    $Datacap->date = strtotime($day);
                                                    $Datacap->info_date = $day;
                                                    $Datacap->uid = $row['uid'];
                                                    $Datacap->tamper = $row['tamper'];
                                                    $Datacap->overspeed = $row['overspeed'];
                                                    $Datacap->totaldistance = $row['totaldistance'];
                                                    $Datacap->fenceconflict = $row['fenceconflict'];
                                                    $Datacap->idletime = $row['idletime'];
                                                    $Datacap->genset = $row['genset'];
                                                    $Datacap->runningtime = $row['runningtime'];
                                                    $Datacap->vehicleid = $row['vehicleid'];
                                                    $Datacap->dev_lat = $row['dev_lat'];
                                                    $Datacap->dev_long = $row['dev_long'];
                                                    $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                                    $Datacap->average = getAverage($row['vehicleid']);
                                                    $REPORT[] = $Datacap;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $sqlday = date("dmy", strtotime($day));
                                    $query = "SELECT * from A$sqlday order by vehicleid ASC";
                                    $result = $db->query($query);
                                    if (isset($result) && $result != "") {
                                        foreach ($result as $row) {
                                            $Datacap = new VODatacap();
                                            $Datacap->date = strtotime($day);
                                            $Datacap->info_date = $day;
                                            $Datacap->uid = $row['uid'];
                                            $Datacap->tamper = isset($row['tamper']) ? $row['tamper'] : '';
                                            $Datacap->overspeed = $row['overspeed'];
                                            $Datacap->totaldistance = $row['totaldistance'];
                                            $Datacap->fenceconflict = $row['fenceconflict'];
                                            $Datacap->idletime = $row['idletime'];
                                            $Datacap->genset = $row['genset'];
                                            $Datacap->runningtime = $row['runningtime'];
                                            $Datacap->vehicleid = $row['vehicleid'];
                                            $Datacap->dev_lat = $row['dev_lat'];
                                            $Datacap->dev_long = $row['dev_long'];
                                            $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                            $Datacap->average = getAverage($row['vehicleid']);
                                            $REPORT[] = $Datacap;
                                        }
                                    }
                                }
                            }
                        }
                        return $REPORT;
                    }

                    function GetDailyReport_Data_All_PDF($location, $days, $customerno, $usemaintainance, $usehierarchy, $groupid) {
                        $path = "sqlite:$location";
                        $db = new PDO($path);
                        $REPORT = array();
                        if (isset($days)) {
                            foreach ($days as $day) {
                                if ($groupid != 0) {
                                    $vehiclesbygroup = getvehiclesbygroup($groupid);
                                    if (isset($vehiclesbygroup)) {
                                        foreach ($vehiclesbygroup as $vehicle) {
                                            $sqlday = date("dmy", strtotime($day));
                                            $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                                            $result = $db->query($query);
                                            if (isset($result) && $result != "") {
                                                foreach ($result as $row) {
                                                    $Datacap = new VODatacap();
                                                    $Datacap->date = strtotime($day);
                                                    $Datacap->info_date = $day;
                                                    $Datacap->uid = $row['uid'];
                                                    $Datacap->tamper = retval_issetor($row['tamper']);
                                                    $Datacap->overspeed = $row['overspeed'];
                                                    $Datacap->totaldistance = $row['totaldistance'];
                                                    $Datacap->fenceconflict = $row['fenceconflict'];
                                                    $Datacap->idletime = $row['idletime'];
                                                    $Datacap->genset = $row['genset'];
                                                    $Datacap->runningtime = $row['runningtime'];
                                                    $Datacap->vehicleid = $row['vehicleid'];
                                                    $Datacap->dev_lat = $row['dev_lat'];
                                                    $Datacap->dev_long = $row['dev_long'];
                                                    $Datacap->location = get_location_pdf($row['dev_lat'], $row['dev_long'], $customerno);
                                                    $Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                                                    $REPORT[] = $Datacap;
                                                }
                                            }
                                        }
                                    }
                                } else if ($usemaintainance == '1' && $usehierarchy == '1') {
                                    $vehiclesbygroup = get_all_vehiclesbyheirarchy();
                                    if (isset($vehiclesbygroup)) {
                                        foreach ($vehiclesbygroup as $vehicle) {
                                            $sqlday = date("dmy", strtotime($day));
                                            $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                                            $result = $db->query($query);
                                            if (isset($result) && $result != "") {
                                                foreach ($result as $row) {
                                                    $Datacap = new VODatacap();
                                                    $Datacap->date = strtotime($day);
                                                    $Datacap->info_date = $day;
                                                    $Datacap->uid = $row['uid'];
                                                    $Datacap->tamper = $row['tamper'];
                                                    $Datacap->overspeed = $row['overspeed'];
                                                    $Datacap->totaldistance = $row['totaldistance'];
                                                    $Datacap->fenceconflict = $row['fenceconflict'];
                                                    $Datacap->idletime = $row['idletime'];
                                                    $Datacap->genset = $row['genset'];
                                                    $Datacap->runningtime = $row['runningtime'];
                                                    $Datacap->vehicleid = $row['vehicleid'];
                                                    $Datacap->dev_lat = $row['dev_lat'];
                                                    $Datacap->dev_long = $row['dev_long'];
                                                    $Datacap->location = get_location_pdf($row['dev_lat'], $row['dev_long'], $customerno);
                                                    $Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                                                    $REPORT[] = $Datacap;
                                                }
                                            }
                                        }
                                    }
                                } else if (isset($_SESSION['ecodeid'])) {
                                    $vehiclesbygroup = getvehiclesbygroup_ecode();
                                    if (isset($vehiclesbygroup)) {
                                        foreach ($vehiclesbygroup as $vehicle) {
                                            $sqlday = date("dmy", strtotime($day));
                                            $query = "SELECT * from A$sqlday where vehicleid=$vehicle->vehicleid order by vehicleid ASC";
                                            $result = $db->query($query);
                                            if (isset($result) && $result != "") {
                                                foreach ($result as $row) {
                                                    $Datacap = new VODatacap();
                                                    $Datacap->date = strtotime($day);
                                                    $Datacap->info_date = $day;
                                                    $Datacap->uid = $row['uid'];
                                                    $Datacap->tamper = $row['tamper'];
                                                    $Datacap->overspeed = $row['overspeed'];
                                                    $Datacap->totaldistance = $row['totaldistance'];
                                                    $Datacap->fenceconflict = $row['fenceconflict'];
                                                    $Datacap->idletime = $row['idletime'];
                                                    $Datacap->genset = $row['genset'];
                                                    $Datacap->runningtime = $row['runningtime'];
                                                    $Datacap->vehicleid = $row['vehicleid'];
                                                    $Datacap->dev_lat = $row['dev_lat'];
                                                    $Datacap->dev_long = $row['dev_long'];
                                                    $Datacap->location = get_location_pdf($row['dev_lat'], $row['dev_long'], $customerno);
                                                    $Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                                                    $REPORT[] = $Datacap;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $sqlday = date("dmy", strtotime($day));
                                    $query = "SELECT * from A$sqlday order by vehicleid ASC";
                                    $result = $db->query($query);
                                    if (isset($result) && $result != "") {
                                        foreach ($result as $row) {
                                            $Datacap = new VODatacap();
                                            $Datacap->date = strtotime($day);
                                            $Datacap->info_date = $day;
                                            $Datacap->uid = $row['uid'];
                                            $Datacap->tamper = retval_issetor($row['tamper']);
                                            $Datacap->overspeed = $row['overspeed'];
                                            $Datacap->totaldistance = $row['totaldistance'];
                                            $Datacap->fenceconflict = $row['fenceconflict'];
                                            $Datacap->idletime = $row['idletime'];
                                            $Datacap->genset = $row['genset'];
                                            $Datacap->runningtime = $row['runningtime'];
                                            $Datacap->vehicleid = $row['vehicleid'];
                                            $Datacap->dev_lat = $row['dev_lat'];
                                            $Datacap->dev_long = $row['dev_long'];
                                            $Datacap->location = get_location_pdf($row['dev_lat'], $row['dev_long'], $customerno);
                                            $Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                                            $REPORT[] = $Datacap;
                                        }
                                    }
                                }
                            }
                        }
                        return $REPORT;
                    }

                    function GetDailyReport_Data_ByID($location, $days, $vehicleid) {
                        $path = "sqlite:$location";
                        $db = new PDO($path);
                        $REPORT = array();
                        if (isset($days)) {
                            foreach ($days as $day) {
                                if ($_SESSION['groupid'] != 0) {
                                    $sqlday = date("dmy", strtotime($day));
                                    $query = "SELECT * from A$sqlday where vehicleid=$vehicleid";
                                    $result = $db->query($query);
                                    if (isset($result) && $result != "") {
                                        foreach ($result as $row) {
                                            $Datacap = new VODatacap();
                                            $Datacap->date = strtotime($day);
                                            $Datacap->uid = $row['uid'];
                                            $Datacap->tamper = $row['tamper'];
                                            $Datacap->overspeed = $row['overspeed'];
                                            $Datacap->totaldistance = $row['totaldistance'];
                                            $Datacap->fenceconflict = $row['fenceconflict'];
                                            $Datacap->idletime = $row['idletime'];
                                            $Datacap->genset = $row['genset'];
                                            $Datacap->runningtime = $row['runningtime'];
                                            $Datacap->vehicleid = $row['vehicleid'];
                                            $Datacap->dev_lat = $row['dev_lat'];
                                            $Datacap->dev_long = $row['dev_long'];
                                            $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                            $Datacap->average = getAverage($row['vehicleid']);
                                            $REPORT[] = $Datacap;
                                        }
                                    }
                                } else if ($_SESSION['use_maintenance'] == '1' && $_SESSION['use_hierarchy'] == '1') {
                                    $sqlday = date("dmy", strtotime($day));
                                    $query = "SELECT * from A$sqlday where vehicleid=$vehicleid";
                                    $result = $db->query($query);
                                    if (isset($result) && $result != "") {
                                        foreach ($result as $row) {
                                            $Datacap = new VODatacap();
                                            $Datacap->date = strtotime($day);
                                            $Datacap->uid = $row['uid'];
                                            $Datacap->tamper = $row['tamper'];
                                            $Datacap->overspeed = $row['overspeed'];
                                            $Datacap->totaldistance = $row['totaldistance'];
                                            $Datacap->fenceconflict = $row['fenceconflict'];
                                            $Datacap->idletime = $row['idletime'];
                                            $Datacap->genset = $row['genset'];
                                            $Datacap->runningtime = $row['runningtime'];
                                            $Datacap->vehicleid = $row['vehicleid'];
                                            $Datacap->dev_lat = $row['dev_lat'];
                                            $Datacap->dev_long = $row['dev_long'];
                                            $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                            $Datacap->average = getAverage($row['vehicleid']);
                                            $REPORT[] = $Datacap;
                                        }
                                    }
                                } else {
                                    $sqlday = date("dmy", strtotime($day));
                                    $query = "SELECT * from A$sqlday";
                                    if ($vehicleid != NULL) {
                                        $query .= " where vehicleid=$vehicleid";
                                    }
                                    $result = $db->query($query);
                                    if (isset($result) && $result != "") {
                                        foreach ($result as $row) {
                                            $Datacap = new VODatacap();
                                            $Datacap->date = strtotime($day);
                                            $Datacap->uid = $row['uid'];
                                            $Datacap->tamper = $row['tamper'];
                                            $Datacap->overspeed = $row['overspeed'];
                                            $Datacap->totaldistance = $row['totaldistance'];
                                            $Datacap->fenceconflict = $row['fenceconflict'];
                                            $Datacap->idletime = $row['idletime'];
                                            $Datacap->genset = $row['genset'];
                                            $Datacap->runningtime = $row['runningtime'];
                                            $Datacap->vehicleid = $row['vehicleid'];
                                            $Datacap->dev_lat = $row['dev_lat'];
                                            $Datacap->dev_long = $row['dev_long'];
                                            $Datacap->location = get_location($row['dev_lat'], $row['dev_long']);
                                            $Datacap->average = getAverage($row['vehicleid']);
                                            $REPORT[] = $Datacap;
                                        }
                                    }
                                }
                            }
                        }
                        return $REPORT;
                    }

                    function getAverage($vehicleid) {
                        $VehicleManager = new VehicleManager($_SESSION['customerno']);
                        $average = $VehicleManager->getAverage($vehicleid);
                        return $average;
                    }

                    function getAverage_pdf($vehicleid, $customerno) {
                        $VehicleManager = new VehicleManager($customerno);
                        $average = $VehicleManager->getAverage($vehicleid);
                        return $average;
                    }

                    function gettemp($rawtemp) {
                        if ($_SESSION['use_humidity'] == 1 && $_SESSION['switch_to'] == 3) {
                            $temp = round($rawtemp / 100);
                        } else {
                            $temp = round((($rawtemp - 1150) / 4.45), 1);
                        }
                        return $temp;
                    }

                    function getFuelRefill($vehicleid, $date) {
                        $vehiclemanager = new VehicleManager($_SESSION['customerno']);
                        $fuelrefill = $vehiclemanager->get_Fuel_Refill($vehicleid, $date);
                        return $fuelrefill;
                    }

                    function GetHourlyReport_Temp($location, $STdate, $SThour) {
                        $temp = 0;
                        $time = strtotime($SThour) + 3600;
                        $nexthr = date('H:i:s', $time);
                        $path = "sqlite:$location";
                        $db = new PDO($path);
                        $REPORT = array();
                        if ($SThour != '23:00:00') {
                            $query = "SELECT * from unithistory where DATETIME(lastupdated) BETWEEN '$STdate $SThour' AND '$STdate $nexthr' ORDER BY lastupdated ASC";
                        } else {
                            $query = "SELECT * from unithistory where DATETIME(lastupdated) BETWEEN '$STdate $SThour' AND '$STdate 23:59:59' ORDER BY lastupdated ASC";
                        }
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                if ($temp != 0 && abs($row['analog1'] - $temp) < 22.25) {
                                    $Datacap = new VODatacap();
                                    $Datacap->date = strtotime($day);
                                    $Datacap->analog1 = $row['analog1'];
                                    $Datacap->lastupdated = $row['lastupdated'];
                                    $REPORT[] = $Datacap;
                                    $temp = $row['analog1'];
                                } elseif ($temp == 0) {
                                    $Datacap = new VODatacap();
                                    $Datacap->date = strtotime($day);
                                    $Datacap->analog1 = $row['analog1'];
                                    $Datacap->lastupdated = $row['lastupdated'];
                                    $REPORT[] = $Datacap;
                                    $temp = $row['analog1'];
                                }
                            }
                        }
                        return $REPORT;
                    }

                    function GetDailyReport_Temp($location, $STdate, $EDdate) {
                        $temp = 0;
                        $time = strtotime($SThour) + 3600;
                        $path = "sqlite:$location";
                        $db = new PDO($path);
                        $REPORT = array();
                        $query = "SELECT * from unithistory where lastupdated BETWEEN '$STdate' AND '$EDdate' ORDER BY lastupdated ASC";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                if ($temp != 0 && abs($row['analog1'] - $temp) < 100) {
                                    $Datacap = new VODatacap();
                                    $Datacap->date = strtotime($day);
                                    $Datacap->analog1 = $row['analog1'];
                                    $Datacap->analog2 = $row['analog2'];
                                    $Datacap->analog3 = $row['analog3'];
                                    $Datacap->analog4 = $row['analog4'];
                                    $Datacap->lastupdated = $row['lastupdated'];
                                    $REPORT[] = $Datacap;
                                    $temp = $row['analog1'];
                                } elseif ($temp == 0) {
                                    $Datacap = new VODatacap();
                                    $Datacap->date = strtotime($day);
                                    $Datacap->analog1 = $row['analog1'];
                                    $Datacap->analog2 = $row['analog2'];
                                    $Datacap->analog3 = $row['analog3'];
                                    $Datacap->analog4 = $row['analog4'];
                                    $Datacap->lastupdated = $row['lastupdated'];
                                    $REPORT[] = $Datacap;
                                    $temp = $row['analog1'];
                                }
                            }
                        }
                        return $REPORT;
                    }

                    function GetDailyReport_Temp_Analog($location, $STdate, $EDdate, $analogtype) {
                        $temp = 0;
                        $time = strtotime($SThour) + 3600;
                        $path = "sqlite:$location";
                        $db = new PDO($path);
                        $REPORT = array();
                        $query = "SELECT * from unithistory where lastupdated BETWEEN '$STdate' AND '$EDdate' ORDER BY lastupdated ASC";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                if ($analogtype == 1) {
                                    if ($temp != 0 && abs($row['analog1'] - $temp) < 22.25) {
                                        $Datacap = new VODatacap();
                                        $Datacap->date = strtotime($day);
                                        $Datacap->analog1 = $row['analog1'];
                                        $Datacap->lastupdated = $row['lastupdated'];
                                        $REPORT[] = $Datacap;
                                        $temp = $row['analog1'];
                                    } elseif ($temp == 0) {
                                        $Datacap = new VODatacap();
                                        $Datacap->date = strtotime($day);
                                        $Datacap->analog1 = $row['analog1'];
                                        $Datacap->lastupdated = $row['lastupdated'];
                                        $REPORT[] = $Datacap;
                                        $temp = $row['analog1'];
                                    }
                                } elseif ($analogtype == 2) {
                                    if ($temp != 0 && abs($row['analog2'] - $temp) < 22.25) {
                                        $Datacap = new VODatacap();
                                        $Datacap->date = strtotime($day);
                                        $Datacap->analog1 = $row['analog2'];
                                        $Datacap->lastupdated = $row['lastupdated'];
                                        $REPORT[] = $Datacap;
                                        $temp = $row['analog2'];
                                    } elseif ($temp == 0) {
                                        $Datacap = new VODatacap();
                                        $Datacap->date = strtotime($day);
                                        $Datacap->analog1 = $row['analog2'];
                                        $Datacap->lastupdated = $row['lastupdated'];
                                        $REPORT[] = $Datacap;
                                        $temp = $row['analog2'];
                                    }
                                } elseif ($analogtype == 3) {
                                    if ($temp != 0 && abs($row['analog3'] - $temp) < 22.25) {
                                        $Datacap = new VODatacap();
                                        $Datacap->date = strtotime($day);
                                        $Datacap->analog1 = $row['analog3'];
                                        $Datacap->lastupdated = $row['lastupdated'];
                                        $REPORT[] = $Datacap;
                                        $temp = $row['analog3'];
                                    } elseif ($temp == 0) {
                                        $Datacap = new VODatacap();
                                        $Datacap->date = strtotime($day);
                                        $Datacap->analog1 = $row['analog3'];
                                        $Datacap->lastupdated = $row['lastupdated'];
                                        $REPORT[] = $Datacap;
                                        $temp = $row['analog3'];
                                    }
                                } elseif ($analogtype == 4) {
                                    if ($temp != 0 && abs($row['analog4'] - $temp) < 22.25) {
                                        $Datacap = new VODatacap();
                                        $Datacap->date = strtotime($day);
                                        $Datacap->analog1 = $row['analog4'];
                                        $Datacap->lastupdated = $row['lastupdated'];
                                        $REPORT[] = $Datacap;
                                        $temp = $row['analog4'];
                                    } elseif ($temp == 0) {
                                        $Datacap = new VODatacap();
                                        $Datacap->date = strtotime($day);
                                        $Datacap->analog1 = $row['analog4'];
                                        $Datacap->lastupdated = $row['lastupdated'];
                                        $REPORT[] = $Datacap;
                                        $temp = $row['analog4'];
                                    }
                                }
                            }
                        }
                        return $REPORT;
                    }

                    function m2h($mins) {
                        if ($mins < 0) {
                            $min = Abs($mins);
                        } else {
                            $min = $mins;
                        }
                        $H = Floor($min / 60);
                        $M = ($min - ($H * 60)) / 100;
                        $hours = $H + $M;
                        if ($mins < 0) {
                            $hours = $hours * (-1);
                        }
                        $expl = explode(".", $hours);
                        $H = $expl[0];
                        if (empty($expl[1])) {
                            $expl[1] = 00;
                        }
                        $M = $expl[1];
                        if (strlen($M) < 2) {
                            $M = $M . 0;
                        }
                        $hours = $H . ":" . $M;
                        return $hours;
                    }

                    function getalerthist($date, $type, $vehicleid, $checkpointid, $fenceid) {
                        include_once '../../lib/bo/ComQueueManager.php';
                        $cqm = new ComQueueManager($_SESSION['customerno']);
                        $currentdate = date("d-m-Y");
                        $i = 1;
                        $data = '';
                        if ($date == $currentdate) {
                            $queues = $cqm->getalerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $_SESSION['customerno']);
                            if (isset($queues)) {
                                foreach ($queues as $queue) {
                                    if ($queue->processed == 1 && $queue->comtype == 0) {
                                        $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>$queue->email</td><td>---</td></tr>";
                                    } else if ($queue->processed == 1 && $queue->comtype == 1) {
                                        $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$queue->phone</td></tr>";
                                    } else {
                                        $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>---</td></tr>";
                                    }
                                    $i++;
                                }
                            }
                        } else {
                            $dt = strtotime(date("Y-m-d", strtotime($date)));
                            $file = date("MY", $dt);
                            $location = "../../customer/" . $_SESSION['customerno'] . "/history/$file.sqlite";
                            if (file_exists($location)) {
                                $queues = getalerthist_sqlite($location, $date, $type, $vehicleid, $checkpointid, $fenceid);
                                if (isset($queues)) {
                                    foreach ($queues as $queue) {
                                        if ($queue->processed == 1) {
                                            $historys = getcomhist_sqlite($location, $queue->cqid);
                                            if (isset($historys)) {
                                                foreach ($historys as $history) {
                                                    if ($history->comtype == 0) {
                                                        $users = $cqm->getuserdetailss($history->userid);
                                                        $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>$users->email</td><td>---</td></tr>";
                                                        $i++;
                                                    } else if ($history->comtype == 1) {
                                                        $users = $cqm->getuserdetailss($history->userid);
                                                        $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$users->phone</td></tr>";
                                                        $i++;
                                                    }
                                                }
                                            }
                                        } else {
                                            $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>---</td></tr>";
                                            $i++;
                                        }
                                    }
                                }
                            }
                        }
                        if ($data == '') {
                            $data .= "<tr><td colspan='5' style='text-align:center;'>No Data Available</td></tr>";
                        }
                        $data .= '</body></table>';
                        echo $data;
                    }

// function for to covert to pdf for alert history
                    function get_pdf_alerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $vehicleno, $customerno, $alertmode, $switchto) {
                        $finalreport = '';
                        $title = 'Alert History Report';
                        if ($switchto == 3) {
                            $subTitle = array("Type : $alertmode", "Warehouse: $vehicleno", "Date: $date");
                        } else {
                            $subTitle = array("Type : $alertmode", "Vehicle No: $vehicleno", "Date: $date");
                        }
                        $finalreport = pdf_header($title, $subTitle);
                        $finalreport .="<table align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                    <tr style='background-color:#CCCCCC;'>
                        <td style='width:100px;height:auto;'></td>
                        <td style='width:100px;height:auto;'><b>Message</b></td>
                        <td style='width:150px;height:auto;'><b>Time</b></td>
                        <td style='width:150px;height:auto;'><b>Email Sent</b></td>
                        <td style='width:150px;height:auto;'><b>SMS Sent</b></td>
                    </tr>";
                        $data = create_pdf_alerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $customerno);
                        if ($data == '') {
                            $finalreport .= "<tr><td colspan='5' style='text-align:center;'>No Data Available</td></tr>";
                        } else {
                            $finalreport .= $data;
                        }
                        $finalreport .= "</table>";
                        echo $finalreport;
                    }

                    function create_pdf_alerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $customerno) {
                        include_once '../../lib/bo/ComQueueManager.php';
                        $cqm = new ComQueueManager($customerno);
                        $currentdate = date("d-m-Y");
                        $i = 1;
                        $data = '';
                        if ($date == $currentdate) {
                            $queues = $cqm->getalerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $customerno);
                            if (isset($queues)) {
                                foreach ($queues as $queue) {
                                    if ($queue->processed == 1 && $queue->comtype == 0) {
                                        $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>$queue->email</td><td>---</td></tr>";
                                    } else if ($queue->processed == 1 && $queue->comtype == 1) {
                                        $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$queue->phone</td></tr>";
                                    } else {
                                        $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>---</td></tr>";
                                    }
                                    $i++;
                                }
                            }
                        } else {
                            $dt = strtotime(date("Y-m-d", strtotime($date)));
                            $file = date("MY", $dt);
                            $location = "../../customer/" . $customerno . "/history/$file.sqlite";
                            if (file_exists($location)) {
                                $queues = getalerthist_sqlite($location, $date, $type, $vehicleid, $checkpointid, $fenceid);
                                if (isset($queues)) {
                                    foreach ($queues as $queue) {
                                        if ($queue->processed == 1) {
                                            $historys = getcomhist_sqlite($location, $queue->cqid);
                                            if (isset($historys)) {
                                                foreach ($historys as $history) {
                                                    if ($history->comtype == 0) {
                                                        $users = $cqm->getuserdetailss($history->userid);
                                                        $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>$users->email</td><td>---</td></tr>";
                                                        $i++;
                                                    } else if ($history->comtype == 1) {
                                                        $users = $cqm->getuserdetailss($history->userid);
                                                        $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$users->phone</td></tr>";
                                                        $i++;
                                                    }
                                                }
                                            }
                                        } else {
                                            $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>---</td></tr>";
                                            $i++;
                                        }
                                    }
                                }
                            }
                        }
                        return $data;
                    }

                    function getalertreportxls($date, $type, $vehicleid, $checkpointid, $fenceid, $vehicleno, $customerno, $alertmode, $switchto) {
                        $finalreport = '';
                        $title = 'Alert History Report';
                        if ($switchto == 3) {
                            $subTitle = array("Type : $alertmode", "Warehouse: $vehicleno", "Date: $date");
                        } else {
                            $subTitle = array("Type : $alertmode", "Vehicle No: $vehicleno", "Date: $date");
                        }
                        $finalreport = excel_header($title, $subTitle);
                        $finalreport .="<table align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                    <tr style='background-color:#CCCCCC;'>
                        <td style='width:100px;height:auto;'></td>
                        <td style='width:100px;height:auto;'><b>Message</b></td>
                        <td style='width:150px;height:auto;'><b>Time</b></td>
                        <td style='width:150px;height:auto;'><b>Email Sent</b></td>
                        <td style='width:150px;height:auto;'><b>SMS Sent</b></td>
                    </tr>";
                        $data = create_pdf_alerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $customerno);
                        if ($data == '') {
                            $finalereport .= "<tr><td colspan='5' style='text-align:center;'>No Data Available</td></tr>";
                        } else {
                            $finalreport .= $data;
                        }
                        $finalreport .= "</table>";
                        echo $finalreport;
                    }

                    function getalerthistTeam($date, $type, $vehicleid, $checkpointid, $fenceid, $customerno) {
                        include_once '../../lib/bo/ComQueueManager.php';
                        $cqm = new ComQueueManager($customerno);
                        $currentdate = date("d-m-Y");
                        //$currentdate = '16-01-2014';
                        if ($date == $currentdate) {
                            $data = '';
                            $queues = $cqm->getalerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $customerno);
                            include_once '../reports/pages/panels/alerthistrep_team.php';
                            $i = 1;
                            if (isset($queues)) {
                                foreach ($queues as $queue) {
                                    if ($queue->processed == 1 && $queue->comtype == 0) {
                                        $data .= "<tr style='background:#FFE0CC;'><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>$queue->email</td><td>---</td></tr>";
                                        $i++;
                                    } else if ($queue->processed == 1 && $queue->comtype == 1) {
                                        $data .= "<tr style='background:#FFE0CC;'><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$queue->phone</td></tr>";
                                        $i++;
                                    } else {
                                        $data .= "<tr style='background:#FFE0CC;'><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>---</td></tr>";
                                        $i++;
                                    }
                                }
                                $data .= '</body></table>';
                            } else {
                                $data .= "<tr style='background:#FFE0CC;'><td colspan='5'>No Data Available</td></tr>";
                                $data .= '</body></table>';
                            }
                        } else {
                            $data = '';
                            $dt = strtotime(date("Y-m-d", strtotime($date)));
                            $file = date("MY", $dt);
                            $location = "../../customer/$customerno/history/$file.sqlite";
                            if (file_exists($location)) {
                                $queues = getalerthist_sqlite_team($location, $date, $type, $vehicleid, $checkpointid, $fenceid);
                                include_once '../reports/pages/panels/alerthistrep_team.php';
                                $i = 1;
                                if (isset($queues)) {
                                    foreach ($queues as $queue) {
                                        if ($queue->processed == 1) {
                                            $historys = getcomhist_sqlite($location, $queue->cqid);
                                            if (isset($historys)) {
                                                foreach ($historys as $history) {
                                                    if ($history->comtype == 0) {
                                                        $users = $cqm->getuserdetailss($history->userid);
                                                        $data .= "<tr style='background:#FFE0CC;'><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>$users->email</td><td>---</td></tr>";
                                                        $i++;
                                                    } else if ($history->comtype == 1) {
                                                        $users = $cqm->getuserdetailss($history->userid);
                                                        $data .= "<tr style='background:#FFE0CC;'><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$users->phone</td></tr>";
                                                        $i++;
                                                    }
                                                }
                                            } else {
                                                $data .= "<tr style='background:#FFE0CC;'><td colspan='5'>No Data Available</td></tr>";
                                            }
                                        } else {
                                            $data .= "<tr style='background:#FFE0CC;'><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>---</td></tr>";
                                            $i++;
                                        }
                                    }
                                } else {
                                    $data .= "<tr style='background:#FFE0CC;'><td colspan='5'>No Data Available</td></tr>";
                                }
                                $data .= '</body></table>';
                            }
                        }
                        echo $data;
                    }

                    function getcomhist_sqlite($location, $cqid) {
                        $path = "sqlite:$location";
                        $db = new PDO($path);
                        $queues = array();
                        $query = "SELECT userid,comtype from comhistory where comqid = $cqid";
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new VODatacap();
                                $Datacap->userid = $row['userid'];
                                $Datacap->comtype = $row['comtype'];
                                $queues[] = $Datacap;
                            }
                            return $queues;
                        }
                        return null;
                    }

                    function getalerthist_sqlite($location, $date, $type, $vehicleid, $checkpointid, $fenceid) {
                        $newdate = date('Y-m-d', strtotime($date));
                        $path = "sqlite:$location";
                        $db = new PDO($path);
                        $queues = array();
                        switch ($type) {
                            case '-1' : {
                                    if ($vehicleid != '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    }
                                }
                                break;
                            case '2': {
                                    if ($vehicleid != '' && $checkpointid != '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND chkid = $checkpointid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else if ($vehicleid == '' && $checkpointid != '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND chkid = $checkpointid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else if ($vehicleid != '' && $checkpointid == '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    }
                                }
                                break;
                            case '3': {
                                    if ($vehicleid != '' && $fenceid != '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND fenceid = $fenceid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else if ($vehicleid == '' && $fenceid != '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND fenceid = $fenceid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else if ($vehicleid != '' && $fenceid == '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    }
                                }
                                break;
                            default : {
                                    if ($vehicleid != '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    }
                                }
                                break;
                        }
                        $query .= "ORDER BY  `comqueue`.`timeadded` ASC ";
                        //echo $query;
                        $result = $db->query($query);
                        if ($vehicleid == '' && $_SESSION['groupid'] != 0) {
                            $gm = new GroupManager($_SESSION['customerno']);
                            $groupedvehicles = $gm->getvehicleforgroup($_SESSION['groupid']);
                            if (isset($result) && $result != "") {
                                foreach ($result as $row) {
                                    if (in_array($row['vehicleid'], $groupedvehicles)) {
                                        $Datacap = new VODatacap();
                                        $Datacap->cqid = $row['cqid'];
                                        $Datacap->timeadded = date("g:i a", strtotime($row["timeadded"]));
                                        $Datacap->message = $row['message'];
                                        $Datacap->processed = $row['processed'];
                                        $queues[] = $Datacap;
                                    }
                                }
                                return $queues;
                            }
                            return null;
                        } else {
                            if (isset($result) && $result != "") {
                                foreach ($result as $row) {
                                    $Datacap = new VODatacap();
                                    $Datacap->cqid = $row['cqid'];
                                    $Datacap->timeadded = date("g:i a", strtotime($row["timeadded"]));
                                    $Datacap->message = $row['message'];
                                    $Datacap->processed = $row['processed'];
                                    $queues[] = $Datacap;
                                }
                                return $queues;
                            }
                            return null;
                        }
                    }

                    function getalerthist_sqlite_team($location, $date, $type, $vehicleid, $checkpointid, $fenceid) {
                        $newdate = date('Y-m-d', strtotime($date));
                        $path = "sqlite:$location";
                        $db = new PDO($path);
                        $queues = array();
                        switch ($type) {
                            case '-1' : {
                                    if ($vehicleid != '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    }
                                }
                                break;
                            case '2': {
                                    if ($vehicleid != '' && $checkpointid != '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND chkid = $checkpointid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else if ($vehicleid == '' && $checkpointid != '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND chkid = $checkpointid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else if ($vehicleid != '' && $checkpointid == '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    }
                                }
                                break;
                            case '3': {
                                    if ($vehicleid != '' && $fenceid != '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND fenceid = $fenceid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else if ($vehicleid == '' && $fenceid != '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND fenceid = $fenceid AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else if ($vehicleid != '' && $fenceid == '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    }
                                }
                                break;
                            default : {
                                    if ($vehicleid != '') {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where vehicleid = $vehicleid AND type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    } else {
                                        $query = "SELECT cqid,timeadded,message,processed FROM `comqueue` where type = $type AND DATETIME(timeadded) BETWEEN '$newdate 00:00:00' AND '$newdate 23:59:59'";
                                    }
                                }
                                break;
                        }
                        $query .= "ORDER BY  `comqueue`.`timeadded` ASC ";
                        //echo $query;
                        $result = $db->query($query);
                        if (isset($result) && $result != "") {
                            foreach ($result as $row) {
                                $Datacap = new VODatacap();
                                $Datacap->cqid = $row['cqid'];
                                $Datacap->timeadded = date("g:i a", strtotime($row["timeadded"]));
                                $Datacap->message = $row['message'];
                                $Datacap->processed = $row['processed'];
                                $queues[] = $Datacap;
                            }
                            return $queues;
                        }
                        return null;
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
                        }
                        return $address;
                    }

                    function gettemptabularreport_fassos_cron($STdate, $EDdate, $deviceid, $interval, $stime, $etime, $customerno) {
                        $_SESSION['temp_sensors'] = 4;
                        $_SESSION['use_humidity'] = 0;
                        $_SESSION['customerno'] = 177;
                        $_SESSION['switch_to'] = 3;
                        $_SESSION['use_ac_sensor'] = 0;
                        $totaldays = gendays($STdate, $EDdate);
                        $finalreport = '';
                        $days = array();
                        $graph_days = array();
                        $graph_days_ig = array();
                        $veh_temp_details = array();
                        $unit = getunitdetailspdf($customerno, $deviceid);
                        if (isset($totaldays)) {
                            foreach ($totaldays as $userdate) {
                                //Date Check Operations
                                $data = NULL;
                                $graph_data = null;
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
                                $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
                                if (file_exists($location)) {
                                    $location = "sqlite:" . $location;
                                    $return = gettempdata_fromsqlite($location, $deviceid, $interval, $f_STdate, $f_EDdate, true, $unit);
                                    $data = $return[0];
                                }
                                if ($data != NULL && count($data) > 1) {
                                    $days = array_merge($days, $data);
                                }
                            }
                        }
                        if ($days != NULL && count($days) > 0) {
                            if (isset($return['vehicleid'])) {
                                $veh_temp_details = getunitdetailsfromvehid($return['vehicleid'], $customerno);
                            }
                            $finalreport = create_temp_from_reportcron($days, $unit, $veh_temp_details);
                        }
                        return $finalreport;
                    }

                    function create_temp_from_reportcron($datarows, $unitdetails, $veh_temp_details = null) {
                        $i = 0;
                        $totalrow = 0;
                        $tr_abv_max = 0;
                        $tr2_abv_max = 0;
                        $tr3_abv_max = 0;
                        $tr4_abv_max = 0;

                        $temp1_bad_reading = 0;
                        $temp2_bad_reading = 0;
                        $temp3_bad_reading = 0;
                        $temp4_bad_reading = 0;

                        $totalminute = 0;
                        $lastdate = NULL;
                        $temp1_data = "";
                        $temp2_data = "";
                        $temp3_data = "";
                        $temp4_data = "";

                        $display = '';
                        $restemp1 = array(0);
                        $restemp2 = array(0);
                        $restemp3 = array(0);
                        $restemp4 = array(0);
                        $min_max_temp1 = get_min_max_temp(1, $veh_temp_details);
                        $min_max_temp2 = get_min_max_temp(2, $veh_temp_details);
                        $min_max_temp3 = get_min_max_temp(3, $veh_temp_details);
                        $min_max_temp4 = get_min_max_temp(4, $veh_temp_details);
                        $temp1counter = 0;
                        $temp2counter = 0;
                        $temp3counter = 0;
                        $temp4counter = 0;

                        $temp1_min = $temp1_max = $temp2_min = $temp2_max = $temp3_min = $temp3_max = $temp4_min = $temp4_max = '';
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
                                    $i++;
                                }
                                //Removing Date Details From DateTime
                                $change->starttime = substr($change->starttime, 11);
                                $change->endtime = substr($change->endtime, 11);
                                $starttime_disp = date('h:i A', strtotime($change->starttime));
                                // Temperature Sensor
                                $temp = 'Not Active';
                                $temp1 = 'Not Active';
                                $temp2 = 'Not Active';
                                $temp3 = 'Not Active';
                                $temp4 = 'Not Active';

                                $tdstring = '';
                                switch ($_SESSION['temp_sensors']) {
                                    case 4:
                                        $s4 = "analog" . $unitdetails->tempsen4;
                                        if ($unitdetails->tempsen4 != 0 && $change->$s4 != 0) {
                                            $temp4 = gettemp($change->$s4);
                                            /* minimum temp */
                                            $temp4_min = set_summary_min_temp2($temp4);
                                            /* maximum temp */
                                            $temp4_max = set_summary_max_temp2($temp4);
                                            if ($temp4 > $min_max_temp4['temp_max_limit'] || $temp4 < $min_max_temp4['temp_min_limit']) {
                                                $tr4_abv_max++;
                                            }
                                        } else {
                                            $temp4_bad_reading++;
                                        }


                                    case 3:
                                        $s3 = "analog" . $unitdetails->tempsen3;
                                        if ($unitdetails->tempsen3 != 0 && $change->$s3 != 0) {
                                            $temp3 = gettemp($change->$s3);
                                            /* minimum temp */
                                            $temp3_min = set_summary_min_temp2($temp3);
                                            /* maximum temp */
                                            $temp3_max = set_summary_max_temp2($temp3);
                                            if ($temp3 > $min_max_temp3['temp_max_limit'] || $temp3 < $min_max_temp3['temp_min_limit']) {
                                                $tr3_abv_max++;
                                            }
                                        } else {
                                            $temp3_bad_reading++;
                                        }



                                    case 2:
                                        $s2 = "analog" . $unitdetails->tempsen2;
                                        if ($unitdetails->tempsen2 != 0 && $change->$s2 != 0) {
                                            $temp2 = gettemp($change->$s2);
                                            /* minimum temp */
                                            $temp2_min = set_summary_min_temp2($temp2);
                                            /* maximum temp */
                                            $temp2_max = set_summary_max_temp2($temp2);
                                            /* above max */
                                            if ($temp2 > $min_max_temp2['temp_max_limit'] || $temp2 < $min_max_temp2['temp_min_limit']) {
                                                $tr2_abv_max++;
                                            }
                                        } else {
                                            $temp2_bad_reading++;
                                        }

                                    case 1:
                                        $s1 = "analog" . $unitdetails->tempsen1;
                                        if ($unitdetails->tempsen1 != 0 && $change->$s1 != 0) {
                                            $temp1 = gettemp($change->$s1);
                                            /* min temp */
                                            $temp1_min = set_summary_min_temp($temp1);
                                            /* maximum temp */
                                            $temp1_max = set_summary_max_temp($temp1);
                                            /* above max */
                                            if ($temp1 > $min_max_temp1['temp_max_limit'] || $temp1 < $min_max_temp1['temp_min_limit']) {
                                                $tr_abv_max++;
                                            }
                                        } else {
                                            $temp1_bad_reading++;
                                        }

                                        break;
                                }
                                $totalrow++;
                            }
                        }
                        $t1 = getName_ByType($unitdetails->n1);
                        if ($t1 == '') {
                            $t1 = 'Temperature 1';
                        }
                        $t2 = getName_ByType($unitdetails->n2);
                        if ($t2 == '') {
                            $t2 = 'Temperature 2';
                        }
                        $t3 = getName_ByType($unitdetails->n3);
                        if ($t3 == '') {
                            $t3 = 'Temperature 3';
                        }
                        $t4 = getName_ByType($unitdetails->n4);
                        if ($t4 == '') {
                            $t4 = 'Temperature 4';
                        }
                        if ($_SESSION['temp_sensors'] == 4) {
                            $temp1_data = array(
                                'title' => $t1,
                                'totalreading' => $totalrow,
                                'totalabvcount' => $tr_abv_max,
                                'unitno' => $unitdetails->unitno,
                                'vehicleno' => $unitdetails->vehicleno,
                                'badcount' => $temp1_bad_reading
                            );
                            $temp2_data = array(
                                'title' => $t2,
                                'totalreading' => $totalrow,
                                'totalabvcount' => $tr2_abv_max,
                                'unitno' => $unitdetails->unitno,
                                'vehicleno' => $unitdetails->vehicleno,
                                'badcount' => $temp2_bad_reading
                            );
                            $temp3_data = array(
                                'title' => $t3,
                                'totalreading' => $totalrow,
                                'totalabvcount' => $tr3_abv_max,
                                'unitno' => $unitdetails->unitno,
                                'vehicleno' => $unitdetails->vehicleno,
                                'badcount' => $temp3_bad_reading
                            );
                            $temp4_data = array(
                                'title' => $t4,
                                'totalreading' => $totalrow,
                                'totalabvcount' => $tr4_abv_max,
                                'unitno' => $unitdetails->unitno,
                                'vehicleno' => $unitdetails->vehicleno,
                                'badcount' => $temp4_bad_reading
                            );
                        }
                        $compliancedata = array($temp1_data, $temp2_data, $temp3_data, $temp4_data);
                        return $compliancedata;
                    }
                    ?>
