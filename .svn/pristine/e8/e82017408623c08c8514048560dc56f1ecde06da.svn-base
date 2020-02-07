<?php
$RELATIVE_PATH_DOTS ="../../";
include_once $RELATIVE_PATH_DOTS.'lib/system/utilities.php';
include_once $RELATIVE_PATH_DOTS.'lib/autoload.php';
$objDeviceManager = new DeviceManager(0);
$arrDeviceData = $objDeviceManager->deviceDetailsForHeatMap();
//prettyPrint($arrDeviceData);
echo json_encode($arrDeviceData);
?>
