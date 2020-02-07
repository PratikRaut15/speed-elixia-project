<?php

if (isset($_POST['STdate']) && isset($_POST['EDdate'])) {

    include_once 'reports_toggleswitch_functions.php';
    $error = "<script>jQuery('#error').show();jQuery('#error').fadeOut(3000)</script>";
    $error1 = "<script>jQuery('#error1').show();jQuery('#error1').fadeOut(3000)</script>";
    $STdate = GetSafeValueString($_POST['STdate'], 'string');
    $EDdate = GetSafeValueString($_POST['EDdate'], 'string');
    $title = "Trip Report";
    $subTitle = array("Vehicle No: {$_POST['vehicleno']}", "Start Date: {$_POST['STdate']} {$_POST['STime']}", "End Date: {$_POST['EDdate']} {$_POST['ETime']}");
    $columns = array('Trip No', 'Vehicle No', 'Start Date', 'Start Time', 'Start location','Start(lat,long)', 'End Date', 'End Time', 'End location','End(lat,long)','Distance<br>[KM]', 'Trip Status', 'Route History');

    if (strtotime($STdate) > strtotime($EDdate)) {
        echo $error;
    } else if (isset($_POST['vehicleid']) && $_POST['vehicleid'] == -1) {
        echo $error1;
    } else if (isset($_SESSION['ecodeid'])) {
        $startdate = date('Y-m-d', strtotime(GetSafeValueString($_POST['s_start'], 'string')));
        $enddate = date('Y-m-d', strtotime(GetSafeValueString($_POST['e_end'], 'string')));
        if (strtotime($STdate) < strtotime($startdate) || strtotime($EDdate) > strtotime($enddate)) {
            echo "<script>jQuery('#error6').show();jQuery('#error6').fadeOut(3000)</script>";
        }
        $vehicleid = GetSafeValueString($_POST['vehicleid'], 'string');
        $groupid = GetSafeValueString($_POST['groupid'], 'string');
        $rawrep = getToggleSwitchReport($STdate, $EDdate, $_POST['STime'], $_POST['ETime'], $vehicleid, $groupid,$_SESSION['customerno']);
    } else {
        $vehicleid = GetSafeValueString($_POST['vehicleid'], 'string');
        $groupid = GetSafeValueString($_POST['groupid'], 'string');
        $arrToggleSwitchDetails = getToggleSwitchReport($STdate, $EDdate, $_POST['STime'], $_POST['ETime'], $vehicleid, $groupid, $_SESSION['customerno']);
        $_SESSION['arrToggleSwitchDetails'] = isset($arrToggleSwitchDetails)?$arrToggleSwitchDetails:'';
        if (isset($arrToggleSwitchDetails) && count($arrToggleSwitchDetails) > 0) {            
            //echo table_header($title, $subTitle, $columns);
            //displayrep($arrToggleSwitchDetails);
            //echo "</tbody></table>";
            $cm = new CustomerManager($_SESSION['customerno']);
            $customer_details = $cm->getcustomerdetail_byid($_SESSION['customerno']);
            toggleswitchhistory_html($title,$subTitle,$columns,$customer_details,$arrToggleSwitchDetails,'HTML');
        } else {
            echo $error;
        }
    }
}
?>
