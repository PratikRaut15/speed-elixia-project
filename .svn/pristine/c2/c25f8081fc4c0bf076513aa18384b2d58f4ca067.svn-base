<?php

/**
 * Functions of TMS - module
 */
error_reporting(0);
ini_set('display_errors', 'On');

// <editor-fold defaultstate="collapsed" desc="INCLUDE FILES">
require_once '../../config.inc.php';
require_once '../../lib/system/Log.php';
require_once '../../lib/system/Sanitise.php';
require_once '../../lib/system/utilities.php';
require_once '../../lib/system/DatabaseTMSManager.php';
require_once 'class/TMSManager.php';
require_once 'class/IndentAlgo.php';
require_once 'class/model/Depot.php';
require_once 'class/model/Zone.php';
require_once 'class/model/Location.php';
require_once 'class/model/Factory.php';
require_once 'class/model/VehicleType.php';
require_once 'class/model/Transporter.php';
require_once 'class/model/Vehicle.php';
require_once 'class/model/TransporterShare.php';
require_once 'class/model/TransporterActualShare.php';
require_once 'class/model/FactoryDeliveryDetails.php';
require_once 'class/model/FactoryProductionDetails.php';
require_once 'class/model/Sku.php';
require_once 'class/model/RouteMaster.php';
require_once 'class/model/RouteCheckpoint.php';
require_once 'class/model/ProposedIndentSKUMapping.php';
require_once 'class/model/ProposedIndentTransporterMapping.php';
require_once 'class/model/ProposedIndent.php';
require_once 'class/model/Indent.php';
require_once 'class/model/IndentSkuMapping.php';
require_once 'class/constants.php';
require_once '../../lib/comman_function/reports_func.php';
// </editor-fold>
if (!isset($_SESSION)) {
    session_start();
}
if (!class_exists('Object')) {

    class Object
    {

    }

}
define("DATEFORMAT_YMD", "Y-m-d");
define("DATEFORMAT_MONTH", "M");
define("DATEFORMAT_YEAR", "Y");
define("DATEFORMAT_DAYS", "%a");
$objDate = new DateTime();
$action = retval_issetor($_GET['pg']);
$confirmMsg = "Are you sure you want to delete?";
$alert = "onclick='return confirm(\"" . $confirmMsg . "\");'";
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - ZONE">
if ($action == 'view-zone') {
    $zones = array();
    $objZone = new Zone();
    $objZone->customerno = $_SESSION["customerno"];
    $objZone->zoneid = '';
    $zonesarray = array();
    $zonesarray = getZones($objZone);
    if (isset($zonesarray)) {
        foreach ($zonesarray as $zone) {
            $zonearr['zonename'] = $zone['zonename'];
            $zonearr['edit'] = "<a href='tms.php?pg=edit-zone&eid=" . $zone['zoneid'] . "'>" . constants::editimage . "</a>";
            $zonearr['delete'] = "<a href='action.php?action=del-zone&did=" . $zone['zoneid'] . "' " . $alert . ">" . constants::deleteimage . "</a>";
            $zones[] = $zonearr;
        }
    }
}
if ($action == 'edit-zone') {
    $eid = $_REQUEST['eid'];
    $zones = array();
    $objZone = new Zone();
    $objZone->customerno = $_SESSION["customerno"];
    $objZone->zoneid = $eid;
    $zones = getZones($objZone);
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - LOCATION">
if ($action == 'view-location') {
    $locations = array();
    $objLocation = new Location();
    $objLocation->customerno = $_SESSION["customerno"];
    $objLocation->locationid = '';
    $locarray = getLocations($objLocation);
    if (isset($locarray)) {
        foreach ($locarray as $location) {
            $loc['locationid'] = $location['locationid'];
            $loc['locationname'] = $location['locationname'];
            $loc['edit'] = "<a href='tms.php?pg=edit-location&eid=" . $location['locationid'] . "'>" . constants::editimage . "</a>";
            $loc['delete'] = "<a href='action.php?action=del-location&did=" . $location['locationid'] . "' " . $alert . ">" . constants::deleteimage . "</a>";
            $locations[] = $loc;
        }
    }
}
if ($action == 'edit-location') {
    $eid = $_REQUEST['eid'];
    $objLocation = new Location();
    $objLocation->customerno = $_SESSION["customerno"];
    $objLocation->locationid = $eid;
    $location = getLocations($objLocation);
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - DEPOT">
if ($action == 'view-depot') {
    $depots = array();
    $objDepot = new Depot();
    $objDepot->customerno = $_SESSION["customerno"];
    $objDepot->locationid = '';
    $objDepot->zoneid = '';
    $depotsarray = array();
    $depotsarray = getDepots($objDepot);
    if (isset($depotsarray)) {
        foreach ($depotsarray as $row) {
            $depot['depotid'] = $row['depotid'];
            $depot['depotcode'] = $row['depotcode'];
            $depot['depotname'] = $row['depotname'];
            $depot['zonename'] = $row['zonename'];
            $depot['edit'] = "<a href='tms.php?pg=edit-depot&eid=" . $row['depotid'] . "'>" . constants::editimage . "</a>";
            $depot['delete'] = "<a href='action.php?action=del-depot&did=" . $row['depotid'] . "' " . $alert . " >" . constants::deleteimage . "</a>";
            $depots[] = $depot;
        }
    }
}
if ($action == 'edit-depot') {
    $eid = $_REQUEST['eid'];
    $objDepot = new Depot();
    $objDepot->customerno = $_SESSION["customerno"];
    $objDepot->depotid = $eid;
    $depots = getDepots($objDepot);
    $multidepots = getMappedDepots($objDepot);
    $mappeddepots = array();
    $mappeddepotidss = array();
    if (isset($multidepots) && !empty($multidepots)) {
        $mappeddepotidsstring = '';
        $mappeddepotidsstring .= $multidepots[0]['depotmappingid'];
        $depotids = explode(",", $mappeddepotidsstring);
        foreach ($depotids as $depot) {
            if ($depot != '') {
                $objMappedDepot = new Depot();
                $objMappedDepot->customerno = $_SESSION["customerno"];
                $objMappedDepot->depotid = $depot;
                $depotsarr = getDepots($objMappedDepot);
                //print_r($depotsarr);
                $depotarray['depotid'] = $depotsarr[0]['depotid'];
                $depotarray['depotname'] = $depotsarr[0]['depotname'];
                $depotarray['factoryname'] = $multidepots[0]['factoryname'];
                $depotarray['factoryid'] = $multidepots[0]['factoryid'];
                $mappeddepots[] = $depotarray;
            }
        }
    }
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - FACTORY DELIVERY">
if ($action == 'view-factory-delivery') {
    $factoryid = '';
    if (isset($_SESSION['factoryid'])) {
        $factoryid = $_SESSION['factoryid'];
    }
    $depotid = '';
    if (isset($_SESSION['depotid'])) {
        $depotid = $_SESSION['depotid'];
    }
    $factory_delivery = array();
    $objFact = new FactoryDeliveryDetails();
    $objFact->customerno = $_SESSION["customerno"];
    $objFact->fdid = '';
    $objFact->factoryid = $factoryid;
    $objFact->depotid = $depotid;
    $factoryarray = getFactoryDelivery($objFact);
    if (isset($factoryarray)) {
        foreach ($factoryarray as $factory) {
            $factoryarr['fdid'] = $factory['fdid'];
            $factoryarr['factoryname'] = $factory['factoryname'];
            $factoryarr['depotname'] = $factory['depotname'];
            $factoryarr['skucode'] = $factory['skucode'];
            $factoryarr['sku_description'] = $factory['sku_description'];
            $factoryarr['date_required'] = date('d-m-Y', strtotime($factory['date_required']));
            $factoryarr['netWeight'] = $factory['netWeight'];
            $factoryarr['grossWeight'] = $factory['grossWeight'];
            $factoryarr['created_on'] = date(speedConstants::DEFAULT_DATETIME, strtotime($factory['created_on']));
            $factoryarr['edit'] = "<a href='tms.php?pg=edit-factory-delivery&eid=" . $factory['fdid'] . "'>" . constants::editimage . "</a>";
            $factoryarr['delete'] = "<a href='action.php?action=del-factory-delivery&did=" . $factory['fdid'] . "' " . $alert . ">" . constants::deleteimage . "</a>";
            $factory_delivery[] = $factoryarr;
        }
    }
}
if ($action == 'edit-factory-delivery') {
    $eid = $_REQUEST['eid'];
    $objFact = new FactoryDeliveryDetails();
    $objFact->customerno = $_SESSION["customerno"];
    $objFact->fdid = $eid;
    $factory_delivery = getFactoryDelivery($objFact);
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - FACTORY / PLANTS">
if ($action == 'view-plant') {
    $plants = array();
    $objFactoty = new Factory();
    $objFactoty->customerno = $_SESSION["customerno"];
    $factoryarray = getFactory($objFactoty);
    if (isset($factoryarray)) {
        foreach ($factoryarray as $factory) {
            $factoryarr['factoryid'] = $factory['factoryid'];
            $factoryarr['factorycode'] = $factory['factorycode'];
            $factoryarr['factoryname'] = $factory['factoryname'];
            $factoryarr['zonename'] = $factory['zonename'];
            $factoryarr['edit'] = "<a href='tms.php?pg=edit-plant&eid=" . $factory['factoryid'] . "'>" . constants::editimage . "</a>";
            $factoryarr['delete'] = "<a href='action.php?action=del-plant&did=" . $factory['factoryid'] . "' " . $alert . ">" . constants::deleteimage . "</a>";
            $plants[] = $factoryarr;
        }
    }
}
if ($action == 'edit-plant') {
    $eid = $_REQUEST['eid'];
    $objFactoty = new Factory();
    $objFactoty->customerno = $_SESSION["customerno"];
    $objFactoty->factoryid = $eid;
    $plant = getFactory($objFactoty);
    //print_r($plant);
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - VEHICLE TYPE">
if ($action == 'view-vehicle-type') {
    $transporters = array();
    $objVehicle = new VehicleType();
    $objVehicle->customerno = $_SESSION["customerno"];
    $transporterarray = getVehicletypes($objVehicle);
    if (isset($transporterarray)) {
        foreach ($transporterarray as $transporter) {
            $transporterarr['vehicletypeid'] = $transporter['vehicletypeid'];
            $transporterarr['vehiclecode'] = $transporter['vehiclecode'];
            $transporterarr['vehicledescription'] = $transporter['vehicledescription'];
            $transporterarr['type'] = $transporter['type'];
            $transporterarr['volume'] = $transporter['volume'];
            $transporterarr['weight'] = $transporter['weight'];
            $transporterarr['edit'] = "<a href='tms.php?pg=edit-vehicle-type&eid=" . $transporter['vehicletypeid'] . "' >" . constants::editimage . "</a>";
            $transporterarr['delete'] = "<a href='action.php?action=del-vehicle-type&did=" . $transporter['vehicletypeid'] . "' " . $alert . " >" . constants::deleteimage . "</a>";
            $transporters[] = $transporterarr;
        }
    }
}
if ($action == 'edit-vehicle-type') {
    $eid = $_REQUEST['eid'];
    $objVehicle = new VehicleType();
    $objVehicle->vehicletypeid = $eid;
    $objVehicle->customerno = $_SESSION['customerno'];
    $vehicletype = getVehicletypes($objVehicle);
    // print_r($vehicletype);
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - TRANSPORTER">
if ($action == 'view-transporter') {
    $transporters = array();
    $objTransporter = new Transporter();
    $objTransporter->customerno = $_SESSION["customerno"];
    $transporterarray = getTransporter($objTransporter);
    //print_r($objTransporter);
    if (isset($transporterarray)) {
        foreach ($transporterarray as $transporter) {
            $transporterarr['transporterid'] = $transporter['transporterid'];
            $transporterarr['transportername'] = $transporter['transportername'];
            $transporterarr['transportercode'] = $transporter['transportercode'];
            $transporterarr['transportermail'] = $transporter['transportermail'];
            $transporterarr['transportermobileno'] = $transporter['transportermobileno'];
            $transporterarr['edit'] = "<a href='tms.php?pg=edit-transporter&eid=" . $transporter['transporterid'] . "'>" . constants::editimage . "</a>";
            $transporterarr['delete'] = "<a href='action.php?action=del-transporter&did=" . $transporter['transporterid'] . "' " . $alert . ">" . constants::deleteimage . "</a>";
            $transporters[] = $transporterarr;
        }
    }
    $vehtypelist = '';
    $objVehicle = new VehicleType();
    $objVehicle->customerno = $_SESSION["customerno"];
    $vehtype = getVehicletypes($objVehicle);
    foreach ($vehtype as $type) {
        $vehtypelist .= '<option value="' . $type['vehicletypeid'] . '">' . $type['vehicledescription'] . ' - ' . $type['vehiclecode'] . '</option>';
    }
}
if ($action == 'edit-transporter') {
    $eid = $_REQUEST['eid'];
    $objTransporter = new Transporter();
    $objTransporter->customerno = $_SESSION["customerno"];
    $objTransporter->transporterid = $eid;
    $transporter = getTransporter($objTransporter);
    $vehtypelist = '';
    $objVehicle = new VehicleType();
    $objVehicle->customerno = $_SESSION["customerno"];
    $vehtype = getVehicletypes($objVehicle);
    foreach ($vehtype as $type) {
        $vehtypelist .= '<option value="' . $type['vehicletypeid'] . '">' . $type['vehicledescription'] . ' - ' . $type['vehiclecode'] . '</option>';
    }
    $objVehicle->transporterid = $eid;
    $arrVehicleMapping = getVehtypetransporterMapping($objVehicle);
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - VEHICLE ">
if ($action == 'view-vehicle') {
    $vehicles = array();
    $objVehicle = new Vehicle();
    $objVehicle->customerno = $_SESSION["customerno"];
    $vehiclerarray = getVehicles($objVehicle);
    if (isset($vehiclerarray)) {
        foreach ($vehiclerarray as $vehicle) {
            $vehiclearr['vehicleid'] = $vehicle['vehicleid'];
            $vehiclearr['vehiclecode'] = $vehicle['vehiclecode'];
            $vehiclearr['vehicleno'] = $vehicle['vehicleno'];
            $vehiclearr['transportername'] = $vehicle['transportername'];
            $vehiclearr['edit'] = "<a href='tms.php?pg=edit-vehicle&eid=" . $vehicle['vehicleid'] . "' >" . constants::editimage . "</a>";
            $vehiclearr['delete'] = "<a href='action.php?action=del-vehicle&did=" . $vehicle['vehicleid'] . "' " . $alert . " >" . constants::deleteimage . "</a>";
            $vehicles[] = $vehiclearr;
        }
    }
}
if ($action == 'edit-vehicle') {
    $eid = $_REQUEST['eid'];
    $objVehicle = new Vehicle();
    $objVehicle->vehicleid = $eid;
    $objVehicle->customerno = $_SESSION['customerno'];
    $vehicle = getVehicles($objVehicle);
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - TRANSPORTER SHARE ">
if ($action == 'view-share') {
    $shares = array();
    $objTransporterShare = new TransporterShare();
    $objTransporterShare->customerno = $_SESSION["customerno"];
    $sharearray = getTransportershare($objTransporterShare);
    if (isset($sharearray)) {
        foreach ($sharearray as $share) {
            $sharearr['transportershareid'] = $share['transportershareid'];
            $sharearr['transportername'] = $share['transportername'];
            $sharearr['factoryid'] = $share['factoryid'];
            $sharearr['factoryname'] = $share['factoryname'];
            $sharearr['sharepercent'] = $share['sharepercent'];
            $sharearr['transportername'] = $share['transportername'];
            $sharearr['zonename'] = $share['zonename'];
            $sharearr['edit'] = "<a href='tms.php?pg=edit-share&eid=" . $share['transportershareid'] . "' >" . constants::editimage . "</a>";
            $sharearr['delete'] = "<a href='action.php?action=del-share&did=" . $share['transportershareid'] . "' " . $alert . " >" . constants::deleteimage . "</a>";
            $shares[] = $sharearr;
        }
    }
}
if ($action == 'edit-share') {
    $eid = $_REQUEST['eid'];
    $objTransporterShare = new TransporterShare();
    $objTransporterShare->customerno = $_SESSION["customerno"];
    $objTransporterShare->transportershareid = $eid;
    $share = getTransportershare($objTransporterShare);
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - ROUTE MASTER">
if ($action == 'view-route') {
    $routes = array();
    $objRouteMaster = new RouteMaster();
    $objRouteMaster->customerno = $_SESSION["customerno"];
    $objRouteMaster->routemasterid = '';
    $routearray = getRoutemaster($objRouteMaster);
    if (isset($routearray)) {
        foreach ($routearray as $route) {
            $routearr['routemasterid'] = $route['routemasterid'];
            $routearr['routename'] = $route['routename'];
            $routearr['routedescription'] = $route['routedescription'];
            $routearr['factoryname'] = $route['factoryname'];
            $routearr['depotname'] = $route['depotname'];
            $routearr['distance'] = $route['distance'];
            $routearr['travellingtime'] = $route['travellingtime'];
            $routearr['edit'] = "<a href='tms.php?pg=edit-route&eid=" . $route['routemasterid'] . "' >" . constants::editimage . "</a>";
            $routearr['delete'] = "<a href='action.php?action=del-route&did=" . $route['routemasterid'] . "' " . $alert . " >" . constants::deleteimage . "</a>";
            $routes[] = $routearr;
        }
    }
}
if ($action == 'edit-route') {
    $eid = $_REQUEST['eid'];
    $objRouteMaster = new RouteMaster();
    $objRouteMaster->customerno = $_SESSION["customerno"];
    $objRouteMaster->routemasterid = $eid;
    $routes = getRoutemaster($objRouteMaster);
    //print_r($routes);
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - ROUTE CHECKPOINT MANAGER">
if ($action == 'view-routemanager') {
    $routechk = array();
    $objRouteChk = new RouteCheckpoint();
    $objRouteChk->customerno = $_SESSION["customerno"];
    $objRouteChk->routecheckpointid = '';
    $routearray = getRoutecheckpoint($objRouteChk);
    if (isset($routearray)) {
        foreach ($routearray as $route) {
            $routearr['routechkpointid'] = $route['routecheckpointid'];
            $routearr['routename'] = $route['routename'];
            $routearr['factoryname'] = $route['factoryname'];
            $routearr['locationname'] = $route['locationname'];
            $routearr['distance'] = $route['distance'];
            $routearr['edit'] = "<a href='tms.php?pg=edit-routemanager&eid=" . $route['routecheckpointid'] . "' >" . constants::editimage . "</a>";
            $routearr['delete'] = "<a href='action.php?action=del-routemanager&did=" . $route['routecheckpointid'] . "' " . $alert . " >" . constants::deleteimage . "</a>";
            $routechk[] = $routearr;
        }
    }
}
if ($action == 'edit-routemanager') {
    $eid = $_REQUEST['eid'];
    $objRouteChk = new RouteCheckpoint();
    $objRouteChk->customerno = $_SESSION["customerno"];
    $objRouteChk->routecheckpointid = $eid;
    $routechekpoints = getRoutecheckpoint($objRouteChk);
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - SKU">
if ($action == 'view-sku') {
    $skus = array();
    $objSku = new Sku();
    $objSku->customerno = $_SESSION["customerno"];
    $objSku->skuid = '';
    $skuarray = getSku($objSku);
    if (isset($skuarray)) {
        foreach ($skuarray as $sku) {
            $skuarr['skuid'] = $sku['skuid'];
            $skuarr['skucode'] = $sku['skucode'];
            $skuarr['sku_description'] = $sku['sku_description'];
            $skuarr['type'] = $sku['type'];
            $skuarr['volume'] = $sku['volume'];
            $skuarr['weight'] = $sku['weight'];
            $skuarr['netgross'] = $sku['netgross'];
            $skuarr['edit'] = "<a href='tms.php?pg=edit-sku&eid=" . $sku['skuid'] . "' >" . constants::editimage . "</a>";
            $skuarr['delete'] = "<a href='action.php?action=del-sku&did=" . $sku['skuid'] . "' " . $alert . " >" . constants::deleteimage . "</a>";
            $skus[] = $skuarr;
        }
    }
}
if ($action == 'edit-sku') {
    $eid = $_REQUEST['eid'];
    $objSku = new Sku();
    $objSku->customerno = $_SESSION["customerno"];
    $objSku->skuid = $eid;
    $skus = getSku($objSku);
    //print_r($skus);
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - FACTORY PRODUCTION">
if ($action == 'view-factory-production') {
    $factory_production = array();
    $objFactProduction = new FactoryProductionDetails();
    $objFactProduction->customerno = $_SESSION["customerno"];
    $objFactProduction->fpid = '';
    $factoryarray = getFactoryProduction($objFactProduction);
    foreach ($factoryarray as $factory) {
        $factoryarr['fpid'] = $factory['fpid'];
        $factoryarr['factoryname'] = $factory['factoryname'];
        $factoryarr['skucode'] = $factory['skucode'];
        $factoryarr['weight'] = $factory['weight'];
        $factoryarr['edit'] = "<a href='tms.php?pg=edit-factory-production&eid=" . $factory['fpid'] . "'><img src='../../images/edit_black.png'/></a>";
        $factoryarr['delete'] = "<a href='action.php?action=del-factory-production&did=" . $factory['fpid'] . "' " . $alert . "><img src='../../images/delete1.png'/></a>";
        $factory_production[] = $factoryarr;
    }
}
if ($action == 'edit-factory-production') {
    $eid = $_REQUEST['eid'];
    $factory_production = array();
    $objFactProduction = new FactoryProductionDetails();
    $objFactProduction->customerno = $_SESSION["customerno"];
    $objFactProduction->fpid = $eid;
    $factoryarray = getFactoryProduction($objFactProduction);
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - PROPOSED INDENT">
if ($action == 'view-proposed-indent') {
    $factoryid = '';
    $transporterid = '';
    if (isset($_SESSION['factoryid']) && $_SESSION['factoryid'] != '') {
        $factoryid = $_SESSION['factoryid'];
    }
    if (isset($_SESSION['transporterid']) && $_SESSION['transporterid'] != '') {
        $transporterid = $_SESSION['transporterid'];
    }
    $startdate = $objDate->modify('first day of this month')->format(DATEFORMAT_YMD);
    $objDate->sub(new DateInterval('P1W'));
    $startdate = $objDate->format(DATEFORMAT_YMD);
    $objDate = new DateTime();
    $enddate = $objDate->modify('last day of this month')->format(DATEFORMAT_YMD);
    $objDate->add(new DateInterval('P1W'));
    $enddate = $objDate->format(DATEFORMAT_YMD);
    $proposed_indents = array();
    $objProposedIndent = new ProposedIndent();
    $objProposedIndent->customerno = $_SESSION["customerno"];
    $objProposedIndent->proposedindentid = '';
    $objProposedIndent->factoryid = $factoryid;
    $objProposedIndent->transporterid = $transporterid;
    $objProposedIndent->startdate = $startdate;
    $objProposedIndent->enddate = $enddate;
    $proposedIndentArray = getProposedIndent($objProposedIndent);
    foreach ($proposedIndentArray as $proposedIndent) {
        $proposedIndentarr['proposedindentid'] = $proposedIndent['proposedindentid'];
        $proposedIndentarr['factoryname'] = $proposedIndent['factoryname'];
        $proposedIndentarr['depotname'] = $proposedIndent['depotname'];
        $proposedIndentarr['zonename'] = $proposedIndent['zonename'];
        $proposedIndentarr['transportername'] = $proposedIndent['transportername'];
        //$proposedIndentarr['vehiclecode'] = $proposedIndent['vehiclecode'];
        $proposedIndentarr['proposedvehiclecode'] = $proposedIndent['vehiclecode'] . ' - ' . $proposedIndent['proposedvehicledescription'];
        if ($proposedIndent['actualvehiclecode'] != '') {
            $proposedIndentarr['actualvehiclecode'] = $proposedIndent['actualvehiclecode'] . ' - ' . $proposedIndent['actualvehicledescription'];
        } else {
            $proposedIndentarr['actualvehiclecode'] = '';
        }
        $proposedIndentarr['date_required'] = date('d-m-Y', strtotime($proposedIndent['date_required']));
        $proposedIndentarr['created_on'] = date(speedConstants::DEFAULT_DATETIME, strtotime($proposedIndent['created_on']));
        $proposedIndentarr['total_weight'] = $proposedIndent['total_weight'];
        $proposedIndentarr['total_volume'] = $proposedIndent['total_volume'];
        $proposedIndentarr['piremark'] = $proposedIndent['piremark'];
        $proposedIndentarr['transporterremarks'] = $proposedIndent['transporterremarks'];
        if ($proposedIndent['isAccepted'] == 1 && $proposedIndent['isApproved'] != 1) {
            $proposedIndentarr['status'] = 'Indent Confirmed By Transporter';
        } else if ($proposedIndent['isAccepted'] == -1 && $proposedIndent['isAutoRejected'] == 0) {
            $proposedIndentarr['status'] = 'Indent Rejected By Transporter';
        } else if ($proposedIndent['isAccepted'] == -1 && $proposedIndent['isAutoRejected'] == 1) {
            $proposedIndentarr['status'] = 'Indent Not Confirmed. Auto Rejected By System';
        } else if ($proposedIndent['isAccepted'] == 0 && $proposedIndent['isAutoRejected'] == 1) {
            $proposedIndentarr['status'] = 'Indent Expired';
        } else {
            $proposedIndentarr['status'] = 'Awaiting Response';
        }
        if ($proposedIndent['isApproved'] == 1 && $proposedIndent['isAccepted'] == 1) {
            $proposedIndentarr['status'] = 'Indent Confirmed By Transporter';
        } else if ($proposedIndent['isApproved'] == -1) {
            $proposedIndentarr['status'] = 'Indent Rejected By Transporter';
        }
        /* Status For Factory Official Approval */
        $proposedIndentarr['shipmentno'] = '';
        $proposedIndentarr['remark'] = '';
        $proposedIndentarr['factorystatus'] = '';
        if ($proposedIndent['loadstatus'] == 0 && $proposedIndent['isAccepted'] == 1) {
            $proposedIndentarr['factorystatus'] = 'Awaiting Response';
        } else if ($proposedIndent['loadstatus'] == '1' && $proposedIndent['isAccepted'] == 1) {
            $proposedIndentarr['factorystatus'] = 'Loaded';
            $proposedIndentarr['remark'] = $proposedIndent['remarks'];
            $proposedIndentarr['shipmentno'] = $proposedIndent['shipmentno'];
        } else if ($proposedIndent['loadstatus'] == '-1' && $proposedIndent['isAccepted'] == 1) {
            $proposedIndentarr['factorystatus'] = 'Rejected';
            $proposedIndentarr['remark'] = $proposedIndent['remarks'];
        } else if ($proposedIndent['loadstatus'] == '2' && $proposedIndent['isAccepted'] == 1) {
            $proposedIndentarr['factorystatus'] = 'Vehicle Not Placed';
            $proposedIndentarr['remark'] = $proposedIndent['remarks'];
        } else if ($proposedIndent['loadstatus'] == '3' && $proposedIndent['isAccepted'] == 1) {
            $proposedIndentarr['factorystatus'] = 'Other';
            $proposedIndentarr['remark'] = $proposedIndent['remarks'];
        }
        $proposedIndentarr['vehicleno'] = $proposedIndent['vehicleno'];
        if ($proposedIndent['drivermobileno'] == 'NULL') {
            $proposedIndentarr['driverno'] = '';
        } else {
            $proposedIndentarr['driverno'] = $proposedIndent['drivermobileno'];
        }
        $proposedIndentarr['edit'] = "<a href='tms.php?pg=view-proposedindent-sku&eid=" . $proposedIndent['proposedindentid'] . "'>" . constants::viewimage . "</a>";
        //$proposedIndentarr['delete'] = "<a href='action.php?action=del-factory-production&did=" . $proposedIndent['proposedindentid'] . "' " . $alert . "><img src='../../images/delete1.png'/></a>";
        $proposedIndentarr['delete'] = '';
        $proposed_indents[] = $proposedIndentarr;
    }
}
if ($action == 'view-proposedindent-sku') {
    $eid = $_REQUEST['eid'];
    $proposed_indents_sku = array();
    $objProposedIndent = new ProposedIndent();
    $objProposedIndent->customerno = $_SESSION["customerno"];
    $objProposedIndent->proposedindentid = $eid;
    $proposedIndentArray = getProposedIndentSkuMapping($objProposedIndent);
    foreach ($proposedIndentArray as $proposedIndent) {
        $proposedIndentarr['proposedindentid'] = $proposedIndent['proposedindentid'];
        $proposedIndentarr['skuid'] = $proposedIndent['skuid'];
        $proposedIndentarr['skucode'] = $proposedIndent['skucode'];
        $proposedIndentarr['sku_description'] = $proposedIndent['sku_description'];
        $proposedIndentarr['no_of_units'] = $proposedIndent['no_of_units'];
        $proposedIndentarr['weight'] = $proposedIndent['weight'];
        $proposedIndentarr['volume'] = $proposedIndent['volume'];
        //$proposedIndentarr['edit'] = "<a href='tms.php?pg=view-proposedindent-sku&eid=" . $proposedIndent['proposedindentid'] . "'><img src='../../images/edit.png'/></a>";
        //$proposedIndentarr['delete'] = "<a href='action.php?action=del-factory-production&did=" . $proposedIndent['proposedindentid'] . "' " . $alert . "><img src='../../images/delete1.png'/></a>";
        //$proposedIndentarr['delete']= '';
        $proposed_indents_sku[] = $proposedIndentarr;
    }
}
if ($action == 'view-proposed-transporter') {
    $proposed_indents_sku = array();
    $startdate = $objDate->modify('first day of this month')->format(DATEFORMAT_YMD);
    $objDate->sub(new DateInterval('P1W'));
    $startdate = $objDate->format(DATEFORMAT_YMD);
    $objDate = new DateTime();
    $enddate = $objDate->modify('last day of this month')->format(DATEFORMAT_YMD);
    $objDate->add(new DateInterval('P1W'));
    $enddate = $objDate->format(DATEFORMAT_YMD);
    $objProposedIndent = new ProposedIndentTransporterMapping();
    $objProposedIndent->customerno = $_SESSION["customerno"];
    $objProposedIndent->proposed_transporterid = isset($_SESSION["transporterid"]) ? $_SESSION["transporterid"] : 0;
    $objProposedIndent->startdate = $startdate;
    $objProposedIndent->enddate = $enddate;
    $proposedIndentArray = getTransporterProposedIndent($objProposedIndent);
    foreach ($proposedIndentArray as $proposedIndent) {
        $proposedIndentarr['proposedindentid'] = $proposedIndent['proposedindentid'];
        $proposedIndentarr['proposed_transporterid'] = $proposedIndent['proposed_transporterid'];
        $proposedIndentarr['transportername'] = $proposedIndent['transportername'];
        $proposedIndentarr['depotname'] = $proposedIndent['depotname'];
        $proposedIndentarr['factoryname'] = $proposedIndent['factoryname'];
        $proposedIndentarr['proposedvehiclecode'] = $proposedIndent['proposedvehiclecode'] . ' - ' . $proposedIndent['proposedvehicledescription'];
        $proposedIndentarr['actualvehiclecode'] = '';
        if ($proposedIndent['actualvehiclecode'] != '') {
            $proposedIndentarr['actualvehiclecode'] = $proposedIndent['actualvehiclecode'] . ' - ' . $proposedIndent['actualvehicledescription'];
        }
        $proposedIndentarr['daterequired'] = date('d-m-Y', strtotime($proposedIndent['date_required']));
        $proposedIndentarr['created_on'] = date(speedConstants::DEFAULT_DATETIME, strtotime($proposedIndent['created_on']));
        $proposedIndentarr['vehicleno'] = $proposedIndent['vehicleno'];
        $proposedIndentarr['drivermobileno'] = $proposedIndent['drivermobileno'];
        $proposedIndentarr['isAccepted'] = $proposedIndent['isAccepted'];
        $proposedIndentarr['edit'] = '';
        $proposedIndentarr['delete'] = '';
        $proposedIndentarr['view'] = "<a href='tms.php?pg=view-transporterindent-sku&eid=" . $proposedIndent['proposedindentid'] . "'>" . constants::viewimage . "</a>";
        if ($proposedIndent['isAccepted'] == 0 && $proposedIndent['isAutoRejected'] == 0) {
            $proposedIndentarr['edit'] = "<a href='tms.php?pg=edit-transporterindent-sku&eid=" . $proposedIndent['proposedindentid'] . "&tid=" . $proposedIndent['proposed_transporterid'] . "&pid=" . $proposedIndent['pitmappingid'] . "' >" . constants::editimage . "</a>";
        }
        if ($proposedIndent['isAccepted'] == 0 && $proposedIndent['isAutoRejected'] == 1) {
            $proposedIndentarr['edit'] = "";
        }
        if ($_SESSION['roleid'] == 5 || $_SESSION['roleid'] == 6 || $_SESSION['roleid'] == 1) {
            $proposedIndentarr['delete'] = "<a href='action.php?action=del-proposedindent&eid=" . $proposedIndent['proposedindentid'] . "' " . $alert . "  >" . constants::deleteimage . "</a>";
        }
        $proposed_indents_sku[] = $proposedIndentarr;
    }
}
if ($action == 'edit-transporterindent-sku') {
    $eid = $_REQUEST['eid'];
    $tid = $_REQUEST['tid'];
    $pid = $_REQUEST['pid'];
    $proposed_indents_sku = array();
    $objProposedIndent = new ProposedIndentTransporterMapping();
    $objProposedIndent->customerno = $_SESSION["customerno"];
    $objProposedIndent->proposedindentid = $eid;
    $objProposedIndent->proposed_transporterid = $tid;
    $objProposedIndent->pitmappingid = $pid;
    $proposedIndentArray = getTransporterProposedIndent($objProposedIndent);
}
if ($action == 'view-transporterindent-sku') {
    $eid = $_REQUEST['eid'];
    $proposed_indents_sku = array();
    $objProposedIndent = new ProposedIndent();
    $objProposedIndent->customerno = $_SESSION["customerno"];
    $objProposedIndent->proposedindentid = $eid;
    $proposedIndentArray = getProposedIndentSkuMapping($objProposedIndent);
    foreach ($proposedIndentArray as $proposedIndent) {
        $proposedIndentarr['proposedindentid'] = $proposedIndent['proposedindentid'];
        $proposedIndentarr['skuid'] = $proposedIndent['skuid'];
        $proposedIndentarr['skucode'] = $proposedIndent['skucode'];
        $proposedIndentarr['sku_description'] = $proposedIndent['sku_description'];
        $proposedIndentarr['no_of_units'] = $proposedIndent['no_of_units'];
        $proposedIndentarr['weight'] = $proposedIndent['weight'];
        $proposedIndentarr['volume'] = $proposedIndent['volume'];
        //$proposedIndentarr['edit'] = "<a href='tms.php?pg=view-proposedindent-sku&eid=" . $proposedIndent['proposedindentid'] . "'><img src='../../images/edit.png'/></a>";
        //$proposedIndentarr['delete'] = "<a href='action.php?action=del-factory-production&did=" . $proposedIndent['proposedindentid'] . "' " . $alert . "><img src='../../images/delete1.png'/></a>";
        //$proposedIndentarr['delete']= '';
        $proposed_indents_sku[] = $proposedIndentarr;
    }
}
if ($action == 'edit-factory-production') {
    $eid = $_REQUEST['eid'];
    $factory_production = array();
    $objFactProduction = new FactoryProductionDetails();
    $objFactProduction->customerno = $_SESSION["customerno"];
    $objFactProduction->fpid = $eid;
    $factoryarray = getFactoryProduction($objFactProduction);
}
if ($action == 'view-indent') {
    $indents = array();
    $objIndent = new Indent();
    $factoryid = '';
    if (isset($_SESSION['factoryid']) && $_SESSION['factoryid'] != '') {
        $factoryid = $_SESSION['factoryid'];
    }
    $startdate = $objDate->modify('first day of this month')->format(DATEFORMAT_YMD);
    $objDate->sub(new DateInterval('P1W'));
    $startdate = $objDate->format(DATEFORMAT_YMD);
    $objDate = new DateTime();
    $enddate = $objDate->modify('last day of this month')->format(DATEFORMAT_YMD);
    $objDate->add(new DateInterval('P1W'));
    $enddate = $objDate->format(DATEFORMAT_YMD);
    $objIndent->customerno = $_SESSION["customerno"];
    $objIndent->indentid = '';
    $objIndent->factoryid = $factoryid;
    $objIndent->startdate = $startdate;
    $objIndent->enddate = $enddate;
    $indentarray = array();
    $indentarray = getIndents($objIndent);
    //print_r($indentarray);
    if (isset($indentarray)) {
        foreach ($indentarray as $indent) {
            $indentarr['indentid'] = $indent['indentid'];
            $indentarr['proposedindentid'] = $indent['proposedindentid'];
            $indentarr['transportername'] = $indent['transportername'];
            $indentarr['factoryname'] = $indent['factoryname'];
            $indentarr['depotname'] = $indent['depotname'];
            $indentarr['proposedvehiclecode'] = $indent['proposedvehiclecode'] . ' - ' . $indent['proposedvehicledescription'];
            if ($indent['actualvehiclecode'] != '') {
                $indentarr['actualvehiclecode'] = $indent['actualvehiclecode'] . ' - ' . $indent['actualvehicledescription'];
            } else {
                $indentarr['actualvehiclecode'] = '';
            }
            $indentarr['vehicleno'] = $indent['vehicleno'];
            $indentarr['daterequired'] = date('d-m-Y', strtotime($indent['date_required']));
            $indentarr['created_on'] = date(speedConstants::DEFAULT_DATETIME, strtotime($indent['created_on']));
            $indentarr['weight'] = $indent['totalweight'];
            $indentarr['volume'] = $indent['totalvolume'];
            $indentarr['view'] = "<a href='tms.php?pg=view-indent-sku&eid=" . $indent['indentid'] . "'>" . constants::viewimage . "</a>";
            if ($indent['loadstatus'] == 0) {
                $indentarr['edit'] = "<a href='tms.php?pg=edit-indent&eid=" . $indent['indentid'] . "'>" . constants::editimage . "</a>";
            } else {
                $indentarr['edit'] = "";
            }
            $indents[] = $indentarr;
        }
    }
}
if ($action == 'edit-indent') {
    $eid = $_REQUEST['eid'];
    $indents = array();
    $objIndent = new Indent();
    $objIndent->customerno = $_SESSION["customerno"];
    $objIndent->indentid = $eid;
    $indents = getIndents($objIndent);
}
if ($action == 'view-indent-sku') {
    $eid = $_REQUEST['eid'];
    $indents_sku = array();
    $objIndent = new Indent();
    $objIndent->customerno = $_SESSION["customerno"];
    $objIndent->indentid = $eid;
    $IndentArray = getIndentSkuMapping($objIndent);
    foreach ($IndentArray as $Indent) {
        $IndentArrayarr['indentid'] = $Indent['indentid'];
        $IndentArrayarr['skuid'] = $Indent['skuid'];
        $IndentArrayarr['skucode'] = $Indent['skucode'];
        $IndentArrayarr['sku_description'] = $Indent['sku_description'];
        $IndentArrayarr['no_of_units'] = $Indent['no_of_units'];
        $indents_sku[] = $IndentArrayarr;
    }
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - SUMMARY (EFFICIENCY)">
if ($action == 'view-summary') {
    $customerno = $_SESSION['customerno'];
    $factoryid = '';
    $depotid = '';
    $transporterid = '';
    $zoneid = '';
    $typeid = '';
    $startdate = $objDate->modify('first day of this month')->format(DATEFORMAT_YMD);
    $enddate = $objDate->modify('last day of this month')->format(DATEFORMAT_YMD);
    if (isset($_POST['filter']) && $_POST['filter'] == 1) {
        $zoneid = $_POST['zoneid'];
        $typeid = $_POST['typeid'];
        if ($_POST['SDate'] != '' && $_POST['SDate'] != '01-01-1970') {
            $startdate = date(DATEFORMAT_YMD, strtotime($_POST['SDate']));
        }
        if ($_POST['EDate'] != '' && $_POST['EDate'] != '01-01-1970') {
            $enddate = date(DATEFORMAT_YMD, strtotime($_POST['EDate']));
        }
    }
    if (isset($_POST['factoryid']) && $_POST['factoryid'] != '') {
        $factoryid = $_POST['factoryid'];
    } else if (isset($_SESSION['factoryid'])) {
        $factoryid = $_SESSION['factoryid'];
    }
    if (isset($_SESSION['depotid'])) {
        $depotid = $_SESSION['depotid'];
    }
    if (isset($_SESSION['transporterid'])) {
        $transporterid = $_SESSION['transporterid'];
    }
    $effObj = new stdClass();
    $effObj->customerno = $customerno;
    $effObj->factoryid = $factoryid;
    $effObj->depotid = $depotid;
    $effObj->transporterid = $transporterid;
    $effObj->zoneid = $zoneid;
    $effObj->typeid = $typeid;
    $effObj->startdate = $startdate;
    $effObj->enddate = $enddate;
    $transportereff = getTransporterEff($effObj);
    $zoneeff = getZoneEff($effObj);
    $transporterzoneeff = getTransporterZoneEff($effObj);
    $factoryeff = getFactoryEff($effObj);
    $daterequiredeff = getDaterequiredEff($effObj);
    /* Vendor Wise Placement Efficiency */
    $transporterEffArray = array();
    $transporterEffArrayTotal = array();
    if (isset($transportereff)) {
        $totalindent = 0;
        $placedindent = 0;
        $effpercent = 0;
        foreach ($transportereff as $transportereffarr) {
            $transportereffarrlist['transportername'] = $transportereffarr['transportername'];
            $transportereffarrlist['totalindent'] = $transportereffarr['totalindent'];
            $transportereffarrlist['placed'] = $transportereffarr['placed'];
            $transportereffarrlist['efficiency'] = round(($transportereffarr['placed'] / $transportereffarr['totalindent']) * 100, 2);
            $totalindent += $transportereffarr['totalindent'];
            $placedindent += $transportereffarr['placed'];
            $effpercent = round($placedindent / $totalindent * 100, 2);
            $transporterEffArray[] = $transportereffarrlist;
            $transporterEffArrayTotal['totalindent'] = $totalindent;
            $transporterEffArrayTotal['placedindent'] = $placedindent;
            $transporterEffArrayTotal['effpercent'] = $effpercent;
        }
    }
    /* Zone Wise Placement Efficiency */
    $zoneEffArray = array();
    $zoneEffArrayTotal = array();
    if (isset($zoneeff)) {
        $totalindent = 0;
        $placedindent = 0;
        $effpercent = 0;
        foreach ($zoneeff as $zoneeffarr) {
            $zoneeffarrlist['factoryname'] = $zoneeffarr['factoryname'];
            $zoneeffarrlist['zonename'] = $zoneeffarr['zonename'];
            $zoneeffarrlist['transportername'] = $zoneeffarr['transportername'];
            $zoneeffarrlist['factoryidcount'] = $zoneeffarr['totalindent'];
            $zoneeffarrlist['placed'] = $zoneeffarr['placed'];
            $zoneeffarrlist['efficiency'] = round(($zoneeffarr['placed'] / $zoneeffarr['totalindent']) * 100, 2);
            $totalindent += $zoneeffarr['totalindent'];
            $placedindent += $zoneeffarr['placed'];
            $effpercent = round($placedindent / $totalindent * 100, 2);
            $zoneEffArray[] = $zoneeffarrlist;
            $zoneEffArrayTotal['totalindent'] = $totalindent;
            $zoneEffArrayTotal['placedindent'] = $placedindent;
            $zoneEffArrayTotal['effpercent'] = $effpercent;
        }
    }
    /* Vendor & Zone Wise Placement Efficiency  */
    $transporterzoneEffArray = array();
    $transporterzoneEffArrayTotal = array();
    if (isset($transporterzoneeff)) {
        $totalindent = 0;
        $placedindent = 0;
        $effpercent = 0;
        foreach ($transporterzoneeff as $transporterzoneeffarr) {
            $transporterzoneeffarrlist['transportername'] = $transporterzoneeffarr['transportername'];
            $transporterzoneeffarrlist['zonename'] = $transporterzoneeffarr['zonename'];
            $transporterzoneeffarrlist['totalindent'] = $transporterzoneeffarr['totalindent'];
            $transporterzoneeffarrlist['placed'] = $transporterzoneeffarr['placed'];
            $transporterzoneeffarrlist['efficiency'] = round(($transporterzoneeffarr['placed'] / $transporterzoneeffarr['totalindent']) * 100, 2);
            $totalindent += $transporterzoneeffarr['totalindent'];
            $placedindent += $transporterzoneeffarr['placed'];
            $effpercent = round($placedindent / $totalindent * 100, 2);
            $transporterzoneEffArray[] = $transporterzoneeffarrlist;
            $transporterzoneEffArrayTotal['totalindent'] = $totalindent;
            $transporterzoneEffArrayTotal['placedindent'] = $placedindent;
            $transporterzoneEffArrayTotal['effpercent'] = $effpercent;
        }
    }
    /* Plant Wise Placement Efficiency   */
    $factoryEffArray = array();
    $factoryEffArrayTotal = array();
    if (isset($factoryeff)) {
        $totalindent = 0;
        $placedindent = 0;
        $effpercent = 0;
        foreach ($factoryeff as $factoryeffarr) {
            $factoryeffarrlist['factoryname'] = $factoryeffarr['factoryname'];
            $factoryeffarrlist['factoryidcount'] = $factoryeffarr['totalindent'];
            $factoryeffarrlist['placed'] = $factoryeffarr['placed'];
            $factoryeffarrlist['efficiency'] = round(($factoryeffarr['placed'] / $factoryeffarr['totalindent']) * 100, 2);
            $totalindent += $factoryeffarr['totalindent'];
            $placedindent += $factoryeffarr['placed'];
            $effpercent = round($placedindent / $totalindent * 100, 2);
            $factoryEffArray[] = $factoryeffarrlist;
            $factoryEffArrayTotal['totalindent'] = $totalindent;
            $factoryEffArrayTotal['placedindent'] = $placedindent;
            $factoryEffArrayTotal['effpercent'] = $effpercent;
        }
    }
    /* Date Wise Placement Efficiency   */
    $daterequiredEffArray = array();
    $daterequiredEffArrayTotal = array();
    if (isset($daterequiredeff)) {
        $totalindent = 0;
        $placedindent = 0;
        $effpercent = 0;
        foreach ($daterequiredeff as $daterequiredeffarr) {
            $daterequiredeffarrlist['requireddate'] = date('d-m-Y', strtotime($daterequiredeffarr['requireddate']));
            $daterequiredeffarrlist['totalindent'] = $daterequiredeffarr['totalindent'];
            $daterequiredeffarrlist['placed'] = $daterequiredeffarr['placed'];
            $daterequiredeffarrlist['efficiency'] = round(($daterequiredeffarr['placed'] / $daterequiredeffarr['totalindent']) * 100, 2);
            $totalindent += $daterequiredeffarr['totalindent'];
            $placedindent += $daterequiredeffarr['placed'];
            $effpercent = round($placedindent / $totalindent * 100, 2);
            $daterequiredEffArray[] = $daterequiredeffarrlist;
            $daterequiredEffArrayTotal['totalindent'] = $totalindent;
            $daterequiredEffArrayTotal['placedindent'] = $placedindent;
            $daterequiredEffArrayTotal['effpercent'] = $effpercent;
        }
    }
    //print_r($zoneeff);
}
if ($action == 'Vendor-Eff') {
    $customerno = $_SESSION['customerno'];
    $factoryid = '';
    $depotid = '';
    $transporterid = '';
    $zoneid = '';
    $typeid = '';
    $startdate = $objDate->modify('first day of this month')->format(DATEFORMAT_YMD);
    $enddate = $objDate->modify('last day of this month')->format(DATEFORMAT_YMD);
    if (isset($_POST['filter']) && $_POST['filter'] == 1) {
        $zoneid = $_POST['zoneid'];
        $typeid = $_POST['typeid'];
        if ($_POST['SDate'] != '' && $_POST['SDate'] != '01-01-1970') {
            $startdate = date(DATEFORMAT_YMD, strtotime($_POST['SDate']));
        }
        if ($_POST['EDate'] != '' && $_POST['EDate'] != '01-01-1970') {
            $enddate = date(DATEFORMAT_YMD, strtotime($_POST['EDate']));
        }
    }
    if (isset($_POST['factoryid']) && $_POST['factoryid'] != '') {
        $factoryid = $_POST['factoryid'];
    } else if (isset($_SESSION['factoryid'])) {
        $factoryid = $_SESSION['factoryid'];
    }
    if (isset($_SESSION['depotid'])) {
        $depotid = $_SESSION['depotid'];
    }
    if (isset($_SESSION['transporterid'])) {
        $transporterid = $_SESSION['transporterid'];
    }
    $effObj = new stdClass();
    $effObj->customerno = $customerno;
    $effObj->factoryid = $factoryid;
    $effObj->depotid = $depotid;
    $effObj->transporterid = $transporterid;
    $effObj->zoneid = $zoneid;
    $effObj->typeid = $typeid;
    $effObj->startdate = $startdate;
    $effObj->enddate = $enddate;
    $transportereff = getTransporterEff($effObj);
    /* Vendor Wise Placement Efficiency */
    $transporterEffArray = array();
    $transporterEffArrayTotal = array();
    if (isset($transportereff)) {
        $totalindent = 0;
        $placedindent = 0;
        $effpercent = 0;
        foreach ($transportereff as $transportereffarr) {
            $transportereffarrlist['transportername'] = $transportereffarr['transportername'];
            $transportereffarrlist['totalindent'] = $transportereffarr['totalindent'];
            $transportereffarrlist['placed'] = $transportereffarr['placed'];
            $transportereffarrlist['efficiency'] = round(($transportereffarr['placed'] / $transportereffarr['totalindent']) * 100, 2);
            $totalindent += $transportereffarr['totalindent'];
            $placedindent += $transportereffarr['placed'];
            $effpercent = round($placedindent / $totalindent * 100, 2);
            $transporterEffArray[] = $transportereffarrlist;
            $transporterEffArrayTotal['totalindent'] = $totalindent;
            $transporterEffArrayTotal['placedindent'] = $placedindent;
            $transporterEffArrayTotal['effpercent'] = $effpercent;
        }
    }
}
if ($action == 'Vendor-Zone-Eff') {
    $customerno = $_SESSION['customerno'];
    $factoryid = '';
    $depotid = '';
    $transporterid = '';
    $zoneid = '';
    $typeid = '';
    $startdate = $objDate->modify('first day of this month')->format(DATEFORMAT_YMD);
    $enddate = $objDate->modify('last day of this month')->format(DATEFORMAT_YMD);
    if (isset($_POST['filter']) && $_POST['filter'] == 1) {
        $zoneid = $_POST['zoneid'];
        $typeid = $_POST['typeid'];
        if ($_POST['SDate'] != '' && $_POST['SDate'] != '01-01-1970') {
            $startdate = date(DATEFORMAT_YMD, strtotime($_POST['SDate']));
        }
        if ($_POST['EDate'] != '' && $_POST['EDate'] != '01-01-1970') {
            $enddate = date(DATEFORMAT_YMD, strtotime($_POST['EDate']));
        }
    }
    if (isset($_POST['factoryid']) && $_POST['factoryid'] != '') {
        $factoryid = $_POST['factoryid'];
    } else if (isset($_SESSION['factoryid'])) {
        $factoryid = $_SESSION['factoryid'];
    }
    if (isset($_SESSION['depotid'])) {
        $depotid = $_SESSION['depotid'];
    }
    if (isset($_SESSION['transporterid'])) {
        $transporterid = $_SESSION['transporterid'];
    }
    $effObj = new stdClass();
    $effObj->customerno = $customerno;
    $effObj->factoryid = $factoryid;
    $effObj->depotid = $depotid;
    $effObj->transporterid = $transporterid;
    $effObj->zoneid = $zoneid;
    $effObj->typeid = $typeid;
    $effObj->startdate = $startdate;
    $effObj->enddate = $enddate;
    $transporterzoneeff = getTransporterZoneEff($effObj);
    /* Vendor & Zone Wise Placement Efficiency  */
    $transporterzoneEffArray = array();
    $transporterzoneEffArrayTotal = array();
    if (isset($transporterzoneeff)) {
        $totalindent = 0;
        $placedindent = 0;
        $effpercent = 0;
        foreach ($transporterzoneeff as $transporterzoneeffarr) {
            $transporterzoneeffarrlist['transportername'] = $transporterzoneeffarr['transportername'];
            $transporterzoneeffarrlist['zonename'] = $transporterzoneeffarr['zonename'];
            $transporterzoneeffarrlist['totalindent'] = $transporterzoneeffarr['totalindent'];
            $transporterzoneeffarrlist['placed'] = $transporterzoneeffarr['placed'];
            $transporterzoneeffarrlist['efficiency'] = round(($transporterzoneeffarr['placed'] / $transporterzoneeffarr['totalindent']) * 100, 2);
            $totalindent += $transporterzoneeffarr['totalindent'];
            $placedindent += $transporterzoneeffarr['placed'];
            $effpercent = round($placedindent / $totalindent * 100, 2);
            $transporterzoneEffArray[] = $transporterzoneeffarrlist;
            $transporterzoneEffArrayTotal['totalindent'] = $totalindent;
            $transporterzoneEffArrayTotal['placedindent'] = $placedindent;
            $transporterzoneEffArrayTotal['effpercent'] = $effpercent;
        }
    }
}
if ($action == 'Plant-Vendor-Zone-Eff') {
    $customerno = $_SESSION['customerno'];
    $factoryid = '';
    $depotid = '';
    $transporterid = '';
    $zoneid = '';
    $typeid = '';
    $startdate = $objDate->modify('first day of this month')->format(DATEFORMAT_YMD);
    $enddate = $objDate->modify('last day of this month')->format(DATEFORMAT_YMD);
    if (isset($_POST['filter']) && $_POST['filter'] == 1) {
        $zoneid = $_POST['zoneid'];
        $typeid = $_POST['typeid'];
        if ($_POST['SDate'] != '' && $_POST['SDate'] != '01-01-1970') {
            $startdate = date(DATEFORMAT_YMD, strtotime($_POST['SDate']));
        }
        if ($_POST['EDate'] != '' && $_POST['EDate'] != '01-01-1970') {
            $enddate = date(DATEFORMAT_YMD, strtotime($_POST['EDate']));
        }
    }
    if (isset($_POST['factoryid']) && $_POST['factoryid'] != '') {
        $factoryid = $_POST['factoryid'];
    } else if (isset($_SESSION['factoryid'])) {
        $factoryid = $_SESSION['factoryid'];
    }
    if (isset($_SESSION['depotid'])) {
        $depotid = $_SESSION['depotid'];
    }
    if (isset($_SESSION['transporterid'])) {
        $transporterid = $_SESSION['transporterid'];
    }
    $effObj = new stdClass();
    $effObj->customerno = $customerno;
    $effObj->factoryid = $factoryid;
    $effObj->depotid = $depotid;
    $effObj->transporterid = $transporterid;
    $effObj->zoneid = $zoneid;
    $effObj->typeid = $typeid;
    $effObj->startdate = $startdate;
    $effObj->enddate = $enddate;
    $zoneeff = getZoneEff($effObj);
    /* Zone Wise Placement Efficiency */
    $zoneEffArray = array();
    $zoneEffArrayTotal = array();
    if (isset($zoneeff)) {
        $totalindent = 0;
        $placedindent = 0;
        $effpercent = 0;
        foreach ($zoneeff as $zoneeffarr) {
            $zoneeffarrlist['factoryname'] = $zoneeffarr['factoryname'];
            $zoneeffarrlist['zonename'] = $zoneeffarr['zonename'];
            $zoneeffarrlist['transportername'] = $zoneeffarr['transportername'];
            $zoneeffarrlist['factoryidcount'] = $zoneeffarr['totalindent'];
            $zoneeffarrlist['placed'] = $zoneeffarr['placed'];
            $zoneeffarrlist['efficiency'] = round(($zoneeffarr['placed'] / $zoneeffarr['totalindent']) * 100, 2);
            $totalindent += $zoneeffarr['totalindent'];
            $placedindent += $zoneeffarr['placed'];
            $effpercent = round($placedindent / $totalindent * 100, 2);
            $zoneEffArray[] = $zoneeffarrlist;
            $zoneEffArrayTotal['totalindent'] = $totalindent;
            $zoneEffArrayTotal['placedindent'] = $placedindent;
            $zoneEffArrayTotal['effpercent'] = $effpercent;
        }
    }
}
if ($action == 'Plant-Eff') {
    $customerno = $_SESSION['customerno'];
    $factoryid = '';
    $depotid = '';
    $transporterid = '';
    $zoneid = '';
    $typeid = '';
    $startdate = $objDate->modify('first day of this month')->format(DATEFORMAT_YMD);
    $enddate = $objDate->modify('last day of this month')->format(DATEFORMAT_YMD);
    if (isset($_POST['filter']) && $_POST['filter'] == 1) {
        $zoneid = $_POST['zoneid'];
        $typeid = $_POST['typeid'];
        if ($_POST['SDate'] != '' && $_POST['SDate'] != '01-01-1970') {
            $startdate = date(DATEFORMAT_YMD, strtotime($_POST['SDate']));
        }
        if ($_POST['EDate'] != '' && $_POST['EDate'] != '01-01-1970') {
            $enddate = date(DATEFORMAT_YMD, strtotime($_POST['EDate']));
        }
    }
    if (isset($_POST['factoryid']) && $_POST['factoryid'] != '') {
        $factoryid = $_POST['factoryid'];
    } else if (isset($_SESSION['factoryid'])) {
        $factoryid = $_SESSION['factoryid'];
    }
    if (isset($_SESSION['depotid'])) {
        $depotid = $_SESSION['depotid'];
    }
    if (isset($_SESSION['transporterid'])) {
        $transporterid = $_SESSION['transporterid'];
    }
    $effObj = new stdClass();
    $effObj->customerno = $customerno;
    $effObj->factoryid = $factoryid;
    $effObj->depotid = $depotid;
    $effObj->transporterid = $transporterid;
    $effObj->zoneid = $zoneid;
    $effObj->typeid = $typeid;
    $effObj->startdate = $startdate;
    $effObj->enddate = $enddate;
    $factoryeff = getFactoryEff($effObj);
    /* Plant Wise Placement Efficiency   */
    $factoryEffArray = array();
    $factoryEffArrayTotal = array();
    if (isset($factoryeff)) {
        $totalindent = 0;
        $placedindent = 0;
        $effpercent = 0;
        foreach ($factoryeff as $factoryeffarr) {
            $factoryeffarrlist['factoryname'] = $factoryeffarr['factoryname'];
            $factoryeffarrlist['factoryidcount'] = $factoryeffarr['totalindent'];
            $factoryeffarrlist['placed'] = $factoryeffarr['placed'];
            $factoryeffarrlist['efficiency'] = round(($factoryeffarr['placed'] / $factoryeffarr['totalindent']) * 100, 2);
            $totalindent += $factoryeffarr['totalindent'];
            $placedindent += $factoryeffarr['placed'];
            $effpercent = round($placedindent / $totalindent * 100, 2);
            $factoryEffArray[] = $factoryeffarrlist;
            $factoryEffArrayTotal['totalindent'] = $totalindent;
            $factoryEffArrayTotal['placedindent'] = $placedindent;
            $factoryEffArrayTotal['effpercent'] = $effpercent;
        }
    }
}
if ($action == 'Date-Eff') {
    $customerno = $_SESSION['customerno'];
    $factoryid = '';
    $depotid = '';
    $transporterid = '';
    $zoneid = '';
    $typeid = '';
    $startdate = $objDate->modify('first day of this month')->format(DATEFORMAT_YMD);
    $enddate = $objDate->modify('last day of this month')->format(DATEFORMAT_YMD);
    if (isset($_POST['filter']) && $_POST['filter'] == 1) {
        $zoneid = $_POST['zoneid'];
        $typeid = $_POST['typeid'];
        if ($_POST['SDate'] != '' && $_POST['SDate'] != '01-01-1970') {
            $startdate = date(DATEFORMAT_YMD, strtotime($_POST['SDate']));
        }
        if ($_POST['EDate'] != '' && $_POST['EDate'] != '01-01-1970') {
            $enddate = date(DATEFORMAT_YMD, strtotime($_POST['EDate']));
        }
    }
    if (isset($_POST['factoryid']) && $_POST['factoryid'] != '') {
        $factoryid = $_POST['factoryid'];
    } else if (isset($_SESSION['factoryid'])) {
        $factoryid = $_SESSION['factoryid'];
    }
    if (isset($_SESSION['depotid'])) {
        $depotid = $_SESSION['depotid'];
    }
    if (isset($_SESSION['transporterid'])) {
        $transporterid = $_SESSION['transporterid'];
    }
    $effObj = new stdClass();
    $effObj->customerno = $customerno;
    $effObj->factoryid = $factoryid;
    $effObj->depotid = $depotid;
    $effObj->transporterid = $transporterid;
    $effObj->zoneid = $zoneid;
    $effObj->typeid = $typeid;
    $effObj->startdate = $startdate;
    $effObj->enddate = $enddate;
    $daterequiredeff = getDaterequiredEff($effObj);
    /* Date Wise Placement Efficiency   */
    $daterequiredEffArray = array();
    $daterequiredEffArrayTotal = array();
    if (isset($daterequiredeff)) {
        $totalindent = 0;
        $placedindent = 0;
        $effpercent = 0;
        foreach ($daterequiredeff as $daterequiredeffarr) {
            $daterequiredeffarrlist['requireddate'] = date('d-m-Y', strtotime($daterequiredeffarr['requireddate']));
            $daterequiredeffarrlist['totalindent'] = $daterequiredeffarr['totalindent'];
            $daterequiredeffarrlist['placed'] = $daterequiredeffarr['placed'];
            $daterequiredeffarrlist['efficiency'] = round(($daterequiredeffarr['placed'] / $daterequiredeffarr['totalindent']) * 100, 2);
            $totalindent += $daterequiredeffarr['totalindent'];
            $placedindent += $daterequiredeffarr['placed'];
            $effpercent = round($placedindent / $totalindent * 100, 2);
            $daterequiredEffArray[] = $daterequiredeffarrlist;
            $daterequiredEffArrayTotal['totalindent'] = $totalindent;
            $daterequiredEffArrayTotal['placedindent'] = $placedindent;
            $daterequiredEffArrayTotal['effpercent'] = $effpercent;
        }
    }
}
//</editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - LEFT OVER SKU">
if ($action == 'left-over-sku') {
    $factoryid = '';
    if (isset($_SESSION['factoryid']) && $_SESSION['factoryid'] != '') {
        $factoryid = $_SESSION['factoryid'];
    }
    $leftover_sku = array();
    $objLeftOver = new stdClass();
    $objLeftOver->customerno = $_SESSION["customerno"];
    $objLeftOver->factoryid = $_SESSION["factoryid"];
    $leftOverArray = getLeftoverDetails($objLeftOver);
    foreach ($leftOverArray as $leftOver) {
        $LeftOverarr['leftoverid'] = $leftOver['leftoverid'];
        $LeftOverarr['factoryname'] = $leftOver['factoryname'];
        $LeftOverarr['depotname'] = $leftOver['depotname'];
        $LeftOverarr['weight'] = $leftOver['weight'];
        $LeftOverarr['volume'] = $leftOver['volume'];
        $LeftOverarr['daterequired'] = date('d-m-Y', strtotime($leftOver['daterequired']));
        $LeftOverarr['created_on'] = date(speedConstants::DEFAULT_DATETIME, strtotime($leftOver['created_on']));
        $LeftOverarr['edit'] = "<a href='tms.php?pg=view-leftover-sku&eid=" . $leftOver['leftoverid'] . "' " . $alert . ">" . constants::editimage . "</a>";
        $LeftOverarr['delete'] = "<a href='action.php?action=del-leftover-sku&did=" . $leftOver['leftoverid'] . "' " . $alert . ">" . constants::deleteimage . "</a>";
        $leftover_sku[] = $LeftOverarr;
    }
}
if ($action == 'view-leftover-sku') {
    $eid = $_REQUEST['eid'];
    $leftover_sku = array();
    $objLeftOver = new stdClass();
    $objLeftOver->customerno = $_SESSION["customerno"];
    $objLeftOver->leftoverid = $eid;
    $leftOverArray = getLeftoverSku($objLeftOver);
    foreach ($leftOverArray as $leftOver) {
        $LeftOverarr['sku'] = $leftOver['skucode'];
        $LeftOverarr['sku_decsription'] = $leftOver['sku_description'];
        $LeftOverarr['no_of_units'] = $leftOver['no_of_units'];
        $LeftOverarr['weight'] = $leftOver['totalWeight'];
        $LeftOverarr['volume'] = $leftOver['totalVolume'];
        $leftover_sku[] = $LeftOverarr;
    }
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - EXPORT PLACEMENT TRACKER">
function exportPlacementTrackerExcel($customerno, $factoryid, $transporterid)
{
    $proposed_indents = array();
    $objProposedIndent = new ProposedIndent();
    $objProposedIndent->customerno = $customerno;
    $objProposedIndent->proposedindentid = '';
    $objProposedIndent->factoryid = $factoryid;
    $objProposedIndent->transporterid = $transporterid;
    $proposedIndentArray = getProposedIndent($objProposedIndent);
    foreach ($proposedIndentArray as $proposedIndent) {
        $proposedIndentarr['proposedindentid'] = $proposedIndent['proposedindentid'];
        $proposedIndentarr['factoryname'] = $proposedIndent['factoryname'];
        $proposedIndentarr['depotname'] = $proposedIndent['depotname'];
        $proposedIndentarr['zonename'] = $proposedIndent['zonename'];
        $proposedIndentarr['transportername'] = $proposedIndent['transportername'];
        //$proposedIndentarr['vehiclecode'] = $proposedIndent['vehiclecode'];
        $proposedIndentarr['proposedvehiclecode'] = $proposedIndent['vehiclecode'] . ' - ' . $proposedIndent['proposedvehicledescription'];
        if ($proposedIndent['actualvehiclecode'] != '') {
            $proposedIndentarr['actualvehiclecode'] = $proposedIndent['actualvehiclecode'] . ' - ' . $proposedIndent['actualvehicledescription'];
        } else {
            $proposedIndentarr['actualvehiclecode'] = '';
        }
        $proposedIndentarr['date_required'] = date('d-m-Y', strtotime($proposedIndent['date_required']));
        $proposedIndentarr['created_on'] = date(speedConstants::DEFAULT_DATETIME, strtotime($proposedIndent['created_on']));
        $proposedIndentarr['total_weight'] = $proposedIndent['total_weight'];
        $proposedIndentarr['total_volume'] = $proposedIndent['total_volume'];
        $proposedIndentarr['piremark'] = $proposedIndent['piremark'];
        $proposedIndentarr['transporterremarks'] = $proposedIndent['transporterremarks'];
        if ($proposedIndent['isAccepted'] == 1 && $proposedIndent['isApproved'] != 1) {
            $proposedIndentarr['status'] = 'Indent Confirmed By Transporter';
        } else if ($proposedIndent['isAccepted'] == -1 && $proposedIndent['isAutoRejected'] == 0) {
            $proposedIndentarr['status'] = 'Indent Rejected By Transporter';
        } else if ($proposedIndent['isAccepted'] == -1 && $proposedIndent['isAutoRejected'] == 1) {
            $proposedIndentarr['status'] = 'Indent Not Confirmed. Auto Rejected By System';
        } else if ($proposedIndent['isAccepted'] == 0 && $proposedIndent['isAutoRejected'] == 1) {
            $proposedIndentarr['status'] = 'Indent Expired';
        } else {
            $proposedIndentarr['status'] = 'Awaiting Response';
        }
        if ($proposedIndent['isApproved'] == 1 && $proposedIndent['isAccepted'] == 1) {
            $proposedIndentarr['status'] = 'Indent Confirmed By Transporter';
        } else if ($proposedIndent['isApproved'] == -1) {
            $proposedIndentarr['status'] = 'Indent Rejected By Transporter';
        }
        /* Status For Factory Official Approval */
        $proposedIndentarr['shipmentno'] = '';
        $proposedIndentarr['remark'] = '';
        $proposedIndentarr['factorystatus'] = '';
        if ($proposedIndent['loadstatus'] == 0 && $proposedIndent['isAccepted'] == 1) {
            $proposedIndentarr['factorystatus'] = 'Awaiting Response';
        } else if ($proposedIndent['loadstatus'] == '1' && $proposedIndent['isAccepted'] == 1) {
            $proposedIndentarr['factorystatus'] = 'Loaded';
            $proposedIndentarr['remark'] = $proposedIndent['remarks'];
            $proposedIndentarr['shipmentno'] = $proposedIndent['shipmentno'];
        } else if ($proposedIndent['loadstatus'] == '-1' && $proposedIndent['isAccepted'] == 1) {
            $proposedIndentarr['factorystatus'] = 'Rejected';
            $proposedIndentarr['remark'] = $proposedIndent['remarks'];
        } else if ($proposedIndent['loadstatus'] == '2' && $proposedIndent['isAccepted'] == 1) {
            $proposedIndentarr['factorystatus'] = 'Vehicle Not Placed';
            $proposedIndentarr['remark'] = $proposedIndent['remarks'];
        }
        $proposedIndentarr['vehicleno'] = $proposedIndent['vehicleno'];
        if ($proposedIndent['drivermobileno'] == 'NULL') {
            $proposedIndentarr['driverno'] = '';
        } else {
            $proposedIndentarr['driverno'] = $proposedIndent['drivermobileno'];
        }
        $proposed_indents[] = $proposedIndentarr;
    }
    placementTrackerHtml($proposed_indents, $customerno);
}

function placementTrackerHtml($proposedindent, $customerno)
{
    $title = 'Placement Tracker';
    $subTitle = array(
    );
    $customer_details = null;
    if (!isset($_SESSION['customerno'])) {
        $cm = new CustomerManager($customerno);
        $customer_details = $cm->getcustomerdetail_byid($customerno);
    }
    $report = '';
    $report .= excel_header($title, $subTitle, $customer_details);
    $report .= "<table id='search_table_2' style='width: 1120px; font-size:12px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
    $report .= "<tr class='dtblTh'>
                    <th>ID</th>
                    <th >Vehicle Requirement Date</th>
                    <th >Factory</th>
                    <th >Depot</th>
                    <th >Zone</th>
                    <th >Transporter </th>
                    <th >Proposed Vehicle</th>
                    <th >Plant Indent Remark</th>
                    <th >Transporter Status</th>
                    <th >Actual Vehicle</th>
                    <th >VehicleNo </th>
                    <th >DriverNo</th>
                    <th >Transporter Remark</th>
                    <th>Factory Status</th>
                    <th>Shipment No</th>
                    <th>Shipment Remark</th>
                    <th>CreatedOn</th>
                    </tr>";
    if (!empty($proposedindent)) {
        foreach ($proposedindent as $indent) {
            $report .= "<tr>";
            $report .= "<td>" . $indent['proposedindentid'] . "</td>";
            $report .= "<td>" . $indent['date_required'] . "</td>";
            $report .= "<td>" . $indent['factoryname'] . "</td>";
            $report .= "<td>" . $indent['depotname'] . "</td>";
            $report .= "<td>" . $indent['zonename'] . "</td>";
            $report .= "<td>" . $indent['transportername'] . "</td>";
            $report .= "<td>" . $indent['proposedvehiclecode'] . "</td>";
            $report .= "<td>" . $indent['piremark'] . "</td>";
            $report .= "<td>" . $indent['status'] . "</td>";
            $report .= "<td>" . $indent['actualvehiclecode'] . "</td>";
            $report .= "<td>" . $indent['vehicleno'] . "</td>";
            $report .= "<td>" . $indent['driverno'] . "</td>";
            $report .= "<td>" . $indent['transporterremarks'] . "</td>";
            $report .= "<td>" . $indent['factorystatus'] . "</td>";
            $report .= "<td>" . $indent['shipmentno'] . "</td>";
            $report .= "<td>" . $indent['remark'] . "</td>";
            $report .= "<td>" . $indent['created_on'] . "</td>";
            $report .= "</tr>";
        }
    } else {
        $report .= "<tr>No Data</tr>";
    }
    $report .= "</table>";
    echo $report;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PAGE ACTIONS  - VENDOR PAYABLE">
if ($action == 'bills') {
    $billlist = '';
    $billlist .= '<option value="-1">Select Bill Type</option>';
    $billtypes = getBilltypeFilter();
    if (isset($billtypes) && !empty($billtypes)) {
        foreach ($billtypes as $billtype) {
            $billlist .= '<option value="' . $billtype['btypeid'] . '">' . $billtype['type'] . '</option>';
        }
    }
    $vehicletypelist = '';
    $vehicletypelist .= '<option value="-1">Select Vehicle Type</option>';
    $vehiclestype = getVehiclestypeFilter();
    if (isset($vehiclestype) && !empty($vehiclestype)) {
        foreach ($vehiclestype as $vehicletype) {
            $vehicletypelist .= '<option value="' . $vehicletype['vtypeid'] . '">' . $vehicletype['type'] . '</option>';
        }
    }
    //get_vehicletype_filter
    $movementypelist = '';
    $movementypelist .= '<option value="-1">Select Movement Type</option>';
    $movementtype = getMovementFilter();
    if (isset($movementtype) && !empty($movementtype)) {
        foreach ($movementtype as $type) {
            $movementypelist .= '<option value="' . $type['tid'] . '">' . $type['type'] . '</option>';
        }
    }
}
if ($action == 'view-bills') {
    $billDetails = array();
    $factoryid = '';
    if (isset($_SESSION['factoryid']) && $_SESSION['factoryid'] != '') {
        $factoryid = $_SESSION['factoryid'];
    }
    $objBill = new stdClass();
    $objBill->billid = '';
    $objBill->factoryid = $factoryid;
    $objBill->customerno = $_SESSION['customerno'];
    $bills = getBillDetailsDraft($objBill);
    if (isset($bills) && !empty($bills)) {
        foreach ($bills as $bill) {
            $billarr['billid'] = $bill['billid'];
            $billarr['bill_no'] = $bill['bill_no'];
            $billarr['bill_date'] = $bill['bill_date'];
            $billarr['invoice_location'] = $bill['invoice_location'];
            $billarr['depotname'] = $bill['depotname'];
            $billarr['vendor'] = $bill['vendorname'];
            $billarr['final_bill_amount'] = $bill['final_bill_amount'];
            $billarr['savedon'] = date('Y-m-d h:s a');
            //$billarr['edit'] = "<a href='tms.php?pg=#&eid=" . $bill['billid'] . "'>" . constants::editimage . "</a>";
            //$billarr['view'] = "<a href='tms.php?pg=#&eid=" . $bill['billid'] . "'>" . constants::viewimage . "</a>";
            $billarr['edit'] = "<a href='tms.php?pg=edit-draft&eid=" . $bill['billid'] . "'>" . constants::editimage . "</a>";
            $billarr['view'] = "<a>" . constants::viewimage . "</a>";
            $billarr['delete'] = "<a href='action.php?action=del-draft&did=" . $bill['billid'] . "' " . $alert . ">" . constants::deleteimage . "</a>";
            $billDetails[] = $billarr;
        }
    }
}
if ($action == 'view-billtracker') {
    $billDetails = array();
    $factoryid = '';
    if (isset($_SESSION['factoryid']) && $_SESSION['factoryid'] != '') {
        $factoryid = $_SESSION['factoryid'];
    }
    $objBill = new stdClass();
    $objBill->billid = '';
    $objBill->factoryid = $factoryid;
    $objBill->customerno = $_SESSION['customerno'];
    $bills = getBillDetails($objBill);
    if (isset($bills) && !empty($bills)) {
        foreach ($bills as $bill) {
            $billarr['billid'] = $bill['billid'];
            $billarr['bill_no'] = $bill['bill_no'];
            $billarr['bill_date'] = $bill['bill_date'];
            $billarr['invoice_location'] = $bill['invoice_location'];
            $billarr['depotname'] = $bill['depotname'];
            $billarr['vendor'] = $bill['vendorname'];
            $billarr['final_bill_amount'] = $bill['final_bill_amount'];
            $billarr['due_days'] = $bill['due_days'];
            $billarr['bill_received_date'] = '';
            if ($bill['bill_received_date'] != '0000-00-00') {
                $billarr['bill_received_date'] = $bill['bill_received_date'];
            }
            $billarr['bill_sent_date'] = $bill['bill_sent_date'];
            $billarr['billing_status'] = $bill['billing_status'];
            $billarr['total_custody'] = $bill['total_custody'];
            $billarr['payment_status'] = $bill['payment_status'];
            $billarr['payment_done'] = $bill['payment_done'];
            $billarr['payment_bucket'] = $bill['payment_bucket'];
            $billarr['mdlz_remark'] = '';
            $billarr['vendor_remark'] = '';
            $billarr['edit'] = "<a href='tms.php?pg=edit-bill&eid=" . $bill['billid'] . "'>" . constants::editimage . "</a>";
            //$billarr['view'] = "<a href='tms.php?pg=#&eid=" . $bill['billid'] . "'>" . constants::viewimage . "</a>";
            //$billarr['edit'] = "<a>" . constants::editimage . "</a>";
            $billarr['view'] = "<a>" . constants::viewimage . "</a>";
            $billarr['delete'] = "<a href='action.php?action=del-bill&did=" . $bill['billid'] . "' " . $alert . ">" . constants::deleteimage . "</a>";
            $billDetails[] = $billarr;
        }
    }
}
if ($action == 'edit-bill') {
    $eid = $_REQUEST['eid'];
    $objBillDetails = new stdClass();
    $objBillDetails->billid = $eid;
    $objBillDetails->customerno = $_SESSION['customerno'];
    $billDetails = getBillDetails($objBillDetails);
    //Get Single Record From Bill Array For Editing Bill Details
    $billDetailsRecord = $billDetails[0];
    //print_r($billDetailsRecord);

    /* GET LR FOR Billid */
    $objLR = new stdClass();
    $objLR->lrid = '';
    $objLR->billid = $eid;
    $objLR->customerno = $_SESSION['customerno'];
    $lrDetails = getLrDetails($objLR);

    $billlist = '';
    $billlist .= '<option value="-1">Select Bill Type</option>';
    $billtypes = getBilltypeFilter();
    if (isset($billtypes) && !empty($billtypes)) {
        foreach ($billtypes as $billtype) {
            if ($billDetailsRecord['billtypeid'] == $billtype['btypeid']) {
                $billlist .= '<option value="' . $billtype['btypeid'] . '" selected="">' . $billtype['type'] . '</option>';
            } else {
                $billlist .= '<option value="' . $billtype['btypeid'] . '">' . $billtype['type'] . '</option>';
            }
        }
    }
    $vehicletypelist = '';
    $vehicletypelist .= '<option value="-1">Select Vehicle Type</option>';
    $vehiclestype = getVehiclestypeFilter();
    if (isset($vehiclestype) && !empty($vehiclestype)) {
        foreach ($vehiclestype as $vehicletype) {
            $vehicletypelist .= '<option value="' . $vehicletype['vtypeid'] . '">' . $vehicletype['type'] . '</option>';
        }
    }
    //get_vehicletype_filter
    $movementypelist = '';
    $movementypelist .= '<option value="-1">Select Movement Type</option>';
    $movementtype = getMovementFilter();
    if (isset($movementtype) && !empty($movementtype)) {
        foreach ($movementtype as $type) {
            $movementypelist .= '<option value="' . $type['tid'] . '">' . $type['type'] . '</option>';
        }
    }
}

if ($action == 'edit-draft') {
    $eid = $_REQUEST['eid'];
    $objBillDetails = new stdClass();
    $objBillDetails->billid = $eid;
    $objBillDetails->customerno = $_SESSION['customerno'];
    $billDetails = getBillDetailsDraft($objBillDetails);
    //Get Single Record From Bill Array For Editing Bill Details
    $billDetailsRecord = $billDetails[0];
    //print_r($billDetailsRecord);

    /* GET LR FOR Billid */
    $objLR = new stdClass();
    $objLR->lrid = '';
    $objLR->billid = $eid;
    $objLR->customerno = $_SESSION['customerno'];
    $lrDetails = getLrDetailsDraft($objLR);

    $billlist = '';
    $billlist .= '<option value="-1">Select Bill Type</option>';
    $billtypes = getBilltypeFilter();
    if (isset($billtypes) && !empty($billtypes)) {
        foreach ($billtypes as $billtype) {
            if ($billDetailsRecord['billtypeid'] == $billtype['btypeid']) {
                $billlist .= '<option value="' . $billtype['btypeid'] . '" selected="">' . $billtype['type'] . '</option>';
            } else {
                $billlist .= '<option value="' . $billtype['btypeid'] . '">' . $billtype['type'] . '</option>';
            }
        }
    }
    $vehicletypelist = '';
    $vehicletypelist .= '<option value="-1">Select Vehicle Type</option>';
    $vehiclestype = getVehiclestypeFilter();
    if (isset($vehiclestype) && !empty($vehiclestype)) {
        foreach ($vehiclestype as $vehicletype) {
            $vehicletypelist .= '<option value="' . $vehicletype['vtypeid'] . '">' . $vehicletype['type'] . '</option>';
        }
    }
    //get_vehicletype_filter
    $movementypelist = '';
    $movementypelist .= '<option value="-1">Select Movement Type</option>';
    $movementtype = getMovementFilter();
    if (isset($movementtype) && !empty($movementtype)) {
        foreach ($movementtype as $type) {
            $movementypelist .= '<option value="' . $type['tid'] . '">' . $type['type'] . '</option>';
        }
    }
}

//</editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="DEPOT FUNCTIONS">
function insertDepot(Depot $objdepot)
{
    //print_r($objdepot);
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $depotid = $objTms->insertDepot($objdepot);
    return $depotid;
}

function getDepots(Depot $objdepot)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $depotList = $objTms->getDepots($objdepot);
    return $depotList;
}

function getMappedDepots(Depot $objdepot)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $depotList = $objTms->getMappedDepots($objdepot);
    return $depotList;
}

function updateDepots(Depot $objdepot)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $depotList = $objTms->udate_depot($objdepot);
    return $depotList;
}

function deleteDepot(Depot $objdepot)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteDepot($objdepot);
    return $noOfRowsAffected;
}

function getDepotsFilter($objdepot)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $depots = $objTms->getDepotsFilter($objdepot);
    //print_r($depots);
    return $depots;
}

function insertMultidepotMapping(Depot $objdepot)
{
    //print_r($objdepot);
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $depotid = $objTms->insertMultidepotMapping($objdepot);
    return $depotid;
}

function deleteMultidepotMapping(Depot $objdepot)
{
    //print_r($objdepot);
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $depotid = $objTms->delete_mapped_depots($objdepot);
    return $depotid;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="ZONE FUNCTIONS">
function insertZone(Zone $objzone)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $zoneid = $objTms->insertZone($objzone);
    return $zoneid;
}

function getZones(Zone $objzone)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $zones = $objTms->getZones($objzone);
    //print_r($depots);
    return $zones;
}

function getZonesFilter(Zone $objzone)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $zones = $objTms->get_zone_filter($objzone);
    //print_r($depots);
    return $zones;
}

function updateZone(Zone $objzone)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $zones = $objTms->updateZone($objzone);
    //print_r($depots);
    return $zones;
}

function deleteZone(Zone $objzone)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $zones = $objTms->deleteZone($objzone);
    //print_r($depots);
    return $zones;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="LOCATION FUNCTIONS">
function insertLocation(Location $objlocation)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $locationid = $objTms->insertLocation($objlocation);
    return $locationid;
}

function getLocations(Location $objlocation)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $locations = $objTms->getLocations($objlocation);
    //print_r($locations);
    return $locations;
}

function updateLocation(Location $objlocation)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $locationid = $objTms->updateLocation($objlocation);
    return $locationid;
}

function deleteLocation(Location $objlocation)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $locationid = $objTms->deleteLocation($objlocation);
    return $locationid;
}

function getLocationsFilter(Location $objlocation)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $locations = $objTms->get_location_filter($objlocation);
    //print_r($depots);
    return $locations;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="FACTORY / PLANTS FUNCTIONS">
function insertFactory(Factory $objFactory)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $factoryid = $objTms->insertFactory($objFactory);
    return $factoryid;
}

function updateFactory(Factory $objFactory)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->updateFactory($objFactory);
    return $noOfRowsAffected;
}

function deleteFactory(Factory $objFactory)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteFactory($objFactory);
    return $noOfRowsAffected;
}

function getFactory(Factory $objFactory)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $factoryList = $objTms->get_factories($objFactory);
    return $factoryList;
}

