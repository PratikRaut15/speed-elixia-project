<?php

set_time_limit(0);
//date_default_timezone_set("Asia/Calcutta");

if ($_REQUEST['report'] == 'travelhist') {

    include 'reports_travel_functions.php';

    ob_start();
    $customerno = $_SESSION['customerno'];
    $cat = get_travel_history_report_pdf($_REQUEST['vehicleid'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['geocode'], $customerno);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output($cat . "_" . date("d-m-Y") . "_travelhistory.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'genset') {

    require_once 'reports_common_functions.php';

    ob_start();
    $cat = getgensetreportpdfMultipleDays($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_GensetReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'gensetdetails') {

    require_once 'reports_common_functions.php';

    ob_start();
    $cat = getgensetreportpdfMultipleDays_details($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_GensetReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'gensetsummary') {

    require_once 'reports_common_functions.php';

    ob_start();
    $cat = getgensetreportpdfMultipleDays_summary($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_GensetReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'extra') {

    require_once 'reports_common_functions.php';

    ob_start();
    $cat = getextrareportpdfMultipleDays($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['extraid'], $_REQUEST['extraval']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_GensetReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'temperature') {
    require_once 'reports_common_functions.php';
    ob_start();
    $cat = gettempreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['interval'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['switchto'], null, 'pdf');
    
    $content = ob_get_clean();
    //echo $content ; die();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($cat);
        ob_end_clean(); //add this line here 
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_TemperatureReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'tempexception') {
    require_once 'reports_common_functions.php';
    ob_start();

    $cat = gettempreportpdf_Excep($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['interval'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['tempselect'], $_REQUEST['switchto']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_TemperatureExceptionReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'humidity') {
    require_once 'reports_common_functions.php';
    ob_start();

    $cat = gethumidityreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['interval'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['switchto'], null, 'pdf');
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_HumidityReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'temphumidity') {
    require_once 'reports_common_functions.php';
    ob_start();

    $cat = gettemphumidityreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['interval'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['switchto']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_HumidityReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'location') {
    require_once 'reports_location_functions.php';
    ob_start();

    if (isset($_REQUEST['interval']) && $_REQUEST['distance'] == 'undefined') {
        $cat = getlocationreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['interval'], null, $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['userid'], $_REQUEST['tripid'], $_REQUEST['triplogno']);
    } elseif (isset($_REQUEST['distance']) && $_REQUEST['interval'] == 'undefined') {
        $cat = getlocationreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], null, $_REQUEST['distance'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['userid'], $_REQUEST['tripid'], $_REQUEST['triplogno']);
    }
    $content = ob_get_clean();
    require_once('html2pdf.php');

    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_LocationReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'stoppage') {
    require_once 'reports_stoppage_functions.php';
    ob_start();

    $cat = getstoppagereportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['interval'], $_REQUEST['stime'], $_REQUEST['etime']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_StoppageReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'overspeed') {
    require_once 'reports_overspeed_functions.php';
    ob_start();

    $cat = getoverspeedreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['stime'], $_REQUEST['etime']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_OverspeedReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}
// alert history pdf
else if ($_REQUEST['report'] == 'alerthist') {
    require_once 'reports_common_functions.php';
    ob_start();

    $cat = get_pdf_alerthist($_REQUEST['sdate'], $_REQUEST['alerttype'], $_REQUEST['vehicleid'], $_REQUEST['chkid'], $_REQUEST['fenceid'], $_REQUEST['vehicleno'], $_REQUEST['customerno'], $_REQUEST['alertmode'], $_REQUEST['switchto']);
    $content = ob_get_clean();
//die();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_AlertHistoryReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'checkpoint') {
    require_once 'reports_chk_functions.php';
    ob_start();

    $cat = getchkreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['temp_sensors']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_CheckpointReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'checkpointAll') {
    require_once 'reports_chk_all_functions.php';
    ob_start();

    $cat = getchkreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['temp_sensors']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output("All_Vehicles_" . date("d-m-Y") . "_CheckpointReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'FuelConsumption') {
    require_once 'reports_fuelefficiency_functions.php';
    $vehicles = GetVehicles_SQLite();
    ob_start();
    $use_hierarchy = isset($_REQUEST['use_hierarchy']) ? $_REQUEST['use_hierarchy'] : null;
    $cat = getfuelcomsumptionpdf($_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($vehicles[$_REQUEST['vehicleid']]['vehicleno'] . "_" . date("d-m-Y") . "_FuelConsumption.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'FuelConsumptionNew') {

    require_once 'reports_fuel_functions.php';

    ob_start();

    $customerno = exit_issetor($_SESSION['customerno']);
    $cat = get_fuelcomsumption_pdf($customerno, $_REQUEST['STdate'], $_REQUEST['STime'], $_REQUEST['EDdate'], $_REQUEST['ETime'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['interval']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_FuelConsumption.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'DistanceReport') {
    require_once 'reports_common_functions.php';
    include_once 'distancereport_functions.php';
    ob_start();

    $use_hierarchy = ret_issetor($_REQUEST['use_hierarchy']);
    $cat = getdistancereportpdf($_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate']);
    $content = ob_get_clean();
    require_once('html2pdf.php');

    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_DistanceReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'IdeltimeReport') {
    require_once 'reports_common_functions.php';

    ob_start();
    $use_hierarchy = retval_issetor($_REQUEST['use_hierarchy']);
    $cat = getideltimereportpdf($_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_IdeltimeReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'GensetReport') {
    require_once 'reports_common_functions.php';

    ob_start();
    $use_hierarchy = retval_issetor($_REQUEST['use_hierarchy']);
    $cat = getgensetreportpdf_All($_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_GensetReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'Overspeed_Report') {
    require_once 'reports_common_functions.php';

    ob_start();

    $use_maintainance = retval_issetor($_REQUEST['use_maintainance']);
    $use_hierarchy = retval_issetor($_REQUEST['use_hierarchy']);
    $cat = getoverspeed_reportpdf($_REQUEST['customerno'], $use_maintainance, $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_Overspeed_Report.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'Fence_Report') {
    require_once 'reports_common_functions.php';

    ob_start();
    $use_hierarchy = retval_issetor($_REQUEST['use_hierarchy']);
    $cat = getfence_reportpdf($_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_FenceConflict_Report.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'Location_Report') {
    require_once 'reports_common_functions.php';

    ob_start();
    $use_hierarchy = isset($_REQUEST['use_hierarchy']) ? $_REQUEST['use_hierarchy'] : null;

    $cat = getlocation_reportpdf($_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_Location_Report.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'Fuel_Report') {
    require_once 'reports_common_functions.php';

    ob_start();
    $use_hierarchy = retval_issetor($_REQUEST['use_hierarchy']);
    $cat = getFuel_reportpdf($_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_Fuel_Report.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'Trip_Report') {
    require_once 'reports_fuelefficiency_functions.php';

    ob_start();
    $cat = print_Trip_Report($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['chk_start'], $_REQUEST['chk_end'], $_REQUEST['chk_via'], $_REQUEST['reporttype']);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_Trip_Report.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'harsh_break') {

    require_once 'reports_advanced_functions.php';
    ob_start();
    list($STdate, $EDdate) = validate_set_vals($_REQUEST['sdate'], $_REQUEST['edate']);
    $totaldays = gendays($STdate, $EDdate);
    $column_name = "harsh_break";
    $cat = generate_pdf_harsh_break($STdate, $EDdate);
    $content = ob_get_clean();

    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_HarshBreak_Report.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
    exit;
} else if ($_REQUEST['report'] == 'sudden_acc') {

    require_once 'reports_advanced_functions.php';
    ob_start();
    list($STdate, $EDdate) = validate_set_vals($_REQUEST['sdate'], $_REQUEST['edate']);
    $totaldays = gendays($STdate, $EDdate);
    $column_name = "sudden_acc";
    $cat = generate_pdf_sudden_acc($STdate, $EDdate);
    $content = ob_get_clean();

    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_Sudden_Acceleration.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
    exit;
} else if ($_REQUEST['report'] == 'sharp_turn') {

    require_once 'reports_advanced_functions.php';
    ob_start();
    list($STdate, $EDdate) = validate_set_vals($_REQUEST['sdate'], $_REQUEST['edate']);
    $totaldays = gendays($STdate, $EDdate);
    $column_name = "sharp_turn";
    $cat = generate_pdf_sharp_turn($STdate, $EDdate);
    $content = ob_get_clean();

    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_Sharp_Turn.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
    exit;
} else if ($_REQUEST['report'] == 'towing') {

    require_once 'reports_advanced_functions.php';
    ob_start();
    list($STdate, $EDdate) = validate_set_vals($_REQUEST['sdate'], $_REQUEST['edate']);
    $totaldays = gendays($STdate, $EDdate);
    $column_name = "towing";
    $cat = generate_pdf_towing($STdate, $EDdate);
    $content = ob_get_clean();

    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_towing.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
    exit;
} else if ($_REQUEST['report'] == 'doorHist') {

    include_once 'reports_door_functions.php';

    $customerno = exit_issetor($_SESSION['customerno']);
    $vehicleno = retval_issetor($_REQUEST['vehicleno']);
    ob_start();
    $cat = getDoorHist_pdf($customerno, $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $vehicleno);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($vehicleno . "_" . date("d-m-Y") . "_DoorHistory.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'routeSmry') {

    require_once 'route_summary_functions.php';

    ob_start();
    $customerno = exit_issetor($_SESSION['customerno']);
    $cat = getRouteSummary_pdf($_REQUEST['sdate'], $_REQUEST['edate'], $customerno);
    $content = ob_get_clean();

    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_RouteSummary.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'FuelHistMaintenancePdf') {

    require_once 'reports_fuelefficiency_functions.php';

    ob_start();
    $customerno = exit_issetor($_SESSION['customerno']);
    $cat = generate_maintenance_fuel_pdf($_REQUEST['vehicleid'], $_REQUEST['vehno'], $_REQUEST['STdate'], $_REQUEST['EDdate'], $_REQUEST['dealer'], $customerno);
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_FuelHistory.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'vehrenewalpdf') {

    require_once 'reports_renewal_functions.php';

    ob_start();
    $customerno = exit_issetor($_SESSION['customerno']);
    $cat = getvehicles_renewal_pdf();
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_RenewalReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'loginhistory') {
    require_once '../user/user_functions.php';
    ob_start();
    if (isset($_REQUEST['sdate']) && isset($_REQUEST['edate'])) {
        $cat = getloginhistorypdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['stime'], $_REQUEST['etime']);
    }
    $content = ob_get_clean();
    require_once('html2pdf.php');

    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_Loginhistorydetails.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} else if ($_REQUEST['report'] == 'transHistMaintenancePdf') {
    include_once 'reports_fuelefficiency_functions.php';
    ob_start();
    $customerno = exit_issetor($_SESSION['customerno']);
    $cat = pdf_maintenance_trans_hist($_REQUEST['vehicleid'], $_REQUEST['vehno'], $_REQUEST['STdate'], $_REQUEST['EDdate'], $_REQUEST['dealer'], $customerno, $_REQUEST['category'], $_REQUEST['status']);
    //die();
    $content = ob_get_clean();
    require_once('html2pdf.php');
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_Transaction_Hist.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}
?>
