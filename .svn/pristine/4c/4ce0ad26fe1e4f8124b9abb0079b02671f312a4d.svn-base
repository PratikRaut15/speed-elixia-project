<?php

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

    public function pull_reasons($devicekey) {
        $reasons = array();
        $json = array();
        $sql = "select customerno from " . DATABASE_SPEED . ".route WHERE devicekey='$devicekey' limit 1";
        $record = $this->dbspeed->query($sql, __FILE__, __LINE__);
        if ($this->dbspeed->num_rows($record) > 0) {
            $row = $this->dbspeed->fetch_array($record);
            $customerno = $row['customerno'];
            $sqlquery = "select * from " . DATABASE_NAME . ".master_reason WHERE customerno IN(0, $customerno) AND isdeleted=0";
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

    public function checkregistration($androidid) {
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

    public function submitregistration($devicekey, $androidid) {
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
    
   
    public function pullorders($devicekey) {
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

    public function pullorderdetail($devicekey, $orderid) {
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

    public function pushcancellation($devicekey, $orderid, $reasonid) {
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

        echo '{"status":"success"}';
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
    
   **/
      
     
      
      
      
      /****** -----------------------  Technova API       --------------------------********/
      
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
                    $order->ref_number = $data['ref_number'];
                    
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
                    $order->ref_number = $data['ref_number'];
                    
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
                    $order->ref_number = $data['ref_number'];
                    
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
                    $order->ref_number = $data['ref_number'];
                    
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
            $ftp_user_name ="POD";
            $ftp_user_pass="Lvoj7zp309!";
            $conn_id = ftp_connect($ftp_server);
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
            $file = "../../../customer/" . $customerno . "/" . $devicekey . "/signature/" . $orderid . "_signature.jpeg";
            $filephoto = "../../../customer/" . $customerno . "/" . $devicekey . "/photo/" . $orderid . "_photo.jpeg";
            $remote_file = "/Images/".$orderid . "_signature.jpeg";
            $remote_filephoto = "/Images/".$orderid . "_photo.jpeg";
            if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
                echo '{"Message":"successfully uploaded -- '.$file.'"}';
                } else {
                echo '{"Message":"There was a problem while uploading -- '.$file.'"}';
                }
                
                if (ftp_put($conn_id, $remote_filephoto, $filephoto, FTP_ASCII)) {
                echo '{"Message":"successfully uploaded -- '.$filephoto.'"}';
                } else {
                echo '{"Message":"There was a problem while uploading -- '.$filephoto.'"}';
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
            
            echo '{"status":"success"}';
        } else {
            echo '{"status":"failure"}';
        }
    }
    
    
}

?>