function getFactoryFilter(Factory $objFactory)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $locations = $objTms->getFactoryFilter($objFactory);
    //print_r($depots);
    return $locations;
}

function getFactoryOfficials(Factory $objFactory)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $factoryList = $objTms->getFactoryOfficials($objFactory);
    return $factoryList;
}

function getMultipleFactoryOfficials(Factory $objFactory)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $factoryList = $objTms->getMultipleFactoryOfficials($objFactory);
    return $factoryList;
}

function getTransporterOfficials(Transporter $objFactory)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $factoryList = $objTms->getTransporterOfficials($objFactory);
    return $factoryList;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="FACTORY DELIVERY FUNCTIONS">
function insertFactoryDelivery(FactoryDeliveryDetails $objFactDelivery)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $factdeliveryid = $objTms->insertFactoryDelivery($objFactDelivery);
    return $factdeliveryid;
}

function updateFactoryDelivery(FactoryDeliveryDetails $objFactDelivery)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->updateFactoryDelivery($objFactDelivery);
    return $noOfRowsAffected;
}

function deleteFactoryDelivery(FactoryDeliveryDetails $objFactDelivery)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteFactoryDelivery($objFactDelivery);
    return $noOfRowsAffected;
}

function getFactoryDelivery(FactoryDeliveryDetails $objFactDelivery)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $factory_delivery = $objTms->getFactoryDelivery($objFactDelivery);
    //print_r($depots);
    return $factory_delivery;
}

