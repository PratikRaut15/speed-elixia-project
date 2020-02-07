<?php
if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
    include 'reports_common_functions.php';
    $today = date("Y-m-d");
    $sdate = $_POST['STdate'];
    $newsdate = date("Y-m-d", strtotime($sdate));
    $edate = $_POST['EDdate'];
    $newedate = date("Y-m-d", strtotime($edate));
    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
    $datecheck = datediff($_POST['STdate'], $_POST['EDdate']);
    $gensetSensor = $_POST['gensetSensor'];
    $datediffcheck = date_SDiff($newsdate, $newedate);
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
            $STime = GetSafeValueString($_POST['STime'], 'string');
            $EDdate = GetSafeValueString($_POST['EDdate'], 'string');
            $ETime = GetSafeValueString($_POST['ETime'], 'string');
            $deviceid = GetSafeValueString($_POST['deviceid'], 'long');
            include 'pages/panels/acsensorhistrep_details.php';
            getgensetreportdetails($STdate, $EDdate, $deviceid, $STime, $ETime, $gensetSensor);
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
