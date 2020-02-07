<?php
if (!isset($_SESSION)) {
    session_start();
    if (!isset($_SESSION['timezone'])) {
        $_SESSION['timezone'] = 'Asia/Kolkata';
    }
    date_default_timezone_set('' . $_SESSION['timezone'] . '');
}
include 'whrtd_functions.php';
$sel_status = $_GET['sel_status'];
display_filter_vehicledata($sel_status);

?>