function insertFactoryDeliveryHistory(FactoryDeliveryDetails $objFactDelivery)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $factdeliveryid = $objTms->insertFactoryDeliveryHistory($objFactDelivery);
    return $factdeliveryid;
}

function deleteFactoryDeliveryHistory(FactoryDeliveryDetails $objFactDelivery)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteFactoryDeliveryHistory($objFactDelivery);
    return $noOfRowsAffected;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="FACTORY PRODUCTION FUNCTIONS">
function insertFactoryProduction(FactoryProductionDetails $objFactProd)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $factprodid = $objTms->insertFactoryProduction($objFactProd);
    return $factprodid;
}

function updateFactoryProduction(FactoryProductionDetails $objFactProd)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->updateFactoryProduction($objFactProd);
    return $noOfRowsAffected;
}

function deleteFactoryProduction(FactoryProductionDetails $objFactProd)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteFactoryProduction($objFactProd);
    return $noOfRowsAffected;
}

function getFactoryProduction(FactoryProductionDetails $objFactProd)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $factprodlist = $objTms->getFactoryProduction($objFactProd);
    return $factprodlist;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="INDENT FUNCTIONS">
function insertIndent(Indent $objIndent)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $indentid = $objTms->insertIndent($objIndent);
    return $indentid;
}

