<?php
if (isset($_POST['vehicleid']) && isset($_POST['SDate']) && isset($_POST['EDate'])) {
  
    //include 'reports_common_functions.php';
    include 'power_report_function.php';
    $SDate = $_POST['SDate'];
    $EDate = $_POST['EDate'];
    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";

    $geocode = isset($_POST['geocode']) ? $_POST['geocode'] : null;
    if (isset($_SESSION['ecodeid'])) {
        /*Client Code Validation */
        $validation = clientCodeValidation($_POST['s_start'], $_POST['e_end'], $_POST['days'], $SDate, $EDate);
        if (isset($validation) && !empty($validation)) {
            if ($validation['isError'] == 1) {
                echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
                die();
            } else {
                $SDate = date('d-m-Y', strtotime($validation['reportStartDate']));
                $EDate = date('d-m-Y', strtotime($validation['reportEndDate']));
                echo "<script>jQuery('#SDate').val('" . $SDate . "');</script>";
                echo "<script>jQuery('#EDate').val('" . $EDate . "');</script>";
            }
        }
    }
    /*Date And Date Diff Check*/
    $datecheck = datediff($SDate, $EDate);
    $datediffcheck = date_SDiff($SDate, $EDate);
    if ($_POST['vehicleno'] == 'Enter Vehicle No') {
        echo "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000);</script>";
    } elseif ($datecheck == 1) {
        if ($datediffcheck <= 30) {
            getPowerStatusData($_POST['vehicleid'], $SDate, $EDate,$_POST['STime'], $_POST['ETime']);
            echo '<script type="text/javascript" src="createcheck.js"></script>';
            include "pages/location_pop_html.php";
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
