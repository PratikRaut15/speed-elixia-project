<?php

/**
 * class of mobility-module
 */
class VOPickup {
    
}

class Pickup extends DatabasePickupWowManager {

    public function __construct($customerno, $userid) {
        parent::__construct($customerno, $userid);
        $this->customerno = $customerno;
        $this->userid = $userid;
        $this->today = date("Y-m-d H:i:s");
    }

    /**
     * To add new city in city-master
     * @param string $cityname
     */
    public function insert_citydata($cityname) {
        $Query = "Insert into city_master (customerno,cityname,entrytime,addedby) VALUES($this->customerno,'%s','$this->today',$this->userid)";
        $SQL = sprintf($Query, Sanitise::String($cityname));
        $this->executeQuery($SQL);
    }

    public function add_cusotmer($name, $phoneno, $email, $address) {
        $Query = "INSERT INTO pickup_customer(customerno, customername, phone, email, address, isdeleted)VALUES(%d,'%s','%s','%s','%s',0)";
        $SQL = sprintf($Query, $this->customerno, Sanitise::String($name), Sanitise::String($phoneno), Sanitise::String($email), Sanitise::String($address));

        $this->executeQuery($SQL);
        $responce = 'ok';
        return $responce;
    }

    public function edit_customer($customer) {
        $Query = "UPDATE pickup_customer SET customername='%s', phone='%s', email='%s', address='%s' where customerno=%d and customerid=%d";
        $SQL = sprintf($Query, $customer->name, $customer->phone, $customer->email, $customer->address, $this->customerno, $customer->customerid);
        $this->executeQuery($SQL);
        $responce = 'ok';
        return $responce;
    }

    public function edit_order($customer) {
        //old data 
        $Query1 = "select u.gcmid"
                . ",oreq.userid"
                . ",oreq.pickupboyid"
                . ",oreq.fromaddressid"
                . ",oreq.toaddressid"
                . ",oreq.pickupdate"
                . ",oreq.slotid"
                . ",oreq.paymentmodeid"
                . ",oreq.awbno"
                . ",oreq.trackingstatusid"
                . ",oreq.addedon"
                . ",oreq.updatedon"
                . ",tr.trackingstatusname"
                . ",su.realname"
                . ",u.gcmid "
                . " from orderrequest as oreq "
                . " inner join user as u on u.userid = oreq.userid AND u.approved = 1 "
                . " left join trackingstatus as tr on tr.trackingstatusid = oreq.trackingstatusid "
                . " left join " . SPEEDDB . ".user as su on su.userid = oreq.pickupboyid "
                . " where "
                . " oreq.orderid=$customer->oid "
                . " AND oreq.isdeleted=0 ";
        $SQL1 = sprintf($Query1, $this->customerno);
        $this->executeQuery($SQL1);
        while ($row = $this->get_nextRow()) {
            $oldtrackingstatusid = $row['trackingstatusid'];
            $oldpickupboyid = $row['pickupboyid'];
            $fromaddressid = $row['fromaddressid'];
            $toaddressid = $row['toaddressid'];
            $pickupdate = $row['pickupdate'];
            $oldslotid = $row['slotid'];
            $paymentmodeid = $row['paymentmodeid'];
            $awbno = $row['awbno'];
            $userid = $row['userid'];
            $addedon = $row['addedon'];
            $updatedon = $row['updatedon'];
        }
        $newpickupboyid = (isset($customer->pickupboyid) && $customer->pickupboyid != $oldpickupboyid) ? $customer->pickupboyid : $oldpickupboyid;
        $newstatus = (isset($customer->status) && $customer->status != $oldtrackingstatusid) ? $customer->status : $oldtrackingstatusid;
        $newtimeslotid = (isset($customer->timeslotid) && $customer->timeslotid != $oldslotid) ? $customer->timeslotid : $oldslotid;

        if (isset($awbno) && empty($awbno)) {
            $Query1 = "select max(seedid)as maxseedid from awbseed where isdeleted=0";
            $SQL1 = sprintf($Query1, $this->customerno);
            $this->executeQuery($SQL1);
            $maxseedid = 0;
            while ($row = $this->get_nextRow()) {
                $maxseedid = $row['maxseedid'];
            }
            $maxseedid = 1 + $maxseedid;

            $input = $maxseedid;
            $value = str_pad($input, 6, "0", STR_PAD_LEFT);
            $value = "0000" . $value;
            $awbno = "W" . $value;
            $Query = "INSERT INTO awbseed(orderid, awbno,addedby,createdtime,isdeleted)"
                    . "VALUES(%d,'%s','%s','%s',0)";
            $SQL = sprintf($Query, $customer->oid, Sanitise::String($awbno), Sanitise::String($this->userid), Sanitise::String($this->today));
            $this->executeQuery($SQL);
        }
        $updateOrderQuery = "UPDATE orderrequest SET "
                . "AWBno = '" . $awbno . "'"
                . ", pickupboyid='" . $newpickupboyid . "'"
                . ", trackingstatusid='" . $newstatus . "'"
                . ", slotid='" . $newtimeslotid . "'"
                . ", updatedon='" . $this->today . "' "
                . "where orderid=$customer->oid";
        $this->executeQuery($updateOrderQuery);

        $insertOrderReqHist = "INSERT INTO orderrequesthistory (`orderid`,`pickupboyid`,`fromaddressid`,`toaddressid`"
                . ",`pickupdate`,`slotid`, `paymentmodeid`, `trackingstatusid`, `awbno`"
                . ", `addedon` ,`updatedon`, `userid`, `inserteddatetime`) "
                . " VALUES ($customer->oid, '$newpickupboyid', '$fromaddressid', '$toaddressid'"
                . ", '$pickupdate', '$newtimeslotid', '$paymentmodeid', '$newstatus', '$awbno'"
                . ", '$addedon', '$updatedon', '$userid','$this->today')";
        $this->executeQuery($insertOrderReqHist);

        $response = 'ok';

        $Query3 = "select u.gcmid"
                . ",oreq.userid"
                . ",oreq.pickupdate"
                . ",oreq.AWBno"
                . ",oreq.trackingstatusid"
                . ",oreq.pickupboyid"
                . ",p.pickupboyname"
                . ",p.pickupboyphoto"
                . ",tr.trackingstatusname"
                . ",su.realname"
                . ",t.timeslot"
                . " from orderrequest as oreq"
                . " inner join user as u on u.userid = oreq.userid AND u.approved =1 "
                . " left join trackingstatus as tr on tr.trackingstatusid = oreq.trackingstatusid "
                . " left join pickupboy as p on p.pid = oreq.pickupboyid "
                . " left join timeslot as t on t.timeslotid = oreq.slotid "
                . " left join " . SPEEDDB . ".user as su on su.userid = oreq.pickupboyid "
                . " where oreq.orderid=$customer->oid "
                . " AND oreq.isdeleted=0 ";
        $SQL3 = sprintf($Query3, $this->customerno);
        $this->executeQuery($SQL3);
        while ($row = $this->get_nextRow()) {
            $userid = $row['userid'];
            $awbno = $row['AWBno'];
            $pickupboyname = $row['realname'];
            $gcmid = $row['gcmid'];
            $pickupboyphoto = $row['pickupboyphoto'];
            $pickupdate = $row['pickupdate'];
            $timeslot = $row['timeslot'];
            $trackingstatusid = $row['trackingstatusid'];
        }
        $tempPickupDate = new DateTime($pickupdate);
        $formattedDate = $tempPickupDate->format('d/m/Y');

        if (!empty($gcmid)) {
            $sendPickupMarshalPhoto = 0;
            $contactInfo = "Kindly contact us on  8828431582 for any issues.";
            if ($oldtrackingstatusid != $customer->status) {
                switch ($trackingstatusid) {
                    case 1: //PickedUp
                        //Your pick up of order # ………………………
                        $message = "Your order #" . $awbno . " has been picked up and the same shall be delivered soon. We will send you an update as soon as it gets delivered.";
                        break;
                    case 2: //Arrived at Wow hub
                        $message = "Your order #" . $awbno . " status has arrived at Wow Hub";
                        break;
                    case 3: // Out for delivery
                        $message = "Your order #" . $awbno . " is confirmed and is out for delivery on " . $formattedDate . " between " . $timeslot . ". Our Pickup Marshal - " . $pickupboyname . " " . $contactInfo;
                        $sendPickupMarshalPhoto = 1;
                        break;
                    case 4: // Delivered
                        $message = "Your order #" . $awbno . " status has been delivered";
                        break;
                    case 5: // Undelivered
                        $message = "Your order #" . $awbno . " status has not been delivered due to some reason. " . $contactInfo;
                        break;
                }
            }
            if ($oldpickupboyid != $customer->pickupboyid) {
                $message = "There is a change in pickup .Your order #" . $awbno . " is now being picked up by our Pickup Marshal- " . $pickupboyname . " " . $contactInfo;
                $sendPickupMarshalPhoto = 1;
            }
            $data = array(
                'message' => $message,
                'title' => 'WOW-PICKUP',
                'subtitle' => '',
                'tickerText' => '',
                'vibrate' => 1,
                'sound' => 1,
                'pickupMarshalName' => $pickupboyname,
                'pickupMarshalPhoto' => ($sendPickupMarshalPhoto) ? $pickupboyphoto : '',
                'awbno' => $awbno,
                'trackingtypeid' => 1,
                'offerimage' => ''
            );
            $result = $this->send_notification(array($gcmid), $data);
            if ($result === FALSE) {
                //$response = "Failed to send notification";
            }
        }
        return $response;
    }