function updateIndent(Indent $objIndent)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->updateIndent($objIndent);
    return $noOfRowsAffected;
}

function editIndent(Indent $objIndent)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->editIndent($objIndent);
    return $noOfRowsAffected;
}

function deleteIndent(Indent $objIndent)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteIndent($objIndent);
    return $noOfRowsAffected;
}

function getIndents(Indent $objIndent)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $indentlist = $objTms->get_indent($objIndent);
    return $indentlist;
}

function getIndentSkuMapping(Indent $objIndent)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $indentlist = $objTms->getIndentSkuMapping($objIndent);
    return $indentlist;
}

function getTransporterEff($effObj)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $eff = $objTms->getTransporterEff($effObj);
    return $eff;
}

function getZoneEff($effObj)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $eff = $objTms->getZoneEff($effObj);
    return $eff;
}

function getTransporterZoneEff($effObj)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $eff = $objTms->getTransporterZoneEff($effObj);
    return $eff;
}

function getFactoryEff($effObj)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $eff = $objTms->getFactoryEff($effObj);
    return $eff;
}

function getDaterequiredEff($effObj)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $eff = $objTms->getDaterequiredEff($effObj);
    return $eff;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="INDENT SKU MAPPING FUNCTIONS">
function insertIndentSkuMapping(IndentSkuMapping $objIndentSkuMapping)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $indent_sku_mappingid = $objTms->insertIndentSkuMapping($objIndentSkuMapping);
    return $indent_sku_mappingid;
}

