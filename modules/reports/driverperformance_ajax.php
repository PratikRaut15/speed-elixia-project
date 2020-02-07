<?php
if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
    include 'reports_common_functions.php';
    include_once 'distancereport_functions.php';
    $STdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['STdate'], 'string')));
    $EDdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['EDdate'], 'string')));
    $datediffcheck = date_SDiff($STdate, $EDdate);
    $drivername = GetSafeValueString($_POST['drivername'], 'string');
    $driverid = GetSafeValueString($_POST['driverid'], 'string');
    if (strtotime($STdate) > strtotime($EDdate)) {
        echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
    } elseif (isset($_SESSION['ecodeid'])) {
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
        if ($datediffcheck <= 30) {
            $reports = getdailyreport_All($STdate, $EDdate);
            if (isset($reports)) {
                include 'pages/panels/distancerep.php';
                include 'displaydistancedata_new.php';
            } else {
                echo "<script type='text/javascript'>jQuery('#error').show();jQuery('#error').fadeOut(3000);</script>";
            }
        } else {
            echo "<script type='text/javascript'>jQuery('#error4').show();jQuery('#error4').fadeOut(3000);</script>";
        }
    } else {
        if ($datediffcheck <= 30) {
            $vehicleid = 0;
            $dm = new DriverManager($_SESSION['customerno']);
            $driverdetails = $dm->get_driver_with_vehicle($driverid);
            $vehicleid = $driverdetails->vehicleid;
            $reports = getdailyreport_All($STdate, $EDdate, $vehicleid); // passed vehicle id for single record.//
            //rettyPrint($reports);die();
            if (isset($reports) && isset($vehicleid) && $vehicleid!=0) {
                include 'pages/panels/driverperformancerep.php';
                include 'driverperformancedata.php';
            } else {
                echo "<script type='text/javascript'>jQuery('#error').show();jQuery('#error').fadeOut(3000);</script>";
            }
        } else {
            echo "<script type='text/javascript'>jQuery('#error4').show();jQuery('#error4').fadeOut(3000);</script>";
        }
    }
}
?>
