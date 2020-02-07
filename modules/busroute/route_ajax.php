<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
if (!isset($_SESSION)) {
    session_start();
}
include 'busrouteFunctions.php';
include 'zone_functions.php';
if (isset($_REQUEST['lat']) && isset($_REQUEST['long']) && isset($_REQUEST['zonename']) && isset($_REQUEST['vehiclearray']) && !isset($_REQUEST['zoneid'])) {
    createnewzone($_REQUEST['lat'], $_REQUEST['long'], $_REQUEST['zonename'], $_REQUEST['vehiclearray']);
} else if (isset($_REQUEST['zoneid']) && isset($_REQUEST['zonename']) && isset($_REQUEST['lat']) && isset($_REQUEST['long']) && isset($_REQUEST['vehiclearray'])) {
    //$vehicleid = GetSafeValueString($_REQUEST['vehicleid'],"string");
    $zoneid = GetSafeValueString($_REQUEST['zoneid'],"string");
    $zonename = GetSafeValueString($_REQUEST['zonename'],"string");
    $lat = GetSafeValueString($_REQUEST['lat'],"string");
    $long = GetSafeValueString($_REQUEST['long'],"string");
    $vehiclearray = GetSafeValueString($_REQUEST['vehiclearray'],"string");
    editzoning($lat,$long,$zonename,$zoneid,$vehiclearray);
}
else if (isset ($_REQUEST['work']) && $_REQUEST['work']=='viewstudent') {
    $students=getstudents();
    echo json_encode($students);
}
else if (isset ($_REQUEST['zoneid']) && $_REQUEST['get']=='zone') 
{
    zoneonmap($_REQUEST['zoneid']);
}
if (isset($_REQUEST['all']) && isset($_REQUEST['isBusStudent'])) {
    $isBusStudent = $_REQUEST['isBusStudent'];
    if ($_REQUEST['isBusStudent'] != 4) {
        $arrResult = getAllStudents($isBusStudent);
        $ajaxpage = new ajaxpage();
        $ajaxpage->SetResult($arrResult);
        $ajaxpage->Render();
    } else {
        $arrResult = array();
        $ajaxpage = new ajaxpage();
        $ajaxpage->SetResult($arrResult);
        $ajaxpage->Render();
    }
} elseif (isset($_REQUEST['all']) && !isset($_REQUEST['isBusStudent'])) {
    $arrResult = getAllStudents();
    $ajaxpage = new ajaxpage();
    $ajaxpage->SetResult($arrResult);
    $ajaxpage->Render();
} elseif (isset($_REQUEST['busRoute'])) {
    $objBusStop = new StdClass();
    $objBusStop->busStopId = "";
    $objBusStop->zoneId = "";
    $objBusStop->customerno = $_SESSION['customerno'];
    $arrResult = getAllBusStops($objBusStop);
    $return = array(
        'finalSeq' => $arrResult,
        'start_point' => "19.250784,72.850693",
    );
    $json = trim(json_encode($return));
    echo $json;
} elseif (isset($_REQUEST['vehmap'])) {
    $vehmap_data = $_POST['vehmap'];
    $customerno = exit_issetor($_SESSION['customerno']);
    $userid = exit_issetor($_SESSION['userid']);
    $date = date('Y-m-d');
    if (empty($vehmap_data)) {
        exit('Vehicles not mapped');
    }
    $data = array();
    foreach ($vehmap_data as $z_slot => $vehicleid_arr) {
        $ex = explode('_', $z_slot);
        foreach ($vehicleid_arr as $vehicleid) {
            $zoneid = filter_var($ex[0], FILTER_SANITIZE_NUMBER_INT);
            $slotid = filter_var($ex[1], FILTER_SANITIZE_NUMBER_INT);
            $vehid = (int) $vehicleid[0];
            if ($vehid == 0) {
                continue;
            }
            $key = "$zoneid-$slotid-$vehid";
            $data[$key] = array(
                'customerno' => $customerno,
                'userid' => $userid,
                'entrytime' => date('Y-m-d h:i:s'),
                'mapdate' => $date,
                'vehicleid' => $vehid,
                'zoneid' => (int) $zoneid,
                'slotid' => (int) $slotid,
            );
        }
    }
    $mm = new MappingManager($customerno);
    if ($mm->mapVehZoneSlot($data)) {
        echo "<span style='font-weight:bold;color:green;'>Mapped successfully</span>";
        exit;
    } else {
        echo "<span style='font-weight:bold;color:red;'>Mapping not successfully</span>";
        exit;
    }
} elseif (isset($_REQUEST['runBusRoute'])) {
    $customerno = exit_issetor($_SESSION['customerno']);
    $mapOrder = exit_issetor($_REQUEST['mapOrders']);
    $dateI = isset($_REQUEST['mapDate']) ? $_REQUEST['mapDate'] : date('Y-m-d');
    $allZones = getZones();
    $allSlots = getSlots();
    $am = new AlgoManager($customerno, $dateI, $allZones, $allSlots);
    $am->runBusRouteAlgorithmByDistance();
} elseif (isset($_REQUEST['busStopRouting'])) {
    $vehid = exit_issetor($_REQUEST['vehid']);
    $startll = "19.250784,72.850693";
    if (!$startll) {
        exit(json_encode(array('error' => 'Vehicle not assigned for this Zone/Slot')));
    }
    $latlong_arr = vehicleBusStopSequence($vehid);
    if (!$latlong_arr) {
        exit(json_encode(array('error' => 'No BusStop found for the Zone/Slot assigned')));
    } else {
        $return = array(
            'finalSeq' => $latlong_arr,
            'start_point' => $startll,
        );
        $json = trim(json_encode($return));
        echo $json;
        exit;
    }
} elseif (isset($_REQUEST['vehicleRoute'])) {
    $arrResult = getAllRoutesByVehicle($_REQUEST['vehid']);
    $routeList = '';
    if (isset($arrResult) && !empty($arrResult)) {
        foreach ($arrResult as $row) {
            $routeList .= "<input type='radio' name='route' class='route_all routeBox' onclick='getRoute($row->routeId);' id ='route_$row->routeId' value='$row->routeId'>Route - $row->routeId</br>";
        }
    } else {
        $routeList = "Route Not Generated";
    }
    //$json = json_encode($routeList);
    echo $routeList;
} elseif (isset($_REQUEST['viewBusStops'])) {
    $objBusStop = new StdClass();
    $objBusStop->busStopId = "";
    $objBusStop->zoneId = "";
    $objBusStop->customerno = $_SESSION['customerno'];
    $arrResult = getAllBusStops($objBusStop);
    echo json_encode($arrResult);
} elseif (isset($_REQUEST['viewBusRoutes'])) {
    $objBusRoute = new StdClass();
    $objBusRoute->busStopId = "";
    $objBusRoute->vehicleid = "";
    $arrResult = getAllBusRoutes($objBusRoute);
    echo json_encode($arrResult);
}
?>
