<?php
include_once '../../lib/system/utilities.php';
include_once '../../lib/bo/DeviceManager.php';
include_once '../../lib/bo/DriverManager.php';
include_once '../../lib/bo/DealerManager.php';
include_once '../../lib/bo/UnitManager.php';
include_once '../../lib/bo/VehicleManager.php';
include_once '../../lib/bo/GroupManager.php';
include_once '../../lib/bo/CheckpointManager.php';
include_once '../../lib/bo/GeofenceManager.php';
include_once '../../lib/bo/GeoCoder.php';
include_once '../../lib/comman_function/reports_func.php';
include_once '../../lib/bo/MaintananceManager.php';
include_once '../../lib/bo/PartManager.php';
include_once '../../lib/bo/TaskManager.php';
include_once '../../lib/model/TempConversion.php';
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}

function get_location($lat, $long) {
    $geo_location = "N/A";
    if ($lat != "" && $long != "") {
        $geo_obj = new GeoCoder($_SESSION['customerno']);
        $geo_location = $geo_obj->get_city_bylatlong($lat, $long);
    }
    return $geo_location;
}

function generate_fuelefficiency_graph($DATA) {
    $all_data = array();
    $chart_height = 200;
    foreach ($DATA as $report) {
        $date = date('Y-m-d', $report->date);
        $report->totaldistancetravelled = $report->totaldistance / 1000;
        $report->consumedfuel = ($report->average != 0) ? $report->totaldistancetravelled / $report->average : 0;
        $fuel_consumed = round($report->consumedfuel, 2);
        if (isset($all_data[$date])) {
            $all_data[$date] += $fuel_consumed;
        } else {
            $all_data[$date] = $fuel_consumed;
        }
    }
    $vehs = $total = '';
    foreach ($all_data as $date => $os) {
        $vehs .= "'$date', ";
        $total .= "$os, ";
        $chart_height += 30;
    }
    return array('vehs' => $vehs, 'data' => $total, 'height' => $chart_height);
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
    $geolocation = $GeoCoder_Obj->get_use_geolocation();
    return $geolocation;
}

function getdealers() {
    $dealermanager = new DealerManager($_SESSION['customerno']);
    $dealers = $dealermanager->get_all_dealers();
    return $dealers;
}

function get_dealers_by_type($type, $roleid, $heirarchy_id) {
    $dealermanager = new DealerManager($_SESSION['customerno']);
    $dealers = $dealermanager->get_dealers_by_type($type, $roleid, $heirarchy_id);
    return $dealers;
}

function fuelmaintenance() {
    $vehiclemgr = new VehicleManager($_SESSION['customerno']);
    $report = $vehiclemgr->getAllFuelRecords();
    return $report;
}

function getfilteredfuelmaintenance($vehicleno, $dealerid, $sdate, $edate, $sort_arr = null) {
    $vehiclemgr = new VehicleManager($_SESSION['customerno']);
    $report = $vehiclemgr->getAllFilterdFuelRecords($vehicleno, $dealerid, $sdate, $edate, $sort_arr);
    return $report;
}

function get_location_detail($lat, $long) {
    $customerno = $_SESSION['customerno'];
    $usegeolocation = get_usegeolocation($customerno);
    $address = null;
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

function getunitdetails($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getunitdetailsfromdeviceid($deviceid);
    return $unitno;
}

function getunitdetailsfromvehid($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
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
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $finalreport = create_html_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

function getgensetreport($STdate, $EDdate, $deviceid) {
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
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $finalreport = create_gensethtml_from_report($days, $acinvertval);
    }
    echo $finalreport;
}

function gettemptabularreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $customerno = $_SESSION['customerno'];
    $unit = getunitdetails($deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
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
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gettempdata_fromsqlite($location, $deviceid, $interval, $STdate, $EDdate);
            }
            if ($data != null && count($data) > 1) {
                $days = array_merge($days, $data);
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $finalreport = create_temphtml_from_report($days, $unit);
    }
    echo $finalreport;
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
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date('Y-m-d H:i:s');
        $formatdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
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
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $filesize = filesize($location);
                if ($filesize > 0) {
                    $location = "sqlite:" . $location;
                    $data = getgensetdata_fromsqlite($location, $deviceid);
                }
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date('Y-m-d H:i:s');
        $formatdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
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
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date('Y-m-d H:i:s');
        $formatdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
        $fromdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
        $todate = date(speedConstants::DEFAULT_DATE, strtotime($EDdate));
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

function getgensetreportpdfMultipleDays($customerno, $STdate, $EDdate, $deviceid, $vehicleno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date('Y-m-d H:i:s');
        $formatdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
        $fromdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
        $todate = date(speedConstants::DEFAULT_DATE, strtotime($EDdate));
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
        $finalreport .= "Vehicle No. $vehicleno
                                From :  $fromdate to : $todate";
        $finalreport .= "<hr />
                                <style type='text/css'>
                                    table, td { border: solid 1px  #999999; color:#000000; }
                                    hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
                                </style>
                                <table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                    <tbody>";
        $finalreport .= create_gensetpdf_for_multipledays($days, $acinvertval);
    }
    echo $finalreport;
}

function gettempreportpdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
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
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gettempdata_fromsqlite($location, $deviceid, $interval, $STdate, $EDdate);
            }
            if ($data != null && count($data) > 1) {
                $days = array_merge($days, $data);
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date('Y-m-d H:i:s');
        $formatdate = date(speedConstants::DEFAULT_DATETIME, strtotime($STdate));
        $fromdate = date(speedConstants::DEFAULT_DATETIME, strtotime($STdate));
        $todate = date(speedConstants::DEFAULT_DATETIME, strtotime($EDdate));
        $finalreport = '<div style="width:auto; height:30px;">
                                        <table style="width: auto; border:none;">
                                            <tr>
                                                <td style="width:430px; border:none;"><img style="width:50%; height: 50%;" src="../../images/elixiaspeed_logo.png" /></td>
                                                <td style="width:420px; border:none;">
                                                    <h3 style="text-transform:uppercase;">Temperature Sensor Report</h3><br />
                                                </td>
                                                <td style="width:230px;border:none;">
                                                    <img src="../../images/elixia_logo_75.png"  /></td>
                                                </tr>
                                            </table>
                                        </div>';
        $finalreport .= "Vehicle No. $vehicleno
                                        From :  $fromdate to : $todate";
        $finalreport .= "<hr /><br/><br/>
                                        <style type='text/css'>
                                            table, td { border: solid 1px  #999999; color:#000000; }
                                            hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
                                        </style>
                                        <table id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                            <tbody>";
        $finalreport .= create_temppdf_from_report($days, $unit);
    }
    echo $finalreport;
}

function gettempreportxls($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval, $stime, $etime) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $unit = getunitdetailspdf($customerno, $deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $STdate = date("Y-m-d", strtotime($STdate));
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
            $location = "../../customer/$customerno/unitno/$unit->unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = gettempdata_fromsqlite($location, $deviceid, $interval, $STdate, $EDdate);
            }
            if ($data != null && count($data) > 1) {
                $days = array_merge($days, $data);
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date('Y-m-d H:i:s');
        $formatdate = date(speedConstants::DEFAULT_DATETIME, strtotime($STdate));
        $fromdate = date(speedConstants::DEFAULT_DATETIME, strtotime($STdate));
        $todate = date(speedConstants::DEFAULT_DATETIME, strtotime($EDdate));
        $finalreport = '<div style="width:auto; height:30px;">
                                                <table style="width: auto; border:none;">
                                                    <tr>
                                                        <td></td>
                                                        <td style="width:420px; border:none;">
                                                            <h3 style="text-transform:uppercase;">Temperature Sensor Report</h3><br />
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                            </div>';
        $finalreport .= "Vehicle No. $vehicleno
                                            From :  $fromdate to : $todate";
        $finalreport .= "<hr /><br/><br/>
                                            <style type='text/css'>
                                                table, td { border: solid 1px  #999999; color:#000000; }
                                                hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
                                            </style>
                                            <table id='search_table_2' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                                <tbody>";
        $finalreport .= create_tempxls_from_report($days, $unit);
    }
    echo $finalreport;
}

