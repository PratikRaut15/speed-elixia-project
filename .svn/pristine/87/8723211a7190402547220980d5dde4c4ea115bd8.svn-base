<?php
require_once("database.inc.php");
define("SP_CHECKLOGINREQUEST", "checkloginrequest");
define("SP_INSERT_SMSLOG", "insert_smslog");

class VODevices {

}

class Object {

}

class api {

    var $status;
    var $status_time;
    static $OTP_MESSAGE = "Thank you for registering with WOW Express. Your OTP is ";
    static $FORGOT_PWD_MSG = "Please login with temporary password: {{OTP}}\r\nValid Upto: {{VALIDUPTO_DATE}}";

    // construct
    function __construct() {
        $this->db = new database(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, WOWDB);
    }

    public function PrepareSP($sp_name, $sp_params) {
        return "call " . $sp_name . "(" . $sp_params . ");";
    }

    // <editor-fold defaultstate="collapsed" desc="API functions">
    function register($name, $email, $phone, $device_id, $platform, $password, $oauthuserid, $gcmid) {
        //echo $oauthuserid; die();
        $arr_p = Array();
        try {
            // Email Verification
            if ($email != "") {
                $q = "SELECT * FROM user WHERE email = '%s' LIMIT 1";
                $dq = sprintf($q, $email);
                $record = $this->db->query($dq, __FILE__, __LINE__);
                $arr_p['success'] = 0;
                $arr_p['message'] = "";
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['success'] = 1;
                    if ($row["approved"] == 1) {
                        $arr_p['message'] = "Email Address Already Present";
                    } else {
                        $arr_p['message'] = "OTP Already Sent";
                    }
                    echo json_encode($arr_p);
                    return json_encode($arr_p);
                }
            } else {
                $arr_p['success'] = 1;
                $arr_p['message'] = "Verification Failed";
                echo json_encode($arr_p);
                return json_encode($arr_p);
            }

            // Phone Verification
            if ($phone != "") {
                $q = "SELECT * FROM user WHERE phone = '%s' LIMIT 1";
                $dq = sprintf($q, $phone);
                $record = $this->db->query($dq, __FILE__, __LINE__);
                $arr_p['success'] = 0;
                $arr_p['message'] = "";
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['success'] = 1;
                    if ($row["approved"] == 1) {
                        $arr_p['message'] = "Phone Number Already Present";
                    } else {
                        $arr_p['message'] = "OTP Already Sent";
                    }
                    echo json_encode($arr_p);
                    return json_encode($arr_p);
                }
            } else {
                $arr_p['success'] = 1;
                $arr_p['message'] = "Verification Failed";
                echo json_encode($arr_p);
                return json_encode($arr_p);
            }

            // Register
            $date = date('Y-m-d H:i:s');
            $Query = "INSERT INTO user (`name`,`email`,`phone`,device_id, gcmid, platform, password, oauthuserid, createdon, updatedon, approved) "
                    . "VALUES ( '" . $name . "', '" . $email . "', '" . $phone . "', '" . $device_id . "', '" . $gcmid . "', '" . $platform . "', '" . $password . "', '" . $oauthuserid . "', '" . $date . "', '" . $date . "', 0)";
            $this->db->query($Query, __FILE__, __LINE__);

