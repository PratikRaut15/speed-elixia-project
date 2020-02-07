<?php 
if (isset($_POST['SDate']) && isset($_POST['EDate']) && isset($_POST['vehicleid']) && $_POST['vehicleid']!='') { 
    include 'reports_door_functions.php';
    $today = date("Y-m-d");
    $sdate = $_POST['SDate'];
    $newsdate = date("Y-m-d", strtotime($sdate));
    $edate = $_POST['EDate'];
    $newedate = date("Y-m-d", strtotime($edate));
    $doorsensor = $_POST['doorsensor'];

    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
    $datecheck = datediff_cmn($_POST['SDate'], $_POST['EDate']);
    $datediffcheck = date_SDiff_cmn($newsdate, $newedate);
    if (strtotime($edate) > strtotime($today)) {
        echo "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";exit;
    }
    if (isset($_SESSION['eocdeid'])) {
        $startdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['s_start'], 'string')));
        $enddate = date('Y-m-d', strtotime(GetSafeValueString($_POST['e_end'], 'string')));
        if (strtotime($_POST['SDate']) < strtotime($startdate) || strtotime($_POST['EDate']) > strtotime($enddate)) {
            echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
        }
    }
    if ($datecheck == 1) {
        if ($datediffcheck <= 30) {
            $STdate = GetSafeValueString($_POST['SDate'], 'string');
            $EDdate = GetSafeValueString($_POST['EDate'], 'string');
            $deviceid = GetSafeValueString($_POST['vehicleid'], 'long');
            
            $title = 'Door Sensor Report';
            $subTitle = array(
                "Vehicle No: {$_POST['vehicleno']}",
                "Start Date: $STdate",
                "End Date: $EDdate",
            );
     
            $columns = array(
            	'Door',
                'Start Time',
                'End Time',
                'Door Status',
                'Duration [HH:MM:SS]',
            );
            echo table_header($title, $subTitle, $columns);
            getDoorSensorData($STdate, $EDdate, $deviceid,$doorsensor);
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