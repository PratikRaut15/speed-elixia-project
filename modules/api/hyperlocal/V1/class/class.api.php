<?php

require_once("global.config.php");
require_once("database.inc.php");
date_default_timezone_set('Asia/Kolkata');

class VOPickups {

}

class object {

}

class api {

 var $status;
 var $status_time;

 // construct
 public function __construct() {
  $this->speeddb = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
  $this->pickupdb = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, DB_PICKUP);
  $this->deliverydb = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, DB_DELIVERY);
 }

 public function check_login($username, $password) {
  $retarray['status'] = "0";
  $retarray['message'] = "";
  $retarray['version'] = '';
  $retarray['customername'] = null;
  $retarray['userkey'] = 0;
  $userkeyparam = 0;
  $pdo = $this->speeddb->CreatePDOConn();
  $todaysdate = date("Y-m-d H:i:s");
  $sp_params = "'" . $username . "'"
   . ",'" . $password . "'"
   . ",'" . $todaysdate . "'"
   . "," . '@usertype'
   . "," . '@userkeyparam';

  $queryCallSP = "CALL " . SP_AUTHENTICATE_FOR_LOGIN . "($sp_params)";
  $arrResult = $pdo->query($queryCallSP)->fetch(PDO::FETCH_ASSOC);
  $this->speeddb->ClosePDOConn($pdo);
  $outputParamsQuery = "SELECT @usertype AS usertype, @userkeyparam AS userkeyparam";
  $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
  $usertype = $outputResult['usertype'];
  $userkeyparam = $outputResult['userkeyparam'];
  if ($userkeyparam != 0) {
   if ($usertype == 0 && $userkeyparam != 0) {
    $retarray['status'] = "1";
    $retarray['userkey'] = $arrResult['userkey'];
    $this->update_push_android_chk($arrResult['userkey'], 1);
    $this->update_push_android_chk_main($arrResult['userkey'], 1);
    $retarray['customerno'] = $arrResult['customerno'];
    $retarray['username'] = $arrResult['username'];
    $retarray['customername'] = $arrResult['customercompany'];
    $retarray['version'] = $arrResult['version'];
    $retarray['role'] = $arrResult['role'];

    $today = date("Y-m-d H:i:s");
    $sql = "UPDATE user SET lastlogin_android='" . $today . "' where userkey = '" . $arrResult['userkey'] . "' AND customerno= '" . $arrResult['customerno'] . "' LIMIT 1";
    $this->speeddb->query($sql, __FILE__, __LINE__);
   } else if ($usertype == 1 && $userkeyparam != 0) {
    $retarray['status'] = "1";
    $retarray['message'] = "forgot_password_success";
    $retarray['version'] = '';
    $retarray['customername'] = null;
    $retarray['userkey'] = $userkeyparam;
   }
  } else {
   $retarray['status'] = "0";
   $retarray['message'] = "Invalid Credentials";
   $retarray['version'] = '';
   $retarray['customername'] = null;
  }
  echo json_encode($retarray);
  return $retarray;
 }

 public function check_userkey($userkey) {
  $sql = "select * from " . TBL_ADMIN_USER . " where userkey='" . $userkey . "' AND isdeleted=0";
  $record = $this->speeddb->query($sql, __FILE__, __LINE__);
  $row = $this->speeddb->fetch_array($record);
  $retarray = array();
  if ($row['userkey'] != "") {
   $retarray['status'] = "successful";
   $retarray['customerno'] = $row["customerno"];
   $retarray['userid'] = $row["userid"];
   $retarray['realname'] = $row["realname"];
   $retarray['roleid'] = $row["roleid"];
   $retarray['role'] = $row["role"];
  } else {
   $retarray['status'] = "unsuccessful";
  }
  return $retarray;
 }

 public function get_pickupboy($userid) {
  $sql = "select * from " . TBL_ADMIN_USER . " where userid='" . $userid . "' AND isdeleted=0";
  $record = $this->speeddb->query($sql, __FILE__, __LINE__);
  $row = $this->speeddb->fetch_array($record);
  if ($row['userkey'] != "") {
   return $row["realname"];
  }
 }

 public function updateLogin($userkey) {
  $today = date('Y-m-d H:i:s');
  $sql = "select * from " . SPEEDDB . ".user where userkey='" . $userkey . "' AND isdeleted = 0";
  $record = $this->speeddb->query($sql, __FILE__, __LINE__);
  $row = $this->speeddb->fetch_array($record);
  if ($row['userkey'] != "") {
   $userid = $row['userid'];
   $customerno = $row['customerno'];
   $sqlInsert = "insert into " . SPEEDDB . ".login_history(userid, customerno,type,timestamp)values($userid,$customerno,1,'" . $today . "')";
   $this->speeddb->query($sqlInsert, __FILE__, __LINE__);
  }
 }

 public function update_push_android_chk($userkey, $val) {
  $sql = "UPDATE " . SPEEDDB . ".user SET chkmanpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
  $this->speeddb->query($sql, __FILE__, __LINE__);
 }

 public function update_push_android_chk_main($userkey, $val) {
  $sql = "UPDATE " . SPEEDDB . ".user SET chkpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
  $this->speeddb->query($sql, __FILE__, __LINE__);
 }

 public function getDashboard($userkey, $pickupid, $clientid, $vendorno, $pickupboyid, $pickupdate, $status, $pageIndex, $pageSize) {
  $today = date('Y-m-d');
  $orders = array();
  $pickup_count = 0;
  $delivery_count = 0;
  $jsonpickup = array();
  $jsondelivery = array();
  $limit = 1;

  $validation = $this->check_userkey($userkey);
  if ($validation['status'] == "successful") {
   $userid = $validation['userid'];
   $customerno = $validation['customerno'];
   /* Pickups */
   $pdo = $this->pickupdb->CreatePDOConn();
   $todaysdate = date("Y-m-d H:i:s");
   $sp_params = "'" . $customerno . "'"
    . ",'" . $pickupid . "'"
    . ",'" . $clientid . "'"
    . ",'" . $vendorno . "'"
    . ",'" . $userid . "'"
    . ",'" . $pickupdate . "'"
    . ",'" . $status . "'"
    . ",'" . $pageIndex . "'"
    . ",'" . $pageSize . "'"
    . "," . '@recordCount';

   $queryCallSP = "CALL " . SP_PULL_PICKUPS . "($sp_params)";
   $arrResultPickups = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
   $this->pickupdb->ClosePDOConn($pdo);
   $pickup_count = count($arrResultPickups);


   if (isset($arrResultPickups) && !empty($arrResultPickups)) {
    foreach ($arrResultPickups as $data) {
     $order = new VOPickups();
     $order->type = 'Pickup';
     $order->pickupid = $data['oid'];
     $order->scanno = $data['orderid'];

     if ($data['customerid'] == "") {
      $order->clientid = 0;
      $order->clientname = '';
     } else {
      $order->clientid = $data['customerid'];
      $order->clientname = $data['customername'];
     }

     if ($data['vendorid'] == "") {
      $order->vendorid = 0;
      $order->vendoraddress = '';
      $order->vendorname = '';
     } else {
      $order->vendorid = $data['vendorid'];
      $order->vendorname = $data['vendorname'];
      $order->vendoraddress = $data['address'];
     }
     $order->pickupdate = $data['pickupdate'];

     $curtime = date('Y-m-d h:i:s');
     $to_time = strtotime($curtime);
     $from_time = strtotime($order->pickupdate);
	if($to_time > $from_time){
		$order->timeleft = "-".round(($to_time - $from_time) / 60) . " minutes";
	}else{
     		$order->timeleft = round(($to_time - $from_time) / 60) . " minutes";
	}
     //$order->timeleft = '30 minutes';
     $jsonpickup[] = $order;
    }

    $pdo = $this->deliverydb->CreatePDOConn();
    $sp_params = "'" . $customerno . "'"
     . ",'" . $pickupid . "'"
     . ",'" . $vendorno . "'"
     . ",'" . $userid . "'"
     . ",'" . $pickupdate . "'"
     . ",'" . $status . "'"
     . ",'" . $pageIndex . "'"
     . ",'" . $pageSize . "'"
     . "," . '@recordCount';

    $queryCallSP = "CALL " . SP_PULL_DELIVERY . "($sp_params)";
    $arrResultDelivery = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
    $delivery_count = count($arrResultDelivery);
    $this->pickupdb->ClosePDOConn($pdo);

    $orders['status'] = '1';
    $orders['pickup_count'] = $pickup_count;
    $orders['delivery_count'] = $delivery_count;
    $orders['pickupboy'] = $this->get_pickupboy($userid);
    $orders['type'] = $jsonpickup[0]->type;
    $orders['for'] = $jsonpickup[0]->vendorname;
    $orders['address'] = $jsonpickup[0]->vendoraddress;
    $orders['datetime'] = $jsonpickup[0]->pickupdate;
    $orders['timeleft'] = $jsonpickup[0]->timeleft;

    $orders['message'] = '';
   }

   if (empty($arrResultPickups) && empty($arrResultDelivery)) {
    $orders['status'] = '0';
    $orders['message'] = 'Data not found';
   }
  } else {
   $orders['status'] = '0';
   $orders['message'] = 'Please check the userkey';
  }

  echo json_encode($orders);
  return json_encode($orders);
 }

 public function pullorders_pending($userkey, $pickupid, $clientid, $vendorno, $pickupboyid, $pickupdate, $status, $pageIndex, $pageSize) {
  $today = date('Y-m-d');
  $orders = array();
  $json = array();
  $jsonvendor = array();
  $jsonclient = array();
  $validation = $this->check_userkey($userkey);
  if ($validation['status'] == "successful") {
   $userid = $validation['userid'];
   $customerno = $validation['customerno'];
   $pdo = $this->pickupdb->CreatePDOConn();
   $todaysdate = date("Y-m-d H:i:s");
   $sp_params = "'" . $customerno . "'"
    . ",'" . $pickupid . "'"
    . ",'" . $clientid . "'"
    . ",'" . $vendorno . "'"
    . ",'" . $userid . "'"
    . ",'" . $pickupdate . "'"
    . ",'" . $status . "'"
    . ",'" . $pageIndex . "'"
    . ",'" . $pageSize . "'"
    . "," . '@recordCount';

   $queryCallSP = "CALL " . SP_PULL_PICKUPS . "($sp_params)";
   $arrResultPickups = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
   $this->pickupdb->ClosePDOConn($pdo);
   $pending_count = 0;
   if (isset($arrResultPickups) && !empty($arrResultPickups)) {
    foreach ($arrResultPickups as $data) {
     $order = new VOPickups();
     $order->name = $data['vendorname'];
     if ($data['vendorid'] != '') {
      $order->vendorno = $data['vendorno'];
      $order->vendorid = $data['vendorid'];
      $order->type = 'Vendor';
      $order->address = $data['address'];
     } else {
      $order->type = 'Customer';
     }
     $order->pickup_count = 1;
     $pending_count += 1;
     $json[] = $order;
    }
    $array = json_decode(json_encode($json), true);
    $jsonPickup = array_reduce($array, function ($result, $currentItem) {
     if (isset($result[$currentItem['vendorno']])) {
      $result[$currentItem['vendorno']]['pickup_count'] += $currentItem['pickup_count'];
     } else {
      $result[$currentItem['vendorno']] = $currentItem;
     }
     return $result;
    });
    $pickups = array();
    foreach ($jsonPickup as $data) {
     $order = new VOPickups();
     $order->name = $data['name'];
     $order->address = $data['address'];
     $order->vendorno = $data['vendorno'];
     $order->vendorid = $data['vendorid'];
     $order->type = 'Vendor';
     $order->pickup_count = $data['pickup_count'];
     $pickups[] = $order;
    }


    $orders['status'] = '1';
    $orders['pending_count'] = $pending_count;
    $orders['pickups'] = $pickups;

    $orders['message'] = '';
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = '0';
    $orders['message'] = 'Data not found';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = '0';
   $orders['message'] = 'Please check the userkey';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function pullorders_pending_vendorwise($userkey, $pickupid, $clientid, $vendorno, $pickupboyid, $pickupdate, $status, $pageIndex, $pageSize) {
  $today = date('Y-m-d');
  $orders = array();
  $json = array();
  $jsonvendor = array();
  $jsonclient = array();
  $validation = $this->check_userkey($userkey);
  if ($validation['status'] == "successful") {
   $userid = $validation['userid'];
   $customerno = $validation['customerno'];
   $pdo = $this->pickupdb->CreatePDOConn();
   $todaysdate = date("Y-m-d H:i:s");
   $sp_params = "'" . $customerno . "'"
    . ",'" . $pickupid . "'"
    . ",'" . $clientid . "'"
    . ",'" . $vendorno . "'"
    . ",'" . $userid . "'"
    . ",'" . $pickupdate . "'"
    . ",'" . $status . "'"
    . ",'" . $pageIndex . "'"
    . ",'" . $pageSize . "'"
    . "," . '@recordCount';

   $queryCallSP = "CALL " . SP_PULL_PICKUPS . "($sp_params)";
   $arrResultPickups = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
   $this->pickupdb->ClosePDOConn($pdo);
   if (isset($arrResultPickups) && !empty($arrResultPickups)) {
    foreach ($arrResultPickups as $data) {
     $order = new VOPickups();
     $order->name = $data['vendorname'];
     if ($data['vendorid'] != '') {
      $order->vendorno = $data['vendorno'];
      $order->vendorid = $data['vendorid'];
      $order->type = 'Vendor';
     } else {
      $order->type = 'Customer';
     }
     $order->pickup_count = 1;

     $json[] = $order;
    }
    $array = json_decode(json_encode($json), true);
    $jsonPickup = array_reduce($array, function ($result, $currentItem) {
     if (isset($result[$currentItem['vendorid']])) {
      $result[$currentItem['vendorid']]['pickup_count'] += $currentItem['pickup_count'];
     } else {
      $result[$currentItem['vendorid']] = $currentItem;
     }
     return $result;
    });

    $orders['status'] = '1';
    $orders['pickups'] = $jsonPickup;
    $orders['message'] = '';
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = '0';
    $orders['message'] = 'Data not found';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = '0';
   $orders['message'] = 'Please check the userkey';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function pullvendorpickups($userkey, $pickupid, $clientid, $vendorno, $pickupboyid, $pickupdate, $status, $pageIndex, $pageSize) {
  $today = date('Y-m-d');
  $orders = array();
  $json = array();
  $jsonvendor = array();
  $jsonclient = array();
  $validation = $this->check_userkey($userkey);
  if ($validation['status'] == "successful") {
   $userid = $validation['userid'];
   $customerno = $validation['customerno'];
   $pdo = $this->pickupdb->CreatePDOConn();
   $todaysdate = date("Y-m-d H:i:s");
   $sp_params = "'" . $customerno . "'"
    . ",'" . $pickupid . "'"
    . ",'" . $clientid . "'"
    . ",'" . $vendorno . "'"
    . ",'" . $userid . "'"
    . ",'" . $pickupdate . "'"
    . ",'" . $status . "'"
    . ",'" . $pageIndex . "'"
    . ",'" . $pageSize . "'"
    . "," . '@recordCount';

   $queryCallSP = "CALL " . SP_PULL_PICKUPS . "($sp_params)";
   $arrResultPickups = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
   $this->pickupdb->ClosePDOConn($pdo);
   if (isset($arrResultPickups) && !empty($arrResultPickups)) {
    foreach ($arrResultPickups as $data) {
     $order = new VOPickups();

     $order->pickupid = $data['oid'];
     if ($data['vendorid'] != '') {
      $order->vendorno = $data['vendorno'];
      $order->vendorid = $data['vendorid'];
      $order->vendorname = $data['vendorname'];
     } else {
      $order->vendorno = '';
      $order->vendorid = '';
      $order->vendorname = '';
     }
     if ($data['customerid'] != '') {
      $order->customerid = $data['customerid'];
      $order->customername = $data['customername'];
     } else {
      $order->customerid = '';
      $order->customername = '';
     }

     $order->scanno = $data['orderid'];

     $arr['vendorno'] = $data['vendorno'];
     $arr['vendorid'] = $data['vendorid'];
     $arr['vendorname'] = $data['vendorname'];

     $arrayVendor[] = $arr;

     $json[] = $order;
    }
    $arrayVendor = array_unique($arrayVendor);
    foreach ($arrayVendor as $arrlist) {
     $vendor = new VOPickups();
     $vendor->vendorno = $arrlist['vendorno'];
     $vendor->vendorid = $arrlist['vendorid'];
     $vendor->vendorname = $arrlist['vendorname'];
     $jsonvendor[] = $vendor;
    }
    $pdo = $this->pickupdb->CreatePDOConn();
    $sp_params = "'" . $customerno . "'";
    $queryCallSP = "CALL " . SP_PULL_CLIENTS . "($sp_params)";
    $arrResultClient = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
    $this->pickupdb->ClosePDOConn($pdo);
    if (isset($arrResultClient) && !empty($arrResultClient)) {
     foreach ($arrResultClient as $data) {
      $client = new VOPickups();
      $client->cleintid = $data['customerid'];
      $client->clientname = $data['customername'];
      $jsonclient[] = $client;
     }
    }

    $orders['status'] = '1';
    $orders['pickups'] = $json;
    $orders['clients'] = $jsonclient;
    //$orders['vendors'] = $jsonvendor;
    $orders['message'] = '';
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = '0';
    $orders['message'] = 'Data not found';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = '0';
   $orders['message'] = 'Please check the userkey';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function pickup($userkey, $pickupid, $clientid, $vendorno, $pickupboyid, $pickupdate, $status, $pageIndex, $pageSize) {
  $today = date('Y-m-d');
  $orders = array();
  $json = array();
  $jsonvendor = array();
  $jsonclient = array();
  $validation = $this->check_userkey($userkey);
  if ($validation['status'] == "successful") {
   $userid = $validation['userid'];
   $customerno = $validation['customerno'];
   $pdo = $this->pickupdb->CreatePDOConn();
   $todaysdate = date("Y-m-d H:i:s");
   $sp_params = "'" . $customerno . "'"
    . ",'" . $pickupid . "'"
    . ",'" . $clientid . "'"
    . ",'" . $vendorno . "'"
    . ",'" . $userid . "'"
    . ",'" . $pickupdate . "'"
    . ",'" . $status . "'"
    . ",'" . $pageIndex . "'"
    . ",'" . $pageSize . "'"
    . "," . '@recordCount';

   $queryCallSP = "CALL " . SP_PULL_PICKUPS . "($sp_params)";
   $arrResultPickups = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
   $this->pickupdb->ClosePDOConn($pdo);
   if (isset($arrResultPickups) && !empty($arrResultPickups)) {
    foreach ($arrResultPickups as $data) {
     $order = new VOPickups();
     $order->pickupid = $data['oid'];
     if ($data['vendorid'] != '') {
      $order->vendorno = $data['vendorno'];
      $order->vendorid = $data['vendorid'];
      $order->vendorname = $data['vendorname'];
     } else {
      $order->vendorno = '';
      $order->vendorid = '';
      $order->vendorname = '';
     }
     if ($data['customerid'] != '') {
      $order->customerid = $data['customerid'];
      $order->customername = $data['customername'];
     } else {
      $order->customerid = '';
      $order->customername = '';
     }

     $order->scanno = $data['orderid'];



     $json[] = $order;
    }
    $orders['status'] = '1';
    $orders['pickups'] = $json;
    $orders['message'] = '';
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = '0';
    $orders['message'] = 'Data not found';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = '0';
   $orders['message'] = 'Please check the userkey';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function pullvendors($userkey) {
  $orders = array();
  $jsonvendor = array();
  $validation = $this->check_userkey($userkey);
  if ($validation['status'] == "successful") {
   $userid = $validation['userid'];
   $customerno = $validation['customerno'];

   $pdo = $this->pickupdb->CreatePDOConn();
   $sp_params = "'" . $customerno . "'"
    . ",'" . $userid . "'";
   $queryCallSP = "CALL " . SP_PULL_VENDORS . "($sp_params)";
   $arrResultVendor = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
   $this->pickupdb->ClosePDOConn($pdo);
   if (isset($arrResultVendor) && !empty($arrResultVendor)) {
    foreach ($arrResultVendor as $data) {
     $vendor = new VOPickups();
     $vendor->vendorid = $data['vendorid'];
     $vendor->vendorname = $data['vendorname'];
     $vendor->vendoraddress = $data['address'];
     $jsonvendor[] = $vendor;
    }
    $orders['status'] = '1';
    $orders['result'] = $jsonvendor;
    $orders['message'] = '';
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = '0';
    $orders['message'] = 'Data Not Found';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = '0';
   $orders['message'] = 'please check the userkey';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function pullclients($userkey) {
  $orders = array();
  $jsonclient = array();
  $validation = $this->check_userkey($userkey);
  if ($validation['status'] == "successful") {
   $userid = $validation['userid'];
   $customerno = $validation['customerno'];


   $pdo = $this->pickupdb->CreatePDOConn();
   $sp_params = "'" . $customerno . "'";
   $queryCallSP = "CALL " . SP_PULL_CLIENTS . "($sp_params)";
   $arrResultClient = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
   $this->pickupdb->ClosePDOConn($pdo);
   if (isset($arrResultClient) && !empty($arrResultClient)) {
    foreach ($arrResultClient as $data) {
     $client = new VOPickups();
     $client->cleintid = $data['customerid'];
     $client->clientname = $data['customername'];
     $jsonclient[] = $client;
    }
    $orders['status'] = '1';
    $orders['result'] = $jsonclient;
    $orders['message'] = '';
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = '0';
    $orders['message'] = 'Data not found';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = '0';
   $orders['message'] = 'please check the userkey';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function pullorder_details($userkey, $pickupid, $clientid, $vendorno, $pickupboyid, $pickupdate, $status) {
  $today = date('Y-m-d');
  $orders = array();
  $json = array();
  $validation = $this->check_userkey($userkey);
  if ($validation['status'] == "successful") {
   $userid = $validation['userid'];
   $customerno = $validation['customerno'];
   $pdo = $this->pickupdb->CreatePDOConn();
   $todaysdate = date("Y-m-d H:i:s");
   $sp_params = "'" . $customerno . "'"
    . ",'" . $pickupid . "'"
    . ",'" . $clientid . "'"
    . ",'" . $vendorno . "'"
    . ",'" . $userid . "'"
    . ",'" . $pickupdate . "'"
    . ",'" . $status . "'";

   $queryCallSP = "CALL " . SP_PULL_PICKUPS . "($sp_params)";
   $arrResultPickups = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
   $this->pickupdb->ClosePDOConn($pdo);
   if (isset($arrResultPickups) && !empty($arrResultPickups)) {
    foreach ($arrResultPickups as $data) {
     $order = new VOPickups();

     $order->oid = $data['oid'];
     $order->orderid = $data['orderid'];
     $order->customer = $data['customername'];
     $order->vendor = $data['vendorname'];
     $order->fulfillmentid = $data['fulfillmentid'];
     $order->awbno = $data['awbno'];

     $order->scanno = $data['orderid'];

     if ($data['customerid'] == "") {
      $order->clientid = 0;
     } else {
      $order->clientid = $data['customerid'];
     }
     if ($data['vendorid'] == "") {
      $order->vendorid = 0;
     } else {
      $order->vendorid = $data['vendorid'];
     }
     $order->shipper = $data['sname'];
     $order->pickup_boy = $this->get_pickupboy($data['pickupboyid']);
     $json[] = $order;
    }
    $orders['status'] = '0';
    $orders['result'] = $json;
    $orders['message'] = '';
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = '0';
    $orders['message'] = 'Data not found';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = '0';
   $orders['message'] = 'Please check the userkey';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function pulldelivery_pending($userkey, $pickupid, $clientid, $vendorno, $pickupboyid, $pickupdate, $status, $pageIndex, $pageSize) {
  $today = date('Y-m-d');
  $orders = array();
  $json = array();
  $validation = $this->check_userkey($userkey);
  if ($validation['status'] == "successful") {
   $userid = $validation['userid'];
   $customerno = $validation['customerno'];
   $pdo = $this->deliverydb->CreatePDOConn();
   $todaysdate = date("Y-m-d H:i:s");
   $sp_params = "'" . $customerno . "'"
    . ",'" . $pickupid . "'"
    . ",'" . $vendorno . "'"
    . ",'" . $userid . "'"
    . ",'" . $pickupdate . "'"
    . ",'" . $status . "'"
    . ",'" . $pageIndex . "'"
    . ",'" . $pageSize . "'"
    . "," . '@recordCount';

   $queryCallSP = "CALL " . SP_PULL_DELIVERY . "($sp_params)";
   $arrResultPickups = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
   $this->pickupdb->ClosePDOConn($pdo);
   $pending_count = 0;
   if (isset($arrResultPickups) && !empty($arrResultPickups)) {
    foreach ($arrResultPickups as $data) {
     $order = new VOPickups();
     //$order->name = $data['full_name'];
     if ($data['vendorno'] != '') {
      $vendorinfo = $this->getVendorno($data['vendorno']);
      $order->vendorno = $vendorinfo['vendorid'];
      $order->vendorid = $vendorinfo['vendorid'];
      $order->type = 'Vendor';
      $order->address = $vendorinfo['address'];
      $order->name = $vendorinfo['vendorname'];
     } else {
      $order->vendorno = '';
      $order->vendorid = '';
      $order->type = 'Customer';
      $order->address = $data['address_main'];
      $order->name = '';
     }
     $order->delivery_count = 1;
     $pending_count += 1;
     $json[] = $order;
    }
    //print_r($json);
    $array = json_decode(json_encode($json), true);
    $jsonPickup = array_reduce($array, function ($result, $currentItem) {
     if (isset($result[$currentItem['vendorno']])) {
      $result[$currentItem['vendorno']]['delivery_count'] += $currentItem['delivery_count'];
     } else {
      $result[$currentItem['vendorno']] = $currentItem;
     }
     return $result;
    });
    $delivery = array();
    foreach ($jsonPickup as $data) {
     $order = new VOPickups();
     $order->name = $data['name'];
     $order->address = $data['address'];
     $order->vendorno = $data['vendorno'];
     $order->vendorid = $data['vendorid'];
     $order->type = 'Vendor';
     $order->delivery_count = $data['delivery_count'];
     $delivery[] = $order;
    }


    $orders['status'] = '1';
    $orders['pending_count'] = $pending_count;
    $orders['delivery'] = $delivery;

    $orders['message'] = '';
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = '0';
    $orders['message'] = 'Data not found';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = '0';
   $orders['message'] = 'Please check the userkey';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function pullvendordelivery($userkey, $pickupid, $vendorno, $pickupboyid, $pickupdate, $status, $pageIndex, $pageSize) {
  $today = date('Y-m-d');
  $orders = array();
  $json = array();
  $jsonclient = array();
  $jsonvendor = array();
  $validation = $this->check_userkey($userkey);
  if ($validation['status'] == "successful") {
   $userid = $validation['userid'];
   $customerno = $validation['customerno'];
   $pdo = $this->deliverydb->CreatePDOConn();
   $todaysdate = date("Y-m-d H:i:s");
   $sp_params = "'" . $customerno . "'"
    . ",'" . $pickupid . "'"
    . ",'" . $vendorno . "'"
    . ",'" . $userid . "'"
    . ",'" . $pickupdate . "'"
    . ",'" . $status . "'"
    . ",'" . $pageIndex . "'"
    . ",'" . $pageSize . "'"
    . "," . '@recordCount';

   $queryCallSP = "CALL " . SP_PULL_DELIVERY . "($sp_params)";
   $arrResultPickups = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
   $this->deliverydb->ClosePDOConn($pdo);
   if (isset($arrResultPickups) && !empty($arrResultPickups)) {
    foreach ($arrResultPickups as $data) {
     $order = new VOPickups();
     $vendor = new VOPickups();
     $order->deliveryid = $data['id'];
     if ($data['vendorno'] != '') {
      $vendorinfo = $this->getVendorno($data['vendorno']);
      $order->vendorno = $vendorinfo['vendorid'];
      $order->vendorid = $vendorinfo['vendorid'];
      $order->type = 'Vendor';
      $order->vendorname = $vendorinfo['vendorname'];
     } else {
      $order->vendorno = '';
      $order->vendorid = '';
      $order->type = 'Customer';
      $order->vendorname = '';
     }
     $order->customerid = '1';
     $order->customername = 'Rediffmail';
     $order->scanno = $data['order_id'];

     $arr['vendorno'] = $vendorinfo['vendorid'];
     $arr['vendorid'] = $vendorinfo['vendorid'];
     $arr['vendorname'] = $vendorinfo['vendorname'];

     $arrayVendor[] = $arr;
     $json[] = $order;
    }
    /*
      $arrayVendor = array_unique($arrayVendor);
      foreach ($arrayVendor as $arrlist) {
      $vendor = new VOPickups();
      $vendor->vendorno = $arrlist['vendorno'];
      $vendor->vendorid = $arrlist['vendorid'];
      $vendor->vendorname = $arrlist['vendorname'];
      $jsonvendor[] = $vendor;
      }
     *
     */
    $pdo = $this->pickupdb->CreatePDOConn();
    $sp_params = "'" . $customerno . "'";
    $queryCallSP = "CALL " . SP_PULL_CLIENTS . "($sp_params)";
    $arrResultClient = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
    $this->pickupdb->ClosePDOConn($pdo);
    if (isset($arrResultClient) && !empty($arrResultClient)) {
     foreach ($arrResultClient as $data) {
      $client = new VOPickups();
      $client->cleintid = $data['customerid'];
      $client->clientname = $data['customername'];
      $jsonclient[] = $client;
     }
    }

    $orders['status'] = '1';
    $orders['delivery'] = $json;
    $orders['clients'] = $jsonclient;
    //$orders['vendors'] = $jsonvendor;
    $orders['message'] = '';
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = '0';
    $orders['message'] = 'Data not found';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = '0';
   $orders['message'] = 'Please check the userkey';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function pull_reasons($userkey) {
  $reasons = array();
  $json = array();
  $validation = $this->check_userkey($userkey);
  if ($validation['status'] == "successful") {
   $customerno = $validation['customerno'];
   $userid = $validation['userid'];
   $sqlquery = "select * from pickup_reason WHERE customerno IN(0, $customerno) AND isdeleted=0";
   $result = $this->pickupdb->query($sqlquery, __FILE__, __LINE__);
   while ($data = $this->pickupdb->fetch_array($result)) {
    $reason = new VOPickups();
    $reason->reasonid = $data['reasonid'];
    $reason->customerno = $customerno;
    $reason->reason = $data['reason'];
    //$reason->timestamp = $data['timestamp'];
    $json[] = $reason;
   }
   $reasons['status'] = '1';
   $reasons['result'] = $json;
   $reasons['message'] = '';
   echo json_encode($reasons);
   return json_encode($reasons);
  } else {
   $reasons['status'] = '0';
   $reasons['message'] = 'Please check userkey';
   echo json_encode($reasons);
   return json_encode($reasons);
  }
 }

 public function getVendorno($vendorid) {
  $sql = "select * from  " . DB_PICKUP . ".pickup_vendor where vendorid=$vendorid";
  $result = $this->pickupdb->query($sql, __FILE__, __LINE__);
  if ($this->pickupdb->num_rows($result) > 0) {
   $row = $this->pickupdb->fetch_array($result);
   $vendor['vendorid'] = $row['vendorid'];
   $vendor['vendorname'] = $row['vendorname'];
   $vendor['address'] = $row['address'];
  }
  return $vendor;
 }

 public function getVendorInfo($vendorno) {
  $sql = "select * from  " . DB_PICKUP . ".pickup_vendor where vendorid=$vendorid";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {
   $row = $this->dbspeed->fetch_array($result);
   return $row['vendorno'];
  }
 }

 public function pushstatus($userkey, $pickupids, $reasonid, $vendorno, $clientid) {
  $today = date('Y-m-d');
  $orders = array();
  $json = array();
  $validation = $this->check_userkey($userkey);
  if ($validation['status'] == "successful") {
   $customerno = $validation['customerno'];
   $userid = $validation['userid'];

   $pids = array();
   $insertpids = array();
   $pickup = json_decode($pickupids);
   foreach ($pickup as $up) {
    if ($up->pickupid != '-1') {
     $order = new VOPickups();
     $order->pid = $up->pickupid;
     $pids[] = $order;
    } else {
     $order = new VOPickups();
     $order->scanno = $up->scanno;
     $order->pickupid = $up->pickupid;
     $order->vendorno = $vendorno;
     $order->clientid = $clientid;
     $insertpids[] = $order;
    }
   }
   if (isset($insertpids)) {
    foreach ($insertpids as $insert) {

     $Query = "INSERT INTO pickup_order(customerno, customerid, vendorno, orderid, pickupboyid, pickupdate,isdeleted)VALUES(%d, %d,'%s', '%s', '%s','%s', 0);";


     $SQL = sprintf($Query, $customerno, $insert->clientid, $insert->vendorno, $insert->scanno, $userid, $today);
     $this->pickupdb->query($SQL, __FILE__, __LINE__);
     $order = new VOPickups();
     $order->pid = $this->pickupdb->last_insert_id();
     $pids[] = $order;
    }
   }
   if (isset($pids)) {
    foreach ($pids as $pid) {
     //$sql = "Update pickup_order SET reasonid=$reasonid where oid='$pid->pid' and pickupboyid=$userid";
     $sql = "Update pickup_order SET status=2, reasonid=$reasonid where oid='$pid->pid' and pickupboyid=$userid";
     $res = $this->pickupdb->query($sql, __FILE__, __LINE__);
    }
   }
   if (!empty($res)) {
    $orders['status'] = '1';
    $orders['message'] = '';
   } else {
    $orders['status'] = '0';
    $orders['message'] = 'record not updated';
   }
  } else {
   $orders['status'] = '0';
   $orders['message'] = 'Please check userkey';
  }

  echo json_encode($orders);
  return json_encode($orders);
 }

 public function pushcancellation($userkey, $pickupids, $reasonid, $vendorno, $clientid) {
  $today = date('Y-m-d');
  $delivery = array();
  $validation = $this->check_userkey($userkey);
  if ($validation['status'] == "successful") {
   $customerno = $validation['customerno'];
   $userid = $validation['userid'];

   $pids = array();
   $insertpids = array();
   $pickup = json_decode($pickupids);
   foreach ($pickup as $up) {
    if ($up->pickupid != '-1') {
     $order = new VOPickups();
     $order->pid = $up->pickupid;
     $pids[] = $order;
    } else {
     $order = new VOPickups();
     $order->scanno = $up->scanno;
     $order->pickupid = $up->pickupid;
     $order->vendorno = $vendorno;
     $order->clientid = $clientid;
     $insertpids[] = $order;
    }
   }

   foreach ($insertpids as $insert) {
    $Query = "INSERT INTO master_orders(customerno, clientid, vendorno, order_id, deliveryboyid, delivery_date)VALUES(%d,%d,'%s', '%s', '%s','%s');";
    $SQL = sprintf($Query, $customerno, $insert->clientid, $insert->vendorno, $insert->scanno, $userid, $today);
    $this->deliverydb->query($SQL, __FILE__, __LINE__);
    $order = new VOPickups();
    $order->pid = $this->deliverydb->last_insert_id();
    $pids[] = $order;
   }

   foreach ($pids as $pid) {
    $sql = "Update master_orders SET is_deliverd=2, reasonid=$reasonid where id=$pid->pid and deliveryboyid=$userid";
    //$sql = "Update master_orders SET reasonid=$reasonid where id=$pid->pid and deliveryboyid=$userid";
    $res = $this->deliverydb->query($sql, __FILE__, __LINE__);
   }

   if (!empty($res)) {
    $delivery['status'] = '1';
    $delivery['message'] = '';
   }
  } else {
   $delivery['status'] = '0';
   $delivery['message'] = 'Please check userkey';
  }
  echo json_encode($delivery);
  return json_encode($delivery);
 }

 public function pushsignature($userkey, $pickupids, $type, $vendorno, $clientid, $signature) {
  $today = date('Y-m-d');
  $pickups = array();
  $validation = $this->check_userkey($userkey);
  if ($validation['status'] == "successful") {

   $customerno = $validation['customerno'];
   $userid = $validation['userid'];
   if ($type == 'pickup') {
    $target_path = "../../../../customer/" . $customerno . "/pickup/";
   } else if ($type == 'delivery') {
    $target_path = "../../../../customer/" . $customerno . "/delivery/";
   }

   if (!is_dir($target_path)) {
    mkdir($target_path, 0777, true) or die("Could not create directory");
   }

   $target_path_signature = $target_path . "signature/";

   if (!is_dir($target_path_signature)) {
    mkdir($target_path_signature, 0777, true) or die("Could not create directory");
   }

   $pids = array();
   $insertpids = array();
   $pickup = json_decode($pickupids);

   foreach ($pickup as $up) {
    if ($up->pickupid != '-1') {
     $order = new VOPickups();
     $order->pid = $up->pickupid;
     $pids[] = $order;
    } else {
     $order = new VOPickups();

     $order->scanno = $up->scanno;
     $order->pickupid = $up->pickupid;
     $order->vendorno = $vendorno;
     $order->clientid = $clientid;
     $insertpids[] = $order;
    }
   }
   if ($type == 'pickup') {
    foreach ($insertpids as $insert) {

     $Query = "INSERT INTO pickup_order(customerno, customerid, vendorno, orderid, pickupboyid, pickupdate, isdeleted)VALUES(%d, %d,'%s', '%s', '%s','%s', 0);";


     $SQL = sprintf($Query, $customerno, $insert->clientid, $insert->vendorno, $insert->scanno, $userid, $today);
     $this->pickupdb->query($SQL, __FILE__, __LINE__);
     $order = new VOPickups();
     $order->pid = $this->pickupdb->last_insert_id();
     $pids[] = $order;
    }
    if (isset($pids)) {
     foreach ($pids as $pid) {
      $image = base64_decode($signature);
      file_put_contents("../../../../customer/" . $customerno . "/pickup/signature/" . $pid->pid . ".jpg", $image);

      $sql = "Update pickup_order SET status=1 where oid=$pid->pid and pickupboyid='$userid'";
      $res = $this->pickupdb->query($sql, __FILE__, __LINE__);
     }
    }
   } else if ($type == 'delivery') {
    if (isset($insertpids)) {
     foreach ($insertpids as $insert) {
      $Query = "INSERT INTO master_orders(customerno, clientid, vendorno, order_id, deliveryboyid, delivery_date)VALUES(%d,%d,'%s', '%s', '%s','%s');";
      $SQL = sprintf($Query, $customerno, $insert->clientid, $insert->vendorno, $insert->scanno, $userid, $today);
      $this->deliverydb->query($SQL, __FILE__, __LINE__);
      $order = new VOPickups();
      $order->pid = $this->deliverydb->last_insert_id();
      $pids[] = $order;
     }
    }
    if (isset($pids)) {
     foreach ($pids as $pid) {
      $image = base64_decode($signature);
      file_put_contents("../../../../customer/" . $customerno . "/delivery/signature/" . $pid->pid . ".jpg", $image);

      $sql = "Update master_order SET is_delivered=1 where id=$pid->pid and deliveryboyid='$userid'";
      $res = $this->pickupdb->query($sql, __FILE__, __LINE__);
     }
    }
   }
   $pickups['status'] = '1';
   $pickups['message'] = '';
  } else {
   $pickups['status'] = '0';
   $pickups['message'] = 'Please check userkey';
  }
  echo json_encode($pickups);
  return json_encode($pickups);
 }

 public function pushphoto($userkey, $pickupids, $type, $vendorno, $clientid, $photo) {
  $pickups = array();
  $today = date('Y-m-d');
  $validation = $this->check_userkey($userkey);
  if ($validation['status'] == "successful") {

   $customerno = $validation['customerno'];
   $userid = $validation['userid'];
   $pids = array();
   $insertpids = array();
   $pickup = json_decode($pickupids);

   if ($type == 'pickup') {
    $target_path = "../../../../customer/" . $customerno . "/pickup/";
   } else if ($type == 'delivery') {
    $target_path = "../../../../customer/" . $customerno . "/delivery/";
   }

   if (!is_dir($target_path)) {
    mkdir($target_path, 0777, true) or die("Could not create directory");
   }




   foreach ($pickup as $up) {
    if ($up->pickupid != '-1') {
     $order = new VOPickups();
     $order->pid = $up->pickupid;
     $pids[] = $order;
    } else {
     $order = new VOPickups();

     $order->scanno = $up->scanno;
     $order->pickupid = $up->pickupid;
     $order->vendorno = $vendorno;
     $order->clientid = $clientid;
     $insertpids[] = $order;
    }
   }
   if ($type == 'pickup') {
    if (isset($insertpids)) {
     foreach ($insertpids as $insert) {
      $Query = "INSERT INTO pickup_order(customerno, customerid, vendorno, orderid, pickupboyid, pickupdate,isdeleted)VALUES(%d, %d,'%s', '%s', '%s','%s', 0);";
      $SQL = sprintf($Query, $customerno, $insert->clientid, $insert->vendorno, $insert->scanno, $userid, $today);
      $this->pickupdb->query($SQL, __FILE__, __LINE__);
      $order = new VOPickups();
      $order->pid = $this->pickupdb->last_insert_id();
      $pids[] = $order;
     }
    }
    if (isset($pids)) {
     foreach ($pids as $pid) {
      $target_path_signature = $target_path . "photo/$pid->pid";

      if (!is_dir($target_path_signature)) {
       mkdir($target_path_signature, 0777, true) or die("Could not create directory");
      }
      $files1 = scandir($target_path_signature);
      $cnt = count($files1);

      if ($cnt == 2) {
       $cnt = 1;
      } else {
       $cnt = ($cnt - 2) + 1;
      }

      $image = base64_decode($photo);
      file_put_contents("../../../../customer/" . $customerno . "/pickup/photo/" . $pid->pid . "/" . $cnt . ".jpg", $image);
     }
    }
   } else if ($type == 'delivery') {
    if (isset($insertpids)) {
     foreach ($insertpids as $insert) {
      $Query = "INSERT INTO master_orders(customerno, clientid, vendorno, order_id, deliveryboyid, delivery_date)VALUES(%d,%d,'%s', '%s', '%s','%s');";
      $SQL = sprintf($Query, $customerno, $insert->clientid, $insert->vendorno, $insert->scanno, $userid, $today);
      $this->deliverydb->query($SQL, __FILE__, __LINE__);
      $order = new VOPickups();
      $order->pid = $this->deliverydb->last_insert_id();
      $pids[] = $order;
     }
    }
    if (isset($pids)) {
     foreach ($pids as $pid) {
      $target_path_signature = $target_path . "photo/$pid->pid";

      if (!is_dir($target_path_signature)) {
       mkdir($target_path_signature, 0777, true) or die("Could not create directory");
      }
      $files1 = scandir($target_path_signature);
      $cnt = count($files1);

      if ($cnt == 2) {
       $cnt = 1;
      } else {
       $cnt = ($cnt - 2) + 1;
      }

      $image = base64_decode($photo);
      file_put_contents("../../../../customer/" . $customerno . "/delivery/photo/" . $pid->pid . "/" . $cnt . ".jpg", $image);
     }
    }
   }
   if (!empty($pids)) {
    $pickups['status'] = '1';
    $pickups['message'] = '';
   } else {
    $pickups['status'] = '0';
    $pickups['message'] = 'Record not updated';
   }
  } else {
   $pickups['status'] = '0';
   $pickups['message'] = 'Please Check userkey';
  }

  echo json_encode($pickups);
  return json_encode($pickups);
 }

}

?>
