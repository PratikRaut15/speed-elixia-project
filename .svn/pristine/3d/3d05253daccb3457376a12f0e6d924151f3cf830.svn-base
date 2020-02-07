<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../";

require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
include_once 'files/dailyreport.php';

$getCustomerno = $_REQUEST['customerno'];
$getDate = $_REQUEST['date'];

$reportDate = new DateTime($getDate);
$date = $reportDate->format(speedConstants::DATE_Ymd);

$objCustomerManager = new CustomerManager();
$allCustomers = array($getCustomerno); //$objCustomerManager->getcustomernos();

foreach ($allCustomers as $customer) {
    $objDailyReportManager = new DailyReportManager($customer);
    $arrDevices = $objDailyReportManager->GetDevicesForReport_by_limit($customer);
    $customerDetails = $objCustomerManager->getcustomerdetail_byid($customer);
    if (isset($arrDevices) && !empty($arrDevices)) {
        $arrDeviceData = array();
        foreach ($arrDevices as $device) {
            $objDevice = new stdClass();
            $objDevice->vehicleId = $device->vehicleid;
            $objDevice->unitId = $device->uid;
            $objDevice->customerNo = $customer;
            $objDevice->is_ac_opp = $device->is_ac_opp;
            $objDevice->is_genset_opp = $device->is_genset_opp;
            $objDevice->isActive = 0;
            $objDevice->isTemperature = -1;
            $objDevice->isHumidity = -1;
            $objDevice->isDigital = -1;
            $objDevice->reportDate = $date;
            if ($customerDetails->temp_sensors > 0 && ($device->tempsen1 != 0 || $device->tempsen2 != 0 || $device->tempsen3 != 0 || $device->tempsen4 != 0)) {
                $objDevice->isTemperature = 0;
            }
            if ($customerDetails->use_humidity == 1 && $device->humidity != 0) {
                $objDevice->isHumidity = 0;
            }
            if (($customerDetails->use_ac_sensor == 1 && $device->acsensor != 0) || ($customerDetails->use_genset_sensor == 1 && $device->gensetsensor != 0)) {
                $objDevice->isDigital = 0;
            }
            $location = "../../customer/" . $customer . "/unitno/" . $device->unitno . "/sqlite/$date.sqlite";

            if (file_exists($location)) {
                $objDevice = $objDailyReportManager->getDeviceWorkingStatus($objDevice, $location, $customerDetails);
            }
            //prettyPrint($objDevice);
            $arrDeviceData[] = $objDevice;
        }
        //prettyPrint($arrDeviceData);

        if(!empty($arrDeviceData)) {
            $annexureFile = "annexure.sqlite";
            $annexurePath = "../../customer/".$customer."/reports/";
            if (!file_exists($annexurePath.$annexureFile)) {
                $fh = fopen($annexurePath.$annexureFile, 'w');
            }
            $annexureSqlite = "sqlite:../../customer/".$customer."/reports/annexure.sqlite";
            foreach ($arrDeviceData as $data) {
                $db = new PDO($annexureSqlite);
                $db->exec('BEGIN IMMEDIATE TRANSACTION');
                insertAnnexureStatus($data, $db);
                $db->exec('COMMIT TRANSACTION');
            }
            echo "Record Updated";
        }
    }
}

?>
