<?php

//file required
//error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'on');
$RELATIVE_PATH_DOTS = "../../../../";
require_once "class/class.api.php";
require_once "class/class.sqlite.php";
require_once "class/class.geo.coder.php";
require_once $RELATIVE_PATH_DOTS . "config.inc.php";
require_once $RELATIVE_PATH_DOTS . 'lib/comman_function/reports_func.php';
require_once $RELATIVE_PATH_DOTS . 'lib/autoload.php';
require_once $RELATIVE_PATH_DOTS . 'modules/routing/assign_function.php';

//ob_start("ob_gzhandler");
//ojbect creation
$apiobj = new api();

/*
  It is not safe. Someone could do something like this: www.example.com?_SERVER['anything']
  or if he has any kind of knowledge he could try to inject something into another variable
 */

extract($_REQUEST);

if (isset($action)) {
    if ($action == 'pullcredentials') {
        $username = $_REQUEST['username']; //'Username not found'
        $password = $_REQUEST['password']; //'Password not found'
        $dpd = $apiobj->get_deliverydriver_details($username, $password); //delivery person  or driver details data
        echo json_encode($dpd);
        exit;
    }


    if ($action == "pullcategory") {
        if (isset($userkey)) {
            $validation = $apiobj->check_userkey($userkey);
            if ($validation['status'] == "successful") {
                $customerno = $validation['customerno'];
                $test = $apiobj->pullcategory($customerno);
            } else {
                $arr_p['status'] = "unsuccessful";
                $arr_p['message'] = "Please check userkey";
                echo json_encode($arr_p);
            }
        } else {
            $arr_p['status'] = "unsuccessful";
            $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";

            echo json_encode($arr_p);
        }
    }



//    if ($action == "pulltripstatus1"){
//        if (isset($userkey)) {
//            $validation = $apiobj->check_userkey($userkey);
//            if ($validation['status'] == "successful") {
//                $customerno = $validation['customerno'];
//                $vehicleid = $validation['delivery_vehicleid'];
//                $test = $apiobj->checktripstatus($customerno, $vehicleid);
//            } else {
//                $arr_p['status'] = "unsuccessful";
//                $arr_p['message'] = "Please check userkey";
//                echo json_encode($arr_p);
//            }
//        } else {
//            $arr_p['status'] = "unsuccessful";
//            $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
//
//            echo json_encode($arr_p);
//        }
//    }
//     if ($action == "pulltripstatus") {
//        $arrResult['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
//        $jsonRequest = json_decode($jsonreq);
//        if (isset($jsonRequest->userkey) && $jsonRequest->userkey != "" && is_numeric($jsonRequest->userkey)) {
//            $arrResult = $apiobj->checktripstatus($jsonRequest);
//        }
//        echo json_encode($arrResult);
//    }

    if ($action == "tripstartend") {
        $arrResult['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        $jsonRequest = json_decode($jsonreq);
        if (isset($jsonRequest->userkey) && $jsonRequest->userkey != "" && is_numeric($jsonRequest->userkey)) {
            $arrResult = $apiobj->tripupdate($jsonRequest);
        }
        echo json_encode($arrResult);
    }
    if ($action == "pullvehicledetails") {
        $arrResult['message'] = speedConstants::API_MANDATORY_PARAM_MISSING;
        $jsonRequest = json_decode($jsonreq);
        if (isset($jsonRequest->userkey) && $jsonRequest->userkey != "" && is_numeric($jsonRequest->userkey)) {
            $arrResult = $apiobj->device_list_details($jsonRequest);
        }
        echo json_encode($arrResult);
    }
//save trip  expense details 
    if ($action == "savedetails") {
        if (isset($userkey)) {
            $validation = $apiobj->check_userkey($userkey);
            if ($validation['status'] == "successful") {
                $customerno = $validation['customerno'];
                $test = $apiobj->saveexpense_details($validation, $amount, $categoryid, $newcategory, $date, $photo);
            } else {
                $arr_p['status'] = "unsuccessful";
                $arr_p['message'] = "Please check userkey";
                echo json_encode($arr_p);
            }
        } else {
            $arr_p['status'] = "unsuccessful";
            $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
            echo json_encode($arr_p);
        }
    }

//Pull driver advance amount  // add total spent amount from driver
    if ($action == "pulladvamt") {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        if (isset($userkey)) {
            $validation = $apiobj->check_userkey($userkey);
            if ($validation['status'] == "successful") {
                $customerno = $validation['customerno'];
                $amntdetails = $apiobj->getDriver_advamt($userkey, $validation);
                if (empty($amntdetails)) {
                    $arr_p['status'] = "unsuccessful";
                    $arr_p['message'] = "vehicle not mapped with this user.";
                } else {
                    $arr_p['status'] = "successful";
                    $arr_p['message'] = "";
                    $arr_p['result'] = $amntdetails;
                }
            } else {
                $arr_p['status'] = "unsuccessful";
                $arr_p['message'] = "Please check userkey";
            }
        }
        echo json_encode($arr_p);
    }

// Add advance amount 
    if ($action == "pushadvamt") {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        if (isset($userkey)) {
            $validation = $apiobj->check_userkey($userkey);
            if ($validation['status'] == "successful") {
                $customerno = $validation['customerno'];
                $advanceamt = $apiobj->getDriver_advamt_add($userkey, $details, $amount, $validation);
                $arr_p['status'] = "successful";
                $arr_p['result'] = $advanceamt;
                $arr_p['message'] = "Advance Amount added sucessfully.";
            } else {
                $arr_p['status'] = "unsuccessful";
                $arr_p['message'] = "Please check userkey";
            }
        }
        echo json_encode($arr_p);
    }

// Expense reset 
    if ($action == "resetexpense") {
        $arr_p['status'] = "unsuccessful";
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        if (isset($userkey)) {
            $validation = $apiobj->check_userkey($userkey);
            if ($validation['status'] == "successful") {
                $customerno = $validation['customerno'];
                $resetexp = $apiobj->reset_expense($userkey, $validation);
                $arr_p['status'] = "successful";
                $arr_p['result'] = "";
                $arr_p['message'] = "Expense reset sucessfully.";
            } else {
                $arr_p['status'] = "unsuccessful";
                $arr_p['message'] = "Please check userkey";
            }
        }
        echo json_encode($arr_p);
    }


    if ($action == 'delivered') {
        $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
        $orderid = (int) exit_issetor($_REQUEST['orderid'], json_encode(array('result' => 'failure'))); //'Order ID not found'
        //$is_del = (int)exit_issetor($_REQUEST['is_del'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'
        $signature = exit_issetor($_REQUEST['signature'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'
        $latitude = exit_issetor($_REQUEST['latitude'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'
        $longitude = exit_issetor($_REQUEST['longitude'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'
        $isoffline = 0;
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        exit_issetor($dpd, json_encode(array('result' => 'failure'))); //'User details not found'

        $dm = new DeliveryManager($dpd['customerno']);
        $dm->order_delivered($orderid, $signature, $latitude, $longitude,$isoffline); //set order as delivered
        exit(json_encode(array('result' => 'success')));
    }

    if ($action == 'cancelled') {
        $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
        $orderid = (int) exit_issetor($_REQUEST['orderid'], json_encode(array('result' => 'failure'))); //'Order ID not found'
        //$is_del = (int)exit_issetor($_REQUEST['is_del'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'
        $reasonid = (int) exit_issetor($_REQUEST['reasonid'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'

        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        exit_issetor($dpd, json_encode(array('result' => 'failure'))); //'User details not found'


        $dm = new DeliveryManager($dpd['customerno']);

        $dm->order_cancelled($orderid, $reasonid); //set order as delivered
        exit(json_encode(array('result' => 'success')));
    }


    if ($action == 'refresh'){
        $data = array();
        $userkey = exit_issetor($_REQUEST['userkey'], 'Userkey not found');
        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        exit_issetor($dpd, json_encode(array('result' => 'failure'))); //'User details not found'

        $dm = new DeliveryManager($dpd['customerno']);
        $cur_slot = $dm->get_current_slot_details();

        if (count($cur_slot) > 0) {
            foreach ($cur_slot as $slot) {
                $data[$slot['slot_id']] = $dm->get_curr_orders($dpd['vehicleid'], $slot['slot_id'], $slot['timing']); //get current slot non-delivered orders
            }
            $json = $dm->all_slots_refresh();
            $result1 = array();
            if (!empty($data)) {
                $result1 = array(
                    'orders' => $data,
                    'slots' => $json,
                    'result' => 'success',
                );
            }
        }
        echo json_encode($result1);
    }
    if ($action == 'pullreasons') {
        $userkey = exit_issetor($_REQUEST['userkey'], 'Userkey not found');

        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        exit_issetor($dpd, json_encode(array('result' => 'failure'))); //'User details not found'


        $dm = new DeliveryManager($dpd['customerno']);
        $data = $dm->pullreasons(); //get current slot non-delivered orders
        echo json_encode($data);
        exit;
    }
    if ($action == 'pullslots') {
        $userkey = exit_issetor($_REQUEST['userkey'], 'Userkey not found');

        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        exit_issetor($dpd, json_encode(array('result' => 'failure'))); //'User details not found'


        $dm = new DeliveryManager($dpd['customerno']);
        $data = $dm->pullslots(); //get current slot non-delivered orders
        echo json_encode($data);
        exit;
    }
    if ($action == 'payment') {
        $userkey = exit_issetor($_REQUEST['userkey'], 'Userkey not found');
        $orderid = (int) exit_issetor($_REQUEST['orderid'], json_encode(array('result' => 'failure'))); //'Order ID not found'
        $type = (int) exit_issetor($_REQUEST['type'], json_encode(array('result' => 'failure'))); //'Payment Type not found'
        $amount = ri($_REQUEST['amount']); //'Amount not found'
        $cheque = ri($_REQUEST['chequeno']);
        $accountno = ri($_REQUEST['accountno']);
        $bank = ri($_REQUEST['bank']);
        $branch = ri($_REQUEST['branch']);
        $reason = ri($_REQUEST['reason']);

        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        exit_issetor($dpd, json_encode(array('result' => 'failure'))); //'User details not found'
        
        $dm = new DeliveryManager($dpd['customerno']);
        $data = $dm->makepayment($orderid, $type, $amount, $cheque, $accountno, $bank, $branch, $reason); //get current slot non-delivered orders
        echo json_encode($data);
        exit;
    }
    if ($action == 'pushphotos') {
        $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
        $orderid = (int) exit_issetor($_REQUEST['orderid'], json_encode(array('result' => 'failure'))); //'Order ID not found'
//$is_del = (int)exit_issetor($_REQUEST['is_del'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'
        $photos = exit_issetor($_REQUEST['photos'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'

        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data
        exit_issetor($dpd, json_encode(array('result' => 'failure'))); //'User details not found'


        $dm = new DeliveryManager($dpd['customerno']);

        $dm->push_photos($orderid, $photos); //set order as delivered
        exit(json_encode(array('result' => 'success')));
    }
    if ($action == 'reset_algo') {
        $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
        $vehicleid = (int) exit_issetor($_REQUEST['vehicleid'], json_encode(array('result' => 'failure'))); //'Vehicle ID not found'
        $slotid = (int) exit_issetor($_REQUEST['slot'], json_encode(array('result' => 'failure'))); //'Slot ID not found'
        $lat = exit_issetor($_REQUEST['lat'], json_encode(array('result' => 'failure'))); //'current lat not found'
        $lng = exit_issetor($_REQUEST['long'], json_encode(array('result' => 'failure'))); //'current long not found'
//echo "$userkey==$vehicleid==$slotid==$lat==$lng";

        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey); //delivery person data

        if (!$dpd) {
            echo json_encode(array('result' => 'failure')); ///'User details not found'
        } elseif ($dpd['vehicleid'] != $vehicleid) {
            echo json_encode(array('result' => 'failure')); //User not assigned this vehicle
        } else {
            $data = reset_algo($vehicleid, $slotid, $lat, $lng, $dpd);
            if ($data) {
                echo json_encode(array('result' => 'success'));
            } else {
                echo json_encode(array('result' => 'failure')); //Some issues
            }
        }
        exit;
    }

    if ($action == 'savebackup') {
        $ordersArr = json_decode($_REQUEST['orders']);
        foreach ($ordersArr as $order) {
            if (isset($order->userkey) && $order->userkey != "" && is_numeric($order->userkey)) {
                $userkey = $order->userkey;
                $orderid = $order->orderid;
                $image = isset($order->image) ? $order->image : "";
                $reasonid = isset($order->reasonid) ? $order->reasonid : "";
                $type = isset($order->type) ? $order->type : "";
                $paymenttype = isset($order->paymenttype) ? $order->paymenttype : "";
                $amount = isset($order->amount) ? $order->amount : "0";
                $cheque = isset($order->cheque) ? $order->cheque : "";
                $accountno = isset($order->accountno) ? $order->accountno : "";
                $bank = isset($order->bank) ? $order->bank : "";
                $branch = isset($order->branch) ? $order->branch : "";
                $reason = isset($order->reason) ? $order->reason : "";
                $dellatLong = isset($order->dellong) ? $order->dellong : "";
                $dellatitude = isset($order->dellat) ? $order->dellat : "";
                $isoffline = isset($order->isoffline) ? $order->isoffline : "0";
                $um = new UserManager();
                $dpd = $um->get_person_details_by_key($userkey);
                $dm = new DeliveryManager($dpd['customerno']);
                if ($type == '0') {
                    $dm->push_photos($orderid, $image);
                }
                if ($type == '1') {
                    $dm->order_cancelled($orderid, $reasonid,$isoffline);
                }
                if ($type == '2') {
                    $dm->order_delivered($orderid, $image, $dellatitude, $dellatLong,$isoffline);
                }
                if ($type == '3') {
                    $dm->makepayment($orderid, $paymenttype, $amount, $cheque, $accountno, $bank, $branch, $reason);
                }
            }
        }
        echo json_encode(array('result' => 'success'));
    }
}
?>