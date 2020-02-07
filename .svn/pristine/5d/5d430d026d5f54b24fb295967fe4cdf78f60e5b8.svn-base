<?php
if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
    include 'reports_common_functions.php';
    $sdate = $_POST['STdate'];
    $edate = $_POST['EDdate'];
    $stime = $_POST['STime'];
    $etime = $_POST['ETime'];
    $interval_p = $_POST['interval'];

if(isset($_POST['tempsel'])){
    $tempselect = GetSafeValueString($_POST['tempsel'], 'string');
}
else{
    $tempselect =  "0";
}
    $newsdate = date("Y-m-d", strtotime($sdate));
    $newedate = date("Y-m-d", strtotime($edate));
    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
    $datecheck = datediff($sdate, $edate);
    $datediffcheck = date_SDiff($newsdate, $newedate);
    $temp_limit = 15;
    $STdate = GetSafeValueString($sdate, 'string');
    $EDdate = GetSafeValueString($edate, 'string');
    $interval = GetSafeValueString($interval_p, 'long');
    $deviceid = GetSafeValueString($_POST['deviceid'], 'long');
    if (isset($_SESSION['ecodeid'])) {
        $startdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['s_start'], 'string')));
        $enddate = date('Y-m-d', strtotime(GetSafeValueString($_POST['e_end'], 'string')));

        if (strtotime($sdate) < strtotime($startdate) || strtotime($edate) > strtotime($enddate)) {
            echo "<script>jQuery('#error4').show();jQuery('#error4').fadeOut(3000)</script>";
        } else {
            if ($datecheck == 1) {
                if ($datediffcheck <= 30) {
                    $return = gettempExcepreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime, $tempselect);
                    extract(get_min_max_temp($tempselect, $return[1]));
                    include 'pages/panels/tempconflict.php';
                    echo $return[0];
                } else {
                    echo $error2;
                }
            } elseif ($datecheck == 0) {
                echo $error1;
            } else {
                echo $error;
            }
        }
    } else {
        if ($datecheck == 1) {
            if ($datediffcheck <= 30) {
                $return = gettempExcepreport($STdate, $EDdate, $deviceid, $interval, $stime, $etime, $tempselect);
                extract(get_min_max_temp($tempselect, $return[1]));

                include 'pages/panels/tempconflict.php';
                echo $return[0];
            } else {
                echo $error2;
            }
        } elseif ($datecheck == 0) {
            echo $error1;
        } else {
            echo $error;
        }
    }
}
?>
