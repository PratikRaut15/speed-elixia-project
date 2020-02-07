<?php

include_once 'tms_function.php';
extract($_REQUEST);
$error = 'Please enter the details properly';
// <editor-fold defaultstate="collapsed" desc="Page Actions  - Zone">
if ($action == 'add-zone') {
  $objZone = new Zone();
  $objZone->zonename = GetSafeValueString($_POST['zone_name'], "string");
  $objZone->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $zoneid = insert_zone($objZone);
  if ($zoneid != 0) {
    header("location: tms.php?pg=view-zone");
  }
}
if ($action == 'edit-zone') {
  $objZone = new Zone();
  $objZone->zoneid = GetSafeValueString($_POST['zoneid'], "int");
  $objZone->zonename = GetSafeValueString($_POST['zone_name'], "string");
  $objZone->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $zoneid = update_zone($objZone);
  if ($zoneid != 0) {
    header("location: tms.php?pg=view-zone");
  }
}
if ($action == 'del-zone') {
  $objZone = new Zone();
  $objZone->zoneid = GetSafeValueString($did, "int");
  $objZone->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $zoneid = delete_zone($objZone);
  if ($zoneid != 0) {
    header("location: tms.php?pg=view-zone");
  }
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Page Actions  - Location">
if ($action == 'add-location') {
  $objZone = new Location();
  $objZone->locationname = GetSafeValueString($_POST['location_name'], "string");
  $objZone->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $locationid = insert_location($objZone);
  if ($locationid != 0) {
    header("location: tms.php?pg=view-location");
  }
}
if ($action == 'edit-location') {
  $objLocation = new Location();
  $objLocation->locationname = GetSafeValueString($_POST['location_name'], "string");
  $objLocation->locationid = GetSafeValueString($_POST['locationid'], "int");
  $objLocation->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $locationid = update_location($objLocation);
  if ($locationid != 0) {
    header("location: tms.php?pg=view-location");
  }
}
if ($action == 'del-location') {
  $objZone = new Location();
  $objZone->locationid = GetSafeValueString($did, "int");
  $objZone->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $locationid = delete_location($objZone);
  if ($locationid != 0) {
    header("location: tms.php?pg=view-location");
  }
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Page Actions  - Depot">
if ($action == 'add-depot') {
  $Multipdepots = array();
  $objDepot = new Depot();
  $objDepot->depotcode = GetSafeValueString($_POST['depotcode'], "string");
  $objDepot->depotname = GetSafeValueString($_POST['depotname'], "string");
  $objDepot->zoneid = GetSafeValueString($_POST['zoneid'], "int");
  $objDepot->multidrop = GetSafeValueString($_POST['multidrop'], "int");
  $objDepot->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  if ($objDepot->multidrop == 1) {
    foreach ($_POST as $single_post_name => $single_post_value) {
      if (substr($single_post_name, 0, 14) == "to_multidepot_") {
        $Multipdepots[] = substr($single_post_name, 14, 15);
      }
    }
  }
  $depotid = insert_depot($objDepot);
  if ($depotid != 0) {
    if (isset($Multipdepots) && !empty($Multipdepots)) {
      asort($Multipdepots);
      $depotstring = implode(',', $Multipdepots);
      $objdepot = new Depot();
      $objdepot->depotid = $depotid;
      $objdepot->factoryid = GetSafeValueString($_POST['factoryid'], "int");
      $objdepot->multidropid = $depotstring;
      $objdepot->customerno = GetSafeValueString($_SESSION['customerno'], "int");
      insert_multidepot_mapping($objdepot);
    }
    header("location: tms.php?pg=view-depot");
  }
}
if ($action == 'edit-depot') {
  $Multipdepots = Array();
  $objDepot = new Depot();
  $objDepot->depotid = GetSafeValueString($_POST['depotid'], "string");
  $objDepot->depotcode = GetSafeValueString($_POST['depot_code'], "string");
  $objDepot->depotname = GetSafeValueString($_POST['depot_name'], "string");
  $objDepot->zoneid = GetSafeValueString($_POST['zoneid'], "int");
  $objDepot->multidrop = GetSafeValueString($_POST['multidrop'], "int");
  if ($objDepot->multidrop == 1) {
    foreach ($_POST as $single_post_name => $single_post_value) {
      if (substr($single_post_name, 0, 14) == "to_multidepot_") {
        $Multipdepots[] = substr($single_post_name, 14, 15);
      }
    }
  }
  $depotid = update_depots($objDepot);
  if ($depotid != 0) {
    if (isset($Multipdepots) && !empty($Multipdepots)) {
      delete_multidepot_mapping($objDepot);
      asort($Multipdepots);
      $depotstring = implode(',', $Multipdepots);
      $objdepot = new Depot();
      $objdepot->depotid = $_POST['depotid'];
      $objdepot->factoryid = GetSafeValueString($_POST['factoryid'], "int");
      $objdepot->multidropid = $depotstring;
      $objdepot->customerno = GetSafeValueString($_SESSION['customerno'], "int");
      insert_multidepot_mapping($objdepot);
    }
    header("location: tms.php?pg=view-depot");
  }
}
if ($action == 'del-depot') {
  $objDepot = new Depot();
  $objDepot->depotid = GetSafeValueString($did, "int");
  $depotid = delete_depot($objDepot);
  if ($depotid != 0) {
    delete_multidepot_mapping($objDepot);
    header("location: tms.php?pg=view-depot");
  }
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Page Actions  - FACTORY DELIEVRY">
if ($action == 'add-factory-delivery') {
  $locations = array();
  $objFact = new FactoryDeliveryDetails();
  $objFact->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $objFact->factoryid = GetSafeValueString($_POST['factoryid'], "int");
  $objFact->skuid = GetSafeValueString($_POST['skuid'], "int");
  $objFact->depotid = GetSafeValueString($_POST['depotid'], "int");
  $objFact->date_required = GetSafeValueString(date('Y-m-d', strtotime($_POST['date_required'])), "string");
  $objFact->weight = GetSafeValueString($_POST['weight'], "string");
  //print_r($objFact);
  if (!empty($_POST['factoryid']) && !empty($_POST['skuid']) && !empty($_POST['depotid']) && !empty($_POST['date_required']) && !empty($_POST['weight'])) {
    $factoryid = insert_factory_delivery($objFact);
    if ($factoryid != 0) {
      header("location: tms.php?pg=view-factory-delivery");
    }
  } else {
    //echo "here";
    header("location: tms.php?pg=view-factory-delivery&msg=$error");
  }
  //print_r($objFact);
}
if ($action == 'edit-factory-delivery') {
  $locations = array();
  $objFact = new FactoryDeliveryDetails();
  $objFact->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $objFact->fdid = GetSafeValueString($_POST['fdid'], "int");
  $objFact->factoryid = GetSafeValueString($_POST['factoryid'], "int");
  $objFact->skuid = GetSafeValueString($_POST['skuid'], "int");
  $objFact->depotid = GetSafeValueString($_POST['depotid'], "int");
  $objFact->date_required = GetSafeValueString(date('Y-m-d', strtotime($_POST['date_required'])), "string");
  $objFact->weight = GetSafeValueString($_POST['weight'], "string");
  $factoryid = update_factory_delivery($objFact);
  if ($factoryid != 0) {
    header("location: tms.php?pg=view-factory-delivery");
  }
}
if ($action == 'del-factory-delivery') {
  $objFact = new FactoryDeliveryDetails();
  $objFact->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $objFact->fdid = $did;
  $factoryid = delete_factory_delivery($objFact);
  if ($factoryid != 0) {
    header("location: tms.php?pg=view-factory-delivery");
  }
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Page Actions  - FACTORY / Plant">
if ($action == 'add-plant') {
  $objFact = new Factory();
  $objFact->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $objFact->factorycode = GetSafeValueString($_POST['plantcode'], "string");
  $objFact->factoryname = GetSafeValueString($_POST['plantname'], "string");
  $objFact->zoneid = GetSafeValueString($_POST['zoneid'], "int");
  $factoryid = insert_factory($objFact);
  if ($factoryid != 0) {
    header("location: tms.php?pg=view-plant");
  }
}
if ($action == 'edit-plant') {
  $objFact = new Factory();
  $objFact->factoryid = GetSafeValueString($_POST['factoryid'], "string");
  $objFact->factorycode = GetSafeValueString($_POST['factorycode'], "string");
  $objFact->factoryname = GetSafeValueString($_POST['factoryname'], "string");
  $objFact->zoneid = GetSafeValueString($_POST['zoneid'], "int");
  $factoryid = update_factory($objFact);
  if ($factoryid != 0) {
    header("location: tms.php?pg=view-plant");
  }
}
if ($action == 'del-plant') {
  $objFact = new Factory();
  $objFact->factoryid = GetSafeValueString($did, "string");
  $factoryid = delete_factory($objFact);
  if ($factoryid != 0) {
    header("location: tms.php?pg=view-plant");
  }
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Page Actions  - Vehicle Type">
if ($action == 'add-vehicle-type') {
  $objVehicle = new VehicleType();
  $objVehicle->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $objVehicle->vehiclecode = GetSafeValueString($_POST['vehiclecode'], "string");
  $objVehicle->vehicledescription = GetSafeValueString($_POST['vehicledescription'], "string");
  $objVehicle->skutypeid = GetSafeValueString($_POST['typeid'], "string");
  $objVehicle->volumecapacity = GetSafeValueString($_POST['volume_m'], "string");
  $objVehicle->weightcapacity = GetSafeValueString($_POST['volume_kg'], "string");
  $vehicletypeid = insert_vehicletype($objVehicle);
  if ($vehicletypeid != 0) {
    header("location: tms.php?pg=view-vehicle-type");
  }
}
if ($action == 'edit-vehicle-type') {
  $objVehicle = new VehicleType();
  $objVehicle->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $objVehicle->vehicletypeid = GetSafeValueString($_POST['vehicletypeid'], "string");
  $objVehicle->vehiclecode = GetSafeValueString($_POST['code'], "string");
  $objVehicle->vehicledescription = GetSafeValueString($_POST['description'], "string");
  $objVehicle->skutypeid = GetSafeValueString($_POST['typeid'], "string");
  $objVehicle->volumecapacity = GetSafeValueString($_POST['volume'], "string");
  $objVehicle->weightcapacity = GetSafeValueString($_POST['weight'], "string");
  //print_r($objVehicle);
  $vehicletypeid = update_vehicletype($objVehicle);
  if ($vehicletypeid != 0) {
    header("location: tms.php?pg=view-vehicle-type");
  }
}
if ($action == 'del-vehicle-type') {
  $objVehicle = new VehicleType();
  $objVehicle->vehicletypeid = GetSafeValueString($did, "int");
  $vehicletypeid = delete_vehicletype($objVehicle);
  if ($vehicletypeid != 0) {
    header("location: tms.php?pg=view-vehicle-type");
  }
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Page Actions  - Transporter">
if ($action == 'add-transporter') {
  $objTransporter = new Transporter();
  $objTransporter->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $objTransporter->transportercode = GetSafeValueString($_POST['transportercode'], "string");
  $objTransporter->transportername = GetSafeValueString($_POST['transportername'], "string");
  $objTransporter->transportermail = GetSafeValueString($_POST['email'], "string");
  $objTransporter->transportermobileno = GetSafeValueString($_POST['mobile'], "string");
  $arrVehiclesTypes = array();
  foreach ($_POST as $single_post_name => $single_post_value) {
    if (substr($single_post_name, 0, 11) == "to_vehicle_")
      $arrVehiclesTypes[] = substr($single_post_name, 11, 12);
  }
  $transporterid = insert_transporter($objTransporter, $arrVehiclesTypes);
  echo $transporterid;
}
if ($action == 'edit-transporter') {
  $objTransporter = new Transporter();
  $objTransporter->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $objTransporter->transporterid = GetSafeValueString($_POST['transporterid'], "string");
  $objTransporter->transportercode = GetSafeValueString($_POST['transporter_code'], "string");
  $objTransporter->transportername = GetSafeValueString($_POST['transporter_name'], "string");
  $objTransporter->transportermail = GetSafeValueString($_POST['transporter_mail'], "string");
  $objTransporter->transportermobileno = GetSafeValueString($_POST['transporter_mobile'], "string");
  $arrVehiclesTypes = array();
  foreach ($_POST as $single_post_name => $single_post_value) {
    if (substr($single_post_name, 0, 11) == "to_vehicle_")
      $arrVehiclesTypes[] = substr($single_post_name, 11, 12);
  }
  $transporterid = update_transporter($objTransporter, $arrVehiclesTypes);
  echo $transporterid;
}
if ($action == 'del-transporter') {
  $objTransporter = new Transporter();
  $objTransporter->transporterid = GetSafeValueString($did, "string");
  $transporterid = delete_transporter($objTransporter);
  if ($transporterid != 0) {
    header("location: tms.php?pg=view-transporter");
  }
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Page Actions  - Vehicle">
if ($action == 'add-vehicle') {
  $objVehicle = new Vehicle();
  $objVehicle->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $objVehicle->vehicleno = GetSafeValueString($_POST['vehicleno'], "string");
  $objVehicle->vehicletypeid = GetSafeValueString($_POST['vehicletypeid'], "int");
  $objVehicle->transporterid = GetSafeValueString($_POST['transporterid'], "int");
  $vehicleid = insert_vehicle($objVehicle);
  if ($vehicleid != 0) {
    header("location: tms.php?pg=view-vehicle");
  }
}
if ($action == 'edit-vehicle') {
  $objVehicle = new Vehicle();
  $objVehicle->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $objVehicle->vehicleid = GetSafeValueString($_POST['vehicleid'], "string");
  $objVehicle->vehicleno = GetSafeValueString($_POST['vehicleno'], "string");
  $objVehicle->vehicletypeid = GetSafeValueString($_POST['vehicletypeid'], "int");
  $objVehicle->transporterid = GetSafeValueString($_POST['transporterid'], "int");
  $vehicleid = update_vehicle($objVehicle);
  if ($vehicleid != 0) {
    header("location: tms.php?pg=view-vehicle");
  }
}
if ($action == 'del-vehicle') {
  $objVehicle = new Vehicle();
  $objVehicle->vehicleid = GetSafeValueString($did, "int");
  $vehicleid = delete_vehicle($objVehicle);
  if ($vehicleid != 0) {
    header("location: tms.php?pg=view-vehicle");
  }
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Page Actions  - Share">
if ($action == 'add-share') {
  $objTransporterShare = new TransporterShare();
  $objTransporterShare->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $objTransporterShare->transporterid = GetSafeValueString($_POST['transporterid'], "int");
  $objTransporterShare->factoryid = GetSafeValueString($_POST['factoryid'], "int");
  $objTransporterShare->zoneid = GetSafeValueString($_POST['zoneid'], "int");
  $objTransporterShare->sharepercent = GetSafeValueString($_POST['sharepercent'], "string");
  $shareid = insert_transportershare($objTransporterShare);
  if ($shareid != 0) {
    header("location: tms.php?pg=view-share");
  }
}
if ($action == 'edit-share') {
  $objTransporterShare = new TransporterShare();
  $objTransporterShare->transportershareid = GetSafeValueString($_POST['transportershareid'], "string");
  $objTransporterShare->transporterid = GetSafeValueString($_POST['transporterid'], "string");
  $objTransporterShare->factoryid = GetSafeValueString($_POST['factoryid'], "string");
  $objTransporterShare->zoneid = GetSafeValueString($_POST['zoneid'], "string");
  $objTransporterShare->sharepercent = GetSafeValueString($_POST['sharepercent'], "string");
  $shareid = update_transportershare($objTransporterShare);
  //if ($shareid != 0) {
  header("location: tms.php?pg=view-share");
  //}
}
if ($action == 'del-share') {
  $objTransporterShare = new TransporterShare();
  $objTransporterShare->transportershareid = GetSafeValueString($did, "int");
  $shareid = delete_transportershare($objTransporterShare);
  //if ($shareid != 0) {
  header("location: tms.php?pg=view-share");
  //}
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Page Actions  - RouteMaster">
if ($action == 'add-route') {
  $objRouteMaster = new RouteMaster();
  $objRouteMaster->routename = GetSafeValueString($_POST['routecode'], "string");
  $objRouteMaster->routedescription = GetSafeValueString($_POST['routedescription'], "string");
  $objRouteMaster->fromlocationid = GetSafeValueString($_POST['fromlocationid'], "string");
  $objRouteMaster->tolocationid = GetSafeValueString($_POST['tolocationid'], "string");
  $objRouteMaster->distance = GetSafeValueString($_POST['routedistance'], "string");
  $objRouteMaster->travellingtime = GetSafeValueString($_POST['travellingtime'], "string");
  $objRouteMaster->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $factoryid = insert_routemaster($objRouteMaster);
  if ($factoryid != 0) {
    header("location: tms.php?pg=view-route");
  }
}
if ($action == 'edit-route') {
  $objRouteMaster = new RouteMaster();
  $objRouteMaster->routemasterid = GetSafeValueString($_POST['routemasterid'], "string");
  $objRouteMaster->routename = GetSafeValueString($_POST['routecode'], "string");
  $objRouteMaster->routedescription = GetSafeValueString($_POST['routedescription'], "string");
  $objRouteMaster->fromlocationid = GetSafeValueString($_POST['fromlocationid'], "string");
  $objRouteMaster->tolocationid = GetSafeValueString($_POST['tolocationid'], "string");
  $objRouteMaster->distance = GetSafeValueString($_POST['routedistance'], "string");
  $objRouteMaster->travellingtime = GetSafeValueString($_POST['travellingtime'], "string");
  $objRouteMaster->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $factoryid = update_routemaster($objRouteMaster);
  if ($factoryid != 0) {
    header("location: tms.php?pg=view-route");
  }
}
if ($action == 'del-route') {
  $objRouteMaster = new RouteMaster();
  $objRouteMaster->routemasterid = GetSafeValueString($did, "string");
  $routemasterid = delete_routemaster($objRouteMaster);
  if ($routemasterid != 0) {
    header("location: tms.php?pg=view-route");
  }
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Page Actions  - Route Checkpoint">
if ($action == 'add-routecheckpoint') {
  $objRouteChk = new RouteCheckpoint();
  $objRouteChk->routemasterid = GetSafeValueString($_POST['routemasterid'], "string");
  $objRouteChk->fromlocationid = GetSafeValueString($_POST['fromlocationid'], "string");
  $objRouteChk->tolocationid = GetSafeValueString($_POST['tolocationid'], "string");
  $objRouteChk->distance = GetSafeValueString($_POST['routedistance'], "string");
  $objRouteChk->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  //print_r($objRouteChk);
  $routecheckpointid = insert_routecheckpoint($objRouteChk);
  if ($routecheckpointid != 0) {
    header("location: tms.php?pg=view-routemanager");
  }
}
if ($action == 'edit-routecheckpoint') {
  $objRouteChk = new RouteCheckpoint();
  $objRouteChk->routecheckpointid = GetSafeValueString($_POST['routecheckpointid'], "string");
  $objRouteChk->routemasterid = GetSafeValueString($_POST['routemasterid'], "string");
  $objRouteChk->fromlocationid = GetSafeValueString($_POST['fromlocationid'], "string");
  $objRouteChk->tolocationid = GetSafeValueString($_POST['tolocationid'], "string");
  $objRouteChk->distance = GetSafeValueString($_POST['routedistance'], "string");
  $objRouteChk->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  //print_r($objRouteChk);
  $routecheckpointid = update_routecheckpoint($objRouteChk);
  if ($routecheckpointid != 0) {
    header("location: tms.php?pg=view-routemanager");
  }
}
if ($action == 'del-routemanager') {
  $objRouteChk = new RouteCheckpoint();
  $objRouteChk->routecheckpointid = GetSafeValueString($did, "string");
  $routecheckpointid = delete_routecheckpoint($objRouteChk);
  if ($routecheckpointid != 0) {
    header("location: tms.php?pg=view-routemanager");
  }
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Page Actions  - SKU Master">
if ($action == 'add-sku') {
  $objSku = new sku();
  $objSku->customerno = $_SESSION['customerno'];
  $objSku->skucode = GetSafeValueString($_POST['skucode'], "string");
  $objSku->sku_description = GetSafeValueString($_POST['skudescription'], "string");
  $objSku->type = GetSafeValueString($_POST['typeid'], "string");
  $objSku->volume = GetSafeValueString($_POST['skuvolume'], "string");
  $objSku->weight = GetSafeValueString($_POST['skuweight'], "string");
  $objSku->netgross = GetSafeValueString($_POST['netgrosspercent'], "string");
  //print_r($objSku);
  $skuid = insert_sku($objSku);
  if ($skuid != 0) {
    header("location: tms.php?pg=view-sku");
  }
}
if ($action == 'edit-sku') {
  $objSku = new sku();
  $objSku->customerno = $_SESSION['customerno'];
  $objSku->skuid = GetSafeValueString($_POST['skuid'], "string");
  $objSku->skucode = GetSafeValueString($_POST['skucode'], "string");
  $objSku->sku_description = GetSafeValueString($_POST['sku_description'], "string");
  $objSku->typeid = GetSafeValueString($_POST['typeid'], "string");
  $objSku->volume = GetSafeValueString($_POST['volume'], "string");
  $objSku->weight = GetSafeValueString($_POST['weight'], "string");
  $objSku->netgross = GetSafeValueString($_POST['netgrosspercent'], "string");
  //print_r($objSku);
  $skuid = update_sku($objSku);
  if ($skuid != 0) {
    header("location: tms.php?pg=view-sku");
  }
}
if ($action == 'del-sku') {
  $objSku = new sku();
  $objSku->customerno = $_SESSION['customerno'];
  $objSku->skuid = $did;
  $skuid = delete_sku($objSku);
  if ($skuid != 0) {
    header("location: tms.php?pg=view-sku");
  }
}
if ($action == 'del-leftover-sku') {
  $objSku = new stdClass();
  $objSku->skuid = $did;
  $objSku->customerno = $_SESSION['customerno'];
  $skuid = delete_leftover_sku($objSku);
  if ($skuid != 0) {
    header("location: tms.php?pg=left-over-sku");
  }
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Page Actions  - FACTORY DELIEVRY">
if ($action == 'add-factoryproduction') {
  $locations = array();
  $objFact = new FactoryProductionDetails();
  $objFact->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $objFact->factoryid = GetSafeValueString($_POST['factoryid'], "int");
  $objFact->skuid = GetSafeValueString($_POST['skuid'], "int");
  $objFact->weight = GetSafeValueString($_POST['weight'], "string");
  //print_r($objFact);
  $factoryid = insert_factory_production($objFact);
  if ($factoryid != 0) {
    header("location: tms.php?pg=view-factory-production");
  }
}
if ($action == 'edit-factory-production') {
  $locations = array();
  $objFact = new FactoryProductionDetails();
  $objFact->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $objFact->fpid = GetSafeValueString($_POST['fpid'], "int");
  $objFact->factoryid = GetSafeValueString($_POST['factoryid'], "int");
  $objFact->skuid = GetSafeValueString($_POST['skuid'], "int");
  $objFact->weight = GetSafeValueString($_POST['weight'], "string");
  //print_r($objFact);
  $factoryid = update_factory_production($objFact);
  if ($factoryid != 0) {
    header("location: tms.php?pg=view-factory-production");
  }
}
if ($action == 'del-factory-production') {
  $objFact = new FactoryProductionDetails();
  $objFact->customerno = GetSafeValueString($_SESSION['customerno'], "int");
  $objFact->fpid = $did;
  $factoryid = delete_factory_production($objFact);
  if ($factoryid != 0) {
    header("location: tms.php?pg=view-factory-production");
  }
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Page Actions Indent ">
if ($action == 'add-indent') {
  $eid = $_REQUEST['eid'];
  $objProposedIndent = new ProposedIndent();
  $objProposedIndent->customerno = $_SESSION['customerno'];
  $objProposedIndent->proposedindentid = $eid;
  $result = get_proposed_indent($objProposedIndent);
  $objSKUMapping = new ProposedIndent();
  $objSKUMapping->customerno = $_SESSION['customerno'];
  $objSKUMapping->proposedindentid = $eid;
  $skuResult = get_proposed_indent_sku_mapping($objSKUMapping);
  //print_r($skuResult);
  $objIndent = new Indent();
  $objIndent->customerno = $_SESSION['customerno'];
  $objIndent->transporterid = $result[0]['transporterid'];
  $objIndent->proposed_vehicletypeid = $result[0]['proposed_vehicletypeid'];
  $objIndent->actual_vehicletypeid = $result[0]['actual_vehicletypeid'];
  $objIndent->vehicleno = $result[0]['vehicleno'];
  $objIndent->proposedindentid = $result[0]['proposedindentid'];
  $objIndent->totalweight = $result[0]['total_weight'];
  $objIndent->totalvolume = $result[0]['total_volume'];
  $objIndent->date_required = $result[0]['date_required'];
  $objIndent->factoryid = $result[0]['factoryid'];
  $objIndent->depotid = $result[0]['depotid'];
  $objSKU = new IndentSkuMapping();
  $objProposedIndent = new ProposedIndent();
  $objProposedIndent->customerno = $_SESSION['customerno'];
  $objProposedIndent->proposedindentid = $eid;
  $objProposedIndent->isApproved = 1;
  approved_proposed_indent($objProposedIndent);
  $insertindet = insert_indent($objIndent);
  if ($insertindet != 0) {
    foreach ($skuResult as $sku) {
      $objSKU->indentid = $insertindet;
      $objSKU->skuid = $sku['skuid'];
      $objSKU->no_of_units = $sku['no_of_units'];
      $objSKU->customerno = $_SESSION['customerno'];
      insert_indent_sku_mapping($objSKU);
    }
    header("location: tms.php?pg=view-proposed-indent");
  }
}
if ($action == 'edit-indent') {
  $objIndent = new Indent();
  $objIndent->indentid = $_POST['indentid'];
  $objIndent->loadstatus = $_POST['loadstatus'];
  $objIndent->shipmentno = $_POST['shipmentno'];
  $objIndent->remark = $_POST['remark'];
  edit_indent($objIndent);
}
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Page Actions  - Proposed INdent ">
if ($action == 'modify-proposed-indent') {
  $objProposedTransporterIndent = new ProposedIndentTransporterMapping();
  $objProposedTransporterIndent->customerno = $_SESSION['customerno'];
  $objProposedTransporterIndent->proposedindentid = GetSafeValueString($_POST['proposed_indentid'], "int");
  $objProposedTransporterIndent->vehicleno = GetSafeValueString($_POST['vehicleno'], "string");
  $objProposedTransporterIndent->actual_vehicletypeid = GetSafeValueString($_POST['actualvehicletypeid'], "int");
  $objProposedTransporterIndent->proposed_vehicletypeid = GetSafeValueString($_POST['proposed_vehicletypeid'], "int");
  $objProposedTransporterIndent->proposed_transporterid = GetSafeValueString($_POST['proposed_transporterid'], "int");
  $objProposedTransporterIndent->drivermobileno = GetSafeValueString($_POST['drivermobileno'], "int");
  $objProposedTransporterIndent->pitmappingid = GetSafeValueString($_POST['pitmappingid'], "int");
  $objProposedTransporterIndent->isAccepted = GetSafeValueString($_POST['isaccepted'], "int");
  $objProposedTransporterIndent->remarks = GetSafeValueString($_POST['remarks'], "string");
  //print_r($objProposedTransporterIndent);
  $objProposedIndent = new ProposedIndent();
  $objProposedIndent->customerno = $_SESSION['customerno'];
  $objProposedIndent->proposedindentid = GetSafeValueString($_POST['proposed_indentid'], "int");
  $objProposedIndent->depotid = GetSafeValueString($_POST['depotid'], "int");
  $objProposedIndent->factoryid = GetSafeValueString($_POST['factoryid'], "int");
  $objProposedIndent->hasTransporterAccepted = GetSafeValueString($_POST['isaccepted'], "int");
  if ($_POST['isaccepted'] == 1) {
    $objProposedIndent->isApproved = 1;
  }
  $pitid = update_proposed_indent($objProposedIndent, $objProposedTransporterIndent);
  if ($_POST['isaccepted'] == -1) {
    $rejection = new stdClass();
    $rejection->proposedindentid = GetSafeValueString($_POST['proposed_indentid'], "int");
    $rejection->transporterid = GetSafeValueString($_POST['proposed_transporterid'], "int");
    $rejection->vehicletypeid = GetSafeValueString($_POST['proposed_vehicletypeid'], "int");
    $rejection->depotid = GetSafeValueString($_POST['depotid'], "int");
    $rejection->factoryid = GetSafeValueString($_POST['factoryid'], "int");
    $rejection->customerno = $_SESSION['customerno'];
    reject_proposed_indent($rejection);
    // <editor-fold defaultstate="collapsed" desc="COMMENTED OUT - SEND EMAIL AND SMS">
    /* SEND EMAIL AND SMS TO NEW ASSIGNED TRANSPORTER  */
    /*
      $factoryEmail = '';
      $BCCEmail = '';
      $factoryemails = array();
      $transporterlist = array();
      $newtransporterEmailArr = array();
      $newtransporterPhoneArr = array();
      $objPI = new ProposedIndent();
      $objPI->customerno = $_SESSION['customerno'];
      $objPI->proposedindentid = GetSafeValueString($_POST['proposed_indentid'], "int");
      $transporterlist = get_assigned_transporter($objPI);
      $objFactory = new Factory();
      $objFactory->customerno = $_SESSION['customerno'];
      $objFactory->factoryid = GetSafeValueString($_POST['factoryid'], "int");
      $factoryemails = get_factory_officials($objFactory);
      foreach ($factoryemails as $emailFactory) {
      if ($emailFactory === end($factoryemails)) {
      $factoryEmail .= $emailFactory['email'];
      } else {
      $factoryEmail .= $emailFactory['email'] . ",";
      }
      }
      $factoryEmail .= ',' . constants::adminemail;
      // print_r($factoryEmailArr);
      $BCCEmail = constants::bccemail;
      if (!empty($transporterlist)) {
      if (isset($transporterlist) && !empty($transporterlist)) {
      foreach ($transporterlist as $emailTransporter) {
      if (isset($emailTransporter['email']) && trim($emailTransporter['email']) != '') {
      $newtransporterEmailArr[] = trim($emailTransporter['email']);
      }
      if (isset($emailTransporter['phone']) && trim($emailTransporter['phone']) != '') {
      $newtransporterPhoneArr[] = $emailTransporter['phone'];
      }
      }
      }
      $newsubject = "Vehicle Requirement for Mondelez - #" . $transporterlist[0]['proposedindentid'] . "";
      $newmessage = '';
      $newmessage .='Hi<br/><br/>';
      $newmessage .='Please follow Vehicle requirement schedule as below <br/><br/>';
      $newmessage .='<table border="1">';
      $newmessage .='<tr>';
      $newmessage .='<th>ID</th>';
      $newmessage .='<th>Vehicle Requirement Date</th>';
      $newmessage .='<th>Factory</th>';
      $newmessage .='<th>Depot</th>';
      $newmessage .='<th>Transporter</th>';
      $newmessage .='<th>Proposed Vehicle</th>';
      $newmessage .='</tr>';
      $newmessage .='<tr>';
      $newmessage .='<td>' . $transporterlist[0]['proposedindentid'] . '</td>';
      $newmessage .='<td>' . $transporterlist[0]['date_required'] . '</td>';
      $newmessage .='<td>' . $transporterlist[0]['factoryname'] . '</td>';
      $newmessage .='<td>' . $transporterlist[0]['depotname'] . '</td>';
      $newmessage .='<td>' . $transporterlist[0]['transportername'] . '</td>';
      $newmessage .='<td>' . $transporterlist[0]['proposedvehiclecode'] . '-' . $transporterlist[0]['proposedvehicledescription'] . '</td>';
      $newmessage .='</tr>';
      $newmessage .='</table><br/><br/>';
      $newmessage .= constants::Portallink;
      $newmessage .= constants::Thanks;
      $newmessage .= constants::CompanyName;
      $newmessage .= constants::CompanyImage;
      $newmessage .='';
      $newmsg = "Hi" . "\r\n" . "Please follow Vehicle requirement schedule as below" . "\r\n" . $transporterlist[0]['factoryname'] . " To " . $transporterlist[0]['depotname'] . " " . $transporterlist[0]['proposedvehiclecode'] . " -" . $transporterlist[0]['proposedvehicledescription'] . " On " . date("d-m-Y", strtotime($transporterlist[0]['date_required'])) . "\rn" . constants::CompanyName;
      if (!empty($newtransporterEmailArr)) {
      $attachmentFilePath = '';
      $attachmentFileName = '';
      //sendMail($newtransporterEmailArr, $factoryEmail, $BCCEmail, $newsubject, $newmessage, $attachmentFilePath, $attachmentFileName);
      }
      if (!empty($newtransporterPhoneArr)) {
      $smscount = getSMSCount($_SESSION['customerno']);
      if ($smscount > 0) {
      $isSMSSent = 0;//sendSMS($newtransporterPhoneArr, $newmsg, $response);
      if ($isSMSSent) {
      updateSMSCount($smscount, $newmsg, $_SESSION['customerno']);
      $todaysdate = date("Y-m-d H:i:s");
      foreach ($newtransporterPhoneArr as $phone) {
      $smsLogId = insertSMSLog($phone, $newmsg, $response, $transporterlist[0]['proposedindentid'], $isSMSSent, $_SESSION['customerno'], $todaysdate);
      }
      }
      }
      }
      }
     *
     */
    //</editor-fold>
  } else if ($_POST['isaccepted'] == 1) {
    $objProposedIndent = new ProposedIndent();
    $objProposedIndent->customerno = $_SESSION['customerno'];
    $objProposedIndent->proposedindentid = $_POST['proposed_indentid'];
    $objProposedIndent->isApproved = 1;
    $result = get_proposed_indent($objProposedIndent);
    $objSKUMapping = new ProposedIndent();
    $objSKUMapping->customerno = $_SESSION['customerno'];
    $objSKUMapping->proposedindentid = $_POST['proposed_indentid'];
    $skuResult = get_proposed_indent_sku_mapping($objSKUMapping);
    $objIndent = new Indent();
    $objIndent->customerno = $_SESSION['customerno'];
    $objIndent->transporterid = $result[0]['transporterid'];
    $objIndent->proposed_vehicletypeid = $result[0]['proposed_vehicletypeid'];
    $objIndent->actual_vehicletypeid = $result[0]['actual_vehicletypeid'];
    $objIndent->vehicleno = $result[0]['vehicleno'];
    $objIndent->proposedindentid = $result[0]['proposedindentid'];
    $objIndent->totalweight = $result[0]['total_weight'];
    $objIndent->totalvolume = $result[0]['total_volume'];
    $objIndent->date_required = $result[0]['date_required'];
    $objIndent->factoryid = $result[0]['factoryid'];
    $objIndent->depotid = $result[0]['depotid'];
    $objSKU = new IndentSkuMapping();
    $insertindet = insert_indent($objIndent);
    if ($insertindet != 0) {
      foreach ($skuResult as $sku) {
        $objSKU->indentid = $insertindet;
        $objSKU->skuid = $sku['skuid'];
        $objSKU->no_of_units = $sku['no_of_units'];
        $objSKU->customerno = $_SESSION['customerno'];
        insert_indent_sku_mapping($objSKU);
      }
    }
    // <editor-fold defaultstate="collapsed" desc="COMMENTED OUT - SEND EMAIL AND SMS">
    /* SEND EMAIL AND SMS TO NEW ASSIGNED TRANSPORTER  */
    /*
      $factoryEmail = '';
      $BCCEmail = '';
      $transporterEmail = '';
      $factoryemails = array();
      $transporterlist = array();
      $FactoryEmailArr = array();
      $FactoryPhoneArr = array();
      $objFactory = new Factory();
      $objFactory->customerno = $_SESSION['customerno'];
      $objFactory->factoryid = GetSafeValueString($_POST['factoryid'], "int");
      $factoryemails = get_factory_officials($objFactory);
      if (isset($factoryemails) && !empty($factoryemails)) {
      foreach ($factoryemails as $emailTransporter) {
      if (isset($emailTransporter['email']) && trim($emailTransporter['email']) != '') {
      $factoryEmailArr[] = trim($emailTransporter['email']);
      }
      if (isset($emailTransporter['phone']) && trim($emailTransporter['phone']) != '') {
      $factoryPhoneArr[] = $emailTransporter['phone'];
      }
      }
      }
      $objPI = new Transporter();
      $objPI->customerno = $_SESSION['customerno'];
      $objPI->transporterid = GetSafeValueString($_POST['proposed_transporterid'], "int");
      $transporterlist = get_transporter_officials($objPI);
      foreach ($transporterlist as $emailFactory) {
      if ($emailFactory === end($transporterlist)) {
      $transporterEmail .= $emailFactory['mail'];
      } else {
      $transporterEmail .= $emailFactory['phone'] . ",";
      }
      }
      $transporterEmail .= ',' . constants::adminemail;
      // print_r($factoryEmailArr);
      $BCCEmail = constants::bccemail;
      if (!empty($factoryEmailArr)) {
      $newsubject1 = "Vehicle Confirmation for Mondelez - #" . $result[0]['proposedindentid'] . "";
      $newmessage = '';
      $newmessage .='Hi<br/><br/>';
      $newmessage .='Please find the vehicle confirmation Status <br/><br/>';
      $newmessage .='<table border="1">';
      $newmessage .='<tr>';
      $newmessage .='<th>ID</th>';
      $newmessage .='<th>Vehicle Requirement Date</th>';
      $newmessage .='<th>Factory</th>';
      $newmessage .='<th>Depot</th>';
      $newmessage .='<th>Transporter</th>';
      $newmessage .='<th>Proposed Vehicle</th>';
      $newmessage .='</tr>';
      $newmessage .='<tr>';
      $newmessage .='<td>' . $result[0]['proposedindentid'] . '</td>';
      $newmessage .='<td>' . $result[0]['date_required'] . '</td>';
      $newmessage .='<td>' . $result[0]['factoryname'] . '</td>';
      $newmessage .='<td>' . $result[0]['depotname'] . '</td>';
      $newmessage .='<td>' . $result[0]['transportername'] . '</td>';
      $newmessage .='<td>' . $result[0]['proposedvehiclecode'] . '-' . $result[0]['proposedvehicledescription'] . '</td>';
      $newmessage .='</tr>';
      $newmessage .='</table><br/><br/>';
      $newmessage .= constants::Portallink;
      $newmessage .= constants::Thanks;
      $newmessage .= constants::CompanyName;
      $newmessage .= constants::CompanyImage;
      $newmessage .='';
      $newmsg = "Hi" . "\r\n" . "Please follow Vehicle requirement schedule as below" . "\r\n" . $result[0]['factoryname'] . " To " . $result[0]['depotname'] . " " . $result[0]['proposedvehiclecode'] . "-" . $result[0]['proposedvehicledescription'] . " On " . date('d-m-Y', strtotime($result[0]['date_required'])) . "\r\n" . constants::CompanyName;
      if (!empty($factoryEmailArr)) {
      $attachmentFilePath = '';
      $attachmentFileName = '';
      //sendMail($factoryEmailArr, $transporterEmail, $BCCEmail, $newsubject1, $newmessage, $attachmentFilePath, $attachmentFileName);
      }
      if (!empty($factoryPhoneArr)) {
      $smscount = getSMSCount($_SESSION['customerno']);
      if ($smscount > 0) {
      $isSMSSent = 0;//sendSMS($factoryPhoneArr, $newmsg, $response);
      if ($isSMSSent) {
      updateSMSCount($smscount, $newmsg, $_SESSION['customerno']);
      $todaysdate = date("Y-m-d H:i:s");
      foreach ($factoryPhoneArr as $phone) {
      $smsLogId = insertSMSLog($phone, $newmsg, $response, $result[0]['proposedindentid'], $isSMSSent, $_SESSION['customerno'], $todaysdate);
      }
      }
      }
      }
      }
     *
     */
    //</editor-fold>
  }
  if ($pitid == 1) {
    header("location: tms.php?pg=view-proposed-transporter");
  }
}
if ($action == 'Calculate-Proposed-Indent') {
  $skuids = GetSafeValueString($_POST['skuids'], "string");
  $skucodes = GetSafeValueString($_POST['skucodes'], "string");
  $weights = GetSafeValueString($_POST['weights'], "string");
  $skus[] = array();
  $skuids = explode(",", $skuids);
  $weights = explode(",", $weights);
  foreach ($skuids as $key => $val) {
    $obj = new stdClass();
    $obj->skuid = $val;
    $obj->weight = $weights[$key];
    $skus[] = $obj;
  }
  $total_weight = 0;
  $total_volume = 0;
  foreach ($skus as $sku) {
    $objsku = new Sku();
    $objsku->customerno = $_SESSION['customerno'];
    $objsku->skuid = $sku->skuid;
    if ($objsku->skuid != '') {
      $skuresult = get_sku($objsku);
      $objlist = new stdClass();
      $objlist->skuid = $skuresult[0]['skuid'];
      $objlist->unitweight = $skuresult[0]['weight'];
      $objlist->volume = $skuresult[0]['volume'];
      $objlist->total_weight = ($sku->weight * $skuresult[0]['netgross']);
      $objlist->noOfUnits = ($skuresult[0]['weight'] != 0) ? (floor($objlist->total_weight / $skuresult[0]['weight'])) : 0;
      $objlist->total_volume = $objlist->noOfUnits * $skuresult[0]['volume'];
      $total_weight += $objlist->total_weight;
      $total_volume += $objlist->total_volume;
    }
    //print_r($objlist);
  }
  //echo "Total Weight : " . $total_weight . "";
  //echo "Total Volume : " . $total_volume . "";
  echo json_encode(array("TotalWeight" => $total_weight, "TotalVolume" => $total_volume));
}
if ($action == 'Create-Proposed-Indent') {
  $skuids = GetSafeValueString($_POST['skuids'], "string");
  $skucodes = GetSafeValueString($_POST['skucodes'], "string");
  $weights = GetSafeValueString($_POST['weights'], "string");
  $skus[] = array();
  $skuslist[] = array();
  $skuids = explode(",", $skuids);
  $weights = explode(",", $weights);
  foreach ($skuids as $key => $val) {
    $obj = new stdClass();
    $obj->skuid = $val;
    $obj->weight = $weights[$key];
    $skus[] = $obj;
  }
  //print_r($skus);
  $total_weight = 0;
  $total_volume = 0;
  foreach ($skus as $sku) {
    $objsku = new Sku();
    $objsku->customerno = $_SESSION['customerno'];
    $objsku->skuid = $sku->skuid;
    if ($objsku->skuid != '') {
      $skuresult = get_sku($objsku);
      $objlist = new stdClass();
      $objlist->skuid = $skuresult[0]['skuid'];
      $objlist->unitweight = $skuresult[0]['weight'];
      $objlist->volume = $skuresult[0]['volume'];
      $objlist->total_weight = $sku->weight + ($sku->weight * $skuresult[0]['netgross']);
      $objlist->noOfUnits = ($skuresult[0]['weight'] != 0) ? ($objlist->total_weight / $skuresult[0]['weight']) : 0;
      $objlist->total_volume = $objlist->noOfUnits * $skuresult[0]['volume'];
      $total_weight += $objlist->total_weight;
      $total_volume += $objlist->total_volume;
      $skulist[] = $objlist;
    }
  }
  $objProposedIndent = new ProposedIndent();
  $objProposedIndent->factoryid = GetSafeValueString($_POST['factoryid'], "int");
  $objProposedIndent->depotid = GetSafeValueString($_POST['depotid'], "int");
  $objProposedIndent->total_weight = $total_weight;
  $objProposedIndent->total_volume = $total_volume;
  $objProposedIndent->date_required = date('Y-m-d', strtotime($_POST['date_required']));
  $objProposedIndent->remark = GetSafeValueString($_POST['remark'], "string");
  $objProposedIndent->customerno = $_SESSION['customerno'];
  $objPITMapping = new ProposedIndentTransporterMapping();
  $objPITMapping->proposed_transporterid = GetSafeValueString($_POST['transporterid'], "int");
  $objPITMapping->proposed_vehicletypeid = GetSafeValueString($_POST['vehicletypeid'], "int");
  $objPITMapping->customerno = $_SESSION['customerno'];
  $arrPISMapping = array();
  foreach ($skulist as $PISMapdetail) {
    $objSKUMapping = new ProposedIndentSkuMapping();
    $objSKUMapping->skuid = $PISMapdetail->skuid;
    $objSKUMapping->no_of_units = $PISMapdetail->noOfUnits;
    $objSKUMapping->weight = $PISMapdetail->total_weight;
    $objSKUMapping->volume = $PISMapdetail->total_volume;
    $objSKUMapping->customerno = $_SESSION['customerno'];
    $arrPISMapping[] = $objSKUMapping;
  }
  $proposedindentid = insert_proposed_indent($objProposedIndent, $objPITMapping, $arrPISMapping);
  if (isset($proposedindentid) && $proposedindentid != '0') {
    /* Get particular depot details */
    $objDepot = new Depot();
    $objDepot->depotid = $_POST['depotid'];
    $currentDepotDetail = get_depots($objDepot);
    $objTransporterShare = new TransporterShare();
    $objTransporterShare->customerno = $_SESSION['customerno'];
    $objTransporterShare->factoryid = GetSafeValueString($_POST['factoryid'], "int");
    $objTransporterShare->zoneid = $currentDepotDetail[0]['zoneid'];
    $transportersharelist = get_transportershare($objTransporterShare);
    if (isset($transportersharelist)) {
      foreach ($transportersharelist as $transporter) {
        if ($transporter['transporterid'] == $_POST['transporterid']) {
          $objTransActualShare = new TransporterActualShare();
          $objTransActualShare->transporterid = $transporter['transporterid'];
          $objTransActualShare->factoryid = GetSafeValueString($_POST['factoryid'], "int");
          $objTransActualShare->zoneid = $currentDepotDetail[0]['zoneid'];
          $objTransActualShare->shared_weight = $total_weight;
          $objTransActualShare->total_weight = $total_weight;
        } else {
          $objTransActualShare = new TransporterActualShare();
          $objTransActualShare->transporterid = $transporter['transporterid'];
          $objTransActualShare->factoryid = GetSafeValueString($_POST['factoryid'], "int");
          $objTransActualShare->zoneid = $currentDepotDetail[0]['zoneid'];
          $objTransActualShare->shared_weight = 0;
          $objTransActualShare->total_weight = $total_weight;
        }
        update_transporteractualshare($objTransActualShare);
      }
    }
  }
}
if ($action == 'del-proposedindent') {
  $eid = $_REQUEST['eid'];
  $objProposedIndent = new ProposedIndent();
  $objProposedIndent->proposedindentid = $eid;
  delete_proposed_indent($objProposedIndent);
  header("location: tms.php?pg=view-proposed-transporter");
}
// </editor-fold>
//
//<editor-fold defaultstate="collapsed" desc="Page Actions  - Vendor Payable ">
if ($action == 'check-deliveryno') {
  $objlrCheck = new stdClass();
  $objlrCheck->lrno = $_POST['lrno'];
  if ($_POST['lrno'] == '0') {
    $objlrCheck->lrno = '';
  }
  $objlrCheck->deliveryno = $_POST['deliveryno'];
  $deliveryDetails = getDeliveryDetails($objlrCheck);
  $test['DeliveryDetails'] = $deliveryDetails;
  echo json_encode($test);
}
if ($action == 'save-vendor-payable-temp') {
  $data = $_POST;
  //print_r($data);
  $objBill = new stdClass();
  $objBillDate = new DateTime($data["bill_date"]);
  $objBillReceivedDate = new DateTime($data["bill_received_date"]);
  $objBillProcessedDate = new DateTime($data["bill_processed_date"]);
  $objBillSentDate = new DateTime($data["bill_sent_date"]);
  $objBill->billtypeid = $data['bill_type'];
  $objBill->invoice_location_id = $data['invoice_location_id'];
  $objBill->depot_id = $data['depot_id'];
  $objBill->vendor_id = $data['vendor_id'];
  $objBill->bill_no = $data['bill_no'];
  $objBill->bill_date = $objBillDate->format(DATEFORMAT_YMD);
  $objBill->description = $data['description'];
  $objBill->final_bill_amt = $data['final_bill_amt'];
  $objBill->bill_received_date = $objBillReceivedDate->format(DATEFORMAT_YMD);
  $objBill->bill_processed_date = $objBillProcessedDate->format(DATEFORMAT_YMD);
  $objBill->bill_sent_date = $objBillSentDate->format(DATEFORMAT_YMD);
  $objBill->grn_no = $data['grn_no'];
  $objBill->po_no = $data['po_no'];
  $objBill->remarks_regarding_bill = $data['remarks_regarding_bill'];
  $objBill->remarks_regarding_settlement = $data['remarks_regarding_settlement'];
  $objBill->due_days = $data['due_days'];
  $objBill->billing_status = $data['billing_status'];
  $objBill->due_status = $data['due_status'];
  $objBill->days_for_receiving_bills = $data['days_for_receiving_bills'];
  $objBill->process_days = $data['process_days'];
  $objBill->custody = $data['custody'];
  $objBill->total_custody = $data['total_custody'];
  $objBill->payment_done = $data['payment_done'];
  $objBill->month_sent = $data['month_sent'];
  $objBill->year_sent = $data['year_sent'];
  $objBill->payment_bucket = $data['payment_bucket'];
  $objBill->payment_status = $data['payment_status'];
  $billId = insert_Bill_Details_Draft($objBill);
  echo $billId;
}
if ($action == 'update-vendor-payable-temp') {
  $data = $_POST;
  //print_r($data);
  $objBill = new stdClass();
  $objBillDate = new DateTime($data["bill_date"]);
  $objBillReceivedDate = new DateTime($data["bill_received_date"]);
  $objBillProcessedDate = new DateTime($data["bill_processed_date"]);
  $objBillSentDate = new DateTime($data["bill_sent_date"]);
  $objBill->billid = $data['transactionid'];
  $objBill->billtypeid = $data['bill_type'];
  $objBill->invoice_location_id = $data['invoice_location_id'];
  $objBill->depot_id = $data['depot_id'];
  $objBill->vendor_id = $data['vendor_id'];
  $objBill->bill_no = $data['bill_no'];
  $objBill->bill_date = $objBillDate->format(DATEFORMAT_YMD);
  $objBill->description = $data['description'];
  $objBill->final_bill_amt = $data['final_bill_amt'];
  $objBill->bill_received_date = $objBillReceivedDate->format(DATEFORMAT_YMD);
  $objBill->bill_processed_date = $objBillProcessedDate->format(DATEFORMAT_YMD);
  $objBill->bill_sent_date = $objBillSentDate->format(DATEFORMAT_YMD);
  $objBill->grn_no = $data['grn_no'];
  $objBill->po_no = $data['po_no'];
  $objBill->remarks_regarding_bill = $data['remarks_regarding_bill'];
  $objBill->remarks_regarding_settlement = $data['remarks_regarding_settlement'];
  $objBill->due_days = $data['due_days'];
  $objBill->billing_status = $data['billing_status'];
  $objBill->due_status = $data['due_status'];
  $objBill->days_for_receiving_bills = $data['days_for_receiving_bills'];
  $objBill->process_days = $data['process_days'];
  $objBill->custody = $data['custody'];
  $objBill->total_custody = $data['total_custody'];
  $objBill->payment_done = $data['payment_done'];
  $objBill->month_sent = $data['month_sent'];
  $objBill->year_sent = $data['year_sent'];
  $objBill->payment_bucket = $data['payment_bucket'];
  $objBill->payment_status = $data['payment_status'];
  $billId = update_Bill_Details_Draft($objBill);
  echo $billId;
}
if ($action == 'update-vendor-payable') {
  $data = $_POST;
  //print_r($data);
  $objBill = new stdClass();
  $objBillDate = new DateTime($data["bill_date"]);
  $objBillReceivedDate = new DateTime($data["bill_received_date"]);
  $objBillProcessedDate = new DateTime($data["bill_processed_date"]);
  $objBillSentDate = new DateTime($data["bill_sent_date"]);
  $objBill->billid = $data['transactionid'];
  $objBill->billtypeid = $data['bill_type'];
  $objBill->invoice_location_id = $data['invoice_location_id'];
  $objBill->depot_id = $data['depot_id'];
  $objBill->vendor_id = $data['vendor_id'];
  $objBill->bill_no = $data['bill_no'];
  $objBill->bill_date = $objBillDate->format(DATEFORMAT_YMD);
  $objBill->description = $data['description'];
  $objBill->final_bill_amt = $data['final_bill_amt'];
  $objBill->bill_received_date = $objBillReceivedDate->format(DATEFORMAT_YMD);
  $objBill->bill_processed_date = $objBillProcessedDate->format(DATEFORMAT_YMD);
  $objBill->bill_sent_date = $objBillSentDate->format(DATEFORMAT_YMD);
  $objBill->grn_no = $data['grn_no'];
  $objBill->po_no = $data['po_no'];
  $objBill->remarks_regarding_bill = $data['remarks_regarding_bill'];
  $objBill->remarks_regarding_settlement = $data['remarks_regarding_settlement'];
  $objBill->due_days = $data['due_days'];
  $objBill->billing_status = $data['billing_status'];
  $objBill->due_status = $data['due_status'];
  $objBill->days_for_receiving_bills = $data['days_for_receiving_bills'];
  $objBill->process_days = $data['process_days'];
  $objBill->custody = $data['custody'];
  $objBill->total_custody = $data['total_custody'];
  $objBill->payment_done = $data['payment_done'];
  $objBill->month_sent = $data['month_sent'];
  $objBill->year_sent = $data['year_sent'];
  $objBill->payment_bucket = $data['payment_bucket'];
  $objBill->payment_status = $data['payment_status'];
  $billId = update_Bill_Details($objBill);
  echo $billId;
}
if ($action == 'save-lrdetails-temp') {
  $lrdata = $_POST;
  //print_r($lrdata);
  $objLRDetails = new stdClass();
  $objLRDetails->bill_id = $lrdata['billid'];
  $objLRDetails->delivery_no = $lrdata['delivery_no'];
  $objLRDetails->lr_no = $lrdata['lr_no'];
  $objLRDetails->shipment_no = $lrdata['shipment_no'];
  $objLRDetails->cost_document_no = $lrdata['cost_document_no'];
  $objLRDetails->truck_type = $lrdata['truck_type'];
  $objLRDetails->route = $lrdata['route'];
  $objLRDetails->vehicle_no = $lrdata['vehicle_no'];
  $objLRDetails->indentid = $lrdata['indentid'];
  $objLRDetails->vehicle_type = $lrdata['vehicle_type'];
  $objLRDetails->movement_type = $lrdata['movement_type'];
  $objLRDetails->cfa_cost = $lrdata['cfa_cost'];
  $objLRDetails->shipment_freight_bill = $lrdata['shipment_freight_bill'];
  $objLRDetails->loading = $lrdata['loading'];
  $objLRDetails->unloading = $lrdata['unloading'];
  $objLRDetails->loading_charges = $lrdata['loading_charges'];
  $objLRDetails->unloading_charges = $lrdata['unloading_charges'];
  $objLRDetails->other_charges = $lrdata['other_charges'];
  $objLRDetails->multidrop_charges = $lrdata['multidrop_charges'];
  $objLRDetails->toll_charges = $lrdata['toll_charges'];
  $objLRDetails->permit_charges = $lrdata['permit_charges'];
  $objLRDetails->charges_outword = $lrdata['charges_outword'];
  $objLRDetails->gprs = $lrdata['gprs'];
  $objLRDetails->noentry_charges = $lrdata['noentry_charges'];
  $objLRDetails->auto_charges = $lrdata['auto_charges'];
  $objLRDetails->lr_charges = $lrdata['lr_charges'];
  $objLRDetails->tt_penalty = $lrdata['tt_penalty'];
  $objLRDetails->any_deduction = $lrdata['any_deduction'];
  $objLRDetails->total_delivery_amount = $lrdata['total_delivery_amount'];
  $lrId = insert_Lr_Details_Draft($objLRDetails);
  echo $lrId;
}
if ($action == 'save-lrdetails') {
  $lrdata = $_POST;
  //print_r($lrdata);
  $objLRDetails = new stdClass();
  $objLRDetails->bill_id = $lrdata['billid'];
  $objLRDetails->delivery_no = $lrdata['delivery_no'];
  $objLRDetails->lr_no = $lrdata['lr_no'];
  $objLRDetails->shipment_no = $lrdata['shipment_no'];
  $objLRDetails->cost_document_no = $lrdata['cost_document_no'];
  $objLRDetails->truck_type = $lrdata['truck_type'];
  $objLRDetails->route = $lrdata['route'];
  $objLRDetails->vehicle_no = $lrdata['vehicle_no'];
  $objLRDetails->indentid = $lrdata['indentid'];
  $objLRDetails->vehicle_type = $lrdata['vehicle_type'];
  $objLRDetails->movement_type = $lrdata['movement_type'];
  $objLRDetails->cfa_cost = $lrdata['cfa_cost'];
  $objLRDetails->shipment_freight_bill = $lrdata['shipment_freight_bill'];
  $objLRDetails->loading = $lrdata['loading'];
  $objLRDetails->unloading = $lrdata['unloading'];
  $objLRDetails->loading_charges = $lrdata['loading_charges'];
  $objLRDetails->unloading_charges = $lrdata['unloading_charges'];
  $objLRDetails->other_charges = $lrdata['other_charges'];
  $objLRDetails->multidrop_charges = $lrdata['multidrop_charges'];
  $objLRDetails->toll_charges = $lrdata['toll_charges'];
  $objLRDetails->permit_charges = $lrdata['permit_charges'];
  $objLRDetails->charges_outword = $lrdata['charges_outword'];
  $objLRDetails->gprs = $lrdata['gprs'];
  $objLRDetails->noentry_charges = $lrdata['noentry_charges'];
  $objLRDetails->auto_charges = $lrdata['auto_charges'];
  $objLRDetails->lr_charges = $lrdata['lr_charges'];
  $objLRDetails->tt_penalty = $lrdata['tt_penalty'];
  $objLRDetails->any_deduction = $lrdata['any_deduction'];
  $objLRDetails->total_delivery_amount = $lrdata['total_delivery_amount'];
  $lrId = insert_Lr_Details($objLRDetails);
  echo $lrId;
}
if ($action == 'delete-lrdetails-temp') {
  $lrdata = $_POST['lrids'];

  $objLRDetails = new stdClass();
  $objLRDetails->lrid = $lrdata;
  $lrId = delete_Lr_Details_Draft($objLRDetails);
  echo $lrId;
}
if ($action == 'delete-lrdetails') {
  $lrdata = $_POST['lrids'];

  $objLRDetails = new stdClass();
  $objLRDetails->lrid = $lrdata;
  $lrId = delete_Lr_Details($objLRDetails);
  echo $lrId;
}
if ($action == 'del-draft') {
  $objLRDetails = new stdClass();
  $objLRDetails->lrid = GetSafeValueString($did, "int");
  $lrId = delete_Bill_Draft($objLRDetails);
  header("location: tms.php?pg=view-bills");
}
if ($action == 'del-bill') {
  $objLRDetails = new stdClass();
  $objLRDetails->lrid = GetSafeValueString($did, "int");
  $lrId = delete_Bill($objLRDetails);
  header("location: tms.php?pg=view-billtracker");
}
if ($action == 'get-lrdetails-temp') {
  $lrdata = $_POST;
  //print_r($lrdata);
  $objLRDetails = new stdClass();
  $objLRDetails->lrid = $lrdata['lrid'];
  $lrDetails = get_Lr_Details_Draft($objLRDetails);
  $lrDetails['LrDetails'] = $lrDetails;
  echo json_encode($lrDetails);
}
if ($action == 'get-lrdetails') {
  $lrdata = $_POST;
  //print_r($lrdata);
  $objLRDetails = new stdClass();
  $objLRDetails->lrid = $lrdata['lrid'];
  $objLRDetails->billid = '';
  $objLRDetails->customerno = $_SESSION['customerno'];
  $lrDetails = get_Lr_Details($objLRDetails);
  $lrDetails['LrDetails'] = $lrDetails;
  echo json_encode($lrDetails);
}
if ($action == 'update-lrdetails-temp') {
  $lrdata = $_POST;
  //print_r($lrdata);
  $objLRDetails = new stdClass();
  $objLRDetails->lrid = $lrdata['lrid'];
  $objLRDetails->delivery_no = $lrdata['delivery_no'];
  $objLRDetails->lr_no = $lrdata['lr_no'];
  $objLRDetails->shipment_no = $lrdata['shipment_no'];
  $objLRDetails->cost_document_no = $lrdata['cost_document_no'];
  $objLRDetails->truck_type = $lrdata['truck_type'];
  $objLRDetails->route = $lrdata['route'];
  $objLRDetails->vehicle_no = $lrdata['vehicle_no'];
  $objLRDetails->indentid = $lrdata['indentid'];
  $objLRDetails->vehicle_type = $lrdata['vehicle_type'];
  $objLRDetails->movement_type = $lrdata['movement_type'];
  $objLRDetails->cfa_cost = $lrdata['cfa_cost'];
  $objLRDetails->shipment_freight_bill = $lrdata['shipment_freight_bill'];
  $objLRDetails->loading = $lrdata['loading'];
  $objLRDetails->unloading = $lrdata['unloading'];
  $objLRDetails->loading_charges = $lrdata['loading_charges'];
  $objLRDetails->unloading_charges = $lrdata['unloading_charges'];
  $objLRDetails->other_charges = $lrdata['other_charges'];
  $objLRDetails->multidrop_charges = $lrdata['multidrop_charges'];
  $objLRDetails->toll_charges = $lrdata['toll_charges'];
  $objLRDetails->permit_charges = $lrdata['permit_charges'];
  $objLRDetails->charges_outword = $lrdata['charges_outword'];
  $objLRDetails->gprs = $lrdata['gprs'];
  $objLRDetails->noentry_charges = $lrdata['noentry_charges'];
  $objLRDetails->auto_charges = $lrdata['auto_charges'];
  $objLRDetails->lr_charges = $lrdata['lr_charges'];
  $objLRDetails->tt_penalty = $lrdata['tt_penalty'];
  $objLRDetails->any_deduction = $lrdata['any_deduction'];
  $objLRDetails->total_delivery_amount = $lrdata['total_delivery_amount'];
  $lrId = update_Lr_Details_Draft($objLRDetails);
  echo $lrId;
}
if ($action == 'update-lrdetails') {
  $lrdata = $_POST;
  //print_r($lrdata);
  $objLRDetails = new stdClass();
  $objLRDetails->lrid = $lrdata['lrid'];
  $objLRDetails->delivery_no = $lrdata['delivery_no'];
  $objLRDetails->lr_no = $lrdata['lr_no'];
  $objLRDetails->shipment_no = $lrdata['shipment_no'];
  $objLRDetails->cost_document_no = $lrdata['cost_document_no'];
  $objLRDetails->truck_type = $lrdata['truck_type'];
  $objLRDetails->route = $lrdata['route'];
  $objLRDetails->vehicle_no = $lrdata['vehicle_no'];
  $objLRDetails->indentid = $lrdata['indentid'];
  $objLRDetails->vehicle_type = $lrdata['vehicle_type'];
  $objLRDetails->movement_type = $lrdata['movement_type'];
  $objLRDetails->cfa_cost = $lrdata['cfa_cost'];
  $objLRDetails->shipment_freight_bill = $lrdata['shipment_freight_bill'];
  $objLRDetails->loading = $lrdata['loading'];
  $objLRDetails->unloading = $lrdata['unloading'];
  $objLRDetails->loading_charges = $lrdata['loading_charges'];
  $objLRDetails->unloading_charges = $lrdata['unloading_charges'];
  $objLRDetails->other_charges = $lrdata['other_charges'];
  $objLRDetails->multidrop_charges = $lrdata['multidrop_charges'];
  $objLRDetails->toll_charges = $lrdata['toll_charges'];
  $objLRDetails->permit_charges = $lrdata['permit_charges'];
  $objLRDetails->charges_outword = $lrdata['charges_outword'];
  $objLRDetails->gprs = $lrdata['gprs'];
  $objLRDetails->noentry_charges = $lrdata['noentry_charges'];
  $objLRDetails->auto_charges = $lrdata['auto_charges'];
  $objLRDetails->lr_charges = $lrdata['lr_charges'];
  $objLRDetails->tt_penalty = $lrdata['tt_penalty'];
  $objLRDetails->any_deduction = $lrdata['any_deduction'];
  $objLRDetails->total_delivery_amount = $lrdata['total_delivery_amount'];
  $lrId = update_Lr_Details($objLRDetails);
  echo $lrId;
}
if ($action == 'check-bill-status') {
  $billdata = $_POST;
  $objBill = new stdClass();
  $objBill->bill_received_date = $billdata['bill_received_date'];
  $objBill->bill_processed_date = $billdata['bill_processed_date'];
  $objBill->bill_sent_date = $billdata['bill_sent_date'];
  $objBill->bill_date = $billdata['bill_date'];
  $payment_bucket = get_payment_bucket();
  /*
    if ($objBill->bill_received_date != '') {
    $objBillReceived = new DateTime($objBill->bill_received_date);
    }
    if ($objBill->bill_processed_date != '') {
    $objBillProcessed = new DateTime($objBill->bill_processed_date);
    }
    if ($objBill->bill_sent_date != '') {
    $objBillSent = new DateTime($objBill->bill_sent_date);
    }
    if ($objBill->bill_date != '') {
    $objBillDate = new DateTime($objBill->bill_date);
    }
   */
  $objBillReceived = new DateTime($objBill->bill_received_date);
  $objBillProcessed = new DateTime($objBill->bill_processed_date);
  $objBillSent = new DateTime($objBill->bill_sent_date);
  $objBillDate = new DateTime($objBill->bill_date);

  $objCurrentDate = new DateTime();
  /* Due Days */
  $dueDayInterval = $objCurrentDate->diff($objBillReceived);
  $data['Due_Day'] = $dueDayInterval->format(DATEFORMAT_DAYS);
  if (isset($objBillSent)) {
    $dueDayInterval = $objBillSent->diff($objBillReceived);
    $data['Due_Day'] = $dueDayInterval->format(DATEFORMAT_DAYS);
  }
  /* Billing Status */
  $data['Billing_Status'] = "With Us";
  if (isset($objBillProcessed) && isset($objBillSent)) {
    $data['Billing_Status'] = "Sent";
  } else if (isset($objBillProcessed) && !isset($objBillSent)) {
    $data['Billing_Status'] = "Under Process";
  }
  /* Due Status */
  $data['Due_Status'] = 'Due';
  if (isset($data['Due_Day']) && $data['Due_Day'] < 30) {
    $data['Due_Status'] = 'Not Due';
  }
  /* Days For Receiving Bills */
  $data['Days_For_Receiving_Bills'] = '';
  if (isset($objBillReceived) && isset($objBillDate)) {
    $daysForReceivingBill = $objBillReceived->diff($objBillDate);
    $data['Days_For_Receiving_Bills'] = $daysForReceivingBill->format(DATEFORMAT_DAYS);
  }
  /* Processed Days */
  $data['Processed_Days'] = '';
  if (isset($objBillSent) && isset($objBillProcessed)) {
    $processedDays = $objBillSent->diff($objBillProcessed);
    $data['Processed_Days'] = $processedDays->format(DATEFORMAT_DAYS);
  }
  /* Custody */
  $data['Custody'] = '';
  if (isset($objBillReceived) && isset($objBillProcessed)) {
    $custody = $objBillProcessed->diff($objBillReceived);
    $data['Custody'] = $custody->format(DATEFORMAT_DAYS);
  }
  /* Total Custody */
  $data['Total_Custody'] = '';
  if (isset($objBillSent) && isset($objBillProcessed)) {
    $totalCustody = $objBillSent->diff($objBillReceived);
    $data['Total_Custody'] = $totalCustody->format(DATEFORMAT_DAYS);
  }
  /* Payment Done In Days */
  $data['Payment_Done_Days'] = $data['Due_Day'];
  $data['Month_Sent'] = $objBillSent->format(DATEFORMAT_MONTH);
  $data['Year_Sent'] = $objBillSent->format(DATEFORMAT_YEAR);
  /* Payment Status */
  $data['Payment_Status'] = "Pending";
  if (isset($payment_bucket) && !empty($payment_bucket)) {
    foreach ($payment_bucket as $bucket) {
      if ($data['Due_Day'] >= $bucket['minrange'] && $data['Due_Day'] <= $bucket['maxrange']) {
        $data['Payment_Status'] = "Between " . $bucket['minrange'] . " - " . $bucket['maxrange'];
      }
    }
  }
  $billStaus['BillStatus'] = $data;
  echo json_encode($billStaus);
}
if ($action == 'insert-transaction-main') {
  $data = $_POST;
  $objVendorPayable = new stdClass();
  $objVendorPayable->billid = $data['transactionid'];
  $objVendorPayable->customerno = $_SESSION['customerno'];
  $billDetails = insert_BillTracker($objVendorPayable);
  echo $billDetails;
}
//</editor-fold>
?>