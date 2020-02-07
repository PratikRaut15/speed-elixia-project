<?php

class VODevices {
    
}

class VODelivery {
    
}

class api {

    var $status;
    var $status_time;

    // construct
    function __construct() {
        $this->db = new database(DATABASE_HOST, DATABASE_PORT, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
        $this->dbspeed = new database(DATABASE_HOST, DATABASE_PORT, DATABASE_USER, DATABASE_PASSWORD, DATABASE_SPEED);
    }

    // catch if data is called
    function getStatus() {
        $statuss = array();
        $sql = "select * from master_status";
        $record = $this->db->query($sql, __FILE__, __LINE__);

        while ($row = $this->db->fetch_array($record)) {
            $status = new VODevices();
            $status->status = $row['statusname'];
            $statuss[] = $status;
        }
        return $statuss;
    }

    function addOrder($order) {
        //echo "<pre>".print_r($order)."</pre>";
        //echo "<pre>".print_r($order->item_details[0]->sub_total)."</pre>"; die();

        $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey=" . $order->userkey;
        $result = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($result);
        $order->customerno = $row['customerno'];
        $order->user = $row['userid'];

        $sql = "INSERT INTO " . DATABASE_NAME . ".master_orders(order_id, customerno, email, user_id, currency, financial_status, item_count, sub_total, taxes_total, discount_total, total, status,is_mobileorder,
                        created_on, updated_on ) VALUES('$order->order_id', $order->customerno, '$order->email', '$order->user_id', '$order->currency', '$order->financial_status',
                            '$order->item_count', '$order->sub_total', '$order->taxes_total', '$order->discount_total', '$order->total', '$order->status',1,'" . date('Y-m-d H:i:s', $order->created_on) . "',
                            '" . date('Y-m-d H:i:s', $order->updated_on) . "');";

        $this->db->query($sql, __FILE__, __LINE__);

        $lastid = $this->db->last_insert_id();

        $this->addBillingAddress($lastid, $order->order_id, $order->billing_address);
        $this->addShippingAddress($lastid, $order->order_id, $order->shipping_address);
        $this->addPaymentMethod($lastid, $order->order_id, $order->payment_method);
        $this->addPaymentDetails($lastid, $order->order_id, $order->payment_details);
        //$this->addPaymentShipment($lastid, $order->order_id, $order->shipments);
        $this->addItems($lastid, $order->order_id, $order->item_details);
        $this->addHistory($lastid, $order);
        $success = 'Success';
        return $success;
    }

    function addBillingAddress($id, $orderid, $billing) {
        $sql = "INSERT INTO " . DATABASE_NAME . ".master_billing_address(orderid, order_id, full_name, address, city, state, country, pincode, phone)
                    VALUES ($id, '$orderid', '$billing->full_name','$billing->address', '$billing->city', '$billing->state', '$billing->country', '$billing->zip', '$billing->phone')";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    function addShippingAddress($id, $orderid, $shipping) {
        $sql = "INSERT INTO " . DATABASE_NAME . ".master_shipping_address(orderid, order_id, full_name, address, city, state, country, pincode, phone)
                    VALUES ($id, '$orderid', '$shipping->full_name','$shipping->address', '$shipping->city', '$shipping->state', '$shipping->country', '$shipping->zip', '$shipping->phone')";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    function addPaymentMethod($id, $orderid, $paymentmethod) {
        $sql = "INSERT INTO " . DATABASE_NAME . ".master_paymentmethod(orderid, order_id, name, type)
                        VALUES ($id, '$orderid', '$paymentmethod->name', '$paymentmethod->type')";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    function addPaymentDetails($id, $orderid, $payment) {
        $sql = "INSERT INTO " . DATABASE_NAME . ".master_paymentdetails(orderid, order_id, name, status, type, verified)
                    VALUES ($id, '$orderid', '$payment->Name', '$payment->Status', '$payment->Type', '$payment->Verified')";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    function addPaymentShipment($id, $orderid, $shipment) {
        $sql = "INSERT INTO " . DATABASE_NAME . ".master_shipment(orderid, order_id, provider, tracking_number, shipping_status, service, price, return_cod_lebel, shipping_lebel)
                    VALUES ($id, '$orderid', '$shipment->provider', '$shipment->tracking_number', '$shipment->shipping_status', '$shipment->service', '$shipment->price',
                        '$shipment->retunr_cod_label', '$shipment->shipping_label')";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    function addHistory($id, $order) {
        $sql = "INSERT INTO " . DATABASE_NAME . ".master_history(orderid,status,customerno,userid)
                    VALUES ($id, '1', '$order->customerno', '$order->user')";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    function addItems($id, $orderid, $items) {
        foreach ($items as $item) {
            //echo $item->discounts;
            $sql = "INSERT INTO " . DATABASE_NAME . ".master_item(orderid, order_id, product_id, product_name, product_price, discount_total, quantity, sub_total, taxes_total, total)
                        VALUES($id, '$orderid', '" . $item->product->_id . "','" . $item->product->name . "','" . $item->product->price . "', '" . $item->discount_total . "', '" . $item->quantity . "', '$item->sub_total', '$item->taxes_total', '$item->total')";
            $this->db->query($sql, __FILE__, __LINE__);
            $lastitem = $this->db->last_insert_id();
            if (!empty($item->discounts)) {
                foreach ($item->discounts as $discount) {
                    $sql = "INSERT INTO " . DATABASE_NAME . ".master_discount(itemid, name, amount)VALUES($lastitem, '$discount->name', '$discount->saved_amount')";
                    $this->db->query($sql, __FILE__, __LINE__);
                }
            }
            if (!empty($item->taxes)) {
                foreach ($item->taxes as $tax) {
                    $sql = "INSERT INTO " . DATABASE_NAME . ".master_taxes(itemid, name, amount )VALUES($lastitem, '$tax->name', '$tax->amount')";
                    $this->db->query($sql, __FILE__, __LINE__);
                }
            }
        }
    }

