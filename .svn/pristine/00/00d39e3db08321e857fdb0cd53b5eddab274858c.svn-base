<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
include_once $RELATIVE_PATH_DOTS . 'lib/comman_function/reports_func.php';
include_once '../trips/class/TripsManager.php';
if (!isset($_SESSION)) {
    session_start();
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

function getvehicles_overspeed() {
    $devicemanager = new DeviceManager($_SESSION['customerno']);
    $devices = $devicemanager->devicesformapping();
    return $devices;
}

function getdate_IST() {
    $ServerDate_IST = strtotime(date("Y-m-d H:i:s"));
    return $ServerDate_IST;
}

function getacinvertval($unitno) {
    $um = new UnitManager($_SESSION['customerno']);
    $invertval = $um->getacinvertval($unitno);
    return $invertval['0'];
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

function get_location_data($location, $count, $userdate, $firstelement, $endelement, $deviceid, $interval, $distance, $Shour, $Ehour, $customerNo) {
    $data = null;
    $location = "sqlite:" . $location;
    if ($count > 1 && $userdate != $endelement && $userdate == $firstelement) {
        $data = getlocation_fromsqlite($location, $deviceid, $interval, $distance, $Shour, Null, $userdate, $customerNo);
    } elseif ($count > 1 && $userdate == $endelement) {
        $data = getlocation_fromsqlite($location, $deviceid, $interval, $distance, Null, $Ehour, $userdate, $customerNo);
    } elseif ($count == 1) {
        $data = getlocation_fromsqlite($location, $deviceid, $interval, $distance, $Shour, $Ehour, $userdate, $customerNo);
    } else {
        $data = getlocation_fromsqlite($location, $deviceid, $interval, $distance, Null, Null, $userdate, $customerNo);
    }
    return $data;
}

function getlocationreport($STdate, $EDdate, $deviceid, $Shour, $Ehour, $interval = null, $distance = null) {
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    $count = count($totaldays);
    $endelement = end($totaldays);
    $firstelement = $totaldays[0];
    $unit = getunitdetails($deviceid);
    $acinvertval = getacinvertval($unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (!file_exists($location)) {
                continue;
            } else {
                $data = get_location_data($location, $count, $userdate, $firstelement, $endelement, $deviceid, $interval, $distance, $Shour, $Ehour, $customerno);
                if ($data != NULL && count($data) > 0) {
                    $days = array_merge($days, $data);
                }
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $finalreport = create_locationhtml_from_report($days, $unit, $endelement, $acinvertval);
    } else {
        $finalreport = "<tr><td colspan='100%' style='text-align:center;'>No Data Found</td></tr></tbody></table></div>";
    }
    echo $finalreport;
}

function getlocationreportpdf($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval = null, $distance = null, $Shour, $Ehour, $userid, $tripid = null, $triplogno = null, $vgroupname = null) {
    $tripdata = '';
    $tripobj = new Trips($_SESSION['customerno'], $_SESSION['userid']);
    if ($tripid == null) {
        $tripid = $tripobj->gettripidbytriplogno($triplogno);
    }
    if (isset($tripid) && !empty($tripid)) {
        $tripobj = new Trips($_SESSION['customerno'], $_SESSION['userid']);
        $tripdata = $tripobj->gettripdetailsedit($tripid);
        //get startdate or enddate from triphistory
        $closedtripenddetails = $tripobj->closedtripdetails_end($tripid);
        $closedtripstartdetails = $tripobj->closedtripdetails_start($tripid);
        $tripstart_date = $closedtripstartdetails[0]['tripstart_date'];
        $tripend_date = $closedtripenddetails[0]['tripend_date'];
        if (empty($tripend_date)) {
            $tripend_date = date('Y-m-d');
        } else {
            $tripend_date = $closedtripenddetails[0]['tripend_date'];
        }
        $getstart_odometer = $getend_odometer = '';
        $startododate = trim(substr($tripstart_date, 0, 11));
        $customerno = $_SESSION['customerno'];
        $unitno = $tripdata[0]->unitno;
        $strlocation = "../../customer/$customerno/unitno/$unitno/sqlite/$startododate.sqlite";
        $getstart_odometer = $tripobj->getOdometer($strlocation, $tripstart_date);
        $endododate = trim(substr($tripend_date, 0, 11));
        $customerno = $_SESSION['customerno'];
        $unitno = $tripdata[0]->unitno;
        $endlocation = "../../customer/$customerno/unitno/$unitno/sqlite/$endododate.sqlite";
        $today = date('Y-m-d');
        if (strtotime($tripend_date) == strtotime($today)) {
            $getend_odometer = $tripobj->getodometerform_mysql($tripdata[0]->vehicleid);
        } else {
            $getend_odometer = $tripobj->getOdometer($endlocation, $tripend_date);
        }
        $getend_odometer = $tripobj->getOdometer($endlocation, $tripend_date);
    }
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $customerno = $_SESSION['customerno'];
    $unitno = getunitno($deviceid);
    $count = count($totaldays);
    $endelement = end($totaldays);
    $firstelement = $totaldays[0];
    $unit = getunitdetails($deviceid);
    $acinvertval = getacinvertval($unitno);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (!file_exists($location)) {
                continue;
            }
            $data = get_location_data($location, $count, $userdate, $firstelement, $endelement, $deviceid, $interval, $distance, $Shour, $Ehour, $_SESSION['customerno']);
            if ($data != NULL && count($data) > 0) {
                $days = array_merge($days, $data);
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $title = 'Location Report';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $STdate $Shour",
            "End Date: $EDdate $Ehour"
        );
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        if ($interval != null) {
            $subTitle[] = "Report Generated for every <b>$interval</b> mins";
        } else {
            $subTitle[] = "Report Generated for every <b>$distance</b> kms";
        }
        //trip changes
        $middlecolumn = null;
        if (isset($tripdata) && !empty($tripdata)) {
            $tripstatus = $tripdata[0]->tripstatus;
            $triplogno = $tripdata[0]->triplogno;
            $routename = $tripdata[0]->routename;
            $budgetedkms = $tripdata[0]->budgetedkms;
            $budgetedhrs = $tripdata[0]->budgetedhrs;
            $consignor = $tripdata[0]->consignor;
            $consignee = $tripdata[0]->consignee;
            $billingparty = $tripdata[0]->billingparty;
            $mintemp = $tripdata[0]->mintemp;
            $maxtemp = $tripdata[0]->maxtemp;
            $drivername = $tripdata[0]->drivername;
            $drivermobile1 = $tripdata[0]->drivermobile1;
            $drivermobile2 = $tripdata[0]->drivermobile2;
            $budgetedkms = $tripdata[0]->budgetedkms;
            $budgetedhrs = $tripdata[0]->budgetedhrs;
            $newsdate = date("Y-m-d", strtotime($tripstart_date));
            $edate = date("Y-m-d", strtotime($tripend_date));
            $firstodometer = 0;
            $lastodometer = 0;
            $lastodometer = $getend_odometer; // last odometer
            $firstodometer = $getstart_odometer; // first odometer
            if ($lastodometer < $firstodometer) {
                $tripobj = new Trips($_SESSION['customerno'], $_SESSION['userid']);
                $lastodometermax = $tripobj->GetOdometerMax($edate, $tripdata[0]->unitno);
                $days1 = gendays($tripstart_date, $tripend_date);
                if (count($days1) > 0) {
                    $lastodometerarr = array();
                    foreach ($days1 as $day) {
                        $lastodometerarr[] = $tripobj->GetOdometerMax($day, $tripdata[0]->unitno);
                    }
                    $lastodometermax = max($lastodometerarr);
                }
                $lastodometer = $lastodometermax + $lastodometer;
            }
            $totaldistance = $lastodometer - $firstodometer;
            $actualhrs = round((strtotime($tripstart_date) - strtotime($tripend_date)) / (60 * 60));
            if ($totaldistance != 0) {
                $res = $totaldistance / 1000;
            } else {
                $res = 0;
            }
            ////////////////Estimated Time calculate///////////////////////
            $estimated_time = 0;
            $estimated_time = $tripdata[0]->budgetedhrs - $actualhrs;
            ///////////////////////////////////////////////////////////////
            $middlecolumn = '<td style="text-align:left;border-right:none; width:250px;"><div> <span style="text-align:center; "> <u>Trip History</u> </span><br>
                    Triplogno : ' . $triplogno . '<br/> '
            . ' Route : ' . $routename . '<br/>'
            . ' Temprature Range : ' . round($mintemp) . ' To ' . round($maxtemp) . '<br/>'
                . ' Driver Name : ' . $drivername . '<br/>'
                . ' Driver No. : ' . $drivermobile1 . '<br/>'
                . ' Driver No.2 : ' . $drivermobile2 . '<br/>'
                . ' Total Budgeted kms : ' . $budgetedkms . 'kms<br/>'
                . ' Total Budgeted hrs : ' . $budgetedhrs . 'hrs<br/>'
                . ' Actual kms : ' . $res . 'kms<br/>'
                . ' Actual hrs : ' . $estimated_time . 'Hrs<br/>'
                . ' Billing Party : ' . $billingparty . 'kms<br/>'
                . ' Consignor : ' . $consignor . '<br/>'
                . ' Consignee : ' . $consignee . '<br/> '
                . '  </div></td>';
        }
        $finalreport = pdf_header($title, $subTitle, NULL, $middlecolumn);
        $finalreport .= "<table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>";
        $finalreport .= create_location_pdf_from_report($days, $unit, $customerno, $userid, $acinvertval);
    }
    echo $finalreport;
}

function getlocationreportcsv($customerno, $STdate, $EDdate, $deviceid, $vehicleno, $interval = null, $distance = null, $Shour, $Ehour, $userid, $tripid = NULL, $triplogno = NULL, $vgroupname = null) {
    $tripdata = '';
    $middlecolumn = '';
    $tripobj = new Trips($_SESSION['customerno'], $_SESSION['userid']);
    if ($tripid == null) {
        $tripid = $tripobj->gettripidbytriplogno($triplogno);
    }
    if (isset($tripid) && !empty($tripid)) {
        $tripobj = new Trips($_SESSION['customerno'], $_SESSION['userid']);
        $tripdata = $tripobj->gettripdetailsedit($tripid);
        //get startdate or enddate from triphistory
        $closedtripenddetails = $tripobj->closedtripdetails_end($tripid);
        $closedtripstartdetails = $tripobj->closedtripdetails_start($tripid);
        $tripstart_date = $closedtripstartdetails[0]['tripstart_date'];
        $tripend_date = $closedtripenddetails[0]['tripend_date'];
        if (empty($tripend_date)) {
            $tripend_date = date('Y-m-d');
        } else {
            $tripend_date = $closedtripenddetails[0]['tripend_date'];
        }
        $getstart_odometer = $getend_odometer = '';
        $startododate = trim(substr($tripstart_date, 0, 11));
        $customerno = $_SESSION['customerno'];
        $unitno = $tripdata[0]->unitno;
        $strlocation = "../../customer/$customerno/unitno/$unitno/sqlite/$startododate.sqlite";
        $getstart_odometer = $tripobj->getOdometer($strlocation, $tripstart_date);
        $endododate = trim(substr($tripend_date, 0, 11));
        $customerno = $_SESSION['customerno'];
        $unitno = $tripdata[0]->unitno;
        $endlocation = "../../customer/$customerno/unitno/$unitno/sqlite/$endododate.sqlite";
        $today = date('Y-m-d');
        if (strtotime($tripend_date) == strtotime($today)) {
            $getend_odometer = $tripobj->getodometerform_mysql($tripdata[0]->vehicleid);
        } else {
            $getend_odometer = $tripobj->getOdometer($endlocation, $tripend_date);
        }
        if (isset($tripdata) && !empty($tripdata)) {
            $tripstatus = $tripdata[0]->tripstatus;
            $triplogno = $tripdata[0]->triplogno;
            $routename = $tripdata[0]->routename;
            $budgetedkms = $tripdata[0]->budgetedkms;
            $budgetedhrs = $tripdata[0]->budgetedhrs;
            $consignor = $tripdata[0]->consignor;
            $consignee = $tripdata[0]->consignee;
            $billingparty = $tripdata[0]->billingparty;
            $mintemp = $tripdata[0]->mintemp;
            $maxtemp = $tripdata[0]->maxtemp;
            $drivername = $tripdata[0]->drivername;
            $drivermobile1 = $tripdata[0]->drivermobile1;
            $drivermobile2 = $tripdata[0]->drivermobile2;
            $budgetedkms = $tripdata[0]->budgetedkms;
            $budgetedhrs = $tripdata[0]->budgetedhrs;
            $newsdate = date("Y-m-d", strtotime($STdate));
            $edate = date("Y-m-d", strtotime($EDdate));
            $firstodometer = 0;
            $lastodometer = 0;
            $lastodometer = $getend_odometer; // last odometer
            $firstodometer = $getstart_odometer; // first odometer
            if ($lastodometer < $firstodometer) {
                $tripobj = new Trips($_SESSION['customerno'], $_SESSION['userid']);
                //$lastodometermax = $tripobj->GetOdometerMax($edate, $tripdata[0]->unitno);
                $days = gendays($tripstart_date, $tripend_date);
                if (count($days) > 0) {
                    $lastodometerarr = array();
                    foreach ($days as $day) {
                        $lastodometerarr[] = $tripobj->GetOdometerMax($day, $tripdata[0]->unitno);
                    }
                    $lastodometermax = max($lastodometerarr);
                }
                $lastodometer = $lastodometermax + $lastodometer;
            }
            $totaldistance = $lastodometer - $firstodometer;
            $actualhrs = round((strtotime($tripstart_date) - strtotime($tripend_date)) / (60 * 60));
            if ($totaldistance != 0) {
                $res = $totaldistance / 1000;
            } else {
                $res = 0;
            }
            ////////////////Estimated Time calculate///////////////////////
            $estimated_time = 0;
            $estimated_time = $tripdata[0]->budgetedhrs - $actualhrs;
            ///////////////////////////////////////////////////////////////
            $middlecolumn = '<td style="text-align:left;border-right:none; width:250px;"><div> <span style="text-align:center; "> <u>Trip History</u> </span><br>
                    Triplogno : ' . $triplogno . '<br/> '
            . ' Route : ' . $routename . '<br/>'
            . ' Temprature Range : ' . round($mintemp) . ' To ' . round($maxtemp) . '<br/>'
                . ' Driver Name : ' . $drivername . '<br/>'
                . ' Driver No. : ' . $drivermobile1 . '<br/>'
                . ' Driver No.2 : ' . $drivermobile2 . '<br/>'
                . ' Total Budgeted kms : ' . $budgetedkms . 'kms<br/>'
                . ' Total Budgeted hrs : ' . $budgetedhrs . 'hrs<br/>'
                . ' Actual kms : ' . $res . 'kms<br/>'
                . ' Actual hrs : ' . $estimated_time . 'hrs<br/>'
                . ' Billing Party : ' . $billingparty . 'kms<br/>'
                . ' Consignor : ' . $consignor . '<br/>'
                . ' Consignee : ' . $consignee . '<br/> '
                . '  </div></td>';
        }
    }
    $totaldays = gendays($STdate, $EDdate);
    $days = array();
    $unitno = getunitnopdf($customerno, $deviceid);
    $count = count($totaldays);
    $endelement = end($totaldays);
    $count_arr = array_values($totaldays);
    $firstelement = array_shift($count_arr);
    $acinvertval = getacinvertval($unitno);
    $unit = getunitdetails($deviceid);
    if (isset($totaldays)) {
        foreach ($totaldays as $userdate) {
            $location = "../../customer/$customerno/unitno/$unitno/sqlite/$userdate.sqlite";
            if (!file_exists($location)) {
                continue;
            }
            $data = get_location_data($location, $count, $userdate, $firstelement, $endelement, $deviceid, $interval, $distance, $Shour, $Ehour, $customerno);
            if ($data != NULL && count($data) > 0) {
                $days = array_merge($days, $data);
            }
        }
    }
    if ($days != NULL && count($days) > 0) {
        $fromdate = date(speedConstants::DEFAULT_DATE, strtotime($STdate));
        $todate = date(speedConstants::DEFAULT_DATE, strtotime($EDdate));
        $title = 'Location Report';
        $subTitle = array(
            "Vehicle No: $vehicleno",
            "Start Date: $fromdate $Shour",
            "End Date: $todate $Ehour"
        );
        if (!is_null($vgroupname)) {
            $subTitle[] = "Group-name: $vgroupname";
        }
        if ($interval != null) {
            $subTitle[] = "Report Generated for every <b>$interval</b> mins";
        } else {
            $subTitle[] = "Report Generated for every <b>$distance</b> kms";
        }
        $finalreport = excel_header($title, $subTitle, NULL, $middlecolumn);
        $finalreport .= "<table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'><tbody>
            <tr style='background-color:#CCCCCC;font-weight:bold;'>
            <td style='width:50px;height:auto; text-align: center;'>Time</td>
            <td style='width:200px;height:auto; text-align: center;'>Location</td>";
        if ($customerno != 96 && $customerno != 66) {
            $finalreport .= "<td style='width:100px;height:auto; text-align: center;'>Speed [km/hr]</td>";
        }
        if ($_SESSION['temp_sensors'] == 1) {
            $finalreport .= "<td style='width:50px;height:auto;'>Temp 1</td>";
            $colspan = 5;
        }
        if ($_SESSION['temp_sensors'] == 2) {
            $finalreport .= "<td style='width:50px;height:auto;'>Temp 1</td>";
            $finalreport .= "<td style='width:50px;height:auto;'>Temp 2</td>";
            $colspan = 7;
        }
        if ($_SESSION['temp_sensors'] == 3) {
            $finalreport .= "<td style='width:50px;height:auto;'>Temp 1</td>";
            $finalreport .= "<td style='width:50px;height:auto;'>Temp 2</td>";
            $finalreport .= "<td style='width:50px;height:auto;'>Temp 3</td>";
            $colspan = 8;
        }
        if ($_SESSION['temp_sensors'] == 4) {
            $finalreport .= "<td style='width:50px;height:auto;'>Temp 1</td>";
            $finalreport .= "<td style='width:50px;height:auto;'>Temp 2</td>";
            $finalreport .= "<td style='width:50px;height:auto;'>Temp 3</td>";
            $finalreport .= "<td style='width:50px;height:auto;'>Temperature 4</td>";
            $colspan = 12;
        }
        if ($_SESSION['use_genset_sensor'] == 1) {
            $finalreport .= "<td style='width:50px;height:auto;'>Genset</td>";
        }
        $finalreport .= "<td style='width:50px;height:auto;'>Status</td>";
        $colspan = 6;
        if ($_SESSION['use_ac_sensor'] == 1) {
            $finalreport .= "<td style='width:50px;height:auto;'>Compressor <br> Status</td>";
            $colspan = 7;
        }
        if ($userid != 391 && $userid != 392 && $customerno != 96 && $customerno != 66) {
            $finalreport .= "<td style='width:100px;height:auto; text-align: center;'>Distance [km]</td>";
            $finalreport .= "<td style='width:100px;height:auto; text-align: center;'>Cumulative Distance [km]</td>";
        }
        $finalreport .= "</tr>";
        $finalreport .= create_location_csv_from_report($days, $unit, $customerno, $userid, $acinvertval);
    }
    echo $finalreport;
}

function getlocation_fromsqlite($location, $deviceid, $interval, $distance, $Shour, $Ehour, $userdate, $customerNo) {
    $devices = array();
    $query = "SELECT unithistory.digitalio,devicehistory.lastupdated, devicehistory.ignition, vehiclehistory.odometer,vehiclehistory.curspeed,vehiclehistory.vehicleid,devicehistory.devicelat, devicehistory.devicelong, analog1, analog2, analog3, analog4
              from devicehistory
              INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated
              INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            WHERE devicehistory.deviceid= $deviceid ";
    if ($customerNo != speedConstants::CUSTNO_APMT) {
        $query .= " AND COALESCE(devicehistory.status, '') !='F'";
    }
    if ($Shour != Null) {
        $query .= " AND devicehistory.lastupdated > '$userdate $Shour'";
    }
    if ($Ehour != Null) {
        $query .= " AND devicehistory.lastupdated < '$userdate $Ehour'";
    }
    $query .= " order by devicehistory.lastupdated ASC";
    try {
        $database = new PDO($location);
        $query1 = "SELECT max(odometer) as odometerm from vehiclehistory";
        $result1 = $database->query($query1);
        if (isset($result1) && $result1 != "") {
            foreach ($result1 as $row) {
                $ODOMETER = $row['odometerm'];
            }
        }
        $result = $database->query($query);
        if (isset($result) && $result != "") {
            $lastupdated = "";
            $distanceval = "";
            foreach ($result as $row) {
                if ($lastupdated == "") {
                    $lastupdated = $row["lastupdated"];
                }
                if ($distanceval == "") {
                    $distanceval = $row["odometer"];
                    $device = new stdClass();
                    $device->lastupdated = $row['lastupdated'];
                    $device->starttime = $row['lastupdated'];
                    $device->endtime = $row['lastupdated'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->curspeed = $row["curspeed"];
                    $device->deviceid = $row["vehicleid"];
                    $device->analog1 = $row["analog1"];
                    $device->analog2 = $row["analog2"];
                    $device->analog3 = $row["analog3"];
                    $device->analog4 = $row["analog4"];
                    $device->digitalio = $row["digitalio"];
                    $device->ignition = $row["ignition"];
                    $device->odometer = $row["odometer"];
                    if ($row['odometer'] < $distanceval) {
                        $row['odometer'] = $ODOMETER + $row['odometer'];
                    }
                    $device->distancecumulative = (($row["odometer"] - $distanceval) / 1000);
                    $devices[] = $device;
                } else {
                    if ($interval != null) {
                        if ($interval == 1) {
                            $device = new stdClass();
                            $device->lastupdated = $row['lastupdated'];
                            $device->starttime = $row['lastupdated'];
                            $device->endtime = $row['lastupdated'];
                            $device->devicelat = $row['devicelat'];
                            $device->devicelong = $row['devicelong'];
                            $device->curspeed = $row["curspeed"];
                            $device->deviceid = $row["vehicleid"];
                            $device->analog1 = $row["analog1"];
                            $device->analog2 = $row["analog2"];
                            $device->analog3 = $row["analog3"];
                            $device->analog4 = $row["analog4"];
                            $device->digitalio = $row["digitalio"];
                            $device->ignition = $row["ignition"];
                            $device->odometer = $row["odometer"];
                            if ($row['odometer'] < $distanceval) {
                                $row['odometer'] = $ODOMETER + $row['odometer'];
                            }
                            $device->distancecumulative = (($row["odometer"] - $distanceval) / 1000);
                            $devices[] = $device;
                            $lastupdated = $row["lastupdated"];
                        } elseif (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) > $interval) {
                            $device = new stdClass();
                            $device->lastupdated = $row['lastupdated'];
                            $device->starttime = $row['lastupdated'];
                            $device->endtime = $row['lastupdated'];
                            $device->devicelat = $row['devicelat'];
                            $device->devicelong = $row['devicelong'];
                            $device->curspeed = $row["curspeed"];
                            $device->deviceid = $row["vehicleid"];
                            $device->analog1 = $row["analog1"];
                            $device->analog2 = $row["analog2"];
                            $device->analog3 = $row["analog3"];
                            $device->analog4 = $row["analog4"];
                            $device->digitalio = $row["digitalio"];
                            $device->ignition = $row["ignition"];
                            $device->odometer = $row["odometer"];
                            if ($row['odometer'] < $distanceval) {
                                $row['odometer'] = $ODOMETER + $row['odometer'];
                            }
                            $device->distancecumulative = (($row["odometer"] - $distanceval) / 1000);
                            $devices[] = $device; //ganesh - added
                            $lastupdated = $row["lastupdated"];
                        }
                    }
                }
                if ($distance != null && $distance != "") {
                    if ($row["odometer"] - $distanceval > $distance * 1000) {
                        $device = new stdClass();
                        $device->lastupdated = $row['lastupdated'];
                        $device->starttime = $row['lastupdated'];
                        $device->endtime = $row['lastupdated'];
                        $device->devicelat = $row['devicelat'];
                        $device->devicelong = $row['devicelong'];
                        $device->curspeed = $row["curspeed"];
                        $device->deviceid = $row["vehicleid"];
                        $device->analog1 = $row["analog1"];
                        $device->analog2 = $row["analog2"];
                        $device->analog3 = $row["analog3"];
                        $device->analog4 = $row["analog4"];
                        $device->digitalio = $row["digitalio"];
                        $device->ignition = $row["ignition"];
                        $device->odometer = $row["odometer"];
                        if ($row['odometer'] < $distanceval) {
                            $row['odometer'] = $ODOMETER + $row['odometer'];
                        }
                        $device->distancecumulative = (($row["odometer"] - $distanceval) / 1000);
                        $devices[] = $device;
                        if ($row["ignition"] == "1") {
                            $distanceval = $row["odometer"];
                        }
                    }
                }
            }
        } else {
            $device = new stdClass();
            $device->lastupdated = isset($row['lastupdated']) ? $row['lastupdated'] : '';
            $device->starttime = isset($row['lastupdated']) ? $row['lastupdated'] : '';
            $device->endtime = isset($row['lastupdated']) ? $row['lastupdated'] : '';
            $device->devicelat = isset($row['devicelat']) ? $row['devicelat'] : 0;
            $device->devicelong = isset($row['devicelong']) ? $row['devicelong'] : 0;
            $device->curspeed = isset($row["curspeed"]) ? $row["curspeed"] : '';
            $device->deviceid = isset($row["vehicleid"]) ? $row["vehicleid"] : '';
            $device->analog1 = isset($row["analog1"]) ? $row["analog1"] : '';
            $device->analog2 = isset($row["analog2"]) ? $row["analog2"] : '';
            $device->analog3 = isset($row["analog3"]) ? $row["analog3"] : '';
            $device->analog4 = isset($row["analog4"]) ? $row["analog4"] : '';
            $device->digitalio = isset($row["digitalio"]) ? $row["digitalio"] : '';
            $device->ignition = isset($row["ignition"]) ? $row["ignition"] : '';
            $device->odometer = isset($row["odometer"]) ? $row["odometer"] : '';
            if (isset($row['odometer'])) {
                if ($row['odometer'] < $distanceval) {
                    $row['odometer'] = $ODOMETER + $row['odometer'];
                }
                $device->distancecumulative = (($row["odometer"] - $distanceval) / 1000);
            } else {
                $device->distancecumulative = 0;
            }
            $devices[] = $device;
        }
    } catch (PDOException $e) {
        die($e);
    }
    return $devices;
}

function getunitdetails($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $unitno = $um->getunitdetailsfromdeviceid($deviceid);
    return $unitno;
}

function create_locationhtml_from_report($datarows, $vehicle, $lastdate = null, $acinvertval) {
    //echo "<pre>"; //print_r($datarows);
    $display = '';
    $old_distance = 0;
    $oldtemp1 = '';
    $oldtemp2 = '';
    $oldtemp3 = '';
    $oldtemp4 = '';
    $cumulativedistance = 0;
    $currentdistance = 0;
    $prevdistance = 0;
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $vehicle->get_conversion;
    $tempconversion->use_humidity = $_SESSION['use_humidity'];
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
                }
                $display .= "<tr><th align='center' colspan = '100%'>" . date('d-m-Y', strtotime($change->endtime)) . "</th></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $prevdistance = 0;
            }
            $currentdate = date("Y-m-d H:i:s");
            $currentdate = substr($currentdate, '0', 11);
            $test = strtotime($change->lastupdated);
            $display .= "<tr><td>" . date(speedConstants::DEFAULT_TIME, strtotime($change->lastupdated)) . "</td>";
            $use_geolocation = get_usegeolocation($_SESSION['customerno']);
            if ((($change->devicelat == '0' && $change->devicelong == '0') || ($change->distancecumulative == $old_distance && $_SESSION['customerno'] != 96 && $_SESSION['customerno'] != 66)) && isset($old_location)) {
                $location = $old_location;
            } else {
                if (19.012969 < $change->devicelat && $change->devicelat < 19.043315 && 72.809920 < $change->devicelong && $change->devicelong < 72.822967) {
                    $location = "Near Bandra Worli Sea Link, Mumbai, India";
                } else {
                    $location = location($change->devicelat, $change->devicelong, $use_geolocation);
                }
            }
            $display .= "<td>$location</td>";
            if ($_SESSION['customerno'] != 96 && $_SESSION['customerno'] != 66) {
                $display .= "<td>$change->curspeed</td>";
            }
            if ($_SESSION['temp_sensors'] == 1) {
                $temp = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp = getTempUtil($tempconversion);
                } else {
                    $temp = '-';
                }
                if ($temp == '-') {
                    $temp = $oldtemp1;
                }
                if ($temp != '-' && $temp != "Not Active") {
                    $display .= "<td>$temp</td>";
                } else {
                    $display .= "<td>$temp</td>";
                }
                $oldtemp1 = $temp;
            }
            if ($_SESSION['temp_sensors'] == 2) {
                $temp1 = 'Not Active';
                $temp2 = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp1 = getTempUtil($tempconversion);
                } else {
                    $temp1 = '-';
                }
                $s = "analog" . $vehicle->tempsen2;
                if ($vehicle->tempsen2 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp2 = getTempUtil($tempconversion);
                } else {
                    $temp2 = '-';
                }
                if ($temp1 == '-') {
                    $temp1 = $oldtemp1;
                }
                if ($temp2 == '-') {
                    $temp2 = $oldtemp2;
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
                $oldtemp1 = $temp1;
                $oldtemp2 = $temp2;
            }
            if ($_SESSION['temp_sensors'] == 3) {
                $temp1 = 'Not Active';
                $temp2 = 'Not Active';
                $temp3 = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp1 = getTempUtil($tempconversion);
                } else {
                    $temp1 = '-';
                }
                $s = "analog" . $vehicle->tempsen2;
                if ($vehicle->tempsen2 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp2 = getTempUtil($tempconversion);
                } else {
                    $temp2 = '-';
                }
                $s = "analog" . $vehicle->tempsen3;
                if ($vehicle->tempsen3 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp3 = getTempUtil($tempconversion);
                } else {
                    $temp3 = '-';
                }
                if ($temp1 == '-') {
                    $temp1 = $oldtemp1;
                }
                if ($temp2 == '-') {
                    $temp2 = $oldtemp2;
                }
                if ($temp3 == '-') {
                    $temp3 = $oldtemp3;
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
                if ($temp3 != '-' && $temp3 != "Not Active") {
                    $display .= "<td>$temp3</td>";
                } else {
                    $display .= "<td>$temp3</td>";
                }
                $oldtemp1 = $temp1;
                $oldtemp2 = $temp2;
                $oldtemp3 = $temp3;
            }
            if ($_SESSION['temp_sensors'] == 4) {
                $temp1 = 'Not Active';
                $temp2 = 'Not Active';
                $temp3 = 'Not Active';
                $temp4 = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp1 = getTempUtil($change->$s);
                } else {
                    $temp1 = '-';
                }
                $s2 = "analog" . $vehicle->tempsen2;
                if ($vehicle->tempsen2 != 0 && $change->$s2 != 0) {
                    $tempconversion->rawtemp = $change->$s2;
                    $temp2 = getTempUtil($tempconversion);
                } else {
                    $temp2 = '-';
                }
                $s3 = "analog" . $vehicle->tempsen3;
                if ($vehicle->tempsen3 != 0 && $change->$s3 != 0) {
                    $tempconversion->rawtemp = $change->$s3;
                    $temp3 = getTempUtil($tempconversion);
                } else {
                    $temp3 = '-';
                }
                $s4 = "analog" . $vehicle->tempsen4;
                if ($vehicle->tempsen4 != 0 && $change->$s4 != 0) {
                    $tempconversion->rawtemp = $change->$s4;
                    $temp4 = getTempUtil($tempconversion);
                } else {
                    $temp4 = '-';
                }
                if ($temp1 == '-') {
                    $temp1 = $oldtemp1;
                }
                if ($temp2 == '-') {
                    $temp2 = $oldtemp2;
                }
                if ($temp3 == '-') {
                    $temp3 = $oldtemp3;
                }
                if ($temp4 == '-') {
                    $temp4 = $oldtemp4;
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
                if ($temp3 != '-' && $temp3 != "Not Active") {
                    $display .= "<td>$temp3</td>";
                } else {
                    $display .= "<td>$temp3</td>";
                }
                if ($temp4 != '-' && $temp4 != "Not Active") {
                    $display .= "<td>$temp4</td>";
                } else {
                    $display .= "<td>$temp4</td>";
                }
                $oldtemp1 = $temp1;
                $oldtemp2 = $temp2;
                $oldtemp3 = $temp3;
                $oldtemp4 = $temp4;
            }
            if ($_SESSION['use_genset_sensor'] == 1) {
                if ($acinvertval == 1) {
                    if ($change->digitalio == 0) {
                        $display .= '<td>OFF </td>';
                    } else {
                        $display .= '<td>ON</td>';
                    }
                } else {
                    if ($change->digitalio == 0) {
                        $display .= '<td>ON</td>';
                    } else {
                        $display .= '<td>OFF</td>';
                    }
                }
            }
            // Add New column for status running or idle - Ganesh
            if ($change->ignition == 1 && round($change->distancecumulative, 1) > 0 && round($change->distancecumulative, 1) != round($old_distance, 1)) {
                $display .= "<td style='cursor:pointer;'>Running</td>";
            } else {
                if ($change->ignition == 1) {
                    $display .= "<td>Idle - Ignition On</td>";
                } else {
                    $display .= "<td>Idle - Ignition Off</td>";
                }
            }
            //show compressor status on or off
            if ($_SESSION['use_ac_sensor'] == 1) {
                if ($acinvertval == 1) {
                    if ($change->digitalio == 0) {
                        $display .= '<td>OFF </td>';
                    } else {
                        $display .= '<td>ON</td>';
                    }
                } else {
                    if ($change->digitalio == 0) {
                        $display .= '<td>ON</td>';
                    } else {
                        $display .= '<td>OFF</td>';
                    }
                }
            }
            if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392 && $_SESSION['customerno'] != 96 && $_SESSION['customerno'] != 66) {
                $curdist = isset($change->distancecumulative) ? $change->distancecumulative : 0;
                $prevdist = isset($prevdistance) ? $prevdistance : 0;
                $totaldist = abs($curdist - $prevdist);
                $display .= "<td>" . $totaldist . "</td>";
                $currentdistance = abs($change->distancecumulative);
                $cumulativedistance = abs(($currentdistance - $prevdistance) + $cumulativedistance);
                $display .= "<td>" . $cumulativedistance . "</td>";
                $display .= "<td><a id='added_$test' style='display:none;'><img src='../../images/added.png' alt='added as checkpoint' width='18' height='18'/></a>
                        <a href='#test_$test' id='add_$test' data-toggle='modal'><img src='../../images/add.png' alt='add as checkpoint' width='18' height='18'/></a> </td>";
            }
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
            $old_location = $location;
            $old_distance = $change->distancecumulative;
            $prevdistance = $currentdistance;
        }
    }
    $display .= '</tbody>';
    $display .= '</table></div>';
    return $display;
}