function updateIndentSkuMapping(IndentSkuMapping $objIndentSkuMapping)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->updateIndentSkuMapping($objIndentSkuMapping);
    return $noOfRowsAffected;
}

function deleteIndentSkuMapping(IndentSkuMapping $objIndentSkuMapping)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteIndentSkuMapping($objIndentSkuMapping);
    return $noOfRowsAffected;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="PROPOSED INDENT FUNCTIONS">
function insertProposedIndent(ProposedIndent $objProposedIndent, ProposedIndentTransporterMapping $objPITMapping
    , $arrPISMapping) {
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $proposedindentid = $objTms->insertProposedIndent($objProposedIndent);
    $objPITMapping->proposedindentid = $proposedindentid;
    $pitmappingid = $objTms->insert_pitmapping($objPITMapping);
    foreach ($arrPISMapping as $objPISMapping) {
        $objPISMapping->proposedindentid = $proposedindentid;
        $pismappingid = $objTms->insert_piskumapping($objPISMapping);
    }
    return $proposedindentid;
}

function updateProposedIndent(ProposedIndent $objProposedIndent, ProposedIndentTransporterMapping $objPITMapping)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->updateProposedIndent($objProposedIndent);
    $noOfRowsAffectedPit = $objTms->update_pitmapping($objPITMapping);
    return $noOfRowsAffectedPit;
}