    function getUser($order) {
        $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey='$order->userkey'";
        $result = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($result);
        return $row;
    }

    /*     * ********location_master api functions********* */

    function get_zone_id($customerno, $areaid) {
        $Query = "SELECT * FROM " . DATABASE_NAME . ".area_master WHERE customerno=$customerno and area_id=$areaid and isdeleted=0";
        $result = $this->db->query($Query, __FILE__, __LINE__);
        $row = $this->db->fetch_array($result);
        return $row;
    }

    function is_zone_exists($customerno, $zoneid) {
        $Query = "SELECT * FROM " . DATABASE_NAME . ".zone_master WHERE customerno=$customerno and zone_id=$zoneid and isdeleted=0";
        $result = $this->db->query($Query, __FILE__, __LINE__);
        $row = $this->db->fetch_array($result);
        return $row;
    }

    function is_area_exists($customerno, $zoneid, $areaid) {
        $Query = "SELECT * FROM " . DATABASE_NAME . ".area_master WHERE customerno=$customerno and zone_id=$zoneid and area_id=$areaid and isdeleted=0";
        $result = $this->db->query($Query, __FILE__, __LINE__);
        $row = $this->db->fetch_array($result);
        return $row;
    }

    /* to add zones in zone_master */

    function add_zone($d, $customerno) {
        $date = date('Y-m-d H:i:s');
        $Query = "insert into " . DATABASE_NAME . ".zone_master(customerno, zone_id, zonename, entry_date, isdeleted)
        values($customerno,{$d['zoneid']},'{$d['zonename']}','$date',0)";

        $this->db->query($Query, __FILE__, __LINE__);
        return $this->db->last_insert_id();
    }

    /* to add area in area_master */

    function add_area($d, $customerno) {
        $date = date('Y-m-d H:i:s');
        $Query = "insert into " . DATABASE_NAME . ".area_master(customerno,zone_id,area_id,areaname,lat,lng,entry_date,isdeleted)
        values($customerno,{$d['zoneid']},{$d['areaid']},'{$d['areaname']}','{$d['lat']}','{$d['lng']}','$date',0)";

        $this->db->query($Query, __FILE__, __LINE__);
        return $this->db->last_insert_id();
    }

    function delete_zones($customerno, $zoneid) {
        $date = date('Y-m-d H:i:s');
        $Query = "update " . DATABASE_NAME . ".zone_master set isdeleted=1, update_date='$date' where customerno=$customerno and zone_id=$zoneid";
        $this->db->query($Query, __FILE__, __LINE__);

        $Query2 = "update " . DATABASE_NAME . ".area_master set isdeleted=1, update_date='$date' where customerno=$customerno and zone_id=$zoneid";
        $this->db->query($Query2, __FILE__, __LINE__);

        return true;
    }

    function delete_areas($customerno, $zoneid, $areaid) {
        $date = date('Y-m-d H:i:s');
        $Query2 = "update " . DATABASE_NAME . ".area_master set isdeleted=1, update_date='$date' where customerno=$customerno and zone_id=$zoneid and area_id=$areaid";
        $this->db->query($Query2, __FILE__, __LINE__);

        return true;
    }

    public function edit_zone($customerno, $d) {
        $date = date('Y-m-d H:i:s');
        $Query = "update " . DATABASE_NAME . ".zone_master set zonename='{$d['zonename']}', update_date='$date' where customerno=$customerno and zone_id={$d['zoneid']} and isdeleted=0";
        $this->db->query($Query, __FILE__, __LINE__);
        return true;
    }

