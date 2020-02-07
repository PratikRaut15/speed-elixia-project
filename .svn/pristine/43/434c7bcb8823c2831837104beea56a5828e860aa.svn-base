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

 public function pullorders_pending($userkey, $pickupid, $clientid, $vendorno, $pickupboyid, $pickupdate, $status) {
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
    . ",'" . $status . "'";

   $queryCallSP = "CALL " . SP_PULL_PICKUPS . "($sp_params)";
   $arrResultPickups = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
   $this->pickupdb->ClosePDOConn($pdo);
   if (isset($arrResultPickups) && !empty($arrResultPickups)) {
    foreach ($arrResultPickups as $data) {
     $order = new VOPickups();
     $order->pickupid = $data['oid'];
     if ($data['customerid'] == 1) {
      $order->scanno = $data['awbno'];
     } else if ($data['customerid'] == 2) {
      $order->scanno = $data['fulfillmentid'];
     } else {
      $order->scanno = "";
     }
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
     $json[] = $order;
    }


    $pdo = $this->pickupdb->CreatePDOConn();
    $sp_params = "'" . $customerno . "'"
     . ",'" . $userid . "'";
    $queryCallSP = "CALL " . SP_PULL_VENDORS . "($sp_params)";
    $arrResultVendor = $pdo->query($queryCallSP)->fetchAll(PDO::FETCH_ASSOC);
    $this->pickupdb->ClosePDOConn($pdo);
    if (isset($arrResultVendor) && !empty($arrResultVendor)) {
     foreach ($arrResultVendor as $data) {
      $vendor = new VOPickups();
      $vendor->vendorno = $data['vendorid'];
      $vendor->vendorname = $data['vendorname'];
      $vendor->vendoraddress = $data['address'];
      $jsonvendor[] = $vendor;
     }
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
    $orders['result'] = $json;
    $orders['vendors'] = $jsonvendor;
    $orders['clients'] = $jsonclient;
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
     $vendor->vendorno = $data['vendorid'];
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
     if ($data['customerid'] == 1) {
      $order->scanno = $data['awbno'];
     } else if ($data['customerid'] == 2) {
      $order->scanno = $data['fulfillmentid'];
     } else {
      $order->scanno = "";
     }
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

 public function pull_reasons($userkey) {
  $reasons = array();
  $json = array();
  $sql = "SELECT * from " . DATABASE_SPEED . ".user WHERE userkey = '$userkey' AND isdeleted = 0 limit 1";
  $record = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($record) > 0) {
   $row = $this->dbspeed->fetch_array($record);
   $customerno = $row['customerno'];
   $sqlquery = "select * from " . DB_PICKUP . ".pickup_reason WHERE customerno IN(0, $customerno) AND isdeleted=0";
   $result = $this->db->query($sqlquery, __FILE__, __LINE__);
   while ($data = $this->db->fetch_array($result)) {
    $reason = new VOPickups();
    $reason->reasonid = $data['reasonid'];
    $reason->customerno = $row['customerno'];
    $reason->reason = $data['reason'];
    $reason->timestamp = $data['timestamp'];
    $json[] = $reason;
   }
   $reasons['status'] = '1';
   $reasons['result'] = $json;
   echo json_encode($reasons);
   return json_encode($reasons);
  } else {
   $reasons['status'] = '0';
   echo json_encode($reasons);
   return json_encode($reasons);
  }
 }

 public function getVendorno($vendorid) {
  $sql = "select * from  " . DB_PICKUP . ".pickup_vendor where vendorid=$vendorid";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {
   $row = $this->dbspeed->fetch_array($result);
   return $row['vendorno'];
  }
 }

 public function pushstatus($userkey, $pickupids, $reasonid) {
  $today = date('Y-m-d');
  $orders = array();
  $json = array();
  $sql = "SELECT * from " . DATABASE_SPEED . ".user WHERE userkey = '$userkey' AND isdeleted = 0 limit 1";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {
   $row = $this->dbspeed->fetch_array($result);
   $customerno = $row['customerno'];
   $userid = $row['userid'];

   $pids = array();
   $insertpids = array();
   $pickup = json_decode($pickupids);
   foreach ($pickup as $up) {
    if ($up->pickupid != '-1') {
     $order = new VODelivery();
     $order->pid = $up->pickupid;
     $pids[] = $order;
    } else {
     $order = new VODelivery();
     $order->type = $up->type;
     $order->scanno = $up->scanno;
     $order->pickupid = $up->pickupid;
     $order->vendorno = $up->vendorid;
     $order->clientid = $up->clientid;
     $insertpids[] = $order;
    }
   }
   foreach ($insertpids as $insert) {
    if ($insert->type == '0') {
     $Query = "INSERT INTO " . DB_PICKUP . ".pickup_order(customerno, customerid, vendorno, awbno, pickupboyid, pickupdate,isdeleted)VALUES(%d, '1','%s', '%s', '%s','%s', 0);";
    } else {
     $Query = "INSERT INTO " . DB_PICKUP . ".pickup_order(customerno, customerid, vendorno, fulfillmentid, pickupboyid, pickupdate, isdeleted)VALUES(%d, '2','%s', '%s', '%s','%s', 0);";
    }

    $SQL = sprintf($Query, $customerno, $insert->vendorno, $insert->scanno, $userid, $today);
    $this->dbspeed->query($SQL, __FILE__, __LINE__);
    $order = new VODelivery();
    $order->pid = $this->dbspeed->last_insert_id();
    $pids[] = $order;
   }

   foreach ($pids as $pid) {
    $sql = "Update " . DB_PICKUP . ".pickup_order SET status=2, reasonid=$reasonid where oid=$pid->pid and pickupboyid=$userid";
    $res = $this->db->query($sql, __FILE__, __LINE__);
   }
   if (!empty($res)) {
    echo '{"status":"1"}';
    return '{"status":"0"}';
   } else {
    echo '{"status":"0"}';
    return '{"status":"0"}';
   }
  } else {
   echo '{"status":"0"}';
   return '{"status":"0"}';
  }
 }

 public function pushsignature($userkey, $pickupids, $signature) {
  $today = date('Y-m-d');
  $sql = "SELECT * from " . DATABASE_SPEED . ".user WHERE userkey = '$userkey' AND isdeleted = 0 limit 1";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {
   $row = $this->dbspeed->fetch_array($result);
   $customerno = $row['customerno'];
   $userid = $row['userid'];

   $target_path = "../../../customer/" . $customerno . "/pickup/";
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
     $order = new VODelivery();
     $order->pid = $up->pickupid;
     $pids[] = $order;
    } else {
     $order = new VODelivery();
     $order->type = $up->type;
     $order->scanno = $up->scanno;
     $order->pickupid = $up->pickupid;
     $order->vendorno = $up->vendorid;
     $order->clientid = $up->clientid;
     $insertpids[] = $order;
    }
   }
   foreach ($insertpids as $insert) {
    if ($insert->type == '0') {
     $Query = "INSERT INTO " . DB_PICKUP . ".pickup_order(customerno, customerid, vendorno, awbno, pickupboyid, pickupdate,isdeleted)VALUES(%d, '1','%s', '%s', '%s','%s', 0);";
    } else {
     $Query = "INSERT INTO " . DB_PICKUP . ".pickup_order(customerno, customerid, vendorno, fulfillmentid, pickupboyid, pickupdate, isdeleted)VALUES(%d, '2','%s', '%s', '%s','%s', 0);";
    }

    $SQL = sprintf($Query, $customerno, $insert->vendorno, $insert->scanno, $userid, $today);
    $this->dbspeed->query($SQL, __FILE__, __LINE__);
    $order = new VODelivery();
    $order->pid = $this->dbspeed->last_insert_id();
    $pids[] = $order;
   }
   foreach ($pids as $pid) {
    $image = base64_decode($signature);
    file_put_contents("../../../customer/" . $customerno . "/pickup/signature/" . $pid->pid . ".jpg", $image);

    $sql = "Update " . DB_PICKUP . ".pickup_order SET status=1 where oid=$pid->pid and pickupboyid='$userid'";
    $res = $this->db->query($sql, __FILE__, __LINE__);
   }
   echo '{"status":"1"}';
  } else {
   echo '{"status":"0"}';
  }
 }

 public function pushphoto($userkey, $pickupids, $photo) {

  $today = date('Y-m-d');
  $sql = "SELECT * from " . DATABASE_SPEED . ".user WHERE userkey = '$userkey' AND isdeleted = 0 limit 1";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {
   $row = $this->dbspeed->fetch_array($result);
   $customerno = $row['customerno'];
   $userid = $row['userid'];
   $pids = array();
   $insertpids = array();
   $pickup = json_decode($pickupids);
   foreach ($pickup as $up) {
    if ($up->pickupid != '-1') {
     $order = new VODelivery();
     $order->pid = $up->pickupid;
     $pids[] = $order;
    } else {
     $order = new VODelivery();
     $order->type = $up->type;
     $order->scanno = $up->scanno;
     $order->pickupid = $up->pickupid;
     $order->vendorno = $up->vendorid;
     $order->clientid = $up->clientid;
     $insertpids[] = $order;
    }
   }
   foreach ($insertpids as $insert) {
    if ($insert->type == '0') {
     $Query = "INSERT INTO " . DB_PICKUP . ".pickup_order(customerno, customerid, vendorno, awbno, pickupboyid, pickupdate,isdeleted)VALUES(%d, '1','%s', '%s', '%s','%s', 0);";
    } else {
     $Query = "INSERT INTO " . DB_PICKUP . ".pickup_order(customerno, customerid, vendorno, fulfillmentid, pickupboyid, pickupdate, isdeleted)VALUES(%d, '2','%s', '%s', '%s','%s', 0);";
    }

    $SQL = sprintf($Query, $customerno, $insert->vendorno, $insert->scanno, $userid, $today);
    $this->dbspeed->query($SQL, __FILE__, __LINE__);
    $order = new VODelivery();
    $order->pid = $this->dbspeed->last_insert_id();
    $pids[] = $order;
   }
   foreach ($pids as $pid) {

    $target_path = "../../../customer/" . $customerno . "/pickup/";
    if (!is_dir($target_path)) {
     mkdir($target_path, 0777, true) or die("Could not create directory");
    }

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
    file_put_contents("../../../customer/" . $customerno . "/pickup/photo/" . $pid->pid . "/" . $cnt . ".jpg", $image);
   }
   echo '{"status":"1"}';
  } else {
   echo '{"status":"0"}';
  }
 }

}

?>