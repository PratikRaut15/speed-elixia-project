<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../";

require_once "../../lib/system/utilities.php";
require_once '../../lib/autoload.php';
include_once 'files/dailyreport.php';

$getCustomerno = 64; //$_REQUEST['customerno'];

$objCustomerManager = new CustomerManager();
$allCustomers = array($getCustomerno); //$objCustomerManager->getcustomernos();

foreach ($allCustomers as $customer) {
    $objDailyReportManager = new DailyReportManager($customer);
    $arrDevices = $objDailyReportManager->GetDevicesForReport_by_limit($customer);
    $customerDetails = $objCustomerManager->getcustomerdetail_byid($customer);
    if (isset($arrDevices) && !empty($arrDevices)) {
        $arrDeviceData = array();
        foreach ($arrDevices as $device) {
            if ($device->installlat == 0 && $device->installlng == 0) {

                $reportDate = new DateTime($device->installdate);
                $reportDate = $reportDate->add(new DateInterval('P1D'));
                $date = $reportDate->format(speedConstants::DATE_Ymd);

                $objDevice = new stdClass();
                $objDevice->vehicleId = $device->vehicleid;
                $objDevice->unitId = $device->uid;
                $objDevice->deviceId = $device->deviceid;
                $objDevice->customerNo = $customer;
                $location = "../../customer/" . $customer . "/unitno/" . $device->unitno . "/sqlite/$date.sqlite";
                if (file_exists($location)) {
                    $latLngData = $objDailyReportManager->getFirstLatLong($objDevice, $location);
                    if (isset($latLngData) && !empty($latLngData)) {
                        $objDevice->lat = $latLngData[0]->lat;
                        $objDevice->lng = $latLngData[0]->lng;
                        //$objDailyReportManager->updateDeviceInstallAddress($objDevice);
                    }
                } else {
                    echo "File Not Found ==> ".$location."<br/>";
                }
            }
        }
    }
}

?>
