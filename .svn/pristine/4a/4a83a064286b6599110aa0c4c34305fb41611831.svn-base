<?php

require_once "database.inc.php";
require_once "reports.api.php";
date_default_timezone_set('Asia/Kolkata');

//define("CAIRO_TIMEZONE", "Africa/Cairo");

class VODevices {

}

class object {

}

define("SP_GET_ODOMETER_READING", "get_odometer_reading");
define("SP_CHECK_VEHICLE_USER_MAPPING", "check_vehicle_user_mapping");
define("SP_INSERT_SMSLOG", "insert_smslog");

class api {

    const PER_SMS_CHARACTERS = 160;

    static $SMS_TEMPLATE_FOR_VEHICLE_USER_DRIVER_MAPPING = "Dear {{USERNAME}}, {{VEHICLENO}} has been allotted for your pickup. Driver Name: {{DRIVERNAME}} ({{DRIVERPHONE}})";
    static $SMS_TEMPLATE_FOR_QUICK_SHARE = "Vehicle No: {{VEHICLENO}}\r\nLocation: {{LOCATION}}\r\nShared by: {{USERNAME}}";
    var $status;
    var $status_time;

//<editor-fold defaultstate="collapsed" desc="Constructor">
// construct
    function __construct() {
        $this->db = new driverdatabase(DB_HOST, DB_PORT, DB_LOGIN, DB_PWD, SPEEDDB);
    }

// </editor-fold>
//
//<editor-fold defaultstate="collapsed" desc="API functions">
// checks for login

    function get_current_slot_details($customerno) {
        $slots = Array();
        $date = date('H:i');
        $SQL = "select customer_slot_id, date_format(start_time, '%H:%i') as start, date_format(end_time, '%H:%i') as end
            from " . DB_DELIVERY . ".slot_master where customerno=$customerno and isdeleted=0 ";
        $record = $this->db->query($SQL, __FILE__, __LINE__);
        $rowcount = $this->db->num_rows($record);
        if ($rowcount > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $slots[] = array(
                    'slot_id' => $row['customer_slot_id'],
                    'timing' => $row['start'] . ' - ' . $row['end'],
                    'start_time' => $row['start'],
                    'end_time' => $row['end']
                );
            }
        }
        return $slots;
    }

    function check_login_driver($username, $password) {

        $retarray = array();
        if (trim($username) == "" || trim($password) == "") {
            $retarray['status'] = "failure";
            $retarray['customername'] = null;
            $retarray['vehicleno'] = null;
            $retarray['userkey'] = 0;
        } else {
            $sql = sprintf("SELECT *,vehicle.vehicleno,customer.customercompany FROM driver
                INNER JOIN vehicle ON vehicle.vehicleid=driver.vehicleid
                INNER JOIN customer ON customer.customerno=driver.customerno
                WHERE username= '%s' AND driver.isdeleted=0 limit 1", $username);
            $record = $this->db->query($sql, __FILE__, __LINE__);
            $row = $this->db->fetch_array($record);
// print_r($row);
            if ($username == $row['username'] && $password == $row['password']) {
                $retarray['status'] = "successful";
                $retarray['userkey'] = $row['userkey'];
                $retarray['customerno'] = $row['customerno'];
                $retarray['customercompany'] = $row['customercompany'];
                $retarray['username'] = $row['username'];
                $retarray['vehicleno'] = $row['vehicleno'];
                $retarray['vehicleid'] = $row['vehicleid'];
                $retarray['drivername'] = $row['drivername'];
                $retarray['istripstarted'] = $row['istripstarted'];
            } else {
                $retarray['status'] = "failure";
                $retarray['customername'] = null;
                $retarray['vehicleno'] = null;
                $retarray['userkey'] = 0;
            }
        }
        echo json_encode($retarray);
        return $retarray;
    }

    public function get_deliverydriver_details($username, $password) {
        $SQL = "SELECT u.userid,u.delivery_vehicleid,u.customerno,u.userkey,u.role,u.realname,c.customercompany FROM user as u  left join customer as c on c.customerno = u.customerno  WHERE u.`roleid` IN (11,47) AND u.`isdeleted`=0  AND u.`username`='" . $username . "' and u.`password`='" . $password . "'";
        $record = $this->db->query($SQL, __FILE__, __LINE__);
        $rowcount = $this->db->num_rows($record);

        if ($rowcount > 0) {
            $adv_amt = '';
            while ($row = $this->db->fetch_array($record)) {
                if ($row['role'] == "Driver") {
                    $userkey = $row['userkey'];
                    $adv_amt = $this->getDriver_advamt1($userkey);
                }
                $data = array(
                    'userkey' => $row['userkey'],
                    'realname' => $row['realname'],
                    'customercompany' => $row['customercompany'],
                    'role' => $row['role'],
                    'customerno' => $row['customerno'],
                    'userid' => $row['userid'],
                    'driver_amt' => $adv_amt,
                    'delivery_vehicleid' => $row['delivery_vehicleid'],
                    'status' => 'successful'
                );
            }

            return $data;
        }
        $data = array('status' => 'unsuccessful');
        return $data;
    }

    public function getDriver_advamt($userkey, $validation) {
        $advamt = 0;
        $data = array();
        if (isset($validation['driverid']) && !empty($validation['driverid'])) {
            $advamt = $this->get_all_totalfund($validation['driverid']);
            $spentamt = $this->get_all_spend_amount($validation['driverid']);
            $tripstatus = $this->checktripstatus($userkey);
            $data = array(
                'advamt' => $advamt,
                'spendamt' => $spentamt,
                'tripstatus' => $tripstatus
            );
        }
        return $data;
    }

    public function getDriver_advamt1($userkey) {
        $advamt = 0;
        $data = array();
        $validation = $this->check_userkey($userkey);
        if (isset($validation['driverid'])) {
            $advamt = $this->get_all_totalfund($validation['driverid']);
            $spentamt = $this->get_all_spend_amount($validation['driverid']);

            $data = array(
                'advamt' => $advamt,
                'spendamt' => $spentamt
            );
        }
        return $data;
    }

    public function getDriver_advamt_add($userkey, $details = null, $amount, $validation) {
        $data = array();
        $customerno = $validation["customerno"];
        $userid = $validation["userid"];
        $driverid = $validation["driverid"];
        $timestamp = date('Y-m-d H:i:s');
        $Query = " INSERT INTO " . DB_DELIVERY . ".expense_fund(userid,driverid,fundamount,advance_details, customerno, entrytime,addedby) "
                . " values($userid,$driverid,$amount,'" . $details . "',$customerno,'" . $timestamp . "',$userid) ";
        $this->db->query($Query, __FILE__, __LINE__);

        $advamt = $this->get_all_totalfund($validation['driverid']);
        $spentamt = $this->get_all_spend_amount($validation['driverid']);
        $data = array(
            'advamt' => $advamt,
            'spendamt' => $spentamt
        );
        return $data;
    }

    public function reset_expense($userkey, $validation) {
        $customerno = $validation["customerno"];
        $userid = $validation["userid"];
        $driverid = $validation["driverid"];
        $timestamp = date('Y-m-d H:i:s');
        $Query = " INSERT INTO " . DB_DELIVERY . ".expense_reset(userid,driverid,resettime, customerno, created_on,created_by) "
                . " values($userid,$driverid,'" . $timestamp . "',$customerno,'" . $timestamp . "',$userid) ";
        $this->db->query($Query, __FILE__, __LINE__);
    }

    public function get_all_totalfund($driverid) {
        $amount = 0;
        $SQL = "select sum(fundamount) as amount from " . DB_DELIVERY . ".expense_fund where driverid=" . $driverid . " group by driverid ";
        $record = $this->db->query($SQL, __FILE__, __LINE__);
        $rowcount = $this->db->num_rows($record);
        if ($rowcount > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $amount = $row['amount'];
            }
        }
        return $amount;
    }

    public function get_all_spend_amount($driverid) {
        $spend_amount = 0;
        $resettime = '';
        $sql = "select resettime from " . DB_DELIVERY . ".expense_reset where driverid=" . $driverid . " order by resettime desc limit 1 ";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $rowcount = $this->db->num_rows($record);
        if ($rowcount > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $resettime = $row['resettime'];
            }
        }

        $SQL = "select sum(amount) as spend_amount from " . DB_DELIVERY . ".expense where driverid=" . $driverid . " AND DATE_FORMAT(expence_date, '%Y-%m-%d %H:%i:%s') < '" . $resettime . "' group by driverid ";
        $record = $this->db->query($SQL, __FILE__, __LINE__);
        $rowcount = $this->db->num_rows($record);
        if ($rowcount > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $spend_amount = $row['spend_amount'];
            }
        }
        return $spend_amount;
    }

// checks for login
// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Helper functions">
//find location
    function location($lat, $long, $customerno, $usegeolocation) {
//OVER_QUERY_LIMIT
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
                    $reportsObj = new reports();
                    $address = $reportsObj->get_location_bylatlong($lat, $long, $customerno);
                }
            } else {
                $reportsObj = new reports();
                $address = $reportsObj->get_location_bylatlong($lat, $long, $customerno);
            }
        }
        return $address;
    }

