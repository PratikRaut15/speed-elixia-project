<?php

if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
    include_once 'user_functions.php';

    $sdate = $_POST['STdate'];
    $newsdate = date("Y-m-d", strtotime($sdate));
    $edate = $_POST['EDdate'];
    $newedate = date("Y-m-d", strtotime($edate));
    $stime = $_POST['STime'];
    $etime = $_POST['ETime'];

    $datecheck = datediff($_POST['STdate'], $_POST['EDdate']);
    $datediffcheck = date_SDiff($newsdate, $newedate);

    if ($datecheck == 1) {
        if ($datediffcheck <= 30) {
            $STdate = GetSafeValueString($_POST['STdate'], 'string');
            $EDdate = GetSafeValueString($_POST['EDdate'], 'string');
            
                include 'pages/loginhistoryheader.php';
                getloginhistoryreport($STdate, $EDdate,$stime,$etime);
            
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