    public function edit_area($customerno, $d) {
        $date = date('Y-m-d H:i:s');
        $Query = "update " . DATABASE_NAME . ".area_master set areaname='{$d['areaname']}', lat='{$d['lat']}',lng='{$d['lng']}',update_date='$date' where customerno=$customerno and zone_id={$d['zoneid']} and area_id={$d['areaid']} and isdeleted=0";
        $this->db->query($Query, __FILE__, __LINE__);
        return true;
    }

    /* public function area_bracket_data(){
      $Query = "SELECT area_master_id, areaname FROM ".DATABASE_NAME.".`area_master` where areaname like '%(%)%'
      and area_master_id not in (SELECT area_master_id FROM ".DATABASE_NAME.".`area_master` where areaname like '%(%)%(%)%' ORDER BY `area_master_id` DESC)
      ORDER BY `area_master_id` asc";
      $result = $this->db->query($Query, __FILE__, __LINE__);
      $data = array();
      while($row = $this->db->fetch_array($result)){
      $areaname = $row['areaname'];
      $areaname = str_replace('(', ', ', $areaname);
      $areaname = str_replace(')', '', $areaname);
      $areaname = trim($areaname);
      $areaname = array_filter(explode(' ', $areaname));

      $areaname_final = '';
      foreach($areaname as $s){
      if($s==','){
      $areaname_final = trim($areaname_final);
      $areaname_final .= ", ";
      }
      else{
      $areaname_final .= "$s ";
      }
      }

      $data[$row['area_master_id']] = trim($areaname_final);
      }
      return $data;
      } */
    /* function update_area_test($area_master_id, $newval){
      $Query = "update ".DATABASE_NAME.".area_master set areaname='$newval' where area_master_id=$area_master_id";
      $this->db->query($Query, __FILE__, __LINE__);
      } */
    /*     * ********ends, location_master api functions********* */

    public function getfence($customerno) {
        $json = array();
        $sql = "SELECT * from " . DATABASE_SPEED . ".fence where customerno=$customerno and isdeleted=0";
        $result = $this->dbspeed->query($sql, __FILE__, __LINE__);
        if ($this->dbspeed->num_rows($result) > 0) {
            while ($data = $this->dbspeed->fetch_array($result)) {
                $order = new VODevices();
                //$order->id = $data['id'];
                $order->fenceid = $data['fenceid'];
                $order->fencename = $data['fencename'];
                $json[] = $order;
            }
            $orders['status'] = 'success';
            $orders['result'] = $json;
            //echo json_encode($orders);
            return json_encode($orders);
        }
    }

    public function get_geofence_from_fenceid($fenceid) {
        $geofence = Array();
        $Query = "SELECT fenceid,geolat,geolong FROM " . DATABASE_SPEED . ".geofence WHERE fenceid=%d AND isdeleted=0";
        $geofenceQuery = sprintf($Query, $fenceid);
        $_SESSION['query'] = $geofenceQuery;
        $result = $this->dbspeed->query($geofenceQuery, __FILE__, __LINE__);

        if ($this->dbspeed->num_rows($result) > 0) {
            while ($data = $this->dbspeed->fetch_array($result)) {
                $order = new VODevices();
                //$order->id = $data['id'];
                $order->fenceid = $data['fenceid'];
                $order->geolat = $data['geolat'];
                $order->geolong = $data['geolong'];
                $geofence[] = $order;
            }

            return $geofence;
        }
    }

    function addPODOrder($order) {
        //echo $order->userkey;
        $success = array();
        $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey=" . $order->userkey;
        $result = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($result);
        $order->customerno = $row['customerno'];
        $order->user = $row['userid'];

        $sql = "INSERT INTO " . DATABASE_NAME . ".master_orders(order_id, customerno, sales_type, production_details, quantity, status,is_mobileorder, created_on, updated_on ) VALUES('$order->ppd_no', $order->customerno,  '$order->sales_type', '" . mysql_real_escape_string($order->production_details) . "', '$order->quantity', 'open',1,'$order->update_date', '$order->update_date');";
        $this->db->query($sql, __FILE__, __LINE__);
        $lastid = $this->db->last_insert_id();

        $sql = "INSERT INTO " . DATABASE_NAME . ".master_shipping_address(orderid, order_id, full_name, address, phone, shipcode)VALUES($lastid, '$order->ppd_no', '$order->ship_to_name', '$order->ship_to_address', '$order->ship_to_phone', '$order->ship_to_code');";
        $this->db->query($sql, __FILE__, __LINE__);

        $sql = " INSERT INTO " . DATABASE_NAME . ".master_shipment(orderid, order_id, provider, tracking_number, shipping_status,transporter_name, route)VALUES($lastid, '$order->ppd_no', '$order->provider', '$order->ref_number', '2','$order->transporter_name', '$order->route_no');";
        $this->db->query($sql, __FILE__, __LINE__);

        $sql = "INSERT INTO " . DATABASE_NAME . ".master_history(orderid,status,customerno,userid, timestamp)
                    VALUES ($lastid, '1', '$order->customerno', '$order->user','$order->update_date')";
        $this->db->query($sql, __FILE__, __LINE__);

        $this->db->query($sql, __FILE__, __LINE__);

        $success['Status'] = 'Success';
        return $success;
    }