//calculate distance
    function distance($customerno, $unitno) {
        $totaldistance = 0;
        $today = date('y-m-d h:i:s');
        /* realtime-data distance calculation */
//Prepare parameters
        $this->db->next_result();
        $sp_params = "'" . $unitno . "'";
        $sp_params = $sp_params . "," . $customerno . ",'" . $today . "'";

        $queryCallSP = "CALL " . SP_GET_ODOMETER_READING . "($sp_params)";

        $records = $this->db->query($queryCallSP, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($records);

        $arrResult = array();
        if ($row_count > 0) {
            $arrResult = $this->db->fetch_array($records);
        }
        if (!empty($arrResult)) {
            $firstodometer = $arrResult['first_odometer'];
            $lastodometer = $arrResult['last_odometer'];

            if ($lastodometer < $firstodometer) {
                $lastodometer = $arrResult['max_odometer'] + $lastodometer;
            }
            $totaldistance = $lastodometer - $firstodometer;
            if (round($totaldistance) != 0) {
                $totaldistance = round(($totaldistance / 1000), 2);
            }
        }
// Free result set
        $records->close();
        $this->db->next_result();
        return $totaldistance;
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

    function checkforvalidity($customerno, $deviceid = null) {
        $devices = Array();
        $Query = "SELECT deviceid,expirydate, Now() as today FROM `devices` where customerno=%d ";
        if ($deviceid != null) {
            $Query .= " AND deviceid = $deviceid";
        }

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

    function check_validity_login($expirydate, $currentdate) {
        date_default_timezone_set("Asia/Calcutta");
        $expirytimevalue = '23:59:59';
        $expirydate = date('Y-m-d H:i:s', strtotime("$expirydate $expirytimevalue"));
        $realtime = strtotime($currentdate);
        $expirytime = strtotime($expirydate);
        $diff = $expirytime - $realtime;
        return $diff;
    }

    function check_userkey($userkey) {
        $sql = "select d.driverid,d.drivername,d.vehicleid,u.userid,u.realname,u.customerno,u.roleid,u.role,u.username,u.userkey,u.delivery_vehicleid from
                `user` as u left join `driver` as d  on  d.userkey = u.userkey and u.customerno=d.customerno
            where u.userkey='" . $userkey . "' AND u.isdeleted=0";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row = $this->db->fetch_array($record);
        $retarray = array();
        if ($row['userkey'] != "") {
            $retarray['status'] = "successful";
            $retarray['customerno'] = $row["customerno"];
            $retarray['driverid'] = $row["driverid"];
            $retarray['drivername'] = $row["drivername"];
            $retarray['username'] = $row["username"];
            $retarray['vehicleid'] = $row["vehicleid"];
            $retarray['userid'] = $row["userid"];
            $retarray['realname'] = $row["realname"];
            $retarray['role'] = $row["role"];
            $retarray['roleid'] = $row["roleid"];
            $retarray['delivery_vehicleid'] = $row["delivery_vehicleid"];
        } else {
            $retarray['status'] = "unsuccessful";
        }
        return $retarray;
    }

    public function get_person_details_by_key($userkey) {
        $SQL = "SELECT customerno, userid, role, roleid, delivery_vehicleid FROM user WHERE isdeleted = 0 and userkey = '%s'";
        $Query = sprintf($SQL, Validator::escapeCharacters($userkey));
        $this->_databaseManager->executeQuery($Query);
        if ($this->_databaseManager->get_rowCount() > 0) {
            while ($row = $this->_databaseManager->get_nextRow()) {
                $data = array(
                    'customerno' => $row['customerno'],
                    'userid' => $row['userid'],
                    'role' => $row['role'],
                    'roleid' => $row['roleid'],
                    'vehicleid' => $row['delivery_vehicleid'],
                );
                return $data;
            }
        }
        return null;
    }

    function order_delivered($customerno, $orderid, $signature, $latitude, $longitude) {
        $time = date("Y-m-d H:i:s");
        $Query = "update master_orders set delivery_lat = '" . $latitude . "', delivery_long = '" . $longitude . "', delivery_time = '" . $time . "', is_delivered = 1 where customerno = " . $customerno . " and id = " . $orderid;
        $record = $this->db->query($Query, __FILE__, __LINE__);
//$row = $this->db->fetch_array($record);

        $Query = "update master_shipment set shipping_status = 5 where orderid = " . $orderid;
        $record = $this->db->query($Query, __FILE__, __LINE__);

        /**
          $signature='iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABGpJREFUeNqcVntIpFUUv/PNN1S+cs10dGQdfBer4msxZXAyTbPAVyxCDhiBQlIRlusfS8ksFESCk4oPrGQRFGfxRauJOgq6iMKmo7uOhoimZpkzPkbzMTPezhnmq2l2ppnpwuX7vnPP3N89v3PO7w6PUkpcGSaTScDn8w3gL+DxeAbixmBcdUSAo6MjP/I/hssgHR0dH+bm5qpHR0e/tbeu1+vFGCW+X11dCbh380C6nM3W1tZPY2Ji/hSLxbSxsfHSdn1zc/N1iURCZTIZXVhY+AhtFiDzulOA5ubm29HR0eeJiYkUnrStrW3f1ken0zEFBQVNwcHBFP16e3tV1ut2N4YkM/hsaGi4ExUVZQZITk7+Fwie9OTkJGR4ePg69zvwV4A/jYiIoH19fSOcn8MIFArFZ9YAKSkpT0UyMzMjDwwMfJSWlqaEXD2PNqVS+TEC4W80Go3MYSR1dXV3kaKkpCSzM/dEkJaWliPOD5LNyuVy88khZxSAhGivr6//MigoiJaXl1NgRcuDRCVtbGy8xLKsAcrUODs7K+3u7n7P09PzOYZhiHUfAT2kqqrq96Kiosytra0X4uPjp7BCOzs7cwHsAURFpqameOfn50HFxcW/rq2tEdhLx19cXHzc39//Dpzi7ZGRkVvLy8s3AQAb7qkyvby8JBkZGV6Dg4OVNTU1766vrz/Jy8t7EhcXp93b27s9Pj5O4GDrUGkPDQbDtaGhodSAgIAzBlCJr68v8fb2Nk8PD4//7BdI5Cc5OTlvxcbGLsPhPq+srLwLZl1ZWVmCv78/UalU99AvPT29y8/PjwBTCPwPJc4kBtcvLi6ekUqlD4AiKdBFxsbG7qyurt4MDw9fgJztbG9vk93dXUFkZOQsREHwm3FbIhjGhE845R9QeQ9PT0/Jzs5OuMX2m9FoJGdnZzfwG/JMDg8P3QeBaPj4hAZ8cX5+Ph0pDgkJ+RltWq1WhBsD5Y+5HGIqGOCY2EuyvYF+AoHgYnJy8s3S0tJJtVp9Iysri0BfPIIiiAPahCKRiAiFQgNUVvL+/j4egDCYaFBXrHlziWL43IYO1PhrqMIflpaWXs7Pz1fW1tYKLQKqxk0zMzPL8Xt6evrawcEBSUhIIGxTU9N16BMRnNAIG1/Nzc29BrXdjuC2fYLvQMF8dXX1rZKSEgkk/nu0QxG839XVpYJk/wIRdqDtPgwvLy+SnZ3tsOMLUBo4SeEmdjwo8k+c3/HxMQNNqAgLCzNBx/9osbPQ8d+gWFZUVFBIh9ahdoHYvYFSYQ1k0a6/QSDqD6DLKWiXnrP19PQEosygFK2srBQ6lXqgMts6ImsQOCEL+fMHpfgKJOZZtMFd8wX6A210YGDgO4uis65cWK9yEdnSZXWf8AsLCzVQWWY/UIJ7Tu8T29ne3p6BAKGhoRSi09i5GSXczQhlLbNddwkEJ5ToK6mpqfqJiQm5vXXobDFSyFFpvcZz9S8RDugnxsfHh4FSN7qjEm6BWHqFdRfkLwEGAFYS+tFIOBZTAAAAAElFTkSuQmCC';
         * */
        $target_path = "../../../../../customer/" . $customerno . "/routing/";
        if (!is_dir($target_path)) {
            mkdir($target_path, 0777, true) or die("Could not create directory");
        }

        $target_path_signature = $target_path . "signature/";

        if (!is_dir($target_path_signature)) {
            mkdir($target_path_signature, 0777, true) or die("Could not create directory");
        }

        $image = base64_decode($signature);
        file_put_contents("../../../../../customer/" . $customerno . "/routing/signature/" . $orderid . ".jpg", $image);
    }

    function push_photos($customerno, $driverid, $photos, $expid) {
//$photos='iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABGpJREFUeNqcVntIpFUUv/PNN1S+cs10dGQdfBer4msxZXAyTbPAVyxCDhiBQlIRlusfS8ksFESCk4oPrGQRFGfxRauJOgq6iMKmo7uOhoimZpkzPkbzMTPezhnmq2l2ppnpwuX7vnPP3N89v3PO7w6PUkpcGSaTScDn8w3gL+DxeAbixmBcdUSAo6MjP/I/hssgHR0dH+bm5qpHR0e/tbeu1+vFGCW+X11dCbh380C6nM3W1tZPY2Ji/hSLxbSxsfHSdn1zc/N1iURCZTIZXVhY+AhtFiDzulOA5ubm29HR0eeJiYkUnrStrW3f1ken0zEFBQVNwcHBFP16e3tV1ut2N4YkM/hsaGi4ExUVZQZITk7+Fwie9OTkJGR4ePg69zvwV4A/jYiIoH19fSOcn8MIFArFZ9YAKSkpT0UyMzMjDwwMfJSWlqaEXD2PNqVS+TEC4W80Go3MYSR1dXV3kaKkpCSzM/dEkJaWliPOD5LNyuVy88khZxSAhGivr6//MigoiJaXl1NgRcuDRCVtbGy8xLKsAcrUODs7K+3u7n7P09PzOYZhiHUfAT2kqqrq96Kiosytra0X4uPjp7BCOzs7cwHsAURFpqameOfn50HFxcW/rq2tEdhLx19cXHzc39//Dpzi7ZGRkVvLy8s3AQAb7qkyvby8JBkZGV6Dg4OVNTU1766vrz/Jy8t7EhcXp93b27s9Pj5O4GDrUGkPDQbDtaGhodSAgIAzBlCJr68v8fb2Nk8PD4//7BdI5Cc5OTlvxcbGLsPhPq+srLwLZl1ZWVmCv78/UalU99AvPT29y8/PjwBTCPwPJc4kBtcvLi6ekUqlD4AiKdBFxsbG7qyurt4MDw9fgJztbG9vk93dXUFkZOQsREHwm3FbIhjGhE845R9QeQ9PT0/Jzs5OuMX2m9FoJGdnZzfwG/JMDg8P3QeBaPj4hAZ8cX5+Ph0pDgkJ+RltWq1WhBsD5Y+5HGIqGOCY2EuyvYF+AoHgYnJy8s3S0tJJtVp9Iysri0BfPIIiiAPahCKRiAiFQgNUVvL+/j4egDCYaFBXrHlziWL43IYO1PhrqMIflpaWXs7Pz1fW1tYKLQKqxk0zMzPL8Xt6evrawcEBSUhIIGxTU9N16BMRnNAIG1/Nzc29BrXdjuC2fYLvQMF8dXX1rZKSEgkk/nu0QxG839XVpYJk/wIRdqDtPgwvLy+SnZ3tsOMLUBo4SeEmdjwo8k+c3/HxMQNNqAgLCzNBx/9osbPQ8d+gWFZUVFBIh9ahdoHYvYFSYQ1k0a6/QSDqD6DLKWiXnrP19PQEosygFK2srBQ6lXqgMts6ImsQOCEL+fMHpfgKJOZZtMFd8wX6A210YGDgO4uis65cWK9yEdnSZXWf8AsLCzVQWWY/UIJ7Tu8T29ne3p6BAKGhoRSi09i5GSXczQhlLbNddwkEJ5ToK6mpqfqJiQm5vXXobDFSyFFpvcZz9S8RDugnxsfHh4FSN7qjEm6BWHqFdRfkLwEGAFYS+tFIOBZTAAAAAElFTkSuQmCC';
        $target_path = "../../../../customer/" . $customerno . "/driver/";
        if (!is_dir($target_path)) {
            mkdir($target_path, 0777, true) or die("Could not create directory");
        }

        $target_path_signature = $target_path . "photos/$driverid";

        if (!is_dir($target_path_signature)) {
            mkdir($target_path_signature, 0777, true) or die("Could not create directory");
        }

        $files1 = scandir($target_path_signature);
        $image = base64_decode($photos);
        file_put_contents("../../../../customer/" . $customerno . "/driver/photos/" . $driverid . "/" . $expid . ".jpg", $image);
    }

    function order_cancelled($customerno, $orderid, $reasonid) {
        $timestamp = date('Y-m-d H:i:s');
        $Query = "update " . DB_DELIVERY . ".master_orders set iscanceled = 1 where customerno = " . $customerno . " and id = " . $orderid;
        $record = $this->db->query($Query, __FILE__, __LINE__);

        $Query = "update " . DB_DELIVERY . ".master_shipment set shipping_status = 7 where orderid = " . $orderid;
        $record = $this->db->query($Query, __FILE__, __LINE__);

        $sqlinsert = "insert into " . DB_DELIVERY . ".master_history(status, orderid, customerno, timestamp)values('$reasonid', '$orderid', '$customerno', '$timestamp') ";
        $this->_databaseManager->executeQuery($sqlinsert);
        $record = $this->db->query($Query, __FILE__, __LINE__);
    }

    public function get_curr_orders($customerno, $vehicleid, $cur_slot = '', $timing) {
        $collected = 0;
        $output = array();
        $dataset = Array();
        if ($timing == null) {
            $timing = "Not Defined";
        }
        $orders = Array();
        $date = date('Y-m-d');
//$date = '2016-04-02';
//$slot = ($cur_slot == '') ? '' : " and b.slot = $cur_slot ";
        $slot = "";
        if (!empty($cur_slot)) {
            $slot = " and b.slot = $cur_slot ";
        }
//$slot = ($cur_slot == '') ? '' : " and b.slot = 1 ";
        $Query = "SELECT a.sequence, a.order_id, b.slot, b.order_id as bill_no, b.lat, b.longi, b.accuracy, b.total_amount, b.reedeem_limit, b.iscanceled, b.is_delivered, c.landmark, d.areaname as area, c.address_main, c.full_name, c.flat, c.building, c.street, c.city, c.phone, c.pincode
        FROM order_route_sequence as a
        left join master_orders as b on a.order_id=b.id
        left join master_shipping_address as c on a.order_id=c.orderid
        left join area_master as d on b.customerno=d.customerno and b.fenceid=d.zone_id and b.areaid=d.area_id
        where b.customerno=$customerno and b.delivery_date='$date' and a.vehicle_id=$vehicleid and b.is_delivered=0 and b.iscanceled=0 $slot order by a.sequence asc";
        $record = $this->db->query($Query, __FILE__, __LINE__);
        $rowcount = $this->db->num_rows($record);
        if ($rowcount > 0) {
            while ($row = $this->db->fetch_array($record)) {
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
                    'area' => $row['area'],
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
                    'collected' => $collected
                );
            }
            $output = $orders;
        } else {
            $output = $orders;
        }
        return $output;
    }

    public function getPaidAmt($orderid) {
        $paidAmt = 0;
        $orders = Array();
        $Query = "select sum(amount) as paidamt from master_payment where orderid=" . $orderid;
        $record = $this->db->query($Query, __FILE__, __LINE__);
        $rowcount = $this->db->num_rows($record);
        if ($rowcount > 0) {
            $row = $this->db->fetch_array($record);
            if ($row['paidamt'] != null) {
                $paidAmt = $row['paidamt'];
            }
        }
        return $paidAmt;
    }

    function all_slots_refresh($customerno) {
        $json = Array();
        $sqlquery = "select * from " . DB_DELIVERY . ".slot_master WHERE customerno = $customerno AND isdeleted=0";
        $record = $this->db->query($sqlquery, __FILE__, __LINE__);
        $rowcount = $this->db->num_rows($record);
        if ($rowcount > 0) {
            while ($data = $this->_databaseManager->get_nextrow()) {
                $reason = new VODelivery();
                $reason->slot_no = $data['customer_slot_id'];
                $reason->slottime = date('h:i a', strtotime($data['start_time'])) . " - " . date('h:i a', strtotime($data['end_time']));
                $json[] = $reason;
            }
        }
        return $json;
    }

    function pullcategory($customerno) {
        $retarray = array();
        //$sql = "select * from " . DB_DELIVERY . ".master_category where customerno=" . $customerno . " AND isdeleted=0";
        $sql = "select * from " . DB_DELIVERY . ".master_category where customerno=" . $customerno . " AND isdeleted=0";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $rowcount = $this->db->num_rows($record);
        if ($rowcount > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $data[] = array(
                    "categoryid" => $row['categoryid'],
                    "categoryname" => $row['categoryname'],
                        //"customerno" => $row['customerno'],
                );
            }
            $retarray['status'] = "successful";
            $retarray['message'] = "";
            $retarray['result'] = $data;
        } else {
            $retarray['status'] = "unsuccessful";
            $retarray['message'] = "Category not found.";
        }
        echo json_encode($retarray);
        return $retarray;
    }

    function pullexpense($tripid, $customerno) {
        $alldata = array();
        $data = array();
        $expamt = array();
        $addexp = 0;
        $sql = "select e.tripid,e.categoryid,e.vehicleid,e.amount,e.expence_date,mc.categoryname,e.customerno from " . DB_DELIVERY . ".expense as e
            left join " . DB_DELIVERY . ".master_category  as mc on mc.categoryid = e.categoryid
            where e.tripid = " . $tripid . " AND  e.customerno=" . $customerno . " AND e.isdeleted=0";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $rowcount = $this->db->num_rows($record);
        if ($rowcount > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $expamt[] = $row['amount'];
                $data[] = array(
                    "categoryid" => $row['categoryid'],
                    "categoryname" => $row['categoryname'],
                    "customerno" => $row['customerno'],
                    "vehicleid" => $row['vehicleid'],
                    "amount" => $row['amount'],
                    "expense_date" => $row['expence_date'],
                    "tripid" => $row['tripid'],
                );
            }
            $addexp = array_sum($expamt);
        }
        $alldata = array($addexp, $data);

        return $alldata;
    }

    function checktripstatus($userkey) {
        $tripstatus = '1';
        $validation = $this->check_userkey($userkey);
        $today = date('Y-m-d H:i:s');
        $userid = $validation['userid'];
        $driverid = $validation['driverid'];
        $vehicleid = $validation['delivery_vehicleid'];
        $customerno = $validation['customerno'];
        $arr_p['result'] = '';
        $sql = "select * from " . DB_DELIVERY . ".trip where customerno=" . $customerno . " AND driverid =" . $driverid . " AND vehicleid=" . $vehicleid . " AND isdeleted=0 ORDER BY tripid DESC LIMIT 1";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $rowcount = $this->db->num_rows($record);
        if ($rowcount > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $tripstatus = $row['is_tripend'];
            }
        }
        return $tripstatus;
    }

    public function tripupdate($objReqDetails) {
        $arr_p = array();
        $userkey = $objReqDetails->userkey;
        $status = $objReqDetails->status;
        $validation = $this->check_userkey($userkey);
        $today = date('Y-m-d H:i:s');
        $userid = $validation['userid'];
        $driverid = $validation['driverid'];
        $vehicleid = $validation['delivery_vehicleid'];
        $customerno = $validation['customerno'];
        $arr_p['result'] = '';
        if ($validation['status'] == "successful") {
            if ($vehicleid == 0) {
                $arr_p['status'] = "unsuccessful";
                $arr_p['message'] = "Driver not mapped with vehicleno.";
            } else {
                //0-tripstart //tripend-1
                if ($status == 0) {
                    $sql = "select * from " . DB_DELIVERY . ".trip where customerno=" . $customerno . " AND driverid =" . $driverid . " AND is_tripend=0 AND vehicleid=" . $vehicleid . " AND isdeleted=0";
                    $record = $this->db->query($sql, __FILE__, __LINE__);
                    $rowcount = $this->db->num_rows($record);
                    if ($rowcount > 0) {
                        $arr_p['status'] = "unsuccessful";
                        $arr_p['message'] = "Trip already started you need to do end trip.";
                    } else {
                        $sqlinsert = "insert into " . DB_DELIVERY . ".trip(userid,driverid,vehicleid,tripstart,customerno,entrytime,addedby) "
                                . " values('$userid','$driverid','$vehicleid','" . $today . "','$customerno','" . $today . "','" . $userid . "') ";
                        $record = $this->db->query($sqlinsert, __FILE__, __LINE__);
                        $arr_p['status'] = "successful";
                        $arr_p['message'] = "Trip started sucessfully.";
                    }
                }
                if ($status == 1) {
                    $Query = "update " . DB_DELIVERY . ".trip set is_tripend=1, tripend='" . $today . "' where driverid =" . $driverid . " AND vehicleid=" . $vehicleid;
                    $record = $this->db->query($Query, __FILE__, __LINE__);
                    $arr_p['status'] = "successful";
                    $arr_p['message'] = "Trip end successfully";
                }
            }
        } else {
            $arr_p['status'] = "unsuccessful";
        }
        return $arr_p;
    }

    function starttrip($customerno, $validation) {
        $retarray = array();
        $today = date('Y-m-d H:i:s');
        $userid = $validation['userid'];
        $driverid = $validation['driverid'];
        $vehicleid = $validation['delivery_vehicleid'];

        $sqlinsert = "insert into " . DB_DELIVERY . ".trip(userid,driverid,vehicleid,tripstart,advance_amt,customerno,entrytime,addedby) "
                . " values('$userid','$driverid','$vehicleid','" . $today . "','" . $advamount . "','$customerno','" . $today . "','" . $userid . "') ";
        $record = $this->db->query($sqlinsert, __FILE__, __LINE__);
        $tripid = $this->db->last_insert_id();

        $data = array(
            "tripid" => $tripid,
        );
        $retarray['status'] = "successful";
        $retarray['result'] = $data;
        echo json_encode($retarray);
        return $retarray;
    }

    function endtrip($customerno, $validation, $tripid) {
        $retarray = array();
        $today = date('Y-m-d H:i:s');
        $userid = $validation['userid'];
        $driverid = $validation['driverid'];
        $vehicleid = $validation['vehicleid'];

        $Query = "update " . DB_DELIVERY . ".trip set is_tripend=1, tripend='" . $today . "' where tripid =" . $tripid;
        $record = $this->db->query($Query, __FILE__, __LINE__);
        $retarray['status'] = "successful";
        $retarray['message'] = "Trip end successfully";
        echo json_encode($retarray);
        return $retarray;
    }

    function saveexpense_details($validation, $amount, $categoryid, $newcategory = null, $date, $photo = null) {
        $retarray = array();
        $today = date('Y-m-d H:i:s');
        $date = date('Y-m-d', strtotime($date));
        $userid = $validation['userid'];
        $driverid = $validation['driverid'];
        $vehicleid = $validation['vehicleid'];
        $customerno = $validation['customerno'];

        if ($categoryid == 0) {
            $sqlinsert = "insert into " . DB_DELIVERY . ".master_category(categoryname,customerno,entrytime,addedby)  values('" . $newcategory . "',$customerno,'" . $today . "',$userid) ";
            $record = $this->db->query($sqlinsert, __FILE__, __LINE__);
            $categoryid = $this->db->last_insert_id();
        }
        $sqlexp = "insert into " . DB_DELIVERY . ".expense(categoryid,vehicleid,driverid,amount,expence_date,customerno,entrytime,addedby) "
                . " values('$categoryid','$vehicleid',$driverid,'" . $amount . "','" . $date . "','$customerno','" . $today . "','" . $userid . "') ";
        $record = $this->db->query($sqlexp, __FILE__, __LINE__);
        $expid = $this->db->last_insert_id();

        $amount = -$amount;
        $timestamp = date('Y-m-d H:i:s');
        $Query = " INSERT INTO " . DB_DELIVERY . ".expense_fund(userid,driverid,fundamount, customerno, entrytime,addedby) "
                . " values($userid,$driverid,$amount,$customerno,'" . $timestamp . "',$userid) ";
        $this->db->query($Query, __FILE__, __LINE__);

        $this->push_photos($customerno, $driverid, $photo, $expid);

        $retarray['status'] = "successful";
        $retarray['message'] = "Expense added sucessfully";
        echo json_encode($retarray);
        return $retarray;
    }

    function gettemp($rawtemp) {
        $temp = round((($rawtemp - 1150) / 4.45), 2);
        return $temp;
    }

    function gethumidity($rawtemp) {
        $temp = round(($rawtemp / 100), 2);
        return $temp;
    }

    function getDigitalTemp($rawValue) {
        $value = round(($rawValue / 100), 2);
        return $value;
    }

    function pull_groups($userkey) {
        $validation = $this->check_userkey($userkey);
        if ($validation['status'] == "successful") {
// successful
            $customerno = $validation["customerno"];
            if ($customerno == 97) {
                date_default_timezone_set(CAIRO_TIMEZONE);
            }
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

    function checkvalidity($expirydate) {
        $today = date('Y-m-d H:i:s');
//        $today = add_hours($today, 0);
        if (strtotime($today) <= strtotime($expirydate)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function update_push_android_chk($userkey, $val) {
        $sql = "UPDATE user SET chkmanpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    function update_push_android_chk_main($userkey, $val) {
        $sql = "UPDATE user SET chkpushandroid = " . $val . " WHERE userkey = '" . $userkey . "'";
        $this->db->query($sql, __FILE__, __LINE__);
    }

    function get_checkpoints($vehicleid) {
        $pdo = $this->db->CreatePDOConn();
        $Query = "SELECT checkpoint.checkpointid, checkpoint.cname, checkpoint.cgeolat, checkpoint.cgeolong, checkpoint.crad FROM checkpointmanage INNER JOIN checkpoint ON checkpoint.checkpointid = checkpointmanage.checkpointid WHERE checkpointmanage.isdeleted = 0 AND checkpointmanage.vehicleid = $vehicleid";
        $arrResult = $pdo->query($Query)->fetchAll(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
        $json_p = Array();
        $x = 0;
        foreach ($arrResult as $row) {
            $json_p[$x]['checkpointid'] = $row['checkpointid'];
            $x++;
        }
        return $json_p;
    }

    function get_checkpoint_customer($customerno, $userkey) {
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

    function get_checkpoint_customer_count($userkey) {
        $q = "SELECT chkpushandroid FROM user WHERE userkey = %s";
        $dq = sprintf($q, $userkey);
        $record = $this->db->query($dq, __FILE__, __LINE__);
        $p = 0;

        while ($row = $this->db->fetch_array($record)) {
            $p = $row['chkpushandroid'];
        }

        return $p;
    }

    function get_checkpoints_count($userkey) {
        $q = "SELECT chkmanpushandroid FROM user WHERE userkey = %s";
        $dq = sprintf($q, $userkey);
        $record = $this->db->query($dq, __FILE__, __LINE__);
        $p = 0;

        while ($row = $this->db->fetch_array($record)) {
            $p = $row['chkmanpushandroid'];
        }

        return $p;
    }

    function getvehicledetail($vehicleid, $customerno) {
        $sql = "SELECT v.vehicleid,v.uid, v.vehicleno,u.unitno,d.lastupdated,d.devicelat, d.devicelong
            FROM vehicle as v
            INNER JOIN devices as d ON d.uid = v.uid
            INNER JOIN unit as u ON d.uid = u.uid
            WHERE v.customerno='" . $customerno . "' AND v.isdeleted=0 AND v.vehicleid ='" . $vehicleid . "' ORDER BY d.lastupdated DESC";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $data = array();
        $rowcount = $this->db->num_rows($record);
        if ($rowcount > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $data = array(
                    "vehicleid" => $row['vehicleid'],
                    "devicelat" => $row['devicelat'],
                    "devicelong" => $row['devicelong'],
                    "uid" => $row['uid'],
                );
            }
            return $data;
        }
        return null;
    }

    function getCustomizeName($customerno, $customeid, $name) {
        $SQL = "SELECT customname FROM `customfield` where customerno=$customerno AND custom_id = $customeid AND `usecustom`=1";
        $record = $this->db->query($SQL, __FILE__, __LINE__);
        $rowcount = $this->db->num_rows($record);
        if ($rowcount > 0) {
            $row = $this->db->fetch_array($record);
            return $row['customname'];
        } else {
            return $name;
        }
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
                $str .= $hours . " hr " . $minutes . " min ";
            } elseif ($minutes > 0) {
                $str .= $minutes . " min ";
            } else {
                $str .= $seconds . " sec ago";
            }
        }
        echo $str;
    }

    function getacinvertval($unitno, $customerno) {
        $sql = "SELECT acsensor,is_ac_opp FROM unit
            WHERE customerno = $customerno AND unitno = $unitno";
        $record = $this->db->query($sql, __FILE__, __LINE__);
//$row = $this->db->fetch_array($record);
        while ($row = $this->db->fetch_array($record)) {
            $acopp['0'] = $row['is_ac_opp'];
            $acopp['1'] = $row['acsensor'];
            return $acopp;
        }

        return NULL;
    }

    function getspeedlimit($vehicleid) {
        $Query = "SELECT `overspeed_limit` FROM `vehicle` WHERE vehicleid=$vehicleid";
        $record = $this->db->query($Query, __FILE__, __LINE__);

        while ($row = $this->db->fetch_array($record)) {
            $vehicle = new VODevices();
            $vehicle->overspeed_limit = $row['overspeed_limit'];
            return $vehicle;
        }

        return null;
    }

    function getgroupname_byuid($uid) {
        $Query = "SELECT `group`.groupname FROM `vehicle` INNER JOIN `group` ON `group`.groupid = vehicle.groupid
            where vehicle.uid = '$uid'";
        $record = $this->db->query($Query, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($record)) {
            return $row['groupname'];
        }
        return "NA";
    }

    function get_all_vehicles($customerno, $vehicleid) {
        $vehicles = Array();
        $Query = 'SELECT *, devices.deviceid , driver.drivername'
                . ' FROM vehicle'
                . ' inner join devices on devices.uid = vehicle.uid '
                . ' left join driver on driver.driverid = vehicle.driverid'
                . ' WHERE vehicle.customerno=' . $customerno
                . ' AND vehicle.vehicleid=' . $vehicleid
                . ' AND vehicle.isdeleted=0';

        $record = $this->db->query($Query, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($record)) {
            $vehicle = new VODevices();
            $vehicle->vehicleid = $row['vehicleid'];
            $vehicle->uid = $row['uid'];
            $vehicle->extbatt = $row['extbatt'];
            $vehicle->odometer = $row['odometer'];
            $vehicle->lastupdated = $row['lastupdated'];
            $vehicle->curspeed = $row['curspeed'];
            $vehicle->driverid = $row['driverid'];
            $vehicle->vehicleno = $row["vehicleno"];
            $vehicle->drivername = $row['drivername'];
            $vehicle->deviceid = $row['deviceid'];
            return $vehicle;
        }
        return null;
    }

    function DailyReport($device, $date, $info, $overspeed_limit, $acinvertval, $acsensor) {
//Device Info
        $record['vehicleid'] = $device->vehicleid;
        $record['uid'] = $device->uid;
        $record['customerno'] = $device->customerno;

//Idle & Running Time Calculation
        $dat = '';
        $dat = array();
        foreach ($info as $inf) {
            if ($inf->condition == 1) {
                $dat[] = $inf;
            }
        }
        $enddat = end($info);
        $data = $this->processtraveldata($dat, $enddat);
        $display = $this->displaytraveldata($data);
//$utilizationtime = utilization($data);
        $record['runningtime'] = $display[0];
        $record['idletime'] = $display[1];

//Odometer Calculation
        $lastrow = end($info);
        $firstrow = $info[0];

        $lastodometer = $lastrow->odometer;
        $firstodometer = $firstrow->odometer;

        $last_lat = $lastrow->devicelat;
        $last_long = $lastrow->devicelong;
        if ($lastodometer < $firstodometer) {
            $max = $this->GetOdometer_Max($date, $device->unitno);
            $lastodometer = $max + $lastodometer;
        }
        $record['totaldistance'] = $lastodometer - $firstodometer;
        $record['totaldistanceKM'] = $lastodometer / 1000 - $firstodometer / 1000;
        if (isset($record['totaldistanceKM']) && $record['totaldistanceKM'] > 0) {
            if (isset($record['runningtime']) && $record['runningtime'] != 0) {
                $AverageSpeed = (int) ($record['totaldistanceKM'] / ($record['runningtime'] / 60));
            } else {
                $AverageSpeed = 0;
            }
        } else {
            $AverageSpeed = 0;
        }
        $record['averagespeed'] = $AverageSpeed;
        $acdat = '';
        $acdat = array();
        foreach ($info as $inf) {
            if ($inf->status != 'F') {
                $acdat[] = $inf;
            }
        }
        if ($acsensor == '1') {
            $record['gensetusage'] = gensetusage($acdat, $acinvertval);
        } else {
            $record['gensetusage'] = 0;
        }

//Tampering PowerCut Overspeed FenceConflict
        $times = $this->monitoring($device->vehicleid, $device->customerno, $info, $overspeed_limit);
        $record['overspeed'] = $times[0];
        $record['date'] = $date;
        $record['lat'] = $last_lat;
        $record['long'] = $last_long;
//print_r($record);
        return $record;
    }

    function ret_issetor(&$var) {
        return isset($var) ? true : false;
    }

    function monitoring($vehicleid, $custno, $deviceinfo, $overspeed_limit) {
//Statuses
        $tamper = 0;
        $powercut = 1;
        $overspeed = 0;

//Counters
        $tampercount = 0;
        $overspeedcount = 0;
        $powercutcount = 0;

        foreach ($deviceinfo as $device) {
            if ($device->tamper == 1 && $tamper == 0) {
                $tampercount += 1;
                $tamper = 1;
            } else if ($device->tamper == 0 && $tamper == 1) {
                $tamper = 0;
            }
            if ($device->powercut == 0 && $powercut == 0) {
                $powercutcount += 1;
                $powercut = 1;
            } else if ($device->powercut == 1 && $powercut == 1) {
                $powercut = 0;
            }
            if ($device->curspeed > $overspeed_limit && $overspeed == 0) {
                $overspeedcount += 1;
                $overspeed = 1;
            } else if ($device->curspeed <= $overspeed_limit && $overspeed == 1) {
                $overspeed = 0;
            }
        }

        $counters[0] = $overspeedcount;
        $counters[1] = $tampercount;
        $counters[2] = $powercutcount;

        return $counters;
    }

    function GetOdometer_Max($date, $unitno) {
        $date = substr($date, 0, 11);
        $customerno = $_SESSION['customerno'];
        $location = "../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        $ODOMETER = 0;
        if (file_exists($location)) {
            $path = "sqlite:$location";
            $db = new PDO($path);
            $query = "SELECT max(odometer) as odometerm from vehiclehistory";
            $result = $db->query($query);
            foreach ($result as $row) {
                $ODOMETER = $row['odometerm'];
            }
        }
        return $ODOMETER;
    }

    function processtraveldata($devicedata, $lastrow) {
//    print_r($lastrow);
        $count = count($devicedata);
        $devices2 = $devicedata;
        $data = Array();
        $datalen = count($devices2);
        if (isset($devices2) && count($devices2) > 1) {
            foreach ($devices2 as $device) {
                $datacap = new VODevices();
                $laststatus = $device->ignition;
                $datacap->ignition = $device->ignition;

                $ArrayLength = count($data);

                if ($ArrayLength == 0) {
//echo $firstidle = '1st starttime'.$device->lastupdated.'_'.$device->id.'<br>';
                    $datacap->starttime = $device->lastupdated;
                    $datacap->startlat = $device->devicelat;
                    $datacap->startlong = $device->devicelong;
                    $datacap->startodo = $device->odometer;
                } else if ($ArrayLength == 1) {
//Filling Up First Array --- Array[0]
//echo $firstidle = '1st starttime'.$device->lastupdated.'_'.$device->id.'<br>';
                    $data[0]->endlat = $device->devicelat;
                    $data[0]->endlong = $device->devicelong;
                    $data[0]->endtime = $device->lastupdated;
                    $data[0]->endodo = $device->odometer;
                    $data[0]->distancetravelled = $data[0]->endodo / 1000 - $data[0]->startodo / 1000;
                    $data[0]->duration = $this->getduration_dashboard($data[0]->endtime, $data[0]->starttime);

                    $datacap->starttime = $data[0]->endtime;
                    $datacap->startlat = $data[0]->endlat;
                    $datacap->startlong = $data[0]->endlong;
                    $datacap->startodo = $data[0]->endodo;
                    $datacap->endtime = $lastrow->lastupdated;
                    $datacap->endlat = $lastrow->devicelat;
                    $datacap->endlong = $lastrow->devicelong;
                    $datacap->endodo = $lastrow->odometer;
                } else {
                    $last = $ArrayLength - 1;
                    $data[$last]->endtime = $device->lastupdated;
                    $data[$last]->endlat = $device->devicelat;
                    $data[$last]->endlong = $device->devicelong;
                    $data[$last]->endodo = $device->odometer;

                    $data[$last]->duration = $this->getduration_dashboard($data[$last]->endtime, $data[$last]->starttime);

                    $data[$last]->distancetravelled = $data[$last]->endodo / 1000 - $data[$last]->startodo / 1000;

                    $datacap->starttime = $data[$last]->endtime;
                    $datacap->startlat = $data[$last]->endlat;
                    $datacap->startlong = $data[$last]->endlong;
                    $datacap->startodo = $data[$last]->endodo;

                    if ($datalen - 1 == $ArrayLength) {
                        $datacap->endtime = $lastrow->lastupdated;
                        $datacap->endlat = $lastrow->devicelat;
                        $datacap->endlong = $lastrow->devicelong;
                        $datacap->endodo = $lastrow->odometer;
                        $datacap->duration = $this->getduration_dashboard($datacap->endtime, $datacap->starttime);
                        $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
                        $datacap->ignition = $device->ignition;
                    }
                }
                $data[] = $datacap;
            }
            if ($data != NULL && count($data) > 0) {
                $optdata = $this->optimizereptime($data);
            }
            return $optdata;
        } else if (isset($devices2) && count($devices2) == 1) {
            $datacap = new VODevices();
            $datacap->starttime = $devices2[0]->lastupdated;
            $datacap->startlat = $devices2[0]->devicelat;
            $datacap->startlong = $devices2[0]->devicelong;
            $datacap->startodo = $devices2[0]->odometer;
            $datacap->endtime = $lastrow->lastupdated;
            $datacap->endlat = $lastrow->devicelat;
            $datacap->endlong = $lastrow->devicelong;
            $datacap->endodo = $lastrow->odometer;
            $datacap->duration = $this->getduration_dashboard($datacap->endtime, $datacap->starttime);
            $datacap->distancetravelled = $datacap->endodo / 1000 - $datacap->startodo / 1000;
            $datacap->ignition = $devices2[0]->ignition;
            $data[] = $datacap;

            return $data;
        }
    }

    function displaytraveldata($datarows) {
        $runningtime = 0;
        $idletime = 0;
        if (isset($datarows)) {
            foreach ($datarows as $change) {

//Removing Date Details From DateTime
                $change->starttime = substr($change->starttime, 11);
                $change->endtime = substr($change->endtime, 11);

                $hour = floor(($change->duration) / 60);
                $minutes = ($change->duration) % 60;
                if ($change->ignition == 1) {
                    $runningtime += $minutes + ($hour * 60);
                } else {
                    $idletime += $minutes + ($hour * 60);
                }
            }
        }

        $utilizationtime[0] = $runningtime;
        $utilizationtime[1] = $idletime;
        return $utilizationtime;
    }

    function getduration_dashboard($EndTime, $StartTime) {
        $diff = strtotime($EndTime) - strtotime($StartTime);
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        return $hours * 60 + $minutes;
    }

    function optimizereptime($data) {
        $datarows = array();
        $ArrayLength = count($data);
        $currentrow = $data[0];
        $a = 0;
        while ($currentrow != $data[$ArrayLength - 1]) {
            $i = 1;
            while (($i + $a) < $ArrayLength - 1 && $data[$i + $a]->duration < 3) {
                $i += 2;
            }
            $currentrow->endtime = $data[$i + $a - 1]->endtime;
            $currentrow->endlat = $data[$i + $a - 1]->endlat;
            $currentrow->endlong = $data[$i + $a - 1]->endlong;
            $currentrow->endodo = $data[$i + $a - 1]->endodo;
            $currentrow->duration = $this->getduration_dashboard($currentrow->endtime, $currentrow->starttime);
            $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
            $datarows[] = $currentrow;
            if (($a + $i) <= $ArrayLength - 1) {
                $currentrow = $data[$i + $a];
            }
            $a += $i;
            if (($a) >= $ArrayLength - 1) {
                $currentrow = $data[$ArrayLength - 1];
            }
        }
        if ($datarows != NULL) {
            $checkop = end($datarows);
            $checkup = end($data);
            if ($checkop->endtime != $checkup->endtime) {
                $currentrow->starttime = $checkop->endtime;
                $currentrow->startlat = $checkop->endlat;
                $currentrow->startlong = $checkop->endlong;
                $currentrow->startodo = $checkop->endodo;

                $currentrow->endtime = $checkup->endtime;
                $currentrow->endlat = $checkup->endlat;
                $currentrow->endlong = $checkup->endlong;
                $currentrow->endodo = $checkup->endodo;
                $currentrow->duration = $this->getduration_dashboard($currentrow->endtime, $currentrow->starttime);
                $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;

                $datarows[] = $currentrow;
            }
        } else {
            $currentrow = end($data);
            $currentrow->endlat = $currentrow->startlat;
            $currentrow->endlong = $currentrow->startlong;
            $currentrow->endtime = date('Y-m-d', strtotime($currentrow->starttime));
            $currentrow->endtime .= " 23:59:59";
            $currentrow->endodo = $currentrow->startodo;

            $currentrow->duration = $this->getduration_dashboard($currentrow->endtime, $currentrow->starttime);
            $currentrow->distancetravelled = $currentrow->endodo / 1000 - $currentrow->startodo / 1000;
            $datarows[] = $currentrow;
        }
        return $datarows;
    }

    function DataFromSqlite($location) {
        $PATH = "$location";
        $Query = 'select * from devicehistory';
        $Query .= ' INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated';
        $Query .= ' INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = unithistory.lastupdated group by devicehistory.lastupdated';
        $DRMS = array();
        $lastig;
        try {
            $db = new PDO($PATH);
            $result = $db->query($Query);
            $firt_ll_set = 0;
            foreach ($result as $row) {
                $DRM = new VODevices();

                if (!isset($lastig) || $lastig != $row['ignition']) {
                    $DRM->condition = 1;
                } else {
                    $DRM->condition = 0;
                }
//Unit Part
                if ($firt_ll_set == 0) {
                    $DRM->first_dev_lat = $row['devicelat'];
                    $DRM->first_dev_long = $row['devicelong'];
                    $firt_ll_set = 1;
                }

                $DRM->uhid = $row['uhid'];
                $DRM->uid = $row['uid'];
                $DRM->unitno = $row['unitno'];
                $DRM->customerno = $row['customerno'];
                $DRM->vehicleid = $row['vehicleid'];
                $DRM->analog1 = $row['analog1'];
                $DRM->analog2 = $row['analog2'];
                $DRM->analog3 = $row['analog3'];
                $DRM->analog4 = $row['analog4'];
                $DRM->digitalio = $row['digitalio'];
                $DRM->lastupdated = $row['lastupdated'];
                $DRM->dhid = $this->ret_issetor($row['dhid']);
                $DRM->vhid = $this->ret_issetor($row['vhid']);

//VehiclePart
                $DRM->vehiclehistoryid = $row['vehiclehistoryid'];
                $DRM->vehicleid = $row['vehicleid'];
                $DRM->vehicleno = $row['vehicleno'];
                $DRM->devicekey = $this->ret_issetor($row['devicekey']);
                $DRM->extbatt = $row['extbatt'];
                $DRM->odometer = $row['odometer'];
                $DRM->curspeed = $row['curspeed'];
                $DRM->customerno = $row['customerno'];
                $DRM->driverid = $row['driverid'];

//DevicePart
                $DRM->devicehistoryid = $row['id'];
                $DRM->deviceid = $row['deviceid'];
                $DRM->customerno = $row['customerno'];
                $DRM->devicelat = $row['devicelat'];
                $DRM->devicelong = $row['devicelong'];
                $DRM->devicekey = $row['devicekey'];
                $DRM->altitude = $row['altitude'];
                $DRM->directionchange = $row['directionchange'];
                $DRM->inbatt = $row['inbatt'];
                $DRM->hwv = $row['hwv'];
                $DRM->swv = $row['swv'];
                $DRM->msgid = $row['msgid'];
                $DRM->uid = $row['uid'];
                $DRM->status = $row['status'];
                $DRM->ignition = $row['ignition'];
                $DRM->powercut = $row['powercut'];
                $DRM->tamper = $row['tamper'];
                $DRM->gpsfixed = $row['gpsfixed'];
                $DRM->online_offline = $row['online/offline'];
                $DRM->gsmstrength = $row['gsmstrength'];
                $DRM->gsmregister = $row['gsmregister'];
                $DRM->gprsregister = $row['gprsregister'];
                $lastig = $row['ignition'];
                $DRMS[] = $DRM;
            }
        } catch (PDOException $e) {
            $DRMS = 0;
        }
        return $DRMS;
    }

    function getGroupname($groupid, $customerno) {
        $list = array();
        $Query = "SELECT groupid,groupname,cityid,code,address FROM `group` where customerno=$customerno AND groupid=$groupid AND isdeleted=0";

        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $listitem[] = $row;
            }

            foreach ($listitem as $row1) {
//print_r($row1);

                $group = new object();
                $group->groupid = $row1['groupid'];
                $group->groupname = $row1['groupname'];
                $group->cityid = $row1['cityid'];
                $group->code = $row1['code'];
                $group->address = $row1['address'];
                $list[] = $group;
            }

            return $list;
        }
        return null;
    }

    function get_vehicle($vehicleid, $customerno) {
        $Query = "SELECT vehicle.vehicleno
                    , driver.drivername
                    , driver.driverphone
                    , devices.deviceid
                    , devices.devicelat
                    , devices.devicelong
                    FROM    vehicle
                    inner join devices on devices.uid=vehicle.uid
                    left join driver on driver.driverid=vehicle.driverid
                    WHERE   vehicle.customerno =$customerno
                    AND     vehicle.vehicleid=$vehicleid
                    AND     vehicle.isdeleted=0
                    group by vehicle.vehicleid";

        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $vehicle = new VODevices();
                $vehicle->vehicleno = $row['vehicleno'];
                $vehicle->drivername = $row['drivername'];
                $vehicle->driverphone = $row['driverphone'];
                $vehicle->deviceid = $row['deviceid'];
                $vehicle->devicelat = $row['devicelat'];
                $vehicle->devicelong = $row['devicelong'];
            }
            return $vehicle;
        }
        return null;
    }

    function getduration1($StartTime) {
        $EndTime = date('Y-m-d H:i:s');
//                echo $EndTime.'_'.$StartTime.'<br>';
        $idleduration = strtotime($EndTime) - strtotime($StartTime);
        $years = floor($idleduration / (365 * 60 * 60 * 24));
        $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        if ($years >= '1' || $months >= '1') {
            $diff = date('d-m-Y', strtotime($StartTime));
        } else if ($days > 0) {
            $diff = $days . ' days ' . $hours . ' hrs ago';
        } else if ($hours > 0) {
            $diff = $hours . ' hrs and ' . $minutes . ' mins ago';
        } else if ($minutes > 0) {
            $diff = $minutes . ' mins ago';
        } else {
            $seconds = strtotime($EndTime) - strtotime($StartTime);
            $diff = $seconds . ' sec ago';
        }
        return $diff;
    }

    function gettemplist($rawtemp, $use_humidity) {
        if ($use_humidity == 1) {
            $temp = round($rawtemp / 100);
        } else {
            $temp = round((($rawtemp - 1150) / 4.45), 1);
        }
        return $temp;
    }

    function gettripdetails($vehicleid, $customerno) {
//api call this function
        $data = array();
        $query = "select t.tripid"
                . ",t.statusdate"
                . ",t.vehicleno"
                . ",ts.tripstatus"
                . ",t.vehicleid"
                . ",t.triplogno"
                . ",t.tripstatusid"
                . ",t.routename"
                . ",t.remark"
                . ",t.budgetedkms"
                . ",t.budgetedhrs"
                . ",con.consigneename"
                . ",consr.consignorname"
                . ",t.billingparty"
                . ",t.mintemp"
                . ",t.maxtemp"
                . ",t.drivername"
                . ",t.drivermobile1"
                . ",t.drivermobile2"
                . ",t.entrytime "
                . ",t.is_tripend "
                . " from tripdetails as t "
                . " left join tripstatus as ts on ts.tripstatusid = t.tripstatusid "
                . " left join tripconsignee as con  on con.consid = t.consigneeid "
                . " left join tripconsignor as consr  on consr.consrid = t.consignorid "
                . " where t.customerno=" . $customerno
                . " AND t.vehicleid='" . $vehicleid . "'"
                . " AND t.isdeleted=0"
                . " order by t.triplogno desc limit 1";

        $record = $this->db->query($query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);

        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                if (!is_null($row['statusdate'])) {
                    $statusdate = date("d-m-Y, h:i a", strtotime($row['statusdate']));
                } else {
                    $statusdate = 'Not Defined';
                }
                $data = array(
                    "tripid" => $row['tripid']
                    , "status" => $statusdate
                    , "vehicleno" => $row['vehicleno']
                    , "tripstatus" => $row["tripstatus"]
                    , "vehicleid" => $row['vehicleid']
                    , "triplogno" => $row['triplogno']
                    , "tripstatusid " => $row['tripstatusid']
                    , "routename" => $row['routename']
                    , "remark" => $row['remark']
                    , "budgetedkms" => $row['budgetedkms'] . " / "
                    , "budgetedhrs" => $row['budgetedhrs'] . " / "
                    , "consignee" => $row['consigneename']
                    , "consignor" => $row['consignorname']
                    , "billingparty" => $row['billingparty']
                    , "mintemp" => $row['mintemp']
                    , "maxtemp" => $row['maxtemp']
                    , "temprange" => floor($row['mintemp']) . " C To " . floor($row['maxtemp']) . " C "
                    , "drivername" => $row['drivername']
                    , "drivermobile1" => $row['drivermobile1']
                    , "drivermobile2" => $row['drivermobile2']
                    , "entrytime" => $row['entrytime']
                    , "is_tripend" => $row['is_tripend'],
                );
            }
            return $data;
        } else {
            $data = array(
                "triplogno" => "Not Defined",
                "status" => "Not Defined",
                //"tripstatusid " => $row['tripstatusid'],
                "routename" => "Not Defined",
                "budgetedkms" => "Not Defined",
                "budgetedhrs" => "Not Defined",
                "actualkms" => "Not Defined",
                "actualhrs" => "Not Defined",
                "consignor" => "Not Defined",
                "consignee" => "Not Defined",
                "billingparty" => "Not Defined",
                "temprange" => "Not Defined",
                "drivername" => "Not Defined",
                "drivermobile1" => "Not Defined",
                "drivermobile2" => "Not Defined",
                "tripid" => "Not Defined",
                "remark" => "Not Defined",
            );
            return $data;
        }
        return NULL;
    }

    function getduration_digitalio($StartTime, $EndTime1) {
        $EndTime = date('Y-m-d H:i:s', strtotime($EndTime1));
        $idleduration = strtotime($EndTime) - strtotime($StartTime);
        $years = floor($idleduration / (365 * 60 * 60 * 24));
        $months = floor(($idleduration - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($idleduration - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        if ($years >= '1' || $months >= '1') {
            $diff = date('d-m-Y', strtotime($StartTime));
        } else if ($days > 0) {
            $diff = $days . ' days ' . $hours . ' hrs ';
        } else if ($hours > 0) {
            $diff = $hours . ' hrs and ' . $minutes . ' mins ';
        } else {
            $diff = $minutes . ' mins ';
        }
        return $diff;
    }

    function DataForHumidity($path, $deviceid, $startdate, $enddate, $interval) {
        $devices = array();
        $dbs = new PDO($path);
        $query = "SELECT devicehistory.ignition, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong, unithistory.vehicleid,unithistory.digitalio,unithistory.analog1, unithistory.analog2, unithistory.analog3, unithistory.analog4,vehiclehistory.vehicleno
            from devicehistory
            INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated and vehiclehistory.uid=devicehistory.uid
            WHERE devicehistory.lastupdated >= '$startdate' AND devicehistory.lastupdated <= '$enddate' AND devicehistory.deviceid= $deviceid AND devicehistory.status!='F' group by devicehistory.lastupdated";
        $sobj = $dbs->prepare($query);
        $sobj->execute();
        /* Fetch all of the remaining rows in the result set */
        $result = $sobj->fetchAll();

        if (isset($result) && !empty($result)) {
            foreach ($result as $row) {
                if (!isset($lastupdated)) {
                    $lastupdated = $row['lastupdated'];
                }
                if (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= $interval) {
                    $device = new VODevices();
                    $humidity = 'Not Active';
                    $temp = 'Not Active';
//$humidity = getpressure($row['analog3']);
                    if ($row['analog4'] != '0') {
                        $humidity = $this->getDigitalTemp($row['analog4']);
                    } else {
                        $humidity = '-';
                    }
                    if ($row['analog3'] != '0') {
                        $temp = $this->getDigitalTemp($row['analog3']);
                    } else {
                        $temp = '-';
                    }
                    $device->humidity = $humidity;
                    $device->temperature = $temp;
                    $device->starttime = $row['lastupdated'];
                    $device->endtime = $row['lastupdated'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->digitalio = $row['digitalio'];
                    $devices[] = $device;
                    $lastupdated = $row['lastupdated'];
                }
            }
            return $devices;
        }
    }

    function DataForTemprature($path, $deviceid, $startdate, $enddate, $interval, $temp_sensors) {
        $devices = array();
        $dbs = new PDO($path);
        $query = "SELECT devicehistory.ignition, devicehistory.lastupdated, devicehistory.devicelat, devicehistory.devicelong, unithistory.vehicleid,unithistory.digitalio,unithistory.analog1, unithistory.analog2, unithistory.analog3, unithistory.analog4,vehiclehistory.vehicleno
            from devicehistory
            INNER JOIN unithistory ON unithistory.lastupdated = devicehistory.lastupdated
            INNER JOIN vehiclehistory ON vehiclehistory.lastupdated = devicehistory.lastupdated and vehiclehistory.uid=devicehistory.uid
            WHERE devicehistory.lastupdated >= '$startdate' AND devicehistory.lastupdated <= '$enddate' AND devicehistory.deviceid= $deviceid AND devicehistory.status!='F' group by devicehistory.lastupdated";
        $sobj = $dbs->prepare($query);
        $sobj->execute();
        /* Fetch all of the remaining rows in the result set */
        $result = $sobj->fetchAll();

        if (isset($result) && !empty($result)) {
            foreach ($result as $row) {
                if (!isset($lastupdated)) {
                    $lastupdated = $row['lastupdated'];
                }

                $temp = 'Not Active';
                $temp1 = 'Not Active';
                $temp2 = 'Not Active';
                $temp3 = 'Not Active';
                $temp4 = 'Not Active';

                if (round(abs(strtotime($row['lastupdated']) - strtotime($lastupdated)) / 60, 2) >= $interval) {
                    $device = new VODevices();
                    $humidity = 'Not Active';
                    $temp = 'Not Active';
//$humidity = getpressure($row['analog3']);
                    if ($row['analog4'] != '0') {
                        $humidity = $this->getDigitalTemp($row['analog4']);
                    } else {
                        $humidity = '-';
                    }
                    if ($row['analog3'] != '0') {
                        $temp = $this->getDigitalTemp($row['analog3']);
                    } else {
                        $temp = '-';
                    }
                    $device->humidity = $humidity;
                    $device->temperature = $temp;
                    $device->starttime = $row['lastupdated'];
                    $device->endtime = $row['lastupdated'];
                    $device->devicelat = $row['devicelat'];
                    $device->devicelong = $row['devicelong'];
                    $device->digitalio = $row['digitalio'];
                    $devices[] = $device;
                    $lastupdated = $row['lastupdated'];
                }
            }

            return $devices;
        }
    }

    function getSMSCount($customerno) {
        $smscount = 0;
        $pdo = $this->db->CreatePDOConn();
        $sqlSmsGet = sprintf("SELECT smsleft FROM customer WHERE customerno=%d", $customerno);
        $arrResult = $pdo->query($sqlSmsGet)->fetch(PDO::FETCH_ASSOC);
        $this->db->ClosePDOConn($pdo);
        if (count($arrResult) == 1) {
            $smscount = $arrResult['smsleft'];
        }
        return $smscount;
    }

    function updateSMSCount($existingSMSCount, $smsmessage, $customerno) {
        $smsconsumed = 0;
        $smsleft = 0;
        $smslength = strlen($smsmessage);
        $divide = floor($smslength / api::PER_SMS_CHARACTERS);
        $mod = $smslength % api::PER_SMS_CHARACTERS;
        if ($mod > 0) {
            $smsconsumed = $divide + 1;
        } else if ($mod == 0) {
            $smsconsumed = $divide;
        }
        $smsleft = $existingSMSCount - $smsconsumed;
        $pdo = $this->db->CreatePDOConn();
        $sqlSmsUpdate = sprintf("UPDATE customer SET smsleft=%d WHERE customerno=%d", $smsleft, $customerno);
        $pdo->query($sqlSmsUpdate);
        $this->db->ClosePDOConn($pdo);
    }

    function insertSMSLog($phone, $message, $response, $vehicleid, $userid, $customerno, $isSMSSent, $todaysdate, $moduleid) {
        $smsid = 0;
        $pdo = $this->db->CreatePDOConn();
        $sp_params = "'" . $phone . "'"
                . ",'" . $message . "'"
                . ",'" . $response . "'"
                . ",'" . $vehicleid . "'"
                . ",'" . $userid . "'"
                . ",'" . $customerno . "'"
                . ",'" . $isSMSSent . "'"
                . ",'" . $todaysdate . "'"
                . ",'" . $moduleid . "'"
                . "," . '@smsid';

        $queryCallSP = "CALL " . SP_INSERT_SMSLOG . "($sp_params)";
        $pdo->query($queryCallSP);
        $this->db->ClosePDOConn($pdo);
        $outputParamsQuery = "SELECT @smsid AS smsid";
        $outputResult = $pdo->query($outputParamsQuery)->fetch(PDO::FETCH_ASSOC);
        if (count($outputResult) > 0) {
            $smsid = $outputResult['smsid'];
        }
        return $smsid;
    }

    function check_vehicle_user_mapping($userid, &$mappedgroupid) {
        $isUserMappedToVehicle = 0;
//Prepare parameters
        $sp_params = "'" . $userid . "'"
                . "," . '@groupidparam' . ""
                . "," . '@isUserMappedToVehicle' . "";
        $queryCallSP = "CALL " . SP_CHECK_VEHICLE_USER_MAPPING . "($sp_params)";
        $this->db->query($queryCallSP, __FILE__, __LINE__);
        $outputParamQuery = "SELECT @isUserMappedToVehicle as isUserMappedToVehicle, @groupidparam as groupid";
        $outParamResult = $this->db->query($outputParamQuery, __FILE__, __LINE__);
        while ($row = $this->db->fetch_array($outParamResult)) {
            $isUserMappedToVehicle = $row['isUserMappedToVehicle'];
            $mappedgroupid = $row['groupid'];
        }
        return $isUserMappedToVehicle;
    }

    function PrepareHtmlData($Datacap, $customerno, $mail_type) {
        $reportsObj = new reports();
        $customer_details = $reportsObj->getcustomerdetail_byid($customerno);
        $reportHtmlData = "";
        $reportHtmlData .= "<html>";
        $reportHtmlData .= "<head>";
        $reportHtmlData .= "<style type='text/css'>
                                body{
                                    font-family:Arial;
                                    font-size: 11pt;
                                }
                                table{
                                    text-align: center;
                                    border-right:1px solid black;
                                    border-bottom:1px solid black;

                                    border-collapse:collapse;
                                    font-family:Arial;
                                    font-size: 10pt;
                                    width: 60%;
                                }
                            </style>";
        $reportHtmlData .= "</head>";
        $reportHtmlData .= "<body>";
        $title = 'Summary Report';
        $subTitle = array();
        if ($mail_type == 'pdf') {
            $reportHtmlData .= $reportsObj->pdf_header($title, $subTitle, $customer_details);
        } else if ($mail_type == 'xls') {
            $reportHtmlData .= $reportsObj->excel_header($title, $subTitle, $customer_details);
        } else {
            return;
        }

        $reportHtmlData .= "<table align='center' style='width: auto; font-size:13px; text-align:center;border-collapse:collapse; border:1px solid #000;'>";
        $reportHtmlData .= "<tr><td width:430px;>Vehicle No</td><td width:650px;>$Datacap->VehicleName</td></tr>";
        $reportHtmlData .= "<tr><td>Driver Name</td><td>$Datacap->DriverName</td></tr>";
        $reportHtmlData .= "<tr><td>Group</td><td>$Datacap->Group</td></tr>";
        $reportHtmlData .= "<tr><td>Start Location</td><td>$Datacap->SL</td></tr>";
        $reportHtmlData .= "<tr><td>End Location</td><td>$Datacap->EL</td></tr>";
        $reportHtmlData .= "<tr><td>Distance Travelled</td><td>$Datacap->DT</td></tr>";
        $reportHtmlData .= "<tr><td>Average Speed</td><td>$Datacap->AS</td></tr>";
        $reportHtmlData .= "<tr><td>Running Time</td><td>$Datacap->RT</td></tr>";
        $reportHtmlData .= "<tr><td>Overspeed</td><td>$Datacap->OS</td></tr>";
        $reportHtmlData .= "<tr><td>Top speed</td><td>$Datacap->TS</td></tr>";
        $reportHtmlData .= "<tr><td>Top speed location</td><td>$Datacap->TSL</td></tr>";
        $reportHtmlData .= "<tr><td>Harsh Break</td><td>$Datacap->HB</td></tr>";
        $reportHtmlData .= "<tr><td>Sudden Acceleration</td><td>$Datacap->SA</td></tr>";
        $reportHtmlData .= "<tr><td>Towing / Freewheeling</td><td>$Datacap->TO</td></tr>";
        $reportHtmlData .= "</table>";
        $reportHtmlData .= "</body>";
        $reportHtmlData .= "</html>";

        return $reportHtmlData;
    }

    public function closedtripdetails_end($tripid, $customerno) {
        $tripdata = array();
        $Query = "select odometer,statusdate from tripdetail_history  WHERE customerno=$customerno AND is_tripend = 1 AND tripstatusid = 10 AND  tripid = $tripid AND isdeleted=0 order by triphisid desc limit 0,1";
        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);

        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $tripdata[] = array(
                    'lasttripend_odometer' => $row['odometer'],
                    'tripend_date' => $row['statusdate'],
                );
            }
            return $tripdata;
        }
        return null;
    }

    public function closedtripdetails_start($tripid, $customerno) {
        $tripdata = array();
        $Query = "select odometer,statusdate from tripdetail_history WHERE customerno=$customerno AND tripstatusid = 3 AND  tripid = $tripid AND isdeleted=0 order by triphisid asc limit 0,1";
        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);

        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $tripdata[] = array(
                    'starttripend_odometer' => $row['odometer'],
                    'tripstart_date' => $row['statusdate'],
                );
            }
            return $tripdata;
        }
        return null;
    }

    public function getOdometer($location, $date) {
        $path = "sqlite:$location";
        $db = new PDO($path);
        $Query = "SELECT * FROM vehiclehistory where lastupdated >= '$date' Order by lastupdated ASC Limit 1 ";
        $result = $db->query($Query);
        if (isset($result) && $result != '') {
            foreach ($result as $row) {
                return $row['odometer'];
            }
        } else {
            return 0;
        }
    }

    public function getunitno($vehicleid) {
        $unitno = "";
        $Query = "select unitno from unit where vehicleid = $vehicleid";
        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $unitno = $row['unitno'];
            }
            return $unitno;
        }
        return null;
    }

    public function getodometerform_mysql($vehicleid, $customerno) {
        $odometer = "";
        $Query = "select odometer from vehicle where vehicleid=$vehicleid AND customerno=$customerno";
        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);

        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $odometer = $row['odometer'];
            }
        }
        return $odometer;
    }

    public function GetOdometerMax($date, $unitno, $customerno) {
        $date = substr($date, 0, 11);
        $location = "../../../customer/$customerno/unitno/$unitno/sqlite/$date.sqlite";
        $ODOMETER = 0;
        if (file_exists($location)) {
            $path = "sqlite:$location";
            $db = new PDO($path);
            $query = "SELECT max(odometer) as odometerm from vehiclehistory";
            $result = $db->query($query);
            foreach ($result as $row) {
                $ODOMETER = $row['odometerm'];
            }
        }
        return $ODOMETER;
    }

    public function getunitdetailsfromvehid($vehicleid, $customerno) {
        $Query = 'SELECT vehicle.fuel_min_volt, vehicle.fuel_max_volt, vehicle.fuelcapacity,vehicle.max_voltage,unit.fuelsensor, vehicle.temp1_min, vehicle.temp1_max, vehicle.temp2_min, vehicle.temp2_max,vehicle.temp3_min, vehicle.temp3_max,vehicle.temp4_min, vehicle.temp4_max, unit.unitno, unit.tempsen1, unit.tempsen2, unit.tempsen3, unit.tempsen4 FROM unit INNER JOIN vehicle ON vehicle.vehicleid = unit.vehicleid
            WHERE unit.customerno =' . $customerno . ' AND unit.vehicleid =' . $vehicleid;
        $record = $this->db->query($Query, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);

        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $unit = new stdClass();
                $unit->unitno = $row['unitno'];
                $unit->tempsen1 = $row['tempsen1'];
                $unit->tempsen2 = $row['tempsen2'];
                $unit->tempsen3 = $row['tempsen3'];
                $unit->tempsen4 = $row['tempsen4'];
                $unit->temp1_min = $row['temp1_min'];
                $unit->temp1_max = $row['temp1_max'];
                $unit->temp2_min = $row['temp2_min'];
                $unit->temp2_max = $row['temp2_max'];
                $unit->temp3_min = $row['temp3_min'];
                $unit->temp3_max = $row['temp3_max'];
                $unit->temp4_min = $row['temp4_min'];
                $unit->temp4_max = $row['temp4_max'];
                $unit->fuelsensor = $row['fuelsensor'];
                $unit->fuelcapacity = $row['fuelcapacity'];
                $unit->maxvoltage = $row['max_voltage'];
                $unit->fuel_min_volt = $row['fuel_min_volt'];
                $unit->fuel_max_volt = $row['fuel_max_volt'];
                return $unit;
            }
        }
        return NULL;
    }

    public function getcustomerdetails($customerno) {

        $sql = "select * from customer where customerno= '" . $customerno . "'";
        $record = $this->db->query($sql, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);

        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $cust = new stdClass();
                $cust->temp_sensors = $row['temp_sensors'];
                $cust->use_hierarchy = $row['use_hierarchy'];
                $cust->use_panic = $row['use_panic'];
                $cust->use_buzzer = $row['use_buzzer'];
                $cust->use_immobiliser = $row['use_immobiliser'];
                $cust->use_freeze = $row['use_freeze'];
                $cust->customername = $row['customername'];
                $cust->customerno = $row['customerno'];
                $cust->use_portable = $row['use_portable'];
                $cust->use_geolocation = $row['use_geolocation'];
                return $cust;
            }
        }
        return NULL;
    }

    public function getdatafromsqlite_newRefined($location, $Date, $vehicleid, $device, $holdtime, $distancetravelled, $unitno, $unit, $flag, $validation) {
        $userid = $validation['userid'];
        $customerno = $validation['customerno'];
        $roleid = $validation['roleid'];

        $basequery = "SELECT vehiclehistory.vehicleid,vehiclehistory.driverid,vehiclehistory.vehicleno,vehiclehistory.odometer, devicehistory.lastupdated, vehiclehistory.curspeed,devicehistory.deviceid, devicehistory.devicelong, devicehistory.devicelat, devicehistory.uid, devicehistory.ignition, devicehistory.status, devicehistory.directionchange,unithistory.analog1,unithistory.analog2,unithistory.analog3,unithistory.analog4
        FROM vehiclehistory
        LEFT OUTER JOIN devicehistory ON devicehistory.lastupdated = vehiclehistory.lastupdated
        LEFT OUTER JOIN unithistory ON unithistory.lastupdated = vehiclehistory.lastupdated ";
        $devicequery = "WHERE vehiclehistory.lastupdated BETWEEN '$Date[0]' AND '$Date[1]' ORDER BY vehiclehistory.lastupdated ASC";
        $database = new PDO($location);
        $result = $database->query($basequery . $devicequery);
        $drivers = $this->get_all_drivers_with_vehicles($validation);
        $counter_first = 0;
        if (isset($result)) {
            $lastdistance = 0;
            $hold_count = 0;
            $total_hold_time = 0;
            $lastdistance_acc = 0;
            $lastdistance_acc_diff = 0; // To Check the 40 Meter Diff
            $cumulative_dist = 0;
            if (isset($result) && $result != "") {
                foreach ($result as $row) {
                    $row['holdtime'] = 0;
                    if ($row['uid'] > 0) {
                        if ($row['devicelat'] > 0 && $row['devicelong'] > 0) {
                            if ($row['odometer'] < $lastdistance_acc_diff) {
                                $max = $this->GetOdometerMax($row['lastupdated'], $unitno, $customerno);
                                $row['odometer'] = $max + $row['odometer'];
                            }
                            $diffmeter = $row['odometer'] - $lastdistance_acc_diff; // To Check the 40 Meter Diff
                            if ($diffmeter > 40) {
// To Check the 40 Meter Diff
                                $cumulative_dist = $row['odometer'] - $lastdistance_acc + $distancetravelled;
                                $counter_first++;
                                $customerdetail = $this->getcustomerdetails($customerno);

                                if ($customerdetail->use_portable != '1') {
                                    if ($counter_first == 1) {
// condition for startpoint
                                        $total_hold_time = $row['lastupdated'];
                                        $lastdistance = $row['odometer'];
                                        $lastdistance_acc = $row['odometer'];
                                        if ($row['odometer'] < $lastdistance_acc) {
                                            $max = $this->GetOdometerMax($row['lastupdated'], $unitno, $customerno);
                                            $row['odometer'] = $max + $row['odometer'];
                                        }
                                        $cumulative_dist = $row['odometer'] - $lastdistance_acc + $distancetravelled;
                                        $row['total_hold_time'] = $total_hold_time;
                                        $device[] = $this->managerow_newRefined($validation, $row, $cumulative_dist, $unit, $flag);
                                    }
                                    if (($lastdistance_acc) < $row['odometer']) {
                                        $lastdistance = $row['odometer'];
                                        $row['total_hold_time'] = $total_hold_time;
                                        $device[] = $this->managerow_newRefined($validation, $row, $cumulative_dist, $unit, $flag);
                                    }
                                } else {
                                    $row['total_hold_time'] = $total_hold_time;
                                    $device[] = $this->managerow_newRefined($validation, $row, $cumulative_dist);
                                }
                            } else {
                                if (($row['odometer'] - $lastdistance) < 25) {
                                    if ($hold_count == 0) {
                                        $total_hold_time = $row['lastupdated'];
                                        $row_old = $row;
                                    }
                                    $hold_count++;
                                    $lastdistance = $row['odometer'];
                                }
                                if ($hold_count > 0 && ($row['odometer'] - $lastdistance) > 25) {
                                    $minus = strtotime($row['lastupdated']) - strtotime($total_hold_time);
                                    $minutes = floor(($minus) / 60);
                                    $row_old['total_hold_time'] = $minutes;

                                    if ($minutes > $holdtime) {
                                        $row_old['holdtime'] = 1;
                                        $row_old['devicelat'] = $row['devicelat'];
                                        $row_old['devicelong'] = $row['devicelong'];
                                        $device[] = $this->managerow_newRefined_hold($row_old, $cumulative_dist, $unit, $flag, $validation);
                                        $total_hold_time = $row['lastupdated'];
                                    }
                                    $hold_count = 0;
                                    $row_old = array();
                                }
                            } // To Check the 40 Meter Diff
                            $lastdistance_acc_diff = $row['odometer']; // To Check the 40 Meter Diff
                        }
                    }
                }
            }
        }
        return $device;
    }

    public function managerow_newRefined($validation, $row, $cumulative_dist, $unit, $flag) {
        $output = new stdClass();
        $output->devicelat = $row['devicelat'];
        $output->devicelong = $row['devicelong'];
        $output->location = "";
        $output->cumulative = $cumulative_dist;
        $output->curspeed = $row['curspeed'];
        $output->lastupdated = $row['lastupdated'];
        $output->status = $row['status'];
        $output->ignition = $row['ignition'];
        $output->holdtime = $row['holdtime'];
        $output->total_hold_time = $row['total_hold_time'];
        $output->test = $unit->unitno;
        if ($flag != 0) {
            $output->temp = $this->set_temp_graph_data($row['lastupdated'], $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], $flag, $validation);
        }
        $output->directionchange = round($row['directionchange'] / 10, 0);

        return $output;
    }

    public function get_all_drivers_with_vehicles($validation) {
        $userid = $validation['userid'];
        $customerno = $validation['customerno'];
        $roleid = $validation['roleid'];
        $heirarchy_id = $validation['heirarchy_id'];

        $groupid = 0;
        $drivers = Array();
        $Query = "SELECT *,driver.driverid FROM driver
            LEFT OUTER JOIN vehicle ON driver.vehicleid = vehicle.vehicleid
            LEFT OUTER JOIN `group` ON `group`.groupid = vehicle.groupid ";
        if ($roleid == '2') {
            $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid
                LEFT OUTER JOIN `district` ON `district`.districtid = city.districtid
                LEFT OUTER JOIN `state` ON `state`.stateid = district.stateid ";
        }
        if ($roleid == '3') {
            $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid
                LEFT OUTER JOIN `district` ON `district`.districtid = city.districtid ";
        }
        if ($roleid == '4') {
            $Query .= " LEFT OUTER JOIN `city` ON `city`.cityid = `group`.cityid ";
        }
        $Query .= " WHERE driver.customerno =%d AND driver.isdeleted=0";
        if ($groupid != 0) {
            $Query .= " AND vehicle.groupid =%d";
        }

        if ($groupid != 0) {
            $driversQuery = sprintf($Query, $customerno, $groupid);
        } else {
            $driversQuery = sprintf($Query, $customerno);
        }
        $heir_query = "";
        if ($roleid == '2') {
            $heir_query = sprintf(" AND state.stateid = %d ", $heirarchy_id);
        }
        if ($roleid == '3') {
            $heir_query = sprintf(" AND district.districtid = %d ", $heirarchy_id);
        }
        if ($roleid == '4') {
            $heir_query = sprintf(" AND city.cityid = %d ", $heirarchy_id);
        }
        $driversQuery .= $heir_query;
        $record = $this->db->query($driversQuery, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $driver = new stdClass();
                $driver->driverid = $row['driverid'];
                $driver->drivername = $row['drivername'];
                $driver->driverlicno = $row['driverlicno'];
                $driver->driverphone = $row['driverphone'];
                $driver->vehicleid = $row['vehicleid'];
                $driver->vehicleno = $row['vehicleno'];
                $drivers[] = $driver;
            }
            return $drivers;
        }
        return null;
    }

    public function set_temp_graph_data($updated_date, $unit, $analog1, $analog2, $analog3, $analog4, $flag, $validation) {
        $customerdetail = $this->getcustomerdetails($validation['customerno']);

        $temp_sensors = $customerdetail->temp_sensors;
        $temp = "";
        if ($temp_sensors == 1) {
            $s = "analog" . $unit->tempsen1;
            if ($unit->tempsen1 != 0 && $$s != 0) {
                $temp = gettemp($$s);
            } else {
                $temp = '-';
            }
        }

        /**/ elseif ($temp_sensors == 2) {
            $temp1 = 'Not Active';
            $temp2 = 'Not Active';
            $s = "analog" . $unit->tempsen1;
            if ($unit->tempsen1 != 0 && $$s != 0) {
                $temp1 = $this->gettemp($$s);
            } else {
                $temp1 = '-';
            }

            $s = "analog" . $unit->tempsen2;
            if ($unit->tempsen2 != 0 && $$s != 0) {
                $temp2 = $this->gettemp($$s);
            } else {
                $temp2 = '-';
            }

            if ($flag == 1) {
                $temp = (int) $temp1;
            } else {
                $temp = (int) $temp2;
            }
        }
        /**/

        return $temp;
    }

    public function managerow_newRefined_hold($row, $cumulative_dist, $unit, $flag, $validation) {
        $customer = $this->getcustomerdetails($validation['customerno']);
        $usegeolocation = $customer->use_geolocation;
        $output = new stdClass();
        $output->devicelat = $row['devicelat'];
        $output->devicelong = $row['devicelong'];
        $output->location = $this->location($output->devicelat, $output->devicelong, $validation['customerno'], $usegeolocation);
        $output->cumulative = $cumulative_dist;
        $output->curspeed = $row['curspeed'];
        $output->lastupdated = $row['lastupdated'];
        $output->status = $row['status'];
        $output->ignition = $row['ignition'];
        $output->holdtime = $row['holdtime'];
        $output->total_hold_time = $row['total_hold_time'];
        $output->test = $unit->unitno;
        if ($flag != 0) {
            $output->temp = $this->set_temp_graph_data($row['lastupdated'], $unit, $row['analog1'], $row['analog2'], $row['analog3'], $row['analog4'], $flag, $validation);
        }
        $output->directionchange = round($row['directionchange'] / 10, 0);
        return $output;
    }

    public function vehicleonmap_route_history($validation, $device) {
        $customerno = $validation['customerno'];
        $userid = $validation['userid'];
        $finaloutput = array();
        $length = count($device);
        $counter = 0;
        foreach ($device as $thisdevice) {
            if ($thisdevice == null) {
                break;
            }
            $counter++;
            $output = new stdClass();
            $date = new DateTime($thisdevice->lastupdated);
            $output->cgeolat = $thisdevice->devicelat;
            $output->cgeolong = $thisdevice->devicelong;
            $output->cspeed = $thisdevice->curspeed;
            $output->cignition = $thisdevice->ignition;
            $output->holdtime = $thisdevice->holdtime;
            $output->cumulative = $thisdevice->cumulative / 1000;
            $output->clastupdated = $date->format('D d-M-Y H:i');
            $output->directionchange = $thisdevice->directionchange;
            $output->total_hold_time = $thisdevice->total_hold_time;
            $output->location = $thisdevice->location;
            $output->test = $thisdevice->test;
            $output->temp = $thisdevice->temp;
            if ($userid != 391 && $userid != 392) {
                $output->users_spec = 0;
            } else {
                $output->users_spec = 1;
            }
            $finaloutput[] = $output;
        }
        return $finaloutput;
    }

    public function firstmappingforvehiclebydate_fromsqlite_newRefined($validation, $location, $Date) {
        $customerno = $validation['customerno'];
        $basequery = "SELECT vehiclehistory.vehicleid, vehiclehistory.driverid, vehiclehistory.vehicleno,vehiclehistory.odometer, devicehistory.lastupdated, vehiclehistory.curspeed,devicehistory.deviceid, devicehistory.devicelong, devicehistory.devicelat, devicehistory.uid, devicehistory.ignition, devicehistory.status, devicehistory.directionchange FROM `vehiclehistory` LEFT OUTER JOIN devicehistory ON devicehistory.lastupdated = vehiclehistory.lastupdated ";
        $devicequery = "WHERE vehiclehistory.lastupdated between '$Date[0]' and '$Date[1]' ORDER BY `vehiclehistory`.`lastupdated` ASC LIMIT 0,1";
        $database = new PDO($location);
        $result = $database->query($basequery . $devicequery);
        $drivers = $this->get_all_drivers_with_vehicles($validation);
        if (isset($result) && $result != "") {
            foreach ($result as $row) {
                if ($row['uid'] > 0) {
                    if ($row['devicelat'] > 0 && $row['devicelong'] > 0) {
                        $device = $this->managerow_newRefined($validation, $drivers, $row, $customerno);
                    }
                }
            }
        }
        return $device;
    }

    public function get_checkpoint_from_chkmanage($vehicleid, $validation) {
        $checkpoints = Array();
        $Query = "SELECT *,checkpointmanage.vehicleid as cvehicleid, checkpoint.checkpointid as ccheckpointid FROM `checkpoint`
            INNER JOIN checkpointmanage ON checkpointmanage.checkpointid = checkpoint.checkpointid
            WHERE checkpointmanage.vehicleid = %d and checkpoint.customerno=%d AND checkpoint.isdeleted=0 AND checkpointmanage.isdeleted=0";
        $checkpointsQuery = sprintf($Query, $vehicleid, $validation['customerno']);

        $record = $this->db->query($checkpointsQuery, __FILE__, __LINE__);
        $row_count = $this->db->num_rows($record);
        if ($row_count > 0) {
            while ($row = $this->db->fetch_array($record)) {
                $checkpoint = new stdClass();
                $checkpoint->checkpointid = $row['ccheckpointid'];
                $checkpoint->cname = $row['cname'];
                $checkpoint->completeaddress = $row['cadd'];
                $checkpoint->cgeolat = $row['cgeolat'];
                $checkpoint->cgeolong = $row['cgeolong'];
                $checkpoint->crad = $row['crad'];
                $checkpoint->vehicleid = $row['cvehicleid'];
                $checkpoints[] = $checkpoint;
            }
        }
        return $checkpoints;
    }

    public function get_checkpoint_from_chkmanagedata($vehicleid, $validation) {
        $checkpoints = $this->get_checkpoint_from_chkmanage($vehicleid, $validation);
        return $checkpoints;
    }

    function device_list_details($objReqDetails) {
        $userkey = $objReqDetails->userkey;
        $validation = $this->check_userkey($userkey);
        $today = date('Y-m-d H:i:s');
        $userid = $validation['userid'];
        $driverid = $validation['driverid'];
        $vehicleid = $validation['delivery_vehicleid'];
        $customerno = $validation['customerno'];

        $arr_p = array();
        $arr_p['status'] = "unsuccessful";

        $todaysdate = date('Y-m-d H:i:s');
        if ($validation['status'] == "successful") {
            if ($vehicleid == 0) {
                $arr_p['status'] = "unsuccessful";
                $arr_p['message'] = "Driver not mapped with vehicleno.";
            } else {
                $devices = $this->checkforvalidity($validation["customerno"]);
                $initday = 0;
                if (isset($devices)) {
                    foreach ($devices as $thisdevice) {

                        $days = $this->check_validity_login($thisdevice->expirydate, $thisdevice->today);
                        //echo "<br>".$thisdevice->expirydate."---------".$thisdevice->today."days".$days;
                        if ($days > 0) {
                            $initday = $days;
                        }
                    }
                }
                if ($initday > 0) {

                    $customerno = $validation["customerno"];
                    if ($customerno == 97) {
                        date_default_timezone_set(speedConstants::CAIRO_TIMEZONE);
                    }
                    $sql = "SELECT unit.humidity, vehicle.temp1_min,unit.is_buzzer,unit.digitalioupdated
                , unit.extra_digitalioupdated,unit.is_freeze,unit.mobiliser_flag,unit.is_mobiliser
                , customer.use_buzzer,customer.use_freeze,customer.use_immobiliser, vehicle.temp1_max
                , vehicle.temp2_min, vehicle.temp2_max, vehicle.kind, vehicle.groupid
                , unit.tempsen1, unit.tempsen2, unit.tempsen3, unit.tempsen4
                , unit.analog1, unit.analog2, unit.analog3, unit.analog4, customer.temp_sensors
                , vehicle.stoppage_transit_time, vehicle.stoppage_flag, customer.use_geolocation
                , user.customerno as customer_no,vehicle.vehicleid, vehicle.overspeed_limit, vehicle.extbatt
                , vehicle.vehicleno,vehicle.curspeed,unit.unitno, unit.digitalio, unit.acsensor, unit.is_ac_opp
                , driver.drivername,driver.driverphone, devices.lastupdated, devices.ignition, devices.powercut
                , devices.gsmstrength, devices.devicelat, devices.devicelong,ignitionalert.status as igstatus
                , ignitionalert.ignchgtime, vehicle.odometer as vehicleodometer
                , COALESCE(tripdetails.statusdate, tripdetail_history.statusdate) as loadingtime
                , COALESCE(tripdetails.odometer, tripdetail_history.odometer) as loadingodometer
                FROM vehicle INNER JOIN devices ON devices.uid = vehicle.uid
                INNER JOIN driver ON driver.driverid = vehicle.driverid
                INNER JOIN unit ON devices.uid = unit.uid
                INNER JOIN " . DB_PARENT . ".customer ON customer.customerno = vehicle.customerno
                INNER JOIN ignitionalert ON ignitionalert.vehicleid = vehicle.vehicleid
                INNER JOIN user ON vehicle.customerno = user.customerno
                LEFT OUTER JOIN tripdetails ON vehicle.vehicleid = tripdetails.vehicleid AND tripdetails.tripstatusid = 3 AND tripdetails.is_tripend = 0
                LEFT OUTER JOIN tripdetail_history ON vehicle.vehicleid = tripdetail_history.vehicleid AND tripdetail_history.tripstatusid = 3 AND tripdetail_history.is_tripend = 0
                WHERE vehicle.customerno =$customerno "
                            . "AND user.userkey =$userkey
                AND unit.trans_statusid NOT IN (10,22)
                AND vehicle.isdeleted=0
                AND driver.isdeleted=0
                and devices.lastupdated <> '0000-00-00 00:00:00'
                AND vehicle.vehicleid = $vehicleid "
                            . "ORDER BY devices.lastupdated DESC";
                    //echo $sql;
                    $record = $this->db->query($sql, __FILE__, __LINE__);
                    $json_p = array();
                    $x = 0;
                    while ($row = $this->db->fetch_array($record)) {
                        $json_p[$x]['lastupdated'] = $this->getduration1($row['lastupdated']);
                        $json_p[$x]['checkpoints'] = $this->get_checkpoints($row['vehicleid']);
                        $json_p[$x]['vehicleid'] = $row['vehicleid'];
                        $json_p[$x]['unitno'] = $row['unitno'];
                        $json_p[$x]['drivername'] = $row['drivername'];
                        $json_p[$x]['vehicleno'] = $row['vehicleno'];


                        $lat = $row['devicelat'];
                        $long = $row['devicelong'];
                        $json_p[$x]['devlat'] = $row['devicelat'];
                        $json_p[$x]['devlong'] = $row['devicelong'];
                        $usegeolocation = '';
                        $json_p[$x]['location'] = $this->location($lat, $long, $customerno, $usegeolocation);

                        $diff = $this->getduration(date('Y-m-d H:i:s'), $row["stoppage_transit_time"]);

                        if ($row["stoppage_flag"] == '1') {
                            $json_p[$x]['ignstatus'] = "Running since $diff";
                        } elseif ($row["stoppage_flag"] == '0') {
                            $json_p[$x]['ignstatus'] = "Idle since $diff";
                        }
                        $json_p[$x]['speed'] = $row['curspeed'];
                        $json_p[$x]['distance'] = (string) $this->distance($row['customer_no'], $row['unitno']);

                        if ($row['acsensor'] == 1) {
                            if ($row['digitalioupdated'] != '0000-00-00 00:00:00') {
                                $digitaldiff = $this->getduration_digitalio($row['digitalioupdated'], $row['lastupdated']);
                            }

                            if ($row['digitalio'] == 0) {
                                if ($row["is_ac_opp"] == 0) {
                                    if ($row['digitalioupdated'] != '0000-00-00 00:00:00') {
                                        $json_p[$x]['digital_status'] = "ON Since" . $digitaldiff;
                                    } else {
                                        $json_p[$x]['digital_status'] = "ON";
                                    }
                                } else {
                                    if ($row['digitalioupdated'] != '0000-00-00 00:00:00') {
                                        $json_p[$x]['digital_status'] = "OFF Since" . $digitaldiff;
                                    } else {
                                        $json_p[$x]['digital_status'] = "OFF";
                                    }
                                }
                            } else {
                                if ($row["is_ac_opp"] == 0) {

                                    if ($row['digitalioupdated'] != '0000-00-00 00:00:00') {
                                        $json_p[$x]['digital_status'] = "OFF Since" . $digitaldiff;
                                    } else {
                                        $json_p[$x]['digital_status'] = "OFF";
                                    }
                                } else {
                                    if ($row['digitalioupdated'] != '0000-00-00 00:00:00') {
                                        $json_p[$x]['digital_status'] = "ON Since" . $digitaldiff;
                                    } else {
                                        $json_p[$x]['digital_status'] = "ON";
                                    }
                                }
                            }
                        } else {
                            $json_p[$x]['digital_status'] = "Not Active";
                        }
                        $json_p[$x]['cust_digital'] = $this->getCustomizeName($customerno, 1, 'Digital');
                        $json_p[$x]['extbatt'] = round($row['extbatt'] / 100, 2);

                        $json_p[$x]['powercut'] = $row['powercut'];
                        $json_p[$x]['simsignal'] = round($row['gsmstrength'] / 31 * 100);
                        $json_p[$x]['overspeed_limit'] = $row['overspeed_limit'];
                        // Temperature Sensor
                        $json_p[$x]['analog_sensors'] = $row['temp_sensors'];
                        $json_p[$x]['analog1'] = 'Not Active';
                        $json_p[$x]['analog2'] = 'Not Active';
                        $json_p[$x]['analog3'] = 'Not Active';
                        $json_p[$x]['analog4'] = 'Not Active';

                        $json_p[$x]['cust_analog1'] = 'Not Active';
                        $json_p[$x]['cust_analog2'] = 'Not Active';
                        $json_p[$x]['cust_analog3'] = 'Not Active';
                        $json_p[$x]['cust_analog4'] = 'Not Active';

                        //$json_p[$x]['cust_analog3'] = $row['cust_analog3'];
                        //$json_p[$x]['cust_analog4'] = $row['cust_analog4'];

                        if ($row['temp_sensors'] == '1') {
                            if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                                if ($row['humidity'] != '0') {
                                    $json_p[$x]['analog1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                                    $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                                } else {
                                    $json_p[$x]['analog1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                                    $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                                }
                            } else {
                                $json_p[$x]['analog1'] = 'Error';
                            }
                            $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                        }

                        if ($row['temp_sensors'] == '2') {
                            if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                                if ($row['humidity'] != '0') {
                                    $json_p[$x]['analog1'] = $this->gethumidity($row['analog' . $row['tempsen1']]);
                                    $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                                } else {
                                    $json_p[$x]['analog1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                                    $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                                }
                            } else {
                                $json_p[$x]['analog1'] = 'Error';
                            }
                            $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');

                            if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                                $json_p[$x]['analog2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            } else {
                                $json_p[$x]['analog2'] = 'Error';
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            }
                        }

                        if ($row['humidity'] != '0') {
                            $json_p[$x]['analog2'] = $this->gethumidity($row['analog' . $row['humidity']]);
                            $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                        }

                        if (isset($row['temp_sensors']) && $row['temp_sensors'] == 3) {
                            if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                                $json_p[$x]['analog1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            } else {
                                $json_p[$x]['analog1'] = 'Error';
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            }
                            if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                                $json_p[$x]['analog2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            } else {
                                $json_p[$x]['analog2'] = 'Error';
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            }
                            if ($row['tempsen3'] != '0' && $row['analog' . $row['tempsen3']] != '0') {
                                $json_p[$x]['analog3'] = $this->gettemp($row['analog' . $row['tempsen3']]);
                                $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 22, 'Analog3');
                            } else {
                                $json_p[$x]['analog3'] = 'Error';
                                $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 22, 'Analog3');
                            }
                        }

                        if (isset($row['temp_sensors']) && $row['temp_sensors'] == 4) {
                            if ($row['tempsen1'] != '0' && $row['analog' . $row['tempsen1']] != '0') {
                                $json_p[$x]['analog1'] = $this->gettemp($row['analog' . $row['tempsen1']]);
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            } else {
                                $json_p[$x]['analog1'] = 'Error';
                                $json_p[$x]['cust_analog1'] = $this->getCustomizeName($customerno, 20, 'Analog1');
                            }

                            if ($row['tempsen2'] != '0' && $row['analog' . $row['tempsen2']] != '0') {
                                $json_p[$x]['analog2'] = $this->gettemp($row['analog' . $row['tempsen2']]);
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            } else {
                                $json_p[$x]['analog2'] = 'Error';
                                $json_p[$x]['cust_analog2'] = $this->getCustomizeName($customerno, 21, 'Analog2');
                            }

                            if ($row['tempsen3'] != '0' && $row['analog' . $row['tempsen3']] != '0') {
                                $json_p[$x]['analog3'] = $this->gettemp($row['analog' . $row['tempsen3']]);
                                $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 2, 'Analog3');
                            } else {
                                $json_p[$x]['analog3'] = 'Error';
                                $json_p[$x]['cust_analog3'] = $this->getCustomizeName($customerno, 22, 'Analog3');
                            }

                            if ($row['tempsen4'] != '0' && $row['analog' . $row['tempsen4']] != '0') {
                                $json_p[$x]['analog4'] = $this->gettemp($row['analog' . $row['tempsen4']]);
                                $json_p[$x]['cust_analog4'] = $this->getCustomizeName($customerno, 23, 'Analog4');
                            } else {
                                $json_p[$x]['analog4'] = 'Error';
                                $json_p[$x]['cust_analog4'] = $this->getCustomizeName($customerno, 23, 'Analog4');
                            }
                        }

                        ///use buzzer  or use mobilizer or freeze  - start
                        $use_buzzer = $row['use_buzzer'];
                        $immobiliser = $row['use_immobiliser'];

                        if ($use_buzzer == 1 && $row['is_buzzer'] == 1) {
                            $buzzer_status = 1; //unit has buzzer
                        } elseif ($use_buzzer == 1 && $row['is_buzzer'] == 0) {
                            $buzzer_status = 0; //unit has NO buzzer
                        } else {
                            $buzzer_status = -1; //no buzzer
                        }

                        if ($row['use_immobiliser'] == 1 && $row['is_mobiliser'] == 0) {
                            //gray  green - disable
                            //Immobilizer Not Installed In Your Vehicle. * Note: For further information please contact an elixir.
                            $mobiliser_status = -1;
                        } elseif ($row['use_immobiliser'] == 1 && $row['is_mobiliser'] == 1 && $row['mobiliser_flag'] == 0) {
                            //green - on  //   Would You Wish To Start The Vehicle ?
                            $mobiliser_status = 0;
                        } elseif ($row['use_immobiliser'] == 1 && $row['is_mobiliser'] == 1 && $row['mobiliser_flag'] == 1) {
                            //red - stop
                            $mobiliser_status = 1; //stop
                        } else {
                            $mobiliser_status = -1; //no mobiliser
                        }

                        if ($row['use_freeze'] == 1 && $row['is_freeze'] == 1) {
                            $freeze_status = 1; //isfreezed
                        } elseif ($row['use_freeze'] == 1 && $row['is_freeze'] == 0) {
                            $freeze_status = 0; //isunfreezed
                        } else {
                            $freeze_status = -1; //freeze not enabled for customer
                        }

                        $json_p[$x]['buzzerstatus'] = $buzzer_status;
                        $json_p[$x]['mobilizerstatus'] = $mobiliser_status;
                        $json_p[$x]['freezestatus'] = $freeze_status;

                        ///use buzzer  or use mobilizer  - end
                        $tripdata = $this->gettripdetails($row['vehicleid'], $customerno);
                        $json_p[$x]['temprange'] = 'Not Defined';
                        $json_p[$x]['triplogno'] = 'Not Defined';
                        $json_p[$x]['status'] = 'Not Defined';
                        $json_p[$x]['buddist'] = 'Not Defined';
                        $json_p[$x]['budhours'] = 'Not Defined';
                        $json_p[$x]['actualhrs'] = 'Not Defined';
                        $json_p[$x]['actualkms'] = 'Not Defined';
                        $json_p[$x]['consignor'] = 'Not Defined';
                        $json_p[$x]['consignee'] = 'Not Defined';
                        $json_p[$x]['billingparty'] = 'Not Defined';
                        $json_p[$x]['tripdrivername'] = 'Not Defined';
                        $json_p[$x]['tripdriverno'] = 'Not Defined';
                        $json_p[$x]['routename'] = 'Not Defined';
                        $json_p[$x]['remark'] = 'Not Defined';
                        $json_p[$x]['loadingtime'] = 'Not Defined';
                        if (isset($tripdata)) {

                            if (isset($tripdata['is_tripend']) && $tripdata['is_tripend'] == 0) {

                                $closedtripenddetails = $this->closedtripdetails_end($tripdata['tripid'], $customerno);
                                $closedtripstartdetails = $this->closedtripdetails_start($tripdata['tripid'], $customerno);

                                $tripstart_date = $closedtripstartdetails[0]['tripstart_date'];
                                $tripend_date = $closedtripenddetails[0]['tripend_date'];
                                if (empty($tripend_date)) {
                                    $tripend_date = date('Y-m-d');
                                } else {
                                    $tripend_date = $closedtripenddetails[0]['tripend_date'];
                                }

                                if (empty($tripstart_date) && empty($tripend_date)) {

                                    $getstart_odometer = $getend_odometer = '';
                                    $startododate = trim(substr($tripstart_date, 0, 11));
                                    $unitno = $this->getunitno($tripdata['vehicleid']);
                                    $strlocation = "../../../customer/$customerno/unitno/$unitno/sqlite/$startododate.sqlite";
                                    $getstart_odometer = $this->getOdometer($strlocation, $tripstart_date);

                                    $endododate = trim(substr($tripend_date, 0, 11));
                                    $endlocation = "../../../customer/$customerno/unitno/$unitno/sqlite/$endododate.sqlite";
                                    $today = date('Y-m-d');
                                    if (strtotime($tripend_date) == strtotime($today)) {
                                        $getend_odometer = $this->getodometerform_mysql($tripdata['vehicleid'], $customerno);
                                    } else {
                                        $getend_odometer = $this->getOdometer($endlocation, $tripend_date);
                                    }
                                    $tripend_odometer = 0;
                                    $tripstart_odometer = 0;
                                    $firstodometer = 0;
                                    $lastodometer = 0;
                                    $lastodometermax = 0;
                                    $lastodometer = $getend_odometer; // last odometer
                                    $firstodometer = $getstart_odometer; // first odometer
                                    if ($lastodometer < $firstodometer) {
                                        $days = $this->gendays($tripstart_date, $tripend_date);
                                        if (count($days) > 0) {
                                            $lastodometerarr = array();
                                            foreach ($days as $day) {
                                                $lastodometerarr[] = $this->GetOdometer_Max($day, $unitno, $customerno);
                                            }
                                            $lastodometermax = max($lastodometerarr);
                                        }
                                        $lastodometer = $lastodometermax + $lastodometer;
                                    }
                                    $totaldistance = $lastodometer - $firstodometer;
                                    $actualhrs = round((strtotime($tripstart_date) - strtotime($tripend_date)) / (60 * 60));
                                    if ($totaldistance != 0) {
                                        $res = $totaldistance / 1000;
                                    } else {
                                        $res = 0;
                                    }

                                    ////////////////Estimated Time calculate///////////////////////
                                    $estimated_time = 0;
                                    $estimated_time = $tripdata['budgetedhrs'] - $actualhrs;
                                    ////////////////Estimated Time calculate///////////////////////

                                    $json_p[$x]['temprange'] = $tripdata['temprange'];
                                    $json_p[$x]['triplogno'] = $tripdata['triplogno'];
                                    $json_p[$x]['status'] = $tripdata['status'];
                                    $actualhrs = $estimated_time;
                                    $actualkms = $res;
                                    $json_p[$x]['buddist'] = $tripdata['budgetedkms'] . $actualkms;
                                    $json_p[$x]['budhours'] = $tripdata['budgetedhrs'] . $actualhrs;
                                    //$json_p[$x]['actualhrs'] = $tripdata['actualhrs'];
                                    //$json_p[$x]['actualkms'] = (round($tripdata['vehicleodometer'] - $tripdata['odometer'] / 100, 2));
                                    //$json_p[$x]['actualkms'] = $tripdata['actualkms'];
                                    $json_p[$x]['consignor'] = $tripdata['consignor'];
                                    $json_p[$x]['consignee'] = $tripdata['consignee'];
                                    $json_p[$x]['billingparty'] = $tripdata['billingparty'];
                                    $json_p[$x]['tripdrivername'] = $tripdata['drivername'];
                                    $json_p[$x]['tripdriverno'] = $tripdata['drivermobile2'] . ',' . $tripdata['drivermobile1'];
                                    $json_p[$x]['routename'] = $tripdata['routename'];
                                    $json_p[$x]['remark'] = $tripdata['remark'];

                                    $json_p[$x]['loadingtime'] = ($row['loadingtime'] != null && $row['loadingtime'] != '') ? date("d-m-Y, h:i a", strtotime($row['loadingtime'])) : 'Not Defined';
                                }
                            }
                        }
                        $x++;
                    }

                    $arr_p['status'] = "successful";
                    $arr_p['result'] = $json_p;
                    $this->update_push_android_chk($userkey, 0);
                } else {
                    $arr_p['status'] = "expired";
                }
            }
        } else {
            $arr_p['status'] = "unsuccessful";
        }


        return $arr_p;
    }

