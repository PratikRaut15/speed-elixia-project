<?php
//file required
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../../../";
include_once $RELATIVE_PATH_DOTS . 'config.inc.php';
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . "lib/autoload.php";
require_once $RELATIVE_PATH_DOTS . "lib/system/Sanitise.php";
require_once $RELATIVE_PATH_DOTS . "modules/trips/class/TripsManager.php";
require_once "class/class.vtsapi.php";
require_once "class/model/LrRequestClass.php";
require_once "class/model/LrResponseClass.php";
require_once $RELATIVE_PATH_DOTS . "vendor/autoload.php";
$action = "";
$apiobj = new api();
$output = null;
$objJsonMapper = new JsonMapper();
$objJsonMapper->bExceptionOnUndefinedProperty = true;
$objJsonMapper->bExceptionOnMissingData = true;
$objJsonMapper->bEnforceMapType = false;
extract($_REQUEST);
if ($action == "pushLrDetails" && isset($jsonreq)) {
    try {
        $jsonreq = json_decode($jsonreq, true);
        $today = date('Y-m-d H:i:s');
        //var_dump($jsonreq);
        //$objOrderDetails = $objJsonMapper->map($jsonreq, new LrRequestClass());
        $objOrderDetails = (object) $jsonreq;
      
        if (isset($objOrderDetails->userkey) && $objOrderDetails->userkey != "") {
            $objOrderDetails->vehicleNo = isset($objOrderDetails->vehicleNo) ? $objOrderDetails->vehicleNo : "0";
            //$objOrderDetails->materialType = isset($objOrderDetails->materialType) ? $objOrderDetails->materialType : "0";
            $objOrderDetails->orderNo = isset($objOrderDetails->orderNo) ? $objOrderDetails->orderNo : "0";
            $objOrderDetails->orderDateTime = isset($objOrderDetails->orderDateTime) ? $objOrderDetails->orderDateTime : "0";
            $objOrderDetails->currentTime = date('Y-m-d H:i:s');
            $dt = DateTime::createFromFormat("Y-m-d H:i:s", $objOrderDetails->currentTime);
            if ($dt !== false && !array_sum($dt->getLastErrors()) && isset($objOrderDetails->chitthiDetails) && !empty($objOrderDetails->chitthiDetails)) {
                $arrVTSResult = $apiobj->get_userdetails_by_key($objOrderDetails->userkey);
                if (empty($arrVTSResult)) {
                    $output = $apiobj->failure('No Userdata');
                    //Log error in DB
                } else {
                    $customerno = $arrVTSResult['customerno'];
                    $userid = $arrVTSResult['userid'];
                    $objOrderDetails->customerno = $customerno;
                    $objOrderDetails->userid = $userid;
                    $objOrderDetails->todaysDate = date('Y-m-d H:i:s');
                    $result = $apiobj->pushTripData($objOrderDetails);

                    $result = (object)$result;
                    if ($result->status == 0) {
                        $output = $apiobj->failure("Vehicle No is not in same format as in elixia.");
                    } else {
                        $message = "Details updated successfully";
                        $output = $apiobj->success($message, $result);
                    }
                }
            } else {
                $output = $apiobj->failure("Date format incorrect. Correct date format: yyyy-mm-dd HH:MM:SS");
            }
        } else {
            $output = $apiobj->failure("Mandatory parameter's value missing. Please resend the request with all parameters.");
            //Log error in DB
        }
    } catch (JsonMapper_Exception $jmEx) {
        $output = $apiobj->failure("JSON request mapping failed. Please resend the request with all required parameters.");
        //Log error in DB
    } catch (Exception $ex) {
        $output = $apiobj->failure("Whoops!!  Looks like there is an unknown exception");
        //Log error in DB
    }
}
if (!isset($output)) {
    $output = $apiobj->failure("Seems like you are playing around without any actions !!");
}
echo json_encode($output);
?>
