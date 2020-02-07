<?php
if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
    include 'reports_door_functions.php';
    $today = date("Y-m-d");
    $sdate = $_POST['STdate'];
    $newsdate = date("Y-m-d", strtotime($sdate));
    $edate = $_POST['EDdate'];
    $newedate = date("Y-m-d", strtotime($edate));
    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
    $datecheck = datediff_cmn($_POST['STdate'], $_POST['EDdate']);
    $datediffcheck = date_SDiff_cmn($newsdate, $newedate);
    if (strtotime($edate) > strtotime($today)) {
        echo "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";exit;
    }
    if (isset($_SESSION['eocdeid'])) {
        $startdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['s_start'], 'string')));
        $enddate = date('Y-m-d', strtotime(GetSafeValueString($_POST['e_end'], 'string')));
        if (strtotime($_POST['STdate']) < strtotime($startdate) || strtotime($_POST['EDdate']) > strtotime($enddate)) {
            echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
        }
    }
    if ($datecheck == 1) {
        if ($datediffcheck <= 30) {
            $STdate = GetSafeValueString($_POST['STdate'], 'string');
            $EDdate = GetSafeValueString($_POST['EDdate'], 'string');
            $deviceid = GetSafeValueString($_POST['deviceid'], 'long');
            $title = 'Door Sensor History';
            $subTitle = array(
                "Vehicle No: {$_POST['vehicleno']}",
                "Start Date: $STdate",
                "End Date: $EDdate",
            );

            if ($_SESSION['customerno'] == speedConstants::CUSTNO_RKFOODLANDS && $_SESSION['switch_to'] == 3) {
                $columns = array(
                'Door1 Start Time',

                'Door1 End Time',
                'Door1 Status',
                'Door1 Duration [HH:MM:SS]',
                'Door2 Start Time',

                'Door2 End Time',
                'Door2 Status',
                'Door2 Duration [HH:MM:SS]',
            );
            } else {
                $columns = array(
                'Start Time',
                'Start Location',
                'End Time',
                'End Location',
                'Door Status',
                'Duration [HH:MM:SS]',
            );
            }


            echo table_header($title, $subTitle, $columns);
            if($_SESSION['customerno'] == speedConstants::CUSTNO_RKFOODLANDS && $_SESSION['switch_to'] == 3){
                getDoubleDoorHist($STdate, $EDdate, $deviceid);
            }else{
                getDoorHist($STdate, $EDdate, $deviceid);
            }

        } else {
            echo $error2;
        }
    } elseif ($datecheck == 0) {
        echo $error1;
    } else {
        echo $error;
    }
}
?>
