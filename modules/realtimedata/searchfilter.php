<?php

if (!isset($_SESSION)) {
    session_start();
}
include 'rtd_functions.php';
$sel_stoppage = '';
$sel_status = '';

if ($_GET['sel_status'] == '') {
    $sel_status;
} else {
    $sel_status = $_GET['sel_status'];
}



if ($_GET['ecodeid'] != '') {
    $_SESSION['ecodeid'] = $_GET['ecodeid'];
}

if ($_GET['eid'] != '') {
    $_SESSION['e_id'] = $_GET['eid'];
}

$_SESSION['customerno'] = $_GET['customer_no'];
$_SESSION['Session_UserRole'] = $_GET['userrole'];
$_SESSION['groupid'] = $_GET['grp'];
$_SESSION['buzzer'] = $_GET['buzzer'];
//print_r($_SESSION);die();
display_filter_vehicledata($sel_status, $sel_stoppage);
?>