function gettemp($rawtemp) {
    if ($_SESSION['use_humidity'] == 1) {
        $temp = round($rawtemp / 100);
    } else {
        $temp = round((($rawtemp - 1150) / 4.45), 1);
    }
    return $temp;
}

function location($lat, $long, $usegeolocation, $customerno = null) {
    $address = NULL;
    $customerno = (!isset($customerno)) ? $_SESSION['customerno'] : $customerno;
    $GeoCoder_Obj = new GeoCoder($customerno);
    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
    return $address;
}

function chklocation($lat, $long, $userid) {
    $chk = new CheckpointManager($_SESSION['customerno']);
    $result = $chk->CheckLocation($lat, $long, $userid);
    return $result;
}

function locationpdf($customerno, $lat, $long, $usegeolocation) {
    $address = NULL;
    $GeoCoder_Obj = new GeoCoder($customerno);
    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
    return $address;
}

function get_usegeolocation($customerno) {
    $GeoCoder_Obj = new GeoCoder($customerno);
    $geolocation = $GeoCoder_Obj->get_use_geolocation();
    return $geolocation;
}

function create_location_pdf_from_report($datarows, $vehicle, $customerno, $userid, $acinvertval) {
    $runningtime = 0;
    $idletime = 0;
    $lastdate = NULL;
    $display = $old_distance = $oldtemp1 = $oldtemp2 = $oldtemp3 = $oldtemp4 = '';
    $cumulativedistance = 0;
    $currentdistance = 0;
    $prevdistance = 0;
    $colspan = '4';
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $vehicle->get_conversion;
    $tempconversion->use_humidity = $_SESSION['use_humidity'];
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                $tdcount = 0;
                $display .= "</tbody></table>
                <hr  id='style-six' /><br/><table id='search_table_2' align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse;'>
                <tbody>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $display .= "
                    <tr style='background-color:#CCCCCC;font-weight:bold;'>
                    <td style='width:100px;height:auto;'>Time</td>
                    <td style='width:400px;height:auto;'>Location</td>";
                $tdcount = 2;
                if ($customerno != 96 && $customerno != 66) {
                    $display .= "<td style='width:50px;height:auto;'>Speed [km/hr]</td>";
                    $tdcount = $tdcount + 1;
                }
                if ($_SESSION['temp_sensors'] == 1) {
                    $display .= "<td style='width:50px;height:auto;'>Temp 1</td>";
                    $tdcount = $tdcount + 1;
                }
                if ($_SESSION['temp_sensors'] == 2) {
                    $display .= "<td style='width:50px;height:auto;'>Temp 1</td>";
                    $display .= "<td style='width:50px;height:auto;'>Temp 2</td>";
                    $tdcount = $tdcount + 2;
                }
                if ($_SESSION['temp_sensors'] == 3) {
                    $display .= "<td style='width:50px;height:auto;'>Temp 1</td>";
                    $display .= "<td style='width:50px;height:auto;'>Temp 2</td>";
                    $display .= "<td style='width:50px;height:auto;'>Temp 3</td>";
                    $tdcount = $tdcount + 3;
                }
                if ($_SESSION['temp_sensors'] == 4) {
                    $display .= "<td style='width:50px;height:auto;'>Temp 1</td>";
                    $display .= "<td style='width:50px;height:auto;'>Temp 2</td>";
                    $display .= "<td style='width:50px;height:auto;'>Temp 3</td>";
                    $display .= "<td style='width:50px;height:auto;'>Temperature 4</td>";
                    $tdcount = $tdcount + 4;
                }
                if ($_SESSION['use_genset_sensor'] == 1) {
                    $display .= "<td style='width:50px;height:auto;'>Genset</td>";
                    $tdcount = $tdcount + 1;
                }
                $display .= "<td style='width:50px;height:auto;'>Status</td>";
                $tdcount = $tdcount + 1;
                if ($_SESSION['use_ac_sensor'] == 1) {
                    $display .= "<td style='width:50px;height:auto;'>Compressor <br> Status</td>";
                    $tdcount = $tdcount + 1;
                }
                if ($userid != 391 && $userid != 392 && $customerno != 96 && $customerno != 66) {
                    $display .= "<td style='width:50px;height:auto;'>Distance [km]</td>";
                    $display .= "<td style='width:70px;height:auto;'>Cumulative Distance [KM] </td>";
                    $tdcount = $tdcount + 1;
                    $tdcount = $tdcount + 1;
                }
                $display .= "</tr>";
                $display .= "<tr style='background-color:#CCCCCC;font-weight:bold;'>"
                . "<td colspan='" . $tdcount . "'>Date " . date('d-m-Y', strtotime($change->endtime)) .
                    "</td></tr>";
                $prevdistance = 0;
            }
            $display .= "<tr><td style='width:100px;height:auto;'>" . date(speedConstants::DEFAULT_TIME, strtotime($change->lastupdated)) . "</td>";
            $use_geolocation = get_usegeolocation($customerno);
            if ((($change->devicelat == '0' && $change->devicelong == '0') || ($change->distancecumulative == $old_distance && $customerno != 96 && $customerno != 66)) && isset($old_location)) {
                $location = $old_location;
            } else {
                if (19.012969 < $change->devicelat && $change->devicelat < 19.043315 && 72.809920 < $change->devicelong && $change->devicelong < 72.822967) {
                    $location = "Near Bandra Worli Sea Link, Mumbai, India";
                } else {
                    $location = locationpdf($customerno, $change->devicelat, $change->devicelong, $use_geolocation);
                }
            }
            $display .= "<td style='width:400px;height:auto;'>" . $location . "</td>";
            if ($customerno != 96 && $customerno != 66) {
                $display .= "<td style='width:150px;height:auto;'>" . $change->curspeed . "</td>";
            }
            if ($_SESSION['temp_sensors'] == 1) {
                $temp = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp = getTempUtil($tempconversion);
                } else {
                    $temp = '-';
                }
                if ($temp == '-') {
                    $temp = $oldtemp1;
                }
                if ($temp != '-' && $temp != "Not Active") {
                    $display .= "<td>$temp</td>";
                } else {
                    $display .= "<td>$temp</td>";
                }
                $oldtemp1 = $temp;
            }
            if ($_SESSION['temp_sensors'] == 2) {
                $temp1 = 'Not Active';
                $temp2 = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp1 = getTempUtil($tempconversion);
                } else {
                    $temp1 = '-';
                }
                $s = "analog" . $vehicle->tempsen2;
                if ($vehicle->tempsen2 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp2 = getTempUtil($tempconversion);
                } else {
                    $temp2 = '-';
                }
                if ($temp1 == '-') {
                    $temp1 = $oldtemp1;
                }
                if ($temp2 == '-') {
                    $temp2 = $oldtemp2;
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
                $oldtemp1 = $temp1;
                $oldtemp2 = $temp2;
            }
            if ($_SESSION['temp_sensors'] == 3) {
                $temp1 = 'Not Active';
                $temp2 = 'Not Active';
                $temp3 = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp1 = getTempUtil($tempconversion);
                } else {
                    $temp1 = '-';
                }
                $s = "analog" . $vehicle->tempsen2;
                if ($vehicle->tempsen2 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp2 = getTempUtil($tempconversion);
                } else {
                    $temp2 = '-';
                }
                $s = "analog" . $vehicle->tempsen3;
                if ($vehicle->tempsen3 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp3 = getTempUtil($tempconversion);
                } else {
                    $temp3 = '-';
                }
                if ($temp1 == '-') {
                    $temp1 = $oldtemp1;
                }
                if ($temp2 == '-') {
                    $temp2 = $oldtemp2;
                }
                if ($temp3 == '-') {
                    $temp3 = $oldtemp3;
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
                if ($temp3 != '-' && $temp3 != "Not Active") {
                    $display .= "<td>$temp3</td>";
                } else {
                    $display .= "<td>$temp3</td>";
                }
                $oldtemp1 = $temp1;
                $oldtemp2 = $temp2;
                $oldtemp3 = $temp3;
            }
            if ($_SESSION['temp_sensors'] == 4) {
                $temp1 = 'Not Active';
                $temp2 = 'Not Active';
                $temp3 = 'Not Active';
                $temp4 = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp1 = getTempUtil($tempconversion);
                } else {
                    $temp1 = '-';
                }
                $s2 = "analog" . $vehicle->tempsen2;
                if ($vehicle->tempsen2 != 0 && $change->$s2 != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp2 = getTempUtil($tempconversion);
                } else {
                    $temp2 = '-';
                }
                $s3 = "analog" . $vehicle->tempsen3;
                if ($vehicle->tempsen3 != 0 && $change->$s3 != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp3 = getTempUtil($tempconversion);
                } else {
                    $temp3 = '-';
                }
                $s4 = "analog" . $vehicle->tempsen4;
                if ($vehicle->tempsen4 != 0 && $change->$s4 != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp4 = getTempUtil($tempconversion);
                } else {
                    $temp4 = '-';
                }
                if ($temp1 == '-') {
                    $temp1 = $oldtemp1;
                }
                if ($temp2 == '-') {
                    $temp2 = $oldtemp2;
                }
                if ($temp3 == '-') {
                    $temp3 = $oldtemp3;
                }
                if ($temp4 == '-') {
                    $temp4 = $oldtemp4;
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
                if ($temp3 != '-' && $temp3 != "Not Active") {
                    $display .= "<td>$temp3</td>";
                } else {
                    $display .= "<td>$temp3</td>";
                }
                if ($temp4 != '-' && $temp4 != "Not Active") {
                    $display .= "<td>$temp4</td>";
                } else {
                    $display .= "<td>$temp4</td>";
                }
                $oldtemp1 = $temp1;
                $oldtemp2 = $temp2;
                $oldtemp3 = $temp3;
                $oldtemp4 = $temp4;
            }
            if ($_SESSION['use_genset_sensor'] == 1) {
                if ($acinvertval == 1) {
                    if ($change->digitalio == 0) {
                        $display .= '<td>OFF</td>';
                    } else {
                        $display .= '<td>ON</td>';
                    }
                } else {
                    if ($change->digitalio == 0) {
                        $display .= '<td>ON</td>';
                    } else {
                        $display .= '<td>OFF</td>';
                    }
                }
            }
            // Add New column for status running or idle - Ganesh
            if ($change->ignition == 1 && round($change->distancecumulative, 1) > 0 && round($change->distancecumulative, 1) != round($old_distance, 1)) {
                $display .= "<td style='cursor:pointer;'> Running</td>";
            } else {
                if ($change->ignition == 1) {
                    $display .= "<td>Idle - Ignition On</td>";
                } else {
                    $display .= "<td>Idle - Ignition Off</td>";
                }
            }
            //show compressor status on or off
            if ($_SESSION['use_ac_sensor'] == 1) {
                if ($acinvertval == 1) {
                    if ($change->digitalio == 0) {
                        $display .= '<td>OFF </td>';
                    } else {
                        $display .= '<td>ON</td>';
                    }
                } else {
                    if ($change->digitalio == 0) {
                        $display .= '<td>ON</td>';
                    } else {
                        $display .= '<td>OFF</td>';
                    }
                }
            }
            if ($userid != 391 && $userid != 392 && $customerno != 96 && $customerno != 66) {
                $curdist = isset($change->distancecumulative) ? $change->distancecumulative : 0;
                $prevdist = isset($prevdistance) ? $prevdistance : 0;
                $totaldist = abs($curdist - $prevdist);
                $display .= "<td style='width:50px;height:auto;'>" . $totaldist . "</td>";
                $currentdistance = $change->distancecumulative;
                $cumulativedistance = abs(abs($currentdistance - $prevdistance) + $cumulativedistance);
                $display .= "<td style='width:50px;height:auto;'>" . $cumulativedistance . "</td>";
            }
            $display .= '</tr>';
            $old_location = $location;
            $old_distance = $change->distancecumulative;
            $prevdistance = $currentdistance;
        }
    }
    $display .= '</tbody></table>';
    $display .= "<page_footer>[[page_cu]]/[[page_nb]]</page_footer>";
    $display .= "<hr style='margin-top:5px;'>";
    $formatdate1 = date(speedConstants::DEFAULT_DATETIME);
    $display .= "<div align='right' style='text-align:center;'> Report Generated On: $formatdate1 </div>";
    return $display;
}

