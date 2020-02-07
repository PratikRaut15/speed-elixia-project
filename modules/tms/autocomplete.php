<?php
include 'tms_function.php';

if ($_POST['dummydata'] == 'zonelist') {
  if ($_POST['data'] != '') {
    $q = '%' . $_POST['data'] . '%';
  }
  $objZone = new Zone();
  $objZone->customerno = $_SESSION["customerno"];
  $objZone->zoneid = '';
  $objZone->zonename = $q;
  $zones = get_zones_filter($objZone);
  if ($zones) {
    $data = array();
    foreach ($zones as $row) {
      $zone = new stdClass();
      $zone->zoneid = $row['zoneid'];
      $zone->zonename = $row['zonename'];
      $data[] = $zone;
    }
    echo json_encode($data);
  }
} else if ($_POST['dummydata'] == 'locationlist') {
  if ($_POST['data'] != '') {
    $q = '%' . $_POST['data'] . '%';
  }
  $objLocation = new Location();
  $objLocation->customerno = $_SESSION["customerno"];
  $objLocation->locationid = '';
  $objLocation->locationname = $q;
  $locations = get_locations_filter($objLocation);
  if ($locations) {
    $data = array();
    foreach ($locations as $row) {
      $location = new stdClass();
      $location->locationid = $row['locationid'];
      $location->locationname = $row['locationname'];
      $data[] = $location;
    }
    echo json_encode($data);
  }
} else if ($_POST['dummydata'] == 'depotlist') {
  if ($_POST['data'] != '') {
    $q = '%' . $_POST['data'] . '%';
  }
  $objDepot = new Depot();
  $objDepot->customerno = $_SESSION["customerno"];
  $objDepot->zoneid = '';
  $objDepot->depotname = $q;
  $depots = get_depots_filter($objDepot);
  if ($depots) {
    $data = array();
    foreach ($depots as $row) {
      $location = new stdClass();
      $location->depotid = $row['depotid'];
      $location->depotname = $row['depotname'];
      $data[] = $location;
    }
    echo json_encode($data);
  }
} else if ($_POST['dummydata'] == 'vehicletypelist') {
  if ($_POST['data'] != '') {
    $q = '%' . $_POST['data'] . '%';
  }
  $objVehicleType = new VehicleType();
  $objVehicleType->customerno = $_SESSION["customerno"];
  $objVehicleType->vehicletypeid = '';
  $objVehicleType->vehiclecode = $q;
  $vehicletypes = get_vehicletype_filter($objVehicleType);
  if ($vehicletypes) {
    $data = array();
    foreach ($vehicletypes as $row) {
      $vehicle = new stdClass();
      $vehicle->vehicletypeid = $row['vehicletypeid'];
      $vehicle->vehiclecode = $row['vehicledescription'] . " - " . $row['vehiclecode'];
      $data[] = $vehicle;
    }
    echo json_encode($data);
  }
} else if ($_POST['dummydata'] == 'transporterlist') {
  if ($_POST['data'] != '') {
    $q = '%' . $_POST['data'] . '%';
  }
  $objTransporter = new Transporter();
  $objTransporter->customerno = $_SESSION["customerno"];
  $objTransporter->transporterid = '';
  $objTransporter->transportername = $q;
  $transporters = get_transporter_filter($objTransporter);
  if ($transporters) {
    $data = array();
    foreach ($transporters as $row) {
      $transporter = new stdClass();
      $transporter->transporterid = $row['transporterid'];
      $transporter->transportername = $row['transportername'];
      $data[] = $transporter;
    }
    echo json_encode($data);
  }
} else if ($_POST['dummydata'] == 'factorylist') {
  if ($_POST['data'] != '') {
    $q = '%' . $_POST['data'] . '%';
  }
  $objFactory = new Factory();
  $objFactory->customerno = $_SESSION["customerno"];
  $objFactory->factoryid = '';
  $objFactory->factoryname = $q;
  $factories = get_factory_filter($objFactory);
  if ($factories) {
    $data = array();
    foreach ($factories as $row) {
      $factory = new stdClass();
      $factory->factoryid = $row['factoryid'];
      $factory->factoryname = $row['factoryname'];
      $data[] = $factory;
    }
    echo json_encode($data);
  }
} else if ($_POST['dummydata'] == 'routelist') {
  if ($_POST['data'] != '') {
    $q = '%' . $_POST['data'] . '%';
  }
  $objRoute = new RouteMaster();
  $objRoute->customerno = $_SESSION["customerno"];
  $objRoute->routemasterid = '';
  $objRoute->routename = $q;
  $routes = get_route_filter($objRoute);
  if ($routes) {
    $data = array();
    foreach ($routes as $row) {
      $route = new stdClass();
      $route->routemasterid = $row['routemasterid'];
      $route->routename = $row['routename'];
      $data[] = $route;
    }
    echo json_encode($data);
  }
} else if ($_POST['dummydata'] == 'typelist') {
  if ($_POST['data'] != '') {
    $q = '%' . $_POST['data'] . '%';
  }
  $objRoute = new sku();
  $objRoute->customerno = $_SESSION["customerno"];
  $objRoute->typeid = '';
  $objRoute->type = $q;
  $routes = get_skutype_filter($objRoute);
  if ($routes) {
    $data = array();
    foreach ($routes as $row) {
      $route = new stdClass();
      $route->typeid = $row['tid'];
      $route->type = $row['type'];
      $data[] = $route;
    }
    echo json_encode($data);
  }
} else if ($_POST['dummydata'] == 'skulist') {
  if ($_POST['data'] != '') {
    $q = '%' . $_POST['data'] . '%';
  }
  $objRoute = new sku();
  $objRoute->customerno = $_SESSION["customerno"];
  $objRoute->skuid = '';
  $objRoute->skucode = $q;
  $routes = get_sku_filter($objRoute);
  if ($routes) {
    $data = array();
    foreach ($routes as $row) {
      $route = new stdClass();
      $route->skuid = $row['skuid'];
      $route->skucode = $row['skucode'];
      $route->sku_description = $row['sku_description'];
      $data[] = $route;
    }
    echo json_encode($data);
  }
} else if ($_POST['dummydata'] == 'sku') {
  if ($_POST['q'] != '') {
    $q = '%' . $_POST['q'] . '%';
    $cnt = $_POST['cnt'];
    $typeid = $_POST['typeid'];
  }
  $objRoute = new sku();
  $objRoute->customerno = $_SESSION["customerno"];
  $objRoute->skuid = '';
  $objRoute->skucode = $q;
  $objRoute->typeid = $typeid;
  $routes = get_sku_bytype($objRoute);
  if ($routes) {
    $data = array();
    foreach ($routes as $row) {
      $route = new stdClass();
      $route->skuid = $row['skuid'];
      $route->sku_description = $row['sku_description'];
      $route->sku_code = $row['skucode'];
      $data[] = $route;
      ?>
      <li onclick="fillsku(<?php echo $route->skuid; ?>, '<?php echo $route->sku_code ?>', '<?php echo $route->sku_description ?>', <?php echo $cnt; ?>)" value="<?php echo $route->skuid; ?>"><?php echo $route->sku_code; ?></li>
      <?php
    }
    //echo json_encode($data);
  }
} else if ($_POST['dummydata'] == 'transtype') {
  if ($_POST['q'] != '') {
    $q = '%' . $_POST['q'] . '%';
    $transporterid = $_POST['transporterid'];
    $typeid = $_POST['typeid'];
  }
  $objRoute = new VehicleType();
  $objRoute->customerno = $_SESSION["customerno"];
  $objRoute->skutypeid = $typeid;
  $objRoute->transporterid = $transporterid;
  $objRoute->vehicledescription = $q;
  $routes = get_vehtype_filter($objRoute);
  if ($routes) {
    $data = array();
    foreach ($routes as $row) {
      $route = new stdClass();
      $route->vehicletypeid = $row['vehicletypeid'];
      $route->vehiclecode = $row['vehiclecode'];
      $route->vehicledescription = $row['vehicledescription'];
      $route->skutypeid = $row['skutypeid'];
      $route->volume = $row['volume'];
      $route->weight = $row['weight'];
      $data[] = $route;
      ?>
      <li onclick="fillvehicletype('<?php echo $route->vehicledescription; ?>', '<?php echo $route->vehiclecode; ?>', '<?php echo $route->vehicletypeid; ?>', '<?php echo $route->volume ?>', '<?php echo $route->weight ?>');" value="<?php echo $route->vehicletypeid; ?>"><?php echo $route->vehicledescription . " - " . $route->vehiclecode ?></li>
      <?php
    }
    //echo json_encode($data);
  }
} else if ($_POST['dummydata'] == 'transporterlist_byZone') {
  if ($_POST['q'] != '') {
    $q = '%' . $_POST['q'] . '%';
    $factoryid = $_POST['factoryid'];
    $depotid = $_POST['depotid'];
  }
  $objDepot = new Depot();
  $objDepot->customerno = $_SESSION["customerno"];
  $objDepot->depotid = $depotid;
  $depot = get_depots($objDepot);

  $objTransporter = new Transporter();
  $objTransporter->customerno = $_SESSION["customerno"];
  $objTransporter->transporterid = '';
  $objTransporter->transportername = $q;
  $objTransporter->factoryid = $factoryid;
  $objTransporter->zoneid = $depot[0]['zoneid'];
  $transporters = get_transporter_filter_byZone($objTransporter);


  if ($transporters) {
    $data = array();
    foreach ($transporters as $row) {
      $route = new stdClass();
      $route->transporterid = $row['transporterid'];
      $route->transportername = $row['transportername'];
      $data[] = $route;
      ?>
      <li onclick="fill_transporter_byZone('<?php echo $route->transporterid; ?>', '<?php echo $route->transportername; ?>');" value="<?php echo $route->transporterid; ?>"><?php echo $route->transportername ?></li>
      <?php
    }
    //echo json_encode($data);
  }
} else if ($_POST['dummydata'] == 'depots') {
  if ($_POST['q'] != '') {
    $q = '%' . $_POST['q'] . '%';
  }

  $objDepot = new Depot();
  $objDepot->customerno = $_SESSION["customerno"];
  $objDepot->zoneid = '';
  $objDepot->depotname = $q;
  $depots = get_depots_filter($objDepot);
  //print_r($depots);
  if ($depots) {

    $data = array();
    foreach ($depots as $row) {
      $depot = new stdClass();
      $depot->depotid = $row['depotid'];
      $depot->depotname = $row['depotname'];
      $data[] = $depot;
      ?>
      <li onClick="addmultidropdepots(<?php echo $depot->depotid; ?>, '<?php echo $depot->depotname ?>');" value="<?php echo $depot->depotid; ?>"><?php echo $depot->depotname ?></li>
      <?php
    }
  }
}
?>