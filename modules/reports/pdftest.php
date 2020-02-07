<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1); */
set_time_limit(0);
session_start();
ini_set('memory_limit', '1024M');
ob_start(); 
//date_default_timezone_set("Asia/Calcutta");

if ($_REQUEST['report'] == 'travelhist') { 
    include 'reports_travel_functions.php';

    ob_start();
    $customerno = $_SESSION['customerno'];
    $groupname = $_SESSION['groupname'];
    $cat = get_travel_history_report_pdf($_REQUEST['vehicleid'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['geocode'], $customerno, $groupname);
    $content = ob_get_clean();
    //echo $content; die();
    //ini_set('max_execution_time', 123456);
    require_once "../../vendor/autoload.php";
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        $html2pdf->Output($cat . "_" . date("d-m-Y") . "_travelhistory.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'genset') {

    require_once 'reports_common_functions.php';

    ob_start();
    $groupname = $_SESSION['groupname'];
    $cat = getgensetreportpdfMultipleDays($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno']);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_GensetReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'gensetdetails') {

    require_once 'reports_common_functions.php';

    ob_start();
    $groupname = isset($_SESSION['groupname']) ? $_SESSION['groupname'] : null;
    $cat = getgensetreportpdfMultipleDays_details($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['stime'], $_REQUEST['etime'], $groupname, $_REQUEST['gensetSensor']);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_GensetReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'gensetsummary') {

    require_once 'reports_common_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    $cat = getgensetreportpdfMultipleDays_summary($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_GensetReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'extra') {

    require_once 'reports_common_functions.php';

    ob_start();
    $groupname = $_SESSION['groupname'];
    $cat = getextrareportpdfMultipleDays($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['extraid'], $_REQUEST['extraval'], $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_GensetReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'temperature') {
    require_once 'reports_common_functions.php';
    if(isset($_REQUEST['tempselect']) && $_REQUEST['tempselect'] != null){
        $_REQUEST['tempselect'] = $_REQUEST['tempselect'];
    }else{
        $_REQUEST['tempselect'] = null;
    }
    ob_start();
    $groupname = $_SESSION['groupname'];
    $fileName = '';
    if (!isset($_REQUEST['vehicleid']) && isset($_REQUEST['groupid'])) {
        $vehiclemanager = new VehicleManager($_REQUEST['customerno']);
        $devices = $vehiclemanager->get_groups_vehicles_withlatlong($_REQUEST['groupid']);
        if (!empty($devices)) {
            $cat = '';
            foreach ($devices AS $device) {
                $cat .= gettempreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $device->deviceid, $device->vehicleno, $_REQUEST['interval'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['switchto'], $groupname, 'pdf' , $_REQUEST['tempselect'],$_REQUEST);
            }
            if ($cat != '') {
                echo $cat;
            } else {
                echo 'Data Not Available';
            }
        }
        $fileName = $_REQUEST['group_name'];
    } else {
        $fileName = $_REQUEST['vehicleno'];
        echo $cat = gettempreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['interval'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['switchto'], $groupname, 'pdf' , $_REQUEST['tempselect'],$_REQUEST);
    }
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try{
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($cat);
        ob_end_clean(); //add this line here
        $html2pdf->Output($fileName . "_" . date("d-m-Y") . "_TemperatureReport.pdf");
    }catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'tempexception') {
    require_once 'reports_common_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    $cat = gettempreportpdf_Excep($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['interval'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['tempselect'], $_REQUEST['switchto'], $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_TemperatureExceptionReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'humidity') {
    require_once 'reports_common_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    $cat = gethumidityreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['interval'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['switchto'], $groupname, 'pdf');
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_HumidityReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'temphumidity') {
    require_once 'reports_common_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    $cat = gettemphumidityreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['interval'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['switchto'], $groupname);

    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_HumidityReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'location') {
    require_once 'reports_location_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    if (isset($_REQUEST['interval']) && $_REQUEST['distance'] == 'undefined') {
        $cat = getlocationreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['interval'], null, $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['userid'], $_REQUEST['tripid'], $_REQUEST['triplogno'], $groupname);
    } elseif (isset($_REQUEST['distance']) && $_REQUEST['interval'] == 'undefined') {
        $cat = getlocationreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], null, $_REQUEST['distance'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['userid'], $_REQUEST['tripid'], $_REQUEST['triplogno'], $groupname);
    }
    $content = ob_get_clean();
    require_once 'html2pdf.php';

    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_LocationReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'stoppage') {
    require_once 'reports_stoppage_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    $cat = getstoppagereportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['interval'], $_REQUEST['stime'], $_REQUEST['etime'], NULL, $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_StoppageReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'overspeed') {
    require_once 'reports_overspeed_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    $cat = getoverspeedreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['stime'], $_REQUEST['etime'], $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
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
elseif ($_REQUEST['report'] == 'alerthist') {
    require_once 'reports_common_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    $cat = get_pdf_alerthist($_REQUEST['sdate'], $_REQUEST['alerttype'], $_REQUEST['vehicleid'], $_REQUEST['chkid'], $_REQUEST['fenceid'], $_REQUEST['vehicleno'], $_REQUEST['customerno'], $_REQUEST['alertmode'], $_REQUEST['switchto'], $groupname);
    $content = ob_get_clean();
//die();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_AlertHistoryReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'checkpointAll') {
    require_once 'reports_chk_all_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    $cat = getchkreportpdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['temp_sensors'], $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output("All_Vehicles_" . date("d-m-Y") . "_CheckpointReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'FuelConsumption') {
    require_once 'reports_fuelefficiency_functions.php';
    $vehicles = GetVehicles_SQLite();
    ob_start();
    $use_hierarchy = isset($_REQUEST['use_hierarchy']) ? $_REQUEST['use_hierarchy'] : null;
    $groupname = $_SESSION['groupname'];
    $cat = getfuelcomsumptionpdf($_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output($vehicles[$_REQUEST['vehicleid']]['vehicleno'] . "_" . date("d-m-Y") . "_FuelConsumption.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'FuelConsumptionNew') {

    require_once 'reports_fuel_functions.php';

    ob_start();
    $groupname = $_SESSION['groupname'];
    $customerno = exit_issetor($_SESSION['customerno']);
    $cat = get_fuelcomsumption_pdf($customerno, $_REQUEST['STdate'], $_REQUEST['STime'], $_REQUEST['EDdate'], $_REQUEST['ETime'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['interval'], $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_FuelConsumption.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'DistanceReport') {
    require_once 'reports_common_functions.php';
    include_once 'distancereport_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    $use_hierarchy = ret_issetor($_REQUEST['use_hierarchy']);

    $cat = getdistancereportpdf($_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate'], $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';

    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_DistanceReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'VehicleDistanceReport') {
    require_once 'reports_common_functions.php';
    include_once 'distancereport_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    $use_hierarchy = ret_issetor($_REQUEST['use_hierarchy']);

    $cat = getvehicledistancereportpdf($_REQUEST['vehicleno'], $_REQUEST['vehicleid'], $_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate'], $groupname);
    $content = ob_get_clean();

    //die;

    require_once 'html2pdf.php';

    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_DistanceReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}

elseif ($_REQUEST['report'] == 'IdeltimeReport') {
    require_once 'reports_common_functions.php';
    $groupname = $_SESSION['groupname'];
    ob_start();
    $use_hierarchy = retval_issetor($_REQUEST['use_hierarchy']);
    $cat = getideltimereportpdf($_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate'], $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_IdeltimeReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'GensetReport') {
    require_once 'reports_common_functions.php';
    $groupname = $_SESSION['groupname'];
    ob_start();
    $use_hierarchy = retval_issetor($_REQUEST['use_hierarchy']);
    $cat = getgensetreportpdf_All($_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate'], $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_GensetReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'Overspeed_Report') {
    require_once 'reports_common_functions.php';

    ob_start();
    $groupname = $_SESSION['groupname'];
    $use_maintainance = retval_issetor($_REQUEST['use_maintainance']);
    $use_hierarchy = retval_issetor($_REQUEST['use_hierarchy']);

    $cat = getoverspeed_reportpdf($_REQUEST['customerno'], $use_maintainance, $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate'], $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_Overspeed_Report.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'Fence_Report') {
    require_once 'reports_common_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    $use_hierarchy = retval_issetor($_REQUEST['use_hierarchy']);
    $cat = getfence_reportpdf($_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate'], $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_FenceConflict_Report.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'Location_Report') {
    require_once 'reports_common_functions.php';
    $groupname = $_SESSION['groupname'];
    ob_start();
    $use_hierarchy = isset($_REQUEST['use_hierarchy']) ? $_REQUEST['use_hierarchy'] : null;

    $cat = getlocation_reportpdf($_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate'], $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_Location_Report.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'Fuel_Report') {
    require_once 'reports_common_functions.php';
    $groupname = $_SESSION['groupname'];
    ob_start();
    $use_hierarchy = retval_issetor($_REQUEST['use_hierarchy']);
    $cat = getFuel_reportpdf($_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate'], $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_Fuel_Report.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'Trip_Report') {
    require_once 'reports_fuelefficiency_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    $cat = print_Trip_Report($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['chk_start'], $_REQUEST['chk_end'], $_REQUEST['chk_via'], $_REQUEST['reporttype'], $groupname,$_REQUEST['chktype'],$_REQUEST['vehicleid']);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_Trip_Report.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'harsh_break') {
    $groupname = $_SESSION['groupname'];
    require_once 'reports_advanced_functions.php';
    ob_start();
    list($STdate, $EDdate) = validate_set_vals($_REQUEST['sdate'], $_REQUEST['edate']);
    $totaldays = gendays($STdate, $EDdate);
    $column_name = "harsh_break";
    $cat = generate_pdf_harsh_break($STdate, $EDdate, $groupname);
    $content = ob_get_clean();

    require_once 'html2pdf.php';
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
} elseif ($_REQUEST['report'] == 'sudden_acc') {

    require_once 'reports_advanced_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    list($STdate, $EDdate) = validate_set_vals($_REQUEST['sdate'], $_REQUEST['edate']);
    $totaldays = gendays($STdate, $EDdate);
    $column_name = "sudden_acc";
    $cat = generate_pdf_sudden_acc($STdate, $EDdate, $groupname);
    $content = ob_get_clean();

    require_once 'html2pdf.php';
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
} elseif ($_REQUEST['report'] == 'sharp_turn') {

    require_once 'reports_advanced_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    list($STdate, $EDdate) = validate_set_vals($_REQUEST['sdate'], $_REQUEST['edate']);
    $totaldays = gendays($STdate, $EDdate);
    $column_name = "sharp_turn";
    $cat = generate_pdf_sharp_turn($STdate, $EDdate, $groupname);
    $content = ob_get_clean();

    require_once 'html2pdf.php';
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
} elseif ($_REQUEST['report'] == 'towing') {

    require_once 'reports_advanced_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    list($STdate, $EDdate) = validate_set_vals($_REQUEST['sdate'], $_REQUEST['edate']);
    $totaldays = gendays($STdate, $EDdate);
    $column_name = "towing";
    $cat = generate_pdf_towing($STdate, $EDdate, $groupname);
    $content = ob_get_clean();

    require_once 'html2pdf.php';
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
} elseif ($_REQUEST['report'] == 'doorHist') {

    include_once 'reports_door_functions.php';
    $groupname = $_SESSION['groupname'];
    $customerno = exit_issetor($_SESSION['customerno']);
    $vehicleno = retval_issetor($_REQUEST['vehicleno']);
    ob_start();

    if ($_SESSION['customerno'] == speedConstants::CUSTNO_RKFOODLANDS && $_SESSION['switch_to'] == 3) {
        $cat = getDoubleDoorHist_pdf($customerno, $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $vehicleno, $groupname);
    }else{
        $cat = getDoorHist_pdf($customerno, $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $vehicleno, $groupname);
    }


    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($vehicleno . "_" . date("d-m-Y") . "_DoorHistory.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'routeSmry') {
    require_once 'route_summary_functions.php';
    $groupname = $_SESSION['groupname'];
    ob_start();
    $customerno = exit_issetor($_SESSION['customerno']);
    $cat = getRouteSummary_pdf($_REQUEST['sdate'], $_REQUEST['edate'], $customerno, $groupname);
    $content = ob_get_clean();

    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_RouteSummary.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'routeTatSmry') {

    require_once 'route_summary_functions.php';
    $groupname = $_SESSION['groupname'];
    ob_start();
    $customerno = exit_issetor($_SESSION['customerno']);
    $cat = getRouteTatSummary_pdf($_REQUEST['sdate'], $_REQUEST['edate'], $customerno, 'pdf', $_REQUEST['grpid'], $groupname);
    $content = ob_get_clean();

    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_RoutewiseOnTimeDeparture.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'FuelHistMaintenancePdf') {
    require_once 'reports_fuelefficiency_functions.php';
    ob_start();

    $groupname = $_SESSION['groupname'];
    $sortdata_arr = array(
        'sortorder' => $_REQUEST['sortorder'],
        'sortcol' => $_REQUEST['sortcol'],
        'vehiclenof' => $_REQUEST['vehiclenof'],
        'transactionidf' => $_REQUEST['transactionidf'],
        'seatcapacityf' => $_REQUEST['seatcapacityf'],
        'fuelf' => $_REQUEST['fuelf'],
        'amountf' => $_REQUEST['amountf'],
        'ratef' => $_REQUEST['ratef'],
        'refnof' => $_REQUEST['refnof'],
        'startkmf' => $_REQUEST['startkmf'],
        'endkmf' => $_REQUEST['endkmf'],
        'netkmf' => $_REQUEST['netkmf'],
        'averagef' => $_REQUEST['averagef'],
        'dealerf' => $_REQUEST['dealerf'],
        'datef' => $_REQUEST['datef'],
        'srnof' => $_REQUEST['srnof'],
        'addamtf' => $_REQUEST['addamtf'],
        'notesf' => $_REQUEST['notesf'],
        'ofasnumberf' => $_REQUEST['ofasnumberf'],
        'chequenof' => $_REQUEST['chequenof'],
        'chequeamountf' => $_REQUEST['chequeamountf'],
        'chequedatef' => $_REQUEST['chequedatef'],
        'tdsamountf' => $_REQUEST['tdsamountf']
    );

    $customerno = exit_issetor($_SESSION['customerno']);
    $cat = generate_maintenance_fuel_pdf($_REQUEST['vehicleid'], $_REQUEST['vehno'], $_REQUEST['STdate'], $_REQUEST['EDdate'], $_REQUEST['dealer'], $customerno, $sortdata_arr, $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_FuelHistory.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'vehrenewalpdf') {

    require_once 'reports_renewal_functions.php';
    ob_start();
    $customerno = exit_issetor($_SESSION['customerno']);
    $cat = getvehicles_renewal_pdf();
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');

        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_RenewalReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'loginhistory') {
    require_once '../user/user_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    if (isset($_REQUEST['sdate']) && isset($_REQUEST['edate'])) {
        $cat = getloginhistorypdf($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['stime'], $_REQUEST['etime'], $groupname);
    }
    $content = ob_get_clean();
    require_once 'html2pdf.php';

    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_Loginhistorydetails.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'transHistMaintenancePdf') {
    include_once 'reports_fuelefficiency_functions.php';
    $groupname = $_SESSION['groupname'];
    ob_start();
    $customerno = $_REQUEST['customerno'];
    $cat = pdf_maintenance_trans_hist($_REQUEST['vehicleid'], $_REQUEST['vehno'], $_REQUEST['STdate'], $_REQUEST['EDdate'], $_REQUEST['dealer'], $customerno, $_REQUEST['category'], $_REQUEST['status'], $groupname);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_transactionhistory.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'callreport') {
    include_once '../sales/sales_function.php';
    ob_start();

    $customerno = $_REQUEST['customerno'];
    $userid = $_REQUEST['userid'];
    $prevdate = $_REQUEST['prevdate'];
    $startdate = date('Y-m-d', strtotime($_REQUEST['startdate']));
    $enddate = date('Y-m-d', strtotime($_REQUEST['enddate']));

    $srarr = $_REQUEST['srarr'];
    $srid = $_REQUEST['srid'];

    if ($srid != 0) {
        $datatest = array(
            'srid' => $srid,
            'prevdate' => $prevdate,
            'startdate' => $startdate,
            'enddate' => $enddate,
            'userid' => $userid,
            'customerno' => $customerno,
            'srarr' => $srarr
        );
    }
    $cat = pdf_callreport($datatest);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_callreport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'skuorderdetails') {
    include_once '../sales/sales_function.php';
    ob_start();
    $customerno = $_REQUEST['customerno'];
    $userid = $_REQUEST['userid'];
    $prevdate = $_REQUEST['prevdate'];
    $stdate = $_REQUEST['stdate'];
    $edate = $_REQUEST['edate'];
    $srarr = $_REQUEST['srarr'];
    $srid = $_REQUEST['srid'];
    $suplist = $_REQUEST['suplist'];
    $srlist = $_REQUEST['srlist'];

    if ($srid != 0) {
        $datatest = array(
            'srid' => $srid,
            'prevdate' => $prevdate,
            'stdate' => $stdate,
            'edate' => $edate,
            'userid' => $userid,
            'customerno' => $customerno,
            'srarr' => $srarr,
            'suplist' => $suplist,
            'srlist' => $srlist
        );
    }
    $cat = pdf_stylereport($datatest);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_stylereport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
} elseif ($_REQUEST['report'] == 'inactive') {
    require_once 'reports_common_functions.php';

    ob_start();
    $cat = getInactiveDevicePDF($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate']);


        $content = ob_get_clean();
        require_once 'html2pdf.php';
        try {
            $html2pdf = new HTML2PDF('L', 'A4', 'en');
            $html2pdf->pdf->SetDisplayMode('fullpage');

            $html2pdf->writeHTML($content);
            $html2pdf->Output("Inactive_Report_" . date("d-m-Y") . "_GensetReport.pdf");
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
}elseif ($_REQUEST['report'] == 'temperature_nestle') {
    require_once 'reports_common_functions.php';
    if(isset($_REQUEST['tempselect']) && $_REQUEST['tempselect'] != null){
        $_REQUEST['tempselect'] = $_REQUEST['tempselect'];
    }else{
        $_REQUEST['tempselect'] = null;
    }
    ob_start();
    $groupname = $_SESSION['groupname'];
    echo $cat = gettempreportpdf_nestle($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['vehicleid'], $_REQUEST['vehicleno'], $_REQUEST['interval'], $_REQUEST['stime'], $_REQUEST['etime'], $_REQUEST['switchto'], $groupname, 'pdf' , $_REQUEST['tempselect']);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try{
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($cat);
        ob_end_clean(); //add this line here
        $html2pdf->Output($_REQUEST['vehicleno'] . "_" . date("d-m-Y") . "_TemperatureReport.pdf");
    }catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}
 elseif ($_REQUEST['report'] == 'smsStore') {
    require_once 'reports_common_functions.php';
    ob_start();
    $cat = getSmsStorePDF($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate']);


        $content = ob_get_clean();
        require_once 'html2pdf.php';
        try {
            $html2pdf = new HTML2PDF('L', 'A4', 'en');
            $html2pdf->pdf->SetDisplayMode('fullpage');

            $html2pdf->writeHTML($content);
            $html2pdf->Output("Inactive_Report_" . date("d-m-Y") . ".pdf");
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
}
 elseif ($_REQUEST['report'] == 'inactiveVehicle') {
    require_once 'reports_common_functions.php';

    ob_start();
    $cat = getInactiveVehiclePDF($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate']);


        $content = ob_get_clean();
        require_once 'html2pdf.php';
        try {
            $html2pdf = new HTML2PDF('L', 'A4', 'en');
            $html2pdf->pdf->SetDisplayMode('fullpage');

            $html2pdf->writeHTML($content);
            $html2pdf->Output("Inactive_Vehicle_Report_" . date("d-m-Y") . ".pdf");
        } catch (HTML2PDF_exception $e) {
            echo $e;
            exit;
        }
}
else if ($_REQUEST['report'] == 'powerStatus') {
    include 'power_report_function.php';
    $customerno = $_SESSION['customerno'];
    $groupname = $_SESSION['groupname'];
     ob_start();
    $cat = get_power_status_report_pdf($_REQUEST['vehicleid'], $_REQUEST['sdate'], $_REQUEST['edate'], $_REQUEST['stime'], $_REQUEST['etime'],$customerno, $groupname);
    $content = ob_get_clean();
     require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
         ob_end_clean();
        $html2pdf->Output($cat . "_" . date("d-m-Y") . "_powerstatus.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}
else if ($_REQUEST['report'] == 'doorSensor'){
    include 'reports_door_functions.php';
    $groupname  = $_SESSION['groupname'];
    $doorsensor  = $_REQUEST['doorsensor'];
    $vehicleno  = $_REQUEST['vehicleno'];
    $deviceid  = $_REQUEST['deviceid'];
     ob_start();
    $cat = get_door_sensor_report_pdf($_REQUEST['sdate'], $_REQUEST['edate'],$deviceid,$vehicleno, $doorsensor);
    $content = ob_get_clean();
    require_once 'html2pdf.php';
    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
         ob_end_clean();
        $html2pdf->Output($cat . "_" . date("d-m-Y") . "_doorSensor.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}
else if ($_REQUEST['report'] == 'annexure'){
      require_once 'reports_common_functions.php';
      ob_start();
      $cat     = getAnnexurePDF($_REQUEST['customerno'], $_REQUEST['sdate'], $_REQUEST['edate']);
      $content = ob_get_clean();
      require_once 'html2pdf.php';
      try {
          $html2pdf = new HTML2PDF('L', 'A4', 'en');
          $html2pdf->pdf->SetDisplayMode('fullpage');
          $html2pdf->writeHTML($content);
          $html2pdf->Output("Annexure_Report_" . date("d-m-Y") . ".pdf");
      } catch (HTML2PDF_exception $e) {
          echo $e;
          exit;
      }
}
elseif ($_REQUEST['report'] == 'DriverPerformanceReport') {
    require_once 'reports_common_functions.php';
    include_once 'distancereport_functions.php';
    ob_start();
    $groupname = $_SESSION['groupname'];
    $use_hierarchy = ret_issetor($_REQUEST['use_hierarchy']);

    $cat = getDriverPerformanceReportpdf($_REQUEST['drivername'], $_REQUEST['driverid'], $_REQUEST['customerno'], $_REQUEST['use_maintainance'], $use_hierarchy, $_REQUEST['groupid'], $_REQUEST['sdate'], $_REQUEST['edate'], $groupname);
    $content = ob_get_clean();

    //die;

    require_once 'html2pdf.php';

    try {
        $html2pdf = new HTML2PDF('L', 'A4', 'en');
        $html2pdf->pdf->SetDisplayMode('fullpage');
        $html2pdf->writeHTML($content);
        $html2pdf->Output(date("d-m-Y") . "_DistanceReport.pdf");
    } catch (HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
}