function approvedProposedIndent(ProposedIndent $objProposedIndent)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->updateProposedIndent($objProposedIndent);
    return $noOfRowsAffected;
}

function deleteProposedIndent(ProposedIndent $objProposedIndent)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteProposedIndent($objProposedIndent);
    return $noOfRowsAffected;
}

function getProposedIndent(ProposedIndent $objProposedIndent)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $proposedindentlist = $objTms->getProposedIndent($objProposedIndent);
    return $proposedindentlist;
}

function getProposedIndentSkuMapping(ProposedIndent $objProposedIndent)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $proposedindentlist = $objTms->getProposedIndentSkuMapping($objProposedIndent);
    return $proposedindentlist;
}

function getTransporterProposedIndent(ProposedIndentTransporterMapping $objProposedIndent)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $proposedindentlist = $objTms->getTransporterProposedIndent($objProposedIndent);
    return $proposedindentlist;
}

function rejectProposedIndent($objProposedIndent)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->rejectProposedIndent($objProposedIndent);
    return $noOfRowsAffectedPit;
}

function getPendingIndent(ProposedIndent $objProposedIndent)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $proposedindentlist = $objTms->getPendingIndent($objProposedIndent);
    return $proposedindentlist;
}

function getAutorejectIndent(ProposedIndent $objProposedIndent)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $proposedindentlist = $objTms->getAutorejectIndent($objProposedIndent);
    return $proposedindentlist;
}