function getacreportcsv($customerno, $STdate, $EDdate, $deviceid, $vehicleno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date(speedConstants::DEFAULT_DATETIME);
        $repdate = convertDateToFormat($STdate, speedConstants::DEFAULT_DATE);
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
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date(speedConstants::DEFAULT_DATETIME);
        $repdate = convertDateToFormat($STdate, speedConstants::DEFAULT_DATE);
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
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date(speedConstants::DEFAULT_DATETIME);
        $repdate = convertDateToFormat($STdate, speedConstants::DEFAULT_DATE);
        $fromdate = convertDateToFormat($STdate, speedConstants::DEFAULT_DATE);
        $todate = convertDateToFormat($EDdate, speedConstants::DEFAULT_DATE);

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

function getgensetreportexcel($customerno, $STdate, $EDdate, $deviceid, $vehicleno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $acinvertval = getacinvertvalpdf($customerno, $unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            //Date Check Operations
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getgensetdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        //$date_t = strtotime(date("Y-m-d H:i:s").' UTC');
        $today = date(speedConstants::DEFAULT_DATETIME);
        $repdate = convertDateToFormat($STdate, speedConstants::DEFAULT_DATE);
        $fromdate = convertDateToFormat($STdate, speedConstants::DEFAULT_DATE);
        $todate = convertDateToFormat($EDdate, speedConstants::DEFAULT_DATE);
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
        $finalreport .= create_gensetexcel_from_report($days, $acinvertval);
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
            $data = null;
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (file_exists($location)) {
                $location = "sqlite:" . $location;
                $data = getacdata_fromsqlite($location, $deviceid);
            }
            if ($data != null && count($data) > 1) {
                $report = createrep($data);
                if ($report != null) {
                    $days = array_merge($days, $report);
                }
            }
        }
    }
    if ($days != null && count($days) > 0) {
        $finalreport = create_html_from_report($days, $acinvertval);
    }
    echo $finalreport;
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
        /* while(isset($data[$a+$i]) && getduration($data[$a+$i]->lastupdated,$currentrow->starttime)<3)
          {
          $i+=1;
          } */
        while (isset($data[$a + $i]) && checkdate_status($data[$a + $i], $currentrow, $data, ($a + $i))) {
            $i += 1;
        }
        if (isset($data[$a + $i])) {
            $currentrow->endtime = $data[$a + $i]->lastupdated;
            $currentrow->duration = round(getduration($currentrow->endtime, $currentrow->starttime), 0);
            $gen_report[] = $currentrow;
            $currentrow = new stdClass();
            $currentrow->starttime = $data[$a + $i]->lastupdated;
            $currentrow_count = $a + $i;
            //Just To Check That Data For The Next Row Is Not Wrong
            while (isset($data[$a + $i + 1]) && getduration($data[$a + $i + 1]->lastupdated, $currentrow->starttime) < 3) {
                $i += 1;
            }
            if (($a + $i) > $currentrow_count) {
                $gen_report[$counter]->endtime = $data[$a + $i]->lastupdated;
                $gen_report[$counter]->duration = round(getduration($gen_report[$counter]->endtime, $gen_report[$counter]->starttime), 0);
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
                $gen_report[$i - 1]->duration = round(getduration($gen_report[$i - 1]->endtime, $gen_report[$i - 1]->starttime), 0);
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
        $i += 1;
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
                if (@$laststatus['digitalio'] != $row['digitalio']) {
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

function gettempdata_fromsqlite($location, $deviceid, $interval, $startdate, $enddate) {
    $devices = array();
    $query = "SELECT devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong,
    unithistory.analog1, unithistory.analog2, unithistory.analog1, unithistory.analog2 from devicehistory INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
    WHERE devicehistory.lastupdated BETWEEN '$startdate' AND '$enddate' AND devicehistory.deviceid= $deviceid AND devicehistory.status!='F' group by devicehistory.lastupdated";
    try {
        $database = new PDO($location);
        $query1 = $query . " ORDER BY devicehistory.lastupdated ASC";
        $result = $database->query($query1);
        $laststatus = array();
        if (isset($result) && $result != "") {
            $lastupdated = "";
            foreach ($result as $row) {
                if ($lastupdated == "") {
                    $lastupdated = $row['lastupdated'];
                } else {
                    if (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) > $interval) {
                        $device = new VODevices();
                        $device->analog1 = $row['analog1'];
                        $device->analog2 = $row['analog2'];
                        $device->analog3 = $row['analog3'];
                        $device->analog4 = $row['analog4'];
                        $device->starttime = $row['lastupdated'];
                        $device->endtime = $row['lastupdated'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $devices[] = $device;
                        $lastupdated = "";
                    }
                }
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    return $devices;
}

function gendays($STdate, $EDdate) {
    $TOTALDAYS = array();
    $STdate = date("Y-m-d", strtotime($STdate));
    $EDdate = date("Y-m-d", strtotime($EDdate));
    while (strtotime($STdate) <= strtotime($EDdate)) {
        $TOTALDAYS[] = $STdate;
        $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
    }
    return $TOTALDAYS;
}

function genhours($STdate, $EDdate) {
    $TOTALDAYS = array();
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

function getdailyreport($STdate, $EDdate, $vehicleid, $stime = null, $etime = null) {
    $start = $STdate;
    $DATAS = array();
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
    $totaldays = array();
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
                $DATA = GetDailyReport_Data($location, $STdate, $EDdate, $vehicleid);
                if ($DATA != null) {
                    $DATAS = array_merge($DATAS, $DATA);
                }

                //$DATAS[] = $DATA;
            } else {
                echo "<script type='text/javascript'>
                jQuery('#error').show();jQuery('#error').fadeOut(5000);
            </script>";
            }
        }
    }
    //print_r($DATAS);
    return $DATAS;
}

function get_all_checkpoint() {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getallcheckpoints();
    return $checkpoints;
}

function get_checkpointname($id, $type = NULL) {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpointname = $checkpointmanager->get_checkpointname($id, $type);
    return $checkpointname;
}

function getdailyreport_check($STdate, $ETdate, $stime, $etime, $chkpt_start, $chkpt_end, $chkpt_via = null, $vehicleid = null, $chktype = NULL) {
    $DATAS = array();
    $STdate = $STdate . " " . date('H:i:s', strtotime($stime));
    $ETdate = $ETdate . " " . date('H:i:s', strtotime($etime));
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/chkreport.sqlite";
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $vehicle = isset($vehicleid) ? $vehicleid : 0;
    if ($vehicle != 0) {
        $VEHICLES = array();
        $VEHICLE = $vehiclemanager->get_vehicle_details($vehicle);
        $VEHICLE = (object) $VEHICLE;
        $VEHICLES[] = $VEHICLE;
    } else if ($_SESSION['groupid'] != 0) {
        $VEHICLES = $vehiclemanager->get_groups_vehicles($_SESSION['groupid']);
    } else {
        $VEHICLES = $vehiclemanager->GetAllVehicles();
    }
    foreach ($VEHICLES as $vehicle) {
        $DATAS[] = GetCheckpoint_Report($location, $STdate, $ETdate, $vehicle->vehicleid, $chkpt_start, $chkpt_end, $vehicle->groupid, $chkpt_via, $chktype);
    }
    return $DATAS;
}

function getdailyreport_check_exception($STdate, $ETdate, $stime, $etime, $chkpt_start, $chkpt_end, $vehicleid, $customerno, $report_type) {
    $STdate = $STdate . " " . date('H:i:s', strtotime($stime));
    $ETdate = $ETdate . " " . date('H:i:s', strtotime($etime));
    $location = "../../customer/$customerno/reports/chkreport.sqlite";
    $DATAS = GetCheckpoint_Report_exception($location, $STdate, $ETdate, $vehicleid, $chkpt_start, $chkpt_end, null, $report_type, $customerno);
    return $DATAS;
}

function getdailyreport_check_pdf($customerno, $STdate, $ETdate, $stime, $etime, $chkpt_start, $chkpt_end, $chkpt_via, $chktype = null, $vehicleid = null) {
    $DATAS = array();
    $vehicleid = GetSafeValueString($vehicleid, "long");
    $STdate = $STdate . " " . date('H:i:s', strtotime($stime));
    $ETdate = $ETdate . " " . date('H:i:s', strtotime($etime));
    $location = "../../customer/$customerno/reports/chkreport.sqlite";

    if (isset($vehicleid) && $vehicleid > 0) {
        $DATAS[] = GetCheckpoint_Report_pdf($customerno, $location, $STdate, $ETdate, $vehicleid, $chkpt_start, $chkpt_end, $chkpt_via, $chktype);
    } else {
        $vehiclemanager = new VehicleManager($_SESSION['customerno']);
        $VEHICLES = $vehiclemanager->GetAllVehicles();
        foreach ($VEHICLES as $vehicle) {
            $DATAS[] = GetCheckpoint_Report_pdf($customerno, $location, $STdate, $ETdate, $vehicle->vehicleid, $chkpt_start, $chkpt_end, $chkpt_via, $chktype);
        }
    }
    //print_r($DATAS);
    return $DATAS;
}

function getdailyreport_check_by_vehicle($STdate, $ETdate, $stime, $etime, $chkpt_start, $chkpt_end, $chkpt_via, $vehicleid = null, $chktype = NULL) {
    $DATAS = array();
    $STdate = $STdate . " " . date('H:i:s', strtotime($stime));
    $ETdate = $ETdate . " " . date('H:i:s', strtotime($etime));
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/chkreport.sqlite";
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);

    $vehicle = isset($vehicleid) ? $vehicleid : 0;
    if ($vehicle != 0) {
        $VEHICLES = array();
        $VEHICLE = $vehiclemanager->get_vehicle_details($vehicle);
        $VEHICLE = (object) $VEHICLE;
        $VEHICLES[] = $VEHICLE;
    } else {
        $VEHICLES = $vehiclemanager->GetAllVehicles();
    }
    //print_r($VEHICLES);
    foreach ($VEHICLES as $vehicle) {
        $DATAS[] = GetCheckpoint_Report_by_vehicle($location, $STdate, $ETdate, $vehicle->vehicleid, $chkpt_start, $chkpt_end, $chkpt_via, $chktype);
    }
    //print_r($DATAS);
    return $DATAS;
}

function GetCheckpoint_Report_exception($location, $STdate, $ETdate, $id, $chkpt_start, $chkpt_end, $groupid = null, $report_type, $customerno = null) {
    if ($customerno == null) {
        $customerno = $_SESSION['customerno'];
    }
    $REPORT_Start = array();
    $REPORT_Final = array();
    $arr_start = array();
    $arr_end = array();
    $path = "sqlite:$location";
    if (file_exists($location)) {
        $db = new PDO($path);

        $flag = 0;
        $Query_Start = "SELECT * FROM V$id WHERE date BETWEEN '$STdate' AND '$ETdate' AND chkid IN ($chkpt_start, $chkpt_end) ORDER BY date asc";
        $result_Start = $db->query($Query_Start);
        if (isset($result_Start) && $result_Start != '') {
            foreach ($result_Start as $row_Start) {
                $Datacap = new stdClass;
                $Datacap->chkrepid = $row_Start['chkrepid'];
                $Datacap->chkid = $row_Start['chkid'];
                $Datacap->status = $row_Start['status'];
                $Datacap->date = $row_Start['date'];
                $REPORT_Start[] = $Datacap;
            }
            foreach ($REPORT_Start as $chkreport) {
                if ($chkreport->status == '1' && $chkreport->chkid == $chkpt_start) {
                    if ($flag == 1) {
                        $arr_start = array_slice($arr_start, 0, -1, true);
                        $arr_start[] = $chkreport->chkrepid;
                    } else if ($flag == 0) {
                        $flag = 1;
                        $arr_start[] = $chkreport->chkrepid;
                    }
                } else if ($chkreport->status == '0' && $chkreport->chkid == $chkpt_end && $flag == 1) {
                    $flag = 0;
                    $arr_end[] = $chkreport->chkrepid;
                }
            }
            if (!empty($arr_start) && !empty($arr_end)) {
                $arr_startlen = count($arr_start);
                $arr_endlen = count($arr_end);
                if ($arr_startlen > $arr_endlen) {
                    $arr_start = array_slice($arr_start, 0, -1, true);
                }
                $arr_start = array(array_pop($arr_start));
                $arr_end = array(array_pop($arr_end));
                $i = 0;
                $startelement = $arr_start[$i];
                $endelement = $arr_end[$i];
                $start_chkppoint = getChkReport($location, $id, $startelement, $chkpt_start, $STdate, $ETdate, 1);
                $end_chkppoint = getChkReport($location, $id, $endelement, $chkpt_end, $STdate, $ETdate, 0);
                $unitno = getunitnotemp_pdf($id, $customerno);
                $did = GetDevice_byId($id, $customerno);
                if (isset($unitno) && $unitno != '') {
                    $userdatestart = date('Y-m-d', strtotime($start_chkppoint->date));
                    $userdatestart = substr($userdatestart, 0, 11);
                    $userdateend = date('Y-m-d', strtotime($end_chkppoint->date));
                    $userdateend = substr($userdateend, 0, 11);
                    $locationstart = "../../customer/$customerno/unitno/$unitno/sqlite/$userdatestart.sqlite";
                    $locationend = "../../customer/$customerno/unitno/$unitno/sqlite/$userdateend.sqlite";
                    if (file_exists($locationstart) && file_exists($locationend)) {
                        $firstodometer = getOdometer($id, $locationstart, $start_chkppoint->date);
                        $lastodometer = getOdometer($id, $locationend, $end_chkppoint->date);
                        $intervel = 5;
                        $distance = round(($lastodometer - $firstodometer) / 1000, 2);
                        $average = getAverage_pdf($id, $customerno);
                        if ($firstodometer != '0' && $lastodometer != '0') {
                            $vehicle = new stdClass();
                            $vehicle->vehicleid = $id;
                            $vehicle->vehicleno = getVehicleName_pdf($id, $customerno);
                            $vehicle->distance = $distance;
                            if ($average > 0) {
                                $vehicle->fuelconsume = $distance / $average;
                            } else {
                                $vehicle->fuelconsume = "N/A";
                            }
                            if ($report_type == 'idle_time') {
                                $vehicle->idletime = getIdletime($start_chkppoint->date, $end_chkppoint->date, $did, $intervel, $customerno, $unitno);
                            }
                            $vehicle->startdate = $start_chkppoint->date;
                            $vehicle->enddate = $end_chkppoint->date;
                            $vehicle->startcheckpoint = $chkpt_start;
                            $vehicle->endcheckpoint = $chkpt_end;
                            $vehicle->groupid = $groupid;
                            $REPORT_Final[] = $vehicle;
                        }
                    }
                }
                return $REPORT_Final;
            }
        }
    }
}

function GetCheckpoint_Report($location, $STdate, $ETdate, $id, $chkpt_start, $chkpt_end, $groupid = null, $chkpt_via = null, $chktype = NULL) {
    $REPORT_Final = array();
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $REPORT_Start = array();
        $flag = 0;
        $arr_start = array();
        $arr_end = array();
        $i = 0;
        if (!empty($chkpt_via)) {
            if (isset($chktype) && $chktype == '2') {
                $Query_Start = "SELECT * FROM V$id WHERE date BETWEEN '$STdate' AND '$ETdate' AND chktype IN ($chkpt_start, $chkpt_end, $chkpt_via) ORDER BY date ASC";
                $result_Start = $db->query($Query_Start);
            } elseif ((isset($chktype) && $chktype == '1') || $chktype == NULL) {
                $Query_Start = "SELECT * FROM V$id WHERE date BETWEEN '$STdate' AND '$ETdate' AND chkid IN ($chkpt_start, $chkpt_end, $chkpt_via) ORDER BY date ASC";
                $result_Start = $db->query($Query_Start);
            }
        } else {
            if (isset($chktype) && $chktype == '2') {
                $Query_Start = "SELECT * FROM V$id WHERE date BETWEEN '$STdate' AND '$ETdate' AND chktype IN ($chkpt_start, $chkpt_end) ORDER BY date ASC";
                $result_Start = $db->query($Query_Start);
            } elseif ((isset($chktype) && $chktype == '1') || $chktype == NULL) {
                $Query_Start = "SELECT * FROM V$id WHERE date BETWEEN '$STdate' AND '$ETdate' AND chkid IN ($chkpt_start, $chkpt_end) ORDER BY date ASC";
                $result_Start = $db->query($Query_Start);
            }
        }
        if (isset($result_Start) && $result_Start != '') {
            $chkidarr = array();
            $datatest = array();
            foreach ($result_Start as $row_Start) {
                $chkidarr[] = $row_Start['chkid'];
                $datatest[] = array(
                    "chkrepid" => $row_Start['chkrepid'],
                    "chkid" => $row_Start['chkid'],
                    "chktype" => isset($row_Start['chktype']) ? $row_Start['chktype'] : 0,
                    "status" => $row_Start['status'],
                    "date" => $row_Start['date']
                );
            }
            if (isset($chktype) && $chktype == '2') {
                if (!empty($datatest)) {
                    foreach ($datatest as $data) {
                        if ($data['chktype'] == $chkpt_start) {
                            $chkpt_start = $data['chkid'];
                            $chkrepid_start = $data['chkrepid'];
                            break;
                        }
                    }
                    foreach (array_reverse($datatest) as $data) {
                        if ($data['chktype'] == $chkpt_end) {
                            $chkpt_end = $data['chkid'];
                            $chkrepid_end = $data['chkrepid'];
                            break;
                        }
                    }
                    if (!empty($chkpt_via)) {
                        foreach (array_slice($datatest, 1, -1) as $data) {
                            if ($data['chktype'] == $chkpt_via && $chkpt_start != $data['chkid'] && $chkpt_end != $data['chkid'] && $data['chkrepid'] > $chkrepid_start && $data['chkrepid'] < $chkrepid_end) {
                                $chkpt_via = $data['chkid'];
                                break;
                            }
                        }
                    }
                }
            }
            if (!empty($chkpt_via)) {
                //echo"<pre>"; print_r($chkidarr);
                if (!empty($chkidarr) && in_array($chkpt_start, $chkidarr) && in_array($chkpt_via, $chkidarr) && in_array($chkpt_end, $chkidarr)) {
                    $REPORT_Start = array();
                    foreach ($datatest as $data) {
                        $Datacap = new stdClass;
                        $Datacap->chkrepid = $data['chkrepid'];
                        $Datacap->chkid = $data['chkid'];
                        $Datacap->status = $data['status'];
                        $Datacap->date = $data['date'];
                        $REPORT_Start[] = $Datacap;
                    }
                }
            } else {
                foreach ($datatest as $data1) {
                    $Datacap1 = new stdClass;
                    $Datacap1->chkrepid = $data1['chkrepid'];
                    $Datacap1->chkid = $data1['chkid'];
                    $Datacap1->status = $data1['status'];
                    $Datacap1->date = $data1['date'];
                    $REPORT_Start[] = $Datacap1;
                }
            }
            $via_start = array();
            foreach ($REPORT_Start as $chkreport) {
                $via_start[$chkreport->chkrepid] = $chkreport->date;
                if ($chkreport->status == '1' && $chkreport->chkid == $chkpt_start) {
                    if ($flag == 1) {
                        $arr_start = array_slice($arr_start, 0, -1, true);
                        $arr_start[] = $chkreport->chkrepid;
                    } else if ($flag == 0) {
                        $flag = 1;
                        $arr_start[] = $chkreport->chkrepid;
                    }
                } else if ($chkreport->status == '0' && $chkreport->chkid == $chkpt_end && $flag == 1) {
                    $flag = 0;
                    $arr_end[] = $chkreport->chkrepid;
                }
            }
            if (!empty($arr_start) && !empty($arr_end)) {
                $arr_start_via = array();
                $arr_end_via = array();
                $isChkPtTraversed = 0;
                $lastChkPtTraveresed = 0;
                // to check if checkpoint via exists
                if (!empty($chkpt_via)) {
                    foreach ($REPORT_Start as $chkreportvia) {
                        if ($isChkPtTraversed == 1 && $chkreportvia->chkrepid <= $lastChkPtTraveresed) {
                            continue;
                        }
                        $isChkPtTraversed = 0;
                        $lastChkPtTraveresed = 0;
                        for ($i = 0; $i < count($arr_start); $i++) {
                            if (array_key_exists($i, $arr_end)) {
                                $thisdate = strtotime($chkreportvia->date);
                                if ($thisdate >= strtotime($via_start[$arr_start[$i]]) && $thisdate <= strtotime($via_start[$arr_end[$i]])) {
                                    if ($chkreportvia->chkid == $chkpt_via) {
                                        $arr_start_via[] = $arr_start[$i];
                                        $arr_end_via[] = $arr_end[$i];
                                        $isChkPtTraversed = 1;
                                        $lastChkPtTraveresed = $arr_end[$i];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    if (!isset($arr_start_via)) {
                        return $REPORT_Final;
                    }
                }
                if (isset($arr_start_via) && !empty($arr_start_via)) {
                    $arr_start = array_unique($arr_start_via);
                    $arr_end = array_unique($arr_end_via);
                }
                $arr_startlen = count($arr_start);
                $arr_endlen = count($arr_end);
                if ($arr_startlen > $arr_endlen) {
                    $arr_start = array_slice($arr_start, 0, -1, true);
                }
                for ($i = 0; $i < count($arr_start); $i++) {
                    //$arr_start[$i] ." AND ". $arr_end[$i];echo "</br>";
                    $startelement = $arr_start[$i];
                    $endelement = $arr_end[$i];
                    $start_chkppoint = getChkReport($location, $id, $startelement, $chkpt_start, $STdate, $ETdate, 1);
                    $end_chkppoint = getChkReport($location, $id, $endelement, $chkpt_end, $STdate, $ETdate, 0);
                    $unitno = getunitnotemp($id);
                    $did = GetDevice_byId($id, $_SESSION['customerno']);
                    if (isset($unitno) && $unitno != '') {
                        $userdatestart = date('Y-m-d', strtotime($start_chkppoint->date));
                        $userdatestart = substr($userdatestart, 0, 11);
                        $userdateend = date('Y-m-d', strtotime($end_chkppoint->date));
                        $userdateend = substr($userdateend, 0, 11);
                        $customerno = $_SESSION['customerno'];
                        $locationstart = "../../customer/$customerno/unitno/$unitno/sqlite/$userdatestart.sqlite";
                        $locationend = "../../customer/$customerno/unitno/$unitno/sqlite/$userdateend.sqlite";
                        if (file_exists($locationstart) && file_exists($locationend)) {
                            $firstodometer = getOdometer($id, $locationstart, $start_chkppoint->date);
                            $lastodometer = getOdometer($id, $locationend, $end_chkppoint->date);
                            $intervel = 5;
                            if ($lastodometer < $firstodometer) {
                                $max = GetOdometerMax($location);
                                $lastodometer = $max + $lastodometer;
                            }
                            $distance = round(($lastodometer - $firstodometer) / 1000, 2);
                            $average = getAverage($id);
                            if ($firstodometer != '0' && $lastodometer != '0') {
                                $vehicle = new stdClass();
                                $vehicle->vehicleid = $id;
                                $vehicle->vehicleno = getVehicleName($id);
                                $vehicle->distance = $distance;
                                if ($average > 0) {
                                    $vehicle->fuelconsume = $distance / $average;
                                } else {
                                    $vehicle->fuelconsume = "N/A";
                                }
                                $vehicle->idletime = getIdletime($start_chkppoint->date, $end_chkppoint->date, $did, $intervel, $customerno, $unitno);
                                $vehicle->startdate = $start_chkppoint->date;
                                $vehicle->enddate = $end_chkppoint->date;
                                $vehicle->startcheckpoint = $chkpt_start;
                                $vehicle->endcheckpoint = $chkpt_end;
                                if (!empty($chkpt_via)) {
                                    $vehicle->route = get_checkpointname($chkpt_start) . ' To ' . get_checkpointname($chkpt_end) . ' Via ' . get_checkpointname($chkpt_via);
                                } else {
                                    $vehicle->route = get_checkpointname($chkpt_start) . ' To ' . get_checkpointname($chkpt_end);
                                }
                                $vehicle->groupid = $groupid;
                                $REPORT_Final[] = $vehicle;
                            }
                            //print_r($REPORT_Final);echo "</br>";
                        }
                    }
                }
                return $REPORT_Final;
            }
        }
    }
}

function GetOdometerMax($location) {
    $ODOMETER = 0;
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $query = "SELECT max(odometer) as odometerm from vehiclehistory";
        $result = $db->query($query);
        if (isset($result) && !empty($result)) {
            foreach ($result as $row) {
                $ODOMETER = $row['odometerm'];
            }
        }
    }
    return $ODOMETER;
}

function GetCheckpoint_Report_pdf($customerno, $location, $STdate, $ETdate, $id, $chkpt_start, $chkpt_end, $chkpt_via = null, $chktype = null) {
    $path = "sqlite:$location";
    if (file_exists($location)) {
        $db = new PDO($path);
        $STdate = date('Y-m-d H:i:s', strtotime($STdate));
        $ETdate = date('Y-m-d H:i:s', strtotime($ETdate));
        $REPORT_Start = array();
        $REPORT_Final = array();
        $flag = 0;
        $arr_start = array();
        $arr_end = array();
        $i = 0;
        if (!empty($chkpt_via)) {
            if (isset($chktype) && $chktype == '2') {
                $Query_Start = "SELECT * FROM V$id WHERE date BETWEEN '$STdate' AND '$ETdate' AND chktype IN ($chkpt_start, $chkpt_end,$chkpt_via) ORDER BY date ASC";
                $result_Start = $db->query($Query_Start);
            } elseif ((isset($chktype) && $chktype == '1') || $chktype == NULL) {
                $Query_Start = "SELECT * FROM V$id WHERE date BETWEEN '$STdate' AND '$ETdate' AND chkid IN ($chkpt_start, $chkpt_end,$chkpt_via) ORDER BY date ASC";
                $result_Start = $db->query($Query_Start);
            }
        } else {
            if (isset($chktype) && $chktype == '2') {
                $Query_Start = "SELECT * FROM V$id WHERE date BETWEEN '$STdate' AND '$ETdate' AND chktype IN ($chkpt_start, $chkpt_end) ORDER BY date ASC";
                $result_Start = $db->query($Query_Start);
            } elseif ((isset($chktype) && $chktype == '1') || $chktype == NULL) {
                $Query_Start = "SELECT * FROM V$id WHERE date BETWEEN '$STdate' AND '$ETdate' AND chkid IN ($chkpt_start, $chkpt_end) ORDER BY date ASC";
                $result_Start = $db->query($Query_Start);
            }
        }
        if (isset($result_Start) && $result_Start != '') {
            $chkidarr = array();
            $datatest = array();
            foreach ($result_Start as $row_Start) {
                $chkidarr[] = $row_Start['chkid'];
                $datatest[] = array(
                    "chkrepid" => $row_Start['chkrepid'],
                    "chkid" => $row_Start['chkid'],
                    "chktype" => isset($row_Start['chktype']) ? $row_Start['chktype'] : 0,
                    "status" => $row_Start['status'],
                    "date" => $row_Start['date']
                );
            }
            if (isset($chktype) && $chktype == '2') {
                if (!empty($datatest)) {
                    foreach ($datatest as $data) {
                        if ($data['chktype'] == $chkpt_start) {
                            $chkpt_start = $data['chkid'];
                            $chkrepid_start = $data['chkrepid'];
                            break;
                        }
                    }
                    foreach (array_reverse($datatest) as $data) {
                        if ($data['chktype'] == $chkpt_end) {
                            $chkpt_end = $data['chkid'];
                            $chkrepid_end = $data['chkrepid'];
                            break;
                        }
                    }
                    if (!empty($chkpt_via)) {
                        foreach (array_slice($datatest, 1, -1) as $data) {
                            if ($data['chktype'] == $chkpt_via && $chkpt_start != $data['chkid'] && $chkpt_end != $data['chkid'] && $data['chkrepid'] > $chkrepid_start && $data['chkrepid'] < $chkrepid_end) {
                                $chkpt_via = $data['chkid'];
                                break;
                            }
                        }
                    }
                }
            }

            if (!empty($chkpt_via)) {
                //echo"<pre>"; print_r($chkidarr);
                if (!empty($chkidarr) && in_array($chkpt_start, $chkidarr) && in_array($chkpt_via, $chkidarr) && in_array($chkpt_end, $chkidarr)) {
                    $REPORT_Start = array();
                    foreach ($datatest as $data) {
                        $Datacap = new stdClass;
                        $Datacap->chkrepid = $data['chkrepid'];
                        $Datacap->chkid = $data['chkid'];
                        $Datacap->status = $data['status'];
                        $Datacap->date = $data['date'];
                        $REPORT_Start[] = $Datacap;
                    }
                }
            } else {
                foreach ($datatest as $data1) {
                    $Datacap1 = new stdClass;
                    $Datacap1->chkrepid = $data1['chkrepid'];
                    $Datacap1->chkid = $data1['chkid'];
                    $Datacap1->status = $data1['status'];
                    $Datacap1->date = $data1['date'];
                    $REPORT_Start[] = $Datacap1;
                }
            }
//            foreach ($result_Start as $row_Start) {
//                $Datacap = new stdClass;
//                $Datacap->chkrepid = $row_Start['chkrepid'];
//                $Datacap->chkid = $row_Start['chkid'];
//                $Datacap->status = $row_Start['status'];
//                $Datacap->date = $row_Start['date'];
//                $REPORT_Start[] = $Datacap;
//            }
            //print_r($REPORT_Start);
            $via_start = array();
            foreach ($REPORT_Start as $chkreport) {
                $via_start[$chkreport->chkrepid] = $chkreport->date;
                if ($chkreport->status == '1' && $chkreport->chkid == $chkpt_start) {
                    if ($flag == 1) {
                        $arr_start = array_slice($arr_start, 0, -1, true);
                        $arr_start[] = $chkreport->chkrepid;
                    } else if ($flag == 0) {
                        $flag = 1;
                        $arr_start[] = $chkreport->chkrepid;
                    }
                } else if ($chkreport->status == '0' && $chkreport->chkid == $chkpt_end && $flag == 1) {
                    $flag = 0;
                    $arr_end[] = $chkreport->chkrepid;
                }
            }
            if (!empty($arr_start) && !empty($arr_end)) {
                $arr_start_via = array();
                $arr_end_via = array();
                $isChkPtTraversed = 0;
                $lastChkPtTraveresed = 0;
                // to check if checkpoint via exists
                if (!empty($chkpt_via)) {
                    foreach ($REPORT_Start as $chkreportvia) {
                        if ($isChkPtTraversed == 1 && $chkreportvia->chkrepid <= $lastChkPtTraveresed) {
                            continue;
                        }
                        $isChkPtTraversed = 0;
                        $lastChkPtTraveresed = 0;
                        for ($i = 0; $i < count($arr_start); $i++) {
                            if (array_key_exists($i, $arr_end)) {
                                $thisdate = strtotime($chkreportvia->date);
                                if ($thisdate >= strtotime($via_start[$arr_start[$i]]) && $thisdate <= strtotime($via_start[$arr_end[$i]])) {
                                    if ($chkreportvia->chkid == $chkpt_via) {
                                        $arr_start_via[] = $arr_start[$i];
                                        $arr_end_via[] = $arr_end[$i];
                                        $isChkPtTraversed = 1;
                                        $lastChkPtTraveresed = $arr_end[$i];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    if (!isset($arr_start_via)) {
                        return $REPORT_Final;
                    }
                }
                if (isset($arr_start_via) && !empty($arr_start_via)) {
                    $arr_start = array_unique($arr_start_via);
                    $arr_end = array_unique($arr_end_via);
                }
                $arr_startlen = count($arr_start);
                $arr_endlen = count($arr_end);
                if ($arr_startlen > $arr_endlen) {
                    $arr_start = array_slice($arr_start, 0, -1, true);
                }
                for ($i = 0; $i < count($arr_start); $i++) {
                    //echo $arr_start[$i] ." AND ". $arr_end[$i];echo "</br>";
                    $startelement = $arr_start[$i];
                    $endelement = $arr_end[$i];
                    $start_chkppoint = getChkReport($location, $id, $startelement, $chkpt_start, $STdate, $ETdate, 1);
                    $end_chkppoint = getChkReport($location, $id, $endelement, $chkpt_end, $STdate, $ETdate, 0);
                    $unitno = getunitnotemp_pdf($id, $customerno);
                    $did = GetDevice_byId($id, $customerno);
                    if (isset($unitno) && $unitno != '') {
                        $userdatestart = date('Y-m-d', strtotime($start_chkppoint->date));
                        $userdatestart = substr($userdatestart, 0, 11);
                        $userdateend = date('Y-m-d', strtotime($end_chkppoint->date));
                        $userdateend = substr($userdateend, 0, 11);
                        $locationstart = "../../customer/$customerno/unitno/$unitno/sqlite/$userdatestart.sqlite";
                        $locationend = "../../customer/$customerno/unitno/$unitno/sqlite/$userdateend.sqlite";
                        if (file_exists($locationstart) && file_exists($locationend)) {
                            $firstodometer = getOdometer($id, $locationstart, $start_chkppoint->date);
                            $lastodometer = getOdometer($id, $locationend, $end_chkppoint->date);
                            $intervel = 5;
                            if ($lastodometer < $firstodometer) {
                                $max = GetOdometerMax($location);
                                $lastodometer = $max + $lastodometer;
                            }
                            $distance = round(($lastodometer - $firstodometer) / 1000, 2);
                            $average = getAverage($id);
                            if ($firstodometer != '0' && $lastodometer != '0') {
                                $vehicle = new stdClass();
                                $vehicle->vehicleid = $id;
                                $vehicle->vehicleno = getVehicleName($id);
                                $vehicle->distance = $distance;
                                if ($average > 0) {
                                    $vehicle->fuelconsume = $distance / $average;
                                } else {
                                    $vehicle->fuelconsume = "N/A";
                                }
                                $vehicle->idletime = getIdletime($start_chkppoint->date, $end_chkppoint->date, $did, $intervel, $customerno, $unitno);
                                $vehicle->startdate = $start_chkppoint->date;
                                $vehicle->enddate = $end_chkppoint->date;
                                $vehicle->startcheckpoint = $chkpt_start;
                                $vehicle->endcheckpoint = $chkpt_end;
                                if (!empty($chkpt_via)) {
                                    $vehicle->route = get_checkpointname($chkpt_start) . ' To ' . get_checkpointname($chkpt_end) . ' Via ' . get_checkpointname($chkpt_via);
                                } else {
                                    $vehicle->route = get_checkpointname($chkpt_start) . ' To ' . get_checkpointname($chkpt_end);
                                }
                                $REPORT_Final[] = $vehicle;
                            }
                            //print_r($REPORT_Final);
                            // echo "</br>";
                        }
                    }
                }
                return $REPORT_Final;
            }
        }
    }
}

function closest($array, $number) {
    #does the array already contain the number?
    if ($i = array_search($number, $array)) {
        return $i;
    }

    #add the number to the array
    $array[] = $number;
    #sort and refind the number
    sort($array);
    $i = array_search($number, $array);
    #check if there is a number above it
    if ($i && isset($array[$i - 1])) {
        return $array[$i - 1];
    }

    //alternatively you could return the number itself here, or below it depending on your requirements
    return null;
}

function getChkReport($location, $id, $chkrep, $chkid, $STdate, $ETdate, $status) {
    $path = "sqlite:$location";
    if (file_exists($location)) {
        $db = new PDO($path);
        $Query_Start = "SELECT * FROM V$id WHERE chkid=$chkid AND status = $status AND date BETWEEN '$STdate' AND '$ETdate' AND chkrepid= $chkrep";
        $result_Start = $db->query($Query_Start);
        if (isset($result_Start) && $result_Start != '') {
            foreach ($result_Start as $row_Start) {
                $Datacap = new stdClass;
                $Datacap->chkrepid = $row_Start['chkrepid'];
                $Datacap->chkid = $row_Start['chkid'];
                $Datacap->status = $row_Start['status'];
                $Datacap->date = $row_Start['date'];
                return $Datacap;
            }
        }
    }
}

function GetCheckpoint_Report_by_vehicle($location, $STdate, $ETdate, $id, $chkpt_start, $chkpt_end, $chkpt_via = null, $chktype = null) {
    $path = "sqlite:$location";
    if (file_exists($location)) {
        $db = new PDO($path);
        $REPORT_Start = array();
        $REPORT_Final = array();
        $flag = 0;
        $arr_start = array();
        $arr_end = array();
        $i = 0;
        if (!empty($chkpt_via)) {
            if (isset($chktype) && $chktype == '2') {
                $Query_Start = "SELECT * FROM V$id WHERE date BETWEEN '$STdate' AND '$ETdate' AND chktype IN ($chkpt_start, $chkpt_end,$chkpt_via) ORDER BY date ASC";
                $result_Start = $db->query($Query_Start);
            } elseif ((isset($chktype) && $chktype == '1') || $chktype == NULL) {
                $Query_Start = "SELECT * FROM V$id WHERE date BETWEEN '$STdate' AND '$ETdate' AND chkid IN ($chkpt_start, $chkpt_end,$chkpt_via) ORDER BY date ASC";
                $result_Start = $db->query($Query_Start);
            }
        } else {
            if (isset($chktype) && $chktype == '2') {
                $Query_Start = "SELECT * FROM V$id WHERE date BETWEEN '$STdate' AND '$ETdate' AND chktype IN ($chkpt_start, $chkpt_end) ORDER BY date ASC";
                $result_Start = $db->query($Query_Start);
            } elseif ((isset($chktype) && $chktype == '1') || $chktype == NULL) {
                $Query_Start = "SELECT * FROM V$id WHERE date BETWEEN '$STdate' AND '$ETdate' AND chkid IN ($chkpt_start, $chkpt_end) ORDER BY date ASC";
                $result_Start = $db->query($Query_Start);
            }
        }
        if (isset($result_Start) && $result_Start != '') {

            $chkidarr = array();
            $datatest = array();
            foreach ($result_Start as $row_Start) {
                $chkidarr[] = $row_Start['chkid'];
                $datatest[] = array(
                    "chkrepid" => $row_Start['chkrepid'],
                    "chkid" => $row_Start['chkid'],
                    "chktype" => $row_Start['chktype'],
                    "status" => $row_Start['status'],
                    "date" => $row_Start['date']
                );
            }
            if (isset($chktype) && $chktype == '2') {
                if (!empty($datatest)) {
                    foreach ($datatest as $data) {
                        if ($data['chktype'] == $chkpt_start) {
                            $chkpt_start = $data['chkid'];
                            $chkrepid_start = $data['chkrepid'];
                            break;
                        }
                    }
                    foreach (array_reverse($datatest) as $data) {
                        if ($data['chktype'] == $chkpt_end) {
                            $chkpt_end = $data['chkid'];
                            $chkrepid_end = $data['chkrepid'];
                            break;
                        }
                    }
                    if (!empty($chkpt_via)) {
                        foreach (array_slice($datatest, 1, -1) as $data) {
                            if ($data['chktype'] == $chkpt_via && $chkpt_start != $data['chkid'] && $chkpt_end != $data['chkid'] && $data['chkrepid'] > $chkrepid_start && $data['chkrepid'] < $chkrepid_end) {
                                $chkpt_via = $data['chkid'];
                                break;
                            }
                        }
                    }
                }
            }
            if (!empty($chkpt_via)) {

                //echo"<pre>"; print_r($chkidarr);
                if (!empty($chkidarr) && in_array($chkpt_start, $chkidarr) && in_array($chkpt_via, $chkidarr) && in_array($chkpt_end, $chkidarr)) {
                    $REPORT_Start = array();
                    foreach ($datatest as $data) {
                        $Datacap = new stdClass;
                        $Datacap->chkrepid = $data['chkrepid'];
                        $Datacap->chkid = $data['chkid'];
                        $Datacap->status = $data['status'];
                        $Datacap->date = $data['date'];
                        $REPORT_Start[] = $Datacap;
                    }
                }
            } else {
                foreach ($datatest as $data1) {
                    $Datacap1 = new stdClass;
                    $Datacap1->chkrepid = $data1['chkrepid'];
                    $Datacap1->chkid = $data1['chkid'];
                    $Datacap1->status = $data1['status'];
                    $Datacap1->date = $data1['date'];
                    $REPORT_Start[] = $Datacap1;
                }
            }

            $via_start = array();
            foreach ($REPORT_Start as $chkreport) {
                $via_start[$chkreport->chkrepid] = $chkreport->date;
                if ($chkreport->status == '1' && $chkreport->chkid == $chkpt_start) {
                    if ($flag == 1) {
                        $arr_start = array_slice($arr_start, 0, -1, true);
                        $arr_start[] = $chkreport->chkrepid;
                    } else if ($flag == 0) {
                        $flag = 1;
                        $arr_start[] = $chkreport->chkrepid;
                    }
                } else if ($chkreport->status == '0' && $chkreport->chkid == $chkpt_end && $flag == 1) {
                    $flag = 0;
                    $arr_end[] = $chkreport->chkrepid;
                }
            }
            if (!empty($arr_start) && !empty($arr_end)) {
                $arr_start_via = array();
                $arr_end_via = array();
                $isChkPtTraversed = 0;
                $lastChkPtTraveresed = 0;
                // to check if checkpoint via exists
                if (!empty($chkpt_via)) {
                    foreach ($REPORT_Start as $chkreportvia) {
                        if ($isChkPtTraversed == 1 && $chkreportvia->chkrepid <= $lastChkPtTraveresed) {
                            continue;
                        }
                        $isChkPtTraversed = 0;
                        $lastChkPtTraveresed = 0;
                        for ($i = 0; $i < count($arr_start); $i++) {
                            if (array_key_exists($i, $arr_end)) {
                                $thisdate = strtotime($chkreportvia->date);
                                if ($thisdate >= strtotime($via_start[$arr_start[$i]]) && $thisdate <= strtotime($via_start[$arr_end[$i]])) {
                                    if ($chkreportvia->chkid == $chkpt_via) {
                                        $arr_start_via[] = $arr_start[$i];
                                        $arr_end_via[] = $arr_end[$i];
                                        $isChkPtTraversed = 1;
                                        $lastChkPtTraveresed = $arr_end[$i];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                    if (!isset($arr_start_via)) {
                        return $REPORT_Final;
                    }
                }
                if (isset($arr_start_via) && !empty($arr_start_via)) {
                    $arr_start = array_unique($arr_start_via);
                    $arr_end = array_unique($arr_end_via);
                }
                $arr_startlen = count($arr_start);
                $arr_endlen = count($arr_end);
                if ($arr_startlen > $arr_endlen) {
                    $arr_start = array_slice($arr_start, 0, -1, true);
                }
                for ($i = 0; $i < count($arr_start); $i++) {
                    //$arr_start[$i] ." AND ". $arr_end[$i];echo "</br>";
                    $startelement = $arr_start[$i];
                    $endelement = $arr_end[$i];
                    $start_chkppoint = getChkReport($location, $id, $startelement, $chkpt_start, $STdate, $ETdate, 1);
                    $end_chkppoint = getChkReport($location, $id, $endelement, $chkpt_end, $STdate, $ETdate, 0);
                    $unitno = getunitnotemp($id);
                    $did = GetDevice_byId($id, $_SESSION['customerno']);
                    if (isset($unitno) && $unitno != '') {
                        $userdatestart = date('Y-m-d', strtotime($start_chkppoint->date));
                        $userdatestart = substr($userdatestart, 0, 11);
                        $userdateend = date('Y-m-d', strtotime($end_chkppoint->date));
                        $userdateend = substr($userdateend, 0, 11);
                        $customerno = $_SESSION['customerno'];
                        $locationstart = "../../customer/$customerno/unitno/$unitno/sqlite/$userdatestart.sqlite";
                        $locationend = "../../customer/$customerno/unitno/$unitno/sqlite/$userdateend.sqlite";
                        if (file_exists($locationstart) && file_exists($locationend)) {
                            $firstodometer = getOdometer($id, $locationstart, $start_chkppoint->date);
                            $lastodometer = getOdometer($id, $locationend, $end_chkppoint->date);
                            $intervel = 5;
                            if ($lastodometer < $firstodometer) {
                                $max = GetOdometerMax($location);
                                $lastodometer = $max + $lastodometer;
                            }
                            $distance = round(($lastodometer - $firstodometer) / 1000, 2);
                            $average = getAverage($id);
                            if ($firstodometer != '0' && $lastodometer != '0') {
                                $vehicle = new stdClass();
                                $vehicle->vehicleid = $id;
                                $vehicle->vehicleno = getVehicleName($id);
                                $vehicle->distance = $distance;
                                if ($average > 0) {
                                    $vehicle->fuelconsume = $distance / $average;
                                } else {
                                    $vehicle->fuelconsume = "N/A";
                                }
                                $vehicle->idletime = getIdletime($start_chkppoint->date, $end_chkppoint->date, $did, $intervel, $customerno, $unitno);
                                $vehicle->startdate = $start_chkppoint->date;
                                $vehicle->enddate = $end_chkppoint->date;
                                $vehicle->startcheckpoint = $chkpt_start;
                                $vehicle->endcheckpoint = $chkpt_end;
                                if (!empty($chkpt_via)) {
                                    $vehicle->route = get_checkpointname($chkpt_start) . ' To ' . get_checkpointname($chkpt_end) . ' Via ' . get_checkpointname($chkpt_via);
                                } else {
                                    $vehicle->route = get_checkpointname($chkpt_start) . ' To ' . get_checkpointname($chkpt_end);
                                }
                                $REPORT_Final[] = $vehicle;
                            }
                            //print_r($REPORT_Final);echo "</br>";
                        }
                    }
                }
                return $REPORT_Final;
            }
        }
    }
}

function print_Trip_Report($customerno, $STDate, $EDdate, $Stime, $Etime, $chk_start, $chk_end, $chk_via, $ReportType, $vgroupname = null, $chktype = null, $vehicleid = null) {
    if ($chktype == '2') {
        $checkword = 'Checkpoint Types';
    } elseif ($chktype == '1' || $chktype == NULL) {
        $checkword = 'Checkpoints';
    }
    if ($ReportType == 'trip') {
        $reports = getdailyreport_check_pdf($customerno, $STDate, $EDdate, $Stime, $Etime, $chk_start, $chk_end, $chk_via, $chktype, $vehicleid);
        if (isset($reports)) {
            ?>
            <div style="width:auto; height:30px;">
                <table style="width: auto; border:none;">
                    <tr>
                        <td style="width:430px; border:none;"><img style="width:50%; height: 50%;" src="../../images/elixiaspeed_logo.png" /></td>
                        <td style="width:420px; border:none;">
                            <h3 style="text-transform:uppercase;">Trip Report</h3><br />
                        </td>
                        <td style="width:230px;border:none;">
                            <img src="../../images/elixia_logo_75.png"  /></td>
                    </tr>
                </table>
            </div>
            <hr />
            <h4>
                <div align='center' style='text-align:center;'>
                    From :                            <?php echo $STDate; ?> to :<?php echo $EDdate; ?>
                </div>
            </h4>
            <style type='text/css'>
                table, td { border: solid 1px  #999999; color:#000000; }
                hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
            </style>
            <table>
                <thead>
                    <tr style="background-color: #CCCCCC; border: none; ">
                        <td colspan="100%" style="text-align: center; border: none; height: 30px;">Between <?php echo $checkword . ' <strong>' . get_checkpointname($chk_start, $chktype); ?> To <?php echo get_checkpointname($chk_end, $chktype); ?><?php
                            if (!empty($chk_via)) {
                                echo " Via " . get_checkpointname($chk_via, $chktype);
                            }
                            ?></strong> From <?php echo date("M j Y", strtotime($STDate)); ?> To <?php echo date("M j Y", strtotime($EDdate)); ?> </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;width: 200px;">Vehicle No</td>
                        <td style="text-align: center;">Distance Travelled (Km)</td>
                        <td style="text-align: center;">Fuel Consumed (Lt)</td>
                        <td style="text-align: center;">Time Taken[Hours :Minutes]</td>
                        <td style="text-align: center;">Idle Time[Hours :Minutes]</td>
                        <td style="text-align: center;">Start Date</td>
                        <td style="text-align: center;">End Date</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($reports as $thisvehicle) {
                        if (!empty($thisvehicle)) {
                            foreach ($thisvehicle as $report) {
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $report->vehicleno; ?></td>
                                    <td style="text-align: center;"><?php
                                        if ($report->distance != '0') {
                                            echo $report->distance;
                                        } else {
                                            echo "N/A";
                                        }
                                        ?></td>
                                    <td style="text-align: center;"><?php echo $report->fuelconsume; ?></td>
                                    <td style="text-align: center;"><?php
                                        if ($report->distance != '0') {
                                            echo m2h(getduration($report->enddate, $report->startdate));
                                        } else {
                                            echo "N/A";
                                        }
                                        ?></td>
                                    <td style="text-align: center;"><?php
                                        if ($report->idletime != '') {
                                            echo m2h($report->idletime);
                                        } else {
                                            echo "N/A";
                                        }
                                        ?></td>
                                    <td style="text-align: center;"><?php echo convertDateToFormat($report->startdate, speedConstants::DEFAULT_DATETIME); ?> </td>
                                    <td style="text-align: center;"><?php echo convertDateToFormat($report->enddate, speedConstants::DEFAULT_DATETIME); ?>  </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
    } elseif ($ReportType == 'vehi') {
        $reports = getdailyreport_check_pdf($customerno, $STDate, $EDdate, $Stime, $Etime, $chk_start, $chk_end, $chk_via, $chktype, $vehicleid);
        if (isset($reports)) {
            ?>
            <div style="width:auto; height:30px;">
                <table style="width: auto; border:none;">
                    <tr>
                        <td style="width:430px; border:none;"><img style="width:50%; height: 50%;" src="../../images/elixiaspeed_logo.png" /></td>
                        <td style="width:420px; border:none;">
                            <h3 style="text-transform:uppercase;">Trip Report</h3><br />
                        </td>
                        <td style="width:230px;border:none;">
                            <img src="../../images/elixia_logo_75.png"  /></td>
                    </tr>
                </table>
            </div>
            <hr />
            <h4>
                <div align='center' style='text-align:center;'>
                    From :                            <?php echo $STDate; ?> to :<?php echo $EDdate; ?>
                </div>
            </h4>
            <style type='text/css'>
                table, td { border: solid 1px  #999999; color:#000000; }
                hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
            </style>
            <table>
                <thead>
                    <tr style="background-color: #CCCCCC; border: none; ">
                        <td colspan="100%" style="text-align: center; border: none; height: 30px;">Between <?php echo $checkword . ' <strong>' . get_checkpointname($chk_start, $chktype); ?> To <?php echo get_checkpointname($chk_end, $chktype); ?><?php
                            if (!empty($chk_via)) {
                                echo " Via " . get_checkpointname($chk_via, $chktype);
                            }
                            ?></strong> From <?php echo date("M j Y", strtotime($STDate)); ?> To <?php echo date("M j Y", strtotime($EDdate)); ?> </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; width: 200px;">Vehicle No</td>
                        <td style="text-align: center;">Trip</td>
                        <td style="text-align: center;">Avg. Distance Travelled (Km)</td>
                        <td style="text-align: center;">Avg. Fuel Consumed (Lt)</td>
                        <td style="text-align: center;">Avg, Taken[Hours :Minutes]</td>
                        <td style="text-align: center;">Avg. Idle Time[Hours :Minutes]</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                }
                foreach ($reports as $thisvehicle) {
                    $new_report = array();
                    if (!empty($thisvehicle)) {
                        foreach ($thisvehicle as $report) {
                            $vehicleçheck = $report->vehicleno;
                            $tripcount = 1;
                            if ($vehicleçheck == $report->vehicleno) {
                                $new_report['vehicleno'] = $report->vehicleno;
                                if (isset($new_report['distance'])) {
                                    $new_report['distance'] += $report->distance;
                                } else {
                                    $new_report['distance'] = $report->distance;
                                }
                                if (isset($new_report['fuelconsume'])) {
                                    if ($report->fuelconsume > 0) {
                                        $new_report['fuelconsume'] += $report->fuelconsume;
                                    } else {
                                        $new_report['fuelconsume'] += "N/A";
                                    }
                                } else {
                                    if ($report->fuelconsume > 0) {
                                        $new_report['fuelconsume'] = $report->fuelconsume;
                                    } else {
                                        $new_report['fuelconsume'] = "N/A";
                                    }
                                }
                                if (isset($new_report['idletime'])) {
                                    $new_report['idletime'] += $report->idletime;
                                } else {
                                    $new_report['idletime'] = $report->idletime;
                                }
                                if (isset($new_report['timetaken'])) {
                                    $new_report['timetaken'] += getduration($report->enddate, $report->startdate);
                                } else {
                                    $new_report['timetaken'] = getduration($report->enddate, $report->startdate);
                                }
                                $new_report['startdate'] = $STDate;
                                $new_report['enddate'] = $EDdate;
                                if (isset($new_report['trip'])) {
                                    $new_report['trip'] += $tripcount;
                                } else {
                                    $new_report['trip'] = $tripcount;
                                }
                                $tripcount += 1;
                            }
                        }
                        if (!empty($new_report)) {
                            ?>
                            <tr>
                                <td style="text-align: center;"><?php echo $new_report['vehicleno']; ?></td>
                                <td style="text-align: center;"><?php echo $new_report['trip']; ?></td>
                                <td style="text-align: center;"><?php
                                    if ($new_report['distance'] != '0') {
                                        echo round($new_report['distance'] / $new_report['trip'], 2);
                                    } else {
                                        echo "N/A";
                                    }
                                    ?></td>
                                <td style="text-align: center;"><?php
                                    if ($new_report['fuelconsume'] > 0) {
                                        echo $new_report['fuelconsume'] / $new_report['trip'];
                                    } else {
                                        echo $new_report['fuelconsume'] = "N/A";
                                    }
                                    ?></td>
                                <td style="text-align: center;"><?php
                                    if ($new_report['distance'] != '0') {
                                        echo m2h($new_report['timetaken'] / $new_report['trip']);
                                    } else {
                                        echo "N/A";
                                    }
                                    ?></td>
                                <td style="text-align: center;"><?php
                                    if ($new_report['idletime'] != '') {
                                        echo m2h($new_report['idletime'] / $new_report['trip']);
                                    } else {
                                        echo "N/A";
                                    }
                                    ?></td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
            </tbody>
        </table>
        <?php
    }
}

function print_Trip_report_csv($customerno, $STDate, $EDdate, $Stime, $Etime, $chk_start, $chk_end, $chk_via, $ReportType, $chktype = null, $vehicleid = null) {
    if ($chktype == '2') {
        $checkword = 'Checkpoint Types';
    } elseif ($chktype == '1' || $chktype == NULL) {
        $checkword = 'Checkpoints';
    }
    if ($ReportType == 'trip') {
        $reports = getdailyreport_check_pdf($customerno, $STDate, $EDdate, $Stime, $Etime, $chk_start, $chk_end, $chk_via, $chktype, $vehicleid);
        // print_r($reports);
        if (isset($reports)) {
            ?>
            <div style="width:auto; height:30px;">
                <table style="width: auto; border:none;">
                    <tr>
                        <td style="width:420px; border:none;">
                            <h3 style="text-transform:uppercase;">Trip Report</h3><br />
                        </td>
                    </tr>
                </table>
            </div>
            <hr />
            <h4>
                <div align='center' style='text-align:center;'>
                    From : <?php echo $STDate; ?> to :<?php echo $EDdate; ?>
                </div>
            </h4>
            <table style="width: auto; border:none;">
                <tr>
                <tr style="background-color: #CCCCCC; border: none; ">
                    <td colspan="7" style="text-align: center; border: none; height: 30px;">Between <?php echo $checkword . ' <strong>' . get_checkpointname($chk_start, $chktype); ?> To <?php echo get_checkpointname($chk_end, $chktype); ?><?php
                        if (!empty($chk_via)) {
                            echo " Via " . get_checkpointname($chk_via, $chktype);
                        }
                        ?> </strong> From <?php echo date("M j Y", strtotime($STDate)); ?> To <?php echo date("M j Y", strtotime($EDdate)); ?> </td>
                </tr>
            </tr>
            </table>
            <style type='text/css'>
                table, td { border: solid 1px  #999999; color:#000000; }
                hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
            </style>
            <table>
                <thead>
                    <tr>
                        <?php
                        if ($chktype == '2') {
                            echo '<td style="text-align: center;">Sr No</td>';
                        }
                        ?>
                        <td style="text-align: center;width: 200px;">Vehicle No</td>
                        <?php if ($chktype == '2') {
                            echo '<td style="text-align: center;">Checkpoint Route</td>';
                        } ?>
                        <td style="text-align: center;">Distance Travelled (Km)</td>
                        <td style="text-align: center;">Fuel Consumed (Lt)</td>
                        <td style="text-align: center;">Time Taken[Hours :Minutes]</td>
                        <td style="text-align: center;">Idle Time[Hours :Minutes]</td>
                        <td style="text-align: center;">Start Date</td>
                        <td style="text-align: center;">End Date</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($reports as $thisvehicle) {
                        if (!empty($thisvehicle)) {
                            $i = 1;
                            foreach ($thisvehicle as $report) {
                                ?>
                                <tr>
                                    <?php
                                    if ($chktype == '2') {
                                        echo '<td  style="text-align: center;">' . $i . '</td>';
                                    }
                                    ?>
                                    <td style="text-align: center;"><?php echo $report->vehicleno; ?></td>
                                    <?php
                                    if ($chktype == '2') {
                                        echo '<td>' . $report->route . '</td>';
                                    }
                                    ?>
                                    <td style="text-align: center;"><?php
                                        if ($report->distance != '0') {
                                            echo $report->distance;
                                        } else {
                                            echo "N/A";
                                        }
                                        ?></td>
                                    <td style="text-align: center;"><?php echo $report->fuelconsume; ?></td>
                                    <td style="text-align: center;"><?php
                                        if ($report->distance != '0') {
                                            echo m2h(getduration($report->enddate, $report->startdate));
                                        } else {
                                            echo "N/A";
                                        }
                                        ?></td>
                                    <td style="text-align: center;"><?php
                                        if ($report->idletime != '') {
                                            echo m2h($report->idletime);
                                        } else {
                                            echo "N/A";
                                        }
                                        ?></td>
                                    <td style="text-align: center;"><?php echo convertDateToFormat($report->startdate, speedConstants::DEFAULT_DATETIME) ?>
                                    <td style="text-align: center;"><?php echo convertDateToFormat($report->enddate, speedConstants::DEFAULT_DATETIME); ?>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }
    } elseif ($ReportType == 'vehi') {
        $reports = getdailyreport_check_pdf($customerno, $STDate, $EDdate, $Stime, $Etime, $chk_start, $chk_end, $chk_via, $chktype, $vehicleid);
        if (isset($reports)) {
            ?>
            <div style="width:auto; height:30px;">
                <table style="width: auto; border:none;">
                    <tr>
                        <td style="width:420px; border:none;">
                            <h3 style="text-transform:uppercase;">Trip Report</h3><br />
                        </td>
                    </tr>
                </table>
            </div>
            <hr />
            <h4>
                <div align='center' style='text-align:center;'>
                    From :                            <?php echo $STDate; ?> to :<?php echo $EDdate; ?>
                </div>
            </h4>
            <table style="width: auto; border:none;">
                <tr>
                <tr style="background-color: #CCCCCC; border: none; ">
                    <td colspan="6" style="text-align: center; border: none; height: 30px;">Between <?php echo $checkword . ' <strong>' . get_checkpointname($chk_start, $chktype); ?> To <?php echo get_checkpointname($chk_end, $chktype); ?><?php
                        if (!empty($chk_via)) {
                            echo " Via " . get_checkpointname($chk_via, $chktype);
                        }
                        ?> </strong> From <?php echo date("M j Y", strtotime($STDate)); ?> To <?php echo date("M j Y", strtotime($EDdate)); ?> </td>
                </tr>
            </tr>
            </table>
            <style type='text/css'>
                table, td { border: solid 1px  #999999; color:#000000; }
                hr.style-six { border: 0; height: 0; border-top: 1px solid rgba(0, 0, 0, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.3); }
            </style>
            <table>
                <thead>
                    <tr>
                        <td style="text-align: center; width: 200px;">Vehicle No</td>
                        <td style="text-align: center;">Trip</td>
                        <td style="text-align: center;">Avg. Distance Travelled (Km)</td>
                        <td style="text-align: center;">Avg. Fuel Consumed (Lt)</td>
                        <td style="text-align: center;">Avg. Time Taken[Hours :Minutes]</td>
                        <td style="text-align: center;">Avg. Idle Time[Hours :Minutes]</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                }
                foreach ($reports as $thisvehicle) {
                    $new_report = array();
                    $new_report['distance'] = 0;
                    $new_report['fuelconsume'] = 0;
                    $new_report['idletime'] = 0;
                    $new_report['timetaken'] = 0;
                    $new_report['trip'] = 0;
                    if (!empty($thisvehicle)) {
                        foreach ($thisvehicle as $report) {
                            $vehicleçheck = $report->vehicleno;
                            $tripcount = 1;
                            if ($vehicleçheck == $report->vehicleno) {
                                $new_report['vehicleno'] = $report->vehicleno;
                                $new_report['distance'] += $report->distance;
                                if ($report->fuelconsume > 0) {
                                    $new_report['fuelconsume'] += $report->fuelconsume;
                                } else {
                                    $new_report['fuelconsume'] += "N/A";
                                }
                                $new_report['idletime'] += $report->idletime;
                                $new_report['timetaken'] += getduration($report->enddate, $report->startdate);
                                $new_report['startdate'] = $STDate;
                                $new_report['enddate'] = $EDdate;
                                $new_report['trip'] += $tripcount;
                                $tripcount += 1;
                            }
                        }
                        if (!empty($new_report)) {
                            ?>
                            <tr>
                                <td style="text-align: center;"><?php echo $new_report['vehicleno']; ?></td>
                                <td style="text-align: center;"><?php echo $new_report['trip']; ?></td>
                                <td style="text-align: center;"><?php
                                    if ($new_report['distance'] != '0') {
                                        echo round($new_report['distance'] / $new_report['trip'], 2);
                                    } else {
                                        echo "N/A";
                                    }
                                    ?></td>
                                <td style="text-align: center;"><?php
                                    if ($new_report['fuelconsume'] > 0) {
                                        echo $new_report['fuelconsume'] / $new_report['trip'];
                                    } else {
                                        echo $new_report['fuelconsume'] = "N/A";
                                    }
                                    ?></td>
                                <td style="text-align: center;"><?php
                                    if ($new_report['distance'] != '0') {
                                        echo m2h($new_report['timetaken'] / $new_report['trip']);
                                    } else {
                                        echo "N/A";
                                    }
                                    ?></td>
                                <td style="text-align: center;"><?php
                                    if ($new_report['idletime'] != '') {
                                        echo m2h($new_report['idletime'] / $new_report['trip']);
                                    } else {
                                        echo "N/A";
                                    }
                                    ?></td>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
            </tbody>
        </table>
        <?php
    }
}

function getOdometer($vehicleid, $location, $date) {
    $path = "sqlite:$location";
    if (file_exists($location)) {
        $db = new PDO($path);
        $Query = "SELECT * FROM vehiclehistory where vehicleid = $vehicleid AND lastupdated >= '$date' order by vehiclehistoryid Limit 1 ";
        $result = $db->query($Query);
        if (isset($result) && $result != '') {
            foreach ($result as $row) {
                return $row['odometer'];
            }
        } else {
            return 0;
        }
    }
}

function getIdletime($STdate, $EDdate, $id, $intervel, $customerno, $unitno) {
    $totaldays = gendays($STdate, $EDdate);
    $SHour = date("H:i:s", strtotime($STdate));
    $EHour = date("H:i:s", strtotime($EDdate));
    $days = array();
    $count = count($totaldays);
    $endelement = end($totaldays);
    $firstelement = $totaldays[0];
    $finalreport = '';
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                if (file_exists($location)) {
                    //Date Check Operations
                    $data = null;
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                    if (file_exists($location)) {
                        $location = "sqlite:" . $location;
                        $data = getstoppage_fromsqlite($location, $id, $intervel, $SHour, null, $userdate);
                    }
                    if ($data != null && count($data) > 0) {
                        $days = array_merge($days, $data);
                    }
                }
            } else if ($count > 1 && $userdate == $endelement) {
                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                if (file_exists($location)) {
                    //Date Check Operations
                    $data = null;
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                    if (file_exists($location)) {
                        $location = "sqlite:" . $location;
                        $data = getstoppage_fromsqlite($location, $id, $intervel, null, $EHour, $userdate);
                    }
                    if ($data != null && count($data) > 0) {
                        $days = array_merge($days, $data);
                    }
                }
            } else if ($count == 1) {
                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                if (file_exists($location)) {
                    //Date Check Operations
                    $data = null;
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                    if (file_exists($location)) {
                        $location = "sqlite:" . $location;
                        $data = getstoppage_fromsqlite($location, $id, $intervel, $SHour, $EHour, $userdate);
                    }
                    if ($data != null && count($data) > 0) {
                        $days = array_merge($days, $data);
                    }
                } else {
                    echo 'File Does not exist';
                }
            } else {
                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                if (file_exists($location)) {
                    //Date Check Operations
                    $data = null;
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                    if (file_exists($location)) {
                        $location = "sqlite:" . $location;
                        $data = getstoppage_fromsqlite($location, $id, $intervel, null, null, $userdate);
                    }
                    if ($data != null && count($data) > 0) {
                        $days = array_merge($days, $data);
                    }
                }
            }
        }
    }
    //print_r($days);
    if ($days != null && count($days) > 0) {
        $finalreport = create_stoppagehtml_from_report($days);
    }
    //print_r($finalreport);
    return $finalreport;
}

function getIdletime_by_vehicle($STdate, $EDdate, $id, $intervel, $customerno, $unitno) {
    $totaldays = gendays($STdate, $EDdate);
    $currentdate = date("Y-m-d H:i:s");
    $currentdate = substr($currentdate, '0', 11);
    $SHour = date("H:i:s", strtotime($STdate));
    $EHour = date("H:i:s", strtotime($EDdate));
    $days = array();
    $count = count($totaldays);
    $endelement = end($totaldays);
    $firstelement = array_shift(array_values($totaldays));
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                if (file_exists($location)) {
                    //Date Check Operations
                    $data = null;
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                    if (file_exists($location)) {
                        $location = "sqlite:" . $location;
                        $data = getstoppage_fromsqlite($location, $id, $intervel, $SHour, null, $userdate);
                    }
                    if ($data != null && count($data) > 0) {
                        $days = array_merge($days, $data);
                    }
                }
            } else if ($count > 1 && $userdate == $endelement) {
                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                if (file_exists($location)) {
                    //Date Check Operations
                    $data = null;
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                    if (file_exists($location)) {
                        $location = "sqlite:" . $location;
                        $data = getstoppage_fromsqlite($location, $id, $intervel, null, $EHour, $userdate);
                    }
                    if ($data != null && count($data) > 0) {
                        $days = array_merge($days, $data);
                    }
                }
            } else if ($count == 1) {
                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                if (file_exists($location)) {
                    //Date Check Operations
                    $data = null;
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                    if (file_exists($location)) {
                        $location = "sqlite:" . $location;
                        $data = getstoppage_fromsqlite($location, $id, $intervel, $SHour, $EHour, $userdate);
                    }
                    if ($data != null && count($data) > 0) {
                        $days = array_merge($days, $data);
                    }
                } else {
                    echo 'File Does not exist';
                }
            } else {
                $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                if (file_exists($location)) {
                    //Date Check Operations
                    $data = null;
                    $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
                    if (file_exists($location)) {
                        $location = "sqlite:" . $location;
                        $data = getstoppage_fromsqlite($location, $id, $intervel, null, null, $userdate);
                    }
                    if ($data != null && count($data) > 0) {
                        $days = array_merge($days, $data);
                    }
                }
            }
        }
    }
    //print_r($days);
    if ($days != null && count($days) > 0) {
        $finalreport = create_stoppagehtml_from_report_by_vehicle($days);
    }
    //print_r($finalreport);
    return $finalreport;
}

function getstoppage_fromsqlite($location, $deviceid, $holdtime, $Shour, $Ehour, $userdate) {
    $devices = array();
    $query = "SELECT devicehistory.lastupdated, vehiclehistory.odometer, devicehistory.devicelat, vehiclehistory.vehicleid, devicehistory.devicelong
                                                                                    from devicehistory INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
                                                                                    WHERE devicehistory.deviceid= $deviceid AND devicehistory.status!='F'";
    if ($Shour != null) {
        $query .= " AND devicehistory.lastupdated > '$userdate $Shour'";
    }
    if ($Ehour != null) {
        $query .= " AND devicehistory.lastupdated < '$userdate $Ehour'";
    }
    $query .= "  ORDER BY devicehistory.lastupdated ASC";
    try {
        $database = new PDO($location);
        $result = $database->query($query);
        if (!empty($result) && $result != '') {
            $lastupdated = "";
            $lastodometer = "";
            $pusharray = 1;
            foreach ($result as $row) {
                //echo $row['odometer'];
                if ($lastodometer == "") {
                    $lastodometer = $row["odometer"];
                    $lastupdated = $row['lastupdated'];
                    $device = new stdClass();
                    $device->starttime = $row['lastupdated'];
                    $device->deviceid = $row['vehicleid'];
                }
                if (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) > $holdtime && $row["odometer"] - $lastodometer < 100 && $pusharray == 1) {
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->deviceid = $row['vehicleid'];
                    $lastodometer = $row["odometer"];
                    $lastupdated = $row['lastupdated'];
                    $pusharray = 0;
                } else {
                    if ($row["odometer"] - $lastodometer > 25) {
                        if ($pusharray == 0) {
                            $device->endtime = $row['lastupdated'];
                            $devices[] = $device;
                        }
                        $lastodometer = $row["odometer"];
                        $lastupdated = $row['lastupdated'];
                        $device = new stdClass();
                        $device->starttime = $row['lastupdated'];
                        $device->deviceid = $row['vehicleid'];
                        $pusharray = 1;
                    }
                }
            }
            if ($pusharray == 0) {
                $device->endtime = $row['lastupdated'];
                $device->deviceid = $row['vehicleid'];
                $devices[] = $device;
            }
        }
    } catch (PDOException $e) {
        die($e);
    }
    return $devices;
}

function create_stoppagehtml_from_report($datarows) {
    $display = '';
    if (isset($datarows)) {
        $x = 0;
        $minutesdiff = 0;
        foreach ($datarows as $change) {
            $x++;
            //echo $change->endtime; echo $change->starttime;
            $secdiff = strtotime($change->endtime) - strtotime($change->starttime);
            $minutesdiff += $secdiff;
            $display = $minutesdiff / 60;
        }
    }
    return $display;
}

function create_stoppagehtml_from_report_by_vehicle($datarows) {
    $display = '';
    if (isset($datarows)) {
        $x = 0;
        foreach ($datarows as $change) {
            $x++;
            //echo $change->endtime; echo $change->starttime;
            $secdiff = strtotime($change->endtime) - strtotime($change->starttime);
            $minutesdiff = floor($secdiff / 60);
            $display = $minutesdiff;
        }
    }
    return $display;
}

function getdailyreport_byID($STdate, $EDdate, $vehicleid) {
    $totaldays = gendays($STdate, $EDdate);
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    $DATA = null;
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_ByID($location, $totaldays, $vehicleid);
    }
    return $DATA;
}

function getfuelcomsumptionpdf($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vehicleid, $vgroupname = null) {
    $currentdate = substr(date("Y-m-d H:i:s"), '0', 11);
    $totaldays = gendays($STdate, $EDdate);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_ByID_PDF($location, $totaldays, $vehicleid, $customerno, $usemaintainance, $usehierarchy, $groupid);
    }
    if (isset($DATA)) {
        $vehicles = GetVehicles_SQLite();
        $title = 'Fuel Consumption Report';
        $subTitle = array(
            "Vehicle No: {$vehicles[$vehicleid]['vehicleno']}",
            "Start Date: $STdate",
            "End Date: $EDdate",
        );

        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        echo pdf_header($title, $subTitle);
        if (isset($DATA)) {
            ?>
            <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tr style='background-color:#CCCCCC;font-weight:bold;'>
                    <td style="width: 300px; text-align: center;" >Vehicle No.</td>
                    <td style="width: 200px; text-align: center;">Date</td>
                    <td style="width: 250px; text-align: center;" >Distance Travelled(In Km)</td>
                    <td style="width: 250px; text-align: center;" >Fuel Consumed(In Lt.)</td>
                </tr>
                <?php
                $totalfuel = 0;
                $totaltravel = 0;
                foreach ($DATA as $report) {
                    ?>
                    <tr>
                        <td><?php echo $vehicles[$report->vehicleid]['vehicleno']; ?></td>
                        <td><?php echo date('d-m-Y', $report->date); ?></td>
                        <?php $report->totaldistancetravelled = $report->totaldistance / 1000; ?>
                        <td><?php echo round($report->totaldistancetravelled, 2); ?></td>
                        <?php $totaltravel += round($report->totaldistancetravelled, 2); ?>
                        <?php $report->consumedfuel = ($report->average != 0) ? $report->totaldistancetravelled / $report->average : 0; ?>
                        <td><?php echo round($report->consumedfuel, 2); ?></td>
                    <?php $totalfuel += round($report->consumedfuel, 2); ?>
                    </tr>
                    <?php
                }
                echo "<tr>
                                                                                                <td colspan='2' style='font-weight:bold;'>Total</td>
                                                                                                <td>" . $totaltravel . "</td>
                                                                                                <td>" . $totalfuel . "</td>
                                                                                            </tr>";
                ?>
            </table>
            <?php
        }
        ?>
        <?php
    }
}

function getfuelconsumptioncsv($customerno, $usemaintainance, $usehierarchy, $groupid, $STdate, $EDdate, $vehicleid, $vgroupname = null) {
    $totaldays = gendays($STdate, $EDdate);
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    if (file_exists($location)) {
        $DATA = GetDailyReport_Data_ByID_PDF($location, $totaldays, $vehicleid, $customerno, $usemaintainance, $usehierarchy, $groupid);
    }
    if (isset($DATA)) {
        $vehicles = GetVehicles_SQLite();
        $title = 'Fuel Consumption Report';
        $subTitle = array(
            "Vehicle No: {$vehicles[$vehicleid]['vehicleno']}",
            "Start Date: $STdate",
            "End Date: $EDdate",
        );
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        echo excel_header($title, $subTitle);
        if (isset($DATA)) {
            ?>
            <table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                <tr style='background-color:#CCCCCC;font-weight:bold;'>
                    <td style="width: 300px; text-align: center;" >Vehicle No.</td>
                    <td style="width: 200px; text-align: center;">Date</td>
                    <td style="width: 250px; text-align: center;" >Distance Travelled(In Km)</td>
                    <td style="width: 250px; text-align: center;" >Fuel Consumed(In Lt.)</td>
                </tr>
                <?php
                $totalfuel = 0;
                $totaltravel = 0;
                foreach ($DATA as $report) {
                    ?>
                    <tr>
                        <td><?php echo $vehicles[$report->vehicleid]['vehicleno']; ?></td>
                        <td><?php echo date('d-m', $report->date); ?></td>
                        <?php $report->totaldistancetravelled = $report->totaldistance / 1000; ?>
                        <td><?php echo round($report->totaldistancetravelled, 2); ?></td>
                        <?php $totaltravel += round($report->totaldistancetravelled, 2); ?>
                        <?php $report->consumedfuel = ($report->average != 0) ? $report->totaldistancetravelled / $report->average : 0; ?>
                        <td><?php echo round($report->consumedfuel, 2); ?></td>
                    <?php $totalfuel += round($report->consumedfuel, 2); ?>
                    </tr>
                    <?php
                }
                echo "<tr>
                                                                                            <td colspan='2' style='font-weight:bold;'>Total</td>
                                                                                            <td>" . $totaltravel . "</td>
                                                                                            <td>" . $totalfuel . "</td>
                                                                                        </tr>";
                ?>
            </table>
            <?php
        }
        ?>
        <?php
    }
}

function generate_maintenance_fuel_html($fuels) {
    $html = '';
    $html .= "<tr style='background-color:#CCCCCC;font-weight:bold;'>
                                                                            <td>VehNo</td>
                                                                            <td>trans Id</td>
                                                                            <td>Seat<br>Capacity</td>
                                                                            <td>Fuel</td>
                                                                            <td>Amount</td>
                                                                            <td>Rate</td>
                                                                            <td>Ref.No</td>
                                                                            <td>Start Km</td>
                                                                            <td>End Km</td>
                                                                            <td>Net Km</td>
                                                                            <td>Avg</td>
                                                                            <td>Dealer</td>
                                                                            <td>Date</td>
                                                                            <td>Additional<br>Amt</td>
                                                                            <td>Notes</td>
                                                                            <td>Slip No</td>
                                                                            <td>Cheque No</td>
                                                                            <td>Cheque Amount</td>
                                                                            <td>Cheque Date </td>
                                                                            <td>TDS Amount</td>
                                                                        </tr>";
    $i = 1;
    if (!empty($fuels)) {
        foreach ($fuels as $fuel) {
            $html .= "<tr>
                                                                                <td>{$fuel->vehicleno}</td> <td>{$fuel->transid}</td><td>{$fuel->seatcapacity}</td><td>{$fuel->fuel}</td><td>{$fuel->amount}</td>
                                                                                <td>" . round($fuel->rate, 2) . "</td>
                                                                                <td>{$fuel->refno}</td><td>{$fuel->openingkm}</td><td>{$fuel->endingkm}</td>";
            $html .= "<td>{$fuel->netkm}</td>
                                                                                <td>{$fuel->average}</td><td>{$fuel->dealername}</td>
                                                                                <td>" . date('d-m-Y', strtotime($fuel->addedon)) . "</td>
                                                                                    <td>" . $fuel->additional_amount . "</td>
                                                                                        <td style='display: inline-block; width:150px;'>" . $fuel->notes . "</td>
                                                                                            <td>" . $fuel->ofasnumber . "</td>
                                                                                            <td>" . $fuel->chequeno . "</td>
                                                                                            <td>" . $fuel->chequeamt . "</td>
                                                                                            <td>" . $fuel->chequedate . "</td>
                                                                                            <td>" . $fuel->tdsamt . "</td>

                                                                            </tr>";
        }
    } else {
        $html .= "<td colspan='100'>Data not available</td>";
    }
    $html .= "</tbody>";
    $html .= "</table>";
    return $html;
}

function generate_maintenance_fuel_xls($vid, $vno, $startdate, $enddate, $dealerid, $customerno, $sortdata_arr = null, $vgroupname = null) {
    $finalreport = '';
    $vehicleid = (int) $vid;
    $dealerid = (int) $dealerid;
    $sdate = strtotime($startdate . ' 00:00');
    $edate = strtotime($enddate . ' 23:59');
    if ($sdate > $edate) {
        echo "Start date is greater then end date";
    } else if ($vno == '') {
        $fuels = getfilteredfuelmaintenance($vno == null, $dealerid, $sdate, $edate, $sortdata_arr);
    } else {
        $fuels = getfilteredfuelmaintenance($vno, $dealerid, $sdate, $edate, $sortdata_arr);
    }
    $middlecolumn = null;
    if (isset($fuels[1]) && !empty($fuels[1])) {
        $middlecolumn = '<td style="text-align:left;border-right:none; width:290px;font-weight:bold;"><div> <span style="text-align:center; "> <u>Fuel History</u> </span><br>
                                                                        Fuel Consumed(lts) : ' . $fuels[1]['totalfuel'] . '<br/> '
                . ' Total Amount(Rs.) : ' . $fuels[1]['totalamount'] . '<br/>'
                . ' Total Net Kms : ' . $fuels[1]['totalnet'] . '<br/>'
                . 'Consolidated Average : ' . $fuels[1]['consolavg'] . '<br/>'
                . '  </div></td>';
    }
    $title = 'Fuel History';
    $subTitle = array(
        "Vehicle No: $vno",
        "Start Date: $startdate",
        "End Date: $enddate",
    );

    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }
    $finalreport .= excel_header($title, $subTitle, null, $middlecolumn);
    $finalreport .= "<table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                                                    <tbody>";
    $finalreport .= generate_maintenance_fuel_html($fuels[0]);
    echo $finalreport;
}

function generate_maintenance_fuel_pdf($vid, $vno, $startdate, $enddate, $dealerid, $customerno, $sortdata_arr = null, $vgroupname = null) {
    $vehicleid = (int) $vid;
    $dealerid = (int) $dealerid;
    $sdate = strtotime($startdate . ' 00:00');
    $edate = strtotime($enddate . ' 23:59');
    $finalreport = '';
    if ($sdate > $edate) {
        echo "Start date is greater then end date";
    } else if ($vno == '') {
        $fuels = getfilteredfuelmaintenance($vno == null, $dealerid, $sdate, $edate, $sortdata_arr);
    } else {
        $fuels = getfilteredfuelmaintenance($vno, $dealerid, $sdate, $edate, $sortdata_arr);
    }
    $middlecolumn = null;
    if (isset($fuels[1]) && !empty($fuels[1])) {
        $middlecolumn = '<td style="text-align:left;border-right:none; width:290px;font-weight:bold;"><div> <span style="text-align:center; "> <u>Fuel History</u> </span><br>
                                                                            Fuel Consumed(lts) : ' . $fuels[1]['totalfuel'] . '<br/> '
                . ' Total Amount(Rs.) : ' . number_format(round($fuels[1]['totalamount'], 2), 2) . '<br/>'
                . ' Total Net Kms : ' . number_format($fuels[1]['totalnet'], 2) . '<br/>'
                . 'Consolidated Average : ' . number_format($fuels[1]['consolavg'], 2) . '<br/>'
                . '  </div></td>';
    }
    $title = 'Fuel History';
    $subTitle = array(
        "Vehicle No: $vno",
        "Start Date: $startdate",
        "End Date: $enddate",
    );

    if (!is_null($vgroupname)) {
        $subTitle[] = "Group-name: $vgroupname";
    }

    $finalreport .= pdf_header($title, $subTitle, null, $middlecolumn);
    $finalreport .= "
        <table id='search_table_2' align='center' style='width: auto; font-size:14px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                                                                           <tbody>";
    $finalreport .= generate_maintenance_fuel_html($fuels[0]);
    echo $finalreport;
}

function getunitnotemp($vehicleid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getuidfromvehicleid($vehicleid);
    return $unitno;
}

function getunitnotemp_pdf($vehicleid, $customerno) {
    $um = new UnitManager($customerno);
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
    $DATAS = array();
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
    $totaldays = array();
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
                if ($DATA != null) {
                    $DATAS = array_merge($DATAS, $DATA);
                }

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
    $DATAS = array();
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
    $totaldays = array();
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
                if ($DATA != null) {
                    $DATAS = array_merge($DATAS, $DATA);
                }

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

function getConsumedFuel($id, $totaldist, $date) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $consumed = $vehiclemanager->getConsumedFuel($id, $totaldist, $date);
    return $consumed;
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
    return $diff / 60;
}

function create_html_from_report($datarows, $acinvert) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
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
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
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
    $display .= "<table align = 'center'><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
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
    $lastdate = null;
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
                $display .= "<tr><th align='center' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $i++;
            }
            $currentdate = date("Y-m-d H:i:s");
            $currentdate = substr($currentdate, '0', 11);
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $display .= "<tr><td>$change->starttime</td><td>$change->endtime</td>";
            //            if($change->ignition==1)
            //            {
            //                $display .= '<td>ON</td>';
            //            }
            //            else
            //            {
            //                $display .= '<td>OFF</td>';
            //            }
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
    $display .= "<table align = 'center'><thead><tr><th colspan='100%'>Statistics</th></tr></thead>";
    $totaltime = 1440 * $count + $totalminute;
    $totaltime = round($totaltime);
    if ($acinvert == 1) {
        $offtime = $totaltime - $idletime;
        $display .= "<tbody><tr><td>Total Gen Set ON Time = $idletime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr></tbody></table>";
    } else {
        $offtime = $totaltime - $runningtime;
        $display .= "<tbody><tr><td>Total Gen Set ON Time = $runningtime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr></tbody></table>";
    }
    return $display;
}

function create_temphtml_from_report($datarows, $vehicle) {
    $i = 1;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    $temp_conversion = new TempConversion();
    $temp_conversion->use_humidity = $_SESSION["use_humidity"];
    $temp_conversion->switch_to = $_SESSION["switch_to"];
    $temp_conversion->unit_type = $vehicle->get_conversion;
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
                $display .= "<tr><th align='center' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $i++;
            }
            $currentdate = date("Y-m-d H:i:s");
            $currentdate = substr($currentdate, '0', 11);
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $display .= "<tr><td>$change->starttime</td>";
            $location = get_location_detail($change->devicelat, $change->devicelong);
            $display .= "<td>$location</td>";
            // Temperature Sensors
            // Temperature Sensor
            if ($_SESSION['temp_sensors'] == 1) {
                $temp = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $temp_conversion->rawtemp = $change->$s;
                    $temp = getTempUtil($temp_conversion);
                } else {
                    $temp = '-';
                }

                if ($temp != '-' && $temp != "Not Active") {
                    $display .= "<td>$temp</td>";
                } else {
                    $display .= "<td>$temp</td>";
                }
            }
            if ($_SESSION['temp_sensors'] == 2) {
                $temp1 = 'Not Active';
                $temp2 = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $temp_conversion->rawtemp = $change->$s;
                    $temp1 = getTempUtil($temp_conversion);
                } else {
                    $temp1 = '-';
                }

                $s = "analog" . $vehicle->tempsen2;
                if ($vehicle->tempsen2 != 0 && $change->$s != 0) {
                    $temp_conversion->rawtemp = $change->$s;
                    $temp2 = getTempUtil($temp_conversion);
                } else {
                    $temp2 = '-';
                }

                if ($temp1 != '-' && $temp1 != "Not Active") {
                    $display .= "<td>$temp1</td>";
                } else {
                    $display .= "<td>$temp1</td>";
                }
                if ($temp2 != '-' && $temp2 != "Not Active") {
                    $display .= "<td>$temp2</td>";
                } else {
                    $display .= "<td>$temp2</td>";
                }
            }
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= '</table>';
    return $display;
}

function create_temppdf_from_report($datarows, $vehicle) {
    $i = 1;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    $temp_conversion = new TempConversion();
    $temp_conversion->use_humidity = $_SESSION["use_humidity"];
    $temp_conversion->switch_to = $_SESSION["switch_to"];
    $temp_conversion->get_conversion = $vehicle->get_conversion;
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
                if ($_SESSION["temp_sensors"] == 2) {
                    $display .= "<tr><td colspan='4' style='width:900px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                } elseif ($_SESSION["temp_sensors"] == 1) {
                    $display .= "<tr><td colspan='3' style='width:750px;height:auto;'>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                }
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                if ($_SESSION["temp_sensors"] == 2) {
                    $display .= "
                    <tr style='background-color:#CCCCCC;'>
                        <td style='width:150px;height:auto;'>Time</td>
                        <td style='width:450px;height:auto;'>Location</td>
                        <td style='width:150px;height:auto;'>Temperature 1</td>
                        <td style='width:150px;height:auto;'>Temperature 2</td>
                    </tr>";
                } elseif ($_SESSION["temp_sensors"] == 1) {
                    $display .= "
                    <tr style='background-color:#CCCCCC;'>
                        <td style='width:150px;height:auto;'>Time</td>
                        <td style='width:450px;height:auto;'>Location</td>
                        <td style='width:150px;height:auto;'>Temperature</td>
                    </tr>";
                }
                $i++;
            }
            $currentdate = date("Y-m-d H:i:s");
            $currentdate = substr($currentdate, '0', 11);
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $display .= "<tr><td style='width:150px;height:auto;'>$change->starttime</td>";
            $location = get_location_detail($change->devicelat, $change->devicelong);
            $display .= "<td style='width:450px;height:auto;'>$location</td>";
            // Temperature Sensors
            // Temperature Sensor
            if ($_SESSION['temp_sensors'] == 1) {
                $temp = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $temp_conversion->rawtemp = $change->$s;
                    $temp = getTempUtil($temp_conversion);
                } else {
                    $temp = '-';
                }

                if ($temp != '-' && $temp != "Not Active") {
                    $display .= "<td style='width:150px;height:auto;'>$temp &deg; C</td>";
                } else {
                    $display .= "<td style='width:150px;height:auto;'>$temp</td>";
                }
            }
            if ($_SESSION['temp_sensors'] == 2) {
                $temp1 = 'Not Active';
                $temp2 = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $temp_conversion->rawtemp = $change->$s;
                    $temp1 = getTempUtil($temp_conversion);
                } else {
                    $temp1 = '-';
                }

                $s = "analog" . $vehicle->tempsen2;
                if ($vehicle->tempsen2 != 0 && $change->$s != 0) {
                    $temp_conversion->rawtemp = $change->$s;
                    $temp2 = getTempUtil($temp_conversion);
                } else {
                    $temp2 = '-';
                }

                if ($temp1 != '-' && $temp1 != "Not Active") {
                    $display .= "<td style='width:150px;height:auto;'>$temp1 &deg; C</td>";
                } else {
                    $display .= "<td style='width:150px;height:auto;'>$temp1</td>";
                }
                if ($temp2 != '-' && $temp2 != "Not Active") {
                    $display .= "<td style='width:150px;height:auto;'>$temp2 &deg; C</td>";
                } else {
                    $display .= "<td style='width:150px;height:auto;'>$temp2</td>";
                }
            }
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= '</table><br/>';
    $display .= "
    <page_footer>
    [[page_cu]]/[[page_nb]]
    </page_footer>";
    $formatdate1 = date(speedConstants::DAFAULT_DATETIME);
    $display .= "<div align='right' style='text-align:center;'> Report Generated On: $formatdate1 </div>";
    return $display;
}

function create_tempxls_from_report($datarows, $vehicle) {
    $i = 1;
    $totalminute = 0;
    $lastdate = null;
    $display = '';
    $temp_conversion = new TempConversion();
    $temp_conversion->use_humidity = $_SESSION["use_humidity"];
    $temp_conversion->switch_to = $_SESSION["switch_to"];
    $temp_conversion->unit_type = $vehicle->get_conversion;
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
                if ($_SESSION["temp_sensors"] == 2) {
                    $display .= "<tr><td align='center' colspan='4' style=''>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                } elseif ($_SESSION["temp_sensors"] == 1) {
                    $display .= "<tr><td align='center' colspan='3' style=''>" . date("d-m-Y", strtotime($change->endtime)) . "</td></tr>";
                }
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                if ($_SESSION["temp_sensors"] == 2) {
                    $display .= "
                    <tr style='background-color:#CCCCCC;'>
                        <td style='width:150px;height:auto;'>Time</td>
                        <td style='width:450px;height:auto;'>Location</td>
                        <td style='width:150px;height:auto;'>Temperature 1 &deg; C</td>
                        <td style='width:150px;height:auto;'>Temperature 2 &deg; C</td>
                    </tr>";
                } elseif ($_SESSION["temp_sensors"] == 1) {
                    $display .= "
                    <tr style='background-color:#CCCCCC;'>
                        <td style='width:150px;height:auto;'>Time</td>
                        <td style='width:450px;height:auto;'>Location</td>
                        <td style='width:150px;height:auto;'>Temperature &deg; C</td>
                    </tr>";
                }
                $i++;
            }
            $currentdate = date("Y-m-d H:i:s");
            $currentdate = substr($currentdate, '0', 11);
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
            $display .= "<tr><td style='width:150px;height:auto;'>$change->starttime</td>";
            $location = get_location_detail($change->devicelat, $change->devicelong);
            $display .= "<td style='width:450px;height:auto;'>$location</td>";
            // Temperature Sensors
            // Temperature Sensor
            if ($_SESSION['temp_sensors'] == 1) {
                $temp = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $temp_conversion->rawtemp = $change->$s;
                    $temp = getTempUtil($temp_conversion);
                } else {
                    $temp = '-';
                }

                if ($temp != '-' && $temp != "Not Active") {
                    $display .= "<td style='width:150px;height:auto;'>$temp</td>";
                } else {
                    $display .= "<td style='width:150px;height:auto;'>$temp</td>";
                }
            }
            if ($_SESSION['temp_sensors'] == 2) {
                $temp1 = 'Not Active';
                $temp2 = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $temp_conversion->rawtemp = $change->$s;
                    $temp1 = getTempUtil($temp_conversion);
                } else {
                    $temp1 = '-';
                }

                $s = "analog" . $vehicle->tempsen2;
                if ($vehicle->tempsen2 != 0 && $change->$s != 0) {
                    $temp_conversion->rawtemp = $change->$s;
                    $temp2 = getTempUtil($temp_conversion);
                } else {
                    $temp2 = '-';
                }

                if ($temp1 != '-' && $temp1 != "Not Active") {
                    $display .= "<td style='width:150px;height:auto;'>$temp1</td>";
                } else {
                    $display .= "<td style='width:150px;height:auto;'>$temp1</td>";
                }
                if ($temp2 != '-' && $temp2 != "Not Active") {
                    $display .= "<td style='width:150px;height:auto;'>$temp2</td>";
                } else {
                    $display .= "<td style='width:150px;height:auto;'>$temp2</td>";
                }
            }
            $display .= '</tr>';
        }
    }
    $display .= '</tbody>';
    $display .= '</table><br/>';
    $formatdate1 = date(speedConstants::DEFAULT_DATETIME);
    $display .= "<div align='right' style='text-align:center;'> Report Generated On: $formatdate1 </div>";
    return $display;
}

function create_pdf_from_report($datarows, $acinvert) {
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
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
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
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
    $display .= "
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
    $formatdate1 = date(speedConstants::DEFAULT_DATETIME);
    $display .= "<div align='right' style='text-align:center;'> Report Generated On: $formatdate1 </div>";
    return $display;
}

function create_gensetpdf_from_report($datarows, $acinvert) {
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
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
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
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
    $display .= "
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
    $formatdate1 = date(speedConstants::DEFAULT_DATETIME);
    $display .= "<div align='right' style='text-align:center;'> Report Generated On: $formatdate1 </div>";
    return $display;
}

function create_pdf_for_multipledays($datarows, $acinvert) {
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
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
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
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
    $display .= "
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
    $formatdate1 = date(speedConstants::DEFAULT_DATETIME);
    $display .= "<div align='right' style='text-align:center;'> Report Generated On: $formatdate1 </div>";
    return $display;
}

function create_gensetpdf_for_multipledays($datarows, $acinvert) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $totalminute = 0;
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
                $display .= "</tbody></table><h4><div> Date " . date('d-m-Y', strtotime($change->endtime)) . "</div></h4>
                        <hr  id='style-six' /><table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
                        <tbody>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $display .= "
                            <tr style='background-color:#CCCCCC;'>
                                <td style='width:100px;height:auto;'>Start Time</td>
                                <td style='width:100px;height:auto;'>End Time</td>
                                <td style='width:150px;height:auto;'>Gen Set Status</td>
                                <td style='width:150px;height:auto;'>Duration[Hours:Minutes]</td>
                            </tr>";
                $i++;
            }
            $currentdate = date("Y-m-d H:i:s");
            $currentdate = substr($currentdate, '0', 11);
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
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
    $display .= "
    <page_footer>
    [[page_cu]]/[[page_nb]]
    </page_footer>
    <div style='float:right;margin:15px;margin-right:60px;'>
        <table align='right' style='font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tr style='background-color:#CCCCCC;'><th><h4>Statistics</h4></th></tr>";
    $totaltime = 1440 * $count + $totalminute;
    $totaltime = round($totaltime);
    if ($acinvert == 1) {
        $offtime = $totaltime - $idletime;
        $display .= "<tr><td>Total Gen Set ON Time = $idletime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr>";
    } else {
        $offtime = $totaltime - $runningtime;
        $display .= "<tr><td>Total Gen Set ON Time = $runningtime Minutes</td></tr><tr><td>Total Gen Set OFF Time = $offtime Minutes</td></tr>";
    }
    $display .= "</table></div><hr style='margin-top:5px;'>";
    $formatdate1 = date(speedConstants::DEFAULT_DATETIME);
    $display .= "<div align='right' style='text-align:center;'> Report Generated On: $formatdate1 </div>";
    return $display;
}

function create_csv_from_report($datarows, $acinvert) {
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
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
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
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
    $display .= "<table align = 'center' style='text-align:center;'><thead><tr style='background-color:#CCCCCC;'><th style='width:50px;height:auto; text-align: center;'></th><th colspan='5'>Statistics</th><th style='width:50px;height:auto; text-align: center;'></th></tr></thead>";
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
    $lastdate = null;
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
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
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
    $display .= "<table align = 'center' style='text-align:center;'><thead><tr style='background-color:#CCCCCC;'><th style='width:50px;height:auto; text-align: center;'></th><th colspan='5'>Statistics</th><th style='width:50px;height:auto; text-align: center;'></th></tr></thead>";
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
    $lastdate = null;
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
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
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
    $display .= "<table align = 'center' style='text-align:center;'><thead><tr style='background-color:#CCCCCC;'><th style='width:50px;height:auto; text-align: center;'></th><th colspan='5'>Statistics</th><th style='width:50px;height:auto; text-align: center;'></th></tr></thead>";
    if ($acinvert == 1) {
        $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $idletime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $runningtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
    } else {
        $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $runningtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $idletime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
    }
    return $display;
}

function create_gensetexcel_from_report($datarows, $acinvert) {
    $i = 1;
    $runningtime = 0;
    $idletime = 0;
    $lastdate = null;
    $totalminute = 0;
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
                $display .= "<tr style='background-color:#CCCCCC;'><th align='center' colspan = '7'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $display .= "
                <tr style='background-color:#CCCCCC;'>
                    <td style='width:50px;height:auto; text-align: center;'></td>
                    <td style='width:50px;height:auto; text-align: center;'>Start Time</td>
                    <td style='width:50px;height:auto; text-align: center;'>End Time</td>
                    <td style='width:50px;height:auto; text-align: center;'>Gen Set Status</td>
                    <td style='width:150px;height:auto; text-align: center;'>Duration[Hours:Minutes]</td>
                    <td style='width:50px;height:auto; text-align: center;'></td>
                </tr>";
                $i++;
            }
            $currentdate = date("Y-m-d H:i:s");
            $currentdate = substr($currentdate, '0', 11);
            //Removing Date Details From DateTime
            $change->starttime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->starttime));
            $change->endtime = date(speedConstants::DEFAULT_DATETIME, strtotime($change->endtime));
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
    $display .= "<table align = 'center' style='text-align:center;'><thead><tr style='background-color:#CCCCCC;'><th style='width:50px;height:auto; text-align: center;'></th><th colspan='5'>Statistics</th><th style='width:50px;height:auto; text-align: center;'></th></tr></thead>";
    $totaltime = 1440 * $count + $totalminute;
    $totaltime = round($totaltime);
    if ($acinvert == 1) {
        $offtime = $totaltime - $idletime;
        $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $idletime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $offtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
    } else {
        $offtime = $totaltime - $runningtime;
        $display .= "<tbody><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set ON Time = $runningtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr><tr><td style='width:50px;height:auto; text-align: center;'></td><td colspan = '5' style=' text-align: center;'>Total Gen Set OFF Time = $offtime Minutes</td><td style='width:50px;height:auto; text-align: center;'></td></tr></tbody></table>";
    }
    return $display;
}

function getvehiclesbygroup($groupid) {
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $VehicleManager->get_groups_vehicles($groupid);
    return $vehicles;
}

function GetDailyReport_Data($location, $STdate, $EDdate, $vehicleid) {
    $fuelconsume = 0;
    $time = strtotime($SThour) + 3600;
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $fuelbalance = getDaily_FuelBalance($STdate, $vehicleid);
        if ($fuelbalance == '') {
            $fuelbalance = 0;
        }
        $average = getAverage($vehicleid);
        $REPORT = array();
        if (isset($fuelbalance) && $average > 0) {
            $query = "SELECT * from vehiclehistory where lastupdated BETWEEN '$STdate' AND '$EDdate'  ORDER BY lastupdated ASC";
            $result = $db->query($query);
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $Datacap = new stdClass();
                    $Datacap->date = strtotime($day);
                    $Datacap->odometer = $row['odometer'];
                    $Datacap->vehicleid = $row['vehicleid'];
                    $Datacap->average = $average;
                    $Datacap->lastupdated = $row['lastupdated'];
                    $Datacap->fuel_balance = $fuelbalance;
                    $REPORT[] = $Datacap;
                    //$fuelconsume = "";
                }
            }
            return $REPORT;
        }
    }
}

function getDaily_FuelBalance($STdate, $vehicleid) {
    $customerno = $_SESSION['customerno'];
    $location = "../../customer/$customerno/reports/dailyreport.sqlite";
    $path = "sqlite:$location";
    $db1 = new PDO($path);
    //$date = date('Y-m-d', strtotime('-1 day',strtotime($STdate)));
    $sqlday = date("dmy", strtotime($STdate));
    $query = "SELECT * from A$sqlday where vehicleid=$vehicleid";
    $result = $db1->query($query);
    $row = $result->fetch();
    if (!$row['fuel_balance']) {
        return 0;
    } else {
        return $row['fuel_balance'];
    }
}

function getFuel($id, $sqlitedate, $start, $current, $fuel) {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $fuel = $vehiclemanager->getFuelConsumed($id, $sqlitedate, $start, $current, $fuel);
    return $fuel;
}

function getFuelAlert() {
    $vehiclemanager = new VehicleManager($_SESSION['customerno']);
    $fuel = $vehiclemanager->get_FuelAlert();
    return $fuel;
}

function GetDailyReport_Data_ByID($location, $days, $vehicleid) {
    $REPORT = array();
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        if (isset($days)) {
            foreach ($days as $day) {
                if ($_SESSION['groupid'] != 0) {
                    $sqlday = date("dmy", strtotime($day));
                    $query = "SELECT * from A$sqlday where vehicleid=$vehicleid";
                    $result = $db->query($query);
                    if (isset($result) && $result != "") {
                        foreach ($result as $row) {
                            $Datacap = new stdClass();
                            $Datacap->date = strtotime($day);
                            $Datacap->uid = $row['uid'];
                            $Datacap->tamper = $row['tamper'];
                            $Datacap->overspeed = $row['overspeed'];
                            $Datacap->totaldistance = abs($row['totaldistance']);
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
                            $Datacap = new stdClass();
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
                    if ($vehicleid != null) {
                        $query .= " where vehicleid=$vehicleid";
                    }
                    $result = $db->query($query);
                    if (isset($result) && $result != "") {
                        foreach ($result as $row) {
                            $Datacap = new stdClass();
                            $Datacap->date = strtotime($day);
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
        }
    }
    return $REPORT;
}

function GetDailyReport_Data_ByID_PDF($location, $days, $vehicleid, $customerno, $usemaintainance, $usehierarchy, $groupid) {
    $REPORT = array();
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        if (isset($days)) {
            foreach ($days as $day) {
                if ($groupid != 0) {
                    $sqlday = date("dmy", strtotime($day));
                    $query = "SELECT * from A$sqlday where vehicleid=$vehicleid";
                    $result = $db->query($query);
                    if (isset($result) && $result != "") {
                        foreach ($result as $row) {
                            $Datacap = new stdClass();
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
                            $Datacap->location = get_location_pdf($row['dev_lat'], $row['dev_long'], $customerno);
                            $Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                            $REPORT[] = $Datacap;
                        }
                    }
                } else if ($usemaintainance == '1' && $usehierarchy == '1') {
                    $sqlday = date("dmy", strtotime($day));
                    $query = "SELECT * from A$sqlday where vehicleid=$vehicleid";
                    $result = $db->query($query);
                    if (isset($result) && $result != "") {
                        foreach ($result as $row) {
                            $Datacap = new stdClass();
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
                            $Datacap->location = get_location_pdf($row['dev_lat'], $row['dev_long'], $customerno);
                            $Datacap->average = getAverage_pdf($row['vehicleid'], $customerno);
                            $REPORT[] = $Datacap;
                        }
                    }
                } else {
                    $sqlday = date("dmy", strtotime($day));
                    $query = "SELECT * from A$sqlday";
                    if ($vehicleid != null) {
                        $query .= " where vehicleid=$vehicleid";
                    }
                    $result = $db->query($query);
                    if (isset($result) && $result != "") {
                        foreach ($result as $row) {
                            $Datacap = new stdClass();
                            $Datacap->date = strtotime($day);
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

function getVehicleName($vehicleid) {
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $vehicleno = $VehicleManager->getVehicleName($vehicleid);
    return $vehicleno;
}

function getVehicleName_pdf($vehicleid, $customerno) {
    $VehicleManager = new VehicleManager($customerno);
    $vehicleno = $VehicleManager->getVehicleName($vehicleid);
    return $vehicleno;
}

function GetHourlyReport_Temp($location, $STdate, $SThour) {
    $REPORT = array();
    if (file_exists($location)) {
        $temp = 0;
        $time = strtotime($SThour) + 3600;
        $nexthr = date('H:i:s', $time);
        $path = "sqlite:$location";
        $db = new PDO($path);
        if ($SThour != '23:00:00') {
            $query = "SELECT * from unithistory where DATETIME(lastupdated) BETWEEN '$STdate $SThour' AND '$STdate $nexthr' ORDER BY lastupdated ASC";
        } else {
            $query = "SELECT * from unithistory where DATETIME(lastupdated) BETWEEN '$STdate $SThour' AND '$STdate 23:59:59' ORDER BY lastupdated ASC";
        }
        $result = $db->query($query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if ($temp != 0 && abs($row['analog1'] - $temp) < 22.25) {
                    $Datacap = new stdClass();
                    $Datacap->date = strtotime($day);
                    $Datacap->analog1 = $row['analog1'];
                    $Datacap->lastupdated = $row['lastupdated'];
                    $REPORT[] = $Datacap;
                    $temp = $row['analog1'];
                } elseif ($temp == 0) {
                    $Datacap = new stdClass();
                    $Datacap->date = strtotime($day);
                    $Datacap->analog1 = $row['analog1'];
                    $Datacap->lastupdated = $row['lastupdated'];
                    $REPORT[] = $Datacap;
                    $temp = $row['analog1'];
                }
            }
        }
    }
    return $REPORT;
}

function GetDailyReport_Temp($location, $STdate, $EDdate) {
    $temp = 0;
    $time = strtotime($SThour) + 3600;
    $REPORT = array();
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $query = "SELECT * from unithistory where lastupdated BETWEEN '$STdate' AND '$EDdate' ORDER BY lastupdated ASC";
        $result = $db->query($query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if ($temp != 0 && abs($row['analog1'] - $temp) < 22.25) {
                    $Datacap = new stdClass();
                    $Datacap->date = strtotime($day);
                    $Datacap->analog1 = $row['analog1'];
                    $Datacap->analog2 = $row['analog2'];
                    $Datacap->analog3 = $row['analog3'];
                    $Datacap->analog4 = $row['analog4'];
                    $Datacap->lastupdated = $row['lastupdated'];
                    $REPORT[] = $Datacap;
                    $temp = $row['analog1'];
                } elseif ($temp == 0) {
                    $Datacap = new stdClass();
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
    }
    return $REPORT;
}

function GetDailyReport_Temp_Analog($location, $STdate, $EDdate, $analogtype) {
    $temp = 0;
    $time = strtotime($SThour) + 3600;
    $REPORT = array();
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $query = "SELECT * from unithistory where lastupdated BETWEEN '$STdate' AND '$EDdate' ORDER BY lastupdated ASC";
        $result = $db->query($query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if ($analogtype == 1) {
                    if ($temp != 0 && abs($row['analog1'] - $temp) < 22.25) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->analog1 = $row['analog1'];
                        $Datacap->lastupdated = $row['lastupdated'];
                        $REPORT[] = $Datacap;
                        $temp = $row['analog1'];
                    } elseif ($temp == 0) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->analog1 = $row['analog1'];
                        $Datacap->lastupdated = $row['lastupdated'];
                        $REPORT[] = $Datacap;
                        $temp = $row['analog1'];
                    }
                } elseif ($analogtype == 2) {
                    if ($temp != 0 && abs($row['analog2'] - $temp) < 22.25) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->analog1 = $row['analog2'];
                        $Datacap->lastupdated = $row['lastupdated'];
                        $REPORT[] = $Datacap;
                        $temp = $row['analog2'];
                    } elseif ($temp == 0) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->analog1 = $row['analog2'];
                        $Datacap->lastupdated = $row['lastupdated'];
                        $REPORT[] = $Datacap;
                        $temp = $row['analog2'];
                    }
                } elseif ($analogtype == 3) {
                    if ($temp != 0 && abs($row['analog3'] - $temp) < 22.25) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->analog1 = $row['analog3'];
                        $Datacap->lastupdated = $row['lastupdated'];
                        $REPORT[] = $Datacap;
                        $temp = $row['analog3'];
                    } elseif ($temp == 0) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->analog1 = $row['analog3'];
                        $Datacap->lastupdated = $row['lastupdated'];
                        $REPORT[] = $Datacap;
                        $temp = $row['analog3'];
                    }
                } elseif ($analogtype == 4) {
                    if ($temp != 0 && abs($row['analog4'] - $temp) < 22.25) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->analog1 = $row['analog4'];
                        $Datacap->lastupdated = $row['lastupdated'];
                        $REPORT[] = $Datacap;
                        $temp = $row['analog4'];
                    } elseif ($temp == 0) {
                        $Datacap = new stdClass();
                        $Datacap->date = strtotime($day);
                        $Datacap->analog1 = $row['analog4'];
                        $Datacap->lastupdated = $row['lastupdated'];
                        $REPORT[] = $Datacap;
                        $temp = $row['analog4'];
                    }
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
    $M = round(($min - ($H * 60)) / 100, 2);
    //echo $M;
    if ($M == 0.6) {
        $H = $H + 1;
        $M = 0;
    }
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
    //$currentdate = '16-01-2014';
    if ($date == $currentdate) {
        $data = '';
        $queues = $cqm->getalerthist($date, $type, $vehicleid, $checkpointid, $fenceid, $_SESSION['customerno']);
        include_once 'pages/panels/alerthistrep.php';
        $i = 1;
        if (isset($queues)) {
            foreach ($queues as $queue) {
                if ($queue->processed == 1 && $queue->comtype == 0) {
                    $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>$queue->email</td><td>---</td></tr>";
                    $i++;
                } else if ($queue->processed == 1 && $queue->comtype == 1) {
                    $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>$queue->phone</td></tr>";
                    $i++;
                } else {
                    $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>---</td></tr>";
                    $i++;
                }
            }
            $data .= '</body></table>';
        } else {
            $data .= "<tr><td colspan='5'>No Data Available</td></tr>";
            $data .= '</body></table>';
        }
    } else {
        $data = '';
        $dt = strtotime(date("Y-m-d", strtotime($date)));
        $file = date("MY", $dt);
        $location = "../../customer/" . $_SESSION['customerno'] . "/history/$file.sqlite";
        if (file_exists($location)) {
            $queues = getalerthist_sqlite($location, $date, $type, $vehicleid, $checkpointid, $fenceid);
            include_once 'pages/panels/alerthistrep.php';
            $i = 1;
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
                        } else {
                            $data .= "<tr><td colspan='5'>No Data Available</td></tr>";
                        }
                    } else {
                        $data .= "<tr><td>$i</td><td>$queue->message</td><td>$queue->timeadded</td><td>---</td><td>---</td></tr>";
                        $i++;
                    }
                }
            } else {
                $data .= "<tr><td colspan='5'>No Data Available</td></tr>";
            }
            $data .= '</body></table>';
        }
    }
    echo $data;
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
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $queues = array();
        $query = "SELECT userid,comtype from comhistory where comqid = $cqid";
        $result = $db->query($query);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                $Datacap = new stdClass();
                $Datacap->userid = $row['userid'];
                $Datacap->comtype = $row['comtype'];
                $queues[] = $Datacap;
            }
            return $queues;
        }
    }
    return null;
}

function getalerthist_sqlite($location, $date, $type, $vehicleid, $checkpointid, $fenceid) {
    $newdate = date('Y-m-d', strtotime($date));
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $queues = array();
        switch ($type) {
            case '-1': {
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
            default: {
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
                        $Datacap = new stdClass();
                        $Datacap->cqid = $row['cqid'];
                        $Datacap->timeadded = convertDateToFormat($row['timeadded'], speedConstants::DEFAULT_TIME);
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
                    $Datacap = new stdClass();
                    $Datacap->cqid = $row['cqid'];
                    $Datacap->timeadded = convertDateToFormat($row['timeadded'], speedConstants::DEFAULT_TIME);
                    $Datacap->message = $row['message'];
                    $Datacap->processed = $row['processed'];
                    $queues[] = $Datacap;
                }
                return $queues;
            }
            return null;
        }
    }
}

function getalerthist_sqlite_team($location, $date, $type, $vehicleid, $checkpointid, $fenceid) {
    $newdate = date('Y-m-d', strtotime($date));
    if (file_exists($location)) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $queues = array();
        switch ($type) {
            case '-1': {
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
            default: {
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
                $Datacap = new stdClass();
                $Datacap->cqid = $row['cqid'];
                $Datacap->timeadded = convertDateToFormat($row['timeadded'], speedConstants::DEFAULT_TIME);
                $Datacap->message = $row['message'];
                $Datacap->processed = $row['processed'];
                $queues[] = $Datacap;
            }
            return $queues;
        }
    }
    return null;
}

function GetDevice_byId($id, $customerno) {
    $dm = new DeviceManager($customerno);
    $result = $dm->GetDevice_byId($id);
    return $result;
}

function get_transaction_details($vehicleid, $categoryid, $dealerid, $sdate1, $edate1, $statusid, $customerno, $invoicefilter = NULL) {
    $mm = new MaintananceManager($customerno);
    $data = $mm->get_transaction_details($vehicleid, $categoryid, $dealerid, $sdate1, $edate1, $statusid, $invoicefilter);
    return $data;
}

function get_make_model($vehicleid, $customerno) {
    $mm = new MaintananceManager($customerno);
    $data = $mm->get_make_model($vehicleid);
    return $data;
}

function getpartsby_maintenanceid($main_id, $customerno) {
    $PartManager = new PartManager($customerno);
    $parts = $PartManager->getpartsby_maintenanceid($main_id);
    return $parts;
}

function gettaskby_maintenanceid($main_id, $customerno) {
    $TaskManager = new TaskManager($customerno);
    $tasks = $TaskManager->gettaskby_maintenanceid($main_id);
    return $tasks;
}

function getpart() {
    $PartManager = new PartManager($_SESSION['customerno']);
    $parts = $PartManager->get_all_part();
    return $parts;
}

function gettask() {
    $TaskManager = new TaskManager($_SESSION['customerno']);
    $tasks = $TaskManager->get_all_task();
    return $tasks;
}

function get_parts_name($parts, $thispart) {
    $pcsv = '';
    if ($parts) {
        $p_arr = array_filter(explode(',', $thispart));
        if (!empty($p_arr)) {
            $f_arr = array();
            foreach ($p_arr as $p) {
                if (ri($parts[$p]->part_name) != '') {
                    $f_arr[] = ri($parts[$p]->part_name);
                }
            }
            $pcsv = implode(', ', $f_arr);
        }
    }
    return $pcsv;
}

function get_task_name($tasks, $thistask) {
    $tcsv = '';
    if ($tasks) {
        $t_arr = array_filter(explode(',', $thistask));
        if (!empty($t_arr)) {
            $f_arr = array();
            foreach ($t_arr as $t) {
                if (ri($tasks[$t]->task_name) != '') {
                    $f_arr[] = ri($tasks[$t]->task_name);
                }
            }
            $tcsv = implode(', ', $f_arr);
        }
    }
    return $tcsv;
}

function html_maintenance_trans_hist($vid, $vno, $startdate, $enddate, $dealerid, $customerno, $categoryid, $statusid) {
    $html = '';
    $startdate = strtotime($startdate);
    $enddate = strtotime($enddate);
    if ($vno == '') {
        $transdata = get_transaction_details(0, $categoryid, $dealerid, $startdate, $enddate, $statusid, $customerno);
    } else {
        $transdata = get_transaction_details($vid, $categoryid, $dealerid, $startdate, $enddate, $statusid, $customerno);
    }
    $html .= "
    <tr style='background-color:#CCCCCC;font-weight:bold;'>
        <td>Sr.No</td>
        <td>Veh.No</td>
        <td>Group Name</td>
        <td>Dealer<br> Name</td>
        <td>TransID</td>
        <td>Meter<br>Reading</td>
        <td>Category</td>
        <td>Qtn amount</td>
        <td>Notes</td>
        <td>Invoice <br>No</td>
        <td>Invoice<br> Amt</td>
        <td>Invoice<br> Date</td>
        <td>Vehicle <br>Out<br> Date</td>
        <td>Status</td>
        <td>Parts</td>
        <td>Tasks</td>
        <td>Accessories</td>
        <td>Tyre</td>
        <td>Battery No</td>

    </tr>";
    if (isset($transdata) && !empty($transdata)) {
        $html .= "<tr><td colspan='19' style='text-align:center;height:20px;font-weight:bold;'>Lables : U - Unit Price , Q - Quantity , T - Total Amount</td></tr>";
        $i = 0;
        foreach ($transdata as $transdatas) {
            $i++;
            $record_parts = getpartsby_maintenanceid($transdatas->mid, $customerno);
            $record_tasks = gettaskby_maintenanceid($transdatas->mid, $customerno);
            $html .= "<tr>
            <td style='width:3%; height:30px;  word-wrap:break-word;'>$i</td>
            <td style=' word-wrap:break-word;'>$transdatas->vehicleno</td>
            <td style=' word-wrap:break-word;'>$transdatas->groupname</td>
            <td style=' width:7%; word-wrap:break-word;'>$transdatas->dname</td>
            <td style=' word-wrap:break-word;'>$transdatas->transid</td>
            <td style=' width:5%; word-wrap:break-word;'>$transdatas->meter_reading</td>
            <td style=' word-wrap:break-word;'>$transdatas->cat</td>
            <td style=' word-wrap:break-word;'>$transdatas->amount_quote</td>
            <td style='width:8%; word-wrap:break-word;'>$transdatas->notes</td>
            <td style=' word-wrap:break-word;'>$transdatas->invoice_no</td>
            <td style=' word-wrap:break-word;'>$transdatas->invoice_amount</td>
            <td style=' word-wrap:break-word;'>$transdatas->invoice_date</td>
            <td style=' word-wrap:break-word;'>$transdatas->vehicle_out_date</td>
            <td style=' width:10%; word-wrap:break-word;'>$transdatas->msname</td>";
            if (!empty($record_parts)) {
                $html .= "<td style='width:7%; word-wrap:break-word;'>";
                $j = 1;
                foreach ($record_parts as $parts) {
                    $html .= $j . ") ";
                    $html .= $parts;
                    $html .= "\r\n";
                    $j++;
                }
                $html .= "</td>";
            } else {
                $html .= "<td style='width:10%; word-wrap:break-word;'> N/A </td>";
            }
            if (!empty($record_tasks)) {
                $html .= "<td style='width:10%; word-wrap:break-word;'>";
                $k = 1;
                foreach ($record_tasks as $tasks) {
                    $html .= $k . ") ";
                    $html .= $tasks;
                    $html .= "\r\n";
                    $k++;
                }
                $html .= "</td>";
            } else {
                $html .= "<td style='width:10%; word-wrap:break-word;'> N/A</td>";
            }
            if ($transdatas->access == '') {
                $html .= "<td>N/A</td>";
            } else {
                $html .= "<td style=' word-wrap:break-word;'>$transdatas->access</td>";
            }
            $html .= "<td style=' word-wrap:break-word;'>$transdatas->battery_srno</td>";
            $html .= "<td style=' word-wrap:break-word;'>$transdatas->tyre_type</td>";
            //$html .= "<td style=' word-wrap:break-word;'>$transdatas->mdate</td>";
            $html .= "</tr>";
        }
    } else {
        $html .= "<td colspan='16'>Data not available</td>";
    }
    $html .= "</tbody></table>";
    return $html;
}

function xls_maintenance_trans_hist($vid, $vno, $startdate, $enddate, $dealer, $customerno, $category, $status, $vgroupname = null) {
    $vehicleid = (int) $vid;
    $dealerid = (int) $dealer;
    $categoryid = (int) $category;
    $statusid = (int) $status;
    $type = "";
    $finalreport = '';
    $makename = '';
    $modelname = '';

    if ($vehicleid != "") {
        $makemodeldata = get_make_model($vehicleid, $customerno);
        $makename = $makemodeldata[0]['makename'];
        $modelname = $makemodeldata[0]['modelname'];
    }

    if ($categoryid == 0) {
        $type = "Battery";
    }
    if ($categoryid == 1) {
        $type = "Tyre";
    }
    if ($categoryid == 2) {
        $type = "Repair";
    }
    if ($categoryid == 3) {
        $type = "Service";
    }
    if ($categoryid == 5) {
        $type = "Accessories";
    }
    if ($categoryid == '-1') {
        $type = "";
    }
    if (strtotime($startdate) > strtotime($enddate)) {
        $finalreport .= "Start date is greater then end date";
    } else {
        $title = 'Transaction History';
        $subTitle = array(
            "Vehicle No: $vno",
            "Category: $type",
            "Start Date: $startdate",
            "End Date: $enddate",
            "Make Name: $makename",
            "Model Name: $modelname",
        );
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }

        $finalreport .= excel_header($title, $subTitle, null, null);
        $finalreport .= "<table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $finalreport .= html_maintenance_trans_hist($vid, $vno, $startdate, $enddate, $dealerid, $customerno, $categoryid, $statusid);
    }
    echo $finalreport;
}

function pdf_maintenance_trans_hist($vid, $vno, $startdate, $enddate, $dealer, $customerno, $category, $status, $vgroupname = null) {
    $vehicleid = (int) $vid;
    $dealerid = (int) $dealer;
    $categoryid = (int) $category;
    $statusid = (int) $status;
    $makename = "";
    $modelname = "";
    if ($vehicleid != "") {
        $makemodeldata = get_make_model($vehicleid, $customerno);
        $makename = $makemodeldata[0]['makename'];
        $modelname = $makemodeldata[0]['modelname'];
    }

    $type = "";
    $finalreport = '';
    if ($categoryid == 0) {
        $type = "Battery";
    }
    if ($categoryid == 1) {
        $type = "Tyre";
    }
    if ($categoryid == 2) {
        $type = "Repair";
    }
    if ($categoryid == 3) {
        $type = "Service";
    }
    if ($categoryid == 5) {
        $type = "Accessories";
    }
    if ($categoryid == '-1') {
        $type = "";
    }
    if (strtotime($startdate) > strtotime($enddate)) {
        $finalreport .= "Start date is greater then end date";
    } else {
        $title = 'Transaction History';
        $subTitle = array(
            "Vehicle No: $vno",
            "Category: $type",
            "Start Date: $startdate",
            "End Date: $enddate",
            "Make Name: $makename",
            "Model Name: $modelname",
        );

        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }

        $finalreport .= pdf_header($title, $subTitle, null, null);
        $finalreport .= "<table id='search_table_2' align='center' style='width:auto; font-size:9px; text-align:center;border-collapse:collapse; border:1px solid #000;'>
        <tbody>";
        $pdfdata = html_maintenance_trans_hist($vid, $vno, $startdate, $enddate, $dealerid, $customerno, $categoryid, $statusid);
        $finalreport .= $pdfdata;
        $finalreport .= "<page_footer>[[page_cu]]/[[page_nb]]</page_footer>";
        $finalreport .= "<hr style='margin-top:5px;'>";
        $formatdate1 = date(speedConstants::DEFAULT_DATETIME);
        $finalreport .= "<div align='right' style='text-align:center;'> PDF Generated On: $formatdate1 </div>";
    }
    echo $finalreport;
}

