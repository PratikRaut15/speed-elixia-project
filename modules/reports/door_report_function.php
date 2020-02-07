<?php 
 if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
        $RELATIVE_PATH_DOTS = "../../";
    }
    include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
    include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
    include_once $RELATIVE_PATH_DOTS . 'lib/comman_function/reports_func.php';

    if (!isset($_SESSION)) {
        session_start();
        if (!isset($_SESSION['timezone'])) {
            $_SESSION['timezone'] = 'Asia/Kolkata';
        }
        date_default_timezone_set('' . $_SESSION['timezone'] . '');
    }
    function getDoorSensorReportData(){
    	
    }
?>