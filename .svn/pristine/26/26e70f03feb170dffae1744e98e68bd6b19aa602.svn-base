<?php
if (!isset($RELATIVE_PATH_DOTS) || trim($RELATIVE_PATH_DOTS) == "") {
    $RELATIVE_PATH_DOTS = "../../";
}

include_once $RELATIVE_PATH_DOTS.'deliveryapi/class/config.inc.php';
include_once $RELATIVE_PATH_DOTS.'lib/system/Validator.php';
include_once $RELATIVE_PATH_DOTS.'lib/system/VersionedDeliveryManager.php';
include_once $RELATIVE_PATH_DOTS.'lib/system/Sanitise.php';
include_once $RELATIVE_PATH_DOTS.'lib/model/VODelivery.php';
include_once $RELATIVE_PATH_DOTS.'lib/bo/PaginationManager.php';
include_once $RELATIVE_PATH_DOTS.'lib/system/Date.php';

class DeliveryManager extends VersionedDeliveryManager {

    function __construct($customerno) {
        // Constructor.
        parent::__construct($customerno);
    }

    public function getstatus() {
        $statuss = Array();
        $Query = "select * from master_status WHERE customerno IN(0, %d) AND isdeleted = 0";
        $SQL = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $status = new VODelivery();
                $status->statusid = $row['statusid'];
                $status->status = $row['statusname'];
                $status->customerno = $row['customerno'];
                $status->timestamp = $row['timestamp'];

                $statuss[] = $status;
            }
            return $statuss;
        }
        return NULL;
    }

    public function getstatus_byid($statusid) {

        $Query = "select * from master_status WHERE customerno=%d AND statusid=%d AND isdeleted = 0";
        $SQL = sprintf($Query, $this->_Customerno, $statusid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $status = new VODelivery();
                $status->statusid = $row['statusid'];
                $status->status = $row['statusname'];
                $status->customerno = $row['customerno'];
                $status->timestamp = $row['timestamp'];
            }
            return $status;
        }
        return NULL;
    }

    public function getstatus_byname($statusid) {

        $Query = "select * from master_status WHERE statusid=%d AND isdeleted = 0";
        $SQL = sprintf($Query, $statusid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $status = new VODelivery();

                return $row['statusname'];
            }
        }
        return NULL;
    }

    public function getreason_byid($statusid) {

        $Query = "select * from master_reason WHERE customerno=%d AND reasonid=%d AND isdeleted = 0";
        $SQL = sprintf($Query, $this->_Customerno, $statusid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $status = new VODelivery();
                $status->statusid = $row['reasonid'];
                $status->status = $row['reason'];
                $status->customerno = $row['customerno'];
                $status->timestamp = $row['timestamp'];
            }
            return $status;
        }
        return NULL;
    }

    public function getreason() {
        $reasons = Array();
        $Query = "select * from master_reason WHERE customerno IN(0,%d) AND isdeleted = 0";
        $SQL = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $reason = new VODelivery();
                $reason->statusid = $row['reasonid'];
                $reason->status = $row['reason'];
                $reason->customerno = $row['customerno'];
                $reason->timestamp = $row['timestamp'];

                $reasons[] = $reason;
            }
            return $reasons;
        }
        return NULL;
    }

    public function addstatus($status) {
        $timestamp = date('Y-m-d H:i:s');

        $sql = "SELECT statusname FROM master_status WHERE customerno=%d AND statusname='%s'";
        $sqlquery = sprintf($sql, $this->_Customerno, $status);
        $this->_databaseManager->executeQuery($sqlquery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $response = 'notok';
            return $response;
        } else {

            $Query = "INSERT INTO master_status (statusname, customerno, userid,  timestamp, isdeleted)  VALUES ('%s',%d,%d,'%s',0)";
            $SQL = sprintf($Query, Sanitise::String($status), $this->_Customerno, $_SESSION['userid'], $timestamp);
            $this->_databaseManager->executeQuery($SQL);
            $response = 'ok';
            return $response;
        }
    }

    public function addhistory($status, $id) {
        $timestamp = date('Y-m-d H:i:s');
        $Query = "INSERT INTO master_history (status, orderid, timestamp)  VALUES ('%s',%d,'%s')";
        $SQL = sprintf($Query, Sanitise::String($status), $id, $timestamp);
        $this->_databaseManager->executeQuery($SQL);

        $Queryupdate = "UPDATE master_shipment SET shipping_status='%s' WHERE  orderid=%d";
        $SQLupdate = sprintf($Queryupdate, $status, $id);
        $this->_databaseManager->executeQuery($SQLupdate);

        if ($status == 5) {
            $Queryupdate = "UPDATE master_orders SET is_deliverd=1 WHERE id=%d";
            $SQLupdate = sprintf($Queryupdate, $id);
            $this->_databaseManager->executeQuery($SQLupdate);
        }
        if ($status == 8) {
            $Queryupdate = "UPDATE master_orders SET iscanceled=1 WHERE id=%d";
            $SQLupdate = sprintf($Queryupdate, $id);
            $this->_databaseManager->executeQuery($SQLupdate);
        }

        $response = 'ok';
        return $response;
    }

    public function addRoute($status, $statusid, $id, $ptime, $stime) {
        $timestamp = date('Y-m-d H:i:s');
        $eta = $ptime . ' ' . $stime . ':00';
        $eta = date('Y-m-d H:i:s', strtotime($eta));
        $Query = "INSERT INTO master_history (route, status, orderid, customerno, userid, timestamp)  VALUES (%d,%d,%d,%d,%d,'%s')";
        $SQL = sprintf($Query, Sanitise::String($status), $statusid, $id, $this->_Customerno, $_SESSION['userid'], $timestamp);
        $this->_databaseManager->executeQuery($SQL);

        $Queryupdate = "UPDATE master_shipment SET route=%d, shipping_status=%d, eta='%s' WHERE orderid='%s'";
        $SQLupdate = sprintf($Queryupdate, $status, $statusid, $eta, $id);
        $this->_databaseManager->executeQuery($SQLupdate);
        $response = 'ok';
        return $response;
    }

    public function editstatus($status, $statusid) {
        $Query = "UPDATE master_status SET statusname = '%s' WHERE customerno=%d AND statusid = %d";
        $SQL = sprintf($Query, Sanitise::String($status), $this->_Customerno, $statusid);
        $this->_databaseManager->executequery($SQL);
        $response = 'ok';
        return $response;
    }

    public function gethistory($id) {
        $statuss = Array();
        $Query = "select master_history.timestamp, master_status.statusname,master_history.route  from master_history
            inner join master_status on master_history.status=master_status.statusid
            WHERE orderid = %d order by id DESC";
        $SQL = sprintf($Query, $id);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $status = new VODelivery();
                $status->status = $row['statusname'];
                $status->route = $row['route'];
                $status->timestamp = $row['timestamp'];
                $statuss[] = $status;
            }
            return $statuss;
        }
        return NULL;
    }

    public function delstatus($statusid) {
        $Query = "UPDATE master_status SET isdeleted=1 WHERE customerno=%d AND statusid = %d";
        $SQL = sprintf($Query, $this->_Customerno, $statusid);
        $this->_databaseManager->executeQuery($SQL);
        //$response = 'del';
        //return $response;
    }

    public function addreason($reason) {
        $timestamp = date('Y-m-d H:i:s');
        $sql = "SELECT reason FROM master_reason WHERE customerno = %d AND reason LIKE '%s'";
        $sqlquery = sprintf($sql, $this->_Customerno, $reason);
        $this->_databaseManager->executeQuery($sqlquery);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $response = 'notok';
            return $response;
        } else {

            $Query = "INSERT INTO master_reason (reason, customerno, userid, timestamp, isdeleted)  VALUES ('%s',%d,%d,'%s',0)";
            $SQL = sprintf($Query, Sanitise::String($reason), $this->_Customerno, $_SESSION['userid'], $timestamp);
            $this->_databaseManager->executeQuery($SQL);
            $response = 'ok';
            return $response;
        }
    }

    public function editreason($reason, $reasonid) {
        $Query = "UPDATE master_reason SET reason = '%s' WHERE customerno=%d AND reasonid = %d";
        $SQL = sprintf($Query, Sanitise::String($reason), $this->_Customerno, $reasonid);
        $this->_databaseManager->executequery($SQL);
        $response = 'ok';
        return $response;
    }

    public function delreason($reasonid) {
        $Query = "Update master_reason SET isdeleted = 1 WHERE customerno=%d AND reasonid = %d";
        $SQL = sprintf($Query, $this->_Customerno, $reasonid);
        $this->_databaseManager->executeQuery($SQL);
        $response = 'del';
        return $response;
    }

    public function getorders() {
        $orders = Array();
        $Query = "SELECT *, master_shipment.shipping_status as shipmentstatus FROM master_orders
                  left outer join master_billing_address on master_orders.id = master_billing_address.orderid
                  left outer join master_shipment on master_orders.id = master_shipment.orderid
                  left outer join master_status on master_shipment.shipping_status = master_status.statusid
                  WHERE master_orders.customerno = %d AND (master_orders.status = 'open' OR master_orders.status = '') AND master_orders.order_id <> '' order by id DESC";
        $SQL = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $order = new VODelivery();
                $order->id = $row['id'];
                $order->order_id = $row['order_id'];
                $order->customerno = $row['customerno'];
                $order->email = $row['email'];
                $order->user_id = $row['user_id'];
                $order->currency = $row['currency'];
                $order->financial_status = $row['financial_status'];
                $order->item_count = $row['item_count'];
                $order->sub_total = $row['sub_count'];
                $order->taxes_total = $row['taxes_total'];
                $order->discount_total = $row['discount_total'];
                $order->total = $row['total'];
                $order->status = $row['shipmentstatus'];
                $order->created_on = $row['created_on'];
                $order->updated_on = $row['updated_on'];
                $order->fullname = $row['full_name'];
                $order->trackingno = $row['tracking_number'];
                $order->shipmentstatus = $row['statusname'];
                $order->transporter_name = $row['transporter_name'];
                $order->lr_no = $row['lr_no'];

                $orders[] = $order;
            }
        }
        return $orders;
    }

    public function getordersbyid($orderid) {
        //$orders = Array();
        $Query = "SELECT *, master_shipment.shipping_status as shipmentstatus FROM master_orders
                  inner join master_billing_address on master_orders.id = master_billing_address.orderid
                  inner join master_shipment on master_orders.id = master_shipment.orderid
                  WHERE customerno = %d AND master_orders.id=%d";
        $SQL = sprintf($Query, $this->_Customerno, $orderid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $order = new VODelivery();
                $order->id = $row['id'];
                $order->order_id = $row['order_id'];
                $order->customerno = $row['customerno'];
                $order->email = $row['email'];
                $order->user_id = $row['user_id'];
                $order->currency = $row['currency'];
                $order->sales_type = $row['sales_type'];
                $order->ref_number = $row['ref_number'];
                $order->financial_status = $row['financial_status'];
                $order->item_count = $row['item_count'];
                $order->sub_total = $row['sub_total'];
                $order->taxes_total = $row['taxes_total'];
                $order->discount_total = $row['discount_total'];
                $order->total = $row['total'];
                $order->status = $row['shipmentstatus'];
                $order->route = $row['route'];
                $order->created_on = $row['created_on'];
                $order->updated_on = $row['updated_on'];
                $order->fullname = $row['full_name'];
                $order->address = $row['address'];
                $order->city = $row['city'];
                $order->state = $row['state'];
                $order->country = $row['country'];
                $order->pincode = $row['pincode'];
                $order->phone = $row['phone'];
                $order->trackingno = $row['tracking_number'];
                $order->provider = $row['provider'];
                $order->shipprice = $row['price'];
                $order->transporter_name = $row['transporter_name'];
                $order->lr_no = $row['lr_no'];
                $order->lr_date = $row['lr_date'];
                $order->eta = $row['eta'];
            }
        }
        return $order;
    }

    public function getBillingAddress($orderid) {
        $billing = array();
        $Query = "SELECT * FROM master_billing_address WHERE orderid = %d";
        $SQL = sprintf($Query, $orderid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $bill = new VODelivery();
                $bill->fullname = $row['full_name'];
                $bill->address = $row['address'];
                $bill->city = $row['city'];
                $bill->state = $row['state'];
                $bill->country = $row['country'];
                $bill->pincode = $row['pincode'];
                $bill->phone = $row['phone'];
            }
        }
        return $bill;
    }

    public function getShippingAddress($orderid) {
        $billing = array();
        $Query = "SELECT * FROM master_shipping_address WHERE orderid = %d";
        $SQL = sprintf($Query, $orderid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $bill = new VODelivery();
                $bill->fullname = $row['full_name'];
                $bill->address = $row['address'];
                $bill->city = $row['city'];
                $bill->state = $row['state'];
                $bill->country = $row['country'];
                $bill->pincode = $row['pincode'];
                $bill->phone = $row['phone'];
                //$billing[] = $bill;
            }
        }
        return $bill;
    }

    public function getordersitembyid($orderid) {
        $orders = Array();
        $Query = "SELECT * FROM master_orders
                  inner join master_item on master_orders.id = master_item.orderid
                  WHERE customerno = %d AND master_orders.id=%d";
        $SQL = sprintf($Query, $this->_Customerno, $orderid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $order = new VODelivery();
                $order->itemid = $row['itemid'];
                $order->order_id = $row['order_id'];
                $order->customerno = $row['customerno'];
                $order->product_id = $row['product_id'];
                $order->discount_total = $row['discount_total'];
                $order->quantity = $row['quantity'];
                $order->subtotal = $row['sub_total'];
                $order->taxes = $row['taxes_total'];
                $order->total = $row['total'];
                $order->product_name = $row['product_name'];
                $order->price = $row['product_price'];
                $orders[] = $order;
            }
        }
        return $orders;
    }

    public function getorder_details($orderid) {
        $orders = Array();
        $Query = "SELECT lat, longi, accuracy FROM master_orders WHERE customerno = %d AND master_orders.id=%d";
        $SQL = sprintf($Query, $this->_Customerno, $orderid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $order = new VODelivery();
                $order->lat = $row['lat'];
                $order->longi = $row['longi'];
                $order->accuracy = $row['accuracy'];
                return $order;
            }
        }
        return $orders;
    }

    public function getarea($areaid) {
        $areas = array();
        $Query = " select * from area_master where customerno=%d and area_master_id=%d";
        $SQL = sprintf($Query, $this->_Customerno, $areaid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $order = new VODelivery();
                $order->area_master_id = $row['area_master_id'];
                $order->area_id = $row['area_id'];
                $order->lat = $row['lat'];
                $order->longi = $row['lng'];
                $order->zone_id = $row['zone_id'];

                return $order;
            }
        }
        return $areas;
    }

    public function getOrderDetails($orderid) {
        $orders = Array();
        $Query = "select *, e.shipping_status as shipmentstatus,c.full_name,master_orders.delivery_lat,master_orders.delivery_long from master_orders
                left join zone_master as b on master_orders.fenceid=b.zone_id and master_orders.customerno=b.customerno
                left join area_master as d on master_orders.fenceid=d.zone_id and master_orders.areaid=d.area_id and master_orders.customerno=d.customerno
                left join master_shipping_address as c on master_orders.id=c.orderid
                left join master_shipment as e on master_orders.id=e.orderid
                where  master_orders.customerno=%d and master_orders.id='%s' ";
        $SQL = sprintf($Query, $this->_Customerno, $orderid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $order = new VODelivery();
                $order->id = $row['id'];
                $order->orderid = $row['order_id'];
                $order->deliveryboyid = $row['deliveryboyid'];
                $order->area = $row['areaname'];
                $order->areaid = $row['areaid'];
                $order->address = $row['address_main'];
                $order->flatno = $row['flat'];
                $order->building = $row['building'];
                $order->street = $row['street'];
                $order->landmark = $row['landmark'];
                $order->city = $row['city'];
                $order->pincode = $row['pincode'];
                $order->slot = $row['slot'];
                $order->deliverydate = $row['delivery_date'];
                $order->status = $row['shipmentstatus'];
                $order->delivery_lat = $row['delivery_lat'];
                $order->delivery_long = $row['delivery_long'];

                if ($row['total_amount'] == '') {
                    $row['total_amount'] = 0;
                }
                $order->total_amount = $row['total_amount'];
                if ($row['reedeem_limit'] == '') {
                    $row['reedeem_limit'] = 0;
                }
                $order->reedeem_limit = $row['reedeem_limit'];
                $order->cust_name = $row['full_name'];
                $order->orderdate = date('Y-m-d', strtotime($row['created_on']));
                return $order;
            }
        }
        return $orders;
    }

    public function getOrderDetails_payment($orderid) {
        $orders = Array();
        $Query = "select *, e.shipping_status as shipmentstatus from master_orders
                left join zone_master as b on master_orders.fenceid=b.zone_id and master_orders.customerno=b.customerno
                left join area_master as d on master_orders.fenceid=d.zone_id and master_orders.areaid=d.area_id and master_orders.customerno=d.customerno
                left join master_shipping_address as c on master_orders.id=c.orderid
                left join master_shipment as e on master_orders.id=e.orderid
                where  master_orders.customerno=%d and master_orders.id=%d ";
        $SQL = sprintf($Query, $this->_Customerno, $orderid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $order = new VODelivery();
                $order->id = $row['id'];
                $order->orderid = $row['order_id'];
                $order->area = $row['areaname'];
                $order->areaid = $row['area_id'];
                $order->address = $row['address_main'];
                $order->flatno = $row['flat'];
                $order->building = $row['building'];
                $order->street = $row['street'];
                $order->landmark = $row['landmark'];
                $order->city = $row['city'];
                $order->pincode = $row['pincode'];
                $order->slot = $row['slot'];
                $order->deliverydate = $row['delivery_date'];
                $order->status = $row['shipmentstatus'];
                if ($row['total_amount'] == '') {
                    $row['total_amount'] = 0;
                }
                $order->total_amount = $row['total_amount'];
                if ($row['reedeem_limit'] == '') {
                    $row['reedeem_limit'] = 0;
                }
                $order->reedeem_limit = $row['reedeem_limit'];
                $order->orderdate = date('Y-m-d', strtotime($row['created_on']));
                return $order;
            }
        }
        return $orders;
    }

    public function getPaidAmt($orderid) {
        $paidAmt = 0;
        $orders = Array();
        $Query = "select sum(amount) as paidamt from master_payment where orderid=%d ";
        $SQL = sprintf($Query, $orderid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            $row = $this->_databaseManager->get_nextrow();
            if ($row['paidamt'] != null) {
                $paidAmt = $row['paidamt'];
            }
        }
        return $paidAmt;
    }

    public function getRedeemUsed($orderid) {
        $orders = Array();
        $Query = "select sum(amount) as paidamt from master_payment where orderid=%d and type=2";
        $SQL = sprintf($Query, $orderid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {

                if (!empty($row['paidamt'])) {
                    return $row['paidamt'];
                } else {
                    return 0;
                }
            }
        } else {

            return 0;
        }
        return 0;
    }

    public function getOrderPayments($orderid) {
        $orders = Array();
        $Query = "select * from master_payment where orderid=%d order by pid DESC";
        $SQL = sprintf($Query, $orderid);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $order = new VODelivery();
                $order->pid = $row['pid'];
                $order->orderid = $row['orderid'];
                $order->type = $row['type'];
                $order->amount = $row['amount'];
                $order->chequeno = $row['chequeno'];
                $order->accountno = $row['accountno'];
                $order->branch = $row['branch'];
                $order->reason = $row['reason'];
                $order->paymentby = $row['paymentby'];
                $order->pending_amt = $row['pending_amt'];
                $order->paymentdate = $row['paymentdate'];

                $orders[] = $order;
            }
        }
        return $orders;
    }

    public function zoneSlotBasedOrders($vehid, $slotid, $date, $zoneid) {
        $orders = Array();
        if (is_array($slotid)) {
            $slotid = implode(",", $slotid);
        }

        if (is_array($zoneid)) {
            $zoneid = implode(",", $zoneid);
        }

//    $Query = "SELECT b.order_id, b.lat, b.longi, b.accuracy,b.slot,b.id,c.address_main as address  FROM `order_route_sequence` as a
        //left join master_orders as b on a.order_id=b.id AND b.iscanceled=0
        //left join area_master as am on b.areaid = am.area_id and b.customerno = am.customerno and  am.customerno= $this->_Customerno
        //left join master_shipping_address as c on a.order_id=c.orderid
        //where a.vehicle_id =$vehid and date(delivery_date) = '$date' and b.slot=$slotid and am.zone_id = $zoneid order by a.sequence
        //";
        $Query = "SELECT b.order_id, b.lat, b.longi, b.accuracy,b.slot,b.id,c.address_main as address  FROM `order_route_sequence` as a
left join master_orders as b on a.order_id=b.id AND b.iscanceled=0
left join area_master as am on b.areaid = am.area_id and b.customerno = am.customerno and  am.customerno= $this->_Customerno
left join master_shipping_address as c on a.order_id=c.orderid
where a.vehicle_id =$vehid and date(delivery_date) = '$date' and b.slot IN (" . $slotid . ") and am.zone_id IN(" . $zoneid . ") order by a.sequence ";

        $SQL = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {

                $orders[] = array(
                    "lat" => $row['lat'],
                    "longi" => $row['longi'],
                    "accuracy" => $row['accuracy'],
                    "slot" => $row['slot'],
                    "order_id" => $row['id'],
                    "pop_display" => "Order-Id: {$row['order_id']}<br>Address: {$row['address']}",
                );
            }
        }
        return $orders;
    }

    public function zoneSlotBasedOrders_pickup($vehid, $slotid, $date) {
        $orders = Array();
        $Query = "SELECT b.orderid, c.lat, c.lng, b.oid, c.address FROM " . DATABASE_PICKUP . ".order_route_sequence as a
left join " . DATABASE_PICKUP . ".pickup_order as b on a.orderid=b.oid
left join " . DATABASE_PICKUP . ".pickup_vendor as c on b.vendorno=c.vendorid
where a.pickupid =$vehid order by a.sequence
";
        $SQL = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $orders[] = array(
                    'lat' => $row['lat'],
                    'longi' => $row['lng'],
                    'accuracy' => 1,
                    'slot' => 1,
                    'order_id' => $row['orderid'],
                    'pop_display' => "Order-Id: {$row['orderid']}<br/>Address: {$row['address']}",
                );
            }
        }
        return $orders;
    }

    function last_slot_ll($vehid, $slot, $odate, $zoneid) {
        if (is_array($slot)) {
            $slot = implode(",", $slot);
        }

        if (is_array($zoneid)) {
            $zoneid = implode(",", $zoneid);
        }

        $ll = null;
        $Query = "SELECT concat(b.lat,',', b.longi) as lastll  FROM `order_route_sequence` as a ";
        $Query .= " inner join master_orders as b on a.order_id=b.id";
        $Query .= " inner join area_master as am on b.areaid = am.area_id and b.customerno = am.customerno and am.customerno=" . $this->_Customerno;
        $Query .= " where a.vehicle_id =" . $vehid . " and date(delivery_date) = '" . $odate . "' and b.slot IN ( " . $slot . ") and am.zone_id IN( " . $zoneid . ")  order by a.sequence desc limit 1";
        $SQL = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $ll = $row['lastll'];
            }
        }
        return $ll;
    }

    public function updateOrders($data) {
        $datetime = date('Y-m-d h:i:s');
        $Query = "update master_orders set lat='{$data['lat']}', longi='{$data['longi']}', accuracy={$data['accu']}, updated_on='{$datetime}'
        WHERE customerno = %d AND id=%d";
        $SQL = sprintf($Query, $this->_Customerno, $data['orderid']);
        $this->_databaseManager->executeQuery($SQL);
    }

    public function updateAreas($data) {
        $datetime = date('Y-m-d h:i:s');
        $Query = "update area_master set lat='{$data['lat']}', lng='{$data['longi']}', update_date='{$datetime}', is_approved=1
         WHERE customerno = %d AND area_master_id=%d";
        $SQL = sprintf($Query, $this->_Customerno, $data['areaid']);
        $this->_databaseManager->executeQuery($SQL);
        return 'ok';
    }

    public function getZoneSlotOrders_count($dateI) {
        $date = date('Y-m-d', strtotime($dateI));
        $groupQ = '';
        if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
            $grpid = (int) $_SESSION['groupid'];
            $groupQ = " AND z.groupid = $grpid ";
        }
        $slotcondition = '';
        if ($_SESSION['customerno'] == 151) {
            $slotcondition = " AND a.slot < 100 ";
        }

        $orders = Array();
        $total = 0;
        $zoneZero = 0;
        $Query = "SELECT fenceid, slot, id FROM master_orders as a
            left join zone_master as z on z.zone_id=a.fenceid and z.customerno=a.customerno
            WHERE a.iscanceled=0 AND  a.customerno = %d and date(delivery_date) = '$date' $groupQ $slotcondition";
        $SQL = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $zone = $row['fenceid'];
                $slot = $row['slot'];
                if ($zone == 0) {
                    $zoneZero++;
                    continue;
                }
                if (!isset($orders[$zone][$slot])) {
                    $orders[$zone][$slot] = 1;
                } else {
                    $orders[$zone][$slot] = $orders[$zone][$slot] + 1;
                }
                $total++;
            }
        }
        return array(
            'orders' => $orders,
            'zoneZero' => $zoneZero,
            'total' => $total,
        );
    }

    public function getZoneSlotOrders_count_pickup($dateI) {
        $date = date('Y-m-d', strtotime($dateI));
        $groupQ = '';
        if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
            $grpid = (int) $_SESSION['groupid'];
            $groupQ = " AND z.groupid = $grpid ";
        }
        $orders = Array();
        $total = 0;
        $zoneZero = 0;
        $Query = "SELECT a.oid FROM " . DATABASE_PICKUP . ".pickup_order as a
            left join " . DATABASE_PICKUP . ".zone_master as z on z.customerno=a.customerno
            left join " . DATABASE_PICKUP . ".pickup_vendor as b on a.vendorno=b.vendorid
            WHERE a.customerno = %d and a.pickupboyid != '' and a.pickupdate='$dateI' and a.orderid!='' and b.lat != '' and b.lng !='' $groupQ";
        //WHERE a.customerno = %d and date(delivery_date) = '$date' $groupQ";
        $SQL = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $zone = 1;
                $slot = 1;
                if ($zone == 0) {
                    $zoneZero++;
                    continue;
                }
                if (!isset($orders[$zone][$slot])) {
                    $orders[$zone][$slot] = 1;
                } else {
                    $orders[$zone][$slot] = $orders[$zone][$slot] + 1;
                }
                $total++;
            }
        }
        return array(
            'orders' => $orders,
            'zoneZero' => $zoneZero,
            'total' => $total,
        );
    }

    public function getMappedOrders($dateI) {
        $date = date('Y-m-d', strtotime($dateI));
        $orders = Array();
        $Query = "SELECT a.vehicle_id, c.vehicleno, b.order_id, b.areaid,a.sequence, a.update_time, b.slot, b.fenceid
        FROM order_route_sequence as a
        left join master_orders as b on a.order_id=b.id
        left join " . SPEEDDB . ".vehicle as c on a.vehicle_id=c.vehicleid where b.iscanceled=0 AND b.customerno=$this->_Customerno and  b.delivery_date='$date'";
        $SQL = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $zone = $row['fenceid'];
                $slot = $row['slot'];
                $seq = $row['sequence'];
                $vehicle_id = $row['vehicle_id'];

                if ($zone == 0) {
                    continue;
                }
                $orders[$zone][$slot][$vehicle_id][$seq] = array(
                    'oid' => $row['order_id'],
                    'vehno' => $row['vehicleno'],
                    'areaid' => $row['areaid'],
                );
            }
        }
        return array(
            'orders' => $orders,
        );
    }

    public function getMappedOrders_pickup($dateI) {
        $date = date('Y-m-d', strtotime($dateI));
        $orders = Array();
        $Query = "SELECT a.pickupid,  b.orderid, a.sequence, b.vendorno, d.vendorname, a.update_time, c.username
        FROM " . DATABASE_PICKUP . ".order_route_sequence as a
        left join " . DATABASE_PICKUP . ".pickup_order as b on a.orderid=b.oid
        left join " . DATABASE_PICKUP . ".pickup_vendor as d on d.vendorid=b.vendorno
        left join user as c on a.pickupid=c.userid ";
        $Query .= "where b.pickupdate='$date'";
        $SQL = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $zone = 1;
                $slot = 1;
                $seq = $row['sequence'];
                $vehicle_id = $row['pickupid'];

                if ($zone == 0) {
                    continue;
                }
                $orders[$zone][$slot][$vehicle_id][$seq] = array(
                    'oid' => $row['orderid'],
                    'vehno' => $row['username'],
                    'areaid' => $row['vendorname'],
                );
            }
        }
        return array(
            'orders' => $orders,
        );
    }

    /* get non-delivered orders and details of specific vehicles of current-date
     * and update
     */

    public function get_curr_orders($vehicleid, $cur_slot = '', $timing) {
        $collected = 0;
        $output = array();
        $dataset = Array();
        if ($timing == null) {
            $timing = "Not Defined";
        }
        $orders = Array();
        $date = date('Y-m-d');
        //$date = '2016-07-27';
        //$slot = ($cur_slot == '') ? '' : " and b.slot=$cur_slot ";
        $slot = "";
        if (!empty($cur_slot)) {
            $slot = " and b.slot= $cur_slot ";
        }
        //$slot = ($cur_slot == '') ? '' : " and b.slot=1 ";
        $Query = "SELECT a.sequence, a.order_id, b.slot, b.order_id as bill_no, b.lat, b.longi, b.accuracy, b.total_amount, b.reedeem_limit, b.iscanceled, b.is_delivered, c.landmark, d.areaname as area, c.address_main, c.full_name, c.flat, c.building, c.street, c.city, c.phone, c.pincode
        FROM order_route_sequence as a
        left join master_orders as b on a.order_id=b.id
        left join master_shipping_address as c on a.order_id=c.orderid
        left join area_master as d on b.customerno=d.customerno and b.fenceid=d.zone_id and b.areaid=d.area_id
        where b.customerno=$this->_Customerno and b.delivery_date='$date' and a.vehicle_id=$vehicleid and b.is_delivered=0 and b.iscanceled=0 $slot order by a.sequence asc";
        //echo "<br><hr>".$Query;
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $dataset[] = $row;
            }

            foreach ($dataset as $row) {
                if ($row['is_delivered'] == 0 && $row['iscanceled'] == 0) {
                    $is_delivered = 0;
                } else if ($row['is_delivered'] == 1 && $row['iscanceled'] == 0) {
                    $is_delivered = 1;
                } else if ($row['is_delivered'] == 0 && $row['iscanceled'] == 1) {
                    $is_delivered = -1;
                }

                if ($row['total_amount'] == '') {
                    $row['total_amount'] = '0';
                }
                if ($row['reedeem_limit'] == '') {
                    $row['reedeem_limit'] = '0';
                }

                $collected = $this->getPaidAmt($row['order_id']);

                $orders[] = array(
                    'id' => $row['order_id'],
                    'billno' => $row['bill_no'],
                    'name' => $row['full_name'],
                    'flat' => $row['flat'],
                    'building' => $row['building'],
                    'street' => $row['street'],
                    'area' => isset($row['area'])?$row['area']:'',
                    'landmark' => $row['landmark'],
                    'address' => $row['address_main'],
                    'city' => $row['city'],
                    'phone' => $row['phone'],
                    'accuracy' => $row['accuracy'],
                    'slot_no' => $row['slot'],
                    'slottime' => $timing,
                    'sequence' => $row['sequence'],
                    'latitude' => $row['lat'],
                    'longitude' => $row['longi'],
                    'pincode' => $row['pincode'],
                    'total_amount' => $row['total_amount'],
                    'redeem_limit' => $row['reedeem_limit'],
                    'isdelivered' => $is_delivered,
                    'collected' => $collected,
                );
            }
            $output = $orders;
        } else {
            $output = $orders;
        }
        return $output;
    }

    /* set order as delivered */

    function all_slots_refresh() {
        $json = Array();
        $sqlquery = "select * from slot_master WHERE customerno = $this->_Customerno AND isdeleted=0";
        $this->_databaseManager->executeQuery($sqlquery);
        while ($data = $this->_databaseManager->get_nextrow()) {
            $reason = new VODelivery();
            $reason->slot_no = $data['customer_slot_id'];
            $reason->slottime = date(speedConstants::DEFAULT_TIME, strtotime($data['start_time'])) . " - " . date(speedConstants::DEFAULT_TIME, strtotime($data['end_time']));
            $json[] = $reason;
        }
        return $json;
    }

    function order_delivered($orderid, $signature, $latitude, $longitude,$isoffline) {
        $time = date("Y-m-d H:i:s");
        $Query = "update master_orders set delivery_lat='" . $latitude . "', delivery_long='" . $longitude . "', delivery_time='" . $time . "', is_delivered=1, isoffline=".$isoffline." where customerno=%d and id = %d";
        $SQL = sprintf($Query, $this->_Customerno, $orderid);
        $this->_databaseManager->executeQuery($SQL);
        $Query = "update master_shipment set shipping_status=5 where orderid = %d";
        $SQL = sprintf($Query, $orderid);
        $this->_databaseManager->executeQuery($SQL);
//      $signature='iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABGpJREFUeNqcVntIpFUUv/PNN1S+cs10dGQdfBer4msxZXAyTbPAVyxCDhiBQlIRlusfS8ksFESCk4oPrGQRFGfxRauJOgq6iMKmo7uOhoimZpkzPkbzMTPezhnmq2l2ppnpwuX7vnPP3N89v3PO7w6PUkpcGSaTScDn8w3gL+DxeAbixmBcdUSAo6MjP/I/hssgHR0dH+bm5qpHR0e/tbeu1+vFGCW+X11dCbh380C6nM3W1tZPY2Ji/hSLxbSxsfHSdn1zc/N1iURCZTIZXVhY+AhtFiDzulOA5ubm29HR0eeJiYkUnrStrW3f1ken0zEFBQVNwcHBFP16e3tV1ut2N4YkM/hsaGi4ExUVZQZITk7+Fwie9OTkJGR4ePg69zvwV4A/jYiIoH19fSOcn8MIFArFZ9YAKSkpT0UyMzMjDwwMfJSWlqaEXD2PNqVS+TEC4W80Go3MYSR1dXV3kaKkpCSzM/dEkJaWliPOD5LNyuVy88khZxSAhGivr6//MigoiJaXl1NgRcuDRCVtbGy8xLKsAcrUODs7K+3u7n7P09PzOYZhiHUfAT2kqqrq96Kiosytra0X4uPjp7BCOzs7cwHsAURFpqameOfn50HFxcW/rq2tEdhLx19cXHzc39//Dpzi7ZGRkVvLy8s3AQAb7qkyvby8JBkZGV6Dg4OVNTU1766vrz/Jy8t7EhcXp93b27s9Pj5O4GDrUGkPDQbDtaGhodSAgIAzBlCJr68v8fb2Nk8PD4//7BdI5Cc5OTlvxcbGLsPhPq+srLwLZl1ZWVmCv78/UalU99AvPT29y8/PjwBTCPwPJc4kBtcvLi6ekUqlD4AiKdBFxsbG7qyurt4MDw9fgJztbG9vk93dXUFkZOQsREHwm3FbIhjGhE845R9QeQ9PT0/Jzs5OuMX2m9FoJGdnZzfwG/JMDg8P3QeBaPj4hAZ8cX5+Ph0pDgkJ+RltWq1WhBsD5Y+5HGIqGOCY2EuyvYF+AoHgYnJy8s3S0tJJtVp9Iysri0BfPIIiiAPahCKRiAiFQgNUVvL+/j4egDCYaFBXrHlziWL43IYO1PhrqMIflpaWXs7Pz1fW1tYKLQKqxk0zMzPL8Xt6evrawcEBSUhIIGxTU9N16BMRnNAIG1/Nzc29BrXdjuC2fYLvQMF8dXX1rZKSEgkk/nu0QxG839XVpYJk/wIRdqDtPgwvLy+SnZ3tsOMLUBo4SeEmdjwo8k+c3/HxMQNNqAgLCzNBx/9osbPQ8d+gWFZUVFBIh9ahdoHYvYFSYQ1k0a6/QSDqD6DLKWiXnrP19PQEosygFK2srBQ6lXqgMts6ImsQOCEL+fMHpfgKJOZZtMFd8wX6A210YGDgO4uis65cWK9yEdnSZXWf8AsLCzVQWWY/UIJ7Tu8T29ne3p6BAKGhoRSi09i5GSXczQhlLbNddwkEJ5ToK6mpqfqJiQm5vXXobDFSyFFpvcZz9S8RDugnxsfHh4FSN7qjEm6BWHqFdRfkLwEGAFYS+tFIOBZTAAAAAElFTkSuQmCC';
        $target_path = RELATIVE_PATH_DOTS."customer/" . $this->_Customerno . "/routing/";
        if (!is_dir($target_path)) {
            mkdir($target_path, 0777, true) or die("Could not create directory");
        }
        $target_path_signature = $target_path . "signature/";
        if (!is_dir($target_path_signature)) {
            mkdir($target_path_signature, 0777, true) or die("Could not create directory");
        }
        $image = base64_decode($signature);
        file_put_contents(RELATIVE_PATH_DOTS."customer/" . $this->_Customerno . "/routing/signature/".$orderid .".jpg", $image);
    }

    function push_photos($orderid, $photos) {
        /**
          $photos='iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABGpJREFUeNqcVntIpFUUv/PNN1S+cs10dGQdfBer4msxZXAyTbPAVyxCDhiBQlIRlusfS8ksFESCk4oPrGQRFGfxRauJOgq6iMKmo7uOhoimZpkzPkbzMTPezhnmq2l2ppnpwuX7vnPP3N89v3PO7w6PUkpcGSaTScDn8w3gL+DxeAbixmBcdUSAo6MjP/I/hssgHR0dH+bm5qpHR0e/tbeu1+vFGCW+X11dCbh380C6nM3W1tZPY2Ji/hSLxbSxsfHSdn1zc/N1iURCZTIZXVhY+AhtFiDzulOA5ubm29HR0eeJiYkUnrStrW3f1ken0zEFBQVNwcHBFP16e3tV1ut2N4YkM/hsaGi4ExUVZQZITk7+Fwie9OTkJGR4ePg69zvwV4A/jYiIoH19fSOcn8MIFArFZ9YAKSkpT0UyMzMjDwwMfJSWlqaEXD2PNqVS+TEC4W80Go3MYSR1dXV3kaKkpCSzM/dEkJaWliPOD5LNyuVy88khZxSAhGivr6//MigoiJaXl1NgRcuDRCVtbGy8xLKsAcrUODs7K+3u7n7P09PzOYZhiHUfAT2kqqrq96Kiosytra0X4uPjp7BCOzs7cwHsAURFpqameOfn50HFxcW/rq2tEdhLx19cXHzc39//Dpzi7ZGRkVvLy8s3AQAb7qkyvby8JBkZGV6Dg4OVNTU1766vrz/Jy8t7EhcXp93b27s9Pj5O4GDrUGkPDQbDtaGhodSAgIAzBlCJr68v8fb2Nk8PD4//7BdI5Cc5OTlvxcbGLsPhPq+srLwLZl1ZWVmCv78/UalU99AvPT29y8/PjwBTCPwPJc4kBtcvLi6ekUqlD4AiKdBFxsbG7qyurt4MDw9fgJztbG9vk93dXUFkZOQsREHwm3FbIhjGhE845R9QeQ9PT0/Jzs5OuMX2m9FoJGdnZzfwG/JMDg8P3QeBaPj4hAZ8cX5+Ph0pDgkJ+RltWq1WhBsD5Y+5HGIqGOCY2EuyvYF+AoHgYnJy8s3S0tJJtVp9Iysri0BfPIIiiAPahCKRiAiFQgNUVvL+/j4egDCYaFBXrHlziWL43IYO1PhrqMIflpaWXs7Pz1fW1tYKLQKqxk0zMzPL8Xt6evrawcEBSUhIIGxTU9N16BMRnNAIG1/Nzc29BrXdjuC2fYLvQMF8dXX1rZKSEgkk/nu0QxG839XVpYJk/wIRdqDtPgwvLy+SnZ3tsOMLUBo4SeEmdjwo8k+c3/HxMQNNqAgLCzNBx/9osbPQ8d+gWFZUVFBIh9ahdoHYvYFSYQ1k0a6/QSDqD6DLKWiXnrP19PQEosygFK2srBQ6lXqgMts6ImsQOCEL+fMHpfgKJOZZtMFd8wX6A210YGDgO4uis65cWK9yEdnSZXWf8AsLCzVQWWY/UIJ7Tu8T29ne3p6BAKGhoRSi09i5GSXczQhlLbNddwkEJ5ToK6mpqfqJiQm5vXXobDFSyFFpvcZz9S8RDugnxsfHh4FSN7qjEm6BWHqFdRfkLwEGAFYS+tFIOBZTAAAAAElFTkSuQmCC';
         *
         */
        $target_path = RELATIVE_PATH_DOTS."customer/" . $this->_Customerno . "/routing/";
        if (!is_dir($target_path)) {
            mkdir($target_path, 0777, true) or die("Could not create directory");
        }

        $target_path_signature = $target_path . "photos/$orderid";

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

        $image = base64_decode($photos);
        file_put_contents(RELATIVE_PATH_DOTS."customer/" . $this->_Customerno . "/routing/photos/" . $orderid . "/" . $cnt . ".jpg", $image);
    }

    function order_cancelled($orderid, $reasonid,$isoffline) {
        $timestamp = date('Y-m-d H:i:s');
        $Query = "update master_orders set iscanceled=1,isoffline=".$isoffline." where customerno=%d and id = %d";
        $SQL = sprintf($Query, $this->_Customerno, $orderid);
        $this->_databaseManager->executeQuery($SQL);

        $Query = "update master_shipment set shipping_status=7 where orderid = %d";
        $SQL = sprintf($Query, $orderid);
        $this->_databaseManager->executeQuery($SQL);

        $sqlinsert = "insert into master_history(status, orderid, customerno, timestamp)values('$reasonid','$orderid', '$this->_Customerno', '$timestamp') ";
        $this->_databaseManager->executeQuery($sqlinsert);
    }

    /* get slots of a specific customer */

    function get_customer_slots() {
        $slots = Array();
        $SQL = "select customer_slot_id, date_format(start_time, '%H:%i') as start, date_format(end_time, '%H:%i') as end from slot_master where customerno =$this->_Customerno and isdeleted=0";

        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $slots[$row['customer_slot_id']] = array(
                    'timing' => $row['start'] . ' - ' . $row['end'],
                    'start_time' => $row['start'],
                    'end_time' => $row['end'],
                );
            }
        }
        return $slots;
    }

    function get_customer_slots_pickup() {
        $slots = Array();
        $SQL = "select customer_slot_id, date_format(start_time, '%H:%i') as start, date_format(end_time, '%H:%i') as end from " . DATABASE_PICKUP . ".slot_master where customerno =$this->_Customerno and isdeleted=0";

        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $slots[$row['customer_slot_id']] = array(
                    'timing' => $row['start'] . ' - ' . $row['end'],
                    'start_time' => $row['start'],
                    'end_time' => $row['end'],
                );
            }
        }
        return $slots;
    }

    /* get customer slot-details, based on current time */

    function get_current_slot_details() {
        $slots = Array();
        $date = date('H:i');
        $SQL = "select customer_slot_id, date_format(start_time, '%H:%i') as start, date_format(end_time, '%H:%i') as end
        from slot_master where customerno=$this->_Customerno and isdeleted=0  ";
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $slots[] = array(
                    'slot_id' => $row['customer_slot_id'],
                    'timing' => $row['start'] . ' - ' . $row['end'],
                    'start_time' => $row['start'],
                    'end_time' => $row['end'],
                );
            }
        }
        return $slots;
    }

    function pullreasons() {
        $json = Array();
        $sqlquery = "select * from master_reason WHERE customerno IN(0, $this->_Customerno) AND isdeleted=0";
        $this->_databaseManager->executeQuery($sqlquery);
        while ($data = $this->_databaseManager->get_nextrow()) {
            $reason = new VODelivery();
            $reason->reasonid = $data['reasonid'];
            $reason->customerno = $data['customerno'];
            $reason->reason = $data['reason'];
            $reason->timestamp = $data['timestamp'];
            $json[] = $reason;
        }
        return array(
            'reasons' => $json,
            'result' => 'success',
        );
    }

    function pullslots() {
        $json = Array();
        $sqlquery = "select * from slot_master WHERE customerno = $this->_Customerno AND isdeleted=0";
        $this->_databaseManager->executeQuery($sqlquery);
        while ($data = $this->_databaseManager->get_nextrow()) {
            $reason = new VODelivery();
            $reason->slot_no = $data['customer_slot_id'];
            $reason->slottime = $data['start_time'] . " - " . $data['end_time'];
            $json[] = $reason;
        }
        return array(
            'slots' => $json,
            'result' => 'success',
        );
    }

    function makepayment($orderid, $type, $amount, $cheque, $accountno, $bank, $branch, $reason) {
        $json = Array();
        $details = $this->getOrderDetails_payment($orderid);
        $paidamt = $this->getPaidAmt($orderid);
        $reamt = $this->getRedeemUsed($orderid);
        $payamount = $paidamt + $amount;
        $redeemamt = $reamt + $amount;
        $pending_amount = $details->total_amount - $payamount;
        if($type == 2 && $payamount <= $details->total_amount && $redeemamt <= $details->reedeem_limit){
            $timestamp = date('Y-m-d H:i:s');
            $Query = "INSERT INTO master_payment(orderid, type, amount, paymentby, pending_amt, paymentdate )Values(%d,'%s', '%s', 1, '%s', '%s') ";
            $SQL = sprintf($Query, $orderid, $type, $amount, $pending_amount, $timestamp);
            $this->_databaseManager->executeQuery($SQL);
            $lastpay = $this->getPaidAmt($orderid);
            if ($payamount == $details->total_amount){
                $status = "Complete";
            }else{
                $status = "Incomplete";
            }
            return array(
                'result' => 'success',
                'status' => $status,
                'collected' => $lastpay
            );
        } else if ($type == 2 && ($payamount >= $details->total_amount || $redeemamt >= $details->reedeem_limit)) {
            return array(
                'result' => 'failure',
                'error' => 'Payment amount not greater than total amount or redeem limit',
            );
        }

        if ($type == 3 && $payamount <= $details->total_amount) {
            $timestamp = date('Y-m-d H:i:s');
            $Query = "INSERT INTO master_payment(orderid, type, amount, chequeno, accountno, bank, branch, paymentby, pending_amt, paymentdate )Values(%d,'%s', '%s', '%s', '%s', '%s', '%s', 1, '%s', '%s') ";
            $SQL = sprintf($Query, $orderid, $type, $amount, $cheque, $accountno, $bank, $branch, $pending_amount, $timestamp);
            $this->_databaseManager->executeQuery($SQL);
            $lastpay = $this->getPaidAmt($orderid);
            if ($payamount == $details->total_amount) {
                $status = "Complete";
            } else {
                $status = "Incomplete";
            }
            return array(
                'result' => 'success',
                'status' => $status,
                'collected' => $lastpay,
            );
        }else if ($type == 3 && $payamount >= $details->total_amount){
            return array(
                'result' => 'failure',
                'error' => 'Payment amount not greater than total amount',
            );
        }

        if ($type == 4 && $reason != "") {
            $timestamp = date('Y-m-d H:i:s');
            $pending_amount = $details->total_amount - $paidamt;
            $Query = "INSERT INTO master_payment(orderid, type, reason, paymentby, pending_amt, paymentdate )Values(%d, '%s','%s', 1, '%s', '%s') ";
            $SQL = sprintf($Query, $orderid, $type, $reason,$pending_amount, $timestamp);
            $this->_databaseManager->executeQuery($SQL);
            $lastpay = $this->getPaidAmt($orderid);
            if ($payamount == $details->total_amount) {
                $status = "Complete";
            } else {
                $status = "Incomplete";
            }
            return array(
                'result' => 'success',
                'status' => $status,
                'collected' => $lastpay,
            );
        } else if ($type == 4 && $reason == "") {
            return array(
                'result' => 'failure',
                'error' => 'reason not found',
            );
        }

        if (($type == 0 || $type == 1) && $payamount <= $details->total_amount) {
            $timestamp = date('Y-m-d H:i:s');
            $Query = "INSERT INTO master_payment(orderid, type, amount, paymentby, pending_amt, paymentdate )Values(%d, '%s','%s', 1, '%s', '%s') ";
            $SQL = sprintf($Query, $orderid, $type, $amount, $pending_amount, $timestamp);
            $this->_databaseManager->executeQuery($SQL);
            $lastpay = $this->getPaidAmt($orderid);
            if ($payamount == $details->total_amount) {
                $status = "Complete";
            } else {
                $status = "Incomplete";
            }
            return array(
                'result' => 'success',
                'status' => $status,
                'collected' => $lastpay,
            );
        } else if (($type == 0 || $type == 1) && $payamount > $details->total_amount) {
            return array(
                'result' => 'failure',
                'error' => 'Payment amount not greater than total amount',
            );
        }
    }

    public function addPayment($data) {
        $timestamp = date('Y-m-d H:i:s');
        $pending_amount = $data['total_amount'] - $data['inp_amount'];

        $Query = "INSERT INTO master_payment(orderid, type, amount, chequeno, accountno, bank, branch, reason, paymentby, pending_amt, paymentdate )Values(%d,'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s') ";
        $SQL = sprintf($Query, $data['orderid'], $data['type'], $data['inp_amount'], $data['$inp_chkno'], $data['inp_accno'], $data['inp_bank'], $data['inp_branch'], $data['inp_reason'], $data['paymentby'], $pending_amount, $timestamp);
        $this->_databaseManager->executeQuery($SQL);

        $response = 'ok';
        return $response;
    }

    public function get_order_sequence($vid, $sid, $date) {
        $groupQ = $vehQ = $slotQ = '';
        if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
            $grpid = (int) $_SESSION['groupid'];
            $groupQ = " AND z.groupid = $grpid ";
        }
        $Ddate = date('Y-m-d', strtotime($date));
        if ($vid != 0) {
            $vehQ = " AND a.vehicle_id=$vid ";
        }
        if ($sid != 0) {
            $slotQ = " AND b.slot=$sid ";
        }

        $query = "select round(a.time_taken/60) as time , b.fenceid, b.delivery_date,v.vehicleid, v.vehicleno, b.slot, a.sequence, b.order_id, z.zonename, concat_ws(',', msa.flat, msa.building,msa.landmark,am.areaname,msa.city) as address, a.vehicle_id ";
        $query .= " from order_route_sequence as a";
        $query .= " inner join master_orders as b on b.id=a.order_id ";
        $query .= " inner join master_shipping_address as msa on msa.orderid=a.order_id";
        $query .= " left join " . DATABASE_SPEED . ".vehicle as v on v.vehicleid=a.vehicle_id and v.isdeleted=0 ";
        $query .= " left join area_master as am on am.area_id=b.areaid and am.customerno=b.customerno and am.isdeleted=0 ";
        $query .= " left join zone_master as z on z.zone_id=b.fenceid and z.customerno=b.customerno and z.isdeleted=0 ";
        $query .= " where b.iscanceled=0 AND date(delivery_date)='$Ddate' $vehQ $slotQ and b.customerno=$this->_Customerno $groupQ";
        $query .= " order by v.vehicleno,b.slot, a.sequence";

        $this->_databaseManager->executeQuery($query);

        if ($this->_databaseManager->get_rowCount() > 0) {
            $data = array();
            $count_d = array();
            while ($row = $this->_databaseManager->get_nextrow()) {
                $data[] = array(
                    'delivery_date' => $row['delivery_date'],
                    'vno' => $row['vehicleno'],
                    'time' => $row['time'],
                    'slot' => $row['slot'],
                    'seq' => $row['sequence'],
                    'oid' => $row['order_id'],
                    'zname' => $row['zonename'],
                    'zid' => $row['fenceid'],
                    'address' => $row['address'],
                    'vehicleid' => $row['vehicleid'],
                );
                /* if(isset($count_d[$row['delivery_date']])){
                  $count_d[$row['delivery_date']] += 1;
                  }
                  else{
                  $count_d[$row['delivery_date']] = 1;
                  }
                  if(isset($count_d[$row['vehicleno']])){
                  $count_d[$row['vehicleno']] += 1;
                  }
                  else{
                  $count_d[$row['vehicleno']] = 1;
                  } */
            }
            //echo "<pre>";print_r($count_d);die();
            return $data;
        } else {
            return null;
        }
    }

    public function get_order_sequence_pickup($vid, $sid, $date) {
        $groupQ = $vehQ = $slotQ = '';
        if (isset($_SESSION['groupid']) && $_SESSION['groupid'] != 0) {
            $grpid = (int) $_SESSION['groupid'];
            $groupQ = " AND z.groupid = $grpid ";
        }
        $Ddate = date('Y-m-d', strtotime($date));
        if ($vid != 0) {
            $vehQ = " AND a.pickupid=$vid ";
        }

        $query = "select round(a.time_taken/60) as time , b.pickupdate,u.userid, u.username, msa.vendorname, a.sequence, b.oid,b.orderid, concat_ws(',', msa.address, msa.pincode) as address, a.pickupid ";
        $query .= " from " . DATABASE_PICKUP . ".order_route_sequence as a";
        $query .= " inner join " . DATABASE_PICKUP . ".pickup_order as b on b.oid=a.orderid ";
        $query .= " inner join " . DATABASE_PICKUP . ".pickup_vendor as msa on msa.vendorid=b.vendorno";
        $query .= " left join user as u on u.userid=a.pickupid and u.isdeleted=0 ";
        //$query .= " left join ".DATABASE_PICKUP.".zone_master as z on z.customerno=b.customerno and z.isdeleted=0 ";
        $query .= " where date(pickupdate)='$Ddate' $vehQ and b.customerno=$this->_Customerno $groupQ";
        $query .= " order by u.userid,a.sequence";

        $this->_databaseManager->executeQuery($query);

        if ($this->_databaseManager->get_rowCount() > 0) {
            $data = array();
            $count_d = array();
            while ($row = $this->_databaseManager->get_nextrow()) {
                $data[] = array(
                    'pickupdate' => $row['pickupdate'],
                    'username' => $row['username'],
                    'vendorname' => $row['vendorname'],
                    'time' => $row['time'],
                    'seq' => $row['sequence'],
                    'oid' => $row['orderid'],
                    'address' => $row['address'],
                );
                /* if(isset($count_d[$row['delivery_date']])){
                  $count_d[$row['delivery_date']] += 1;
                  }
                  else{
                  $count_d[$row['delivery_date']] = 1;
                  }
                  if(isset($count_d[$row['vehicleno']])){
                  $count_d[$row['vehicleno']] += 1;
                  }
                  else{
                  $count_d[$row['vehicleno']] = 1;
                  } */
            }
            //echo "<pre>";print_r($count_d);die();
            return $data;
            //print_r($data);
        } else {
            return null;
        }
    }

    function addZone($zoneid, $zonename) {
        $timestamp = date('Y-m-d H:i:s');
        $Query = "INSERT INTO " . DATABASE_NAME . ".zone_master(customerno, zone_id, zonename, entry_date, isdeleted)
        values('%s', '%d', '%s', '%s', 0)";
        $SQL = sprintf($Query, $this->_Customerno, $zoneid, $zonename, $timestamp);
        $this->_databaseManager->executeQuery($SQL);

        $zoneid = $this->_databaseManager->get_insertedId();

        if (isset($zoneid)) {
            $response = $zoneid;
        } else {
            $response = 'not ok';
        }
        return $response;
    }

    function addArea($zoneid, $areaid, $areaname, $lat, $lng) {
        $timestamp = date('Y-m-d H:i:s');
        $Query = "INSERT INTO " . DATABASE_NAME . ".area_master(customerno, zone_id, area_id, areaname, lat, lng, entry_date, isdeleted) values('%s', '%d', '%d', '%s', '%s','%s','%s', 0)";
        $SQL = sprintf($Query, $this->_Customerno, $zoneid, $areaid, $areaname, $lat, $lng, $timestamp);
        $this->_databaseManager->executeQuery($SQL);

        $area_id = $this->_databaseManager->get_insertedId();

        if (isset($area_id)) {
            $response = $area_id;
        } else {
            $response = 'not ok';
        }
        return $response;
    }

    function addSlot($slotid, $start, $end) {
        $timestamp = date('Y-m-d H:i:s');
        $Query = "INSERT INTO " . DATABASE_NAME . ".slot_master(customerno, customer_slot_id, start_time, end_time, created_by, isdeleted) values(%d, %d, '%s', '%s', '%s', 0)";
        $SQL = sprintf($Query, $this->_Customerno, $slotid, $start, $end, $_SESSION['userid']);
        $this->_databaseManager->executeQuery($SQL);

        $slot_id = $this->_databaseManager->get_insertedId();

        if (isset($slot_id)) {
            $response = $slot_id;
        } else {
            $response = 'not ok';
        }
        return $response;
    }

    function getdeliverydata() {
        $data = Array();
        $Query = "select v.vehicleno,u.realname,u.delivery_vehicleid,mo.order_id, mo.delivery_date, mo.slot, mo.lat, mo.longi, mo.delivery_long, mo.delivery_lat, mo.delivery_time, mo.is_delivered from master_orders as mo
            left join " . DB_PARENT . ".`user` as u  on u.userid = mo.deliveryboyid and u.customerno = mo.customerno
            left join " . SPEEDDB . ".`vehicle` as v on v.vehicleid = u.delivery_vehicleid
            WHERE mo.customerno=%d and mo.is_delivered=1";
        $SQL = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $data[] = array(
                    'orderid' => $row['order_id'],
                    'delivery_date' => $row['delivery_date'],
                    'slot' => $row['slot'],
                    'lat' => $row['lat'],
                    'longi' => $row['longi'],
                    'delivery_lat' => $row['delivery_lat'],
                    'delivery_long' => $row['delivery_long'],
                    'delivery_time' => $row['delivery_time'],
                    'is_delivered' => $row['is_delivered'],
                    'vehicleno' => $row['vehicleno'],
                    'realname' => $row['realname'],
                );
            }
            return $data;
        }
        return NULL;
    }

    function getdeliveryboy() {
        $result = array();
        $data = array();
        $Query = "SELECT * FROM " . SPEEDDB . ".user where customerno = %d and roleid IN (47) and isdeleted = 0";
        $SQL = sprintf($Query, $this->_Customerno);
        $this->_databaseManager->executeQuery($SQL);

        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $del = new stdClass();
                $del->pid = $row['userid'];
                $del->name = $row['realname'];
                $del->username = $row['username'];
                $del->phone = $row['phone'];
                $del->email = $row['email'];
                $data[] = $del;
            }
        }
            $Query = "SELECT * FROM " . SPEEDDB . ".user where customerno = %d and roleid IN (11) and isdeleted = 0";
            $SQL = sprintf($Query, $this->_Customerno);
            $this->_databaseManager->executeQuery($SQL);
            $data1[] = array();
            if ($this->_databaseManager->get_rowCount() > 0) {
                while ($row = $this->_databaseManager->get_nextRow()) {
                    $del = new stdClass();
                    $del->pid = $row['userid'];
                    $del->name = $row['realname'];
                    $del->username = $row['username'];
                    $del->phone = $row['phone'];
                    $del->email = $row['email'];
                    $data1[] = $del;
                }
            }

        $result = array_merge($data,$data1);
        return $result;
    }

    function pullorders(){
        $today = date('Y-m-d');
        $Query = "SELECT a.order_id,u.realname,a.deliveryboyid,a.delivery_lat,a.delivery_long, b.zonename, d.areaname, c.flat, c.building, c.city, c.landmark , a.slot, a.delivery_date, date(created_on) as orderdate, f.statusname, a.id, e.route
FROM master_orders as a
left join " . DB_PARENT . ".`user` as u on u.userid = a.deliveryboyid
left join zone_master as b on a.fenceid=b.zone_id and a.customerno=b.customerno
left join area_master as d on a.fenceid=d.zone_id and a.areaid=d.area_id and a.customerno=d.customerno
left join master_shipping_address as c on a.id=c.orderid
left join master_shipment as e on a.id = e.orderid
left join master_status as f on e.shipping_status = f.statusid
WHERE  a.customerno=" . $this->_Customerno . " AND a.delivery_date= '" . $today . "' ORDER BY  id DESC";
        $SQL = sprintf($Query);
        $this->_databaseManager->executeQuery($SQL);
        $data = array();
        if ($this->_databaseManager->get_rowCount() > 0){
            while ($row = $this->_databaseManager->get_nextRow()) {
                $del = new stdClass();
                $del->order_id = $row['order_id'];
                $del->zonename = $row['zonename'];
                $del->areaname = $row['areaname'];
                $del->flat = $row['flat'];
                $del->building = $row['building'];
                $del->city = $row['city'];
                $del->landmark = $row['landmark'];
                $del->slot = $row['slot'];
                $del->delivery_date = $row['delivery_date'];
                $del->orderdate = $row['orderdate'];
                $del->statusname = isset($row['statusname']) ? $row['statusname'] : "Ongoing";
                $oid = $row['id'];
                $del->deliveryboyid = $row['deliveryboyid'];
                $del->realname = $row['realname'];
                $view = " <a href='assign.php?id=14&oid=$oid'><img src='../../images/icon_pages.png' width='16px;' height='16px;' alt='View'  title='View' /></a>";
                $payment = "<a href='assign.php?id=13&oid=$oid'><img src='../../images/icon_money.png' width='16px;' height='16px;' alt='Payment' title='Payment' /></a>";
                $editlink = "<a href='assign.php?id=9&oid=$oid'><img src='../../images/edit.png' width='16px;' height='16px;' alt='Edit' title='Edit'  /></a>$view $payment";

                $signpath = "../../customer/" . $this->_Customerno . "/routing/signature/" . $oid . ".jpg";

                $photo = "";
                $signature = "";
                if (file_exists($signpath)) {
                    $signature = "<img width='150px;' height='80px;' src='../../customer/" . $this->_Customerno . "/routing/signature/" . $oid . ".jpg'/>";
                }

                $photopath = "../../customer/" . $this->_Customerno . "/routing/photos/" . $oid . "/";
                if (is_dir($photopath)) {
                    $files = scandir($photopath);
                    foreach ($files as $file) {
                        if ($file != ".." && $file != '.') {
                            //echo $file;
                            $photo = '<img alt="signature" width="150px;" height="80px;" src="' . $photopath . $file . '" style="border:1px solid #ccc; padding: 5px;" margin-left:25px; />';
                            echo "&nbsp;&nbsp;";
                        }
                    }
                }
                $del->editlink = $editlink;
                $del->signature = $signature;
                $del->photo = $photo;
                $del->route = $row['route'];
                $del->id = $oid;
                $del->delivery_lat = $row['delivery_lat'];
                $del->delivery_long = $row['delivery_long'];
                $data[] = $del;
            }
            return $data;
        }else{
            return NULL;
        }
    }

    public function get_deliveryboyname($vehicleid) {
        $realname = "";
        $query = "select realname from " . SPEEDDB . ".user where delivery_vehicleid=" . $vehicleid;
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $realname = $row['realname'];
            }
            return $realname;
        }
        return $realname;
    }

    public function add_amount_driver($data) {
        $driverid = $data['driverid'];
        $fundamount = $data['amount'];
        $amountdetail = $data['amountdetail'];
        $userid = $data['userid'];
        $customerno = $data['customerno'];

        $timestamp = date('Y-m-d H:i:s');
        $Query = " INSERT INTO " . DB_DELIVERY . ".expense_fund(userid,driverid,fundamount,advance_details ,customerno, entrytime,addedby) "
                . " values($userid,$driverid,$fundamount,'" . $amountdetail . "',$customerno,'" . $timestamp . "',$userid) ";
        $this->_databaseManager->executeQuery($Query);

        $query = "select sum(fundamount) as amount from " . DB_DELIVERY . ".expense_fund where driverid=" . $driverid . " group by driverid ";
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $amount = $row['amount'];
            }
        }
        return $amount;
    }

    public function get_all_totalfund($driverid) {
        $amount = 0;
        $query = "select sum(fundamount) as amount from " . DB_DELIVERY . ".expense_fund where driverid=" . $driverid . " group by driverid ";
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $amount = $row['amount'];
            }
        }
        return $amount;
    }

    public function getcategory($customerno) {
        $data = array();
        $query = "select * from " . DB_DELIVERY . ".master_category where customerno=" . $customerno . " AND isdeleted=0 order by categoryid desc ";
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $data[] = array(
                    'categoryid' => $row['categoryid'],
                    'categoryname' => $row['categoryname'],
                    'customerno' => $row['customerno']
                );
            }
        }
        return $data;
    }

    public function geteditcategory($customerno, $catid) {
        $data = array();
        $query = "select * from " . DB_DELIVERY . ".master_category where customerno=" . $customerno . " AND categoryid=" . $catid . " AND isdeleted=0 ";
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $data[] = array(
                    'categoryid' => $row['categoryid'],
                    'categoryname' => $row['categoryname'],
                    'customerno' => $row['customerno']
                );
            }
        }
        return $data;
    }

    public function geteditexpense($customerno, $expid) {
        $data = array();
        $query = "select mc.categoryid,mc.categoryname,e.amount,d.driverid,d.vehicleid,d.drivername,e.expid,e.expence_date  from " . DB_DELIVERY . ".expense as e "
                . " left join master_category as mc on e.categoryid = mc.categoryid inner join " . SPEEDDB . ".driver as d on d.driverid = e.driverid "
                . "  where e.customerno=" . $customerno . " AND e.isdeleted=0  AND e.expid =" . $expid . " order by e.expid desc ";
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $data[] = array(
                    'expid' => $row['expid'],
                    'expence_date' => $row['expence_date'],
                    'categoryname' => $row['categoryname'],
                    'categoryid' => $row['categoryid'],
                    'amount' => $row['amount'],
                    'drivername' => $row['drivername'],
                    'driverid' => $row['driverid'],
                    'vehicleid'=> $row['vehicleid']
                );
            }
        }
        return $data;
    }

    public function addcategory($data) {
        $categoryname = $data['category'];
        $customerno = $data['customerno'];
        $userid = $data["userid"];
        $timestamp = date('Y-m-d H:i:s');
        $Query = " INSERT INTO " . DB_DELIVERY . ".master_category(categoryname,customerno,entrytime,addedby)  values('" . $categoryname . "',$customerno,'" . $timestamp . "',$userid) ";
        $this->_databaseManager->executeQuery($Query);
        return true;
    }

    public function editcategory($data) {
        $categoryname = $data['category'];
        $customerno = $data['customerno'];
        $userid = $data["userid"];
        $catid = $data["catid"];
        $timestamp = date('Y-m-d H:i:s');
        $Query = " update  " . DB_DELIVERY . ".master_category set categoryname='" . $categoryname . "',updatedtime='" . $timestamp . "', updatedby=" . $userid . " where categoryid=" . $catid;
        $this->_databaseManager->executeQuery($Query);
        return true;
    }

    public function deletecategory($data) {
        $customerno = $data['customerno'];
        $userid = $data["userid"];
        $catid = $data["catid"];
        $timestamp = date('Y-m-d H:i:s');
        $Query = " update  " . DB_DELIVERY . ".master_category set isdeleted=1,updatedtime='" . $timestamp . "', updatedby=" . $userid . " where categoryid=" . $catid;
        $this->_databaseManager->executeQuery($Query);
        return true;
    }

    public function get_expense($customerno) {
        $data = array();
        $query = "select mc.categoryname,e.amount,d.drivername,e.expid,e.expence_date  from " . DB_DELIVERY . ".expense as e "
                . " left join master_category as mc on e.categoryid = mc.categoryid inner join " . SPEEDDB . ".driver as d on d.driverid = e.driverid "
                . "  where e.customerno=" . $customerno . " AND e.isdeleted=0  order by e.expid desc ";
        $this->_databaseManager->executeQuery($query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextrow()) {
                $data[] = array(
                    'expid' => $row['expid'],
                    'expence_date' => $row['expence_date'],
                    'categoryname' => $row['categoryname'],
                    'amount' => $row['amount'],
                    'drivername' => $row['drivername']
                );
            }
        }
        return $data;
    }

    public function edit_expense($customerno, $data) {

        $categoryid = $data['categoryid'];
        $customerno = $data['customerno'];
        $userid = $data["userid"];
        $driverid = $data['driverid'];
        $amount = $data['amount'];
        $expdate = $data['expdate'];
        $expid = $data['expid'];
        $timestamp = date('Y-m-d H:i:s');
        $Query = " update  " . DB_DELIVERY . ".expense set expence_date = '".$expdate."',amount=".$amount.",driverid=".$driverid.",categoryid=" . $categoryid . ",updatedtime='" . $timestamp . "', updatedby=" . $userid . " where expid=" . $expid;
        $this->_databaseManager->executeQuery($Query);
        return true;
    }



    public function add_expense($data) {
        $categoryid = $data['categoryid'];
        $customerno = $data['customerno'];
        $userid = $data["userid"];
        $driverid = $data['driverid'];
        $amount = $data['amount'];
        $expdate = date('Y-m-d',strtotime($data['expdate']));

        $timestamp = date('Y-m-d H:i:s');
        $Query = " INSERT INTO " . DB_DELIVERY . ".expense(categoryid,driverid,amount,expence_date,customerno,entrytime,addedby) "
                . " values($categoryid,$driverid,$amount,'".$expdate."',$customerno,'" . $timestamp . "',$userid) ";
        $this->_databaseManager->executeQuery($Query);
        return true;
    }

}
