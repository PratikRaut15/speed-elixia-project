<?php
if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {

    include_once 'reports_trip_functions.php';
    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(5000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(5000)</script>";
    $error2 = "<script>jQuery('#error2').show();jQuery('#error2').fadeOut(5000)</script>";
    $STdate = GetSafeValueString($_POST['STdate'], 'string');
    $EDdate = GetSafeValueString($_POST['EDdate'], 'string');
    $title = "Trip Report";

    $columns = array('Start Time', 'End Time', 'Starting location', 'End location', 'Distance', 'Map View', 'Table View');

    if (strtotime($STdate) > strtotime($EDdate)) {
        echo $error;
    } elseif (isset($_POST['vehicleid']) && $_POST['vehicleid'] == -1) {
        echo $error1;
    } elseif (isset($_SESSION['ecodeid'])) {
        /*Client Code Validation */
        $validation = clientCodeValidation($_POST['s_start'], $_POST['e_end'], $_POST['days'], $STdate, $EDdate);
        if (isset($validation) && !empty($validation)) {
            if ($validation['isError'] == 1) {
                echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(5000)</script>";
                die();
            } else {
                $STdate = date('d-m-Y', strtotime($validation['reportStartDate']));
                $EDdate = date('d-m-Y', strtotime($validation['reportEndDate']));
                echo "<script>jQuery('#SDate').val('" . $STdate . "');</script>";
                echo "<script>jQuery('#EDate').val('" . $EDdate . "');</script>";
            }
        }
        $subTitle = array("Vehicle No: {$_POST['vehicleno']}", "Start Date: {$STdate} {$_POST['STime']}", "End Date: {$EDdate} {$_POST['ETime']}");
        $vehicleid = GetSafeValueString($_POST['vehicleid'], 'string');
        $checkpoints = getcheckpoints($vehicleid);
        $rawrep = getchkrep($STdate, $EDdate, $_POST['STime'], $_POST['ETime'], $vehicleid, $checkpoints);

        if (isset($rawrep) && count($rawrep) > 0) {
            echo table_header($title, $subTitle, $columns);
            $chkrep = processchkrep($rawrep);
            displayrep($chkrep);
            echo "</tbody></table>";
        } else {
            echo $error;
        }
    } else {
        $subTitle = array("Vehicle No: {$_POST['vehicleno']}", "Start Date: {$STdate} {$_POST['STime']}", "End Date: {$EDdate} {$_POST['ETime']}");
        $vehicleid = GetSafeValueString($_POST['vehicleid'], 'string');
        $checkpoints = getcheckpoints($vehicleid);
        if (isset($checkpoints) && !empty($checkpoints)) {
            $rawrep = getchkrep($STdate, $EDdate, $_POST['STime'], $_POST['ETime'], $vehicleid, $checkpoints);

            if (isset($rawrep) && count($rawrep) > 0) {
                echo table_header($title, $subTitle, $columns);
                $chkrep = processchkrep($rawrep);
                displayrep($chkrep);
                echo "</tbody></table>";
            } else {
                echo $error;
            }
        } else {
            echo $error2;
        }
    }
}
?>