    function send_notification($registration_ids, $data) {
        $url = 'https://android.googleapis.com/gcm/send';
        // prep the bundle
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $data,
        );

        $headers = array(
            'Authorization: key=' . WOW_API_KEY,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);

        // Close connection
        curl_close($ch);
        return $result;
    }

    public function edit_shiper($customer) {
        $Query = "UPDATE pickup_shiper SET sname = '%s', phone = '%s', email = '%s' where customerno = %d and sid = %d";
        $SQL = sprintf($Query, $customer->sname, $customer->phone, $customer->email, $this->customerno, $customer->sid);
        $this->executeQuery($SQL);
        $responce = 'ok';
        return $responce;
    }

    public function add_shiper($name, $phoneno, $email) {
        $Query = "INSERT INTO pickup_shiper(customerno, sname, phone, email, isdeleted)VALUES(%d, '%s', '%s', '%s', 0)";
        $SQL = sprintf($Query, $this->customerno, Sanitise::String($name), Sanitise::String($phoneno), Sanitise::String($email));

        $this->executeQuery($SQL);
        $responce = 'ok';
        return $responce;
    }

    public function getcustomers() {
        $customers = Array();
        $Query = "SELECT * from pickup_customer where customerno = %d and isdeleted = 0";
        $SQL = sprintf($Query, $this->customerno);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $pickup = new VOPickup();
                $pickup->customerid = $row['customerid'];
                $pickup->customername = $row['customername'];
                $pickup->email = $row['email'];
                $pickup->phone = $row['phone'];
                $customers[] = $pickup;
            }
            return $customers;
        } else {
            return NULL;
        }
    }

    public function getshipers() {
        $customers = Array();
        $Query = "SELECT * from pickup_shiper where customerno = %d and isdeleted = 0";
        $SQL = sprintf($Query, $this->customerno);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $pickup = new VOPickup();
                $pickup->sid = $row['sid'];
                $pickup->sname = $row['sname'];
                $pickup->email = $row['email'];
                $pickup->phone = $row['phone'];
                $customers[] = $pickup;
            }
            return $customers;
        } else {
            return NULL;
        }
    }

    public function DeleteCustomer($userid, $customerno) {
        $SQL = sprintf("UPDATE pickup_customer SET isdeleted = 1 where customerid = %d and customerno = %d", Sanitise::Long($userid), Sanitise::Long($customerno));
        $this->executeQuery($SQL);
    }

    public function DeleteShiper($userid, $customerno) {
        $SQL = sprintf("UPDATE pickup_shiper SET isdeleted = 1 where sid = %d and customerno = %d", Sanitise::Long($userid), Sanitise::Long($customerno));
        $this->executeQuery($SQL);
    }

    public function getCustomer($customerid) {
        $Query = "SELECT * FROM pickup_customer where customerno = %d and customerid = %d";
        $SQL = sprintf($Query, $this->customerno, $customerid);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $pickup = new VOPickup();
                $pickup->customerid = $row['customerid'];
                $pickup->customername = $row['customername'];
                $pickup->email = $row['email'];
                $pickup->phone = $row['phone'];
                $pickup->address = $row['address'];
            }
            return $pickup;
        } else {
            return NULL;
        }
    }

    public function getShiper($shiperid) {
        $Query = "SELECT * FROM pickup_shiper where customerno = %d and sid = %d";
        $SQL = sprintf($Query, $this->customerno, $shiperid);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $pickup = new VOPickup();
                $pickup->sid = $row['sid'];
                $pickup->sname = $row['sname'];
                $pickup->email = $row['email'];
                $pickup->phone = $row['phone'];
            }
            return $pickup;
        } else {
            return NULL;
        }
    }

    public function getPickupboy($userid) {
        $Query = "SELECT *, p.pickupboyphoto FROM " . SPEEDDB . ".user
left join " . WOWDB . ".pickupboy as p on p.userid = user.userid
where user.customerno = %d and user.userid = %d";
        $SQL = sprintf($Query, $this->customerno, $userid);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $pickup = new VOPickup();
                $pickup->pid = $row['userid'];
                $pickup->name = $row['realname'];
                $pickup->username = $row['username'];
                $pickup->customerno = $row['customerno'];
                $pickup->email = $row['email'];
                $pickup->phone = $row['phone'];
                $pickup->pickupboyimg = $row['pickupboyphoto'];
            }
            return $pickup;
        } else {
            return NULL;
        }
    }

    public function getPinno($userid) {
        $pins = Array();
        $Query = "SELECT * FROM pinmapping where customerno = %d and pid = %d and isdeleted = 0";
        $SQL = sprintf($Query, $this->customerno, $userid);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $pickup = new VOPickup();
                $pickup->mpid = $row['mpid'];
                $pickup->pid = $row['pid'];
                $pickup->customerno = $row['customerno'];
                $pickup->pincode = $row['pincode'];
                $pins[] = $pickup;
            }
            return $pins;
        } else {
            return NULL;
        }
    }

    function getallpickupstatus() {
        $Query = "SELECT * FROM trackingstatus where isdeleted = 0";
        $SQL = sprintf($Query, $this->customerno);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            $data = array();
            while ($row = $this->get_nextRow()) {
                $pickup = new VOPickup();
                $pickup->trackingstatusid = $row['trackingstatusid'];
                $pickup->trackingstatusname = $row['trackingstatusname'];
                $pickup->tracking_sequence = $row['tracking_sequence'];
                $pickup->isdeleted = $row['isdeleted'];
                $data[] = $pickup;
            }
            return $data;
        } else {
            return NULL;
        }
    }

    function getallpickupslotstime() {
        $Query = "SELECT * FROM timeslot";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            $data = array();
            while ($row = $this->get_nextRow()) {
                $pickup = new VOPickup();
                $pickup->timeslotid = $row['timeslotid'];
                $pickup->timeslot = $row['timeslot'];
                $data[] = $pickup;
            }
            return $data;
        } else {
            return NULL;
        }
    }

    function getpickup() {
        $Query = "SELECT * FROM " . SPEEDDB . ".user where customerno = %d and role = 'Pickup' and isdeleted = 0";
        $SQL = sprintf($Query, $this->customerno);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            $data = array();
            while ($row = $this->get_nextRow()) {
                $pickup = new VOPickup();
                $pickup->pid = $row['userid'];
                $pickup->name = $row['realname'];
                $pickup->username = $row['username'];
                $pickup->phone = $row['phone'];
                $pickup->email = $row['email'];
                $data[] = $pickup;
            }
            return $data;
        } else {
            return NULL;
        }
    }

    function getordercount($pid) {
        $Query = "SELECT * FROM pickup_order where customerno = %d and pickupboyid = %d and isdeleted = 0";
        $SQL = sprintf($Query, $this->customerno, $pid);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            $cnt = $this->get_rowCount();
            return $cnt;
        } else {
            return NULL;
        }
    }

    function getordercount_status($pid, $status) {
        $Query = "SELECT * FROM pickup_order where customerno = %d and pickupboyid = %d and isdeleted = 0 and status = '%s'";
        $SQL = sprintf($Query, $this->customerno, $pid, $status);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            $cnt = $this->get_rowCount();
            return $cnt;
        } else {
            return NULL;
        }
    }

    function getorders($oid) {
        $Query = "select u.userid,u.name,u.phone,a.trackingstatusid, a.orderid, a.slotid, a.fromaddressid, addr.address as fromaddress, address.address as toaddress, a.pickupboyid, a.toaddressid, a.pickupdate, ts.timeslot, tstatus.trackingstatusname, a.AWBno from orderrequest as a
                    left join timeslot as ts on a.slotid = ts.timeslotid
                    left join trackingstatus as tstatus on a.trackingstatusid = tstatus.trackingstatusid
                    left join address as addr on addr.androidaddressid = a.fromaddressid
                    left join address on address.androidaddressid = a.toaddressid
                    inner join user as u on u.userid = a.userid 
where a.orderid = %d AND a.isdeleted = 0";
        $SQL = sprintf($Query, $oid);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            $data = array();
            while ($row = $this->get_nextRow()) {
                $pickup = new VOPickup();
                $pickup->orderid = $row['orderid'];
                $pickup->fromaddressid = $row['fromaddressid'];
                $pickup->toaddressid = $row['toaddressid'];
                $pickup->awbno = $row['AWBno'];
                $pickup->trackingstatusname = $row['trackingstatusname'];
                $pickup->timeslot = $row['timeslot'];
                $pickup->pickupdate = $row['pickupdate'];
                $pickup->pickupboyid = $row['pickupboyid'];
                $pickup->slotid = $row['slotid'];
                $pickup->fromaddress = $row['fromaddress'];
                $pickup->toaddress = $row['toaddress'];
                $pickup->trackingstatusid = $row['trackingstatusid'];
                $pickup->username = $row['name'];
                $pickup->phone = $row['phone'];
                $pickup->userid = $row['userid'];
            }
            return $pickup;
        } else {
            return NULL;
        }
    }

    public function getshipping_details($oid, $userid) {
        $Query = "select orderid,userid,itemname,itemid,shipmentvalue,weightid,vehicletypeid,packingrequired,orderimage from shippingdetails where orderid = %d AND userid = %d ";
        $SQL = sprintf($Query, $oid, $userid);
        $this->executeQuery($SQL);
        $dataship = array();
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $pickup = new VOPickup();
                $pickup->orderid = $row['orderid'];
                $pickup->userid = $row['userid'];
                $pickup->itemname = $row['itemname'];
                $pickup->itemid = $row['itemid'];
                $pickup->weightid = $row['weightid'];
                $pickup->orderimage = $row['orderimage'];
                $dataship[] = $pickup;
            }
            return $dataship;
        } else {
            return NULL;
        }
    }

    public function add_vendor($vendor, $maping) {
        $Query = "INSERT INTO pickup_vendor(customerno, address, vendorname, vendorcompany, phone, email, pincode, lat, lng, isdeleted)VALUES(%d, '%s', '%s', '%s', '%s', '%s', %d, '%s', '%s', 0)";
        $SQL = sprintf($Query, $this->customerno, Sanitise::String($vendor->address), Sanitise::String($vendor->name), Sanitise::String($vendor->company), Sanitise::String($vendor->phone), Sanitise::String($vendor->email), Sanitise::String($vendor->pincode), Sanitise::String($vendor->lat), Sanitise::String($vendor->lng));
        $this->executeQuery($SQL);
        $vendorid = $this->get_insertedId();
        if (isset($vendorid) && !empty($maping)) {
            $Query = "INSERT INTO vendormapping(customerid, vendorid, vendor_no, isdeleted)VALUES(%d, %d, %d, 0)";
            foreach ($maping as $map) {
                $SQL = sprintf($Query, $map->custno, $vendorid, $map->val);
                $this->executeQuery($SQL);
            }
        }

        $responce = 'ok';
        return $responce;
    }

    public function add_pickup($vendor) {
        $userkey1 = mt_rand();
        $Query = "INSERT INTO " . SPEEDDB . ".user(customerno, realname, username, password, role, roleid, phone, email, userkey, isdeleted)values(%d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', 0)";
        $SQL = sprintf($Query, $this->customerno, Sanitise::String($vendor->name), Sanitise::String($vendor->username), sha1($vendor->password), 'Pickup', '11', Sanitise::String($vendor->phone), Sanitise::String($vendor->email), Sanitise::String($userkey1));
        $this->executeQuery($SQL);
        $userid = $this->get_insertedId();

        $Query1 = "INSERT INTO pickupboy(userid, pickupboyname, emailid, phoneno, pickupboyphoto, addedby, createdtime, updatedby, updatedtime, isdeleted)values(%d, '%s', '%s', '%s', '%s', %d, '%s', %d, '%s', 0)";
        $SQL1 = sprintf($Query1, $userid, Sanitise::String($vendor->name), Sanitise::String($vendor->email), Sanitise::String($vendor->phone), Sanitise::String($vendor->base64img), $this->userid, Sanitise::String($this->today), $this->userid, Sanitise::String($this->today));
        $this->executeQuery($SQL1);
        return $userid;
    }

    public function update_pickupboy_imgname($pickupid, $ext) {
        $imagename = $pickupid . '.' . $ext;
        $Query = "Update " . SPEEDDB . ".user SET pickupimgname = '" . $imagename . "' where userid = " . $pickupid;
        $SQL = sprintf($Query);
        $res = $this->executeQuery($SQL);
    }

    public function edit_pickup($vendor) {
        $userkey1 = mt_rand();
        //sha1('$primaryuserpassword')
        $Query = "Update " . SPEEDDB . ".user SET customerno = '$this->customerno', realname = '$vendor->name', username = '$vendor->username', email = '$vendor->email', phone = '$vendor->phone' where customerno = $this->customerno and userid = $vendor->pid";
        $SQL = sprintf($Query);
        $res = $this->executeQuery($SQL);

        $Query = "Update pickupboy SET pickupboyname = '" . $vendor->name . "', emailid = '" . $vendor->email . "', phoneno = '" . $vendor->phone . "', updatedby = " . $this->userid . ", updatedtime = '" . $this->today . "' where userid = '$vendor->pid'";
        $SQL = sprintf($Query);
        $res = $this->executeQuery($SQL);

        /* if (!empty($maping)) {

          $SQL = "update pinmapping SET isdeleted = 1 where pid = %d and customerno = %d ";
          $SQLEXEC = sprintf($SQL, $vendor->pid, $this->customerno);
          $this->executeQuery($SQLEXEC);

          $Query = "INSERT INTO pinmapping(customerno, pid, pincode, isdeleted)VALUES(%d, %d, %d, 0)";
          foreach ($maping as $map) {
          $SQL = sprintf($Query, $this->customerno, $vendor->pid, $map->value);
          $this->executeQuery($SQL);
          }
          } */

        $responce = 'ok';
        return $responce;
    }

    public function edit_vendor($vendor, $maping) {
        $Query = "UPDATE pickup_vendor SET address = '%s', vendorname = '%s', vendorcompany = '%s', phone = '%s', email = '%s', pincode = %d, lat = '%s', lng = '%s' where customerno = %d and vendorid = %d";
        $SQL = sprintf($Query, Sanitise::String($vendor->address), Sanitise::String($vendor->vendorname), Sanitise::String($vendor->company), Sanitise::String($vendor->phone), Sanitise::String($vendor->email), Sanitise::String($vendor->pincode), Sanitise::String($vendor->lat), Sanitise::String($vendor->lng), $this->customerno, $vendor->vendorid);

        $this->executeQuery($SQL);

        if (isset($maping) && !empty($maping)) {
            $Del = "UPDATE vendormapping SET isdeleted = 1 where vendorid = %d";
            $SQLDEL = sprintf($Del, $vendor->vendorid);
            $this->executeQuery($SQLDEL);
            $Query = "INSERT INTO vendormapping(customerid, vendorid, vendor_no, isdeleted)VALUES(%d, %d, %d, 0)";
            foreach ($maping as $map) {
                $SQL = sprintf($Query, $map->custno, $vendor->vendorid, $map->val);
                $this->executeQuery($SQL);
            }
        }

        $responce = 'ok';
        return $responce;
    }

    public function getvendors() {
        $vendors = Array();
        $Query = "SELECT * from pickup_vendor where customerno = %d and isdeleted = 0";
        $SQL = sprintf($Query, $this->customerno);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $pickup = new VOPickup();
                $pickup->vendorid = $row['vendorid'];
                $pickup->vendorname = $row['vendorname'];
                $pickup->vno = $row['vendorno'];
                $pickup->email = $row['email'];
                $pickup->phone = $row['phone'];
                $pickup->address = $row['address'];
                $pickup->pincode = $row['pincode'];
                $pickup->company = $row['vendorcompany'];
                $vendors[] = $pickup;
            }
            return $vendors;
        } else {
            return NULL;
        }
    }

    public function getVendor($vendorid) {
        $Query = "SELECT * FROM pickup_vendor where customerno = %d and vendorid = %d";
        $SQL = sprintf($Query, $this->customerno, $vendorid);
        $this->executeQuery($SQL);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $pickup = new VOPickup();
                $pickup->vendorid = $row['vendorid'];
                $pickup->vendorname = $row['vendorname'];
                $pickup->vno = $row['vendorno'];
                $pickup->email = $row['email'];
                $pickup->phone = $row['phone'];
                $pickup->address = $row['address'];
                $pickup->pincode = $row['pincode'];
                $pickup->company = $row['vendorcompany'];
            }
            return $pickup;
        } else {
            return NULL;
        }
    }

    public function DeleteVendor($userid, $customerno) {
        $SQL = sprintf("UPDATE pickup_vendor SET isdeleted = 1 where vendorid = %d and customerno = %d", Sanitise::Long($userid), Sanitise::Long($customerno));
        $this->executeQuery($SQL);
    }

    public function DeletePickpBoy($pid) {
        $SQL = sprintf("UPDATE " . SPEEDDB . ".user SET isdeleted = 1 where userid = %d and customerno = %d", Sanitise::Long($pid), Sanitise::Long($this->customerno));
        $this->executeQuery($SQL);
        $SQL = sprintf("UPDATE " . WOWDB . ".pickupboy SET isdeleted = 1 where userid = %d", Sanitise::Long($pid));
        $this->executeQuery($SQL);
    }

    public function getPickpBoy($vendorno) {
        $Query = "SELECT * from pickup_vendor where customerno = %d and vendorid = %d";
        $SQL = sprintf($Query, $this->customerno, $vendorno);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $vendorid = $row['vendorid'];
                $pincode = $row['pincode'];
                $SQL = "select * from pinmapping where customerno = %d and pincode = %d and isdeleted = 0";
                $Que = sprintf($SQL, $this->customerno, $pincode);
                $this->executeQuery($Que);
                if ($this->get_rowCount() > 0) {
                    while ($row = $this->get_nextRow()) {
                        return $row['pid'];
                    }
                } else {
                    return NULL;
                }
            }
        } else {
            return NULL;
        }
    }

    public function getvendor_no($customerid, $vendorid) {
        $Query = "SELECT vendor_no from vendormapping where customerid = %d and vendorid = %d and isdeleted = 0";
        $SQL = sprintf($Query, $customerid, $vendorid);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {

                return $row['vendor_no'];
            }
        } else {
            return NULL;
        }
    }

    public function SaveCheckpoint($checkpoint, $userid) {
        if (!isset($checkpoint->checkpointid)) {
            $this->Insert($checkpoint, $userid);
        } else {
            $this->Update($checkpoint, $userid);
        }
    }

    public function SaveVendors($vendor, $userid) {
        if (!isset($vendor->vendorid)) {
            $this->InsertVendor($vendor, $userid);
        } else {
            $this->Update($vendor, $userid);
        }
    }

    private function Insert($checkpoint, $userid) {
        $today = date('Y-m-d H:i:s');
        $Query = "INSERT INTO pickup_order(customerno, customerid, vendorno, orderid, fulfillmentid, awbno, shipperid, pickupboyid, pickupdate, isdeleted)VALUES(%d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', 0);
";
        $SQL = sprintf($Query, $this->customerno, Sanitise::String($checkpoint->customerid), Sanitise::String($checkpoint->vendorno), Sanitise::String($checkpoint->orderid), Sanitise::String($checkpoint->fulfillmentid), Sanitise::String($checkpoint->awbno), Sanitise::String($checkpoint->shipperid), Sanitise::String($checkpoint->pickupboy), Sanitise::Date($checkpoint->pickupdate));
        $this->executeQuery($SQL);
    }

    /**
     * 
      private function InsertVendor($vendor, $userid) {
      $today = date('Y-m-d H:i:s');

      $Query = "INSERT INTO pickup_vendor(customerno, vendorno, address, vendorname, vendorcompany, phone, email, pincode, lat, lng, isdeleted)VALUES(%d, %d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', 0)";
      $SQL = sprintf($Query, $this->customerno, Sanitise::String($vendor->vendorno), Sanitise::String($vendor->address), Sanitise::String($vendor->vendorname), Sanitise::String($vendor->vendorcompany), Sanitise::String($vendor->phone), Sanitise::String($vendor->email), Sanitise::String($vendor->pincode), Sanitise::String($vendor->lat), Sanitise::String($vendor->lng));
      $this->executeQuery($SQL);
      }
     */
    private function InsertVendor($vendor, $userid) {
        $today = date('Y-m-d H:i:s');

        $Query = "INSERT INTO pickup_vendor(customerno, address, vendorname, vendorcompany, phone, phone2, pincode, lat, lng, isdeleted)VALUES(%d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', 0)";
        $SQL = sprintf($Query, $this->customerno, Sanitise::String($vendor->address), Sanitise::String($vendor->vendorname), Sanitise::String($vendor->vendorcompany), Sanitise::String($vendor->phone1), Sanitise::String($vendor->phone2), Sanitise::String($vendor->pincode), Sanitise::String($vendor->lat), Sanitise::String($vendor->lng));
        $this->executeQuery($SQL);
        $vendorid = $this->get_insertedId();
        $Query1 = "INSERT INTO vendormapping(customerid, vendorid, vendor_no, isdeleted)VALUES(%d, %d, %d, 0)";
        $SQL1 = sprintf($Query1, '1', $vendorid, $vendor->redif);
        $SQL2 = sprintf($Query1, '2', $vendorid, $vendor->paytm);
        $this->executeQuery($SQL1);
        $this->executeQuery($SQL2);
    }

    /**
     * to check if city name exists in city-master
     * @param string $cityname
     * @return boolean
     */
    public function is_city_exists($cityname) {
        $Query = "SELECT cityid FROM city_master WHERE customerno = $this->customerno AND cityname = '%s' AND isdeleted = 0";
        $SQL = sprintf($Query, Sanitise::String($cityname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * to check if location name exists in location-master
     * @param string $location
     * @return boolean
     */
    public function is_location_exists($locationname, $cityid) {
        $Query = "SELECT location FROM location_master WHERE customerno = $this->customerno AND location = '%s' and cityid = %d AND isdeleted = 0";
        $SQL = sprintf($Query, Sanitise::String($locationname), $cityid);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * to check if trackie  emailid exists in trackie-master
     * @param string $emailid
     * @return boolean
     */
    public function is_trackie_email_exists($email) {
        $Query = "SELECT email FROM trackie WHERE customerno = $this->customerno AND email = '%s' AND isdeleted = 0";
        $SQL = sprintf($Query, $email);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To add new trackie in trackie-master
     * @param string $trackiename,$phone,$email,$address,$weeklyoff
     */
    function insert_trackiedata($trackiename, $phone, $email, $address, $weeklyoff) {
        $Query = "Insert into trackie (customerno, name, phone, email, address, weekly_off, entrytime, addedby)VALUES($this->customerno, '%s', %d, '%s', '%s', '%s', '$this->today', $this->userid)";
        $SQL = sprintf($Query, Sanitise::String($trackiename), $phone, Sanitise::String($email), Sanitise::String($address), $weeklyoff);
        $this->executeQuery($SQL);
    }

    /**
     * To add new location in location-master
     * @param string $locationname
     */
    public function insert_locationdata($cityid, $locationname) {
        $Query = "Insert into location_master (location, customerno, cityid, entrytime, addedby) VALUES('%s', $this->customerno, %d, '$this->today', $this->userid)";
        $SQL = sprintf($Query, Sanitise::String($locationname), $cityid, $this->userid);
        $this->executeQuery($SQL);
    }

    /**
     * City like query
     * @param string $q
     * @return type
     */
    public function getcitydata($q) {
        $getdata = array();
        $lq = "%$q%";
        $Query = "SELECT cityid, cityname FROM city_master WHERE customerno = $this->customerno AND cityname LIKE '%s' AND isdeleted = 0";
        $SQL = sprintf($Query, Sanitise::String($lq));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getdata[] = array(
                    'id' => $row['cityid'],
                    'value' => $row['cityname'],
                );
            }
            return $getdata;
        }
        return null;
    }

    /**
     * To check if service-name already exists
     * @param string $sname
     * @return boolean
     */
    public function is_service_exists($sname) {
        $Query = "SELECT serviceid FROM service_list WHERE customerno = $this->customerno AND service_name = '%s' AND isdeleted = 0";
        $SQL = sprintf($Query, Sanitise::String($sname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To insert service
     * @param string $sdata
     */
    public function add_service($sdata) {
        extract($sdata);
        $Query = "Insert into service_list (customerno, service_name, cost, expected_time, entrytime, addedby)
VALUES($this->customerno, '%s', %f, %d, '$this->today', $this->userid)";
        $SQL = sprintf($Query, Sanitise::String($service_name), Sanitise::Float($cost), $expTime);
        $this->executeQuery($SQL);
    }

    /**
     * to check if category name exists in category
     * @param string $catname
     * @return boolean 
     */
    public function is_category_exists($catname) {
        $Query = "SELECT categoryname FROM category WHERE customerno = $this->customerno AND categoryname = '%s' AND isdeleted = 0";
        $SQL = sprintf($Query, Sanitise::String($catname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To add new Category in category table
     * @param string $catname
     */
    public function add_categorydata($catname) {
        $Query = "Insert into category (customerno, categoryname, entrytime, addedby) VALUES($this->customerno, '%s', '$this->today', $this->userid)";
        $SQL = sprintf($Query, Sanitise::String($catname));
        $this->executeQuery($SQL);
    }

    /**
     * Common method for big forms
     * @param type $sdata
     * @return type
     */
    public function insert_arr($sdata) {
        $clmns = array();
        $values = array();
        $defc = array('customerno', 'entrytime', 'addedby');
        $defv = array($this->customerno, "'" . $this->today . "'", $this->userid);
        foreach ($sdata as $clmn => $dat) {
            $clmns[] = $clmn;
            if (is_null($dat[0])) {
                $values[] = "'" . $dat[1] . "'";
            } else {
                $values[] = "'" . Sanitise::$dat[0]($dat[1]) . "'";
            }
        }
        $clmns = array_merge($clmns, $defc);
        $values = array_merge($values, $defv);
        $cclmns = implode(',', $clmns);
        $cvalues = implode(',', $values);
        return array('clms' => $cclmns, 'cval' => $cvalues);
    }

    /**
     * Add new client
     * @param array $sdata
     */
    public function add_client($sdata) {
        $d = $this->insert_arr($sdata);
        $SQL = "insert into client({$d['clms']}) values({$d['cval']})";
        $this->executeQuery($SQL);
    }

    public function is_clientno_exists($clientno) {
        $Query = "SELECT clientid FROM client WHERE customerno = $this->customerno AND clientno = '%s' AND isdeleted = 0";
        $SQL = sprintf($Query, Sanitise::String($clientno));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * to check if style no exists in style add
     * @param string $styleno
     * @return boolean
     */
    public function is_style_number_exists($styleno) {
        $Query = "SELECT styleno FROM style WHERE customerno = $this->customerno AND styleno = '%s' AND isdeleted = 0";
        $SQL = sprintf($Query, $styleno);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To add new styleno in style table
     * @param string $styleno
     */
    public function insert_styledata($styleno, $category, $mrp, $distprice, $retprice) {
        $Query = "Insert into style (customerno, styleno, categoryid, mrp, distprice, retailprice, entrytime, addedby) VALUES($this->customerno, '%s', %d, '%s', '%s', '%s', '$this->today', $this->userid)";
        $SQL = sprintf($Query, Sanitise::String($styleno), $category, Sanitise::Float($mrp), Sanitise::Float($distprice), Sanitise::Float($retprice));
        $this->executeQuery($SQL);
    }

    /**
     * to check if State name exists in state
     * @param string $statename
     * @return boolean 
     */
    public function is_state_exists($statename) {
        $Query = "SELECT statename FROM state WHERE customerno = $this->customerno AND statename = '%s' AND isdeleted = 0";
        $SQL = sprintf($Query, Sanitise::String($statename));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To add new Statename in State table
     * @param string $statename
     */
    public function add_statedata($statename) {
        $Query = "Insert into state (customerno, statename, entrytime, addedby) VALUES($this->customerno, '%s', '$this->today', $this->userid)";
        $SQL = sprintf($Query, Sanitise::String($statename));
        $this->executeQuery($SQL);
    }

    public function get_category() {
        $Query = "SELECT categoryid, categoryname FROM category WHERE customerno = $this->customerno AND isdeleted = 0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getcatdata[] = array(
                    'id' => $row['categoryid'],
                    'value' => $row['categoryname']
                );
            }
            return $getcatdata;
        }
        return null;
    }

    public function get_state() {
        $Query = "SELECT stateid, statename FROM state WHERE customerno = $this->customerno AND isdeleted = 0";
        $SQL = sprintf($Query);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $getstatedata[] = array(
                    'id' => $row['stateid'],
                    'value' => $row['statename']
                );
            }
            return $getstatedata;
        }
        return null;
    }

    /**
     * to check if ASM name exists in asm
     * @param string $asmname
     * @return boolean 
     */
    public function is_asm_exists($asmname) {
        $Query = "SELECT asmname FROM ASM WHERE customerno = $this->customerno AND asmname = '%s' AND isdeleted = 0";
        $SQL = sprintf($Query, Sanitise::String($asmname));
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * To add new asmname in ASM table
     * @param string $asmname
     */
    public function add_asmdata($stateid, $asmname) {
        $Query = "Insert into ASM (customerno, stateid, asmname, entrytime, addedby) VALUES($this->customerno, '%d', '%s', '$this->today', $this->userid)";
        $SQL = sprintf($Query, (int) $stateid, Sanitise::String($asmname));
        $this->executeQuery($SQL);
    }

    public function getOrdersAll($customerno, $userid, $filterdata) {

        $txtcustomer = $filterdata['txtorderid'];
        $txtpickupdate = $filterdata['txtpickupdate'];
        $txtawbno = $filterdata['txtawbno'];
        $custdate = date('Y-m-d H:i:s', strtotime($txtpickupdate));

        $where = "";


        if (!empty($txtawbno)) {
            $where .= " AND a.awbno LIKE '%$txtawbno%' ";
        }

        if (!empty($txtorderid)) {
            $where .= " AND a.orderid LIKE '%$txtorderid%' ";
        }

        if (!empty($txtpickupdate)) {
            $where .= " AND a.pickupdate = '$custdate' ";
        }



        $pickups[] = array();

        $Query = "select a.orderid, u.realname, a.AWBno, ts.timeslot, a.pickupdate, tstatus.trackingstatusname
from orderrequest as a
left join timeslot as ts on a.slotid = ts.timeslotid
left join trackingstatus as tstatus on a.trackingstatusid = tstatus.trackingstatusid
left join address as addr on addr.androidaddressid = a.fromaddressid
left join address on address.androidaddressid = a.toaddressid
left join " . SPEEDDB . ".user as u on a.pickupboyid = u.userid
where a.isdeleted = 0" . $where;

        $this->executeQuery($Query);

        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextRow()) {
                $pickup = new VOPickup();
                $pickup->orderid = $row['orderid'];
                $pickup->awbno = $row['AWBno'];
                $pickup->timeslot = $row['timeslot'];
                $pickup->realname = $row['realname'];
                $pickup->pickupdate = $row['pickupdate'];
                $pickup->trackingstatusname = $row['trackingstatusname'];
                $pickups[] = $pickup;
            }
            return $pickups;
        } else {
            return NULL;
        }
    }

    public function getarea($areaid) {
        $areas = array();
        $Query = " select * from area_master where customerno = %d and area_master_id = %d";
        $SQL = sprintf($Query, $this->customerno, $areaid);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextrow()) {
                $order = new VOPickup();
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

    function addPickupZone($zoneid, $zonename) {
        $timestamp = date('Y-m-d H:i:s');
        $Query = "INSERT INTO zone_master(customerno, zone_id, zonename, entry_date, isdeleted)
values('%s', '%d', '%s', '%s', 0)";
        $SQL = sprintf($Query, $this->customerno, $zoneid, $zonename, $timestamp);
        $this->executeQuery($SQL);

        $zoneid = $this->get_insertedId();

        if (isset($zoneid)) {
            $response = $zoneid;
        } else {
            $response = 'not ok';
        }


        return $response;
    }

    function addPickupArea($zoneid, $areaid, $areaname, $lat, $lng) {
        $timestamp = date('Y-m-d H:i:s');
        $Query = "INSERT INTO area_master(customerno, zone_id, area_id, areaname, lat, lng, entry_date, isdeleted) values('%s', '%d', '%d', '%s', '%s', '%s', '%s', 0)";
        $SQL = sprintf($Query, $this->customerno, $zoneid, $areaid, $areaname, $lat, $lng, $timestamp);
        $this->executeQuery($SQL);

        $area_id = $this->get_insertedId();

        if (isset($area_id)) {
            $response = $area_id;
        } else {
            $response = 'not ok';
        }


        return $response;
    }

    public function updateAreas($data) {
        $datetime = date('Y-m-d h:i:s');
        $Query = "update area_master set lat = '{$data['lat']}', lng = '{$data['longi']}', update_date = '{$datetime}', is_approved = 1
WHERE customerno = %d AND area_master_id = %d";
        $SQL = sprintf($Query, $this->customerno, $data['areaid']);
        $this->executeQuery($SQL);
        return 'ok';
    }

    function addPickupSlot($slotid, $start, $end) {
        $timestamp = date('Y-m-d H:i:s');
        $Query = "INSERT INTO slot_master(customerno, customer_slot_id, start_time, end_time, created_by, isdeleted) values(%d, %d, '%s', '%s', '%s', 0)";
        $SQL = sprintf($Query, $this->customerno, $slotid, $start, $end, $this->userid);
        $this->executeQuery($SQL);

        $slot_id = $this->get_insertedId();

        if (isset($slot_id)) {
            $response = $slot_id;
        } else {
            $response = 'not ok';
        }


        return $response;
    }

    function get_customer_slots_pickup() {
        $slots = Array();
        $SQL = "select customer_slot_id, date_format(start_time, '%H:%i') as start, date_format(end_time, '%H:%i') as end from " . DATABASE_PICKUP . ".slot_master where customerno = $this->customerno and isdeleted = 0";

        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextrow()) {
                $slots[$row['customer_slot_id']] = array(
                    'timing' => $row['start'] . ' - ' . $row['end'],
                    'start_time' => $row['start'],
                    'end_time' => $row['end']
                );
            }
        }
        return $slots;
    }

    public function zoneSlotBasedOrders_pickup($vehid, $slotid, $date) {
        $orders = Array();
        $Query = "SELECT b.orderid, c.lat, c.lng, b.oid, c.address FROM " . DATABASE_PICKUP . ".order_route_sequence as a
left join " . DATABASE_PICKUP . ".pickup_order as b on a.orderid = b.oid
left join " . DATABASE_PICKUP . ".pickup_vendor as c on b.vendorno = c.vendorid
where a.pickupid = $vehid order by a.sequence
";
        $SQL = sprintf($Query, $this->customerno);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextrow()) {
                $orders[] = array(
                    'lat' => $row['lat'],
                    'longi' => $row['lng'],
                    'accuracy' => 1,
                    'slot' => 1,
                    'order_id' => $row['orderid'],
                    'pop_display' => "Order-Id: {$row['orderid']}<br/>Address: {$row['address']}"
                );
            }
        }
        return $orders;
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
left join " . DATABASE_PICKUP . ".zone_master as z on z.customerno = a.customerno
left join " . DATABASE_PICKUP . ".pickup_vendor as b on a.vendorno = b.vendorid
WHERE a.customerno = %d and a.pickupboyid != '' and a.pickupdate = '$dateI' and a.orderid!='' and b.lat != '' and b.lng !='' $groupQ";
        //WHERE a.customerno = %d and date(delivery_date) = '$date' $groupQ";
        $SQL = sprintf($Query, $this->customerno);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextrow()) {
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
            'total' => $total
        );
    }

    public function getMappedOrders_pickup($dateI) {
        $date = date('Y-m-d', strtotime($dateI));
        $orders = Array();
        $Query = "SELECT a.pickupid,  b.orderid, a.sequence, b.vendorno, d.vendorname, a.update_time, c.username
        FROM " . DATABASE_PICKUP . ".order_route_sequence as a
        left join " . DATABASE_PICKUP . ".pickup_order as b on a.orderid=b.oid 
        left join " . DATABASE_PICKUP . ".pickup_vendor as d on d.vendorid=b.vendorno 
        left join " . DATABASE_SPEED . ".user as c on a.pickupid=c.userid ";
        $Query.="where b.pickupdate='$date'";
        $SQL = sprintf($Query, $this->customerno);
        $this->executeQuery($SQL);
        if ($this->get_rowCount() > 0) {
            while ($row = $this->get_nextrow()) {
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
                    'areaid' => $row['vendorname']
                );
            }
        }
        return array(
            'orders' => $orders,
        );
    }

}

?>