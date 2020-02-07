<?php
include_once 'pickup_functions.php';
if (isset($_POST['name']) && !isset($_POST['customerid'])) {
    $name = GetSafeValueString($_POST['name'], "string");
    $phoneno = GetSafeValueString($_POST['phoneno'], "string");
    $email = GetSafeValueString($_POST['email'], "string");
    $address = GetSafeValueString($_POST['address'], "string");
    $response = addcustomer($name, $phoneno, $email, $address);
    echo $response;
}
if (isset($_POST['customerid'])) {
    $customerid = GetSafeValueString($_POST['customerid'], "string");
    $name = GetSafeValueString($_POST['name'], "string");
    $phoneno = GetSafeValueString($_POST['phoneno'], "string");
    $email = GetSafeValueString($_POST['email'], "string");
    $address = GetSafeValueString($_POST['address'], "string");
    $response = editcustomer($customerid, $name, $phoneno, $email, $address);
    echo $response;
}
if (isset($_POST['pickupboyid']) && isset($_POST['oid'])) {
    $pickupboyid = GetSafeValueString($_POST['pickupboyid'], "string");
    $oid = GetSafeValueString($_POST['oid'], "string");
    $status = GetSafeValueString($_POST['status'], "string");
    $response = editorder($pickupboyid, $oid, $status);
    echo $response;
}
if (isset($_POST['shipername']) && !isset($_POST['shiperid'])) {
    $name = GetSafeValueString($_POST['shipername'], "string");
    $phoneno = GetSafeValueString($_POST['phoneno'], "string");
    $email = GetSafeValueString($_POST['email'], "string");
    $response = addshiper($name, $phoneno, $email);
    echo $response;
}
if (isset($_POST['shiperid'])) {
    $shiperid = GetSafeValueString($_POST['shiperid'], "string");
    $name = GetSafeValueString($_POST['shipername'], "string");
    $phoneno = GetSafeValueString($_POST['phoneno'], "string");
    $email = GetSafeValueString($_POST['email'], "string");
    $response = editshiper($shiperid, $name, $phoneno, $email);
    echo $response;
}
if (isset($_POST['vendorname']) && !isset($_POST['vendorid'])) {
    $name = GetSafeValueString($_POST['vendorname'], "string");
    $phoneno = GetSafeValueString($_POST['phoneno'], "string");
    $email = GetSafeValueString($_POST['email'], "string");
    $address = GetSafeValueString($_POST['address'], "string");
    $company = GetSafeValueString($_POST['vendorcompany'], "string");
    $pincode = GetSafeValueString($_POST['pincode'], "string");
    $address_check = urlencode(GetSafeValueString($_POST['address'], "string"));
    $address_pincode = urlencode($_POST['address'] . ' ' . $_POST['pincode']);
    $lat = "";
    $long = "";
    $accuracy = 0;
    $key = "";
    $google_api1 = signUrlLocation("http://maps.google.com/maps/api/geocode/json?address=$address_check&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    $google_api2 = signUrlLocation("http://maps.google.com/maps/api/geocode/json?address=$address_pincode&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_URL, $google_api1);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    $results1 = curl_exec($ch1);
    $array_data1 = json_decode($results1);
    $partial_match = retval_issetor($array_data1->results[0]->partial_match, null);
    if ($array_data1->status === 'OK' && $partial_match == 0) {
        $location = $array_data1->results[0]->geometry->location;
        $lat = $location->lat;
        $long = $location->lng;
        $accuracy = 1;
    } else {
        if ($_POST['pincode'] != "") {
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, $google_api2);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            $results2 = curl_exec($ch1);
            $array_data2 = json_decode($results2);
            if ($array_data2->status === 'OK') {
                $location = $array_data2->results[0]->geometry->location;
                $lat = $location->lat;
                $long = $location->lng;
                $accuracy = 2;
            }
        }
    }
    $vendor = new stdClass();
    $vendor->name = $name;
    $vendor->phone = $phoneno;
    $vendor->email = $email;
    $vendor->address = $address;
    $vendor->pincode = $pincode;
    $vendor->company = $company;
    $vendor->lat = $lat;
    $vendor->lng = $long;
    $maping = json_decode($_POST['vmap']);
    $response = addvendor($vendor, $maping);
    echo $response;
}
if (isset($_POST['vendorid'])) {
    $vendorid = GetSafeValueString($_POST['vendorid'], "string");
    $name = GetSafeValueString($_POST['vendorname'], "string");
    $phoneno = GetSafeValueString($_POST['phoneno'], "string");
    $email = GetSafeValueString($_POST['email'], "string");
    $address = GetSafeValueString($_POST['address'], "string");
    $company = GetSafeValueString($_POST['vendorcompany'], "string");
    $pincode = GetSafeValueString($_POST['pincode'], "string");
    $address_check = urlencode(GetSafeValueString($_POST['address'], "string"));
    $address_pincode = urlencode($_POST['address'] . ' ' . $_POST['pincode']);
    $lat = "";
    $long = "";
    $accuracy = 0;
    $key = "";
    $google_api1 = signUrlLocation("http://maps.google.com/maps/api/geocode/json?address=$address_check&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    $google_api2 = signUrlLocation("http://maps.google.com/maps/api/geocode/json?address=$address_pincode&region=in&sensor=false&key=" . GOOGLE_MAP_API_KEY, '');
    $ch1 = curl_init();
    curl_setopt($ch1, CURLOPT_URL, $google_api1);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    $results1 = curl_exec($ch1);
    $array_data1 = json_decode($results1);
    $partial_match = retval_issetor($array_data1->results[0]->partial_match, null);
    if ($array_data1->status === 'OK' && $partial_match == 0) {
        $location = $array_data1->results[0]->geometry->location;
        $lat = $location->lat;
        $long = $location->lng;
        $accuracy = 1;
    } else {
        if ($_POST['pincode'] != "") {
            $ch1 = curl_init();
            curl_setopt($ch1, CURLOPT_URL, $google_api2);
            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            $results2 = curl_exec($ch1);
            $array_data2 = json_decode($results2);
            if ($array_data2->status === 'OK') {
                $location = $array_data2->results[0]->geometry->location;
                $lat = $location->lat;
                $long = $location->lng;
                $accuracy = 2;
            }
        }
    }
    $vendor = new stdClass();
    $vendor->vendorid = $vendorid;
    $vendor->vendorname = $name;
    $vendor->phone = $phoneno;
    $vendor->email = $email;
    $vendor->address = $address;
    $vendor->pincode = $pincode;
    $vendor->company = $company;
    $vendor->lat = $lat;
    $vendor->lng = $long;
    $maping = json_decode($_POST['vmap']);
    $response = editvendor($vendor, $maping);
    echo $response;
}
if (isset($_POST['pickupname']) && !isset($_POST['pickupuser'])) {
    $name = GetSafeValueString($_POST['pickupname'], "string");
    $phoneno = GetSafeValueString($_POST['phoneno'], "string");
    $email = GetSafeValueString($_POST['email'], "string");
    $password = GetSafeValueString($_POST['password'], "string");
    $username = GetSafeValueString($_POST['username'], "string");
    $base64img = base64_encode(GetSafeValueString($_POST['base64img'], "string"));
    $vendor = new stdClass();
    $vendor->name = $name;
    $vendor->phone = $phoneno;
    $vendor->email = $email;
    $vendor->username = $username;
    $vendor->password = $password;
    $vendor->base64img = $base64img;
    $maping = json_decode($_POST['vmap']);
    $response = addpickup($vendor, $maping);
    echo $response;
}
if (isset($_POST['pickupuser'])) {
    $pid = GetSafeValueString($_POST['pickupuser'], "string");
    $name = GetSafeValueString($_POST['pickupname'], "string");
    $username = GetSafeValueString($_POST['username'], "string");
    $phoneno = GetSafeValueString($_POST['phoneno'], "string");
    $email = GetSafeValueString($_POST['email'], "string");
    $base64img = base64_encode(GetSafeValueString($_POST['base64img'], "string"));
    $vendor = new stdClass();
    $vendor->pid = $pid;
    $vendor->name = $name;
    $vendor->username = $username;
    $vendor->phone = $phoneno;
    $vendor->email = $email;
    $vendor->base64img = $base64img;
    $maping = json_decode($_POST['vmap']);
    $response = editpickup($vendor, $maping);
    echo $response;
}
if (isset($_POST['addpickup_orders'])) {
    $orderno = GetSafeValueString($_POST['orderid'], "string");
    $customer = GetSafeValueString($_POST['customer'], "string");
    $vendorno = GetSafeValueString($_POST['vendor'], "string");
    $pickupboyid = GetSafeValueString($_POST['pickupboyid'], "string");
    $status = GetSafeValueString($_POST['status'], "string");
    $pickupdate = GetSafeValueString($_POST['pickupdate'], "string");
    $oname = GetSafeValueString($_POST['oname'], "string");
    $oaddress = GetSafeValueString($_POST['oaddress'], "string");
    $olandmark = GetSafeValueString($_POST['olandmark'], "string");
    $pincode = GetSafeValueString($_POST['pincode'], "string");
    $checkpoint = new stdClass();
    $checkpoint->customerid = $customer;
    $checkpoint->vendorno = $vendorno;
    $checkpoint->orderid = $orderno;
    $checkpoint->pickupdate = date('Y-m-d', strtotime($pickupdate));
    $checkpoint->name = $oname;
    $checkpoint->address = $oaddress;
    $checkpoint->landmark = $olandmark;
    $checkpoint->pincode = $pincode;
    if ($pickupboyid == 0 || $pickupboyid == '') {
        $checkpoint->pickupboy = getPickup_Boy($vendorno);
    } else {
        $checkpoint->pickupboy = $pickupboyid;
    }
    $checkpoint->customerno = $customer;
    $pickupmanager = new Pickup($_SESSION['customerno'], $_SESSION['userid']);
    $chk = $pickupmanager->is_order_exists($checkpoint);
    if ($chk == 1) {
        $response = "exists";
    } else {
        $pickupmanager->SaveCheckpoint($checkpoint, $_SESSION['userid']);
        $response = "ok";
    }
    echo $response;
}
?>