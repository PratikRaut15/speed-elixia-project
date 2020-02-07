<?php
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
//$code_start = microtime(true);
set_time_limit(0);
//date_default_timezone_set("Asia/Calcutta");
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['timezone'])) {
    $_SESSION['timezone'] = 'Asia/Kolkata';
}
date_default_timezone_set('' . $_SESSION['timezone'] . '');
$month_start = strtotime('first day of this month', time());
$month_end = strtotime('last day of this month', time());
$dmonthstart = date("d-m-Y", $month_start);
$dmonthend = date("d-m-Y", $month_end);
$s_date = isset($_REQUEST['SDate']) ? $_REQUEST['SDate'] : $dmonthstart;
$e_date = isset($_REQUEST['EDate']) ? $_REQUEST['EDate'] : $dmonthend;
if (isset($_REQUEST['to_get']) && ($_REQUEST['to_get'] == 'get_informatics_report' || $_REQUEST['to_get'] == 'get_summarized_report')) {
    include_once 'informatics_functions_new.php';
    if (validate_input($s_date, $e_date)) {
        $start_date = date('Y-m-d', strtotime($s_date));
        $end_date = date('Y-m-d', strtotime($e_date));
        $currentMonthName = date('F', strtotime($e_date));
        $currentYear = date('Y', strtotime($e_date));
        ?>
        <!-- Starts, Reports-->
        <div style='width:75%' id='informaticsStart'>
            <style type='text/css'>
                .greentd{
                    background-color:#2cb62c;
                }
                .yellowtd{
                    background-color:#eff048;
                }
                .redtd{
                    background-color:#ee3d1d;
                }
                .txtCenter{
                    text-align: center;
                    font-weight: bold;

                }
            </style>
            <!-- Starts, Installed devices -->
            <?php
            $def_chart_height = 200;
            $total_devices_installed = count($all_vehicles); //$devicemanager->get_all_devices_count($start_date, $end_date);
            $device_installed_display = ($total_devices_installed <= 1) ? $total_devices_installed . " Device" : $total_devices_installed . " Devices";
            ?>
            <!--
            <div style='float:left'>Total Installed Devices: <b><?php echo $device_installed_display; ?></b></div>
            -->

            <!-- Ends, Installed devices -->
            <!-- Daily Reports Data -->
            <?php
            $dailyreportdata = get_daily_report_data($start_date, $end_date);
            $_SESSION["dailyreportdata"] = $dailyreportdata;
            ?>
            <h3 class="txtCenter">Informatics Report for <?php echo $currentMonthName . " " . $currentYear; ?></h3><br/>

            <?php
            include 'pages/overspeed.php';
            include 'pages/harshbreak.php';
            include 'pages/acceleration.php';
            include 'pages/nightdrive.php';
            include 'pages/weekenddrive.php';
            ?>

        </div>
        <?php
    }
}
else {
    echo "Please select Dates";
}
?>
