<?php
/*Below check is added for client code login*/
if (isset($_SESSION['ecodeid']) && !empty($_SESSION['ecodeid'])) {
    $_SESSION['visits_modal'] = '';
}
if ($page != 'reports.php' || $_SESSION['visits_modal'] != '0') {
// donot touch this code
    $jquery = "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/bootstrap/js/jquery.min.js' > </script>";
// do not touch this code
    echo $jquery;
}
echo "<script src='" . $_SESSION['subdir'] . "/bootstrap/js/bootstrap.js' type='text/javascript'></script>";
// do not touch this code

$googleapi = "<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?v=3.16&sensor=false&&libraries=drawing,places&key=" . GOOGLE_MAP_API_KEY . "'></script>";

//$googleapi = "<script type='text/javascript' src='https://maps.google.com/maps/api/js?sensor=false&libraries=drawing,places&key=" . GOOGLE_MAP_API_KEY . "'></script>";
$chartapi = "<script type='text/javascript' src='https://www.google.com/jsapi'></script>";
$paginator = "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/easypaginate.js'></script>";
$filter = "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/filter/picnet.table.filter.min.js'></script>";
$filter_init = "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/filter/init_filter.js'></script>";
$realtime_slide = "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/realtime_slide_map.js'></script>";
$history_inti = "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/travel_history.js'></script>";
$export_reports = "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/exportReports.js'></script>";
$all_js = "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/all_js.js'></script>";
$tipsy = "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/jquery.tipsy.js'></script>";
$easing = "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/slide_down_ease/jquery.easing.min.js'></script>";
$slider = " <script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/slider/js/bootstrap-slider.js'></script>";
if (isset($_SESSION['visits_modal'])) {
    $session_variable = $_SESSION['visits_modal'];
}
// user first use the lib the use the init js file
$timepicker = "<script src='" . $_SESSION['subdir'] . "/scripts/datetime/bootstrap-timepicker.min.js'></script>";
$timepick = "<script src='" . $_SESSION['subdir'] . "/scripts/datetime/bootstrap-timepicker.js'></script>";
$date_picker = "<script src='" . $_SESSION['subdir'] . "/scripts/datetime/bootstrap-datepicker.min.js'></script>";
$date_init = "<script src='" . $_SESSION['subdir'] . "/scripts/datetime/datetime_bootstrapper.js'></script>";
$datetime_picker = "<script src='" . $_SESSION['subdir'] . "/scripts/datetime/bootstrap-datetimepicker.min.js'></script>";
$datetime_init = "<script src='" . $_SESSION['subdir'] . "/scripts/datetime/bootstrap-datetimepicker.pt-BR.js'></script>";
$edit_vehicle = "<script src='" . $_SESSION['subdir'] . "/scripts/edit_vehicles.js'></script>";
$drivers = "<script src='" . $_SESSION['subdir'] . "/scripts/modules/drivers.js'></script>";
$checkpoints = "<script src='" . $_SESSION['subdir'] . "/scripts/modules/checkpoints.js'></script>";
$editcheckpoints = "<script src='" . $_SESSION['subdir'] . "/scripts/modules/editcheckpoints.js'></script>";
$checkpointtypes = "<script src='" . $_SESSION['subdir'] . "/scripts/modules/checkpointtypes.js'></script>";
$editcheckpointtypes = "<script src='" . $_SESSION['subdir'] . "/scripts/modules/editcheckpointtypes.js'></script>";
$checkpointscz = "<script src='" . $_SESSION['subdir'] . "/scripts/modules/checkpointscz.js'></script>";
$editcheckpointscz = "<script src='" . $_SESSION['subdir'] . "/scripts/modules/editcheckpointscz.js'></script>";
$location = "<script src='" . $_SESSION['subdir'] . "/scripts/modules/location.js'></script>";
$editlocation = "<script src='" . $_SESSION['subdir'] . "/scripts/modules/editlocation.js'></script>";
echo "<script src='" . $_SESSION['subdir'] . "/scripts/sticky.js' type='text/javascript'></script>";
/* Marker Google Utility */
$markerwithlable = "<script src='" . $_SESSION['subdir'] . "/scripts/googlemaps/v3-utility-library/markerwithlable/src/markerwithlable.js'></script>";
$markerclusterer = "<script src='" . $_SESSION['subdir'] . "/scripts/googlemaps/v3-utility-library/markerclusterer/src/markerclusterer.js'></script>";
$markerclustererplus = "<script src='" . $_SESSION['subdir'] . "/scripts/googlemaps/v3-utility-library/markerclustererplus/src/markerclusterer.js'></script>";
/* Script Variables -- SS */
if (isset($_SESSION['customerno'])) {
    echo $all_js;
    echo "<script src='" . $_SESSION['subdir'] . "/scripts/speedUtils.js' type='text/javascript'></script>";
    echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
}
echo $timepicker;
if ($page != 'mobility.php' && $page != 'salesengage.php' && $page != 'sales.php') {
    echo $date_picker;
}
echo $date_init;
//echo $datetime_picker;
//echo $datetime_init;
//echo $timepick;
//echo "Page is: ".$page; exit();
switch ($page) {
    case 'index.php':
        echo $googleapi;
        echo "<script src='scripts/ecodemap.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
        echo $markerwithlable;
        echo $markerclusterer;
        break;
    case 'speed_dashboard.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/speed_dashboard.js' type='text/javascript'></script>";
        echo "";
        echo $history_inti;
        break;
    case 'elixiacode.php':
        echo $googleapi;
        echo "<script src='scripts/ecodemap.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
        echo $markerwithlable;
        echo $markerclusterer;
        break;
    case 'map.php':
        echo $googleapi;
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/vehicles.js' type='text/javascript'></script>";
        echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/createcheck_comman.js'></script>";
        echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/modules/reports/location_map_pop.js'></script>";
        echo '<script src="' . $_SESSION['subdir'] . '/scripts/dragg/jquery-ui.js"></script>';
        echo $markerwithlable;
        echo $markerclustererplus;
        break;
    case 'mapwarehouse.php':
        echo $googleapi;
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/staticvehicles.js' type='text/javascript'></script>";
        echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/createcheck_comman.js'></script>";
        echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/modules/reports/location_map_pop.js'></script>";
        echo '<script src="' . $_SESSION['subdir'] . '/scripts/dragg/jquery-ui.js"></script>';
        echo $markerwithlable;
        echo $markerclustererplus;
        break;
    case 'busRouteMap.php':
        echo $googleapi;
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/busroute/busroute.js' type='text/javascript'></script>";
        echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/createcheck_comman.js'></script>";
        echo '<script src="' . $_SESSION['subdir'] . '/scripts/dragg/jquery-ui.js"></script>';
        echo $markerwithlable;
        echo $markerclustererplus;
        break;
    case 'busZone.php':
        echo $googleapi;
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/Zone/editZones.js' type='text/javascript'></script>";
        echo $markerwithlable;
        break;
    case 'busStopRoute.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
        echo $googleapi;
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/busroute/routeMap.js' type='text/javascript'></script>";
        echo $markerwithlable;
        echo $markerclustererplus;
        break;
    case 'zoneVehicleMapping.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/busroute/zoneVehicleMapping.js'></script>";
        break;
    case 'studentMapping.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/busroute/runBusRouteAlgo.js' ></script>";
        break;
    case 'directionMap.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
        echo $googleapi;
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/busroute/busStopRouting.js' type='text/javascript'></script>";
        echo $markerwithlable;
        echo $markerclustererplus;
        break;
    case 'busStop.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/busroute/busStop.js' type='text/javascript'></script>";
        break;
    case 'busRoute.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/busroute/viewBusRoute.js' type='text/javascript'></script>";
        break;
    case 'warehousemap.php':
        echo $googleapi;
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/warehouse.js' type='text/javascript'></script>";
        echo '<script src="' . $_SESSION['subdir'] . '/scripts/dragg/jquery-ui.js"></script>';
        echo $markerwithlable;
        echo $markerclustererplus;
        break;
    case 'checkpoint.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/modal.js' type='text/javascript'></script>";
        echo $googleapi;
        if ((isset($_GET['id']) && $_GET['id'] == '1') || !isset($_GET['id'])) {
            echo $checkpoints;
        } elseif ((isset($_GET['id']) && $_GET['id'] == '3')) {
            echo $editcheckpoints;
        }
        echo '<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>';
        //$jquery = "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/bootstrap/js/jquery.min.js' > </script>";
        // do not touch this code
        //echo $jquery;
        break;
    case 'checkpointtype.php':
        if ((isset($_GET['id']) && $_GET['id'] == '1') || !isset($_GET['id'])) {
            echo $checkpointtypes;
        } elseif ((isset($_GET['id']) && $_GET['id'] == '3')) {
            echo $editcheckpointtypes;
        }
        echo '<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>';
        break;
    case 'czone.php':
        if (!isset($_GET['id']) || $_GET['id'] == '1') {
            echo $googleapi;
            echo $checkpointscz;
        } elseif ($_GET['id'] == '3') {
            echo $googleapi;
            echo $editcheckpointscz;
        }
        echo '<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>';
        break;
    case 'enh_checkpoint.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/enh_chkpt.js' type='text/javascript'></script>";
        echo '<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>';
        break;
    case 'checkpointException.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/modules/checkpointException.js' type='text/javascript'></script>";
        break;
    case 'fencing.php':
        if (!isset($_GET['id']) || $_GET['id'] == '1') {
            echo $googleapi;
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/fence_create.js' type='text/javascript'></script>";
        } elseif ($_GET['id'] == '3') {
            echo $googleapi;
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/fence_create.js' type='text/javascript'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/fenceonmap.js' type='text/javascript'></script>";
        } elseif ($_GET['id'] == '4') {
            echo $googleapi;
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/fencing/editFences.js' type='text/javascript'></script>";
            echo '<script src="' . $_SESSION['subdir'] . '/scripts/dragg/jquery-ui.js"></script>';
            echo $markerwithlable;
        }
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
        break;
    case 'zone.php':
        if (!isset($_GET['id']) || $_GET['id'] == '1') {
            echo $googleapi;
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/zone_create.js' type='text/javascript'></script>";
        } elseif ($_GET['id'] == '3') {
            echo $googleapi;
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/zone_create.js' type='text/javascript'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/zoneonmap.js' type='text/javascript'></script>";
        } elseif ($_GET['id'] == '4') {
            echo $googleapi;
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/Zone/editZones.js' type='text/javascript'></script>";
            echo '<script src="' . $_SESSION['subdir'] . '/scripts/dragg/jquery-ui.js"></script>';
            echo $markerwithlable;
            echo $markerclusterer;
        }
        echo '<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>';
        break;
    case 'accinfo.php':
        if (!isset($_GET['id']) || $_GET['id'] == '1' || $_GET['id'] == '2' || $_GET['id'] == '6') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/account.js' type='text/javascript'></script>";
        } elseif ($_GET['id'] == '4') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/edituser.js' type='text/javascript'></script>";
        } elseif ($_GET['id'] == '3') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/account.js' type='text/javascript'></script>";
        }
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/edituser.js' type='text/javascript'></script>";
        echo $export_reports;
        break;
    case 'history.php':
        if (!isset($_GET['id']) || $_GET['id'] == '1') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/getlocation.js' type='text/javascript'></script>";
        } elseif ($_GET['id'] == '5') {
            echo $googleapi;
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/vehiclehist.js' type='text/javascript'></script>";
        }
        break;
    case 'vehicle.php':
        if (!isset($_GET['id']) || $_GET['id'] == '1') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/aevehicle.js' type='text/javascript'></script>";
        } elseif ($_GET['id'] == '4') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/aevehicle.js' type='text/javascript'></script>";
            echo $edit_vehicle;
        } elseif ($_GET['id'] == '2') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/bootstrap.file-input.js' type='text/javascript'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/vehicle_support.js' type='text/javascript'></script>";
        } elseif ($_GET['id'] == '43') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/vehicle_renewal.js' type='text/javascript'></script>";
        } elseif ($_GET['id'] == '3') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/vehiclemapper.js' type='text/javascript'></script>";
        } elseif ($_GET['id'] == '6' || $_GET['id'] == '8') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/add_vehicle.js' type='text/javascript'></script>";
        } elseif ($_GET['id'] == '7' || $_GET['id'] == '9') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/edit_vehicle.js' type='text/javascript'></script>";
        }
        break;
    case 'support.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/support.js' type='text/javascript'></script>";
        break;
    case 'genset.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
        break;
    case 'location.php':
        echo $googleapi;
        if ($_GET['id'] == '1' || !isset($_GET['id'])) {
            echo $location;
        }
        if ($_GET['id'] == '3') {
            echo $editlocation;
        }
        echo '<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>';
        break;
    case 'driver.php':
        echo $drivers;
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/modal.js' type='text/javascript'></script>";
        if (isset($_GET['id']) && $_GET['id'] == '3') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/drivermapper.js' type='text/javascript'></script>";
        }
        break;
    case 'route.php':
        if (!isset($_GET['id']) || $_GET['id'] == '1' || $_GET['id'] == '4' || $_GET['id'] == '6' || $_GET['id'] == '7') {
            //echo '<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>';
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/trash/timepicker/jquery.timepicker.js' type='text/javascript'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/route.js' type='text/javascript'></script>";
        }
        break;
    case 'enh_route.php':
        if (!isset($_GET['id']) || $_GET['id'] == '1' || $_GET['id'] == '4' || $_GET['id'] == '6' || $_GET['id'] == '7') {
            echo '<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>';
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/route.js' type='text/javascript'></script>";
        }
        break;
    case 'route_enc.php':
        if (!isset($_GET['id']) || $_GET['id'] == '1' || $_GET['id'] == '4') {
            echo '<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>';
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/route.js' type='text/javascript'></script>";
        }
        break;
    case 'distance_dashboard.php':
        echo '<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>';
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/route.js' type='text/javascript'></script>";
        break;
    case 'realtimedata.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/chardin.js' type='text/javascript'></script>";
        echo $easing;
        echo $googleapi;
        echo $realtime_slide;
        echo $filter;
        include_once '../../scripts/filter/init_filter.php';
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/modal.js' type='text/javascript'></script>";
        echo '<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>';
        if ($session_variable == '0') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/account.js' type='text/javascript'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/edituser.js' type='text/javascript'></script>";
        }
        echo $markerwithlable;
        //echo $calendar;
        echo $tipsy;
        break;
    case 'vehicleDashboard.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/chardin.js' type='text/javascript'></script>";
        echo $easing;
        echo $googleapi;
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/vehicleDashboard.js' type='text/javascript'></script>";
        echo $filter;
        include_once '../../scripts/filter/init_filter.php';
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/modal.js' type='text/javascript'></script>";
        echo '<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>';
        if ($session_variable == '0') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/account.js' type='text/javascript'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/edituser.js' type='text/javascript'></script>";
        }
        echo $markerwithlable;
        //echo $calendar;
        echo $tipsy;
        break;
    case 'warehouse.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/chardin.js' type='text/javascript'></script>";
        echo $easing;
        echo $googleapi;
        echo $realtime_slide;
        echo $filter;
        include_once '../../scripts/filter/init_filter_warehouse.php';
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/modal.js' type='text/javascript'></script>";
        echo '<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>';
        if ($session_variable == '0') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/account.js' type='text/javascript'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/edituser.js' type='text/javascript'></script>";
        }
        echo $markerwithlable;
        //echo $calendar;
        echo $tipsy;
        break;
    case 'reports.php':
        $advanced_report = array(33, 34, 35, 36, 51);
        $highchart_report = array(21, 23, 24, 25, 26, 27, 29);
        //$secondary_sales_report = array(47, 48, 49, 50, 51, 52, 53);
        if (!isset($_GET['id']) || $_GET['id'] == '1' || $_GET['id'] == '38') {
            echo $history_inti;
            echo $export_reports;
            echo $googleapi;
            echo $slider;
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/routehist_new.js' type='text/javascript'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/modules/reports/location_map_pop.js' type='text/javascript'></script>";
            echo $markerwithlable;
        } elseif (in_array($_GET['id'], $advanced_report)) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/advanced_report.js'></script>";
        } elseif (isset($_GET['id']) && $_GET['id'] == '37') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/maintenance/fuel_history.js'></script>";
        } else {
            echo $history_inti;
            echo $export_reports;
            echo $googleapi; //ak added
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/modal.js' type='text/javascript'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/modules/reports/location_map_pop.js' type='text/javascript'></script>";
            echo $markerwithlable;
        }
        if (isset($_GET['id']) && in_array($_GET['id'], $highchart_report)) {
            echo '<script src="../../scripts/highcharts/js/highcharts.js" type="text/javascript"></script>';
            echo '<script src="../../scripts/highcharts/js/modules/exporting.js" type="text/javascript"></script>';
        }
        echo '<script src="../../scripts/reports/mail_pop_up.js" type="text/javascript"></script>';
        break;
    case 'ecode.php':
        echo "<script src = '" . $_SESSION['subdir'] . "/scripts/ecode.js' type='text/javascript'></script>";
        break;
    case 'users.php':
        echo "<script src = '" . $_SESSION['subdir'] . "/scripts/account.js' type='text/javascript'></script>";
        if (!isset($_GET['id']) || $_GET['id'] == '1') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/account_ready.js' type='text/javascript'></script>";
        }
        break;
    case 'smstracking.php':
        echo "<script src = '" . $_SESSION['subdir'] . "/scripts/smstrack.js' type='text/javascript'></script>";
        break;
    case 'group.php':
        if (!isset($_GET['id']) || $_GET['id'] == '1' || $_GET['id'] == '4') {
            echo '<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>';
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/group.js' type='text/javascript'></script>";
        }
        break;
    case 'nation.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/master.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickup/pickup.js' type='text/javascript'></script>";
        break;
    case 'shiper.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/master.js' type='text/javascript'></script>";
        break;
    case 'order_edit.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/master.js' type='text/javascript'></script>";
        break;
    case 'route_dashboard.php':
        echo $tipsy;
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/route_dashboard.js' type='text/javascript'></script>";
        echo $googleapi;
        echo '<script src="https://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>';
        echo $markerwithlable;
        break;
    case 'approvals.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/master.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/approvals.js' type='text/javascript'></script>";
        if (isset($_GET['id']) && $_GET['id'] == '3') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/approval_view.js' type='text/javascript'></script>";
        }
        break;
    case 'transaction.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/bootstrap.file-input.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/transaction.js' type='text/javascript'></script>";
        break;
    case 'student.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/bootstrap.file-input.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/student.js' type='text/javascript'></script>";
        break;
    case 'modifytransaction.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/bootstrap.file-input.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/transaction.js' type='text/javascript'></script>";
        break;
    case 'mobility.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/mobility/main.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/mobility/datatable.js' type='text/javascript'></script>";
        if (!isset($_GET['pg'])) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/mobility/timeline.js' type='text/javascript'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
        }
        break;
    case 'salesengage.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/salesengage/main.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/salesengage/datatable.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/salesengage/multipleselect/jquery.multiselect.js' type='text/javascript'></script>";
        echo '<link href="' . $_SESSION['subdir'] . '/scripts/salesengage/multipleselect/jquery.multiselect.css" rel="stylesheet" type="text/css" />';
        echo '<link href="' . $_SESSION['subdir'] . '/scripts/salesengage/multipleselect/jquery-ui.css" rel="stylesheet" type="text/css" />';
        break;
    case 'tms.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
        if ($_GET['pg'] == 'bills' || $_GET['pg'] == 'edit-bill' || $_GET['pg'] == 'edit-draft') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/tms/vendorPayable.js' type='text/javascript'></script>";
        } else {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/tms/tms.js' type='text/javascript'></script>";
        }
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/tms/tmsautoSuggest.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/tms/datatable.js' type='text/javascript'></script>";
        break;
    case 'sales.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/sales/main.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/sales/datatable.js' type='text/javascript'></script>";
        break;
    case 'assign.php':
        if (!isset($_GET['id']) || $_GET['id'] == 1) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/routing/route_map.js'></script>";
        } elseif ($_GET['id'] == 2) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
            echo $googleapi;
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/routing/vehicleRoutes.js' type='text/javascript'></script>";
            echo $markerwithlable;
            echo $markerclustererplus;
        } elseif ($_GET['id'] == 3) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/routing/assignOrders.js' ></script>";
        } elseif ($_GET['id'] == 4 || $_GET['id'] == 10) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
            echo $googleapi;
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/routing/editLatLong.js' ></script>";
        } elseif ($_GET['id'] == 5) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/routing/routeAlgo.js' ></script>";
        } elseif ($_GET['id'] == 6 || $_GET['id'] == 7 || $_GET['id'] == 17) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/routing/mastersDisplay.js' ></script>";
        } elseif ($_GET['id'] == 8 || $_GET['id'] == 9 || $_GET['id'] == 13 || $_GET['id'] == 14 || $_GET['id'] == 16) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/routing/orders.js' ></script>";
        } elseif ($_GET['id'] == 11) {
            echo $googleapi;
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/routing/heatMap.js' type='text/javascript'></script>";
        } elseif ($_GET['id'] == 12) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/routing/viewSeq.js' ></script>";
        } elseif ($_GET['id'] == 19) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
        }
        break;
    case 'pick.php':
        if ($_GET['id'] == 6 || $_GET['id'] == 7 || $_GET['id'] == 17) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickup/mastersDisplay.js' ></script>";
        } elseif ($_GET['id'] == 4 || $_GET['id'] == 10) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
            echo $googleapi;
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickup/editLatLong.js' ></script>";
        } elseif (!isset($_GET['id']) || $_GET['id'] == 1) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickup/route_map.js'></script>";
        } elseif ($_GET['id'] == 5) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickup/routeAlgo.js' ></script>";
        } elseif ($_GET['id'] == 8 || $_GET['id'] == 16) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickup/orders.js' ></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickup/pickup.js' ></script>";
        } elseif ($_GET['id'] == 10) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
            echo $googleapi;
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickup/editLatLong.js' ></script>";
        } elseif ($_GET['id'] == 2) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
            echo $googleapi;
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickup/vehicleRoutes.js' type='text/javascript'></script>";
            echo $markerwithlable;
            echo $markerclustererplus;
        } elseif ($_GET['id'] == 3) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
            if ($switch_to == 10) {
                echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickupwow/assignOrders.js' ></script>";
            } else {
                echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickup/assignOrders.js' ></script>";
            }
        } elseif ($_GET['id'] == 12) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickup/viewSeq.js' ></script>";
        } elseif ($_GET['id'] == 19) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/master.js' type='text/javascript'></script>";
        } elseif ($_GET['id'] == 20) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickup/assignUserOrders.js' ></script>";
        } elseif ($_GET['id'] == 24) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickup/datatable.js' type='text/javascript'></script>";
        }
        break;
    case 'orders.php':
        if (!isset($_GET['id']) || $_GET['id'] == 1) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickup/assignOrders.js' ></script>";
        }
        break;
    case 'trips.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
        if ($_GET['pg'] != 'tripreport') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/trips/datatable.js' type='text/javascript'></script>";
        }
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/trips/main.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
        if ($_GET['pg'] == 'addlr') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/trips/tripsvalidate.js' type='text/javascript'></script>";
        }
        break;
    case 'conditions.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/hierarchy.js' type='text/javascript'></script>";
        break;
    case 'loginhistory.php':
        echo $history_inti;
        break;
    case 'dealer.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/master.js' type='text/javascript'></script>";
        break;
    case 'expensemng.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
        break;
    case 'sectms.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/tms/datatable.js' type='text/javascript'></script>";
        break;
    case 'mapdashboard.php':
        echo $googleapi;
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/mapdashboard.js' type='text/javascript'></script>";
        echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/createcheck_comman.js'></script>";
        echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/modules/reports/location_map_pop.js'></script>";
        echo '<script src="' . $_SESSION['subdir'] . '/scripts/dragg/jquery-ui.js"></script>';
        echo $markerwithlable;
        echo $markerclustererplus;
        break;
    case 'orders.php':
        if (!isset($_GET['id']) || $_GET['id'] == 1) {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/dragg/jquery-ui.js'></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/pickup/assignOrders.js' ></script>";
        }
        break;
    case 'trips.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
        if ($_GET['pg'] != 'tripreport') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/trips/datatable.js' type='text/javascript'></script>";
        }
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/trips/main.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
        if ($_GET['pg'] == 'addlr') {
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/trips/tripsvalidate.js' type='text/javascript'></script>";
        }
        if ($_GET['pg'] == 'trip_mapview') {
            echo $googleapi;
            echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
        }
        break;
    case 'conditions.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/hierarchy.js' type='text/javascript'></script>";
        break;
    case 'loginhistory.php':
        echo $history_inti;
        break;
    case 'dealer.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/master.js' type='text/javascript'></script>";
        break;
    case 'expensemng.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
        break;
    case 'sectms.php':
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/autocomplete/jquery-ui.min.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/datatables/jquery.dataTables.min.js' ></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/tms/datatable.js' type='text/javascript'></script>";
        break;
    case 'mapdashboard.php':
        echo $googleapi;
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/mapdashboard.js' type='text/javascript'></script>";
        echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/createcheck_comman.js'></script>";
        echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/modules/reports/location_map_pop.js'></script>";
        echo '<script src="' . $_SESSION['subdir'] . '/scripts/dragg/jquery-ui.js"></script>';
        echo $markerwithlable;
        echo $markerclustererplus;
        break;
        echo '<script src="' . $_SESSION['subdir'] . '/scripts/dragg/jquery-ui.js"></script>';
        echo $markerwithlable;
        echo $markerclustererplus;
        break;
    case 'radar.php':
        echo $googleapi;
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/radar.js' type='text/javascript'></script>";
        echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/createcheck_comman.js'></script>";
        echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/modules/reports/location_map_pop.js'></script>";
        echo '<script src="' . $_SESSION['subdir'] . '/scripts/dragg/jquery-ui.js"></script>';
        echo $markerwithlable;
        echo $markerclustererplus;
        break;
    case 'mappage.php':
        echo $googleapi;
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/infobox.js' type='text/javascript'></script>";
        echo "<script src='" . $_SESSION['subdir'] . "/scripts/mapvehicles.js' type='text/javascript'></script>";
        echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/scripts/createcheck_comman.js'></script>";
        echo "<script type='text/javascript' src='" . $_SESSION['subdir'] . "/modules/reports/location_map_pop.js'></script>";
        echo '<script src="' . $_SESSION['subdir'] . '/scripts/dragg/jquery-ui.js"></script>';
        echo $markerwithlable;
        echo $markerclustererplus;
        break;
}
?>