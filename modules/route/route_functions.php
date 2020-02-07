<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}
include_once $RELATIVE_PATH_DOTS . 'lib/system/utilities.php';
include_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
if (!isset($_SESSION)) {
    session_start();
}
function addroute($routename, $routearray, $vehiclearray, $chkDetails, $routeTat, $routeType = null) {
    $routename = GetSafeValueString($routename, "string");
    $routearray = GetSafeValueString($routearray, "string");
    $vehiclearray = GetSafeValueString($vehiclearray, "string");
    $routeType = GetSafeValueString($routeType, "string");
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routemanager->add_Route($routename, $routearray, $vehiclearray, $_SESSION['userid'], $chkDetails, $routeTat, $routeType);
}

function addroute_enh($routename, $routearray, $vehiclearray, $thourarray, $tminarray, $distancearray) {
    $routename = GetSafeValueString($routename, "string");
    $routearray = GetSafeValueString($routearray, "string");
    $vehiclearray = GetSafeValueString($vehiclearray, "string");
    $thourarray = GetSafeValueString($thourarray, "string");
    $tminarray = GetSafeValueString($tminarray, "string");
    $distancearray = GetSafeValueString($distancearray, "string");
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routemanager->add_route_enh($routename, $routearray, $vehiclearray, $thourarray, $tminarray, $distancearray, $_SESSION['userid']);
}

function editroute($routeid, $routename, $routearray, $vehiclearray, $chkDetails, $routeTat, $routeType = null) {
    $routeid = GetSafeValueString($routeid, "string");
    $routename = GetSafeValueString($routename, "string");
    $routearray = GetSafeValueString($routearray, "string");
    $vehiclearray = GetSafeValueString($vehiclearray, "string");
    $routeType = GetSafeValueString($routeType, "string");
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routemanager->edit_Route($routeid, $routename, $routearray, $vehiclearray, $_SESSION['userid'], $chkDetails, $routeTat, $routeType);
}

function editroute_enh($routeid, $routename, $routearray, $vehiclearray, $thourarray, $tminarray, $distancearray) {
    $routeid = GetSafeValueString($routeid, "string");
    $routename = GetSafeValueString($routename, "string");
    $routearray = GetSafeValueString($routearray, "string");
    $vehiclearray = GetSafeValueString($vehiclearray, "string");
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routemanager->edit_route_enh($routeid, $routename, $routearray, $vehiclearray, $thourarray, $tminarray, $distancearray, $_SESSION['userid']);
}

function getvehicles() {
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $VehicleManager->get_all_vehicles();
    return $vehicles;
}

function getvehiclesfroroute() {
    $kind = '';
    $VehicleManager = new VehicleManager($_SESSION['customerno']);
    $vehicles = $VehicleManager->get_all_vehicles_with_drivers_by_groupname($kind);
    return $vehicles;
}

function getaddedvehicles($routeid) {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $vehicles = $routemanager->get_added_vehicles($routeid);
    return $vehicles;
}

function get_route_name($routen) {
    $routen = GetSafeValueString($routen, 'string');
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routes = $routemanager->get_all_routes();
    $status = NULL;
    if (isset($routes)) {
        foreach ($routes as $thisroute) {
            if ($thisroute->routename == $routen) {
                $status = "notok";
                break;
            }
        }
        if (!isset($status)) {
            $status = "ok";
        }
    } else {
        $status = "ok";
    }
    echo $status;
}

function get_chks_for_route($routeid) {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $checkpoints = $routemanager->getchksforroute($routeid);
    return $checkpoints;
}

function get_chks_for_route_enh($routeid) {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $checkpoints = $routemanager->getchksforroute_enh($routeid);
    return $checkpoints;
}

function getchkpname($chkid) {
    $chkid = GetSafeValueString($chkid, 'long');
    $routemanager = new RouteManager($_SESSION['customerno']);
    $checkpoint = $routemanager->getchknameforroute($chkid);
    return $checkpoint;
}

function delroute($routeid) {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routemanager->DeleteRoute($routeid, $_SESSION['userid']);
    return "Del";
}

function getroutes() {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routes = $routemanager->get_all_routes();
    return $routes;
}

function getroutes_enh() {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routes = $routemanager->get_all_routes_enh();
    return $routes;
}

