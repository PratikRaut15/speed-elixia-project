<?php

/**
 * Start page of TMS module
 */
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');

require_once '../panels/header.php';
require_once "tms_function.php";
if (!isset($_SESSION['userid'])) {
  header("location: ../../index.php");
}
if ($_SESSION['use_tms'] == 0) {
  exit('No access to this module');
}

$pg = isset($_GET['pg']) ? $_GET['pg'] : 'def';
echo "<br/>";

// <editor-fold defaultstate="collapsed" desc="PAGE ACTION URL">
if (!isset($_GET['pg']) || $pg == 'view-indent') {
  require_once 'pages/indentview.php';
}
if (!isset($_GET['pg']) || $pg == 'edit-indent') {
  require_once 'pages/indentedit.php';
} elseif ($pg == 'view-zone') {
  require_once 'pages/zoneview.php';
} elseif ($pg == 'view-location') {
  require_once 'pages/locationview.php';
} elseif ($pg == 'view-depot') {
  require_once 'pages/depotview.php';
} elseif ($pg == 'view-plant') {
  require_once 'pages/plantview.php';
} elseif ($pg == 'view-vehicle-type') {
  require_once 'pages/vehicletypeview.php';
} elseif ($pg == 'view-transporter') {
  require_once 'pages/transporterview.php';
} elseif ($pg == 'view-vehicle') {
  require_once 'pages/vehicleview.php';
} elseif ($pg == 'view-share') {
  require_once 'pages/shareview.php';
} elseif ($pg == 'view-route') {
  require_once 'pages/routeview.php';
} elseif ($pg == 'view-routemanager') {
  require_once 'pages/routemanagerview.php';
} elseif ($pg == 'view-sku') {
  require_once 'pages/skuview.php';
} elseif ($pg == 'view-factory-delivery') {
  require_once 'pages/factorydeliveryview.php';
} elseif ($pg == 'view-factory-production') {
  require_once 'pages/productionview.php';
} elseif ($pg == 'view-proposed-indent') {
  require_once 'pages/proposedindentview.php';
} elseif ($pg == 'add-proposed-indent') {
  require_once 'pages/proposedindentadd.php';
} elseif ($pg == 'view-proposedindent-sku') {
  require_once 'pages/proposedindentskuview.php';
} elseif ($pg == 'view-proposed-transporter') {
  require_once 'pages/proposedtransporterview.php';
} elseif ($pg == 'view-transporterindent-sku') {
  require_once 'pages/transporterindentskuview.php';
} elseif ($pg == 'view-indent-sku') {
  require_once 'pages/indentskuview.php';
} elseif ($pg == 'view-indents') {
  require_once 'pages/indentalgo.php';
} elseif ($pg == 'view-indents1') {
  require_once 'pages/indentalgo_copy.php';
} elseif ($pg == 'edit-zone') {
  require_once 'pages/zoneedit.php';
} elseif ($pg == 'edit-location') {
  require_once 'pages/locationedit.php';
} elseif ($pg == 'edit-depot') {
  require_once 'pages/depotedit.php';
} elseif ($pg == 'edit-plant') {
  require_once 'pages/plantedit.php';
} elseif ($pg == 'edit-vehicle-type') {
  require_once 'pages/vehicletypeedit.php';
} elseif ($pg == 'edit-transporter') {
  require_once 'pages/transporteredit.php';
} elseif ($pg == 'edit-vehicle') {
  require_once 'pages/vehicleedit.php';
} elseif ($pg == 'edit-share') {
  require_once 'pages/shareedit.php';
} elseif ($pg == 'edit-route') {
  require_once 'pages/routeedit.php';
} elseif ($pg == 'edit-routemanager') {
  require_once 'pages/routemanageredit.php';
} elseif ($pg == 'edit-sku') {
  require_once 'pages/skuedit.php';
} elseif ($pg == 'edit-factory-delivery') {
  require_once 'pages/factorydeliveryedit.php';
} elseif ($pg == 'edit-factory-production') {
  require_once 'pages/productionedit.php';
} elseif ($pg == 'edit-transporterindent-sku') {
  require_once 'pages/proposedtransporteredit.php';
} elseif ($pg == 'view-users') {
  require_once 'user/viewusers.php';
} elseif ($pg == 'add-user') {
  require_once 'user/adduser.php';
} elseif ($pg == 'edit-user') {
  require_once 'user/edituser.php';
} elseif ($pg == 'left-over-sku') {
  require_once 'pages/leftoversku.php';
} elseif ($pg == 'view-leftover-sku') {
  require_once 'pages/leftoversku_mapping.php';
} elseif ($pg == 'view-summary') {
  require_once 'pages/summary.php';
} elseif ($pg == 'Vendor-Eff') {
  require_once 'pages/vendoreff.php';
} elseif ($pg == 'Vendor-Zone-Eff') {
  require_once 'pages/vendorzone.php';
} elseif ($pg == 'Plant-Vendor-Zone-Eff') {
  require_once 'pages/plantvendor.php';
} elseif ($pg == 'Plant-Eff') {
  require_once 'pages/planteff.php';
} elseif ($pg == 'Date-Eff') {
  require_once 'pages/dateeff.php';
} elseif ($pg == 'view-bills') {
  require_once 'pages/billsview.php';
} elseif ($pg == 'view-billtracker') {
  require_once 'pages/billtrackerview.php';
} elseif ($pg == 'bills') {
  require_once 'pages/billpayable.php';
} else if ($pg == 'edit-bill') {
  require_once 'pages/billpayableedit.php';
} else if ($pg == 'edit-draft') {
  require_once 'pages/billpayabledraft.php';
} elseif ($pg == 'import-data') {
  require_once 'pages/import.php';
} else {
  header("location: ../../index.php");
}
// </editor-fold>

require_once '../panels/footer.php';
?>