            // Send OTP
            if ($phone != "") {
                $q = "SELECT * FROM user WHERE phone = '%s' AND approved = 0 LIMIT 1";
                $dq = sprintf($q, $phone);
                $record = $this->db->query($dq, __FILE__, __LINE__);
                if ($this->db->num_rows($record) > 0) {
                    while ($row = $this->db->fetch_array($record)) {
                        $userid = $row['userid'];
                        $date = date('Y-m-d H:i:s');
                        $otp = rand(1000, 9999);
                        $sql = "UPDATE user SET otp=" . $otp . " where phone = '" . $phone . "' LIMIT 1";
                        $this->db->query($sql, __FILE__, __LINE__);
                        $message = api::$OTP_MESSAGE . $otp;
                        $response = '';
                        $isSMSSent = $this->sendSMS($phone, $message, $response);
                        if (is_array($phone)) {
                            foreach ($phone as $phoneno) {
                                $smsLogId = $this->insertSMSLog($phoneno, $message, $response, $userid, $isSMSSent, $date);
                            }
                        } else {
                            $smsLogId = $this->insertSMSLog($phone, $message, $response, $userid, $isSMSSent, $date);
                        }
                        $arr_p['success'] = 0;
                        $arr_p['message'] = "OTP send sucessfully";
                        $arr_p['otp'] = $otp;
                        $arr_p['smslogid'] = $smsLogId;
                    }
                } else {
                    $arr_p['success'] = 1;
                    $arr_p['message'] = "Your Phone No. is already approved";
                }
            } else {
                $arr_p['success'] = 1;
                $arr_p['message'] = "Phone No. cannot be blank";
            }
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function otpverification($phone, $otp) {
        $arr_p = Array();
        try {
            if ($otp != "") {
                $q = "SELECT * FROM user WHERE phone = '%s' AND otp = %d AND approved = 0 LIMIT 1";
                $dq = sprintf($q, $phone, $otp);
                $record = $this->db->query($dq, __FILE__, __LINE__);
                $arr_p['success'] = 1;
                $arr_p['message'] = "Verification Failed";
                $date = date('Y-m-d H:i:s');
                while ($row = $this->db->fetch_array($record)) {
                    $Query = "UPDATE user "
                            . "SET updatedon = '" . $date . "'"
                            . ", approved = 1 "
                            . "WHERE phone = '$phone' AND otp = $otp AND approved = 0";
                    $this->db->query($Query, __FILE__, __LINE__);
                    $arr_p['success'] = 0;
                    $arr_p['message'] = "Verification Sucessfully";
                }
            } else {
                $arr_p['success'] = 1;
                $arr_p['message'] = "Verification Failed";
            }
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function addaddress($addressid, $name, $street, $address, $pincode, $cityid, $stateid, $phone, $addresstype, $addressname, $userid) {

        $date = date('Y-m-d H:i:s');
        $arr_p = Array();
        try {
            $Query = "INSERT INTO address (`userid`"
                    . ",`name`"
                    . ",`street`"
                    . ",`address`"
                    . ", `pincode`"
                    . ", `cityid`"
                    . ", `stateid`"
                    . ", `phone`"
                    . ", `addresstypeid`"
                    . ", `addressname`"
                    . ",`createdon`"
                    . ", `updatedon`"
                    . ", `androidaddressid`)"
                    . " VALUES ( $userid"
                    . ", '$name'"
                    . ", '$street'"
                    . ", '$address'"
                    . ", $pincode"
                    . ", $cityid"
                    . ", $stateid"
                    . ", '$phone'"
                    . ", $addresstype"
                    . ", '$addressname'"
                    . ", '$date'"
                    . ", '$date'"
                    . ", '$addressid')";
            $this->db->query($Query, __FILE__, __LINE__);
            $arr_p['success'] = 0;
            $arr_p['message'] = "";
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function updateaddress($addressid, $name, $street, $address, $pincode, $cityid, $stateid, $phone, $addresstype, $addressname, $userid) {
        $date = date('Y-m-d H:i:s');
        $arr_p = Array();
        try {
            $sql = "UPDATE address "
                    . "SET name='" . $name
                    . "',street='" . $street
                    . "',address='" . $address
                    . "',pincode='" . $pincode
                    . "',cityid='" . $cityid
                    . "',stateid='" . $stateid
                    . "',phone='" . $phone
                    . "',addresstypeid='" . $addresstype
                    . "',addressname='" . $addressname
                    . "' where androidaddressid = '" . $addressid
                    . "' AND userid = " . $userid
                    . " LIMIT 1";
            $this->db->query($sql, __FILE__, __LINE__);
            $arr_p['success'] = 0;
            $arr_p['message'] = "";
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function pulladdress($userid) {
        $arr_p = Array();
        $json = Array();
        $x = 0;
        try {
            $q = "SELECT * FROM address WHERE userid = %d";
            $dq = sprintf($q, $userid);
            $record = $this->db->query($dq, __FILE__, __LINE__);
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['success'] = 0;
                    $arr_p['message'] = "";
                    $json[$x]['addressid'] = $row["androidaddressid"];
                    $json[$x]['name'] = $row["name"];
                    $json[$x]['street'] = $row["street"];
                    $json[$x]['address'] = $row["address"];
                    $json[$x]['pincode'] = $row["pincode"];
                    $json[$x]['cityid'] = $row["cityid"];
                    $json[$x]['phone'] = $row["phone"];
                    $json[$x]['addresstypeid'] = $row["addresstypeid"];
                    $json[$x]['addressname'] = $row["addressname"];
                    $x++;
                }
                $arr_p['data'] = $json;
            } else {
                $arr_p['success'] = 1;
                $arr_p['message'] = "No Addresses Found";
            }
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function discountcode($orderdiscountcode, $amount, $userid) {
        $date = date('Y-m-d H:i:s');
        $arr_p = Array();
        try {
            if ($orderdiscountcode != '') {
                $discounttable = 'orderdiscountmanage';
                $discountcode = $orderdiscountcode;
            }
            $q = "SELECT * FROM $discounttable INNER JOIN discount ON discount.discountid = $discounttable.discountid WHERE discount.code = '%s' AND $discounttable.userid = '%d' and $discounttable.orderid <> 0";
            $dq = sprintf($q, $discountcode, $userid);
            $record = $this->db->query($dq, __FILE__, __LINE__);
            $arr_p['success'] = 1;
            $arr_p['message'] = "Discount Code Already Applied";
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $arr_p['success'] = 1;
                    $arr_p['message'] = "Discount Code Already Applied";
                }
            } else {
                $q = "SELECT * FROM discount WHERE code = '%s'";
                $dq = sprintf($q, $discountcode);
                $record = $this->db->query($dq, __FILE__, __LINE__);
                if ($this->db->num_rows($record) > 0) {
                    while ($row = $this->db->fetch_array($record)) {
                        $discountid = $row["discountid"];
                        $perc = $row["percentage"];
                        $Query = "INSERT INTO $discounttable (`discountid`,`userid`,`amount`,`createdon`) VALUES ( $discountid, $userid, $amount, '$date')";
                        $this->db->query($Query, __FILE__, __LINE__);
                        $lastid = $this->db->last_insert_id();
                        $arr_p['success'] = 0;
                        $arr_p['discountmanageid'] = $lastid;
                        $arr_p['message'] = "";
                        $arr_p['finalpayableamount'] = $amount / 100 * $perc;
                    }
                } else {
                    $arr_p['success'] = 1;
                    $arr_p['message'] = "Incorrect Discount Code";
                }
            }
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function gettimeslot($pickupdate) {
        $today = date('Y-m-d');
        $time = date('H:i');
        $arr_p = Array();
        try {
            $arr_p['success'] = 0;
            $arr_p['message'] = "";

            $arr_p['TimeSlot1']['slotid'] = 0;
            $arr_p['TimeSlot1']['status'] = 1;
            $arr_p['TimeSlot2']['slotid'] = 1;
            $arr_p['TimeSlot2']['status'] = 1;
            $arr_p['TimeSlot3']['slotid'] = 2;
            $arr_p['TimeSlot3']['status'] = 1;
            $arr_p['TimeSlot4']['slotid'] = 3;
            $arr_p['TimeSlot4']['status'] = 1;

            if (strtotime($pickupdate) > strtotime($today)) {
                $arr_p['TimeSlot1']['slotid'] = 0;
                $arr_p['TimeSlot1']['status'] = 0;
                $arr_p['TimeSlot2']['slotid'] = 1;
                $arr_p['TimeSlot2']['status'] = 0;
                $arr_p['TimeSlot3']['slotid'] = 2;
                $arr_p['TimeSlot3']['status'] = 0;
                $arr_p['TimeSlot4']['slotid'] = 3;
                $arr_p['TimeSlot4']['status'] = 0;
            } else if (strtotime($pickupdate) == strtotime($today)) {
                if ($time <= "10:30") {
                    $arr_p['TimeSlot1']['slotid'] = 0;
                    $arr_p['TimeSlot1']['status'] = 1;
                    $arr_p['TimeSlot2']['slotid'] = 1;
                    $arr_p['TimeSlot2']['status'] = 1;
                    $arr_p['TimeSlot3']['slotid'] = 2;
                    $arr_p['TimeSlot3']['status'] = 0;
                    $arr_p['TimeSlot4']['slotid'] = 3;
                    $arr_p['TimeSlot4']['status'] = 0;
                } elseif ($time < "13:30") {
                    $arr_p['TimeSlot1']['slotid'] = 0;
                    $arr_p['TimeSlot1']['status'] = 1;
                    $arr_p['TimeSlot2']['slotid'] = 1;
                    $arr_p['TimeSlot2']['status'] = 1;
                    $arr_p['TimeSlot3']['slotid'] = 2;
                    $arr_p['TimeSlot3']['status'] = 1;
                    $arr_p['TimeSlot4']['slotid'] = 3;
                    $arr_p['TimeSlot4']['status'] = 0;
                } elseif ($time < "16:30") {
                    $arr_p['TimeSlot1']['slotid'] = 0;
                    $arr_p['TimeSlot1']['status'] = 1;
                    $arr_p['TimeSlot2']['slotid'] = 1;
                    $arr_p['TimeSlot2']['status'] = 1;
                    $arr_p['TimeSlot3']['slotid'] = 2;
                    $arr_p['TimeSlot3']['status'] = 1;
                    $arr_p['TimeSlot4']['slotid'] = 3;
                    $arr_p['TimeSlot4']['status'] = 1;
                } elseif ($time < "19:30") {
                    $arr_p['TimeSlot1']['slotid'] = 0;
                    $arr_p['TimeSlot1']['status'] = 1;
                    $arr_p['TimeSlot2']['slotid'] = 1;
                    $arr_p['TimeSlot2']['status'] = 1;
                    $arr_p['TimeSlot3']['slotid'] = 2;
                    $arr_p['TimeSlot3']['status'] = 1;
                    $arr_p['TimeSlot4']['slotid'] = 3;
                    $arr_p['TimeSlot4']['status'] = 1;
                }
            }
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function orderrequest($shipping, $fromaddressid, $toaddressid, $pickupdate, $slotid, $paymentmodeid, $userid, $discountmanageid = null, $pickupboyid = null) {
        $orderimage = '';
        $pickupboyid = $pickupboyid != null ? $pickupboyid : '';
        $date = date('Y-m-d H:i:s');
        $pickupdate = DateTime::createFromFormat('d/m/Y', $pickupdate)->format('Y-m-d');
        $arr_p = Array();
        try {
            $trackingstatusid = 0;
            $Query = "INSERT INTO orderrequest (`fromaddressid`,`toaddressid`,`pickupdate`,`slotid`, `paymentmodeid`, `trackingstatusid`, `addedon`, `updatedon`, `userid`) "
                    . "VALUES ( '$fromaddressid', '$toaddressid', '$pickupdate', '$slotid', $paymentmodeid, $trackingstatusid, '$date', '$date', $userid)";
            $this->db->query($Query, __FILE__, __LINE__);

            $orderid = $this->db->last_insert_id();

            $Query = "INSERT INTO orderrequesthistory (`orderid`,`pickupboyid`,`fromaddressid`,`toaddressid`,`pickupdate`,`slotid`, `paymentmodeid`, `trackingstatusid`, `addedon`, `updatedon`, `userid`,`inserteddatetime`) "
                    . "VALUES ($orderid, '$pickupboyid', '$fromaddressid', '$toaddressid', '$pickupdate', '$slotid', $paymentmodeid, $trackingstatusid, '$date', '$date', $userid, '$date')";
            $this->db->query($Query, __FILE__, __LINE__);

            if (isset($discountmanageid) && $discountmanageid != 0) {
                $Query = "update orderdiscountmanage SET orderid = %d WHERE dmid = %d";
                $SQL = sprintf($Query, $orderid, $discountmanageid);
                $this->db->query($SQL, __FILE__, __LINE__);
            }

            if (isset($shipping)) {
                foreach ($shipping as $t) {
                    $orderimage = base64_encode($t->orderimage);
                    $Query = "INSERT INTO shippingdetails (`orderid`,`userid`,`itemname`,`itemid`, `shipmentvalue`, `weightid`, `vehicletypeid`, `packingrequired`, `insureshipment`,`orderimage`,`addedon`, `updatedon`) "
                            . "VALUES ( $orderid, '$userid', '$t->itemname', '$t->itemid', '$t->shipmentvalue', '$t->weightid', '$t->vehicletypeid', '$t->packingrequred', '$t->insureshipment','$orderimage', '$date', '$date')";
                    $this->db->query($Query, __FILE__, __LINE__);
                }
            }

            $strtimeslot = '';
            if (isset($slotid)) {
                $Query = "SELECT timeslot FROM timeslot WHERE timeslotid = " . $slotid;
                $record = $this->db->query($Query, __FILE__, __LINE__);
                if ($this->db->num_rows($record) > 0) {
                    while ($row = $this->db->fetch_array($record)) {
                        $strtimeslot = $row['timeslot'];
                    }
                }
            }

            $pickupdate = DateTime::createFromFormat('Y-m-d', $pickupdate)->format('d-m-Y');
            $arr_p['success'] = 0;
            $arr_p['request_number'] = $orderid;
            $arr_p['request_date'] = $pickupdate . ' / ' . $strtimeslot;
            $arr_p['image'] = $orderimage;
            $arr_p['message'] = "";
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function calcestimate($userid, $fromaddressid, $toaddressid, $vehicletypeid, $weightid, $packingrequired, $insurancerequired, $totalvalue) {
        $date = date('Y-m-d H:i:s');
        $arr_p = Array();
        try {
            $cost = 0;
            if ($vehicletypeid == 0 && $weightid == 0) {
                $cost += 150;
                if ($packingrequired == 1) {
                    $cost+=50;
                }
                if ($insurancerequired == 1) {
                    $cost+= (2 / 100 * $totalvalue);
                }
            }

            if ($vehicletypeid == 0 && $weightid == 1) {
                $cost += 200;
                if ($packingrequired == 1) {
                    $cost+=70;
                }
                if ($insurancerequired == 1) {
                    $cost+= (2 / 100 * $totalvalue);
                }
            }

            if ($vehicletypeid == 0 && $weightid == 2) {
                $cost += 250;
                if ($packingrequired == 1) {
                    $cost+=100;
                }
                if ($insurancerequired == 1) {
                    $cost+= (2 / 100 * $totalvalue);
                }
            }

            if ($vehicletypeid == 0 && $weightid == 3) {
                $cost += 300;
                if ($packingrequired == 1) {
                    $cost+=150;
                }
                if ($insurancerequired == 1) {
                    $cost+= (2 / 100 * $totalvalue);
                }
            }

            if ($vehicletypeid == 0 && $weightid == 4) {
                $cost += 500;
                if ($packingrequired == 1) {
                    $cost+=200;
                }
                if ($insurancerequired == 1) {
                    $cost+= (2 / 100 * $totalvalue);
                }
            }

            if ($vehicletypeid == 1 && $weightid == 4) {
                $cost += 1300;
                if ($packingrequired == 1) {
                    $cost+=300;
                }
                if ($insurancerequired == 1) {
                    $cost+= (2 / 100 * $totalvalue);
                }
            }

            if ($vehicletypeid == 1 && $weightid == 5) {
                $cost += 1600;
                if ($packingrequired == 1) {
                    $cost+=400;
                }
                if ($insurancerequired == 1) {
                    $cost+= (2 / 100 * $totalvalue);
                }
            }

            if ($vehicletypeid == 2 && $weightid == 5) {
                $cost += 2000;
                if ($packingrequired == 1) {
                    $cost+=1000;
                }
                if ($insurancerequired == 1) {
                    $cost+= (2 / 100 * $totalvalue);
                }
            }


            $Query = "INSERT INTO estimate (`fromaddressid`,`toaddressid`,`vehicletypeid`,`weightid`, `packingrequired`, `insurancerequired`, `addedon`, `userid`, `estimate`) VALUES ( '$fromaddressid', '$toaddressid', '$vehicletypeid', '$weightid', $packingrequired, '$insurancerequired', '$date', $userid, '$cost')";
            $this->db->query($Query, __FILE__, __LINE__);
            $lastid = $this->db->last_insert_id();

            $arr_p['success'] = 0;
            $arr_p['message'] = "";
            $arr_p['estimate'] = $cost;
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function login($username, $password, $oauthuserid, $gcmid, $platform) {
        try {
            $date = date('Y-m-d H:i:s');
            $arr_p = Array();
            $success = false;
            //$arr_p["data"];
            $i = array();
            $arr_p['success'] = 1;
            $arr_p['message'] = "Issue with Login";
            // $q = "SELECT * FROM user WHERE email = '%s' AND (password = '%s' OR (oauthuserid = '%s' AND oauthuserid<>NULL )) and approved = 1";
            // $dq = sprintf($q, $username, $password, $oauthuserid);
            // $record = $this->db->query($dq, __FILE__, __LINE__);
            // Prepare parameters
            $sp_params = "'" . $username . "'"
                    . ",'" . $password . "'"
                    . ",'" . $date . "'"
                    . ",'" . $oauthuserid . "'"
                    . ",'" . $gcmid . "'"
                    . "," . "@usertype" . ""
                    . "," . "@useridparam";
            $query = $this->PrepareSP(SP_CHECKLOGINREQUEST, $sp_params);
            $record = $this->db->query($query, __FILE__, __LINE__);
            $this->db->next_result();

            $query1 = "select @usertype as usertype, @useridparam as useridparam";
            $record1 = $this->db->query($query1, __FILE__, __LINE__);


            while ($row = $this->db->fetch_array($record1)) {
                $useridparam = $row['useridparam'];
                $usertype = $row['usertype'];
            }
            if ($usertype == 1) {
                $arr_p['success'] = 0;
                $arr_p['message'] = "Change password";
                $arr_p['userid'] = $useridparam;
            } else if ($useridparam == NULL && $usertype == 0) {
                $arr_p['success'] = 1;
                $arr_p['message'] = "Invalid Login Details";
            } else if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $success = true;
                    $arr_p['success'] = 0;
                    $arr_p['message'] = "";
                    $i["userid"] = $row["userid"];
                    $userid = $row["userid"];
                    $i["name"] = $row["name"];
                    $i["email"] = $row["email"];
                    $i["phone"] = $row["phone"];
                    $arr_p["User"] = $i;
                }

                if ($success == true) {
                    $q = "SELECT * FROM address WHERE userid = '%s'";
                    $dq = sprintf($q, $userid);
                    $record = $this->db->query($dq, __FILE__, __LINE__);
                    $i = Array();
                    if ($this->db->num_rows($record) > 0) {
                        while ($row = $this->db->fetch_array($record)) {
                            $i["addressid"] = $row["androidaddressid"];
                            $i["name"] = $row["name"];
                            $i["street"] = $row["street"];
                            $i["address"] = $row["address"];
                            $i["cityid"] = $row["cityid"];
                            $i["stateid"] = $row["stateid"];
                            $i["pincode"] = $row["pincode"];
                            $i["phone"] = $row["phone"];
                            $i["addresstypeid"] = $row["addresstypeid"];
                            $i["addressname"] = $row["addressname"];
                            $arr_p["Address"][] = $i;
                        }
                    }

                    $q = "SELECT * FROM state";
                    $record = $this->db->query($q, __FILE__, __LINE__);
                    $i = Array();
                    if ($this->db->num_rows($record) > 0) {
                        while ($row = $this->db->fetch_array($record)) {
                            $i["stateid"] = $row["stateid"];
                            $i["name"] = $row["name"];
                            $arr_p["States"] = $i;
                        }
                    }

                    $q = "SELECT * FROM city";
                    $record = $this->db->query($q, __FILE__, __LINE__);
                    $i = Array();
                    if ($this->db->num_rows($record) > 0) {
                        while ($row = $this->db->fetch_array($record)) {
                            $i["cityid"] = $row["cityid"];
                            $i["name"] = $row["name"];
                            $i["stateid"] = $row["stateid"];
                            $arr_p["Cities"] = $i;
                        }
                    }

                    $q = "SELECT * FROM ecommerce";
                    $record = $this->db->query($q, __FILE__, __LINE__);
                    $i = Array();
                    if ($this->db->num_rows($record) > 0) {
                        while ($row = $this->db->fetch_array($record)) {
                            $i["ecommerceid"] = $row["ecommerceid"];
                            $i["name"] = $row["name"];
                            $arr_p["EcommercePartners"] = $i;
                        }
                    }

                    $q = "SELECT * FROM pincodes";
                    $record = $this->db->query($q, __FILE__, __LINE__);
                    $i = Array();
                    if ($this->db->num_rows($record) > 0) {
                        while ($row = $this->db->fetch_array($record)) {
                            $i["pincodeid"] = $row["pincodeid"];
                            $i["stateid"] = $row["stateid"];
                            $i["pincode"] = $row["pincode"];
                            $arr_p["PinCodes"][] = $i;
                        }
                    }

                    $q = "SELECT * FROM item";
                    $record = $this->db->query($q, __FILE__, __LINE__);
                    $i = Array();
                    if ($this->db->num_rows($record) > 0) {
                        while ($row = $this->db->fetch_array($record)) {
                            $i["itemid"] = $row["itemid"];
                            $i["name"] = $row["name"];
                            $arr_p["Items"][] = $i;
                        }
                    }

                    $q = "SELECT *, o.orderid, addfrom.pincode as frompincode, addfrom.name as fromname,
                    addto.pincode as topincode
                    FROM orderrequest as o
                    left outer join orderdiscountmanage as dm on dm.orderid = o.orderid
                    left outer join discount on discount.discountid = dm.discountid
                    left outer join address as addfrom on addfrom.androidaddressid = o.fromaddressid
                    left outer join address as addto on addto.androidaddressid = o.toaddressid
                    WHERE o.userid = '%s'";
                    $dq = sprintf($q, $userid);
                    $record = $this->db->query($dq, __FILE__, __LINE__);
                    /* To be removed once we get logic for AWB no */
                    $numOfRows = floor($this->db->num_rows($record));
                    $perStatusCount = floor($numOfRows / 3);
                    $counter = 0;

                    $i = Array();
                    if ($this->db->num_rows($record) > 0) {
                        while ($row = $this->db->fetch_array($record)) {
                            $i["orderid"] = $row['orderid'];
                            $i["fromaddressid"] = $row["fromaddressid"];
                            $i["frompincode"] = $row["frompincode"];
                            $i["toaddressid"] = $row["toaddressid"];
                            $i["topincode"] = $row["topincode"];
                            $i["pickupdate"] = $row["pickupdate"];
                            $i["slotid"] = $row["slotid"];
                            $i["paymentmodeid"] = $row["paymentmodeid"];
                            $i["addedon"] = $row["addedon"];
                            $i["updatedon"] = $row["updatedon"];
                            $i["userid"] = $row["userid"];
                            $i["itemlist"] = $this->getshippingdetails($row["orderid"], $row["userid"]);
                            $i["totalamount"] = $this->getTotalAmount($row["fromaddressid"], $row["toaddressid"]);
                            $i["discountcode"] = $row['code'];
                            $i["finalamount"] = $this->getFinalAmount($row['orderid'], $row['code'], $i["totalamount"]);
                            //$arr_p["AllPastOrderDetails"][] = $i;
                            $j['userid'] = $userid;
                            $j['orderid'] = $row["orderid"];
                            $j['AirwayBillNo'] = $row["AWBno"];
                            if ($counter <= $perStatusCount) {
                                $j['status'] = "1";
                            } else if ($counter > $perStatusCount && $counter <= $numOfRows - $perStatusCount) {
                                $j['status'] = "0";
                            } else {
                                $j['status'] = "2";
                            }
                            $arr_p["MyPickups"][] = $j;
                            $counter++;
                        }
                    }
                } else {
                    $arr_p['success'] = 1;
                    $arr_p['message'] = "Invalid Login Details";
                }
            } else {
                $arr_p['success'] = 1;
                $arr_p['message'] = "Invalid Login Details";
            }
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception has occured";
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    // Pickup Notification API
    function pickupNotification($userid) {
        $date = date('Y-m-d H:i:s');
        $arr_p = Array();
        $success = false;

        $q = "SELECT *,o.userid,p.pickupboyname,pickupboyphoto
                    FROM orderrequest as o
                    left join address on address.androidaddressid = o.toaddressid
                    left join pickupboy as p on p.pid = o.pickupboyid
                    WHERE o.userid = '%s' limit 2";
        $dq = sprintf($q, $userid);
        $record = $this->db->query($dq, __FILE__, __LINE__);
        $numOfRows = $this->db->num_rows($record);
        $i = Array();
        if ($numOfRows > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $i["OrderId"] = $row['orderid'];
                $i["AirwayBillNo"] = $row['AWBno'];
                $i["PhoneNo"] = $row['phone'];
                $i["Message"] = '';
                $i["PickupMarshalName"] = $row['pickupboyname'];
                $i["PickupMarshalPhoto"] = $row['pickupboyphoto'];
                $arr_p["Notifications"][] = $i;
            }
        } else {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Notifications Not Available";
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    // Pickups API
    function pickups($userid) {
        $date = date('Y-m-d H:i:s');
        $arr_p = Array();
        $success = false;
        try {
            $q = "SELECT *,o.userid
                    FROM orderrequest as o
                    left join address on address.androidaddressid = o.toaddressid
                    WHERE o.userid = '%s'";
            $dq = sprintf($q, $userid);
            $record = $this->db->query($dq, __FILE__, __LINE__);
            $numOfRows = floor($this->db->num_rows($record));
            $i = Array();
            if ($numOfRows > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $i["OrderId"] = $row['orderid'];
                    $i["AirwayBillNo"] = $row['AWBno'];
                    $trackingStatus = $row['trackingstatusid'];
                    /*
                      Status for Pickup:
                      0 -Pending
                      1 -Completed
                      2- Failed
                     */
                    $i["status"] = ($trackingStatus >= 0 && $trackingStatus < 4) ? "0" : ($trackingStatus == 4) ? "1" : "2";
                    $arr_p["Notifications"][] = $i;
                }
                $arr_p['success'] = 0;
            } else {
                $arr_p['success'] = 1;
                $arr_p['message'] = "Pickups Not Available";
            }
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    // OrderDetails API
    function orderDetails($userid, $orderid) {
        $date = date('Y-m-d H:i:s');
        $arr_p = Array();
        $success = false;
        try {
            $arr_p['success'] = 0;
            $q = "SELECT *, o.orderid, addfrom.pincode as frompincode, addfrom.name as fromname,
                    addfrom.street as fromstreet, addfrom.address as fromaddress, addfrom.pincode as frompincode,
                    addfrom.phone as fromphone, frompins.pincode as frompincodedetail, topins.pincode as topincodedetail,
                    addto.pincode as topincode, addto.name as toname,
                    addto.street as tostreet, addto.address as toaddress, addto.pincode as topincode,
                    addto.phone as tophone, cityfrom.name as cityfromname, cityto.name as citytoname,
                    dm.amount as discountmanage, o.fromaddressid as fromaddress_id, o.toaddressid as toaddress_id
                    FROM orderrequest as o
                    left outer join orderdiscountmanage as dm on dm.orderid = o.orderid
                    left outer join discount on discount.discountid = dm.discountid
                    left outer join address as addfrom on addfrom.androidaddressid = o.fromaddressid
                    left outer join address as addto on addto.androidaddressid = o.toaddressid
                    left outer join city as cityfrom on cityfrom.cityid = addfrom.cityid
                    left outer join city as cityto on cityto.cityid = addto.cityid
                    left outer join pincodes as frompins on frompins.pincodeid=addfrom.pincode
                    left outer join pincodes as topins on topins.pincodeid=addto.pincode
                    WHERE o.userid = $userid and o.orderid = $orderid";
            //$dq = sprintf($q, $userid, $orderid);
            $record = $this->db->query($q, __FILE__, __LINE__);
            /* To be removed once we get logic for AWB no */
            $i = Array();
            if ($this->db->num_rows($record) > 0) {
                while ($row = $this->db->fetch_array($record)) {
                    $i["OrderId"] = $row['orderid'];
                    $i["AirwayBillNo"] = $row['AWBno'];
                    $shippingDetails = $this->getshippingdetails($row["orderid"], $userid);
                    $i["ItemNames"] = '';
                    $i["ItemImages"] = '';
                    if (isset($shippingDetails)) {
                        foreach ($shippingDetails as $arrItemDetails) {
                            foreach ($arrItemDetails as $itemDetail) {
                                $listitem[] = $itemDetail['itemname'];
                                $listphoto[] = str_replace("\n", "", $itemDetail['orderimage']);
                            }
                        }
                        if (isset($listitem)) {
                            $list = implode(',', $listitem);
                            $i["ItemNames"] = $list;
                        }
                        if (isset($listitem)) {
                            $i["ItemImages"] = $listphoto;
                        }
                    }
                    $addressFrom = $row["fromname"] . ", " . $row["fromstreet"] . "," . $row["fromaddress"] . "," . $row["frompincode"] . "," . $row["cityfromname"];
                    $addressTo = $row["toname"] . ", " . $row["tostreet"] . "," . $row["toaddress"] . "," . $row["topincode"] . "," . $row["citytoname"];

                    $i["FromAddressPincode"] = $row["frompincodedetail"];
                    $i["ToAddressPincode"] = $row["topincodedetail"];
                    $i["FromAddressCombined"] = $addressFrom;
                    $i["ToAddressCombined"] = $addressTo;
                    $i["TotalAmount"] = $this->getTotalAmount($row["fromaddress_id"], $row["toaddress_id"]);
                    $i["DiscountCode"] = $row['code'];
                    $i["FinalPayableAmount"] = $this->getFinalAmount($row['discountmanage'], $i["TotalAmount"]);

                    //$listitem = '';
                    $arr_p['OrderDetail'] = $i;
                }
            } else {
                $arr_p['success'] = 1;
                $arr_p['message'] = "Order Not Available";
            }
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function returnOrder($returnitemdetails, $fromaddressid, $pickupdate, $slotid, $paymentmodeid, $userid) {
        $date = date('Y-m-d H:i:s');
        $arr_p = Array();
        try {
            $trackingstatusid = 1;
            $Query = "INSERT INTO orderreturn (`fromaddressid`,`pickupdate`,`slotid`, `paymentmodeid`,  `addedon`, `updatedon`, `userid`) "
                    . "VALUES ( '$fromaddressid', '$pickupdate', '$slotid', $paymentmodeid, '$date', '$date', $userid)";
            $this->db->query($Query, __FILE__, __LINE__);
            $orderid = $this->db->last_insert_id();

            $Query = "INSERT INTO orderreturnhistory (`orderid`,`fromaddressid`,`pickupdate`,`slotid`, `paymentmodeid`, `addedon`, `updatedon`, `userid`) "
                    . "VALUES ($orderid, '$fromaddressid', '$pickupdate', '$slotid', $paymentmodeid, '$date', '$date', $userid)";
            $this->db->query($Query, __FILE__, __LINE__);

            foreach ($returnitemdetails as $t) {
                $Query = "INSERT INTO returnitemdetails (`orderreturnid`,`ecommercepartnerid`,`invoiceno`,`reason`, `returnitemimage`,`addedon`, `updatedon`) "
                        . "VALUES ( $orderid, '$t->ecommercepartnerid', '$t->invoiceno', '$t->reason', '$t->returnitemimage', '$date', '$date')";
                $this->db->query($Query, __FILE__, __LINE__);
            }

            $arr_p['success'] = 0;
            $arr_p['request_number'] = $orderid;
            $arr_p['request_date'] = $date;
            $arr_p['message'] = "";
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function userclear() {
        $arr_p = Array();
        /*
          $Query = "TRUNCATE TABLE `address`;
          TRUNCATE TABLE `estimate`;
          TRUNCATE TABLE `orderdiscountmanage`;
          TRUNCATE TABLE `orderrequest`;
          TRUNCATE TABLE `orderrequesthistory`;
          TRUNCATE TABLE `orderreturn`;
          TRUNCATE TABLE `orderreturnhistory`;
          TRUNCATE TABLE `returnitemdetails`;
          TRUNCATE TABLE `shippingdetails`;
          TRUNCATE TABLE `user`;";
         */
        $Query = "TRUNCATE TABLE `user`;";
        $this->db->query($Query, __FILE__, __LINE__);
        $arr_p['success'] = 0;
        $arr_p['message'] = "";
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function trackOrder($awbno, $trackingtypeid) {
        $arr_p = array();
        $userid = 0;
        try {
            $tableName = '';
            //shipped product
            if ($trackingtypeid == 1) {
                $tableName = "orderrequesthistory";
            }
            //returned product
            else if ($trackingtypeid == 2) {
                //get the data from return orders history table which needs to be created.
                $tableName = "orderreturnhistory";
            }
            $orderid = $this->getorderid($awbno, $tableName);
            if ($orderid != 0) {
                if ($tableName != '') {

                    $q = "SELECT userid
                        , orderid
                        , ts.trackingstatusid
                        , tbl.trackingstatusid as ordertrackingstatus
                        , addedon
                  FROM   trackingstatus as ts
                  LEFT OUTER JOIN $tableName as tbl ON tbl.trackingstatusid = ts.trackingstatusid
                  AND orderid = $orderid
                  ORDER BY ts.tracking_sequence DESC";
// Query changes for display only last addedon desc distinct rows
//                    $q="SELECT tbl.userid
//                        , tbl.orderid
//                        , ts.`trackingstatusid`
//                        , tbl.trackingstatusid as ordertrackingstatus
//                        , tbl.addedon
//                    FROM trackingstatus as ts
//                    LEFT OUTER JOIN $tableName as tbl ON tbl.trackingstatusid = ts.trackingstatusid
//                            AND tbl.orderid = $orderid
//                    GROUP BY ts.`trackingstatusid`
//                    ORDER BY tbl.addedon DESC";

                    $record = $this->db->query($q, __FILE__, __LINE__);
                    $arrTrackingHistory = array();
                    if ($this->db->num_rows($record) > 0) {
                        //bit to check whether the previous tracking stage is currently active stage
                        $isPrevStageActive = 0;
                        while ($row = $this->db->fetch_array($record)) {
                            if ($userid == 0) {
                                $userid = $row['userid'];
                            }
                            $trackingDetails = new Object();
                            $trackingDetails->TrackingTypeId = $trackingtypeid;
                            $trackingDetails->TrackingStatusId = $row['trackingstatusid'];
                            $orderTrackingStatusId = isset($row['ordertrackingstatus']) ? $row['ordertrackingstatus'] : '';

                            $trackingDetails->Timestamp = isset($row['addedon']) ? $row['addedon'] : '';
                            /*
                              StageActiveStatus = 0 means the particular tracking stage has not been started
                              StageActiveStatus = 1 means the particular tracking stage has been started
                              StageActiveStatus = 2 means the particular tracking stage has been completed
                              StageActiveStatus = 3 means the particular tracking stage has been Undelivered
                             */

                            if ($isPrevStageActive == 1) {
                                $trackingDetails->StageActiveStatus = 2;
                            } else {
                                $trackingDetails->StageActiveStatus = ($orderTrackingStatusId == '') ? 0 : 1;
                                if ($trackingDetails->StageActiveStatus == 1 && $isPrevStageActive == 0) {
                                    $isPrevStageActive = 1;
                                }
                            }
//                            if ($orderTrackingStatusId == 5) {
//                                $trackingDetails->TrackingStatusId = 4;
//                                $trackingDetails->StageActiveStatus = 3;
//                            }

                            $arrTrackingHistory[] = $trackingDetails;
                        }

                    }
                    $arrPickupMarshal = array();
                    $getPickupBoyDetailsQuery = "select p.phoneno"
                            . " ,p.pickupboyphoto "
                            . " ,su.realname "
                            . " from orderrequest as oreq"
                            . " inner join user as u on u.userid = oreq.userid AND u.approved =1 "
                            . " left join pickupboy as p on p.userid = oreq.pickupboyid "
                            . " left join " . SPEEDDB . ".user as su on su.userid = oreq.pickupboyid "
                            . " where oreq.orderid= $orderid "
                            . " AND oreq.isdeleted=0 ";
                    $record = $this->db->query($getPickupBoyDetailsQuery, __FILE__, __LINE__);
                    while ($row = $this->db->fetch_array($record)) {
                        $pickupBoyDetails = new Object();
                        $pickupBoyDetails->name = $row['realname'];
                        $pickupBoyDetails->mobileno = $row['phoneno'];
                        $pickupBoyDetails->photo = $row['pickupboyphoto'];
                        $arrPickupMarshal[] = $pickupBoyDetails;
                    }
                    //Create response
                    $arr_p['success'] = 0;
                    $arr_p['OrderId'] = $orderid;
                    $arr_p['AirwayBillNo'] = $awbno;
                    $arr_p['TrackingStatus'] = $arrTrackingHistory;
                    $arr_p['PickupMarshal'] = $arrPickupMarshal;
                } else {
                    //Create response
                    $arr_p['success'] = 1;
                    $arr_p['message'] = "Tracking Details Not Available";
                }
            } else {
                $arr_p['success'] = 1;
                $arr_p['message'] = "Unable to find the details for the passed AWB no";
            }
        } catch (Exception $ex) {
            $arr_p['success'] = 1;
            $arr_p['message'] = "Exception occured " . $ex->message;
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    // forgot password - Update new password
    function forgot_newpassword_req_old($newpassword, $userid) {
        $arr_p = Array();
        if (!empty($newpassword)) {
            $sql = "update user set password ='" . $newpassword . "' where userid =" . $userid;
            $record = $this->db->query($sql, __FILE__, __LINE__);

            $sql1 = "update forgot_password_request set isused =1 where userid =" . $userid;
            $record1 = $this->db->query($sql1, __FILE__, __LINE__);
            $arr_p['success'] = 1;
            $arr_p['message'] = " Password updated sucessfully. Please login again with New Password";
        } else {
            $arr_p['success'] = 1;
            $arr_p['message'] = " new password should not be blank";
        }

        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function forgot_newpassword_req($newpassword, $userid) {
        $arr_p = Array();
        $date = date('Y-m-d H:i:s');
        if (!empty($newpassword)) {
            $sql1 = "CALL update_newforgotpassword ('" . $newpassword . "'," . $userid . ",'" . $date . "')";
            $record1 = $this->db->query($sql1, __FILE__, __LINE__);
            if ($record1 == TRUE) {
                $arr_p['success'] = 1;
                $arr_p['message'] = " Password updated sucessfully. Please login again with New Password";
            } else {
                $arr_p['success'] = 1;
                $arr_p['message'] = "unable to update password";
            }
        } else {
            $arr_p['success'] = 1;
            $arr_p['message'] = " new password should not be blank";
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function generate_forgot_otp($registeredemail) {
        $date = date('Y-m-d H:i:s');
        $otp = '';
        $arr_p = Array();
        $arr_p['success'] = 1;
        $sql = "select userid,phone,email,oauthuserid "
                . "from user "
                . " WHERE email ='" . $registeredemail . "'"
                . " AND approved = 1 "
                . " AND isdeleted = 0 ";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        if ($this->db->num_rows($record) > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $userid = $row['userid'];
                $phone = $row['phone'];
                $email = $row['email'];
                $oauthuserid = $row['oauthuserid'];
            }
            if ($oauthuserid == '') {
                $sqlCallSP = "CALL wow_insert_forgot_password_request (" . $userid . ",'" . $date . "',@otp,@otpvaliduptoparam)";
                $this->db->query($sqlCallSP, __FILE__, __LINE__);

                $sqlOutputParams = "SELECT @otp as otpno, @otpvaliduptoparam as validotpdate";
                $outputParamsRecord = $this->db->query($sqlOutputParams, __FILE__, __LINE__);
                if ($this->db->num_rows($outputParamsRecord) > 0) {
                    while ($row = $this->db->fetch_array($outputParamsRecord)) {
                        $otp = $row['otpno'];
                        $validotpdate = $row['validotpdate'];
                    }
                    if ($otp == -1) {
                        $arr_p['message'] = "Your otp request limit exceeded today.";
                    } else {
                        $statusMessage = '';
                        $message = api::$FORGOT_PWD_MSG;
                        $message = str_replace("{{OTP}}", $otp, $message);
                        $message = str_replace("{{VALIDUPTO_DATE}}", date('d-M-Y h:i:s A', strtotime($validotpdate)), $message);

                        if ($phone != "") {
                            $smsText = '';
                            $smsText = $message;
                            $response = '';
                            $isSMSSent = $this->sendSMS($phone, $smsText, $response);
                            if (is_array($phone)) {
                                foreach ($phone as $phoneno) {
                                    $smsLogId = $this->insertSMSLog($phoneno, $smsText, $response, $userid, $isSMSSent, $date);
                                }
                            } else {
                                $smsLogId = $this->insertSMSLog($phone, $smsText, $response, $userid, $isSMSSent, $date);
                            }
                            $statusMessage = "Temporary password SMS sent successfully.";
                        } else {
                            $statusMessage = "Unable to send SMS.";
                        }

                        if ($email != "") {
                            $subject = "WOW Express Forgot Password";
                            $content = $message;
                            $headers = "From: noreply@wowexpress.in\r\n";
                            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                            $isEmailSent = @mail($email, $subject, $content, $headers);
                            if ($isEmailSent) {
                                $emailMessage = "Temporary password Email sent successfully";
                                $statusMessage = $statusMessage != '' ? $statusMessage . ", " . $emailMessage : $emailMessage;
                            } else {
                                $emailMessage = "Temporary password Email sending failed";
                                $statusMessage = $statusMessage != '' ? $statusMessage . ", " . $emailMessage : $emailMessage;
                            }
                        }
                        $arr_p['success'] = "0";
                        $arr_p['message'] = $statusMessage;
                    }
                }
            } else {
                $arr_p['message'] = "This emailid is registered using 3rd party apps like facebook";
            }
        } else {
            $arr_p['message'] = "This emailid not exists";
        }

        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Helper functions">

    function sendSMS($phoneArray, $message, &$response) {
        $isSMSSent = 0;
        $countryCode = "91";
        $arrPhone = array();
        if (is_array($phoneArray)) {
            foreach ($phoneArray as $phone) {
                if (preg_match('/^\d{10}$/', $phone)) {
                    $arrPhone[] = $countryCode . $phone;
                }
            }
        } else {
            $arrPhone[] = $countryCode . $phoneArray;
        }
        $phone = implode(",", $arrPhone);
        $url = str_replace("{{PHONENO}}", urlencode($phone), SMS_URL);
        $url = str_replace("{{MESSAGETEXT}}", urlencode($message), $url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        if ($response === false) {
            //echo 'Curl error: ' . curl_error($ch);
            $isSMSSent = 0;
        } else {
            $isSMSSent = 1;
        }
        curl_close($ch);
        return $isSMSSent;
    }

    function getshippingdetails($orderid, $userid) {
        $arr_p = array();
        $q = "SELECT * FROM shippingdetails WHERE orderid=%d and userid = %d";
        $dq = sprintf($q, $orderid, $userid);
        $record = $this->db->query($dq, __FILE__, __LINE__);
        $i = Array();
        if ($this->db->num_rows($record) > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $i['id'] = $row['id'];
                $i['itemname'] = $row['itemname'];
                $i['itemid'] = $row['itemid'];
                $i['shipmentvalue'] = $row['shipmentvalue'];
                $i['weightid'] = $row['weightid'];
                $i['vehicletypeid'] = $row['vehicletypeid'];
                $i['packingrequired'] = $row['packingrequired'];
                $i['insureshipment'] = $row['insureshipment'];
                $i['orderimage'] = $row['orderimage'];

                $arr_p["item"][] = $i;
            }
        }
        return $arr_p;
    }

    function getTotalAmount($fromaddress, $toaddress) {
        $q = "SELECT * FROM estimate WHERE fromaddressid=%d and toaddressid = %d";
        $dq = sprintf($q, $fromaddress, $toaddress);
        $record = $this->db->query($dq, __FILE__, __LINE__);
        $i = Array();
        $row = $this->db->fetch_array($record);
        $total_amount = $row['estimate'];
        return $total_amount;
    }

    function getFinalAmount($discountamount, $totalAmount) {
        $finalAmount = $totalAmount - $discountamount;
        return $finalAmount;
    }

    function OrderPadding($orderid) {
        $input = $orderid;
        $value = str_pad($input, 4, "0", STR_PAD_LEFT);
        $orderpadding = "WN1" . $value;
        return $orderpadding;
    }

    function insertSMSLog($phone, $message, $response, $userid, $isSMSSent, $todaysdate) {
        $smsid = 0;
        $pdo = $this->db->CreatePDOConn();
        $sp_params = "'" . $phone . "'"
                . ",'" . $message . "'"
                . ",'" . $response . "'"
                . ",'" . $userid . "'"
                . ",'" . $isSMSSent . "'"
                . ",'" . $todaysdate . "'"
                . "," . '@smsid';

        $queryCallSP = "CALL " . SP_INSERT_SMSLOG . "($sp_params)";
        $record1 = $this->db->query($queryCallSP, __FILE__, __LINE__);

        $outputParamsQuery = "SELECT @smsid AS smsid";
        $outputResult = $this->db->query($outputParamsQuery, __FILE__, __LINE__);

        if ($this->db->num_rows($outputResult) > 0) {
            while ($row = $this->db->fetch_array($outputResult)) {

                $smsid = $row['smsid'];
            }
        }

        return $smsid;
    }

    function getorderid($awbno, $tableName) {
        $orderid = 0;
        $q = "SELECT orderid
                  FROM   $tableName
                  WHERE awbno = '$awbno'"
                . " AND isdeleted = 0";

        $record = $this->db->query($q, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        $orderid = $row['orderid'];
        return $orderid;
    }

    // </editor-fold>
    //
    // <editor-fold defaultstate="collapsed" desc="Unknown functions - Please verify and remove if not used">
    //find location
    function location($lat, $long, $customerno, $usegeolocation) { //OVER_QUERY_LIMIT
        $address = NULL;
        if ($lat != '0' && $long != '0') {
            if ($usegeolocation == 1) {
                $API = "http://www.speed.elixiatech.com/location.php?lat=" . $lat . "&long=" . $long . "";
                $location = json_decode(file_get_contents("$API&sensor=false"));
                if (isset($location) && $location->status != 'OVER_QUERY_LIMIT') {
                    @$address = "Near " . $location->results[0]->formatted_address;
                    if (isset($location->results[0]->formatted_address) && $location->results[0]->formatted_address == "") {
                        $GeoCoder_Obj = new GeoCoder($customerno);
                        $address = $GeoCoder_Obj->get_location_bylatlong($lat, $long);
                    }
                } else {
                    $address = $this->get_location_bylatlong($lat, $long, $customerno);
                }
            } else {
                $address = $this->get_location_bylatlong($lat, $long, $customerno);
            }
        }
        return $address;
    }

    public function get_location_bylatlong($lat, $long, $customerno) {
        $latint = floor($lat);
        $longint = floor($long);

        $geoloc_query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `lat` ) * PI( ) /180 /2 ) , 2 ) +
                         COS( " . $lat . " * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
                         AS distance FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " HAVING distance <2 AND customerno = " . $customerno . " ORDER BY distance LIMIT 0,1 ";
        $record = $this->db->query($geoloc_query, __FILE__, __LINE__);
        $record_counts = $this->db->query($geoloc_query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record_counts);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                if ($row['distance'] > 1) {
                    $location_string = round($row['distance'], 2) . " Km from " . $row['location'] . ", " . $row['city'] . ", " . $row['state'];
                } else {
                    $location_string = "Near " . $row['location'] . ", " . $row['city'] . ", " . $row['state'];
                }
            }
            return $location_string;
        } else {

            $geolocation_query = "SELECT * , 3956 *2 * ASIN( SQRT( POWER( SIN( ( " . $lat . "- `lat` ) * PI( ) /180 /2 ) , 2 ) +
                             COS( " . $lat . " * PI( ) /180 ) * COS( `lat` * PI( ) /180 ) * POWER( SIN( ( " . $long . " - `long` ) * PI( ) /180 /2 ) , 2 ) ) )
                             AS distance FROM geotest WHERE `latfloor` = " . $latint . " AND `longfloor` = " . $longint . " HAVING distance <10 AND customerno IN(0, " . $customerno . ") ORDER BY distance LIMIT 0,1 ";
            $records = $this->db->query($geolocation_query, __FILE__, __LINE__);
            $record_countss = $this->db->query($geolocation_query, __FILE__, __LINE__);
            $row_counts = $this->db->num_rows($record_countss);
            if ($row_counts > 0) {
                while ($row = $this->db->fetch_array($records)) {
                    if ($row['distance'] > 1) {
                        $location_string = round($row['distance'], 2) . " Km from " . $row['location'] . ", " . $row['city'] . ", " . $row['state'];
                    } else {
                        $location_string = "Near " . $row['location'] . ", " . $row['city'] . ", " . $row['state'];
                    }
                }
                return $location_string;
            } else {
                return "google temporarily down";
            }
            return null;
        }
    }

    //calculate distance
    function distance($customerno, $unitno) {
        $date = date('Y-m-d');
        $location = "../../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        $Data = $this->odometerfromSqlite($location);
        if ($Data != 0) {
            $lastodometer = $Data['last'];
            $firstodometer = $Data['first'];
            $distance = $lastodometer / 1000 - $firstodometer / 1000;
            $distancekm = round($distance, 2);
        } else {
            $distancekm = 0;
        }
        return $distancekm;

        //$location = "../../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
    }

    function odometerfromSqlite($location) {
        try {
            $DRMS = array();
            $DRMS['first'] = 0;
            $DRMS['last'] = 0;
            if (file_exists($location)) {
                $path = "sqlite:$location";
                $dbs = new PDO($path);
                //$query = "SELECT * from vehiclehistory ORDER BY vehiclehistory.lastupdated desc LIMIT 0,1";
                $Query = "SELECT (SELECT odometer FROM vehiclehistory ORDER BY odometer LIMIT 1) as 'first',(SELECT odometer FROM vehiclehistory ORDER BY odometer DESC LIMIT 1) as 'last'";
                $sobj = $dbs->prepare($Query);
                $sobj->execute();
                /* Fetch all of the remaining rows in the result set */

                $result = $sobj->fetchAll();
                $DRMS['first'] = $result[0]['first'];
                $DRMS['last'] = $result[0]['last'];
            }
        } catch (PDOException $e) {
            $e->getMessage();
        }
        return $DRMS;
    }

    // difference in time
    function getduration($EndTime, $StartTime) {
//                echo $EndTime.'_'.$StartTime.'<br>';
        $idleduration = strtotime($EndTime) - strtotime($StartTime);
        $years = floor($idleduration / (365 * 60 * 60 * 24));
        $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        if ($years > 0 || $months > 0) {
            $diff = date('Y-m-d', strtotime($StartTime));
        } else if ($days > 0) {
            $diff = $days . ' Days ' . $hours . ' hrs and ' . $minutes . ' mins';
        } else if ($hours > 0) {
            $diff = $hours . ' hrs and ' . $minutes . ' mins';
        } else {
            $diff = $minutes . ' mins';
        }
        return $diff;
    }

    public function checkforvalidity($customerno) {
        $devices = Array();
        $Query = "SELECT deviceid,expirydate, Now() as today FROM `devices` where customerno=%d";
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

    public function check_validity_login($expirydate, $currentdate) {
        date_default_timezone_set("Asia/Calcutta");
        $realtime = strtotime($currentdate);
        $expirytime = strtotime($expirydate);
        $diff = $expirytime - $realtime;
        return $diff;
    }

    // checks for login
    function check_login($username, $password) {
        $sql = "select *," . TBL_ADMIN_CUSTOMER . ".customerno as customer_no, (SELECT groupman.isdeleted FROM groupman WHERE groupman.userid = " . TBL_ADMIN_USER . ".userid
                            ORDER BY groupman.gmid DESC LIMIT 0 , 1) AS grpdel from " . TBL_ADMIN_USER . " INNER JOIN " . TBL_ADMIN_CUSTOMER . " ON " . TBL_ADMIN_CUSTOMER . ".customerno = " . TBL_ADMIN_USER . ".customerno INNER JOIN `android_version` where username='" . $username . "' AND isdeleted=0 limit 1";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        $retarray = array();
        if ($username == $row['username'] and $password == $row['password'] AND $row['grpdel'] != 1) {
            $devices = $this->checkforvalidity($row['customer_no']);
            $initday = 0;
            if (isset($devices)) {
                foreach ($devices as $thisdevice) {
                    $days = $this->check_validity_login($thisdevice->expirydate, $thisdevice->today);
                    if ($days > 0) {
                        $initday = $days;
                    }
                }
            }
            if ($initday > 0) {
                $retarray['status'] = "successful";
                $retarray['userkey'] = $row['userkey'];
                $this->update_push_android_chk($row['userkey'], 1);
                $this->update_push_android_chk_main($row['userkey'], 1);
                $retarray['customerno'] = $row['customer_no'];
                $retarray['username'] = $row['username'];
                $retarray['customername'] = $row['customercompany'];
                $retarray['version'] = $row['version'];
                $retarray['role'] = $row['role'];

                $today = date("Y-m-d H:i:s");
                $sql = "UPDATE user SET lastlogin_android='" . $today . "' where userkey = '" . $row['userkey'] . "' AND customerno= '" . $row['customer_no'] . "' LIMIT 1";
                $this->db->query($sql, __FILE__, __LINE__);
            } else {
                $retarray['status'] = "failure";
                $retarray['version'] = '';
                $retarray['customername'] = null;
                $retarray['userkey'] = 0;
            }
        } else {
            $retarray['status'] = "failure";
            $retarray['version'] = '';
            $retarray['customername'] = null;
            $retarray['userkey'] = 0;
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

    function gettemp($rawtemp) {
        $temp = round((($rawtemp - 1150) / 4.45), 2);
        return $temp;
    }

    function pull_groups($userkey) {
        $validation = $this->check_userkey($userkey);
        if ($validation['status'] == "successful") {
            // successful
            $customerno = $validation["customerno"];

            $groupids = array();

            $groupidsql = "select group.groupid,group.groupname from `group`
            INNER JOIN groupman ON groupman.groupid = group.groupid
            INNER JOIN user ON user.userid = groupman.userid
            where user.userkey=$userkey AND group.isdeleted=0 AND groupman.isdeleted=0 order by group.groupname ASC";
            $recordgrp = $this->db->query($groupidsql, __FILE__, __LINE__);
            while ($rowgrp = $this->db->fetch_array($recordgrp)) {
                $group = new VODevices();
                $group->groupid = $rowgrp['groupid'];
                $group->groupname = $rowgrp['groupname'];
                $groupids[] = $group;
            }
            if (!isset($groupids) || count($groupids) == 0) {
                $group = new VODevices();
                $group->groupid = 0;
                $group->groupname = "All Groups";
                $groupids[] = $group;

                $Query = "SELECT groupid,groupname FROM `group` where customerno=$customerno AND isdeleted=0 order by groupname ASC";
                $recordgrp = $this->db->query($Query, __FILE__, __LINE__);
                while ($rowgrp = $this->db->fetch_array($recordgrp)) {
                    $group = new VODevices();
                    $group->groupid = $rowgrp['groupid'];
                    $group->groupname = $rowgrp['groupname'];
                    $groupids[] = $group;
                }
            }

            return $groupids;
        }
    }

    function device_list($userkey) {

        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";

        if ($validation['status'] == "successful") {
            // successful
            $customerno = $validation["customerno"];
            $arr_p['groups'] = $this->pull_groups($userkey);
            $arr_p['checkpoints_count'] = $this->get_checkpoint_customer_count($userkey);
            $arr_p['checkpointsman_count'] = $this->get_checkpoints_count($userkey);
            if ($arr_p['checkpoints_count'] == 1) {
                $arr_p['checkpoints'] = $this->get_checkpoint_customer($customerno, $userkey);
            }
            $groupidsql = "SELECT * ,groupman.isdeleted as gmdel,groupman.groupid as gmgrpid, (SELECT groupman.isdeleted FROM groupman WHERE groupman.userid = user.userid
                                                ORDER BY groupman.gmid DESC LIMIT 0 , 1) AS grpdel FROM user
                                                LEFT OUTER JOIN groupman ON groupman.userid = user.userid
                                                WHERE user.userkey =$userkey
                                                ORDER BY groupman.gmid DESC";
            $recordgrp = $this->db->query($groupidsql, __FILE__, __LINE__);
            $groupids = array();
            while ($rowgrp = $this->db->fetch_array($recordgrp)) {
                if ($rowgrp['gmdel'] == 0) {
                    $groupid = $rowgrp['gmgrpid'];
                    $groupids[] = $groupid;
                }
            }
            $firstgroup = array_shift(array_values($groupids));

            $sql = "SELECT vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max, vehicle.kind, vehicle.groupid, unit.tempsen1, unit.tempsen2, unit.analog1, unit.analog2, unit.analog3, unit.analog4,
                                                            customer.temp_sensors, vehicle.stoppage_transit_time, vehicle.stoppage_flag, customer.use_geolocation, user.customerno as customer_no,vehicle.groupid as veh_grpid,vehicle.vehicleid, vehicle.overspeed_limit, vehicle.extbatt, vehicle.vehicleno,vehicle.curspeed,unit.unitno, unit.digitalio, unit.acsensor, unit.is_ac_opp, driver.drivername,driver.driverphone,
                                                            devices.lastupdated, devices.ignition, devices.inbatt, devices.tamper, devices.powercut, devices.gsmstrength, devices.devicelat, devices.devicelong,
                                                            (SELECT customname FROM `customfield` where customerno=user.customerno AND name='Digital' AND `usecustom`=1) as digital,
                                                            ignitionalert.status as igstatus,ignitionalert.ignchgtime FROM vehicle
                                                            INNER JOIN devices ON devices.uid = vehicle.uid
                                                            INNER JOIN driver ON driver.driverid = vehicle.driverid
                                                            INNER JOIN unit ON devices.uid = unit.uid
                                                            INNER JOIN customer ON customer.customerno = vehicle.customerno
                                                            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                                                            INNER JOIN user ON vehicle.customerno = user.customerno
                                                            WHERE vehicle.customerno =$customerno
                                                            AND user.userkey =$userkey
                                                            AND unit.trans_statusid NOT IN (10,22)
                                                            AND vehicle.isdeleted=0 and driver.isdeleted=0 and devices.lastupdated <> '0000-00-00 00:00:00' ORDER BY devices.lastupdated DESC";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            $json_p = array();
            $x = 0;
            while ($row = $this->db->fetch_array($record)) {
                if ($firstgroup == '') {
                    $json_p[$x]['vehicleid'] = $row['vehicleid'];
                    if ($arr_p['checkpointsman_count'] == 1) {
                        $json_p[$x]['checkpoints'] = $this->get_checkpoints($row['vehicleid']);
                    }
                    $json_p[$x]['vehicleno'] = $row['vehicleno'];
                    $json_p[$x]['kind'] = $row['kind'];
                    $json_p[$x]['groupid'] = $row['groupid'];
                    $json_p[$x]['location'] = $this->location($row['devicelat'], $row['devicelong'], $row['customer_no'], $row['use_geolocation']);
                    $json_p[$x]['unitno'] = $row['unitno'];
                    $json_p[$x]['lastupdated'] = $row['lastupdated'];
                    $json_p[$x]['drivername'] = $row['drivername'];
                    $json_p[$x]['driverphone'] = $row['driverphone'];
                    $json_p[$x]['ignition'] = $row['ignition'];
                    $json_p[$x]['curspeed'] = $row['curspeed'];
                    $json_p[$x]['distance'] = $this->distance($row['customer_no'], $row['unitno']);
                    if ($row['acsensor'] == 1) {
                        if ($row['digitalio'] == 0) {
                            if ($row["is_ac_opp"] == 0) {
                                $json_p[$x]['acstatus'] = "1";
                            } else {
                                $json_p[$x]['acstatus'] = "0";
                            }
                        } else
                        if ($row["is_ac_opp"] == 0) {
                            $json_p[$x]['acstatus'] = "0";
                        } else {
                            $json_p[$x]['acstatus'] = "1";
                        }
                    } else {
                        $json_p[$x]['acstatus'] = "-1";
                    }
                    $diff = $this->getduration(date('Y-m-d H:i:s'), $row["stoppage_transit_time"]);
                    if ($row["stoppage_flag"] == '1') {
                        $json_p[$x]['ignstatus'] = "Running since $diff";
                    } else if ($row["stoppage_flag"] == '0') {
                        $json_p[$x]['ignstatus'] = "Idle since $diff";
                    }
                    $json_p[$x]['extbatt'] = round($row['extbatt'] / 100, 2);
                    $json_p[$x]['inbatt'] = $row['inbatt'] / 1000;
                    $json_p[$x]['tamper'] = $row['tamper'];
                    $json_p[$x]['powercut'] = $row['powercut'];
                    $json_p[$x]['gsmstrength'] = round($row['gsmstrength'] / 31 * 100);
                    $json_p[$x]['devicelat'] = $row['devicelat'];
                    $json_p[$x]['devicelong'] = $row['devicelong'];
                    $json_p[$x]['digital'] = $row['digital'];
                    $json_p[$x]['stoppage_flag'] = $row['stoppage_flag'];
                    $json_p[$x]['overspeed_limit'] = $row['overspeed_limit'];
                    $json_p[$x]['temp1_min'] = $row['temp1_min'];
                    $json_p[$x]['temp1_max'] = $row['temp1_max'];
                    $json_p[$x]['temp2_min'] = $row['temp2_min'];
                    $json_p[$x]['temp2_max'] = $row['temp2_max'];

                    // Temperature Sensor
                    $json_p[$x]['temp_sensors'] = $row['temp_sensors'];
                    $json_p[$x]['temp1'] = 'Not Active';
                    $json_p[$x]['temp2'] = 'Not Active';
                    if ($row['temp_sensors'] == '1') {
                        if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                            $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                        else
                            $json_p[$x]['temp1'] = '0';
                    }

                    if ($row['temp_sensors'] == '2') {
                        if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                            $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                        else
                            $json_p[$x]['temp1'] = '0';

                        if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0')
                            $json_p[$x]['temp2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                        else
                            $json_p[$x]['temp2'] = '0';
                    }
                    $x++;
                }
                else if (in_array($row['veh_grpid'], $groupids)) {
                    $json_p[$x]['vehicleid'] = $row['vehicleid'];
                    if ($arr_p['checkpointsman_count'] == 1) {
                        $json_p[$x]['checkpoints'] = $this->get_checkpoints($row['vehicleid']);
                    }
                    $json_p[$x]['vehicleno'] = $row['vehicleno'];
                    $json_p[$x]['kind'] = $row['kind'];
                    $json_p[$x]['groupid'] = $row['groupid'];
                    $json_p[$x]['location'] = $this->location($row['devicelat'], $row['devicelong'], $row['customer_no'], $row['use_geolocation']);
                    $json_p[$x]['unitno'] = $row['unitno'];
                    $json_p[$x]['lastupdated'] = $row['lastupdated'];
                    $json_p[$x]['drivername'] = $row['drivername'];
                    $json_p[$x]['driverphone'] = $row['driverphone'];
                    $json_p[$x]['ignition'] = $row['ignition'];
                    $json_p[$x]['curspeed'] = $row['curspeed'];
                    $json_p[$x]['stoppage_flag'] = $row['stoppage_flag'];
                    $json_p[$x]['distance'] = $this->distance($row['customer_no'], $row['unitno']);
                    if ($row['acsensor'] == '1') {
                        if ($row['digitalio'] == '0') {
                            if ($row["is_ac_opp"] == '0') {
                                $json_p[$x]['acstatus'] = "1";
                            } else {
                                $json_p[$x]['acstatus'] = "0";
                            }
                        } else
                        if ($row["is_ac_opp"] == '0') {
                            $json_p[$x]['acstatus'] = "0";
                        } else {
                            $json_p[$x]['acstatus'] = "1";
                        }
                    } else {
                        $json_p[$x]['acstatus'] = "-1";
                    }
                    $diff = $this->getduration(date('Y-m-d H:i:s'), $row["stoppage_transit_time"]);
                    if ($row["stoppage_flag"] == '1') {
                        $json_p[$x]['ignstatus'] = "Running since $diff";
                    } else if ($row["stoppage_flag"] == '0') {
                        $json_p[$x]['ignstatus'] = "Idle since $diff";
                    }
                    $json_p[$x]['extbatt'] = round($row['extbatt'] / 100, 2);
                    $json_p[$x]['inbatt'] = $row['inbatt'] / 1000;
                    $json_p[$x]['tamper'] = $row['tamper'];
                    $json_p[$x]['powercut'] = $row['powercut'];
                    $json_p[$x]['gsmstrength'] = round($row['gsmstrength'] / 31 * 100);
                    $json_p[$x]['devicelat'] = $row['devicelat'];
                    $json_p[$x]['devicelong'] = $row['devicelong'];
                    $json_p[$x]['digital'] = $row['digital'];
                    $json_p[$x]['overspeed_limit'] = $row['overspeed_limit'];
                    $json_p[$x]['temp1_min'] = $row['temp1_min'];
                    $json_p[$x]['temp1_max'] = $row['temp1_max'];
                    $json_p[$x]['temp2_min'] = $row['temp2_min'];
                    $json_p[$x]['temp2_max'] = $row['temp2_max'];

                    // Temperature Sensor
                    $json_p[$x]['temp_sensors'] = $row['temp_sensors'];
                    $json_p[$x]['temp1'] = 'Not Active';
                    $json_p[$x]['temp2'] = 'Not Active';
                    if ($row['temp_sensors'] == '1') {
                        if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                            $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                        else
                            $json_p[$x]['temp1'] = '0';
                    }

                    if ($row['temp_sensors'] == '2') {
                        if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                            $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                        else
                            $json_p[$x]['temp1'] = '0';

                        if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0')
                            $json_p[$x]['temp2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                        else
                            $json_p[$x]['temp2'] = '0';
                    }

                    $x++;
                }
            }
            $arr_p['status'] = "successful";
            $arr_p['result'] = $json_p;
            $this->update_push_android_chk($userkey, 0);
        }else {
            $arr_p['status'] = "unsuccessful";
        }

        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    public function checkvalidity($expirydate) {
        $today = date('Y-m-d H:i:s');
//        $today = add_hours($today, 0);
        if (strtotime($today) <= strtotime($expirydate)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function register_gcm($userkey, $regid) {
        $sql = "select userid from user where userkey='" . $userkey . "'";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        $response = array();
        $response['status'] = 'failure';
        if ($row['userid'] != "") {
            $sql = "UPDATE user SET gcmid = '" . $regid . "' WHERE userkey = '" . $userkey . "'";
            $this->db->query($sql, __FILE__, __LINE__);

            $response['status'] = 'success';
        } else {
            $response['status'] = 'failure';
        }

        echo json_encode($response);
    }

    function unregister_gcm($userkey) {
        $sql = "select userid from user where userkey='" . $userkey . "'";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        $response = array();
        $response['status'] = 'failure';
        if ($row['userid'] != "") {
            $sql = "UPDATE user SET gcmid = '' WHERE userkey = '" . $userkey . "'";

            $response['status'] = 'success';
        } else {
            $response['status'] = 'failure';
        }

        echo json_encode($response);
    }

    public function client_code_details($ecodeid) {
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";

        $Query = "SELECT vehicle.overspeed_limit, vehicle.stoppage_flag, vehicle.vehicleno,vehicle.vehicleid,devices.deviceid,
            devices.devicelat,devices.devicelong,driver.drivername,driver.driverphone,vehicle.curspeed,
            devices.lastupdated,vehicle.kind,devices.ignition,devices.status,devices.directionchange,
            devices.uid,elixiacode.expirydate FROM `devices`
            INNER JOIN unit ON unit.uid = devices.uid
            INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            INNER JOIN driver ON vehicle.driverid = driver.driverid
            INNER JOIN ecodeman ON ecodeman.vehicleid = vehicle.vehicleid
            INNER JOIN elixiacode ON elixiacode.id = ecodeman.ecodeid
            where elixiacode.ecode=%d AND unit.trans_statusid NOT IN (10,22)";
        $devicesQuery = sprintf($Query, $ecodeid);
        $record = $this->db->query($devicesQuery, __FILE__, __LINE__);

        $json_p = array();
        $arr_p['error'] = 'Invalid Client Code';
        $x = 0;
        while ($row = $this->db->fetch_array($record)) {
            if ($row['uid'] > 0) {
                if ($row['devicelat'] > 0 & $row['devicelong'] > 0) {
                    $checkstatus = $this->checkvalidity($row["expirydate"]);
                    if ($checkstatus == true) {
                        $json_p[$x]['vehicleno'] = $row['vehicleno'];
                        $json_p[$x]['vehicleid'] = $row['vehicleid'];
                        $json_p[$x]['deviceid'] = $row['deviceid'];
                        $json_p[$x]['devicelat'] = $row['devicelat'];
                        $json_p[$x]['devicelong'] = $row['devicelong'];
                        $json_p[$x]['drivername'] = $row['drivername'];
                        $json_p[$x]['driverphone'] = $row['driverphone'];
                        $json_p[$x]['curspeed'] = $row['curspeed'];
                        $json_p[$x]['lastupdated'] = $row['lastupdated'];
                        $json_p[$x]['type'] = $row['kind'];
                        $json_p[$x]['ignition'] = $row['ignition'];
                        $json_p[$x]['devicestatus'] = $row['status'];
                        $json_p[$x]['directionchange'] = $row['directionchange'];
                        $json_p[$x]['overspeed_limit'] = $row['overspeed_limit'];
                        $json_p[$x]['stoppage_flag'] = $row['stoppage_flag'];
                        $arr_p['error'] = '';
                        $arr_p['status'] = "successful";
                        $x++;
                    } else {
                        $arr_p['error'] = 'Client Code Expired';
                    }
                    $arr_p['result'] = $json_p;
                }
            }
        }
        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    public function create_client_code($userkey, $code) {
        //print_r($code);

        $code = json_decode($code);
        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";
        $today = date('Y-m-d H:i:s');
        $code->menuoption = 0;
        if ($validation['status'] == "successful") {
            $ecoderandom = mt_rand(0, 999999);
            $customerno = $validation["customerno"];
            $userid = $validation['userid'];

            //1567123285
            //1335747238
            $code->reports = str_replace(', ', ',', $code->reports);
            $reports = explode(",", $code->reports);

            if (in_array('Route History', $reports)) {
                $code->menuoption = $code->menuoption + 1;
            }
            if (in_array('Travel History', $reports)) {
                $code->menuoption = $code->menuoption + 2;
            }
            if (in_array('Alert History', $reports)) {
                $code->menuoption = $code->menuoption + 4;
            }
            if (in_array('Fuel Refill History', $reports)) {
                $code->menuoption = $code->menuoption + 8;
            }
            if (in_array('Location History', $reports)) {
                $code->menuoption = $code->menuoption + 16;
            }
            if (in_array('Stoppage History', $reports)) {
                $code->menuoption = $code->menuoption + 32;
            }
            if (in_array('Overspeed History', $reports)) {
                $code->menuoption = $code->menuoption + 64;
            }
            if (in_array('Genset History', $reports)) {
                $code->menuoption = $code->menuoption + 128;
            }
            if (in_array('Trip Report', $reports)) {
                $code->menuoption = $code->menuoption + 256;
                $code->menuoption = $code->menuoption + 1048576;
            }
            if (in_array('Checkpoint Report', $reports)) {
                $code->menuoption = $code->menuoption + 1024;
            }
            if (in_array('Fuel Comsumption Report', $reports)) {
                $code->menuoption = $code->menuoption + 2048;
                $code->menuoption = $code->menuoption + 524288;
            }
            if (in_array('Route Report', $reports)) {
                $code->menuoption = $code->menuoption + 4096;
            }
            if (in_array('Distance Report', $reports)) {
                $code->menuoption = $code->menuoption + 8192;
            }
            if (in_array('Idle-Time Report', $reports)) {
                $code->menuoption = $code->menuoption + 16384;
            }
            if (in_array('Genset Report', $reports)) {
                $code->menuoption = $code->menuoption + 32768;
            }
            if (in_array('Overspeed Report', $reports)) {
                $code->menuoption = $code->menuoption + 65536;
            }
            if (in_array('Fence Cnflict Report', $reports)) {
                $code->menuoption = $code->menuoption + 131072;
            }
            if (in_array('Location Report', $reports)) {
                $code->menuoption = $code->menuoption + 262144;
            }
            if (in_array('Temperature Report', $reports)) {
                $code->menuoption = $code->menuoption + 2097152;
                $code->menuoption = $code->menuoption + 4194304;
            }

            //echo $code->menuoption;
            $vids = Array();

            $code->vehicles = str_replace(', ', ',', $code->vehicles);
            $vehicles = explode(",", $code->vehicles);
            foreach ($vehicles as $vehicle) {
                $SQL = "select * from vehicle where customerno=$customerno and REPLACE(vehicleno, ' ', '') = '$vehicle'";
                $rec = $this->db->query($SQL, __FILE__, __LINE__);
                while ($row = $this->db->fetch_array($rec)) {
                    $vids[] = $row['vehicleid'];
                }
            }

            if (!empty($vids)) {
                $sqlInsert = "INSERT INTO elixiacode (`customerno`,`startdate`,`expirydate`,`ecode`,`datecreated`,isdeleted, userid, ecodeemail, ecodesms, menuoption) VALUES ( $customerno,'" . $code->startdate . "','" . $code->enddate . "','$ecoderandom','" . $today . "',0,$userid,'" . $code->email . "','" . $code->sms . "',$code->menuoption)";
                $record = $this->db->query($sqlInsert, __FILE__, __LINE__);
                $lastid = $this->db->last_insert_id();
                if (isset($record)) {


                    foreach ($vids as $vid) {
                        $Query = "INSERT INTO ecodeman (`customerno`,`vehicleid`,`ecodeid`,isdeleted, userid) VALUES ( $customerno,$vid,$lastid,0,$userid)";
                        $this->db->query($Query, __FILE__, __LINE__);
                    }
                    $arr_p['status'] = "Successful";
                    $arr_p['clientcode'] = $ecoderandom;

                    if ($code->email != '') {
                        $subject = "Client Code";
                        $content = 'Your Client Code is ' . $ecoderandom . '. <br/>Generated by ' . $validation['realname'] . '. <br/>Please visit www.speed.elixiatech.com and trace your vehicle. <br/> Valid Until: ' . date("F j, Y, g:i a", strtotime($code->enddate));
                        $headers = "From: noreply@elixiatech.com\r\n";
                        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                        @mail($code->email, $subject, $content, $headers);
                    }

                    if ($code->sms != '') {
                        $smsmessage = 'Client Code: ' . $ecoderandom . '. Generated by ' . $validation['realname'] . '. Validity: ' . date("F j, Y, g:i a", strtotime($code->enddate)) . '. Link: www.speed.elixiatech.com';
                        $url = "http://pacems.asia:8080/bulksms/bulksms?username=xzt-elixia&password=elixia&type=0&dlr=1&destination=91" . urlencode($code->sms) . "&source=ELIXIA&message=" . urlencode($smsmessage);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                        $result = curl_exec($ch);
                        curl_close($ch);
                    }
                } else {
                    $arr_p['status'] = "unsuccessful";
                }
            } else {
                $arr_p['status'] = "unsuccessful";
                $arr_p['error'] = "please check the vehicle number";
            }
            //print_r($vids);
        } else {
            $arr_p['status'] = "unsuccessful";
        }

        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    public function update_push_android_chk($userkey, $val) {
        $sql = "UPDATE user SET chkmanpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    public function update_push_android_chk_main($userkey, $val) {
        $sql = "UPDATE user SET chkpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    public function get_checkpoints($vehicleid) {
        $Query = "SELECT checkpoint.checkpointid, checkpoint.cname, checkpoint.cgeolat, checkpoint.cgeolong, checkpoint.crad FROM checkpointmanage INNER JOIN checkpoint ON checkpoint.checkpointid = checkpointmanage.checkpointid WHERE checkpointmanage.isdeleted = 0 AND checkpointmanage.vehicleid = %d";
        $devicesQuery = sprintf($Query, $vehicleid);
        $record = $this->db->query($devicesQuery, __FILE__, __LINE__);
        $json_p = Array();
        $x = 0;
        while ($row = $this->db->fetch_array($record)) {
            $json_p[$x]['checkpointid'] = $row['checkpointid'];
            $x++;
        }

        return $json_p;
    }

    public function get_checkpoint_customer($customerno, $userkey) {
        $Query = "SELECT distinct(checkpoint.checkpointid), checkpoint.cname, checkpoint.cgeolat, checkpoint.cgeolong, checkpoint.crad FROM checkpointmanage INNER JOIN checkpoint ON checkpoint.checkpointid = checkpointmanage.checkpointid WHERE checkpointmanage.isdeleted = 0 AND checkpoint.isdeleted = 0 AND checkpointmanage.customerno = %d";
        $devicesQuery = sprintf($Query, $customerno);
        $record = $this->db->query($devicesQuery, __FILE__, __LINE__);
        $json_p = array();
        $x = 0;
        while ($row = $this->db->fetch_array($record)) {
            $json_p[$x]['cname'] = $row['cname'];
            $json_p[$x]['checkpointid'] = $row['checkpointid'];
            $json_p[$x]['cgeolat'] = $row['cgeolat'];
            $json_p[$x]['cgeolong'] = $row['cgeolong'];
            $json_p[$x]['crad'] = $row['crad'];
            $x++;
        }

        $sql = "UPDATE user SET chkpushandroid = 0 WHERE userkey = '" . $userkey . "'";
        $this->db->query($sql, __FILE__, __LINE__);

        return $json_p;
    }

    public function get_checkpoint_customer_count($userkey) {
        $q = "SELECT chkpushandroid FROM user WHERE userkey = %s";
        $dq = sprintf($q, $userkey);
        $record = $this->db->query($dq, __FILE__, __LINE__);
        $p = 0;

        while ($row = $this->db->fetch_array($record)) {
            $p = $row['chkpushandroid'];
        }

        return $p;
    }

    public function get_checkpoints_count($userkey) {
        $q = "SELECT chkmanpushandroid FROM user WHERE userkey = %s";
        $dq = sprintf($q, $userkey);
        $record = $this->db->query($dq, __FILE__, __LINE__);
        $p = 0;

        while ($row = $this->db->fetch_array($record)) {
            $p = $row['chkmanpushandroid'];
        }

        return $p;
    }

    function device_list_details($userkey, $vehicleid) {

        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";

        if ($validation['status'] == "successful") {
            $arr_p['checkpointsman_count'] = $this->get_checkpoints_count($userkey);
            // successful
            $customerno = $validation["customerno"];
            $sql = "SELECT vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max, vehicle.kind, vehicle.groupid, unit.tempsen1, unit.tempsen2, unit.analog1, unit.analog2, unit.analog3, unit.analog4,
                                                            customer.temp_sensors, vehicle.stoppage_transit_time, vehicle.stoppage_flag, customer.use_geolocation, user.customerno as customer_no,vehicle.groupid as veh_grpid,vehicle.vehicleid, vehicle.overspeed_limit, vehicle.extbatt, vehicle.vehicleno,vehicle.curspeed,unit.unitno, unit.digitalio, unit.acsensor, unit.is_ac_opp, driver.drivername,driver.driverphone,
                                                    devices.lastupdated, devices.ignition, devices.inbatt, devices.tamper, devices.powercut, devices.gsmstrength, devices.devicelat, devices.devicelong,
                                                            (SELECT customname FROM `customfield` where customerno=user.customerno AND name='Digital' AND `usecustom`=1) as digital,
                                                            ignitionalert.status as igstatus,ignitionalert.ignchgtime FROM vehicle
                                                            INNER JOIN devices ON devices.uid = vehicle.uid
                                                            INNER JOIN driver ON driver.driverid = vehicle.driverid
                                                            INNER JOIN unit ON devices.uid = unit.uid
                                                            INNER JOIN customer ON customer.customerno = vehicle.customerno
                                                            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                                                            INNER JOIN user ON vehicle.customerno = user.customerno
                                                            WHERE vehicle.customerno =$customerno
                                                            AND user.userkey =$userkey
                                                            AND unit.trans_statusid NOT IN (10,22)
                                                            AND vehicle.isdeleted=0 and driver.isdeleted=0 and devices.lastupdated <> '0000-00-00 00:00:00' AND vehicle.vehicleid = $vehicleid ORDER BY devices.lastupdated DESC";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            $json_p = array();
            $x = 0;
            while ($row = $this->db->fetch_array($record)) {
                $json_p[$x]['vehicleid'] = $row['vehicleid'];
                if ($arr_p['checkpointsman_count'] == 1) {
                    $json_p[$x]['checkpoints'] = $this->get_checkpoints($row['vehicleid']);
                }
                $json_p[$x]['vehicleno'] = $row['vehicleno'];
                $json_p[$x]['kind'] = $row['kind'];
                $json_p[$x]['groupid'] = $row['groupid'];
                $json_p[$x]['location'] = $this->location($row['devicelat'], $row['devicelong'], $row['customer_no'], $row['use_geolocation']);
                $json_p[$x]['unitno'] = $row['unitno'];
                $json_p[$x]['lastupdated'] = $row['lastupdated'];
                $json_p[$x]['drivername'] = $row['drivername'];
                $json_p[$x]['driverphone'] = $row['driverphone'];
                $json_p[$x]['ignition'] = $row['ignition'];
                $json_p[$x]['curspeed'] = $row['curspeed'];
                $json_p[$x]['distance'] = $this->distance($row['customer_no'], $row['unitno']);
                if ($row['acsensor'] == 1) {
                    if ($row['digitalio'] == 0) {
                        if ($row["is_ac_opp"] == 0) {
                            $json_p[$x]['acstatus'] = "1";
                        } else {
                            $json_p[$x]['acstatus'] = "0";
                        }
                    } else
                    if ($row["is_ac_opp"] == 0) {
                        $json_p[$x]['acstatus'] = "0";
                    } else {
                        $json_p[$x]['acstatus'] = "1";
                    }
                } else {
                    $json_p[$x]['acstatus'] = "-1";
                }
                $diff = $this->getduration(date('Y-m-d H:i:s'), $row["stoppage_transit_time"]);
                if ($row["stoppage_flag"] == '1') {
                    $json_p[$x]['ignstatus'] = "Running since $diff";
                } else if ($row["stoppage_flag"] == '0') {
                    $json_p[$x]['ignstatus'] = "Idle since $diff";
                }
                $json_p[$x]['extbatt'] = round($row['extbatt'] / 100, 2);
                $json_p[$x]['inbatt'] = $row['inbatt'] / 1000;
                $json_p[$x]['tamper'] = $row['tamper'];
                $json_p[$x]['powercut'] = $row['powercut'];
                $json_p[$x]['gsmstrength'] = round($row['gsmstrength'] / 31 * 100);
                $json_p[$x]['devicelat'] = $row['devicelat'];
                $json_p[$x]['devicelong'] = $row['devicelong'];
                $json_p[$x]['digital'] = $row['digital'];
                $json_p[$x]['stoppage_flag'] = $row['stoppage_flag'];
                $json_p[$x]['overspeed_limit'] = $row['overspeed_limit'];
                $json_p[$x]['temp1_min'] = $row['temp1_min'];
                $json_p[$x]['temp1_max'] = $row['temp1_max'];
                $json_p[$x]['temp2_min'] = $row['temp2_min'];
                $json_p[$x]['temp2_max'] = $row['temp2_max'];

                // Temperature Sensor
                $json_p[$x]['temp_sensors'] = $row['temp_sensors'];
                $json_p[$x]['temp1'] = 'Not Active';
                $json_p[$x]['temp2'] = 'Not Active';
                if ($row['temp_sensors'] == '1') {
                    if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                        $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                    else
                        $json_p[$x]['temp1'] = '0';
                }

                if ($row['temp_sensors'] == '2') {
                    if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                        $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                    else
                        $json_p[$x]['temp1'] = '0';

                    if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0')
                        $json_p[$x]['temp2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                    else
                        $json_p[$x]['temp2'] = '0';
                }

                $x++;
            }

            $arr_p['status'] = "successful";
            $arr_p['result'] = $json_p;
            $this->update_push_android_chk($userkey, 0);
        }else {
            $arr_p['status'] = "unsuccessful";
        }

        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function device_list_byname($userkey, $vehicleno) {

        $validation = $this->check_userkey($userkey);
        $arr_p = array();
        $arr_p['status'] = "unsuccessful";

        if ($validation['status'] == "successful") {
            $arr_p['checkpointsman_count'] = $this->get_checkpoints_count($userkey);
            // successful
            $customerno = $validation["customerno"];
            $sql = "SELECT vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max, vehicle.kind, vehicle.groupid, unit.tempsen1, unit.tempsen2, unit.analog1, unit.analog2, unit.analog3, unit.analog4,
                                                            customer.temp_sensors, vehicle.stoppage_transit_time, vehicle.stoppage_flag, customer.use_geolocation, user.customerno as customer_no,vehicle.groupid as veh_grpid,vehicle.vehicleid, vehicle.overspeed_limit, vehicle.extbatt, vehicle.vehicleno,vehicle.curspeed,unit.unitno, unit.digitalio, unit.acsensor, unit.is_ac_opp, driver.drivername,driver.driverphone,
                                                    devices.lastupdated, devices.ignition, devices.inbatt, devices.tamper, devices.powercut, devices.gsmstrength, devices.devicelat, devices.devicelong,
                                                            (SELECT customname FROM `customfield` where customerno=user.customerno AND name='Digital' AND `usecustom`=1) as digital,
                                                            ignitionalert.status as igstatus,ignitionalert.ignchgtime FROM vehicle
                                                            INNER JOIN devices ON devices.uid = vehicle.uid
                                                            INNER JOIN driver ON driver.driverid = vehicle.driverid
                                                            INNER JOIN unit ON devices.uid = unit.uid
                                                            INNER JOIN customer ON customer.customerno = vehicle.customerno
                                                            INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                                                            INNER JOIN user ON vehicle.customerno = user.customerno
                                                            WHERE vehicle.customerno =$customerno
                                                            AND user.userkey =$userkey
                                                            AND unit.trans_statusid NOT IN (10,22)
                                                            AND vehicle.isdeleted=0 and driver.isdeleted=0 and devices.lastupdated <> '0000-00-00 00:00:00' AND vehicle.vehicleno = '$vehicleno' ORDER BY devices.lastupdated DESC";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            $json_p = array();
            $x = 0;
            while ($row = $this->db->fetch_array($record)) {
                $json_p[$x]['vehicleid'] = $row['vehicleid'];
                if ($arr_p['checkpointsman_count'] == 1) {
                    $json_p[$x]['checkpoints'] = $this->get_checkpoints($row['vehicleid']);
                }
                $json_p[$x]['vehicleno'] = $row['vehicleno'];
                $json_p[$x]['kind'] = $row['kind'];
                $json_p[$x]['groupid'] = $row['groupid'];
                $json_p[$x]['location'] = $this->location($row['devicelat'], $row['devicelong'], $row['customer_no'], $row['use_geolocation']);
                $json_p[$x]['unitno'] = $row['unitno'];
                $json_p[$x]['lastupdated'] = $row['lastupdated'];
                $json_p[$x]['drivername'] = $row['drivername'];
                $json_p[$x]['driverphone'] = $row['driverphone'];
                $json_p[$x]['ignition'] = $row['ignition'];
                $json_p[$x]['curspeed'] = $row['curspeed'];
                $json_p[$x]['distance'] = $this->distance($row['customer_no'], $row['unitno']);
                if ($row['acsensor'] == 1) {
                    if ($row['digitalio'] == 0) {
                        if ($row["is_ac_opp"] == 0) {
                            $json_p[$x]['acstatus'] = "1";
                        } else {
                            $json_p[$x]['acstatus'] = "0";
                        }
                    } else
                    if ($row["is_ac_opp"] == 0) {
                        $json_p[$x]['acstatus'] = "0";
                    } else {
                        $json_p[$x]['acstatus'] = "1";
                    }
                } else {
                    $json_p[$x]['acstatus'] = "-1";
                }
                $diff = $this->getduration(date('Y-m-d H:i:s'), $row["stoppage_transit_time"]);
                if ($row["stoppage_flag"] == '1') {
                    $json_p[$x]['ignstatus'] = "Running since $diff";
                } else if ($row["stoppage_flag"] == '0') {
                    $json_p[$x]['ignstatus'] = "Idle since $diff";
                }
                $json_p[$x]['extbatt'] = round($row['extbatt'] / 100, 2);
                $json_p[$x]['inbatt'] = $row['inbatt'] / 1000;
                $json_p[$x]['tamper'] = $row['tamper'];
                $json_p[$x]['powercut'] = $row['powercut'];
                $json_p[$x]['gsmstrength'] = round($row['gsmstrength'] / 31 * 100);
                $json_p[$x]['devicelat'] = $row['devicelat'];
                $json_p[$x]['devicelong'] = $row['devicelong'];
                $json_p[$x]['digital'] = $row['digital'];
                $json_p[$x]['stoppage_flag'] = $row['stoppage_flag'];
                $json_p[$x]['overspeed_limit'] = $row['overspeed_limit'];
                $json_p[$x]['temp1_min'] = $row['temp1_min'];
                $json_p[$x]['temp1_max'] = $row['temp1_max'];
                $json_p[$x]['temp2_min'] = $row['temp2_min'];
                $json_p[$x]['temp2_max'] = $row['temp2_max'];

                // Temperature Sensor
                $json_p[$x]['temp_sensors'] = $row['temp_sensors'];
                $json_p[$x]['temp1'] = 'Not Active';
                $json_p[$x]['temp2'] = 'Not Active';
                if ($row['temp_sensors'] == '1') {
                    if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                        $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                    else
                        $json_p[$x]['temp1'] = '0';
                }

                if ($row['temp_sensors'] == '2') {
                    if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0')
                        $json_p[$x]['temp1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                    else
                        $json_p[$x]['temp1'] = '0';

                    if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0')
                        $json_p[$x]['temp2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                    else
                        $json_p[$x]['temp2'] = '0';
                }

                $x++;
            }

            $arr_p['status'] = "successful";
            $arr_p['result'] = $json_p;
            $this->update_push_android_chk($userkey, 0);
        }else {
            $arr_p['status'] = "unsuccessful";
        }

        echo json_encode($arr_p);
        return json_encode($arr_p);
    }

    function api_track($skey, $vehicleno) {
        $skey = stripslashes(trim($skey));
        $vehicleno = stripslashes(trim($vehicleno));
        if ($skey != "" && isset($skey)) {
            $sql1 = "select customerno from " . TBL_ADMIN_USER . " where 1 and userkey='" . $skey . "'";
            $result1 = $this->db->query($sql1, __FILE__, __LINE__);
            $row1 = $this->db->fetch_array($result1);

            if ($row1['customerno'] != "") {
                $_SESSION['api_customer_id'] = $row1['customerno'];
                header('Location: map.php?vehicleno=' . $vehicleno . '');
            } else {
                echo "Customer does not exist";
            }
        } else {
            echo "Request denied";
            die();
        }
    }

    function api_device_list($vehicleno) {

        if (isset($_SESSION['api_customer_id']) && $_SESSION['api_customer_id'] != "") {
            $sql = "select  	devices.*,	unit.vehicleid ,	vehicle.vehicleno,vehicle.extbatt,vehicle.curspeed,vehicle.odometer, ";
            $sql.="driver.drivername,driver.drivername,driver.driverphone FROM 	devices INNER JOIN unit ON unit.uid=devices.uid INNER JOIN vehicle ON 	";
            $sql.="vehicle.vehicleid=unit.vehicleid INNER JOIN driver  ON driver.driverid=vehicle.driverid";
            $sql.=" WHERE devices.customerno=" . $_SESSION['api_customer_id'] . " and vehicle.isdeleted=0 and  driver.isdeleted=0   ";
            $str = array();

            if ($vehicleno != "null") {
                $vehicleno = explode(",", $vehicleno);

                $count = count($vehicleno);
                for ($i = 0; $i < $count; $i++) {
                    if ($i == 0) {
                        $sql.=" and ";
                    }

                    if ($vehicleno[$i]) {

                        $sql.=" vehicleno = '" . stripslashes(trim($vehicleno[$i])) . "'  ";
                        if ($count == 1) {

                        } else {
                            if ($i < ($count - 1))
                                $sql.=" or  ";
                        }
                    }
                }
            }

            //echo $sql;

            $record = $this->db->query($sql, __FILE__, __LINE__);
            $json_p = array();
            //$json_p="null";
            $x = 0;

            while ($row = $this->db->fetch_array($record)) {
                if ($row['devicelat'] != 0 and $row['devicelong'] != 0) {

                    $json_p[$x]['deviceid'] = $row['deviceid'];
                    $json_p[$x]['vehicleno'] = $row['vehicleno'];
                    $json_p[$x]['drivername'] = $row['drivername'];
                    $json_p[$x]['driverphoneno'] = $row['driverphoneno'];
                    $json_p[$x]['devicelat'] = $row['devicelat'];
                    $json_p[$x]['devicelong'] = $row['devicelong'];
                    $json_p[$x]['lastupdated'] = $row['lastupdated'];

                    $x++;
                }
            }
            $arr_p = array();
            $arr_p['status'] = "successful";
            $arr_p['dcount'] = $x;

            $arr_p['result'] = $json_p;

            echo json_encode($arr_p);
        } else {

            echo '{"status":"failure"}';
        }
    }

    function event_history_access_handler($skey, $vehicleno, $startdate, $endtime) {

        $skey = stripslashes(trim($skey));
        $endtime = stripslashes(trim($endtime));
        $startdate = stripslashes(trim($startdate));
        $vehicleno = stripslashes(trim($vehicleno));
        if ($skey != "" && isset($skey)) {

            $sql1 = "select customerno from " . TBL_ADMIN_USER . " where 1 and userkey='" . $skey . "'";
            $result1 = $this->db->query($sql1, __FILE__, __LINE__);
            $row1 = $this->db->fetch_array($result1);
            if ($endtime != "") {
                $_SESSION['endtime'] = $endtime;
            } else {
                $_SESSION['endtime'] = "23:59:59";
            }
            if ($startdate != "") {
                $_SESSION['startdate'] = strtotime($startdate);
            } else {
                $_SESSION['startdate'] = strtotime(date("d-m-y h:i:s"));
            }

            if ($row1['customerno'] != "") {
                $_SESSION['api_customer_id'] = $row1['customerno'];
                $sql = "select  	devices.*,unit.unitno,	unit.vehicleid ,	vehicle.vehicleno,vehicle.extbatt,vehicle.curspeed,vehicle.odometer, ";
                $sql.="driver.drivername,driver.drivername,driver.driverphone FROM 	devices INNER JOIN unit ON unit.uid=devices.uid INNER JOIN vehicle ON 	";
                $sql.="vehicle.vehicleid=unit.vehicleid INNER JOIN driver  ON driver.driverid=vehicle.driverid";
                $sql.=" WHERE devices.customerno=" . $_SESSION['api_customer_id'] . " and
                                                            vehicle.vehicleno='" . $vehicleno . "' and vehicle.isdeleted=0 and  driver.isdeleted=0   ";
                $result = $this->db->query($sql, __FILE__, __LINE__);
                $row = $this->db->fetch_array($result);
                $_SESSION['api_unitno'] = $row['unitno'];
                header('Location: eventhistory.php');
            } else {
                echo "Customer does not exist";
            }
        } else {
            echo "Request denied";
            die();
        }
    }

    function event_history_data($devicekey, $startdate, $enddate) {
        $customerno = $_SESSION['api_customer_id'];
        $location = "../../customer/" . $_SESSION['api_customer_id'] . "/unitno/" . $_SESSION['api_unitno'] . "/sqlite/" . date("Y-m-d", $_SESSION['startdate']) . ".sqlite";
        $col_array = array();
        if (file_exists($location)) {

            $path = "sqlite:$location";
            $db_sqllite = new PDO($path);
            if ($db_sqllite) {

                $query_sqllite = "SELECT * FROM unithistory WHERE lastupdated between '" . date("Y-m-d h:i:s", $_SESSION['startdate']) . "' and '" . date("Y-m-d", $_SESSION['startdate']) . " " . $_SESSION['endtime'] . "'";
                $result = $db_sqllite->query($query_sqllite);
                foreach ($result as $row) {
                    // time patch
                    $this->status_time = $row['lastupdated'];
                    // initialisation of a first row
                    if ($c == 0) {
                        $col_array[$i]['stattime'] = $this->status_time;
                        $col_array[$i]['loadstatus'] = $row['msgkey'];
                        //location query
                        $query_sqllite_loc = 'SELECT * FROM devicehistory WHERE id=' . $row['uhid'];
                        $result_loc = $db_sqllite->query($query_sqllite_loc);
                        if (isset($result_loc)) {
                            foreach ($result_loc as $row_loc) {
                                $col_array[$i]['lat'] = $row_loc['devicelat'];
                                $col_array[$i]['long'] = $row_loc['devicelong'];
                            }
                        }
                        // location query
                        $this->status = $row['msgkey'];
                    }
                    // counter condition for the first initailisation
                    $c++;
                    // change checks
                    if ($row['msgkey'] != $this->status) {
                        $col_array[$i]['endtime'] = $this->status_time;

                        //location query
                        $query_sqllite_loc = 'SELECT * FROM devicehistory WHERE id=' . $row['uhid'];
                        $result_loc = $db_sqllite->query($query_sqllite_loc);
                        if (isset($result_loc)) {
                            foreach ($result_loc as $row_loc) {
                                $col_array[$i]['lat'] = $row_loc['devicelat'];
                                $col_array[$i]['long'] = $row_loc['devicelong'];
                            }
                        }
                        // location query
                        // end of row
                        $i++;
                        //array end  init
                        $col_array[$i]['stattime'] = $this->status_time;
                        $col_array[$i]['loadstatus'] = $row['msgkey'];
                        $this->status = $row['msgkey'];
                    }
                }
                $col_array[$i]['endtime'] = $this->status_time;
                //location query
                $query_sqllite_loc = 'SELECT * FROM devicehistory WHERE id=' . $row['uhid'];
                $result_loc = $db_sqllite->query($query_sqllite_loc);
                if (isset($result_loc)) {
                    foreach ($result_loc as $row_loc) {
                        $col_array[$i]['lat'] = $row_loc['devicelat'];
                        $col_array[$i]['long'] = $row_loc['devicelong'];
                    }
                }
                // location query
            } else {
                die($err);
            }
        } else {

            echo "file not found";
        }
        ?>
        <table >
            <tr>
                <th>Start Time</th>
                <th>End time Time</th>
                <th>Location</th>
                <th>Start Time</th>
                <th>Duration </th>
            </tr>
        <?php
        foreach ($col_array as $row_changes) {
            ?>
                <tr>
                    <td> <?php echo $row_changes['stattime']; ?></td>
                    <td> <?php echo $row_changes['endtime']; ?></td>
                    <td>
            <?php
            if ($lat != '0' && $long != '0') {
                $API = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $row_changes['lat'] . "," . $row_changes['long'] . "";
                $location = json_decode(file_get_contents("$API&sensor=false"));
                @$address = $location->results[0]->formatted_address;
                if ($location->status == "OVER_QUERY_LIMIT") {
                    @$address = "Temporarily Unavailable";
                }
            }
            echo $address;
            ?> </td>
                    <td> <?php
                        if ($row_changes['loadstatus'] == 0) {
                            echo "done";
                        } else if ($row_changes['loadstatus'] == 1) {
                            echo "unloading";
                        } else if ($row_changes['loadstatus'] == 2) {
                            echo "loading";
                        }
                        ?></td>
                    <td><?php
                        $diff = (strtotime($row_changes['endtime']) - strtotime($row_changes['stattime']));
                        $this->dateDifference($diff);
                        ?></td>

                </tr>
            <?php
        }
        ?>
        </table>
            <?php
        }

        function dateDifference($diff) {
            $str;


            $years = floor($diff / (365 * 60 * 60 * 24));
            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
            $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));

            $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
            $seconds = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60 - $minutes * 60));
            if ($days > 0) {
                $str = $years . " years";
            } else {
                if ($hours > 0) {
                    $str.= $hours . " hr " . $minutes . " min ";
                } elseif ($minutes > 0) {
                    $str.= $minutes . " min ";
                } else {
                    $str.= $seconds . " sec ago";
                }
            }
            echo $str;
        }

        function event_login_access_handler($skey) {
            //$skey=stripslashes(trim($skey));
            if ($skey != "") {
                $sql1 = "select * from " . TBL_ADMIN_USER . " where 1 and userkey='" . $skey . "'";
                $result1 = $this->db->query($sql1, __FILE__, __LINE__);
                $row1 = $this->db->fetch_array($result1);
                if ($row1['customerno'] != "") {
                    $_SESSION['customerno'] = $row1['customerno'];
                    $_SESSION['username'] = $row1['username'];
                    $_SESSION['realname'] = $row1['realname'];
                    $_SESSION['userid'] = $row1['userid'];
                    $_SESSION['Session_UserRole'] = $row1['role'];
                    $_SESSION['subdir'] = "/speed/";
                    // Setting session variables
                    $_SESSION["customercompany"] = $row1["customercompany"];
                    $_SESSION["visits_modal"] = $row1['visited'];
                    $_SESSION["role_modal"] = $row1['role'];

                    $_SESSION["sessionauth"] = $row1['role'];
                    $_SESSION["groupid"] = $row1['groupid'];
                    header('Location: http://speed.elixiatech.com/modules/realtimedata/realtimedata.php');
                } else {
                    echo "request denied";
                }
            }
        }

        function event_login_access_handler_unsub($skey) {
            //$skey=stripslashes(trim($skey));
            if ($skey != "") {
                $sql1 = "select *,user.customerno from " . TBL_ADMIN_USER . " INNER JOIN customer ON customer.customerno = user.customerno where 1";
                $result1 = $this->db->query($sql1, __FILE__, __LINE__);
                while ($row1 = $this->db->fetch_array($result1)) {
                    if (sha1($row1['userkey']) == $skey) {
                        $_SESSION['customerno'] = $row1['customerno'];
                        $_SESSION['username'] = $row1['username'];
                        $_SESSION['realname'] = $row1['realname'];
                        $_SESSION['userid'] = $row1['userid'];
                        $_SESSION['Session_UserRole'] = $row1['role'];
                        $_SESSION['subdir'] = "/speed/";
                        // Setting session variables
                        $_SESSION["customercompany"] = $row1["customercompany"];
                        $_SESSION["visits_modal"] = $row1['visited'];
                        $_SESSION["role_modal"] = $row1['role'];
                        //$_SESSION['Session_User'] = $user;
                        $_SESSION["sessionauth"] = $row1['role'];
                        $_SESSION["groupid"] = $row1['groupid'];

                        $log = new Log();
                        if ($log->createlog($_SESSION['customerno'], "Logged In", $_SESSION['userid'])) {

                        }
                        header('Location: http://www.speed.elixiatech.com/modules/user/accinfo.php?id=3');
                    }
                }
            }
        }

        function event_alerts() {
            for ($i = 0; $i <= 19; $i++) {
                $sql = "Select * from " . TBL_VEHICLE . " where isdeleted=0 and customerno=" . $i . " ";
                $result1 = $this->db->query($sql, __FILE__, __LINE__);
                while ($row = $this->db->fetch_array($result1)) {
                    $sql2 = "Select * from " . TBL_IGI . " where vehicleid=" . $row['vehicleid'] . " and customerno=" . $row['customerno'] . " ";
                    $result12 = $this->db->query($sql2, __FILE__, __LINE__);
                    $row2 = $this->db->fetch_array($result12);
                    if ($row2['igalertid'] == 0) {
                        $insert_sql_array = array();
                        $insert_sql_array['vehicleid'] = $row['vehicleid'];
                        $insert_sql_array['customerno'] = $row['customerno'];
                        echo "<pre>";
                        print_r($insert_sql_array);
                        echo "</pre>";
                        $this->db->insert(TBL_IGI, $insert_sql_array);
                    }
                }
            }
        }

        function updateLogin($userkey) {
            $today = date('Y-m-d H:i:s');
            $sql = "select * from " . TBL_ADMIN_USER . " where userkey='" . $userkey . "' AND isdeleted = 0";
            $record = $this->db->query($sql, __FILE__, __LINE__);
            $row = $this->db->fetch_array($record);
            if ($row['userkey'] != "") {
                $userid = $row['userid'];
                $customerno = $row['customerno'];
                $sqlInsert = "insert into login_history(userid, customerno,type,timestamp)values($userid,$customerno,1,'" . $today . "')";
                $this->db->query($sqlInsert, __FILE__, __LINE__);
            }
        }

        //</editor-fold>
    }
    ?>