function uploadVehRouteMapping($all_form) {
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $objRouteManager = new RouteManager($customerno);
    $skippedArray = array();
    $skipped = 0;
    $added = 0;
    $arrRouteVehMapping = array();
    $tempVehIds = array();
    foreach ($all_form as $form) {
        $vehId = trim(GetSafeValueString($form["Vehicle ID"], "string"));
        $uploadedRouteId = trim(GetSafeValueString($form["Route ID"], "string"));
        if ($vehId != '' && is_numeric($vehId) && $uploadedRouteId != '' && is_numeric($uploadedRouteId)) {
            $isVehIdExists = 0;
            if (isset($tempVehIds) && count($tempVehIds) > 0) {
                $isVehIdExists = in_array($vehId, $tempVehIds);
            }
            $objRoute = new stdClass();
            $objRoute->routeid = $uploadedRouteId;
            $objRouteDetails = $objRouteManager->get_all_routes($objRoute);
            $routeid = $objRouteDetails[0]->routeid;
            $routename = $objRouteDetails[0]->routename;
            if (!isset($routeid) || $isVehIdExists) {
                $skipped++;
                $skippedArray[] = $form;
                continue;
            }
            $added++;
            $strChkPtIdsInRoute = "";
            $arrRouteVehMapping[$routeid]["routename"] = $routename;
            $arrRouteVehMapping[$routeid]["vehIds"][] = $vehId;
            $tempVehIds[] = $vehId;
            $arrChkPtsInRoute = $objRouteManager->getchksforroute($routeid);
            foreach ($arrChkPtsInRoute as $chkPt) {
                $strChkPtIdsInRoute = ($strChkPtIdsInRoute == "") ? $chkPt->checkpointid : $strChkPtIdsInRoute . "," . $chkPt->checkpointid;
            }
            $arrRouteVehMapping[$routeid]["chkPtIds"] = $strChkPtIdsInRoute;
        }
    }
    foreach ($arrRouteVehMapping as $routeId => $routeIdVal) {
        $strVehIdsInRoute = implode(",", $routeIdVal["vehIds"]);
        $strChkPtIdsInRoute = $routeIdVal["chkPtIds"];
        $objRouteManager->edit_route($routeId, $routeIdVal["routename"], $strChkPtIdsInRoute, $strVehIdsInRoute, $userid);
    }
    return array(
        'added' => $added,
        'skipped' => $skipped,
        'skippedData' => $skippedArray
    );
}

function getAllRoutes($vehicleId) {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $routes = $routemanager->get_routes_for_vehicleid($vehicleId);
    return $routes;
}

function getAllRoutesByCustomer($customerno) {
    $routemanager = new RouteManager($customerno);
    $routes = $routemanager->get_route_fromcustomer($customerno);
    return $routes;
}

function addFutureRoute($routearray, $vehicleid) {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $data = $routemanager->addFutureRoute($routearray, $vehicleid, $_SESSION['userid']);
    return $data;
}

function getFutureRoutes($vehicleId) {
    $routemanager = new RouteManager($_SESSION['customerno']);
    $futureRoutes = $routemanager->get_future_routes_for_vehicleid($vehicleId);
    return $futureRoutes;
}

function uploadRouteVehicleDetails($routeVehicleData) {
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $arrRouteVehMapping = array();
    $tempVehIds = array();
    $objRouteManager = new RouteManager($customerno);
    $objVehManager = new VehicleManager($customerno);
    $objChkManager = new CheckpointManager($customerno);
    $allErrors = array();
    $skippedArray = array();
    $skipped = 0;
    $added = 0;
    $errors = "";
    $rowNum = 1;
    $arrRouteWiseData = array_reduce($routeVehicleData, function ($result, $currentItem) {
        if (isset($result[$currentItem['routeName'] . '-' . $currentItem['vehicleNo']])) {
            $result[$currentItem['routeName'] . '-' . $currentItem['vehicleNo']][] = $currentItem;
        } else {
            $result[$currentItem['routeName'] . '-' . $currentItem['vehicleNo']] = array();
            $result[$currentItem['routeName'] . '-' . $currentItem['vehicleNo']][] = $currentItem;
        }
        return $result;
    });
    //prettyPrint($arrRouteWiseData);
    $arrRouteData = array();
    if (isset($arrRouteWiseData) && !empty($arrRouteWiseData)) {
        foreach ($arrRouteWiseData as $routeData) {
            $firstRouteData = reset($routeData);
            //print_r($routeData);
            $objRouteData = new stdClass();
            $objRouteData->rowNum = $rowNum;
            $objRouteData->routeName = trim($firstRouteData['routeName']);
            $objRouteData->userId = $userid;
            $objRouteData->customerNo = $customerno;
            $objRouteData->todaysDate = date(speedConstants::DEFAULT_TIMESTAMP);
            $objRouteData->routeId = 0;
            $objRouteData->vehicleId = 0;
            $objRouteData->routeDetails = array();
            if (isset($objRouteData->routeName) && $objRouteData->routeName != '') {
                $arrRoute = $objRouteManager->getRouteByName($objRouteData->routeName);
                if (isset($arrRoute) && !empty($arrRoute)) {
                    $objRouteData->routeId = $arrRoute[0]['routeId'];
                }
            }
            if (isset($firstRouteData['vehicleNo']) && $firstRouteData['vehicleNo'] != '') {
                $arrVehicle = $objVehManager->get_all_vehicles_byId(trim($firstRouteData['vehicleNo']), 1);
                if (isset($arrVehicle) && !empty($arrVehicle)) {
                    $objRouteData->vehicleId = $arrVehicle[0]->vehicleid;
                }
            }
            $i = 1;
            foreach ($routeData as $data) {
                $objChk = new stdClass();
                $objChk->sequence = $i;
                $objChk->etaTime = $data['etaTime'];
                $objChk->cName = trim($data['storeName']);
                $objChk->chkId = 0;
                if (isset($data['storeName']) && $data['storeName'] != '') {
                    $arrChkDetails = $objChkManager->getcheckpointsforcustomer(trim($data['storeName']));
                    if (isset($arrChkDetails) && !empty($arrChkDetails)) {
                        $objChk->chkId = $arrChkDetails[0]->checkpointid;
                    }
                }
                $objRouteData->routeDetails[] = $objChk;
                $i++;
            }
            //prettyPrint($objRouteData);
            $returnArray = checkRouteValidation($objRouteData);
            //prettyPrint($returnArray);die();
            if (isset($returnArray) && !empty($returnArray) && $returnArray['isValid'] == 1) {
                $currentRouteId = $objRouteManager->routeMapping($objRouteData);
                if (isset($currentRouteId) && $currentRouteId != 0) {
                    $added++;
                } else {
                    $skipped++;
                    $skippedArray[$firstRouteData['routeName']][] = $firstRouteData['routeName'];
                }
            } else {
                $skipped++;
                $allErrors[] = $returnArray["error"];
            }
            $rowNum++;

            //die();
        }

        if (isset($allErrors) && !empty($allErrors)) {
            foreach ($allErrors as $key => $value) {
                $errors .= "<br><div style='border:1px solid white; padding:5px 10px'>" . $value . "</div>";
            }
        }
    }
    return array(
        'added' => $added,
        'skipped' => $skipped,
        'errors' => $errors,
        'skippedData' => $skippedArray
    );
}

