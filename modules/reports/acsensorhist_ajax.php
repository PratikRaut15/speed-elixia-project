<?php
if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {
    include 'reports_common_functions.php';
    $today = date("Y-m-d");
    $STdate = $_POST['STdate'];
    $EDdate = $_POST['EDdate'];

    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";


    if (strtotime($EDdate) > strtotime($today)) {
        echo "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";exit;
    }

    if (isset($_SESSION['eocdeid'])) {
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
    /*Date And Date Diff Check*/
    $datecheck = datediff($STdate, $EDdate);
    $datediffcheck = date_SDiff($STdate, $EDdate);
    if ($datecheck == 1) {
        if ($datediffcheck <= 30) {

            $deviceid = GetSafeValueString($_POST['deviceid'], 'long');
            include 'pages/panels/acsensorhistrep.php';

            getgensetreport($STdate, $EDdate, $deviceid);
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
