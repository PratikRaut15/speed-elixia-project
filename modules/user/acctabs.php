<br/>
<?php
    include 'user_functions.php';
    if (!isset($_GET['id']) || $_GET['id'] == 1) {
        include 'pages/myaccount.php';
    } elseif ($_GET['id'] == 2) {
        include 'pages/contractinfo.php';
    } elseif ($_GET['id'] == 3) {
        include_once 'exception_functions.php';
        $exceptions = getCheckpointExceptions();
        include 'pages/alerts.php';
    } elseif ($_GET['id'] == 4) {
        include 'pages/customize.php';
    } elseif ($_GET['id'] == 5) {
        include 'pages/servicehistory.php';
    } elseif ($_GET['id'] == 6) {
        include 'pages/acc_summary.php';
    } elseif ($_GET['id'] == 7) {
        include 'pages/installDetails.php';
    }
?>