    function clean_up($text) {
        $unwanted = array("'"); // add any unwanted char to this array
        $text = trim($text);
        return str_ireplace($unwanted, '', $text);
    }

    function addBillOrder($order) {
        $result = array();
        $today = date("Y-m-d H:i:s");
        $order->billno = $this->clean_up($order->billno);
        $order->customerno = $this->clean_up($order->customerno);
        $order->slot = $this->clean_up($order->slot);
        $order->lat = $this->clean_up($order->lat);
        $order->long = $this->clean_up($order->long);
        $order->accuracy = $this->clean_up($order->accuracy);
        $order->street = $this->clean_up($order->street);
        $order->area = $this->clean_up($order->address);
        $order->landmark = isset($order->landmark) ? $this->clean_up($order->landmark) : "";
        $order->address = $this->clean_up($order->address);
        $order->pincode = $this->clean_up($order->pincode);
        $order->city = $this->clean_up($order->city);
        $order->delivery_date = $this->clean_up($order->delivery_date);
        $order->total_amount = $this->clean_up($order->total_amount);
        $order->redeem_limit = isset($order->redeem_limit) ? $this->clean_up($order->redeem_limit) : "";
        $order->operation_mode = $this->clean_up($order->operation_mode);
        $order->customername = ri($order->customername); // optional parameter
        $order->order_type = $this->clean_up($order->order_type);
        $order->areaid = isset($order->areaid) ? $order->areaid : 0;
        $order->building = "";
        $order->flat = "";
        if ($order->operation_mode == 0) {
            $master_orders = "master_orders_dummy";
            $master_shipping_address = "master_shipping_address_dummy";
            $master_shipment = "master_shipment_dummy";
        }
        else if ($order->operation_mode == 1) {
            $master_orders = "master_orders";
            $master_shipping_address = "master_shipping_address";
            $master_shipment = "master_shipment";
        }
        else {
            $result['Status'] = 'Failure';
            return $result;
            exit;
        }

        $Query = "SELECT order_id FROM " . DATABASE_NAME . "." . $master_orders . " WHERE order_id= '" . $order->billno . "' and iscanceled=0 ";
        $result1 = $this->db->query($Query, __FILE__, __LINE__);
        $row = $this->db->fetch_array($result1);

        if (empty($row)) {
            $sql = "INSERT INTO " . DATABASE_NAME . "." . $master_orders .
                    "(order_id, customerno, created_on, updated_on, delivery_date"
                    . ", slot, lat, longi, accuracy,fenceid,areaid, total_amount"
                    . ", reedeem_limit,is_mobileorder ) "
                    . " VALUES('$order->billno', $order->customerno,  '$today', '$today', '$order->delivery_date'"
                    . ", '$order->slot', '$order->lat', '$order->long', '$order->accuracy','$order->zoneid','$order->areaid','$order->total_amount'"
                    . ", '$order->redeem_limit','$order->order_type');";
            $this->db->query($sql, __FILE__, __LINE__);
            $lastid = $this->db->last_insert_id();

            $sql = "INSERT INTO " . DATABASE_NAME . "." . $master_shipping_address .
                    "(orderid, order_id, full_name, city,flat,building"
                    . ", landmark, area, address_main, street, pincode)"
                    . " VALUES($lastid, '$order->billno', '$order->customername', '$order->city', '$order->flat','$order->building'"
                    . " ,'$order->landmark','$order->street' ,'$order->address','$order->street', '$order->pincode')";
            $res_shipping = $this->db->query($sql, __FILE__, __LINE__);

            $sql = " INSERT INTO " . DATABASE_NAME . "." . $master_shipment .
                    "(orderid, order_id, tracking_number) "
                    . " VALUES ($lastid, '$order->billno', '$order->billno'); ";
            $res_shipment = $this->db->query($sql, __FILE__, __LINE__);


            if ($lastid != '' && isset($res_shipping) && isset($res_shipment)) {
                $result['Status'] = 'Success';
                $result['Accuracy'] = $order->accuracy;
                $result['Latitude'] = $order->lat;
                $result['Longitude'] = $order->long;
                //$result['Test Params'] = $order->lat." ".$order->long;
                if ($order->operation_mode == 0) {
                    $result['Operation Mode'] = "Testing";
                }
                else if ($order->operation_mode == 1) {
                    $result['Operation Mode'] = "Live Data";
                }
            }
            else {
                $result['Status'] = 'Failure';
            }
        }else{
            $result['Status'] = 'Failure';
            $result['Error'] = 'Bill No Already Exists';
        }
        //$success = 'Success';
        return $result;
    }