function get_trans_status() {
    $maintanace_obj = new MaintananceManager($_SESSION['customerno']);
    $status = $maintanace_obj->get_trans_status();
    return $status;
}

function get_all_checkpoint_type() {
    $checkpointmanager = new CheckpointManager($_SESSION['customerno']);
    $checkpoints = $checkpointmanager->getallcheckpointtype();
    return $checkpoints;
}

if (isset($_POST["Filter"]) && $_POST["Filter"] == '1') {
    $vehicleid = (int) GetSafeValueString($_POST["vehicleid"], "string");
    $dealerid = GetSafeValueString($_POST["dealerid"], "string");
    $sdate1 = GetSafeValueString($_POST["STdate"], "string");
    $edate1 = GetSafeValueString($_POST["ETdate"], "string");
    $sdate = strtotime($sdate1 . ' 00:00');
    $edate = strtotime($edate1 . ' 23:59');
    if ($sdate > $edate) {
        $errorfound = true;
        echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
    } elseif ($vehicleno == '') {
        $fuels = getfilteredfuelmaintenance($vehicleno = null, $dealerid, $sdate, $edate);
    } else {
        $fuels = getfilteredfuelmaintenance($vehicleno, $dealerid, $sdate, $edate);
    }
    echo json_encode($fuels[0]);
}
?>
