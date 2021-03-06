<script src="<?php echo $_SESSION['subdir']; ?>/scripts/jquery.battatech.excelexport.js"></script>
<br/>
<?php
if (!isset($_GET['id']) || $_GET['id'] == 1) {
    include 'reports_route_functions.php';
    include 'pages/routehist.php';
} elseif ($_GET['id'] == 2) {
    include 'reports_route_functions.php';
    include 'pages/travelhist.php';
} elseif ($_GET['id'] == 3) {
    include 'reports_common_functions.php';
    include 'pages/variablehist.php';
} elseif ($_GET['id'] == 4) {
    //    include 'pages/driverhist.php';
} elseif ($_GET['id'] == 5) {
    include 'reports_common_functions.php';
    include 'pages/vehiclehist.php';
} elseif ($_GET['id'] == 6) {
    include 'pages/chkhist.php';
} elseif ($_GET['id'] == 7) {
    include 'pages/acsensorhist.php';
} elseif ($_GET['id'] == 8) {
    include 'pages/triphist.php';
} elseif ($_GET['id'] == 9) {
    include 'route_functions.php';
    include 'pages/routereport.php';
} elseif ($_GET['id'] == 10) {
    include 'route_functions.php';
    include 'pages/triproutereport.php';
} elseif ($_GET['id'] == 11) {
    include 'reports_common_functions.php';
    include 'pages/temprep.php';
} elseif ($_GET['id'] == 12) {
    include 'reports_common_functions.php';
    include 'pages/alerthist.php';
} elseif ($_GET['id'] == 13) {
    include 'reports_route_functions.php';
    include 'pages/tempreptabular.php';
} elseif ($_GET['id'] == 14) {
    include 'pages/overspeedreport.php';
} elseif ($_GET['id'] == 15) {
    include 'reports_route_functions.php';
    include 'pages/stoppagereport.php';
} elseif ($_GET['id'] == 16) {
    include 'reports_route_functions.php';
    include 'pages/locationreport.php';
} elseif ($_GET['id'] == 18) {
    include 'reports_tripchkpt_functions.php';
    include 'pages/checktriphist.php';
} elseif ($_GET['id'] == 19) {
    include 'reports_common_functions.php';
    include 'pages/fuelconsumptionreport.php';
} elseif ($_GET['id'] == 20) {
    include 'reports_common_functions.php';
    include 'pages/fuelreport.php';
} elseif ($_GET['id'] == 22) {
    include 'route_functions.php';
    include 'pages/triproutereport.php';
} elseif ($_GET['id'] == 23) {
    include 'pages/distancereport.php';
} elseif ($_GET['id'] == 24) {
    include 'pages/ideltimereport.php';
} elseif ($_GET['id'] == 25) {
    include 'pages/gensetreport.php';
} elseif ($_GET['id'] == 26) {
    include 'pages/overspeed_report.php';
} elseif ($_GET['id'] == 27) {
    include 'pages/fenceconflictreport.php';
} elseif ($_GET['id'] == 28) {
    include 'pages/location_report.php';
} elseif ($_GET['id'] == 29) {
    include 'pages/fuelconsumption_report.php';
} elseif ($_GET['id'] == 30) {
    include 'reports_fuelefficiency_functions.php';
    include 'pages/tripreport.php';
} elseif ($_GET['id'] == 31) {
    include 'route_functions.php';
    include 'pages/routetripreport.php';
} elseif ($_GET['id'] == 32) {
    include 'pages/chkhist_all.php';
} elseif ($_GET['id'] == 33) {
    include 'pages/harsh_break_report.php';
} elseif ($_GET['id'] == 34) {
    include 'pages/sudden_acc_report.php';
} elseif ($_GET['id'] == 35) {
    include 'pages/sharp_turn_report.php';
} elseif ($_GET['id'] == 36) {
    include 'pages/towing_report.php';
} elseif ($_GET['id'] == 37) {
    include 'reports_fuelhistory_ajax.php';
    include 'pages/fuelhistory.php';
} elseif ($_GET['id'] == 39) {
    include 'pages/doorhist.php';
} elseif ($_GET['id'] == 40) {
    include 'pages/routeSummary.php';
} elseif ($_GET['id'] == 41) {
    include 'pages/extrasensorhist.php';
} elseif ($_GET['id'] == 42) {
    include 'pages/fuel_report.php';
} elseif ($_GET['id'] == 43) {
    include 'reports_renewal_functions.php';
    include 'pages/renewal_report.php';
} elseif ($_GET['id'] == 44) {
    include 'pages/tempconflict.php';
} elseif ($_GET['id'] == 45) {
    include 'pages/acsensorhistdetail.php'; // id =7 split report in details
} elseif ($_GET['id'] == 46) {
    // id =7 split report in Summary
    include 'pages/acsensorhistsummary.php';
} elseif ($_GET['id'] == 47) {
    // Humidity Report
    include 'pages/humidity_report.php';
} elseif ($_GET['id'] == 48) {
    // Humidity And Temprature Report
    include 'reports_route_functions.php';
    include 'pages/humiditytemp_report.php';
} elseif ($_GET['id'] == 49) {
    include 'reports_fuelefficiency_functions.php';
    include 'pages/transactiondetails.php';
} elseif ($_GET['id'] == 50) {
    include 'pages/toggleswitchrep.php';
} elseif ($_GET['id'] == 51) {
    include '../expense/expense_function.php';
    include 'pages/expenanalysis.php';
} elseif ($_GET['id'] == 52) {
    include 'pages/stoppageanalysis.php';
} elseif ($_GET['id'] == 53) {
    include 'pages/vehicleSummaryReport.php';
} elseif ($_GET['id'] == 54) {
    include 'reports_common_functions.php';
    include 'pages/tatSummary.php';
} elseif ($_GET['id'] == 55) {
    // First Call Last Call Report
    include '../sales/sales_function.php';
    include 'pages/call_report.php';
} elseif ($_GET['id'] == 56) {
    // SKU Wise Report
    include '../sales/sales_function.php';
    include 'pages/skuwise_report.php';
} elseif ($_GET['id'] == 57) {
    // Secondary sales summary report
    include '../sales/sales_function.php';
    include 'pages/salessummary_report.php';
} elseif ($_GET['id'] == 58) {
    // Secondary sales summary report
    include 'pages/inactive_device.php';
} elseif ($_GET['id'] == 59) {
    // Vehicle History / Distance Report
    include 'pages/vehicleDistanceReport.php';
} elseif ($_GET['id'] == 'changeSqlite') {
    // Vehicle History / Distance Report
    include 'pages/changeSqlite.php';
} elseif ($_GET['id'] == 'updateSqlite') {
    // Vehicle History / Distance Report
    include 'pages/updateSqlite.php';
} elseif ($_GET['id'] == 60) {
    // Secondary sales call summary report
    include '../sales/sales_function.php';
    include 'pages/callsummary_report.php';
} elseif ($_GET['id'] == 61) {
    include 'reports_route_functions.php';
    include 'pages/temperatureForm.php'; // for Nestle 473
} elseif ($_GET['id'] == 62) {
    include 'reports_chk_functions.php';
    include 'pages/vehicleInOutRepot.php';
} elseif ($_GET['id'] == 63) {
    include 'reports_chk_functions.php';
    include 'pages/freezeIgnOnTimeReport.php';
} elseif ($_GET['id'] == 64) {
    include 'pages/smsStoreReport.php';
} elseif ($_GET['id'] == 65) {
    include 'pages/inactiveVehicle.php';
} elseif ($_GET['id'] == 66) {
    include 'power_report_function.php';
    include 'pages/powerstatus.php';
} elseif ($_GET['id'] == 67) {
    include 'door_report_function.php';
    include 'pages/doorsensorreport.php';
} elseif ($_GET['id'] == 68) {
    include 'route_functions.php';
    include 'pages/annexureReport.php';
} elseif ($_GET['id'] == 69) {
    include 'route_functions.php';
    include 'pages/driverPerformanceReport.php';
} elseif ($_GET['id'] == 70) {
    include 'reports_common_functions.php';
    //include 'pages/tatSummary.php';
    include 'pages/tatSummaryLive.php';
} elseif ($_GET['id'] == 71) {
    include 'reports_common_functions.php';
    include 'pages/prevalerthist.php';
} elseif ($_GET['id'] == 72) {
    include 'reports_route_functions.php';
    include 'pages/groupWiseTempRepTabular.php';
}
?>
