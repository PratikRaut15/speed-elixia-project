<?php

include_once '../../config.inc.php';
include_once '../../lib/bo/UserManager.php';
include_once 'reports_location_functions.php';
include_once '../trips/class/TripsManager.php';
$userkey = $_GET['userkey'];
$triplogno = $_GET['triplogno'];
$deviceid = $_GET['deviceid'];
$sdate = $_GET['sdate'];
$stime = $_GET['stime'];
$edate = $_GET['edate'];
$etime = $_GET['etime'];
$interval = $_GET['interval'];
$vehicleno = $_GET['vehicleno'];
$distance = 1;
$frequency = 1;

$_SESSION['subdir'] = $subdir;
if (isset($_GET['sdate']) && isset($_GET['edate'])) {
    if (isset($userkey) && !empty($userkey)) {
        $user = new UserManager();
        loginwithuserkey($userkey);
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/bootstrap/css/bootstrap.css" type="text/css" />';
        echo '<link href="' . $_SESSION['subdir'] . '/css/bootstrap.css" rel="stylesheet" type="text/css" />';
        echo '<link href="' . $_SESSION['subdir'] . '/style/style.css" rel="stylesheet" type="text/css" />';
        ///ajax code started 
        $newsdate = date("Y-m-d", strtotime($sdate));
        $newedate = date("Y-m-d", strtotime($edate));
        $datecheck = datediff($_GET['sdate'], $_GET['edate']);
        $datediffcheck = date_SDiff($newsdate, $newedate);

        $tripobj = new Trips($_SESSION['customerno'], $_SESSION['userid']);
        $tripid = $tripobj->gettripidbytriplogno($triplogno);

        if ($datecheck == 1) {
            if ($datediffcheck <= 30) {
                $STdate = GetSafeValueString($_GET['sdate'], 'string');
                $STime = GetSafeValueString($_GET['stime'], 'string');
                $EDdate = GetSafeValueString($_GET['edate'], 'string');
                $ETime = GetSafeValueString($_GET['etime'], 'string');
                $interval = GetSafeValueString($_GET['interval'], 'long');
                $distance = 1;
                $vehicleno = GetSafeValueString($_GET['vehicleno'], 'string');
                $deviceid = 0;
                $deviceid = GetSafeValueString($_GET['deviceid'], 'long');
                if ((!isset($deviceid) || $deviceid == 0 || $deviceid == "NULL" || $deviceid == '') && isset($_GET['vehicleno'])) {
                    $vehicleno = GetSafeValueString($_GET['vehicleno'], 'string');
                    $devicemanager = new DeviceManager($_SESSION['customerno']);
                    $devices = $devicemanager->devicesformapping_byId($vehicleno);
                    if ($devices) {
                        foreach ($devices as $row) {
                            $deviceid = $row->deviceid;
                        }
                    }
                }

                if (isset($tripid) && $tripid != 0) {
                    $tripobj = new Trips($_SESSION['customerno'], $_SESSION['userid']);
                    $tripdetails = $tripobj->gettripdetailsedit($tripid);
                    if (isset($tripdetails) && !empty($tripdetails)) {
                        $tripdata = $tripdetails;
                        $mintemp = $tripdata[0]->mintemp;
                        $maxtemp = $tripdata[0]->maxtemp;
                    }
                }

                if ($deviceid != 0) {
                    if (empty($tripdata) && !isset($tripdata)) {
                        $tripdata = NULL;
                    }

                    $title = 'Location Report';
                    $subTitle = array(
                        "Vehicle No: {$_GET['vehicleno']}",
                        "Start Date: $STdate {$_GET['stime']}",
                        "End Date: $EDdate {$_GET['etime']}"
                    );
                    if ($frequency == 1) {
                        $subTitle[] = "Report Generated for every <b>$interval</b> mins";
                    } else {
                        $subTitle[] = "Report Generated for every <b>$distance</b> kms";
                    }
                    $columns = array(
                        'Time',
                        'Location'
                    );
                    $columnlinks = array(
                        '',
                        ''
                    );
                    if ($_SESSION['customerno'] != 96 && $_SESSION['customerno'] != 66) {
                        $columns[] = "Speed [km/hr]";
                        //$columnlinks[] = "";
                        $columnlinks[] = "<a href='javascript:void(0)' title='View Overspeed Report' >ViewReport</a>";
                    }
                    $tempCols = array();
                    $tempColLinks = array();
                    $tripmin = '';
                    $tripmax = '';

                    if (!empty($tripid)) {
                        $tripmax = $maxtemp;
                        $tripmin = $mintemp;
                    }
                    switch ($_SESSION['temp_sensors']) {
                        case 4:
                            $tempCols[] = "Temperature 4";
                            $tempColLinks[] = "<a href='javascript:void(0)' title='View Temperature Report' >ViewReport</a>";
                        case 3:
                            $tempCols[] = "Temperature 3";
                            $tempColLinks[] = "<a href='javascript:void(0)' title='View Temperature Report' >ViewReport</a>";
                        case 2:
                            $tempCols[] = "Temperature 2";
                            $tempColLinks[] = "<a href='javascript:void(0)' title='View Temperature Report' >ViewReport</a>";
                        case 1:
                            $tempCols[] = "Temperature 1";
                            $tempColLinks[] = "<a href='javascript:void(0)' title='View Temperature Report' >ViewReport</a>";
                    }
                    $tempCols = array_reverse($tempCols);
                    $tempColLinks = array_reverse($tempColLinks);
                    foreach ($tempCols as $tempCol) {
                        $columns[] = $tempCol;
                    }
                    foreach ($tempColLinks as $tempColLink) {
                        $columnlinks[] = $tempColLink;
                    }

                    if ($_SESSION['use_genset_sensor'] == 1) {
                        $columns[] = " Genset";
                        //$columnlinks[] = "";
                        $columnlinks[] = "<a href='javascript:void(0)' title='View Genset Report' >ViewReport</a>";
                    }

                    $columns[] = "Status";
                    $columnlinks[] = "";
                    if ($_SESSION['use_ac_sensor'] == 1) {
                        $columns[] = "Compressor<br> Status";
                        $columnlinks[] = "";
                    }
                    if ($_SESSION['userid'] != 391 && $_SESSION['userid'] != 392 && $_SESSION['customerno'] != 96 && $_SESSION['customerno'] != 66) {
                        $columns[] = "Distance [KM]";
                        $columns[] = "Cumulative Distance [KM] ";
                        $columns[] = "Add As Checkpoint";
                        $columnlinks[] = "";
                        $vehicledata = getvehicleidfromdeviceid($deviceid);
                        $vehicleid = $vehicledata['vehicleid'];



                        $columnlinks[] = "<a href='javascript:void(0)' title='View Route' onclick='getRouteHistReport(\"" . $vehicleid . "\",\"" . $EDdate . "\",\"" . $ETime . "\",\"" . $STdate . "\",\"" . $STime . "\",\"" . $deviceid . "\",\"" . $vehicleno . "\");'>ViewRoute</a>";
                        $columnlinks[] = '';
                        if (isset($tripid) && !empty($tripid)) {
                            $tripdetails = "<u>Trip History</u>";
                        }
                    }

                    $middlecolumn = $triplogno = $tripstatus = $routename = $budgetedkms = $budgetedhrs = $consignor = $consignee = $billingparty = $mintemp = $maxtemp = $drivername = $drivermobile1 = $drivermobile2 = '';
                    
                    if (isset($tripdata) && !empty($tripdata)) {
                        $edate = $newedate;
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
                        $firstodometer = 0;
                        $lastodometer = 0;
                        $lastodometer = $tripdata[0]->vehicleodometer;  // last odometer
                        $firstodometer = $tripdata[0]->loadingodometer; // first odometer
                        if ($lastodometer < $firstodometer) {
                            $tripobj = new Trips($_SESSION['customerno'], $_SESSION['userid']);
                            $lastodometermax = $tripobj->GetOdometerMax($edate, $tripdata[0]->unitno);
                            $lastodometer = $lastodometermax + $lastodometer;
                        }
                        $totaldistance = $lastodometer - $firstodometer;
                        $actualhrs = round((strtotime($newsdate) - strtotime($tripdata[0]->entrytime)) / (60 * 60));
                        if ($totaldistance != 0) {
                            $res = $totaldistance / 1000;
                        } else {
                            $res = 0;
                        }

                        ////////////////Estimated Time calculate///////////////////////
                        $estimated_time = 0;
                        $estimated_time = $tripdata[0]->budgetedhrs - $actualhrs;
                        ///////////////////////////////////////////////////////////////

                        $styleset2 = 'style="width:28%; text-align:left;"';
                        $middlecolumn = '<div class="newTableSubHeader" ' . $styleset2 . '> <span style="text-align:center; padding-left:20%;"> <u>Trip History</u> </span><br> 
               Triplogno : ' . $triplogno . '<br/> '
                                . ' Route : ' . $routename . '<br/>'
                                . ' Temprature Range : ' . round($mintemp) . ' To ' . round($maxtemp) . '<br/>'
                                . ' Driver Name : ' . $drivername . '<br/>'
                                . ' Driver No. : ' . $drivermobile1 . '<br/>'
                                . ' Driver No.2 : ' . $drivermobile2 . '<br/>'
                                . ' Total Budgeted kms : ' . $budgetedkms . 'kms<br/>'
                                . ' Total Budgeted hrs : ' . $budgetedhrs . 'hrs<br/>'
                                . ' Actual kms : ' . $res . 'kms<br/>'
                                . ' Actual Hrs : ' . $estimated_time . 'hrs<br/>'
                                . ' Billing Party : ' . $billingparty . 'kms<br/>'
                                . ' Consignor : ' . $consignor . '<br/>'
                                . ' Consignee : ' . $consignee . '<br/> '
                                . '  </div>';
                    }

                    echo table_header($title, $subTitle, $columns, FALSE, $middlecolumn);


                    if ($columnlinks != null) {
                        if (count($columnlinks) > 24) {
                            echo "<style>.newTable th, .newTable td{padding:4px;}</style>";
                        }
                        $header = '';
                        $header .= '<tr>';
                        foreach ($columnlinks as $s_columns) {
                            $header .= "<th>$s_columns</th>";
                        }
                        $header .= '</tr>';
                        echo $header;
                    }
                    ///locationrep end

                    if ($frequency == '1') {
                        getlocationreport($STdate, $EDdate, $deviceid, $_GET['stime'], $_GET['etime'], $interval, null);
                    } elseif ($frequency == '2') {
                        getlocationreport($STdate, $EDdate, $deviceid, $_GET['etime'], $_GET['etime'], null, $distance);
                    }
                } else {
                    echo "Please Select Vehicle";
                }
            } else {
                echo "Please Select Dates With Difference Of Not More Than 30 Days";
            }
        } else if ($datecheck == 0) {
            echo "Please Check The Date";
        } else {
            echo "Data Not Available";
        }
    } else {
        echo "user not authorized";
    }
}
?>