function getAssignedTransporter(ProposedIndent $objProposedIndent)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $proposedindentlist = $objTms->getAssignedTransporter($objProposedIndent);
    return $proposedindentlist;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="ROUTE MASTER FUNCTIONS">
function insertRoutemaster(RouteMaster $objRouteMaster)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $routemasterid = $objTms->insertRoutemaster($objRouteMaster);
    return $routemasterid;
}

function updateRoutemaster(RouteMaster $objRouteMaster)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->updateRoutemaster($objRouteMaster);
    return $noOfRowsAffected;
}

function deleteRoutemaster(RouteMaster $objRouteMaster)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteRoutemaster($objRouteMaster);
    return $noOfRowsAffected;
}

function getRoutemaster(RouteMaster $objRouteMaster)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $routemasterlist = $objTms->getRoutemaster($objRouteMaster);
    return $routemasterlist;
}

function getRouteFilter(RouteMaster $objRouteMaster)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $routes = $objTms->getRouteFilter($objRouteMaster);
    //print_r($depots);
    return $routes;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="CHECKPOINTS IN ROUTE FUNCTIONS">
function insertRoutecheckpoint(RouteCheckpoint $objRouteCheckpoint)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $routecheckpointid = $objTms->insertRoutecheckpoint($objRouteCheckpoint);
    return $routecheckpointid;
}

function updateRoutecheckpoint(RouteCheckpoint $objRouteCheckpoint)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->updateRoutecheckpoint($objRouteCheckpoint);
    return $noOfRowsAffected;
}

function deleteRoutecheckpoint(RouteCheckpoint $objRouteCheckpoint)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteRoutecheckpoint($objRouteCheckpoint);
    return $noOfRowsAffected;
}

function getRoutecheckpoint(RouteCheckpoint $objRouteCheckpoint)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $routecheckpointlist = $objTms->getRoutecheckpoint($objRouteCheckpoint);
    return $routecheckpointlist;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="SKU FUNCTIONS">
function insertSku(Sku $objSku)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $skuid = $objTms->insertSku($objSku);
    return $skuid;
}

function updateSku(Sku $objSku)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->updateSku($objSku);
    return $noOfRowsAffected;
}

function deleteSku(Sku $objSku)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteSku($objSku);
    return $noOfRowsAffected;
}

function deleteLeftoverSku($objSku)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteLeftoverSku($objSku);
    return $noOfRowsAffected;
}

function getSku(Sku $objSku)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $skulist = $objTms->getSku($objSku);
    return $skulist;
}

function getSkutypeFilter(Sku $objSku)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $skutypes = $objTms->getSkutypeFilter($objSku);
    //print_r($depots);
    return $skutypes;
}

function getSkuFilter(Sku $objSku)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $skutypes = $objTms->getSkuFilter($objSku);
    //print_r($depots);
    return $skutypes;
}

function getSkuBytype(Sku $objSku)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $skutypes = $objTms->getSkuBytype($objSku);
    //print_r($depots);
    return $skutypes;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="VEHICLE TYPE FUNCTIONS">
function insertVehicletype(VehicleType $objVehicleType)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $vehicletypeid = $objTms->insertVehicletype($objVehicleType);
    return $vehicletypeid;
}

function updateVehicletype(VehicleType $objVehicleType)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->updateVehicletype($objVehicleType);
    return $noOfRowsAffected;
}

function deleteVehicletype(VehicleType $objVehicleType)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteVehicletype($objVehicleType);
    return $noOfRowsAffected;
}

function getVehicletypes(VehicleType $objVehicleType)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $vehicletypes = $objTms->getVehicletypes($objVehicleType);
    return $vehicletypes;
}

function getVehicletypeFilter(VehicleType $objVehicleType)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $locations = $objTms->getVehicletypeFilter($objVehicleType);
    //print_r($depots);
    return $locations;
}

function getVehtypeFilter(VehicleType $objVehicleType)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $locations = $objTms->getVehtypeFilter($objVehicleType);
    //print_r($depots);
    return $locations;
}

function insertVehtypetransporterMapping(VehicleType $objVehicleType)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $vehicletypeid = $objTms->insertVehtypetransporterMapping($objVehicleType);
    //return $vehicletypeid;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="VEHICLE FUNCTIONS">
function insertVehicle(Vehicle $objVehicle)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $vehicletypeid = $objTms->insertVehicle($objVehicle);
    return $vehicletypeid;
}

function updateVehicle(Vehicle $objVehicle)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->updateVehicle($objVehicle);
    return $noOfRowsAffected;
}

function deleteVehicle(Vehicle $objVehicle)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteVehicle($objVehicle);
    return $noOfRowsAffected;
}

function getVehicles(Vehicle $objVehicle)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $vehiclelist = $objTms->getVehicles($objVehicle);
    return $vehiclelist;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="TRANSPORTER FUNCTIONS">
function insertTransporter(Transporter $objTransporter, $arrVehicleTypes)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $transporterid = $objTms->insertTransporter($objTransporter);
    if ($transporterid != 0) {
        foreach ($arrVehicleTypes as $vehtype) {
            $objVehicleType = new VehicleType();
            $objVehicleType->customerno = $_SESSION['customerno'];
            $objVehicleType->vehicletypeid = $vehtype;
            $objVehicleType->transporterid = $transporterid;
            insertVehtypetransporterMapping($objVehicleType);
        }
    }
    return $transporterid;
}

function updateTransporter(Transporter $objTransporter, $arrVehicleTypes)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->updateTransporter($objTransporter);
    $objVehicle = new VehicleType();
    $objVehicle->vehtypemappingid = '';
    $objVehicle->transporterid = $objTransporter->transporterid;
    $objTms->delete_vehtypetransporter_mapping($objVehicle);
    foreach ($arrVehicleTypes as $vehtype) {
        $objVehicleType = new VehicleType();
        $objVehicleType->customerno = $objTransporter->customerno;
        $objVehicleType->vehicletypeid = $vehtype;
        $objVehicleType->transporterid = $objTransporter->transporterid;
        insertVehtypetransporterMapping($objVehicleType);
    }
    return $noOfRowsAffected;
}

function deleteTransporter(Transporter $objTransporter)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteTransporter($objTransporter);
    return $noOfRowsAffected;
}

function getTransporter(Transporter $objTransporter)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $transporterlist = $objTms->getTransporter($objTransporter);
    return $transporterlist;
}

function getTransporterFilter(Transporter $objTransporter)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $transporterlist = $objTms->getTransporterFilter($objTransporter);
    //print_r($depots);
    return $transporterlist;
}

function getTransporterFilterByzone(Transporter $objTransporter)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $transporterlist = $objTms->getTransporterFilterByzone($objTransporter);
    //print_r($depots);
    return $transporterlist;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="TRANSPORTER SHARE FUNCTIONS">
function insertTransportershare(TransporterShare $objTransporterShare)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $transportershareid = $objTms->insertTransportershare($objTransporterShare);
    return $transportershareid;
}

function updateTransportershare(TransporterShare $objTransporterShare)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->updateTransportershare($objTransporterShare);
    return $noOfRowsAffected;
}

function deleteTransportershare(TransporterShare $objTransporterShare)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $noOfRowsAffected = $objTms->deleteTransportershare($objTransporterShare);
    return $noOfRowsAffected;
}

function getTransportershare(TransporterShare $objTransporterShare)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $transporterlist = $objTms->getTransportershare($objTransporterShare);
    return $transporterlist;
}

function getTransportershareFilter(TransporterShare $objTransporterShare)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $locations = $objTms->getTransporterFilter($objTransporter);
    //print_r($depots);
    return $locations;
}

function getTransporteractualshare(TransporterActualShare $objTransporterActualShare)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $transporterlist = $objTms->getTransporteractualshare($objTransporterActualShare);
    return $transporterlist;
}

function updateTransporteractualshare(TransporterActualShare $objTransActualShare)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    //Update actual share once the indent is created
    $objTms->updateTransporteractualshare($objTransActualShare);
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="ALGO  FUNCTIONS">
function getSkuweight($objSku)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $skulist = $objTms->getSkuweight($objSku);
    return $skulist;
}

function getSkuweightFactorydepot($objSku)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $skulist = $objTms->getSkuweightFactorydepot($objSku);
    return $skulist;
}

function getVehtypetransporterMapping($objVehTypeTransMapping)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $skulist = $objTms->getVehtypetransporterMapping($objVehTypeTransMapping);
    return $skulist;
}

function insertLeftover($objLeftOver)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $leftOverId = $objTms->insertLeftover($objLeftOver);
    $arrLeftOverSKU = $objLeftOver->leftOverSku;
    foreach ($arrLeftOverSKU as $objSKULeftOver) {
        $objSKULeftOver->leftOverId = $leftOverId;
        $skuLeftOverMappingid = $objTms->insert_leftoverSkuMapping($objSKULeftOver);
    }
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="IMPORT FUNCTIONS">
function uploadLocations($all_form)
{
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $skipped = 0;
    $added = 0;
    foreach ($all_form as $form) {
        $objLocation = new Location();
        $objLocation->locationname = GetSafeValueString($form['locationname'], "string");
        $objLocation->customerno = GetSafeValueString($_SESSION['customerno'], "int");
        $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
        $objTms->insertLocation($objLocation);
        $added++;
    }
    return array(
        'added' => $added,
        'skipped' => $skipped,
    );
}

function uploadRoute($all_form)
{
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $skipped = 0;
    $added = 0;
    foreach ($all_form as $form) {
        $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
        $objDepot = new Depot();
        $objDepot->customerno = $customerno;
        $objDepot->depotname = $form['todepot'];
        $depots = $objTms->get_depots_like($objDepot);
        $objFactory = new Factory();
        $objFactory->customerno = $customerno;
        $objFactory->factoryname = $form['fromplant'];
        $factory = $objTms->get_factories_like($objFactory);
        $objRouteMaster = new RouteMaster();
        $objRouteMaster->routename = GetSafeValueString($form['routecode'], "string");
        $objRouteMaster->routedescription = GetSafeValueString($form['routedescription'], "string");
        $objRouteMaster->fromlocationid = $factory[0]['factoryid'];
        $objRouteMaster->tolocationid = $depots[0]['depotid'];
        $objRouteMaster->distance = GetSafeValueString($form['distance'], "string");
        $objRouteMaster->travellingtime = GetSafeValueString($form['time'], "string");
        $objRouteMaster->customerno = GetSafeValueString($_SESSION['customerno'], "int");
        //print_r($objRouteMaster);
        $objTms->insertRoutemaster($objRouteMaster);
        $added++;
    }
    return array(
        'added' => $added,
        'skipped' => $skipped,
    );
}

function uploadRouteCheckpoint($all_form)
{
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $skipped = 0;
    $added = 0;
    foreach ($all_form as $form) {
        $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
        $objLocation = new Location();
        $objLocation->customerno = $customerno;
        $objLocation->locationname = $form['tolocation'];
        $locations = $objTms->get_locations_like($objLocation);
        $objFactory = new Factory();
        $objFactory->customerno = $customerno;
        $objFactory->factoryname = $form['fromplant'];
        $factory = $objTms->get_factories_like($objFactory);
        $objRoute = new RouteMaster();
        $objRoute->customerno = $customerno;
        $objRoute->routedescription = $form['routedescription'];
        $route = $objTms->get_routemaster_like($objRoute);
        $objRouteChk = new RouteCheckpoint();
        $objRouteChk->routemasterid = $route[0]['routemasterid'];
        $objRouteChk->fromlocationid = $factory[0]['factoryid'];
        $objRouteChk->tolocationid = $locations[0]['locationid'];
        $objRouteChk->distance = GetSafeValueString($form['distance'], "string");
        $objRouteChk->customerno = GetSafeValueString($_SESSION['customerno'], "int");
        //print_r($objRouteChk);
        $objTms->insertRoutecheckpoint($objRouteChk);
        $added++;
    }
    return array(
        'added' => $added,
        'skipped' => $skipped,
    );
}

function uploadSku($all_form)
{
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $skipped = 0;
    $added = 0;
    foreach ($all_form as $form) {
        $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
        $objSku = new sku();
        $objSku1 = new sku();
        $objSku1->customerno = $customerno;
        $objSku1->type = GetSafeValueString($form['type'], "string");
        $skutype = $objTms->get_skutype_like($objSku1);
        $objSku->customerno = $customerno;
        $objSku->skucode = GetSafeValueString($form['skucode'], "string");
        $objSku->sku_description = GetSafeValueString($form['description'], "string");
        $objSku->type = $skutype[0]['tid'];
        $objSku->volume = GetSafeValueString($form['volume'], "string");
        $objSku->weight = GetSafeValueString($form['weight'], "string");
        $objSku->netgross = GetSafeValueString($form['netgross'], "string");
        $objTms->insertSku($objSku);
        $added++;
    }
    return array(
        'added' => $added,
        'skipped' => $skipped,
    );
}

