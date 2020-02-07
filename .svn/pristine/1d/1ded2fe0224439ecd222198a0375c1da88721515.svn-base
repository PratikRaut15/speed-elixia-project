<?php

class VODelivery {

}

class VODevices {

}

class api {

 var $status;
 var $status_time;

 // construct
 function __construct() {
  $this->db = new database(DATABASE_HOST, DATABASE_PORT, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
  $this->dbspeed = new database(DATABASE_HOST, DATABASE_PORT, DATABASE_USER, DATABASE_PASSWORD, DATABASE_SPEED);
 }

 public function check_validity_login($expirydate, $currentdate) {
  date_default_timezone_set("Asia/Calcutta");
  $realtime = strtotime($currentdate);
  $expirytime = strtotime($expirydate);
  $diff = $expirytime - $realtime;
  return $diff;
 }

 function check_login($username, $password) {
  $sql = "select *," . TBL_ADMIN_CUSTOMER . ".customerno as customer_no, (SELECT groupman.isdeleted FROM groupman WHERE groupman.userid = " . TBL_ADMIN_USER . ".userid
                            ORDER BY groupman.gmid DESC LIMIT 0 , 1) AS grpdel from " . TBL_ADMIN_USER . " INNER JOIN " . TBL_ADMIN_CUSTOMER . " ON " . TBL_ADMIN_CUSTOMER . ".customerno = " . TBL_ADMIN_USER . ".customerno INNER JOIN `android_version` where username='" . $username . "' AND isdeleted=0 limit 1";

  $record = $this->db->query($sql, __FILE__, __LINE__);
  $row = $this->db->fetch_array($record);

  $retarray = array();
  //$password = sha1($password);
  if ($username == $row['username'] and $password == $row['password']) {
   $devices = $this->checkforvalidity($row['customer_no']);
   $initday = 1;

   if (isset($devices)) {

    foreach ($devices as $thisdevice) {
     $days = $this->check_validity_login($thisdevice->expirydate, $thisdevice->today);
     if ($days > 0) {
      $initday = $days;
     }
    }
   }
   if ($initday > 0) {

    $retarray['status'] = "success";
    $retarray['userkey'] = $row['userkey'];
    $retarray['realname'] = $row['realname'];
    $retarray['customercompany'] = $row['customercompany'];
    $this->update_push_android_chk($row['userkey'], 1);
    $this->update_push_android_chk_main($row['userkey'], 1);
    $today = date("Y-m-d H:i:s");
    $sql = "UPDATE user SET lastlogin_android='" . $today . "' where userkey = '" . $row['userkey'] . "' AND customerno= '" . $row['customer_no'] . "' LIMIT 1";
    $this->db->query($sql, __FILE__, __LINE__);
   } else {
    $retarray['status'] = "failure";
   }
  } else {
   $retarray['status'] = "failure";
  }
  echo json_encode($retarray);
  return $retarray;
 }

 function check_userkey($userkey) {
  $sql = "select * from " . TBL_ADMIN_USER . " where userkey='" . $userkey . "' AND isdeleted=0";
  $record = $this->db->query($sql, __FILE__, __LINE__);
  $row = $this->db->fetch_array($record);
  $retarray = array();
  if ($row['userkey'] != "") {
   $retarray['status'] = "successful";
   $retarray['customerno'] = $row["customerno"];
   $retarray['userid'] = $row["userid"];
   $retarray['realname'] = $row["realname"];
  } else {
   $retarray['status'] = "unsuccessful";
  }
  return $retarray;
 }

 function updateLogin($userkey) {
  $today = date('Y-m-d H:i:s');
  $sql = "select * from " . DATABASE_SPEED . ".user where userkey='" . $userkey . "' AND isdeleted = 0";
  $record = $this->db->query($sql, __FILE__, __LINE__);
  $row = $this->db->fetch_array($record);
  if ($row['userkey'] != "") {
   $userid = $row['userid'];
   $customerno = $row['customerno'];
   $sqlInsert = "insert into " . DATABASE_SPEED . ".login_history(userid, customerno,type,timestamp)values($userid,$customerno,1,'" . $today . "')";
   $this->db->query($sqlInsert, __FILE__, __LINE__);
  }
 }

 public function checkforvalidity($customerno) {
  $devices = Array();
  $Query = "SELECT  deviceid,expirydate, Now() as today FROM `devices` where customerno=%d";
  $devicesQuery = sprintf($Query, $customerno);
  $record = $this->db->query($devicesQuery, __FILE__, __LINE__);

  while ($row = $this->db->fetch_array($record)) {
   $device = new VODevices();
   $device->deviceid = $row['deviceid'];
   $device->today = $row["today"];
   $device->expirydate = $row["expirydate"];
   $devices[] = $device;
  }
  return $devices;
 }

 public function update_push_android_chk($userkey, $val) {
  $sql = "UPDATE " . DATABASE_SPEED . ".user SET chkmanpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
  $this->db->query($sql, __FILE__, __LINE__);
 }

 public function update_push_android_chk_main($userkey, $val) {
  $sql = "UPDATE " . DATABASE_SPEED . ".user SET chkpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
  $this->db->query($sql, __FILE__, __LINE__);
 }