function create_location_csv_from_report($datarows, $vehicle, $customerno, $userid, $acinvertval) {
    $lastdate = NULL;
    $display = '';
    $old_distance = '';
    $oldtemp1 = '';
    $oldtemp2 = '';
    $oldtemp3 = '';
    $oldtemp4 = '';
    $colspan = '4';
    $cumulativedistance = 0;
    $currentdistance = 0;
    $prevdistance = 0;
    $tempconversion = new TempConversion();
    $tempconversion->unit_type = $vehicle->get_conversion;
    $tempconversion->use_humidity = $_SESSION['use_humidity'];
    if (isset($datarows)) {
        foreach ($datarows as $change) {
            $comparedate = date('d-m-Y', strtotime($change->endtime));
            if (strtotime($lastdate) != strtotime($comparedate)) {
                $display .= "<tr><td style='width:335px;height:auto; text-align: center;background-color:#CCCCCC;font-weight:bold;' colspan=100%>$comparedate</td></tr>";
                $lastdate = date('d-m-Y', strtotime($change->endtime));
                $prevdistance = 0;
            }
            $display .= "<td style='width:50px;height:auto; text-align: center;'>" . date(speedConstants::DEFAULT_TIME, strtotime($change->lastupdated)) . "</td>";
            $use_geolocation = get_usegeolocation($customerno);
            if ((($change->devicelat == '0' && $change->devicelong == '0') || ($change->distancecumulative == $old_distance && $customerno != 96 && $customerno != 66)) && isset($old_location)) {
                $location = $old_location;
            } else {
                if (19.012969 < $change->devicelat && $change->devicelat < 19.043315 && 72.809920 < $change->devicelong && $change->devicelong < 72.822967) {
                    $location = "Near Bandra Worli Sea Link, Mumbai, India";
                } else {
                    $location = locationpdf($customerno, $change->devicelat, $change->devicelong, $use_geolocation);
                }
            }
            $display .= "<td style='width:300px;height:auto; text-align: center;'>" . $location . "</td>";
            if ($customerno != 96 && $customerno != 66) {
                $display .= "<td style='width:100px;height:auto; text-align: center;'>" . $change->curspeed . "</td>";
            }
            if ($_SESSION['temp_sensors'] == 1) {
                $temp = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp = getTempUtil($tempconversion);
                } else {
                    $temp = '-';
                }
                if ($temp == '-') {
                    $temp = $oldtemp1;
                }
                if ($temp != '-' && $temp != "Not Active") {
                    $display .= "<td>$temp</td>";
                } else {
                    $display .= "<td>$temp</td>";
                }
                $oldtemp1 = $temp;
            }
            if ($_SESSION['temp_sensors'] == 2) {
                $temp1 = 'Not Active';
                $temp2 = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp1 = getTempUtil($tempconversion);
                } else {
                    $temp1 = '-';
                }
                $s = "analog" . $vehicle->tempsen2;
                if ($vehicle->tempsen2 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp2 = getTempUtil($tempconversion);
                } else {
                    $temp2 = '-';
                }
                if ($temp1 == '-') {
                    $temp1 = $oldtemp1;
                }
                if ($temp2 == '-') {
                    $temp2 = $oldtemp2;
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
                $oldtemp1 = $temp1;
                $oldtemp2 = $temp2;
            }
            if ($_SESSION['temp_sensors'] == 3) {
                $temp1 = 'Not Active';
                $temp2 = 'Not Active';
                $temp3 = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp1 = getTempUtil($tempconversion);
                } else {
                    $temp1 = '-';
                }
                $s = "analog" . $vehicle->tempsen2;
                if ($vehicle->tempsen2 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp2 = getTempUtil($tempconversion);
                } else {
                    $temp2 = '-';
                }
                $s = "analog" . $vehicle->tempsen3;
                if ($vehicle->tempsen3 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp3 = getTempUtil($tempconversion);
                } else {
                    $temp3 = '-';
                }
                if ($temp1 == '-') {
                    $temp1 = $oldtemp1;
                }
                if ($temp2 == '-') {
                    $temp2 = $oldtemp2;
                }
                if ($temp3 == '-') {
                    $temp3 = $oldtemp3;
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
                if ($temp3 != '-' && $temp3 != "Not Active") {
                    $display .= "<td>$temp3</td>";
                } else {
                    $display .= "<td>$temp3</td>";
                }
                $oldtemp1 = $temp1;
                $oldtemp2 = $temp2;
                $oldtemp3 = $temp3;
            }
            if ($_SESSION['temp_sensors'] == 4) {
                $temp1 = 'Not Active';
                $temp2 = 'Not Active';
                $temp3 = 'Not Active';
                $temp4 = 'Not Active';
                $s = "analog" . $vehicle->tempsen1;
                if ($vehicle->tempsen1 != 0 && $change->$s != 0) {
                    $tempconversion->rawtemp = $change->$s;
                    $temp1 = getTempUtil($tempconversion);
                } else {
                    $temp1 = '-';
                }
                $s2 = "analog" . $vehicle->tempsen2;
                if ($vehicle->tempsen2 != 0 && $change->$s2 != 0) {
                    $tempconversion->rawtemp = $change->$s2;
                    $temp2 = getTempUtil($tempconversion);
                } else {
                    $temp2 = '-';
                }
                $s3 = "analog" . $vehicle->tempsen3;
                if ($vehicle->tempsen3 != 0 && $change->$s3 != 0) {
                    $tempconversion->rawtemp = $change->$s3;
                    $temp3 = getTempUtil($tempconversion);
                } else {
                    $temp3 = '-';
                }
                $s4 = "analog" . $vehicle->tempsen4;
                if ($vehicle->tempsen4 != 0 && $change->$s4 != 0) {
                    $tempconversion->rawtemp = $change->$s4;
                    $temp4 = getTempUtil($tempconversion);
                } else {
                    $temp4 = '-';
                }
                if ($temp1 == '-') {
                    $temp1 = $oldtemp1;
                }
                if ($temp2 == '-') {
                    $temp2 = $oldtemp2;
                }
                if ($temp3 == '-') {
                    $temp3 = $oldtemp3;
                }
                if ($temp4 == '-') {
                    $temp4 = $oldtemp4;
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
                if ($temp3 != '-' && $temp3 != "Not Active") {
                    $display .= "<td>$temp3</td>";
                } else {
                    $display .= "<td>$temp3</td>";
                }
                if ($temp4 != '-' && $temp4 != "Not Active") {
                    $display .= "<td>$temp4</td>";
                } else {
                    $display .= "<td>$temp4</td>";
                }
                $oldtemp1 = $temp1;
                $oldtemp2 = $temp2;
                $oldtemp3 = $temp3;
                $oldtemp4 = $temp4;
            }
            if ($_SESSION['use_genset_sensor'] == 1) {
                if ($acinvertval == 1) {
                    if ($change->digitalio == 0) {
                        $display .= '<td>OFF</td>';
                    } else {
                        $display .= '<td>ON</td>';
                    }
                } else {
                    if ($change->digitalio == 0) {
                        $display .= '<td>ON</td>';
                    } else {
                        $display .= '<td>OFF</td>';
                    }
                }
            }
            // Add New column for status running or idle - Ganesh
            if ($change->ignition == 1 && round($change->distancecumulative, 1) > 0) {
                $display .= "<td style='cursor:pointer;'> Running</td>";
            } else {
                if ($change->ignition == 1) {
                    $display .= "<td>Idle - Ignition On</td>";
                } else {
                    $display .= "<td>Idle - Ignition Off</td>";
                }
            }
            //show compressor status on or off
            if ($_SESSION['use_ac_sensor'] == 1) {
                if ($acinvertval == 1) {
                    if ($change->digitalio == 0) {
                        $display .= '<td>OFF </td>';
                    } else {
                        $display .= '<td>ON</td>';
                    }
                } else {
                    if ($change->digitalio == 0) {
                        $display .= '<td>ON</td>';
                    } else {
                        $display .= '<td>OFF</td>';
                    }
                }
            }
            if ($userid != 391 && $userid != 392 && $customerno != 96 && $customerno != 66) {
                $curdist = isset($change->distancecumulative) ? $change->distancecumulative : 0;
                $prevdist = isset($prevdistance) ? $prevdistance : 0;
                $totaldist = abs($curdist - $prevdist);
                $display .= "<td style='width:100px;height:auto; text-align: center;'>" . $totaldist . "</td>";
                $currentdistance = $change->distancecumulative;
                $cumulativedistance = abs(abs($currentdistance - $prevdistance) + $cumulativedistance);
                $display .= "<td>" . $cumulativedistance . "</td>";
            }
            $display .= '</tr>';
            $old_location = $location;
            $old_distance = $change->distancecumulative;
            $prevdistance = $currentdistance;
        }
    }
    $display .= '</tbody>';
    $display .= "</table>";
    return $display;
}

function createcheck() {
    ?>
    <script type="text/javascript">
        alert("Test");
    </script>
    <?php
}

function getvehicleidfromdeviceid($deviceid) {
    $um = new UnitManager($_SESSION['customerno']);
    $vehicleid = $um->getvehicleidbydeviceid($deviceid);
    return $vehicleid;
}

?>