function uploadFacrotyDelivery($all_form)
{
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $skipped = 0;
    $added = 0;
    foreach ($all_form as $form) {
        $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
        $objFactory = new Factory();
        $objFactory->customerno = $customerno;
        $objFactory->factoryname = $form['factory'];
        $factory = $objTms->get_factories_like($objFactory);
        $objDepot = new Depot();
        $objDepot->customerno = $customerno;
        $objDepot->depotname = $form['depot'];
        $depots = $objTms->get_depots_like($objDepot);
        $objSku = new Sku();
        $objSku->customerno = $customerno;
        $objSku->skucode = $form['sku'];
        $skus = $objTms->get_sku_like($objSku);
        $objFactoryDelivery = new FactoryDeliveryDetails();
        $objFactoryDelivery->customerno = $customerno;
        $objFactoryDelivery->factoryid = $factory[0]['factoryid'];
        $objFactoryDelivery->skuid = $skus[0]['skuid'];
        $objFactoryDelivery->depotid = $depots[0]['depotid'];
        $objFactoryDelivery->date_required = date_maker($form['daterequired']);
        $objFactoryDelivery->weight = GetSafeValueString($form['weight'], "string");
        //print_r($objFactoryDelivery);
        $objTms->insertFactoryDelivery($objFactoryDelivery);
        $added++;
    }
    return array(
        'added' => $added,
        'skipped' => $skipped,
    );
}

function uploadFacrotyPrduction($all_form)
{
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $skipped = 0;
    $added = 0;
    foreach ($all_form as $form) {
        $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
        $objFactory = new Factory();
        $objFactory->customerno = $customerno;
        $objFactory->factoryname = $form['factory'];
        $factory = $objTms->get_factories_like($objFactory);
        $objSku = new Sku();
        $objSku->customerno = $customerno;
        $objSku->skucode = $form['sku'];
        $skus = $objTms->get_sku_like($objSku);
        $objFactoryProduction = new FactoryProductionDetails();
        $objFactoryProduction->customerno = $customerno;
        $objFactoryProduction->factoryid = $factory[0]['factoryid'];
        $objFactoryProduction->skuid = $skus[0]['skuid'];
        $objFactoryProduction->weight = GetSafeValueString($form['weight'], "string");
        //print_r($objFactoryProduction);
        $objTms->insertFactoryProduction($objFactoryProduction);
        $added++;
    }
    return array(
        'added' => $added,
        'skipped' => $skipped,
    );
}

function uploadDepots($all_form)
{
    $customerno = $_SESSION['customerno'];
    $userid = $_SESSION['userid'];
    $skipped = 0;
    $added = 0;
    foreach ($all_form as $form) {
        $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
        $objZone = new Zone();
        $objZone->zonename = GetSafeValueString($form['zonename'], "string");
        $objZone->customerno = GetSafeValueString($_SESSION['customerno'], "int");
        $zone = $objTms->get_zone_filter($objZone);
        $objFactory = new Factory();
        $objFactory->customerno = $customerno;
        $objFactory->factoryname = $form['factoryname'];
        $factory = $objTms->get_factories_like($objFactory);
        $objDepot = new Depot();
        $objDepot->depotcode = GetSafeValueString($form['depotcode'], "string");
        $objDepot->depotname = GetSafeValueString($form['depotname'], "string");
        $objDepot->zoneid = $zone[0][zoneid];
        $objDepot->multidrop = GetSafeValueString($form['multidrop'], "string");
        $objDepot->customerno = GetSafeValueString($_SESSION['customerno'], "int");
        $depotid = $objTms->insertDepot($objDepot);
        if ($depotid != 0 && $objDepot->multidrop == 1) {
            $depotids = array();
            $multidepots = explode(',', $form['multidepots']);
            if (isset($multidepots) && !empty($multidepots)) {
                foreach ($multidepots as $depot) {
                    $objdepot = new Depot();
                    $objdepot->customerno = $_SESSION['customerno'];
                    $objdepot->depotname = $depot;
                    $depots = $objTms->get_depots_like($objdepot);
                    if (isset($depots) && !empty($depots)) {
                        $depotids[] = $depots[0][depotid];
                    }
                }
                asort($depotids);
                $depotstring = implode(',', $depotids);
                $objdepotmap = new Depot();
                $objdepotmap->depotid = $depotid;
                $objdepotmap->factoryid = $factory[0][factoryid];
                $objdepotmap->multidropid = $depotstring;
                $objdepotmap->customerno = $_SESSION['customerno'];
                insertMultidepotMapping($objdepotmap);
            }
        }
        $added++;
    }
    return array(
        'added' => $added,
        'skipped' => $skipped,
    );
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="USER FUNCTIONS">
function getusersbygrp()
{
    $usermanager = new UserManager();
    $users = $usermanager->getusersforcustomerbygrp($_SESSION['customerno']);
    return $users;
}

function deluser($uid)
{
    $usermanager = new UserManager();
    $userid = GetSafeValueString($uid, 'string');
    $usermanager->DeleteUser($userid, $_SESSION['customerno'], $_SESSION['userid']);
}

function getuser($uid)
{
    $usermanager = new UserManager();
    $userid = GetSafeValueString($uid, 'string');
    $user = $usermanager->get_user($_SESSION['customerno'], $userid);
    return $user;
}

function gettmsuser($uid, $role, $customerno)
{
    $usermanager = new UserManager();
    $user = $usermanager->get_usertms($uid, $role, $customerno);
    return $user;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="LEFT OVER SKU FUNCTIONS">
function getLeftoverDetails($objleftover)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $leftOvers = $objTms->getLeftoverDetails($objleftover);
    return $leftOvers;
}

function getLeftoverSku($objleftover)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $leftOvers = $objTms->getLeftoverSku($objleftover);
    return $leftOvers;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="VENDOR PAYABLE FUNCTIONS">
function getBilltypeFilter()
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $billtypes = $objTms->getBilltypeFilter();
    return $billtypes;
}

function getVehiclestypeFilter()
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $vehicletypes = $objTms->getVehiclestypeFilter();
    return $vehicletypes;
}

function getMovementFilter()
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $movementtypes = $objTms->getMovementFilter($objSku);
    return $movementtypes;
}

function getPaymentBucket()
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $paymentbucket = $objTms->getPaymentBucket($objSku);
    return $paymentbucket;
}

function uploadShipmentData($all_form)
{
    $skippedArray = array();
    $skipped = 0;
    $added = 0;
    foreach ($all_form as $form) {
        $deliveryno = trim(GetSafeValueString($form["Delivery No"], "string"));
        if (checkdeliverynoExists($deliveryno) || $deliveryno == '') {
            $skipped++;
            $skippedArray[] = $form;
            continue;
        }
        $shipmentdata = new stdClass();
        $shipmentdata->deliveryno = GetSafeValueString($form["Delivery No"], "string");
        $shipmentdata->lrno = GetSafeValueString($form["LR No"], "string");
        $shipmentdata->shipmentno = GetSafeValueString($form["Shipment No"], "string");
        $shipmentdata->costdocumentno = GetSafeValueString($form["Cost Document No"], "string");
        $shipmentdata->trucktype = GetSafeValueString($form["Truck Type"], "string");
        $shipmentdata->route = GetSafeValueString($form["Route"], "string");
        $shipmentdata->vehicleno = GetSafeValueString($form["Vehicle No"], "string");
        insertShipmentData($shipmentdata);
        $added++;
    }
    return array(
        'added' => $added,
        'skipped' => $skipped,
        'skippedData' => $skippedArray,
    );
}

function uploadPaymentData($all_form)
{
    $skippedArray = array();
    $skipped = 0;
    $added = 0;
    foreach ($all_form as $form) {
        $documentno = trim(GetSafeValueString($form["Document No"], "string"));
        if (checkdocumentnoExists($documentno) || $documentno == '') {
            $skipped++;
            $skippedArray[] = $form;
            continue;
        }
        $objClearingDate = new DateTime($form["Clearing Date"]);
        $paymentdata = new stdClass();
        $paymentdata->vendorcode = GetSafeValueString($form["Vendor Code"], "string");
        $paymentdata->billno = GetSafeValueString($form["Document No"], "string");
        $paymentdata->clearingdocno = GetSafeValueString($form["Clearing Document No"], "string");
        $paymentdata->clearingdate = $objClearingDate->format(DATEFORMAT_YMD);
        $paymentdata->refno = GetSafeValueString($form["Reference No"], "string");
        $paymentdata->paymentstatus = GetSafeValueString($form["Payment Status"], "string");
        insertPaymentData($paymentdata);
        $added++;
    }
    return array(
        'added' => $added,
        'skipped' => $skipped,
        'skippedData' => $skippedArray,
    );
}

function checkdeliverynoExists($deliveryno)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $isExists = $objTms->checkdeliverynoExists($deliveryno);
    return $isExists;
}

function checkdocumentnoExists($documentno)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $isExists = $objTms->checkdocumentnoExists($documentno);
    return $isExists;
}

function insertShipmentData($shipmentdata)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $isInserted = $objTms->insertShipmentData($shipmentdata);
    return $isInserted;
}

function insertPaymentData($paymentdata)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $isInserted = $objTms->insertPaymentData($paymentdata);
    return $isInserted;
}

function getDeliveryDetails($objlrCheck)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $isExists = $objTms->getDeliveryDetails($objlrCheck);
    return $isExists;
}

function insertBillDetailsDraft($objBill)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $billId = $objTms->insertBillDetailsDraft($objBill);
    return $billId;
}

function updateBillDetailsDraft($objBill)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $billId = $objTms->updateBillDetailsDraft($objBill);
    return $billId;
}

function updateBillDetails($objBill)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $billId = $objTms->updateBillDetails($objBill);
    return $billId;
}

function insertLrDetailsDraft($objLr)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $billId = $objTms->insertLrDetailsDraft($objLr);
    return $billId;
}

function insertLrDetails($objLr)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $billId = $objTms->insertLrDetails($objLr);
    return $billId;
}

function deleteLrDetailsDraft($objLr)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $billId = $objTms->deleteLrDetailsDraft($objLr);
    return $billId;
}

function deleteLrDetails($objLr)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $billId = $objTms->deleteLrDetails($objLr);
    return $billId;
}

function deleteBillDraft($objLr)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $billId = $objTms->deleteBillDraft($objLr);
    return $billId;
}

function deleteBill($objLr)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $billId = $objTms->deleteBill($objLr);
    return $billId;
}

function getLrDetailsDraft($objLr)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $lrDetails = $objTms->getLrDetailsDraft($objLr);
    return $lrDetails;
}

function getLrDetails($objLr)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $lrDetails = $objTms->getLrDetails($objLr);
    return $lrDetails;
}

function updateLrDetailsDraft($objLr)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $billId = $objTms->updateLrDetailsDraft($objLr);
    return $billId;
}

function updateLrDetails($objLr)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $billId = $objTms->updateLrDetails($objLr);
    return $billId;
}

function getBillDetailsDraft($objBill)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $bills = $objTms->getBillDetailsDraft($objBill);
    return $bills;
}

function getBillDetails($objBill)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $bills = $objTms->getBillDetails($objBill);
    return $bills;
}

function insertBilltracker($objBill)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $bills = $objTms->insertBilltracker($objBill);
    return $bills;
}

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="HELPER FUNCTIONS">
function getSMSCount($customerno)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $smscount = $objTms->getSMSCount($customerno);
    return $smscount;
}

function updateSMSCount($existingSMSCount, $smsmessage, $customerno)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $smscount = $objTms->updateSMSCount($existingSMSCount, $smsmessage, $customerno);
    return $smscount;
}

function sendMailPHPMAILER(array $arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName)
{
    include_once "../cron/class.phpmailer.php";
    $isEmailSent = 0;
    $completeFilePath = '';
    if ($attachmentFilePath != '' && $attachmentFileName != '') {
        $completeFilePath = $attachmentFilePath . "/" . $attachmentFileName;
    }
    $mail = new PHPMailer();
    $mail->IsMail();
    /* Clear Email Addresses */
    $mail->ClearAddresses();
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();
    $mail->ClearCustomHeaders();
    //unset($arrToMailIds);
    //$arrToMailIds = array('sshrikanth@elixiatech.com', 'mrudangvora@gmail.com', 'shrisurya24@gmail.com');
    //$strCCMailIds = '';
    if (!empty($arrToMailIds)) {
        foreach ($arrToMailIds as $mailto) {
            $mail->AddAddress($mailto);
        }
        if (!empty($strCCMailIds)) {
            $mail->AddCustomHeader("CC: " . $strCCMailIds);
        }
        if (!empty($strBCCMailIds)) {
            $mail->AddCustomHeader("BCC: " . $strBCCMailIds);
        }
    }
    $mail->From = "noreply@elixiatech.com";
    $mail->FromName = "Elixia Speed";
    $mail->Sender = "noreply@elixiatech.com";
    //$mail->AddReplyTo($from,"Elixia Speed");
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->IsHtml(true);
    if ($completeFilePath != '' && filename != '') {
        $mail->AddAttachment($completeFilePath, $attachmentFileName);
    }
    //SEND Mail
    if ($mail->Send()) {
        $isEmailSent = 1; // or use booleans here
    }
    /* Clear Email Addresses */
    $mail->ClearAddresses();
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();
    $mail->ClearCustomHeaders();
    return $isEmailSent;
}

function sendMail(array $arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName)
{
    // multiple recipients
    $isEmailSent = 0;
    $headers = '';
    $to = implode(',', $arrToMailIds);
    /*
    $to = 'sshrikanth@elixiatech.com' . ', '; // note the comma
    $to .= 'mrudangvora@gmail.com';
    $to .= 'shrisurya24@gmail.com';
     *
     */
    // To send HTML mail, the Content-type header must be set
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    // Additional headers
    $headers .= 'From: Elixia-Noreply <noreply@elixiatech.com>' . "\r\n";
    $headers .= 'CC: ' . $strCCMailIds . '' . "\r\n";
    $headers .= 'BCC: ' . $strBCCMailIds . '' . "\r\n";
    // Mail it
    /*
    if (mail($to, $subject, $message, $headers)) {
    $isEmailSent = 1;
    }
     *
     */
    return $isEmailSent;
}

function sendSMS($phoneArray, $message, &$response)
{
    $isSMSSent = false;
    $countryCode = "91";
    $arrPhone = array();
    //unset($phoneArray);
    //$phoneArray = array('9892924121', '9021844677', '9969941084');
    //$phoneArray = array('9021844677');
    //print_r($phoneArray);
    if (is_array($phoneArray)) {
        foreach ($phoneArray as $phone) {
            $arrPhone[] = $countryCode . $phone;
        }
    } else {
        $arrPhone[] = $countryCode . $phoneArray;
    }
    $phone = implode(",", $arrPhone);
    $url = str_replace("{{PHONENO}}", urlencode($phone), SMS_URL);
    $url = str_replace("{{MESSAGETEXT}}", urlencode($message), $url);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    if ($response === false) {
        //echo 'Curl error: ' . curl_error($ch);
        $isSMSSent = false;
    } else {
        $isSMSSent = true;
    }
    curl_close($ch);
    return $isSMSSent;
}

function insertSMSLog($phone, $message, $response, $proposedindentid, $isSMSSent, $customerno, $todaysdate)
{
    $objTms = new TMS($_SESSION['customerno'], $_SESSION['userid']);
    $smscount = $objTms->insertSMSLog($phone, $message, $response, $proposedindentid, $isSMSSent, $customerno, $todaysdate);
    return $smscount;
}; // </editor-fold>; //