// </editor-fold>
//
// <editor-fold defaultstate="collapsed" desc="Utility functions">
    function sendMail(array $arrToMailIds, $strCCMailIds, $strBCCMailIds, $subject, $message, $attachmentFilePath, $attachmentFileName) {
        include_once "../../cron/class.phpmailer.php";
        $isEmailSent = 0;
        $completeFilePath = '';
        if ($attachmentFilePath != '' && $attachmentFileName != '') {
            $completeFilePath = $attachmentFilePath . "/" . $attachmentFileName;
        }

        $mail = new PHPMailer();
        $mail->IsMail();

        $mail->ClearAddresses();
        $mail->ClearAllRecipients();
        $mail->ClearAttachments();
        $mail->ClearCustomHeaders();

        if (!empty($arrToMailIds)) {
            foreach ($arrToMailIds as $mailto) {
                $mail->AddAddress($mailto);
            }
            if (!empty($strCCMailIds)) {
                $mail->AddCustomHeader("CC: " . $strCCMailIds);
            }

            if (!empty($strBCCMailIds)) {
                $mail->AddCustomHeader("BCC: " . $strBCCMailIds);
            }
        }
        $mail->From = "noreply@elixiatech.com";
        $mail->FromName = "Elixia Speed";
        $mail->Sender = "noreply@elixiatech.com";
//$mail->AddReplyTo($from,"Elixia Speed");
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->IsHtml(true);
        if ($completeFilePath != '' && $attachmentFileName != '') {
            $mail->AddAttachment($completeFilePath, $attachmentFileName);
        }
//SEND Mail

        if ($mail->Send()) {
            $isEmailSent = 1; // or use booleans here
        }
        return $isEmailSent;
    }

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

    function gendays($STdate, $EDdate) {
        $TOTALDAYS = Array();
        $STdate = date("Y-m-d", strtotime($STdate));
        $EDdate = date("Y-m-d", strtotime($EDdate));
        while (strtotime($STdate) <= strtotime($EDdate)) {
            $TOTALDAYS[] = $STdate;
            $STdate = date("Y-m-d", strtotime($STdate . ' + 1 day'));
        }
        return $TOTALDAYS;
    }

//</editor-fold>
}

?>