    function addCancelOrder($order) {
        $result = array();
        $today = date("Y-m-d H:i:s");
        $order->billno = $this->clean_up($order->billno);
        $order->customerno = $this->clean_up($order->customerno);
        $order->operation_mode = $this->clean_up($order->operation_mode);
        if ($order->operation_mode == 0) {
            $master_orders = "master_orders_dummy";
            $master_shipping_address = "master_shipping_address_dummy";
            $master_shipment = "master_shipment_dummy";
        }
        else if ($order->operation_mode == 1) {
            $master_orders = "master_orders";
            $master_shipping_address = "master_shipping_address";
            $master_shipment = "master_shipment";
        }
        else {
            $result['Status'] = 'Failure';
            return $result;
            exit;
        }

        //$sql_select = "SELECT order_id from ".DATABASE_NAME.".".$master_orders."";
        $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey=" . $order->userkey;
        $result2 = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($result2);
        $order->customerno = $row['customerno'];
        $order->user = $row['userid'];
        if (!empty($row)) {

            $Query = "SELECT order_id FROM " . DATABASE_NAME . "." . $master_orders . " WHERE order_id= '$order->billno' and customerno= '$order->customerno' ";
            $result1 = $this->db->query($Query, __FILE__, __LINE__);
            $row = $this->db->fetch_array($result1);

            if (!empty($row)) {
                $sql = "Update " . DATABASE_NAME . "." . $master_orders . " SET is_mobileorder=1, iscanceled=1 WHERE order_id = '$order->billno' and customerno= '$order->customerno' ";
                $this->db->query($sql, __FILE__, __LINE__);
                $sql = "Update " . DATABASE_NAME . "." . $master_shipment . " SET shipping_status=7 WHERE order_id = '$order->billno'";
                $this->db->query($sql, __FILE__, __LINE__);
                $result['Status'] = 'Success';
            }
            else {
                $result['Status'] = 'Success';
                //$result['Error'] = 'Bill No Not Found';
            }
        }
        else {
            $result['Status'] = 'Failure';
            $result['Error'] = 'Please Provide Valid Userkey';
        }
        //$success = 'Success';
        return $result;
    }

    function addEditOrder($order) {
        $result = array();
        $today = date("Y-m-d H:i:s");
        $order->billno = $this->clean_up($order->billno);
        $order->customerno = $this->clean_up($order->customerno);
        $order->slot = $this->clean_up($order->slot);
        $order->lat = $this->clean_up($order->lat);
        $order->long = $this->clean_up($order->long);
        $order->accuracy = $this->clean_up($order->accuracy);
        $order->fenceid = $this->clean_up($order->fenceid);
        $order->street = $this->clean_up($order->street);
        $order->flat = $this->clean_up($order->flat);
        $order->building = $this->clean_up($order->building);
        $order->landmark = $this->clean_up($order->landmark);
        $order->area = $this->clean_up($order->area);
        $order->address = $this->clean_up($order->address);
        $order->pincode = $this->clean_up($order->pincode);
        $order->city = $this->clean_up($order->city);
        $order->delivery_date = $this->clean_up(date('Y-m-d', strtotime($order->delivery_date)));
        $order->total_amount = $this->clean_up($order->total_amount);
        $order->redeem_limit = $this->clean_up($order->redeem_limit);
        $order->operation_mode = $this->clean_up($order->operation_mode);
        $order->customername = ri($order->customername); // optional parameter
        $order->delboyid = ri($order->delboyid); // delivery boy id

        if ($order->operation_mode == 0) {
            $master_orders = "master_orders_dummy";
            $master_shipping_address = "master_shipping_address_dummy";
            $master_shipment = "master_shipment_dummy";
        }
        else if ($order->operation_mode == 1) {
            $master_orders = "master_orders";
            $master_shipping_address = "master_shipping_address";
            $master_shipment = "master_shipment";
        }
        else {
            $result['Status'] = 'Failure';
            return $result;
            exit;
        }

        //$sql_select = "SELECT order_id from ".DATABASE_NAME.".".$master_orders."";

        $Query = "SELECT order_id FROM " . DATABASE_NAME . "." . $master_orders . " WHERE order_id= '$order->billno' ";
        $result1 = $this->db->query($Query, __FILE__, __LINE__);
        $row = $this->db->fetch_array($result1);

        if (!empty($row)) {
            $sql = "Update " . DATABASE_NAME . "." . $master_orders . " SET is_mobileorder=0, deliveryboyid = '$order->delboyid',delivery_date='$order->delivery_date', slot='$order->slot', lat='$order->lat', longi='$order->long', accuracy='$order->accuracy', fenceid='$order->fenceid', areaid=$order->areaid, total_amount='$order->total_amount', reedeem_limit='$order->redeem_limit' WHERE order_id='$order->billno'  ";
            $this->db->query($sql, __FILE__, __LINE__);

            $sql1 = "Update " . DATABASE_NAME . "." . $master_shipping_address . " SET full_name='$order->customername', city='$order->city', flat='$order->flat', building='$order->building', landmark='$order->landmark', area='$order->area', address_main='$order->address', street='$order->street', pincode='$order->pincode' WHERE order_id='$order->billno' ";
            $this->db->query($sql1, __FILE__, __LINE__);

            $result['Status'] = 'Success';
            $result['Accuracy'] = $order->accuracy;
            $result['Zone ID'] = $order->fenceid;
            $result['Latitude'] = $order->lat;
            $result['Longitude'] = $order->long;
            // $result['Test Params'] = $order->lat." ".$order->long;
            if ($order->operation_mode == 0) {
                $result['Operation Mode'] = "Testing";
            }
            else {
                $result['Operation Mode'] = "Live Data";
            }
        }
        else {
            $result['Status'] = 'Failure';
            $result['Error'] = 'Bill No Not Found';
        }
        //$success = 'Success';
        return $result;
    }

