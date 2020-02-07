<?php
//file required
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
$RELATIVE_PATH_DOTS = "../../../../";
include_once $RELATIVE_PATH_DOTS . 'config.inc.php';
require_once $RELATIVE_PATH_DOTS . "lib/system/utilities.php";
require_once $RELATIVE_PATH_DOTS . "lib/autoload.php";
require_once $RELATIVE_PATH_DOTS . "lib/system/Sanitise.php";
require_once "class/class.vtsapi.php";
require_once $RELATIVE_PATH_DOTS . "modules/trips/class/TripsManager.php";
require_once $RELATIVE_PATH_DOTS . "vendor/autoload.php";
$action = "";
$apiobj = new api();
$output = null;

extract($_REQUEST);
if ($action == "pushTripDetails" && isset($jsonreq)) {
    try {
        $jsonreq = json_decode($jsonreq, true);
        $today = date('Y-m-d H:i:s');
        $objTripDetails = (object) $jsonreq;
        //$objTripDetails->userkey = sha1($objTripDetails->userkey); // TESTING PURPOSE
        if (isset($objTripDetails->userkey) && $objTripDetails->userkey != "") {
            $arrVTSResult = $apiobj->get_userdetails_by_key($objTripDetails->userkey);
            if (empty($arrVTSResult)) {
                    $output = $apiobj->failure('Please check the userkey');
                    //Log error in DB
            }else{
                //Validation for invoiceNo starts here
                if(isset($objTripDetails->invoiceNo) && $objTripDetails->invoiceNo!='')
                {
                    if($apiobj->validateInvoiceNumber($objTripDetails->invoiceNo)==true)
                    {
                        if(isset($objTripDetails->truckNo) && $objTripDetails->truckNo!=''){
                        $objVehicleMgr = new VehicleManager($arrVTSResult['customerno']);
                        $arrVehicleDetails= $objVehicleMgr->get_all_vehicles_byId($objTripDetails->truckNo,1);
                        $arrVehicleDetails=$arrVehicleDetails[0];
            
                        if(isset($arrVehicleDetails) && !empty($arrVehicleDetails)){
                            $customerno = $arrVTSResult['customerno'];
                            $userid = $arrVTSResult['userid'];
                            $objTripDetails->customerno = $customerno;
                            $objTripDetails->userid = $userid;
                            $objTripDetails->todaysDate = date('Y-m-d H:i:s');
                            $objTripDetails->vehicleId = $arrVehicleDetails->vehicleid;
                            $result = $apiobj->pushTripData($objTripDetails);
                            if(isset($result) && !empty($result)){
                               $message = "Trip Inserted Successfully";
                               $output = $apiobj->success($message, $result);
                            }else{
                                $output = $apiobj->failure("Please check the Distributor's Name.");
                            }
                        }else{
                            $output = $apiobj->failure("Please check the Vehicle No.");
                        } 
                        }else{
                        $output = $apiobj->failure("Vehicle Number is empty");
                        }
                    }
                    else
                    {
                        $output = $apiobj->failure("Duplicate invoice number");
                    }
                    
                }
                else
                {
                    $output = $apiobj->failure("Invoice Number is empty");
                }
                
            }  
        } 
        else {
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