function checkRouteValidation($objRouteData) {
    $isValid = 1; // means Valid and 0 means Invalid(validation failed)
    $errors = array();
    $inValidData = "";
    $objError = new stdClass();
    if ($objRouteData->routeId == 0) {
        $inValidData .= "Route is either Missing or Incorrect in Row " . $objRouteData->rowNum . "<br />";
        $isValid = 0;
    }
    if ($objRouteData->vehicleId == 0) {
        $inValidData .= "Vehicle Number is either Missing or Incorrect in Row " . $objRouteData->rowNum . "<br />";
        $isValid = 0;
    }
    //prettyPrint($objRouteData->routeDetails);
    if (isset($objRouteData->routeDetails) && !empty($objRouteData->routeDetails)) {
        //print_r($objRouteData->routeDetails);
        foreach ($objRouteData->routeDetails as $route) {
            //print_r($route);
            if ($route->chkId == 0) {
                $inValidData .= "Checkpoint is either Missing or Incorrect in Row " . $objRouteData->rowNum . "<br />";
                $isValid = 0;
            }
        }
    }
    $errors['error'] = $inValidData;
    $errors['isValid'] = $isValid;
    return $errors;
}

function uploadVehRouteMapping1($all_form) {
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $objRouteManager = new RouteManager($customerno);
    $skippedArray = array();
    $skipped = 0;
    $added = 0;
    $arrRouteVehMapping = array();
    $tempVehIds = array();
    foreach ($all_form as $form) {
        $vehId = trim(GetSafeValueString($form["Vehicle ID"], "string"));
        $uploadedRouteId = trim(GetSafeValueString($form["Route ID"], "string"));
        if ($vehId != '' && is_numeric($vehId) && $uploadedRouteId != '' && is_numeric($uploadedRouteId)) {
            $isVehIdExists = 0;
            if (isset($tempVehIds) && count($tempVehIds) > 0) {
                $isVehIdExists = in_array($vehId, $tempVehIds);
            }
            $objRoute = new stdClass();
            $objRoute->routeid = $uploadedRouteId;
            $objRouteDetails = $objRouteManager->get_all_routes($objRoute);
            $routeid = $objRouteDetails[0]->routeid;
            $routename = $objRouteDetails[0]->routename;
            if (!isset($routeid) || $isVehIdExists) {
                $skipped++;
                $skippedArray[] = $form;
                continue;
            }
            $added++;
            $strChkPtIdsInRoute = "";
            $arrRouteVehMapping[$routeid]["routename"] = $routename;
            $arrRouteVehMapping[$routeid]["vehIds"][] = $vehId;
            $tempVehIds[] = $vehId;
            $arrChkPtsInRoute = $objRouteManager->getchksforroute($routeid);
            foreach ($arrChkPtsInRoute as $chkPt) {
                $strChkPtIdsInRoute = ($strChkPtIdsInRoute == "") ? $chkPt->checkpointid : $strChkPtIdsInRoute . "," . $chkPt->checkpointid;
            }
            $arrRouteVehMapping[$routeid]["chkPtIds"] = $strChkPtIdsInRoute;
        }
    }
    foreach ($arrRouteVehMapping as $routeId => $routeIdVal) {
        $strVehIdsInRoute = implode(",", $routeIdVal["vehIds"]);
        $strChkPtIdsInRoute = $routeIdVal["chkPtIds"];
        $objRouteManager->edit_route($routeId, $routeIdVal["routename"], $strChkPtIdsInRoute, $strVehIdsInRoute, $userid);
    }
    return array(
        'added' => $added,
        'skipped' => $skipped,
        'skippedData' => $skippedArray
    );
}

?>