<?php

//file required
//error_reporting(E_ALL ^E_STRICT);
//ini_set('display_errors', 'on');
require_once("class/config.inc.php");
require_once("../../../config.inc.php");
require_once("class/class.api.php");

$apiobj = new api();

extract($_REQUEST);

if ($action == "register") {
    if (isset($name) && isset($email) && isset($phone)) {
        if (!isset($oauthuserid)) {
            $oauthuserid = '';
        }
        if (!isset($gcmid)) {
            $gcmid = '';
        }
        $test = $apiobj->register($name, $email, $phone, $device_id, $platform, $password, $oauthuserid, $gcmid);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "otpverification") {
    if (isset($phone) && isset($otp)) {
        $test = $apiobj->otpverification($phone, $otp);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "addaddress") {
    if (isset($userid)) {
        $test = $apiobj->addaddress($addressid, $name, $street, $address, $pincode, $cityid, $stateid, $phone, $addresstype, $addressname, $userid);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "updateaddress") {
    if (isset($userid)) {
        $test = $apiobj->updateaddress($addressid, $name, $street, $address, $pincode, $cityid, $stateid, $phone, $addresstype, $addressname, $userid);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "pulladdress") {
    if (isset($userid)) {
        $test = $apiobj->pulladdress($userid);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "discountcode") {
    if (isset($userid) && isset($orderdiscountcode) && isset($amount)) {
        $test = $apiobj->discountcode($orderdiscountcode, $amount, $userid);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "timeslot") {
    if (isset($pickupdate)) {
        $test = $apiobj->gettimeslot($pickupdate);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "orderrequest") {
    if (isset($userid) && isset($shippingdetails)) {
        $shipping = json_decode($shippingdetails);
        $pickupboyid = isset($pickupboyid) ? $pickupboyid : '';
        $test = $apiobj->orderrequest($shipping, $fromaddressid, $toaddressid, $pickupdate, $slotid, $paymentmodeid, $userid, $discountmanageid, $pickupboyid);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "calcestimate") {
    if (isset($userid)) {
        $test = $apiobj->calcestimate($userid, $fromaddressid, $toaddressid, $vehicletypeid, $weightid, $packingrequired, $insurancerequired, $totalvalue);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "login") {
    if (isset($username) && isset($password) && isset($oauthuserid) && isset($gcmid)) {
        $test = $apiobj->login($username, $password, $oauthuserid, $gcmid, $platform);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "pickupnotification") {
    if (isset($userid)) {
        $test = $apiobj->pickupNotification($userid);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "pickups") {
    if (isset($userid)) {
        $test = $apiobj->pickups($userid);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "orderdetails") {
    if (isset($userid) && isset($orderid)) {
        $test = $apiobj->orderDetails($userid, $orderid);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "trackorder") {
    if (isset($awbno) && isset($trackingtypeid)) {
        $test = $apiobj->trackOrder($awbno, $trackingtypeid);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == "returnorder") {
    if (isset($userid) && isset($returnitemdetails)) {
        $returnitemdetails = json_decode($returnitemdetails);
        $test = $apiobj->returnOrder($returnitemdetails, $fromaddressid, $pickupdate, $slotid, $paymentmodeid, $userid);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
if ($action == "userclear") {
    //if (isset($userid)) {
    $test = $apiobj->userclear();
    $arr_p['success'] = 0;
    $arr_p['message'] = "User table clear.";


    // } else {
    // $arr_p['success'] = 1;
    //   $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
    echo json_encode($arr_p);
    //}
}

if ($action == "forgotpass") {
    if (isset($registeredemail)) {
        $test = $apiobj->generate_forgot_otp($registeredemail);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}

if ($action == 'forgot_new_password_req') {
    if (isset($newpassword) && isset($userid)) {
        $test = $apiobj->forgot_newpassword_req($newpassword, $userid);
    } else {
        $arr_p['success'] = 1;
        $arr_p['message'] = "Mandatory parameters missing. Please resend the request with all parameters.";
        echo json_encode($arr_p);
    }
}
?>