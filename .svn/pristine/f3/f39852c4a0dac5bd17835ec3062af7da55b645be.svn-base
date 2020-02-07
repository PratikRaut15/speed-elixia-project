<?php

if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
    include_once 'reports_location_functions.php';
    include_once '../trips/class/TripsManager.php';

    $STdate = $_POST['STdate'];

    $EDdate = $_POST['EDdate'];


    if ($_POST["frequency"] == '2') {
        echo "<script>jQuery('#distanceid').show();jQuery('#intervalid').hide();</script>";
    }


    if (isset($_SESSION['ecodeid'])) {
        /*Client Code Validation */
        $validation = clientCodeValidation($_POST['s_start'], $_POST['e_end'], $_POST['days'], $STdate, $EDdate);
        if (isset($validation) && !empty($validation)) {
            if ($validation['isError'] == 1) {
                echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
                die();
            } else {
                $STdate = date('d-m-Y', strtotime($validation['reportStartDate']));
                $EDdate = date('d-m-Y', strtotime($validation['reportEndDate']));
                echo "<script>jQuery('#SDate').val('" . $STdate . "');</script>";
                echo "<script>jQuery('#EDate').val('" . $EDdate . "');</script>";
            }
        }
    }
    /* Date And Date Diff Check */
    $datecheck = datediff($STdate, $EDdate);
    $datediffcheck = date_SDiff($STdate, $EDdate);
    if ($datecheck == 1) {
        if ($datediffcheck <= 30) {
            $userkey = GetSafeValueString($_POST['userkey'], 'string');
            $triplogno = GetSafeValueString($_POST['triplogno'], 'string');
            if (!empty($triplogno)) {
                $tripobj = new Trips($_SESSION['customerno'], $_SESSION['userid']);
                $tripid = $tripobj->gettripidbytriplogno($triplogno);
            } else {
                $tripid = GetSafeValueString($_POST['tripid'], 'string');
            }

            $STime = GetSafeValueString($_POST['STime'], 'string');

            $ETime = GetSafeValueString($_POST['ETime'], 'string');
            $interval = GetSafeValueString($_POST['interval'], 'long');
            $distance = GetSafeValueString($_POST['distance'], 'long');
            $vehicleno = GetSafeValueString($_POST['vehicleno'], 'string');
            $deviceid = 0;
            $deviceid = GetSafeValueString($_POST['deviceid'], 'long');
            if ((!isset($deviceid) || $deviceid == 0 || $deviceid == "NULL" || $deviceid == '') && isset($_POST['vehicleno'])) {
                $vehicleno = GetSafeValueString($_POST['vehicleno'], 'string');
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

                //get startdate or enddate from triphistory
                $closedtripenddetails = $tripobj->closedtripdetails_end($tripid);
                $closedtripstartdetails = $tripobj->closedtripdetails_start($tripid);
                $tripstart_date = $closedtripstartdetails[0]['tripstart_date'];
                $tripend_date = $closedtripenddetails[0]['tripend_date'];
                if(empty($tripend_date)){
                    $tripend_date = date('Y-m-d');
                }

                $getstart_odometer=$getend_odometer='';
                $startododate = trim(substr($tripstart_date,0,11));
                $customerno = $_SESSION['customerno'];
                $tripdata = $tripdetails;
                $unitno = $tripdata[0]->unitno;
                $strlocation = "../../customer/$customerno/unitno/$unitno/sqlite/$startododate.sqlite";
                $getstart_odometer = $tripobj->getOdometer($strlocation, $tripstart_date);
                $endododate = trim(substr($tripend_date,0,11));
                $customerno = $_SESSION['customerno'];
                $tripdata = $tripdetails;
                $unitno = $tripdata[0]->unitno;
                $endlocation = "../../customer/$customerno/unitno/$unitno/sqlite/$endododate.sqlite";
                $today= date('Y-m-d');
//                if(strtotime($tripend_date)== strtotime($today)){
//                   $getend_odometer = $tripobj->getodometerform_mysql($tripdata[0]->vehicleid);
//                }else{
//                   $getend_odometer = $tripobj->getOdometer($endlocation, $tripend_date);
//                }
                 $getend_odometer = $tripobj->getOdometer($endlocation, $tripend_date);

                if (isset($tripdetails) && !empty($tripdetails)) {
                    $tripdata = $tripdetails;
                    $mintemp = $tripdata[0]->mintemp;
                    $maxtemp = $tripdata[0]->maxtemp;
                }
            }


            if ($deviceid != 0) {
                include 'pages/panels/locationrep.php';
                if ($_POST["frequency"] == '1') {
                    getlocationreport($STdate, $EDdate, $deviceid, $_POST['STime'], $_POST['ETime'], $interval, null);
                } elseif ($_POST["frequency"] == '2') {
                    getlocationreport($STdate, $EDdate, $deviceid, $_POST['STime'], $_POST['ETime'], null, $distance);
                }
            } else {
                echo "<script>jQuery('#error3').show();jQuery('#error3').fadeOut(3000)</script>";
            }
        } else {
            echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
        }
    } else if ($datecheck == 0) {
        echo "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    } else {
        echo "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    }
}
?>

