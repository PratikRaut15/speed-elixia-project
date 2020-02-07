<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../";

require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
require_once 'files/calculatedist.php';
require_once 'files/push_sqlite.php';

$curtime = date('Y-m-d H:i:s');
$moduleid = speedConstants::MODULE_VTS;
$objCronManager = new CronManager();
$objCustomerManager = new CustomerManager();
$objDailyReportManager = new DailyReportManager(null);

$fences = $objCronManager->getalldeviceswithgeofencesforcrons();
if (isset($fences)) {
    $arrVehicleFenceStatus = array();
    foreach ($fences as $fence) {
        $ServerDate = date(speedConstants::DEFAULT_TIMESTAMP);
        $ServerDateIST_Less1Hour = add_hours($ServerDate, -1);
        if ($fence->customerno == 64 && strtotime($fence->lastupdated) > strtotime($ServerDateIST_Less1Hour)) {

            if ($fence->conflictstatus == 1) {
                $objFence = new stdClass();
                $objFence->vehicleNo = $fence->vehicleno;
                $objFence->fenceName = $fence->fencename;
                $objFence->status = "Outside Fence";
                $objFence->location = location($fence->devicelat, $fence->devicelong, $fence->customerno);
                $arrVehicleFenceStatus[] = $objFence;
            }

        }
    }
    //prettyPrint($arrVehicleFenceStatus);
    if (isset($arrVehicleFenceStatus) && !empty($arrVehicleFenceStatus)) {
        $message = "";
        $tableRows = "";
        $placehoders['{{REALNAME}}'] = 'Malcom';
        $placehoders['{{CUSTOMER}}'] = 64;
        $subject = "Vehicle Fence Status Report";
        foreach ($arrVehicleFenceStatus as $vehicle) {
            $tableRows .= "<tr>";
            $tableRows .= "<td>" . $vehicle->vehicleNo . "</td>";
            $tableRows .= "<td>" . $vehicle->fenceName . "</td>";
            $tableRows .= "<td>" . $vehicle->status . "</td>";
            $tableRows .= "<td>" . $vehicle->location . "</td>";
            $tableRows .= "</tr>";
        }
        if ($tableRows != '') {
            $html = file_get_contents('../emailtemplates/cronVehicleFenceStatus.html');
            $placehoders['{{DATA_ROWS}}'] = $tableRows;
            foreach ($placehoders as $key => $val) {
                $html = str_replace($key, $val, $html);
            }
            $message .= $html;
            $attachmentFilePath = '';
            $attachmentFileName = '';
            $CCEmail = '';
            $BCCEmail = 'software@elixiatech.com';
            //
            $isMailSent = sendMailUtil(array('anthony.malcom@mahindra.com'), $CCEmail, $BCCEmail, $subject, $message, $attachmentFilePath, $attachmentFileName, $isTemplatedMessage = 1);
            if (isset($isMailSent)) {
                echo $message;
            }
        }
    }
}

function location($lat, $long, $customerno) {
    $address = NULL;
    $GeoCoder_Obj = new GeoCoder($customerno);
    $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
    return $address;
}

?>