    public function getAccuracy() {
        $json = array();
        $sqlquery = "select * from " . DATABASE_NAME . ".testdata  where uniqueid between 17701 and 17800";
        $result = $this->db->query($sqlquery, __FILE__, __LINE__);
        while ($data = $this->db->fetch_array($result)) {
            $reason = new VODelivery();
            $reason->uniqueid = $data['uniqueid'];
            $reason->flat = $data['flat'];
            $reason->building = $data['building'];
            $reason->landmark = $data['landmark'];
            $reason->street = $data['street'];
            if ($data['subarea'] != '') {
                $reason->area = $data['subarea'] . ", " . $data['area'];
            }
            else {
                $reason->area = $data['area'];
            }

            $reason->city = "Mumbai";
            $reason->pincode = $data['pincode'];
            $json[] = $reason;
        }
        return $json;
    }

    function UpdateBillOrder($order){
        $result = array();
        $order->billno = $this->clean_up($order->billno);
        $order->customerno = $this->clean_up($order->customerno);
        $order->slot = $this->clean_up($order->slot);
        $order->lat = $this->clean_up($order->lat);
        $order->long = $this->clean_up($order->long);
        $order->accuracy = $this->clean_up($order->accuracy);
        $order->fenceid = $this->clean_up($order->fenceid);
        $order->street = $this->clean_up($order->street);
        $order->flat = $this->clean_up($order->flat);
        $order->building = $this->clean_up($order->building);
        $order->landmark = $this->clean_up($order->landmark);
        $order->area = $this->clean_up($order->area);
        $order->address = $this->clean_up($order->address);
        $order->pincode = $this->clean_up($order->pincode);
        $order->city = $this->clean_up($order->city);
        $order->delivery_date = $this->clean_up($order->delivery_date);
        $order->operation_mode = $this->clean_up($order->operation_mode);

        //$sql_select = "SELECT order_id from ".DATABASE_NAME.".".$master_orders."";

        $sql = "Update " . DATABASE_NAME . ".testdata SET accuracy='$order->accuracy',lat='$order->lat', longi='$order->long' WHERE uniqueid='$order->billno'  ";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    function addReason($order) {
        $result = array();
        $today = date("Y-m-d H:i:s");

        $order->userkey = $this->clean_up($order->userkey);
        $order->reason = $this->clean_up($order->reason);

        $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey=" . $order->userkey;
        $result2 = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($result2);
        $order->customerno = $row['customerno'];
        $order->user = $row['userid'];
        if (!empty($row)) {

            $sql = "INSERT INTO " . DATABASE_NAME . ".master_reason(customerno, userid, reason, timestamp) VALUES($order->customerno, $order->user,  '$order->reason', '$today');";
            $res = $this->db->query($sql, __FILE__, __LINE__);
            $lastid = $this->db->last_insert_id();
            if ($res) {
                $result['Status'] = 'Success';
                $result['ID'] = $lastid;
            }
            else {
                $result['Status'] = 'Failure';
                $result['Error'] = 'Record Not Inserted';
            }
        }
        else {
            $result['Status'] = 'Failure';
            $result['Error'] = 'Please Provide Valid Userkey';
        }

        return $result;
    }

    function editReason($order) {
        $result = array();
        $today = date("Y-m-d H:i:s");

        $order->userkey = $this->clean_up($order->userkey);
        $order->reasonid = $this->clean_up($order->reasonid);
        $order->reason = $this->clean_up($order->reason);

        $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey=" . $order->userkey;
        $result2 = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($result2);
        $order->customerno = $row['customerno'];
        $order->user = $row['userid'];
        if (!empty($row)) {
            $Query = "SELECT reasonid FROM " . DATABASE_NAME . ".master_reason WHERE reasonid= '$order->reasonid' ";
            $result1 = $this->db->query($Query, __FILE__, __LINE__);
            $row = $this->db->fetch_array($result1);

            if (!empty($row)) {
                $sql = "Update " . DATABASE_NAME . ".master_reason SET reason='$order->reason', timestamp='$today' where reasonid=$order->reasonid ";
                $res = $this->db->query($sql, __FILE__, __LINE__);
                if ($res) {
                    $result['Status'] = 'Success';
                }
                else {
                    $result['Status'] = 'Failure';
                    $result['Error'] = 'Record Not Updated';
                }
            }
            else {
                $result['Status'] = 'Failure';
                $result['Error'] = 'Reasonid Not Found';
            }
        }
        else {
            $result['Status'] = 'Failure';
            $result['Error'] = 'Please Provide Valid Userkey';
        }

        return $result;
    }

    function deleteReason($order) {
        $result = array();
        $today = date("Y-m-d H:i:s");

        $order->userkey = $this->clean_up($order->userkey);
        $order->reasonid = $this->clean_up($order->reasonid);
        $order->reason = $this->clean_up($order->reason);

        $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey=" . $order->userkey;
        $result2 = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($result2);
        $order->customerno = $row['customerno'];
        $order->user = $row['userid'];
        if (!empty($row)) {
            $Query = "SELECT reasonid FROM " . DATABASE_NAME . ".master_reason WHERE reasonid= '$order->reasonid' and isdeleted=0 ";
            $result1 = $this->db->query($Query, __FILE__, __LINE__);
            $row = $this->db->fetch_array($result1);

            if (!empty($row)) {
                $sql = "Update " . DATABASE_NAME . ".master_reason SET isdeleted='1', timestamp='$today' where reasonid=$order->reasonid ";
                $res = $this->db->query($sql, __FILE__, __LINE__);
                if ($res) {
                    $result['Status'] = 'Success';
                }
                else {
                    $result['Status'] = 'Failure';
                    $result['Error'] = 'Record Not Deleted';
                }
            }
            else {
                $result['Status'] = 'Failure';
                $result['Error'] = 'Reason Id Alredy Deleted';
            }
        }
        else {
            $result['Status'] = 'Failure';
            $result['Error'] = 'Please Provide Valid Userkey';
        }

        return $result;
    }

    function addPayment($order) {
        $resultArr = array();
        $today = date("Y-m-d H:i:s");
        $order->p_amount = 0;

        $order->userkey = $this->clean_up($order->userkey);
        $order->billno = $this->clean_up($order->billno);
        $order->paymenttype = $this->clean_up($order->paymenttype);
        $order->amount = $this->clean_up($order->amount);
        $order->chequeno = $this->clean_up($order->chequeno);
        $order->accountno = $this->clean_up($order->accountno);
        $order->branch = $this->clean_up($order->branch);
        $order->reason = $this->clean_up($order->reason);
        $order->paymentby = $this->clean_up($order->paymentby);

        $Query = "SELECT customerno, userid FROM " . DATABASE_SPEED . ".user WHERE userkey=" . $order->userkey;
        $result = $this->dbspeed->query($Query, __FILE__, __LINE__);
        $row = $this->dbspeed->fetch_array($result);
        $order->customerno = $row['customerno'];
        $order->user = $row['userid'];
        $Que = "SELECT * from " . DATABASE_NAME . ".master_orders where order_id=$order->billno and customerno=$order->customerno";
        $res = $this->dbspeed->query($Que, __FILE__, __LINE__);
        $data = $this->dbspeed->fetch_array($res);
        if ($data['total_amount'] == '') {
            $data['total_amount'] = 0;
        }
        if ($data['reedeem_limit'] == '') {
            $data['reedeem_limit'] = 0;
        }
        $order->total_amount = $data['total_amount'];
        $order->redeem_limit = $data['reedeem_limit'];
        $order->pending_amount = $order->total_amount - $order->amount;

        $QueryPayment = "INSERT INTO " . DATABASE_NAME . ".master_payment(orderid, type, amount, chequeno, accountno, branch, reason, paymentby, pending_amt, paymentdate )Values(%d,'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s') ";
        $SQLpayment = sprintf($QueryPayment, $order->billno, $order->paymenttype, $order->amount, $order->chequeno, $order->accountno, $order->branch, $order->reason, $order->paymentby, $order->pending_amount, $today);
        $resultpayment = $this->dbspeed->query($SQLpayment, __FILE__, __LINE__);
        if ($resultpayment) {
            $resultArr['Status'] = 'Success';
            $resultArr['Bill No'] = $order->billno;
        }
        else {
            $resultArr['Status'] = 'Failure';
        }

        return $resultArr;
    }

    function getZone_fence_details($customerno) {
        $resultarr = array();
        $Query = "select fence.fenceid  from " . DATABASE_SPEED . ".fence WHERE fence.isdeleted =0 AND fence.customerno =" . $customerno;
        $result = $this->dbspeed->query($Query, __FILE__, __LINE__);
        if ($this->dbspeed->num_rows($result) > 0) {

            while ($data = $this->db->fetch_array($result)) {
                $res = new stdClass();
                $res->fenceid = $data['fenceid'];
                $resultarr[] = $res;
            }
            return $resultarr;
        }
        return null;
    }

    function getZone_details($customerno, $fences, $lat, $long) {
        if (isset($fences)) {
            foreach ($fences as $fence) {
                $polygon = array();
                $pointLocation = new PointLocation($customerno);
                $points = array($lat . " " . $long);
                $geofence = $this->get_geofence_from_fenceid($fence->fenceid);
                if (isset($geofence)) {
                    foreach ($geofence as $thisgeofence) {
                        $polygon[] = $thisgeofence->geolat . " " . $thisgeofence->geolong;
                    }
                    foreach ($points as $point) {
                        if ($pointLocation->checkPointStatus($point, $polygon) == "inside") {
                            // Insert in
                            return $fence->fenceid;
                        }
                        else {
                            return 0;
                        }
                    }
                }
            }
        }
    }

    function existslatlong_areamaster($lat, $long, $customerno) {
        $Query = "select zone_id,area_id,areaname,lat,lng from " . DATABASE_NAME . ".area_master where lat='" . $lat . "' AND lng='" . $long . "' AND customerno =" . $customerno . " AND isdeleted=0 order by area_id desc limit 0,1";
        $result = $this->dbspeed->query($Query, __FILE__, __LINE__);
        if ($this->dbspeed->num_rows($result) > 0) {
            while ($data = $this->db->fetch_array($result)) {
                $res = new stdClass();
                $res->areaid = $data['area_id'];
                $res->zoneid = $data['zone_id'];
                $res->areaname = $data['areaname'];
                $res->lat = $data['lat'];
                $res->long = $data['lng'];
            }
            return $res;
        }
        return false;
    }

    function getzoneid($fenceid, $customerno) {
        $zone_id = 0;
        $Query = "select zone_id from " . DATABASE_NAME . ".zone_master where fence_id =" . $fenceid . " AND customerno =" . $customerno . " limit 1 ";
        $result = $this->dbspeed->query($Query, __FILE__, __LINE__);
        if ($this->dbspeed->num_rows($result) > 0) {
            while ($data = $this->db->fetch_array($result)) {
                $zone_id = $data['zone_id'];
            }
        }
        return $zone_id;
    }

    function insert_areamaster($areadata) {
        $maxareaid = 0;
        $areaid = 0;
        $customerno = $areadata['customerno'];
        $zoneid = $areadata['zone_id'];
        $areaname = $areadata['areaname'];
        $lat = $areadata['lat'];
        $long = $areadata['long'];

        $today = date("Y-m-d H:i:s");
        $Query = "select max(area_id) as maxareaid from " . DATABASE_NAME . ".area_master where customerno =" . $customerno;
        $result = $this->dbspeed->query($Query, __FILE__, __LINE__);
        if ($this->dbspeed->num_rows($result) > 0) {
            while ($data = $this->db->fetch_array($result)) {
                $maxareaid = $data['maxareaid'];
            }
        }
        $areaid = $maxareaid + 1;
        $QueryPayment = "INSERT INTO " . DATABASE_NAME . ".area_master(customerno,zone_id,area_id,areaname,lat,lng,entry_date) Values(%d,%d,%d,'%s','%s','%s','%s') ";
        $SQLpayment = sprintf($QueryPayment, $customerno, $zoneid, $areaid, $areaname, $lat, $long, $today);
        $result = $this->dbspeed->query($SQLpayment, __FILE__, __LINE__);
        return $areaid;
    }

}

?>
