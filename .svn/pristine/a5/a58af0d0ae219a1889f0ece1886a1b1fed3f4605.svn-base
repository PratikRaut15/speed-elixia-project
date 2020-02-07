<?php

    if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {

        include_once 'reports_common_functions.php';
        $STdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['STdate'], 'string')));
        $EDdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['EDdate'], 'string')));
        $vehicleid = GetSafeValueString($_POST['deviceid'], 'string');
        $datediffcheck = date_SDiff($STdate, $EDdate);
        $vehno = retval_issetor($_POST['vehno']);

        if (strtotime($STdate) > strtotime($EDdate)) {
            echo "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(3000)</script>";
        } elseif ($_POST['deviceid'] == 'Select Vehicle') {
            echo "<script>jQuery('#error3').show();jQuery('#error3').fadeOut(3000)</script>";
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

            if ($vehicleid == 'All') {
                $reports = getFuel_ReportAll($STdate, $EDdate);
            } else {
                $reports = getFuel_Report($STdate, $EDdate, $vehicleid);
            }
            if (isset($reports)) {
                include 'pages/panels/fuelrep.php';
                include 'displayfueldata.php';
            }

        } else {

            if ($vehicleid == 'All') {
                $reports = getFuel_ReportAll($STdate, $EDdate);
            } else {
                $reports = getFuel_Report($STdate, $EDdate, $vehicleid);
            }

            if (isset($reports)) {
                include 'pages/panels/fuelrep.php';
                include 'displayfueldata.php';
            }
        }
    }
?>

