<?php

//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
error_reporting(0);
include_once 'assign_function.php';
include_once '../../lib/bo/UserManager.php';
//echo "<pre>";

$action = exit_issetor($_REQUEST['action'], json_encode(array('result' => 'failure')));

/* hit on login by delivery boys, from tab */
if ($action == 'pullcredentials') {
    $username = exit_issetor($_REQUEST['username'], json_encode(array('result' => 'username failure'))); //'Username not found'
    $password = exit_issetor($_REQUEST['password'], json_encode(array('result' => 'password failure'))); //'Password not found'
    $um = new UserManager();
    $dpd = $um->get_delivery_person_details($username, $password); //delivery person data
    echo json_encode($dpd);
    exit;
} elseif ($action == 'delivered') {
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $orderid = (int) exit_issetor($_REQUEST['orderid'], json_encode(array('result' => 'failure'))); //'Order ID not found'
    //$is_del = (int)exit_issetor($_REQUEST['is_del'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'
    $signature = exit_issetor($_REQUEST['signature'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'
    $latitude = exit_issetor($_REQUEST['latitude'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'
    $longitude = exit_issetor($_REQUEST['longitude'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); //delivery person data
    exit_issetor($dpd, json_encode(array('result' => 'failure'))); //'User details not found'
    include_once '../../lib/bo/DeliveryManager.php';
    $dm = new DeliveryManager($dpd['customerno']);
    $dm->order_delivered($orderid, $signature,$latitude,$longitude); //set order as delivered
    exit(json_encode(array('result' => 'success')));
} elseif ($action == 'cancelled') {
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $orderid = (int) exit_issetor($_REQUEST['orderid'], json_encode(array('result' => 'failure'))); //'Order ID not found'
    //$is_del = (int)exit_issetor($_REQUEST['is_del'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'
    $reasonid = (int) exit_issetor($_REQUEST['reasonid'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'

    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); //delivery person data
    exit_issetor($dpd, json_encode(array('result' => 'failure'))); //'User details not found'

    include_once '../../lib/bo/DeliveryManager.php';
    $dm = new DeliveryManager($dpd['customerno']);

    $dm->order_cancelled($orderid, $reasonid); //set order as delivered
    exit(json_encode(array('result' => 'success')));
} elseif ($action == 'refresh') {
    $data = array();
    $userkey = exit_issetor($_REQUEST['userkey'], 'Userkey not found');
    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); //delivery person data
    exit_issetor($dpd, json_encode(array('result' => 'failure'))); //'User details not found'

    include_once '../../lib/bo/DeliveryManager.php';
    $dm = new DeliveryManager($dpd['customerno']);
    $cur_slot = $dm->get_current_slot_details();
    
    if (count($cur_slot) > 0) {
        foreach ($cur_slot as $slot) {
            $data[$slot['slot_id']] = $dm->get_curr_orders($dpd['vehicleid'], $slot['slot_id'], $slot['timing']);  //get current slot non-delivered orders
        }
        $json = $dm->all_slots_refresh();
        $result1 = array();
        if(!empty($data)){
            $result1 = array(
                'orders' => $data,
                'slots' => $json,
                'result' => 'success'
            );
        }
    }
    echo json_encode($result1);
    
}  elseif ($action == 'pullreasons') {
    $userkey = exit_issetor($_REQUEST['userkey'], 'Userkey not found');

    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); //delivery person data
    exit_issetor($dpd, json_encode(array('result' => 'failure'))); //'User details not found'

    include_once '../../lib/bo/DeliveryManager.php';
    $dm = new DeliveryManager($dpd['customerno']);
    $data = $dm->pullreasons(); //get current slot non-delivered orders
    echo json_encode($data);
    exit;
} elseif ($action == 'pullslots') {
    $userkey = exit_issetor($_REQUEST['userkey'], 'Userkey not found');

    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); //delivery person data
    exit_issetor($dpd, json_encode(array('result' => 'failure'))); //'User details not found'

    include_once '../../lib/bo/DeliveryManager.php';
    $dm = new DeliveryManager($dpd['customerno']);
    $data = $dm->pullslots(); //get current slot non-delivered orders
    echo json_encode($data);
    exit;
} elseif ($action == 'payment') {
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

    include_once '../../lib/bo/DeliveryManager.php';
    $dm = new DeliveryManager($dpd['customerno']);
    $data = $dm->makepayment($orderid, $type, $amount, $cheque, $accountno, $bank, $branch, $reason); //get current slot non-delivered orders
    echo json_encode($data);
    exit;
} elseif ($action == 'pushphotos') {
    $userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
    $orderid = (int) exit_issetor($_REQUEST['orderid'], json_encode(array('result' => 'failure'))); //'Order ID not found'
    //$is_del = (int)exit_issetor($_REQUEST['is_del'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'
    $photos = exit_issetor($_REQUEST['photos'], json_encode(array('result' => 'failure'))); //'Is Delivered not found'

    $um = new UserManager();
    $dpd = $um->get_person_details_by_key($userkey); //delivery person data
    exit_issetor($dpd, json_encode(array('result' => 'failure'))); //'User details not found'

    include_once '../../lib/bo/DeliveryManager.php';
    $dm = new DeliveryManager($dpd['customerno']);

    $dm->push_photos($orderid, $photos); //set order as delivered
    exit(json_encode(array('result' => 'success')));
} elseif ($action == 'reset_algo') {
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
} elseif ($action == 'savebackup') {
    $ordersArr = json_decode($_REQUEST['orders']);

    foreach ($ordersArr as $order) {
        //foreach($mainorder as $order){
        $userkey = $order->userkey;
        $orderid = $order->orderid;
        $image = $order->image;
        $reasonid = $order->reasonid;
        $type = $order->type;
        $paymenttype = $order->paymenttype;
        $amount = $order->amount;
        $cheque = $order->cheque;
        $accountno = $order->accountno;
        $bank = $order->bank;
        $branch = $order->branch;
        $reason = $order->reason;


        $um = new UserManager();
        $dpd = $um->get_person_details_by_key($userkey);

        include_once '../../lib/bo/DeliveryManager.php';
        $dm = new DeliveryManager($dpd['customerno']);


        if ($type == '0') {
            $dm->push_photos($orderid, $image);
        }
        if ($type == '1') {
            $dm->order_cancelled($orderid, $reasonid);
        }
        if ($type == '2') {
            $dm->order_delivered($orderid, $image);
        }
        if ($type == '3') {
            $dm->makepayment($orderid, $paymenttype, $amount, $cheque, $accountno, $bank, $branch, $reason);
        }

        //}
    }

    echo json_encode(array('result' => 'success'));
} else {
    echo json_encode(array('result' => 'failure'));
    exit;
}
?>