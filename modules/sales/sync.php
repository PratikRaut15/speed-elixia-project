<?php

require_once 'sales_function.php';
//error_reporting(E_ALL ^ E_STRICT);
//ini_set('display_errors', 'On');
//$getdata = array( 'id' => 5,'distributorid' => 12, 'areaid' => 43, 'shopname' => 'ganesh store', 'phone' => '9870288657', 'ownername' => 'ganesh','address' => 'Thane','email' => 'test@gmail.com','cdob' => '12-08-1989', 'type' => '2', 'shopid' => '4', 'categoryid' => '3', 'styleid' => '4', 'cartons' => '150', 'qty' => '1000','remark' => 'test', 'latitude' => '1020', 'longitude' => '2010', 'radius' => '40', 'reason' => 'test reason' );
//$data = json_encode($getdata);
//print_r($data); exit;
$alldata = json_decode($data, true);
extract($alldata);
//echo "<pre>";
//print_r($alldata);
$userkey = exit_issetor($_REQUEST['userkey'], json_encode(array('result' => 'failure'))); //'Userkey not found'
$um = new UserManager();
$dpd = $um->get_person_details_by_key($userkey); // person data
if (empty($dpd)) {
    echo failure('No Userdata');
    exit;
}
$salesid = $dpd['userid'];
if ($id == 1) {    //add shops
    $mob = new sales($dpd['customerno'], $dpd['userid']);
    $distid = $mob->getareaidtodistid_api($areaid);
    $mob->add_shopdata_api($type, $distid, $salesid, $areaid, $shopname, $phone, NULL, $ownername, $address, $email, $cdob, NULL, NULL);
    echo success('Shop Added sucessfully');
} else if ($id == 2) {    //add secondary sales orders
    $mob = new sales($dpd['customerno'], $dpd['userid']);
    $data = $dm->getdistareabyshopid_api($shopid);
    $distid = $data[0]['distid'];
    $areaid = $data[0]['areaid'];
    $catid = $dm->getcatidbyskuid_api($styleid);
    $mob->add_orderdata_api($salesid, $distid, $areaid, $shopid, $catid, $styleid, $qty, NULL);
    echo success('Order Added sucessfully');
} else if ($id == 3) {   //add primary sales orders
    $mob = new Sales($dpd['customerno'], $dpd['userid']);
    $role = $dpd['role'];
    $mob->add_primarysalesdata_api($role, $salesid, $distributorid, $cartons, $styleid, NULL);
    echo success('Added primarysales sucessfully');
} else if ($id == 4) {    //add deadstocks
    $mob = new Sales($dpd['customerno'], $dpd['userid']);
    $role = $dpd['role'];
    $data = $mob->getdistareabyshopid_api($shopid);
        $distid =  $data[0]['distid'];
        $areaid =  $data[0]['areaid'];
        
    $mob->add_deadstockdata_api($role, $reason, $salesid, $distid, $cartons, $styleid, $areaid, $shopid);
    echo success('Add dead stock sucessfully');
} else if ($id == 5) {  //Add entry 
    $mob = new Sales($dpd['customerno'], $dpd['userid']);
    $role = $dpd['role'];
    $data = $mob->getdistareabyshopid_api($shopid);
    $distid =  $data[0]['distid'];
    $areaid =  $data[0]['areaid'];
    $mob->add_entrydata_api($role, $distid, $areaid, $salesid, $shopid, $remark, $latitude, $longitude, $radius, NULL);
    echo success('Entry Added sucessfully');
} else {
    failure('Unknown id');
}
?>