 public function pullorders_pending($userkey) {
  $today = date('Y-m-d');
  $orders = array();
  $json = array();
  $jsonvendor = array();
  $jsonclient = array();
  $sql = "SELECT * from " . DATABASE_SPEED . ".user WHERE userkey = '$userkey' AND isdeleted = 0 limit 1";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {

   $row = $this->dbspeed->fetch_array($result);
   $userid = $row['userid'];
   $customerno = $row['customerno'];
   $sqlquery = "select * from " . DATABASE_NAME . ".pickup_order as a
        inner join " . DATABASE_NAME . ".vendormapping as vm   on vm.customerid = a.customerid AND vm.vendor_no = a.vendorno AND vm.isdeleted = 0
left join " . DATABASE_NAME . ".pickup_customer as b on a.customerid=b.customerid and a.customerno = b.customerno
left join " . DATABASE_NAME . ".pickup_vendor as c on  vm.vendorid = c.vendorid and a.customerno=c.customerno
left join " . DATABASE_NAME . ".pickup_shiper as d on a.shipperid=d.sid and  a.customerno = d.customerno
left join " . DATABASE_SPEED . ".user as e on a.pickupboyid = e.userid and a.customerno = e.customerno
            WHERE a.isdeleted=0 and  a.pickupboyid=$userid and a.pickupdate='$today' and a.status=0";
   $query = $this->db->query($sqlquery, __FILE__, __LINE__);
   if ($this->db->num_rows($query) > 0) {
    while ($data = $this->db->fetch_array($query)) {
     $order = new VODelivery();
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
     //$order->awbno = $data['awbno'];
     //$order->fulfillmentid = $data['fulfillmentid'];
     $json[] = $order;
    }


    $sqlvendor = "select * from " . DATABASE_NAME . ".pickup_vendor Inner join " . DATABASE_NAME . ".pinmapping on " . DATABASE_NAME . ".pinmapping.pincode = " . DATABASE_NAME . ".pickup_vendor.pincode and pinmapping.pid=$userid where pickup_vendor.customerno=$customerno";
    $queryvendor = $this->db->query($sqlvendor, __FILE__, __LINE__);
    if ($this->db->num_rows($query) > 0) {
     while ($data = $this->db->fetch_array($queryvendor)) {
      $order = new VODelivery();
      $order->vendorno = $data['vendorid'];
      $order->vendorname = $data['vendorname'];
      $order->vendoraddress = $data['address'];
      $jsonvendor[] = $order;
     }
    }

    $sqlclient = "select * from " . DATABASE_NAME . ".pickup_customer where customerno = $customerno";
    $queryclient = $this->db->query($sqlclient, __FILE__, __LINE__);
    if ($this->db->num_rows($query) > 0) {
     while ($data = $this->db->fetch_array($queryclient)) {
      $order = new VODelivery();
      $order->clientid = $data['customerid'];
      $order->clientname = $data['customername'];
      $jsonclient[] = $order;
     }
    }

    $orders['status'] = 'success';
    $orders['result'] = $json;
    $orders['vendors'] = $jsonvendor;
    $orders['clients'] = $jsonclient;
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = 'failure';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = 'failure';
   $orders['error'] = 'please checek the userkey';
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
   $sqlquery = "select * from " . DATABASE_NAME . ".pickup_reason WHERE customerno IN(0, $customerno) AND isdeleted=0";
   $result = $this->db->query($sqlquery, __FILE__, __LINE__);
   while ($data = $this->db->fetch_array($result)) {
    $reason = new VODelivery();
    $reason->reasonid = $data['reasonid'];
    $reason->customerno = $row['customerno'];
    $reason->reason = $data['reason'];
    $reason->timestamp = $data['timestamp'];
    $json[] = $reason;
   }
   $reasons['status'] = 'success';
   $reasons['result'] = $json;
   echo json_encode($reasons);
   return json_encode($reasons);
  } else {
   $reasons['status'] = 'failure';
   echo json_encode($reasons);
   return json_encode($reasons);
  }
 }

 public function pullvendors($userkey) {
  $orders = array();
  $json = array();
  $sql = "SELECT * from " . DATABASE_SPEED . ".user WHERE userkey = '$userkey' AND isdeleted = 0 limit 1";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {

   $row = $this->dbspeed->fetch_array($result);
   $userid = $row['userid'];
   $sqlquery = "select distinct(vendorid), c.address, c.vendorname from " . DATABASE_NAME . ".pickup_order
left join " . DATABASE_NAME . ".pickup_vendor as c on  " . DATABASE_NAME . ".pickup_order.vendorno = c.vendorno and " . DATABASE_NAME . ".pickup_order.customerno=c.customerno
left join " . DATABASE_SPEED . ".user as e on " . DATABASE_NAME . ".pickup_order.pickupboyid = e.userid and " . DATABASE_NAME . ".pickup_order.customerno = e.customerno
            WHERE " . DATABASE_NAME . ".pickup_order.isdeleted=0 and  " . DATABASE_NAME . ".pickup_order.pickupboyid=$userid and  status=0";
   $query = $this->db->query($sqlquery, __FILE__, __LINE__);
   if ($this->db->num_rows($query) > 0) {
    while ($data = $this->db->fetch_array($query)) {
     $order = new VODelivery();
     $order->vendorno = $data['vendorid'];
     $order->vendorname = $data['vendorname'];
     $order->vendoraddress = $data['address'];

     $json[] = $order;
    }
    $orders['status'] = 'success';
    $orders['result'] = $json;
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = 'failure';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = 'failure';
   $orders['error'] = 'please checek the userkey';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function pullclients($userkey) {
  $orders = array();
  $json = array();
  $sql = "SELECT * from " . DATABASE_SPEED . ".user WHERE userkey = '$userkey' AND isdeleted = 0 limit 1";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {

   $row = $this->dbspeed->fetch_array($result);
   $userid = $row['userid'];
   $sqlquery = "select distinct(pickup_order.customerid), b.customername from " . DATABASE_NAME . ".pickup_order
left join " . DATABASE_NAME . ".pickup_customer as b on pickup_order.customerid=b.customerid and " . DATABASE_NAME . ".pickup_order.customerno = b.customerno
left join " . DATABASE_SPEED . ".user as e on " . DATABASE_NAME . ".pickup_order.pickupboyid = e.userid and " . DATABASE_NAME . ".pickup_order.customerno = e.customerno
            WHERE " . DATABASE_NAME . ".pickup_order.isdeleted=0 and  " . DATABASE_NAME . ".pickup_order.pickupboyid=$userid and  status=0";
   $query = $this->db->query($sqlquery, __FILE__, __LINE__);
   if ($this->db->num_rows($query) > 0) {
    while ($data = $this->db->fetch_array($query)) {
     $order = new VODelivery();
     $order->clientid = $data['customerid'];
     $order->clientname = $data['customername'];
     $json[] = $order;
    }
    $orders['status'] = 'success';
    $orders['result'] = $json;
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = 'failure';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = 'failure';
   $orders['error'] = 'please checek the userkey';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function pullorders_picked($userkey) {
  $orders = array();
  $json = array();
  $sql = "SELECT * from " . DATABASE_SPEED . ".user WHERE userkey = '$userkey' AND isdeleted = 0 limit 1";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {

   $row = $this->dbspeed->fetch_array($result);
   $userid = $row['userid'];
   $sqlquery = "select * from " . DATABASE_NAME . ".pickup_order
left join " . DATABASE_NAME . ".pickup_customer as b on pickup_order.customerid=b.customerid and " . DATABASE_NAME . ".pickup_order.customerno = b.customerno
left join " . DATABASE_NAME . ".pickup_vendor as c on  " . DATABASE_NAME . ".pickup_order.vendorno = c.vendorno and " . DATABASE_NAME . ".pickup_order.customerno=c.customerno
left join " . DATABASE_NAME . ".pickup_shiper as d on " . DATABASE_NAME . ".pickup_order.shipperid=d.sid and " . DATABASE_NAME . ".pickup_order.customerno = d.customerno
left join " . DATABASE_SPEED . ".user as e on " . DATABASE_NAME . ".pickup_order.pickupboyid = e.userid and " . DATABASE_NAME . ".pickup_order.customerno = e.customerno
            WHERE " . DATABASE_NAME . ".pickup_order.isdeleted=0 and  " . DATABASE_NAME . ".pickup_order.pickupboyid=$userid and  status=1";
   $query = $this->db->query($sqlquery, __FILE__, __LINE__);
   if ($this->db->num_rows($query) > 0) {
    while ($data = $this->db->fetch_array($query)) {
     $order = new VODelivery();
     $order->oid = $data['oid'];
     $order->orderid = $data['orderid'];
     $order->customer = $data['customername'];
     $order->vendor = $data['vendorname'];
     $order->fulfillmentid = $data['fulfillmentid'];
     $order->awbno = $data['awbno'];
     $order->shipper = $data['sname'];
     $order->pickup_boy = $data['realname'];

     $json[] = $order;
    }
    $orders['status'] = 'success';
    $orders['result'] = $json;
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = 'failure';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = 'failure';
   $orders['error'] = 'please checek the userkey';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function pullorders_cancelled($userkey) {
  $orders = array();
  $json = array();
  $sql = "SELECT * from " . DATABASE_SPEED . ".user WHERE userkey = '$userkey' AND isdeleted = 0 limit 1";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {

   $row = $this->dbspeed->fetch_array($result);
   $userid = $row['userid'];
   $sqlquery = "select * from " . DATABASE_NAME . ".pickup_order
left join " . DATABASE_NAME . ".pickup_customer as b on pickup_order.customerid=b.customerid and " . DATABASE_NAME . ".pickup_order.customerno = b.customerno
left join " . DATABASE_NAME . ".pickup_vendor as c on  " . DATABASE_NAME . ".pickup_order.vendorno = c.vendorno and " . DATABASE_NAME . ".pickup_order.customerno=c.customerno
left join " . DATABASE_NAME . ".pickup_shiper as d on " . DATABASE_NAME . ".pickup_order.shipperid=d.sid and " . DATABASE_NAME . ".pickup_order.customerno = d.customerno
left join " . DATABASE_SPEED . ".user as e on " . DATABASE_NAME . ".pickup_order.pickupboyid = e.userid and " . DATABASE_NAME . ".pickup_order.customerno = e.customerno
            WHERE " . DATABASE_NAME . ".pickup_order.isdeleted=0 and  " . DATABASE_NAME . ".pickup_order.pickupboyid=$userid and  status=2";
   $query = $this->db->query($sqlquery, __FILE__, __LINE__);
   if ($this->db->num_rows($query) > 0) {
    while ($data = $this->db->fetch_array($query)) {
     $order = new VODelivery();
     $order->oid = $data['oid'];
     $order->orderid = $data['orderid'];
     $order->customer = $data['customername'];
     $order->vendor = $data['vendorname'];
     $order->fulfillmentid = $data['fulfillmentid'];
     $order->awbno = $data['awbno'];
     $order->shipper = $data['sname'];
     $order->pickup_boy = $data['realname'];

     $json[] = $order;
    }
    $orders['status'] = 'success';
    $orders['result'] = $json;
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = 'failure';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = 'failure';
   $orders['error'] = 'please checek the userkey';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function pullorder_details($userkey, $orderid) {
  $today = date('Y-m-d');
  $orders = array();
  $json = array();
  $sql = "SELECT * from " . DATABASE_SPEED . ".user WHERE userkey = '$userkey' AND isdeleted = 0 limit 1";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {

   $row = $this->dbspeed->fetch_array($result);
   $userid = $row['userid'];
   $sqlquery = "select * from " . DATABASE_NAME . ".pickup_order as a
left join " . DATABASE_NAME . ".pickup_customer as b on a.customerid=b.customerid and a.customerno = b.customerno
left join " . DATABASE_NAME . ".pickup_vendor as c on  a.vendorno = c.vendorid and a.customerno=c.customerno
left join " . DATABASE_NAME . ".pickup_shiper as d on a.shipperid=d.sid and a.customerno = d.customerno
left join " . DATABASE_SPEED . ".user as e on a.pickupboyid = e.userid and a.customerno = e.customerno
            WHERE a.orderid=$orderid and  a.pickupboyid=$userid and a.pickupdate='$today' ";
   $query = $this->db->query($sqlquery, __FILE__, __LINE__);
   if ($this->db->num_rows($query) > 0) {
    while ($data = $this->db->fetch_array($query)) {
     $order = new VODelivery();
     $order->oid = $data['oid'];
     $order->orderid = $data['orderid'];
     $order->customer = $data['customername'];
     $order->vendor = $data['vendorname'];
     $order->fulfillmentid = $data['fulfillmentid'];
     $order->awbno = $data['awbno'];
     $order->shipper = $data['sname'];
     $order->pickup_boy = $data['realname'];

     $json[] = $order;
    }
    $orders['status'] = 'success';
    $orders['result'] = $json;
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = 'failure';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = 'failure';
   $orders['error'] = 'please checek the userkey';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function pullorderdetail($userkey, $orderid) {
  $orders = array();
  $json = array();
  $sql = "SELECT * from " . DATABASE_SPEED . ".route WHERE devicekey = '$userkey' AND isdeleted = 0 limit 1";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {
   $row = $this->dbspeed->fetch_array($result);
   $routeid = $row['routeid'];
   $sqlquery = "select * from " . DATABASE_NAME . ".master_orders
                        inner join " . DATABASE_NAME . ".master_shipment on " . DATABASE_NAME . ".master_orders.id = " . DATABASE_NAME . ".master_shipment.orderid
                        inner join " . DATABASE_NAME . ".master_shipping_address on " . DATABASE_NAME . ".master_orders.id = " . DATABASE_NAME . ".master_shipping_address.orderid
                        where " . DATABASE_NAME . ".master_shipment.shipping_status = 2 AND " . DATABASE_NAME . ".master_shipment.route=$routeid
                        AND " . DATABASE_NAME . ".master_orders.status='Open' AND " . DATABASE_NAME . ".master_orders.id=$orderid";
   $query = $this->db->query($sqlquery, __FILE__, __LINE__);
   if ($this->db->num_rows($query) > 0) {
    while ($data = $this->db->fetch_array($query)) {
     $order = new VODelivery();
     $order->id = $data['id'];
     $order->order_id = $data['order_id'];
     $order->email = $data['email'];
     $order->item_count = $data['item_count'];
     $order->sub_total = $data['sub_total'];
     $order->taxes_total = $data['taxes_total'];
     $order->discount_total = $data['discount_total'];
     $order->total = $data['total'];
     $order->created_on = $data['created_on'];
     $order->updated_on = $data['updated_on'];
     $order->tracking_number = $data['tracking_number'];
     $order->shipping_status = $data['shipping_status'];
     $order->full_name = $data['full_name'];
     $order->address = $data['address'];
     $order->city = $data['city'];
     $order->state = $data['state'];
     $order->country = $data['country'];
     $order->pincode = $data['pincode'];
     $order->phone = $data['phone'];
     $json[] = $order;
    }
    $orders['status'] = 'success';
    $orders['result'] = $json;
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    echo '{"status":"failure"}';
    return '{"status":"failure"}';
   }
  } else {
   echo '{"status":"failure"}';
   return '{"status":"failure"}';
  }
 }

 public function getVendorno($vendorid) {
  $sql = "select * from  " . DATABASE_NAME . ".pickup_vendor where vendorid=$vendorid";
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
     $Query = "INSERT INTO " . DATABASE_NAME . ".pickup_order(customerno, customerid, vendorno, awbno, pickupboyid, pickupdate,isdeleted)VALUES(%d, '1','%s', '%s', '%s','%s', 0);";
    } else {
     $Query = "INSERT INTO " . DATABASE_NAME . ".pickup_order(customerno, customerid, vendorno, fulfillmentid, pickupboyid, pickupdate, isdeleted)VALUES(%d, '2','%s', '%s', '%s','%s', 0);";
    }

    $SQL = sprintf($Query, $customerno, $insert->vendorno, $insert->scanno, $userid, $today);
    $this->dbspeed->query($SQL, __FILE__, __LINE__);
    $order = new VODelivery();
    $order->pid = $this->dbspeed->last_insert_id();
    $pids[] = $order;
   }



   foreach ($pids as $pid) {
    $sql = "Update " . DATABASE_NAME . ".pickup_order SET status=2, reasonid=$reasonid where oid=$pid->pid and pickupboyid=$userid";
    $res = $this->db->query($sql, __FILE__, __LINE__);
   }




   if (!empty($res)) {
    echo '{"status":"success"}';
    return '{"status":"success"}';
   } else {
    echo '{"status":"failure"}';
    return '{"status":"failure"}';
   }
  } else {
   echo '{"status":"failure"}';
   return '{"status":"failure"}';
  }
 }

 public function pushsignature($userkey, $pickupids, $signature) {
  //error_reporting(E_ALL ^ E_STRICT);
  //ini_set('display_errors', 'On');
  //echo $pickupids;
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
     $Query = "INSERT INTO " . DATABASE_NAME . ".pickup_order(customerno, customerid, vendorno, awbno, pickupboyid, pickupdate,isdeleted)VALUES(%d, '1','%s', '%s', '%s','%s', 0);";
    } else {
     $Query = "INSERT INTO " . DATABASE_NAME . ".pickup_order(customerno, customerid, vendorno, fulfillmentid, pickupboyid, pickupdate, isdeleted)VALUES(%d, '2','%s', '%s', '%s','%s', 0);";
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

    $sql = "Update " . DATABASE_NAME . ".pickup_order SET status=1 where oid=$pid->pid and pickupboyid='$userid'";
    $res = $this->db->query($sql, __FILE__, __LINE__);
   }


   echo '{"status":"success"}';
  } else {
   echo '{"status":"failure"}';
  }
 }

 public function pushphoto($userkey, $pickupids, $photo) {
  //error_reporting(E_ALL ^ E_STRICT);
  //ini_set('display_errors', 'On');
  //echo $pickupids;
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
     $Query = "INSERT INTO " . DATABASE_NAME . ".pickup_order(customerno, customerid, vendorno, awbno, pickupboyid, pickupdate,isdeleted)VALUES(%d, '1','%s', '%s', '%s','%s', 0);";
    } else {
     $Query = "INSERT INTO " . DATABASE_NAME . ".pickup_order(customerno, customerid, vendorno, fulfillmentid, pickupboyid, pickupdate, isdeleted)VALUES(%d, '2','%s', '%s', '%s','%s', 0);";
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


   echo '{"status":"success"}';
  } else {
   echo '{"status":"failure"}';
  }
 }

 /**
   public function pushsignature($devicekey, $orderid, $signature) {
   $sql = "select customerno from " . DATABASE_SPEED . ".route WHERE devicekey='$devicekey' limit 1";
   $record = $this->dbspeed->query($sql, __FILE__, __LINE__);
   if ($this->dbspeed->num_rows($record) > 0) {
   $row = $this->dbspeed->fetch_array($record);
   $customerno = $row['customerno'];

   $target_path = "../../../customer/" . $customerno . "/" . $devicekey . "/";
   if (!is_dir($target_path)) {
   mkdir($target_path, 0777, true) or die("Could not create directory");
   }

   $target_path_signature = $target_path . "signature/";

   if (!is_dir($target_path_signature)) {
   mkdir($target_path_signature, 0777, true) or die("Could not create directory");
   }

   // $signature='iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABGpJREFUeNqcVntIpFUUv/PNN1S+cs10dGQdfBer4msxZXAyTbPAVyxCDhiBQlIRlusfS8ksFESCk4oPrGQRFGfxRauJOgq6iMKmo7uOhoimZpkzPkbzMTPezhnmq2l2ppnpwuX7vnPP3N89v3PO7w6PUkpcGSaTScDn8w3gL+DxeAbixmBcdUSAo6MjP/I/hssgHR0dH+bm5qpHR0e/tbeu1+vFGCW+X11dCbh380C6nM3W1tZPY2Ji/hSLxbSxsfHSdn1zc/N1iURCZTIZXVhY+AhtFiDzulOA5ubm29HR0eeJiYkUnrStrW3f1ken0zEFBQVNwcHBFP16e3tV1ut2N4YkM/hsaGi4ExUVZQZITk7+Fwie9OTkJGR4ePg69zvwV4A/jYiIoH19fSOcn8MIFArFZ9YAKSkpT0UyMzMjDwwMfJSWlqaEXD2PNqVS+TEC4W80Go3MYSR1dXV3kaKkpCSzM/dEkJaWliPOD5LNyuVy88khZxSAhGivr6//MigoiJaXl1NgRcuDRCVtbGy8xLKsAcrUODs7K+3u7n7P09PzOYZhiHUfAT2kqqrq96Kiosytra0X4uPjp7BCOzs7cwHsAURFpqameOfn50HFxcW/rq2tEdhLx19cXHzc39//Dpzi7ZGRkVvLy8s3AQAb7qkyvby8JBkZGV6Dg4OVNTU1766vrz/Jy8t7EhcXp93b27s9Pj5O4GDrUGkPDQbDtaGhodSAgIAzBlCJr68v8fb2Nk8PD4//7BdI5Cc5OTlvxcbGLsPhPq+srLwLZl1ZWVmCv78/UalU99AvPT29y8/PjwBTCPwPJc4kBtcvLi6ekUqlD4AiKdBFxsbG7qyurt4MDw9fgJztbG9vk93dXUFkZOQsREHwm3FbIhjGhE845R9QeQ9PT0/Jzs5OuMX2m9FoJGdnZzfwG/JMDg8P3QeBaPj4hAZ8cX5+Ph0pDgkJ+RltWq1WhBsD5Y+5HGIqGOCY2EuyvYF+AoHgYnJy8s3S0tJJtVp9Iysri0BfPIIiiAPahCKRiAiFQgNUVvL+/j4egDCYaFBXrHlziWL43IYO1PhrqMIflpaWXs7Pz1fW1tYKLQKqxk0zMzPL8Xt6evrawcEBSUhIIGxTU9N16BMRnNAIG1/Nzc29BrXdjuC2fYLvQMF8dXX1rZKSEgkk/nu0QxG839XVpYJk/wIRdqDtPgwvLy+SnZ3tsOMLUBo4SeEmdjwo8k+c3/HxMQNNqAgLCzNBx/9osbPQ8d+gWFZUVFBIh9ahdoHYvYFSYQ1k0a6/QSDqD6DLKWiXnrP19PQEosygFK2srBQ6lXqgMts6ImsQOCEL+fMHpfgKJOZZtMFd8wX6A210YGDgO4uis65cWK9yEdnSZXWf8AsLCzVQWWY/UIJ7Tu8T29ne3p6BAKGhoRSi09i5GSXczQhlLbNddwkEJ5ToK6mpqfqJiQm5vXXobDFSyFFpvcZz9S8RDugnxsfHh4FSN7qjEm6BWHqFdRfkLwEGAFYS+tFIOBZTAAAAAElFTkSuQmCC';

   $image = base64_decode($signature);
   file_put_contents("../../../customer/" . $customerno . "/" . $devicekey . "/signature/" . $orderid . ".jpeg", $image);

   $sql = "Update " . DATABASE_NAME . ".master_shipment
   inner join " . DATABASE_SPEED . ".route on " . DATABASE_NAME . ".master_shipment.route  = " . DATABASE_SPEED . ".route.routeid
   SET " . DATABASE_NAME . ".master_shipment.shipping_status = 5
   WHERE " . DATABASE_NAME . ".master_shipment.orderid=$orderid AND " . DATABASE_SPEED . ".route.devicekey='$devicekey' ";
   $query = $this->db->query($sql, __FILE__, __LINE__);

   $timestamp = date('Y-m-d H:i:s');
   $sqlinsert = "insert into " . DATABASE_NAME . ".master_history(status, orderid, customerno, timestamp)values('5','$orderid', '$customerno', '$timestamp') ";
   $queryinsert = $this->db->query($sqlinsert, __FILE__, __LINE__);
   echo '{"status":"success"}';
   } else {
   echo '{"status":"failure"}';
   }
   }

  * */
 /*  * **** -----------------------  Technova API       --------------------------******* */

 public function checkregistration_technova($androidid) {
  $orders = array();
  $json = array();
  $sql = "SELECT * from " . DATABASE_SPEED . ".route WHERE androidid = '$androidid' AND isdeleted = 0 limit 1";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {
   $row = $this->dbspeed->fetch_array($result);
   $routeid = $row['routeid'];
   $sqlquery = "select * from " . DATABASE_NAME . ".master_orders
                        inner join " . DATABASE_NAME . ".master_shipment on " . DATABASE_NAME . ".master_orders.id = " . DATABASE_NAME . ".master_shipment.orderid
                        inner join " . DATABASE_NAME . ".master_shipping_address on " . DATABASE_NAME . ".master_orders.id = " . DATABASE_NAME . ".master_shipping_address.orderid
                        where " . DATABASE_NAME . ".master_shipment.shipping_status = 2 AND " . DATABASE_NAME . ".master_shipment.route=$routeid AND " . DATABASE_NAME . ".master_orders.status='Open'";
   $query = $this->db->query($sqlquery, __FILE__, __LINE__);
   if ($this->db->num_rows($query) > 0) {
    while ($data = $this->db->fetch_array($query)) {
     $order = new VODelivery();

     $order->id = $data['id'];
     $order->order_id = $data['order_id'];
     $order->full_name = $data['full_name'];
     $order->address = $data['address'];
     $order->city = $data['city'];
     $order->state = $data['state'];
     $order->country = $data['country'];
     $order->pincode = $data['pincode'];
     $order->phone = $data['phone'];
     $order->transporter_name = $data['transporter_name'];
     $order->lr_no = $data['lr_no'];
     $order->lr_date = $data['lr_date'];
     $order->tracking_number = $data['tracking_number'];

     $json[] = $order;
    }
    $orders['status'] = 'success';
    $orders['result'] = $json;
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = 'failure';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = 'failure';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function submitregistration_technova($devicekey, $androidid) {
  $registration = array();
  $today = date('Y-m-d H:i:s');
  $sql = "select devicekey from " . DATABASE_SPEED . ".route where devicekey='$devicekey' and isregister=0";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {
   $sqlquery = "update " . DATABASE_SPEED . ".route SET androidid = '$androidid', isregister=1, androidstamp='$today' WHERE devicekey='$devicekey'";
   $query = $this->dbspeed->query($sqlquery, __FILE__, __LINE__);


   $sqlget = "SELECT * from " . DATABASE_SPEED . ".route WHERE devicekey = '$devicekey' AND isdeleted = 0 limit 1";
   $resultget = $this->dbspeed->query($sqlget, __FILE__, __LINE__);
   if ($this->dbspeed->num_rows($resultget) > 0) {
    $row = $this->dbspeed->fetch_array($resultget);
    $routeid = $row['routeid'];
    $sqlquery = "select * from " . DATABASE_NAME . ".master_orders
                        inner join " . DATABASE_NAME . ".master_shipment on " . DATABASE_NAME . ".master_orders.id = " . DATABASE_NAME . ".master_shipment.orderid
                        inner join " . DATABASE_NAME . ".master_shipping_address on " . DATABASE_NAME . ".master_orders.id = " . DATABASE_NAME . ".master_shipping_address.orderid
                        where " . DATABASE_NAME . ".master_shipment.shipping_status = 2 AND " . DATABASE_NAME . ".master_shipment.route=$routeid AND " . DATABASE_NAME . ".master_orders.status='Open'";
    $query = $this->db->query($sqlquery, __FILE__, __LINE__);
    if ($this->db->num_rows($query) > 0) {
     while ($data = $this->db->fetch_array($query)) {
      $order = new VODelivery();
      $order->id = $data['id'];
      $order->order_id = $data['order_id'];
      $order->tracking_number = $data['tracking_number'];

      $order->ship_to_name = $data['full_name'];
      $order->ship_to_address = $data['address'];
      $order->ship_to_city = $data['city'];
      $order->ship_to_country = $data['country'];
      $order->ship_to_phone = $data['phone'];
      $order->ship_to_state = $data['state'];
      $order->ship_to_zip = $data['pincode'];
      $order->ship_to_code = $data['shipcode'];

      $order->created_on = $data['created_on'];
      $order->updated_on = $data['updated_on'];
      $order->shipping_status = $data['shipping_status'];
      $order->lr_no = $data['lr_no'];
      $order->lr_date = $data['lr_date'];
      $json[] = $order;
     }
     $registration['status'] = 'success';
     $registration['result'] = $json;
     echo json_encode($registration);
     return json_encode($registration);
    }
   }
  } else {
   $registration['status'] = 'failure';
  }
  echo json_encode($registration);
 }

 public function pullorders_technova($devicekey) {
  $orders = array();
  $json = array();
  $sql = "SELECT * from " . DATABASE_SPEED . ".route WHERE devicekey = '$devicekey' AND isdeleted = 0 limit 1";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {
   $row = $this->dbspeed->fetch_array($result);
   $routeid = $row['routeid'];
   $sqlquery = "select * from " . DATABASE_NAME . ".master_orders
                        inner join " . DATABASE_NAME . ".master_shipment on " . DATABASE_NAME . ".master_orders.id = " . DATABASE_NAME . ".master_shipment.orderid
                        inner join " . DATABASE_NAME . ".master_shipping_address on " . DATABASE_NAME . ".master_orders.id = " . DATABASE_NAME . ".master_shipping_address.orderid
                        where " . DATABASE_NAME . ".master_shipment.shipping_status = 2 AND " . DATABASE_NAME . ".master_shipment.route=$routeid AND " . DATABASE_NAME . ".master_orders.status='Open'";
   $query = $this->db->query($sqlquery, __FILE__, __LINE__);
   if ($this->db->num_rows($query) > 0) {
    while ($data = $this->db->fetch_array($query)) {
     $order = new VODelivery();
     $order->id = $data['id'];
     $order->id = $data['id'];
     $order->order_id = $data['order_id'];
     $order->tracking_number = $data['tracking_number'];

     $order->ship_to_name = $data['full_name'];
     $order->ship_to_address = $data['address'];
     $order->ship_to_city = $data['city'];
     $order->ship_to_country = $data['country'];
     $order->ship_to_phone = $data['phone'];
     $order->ship_to_state = $data['state'];
     $order->ship_to_zip = $data['pincode'];
     $order->ship_to_code = $data['shipcode'];

     $order->created_on = $data['created_on'];
     $order->updated_on = $data['updated_on'];
     $order->shipping_status = $data['shipping_status'];
     $order->lr_no = $data['lr_no'];
     $order->lr_date = $data['lr_date'];
     $json[] = $order;
    }
    $orders['status'] = 'success';
    $orders['result'] = $json;
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    $orders['status'] = 'failure';
    echo json_encode($orders);
    return json_encode($orders);
   }
  } else {
   $orders['status'] = 'failure';
   echo json_encode($orders);
   return json_encode($orders);
  }
 }

 public function pullorderdetail_technova($devicekey, $orderid) {
  $orders = array();
  $json = array();
  $sql = "SELECT * from " . DATABASE_SPEED . ".route WHERE devicekey = '$devicekey' AND isdeleted = 0 limit 1";
  $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($result) > 0) {
   $row = $this->dbspeed->fetch_array($result);
   $routeid = $row['routeid'];
   $sqlquery = "select * from " . DATABASE_NAME . ".master_orders
                        inner join " . DATABASE_NAME . ".master_shipment on " . DATABASE_NAME . ".master_orders.id = " . DATABASE_NAME . ".master_shipment.orderid
                        inner join " . DATABASE_NAME . ".master_shipping_address on " . DATABASE_NAME . ".master_orders.id = " . DATABASE_NAME . ".master_shipping_address.orderid
                        where " . DATABASE_NAME . ".master_shipment.shipping_status = 2 AND " . DATABASE_NAME . ".master_shipment.route=$routeid
                        AND " . DATABASE_NAME . ".master_orders.status='Open' AND " . DATABASE_NAME . ".master_orders.id=$orderid";
   $query = $this->db->query($sqlquery, __FILE__, __LINE__);
   if ($this->db->num_rows($query) > 0) {
    while ($data = $this->db->fetch_array($query)) {
     $order = new VODelivery();
     $order->id = $data['id'];
     $order->id = $data['id'];
     $order->order_id = $data['order_id'];
     $order->tracking_number = $data['tracking_number'];

     $order->ship_to_name = $data['full_name'];
     $order->ship_to_address = $data['address'];
     $order->ship_to_city = $data['city'];
     $order->ship_to_country = $data['country'];
     $order->ship_to_phone = $data['phone'];
     $order->ship_to_state = $data['state'];
     $order->ship_to_zip = $data['pincode'];
     $order->ship_to_code = $data['shipcode'];

     $order->created_on = $data['created_on'];
     $order->updated_on = $data['updated_on'];
     $order->shipping_status = $data['shipping_status'];
     $order->lr_no = $data['lr_no'];
     $order->lr_date = $data['lr_date'];
     $json[] = $order;
    }
    $orders['status'] = 'success';
    $orders['result'] = $json;
    echo json_encode($orders);
    return json_encode($orders);
   } else {
    echo '{"status":"failure"}';
    return '{"status":"failure"}';
   }
  } else {
   echo '{"status":"failure"}';
   return '{"status":"failure"}';
  }
 }

 public function pushsignature_technova($devicekey, $orderid, $signature, $photo) {
  $sql = "select customerno from " . DATABASE_SPEED . ".route WHERE devicekey='$devicekey' limit 1";
  $record = $this->dbspeed->query($sql, __FILE__, __LINE__);
  if ($this->dbspeed->num_rows($record) > 0) {
   $row = $this->dbspeed->fetch_array($record);
   $customerno = $row['customerno'];

   $target_path = "../../../customer/" . $customerno . "/" . $devicekey . "/";
   if (!is_dir($target_path)) {
    mkdir($target_path, 0777, true) or die("Could not create directory");
   }

   $target_path_signature = $target_path . "signature/";

   if (!is_dir($target_path_signature)) {
    mkdir($target_path_signature, 0777, true) or die("Could not create directory");
   }
   /**
     $signature='iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABGpJREFUeNqcVntIpFUUv/PNN1S+cs10dGQdfBer4msxZXAyTbPAVyxCDhiBQlIRlusfS8ksFESCk4oPrGQRFGfxRauJOgq6iMKmo7uOhoimZpkzPkbzMTPezhnmq2l2ppnpwuX7vnPP3N89v3PO7w6PUkpcGSaTScDn8w3gL+DxeAbixmBcdUSAo6MjP/I/hssgHR0dH+bm5qpHR0e/tbeu1+vFGCW+X11dCbh380C6nM3W1tZPY2Ji/hSLxbSxsfHSdn1zc/N1iURCZTIZXVhY+AhtFiDzulOA5ubm29HR0eeJiYkUnrStrW3f1ken0zEFBQVNwcHBFP16e3tV1ut2N4YkM/hsaGi4ExUVZQZITk7+Fwie9OTkJGR4ePg69zvwV4A/jYiIoH19fSOcn8MIFArFZ9YAKSkpT0UyMzMjDwwMfJSWlqaEXD2PNqVS+TEC4W80Go3MYSR1dXV3kaKkpCSzM/dEkJaWliPOD5LNyuVy88khZxSAhGivr6//MigoiJaXl1NgRcuDRCVtbGy8xLKsAcrUODs7K+3u7n7P09PzOYZhiHUfAT2kqqrq96Kiosytra0X4uPjp7BCOzs7cwHsAURFpqameOfn50HFxcW/rq2tEdhLx19cXHzc39//Dpzi7ZGRkVvLy8s3AQAb7qkyvby8JBkZGV6Dg4OVNTU1766vrz/Jy8t7EhcXp93b27s9Pj5O4GDrUGkPDQbDtaGhodSAgIAzBlCJr68v8fb2Nk8PD4//7BdI5Cc5OTlvxcbGLsPhPq+srLwLZl1ZWVmCv78/UalU99AvPT29y8/PjwBTCPwPJc4kBtcvLi6ekUqlD4AiKdBFxsbG7qyurt4MDw9fgJztbG9vk93dXUFkZOQsREHwm3FbIhjGhE845R9QeQ9PT0/Jzs5OuMX2m9FoJGdnZzfwG/JMDg8P3QeBaPj4hAZ8cX5+Ph0pDgkJ+RltWq1WhBsD5Y+5HGIqGOCY2EuyvYF+AoHgYnJy8s3S0tJJtVp9Iysri0BfPIIiiAPahCKRiAiFQgNUVvL+/j4egDCYaFBXrHlziWL43IYO1PhrqMIflpaWXs7Pz1fW1tYKLQKqxk0zMzPL8Xt6evrawcEBSUhIIGxTU9N16BMRnNAIG1/Nzc29BrXdjuC2fYLvQMF8dXX1rZKSEgkk/nu0QxG839XVpYJk/wIRdqDtPgwvLy+SnZ3tsOMLUBo4SeEmdjwo8k+c3/HxMQNNqAgLCzNBx/9osbPQ8d+gWFZUVFBIh9ahdoHYvYFSYQ1k0a6/QSDqD6DLKWiXnrP19PQEosygFK2srBQ6lXqgMts6ImsQOCEL+fMHpfgKJOZZtMFd8wX6A210YGDgO4uis65cWK9yEdnSZXWf8AsLCzVQWWY/UIJ7Tu8T29ne3p6BAKGhoRSi09i5GSXczQhlLbNddwkEJ5ToK6mpqfqJiQm5vXXobDFSyFFpvcZz9S8RDugnxsfHh4FSN7qjEm6BWHqFdRfkLwEGAFYS+tFIOBZTAAAAAElFTkSuQmCC';
    *
    */
   $image = base64_decode($signature);
   file_put_contents("../../../customer/" . $customerno . "/" . $devicekey . "/signature/" . $orderid . "_signature.jpeg", $image);

   $photo = base64_decode($photo);
   file_put_contents("../../../customer/" . $customerno . "/" . $devicekey . "/photo/" . $orderid . "_photo.jpeg", $photo);

   $ftp_server = "14.141.181.11";
   $ftp_user_name = "POD";
   $ftp_user_pass = "Lvoj7zp309!";
   $conn_id = ftp_connect($ftp_server);
   $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
   $file = "../../../customer/" . $customerno . "/" . $devicekey . "/signature/" . $orderid . "_signature.jpeg";
   $filephoto = "../../../customer/" . $customerno . "/" . $devicekey . "/photo/" . $orderid . "_photo.jpeg";
   $remote_file = "/Images/" . $orderid . "_signature.jpeg";
   $remote_filephoto = "/Images/" . $orderid . "_photo.jpeg";
   if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
    echo '{"Message":"successfully uploaded -- ' . $file . '"}';
   } else {
    echo '{"Message":"There was a problem while uploading -- ' . $file . '"}';
   }

   if (ftp_put($conn_id, $remote_filephoto, $filephoto, FTP_ASCII)) {
    echo '{"Message":"successfully uploaded -- ' . $filephoto . '"}';
   } else {
    echo '{"Message":"There was a problem while uploading -- ' . $filephoto . '"}';
   }
   ftp_close($conn_id);

   $sql = "Update " . DATABASE_NAME . ".master_shipment
                    inner join " . DATABASE_SPEED . ".route on " . DATABASE_NAME . ".master_shipment.route  = " . DATABASE_SPEED . ".route.routeid
                    SET " . DATABASE_NAME . ".master_shipment.shipping_status = 5
                    WHERE " . DATABASE_NAME . ".master_shipment.orderid=$orderid AND " . DATABASE_SPEED . ".route.devicekey='$devicekey' ";
   $query = $this->db->query($sql, __FILE__, __LINE__);

   $timestamp = date('Y-m-d H:i:s');
   $sqlinsert = "insert into " . DATABASE_NAME . ".master_history(status, orderid, customerno, timestamp)values('5','$orderid', '$customerno', '$timestamp') ";
   $queryinsert = $this->db->query($sqlinsert, __FILE__, __LINE__);

   $sqlq = "select order_id from master_orders ";
   $query = $this->db->query($sqlq, __FILE__, __LINE__);
   if ($this->db->num_rows($query) > 0) {
    while ($data = $this->db->fetch_array($query)) {
     $order_id = $data['order_id'];
    }
   }

   $server = '14.141.181.11';
   $username = 'poduser';
   $password = 'tnpod@2015';
   $database = 'tnpoddb';
   $connection = mssql_connect($server, $username, $password);
   mssql_select_db($database, $connection);
   $sql = "insert into master_orderstatus(orderid, order_id,status,reasonid)values('" . $orderid . "','" . $order_id . "', '1', '');";
   mssql_query($sql, $connection);

   echo '{"status":"success"}';
  } else {
   echo '{"status":"failure"}';
  }
 }

 public function pushcancellation_technova($devicekey, $orderid, $reasonid) {
  $sql = "Update " . DATABASE_NAME . ".master_shipment
                    inner join " . DATABASE_SPEED . ".route on " . DATABASE_NAME . ".master_shipment.route  = " . DATABASE_SPEED . ".route.routeid
                    SET " . DATABASE_NAME . ".master_shipment.shipping_status = 6, " . DATABASE_NAME . ".master_shipment.cancel_reason=$reasonid
                    WHERE " . DATABASE_NAME . ".master_shipment.orderid=$orderid AND " . DATABASE_SPEED . ".route.devicekey='$devicekey' ";
  $this->db->query($sql, __FILE__, __LINE__);



  $timestamp = date('Y-m-d H:i:s');

  $sqlcustomer = "select customerno from " . DATABASE_SPEED . ".route WHERE devicekey='$devicekey' limit 1";
  $recordcus = $this->dbspeed->query($sqlcustomer, __FILE__, __LINE__);
  $row = $this->dbspeed->fetch_array($recordcus);
  $customerno = $row['customerno'];

  $sqlinsert = "insert into " . DATABASE_NAME . ".master_history(status, orderid, customerno, timestamp)values('6','$orderid', '$customerno', '$timestamp') ";
  $this->db->query($sqlinsert, __FILE__, __LINE__);

  $sqlq = "select order_id from master_orders ";
  $query = $this->db->query($sqlq, __FILE__, __LINE__);
  if ($this->db->num_rows($query) > 0) {
   while ($data = $this->db->fetch_array($query)) {
    $order_id = $data['order_id'];
   }
  }

  $server = '14.141.181.11';
  $username = 'poduser';
  $password = 'tnpod@2015';
  $database = 'tnpoddb';
  $connection = mssql_connect($server, $username, $password);
  mssql_select_db($database, $connection);
  $sql = "insert into master_orderstatus(orderid, order_id,status,reasonid)values('" . $orderid . "','" . $order_id . "', '0', '" . $reasonid . "');";
  mssql_query($sql, $connection);


  echo '{"status":"success"}';
 }

}

?>