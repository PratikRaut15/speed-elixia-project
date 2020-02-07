<?php
if (isset($_SESSION['visits_modal'])) {
    $session_variable = $_SESSION['visits_modal'];
}
$newauto = false;
switch ($page) {
    case 'realtimedata.php':
        if (isset($_SESSION['visits_modal']) && $_SESSION['visits_modal'] != '0') {
            echo '<link href="' . $_SESSION['subdir'] . '/css/modal.css" rel="stylesheet" type="text/css" />';
        }
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/style/tipsy.css" type="text/css" />';
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/css/realtimedata.css" type="text/css" />';
        //echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/css/checkbox.css" type="text/css" />';
        break;
    case 'vehicleDashboard.php':
        if (isset($_SESSION['visits_modal']) && $_SESSION['visits_modal'] != '0') {
            echo '<link href="' . $_SESSION['subdir'] . '/css/modal.css" rel="stylesheet" type="text/css" />';
        }
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/style/tipsy.css" type="text/css" />';
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/css/realtimedata.css" type="text/css" />';
        //echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/css/checkbox.css" type="text/css" />';
        break;
    case 'warehouse.php':
        if ($_SESSION['visits_modal'] != '0') {
            echo '<link href="' . $_SESSION['subdir'] . '/css/modal.css" rel="stylesheet" type="text/css" />';
        }
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/style/tipsy.css" type="text/css" />';
        //echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/css/checkbox.css" type="text/css" />';
        break;
    case 'route_dashboard.php':
        echo '<link href="' . $_SESSION['subdir'] . '/css/modal.css" rel="stylesheet" type="text/css" />';
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/style/tipsy.css" type="text/css" />';
    case 'reports.php':
        echo '<link href="' . $_SESSION['subdir'] . '/css/modal.css" rel="stylesheet" type="text/css" />';
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/style/tipsy.css" type="text/css" />';
        break;
    case 'map.php':
        echo '<link href="' . $_SESSION['subdir'] . '/css/modal.css" rel="stylesheet" type="text/css" />';
        break;
    case 'mobility.php':
        $newauto = TRUE;
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/bootstrap/new/bootstrap.min.css" type="text/css" />';
        //echo '<link rel="stylesheet" href="'. $_SESSION['subdir'].'/css_index/bootstrap.css" type="text/css" />';
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/autocomplete/jquery-ui.min.css" type="text/css" />';
        echo '<link href="' . $_SESSION['subdir'] . '/css/mobility.css" rel="stylesheet" type="text/css" />';
        break;
    case 'assign.php':
        $newauto = true;
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/autocomplete/jquery-ui.min.css" type="text/css" />';
        break;
    case 'zoneVehicleMapping.php':
        $newauto = true;
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/autocomplete/jquery-ui.min.css" type="text/css" />';
        break;
    case 'pick.php':
        $newauto = true;
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/autocomplete/jquery-ui.min.css" type="text/css" />';
        break;
    case 'pickup.php':
        $newauto = true;
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/autocomplete/jquery-ui.min.css" type="text/css" />';
        break;
    case 'orders.php':
        $newauto = true;
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/autocomplete/jquery-ui.min.css" type="text/css" />';
        break;
    case 'sales.php':
        //echo '<link rel="stylesheet" href="'. $_SESSION['subdir'].'/bootstrap/new/bootstrap.min.css" type="text/css" />';
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/autocomplete/jquery-ui.min.css" type="text/css" />';
        echo '<link href="' . $_SESSION['subdir'] . '/css/sales.css" rel="stylesheet" type="text/css" />';
        break;
    case 'salesengage.php':
        $newauto = TRUE;
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/bootstrap/new/bootstrap.min.css" type="text/css" />';
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/autocomplete/jquery-ui.min.css" type="text/css" />';
        echo '<link href="' . $_SESSION['subdir'] . '/css/salesengage.css" rel="stylesheet" type="text/css" />';
        break;
    case 'tms.php':
        $newauto = false;
        //echo '<link rel="stylesheet" href="'. $_SESSION['subdir'].'/bootstrap/new/bootstrap.min.css" type="text/css" />';
        //echo '<link rel="stylesheet" href="'. $_SESSION['subdir'].'/css_index/bootstrap.css" type="text/css" />';
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/autocomplete/jquery-ui.min.css" type="text/css" />';
        echo '<link href="' . $_SESSION['subdir'] . '/css/tms.css" rel="stylesheet" type="text/css" />';
        break;
    case 'trips.php':
        //echo '<link rel="stylesheet" href="'. $_SESSION['subdir'].'/bootstrap/new/bootstrap.min.css" type="text/css" />';
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/autocomplete/jquery-ui.min.css" type="text/css" />';
        echo '<link href="' . $_SESSION['subdir'] . '/css/trips.css" rel="stylesheet" type="text/css" />';
        break;
    case 'genset.php':
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/autocomplete/jquery-ui.min.css" type="text/css" />';
        break;
    case 'loginhistory.php':
        echo '<link href="' . $_SESSION['subdir'] . '/css/modal.css" rel="stylesheet" type="text/css" />';
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/style/tipsy.css" type="text/css" />';
        echo '<link href="' . $_SESSION['subdir'] . '/style/style.css" rel="stylesheet" type="text/css" />';
        break;
    case 'users.php':
        echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/jstree-master/dist/themes/default/style.min.css" type="text/css" />';
        break;
    case 'sectms.php':
        $newauto = false;
        //echo '<link rel="stylesheet" href="'. $_SESSION['subdir'].'/bootstrap/new/bootstrap.min.css" type="text/css" />';
        //echo '<link rel="stylesheet" href="'. $_SESSION['subdir'].'/css_index/bootstrap.css" type="text/css" />';
        //echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/autocomplete/jquery-ui.min.css" type="text/css" />';
        //echo '<link href="' . $_SESSION['subdir'] . '/css/tms.css" rel="stylesheet" type="text/css" />';
        break;
    case 'mapdashboard.php':
        echo '<link href="' . $_SESSION['subdir'] . '/css/modal.css" rel="stylesheet" type="text/css" />';
        break;
    case 'toll.php':
        echo '<link href="' . $_SESSION['subdir'] . '/css/modal.css" rel="stylesheet" type="text/css" />';
        break;
    case 'radar.php':
        echo '<link href="' . $_SESSION['subdir'] . '/css/modal.css" rel="stylesheet" type="text/css" />';
        break;
    case 'mappage.php':
        echo '<link href="' . $_SESSION['subdir'] . '/css/modal.css" rel="stylesheet" type="text/css" />';
        break;
}
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/style/sticky.css" type="text/css" />';
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/bootstrap/css/bootstrap.css" type="text/css" />';
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/css/bootstrap.css" type="text/css" />';
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/datetime/datepicker.css" />';
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/slider/css/slider.css" />';
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/css/chardin.css" />';
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/autocomplete/jquery-ui.min.css" type="text/css" />';
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/scripts/trash/timepicker/jquery.timepicker.css" type="text/css" />';
if (!$newauto) {
    echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/style/autoSuggest.css" type="text/css" />';
}
echo '<link href="' . $_SESSION['subdir'] . '/font-awesome/css/font-awesome.min.css" rel="stylesheet">';
echo '<link rel="stylesheet" href="' . $_SESSION['subdir'] . '/css/elixia-site.css" type="text/css" />';
?>